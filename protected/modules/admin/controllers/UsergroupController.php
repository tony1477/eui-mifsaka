<?php
class UsergroupController extends Controller {
	public $menuname = 'usergroup';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$usergroupid = isset ($_POST['usergroupid']) ? $_POST['usergroupid'] : '';
		$username = isset ($_POST['username']) ? $_POST['username'] : '';
		$groupname = isset ($_POST['groupname']) ? $_POST['groupname'] : '';
		$usergroupid = isset ($_GET['q']) ? $_GET['q'] : $usergroupid;
		$username = isset ($_GET['q']) ? $_GET['q'] : $username;
		$groupname = isset ($_GET['q']) ? $_GET['q'] : $groupname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'usergroupid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows ;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('usergroup t')
			->join('useraccess p', 'p.useraccessid=t.useraccessid')
			->join('groupaccess q', 'q.groupaccessid=t.groupaccessid')
			->where('(usergroupid like :usergroupid) and (p.username like :username) and (q.groupname like :groupname)',
											array(':usergroupid'=>'%'.$usergroupid.'%',':username'=>'%'.$username.'%',':groupname'=>'%'.$groupname.'%'))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('usergroup t')
			->join('useraccess p', 'p.useraccessid=t.useraccessid')
			->join('groupaccess q', 'q.groupaccessid=t.groupaccessid')
			->where('(usergroupid like :usergroupid) and (p.username like :username) and (q.groupname like :groupname)',
											array(':usergroupid'=>'%'.$usergroupid.'%',':username'=>'%'.$username.'%',':groupname'=>'%'.$groupname.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'usergroupid'=>$data['usergroupid'],
				'useraccessid'=>$data['useraccessid'],
				'username'=>$data['username'],
				'groupaccessid'=>$data['groupaccessid'],
				'groupname'=>$data['groupname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertusergroup(:vuseraccessid,:vgroupaccessid,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateusergroup(:vid,:vuseraccessid,:vgroupaccessid,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vuseraccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vgroupaccessid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-usergroup"]["name"]);
		if (move_uploaded_file($_FILES["file-usergroup"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$username = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$userid = Yii::app()->db->createCommand("select useraccessid from useraccess where username = '".$username."'")->queryScalar();
					$groupname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$groupid = Yii::app()->db->createCommand("select groupaccessid from groupaccess where groupname = '".$groupname."'")->queryScalar();
					$this->ModifyData($connection,array($id,$userid,$groupid));
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['usergroupid'])?$_POST['usergroupid']:''),$_POST['useraccessid'],$_POST['groupaccessid']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeusergroup(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select a.usergroupid,b.username,c.groupname
						from usergroup a
						join useraccess b on b.useraccessid = a.useraccessid
						join groupaccess c on c.groupaccessid = a.groupaccessid ";
		$usergroupid = filter_input(INPUT_GET,'usergroupid');
		$username = filter_input(INPUT_GET,'username');
		$groupname = filter_input(INPUT_GET,'groupname');
		$sql .= " where coalesce(a.usergroupid,'') like '%".$usergroupid."%' 
			and coalesce(b.username,'') like '%".$username."%'
			and coalesce(c.groupname,'') like '%".$groupname."%'
			";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " where a.usergroupid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by username asc,groupname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('usergroup');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('usergroupid'),
																	GetCatalog('username'),
																	GetCatalog('groupname'));
		$this->pdf->setwidths(array(15,90,90));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['usergroupid'],$row1['username'],$row1['groupname']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='usergroup';
		parent::actionDownxls();
		$sql = "select a.usergroupid,b.username,c.groupname
						from usergroup a
						join useraccess b on b.useraccessid = a.useraccessid
						join groupaccess c on c.groupaccessid = a.groupaccessid ";
		$usergroupid = filter_input(INPUT_GET,'usergroupid');
		$username = filter_input(INPUT_GET,'username');
		$groupname = filter_input(INPUT_GET,'groupname');
		$sql .= " where coalesce(a.usergroupid,'') like '%".$usergroupid."%' 
			and coalesce(b.username,'') like '%".$username."%'
			and coalesce(c.groupname,'') like '%".$groupname."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . "where a.usergroupid in (".$_GET['id'].")";
		}
		$sql = $sql . "order by username asc,groupname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;	
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('usergroupid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('username'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('groupname'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['usergroupid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['username'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['groupname']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}