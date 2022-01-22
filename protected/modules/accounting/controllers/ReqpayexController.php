<?php
class ReqpayexController extends Controller
{
  public $menuname = 'reqpayex';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexekspedisi()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchekspedisi();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into reqpay (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inspayreq').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'reqpayid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $reqpayid        = isset($_POST['reqpayid']) ? $_POST['reqpayid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $reqpayno        = isset($_POST['reqpayno']) ? $_POST['reqpayno'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $reqpayid        = isset($_GET['q']) ? $_GET['q'] : $reqpayid;
    $docdate         = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $reqpayno        = isset($_GET['q']) ? $_GET['q'] : $reqpayno;
    $headernote      = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'reqpayid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpay t')->join('company a', 'a.companyid = t.companyid')->where("((docdate like :docdate) or
							(reqpayno like :reqpayno)) and t.recordstatus in (".getUserRecordStatus('listpayreq').") and
							t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyname')->from('reqpay t')->join('company a', 'a.companyid = t.companyid')->where("((docdate like :docdate) or
						(reqpayno like :reqpayno)) and t.recordstatus in (".getUserRecordStatus('listpayreq').") and
						t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'reqpayid' => $data['reqpayid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'reqpayno' => $data['reqpayno'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'headernote' => $data['headernote'],
        'recordstatusreqpay' => findstatusname("apppayreq", $data['recordstatus'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchekspedisi()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpayinv t')->join('ekspedisi a', 'a.ekspedisiid=t.ekspedisiid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('currency c', 'c.currencyid=a.currencyid')->join('tax d', 'd.taxid=t.taxid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.ekspedisino,b.fullname as supplier,a.docdate,
							a.amount,t.payamount as saldo,c.currencyname,d.taxcode')->from('reqpayinv t')->join('ekspedisi a', 'a.ekspedisiid=t.ekspedisiid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('currency c', 'c.currencyid=a.currencyid')->join('tax d', 'd.taxid=t.taxid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'reqpayinvid' => $data['reqpayinvid'],
        'reqpayid' => $data['reqpayid'],
        'ekspedisiid' => $data['ekspedisiid'],
        'ekspedisino' => $data['ekspedisino'],
        'supplier' => $data['supplier'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
        'taxno' => $data['taxno'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'payamount' => Yii::app()->format->formatNumber($data['payamount']),
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
        $sql     = 'call Insertreqpay(:vcompanyid,:vdocdate,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatereqpay(:vid,:vcompanyid,:vdocdate,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['reqpayid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['reqpayid']);
      }
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(true, 'insertsuccess', 1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 1);
    }
  }
  public function actionSaveekspedisi()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertreqpayexp(:vreqpayid,:vekspedisiid,:vtaxid,:vtaxno,:vtaxdate,:vcurrencyid,:vcurrencyrate,:vbankaccountno,:vbankname,:vbankowner,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatereqpayexp(:vid,:vreqpayid,:vekspedisiid,:vtaxid,:vtaxno,:vtaxdate,:vcurrencyid,:vcurrencyrate,:vbankaccountno,:vbankname,:vbankowner,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['reqpayinvid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['reqpayinvid']);
      }
      $command->bindvalue(':vreqpayid', $_POST['reqpayid'], PDO::PARAM_STR);
      $command->bindvalue(':vekspedisiid', $_POST['ekspedisiid'], PDO::PARAM_STR);
      $command->bindvalue(':vtaxid', $_POST['taxid'], PDO::PARAM_STR);
      $command->bindvalue(':vtaxno', $_POST['taxno'], PDO::PARAM_STR);
      $command->bindvalue(':vtaxdate', date(Yii::app()->params['datetodb'], strtotime($_POST['taxdate'])), PDO::PARAM_STR);
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteReqpay(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
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
        $sql     = 'call ApproveReqpay(:vid,:vcreatedby)';
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
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgereqpay(:vid,:vcreatedby)';
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
  public function actionPurgeinvoice()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgereqpayinv(:vid,:vcreatedby)';
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
  public function actiongeneratebank()
  {
    if (isset($_POST['ekspedisiid'])) {
      $bankaccountno = '';
      $bankname      = '';
      $bankowner     = '';
      $cmd           = Yii::app()->db->createCommand()->select('t.*,a.bankaccountno,a.bankname,a.accountowner')->from('ekspedisi t')->join('addressbook a', 'a.addressbookid=t.addressbookid')->where("ekspedisiid = '" . $_POST['ekspedisiid'] . "' ")->limit(1)->queryRow();
      $bankaccountno = $cmd['bankaccountno'];
      $bankname      = $cmd['bankname'];
      $bankowner     = $cmd['accountowner'];
    }
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'status' => 'success',
        'currencyid' => 40,
        'currencyrate' => 1,
        'bankaccountno' => $bankaccountno,
        'bankname' => $bankname,
        'bankowner' => $bankowner
      ));
      Yii::app()->end();
    }
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select distinct a.reqpayid,d.fullname as supplier,b.bankname,d.bankaccountno,a.companyid,d.accountowner,
	  (select sum(za.amount)
				from ekspedisi za
				join reqpayinv zb on zb.ekspedisiid = za.ekspedisiid
				where zb.reqpayid = a.reqpayid) as nilai
				from reqpay a 
				join reqpayinv b on b.reqpayid = a.reqpayid
				join ekspedisi c on c.ekspedisiid = b.ekspedisiid
				join addressbook d on d.addressbookid = c.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.reqpayid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('reqpay');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'Dibayarkan kepada ');
      $this->pdf->text(40, $this->pdf->gety() + 2, ': ' . $row['supplier']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Sejumlah Rp. ');
      $this->pdf->text(40, $this->pdf->gety() + 6, ': ' . Yii::app()->format->formatCurrency($row['nilai']));
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Terbilang ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . strtoupper($this->eja($row['nilai'])));
      $this->pdf->text(130, $this->pdf->gety() + 2, 'Bank / A.N');
      $this->pdf->text(160, $this->pdf->gety() + 2, ': ' . $row['bankname']);
      $this->pdf->text(170, $this->pdf->gety() + 2, '/  ' . $row['accountowner']);
      $this->pdf->text(130, $this->pdf->gety() + 6, 'No Rekening');
      $this->pdf->text(160, $this->pdf->gety() + 6, ': ' . $row['bankaccountno']);
      $sql1        = "select b.ekspedisino,d.fullname as supplier,b.docdate,adddate(b.docdate,e.paydays) as duedate,b.amount
        from reqpayinv a
        left join ekspedisi b on b.ekspedisiid = a.ekspedisiid
        left join poheader c on c.poheaderid = b.poheaderid 
				left join addressbook d on d.addressbookid = c.addressbookid
				left join paymentmethod e on e.paymentmethodid = c.paymentmethodid
        where reqpayid = " . $row['reqpayid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 15);
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
        25,
        25,
        25,
        25,
        25,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'Tgl Invoice',
        'Nilai',
        'Jth Tempo',
        'No Faktur pajak',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'C',
        'C',
        'R',
        'C',
        'C',
        'L'
      );
      $i                         = 0;
      $total                     = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['ekspedisino'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['docdate'])),
          Yii::app()->format->formatNumber($row1['amount']),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['duedate'])),
          $row1['fakturpajakno'],
          $row1['fakturpajakno']
        ));
        $total += $row1['amount'];
      }
      $this->pdf->setaligns(array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'L',
        'R'
      ));
      $this->pdf->setwidths(array(
        10,
        25,
        25,
        25,
        25,
        25,
        25
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        '',
        'TOTAL :',
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->checkNewPage(30);
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->text(10, $this->pdf->gety(), 'Diajukan oleh');
      $this->pdf->text(45, $this->pdf->gety(), 'Diperiksa oleh');
      $this->pdf->text(85, $this->pdf->gety(), 'Diketahui oleh');
      $this->pdf->text(125, $this->pdf->gety(), 'Disetujui oleh');
      $this->pdf->text(165, $this->pdf->gety(), 'Dibayar oleh');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(45, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(85, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(125, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(165, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(10, $this->pdf->gety() + 20, 'Adm H/D');
      $this->pdf->text(42, $this->pdf->gety() + 20, 'Divisi Acc & Finance');
      $this->pdf->text(85, $this->pdf->gety() + 20, 'Branch Manager');
      $this->pdf->text(125, $this->pdf->gety() + 20, 'Dir. Keuangan');
      $this->pdf->text(165, $this->pdf->gety() + 20, 'Bag. Bank pusat');
      $this->pdf->text(10, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(42, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(85, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(125, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(165, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->setFontSize(7);
      $this->pdf->text(10, $this->pdf->gety() + 33, 'NB :Faktur pajak wajib diisi jika pembayaran melalui Legal (Tanpa melampirkan faktur pajak lagi)');
      $this->pdf->text(10, $this->pdf->gety() + 38, '     :Dibuat rangkap 3, putih untuk Bag.Bank/Kasir, setelah dibayar diserahkan ke Adm H/D,Rangkap 2 utk Bag.Pajak,rangkap 3 Arsip H/D');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select docdate,reqpayno,headernote,recordstatus
				from reqpay a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.reqpayid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('reqpayno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(3, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['reqpayno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(3, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reqpay.xlsx"');
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