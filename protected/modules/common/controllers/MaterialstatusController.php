<?php
class MaterialstatusController extends Controller {
	public $menuname = 'materialstatus';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$materialstatusid = isset ($_POST['materialstatusid']) ? $_POST['materialstatusid'] : '';
		$materialstatusname = isset ($_POST['materialstatusname']) ? $_POST['materialstatusname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$materialstatusid = isset ($_GET['q']) ? $_GET['q'] : $materialstatusid;
		$materialstatusname = isset ($_GET['q']) ? $_GET['q'] : $materialstatusname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'materialstatusid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
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
				->from('materialstatus t')
				->where('(materialstatusname like :materialstatusname)',
												array(':materialstatusname'=>'%'.$materialstatusname.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('materialstatus t')
				->where('((materialstatusname like :materialstatusname)) and t.recordstatus=1',
												array(':materialstatusname'=>'%'.$materialstatusname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('materialstatus t')
				->where('(materialstatusname like :materialstatusname)',
												array(':materialstatusname'=>'%'.$materialstatusname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('materialstatus t')
				->where('((materialstatusname like :materialstatusname)) and t.recordstatus=1',
												array(':materialstatusname'=>'%'.$materialstatusname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'materialstatusid'=>$data['materialstatusid'],
				'materialstatusname'=>$data['materialstatusname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmaterialstatus(:vmaterialstatusname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updatematerialstatus(:vid,:vmaterialstatusname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmaterialstatusname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-materialstatus"]["name"]);
		if (move_uploaded_file($_FILES["file-materialstatus"]["tmp_name"], $target_file)) {
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
					$materialstatusname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$materialstatusname,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['materialstatusid'])?$_POST['materialstatusid']:''),$_POST['materialstatusname'],$_POST['recordstatus']));
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
				$sql = 'call Purgematerialstatus(:vid,:vcreatedby)';
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
	  $sql = "select materialstatusid,materialstatusname,recordstatus
				from materialstatus a ";
		$materialstatusid = filter_input(INPUT_GET,'materialstatusid');
		$materialstatusname = filter_input(INPUT_GET,'materialstatusname');
		$sql .= " where coalesce(a.materialstatusid,'') like '%".$materialstatusid."%' 
			and coalesce(a.materialstatusname,'') like '%".$materialstatusname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.materialstatusid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('materialstatus');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L');
		$this->pdf->colheader = array(GetCatalog('materialstatusname'),
                GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['materialstatusname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}	
	public function actionDownxls() {
		parent::actionDownload();
		$sql = "select materialstatusid,materialstatusname,recordstatus
				from materialstatus a ";
		$materialstatusid = filter_input(INPUT_GET,'materialstatusid');
		$materialstatusname = filter_input(INPUT_GET,'materialstatusname');
		$sql .= " where coalesce(a.materialstatusid,'') like '%".$materialstatusid."%' 
			and coalesce(a.materialstatusname,'') like '%".$materialstatusname."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.materialstatusid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i=1;
		$this->phpexcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,1,GetCatalog('materialstatusname'))
			->setCellValueByColumnAndRow(1,1,GetCatalog('recordstatus'));		
		foreach($dataReader as $row1)
		{
			$this->phpexcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['materialstatusname'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['recordstatus']);		
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}