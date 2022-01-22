<?php
class GroupmenuauthController extends Controller {
	public $menuname = 'groupmenuauth';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$groupmenuauthid = isset ($_POST['groupmenuauthid']) ? $_POST['groupmenuauthid'] : '';
		$groupname = isset ($_POST['groupname']) ? $_POST['groupname'] : '';
		$menuobject = isset ($_POST['menuobject']) ? $_POST['menuobject'] : '';
		$menuvalueid = isset ($_POST['menuvalueid']) ? $_POST['menuvalueid'] : '';
		$groupmenuauthid = isset ($_GET['q']) ? $_GET['q'] : $groupmenuauthid;
		$groupname = isset ($_GET['q']) ? $_GET['q'] : $groupname;
		$menuobject = isset ($_GET['q']) ? $_GET['q'] : $menuobject;
		$menuvalueid = isset ($_GET['q']) ? $_GET['q'] : $menuvalueid;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'groupmenuauthid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('groupmenuauth t')
			->leftjoin('groupaccess p','t.groupaccessid=p.groupaccessid')
			->leftjoin('menuauth q','t.menuauthid=q.menuauthid')
			->where('(groupmenuauthid like :groupmenuauthid) and (p.groupname like :groupname) and (q.menuobject like :menuobject) and (menuvalueid like :menuvalueid)',
				array(':groupmenuauthid'=>'%'.$groupmenuauthid.'%',':groupname'=>'%'.$groupname.'%',':menuobject'=>'%'.$menuobject.'%',':menuvalueid'=>'%'.$menuvalueid.'%'))			
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('groupmenuauth t')
			->leftjoin('groupaccess p','t.groupaccessid=p.groupaccessid')
			->leftjoin('menuauth q','t.menuauthid=q.menuauthid')
			->where('(groupmenuauthid like :groupmenuauthid) and (p.groupname like :groupname) and (q.menuobject like :menuobject) and (menuvalueid like :menuvalueid)',
				array(':groupmenuauthid'=>'%'.$groupmenuauthid.'%',':groupname'=>'%'.$groupname.'%',':menuobject'=>'%'.$menuobject.'%',':menuvalueid'=>'%'.$menuvalueid.'%'))			
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'groupmenuauthid'=>$data['groupmenuauthid'],
				'groupaccessid'=>$data['groupaccessid'],
				'groupname'=>$data['groupname'],
				'menuauthid'=>$data['menuauthid'],
				'menuobject'=>$data['menuobject'],
				'menuvalueid'=>$data['menuvalueid'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertgroupmenuauth(:vgroupaccessid,:vmenuauthid,:vmenuvalueid,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updategroupmenuauth(:vid,:vgroupaccessid,:vmenuauthid,:vmenuvalueid,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vgroupaccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmenuauthid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmenuvalueid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-groupmenuauth"]["name"]);
		if (move_uploaded_file($_FILES["file-groupmenuauth"]["tmp_name"], $target_file)) {
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
					$groupname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$groupid = Yii::app()->db->createCommand("select groupaccessid from groupaccess where groupname = '".$groupname."'")->queryScalar();
					$menuobject = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$menuauthid = Yii::app()->db->createCommand("select menuauthid from menuauth where menuobject = '".$menuobject."'")->queryScalar();
					$menuvalueid = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$groupid,$menuauthid,$menuvalueid));
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e)
			{
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
			$this->ModifyData($connection,array((isset($_POST['groupmenuauthid'])?$_POST['groupmenuauthid']:''),$_POST['groupaccessid'],$_POST['menuauthid'],$_POST['menuvalueid']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e)
		{
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
				$sql = 'call Purgegroupmenuauth(:vid,:vcreatedby)';
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
	  $sql = "select a.groupmenuauthid,b.groupname as groupaccess,c.menuobject,a.menuvalueid
						from groupmenuauth a
						left join groupaccess b on b.groupaccessid = a.groupaccessid
						left join menuauth c on c.menuauthid = a.menuauthid ";
		$groupmenuauthid = filter_input(INPUT_GET,'groupmenuauthid');
		$groupname = filter_input(INPUT_GET,'groupname');
		$menuobject = filter_input(INPUT_GET,'menuobject');
		$menuvalueid = filter_input(INPUT_GET,'menuvalueid');
		$sql .= " where coalesce(a.groupmenuauthid,'') like '%".$groupmenuauthid."%' 
			and coalesce(b.groupname,'') like '%".$groupname."%'
			and coalesce(c.menuobject,'') like '%".$menuobject."%'
			and coalesce(a.menuvalueid,'') like '%".$menuvalueid."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.groupmenuauthid in (".$_GET['id'].")";
		}
		$sql = $sql . "order by groupname asc,menuobject asc,menuvalueid asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('groupmenuauth');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('groupmenuauthid'),
																	GetCatalog('groupaccess'),
																	GetCatalog('menuobject'),
																	GetCatalog('menuvalueid'));
		$this->pdf->setwidths(array(20,90,55,30));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['groupmenuauthid'],$row1['groupaccess'],$row1['menuobject'],$row1['menuvalueid']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='groupmenuauth';
		parent::actionDownxls();
		$sql = "select a.groupmenuauthid,b.groupname as groupaccess,c.menuobject,a.menuvalueid
						from groupmenuauth a
						left join groupaccess b on b.groupaccessid = a.groupaccessid
						left join menuauth c on c.menuauthid = a.menuauthid ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.groupmenuauthid in (".$_GET['id'].")";
		}
		$sql = $sql . "order by groupname asc,menuobject asc,menuvalueid asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('groupmenuauthid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('groupaccess'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('menuobject'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('menuvalueid'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['groupmenuauthid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['groupaccess'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['menuobject'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['menuvalueid']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}