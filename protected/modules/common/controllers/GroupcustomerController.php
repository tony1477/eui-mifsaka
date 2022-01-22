<?php
class GroupcustomerController extends Controller {
	public $menuname = 'groupcustomer';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$groupname = isset ($_POST['groupname']) ? $_POST['groupname'] : '';
		$groupname = isset ($_GET['q']) ? $_GET['q'] : $groupname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'groupcustomerid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('groupcustomer t')
				->where('(groupname like :groupname)',
				array(':groupname'=>'%'.$groupname.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('groupcustomer t')
				->where('((groupname like :groupname)) and t.recordstatus=1',
												array(':groupname'=>'%'.$groupname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('groupcustomer t')
				->where('(groupname like :groupname)',
												array(':groupname'=>'%'.$groupname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('groupcustomer t')
				->where('((groupname like :groupname)) and t.recordstatus=1',
												array(':groupname'=>'%'.$groupname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'groupcustomerid'=>$data['groupcustomerid'],
				'groupname'=>$data['groupname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertgroupcustomer(:vgroupname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updategroupcustomer(:vid,:vgroupname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vgroupname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-groupcustomer"]["name"]);
		if (move_uploaded_file($_FILES["file-groupcustomer"]["tmp_name"], $target_file)) {
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
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$groupname,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['groupcustomerid'])?$_POST['groupcustomerid']:''),$_POST['groupname'],$_POST['recordstatus']));
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
				$sql = 'call Purgegroupcustomer(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
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
	  $sql = "select groupcustomerid,groupname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from groupcustomer a ";
		$groupcustomerid = filter_input(INPUT_GET,'groupcustomerid');
		$groupname = filter_input(INPUT_GET,'groupname');
		$sql .= " where coalesce(a.groupcustomerid,'') like '%".$groupcustomerid."%' 
			and coalesce(a.groupname,'') like '%".$groupname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.groupcustomerid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by groupname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('groupcustomer');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('groupcustomerid'),
																	GetCatalog('groupname'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['groupcustomerid'],$row1['groupname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='groupcustomer';
		parent::actionDownxls();
		$sql = "select groupcustomerid,groupname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from groupcustomer a ";
		$groupcustomerid = filter_input(INPUT_GET,'groupcustomerid');
		$groupname = filter_input(INPUT_GET,'groupname');
		$sql .= " where coalesce(a.groupcustomerid,'') like '%".$groupcustomerid."%' 
			and coalesce(a.groupname,'') like '%".$groupname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.groupcustomerid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by groupname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('groupcustomerid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('groupname'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['groupcustomerid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['groupname'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}