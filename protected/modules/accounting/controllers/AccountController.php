<?php
class AccountController extends Controller {
  public $menuname = 'account';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexcombosloc() {
		if(isset($_GET['grid']))
				echo $this->searchcombosloc();
		else
				$this->renderPartial('index',array());
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
    $a='';
		if ($id == '') {
			$sql     = 'call Insertaccount(:vaccountcode,:vaccountname,:vparentaccountid,:vcurrencyid,:vaccounttypeid,:vcompanyid,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateaccount(:vid,:vaccountcode,:vaccountname,:vparentaccountid,:vcurrencyid,:vaccounttypeid,:vcompanyid,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vaccountcode', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaccountname', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vparentaccountid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaccounttypeid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vcompanyid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-account"]["name"]);
		if (move_uploaded_file($_FILES["file-account"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 3; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$accountcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$accountname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$parentaccountcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$parentaccountid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$parentaccountcode."'")->queryScalar();
					$currencyname = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
					$accounttypename = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$accounttypeid = Yii::app()->db->createCommand("select accounttypeid from accounttype where accounttypename = '".$accounttypename."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$this->ModifyData($connection,array($id,$accountcode,$accountname,$parentaccountid,$currencyid,$accounttypeid,$companyid,$recordstatus));
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
	public function actionSave() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['accountid'])?$_POST['accountid']:''),$_POST['accountcode'],$_POST['accountname'],$_POST['parentaccountid'],$_POST['currencyid'],$_POST['accounttypeid'],$_POST['companyid'],$_POST['recordstatus']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeaccount(:vid,:vcreatedby)';
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
  public function search() {
    header("Content-Type: application/json");
    $accountid       = isset($_POST['accountid']) ? $_POST['accountid'] : '';
    $accountname     = isset($_POST['accountname']) ? $_POST['accountname'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $companyname       = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $accountcode     = isset($_POST['accountcode']) ? $_POST['accountcode'] : '';
    $accounttypeid   = isset($_POST['accounttypeid']) ? $_POST['accounttypeid'] : '';
    $accounttypename = isset($_POST['accounttypename']) ? $_POST['accounttypename'] : '';
    $parentaccountid = isset($_POST['parentaccountid']) ? $_POST['parentaccountid'] : '';
    $parentaccountcode = isset($_POST['parentaccountcode']) ? $_POST['parentaccountcode'] : '';
    $parentaccountname = isset($_POST['parentaccountname']) ? $_POST['parentaccountname'] : '';
    $currencyid      = isset($_POST['currencyid']) ? $_POST['currencyid'] : '';
    $currencyname      = isset($_POST['currencyname']) ? $_POST['currencyname'] : '';
    $recordstatus    = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $accountid       = isset($_GET['q']) ? $_GET['q'] : $accountid;
    $accountname     = isset($_GET['q']) ? $_GET['q'] : $accountname;
    $companyid       = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $companyname       = isset($_GET['q']) ? $_GET['q'] : $companyname;
    $accountcode     = isset($_GET['q']) ? $_GET['q'] : $accountcode;
    $accounttypeid   = isset($_GET['q']) ? $_GET['q'] : $accounttypeid;
    $accounttypename = isset($_GET['q']) ? $_GET['q'] : $accounttypename;
    $parentaccountid = isset($_GET['q']) ? $_GET['q'] : $parentaccountid;
    $parentaccountcode = isset($_GET['q']) ? $_GET['q'] : $parentaccountcode;
    $parentaccountname = isset($_GET['q']) ? $_GET['q'] : $parentaccountname;
    $currencyid      = isset($_GET['q']) ? $_GET['q'] : $currencyid;
    $currencyname      = isset($_GET['q']) ? $_GET['q'] : $currencyname;
    $recordstatus    = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.accountid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and 
			t.recordstatus=1 and 
			t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->queryScalar();
    } else if (isset($_GET['trx'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and 
			t.recordstatus=1 and t.accounttypeid = 2 and 
			t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->queryScalar();
    } else if (isset($_GET['trxcom'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and t.companyid = '".$_GET['companyid']."' and 
			t.recordstatus=1 and t.accounttypeid = 2", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->queryScalar();
    } else if (isset($_GET['trxsloc'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->leftjoin('plant d', 'd.companyid = t.companyid')->leftjoin('sloc e', 'e.plantid = d.plantid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and e.slocid = '".$_GET['slocid']."' and 
			t.recordstatus=1 and t.accounttypeid = 2 and 
			t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->queryScalar();
    } else if (isset($_GET['trxacc'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and t.companyid = '".$_GET['companyid']."' and 
			t.recordstatus=1 and t.accounttypeid = 1", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->queryScalar();
    } else if (isset($_GET['params'])) {
      if(isset($_GET['startcodeacc']) && ($_GET['startcodeacc']!='')) {
          $acccode = " and t.accountcode between '".$_GET['startcodeacc']."' and '".$_GET['endcodeacc']."'";
      }
      else {
          $acccode = '';
      }
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and 
			t.recordstatus=1
            {$acccode}
			and t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->queryScalar();
	} else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->leftjoin('account d', 'd.accountid = t.parentaccountid')->where("(t.accountid like :accountid) and (a.companyname like :companyname) and (t.accountcode like :accountcode) and (t.accountname like :accountname) and (c.accounttypename like :accounttypename) and (ifnull(d.accountcode,'') like :parentaccountcode) and (ifnull(d.accountname,'') like :parentaccountname) and (b.currencyname like :currencyname) and (t.recordstatus like :recordstatus)", array(
        ':accountid' => '%' . $accountid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':accountcode' => '%' . $accountcode . '%',
        ':accountname' => '%' . $accountname . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':parentaccountcode' => '%' . $parentaccountcode . '%',
        ':parentaccountname' => '%' . $parentaccountname . '%',
        ':currencyname' => '%' . $currencyname . '%',
        ':recordstatus' => '%' . $recordstatus . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				(select z.accountcode from account z where z.accountid = t.parentaccountid) as parentaccountcode')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and 
			t.recordstatus=1 and 
			t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trx'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				(select z.accountcode from account z where z.accountid = t.parentaccountid) as parentaccountcode')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and 
			t.recordstatus=1 and t.accounttypeid = 2 and 
			t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trxcom'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				(select z.accountcode from account z where z.accountid = t.parentaccountid) as parentaccountcode')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and t.companyid = '".$_GET['companyid']."' and
			t.recordstatus=1 and t.accounttypeid = 2", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trxsloc'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				(select z.accountcode from account z where z.accountid = t.parentaccountid) as parentaccountcode')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->leftjoin('plant d', 'd.companyid = t.companyid')->leftjoin('sloc e', 'e.plantid = d.plantid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and e.slocid = '".$_GET['slocid']."' and
			t.recordstatus=1 and t.accounttypeid = 2 and 
			t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['trxacc'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				(select z.accountcode from account z where z.accountid = t.parentaccountid) as parentaccountcode')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and t.companyid = '".$_GET['companyid']."' and
			t.recordstatus=1 and t.accounttypeid = 1", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['params'])) {
      if(isset($_GET['startcodeacc']) && ($_GET['startcodeacc']!='')) {
          $acccode = " and t.accountcode between '".$_GET['startcodeacc']."' and '".$_GET['endcodeacc']."'";
      }
      else {
          $acccode = '';
      }
       $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				(select z.accountcode from account z where z.accountid = t.parentaccountid) as parentaccountcode')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->where("((accountcode like :accountcode) or (parentaccountid like :parentaccountid) or (c.accounttypename like :accounttypename) or (accountname like :accountname)) and 
			t.recordstatus=1 
            {$acccode}
			and t.companyid in (".getUserObjectValues('company').")", array(
        ':accountcode' => '%' . $accountcode . '%',
        ':parentaccountid' => '%' . $parentaccountid . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
	else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.currencyname,c.accounttypename,
				(select z.accountcode from account z where z.accountid = t.parentaccountid) as parentaccountcode')->from('account t')->leftjoin('company a', 'a.companyid=t.companyid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->leftjoin('accounttype c', 'c.accounttypeid = t.accounttypeid')->leftjoin('account d', 'd.accountid = t.parentaccountid')->where("(t.accountid like :accountid) and (a.companyname like :companyname) and (t.accountcode like :accountcode) and (t.accountname like :accountname) and (c.accounttypename like :accounttypename) and (ifnull(d.accountcode,'') like :parentaccountcode) and (ifnull(d.accountname,'') like :parentaccountname) and (b.currencyname like :currencyname) and (t.recordstatus like :recordstatus)", array(
        ':accountid' => '%' . $accountid . '%',
        ':companyname' => '%' . $companyname . '%',
        ':accountcode' => '%' . $accountcode . '%',
        ':accountname' => '%' . $accountname . '%',
        ':accounttypename' => '%' . $accounttypename . '%',
        ':parentaccountcode' => '%' . $parentaccountcode . '%',
        ':parentaccountname' => '%' . $parentaccountname . '%',
        ':currencyname' => '%' . $currencyname . '%',
        ':recordstatus' => '%' . $recordstatus . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'accountid' => $data['accountid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'accountcode' => $data['accountcode'],
        'accountname' => $data['accountname'],
        'parentaccountid' => $data['parentaccountid'],
        'parentaccountcode' => $data['parentaccountcode'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'accounttypeid' => $data['accounttypeid'],
        'accounttypename' => $data['accounttypename'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchcombosloc() {
		header("Content-Type: application/json");
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$accountcode = isset($_GET['accountcode']) ? $_GET['accountcode'] : '';
		$accountname = isset($_GET['accountname']) ? $_GET['accountname'] : '';
		$slocid = isset ($_GET['slocid']) ? $_GET['slocid'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'a.accountid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc' ;
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();
		$sqlcount = "SELECT COUNT(1) as total 
								FROM account a 
								JOIN company b ON b.companyid = a.companyid
								JOIN plant c ON c.companyid = b.companyid
								JOIN sloc d ON d.plantid = c.plantid
								WHERE a.recordstatus = 1 AND a.accounttypeid = 2 
								AND a.accountname LIKE '%".$description."%'
								AND d.slocid = ".$slocid."";
		$cmd = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$result['total'] = $cmd;
		$sqldata = "SELECT a.accountid, a.accountcode, a.accountname, b.companyname
								FROM account a 
								JOIN company b ON b.companyid = a.companyid
								JOIN plant c ON c.companyid = b.companyid
								JOIN sloc d ON d.plantid = c.plantid
								WHERE a.recordstatus = 1 AND a.accounttypeid = 2 
								AND a.accountname LIKE '%".$description."%'
								AND d.slocid = ".$slocid." LIMIT ".$offset.",".$rows;
		$cmd = Yii::app()->db->createCommand($sqldata)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'companyname'=>$data['companyname'],
				'accountname'=>$data['accountname'],
				'accountcode'=>$data['accountcode'],
				'accountid'=>$data['accountid'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
  public function actionGetAccount() {
		header("Content-Type: application/json");
		$accheaderid = isset($_POST['accheaderid']) ? $_POST['accheaderid'] : '';
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()->select('accountcode, accountname')->from('account t')->where('accountid = :accountid', array(
				':accountid' => $accheaderid))->queryRow();
		$result['accountname'] = $cmd['accountname'];
		$result['accountcode'] = $cmd['accountcode'];
		echo CJSON::encode($result);
  }
	public function actionAccountCompany() {
		header("Content-Type: application/json");
		$accountcode = isset($_GET['accountcode']) ? $_GET['accountcode'] : '';
		$accountname = isset($_GET['accountname']) ? $_GET['accountname'] : '';
		$accountcode = isset ($_GET['q']) ? $_GET['q'] : '';
    $accountname = isset ($_GET['q']) ? $_GET['q'] : '';
		$companyid = isset ($_GET['companyid']) ? $_GET['companyid'] : '';
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'a.accountid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort   = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order  = isset($_GET['order']) ? strval($_GET['order']) : $order ;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		$sqlcount = "select count(1) as total 
                FROM account a 
                JOIN company b ON b.companyid = a.companyid
                WHERE a.recordstatus = 1 
                AND ((coalesce(a.accountname,'') like '%".$accountname."%') or (coalesce(a.accountcode,'') like '%".$accountcode."%')) ";
    if ($companyid !== '')
    {
      $sqlcount = $sqlcount . "AND a.companyid = ".$companyid." ";
    }
    //$sqlcount = $sqlcount . "LIMIT ".$offset.",".$rows;
    $cmd = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$result['total'] = $cmd;

		$sqldata = "select a.accountid, a.accountcode, a.accountname, b.companyname
								FROM account a 
								JOIN company b ON b.companyid = a.companyid
                WHERE a.recordstatus = 1 
                AND ((coalesce(a.accountname,'') like '%".$accountname."%') or (coalesce(a.accountcode,'') like '%".$accountcode."%')) ";
    if ($companyid !== '')
    {
      $sqldata = $sqldata . "AND a.companyid = ".$companyid." ";
    }
    $sqldata = $sqldata . "LIMIT ".$offset.",".$rows;
								
		$cmd = Yii::app()->db->createCommand($sqldata)->queryAll();    
		foreach($cmd as $data) {	
			$row[] = array(
				'companyname'=>$data['companyname'],
				'accountname'=>$data['accountname'],
				'accountcode'=>$data['accountcode'],
				'accountid'=>$data['accountid'],
			);
		}
    $result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
	}
	public function actionAccountFormula() {        
		header("Content-Type: application/json");
		$accountcode = isset($_GET['accountcode']) ? $_GET['accountcode'] : '';
		$accountname = isset($_GET['accountname']) ? $_GET['accountname'] : '';
		$accountname = isset ($_GET['q']) ? $_GET['q'] : '';
		$companyid = isset ($_GET['companyid']) ? $_GET['companyid'] : '';
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'a.accountid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort   = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order  = isset($_GET['order']) ? strval($_GET['order']) : $order ;
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();        
		$sqlcount = "select count(1) as total 
								from account a 
								where a.recordstatus = 1 
								and a.accountname LIKE '%".$accountname."%'
								AND a.companyid = ".$companyid;		
		$cmd = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$result['total'] = $cmd;    
		$sqldata = "select a.accountid, a.accountcode, a.accountname, b.companyname
								FROM account a 
								JOIN company b ON b.companyid = a.companyid
								WHERE a.recordstatus = 1 
								AND a.accountname LIKE '%".$accountname."%'
								AND a.companyid = ".$companyid." LIMIT ".$offset.",".$rows;
		$cmd = Yii::app()->db->createCommand($sqldata)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'companyname'=>$data['companyname'],
				'accountname'=>$data['accountname'],
				'accountcode'=>$data['accountcode'],
				'accountid'=>$data['accountid'],
			);
		}
    $result=array_merge($result,array('rows'=>$row));
		echo CJSON::encode($result);
	}
	public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.accountid,b.companyid,b.companycode,a.accountcode,a.accountname,a.parentaccountid,c.currencyname,d.accounttypename,a.recordstatus,e.accountcode as pc
						from account a
						left join company b on b.companyid=a.companyid
						left join currency c on c.currencyid=a.currencyid
						left join accounttype d on d.accounttypeid = a.accounttypeid
						left join account e on e.accountid = a.parentaccountid
						where (a.accountid like '%".$_GET['accountid']."%') and (b.companyname like '%".$_GET['companyname']."%') and (a.accountcode like '%".$_GET['accountcode']."%') and (a.accountname like '%".$_GET['accountname']."%') and (d.accounttypename like '%".$_GET['accounttypename']."%') and (c.currencyname like '%".$_GET['currencyname']."%')";
    if ($_GET['parentaccountcode'] !== '') {
      $sql = $sql . " and (e.accountcode like '%".$_GET['parentaccountcode']."%') ";
    }
    if ($_GET['parentaccountname'] !== '') {
      $sql = $sql . " and (e.accountname like '%".$_GET['parentaccountname']."%') ";
    }
    if ($_GET['recordstatus'] !== '') {
      $sql = $sql . " and  a.recordstatus = (".$_GET['recordstatus'].") ";
    }
    if ($_GET['id'] !== '') {
      $sql = $sql . " and  a.accountid in (".$_GET['id'].") ";
    } 
    $sql = $sql . " order by accountcode asc";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
		foreach ($dataReader as $row)  {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->title = GetCatalog('account');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C'
    );
    $this->pdf->colheader = array(
      'ID',
      'PT',
      'Kode Akun',
      'Nama Akun',
      'Akun Induk',
      'Mata Uang',
      'Jenis Akun',
      'Status'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      30,
      55,
      25,
      20,
      20,
      15
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial', '', 8);
    $this->pdf->coldetailalign = array(
      'R',
      'L',
      'L',
      'L',
      'L',
      'R',
      'R',
      'C'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['accountid'],
        $row1['companycode'],
        $row1['accountcode'],
        $row1['accountname'],
        $row1['pc'],
        $row1['currencyname'],
        $row1['accounttypename'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownXLS() {
    $this->menuname = 'account';
    parent::actionDownxls();
    $sql = "select a.accountid,b.companyid,b.companycode,a.accountcode,a.accountname,a.parentaccountid,c.currencyname,d.accounttypename,a.recordstatus,e.accountcode as pc
						from account a
						left join company b on b.companyid=a.companyid
						left join currency c on c.currencyid=a.currencyid
						left join accounttype d on d.accounttypeid = a.accounttypeid
						left join account e on e.accountid = a.parentaccountid
						where (a.accountid like '%".$_GET['accountid']."%') and (b.companyname like '%".$_GET['companyname']."%') and (a.accountcode like '%".$_GET['accountcode']."%') and (a.accountname like '%".$_GET['accountname']."%') and (d.accounttypename like '%".$_GET['accounttypename']."%') and (c.currencyname like '%".$_GET['currencyname']."%')";
    if ($_GET['parentaccountcode'] !== '') {
      $sql = $sql . " and (e.accountcode like '%".$_GET['parentaccountcode']."%') ";
    }
    if ($_GET['parentaccountname'] !== '') {
      $sql = $sql . " and (e.accountname like '%".$_GET['parentaccountname']."%') ";
    }
    if ($_GET['recordstatus'] !== '') {
      $sql = $sql . " and  a.recordstatus = (".$_GET['recordstatus'].") ";
    }
    if ($_GET['id'] !== '') {
      $sql = $sql . " and  a.accountid in (".$_GET['id'].") ";
    } 
    $sql = $sql . " order by accountcode asc";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 2;
    /*$this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('company'))->setCellValueByColumnAndRow(1, 1, GetCatalog('accountcode'))->setCellValueByColumnAndRow(2, 1, GetCatalog('accountname'))->setCellValueByColumnAndRow(3, 1, GetCatalog('parentaccount'))->setCellValueByColumnAndRow(4, 1, GetCatalog('currencyname'))->setCellValueByColumnAndRow(5, 1, GetCatalog('accounttypename'))->setCellValueByColumnAndRow(6, 1, GetCatalog('recordstatus'));*/
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['accountid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['companycode'])->setCellValueByColumnAndRow(2, $i + 1, $row1['accountcode'])->setCellValueByColumnAndRow(3, $i + 1, $row1['accountname'])->setCellValueByColumnAndRow(4, $i + 1, $row1['pc'])->setCellValueByColumnAndRow(5, $i + 1, $row1['currencyname'])->setCellValueByColumnAndRow(6, $i + 1, $row1['accounttypename'])->setCellValueByColumnAndRow(7, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}