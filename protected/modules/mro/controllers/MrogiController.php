<?php
class MrogiController extends Controller
{
  public $menuname = 'mrogi';
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
        echo $this->searchdetail();
    else
        $this->renderPartial('index', array());
    }
    
    public function actionIndexgihader(){
    if (isset($_GET['grid']))
        echo $this->searchgihader();
    else
        $this->renderPartial('index', array());
    }
	
    public function actionIndexmroinvoice(){
    if (isset($_GET['grid']))
        echo $this->searchmroinvoice();
    else
        $this->renderPartial('index', array());
    }
    
  public function actionGetData()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into mrogiheader (mrogidate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insmrogi').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'mrogiheaderid' => $model->mrogiheaderid
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $giheaderid = isset($_POST['giheaderid']) ? $_POST['giheaderid'] : '';
    $gino       = isset($_POST['gino']) ? $_POST['gino'] : '';
    $sono       = isset($_POST['sono']) ? $_POST['sono'] : '';
    $headernote = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $customer   = isset($_POST['customer']) ? $_POST['customer'] : '';
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'giheaderid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$connection		= Yii::app()->db;
		$from = '
			from mrogiheader a0 
            left join giheader a1 on a1.giheaderid = a0.giheaderid
            left join addressbook b on b.addressbookid = a0.addressbookid ';
		$where = "
			where a0.recordstatus < 3 and a0.recordstatus in (".getUserRecordStatus('listmrogi').")";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select a0.mrogiheaderid,a0.mrogino,a0.mrogidate,a0.giheaderid,a0.shipto,a0.headernote,a0.recordstatus,a0.statusname,a1.gino as gino, b.addressbookid, b.fullname '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'mrogidate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['mrogidate'])),
        'mrogiheaderid' => $data['mrogiheaderid'],
        'mrogino' => $data['mrogino'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'shipto' => $data['shipto'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusmrogiheader' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
   
    public function actionGeneratedata(){
        header("Content-Type: application/json");
        $result = array();
        $row = array();
        $id = 0;
        if (isset($_POST['hid'])) {
            $id = $_POST['hid'];
        } else if (isset($_GET['hid'])) {
            $id = $_GET['hid'];
        }
        $result['total']=1;
        $sqldata = "SELECT d.addressbookid, d.fullname, t.taxid, t.taxcode, c.shipto, c.headernote
                FROM giheader a 
                JOIN soheader c ON a.soheaderid = c.soheaderid
                JOIN addressbook d ON d.addressbookid = c.addressbookid
                JOIN tax t ON t.taxid = c.taxid
                WHERE a.giheaderid = ".$id;
        $query = Yii::app()->db->createCommand($sqldata)->queryAll(); 
        foreach($query as $data){
        $row[] = array(
            'addressbookid'=>$data['addressbookid'],
            'fullname'=>$data['fullname'],
            'taxid'=>$data['taxid'],
            'taxcode'=>$data['taxcode'],
            'shipto'=>$data['shipto'],
            'headernote'=>$data['headernote']
        );
        }
        $result = array_merge($result,array('rows'=>$row));
        return CJSON::encode($result);
    }
    
    public function searchdetail(){
        header("Content-Type: application/json");
        $id = 0;
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $page       = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : 'mrogiheaderid';
        $order      = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
        $offset     = ($page - 1) * $rows;
        $result     = array();
        $row        = array();
		$connection		= Yii::app()->db;   
    
        $from = " from mrogidetail a0 
                  left join product a1 on a1.productid = a0.productid
                  left join unitofmeasure a2 on a2.unitofmeasureid = a0.unitofmeasureid";
        $where = " where a0.mrogiheaderid = ".$id;
        $sqlcount = 'select count(1) as total '.$from.' '.$where;
        $sql = "select a0.mrogidetailid,a0.mrogiheaderid,a0.productid,a0.qty,a0.unitofmeasureid,a0.netprice,a0.gidetailid,a0.itemnote,a1.productname as productname,a2.description as uomcode ".$from.$where." LIMIT ".$offset.",".$rows;
    
         //$connection->createCommand($sql)->bindvalue(':mrogiheaderid',$id,PDO::PARAM_STR);
        
        $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
        $res = $connection->createCommand($sql)->queryAll();
        foreach($res as $data){
            $row[] = array(
            'mrogidetailid' => $data['mrogidetailid'],
            'productid' => $data['productid'],
            'qty' => $data['qty'],
            'unitofmeasureid' => $data['unitofmeasureid'],
            'netprice' => $data['netprice'],
            'productname' => $data['productname'],
            'uomcode' => $data['uomcode'],
            'itemnote' => $data['itemnote'],
            'gidetailid' => $data['gidetailid']
            );
        }
        $result = array_merge($result,array('rows'=>$row));
        return CJSON::encode($result);
    }
    
    public function searchgihader(){
        header("Content-Type: application/json");
        $giheaderid = isset($_POST['giheaderid']) ? $_POST['giheaderid'] : '';
        $gino       = isset($_POST['gino']) ? $_POST['gino'] : '';
        $sono       = isset($_POST['sono']) ? $_POST['sono'] : '';
        $headernote = isset($_POST['headernote']) ? $_POST['headernote'] : '';
        $customer   = isset($_POST['customer']) ? $_POST['customer'] : '';
        $page       = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : 'giheaderid';
        $order      = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
        $offset     = ($page - 1) * $rows;
        $result     = array();
        $row        = array();
		$connection		= Yii::app()->db;
		$from = 'from giheader b 
                join soheader z on z.soheaderid = b.soheaderid
                join company c on c.companyid = z.companyid
                join addressbook d on d.addressbookid = z.addressbookid
                join employee e on e.employeeid = z.employeeid';
		$where = "where b.giheaderid in (select a.giheaderid from giretur a )
                and b.gino like '%".(isset($_REQUEST['gino'])?$_REQUEST['gino']:'')."%' AND b.recordstatus='3'";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select b.gino, b.giheaderid, z.sono, e.fullname as sales, b.headernote, c.companyname, d.fullname, d.addressbookid, c.companyid '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
        foreach ($cmd as $data) {
            $row[] = array(
            'giheaderid' => $data['giheaderid'],
            'gino' => $data['gino'],
            'companyname' => $data['companyname'],
            'addressbookid' => $data['addressbookid'],
            'sono' => $data['sono'],
            'companyid' => $data['companyid'],
            'fullname' => $data['fullname'],
            'sales' => $data['sales'],
            'headernote' => $data['headernote']);
        }
        $result = array_merge($result, array(
            'rows' => $row));
        return CJSON::encode($result);
    }
	
    public function searchmroinvoice(){
        header("Content-Type: application/json");
        $giheaderid = isset($_POST['giheaderid']) ? $_POST['giheaderid'] : '';
        $gino       = isset($_POST['gino']) ? $_POST['gino'] : '';
        $sono       = isset($_POST['sono']) ? $_POST['sono'] : '';
        $headernote = isset($_POST['headernote']) ? $_POST['headernote'] : '';
        $customer   = isset($_POST['customer']) ? $_POST['customer'] : '';
        $page       = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : 'giheaderid';
        $order      = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
        $offset     = ($page - 1) * $rows;
        $result     = array();
        $row        = array();
		$connection		= Yii::app()->db;
		$from = 'from mrogiheader b ';
		$where = "where b.mrogiheaderid
                and b.mrogino like '%".(isset($_REQUEST['mrogino'])?$_REQUEST['mrogino']:'')."%' AND b.recordstatus='2' AND b.mrogiheaderid NOT IN (SELECT mrogiheaderid FROM mroinvoice WHERE recordstatus=2) ORDER BY mrogiheaderid DESC ";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select b.mrogino, b.mrogidate, b.shipto, b.mrogiheaderid '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql .  ' limit '.$offset.','.$rows)->queryAll();
        foreach ($cmd as $data) {
            $row[] = array(
            'mrogiheaderid' => $data['mrogiheaderid'],
            'mrogino' => $data['mrogino'],
            'mrogidate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['mrogidate'])),
            'shipto' => $data['shipto']);
        }
        $result = array_merge($result, array(
            'rows' => $row));
        return CJSON::encode($result);
    }
    
  public function actionGenerategi()
  {
      header("Content-Type: application/json");
      if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Updatemrodetail(:vid,:vhid,:vdate,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vdate',(($_POST['mrogidate']!=='')?date(Yii::app()->params['datetodb'], strtotime($_POST['mrogidate'])):null), PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->id, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        // GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(false, $e->getMessage(), 1);
      }
     
         $sql = "SELECT d.addressbookid, d.fullname, t.taxid, t.taxcode, c.shipto, c.headernote
                FROM giheader a 
                JOIN soheader c ON a.soheaderid = c.soheaderid
                JOIN addressbook d ON d.addressbookid = c.addressbookid
                JOIN tax t ON t.taxid = c.taxid
                WHERE a.giheaderid = ".$_POST['hid'];
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        echo CJSON::encode(array(
                'status'=>'success',
                'addressbookid'=>$data['addressbookid'],
                'fullname'=>$data['fullname'],
                'taxid'=>$data['taxid'],
                'taxcode'=>$data['taxcode'],
                'shipto'=>$data['shipto'],
                'headernote'=>$data['headernote'],
                ));
    }
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
        $sql     = 'call Insertmrogi(:mrogidate,:giheaderid,:addressbookid,:taxid,:shipto,:headernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatemrogi(:mrogiheaderid,:mrogidate,:giheaderid,:addressbookid,:taxid,:shipto,:headernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':mrogiheaderid', $_POST['mrogiheaderid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['giheaderid']);
      }
      $command->bindvalue(':mrogidate', date(Yii::app()->params['datetodb'], strtotime($_POST['mrogidate'])), PDO::PARAM_STR);
      $command->bindvalue(':giheaderid', $_POST['giheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':addressbookid', $_POST['addressbookid'], PDO::PARAM_STR);
      $command->bindvalue(':taxid', $_POST['taxid'], PDO::PARAM_STR);
      $command->bindvalue(':shipto', $_POST['shipto'], PDO::PARAM_STR);
      $command->bindvalue(':headernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(true, 'insertsuccess', 1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 1);
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
        $sql     = 'call Insertgidetails(:vqty,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'UPDATE mrogidetail SET productid = :productid, qty = :qty, itemnote = :itemnote WHERE mrogidetailid = :vid';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['mrogidetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['mrogidetailid']);
      }
      $command->bindvalue(':productid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':qty', str_replace(",", "", $_POST['qty']), PDO::PARAM_STR);
      $command->bindvalue(':itemnote', $_POST['itemnote'], PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(true, 'insertsuccess', 1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 1);
    }
  }
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Deletemrogi(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage(), 1);
      }
    } else {
      GetMessage(true, 'chooseone', 1);
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
        $sql     = 'call Approvemrogi(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage(), 1);
      }
    } else {
      GetMessage(true, 'chooseone', 1);
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
        $sql     = 'call Purgegiheader(:vid,:vcreatedby)';
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
  public function actionPurgedetail()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeGidetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
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
		$sql = "select  a.mrogino,a.mrogidate,b.sono ,a.shipto, a.giheaderid,a.headernote,
						a.recordstatus,c.fullname as customer,d.fullname as sales,f.cityname,
						(
						select distinct g.mobilephone
						from addresscontact g 
						where g.addressbookid = c.addressbookid
						limit 1 
						) as hp,
							(
						select distinct h.phoneno
						from address h
						where h.addressbookid = c.addressbookid
						limit 1 
						) as phone 
						from mrogiheader a
                        left join giheader y on y.giheaderid = a.giheaderid
						left join soheader b on b.soheaderid = y.soheaderid 
						left join addressbook c on c.addressbookid = b.addressbookid
						left join employee d on d.employeeid = b.employeeid
						left join company e on e.companyid = b.companyid
						left join city f on f.cityid = e.cityid ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.mrogiheaderid in (".$_GET['id'].")";
		}
    $dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    foreach($dataReader as $row)
    {
    //$this->pdf->companyid = $row['companyid'];
    }
	  $this->pdf->title=getCatalog('giheader');
	  $this->pdf->AddPage('P',array(220,140));
		$this->pdf->AddFont('tahoma','','tahoma.php');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('tahoma');
	  // definisi font
	  

    foreach($dataReader as $row)
    {
      $this->pdf->setFontSize(9);      
      $this->pdf->text(10,$this->pdf->gety()+0,'No ');$this->pdf->text(25,$this->pdf->gety()+0,': '.$row['mrogino']);
			$this->pdf->text(10,$this->pdf->gety()+5,'Sales ');$this->pdf->text(25,$this->pdf->gety()+5,': '.$row['sales']);
      $this->pdf->text(140,$this->pdf->gety()+0,$row['cityname'].', '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['mrogidate'])));
			$this->pdf->text(10,$this->pdf->gety()+10,'No. SO ');$this->pdf->text(25,$this->pdf->gety()+10,': '.$row['sono']);
			$this->pdf->text(10,$this->pdf->gety()+15,'Dengan hormat,');
			$this->pdf->text(10,$this->pdf->gety()+20,'Bersama ini kami kirimkan barang-barang sebagai berikut:');
			$this->pdf->text(140,$this->pdf->gety()+5,'Kepada Yth, ');
      $this->pdf->text(140,$this->pdf->gety()+10,$row['customer']);
		
      $sql1 = "select distinct b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,d.description,f.description as rak, a.itemnote
								from mrogidetail a
                                inner join giheader z on z.giheaderid = '".$_GET['id']."'
                                inner join gidetail y on y.giheaderid = z.giheaderid
								inner join product b on b.productid = a.productid
								inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
								inner join sloc d on d.slocid = y.slocid
								left join storagebin f on f.storagebinid = y.storagebinid
								where mrogiheaderid = '".$_GET['id']."' group by b.productname, y.sodetailid order by sodetailid";
      $dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();

			$this->pdf->sety($this->pdf->gety()+25);
      $this->pdf->colalign = array('L','L','L','L','L','L','L');
      $this->pdf->setwidths(array(8,77,20,20,50,30));
			$this->pdf->colheader = array('No','Nama Barang','Qty','Unit','Gudang - Rak','Keterangan');
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array('L','L','L','L','L','L','L');
      $i=0;
      foreach($dataReader1 as $row1)
      {
        $i=$i+1;
        $this->pdf->row(array($i,$row1['productname'],
				Yii::app()->format->formatNumber($row1['vqty']),
				$row1['uomcode'],
				$row1['description'].' - '.$row1['rak'],
				$row1['itemnote']));
      }
			$this->pdf->colalign = array('C','C');
      $this->pdf->setwidths(array(20,170));
      $this->pdf->coldetailalign = array('L','L');
			$this->pdf->row(array('Ship To',$row['shipto'].' / '.$row['phone'].' / '.$row['hp']));

			$this->pdf->row(array('Note',$row['headernote']));
	  
			$this->pdf->colalign = array('C');
      $this->pdf->setwidths(array(150));
      $this->pdf->coldetailalign = array('L');
			$this->pdf->row(array(
			'Barang-barang tersebut diatas kami (saya) periksa dan terima dengan baik serta cukup.'
			));
			$this->pdf->checkNewPage(20);
			//$this->pdf->Image('images/ttdsj.jpg',5,$this->pdf->gety()+25,200);
						$this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->text(15,$this->pdf->gety(),'  Dibuat oleh,');$this->pdf->text(55,$this->pdf->gety(),' Disetujui oleh,');$this->pdf->text(96,$this->pdf->gety(),'  Diketahui oleh,');$this->pdf->text(137,$this->pdf->gety(),'Dibawa oleh,');$this->pdf->text(178,$this->pdf->gety(),' Diterima oleh,');
			$this->pdf->text(15,$this->pdf->gety()+22,'........................');$this->pdf->text(55,$this->pdf->gety()+22,'.........................');$this->pdf->text(96,$this->pdf->gety()+22,'........................');$this->pdf->text(137,$this->pdf->gety()+22,'........................');$this->pdf->text(178,$this->pdf->gety()+22,'........................');
			$this->pdf->text(15,$this->pdf->gety()+25,'Admin Gudang');$this->pdf->text(55,$this->pdf->gety()+25,' Kepala Gudang');$this->pdf->text(96,$this->pdf->gety()+25,'     Distribusi');$this->pdf->text(137,$this->pdf->gety()+25,'        Supir');$this->pdf->text(178,$this->pdf->gety()+25,'Customer/Toko');

		}
    // me-render ke browser
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'giheader';
    parent::actionDownxls();
    $sql = "select b.companyid, a.gino,a.gidate,b.sono ,b.shipto,a.giheaderid,a.headernote,
						a.recordstatus,c.fullname as customer,d.fullname as sales,f.cityname,g.mobilephone as hp,h.phoneno as phone
						from giheader a
						left join soheader b on b.soheaderid = a.soheaderid 
						left join addressbook c on c.addressbookid = b.addressbookid
						left join employee d on d.employeeid = b.employeeid
						left join company e on e.companyid = d.companyid
						left join city f on f.cityid = e.cityid
						left join addresscontact g on g.addressbookid = c.addressbookid
						left join address h on h.addressid = c.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.giheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['gino'])->setCellValueByColumnAndRow(4, $line, $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])));
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Sales')->setCellValueByColumnAndRow(1, $line, ': ' . $row['sales'])->setCellValueByColumnAndRow(4, $line, 'Kepada Yth, ');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No. SO')->setCellValueByColumnAndRow(1, $line, ': ' . $row['sono'])->setCellValueByColumnAndRow(4, $line, $row['customer']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dengan hormat,');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Bersama ini kami kirimkan barang-barang sebagai berikut: ');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Barang')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Gudang - Rak')->setCellValueByColumnAndRow(5, $line, 'Keterangan');
      $line++;
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,d.description,f.description as rak,itemnote
								from gidetail a
								inner join product b on b.productid = a.productid
								inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
								inner join sloc d on d.slocid = a.slocid
								left join storagebin f on f.storagebinid = a.storagebinid
								where giheaderid = " . $row['giheaderid'] . " group by b.productname,a.sodetailid order by sodetailid";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['vqty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['description'] . ' - ' . $row1['rak'])->setCellValueByColumnAndRow(5, $line, $row1['itemnote']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Ship To ')->setCellValueByColumnAndRow(1, $line, $row['shipto'] . ' / ' . $row['phone'] . ' / ' . $row['hp']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Barang-barang tersebut diatas kami (saya) periksa dan terima dengan baik serta cukup.');
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')->setCellValueByColumnAndRow(1, $line, 'Disetujui oleh, ')->setCellValueByColumnAndRow(2, $line, 'Diketahui oleh, ')->setCellValueByColumnAndRow(3, $line, 'Dibawa oleh, ')->setCellValueByColumnAndRow(4, $line, 'Diterima oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................')->setCellValueByColumnAndRow(4, $line, '........................');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Admin Gudang')->setCellValueByColumnAndRow(1, $line, 'Kepala Gudang')->setCellValueByColumnAndRow(2, $line, 'Distribusi')->setCellValueByColumnAndRow(3, $line, 'Supir')->setCellValueByColumnAndRow(4, $line, 'Customer/Toko');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
