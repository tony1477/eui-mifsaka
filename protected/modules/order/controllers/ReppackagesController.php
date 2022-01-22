<?php
class ReppackagesController extends Controller {
  public $menuname = 'reppackages';
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
  public function actionIndexdatacomp()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchdatacomp();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdatacust()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchdatacust();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchDetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdisc()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchDisc();
    else
      $this->renderPartial('index', array());
  }
  public function actionPackagetype()
  {
      $arr = array('All Customer','Pilih Perusahaan','Pilih Customer','Pilih Perusahaan dan Customer');
      $result = array();
      $result['total'] = count($arr);
      for($i=0; $i<count($arr); $i++) {
          $row[] = array(
            'no'=>$i+1,
            'type'=>$arr[$i],
          );
      }
      
      $result=array_merge($result,array('rows'=>$row));
      echo CJSON::encode($result);
  }
  public function actionGetCompany()
  {
      header("Content-Type: application/json");
		$companyname = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.companyid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = 'from company t';
		$where = "
			where (companyname like '%".$companyname."%')
				and t.recordstatus = 1 and companyid in (".getUserObjectValues('company').")
                and t.companyid not in(select companyid from tempcompany where tableid = {$_GET['packageid']})";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.companyid,t.companyname,companycode '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'companycode'=>$data['companycode'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
    }
  public function actionGetCustomer()
  {
      header("Content-Type: application/json");
      $fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
      $fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
      $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'addressbookid';
      $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
      $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
      $rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
      $sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
      $order = isset($_GET['order']) ? strval($_GET['order']) : $order;
      $offset = ($page-1) * $rows;
      $result = array();
      $row = array();
      
      $cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('account a','a.accountid = t.accpiutangid')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('custcategory e','e.custcategoryid = t.custcategoryid')
				->leftjoin('custgrade f','f.custgradeid = t.custgradeid')
				->where("(fullname like :fullname) and iscustomer = 1 and t.recordstatus=1
                          and t.addressbookid not in(select customerid from tempcustomer where tableid = {$_GET['packageid']})",
						array(':fullname'=>'%'.$fullname.'%'))
				->queryScalar();
		
		$result['total'] = $cmd;
      
        $cmd = Yii::app()->db->createCommand()
				->select('t.*,a.accountname,b.areaname,c.categoryname,d.groupname,e.custcategoryname,f.custgradename,g.taxcode,h.paycode')			
				->from('addressbook t')
				->leftjoin('account a','a.accountid = t.accpiutangid')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('custcategory e','e.custcategoryid = t.custcategoryid')
				->leftjoin('custgrade f','f.custgradeid = t.custgradeid')
				->leftjoin('tax g','g.taxid = t.taxid')
				->leftjoin('paymentmethod h','h.paymentmethodid = t.paymentmethodid')
				->where("(fullname like :fullname) and iscustomer = 1 and t.recordstatus=1
                        and t.addressbookid not in(select customerid from tempcustomer where tableid = {$_GET['packageid']})",
						array(':fullname'=>'%'.$fullname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
      
        foreach($cmd as $data) {	
			$row[] = array(
			'addressbookid'=>$data['addressbookid'],
			'fullname'=>$data['fullname'],
			'taxno'=>$data['taxno'],
			'ktpno'=>$data['ktpno'],
			'creditlimit'=>Yii::app()->format->formatNumber($data['creditlimit']),
			'currentlimit'=>Yii::app()->format->formatNumber($data['currentlimit']),
			'isstrictlimit'=>$data['isstrictlimit'],
			'overdue'=>$data['overdue'],
			'accpiutangid'=>$data['accpiutangid'],
			'accpiutangname'=>$data['accountname'],
			'salesareaid'=>$data['salesareaid'],
			'areaname'=>$data['areaname'],
			'pricecategoryid'=>$data['pricecategoryid'],
			'categoryname'=>$data['categoryname'],
			'groupcustomerid'=>$data['groupcustomerid'],
			'groupname'=>$data['groupname'],
			'custcategoryid'=>$data['custcategoryid'],
			'custcategoryname'=>$data['custcategoryname'],
			'custgradeid'=>$data['custgradeid'],
			'custgradename'=>$data['custgradename'],
			'taxid'=>$data['taxid'],
			'taxcode'=>$data['taxcode'],
			'paymentmethodid'=>$data['paymentmethodid'],
			'paycode'=>$data['paycode'],
            'bankaccountno'=>$data['bankaccountno'],
            'bankname'=>$data['bankname'],
            'accountowner'=>$data['accountowner'],
			'recordstatuscustomer'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
  }
  public function actiongetDataPackage()
  {
      $idcm = Yii::app()->db->createCommand("select group_concat(companyid) as companyid, group_concat(companyname) as companyname from tempcompany where tableid = ".$_REQUEST['id'])->queryRow();
      $idcs = Yii::app()->db->createCommand("select group_concat(customerid) as customerid, group_concat(fullname) as fullname from tempcustomer where tableid = ".$_REQUEST['id'])->queryRow();
			echo CJSON::encode(array(
				'companyid' => $idcm['companyid'],
				'companies' => $idcm['companyname'],
				'customerid' => $idcs['customerid'],
				'customers' => $idcs['fullname'],
			));
  }
  public function actionGeneratedetail()
  {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateSOPO(:vid, :vhid, :vcompanyid, :vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_INT);
        $command->bindvalue(':vcreatedby', Yii::app()->user->id, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
      }
    }
    Yii::app()->end();
  }
  public function actionGenerateaddress()
  {
		$sql = "select concat(addressname,ifnull(cityname,'')) 
			from address a 
			join addressbook b on b.addressbookid = a.addressbookid 
			left join city c on c.cityid = a.cityid 
			where b.addressbookid = ".$_POST['id']." 
			limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryScalar();
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateCustDisc(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
      }
      catch (Exception $e) {
        $transaction->rollBack();
      }
      echo CJSON::encode(array(
        'shipto' => $address,
        'billto' => $address
      ));
      Yii::app()->end();
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $packageid    = isset($_POST['packageid']) ? $_POST['packageid'] : '';
    $companyname  = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $docno        = isset($_POST['docno']) ? $_POST['docno'] : '';
    $customer  	  = isset($_POST['customer']) ? $_POST['customer'] : '';
    $packagename  = isset($_POST['packagename']) ? $_POST['packagename'] : '';
    $headernote   = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 't.packageid';
    $order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset       = ($page - 1) * $rows;
    $result       = array();
    $row          = array();
    $connection		= Yii::app()->db;
    $maxstat			= $connection->createCommand("select getwfmaxstatbywfname('apppkg')")->queryScalar();
    
    $from = '
        from packages t
        left join tempcompany a1 on a1.tableid = t.packageid
        left join tempcustomer a2 on a2.tableid = t.packageid 
        left join paymentmethod a3 on a3.paymentmethodid = t.paymentmethodid ';
    $where = "
        where ((a1.companyid in (".getUserObjectWfValues('company','apppkg').")) or (t.customerid is null and t.companyid is null) or (a2.customerid is not null and a1.companyid is null))
		-- and t.recordstatus in (".getUserRecordStatus('listpkg').")
		and (packageid like '%".$packageid."%') and (coalesce(docno,'') like '%".$docno."%') and (coalesce(a2.fullname,'') like '%".$customer."%') 
            and (coalesce(a1.companyname,'') like '%".$companyname."%')
            and (coalesce(t.packagename,'') like '%".$packagename."%') and (t.headernote like '%".$headernote."%') ";
    $sqlcount = ' select count(distinct packageid) as total '.$from.' '.$where;
    $sql = "
			select distinct t.packageid,t.docno,t.packagename,t.docdate,t.startdate,t.enddate,t.headernote,t.recordstatus,t.statusname,t.companyid,t.customerid,
            case 
		when packagetype = 1 then 'All Customer' 
		when packagetype = 2 then 'Untuk Perusahaan'
		when packagetype = 3 then 'Untuk Customer'
		when packagetype = 4 then 'Untuk Perusahaan dan Customer' end as packagetypename, t.packagetype, t.paymentmethodid,a3.paycode
		-- ifnull(t.companyid,'-') as companyid,
		-- ifnull(t.customerid,'-') as customerid 
			".$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$sql = $sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows;
		$cmd = $connection->createCommand($sql)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'packageid' => $data['packageid'],
        'companyid' => $data['companyid'],
        'packagetype'=>$data['packagetype'],
        'packagename' => $data['packagename'],
        'customerid' => $data['customerid'],
        'packagetypename' => $data['packagetypename'],
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'startdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
        'enddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
        'headernote' => $data['headernote'],
        'recordstatus'=> $data['recordstatus'],
        'statusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function searchcombo()
  {
    header("Content-Type: application/json");
		$packageid   = isset($_GET['q']) ? $_GET['q'] : '';
        $docno        	= isset($_GET['q']) ? $_GET['q'] : '';
        $customer  		= isset($_GET['q']) ? $_GET['q'] : '';
		$pocustno     = isset($_GET['q']) ? $_GET['q'] : '';
		$headernote   = isset($_GET['q']) ? $_GET['q'] : '';
		$page         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows         = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort         = isset($_GET['sort']) ? strval($_GET['sort']) : 'packageid';
		$order        = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$recordstatus = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppkg')")->queryScalar();
        $connection		= Yii::app()->db;
		$from = "from 
			(select a.packageid,docno,packagename,startdate,enddate,headernote,a1.paymentmethodid,a1.paycode
    from packages a
    left join paymentmethod a1 on a1.paymentmethodid = a.paymentmethodid
    where packagetype=1 and curdate() >= startdate and curdate() <= enddate and a.recordstatus={$recordstatus}
    and a.docno like '%".(isset($_REQUEST['docno'])?$_REQUEST['docno']:'')."%'
    and a.packagename like '%".(isset($_REQUEST['packagename'])?$_REQUEST['packagename']:'')."%'
    union
    select b.packageid,docno,packagename,startdate,enddate,headernote,b2.paymentmethodid,b2.paycode
    from packages b
    join tempcompany b1 on b1.tableid = b.packageid
    left join paymentmethod b2 on b2.paymentmethodid = b.paymentmethodid
    where b1.companyid = ".(isset($_REQUEST['companyid'])?$_REQUEST['companyid']:'null')." and b.packagetype=2 and curdate() >= startdate and   curdate() <= enddate and b.recordstatus={$recordstatus}
    and b.docno like '%".(isset($_REQUEST['docno'])?$_REQUEST['docno']:'')."%'
    and b.packagename like '%".(isset($_REQUEST['packagename'])?$_REQUEST['packagename']:'')."%'
    union
    select c.packageid,docno,packagename,startdate,enddate,headernote,c2.paymentmethodid,c2.paycode
    from packages c
    join tempcustomer c1 on c1.tableid = c.packageid
    left join paymentmethod c2 on c2.paymentmethodid = c.paymentmethodid
    where c1.customerid = ".(isset($_REQUEST['addressbookid'])?$_REQUEST['addressbookid']:'null')." and c.packagetype=3 and curdate() >= startdate  and curdate() <= enddate and c.recordstatus={$recordstatus}
    and c.docno like '%".(isset($_REQUEST['docno'])?$_REQUEST['docno']:'')."%'
    and c.packagename like '%".(isset($_REQUEST['packagename'])?$_REQUEST['packagename']:'')."%'
    union
    select d.packageid,docno,packagename,startdate,enddate,headernote,d3.paymentmethodid,d3.paycode
    from packages d
    join tempcompany d1 on d1.tableid = d.packageid
    join tempcustomer d2 on d2.tableid = d.packageid
    join paymentmethod d3 on d3.paymentmethodid = d.paymentmethodid
    where d.packagetype=4 and d2.customerid = ".(isset($_REQUEST['addressbookid'])?$_REQUEST['addressbookid']:'null')." and d1.companyid = ".(isset ($_REQUEST['companyid'])?$_REQUEST['companyid']:'null')."
    and curdate() >= startdate and curdate() <= enddate and d.recordstatus={$recordstatus}
    and d.docno like '%".(isset($_REQUEST['docno'])?$_REQUEST['docno']:'')."%'
    and d.packagename like '%".(isset($_REQUEST['packagename'])?$_REQUEST['packagename']:'')."%' ) z";
		$sql = "select *  ".$from;
		$sqlcount = ' select count(1) as total '.$from;
        $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'packageid' => $data['packageid'],
        'docno' => $data['docno'],
        'startdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
        'enddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
        'packagename' => $data['packagename'],
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'headernote' => $data['headernote'],
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'packagedetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->where('packageid = :packageid', array(
      ':packageid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode')->from('packagedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->where('packageid = :packageid', array(
      ':packageid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'packagedetailid' => $data['packagedetailid'],
        'packageid' => $data['packageid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'price' => Yii::app()->format->formatNumber($data['price']),
        'isbonus' => $data['isbonus'],
        'qty' => Yii::app()->format->formatNumber($data['qty'])
      );
    }
    
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchdisc()
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
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'packagediscid';
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
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedisc t')->where('packageid = :packageid', array(
        ':packageid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedisc t')->where('packageid = :packageid', array(
        ':packageid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select()->from('packagedisc t')->where('packageid = :packageid', array(
        ':packageid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('packagedisc t')->where('packageid = :packageid', array(
        ':packageid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'packagediscid' => $data['packagediscid'],
        'packageid' => $data['packageid'],
        'discvalue' => Yii::app()->format->formatNumber($data['discvalue']),
        'discvalue1' => '-'
      );
    }
    $cmd      = Yii::app()->db->createCommand()->selectdistinct('(sum(t.price * t.qty) - gettotalamountdiscpackage(t.packageid)) as amountbefdisc,gettotalamountdiscpackage(t.packageid) as amountafterdisc')->from('packagedetail t')->where('packageid = :packageid', array(
      ':packageid' => $id
    ))->queryRow();
    $footer[] = array(
      'packagediscid' => 'Diskon',
      'discvalue' => Yii::app()->format->formatNumber($cmd['amountbefdisc']),
      'discvalue1' => '-'
    );
    $footer[] = array(
      'packagediscid' => 'Setelah Diskon',
      'discvalue' => $cmd['amountafterdisc'],
      'discvalue1' => Yii::app()->format->formatNumber($cmd['amountafterdisc'])
    );
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchdatacomp()
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
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'tableid';
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
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('tempcompany t')->where('tableid = :packageid', array(
        ':packageid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedisc t')->where('packageid = :packageid', array(
        ':packageid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,(select companycode from company x where x.companyid = t.companyid ) as companycode')->from('tempcompany t')->where('tableid = :packageid', array(
        ':packageid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select()->from('packagedisc t')->where('packageid = :packageid', array(
        ':packageid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'tableid' => $data['tableid'],
        'companyid' => $data['companyid'],
        'companycode' => $data['companycode'],
        'companyname' => $data['companyname']
      );
    }
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchdatacust()
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
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'tableid';
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
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('tempcustomer t')->where('tableid = :packageid', array(
        ':packageid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedisc t')->where('packageid = :packageid', array(
        ':packageid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('tempcustomer t')->where('tableid = :packageid', array(
        ':packageid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('packagedisc t')->where('packageid = :packageid', array(
        ':packageid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'tableid' => $data['tableid'],
        'custoemrid' => $data['customerid'],
        'fullname' => $data['fullname'],
        'areaname' => $data['areaname'],
        'groupname' => $data['groupname'],
        'gradedesc' => $data['gradedesc']
      );
    }
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    
    echo CJSON::encode($result);
  }
  private function ModifyData($connection,$arraydata)
  {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertpackage(:vdocno,:vdocdate,:vcompanyid,:vpoheaderid,:vaddressbookid,:vpocustno,:vemployeeid,:vpaymentmethodid,:vtaxid,:vshipto,:vbillto,:vheadernote,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vdocno', $arraydata[3], PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus', $arraydata[13], PDO::PARAM_STR);
		} else {
			$sql     = 'call UpdatePackage (:vid,:vdocdate,:vpackagename,:vpackagetype,:vcompanyid,:vcustomerid,:vpaymentmethodid,:vstartdate,:venddate,:vheadernote,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
        $command->bindvalue(':vdocdate',$arraydata[1],PDO::PARAM_STR);
        $command->bindvalue(':vpackagename',$arraydata[2],PDO::PARAM_STR);
        $command->bindvalue(':vpackagetype',$arraydata[3],PDO::PARAM_STR);
        $command->bindvalue(':vcompanyid',$arraydata[4],PDO::PARAM_STR);
        $command->bindvalue(':vcustomerid',$arraydata[5],PDO::PARAM_STR);
        $command->bindvalue(':vpaymentmethodid',$arraydata[6],PDO::PARAM_STR);
        $command->bindvalue(':vstartdate',$arraydata[7],PDO::PARAM_STR);
        $command->bindvalue(':venddate',$arraydata[8],PDO::PARAM_STR);
        $command->bindvalue(':vheadernote',$arraydata[9],PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby',Yii::app()->user->id,PDO::PARAM_STR);
        

		$command->execute();
	}
  public function actionUpload()
  {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["Filepackage"]["name"]);
		if (move_uploaded_file($_FILES["Filepackage"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					if ($nourut != '') {
						$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
						$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
						$docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()));
						$docno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$abid = Yii::app()->db->createCommand("select packageid 
							from package 
							where companyid = ".$companyid."
							and docdate = '".$docdate."' 
							and docno = '".$docno."' 					
							")->queryScalar();
						if ($abid == '') {					
							$customer = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
							$customerid = Yii::app()->db->createCommand("select addressbookid 
								from addressbook 
								where fullname = '".$customer."' and iscustomer = 1")->queryScalar();
							$pono = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
							$poheaderid = Yii::app()->db->createCommand("select poheaderid 
								from poheader 
								where companyid = ".$companyid." and pono like '".$pono."'")->queryScalar();
							$pocustno = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
							$totalbefdisc = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$totalaftdisc = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$sales = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$salesid = Yii::app()->db->createCommand("select employeeid from employee where fullname = '".$sales."'")->queryScalar();
							$paymentmethod = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$paymentmethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$paymentmethod."'")->queryScalar();
							$taxcode = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$taxcode."'")->queryScalar();
							$shipto = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$billto = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
							$headernote = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$recordstatus = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$this->ModifyData($connection,array('',$docdate,
								$companyid, $docno, $poheaderid, $customerid, $pocustno, $salesid, $paymentmethodid, $taxid, $shipto, $billto,$headernote,$recordstatus));
							//get id addressbookid
							$abid = Yii::app()->db->createCommand("select packageid 
								from package 
								where companyid = ".$companyid."
								and docdate = '".$docdate."' 
								and docno = '".$docno."' 					
								")->queryScalar();
						}
						if ($abid != '') {
							if ($objWorksheet->getCellByColumnAndRow(16, $row)->getValue() != '') {
								$productname = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
								$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
								$qty = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
								$uomcode = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
								$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
								$sloccode = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
								$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
								$price = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
								$currencyname = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
								$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
								$currencyrate = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
								$delvdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(23, $row)->getValue()));
								$description = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue();
								$this->ModifyDataDetail($connection,array('',$abid,$productid,$qty,$uomid,$slocid,$price,$currencyid,$currencyrate,
									$delvdate,$description));
							}
							if ($objWorksheet->getCellByColumnAndRow(25, $row)->getValue() != '') {
								$discvalue = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue();
								$this->ModifyDataDisc($connection,array('',$abid, $discvalue));
							}					
						}
					}	
				}
				$transaction->commit();
				GetMessage(false, 'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, $e->getMessage());
			}
        }
  }
  public function actionUploadSOPO()
  {
		//parent::actionUploadDoc();
        Yii::import('ext.PHPExcel.XPHPExcel');
		$this->phpExcel = XPHPExcel::createPHPExcel();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FilepackagePO"]["name"]);
		if (move_uploaded_file($_FILES["FilepackagePO"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 1; $row <= $highestRow; ++$row) {
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '{$companycode}'")->queryScalar();
                    $customer = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$addressbookid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '{$customer}'")->queryScalar();
                    $sales = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$employeeid = Yii::app()->db->createCommand("select employeeid from employee where fullname = '{$sales}'")->queryScalar();
                    $shipto = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $disc = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $row+=1;
                    $docdate = date(Yii::app()->params['datetodb'],strtotime($objWorksheet->getCellByColumnAndRow(1, $row)->getValue()));
                    $top = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $paymentmethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '{$top}'")->queryScalar();
                    $tax = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$tax."'")->queryScalar();
                    $billto = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$headernote = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                    //var_dump($companyid);
                    
                    $stmt = Yii::app()->db->createCommand("select getrunno({$companyid},25,'{$docdate}')");
                    $docno = $stmt->queryScalar();
                    
                    $this->ModifyData($connection,array('',$docdate,
								$companyid, $docno, '', $addressbookid, '', $employeeid, $paymentmethodid, $taxid, $shipto, $billto,$headernote,1));
                    
                    $abid = Yii::app()->db->createCommand("select packageid 
							from package 
							where companyid = ".$companyid."
							and docdate = '".$docdate."' 
							and docno = '".$docno."' 					
							")->queryScalar();
                    
                    if ($abid != '')
                    {
                        $row+=3;
                        for ($i = $row; $i <= $highestRow; ++$i)
                        {
                            if ($objWorksheet->getCellByColumnAndRow(1, $i)->getValue() != '')
                            {
                                $productname = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
                                $productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
                                $qty = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
                                $uomcode = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
                                $uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
                                $sloccode = $objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
                                $slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
                                $price = $objWorksheet->getCellByColumnAndRow(5, $i)->getValue();
                                $currencyname = $objWorksheet->getCellByColumnAndRow(6, $i)->getValue();
                                $currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
                                $currencyrate = $objWorksheet->getCellByColumnAndRow(7, $i)->getValue();
                                $delvdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(8, $i)->getValue()));
                                $description = $objWorksheet->getCellByColumnAndRow(9, $i)->getValue();
                                $this->ModifyDataDetail($connection,array('',$abid,$productid,$qty,$uomid,$slocid,$price,$currencyid,$currencyrate,
                                $description,$delvdate));
                                $row++;
                            }
                        }
                    }
                    
                    if ($disc != '')
                    {
                        $exp = explode('+',$disc);
                        for($j=0; $j<count($exp); $j++)
                        {
                            $this->ModifyDataDisc($connection,array('',$abid, $exp[$j]));    
                        }
                    }
				}
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
			$this->ModifyData($connection,array((isset($_POST['packageid'])?$_POST['packageid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),
				$_POST['packagename'],$_POST['packagetype'],$_POST['companyid'],$_POST['customerid'],$_POST['paymentmethodid'], date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])),date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])),$_POST['headernote']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  private function ModifyDataDetail($connection,$arraydata)
  {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertpackagedetail(:vpackageid,:vproductid,:vqty,:vuomid,:vprice,:visbonus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatepackagedetail(:vid,:vpackageid,:vproductid,:vqty,:vuomid,:vprice,:visbonus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vpackageid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vprice', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':visbonus', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveDetail()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['packagedetailid'])?$_POST['packagedetailid']:''),$_POST['packageid'],$_POST['productid'],$_POST['qty'],
            $_POST['unitofmeasureid'],$_POST['price'],$_POST['isbonus']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  private function ModifyDataDisc($connection,$arraydata)
  {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertpackagedisc(:vpackageid,:vdiscvalue,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatepackagedisc(:vid,:vpackageid,:vdiscvalue,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vpackageid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vdiscvalue', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveDisc()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDisc($connection, array((isset($_POST['packagediscid'])?$_POST['packagediscid']:''),$_POST['packageid'],$_POST['discvalue']));
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
        $sql     = 'call DeleteSO(:vid,:vcreatedby)';
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
        $sql     = 'call ApprovePackage(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        //$this->SendNotifWaCustomer($this->menuname,$id);
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
        $sql     = 'call Purgepackage(:vid,:vcreatedby)';
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
        $sql     = 'call Purgepackagedetail(:vid,:vcreatedby)';
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
  public function actionPurgedisc()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepackagedisc(:vid,:vcreatedby)';
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
    $sql = "select a.*
      from packages a
      -- join addressbook b on b.addressbookid = a.addressbookid
		  -- join employee d on d.employeeid = a.employeeid
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		  -- join tax e on e.taxid = a.taxid 
      ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.packageid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Program Paket';
    $this->pdf->AddPage('P', 'A4');
    $this->pdf->AliasNbPages();
    $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
    $this->pdf->SetFont('Tahoma');
    foreach ($dataReader as $row) {
      /*
      if ($row['addressbookid'] != '') {
        $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.lat, a.lng
					from address a
					left join addresstype b on b.addresstypeid = a.addresstypeid
					left join city c on c.cityid = a.cityid
					where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $phone;
        foreach ($dataReader1 as $row1) {
          $phone = $row1['phoneno'];
        }
      }
      */
      $this->pdf->SetFontSize(10);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        25,
        100,
        30,
        60
      ));
      $this->pdf->row(array(
        'Nama Paket',
        ' : '.$row['packagename'],
        'No Paket',
        ' : ' . $row['docno']
      ));
      $this->pdf->row(array(
        'Tanggal Mulai',
        ' : ' . date(Yii::app()->params['dateviewfromdb'],strtotime($row['startdate'])),
        'Tanggal Akhir',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate']))
      ));
      $this->pdf->setY($this->pdf->getY()+5);
      $this->pdf->SetFontSize(8);
      $sql1        = "select a.packageid,c.uomcode,a.qty,a.price as price,(qty * price) as total,b.productname
			from packagedetail a
			left join packages f on f.packageid = a.packageid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
			-- left join currency d on d.currencyid = a.currencyid
			-- left join tax e on e.taxid = f.taxid
			where a.packageid = " . $row['packageid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 0);
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
        90,
        15,
        15,
        getUserObjectValues('pricepkg')== '1' ? 20 : 20,
        30,
        //30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty',
        getUserObjectValues('pricepkg')== '1' ? 'Harga' : '',
        getUserObjectValues('pricepkg')== '1' ? 'Total' : '',
        //'Quota Paket'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'C',
        'L',
        'R',
        'R',
        'R',
        'L'
      );
      $i=1;
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->format->formatNumber($row1['qty']),
          //$row1['itemnote'],
          getUserObjectValues('pricepkg')== '1' ? Yii::app()->format->formatCurrency($row1['price']) : '',
          getUserObjectValues('pricepkg')== '1' ? Yii::app()->format->formatCurrency($row1['total']) : '', 
          //date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate']))
        ));
        $i++;
        $total    = $row1['total'] + $total;
        $totalqty = $row1['qty'] + $totalqty;
      }
      $this->pdf->row(array(
        '',
        'Total',
        '',
        Yii::app()->format->formatNumber($totalqty),
        '',
          getUserObjectValues('pricepkg')== '1' ? Yii::app()->format->formatCurrency($total) : '', 
      ));
      $sql1        = "select a.discvalue
			from packagedisc a
			where a.packageid = " . $row['packageid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $discvalue   = '';
      foreach ($dataReader1 as $row1) {
        if ($discvalue == '') {
          $discvalue = Yii::app()->format->formatNumber($row1['discvalue']);
        } else {
          $discvalue = $discvalue . ' + ' . Yii::app()->format->formatNumber($row1['discvalue']);
        }
      }
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        getUserObjectValues('pricepkg')== '1' ? 'Diskon (%)' : '', 
        getUserObjectValues('pricepkg')== '1' ? $discvalue : '',
      ));
      $totalbefdisc = Yii::app()->db->createCommand('select GetTotalBefDiscPackage('.$row['packageid'].')')->queryScalar();
      $hrgaftdisc = Yii::app()->db->createCommand('select GetTotalAmountDiscPackage('.$row['packageid'].')')->queryScalar();
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        getUserObjectValues('pricepkg')== '1' ? 'Harga Diskon' : '',
        getUserObjectValues('pricepkg')== '1' ? Yii::app()->format->formatNumber($totalbefdisc - $hrgaftdisc) : '', 
      ));
      $bilangan = explode(".", $hrgaftdisc);
      $this->pdf->row(array(
        'Harga Sesudah Diskon',
        Yii::app()->format->formatCurrency($hrgaftdisc) . ' (' . eja($bilangan[0]) . ')'
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));   
			$this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->checkNewPage(10);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety(), 'Pembuat');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownPDF1()
  {
    parent::actionDownload();
    $sql = "select a.companyid, a.packageid,a.docno, b.fullname as customername, a.docdate, c.paymentname, e.taxcode, e.taxvalue,
			a.addressbookid, a.headernote,a.recordstatus,a.shipto,a.billto,d.fullname as salesname
      from package a
      join addressbook b on b.addressbookid = a.addressbookid
		  join employee d on d.employeeid = a.employeeid
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		  join tax e on e.taxid = a.taxid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.packageid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Sales Order';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
    $this->pdf->SetFont('Tahoma');
    foreach ($dataReader as $row) {
      if ($row['addressbookid'] > 0) {
        $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno
					from address a
					left join addresstype b on b.addresstypeid = a.addresstypeid
					left join city c on c.cityid = a.cityid
					where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $phone;
        foreach ($dataReader1 as $row1) {
          $phone = $row1['phoneno'];
        }
      }
      $this->pdf->SetFontSize(8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        100,
        30,
        60
      ));
      $this->pdf->row(array(
        'Customer',
        '',
        'Sales Order No',
        ' : ' . $row['docno']
      ));
      $this->pdf->row(array(
        'Name',
        ' : ' . $row['customername'],
        'SO Date',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate']))
      ));
      $this->pdf->row(array(
        'Phone',
        ' : ' . $phone,
        'Sales',
        ' : ' . $row['salesname']
      ));
      $this->pdf->row(array(
        'Address',
        ' : ' . $row['shipto'],
        'Payment',
        ' : ' . $row['paymentname']
      ));
      $sql1        = "select a.packageid,c.uomcode,a.qty,a.price * a.currencyrate as price,(qty * price * currencyrate) as total,(e.taxvalue * qty * price * currencyrate/ 100) as ppn,b.productname,
			d.symbol,d.i18n,a.itemnote,a.delvdate
			from packagedetail a
			left join package f on f.packageid = a.packageid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
			left join currency d on d.currencyid = a.currencyid
			left join tax e on e.taxid = f.taxid
			where a.packageid = " . $row['packageid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() +0);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        15,
        15,
        110,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'Qty',
        'Units',
        'Description',
        'Item Note',
        'Tgl Kirim'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'R',
        'C',
        'L',
        'L',
        'R',
        'L'
      );
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['productname'],
          $row1['itemnote'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate']))
        ));
        $total    = $row1['total'] + $total;
        $totalqty = $row1['qty'] + $totalqty;
      }
      $this->pdf->row(array(
        Yii::app()->format->formatNumber($totalqty),
        '',
        'Total',
        '',
        ''
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
      );
      $this->pdf->setwidths(array(
        35,
        200,
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
      ));   
			$this->pdf->coldetailalign = array(
        'L',
        'L',
      );
      $this->pdf->row(array(
        'Ship To',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->checkNewPage(10);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select addressbookid,custreqno,quotno,headernote,recordstatus
			from package a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.packageid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('custreqno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('quotno'))->setCellValueByColumnAndRow(8, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(9, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['custreqno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['quotno'])->setCellValueByColumnAndRow(8, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(9, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="package.xlsx"');
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

