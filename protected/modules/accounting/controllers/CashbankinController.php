<?php
class CashbankinController extends Controller {
  public $menuname = 'cashbankin';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexpay() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchpay();
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
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into cashbankar (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inscbin').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'cashbankarid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $cashbankarid    = isset($_POST['cashbankarid']) ? $_POST['cashbankarid'] : '';
    $cashbankarno    = isset($_POST['cashbankarno']) ? $_POST['cashbankarno'] : '';
    $ttntid          = isset($_POST['ttntid']) ? $_POST['ttntid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $cashbankarid    = isset($_GET['q']) ? $_GET['q'] : $cashbankarid;
    $cashbankarno    = isset($_GET['q']) ? $_GET['q'] : $cashbankarno;
    $ttntid          = isset($_GET['q']) ? $_GET['q'] : $ttntid;
    $docdate         = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankarid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbankar t')->leftjoin('ttnt a', 'a.ttntid=t.ttntid')->leftjoin('company b', 'b.companyid=t.companyid')->where("((t.cashbankarno like :cashbankarno) or
						(t.docdate like :docdate) or
						(a.docno like :ttntid)) and t.recordstatus in (".getUserRecordStatus('listcbin').") and
				t.companyid in (".getUserObjectValues('company').")", array(
      ':cashbankarno' => '%' . $cashbankarno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.docno,b.companyname')->from('cashbankar t')->leftjoin('ttnt a', 'a.ttntid=t.ttntid')->leftjoin('company b', 'b.companyid=t.companyid')->where("((t.cashbankarno like :cashbankarno) or
						(t.docdate like :docdate) or
						(a.docno like :ttntid)) and t.recordstatus in (".getUserRecordStatus('listcbin').") and
				t.companyid in (".getUserObjectValues('company').")", array(
      ':cashbankarno' => '%' . $cashbankarno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankarid' => $data['cashbankarid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'cashbankarno' => $data['cashbankarno'],
        'ttntid' => $data['ttntid'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'recordstatuscashbankin' => findstatusname("appcbin", $data['recordstatus'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchpay() {
    header("Content-Type: application/json");
    $id        = 0;
    $cbarinvid = '';
    if (isset($_POST['cbarinvid'])) {
      $cbarinvid = $_POST['cbarinvid'];
    }
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbarpay t')->join('currency a', 'a.currencyid=t.currencyid')->join('cbarinv b', 'b.cbarinvid=t.cbarinvid and b.cashbankarid=t.cashbankarid')->leftjoin('account c', 'c.accountid=t.accountid')->where('b.cashbankarid = :cashbankarid and b.cbarinvid = :cbarinvid', array(
      ':cashbankarid' => $id,
      ':cbarinvid' => $cbarinvid
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.currencyname,c.accountname')->from('cbarpay t')->join('currency a', 'a.currencyid=t.currencyid')->join('cbarinv b', 'b.cbarinvid=t.cbarinvid and b.cashbankarid=t.cashbankarid')->leftjoin('account c', 'c.accountid=t.accountid')->where('b.cashbankarid = :cashbankarid and b.cbarinvid = :cbarinvid', array(
      ':cashbankarid' => $id,
      ':cbarinvid' => $cbarinvid
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbarpayid' => $data['cbarpayid'],
        'cashbankarid' => $data['cashbankarid'],
        'cbarinvid' => $data['cbarinvid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'cheqno' => $data['cheqno'],
        'tglterima' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglterima'])),
        'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'bankaccountno' => $data['bankaccountno'],
        'bankname' => $data['bankname'],
        'bankowner' => $data['bankowner'],
        'detailnote' => $data['detailnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbarinv t')->join('invoice a', 'a.invoiceid=t.invoiceid')->join('currency b', 'b.currencyid=t.currencyid')->where('cashbankarid = :cashbankarid', array(
      ':cashbankarid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.invoiceno,a.amount as nilai,a.invoicedate,(a.amount-a.payamount) as saldo,b.currencyname')->from('cbarinv t')->join('invoice a', 'a.invoiceid=t.invoiceid')->join('currency b', 'b.currencyid=t.currencyid')->where('cashbankarid = :cashbankarid', array(
      ':cashbankarid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbarinvid' => $data['cbarinvid'],
        'cashbankarid' => $data['cashbankarid'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'nilai' => Yii::app()->format->formatNumber($data['nilai']),
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'payamount' => Yii::app()->format->formatNumber($data['payamount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'saldo' => Yii::app()->format->formatNumber($data['saldo'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertcashbankin(:vttntid,:vdocdate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecashbankin(:vid,:vcompanyid,:vdocdate,:vttntid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cashbankarid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cashbankarid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vttntid', $_POST['ttntid'], PDO::PARAM_STR);
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
  public function actionSavepay() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertcbarpay(:vcashbankarid,:vcbarinvid,:vaccountid,:vcheqno,:vtglterima,:vtglcair,:vamount,:vcurrencyid,:vcurrencyrate,:vbankname,:vbankowner,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecbarpay(:vid,:vcashbankarid,:vcbarinvid,:vaccountid,:vcheqno,:vtglterima,:vtglcair,:vamount,:vcurrencyid,:vcurrencyrate,:vbankname,:vbankowner,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbarpayid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbarpayid']);
      }
      $command->bindvalue(':vcashbankarid', $_POST['cashbankarid'], PDO::PARAM_STR);
      $command->bindvalue(':vcbarinvid', $_POST['cbarinvid'], PDO::PARAM_STR);
      $command->bindvalue(':vaccountid', $_POST['accountid'], PDO::PARAM_STR);
      $command->bindvalue(':vcheqno', $_POST['cheqno'], PDO::PARAM_STR);
      $command->bindvalue(':vtglterima', date(Yii::app()->params['datetodb'], strtotime($_POST['tglterima'])), PDO::PARAM_STR);
      $command->bindvalue(':vtglcair', date(Yii::app()->params['datetodb'], strtotime($_POST['tglcair'])), PDO::PARAM_STR);
      $command->bindvalue(':vamount', $_POST['amount'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vbankname', $_POST['bankname'], PDO::PARAM_STR);
      $command->bindvalue(':vbankowner', $_POST['bankowner'], PDO::PARAM_STR);
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
        $sql     = 'call Insertcbarinv(:vcashbankarid,:vinvoiceid,:vpayamount,:vcurrencyid,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecbarinv(:vid,:vcashbankarid,:vinvoiceid,:vpayamount,:vcurrencyid,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbarinvid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbarinvid']);
      }
      $command->bindvalue(':vcashbankarid', $_POST['cashbankarid'], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceid', $_POST['invoiceid'], PDO::PARAM_STR);
      $command->bindvalue(':vpayamount', $_POST['payamount'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
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
        $sql     = 'call GenerateCBTTNT(:vid, :vhid)';
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
        $sql     = 'call Purgecashbankin(:vid,:vcreatedby)';
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
  public function actionPurgepay() {
		parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecbarpay(:vid,:vcreatedby)';
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
  public function actionPurgeinvoice() {
		parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecbarinv(:vid,:vcreatedby)';
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
        $sql     = 'call ApproveCashbankin(:vid,:vcreatedby)';
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
        $sql     = 'call DeleteCashbankin(:vid,:vcreatedby)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.cashbankarid,a.cashbankarno,a.docdate,c.docno as ttntno,g.sono,i.fullname as customer,c.description,b.companyid
                        from cashbankar a
                        left join company b on b.companyid = a.companyid
                        left join ttnt c on c.ttntid = a.ttntid
                        left join ttntdetail d on d.ttntid = c.ttntid
                        left join invoice e on e.invoiceid = d.invoiceid
                        left join giheader f on f.giheaderid = e.giheaderid
                        left join soheader g on g.soheaderid = f.soheaderid
                        left join employee h on h.employeeid = c.employeeid
                        left join addressbook i on i.addressbookid = g.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cashbankarid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('cashbankin');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['cashbankarno']);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'TTNT ');
      $this->pdf->text(130, $this->pdf->gety() + 2, ': ' . $row['ttntno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(120, $this->pdf->gety() + 6, 'SO ');
      $this->pdf->text(130, $this->pdf->gety() + 6, ': ' . $row['sono'] . ' / ' . $row['customer']);
      $sql1        = "select a.cashbankarid,b.invoiceno,c.currencyname,b.invoicedate,a.payamount, a.currencyrate
                            from cbarinv a
                            left join invoice b on b.invoiceid = a.invoiceid
                            left join currency c on c.currencyid = a.currencyid
                            where a.cashbankarid = " . $row['cashbankarid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->colalign = array(
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
        30,
        30,
        50,
        25
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'Tgl Invoice',
        'Diterima',
        'Mata Uang',
        'Kurs'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'C',
        'C',
        'R',
        'C',
        'C'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['invoiceno'],
          $row1['invoicedate'],
          Yii::app()->format->formatNumber($row1['payamount']),
          $row1['currencyname'],
          $row1['currencyrate']
        ));
      }
      $sql2        = "select a.cashbankarid,b.currencyname,d.invoiceno,a.cheqno, a.tglcair, a.amount, b.currencyname, a.bankowner
                            from cbarpay a
                            left join currency b on b.currencyid = a.currencyid
							left join cbarinv c on c.cbarinvid = a.cbarinvid
							left join invoice d on d.invoiceid = c.invoiceid
                            where a.cashbankarid = " . $row['cashbankarid'];
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
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
        30,
        30,
        30,
        30,
        30,
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'No Cek',
        'Tgl Cair',
        'Jumlah',
        'Mata Uang',
        'Pemilik Akun'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'C',
        'C',
        'C',
        'R',
        'C',
        'C'
      );
      $i                         = 0;
      foreach ($dataReader2 as $row2) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row2['invoiceno'],
          $row2['cheqno'],
          $row2['tglcair'],
          Yii::app()->format->formatNumber($row2['amount']),
          $row2['currencyname'],
          $row2['bankowner']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        50
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note: ',
        $row['description']
      ));
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(10, $this->pdf->gety(), 'Penerima');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(120, $this->pdf->gety(), 'Mengetahui Peminta');
      $this->pdf->text(170, $this->pdf->gety(), 'Peminta Barang');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '........................');
      $this->pdf->checkNewPage(40);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select ttntid,docdate,recordstatus
				from cashbankin a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cashbankarid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('ttntid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['ttntid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="cashbankin.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    unset($excel);
  }
}