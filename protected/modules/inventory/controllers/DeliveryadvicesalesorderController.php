<?php
class DeliveryadvicesalesorderController extends Controller
{
  public $menuname = 'deliveryadvicesalesorder';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGenerateso()
  {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateDASO(:vid, :vslocid,:vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollBack();
      }
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $deliveryadviceid = isset($_POST['deliveryadviceid']) ? $_POST['deliveryadviceid'] : '';
    $dadate           = isset($_POST['dadate']) ? $_POST['dadate'] : '';
    $dano             = isset($_POST['dano']) ? $_POST['dano'] : '';
    $useraccessid     = isset($_POST['useraccessid']) ? $_POST['useraccessid'] : '';
    $slocid           = isset($_POST['slocid']) ? $_POST['slocid'] : '';
    $soheaderid       = isset($_POST['soheaderid']) ? $_POST['soheaderid'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $recordstatus     = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $deliveryadviceid = isset($_GET['q']) ? $_GET['q'] : $deliveryadviceid;
    $dadate           = isset($_GET['q']) ? $_GET['q'] : $dadate;
    $dano             = isset($_GET['q']) ? $_GET['q'] : $dano;
    $useraccessid     = isset($_GET['q']) ? $_GET['q'] : $useraccessid;
    $slocid           = isset($_GET['q']) ? $_GET['q'] : $slocid;
    $soheaderid       = isset($_GET['q']) ? $_GET['q'] : $soheaderid;
    $headernote       = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $recordstatus     = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'deliveryadviceid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $page             = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows             = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort             = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order            = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
    $rec          = array();
    $com          = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appda')")->queryScalar();
		
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('deliveryadvice t')->where('
				(t.deliveryadviceid like :deliveryadviceid) or
				(t.dadate like :dadate) or
				(t.dano like :dano) or
				(t.sono like :soheaderid) or				
				(t.username like :useraccessid) or
				(t.sloccode like :slocid) or				
				(t.headernote like :headernote) and t.soheaderid > 0', array(
        ':deliveryadviceid' => '%' . $deliveryadviceid . '%',
        ':dadate' => '%' . $dadate . '%',
        ':dano' => '%' . $dano . '%',
        ':soheaderid' => '%' . $soheaderid . '%',
        ':useraccessid' => '%' . $useraccessid . '%',
        ':slocid' => '%' . $slocid . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->queryScalar();
    } else if (isset($_GET['dapr'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('deliveryadvice t')->where("((t.dano like :dano) or (t.sloccode like :slocid) or (t.slocdesc like :slocid)) 
				and t.recordstatus in (".getUserRecordStatus('listda').")
        and t.useraccessid in (".getUserObjectValues('useraccess').")
        and	t.deliveryadviceid in (select dad.deliveryadviceid
				from deliveryadvicedetail dad
				where qty > prqty and qty-giqty > 0)", array(
        ':slocid' => '%' . $slocid . '%',
        ':dano' => '%' . $dano . '%'
      ))->queryScalar();
    } else if (isset($_GET['tsda'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('deliveryadvice t')->where("((t.dano like :dano) or (t.sloccode like :slocid) or (t.slocdesc like :slocid))
				and t.recordstatus = {$maxstat} 
				and t.recordstatus in (".getUserRecordStatus('listda').")
        and t.useraccessid in (".getUserObjectValues('useraccess').")
        and t.deliveryadviceid in (select dad.deliveryadviceid
        from deliveryadvicedetail dad
        where qty-giqty > 0)", array(
        ':slocid' => '%' . $slocid . '%',
        ':dano' => '%' . $dano . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('deliveryadvice t')->where("(
								(coalesce(t.deliveryadviceid,'') like :deliveryadviceid) and
				(coalesce(t.dadate,'') like :dadate) and
				(coalesce(t.dano,'') like :dano) and
				(coalesce(t.sono,'') like :soheaderid) and				
				(coalesce(t.username,'') like :useraccessid) and
				(coalesce(t.sloccode,'') like :slocid) and				
				(coalesce(t.headernote,'') like :headernote)) and 
				t.soheaderid is not null and 
				t.recordstatus > 0 and 
				t.recordstatus < {$maxstat} and
				t.recordstatus in (".getUserRecordStatus('listda').")
        and t.useraccessid in (".getUserObjectValues('useraccess').")", array(
        ':deliveryadviceid' => '%' . $deliveryadviceid . '%',
        ':dadate' => '%' . $dadate . '%',
        ':dano' => '%' . $dano . '%',
        ':soheaderid' => '%' . $soheaderid . '%',
        ':useraccessid' => '%' . $useraccessid . '%',
        ':slocid' => '%' . $slocid . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('deliveryadvice t')->where('
				(t.deliveryadviceid like :deliveryadviceid) or
				(t.dadate like :dadate) or
				(t.dano like :dano) or
				(t.sono like :soheaderid) or			
				(t.username like :useraccessid) or
				(t.sloccode like :slocid) or				
				(t.headernote like :headernote) and t.soheaderid > 0', array(
        ':deliveryadviceid' => '%' . $deliveryadviceid . '%',
        ':dadate' => '%' . $dadate . '%',
        ':dano' => '%' . $dano . '%',
        ':soheaderid' => '%' . $soheaderid . '%',
        ':useraccessid' => '%' . $useraccessid . '%',
        ':slocid' => '%' . $slocid . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['dapr'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('deliveryadvice t')->where("((t.dano like :dano) or (t.sloccode like :slocid) or (t.slocdesc like :slocid)) 
				and t.recordstatus in (".getUserRecordStatus('listda').")
        and t.useraccessid in (".getUserObjectValues('useraccess').")
        and t.deliveryadviceid in (select dad.deliveryadviceid
        from deliveryadvicedetail dad
        where qty > prqty and qty-giqty > 0)", array(
        ':slocid' => '%' . $slocid . '%',
        ':dano' => '%' . $dano . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['tsda'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('deliveryadvice t')->where("((t.dano like :dano) or (t.sloccode like :slocid) or (t.slocdesc like :slocid)) 
				and t.recordstatus = {$maxstat}
				and t.recordstatus in (".getUserRecordStatus('listda').")
        and t.useraccessid in (".getUserObjectValues('useracess').")
        and t.deliveryadviceid in (select dad.deliveryadviceid
        from deliveryadvicedetail dad
        where qty-giqty > 0)", array(
        ':slocid' => '%' . $slocid . '%',
        ':dano' => '%' . $dano . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('deliveryadvice t')->where("(
				(coalesce(t.deliveryadviceid,'') like :deliveryadviceid) and
				(coalesce(t.dadate,'') like :dadate) and
				(coalesce(t.dano,'') like :dano) and
				(coalesce(t.sono,'') like :soheaderid) and				
				(coalesce(t.username,'') like :useraccessid) and
				(coalesce(t.sloccode,'') like :slocid) and				
				(coalesce(t.headernote,'') like :headernote)) and 
				t.soheaderid is not null and
				t.recordstatus > 0 and 
				t.recordstatus < {$maxstat} and
				t.recordstatus in (".getUserRecordStatus('listda').")
				and t.useraccessid in (".getUserObjectValues('useraccess').")", array(
        ':deliveryadviceid' => '%' . $deliveryadviceid . '%',
        ':dadate' => '%' . $dadate . '%',
        ':dano' => '%' . $dano . '%',
        ':soheaderid' => '%' . $soheaderid . '%',
        ':useraccessid' => '%' . $useraccessid . '%',
        ':slocid' => '%' . $slocid . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'deliveryadviceid' => $data['deliveryadviceid'],
        'dadate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['dadate'])),
        'dano' => $data['dano'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'headernote' => $data['headernote'],
        'useraccessid' => $data['useraccessid'],
        'username' => $data['username'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'] . ' - ' . $data['slocdesc'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusda' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.deliveryadvicedetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('deliveryadvicedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('deliveryadviceid = :deliveryadviceid', array(
      ':deliveryadviceid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.requestedbycode,d.sloccode,d.description,getstock(t.productid,t.unitofmeasureid,t.slocid) as stock')->from('deliveryadvicedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('deliveryadviceid = :deliveryadviceid', array(
      ':deliveryadviceid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'deliveryadvicedetailid' => $data['deliveryadvicedetailid'],
        'deliveryadviceid' => $data['deliveryadviceid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'requestedbyid' => $data['requestedbyid'],
        'requestedbycode' => $data['requestedbycode'],
        'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'slocid' => $data['slocid'],
        'tosloccode' => $data['sloccode'] . ' - ' . $data['description'],
        'itemtext' => $data['itemtext'],
        'prqty' => Yii::app()->format->formatNumber($data['prqty']),
        'giqty' => Yii::app()->format->formatNumber($data['giqty']),
        'grqty' => Yii::app()->format->formatNumber($data['grqty']),
        'poqty' => Yii::app()->format->formatNumber($data['poqty']),
        'stock' => Yii::app()->format->formatNumber($data['stock']),
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
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
        $sql     = 'call InsertDA(:vdadate,:vheadernote,:vslocid,:vproductplanid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateDASO(:vid,:vdadate,:vheadernote,:vslocid,:vsoheaderid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['deliveryadviceid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['deliveryadviceid']);
      }
      $command->bindvalue(':vdadate', date(Yii::app()->params['datetodb'], strtotime($_POST['dadate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vsoheaderid', $_POST['soheaderid'], PDO::PARAM_STR);
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
  public function actionSavedetail()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertdadetail(:vdeliveryadviceid,:vproductid,:vqty,:vunitofmeasureid,:vrequestedbyid,:vreqdate,:vitemtext,:vslocid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatedadetail(:vid,:vdeliveryadviceid,:vproductid,:vqty,:vunitofmeasureid,:vrequestedbyid,:vreqdate,:vitemtext,:vslocid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['deliveryadvicedetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['deliveryadvicedetailid']);
      }
      $command->bindvalue(':vdeliveryadviceid', $_POST['deliveryadviceid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vunitofmeasureid', $_POST['unitofmeasureid'], PDO::PARAM_STR);
      $command->bindvalue(':vrequestedbyid', $_POST['requestedbyid'], PDO::PARAM_STR);
      $command->bindvalue(':vreqdate', date(Yii::app()->params['datetodb'], strtotime($_POST['reqdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vitemtext', $_POST['itemtext'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
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
        $sql     = 'call Purgedeliveryadvice(:vid,:vcreatedby)';
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
        $sql     = 'call Purgedeliveryadvicedetail(:vid,:vcreatedby)';
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
  public function actiongetdata()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into deliveryadvice (useraccessid,dadate,recordstatus) values (".getUserID().",'".$dadate->format('Y-m-d')."',".findstatusbyuser('insda').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'deliveryadviceid' => $id
			));
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
        $sql     = 'call DeleteDA(:vid,:vcreatedby)';
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
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveDA(:vid,:vcreatedby)';
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
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select getcompanysloc(a.slocid) as companyid,a.dano,a.dadate,a.headernote,
								a.deliveryadviceid,b.sloccode,b.description,a.recordstatus,c.productplanno,d.sono,e.productoutputno
								from deliveryadvice a 
								left join productplan c on c.productplanid = a.productplanid 
								left join soheader d on d.soheaderid = a.soheaderid 
								left join productoutput e on e.productoutputid = a.productoutputid
								left join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.deliveryadviceid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('deliveryadvicesalesorder');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(10);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . $row['dano']);
      $this->pdf->text(50, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])));
      $this->pdf->text(90, $this->pdf->gety(), 'SO ');
      $this->pdf->text(100, $this->pdf->gety(), ': ' . $row['sono']);
      $this->pdf->text(130, $this->pdf->gety(), 'Gudang ');
      $this->pdf->text(140, $this->pdf->gety(), ': ' . $row['sloccode'] . ' - ' . $row['description']);
      $sql1        = "select b.productname, sum(a.qty) as qty, c.uomcode, a.itemtext,e.sloccode
										from deliveryadvicedetail a
										left join product b on b.productid = a.productid
										left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
										left join sloc e on e.slocid = a.slocid
										where deliveryadviceid = " . $row['deliveryadviceid'] . " group by b.productname,c.uomcode,e.sloccode ";
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
        90,
        20,
        10,
        60,
        15
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gd Tujuan',
        'Remark'
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
          $row1['sloccode'],
          $row1['itemtext']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
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
      $this->pdf->text(120, $this->pdf->gety(), 'Mengetahui Peminta');
      $this->pdf->text(170, $this->pdf->gety(), 'Peminta Barang');
      $this->pdf->text(10, $this->pdf->gety() + 12, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 12, '........................');
      $this->pdf->text(120, $this->pdf->gety() + 12, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 12, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'deliveryadvicesalesorder';
    parent::actionDownxls();
    $sql = "select getcompanysloc(a.slocid) as companyid,a.dano,a.dadate,a.headernote,
								a.deliveryadviceid,b.sloccode,b.description,a.recordstatus,c.productplanno,d.sono,e.productoutputno
								from deliveryadvice a 
								left join productplan c on c.productplanid = a.productplanid 
								left join soheader d on d.soheaderid = a.soheaderid 
								left join productoutput e on e.productoutputid = a.productoutputid
								left join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.deliveryadviceid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['dano'])->setCellValueByColumnAndRow(4, $line, 'SO')->setCellValueByColumnAndRow(5, $line, ': ' . $row['sono']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Tgl')->setCellValueByColumnAndRow(1, $line, ': ' . $row['dadate'])->setCellValueByColumnAndRow(4, $line, 'Gudang')->setCellValueByColumnAndRow(5, $line, ': ' . $row['sloccode'] . ' - ' . $row['description']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Items')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Gd Tujuan')->setCellValueByColumnAndRow(5, $line, 'Remark');
      $line++;
      $sql1        = "select b.productname, sum(a.qty) as qty, c.uomcode, a.itemtext,concat(e.sloccode,' - ',e.description) as sloccode
									from deliveryadvicedetail a
									left join product b on b.productid = a.productid
									left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
									left join sloc e on e.slocid = a.slocid
									where deliveryadviceid = " . $row['deliveryadviceid'] . " group by b.productname,c.uomcode,e.sloccode ";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['qty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['sloccode'])->setCellValueByColumnAndRow(5, $line, $row1['itemtext']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Note : ')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Penerima')->setCellValueByColumnAndRow(1, $line, 'Mengetahui')->setCellValueByColumnAndRow(3, $line, 'Mengetahui Peminta')->setCellValueByColumnAndRow(4, $line, 'Peminta Barang');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }

}
