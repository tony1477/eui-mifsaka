<?php
class CashbankoutController extends Controller {
  public $menuname = 'cashbankout';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexinvoice() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchinvoice();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		parent::actionIndex();
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into cashbankout (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inscbout').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'cashbankoutid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $cashbankoutid   = isset($_POST['cashbankoutid']) ? $_POST['cashbankoutid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $cashbankoutno   = isset($_POST['cashbankoutno']) ? $_POST['cashbankoutno'] : '';
    $reqpayid        = isset($_POST['reqpayid']) ? $_POST['reqpayid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankoutid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $rec          = array();
    $com          = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appcbout')")->queryScalar();
			
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbankout t')
		->leftjoin('reqpay a', 'a.reqpayid=t.reqpayid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(b.companyname,'') like :companyid) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.cashbankoutid,'') like :cashbankoutid) and
						(coalesce(t.cashbankoutno,'') like :cashbankoutno) and
						(coalesce(a.reqpayno,'') like :reqpayid) and t.recordstatus in (".getUserRecordStatus('listcbout').") and t.recordstatus < {$maxstat} and t.companyid 
                        in (".getUserObjectWfValues('company','listcbout').")", array(
      ':companyid' => '%' . $companyid . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cashbankoutid' => '%' . $cashbankoutid . '%',
      ':cashbankoutno' => '%' . $cashbankoutno . '%',
      ':reqpayid' => '%' . $reqpayid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.reqpayno,b.companyname')->from('cashbankout t')
		->leftjoin('reqpay a', 'a.reqpayid=t.reqpayid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(b.companyname,'') like :companyid) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.cashbankoutid,'') like :cashbankoutid) and
						(coalesce(t.cashbankoutno,'') like :cashbankoutno) and
						(coalesce(a.reqpayno,'') like :reqpayid) and t.recordstatus in (".getUserRecordStatus('listcbout').") and t.recordstatus < {$maxstat} and t.companyid 
                        in (".getUserObjectWfValues('company','listcbout').")", array(
      ':companyid' => '%' . $companyid . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cashbankoutid' => '%' . $cashbankoutid . '%',
      ':cashbankoutno' => '%' . $cashbankoutno . '%',
      ':reqpayid' => '%' . $reqpayid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankoutid' => $data['cashbankoutid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'cashbankoutno' => $data['cashbankoutno'],
        'reqpayid' => $data['reqpayid'],
        'reqpayno' => $data['reqpayno'],
        'recordstatus' => $data['recordstatus'],
        'recordstatuscashbankout' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchinvoice() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbapinv t')->leftjoin('invoiceap a', 'a.invoiceapid=t.invoiceapid')->leftjoin('addressbook b', 'b.addressbookid=a.addressbookid')->leftjoin('poheader c', 'c.poheaderid=a.poheaderid')->leftjoin('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->leftjoin('currency e', 'e.currencyid=t.currencyid')->leftjoin('account f', 'f.accountid=t.accountid')->leftjoin('reqpayinv g', 'g.invoiceapid=t.invoiceapid')->leftjoin('ekspedisi h', 'h.ekspedisiid=t.ekspedisiid')->leftjoin('plant i', 'i.plantid=t.plantid')->leftjoin('cheque j', 'j.chequeid=t.chequeid')->where('cashbankoutid = :cashbankoutid', array(
      ':cashbankoutid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('distinct t.*,e.currencyname,a.invoiceno,(select xa.fullname from addressbook xa where xa.addressbookid = a.addressbookid) as supplier,
						a.invoicedate,(select xb.fullname from addressbook xb where xb.addressbookid = h.addressbookid) as ekspedisi,
							adddate(a.invoicedate,d.paydays) as duedate,a.amount,(a.amount-a.payamount-t.payamount) as saldo,f.accountname,i.plantcode,j.chequeno,
							h.ekspedisino,h.amount as nilai,h.docdate as tglexp')->from('cbapinv t')->leftjoin('invoiceap a', 'a.invoiceapid=t.invoiceapid')->leftjoin('addressbook b', 'b.addressbookid=a.addressbookid')->leftjoin('poheader c', 'c.poheaderid=a.poheaderid')->leftjoin('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->leftjoin('currency e', 'e.currencyid=t.currencyid')->leftjoin('account f', 'f.accountid=t.accountid')->leftjoin('reqpayinv g', 'g.invoiceapid=t.invoiceapid')->leftjoin('ekspedisi h', 'h.ekspedisiid=t.ekspedisiid')->leftjoin('plant i', 'i.plantid=t.plantid')->leftjoin('cheque j', 'j.chequeid=t.chequeid')->where('cashbankoutid = :cashbankoutid', array(
      ':cashbankoutid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbapinvid' => $data['cbapinvid'],
        'cashbankoutid' => $data['cashbankoutid'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'invoiceapid' => $data['invoiceapid'],
        'invoiceno' => $data['invoiceno'],
        'ekspedisiid' => $data['ekspedisiid'],
        'ekspedisino' => $data['ekspedisino'],
        'supplier' => $data['supplier'],
        'ekspedisi' => $data['ekspedisi'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'nilai' => Yii::app()->format->formatNumber($data['nilai']),
        'cashbankno' => $data['cashbankno'],
        'chequeid' => $data['chequeid'],
        'chequeno' => $data['chequeno'],
        'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
        'payamount' => Yii::app()->format->formatNumber($data['payamount']),
        'amountold' => Yii::app()->format->formatNumber($data['payamount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'bankaccountno' => $data['bankaccountno'],
        'bankname' => $data['bankname'],
        'bankowner' => $data['bankowner'],
        'itemnote' => $data['itemnote'],
        'saldo' => Yii::app()->format->formatNumber($data['saldo'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $cmd = Yii::app()->db->createCommand()->select('sum(a.amount) as amount,sum(a.amount-a.payamount-t.payamount) as saldo,
		sum(t.payamount) as paymount,
			sum(h.amount) as nilai')->from('cbapinv t')->leftjoin('invoiceap a', 'a.invoiceapid=t.invoiceapid')->leftjoin('addressbook b', 'b.addressbookid=a.addressbookid')->leftjoin('poheader c', 'c.poheaderid=a.poheaderid')->leftjoin('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->leftjoin('currency e', 'e.currencyid=t.currencyid')->leftjoin('account f', 'f.accountid=t.accountid')->leftjoin('reqpayinv g', 'g.invoiceapid=t.invoiceapid')->leftjoin('ekspedisi h', 'h.ekspedisiid=t.ekspedisiid')->where('cashbankoutid = :cashbankoutid', array(
      ':cashbankoutid' => $id
    ))->queryRow();
    $footer[] = array(
      'invoiceno' => 'Total',
      'amount' => Yii::app()->format->formatNumber($cmd['amount']),
      'payamount' => Yii::app()->format->formatNumber($cmd['paymount']),
      'nilai' => Yii::app()->format->formatNumber($cmd['nilai']),
      'saldo' => Yii::app()->format->formatNumber($cmd['saldo']),
    );
    $result   = array_merge($result, array(
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
        $sql     = 'call Insertcashbankout(:vdocdate,:vreqpayid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecashbankout(:vid,:vcompanyid,:vdocdate,:vreqpayid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cashbankoutid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cashbankoutid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vreqpayid', $_POST['reqpayid'], PDO::PARAM_STR);
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
  public function actionSaveinvoice() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertcbapinv(:vcashbankoutid,:vplantid,:vinvoiceapid,:vekspedisiid,:accountid,:vcashbankno,:vtglcair,:vpayamount,:vcurrencyid,:vcurrencyrate,:vbankaccountno,:vbankname,:vbankowner,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecbapinv(:vid,:vcashbankoutid,:vplantid,:vinvoiceapid,:vekspedisiid,:vaccountid,:vcashbankno,:vchequeid,:vtglcair,:vpayamount,:vcurrencyid,:vcurrencyrate,:vbankaccountno,:vbankname,:vbankowner,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbapinvid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbapinvid']);
      }
      $command->bindvalue(':vcashbankoutid', $_POST['cashbankoutid'], PDO::PARAM_STR);
      $command->bindvalue(':vplantid', $_POST['plantid'], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceapid', $_POST['invoiceapid'], PDO::PARAM_STR);
      $command->bindvalue(':vekspedisiid', $_POST['ekspedisiid'], PDO::PARAM_STR);
      $command->bindvalue(':vaccountid', $_POST['accountid'], PDO::PARAM_STR);
      $command->bindvalue(':vcashbankno', $_POST['cashbankno'], PDO::PARAM_STR);
      $command->bindvalue(':vchequeid', $_POST['chequeid'], PDO::PARAM_STR);
      $command->bindvalue(':vtglcair', date(Yii::app()->params['datetodb'], strtotime($_POST['tglcair'])), PDO::PARAM_STR);
      $command->bindvalue(':vpayamount', $_POST['payamount'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vbankaccountno', $_POST['bankaccountno'], PDO::PARAM_STR);
      $command->bindvalue(':vbankname', $_POST['bankname'], PDO::PARAM_STR);
      $command->bindvalue(':vbankowner', $_POST['bankowner'], PDO::PARAM_STR);
      $command->bindvalue(':vitemnote', $_POST['itemnote'], PDO::PARAM_STR);
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
  public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateCBREQPAY(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success',
            'div' => "Data generated"
          ));
        }
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage('failure', $e->getMessage());
      }
    }
    Yii::app()->end();
  }
  public function actionPurge() {
		parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecashbankout(:vid,:vcreatedby)';
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
  public function actionPurgeinvoice() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecbapinv(:vid,:vcreatedby)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select *,a.companyid,a.cashbankoutno,a.docdate,c.reqpayno
                        from cashbankout a
                        left join company b on b.companyid = a.companyid
                        left join reqpay c on c.reqpayid = a.reqpayid
                        ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cashbankoutid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('cashbankout');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(7);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['cashbankoutno']);
      $this->pdf->text(60, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(80, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(120, $this->pdf->gety(), 'Reqpay ');
      $this->pdf->text(140, $this->pdf->gety(), ': ' . $row['reqpayno']);
      $sql1        = "select a.*,b.accountname,c.currencyname,d.invoiceno,e.pono
                            from cbapinv a
                            left join account b on b.accountid = a.accountid
                            left join currency c on c.currencyid = a.currencyid
														left join invoiceap d on d.invoiceapid = a.invoiceapid
														left join poheader e on e.poheaderid = d.poheaderid
                            where cashbankoutid = " . $row['cashbankoutid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        50,
        15,
        25,
        40,
        30,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun',
        'Tgl Cair',
        'Dibayar',
        'No Cash/Bank',
        'Pemilik Akun',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'C',
        'R',
        'C',
        'C',
        'C'
      );
      $i                         = 0;
      $total                     = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['accountname'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['tglcair'])),
          Yii::app()->format->formatCurrency($row1['payamount']),
          $row1['cashbankno'],
          $row1['bankowner'],
          $row1['invoiceno'] . ' / ' . $row1['pono']
        ));
        $total += $row1['payamount'];
      }
      $this->pdf->row(array(
        '',
        'TOTAL',
        '',
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        170
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note:',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 18, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 18, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 20, '  Admin Kasir');
      $this->pdf->text(55, $this->pdf->gety() + 20, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 20, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveCashbankout(:vid,:vcreatedby)';
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
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteCashbankout(:vid,:vcreatedby)';
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
  public function actionDownxls()
  {
    parent::actionDownXls();
    $sql = "select docdate,reqpayid,recordstatus
				from cashbankout a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cashbankoutid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('reqpayid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['reqpayid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    $this->getFooterXls($this->phpExcel);
  }
}