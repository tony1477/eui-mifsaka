<?php
class GiheaderController extends Controller {
  public $menuname = 'giheader';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexgiretur() {
    if (isset($_GET['grid']))
      echo $this->searchgiretur();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexinvoice() {
    if (isset($_GET['grid']))
      echo $this->searchinvoice();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into giheader (gidate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insgi').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'giheaderid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $giheaderid = isset($_POST['giheaderid']) ? $_POST['giheaderid'] : '';
    $gino       = isset($_POST['gino']) ? $_POST['gino'] : '';
    $sono       = isset($_POST['sono']) ? $_POST['sono'] : '';
    $headernote = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $customer   = isset($_POST['customer']) ? $_POST['customer'] : '';
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'giheaderid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();	
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appgi')")->queryScalar();
		$connection		= Yii::app()->db;
		$from = '
			from giheader t 
			left join soheader a on a.soheaderid = t.soheaderid 
			left join addressbook b on b.addressbookid = a.addressbookid 
			left join company c on c.companyid = a.companyid ';
		$where = "
			where (coalesce(t.giheaderid,'') like '%".$giheaderid."%') and (coalesce(t.gino,'') like '%".$gino."%')  
				and (coalesce(a.sono,'') like '%".$sono."%') and (coalesce(b.fullname,'') like '%".$customer."%') 
				and (coalesce(t.headernote,'') like '%".$headernote."%')
				and t.recordstatus > 0 and t.recordstatus < {$maxstat} and t.recordstatus in (".getUserRecordStatus('listgi').") and a.companyid in (".getUserObjectValues('company').")";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select t.giheaderid,t.gino,t.gidate,t.soheaderid,a.sono,a.pocustno,b.fullname,a.shipto,t.headernote,
		t.statusname,t.recordstatus,c.companyid,c.companyname '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'gidate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gidate'])),
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'pocustno' => $data['pocustno'],
        'fullname' => $data['fullname'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'shipto' => $data['shipto'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgiheader' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchinvoice() {
    header("Content-Type: application/json");
    $gino       = isset($_GET['q']) ? $_GET['q'] : '';
    $page       = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : 'giheaderid';
    $order      = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$connection		= Yii::app()->db;
		$from = '
			from giheader t 
			left join soheader a on a.soheaderid = t.soheaderid 
			left join addressbook b on b.addressbookid = a.addressbookid
			left join company c on c.companyid = t.companyid ';
		$where = "
			where (coalesce(t.gino,'') like '%".$gino."%')
				and t.recordstatus = 3 and t.recordstatus in (select b.wfbefstat
					from workflow a
					inner join wfgroup b on b.workflowid = a.workflowid
					inner join groupaccess c on c.groupaccessid = b.groupaccessid
					inner join usergroup d on d.groupaccessid = c.groupaccessid
					inner join useraccess e on e.useraccessid = d.useraccessid
					where upper(a.wfname) = upper('listgi') and upper(e.username)=upper('" . Yii::app()->user->name . "') and
					a.companyid in (select gm.menuvalueid from groupmenuauth gm
					inner join menuauth ma on ma.menuauthid = gm.menuauthid
					where upper(ma.menuobject) = upper('company') and gm.groupaccessid = c.groupaccessid)) 
          and t.giheaderid not in (select k.giheaderid from invoice k where k.recordstatus > 0)";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select t.giheaderid,t.gino,t.gidate,t.soheaderid,a.sono,a.pocustno,b.fullname,a.shipto,t.headernote,c.companyname,t.statusname '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'sono' => $data['sono'],
        'customername' => $data['fullname'],
        'companyname' => $data['companyname'],
        'gidate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gidate'])),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchgiretur() {
    header("Content-Type: application/json");
    $gino       = isset($_GET['q']) ? $_GET['q'] : '';
    $page       = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : 'giheaderid';
    $order      = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$connection		= Yii::app()->db;
		$from = '
			from giheader t 
			left join soheader a on a.soheaderid = t.soheaderid 
			left join addressbook b on b.addressbookid = a.addressbookid 
			left join company c on c.companyid = t.companyid ';
		$where = "
			where (coalesce(t.gino,'') like '%".$gino."%')
				and t.recordstatus = 3 and t.recordstatus in (select b.wfbefstat
					from workflow a
					inner join wfgroup b on b.workflowid = a.workflowid
					inner join groupaccess c on c.groupaccessid = b.groupaccessid
					inner join usergroup d on d.groupaccessid = c.groupaccessid
					inner join useraccess e on e.useraccessid = d.useraccessid
					where upper(a.wfname) = upper('listgi') and upper(e.username)=upper('" . Yii::app()->user->name . "') and
					a.companyid in (select gm.menuvalueid from groupmenuauth gm
					inner join menuauth ma on ma.menuauthid = gm.menuauthid
					where upper(ma.menuobject) = upper('company') and gm.groupaccessid = c.groupaccessid))";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select t.giheaderid,t.gino,t.gidate,t.soheaderid,a.sono,a.pocustno,b.fullname,a.shipto,t.headernote,c.companyname,t.statusname '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'sono' => $data['sono'],
        'fullname' => $data['fullname'],
        'companyname' => $data['companyname'],
        'gidate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gidate'])),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchDetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'sodetailid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset = ($page - 1) * $rows;
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    $footer = array();
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcodec,c.sloccode,d.description')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,d.description,c.description as slocdesc')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'gidetailid' => $data['gidetailid'],
        'giheaderid' => $data['giheaderid'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['description'],
        'itemnote' => $data['itemnote']
      );
    }
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $cmd      = Yii::app()->db->createCommand()->select('sum(t.qty) as totalqty')->from('gidetail t')->where('giheaderid = :giheaderid', array(
      ':giheaderid' => $id
    ))->queryRow();
    $footer[] = array(
      'productid' => 'Total',
      'qty' => Yii::app()->format->formatNumber($cmd['totalqty'])
    );
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionGenerateso() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGISO(:vid, :vhid)';
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
  }
	private function ModifyData($arraydata) {
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertgi(:vgidate,:vgino,:vsoheaderid,:vheadernote,:vrecordstatus, :vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vgino', $arraydata[2], PDO::PARAM_STR);
        $command->bindvalue(':vrecordstatus', $arraydata[5], PDO::PARAM_STR);
      } else {
        $sql     = 'call Updategi(:vid,:vgidate,:vsoheaderid,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $arraydata[0]);
      }
      $command->bindvalue(':vgidate', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vsoheaderid', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $arraydata[4], PDO::PARAM_STR);
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
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileGiheader"]["name"]);
		if (move_uploaded_file($_FILES["FileGiheader"]["tmp_name"], $target_file)) {
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
					$gino = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$gidate = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$sono = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$soheaderid = Yii::app()->db->createCommand("select soheaderid from soheader where sono = '".$sono."' and companyid = ".$companyid)->queryScalar();
					$abid = Yii::app()->db->createCommand("select giheaderid from giheader where gino = '".$gino."' and companyid = ".$companyid)->queryScalar();
					if ($abid == '') {		
						$headernote = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$this->ModifyData(array('',date(Yii::app()->params['datetodb'], strtotime($gidate)),$gino,$soheaderid,$headernote,$recordstatus));
						$abid = Yii::app()->db->createCommand("select giheaderid from giheader where gino = '".$gino."' and companyid = ".$companyid)->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(7, $row)->getValue() != '') {
							$productname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
							$qty = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$sloccode = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
							$storagebin = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$storagebinid = Yii::app()->db->createCommand("select storagebinid from storagebin where slocid = '".$slocid."' and description = '".$storagebin."'")->queryScalar();
							$itemnote = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$this->ModifyDataDetail(array('',$abid,$productid,$qty,$slocid,$storagebinid,$itemnote));
						}
					}
				}
    }
	}
  public function actionSave() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $this->ModifyData(array((isset($_POST['giheaderid'])?$_POST['giheaderid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['gidate'])),
			'',$_POST['soheaderid'],$_POST['headernote']));
  }
	private function ModifyDataDetail($arraydata) {
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertgidetail(:vgiheaderid,:vproductid,:vqty,:vslocid,:vstoragebinid,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
				$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
				$command->bindvalue(':vslocid', $arraydata[4], PDO::PARAM_STR);
      } else {
        $sql     = 'call Updategidetail(:vid,:vgiheaderid,:vqty,:vstoragebinid,:vitemnote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
      }
			$command->bindvalue(':vgiheaderid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vitemnote', $arraydata[6], PDO::PARAM_STR);
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
    $this->ModifyDataDetail(array((isset($_POST['gidetailid'])?$_POST['gidetailid']:''),$_POST['giheaderid'],'',str_replace(",", "", $_POST['qty']),'',
			$_POST['storagebinid'],$_POST['itemnote']));
  }
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteGI(:vid,:vcreatedby)';
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
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveGI(:vid,:vcreatedby)';
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
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgegiheader(:vid,:vcreatedby)';
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
        $sql     = 'call PurgeGidetail(:vid,:vcreatedby)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select b.companyid, a.gino,a.gidate,b.sono ,b.shipto,a.giheaderid,a.headernote,
						a.recordstatus,c.fullname as customer,d.fullname as sales,f.cityname,b.isdisplay,
						(
						select distinct g.mobilephone
						from addresscontact g 
						where g.addressbookid = c.addressbookid
						limit 1 
						) as hp,
							(
						select distinct h.phoneno
						from address h
						where h.addressbookid = c.addressbookid
						limit 1 
						) as phone 
						from giheader a
						left join soheader b on b.soheaderid = a.soheaderid 
						left join addressbook c on c.addressbookid = b.addressbookid
						left join employee d on d.employeeid = b.employeeid
						left join company e on e.companyid = b.companyid
						left join city f on f.cityid = e.cityid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.giheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('giheader');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AddFont('tahoma', '', 'tahoma.php');
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('tahoma');
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(9);
      if($row['isdisplay']) $this->pdf->Image('images/DISPLAY.jpg', 0, 8, 210, 135);
      $this->pdf->text(10, $this->pdf->gety() + 0, 'No ');
      $this->pdf->text(25, $this->pdf->gety() + 0, ': ' . $row['gino']);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Sales ');
      $this->pdf->text(25, $this->pdf->gety() + 5, ': ' . $row['sales']);
      $this->pdf->text(140, $this->pdf->gety() + 0, $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])));
      $this->pdf->text(10, $this->pdf->gety() + 10, 'No. SO ');
      $this->pdf->text(25, $this->pdf->gety() + 10, ': ' . $row['sono']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Dengan hormat,');
      $this->pdf->text(10, $this->pdf->gety() + 20, 'Bersama ini kami kirimkan barang-barang sebagai berikut:');
      $this->pdf->text(140, $this->pdf->gety() + 5, 'Kepada Yth, ');
      $this->pdf->text(140, $this->pdf->gety() + 10, $row['customer']);
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,d.description,f.description as rak,itemnote
								from gidetail a
								inner join product b on b.productid = a.productid
								inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
								inner join sloc d on d.slocid = a.slocid
								left join storagebin f on f.storagebinid = a.storagebinid
								where giheaderid = " . $row['giheaderid'] . " group by b.productname,a.sodetailid order by sodetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->colalign = array(
        'L',
        'L',
        'C',
        'L',
        'L',
        'C',
        'L'
      );
      $this->pdf->setwidths(array(
        8,
        90,
        20,
        10,
        50,
        28
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Gudang - Rak',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
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
          Yii::app()->format->formatCurrency($row1['vqty']),
          $row1['uomcode'],
          $row1['description'] . ' - ' . $row1['rak'],
          $row1['itemnote']
        ));
      }
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        170
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Ship To',
        $row['shipto'] . ' / ' . $row['phone'] . ' / ' . $row['hp']
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->colalign = array(
        'C'
      );
      $this->pdf->setwidths(array(
        150
      ));
      $this->pdf->coldetailalign = array(
        'L'
      );
      $this->pdf->row(array(
        'Barang-barang tersebut diatas kami (saya) periksa dan terima dengan baik serta cukup.'
      ));
      $this->pdf->checkNewPage(20);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Disetujui oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(137, $this->pdf->gety(), 'Dibawa oleh,');
      $this->pdf->text(178, $this->pdf->gety(), ' Diterima oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(137, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(178, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, 'Admin Gudang');
      $this->pdf->text(55, $this->pdf->gety() + 25, ' Kepala Gudang');
      $this->pdf->text(96, $this->pdf->gety() + 25, '     Distribusi');
      $this->pdf->text(137, $this->pdf->gety() + 25, '        Supir');
      $this->pdf->text(178, $this->pdf->gety() + 25, 'Customer/Toko');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'giheader';
    parent::actionDownxls();
    $sql = "select b.companyid, a.gino,a.gidate,b.sono ,b.shipto,a.giheaderid,a.headernote,
						a.recordstatus,c.fullname as customer,d.fullname as sales,f.cityname,g.mobilephone as hp,h.phoneno as phone
						from giheader a
						left join soheader b on b.soheaderid = a.soheaderid 
						left join addressbook c on c.addressbookid = b.addressbookid
						left join employee d on d.employeeid = b.employeeid
						left join company e on e.companyid = d.companyid
						left join city f on f.cityid = e.cityid
						left join addresscontact g on g.addressbookid = c.addressbookid
						left join address h on h.addressid = c.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.giheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['gino'])->setCellValueByColumnAndRow(4, $line, $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])));
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Sales')->setCellValueByColumnAndRow(1, $line, ': ' . $row['sales'])->setCellValueByColumnAndRow(4, $line, 'Kepada Yth, ');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No. SO')->setCellValueByColumnAndRow(1, $line, ': ' . $row['sono'])->setCellValueByColumnAndRow(4, $line, $row['customer']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dengan hormat,');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Bersama ini kami kirimkan barang-barang sebagai berikut: ');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Barang')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Gudang - Rak')->setCellValueByColumnAndRow(5, $line, 'Keterangan');
      $line++;
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,d.description,f.description as rak,itemnote
								from gidetail a
								inner join product b on b.productid = a.productid
								inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
								inner join sloc d on d.slocid = a.slocid
								left join storagebin f on f.storagebinid = a.storagebinid
								where giheaderid = " . $row['giheaderid'] . " order by sodetailid";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['vqty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['description'] . ' - ' . $row1['rak'])->setCellValueByColumnAndRow(5, $line, $row1['itemnote']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Ship To ')->setCellValueByColumnAndRow(1, $line, $row['shipto'] . ' / ' . $row['phone'] . ' / ' . $row['hp']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Barang-barang tersebut diatas kami (saya) periksa dan terima dengan baik serta cukup.');
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')->setCellValueByColumnAndRow(1, $line, 'Disetujui oleh, ')->setCellValueByColumnAndRow(2, $line, 'Diketahui oleh, ')->setCellValueByColumnAndRow(3, $line, 'Dibawa oleh, ')->setCellValueByColumnAndRow(4, $line, 'Diterima oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................')->setCellValueByColumnAndRow(4, $line, '........................');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Admin Gudang')->setCellValueByColumnAndRow(1, $line, 'Kepala Gudang')->setCellValueByColumnAndRow(2, $line, 'Distribusi')->setCellValueByColumnAndRow(3, $line, 'Supir')->setCellValueByColumnAndRow(4, $line, 'Customer/Toko');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}