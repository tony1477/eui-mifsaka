<?php
class DeliveryadviceController extends Controller {
  public $menuname = 'deliveryadvice';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdapr() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchdapr();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndextsda() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchtsda();
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
  public function search() {
    header("Content-Type: application/json");
    $deliveryadviceid = isset($_POST['deliveryadviceid']) ? $_POST['deliveryadviceid'] : '';
    $dadate           = isset($_POST['dadate']) ? $_POST['dadate'] : '';
    $dano             = isset($_POST['dano']) ? $_POST['dano'] : '';
    $slocid           = isset($_POST['slocid']) ? $_POST['slocid'] : '';
    $useraccessid           = isset($_POST['useraccessid']) ? $_POST['useraccessid'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $recordstatus     = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'deliveryadviceid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appda')")->queryScalar();
		$connection				= Yii::app()->db;
		$from = '
			from deliveryadvice t 
			left join sloc a on a.slocid = t.slocid 
			left join plant b on b.plantid = a.plantid 
			left join company c on c.companyid = b.companyid ';
		$where = "
			where (t.deliveryadviceid like '%".$deliveryadviceid."%') and
				(coalesce(t.dano,'') like '%".$dano."%') and			
				(coalesce(t.sloccode,'') like  '%".$slocid."%') and								
				(coalesce(t.username,'') like  '%".$useraccessid."%') and								
				(coalesce(t.dadate,'') like  '%".$dadate."%') and								
				(coalesce(t.headernote,'') like '%".$headernote."%') and
				t.productplanid is null and				
				t.soheaderid is null and				
				t.productoutputid is null and 
				t.recordstatus > 0 and 
				t.recordstatus < {$maxstat} and
				t.recordstatus in (".getUserRecordStatus('listda').")
        and t.useraccessid in (".getUserObjectValues('useraccess').")";
		//and t.deliveryadviceid in (select distinct k.deliveryadviceid from deliveryadvicedetail k where k.slocid in (".getUserObjectValues('sloc')."))
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.deliveryadviceid,t.dadate,t.dano,t.headernote,t.useraccessid,t.username,t.slocid,t.sloccode,t.statusname,
		t.recordstatus, a.description as slocdesc, c.companyid,c.companyname, (
		select case when sum(z.qty) > sum(z.giqty) then 1 else 0 end 
		from deliveryadvicedetail z 
		where z.deliveryadviceid = t.deliveryadviceid 		
		) as warna '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'deliveryadviceid' => $data['deliveryadviceid'],
        'dadate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['dadate'])),
        'dano' => $data['dano'],
        'headernote' => $data['headernote'],
        'useraccessid' => $data['useraccessid'],
        'username' => $data['username'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'warna' => $data['warna'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusda' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchdapr()
  {
    header("Content-Type: application/json");
    $deliveryadviceid = isset($_GET['q']) ? $_GET['q'] : '';
    $dano             = isset($_GET['q']) ? $_GET['q'] : '';
    $page             = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows             = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $sort             = isset($_GET['sort']) ? strval($_GET['sort']) : 't.deliveryadviceid';
    $order            = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
		$connection				= Yii::app()->db;
		$from = '
			from deliveryadvice t ';
		$where = "
			where ((coalesce(t.dano,'') like '%".$dano."%') or (t.deliveryadviceid like '%".$deliveryadviceid."%')) 
				and t.recordstatus = getwfmaxstatbywfname('appda')
				and t.recordstatus in (".getUserRecordStatus('listda').")
				and
				t.slocid in (".getUserObjectValues('sloc').") and
t.deliveryadviceid in (select dad.deliveryadviceid
from deliveryadvicedetail dad
where qty-giqty > 0)"; 
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.deliveryadviceid,t.dadate,t.dano,t.sloccode,t.productplanno,t.headernote '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'deliveryadviceid' => $data['deliveryadviceid'],
        'dadate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['dadate'])),
        'dano' => $data['dano'],
        'sloccode' => $data['sloccode'],
        'productplanno' => $data['productplanno'],
        'headernote' => $data['headernote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchtsda()
  {
    header("Content-Type: application/json");
    $deliveryadviceid = isset($_GET['q']) ? $_GET['q'] : '';
    $dano             = isset($_GET['q']) ? $_GET['q'] : '';
    $sloccode           = isset($_GET['q']) ? $_GET['q'] : '';
    $headernote       = isset($_GET['q']) ? $_GET['q'] : '';
    $page             = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows             = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $sort             = isset($_GET['sort']) ? strval($_GET['sort']) : 't.deliveryadviceid';
    $order            = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
		$connection				= Yii::app()->db;
		$from = '
			from deliveryadvice t ';
		$where = "
			where ((coalesce(t.dano,'') like '%".$dano."%') or (t.sloccode like '%".$sloccode."%') 
				or (t.headernote like '%".$headernote."%')) 
				and t.recordstatus = getwfmaxstatbywfname('appda')
				and t.recordstatus in (".getUserRecordStatus('listda').")
        and t.slocid in (".getUserObjectWfValues('sloc','appts').")
				and t.deliveryadviceid in (select dad.deliveryadviceid
				from deliveryadvicedetail dad
				where qty-giqty > 0)";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.deliveryadviceid,t.dadate,t.dano,t.productplanno,t.sloccode,t.headernote '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'deliveryadviceid' => $data['deliveryadviceid'],
        'dadate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['dadate'])),
        'dano' => $data['dano'],
        'sloccode' => $data['sloccode'],
        'productplanno' => $data['productplanno'],
        'headernote' => $data['headernote']
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
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.requestedbycode,d.sloccode,d.description,getstock(t.productid,t.unitofmeasureid,t.slocid) as stock')
    	->from('deliveryadvicedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('deliveryadviceid = :deliveryadviceid', array(
      ':deliveryadviceid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qty'] > $data['giqty']) {
				$wtfs = 1;
			} else {
				$wtfs = 0;
			}
			if ($data['qty']-$data['giqty'] > $data['stock']) {
				$wstock = 1;
			} else {
				$wstock = 0;
			}
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
        'recordstatus' => $data['recordstatus'],
				'wtfs' => $wtfs,
				'wstock' => $wstock
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
        $sql     = 'call UpdateDA(:vid,:vdadate,:vheadernote,:vslocid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['deliveryadviceid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['deliveryadviceid']);
      }
      $command->bindvalue(':vdadate', date(Yii::app()->params['datetodb'], strtotime($_POST['dadate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
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
			$sql = "insert into deliveryadvice (useraccessid,dadate,recordstatus) values (".GetUserID().",'".$dadate->format('Y-m-d')."',".findstatusbyuser('insda').")";
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
  public function actionDownPDF() {
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
    $this->pdf->title = getCatalog('deliveryadvice');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(10);
      $this->pdf->text(10, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])));
      $this->pdf->text(50, $this->pdf->gety(), 'No ');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . $row['dano']);
      $this->pdf->text(90, $this->pdf->gety(), 'SO ');
      $this->pdf->text(100, $this->pdf->gety(), ': ' . $row['sono']);
      $this->pdf->text(130, $this->pdf->gety(), 'SPP ');
      $this->pdf->text(140, $this->pdf->gety(), ': ' . $row['productplanno']);
      $this->pdf->text(170, $this->pdf->gety(), 'OP ');
      $this->pdf->text(180, $this->pdf->gety(), ': ' . $row['productoutputno']);
      $this->pdf->text(10, $this->pdf->gety()+4, 'Gudang ');
      $this->pdf->text(40, $this->pdf->gety()+4, ': ' . $row['sloccode'] . ' - ' . $row['description']);
      $sql1        = "select b.productname, sum(a.qty) as qty, c.uomcode, a.itemtext,e.sloccode
              from deliveryadvicedetail a
              left join product b on b.productid = a.productid
              left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
              left join sloc e on e.slocid = a.slocid
              where deliveryadviceid = " . $row['deliveryadviceid'] . " group by b.productname,c.uomcode,e.sloccode ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 6);
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
        100,
        20,
        10,
        30,
        25
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
          Yii::app()->format->formatCurrency($row1['qty']),
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
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'deliveryadvice';
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
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['dano']);
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
								where deliveryadviceid = " . $row['deliveryadviceid'] . " 
								group by b.productname,c.uomcode,e.sloccode ";
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
	public function actionDownPDF1()
  {
    parent::actionDownload();
    $sql = "select a.deliveryadviceid, a.dadate,a.dano,a.username, a.sloccode,a.statusname
						from deliveryadvice a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.deliveryadviceid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Product Yang Tidak ada di Gudang Sumber";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['deliveryadviceid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO FPB ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['dano']);
     
     
      $sql1= "select b.deliveryadviceid, a.productid, b.dadate,b.dano,b.username, b.sloccode,b.statusname,c.productname
							from deliveryadvicedetail a
							join deliveryadvice b on b.deliveryadviceid = a.deliveryadviceid
							join product c ON c.productid = a.productid 
							where b.deliveryadviceid = ".$row['deliveryadviceid']." and b.slocid not in (select x.slocid from productplant x where x.productid = a.productid) ";
        
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
          20,
          25,
          25,
          80,
          25
        ));
        $this->pdf->colheader = array(
          'No',
          'ID',
          'Tanggal FPB',
          'Gudang',
          'Nama Barang',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'L',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['deliveryadviceid'], $row1['dadate'],$row1['sloccode'],$row1['productname'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }
	public function actionDownPDF2()
  {
    parent::actionDownload();
    $sql = "select a.deliveryadviceid, a.dadate,a.dano,a.username, a.sloccode,a.statusname
						from deliveryadvice a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.deliveryadviceid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Product Yang Tidak ada di Gudang Tujuan";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['deliveryadviceid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO FPB ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['dano']);
     
     
      $sql1= "select b.deliveryadviceid, a.productid, b.dadate,b.dano,b.username, b.sloccode,b.statusname,c.productname
							from deliveryadvicedetail a
							join deliveryadvice b on b.deliveryadviceid = a.deliveryadviceid
							join product c ON c.productid = a.productid 
							where b.deliveryadviceid = ".$row['deliveryadviceid']." and a.slocid not in (select x.slocid from productplant x where x.productid = a.productid) ";
        
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
          20,
          25,
          25,
          80,
          25
        ));
        $this->pdf->colheader = array(
          'No',
          'ID',
          'Tanggal FPB',
          'Gudang',
          'Nama Barang',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'L',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['deliveryadviceid'], $row1['dadate'],$row1['sloccode'],$row1['productname'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  } 
	public function actionDownPDF3()
  {
    parent::actionDownload();
    $sql = "select a.deliveryadviceid, a.dadate,a.dano,a.username, a.sloccode,a.statusname
from deliveryadvice a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.deliveryadviceid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Satuan Yang Tidak ada di Gudang Sumber";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['deliveryadviceid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO FPB ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['dano']);
     
     
      $sql1= "select b.deliveryadviceid, a.productid, b.dadate,b.dano,b.username, b.sloccode,b.statusname,c.productname,d.uomcode
							from deliveryadvicedetail a
							join deliveryadvice b on b.deliveryadviceid = a.deliveryadviceid
							join product c ON c.productid = a.productid 
              join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
							where b.deliveryadviceid = ".$row['deliveryadviceid']." and b.slocid not in (select x.slocid from productplant x where x.productid = a.productid and x.unitofissue = a.unitofmeasureid) ";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->colalign = array(
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
          30,
          30,
          30,
          70,
          30
          
        ));
        $this->pdf->colheader = array(
          'No',
          'Tgl FPB',
          'Gudang',
          'Satuan',
          'Nama Barang',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['dadate'],$row1['sloccode'],$row1['uomcode'],$row1['productname'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }
	public function actionDownPDF4()
  {
    parent::actionDownload();
    $sql = "select a.deliveryadviceid, a.dadate,a.dano,a.username, a.sloccode,a.statusname
from deliveryadvice a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.deliveryadviceid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Satuan Yang Tidak ada di Gudang Tujuan";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['deliveryadviceid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO FPB ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['dano']);
     
     
      $sql1= "select b.deliveryadviceid, a.productid, b.dadate,b.dano,b.username, b.sloccode,b.statusname,c.productname,d.uomcode
							from deliveryadvicedetail a
							join deliveryadvice b on b.deliveryadviceid = a.deliveryadviceid
							join product c ON c.productid = a.productid 
              join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
							where b.deliveryadviceid = ".$row['deliveryadviceid']." and a.slocid not in (select x.slocid from productplant x where x.productid = a.productid and x.unitofissue = a.unitofmeasureid)";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->colalign = array(
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
          30,
          30,
          30,
          70,
          30
          
        ));
        $this->pdf->colheader = array(
          'No',
          'Tgl FPB',
          'Gudang',
          'Satuan',
          'Nama Barang',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['dadate'],$row1['sloccode'],$row1['uomcode'],$row1['productname'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownPDF5()
  {
    parent::actionDownload();
    $sql = "select a.deliveryadviceid, a.dadate,a.dano,a.username, a.sloccode,a.statusname
from deliveryadvice a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.deliveryadviceid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Material Yang Gudang Sumber Belum Di Centang";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['deliveryadviceid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO FPB ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['dano']);
     
     
      $sql1= "select a.deliveryadviceid, b.productid, a.dadate, a.slocid, b.slocid, a.sloccode,a.statusname, c.productname,d.uomcode
from deliveryadvice a
join deliveryadvicedetail b on a.deliveryadviceid = b.deliveryadviceid
join product c ON c.productid = b.productid 
join unitofmeasure d on d.unitofmeasureid= b.unitofmeasureid
WHERE a.deliveryadviceid=".$row['deliveryadviceid']." and b.productid in 
(select z.productid from productplant z where z.productid = b.productid and z.slocid = a.slocid and z.issource=0 and z.recordstatus=1)";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->colalign = array(
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
          70,
          30,
          30,
          30,
          30
          
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Tgl FPB',
          'Gudang',
          'Satuan',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'L',
          'C',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],$row1['dadate'],$row1['sloccode'],$row1['uomcode'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }    
  	
}
