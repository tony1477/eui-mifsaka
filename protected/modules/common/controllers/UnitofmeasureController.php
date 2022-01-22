<?php
class UnitofmeasureController extends Controller {
	public $menuname = 'unitofmeasure';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			if(isset($_GET['productplant']))
			echo $this->searchproductplant();
		else
			$this->renderPartial('index',array());
	}
	public function search()
	{
		header("Content-Type: application/json");
		$unitofmeasureid = isset ($_POST['unitofmeasureid']) ? $_POST['unitofmeasureid'] : '';
		$uomcode = isset ($_POST['uomcode']) ? $_POST['uomcode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$unitofmeasureid = isset ($_GET['q']) ? $_GET['q'] : $unitofmeasureid;
		$uomcode = isset ($_GET['q']) ? $_GET['q'] : $uomcode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'unitofmeasureid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('unitofmeasure t')
				->where('((uomcode like :uomcode) or (description like :description)) and t.recordstatus=1',
					array(':uomcode'=>'%'.$uomcode.'%',':description'=>'%'.$description.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('unitofmeasure t')
				->where('(uomcode like :uomcode) and (description like :description)',
					array(':uomcode'=>'%'.$uomcode.'%',':description'=>'%'.$description.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('unitofmeasure t')
				->where('((uomcode like :uomcode) or (description like :description)) and t.recordstatus=1',
							array(':uomcode'=>'%'.$uomcode.'%',':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('unitofmeasure t')
				->where('(uomcode like :uomcode) and (description like :description)',
						array(':uomcode'=>'%'.$uomcode.'%',':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'unitofmeasureid'=>$data['unitofmeasureid'],
				'uomcode'=>$data['uomcode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchproductplant() {
		header("Content-Type: application/json");
		$unitofmeasureid = isset ($_REQUEST['unitofmeasureid']) ? $_REQUEST['unitofmeasureid'] : '';
		$uomcode = isset ($_REQUEST['uomcode']) ? $_REQUEST['uomcode'] : '';
		$productid = isset ($_REQUEST['productid']) ? $_REQUEST['productid'] : '';
		$description = isset ($_REQUEST['description']) ? $_REQUEST['description'] : '';
		$recordstatus = isset ($_REQUEST['recordstatus']) ? $_REQUEST['recordstatus'] : '';
		$unitofmeasureid = isset ($_GET['q']) ? $_GET['q'] : $unitofmeasureid;
		$uomcode = isset ($_GET['q']) ? $_GET['q'] : $uomcode;
		$productid = isset ($_POST['q']) ? $_POST['q'] : $productid;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'unitofmeasureid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->selectdistinct('t.*')
			->from('unitofmeasure t')
			->join('productplant a','a.unitofissue = t.unitofmeasureid')
			->where('((uomcode like :uomcode) or (description like :description)) and (productid = :productid)',
				array(':uomcode'=>'%'.$uomcode.'%',
					':description'=>'%'.$description.'%',
					':productid'=>$productid
				))
			->order($sort.' '.$order)
			->queryAll();
		$result['total'] = count($cmd);
		foreach($cmd as $data) {	
			$row[] = array(
				'unitofmeasureid'=>$data['unitofmeasureid'],
				'uomcode'=>$data['uomcode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call InsertUOM(:vuomcode,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call UpdateUOM(:vid,:vuomcode,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vuomcode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-unitofmeasure"]["name"]);
		if (move_uploaded_file($_FILES["file-unitofmeasure"]["tmp_name"], $target_file)) {
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
					$uomcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$languagename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['unitofmeasureid'])?$_POST['unitofmeasureid']:''),$_POST['uomcode'],$_POST['description'],$_POST['recordstatus']));
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
				$sql = 'call Purgeunitofmeasure(:vid,:vcreatedby)';
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
	  $sql = "select unitofmeasureid,uomcode,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from unitofmeasure a ";
		$unitofmeasureid = filter_input(INPUT_GET,'unitofmeasureid');
		$uomcode = filter_input(INPUT_GET,'uomcode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.unitofmeasureid,'') like '%".$unitofmeasureid."%' 
			and coalesce(a.uomcode,'') like '%".$uomcode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.unitofmeasureid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by uomcode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('unitofmeasure');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('unitofmeasureid'),
																	GetCatalog('uomcode'),
																	GetCatalog('description'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,55,100,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['unitofmeasureid'],$row1['uomcode'],$row1['description'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='unitofmeasure';
		parent::actionDownxls();
		$sql = "select unitofmeasureid,uomcode,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from unitofmeasure a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where a.unitofmeasureid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by uomcode asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('unitofmeasureid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('uomcode'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['unitofmeasureid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['uomcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}