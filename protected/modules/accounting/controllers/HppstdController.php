<?php
class HppstdController extends Controller {
  public $menuname = 'hppstd';
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
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into hppstd (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',1)";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'hppstdid' => $id
			));
    }
  }
  public function actionGetProductParent() {
    $product = isset($_POST['product']) ? $_POST['product'] : '';
    $product = isset($_GET['q']) ? $_GET['q'] : $product;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'hppstdid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result = array();
    $row = array();

    $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
      ->from('hppstddet t')
      ->leftjoin('product a','a.productid = t.productid')
      ->leftjoin('unitofmeasure b','b.unitofmeasureid = t.uomid')
      ->where('t.hppstdid = :hppstdid and a.productname like :productname', array(
        ':hppstdid' => $_REQUEST['hppstdid'],
        ':productname' => '%'.$product.'%'
      ))->queryScalar();

    $result['total'] = $cmd;

    $cmd = Yii::app()->db->createCommand()->select('t.*, a.productname')
      ->from('hppstddet t')
      ->leftjoin('product a','a.productid = t.productid')
      ->leftjoin('unitofmeasure b','b.unitofmeasureid = t.uomid')
      ->where('t.hppstdid = :hppstdid and a.productname like :productname', array(
        ':hppstdid' => $_REQUEST['hppstdid'],
        ':productname' => '%'.$product.'%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();

    foreach ($cmd as $data) {
      $row[] = array(
        'hppstddetid' => $data['hppstddetid'],
        'hppstdcode' => $data['hppstdcode'],
        'productname' => $data['productname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function search() {
    header("Content-Type: application/json");
    $hppstdid       = isset($_POST['hppstdid']) ? $_POST['hppstdid'] : '';
    $plantid  = isset($_POST['plantid']) ? $_POST['plantid'] : '';
    $productid   = isset($_POST['product']) ? $_POST['product'] : '';
    $price         = isset($_POST['price']) ? $_POST['price'] : '';
    $uomid       = isset($_POST['uomid']) ? $_POST['uomid'] : '';
    $docdate     = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $recordstatus = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $hppstdid       = isset($_GET['q']) ? $_GET['q'] : $hppstdid;
    $plantid  = isset($_GET['q']) ? $_GET['q'] : $plantid;
    $productid   = isset($_GET['q']) ? $_GET['q'] : $productid;
    $price         = isset($_GET['q']) ? $_GET['q'] : $price;
    $uomid       = isset($_GET['q']) ? $_GET['q'] : $uomid;
    $docdate     = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $recordstatus = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'hppstdid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();

    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('hppstd t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->leftjoin('plant c', 'c.plantid = t.plantid')
				->where('((hppstdid like :hppstdid) or (a.productname like :productname) or (b.uomcode like :uomcode) or (c.plantid like :plantid)) ', array(
        ':hppstdid' => '%' . $hppstdid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':plantid' => '%' . $plantid . '%'
      ))->queryScalar();
    }
    else {
      if(isset($productdetail) && $productdetail !='') {
            $sqlproductdetail  = " and hppstdid in 
                (
                select distinct za.hppstdid
                from hppstddetail za 
                left join product zb on zb.productid = za.productid 
                where zb.productname like '%".$productdetail."%'
                ) ";
        }
        else {
            $sqlproductdetail = '';
        }
        
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('hppstd t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->leftjoin('plant c', 'c.plantid = t.plantid')
				->where("
      (coalesce(hppstdid,'') like :hppstdid) 
			and (coalesce(a.productname,'') like :productname) 
			and (coalesce(b.uomcode,'') like :uomcode) 
			and (coalesce(c.plantid,'') like :plantid) 
			and (coalesce(docdate,'') like :docdate) 
			", array(
        ':hppstdid' => '%' . $hppstdid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':plantid' => '%' . $plantid . '%',
        ':docdate' => '%'. $docdate. '%',
        //':productdetailname' => '%'. $productdetail. '%',
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.plantcode,d.companyname, null as hppstdcode')->from('hppstd t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('plant c', 'c.plantid = t.plantid')->leftjoin('company d', 'd.companyid = t.companyid')->where('((hppstdid like :hppstdid) or (a.productname like :productname) or (b.uomcode like :uomcode) or (c.plantid like :plantid))', array(
        ':hppstdid' => '%' . $hppstdid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':plantid' => '%' . $plantid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } 
    else {
      if(isset($productdetail) && $productdetail !='') {
            $sqlproductdetail  = " and hppstdid in 
                (
                select distinct za.hppstdid
                from hppstddetail za 
                left join product zb on zb.productid = za.productid 
                where zb.productname like '%".$productdetail."%'
                ) ";
        }
        else {
            $sqlproductdetail = '';
        }
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.plantcode,d.companyname,d.companyname as hppstdcode')->from('hppstd t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('plant c', 'c.plantid = t.plantid')->leftjoin('company d', 'd.companyid = t.companyid')->where("
      (coalesce(hppstdid,'') like :hppstdid)  
      and (coalesce(a.productname,'') like :productname) 
			and (coalesce(b.uomcode,'') like :uomcode) 
			and (coalesce(c.plantid,'') like :plantid) 
			and (coalesce(docdate,'') like :docdate)
      ", array(
        ':hppstdid' => '%' . $hppstdid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':plantid' => '%' . $plantid . '%',
        ':docdate' => '%'. $docdate. '%',
        //':productdetailname' => '%'. $productdetail. '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'hppstdid' => $data['hppstdid'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'pricegenerate' => Yii::app()->format->formatCurrency($data['pricegenerate']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'docdate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['docdate'])),
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'hppstddetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('hppstddet t')->leftjoin('product a', 'a.productid = t.productid')->where('t.hppstdid = :hppstdid', array(
      ':hppstdid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,ta.hppstdcode,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						t.hppstdcode,t.isheader,t.qtyheader,t.qty,t.price,t.pricegenerate,t.isbold,t.nourut,t.isview,
            (select productname from product x where x.productid = ta.productid) as productparent')
            ->from('hppstddet t')
            ->leftjoin('hppstddet ta','ta.hppstddetid = t.parentid')
            ->leftjoin('product a', 'a.productid = t.productid')
            ->where('t.hppstdid = :hppstdid', array(
      ':hppstdid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'hppstddetid' => $data['hppstddetid'],
        'hppstdid' => $data['hppstdid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'parentid' => $data['parentid'],
        'productparent'=>$data['productparent'],
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'pricegenerate' => Yii::app()->format->formatCurrency($data['pricegenerate']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'qtyheader' => Yii::app()->format->formatNumber($data['qtyheader']),
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'isheader' => $data['isheader'],
        'isbold' => $data['isbold'],
        'isview' => $data['isview'],
        'hppstdcode' => $data['hppstdcode'],
        'nourut' => $data['nourut'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actioncopyhppstd() {
		parent::actionIndex();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Copyhppstd(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, 'insertsuccess',);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionGethppstdcode() {
    if($_POST['parentid']) {
      $sql = "select hppstdcode from hppstddet where hppstddetid = ".$_POST['parentid'];
      $q = Yii::app()->db->createCommand($sql)->queryScalar();

      echo json_encode(array(
        'status' => 'succcess',
        'hppstdcode' => $q,
      ));
    }
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Inserthppstd(:vplantid,:vdocdate,:vproductid,:vuomid,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatehppstd(:vid,:vplantid,:vdocdate,:vproductid,:vuomid,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vplantid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vdocdate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('_FILES_').'/uploads/' . basename($_FILES["file-hppstd"]["name"]);
		if (move_uploaded_file($_FILES["file-hppstd"]["tmp_name"], $target_file)) {
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
        $idheader = $objWorksheet->getCellByColumnAndRow(10, 1)->getValue();
        $plant = $objWorksheet->getCellByColumnAndRow(2, 2)->getValue();
        $plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plant."'")->queryScalar();
        $docdate = date(Yii::app()->params['datetodb'],strtotime($objWorksheet->getCellByColumnAndRow(2, 3)->getValue()));
        $producth = $objWorksheet->getCellByColumnAndRow(2, 4)->getValue();
        $productidh = Yii::app()->db->createCommand("select productid from product where productname = '".$producth."'")->queryScalar();
        $uomh = $objWorksheet->getCellByColumnAndRow(2, 5)->getValue();
        $uomidh = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomh."'")->queryScalar();

        if($idheader=='') {
          //save to header
          // get the id as $idheader
          $sql = "
            insert into hppstd (plantid,docdate,productid,uomid,recordstatus) 
            values (".$plantid.",'".$docdate."','".$productidh."','".$uomidh."',".findstatusbyuser("inshppstd").")";
          Yii::app()->db->createCommand($sql)->execute();
          
          $sql = "select last_insert_id();";
          $idheader = Yii::app()->db->createCommand($sql)->queryScalar();
        }
        else {
          $this->ModifyData($connection,array($idheader,$plantid,$docdate,$productidh,$uomidh));
        }

				for ($row = 8; $row <= $highestRow; ++$row) {
					$iddetail = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$productname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
          $uom = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
          $uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uom."'")->queryScalar();
					$isheader = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
          if($isheader=='YES') { $isheader=1;} else {$isheader=0;}
          $parent = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
          if($parent!=''){
            $parentid = Yii::app()->db->createCommand("select hppstddetid from hppstddet where hppstdcode = '".$parent."' and hppstdid = ".$idheader)->queryScalar();
          }
          else {
            $parentid=0;
          }
					$hppstdcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$qty = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$price = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$isbold = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
          if($isbold=='YES') { $isbold=1;} else {$isbold=0;}
					$isview = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
          if($isview=='YES') { $isview=1;} else {$isview=0;}
					$nourut = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();

          //:vhppstdid,:vproductid,:vuomid,:vhppstdcode,:visheader,:vparentid,:vqty,:vprice,:visbold,:visview,:vnourut
          $this->ModifyDataDetail($connection,array($iddetail,$idheader,$productid,$uomid,$hppstdcode,$isheader,$parentid,$qty,$price,$isbold,$isview,$nourut));					
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
			$this->ModifyData($connection,array((isset($_POST['hppstdid'])?$_POST['hppstdid']:''),$_POST['plantid'],date(Yii::app()->params['datetodb'],strtotime($_POST['docdate'])),$_POST['productid'],$_POST['uomid']));
			$transaction->commit();
			GetMessage(false, 'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true, $e->getMessage());
		}
  }
	private function ModifyDataDetail($connection,$arraydata) {
		$detailid = (isset($arraydata[0])?$arraydata[0]:'');
		if ($detailid == '') {
			$sql     = 'call Inserthppstddet(:vhppstdid,:vproductid,:vuomid,:vhppstdcode,:visheader,:vparentid,:vqty,:vprice,:visbold,:visview,:vnourut,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatehppstddet(:vid,:vhppstdid,:vproductid,:vuomid,:vhppstdcode,:visheader,:vparentid,:vqty,:vprice,:visbold,:visview,:vnourut,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vhppstdid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vhppstdcode', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':visheader', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vparentid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vprice', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':visbold', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':visview', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vnourut', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['hppstddetid'])?$_POST['hppstddetid']:''),$_POST['hppstdid'],$_POST['productid'],$_POST['uomid'],$_POST['hppstdcode'],$_POST['isheader'],($_POST['parentid']=='' ? 0 : $_POST['parentid']),$_POST['qty'],$_POST['price'],$_POST['isbold'],$_POST['isview'],$_POST['nourut']));
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
        $sql     = 'call Purgehppstd(:vid,:vcreatedby)';
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
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgehppstddet(:vid,:vcreatedby)';
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
    $sql = "select a.*, b.productname, c.uomcode, d.plantcode
				from hppstd a 
				left join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        left join plant d on d.plantid = a.plantid
        where a.hppstdid like '%".$_GET['hppstdid']."%' ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.hppstdid in (".$_GET['id'].") ";
    }
    if ($_GET['plantcode'] !== '') {
      $sql = $sql . " and d.plantcode like '%".$_GET['plantcode']."%' ";
    }
    /*
    if ($_GET['docdate'] !== '') {
      $sql = $sql . " and a.docdate like '%".$_GET['docdate']."%' ";
    }
    */
    if ($_GET['product'] !== '') {
      $sql = $sql . " and b.productname like '%".$_GET['product']."%' ";
    }
    /*if ($_GET['description'] !== '') {
      $sql = $sql . " and a.description like '%".$_GET['description']."%' ";
    }*/
    if ($_GET['uomcode'] !== '') {
      $sql = $sql . " and c.uomcode like '%".$_GET['uomcode']."%' ";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->title = getCatalog('hppstd');
    $this->pdf->AddPage('L',array(210,300));
    $this->pdf->SetFont('Arial');
    $this->pdf->AliasNBPages();
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      //$this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(15, $this->pdf->gety() + 5, getCatalog('plant'));
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['plantcode']);
      $this->pdf->text(15, $this->pdf->gety() + 10, getCatalog('docdate'));
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(15, $this->pdf->gety() + 15, getCatalog('product'));
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['productname']);
      $this->pdf->text(15, $this->pdf->gety() + 20, getCatalog('unitofmeasure'));
      $this->pdf->text(50, $this->pdf->gety() + 20, ': ' . $row['uomcode']);

      $sql1 = "select a.*, b.productname, c.uomcode, d.hppstdcode as parentcode,(select productname from product x where x.productid = d.productid) as parentproduct,
        if(a.isheader=1,'YES','NO') as isheadername,
        if(a.isbold=1,'YES','NO') as isboldname,
        if(a.isview=1,'YES','NO') as isviewname
					from hppstddet a 
					left join product b on b.productid = a.productid 
					left join unitofmeasure c on c.unitofmeasureid = a.uomid 
					left join hppstddet d on d.hppstddetid = a.parentid
					where a.hppstdid = ".$row['hppstdid'];
        
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->colalign = array(
        'C',
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
      $this->pdf->setwidths(array(
        8,
        80,
        12,
        15,
        65,
        23,
        15,
        17,
        21,
        14,
        14,
      ));
      $this->pdf->colheader = array(
        'NO',
        getCatalog('Items'),
        getCatalog('unitofmeasure'),
        getCatalog('isheader?'),
        getCatalog('parent'),
        getCatalog('hppstdcode'),
        getCatalog('qty'),
        getCatalog('price'),
        getCatalog('isbold'),
        getCatalog('isview'),
        getCatalog('nourut'),
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'C',
        'C',
        'L',
        'L',
        'R',
        'R',
        'C',
        'C',
        'C'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          $row1['isheadername'],
          $row1['parentproduct'],
          $row1['hppstdcode'],
          Yii::app()->format->formatCurrency($row1['qty']),
          Yii::app()->format->formatCurrency($row1['price']),
          $row1['isboldname'],
          $row1['isviewname'],
          $row1['nourut'],
        ));
      }
      $this->pdf->checkNewPage(10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 10, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 30, '____________ ');
      $this->pdf->text(150, $this->pdf->gety() + 30, '____________');
      $this->pdf->setY($this->pdf->getY()+35);
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
		$this->menuname='hppstd';
    parent::actionDownxls();
    $sql = "select a.*, b.productname, c.uomcode, d.plantcode
				from hppstd a 
				left join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        left join plant d on d.plantid = a.plantid
        where a.hppstdid like '%".$_GET['hppstdid']."%'
				";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.hppstdid in (".$_GET['id'].") ";
    }
    if ($_GET['plantcode'] !== '') {
      $sql = $sql . " and d.plantcode like '%".$_GET['plantcode']."%' ";
    }
    /*
    if ($_GET['docdate'] !== '') {
      $sql = $sql . " and a.docdate like '%".$_GET['docdate']."%' ";
    }
    */
    if ($_GET['product'] !== '') {
      $sql = $sql . " and b.productname like '%".$_GET['product']."%' ";
    }
    /*if ($_GET['description'] !== '') {
      $sql = $sql . " and a.description like '%".$_GET['description']."%' ";
    }*/
    if ($_GET['uomcode'] !== '') {
      $sql = $sql . " and c.uomcode like '%".$_GET['uomcode']."%' ";
    }
    /*
    if ($_GET['productdetail'] !== '') {
      $sql = $sql . " and a.hppstdid in 
      (
      select za.hppstdid
      from hppstddet za 
      left join product zb on zb.productid = za.productid 
      where zb.productname like '%".$_GET['productdetail']."%'
      ) ";
    }
    */
    
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 1;

		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0, 2, strtoupper(getCatalog('plantcode')))
			->setCellValueByColumnAndRow(0, 3, strtoupper(getCatalog('docdate')))
			->setCellValueByColumnAndRow(0, 4, strtoupper(getCatalog('product')))
			->setCellValueByColumnAndRow(0, 5, strtoupper(getCatalog('uom')));
    
    $i=7;
    $this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0, $i, getCatalog('hppstdid'))
			->setCellValueByColumnAndRow(1, $i, getCatalog('product'))
			->setCellValueByColumnAndRow(2, $i, getCatalog('uom'))
			->setCellValueByColumnAndRow(3, $i, getCatalog('isheader?'))
			->setCellValueByColumnAndRow(4, $i, getCatalog('parent'))
			->setCellValueByColumnAndRow(5, $i, getCatalog('hppstdcode'))
			->setCellValueByColumnAndRow(6, $i, getCatalog('Qty'))
			->setCellValueByColumnAndRow(7, $i, getCatalog('price'))
			->setCellValueByColumnAndRow(8, $i, getCatalog('isbold'))
			->setCellValueByColumnAndRow(9, $i, getCatalog('isview'))
			->setCellValueByColumnAndRow(10, $i, getCatalog('nourut'));
    
    $i++;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(10, 1, $row['hppstdid'])
          ->setCellValueByColumnAndRow(2, 2, strtoupper($row['plantcode']))
          ->setCellValueByColumnAndRow(2, 3, $row['docdate'])
          ->setCellValueByColumnAndRow(2, 4, strtoupper($row['productname']))
          ->setCellValueByColumnAndRow(2, 5, strtoupper($row['uomcode']));

      $sql1 = "select a.*, b.productname, c.uomcode, d.hppstdcode as parentcode,
        if(a.isheader=1,'YES','NO') as isheadername,
        if(a.isbold=1,'YES','NO') as isboldname,
        if(a.isview=1,'YES','NO') as isviewname
					from hppstddet a 
					left join product b on b.productid = a.productid 
					left join unitofmeasure c on c.unitofmeasureid = a.uomid 
					left join hppstddet d on d.hppstddetid = a.parentid
					where a.hppstdid = ".$row['hppstdid'];
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			foreach ($dataReader1 as $row1) {
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0, $i , $row1['hppstddetid'])
					->setCellValueByColumnAndRow(1, $i , $row1['productname'])
					->setCellValueByColumnAndRow(2, $i , $row1['uomcode'])
					->setCellValueByColumnAndRow(3, $i , $row1['isheadername'])
					->setCellValueByColumnAndRow(4, $i , $row1['parentcode'])
					->setCellValueByColumnAndRow(5, $i , $row1['hppstdcode'])
					->setCellValueByColumnAndRow(6, $i , $row1['qty'])
					->setCellValueByColumnAndRow(7, $i , $row1['price'])
					->setCellValueByColumnAndRow(8, $i , $row1['isboldname'])
					->setCellValueByColumnAndRow(9, $i , $row1['isviewname'])
					->setCellValueByColumnAndRow(10, $i , $row1['nourut']);
				$i += 1;
			}
    }
    $this->getFooterXLS($this->phpExcel);
  }
}