<?php
class ProductplanController extends Controller
{
  public $menuname = 'productplan';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  } 
	public function actionIndexcombo()
  {
    if (isset($_GET['grid']))
      echo $this->searchcombo();
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
			$sql = "insert into productplan (productplandate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insprodplan').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'productplanid' => $id
			));
    }
  }
  public function searchcombo()
  {
    header("Content-Type: application/json");
    $productplanid   = isset($_GET['q']) ? $_GET['q'] : '';
    $productplanno   = isset($_GET['q']) ? $_GET['q'] : '';
    $description     = isset($_GET['q']) ? $_GET['q'] : '';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : 't.productplanid';
    $order           = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		$connection			 = Yii::app()->db;
		$from = '
			from productplan t 
			left join soheader a on a.soheaderid = t.soheaderid
            join company b on b.companyid = t.companyid ';
		$where = "
			where ((coalesce(description,'') like '%".$description."%') or (productplanid like '%".$productplanid."%') or (productplanno like '%".$productplanno."%')) 
				and t.productplandate <= curdate() and t.recordstatus = 3 and t.companyid in (".getUserObjectValues('company').") ";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.productplanid,t.productplanno,t.description,b.companyname '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productplanid' => $data['productplanid'],
        'productplanno' => $data['productplanno'],
        'description' => $data['description'],
        'companyname' => $data['companyname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function search()
  {
    header("Content-Type: application/json");
    $productplanid   = isset($_POST['productplanid']) ? $_POST['productplanid'] : '';
    $company       = isset($_POST['company']) ? $_POST['company'] : '';
    $productplanno   = isset($_POST['productplanno']) ? $_POST['productplanno'] : '';
    $sono      			 = isset($_POST['sono']) ? $_POST['sono'] : '';
    $description     = isset($_POST['description']) ? $_POST['description'] : '';
    $productplandate = isset($_POST['productplandate']) ? $_POST['productplandate'] : '';
    $customer = isset($_POST['customer']) ? $_POST['customer'] : '';
    $foreman = isset($_POST['foreman']) ? $_POST['foreman'] : '';
    $productdetail = isset($_POST['productdetail']) ? $_POST['productdetail'] : '';
    $productfg = isset($_POST['productfg']) ? $_POST['productfg'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productplanid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $whereproductfg='';
    $whereproductdetail = '';
    if($productdetail!='') {
        $whereproductdetail = " and t.productplanid in (select a.productplanid
        from productplan a
        join productplandetail b on b.productplanid = a.productplanid
        join product c on c.productid = b.productid
        where c.productname like '%".$productdetail."%') ";
    }
    if($productfg!='') {
        $whereproductfg =" and t.productplanid in (select a.productplanid
        from productplan a
        join productplanfg b on b.productplanid = a.productplanid
        join product c on c.productid = b.productid
        where c.productname like '%".$productfg."%') ";
    }
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appprodplan')")->queryScalar();
		$connection		= Yii::app()->db;
		$from = ' 
			from productplan t 
			left join soheader a on a.soheaderid = t.soheaderid 
			left join addressbook b on b.addressbookid = a.addressbookid 
			left join company c on c.companyid = t.companyid 
			left join employee d on d.employeeid = t.employeeid ';
		$where = "
			where (coalesce(a.sono,'') like '%".$sono."%') and (coalesce(c.companyname,'') like '%".$company."%') 
				and (coalesce(t.productplanno,'') like '%".$productplanno."%') and (coalesce(t.productplandate,'') like '%".$productplandate."%') 
				and (t.productplanid like '%".$productplanid."%') 
				and (coalesce(b.fullname,'') like '%".$customer."%') and (coalesce(d.fullname,'') like '%".$foreman."%') and (coalesce(t.description,'') like '%".$description."%') 
				and t.recordstatus in (".getUserRecordStatus('listprodplan').")
				-- and t.recordstatus < {$maxstat}
        and t.companyid in (".getUserObjectValues('company').")
				and t.productplanid in (select distinct a1.productplanid from productplanfg a1 WHERE a1.qty <> a1.qtyres AND a1.productplanid IS NOT null)
                {$whereproductfg} {$whereproductdetail}
        ";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = "
			select t.productplanid,t.soheaderid,a.sono,t.productplanno,t.companyid,c.companyname,b.fullname,t.productplandate,
				t.description,t.statusname,t.recordstatus,t.employeeid,t.pptype,d.fullname as foreman,(
				select case when sum(z.qty) > sum(z.qtyres) then 1 else 0 end 
				from productplanfg z 
				where z.productplanid = t.productplanid  
				) as warna,
                if(pptype = 0, '-',d.fullname) as foremanname ".$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productplanid' => $data['productplanid'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'productplanno' => $data['productplanno'],
        'warna' => $data['warna'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'customername' => $data['fullname'],
        'employeeid' => $data['employeeid'],
        'foreman' => $data['foreman'],
        'foremanname' => $data['foremanname'],
        'pptype' => $data['pptype'],
        'productplandate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['productplandate'])),
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusproductplan' => $data['statusname']
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productplanfg t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('billofmaterial e', 'e.bomid = t.bomid')->leftjoin('machine f', 'f.machineid = t.machineid')->leftjoin('employee g', 'g.employeeid = t.employeeid')->where('t.productplanid = :productplanid', array(
      ':productplanid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,e.bomversion,c.sloccode,c.description as slocdesc,getstock(t.productid,t.uomid,t.slocid) as stock, f.machinecode, g.fullname')->from('productplanfg t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('billofmaterial e', 'e.bomid = t.bomid')->leftjoin('machine f', 'f.machineid = t.machineid')->leftjoin('employee g', 'g.employeeid = t.employeeid')->where('t.productplanid = :productplanid', array(
      ':productplanid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productplanfgid' => $data['productplanfgid'],
        'productplanid' => $data['productplanid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qtyres' => Yii::app()->format->formatNumber($data['qtyres']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'bomid' => $data['bomid'],
        'bomversion' => $data['bomversion'],
        'startdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
        'enddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'stock' => Yii::app()->format->formatNumber($data['stock']),
        'description' => $data['description'],
        'machineid' => $data['machineid'],
        'machinecode' => $data['machinecode'],
        'employeeid' => $data['machineid'],
        'fullname' => $data['fullname']
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
    $id              = 0;
    $productplanfgid = '';
    if (isset($_POST['productplanfgid'])) {
      $productplanfgid = $_POST['productplanfgid'];
    }
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } else if (isset($_POST['id'])) {
      $id = $_POST['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productplandetail t')->join('productplanfg c', 'c.productplanfgid = t.productplanfgid and c.productplanid = t.productplanid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('billofmaterial d', 'd.bomid = t.bomid')->where('c.productplanid = :productplanid and c.productplanfgid = :productplanfgid', array(
      ':productplanid' => $id,
      ':productplanfgid' => $productplanfgid
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,d.bomversion,
					(select sloccode from sloc zz where zz.slocid = t.fromslocid) as fromsloccode,
					(select description from sloc zz where zz.slocid = t.fromslocid) as fromslocdesc,
					(select sloccode from sloc zz where zz.slocid = t.toslocid) as tosloccode,
					(select description from sloc zz where zz.slocid = t.toslocid) as toslocdesc,
					getstock(t.productid,t.uomid,t.fromslocid) as stockfrom,
					getstock(t.productid,t.uomid,t.toslocid) as stockto,
                    (select sum(x.qty)
                    from productplandetail x
                    join productplanfg y on y.productplanfgid = x.productplanfgid
                    join productplan z on z.productplanid = y.productplanid
                    where x.productid = t.productid and x.toslocid = t.toslocid
                    and y.startdate <= now() and y.startdate >= date_sub(now(),interval 1 MONTH)
                    and z.recordstatus=3 and y.productplanfgid <> c.productplanfgid 
                    and x.qtyres < x.qty
                    ) as dipakai')->from('productplandetail t')->join('productplanfg c', 'c.productplanfgid = t.productplanfgid and c.productplanid = t.productplanid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('billofmaterial d', 'd.bomid = t.bomid')->where('c.productplanid = :productplanid and c.productplanfgid = :productplanfgid', array(
      ':productplanid' => $id,
      ':productplanfgid' => $productplanfgid
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productplandetailid' => $data['productplandetailid'],
        'productplanfgid' => $data['productplanfgid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'fromslocid' => $data['fromslocid'],
        'fromsloccode' => $data['fromsloccode'].' - '.$data['fromslocdesc'],
        'toslocid' => $data['toslocid'],
        'tosloccode' => $data['tosloccode'].' - '.$data['fromslocdesc'],
        'bomid' => $data['bomid'],
        'stockfrom' => Yii::app()->format->formatNumber($data['stockfrom']),
        'stockto' => Yii::app()->format->formatNumber($data['stockto']),
        'dipakai' => Yii::app()->format->formatNumber($data['dipakai']),
        'bomversion' => $data['bomversion'],
        'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
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
        $sql     = 'call Insertproductplan(:vcompanyid,:vsoheaderid,:vproductplandate,:vpptype,:vemployeeid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductplan(:vid,:vcompanyid,:vsoheaderid,:vproductplandate,:vpptype,:vemployeeid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productplanid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productplanid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vsoheaderid', $_POST['soheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductplandate', date(Yii::app()->params['datetodb'], strtotime($_POST['productplandate'])), PDO::PARAM_STR);
      $command->bindvalue(':vpptype', $_POST['pptype'], PDO::PARAM_STR);
      $command->bindvalue(':vemployeeid', $_POST['employeeid'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
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
  public function actionSavedetailfg()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertproductplanfg(:vcompanyid,:vproductplanid,:vproductid,:vqty,:vuomid,:vslocid,:vbomid,:vstartdate,:venddate,:vmachineid,:vemployeeid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductplanfg(:vid,:vcompanyid,:vproductplanid,:vproductid,:vqty,:vuomid,:vslocid,:vbomid,:vstartdate,:venddate,:vmachineid,:vemployeeid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productplanfgid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productplanfgid']);
      }
      $command->bindvalue(':vproductplanid', $_POST['productplanid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vbomid', $_POST['bomid'], PDO::PARAM_STR);
      $command->bindvalue(':vstartdate', date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])), PDO::PARAM_STR);
      $command->bindvalue(':venddate', date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])), PDO::PARAM_STR);
      $command->bindvalue(':vmachineid', $_POST['machineid'], PDO::PARAM_STR);
      $command->bindvalue(':vemployeeid', $_POST['employeeid'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
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
  public function actionSavedetail()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord']) && $_POST['isNewRecord'] == true) {
        $sql     = 'call Insertproductplandetail(:vproductplanid,:vproductplanfgid,:vproductid,:vqty,:vuomid,:vfromslocid,:vtoslocid,:vbomid,:vreqdate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductplandetail(:vid,:vproductplanid,:vproductplanfgid,:vproductid,:vqty,:vuomid,
			:vfromslocid,:vtoslocid,:vbomid, :vreqdate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productplandetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['productplandetailid']);
      }
      $command->bindvalue(':vproductplanid', $_POST['productplanid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductplanfgid', $_POST['productplanfgid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vfromslocid', $_POST['fromslocid'], PDO::PARAM_STR);
      $command->bindvalue(':vtoslocid', $_POST['toslocid'], PDO::PARAM_STR);
      $command->bindvalue(':vbomid', $_POST['bomid'], PDO::PARAM_STR);
      $command->bindvalue(':vreqdate', date(Yii::app()->params['datetodb'], strtotime($_POST['reqdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectProductplan(:vid,:vcreatedby)';
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
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveproductPlan(:vid,:vcreatedby)';
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
        $sql     = 'call Purgeproductplan(:vid,:vcreatedby)';
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
  public function actionPurgedetailfg()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductplanfg(:vid,:vcreatedby)';
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
  public function actionPurgedetail()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeproductplandetail(:vid,:vcreatedby)';
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
  public function actionGeneratedetail()
  {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GeneratePPSO(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success'
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
  public function actionGenerateBarcode()
  {
    $ids = $_REQUEST['id'];
    foreach ($ids as $id) 
		{
      $sql    = "select ifnull(recordstatus,0) as recordstatus from productplan where productplanid  = " . $id . " and recordstatus = getwfmaxstatbywfname('appprodplan')";
      $status = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql1    = "select ifnull(isbarcode,0) as isbarcode from productplan where productplanid  = " . $id . " and recordstatus = getwfmaxstatbywfname('appprodplan')";
      $isbarcode = Yii::app()->db->createCommand($sql1)->queryScalar();
      $sql2    = "select ifnull(count(1),0) as barcode from productplanfg a join product b on b.productid=a.productid where productplanid  = " . $id . " and barcode = '' ";
      $barcode = Yii::app()->db->createCommand($sql2)->queryScalar();
      if ($status == 0) 
			{
        GetMessage('failure', 'docnotmaxstatus');
			} 
			else 
			if ($isbarcode == 1)
			{
				GetMessage('failure', 'datagenerated');
			}
			else
      if ($barcode != 0)
      {
        GetMessage('failure', 'emptybarcode');
      }
      else
			/*{				
				$update = "update productplan set isbarcode = 1 where productplanid = " . $id;
				Yii::app()->db->createCommand($update)->execute();				
				
				$sql = "insert into tempscan (companyid,productplanid,productplanfgid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
				select a.companyid,a.productplanid,b.productplanfgid,b.productid,b.slocid,b.qty,0,c.barcode,b.uomid,1
				from productplan a 
				join productplanfg b on b.productplanid = a.productplanid 
				join product c on c.productid = b.productid 
				where a.productplanid = " . $id;
				Yii::app()->db->createCommand($sql)->execute();
				$sql  = "select a.companyid,a.productplanid,b.productplanfgid,b.productid,b.slocid,b.qty,0,c.barcode,b.uomid,1,0,f.plantid,b.startdate
						from productplan a 
						join productplanfg b on b.productplanid = a.productplanid 
						join product c on c.productid = b.productid 
						join sloc d on d.slocid = b.slocid 
						join plant f on f.plantid = d.plantid 
						join unitofmeasure e on e.unitofmeasureid = b.uomid
						where a.productplanid = " . $id;
				$rows = Yii::app()->db->createCommand($sql)->queryAll();
				foreach ($rows as $row) {
					for ($i = 1; $i <= $row['qty']; $i++) {
						$sql = "insert into tempscan (companyid,productplanid,productplanfgid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
							values (" . $row['companyid'] . "," . $row['productplanid'] . "," . $row['productplanfgid'] . "," . $row['productid'] . "," . $row['slocid'] . ",1,0,concat(" . $row['plantid'] . $row['productplanfgid'] . ",'-P',lpad(" . $i . ",5,'0'))," . $row['uomid'] . ",0)";
						Yii::app()->db->createCommand($sql)->execute();
					}
				}
				GetMessage('success', 'GenerateBarcodeDone');
			}*/
			{
        try
				{
					$connection = Yii::app()->db;
					$transaction = $connection->beginTransaction();
					$update = "update productplan set isbarcode = 1 where productplanid = " . $id;
					$command1 = $connection->createCommand($update);
          $command1->execute();		
				
					$sql = "insert into tempscan (companyid,productplanid,productplanfgid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
									select a.companyid,a.productplanid,b.productplanfgid,b.productid,b.slocid,b.qty,0,c.barcode,b.uomid,1
									from productplan a 
									join productplanfg b on b.productplanid = a.productplanid 
									join product c on c.productid = b.productid 
									where a.productplanid = " . $id;
				
					$command2 = $connection->createCommand($sql);
					$command2->execute();
              
					$sql  = "select a.companyid,a.productplanid,b.productplanfgid,b.productid,b.slocid,b.qty,0,c.barcode,b.uomid,1,0,f.plantid,b.startdate
									from productplan a 
									join productplanfg b on b.productplanid = a.productplanid 
									join product c on c.productid = b.productid 
									join sloc d on d.slocid = b.slocid 
									join plant f on f.plantid = d.plantid 
									join unitofmeasure e on e.unitofmeasureid = b.uomid
									where a.productplanid = " . $id;
					$rows = Yii::app()->db->createCommand($sql)->queryAll();
					foreach ($rows as $row)
					{
						for ($i = 1; $i <= $row['qty']; $i++) 
						{
							$sql1 = "insert into tempscan (companyid,productplanid,productplanfgid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
											values (" . $row['companyid'] . "," . $row['productplanid'] . "," . $row['productplanfgid'] . "," . $row['productid'] . "," . $row['slocid'] . ",1,0,concat(" . $row['productplanfgid'] . ",'-P',lpad(" . $i . ",5,'0'))," . $row['uomid'] . ",0)";
						
							$command3 = $connection->createCommand($sql1);
							$command3->execute();
						}
					}
					$transaction->commit();
					GetMessage('success', 'GenerateBarcodeDone');
        }
        catch (Exception $e)
				{
					$transaction->rollBack();
					GetMessage('failure', $e->getMessage());
        }
      }
    }
  }
  /*public function actionDownEan13()
  {
    parent::actionDownload();
    $this->pdf->SetY(-8);
		$sql = "select a.*,b.productname 
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.productplanid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->AddPage('P', array(
      32.7,
      52.5
    ));
    $this->pdf->isfooter = false;
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
        $this->pdf->Line(32.7,3.5,32.7,2);
        $this->pdf->Line(32.7,51.5,32.7,50);
				$this->pdf->setxy(4.5, 19.5);
        $this->pdf->SetFont('Arial', 'B', 5.5);
				$this->pdf->CustomMultiCell(25, 2.5, $row['productname'], 0, 'C', False);
        $this->pdf->EAN13(7.5, $this->pdf->gety(), $row['barcode'], $h=5, $w=.20);
        $this->pdf->sety($this->pdf->gety() +10);
        $sql   = "select a.*,b.productname 
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
        $c128s = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($c128s as $c128) {
          $code = $c128['barcode'];
          $this->pdf->Code128(1.5, $this->pdf->gety()-2, $code, 30, 6);
          $this->pdf->SetFont('Arial', 'B', 6);
          $this->pdf->text(9, $this->pdf->gety() + 6, $code);
        }
        $this->pdf->AddPage('P', array(
					32.7,
					52.5
        ));
      }
    }
    $this->pdf->Output();
  }*/
  public function actionDownSticker()
  {
    parent::actionDownload();
		$sql = "select a.*,b.productname,
					substr(productname,1,(20+instr(substr(productname,21,20),' '))) as productname1,
					substr(productname,(21+instr(substr(productname,21,20),' ')),(20+instr(substr(productname,21,20),' '))) as productname2,
					substr(productname,((21+instr(substr(productname,21,20),' '))+(20+instr(substr(productname,21,20),' '))),(20+instr(substr(productname,21,20),' '))) as productname3
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.productplanid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->AddPage('P', array(
      82.6,
      108.3425
    ));
    $x = 0; $y = 0; $hitung = 0;
    $this->pdf->isfooter = false;
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
      		$hitung += 1;
      		//jika sisa pembagian dengan 2 tidak ada sisa, maka baris baru
      		if ($hitung % 2 != 0) {
      			$x = 10;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['productname1']);
						$this->pdf->text($x-8, $y+6, $row['productname2']);				
						$this->pdf->text($x-8, $y+8, $row['productname3']);
      			$this->pdf->SetFont('Arial', '', 5);
		      	$this->pdf->EAN13($x+1, $y+8.5, $row['barcode'], $h=3, $w=.20);
				    $sql   = "select a.*,b.productname 
						from tempscan a 
						join product b on b.productid = a.productid 
						where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
						and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
				    $c128s = Yii::app()->db->createCommand($sql)->queryAll();
				    foreach ($c128s as $c128) {
					    $code = $c128['barcode'];
					    $this->pdf->Code128($x-4.5, $y+15, $code, 30, 3);
					    $this->pdf->text($x+5, $y +20, $code);
					  }
      			$this->pdf->sety($y);
      		} else {
      			$x = 50;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['productname1']);
						$this->pdf->text($x-8, $y+6, $row['productname2']);				
						$this->pdf->text($x-8, $y+8, $row['productname3']);
      			$this->pdf->SetFont('Arial', '', 5);
		      	$this->pdf->EAN13($x+1, $y+8.5, $row['barcode'], $h=3, $w=.20);
		      	$sql   = "select a.*,b.productname 
						from tempscan a 
						join product b on b.productid = a.productid 
						where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
						and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
				    $c128s = Yii::app()->db->createCommand($sql)->queryAll();
				    foreach ($c128s as $c128) {
					    $code = $c128['barcode'];
					    $this->pdf->Code128($x-4.5, $y+15, $code, 30, 3);
					    $this->pdf->text($x+5, $y+20, $code);
					  }
      			$y = $this->pdf->gety()+21.75;
      		}
      		if ($y > 90) {
      			$this->pdf->AddPage('P', array(
							82.6,
							108.3425
						));
      			$x = 0; $y = 0;
      		}
      }
      if ($y > 100) {
      	$this->pdf->checknewpage(5); 
  			//$i = $i-2;    		
  			$x = 0; $y = 0;
  		}
    }
    $this->pdf->Output();
  }
  public function actionDownSticker2()
  {
    parent::actionDownloadbarcode();
    $this->pdf->AddPage('P', array(
      150, 150
      
    ));
    $this->pdf->SetY(-8);
    $this->pdf->SetFooterMargin(-15);
		$sql = "select a.barcode, a.qtyori, b.productname, a.productid
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.productplanid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->SetAutoPageBreak(TRUE, -20);

      
    //$this->pdf->AddPage("L", "mm", $pageLayout , true, 'UTF-8', false);
    //$this->pdf->isfooter = false;
    $style = array(
    'position' => 'L',
    'align' => 'L',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 10,
    'stretchtext' => 15
);
   
    $hitung = 1;
    $x = 7; $flag = 1; $c = 1;
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
          if($hitung <= 5 && $flag == 1){
            //$this->pdf->setY($this->pdf->getY()+10);
            $sql   = "select b.productname, a.qtyori, a.productid, a.barcode
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
            $c128s = Yii::app()->db->createCommand($sql)->queryRow();
            //$this->pdf->Line(109,25,109,20);
            //$this->pdf->Line(109,155,109,150);
            $code = $c128s['barcode'];
            if($hitung==5) {
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $x-($c+1), 23, 15, 15, '', 'N');
                $mid_x = 11.5;
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->text($x-($c+1.5), 38, $code);  
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->Ln(3);
                //$this->pdf->setX($this->pdf->getX()-8);
                //$this->pdf->MultiCell(20, 1,$row['productname'], 0, 'C', false);
                $this->pdf->MultiCell(18, 1, $row['productname'], 0, 'C', 0, 0, $x-($c+2), '', false, 0, false, true, 40, 'T');
            }else{
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $x-($c), 23, 15, 15, '', 'N');
                $mid_x = 11.5;
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->text($x-($c+0.5), 38, $code);  
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->Ln(3);
                //$this->pdf->setX($this->pdf->getX()-8);
                //$this->pdf->MultiCell(20, 1,$row['productname'], 0, 'C', false);
                $this->pdf->MultiCell(18, 1, $row['productname'], 0, 'C', 0, 0, $x-($c+1.5), '', false, 0, false, true, 40, 'T');
            }
        
        //$this->pdf->write1DBarcode($row['barcode'], 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
        //$y = $this->pdf->getY();
        //$this->pdf->SetY(-25);
        //$this->pdf->text(31,$y-3.8,$row['barcode']);
        }else{
            //$this->pdf->setY($this->pdf->getY()+10);
            $sql   = "select b.productname, a.qtyori, a.productid, a.barcode
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
            $c128s = Yii::app()->db->createCommand($sql)->queryRow();
            //$this->pdf->Line(109,25,109,20);
            //$this->pdf->Line(109,155,109,150);
              $code = $c128s['barcode'];
            if($hitung==10) {
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $x-($c+1), 73, 15, 15, '', 'N');
                $mid_x = 11.5;
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->text($x-($c+1.5), 88, $code);  
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->Ln(3);

                $this->pdf->MultiCell(18, 1, $row['productname'], 0, 'C', 0, 0, $x-($c+1.5), 91, false, 0, false, true, 40, 'T');
            }else{
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $x-($c), 73, 15, 15, '', 'N');
                $mid_x = 11.5;
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->text($x-($c+0.5), 88, $code);  
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->Ln(3);

                $this->pdf->MultiCell(18, 1, $row['productname'], 0, 'C', 0, 0, $x-($c+1.5), 91, false, 0, false, true, 40, 'T');
            }
        
        //$this->pdf->write1DBarcode($row['barcode'], 'EAN13', '', '', '', 5, 0.2, $style, 'N'); 
        //$y = $this->pdf->getY();
        //$this->pdf->SetY(-25);
        //$this->pdf->text(31,$y-3.8,$row['barcode']);       
        
        }
        $hitung++;
        $x += 28;
		$c++;
        if($hitung == 6 ){
            $x = 7;
			$c=1;
            $flag = 2;
        }
        if($hitung > 10){
            $hitung = 1;
			$c=1;
            $flag = 1;
            $this->pdf->AddPage('L', array(
							150,
							150
						));
            $x = 7;
        }
        
      }
    }
		$id = $_REQUEST['id'];
    $this->pdf->Output($id.'.pdf', 'I');
  }
  public function actionDownSticker3()
  {
    parent::actionDownloadbarcode();
    $this->pdf->AddPage('P', array(
      150, 150
      
    ));
    $this->pdf->SetY(-8);
    $this->pdf->SetFooterMargin(-15);
		$sql = "select a.barcode, a.qtyori, b.productname, a.productid
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.productplanid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->SetAutoPageBreak(TRUE, -20);

      
    //$this->pdf->AddPage("L", "mm", $pageLayout , true, 'UTF-8', false);
    //$this->pdf->isfooter = false;
    $style = array(
    'position' => 'L',
    'align' => 'L',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 10,
    'stretchtext' => 15
);
   
    $hitung = 1;
    $x = 7; $flag = 1; $c = 1;
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
          if($hitung <= 5 && $flag == 1){
            //$this->pdf->setY($this->pdf->getY()+10);
            $sql   = "select b.productname, a.qtyori, a.productid, a.barcode
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
            $c128s = Yii::app()->db->createCommand($sql)->queryRow();
            //$this->pdf->Line(109,25,109,20);
            //$this->pdf->Line(109,155,109,150);
            $code = $c128s['barcode'];
            if($hitung==5) {  
                $this->pdf->setFont('helvetica','B',5);
                //$this->pdf->setX($this->pdf->getX()-8);
                //$this->pdf->MultiCell(20, 1,$row['productname'], 0, 'C', false);
                $this->pdf->MultiCell(18, 1, $row['productname'].' RANGKA PER 2.40 ', 0, 'C', 0, 0, $x-($c+2), 21.5, false, 0, false, true, 20, 'T');
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $x-($c+1), 32, 15, 15, '', 'N');
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->text($x-($c+1.5), 48, $code);
            }else{  
                $this->pdf->setFont('helvetica','B',5);
                //$this->pdf->setX($this->pdf->getX()-8);
                //$this->pdf->MultiCell(20, 1,$row['productname'], 0, 'C', false);
                $this->pdf->MultiCell(18, 1, $row['productname'].' RANGKA PER 2.40 ', 0, 'C', 0, 0, $x-($c+1.5), 21.5, false, 0, false, true, 20, 'T');
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $x-($c), 32, 15, 15, '', 'N');
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->text($x-($c+0.5), 48, $code);
            }
        
        //$this->pdf->write1DBarcode($row['barcode'], 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
        //$y = $this->pdf->getY();
        //$this->pdf->SetY(-25);
        //$this->pdf->text(31,$y-3.8,$row['barcode']);
        }else{
            //$this->pdf->setY($this->pdf->getY()+10);
            $sql   = "select b.productname, a.qtyori, a.productid, a.barcode
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
            $c128s = Yii::app()->db->createCommand($sql)->queryRow();
            //$this->pdf->Line(109,25,109,20);
            //$this->pdf->Line(109,155,109,150);
              $code = $c128s['barcode'];
            if($hitung==10) {
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->MultiCell(18, 1, $row['productname'].' RANGKA PER 2.40 ', 0, 'C', 0, 0, $x-($c+1.5), 72, false, 0, false, true, 40, 'T');
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $x-($c+1),82, 15, 15, '', 'N');
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->text($x-($c+1.5), 98, $code);  
            }else{
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->MultiCell(18, 1, $row['productname'].' RANGKA PER 2.40 ', 0, 'C', 0, 0, $x-($c+1.5), 72, false, 0, false, true, 40, 'T');
                $this->pdf->write2DBarcode($code, 'QRCODE,H', $x-($c),82, 15, 15, '', 'N');
                $this->pdf->setFont('helvetica','B',5);
                $this->pdf->text($x-($c+0.5), 98, $code);  
            }
        
        //$this->pdf->write1DBarcode($row['barcode'], 'EAN13', '', '', '', 5, 0.2, $style, 'N'); 
        //$y = $this->pdf->getY();
        //$this->pdf->SetY(-25);
        //$this->pdf->text(31,$y-3.8,$row['barcode']);       
        
        }
        $hitung++;
        $x += 28;
		$c++;
        if($hitung == 6 ){
            $x = 7;
			$c=1;
            $flag = 2;
        }
        if($hitung > 10){
            $hitung = 1;
			$c=1;
            $flag = 1;
            $this->pdf->AddPage('L', array(
							150,
							150
						));
            $x = 7;
        }
        
      }
    }
		$id = $_REQUEST['id'];
    $this->pdf->Output($id.'.pdf', 'I');
  }
  public function actionDownKbpoin()
  {
    parent::actionDownloadbarcode();
    //$this->pdf->SetY(-8);
    //$this->pdf->SetFooterMargin(-15);
	$sql = "select a.barcode, a.qtyori, b.productname, a.productid
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.productplanid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $width = 175;
    $height = 266;
    
    // print only promo barcode
    $pageLayout = array($width, $height);
    $this->pdf->SetPrintHeader(false);
    $this->pdf->SetPrintFooter(false);
    $this->pdf->SetAutoPageBreak(TRUE, -20);
        $this->pdf->AddPage('P', array(
					109,
					175
        ));
    $sqlpromo = "select IFNULL(COUNT(1),0) 
                 from tempscan a
                 join product b on b.productid = a.productid
                 where a.isean = 1 and a.productplanid = ".$_REQUEST['id']." AND b.productname like '%POIN %' LIMIT 1";
    $getproduct = Yii::app()->db->createCommand($sqlpromo)->queryScalar();
    if($getproduct==0){
        $this->pdf->SetFont('helvetica','B',20);
        $this->pdf->text(10, 75, 'Anda Belum Mendapatkan');
        $this->pdf->text(24, 85, 'Kasur Busa POIN');
        $this->pdf->text(14, 95, 'Silahkan Mencoba Lagi');
    }else{
        
    
    //$this->pdf->addFormat("custom",$width,$height);
    //$this->pdf->reFormat("custom",'P');
    
    //$this->pdf->AddPage("L", "mm", $pageLayout , true, 'UTF-8', false);
    //$this->pdf->isfooter = false;
    $style = array(
    'position' => 'C',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 15,
    'stretchtext' => 4
);
    /*
    define('B', Yii::app()->request->baseUrl);
    $codeContents = '123456DEMO'; 
    $this->pdf->setY($this->pdf->getY()+10);
    $this->pdf->write2DBarcode('PHP QR Code :)', 'QRCODE,H', 27, 52, 45, 45, '', 'N');
    $mid_x = 50; // the middle of the "PDF screen", fixed by now.
    $text = 'PHP QR Code :)';
    $this->pdf->setFont('helvetica','B',12);
    $this->pdf->text($mid_x - ($this->pdf->GetStringWidth($text) / 2), 98, $text);
    $this->pdf->setFont('helvetica','',18);
    $this->pdf->setFont('helvetica','B',18);
    $this->pdf->Ln(6);
    $this->pdf->MultiCell(80, 2.5,'MATRAS KANGAROO REGULAR E-CLASS KNITING 0178 COKLAT 180X200', 0, 'C', false);
    $this->pdf->write1DBarcode('1234567890128', 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
    $this->pdf->text(26.5,152,'8994349049122');
    */
    //$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //$this->pdf->setPrintFooter(true);
    //$this->pdf->SetFooterMargin(-100);
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
        $this->pdf->setY($this->pdf->getY()+10);
          $product1 = substr($row['productname'],0,10);
          $product2 = substr($row['productname'],11,4);
          $product3 = substr($row['productname'],16,9);
         $sql   = "select b.productname, a.productid, a.barcode
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
        $c128s = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($c128s as $c128) {          
            $this->pdf->Line(109,25,109,20);
            $this->pdf->Line(109,155,109,150);
            $code = $c128['barcode'];
            $this->pdf->write2DBarcode($code, 'QRCODE,H', 32, 40, 45, 45, '', 'N');
            $mid_x = 52;
            $this->pdf->setFont('helvetica','B',14);
            $this->pdf->text($mid_x - ($this->pdf->GetStringWidth($code) / 2), 88, $code);  
        }
        $this->pdf->setFont('helvetica','B',20);
        $this->pdf->Ln(6);
        $this->pdf->MultiCell(90, 2.5,$product1, 0, 'C', false);
        $this->pdf->setY(100);
        $this->pdf->setFont('helvetica','B',55);
        $this->pdf->MultiCell(90, 2.5,$product2, 0, 'C', false);
        $this->pdf->setFont('helvetica','B',20);
        $this->pdf->setY(122);
        $this->pdf->MultiCell(90, 2.5,$product3, 0, 'C', false);
        $this->pdf->setFont('helvetica','B',18);
        $this->pdf->write1DBarcode($row['barcode'], 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
        $y = $this->pdf->getY();
        $this->pdf->SetY(-25);
        $this->pdf->text(31,$y-3.8,$row['barcode']);
        $this->pdf->AddPage('P', array(
					109,
					175
        ));
      }
    }
    }
    $this->pdf->Output('test.pdf', 'I');
  }
	/*
  if (($i % 2) == 0) {
					$this->pdf->setxy(0, $this->pdf->gety());
		      $this->pdf->SetFont('Arial', 'B', 5);
					//$this->pdf->CustomMultiCell(41.3, 1.25, $row['productname'], 0, 'C', False);
					$this->pdf->text(2, $this->pdf->gety()+3, $row['productname1']);
					$this->pdf->text(2, $this->pdf->gety()+5, $row['productname2']);				
					$this->pdf->text(2, $this->pdf->gety()+7, $row['productname3']);
		      $this->pdf->sety($this->pdf->gety() +6);
		      $this->pdf->EAN13(9, $this->pdf->gety()+0.5, $row['barcode'], $h=3, $w=.20);
		      $this->pdf->sety($this->pdf->gety() +8);
		      $sql   = "select a.*,b.productname 
					from tempscan a 
					join product b on b.productid = a.productid 
					where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
					and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
		      $c128s = Yii::app()->db->createCommand($sql)->queryAll();
		      foreach ($c128s as $c128) {
		        $code = $c128['barcode'];
		        $this->pdf->Code128(3.5, $this->pdf->gety()-2, $code, 30, 3);
		        $this->pdf->SetFont('Arial', 'B', 5);
		        $this->pdf->text(15, $this->pdf->gety() +3, $code);
		      }
		      
		      $i += 1;
		      
					$this->pdf->setxy(40, $this->pdf->gety());
		      $this->pdf->SetFont('Arial', 'B', 5);
					//$this->pdf->CustomMultiCell(41.3, 1.25, $row['productname'], 0, 'C', False);
					$this->pdf->text(40, $this->pdf->gety()+3, $row['productname1']);
					$this->pdf->text(40, $this->pdf->gety()+5, $row['productname2']);				
					$this->pdf->text(40, $this->pdf->gety()+7, $row['productname3']);
		      $this->pdf->sety($this->pdf->gety() +6);
		      $this->pdf->EAN13(50, $this->pdf->gety()+0.5, $row['barcode'], $h=3, $w=.20);
		      $this->pdf->sety($this->pdf->gety() +8);
		      $sql   = "select a.*,b.productname 
					from tempscan a 
					join product b on b.productid = a.productid 
					where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
					and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
		      $c128s = Yii::app()->db->createCommand($sql)->queryAll();
		      foreach ($c128s as $c128) {
		        $code = $c128['barcode'];
		        $this->pdf->Code128(3.5, $this->pdf->gety()-2, $code, 70, 3);
		        $this->pdf->SetFont('Arial', 'B', 5);
		        $this->pdf->text(55, $this->pdf->gety() +3, $code);
		      }
		      
        } else {
        	$this->pdf->sety($this->pdf->gety()+20);*/
  public function actionDownCode128()
  {
    parent::actionDownload();
    $sql = "select a.*,b.productname 
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 0 and a.productplanid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->AddPage('P', array(
      60,
      40
    ));
    $this->pdf->isfooter = false;
    foreach ($fgs as $row) {
      $this->pdf->setxy(5, 5);
      $this->pdf->SetFont('Arial', '', 8);
      $this->pdf->row(array(
        $row['productname']
      ));
      $code = $row['barcode'];
      $this->pdf->Code128(6, $this->pdf->gety(), $code, 50, 15);
      $this->pdf->SetFont('Arial', '', 8);
      $this->pdf->text(15, $this->pdf->gety() + 18, $code);
      $this->pdf->AddPage('P', array(
        60,
        40
      ));
    }
    $this->pdf->Output();
  }
  public function lama_actionDownEan13()
  {
    parent::actionDownloadbarcode();
    $this->pdf->SetY(-8);
    $this->pdf->SetFooterMargin(-15);
		$sql = "select a.barcode, a.qtyori, b.productname, a.productid, a.productplanfgid
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.productplanid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->SetAutoPageBreak(TRUE, -20);
    $width = 175;
    $height = 266;
    //$this->pdf->addFormat("custom",$width,$height);
    //$this->pdf->reFormat("custom",'P');
    $pageLayout = array($width, $height);
        $this->pdf->AddPage('P', array(
					109,
					175
        ));
    //$this->pdf->AddPage("L", "mm", $pageLayout , true, 'UTF-8', false);
    //$this->pdf->isfooter = false;
    $style = array(
    'position' => 'C',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 15,
    'stretchtext' => 4
);
    /*
    define('B', Yii::app()->request->baseUrl);
    $codeContents = '123456DEMO'; 
    $this->pdf->setY($this->pdf->getY()+10);
    $this->pdf->write2DBarcode('PHP QR Code :)', 'QRCODE,H', 27, 52, 45, 45, '', 'N');
    $mid_x = 50; // the middle of the "PDF screen", fixed by now.
    $text = 'PHP QR Code :)';
    $this->pdf->setFont('helvetica','B',12);
    $this->pdf->text($mid_x - ($this->pdf->GetStringWidth($text) / 2), 98, $text);
    $this->pdf->setFont('helvetica','',18);
    $this->pdf->setFont('helvetica','B',18);
    $this->pdf->Ln(6);
    $this->pdf->MultiCell(80, 2.5,'MATRAS KANGAROO REGULAR E-CLASS KNITING 0178 COKLAT 180X200', 0, 'C', false);
    $this->pdf->write1DBarcode('1234567890128', 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
    $this->pdf->text(26.5,152,'8994349049122');
    */
    //$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //$this->pdf->setPrintFooter(true);
    //$this->pdf->SetFooterMargin(-100);
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
        $this->pdf->setY($this->pdf->getY()+10);
         $sql   = "select b.productname, a.qtyori, a.productid, a.barcode
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and a.productplanfgid = " . $row['productplanfgid'] . " and right(a.barcode,5) = " . $i;
        $c128s = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($c128s as $c128) {          
            $this->pdf->Line(109,25,109,20);
            $this->pdf->Line(109,155,109,150);
            $code = $c128['barcode'];
            $this->pdf->write2DBarcode($code, 'QRCODE,H', 32, 52, 45, 45, '', 'N');
            $mid_x = 52;
            $this->pdf->setFont('helvetica','B',14);
            $this->pdf->text($mid_x - ($this->pdf->GetStringWidth($code) / 2), 98, $code);  
        }
        $this->pdf->setFont('helvetica','B',18);
        $this->pdf->Ln(6);
        $this->pdf->MultiCell(90, 2.5,$row['productname'], 0, 'C', false);
        $this->pdf->write1DBarcode($row['barcode'], 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
        $y = $this->pdf->getY();
        $this->pdf->SetY(-25);
        $this->pdf->text(31,$y-3.8,$row['barcode']);
        $this->pdf->AddPage('P', array(
					109,
					175
        ));
      }
    }
		$id = $_REQUEST['id'];
    $this->pdf->Output($id.'.pdf', 'I');
  }
  public function actionDownEan13()
  {
    parent::actionDownloadbarcode();
    $this->pdf->SetY(-8);
    $this->pdf->SetFooterMargin(-15);
		$sql = "select a.barcode, a.qtyori, b.productname, a.productid, a.productplanfgid, b.k3lnumber
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.productplanid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->SetAutoPageBreak(TRUE, -20);
    $width = 175;
    $height = 266;
    //$this->pdf->addFormat("custom",$width,$height);
    //$this->pdf->reFormat("custom",'P');
    $pageLayout = array($width, $height);
        $this->pdf->AddPage('P', array(
					109,
					175
        ));
    //$this->pdf->AddPage("L", "mm", $pageLayout , true, 'UTF-8', false);
    //$this->pdf->isfooter = false;
    $style = array(
    'position' => 'C',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 15,
    'stretchtext' => 4
);
    /*
    define('B', Yii::app()->request->baseUrl);
    $codeContents = '123456DEMO'; 
    $this->pdf->setY($this->pdf->getY()+10);
    $this->pdf->write2DBarcode('PHP QR Code :)', 'QRCODE,H', 27, 52, 45, 45, '', 'N');
    $mid_x = 50; // the middle of the "PDF screen", fixed by now.
    $text = 'PHP QR Code :)';
    $this->pdf->setFont('helvetica','B',12);
    $this->pdf->text($mid_x - ($this->pdf->GetStringWidth($text) / 2), 98, $text);
    $this->pdf->setFont('helvetica','',18);
    $this->pdf->setFont('helvetica','B',18);
    $this->pdf->Ln(6);
    $this->pdf->MultiCell(80, 2.5,'MATRAS KANGAROO REGULAR E-CLASS KNITING 0178 COKLAT 180X200', 0, 'C', false);
    $this->pdf->write1DBarcode('1234567890128', 'EAN13', '', '', '', 20, 0.9, $style, 'N'); 
    $this->pdf->text(26.5,152,'8994349049122');
    */
    //$this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //$this->pdf->setPrintFooter(true);
    //$this->pdf->SetFooterMargin(-100);
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
        $this->pdf->setY($this->pdf->getY()+10);
         $sql   = "select b.productname, a.qtyori, a.productid, a.barcode
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.productplanid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and a.productplanfgid = " . $row['productplanfgid'] . " and right(a.barcode,5) = " . $i;
        $c128s = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($c128s as $c128) {          
            $this->pdf->Line(109,25,109,20);
            $this->pdf->Line(109,155,109,150);
            $code = $c128['barcode'];
            $this->pdf->write2DBarcode($code, 'QRCODE,H', 34, 52, 42, 42, '', 'N');
            $mid_x = 52;
            $this->pdf->setFont('helvetica','B',14);
            $this->pdf->text($mid_x - ($this->pdf->GetStringWidth($code) / 2), 95, $code);  
        }
        $this->pdf->setFont('helvetica','B',16);
        $this->pdf->Ln(6);
        $this->pdf->MultiCell(90, 2.5,$row['productname'], 0, 'C', false);
        $this->pdf->write1DBarcode($row['barcode'], 'EAN13', '', '', '', 21, 0.9, $style, 'N'); 
        $y = $this->pdf->getY();
        $this->pdf->SetY(-25);
        $this->pdf->setFont('helvetica','B',15);
        $this->pdf->text(36,$y-3.8,$row['barcode']);
        $this->pdf->setFont('helvetica','B',13.5);
        $this->pdf->text(9,$y+1.5,$row['k3lnumber']);
        $this->pdf->AddPage('P', array(
					109,
					175
        ));
      }
    }
		$id = $_REQUEST['id'];
    $this->pdf->Output($id.'.pdf', 'I');
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select a.*,b.sono, c.fullname
            from productplan a
            left join soheader b on b.soheaderid = a.soheaderid 
            left join employee c on c.employeeid = a.employeeid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('productplan');
    $this->pdf->AddPage('P');
    $this->pdf->AliasNBPages();
    $this->pdf->SetFont('Arial');
    foreach ($dataReader as $row)
    {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No SPP ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(115, $this->pdf->gety() + 5, 'No SO ');
      $this->pdf->text(135, $this->pdf->gety() + 5, ': ' . $row['sono']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl SPP ');
      if($row['pptype']==1 && $row['employeeid']!= '') {
          $this->pdf->text(115, $this->pdf->gety() + 10, 'Foreman ');
          $this->pdf->text(135, $this->pdf->gety() + 10, ': '.$row['fullname']);
      }
      else
      {
          $this->pdf->text(115, $this->pdf->gety() + 10, 'Jenis SPP ');
          $this->pdf->text(135, $this->pdf->gety() + 10, ': UMUM');
      }
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $sql1 = "select b.productname, a.qty, c.uomcode, a.description,d.sloccode,d.description as slocdesc,a.startdate,a.enddate,a.qty * e.cycletime as ct,a.qty * e.cycletimemin as ctmin
                from productplanfg a
                left join billofmaterial e on e.bomid=a.bomid
                left join product b on b.productid = a.productid
                left join unitofmeasure c on c.unitofmeasureid = a.uomid
        left join sloc d on d.slocid = a.slocid
                where productplanid = " . $row['productplanid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 20, 'FG');
      $this->pdf->sety($this->pdf->gety() + 25);
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
        'C'
      );
      $this->pdf->setwidths(array(
        8,
        50,
        20,
        12,
        20,
        17,
        17,
        15,
        15,
        20
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang',
        'Tgl Mulai',
        'Tgl Selesai',
        'CT (sec)',
        'CT (min)',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L',
        'L',
        'R',
        'R',
        'L'
      );
      $i = 0;
      $totalqty=0;
      $totalct=0;
      $totalctmin=0;
      foreach ($dataReader1 as $row1)
      {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['sloccode'],/* . ' - ' . $row1['slocdesc'],*/
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['startdate'])),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['enddate'])),
          Yii::app()->format->formatCurrency($row1['ct']),
          Yii::app()->format->formatCurrency($row1['ctmin']),
          $row1['description']
        ));
        $totalqty += $row1['qty'];
        $totalct += $row1['ct'];
        $totalctmin += $row1['ctmin'];
      }
      $this->pdf->setwidths(array(58,20,60,21,15,20));
      $this->pdf->coldetailalign = array('R','R','R','R','R','L');      
      $this->pdf->row(array(
        'TOTAL QTY  >>>>>',
        Yii::app()->format->formatNumber($totalqty),
        'TOTAL CYCLE TIME  >>>>>',
        Yii::app()->format->formatCurrency($totalct),
        Yii::app()->format->formatCurrency($totalctmin),
        ''
      ));
      
      $sql1 = "select b.productname, sum(a.qty) as qty, c.uomcode, a.description,d.bomversion,
              (select sloccode from sloc d where d.slocid = a.fromslocid) as fromsloccode,
              (select description from sloc d where d.slocid = a.fromslocid) as fromslocdesc,
              (select sloccode from sloc d where d.slocid = a.toslocid) as tosloccode,  
              (select description from sloc d where d.slocid = a.toslocid) as toslocdesc      
                from productplandetail a
                left join product b on b.productid = a.productid
                left join unitofmeasure c on c.unitofmeasureid = a.uomid
        left join billofmaterial d on d.bomid = a.bomid
                where b.isstock = 1 and productplanid = " . $row['productplanid'] . " 
        group by b.productname,c.uomcode,d.bomversion,fromsloccode,fromslocdesc,tosloccode,toslocdesc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 10, 'RM');
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
        60,
        20,
        15,
        25,
        25,
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang Asal',
        'Gudang Tujuan',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1)
      {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['fromsloccode'] . ' - ' . $row1['fromslocdesc'],
          $row1['tosloccode'] . ' - ' . $row1['toslocdesc'],
          $row1['bomversion'] . '' . $row1['description']
        ));
        $this->pdf->checkPageBreak(20);
      }
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 10, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 30, '____________ ');
      $this->pdf->text(150, $this->pdf->gety() + 30, '____________');
    }
    $this->pdf->Output();
  }
  /*{
    parent::actionDownload();
    $sql = "select a.*,b.sono 
      from productplan a
left join soheader b on b.soheaderid = a.soheaderid	";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('productplan');
    $this->pdf->AddPage('P');
    $this->pdf->AliasNBPages();
    $this->pdf->SetFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No SPP ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(115, $this->pdf->gety() + 5, 'No SO ');
      $this->pdf->text(135, $this->pdf->gety() + 5, ': ' . $row['sono']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl SPP ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $sql1        = "select b.productname, a.qty, c.uomcode, a.description,d.sloccode,d.description as slocdesc,a.startdate,a.enddate
        from productplanfg a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join sloc d on d.slocid = a.slocid
        where productplanid = " . $row['productplanid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 20, 'FG');
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->colalign = array(
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
      $this->pdf->setwidths(array(
        10,
        50,
        20,
        15,
        36,
        17,
        17,
        25
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang',
        'Tgl Mulai',
        'Tgl Selesai',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
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
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['sloccode'] . ' - ' . $row1['slocdesc'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['startdate'])),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['enddate'])),
          $row1['description']
        ));
      }
      $sql1        = "select b.productname, sum(a.qty) as qty, c.uomcode, a.description,d.bomversion,
				(select sloccode from sloc d where d.slocid = a.fromslocid) as fromsloccode,
				(select description from sloc d where d.slocid = a.fromslocid) as fromslocdesc,
				(select sloccode from sloc d where d.slocid = a.toslocid) as tosloccode,	
				(select description from sloc d where d.slocid = a.toslocid) as toslocdesc			
        from productplandetail a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join billofmaterial d on d.bomid = a.bomid
        where b.isstock = 1 and productplanid = " . $row['productplanid'] . " 
				group by b.productname,c.uomcode,d.bomversion,fromsloccode,fromslocdesc,tosloccode,toslocdesc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 10, 'RM');
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
        60,
        20,
        15,
        25,
        25,
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang Asal',
        'Gudang Tujuan',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
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
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['fromsloccode'] . ' - ' . $row1['fromslocdesc'],
          $row1['tosloccode'] . ' - ' . $row1['toslocdesc'],
          $row1['bomversion'] . '' . $row1['description']
        ));
        $this->pdf->checkPageBreak(20);
      }
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 10, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 30, '____________ ');
      $this->pdf->text(150, $this->pdf->gety() + 30, '____________');
    }
    $this->pdf->Output();
  }*/
  public function actionDownPDF1()
  {
    parent::actionDownload();
    $sql = "select productplandate, productplanno,productplanid
		from productplan  ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where productplanid =" . $_GET['id'] . "";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "Material Yang Tidak Ada Di Stock Gudang Sumber";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID SPP');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['productplanid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Rencana ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO PO ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['productplanno']);
     
     
      $sql1= "select*, b.fromslocid,b.productid,b.productplanid,a.productname,c.sloccode,c.description, sum(b.qty) as jumlah
							from productplandetail b
							left join product a on a.productid=b.productid
							left join productplanfg d on d.productplanid=b.productplanid
							left join sloc c on c.slocid=d.slocid
							WHERE b.fromslocid NOT IN (
							SELECT z.slocid FROM productplant z WHERE z.productid = b.productid and z.issource = 1 and z.recordstatus = 1) 
							AND b.productplanid =".$row['productplanid'] ;
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          50,
          25,
          30,
          30
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Gudanng',
          'Qty', 
          'Keterangan' 
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],$row1['sloccode'], $row1['jumlah'], $row1['description']
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownPDF2()
  {
    parent::actionDownload();
    $sql = "select productplandate, productplanno,productplanid
from productplan  ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where productplanid =" . $_GET['id'] . "";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "Material Yang Tidak Ada Di Stock Gudang Tujuan";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID SPP');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['productplanid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Rencana ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO PO ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['productplanno']);
     
     
      $sql1= "select*, b.fromslocid,b.productid,b.productplanid,a.productname,c.sloccode,c.description, sum(b.qty) as jumlah
							from productplandetail b
							left join product a on a.productid=b.productid
							left join productplanfg d on d.productplanid=b.productplanid
							left join sloc c on c.slocid=d.slocid
							WHERE b.fromslocid NOT IN (
							SELECT z.slocid FROM productplant z WHERE z.productid = b.productid and z.issource = 1 and z.recordstatus=1) 
							AND b.productplanid =".$row['productplanid'] ;
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          50,
          25,
          30,
          30
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Gudanng',
          'Qty', 
          'Keterangan' 
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],$row1['sloccode'], $row1['jumlah'], $row1['description']
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownPDF3()
  {
    parent::actionDownload();
    $sql = "select productplandate, productplanno,productplanid
from productplan  ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where productplanid =" . $_GET['id'] . "";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "Satuan Yang Tidak Ada Di Stock Gudang Sumber";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID SPP');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['productplanid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Rencana ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO PO ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['productplanno']);
     
     
      $sql1= "select*, b.fromslocid,b.productid,b.productplanid,a.productname,c.sloccode,c.description, sum(b.qty) as jumlah
							from productplandetail b
							left join product a on a.productid=b.productid
							left join productplanfg d on d.productplanid=b.productplanid
							left join sloc c on c.slocid=d.slocid
							WHERE b.uomid NOT IN (
							SELECT z.unitofissue FROM productplant z WHERE z.productid = b.productid AND z.issource = 1 AND z.unitofissue = b.uomid )
							AND b.productplanid =".$row['productplanid'] ;
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          50,
          25,
          30,
          30
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Gudanng',
          'Qty', 
          'Keterangan' 
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],$row1['sloccode'], $row1['jumlah'], $row1['description']
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownPDF4()
  {
    parent::actionDownload();
    $sql = "select productplandate, productplanno,productplanid
from productplan  ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where productplanid =" . $_GET['id'] . "";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "Satuan Yang Tidak Ada Di Stock Gudang Tujuan";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID SPP');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['productplanid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Rencana ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO PO ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['productplanno']);
     
     
      $sql1= "select*, b.fromslocid,b.productid,b.productplanid,a.productname,c.sloccode,c.description, sum(b.qty) as jumlah
							from productplandetail b
							left join product a on a.productid=b.productid
							left join productplanfg d on d.productplanid=b.productplanid
							left join sloc c on c.slocid=d.slocid
							WHERE b.uomid NOT IN (
							SELECT z.unitofissue FROM productplant z WHERE z.productid = b.productid AND z.recordstatus = 1 AND z.unitofissue = b.uomid )
							AND b.productplanid =".$row['productplanid'] ;
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          50,
          25,
          30,
          30
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Gudanng',
          'Qty', 
          'Keterangan' 
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],$row1['sloccode'], $row1['jumlah'], $row1['description']
        ));
      }
    }
    $this->pdf->Output();
  }    
  public function actionDownPDF5()
  {
      parent::actionDownload();
      $sql = "select a.*,b.sono 
      from productplan a
      left join soheader b on b.soheaderid = a.soheaderid	";
      if ($_GET['id'] !== '') {
          $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
      }
      $command          = Yii::app()->db->createCommand($sql);
      $dataReader       = $command->queryAll();
      $this->pdf->title = GetCatalog('productplan');
      $this->pdf->AddPage('P',array(220,297));
      $this->pdf->AliasNBPages();
      $this->pdf->SetFont('Arial');
      
		foreach ($dataReader as $row) {
			$this->pdf->SetFontSize(8);
			$this->pdf->text(15, $this->pdf->gety() + 5, 'No SPP ');
			$this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
			$this->pdf->text(115, $this->pdf->gety() + 5, 'No SO ');
			$this->pdf->text(135, $this->pdf->gety() + 5, ': ' . $row['sono']);
			$this->pdf->text(115, $this->pdf->gety() + 10, 'SHIFT ');
			$this->pdf->text(135, $this->pdf->gety() + 10, ': ' );
			$this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl SPP ');
			$this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
			$this->pdf->text(115, $this->pdf->gety() + 15, 'CT ');
			$this->pdf->text(135, $this->pdf->gety() + 15, ': ' );
			
			$sql1        = "select b.productname, a.qty, c.uomcode, a.description,d.sloccode,d.description as slocdesc,a.startdate,a.enddate,a.cycletime, a.machineid, a.employeeid, e.machinecode, f.fullname
        from productplanfg a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
        left join sloc d on d.slocid = a.slocid
        left join machine e on e.machineid = a.machineid
        left join employee f on f.employeeid = a.employeeid
        where productplanid = " . $row['productplanid'];
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $totalct = 0;
        $totalqty = 0;
        $this->pdf->text(10, $this->pdf->gety() + 20, 'FG');
        $this->pdf->sety($this->pdf->gety() + 25);
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
        'C',
        'C'
        );
        $this->pdf->setwidths(array(
            10,
            50,
            15,
            12,
            18,
            17,
            17,
            20,
            30,
            20
        ));
        $this->pdf->colheader = array(
            'No',
            'Items',
            'Qty',
            'Satuan',
            'Berat',
            'Qty Hasil',
            'Qty Reject',
            'Mesin',
            'Operator',
            'Keterangan'
        );
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array(
            'L',
            'L',
            'R',
            'C',
            'L',
            'L',
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
                Yii::app()->format->formatCurrency($row1['qty']),
                $row1['uomcode'],
                '',
                '',
                '',
                $row1['machinecode'],
                $row1['fullname'],
                ''
            ));
            $totalct += ($row1['cycletime']*$row1['qty']);
            $totalqty += $row1['qty'];
        }
        $this->pdf->setFont('Arial','B',10);
        $this->pdf->row(array(
            '',
            'TOTAL ',
            Yii::app()->format->formatCurrency($totalqty),
            '',
            '',
            '',
            '',
        ));
        $this->pdf->SetFont('Arial','',8);
        
        $sql1        = "select b.productname, sum(a.qty) as qty, c.uomcode, a.description,d.bomversion,
				(select sloccode from sloc d where d.slocid = a.fromslocid) as fromsloccode,
				(select description from sloc d where d.slocid = a.fromslocid) as fromslocdesc,
				(select sloccode from sloc d where d.slocid = a.toslocid) as tosloccode,	
				(select description from sloc d where d.slocid = a.toslocid) as toslocdesc			
        from productplandetail a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join billofmaterial d on d.bomid = a.bomid
        where b.isstock = 1 and productplanid = " . $row['productplanid'] . " 
				group by b.productname,c.uomcode,d.bomversion,fromsloccode,fromslocdesc,tosloccode,toslocdesc ";
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $this->pdf->text(10, $this->pdf->gety() + 10, 'RM');
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
            60,
            20,
            15,
            25,
            25,
            35
        ));
        $this->pdf->colheader = array(
            'No',
            'Items',
            'Qty',
            'Satuan',
            'Gudang Tujuan',
            'Realisasi Qty'
        );
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array(
            'L',
            'L',
            'R',
            'C',
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
                Yii::app()->format->formatCurrency($row1['qty']),
                $row1['uomcode'],
                $row1['fromsloccode'] . ' - ' . $row1['fromslocdesc'],

            ));
            $this->pdf->checkPageBreak(20);
        }
        $this->pdf->text(10, $this->pdf->gety() + 10, 'Proposed By');
        $this->pdf->text(50, $this->pdf->gety() + 10, 'Approved By');
        $this->pdf->text(100, $this->pdf->gety() + 10, 'Produced By');
        $this->pdf->text(150, $this->pdf->gety() + 10, 'Checked By');
        $this->pdf->text(10, $this->pdf->gety() + 30, '__ ');
        $this->pdf->text(50, $this->pdf->gety() + 30, '__');
        $this->pdf->text(100, $this->pdf->gety() + 30, '__');
        $this->pdf->text(150, $this->pdf->gety() + 30, '__');
		}
		$this->pdf->Output();
	}
	public function actionDownpdfhasil()
  {
    parent::actionDownload();
    $sql = "select a.*,b.sono 
      from productplan a
      left join soheader b on b.soheaderid = a.soheaderid	";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('productplan');
    $this->pdf->AddPage('P','A4');
    $this->pdf->AliasNBPages();
    $this->pdf->SetFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No SPP ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(115, $this->pdf->gety() + 5, 'No SO ');
      $this->pdf->text(135, $this->pdf->gety() + 5, ': ' . $row['sono']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl SPP ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $sql1        = "select b.productname, a.qty, c.uomcode, a.description,d.sloccode,d.description as slocdesc,a.startdate,a.enddate,a.cycletime
        from productplanfg a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join sloc d on d.slocid = a.slocid
        where productplanid = " . $row['productplanid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalct = 0;
      $totalqty = 0;
      $this->pdf->text(10, $this->pdf->gety() + 20, 'FG');
      $this->pdf->sety($this->pdf->gety() + 25);
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
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        50,
        20,
        15,
        36,
        17,
        17,
        17,
        25
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang',
        'Tgl Mulai',
        'Tgl Selesai',        
        'Qty Hasil'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L',
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
          $row1['sloccode'] . ' - ' . $row1['slocdesc'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['startdate'])),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['enddate'])),
          $row1['description']
        ));
        $totalct += ($row1['cycletime']*$row1['qty']);
        $totalqty += $row1['qty'];
      }
       $this->pdf->setFont('Arial','B',10);
       $this->pdf->row(array(
          '',
          'TOTAL ',
          Yii::app()->format->formatCurrency($totalqty),
          '',
          '',
          '',
          '',
        ));
        $this->pdf->SetFont('Arial','',8);
        
      $sql1        = "select b.productname, sum(a.qty) as qty, c.uomcode, a.description,d.bomversion,
				(select sloccode from sloc d where d.slocid = a.fromslocid) as fromsloccode,
				(select description from sloc d where d.slocid = a.fromslocid) as fromslocdesc,
				(select sloccode from sloc d where d.slocid = a.toslocid) as tosloccode,	
				(select description from sloc d where d.slocid = a.toslocid) as toslocdesc			
        from productplandetail a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join billofmaterial d on d.bomid = a.bomid
        where b.isstock = 1 and productplanid = " . $row['productplanid'] . " 
				group by b.productname,c.uomcode,d.bomversion,fromsloccode,fromslocdesc,tosloccode,toslocdesc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 10, 'RM');
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
        60,
        20,
        15,
        25,
        25,
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang Tujuan',
        'Realisasi Qty'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
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
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['fromsloccode'] . ' - ' . $row1['fromslocdesc'],
          
        ));
        $this->pdf->checkPageBreak(20);
      }
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 10, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 30, '____ ');
      $this->pdf->text(150, $this->pdf->gety() + 30, '____');
    }
    $this->pdf->Output();
  }
	public function actionDownpdfpendinganqtyspp()
  {
      parent::actionDownload();
      if (isset($_GET['id']) && $_GET['id']!=='') {
        $id          = $_GET['id'];
        $connection  = Yii::app()->db;
          
          $sql = "select productname,uomcode,qtystock,qtypending,qtystock-qtypending as qtyspptersedia,qtyspp,(qtystock-qtypending)-qtyspp as selisih
									from (select b.productname, c.uomcode, sum(a.qty) as qtyspp,
									ifnull((select sum(d.qty - d.qtyres) from productplandetail d join productplanfg e on e.productplanfgid=d.productplanfgid join productplan j on j.productplanid=d.productplanid where j.recordstatus=3 and d.productid=a.productid and d.uomid=a.uomid and d.qty>d.qtyres and e.qty>e.qtyres and j.companyid= f.companyid),0) as qtypending,
									ifnull((select sum(g.qty + g.qtyinprogress) from productstock g join sloc h on h.slocid=g.slocid join plant i on i.plantid=h.plantid where i.companyid=f.companyid and g.productid=a.productid and g.unitofmeasureid=a.uomid),0) as qtystock
									from productplandetail a
									join product b on b.productid=a.productid
									join unitofmeasure c on c.unitofmeasureid=a.uomid
									join productplan f on f.productplanid=a.productplanid
									where a.productplanid = {$id}
									group by a.productid)z";


          $spp = "select t.productplanno, t.productplandate, a.companycode, a.companyname
                  from productplan t
                  join company a on t.companyid = a.companyid
                  where t.productplanid = {$id}";
          $data = $connection->createCommand($spp)->queryRow();

          $this->pdf->title = "Laporan Rekap Qty per SPP : ".$data['productplanno'];
          //$this->pdf->AddPage('P', array(220,140));
          $this->pdf->AddPage('L');
          $this->pdf->setFont('Arial','',11);
          $this->pdf->text(10,12,'Perusahaan   : '.$data['companyname']);
          $this->pdf->text(10,17,'Tanggal SPP : '.date(Yii::app()->params['dateviewfromdb'], strtotime($data['productplandate'])));
          
          $this->pdf->setY($this->pdf->getY()+10);
          
          $query = $connection->createCommand($sql)->queryAll();
          $this->pdf->setFont('Arial','',9);
          $this->pdf->colalign = array('C','L','C','R','R','R','R','R');
          $this->pdf->setwidths(array(10,95,15,32,32,32,32,32));
          $this->pdf->colheader = array('No',getCatalog('productname'),getCatalog('unitofmeasure'),'Qty Stock','Qty Pending','Qty Stock-Pending','Qty SPP','Selisih');
          $this->pdf->RowHeader();
          $this->pdf->coldetailalign = array('C','L','C','R','R','R','R','R');
          $i=1;
          foreach($query as $row){
              $this->pdf->row(array($i,$row['productname'],
                                    $row['uomcode'],
                              Yii::app()->format->formatNumber($row['qtystock']),
                              Yii::app()->format->formatNumber($row['qtypending']),
                              Yii::app()->format->formatNumber($row['qtystock'] - $row['qtypending']),
                              Yii::app()->format->formatNumber($row['qtyspp']),
                              Yii::app()->format->formatNumber($row['qtystock'] - $row['qtypending'] - $row['qtyspp']),
                            ));
              $i++;
          }
          
      }else{
            GetMessage(false, 'chooseone', 1);
      }
      $this->pdf->Output();
  }
  public function actionDownXls()
  {
    $this->menuname = 'suratperintahproduksi';
    parent::actionDownxls();
    $sql = "select a.*,b.sono 
      from productplan a
left join soheader b on b.soheaderid = a.soheaderid	";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 4;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, 'No SPP:')->setCellValueByColumnAndRow(0, $i + 1, 'Tgl SPP:')->setCellValueByColumnAndRow(3, $i, 'No SO:');
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $i, $row['productplanno'])->setCellValueByColumnAndRow(1, $i + 1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])))->setCellValueByColumnAndRow(4, $i, $row['sono']);
      $i += 1;
      $sql1        = "select b.productname, a.qty, c.uomcode, a.description,d.sloccode,d.description as slocdesc,a.startdate,a.enddate
        from productplanfg a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join sloc d on d.slocid = a.slocid
        where productplanid = " . $row['productplanid'];
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 5;
      $no          = 1;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, 'FG');
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 2, 'No')->setCellValueByColumnAndRow(1, $i + 2, 'Items')->setCellValueByColumnAndRow(2, $i + 2, 'Qty')->setCellValueByColumnAndRow(3, $i + 2, 'Unit')->setCellValueByColumnAndRow(4, $i + 2, 'Gudang')->setCellValueByColumnAndRow(5, $i + 2, 'Tgl Mulai')->setCellValueByColumnAndRow(6, $i + 2, 'Tgl Selesai')->setCellValueByColumnAndRow(7, $i + 2, 'Remark');
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 3, $no)->setCellValueByColumnAndRow(1, $i + 3, $row1['productname'])->setCellValueByColumnAndRow(2, $i + 3, $row1['qty'])->setCellValueByColumnAndRow(3, $i + 3, $row1['uomcode'])->setCellValueByColumnAndRow(4, $i + 3, $row1['sloccode'] . '-' . $row1['slocdesc'])->setCellValueByColumnAndRow(5, $i + 3, $row1['startdate'])->setCellValueByColumnAndRow(6, $i + 3, $row1['enddate'])->setCellValueByColumnAndRow(7, $i + 3, $row1['description']);
        $i += 1;
        $no++;
      }
      $sql2        = "select b.productname, sum(a.qty) as qty, c.uomcode, a.description,d.bomversion,
				(select sloccode from sloc d where d.slocid = a.fromslocid) as fromsloccode,
				(select description from sloc d where d.slocid = a.fromslocid) as fromslocdesc,
				(select sloccode from sloc d where d.slocid = a.toslocid) as tosloccode,	
				(select description from sloc d where d.slocid = a.toslocid) as toslocdesc			
        from productplandetail a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join billofmaterial d on d.bomid = a.bomid
        where b.isstock = 1 and productplanid = " . $row['productplanid'] . " 
				group by b.productname,c.uomcode,d.bomversion,fromsloccode,fromslocdesc,tosloccode,toslocdesc ";
      $dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
      $no2         = 1;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 4, 'RM');
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 5, 'No')->setCellValueByColumnAndRow(1, $i + 5, 'Items')->setCellValueByColumnAndRow(2, $i + 5, 'Qty')->setCellValueByColumnAndRow(3, $i + 5, 'Unit')->setCellValueByColumnAndRow(4, $i + 5, 'Gudang Asal')->setCellValueByColumnAndRow(5, $i + 5, 'Gudang Tujuan')->setCellValueByColumnAndRow(6, $i + 5, 'Remark');
      foreach ($dataReader2 as $row2) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 6, $no2)->setCellValueByColumnAndRow(1, $i + 6, $row2['productname'])->setCellValueByColumnAndRow(2, $i + 6, $row2['qty'])->setCellValueByColumnAndRow(3, $i + 6, $row2['uomcode'])->setCellValueByColumnAndRow(4, $i + 6, $row2['fromsloccode'] . '-' . $row2['fromslocdesc'])->setCellValueByColumnAndRow(5, $i + 6, $row2['tosloccode'] . '-' . $row2['toslocdesc'])->setCellValueByColumnAndRow(6, $i + 6, $row2['bomversion'] . '' . $row2['description']);
        $i += 1;
        $no2++;
      }
      $this->getFooterXLS($this->phpExcel);
    }
  }
  public function testactionDownxls()
  {
    parent::actionDownload();
    $sql = "select a.*,b.sono 
      		from productplan a
			left join soheader b on b.soheaderid = a.soheaderid	";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('productplanversion'))->setCellValueByColumnAndRow(1, 1, GetCatalog('productid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('qty'))->setCellValueByColumnAndRow(3, 1, GetCatalog('uomid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('productplandate'))->setCellValueByColumnAndRow(5, 1, GetCatalog('description'))->setCellValueByColumnAndRow(6, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['productplanversion'])->setCellValueByColumnAndRow(1, $i + 1, $row1['productid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['qty'])->setCellValueByColumnAndRow(3, $i + 1, $row1['uomid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['productplandate'])->setCellValueByColumnAndRow(5, $i + 1, $row1['description'])->setCellValueByColumnAndRow(6, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="productplan.xlsx"');
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