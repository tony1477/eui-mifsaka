<?php
class CbinController extends Controller {
  public $menuname = 'cbin';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexjournal() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchjournal();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into cbin (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inscbin').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'cbinid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $cbinid          = isset($_POST['cbinid']) ? $_POST['cbinid'] : '';
    $cbinno          = isset($_POST['cbinno']) ? $_POST['cbinno'] : '';
    $ttntid          = isset($_POST['ttntid']) ? $_POST['ttntid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $companyid         = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cbinid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();	
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appcbin')")->queryScalar();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbin t')
		->leftjoin('ttnt a', 'a.ttntid=t.ttntid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.cbinno,'') like :cbinno) and
			(coalesce(t.docdate,'') like :docdate) and
			(coalesce(b.companyname,'') like :companyid) and
			(coalesce(t.cbinid,'') like :cbinid) and
			(coalesce(a.docno,'') like :ttntid)  and t.recordstatus in (".getUserRecordStatus('listcbin').") and t.recordstatus < {$maxstat} and t.companyid 
            in (".getUserObjectWfValues('company','listcbin').")", array(
      ':cbinno' => '%' . $cbinno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cbinid' => '%' . $cbinid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.docno,b.companyname')->from('cbin t')
		->leftjoin('ttnt a', 'a.ttntid=t.ttntid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.cbinno,'') like :cbinno) and
			(coalesce(t.docdate,'') like :docdate) and
			(coalesce(b.companyname,'') like :companyid) and
			(coalesce(t.cbinid,'') like :cbinid) and
			(coalesce(a.docno,'') like :ttntid)  and t.recordstatus in (".getUserRecordStatus('listcbin').") and t.recordstatus < {$maxstat} and t.companyid 
            in (".getUserObjectWfValues('company','listcbin').")", array(
      ':cbinno' => '%' . $cbinno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cbinid' => '%' . $cbinid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbinid' => $data['cbinid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'cbinno' => $data['cbinno'],
        'ttntid' => $data['ttntid'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'recordstatus' => $data['recordstatus'],
        'recordstatuscbin' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchjournal() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbinjournal t')->leftjoin('currency a', 'a.currencyid=t.currencyid')->leftjoin('account b', 'b.accountid=t.accountid')->leftjoin('cheque c', 'c.chequeid=t.chequeid')->leftjoin('plant d', 'd.plantid=t.plantid')->leftjoin('addressbook e','e.addressbookid=t.customerid')->where('cbinid = :cbinid', array(
      ':cbinid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.currencyname,b.accountname,c.chequeno,d.plantcode,e.fullname')->from('cbinjournal t')->leftjoin('currency a', 'a.currencyid=t.currencyid')->leftjoin('account b', 'b.accountid=t.accountid')->leftjoin('cheque c', 'c.chequeid=t.chequeid')->leftjoin('plant d', 'd.plantid=t.plantid')->leftjoin('addressbook e','e.addressbookid=t.customerid')->where('cbinid = :cbinid', array(
      ':cbinid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbinjournalid' => $data['cbinjournalid'],
        'cbinid' => $data['cbinid'],
        'plantid' => $data['plantid'],
        'customerid' => $data['customerid'],
        'fullname' => $data['fullname'],
        'plantcode' => $data['plantcode'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatCurrency($data['currencyrate']),
        'chequeid' => $data['chequeid'],
        'chequeno' => $data['chequeno'],
        'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
				    $cmd             = Yii::app()->db->createCommand()->select('sum(t.debit) as debit')->from('cbinjournal t')->leftjoin('currency a', 'a.currencyid=t.currencyid')->leftjoin('account b', 'b.accountid=t.accountid')->where('cbinid = :cbinid', array(
      ':cbinid' => $id
    ))->queryScalar();
		$footer[] = array(
      'accountname' => 'Total',
      'debit' => Yii::app()->format->formatCurrency($cmd),
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
        $sql     = 'call InsertCbin(:vttntid,:vdocdate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateCbin(:vid,:vcompanyid,:vdocdate,:vttntid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbinid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbinid']);
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
  public function actionSavejournal() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertCbinjournal(:vcbinid,:vplantid,:vaccountid,:vdebit,:vcurrencyid,:vcurrencyrate,:vchequeid,:vtglcair,:vcustomerid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateCbinjournal(:vid,:vcbinid,:vplantid,:vaccountid,:vdebit,:vcurrencyid,:vcurrencyrate,:vchequeid,:vtglcair,:vcustomerid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbinjournalid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbinjournalid']);
      }
      $command->bindvalue(':vcbinid', $_POST['cbinid'], PDO::PARAM_STR);
      $command->bindvalue(':vplantid', $_POST['plantid'], PDO::PARAM_STR);
      $command->bindvalue(':vaccountid', $_POST['accountid'], PDO::PARAM_STR);
      $command->bindvalue(':vdebit', $_POST['debit'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vchequeid', $_POST['chequeid'], PDO::PARAM_STR);
      $command->bindvalue(':vtglcair', date(Yii::app()->params['datetodb'], strtotime($_POST['tglcair'])), PDO::PARAM_STR);
      $command->bindvalue(':vcustomerid', $_POST['customerid'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
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
  public function actionPurge() {
		parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecbin(:vid,:vcreatedby)';
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
  public function actionPurgejournal() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecbinjournal(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
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
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveCbin(:vid,:vcreatedby)';
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
        $sql     = 'call DeleteCbIn(:vid,:vcreatedby)';
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
    $sql = "select distinct a.cbinid,a.cbinno,a.docdate as cbindate,c.docno as ttntno,c.docdate as ttntdate,b.companyid,concat('Pelunasan Piutang ',c.docno) as uraian
                        from cbin a
                        left join company b on b.companyid = a.companyid
                        left join ttnt c on c.ttntid = a.ttntid
                        left join cbinjournal d on d.cbinid = a.cbinid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cbinid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('cbin');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(7);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['cbinno']);
      $this->pdf->text(60, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(70, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['cbindate'])));
      $this->pdf->text(100, $this->pdf->gety(), 'TTNT ');
      $this->pdf->text(130, $this->pdf->gety(), ': ' . $row['ttntno']);
      $this->pdf->text(160, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(180, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['ttntdate'])));
      $sql1        = "select b.accountname,a.description,d.chequeno,a.tglcair,a.debit,c.currencyname,a.currencyrate,e.fullname
                            from cbinjournal a
                            left join account b on b.accountid=a.accountid
                            left join currency c on c.currencyid=a.currencyid
														left join cheque d on d.chequeid = a.chequeid
                            left join addressbook e on e.addressbookid = a.customerid
                            where a.cbinid = " . $row['cbinid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->colalign = array(
        'C',
        'L',
        'L',
        'L',
        'L',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        40,
        45,
        45,
        20,
        20,
        25,
        25
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun',
        'Keterangan',
        'Customer',
        'No. Cek/Giro',
        'Tgl. Cair',
        'Debit',
        'Kredit'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'L',
        'L',
        'L',
        'C',
        'R',
        'R'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['accountname'],
          $row1['fullname'],
          $row1['description'],
          $row1['chequeno'],
          $row1['tglcair'],
          Yii::app()->format->formatCurrency($row1['debit']),
          '0.00'
        ));
        $total = $row1['debit'] + $total;
      }
      $i = $i + 1;
      $this->pdf->row(array(
        $i,
        'KAS PENAMPUNG PIUTANG',
        $row['uraian'],
        '',
        '',
        '0.00',
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->setbordercell(array(
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB'
      ));
      $this->pdf->row(array(
        '',
        '',
        'Jumlah',
        '',
        '',
        Yii::app()->format->formatCurrency($total),
        Yii::app()->format->formatCurrency($total)
      ));
					$this->pdf->checkPageBreak(10);
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
  public function actionDownxls() {
    parent::actionDownXls();
    $sql = "select ttntid,docdate,recordstatus
				from cbin a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cbinid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('ttntid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['ttntid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    $this->getFooterXls($this->phpExcel);
  }
}