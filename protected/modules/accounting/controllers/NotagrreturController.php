<?php
class NotagrreturController extends Controller
{
  public $menuname = 'notagrretur';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexproduct()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchproduct();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexakun()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchakun();
    else
      $this->renderPartial('index', array());
  }
  public function actiongetdata()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into notagrretur (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insnotagrretur').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'notagrreturid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $notagrreturid   = isset($_POST['notagrreturid']) ? $_POST['notagrreturid'] : '';
    $notagrreturno   = isset($_POST['notagrreturno']) ? $_POST['notagrreturno'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $grreturid       = isset($_POST['grreturid']) ? $_POST['grreturid'] : '';
    $poheaderid      = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $addressbook     = isset($_POST['addressbook']) ? $_POST['addressbook'] : '';
    $company     = isset($_POST['company']) ? $_POST['company'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagrreturid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appnotagrretur')")->queryScalar();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagrretur t')
		->leftjoin('grretur a', 'a.grreturid = t.grreturid')
		->leftjoin('poheader b', 'b.poheaderid = a.poheaderid')
		->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')
		->leftjoin('company c', 'c.companyid = t.companyid')
		->where("(coalesce(t.docdate,'') like :docdate) and
			(coalesce(t.notagrreturno,'') like :notagrreturno) and
			(coalesce(t.notagrreturid,'') like :notagrreturid) and
			(coalesce(b.pono,'') like :poheaderid) and
			(coalesce(d.fullname,'') like :addressbookid) and
			(coalesce(c.companyname,'') like :company) and
			(coalesce(a.grreturno,'') like :grreturid) and t.recordstatus in (".getUserRecordStatus('listnotagrretur').") and t.recordstatus < {$maxstat} and
            t.companyid in (".getUserObjectWfValues('company','listnotagrretur').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':notagrreturno' => '%' . $notagrreturno . '%',
      ':notagrreturid' => '%' . $notagrreturid . '%',
      ':poheaderid' => '%' . $poheaderid  . '%',
      ':addressbookid' => '%' . $addressbook  . '%',
      ':company' => '%' . $company  . '%',
      ':grreturid' => '%' . $grreturid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.grreturno,b.poheaderid,b.pono,c.companyname,d.fullname,d.addressbookid')->from('notagrretur t')
		->leftjoin('grretur a', 'a.grreturid = t.grreturid')
		->leftjoin('poheader b', 'b.poheaderid = a.poheaderid')
		->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')
		->leftjoin('company c', 'c.companyid = t.companyid')
		->where("(coalesce(t.docdate,'') like :docdate) and
			(coalesce(t.notagrreturno,'') like :notagrreturno) and
			(coalesce(t.notagrreturid,'') like :notagrreturid) and
			(coalesce(b.pono,'') like :poheaderid) and
			(coalesce(d.fullname,'') like :addressbookid) and
			(coalesce(c.companyname,'') like :company) and
			(coalesce(a.grreturno,'') like :grreturid) and t.recordstatus in (".getUserRecordStatus('listnotagrretur').") and t.recordstatus < {$maxstat} and
            t.companyid in (".getUserObjectWfValues('company','listnotagrretur').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':notagrreturno' => '%' . $notagrreturno . '%',
      ':notagrreturid' => '%' . $notagrreturid . '%',
      ':poheaderid' => '%' . $poheaderid  . '%',
      ':addressbookid' => '%' . $addressbook  . '%',
      ':company' => '%' . $company  . '%',
      ':grreturid' => '%' . $grreturid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrreturid' => $data['notagrreturid'],
        'notagrreturno' => $data['notagrreturno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'grreturid' => $data['grreturid'],
        'grreturno' => $data['grreturno'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
				'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusnotagrretur' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchproduct()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagrrpro t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('grreturdetail b', 'b.grreturdetailid=t.grreturdetailid')->leftjoin('grdetail c', 'c.grdetailid=b.grdetailid')->leftjoin('podetail d', 'd.podetailid=c.podetailid')->leftjoin('unitofmeasure e', 'e.unitofmeasureid=b.uomid')->leftjoin('currency f', 'f.currencyid=t.currencyid')->leftjoin('sloc g', 'g.slocid=t.slocid')->where('(notagrreturid = :notagrreturid)', array(
      ':notagrreturid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,e.uomcode,(t.qty*t.price) as total,f.currencyname,g.sloccode')->from('notagrrpro t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('grreturdetail b', 'b.grreturdetailid=t.grreturdetailid')->leftjoin('grdetail c', 'c.grdetailid=b.grdetailid')->leftjoin('podetail d', 'd.podetailid=c.podetailid')->leftjoin('unitofmeasure e', 'e.unitofmeasureid=b.uomid')->leftjoin('currency f', 'f.currencyid=t.currencyid')->leftjoin('sloc g', 'g.slocid=t.slocid')->where('(notagrreturid = :notagrreturid)', array(
      ':notagrreturid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrrproid' => $data['notagrrproid'],
        'notagrreturid' => $data['notagrreturid'],
        'grreturdetailid' => $data['grreturdetailid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatCurrency($data['currencyrate']),
        'total' => Yii::app()->format->formatCurrency($data['total'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(qty*price) as jumlah from notagrrpro where notagrreturid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryScalar();
		$footer[] = array(
      'productname' => 'Total',
      'total' => Yii::app()->format->formatCurrency($cmd)
    );
		$sql = "select b.uomcode, sum(t.qty) as jumlah 
		from notagrrpro t 
		left join grreturdetail a on a.grreturdetailid = t.grreturdetailid 
		left join unitofmeasure b on b.unitofmeasureid = a.uomid 
		where notagrreturid = ".$id." group by b.uomcode";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
		$footer[] = array(
      'productname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($data['jumlah']),
      'uomcode' => $data['uomcode'],
    );
		}
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchakun()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagrracc t')->leftjoin('account a', 'a.accountid=t.accountid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->where('(notagrreturid = :notagrreturid)', array(
      ':notagrreturid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountname,b.currencyname')->from('notagrracc t')->leftjoin('account a', 'a.accountid=t.accountid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->where('(notagrreturid = :notagrreturid)', array(
      ':notagrreturid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrraccid' => $data['notagrraccid'],
        'notagrreturid' => $data['notagrreturid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debet' => Yii::app()->format->formatNumber($data['debet']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'itemnote' => $data['itemnote']
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
        $sql     = 'call Insertnotagrretur(:vcompanyid,:vdocdate,:vgrreturid,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatenotagrretur(:vid,:vcompanyid,:vdocdate,:vgrreturid,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['notagrreturid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['notagrreturid']);
      }
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vgrreturid', $_POST['grreturid'], PDO::PARAM_STR);
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
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
  public function actionSaveproduct()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertnotagrrpro(:vnotagrreturid,:vprice,:vcurrencyid,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatenotagrrpro(:vid,:vnotagrreturid,:vprice,:vcurrencyid,:vcurrencyrate,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['notagrrproid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['notagrrproid']);
      }
      $command->bindvalue(':vnotagrreturid', $_POST['notagrreturid'], PDO::PARAM_STR);
      $command->bindvalue(':vprice', $_POST['price'], PDO::PARAM_STR);
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
  public function actionSaveakun()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertnotagrracc(:vnotagrreturid,:vaccountid,:vdebet,:vcredit,:vcurrencyid,:vcurrencyrate,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatenotagrracc(:vid,:vnotagrreturid,:vaccountid,:vdebet,:vcredit,:vcurrencyid,:vcurrencyrate,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['notagrraccid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['notagrraccid']);
      }
      $command->bindvalue(':vnotagrreturid', $_POST['notagrreturid'], PDO::PARAM_STR);
      $command->bindvalue(':vaccountid', $_POST['accountid'], PDO::PARAM_STR);
      $command->bindvalue(':vdebet', $_POST['debet'], PDO::PARAM_STR);
      $command->bindvalue(':vcredit', $_POST['credit'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
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
  public function actionGeneratedetail()
  {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateNGRRPO(:vid, :vhid)';
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
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgenotagrretur(:vid,:vcreatedby)';
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
  public function actionPurgeproduct()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgenotagrrpro(:vid,:vcreatedby)';
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
  public function actionPurgeakun()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgenotagrracc(:vid,:vcreatedby)';
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
        $sql     = 'call ApproveNotagrretur(:vid,:vcreatedby)';
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
        $sql     = 'call DeleteNotagrretur(:vid,:vcreatedby)';
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
  public function actionDownPDF1()
  {
    parent::actionDownload();
    $sql = "select notagrreturid,a.companyid,a.notagrreturno,a.docdate,a.headernote,c.grreturno,d.pono,e.fullname
                        from notagrretur a 
						left join grretur c on c.grreturid = a.grreturid
                        left join poheader d on d.poheaderid = c.poheaderid
                        left join addressbook e on e.addressbookid = d.addressbookid			
                        ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.notagrreturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('notagrretur');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['notagrreturno']);
      $this->pdf->text(50, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(90, $this->pdf->gety(), 'No. Reff. ');
      $this->pdf->text(100, $this->pdf->gety(), ': ' . $row['grreturno'] . ' / ' . $row['pono']);
      $this->pdf->text(150, $this->pdf->gety(), 'Supplier ');
      $this->pdf->text(160, $this->pdf->gety(), ': ' . $row['fullname']);
      $sql1        = "select b.productname, a.qty, c.uomcode, concat(e.sloccode,' - ',e.description) as sloccode,a.price,a.qty*a.price as jumlah
        from notagrrpro a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        left join sloc e on e.slocid = a.slocid
        where notagrreturid = " . $row['notagrreturid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totaljumlah = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 3);
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
        90,
        20,
        15,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Harga',
        'Jumlah'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['price']),
          Yii::app()->format->formatCurrency($row1['jumlah'])
        ));
        $totaljumlah += $row1['jumlah'];
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Total',
        Yii::app()->format->formatCurrency($totaljumlah)
      ));
      $sql2        = "select b.accountname,a.debet,a.credit,c.currencyname,a.itemnote
                from notagrracc a
                left join account b on b.accountid = a.accountid
                left join currency c on c.currencyid = a.currencyid
                where notagrreturid = " . $row['notagrreturid'];
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      $totaldebet  = 0;
      $totalcredit = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 3);
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
        60,
        25,
        25,
        25,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun',
        'Debet',
        'Credit',
        'Mata Uang',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader2 as $row2) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row2['accountname'],
          Yii::app()->format->formatNumber($row2['debet']),
          Yii::app()->format->formatNumber($row2['credit']),
          $row2['currencyname'],
          $row2['itemnote']
        ));
        $totaldebet += $row1['debet'];
        $totalcredit += $row1['credit'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatCurrency($totaldebet),
        Yii::app()->format->formatCurrency($totalcredit),
        '',
        ''
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
      $this->pdf->row(array(
        'Note:',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 18, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 18, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 20, '    Admin AP');
      $this->pdf->text(55, $this->pdf->gety() + 20, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 20, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select notagrreturid,a.companyid,a.notagrreturno,a.docdate,a.headernote,c.grreturno,d.pono,e.fullname
                        from notagrretur a 
						left join grretur c on c.grreturid = a.grreturid
                        left join poheader d on d.poheaderid = c.poheaderid
                        left join addressbook e on e.addressbookid = d.addressbookid			
                        ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.notagrreturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('notagrretur');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['notagrreturno']);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'No. Reff. ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . $row['grreturno'] . ' / ' . $row['pono']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(120, $this->pdf->gety() + 6, 'Supplier ');
      $this->pdf->text(140, $this->pdf->gety() + 6, ': ' . $row['fullname']);
      $sql1        = "select b.productname, a.qty, c.uomcode, concat(e.sloccode,' - ',e.description) as sloccode,a.price,a.qty*a.price as jumlah
        from notagrrpro a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        left join sloc e on e.slocid = a.slocid
        where notagrreturid = " . $row['notagrreturid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      
      $sql_tax = "select taxvalue, taxcode
      from notagrrpro t 
      join grreturdetail b on b.grreturdetailid = t.grreturdetailid 
      join grdetail c on c.grdetailid = b.grdetailid 
      join podetail d on d.podetailid = c.podetailid
      join poheader p on p.poheaderid = d.poheaderid
      join tax tx on tx.taxid = p.taxid
      where notagrreturid = ".$_GET['id'];
      $taxvalue = Yii::app()->db->createCommand($sql_tax)->queryRow();
        
        
      $totaljumlah = 0;
      $i           = 0;
      $ppn = 0;
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
        90,
        20,
        15,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Harga',
        'Jumlah'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        if($taxvalue['taxvalue']!=0)
        {
            $price = 100/(100+$taxvalue['taxvalue'])*$row1['price'];
            $jumlah = $row1['qty']*$price;
        }
        else
        {
            $price = $row1['price'];
            $jumlah = $row1['qty']*$price;
        }
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($price),
          Yii::app()->format->formatCurrency($jumlah)
        ));
        $totaljumlah += $jumlah;
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Sub Total',
        Yii::app()->format->formatCurrency($totaljumlah)
      ));
        $ppn = ($taxvalue['taxvalue']*$totaljumlah)/100;
    $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'PPN '.$taxvalue['taxvalue'].'%',
        Yii::app()->format->formatCurrency($ppn)
      ));
    $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Grand Total',
        Yii::app()->format->formatCurrency($ppn+$totaljumlah)
      ));
        
      $sql2        = "select b.accountname,a.debet,a.credit,c.currencyname,a.itemnote
                from notagrracc a
                left join account b on b.accountid = a.accountid
                left join currency c on c.currencyid = a.currencyid
                where notagrreturid = " . $row['notagrreturid'];
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      $totaldebet  = 0;
      $totalcredit = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 12);
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
        60,
        25,
        25,
        25,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun',
        'Debet',
        'Credit',
        'Mata Uang',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader2 as $row2) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row2['accountname'],
          Yii::app()->format->formatNumber($row2['debet']),
          Yii::app()->format->formatNumber($row2['credit']),
          $row2['currencyname'],
          $row2['itemnote']
        ));
        $totaldebet += $row1['debet'];
        $totalcredit += $row1['credit'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatCurrency($totaldebet),
        Yii::app()->format->formatCurrency($totalcredit),
        '',
        ''
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
      $this->pdf->row(array(
        'Note:',
        $row['headernote']
      ));
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
    $sql = "select docdate,grreturid,recordstatus
				from notagrretur a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.notagrreturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('grreturid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['grreturid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="notagrretur.xlsx"');
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