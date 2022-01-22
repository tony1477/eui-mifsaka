<?php
class ProductconversionController extends Controller {
	public $menuname = 'productconversion';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexdetail() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchdetail();
		else
			$this->renderPartial('index',array());
	}      
	public function actiongetdata() {
		parent::actionIndex();
		if(isset($_GET['id'])) {                    
		}
		else {
			$sql = "insert into productconversion () values ()";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'productconversionid'=>$id,
			));
		}
	}
	public function search() {
		header("Content-Type: application/json");
		$productconversionid = isset ($_POST['productconversionid']) ? $_POST['productconversionid'] : '';
		$productname = isset ($_POST['productname']) ? $_POST['productname'] : '';
		$qty = isset ($_POST['qty']) ? $_POST['qty'] : '';
		$uomcode = isset ($_POST['uomcode']) ? $_POST['uomcode'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$productconversionid = isset ($_GET['q']) ? $_GET['q'] : $productconversionid;
		$productname = isset ($_GET['q']) ? $_GET['q'] : $productname;
		$qty = isset ($_GET['q']) ? $_GET['q'] : $qty;
		$uomcode = isset ($_GET['q']) ? $_GET['q'] : $uomcode;
        $recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'productconversionid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.')>0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order ;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['trx'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('productconversion t')
				->leftjoin('product a','a.productid=t.productid')
				->leftjoin('unitofmeasure b','b.unitofmeasureid=t.uomid')
				->where('(a.productname like :productname) and t.recordstatus=1',
					array(
					':productname'=>'%'.$productname.'%',
					
					))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('productconversion t')
				->leftjoin('product a','a.productid=t.productid')
				->leftjoin('unitofmeasure b','b.unitofmeasureid=t.uomid')
				->where("(coalesce(t.productconversionid,'') like :productconversionid) and (coalesce(a.productname,'') like :productname) and (coalesce(b.uomcode,'') like :uomcode)",
					array(
					':productconversionid'=>'%'.$productconversionid.'%',
					':productname'=>'%'.$productname.'%',
					':uomcode'=>'%'.$uomcode.'%'
					))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['trx'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.productname,b.uomcode')
				->from('productconversion t')
				->leftjoin('product a','a.productid=t.productid')
				->leftjoin('unitofmeasure b','b.unitofmeasureid=t.uomid')
				->where('(a.productname like :productname)  and t.recordstatus=1',
					array(
					':productname'=>'%'.$productname.'%',
					
					))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.productname,b.uomcode')
			->from('productconversion t')
			->leftjoin('product a','a.productid=t.productid')
			->leftjoin('unitofmeasure b','b.unitofmeasureid=t.uomid')
				->where("(coalesce(t.productconversionid,'') like :productconversionid) and (coalesce(a.productname,'') like :productname) and (coalesce(b.uomcode,'') like :uomcode)",
				array(
				':productconversionid'=>'%'.$productconversionid.'%',
				':productname'=>'%'.$productname.'%',
				':uomcode'=>'%'.$uomcode.'%'
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'productconversionid'=>$data['productconversionid'],
				'productid'=>$data['productid'],
				'productname'=>$data['productname'],
				'qty'=>Yii::app()->format->formatNumber($data['qty']),
				'uomid'=>$data['uomid'],
				'uomcode'=>$data['uomcode'],
                'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionSearchdetail() {
		header("Content-Type: application/json");
		$id=0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}            
    $result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
		->select('count(1) as total')
		->from('productconversiondetail t')
		->leftjoin('product a','a.productid = t.productid')
		->leftjoin('unitofmeasure b','b.unitofmeasureid = t.uomid')
		->where('productconversionid = :productconversionid',
						array(':productconversionid'=>$id))
		->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
		->select('t.*,a.productname,b.uomcode')
		->from('productconversiondetail t')
		->leftjoin('product a','a.productid = t.productid')
		->leftjoin('unitofmeasure b','b.unitofmeasureid = t.uomid')
		->where('productconversionid = :productconversionid',
						array(':productconversionid'=>$id))
		->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'productconversiondetailid'=>$data['productconversiondetailid'],
				'productconversionid'=>$data['productconversionid'],
				'productid'=>$data['productid'],
				'productname'=>$data['productname'],
				'qty'=>Yii::app()->format->formatNumber($data['qty']),
				'uomid'=>$data['uomid'],
				'uomcode'=>$data['uomcode'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertproductconversion(:vproductid,:vqty,:vuomid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproductconversion(:vid,:vproductid,:vqty,:vuomid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vproductid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vqty',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vuomid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-productconversion"]["name"]);
		if (move_uploaded_file($_FILES["file-productconversion"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$abid = '';$nourut = '';
				for ($row = 2; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$productname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
					$abid = Yii::app()->db->createCommand("select productconversionid from productconversion where productid = ".$productid." limit 1")->queryScalar();
					if ($abid == '') {					
						$qty = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$this->ModifyData($connection,array('',$productid,$qty,$uomid));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(4, $row)->getValue() != '') {
							$productname = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
							$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
							$qty = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
							$uomcode = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$this->ModifyDataDetail($connection,array('',$abid, $productid,$qty,$uomid));
						}
					}
				}
				$transaction->commit();			
				GetMessage(true,'insertsuccess',1);
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(false,$e->getMessage(),1);
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['productconversionid'])?$_POST['productconversionid']:''),$_POST['productid'],$_POST['qty'],$_POST['uomid'],(isset($_POST['recordstatus']) ? 1 : 0)));
			$transaction->commit();			
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	private function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertproductconversiondetail(:vproductconversionid,:vproductid,:vqty,:vuomid,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproductconversiondetail(:vid,:vproductconversionid,:vproductid,:vqty,:vuomid,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vproductconversionid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vproductid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vqty',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vuomid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSavedetail() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataDetail($connection,array((isset($_POST['productconversiondetailid'])?$_POST['productconversiondetailid']:''),$_POST['productconversionid'],$_POST['productid'],$_POST['qty'],$_POST['uomid']));
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
				$sql = 'call Purgeproductconversion(:vid,:vcreatedby)';
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
	public function actionPurgedetail() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call PurgeConversiondet(:vid,:vcreatedby)';
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
	public function actionGeneratedetail() {
		if(isset($_POST['id'])) {
			$sql = "select a.unitofissue 
				from productplant a
				where productid = ".$_POST['id'];
			if (Yii::app()->request->isAjaxRequest) {
				echo CJSON::encode(array(
					'status'=>'success',
					'uomid'=>Yii::app()->db->createCommand($sql)->queryScalar(),
					'div'=>"Data generated"
				));
			}
		}
		Yii::app()->end();
	}
	public function actionDownPDF() {
		parent::actionDownload();
		$sql = "select a.productconversionid,
						ifnull((b.productname),'-')as productname,a.qty,
						ifnull((c.uomcode),'-')as uomcode
						from productconversion a
						left join product b on b.productid = a.productid
						left join unitofmeasure c on c.unitofmeasureid = a.uomid ";
		$productconversionid = filter_input(INPUT_GET,'productconversionid');
		$productname = filter_input(INPUT_GET,'productname');
		$uomcode = filter_input(INPUT_GET,'uomcode');
		$sql .= " where coalesce(a.productconversionid,'') like '%".$productconversionid."%' 
			and coalesce(b.productname,'') like '%".$productname."%'
			and coalesce(c.uomcode,'') like '%".$uomcode."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.productconversionid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
		
	  $this->pdf->title=GetCatalog('productconversion');
	  $this->pdf->AddPage('P');
		$this->pdf->SetFont('Arial','',10);
		$this->pdf->AliasNBPages();
		foreach($dataReader as $row) {
			$this->pdf->SetFontSize(8);
      $this->pdf->text(15,$this->pdf->gety()+5,'Material / Service ');$this->pdf->text(50,$this->pdf->gety()+5,': '.$row['productname']);
      $this->pdf->text(15,$this->pdf->gety()+10,'Qty ');$this->pdf->text(50,$this->pdf->gety()+10,': '.Yii::app()->format->formatNumber($row['qty']));
      $this->pdf->text(15,$this->pdf->gety()+15,'Satuan ');$this->pdf->text(50,$this->pdf->gety()+15,': '.$row['uomcode']);
      
			$sql1 = "select a.productconversiondetailid,a.productconversionid,b.productname,a.qty,c.uomcode
							from productconversiondetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.uomid
							where a.productconversionid = '".$row['productconversionid']."'
							order by productconversiondetailid ";
			$command1=$this->connection->createCommand($sql1);
      $dataReader1=$command1->queryAll();
			
			$this->pdf->sety($this->pdf->gety()+20);
      
      $this->pdf->colalign = array('C','C','C','C');
      $this->pdf->setwidths(array(10,140,20,20));
			$this->pdf->colheader = array('No','Material / Service','Qty','Satuan');
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array('L','L','C','C');
      $i=0;
      foreach($dataReader1 as $row1)
			{
				$i=$i+1;
        $this->pdf->row(array($i,$row1['productname'],
            Yii::app()->format->formatNumber($row1['qty']),
            $row1['uomcode']));
			}
			$this->pdf->checkNewPage(10);
      $this->pdf->text(25,$this->pdf->gety()+10,'Approved By');$this->pdf->text(160,$this->pdf->gety()+10,'Proposed By');
      $this->pdf->text(25,$this->pdf->gety()+30,'____________ ');$this->pdf->text(160,$this->pdf->gety()+30,'____________');
			$this->pdf->sety($this->pdf->gety()+40);
		}
		
		$this->pdf->Output();
	}
}
