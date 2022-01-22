<?php
class OwnershipController extends Controller {
	public $menuname = 'ownership';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$ownershipid = isset ($_POST['ownershipid']) ? $_POST['ownershipid'] : '';
		$ownershipname = isset ($_POST['ownershipname']) ? $_POST['ownershipname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$ownershipid = isset ($_GET['q']) ? $_GET['q'] : $ownershipid;
		$ownershipname = isset ($_GET['q']) ? $_GET['q'] : $ownershipname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'ownershipid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();	
		// result
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('ownership t')
				->where('(ownershipname like :ownershipname)',
												array(':ownershipname'=>'%'.$ownershipname.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('ownership t')
				->where('((ownershipname like :ownershipname)) and t.recordstatus=1',
												array(':ownershipname'=>'%'.$ownershipname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('ownership t')
				->where('(ownershipname like :ownershipname)',
					array(':ownershipname'=>'%'.$ownershipname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('ownership t')
				->where('((ownershipname like :ownershipname)) and t.recordstatus=1',
												array(':ownershipname'=>'%'.$ownershipname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}		
		foreach($cmd as $data) {	
			$row[] = array(
				'ownershipid'=>$data['ownershipid'],
				'ownershipname'=>$data['ownershipname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertownership(:vownershipname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateownership(:vid,:vownershipname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $_POST['ownershipid']);
		}
		$command->bindvalue(':vownershipname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-ownership"]["name"]);
		if (move_uploaded_file($_FILES["file-ownership"]["tmp_name"], $target_file)) {
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
					$ownershipname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$ownershipname,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['ownershipid'])?$_POST['ownershipid']:''),$_POST['ownershipname'],$_POST['recordstatus']));
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
				$sql = 'call Purgeownership(:vid,:vcreatedby)';
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
		//masukkan perintah download
	  $sql = "select ownershipid,ownershipname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from ownership a ";
		$ownershipid = filter_input(INPUT_GET,'ownershipid');
		$ownershipname = filter_input(INPUT_GET,'ownershipname');
		$sql .= " where coalesce(a.ownershipid,'') like '%".$ownershipid."%' 
			and coalesce(a.ownershipname,'') like '%".$ownershipname."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.ownershipid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by ownershipname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('ownership');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('ownershipid'),
																	GetCatalog('ownershipname'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['ownershipid'],$row1['ownershipname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='ownership';
		parent::actionDownxls();
		$sql = "select ownershipid,ownershipname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from ownership a ";
		$ownershipid = filter_input(INPUT_GET,'ownershipid');
		$ownershipname = filter_input(INPUT_GET,'ownershipname');
		$sql .= " where coalesce(a.ownershipid,'') like '%".$ownershipid."%' 
			and coalesce(a.ownershipname,'') like '%".$ownershipname."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.ownershipid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by ownershipname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['ownershipid'])
				->setCellValueByColumnAndRow(1,$i,$row1['ownershipname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}