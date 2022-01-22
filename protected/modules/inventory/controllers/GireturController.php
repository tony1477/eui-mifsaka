<?php
class GireturController extends Controller
{
  public $menuname = 'giretur';
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
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into giretur (gireturdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insgiretur').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'gireturid' => $id
			));
    }
  }
  public function actionGenerategi()
  {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGIRGI(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
      }
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $gireturid   = isset($_POST['gireturid']) ? $_POST['gireturid'] : '';
    $gireturno   = isset($_POST['gireturno']) ? $_POST['gireturno'] : '';
    $gireturdate = isset($_POST['gireturdate']) ? $_POST['gireturdate'] : '';
    $giheaderid  = isset($_POST['giheaderid']) ? $_POST['giheaderid'] : '';
    $headernote  = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $companyid  = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $fullname  = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $gireturid   = isset($_GET['q']) ? $_GET['q'] : $gireturid;
    $gireturno   = isset($_GET['q']) ? $_GET['q'] : $gireturno;
    $gireturdate = isset($_GET['q']) ? $_GET['q'] : $gireturdate;
    $giheaderid  = isset($_GET['q']) ? $_GET['q'] : $giheaderid;
    $headernote  = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $companyid  = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $fullname  = isset($_GET['q']) ? $_GET['q'] : $fullname;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'gireturid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
    $rec          = array();
    $com          = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appgiretur')")->queryScalar();
		
		$rec = Yii::app()->db->createCommand()->select ('group_concat(distinct b.wfbefstat) as wfbefsat')
		->from('workflow a')
		->join('wfgroup b', 'b.workflowid = a.workflowid')
		->join('groupaccess c', 'c.groupaccessid = b.groupaccessid')
		->join('usergroup d', 'd.groupaccessid = c.groupaccessid')
		->join('useraccess e', 'e.useraccessid = d.useraccessid')
		->where(" upper(a.wfname) = upper('listgiretur')
		and e.username = '" . Yii::app()->user->name . "' ")->queryScalar();
		
		$com = Yii::app()->db->createCommand()->select ('group_concat(distinct a.menuvalueid) as menuvalueid')
		->from('groupmenuauth a')
		->join('menuauth b', 'b.menuauthid = a.menuauthid')
		->join('usergroup c', 'c.groupaccessid = a.groupaccessid')
		->join('useraccess d', 'd.useraccessid = c.useraccessid')
		->where("upper(b.menuobject) = upper('company')
		and d.username = '" . Yii::app()->user->name . "' ")->queryScalar();
		
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company c', 'c.companyid = b.companyid')->where("(gireturno like :gireturno) or
						(t.headernote like :headernote)", array(
        ':gireturno' => '%' . $gireturno . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->queryScalar();
    } else if (isset($_GET['invoice'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company c', 'c.companyid = b.companyid')->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')->where("((gireturno like :gireturno) or
						(t.headernote like :headernote))
					and t.recordstatus in (".getUserRecordStatus('listgiretur').") and c.companyid in (".getUserObjectValues('company').")", array(
        ':gireturno' => '%' . $gireturno . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->queryScalar();
    } else if (isset($_GET['notagir'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company c', 'c.companyid = b.companyid')->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')->where("
      ((t.gireturid like :gireturid) or
    (t.gireturno like :gireturno) or
    (d.fullname like :fullname) or   
    (c.companyname like :companyid))
					and t.companyid = {$_REQUEST['companyid']} and t.recordstatus in (".getUserRecordStatus('listgiretur').") and c.companyid in (".getUserObjectValues('company').")
					and t.gireturid not in (select j.gireturid from notagir j where j.recordstatus = 3)
					and t.giheaderid in (select k.giheaderid from invoice k where k.recordstatus =3)", array(
      ':gireturid' => '%' . $gireturid . '%',
      ':gireturno' => '%' . $gireturno . '%',
      ':companyid' => '%' . $companyid . '%',
      ':fullname' => '%' . $fullname . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company c', 'c.companyid = b.companyid')->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')->where("
      (coalesce(t.gireturid,'') like :gireturid) and
    (coalesce(t.gireturdate,'') like :gireturdate) and
    (coalesce(a.gino,'') like :giheaderid) and
    (coalesce(d.fullname,'') like :fullname) and   
    (coalesce(c.companyname,'') like :companyid) and   
    (coalesce(t.headernote,'') like :headernote)
					and t.recordstatus in (".getUserRecordStatus('listgiretur').") and t.recordstatus < {$maxstat} and c.companyid in (".getUserObjectValues('company').")", array(
        ':headernote' => '%' . $headernote . '%',
      ':gireturid' => '%' . $gireturid . '%',
      ':gireturdate' => '%' . $gireturdate . '%',
      ':giheaderid' => '%' . $giheaderid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':fullname' => '%' . $fullname . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.gino,d.fullname as customername,c.companyid,c.companyname')->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company c', 'c.companyid = b.companyid')->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')->where("((gireturno like :gireturno) or
				(t.headernote like :headernote))", array(
        ':gireturno' => '%' . $gireturno . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['invoice'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.gino,d.fullname as customername,c.companyid,c.companyname')->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company c', 'c.companyid = b.companyid')->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')->where("((gireturno like :gireturno) or
				(t.headernote like :headernote)) and t.gireturid not in (select gireturid from invoice)
									and t.recordstatus in (".getUserRecordStatus('listgiretur').") and c.companyid in (".getUserObjectValues('company').")", array(
        ':gireturno' => '%' . $gireturno . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['notagir'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.gino,d.fullname as customername,c.companyid,c.companyname')
			->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')
			->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')
			->where("
      ((t.gireturid like :gireturid) or
    (t.gireturno like :gireturno) or
    (d.fullname like :fullname) or   
    (c.companyname like :companyid))
					and t.companyid = {$_REQUEST['companyid']} and t.recordstatus in (".getUserRecordStatus('listgiretur').") and c.companyid in (".getUserObjectValues('company').")
					and t.gireturid not in (select j.gireturid from notagir j where j.recordstatus = 3)
					and t.giheaderid in (select k.giheaderid from invoice k where k.recordstatus =3)", array(
      ':gireturid' => '%' . $gireturid . '%',
      ':gireturno' => '%' . $gireturno . '%',
      ':companyid' => '%' . $companyid . '%',
      ':fullname' => '%' . $fullname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.gino,d.fullname as customername,c.companyid,c.companyname')
			->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')
			->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')
			->leftjoin('company c', 'c.companyid = b.companyid')
			->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')
			->where("
      (coalesce(t.gireturid,'') like :gireturid) and
    (coalesce(t.gireturdate,'') like :gireturdate) and
    (coalesce(a.gino,'') like :giheaderid) and
    (coalesce(d.fullname,'') like :fullname) and   
    (coalesce(c.companyname,'') like :companyid) and   
    (coalesce(t.headernote,'') like :headernote)
					and t.recordstatus in (".getUserRecordStatus('listgiretur').") and t.recordstatus < {$maxstat} and c.companyid in (".getUserObjectValues('company').")", array(
       ':headernote' => '%' . $headernote . '%',
      ':gireturid' => '%' . $gireturid . '%',
      ':gireturdate' => '%' . $gireturdate . '%',
      ':giheaderid' => '%' . $giheaderid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':fullname' => '%' . $fullname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'gireturid' => $data['gireturid'],
        'gireturno' => $data['gireturno'],
        'gireturdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gireturdate'])),
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'customername' => $data['customername'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgiretur' => $data['statusname']
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
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'gireturdetailid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset = ($page - 1) * $rows;
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    $footer = array();
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('gireturid = :gireturid', array(
        ':gireturid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('gireturid = :gireturid', array(
        ':gireturid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcodec,c.sloccode,d.description')->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('gireturid = :gireturid', array(
        ':gireturid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,c.description as slocdesc,d.description')->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('gireturid = :gireturid', array(
        ':gireturid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'gireturdetailid' => $data['gireturdetailid'],
        'gireturid' => $data['gireturid'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['description'],
        'itemnote' => $data['itemnote']
      );
    }
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $cmd      = Yii::app()->db->createCommand()->select('sum(t.qty) as totalqty')->from('gireturdetail t')->where('gireturid = :gireturid', array(
      ':gireturid' => $id
    ))->queryRow();
    $footer[] = array(
      'productid' => 'Total',
      'qty' => Yii::app()->format->formatNumber($cmd['totalqty'])
    );
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionGenerateso()
  {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGISO(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
      }
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
        $sql     = 'call Insertgiretur(:vgireturdate,:vgiheaderid,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updategiretur(:vid,:vgireturdate,:vgiheaderid,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['gireturid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['gireturid']);
      }
      $command->bindvalue(':vgireturdate', date(Yii::app()->params['datetodb'], strtotime($_POST['gireturdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vgiheaderid', $_POST['giheaderid'], PDO::PARAM_STR);
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
        $sql     = 'call Insertgireturdetail(:vgireturid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updategireturdetail(:vid,:vgireturid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['gireturdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['gireturdetailid']);
      }
      $command->bindvalue(':vgireturid', $_POST['gireturid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', str_replace(",", "", $_POST['qty']), PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
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
        $sql     = 'call DeleteGIRetur(:vid,:vcreatedby)';
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
        $sql     = 'call ApproveGIretur(:vid,:vcreatedby)';
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
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgegiretur(:vid,:vcreatedby)';
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
        $sql     = 'call Purgegireturdetail(:vid,:vcreatedby)';
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
    $sql = "select c.companyid, a.gireturno,a.gireturdate,b.gino ,c.shipto,a.gireturid,a.headernote,d.fullname,a.recordstatus
						from giretur a
						left join giheader b on b.giheaderid = a.giheaderid 
						left join soheader c on c.soheaderid = b.soheaderid 
						left join addressbook d on d.addressbookid=c.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.gireturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('giretur');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(15, $this->pdf->gety(), ': ' . $row['gireturno']);
      $this->pdf->text(40, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(45, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])));
      $this->pdf->text(70, $this->pdf->gety(), 'SJ');
      $this->pdf->text(75, $this->pdf->gety(), ': ' . $row['gino']);
      $this->pdf->text(100, $this->pdf->gety(), 'Customer ');
      $this->pdf->text(115, $this->pdf->gety(), ': ' . $row['fullname']);
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,d.description,
								f.description as rak
								from gireturdetail a
								inner join product b on b.productid = a.productid
								inner join unitofmeasure c on c.unitofmeasureid = a.uomid
								inner join sloc d on d.slocid = a.slocid
								left join storagebin f on f.storagebinid = a.storagebinid
								left join gidetail g on g.gidetailid = a.gidetailid
								left join sodetail h on h.sodetailid = g.sodetailid
								where gireturid = " . $row['gireturid'] . " group by b.productname order by h.sodetailid";
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
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        10,
        130,
        15,
        10,
        40
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
        'L',
        'L',
        'R',
        'L',
        'L',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['vqty']),
          $row1['uomcode'],
          $row1['description'] . ' - ' . $row1['rak']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
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
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety(), '');
      $this->pdf->text(20, $this->pdf->gety(), ' Dibuat Oleh,');
      $this->pdf->text(70, $this->pdf->gety(), '  Dibawa Oleh,');
      $this->pdf->text(120, $this->pdf->gety(), 'Diserahkan,');
      $this->pdf->text(170, $this->pdf->gety(), 'Diterima Oleh,');
      $this->pdf->text(10, $this->pdf->gety() + 15, '');
      $this->pdf->text(20, $this->pdf->gety() + 15, '.........................');
      $this->pdf->text(70, $this->pdf->gety() + 15, '............................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '.............................');
      $this->pdf->text(10, $this->pdf->gety() + 18, '');
      $this->pdf->text(20, $this->pdf->gety() + 18, '  Adm Gudang');
      $this->pdf->text(70, $this->pdf->gety() + 18, ' Ekspedisi/ Supir');
      $this->pdf->text(120, $this->pdf->gety() + 18, '    Customer');
      $this->pdf->text(170, $this->pdf->gety() + 18, ' Kepala Gudang');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'giretur';
    parent::actionDownxls();
    $sql = "select c.companyid, a.gireturno,a.gireturdate,b.gino ,c.shipto,a.gireturid,a.headernote,
						a.recordstatus
						from giretur a
						left join giheader b on b.giheaderid = a.giheaderid 
						left join soheader c on c.soheaderid = b.soheaderid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.gireturid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['gireturno']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Date')->setCellValueByColumnAndRow(1, $line, ': ' . $row['gireturdate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'SJ No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['gino']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Barang')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Gudang');
      $line++;
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,d.description,
								f.description as rak
								from gireturdetail a
								inner join product b on b.productid = a.productid
								inner join unitofmeasure c on c.unitofmeasureid = a.uomid
								inner join sloc d on d.slocid = a.slocid
								left join storagebin f on f.storagebinid = a.storagebinid
								left join gidetail g on g.gidetailid = a.gidetailid
								left join sodetail h on h.sodetailid = g.sodetailid
								where gireturid = " . $row['gireturid'] . " group by b.productname order by h.sodetailid";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['vqty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['description'] . ' - ' . $row1['rak']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note : ')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')->setCellValueByColumnAndRow(1, $line, 'Dibawa oleh, ')->setCellValueByColumnAndRow(2, $line, 'Diserahkan oleh, ')->setCellValueByColumnAndRow(3, $line, 'Diterima oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Admin Gudang')->setCellValueByColumnAndRow(1, $line, 'Ekspedisi/ Supir')->setCellValueByColumnAndRow(2, $line, 'Customer')->setCellValueByColumnAndRow(3, $line, 'Kepala Gudang');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
