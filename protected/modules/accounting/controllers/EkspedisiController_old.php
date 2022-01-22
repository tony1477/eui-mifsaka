<?php
class EkspedisiController extends Controller
{
  public $menuname = 'ekspedisi';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexpo()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchpo();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexmaterial()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchmaterial();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into ekspedisi (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inseksp').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'ekspedisiid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $ekspedisiid     = isset($_POST['ekspedisiid']) ? $_POST['ekspedisiid'] : '';
    $ekspedisino     = isset($_POST['ekspedisino']) ? $_POST['ekspedisino'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $addressbookid   = isset($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
    $amount          = isset($_POST['amount']) ? $_POST['amount'] : '';
    $currencyid      = isset($_POST['currencyid']) ? $_POST['currencyid'] : '';
    $currencyrate    = isset($_POST['currencyrate']) ? $_POST['currencyrate'] : '';
    $recordstatus    = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $ekspedisiid     = isset($_GET['q']) ? $_GET['q'] : $ekspedisiid;
    $ekspedisino     = isset($_GET['q']) ? $_GET['q'] : $ekspedisino;
    $docdate         = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $addressbookid   = isset($_GET['q']) ? $_GET['q'] : $addressbookid;
    $amount          = isset($_GET['q']) ? $_GET['q'] : $amount;
    $currencyid      = isset($_GET['q']) ? $_GET['q'] : $currencyid;
    $currencyrate    = isset($_GET['q']) ? $_GET['q'] : $currencyrate;
    $recordstatus    = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'ekspedisiid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('ekspedisi t')->leftjoin('addressbook a', 'a.addressbookid=t.addressbookid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('company c', 'c.companyid=t.companyid')->where("((docdate like :docdate) or (ekspedisino like :ekspedisino) or
						(a.fullname like :addressbookid)) and t.recordstatus in (".getUserRecordStatus('listeksp').") and 
					t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':ekspedisino' => '%' . $ekspedisino . '%',
      ':addressbookid' => '%' . $addressbookid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.fullname as supplier,b.currencyname,c.companyname')->from('ekspedisi t')->leftjoin('addressbook a', 'a.addressbookid=t.addressbookid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('company c', 'c.companyid=t.companyid')->where("((docdate like :docdate) or (ekspedisino like :ekspedisino) or
						(a.fullname like :addressbookid)) and t.recordstatus in (".getUserRecordStatus('listeksp').") and 
			t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':ekspedisino' => '%' . $ekspedisino . '%',
      ':addressbookid' => '%' . $addressbookid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ekspedisiid' => $data['ekspedisiid'],
        'ekspedisino' => $data['ekspedisino'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'addressbookid' => $data['addressbookid'],
        'supplier' => $data['supplier'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'recordstatusekspedisi' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchpo()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('ekspedisipo t')->join('poheader a', 'a.poheaderid=t.poheaderid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->where('t.ekspedisiid = :ekspedisiid', array(
      ':ekspedisiid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.docdate,a.pono,b.fullname as supplier')->from('ekspedisipo t')->join('poheader a', 'a.poheaderid=t.poheaderid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->where('t.ekspedisiid = :ekspedisiid', array(
      ':ekspedisiid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ekspedisipoid' => $data['ekspedisipoid'],
        'ekspedisiid' => $data['ekspedisiid'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'supplier' => $data['supplier']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchmaterial()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'eksmatid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('eksmat t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid=t.uomid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->where('ekspedisiid = :ekspedisiid', array(
      ':ekspedisiid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.currencyname')->from('eksmat t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid=t.uomid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->where('ekspedisiid = :ekspedisiid', array(
      ':ekspedisiid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'eksmatid' => $data['eksmatid'],
        'ekspedisiid' => $data['ekspedisiid'],
        'ekspedisipoid' => $data['ekspedisipoid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'expense' => Yii::app()->format->formatNumber($data['expense']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate'])
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
        $sql     = 'call Insertekspedisi(:vcompanyid,:vdocdate,:vaddressbookid,:vamount,:vcurrencyid,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateekspedisi(:vid,:vcompanyid,:vdocdate,:vaddressbookid,:vamount,:vcurrencyid,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['ekspedisiid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['ekspedisiid']);
      }
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vaddressbookid', $_POST['addressbookid'], PDO::PARAM_STR);
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vamount', $_POST['amount'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
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
  public function actionSavepo()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertekspedisipo(:vekspedisiid,:vpoheaderid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateekspedisipo(:vid,:vekspedisiid,:vpoheaderid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['ekspedisipoid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['ekspedisipoid']);
      }
      $command->bindvalue(':vekspedisiid', $_POST['ekspedisiid'], PDO::PARAM_STR);
      $command->bindvalue(':vpoheaderid', $_POST['poheaderid'], PDO::PARAM_STR);
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
  public function actionSavematerial()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Inserteksmat(:vekspedisiid,:vekspedisipoid,:vproductid,:vqty,:vuomid,:vexpense,:vcurrencyid,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateeksmat(:vid,:vekspedisiid,:vekspedisipoid,:vproductid,:vqty,:vuomid,:vexpense,:vcurrencyid,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['eksmatid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['eksmatid']);
      }
      $command->bindvalue(':vekspedisiid', $_POST['ekspedisiid'], PDO::PARAM_STR);
      $command->bindvalue(':vekspedisipoid', $_POST['ekspedisipoid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vexpense', $_POST['expense'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
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
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveEkspedisi(:vid,:vcreatedby)';
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteEkspedisi(:vid,:vcreatedby)';
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
        $sql     = 'call Purgeekspedisi(:vid,:vcreatedby)';
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
  public function actionPurgepo()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeekspedisipo(:vid,:vcreatedby)';
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
  public function actionPurgematerial()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeeksmat(:vid,:vcreatedby)';
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
    $sql = "select *,a.ekspedisino, c.pono, a.docdate as eksdate, d.fullname as kurir,
                        (select zz.fullname
                        from poheader z
                        left join addressbook zz on zz.addressbookid = z.addressbookid
                        where z.poheaderid = b.poheaderid) as supplier
                        from ekspedisi a
                        left join ekspedisipo b on b.ekspedisiid = a.ekspedisiid
                        left join poheader c on c.poheaderid = b.poheaderid
                        left join addressbook d on d.addressbookid = a.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.ekspedisiid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('ekspedisi');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['ekspedisino']);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'Ekspedisi ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . $row['kurir']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['eksdate'])));
      $this->pdf->text(120, $this->pdf->gety() + 6, 'PO ');
      $this->pdf->text(140, $this->pdf->gety() + 6, ': ' . $row['pono'] . ' / ' . $row['supplier']);
      $sql1        = "select *,b.productname,c.currencyname,d.uomcode
                            from eksmat a
                            left join product b on b.productid = a.productid
                            left join currency c on c.currencyid = a.currencyid
                            left join unitofmeasure d on d.unitofmeasureid = a.uomid
                            where ekspedisiid = " . $row['ekspedisiid'];
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
        100,
        20,
        20,
        30,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Product',
        'Qty',
        'Satuan',
        'Biaya',
        'Mata Uang'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'C'
      );
      $i                         = 0;
      $totalexpense              = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['expense']),
          $row1['currencyname']
        ));
        $totalexpense += $row1['expense'];
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        'Total',
        Yii::app()->format->formatCurrency($totalexpense),
        $row1['currencyname']
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
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->checkNewPage(40);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '    Admin AP');
      $this->pdf->text(55, $this->pdf->gety() + 25, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select docdate,addressbookid,amount,currencyid,currencyrate,recordstatus
				from ekspedisi a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.ekspedisiid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('amount'))->setCellValueByColumnAndRow(3, 1, GetCatalog('currencyid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('currencyrate'))->setCellValueByColumnAndRow(5, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['amount'])->setCellValueByColumnAndRow(3, $i + 1, $row1['currencyid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['currencyrate'])->setCellValueByColumnAndRow(5, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ekspedisi.xlsx"');
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