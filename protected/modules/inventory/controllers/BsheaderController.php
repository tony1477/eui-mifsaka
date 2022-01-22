<?php
class BsheaderController extends Controller
{
  public $menuname = 'bsheader';
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
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData()
  {
    if (!isset($_GET['id'])) {
			$sql = "insert into bsheader (bsdate,recordstatus) values (now(),'".findstatusbyuser('insbs')."')";
			Yii::app()->db->createCommand($sql)->execute();
			$id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'bsheaderid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $bsheaderid = isset($_POST['bsheaderid']) ? $_POST['bsheaderid'] : '';
    $slocid     = isset($_POST['slocid']) ? $_POST['slocid'] : '';
    $bsdate     = isset($_POST['bsdate']) ? $_POST['bsdate'] : '';
    $bsheaderno = isset($_POST['bsheaderno']) ? $_POST['bsheaderno'] : '';
    $headernote = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $bsheaderid = isset($_GET['q']) ? $_GET['q'] : $bsheaderid;
    $slocid     = isset($_GET['q']) ? $_GET['q'] : $slocid;
    $bsdate     = isset($_GET['q']) ? $_GET['q'] : $bsdate;
    $bsheaderno = isset($_GET['q']) ? $_GET['q'] : $bsheaderno;
    $headernote = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'bsheaderid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appbs')")->queryScalar();
		
    if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('bsheader t')->leftjoin('sloc a', 'a.slocid = t.slocid')->where("
      ((coalesce(bsheaderid,'') like :bsheaderid) and 
    (coalesce(sloccode,'') like :sloccode) and 
    (coalesce(bsheaderno,'') like :bsheaderno) and 
    (coalesce(headernote,'') like :headernote) and
    (coalesce(bsdate,'') like :bsdate))
				and t.recordstatus in (".getUserRecordStatus('listbs').") and t.recordstatus < {$maxstat} and
				t.slocid in (".getUserObjectValues('sloc').")", array(
        ':bsheaderid' => '%' . $bsheaderid . '%',
        ':sloccode' => '%' . $slocid . '%',
        ':bsheaderno' => '%' . $bsheaderno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':bsdate' => '%' . $bsdate . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('bsheader t')->leftjoin('sloc a', 'a.slocid = t.slocid')->where("
      ((coalesce(bsheaderid,'') like :bsheaderid) and 
    (coalesce(sloccode,'') like :sloccode) and 
    (coalesce(bsheaderno,'') like :bsheaderno) and 
    (coalesce(headernote,'') like :headernote) and
    (coalesce(bsdate,'') like :bsdate))", array(
        ':bsheaderid' => '%' . $bsheaderid . '%',
        ':sloccode' => '%' . $slocid . '%',
        ':bsheaderno' => '%' . $bsheaderno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':bsdate' => '%' . $bsdate . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,a.description as slocdesc')->from('bsheader t')->leftjoin('sloc a', 'a.slocid = t.slocid')->where("
      ((coalesce(bsheaderid,'') like :bsheaderid) and 
    (coalesce(sloccode,'') like :sloccode) and 
    (coalesce(bsheaderno,'') like :bsheaderno) and 
    (coalesce(headernote,'') like :headernote) and
    (coalesce(bsdate,'') like :bsdate))
					and t.recordstatus in (".getUserRecordStatus('listbs').") and t.recordstatus < {$maxstat} and
					t.slocid in (".getUserObjectValues('sloc').")", array(
        ':bsheaderid' => '%' . $bsheaderid . '%',
        ':sloccode' => '%' . $slocid . '%',
        ':bsheaderno' => '%' . $bsheaderno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':bsdate' => '%' . $bsdate . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.sloccode,a.description as slocdesc')->from('bsheader t')->leftjoin('sloc a', 'a.slocid = t.slocid')->where("((bsheaderid like :bsheaderid) or (sloccode like :sloccode) or 
				(bsheaderno like :bsheaderno) or (headernote like :headernote) or (bsdate like :bsdate))
								and t.recordstatus in (".getUserRecordStatus('listbs').")and
					t.slocid in (".getUserObjectValues('sloc').")", array(
        ':bsheaderid' => '%' . $bsheaderid . '%',
        ':sloccode' => '%' . $slocid . '%',
        ':bsheaderno' => '%' . $bsheaderno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':bsdate' => '%' . $bsdate . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'bsheaderid' => $data['bsheaderid'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'] . ' - ' . $data['slocdesc'],
        'bsdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['bsdate'])),
        'bsheaderno' => $data['bsheaderno'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusbsheader' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchDetail()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'bsdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('bsdetail t')->leftjoin('bsheader g', 'g.bsheaderid = t.bsheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('ownership c', 'c.ownershipid = t.ownershipid')->leftjoin('materialstatus d', 'd.materialstatusid = t.materialstatusid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->leftjoin('currency f', 'f.currencyid = t.currencyid')->leftjoin('productdetail h', 'h.productid = t.productid and h.unitofmeasureid = t.unitofmeasureid and h.storagebinid = t.storagebinid and h.slocid = g.slocid')->where('t.bsheaderid = :bsheaderid', array(
      ':bsheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.ownershipname,d.materialstatusname,e.description,f.currencyname,ifnull(h.qty,0) as qtystock,ifnull(h.buyprice,0) as buypricestock')->from('bsdetail t')->leftjoin('bsheader g', 'g.bsheaderid = t.bsheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('ownership c', 'c.ownershipid = t.ownershipid')->leftjoin('materialstatus d', 'd.materialstatusid = t.materialstatusid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->leftjoin('currency f', 'f.currencyid = t.currencyid')->leftjoin('productdetail h', 'h.productid = t.productid and h.unitofmeasureid = t.unitofmeasureid and h.storagebinid = t.storagebinid and h.slocid = g.slocid')->where('t.bsheaderid = :bsheaderid', array(
      ':bsheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'bsdetailid' => $data['bsdetailid'],
        'bsheaderid' => $data['bsheaderid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNum($data['qty']),
        'qtystock' => Yii::app()->format->formatNum($data['qtystock']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'ownershipid' => $data['ownershipid'],
        'ownershipname' => $data['ownershipname'],
        'materialstatusid' => $data['materialstatusid'],
        'materialstatusname' => $data['materialstatusname'],
        'storagebinid' => $data['storagebinid'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'buyprice' => Yii::app()->format->formatNum($data['buyprice']),
        'buypricestock' => Yii::app()->format->formatNum($data['buypricestock']),
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'description' => $data['description'],
        'location' => $data['location'],
        'expiredate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['expiredate'])),
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteBS(:vid,:vcreatedby)';
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
        $sql     = 'call ApproveBS(:vid,:vcreatedby)';
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
  public function actionSave()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertbsheader(:vslocid,:vbsdate,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatebsheader(:vid,:vslocid,:vbsdate,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['bsheaderid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['bsheaderid']);
      }
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vbsdate', date(Yii::app()->params['datetodb'], strtotime($_POST['bsdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
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
        $sql     = 'call Insertbsdetail(:vbsheaderid,:vproductid,:vunitofmeasureid,:vqty,:vitemnote,:vlocation,:vownershipid,
					:vexpiredate,:vmaterialstatusid,:vstoragebin,:vcurrencyid,:vbuyprice,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatebsdetail(:vid,:vbsheaderid,:vproductid,:vunitofmeasureid,:vqty,:vitemnote,:vlocation,:vownershipid,
					:vexpiredate,:vmaterialstatusid,:vstoragebin,:vcurrencyid,:vbuyprice,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['bsdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['bsdetailid']);
      }
      $command->bindvalue(':vbsheaderid', $_POST['bsheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vunitofmeasureid', $_POST['unitofmeasureid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vitemnote', $_POST['itemnote'], PDO::PARAM_STR);
      $command->bindvalue(':vlocation', $_POST['location'], PDO::PARAM_STR);
      $command->bindvalue(':vownershipid', $_POST['ownershipid'], PDO::PARAM_STR);
      $command->bindvalue(':vexpiredate', date(Yii::app()->params['datetodb'], strtotime($_POST['expiredate'])), PDO::PARAM_STR);
      $command->bindvalue(':vmaterialstatusid', $_POST['materialstatusid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebin', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vbuyprice', $_POST['buyprice'], PDO::PARAM_STR);
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
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebsheader(:vid,:vcreatedby)';
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
        $sql     = 'call PurgeBsdetail(:vid,:vcreatedby)';
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
    $sql = "select a.bsheaderid,a.bsheaderno,a.bsdate,b.sloccode,a.headernote
						from bsheader a
						inner join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.bsheaderid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('bsheader');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(15, $this->pdf->gety(), 'No ');
      $this->pdf->text(40, $this->pdf->gety(), ': ' . $row['bsheaderno']);
      $this->pdf->text(70, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(95, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['bsdate'])));
      $this->pdf->text(120, $this->pdf->gety(), 'Gudang ');
      $this->pdf->text(150, $this->pdf->gety(), ': ' . $row['sloccode']);
      $i           = 0;
      $totalqty    = 0;
      $totaljumlah = 0;
      $sql1        = "select b.productname,a.qty,a.buyprice,c.uomcode,a.itemnote,a.location,d.ownershipname,a.expiredate,e.materialstatusname,f.description
							from bsdetail a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							inner join ownership d on d.ownershipid = a.ownershipid
							inner join materialstatus e on e.materialstatusid = a.materialstatusid
							inner join storagebin f on f.storagebinid = a.storagebinid
							where bsheaderid = " . $row['bsheaderid'] . " order by bsdetailid ";
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
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        7,
        45,
        20,
        15,
        22,
        25,
        30,
        20,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Satuan',
        'Buyprice',
        'Jumlah',
        'Rak',
        'Status',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'C',
        'L',
        'L',
        'L'
      );
      foreach ($dataReader1 as $row1) {
        if (GetMenuAuth('currency') == 'false') {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
            $row1['productname'],
            Yii::app()->format->formatNumber($row1['qty']),
            $row1['uomcode'],
            Yii::app()->format->formatCurrency($row1['buyprice']),
            Yii::app()->format->formatCurrency($row1['qty'] * $row1['buyprice']),
            $row1['description'],
            $row1['ownershipname'] . '-' . $row1['materialstatusname'],
            $row1['itemnote']
          ));
          $totalqty += $row1['qty'];
          $totaljumlah += $row1['qty'] * $row1['buyprice'];
        }
        if (GetMenuAuth('currency') == 'true') {
          $i = $i + 1;
          $this->pdf->row(array(
            $i,
            $row1['productname'],
            $row1['uomcode'] . '  ' . Yii::app()->format->formatNumber($row1['qty']),
            '0',
            '0',
            $row1['description'],
            $row1['ownershipname'] . '-' . $row1['materialstatusname'],
            $row1['itemnote']
          ));
        }
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->coldetailalign = array(
        'L',
        'R',
        'R',
        'R',
        'R',
        'C',
        'L',
        'L'
      );
      $this->pdf->row(array(
        '',
        'TOTAL',
        Yii::app()->format->formatNumber($totalqty),
        '',
        '',
        Yii::app()->format->formatCurrency($totaljumlah),
        '',
        ''
      ));
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), ' Diketahui oleh,');
      $this->pdf->text(137, $this->pdf->gety(), '     Disetujui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 18, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(137, $this->pdf->gety() + 18, '.................................');
      $this->pdf->text(15, $this->pdf->gety() + 20, '       Admin');
      $this->pdf->text(55, $this->pdf->gety() + 20, '    Supervisor');
      $this->pdf->text(96, $this->pdf->gety() + 20, 'Chief Accounting');
      $this->pdf->text(137, $this->pdf->gety() + 20, 'Manager Accounting');
    }
    $this->pdf->Output();
  }
  public function actionDownPDFminus()
  {
    parent::actionDownload();
    $sql = "select a.bsheaderid,a.bsheaderno,a.bsdate,b.sloccode,a.headernote
						from bsheader a
						inner join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.bsheaderid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = "Produk Yang Akan Minus Setelah Koreksi";
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['bsheaderno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['bsdate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'Gudang ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': ' . $row['sloccode']);
      $i           = 0;
      $totalqty    = 0;
      $totaljumlah = 0;
      $sql1        = "select * from (select *,qty+qtystock as selisih
						from (select bsdetailid,d.productname,sum(a.qty) as qty,
						ifnull((select ifnull(b.qty,0) from productstock b 
						where b.productid=a.productid 
						and b.slocid=c.slocid 
						and b.unitofmeasureid=a.unitofmeasureid 
						and b.storagebinid=a.storagebinid),0) as qtystock
						from bsdetail a
						join bsheader c on c.bsheaderid=a.bsheaderid
						join product d on d.productid=a.productid
						where a.bsheaderid in (" . $_GET['id'] . ")
						group by a.productid,unitofmeasureid,storagebinid) z) zz
						where selisih < 0";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
        $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->colalign = array('C','C','C','C','C');
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(7,105,30,30,30));
        $this->pdf->colheader = array('No','Nama Barang','Qty Koreksi','Qty Stock','Qty Setelah Koreksi');
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array('R','L','R','R','R');
      foreach ($dataReader1 as $row1) {
        $i                         = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          Yii::app()->format->formatNumber($row1['qtystock']),
          Yii::app()->format->formatNumber($row1['selisih'])
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'bsheader';
    parent::actionDownxls();
    $sql = "select a.bsheaderid,a.bsheaderno,a.bsdate,b.sloccode,a.headernote
						from bsheader a
						inner join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.bsheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'No')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['bsheaderno']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'Date')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['bsdate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'Gudang')
          ->setCellValueByColumnAndRow(1, $line, ': ' . $row['sloccode']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'No')
          ->setCellValueByColumnAndRow(1, $line, 'Nama Barang')
          ->setCellValueByColumnAndRow(2, $line, 'Qty')
          ->setCellValueByColumnAndRow(3, $line, 'Satuan')
          ->setCellValueByColumnAndRow(4, $line, 'Buyprice')
          ->setCellValueByColumnAndRow(5, $line, 'Jumlah')
          ->setCellValueByColumnAndRow(6, $line, 'Rak')
          ->setCellValueByColumnAndRow(7, $line, 'Status')
          ->setCellValueByColumnAndRow(8, $line, 'Keterangan');
      $line++;
      $sql1        = "select b.productname,a.qty,c.uomcode,a.itemnote,a.location,d.ownershipname,a.expiredate,e.materialstatusname,f.description,a.buyprice,(a.qty*a.buyprice) as jumlah
							from bsdetail a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							inner join ownership d on d.ownershipid = a.ownershipid
							inner join materialstatus e on e.materialstatusid = a.materialstatusid
							inner join storagebin f on f.storagebinid = a.storagebinid
							where bsheaderid = " . $row['bsheaderid'] . " order by bsdetailid";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $line, $i += 1)
            ->setCellValueByColumnAndRow(1, $line, $row1['productname'])
            ->setCellValueByColumnAndRow(2, $line, $row1['qty'])
            ->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])
            ->setCellValueByColumnAndRow(4, $line, $row1['buyprice'])
            ->setCellValueByColumnAndRow(5, $line, $row1['jumlah'])
            ->setCellValueByColumnAndRow(6, $line, $row1['description'])
            ->setCellValueByColumnAndRow(7, $line, $row1['materialstatusname'])
            ->setCellValueByColumnAndRow(8, $line, $row1['itemnote']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note : ')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')->setCellValueByColumnAndRow(1, $line, 'Diperiksa oleh, ')->setCellValueByColumnAndRow(2, $line, 'Diketahui oleh, ')->setCellValueByColumnAndRow(3, $line, 'Disetujui oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Admin')->setCellValueByColumnAndRow(1, $line, 'Supervisor')->setCellValueByColumnAndRow(2, $line, 'Chief Accounting')->setCellValueByColumnAndRow(3, $line, 'Manager Accounting');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
