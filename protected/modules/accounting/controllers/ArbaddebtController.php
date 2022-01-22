<?php
class ArbaddebtController extends Controller {
  public $menuname = 'arbaddebt';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexacc() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchacc();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into arbaddebt (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insarbaddebt').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'arbaddebtid' => $id
			));
    }
  }
  public function actionGetinvoice() {
    $invoiceid = $_POST['invoiceid'];

    $sql = "select invoiceno, invoicedate, amount, payamount from invoice where invoiceid = ".$invoiceid;
    $q = Yii::app()->db->createCommand($sql)->queryRow();

    if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'amount'=> $q['amount'],
				'payamount'=>$q['payamount'],
				'invoicedate'=>date(Yii::app()->params['dateviewfromdb'],strtotime($q['invoicedate']))
				));
			Yii::app()->end();
		}
  }
  public function search() {
    header("Content-Type: application/json");
    $arbaddebtid          = isset($_POST['arbaddebtid']) ? $_POST['arbaddebtid'] : '';
    $docno          = isset($_POST['docno']) ? $_POST['docno'] : '';
    $plantid          = isset($_POST['plantid']) ? $_POST['plantid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $companyid         = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'arbaddebtid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();	
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apparbaddebt')")->queryScalar();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('arbaddebt t')
		->leftjoin('plant a', 'a.plantid=t.plantid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.docno,'') like :docno) and
			(coalesce(t.docdate,'') like :docdate) and
			(coalesce(b.companyname,'') like :companyid) and
			(coalesce(t.arbaddebtid,'') like :arbaddebtid) and
			(coalesce(a.plantcode,'') like :plantid)  and t.recordstatus in (".getUserRecordStatus('listarbaddebt').") and t.recordstatus < {$maxstat} and t.companyid 
            in (".getUserObjectWfValues('company','listarbaddebt').")", array(
      ':docno' => '%' . $docno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':arbaddebtid' => '%' . $arbaddebtid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':plantid' => '%' . $plantid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.plantcode,b.companyname, 
        (select sum(debit) from arbaddebtacc z where z.arbaddebtid = t.arbaddebtid) as debit,
				(select sum(credit) from arbaddebtacc z where z.arbaddebtid = t.arbaddebtid) as credit')
    ->from('arbaddebt t')
		->leftjoin('plant a', 'a.plantid=t.plantid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.docno,'') like :docno) and
			(coalesce(t.docdate,'') like :docdate) and
			(coalesce(b.companyname,'') like :companyid) and
			(coalesce(t.arbaddebtid,'') like :arbaddebtid) and
			(coalesce(a.plantcode,'') like :plantid)  and t.recordstatus in (".getUserRecordStatus('listarbaddebt').") and t.recordstatus < {$maxstat} and t.companyid 
            in (".getUserObjectWfValues('company','listarbaddebt').")", array(
      ':docno' => '%' . $docno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':arbaddebtid' => '%' . $arbaddebtid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':plantid' => '%' . $plantid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'arbaddebtid' => $data['arbaddebtid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'plantcode' => $data['plantcode'],
        'plantid' => $data['plantid'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusarbaddebt' => $data['statusname'],
        'debit' => $data['debit'],
        'credit' => $data['credit']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('arbaddebtdet t')
    ->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand('select t.*,amount-payamount as saldo
			from arbaddebtdet t
			where arbaddebtid = '.$id
    )->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'arbaddebtdetid' => $data['arbaddebtdetid'],
        'arbaddebtid' => $data['arbaddebtid'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'],strtotime($data['invoicedate'])),
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'saldo' => Yii::app()->format->formatCurrency($data['saldo']),
        'amount' => Yii::app()->format->formatCurrency($data['amount']),
        'payamount' => Yii::app()->format->formatCurrency($data['payamount']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
        
    $cmd = Yii::app()->db->createCommand()->select('sum(t.amount) as amount, sum(t.payamount) as payamount, sum(t.amount-t.payamount) as saldo')
      ->from('arbaddebtdet t')
      ->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryRow();
		$footer[] = array(
      'fullname' => 'Total',
      'saldo' => Yii::app()->format->formatCurrency($cmd['saldo']),
      'amount' => Yii::app()->format->formatCurrency($cmd['amount']),
      'payamount' => Yii::app()->format->formatCurrency($cmd['payamount']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchacc() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('arbaddebtacc t')
    ->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*')
    ->from('arbaddebtacc t')
    ->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'arbaddebtaccid' => $data['arbaddebtaccid'],
        'arbaddebtid' => $data['arbaddebtid'],
        'accountid' => $data['accountid'],
        'employeeid' => $data['employeeid'],
        'employeename' => $data['employeename'],
        'accountname' => $data['accountname'],
        'debit' => Yii::app()->format->formatCurrency($data['debit']),
        'credit' => Yii::app()->format->formatCurrency($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'ratevalue' => Yii::app()->format->formatCurrency($data['ratevalue']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
        
    $cmd = Yii::app()->db->createCommand()->select('sum(t.debit) as debit, sum(t.credit) as credit')
      ->from('arbaddebtacc t')
      ->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
      'debit' => Yii::app()->format->formatCurrency($cmd['debit']),
      'credit' => Yii::app()->format->formatCurrency($cmd['credit']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertarbaddebt(:vcompanyid,:vplantid,:vdocdate,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatearbaddebt(:vid,:vcompanyid,:vplantid,:vdocdate,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['arbaddebtid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['arbaddebtid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vplantid', $_POST['plantid'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage(), 1);
    }
  }
  public function actionSavedetail() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertarbaddebtdetail(:varbaddebtid,:vaddressbookid,:vinvoiceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatearbaddebtdetail(:vid,:varbaddebtid,:vaddressbookid,:vinvoiceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['arbaddebtdetid'], PDO::PARAM_STR);
        //$this->DeleteLock($this->menuname, $_POST['arbaddebtdetailid']);
      }
      $command->bindvalue(':varbaddebtid', $_POST['arbaddebtid'], PDO::PARAM_STR);
      $command->bindvalue(':vaddressbookid', $_POST['addressbookid'], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceid', $_POST['invoiceid'], PDO::PARAM_STR);
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
  public function actionSaveacc() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
		if($_POST['employeeid'] == ''){$employeeid = 0;}else{$employeeid = $_POST['employeeid'];}
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertarbaddebtacc(:varbaddebtid,:vaccountid,:vemployeeid,:vdebit,:vcurrencyid,:vratevalue,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatearbaddebtacc(:vid,:varbaddebtid,:vaccountid,:vemployeeid,:vdebit,:vcurrencyid,:vratevalue,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['arbaddebtaccid'], PDO::PARAM_STR);
        //$this->DeleteLock($this->menuname, $_POST['arbaddebtaccid']);
      }
      $command->bindvalue(':varbaddebtid', $_POST['arbaddebtid'], PDO::PARAM_STR);
      $command->bindvalue(':vaccountid', $_POST['accountid'], PDO::PARAM_STR);
      $command->bindvalue(':vemployeeid', $employeeid, PDO::PARAM_STR);
      $command->bindvalue(':vdebit', $_POST['debit'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vratevalue', $_POST['ratevalue'], PDO::PARAM_STR);
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
  public function actionPurgedetail() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgearbaddebtdetail(:vid,:vcreatedby)';
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
  public function actionPurgeacc() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgearbaddebtacc(:vid,:vcreatedby)';
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
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvearbaddebt(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Deletearbaddebt(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $id = $_GET['id'];
    $sql = "select arbaddebtid, a.companyid, companyname, plantcode, headernote, docno, docdate
    from arbaddebt a 
    left join company b on b.companyid = a.companyid
    left join plant c on c.plantid = a.plantid
    where a.arbaddebtid=".$id;
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    $q = Yii::app()->db->createCommand($sql)->queryRow();

		$this->pdf->companyid = $q['companyid'];
    $this->pdf->title = 'Piutang Bad Debt ';
		$this->pdf->AddPage('P');

		$this->pdf->sety($this->pdf->gety()+0);
	 
		foreach($dataReader as $row)
		{                
			$this->pdf->SetFont('Arial','B',14);
			$this->pdf->text(10,$this->pdf->gety()+5,'Daftar Piutang Dagang Bad Debt ');
      $this->pdf->SetFont('Arial','',11);
			$this->pdf->text(10,$this->pdf->gety()+10,'Perusahaan : '.$row['companyname']);
			$this->pdf->text(10,$this->pdf->gety()+15,'Cabang        : '.$row['plantcode']);
			$this->pdf->text(10,$this->pdf->gety()+20,'Ket               : '.$row['headernote']);
      $this->pdf->setY($this->pdf->getY()+20);

      $sql1 = "select distinct fullname,addressbookid
        from arbaddebtdet a
        where a.arbaddebtid = ".$row['arbaddebtid'];
      
      $nilaitot1 = 0;
      $dibayar1 = 0;
      $sisa1 = 0;

			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      foreach($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->text(10,$this->pdf->gety()+5,'Customer : '.$row1['fullname']);
        $this->pdf->setY($this->pdf->getY()+5);
        
        $this->pdf->sety($this->pdf->gety()+7);
        $this->pdf->setFont('Arial','B',8);
        $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
        $this->pdf->setwidths(array(10,25,18,18,11,8,25,25,25,30));
        $this->pdf->colheader = array('No','Dokumen','Tanggal','j_tempo','Umur','UT','Nilai','Kum_bayar','Sisa','Sales');
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array('C','C','C','C','C','C','R','R','R','L');
        $this->pdf->setFont('Arial','',8);
        $i=0;
        $nilaitot = 0;
        $dibayar = 0;
        $sisa = 0;

        $sql2 = "select *
          from (select if(c.isdisplay=1,concat(t.invoiceno,'_D'),t.invoiceno) as invoiceno,t.invoicedate,e.paydays,
          date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
          datediff('{$row['docdate']}',t.invoicedate) as umur,
          datediff('{$row['docdate']}',date_add(t.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,
          t.amount,ff.fullname as sales,t.payamount
          from arbaddebtdet t
          join invoice a on a.invoiceid = t.invoiceid 
          join giheader b on b.giheaderid = a.giheaderid
          join soheader c on c.soheaderid = b.soheaderid
          join addressbook d on d.addressbookid = t.addressbookid
          join paymentmethod e on e.paymentmethodid = t.paymentmethodid
          join employee ff on ff.employeeid = c.employeeid
          where d.addressbookid = {$row1['addressbookid']}
          and t.arbaddebtid = {$row['arbaddebtid']})z";
        $dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
        foreach($dataReader2 as $row2) {
          $i+=1;
          $this->pdf->row(array(
            $i,$row2['invoiceno'],
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])),
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['jatuhtempo'])),
            $row2['umur'],
            //'',
            $row2['umurtempo'],
            //'',
            Yii::app()->format->formatCurrency($row2['amount']),
            Yii::app()->format->formatCurrency($row2['payamount']),
            Yii::app()->format->formatCurrency(($row2['amount']-$row2['payamount'])),
            //'',
            $row2['sales'],
          ));
          $nilaitot += $row2['amount'];
          $dibayar += $row2['payamount'];
          $sisa += ($row2['amount']-$row2['payamount']);  
          $this->pdf->checkPageBreak(20);
        }
        $this->pdf->setFont('Arial','B',9);
        $this->pdf->setwidths(array(10,80,25,25,25,30));
        $this->pdf->coldetailalign = array('L','L','R','R','R');
        $this->pdf->row(array(
            '','Total '.$row1['fullname'],
            Yii::app()->format->formatCurrency($nilaitot),
            Yii::app()->format->formatCurrency($dibayar),
            Yii::app()->format->formatCurrency(($sisa)),
        ));
        $nilaitot1 += $nilaitot;
        $dibayar1 += $dibayar;
        $sisa1 += $sisa; 
      }
      $this->pdf->setY($this->pdf->getY()+10);
      $this->pdf->setFont('Arial','B',10);
      $this->pdf->setwidths(array(10,60,30,30,30,30));
      $this->pdf->coldetailalign = array('L','L','R','R','R');
      $this->pdf->row(array(
          '','Total BAD DEBT',
          Yii::app()->format->formatCurrency($nilaitot1),
          Yii::app()->format->formatCurrency($dibayar1),
          Yii::app()->format->formatCurrency(($sisa1)),
      ));
	  
	  $this->pdf->setFont('Arial','B',11);
      $this->pdf->text(10,$this->pdf->gety()+10,'Jurnal ');

      $this->pdf->setY($this->pdf->getY()+13);
      $this->pdf->setFont('Arial','B',10);
      $this->pdf->colalign = array('C','L','C','C','C');
      $this->pdf->setwidths(array(10,70,30,30,15));
      $this->pdf->colheader = array('No','Account','Debit','Credit','Rate');
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array('L','L','R','R','C');
      $this->pdf->setFont('Arial','',8);
      $sqlacc = "select a.*, if(a.employeeid<>'',concat(accountname,' - ',employeename),accountname) as accountname
        from arbaddebtacc a
        where a.arbaddebtid = ".$row['arbaddebtid'];
      $qacc = Yii::app()->db->createCommand($sqlacc)->queryAll();

      $i=0;
      foreach($qacc as $rowx) {
        $i+=1;
        $this->pdf->row(array(
          $i,
          $rowx['accountname'],
          Yii::app()->format->formatCurrency($rowx['debit']),
          Yii::app()->format->formatCurrency($rowx['credit']),
          Yii::app()->format->formatCurrency($rowx['ratevalue'])
        ));
      }
    }
		$this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownXls();
    $sql = "select plantid,docdate,recordstatus
				from arbaddebt a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.arbaddebtid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('plantid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['plantid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    $this->getFooterXls($this->phpExcel);
  }
}