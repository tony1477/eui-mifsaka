<?php
class TransdocController extends Controller {
	public $menuname = 'transdoc';
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
          $transdate = new Datetime();
          $sql = "insert into transdoc (transdate,recordstatus) values ('".$transdate->format('Y-m-d')."',".findstatusbyuser('instransdoc').")";
          $model = Yii::app()->db->createCommand($sql)->execute();
          $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
                echo CJSON::encode(array(
				'transdocid'=>$id,
			));
		}
	}
	public function search() {
		header("Content-Type: application/json");
		$transdocid = isset ($_POST['transdocid']) ? $_POST['transdocid'] : '';
		$fromemployee = isset ($_POST['fromemployee']) ? $_POST['fromemployee'] : '';
		$toemployee = isset ($_POST['toemployee']) ? $_POST['toemployee'] : '';
		$transdocid = isset ($_GET['q']) ? $_GET['q'] : $transdocid;
		$fromemployee = isset ($_GET['q']) ? $_GET['q'] : $fromemployee;
		$toemployee = isset ($_GET['q']) ? $_GET['q'] : $toemployee;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'transdocid';
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
				->from('transdoc t')
				->leftjoin('product a','a.productid=t.productid')
				->leftjoin('unitofmeasure b','b.unitofmeasureid=t.uomid')
				->where('(a.productname like :productname) or (b.uomcode like :uomcode)',
					array(
					':productname'=>'%'.$productname.'%',
					':uomcode'=>'%'.$uomcode.'%'
					))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('transdoc t')
				->leftjoin('employee a','a.employeeid=t.fromemployeeid')
				->leftjoin('employee b','b.employeeid=t.toemployeeid')
				->where("(coalesce(t.transdocid,'') like :transdocid) and (coalesce(a.fullname,'') like :fromemployee) and (coalesce(b.fullname,'') like :toemployee)",
					array(
					':transdocid'=>'%'.$transdocid.'%',
					':fromemployee'=>'%'.$fromemployee.'%',
					':toemployee'=>'%'.$toemployee.'%'
					))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['trx'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.productname,b.uomcode')
				->from('transdoc t')
				->leftjoin('product a','a.productid=t.productid')
				->leftjoin('unitofmeasure b','b.unitofmeasureid=t.uomid')
				->where('(a.productname like :productname) or (b.uomcode like :uomcode)',
					array(
					':productname'=>'%'.$productname.'%',
					':uomcode'=>'%'.$uomcode.'%'
					))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,a.fullname as fromemployee,b.fullname as toemployee, if(docupload is null,0,1) as uploaded')
			->from('transdoc t')
			->leftjoin('employee a','a.employeeid=t.fromemployeeid')
			->leftjoin('employee b','b.employeeid=t.toemployeeid')
            ->where("(coalesce(t.transdocid,'') like :transdocid) and (coalesce(a.fullname,'') like :fromemployee) and (coalesce(b.fullname,'') like :toemployee)",
					array(
					':transdocid'=>'%'.$transdocid.'%',
					':fromemployee'=>'%'.$fromemployee.'%',
					':toemployee'=>'%'.$toemployee.'%'
					))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'transdocid'=>$data['transdocid'],
				'transdocno'=>$data['transdocno'],
				'transdate'=>date(Yii::app()->params['dateviewfromdb'],strtotime($data['transdate'])),
				'fromemployeeid'=>$data['fromemployeeid'],
				'fromemployee'=>$data['fromemployee'],
				'toemployeeid'=>$data['toemployeeid'],
				'toemployee'=>$data['toemployee'],
				'recordstatus'=>$data['recordstatus'],
				'uploaded'=>$data['uploaded'],
				'docupload'=>$data['docupload'],
				'statusname'=>$data['statusname']
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
		->from('transdocdet t')
		->leftjoin('legaldoc a','a.legaldocid = t.legaldocid')
		->leftjoin('storagedoc b','b.storagedocid = t.storagedocid')
		->where('transdocid = :transdocid',
						array(':transdocid'=>$id))
		->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
		->select('t.*,a.docname,b.storagedocname')
		->from('transdocdet t')
		->leftjoin('legaldoc a','a.legaldocid = t.legaldocid')
		->leftjoin('storagedoc b','b.storagedocid = t.storagedocid')
		->where('transdocid = :transdocid',
						array(':transdocid'=>$id))
		->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'transdocdetid'=>$data['transdocdetid'],
				'transdocid'=>$data['transdocid'],
				'legaldocid'=>$data['legaldocid'],
				'docname'=>$data['docname'],
				'storagedocid'=>$data['storagedocid'],
				'storagedocname'=>$data['storagedocname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Inserttransdoc(:vproductid,:vqty,:vuomid,:vdocupload,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatetransdoc(:vid,:vtransdate,:vfromeployeeid,:vtoemployeeid,:vdocupload,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vtransdate',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vfromeployeeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vtoemployeeid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdocupload',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-transdoc"]["name"]);
		if (move_uploaded_file($_FILES["file-transdoc"]["tmp_name"], $target_file)) {
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
					$abid = Yii::app()->db->createCommand("select transdocid from transdoc where productid = ".$productid." limit 1")->queryScalar();
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
    
    public function actionDocupload() {
		parent::actionUpload();
		
        $target_file = dirname('__FILES__').'/images/docupload/' . basename($_FILES["docupload"]["name"]);
        try {
            $imageFileType =  strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf" ) {
                throw new Exception("Sorry, only JPG, JPEG, PNG & PDF files are allowed.");
            }
            else {
                if(!file_exists($target_file))  {
                    move_uploaded_file($_FILES["docupload"]["tmp_name"], $target_file);
                    GetMessage(true,'insertsuccess',1);
                }
                else
                {
                    throw new Exception('File Exists.Periksa File Kembali atau Ubah Nama File ');
                }
            }
        }
        catch (Exception $e) {
			GetMessage(true,$e->getMessage());
		}
	}
    
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['transdocid'])?$_POST['transdocid']:''),date(Yii::app()->params['datetodb'],strtotime($_POST['transdate'])),$_POST['fromemployeeid'],$_POST['toemployeeid'],$_POST['docupload']));
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
			$sql = 'call Inserttransdocdetail(:vtransdocid,:vlegaldocid,:vstoragedocid,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatetransdocdetail(:vid,:vtransdocid,:vlegaldocid,:vstoragedocid,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vtransdocid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vlegaldocid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vstoragedocid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSavedetail() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataDetail($connection,array((isset($_POST['transdocdetid'])?$_POST['transdocdetid']:''),$_POST['transdocid'],$_POST['legaldocid'],$_POST['storagedocid']));
			$transaction->commit();			
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
    
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvetransdoc(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
    
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgetransdoc(:vid,:vcreatedby)';
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
		$sql = "select a.* , b.fullname as fromemployee, c.fullname as toemployee, b.oldnik as fromemployeenik, c.oldnik as toemployeenik
                from transdoc a
                left join employee b on b.employeeid = a.fromemployeeid
                left join employee c on c.employeeid = a.toemployeeid
                ";
		$transdocid = filter_input(INPUT_GET,'transdocid');
		$transdocno = filter_input(INPUT_GET,'transdocno');
		$fromemployee = filter_input(INPUT_GET,'fromemployee');
		$toemployee = filter_input(INPUT_GET,'toemployee');
        
		$sql .= " where coalesce(a.transdocid,'') like '%".$transdocid."%' 
			and coalesce(a.transdocno,'') like '%".$transdocno."%'
			and coalesce(b.fullname,'') like '%".$fromemployee."%'
			and coalesce(c.fullname,'') like '%".$toemployee."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.transdocid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
        $dataReader=$command->queryAll();
		
        $this->pdf->title=GetCatalog('SERAH TERIMA DOKUMEN');
        $this->pdf->AddPage('P');
		$this->pdf->SetFont('Arial','',10);
		$this->pdf->AliasNBPages();
		foreach($dataReader as $row) {
            $this->pdf->setFont('Arial','B',11);
            $this->pdf->text(85,15,$row['transdocno']);
            $this->pdf->setY($this->pdf->getY()+10);
            $this->pdf->SetFont('Arial','',10);
            
            $this->pdf->text(15,$this->pdf->gety()+5,'Saya yg bertanda tangan dibawah ini : ');
            //$this->pdf->text(50,$this->pdf->gety()+5,': '.$row['productname']);
            
            $this->pdf->text(15,$this->pdf->gety()+10,'NAMA LENGKAP ');
            $this->pdf->text(85,$this->pdf->gety()+10,' : '.$row['fromemployee']);
            $this->pdf->text(15,$this->pdf->gety()+15,'NIK KARYAWAN');
            $this->pdf->text(85,$this->pdf->gety()+15,' : '.$row['fromemployeenik']);
            $this->pdf->text(15,$this->pdf->gety()+20,'Selanjutnya dianggap sebagai Pihak Pertama');
            $this->pdf->text(15,$this->pdf->gety()+30,'Dengan : ');
            $this->pdf->text(15,$this->pdf->gety()+35,'NAMA LENGKAP  ');
            $this->pdf->text(85,$this->pdf->gety()+35,' : '.$row['toemployee']);
            $this->pdf->text(15,$this->pdf->gety()+40,'NIK  KARYAWAN ');
            $this->pdf->text(85,$this->pdf->gety()+40,' : '.$row['toemployeenik']);
            $this->pdf->text(15,$this->pdf->gety()+45,'Selanjutnya dianggap sebagai Pihak Kedua  ');
            
            /*
            $this->pdf->text(25,$this->pdf->gety()+50,'Pihak Pertama telah menyerahkan dokumen-dokumen dibawah, pada tanggal : '.date(Yii::app()->params['dateviewfromdb'],strtotime($row['transdate'])).' Dengan lengkap dan utuh kepada Pihak Kedua. Adapun dokumen-dokumen yang dimaksud adalah : ');*/
            
            $this->pdf->setY($this->pdf->getY()+50);
            $this->pdf->setX(15);
            $this->pdf->MultiCell(0,5,'Pihak Pertama telah menyerahkan dokumen-dokumen dibawah, pada tanggal : '.date(Yii::app()->params['dateviewfromdb'],strtotime($row['transdate'])).' Dengan lengkap dan utuh kepada Pihak Kedua. Adapun dokumen-dokumen yang dimaksud adalah : ',0);
            
            /*
            $this->pdf->row(array(
                'Pihak Pertama telah menyerahkan dokumen-dokumen dibawah, pada tanggal : '.date(Yii::app()->params['dateviewfromdb'],strtotime($row['transdate'])).' Dengan lengkap dan utuh kepada Pihak Kedua. Adapun dokumen-dokumen yang dimaksud adalah : ',
            ));
            */
            //$this->pdf->text(50,$this->pdf->gety()+10,': '.Yii::app()->format->formatNumber($row['qty']));
            //$this->pdf->text(15,$this->pdf->gety()+15,'Satuan ');
            //$this->pdf->text(50,$this->pdf->gety()+15,': '.$row['uomcode']);
      
			$sql1 = "select a.transdocdetid,a.transdocid,b.docname,c.storagedocname, (select doctypename from doctype x where x.doctypeid = b.doctypeid ) as doctypename
							from transdocdet a
							left join legaldoc b on b.legaldocid = a.legaldocid
							left join storagedoc c on c.storagedocid = a.storagedocid
							where a.transdocid = '".$row['transdocid']."'
							order by transdocdetid ";
			$command1=$this->connection->createCommand($sql1);
            $dataReader1=$command1->queryAll();
			
			$this->pdf->sety($this->pdf->gety()+5);
            $this->pdf->setX(15);
      
            $this->pdf->colalign = array('C','C','L');
            $this->pdf->setwidths(array(10,140,30));
			$this->pdf->colheader = array('No','Nama Dokumen','Lokasi/Tujuan');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','C');
            $i=0;
      foreach($dataReader1 as $row1)
			{
                $this->pdf->setX(15);
				$i=$i+1;
                $this->pdf->row(array($i,
                    $row1['doctypename'].' '.$row1['docname'],
                    $row1['storagedocname']));
			}
			$this->pdf->checkNewPage(10);
            
            $this->pdf->setY($this->pdf->getY()+10);
            $this->pdf->setX(15);
            $this->pdf->MultiCell(0,5,'Demikianlah surat serah terima ini dibuat dengan sebenarnya. Untuk dapat digunakan sebagaimana mestinya.',0);
            
            $this->pdf->text(25,$this->pdf->gety()+10,'Pihak Pertama');$this->pdf->text(160,$this->pdf->gety()+10,'Pihak Kedua');
            $this->pdf->text(25,$this->pdf->gety()+30,$row['fromemployee']);$this->pdf->text(160,$this->pdf->gety()+30,$row['toemployee']);
			$this->pdf->sety($this->pdf->gety()+40);
		}
		
		$this->pdf->Output();
	}
}
