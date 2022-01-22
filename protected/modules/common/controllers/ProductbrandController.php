<?php
class ProductbrandController extends Controller {
	public $menuname = 'productbrand';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$productbrandid = isset ($_POST['productbrandid']) ? $_POST['productbrandid'] : '';
		$brandname = isset ($_POST['brandname']) ? $_POST['brandname'] : '';
		$productbrandid = isset ($_GET['q']) ? $_GET['q'] : $productbrandid;
		$brandname = isset ($_GET['q']) ? $_GET['q'] : $brandname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'productbrandid';
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
				->from('productbrand t')
				->where('((t.productbrandid like :productbrandid) or (t.brandname like :brandname)) 
					and t.recordstatus = 1 ',
					array(':productbrandid'=>'%'.$productbrandid.'%',':brandname'=>'%'.$brandname.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('productbrand t')
				->where("(coalesce(t.productbrandid,'') like :productbrandid) and (coalesce(t.brandname,'') like :brandname) ",
					array(':productbrandid'=>'%'.$productbrandid.'%',':brandname'=>'%'.$brandname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('productbrand t')
				->where('((t.productbrandid like :productbrandid) or (t.brandname like :brandname) ) 
					and t.recordstatus = 1 ',
					array(':productbrandid'=>'%'.$productbrandid.'%',':brandname'=>'%'.$brandname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select("t.*")	
				->from('productbrand t')
				->where("(coalesce(t.productbrandid,'') like :productbrandid) and (coalesce(t.brandname,'') like :brandname)",
					array(':productbrandid'=>'%'.$productbrandid.'%',':brandname'=>'%'.$brandname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'productbrandid'=>$data['productbrandid'],
				'brandname'=>$data['brandname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertproductbrand(:vbrandname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproductbrand(:vid,:vbrandname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vbrandname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["Fileproductbrand"]["name"]);
		if (move_uploaded_file($_FILES["Fileproductbrand"]["tmp_name"], $target_file)) {
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
					$brandname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$isstock = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$productbrandpic = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$barcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where materialtypecode = '".$materialtypecode."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$brandname,$isstock,$productbrandpic,$barcode,$materialtypeid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['productbrandid'])?$_POST['productbrandid']:''),$_POST['brandname'],$_POST['recordstatus']));
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
				$sql = 'call Purgeproductbrand(:vid,:vcreatedby)';
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
					substr(brandname,1,(20+instr(substr(brandname,21,20),' '))) as brandname1,
					substr(brandname,(21+instr(substr(brandname,21,20),' ')),(20+instr(substr(brandname,21,20),' '))) as brandname2,
					substr(brandname,((21+instr(substr(brandname,21,20),' '))+(20+instr(substr(brandname,21,20),' '))),(20+instr(substr(brandname,21,20),' '))) as brandname3
			from productbrand a ";
		$productbrandid = filter_input(INPUT_GET,'productbrandid');
		$brandname = filter_input(INPUT_GET,'brandname');
		$sql .= " where coalesce(a.productbrandid,'') like '%".$productbrandid."%' 
			and coalesce(a.brandname,'') like '%".$brandname."%'"; 
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.productbrandid in (" . $_GET['id'] . ")";
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
					$this->pdf->text($x-8, $y+4, $row['brandname1']);
					$this->pdf->text($x-8, $y+6, $row['brandname2']);				
					$this->pdf->text($x-8, $y+8, $row['brandname3']);
					$this->pdf->SetFont('Arial', '', 5);
					if (is_numeric($row['barcode'])) {
						$this->pdf->EAN13($x-1, $y+8.5, $row['barcode'], $h=3, $w=.20);
					} 
					$this->pdf->sety($y);
				} else  {
      			$x = 50;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['brandname1']);
						$this->pdf->text($x-8, $y+6, $row['brandname2']);				
						$this->pdf->text($x-8, $y+8, $row['brandname3']);
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
		$this->menuname='productbrand';
		parent::actionDownxls();
		$sql = "select productbrandid,brandname,productbrandpic,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						barcode,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from productbrand a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.productbrandid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by brandname asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['productbrandid'])
				->setCellValueByColumnAndRow(1,$i,$row1['brandname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['productbrandpic'])
				->setCellValueByColumnAndRow(3,$i,$row1['isstock'])
				->setCellValueByColumnAndRow(4,$i,$row1['barcode'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
}
