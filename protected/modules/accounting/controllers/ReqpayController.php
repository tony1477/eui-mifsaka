<?php
class ReqpayController extends Controller
{
  public $menuname = 'reqpay';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexinvoice()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchinvoice();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into reqpay (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inspayreq').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'reqpayid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $reqpayid   = isset($_POST['reqpayid']) ? $_POST['reqpayid'] : '';
    $docdate    = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $reqpayno   = isset($_POST['reqpayno']) ? $_POST['reqpayno'] : '';
    $headernote = isset($_POST['headernote']) ? $_POST['headernote'] : '';
		$companyid      = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $reqpayid   = isset($_GET['q']) ? $_GET['q'] : $reqpayid;
    $docdate    = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $reqpayno   = isset($_GET['q']) ? $_GET['q'] : $reqpayno;
    $headernote = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $companyid = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'reqpayid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
				
    if (isset($_GET['cashbankout'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpay t')->leftjoin('company a', 'a.companyid = t.companyid')->where("((docdate like :docdate) or
                            (reqpayno like :reqpayno)) and t.recordstatus=getwfmaxstatbywfname('apppayreq') and t.companyid = '".$_GET['companyid']."' and t.recordstatus in (".getUserRecordStatus('listpayreq').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':reqpayno' => '%' . $reqpayno . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpay t')
			->leftjoin('company a', 'a.companyid = t.companyid')
			->where("(coalesce(docdate,'') like :docdate) 
			and (coalesce(reqpayno,'') like :reqpayno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(reqpayid,'') like :reqpayid) 
			and (coalesce(companyname,'') like :companyid) and t.recordstatus < getwfmaxstatbywfname('apppayreq') and t.recordstatus in (".getUserRecordStatus('listpayreq').") and 
            t.companyid in (".getUserObjectWfValues('company','listpayreq').")", array(
        ':docdate' => '%' . $docdate . '%',
      ':headernote' => '%' . $headernote . '%',
      ':reqpayid' => '%' . $reqpayid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['cashbankout'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname')->from('reqpay t')->leftjoin('company a', 'a.companyid = t.companyid')->where("((docdate like :docdate) or
                            (reqpayno like :reqpayno)) and t.recordstatus=getwfmaxstatbywfname('apppayreq') and t.companyid = '".$_GET['companyid']."' and t.recordstatus in (".getUserRecordStatus('listpayreq').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':reqpayno' => '%' . $reqpayno . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname')->from('reqpay t')
			->leftjoin('company a', 'a.companyid = t.companyid')
		->where("(coalesce(docdate,'') like :docdate) 
			and (coalesce(reqpayno,'') like :reqpayno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(reqpayid,'') like :reqpayid) 
			and (coalesce(companyname,'') like :companyid) and t.recordstatus < getwfmaxstatbywfname('apppayreq') and t.recordstatus in (".getUserRecordStatus('listpayreq').") and 
            t.companyid in (".getUserObjectWfValues('company','listpayreq').")", array(
        ':docdate' => '%' . $docdate . '%',
      ':headernote' => '%' . $headernote . '%',
      ':reqpayid' => '%' . $reqpayid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'reqpayid' => $data['reqpayid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'reqpayno' => $data['reqpayno'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
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
  public function actionSearchinvoice()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'reqpayinvid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpayinv t')->join('invoiceap a', 'a.invoiceapid=t.invoiceapid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('poheader c', 'c.poheaderid=a.poheaderid')->join('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->join('currency e', 'e.currencyid=t.currencyid')->leftjoin('tax f', 'f.taxid=t.taxid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.invoiceno,b.fullname as supplier,a.invoicedate,
													adddate(a.invoicedate,d.paydays) as duedate,t.amount,t.payamount as saldo,e.currencyname,f.taxcode')->from('reqpayinv t')->join('invoiceap a', 'a.invoiceapid=t.invoiceapid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('poheader c', 'c.poheaderid=a.poheaderid')->join('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->join('currency e', 'e.currencyid=t.currencyid')->leftjoin('tax f', 'f.taxid=t.taxid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'reqpayinvid' => $data['reqpayinvid'],
        'reqpayid' => $data['reqpayid'],
        'invoiceapid' => $data['invoiceapid'],
        'invoiceno' => $data['invoiceno'],
        'supplier' => $data['supplier'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
        'taxno' => $data['taxno'],
        'taxdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['taxdate'])),
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'payamount' => Yii::app()->format->formatNumber($data['payamount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'bankaccountno' => $data['bankaccountno'],
        'bankname' => $data['bankname'],
        'bankowner' => $data['bankowner'],
        'itemnote' => $data['itemnote'],
        'saldo' => Yii::app()->format->formatNumber($data['saldo'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $cmd             = Yii::app()->db->createCommand()->select('sum(t.amount) as amount,
		sum(t.payamount) as saldo')->from('reqpayinv t')->join('invoiceap a', 'a.invoiceapid=t.invoiceapid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('poheader c', 'c.poheaderid=a.poheaderid')->join('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->join('currency e', 'e.currencyid=t.currencyid')->leftjoin('tax f', 'f.taxid=t.taxid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryRow();
		$footer[] = array(
      'invoiceno' => 'Total',
      'amount' => Yii::app()->format->formatNumber($cmd['amount']),
      'saldo' => Yii::app()->format->formatNumber($cmd['saldo']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
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
        $sql     = 'call Insertreqpay(:vcompanyid,:vdocdate,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatereqpay(:vid,:vcompanyid,:vdocdate,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['reqpayid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['reqpayid']);
      }
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(true, 'insertsuccess', 1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 1);
    }
  }
  public function actionSaveinvoice()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertreqpayinv(:vreqpayid,:vinvoiceapid,:vtaxid,:vtaxno,:vtaxdate,:vcurrencyid,:vcurrencyrate,:vbankaccountno,:vbankname,:vbankowner,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatereqpayinv(:vid,:vreqpayid,:vinvoiceapid,:vtaxid,:vtaxno,:vtaxdate,:vcurrencyid,:vcurrencyrate,:vbankaccountno,:vbankname,:vbankowner,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['reqpayinvid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['reqpayinvid']);
      }
      $command->bindvalue(':vreqpayid', $_POST['reqpayid'], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceapid', $_POST['invoiceapid'], PDO::PARAM_STR);
      $command->bindvalue(':vtaxid', $_POST['taxid'], PDO::PARAM_STR);
      $command->bindvalue(':vtaxno', $_POST['taxno'], PDO::PARAM_STR);
      $command->bindvalue(':vtaxdate', date(Yii::app()->params['datetodb'], strtotime($_POST['taxdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vbankaccountno', $_POST['bankaccountno'], PDO::PARAM_STR);
      $command->bindvalue(':vbankname', $_POST['bankname'], PDO::PARAM_STR);
      $command->bindvalue(':vbankowner', $_POST['bankowner'], PDO::PARAM_STR);
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
        $sql     = 'call DeleteReqpay(:vid,:vcreatedby)';
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
  private function SendNotifWa($menuname,$idarray)
  {
    // getrecordstatus
		$ids = null;
		if(is_array($idarray)==TRUE) {
			foreach($idarray as $id) {
				$sql = "select reqpayid
							from reqpay
							where recordstatus = getwfmaxstatbywfname('apppayreq') and reqpayid = ".$id;
				if($ids == null) {
					$ids = Yii::app()->db->createCommand($sql)->queryScalar();
				}
				else
				{
					$ids .= ','.Yii::app()->db->createCommand($sql)->queryScalar();
				}
				//var_dump($idarray);
			}
			// get customer number
			if($ids == null) {
				foreach($idarray as $id) {
					$sql = "select reqpayid
								from reqpay
								where reqpayid = ".$id;
					if($ids == null) {
						$ids = Yii::app()->db->createCommand($sql)->queryScalar();
					}
					else
					{
						$ids .= ','.Yii::app()->db->createCommand($sql)->queryScalar();
					}
					//var_dump($idarray);
				}
				$getSalesOrder = "select a.reqpayid,c.fullname,a.docdate AS podate,d.companycode,(SELECT SUM((a1.poqty * a1.netprice * a1.ratevalue) * ((100+b.taxvalue)/100)) FROM podetail a1 WHERE a1.poheaderid=a.poheaderid) AS povalue,e.paycode,c.hutang,a.statusname
								from reqpay a
								join tax b on b.taxid = a.taxid
								join addressbook c on c.addressbookid = a.addressbookid
								join company d on d.companyid = a.companyid
								JOIN paymentmethod e ON e.paymentmethodid=a.paymentmethodid
								where a.reqpayid IN ({$ids})
								group by reqpayid
				";

				$res = Yii::app()->db->createCommand($getSalesOrder)->queryAll();
				
				foreach($res as $row) {
					$companyid = $row['companyid'];
					$wanumber = $row['wanumber'];
					$telegramid = $row['telegramid'];
					$time = date('Y-m-d H:i:s');
					
					$pesanwa = 
					"ID Purchase Order ".$row['companycode'].": ".$row['poheaderid']."\nTanggal: ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['podate']))."\nSupplier : *".$row['fullname']."\n\nTotal Hutang Rp. ".Yii::app()->format->formatCurrency($row['hutang'])."\nHutang JTT Rp. ".Yii::app()->format->formatCurrency($row['hutang'])."\n\nTelah disetujui oleh bagian terkait dengan status *".$row['statusname']."*, silahkan _*Review*_ lalu _*Approve*_ / _*Reject*_ pada Aplikasi ERP AKA Group.\n\n_*Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".
					$time;
					
					$pesantele = 
					"ID Purchase Order ".$row['companycode'].": ".$row['poheaderid']."\nTanggal: ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['podate']))."\nSupplier : ".$row['fullname']."\n\nTotal Hutang Rp. ".Yii::app()->format->formatCurrency($row['hutang'])."\nHutang JTT Rp. ".Yii::app()->format->formatCurrency($row['hutang'])."\n\nTelah disetujui oleh bagian terkait dengan status ".$row['statusname'].", silahkan Review lalu Approve / Reject pada Aplikasi ERP AKA Group.\n\nPesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)\n".
					$time;
					
					$getWaNumber = "SELECT e.useraccessid,b.groupaccessid,replace(e.wanumber,'+','') as wanumber,e.telegramid
									FROM poheader a
									JOIN wfgroup b ON b.wfbefstat=a.recordstatus AND b.workflowid=129
									JOIN groupmenuauth c ON c.groupaccessid=b.groupaccessid AND c.menuauthid=5 AND c.menuvalueid=a.companyid
									JOIN usergroup d ON d.groupaccessid=c.groupaccessid
									JOIN useraccess e ON e.useraccessid=d.useraccessid AND e.recordstatus=1 AND e.useraccessid<>2 AND e.useraccessid<>106
									-- AND e.useraccessid<>3
									WHERE a.poheaderid = {$row['poheaderid']}
					";
					$res1 = Yii::app()->db->createCommand($getWaNumber)->queryAll();
					
					foreach($res1 as $row1)
					{
						$wanumber = $row1['wanumber'];
						$telegramid = $row1['telegramid'];
						if ($row1['useraccessid'] == 3){$ui=" - eui ".$row1['groupaccessid'];}else{$ui="";}
/*					//Whatsapp Japri
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($pesanwa)."&tujuan=".$wanumber."@s.whatsapp.net",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
*/						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($pesantele.$ui);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
					}
				curl_close($ch);
				}
			}
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
        $sql     = 'call ApproveReqpay(:vid,:vcreatedby)';
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
        $sql     = 'call Purgereqpay(:vid,:vcreatedby)';
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
  public function actionPurgeinvoice()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgereqpayinv(:vid,:vcreatedby)';
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
  public function actiongeneratebank()
  {
    if (isset($_POST['invoiceapid'])) {
      $taxid         = '';
      $taxno         = '';
      $taxdate       = '';
      $bankaccountno = '';
      $bankname      = '';
      $bankowner     = '';
      $cmd           = Yii::app()->db->createCommand()->select('t.*,b.bankaccountno,b.bankname,b.accountowner')->from('invoiceap t')->join('poheader a', 'a.poheaderid=t.poheaderid')->join('addressbook b', 'b.addressbookid=t.addressbookid')->where("invoiceapid = '" . $_POST['invoiceapid'] . "' ")->limit(1)->queryRow();
      $taxid         = $cmd['taxid'];
      $taxno         = $cmd['taxno'];
      $taxdate       = $cmd['taxdate'];
      $bankaccountno = $cmd['bankaccountno'];
      $bankname      = $cmd['bankname'];
      $bankowner     = $cmd['accountowner'];
    }
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'status' => 'success',
        'currencyid' => 40,
        'currencyrate' => 1,
        'bankaccountno' => $bankaccountno,
        'bankname' => $bankname,
        'bankowner' => $bankowner
      ));
      Yii::app()->end();
    }
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select distinct a.reqpayid,a.docdate,d.fullname as supplier,b.bankname,d.bankaccountno,a.companyid,d.accountowner,a.reqpayno,
	  (select sum(za.amount)
				from invoiceap za
				join reqpayinv zb on zb.invoiceapid = za.invoiceapid
				where zb.reqpayid = a.reqpayid) as nilai,a.headernote
				from reqpay a 
				join reqpayinv b on b.reqpayid = a.reqpayid
				join invoiceap c on c.invoiceapid = b.invoiceapid
				join addressbook d on d.addressbookid = c.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.reqpayid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('reqpay');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No. Dokumen ');
      $this->pdf->text(40, $this->pdf->gety() + 2, ': ' . $row['reqpayno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Dibayarkan kepada ');
      $this->pdf->text(40, $this->pdf->gety() + 6, ': ' . $row['supplier']);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Sejumlah Rp. ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . Yii::app()->format->formatCurrency($row['nilai']));
      $this->pdf->text(120, $this->pdf->gety() + 6, 'Bank ');
      $this->pdf->text(140, $this->pdf->gety() + 6, ': ' . $row['bankname']);
      $this->pdf->text(120, $this->pdf->gety() + 10, 'A/N ');
      $this->pdf->text(140, $this->pdf->gety() + 10, ': ' . $row['accountowner']);
      $this->pdf->SetFontSize(9);
      $this->pdf->text(120, $this->pdf->gety() + 14, 'No Rekening');
      $this->pdf->text(140, $this->pdf->gety() + 14, ': ' . $row['bankaccountno']);
      $this->pdf->SetFontSize(8);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'Tgl Dokumen ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(10, $this->pdf->gety() + 18, 'Terbilang ');
      $this->pdf->text(40, $this->pdf->gety() + 18, ': ');
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setaligns(array(
        'C',
        'L'
      ));
      $this->pdf->setwidths(array(
        31,
        160
      ));
      $this->pdf->row(array(
        '',
        strtoupper(eja($row['nilai']))
      ));
      $sql1        = "select b.invoiceno,d.fullname as supplier,b.invoicedate,adddate(b.invoicedate,e.paydays) as duedate,a.amount,a.taxno,a.itemnote
        from reqpayinv a
        left join invoiceap b on b.invoiceapid = a.invoiceapid
        left join poheader c on c.poheaderid = b.poheaderid 
				left join addressbook d on d.addressbookid = c.addressbookid
				left join paymentmethod e on e.paymentmethodid = c.paymentmethodid
        where reqpayid = " . $row['reqpayid'] . "
        order by reqpayinvid ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 2);
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
        25,
        25,
        25,
        25,
        25,
        60
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'Tgl Invoice',
        'Nilai',
        'Jth Tempo',
        'No Faktur pajak',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'C',
        'C',
        'R',
        'C',
        'C',
        'L'
      );
      $i                         = 0;
      $total                     = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['invoiceno'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
          Yii::app()->format->formatCurrency($row1['amount']),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['duedate'])),
          $row1['taxno'],
          $row1['itemnote']
        ));
        $total += $row1['amount'];
      }
      $this->pdf->SetFontSize(10);
      $this->pdf->setaligns(array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'L',
        'R'
      ));
      $this->pdf->setwidths(array(
        10,
        25,
        25,
        25,
        25,
        25,
        35
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'TOTAL :',
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Keterangan ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . $row['headernote']);
      $this->pdf->checkNewPage(30);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety(), 'Diajukan oleh');
      $this->pdf->text(45, $this->pdf->gety(), 'Diperiksa oleh');
      $this->pdf->text(85, $this->pdf->gety(), 'Diketahui oleh');
      $this->pdf->text(125, $this->pdf->gety(), 'Disetujui oleh');
      $this->pdf->text(165, $this->pdf->gety(), 'Dibayar oleh');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(45, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(85, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(125, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(165, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(10, $this->pdf->gety() + 20, 'Adm H/D');
      $this->pdf->text(42, $this->pdf->gety() + 20, 'Divisi Acc & Finance');
      $this->pdf->text(85, $this->pdf->gety() + 20, 'Branch Manager');
      $this->pdf->text(125, $this->pdf->gety() + 20, 'Dir. Keuangan');
      $this->pdf->text(165, $this->pdf->gety() + 20, 'Bag. Bank pusat');
      $this->pdf->text(10, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(42, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(85, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(125, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(165, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->setFontSize(7);
      $this->pdf->text(10, $this->pdf->gety() + 33, 'NB :Faktur pajak wajib diisi jika pembayaran melalui Legal (Tanpa melampirkan faktur pajak lagi)');
      $this->pdf->text(10, $this->pdf->gety() + 38, '     :Dibuat rangkap 3, putih untuk Bag.Bank/Kasir, setelah dibayar diserahkan ke Adm H/D,Rangkap 2 utk Bag.Pajak,rangkap 3 Arsip H/D');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select docdate,reqpayno,headernote,recordstatus
				from reqpay a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.reqpayid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('reqpayno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(3, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['reqpayno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(3, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reqpay.xlsx"');
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
