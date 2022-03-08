<?php
class TtfController extends Controller
{
  public $menuname = 'ttf';
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
  public function search()
  {
    header("Content-Type: application/json");
    $ttfid        = isset($_POST['ttfid']) ? $_POST['ttfid'] : '';
    $companyid     = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $ttntno       = isset($_POST['ttntno']) ? $_POST['ttntno'] : '';
    $docno         = isset($_POST['docno']) ? $_POST['docno'] : '';
    $employeeid    = isset($_POST['employeeid']) ? $_POST['employeeid'] : '';
    $description   = isset($_POST['description']) ? $_POST['description'] : '';
    $ttfid        = isset($_GET['q']) ? $_GET['q'] : $ttfid;
    $companyid     = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $ttntno       = isset($_GET['q']) ? $_GET['q'] : $ttntno;
    $docno         = isset($_GET['q']) ? $_GET['q'] : $docno;
    $employeeid    = isset($_GET['q']) ? $_GET['q'] : $employeeid;
    $description   = isset($_GET['q']) ? $_GET['q'] : $description;
    $page          = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows          = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort          = isset($_POST['sort']) ? strval($_POST['sort']) : 'ttfid';
    $order         = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset        = ($page - 1) * $rows;
    $page          = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows          = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort          = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order         = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset        = ($page - 1) * $rows;
    $result        = array();
    $row           = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appttf')")->queryScalar();
    if  (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
        ->from('ttf t')
        ->leftjoin('ttnt a', 'a.ttntid = t.ttntid')
        ->leftjoin('employee b', 'b.employeeid = t.employeeid')
        ->leftjoin('company c', 'c.companyid = t.companyid')
        ->where("
      	 (coalesce(a.docno,'') like :ttntno) and 
          (coalesce(t.docno,'') like :docno) and 
          (coalesce(b.fullname,'') like :employeeid) and 
          (coalesce(t.description,'') like :description) and 
          (coalesce(c.companyname,'') like :companyid)
					and t.recordstatus < {$maxstat}
					and t.recordstatus in (".getUserRecordStatus('listttf').")
					and	c.companyid in (".getUserObjectValues('company').")", array(
        ':ttntno' => '%' . $ttntno . '%',
        ':docno' => '%' . $docno . '%',
        ':employeeid' => '%' . $employeeid . '%',
        ':description' => '%' . $description . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->queryRow();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
        ->from('ttf t')
        ->leftjoin('ttnt a', 'a.ttntid = t.ttntid')
        ->leftjoin('employee b', 'b.employeeid = t.employeeid')
        ->leftjoin('company c', 'c.companyid = t.companyid')
        ->where("
      (coalesce(a.docno,'') like :ttntno) and 
          (coalesce(t.docno,'') like :docno) and 
          (coalesce(b.fullname,'') like :employeeid) and 
          (coalesce(t.description,'') like :description) and 
          (coalesce(c.companyname,'') like :companyid) and t.recordstatus in (".getUserRecordStatus('listttf').") 
				and c.companyid in (".getUserObjectValues('company').")", array(
        ':ttntno' => '%' . $ttntno . '%',
        ':docno' => '%' . $docno . '%',
        ':employeeid' => '%' . $employeeid . '%',
        ':description' => '%' . $description . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->queryRow();
    }
    $result['total'] = $cmd['total'];
    if  (!isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,c.companyname,b.fullname as employeename,a.docno as ttntno')
        ->from('ttf t')
        ->leftjoin('ttnt a', 'a.ttntid = t.ttntid')
        ->leftjoin('employee b', 'b.employeeid = t.employeeid')
        ->leftjoin('company c', 'c.companyid = t.companyid')
        ->where("
       (coalesce(a.docno,'') like :ttntno) and 
          (coalesce(t.docno,'') like :docno) and 
          (coalesce(b.fullname,'') like :employeeid) and 
          (coalesce(t.description,'') like :description) and 
          (coalesce(c.companyname,'') like :companyid)
					and t.recordstatus < {$maxstat}
					and t.recordstatus in (".getUserRecordStatus('listttf').")
					and	c.companyid in (".getUserObjectValues('company').")", array(
        ':ttntno' => '%' . $ttntno . '%',
        ':docno' => '%' . $docno . '%',
        ':employeeid' => '%' . $employeeid . '%',
        ':description' => '%' . $description . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,b.fullname as employeename,a.docno as ttntno')
        ->from('ttf t')
        ->leftjoin('ttnt a', 'a.ttntid = t.ttntid')
        ->leftjoin('employee b', 'b.employeeid = t.employeeid')
        ->leftjoin('company c', 'c.companyid = t.companyid')->where("
      (coalesce(a.docno,'') like :ttntno) and 
          (coalesce(t.docno,'') like :docno) and 
          (coalesce(b.fullname,'') like :employeeid) and 
          (coalesce(t.description,'') like :description) and 
          (coalesce(c.companyname,'') like :companyid) and t.recordstatus in (select b.wfbefstat
				from workflow a
				inner join wfgroup b on b.workflowid = a.workflowid
				inner join usergroup d on d.groupaccessid = b.groupaccessid
				inner join useraccess e on e.useraccessid = d.useraccessid
				where a.wfname = 'listttf' and e.username = '" . Yii::app()->user->name . "') 
				and c.companyid in (select f.menuvalueid
						from workflow a
						inner join wfgroup b on b.workflowid = a.workflowid
						inner join groupaccess c on c.groupaccessid = b.groupaccessid
						inner join usergroup d on d.groupaccessid = c.groupaccessid
						inner join useraccess e on e.useraccessid = d.useraccessid
						inner join groupmenuauth f on f.groupaccessid=c.groupaccessid
						inner join menuauth g on g.menuauthid = f.menuauthid
						where e.username = '" . Yii::app()->user->name . "' and
						g.menuobject = 'company')", array(
        ':ttntno' => '%' . $ttntno . '%',
        ':docno' => '%' . $docno . '%',
        ':employeeid' => '%' . $employeeid . '%',
        ':description' => '%' . $description . '%',
        ':companyid' => '%' . $companyid . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'ttfid' => $data['ttfid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'docno' => $data['docno'],
        'ttntno' => $data['ttntno'],
        'ttntid' => $data['ttntid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'employeeid' => $data['employeeid'],
        'employeename' => $data['employeename'],
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusttf' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdetail()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.ttfdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array(   );
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('ttfdetail t')->leftjoin('invoice b', 'b.invoiceid = t.invoiceid')->leftjoin('giheader c', 'c.giheaderid = b.giheaderid')->leftjoin('soheader d', 'd.soheaderid = c.soheaderid')->leftjoin('addressbook e', 'e.addressbookid = d.addressbookid')->where('ttfid = :ttfid', array(
      ':ttfid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,b.invoicedate,c.gino,d.sono,e.fullname,b.invoiceno,t.amount,t.payamount,
					adddate(b.invoicedate,f.paydays) as jatuhtempo')->from('ttfdetail t')->leftjoin('invoice b', 'b.invoiceid = t.invoiceid')->leftjoin('giheader c', 'c.giheaderid = b.giheaderid')->leftjoin('soheader d', 'd.soheaderid = c.soheaderid')->leftjoin('addressbook e', 'e.addressbookid = d.addressbookid')->leftjoin('paymentmethod f', 'f.paymentmethodid = d.paymentmethodid')->where('ttfid = :ttfid', array(
      ':ttfid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ttfdetailid' => $data['ttfdetailid'],
        'ttfid' => $data['ttfid'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'jatuhtempo' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['jatuhtempo'])),
        'sono' => $data['sono'],
        'fullname' => $data['fullname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'payamount' => Yii::app()->format->formatNumber($data['payamount'])
      );
    }
    $cmd      = Yii::app()->db->createCommand()->select('sum(b.amount) as total')->from('ttfdetail t')->leftjoin('invoice b', 'b.invoiceid = t.invoiceid')->where('ttfid = :ttfid', array(
      ':ttfid' => $id
    ))->queryRow();
    $footer[] = array(
      'gino' => 'Total',
      'amount' => Yii::app()->format->formatNumber($cmd['total'])
    );
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionGenerateinv()
  {
    if (isset($_POST['id'])) {
      if ($_POST['id'] !== '') {
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {
          $sql     = 'call GenerateTTFINV(:vid, :vhid)';
          $command = $connection->createCommand($sql);
          $command->bindvalue(':vid', $_POST['invoiceid'], PDO::PARAM_INT);
          $command->bindvalue(':vhid', $_POST['ttfdetailid'], PDO::PARAM_INT);
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
        $sql     = 'call Insertttf(:vttfid,:vttntid,:vcompanyid,:vdocdate,:vemployeeid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatettf(:vttfid,:vttntid,:vcompanyid,:vdocdate,:vemployeeid,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vttfid', $_POST['ttfid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['ttfid']);
      }
      $command->bindvalue(':vttntid', $_POST['ttntid'], PDO::PARAM_STR);
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vemployeeid', $_POST['employeeid'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
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
  public function actionGetInvoice()
	{
    if (isset($_POST['ttntdetailid'])) {
      header("Content-Type: application/json");
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'select invoiceid from ttntdetail where ttntdetailid = :vttntdetailid';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vttntdetailid', $_POST['ttntdetailid'], PDO::PARAM_INT);
        $result = $command->queryScalar();
      }
      catch (Exception $e) {
        GetMessage(false, $e->getMessage(), 1);
      }
        $row['invoiceid'] = $result;
        echo CJSON::encode($row);
    }
  }
  public function actionSaveDetail()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertttfdetail(:vttfid,:vinvoiceid,:vttntdetailid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatettfdetail(:vid,:vttfid,:vinvoiceid,:vttntdetailid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['ttfdetailid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['ttfdetailid']);
      }
      $command->bindvalue(':vttfid', $_POST['ttfid'], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceid', $_POST['invoiceid'], PDO::PARAM_STR);
      $command->bindvalue(':vttntdetailid', $_POST['ttntdetailid'], PDO::PARAM_STR);
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
        $sql     = 'call Purgettf(:vid,:vcreatedby)';
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
  public function actionPurgeDetail()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgettfdetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql); {
          $command->bindvalue(':vid', $id, PDO::PARAM_STR);
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
  public function actiongetdata()
  {
    if (isset($_GET['id'])) {
    } else {      
			$dadate              = new DateTime('now');
			$sql = "insert into ttf (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insttf').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'ttfid' => $id
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
        $sql     = 'call Deletettf(:vid,:vcreatedby)';
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
        $sql     = 'call Approvettf(:vid,:vcreatedby)';
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
    ob_start();
    $sql = "select a.ttfid,a.docno as ttfdocno,d.docno as ttntdocno,b.fullname as employeename,a.docdate,c.companyname,c.bankacc2,c.bankacc1,c.bankacc3,a.companyid
				from ttf a 
				inner join employee b on b.employeeid = a.employeeid 
				inner join ttnt d on d.ttntid = a.ttntid 
				inner join company c on c.companyid = a.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.ttfid in (" . $_GET['id'] . ")";
    }
    $command             = $this->connection->createCommand($sql);
    $dataReader          = $command->queryAll();
		foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $row['companyid'];
    }
    
    $count = Yii::app()->db->createCommand("select count(1) from ttfdetail where ttfid = ".$_REQUEST['id'])->queryScalar();
            
            if($count>7){
                $this->pdf->AddPage('P',array(198,280));          
            }else{
                $this->pdf->AddPage('P',array(198,140));
            }
		//		var_dump($this->sqldata);
	  $this->pdf->isheader=false;
	  

		foreach($dataReader as $row)
    {
			$i=0;$total2=0;
			$this->pdf->SetFont('Arial','B',18);
			$this->pdf->text(70,$this->pdf->gety(),'Tanda Terima Faktur');
			$this->pdf->SetFont('Arial','B',9);
            $this->pdf->text(10,$this->pdf->gety()+5,''.$row['companyname']);
            $this->pdf->text(140,$this->pdf->gety()+5,'No. ');$this->pdf->text(153,$this->pdf->gety()+5,': '.$row['ttfdocno']);
            $this->pdf->text(140,$this->pdf->gety()+10,'Sales ');$this->pdf->text(153,$this->pdf->gety()+10,': '.$row['employeename']);
            $this->pdf->text(10,$this->pdf->gety()+10,'No. Ref. ');$this->pdf->text(25,$this->pdf->gety()+10,': '.$row['ttntdocno']);
      $this->pdf->text(80,$this->pdf->gety()+10,'Tanggal');$this->pdf->text(95,$this->pdf->gety()+10  ,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
     

			$sql1 = "select distinct e.addressbookid,e.fullname
					from ttfdetail a
					join invoice b on b.invoiceid = a.invoiceid
					join giheader c on c.giheaderid = b.giheaderid
					join soheader d on d.soheaderid = c.soheaderid
					join paymentmethod f on f.paymentmethodid = d.paymentmethodid
					join addressbook e on e.addressbookid = d.addressbookid
					where a.ttfid = ".$row['ttfid']." order by b.invoicedate ";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			
			$this->pdf->sety($this->pdf->gety()+13);
			$this->pdf->setFont('Arial','B',7);
			$this->pdf->colalign = array('C','C','C','C','C','C');
			$this->pdf->setwidths(array(8,22,68,30,25,30));
			$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
			$this->pdf->colheader = array('No.','No. Inv','Nama Toko','Tgl Inv.', 'Tgl. JTT','Nilai Inv.');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','C','L','C','R');
			$this->pdf->setFont('Arial','',8);
			
			foreach($dataReader1 as $row1)
			{	
				$total = 0;
				$this->pdf->colalign = array('C','C','C','C','C','C');
				$this->pdf->setwidths(array(8,22,68,30,25,30));
				$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
				$this->pdf->coldetailalign = array('L','L','C','L','C','R');
				$this->pdf->setFont('Arial','',8);
				
				$sql2 = "select b.invoiceno,d.sono,e.fullname,b.invoicedate,adddate(b.invoicedate,f.paydays) as jatuhtempo, a.amount,
					b.amount-ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=getwfmaxstatbywfname('appcutar') and f.invoiceid=a.invoiceid and g.docdate <= h.docdate),0) as saldoinvoice
					from ttfdetail a
					join invoice b on b.invoiceid = a.invoiceid
					join giheader c on c.giheaderid = b.giheaderid
					join soheader d on d.soheaderid = c.soheaderid
					join paymentmethod f on f.paymentmethodid = d.paymentmethodid
					join addressbook e on e.addressbookid = d.addressbookid
					join ttf h on h.ttfid=a.ttfid
					where a.ttfid = ".$row['ttfid']." and e.addressbookid = ".$row1['addressbookid']." order by b.invoicedate ";
			$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();

				
				
				foreach($dataReader2 as $row2)
				{
					$i+=1;
					$this->pdf->row(array($i,$row2['invoiceno'],
						$row2['fullname'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])),
						date(Yii::app()->params['dateviewfromdb'], strtotime($row2['jatuhtempo'])),
						Yii::app()->format->formatNumber($row2['saldoinvoice'])
						));
					$total += $row2['saldoinvoice'];
				}
				$this->pdf->setwidths(array(153,30,20,20,20,20));
				$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
				$this->pdf->coldetailalign = array('R','R','R','R','R','R');
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->row(array('TOTAL '.$row1['fullname'].'  >>> ',
					//Yii::app()->format->formatNumber($total)));
				$total2 += $total;
			}
			$bilangan = explode(".",$total2);
			$this->pdf->setwidths(array(153,30,20,20,20,20));
			$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
			$this->pdf->coldetailalign = array('C','R','R','R','R','R');
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->row(array('GRAND TOTAL  >>> ',
				Yii::app()->format->formatNumber($total2),
				));

			$this->pdf->checkNewPage(15);
								 
			$this->pdf->setFont('Arial','',8);
			$this->pdf->sety($this->pdf->gety()+5);
            $this->pdf->setFont('Arial','I',8);
        
			$this->pdf->text(15,$this->pdf->gety(),'Terbilang: '. eja($bilangan[0]));
            $this->pdf->setFont('Arial','B',10);
            $this->pdf->text(15,$this->pdf->gety()+5,'Pembayaran Dilakukan Pada');
            $this->pdf->text(15,$this->pdf->gety()+10,'Metode Pembayaran');
            $this->pdf->setFont('Arial','',8);
            $this->pdf->text(80,$this->pdf->gety()+5,'Tanggal :');
            $this->pdf->text(80,$this->pdf->gety()+10,'Cash / Tunai');
            $this->pdf->rect(99,$this->pdf->gety()+7,4,4);
            
            $this->pdf->text(120,$this->pdf->gety()+10,'Transfer');
            $this->pdf->rect(134,$this->pdf->gety()+7,4,4);
            $this->pdf->text(150,$this->pdf->gety()+10,'Giro / Cek');
            $this->pdf->rect(165,$this->pdf->gety()+7,4,4);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->text(15, $this->pdf->gety()+15, 'Apabila Tansfer / Cek / Giro Ditujukan Ke:');
            $this->pdf->setFont('Arial','',9);
            $this->pdf->text(25, $this->pdf->gety()+20, '~ Rekening '.$row['bankacc1']);}
/*            if ($row['bankacc2'] !== null ){
            $this->pdf->text(25, $this->pdf->gety() + 24, '~ Rekening '.$row['bankacc2']);}
            if ($row['bankacc3'] !== null ){
            $this->pdf->text(25, $this->pdf->gety() + 28, '~ Rekening '.$row['bankacc3']);}
*/            
            $this->pdf->setFont('Arial','',8);
			
			$this->pdf->text(25,$this->pdf->gety()+27,'       Disiapkan oleh,');
			$this->pdf->text(88,$this->pdf->gety()+27,'     Diserahkan oleh,');
			$this->pdf->text(145,$this->pdf->gety()+27,'     Diterima oleh,');
			
			$this->pdf->text(25,$this->pdf->gety()+42,'     ..............................');
			$this->pdf->text(88,$this->pdf->gety()+42,' ..............................');
			$this->pdf->text(145,$this->pdf->gety()+42,'..............................');
			
			$this->pdf->text(35,$this->pdf->gety()+45,'Collector');
			$this->pdf->text(90,$this->pdf->gety()+45,$row['employeename']);
			$this->pdf->text(150,$this->pdf->gety()+45,'Customer');
			
			$this->pdf->Output();
  }
  /*public function actionDownPDF()
  {
    parent::actionDownload();
    ob_start();
    $sql = "select a.ttfid,a.docno as ttfdocno,d.docno as ttntdocno,b.fullname as employeename,a.docdate,c.companyname,c.bankacc2,c.bankacc1,c.bankacc3,a.companyid
				from ttf a 
				inner join employee b on b.employeeid = a.employeeid 
				inner join ttnt d on d.ttntid = a.ttntid 
				inner join company c on c.companyid = a.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.ttfid in (" . $_GET['id'] . ")";
    }
    $command             = $this->connection->createCommand($sql);
    $dataReader          = $command->queryAll();
		foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $row['companyid'];
    }
    
    $count = Yii::app()->db->createCommand("select count(1) from ttfdetail where ttfid = ".$_REQUEST['id'])->queryScalar();
            
            if($count>7){
                $this->pdf->AddPage('P',array(198,280));          
            }else{
                $this->pdf->AddPage('P',array(198,140));
            }
		//		var_dump($this->sqldata);
	  $this->pdf->isheader=false;
	  

		foreach($dataReader as $row)
    {
			$i=0;$total2=0;
			$this->pdf->SetFont('Arial','B',18);
			$this->pdf->text(70,$this->pdf->gety(),'Tanda Terima Faktur');
			$this->pdf->SetFont('Arial','B',9);
            $this->pdf->text(10,$this->pdf->gety()+5,''.$row['companyname']);
            $this->pdf->text(140,$this->pdf->gety()+5,'No. ');$this->pdf->text(153,$this->pdf->gety()+5,': '.$row['ttfdocno']);
            $this->pdf->text(140,$this->pdf->gety()+10,'Sales ');$this->pdf->text(153,$this->pdf->gety()+10,': '.$row['employeename']);
            $this->pdf->text(10,$this->pdf->gety()+10,'No. Ref. ');$this->pdf->text(25,$this->pdf->gety()+10,': '.$row['ttntdocno']);
      $this->pdf->text(80,$this->pdf->gety()+10,'Tanggal');$this->pdf->text(95,$this->pdf->gety()+10  ,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
     

			$sql1 = "select distinct e.addressbookid,e.fullname
					from ttfdetail a
					join invoice b on b.invoiceid = a.invoiceid
					join giheader c on c.giheaderid = b.giheaderid
					join soheader d on d.soheaderid = c.soheaderid
					join paymentmethod f on f.paymentmethodid = d.paymentmethodid
					join addressbook e on e.addressbookid = d.addressbookid
					where a.ttfid = ".$row['ttfid']." order by fullname ";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			
			$this->pdf->sety($this->pdf->gety()+13);
			$this->pdf->setFont('Arial','B',7);
			$this->pdf->colalign = array('C','C','C','C','C','C');
			$this->pdf->setwidths(array(8,22,50,18,18,28));
			$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
			$this->pdf->colheader = array('No.','No. Inv','Nama Toko','Tgl Inv.', 'Tgl. JTT','Nilai Inv.');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','C','L','C','R');
			$this->pdf->setFont('Arial','',8);
			
			foreach($dataReader1 as $row1)
			{	
				$total = 0;
				$this->pdf->colalign = array('C','C','C','C','C','C');
				$this->pdf->setwidths(array(8,22,50,18,18,28));
				$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
				$this->pdf->coldetailalign = array('L','L','C','L','C','R');
				$this->pdf->setFont('Arial','',8);
				
				$sql2 = "select b.invoiceno,d.sono,e.fullname,b.invoicedate,adddate(b.invoicedate,f.paydays) as jatuhtempo, a.amount,
					b.amount-ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=getwfmaxstatbywfname('appcutar') and f.invoiceid=a.invoiceid and g.docdate < h.docdate),0) as saldoinvoice
					from ttfdetail a
					join invoice b on b.invoiceid = a.invoiceid
					join giheader c on c.giheaderid = b.giheaderid
					join soheader d on d.soheaderid = c.soheaderid
					join paymentmethod f on f.paymentmethodid = d.paymentmethodid
					join addressbook e on e.addressbookid = d.addressbookid
					join ttf h on h.ttfid=a.ttfid
					where a.ttfid = ".$row['ttfid']." and e.addressbookid = ".$row1['addressbookid']." order by fullname ";
			$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();

				
				
				foreach($dataReader2 as $row2)
				{
					$i+=1;
					$this->pdf->row(array($i,$row2['invoiceno'],
						$row2['fullname'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])),
						date(Yii::app()->params['dateviewfromdb'], strtotime($row2['jatuhtempo'])),
						Yii::app()->format->formatNumber($row2['saldoinvoice'])
						));
					$total += $row2['saldoinvoice'];
				}
				$this->pdf->setwidths(array(116,28,20,20,20,20));
				$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
				$this->pdf->coldetailalign = array('R','R','R','R','R','R');
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->row(array('TOTAL '.$row1['fullname'].'  >>> ',
					//Yii::app()->format->formatNumber($total)));
				$total2 += $total;
			}
			$bilangan = explode(".",$total2);
			$this->pdf->setwidths(array(116,28,20,20,20,20));
			$this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
			$this->pdf->coldetailalign = array('C','R','R','R','R','R');
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->row(array('GRAND TOTAL  >>> ',
				Yii::app()->format->formatNumber($total2),
				));

			$this->pdf->checkNewPage(15);
								 
			$this->pdf->setFont('Arial','',8);
			$this->pdf->sety($this->pdf->gety()+5);
            $this->pdf->setFont('Arial','I',8);
        
			$this->pdf->text(15,$this->pdf->gety(),'Terbilang: '. eja($bilangan[0]));
            $this->pdf->setFont('Arial','B',10);
            $this->pdf->text(15,$this->pdf->gety()+5,'Pembayaran Dilakukan Pada');
            $this->pdf->text(15,$this->pdf->gety()+10,'Metode Pembayaran');
            $this->pdf->setFont('Arial','',8);
            $this->pdf->text(80,$this->pdf->gety()+5,'Tanggal :');
            $this->pdf->text(80,$this->pdf->gety()+10,'Cash / Tunai');
            $this->pdf->rect(99,$this->pdf->gety()+7,4,4);
            
            $this->pdf->text(120,$this->pdf->gety()+10,'Transfer');
            $this->pdf->rect(134,$this->pdf->gety()+7,4,4);
            $this->pdf->text(150,$this->pdf->gety()+10,'Giro / Cek');
            $this->pdf->rect(165,$this->pdf->gety()+7,4,4);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->text(15, $this->pdf->gety()+15, 'Apabila Tansfer / Cek / Giro Ditujukan Ke:');
            $this->pdf->setFont('Arial','',9);
            $this->pdf->text(25, $this->pdf->gety()+20, '~ Rekening '.$row['bankacc1']);}
            if ($row['bankacc2'] !== null ){
            $this->pdf->text(25, $this->pdf->gety() + 24, '~ Rekening '.$row['bankacc2']);}
            if ($row['bankacc3'] !== null ){
            $this->pdf->text(25, $this->pdf->gety() + 28, '~ Rekening '.$row['bankacc3']);
            
            $this->pdf->setFont('Arial','',8);
			
			$this->pdf->text(25,$this->pdf->gety()+35,'       Disiapkan oleh,');
			$this->pdf->text(88,$this->pdf->gety()+35,'     Diserahkan oleh,');
			$this->pdf->text(145,$this->pdf->gety()+35,'     Diterima oleh,');
			
			$this->pdf->text(25,$this->pdf->gety()+50,'     ..............................');
			$this->pdf->text(88,$this->pdf->gety()+50,' ..............................');
			$this->pdf->text(145,$this->pdf->gety()+50,'..............................');
			
			$this->pdf->text(35,$this->pdf->gety()+53,'Collector');
			$this->pdf->text(90,$this->pdf->gety()+53,$row['employeename']);
      $this->pdf->text(150,$this->pdf->gety()+53,'Customer');
		}
	  $this->pdf->Output();
  }*/
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select docdate,docno,employeeid,addressbookid,description
				from ttf a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.ttfid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('employeeid'))->setCellValueByColumnAndRow(3, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('description'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['employeeid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['description']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ttf.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    unset($excel);
  }
}
