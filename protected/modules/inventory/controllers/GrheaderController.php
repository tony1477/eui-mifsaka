<?php
class GrheaderController extends Controller {
  public $menuname = 'grheader';
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
  public function actiongetdata() {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into grheader (grdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insgr').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'grheaderid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $grheaderid = isset($_POST['grheaderid']) ? $_POST['grheaderid'] : '';
    $grdate     = isset($_POST['grdate']) ? $_POST['grdate'] : '';
    $grno       = isset($_POST['grno']) ? $_POST['grno'] : '';
    $poheaderid = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $companyid = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $fullname   = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $headernote = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $grheaderid = isset($_GET['q']) ? $_GET['q'] : $grheaderid;
    $grdate     = isset($_GET['q']) ? $_GET['q'] : $grdate;
    $grno       = isset($_GET['q']) ? $_GET['q'] : $grno;
    $poheaderid = isset($_GET['q']) ? $_GET['q'] : $poheaderid;
    $companyid = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $fullname   = isset($_GET['q']) ? $_GET['q'] : $fullname;
    $headernote = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'grheaderid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $page       = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows       = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort       = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order      = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appgr')")->queryScalar();
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grheader t')->join('poheader j', 'j.poheaderid = t.poheaderid')->join('addressbook k', 'k.addressbookid = j.addressbookid')->join('company l', 'l.companyid = j.companyid')->where("(
        (grheaderid like :grheaderid) or
        (grdate like :grdate) or
						(grno like :grno) or (k.fullname like :fullname) or
						(t.headernote like :headernote) or
						(l.companyname like :companyid) or
						(j.pono like :poheaderid)) and j.companyid in (".getUserObjectValues('company').")", array(
        ':grheaderid' => '%' . $grheaderid . '%',
        ':grdate' => '%' . $grdate . '%',
        ':grno' => '%' . $grno . '%',
        ':fullname' => '%' . $fullname . '%',
        ':companyid' => '%' . $companyid . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->queryScalar();
    } else if (isset($_GET['invgr'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grheader t')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('addressbook k', 'k.addressbookid = j.addressbookid')->leftjoin('company l', 'l.companyid = j.companyid')->where("(
      (coalesce(grheaderid,'') like :grheaderid) or
      (coalesce(grdate,'') like :grdate) or
			(coalesce(grno,'') like :grno) and 
			(coalesce(l.companyname) like :companyid) or
			(coalesce(k.fullname,'') like :fullname) or
			(coalesce(t.headernote,'') like :headernote) or
			(coalesce(j.pono,'') like :poheaderid)) 
			and t.recordstatus > 0
			and l.companyid = '".$_GET['companyid']."'
			and t.recordstatus in (".getUserRecordStatus('listgr').") and j.companyid in (".getUserObjectValues('company').")", array(
        ':grheaderid' => '%' . $grheaderid . '%',
        ':grdate' => '%' . $grdate . '%',
        ':grno' => '%' . $grno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':fullname' => '%' . $fullname . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->queryScalar();
    } else if (isset($_GET['pogr'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grheader t')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('company l', 'l.companyid = j.companyid')->where("(
      (coalesce(grheaderid,'') like :grheaderid) or
      (coalesce(grdate,'') like :grdate) or
			(coalesce(grno,'') like :grno) or 
			(coalesce(l.companyname) like :companyid) or
			(coalesce(t.headernote,'') like :headernote) or
			(coalesce(j.pono,'') like :poheaderid)) 
			and t.recordstatus > 0
			and l.companyid = '".$_GET['companyid']."' and t.poheaderid = '".$_GET['poheaderid']."'
			and t.recordstatus = {$maxstat} 
      and t.grheaderid not in (select a.grheaderid from invoiceap a where a.recordstatus > 0 and a.grheaderid is not null)
			and t.recordstatus in (".getUserRecordStatus('listgr').") and j.companyid in (".getUserObjectValues('company').")", array(
        ':grheaderid' => '%' . $grheaderid . '%',
        ':grdate' => '%' . $grdate . '%',
        ':grno' => '%' . $grno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->queryScalar();
	} else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('grheader t')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('addressbook k', 'k.addressbookid = j.addressbookid')->leftjoin('company l', 'l.companyid = j.companyid')->where("(
      (coalesce(grheaderid,'') like :grheaderid) and
      (coalesce(grdate,'') like :grdate) and
			(coalesce(grno,'') like :grno) and 
			(coalesce(l.companyname) like :companyid) and
			(coalesce(k.fullname,'') like :fullname) and
			(coalesce(t.headernote,'') like :headernote) and
			(coalesce(j.pono,'') like :poheaderid)) 
			and t.recordstatus < {$maxstat}
			and t.recordstatus in (".getUserRecordStatus('listgr').") and j.companyid in (".getUserObjectValues('company').")", array(
        ':grheaderid' => '%' . $grheaderid . '%',
        ':grdate' => '%' . $grdate . '%',
        ':grno' => '%' . $grno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':fullname' => '%' . $fullname . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,j.pono,k.fullname')->from('grheader t')->join('poheader j', 'j.poheaderid = t.poheaderid')->join('addressbook k', 'k.addressbookid = j.addressbookid')->join('company l', 'l.companyid = j.companyid')->where("(
        (grheaderid like :grheaderid) or
        (grdate like :grdate) or
                                        (grno like :grno) or (k.fullname like :fullname) or
                                        (t.headernote like :headernote) or
                                        (j.pono like :poheaderid)) and j.companyid in (".getUserObjectValues('company').")", array(
        ':grheaderid' => '%' . $grheaderid . '%',
        ':grdate' => '%' . $grdate . '%',
        ':grno' => '%' . $grno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':fullname' => '%' . $fullname . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['invgr'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,j.pono,k.fullname,l.companyname,j.companyid')->from('grheader t')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('addressbook k', 'k.addressbookid = j.addressbookid')->leftjoin('company l', 'l.companyid = j.companyid')->where("(
      (coalesce(grheaderid,'') like :grheaderid) or
      (coalesce(grdate,'') like :grdate) or
			(coalesce(grno,'') like :grno) or 
			(coalesce(k.fullname,'') like :fullname) or
			(coalesce(l.companyname,'') like :companyid) or
			(coalesce(t.headernote,'') like :headernote) or
			(coalesce(j.pono,'') like :poheaderid)) 
			and t.recordstatus > 0
			and l.companyid = '".$_GET['companyid']."'
			and t.recordstatus in (".getUserRecordStatus('listgr').") and j.companyid in (".getUserObjectValues('company').")", array(
        ':grheaderid' => '%' . $grheaderid . '%',
        ':grdate' => '%' . $grdate . '%',
        ':grno' => '%' . $grno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':fullname' => '%' . $fullname . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['pogr'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,j.pono,l.companyname,j.companyid, j.pono as fullname ')->from('grheader t')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('company l', 'l.companyid = j.companyid')->where("(
      (coalesce(grheaderid,'') like :grheaderid) or
      (coalesce(grdate,'') like :grdate) or
			(coalesce(grno,'') like :grno) or 
			(coalesce(l.companyname,'') like :companyid) or
			(coalesce(t.headernote,'') like :headernote) or
			(coalesce(j.pono,'') like :poheaderid)) 
			and t.recordstatus > 0
			and l.companyid = '".$_GET['companyid']."' and t.poheaderid = '".$_GET['poheaderid']."'
      and t.recordstatus = {$maxstat} 
      and t.grheaderid not in (select a.grheaderid from invoiceap a where a.recordstatus > 0 and a.grheaderid is not null)
			and t.recordstatus in (".getUserRecordStatus('listgr').") and j.companyid in (".getUserObjectValues('company').")", array(
        ':grheaderid' => '%' . $grheaderid . '%',
        ':grdate' => '%' . $grdate . '%',
        ':grno' => '%' . $grno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
	} else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,j.pono,k.fullname,l.companyname,j.companyid')->from('grheader t')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('addressbook k', 'k.addressbookid = j.addressbookid')->leftjoin('company l', 'l.companyid = j.companyid')->where("(
      (coalesce(grheaderid,'') like :grheaderid) and
      (coalesce(grdate,'') like :grdate) and
			(coalesce(grno,'') like :grno) and 
			(coalesce(k.fullname,'') like :fullname) and
			(coalesce(l.companyname,'') like :companyid) and
			(coalesce(t.headernote,'') like :headernote) and
			(coalesce(j.pono,'') like :poheaderid)) 
			and t.recordstatus < {$maxstat}
			and t.recordstatus in (".getUserRecordStatus('listgr').") and j.companyid in (".getUserObjectValues('company').")", array(
        ':grheaderid' => '%' . $grheaderid . '%',
        ':grdate' => '%' . $grdate . '%',
        ':grno' => '%' . $grno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':fullname' => '%' . $fullname . '%',
        ':headernote' => '%' . $headernote . '%',
        ':poheaderid' => '%' . $poheaderid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'grheaderid' => $data['grheaderid'],
        'grno' => $data['grno'],
        'grdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['grdate'])),
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgrheader' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('grdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('podetail d', 'd.podetailid = t.podetailid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->where('grheaderid = :grheaderid', array(
      ':grheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,
		c.description as slocdesc,e.description,a.barcode')->from('grdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('podetail d', 'd.podetailid = t.podetailid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->where('grheaderid = :grheaderid', array(
      ':grheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'grdetailid' => $data['grdetailid'],
        'grheaderid' => $data['grheaderid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'barcode' => $data['barcode'],
        'description' => $data['description'],
        'itemtext' => $data['itemtext']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
    //$connection  = Yii::app()->db;
    //$transaction = $connection->beginTransaction();
    //try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertgrheader(:vcompanyid,:vgrdate,:vgrno,:vpoheaderid,:vheadernote,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
        $command->bindvalue(':vgrno', $arraydata[2], PDO::PARAM_STR);
        $command->bindvalue(':vrecordstatus', $arraydata[6], PDO::PARAM_STR);
      } else {
        $sql     = 'call Updategrheader(:vid,:vgrdate,:vpoheaderid,:vheadernote,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $arraydata[0]);
      }
      $command->bindvalue(':vgrdate', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vpoheaderid', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      //$transaction->commit();
      //GetMessage(false, 'insertsuccess');
    /*}
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }*/
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileGrheader"]["name"]);
		if (move_uploaded_file($_FILES["FileGrheader"]["tmp_name"], $target_file)) {
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
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$docno = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$pono = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$poheaderid = Yii::app()->db->createCommand("select poheaderid 
						from poheader 
						where pono = '".$pono."'
						and companyid = ".$companyid."
						")->queryScalar();
					$abid = Yii::app()->db->createCommand("select grheaderid 
						from grheader 
						where grno = '".$docno."'
						and companyid = ".$companyid." 
						")->queryScalar();
					if ($abid == '') {					
						$headernote = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$this->ModifyData($connection,array('',$companyid,$docno,$docdate,$poheaderid,$headernote,$recordstatus));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select grheaderid 
						from grheader 
						where grno = '".$docno."'
						and companyid = ".$companyid." 
						")->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(7, $row)->getValue() != '') {
							$productname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$productid = Yii::app()->db->createCommand("select addresstypeid from product where productname = '".$productname."'")->queryScalar();
							$qty = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$uomcode = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
							$sloccode = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
							$sbincode = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$sbinid = Yii::app()->db->createCommand("select storagebinid 
								from storagebin 
								where description = '".$sbincode."'
								and slocid = ".$slocid."
								")->queryScalar();
							$itemtext = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$this->ModifyDataDetail(array('',$abid,$productid,$qty,$uomid,$slocid,$sbinid,$itemtext));
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
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
        $this->ModifyData($connection,array((isset($_POST['grheaderid'])?$_POST['grheaderid']:''),'','',date(Yii::app()->params['datetodb'], strtotime($_POST['grdate'])),
        $_POST['poheaderid'],$_POST['headernote']));
        $transaction->commit();
        getMessage(false,'insertsuccess');
    }
    catch(Exception $e)
    {
        $transaction->rollback();
        GetMessage(true,$e->getMessage());
    }
  }
	private function ModifyDataDetail($connection,$arraydata) {
    //$connection  = Yii::app()->db;
    //$transaction = $connection->beginTransaction();
    //try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertgrdetail(:vgrheaderid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vitemtext,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updategrdetail(:vid,:vgrheaderid,:vproductid,:vqty,:vuomid,:vslocid,:vstoragebinid,:vitemtext,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
      }
      $command->bindvalue(':vgrheaderid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vitemtext', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      //$transaction->commit();
      //GetMessage(false, 'insertsuccess');
    /*}
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }*/
	}
  public function actionSavedetail() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
        $this->ModifyDataDetail($connection,array((isset($_POST['grdetailid'])?$_POST['grdetailid']:''),$_POST['grheaderid'],$_POST['productid'],$_POST['qty'],$_POST['unitofmeasureid'],
                $_POST['slocid'],$_POST['storagebinid'],$_POST['itemtext']));
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
        $sql     = 'call Purgegrheader(:vid,:vcreatedby)';
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
        $sql     = 'call Purgegrdetail(:vid,:vcreatedby)';
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
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteGR(:vid,:vcreatedby)';
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
        $sql     = 'call ApproveGR(:vid,:vcreatedby)';
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
  public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
			$sql = "select headernote from poheader where poheaderid = ".$_POST['id'];
			$header = Yii::app()->db->createCommand($sql)->queryScalar();
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateGRPO(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success',
            'headernote' => $header,
            'div' => "Data generated"
          ));
        }
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(false, $e->getMessage());
      }
    }
    Yii::app()->end();
  }
  public function actionGeneratebarcode() {
  $ids = $_REQUEST['id'];
		foreach ($ids as $id) {
			$sql    = "select ifnull(recordstatus,0) as recordstatus from grheader where grheaderid  = " . $id . " and recordstatus = getwfmaxstatbywfname('appgr')";
			$status = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql1    = "select ifnull(isbarcode,0) as isbarcode from grheader where grheaderid  = " . $id . " and recordstatus = getwfmaxstatbywfname('appgr')";
			$isbarcode = Yii::app()->db->createCommand($sql1)->queryScalar();
			$sql2    = "select ifnull(count(1),0) as barcode from grdetail a join product b on b.productid=a.productid where grheaderid  = " . $id . " and barcode = '' ";
			$barcode = Yii::app()->db->createCommand($sql1)->queryScalar();
			if ($status == 0)  {
				GetMessage(false, 'docnotmaxstatus');
			} 
			else 
			if ($isbarcode == 1) {
				GetMessage(false, 'datagenerated');
			}
			else 
			if ($barcode != 0) {
				GetMessage(false, 'emptybarcode');
			}
			else {
				$sql = "insert into tempscan (companyid,grheaderid,grdetailid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
				select d.companyid,a.grheaderid,b.grdetailid,b.productid,b.slocid,b.qty,0,c.barcode,b.unitofmeasureid,1
				from grheader a 
				join grdetail b on b.grheaderid = a.grheaderid 
								join poheader d on d.poheaderid = a.poheaderid
				join product c on c.productid = b.productid 
				where a.grheaderid = " . $id;
				Yii::app()->db->createCommand($sql)->execute();
				$sql  = "select g.companyid,a.grheaderid,b.grdetailid,b.productid,b.slocid,b.qty,0,c.barcode,b.unitofmeasureid,1,0,f.plantid,a.grdate
						from grheader a 
						join grdetail b on b.grheaderid = a.grheaderid
						join product c on c.productid = b.productid 
						join sloc d on d.slocid = b.slocid 
						join plant f on f.plantid = d.plantid 
						join unitofmeasure e on e.unitofmeasureid = b.unitofmeasureid
												join poheader g on g.poheaderid = a.poheaderid
						where a.grheaderid = " . $id;
				$rows = Yii::app()->db->createCommand($sql)->queryAll();
				foreach ($rows as $row) {
					for ($i = 1; $i <= $row['qty']; $i++) {
						$sql = "insert into tempscan (companyid,grheaderid,grdetailid,productid,slocid,qtyori,qtyscan,barcode,unitofmeasureid,isean)
							values (" . $row['companyid'] . "," . $row['grheaderid'] . "," . $row['grdetailid'] . "," . $row['productid'] . "," . $row['slocid'] . ",1,0,concat(" . $row['plantid'] . $row['grdetailid'] . ",'-G',lpad(" . $i . ",5,'0'))," . $row['unitofmeasureid'] . ",0)";
						Yii::app()->db->createCommand($sql)->execute();
					}
				}
				$sql = "update grheader set isbarcode = 1 where grheaderid = " . $id;
				Yii::app()->db->createCommand($sql)->execute();
				GetMessage(false, 'GenerateBarcodeDone');
			}
		}
  }
  public function actionDownSticker() {
    parent::actionDownload();
		$sql = "select a.*,b.productname,
					substr(productname,1,(20+instr(substr(productname,21,20),' '))) as productname1,
					substr(productname,(21+instr(substr(productname,21,20),' ')),(20+instr(substr(productname,21,20),' '))) as productname2,
					substr(productname,((21+instr(substr(productname,21,20),' '))+(20+instr(substr(productname,21,20),' '))),(20+instr(substr(productname,21,20),' '))) as productname3
			from tempscan a 
			join product b on b.productid = a.productid 
			where a.isean = 1 and a.grheaderid = " . $_REQUEST['id'];
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
						where a.isean = 0 and a.grheaderid = " . $_REQUEST['id'] . " 
						and a.productid = " . $row['productid'] . " and right(a.barcode,5) = " . $i;
				    $c128s = Yii::app()->db->createCommand($sql)->queryAll();
				    foreach ($c128s as $c128) {
					    $code = $c128['barcode'];
					    $this->pdf->Code128($x-4.5, $y+15, $code, 30, 3);
					    $this->pdf->text($x+5, $y +20, $code);
					  }
      			$this->pdf->sety($y);
      		} else {
      			$x = 53;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['productname1']);
						$this->pdf->text($x-8, $y+6, $row['productname2']);				
						$this->pdf->text($x-8, $y+8, $row['productname3']);
      			$this->pdf->SetFont('Arial', '', 5);
		      	$this->pdf->EAN13($x+1, $y+8.5, $row['barcode'], $h=3, $w=.20);
		      	$sql   = "select a.*,b.productname 
						from tempscan a 
						join product b on b.productid = a.productid 
						where a.isean = 0 and a.grheaderid = " . $_REQUEST['id'] . " 
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
    $sql = "select b.companyid,a.grno,a.grdate,a.grheaderid,b.pono,c.fullname,a.recordstatus,a.headernote
						from grheader a
						left join poheader b on b.poheaderid = a.poheaderid
						left join addressbook c on c.addressbookid = b.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.grheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('grheader');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->setFont('Arial');
    $this->pdf->AliasNbPages();
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(7);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['grno']);
      $this->pdf->text(50, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
      $this->pdf->text(90, $this->pdf->gety(), 'PO ');
      $this->pdf->text(100, $this->pdf->gety(), ': ' . $row['pono']);
      $this->pdf->text(130, $this->pdf->gety(), 'Supplier ');
      $this->pdf->text(140, $this->pdf->gety(), ': ' . $row['fullname']);
      $sql1        = "select b.productname, a.qty, c.uomcode,concat(d.sloccode,'-',d.description) as description,
							e.description as rak
							from grdetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							left join sloc d on d.slocid = a.slocid
							left join storagebin e on e.storagebinid = a.storagebinid
							where grheaderid = " . $row['grheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->colalign = array(
        'C',
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
        20,
        70
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Gudang'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
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
          $row1['description'] . ' - ' . $row1['rak']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        170
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note:',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->text(10, $this->pdf->gety(), 'Penerima');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(90, $this->pdf->gety(), 'Supplier / Perwakilan');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(90, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownPDF1() {
    parent::actionDownload();
    $sql = "SELECT c.grno, a.pono, c.grdate, c.grheaderid
FROM grheader c
JOIN poheader a ON a.poheaderid = c.poheaderid  ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where c.grheaderid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Stock Yang Melebihi Stock PO";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No GR ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['grno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO PO ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['pono']);
     
     
      $sql1= "select a.*, poqty, qtyres, (poqty-qtyres) as sisa,c.productname,d.sloccode, f.statusname, 
      IF(poqty-qtyres=0,'Qty Sudah Terpenuhi',(a.qty-(poqty-qtyres))) as keterangan
from grdetail a
join podetail b on b.podetailid=a.podetailid 
join grheader z on z.grheaderid = a.grheaderid
join product c on c.productid=a.productid
join sloc d on d.slocid=a.slocid
join poheader f on f.poheaderid=b.poheaderid
where a.grheaderid=".$row['grheaderid']." AND z.recordstatus NOT IN (0,3) HAVING a.qty - sisa > 0
";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          50,
          25,
          30,
          40
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Gudang',
          'Selisih',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'L',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
          if(is_string($row1['keterangan'])){
              $ket = $row1['keterangan'];
          }else{
              
          }
        $this->pdf->row(array(
          $i,
          $row1['productname'],$row1['sloccode'], $ket, $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownPDF2() {
    parent::actionDownload();
    $sql = "SELECT c.grno, a.pono, c.grdate, c.grheaderid
FROM grheader c
JOIN poheader a ON a.poheaderid = c.poheaderid  ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where c.grheaderid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Material Yang Tidak ada Di Gudang";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No GR ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['grno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO PO ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['pono']);
      $sql1= "select a.grheaderid, b.productid, a.grdate,a.grno, d.sloccode,a.statusname, c.productname
from grheader a
join grdetail b on a.grheaderid = b.grheaderid
join product c on c.productid = b.productid
join sloc d on d.slocid = b.slocid
WHERE b.slocid NOT IN (
SELECT z.slocid FROM productplant z WHERE z.productid = b.productid AND z.recordstatus=1) AND a.grheaderid=".$row['grheaderid']."";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        
        $this->pdf->colalign = array(
         'C',
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          10,
          70,
          25,
          25,
          40
        ));
        $this->pdf->colheader = array(
          'No',
          'ID',
          'Nama Barang',
          'Tanggal ',
          'Gudang',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'L',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
      $i= $i + 1;
        $this->pdf->row(array(
         $i,
          $row1['grheaderid'],$row1['productname'], $row1['grdate'],$row1['sloccode'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }    
	public function actionDownPDF3() {
    parent::actionDownload();
    $sql = "SELECT c.grno, a.pono, c.grdate, c.grheaderid
FROM grheader c
JOIN poheader a ON a.poheaderid = c.poheaderid  ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where c.grheaderid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Material Yang Tidak ada Sumber Gudang";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No GR ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['grno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO PO ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['pono']);
     
      $sql1= "select a.grheaderid, b.productid, a.grdate,a.grno, d.sloccode,a.statusname, c.productname,e.uomcode 
from grheader a
join grdetail b on a.grheaderid = b.grheaderid
join unitofmeasure e on e.unitofmeasureid= b.unitofmeasureid
join product c on c.productid = b.productid
join sloc d on d.slocid = b.slocid
WHERE b.unitofmeasureid NOT IN (
SELECT z.unitofissue FROM productplant z WHERE z.productid = b.productid AND z.recordstatus=1 AND z.slocid = b.slocid)
AND a.grheaderid=".$row['grheaderid']."";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          10,
          70,
          25,
          25,
          40
        ));
        $this->pdf->colheader = array(
           'No',
          'ID',
          'Nama Barang',
          'Tanggal ',
          'Satuan',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'L',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
      $i= $i + 1;
        $this->pdf->row(array(
         $i,
          $row1['grheaderid'],$row1['productname'],$row1['grdate'],$row1['uomcode'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }        
  public function actionDownxls() {
    $this->menuname = 'grheader';
    parent::actionDownxls();
    $sql = "select b.companyid,a.grno,a.grdate,a.grheaderid,b.pono,c.fullname,a.recordstatus,a.headernote
						from grheader a
						left join poheader b on b.poheaderid = a.poheaderid
						left join addressbook c on c.addressbookid = b.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.grheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['grno'])->setCellValueByColumnAndRow(4, $line, 'PO No')->setCellValueByColumnAndRow(5, $line, ': ' . $row['pono']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Date')->setCellValueByColumnAndRow(1, $line, ': ' . $row['grdate'])->setCellValueByColumnAndRow(4, $line, 'Vendor')->setCellValueByColumnAndRow(5, $line, ': ' . $row['fullname']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Barang')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Gudang');
      $line++;
      $sql1        = "select b.productname, a.qty, c.uomcode,concat(d.sloccode,'-',d.description) as description,
							e.description as rak
							from grdetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							left join sloc d on d.slocid = a.slocid
							left join storagebin e on e.storagebinid = a.storagebinid
							where grheaderid = " . $row['grheaderid'];
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['qty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['description'] . ' - ' . $row1['rak']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note : ')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Penerima')->setCellValueByColumnAndRow(1, $line, 'Mengetahui')->setCellValueByColumnAndRow(2, $line, 'Supllier / Perwakilan');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}