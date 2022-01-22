<?php
class ReparbaddebtController extends Controller {
  public $menuname = 'reparbaddebt';
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
      echo $this->actionSearchDetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexacc() {
    if (isset($_GET['grid']))
      echo $this->actionSearchAcc();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $arbaddebtid  = isset($_POST['arbaddebtid']) ? $_POST['arbaddebtid'] : '';
    $docno        = isset($_POST['docno']) ? $_POST['docno'] : '';
    $docdate      = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $plant  		  = isset($_POST['plant']) ? $_POST['plant'] : '';
		$companyname  = isset($_POST['companyname']) ? $_POST['companyname'] : '';
		$headernote   = isset($_POST['headernote']) ? $_POST['headernote'] : '';
		$page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 't.arbaddebtid';
		$order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$connection		= Yii::app()->db;
		$from = 'from arbaddebt t
      left join plant a on a.plantid = t.plantid
      left join company b on b.companyid = t.companyid ';
		$where ="where
			(coalesce(t.docno,'') like '%".$docno."%') and
			(coalesce(t.docdate,'') like '%".$docdate."%') and
			(coalesce(b.companyname,'') like '%".$companyname."%') and
			(coalesce(t.arbaddebtid,'') like '%".$arbaddebtid."%') and
			(coalesce(a.plantcode,'') like '%".$plant."%') and t.companyid in(".getUserObjectWfValues('company','listarbaddebt').")";
		$sqldep = new CDbCacheDependency('select max(arbaddebtid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.*,a.plantcode,b.companyname, 
        (select sum(debit) from arbaddebtacc z where z.arbaddebtid = t.arbaddebtid) as debit,
				(select sum(credit) from arbaddebtacc z where z.arbaddebtid = t.arbaddebtid) as credit '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'arbaddebtid' => $data['arbaddebtid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'plantcode' => $data['plantcode'],
        'plantid' => $data['plantid'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusarbaddebt' => $data['statusname'],
        'debit' => $data['debit'],
        'credit' => $data['credit']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'arbaddebtdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('arbaddebtdet t')->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*')->from('arbaddebtdet t')->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'arbaddebtdetid' => $data['arbaddebtdetid'],
        'arbaddebtid' => $data['arbaddebtid'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'],strtotime($data['invoicedate'])),
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'amount' => Yii::app()->format->formatCurrency($data['amount']),
        'payamount' => Yii::app()->format->formatCurrency($data['payamount']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$cmd = Yii::app()->db->createCommand()->select('sum(t.amount) as amount, sum(t.payamount) as payamount')
      ->from('arbaddebtdet t')
      ->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryRow();
		$footer[] = array(
      'fullname' => 'Total',
      'amount' => Yii::app()->format->formatCurrency($cmd['amount']),
      'payamount' => Yii::app()->format->formatCurrency($cmd['payamount']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchacc() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'arbaddebtaccid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset = ($page - 1) * $rows;
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    $footer = array();
    $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('arbaddebtacc t')->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryScalar();
    
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()->select()->from('arbaddebtacc t')->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    
    foreach ($cmd as $data) {
      $row[] = array(
        'arbaddebtaccid' => $data['arbaddebtaccid'],
        'arbaddebtid' => $data['arbaddebtid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'employeeid' => $data['employeeid'],
        'employeename' => $data['employeename'],
        'debit' => Yii::app()->format->formatCurrency($data['debit']),
        'credit' => Yii::app()->format->formatCurrency($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'ratevalue' => Yii::app()->format->formatCurrency($data['ratevalue']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
        
    $cmd = Yii::app()->db->createCommand()->select('sum(t.debit) as debit, sum(t.credit) as credit')
      ->from('arbaddebtacc t')
      ->where('arbaddebtid = :arbaddebtid', array(
      ':arbaddebtid' => $id
    ))->queryRow();
		$footer[] = array(
      'accountname' => 'Total',
      'debit' => Yii::app()->format->formatCurrency($cmd['debit']),
      'credit' => Yii::app()->format->formatCurrency($cmd['credit']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    // parent::actionDownload();
    // $sql = "select a.companyid, a.arbaddebtid,a.docno, b.fullname as plantname, a.sodate, c.paymentname, e.taxcode, e.taxvalue,
		// 	a.addressbookid, a.headernote,a.recordstatus,a.shipto,a.billto,d.fullname as salesname
    //   from arbaddebt a
    //   join addressbook b on b.addressbookid = a.addressbookid
		//   join employee d on d.employeeid = a.employeeid
    //   join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		//   join tax e on e.taxid = a.taxid ";
    // if ($_GET['id'] !== '') {
    //   $sql = $sql . "where a.arbaddebtid in (" . $_GET['id'] . ")";
    // }
    // $command    = $this->connection->createCommand($sql);
    // $dataReader = $command->queryAll();
    // foreach ($dataReader as $row) {
    //   $this->pdf->companyid = $row['companyid'];
    // }
    // $this->pdf->title = 'Sales Order';
    // $this->pdf->AddPage('P', array(
    //   220,
    //   140
    // ));
    // $this->pdf->AliasNbPages();
    // $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
    // $this->pdf->SetFont('Tahoma');
    // foreach ($dataReader as $row) {
    //   if ($row['addressbookid'] > 0) {
    //     $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.lat, a.lng
		// 			from address a
		// 			left join addresstype b on b.addresstypeid = a.addresstypeid
		// 			left join city c on c.cityid = a.cityid
		// 			where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
    //     $command1    = $this->connection->createCommand($sql1);
    //     $dataReader1 = $command1->queryAll();
    //     $phone;
    //     foreach ($dataReader1 as $row1) {
    //       $phone = $row1['phoneno'];
    //     }
    //   }
    //   $this->pdf->SetFontSize(8);
    //   $this->pdf->colalign = array(
    //     'C',
    //     'C',
    //     'C',
    //     'C'
    //   );
    //   $this->pdf->setwidths(array(
    //     20,
    //     100,
    //     30,
    //     60
    //   ));
    //   $this->pdf->row(array(
    //     'plant',
    //     '',
    //     'Sales Order No',
    //     ' : ' . $row['docno']
    //   ));
    //   $this->pdf->row(array(
    //     'Name',
    //     ' : ' . $row['plantname'].'   ('.$row1['lat'].','.$row1['lng'].')',
    //     'SO Date',
    //     ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['sodate']))
    //   ));
    //   $this->pdf->row(array(
    //     'Phone',
    //     ' : ' . $phone,
    //     'Sales',
    //     ' : ' . $row['salesname']
    //   ));
    //   $this->pdf->row(array(
    //     'Address',
    //     ' : ' . $row['shipto'],
    //     'Payment',
    //     ' : ' . $row['paymentname']
    //   ));
    //   $sql1        = "select a.arbaddebtid,c.uomcode,a.qty,a.price * a.currencyrate as price,(qty * price * currencyrate) as total,(e.taxvalue * qty * price * currencyrate / 100) as ppn,b.productname,
		// 	d.symbol,d.i18n,a.itemnote
		// 	from arbaddebtdet a
		// 	left join arbaddebt f on f.arbaddebtid = a.arbaddebtid 
		// 	left join product b on b.productid = a.productid
		// 	left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
		// 	left join currency d on d.currencyid = a.currencyid
		// 	left join tax e on e.taxid = f.taxid
		// 	where a.arbaddebtid = " . $row['arbaddebtid'];
    //   $command1    = $this->connection->createCommand($sql1);
    //   $dataReader1 = $command1->queryAll();
    //   $total       = 0;
    //   $totalqty    = 0;
    //   $this->pdf->sety($this->pdf->gety() + 0);
    //   $this->pdf->colalign = array(
    //     'C',
    //     'C',
    //     'C',
    //     'C',
    //     'C',
    //     'C'
    //   );
    //   $this->pdf->setwidths(array(
    //     20,
    //     15,
    //     70,
    //     40,
    //     20,
    //     35
    //   ));
    //   $this->pdf->colheader = array(
    //     'Qty',
    //     'Units',
    //     'Description',
    //     'Item Note',
    //     'Unit Price',
    //     'Total'
    //   );
    //   $this->pdf->RowHeader();
    //   $this->pdf->coldetailalign = array(
    //     'R',
    //     'C',
    //     'L',
    //     'L',
    //     'R',
    //     'R',
    //     'R'
    //   );
    //   foreach ($dataReader1 as $row1) {
    //     $this->pdf->row(array(
    //       Yii::app()->format->formatNumber($row1['qty']),
    //       $row1['uomcode'],
    //       $row1['productname'],
    //       $row1['itemnote'],
    //       Yii::app()->format->formatCurrency($row1['price']),
    //       Yii::app()->format->formatCurrency($row1['total'])
    //     ));
    //     $total    = $row1['total'] + $total;
    //     $totalqty = $row1['qty'] + $totalqty;
    //   }
    //   $this->pdf->row(array(
    //     Yii::app()->format->formatNumber($totalqty),
    //     '',
    //     'Total',
    //     '',
    //     Yii::app()->format->formatCurrency($total)
    //   ));
    //   $sql1        = "select a.discvalue
		// 	from arbaddebtacc a
		// 	where a.arbaddebtid = " . $row['arbaddebtid'];
    //   $command1    = $this->connection->createCommand($sql1);
    //   $dataReader1 = $command1->queryAll();
    //   $discvalue   = '';
    //   foreach ($dataReader1 as $row1) {
    //     if ($discvalue == '') {
    //       $discvalue = Yii::app()->format->formatNumber($row1['discvalue']);
    //     } else {
    //       $discvalue = $discvalue . ' + ' . Yii::app()->format->formatNumber($row1['discvalue']);
    //     }
    //   }
    //   $this->pdf->colalign = array(
    //     'C',
    //     'C',
    //     'C',
    //     'C',
    //     'C',
    //     'C'
    //   );
    //   $this->pdf->setwidths(array(
    //     35,
    //     155,
    //     155,
    //     155,
    //     155,
    //     155
    //   ));
    //   $this->pdf->iscustomborder = false;
    //   $this->pdf->setbordercell(array(
    //     'none',
    //     'none',
    //     'none',
    //     'none',
    //     'none',
    //     'none'
    //   ));
    //   $this->pdf->coldetailalign = array(
    //     'L',
    //     'L',
    //     'L',
    //     'L',
    //     'L',
    //     'L'
    //   );
    //   $this->pdf->row(array(
    //     'Diskon (%)',
    //     $discvalue
    //   ));
    //   $cmd                 = Yii::app()->db->createCommand()->selectdistinct('gettotalamountdiscso(t.arbaddebtid) as amountafterdisc')->from('arbaddebtdet t')->where('arbaddebtid = :arbaddebtid', array(
    //     ':arbaddebtid' => $row['arbaddebtid']
    //   ))->queryRow();
    //   $this->pdf->colalign = array(
    //     'C',
    //     'C',
    //     'C',
    //     'C',
    //     'C',
    //     'C'
    //   );
    //   $this->pdf->setwidths(array(
    //     35,
    //     155,
    //     155,
    //     155,
    //     155,
    //     155
    //   ));
    //   $this->pdf->iscustomborder = false;
    //   $this->pdf->setbordercell(array(
    //     'none',
    //     'none',
    //     'none',
    //     'none',
    //     'none',
    //     'none'
    //   ));
    //   $this->pdf->coldetailalign = array(
    //     'L',
    //     'L',
    //     'L',
    //     'L',
    //     'L',
    //     'L'
    //   );
    //   $this->pdf->row(array(
    //     'Harga Diskon',
    //     Yii::app()->format->formatNumber($total - $cmd['amountafterdisc'])
    //   ));
    //   $bilangan = explode(".", $cmd['amountafterdisc']);
    //   $this->pdf->row(array(
    //     'Harga Sesudah Diskon',
    //     Yii::app()->format->formatCurrency($cmd['amountafterdisc']) . ' (' . eja($bilangan[0]) . ')'
    //   ));
    //   $this->pdf->sety($this->pdf->gety());
    //   $this->pdf->colalign = array(
    //     'C',
    //     'C',
    //     'C',
    //     'C',
    //     'C',
    //     'C'
    //   );
    //   $this->pdf->setwidths(array(
    //     35,
    //     155,
    //     155,
    //     155,
    //     155,
    //     155
    //   ));
    //   $this->pdf->iscustomborder = false;
    //   $this->pdf->setbordercell(array(
    //     'none',
    //     'none',
    //     'none',
    //     'none',
    //     'none',
    //     'none'
    //   ));
    //   $this->pdf->coldetailalign = array(
    //     'L',
    //     'L',
    //     'L',
    //     'L',
    //     'L',
    //     'L'
    //   );
    //   $this->pdf->row(array(
    //     'Ship To',
    //     $row['shipto']
    //   ));
    //   $this->pdf->row(array(
    //     'Bill To',
    //     $row['billto']
    //   ));
    //   $this->pdf->row(array(
    //     'Note',
    //     $row['headernote']
    //   ));
    //   $this->pdf->checkNewPage(10);
    //   $this->pdf->sety($this->pdf->gety() + 5);
    //   $this->pdf->text(10, $this->pdf->gety(), 'Pembuat');
    //   $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
    //   $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
    //   $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
    // }
    // $this->pdf->Output();
  }
  
}