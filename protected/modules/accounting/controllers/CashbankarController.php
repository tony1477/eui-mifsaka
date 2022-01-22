<?php
class CashbankarController extends Controller {
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
    $cashbankarid = isset($_POST['cashbankarid']) ? $_POST['cashbankarid'] : '';
    $ttntid       = isset($_POST['ttntid']) ? $_POST['ttntid'] : '';
    $docdate      = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $cashbankarid = isset($_GET['q']) ? $_GET['q'] : $cashbankarid;
    $ttntid       = isset($_GET['q']) ? $_GET['q'] : $ttntid;
    $docdate      = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankarid';
    $order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       = ($page - 1) * $rows;
    $page         = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows         = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort         = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order        = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset       = ($page - 1) * $rows;
    $result       = array();
    $row          = array();
    if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbankar t')->leftjoin('ttnt a', 'a.ttntid=t.ttntid')->where("(t.docdate like :docdate) or
                            (a.docno like :ttntid)", array(
        ':docdate' => '%' . $docdate . '%',
        ':ttntid' => '%' . $ttntid . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbankar t')->leftjoin('ttnt a', 'a.ttntid=t.ttntid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->where("(t.docdate like :docdate) or
                            (a.docno like :ttntid)", array(
        ':docdate' => '%' . $docdate . '%',
        ':ttntid' => '%' . $ttntid . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.docno')->from('cashbankar t')->leftjoin('ttnt a', 'a.ttntid=t.ttntid')->where("(t.docdate like :docdate) or
                            (a.docno like :ttntid)", array(
        ':docdate' => '%' . $docdate . '%',
        ':ttntid' => '%' . $ttntid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.docno')->from('cashbankar t')->leftjoin('ttnt a', 'a.ttntid=t.ttntid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->where("(t.docdate like :docdate) or
                            (a.docno like :ttntid)", array(
        ':docdate' => '%' . $docdate . '%',
        ':ttntid' => '%' . $ttntid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankarid' => $data['cashbankarid'],
        'ttntid' => $data['ttntid'],
        'docno' => $data['docno'],
        'docdate' => $data['docdate'],
        'recordstatuscashbankar' => $this->findstatusname("appcbin", $data['recordstatus'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchpay()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbarpay t')->leftjoin('currency a', 'a.currencyid=t.currencyid')->where('cashbankarid = :cashbankarid', array(
      ':cashbankarid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.currencyname')->from('cbarpay t')->leftjoin('currency a', 'a.currencyid=t.currencyid')->where('cashbankarid = :cashbankarid', array(
      ':cashbankarid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbarpayid' => $data['cbarpayid'],
        'cashbankarid' => $data['cashbankarid'],
        'cheqno' => $data['cheqno'],
        'tglterima' => $data['tglterima'],
        'tglcair' => $data['tglcair'],
        'amount' => $data['amount'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => $data['currencyrate'],
        'bankname' => $data['bankname'],
        'bankowner' => $data['bankowner']
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbarinv t')->leftjoin('invoice a', 'a.invoiceid=t.invoiceid')->where('cashbankarid = :cashbankarid', array(
      ':cashbankarid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.invoiceno,a.amount as nilai,a.invoicedate,(a.amount-t.payamount) as saldo')->from('cbarinv t')->leftjoin('invoice a', 'a.invoiceid=t.invoiceid')->where('cashbankarid = :cashbankarid', array(
      ':cashbankarid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbarinvid' => $data['cbarinvid'],
        'cashbankarid' => $data['cashbankarid'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'nilai' => Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $data['nilai']),
        'invoicedate' => $data['invoicedate'],
        'payamount' => Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $data['payamount']),
        'saldo' => $data['saldo']
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
        $sql     = 'call Insertcashbankar(:vttntid,:vdocdate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecashbankar(:vid,:vttntid,:vdocdate,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cashbankarid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cashbankarid']);
      }
      $command->bindvalue(':vttntid', $_POST['ttntid'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
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
        $sql     = 'call Insertcbarpay(:vcashbankarid,:vcheqno,:vtglterima,:vtglcair,:vamount,:vcurrencyid,:vcurrencyrate,:vbankname,:vbankowner,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecbarpay(:vid,:vcashbankarid,:vcheqno,:vtglterima,:vtglcair,:vamount,:vcurrencyid,:vcurrencyrate,:vbankname,:vbankowner,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbarpayid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbarpayid']);
      }
      $command->bindvalue(':vcashbankarid', $_POST['cashbankarid'], PDO::PARAM_STR);
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
        $sql     = 'call Insertcbarinv(:vcashbankarid,:vinvoiceid,:vpayamount,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecbarinv(:vid,:vcashbankarid,:vinvoiceid,:vpayamount,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbarinvid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbarinvid']);
      }
      $command->bindvalue(':vcashbankarid', $_POST['cashbankarid'], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceid', $_POST['invoiceid'], PDO::PARAM_STR);
      $command->bindvalue(':vpayamount', $_POST['payamount'], PDO::PARAM_STR);
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
        $sql     = 'call Purgecashbankar(:vid,:vcreatedby)';
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
        $sql     = 'call Purgecbarinv(:vid,:vcreatedby)';
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
    $sql = "select ttntid,docdate,recordstatus
				from cashbankar a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cashbankarid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('cashbankar');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('ttntid'),
      GetCatalog('docdate'),
      GetCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      40,
      40,
      40
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['ttntid'],
        $row1['docdate'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownXls();
    $sql = "select ttntid,docdate,recordstatus
				from cashbankar a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cashbankarid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('ttntid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['ttntid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    $this->getFooterXls($this->phpExcel);
  }
}