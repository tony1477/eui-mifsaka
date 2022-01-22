<?php
class BalancesheetController extends Controller {
  public $menuname = 'balancesheet';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $repneracaid     = isset($_POST['repneracaid']) ? $_POST['repneracaid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $accountid       = isset($_POST['accountid']) ? $_POST['accountid'] : '';
    $isdebet         = isset($_POST['isdebet']) ? $_POST['isdebet'] : '';
    $nourut          = isset($_POST['nourut']) ? $_POST['nourut'] : '';
    $recordstatus    = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $repneracaid     = isset($_GET['q']) ? $_GET['q'] : $repneracaid;
    $companyid       = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $accountid       = isset($_GET['q']) ? $_GET['q'] : $accountid;
    $isdebet         = isset($_GET['q']) ? $_GET['q'] : $isdebet;
    $nourut          = isset($_GET['q']) ? $_GET['q'] : $nourut;
    $recordstatus    = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'repneracaid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $com          = array();
		
		$com = Yii::app()->db->createCommand()->select ('group_concat(distinct a.menuvalueid) as menuvalueid')
		->from('groupmenuauth a')
		->join('menuauth b', 'b.menuauthid = a.menuauthid')
		->join('usergroup c', 'c.groupaccessid = a.groupaccessid')
		->join('useraccess d', 'd.useraccessid = c.useraccessid')
		->where("upper(b.menuobject) = upper('company')
		and d.username = '" . Yii::app()->user->name . "' ")->queryScalar();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('repneraca t')->join('company a', 'a.companyid=t.companyid')->join('account b', 'b.accountid=t.accountid')->join('accounttype c', 'c.accounttypeid=b.accounttypeid')->where('((t.repneracaid like :repneracaid) or
								(a.companyname like :companyid) or
								(b.accountname like :accountid) or
								(t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repneracaid' => '%' . $repneracaid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountid' => '%' . $accountid . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyid,a.companyname,b.accountid,b.accountname,c.accounttypename')->from('repneraca t')->join('company a', 'a.companyid=t.companyid')->join('account b', 'b.accountid=t.accountid')->join('accounttype c', 'c.accounttypeid=b.accounttypeid')->where('((t.repneracaid like :repneracaid) or
								(a.companyname like :companyid) or
								(b.accountname like :accountid) or
								(t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repneracaid' => '%' . $repneracaid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountid' => '%' . $accountid . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'repneracaid' => $data['repneracaid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'accounttypename' => $data['accounttypename'],
        'isdebet' => $data['isdebet'],
        'accformula' => $data['accformula'],
        'aftacc' => $data['aftacc'],
        'nourut' => $data['nourut'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertbalancesheet(:vcompanyid,:vaccountid,:visdebet,:vaccformula,:vaftacc,:vnourut,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatebalancesheet(:vid,:vcompanyid,:vaccountid,:visdebet,:vaccformula,:vaftacc,:vnourut,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaccountid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':visdebet', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vaccformula', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vaftacc', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vnourut', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-balancesheet"]["name"]);
		if (move_uploaded_file($_FILES["file-balancesheet"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$accountcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$accountid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$accountcode."'")->queryScalar();
					$isdebet = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$accformula = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$aftacc = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$nourut = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$this->ModifyData($connection,array($id,$companyid,$accountid,$isdebet,$accformula,$aftacc,$nourut,$recordstatus));
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
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['repneracaid'])?$_POST['repneracaid']:''),$_POST['companyid'],$_POST['accountid'],$_POST['isdebet'],
				$_POST['accformula'],$_POST['aftacc'],$_POST['nourut'],$_POST['recordstatus']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge() {
		parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebalancesheet(:vid,:vcreatedby)';
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
  private $subtotal = 0;
  private $sublast = 0;
  private function GetBalance($account, $reportdate, $isdebet) {
    $sql = "select ifnull(count(1),0) from account where parentaccountid = " . $account;
    $a   = Yii::app()->db->createCommand($sql)->queryScalar();
    if ($a > 0) {
      $sql        = "select accountid from account where parentaccountid = " . $account;
      $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
      foreach ($dataReader as $data) {
        $this->GetBalance($data['accountid'], $reportdate, $isdebet);
      }
    } else {
      if ($isdebet == 1) {
        $sql = "select ifnull(sum(a.debit * a.ratevalue) - sum(a.credit * a.ratevalue),0)
					from genledger a
					where a.accountid = " . $account . " and month(a.journaldate) <= month('" . $reportdate . "') 
					and year(a.journaldate) = year('" . $reportdate . "')";
        $this->subtotal += Yii::app()->db->createCommand($sql)->queryScalar();
      } else {
        $sql = "select ifnull(sum(a.credit * a.ratevalue) - sum(a.debit * a.ratevalue),0)
					from genledger a
					where a.accountid = " . $account . " and month(a.journaldate) <= month('" . $reportdate . "') 
					and year(a.journaldate) = year('" . $reportdate . "')";
        $this->subtotal += Yii::app()->db->createCommand($sql)->queryScalar();
      }
    }
  }
  private function GetBalanceLastMonth($account, $reportdate, $isdebet) {
    $sql = "select ifnull(count(1),0) from account where parentaccountid = " . $account;
    $a   = Yii::app()->db->createCommand($sql)->queryScalar();
    if ($a > 0) {
      $sql        = "select accountid from account where parentaccountid = " . $account;
      $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
      foreach ($dataReader as $data) {
        $this->GetBalanceLastMonth($data['accountid'], $reportdate, $isdebet);
      }
    } else {
      if ($isdebet == 1) {
        $sql = "select ifnull(sum(a.debit * a.ratevalue) - sum(a.credit * a.ratevalue),0)
					from genledger a
					where a.accountid = " . $account . " and month(a.journaldate) <= month('" . $reportdate . "')-1 
					and year(a.journaldate) = year('" . $reportdate . "')";
        $this->subtotal += Yii::app()->db->createCommand($sql)->queryScalar();
      } else {
        $sql = "select ifnull(sum(a.credit * a.ratevalue) - sum(a.debit * a.ratevalue),0)
					from genledger a
					where a.accountid = " . $account . " and month(a.journaldate) <= month('" . $reportdate . "')-1 
					and year(a.journaldate) = year('" . $reportdate . "')";
        $this->subtotal += Yii::app()->db->createCommand($sql)->queryScalar();
      }
    }
  }
  private function GenerateTable() {
    $connection           = Yii::app()->db;
    $sqlactiva            = "select a.aftacc,a.accountid, a.accountcode,a.accountname,a.parentaccountid
						from repneraca a
						where a.isdebet = 1 and a.companyid = " . $_GET['company'] . " order by a.nourut";
    $comactiva            = $this->connection->createCommand($sqlactiva)->queryAll();
    $sqlpasiva            = "select a.aftacc,a.accountid, a.accountcode,a.accountname,a.parentaccountid
						from repneraca a
						where a.isdebet = 0 and a.companyid = " . $_GET['company'] . " order by a.nourut";
    $compasiva            = $this->connection->createCommand($sqlpasiva)->queryAll();
    $this->pdf->companyid = $_GET['company'];
    $countactiva          = count($comactiva);
    $countpasiva          = count($compasiva);
    $counts               = ($countactiva >= $countpasiva) ? $countactiva : $countpasiva;
    $i                    = 0;
    $spasi                = '';
    $s                    = array();
    $sql                  = "truncate table repneracalajur";
    $this->connection->createCommand($sql)->execute();
    foreach ($comactiva as $activas) {
      if ($activas['aftacc'] !== '0') {
        $spasi = str_replace('  ', '', $spasi);
        $sql   = "insert into repneracalajur (companyid,jumlah,accactivaid,accactivacode,accactivaname,accparent,accblninitotal,accblninipersen,accblnlalutotal,
							accblnlalupersen) 
						values (" . $_GET['company'] . "," . $i . ",null,'" . $activas['accountcode'] . "','" . $spasi . $activas['accountname'] . "'," . $activas['parentaccountid'] . "
						,null,null,null,null)";
        $this->connection->createCommand($sql)->execute();
        $spasi = '  ' . $spasi;
        array_push($s, $activas['aftacc']);
        $i++;
      } else {
        $this->GetBalance($activas['accountid'], date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
        $accblninitotal = $this->subtotal;
        $this->subtotal = 0;
        $this->GetBalanceLastMonth($activas['accountid'], date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
        $accblnlalutotal = $this->subtotal;
        $this->subtotal  = 0;
        $sql             = "insert into repneracalajur (companyid,jumlah,accactivaid,accactivacode,accactivaname,accparent,accblninitotal,accblninipersen,accblnlalutotal,
							accblnlalupersen) 
						values (" . $_GET['company'] . "," . $i . "," . $activas['accountid'] . ",'" . $activas['accountcode'] . "','" . $spasi . $activas['accountname'] . "'," . $activas['parentaccountid'] . "
						," . $accblninitotal . ",null," . $accblnlalutotal . ",null)";
        $this->connection->createCommand($sql)->execute();
        $i++;
        if (in_array($activas['accountid'], $s)) {
          $sql = "select a.accountid,b.accountname from repneraca a join account b on b.accountid = a.accountid where aftacc = " . $activas['accountid'];
          $a   = $this->connection->createCommand($sql)->queryRow();
          $this->GetBalance($a['accountid'], date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
          $accblninitotal = $this->subtotal;
          $this->subtotal = 0;
          $this->GetBalanceLastMonth($a['accountid'], date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
          $accblnlalutotal = $this->subtotal;
          $this->subtotal  = 0;
          $sql             = "insert into repneracalajur (companyid,jumlah,accactivaid,accactivacode,accactivaname,accparent,accblninitotal,accblninipersen,accblnlalutotal,
								accblnlalupersen) 
							values (" . $_GET['company'] . "," . $i . "," . $a['accountid'] . ",null,'TOTAL " . $a['accountname'] . "',null,
							" . $accblninitotal . ",null," . $accblnlalutotal . ",null)";
          $this->connection->createCommand($sql)->execute();
          $s = array_diff($s, array(
            $a['accountid']
          ));
          $i++;
        }
      }
    }
    $this->GetBalance(1, date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
    $accblninitotal = $this->subtotal;
    $this->subtotal = 0;
    $this->GetBalanceLastMonth(1, date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
    $accblnlalutotal = $this->subtotal;
    $this->subtotal  = 0;
    $sql             = "insert into repneracalajur (companyid,jumlah,accactivaid,accactivacode,accactivaname,accparent,accblninitotal,accblninipersen,accblnlalutotal,
					accblnlalupersen) 
				values (" . $_GET['company'] . "," . $i . ",1,null,'TOTAL ACTIVA',null,
				" . $accblninitotal . ",null," . $accblnlalutotal . ",null)";
    $this->connection->createCommand($sql)->execute();
    $j = 0;
    $s = array();
    foreach ($compasiva as $pasiva) {
      if ($pasiva['aftacc'] !== '0') {
        $spasi = str_replace('  ', '', $spasi);
        $sql   = "select ifnull(count(1),0) from repneracalajur where jumlah = " . $j;
        $a     = $this->connection->createCommand($sql)->queryScalar();
        if ($a > 0) {
          $sql = "update repneracalajur 
						set accpasivaname = '" . $spasi . $pasiva['accountname'] . "',
							pasparent = " . $pasiva['parentaccountid'] . ",
							accpasivacode = " . $pasiva['accountcode'] . ",
							pasblninitotal = null,
							pasblninipersen = null,
							pasblnlalutotal = null,
							pasblnlalupersen = null
						where companyid = " . $_GET['company'] . " and jumlah = " . $j;
          $this->connection->createCommand($sql)->execute();
        } else {
          $sql = "insert into repneracalajur (companyid,jumlah,accpasivaname,accpasivacode,pasparent,pasblninitotal,pasblninipersen,pasblnlalutotal,pasblnlalupersen) 
						values (" . $_GET['company'] . "," . $j . ",'" . $pasiva['accountname'] . "','" . $pasiva['accountcode'] . "'," . $spasi . $pasiva['parentaccountid'] . ",null,null,null,null)";
          $this->connection->createCommand($sql)->execute();
        }
        $spasi = '  ' . $spasi;
        array_push($s, $pasiva['aftacc']);
        $j++;
      } else {
        $this->GetBalance($pasiva['accountid'], date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
        $accblninitotal = $this->subtotal;
        $this->subtotal = 0;
        $this->GetBalanceLastMonth($pasiva['accountid'], date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
        $accblnlalutotal = $this->subtotal;
        $this->subtotal  = 0;
        $sql             = "select ifnull(count(1),0) from repneracalajur where jumlah = " . $j;
        $a               = $this->connection->createCommand($sql)->queryScalar();
        if ($a > 0) {
          $sql = "update repneracalajur 
						set accpasivaid = " . $pasiva['accountid'] . ",
							accpasivaname = '" . $spasi . $pasiva['accountname'] . "',
							pasparent = " . $pasiva['parentaccountid'] . ",
							accpasivacode = " . $pasiva['accountcode'] . ",
							pasblninitotal = " . $accblninitotal . ",
							pasblninipersen = null,
							pasblnlalutotal = " . $accblnlalutotal . ",
							pasblnlalupersen = null
						where companyid = " . $_GET['company'] . " and jumlah = " . $j;
          $this->connection->createCommand($sql)->execute();
        } else {
          $sql = "insert into repneracalajur (companyid,jumlah,accpasivaid,accpasivaname,accpasivacode,pasparent,pasblninitotal,pasblninipersen,pasblnlalutotal,pasblnlalupersen) 
						values (" . $_GET['company'] . "," . $j . "," . $pasiva['accountid'] . ",'" . $spasi . $pasiva['accountname'] . "','" . $pasiva['accountcode'] . "'," . $pasiva['parentaccountid'] . "," . $accblninitotal . ",null," . $accblnlalutotal . ",null)";
          $this->connection->createCommand($sql)->execute();
        }
        $j++;
        if (in_array($pasiva['accountid'], $s)) {
          $sql = "select a.accountid,b.accountname from repneraca a join account b on b.accountid = a.accountid where aftacc = " . $pasiva['accountid'];
          $a   = $this->connection->createCommand($sql)->queryRow();
          $this->GetBalance($a['accountid'], date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
          $accblninitotal = $this->subtotal;
          $this->subtotal = 0;
          $this->GetBalanceLastMonth($a['accountid'], date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
          $accblnlalutotal = $this->subtotal;
          $this->subtotal  = 0;
          $sql             = "select ifnull(count(1),0) from repneracalajur where jumlah = " . $j;
          $b               = $this->connection->createCommand($sql)->queryScalar();
          if ($b > 0) {
            $sql = "update repneracalajur 
							set accpasivaid = " . $a['accountid'] . ",
								accpasivaname = 'TOTAL " . $a['accountname'] . "',
								pasblninitotal = " . $accblninitotal . ",
								pasblninipersen = null,
								pasblnlalutotal = " . $accblnlalutotal . ",
								pasblnlalupersen = null
							where companyid = " . $_GET['company'] . " and jumlah = " . $j;
            $this->connection->createCommand($sql)->execute();
          } else {
            $sql = "insert into repneracalajur (companyid,jumlah,accpasivaid,accpasivaname,accpasivacode,pasparent,pasblninitotal,pasblninipersen,pasblnlalutotal,pasblnlalupersen) 
							values (" . $_GET['company'] . "," . $j . "," . $a['accountid'] . ",'TOTAL " . $a['accountname'] . "',null,null," . $accblninitotal . ",null," . $accblnlalutotal . ",null)";
            $this->connection->createCommand($sql)->execute();
          }
          $s = array_diff($s, array(
            $a['accountid']
          ));
          $j++;
        }
      }
    }
    $j = ($j > $i) ? $j : $i;
    $this->GetBalance(155, date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
    $accblninitotal = $this->subtotal;
    $this->subtotal = 0;
    $this->GetBalanceLastMonth(155, date(Yii::app()->params['datetodb'], strtotime($_GET['date'])), 1);
    $accblnlalutotal = $this->subtotal;
    $this->subtotal  = 0;
    $sql             = "select ifnull(count(1),0) from repneracalajur where jumlah = " . $j;
    $b               = $this->connection->createCommand($sql)->queryScalar();
    if ($b > 0) {
      $sql = "update repneracalajur 
				set accpasivaid = 155,
					accpasivaname = 'TOTAL PASIVA',
					pasblninitotal = " . $accblninitotal . ",
					pasblninipersen = null,
					pasblnlalutotal = " . $accblnlalutotal . ",
					pasblnlalupersen = null
				where companyid = " . $_GET['company'] . " and jumlah = " . $j;
      $this->connection->createCommand($sql)->execute();
    } else {
      $sql = "insert into repneracalajur (companyid,jumlah,accpasivaid,accpasivacode,accpasivaname,pasparent,pasblninitotal,pasblninipersen,pasblnlalutotal,
						pasblnlalupersen) 
					values (" . $_GET['company'] . "," . $j . ",155,null,'TOTAL PASIVA',null,
					" . $accblninitotal . ",null," . $accblnlalutotal . ",null)";
      $this->connection->createCommand($sql)->execute();
    }
    $sql             = "select accblninitotal,accblnlalutotal  
			from repneracalajur a 
			where accactivaid = 1 and companyid = " . $_GET['company'];
    $data            = $this->connection->createCommand($sql)->queryRow();
    $accblninitotal  = $data['accblninitotal'];
    $accblnlalutotal = $data['accblnlalutotal'];
    $sql             = "select pasblninitotal,pasblnlalutotal  
			from repneracalajur a 
			where accpasivaid = 155 and companyid = " . $_GET['company'];
    $data            = $this->connection->createCommand($sql)->queryRow();
    $pasblninitotal  = $data['pasblninitotal'];
    $pasblnlalutotal = $data['pasblnlalutotal'];
    $sql             = "select * from repneracalajur a
			where a.companyid = " . $_GET['company'] . " 
			order by jumlah";
    $datareader      = $this->connection->createCommand($sql)->queryAll();
    foreach ($datareader as $data) {
      if ($data['accactivaid'] !== null) {
        $sql = "update repneracalajur 
				set accblninipersen = (accblninitotal / " . $accblninitotal . ") * 100,
				accblnlalupersen = (accblnlalutotal / " . $accblnlalutotal . ") * 100
				where companyid = " . $_GET['company'] . " 
				and accactivaid = " . $data['accactivaid'];
        $this->connection->createCommand($sql)->execute();
      }
      if ($data['accpasivaid'] !== null) {
        $sql = "update repneracalajur 
				set pasblninipersen = (pasblninitotal / " . $pasblninitotal . ") * 100,
				pasblnlalupersen = (pasblnlalutotal / " . $pasblnlalutotal . ") * 100
				where companyid = " . $_GET['company'] . " 
				and accpasivaid = " . $data['accpasivaid'];
        $this->connection->createCommand($sql)->execute();
      }
    }
  }
  public function actionGeneratebs() {
    parent::actionDownload();
		$sql = "call InsertBSLajur(" . $_REQUEST['companyname'] . ", '" . date(Yii::app()->params['datetodb'], strtotime($_REQUEST['bsdate'])) . "')";
    Yii::app()->db->createCommand($sql)->execute();
		GetMessage('success', 'alreadysaved');
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    $sql        = "select a.*
			from repneracalajur a 
			where a.companyid = " . $_GET['company'] . " 
			and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			order by jumlah ";
    $datareader = $this->connection->createCommand($sql)->queryAll();
    $this->pdf->AddPage('L','A4');
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->Cell(0, 0, GetCatalog('balancesheet'), 0, 0, 'C');
    $this->pdf->Cell(-277, 10, 'Per : ' . date("t F Y", strtotime($_GET['date'])), 0, 0, 'C');
    $i = 0;
    $this->pdf->setFont('Arial', 'B', 7);
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->colalign  = array(
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
    $this->pdf->colheader = array(
      '',
      'Bulan Ini',
      '',
      'Bulan Lalu',
      '',
      '',
      'Bulan Ini',
      '',
      'Bulan Lalu',
      ''
    );
    $this->pdf->setwidths(array(
      72,
      29,
      11,
      29,
      11,
      52,
      29,
      11,
      29,
      11
    ));
    $this->pdf->Rowheader();
    $this->pdf->colheader = array(
      'Keterangan',
      'Total',
      '%',
      'Total',
      '%',
      'Keterangan',
      'Total',
      '%',
      'Total',
      '%'
    );
    $this->pdf->setwidths(array(
      72,
      29,
      11,
      29,
      11,
      52,
      29,
      11,
      29,
      11
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'R',
      'R',
      'R',
      'R',
      'L',
      'R',
      'R',
      'R',
      'R'
    );
    $this->pdf->setFont('Arial', '', 7);
    foreach ($datareader as $data) {
      if ((strpos($data['accactivaname'], 'TOTAL') !== false) && (strpos($data['accpasivaname'], 'TOTAL') !== false)) {
        $this->pdf->rowstyles = array(
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          )
        );
      } else if (($data['accactivaid'] == null) && (strpos($data['accpasivaname'], 'TOTAL') !== false)) {
        $this->pdf->rowstyles = array(
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          )
        );
      } else if (($data['accactivaid'] == null) && ($data['accpasivaid'] == null)) {
        $this->pdf->rowstyles = array(
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          )
        );
      } else if (($data['accactivaid'] !== null) && ($data['accpasivaid'] == null)) {
        $this->pdf->rowstyles = array(
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          )
        );
      } else if (($data['accactivaid'] === null) && ($data['accpasivaid'] !== null)) {
        $this->pdf->rowstyles = array(
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          )
        );
      } else if ((strpos($data['accactivaname'], 'TOTAL') !== false) && (strpos($data['accpasivaname'], 'TOTAL') === false)) {
        $this->pdf->rowstyles = array(
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          )
        );
      } else if ((strpos($data['accactivaname'], 'TOTAL') === false) && (strpos($data['accpasivaname'], 'TOTAL') !== false)) {
        $this->pdf->rowstyles = array(
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          ),
          array(
            'Arial',
            'B',
            7
          )
        );
      } else if (($data['accactivaid'] !== null) && ($data['accpasivaid'] !== null)) {
        $this->pdf->rowstyles = array(
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          ),
          array(
            'Arial',
            '',
            7
          )
        );
      }
			if (($data['accactivaid'] == null) && ($data['accpasivaid'] == null)) {
				$this->pdf->row(array(
					$data['accactivaname'],
					'',
					'',
					'',
					'',
					$data['accpasivaname'],
					'',
					'',
					'',
					''
				));
			} else 
			if (($data['accactivaid'] == null) && ($data['accpasivaid'] !== null)) {
				$this->pdf->row(array(
					$data['accactivaname'],
					'',
					'',
					'',
					'',
					'  '.$data['accpasivaname'],
					Yii::app()->format->formatCurrency($data['pasblninitotal']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['pasblninipersen']),
					Yii::app()->format->formatCurrency($data['pasblnlalutotal']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['pasblnlalupersen'])
				));
			}	else 
				if (($data['accactivaid'] !== null) && ($data['accpasivaid'] == null)) {
				$this->pdf->row(array(
					'  '.$data['accactivaname'],
					Yii::app()->format->formatCurrency($data['accblninitotal']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['accblninipersen']),
					Yii::app()->format->formatCurrency($data['accblnlalutotal']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['accblnlalupersen']),
					$data['accpasivaname'],
					'',
					'',
					'',
					'',
				));
			}	
				else {
				$this->pdf->row(array(
					'  '.$data['accactivaname'],
					Yii::app()->format->formatCurrency($data['accblninitotal']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['accblninipersen']),
					Yii::app()->format->formatCurrency($data['accblnlalutotal']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['accblnlalupersen']),
					'  '.$data['accpasivaname'],
					Yii::app()->format->formatCurrency($data['pasblninitotal']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['pasblninipersen']),
					Yii::app()->format->formatCurrency($data['pasblnlalutotal']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['pasblnlalupersen'])
				));
			}
    }
    $this->pdf->Output();
  }
  public function actionDownXls() {
    $this->menuname = 'laporanneraca';
    parent::actionDownxls();
    $sql        = "select a.*
			from repneracalajur a 
			where a.companyid = " . $_GET['company'] . " 
			and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			order by jumlah ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 6;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($_GET['date'])));
    $sqlcompany = " select companycode from company where companyid = '" . $_GET['company'] . "' ";
    $companycode = $this->connection->createCommand($sqlcompany)->queryScalar();
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 3, $companycode);
    $styleArray = array(
      'font' => array(
        'bold' => true,
        'color' => array(
          'rgb' => '#FFFFFF'
        ),
        'size' => 9,
        'name' => 'Arial'
      )
    );
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['accactivaname'])->setCellValueByColumnAndRow(1, $i + 1, $row1['accblninitotal']/$_GET['per'])->setCellValueByColumnAndRow(2, $i + 1, $row1['accblninipersen'])->setCellValueByColumnAndRow(3, $i + 1, $row1['accblnlalutotal']/$_GET['per'])->setCellValueByColumnAndRow(4, $i + 1, $row1['accblnlalupersen'])->setCellValueByColumnAndRow(5, $i + 1, $row1['accpasivaname'])->setCellValueByColumnAndRow(6, $i + 1, $row1['pasblninitotal']/$_GET['per'])->setCellValueByColumnAndRow(7, $i + 1, $row1['pasblninipersen'])->setCellValueByColumnAndRow(8, $i + 1, $row1['pasblnlalutotal']/$_GET['per'])->setCellValueByColumnAndRow(9, $i + 1, $row1['pasblnlalupersen']);
      $i += 1;
    }
    $this->getFooterXLS($this->phpExcel);
  }
  public function actionDownRatioPDF() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    $akt  = "select a.accblninitotal
			from repneracalajur a 
			where a.companyid = " . $_GET['company'] . " 
			and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			";
    $pas  = "select a.pasblninitotal
			from repneracalajur a 
			where a.companyid = " . $_GET['company'] . " 
			and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			";
    $lr   = "select a.actualblninitotal
			from repprofitlosslajur a 
			where a.companyid = " . $_GET['company'] . " 
			and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			";
    $this->pdf->AddPage('P');
    $this->pdf->SetFont('Arial','B',12);
    $this->pdf->companyid = $_GET['company'];
    $per = $_GET['per'];
    $this->pdf->Cell(0, 0, 'FINANCIAL RATIO', 0, 0, 'C');
    $this->pdf->Cell(-192, 10, 'Per : ' . date("t F Y", strtotime($_GET['date'])), 0, 0, 'C');
    
    $sql1 = $akt.'and a.accactivaname = "TOTAL AKTIVA LANCAR" ';
    $aktivalancar = $connection->createCommand($sql1)->queryScalar();
    $sql3 = $akt.'and a.accactivaname = " PERSEDIAAN" ';
    $persediaan = $connection->createCommand($sql3)->queryScalar();
    $sql4 = $akt.'and a.accactivaname = " KAS" ';
    $kas = $connection->createCommand($sql4)->queryScalar();
    $sql5 = $akt.'and a.accactivaname = " BANK" ';
    $bank = $connection->createCommand($sql5)->queryScalar();
    $sql6 = $akt.'and a.accactivaname = "TOTAL AKTIVA" ';
    $aktiva = $connection->createCommand($sql6)->queryScalar();
    $sql13 = $akt.'and a.accactivaname = " PIUTANG DAGANG" ';
    $piutangdagang = $connection->createCommand($sql13)->queryScalar();
    $sql14 = $akt.'and a.accactivaname = " PIUTANG GIRO" ';
    $piutanggiro = $connection->createCommand($sql14)->queryScalar();
    $sql19 = $akt.'and a.accactivaname = " PERSEDIAAN BARANG JADI (FG)" ';
    $fg = $connection->createCommand($sql19)->queryScalar();
    $sql20 = $akt.'and a.accactivaname = " PERSEDIAAN BAHAN BAKU" ';
    $rw = $connection->createCommand($sql20)->queryScalar();
    $sql21 = $akt.'and a.accactivaname = " PERSEDIAAN WIP" ';
    $wip = $connection->createCommand($sql21)->queryScalar();
    
    
    $sql2 = $pas.'and a.accpasivaname = "TOTAL KEWAJIBAN LANCAR" ';
    $kewajibanlancar = $connection->createCommand($sql2)->queryScalar();
    $sql7 = $pas.'and a.accpasivaname = "TOTAL EKUITAS" ';
    $ekuitas = $connection->createCommand($sql7)->queryScalar();
    $sql8 = $pas.'and a.accpasivaname = "TOTAL KEWAJIBAN JANGKA PANJANG" ';
    $kewajibanjangkapanjang = $connection->createCommand($sql8)->queryScalar();
    $sql15 = $pas.'and a.accpasivaname = "HUTANG DAGANG" ';
    $hutangdagang = $connection->createCommand($sql15)->queryScalar();
    $sql16 = $pas.'and a.accpasivaname = "HUTANG GIRO" ';
    $hutanggiro = $connection->createCommand($sql16)->queryScalar();
    $sql17 = $pas.'and a.accpasivaname = "HUTANG AFILIASI" ';
    $hutangafiliasi = $connection->createCommand($sql17)->queryScalar();
    
    $sql9 = $lr.'and a.accountname = "Total PENJUALAN BERSIH BARANG JADI (FG)" ';
    $penjualanbersih = $connection->createCommand($sql9)->queryScalar();
    $sql10 = $lr.'and a.accountname = " LABA (RUGI) BERSIH" ';
    $labarugibersih = $connection->createCommand($sql10)->queryScalar();
    $sql11 = $lr.'and a.accountname = "Total LABA KOTOR BARANG JADI (FG)" ';
    $labakotor = $connection->createCommand($sql11)->queryScalar();
    $sql12 = $lr.'and a.accountname = "Total HARGA POKOK PENJUALAN" ';
    $hpp = $connection->createCommand($sql12)->queryScalar();
    
    $sql18 = "select sum(debit-credit)
              from genledger a
              join genjournal b on b.genjournalid=a.genjournalid
              join account c on c.accountid=a.accountid and c.companyid=b.companyid
              where a.companyid = ".$_GET['company']."
              and year(b.journaldate) = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
              and month(b.journaldate) = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
              and c.accountcode between '110501' and '1105019999999999999999'
              and (b.referenceno like 'GR-%' or b.referenceno like 'GRR-%') ";
    $pembelian = $connection->createCommand($sql18)->queryScalar();
    
    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(3,35,'A. Liquiditas Ratio','B');
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(10,42,'- Current Ratio');
    $this->pdf->text(55,39,'Aktiva Lancar');
    $this->pdf->text(40,42,'= ');
    $this->pdf->text(45,40,'_______________________');
    $this->pdf->text(88,42,'x 100% ');
    $this->pdf->text(52,44,'Kewajiban Lancar');
    $this->pdf->text(50,51,Yii::app()->format->formatCurrency($aktivalancar/$per));
    $this->pdf->text(40,54,'= ');
    $this->pdf->text(45,52,'_______________________');
    $this->pdf->text(88,54,'x 100% ');
    $this->pdf->text(50,56,Yii::app()->format->formatCurrency($kewajibanlancar/$per));
    $this->pdf->text(40,62,'= ');
    $this->pdf->text(45,62,Yii::app()->format->formatCurrency(($aktivalancar/$kewajibanlancar)*100).' %');
    
    $this->pdf->text(10,72,'- Quick Ratio');
    $this->pdf->text(47,69,'Aktiva Lancar - Persediaan');
    $this->pdf->text(40,72,'= ');
    $this->pdf->text(45,70,'_______________________');
    $this->pdf->text(88,72,'x 100% ');
    $this->pdf->text(53,74,'Kewajiban Lancar');
    $this->pdf->text(40,84,'= ');
    $this->pdf->text(50,81,Yii::app()->format->formatCurrency(($aktivalancar-($fg+$wip+$rw))/$per));
    $this->pdf->text(45,82,'_______________________');
    $this->pdf->text(50,86,Yii::app()->format->formatCurrency($kewajibanlancar/$per));
    $this->pdf->text(88,84,'x 100% ');
    $this->pdf->text(40,92,'= ');
    $this->pdf->text(45,92,Yii::app()->format->formatCurrency((($aktivalancar - ($fg+$wip+$rw))/$kewajibanlancar)*100).' %');
    
    $this->pdf->text(10,102,'- Cash Ratio');
    $this->pdf->text(40,102,'= ');
    $this->pdf->text(58,99,'Kas & Bank');
    $this->pdf->text(45,100,'_______________________');
    $this->pdf->text(53,104,'Kewajiban Lancar');
    $this->pdf->text(88,102,'x 100% ');
    $this->pdf->text(40,112,'= ');
    $this->pdf->text(50,109,Yii::app()->format->formatCurrency(($kas+$bank)/$per));
    $this->pdf->text(45,110,'_______________________');
    $this->pdf->text(50,114,Yii::app()->format->formatCurrency($kewajibanlancar/$per));
    $this->pdf->text(88,112,'x 100% ');
    $this->pdf->text(40,120,'= ');
    $this->pdf->text(45,120,Yii::app()->format->formatCurrency((($kas + $bank)/$kewajibanlancar)*100).' %');
    
    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(3,135,'B. Solvabilitas Ratio','B');
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(13,140,'Total Assets To');
    $this->pdf->text(10,142,' - ');
    $this->pdf->text(13,145,'Debt ratio');
    $this->pdf->text(40,143,'= ');
    $this->pdf->text(55,140,'Jumlah Aktiva');
    $this->pdf->text(45,141,'_______________________');
    $this->pdf->text(52,145,'Jumlah Kewajiban');
    $this->pdf->text(88,142,'x 100% ');
    $this->pdf->text(40,155,'= ');
    $this->pdf->text(50,152,Yii::app()->format->formatCurrency($aktiva/$per));
    $this->pdf->text(45,153,'_______________________');
    $this->pdf->text(50,157,Yii::app()->format->formatCurrency(($kewajibanlancar+$kewajibanjangkapanjang)/$per));
    $this->pdf->text(88,155,'x 100% ');
    $this->pdf->text(40,163,'= ');
    $this->pdf->text(45,163,Yii::app()->format->formatCurrency(($aktiva/($kewajibanlancar+$kewajibanjangkapanjang))*100).' %');
    
    $this->pdf->text(13,173,'Capital To');
    $this->pdf->text(10,175,' - ');
    $this->pdf->text(13,178,'Total Debt ratio');
    $this->pdf->text(40,174,'= ');
    $this->pdf->text(54 ,172,'Jumlah Ekuitas');
    $this->pdf->text(45,173,'_______________________');
    $this->pdf->text(52,177,'Jumlah Kewajiban');
    $this->pdf->text(88,175,'x 100% ');
    $this->pdf->text(40,187,'= ');
    $this->pdf->text(50,185,Yii::app()->format->formatCurrency($ekuitas/$per));
    $this->pdf->text(45,186,'_______________________');
    $this->pdf->text(50,190,Yii::app()->format->formatCurrency(($kewajibanlancar+$kewajibanjangkapanjang)/$per));
    $this->pdf->text(88,188,'x 100% ');
    $this->pdf->text(40,196,'= ');
    $this->pdf->text(45,196,Yii::app()->format->formatCurrency(($ekuitas/($kewajibanlancar+$kewajibanjangkapanjang))*100).' %');  

    $this->pdf->text(13,206,'Total Debt To');
    $this->pdf->text(10,208,' - ');
    $this->pdf->text(13,211,'Total Assets Ratio');
    $this->pdf->text(40,207,'= ');
    $this->pdf->text(52,205,'Jumlah Kewajiban');
    $this->pdf->text(45,206,'_______________________');
    $this->pdf->text(55,210,'Jumlah Aktiva');
    $this->pdf->text(88,208,'x 100% ');
    $this->pdf->text(40,220,'= ');
    $this->pdf->text(50,218,Yii::app()->format->formatCurrency(($kewajibanlancar+$kewajibanjangkapanjang)/$per));
    $this->pdf->text(45,219,'_______________________');
    $this->pdf->text(50,223,Yii::app()->format->formatCurrency($aktiva/$per));
    $this->pdf->text(88,219,'x 100% ');
    $this->pdf->text(40,229,'= ');
    $this->pdf->text(45,229,Yii::app()->format->formatCurrency((($kewajibanlancar + $kewajibanjangkapanjang)/$aktiva)*100).' %');
    
    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,35,'C. Rentabilitas Ratio','B');
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(113,40,'Net Operating To');
    $this->pdf->text(166,39,'Laba Bersih');
    $this->pdf->text(110,42,'- ');
    $this->pdf->text(150,42,'= ');
    $this->pdf->text(155,40,'_____________________');
    $this->pdf->text(195,42,'x 100% ');
    $this->pdf->text(113,45,'Net Revenue Ratio');
    $this->pdf->text(166,44,'Penjualan Bersih');
    $this->pdf->text(160,51,Yii::app()->format->formatCurrency($labarugibersih/$per));
    $this->pdf->text(150,54,'= ');
    $this->pdf->text(155,52,'_____________________');
    $this->pdf->text(195,54,'x 100% ');
    $this->pdf->text(160,56,Yii::app()->format->formatCurrency($penjualanbersih/$per));
    $this->pdf->text(150,62,'= ');
    $this->pdf->text(155,62,Yii::app()->format->formatCurrency(($labarugibersih/$penjualanbersih)*100).' %'); 
    
    $this->pdf->text(113,69,'Gross Profit To');
    $this->pdf->text(166,69,'Laba Kotor');
    $this->pdf->text(110,72,'- ');
    $this->pdf->text(150,72,'= ');
    $this->pdf->text(155,70,'_____________________');
    $this->pdf->text(195,72,'x 100% ');
    $this->pdf->text(113,74,'Cost of Good Sold');
    $this->pdf->text(170,74,'HPP');
    $this->pdf->text(150,84,'= ');
    $this->pdf->text(155,82,'_____________________');
    $this->pdf->text(195,84,'x 100% ');
    $this->pdf->text(160,81,Yii::app()->format->formatCurrency($labakotor/$per));
    $this->pdf->text(160,86,Yii::app()->format->formatCurrency(-$hpp/$per));
    $this->pdf->text(150,92,'= ');
    $this->pdf->text(155,92,Yii::app()->format->formatCurrency(($labakotor/-$hpp)*100).' %');
    
    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,102,'D. Average Collection Period','B');
    $this->pdf->text(108,107,'(Umur Piutang Dagang)');
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(150,105,'= ');
    $this->pdf->text(166,102,'P/D + P/G');
    $this->pdf->text(155,103,'_____________________');
    $this->pdf->text(163,107,'Penjualan Bersih');
    $this->pdf->text(195,105,'x 30 ');
    $this->pdf->text(150,115,'= ');
    $this->pdf->text(155,113,'_____________________');
    $this->pdf->text(195,115,'x 30 ');
    $this->pdf->text(160,112,Yii::app()->format->formatCurrency(($piutangdagang+$piutanggiro)/$per));
    $this->pdf->text(160,117,Yii::app()->format->formatCurrency($penjualanbersih/$per));
    $this->pdf->text(150,123,'= ');
    $this->pdf->text(155,123,Yii::app()->format->formatCurrency((($piutangdagang+$piutanggiro)/$penjualanbersih)*30).' Hari');  

    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,133,'E. Average Days Inventory','B');
    $this->pdf->text(108,138,'(Umur Persediaan/Stok)'); 
    
    $this->pdf->SetFont('Arial','',9);      
    $this->pdf->text(150,136,'= ');
    $this->pdf->text(165,133,'Persediaan');
    $this->pdf->text(155,134,'_____________________');
    $this->pdf->text(169,138,'HPP');
    $this->pdf->text(195,135,'x 30 ');
    $this->pdf->text(150,148,'= ');
    $this->pdf->text(160,145,Yii::app()->format->formatCurrency(($fg+$wip+$rw)/$per));
    $this->pdf->text(155,146,'_____________________');
    $this->pdf->text(160,150,Yii::app()->format->formatCurrency(-$hpp/$per));
    $this->pdf->text(195,148,'x 30 ');
    $this->pdf->text(150,156,'= ');
    $this->pdf->text(155,156,Yii::app()->format->formatCurrency((($fg+$wip+$rw)/-$hpp)*30).' Hari');

    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,165,'F. Average Payment Period','B');
    $this->pdf->text(108,170,'(Umur Hutang Dagang)'); 
    
    if ($pembelian == '') {$pembelianper = 0; $averagepaymentperiod = 0;} else {$pembelianper = $pembelian/$per;$averagepaymentperiod = (($hutangdagang+$hutanggiro)/$pembelian)*30;}
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(150,167,'= ');
    $this->pdf->text(166,165,'H/D + H/G');
    $this->pdf->text(155,166,'_____________________');
    $this->pdf->text(163,170,'Pembelian Kredit');
    $this->pdf->text(195,168,'x 30 ');
    $this->pdf->text(150,180,'= ');
    $this->pdf->text(160,178,Yii::app()->format->formatCurrency(($hutangdagang+$hutanggiro)/$per));
    $this->pdf->text(155,179,'_____________________');
    $this->pdf->text(160,183,Yii::app()->format->formatCurrency($pembelianper));
    $this->pdf->text(195,181,'x 30 ');
    $this->pdf->text(150,189,'= ');
    $this->pdf->text(155,189,Yii::app()->format->formatCurrency($averagepaymentperiod).' Hari');

    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,198,'G. ROI / Return On Investment','B');
    $this->pdf->text(105,203,'(Hasil Pengembalian Investasi)'); 
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(150,200,'= ');
    $this->pdf->text(165,198,'Laba Bersih');
    $this->pdf->text(155,199,'_____________________');
    $this->pdf->text(163,203,'Jumlah Aktiva');
    $this->pdf->text(195,201,'x 100% ');
    $this->pdf->text(150,213,'= ');
    $this->pdf->text(160,211,Yii::app()->format->formatCurrency($labarugibersih/$per));
    $this->pdf->text(155,212,'_____________________');
    $this->pdf->text(160,216,Yii::app()->format->formatCurrency($aktiva/$per));
    $this->pdf->text(195,212,'x 100% ');
    $this->pdf->text(150,222,'= ');
    $this->pdf->text(155,222,Yii::app()->format->formatCurrency(($labarugibersih/$aktiva)*100).' %');

    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,231,'H. ROE / Return On Equity','B');
    $this->pdf->text(105,236,'(Hasil Pengembalian Ekuitas)'); 
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(150,233,'= ');
    $this->pdf->text(165,231,'Laba Bersih');
    $this->pdf->text(155,232,'_____________________');
    $this->pdf->text(167,236,'Ekuitas');
    $this->pdf->text(195,234,'x 100% ');
    $this->pdf->text(150,246,'= ');
    $this->pdf->text(160,244,Yii::app()->format->formatCurrency($labarugibersih/$per));
    $this->pdf->text(155,245,'_____________________');
    $this->pdf->text(160,249,Yii::app()->format->formatCurrency($ekuitas/$per));
    $this->pdf->text(195,245,'x 100% ');
    $this->pdf->text(150,255,'= ');
    $this->pdf->text(155,255,Yii::app()->format->formatCurrency(($labarugibersih/$ekuitas)*100).' %');
    
    $this->pdf->Output();
  }
  public function actionDownNeracaUjiCobaPDF() {
        parent::actionDownload();
		$i=0;$bulanini=0;$bulanlalu=0;
        $companyid = $_GET['company'];
        $per = $_GET['per'];
		$sql = "select * from(select a.accountid,a.companyid,a.accountcode,a.accountname,a.parentaccountid,a.currencyid,a.accounttypeid,a.recordstatus,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					join genjournal c on c.genjournalid=b.genjournalid
					where b.accountid = a.accountid and c.journaldate <= last_day('".date(Yii::app()->params['datetodb'], strtotime($_GET['date']))."')
					group by accountid asc),0) as bulanini,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					join genjournal c on c.genjournalid=b.genjournalid
					where b.accountid = a.accountid and c.journaldate <= last_day(date_sub('".date(Yii::app()->params['datetodb'], strtotime($_GET['date']))."',interval 1 month))
					group by accountid asc),0) as bulanlalu
					from account a
					where a.companyid = '".$companyid."' and a.accountcode < '19%') z 
					where z.bulanini <> 0 or z.bulanlalu <> 0
					order by accountcode asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Neraca - Uji Coba';
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['datetodb'], strtotime($_GET['date']));
		$this->pdf->AddPage('P');

		$this->pdf->sety($this->pdf->gety());
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C');
		$this->pdf->setwidths(array(10,80,30,35,35));
		$this->pdf->colheader = array('No','Nama Akun','Kode Akun','Bulan Ini','Bulan Lalu');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('R','L','L','R','R');
		
		foreach($dataReader as $row)
		{
			$this->pdf->SetFont('Arial','',8);
			$i+=1;
			$this->pdf->row(array(
				$i,$row['accountname'],
				$row['accountcode'],
				Yii::app()->format->formatCurrency($row['bulanini']/$per),
				Yii::app()->format->formatCurrency($row['bulanlalu']/$per),
			));
			$bulanini += $row['bulanini']/$per;
			$bulanlalu += $row['bulanlalu']/$per;
			$this->pdf->checkPageBreak(10);
		}
		$this->pdf->SetFont('Arial','BI',8);
		$this->pdf->row(array(
			'','',
			'TOTAL AKTIVA',
			Yii::app()->format->formatCurrency($bulanini),
			Yii::app()->format->formatCurrency($bulanlalu),
		));
				
		$i=0;$bulanini=0;$bulanlalu=0;
		$sql = "select * from(select a.accountid,a.companyid,a.accountcode,a.accountname,a.parentaccountid,a.currencyid,a.accounttypeid,a.recordstatus,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					join genjournal c on c.genjournalid=b.genjournalid
					where b.accountid = a.accountid and c.journaldate <= last_day('".date(Yii::app()->params['datetodb'], strtotime($_GET['date']))."')
					group by accountid asc),0) as bulanini,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					join genjournal c on c.genjournalid=b.genjournalid
					where b.accountid = a.accountid and c.journaldate <= last_day(date_sub('".date(Yii::app()->params['datetodb'], strtotime($_GET['date']))."',interval 1 month))
					group by accountid asc),0) as bulanlalu
					from account a
					where a.companyid = '".$companyid."' and a.accountcode between '2%' and '29%'
					order by a.accountcode asc) z where z.bulanini <> 0 or z.bulanlalu <> 0
					order by accountcode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Neraca - Uji Coba';
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['datetodb'], strtotime($_GET['date']));
		$this->pdf->AddPage('P');

		$this->pdf->sety($this->pdf->gety());
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C');
		$this->pdf->setwidths(array(10,80,30,35,35));
		$this->pdf->colheader = array('No','Nama Akun','Kode Akun','Bulan Ini','Bulan Lalu');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('R','L','L','R','R');
		
		foreach($dataReader as $row)
		{
			$this->pdf->SetFont('Arial','',8);
			$i+=1;
			$this->pdf->row(array(
				$i,$row['accountname'],
				$row['accountcode'],
				Yii::app()->format->formatCurrency($row['bulanini']/$per),
				Yii::app()->format->formatCurrency($row['bulanlalu']/$per),
			));
			$bulanini += $row['bulanini']/$per;
			$bulanlalu += $row['bulanlalu']/$per;
			$this->pdf->checkPageBreak(10);
		}
		$this->pdf->SetFont('Arial','BI',8);
		$this->pdf->row(array(
			'','',
			'TOTAL PASIVA',
			Yii::app()->format->formatCurrency($bulanini),
			Yii::app()->format->formatCurrency($bulanlalu),
		));	
		
		$this->pdf->Output();
  }
  public function actionDownNeracaUjiCobaXLS() {
        $this->menuname='neracaujicoba';
		parent::actionDownxls();
        $companyid = $_GET['company'];
        $per = $_GET['per'];
		$sql = "select * from(select a.accountid,a.companyid,a.accountcode,a.accountname,a.parentaccountid,a.currencyid,a.accounttypeid,a.recordstatus,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					join genjournal c on c.genjournalid=b.genjournalid
					where b.accountid = a.accountid and c.journaldate <= last_day('".date(Yii::app()->params['datetodb'], strtotime($_GET['date']))."')
					group by accountid asc),0) as bulanini,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					join genjournal c on c.genjournalid=b.genjournalid
					where b.accountid = a.accountid and c.journaldate <= last_day(date_sub('".date(Yii::app()->params['datetodb'], strtotime($_GET['date']))."',interval 1 month))
					group by accountid asc),0) as bulanlalu
					from account a
					where a.companyid = '".$companyid."' and a.accountcode < '19%') z 
					where z.bulanini <> 0 or z.bulanlalu <> 0
					order by accountcode asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		$this->phpExcel->setActiveSheetIndex(0)			
			->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($_GET['date'])))
			->setCellValueByColumnAndRow(4,1,getcompanycode($companyid));
		$line=4;
		$i=0;$bulanini=0;$bulanlalu=0;			
		$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,'No')
					->setCellValueByColumnAndRow(1,$line,'Nama Akun')
					->setCellValueByColumnAndRow(2,$line,'Kode Akun')					
					->setCellValueByColumnAndRow(3,$line,'Bulan Ini')
					->setCellValueByColumnAndRow(4,$line,'Bulan Lalu');
		$line++;
		foreach($dataReader as $row)
		{
			$i+=1;
			$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row['accountname'])
					->setCellValueByColumnAndRow(2,$line,$row['accountcode'])
					->setCellValueByColumnAndRow(3,$line,$row['bulanini']/$per)
					->setCellValueByColumnAndRow(4,$line,$row['bulanlalu']/$per)	;
			$line++;
			$bulanini += $row['bulanini']/$per;
			$bulanlalu += $row['bulanlalu']/$per;
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(1,$line,'TOTAL AKTIVA')			
					->setCellValueByColumnAndRow(3,$line,$bulanini)										
					->setCellValueByColumnAndRow(4,$line,$bulanlalu);
		$line++;
		
		$i=0;$bulanini=0;$bulanlalu=0;
		$sql = "select * from(select a.accountid,a.companyid,a.accountcode,a.accountname,a.parentaccountid,a.currencyid,a.accounttypeid,a.recordstatus,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					join genjournal c on c.genjournalid=b.genjournalid
					where b.accountid = a.accountid and c.journaldate <= last_day('".date(Yii::app()->params['datetodb'], strtotime($_GET['date']))."')
					group by accountid asc),0) as bulanini,
					ifnull((select sum(b.debit*b.ratevalue)-sum(b.credit*b.ratevalue)
					from genledger b
					join genjournal c on c.genjournalid=b.genjournalid
					where b.accountid = a.accountid and c.journaldate <= last_day(date_sub('".date(Yii::app()->params['datetodb'], strtotime($_GET['date']))."',interval 1 month))
					group by accountid asc),0) as bulanlalu
					from account a
					where a.companyid = '".$companyid."' and a.accountcode between '2%' and '29%'
					order by a.accountcode asc) z where z.bulanini <> 0 or z.bulanlalu <> 0
					order by accountcode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		
		foreach($dataReader as $row)
		$this->phpExcel->setActiveSheetIndex(0)			
			->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['datetodb'], strtotime($_GET['date'])))
			->setCellValueByColumnAndRow(4,1,getcompanycode($companyid));
		$line++;
					
		$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,'No')
					->setCellValueByColumnAndRow(1,$line,'Nama Akun')
					->setCellValueByColumnAndRow(2,$line,'Kode Akun')					
					->setCellValueByColumnAndRow(3,$line,'Bulan Ini')
					->setCellValueByColumnAndRow(4,$line,'Bulan Lalu');
		$line++;
		
		foreach($dataReader as $row)
		{
			$i+=1;
			$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row['accountname'])
					->setCellValueByColumnAndRow(2,$line,$row['accountcode'])
					->setCellValueByColumnAndRow(3,$line,$row['bulanini']/$per)
					->setCellValueByColumnAndRow(4,$line,$row['bulanlalu']/$per)	;
			$line++;
			$bulanini += $row['bulanini']/$per;
			$bulanlalu += $row['bulanlalu']/$per;
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(1,$line,'TOTAL AKTIVA')			
					->setCellValueByColumnAndRow(3,$line,$bulanini)										
					->setCellValueByColumnAndRow(4,$line,$bulanlalu);
		$line++;
		
		$this->getFooterXLS($this->phpExcel);
  }
}