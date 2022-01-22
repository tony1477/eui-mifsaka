<?php
class ProductcollectionController extends Controller {
	public $menuname = 'productcollection';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$productcollectid = isset ($_POST['productcollectid']) ? $_POST['productcollectid'] : '';
		$collectionname = isset ($_POST['collectionname']) ? $_POST['collectionname'] : '';
		$productcollectid = isset ($_GET['q']) ? $_GET['q'] : $productcollectid;
		$collectionname = isset ($_GET['q']) ? $_GET['q'] : $collectionname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'productcollectid';
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
				->from('productcollection t')
				->where('((t.productcollectid like :productcollectid) or (t.collectionname like :collectionname)) 
					and t.recordstatus = 1 ',
					array(':productcollectid'=>'%'.$productcollectid.'%',':collectionname'=>'%'.$collectionname.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('productcollection t')
				->where("(coalesce(t.productcollectid,'') like :productcollectid) and (coalesce(t.collectionname,'') like :collectionname) ",
					array(':productcollectid'=>'%'.$productcollectid.'%',':collectionname'=>'%'.$collectionname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('productcollection t')
				->where('((t.productcollectid like :productcollectid) or (t.collectionname like :collectionname) ) 
					and t.recordstatus = 1 ',
					array(':productcollectid'=>'%'.$productcollectid.'%',':collectionname'=>'%'.$collectionname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select("t.*")	
				->from('productcollection t')
				->where("(coalesce(t.productcollectid,'') like :productcollectid) and (coalesce(t.collectionname,'') like :collectionname)",
					array(':productcollectid'=>'%'.$productcollectid.'%',':collectionname'=>'%'.$collectionname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'productcollectid'=>$data['productcollectid'],
				'collectionname'=>$data['collectionname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertproductcollect(:vcollectionname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproductcollect(:vid,:vcollectionname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcollectionname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["Fileproductcollect"]["name"]);
		if (move_uploaded_file($_FILES["Fileproductcollect"]["tmp_name"], $target_file)) {
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
					$collectionname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$isstock = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$productcollectpic = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$barcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where materialtypecode = '".$materialtypecode."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$collectionname,$isstock,$productcollectpic,$barcode,$materialtypeid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['productcollectid'])?$_POST['productcollectid']:''),$_POST['collectionname'],$_POST['recordstatus']));
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
				$sql = 'call Purgeproductcollect(:vid,:vcreatedby)';
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
					substr(collectionname,1,(20+instr(substr(collectionname,21,20),' '))) as collectionname1,
					substr(collectionname,(21+instr(substr(collectionname,21,20),' ')),(20+instr(substr(collectionname,21,20),' '))) as collectionname2,
					substr(collectionname,((21+instr(substr(collectionname,21,20),' '))+(20+instr(substr(collectionname,21,20),' '))),(20+instr(substr(collectionname,21,20),' '))) as collectionname3
			from productcollect a ";
		$productcollectid = filter_input(INPUT_GET,'productcollectid');
		$collectionname = filter_input(INPUT_GET,'collectionname');
		$sql .= " where coalesce(a.productcollectid,'') like '%".$productcollectid."%' 
			and coalesce(a.collectionname,'') like '%".$collectionname."%'"; 
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.productcollectid in (" . $_GET['id'] . ")";
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
					$this->pdf->text($x-8, $y+4, $row['collectionname1']);
					$this->pdf->text($x-8, $y+6, $row['collectionname2']);				
					$this->pdf->text($x-8, $y+8, $row['collectionname3']);
					$this->pdf->SetFont('Arial', '', 5);
					if (is_numeric($row['barcode'])) {
						$this->pdf->EAN13($x-1, $y+8.5, $row['barcode'], $h=3, $w=.20);
					} 
					$this->pdf->sety($y);
				} else  {
      			$x = 50;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['collectionname1']);
						$this->pdf->text($x-8, $y+6, $row['collectionname2']);				
						$this->pdf->text($x-8, $y+8, $row['collectionname3']);
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
		$this->menuname='productcollect';
		parent::actionDownxls();
		$sql = "select productcollectid,collectionname,productcollectpic,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						barcode,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from productcollect a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.productcollectid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by collectionname asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['productcollectid'])
				->setCellValueByColumnAndRow(1,$i,$row1['collectionname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['productcollectpic'])
				->setCellValueByColumnAndRow(3,$i,$row1['isstock'])
				->setCellValueByColumnAndRow(4,$i,$row1['barcode'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
}
