<?php
class PodirectController extends Controller {
  public $menuname = 'podirect';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexpoplant() {
    if (isset($_GET['grid']))
      echo $this->searchpoplant();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into poheader (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inspo').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'poheaderid' => $id
			));
    }
  }
  public function actionGeneratedata() {
    $productid    = '';
    $qty          = '';
    $price        = '';
    $currencyid   = '';
    $currencyrate = '';
    $cmd          = Yii::app()->db->createCommand()->select('a.productid,sum(a.poqty),a.netprice,a.currencyid,a.ratevalue')->from('podetail t')->where("t.poheaderid = '" . $_POST['poheaderid'] . "' and t.productid = '" . $_POST['productid'] . "'")->limit(1)->queryRow();
    $productid    = $cmd['productid'];
    $qty          = $cmd['poqty'];
    $price        = $cmd['netprice'];
    $currencyid   = $cmd['currencyid'];
    $currencyrate = $cmd['ratevalue'];
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'status' => 'success',
        'productid' => $productid,
        'qty' => $qty,
        'price' => $price,
        'currencyid' => $currencyid,
        'currencyrate' => $currencyrate
      ));
      Yii::app()->end();
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $docdate           = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $addressbookid     = isset($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
    $headernote        = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $pono              = isset($_POST['pono']) ? $_POST['pono'] : '';
    $paymentmethodid   = isset($_POST['paymentmethodid']) ? $_POST['paymentmethodid'] : '';
    $companyid         = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $docdate           = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $addressbookid     = isset($_GET['q']) ? $_GET['q'] : $addressbookid;
    $headernote        = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $pono              = isset($_GET['q']) ? $_GET['q'] : $pono;
    $paymentmethodid   = isset($_GET['q']) ? $_GET['q'] : $paymentmethodid;
    $companyid         = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $page              = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows              = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort              = isset($_POST['sort']) ? strval($_POST['sort']) : 'poheaderid';
    $order             = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset            = ($page - 1) * $rows;
    $page              = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows              = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort              = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order             = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset            = ($page - 1) * $rows;
    $result            = array();
    $row               = array();
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')->where("
      ((coalesce(docdate,'') like :docdate) and 
				(coalesce(pono,'') like :pono) and 
				(coalesce(b.fullname,'') like :addressbookid) and 
				(coalesce(c.paycode,'') like :paymentmethodid) and 
				(coalesce(d.companyname,'') like :companyid))", array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':addressbookid' => '%' . $addressbookid . '%',
        ':paymentmethodid' => '%' . $paymentmethodid . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->queryScalar();
    } else if (isset($_GET['grpo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')->where("((docdate like :docdate) or 
                                    	(pono like :pono) or 
                                    	(b.fullname like :addressbookid) or 
                                    	(c.paycode like :paymentmethodid) or 
                                    	(d.companyname like :companyid))
					and t.recordstatus in (".getUserRecordStatus('listpo').")
					and t.companyid in (".getUserObjectValues('company').")
				and t.poheaderid in (select z.poheaderid from podetail z where poqty > qtyres and z.slocid in (".getUserObjectValues('sloc').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':addressbookid' => '%' . $addressbookid . '%',
        ':paymentmethodid' => '%' . $paymentmethodid . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->queryScalar();
    } else if (isset($_GET['invpo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')->where("
      ((coalesce(docdate,'') like :docdate) or 
				(coalesce(pono,'') like :pono) or 
				(coalesce(b.fullname,'') like :addressbookid) or 
				(coalesce(c.paycode,'') like :paymentmethodid) or 
				(coalesce(d.companyname,'') like :companyid))
			and d.companyid = '".$_GET['companyid']."'
					and t.recordstatus in (".getUserRecordStatus('listpo').") and
						t.companyid in (".getUserObjectValues('company').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':addressbookid' => '%' . $addressbookid . '%',
        ':paymentmethodid' => '%' . $paymentmethodid . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(distinct t.poheaderid) as total')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')->leftjoin('podetail f', 'f.poheaderid = t.poheaderid')->where("
      ((coalesce(docdate,'') like :docdate) and 
				(coalesce(pono,'') like :pono) and 
				(coalesce(b.fullname,'') like :addressbookid) and 
				(coalesce(c.paycode,'') like :paymentmethodid) and 
				(coalesce(d.companyname,'') like :companyid))
					and f.prmaterialid is null
					and t.recordstatus < 5
					and t.recordstatus in (".getUserRecordStatus('listpo').") and 
						t.companyid in (".getUserObjectValues('company').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':addressbookid' => '%' . $addressbookid . '%',
        ':paymentmethodid' => '%' . $paymentmethodid . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.description,b.fullname,c.paycode,d.companyname,e.taxcode,
			(
			select case when sum(z.poqty) > sum(z.qtyres) then 1 else 0 end
			from podetail z 
			) as warna ')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')->where("
      ((coalesce(docdate,'') like :docdate) and 
				(coalesce(pono,'') like :pono) and 
				(coalesce(b.fullname,'') like :addressbookid) and 
				(coalesce(c.paycode,'') like :paymentmethodid) and 
				(coalesce(d.companyname,'') like :companyid))", array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':addressbookid' => '%' . $addressbookid . '%',
        ':paymentmethodid' => '%' . $paymentmethodid . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['grpo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.description,b.fullname,c.paycode,d.companyname,e.taxcode,
			(
			select case when sum(z.poqty) > sum(z.qtyres) then 1 else 0 end
			from podetail z 
			) as warna ')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')->where("((docdate like :docdate) or 
                                    (pono like :pono) or 
                                    (b.fullname like :addressbookid) or 
                                    (c.paycode like :paymentmethodid) or 
                                    (d.companyname like :companyid))
					and t.recordstatus in (".getUserRecordStatus('listpo').")
					and t.companyid in (".getUserObjectValues('company').")
				and t.poheaderid in (select z.poheaderid from podetail z where poqty > qtyres and z.slocid in (".getUserObjectValues('sloc').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':addressbookid' => '%' . $addressbookid . '%',
        ':paymentmethodid' => '%' . $paymentmethodid . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['invpo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.description,b.fullname,c.paycode,d.companyname,e.taxcode,
			(
			select case when sum(z.poqty) > sum(z.qtyres) then 1 else 0 end
			from podetail z 
			) as warna ')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')->where("
      ((coalesce(docdate,'') like :docdate) or 
				(coalesce(pono,'') like :pono) or 
				(coalesce(b.fullname,'') like :addressbookid) or 
				(coalesce(c.paycode,'') like :paymentmethodid) or 
				(coalesce(d.companyname,'') like :companyid))
			and d.companyid = '".$_GET['companyid']."'
					and t.recordstatus in (".getUserRecordStatus('listpo').") and 
						t.companyid in (".getUserObjectValues('company').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':addressbookid' => '%' . $addressbookid . '%',
        ':paymentmethodid' => '%' . $paymentmethodid . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('distinct t.*,a.description,b.fullname,c.paycode,d.companyname,e.taxcode,
			(
			select case when sum(z.poqty) > sum(z.qtyres) then 1 else 0 end
			from podetail z 
			) as warna ')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')->leftjoin('podetail f', 'f.poheaderid = t.poheaderid')->where("
      ((coalesce(docdate,'') like :docdate) and 
				(coalesce(pono,'') like :pono) and 
				(coalesce(b.fullname,'') like :addressbookid) and 
				(coalesce(c.paycode,'') like :paymentmethodid) and 
				(coalesce(d.companyname,'') like :companyid))
					and f.prmaterialid is null
					and t.recordstatus < 5
					and t.recordstatus in (".getUserRecordStatus('listpo').") and 
						t.companyid in (".getUserObjectValues('company').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':addressbookid' => '%' . $addressbookid . '%',
        ':paymentmethodid' => '%' . $paymentmethodid . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'purchasinggroupid' => $data['purchasinggroupid'],
        'purchasinggroupcode' => $data['description'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'paymentmethodid' => $data['paymentmethodid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'paycode' => $data['paycode'],
				'warna' => $data['warna'],
        'shipto' => $data['shipto'],
        'billto' => $data['billto'],
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
        'recordstatus' => $data['recordstatus'],
        'recordstatuspoheader' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchpoplant() {
    header("Content-Type: application/json");
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'accountid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $companyid       = isset($_GET['companyid']) ? $_GET['companyid'] : '';
    $sqlcount="select count(1) as total
                from poheader a
                join addressbook b on b.addressbookid=a.addressbookid
                where a.recordstatus=5  and a.addressbookid in 
                (select a.menuvalueid 
                from groupmenuauth a
                inner join menuauth b on b.menuauthid = a.menuauthid
                inner join usergroup c on c.groupaccessid = a.groupaccessid 
                inner join useraccess d on d.useraccessid = c.useraccessid 
                where upper(b.menuobject) = upper('supplier')
                and upper(d.username)=upper('" . Yii::app()->user->name . "'))";
    
    $cmd = Yii::app()->db->createCommand($sqlcount)->queryScalar();
    $result['total'] = $cmd;
    $sqldata = " select a.poheaderid,a.pono,b.fullname, a.companyid,c.companyname
                from poheader a
                join addressbook b on b.addressbookid=a.addressbookid
                join company c on c.companyid=a.companyid
                where a.recordstatus=5 and a.addressbookid in 
                (select a.menuvalueid 
                from groupmenuauth a
                inner join menuauth b on b.menuauthid = a.menuauthid
                inner join usergroup c on c.groupaccessid = a.groupaccessid 
                inner join useraccess d on d.useraccessid = c.useraccessid 
                where upper(b.menuobject) = upper('supplier')
                and upper(d.username)=upper('" . Yii::app()->user->name . "'))
								and a.poheaderid in 
								(
								select z.poheaderid 
								from podetail z 
								where poqty > qtyres  
								)
                order by a.poheaderid desc
                LIMIT ".$offset.",".$rows;
     $cmd = Yii::app()->db->createCommand($sqldata)->queryAll();
     foreach ($cmd as $data) {
      $row[] = array(
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'fullname' => $data['fullname'],
        'companyname' => $data['companyname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'podetailid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('podetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->leftjoin('prmaterial f', 'f.prmaterialid = t.prmaterialid')->leftjoin('prheader g', 'g.prheaderid = f.prheaderid')->leftjoin('currency h', 'h.currencyid = t.currencyid')->where('poheaderid = :poheaderid', array(
      ':poheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.currencyname,g.prno,d.sloccode,h.currencyname,c.symbol')->from('podetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->leftjoin('prmaterial f', 'f.prmaterialid = t.prmaterialid')->leftjoin('prheader g', 'g.prheaderid = f.prheaderid')->leftjoin('currency h', 'h.currencyid = t.currencyid')->where('poheaderid = :poheaderid', array(
      ':poheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qtyres'] < $data['poqty']) {
				$wqtyres = 1;
			} else {
				$wqtyres = 0;
			}
      $row[] = array(
        'podetailid' => $data['podetailid'],
        'prmaterialid' => $data['prmaterialid'],
        'prno' => $data['prno'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
				'wqtyres' => $wqtyres,
        'poqty' => Yii::app()->format->formatNumber($data['poqty']),
        'saldoqty' => Yii::app()->format->formatNumber($data['poqty'] - $data['qtyres']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'overdelvtol' => Yii::app()->format->formatNumber($data['overdelvtol']),
        'qtyres' => Yii::app()->format->formatNumber($data['qtyres']),
        'underdelvtol' => Yii::app()->format->formatNumber($data['underdelvtol']),
        'delvdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])),
        'netprice' => Yii::app()->format->formatNumber($data['netprice']),
        'total' => Yii::app()->format->formatNumber(($data['poqty'] * $data['netprice'])),
        'itemtext' => $data['itemtext']
      );
    }
    $cmd      = Yii::app()->db->createCommand()->select('sum(t.poqty) as totalqty,sum(t.qtyres) as totalqtyres, sum(t.netprice*t.poqty*t.ratevalue) as totalamount')->from('podetail t')->where('poheaderid = :poheaderid', array(
      ':poheaderid' => $id
    ))->queryRow();
    $footer[] = array(
      'productname' => 'Total',
      'poqty' => Yii::app()->format->formatNumber($cmd['totalqty']),
      'qtyres' => Yii::app()->format->formatNumber($cmd['totalqtyres']),
      'saldoqty' => Yii::app()->format->formatNumber($cmd['totalqty'] - $cmd['totalqtyres']),
      'total' => Yii::app()->format->formatNumber($cmd['totalamount'])
    );
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($arraydata){
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertpoheader(:vpurchasinggroupid,:vdocdate,:vaddressbookid,:vheadernote,:vpono,:vpaymentmethodid,:vshipto,:vbillto,:vcompanyid,:vtaxid,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vpono', $arraydata[4], PDO::PARAM_STR);
        $command->bindvalue(':vrecordstatus', $arraydata[11], PDO::PARAM_STR);
      } else {
        $sql     = 'call Updatepoheader(:vid,:vpurchasinggroupid,:vdocdate,:vaddressbookid,:vheadernote,:vpaymentmethodid,:vshipto,:vbillto,:vcompanyid,:vtaxid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $arraydata[0]);
      }
      $command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vpurchasinggroupid', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vaddressbookid', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vpaymentmethodid', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vtaxid', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vshipto', $arraydata[8], PDO::PARAM_STR);
      $command->bindvalue(':vbillto', $arraydata[9], PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $arraydata[10], PDO::PARAM_STR);
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
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FilePodirect"]["name"]);
		if (move_uploaded_file($_FILES["FilePodirect"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
			for ($row = 2; $row <= $highestRow; ++$row) {
				$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
				$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
				$purchasinggroup = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
				$docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
				$pono = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
				$supplier = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
				$supplierid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$supplier."'")->queryScalar();
				$abid = Yii::app()->db->createCommand("select poheaderid 
					from poheader 
					where companyid = ".$companyid."
					and purchasinggroupid = ".$purchasinggroupid."
					and docdate = ".$docdate."
					and pono = ".$pono."
					and addressbookid = ".$addressbookid."
					")->queryScalar();
				if ($abid == '') {					
					$paycode = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$taxcode = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$shipto = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$billto = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$headernote = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$this->ModifyData(array('',$fullname,$taxno,$bankaccountno,$bankname,$accountowner,$recordstatus));
					//get id addressbookid
					$abid = Yii::app()->db->createCommand("select poheaderid 
						from poheader 
						where companyid = ".$companyid."
						and purchasinggroupid = ".$purchasinggroupid."
						and docdate = ".$docdate."
						and pono = ".$pono."
						and addressbookid = ".$addressbookid."
						")->queryScalar();
				}
				if ($abid != '') {
					if ($objWorksheet->getCellByColumnAndRow(12, $row)->getValue() != '') {
						$productname = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
						$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
						$qty = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
						$uomcode = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
						$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
						$delvdate = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
						$netprice = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
						$currencyname = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
						$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
						$ratevalue = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
						$sloccode = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
						$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
						$undertol = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
						$overtol = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
						$itemtext = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
						$this->ModifyDataDetail(array('',$abid,'',$productid,$qty,$uomid,$delvdate,$netprice,$currencyid,$ratevalue,$slocid,$undertol,$overtol,$itemtext));
					}
				}
			}
    }
	}
  public function actionSave() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $this->ModifyData(array((isset($_POST['poheaderid'])?$_POST['poheaderid']:''),
			$_POST['companyid'],
			$_POST['purchasinggroupid'],
			date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),
			'',
			$_POST['addressbookid'],
			$_POST['paymentmethodid'],
			$_POST['taxid'],
			$_POST['shipto'],
			$_POST['billto'],
			$_POST['headernote'],
			''
			));
  }
	private function ModifyDataDetail($arraydata) {
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertpodetail(:vpoheaderid,:vproductid,:vpoqty,:vunitofmeasureid,:vdelvdate,:vnetprice,:vcurrencyid,:vslocid,:vitemtext,:vprdetailid,:vunderdelvtol,:voverdelvtol,:vratevalue,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatepodetail(:vid,:vpoheaderid,:vproductid,:vpoqty,:vunitofmeasureid,:vdelvdate,:vnetprice,:vcurrencyid,:vslocid,:vitemtext,:vprdetailid,:vunderdelvtol,:voverdelvtol,:vratevalue,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
      }
      $command->bindvalue(':vpoheaderid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vprdetailid', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vpoqty', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vunitofmeasureid',  $arraydata[5],PDO::PARAM_STR);
      $command->bindvalue(':vdelvdate', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vnetprice', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $arraydata[8], PDO::PARAM_STR);
      $command->bindvalue(':vratevalue', $arraydata[9], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $arraydata[10], PDO::PARAM_STR);
      $command->bindvalue(':vunderdelvtol', $arraydata[11], PDO::PARAM_STR);
      $command->bindvalue(':voverdelvtol', $arraydata[12], PDO::PARAM_STR);
      $command->bindvalue(':vitemtext', $arraydata[13], PDO::PARAM_STR);
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
  public function actionSavedetail() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $this->ModifyDataDetail(array((isset($_POST['podetailid'])?$_POST['podetailid']:''),$_POST['poheaderid'],
			'',
			$_POST['productid'],
			$_POST['poqty'],
			$_POST['unitofmeasureid'],
			date(Yii::app()->params['datetodb'], strtotime($_POST['delvdate'])),
			$_POST['netprice'],
			$_POST['currencyid'],
			$_POST['ratevalue'],
			$_POST['slocid'],
			$_POST['underdelvtol'],
			$_POST['overdelvtol'],
			$_POST['itemtext']
			));
  }
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepoheader(:vid,:vcreatedby)';
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
  public function actionPurgedetail() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepodetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_STR);
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
  public function actionApprove() {
    parent::actionApprove();
    $id          = $_POST['id'];
    $a           = Yii::app()->user->name;
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      foreach ($id as $ids) {
        $sql     = 'call ApprovePODirect(:vid, :vlastupdateby)';
        $command = $connection->createCommand($sql);
        $command->bindValue(':vid', $ids, PDO::PARAM_INT);
        $command->bindValue(':vlastupdateby', $a, PDO::PARAM_STR);
        $command->execute();
      }
      $transaction->commit();
      GetMessage(false, 'insertsuccess', 1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage(), 1);
    }
  }
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeletePO(:vid,:vcreatedby)';
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
      GetMessage('failure', 'chooseone');
    }
  }
  public function actionGeneratedetail() {
    if (isset($_POST['supplierid']) & isset($_POST['prmaterialid'])) {
      $podetail  = Yii::app()->db->createCommand()->select('t.*,e.slocid,b.prno,c.productname,d.uomcode,(t.qty - t.poqty) as posisa,e.sloccode')->from('prmaterial t')->join('prheader b', 'b.prheaderid = t.prheaderid')->join('product c', 'c.productid = t.productid')->join('unitofmeasure d', 'd.unitofmeasureid = t.unitofmeasureid')->leftjoin('deliveryadvicedetail f', 'f.deliveryadvicedetailid = t.deliveryadvicedetailid')->leftjoin('deliveryadvice g', 'g.deliveryadviceid = f.deliveryadviceid')->join('sloc e', 'e.slocid = g.slocid')->where('t.prmaterialid = ' . $_POST['prmaterialid'] . ' and t.qty > t.poqty ' . ' and b.prno is not null')->queryRow();
      $pirdetail = Yii::app()->db->createCommand()->select('ifnull(underdelvtol,0) as underdelvtol, ifnull(overdelvtol,0) as overdelvtol,price,currencyid')->from('purchinforec t')->where('t.addressbookid = ' . $_POST['supplierid'] . ' and t.productid = ' . $podetail['productid'])->queryRow();
      echo CJSON::encode(array(
        'status' => 'success',
        'prmaterialid' => $podetail['prmaterialid'],
        'prno' => $podetail['prno'],
        'productid' => $podetail['productid'],
        'productname' => $podetail['productname'],
        'poqty' => $podetail['posisa'],
        'unitofmeasureid' => $podetail['unitofmeasureid'],
        'uomcode' => $podetail['uomcode'],
        'slocid' => $podetail['slocid'],
        'slocdesc' => $podetail['sloccode'],
        'itemtext' => $podetail['itemtext'],
        'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($podetail['reqdate'])),
        'currencyid' => ($pirdetail['currencyid'] !== null) ? $pirdetail['currencyid'] : 40,
        'currencyrate' => 1,
        'underdelvtol' => ($pirdetail['underdelvtol'] !== null) ? $pirdetail['underdelvtol'] : 0,
        'overdelvtol' => ($pirdetail['overdelvtol'] !== null) ? $pirdetail['overdelvtol'] : 0
      ));
      Yii::app()->end();
    }
  }
  public function actionGenerateaddress() {
    $product = null;
    if (isset($_POST['id'])) {
      $product = Yii::app()->db->createCommand()->select('t.*')->from('company t')->where('t.companyid = ' . $_POST['id'])->queryRow();
    }
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'shipto' => $product['address'],
        'billto' => $product['billto']
      ));
      Yii::app()->end();
    }
  }
	public function actionGenerateBarcode()
  {
    $ids = $_REQUEST['id'];
      
    foreach ($ids as $id) 
		{
			$max = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppo')")->queryScalar();
			$sql    = "select ifnull(recordstatus,0) as recordstatus from poheader where poheaderid  = {$id} and recordstatus = {$max}";
			$status = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql1    = "select ifnull(isbarcode,0) as isbarcode from poheader where poheaderid  = {$id} and recordstatus = {$max}";
			$isbarcode = Yii::app()->db->createCommand($sql1)->queryScalar();
			$sql2    = "select ifnull(count(1),0) as barcode from podetail a join product b on b.productid=a.productid where poheaderid  = {$id}  and barcode = ''";
			$barcode = Yii::app()->db->createCommand($sql2)->queryScalar();
			if ($status == 0) {
				GetMessage('failure', 'docnotmaxstatus');
			} 
			else 
			if ($isbarcode == 1) {
				GetMessage('failure', 'datagenerated');
			}
			else
			if ($barcode != 0) {
				GetMessage('failure', 'emptybarcode');
			}
			else{
				try{
					$connection = Yii::app()->db;
					$transaction = $connection->beginTransaction();
					$update = "update poheader set isbarcode = 1 where poheaderid = ".$id;
					$command1 = $connection->createCommand($update);
					$command1->execute();

					$sql = "insert into tempscan (companyid,poheaderid,podetailid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
					select a.companyid,a.poheaderid,b.podetailid,b.productid,b.slocid,b.poqty,0,c.barcode,b.unitofmeasureid,1
					from poheader a 
					join podetail b on b.poheaderid = a.poheaderid
					join product c on c.productid = b.productid 
					where a.poheaderid = " . $id;
					$command2 = $connection->createCommand($sql);
					$command2->execute();
					
					$sql  = "select a.companyid,a.poheaderid,b.podetailid,b.productid,b.slocid,b.poqty,c.barcode,b.unitofmeasureid,f.plantid,b.delvdate
									from poheader a 
									join podetail b on b.poheaderid = a.poheaderid 
									join product c on c.productid = b.productid 
									join sloc d on d.slocid = b.slocid 
									join plant f on f.plantid = d.plantid 
									join unitofmeasure e on e.unitofmeasureid = b.unitofmeasureid
									where a.poheaderid = " . $id;
					$rows = Yii::app()->db->createCommand($sql)->queryAll();
					foreach ($rows as $row) {
							for ($i = 1; $i <= $row['poqty']; $i++) {
									$sql = "insert into tempscan (companyid,poheaderid,podetailid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
											values (" . $row['companyid'] . "," . $row['poheaderid'] . "," . $row['podetailid'] . "," . $row['productid'] . "," . $row['slocid'] . ",1,0,concat(" . $row['plantid'] . $row['podetailid'] . ",'-O',lpad(" . $i . ",5,'0'))," . $row['unitofmeasureid'] . ",0)";
									$command3 = $connection->createCommand($sql);
									$command3->execute();
							}
					}
					$transaction->commit();
					GetMessage('success', 'GenerateBarcodeDone');
				}
				catch (Exception $e) {
					$transaction->rollBack();
					GetMessage('failure', $e->getMessage());
				}
			}
		}
  }
  public function actionDownEan13() {
    parent::actionDownloadbarcode();
    $this->pdf->SetY(-8);
    $this->pdf->SetFooterMargin(-15);
		$sql = "select a.barcode, a.qtyori, b.productname, a.productid
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.poheaderid = " . $_REQUEST['id'];
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->SetAutoPageBreak(TRUE, -20);
    $width = 175;
    $height = 266;
    $pageLayout = array($width, $height);
		$this->pdf->AddPage('P', array(
			109,
			175
		));
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
    foreach ($fgs as $row) {
      for ($i = 1; $i <= $row['qtyori']; $i++) {
        $this->pdf->setY($this->pdf->getY()+10);
         $sql   = "select b.productname, a.qtyori, a.productid, a.barcode
				from tempscan a 
				join product b on b.productid = a.productid 
				where a.isean = 0 and a.poheaderid = " . $_REQUEST['id'] . " 
				and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
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
	public function actionDownSticker() {
    parent::actionDownload();
		$sql = "select a.*,b.productname,
					substr(productname,1,(20+instr(substr(productname,21,20),' '))) as productname1,
					substr(productname,(21+instr(substr(productname,21,20),' ')),(20+instr(substr(productname,21,20),' '))) as productname2,
					substr(productname,((21+instr(substr(productname,21,20),' '))+(20+instr(substr(productname,21,20),' '))),(20+instr(substr(productname,21,20),' '))) as productname3
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.poheaderid = " . $_REQUEST['id'];
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
					where a.isean = 0 and a.poheaderid = " . $_REQUEST['id'] . " 
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
					where a.isean = 0 and a.poheaderid = " . $_REQUEST['id'] . " 
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.companyid,(select companyname from company zz where zz.companyid = a.companyid) as companyname,
		b.fullname, a.pono, a.docdate,b.addressbookid,a.poheaderid,c.paymentname,a.headernote,a.printke,a.poheaderid,
			ifnull(a.printke,0) as printke,a.recordstatus,a.shipto,a.billto
      from poheader a
      left join addressbook b on b.addressbookid = a.addressbookid
      left join paymentmethod c on c.paymentmethodid = a.paymentmethodid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.poheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('poheader');
    $this->pdf->AddPage('P', 'Letter');
    $this->pdf->AliasNbPages();
    $this->pdf->isprint = true;
    foreach ($dataReader as $row) {
      $sql1               = "update poheader set printke = ifnull(printke,0) + 1
				where poheaderid = " . $row['poheaderid'];
      $command1           = $this->connection->createCommand($sql1);
      $this->pdf->printke = $row['printke'];
      $command1->execute();
      $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.faxno
        from address a
        left join addresstype b on b.addresstypeid = a.addresstypeid
        left join city c on c.cityid = a.cityid
        where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $contact     = '';
      $addressname = '';
      $phoneno     = '';
      $faxno       = '';
      foreach ($dataReader1 as $row1) {
        $addressname = $row1['addressname'];
        $phoneno     = $row1['phoneno'];
        $faxno       = $row1['faxno'];
      }
      $sql2        = "select ifnull(a.addresscontactname,'') as addresscontactname, ifnull(a.phoneno,'') as phoneno, ifnull(a.mobilephone,'') as mobilephone
					from addresscontact a
					where addressbookid = " . $row['addressbookid'] . " order by addresscontactid " . " limit 1";
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      foreach ($dataReader2 as $row2) {
        $contact = $row2['addresscontactname'];
      }
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->Rect(10, 10, 202, 30);
      $this->pdf->text(15, 15, 'Supplier');
      $this->pdf->text(40, 15, ': ' . $row['fullname']);
      $this->pdf->text(15, 20, 'Attention');
      $this->pdf->text(40, 20, ': ' . $contact);
      $this->pdf->text(15, 25, 'Address');
      $this->pdf->text(40, 25, ': ' . $addressname);
      $this->pdf->text(15, 30, 'Phone');
      $this->pdf->text(40, 30, ': ' . $phoneno);
      $this->pdf->text(15, 35, 'Fax');
      $this->pdf->text(40, 35, ': ' . $faxno);
      $this->pdf->text(120, 15, 'PO No ');
      $this->pdf->text(150, 15, ': ' . $row['pono']);
      $this->pdf->text(120, 20, 'PO Date ');
      $this->pdf->text(150, 20, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $sql1        = "select *,(jumlah * (taxvalue / 100)) as ppn, jumlah + (jumlah * (taxvalue / 100)) as total
        from (select a.poheaderid,c.uomcode,a.poqty,a.delvdate,a.netprice,(a.netprice*a.poqty*a.ratevalue) as jumlah,b.productname,
        d.symbol,d.i18n,e.taxvalue,a.itemtext
        from podetail a
				left join poheader f on f.poheaderid = a.poheaderid
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
        left join currency d on d.currencyid = a.currencyid
        left join tax e on e.taxid = f.taxid
        where a.poheaderid = ".$row['poheaderid'].") z";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total = 0;$jumlah = 0;$ppn = 0;
      $this->pdf->sety($this->pdf->gety() + 30);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
      $this->pdf->setwidths(array(15,10,45,22,25,22,25,18,20));
      $this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
	    $this->pdf->colheader = array('Qty','Units','Item', 'Unit Price','Jumlah','PPN','Total','Delivery','Remarks');
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array('R','C','L','R','R','R','R','R','R','L');
			$this->pdf->setFont('Arial','',8);
      $symbol = '';
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatCurrency($row1['poqty']),
          $row1['uomcode'],
          iconv("UTF-8", "ISO-8859-1", $row1['productname']),
          Yii::app()->format->formatCurrency($row1['netprice'], iconv("UTF-8", "ISO-8859-1", $row1['symbol'])),
			           Yii::app()->format->formatCurrency($row1['jumlah'], $row1['symbol']),
			           Yii::app()->format->formatCurrency($row1['ppn'], $row1['symbol']),
			           Yii::app()->format->formatCurrency($row1['total'], $row1['symbol']),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate'])),
          $row1['itemtext']
        ));
        $jumlah = $row1['jumlah'] + $jumlah;
        $ppn = $row1['ppn'] + $ppn;
        $total = $row1['total'] + $total;
        $symbol = $row1['symbol'];
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        'Grand Total',
        Yii::app()->format->formatCurrency($jumlah,$symbol),
        Yii::app()->format->formatCurrency($ppn,$symbol),
        Yii::app()->format->formatCurrency($total,$symbol),
        '',
        ''
      ));
      $this->pdf->title = '';
      $this->pdf->checknewpage(100);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->setFont('Arial', 'BU', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'TERM OF CONDITIONS');
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->colheader = array(
        'Item',
        'Description'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        'Payment Term',
        $row['paymentname']
      ));
      $this->pdf->row(array(
        'Kirim ke',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Tagih ke',
        $row['billto']
      ));
      $this->pdf->row(array(
        'Keterangan',
        $row['headernote']
      ));
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->CheckPageBreak(60);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Thanking you and assuring our best attention we remain.');
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Sincerrely Yours');
      $this->pdf->text(10, $this->pdf->gety() + 15, $row['companyname']);
      $this->pdf->text(135, $this->pdf->gety() + 15, 'Confirmed and Accepted by Supplier');
      $this->pdf->text(10, $this->pdf->gety() + 35, '');
      $this->pdf->text(10, $this->pdf->gety() + 36, '____________________');
      $this->pdf->text(135, $this->pdf->gety() + 36, '__________________________');
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(10, $this->pdf->gety() + 40, '');
      $this->pdf->setFont('Arial', 'BU', 7);
      $this->pdf->text(10, $this->pdf->gety() + 55, '#Note: Mohon tidak memberikan gift atau uang kepada staff kami#');
      $this->pdf->text(10, $this->pdf->gety() + 60, '#Print ke: ' . $row['printke']);
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownload();
    $sql = "select purchasinggroupid,docdate,addressbookid,headernote,pono,paymentmethodid,printke,shipto,billto,companyid,recordstatus
				from poheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.poheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('purchasinggroupid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(3, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(4, 1, GetCatalog('pono'))->setCellValueByColumnAndRow(5, 1, GetCatalog('paymentmethodid'))->setCellValueByColumnAndRow(6, 1, GetCatalog('printke'))->setCellValueByColumnAndRow(7, 1, GetCatalog('shipto'))->setCellValueByColumnAndRow(8, 1, GetCatalog('billto'))->setCellValueByColumnAndRow(9, 1, GetCatalog('companyid'))->setCellValueByColumnAndRow(10, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['purchasinggroupid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(4, $i + 1, $row1['pono'])->setCellValueByColumnAndRow(5, $i + 1, $row1['paymentmethodid'])->setCellValueByColumnAndRow(6, $i + 1, $row1['printke'])->setCellValueByColumnAndRow(7, $i + 1, $row1['shipto'])->setCellValueByColumnAndRow(8, $i + 1, $row1['billto'])->setCellValueByColumnAndRow(9, $i + 1, $row1['companyid'])->setCellValueByColumnAndRow(10, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="poheader.xlsx"');
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