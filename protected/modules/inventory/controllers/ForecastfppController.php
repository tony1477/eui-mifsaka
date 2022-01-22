<?php
class ForecastfppController extends Controller {
  public $menuname = 'forecastfpp';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (isset($_GET['id'])) {
    } else {
			$docdate              = new DateTime('now');
			$sql = "insert into forecastfpp (docdate,recordstatus) values ('".$docdate->format('Y-m-d')."',1)";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'forecastfppid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $forecastfppid   = isset($_POST['forecastfppid']) ? $_POST['forecastfppid'] : '';
    $perioddatey        = isset($_POST['perioddateyear']) ? $_POST['perioddateyear'] : '';
    $perioddatem        = isset($_POST['perioddatemonth']) ? $_POST['perioddatemonth'] : '';
    $companyname    = isset($_POST['companyname'])  ? $_POST['companyname'] : '';
    $productname       = isset($_POST['productname']) ? $_POST['productname'] : '';
    $sloccode       = isset($_POST['sloccode']) ? $_POST['sloccode'] : '';
    $recordstatus   = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $collectionname   = isset($_POST['collectionname']) ? $_POST['collectionname'] : '';
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'forecastfppid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
    $perioddate = '';
    $collection = '';
    if($perioddatey != '') {
        $perioddate .= "  year(perioddate) = year('{$perioddatey}-01-01') and ";
    }
    if($perioddatem != '') {
        $perioddate .= "  month(perioddate) = month('2020-{$perioddatem}-01') and ";
    }
    if($collectionname != '') {
        $collection .= " b.collectionname like '%".$collectionname."%' and ";
    }
    $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('forecastfpp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('productcollection b','b.productcollectid = t.productcollectid')
    ->where("{$perioddate} (a.companyname like :companyname) and {$collection} -- (b.collectionname like :collectionname) and
            t.companyid in (".getUserObjectValues('company').")", array(
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
        //':collectionname' => '%' . $collectionname . '%',
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname, b.collectionname')
    ->from('forecastfpp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('productcollection b','b.productcollectid = t.productcollectid')
    ->where("{$perioddate}  (a.companyname like :companyname) and {$collection} -- (b.collectionname like :collectionname) and
            t.companyid in (".getUserObjectValues('company').")", array(
        
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
        //':collectionname' => '%' . $collectionname . '%',
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'forecastfppid' => $data['forecastfppid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'perioddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['perioddate'])),
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'productcollectid' => $data['productcollectid'],
        'collectionname' => $data['collectionname'],
        'headernote' => $data['headernote'],
        'sumpendingpo' => Yii::app()->format->formatCurrency($data['sumpendingpo']),
        'sumpredictpo' => Yii::app()->format->formatCurrency($data['sumpredictpo']),
        'sumtotalpo' => Yii::app()->format->formatCurrency($data['sumtotalpo']),
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.forecastfppdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('forecastfppdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('forecastfppid = :forecastfppid', array(
      ':forecastfppid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,d.sloccode,d.description')
    	->from('forecastfppdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('forecastfppid = :forecastfppid', array(
      ':forecastfppid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'forecastfppdetid' => $data['forecastfppdetid'],
        'forecastfppid' => $data['forecastfppid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'qtyforecast' => Yii::app()->format->formatNumber($data['qtyforecast']),
        'avg3month' => Yii::app()->format->formatNumber($data['avg3month']),
        'avgperday' => Yii::app()->format->formatNumber($data['avgperday']),
        'qtymax' => Yii::app()->format->formatNumber($data['qtymax']),
        'qtymin' => Yii::app()->format->formatNumber($data['qtymin']),
        'leadtime' => Yii::app()->format->formatNumber($data['leadtime']),
        'pendingpo' => Yii::app()->format->formatNumber($data['pendingpo']),
        'saldoawal' => Yii::app()->format->formatNumber($data['saldoawal']),
        'grpredict' => Yii::app()->format->formatNumber($data['grpredict']),
        'prqty' => Yii::app()->format->formatNumber($data['prqty']),
        'prqtyreal' => Yii::app()->format->formatNumber($data['prqtyreal']),
        'price' => Yii::app()->format->formatNumber($data['price']),
        'povalueout' => Yii::app()->format->formatNumber($data['povalueout']),
        'povalue' => Yii::app()->format->formatNumber($data['povalue']),
        'povaluetot' => Yii::app()->format->formatNumber($data['povaluetot']),
        'qtyshare' => Yii::app()->format->formatNumber($data['qtyshare']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    
    echo CJSON::encode($result);
  }
  public function actionGeneratefpp() {
    parent::actionDownload();
		$sql = "call GenerateForecastFPP(" . $_REQUEST['companyid'] . ", '" . date(Yii::app()->params['datetodb'], strtotime($_REQUEST['perioddate'])) . "')";
    Yii::app()->db->createCommand($sql)->execute();
		GetMessage('success', 'alreadysaved');

    /*
    if (isset($_POST['id'])) {
			//$sql = "select headernote from forecastfpp where forecastfppid = ".$_POST['id'];
			//$header = Yii::app()->db->createCommand($sql)->queryScalar();
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateForecastFPP(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
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
    */
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertforecastfpp(:vdocdate,:vperioddate,:vcompanyid,:vproductcollect,:vproductid,:vslocid,:vunitofmeasureid,:vleadtime,:vgrpredictreal,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateforecastfpp(:vid,:vdocdate,:vperioddate,:vcompanyid,:vproductcollect,:vheadernote,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vdocdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vperioddate', $arraydata[2], PDO::PARAM_STR);
    $command->bindvalue(':vcompanyid', $arraydata[3], PDO::PARAM_STR);
    $command->bindvalue(':vproductcollect', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
    $target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-forecastfpp"]["name"]);
    if (move_uploaded_file($_FILES["file-forecastfpp"]["tmp_name"], $target_file)) {
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objPHPExcel = $objReader->load($target_file);
      $objWorksheet = $objPHPExcel->getActiveSheet();
      $highestRow = $objWorksheet->getHighestRow(); 
      $highestColumn = $objWorksheet->getHighestColumn();
      $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
			try {
        $company = $objWorksheet->getCellByColumnAndRow(2, 2)->getValue();
        $productcollect = $objWorksheet->getCellByColumnAndRow(6, 2)->getValue();
        $companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$company."'")->queryScalar();
        $productcollectid = Yii::app()->db->createCommand("select productcollectid from productcollection where collectionname = '".$productcollect."'")->queryScalar();
        $perioddate = date(Yii::app()->params['datetodb'],strtotime($objWorksheet->getCellByColumnAndRow(2,3)->getValue()));
        $ch = "select ifnull(count(1),0) from forecastfpp where companyid = {$companyid} and perioddate = '{$perioddate}' and productcollectid = {$productcollectid}";
        $q = Yii::app()->db->createCommand($ch)->queryScalar();
        if($q>0) {
          // get ID
          $forecastfppid = Yii::app()->db->createCommand("select forecastfppid from forecastfpp where companyid = {$companyid} and perioddate = '{$perioddate}' and productcollectid = {$productcollectid}")->queryScalar();
        }
        else {
          // insert new row, and get ID
          $ins = "insert into forecastfpp(docdate,perioddate,companyid,productcollectid,recordstatus)values(curdate(),'{$perioddate}',{$companyid},{$productcollectid},1)";
          $query = Yii::app()->db->createCommand($ins)->execute();
          $forecastfppid = Yii::app()->db->createCommand("select last_insert_id()")->queryScalar();
        }
				for ($row = 5; $row <= $highestRow; ++$row) {
					$detid = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					//$docdate = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					//$perioddate = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					//$companycode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					//$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
          $sloccode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$product = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
          $productid = Yii::app()->db->createCommand("select productid from product where productname = '".$product."'")->queryScalar();
          $uomcode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
          $qtyforecast = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
          $avg3month = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
          $avgperday = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
          $qtymax = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
          $qtymin = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
          $leadtime = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
          $prqtyreal = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
          //$price = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$this->ModifyDataDetailUpload($connection,array($detid,$forecastfppid,$productid,$slocid,$uomid,$qtyforecast,$avg3month,$avgperday,$qtymax,$qtymin,$leadtime,$prqtyreal));
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
      
			$this->ModifyData($connection,array((isset($_POST['forecastfppid'])?$_POST['forecastfppid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),date(Yii::app()->params['datetodb'], strtotime($_POST['perioddate'])),$_POST['companyid'],$_POST['productcollectid'],$_POST['headernote']));
      $transaction->commit();
      
      //var_dump($_POST);
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  private function ModifyDataDetailUpload($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertforecastfppdetupload(:vforecastfppid,:vproductid,:vslocid,:vuomid,:vqtyforecast,:vavg3month,:vavgperday,:vqtymax,:vqtymin,:vleadtime,:vprqtyreal,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateforecastfppdetupload(:vid,:vforecastfppid,:vproductid,:vslocid,:vuomid,:vqtyforecast,:vavg3month,:vavgperday,:vqtymax,:vqtymin,:vleadtime,:vprqtyreal,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vforecastfppid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
    $command->bindvalue(':vslocid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vqtyforecast', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vavg3month', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vavgperday', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vqtymax', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vqtymin', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vleadtime', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vprqtyreal', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  private function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertforecastfppdet(:vforecastfppid,:vproductid,:vslocid,:vuomid,:vqtymax,:vqtymin,:vleadtime,:vprqtyreal,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateforecastfppdet(:vid,:vforecastfppid,:vproductid,:vslocid,:vuomid,:vqtymax,:vqtymin,:vleadtime,:vprqtyreal,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vforecastfppid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
    $command->bindvalue(':vslocid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vqtymax', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vqtymin', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vleadtime', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vprqtyreal', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveDetail() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['forecastfppdetid'])?$_POST['forecastfppdetid']:''),$_POST['forecastfppid'],$_POST['productid'],$_POST['slocid'],
				$_POST['unitofmeasureid'],$_POST['qtymax'],$_POST['qtymin'],$_POST['leadtime'],$_POST['prqtyreal']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if(isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approveforecastfpp(:vid,:vcreatedby)';
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
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Deleteforecastfpp(:vid,:vcreatedby)';
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
  public function actionPurge() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeforecastfpp(:vid,:vcreatedby)';
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
  public function actionPurgedetail()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeForecastfppdet(:vid,:vcreatedby)';
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
    //$forecastfppid = filter_input(INPUT_GET,'forecastfppid');
    $forecastfppid = filter_input(INPUT_GET,'id');
    $perioddate = filter_input(INPUT_GET,'perioddate');
    $companyname = filter_input(INPUT_GET,'companyname');
    $productname = filter_input(INPUT_GET,'productname');
    $sloccode = filter_input(INPUT_GET,'sloccode');
    
    
    
    /*$sql = "select a.forecastfppid,a.perioddate,b.accountname,a.expeditionamount,a.companyid,ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between DATE_ADD(DATE_ADD(LAST_DAY(a.perioddate),INTERVAL 1 DAY),INTERVAL - 1 MONTH) and LAST_DAY(a.perioddate)),0) as realisasi,
            ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between CONCAT(YEAR(a.perioddate),'-01-01') and LAST_DAY(a.perioddate)),0) as kumrealisasi
						from expedition a
						join account b on b.accountid = a.accountid 
						join company c on c.companyid = a.companyid ";
		$forecastfppid = filter_input(INPUT_GET,'forecastfppid');
		$perioddate = filter_input(INPUT_GET,'perioddate');
		$companyname = filter_input(INPUT_GET,'companyname');
		$accountname = filter_input(INPUT_GET,'accountname');
		$accountcode = filter_input(INPUT_GET,'accountcode');
		$sql .= " where coalesce(a.forecastfppid,'') like '%".$forecastfppid."%' 
			and coalesce(a.perioddate,'') like '%".$perioddate."%'
			and coalesce(c.companyname,'') like '%".$companyname."%'
			and coalesce(b.accountname,'') like '%".$accountname."%'
			and coalesce(b.accountcode,'') like '%".$accountcode."%'
			";
    
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.forecastfppid in (" . $_GET['id'] . ")";
    } 
    */
    $sql = "select t.*, ta.perioddate, ta.docdate, a.companyname, b.productname, c.sloccode as sloccode, d.uomcode, ta.companyid
            from forecastfpp ta
            join forecastfppdet t on t.forecastfppid = ta.forecastfppid
            join company a on a.companyid = ta.companyid
            left join product b on b.productid = t.productid
            left join sloc c on c.slocid = t.slocid
            left join unitofmeasure d on d.unitofmeasureid = t.unitofmeasureid
            where coalesce(t.forecastfppid,'') = ".$forecastfppid."
            and coalesce(ta.perioddate,'') like '%".$perioddate."%' 
            and coalesce(a.companyname,'') like '%".$companyname."%' 
            and coalesce(b.productname,'') like '%".$productname."%' 
            and coalesce(c.sloccode,'') like '%".$sloccode."%' 
            ";
    //$sql = $sql . " order by accountname asc ";
    $command          = Yii::app()->db->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row1)
		{
			$this->pdf->companyid = $row1['companyid'];
		}
    $this->pdf->title = GetCatalog('forecastfpp');
    $this->pdf->AddPage('L',array(220,450));
    $this->pdf->setFont('Arial','B',8);
    $this->pdf->colalign  = array(
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
    );
    $this->pdf->colheader = array(
      GetCatalog('ID'),
      GetCatalog('docdate'),
      GetCatalog('perioddate'),
      GetCatalog('company'),
      GetCatalog('sloccode'),
      GetCatalog('product'),
      GetCatalog('uom'),
      GetCatalog('qtyforecast'),
      GetCatalog('avg3month'),
      GetCatalog('avgperday'),
      GetCatalog('qtymax'),
      GetCatalog('qtymin'),
      GetCatalog('leadtime'),
      GetCatalog('pendingpo'),
      GetCatalog('saldoawal'),
      GetCatalog('grpredict'),
      GetCatalog('prqtygen'),
      GetCatalog('prqtyreal'),
      getUserObjectValues('purchasing') == 1 ? GetCatalog('price') : '',
      getUserObjectValues('purchasing') == 1 ? GetCatalog('povalueout') : '',
      getUserObjectValues('purchasing') == 1 ? GetCatalog('povalue') : '',
      getUserObjectValues('purchasing') == 1 ? GetCatalog('povaluetot') : '',
      GetCatalog('qtyshare'),
    );
    $this->pdf->setwidths(array(
      15,
      20,
      17,
      35,
      25,
      35,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial','',8);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['forecastfppid'],
        date(Yii::app()->params['dateviewfromdb'],strtotime($row1['docdate'])),
        date(Yii::app()->params['dateviewfromdb'],strtotime($row1['perioddate'])),
        $row1['companyname'],
        $row1['sloccode'],
        $row1['productname'],
        $row1['uomcode'],
        Yii::app()->format->formatCurrency($row1['qtyforecast']),
        Yii::app()->format->formatCurrency($row1['avg3month']),
        Yii::app()->format->formatCurrency($row1['avgperday']),
        Yii::app()->format->formatCurrency($row1['qtymax']),
        Yii::app()->format->formatCurrency($row1['qtymin']),
        Yii::app()->format->formatCurrency($row1['leadtime']),
        Yii::app()->format->formatCurrency($row1['pendingpo']),
        Yii::app()->format->formatCurrency($row1['saldoawal']),
        Yii::app()->format->formatCurrency($row1['grpredict']),
        Yii::app()->format->formatCurrency($row1['prqty']),
        Yii::app()->format->formatCurrency($row1['prqtyreal']),
        getUserObjectValues('purchasing') == 1  ? Yii::app()->format->formatCurrency($row1['price']) : '',
        getUserObjectValues('purchasing') == 1  ? Yii::app()->format->formatCurrency($row1['povalueout']) : '',
        getUserObjectValues('purchasing') == 1  ? Yii::app()->format->formatCurrency($row1['povalue']) : '',
        getUserObjectValues('purchasing') == 1  ? Yii::app()->format->formatCurrency($row1['povaluetot']) : '',
        Yii::app()->format->formatCurrency($row1['qtyshare']),
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'forecastfpp';
    parent::actionDownxls();
    $forecastfppid = filter_input(INPUT_GET,'id');
    $perioddate = filter_input(INPUT_GET,'perioddate');
    $companyname = filter_input(INPUT_GET,'companyname');
    $productname = filter_input(INPUT_GET,'productname');
    $sloccode = filter_input(INPUT_GET,'sloccode');
      
    $year = date('Y',strtotime($perioddate));
    $month = date('m',strtotime($perioddate));
    $day1 = strtotime(''.$year.'-'.$month.'-01');
    $day2 = strtotime(''.$year.'-'.$month.'-01 -1 month');
    $bulanini = date('Y-m-d',($day1));
    $bulanlalu = date('Y-m-d',($day2));
    
    $sql = "select t.*, ta.perioddate, ta.docdate, a.companyname, a.companycode, b.productname, c.sloccode as sloccode, d.uomcode, ta.companyid,ta.productcollectid, e.collectionname
    from forecastfpp ta
    join forecastfppdet t on t.forecastfppid = ta.forecastfppid
    join company a on a.companyid = ta.companyid
    left join product b on b.productid = t.productid
    left join sloc c on c.slocid = t.slocid
    left join unitofmeasure d on d.unitofmeasureid = t.unitofmeasureid
    left join productcollection e on e.productcollectid = ta.productcollectid
    where coalesce(t.forecastfppid,'') = ".$forecastfppid." 
    and coalesce(ta.perioddate,'') like '%".$perioddate."%' 
    and coalesce(a.companyname,'') like '%".$companyname."%' 
    and coalesce(b.productname,'') like '%".$productname."%' 
    and coalesce(c.sloccode,'') like '%".$sloccode."%'
		order by t.forecastfppid";
      
    $command          = Yii::app()->db->createCommand($sql);
    $dataReader       = $command->queryAll();
    $i=4;
    $header = Yii::app()->db->createCommand($sql)->queryRow();

    $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, 2, 'Perusahaan:')
          ->setCellValueByColumnAndRow(2, 2, $header['companycode'])
          ->setCellValueByColumnAndRow(0, 3, 'Periode')
          ->setCellValueByColumnAndRow(5, 2, 'Product: ')
          ->setCellValueByColumnAndRow(6, 2, $header['collectionname'])
          ->setCellValueByColumnAndRow(2, 3, $header['perioddate']);
    //$i++;
    
    $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, 'ID')
          ->setCellValueByColumnAndRow(1, $i, getCatalog('product'))
          ->setCellValueByColumnAndRow(2, $i, getCatalog('sloc'))
          ->setCellValueByColumnAndRow(3, $i, getCatalog('uom'))
          ->setCellValueByColumnAndRow(4, $i, getCatalog('qtyforecast'))
          ->setCellValueByColumnAndRow(5, $i, getCatalog('avg3month'))
          ->setCellValueByColumnAndRow(6, $i, getCatalog('avgperday'))
          ->setCellValueByColumnAndRow(7, $i, getCatalog('qtymax'))
          ->setCellValueByColumnAndRow(8, $i, getCatalog('qtymin'))
          ->setCellValueByColumnAndRow(9, $i, getCatalog('leadtime'))
          ->setCellValueByColumnAndRow(10, $i, getCatalog('pendingpo'))
          ->setCellValueByColumnAndRow(11, $i, getCatalog('saldoawal'))
          ->setCellValueByColumnAndRow(12, $i, getCatalog('grpredict'))
          ->setCellValueByColumnAndRow(13, $i, getCatalog('prqtygen'))
          ->setCellValueByColumnAndRow(14, $i, getCatalog('prqtyreal'))
          ->setCellValueByColumnAndRow(15, $i, getCatalog('price'))
          ->setCellValueByColumnAndRow(16, $i, getCatalog('povalueout'))
          ->setCellValueByColumnAndRow(17, $i, getCatalog('povalue'))
          ->setCellValueByColumnAndRow(18, $i, getCatalog('povaluetot'))
          ->setCellValueByColumnAndRow(19, $i, getCatalog('qtyshare'));
    $i++;
    
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, $row1['forecastfppdetid'])
          ->setCellValueByColumnAndRow(1, $i, $row1['productname'])
          ->setCellValueByColumnAndRow(2, $i, $row1['sloccode'])
          ->setCellValueByColumnAndRow(3, $i, $row1['uomcode'])
          ->setCellValueByColumnAndRow(4, $i, $row1['qtyforecast'])
          ->setCellValueByColumnAndRow(5, $i, $row1['avg3month'])
          ->setCellValueByColumnAndRow(6, $i, $row1['avgperday'])
          ->setCellValueByColumnAndRow(7, $i, $row1['qtymax'])
          ->setCellValueByColumnAndRow(8, $i, $row1['qtymin'])
          ->setCellValueByColumnAndRow(9, $i, $row1['leadtime'])
          ->setCellValueByColumnAndRow(10, $i, $row1['pendingpo'])
          ->setCellValueByColumnAndRow(11, $i, $row1['saldoawal'])
          ->setCellValueByColumnAndRow(12, $i, $row1['grpredict'])
          ->setCellValueByColumnAndRow(13, $i, $row1['prqty'])
          ->setCellValueByColumnAndRow(14, $i, $row1['prqtyreal'])
          ->setCellValueByColumnAndRow(15, $i, $row1['price'])
          ->setCellValueByColumnAndRow(16, $i, $row1['povalueout'])
          ->setCellValueByColumnAndRow(17, $i, $row1['povalue'])
          ->setCellValueByColumnAndRow(18, $i, $row1['povaluetot'])
          ->setCellValueByColumnAndRow(19, $i, $row1['qtyshare']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
  
}
