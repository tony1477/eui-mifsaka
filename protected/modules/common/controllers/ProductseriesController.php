<?php
class ProductseriesController extends Controller {
	public $menuname = 'productseries';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$productseriesid = isset ($_POST['productseriesid']) ? $_POST['productseriesid'] : '';
		$seriescode = isset ($_POST['seriescode']) ? $_POST['seriescode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$productseriesid = isset ($_GET['q']) ? $_GET['q'] : $productseriesid;
		$seriescode = isset ($_GET['q']) ? $_GET['q'] : $seriescode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'productseriesid';
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
				->from('productseries t')
				->where('((t.productseriesid like :productseriesid) or (t.description like :description))
					and t.recordstatus = 1 ',
					array(':productseriesid'=>'%'.$productseriesid.'%',':description'=>'%'.$description.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('productseries t')
				->where("(coalesce(t.productseriesid,'') like :productseriesid) and (coalesce(t.description,'') like :description) ",
					array(':productseriesid'=>'%'.$productseriesid.'%',':description'=>'%'.$description.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('productseries t')
				->where('((t.productseriesid like :productseriesid) or (t.description like :description) ) 
					and t.recordstatus = 1 ',
					array(':productseriesid'=>'%'.$productseriesid.'%',':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select("t.*")	
				->from('productseries t')
				->where("(coalesce(t.productseriesid,'') like :productseriesid) and (coalesce(t.description,'') like :description)",
					array(':productseriesid'=>'%'.$productseriesid.'%',':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'productseriesid'=>$data['productseriesid'],
				'seriescode'=>$data['seriescode'],
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
			$sql = 'call Insertproductseries(:vseriescode,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproductseries(:vid,:vseriescode,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vseriescode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["Fileproductseries"]["name"]);
		if (move_uploaded_file($_FILES["Fileproductseries"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$isstock = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$productseriespic = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$barcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where materialtypecode = '".$materialtypecode."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$description,$isstock,$productseriespic,$barcode,$materialtypeid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['productseriesid'])?$_POST['productseriesid']:''),$_POST['seriescode'],$_POST['description'],$_POST['recordstatus']));
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
				$sql = 'call Purgeproductseries(:vid,:vcreatedby)';
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
		else
		{
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.*,
					substr(description,1,(20+instr(substr(description,21,20),' '))) as description1,
					substr(description,(21+instr(substr(description,21,20),' ')),(20+instr(substr(description,21,20),' '))) as description2,
					substr(description,((21+instr(substr(description,21,20),' '))+(20+instr(substr(description,21,20),' '))),(20+instr(substr(description,21,20),' '))) as description3
			from productseries a ";
		$productseriesid = filter_input(INPUT_GET,'productseriesid');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.productseriesid,'') like '%".$productseriesid."%' 
			and coalesce(a.description,'') like '%".$description."%'"; 
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.productseriesid in (" . $_GET['id'] . ")";
    }
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->AddPage('P', array(
      82.6,
      108.3425
    ));
    $x = 0; $y = 0; $hitung = 0; $i = 0;
    $this->pdf->isfooter = false;
    foreach ($fgs as $row) {
				$hitung += 1;
				if ($hitung % 2 != 0) {
					$x = 10;
					$this->pdf->SetFont('Arial', 'B', 5);
					$this->pdf->text($x-8, $y+4, $row['description1']);
					$this->pdf->text($x-8, $y+6, $row['description2']);				
					$this->pdf->text($x-8, $y+8, $row['description3']);
					$this->pdf->SetFont('Arial', '', 5);
					if (is_numeric($row['barcode'])) {
						$this->pdf->EAN13($x-1, $y+8.5, $row['barcode'], $h=3, $w=.20);
					} 
					$this->pdf->sety($y);
				} else  {
      			$x = 50;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['description1']);
						$this->pdf->text($x-8, $y+6, $row['description2']);				
						$this->pdf->text($x-8, $y+8, $row['description3']);
      			$this->pdf->SetFont('Arial', '', 5);
						if (is_numeric($row['barcode'])) {
							$this->pdf->EAN13($x-1, $y+8.5, $row['barcode'], $h=3, $w=.20);
						}
      			$y = $this->pdf->gety()+21.75;
				}
			if ($y > 90) {
				$this->pdf->AddPage('P', array(
					82.6,
					108.3425
				));
				$x = 0; $y = 0;
			}
    }
    $this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='productseries';
		parent::actionDownxls();
		$sql = "select productseriesid,description,productseriespic,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						barcode,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from productseries a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.productseriesid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by description asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['productseriesid'])
				->setCellValueByColumnAndRow(1,$i,$row1['description'])							
				->setCellValueByColumnAndRow(2,$i,$row1['productseriespic'])
				->setCellValueByColumnAndRow(3,$i,$row1['isstock'])
				->setCellValueByColumnAndRow(4,$i,$row1['barcode'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
}
