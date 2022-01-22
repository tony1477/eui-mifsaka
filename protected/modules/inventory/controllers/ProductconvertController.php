<?php
class ProductconvertController extends Controller
{
  public $menuname = 'productconvert';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actiongetdata()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into productconvert (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insconvert').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'productconvertid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $productconvertid    = isset($_POST['productconvertid']) ? $_POST['productconvertid'] : '';
    $productid           = isset($_POST['productid']) ? $_POST['productid'] : '';
    $uomid               = isset($_POST['uomid']) ? $_POST['uomid'] : '';
    $slocid              = isset($_POST['slocid']) ? $_POST['slocid'] : '';
    $storagebinid        = isset($_POST['storagebinid']) ? $_POST['storagebinid'] : '';
    $page                = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows                = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort                = isset($_POST['sort']) ? strval($_POST['sort']) : 'productconvertid';
    $order               = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset              = ($page - 1) * $rows;
    $result              = array();
    $row                 = array();
    $cmd                 = Yii::app()->db->createCommand()->select('count(1) as total')->from('productconvert t')
		->leftjoin('productconversion e', 'e.productconversionid=t.productconversionid')
		->leftjoin('product a', 'a.productid=e.productid')
		->leftjoin('unitofmeasure b', 'b.unitofmeasureid=t.uomid')
		->leftjoin('sloc c', 'c.slocid=t.slocid')
		->leftjoin('storagebin d', 'd.storagebinid=t.storagebinid')
		->where("(coalesce(a.productname,'') like :productid) and 
			(coalesce(c.sloccode,'') like :slocid) and 
			(coalesce(t.productconvertid,'') like :productconvertid) and 
			(coalesce(d.description,'') like :storagebinid)", array(
      ':productconvertid' => '%' . $productconvertid . '%',
      ':productid' => '%' . $productid . '%',
      ':slocid' => '%' . $slocid . '%',
      ':storagebinid' => '%' . $storagebinid . '%'
    ))->queryScalar();
    $result['total']     = $cmd;
    $cmd                 = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,c.description as slocdesc,d.description as storage')->from('productconvert t')
		->leftjoin('productconversion e', 'e.productconversionid=t.productconversionid')
		->leftjoin('product a', 'a.productid=e.productid')
		->leftjoin('unitofmeasure b', 'b.unitofmeasureid=t.uomid')
		->leftjoin('sloc c', 'c.slocid=t.slocid')
		->leftjoin('storagebin d', 'd.storagebinid=t.storagebinid')
		->where("(coalesce(a.productname,'') like :productid) and 
			(coalesce(c.sloccode,'') like :slocid) and 
			(coalesce(t.productconvertid,'') like :productconvertid) and 
			(coalesce(d.description,'') like :storagebinid)", array(
      ':productconvertid' => '%' . $productconvertid . '%',
      ':productid' => '%' . $productid . '%',
      ':slocid' => '%' . $slocid . '%',
      ':storagebinid' => '%' . $storagebinid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productconvertid' => $data['productconvertid'],
        'productconversionid' => $data['productconversionid'],
        'productconvertno' => $data['productconvertno'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'storagebin' => $data['storage'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusproductconvert' => findstatusname("appconvert", $data['recordstatus'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionGenerateBarcode()
  {
    $ids = $_REQUEST['id'];
      
    foreach ($ids as $id) 
		{
      $sql    = "select ifnull(recordstatus,0) as recordstatus from productconvert where productconvertid  = " . $id . " and recordstatus = 3";
      $status = Yii::app()->db->createCommand($sql)->queryScalar();
      $sql1    = "select ifnull(isbarcode,0) as isbarcode from productconvert where productconvertid  = " . $id . " and recordstatus = 3";
      $isbarcode = Yii::app()->db->createCommand($sql1)->queryScalar();
      $sql2    = "select ifnull(count(1),0) as barcode 
                from productconvert a 
                join productconversion c on c.productconversionid = a.productconversionid
                join product b on b.productid = c.productid 
                where productconvertid = {$id} and barcode = ''";
      $barcode = Yii::app()->db->createCommand($sql2)->queryScalar();
      if ($status == 0) 
      {
          GetMessage(true, 'docnotmaxstatus');
      }
      else if ($isbarcode == 1)
      {
          GetMessage(true, 'datagenerated');
      }
      else if ($barcode != 0)
      {
          GetMessage(true, 'emptybarcode');
      }
      else{
          try{
				$connection = Yii::app()->db;
                $transaction = $connection->beginTransaction();
				$update = "update productconvert set isbarcode = 1 where productconvertid = " . $id;
				Yii::app()->db->createCommand($update)->execute();				
				
				$sql = "insert into tempscan (companyid,productconvertid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
				select e.companyid, a.productconvertid, b.productid,a.slocid,a.qty,0,c.barcode,a.uomid,1
                from productconvert a 
                join productconversion b on b.productconversionid = a.productconversionid
                join product c on c.productid = b.productid 
                join sloc d on d.slocid = a.slocid
                join plant e on e.plantid = d.plantid
                join company f on f.companyid = e.companyid
                where a.productconvertid = " . $id;
				Yii::app()->db->createCommand($sql)->execute();
              
				$sql  = "select e.companyid, a.productconvertid, b.productid,a.slocid,a.storagebinid,a.qty,0 as qtyscan,c.barcode,a.uomid,0 as isean, e.plantid, productconvertid
                from productconvert a 
                join productconversion b on b.productconversionid = a.productconversionid
                join product c on c.productid = b.productid 
                join sloc d on d.slocid = a.slocid
                join plant e on e.plantid = d.plantid
                join unitofmeasure g on unitofmeasureid = a.uomid
                join company f on f.companyid = e.companyid
                where a.productconvertid = " . $id;
				$rows = Yii::app()->db->createCommand($sql)->queryAll();
				foreach ($rows as $row) {
					for ($i = 1; $i <= $row['qty']; $i++) {
						$sql = "insert into tempscan (companyid, productconvertid ,productid,slocid,storagebinid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
							values ({$row['companyid']},{$row['productconvertid']},{$row['productid']},{$row['slocid']},{$row['storagebinid']},1,0,concat(".$row['plantid'].$row['productconvertid'].",'-C',lpad(".$i.",5,'0'))," . $row['uomid'].",0)";
						Yii::app()->db->createCommand($sql)->execute();
					}
				}
                $transaction->commit();
				GetMessage(false, 'GenerateBarcodeDone');
        }
        catch (Exception $e) {
            $transaction->rollBack();
            GetMessage(true, $e->getMessage());
        }
      }
    }   
  }
	public function actionDownEan13()
  {
    parent::actionDownloadbarcode();
    $this->pdf->SetY(-8);
    $this->pdf->SetFooterMargin(-15);
		$sql = "select a.barcode, a.qtyori, b.productname, a.productid
                from tempscan a 
                join product b on b.productid = a.productid
                where a.isean = 1 and a.productconvertid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->SetAutoPageBreak(TRUE, -20);
    $width = 175;
    $height = 266;
    //$this->pdf->addFormat("custom",$width,$height);
    //$this->pdf->reFormat("custom",'P');
    $pageLayout = array($width, $height);
        $this->pdf->AddPage('P', array(
					109,
					175
        ));
    //$this->pdf->AddPage("L", "mm", $pageLayout , true, 'UTF-8', false);
    //$this->pdf->isfooter = false;
    $style = array(
    'position' => 'C',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 15,
    'stretchtext' => 4
);
    /*
    define('B', Yii::app()->request->baseUrl);
    $codeContents = '123456DEMO'; 
    $this->pdf->setY($this->pdf->getY()+10);
    $this->pdf->write2DBarcode('PHP QR Code :)', 'QRCODE,H', 27, 52, 45, 45, '', 'N');
    $mid_x = 50; // the middle of the "PDF screen", fixed by now.
    $text = 'PHP QR Code :)';
    $this->pdf->setFont('helvetica','B',12);
    $this->pdf->text($mid_x - ($this->pdf->GetStringWidth($text) / 2), 98, $text);
    $this->pdf->setFont('helvetica','',18);
    $this->pdf->setFont('helvetica','B',18);
    $this->pdf->Ln(6);
    $this->pdf->MultiCell(80, 2.5,'MATRAS KANGAROO REGULAR E-CLASS KNITING 0178 COKLAT 180X200', 0, 'C', false);
    $this->pdf->write1DBarcode('1234567890128', 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
    $this->pdf->text(26.5,152,'8994349049122');
    */
    //$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //$this->pdf->setPrintFooter(true);
    //$this->pdf->SetFooterMargin(-100);
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
        $this->pdf->setY($this->pdf->getY()+10);
         $sql   = "select a.barcode, a.qtyori, b.productname, a.productid
                    from tempscan a 
                    join product b on b.productid = a.productid
				    where a.isean = 0 and a.productconvertid = " . $_REQUEST['id'] . " 
				    and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
        $c128s = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($c128s as $c128) {          
            $this->pdf->Line(109,25,109,20);
            $this->pdf->Line(109,155,109,150);
            $code = $c128['barcode'];
            $this->pdf->write2DBarcode($code, 'QRCODE,H', 32, 52, 45, 45, '', 'N');
            $mid_x = 52;
            $this->pdf->setFont('helvetica','B',14);
            $this->pdf->text($mid_x - ($this->pdf->GetStringWidth($code) / 2), 98, $code);  
        }
        $this->pdf->setFont('helvetica','B',18);
        $this->pdf->Ln(6);
        $this->pdf->MultiCell(90, 2.5,$row['productname'], 0, 'C', false);
        $this->pdf->write1DBarcode($row['barcode'], 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
        $y = $this->pdf->getY();
        $this->pdf->SetY(-25);
        $this->pdf->text(31,$y-3.8,$row['barcode']);
        $this->pdf->AddPage('P', array(
					109,
					175
        ));
      }
    }
		$id = $_REQUEST['id'];
    $this->pdf->Output($id.'.pdf', 'I');
  }
  public function actionDownSticker()
  {
    parent::actionDownload();
		$sql = "select a.*,b.productname,
					substr(productname,1,(20+instr(substr(productname,21,20),' '))) as productname1,
					substr(productname,(21+instr(substr(productname,21,20),' ')),(20+instr(substr(productname,21,20),' '))) as productname2,
					substr(productname,((21+instr(substr(productname,21,20),' '))+(20+instr(substr(productname,21,20),' '))),(20+instr(substr(productname,21,20),' '))) as productname3
			from tempscan a 
			join product b on b.productid = a.productid
			where a.isean = 1 and a.productconvertid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->AddPage('P', array(
      82.6,
      108.3425
    ));
    $x = 0; $y = 0; $hitung = 0;
    $this->pdf->isfooter = false;
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
      		$hitung += 1;
      		//jika sisa pembagian dengan 2 tidak ada sisa, maka baris baru
      		if ($hitung % 2 != 0) {
      			$x = 10;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['productname1']);
						$this->pdf->text($x-8, $y+6, $row['productname2']);				
						$this->pdf->text($x-8, $y+8, $row['productname3']);
      			$this->pdf->SetFont('Arial', '', 5);
		      	$this->pdf->EAN13($x+1, $y+8.5, $row['barcode'], $h=3, $w=.20);
				    $sql   = "select a.*,b.productname 
						from tempscan a 
						join product b on b.productid = a.productid
						where a.isean = 0 and a.productconvertid = " . $_REQUEST['id'] . " 
						and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
				    $c128s = Yii::app()->db->createCommand($sql)->queryAll();
				    foreach ($c128s as $c128) {
					    $code = $c128['barcode'];
					    $this->pdf->Code128($x-4.5, $y+15, $code, 30, 3);
					    $this->pdf->text($x+5, $y +20, $code);
					  }
      			$this->pdf->sety($y);
      		} else {
      			$x = 53;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['productname1']);
						$this->pdf->text($x-8, $y+6, $row['productname2']);				
						$this->pdf->text($x-8, $y+8, $row['productname3']);
      			$this->pdf->SetFont('Arial', '', 5);
		      	$this->pdf->EAN13($x+1, $y+8.5, $row['barcode'], $h=3, $w=.20);
		      	$sql   = "select a.*,b.productname 
						from tempscan a 
						join product b on b.productid = a.productid
						where a.isean = 0 and a.productconvertid = " . $_REQUEST['id'] . " 
						and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
				    $c128s = Yii::app()->db->createCommand($sql)->queryAll();
				    foreach ($c128s as $c128) {
					    $code = $c128['barcode'];
					    $this->pdf->Code128($x-4.5, $y+15, $code, 30, 3);
					    $this->pdf->text($x+5, $y+20, $code);
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
      if ($y > 100) {
      	$this->pdf->checknewpage(5); 
  			//$i = $i-2;    		
  			$x = 0; $y = 0;
  		}
    }
    $this->pdf->Output();
  }
  public function actionSearchdetail()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productconvertdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('storagebin c', 'c.storagebinid=t.storagebinid')->where('productconvertid = :productconvertid', array(
      ':productconvertid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.description as storage,d.sloccode,d.description as slocdesc')->from('productconvertdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('storagebin c', 'c.storagebinid=t.storagebinid')->leftjoin('sloc d', 'd.slocid=t.slocid')->where('productconvertid = :productconvertid', array(
      ':productconvertid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productconvertdetailid' => $data['productconvertdetailid'],
        'productconvertid' => $data['productconvertid'],
        'productconversiondetailid' => $data['productconversiondetailid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'storagebin' => $data['storage']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSave()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertproductconvert(:vproductconversionid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductconvert(:vid,:vproductconversionid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productconvertid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productconvertid']);
      }
      $command->bindvalue(':vproductconversionid', $_POST['productconversionid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionSavedetail()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertproductconvertdetail(:vproductconvertid,:vproductconversiondetailid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductconvertdetail(:vid,:vproductconvertid,:vproductconversiondetailid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productconvertdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productconvertdetailid']);
      }
      $command->bindvalue(':vproductconvertid', $_POST['productconvertid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductconversiondetailid', $_POST['productconversiondetailid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductconvert(:vid,:vcreatedby)';
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
  public function actionGenerateConv()
  {
    if (isset($_POST['id'])) {
			$sql = "select uomid from productconversion where productconversionid = ".$_POST['id'];
			$uom = Yii::app()->db->createCommand($sql)->queryScalar();
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateConv(:vid,:vslocid,:vqty,:vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_INT);
        $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success',
            'uomid' => $uom,
            'div' => "Data generated"
          ));
        }
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
      }
    }
    Yii::app()->end();
  }
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteConvert(:vid,:vcreatedby)';
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
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveConvert(:vid,:vcreatedby)';
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
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select a.productconvertid,c.productname,a.qty,d.uomcode,e.sloccode,f.description as rak
						from productconvert a
						left join productconversion b on b.productconversionid = a.productconversionid
						left join product c on c.productid = b.productid
						left join unitofmeasure d on d.unitofmeasureid = a.uomid
						left join sloc e on e.slocid = a.slocid
						left join storagebin f on f.storagebinid = a.storagebinid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productconvertid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('productconvert');
    $this->pdf->AddPage('P');
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->SetFontSize(8);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'Kode Gudang');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['sloccode']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Qty');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . $row['qty']);
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Material / Service');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['productname']);
      $this->pdf->text(15, $this->pdf->gety() + 20, 'Kode Satuan');
      $this->pdf->text(50, $this->pdf->gety() + 20, ': ' . $row['uomcode']);
      $this->pdf->text(15, $this->pdf->gety() + 25, 'Rak');
      $this->pdf->text(50, $this->pdf->gety() + 25, ': ' . $row['rak']);
      $sql1        = "select a.productconvertdetailid,b.productname,a.qty,c.uomcode,d.sloccode,e.description as rak
							from productconvertdetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join sloc d on d.slocid = a.slocid
							left join storagebin e on e.storagebinid = a.storagebinid
							where productconvertid = " . $row['productconvertid'] . " order by productconvertdetailid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 30);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->setwidths(array(
        10,
        85,
        15,
        15,
        33,
        33
      ));
      $this->pdf->colheader = array(
        'No',
        'Material / Service',
        'Qty',
        'Satuan',
        'Gudang',
        'Rak Tujuan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'C',
        'C',
        'C',
        'C'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['qty'],
          $row1['uomcode'],
          $row1['sloccode'],
          $row1['rak']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->checkNewPage(10);
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
    }
    $this->pdf->text(25, $this->pdf->gety() + 10, 'Approved By');
    $this->pdf->text(150, $this->pdf->gety() + 10, 'Proposed By');
    $this->pdf->text(25, $this->pdf->gety() + 30, '____________ ');
    $this->pdf->text(150, $this->pdf->gety() + 30, '____________');
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'productconvert';
    parent::actionDownxls();
    $sql = "select a.productconvertid,c.productname,a.qty,d.uomcode,e.sloccode,f.description as rak
						from productconvert a
						left join productconversion b on b.productconversionid = a.productconversionid
						left join product c on c.productid = b.productid
						left join unitofmeasure d on d.unitofmeasureid = a.uomid
						left join sloc e on e.slocid = a.slocid
						left join storagebin f on f.storagebinid = a.storagebinid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productconvertid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Kode Gudang')->setCellValueByColumnAndRow(1, $line, ': ' . $row['sloccode']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Qty')->setCellValueByColumnAndRow(1, $line, ': ' . $row['qty']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Material / Service')->setCellValueByColumnAndRow(1, $line, ': ' . $row['productname']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Kode Satuan')->setCellValueByColumnAndRow(1, $line, ': ' . $row['uomcode']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Rak')->setCellValueByColumnAndRow(1, $line, ': ' . $row['rak']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Material / Service')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Satuan')->setCellValueByColumnAndRow(4, $line, 'Gudang')->setCellValueByColumnAndRow(5, $line, 'Rak tujuan');
      $line++;
      $sql1        = "select a.productconvertdetailid,b.productname,a.qty,c.uomcode,d.sloccode,e.description as rak
							from productconvertdetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.uomid
							left join sloc d on d.slocid = a.slocid
							left join storagebin e on e.storagebinid = a.storagebinid
							where productconvertid = " . $row['productconvertid'] . " order by productconvertdetailid ";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['qty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['sloccode'])->setCellValueByColumnAndRow(5, $line, $row1['rak']);
        $line++;
      }
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Approved By')->setCellValueByColumnAndRow(4, $line, 'Proposed By');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '_____________')->setCellValueByColumnAndRow(4, $line, '_____________');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
