<?php
class PrheaderController extends Controller
{
  public $menuname = 'prheader';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexmaterial()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchmaterial();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into prheader (prdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inspr').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'prheaderid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $prheaderid       = isset($_POST['prheaderid']) ? $_POST['prheaderid'] : '';
    $prdate           = isset($_POST['prdate']) ? $_POST['prdate'] : '';
    $prno             = isset($_POST['prno']) ? $_POST['prno'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $deliveryadviceid = isset($_POST['deliveryadviceid']) ? $_POST['deliveryadviceid'] : '';
    $slocid     = isset($_POST['slocid']) ? $_POST['slocid'] : '';
    $prheaderid       = isset($_GET['q']) ? $_GET['q'] : $prheaderid;
    $prdate           = isset($_GET['q']) ? $_GET['q'] : $prdate;
    $prno             = isset($_GET['q']) ? $_GET['q'] : $prno;
    $headernote       = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $deliveryadviceid = isset($_GET['q']) ? $_GET['q'] : $deliveryadviceid;
    $slocid     = isset($_GET['q']) ? $_GET['q'] : $slocid;
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'prheaderid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $page             = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows             = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort             = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order            = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppr')")->queryScalar();
		
    if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('prheader t')
			->leftjoin('deliveryadvice s', 's.deliveryadviceid = t.deliveryadviceid')
			->leftjoin('sloc k', 'k.slocid = s.slocid')
			->leftjoin('plant l', 'l.plantid = k.plantid')
			->leftjoin('company m', 'm.companyid = l.companyid')
			->where("
       ((coalesce(prdate,'') like :prdate) and 
						(coalesce(prno,'') like :prno) and 
						(coalesce(t.headernote,'') like :headernote) and 
						(coalesce(s.sloccode,'') like :slocid) and 
						(coalesce(s.dano,'') like :deliveryadviceid))
					and t.recordstatus in (".getUserRecordStatus('listpr').")
					and t.recordstatus > 0 
					and t.recordstatus < {$maxstat} 
					and k.slocid in (".getUserObjectWfValues('sloc','apppr').")
					and m.companyid in (".getUserObjectValues('company').")", array(
        ':prdate' => '%' . $prdate . '%',
        ':prno' => '%' . $prno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':deliveryadviceid' => '%' . $deliveryadviceid . '%',
        ':slocid' => '%' . $slocid . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('prheader t')
			->leftjoin('deliveryadvice s', 's.deliveryadviceid = t.deliveryadviceid')
			->leftjoin('sloc k', 'k.slocid = s.slocid')
			->leftjoin('plant l', 'l.plantid = k.plantid')
			->leftjoin('company m', 'm.companyid = l.companyid')
			->where("
      ((coalesce(prdate,'') like :prdate) or 
						(coalesce(prno,'') like :prno) or 
						(coalesce(t.headernote,'') like :headernote) or 
						(coalesce(s.sloccode,'') like :slocid) or 
						(coalesce(s.dano,'') like :deliveryadviceid)) and m.companyid in (".getUserObjectValues('company').")", array(
        ':prdate' => '%' . $prdate . '%',
        ':prno' => '%' . $prno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':deliveryadviceid' => '%' . $deliveryadviceid . '%',
        ':slocid' => '%' . $slocid . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,s.dano,s.sloccode,s.slocid,k.description as slocdesc,
			(
		select case when sum(qty) > sum(poqty) then 1 else 0 end
		from prmaterial z 
		where z.prheaderid = t.prheaderid 
		) as warna')->from('prheader t')->leftjoin('deliveryadvice s', 's.deliveryadviceid = t.deliveryadviceid')
		->leftjoin('sloc k', 'k.slocid = s.slocid')
					->leftjoin('plant l', 'l.plantid = k.plantid')
			->leftjoin('company m', 'm.companyid = l.companyid')
		->where("
      ((coalesce(prdate,'') like :prdate) and 
						(coalesce(prno,'') like :prno) and 
						(coalesce(t.headernote,'') like :headernote) and 
						(coalesce(s.sloccode,'') like :slocid) and 
						(coalesce(s.dano,'') like :deliveryadviceid))
					and t.recordstatus in (".getUserRecordStatus('listpr').")
					and t.recordstatus > 0 
					and t.recordstatus < {$maxstat} 
					and k.slocid in (".getUserObjectWfValues('sloc','apppr').")
					and m.companyid in (".getUserObjectValues('company').")", array(
        ':prdate' => '%' . $prdate . '%',
        ':prno' => '%' . $prno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':deliveryadviceid' => '%' . $deliveryadviceid . '%',
        ':slocid' => '%' . $slocid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,s.dano,s.sloccode,s.slocid,k.description as slocdesc,
			(
		select case when sum(qty) > sum(poqty) then 1 else 0 end
		from prmaterial z 
		where z.prheaderid = t.prheaderid 
		) as warna')->from('prheader t')->leftjoin('deliveryadvice s', 's.deliveryadviceid = t.deliveryadviceid')
		->leftjoin('sloc k', 'k.slocid = s.slocid')
					->leftjoin('plant l', 'l.plantid = k.plantid')
			->leftjoin('company m', 'm.companyid = l.companyid')
		->where("
      ((coalesce(prdate,'') like :prdate) or 
						(coalesce(prno,'') like :prno) or 
						(coalesce(t.headernote,'') like :headernote) or 
						(coalesce(s.sloccode,'') like :slocid) or 
						(coalesce(s.dano,'') like :deliveryadviceid)) and m.companyid in (".getUserObjectValues('company').")", array(
        ':prdate' => '%' . $prdate . '%',
        ':prno' => '%' . $prno . '%',
        ':headernote' => '%' . $headernote . '%',
        ':deliveryadviceid' => '%' . $deliveryadviceid . '%',
        ':slocid' => '%' . $slocid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'prheaderid' => $data['prheaderid'],
        'prdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['prdate'])),
        'prno' => $data['prno'],
        'headernote' => $data['headernote'],
        'deliveryadviceid' => $data['deliveryadviceid'],
        'dano' => $data['dano'],
        'warna' => $data['warna'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusprheader' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchmaterial()
  {
    header("Content-Type: application/json");
    $id = 0;
    $q  = "";
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    if (isset($_GET['q'])) {
      $q = $_GET['q'];
    }
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'prmaterialid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset = ($page - 1) * $rows;
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('prmaterial t')->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.prheaderid = :prheaderid', array(
        ':prheaderid' => $id
      ))->queryRow();
    } else if (isset($_GET['popr'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('prmaterial t')->leftjoin('prheader ef', 'ef.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->leftjoin('deliveryadvice e', 'e.deliveryadviceid = d.deliveryadviceid')->leftjoin('sloc f', 'f.slocid = e.slocid')->leftjoin('plant g', 'g.plantid = f.plantid')->where("((ef.prno like :prno) or (a.productname like :prname)) 
                and g.companyid = '".$_GET['companyid']."'
                and ef.recordstatus in (select b.wfbefstat
				from workflow a
				inner join wfgroup b on b.workflowid = a.workflowid
				inner join groupaccess c on c.groupaccessid = b.groupaccessid
				inner join usergroup d on d.groupaccessid = c.groupaccessid
				inner join useraccess e on e.useraccessid = d.useraccessid
				where upper(a.wfname) = upper('listpr') and upper(e.username)=upper('" . Yii::app()->user->name . "') and 
				ef.prno is not null and
						g.companyid in (select gm.menuvalueid from groupmenuauth gm
						inner join menuauth ma on ma.menuauthid = gm.menuauthid
						where upper(ma.menuobject) = upper('company') and gm.groupaccessid = c.groupaccessid)) and t.qty > t.poqty", array(
        ':prno' => '%' . $q . '%',
        ':prname' => '%' . $q . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('prmaterial t')->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.prheaderid = :prheaderid', array(
        ':prheaderid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.description,e.prno')->from('prmaterial t')->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.prheaderid = :prheaderid', array(
        ':prheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['popr'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.description,ef.prno')->from('prmaterial t')->leftjoin('prheader ef', 'ef.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->leftjoin('deliveryadvice ex', 'ex.deliveryadviceid = d.deliveryadviceid')->leftjoin('sloc f', 'f.slocid = ex.slocid')->leftjoin('plant g', 'g.plantid = f.plantid')->where("((ef.prno like :prno) or (a.productname like :prname))                
                and g.companyid = '".$_GET['companyid']."'
                and ef.recordstatus in (select b.wfbefstat
				from workflow a
				inner join wfgroup b on b.workflowid = a.workflowid
				inner join groupaccess c on c.groupaccessid = b.groupaccessid
				inner join usergroup d on d.groupaccessid = c.groupaccessid
				inner join useraccess e on e.useraccessid = d.useraccessid
				where upper(a.wfname) = upper('listpr') and upper(e.username)=upper('" . Yii::app()->user->name . "') and 
				ef.prno is not null and
						g.companyid in (select gm.menuvalueid from groupmenuauth gm
						inner join menuauth ma on ma.menuauthid = gm.menuauthid
						where upper(ma.menuobject) = upper('company') and gm.groupaccessid = c.groupaccessid)) and t.qty > t.poqty", array(
        ':prno' => '%' . $q . '%',
        ':prname' => '%' . $q . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.description,e.prno')->from('prmaterial t')->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.prheaderid = :prheaderid', array(
        ':prheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
			if ($data['qty'] > $data['poqty']) {
				$wqty = 1;
			} else {
				$wqty = 0;
			}
      $row[] = array(
        'prmaterialid' => $data['prmaterialid'],
        'prheaderid' => $data['prheaderid'],
        'prno' => $data['prno'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
				'wqty' => $wqty,
        'requestedbyid' => $data['requestedbyid'],
        'description' => $data['description'],
        'poqty' => Yii::app()->format->formatNumber($data['poqty']),
        'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'itemtext' => $data['itemtext']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionsave()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertprheader(:vprdate,:vheadernote,:vdeliveryadviceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateprheader(:vid,:vprdate,:vheadernote,:vdeliveryadviceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['prheaderid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['prheaderid']);
      }
      $command->bindvalue(':vprdate', date(Yii::app()->params['datetodb'], strtotime($_POST['prdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vdeliveryadviceid', $_POST['deliveryadviceid'], PDO::PARAM_STR);
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
  public function actionSaveMaterial()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertprmaterial(:vprheaderid,:vproductid,:vqty,:vuomid,:vreqid,:vreqdate,:vitemtext,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateprmaterial(:vid,:vprheaderid,:vproductid,:vqty,:vuomid,:vreqid,:vreqdate,:vitemtext,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['prmaterialid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['prmaterialid']);
      }
      $command->bindvalue(':vprheaderid', $_POST['prheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['unitofmeasureid'], PDO::PARAM_STR);
      $command->bindvalue(':vreqid', $_POST['requestedbyid'], PDO::PARAM_STR);
      $command->bindvalue(':vreqdate', date(Yii::app()->params['datetodb'], strtotime($_POST['reqdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vitemtext', $_POST['itemtext'], PDO::PARAM_STR);
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
        $sql     = 'call Purgeprheader(:vid,:vcreatedby)';
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
  public function actionPurgedetail()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call purgeprmaterial(:vid,:vcreatedby)';
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeletePR(:vid,:vcreatedby)';
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
      GetMessage(true, 'chooseone', 1);
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
        $sql     = 'call ApprovePR(:vid,:vcreatedby)';
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
  public function actionGeneratedetail()
  {
    if (isset($_POST['id'])) {
			$sql = "select headernote from deliveryadvice where deliveryadviceid = ".$_POST['id'];
			$header = Yii::app()->db->createCommand($sql)->queryScalar();
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GeneratePRDA(:vid, :vhid)';
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
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select b.slocid,e.companyid,a.prno,a.prdate,a.headernote,a.prheaderid,b.sloccode,b.description,c.dano,a.recordstatus
						from prheader a 
						inner join deliveryadvice c on c.deliveryadviceid = a.deliveryadviceid
						inner join sloc b on b.slocid = c.slocid     
						inner join plant d on d.plantid = b.plantid
						inner join company e on e.companyid = d.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.prheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('prheader');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->SetFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(9);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['prno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])));
      $this->pdf->text(110, $this->pdf->gety() + 2, 'Gudang ');
      $this->pdf->text(150, $this->pdf->gety() + 2, ': ' . $row['sloccode'] . ' - ' . $row['description']);
      $this->pdf->text(110, $this->pdf->gety() + 6, 'No Permintaan Barang ');
      $this->pdf->text(150, $this->pdf->gety() + 6, ': ' . $row['dano']);
      $sql1        = "select b.productname, a.qty, c.uomcode, a.itemtext
							from prmaterial a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							where prheaderid = " . $row['prheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        90,
        20,
        15,
        55
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
          $row1['itemtext']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
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
      $this->pdf->checkNewPage(20);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(10, $this->pdf->gety(), 'Penerima');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(120, $this->pdf->gety(), 'Mengetahui Pembuat');
      $this->pdf->text(170, $this->pdf->gety(), 'Pembuat');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'prheader';
    parent::actionDownxls();
    $sql = "select b.slocid,e.companyid,a.prno,a.prdate,a.headernote,a.prheaderid,b.description,c.dano,a.recordstatus
						from prheader a 
						inner join deliveryadvice c on c.deliveryadviceid = a.deliveryadviceid
						inner join sloc b on b.slocid = c.slocid     
						inner join plant d on d.plantid = b.plantid
						inner join company e on e.companyid = d.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.prheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['prno'])->setCellValueByColumnAndRow(4, $line, 'No Permintaan Barang')->setCellValueByColumnAndRow(5, $line, ': ' . $row['dano']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Tgl')->setCellValueByColumnAndRow(1, $line, ': ' . $row['prdate'])->setCellValueByColumnAndRow(4, $line, 'Gudang')->setCellValueByColumnAndRow(5, $line, ': ' . $row['description']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Items')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Remark');
      $line++;
      $sql1        = "select b.productname, a.qty, c.uomcode, a.itemtext
							from prmaterial a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							where prheaderid = " . $row['prheaderid'];
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['qty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['itemtext']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note : ')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Penerima')->setCellValueByColumnAndRow(1, $line, 'Mengetahui')->setCellValueByColumnAndRow(3, $line, 'Mengetahui Pembuat')->setCellValueByColumnAndRow(4, $line, 'Pembuat');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................')->setCellValueByColumnAndRow(4, $line, '........................');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
	public function actionDownPDF1() {
    parent::actionDownload();
    $sql = "select b.slocid,e.companyid,a.prno,a.prdate,a.headernote,a.prheaderid,b.sloccode,b.description,c.dano,a.recordstatus
						from prheader a 
						inner join deliveryadvice c on c.deliveryadviceid = a.deliveryadviceid
						inner join sloc b on b.slocid = c.slocid     
						inner join plant d on d.plantid = b.plantid
						inner join company e on e.companyid = d.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.prheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('prheader');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->SetFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(9);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['prno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['prdate'])));
      $this->pdf->text(110, $this->pdf->gety() + 2, 'Gudang ');
      $this->pdf->text(150, $this->pdf->gety() + 2, ': ' . $row['sloccode'] . ' - ' . $row['description']);
      $this->pdf->text(110, $this->pdf->gety() + 6, 'No Permintaan Barang ');
      $this->pdf->text(150, $this->pdf->gety() + 6, ': ' . $row['dano']);
      $sql1        = "select b.productname, a.qty, c.uomcode, a.itemtext, a.productid,poqty
							from prmaterial a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							where prheaderid = " . $row['prheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
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
        80,
        20,
        20,
        20,
        25,
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Qty PO',
        'Qty Stock',
        'Unit',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $this->pdf->setFont('Arial','',8);
        $sql2 = "select sum(qty) as qty, e.companyname, c.uomcode, e.companycode
            from productstock a
            join sloc b on b.slocid = a.slocid
            join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
            join plant d on d.plantid = b.plantid
            join company e on e.companyid = d.companyid
            where a.productid = {$row1['productid']}
            and e.recordstatus=1
            group by e.companyid";
        $command2 = Yii::app()->db->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
          
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          Yii::app()->format->formatNumber($row1['poqty']),
          '',
          $row1['uomcode'],
          $row1['itemtext']
        ));
        $k=0;
        $subqtystock=0;
        foreach($dataReader2 as $row2)
        {
           $k=+1;
           $this->pdf->row(array(
           '',
           ' - '.$row2['companyname'],
           '',
           '',
           Yii::app()->format->formatNumber($row2['qty']),
           $row2['uomcode'],
        )); 
            $subqtystock += $row2['qty'];
        }
          $this->pdf->setFont('Arial','B',9);
          $this->pdf->row(array(
          '',
          'Jumlah',
          Yii::app()->format->formatNumber($row1['qty']),
          Yii::app()->format->formatNumber($row1['poqty']),
          Yii::app()->format->formatNumber($subqtystock),
        ));
        $this->pdf->sety($this->pdf->gety()+5);
      }
      $this->pdf->setFont('Arial','',8);
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
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
      $this->pdf->checkNewPage(20);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(10, $this->pdf->gety(), 'Penerima');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(120, $this->pdf->gety(), 'Mengetahui Pembuat');
      $this->pdf->text(170, $this->pdf->gety(), 'Pembuat');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
	public function actionDownxls1()
  {
    $this->menuname = 'checkfpp';
    parent::actionDownxls();
    $sql = "select b.slocid,e.companyid,a.prno,a.prdate,a.headernote,a.prheaderid,b.sloccode,b.description,c.dano,a.recordstatus
						from prheader a 
						inner join deliveryadvice c on c.deliveryadviceid = a.deliveryadviceid
						inner join sloc b on b.slocid = c.slocid     
						inner join plant d on d.plantid = b.plantid
						inner join company e on e.companyid = d.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.prheaderid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 6;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(1, 2, ': ' . $row['prno'])
          ->setCellValueByColumnAndRow(5, 2, ': ' . $row['sloccode'] . ' - ' . $row['description']);
        
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(1, 3, ': ' . $row['prdate'])
          ->setCellValueByColumnAndRow(5, 3, ': ' . $row['dano']);
        
      $sql1        = "select b.productname, a.qty, c.uomcode, a.itemtext,b.productid,poqty
							from prmaterial a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							where prheaderid = " . $row['prheaderid'];
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $line, $i += 1)
            ->setCellValueByColumnAndRow(1, $line, $row1['productname'])
            ->setCellValueByColumnAndRow(2, $line, $row1['qty'])
            ->setCellValueByColumnAndRow(3, $line, $row1['poqty'])
            ->setCellValueByColumnAndRow(5, $line, $row1['uomcode'])
            ->setCellValueByColumnAndRow(6, $line, $row1['itemtext']);
        $line++;
        
        $sql2 = "select sum(qty) as qty, e.companyname, c.uomcode, e.companycode
            from productstock a
            join sloc b on b.slocid = a.slocid
            join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
            join plant d on d.plantid = b.plantid
            join company e on e.companyid = d.companyid
            where a.productid = {$row1['productid']}
            and e.recordstatus=1
            group by e.companyid";
          
        $dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
        $k=0;
        $subqtystock = 0;
        foreach($dataReader2 as $row2) {
            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0, $line, '')
                ->setCellValueByColumnAndRow(1, $line, ' - '.$row2['companyname'])
                ->setCellValueByColumnAndRow(4, $line, $row2['qty'])
                ->setCellValueByColumnAndRow(5, $line, $row1['uomcode']);
            $line++;
            $subqtystock += $row2['qty'];
        }
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $line, 'Jumlah')
            ->setCellValueByColumnAndRow(2, $line, $row1['qty'])
            ->setCellValueByColumnAndRow(3, $line, $row1['poqty'])
            ->setCellValueByColumnAndRow(4, $line, $subqtystock);
        $line+=2; 
      }
      $line++;
        
    }
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'Note : ')
          ->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'Penerima')
          ->setCellValueByColumnAndRow(1, $line, 'Mengetahui')
          ->setCellValueByColumnAndRow(3, $line, 'Mengetahui Pembuat')
          ->setCellValueByColumnAndRow(4, $line, 'Pembuat');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, '........................')
          ->setCellValueByColumnAndRow(1, $line, '........................')
          ->setCellValueByColumnAndRow(3, $line, '........................')
          ->setCellValueByColumnAndRow(4, $line, '........................');
      $line++;
      
    $this->getFooterXLS($this->phpExcel);
  }
}
