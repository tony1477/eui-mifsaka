<?php
class InvoicearController extends Controller {
  public $menuname = 'invoicear';
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
  public function actionGeneratedate() {
    $cmd = Yii::app()->db->createCommand()->select('t.gidate')->from('giheader t')->where("t.giheaderid = '" . $_POST['giheaderid'] . "'")->limit(1)->queryRow();
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'status' => 'success',
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($cmd['gidate']))
      ));
      Yii::app()->end();
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $invoiceid     = isset($_POST['invoiceid']) ? $_POST['invoiceid'] : '';
    $invoicedate     = isset($_POST['invoicedate']) ? $_POST['invoicedate'] : '';
    $invoiceno       = isset($_POST['invoiceno']) ? $_POST['invoiceno'] : '';
    $giheaderid      = isset($_POST['giheaderid']) ? $_POST['giheaderid'] : '';
    $companyid      = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $customer      = isset($_POST['customer']) ? $_POST['customer'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $invoiceid = isset($_GET['q']) ? $_GET['q'] : $invoiceid;
    $invoicedate = isset($_GET['q']) ? $_GET['q'] : $invoicedate;
    $invoiceno   = isset($_GET['q']) ? $_GET['q'] : $invoiceno;
    $giheaderid  = isset($_GET['q']) ? $_GET['q'] : $giheaderid;
    $companyid  = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $customer  = isset($_GET['q']) ? $_GET['q'] : $customer;
    $headernote  = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
    $rec          = array();
    $com          = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appinvar')")->queryScalar();
				
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoice t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company d', 'd.companyid = b.companyid')->leftjoin('addressbook c', 'c.addressbookid = b.addressbookid')->leftjoin('currency e', 'e.currencyid = t.currencyid')->
			where("((gino like :gino) or (t.giheaderid is null)  or (invoiceno like :invoiceno) or (fullname like :fullname) or (a.headernote like :headernote))", array(
        ':gino' => '%' . $giheaderid . '%',
        ':invoiceno' => '%' . $invoiceno . '%',
        ':fullname' => '%' . $giheaderid . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->queryScalar();
    } else if (isset($_GET['ttntinv'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoice t')->join('giheader j', 'j.giheaderid = t.giheaderid')->join('soheader k', 'k.soheaderid = j.soheaderid')->join('addressbook m', 'm.addressbookid = k.addressbookid')->leftjoin('currency n', 'n.currencyid = t.currencyid')->
			where("((t.invoiceno like :invoiceno) or (m.fullname like :fullname))
					and k.companyid = :companyid
					and t.recordstatus in (".getUserRecordStatus('listinvar').") and k.companyid in (".getUserObjectValues('company').") and t.invoiceno is not null 
					and t.payamount < t.amount", array(
        ':invoiceno' => '%' . $invoiceno . '%',
        ':fullname' => '%' . $giheaderid . '%',
        ':companyid' => $_GET['companyid']
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoice t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company d', 'd.companyid = b.companyid')->leftjoin('addressbook c', 'c.addressbookid = b.addressbookid')->leftjoin('currency e', 'e.currencyid = t.currencyid')->
			where("((coalesce(gino,'') like :gino) and 
		(coalesce(invoiceid,'') like :invoiceid) and 
		(coalesce(invoiceno,'') like :invoiceno) and 
		(coalesce(t.invoicedate,'') like :invoicedate) and 
		(coalesce(fullname,'') like :fullname) and 
		(coalesce(a.headernote,'') like :headernote) and 
		(coalesce(d.companyname,'') like :companyid))
					and t.recordstatus < {$maxstat} and t.recordstatus in (".getUserRecordStatus('listinvar').") and b.companyid in (".getUserObjectWfValues('company','listinvar').")", array(
        ':gino' => '%' . $giheaderid . '%',
      ':invoiceid' => '%' . $invoiceid . '%',
      ':invoiceno' => '%' . $invoiceno . '%',
      ':companyid' => '%' . $companyid . '%',
      ':invoicedate' => '%' . $invoicedate . '%',
      ':fullname' => '%' . $customer . '%',
      ':headernote' => '%' . $headernote . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['list'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.gino,b.sono,c.fullname,d.companyname,e.currencyname,t.currencyrate,(t.amount-t.payamount) as saldo,case when t.amount > t.payamount then 1
		when t.amount = t.payamount then 2
		end as warna')->from('invoice t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company d', 'd.companyid = b.companyid')->leftjoin('addressbook c', 'c.addressbookid = b.addressbookid')->leftjoin('currency e', 'e.currencyid = t.currencyid')->where("((gino like :gino) or (invoiceno like :invoiceno) or (fullname like :fullname) or (a.headernote like :headernote))", array(
        ':gino' => '%' . $giheaderid . '%',
        ':invoiceno' => '%' . $invoiceno . '%',
        ':fullname' => '%' . $giheaderid . '%',
        ':headernote' => '%' . $headernote . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['ttntinv'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,j.gino,k.sono,m.fullname,l.companyname,n.currencyname,t.currencyrate
			,(t.amount-t.payamount) as saldo,case when t.amount > t.payamount then 1
		when t.amount = t.payamount then 2
		end as warna')->from('invoice t')->leftjoin('giheader j', 'j.giheaderid = t.giheaderid')->leftjoin('soheader k', 'k.soheaderid = j.soheaderid')->leftjoin('company l', 'l.companyid = k.companyid')->leftjoin('addressbook m', 'm.addressbookid = k.addressbookid')->leftjoin('currency n', 'n.currencyid = t.currencyid')->where("((t.invoiceno like :invoiceno) or (m.fullname like :fullname))
                and k.companyid = :companyid
					and t.recordstatus in (".getUserRecordStatus('listinvar').") and k.companyid in (".getUserObjectValues('company').") and t.invoiceno is not null 
and t.payamount < t.amount", array(
        ':invoiceno' => '%' . $invoiceno . '%',
        ':fullname' => '%' . $giheaderid . '%',
        ':companyid' => $_GET['companyid']
      ))->offset($offset)->limit($rows)->order('t.invoicedate asc')->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.gino,b.sono,c.fullname,d.companyname,e.currencyname,t.currencyrate,(t.amount-t.payamount) as saldo,case when t.amount > t.payamount then 1
		when t.amount = t.payamount then 2
		end as warna')->from('invoice t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company d', 'd.companyid = b.companyid')->leftjoin('addressbook c', 'c.addressbookid = b.addressbookid')->leftjoin('currency e', 'e.currencyid = t.currencyid')->
			where("((coalesce(gino,'') like :gino) and 
		(coalesce(invoiceid,'') like :invoiceid) and 
		(coalesce(invoiceno,'') like :invoiceno) and 
		(coalesce(t.invoicedate,'') like :invoicedate) and 
		(coalesce(fullname,'') like :fullname) and 
		(coalesce(a.headernote,'') like :headernote) and 
		(coalesce(d.companyname,'') like :companyid))
					and t.recordstatus < {$maxstat} and t.recordstatus in (".getUserRecordStatus('listinvar').") and b.companyid in (".getUserObjectWfValues('company','listinvar').")", array(
        ':gino' => '%' . $giheaderid . '%',
      ':invoiceid' => '%' . $invoiceid . '%',
      ':invoiceno' => '%' . $invoiceno . '%',
      ':companyid' => '%' . $companyid . '%',
      ':invoicedate' => '%' . $invoicedate . '%',
      ':fullname' => '%' . $customer . '%',
      ':headernote' => '%' . $headernote . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceid' => $data['invoiceid'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'invoiceno' => $data['invoiceno'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatCurrency($data['currencyrate']),
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'sono' => $data['sono'],
        'fullname' => $data['fullname'],
        'companyname' => $data['companyname'],
        'warna' => $data['warna'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'saldo' => Yii::app()->format->formatNumber($data['saldo']),
        'payamount' => Yii::app()->format->formatNumber($data['payamount']),
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
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
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'gidetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->leftjoin('storagebin c', 'c.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
      ':giheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,d.sloccode,d.description as slocdesc,c.description')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->leftjoin('storagebin c', 'c.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
      ':giheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'qty' => $data['qty'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['description'],
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select a.uomcode,sum(qty) as credit 
		from gidetail t 
		join unitofmeasure a on a.unitofmeasureid = t.unitofmeasureid
		where giheaderid = ".$id." group by a.uomcode";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
			$footer[] = array(
				'productname' => 'Total',
				'qty' => Yii::app()->format->formatNumber($data['credit']),
				'uomcode' => $data['uomcode']
			);
		}
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($arraydata) {
		$connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {
            $id = (isset($arraydata[0])?$arraydata[0]:'');
            if ($id == '') {
            $sql     = 'call Insertinvoicear(:vgiheaderid,:vinvoiceno,:vamount,:vcurrencyid,:vcurrencyrate,:vheadernote,:vrecordstatus,:vcreatedby)';
            $command = $connection->createCommand($sql);
            $command->bindvalue(':vinvoiceno', $arraydata[2], PDO::PARAM_STR);
            $command->bindvalue(':vamount', $arraydata[3], PDO::PARAM_STR);
            $command->bindvalue(':vrecordstatus', $arraydata[7], PDO::PARAM_STR);
          } else {
            $sql     = 'call Updateinvoicear(:vid,:vgiheaderid,:vcurrencyid,:vcurrencyrate,:vheadernote,:vcreatedby)';
            $command = $connection->createCommand($sql);
            $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
            $this->DeleteLock($this->menuname, $arraydata[0]);
          }
          $command->bindvalue(':vgiheaderid', $arraydata[1], PDO::PARAM_STR);
          $command->bindvalue(':vcurrencyid', $arraydata[4], PDO::PARAM_STR);
          $command->bindvalue(':vcurrencyrate', $arraydata[5], PDO::PARAM_STR);
          $command->bindvalue(':vheadernote', $arraydata[6], PDO::PARAM_STR);
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
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileInvoice"]["name"]);
		if (move_uploaded_file($_FILES["FileInvoice"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			for ($row = 2; $row <= $highestRow; ++$row) {
				$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
				$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
				$invoicedate = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
                $invoiceno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
                $gino = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
				$giheaderid = Yii::app()->db->createCommand("select giheaderid from giheader where gino = '".$gino."' and companyid = ".$companyid)->queryScalar();
				$amount = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
				$currencyname = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
				$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
				$currencyrate = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
				$headernote = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
				$recordstatus = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
				$this->ModifyData(array('',$giheaderid,$invoiceno,$amount,$currencyid,$currencyrate,$headernote,$recordstatus));
			}
    }
	}
  public function actionSave() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $this->ModifyData(array((isset($_POST['invoiceid'])?$_POST['invoiceid']:''),$_POST['giheaderid'],'',0,$_POST['currencyid'],$_POST['currencyrate'],$_POST['headernote'],findstatusbyuser('insinvar')));
  }
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteInvoiceAR(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage(), 1);
      }
    } else {
      GetMessage(true, 'chooseone', 1);
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveInvoiceAR(:vid,:vcreatedby)';
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
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinvoice(:vid,:vcreatedby)';
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
    $sql = "select f.companyid,a.amount,g.symbol,currencyrate,a.giheaderid,invoiceid,invoiceno,f.sono,d.fullname as customer,a.invoicedate,a.headernote, taxvalue,a.recordstatus,
	   f.shipto as addressname,j.cityname,f.isdisplay,ifnull(count(packageid),0) as pkgid,
		 a.recordstatus,date_add(a.invoicedate, INTERVAL e.paydays day) as duedate,b.gino,f.sono,f.soheaderid,h.fullname as sales,i.bankacc1,i.bankacc2,i.bankacc3,
		 (select headernote from packages s where s.packageid = f.packageid) as packagenote,(select packagename from packages s where s.packageid = f.packageid) as packagename,ifnull(qtypackage,0) as qtypackage
		from invoice a 
		left join giheader b on b.giheaderid = a.giheaderid
		left join soheader f on f.soheaderid = b.soheaderid
		left join tax c on c.taxid = f.taxid 
		left join currency g on g.currencyid = a.currencyid
		left join addressbook d on d.addressbookid = f.addressbookid
		left join paymentmethod e on e.paymentmethodid = f.paymentmethodid
		left join employee h on h.employeeid = f.employeeid
		left join company i on i.companyid = f.companyid
		left join city j on j.cityid = i.cityid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where a.invoiceid in (" . $_GET['id'] . ")";
    }
    $sql        = $sql . " order by invoiceid";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Faktur Penitipan Barang';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AddFont('tahoma', '', 'tahoma.php');
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('tahoma');
    $sodisc      = '';
    $sql1        = 'select ifnull(discvalue,0) as discvalue from sodisc z where z.soheaderid = ' . $row['soheaderid'];
    $command1    = $this->connection->createCommand($sql1);
    $dataReader1 = $command1->queryAll();
    foreach ($dataReader1 as $row1) {
      if ($sodisc == '') {
        $sodisc = Yii::app()->format->formatCurrency($row1['discvalue']);
      } else {
        $sodisc = $sodisc . '+' . Yii::app()->format->formatCurrency($row1['discvalue']);
      }
    }
    if ($sodisc == '') {
      $sodisc = '0';
    }
    foreach ($dataReader as $row) {
			if($row['isdisplay']==1) $this->pdf->Image('images/DISPLAY.jpg', 0, 8, 210, 135);
      $this->pdf->setFontSize(9);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        70,
        20,
        10,
        10,
        70
      ));
      $this->pdf->row(array(
        'No',
        ' : ' . $row['invoiceno'],
        '',
        '',
        '',
        $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate']))
      ));
      $this->pdf->row(array(
        'Sales',
        ' : ' . $row['sales'],
        '',
        '',
        '',
        'Kepada Yth, '
      ));
      $this->pdf->row(array(
        'No. SO ',
        ' : ' . $row['sono'],
        '',
        '',
        '',
        $row['customer']
      ));
      $this->pdf->row(array(
        'T.O.P. ',
        ($row['isdisplay']==1) ? ' : LANGSUNG BAYAR SAAT TERJUAL' : ' : ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])),
        '',
        '',
        '',
        $row['addressname']
      ));
      $sql1        = "select * from (select a.sodetailid,d.productname,sum(a.qty) as qty,c.uomcode,f.price,b.symbol,a.itemnote,
	    (price * sum(a.qty) * ifnull(e.taxvalue,0)/100) as taxvalue
        from gidetail a
				inner join sodetail f on f.sodetailid = a.sodetailid
				inner join soheader g on g.soheaderid = f.soheaderid
		inner join product d on d.productid = a.productid
		inner join currency b on b.currencyid = f.currencyid
		inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
		left join tax e on e.taxid = g.taxid
        where a.giheaderid = '" . $row['giheaderid'] . "' group by d.productname,a.sodetailid order by a.sodetailid
		) zz order by zz.sodetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->SetY($this->pdf->gety() + 3);
      $this->pdf->setFontSize(9);
      $this->pdf->colalign = array(
        'L',
        'L',
        'C',
        'C',
        'C',
        'C',
        'L',
        'L'
      );
      $this->pdf->setwidths(array(
        7,
        110,
        18,
        10,
        27,
        32
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
          ($row['pkgid']==1) ? '' : 'Price',
          ($row['pkgid']==1) ? '' : 'Total',
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R',
        'R'
      );
      $i                         = 0;
      $total                     = 0;
      $b                         = '';
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $b = $row1['symbol'];
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          ($row['pkgid']==1) ? '' : Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
          ($row['pkgid']==1) ? '' : Yii::app()->format->formatCurrency(($row1['price'] * $row1['qty']), $row1['symbol'])
        ));
        $total += ($row1['price'] * $row1['qty']);
      }
      $this->pdf->setaligns(array(
        'L',
        'R',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R'
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        ($row['pkgid']==1) ? '' : 'Nominal',
        ($row['pkgid']==1) ? '' : Yii::app()->format->formatCurrency($total, $b)
      ));
      $this->pdf->row(array(
        '',
        ($row['pkgid']==1) ? '' : 'Disc ' . $sodisc . ' (%) ',
        '',
        '',
        ($row['pkgid']==1) ? '' : 'Diskon',
        ($row['pkgid']==1) ? '' : Yii::app()->format->formatCurrency($total - $row['amount'], $b)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Netto',
        Yii::app()->format->formatCurrency($row['amount'], $b)
      ));
      $bilangan                  = explode(".", $row['amount']);
      $this->pdf->iscustomborder = true;
      $this->pdf->setbordercell(array(
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        ''
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
        'Terbilang : ' . eja($bilangan[0])
      ));
      $this->pdf->row(array(
        (($row['pkgid']==1) ? "NOTE : ".$row['packagename']." (QTY : ".Yii::app()->format->formatCurrency($row['qtypackage']).") \n".$row['packagenote']." \n". $row['headernote'] : "NOTE : ".$row['headernote']
      )));
/*	  $this->pdf->row(array(
        ($row['pkgid']==1) ? "NOTE : ".$row['packagename']." (QTY : ".Yii::app()->format->formatCurrency($row['qtypackage']).") \n". $row['headernote'] : 'NOTE : ' . $row['headernote']
      ));*/
      $this->pdf->checkNewPage(20);
      $this->pdf->text(25, $this->pdf->gety() + 5, 'Approved By');
      $this->pdf->text(170, $this->pdf->gety() + 5, 'Proposed By');
      $this->pdf->text(25, $this->pdf->gety() + 25, '_____________ ');
      $this->pdf->text(170, $this->pdf->gety() + 25, '_____________');
      $this->pdf->text(10, $this->pdf->gety() + 30, 'Catatan:');
      $this->pdf->text(25, $this->pdf->gety() + 30, '- Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan');
      if ($row['bankacc1'] !== null ){
      $this->pdf->text(25, $this->pdf->gety() + 35, '- Transfer Bank ke:');
      $this->pdf->text(55, $this->pdf->gety() + 35, '~ Rekening '.$row['bankacc1']);}
      if ($row['bankacc2'] !== null ){
      $this->pdf->text(55, $this->pdf->gety() + 40, '~ Rekening '.$row['bankacc2']);}
      if ($row['bankacc3'] !== null ){
      $this->pdf->text(55, $this->pdf->gety() + 45, '~ Rekening '.$row['bankacc3']);}
    }
    $this->pdf->Output();
  }
  public function actionDownPDF1() {
    parent::actionDownload();
    $sql = "select f.companyid,a.amount,g.symbol,currencyrate,a.giheaderid,invoiceid,invoiceno,f.sono,d.fullname as customer,a.invoicedate,a.headernote, taxvalue,a.recordstatus,
	   f.shipto as addressname,j.cityname,f.isdisplay,
		 a.recordstatus,date_add(a.invoicedate, INTERVAL e.paydays day) as duedate,b.gino,f.sono,f.soheaderid,h.fullname as sales,i.bankacc1,i.bankacc2,i.bankacc3
		from invoice a 
		left join giheader b on b.giheaderid = a.giheaderid
		left join soheader f on f.soheaderid = b.soheaderid
		left join tax c on c.taxid = f.taxid 
		left join currency g on g.currencyid = a.currencyid
		left join addressbook d on d.addressbookid = f.addressbookid
		left join paymentmethod e on e.paymentmethodid = f.paymentmethodid
		left join employee h on h.employeeid = f.employeeid
		left join company i on i.companyid = f.companyid
		left join city j on j.cityid = i.cityid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where a.invoiceid in (" . $_GET['id'] . ")";
    }
    $sql        = $sql . " order by invoiceid";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Faktur Penitipan Barang';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AddFont('tahoma', '', 'tahoma.php');
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('tahoma');
    $sodisc      = '';
    $sql1        = 'select ifnull(discvalue,0) as discvalue from sodisc z where z.soheaderid = ' . $row['soheaderid'];
    $command1    = $this->connection->createCommand($sql1);
    $dataReader1 = $command1->queryAll();
    foreach ($dataReader1 as $row1) {
      if ($sodisc == '') {
        $sodisc = Yii::app()->format->formatCurrency($row1['discvalue']);
      } else {
        $sodisc = $sodisc . '+' . Yii::app()->format->formatCurrency($row1['discvalue']);
      }
    }
    if ($sodisc == '') {
      $sodisc = '0';
    }
    foreach ($dataReader as $row) {
			if($row['isdisplay']==1) $this->pdf->Image('images/DISPLAY.jpg', 0, 8, 210, 135);
      $this->pdf->setFontSize(9);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        70,
        20,
        10,
        10,
        70
      ));
      $this->pdf->row(array(
        'No',
        ' : ' . $row['invoiceno'],
        '',
        '',
        '',
        $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate']))
      ));
      $this->pdf->row(array(
        'Sales',
        ' : ' . $row['sales'],
        '',
        '',
        '',
        'Kepada Yth, '
      ));
      $this->pdf->row(array(
        'No. SO ',
        ' : ' . $row['sono'],
        '',
        '',
        '',
        $row['customer']
      ));
      $this->pdf->row(array(
        'T.O.P. ',
        ($row['isdisplay']==1) ? ' : LANGSUNG BAYAR SAAT TERJUAL' : ' : ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])),
        '',
        '',
        '',
        $row['addressname']
      ));
      $sql1        = "select * from (select a.sodetailid,d.productname,sum(a.qty) as qty,c.uomcode,f.price,b.symbol,a.itemnote,
	    (price * sum(a.qty) * ifnull(e.taxvalue,0)/100) as taxvalue
        from gidetail a
				inner join sodetail f on f.sodetailid = a.sodetailid
				inner join soheader g on g.soheaderid = f.soheaderid
		inner join product d on d.productid = a.productid
		inner join currency b on b.currencyid = f.currencyid
		inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
		left join tax e on e.taxid = g.taxid
        where a.giheaderid = '" . $row['giheaderid'] . "' group by d.productname,a.sodetailid order by a.sodetailid
		) zz order by zz.sodetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->SetY($this->pdf->gety() + 3);
      $this->pdf->setFontSize(9);
      $this->pdf->colalign = array(
        'L',
        'L',
        'C',
        'C',
        'C',
        'C',
        'L',
        'L'
      );
      $this->pdf->setwidths(array(
        7,
        110,
        18,
        10,
        27,
        32
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Price',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R',
        'R'
      );
      $i                         = 0;
      $total                     = 0;
      $b                         = '';
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $b = $row1['symbol'];
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
          Yii::app()->format->formatCurrency(($row1['price'] * $row1['qty']), $row1['symbol'])
        ));
        $total += ($row1['price'] * $row1['qty']);
      }
      $this->pdf->setaligns(array(
        'L',
        'R',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R'
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Nominal',
        Yii::app()->format->formatCurrency($total, $b)
      ));
      $this->pdf->row(array(
        '',
        'Disc ' . $sodisc . ' (%) ',
        '',
        '',
        'Diskon',
        Yii::app()->format->formatCurrency($total - $row['amount'], $b)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Netto',
        Yii::app()->format->formatCurrency($row['amount'], $b)
      ));
      $bilangan                  = explode(".", $row['amount']);
      $this->pdf->iscustomborder = true;
      $this->pdf->setbordercell(array(
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        ''
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
        'Terbilang : ' . eja($bilangan[0])
      ));
      $this->pdf->row(array(
        'NOTE : ' . $row['headernote']
      ));
      $this->pdf->checkNewPage(20);
      $this->pdf->text(25, $this->pdf->gety() + 5, 'Approved By');
      $this->pdf->text(170, $this->pdf->gety() + 5, 'Proposed By');
      $this->pdf->text(25, $this->pdf->gety() + 25, '_____________ ');
      $this->pdf->text(170, $this->pdf->gety() + 25, '_____________');
      $this->pdf->text(10, $this->pdf->gety() + 30, 'Catatan:');
      $this->pdf->text(25, $this->pdf->gety() + 30, '- Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan');
      if ($row['bankacc1'] !== null ){
      $this->pdf->text(25, $this->pdf->gety() + 35, '- Transfer Bank ke:');
      $this->pdf->text(55, $this->pdf->gety() + 35, '~ Rekening '.$row['bankacc1']);}
      if ($row['bankacc2'] !== null ){
      $this->pdf->text(55, $this->pdf->gety() + 40, '~ Rekening '.$row['bankacc2']);}
      if ($row['bankacc3'] !== null ){
      $this->pdf->text(55, $this->pdf->gety() + 45, '~ Rekening '.$row['bankacc3']);}
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'printinvoicearppn';
    parent::actionDownXls();
    if (getUserObjectValues('invarppn') == 1){
    $sql = "select f.companyid,a.amount,g.symbol,currencyrate,a.giheaderid,invoiceid,invoiceno,f.sono,d.fullname as customer,a.invoicedate,a.headernote,c.taxvalue,a.recordstatus,a.amount*(c.taxvalue/100) as amountppn,
	   f.shipto as addressname,
	   j.cityname,
		 a.recordstatus,date_add(a.invoicedate, INTERVAL e.paydays day) as duedate,b.gino,f.sono,f.soheaderid,h.fullname as sales,i.bankacc1,i.bankacc2,i.bankacc3
		from invoice a 
		left join giheader b on b.giheaderid = a.giheaderid
		left join soheader f on f.soheaderid = b.soheaderid
		left join tax c on c.taxid = f.taxid 
		left join currency g on g.currencyid = a.currencyid
		left join addressbook d on d.addressbookid = f.addressbookid
		left join paymentmethod e on e.paymentmethodid = f.paymentmethodid
		left join employee h on h.employeeid = f.employeeid
		left join company i on i.companyid = f.companyid
		left join city j on j.cityid = i.cityid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.invoiceid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    //$excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 9;
    
    foreach ($dataReader as $row) {
        $sql1 = "select * from (select a.sodetailid,d.productname,sum(a.qty) as qty,c.uomcode,f.price,b.symbol,a.itemnote,
	    (price * sum(a.qty) * ifnull(e.taxvalue,0)/100) as taxvalue
        from gidetail a
				inner join sodetail f on f.sodetailid = a.sodetailid
				inner join soheader g on g.soheaderid = f.soheaderid
		inner join product d on d.productid = a.productid
		inner join currency b on b.currencyid = f.currencyid
		inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
		left join tax e on e.taxid = g.taxid
        where a.giheaderid = '" . $row['giheaderid'] . "' group by d.productname,a.sodetailid order by a.sodetailid
		) zz order by zz.sodetailid";
        $dataReader1 = $this->connection->createCommand($sql1)->queryAll();
        $x = 1;
        $total=0;
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, 3, $row['invoiceno'])
            ->setCellValueByColumnAndRow(1, 4, $row['sales'])
            ->setCellValueByColumnAndRow(1, 5, $row['sono'])
            ->setCellValueByColumnAndRow(1, 6, date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])))
            ->setCellValueByColumnAndRow(5, 3, $row['cityname'].', '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])))
            ->setCellValueByColumnAndRow(5, 5, $row['customer'])
            ->setCellValueByColumnAndRow(5, 6, $row['addressname']);
        
        foreach($dataReader1 as $row1)
        {
            $b = $row1['symbol'];
            $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(0, $i , $x)
              ->setCellValueByColumnAndRow(1, $i , $row1['productname'])
              ->setCellValueByColumnAndRow(2, $i, Yii::app()->format->formatNumber($row1['qty']))
              ->setCellValueByColumnAndRow(3, $i, $row1['uomcode'])
              ->setCellValueByColumnAndRow(4, $i, Yii::app()->format->formatCurrency($row1['price']/1.1, $row1['symbol']))
              ->setCellValueByColumnAndRow(5, $i, Yii::app()->format->formatCurrency(($row1['price'] * $row1['qty'])/1.1, $row1['symbol']));
            $i += 1;
            $x += 1;
            $total += ($row1['price'] * $row1['qty'])/1.1;
        }
        $sodisc='';
        $sql2 = 'select ifnull(discvalue,0) as discvalue from sodisc z where z.soheaderid = ' . $row['soheaderid'];
        $dataReader2 = $this->connection->createCommand($sql2)->queryAll();
        $sqlinvoice = "select round(sum(d.price*c.qty)/1.1,2)
                from invoice a
                join giheader b on b.giheaderid = a.giheaderid
                join gidetail c on c.giheaderid = a.giheaderid
                join sodetail d on d.sodetailid = c.sodetailid
                where a.invoiceid = ".$row['invoiceid'];
        $nilai1 = $this->connection->createCommand($sqlinvoice)->queryScalar();
        $nilai=0;
        $nilai2=0;
        $nilaiakhir = $nilai1;
        foreach ($dataReader2 as $row2)
        {
            if($row2['discvalue'] == 0 || $row2['discvalue']===null)
            {
                $sodisc = 0;
                $nilai2 = 0;
                //$nilaiakhir = $nilai1;
            }
            else
            {

                if ($sodisc == '')
                {
                    $sodisc = Yii::app()->format->formatNumber($row2['discvalue']);
                    $nilai = $row2['discvalue']/100*$nilai1;
                    $nilai2 = -1*($nilai-$nilai1);
                }
                else
                {
                    $sodisc = $sodisc . '+' . Yii::app()->format->formatNumber($row2['discvalue']);
                    $nilai = $nilai2;
                    $nilai = ($row2['discvalue']/100)*$nilai;
                    $nilai2 = -1*($nilai-$nilai2);
                }

                $nilaiakhir = $nilai2;
            }
        }
        if ($sodisc == '')
        {
            $sodisc = '0';
        }
        
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(3, $i ,'___________')
            ->setCellValueByColumnAndRow(4, $i+1 ,'Total')
            ->setCellValueByColumnAndRow(5, $i+1 ,Yii::app()->format->formatCurrency($total, $b));
        
        if ($sodisc!=0) 
        {
           $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $i+2 ,'Disc '.$sodisc.' (%)')
            ->setCellValueByColumnAndRow(4, $i+2 ,'Diskon')
            ->setCellValueByColumnAndRow(5, $i+2 , Yii::app()->format->formatCurrency(-($total - $nilai2), $b));
        }
        else
        {
           $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $i+2 ,'Disc '.$sodisc.' (%)')
            ->setCellValueByColumnAndRow(4, $i+2 ,'Diskon')
            ->setCellValueByColumnAndRow(5, $i+2 , Yii::app()->format->formatCurrency($nilai2, $b));
        }
        
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(4, $i+3 ,'Total Setelah Diskon')
            ->setCellValueByColumnAndRow(5, $i+3 , Yii::app()->format->formatCurrency($nilaiakhir, $b));
        
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(4, $i+4 ,'PPN '.$row['taxvalue'].'%')
            ->setCellValueByColumnAndRow(5, $i+4 , Yii::app()->format->formatCurrency(($nilai2*$row['taxvalue']/100), $b));
        
        if($sodisc==0)
        {
            $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(4, $i+5 ,'Total Setelah PPN ')
            ->setCellValueByColumnAndRow(5, $i+5 , Yii::app()->format->formatCurrency($total, $b));
        }
        else
        {
            $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(4, $i+5 ,'Total Setelah PPN ')
            ->setCellValueByColumnAndRow(5, $i+5 , Yii::app()->format->formatCurrency($nilai2+($nilai2*$row['taxvalue']/100), $b));
        }
     
        $bilangan = explode(".", $nilai2+($nilai2*($row['taxvalue']/100)));
        
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $i+6 ,'Terbilang :')
            ->setCellValueByColumnAndRow(1, $i+6 , eja($bilangan[0])); 
        
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $i+7 ,'NOTE :')
            ->setCellValueByColumnAndRow(1, $i+7 , $row['headernote']);
        
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $i+8 ,'Approved By :')
            ->setCellValueByColumnAndRow(4, $i+8 ,'Proposed By');
        
        
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $i+13 ,'Catatan :')
            ->setCellValueByColumnAndRow(1, $i+13 ,'- Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan');
        if ($row['bankacc1'] !== null )
        {
          $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $i+14 ,'Transfer Bank ke : ~ Rekening '.$row['bankacc1']);
        }
        if ($row['bankacc2'] !== null )
        {
          $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $i+15 ,'Transfer Bank ke : ~ Rekening '.$row['bankacc2']);
        }
        if ($row['bankacc3'] !== null )
        {
            $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $i+16 ,'Transfer Bank ke : ~ Rekening '.$row['bankacc3']);
        }
                         
                                         
    }
    $this->getFooterXLS($this->phpExcel);
  }}
}