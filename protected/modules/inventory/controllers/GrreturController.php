<?php
class GrreturController extends Controller
{
  public $menuname = 'grretur';
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
  public function actiongetdata()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into grretur (grreturdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insgrretur').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'grreturid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $grreturid   = isset($_POST['grreturid']) ? $_POST['grreturid'] : '';
    $grreturno   = isset($_POST['grreturno']) ? $_POST['grreturno'] : '';
    $grreturdate = isset($_POST['grreturdate']) ? $_POST['grreturdate'] : '';
    $poheaderid  = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $headernote  = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $fullname  = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $grreturid   = isset($_GET['q']) ? $_GET['q'] : $grreturid;
    $grreturno   = isset($_GET['q']) ? $_GET['q'] : $grreturno;
    $grreturdate = isset($_GET['q']) ? $_GET['q'] : $grreturdate;
    $poheaderid  = isset($_GET['q']) ? $_GET['q'] : $poheaderid;
    $headernote  = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $fullname  = isset($_GET['q']) ? $_GET['q'] : $fullname;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'grreturid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appgrretur')")->queryScalar();
		$maxstatnotagrretur = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appnotagrretur')")->queryScalar();
		
    if (isset($_GET['notagrretur'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')->where("
      ((coalesce(grreturno,'') like :grreturno) or
					(coalesce(b.fullname,'') like :fullname)) and t.recordstatus = {$maxstat} and t.recordstatus in (".getUserRecordStatus('listgrretur').") and a.companyid = {$_GET['companyid']}
					and t.grreturid not in (select a1.grreturid from notagrretur a1 where a1.companyid = {$_GET['companyid']} and a1.recordstatus = {$maxstatnotagrretur})", array(
        ':grreturno' => '%' . $grreturno . '%',
      ':fullname' => '%' . $fullname . '%',
      ))->queryScalar();
    } else if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')->where("
      (coalesce(grreturdate,'') like :grreturdate) and
					(coalesce(grreturno,'') like :grreturno) and
					(coalesce(t.headernote,'') like :headernote) and
					(coalesce(a.pono,'') like :poheaderid) and 
					(coalesce(b.fullname,'') like :fullname) and
					(coalesce(t.grreturid,'') like :grreturid) and t.recordstatus < {$maxstat} and t.recordstatus in (".getUserRecordStatus('listgrretur').") and a.companyid in (".getUserObjectValues('company').")", array(
        ':grreturdate' => '%' . $grreturdate . '%',
        ':grreturno' => '%' . $grreturno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%',
      ':fullname' => '%' . $fullname . '%',
      ':grreturid' => '%' . $grreturid . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->where('(grreturdate like :grreturdate) or
                                        (grreturno like :grreturno) or
                                        (t.headernote like :headernote) or
                                        (a.pono like :poheaderid)', array(
        ':grreturdate' => '%' . $grreturdate . '%',
        ':grreturno' => '%' . $grreturno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%',
      ':fullname' => '%' . $fullname . '%',
      ':grreturid' => '%' . $grreturid . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['notagrretur'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.fullname')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')->where("
      ((coalesce(grreturno,'') like :grreturno) or
					(coalesce(b.fullname,'') like :fullname)) and t.recordstatus = {$maxstat} and t.recordstatus in (".getUserRecordStatus('listgrretur').") and a.companyid = {$_GET['companyid']}
					and t.grreturid not in (select a1.grreturid from notagrretur a1 where a1.companyid = {$_GET['companyid']} and a1.recordstatus = {$maxstatnotagrretur})", array(
        ':grreturno' => '%' . $grreturno . '%',
      ':fullname' => '%' . $fullname . '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.fullname')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')->where("
      (coalesce(grreturdate,'') like :grreturdate) and
					(coalesce(grreturno,'') like :grreturno) and
					(coalesce(t.headernote,'') like :headernote) and
					(coalesce(a.pono,'') like :poheaderid) and 
					(coalesce(b.fullname,'') like :fullname) and
					(coalesce(t.grreturid,'') like :grreturid) and t.recordstatus < {$maxstat} and 
                                        t.recordstatus in (".getUserRecordStatus('listgrretur').") and a.companyid in (".getUserObjectValues('company').")", array(
        ':grreturdate' => '%' . $grreturdate . '%',
        ':grreturno' => '%' . $grreturno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%',
      ':fullname' => '%' . $fullname . '%',
      ':grreturid' => '%' . $grreturid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.fullname')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')->where('(grreturdate like :grreturdate) or
						(grreturno like :grreturno) or
						(t.headernote like :headernote) or
						(a.pono like :poheaderid)', array(
        ':grreturdate' => '%' . $grreturdate . '%',
        ':grreturno' => '%' . $grreturno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%',
      ':fullname' => '%' . $fullname . '%',
      ':grreturid' => '%' . $grreturid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'grreturid' => $data['grreturid'],
        'grreturno' => $data['grreturno'],
        'grreturdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['grreturdate'])),
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgrretur' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grreturdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('grreturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('podetail d', 'd.podetailid = t.podetailid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->where('grreturid = :grreturid', array(
      ':grreturid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.unitofmeasureid,b.uomcode,c.sloccode,
		c.description as slocdesc,e.description')->from('grreturdetail t')
		->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
		->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('podetail d', 'd.podetailid = t.podetailid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->where('grreturid = :grreturid', array(
      ':grreturid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'grreturdetailid' => $data['grreturdetailid'],
        'grreturid' => $data['grreturid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['description'],
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
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
        $sql     = 'call Insertgrretur(:vgrreturdate,:vpoheaderid,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updategrretur(:vid,:vgrreturdate,:vpoheaderid,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['grreturid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['grreturid']);
      }
      $command->bindvalue(':vgrreturdate', date(Yii::app()->params['datetodb'], strtotime($_POST['grreturdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vpoheaderid', $_POST['poheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(true, 'insertsuccess', 1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 0);
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
        $sql     = 'call Insertgrreturdetail(:vgrreturid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updategrreturdetail(:vid,:vgrreturid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['grreturdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['grreturdetailid']);
      }
      $command->bindvalue(':vgrreturid', $_POST['grreturid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':vitemnote', $_POST['itemnote'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(true, 'insertsuccess', 1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 0);
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
        $sql     = 'call Purgegrretur(:vid,:vcreatedby)';
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
        GetMessage(false, $e->getMessage(), 0);
      }
    } else {
      GetMessage(false, 'chooseone', 0);
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
        $sql     = 'call Purgegrreturdetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 0);
      }
    } else {
      GetMessage(false, 'chooseone', 0);
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
        $sql     = 'call DeleteGRRetur(:vid,:vcreatedby)';
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
        GetMessage(false, $e->getMessage(), 0);
      }
    } else {
      GetMessage(false, 'chooseone', 0);
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
        $sql     = 'call ApproveGRRetur(:vid,:vcreatedby)';
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
        GetMessage(false, $e->getMessage(), 0);
      }
    } else {
      GetMessage(false, 'chooseone', 0);
    }
  }
  public function actionGeneratedetail()
  {
    if (isset($_POST['id'])) {
      $sql = "select headernote from poheader where poheaderid = ".$_POST['id'];
			$header = Yii::app()->db->createCommand($sql)->queryScalar();
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGRRPO(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success',
            'headernote' => $header,
            'div' => "Data generated"
          ));
        }
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(false, $e->getMessage(), 0);
      }
    }
    Yii::app()->end();
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select a.grreturid,b.companyid,a.grreturno,a.grreturdate,a.poheaderid,b.pono,c.fullname,a.recordstatus,a.headernote
						from grretur a
						left join poheader b on b.poheaderid = a.poheaderid
						left join addressbook c on c.addressbookid = b.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.grreturid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('grretur');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->SetFont('Arial', '', 10);
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(7);
      $this->pdf->text(10, $this->pdf->gety(), 'No');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['grreturno']);
      $this->pdf->text(50, $this->pdf->gety(), 'Tgl');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . $row['grreturdate']);
      $this->pdf->text(90, $this->pdf->gety(), 'PO ');
      $this->pdf->text(100, $this->pdf->gety(), ': ' . $row['pono']);
      $this->pdf->text(130, $this->pdf->gety(), 'Supplier');
      $this->pdf->text(140, $this->pdf->gety(), ': ' . $row['fullname']);
      $sql1        = "select b.productname, a.qty, c.uomcode,d.sloccode
								from grreturdetail a
								left join product b on b.productid = a.productid
								left join unitofmeasure c on c.unitofmeasureid = a.uomid
								left join sloc d on d.slocid = a.slocid
								where grreturid = " . $row['grreturid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        120,
        20,
        20,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Gudang'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'R',
        'R',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['sloccode']
        ));
      }
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->row(array(
        'Note:',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(65, $this->pdf->gety(), ' Disetujui oleh,');
      $this->pdf->text(125, $this->pdf->gety(), 'Dibawa oleh,');
      $this->pdf->text(178, $this->pdf->gety(), ' Diterima oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(65, $this->pdf->gety() + 15, '.........................');
      $this->pdf->text(125, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(178, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(15, $this->pdf->gety() + 17, 'Admin Gudang');
      $this->pdf->text(65, $this->pdf->gety() + 17, ' Kepala Gudang');
      $this->pdf->text(125, $this->pdf->gety() + 17, '        Supir');
      $this->pdf->text(178, $this->pdf->gety() + 17, 'Supplier/Toko');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'grretur';
    parent::actionDownxls();
    $sql = "select a.grreturid,b.companyid,a.grreturno,a.grreturdate,a.poheaderid,b.pono,c.fullname,a.recordstatus,a.headernote
						from grretur a
						left join poheader b on b.poheaderid = a.poheaderid
						left join addressbook c on c.addressbookid = b.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.grreturid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['grreturno']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Date')->setCellValueByColumnAndRow(1, $line, ': ' . $row['grreturdate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'PO No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['pono']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Vendor')->setCellValueByColumnAndRow(1, $line, ': ' . $row['fullname']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Barang')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Gudang');
      $line++;
      $sql1        = "select b.productname, a.qty, c.uomcode,d.description
								from grreturdetail a
								left join product b on b.productid = a.productid
								left join unitofmeasure c on c.unitofmeasureid = a.uomid
								left join sloc d on d.slocid = a.slocid
								where grreturid = " . $row['grreturid'];
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['qty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['description']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note : ')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')->setCellValueByColumnAndRow(1, $line, 'Disetujui oleh, ')->setCellValueByColumnAndRow(2, $line, 'Dibawa oleh, ')->setCellValueByColumnAndRow(3, $line, 'Diterima oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Admin Gudang')->setCellValueByColumnAndRow(1, $line, 'Kepala Gudang')->setCellValueByColumnAndRow(2, $line, 'Supir')->setCellValueByColumnAndRow(3, $line, 'Customer/Toko');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
