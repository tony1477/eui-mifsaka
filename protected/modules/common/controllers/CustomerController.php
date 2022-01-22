<?php
class CustomerController extends Controller {
	public $menuname = 'customer';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexaddress() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchAddress();
		else
			$this->renderPartial('index',array());
	}
    public function actionIndexcustcb() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchCustcb();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcontact() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchcontact();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexdiscount() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->actionSearchdisc();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$addressbookid = isset ($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
		$fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
		$iscustomer = isset ($_POST['iscustomer']) ? $_POST['iscustomer'] : '';
		$isemployee = isset ($_POST['isemployee']) ? $_POST['isemployee'] : '';
		$isvendor = isset ($_POST['isvendor']) ? $_POST['isvendor'] : '';
		$ishospital = isset ($_POST['ishospital']) ? $_POST['ishospital'] : '';
		$taxno = isset ($_POST['taxno']) ? $_POST['taxno'] : '';
		$ktpno = isset ($_POST['ktpno']) ? $_POST['ktpno'] : '';
		$creditlimit = isset ($_POST['creditlimit']) ? $_POST['creditlimit'] : '';
		$isstrictlimit = isset ($_POST['isstrictlimit']) ? $_POST['isstrictlimit'] : '';
		$accpiutangid = isset ($_POST['accpiutangid']) ? $_POST['accpiutangid'] : '';
		$salesareaid = isset ($_POST['salesareaid']) ? $_POST['salesareaid'] : '';
		$areaname = isset ($_POST['areaname']) ? $_POST['areaname'] : '';
		$groupcustomer = isset ($_POST['groupcustomer']) ? $_POST['groupcustomer'] : '';
		$pricecategoryid = isset ($_POST['pricecategoryid']) ? $_POST['pricecategoryid'] : '';
		$bankaccountno = isset ($_POST['bankaccountno']) ? $_POST['bankaccountno'] : '';
		$bankname = isset ($_POST['bankname']) ? $_POST['bankname'] : '';
		$accountowner = isset ($_POST['accountowner']) ? $_POST['accountowner'] : '';
		$addressbookid = isset ($_GET['q']) ? $_GET['q'] : $addressbookid;
		$fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
		$iscustomer = isset ($_GET['q']) ? $_GET['q'] : $iscustomer;
		$isemployee = isset ($_GET['q']) ? $_GET['q'] : $isemployee;
		$isvendor = isset ($_GET['q']) ? $_GET['q'] : $isvendor;
		$ishospital = isset ($_GET['q']) ? $_GET['q'] : $ishospital;
		$taxno = isset ($_GET['q']) ? $_GET['q'] : $taxno;
		$ktpno = isset ($_GET['q']) ? $_GET['q'] : $ktpno;
		$creditlimit = isset ($_GET['q']) ? $_GET['q'] : $creditlimit;
		$isstrictlimit = isset ($_GET['q']) ? $_GET['q'] : $isstrictlimit;
		$accpiutangid = isset ($_GET['q']) ? $_GET['q'] : $accpiutangid;
		$salesareaid = isset ($_GET['q']) ? $_GET['q'] : $salesareaid;
		$areaname = isset ($_GET['q']) ? $_GET['q'] : $areaname;
		$groupcustomer = isset ($_GET['q']) ? $_GET['q'] : $groupcustomer;
		$pricecategoryid = isset ($_GET['q']) ? $_GET['q'] : $pricecategoryid;
		$bankaccountno = isset ($_GET['q']) ? $_GET['q'] : $bankaccountno;
		$bankname = isset ($_GET['q']) ? $_GET['q'] : $bankname;
		$accountowner = isset ($_GET['q']) ? $_GET['q'] : $accountowner;
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
		
		$companyid       = isset($_GET['companyid']) ? $_GET['companyid'] : '';
		if (isset($_GET['company'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('addressaccount t1','t1.addressbookid = t.addressbookid')
				->leftjoin('account a','a.accountid = t.accpiutangid')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('custcategory e','e.custcategoryid = t.custcategoryid')
				->leftjoin('custgrade f','f.custgradeid = t.custgradeid')
				->leftjoin('tax g','g.taxid = t.taxid')
				->leftjoin('paymentmethod h','h.paymentmethodid = t.paymentmethodid')
				->where("(fullname like :fullname) and t1.recordstatus = 1 and iscustomer = 1 and t.recordstatus=1 and t1.companyid=".$companyid,
						array(':fullname'=>'%'.$fullname.'%'))
				->queryScalar();
		}
		else
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('account a','a.accountid = t.accpiutangid')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('custcategory e','e.custcategoryid = t.custcategoryid')
				->leftjoin('custgrade f','f.custgradeid = t.custgradeid')
				->where("((coalesce(t.addressbookid,'') like :addressbookid) and (coalesce(t.fullname,'') like :fullname) and (coalesce(t.bankname,'') like :bankname) and (coalesce(t.accountowner,'') like :accountowner) and (coalesce(b.areaname,'') like :areaname) and (coalesce(d.groupname,'') like :groupcustomer)) and iscustomer = 1",
						array(':addressbookid'=>'%'.$addressbookid.'%',
									':fullname'=>'%'.$fullname.'%',
									':bankname'=>'%'.$bankname.'%',
									':accountowner'=>'%'.$accountowner.'%',
									':areaname'=>'%'.$areaname.'%',
									':groupcustomer'=>'%'.$groupcustomer.'%',
						))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->leftjoin('account a','a.accountid = t.accpiutangid')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('custcategory e','e.custcategoryid = t.custcategoryid')
				->leftjoin('custgrade f','f.custgradeid = t.custgradeid')
				->where('(fullname like :fullname) and iscustomer = 1 and t.recordstatus=1',
						array(':fullname'=>'%'.$fullname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['company'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.accountname,b.areaname,c.categoryname,d.groupname,e.custcategoryname,f.custgradename,g.taxcode,h.paycode')			
				->from('addressbook t')
				->leftjoin('addressaccount t1','t1.addressbookid = t.addressbookid')
				->leftjoin('account a','a.accountid = t.accpiutangid')
				->leftjoin('salesarea b','b.salesareaid = t.salesareaid')
				->leftjoin('pricecategory c','c.pricecategoryid = t.pricecategoryid')
				->leftjoin('groupcustomer d','d.groupcustomerid = t.groupcustomerid')
				->leftjoin('custcategory e','e.custcategoryid = t.custcategoryid')
				->leftjoin('custgrade f','f.custgradeid = t.custgradeid')
				->leftjoin('tax g','g.taxid = t.taxid')
				->leftjoin('paymentmethod h','h.paymentmethodid = t.paymentmethodid')
				->where("(fullname like :fullname) and t1.recordstatus = 1 and iscustomer = 1 and t.recordstatus=1 and t1.companyid=".$companyid,
						array(':fullname'=>'%'.$fullname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		if (!isset($_GET['combo'])) {
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
				->where("((coalesce(t.addressbookid,'') like :addressbookid) and (coalesce(t.fullname,'') like :fullname) and (coalesce(t.bankname,'') like :bankname) and (coalesce(t.accountowner,'') like :accountowner) and (coalesce(b.areaname,'') like :areaname) and (coalesce(d.groupname,'') like :groupcustomer)) and iscustomer = 1",
						array(':addressbookid'=>'%'.$addressbookid.'%',
									':fullname'=>'%'.$fullname.'%',
									':bankname'=>'%'.$bankname.'%',
									':accountowner'=>'%'.$accountowner.'%',
									':areaname'=>'%'.$areaname.'%',
									':groupcustomer'=>'%'.$groupcustomer.'%',
						))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else 	{
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
				->where('(fullname like :fullname) and iscustomer = 1 and t.recordstatus=1',
						array(':fullname'=>'%'.$fullname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
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
		return CJSON::encode($result);
	}
	public function actionSearchAddress() {
		header("Content-Type: application/json");
		$id = 0;	
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else 
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addressid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('address t')
				->join('addresstype b','b.addresstypeid = t.addresstypeid')
				->join('city c','c.cityid = t.cityid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,b.addresstypename,c.cityname')			
				->from('address t')
				->join('addresstype b','b.addresstypeid = t.addresstypeid')
				->join('city c','c.cityid = t.cityid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'addressid'=>$data['addressid'],
			'addressbookid'=>$data['addressbookid'],
			'addressname'=>$data['addressname'],
			'addresstypeid'=>$data['addresstypeid'],
			'addresstypename'=>$data['addresstypename'],
			'rt'=>$data['rt'],
			'rw'=>$data['rw'],
			'cityid'=>$data['cityid'],
			'cityname'=>$data['cityname'],
			'phoneno'=>$data['phoneno'],
			'faxno'=>$data['faxno'],
			'lat'=>$data['lat'],
			'lng'=>$data['lng']
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
    public function actionSearchcustcb() {
		header("Content-Type: application/json");
		$id=0;	
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
        $fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
        $fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addressbookid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('addressbook t')
				->where('(fullname like :fullname) and iscustomer = 1 and t.recordstatus=1',
						array(':fullname'=>'%'.$fullname.'%'))
				->queryScalar();
		$result['total'] = $cmd;		
		$cmd = Yii::app()->db->createCommand()
				->select('t.addressbookid as customerid, t.fullname as customername')			
				->from('addressbook t')
				->where('(fullname like :fullname) and iscustomer = 1 and t.recordstatus=1',
						array(':fullname'=>'%'.$fullname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'customerid'=>$data['customerid'],
			'customername'=>$data['customername'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionSearchcontact() {
		header("Content-Type: application/json");
		$id=0;	
		if (isset($_POST['id']))
		{
			$id = $_POST['id'];
		}
		else
		if (isset($_GET['id']))
		{
			$id = $_GET['id'];
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addresscontactid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('addresscontact t')
				->leftjoin('contacttype b','b.contacttypeid = t.contacttypeid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;		
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,b.contacttypename')			
				->from('addresscontact t')
				->leftjoin('contacttype b','b.contacttypeid = t.contacttypeid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
			'addresscontactid'=>$data['addresscontactid'],
			'addressbookid'=>$data['addressbookid'],
			'contacttypeid'=>$data['contacttypeid'],
			'contacttypename'=>$data['contacttypename'],
			'addresscontactname'=>$data['addresscontactname'],
			'phoneno'=>$data['phoneno'],
			'mobilephone'=>$data['mobilephone'],
			'wanumber'=>$data['wanumber'],
			'telegramid'=>$data['telegramid'],
			'emailaddress'=>$data['emailaddress']
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actionSearchdisc() {
		header("Content-Type: application/json");
		$id=0;	
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		else
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.custdiscid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
		$offset = ($page-1) * $rows;
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('custdisc t')
                ->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*, a.description, b.paymentmethodid as sopaymethodid, c.paymentmethodid as realpaymethodid, b.paycode as sopaycode, c.paycode as realpaycode')			
				->from('custdisc t')
                ->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
                ->leftjoin('paymentmethod b','b.paymentmethodid = t.sopaymethodid')
                ->leftjoin('paymentmethod c','c.paymentmethodid = t.realpaymethodid')
				->where('addressbookid = :abid',
						array(':abid'=>$id))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'custdiscid'=>$data['custdiscid'],
				'addressbookid'=>$data['addressbookid'],
				'materialtypeid'=>$data['materialtypeid'],
				'description'=>$data['description'],
				'discvalue'=>$data['discvalue'],
				'sopaycode'=>$data['sopaycode'],
				'realpaycode'=>$data['realpaycode'],
				'sopaymethodid'=>$data['sopaymethodid'],
				'realpaymethodid'=>$data['realpaymethodid'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));;
		echo CJSON::encode($result);
	}
	public function actiongetdata() {
		if (!isset($_GET['id']))
		{
			$dadate              = new DateTime('now');
			$sql = "insert into addressbook (iscustomer,recordstatus) values (1,1)";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'addressbookid' => $id
			));
		}
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call InsertCustomer(:vfullname,:vtaxno,:vktpno,:vcreditlimit,:visstrictlimit,:vsalesareaid,:vpricecategoryid,:vgroupcustomerid,:vcustcategoryid,:vcustgradeid,:vbankname,:vbankaccountno,:vaccountowner,:voverdue,:vtaxid,:vpaymentmethodid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call UpdateCustomer(:vid,:vfullname,:vtaxno,:vktpno,:vcreditlimit,:visstrictlimit,:vsalesareaid,:vpricecategoryid,:vgroupcustomerid,:vcustcategoryid,:vcustgradeid,:vbankname,:vbankaccountno,:vaccountowner,:voverdue,:vtaxid,:vpaymentmethodid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vfullname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vtaxno',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vktpno',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreditlimit',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':visstrictlimit',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vsalesareaid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vpricecategoryid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vgroupcustomerid',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vcustcategoryid',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vcustgradeid',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vbankname',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vbankaccountno',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vaccountowner',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':voverdue',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vtaxid',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[17],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();		
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-customer"]["name"]);
		if (move_uploaded_file($_FILES["file-customer"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$abid = '';$nourut = '';
				for ($row = 2; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$fullname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					if ($abid == '') {					
						$taxno = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
						$ktpno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$kreditlimit = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$strictlimit = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$overdue = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$salesareaname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
						$salesareaid = Yii::app()->db->createCommand("select salesareaid from salesarea where areaname = '".$salesareaname."'")->queryScalar();
						$pricecategoryname = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
						$pricecategoryid = Yii::app()->db->createCommand("select pricecategoryid from pricecategory where categoryname = '".$pricecategoryname."'")->queryScalar();
						$groupcustomername = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
						$groupcustomerid = Yii::app()->db->createCommand("select groupcustomerid from groupcustomer where groupname = '".$groupcustomername."'")->queryScalar();
						$bankaccountno = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
						$bankname = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
						$accountowner = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
						$this->ModifyData($connection,array('',$fullname,$taxno,$ktpno,$kreditlimit,$strictlimit,$overdue,$salesareaid,$pricecategoryid,$groupcustomerid, 
							$bankaccountno,$bankname,$accountowner,$recordstatus));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(14, $row)->getValue() != '') {
							$addresstypename = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$addresstypeid = Yii::app()->db->createCommand("select addresstypeid from addresstype where addresstypename = '".$addresstypename."'")->queryScalar();
							$addressname = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$rt = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
							$rw = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
							$cityname = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
							$cityid = Yii::app()->db->createCommand("select cityid from city where cityname = '".$cityname."'")->queryScalar();
							$phoneno = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
							$faxno = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
							$lat = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
							$lng = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
							$this->ModifyDataAddress($connection,array('',$abid,$addresstypeid,$addressname,$rt,$rw,$cityid,$phoneno,$faxno,$lat,$lng));
						}
						if ($objWorksheet->getCellByColumnAndRow(23, $row)->getValue() != '') {
							$contacttypename = $objWorksheet->getCellByColumnAndRow(23, $row)->getValue();
							$contacttypeid = Yii::app()->db->createCommand("select contacttypeid from contacttype where contacttypename = '".$contacttypename."'")->queryScalar();
							$contactname = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue();
							$contactph = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue();
							$contacthp = $objWorksheet->getCellByColumnAndRow(26, $row)->getValue();
							$contactemail = $objWorksheet->getCellByColumnAndRow(27, $row)->getValue();
							$this->ModifyDataContact($connection,array('',$abid, $contacttypeid,$contactname,$contacthp,$contactph,$contactemail));
						}
						if ($objWorksheet->getCellByColumnAndRow(28, $row)->getValue() != '') {
							$discvalue = $objWorksheet->getCellByColumnAndRow(28, $row)->getValue();
							$this->ModifyDataDisc($connection,array('',$abid,$discvalue));
						}
					}
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
			}
    }
	}
    public function actionUploadcustomerinfo() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file2-customer"]["name"]);
		if (move_uploaded_file($_FILES["file2-customer"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$abid = '';$nourut = '';
				for ($row = 3; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                    if($nourut == '') {
                        $nourut = '';
                    }
					$fullname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$abid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."'")->queryScalar();
					if ($abid != '') {					
						$materialtype = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
						$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where description = '".$materialtype."'")->queryScalar();
						$discvalue = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$sopaymethod = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$sopaymethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$sopaymethod."'")->queryScalar();
                        $realpaymethod = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$realpaymethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$realpaymethod."'")->queryScalar();
						$this->ModifyDataDisc($connection,array($nourut,$abid,$materialtypeid,$discvalue,$sopaymethodid,$realpaymethodid));
						//get id addressbookid
					}
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
			}
    }
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['addressbookid'])?$_POST['addressbookid']:''),$_POST['fullname'],$_POST['taxno'],
				$_POST['ktpno'],$_POST['creditlimit'],
				isset($_POST['isstrictlimit'])?($_POST['isstrictlimit']=="on")?1:0:0,$_POST['salesareaid'],
				$_POST['pricecategoryid'],$_POST['groupcustomerid'],
				$_POST['custcategoryid'],$_POST['custgradeid'],
				$_POST['bankname'],$_POST['bankaccountno'],
				$_POST['accountowner'],$_POST['overdue'],
				$_POST['taxid'],$_POST['paymentmethodid'],
				isset($_POST['recordstatuscustomer'])?($_POST['recordstatuscustomer']=="on")?1:0:0));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	private function ModifyDataAddress($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddress(:vaddressbookid,:vaddresstypeid,:vaddressname,:vrt,:vrw,:vcityid,:vphoneno,:vfaxno,:vlat,:vlng,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateaddress(:vid,:vaddressbookid,:vaddresstypeid,:vaddressname,:vrt,:vrw,:vcityid,:vphoneno,:vfaxno,:vlat,:vlng,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vaddresstypeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaddressname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrt',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrw',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcityid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vfaxno',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vlat',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vlng',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionsaveaddress() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataAddress($connection,array((isset($_POST['addressid'])?$_POST['addressid']:''),$_POST['addressbookid'],$_POST['addresstypeid'],$_POST['addressname'],$_POST['rt'],
				$_POST['rw'],$_POST['cityid'],$_POST['phoneno'],$_POST['faxno'],$_POST['lat'],$_POST['lng']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	private function ModifyDataContact($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddresscontact(:vaddressbookid,:vcontacttypeid,:vaddresscontactname,:vphoneno,:vmobilephone,:vwanumber,:vtelegramid,:vemailaddress,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateaddresscontact(:vid,:vaddressbookid,:vcontacttypeid,:vaddresscontactname,:vphoneno,:vmobilephone,:vwanumber,:vtelegramid,:vemailaddress,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcontacttypeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaddresscontactname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmobilephone',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vwanumber',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vtelegramid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vemailaddress',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionsavecontact() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			$this->ModifyDataContact($connection,array((isset($_POST['addresscontactid'])?$_POST['addresscontactid']:''),$_POST['addressbookid'],$_POST['contacttypeid'],
				$_POST['addresscontactname'],$_POST['mobilephone'],$_POST['phoneno'],$_POST['wanumber'],$_POST['telegramid'],$_POST['emailaddress']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
    private function ModifyDataDisc($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcustomerdisc(:vaddressbookid,:vmaterialtypeid,:vdiscvalue,:vsopaymethodid,:vrealpaymethodid,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecustomerdisc(:vid,:vaddressbookid,:vmaterialtypeid,:vdiscvalue,:vsopaymethodid,:vrealpaymethodid,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialtypeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdiscvalue',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vsopaymethodid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrealpaymethodid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}		
	public function actionsavedisc() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyDataDisc($connection,array((isset($_POST['custdiscid'])?$_POST['custdiscid']:''),$_POST['addressbookid'],$_POST['materialtypeid'],$_POST['discvalue'],$_POST['sopaymethodid'],$_POST['realpaymethodid']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecustomer(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionPurgeaddress() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeaddress(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionPurgecontact() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecontact(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionPurgedisc() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgedisc(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select addressbookid,
						case when fullname is null then '-' else fullname end as fullname,
						case when taxno is null then '-' else taxno end as taxno,
						creditlimit,currentlimit,overdue,
						case when isstrictlimit = 1 then 'Yes' else 'No' end as isstrictlimit,
						case when areaname is null then '-' else areaname end as areaname,
						case when categoryname is null then '-' else categoryname end as categoryname,
						case when accountname is null then '-' else accountname end as accountname,
						case when bankaccountno is null then '-' else bankaccountno end as bankaccountno,
						case when bankname is null then '-' else bankname end as bankname,
						case when accountowner is null then '-' else accountowner end as accountowner,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus,groupname
						from (select t.addressbookid,t.fullname,t.taxno,t.creditlimit,t.currentlimit,t.overdue,t.isstrictlimit,
						b.areaname,c.categoryname,a.accountname,t.bankaccountno,t.bankname,t.accountowner,t.recordstatus, d.groupname
						from addressbook t
						left join account a on a.accountid = t.accpiutangid
						left join salesarea b on b.salesareaid = t.salesareaid
						left join pricecategory c on c.pricecategoryid = t.pricecategoryid
						left join groupcustomer d on d.groupcustomerid = t.groupcustomerid
						left join custcategory e on e.custcategoryid = t.custcategoryid
						left join custgrade f on f.custgradeid = t.custgradeid
						where t.iscustomer = 1 ) z ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$bankname = filter_input(INPUT_GET,'bankname');
		$accountowner = filter_input(INPUT_GET,'accountowner');
		$areaname = filter_input(INPUT_GET,'areaname');
		$groupcustomer = filter_input(INPUT_GET,'groupcustomer');
		$sql .= " where coalesce(addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(fullname,'') like '%".$fullname."%'
			and coalesce(bankname,'') like '%".$bankname."%'
			and coalesce(accountowner,'') like '%".$accountowner."%'
			and coalesce(areaname,'') like '%".$areaname."%'
			and coalesce(groupname,'') like '%".$groupcustomer."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('customer');
		$this->pdf->AddPage('P',array(500,160));
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('addressbookid'),
																	GetCatalog('fullname'),
																	GetCatalog('taxno'),
																	GetCatalog('creditlimit'),
																	GetCatalog('currentlimit'),
																	GetCatalog('overdue'),
																	GetCatalog('isstrictlimit'),
																	GetCatalog('areaname'),
																	GetCatalog('categoryname'),
																	GetCatalog('accountname'),
																	GetCatalog('bankaccountno'),
																	GetCatalog('bankname'),
																	GetCatalog('accountowner'),																	
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(10,60,32,25,25,25,25,90,20,60,27,25,30,15));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',8);
		$this->pdf->coldetailalign = array('L','L','L','R','R','L','L','L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['addressbookid'],$row1['fullname'],$row1['taxno'],Yii::app()->format->formatNumber($row1['creditlimit']),
														Yii::app()->format->formatNumber($row1['currentlimit']),$row1['overdue'],$row1['isstrictlimit'],$row1['areaname'],
														$row1['categoryname'],$row1['accountname'],$row1['bankaccountno'],$row1['bankname'],
														$row1['accountowner'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
    public function actionDownPDF1() {
	  parent::actionDownload();
	  $sql = "select t.addressbookid, t.fullname, a.custdiscid,a.materialtypeid, a.discvalue, a.sopaymethodid, a.realpaymethodid,b.`description`, a.custdiscid,
              e.paycode as sopaycode, f.paycode as realpaycode
						from addressbook t
						lef join custdisc a on a.addressbookid = t.addressbookid
                        left join materialtype b on b.materialtypeid = a.materialtypeid
						left join salesarea c on c.salesareaid = t.salesareaid
						left join groupcustomer d on d.groupcustomerid = t.groupcustomerid
                        left join paymentmethod e on e.paymentmethodid = a.sopaymethodid
                        left join paymentmethod f on f.paymentmethodid = a.realpaymethodid
						where t.iscustomer = 1 ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$bankname = filter_input(INPUT_GET,'bankname');
		$accountowner = filter_input(INPUT_GET,'accountowner');
		$areaname = filter_input(INPUT_GET,'areaname');
        $groupcustomer = filter_input(INPUT_GET,'groupcustomer');
		$sql .= " and coalesce(t.addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(fullname,'') like '%".$fullname."%'
			and coalesce(bankname,'') like '%".$bankname."%'
			and coalesce(accountowner,'') like '%".$accountowner."%'
			and coalesce(areaname,'') like '%".$areaname."%'
            and coalesce(groupname,'') like '%".$groupcustomer."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and t.addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('customer');
		$this->pdf->AddPage('P','A4');
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('ID'),
                                      GetCatalog('fullname'),
                                      GetCatalog('materialtype'),
                                      GetCatalog('discvalue'),
                                      GetCatalog('topso'),
                                      GetCatalog('topreal'));
		$this->pdf->setwidths(array(10,60,32,25,25,25));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',8);
		$this->pdf->coldetailalign = array('L','L','L','R','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['custdiscid'],$row1['fullname'],$row1['description'],$row1['discvalue'],$row1['sopaycode'],$row1['realpaycode']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='customer';
		parent::actionDownxls();
		$sql = "select addressbookid,
						case when fullname is null then '-' else fullname end as fullname,
						case when taxno is null then '-' else taxno end as taxno,
						creditlimit,currentlimit,overdue,
						case when isstrictlimit = 1 then 'Yes' else 'No' end as isstrictlimit,
						case when areaname is null then '-' else areaname end as areaname,
						case when categoryname is null then '-' else categoryname end as categoryname,
						case when accountname is null then '-' else accountname end as accountname,
						case when bankaccountno is null then '-' else bankaccountno end as bankaccountno,
						case when bankname is null then '-' else bankname end as bankname,
						case when accountowner is null then '-' else accountowner end as accountowner,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus,mobilephone,addresscontactname,groupname
						from (select t.addressbookid,t.fullname,t.taxno,t.creditlimit,t.currentlimit,t.overdue,t.isstrictlimit,
						b.areaname,c.categoryname,a.accountname,t.bankaccountno,t.bankname,t.accountowner,t.recordstatus,(select g.mobilephone from addresscontact g where g.addressbookid = t.addressbookid Limit 1) as mobilephone,(select g.addresscontactname from addresscontact g where g.addressbookid = t.addressbookid Limit 1) as addresscontactname,d.groupname
						from addressbook t
						left join account a on a.accountid = t.accpiutangid
						left join salesarea b on b.salesareaid = t.salesareaid
						left join pricecategory c on c.pricecategoryid = t.pricecategoryid
						left join groupcustomer d on d.groupcustomerid = t.groupcustomerid
						left join custcategory e on e.custcategoryid = t.custcategoryid
						left join custgrade f on f.custgradeid = t.custgradeid
						where t.iscustomer = 1 ) z ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$bankname = filter_input(INPUT_GET,'bankname');
		$accountowner = filter_input(INPUT_GET,'accountowner');
		$areaname = filter_input(INPUT_GET,'areaname');
        $groupcustomer = filter_input(INPUT_GET,'groupcustomer');
		$sql .= " where coalesce(addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(fullname,'') like '%".$fullname."%'
			and coalesce(bankname,'') like '%".$bankname."%'
			and coalesce(accountowner,'') like '%".$accountowner."%'
			and coalesce(areaname,'') like '%".$areaname."%'
            and coalesce(groupname,'') like '%".$groupcustomer."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['addressbookid'])
				->setCellValueByColumnAndRow(1,$i,$row1['fullname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['taxno'])
				->setCellValueByColumnAndRow(3,$i,$row1['creditlimit'])
				->setCellValueByColumnAndRow(4,$i,$row1['currentlimit'])
				->setCellValueByColumnAndRow(5,$i,$row1['overdue'])
				->setCellValueByColumnAndRow(6,$i,$row1['isstrictlimit'])
				->setCellValueByColumnAndRow(7,$i,$row1['areaname'])
				->setCellValueByColumnAndRow(8,$i,$row1['categoryname'])
				->setCellValueByColumnAndRow(9,$i,$row1['accountname'])
				->setCellValueByColumnAndRow(10,$i,$row1['bankaccountno'])
				->setCellValueByColumnAndRow(11,$i,$row1['bankname'])
				->setCellValueByColumnAndRow(12,$i,$row1['accountowner'])
				->setCellValueByColumnAndRow(13,$i,$row1['recordstatus'])
				->setCellValueByColumnAndRow(14,$i,$row1['addresscontactname'])
				->setCellValueByColumnAndRow(15,$i,$row1['mobilephone']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
    public function actionDownxls1() {
		$this->menuname='customerinfo';
		parent::actionDownxls();
		 $sql = "select t.addressbookid, t.fullname, a.custdiscid,a.materialtypeid, a.discvalue, a.sopaymethodid, a.realpaymethodid,b.`description`, a.custdiscid,
              e.paycode as sopaycode, f.paycode as realpaycode
						from addressbook t
						left join custdisc a on a.addressbookid = t.addressbookid
                        left join materialtype b on b.materialtypeid = a.materialtypeid
						left join salesarea c on c.salesareaid = t.salesareaid
						left join groupcustomer d on d.groupcustomerid = t.groupcustomerid
                        left join paymentmethod e on e.paymentmethodid = a.sopaymethodid
                        left join paymentmethod f on f.paymentmethodid = a.realpaymethodid
						where t.iscustomer = 1 ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$bankname = filter_input(INPUT_GET,'bankname');
		$accountowner = filter_input(INPUT_GET,'accountowner');
		$areaname = filter_input(INPUT_GET,'areaname');
        $groupcustomer = filter_input(INPUT_GET,'groupcustomer');
		$sql .= " and coalesce(t.addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(fullname,'') like '%".$fullname."%'
			and coalesce(bankname,'') like '%".$bankname."%'
			and coalesce(accountowner,'') like '%".$accountowner."%'
			and coalesce(areaname,'') like '%".$areaname."%'
            and coalesce(groupname,'') like '%".$groupcustomer."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and t.addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['custdiscid'])
				->setCellValueByColumnAndRow(1,$i,$row1['fullname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['description'])
				->setCellValueByColumnAndRow(3,$i,$row1['discvalue'])
				->setCellValueByColumnAndRow(4,$i,$row1['sopaycode'])
				->setCellValueByColumnAndRow(5,$i,$row1['realpaycode']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}