<?php
class BomController extends Controller {
  public $menuname = 'bom';
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
			$sql = "insert into billofmaterial (bomdate,recordstatus) values ('".$dadate->format('Y-m-d')."',1)";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'bomid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $bomid       = isset($_POST['bomid']) ? $_POST['bomid'] : '';
    $bomversion  = isset($_POST['bomversion']) ? $_POST['bomversion'] : '';
    $productid   = isset($_POST['product']) ? $_POST['product'] : '';
    $productdetail  = isset($_POST['productdetail']) ? $_POST['productdetail'] : '';
    $qty         = isset($_POST['qty']) ? $_POST['qty'] : '';
    $uomid       = isset($_POST['uomid']) ? $_POST['uomid'] : '';
    $bomdate     = isset($_POST['bomdate']) ? $_POST['bomdate'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $cycletime = isset($_POST['cycletime']) ? $_POST['cycletime'] : '';
    $recordstatus = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $bomid       = isset($_GET['q']) ? $_GET['q'] : $bomid;
    $bomversion  = isset($_GET['q']) ? $_GET['q'] : $bomversion;
    $productid   = isset($_GET['q']) ? $_GET['q'] : $productid;
    $productdetail   = isset($_GET['q']) ? $_GET['q'] : $productdetail;
    $qty         = isset($_GET['q']) ? $_GET['q'] : $qty;
    $uomid       = isset($_GET['q']) ? $_GET['q'] : $uomid;
    $bomdate     = isset($_GET['q']) ? $_GET['q'] : $bomdate;
    $description = isset($_GET['q']) ? $_GET['q'] : $description;
    $cycletime = isset($_GET['q']) ? $_GET['q'] : $cycletime;
    $recordstatus = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'bomid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
      
    if(isset($cycletime) && $cycletime!='')
        $cycletime = 'and cycletime = '.$cycletime;
    else
        $cycletime = "and cycletime like '%%' "; 
      
    if(isset($recordstatus) && $recordstatus!='')
        $recordstatus = 'and t.recordstatus = '.$recordstatus;
    else
        $recordstatus = "and t.recordstatus like '%%' ";

    if (isset($_GET['trxpplan'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(distinct t.bomid,t.bomversion,t.productid,t.qty,t.cycletime,t.cycletimemin,t.hppcycletime,t.hppcycletimemin,t.uomid,t.bomdate,t.description,t.recordstatus,t.createddate,t.updatedate,a.productname,b.uomcode) as total')
		->from('billofmaterial t')
        ->leftjoin('product a', 'a.productid = t.productid')
		->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
        ->leftjoin('productplant c', 'c.productid = t.productid and c.unitofissue = t.uomid')
		->leftjoin('sloc d', 'd.slocid = c.slocid')
		->leftjoin('plant e', 'e.plantid = d.plantid')
        ->where("((bomid like :bomid) or (productname like :productname) or (uomcode like :uomcode) or (bomversion like :bomversion)) and t.recordstatus = 1 and a.recordstatus = 1 and a.isstock = 1 and c.recordstatus = 1 and c.issource = 1 and c.slocid in (".getUserObjectValues('sloc').") and e.companyid = '".$_GET['companyid']."'", array(
        ':bomid' => '%' . $bomid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':bomversion' => '%' . $bomversion . '%'
      ))->queryScalar();
    } else if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('billofmaterial t')->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->where('((bomid like :bomid) or (productname like :productname) or (uomcode like :uomcode) or (bomversion like :bomversion)) 
				and t.recordstatus = 1 and a.isstock = 1', array(
        ':bomid' => '%' . $bomid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':bomversion' => '%' . $bomversion . '%'
      ))->queryScalar();
    } else {
      if(isset($productdetail) && $productdetail !='') {
            $sqlproductdetail  = " and bomid in 
                (
                select distinct za.bomid
                from bomdetail za 
                left join product zb on zb.productid = za.productid 
                where zb.productname like '%".$productdetail."%'
                ) ";
        }
        else {
            $sqlproductdetail = '';
        }
        
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
				->from('billofmaterial t')
				->leftjoin('product a', 'a.productid = t.productid')
				->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
				->where("
      (coalesce(bomid,'') like :bomid) 
			and (coalesce(productname,'') like :productname) 
			and (coalesce(uomcode,'') like :uomcode) 
			and (coalesce(bomversion,'') like :bomversion) 
			and (coalesce(t.description,'') like :description) 
			and (coalesce(bomdate,'') like :bomdate) 
			".$sqlproductdetail.$cycletime.' '.$recordstatus, array(
        ':bomid' => '%' . $bomid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':bomversion' => '%' . $bomversion . '%',
        ':description' => '%' . $description . '%',
        ':bomdate' => '%'. $bomdate. '%',
        //':productdetailname' => '%'. $productdetail. '%',
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['trxpplan'])) {
      $cmd = Yii::app()->db->createCommand()->selectDistinct('t.bomid,t.bomversion,t.productid,t.qty,t.cycletime,t.cycletimemin,t.hppcycletime,t.hppcycletimemin,t.uomid,t.bomdate,t.description,t.recordstatus,t.createddate,t.updatedate,a.productname,b.uomcode')
      ->from('billofmaterial t')
      ->leftjoin('product a', 'a.productid = t.productid')
      ->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')
      ->leftjoin('productplant c', 'c.productid = t.productid and c.unitofissue = t.uomid')
      ->leftjoin('sloc d', 'd.slocid = c.slocid')
      ->leftjoin('plant e', 'e.plantid = d.plantid')
      ->where("((bomid like :bomid) or (productname like :productname) or (uomcode like :uomcode) or (bomversion like :bomversion)) and t.recordstatus = 1 and a.recordstatus = 1 and a.isstock = 1 and c.recordstatus = 1 and c.issource = 1 and c.slocid in (".getUserObjectValues('sloc').") and e.companyid = '".$_GET['companyid']."'", array(
        ':bomid' => '%' . $bomid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':bomversion' => '%' . $bomversion . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode')->from('billofmaterial t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->where('((bomid like :bomid) or (productname like :productname) or (uomcode like :uomcode) or (bomversion like :bomversion)) and t.recordstatus = 1 and a.isstock = 1', array(
        ':bomid' => '%' . $bomid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':bomversion' => '%' . $bomversion . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      if(isset($productdetail) && $productdetail !='') {
            $sqlproductdetail  = " and bomid in 
                (
                select distinct za.bomid
                from bomdetail za 
                left join product zb on zb.productid = za.productid 
                where zb.productname like '%".$productdetail."%'
                ) ";
        }
        else {
            $sqlproductdetail = '';
        }
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode')->from('billofmaterial t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->where("
      (coalesce(bomid,'') like :bomid)  
      and (coalesce(productname,'') like :productname) 
			and (coalesce(uomcode,'') like :uomcode) 
			and (coalesce(bomversion,'') like :bomversion) 
			and (coalesce(t.description,'') like :description) 
			and (coalesce(bomdate,'') like :bomdate)
            ".$sqlproductdetail.$cycletime.' '.$recordstatus, array(
        ':bomid' => '%' . $bomid . '%',
        ':productname' => '%' . $productid . '%',
        ':uomcode' => '%' . $uomid . '%',
        ':bomversion' => '%' . $bomversion . '%',
        ':description' => '%' . $description . '%',
        ':bomdate' => '%'. $bomdate. '%',
        //':productdetailname' => '%'. $productdetail. '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'bomid' => $data['bomid'],
        'bomversion' => $data['bomversion'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'cycletime' => $data['cycletime'],
        'cycletimemin' => $data['cycletimemin'],
        'hppcycletime' => $data['hppcycletime'],
        'hppcycletimemin' => $data['hppcycletimemin'],
        'bomdate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['bomdate'])),
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'bomdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('bomdetail t')->leftjoin('product a', 'a.productid = t.productid')->where('t.bomid = :bomid', array(
      ':bomid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,
						(select b.uomcode from unitofmeasure b where b.unitofmeasureid = t.uomid) as uomcode,
						(select c.bomversion from billofmaterial c where c.bomid = t.productbomid) as bomversion')->from('bomdetail t')->leftjoin('product a', 'a.productid = t.productid')->where('t.bomid = :bomid', array(
      ':bomid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'bomdetailid' => $data['bomdetailid'],
        'bomid' => $data['bomid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'productbomid' => $data['productbomid'],
        'productbomversion' => $data['bomversion'],
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actioncopyBom() {
		parent::actionIndex();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call CopyBOM(:vid,:vcreatedby)';
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
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertbom(:vbomversion,:vproductid,:vqty,:vcycletime,:vhppcycletime,:vuomid,:vbomdate,:vdescription,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatebom(:vid,:vbomversion,:vproductid,:vqty,:vcycletime,:vhppcycletime,:vuomid,:vbomdate,:vdescription,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vbomversion', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcycletime', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vhppcycletime', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vbomdate', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('_FILES_').'/uploads/' . basename($_FILES["file-bom"]["name"]);
		if (move_uploaded_file($_FILES["file-bom"]["tmp_name"], $target_file)) {
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
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$bomversion = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$bomdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()));
					$productname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
					$qty = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$cycletime = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$uomcode = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
					$description = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$hppcycletime = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					if ($id == '') {					
						//get id addressbookid
						$id = Yii::app()->db->createCommand("select bomid from billofmaterial where bomversion = '".$bomversion."'")->queryScalar();
					}
                    if($productid>0)
					   $this->ModifyData($connection,array($id,$bomversion,$productid,$qty,$uomid,$bomdate,$description,$recordstatus,$cycletime,$hppcycletime));
					if ($id == '') {					
						//get id addressbookid
						$id = Yii::app()->db->createCommand("select bomid from billofmaterial where bomversion = '".$bomversion."'")->queryScalar();
					}
					if ($id != '') {
						$detailid = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $productname = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
                        if($detailid != '') {
                            //$productname = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
                            $qty = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
                            $uomcode = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
                            $uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
                            $productbom = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
                            $productbomid = Yii::app()->db->createCommand("select bomid from billofmaterial where bomversion = '".$productbom."'")->queryScalar();
                            $description = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
                            $this->ModifyDataDetail($connection,array($detailid,$id,$productid,$qty,$uomid,$productbomid,$description));
                        }
                        else {
                            if($productname != '') {
                                $productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
                                $qty = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
                                $uomcode = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
                                $uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
                                $productbom = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
                                $productbomid = Yii::app()->db->createCommand("select bomid from billofmaterial where bomversion = '".$productbom."'")->queryScalar();
                                $description = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
                                $this->ModifyDataDetail($connection,array($detailid,$id,$productid,$qty,$uomid,$productbomid,$description));
                            }
                            else {
                                //$transaction->rollBack();
				                //GetMessage(true, 'productidempty');    
                            }
                        }
                        
					}
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
			$this->ModifyData($connection,array((isset($_POST['bomid'])?$_POST['bomid']:''),$_POST['bomversion'],$_POST['productid'],$_POST['qty'],$_POST['uomid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['bomdate'])),$_POST['description'],(isset($_POST['recordstatus']) ? 1 : 0),$_POST['cycletime'],$_POST['hppcycletime']));
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
			$sql     = 'call Insertbomdetail(:vbomid,:vproductid,:vqty,:vuomid,:vproductbomid,:vdescription,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatebomdetail(:vid,:vbomid,:vproductid,:vqty,:vuomid,:vproductbomid,:vdescription,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vbomid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vproductbomid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['bomdetailid'])?$_POST['bomdetailid']:''),$_POST['bomid'],$_POST['productid'],$_POST['qty'],$_POST['uomid'],
				$_POST['productbomid'],$_POST['description']));
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
        $sql     = 'call Purgebom(:vid,:vcreatedby)';
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
        $sql     = 'call Purgebomdetail(:vid,:vcreatedby)';
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
      $sql = "select a.bomid,a.bomversion,a.bomdate,b.productname,a.qty,c.uomcode,a.cycletime,a.cycletimemin,a.description,a.recordstatus
				from billofmaterial a 
				left join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        where a.bomid like '%".$_GET['bomid']."%'
				";
        if ($_GET['id'] !== '') {
          $sql = $sql . " and a.bomid in (".$_GET['id'].") ";
        }
        if ($_GET['bomversion'] !== '') {
          $sql = $sql . " and a.bomversion like '%".$_GET['bomversion']."%' ";
        }
        if ($_GET['bomdate'] !== '') {
          $sql = $sql . " and a.bomdate like '%".$_GET['bomdate']."%' ";
        }
        if ($_GET['product'] !== '') {
          $sql = $sql . " and b.productname like '%".parse_url($_GET['product'],PHP_URL_PATH)."%' ";
        }
        if ($_GET['description'] !== '') {
          $sql = $sql . " and a.description like '%".$_GET['description']."%' ";
        }
        if ($_GET['uom'] !== '') {
          $sql = $sql . " and c.uomcode like '%".$_GET['uom']."%' ";
        }
        if ($_GET['productdetail'] !== '') {
          $sql = $sql . " and a.bomid in 
          (
          select za.bomid
          from bomdetail za 
          left join product zb on zb.productid = za.productid 
          where zb.productname like '%".$_GET['productdetail']."%'
          ) ";
        }
        if(isset($_GET['cycletime']) && $_GET['cycletime']!='')
	        $sql = $sql . ' and cycletime = '.$_GET['cycletime'];
	      else
	        $sql = $sql . " and cycletime like '%%' "; 
	      
	      if(isset($_GET['recordstatus']) && $_GET['recordstatus']!='')
	        $sql = $sql . ' and a.recordstatus = '.$_GET['recordstatus'];
	      else
	        $sql = $sql. " and a.recordstatus like '%%' ";
      $command          = $this->connection->createCommand($sql);
      $command->bindvalue(':bomid',$_GET['bomid'], PDO::PARAM_STR);
      $command->bindvalue(':productname', "'%".$_GET['product']."%'", PDO::PARAM_STR);
      $command->bindvalue(':uomcode', "'%".$_GET['uom']."%'", PDO::PARAM_STR);
      $command->bindvalue(':bomversion', "'%".$_GET['bomversion']."%'", PDO::PARAM_STR);
      $command->bindvalue(':description', "'%".$_GET['description']."%'", PDO::PARAM_STR);
      $command->bindvalue(':productdetailname', "'%".$_GET['productdetail']."%'", PDO::PARAM_STR);
      $dataReader       = $command->queryAll();
      $this->pdf->title = getCatalog('bom');
      $this->pdf->AddPage('P');
      $this->pdf->SetFont('Arial');
      $this->pdf->AliasNBPages();
      foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'BOM Version ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['bomversion']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'BOM Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['bomdate'])));
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Material / Service');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['productname']);
      $this->pdf->text(15, $this->pdf->gety() + 20, 'Qty');
      $this->pdf->text(50, $this->pdf->gety() + 20, ': ' . $row['qty'] . ' ' . $row['uomcode']);
      $this->pdf->text(15, $this->pdf->gety() + 25, 'Cycletime');
      $this->pdf->text(50, $this->pdf->gety() + 25, ': ' . Yii::app()->format->formatCurrency($row['cycletime']) . ' detik / ' . Yii::app()->format->formatCurrency($row['cycletimemin']) . ' menit');
      $this->pdf->text(15, $this->pdf->gety() + 30, 'Description');
      $this->pdf->text(50, $this->pdf->gety() + 30, ': ' . $row['description']);
      $sql1        = "select a.bomdetailid,b.productname, a.qty, c.uomcode, a.description, d.bomversion
        from bomdetail a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join billofmaterial d on d.bomid = a.productbomid
        where a.bomid = '" . $row['bomid'] . "'
	order by bomdetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 35);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        80,
        20,
        15,
        65
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['bomversion'] . ' - ' . $row1['description']
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
  public function actionDownPDF2() {
        parent::actionDownload();
        $companyid = 1;
        $productcollect = 'KAIN';
        $startdate = '2021-11-01';
        $enddate = '2021-12-31';

        $this->pdf->title = 'Title';
        $this->pdf->subtitle = 'TES';

        $this->pdf->companyid = $companyid;
        $this->pdf->AddPage('P', 'A4');

        $sql = "select a.productname,a.productid
            from product a
            join productcollection b on b.productcollectid = a.productcollectid
            where b.collectionname like '%{$productcollect}%' ";
        $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
        // $i = 5;
        $this->pdf->setFont('Arial', '', 8);

        $sqldel = "delete from getfg where useraccessid = (select useraccessid from useraccess where username = '" . Yii::app()->user->name . "')";
        $q = Yii::app()->db->createCommand($sqldel)->execute();

        foreach ($dataReader as $row) {
            $sql1 = "call getFG({$row['productid']},{$row['productid']},'{$startdate}','{$enddate}','" . Yii::app()->user->name . "')";
            //$this->pdf->setY($this->pdf->getY() + 5);
            Yii::app()->db->createCommand($sql1)->execute();

            $sql1 = "select distinct productid2,productname2 from getfg where useraccessid = (select useraccessid from useraccess where username = '" . Yii::app()->user->name . "') and productid1 = " . $row['productid'];
            $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
            foreach ($dataReader1 as $row1) {
                $this->pdf->text(10, $this->pdf->gety(), substr($row['productname'], 0, 50) . '-> ' . $row1['productname2']);
                $this->pdf->setY($this->pdf->getY() + 5);
            }
        }

        $this->pdf->Output();
    }
  public function actionDownxls() {
		$this->menuname='bom';
    parent::actionDownxls();
    $sql = "select a.bomid,a.bomversion,a.bomdate,b.productname,a.qty,a.cycletime,c.uomcode,a.description,a.recordstatus
				from billofmaterial a 
				left join product b on b.productid = a.productid 
				left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        where a.bomid like '%".$_GET['bomid']."%'
				";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.bomid in (".$_GET['id'].") ";
    }
    if ($_GET['bomversion'] !== '') {
      $sql = $sql . " and a.bomversion like '%".$_GET['bomversion']."%' ";
    }
    if ($_GET['bomdate'] !== '') {
      $sql = $sql . " and a.bomdate like '%".$_GET['bomdate']."%' ";
    }
    if ($_GET['product'] !== '') {
      $sql = $sql . " and b.productname like '%".$_GET['product']."%' ";
    }
    if ($_GET['description'] !== '') {
      $sql = $sql . " and a.description like '%".$_GET['description']."%' ";
    }
    if ($_GET['uom'] !== '') {
      $sql = $sql . " and c.uomcode like '%".$_GET['uom']."%' ";
    }
    if ($_GET['productdetail'] !== '') {
      $sql = $sql . " and a.bomid in 
      (
      select za.bomid
      from bomdetail za 
      left join product zb on zb.productid = za.productid 
      where zb.productname like '%".$_GET['productdetail']."%'
      ) ";
    }
    if(isset($_GET['cycletime']) && $_GET['cycletime']!='')
        $sql = $sql . ' and cycletime = '.$_GET['cycletime'];
      else
        $sql = $sql . " and cycletime like '%%' "; 
      
      if(isset($_GET['recordstatus']) && $_GET['recordstatus']!='')
        $sql = $sql . ' and a.recordstatus = '.$_GET['recordstatus'];
      else
        $sql = $sql. " and a.recordstatus like '%%' ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0, 1, getCatalog('bomid'))
			->setCellValueByColumnAndRow(1, 1, getCatalog('bomversion'))
			->setCellValueByColumnAndRow(2, 1, getCatalog('bomdate'))
			->setCellValueByColumnAndRow(3, 1, getCatalog('product'))
			->setCellValueByColumnAndRow(4, 1, getCatalog('qty'))
			->setCellValueByColumnAndRow(5, 1, getCatalog('cycletime'))
			->setCellValueByColumnAndRow(6, 1, getCatalog('uom'))
			->setCellValueByColumnAndRow(7, 1, getCatalog('description'))
			->setCellValueByColumnAndRow(8, 1, getCatalog('recordstatus'))
			->setCellValueByColumnAndRow(9, 1, getCatalog('bomdetailid'))
			->setCellValueByColumnAndRow(10, 1, getCatalog('product'))
			->setCellValueByColumnAndRow(11, 1, getCatalog('qty'))
			->setCellValueByColumnAndRow(12, 1, getCatalog('uom'))
			->setCellValueByColumnAndRow(13, 1, getCatalog('productbom'))
			->setCellValueByColumnAndRow(14, 1, getCatalog('description'))
			;
    foreach ($dataReader as $row) {
			$sql1 = "select a.bomdetailid,b.productname,a.qty,c.uomcode,d.bomversion,a.description
					from bomdetail a 
					left join product b on b.productid = a.productid 
					left join unitofmeasure c on c.unitofmeasureid = a.uomid 
					left join billofmaterial d on d.bomid = a.productbomid 
					where a.bomid = ".$row['bomid'];
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
			foreach ($dataReader1 as $row1) {
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0, $i + 1, $row['bomid'])
					->setCellValueByColumnAndRow(1, $i + 1, $row['bomversion'])
					->setCellValueByColumnAndRow(2, $i + 1, date(Yii::app()->params['dateviewfromdb'], strtotime($row['bomdate'])))
					->setCellValueByColumnAndRow(3, $i + 1, $row['productname'])
					->setCellValueByColumnAndRow(4, $i + 1, $row['qty'])
					->setCellValueByColumnAndRow(5, $i + 1, $row['cycletime'])
					->setCellValueByColumnAndRow(6, $i + 1, $row['uomcode'])
					->setCellValueByColumnAndRow(7, $i + 1, $row['description'])
					->setCellValueByColumnAndRow(8, $i + 1, $row['recordstatus'])
					->setCellValueByColumnAndRow(9, $i + 1, $row1['bomdetailid'])
					->setCellValueByColumnAndRow(10, $i + 1, $row1['productname'])
					->setCellValueByColumnAndRow(11, $i + 1, $row1['qty'])
					->setCellValueByColumnAndRow(12, $i + 1, $row1['uomcode'])
					->setCellValueByColumnAndRow(13, $i + 1, $row1['bomversion'])
					->setCellValueByColumnAndRow(14, $i + 1, $row1['description'])
					;
				$i += 1;
			}
    }
    $this->getFooterXLS($this->phpExcel);
  }
}