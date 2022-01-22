<?php
class TaxController extends Controller {
	public $menuname = 'tax';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$taxid = isset ($_POST['taxid']) ? $_POST['taxid'] : '';
		$taxcode = isset ($_POST['taxcode']) ? $_POST['taxcode'] : '';
		$taxvalue = isset ($_POST['taxvalue']) ? $_POST['taxvalue'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$taxid = isset ($_GET['q']) ? $_GET['q'] : $taxid;
		$taxcode = isset ($_GET['q']) ? $_GET['q'] : $taxcode;
		$taxvalue = isset ($_GET['q']) ? $_GET['q'] : $taxvalue;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.taxid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';		
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.taxid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();		
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('tax t')
				->where("((taxid like :taxid) 
				or (taxcode like :taxcode)
				or (description like :description)
				or (taxvalue like :taxvalue)) 
				and t.recordstatus = 1", array(
						':taxid' => '%' . $taxid . '%',
						':taxvalue' => '%' . $taxvalue . '%',
						':description' => '%' . $description . '%',
						':taxcode' => '%' . $taxcode . '%',
				))->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('tax t')
				->where("((taxid like :taxid) 
				and (taxcode like :taxcode)
				and (description like :description)
				and (taxvalue like :taxvalue))
				", array(
						':taxid' => '%' . $taxid . '%',
						':taxvalue' => '%' . $taxvalue . '%',
						':description' => '%' . $description . '%',
						':taxcode' => '%' . $taxcode . '%',
				))->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('tax t')
				->where("((taxid like :taxid) 
				or (taxcode like :taxcode)
				or (description like :description)
				or (taxvalue like :taxvalue)) 
				and t.recordstatus = 1", array(
						':taxid' => '%' . $taxid . '%',
						':taxvalue' => '%' . $taxvalue . '%',
						':description' => '%' . $description . '%',
						':taxcode' => '%' . $taxcode . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('tax t')
				->where("((taxid like :taxid) 
				and (taxcode like :taxcode)
				and (description like :description)
				and (taxvalue like :taxvalue))
				", array(
						':taxid' => '%' . $taxid . '%',
						':taxvalue' => '%' . $taxvalue . '%',
						':description' => '%' . $description . '%',
						':taxcode' => '%' . $taxcode . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) { 
			$row[] = array(
				'taxid'=>$data['taxid'],
				'taxcode'=>$data['taxcode'],
				'taxvalue'=>$data['taxvalue'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($arraydata) {
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
				$sql = 'call Inserttax(:vtaxcode,:vtaxvalue,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else {
				$sql = 'call Updatetax(:vid,:vtaxcode,:vtaxvalue,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $arraydata[0]);
			}
			$command->bindvalue(':vtaxcode',$arraydata[1],PDO::PARAM_STR);
			$command->bindvalue(':vtaxvalue',$arraydata[2],PDO::PARAM_STR);
			$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileLanguage"]["name"]);
		if (move_uploaded_file($_FILES["FileLanguage"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			for ($row = 2; $row <= $highestRow; ++$row) {
				$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
				$taxcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				$taxvalue = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
				$description = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
				$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
				$this->ModifyData(array($id,$taxcode,$taxvalue,$description,$recordstatus));
			}
    }
	}
	public function actionSave() {
		header("Content-Type: application/json");
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$this->ModifyData(array((isset($_POST['taxid'])?$_POST['taxid']:''),$_POST['taxcode'],$_POST['taxvalue'],$_POST['description'],$_POST['recordstatus']));
	}
	public function actionPurge() {
		header("Content-Type: application/json");		
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgetax(:vid,:vcreatedby)';
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
	  $sql = "select taxid,taxcode,taxvalue,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from tax a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . " where a.taxid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by taxcode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('tax');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('taxid'),
																	GetCatalog('taxcode'),
																	GetCatalog('taxvalue'),
																	GetCatalog('description'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(10,30,25,110,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['taxid'],$row1['taxcode'],$row1['taxvalue'],$row1['description'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='tax';
		parent::actionDownxls();
		$sql = "select taxid,taxcode,taxvalue,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from tax a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . " where a.taxid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by taxcode asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['taxid'])
				->setCellValueByColumnAndRow(1,$i,$row1['taxcode'])							
				->setCellValueByColumnAndRow(2,$i,$row1['taxvalue'])
				->setCellValueByColumnAndRow(3,$i,$row1['description'])
				->setCellValueByColumnAndRow(4,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}