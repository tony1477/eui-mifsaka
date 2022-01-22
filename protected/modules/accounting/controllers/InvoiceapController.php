<?php
class InvoiceapController extends Controller {
  public $menuname = 'invoiceap';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexmaterial() {
    if (isset($_GET['grid']))
      echo $this->actionsearchmaterial();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexjurnal() {
    if (isset($_GET['grid']))
      echo $this->actionsearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into invoiceap (invoicedate,currencyid,currencyrate,taxdate,receiptdate,recordstatus) 
				values ('".$dadate->format('Y-m-d')."',40,1,'".$dadate->format('Y-m-d')."','".$dadate->format('Y-m-d')."',".findstatusbyuser('insinvap').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'invoiceapid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $invoiceapid     = isset($_POST['invoiceapid']) ? $_POST['invoiceapid'] : '';
    $invoiceno       = isset($_POST['invoiceno']) ? $_POST['invoiceno'] : '';
    $invoicedate     = isset($_POST['invoicedate']) ? $_POST['invoicedate'] : '';
    $poheaderid      = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $addressbookid   = isset($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
    $paymentmethodid = isset($_POST['paymentmethodid']) ? $_POST['paymentmethodid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $taxid           = isset($_POST['taxid']) ? $_POST['taxid'] : '';
    $grheaderid      = isset($_POST['grheaderid']) ? $_POST['grheaderid'] : '';
    $invoiceapid     = isset($_GET['q']) ? $_GET['q'] : $invoiceapid;
    $invoiceno       = isset($_GET['q']) ? $_GET['q'] : $invoiceno;
    $invoicedate     = isset($_GET['q']) ? $_GET['q'] : $invoicedate;
    $poheaderid      = isset($_GET['q']) ? $_GET['q'] : $poheaderid;
    $addressbookid   = isset($_GET['q']) ? $_GET['q'] : $addressbookid;
    $paymentmethodid = isset($_GET['q']) ? $_GET['q'] : $paymentmethodid;
    $taxid           = isset($_GET['q']) ? $_GET['q'] : $taxid;
    $grheaderid           = isset($_GET['q']) ? $_GET['q'] : $grheaderid;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceapid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $rec          = array();
    $com          = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appinvap')")->queryScalar();
		$maxstatreqpay = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppayreq')")->queryScalar();
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceap t')->leftjoin('poheader a', 'a.poheaderid=t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->leftjoin('paymentmethod d', 'd.paymentmethodid=t.paymentmethodid')->leftjoin('tax e', 'e.taxid=t.taxid')->leftjoin('company f', 'f.companyid = t.companyid')->leftjoin('grheader g', 'g.grheaderid = t.grheaderid')->where("
      ((t.invoicedate like :invoicedate) or 
                            (t.invoiceno like :invoiceno) or 
                            (a.pono like :poheaderid) or
                            (b.fullname like :addressbookid)) and f.companyid in (".getUserObjectValues('company').")", array(
        ':invoicedate' => '%' . $invoicedate . '%',
        ':invoiceno' => '%' . $invoiceno . '%',
        ':poheaderid' => '%' . $poheaderid . '%',
        ':addressbookid' => '%' . $addressbookid . '%'
      ))->queryScalar();
    } else if (isset($_GET['reqpay'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceap t')->leftjoin('poheader a', 'a.poheaderid=t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->leftjoin('paymentmethod d', 'd.paymentmethodid=t.paymentmethodid')->leftjoin('tax e', 'e.taxid=t.taxid')->leftjoin('company f', 'f.companyid = t.companyid')->leftjoin('grheader g', 'g.grheaderid = t.grheaderid')->where("((t.invoicedate like :invoicedate) or 
                            (t.invoiceno like :invoiceno) or 
                            (a.pono like :poheaderid) or
                            (b.fullname like :addressbookid)) and t.recordstatus = {$maxstat}
														and t.companyid = '".$_GET['companyid']."' 				
						and t.invoiceapid not in (select distinct j.invoiceapid
						from reqpayinv j
						join reqpay k on k.reqpayid=j.reqpayid
						where k.recordstatus={$maxstatreqpay} and k.companyid=t.companyid)", array(
        ':invoicedate' => '%' . $invoicedate . '%',
        ':invoiceno' => '%' . $invoiceno . '%',
        ':poheaderid' => '%' . $poheaderid . '%',
        ':addressbookid' => '%' . $addressbookid . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceap t')
		->leftjoin('poheader a', 'a.poheaderid=t.poheaderid')
		->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')
		->leftjoin('currency c', 'c.currencyid=t.currencyid')
		->leftjoin('paymentmethod d', 'd.paymentmethodid=t.paymentmethodid')
		->leftjoin('tax e', 'e.taxid=t.taxid')
		->leftjoin('company f', 'f.companyid=a.companyid')
		->leftjoin('grheader g', 'g.grheaderid=t.grheaderid')
		->where("(coalesce(t.invoicedate,'') like :invoicedate) and 
			(coalesce(t.invoiceapid,'') like :invoiceapid) and 
			(coalesce(t.invoiceno,'') like :invoiceno) and 
			(coalesce(a.pono,'') like :poheaderid) and
			(coalesce(f.companyname,'') like :companyid) and
			(coalesce(e.taxcode,'') like :taxid) and
			(coalesce(g.grno,'') like :grheaderid) and
			(coalesce(b.fullname,'') like :addressbookid)
					and t.recordstatus in (".getUserRecordStatus('listinvap').")
					and t.recordstatus < {$maxstat}
					and t.companyid in (".getUserObjectWfValues('company','listinvap').")", array(
        ':invoicedate' => '%' . $invoicedate . '%',
      ':invoiceapid' => '%' . $invoiceapid . '%',
      ':invoiceno' => '%' . $invoiceno . '%',
      ':poheaderid' => '%' . $poheaderid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':taxid' => '%' . $taxid . '%',
      ':grheaderid' => '%' . $grheaderid . '%',
      ':addressbookid' => '%' . $addressbookid . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.fullname as supplier,c.currencyname,d.paycode,e.taxcode,f.companyname,g.grno')->from('invoiceap t')->leftjoin('poheader a', 'a.poheaderid=t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->leftjoin('paymentmethod d', 'd.paymentmethodid=t.paymentmethodid')->leftjoin('tax e', 'e.taxid=t.taxid')->leftjoin('company f', 'f.companyid = t.companyid')->leftjoin('grheader g', 'g.grheaderid = t.grheaderid')->where("((t.invoicedate like :invoicedate) or 
                            (t.invoiceno like :invoiceno) or 
                            (a.pono like :poheaderid) or
                            (b.fullname like :addressbookid)) and f.companyid in (".getUserObjectValues('company').")", array(
        ':invoicedate' => '%' . $invoicedate . '%',
        ':invoiceno' => '%' . $invoiceno . '%',
        ':poheaderid' => '%' . $poheaderid . '%',
        ':addressbookid' => '%' . $addressbookid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['reqpay'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.fullname as supplier,c.currencyname,d.paycode,e.taxcode,f.companyname,g.grno')->from('invoiceap t')->leftjoin('poheader a', 'a.poheaderid=t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->leftjoin('paymentmethod d', 'd.paymentmethodid=t.paymentmethodid')->leftjoin('tax e', 'e.taxid=t.taxid')->leftjoin('company f', 'f.companyid = t.companyid')->leftjoin('grheader g', 'g.grheaderid = t.grheaderid')->where("((t.invoicedate like :invoicedate) or 
				(t.invoiceno like :invoiceno) or 
				(a.pono like :poheaderid) or
				(b.fullname like :addressbookid)) and t.recordstatus = {$maxstat}
				and t.companyid = '".$_GET['companyid']."' 		
				and t.invoiceapid not in (select distinct j.invoiceapid
				from reqpayinv j
				join reqpay k on k.reqpayid=j.reqpayid
				where k.recordstatus={$maxstatreqpay} and k.companyid=t.companyid)", array(
        ':invoicedate' => '%' . $invoicedate . '%',
        ':invoiceno' => '%' . $invoiceno . '%',
        ':poheaderid' => '%' . $poheaderid . '%',
        ':addressbookid' => '%' . $addressbookid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.pono,b.fullname as supplier,c.currencyname,d.paycode,e.taxcode,f.companyname,g.grno')->from('invoiceap t')
		->leftjoin('poheader a', 'a.poheaderid=t.poheaderid')
		->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')
		->leftjoin('currency c', 'c.currencyid=t.currencyid')
		->leftjoin('paymentmethod d', 'd.paymentmethodid=t.paymentmethodid')
		->leftjoin('tax e', 'e.taxid=t.taxid')
		->leftjoin('company f', 'f.companyid=a.companyid')
		->leftjoin('grheader g', 'g.grheaderid=t.grheaderid')
		->where("(coalesce(t.invoicedate,'') like :invoicedate) and 
			(coalesce(t.invoiceapid,'') like :invoiceapid) and 
			(coalesce(t.invoiceno,'') like :invoiceno) and 
			(coalesce(a.pono,'') like :poheaderid) and
			(coalesce(f.companyname,'') like :companyid) and
			(coalesce(e.taxcode,'') like :taxid) and
			(coalesce(g.grno,'') like :grheaderid) and
			(coalesce(b.fullname,'') like :addressbookid)
					and t.recordstatus in (".getUserRecordStatus('listinvap').")
					and t.recordstatus < {$maxstat}
					and t.companyid in (".getUserObjectWfValues('company','listinvap').")", array(
        ':invoicedate' => '%' . $invoicedate . '%',
      ':invoiceapid' => '%' . $invoiceapid . '%',
      ':invoiceno' => '%' . $invoiceno . '%',
      ':poheaderid' => '%' . $poheaderid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':taxid' => '%' . $taxid . '%',
      ':grheaderid' => '%' . $grheaderid . '%',
      ':addressbookid' => '%' . $addressbookid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapid' => $data['invoiceapid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'invoiceno' => $data['invoiceno'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'grheaderid' => $data['grheaderid'],
        'grno' => $data['grno'],
        'addressbookid' => $data['addressbookid'],
        'supplier' => $data['supplier'],
        'amount' => Yii::app()->format->formatCurrency($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatCurrency($data['currencyrate']),
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
        'taxno' => $data['taxno'],
        'taxdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['taxdate'])),
        'receiptdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['receiptdate'])),
        'recordstatus' => $data['recordstatus'],
        'recordstatusinvoiceap' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchMaterial() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceapmatid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceapmat t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('podetail b', 'b.podetailid=t.podetailid')->leftjoin('grdetail c', 'c.grdetailid=t.grdetailid')->leftjoin('unitofmeasure d', 'd.unitofmeasureid=t.uomid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.poqty,c.qty as grqty,d.uomcode,b.ratevalue*(b.netprice+(select b.netprice*b.ratevalue*(k.taxvalue/100) from poheader j join tax k on k.taxid=j.taxid where j.poheaderid=b.poheaderid)) as price,b.ratevalue*c.qty*(b.netprice+(select b.netprice*b.ratevalue*(k.taxvalue/100) from poheader j join tax k on k.taxid=j.taxid where j.poheaderid=b.poheaderid)) as jumlah')->from('invoiceapmat t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('podetail b', 'b.podetailid=t.podetailid')->leftjoin('grdetail c', 'c.grdetailid=t.grdetailid')->leftjoin('unitofmeasure d', 'd.unitofmeasureid=t.uomid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapmatid' => $data['invoiceapmatid'],
        'invoiceapid' => $data['invoiceapid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'podetailid' => $data['podetailid'],
        'grdetailid' => $data['grdetailid'],
        'poqty' => Yii::app()->format->formatNumber($data['poqty']),
        'grqty' => Yii::app()->format->formatNumber($data['grqty']),
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'jumlah' => Yii::app()->format->formatCurrency($data['jumlah']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		    $cmd             = Yii::app()->db->createCommand()->select('sum(b.poqty) as poqty,sum(c.qty) as grqty,sum(b.ratevalue*c.qty*(b.netprice+(select b.netprice*b.ratevalue*(k.taxvalue/100) from poheader j join tax k on k.taxid=j.taxid where j.poheaderid=b.poheaderid))) as jumlah')->from('invoiceapmat t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('podetail b', 'b.podetailid=t.podetailid')->leftjoin('grdetail c', 'c.grdetailid=t.grdetailid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->queryRow();
		$footer[] = array(
      'productname' => 'Total',
      'poqty' => Yii::app()->format->formatNumber($cmd['poqty']),
      'grqty' => Yii::app()->format->formatNumber($cmd['grqty']),
			'',
      'jumlah' => Yii::app()->format->formatNumber($cmd['jumlah']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchJurnal() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'debet';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceapjurnal t')->join('account a', 'a.accountid=t.accountid')->join('currency b', 'b.currencyid=t.currencyid')->leftjoin('plant c','c.plantid=t.plantid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountname,b.currencyname,c.plantcode')->from('invoiceapjurnal t')->join('account a', 'a.accountid=t.accountid')->join('currency b', 'b.currencyid=t.currencyid')->leftjoin('plant c','c.plantid = t.plantid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapjurnalid' => $data['invoiceapjurnalid'],
        'invoiceapid' => $data['invoiceapid'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debet' => Yii::app()->format->formatNumber($data['debet']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
    //$connection  = Yii::app()->db;
    //$transaction = $connection->beginTransaction();
    //try {
			$id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
				$sql     = 'call Insertinvoiceap(:vcompanyid,:vinvoiceno,:vinvoicedate,:vpoheaderid,:vaddressbookid,:vamount,:vcurrencyid,:vcurrencyrate,:vpaymentmethodid,:vtaxid,:vtaxno,:vtaxdate,:vreceiptdate,:vgrheaderid,:vrecordstatus,:vcreatedby)';
				$command = $connection->createCommand($sql);				
				$command->bindvalue(':vrecordstatus', $arraydata[15], PDO::PARAM_STR);
			} else {
				$sql     = 'call Updateinvoiceap(:vid,:vcompanyid,:vinvoiceno,:vinvoicedate,:vpoheaderid,:vaddressbookid,:vamount,:vcurrencyid,:vcurrencyrate,:vpaymentmethodid,:vtaxid,:vtaxno,:vtaxdate,:vreceiptdate,:vgrheaderid,:vcreatedby)';
				$command = $connection->createCommand($sql);
				$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $arraydata[0]);
			}
      $command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vinvoicedate', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vpoheaderid', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceno', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vaddressbookid', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vamount', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $arraydata[8], PDO::PARAM_STR);
      $command->bindvalue(':vpaymentmethodid', $arraydata[9], PDO::PARAM_STR);
      $command->bindvalue(':vtaxid', $arraydata[10], PDO::PARAM_STR);
      $command->bindvalue(':vtaxno', $arraydata[11], PDO::PARAM_STR);
      $command->bindvalue(':vtaxdate', $arraydata[12], PDO::PARAM_STR);
      $command->bindvalue(':vreceiptdate', $arraydata[13], PDO::PARAM_STR);
      $command->bindvalue(':vgrheaderid', $arraydata[14], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      //$transaction->commit();
      //GetMessage(true, 'insertsuccess', 1);
    /*}
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 1);
    }*/
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileInvoiceap"]["name"]);
		if (move_uploaded_file($_FILES["FileInvoiceap"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                for ($row = 2; $row <= $highestRow; ++$row) {
                    $nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                    if ($nourut != '') {
                        $companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
                        $docdate = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $docno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $pono = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $poheaderid = Yii::app()->db->createCommand("select poheaderid 
                        from poheader 
                        where companyid = ".$companyid." and pono = '".$pono."'")->queryScalar();
                        $supplier = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $addressbookid = Yii::app()->db->createCommand("select addressbookid 
                        from addressbook 
                        where fullname = '".$supplier."'")->queryScalar();
                        $amount = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $currencyname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
                        $currencyrate = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $paymentmethod = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $paymentmethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$paymentmethod."'")->queryScalar();
                        $taxcode = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$taxcode."'")->queryScalar();
                        $pajakno = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $taxdate = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
                        $receiptdate = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
                        $grno = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
                        $grheaderid = Yii::app()->db->createCommand("select grheaderid 
                        from grheader 
                        where companyid = ".$companyid." and grno = '".$grno."'")->queryScalar();
                        $recordstatus = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
                        $this->ModifyData($connection,array('',$companyid,$docdate,$poheaderid,$docno,$addressbookid,$amount,$currencyid,$currencyrate,$paymentmethodid,$taxid,$pajakno,$taxdate,$receiptdate,$grheaderid,$recordstatus));
                }
            }
            $transaction->commit();
            GetMessage(false, 'insertsuccess');
        }
        catch (Exception $e) {
            $transaction->rollback();
            GetMessage(true, $e->getMessage());
        }
    }
 }
  public function actionSave() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
        $this->ModifyData($connection, array((isset($_POST['invoiceapid'])?$_POST['invoiceapid']:''),
                $_POST['companyid'],
                date(Yii::app()->params['datetodb'], strtotime($_POST['invoicedate'])),
                $_POST['poheaderid'],
                $_POST['invoiceno'],
                $_POST['addressbookid'],
                $_POST['amount'],
                $_POST['currencyid'],
                $_POST['currencyrate'],
                $_POST['paymentmethodid'],
                $_POST['taxid'],
                $_POST['taxno'],
                date(Yii::app()->params['datetodb'], strtotime($_POST['taxdate'])),
                date(Yii::app()->params['datetodb'], strtotime($_POST['receiptdate'])),
                $_POST['grheaderid']
            ));
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
    }
  }
	private function ModifyDataMaterial($connection, $arraydata) {
    //$connection  = Yii::app()->db;
    //$transaction = $connection->beginTransaction();
    //try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertinvoiceapmat(:vinvoiceapid,:vproductid,:vpodetailid,:vgrdetailid,:vpoqty,:vgrqty,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateinvoiceapmat(:vid,:vinvoiceapid,:vproductid,:vpodetailid,:vgrdetailid,:vpoqty,:vgrqty,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
      }
      $command->bindvalue(':vinvoiceapid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vpodetailid', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vgrdetailid', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vpoqty', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vgrqty', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vitemnote', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      //$transaction->commit();
      //GetMessage(true, 'insertsuccess', 1);
    /*}
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 1);
    }*/
	}
  public function actionSavematerial() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection = Yii::app()->db;
    $transaction= $connection->beginTransaction();
    try {
        $this->ModifyDataMaterial($connection,array((isset($_POST['invoiceapmatid'])?$_POST['invoiceapmatid']:''),$_POST['invoiceapid'],$_POST['productid'],
                $_POST['podetailid'],$_POST['grdetailid'],$_POST['poqty'],$_POST['grqty'],$_POST['itemnote']));
    }
    catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
    }
  }
	private function ModifyDataJurnal($connection, $arraydata) {
	//$connection  = Yii::app()->db;
    //$transaction = $connection->beginTransaction();
    //try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertinvoiceapjurnal(:vinvoiceapid,:vplantid,:vaccountid,:vdebet,:vcredit,:vcurrencyid,:vcurrencyrate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateinvoiceapjurnal(:vid,:vinvoiceapid,:vplantid,:vaccountid,:vdebet,:vcredit,:vcurrencyid,:vcurrencyrate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
      }
      $command->bindvalue(':vinvoiceapid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vplantid', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vaccountid', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vdebet', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vcredit', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $arraydata[8], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      //$transaction->commit();
      //GetMessage(true, 'insertsuccess', 1);
    /*}
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 1);
    */
	}
	public function actionSaveJurnal() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection = Yii::app()->db;
    $transaction= $connection->beginTransaction();
    try {
        $this->ModifyDataJurnal($connection,array((isset($_POST['invoiceapjurnalid'])?$_POST['invoiceapjurnalid']:''),
			$_POST['invoiceapid'],
			$_POST['plantid'],
			$_POST['accountid'],
			$_POST['debet'],
			$_POST['credit'],
			$_POST['currencyid'],
			$_POST['currencyrate'],
			$_POST['description']
		));
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
    }
  }
  public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $cmd             = Yii::app()->db->createCommand()->select('t.addressbookid,t.paymentmethodid,t.taxid,b.paycode,c.taxcode,getamountinvpo(t.poheaderid) as amount')->from('poheader t')->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')->leftjoin('paymentmethod b', 'b.paymentmethodid = t.paymentmethodid')->leftjoin('tax c', 'c.taxid = t.taxid')->where("t.poheaderid = " . $_POST['id'])->queryRow();
    } else if(isset($_POST['grheaderid'])){
         $cmd             = Yii::app()->db->createCommand()->select('e.addressbookid,e.paymentmethodid,e.taxid,b.paycode,c.taxcode,GetAmountInvGR(t.grheaderid) as amount')->from('grheader t')
                            ->leftjoin('poheader e', 'e.poheaderid = t.poheaderid')->leftjoin('addressbook a', 'a.addressbookid = e.addressbookid')->leftjoin('paymentmethod b', 'b.paymentmethodid = e.paymentmethodid')->leftjoin('tax c', 'c.taxid = e.taxid')->where("t.grheaderid = " . $_POST['grheaderid'])->queryRow();
    }
      $addressbookid   = '';
      $paymentmethodid = '';
      $taxid           = '';
      $amount          = 0;
      foreach ($cmd as $data) {
        $addressbookid   = $cmd['addressbookid'];
        $paymentmethodid = $cmd['paymentmethodid'];
        $taxid           = $cmd['taxid'];
        $amount          = $cmd['amount'];
      }
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
				if(isset($_POST['pogr'])){
						$sql = 'call GenerateAPGR(:vhid, :vgrheaderid)';
						$command = $connection->createCommand($sql);
						$command->bindvalue(':vhid',$_POST['hid'],PDO::PARAM_INT);
						$command->bindvalue(':vgrheaderid',$_POST['grheaderid'],PDO::PARAM_INT);
				}else{
					$sql     = 'call GenerateAPPO(:vid, :vhid)';
					$command = $connection->createCommand($sql);
					$command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
					$command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
				}
				$command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success',
            'addressbookid' => $addressbookid,
            'paymentmethodid' => $paymentmethodid,
            'taxid' => $taxid,
            'amount' => $amount
          ));
        }
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage('failure', $e->getMessage());
      }
    Yii::app()->end();
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveInvoiceAP(:vid,:vcreatedby)';
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
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteAP(:vid,:vcreatedby)';
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
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoiceap(:vid,:vcreatedby)';
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
  public function actionPurgematerial() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoiceapmat(:vid,:vcreatedby)';
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
  public function actionPurgejurnal() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoiceapjurnal(:vid,:vcreatedby)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select journalno,invoiceapid,invoiceno,f.pono,fullname,amount,symbol,currencyrate,a.invoicedate,concat('Pencatatan Invoice Supplier No ',invoiceno) as headernote, taxvalue,a.recordstatus,
	   (select addressname from address e where e.addressbookid = f.addressbookid limit 1) as addressname,
	   (select cityname from address e left join city f on f.cityid = e.cityid where e.addressbookid = f.addressbookid limit 1) as cityname,a.companyid
		from invoiceap a 
		left join poheader f on f.poheaderid = a.poheaderid
		left join currency b on b.currencyid = a.currencyid 
		left join tax c on c.taxid = a.taxid 
		left join addressbook d on d.addressbookid = f.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.invoiceapid in (" . $_GET['id'] . ")";
    }
    $sql              = $sql . " order by invoiceapid ";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Journal Adjustment';
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'PO No: ' . $row['pono']);
      $this->pdf->text(120, $this->pdf->gety() + 5, 'Tanggal: ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])));
      $this->pdf->text(15, $this->pdf->gety() + 10, 'J.NO: ' . $row['journalno']);
      $this->pdf->text(120, $this->pdf->gety() + 10, 'Supplier: ' . $row['fullname']);
      $sql1        = "select accountcode, accountname,debet,credit,a.currencyid,currencyrate,a.description,symbol,e.plantcode
        from invoiceapjurnal a
		left join currency b on b.currencyid = a.currencyid
		left join account d on d.accountid = a.accountid 
        left join plant e on e.plantid = a.plantid 
        where invoiceapid = " . $row['invoiceapid'] . " order by debet desc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->SetY($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        40,
        30,
        30,
        10,
        50
      ));
      $this->pdf->colheader = array(
        'Account Code',
        'Account Name',
        'Debit',
        'Credit',
        'Rate',
        'Description'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'L'
      );
      $debit                     = 0;
      $credit                    = 0;
      foreach ($dataReader1 as $row1) {
        $debit  = $debit + ($row1['debet'] * $row1['currencyrate']);
        $credit = $credit + ($row1['credit'] * $row1['currencyrate']);
        $this->pdf->row(array(
          $row1['accountcode'],
          $row1['accountname'].' '.$row1['plantcode'],
          Yii::app()->format->formatCurrency($row1['debet']),
          Yii::app()->format->formatCurrency($row1['credit']),
          Yii::app()->format->formatCurrency($row1['currencyrate']),
          $row1['description']
        ));
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatCurrency($debit),
        Yii::app()->format->formatCurrency($credit),
        '',
        ''
      ));
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->setwidths(array(
        15,
        170
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 1);
      $this->pdf->setwidths(array(
        15,
        170
      ));
      $this->pdf->row(array(
        'Nilai',
        Yii::app()->numberFormatter->formatCurrency($row['amount'], $row['symbol'])
      ));
      $this->pdf->checkNewPage(20);
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 45, 'Prepared By');
      $this->pdf->text(10, $this->pdf->gety() + 75, '__________________');
      $this->pdf->text(90, $this->pdf->gety() + 45, 'Approved By');
      $this->pdf->text(90, $this->pdf->gety() + 75, '__________________');
      $this->pdf->text(150, $this->pdf->gety() + 45, 'Received By');
      $this->pdf->text(150, $this->pdf->gety() + 75, '__________________');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownload();
    $sql = "select invoiceno,invoicedate,poheaderid,addressbookid,amount,currencyid,currencyrate,paymentmethodid,taxid,taxno,taxdate,recordstatus
				from invoiceap a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.invoiceapid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('invoiceno'))->setCellValueByColumnAndRow(1, 1, GetCatalog('invoicedate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('poheaderid'))->setCellValueByColumnAndRow(3, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('amount'))->setCellValueByColumnAndRow(5, 1, GetCatalog('currencyid'))->setCellValueByColumnAndRow(6, 1, GetCatalog('currencyrate'))->setCellValueByColumnAndRow(7, 1, GetCatalog('paymentmethodid'))->setCellValueByColumnAndRow(8, 1, GetCatalog('taxid'))->setCellValueByColumnAndRow(9, 1, GetCatalog('taxno'))->setCellValueByColumnAndRow(10, 1, GetCatalog('taxdate'))->setCellValueByColumnAndRow(11, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['invoiceno'])->setCellValueByColumnAndRow(1, $i + 1, $row1['invoicedate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['poheaderid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['amount'])->setCellValueByColumnAndRow(5, $i + 1, $row1['currencyid'])->setCellValueByColumnAndRow(6, $i + 1, $row1['currencyrate'])->setCellValueByColumnAndRow(7, $i + 1, $row1['paymentmethodid'])->setCellValueByColumnAndRow(8, $i + 1, $row1['taxid'])->setCellValueByColumnAndRow(9, $i + 1, $row1['taxno'])->setCellValueByColumnAndRow(10, $i + 1, $row1['taxdate'])->setCellValueByColumnAndRow(11, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="invoiceap.xlsx"');
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
