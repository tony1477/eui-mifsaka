<?php
class ProductplasticoutputController extends Controller
{
  public $menuname = 'productplasticoutput';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexts()
  {
    if (isset($_GET['grid']))
      echo $this->searchts();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexda()
  {
    if (isset($_GET['grid']))
      echo $this->searchda();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexfg()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchdetailfg();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into productoutput (productoutputdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insop').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'productoutputid' => $id
			));
    }
  }
  public function actionGenerateplan()
  {
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateOPPP(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(false, $e->getMessage(), 1);
      }
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
		$productoutputid	= isset($_POST['productoutputid']) ? $_POST['productoutputid'] : '';
    $productoutputno	= isset($_POST['productoutputno']) ? $_POST['productoutputno'] : '';
    $productplanno		= isset($_POST['productplanno']) ? $_POST['productplanno'] : '';
    $companyid		= isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $description		= isset($_POST['description']) ? $_POST['description'] : '';
    $sono		= isset($_POST['sono']) ? $_POST['sono'] : '';
    $customer		= isset($_POST['customer']) ? $_POST['customer'] : '';
		$page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 't.productoutputid';
		$order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appop')")->queryScalar();
		$connection		= Yii::app()->db;
		$from = "
			from productoutput t 
			left join productplan a on a.productplanid = t.productplanid 
			left join soheader b on b.soheaderid = a.soheaderid 
			left join addressbook c on c.addressbookid = b.addressbookid ";
		$where = "
 			where ((t.productoutputid like '%".$productoutputid."%') 
				and (coalesce(t.productoutputno,'') like '%".$productoutputno."%') 
				and (coalesce(t.productplanno,'') like '%".$productplanno."%')
				and (coalesce(b.sono,'') like '%".$sono."%')
				and (coalesce(c.fullname,'') like '%".$customer."%')
				and (coalesce(t.description,'') like '%".$description."%')
				and (coalesce(t.companyname,'') like '%".$companyid."%')
				)
				and t.recordstatus < {$maxstat}
				and t.recordstatus in (".getUserRecordStatus('listop').") 
				and t.companyid in (".getUserObjectValues('company').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = ' 
			select t.productoutputid,t.productplanid,t.productplanno,t.productoutputno,t.productoutputdate,t.description,t.companyname,
			t.statusname,t.recordstatus,t.companyid,b.sono,b.soheaderid,c.fullname,c.addressbookid '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productoutputid' => $data['productoutputid'],
        'productplanid' => $data['productplanid'],
        'productplanno' => $data['productplanno'],
        'productoutputno' => $data['productoutputno'],
        'productoutputdate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['productoutputdate'])),
        'description' => $data['description'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
				'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
				'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusproductoutput' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchts()
  {
      header("Content-Type: application/json");
      $productoutputid = isset($_POST['productoutputid']) ? $_POST['productoutputid'] : '';
      $productoutputno = isset($_POST['productoutputno']) ? $_POST['productoutputno'] : '';
      $productplanno   = isset($_POST['productplanno']) ? $_POST['productplanno'] : '';
      $page            = isset($_GET['page']) ? intval($_GET['page']) : 1;
      $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
      $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : 'productoutputid';
      $order           = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
      $offset          = ($page - 1) * $rows;
      $result          = array();
      $row             = array();
      $connection		= Yii::app()->db;
      
      $sql = "SELECT a.productoutputno, a.productoutputid, a.description, a.productplanno, b.productplanid, productoutputdate
              FROM productoutput a
              JOIN productplan b ON a.productplanid = b.productplanid
              WHERE a.recordstatus = 3
              AND a.productoutputno LIKE '%".(isset($_GET['q']) ? $_GET['q'] : '')."%'
              AND a.companyid in (".getUserObjectValues('company').")
              ORDER BY productoutputid DESC
              LIMIT ".$offset.",".$rows."";
        $cmd = $connection->createCommand($sql)->queryAll();
      
        $sqlcount = "SELECT COUNT(1)
                     FROM productoutput a
                     JOIN productplan b ON a.productplanid = b.productplanid
                     WHERE a.recordstatus = 3
                     AND a.productoutputno LIKE '%".(isset($_GET['q']) ? $_GET['q'] : '')."%'
                     AND a.companyid in (".getUserObjectValues('company').")
                    ORDER BY productoutputid DESC";
      
        $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
      
        foreach ($cmd as $data) {
        $row[] = array(
        'productoutputid' => $data['productoutputid'],
        'productplanid' => $data['productplanid'],
        'productplanno' => $data['productplanno'],
        'productoutputno' => $data['productoutputno'],
        'description' => $data['description'],
        'productoutputdate' => $data['productoutputdate']);
        }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchda()
  {
    header("Content-Type: application/json");
		$productoutputid	= isset($_GET['q']) ? $_GET['q'] : '';
    $productoutputno	= isset($_GET['q']) ? $_GET['q'] : '';
    $productplanno		= isset($_GET['q']) ? $_GET['q'] : '';
		$page         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows         = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort         = isset($_GET['sort']) ? strval($_GET['sort']) : 't.productoutputid';
		$order        = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$connection		= Yii::app()->db;
		$from = "
			from productoutput t ";
		$where = "
 			where ((productoutputid like '%".$productoutputid."%') or (coalesce(productoutputno,'') like '%".$productoutputno."%') 
				or (coalesce(productplanno,'') like '%".$productplanno."%'))
				and t.recordstatus = 3
				and t.recordstatus in (".getUserRecordStatus('listop').") and t.companyid in (".getUserObjectValues('company').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = ' 
			select t.productoutputid,t.productplanid,t.productplanno,t.productoutputno,t.productoutputdate,t.description,t.companyname,
			t.statusname,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productoutputid' => $data['productoutputid'],
        'productplanid' => $data['productplanid'],
        'productplanno' => $data['productplanno'],
        'productoutputno' => $data['productoutputno'],
        'productoutputdate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['productoutputdate'])),
        'description' => $data['description'],
        'companyname' => $data['companyname'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusproductoutput' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdetailfg()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productoutputfg t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('t.productoutputid = :productoutputid', array(
      ':productoutputid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,c.description as slocdesc,d.description as rak,
		getstock(t.productid,t.uomid,t.slocid) as stock,
		getminstockmrp(t.productid,t.uomid,t.slocid) as minstock')->from('productoutputfg t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('t.productoutputid = :productoutputid', array(
      ':productoutputid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      if ($data['minstock'] > $data['stock']) {
				$wstock = 1;
			} else {
				$wstock = 0;
			}
      $row[] = array(
        'productoutputfgid' => $data['productoutputfgid'],
        'productoutputid' => $data['productoutputid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qtyoutput' => Yii::app()->format->formatNumber($data['qtyoutput']),
        'stock' => Yii::app()->format->formatNumber($data['stock']),
        'wstock' => $wstock,
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'outputdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['outputdate'])),
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'rak' => $data['rak'],
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionSearchdetail()
  {
    header("Content-Type: application/json");
    $id                = 0;
    $productoutputfgid = '';
    if (isset($_POST['productoutputfgid'])) {
      $productoutputfgid = $_POST['productoutputfgid'];
    }
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } else if (isset($_POST['id'])) {
      $id = $_POST['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productoutputdetail t')->leftjoin('productoutputfg c', 'c.productoutputfgid = t.productoutputfgid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('c.productoutputid = :productoutputid and c.productoutputfgid like :productoutputfgid', array(
      ':productoutputid' => $id,
      ':productoutputfgid' => $productoutputfgid     
		))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->selectdistinct('t.*,a.productname,b.uomcode,
					(select sloccode from sloc zz where zz.slocid = t.fromslocid) as fromsloccode,
			(select description from sloc zz where zz.slocid = t.fromslocid) as fromslocdesc,
			(select sloccode from sloc zz where zz.slocid = t.toslocid) as tosloccode,
			(select description from sloc zz where zz.slocid = t.toslocid) as toslocdesc,
			d.description as rak,
			getstock(t.productid,t.uomid,t.fromslocid) as fromslocstock,
			getstock(t.productid,t.uomid,t.toslocid) as toslocstock,
			getminstockmrp(t.productid,t.uomid,t.fromslocid) as minfromstock,
			getminstockmrp(t.productid,t.uomid,t.toslocid) as mintostock')->from('productoutputdetail t')->leftjoin('productoutputfg c', 'c.productoutputfgid = t.productoutputfgid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('c.productoutputid = :productoutputid and c.productoutputfgid like :productoutputfgid', array(
      ':productoutputid' => $id,
      ':productoutputfgid' => $productoutputfgid
    ))->queryAll();
    foreach ($cmd as $data) {
			if ($data['minfromstock'] > $data['fromslocstock']) {
				$wfromstock = 1;
			} else {
				$wfromstock = 0;
			}
			if ($data['mintostock'] > $data['toslocstock']) {
				$wtostock = 1;
			} else {
				$wtostock = 0;
			}
      $row[] = array(
        'productoutputdetailid' => $data['productoutputdetailid'],
        'productoutputfgid' => $data['productoutputfgid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'fromslocid' => $data['fromslocid'],
        'fromsloccode' => $data['fromsloccode'].' - '.$data['fromslocdesc'],
        'fromslocstock' => Yii::app()->format->formatNumber($data['fromslocstock']),
        'wfromstock' => $wfromstock,
        'wtostock' => $wtostock,
        'toslocid' => $data['toslocid'],
        'tosloccode' => $data['tosloccode'].' - '.$data['toslocdesc'],
        'toslocstock' => Yii::app()->format->formatNumber($data['toslocstock']),
        'storagebinid' => $data['storagebinid'],
        'rak' => $data['rak'],
        'description' => $data['description']
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
        $sql     = 'call Insertproductoutput(:vproductoutputdate,:vproductplanid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductoutput(:vid,:vproductoutputdate,:vproductplanid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productoutputid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productoutputid']);
      }
      $command->bindvalue(':vproductplanid', $_POST['productplanid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductoutputdate', date(Yii::app()->params['datetodb'], strtotime($_POST['productoutputdate'])), PDO::PARAM_STR);
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
  public function actionSavedetailfg()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertproductoutputfg(:vproductoutputid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:voutputdate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductoutputfg(:vid,:vproductoutputid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:voutputdate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productoutputfgid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productoutputfgid']);
      }
      $command->bindvalue(':vproductoutputid', $_POST['productoutputid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', str_replace(",", "", $_POST['qtyoutput']), PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':voutputdate', date(Yii::app()->params['datetodb'], strtotime($_POST['outputdate'])), PDO::PARAM_STR);
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
  public function actionSavedetail()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertproductoutputdetail(:vproductoutputid,:vproductid,:vuomid,:vreqdate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductoutputplasticdetail(:vid,:vqty,:vstoragebintoid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productoutputdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productoutputdetailid']);
      }
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebintoid', $_POST['storagebinid'], PDO::PARAM_STR);
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectProductoutput(:vid,:vcreatedby)';
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
      $id         = $_POST['id'];
      $connection = Yii::app()->db;
      $sql        = 'call ApproveOPPlastic(:vid,:vcreatedby)';
      $command    = $connection->createCommand($sql);
      foreach ($id as $ids) {
        $transaction = $connection->beginTransaction();
        try {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
          $transaction->commit();
          GetMessage(false, 'insertsuccess');
        }
        catch (Exception $e) {
          $transaction->rollback();
          GetMessage(true, $e->getMessage());
        }
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
        $sql     = 'call Purgeproductoutput(:vid,:vcreatedby)';
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
  public function actionPurgedetailfg()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductoutputfg(:vid,:vcreatedby)';
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
    $sql = "select a.*,b.productplanno,b.productplandate
      from productoutput a 
	join productplan b on b.productplanid = a.productplanid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productoutputid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('productoutput');
    $this->pdf->AddPage('P');
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Output ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['productoutputno']);
      $this->pdf->text(120, $this->pdf->gety() + 5, 'No Plan ');
      $this->pdf->text(140, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Output ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])));
      $this->pdf->text(120, $this->pdf->gety() + 10, 'Tgl Plan ');
      $this->pdf->text(140, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $sql1        = "select b.productname, a.qtyoutput as qty, c.uomcode, a.description,
			concat(d.sloccode,'-',d.description) as sloccode, 
			e.description as rak
        from productoutputfg a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.uomid
				inner join sloc d on d.slocid = a.slocid
				inner join storagebin e on e.storagebinid = a.storagebinid
        where productoutputid = " . $row['productoutputid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 25);
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
        80,
        30,
        20,
        35,
        15
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang',
        'Rak'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
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
          $row1['sloccode'],
          $row1['rak']
        ));
      }
      $this->pdf->checkPagebreak(40);
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 25, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 25, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 45, '____________ ');
      $this->pdf->text(150, $this->pdf->gety() + 45, '____________');
    }
    $this->pdf->Output();
  }
  public function actionDownPDFminus()
  {
    parent::actionDownload();
    $sql = "select b.productoutputno, b.productoutputdate, b.productplanno
	from productoutput b ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where b.productoutputid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "Stok Tidak Mencukupi";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['productoutputno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO SPP ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $i           = 0;
      $totalqty    = 0;
      $totaljumlah = 0;
      
      $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          100,
          25,
          25,
          25,
          25
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Qty Pemakaian',
          'Qty Stock',
          'Qty Detail',
          'Selisih'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'R',
          'L',
          'R',
          'R',
          'R',
          'R'
        );
      
      $sql1= "select *,qty-qtystock as selisih
   from
	(
	select j.productname,sum(a.qty) as qty,
	ifnull((
	select z.qty
	from productstock z
	where z.productid = a.productid and
	z.unitofmeasureid = a.uomid and
	z.slocid = a.toslocid and
	z.storagebinid = a.storagebinid
	),0) as qtystock,
	ifnull((
	select z.qty
	from productdetail z
	where z.productid = a.productid and
	z.unitofmeasureid = a.uomid and
	z.slocid = a.toslocid and
	z.storagebinid = a.storagebinid
	),0) as qtydetail
	from productoutputdetail a
	join product j on j.productid=a.productid
	where a.productoutputid = '".$_GET['id']."' group by toslocid,a.productid) zz
	where zz.qtystock < zz.qty or zz.qtydetail < zz.qty";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
      foreach ($dataReader1 as $row1) {
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          Yii::app()->format->formatNumber($row1['qtystock']),
          Yii::app()->format->formatNumber($row1['qtydetail']),
          Yii::app()->format->formatNumber($row1['selisih'])
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'productoutput';
    parent::actionDownxls();
    $sql = "select a.*,b.productplanno,b.productplandate
            from productoutput a 
	        join productplan b on b.productplanid = a.productplanid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productoutputid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 5;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(1, 3, 'No Output')
          ->setCellValueByColumnAndRow(2, 3, ': ' . $row['productoutputno'])
          ->setCellValueByColumnAndRow(1, 4, 'Tgl Output')
          ->setCellValueByColumnAndRow(2, 4, ': ' . $row['productplandate'])
          ->setCellValueByColumnAndRow(4, 3, 'No Plan')
          ->setCellValueByColumnAndRow(5, 3, ': ' . $row['productplanno']) 
          ->setCellValueByColumnAndRow(4, 4, 'Tgl Plan')
          ->setCellValueByColumnAndRow(5, 4, ': ' . $row['productplandate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'No')
          ->setCellValueByColumnAndRow(1, $line, 'Items')
          ->setCellValueByColumnAndRow(3, $line, 'Qty')
          ->setCellValueByColumnAndRow(4, $line, 'Unit')
          ->setCellValueByColumnAndRow(5, $line, 'Gudang')
          ->setCellValueByColumnAndRow(6, $line, 'Rak');
      $line++;
      $sql1        = "select b.productname, a.qtyoutput as qty, c.uomcode, a.description,
			concat(d.sloccode,'-',d.description) as sloccode, 
			e.description as rak
            from productoutputfg a
                inner join product b on b.productid = a.productid
                inner join unitofmeasure c on c.unitofmeasureid = a.uomid
				inner join sloc d on d.slocid = a.slocid
				inner join storagebin e on e.storagebinid = a.storagebinid
        where productoutputid = " . $row['productoutputid'];
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $line, $i += 1)
            ->setCellValueByColumnAndRow(1, $line, $row1['productname'])
            ->setCellValueByColumnAndRow(3, $line, $row1['qty'])
            ->setCellValueByColumnAndRow(4, $line, $row1['uomcode'])
            ->setCellValueByColumnAndRow(5, $line, $row1['sloccode'])
            ->setCellValueByColumnAndRow(6, $line, $row1['rak']);
       
$sql2        = "SELECT DISTINCT t.*,a.productname,b.uomcode,
					(select sloccode from sloc zz where zz.slocid = t.fromslocid) as fromsloccode,
			(select description from sloc zz where zz.slocid = t.fromslocid) as fromslocdesc,
			(select sloccode from sloc zz where zz.slocid = t.toslocid) as tosloccode,
			(select description from sloc zz where zz.slocid = t.toslocid) as toslocdesc,
			d.description as rak,
			getstock(t.productid,t.uomid,t.fromslocid) as fromslocstock,
			getstock(t.productid,t.uomid,t.toslocid) as toslocstock,
			getminstockmrp(t.productid,t.uomid,t.fromslocid) as minfromstock,
			getminstockmrp(t.productid,t.uomid,t.toslocid) as mintostock
FROM productoutputdetail t
LEFT JOIN productoutputfg c ON c.productoutputfgid = t.productoutputfgid
LEFT JOIN product a ON a.productid = t.productid
LEFT JOIN unitofmeasure b ON b.unitofmeasureid = t.uomid
LEFT JOIN storagebin d ON d.storagebinid = t.storagebinid 

 where t.productoutputid = " . $row['productoutputid'] ;
      $dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
      $c           = 0;
      $line++; 
            $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(1, $line, 'Material/Service - FG');
      $line++;  
          $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(1, $line, 'No')
          ->setCellValueByColumnAndRow(2, $line, 'Items')
          ->setCellValueByColumnAndRow(3, $line, 'Qty')
          ->setCellValueByColumnAndRow(4, $line, 'Unit')
          ->setCellValueByColumnAndRow(5, $line, 'Gudang Asal')
          ->setCellValueByColumnAndRow(6, $line, 'Stock Gd Asal')
          ->setCellValueByColumnAndRow(7, $line, 'Stock Gd Tujuan')
          ->setCellValueByColumnAndRow(8, $line, 'Rak');
      $line++;
      foreach ($dataReader2 as $row2) {
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $line, $c += 1)
            ->setCellValueByColumnAndRow(2, $line, $row2['productname'])
            ->setCellValueByColumnAndRow(3, $line, $row2['qty'])
            ->setCellValueByColumnAndRow(4, $line, $row2['uomcode'])
            ->setCellValueByColumnAndRow(5, $line, $row2['fromsloccode'])
            ->setCellValueByColumnAndRow(6, $line, $row2['fromslocstock'])
            ->setCellValueByColumnAndRow(7, $line, $row2['toslocstock'])
            ->setCellValueByColumnAndRow(8, $line, $row2['rak']);
        $line++;
      }
      }
     
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)
      ->setCellValueByColumnAndRow(1, $line, 'Approved By')
      ->setCellValueByColumnAndRow(4, $line, 'Proposed By');
      
    $line += 4;
    $this->phpExcel->setActiveSheetIndex(0)    
      ->setCellValueByColumnAndRow(1,$line, '____________ ')
      ->setCellValueByColumnAndRow(4, $line, '____________ ');
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
