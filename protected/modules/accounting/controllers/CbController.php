<?php
class CbController extends Controller {
  public $menuname = 'cb';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexacc() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchacc();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexcheque() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchcheque();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexbank() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchbank();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		parent::actionIndex();
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into  cb (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inscb').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'cbid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $cbid           = isset($_POST['cbid']) ? $_POST['cbid'] : '';
    $cashbankno      = isset($_POST['cashbankno']) ? $_POST['cashbankno'] : '';
    $receiptno       = isset($_POST['receiptno']) ? $_POST['receiptno'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cbid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appcb')")->queryScalar();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cb t')
		->join('company a', 'a.companyid=t.companyid')->where("((coalesce(t.cashbankno,'') like :cashbankno) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.headernote,'') like :headernote) and
						(coalesce(a.companyname,'') like :companyid) and
						(coalesce(t.cbid,'') like :cbid) and
						(coalesce(t.receiptno,'') like :receiptno))
						and t.recordstatus < {$maxstat}
						and t.recordstatus in (".getUserRecordStatus('listcb').")
						and t.companyid in (".getUserObjectWfValues('company','listcb').")
						and t.isin in (".getUserObjectValues('cashbank').")
						", array(
      ':cashbankno' => '%' . $cashbankno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cbid' => '%' . $cbid . '%',
      ':headernote' => '%' . $headernote . '%',
      ':companyid' => '%' . $companyid . '%',
      ':receiptno' => '%' . $receiptno . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyname')->from('cb t')
		->leftjoin('company a', 'a.companyid=t.companyid')->where("((coalesce(t.cashbankno,'') like :cashbankno) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.headernote,'') like :headernote) and
						(coalesce(a.companyname,'') like :companyid) and
						(coalesce(t.cbid,'') like :cbid) and
						(coalesce(t.receiptno,'') like :receiptno))
						and t.recordstatus < {$maxstat}
						and t.recordstatus in (".getUserRecordStatus('listcb').")
						and t.companyid in (".getUserObjectWfValues('company','listcb').")
						and t.isin in (".getUserObjectValues('cashbank').")
						", array(
      ':cashbankno' => '%' . $cashbankno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cbid' => '%' . $cbid . '%',
      ':headernote' => '%' . $headernote . '%',
      ':companyid' => '%' . $companyid . '%',
      ':receiptno' => '%' . $receiptno . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbid' => $data['cbid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'cashbankno' => $data['cashbankno'],
        'receiptno' => $data['receiptno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'isin' => $data['isin'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchacc()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cbaccid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbacc t')->leftjoin('account a', 'a.accountid=t.debitaccid')->leftjoin('account b', 'b.accountid=t.creditaccid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->leftjoin('cheque d', 'd.chequeid=t.chequeid')->leftjoin('employee e', 'e.employeeid=t.employeeid')->leftjoin('addressbook f', 'f.addressbookid=t.customerid')->leftjoin('addressbook g', 'g.addressbookid=t.supplierid')->leftjoin('plant h', 'h.plantid=t.debplantid')->leftjoin('plant i', 'i.plantid=t.credplantid')->where('cbid = :cbid', array(
      ':cbid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,d.chequeid,d.chequeno,a.accountid as debitaccid,b.accountid as creditaccid,a.accountname as accdebitname,b.accountname as acccreditname,c.currencyname,e.fullname, f.fullname as customername, g.fullname as suppliername,f.addressbookid as customerid, g.addressbookid as suppid, h.plantcode as debplantcode,i.plantcode as credplantcode')->from('cbacc t')->leftjoin('account a', 'a.accountid=t.debitaccid')->leftjoin('account b', 'b.accountid=t.creditaccid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->leftjoin('cheque d', 'd.chequeid=t.chequeid')->leftjoin('employee e','e.employeeid=t.employeeid')->leftjoin('addressbook f', 'f.addressbookid=t.customerid')->leftjoin('addressbook g', 'g.addressbookid=t.supplierid')->leftjoin('plant h', 'h.plantid=t.debplantid')->leftjoin('plant i', 'i.plantid=t.credplantid')->where('cbid = :cbid', array(
      ':cbid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbaccid' => $data['cbaccid'],
        'cbid' => $data['cbid'],
        'debplantid' => $data['debplantid'],
        'credplantid' => $data['credplantid'],
        'debplantcode' => $data['debplantcode'],
        'credplantcode' => $data['credplantcode'],
        'debitaccid' => $data['debitaccid'],
        'creditaccid' => $data['creditaccid'],
        'accdebitname' => $data['accdebitname'],
        'acccreditname' => $data['acccreditname'],
        'employeeid' => $data['employeeid'],
        'fullname' => $data['fullname'],
        'chequeid' => $data['chequeid'],
        'chequeno' => $data['chequeno'],
        'customerid' => $data['customerid'],
        'customername' => $data['customername'],
        'supplierid' => $data['supplierid'],
        'suppliername' => $data['suppliername'],
        'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
        'tgltolak' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tgltolak'])),
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		    $cmd             = Yii::app()->db->createCommand()->select('
		sum(t.amount) as amount')->from('cbacc t')->join('account a', 'a.accountid=t.debitaccid')->join('account b', 'b.accountid=t.creditaccid')->join('currency c', 'c.currencyid=t.currencyid')->leftjoin('cheque d', 'd.chequeid=t.chequeid')->leftjoin('employee e','e.employeeid=t.employeeid')->where('cbid = :cbid', array(
      ':cbid' => $id
    ))->queryScalar();
    $footer[] = array(
      'accdebitname' => 'Total',
      'amount' => Yii::app()->format->formatNumber($cmd)
    );
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchcheque()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $chequeid      = isset($_POST['chequeid']) ? $_POST['chequeid'] : '';
    $companyid     = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $tglbayar      = isset($_POST['tglbayar']) ? $_POST['tglbayar'] : '';
    $chequeno      = isset($_POST['chequeno']) ? $_POST['chequeno'] : '';
    $bankid        = isset($_POST['bankid']) ? $_POST['bankid'] : '';
    $tglcheque     = isset($_POST['tglcheque']) ? $_POST['tglcheque'] : '';
    $tgltempo      = isset($_POST['tgltempo']) ? $_POST['tgltempo'] : '';
    $tglcair       = isset($_POST['tglcair']) ? $_POST['tglcair'] : '';
    $tgltolak      = isset($_POST['tgltolak']) ? $_POST['tgltolak'] : '';
    $addressbookid = isset($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
    $iscustomer    = isset($_POST['iscustomer']) ? $_POST['iscustomer'] : '';
    $chequeid      = isset($_GET['q']) ? $_GET['q'] : $chequeid;
    $companyid     = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $tglbayar      = isset($_GET['q']) ? $_GET['q'] : $tglbayar;
    $chequeno      = isset($_GET['q']) ? $_GET['q'] : $chequeno;
    $bankid        = isset($_GET['q']) ? $_GET['q'] : $bankid;
    $tglcheque     = isset($_GET['q']) ? $_GET['q'] : $tglcheque;
    $tgltempo      = isset($_GET['q']) ? $_GET['q'] : $tgltempo;
    $tglcair       = isset($_GET['q']) ? $_GET['q'] : $tglcair;
    $tgltolak      = isset($_GET['q']) ? $_GET['q'] : $tgltolak;
    $addressbookid = isset($_GET['q']) ? $_GET['q'] : $addressbookid;
    $iscustomer    = isset($_GET['q']) ? $_GET['q'] : $iscustomer;
    $page          = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows          = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort          = isset($_POST['sort']) ? strval($_POST['sort']) : 't.chequeid';
    $order         = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page          = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows          = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort          = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order         = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset        = ($page - 1) * $rows;
    $result        = array();
    $row           = array();
    if (isset($_GET['trx'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('cheque t')->join('addressbook a', 'a.addressbookid=t.addressbookid')->join('currency b', 'b.currencyid=t.currencyid')->join('bank c', 'c.bankid=t.bankid')->join('company d', 'd.companyid=t.companyid')->where("((t.chequeid like :chequeid) or (t.chequeno like :chequeno) or (d.companyname like :companyid) or (c.bankname like :bankid) or 
			(t.tglbayar like :tglbayar) or (t.tglcheque like :tglcheque) or (t.tglcair like :tglcair) or (t.tgltolak like :tgltolak)) and 
			(t.tglcair is null or t.tglcair = '1970-01-01') and (t.tgltolak is null or t.tgltolak = '1970-01-01') and t.recordstatus=2 and t.iscustomer in(".$_GET['iscustomer'].") and t.companyid = ".$_REQUEST['companyid']." and
			t.companyid in (select gm.menuvalueid from groupaccess c
			inner join usergroup d on d.groupaccessid = c.groupaccessid
			inner join useraccess e on e.useraccessid = d.useraccessid
			inner join groupmenuauth gm on gm.groupaccessid = c.groupaccessid
			inner join menuauth ma on ma.menuauthid = gm.menuauthid
			where upper(e.username)=upper('" . Yii::app()->user->name . "') and upper(ma.menuobject) = upper('company'))", array(
        ':chequeid' => '%' . $chequeid . '%',
        ':chequeno' => '%' . $chequeno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':bankid' => '%' . $bankid . '%',
        ':tglbayar' => '%' . $tglbayar . '%',
        ':tglcheque' => '%' . $tglcheque . '%',
        ':tglcair' => '%' . $tglcair . '%',
        ':tgltolak' => '%' . $tgltolak . '%'
      ))->queryScalar();
    } if(isset($_GET['trxcom'])) {
      $cmd = Yii::app()->db->createCommand()
          ->select('ifnull(count(1),0)')
          ->from('cheque t')
          ->join('addressbook a','a.addressbookid = t.addressbookid')
          ->join('currency b','b.currencyid = t.currencyid')
          ->join('bank c','c.bankid = t.bankid')
          ->join('company d','d.companyid = t.companyid')
          ->where("t.recordstatus=getwfmaxstatbywfname('appcheq') and t.companyid in (".getUserObjectValues('company').") and t.iscustomer in(".$_REQUEST['iscustomer'].") and t.chequeid = ".$_REQUEST['chequeid'])
          ->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('cheque t')->join('addressbook a', 'a.addressbookid=t.addressbookid')->join('currency b', 'b.currencyid=t.currencyid')->join('bank c', 'c.bankid=t.bankid')->join('company d', 'd.companyid=t.companyid')->where("((t.chequeid like :chequeid) or (t.chequeno like :chequeno) or (d.companyname like :companyid) or (c.bankname like :bankid) or 
			(t.tglbayar like :tglbayar) or (t.tglcheque like :tglcheque) or (t.tglcair like :tglcair) or (t.tgltolak like :tgltolak)) and 
			t.recordstatus=2 and
			t.companyid in (select gm.menuvalueid from groupaccess c
			inner join usergroup d on d.groupaccessid = c.groupaccessid
			inner join useraccess e on e.useraccessid = d.useraccessid
			inner join groupmenuauth gm on gm.groupaccessid = c.groupaccessid
			inner join menuauth ma on ma.menuauthid = gm.menuauthid
			where upper(e.username)=upper('" . Yii::app()->user->name . "') and upper(ma.menuobject) = upper('company'))", array(
        ':chequeid' => '%' . $chequeid . '%',
        ':chequeno' => '%' . $chequeno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':bankid' => '%' . $bankid . '%',
        ':tglbayar' => '%' . $tglbayar . '%',
        ':tglcheque' => '%' . $tglcheque . '%',
        ':tglcair' => '%' . $tglcair . '%',
        ':tgltolak' => '%' . $tgltolak . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['trx'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.fullname,b.currencyname,c.bankname,d.companyname')->from('cheque t')->join('addressbook a', 'a.addressbookid=t.addressbookid')->join('currency b', 'b.currencyid=t.currencyid')->join('bank c', 'c.bankid=t.bankid')->join('company d', 'd.companyid=t.companyid')->where("((t.chequeid like :chequeid) or (t.chequeno like :chequeno) or (d.companyname like :companyid) or (c.bankname like :bankid) or 
			(t.tglbayar like :tglbayar) or (t.tglcheque like :tglcheque) or (t.tglcair like :tglcair) or (t.tgltolak like :tgltolak)) and 
			(t.tglcair is null or t.tglcair = '1970-01-01') and (t.tgltolak is null or t.tgltolak = '1970-01-01') and t.recordstatus=2 and t.iscustomer in(".$_GET['iscustomer'].") and t.companyid = ".$_REQUEST['companyid']." and
			t.companyid in (select gm.menuvalueid from groupaccess c
			inner join usergroup d on d.groupaccessid = c.groupaccessid
			inner join useraccess e on e.useraccessid = d.useraccessid
			inner join groupmenuauth gm on gm.groupaccessid = c.groupaccessid
			inner join menuauth ma on ma.menuauthid = gm.menuauthid
			where upper(e.username)=upper('" . Yii::app()->user->name . "') and upper(ma.menuobject) = upper('company'))", array(
        ':chequeid' => '%' . $chequeid . '%',
        ':chequeno' => '%' . $chequeno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':bankid' => '%' . $bankid . '%',
        ':tglbayar' => '%' . $tglbayar . '%',
        ':tglcheque' => '%' . $tglcheque . '%',
        ':tglcair' => '%' . $tglcair . '%',
        ':tgltolak' => '%' . $tgltolak . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if(isset($_GET['trxcom'])) {
      $cmd = Yii::app()->db->createCommand()
          ->select('t.*, a.fullname, b.currencyname, c.bankname,d.companyname')
          ->from('cheque t')
          ->join('addressbook a','a.addressbookid = t.addressbookid')
          ->join('currency b','b.currencyid = t.currencyid')
          ->join('bank c','c.bankid = t.bankid')
          ->join('company d','d.companyid = t.companyid')
          ->where("t.recordstatus=getwfmaxstatbywfname('appcheq') and t.companyid in (".getUserObjectValues('company').") and t.iscustomer in(".$_REQUEST['iscustomer'].") and t.chequeid = ".$_REQUEST['chequeid'])
          ->offset($offset)
          ->limit($rows)
          ->order($sort.' '.$order)
          ->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.fullname,b.currencyname,c.bankname,d.companyname')->from('cheque t')->join('addressbook a', 'a.addressbookid=t.addressbookid')->join('currency b', 'b.currencyid=t.currencyid')->join('bank c', 'c.bankid=t.bankid')->join('company d', 'd.companyid=t.companyid')->where("((t.chequeid like :chequeid) or (t.chequeno like :chequeno) or (d.companyname like :companyid) or (c.bankname like :bankid) or 
			(t.tglbayar like :tglbayar) or (t.tglcheque like :tglcheque) or (t.tglcair like :tglcair) or (t.tgltolak like :tgltolak)) and 
			t.recordstatus=2 and 
			t.companyid in (select gm.menuvalueid from groupaccess c
			inner join usergroup d on d.groupaccessid = c.groupaccessid
			inner join useraccess e on e.useraccessid = d.useraccessid
			inner join groupmenuauth gm on gm.groupaccessid = c.groupaccessid
			inner join menuauth ma on ma.menuauthid = gm.menuauthid
			where upper(e.username)=upper('" . Yii::app()->user->name . "') and upper(ma.menuobject) = upper('company'))", array(
        ':chequeid' => '%' . $chequeid . '%',
        ':chequeno' => '%' . $chequeno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':bankid' => '%' . $bankid . '%',
        ':tglbayar' => '%' . $tglbayar . '%',
        ':tglcheque' => '%' . $tglcheque . '%',
        ':tglcair' => '%' . $tglcair . '%',
        ':tgltolak' => '%' . $tgltolak . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'chequeid' => $data['chequeid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'tglbayar' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglbayar'])),
        'chequeno' => $data['chequeno'],
        'bankid' => $data['bankid'],
        'bankname' => $data['bankname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'amountold' => $data['amount'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'tglcheque' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcheque'])),
        'tgltempo' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tgltempo'])),
        'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
        'tgltolak' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tgltolak'])),
        'addressbookid' => $data['addressbookid'],
        'iscustomer' => $data['iscustomer'],
        'fullname' => $data['fullname'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchbank()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $bankid          = isset($_POST['bankid']) ? $_POST['bankid'] : '';
    $bankname        = isset($_POST['bankname']) ? $_POST['bankname'] : '';
    $recordstatus    = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $bankid          = isset($_GET['q']) ? $_GET['q'] : $bankid;
    $bankname        = isset($_GET['q']) ? $_GET['q'] : $bankname;
    $recordstatus    = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'bankid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('bank t')->where("(bankname like :bankname) 
			and t.recordstatus =1", array(
      ':bankname' => '%' . $bankname . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*')->from('bank t')->where("(bankname like :bankname) 
			and t.recordstatus =1", array(
      ':bankname' => '%' . $bankname . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'bankid' => $data['bankid'],
        'bankname' => $data['bankname'],
        'recordstatus' => $data['recordstatus']
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
        $sql     = 'call Insertcb(:vcompanyid,:vdocdate,:vreceiptno,:visin,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecb(:vid,:vcompanyid,:vdocdate,:vreceiptno,:visin,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vreceiptno', $_POST['receiptno'], PDO::PARAM_STR);
      $status = isset($_POST['isin']) ? ($_POST['isin'] == "on") ? 1 : 0 : 0;
      $command->bindvalue(':visin', $status, PDO::PARAM_STR);
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
  public function actionSaveacc()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
      
    if(isset($_POST['employeeid'])&& $_POST['employeeid']==''){
          $employeeid = 0;
    }else{
          $employeeid = $_POST['employeeid'];
    }
    
    if(isset($_POST['customerid'])&& $_POST['customerid']==''){
          $customerid = 0;
    }else{
          $customerid = $_POST['customerid'];
    }
      
    if(isset($_POST['supplierid'])&& $_POST['supplierid']==''){
          $supplierid = 0;
    }else{
          $supplierid = $_POST['supplierid'];
    }
      
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertcbacc(:vcbid,:vdebplantid,:vdebitaccid,:vdescription,:vamount,:vcurrencyid,:vcurrencyrate,:vemployeeid,:vcustomerid,:vsupplierid,:vchequeid,:vtglcair,:vtgltolak,:vcredplantid,:vcreditaccid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecbacc(:vid,:vcbid,:vdebplantid,:vdebitaccid,:vdescription,:vamount,:vcurrencyid,:vcurrencyrate,:vemployeeid,:vcustomerid,:vsupplierid,:vchequeid,:vtglcair,:vtgltolak,:vcredplantid,:vcreditaccid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['cbaccid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['cbaccid']);
      }
      $command->bindvalue(':vcbid', $_POST['cbid'], PDO::PARAM_STR);
      $command->bindvalue(':vdebplantid', $_POST['debplantid'], PDO::PARAM_STR);
      $command->bindvalue(':vdebitaccid', $_POST['debitaccid'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
      $command->bindvalue(':vamount', $_POST['amount'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vemployeeid', $employeeid, PDO::PARAM_STR);
      $command->bindvalue(':vcustomerid', $customerid, PDO::PARAM_STR);
      $command->bindvalue(':vsupplierid', $supplierid, PDO::PARAM_STR);
      $command->bindvalue(':vchequeid', $_POST['chequeid'], PDO::PARAM_STR);
      $command->bindvalue(':vtglcair', date(Yii::app()->params['datetodb'], strtotime($_POST['tglcair'])), PDO::PARAM_STR);
      $command->bindvalue(':vtgltolak', date(Yii::app()->params['datetodb'], strtotime($_POST['tgltolak'])), PDO::PARAM_STR);
      $command->bindvalue(':vcredplantid', $_POST['credplantid'], PDO::PARAM_STR);
      $command->bindvalue(':vcreditaccid', $_POST['creditaccid'], PDO::PARAM_STR);
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
  public function actionSavecheque()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertcheque(:vcompanyid,:vtglbayar,:vchequeno,:vbankid,:vamount,:vcurrencyid,:vcurrencyrate,:vtglcheque,:vtgltempo,:vaddressbookid,:viscustomer,:vtglcair,:vtgltolak,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatecheque(:vid,:vcompanyid,:vtglbayar,:vchequeno,:vbankid,:vamount,:vcurrencyid,:vcurrencyrate,:vtglcheque,:vtgltempo,:vaddressbookid,:viscustomer,:vtglcair,:vtgltolak,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['chequeid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['chequeid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vtglbayar', date(Yii::app()->params['datetodb'], strtotime($_POST['tglbayar'])), PDO::PARAM_STR);
      $command->bindvalue(':vchequeno', $_POST['chequeno'], PDO::PARAM_STR);
      $command->bindvalue(':vbankid', $_POST['bankid'], PDO::PARAM_STR);
      $command->bindvalue(':vamount', $_POST['amount'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vtglcheque', date(Yii::app()->params['datetodb'], strtotime($_POST['tglcheque'])), PDO::PARAM_STR);
      $command->bindvalue(':vtgltempo', date(Yii::app()->params['datetodb'], strtotime($_POST['tgltempo'])), PDO::PARAM_STR);
      $command->bindvalue(':vaddressbookid', $_POST['addressbookid'], PDO::PARAM_STR);
      $command->bindvalue(':viscustomer', $_POST['iscustomer'], PDO::PARAM_STR);
      $command->bindvalue(':vtglcair', date(Yii::app()->params['datetodb'], strtotime($_POST['tglcair'])), PDO::PARAM_STR);
      $command->bindvalue(':vtgltolak', date(Yii::app()->params['datetodb'], strtotime($_POST['tgltolak'])), PDO::PARAM_STR);
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
  public function actionSavebank()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertbank(:vbankname,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatebank(:vid,:vbankname,:vrecordstatus, :vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['bankid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['bankid']);
      }
      $command->bindvalue(':vbankname', $_POST['bankname'], PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $_POST['recordstatus'], PDO::PARAM_STR);
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
        $sql     = 'call Purgecb(:vid,:vcreatedby)';
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
  public function actionPurgeacc()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecbacc(:vid,:vcreatedby)';
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
  public function actionPurgecheque()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgecheque(:vid,:vcreatedby)';
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
  public function actionPurgebank()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebank(:vid,:vcreatedby)';
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
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvecb(:vid,:vcreatedby)';
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
        $sql     = 'call Deletecb(:vid,:vcreatedby)';
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
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select *,a.cashbankno,a.docdate,a.receiptno
			from cb a
			join company b on b.companyid = a.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cbid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('cashbank');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No. Transaksi ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['cashbankno']);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'No Kwitansi ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . $row['receiptno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tanggal ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $sql1        = "select b.accountname as accdebitname,c.accountname as acccreditname,case when a.employeeid <> 0 then concat(a.description,' - ',f.fullname) else a.description end as description,a.amount,d.currencyname,
					d.symbol,e.chequeno,a.currencyrate,f.fullname,
					case when a.tglcair = '1970-01-01' then null else a.tglcair end as tglcair,
					case when a.tgltolak = '1970-01-01' then null else a.tgltolak end as tgltolak
					from cbacc a
					join account b on b.accountid = a.debitaccid
					join account c on c.accountid = a.creditaccid
					left join currency d on d.currencyid = a.currencyid
					left join cheque e on e.chequeid=a.chequeid
          left join employee f on f.employeeid=a.employeeid
					where a.cbid = " . $row['cbid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
        'C',
        'L',
        'L',
        'L',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        7,
        45,
        25,
        38,
        25,
        15,
        20,
        15,
        15
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun Debit',
        'Akun Credit',
        'Uraian',
        'Nilai',
        'Kurs',
        'No. Cek/Giro',
        'Tgl. Cair',
        'Tgl. Tolak'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'R',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      $amount                    = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['accdebitname'],
          $row1['acccreditname'],
          $row1['description'],
          $row1['symbol'].Yii::app()->format->formatCurrency($row1['amount']),
          Yii::app()->format->formatCurrency($row1['currencyrate']),
          $row1['chequeno'],
          (($row1['tglcair'] !== null) ? date(Yii::app()->params['dateviewfromdb'], strtotime($row1['tglcair'])) : ''),
          (($row1['tgltolak'] !== null) ? date(Yii::app()->params['dateviewfromdb'], strtotime($row1['tgltolak'])) : '')
        ));
        $amount += $row1['amount'];
      }
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        '',
        '',
        '',
        'JUMLAH',
        $row1['symbol'].Yii::app()->format->formatCurrency($amount),
        '',
        '',
        '',
        ''
      ));
      $bilangan = explode(".", $amount);
      $this->pdf->sety($this->pdf->gety() + 0);
      $this->pdf->setFont('Arial', 'I', 8);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        7,
        200
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        '',
        'Terbilang : ' . eja($bilangan[0]) . ' ' . $row1['currencyname']
      ));
      $this->pdf->setFont('Arial', 'BI', 8);
      $this->pdf->row(array(
        '',
        'NOTE : ' . $row['headernote']
      ));
      $this->pdf->checkNewPage(15);
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Disetujui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '  Admin Kasir');
      $this->pdf->text(55, $this->pdf->gety() + 25, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select receiptno,docdate,recordstatus
				from cb a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cbid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('receiptno'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['receiptno'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="cb.xlsx"');
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
