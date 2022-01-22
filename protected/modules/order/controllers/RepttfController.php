<?php
class RepttfController extends Controller {
  public $menuname = 'repttf';
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
  public function actionIndexdisc()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchDisc();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $ttfid   = isset($_POST['ttfid']) ? $_POST['ttfid'] : '';
    $docno        	= isset($_POST['docno']) ? $_POST['docno'] : '';
    $sales  		= isset($_POST['sales']) ? $_POST['sales'] : '';
    $companyname    = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $description     = isset($_POST['description']) ? $_POST['description'] : '';
    $page           = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows           = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort           = isset($_POST['sort']) ? strval($_POST['sort']) : 't.ttfid';
    $order          = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset         = ($page - 1) * $rows;
    $result         = array();
    $row            = array();
    $connection		= Yii::app()->db;
		$from = '
			from ttf t 
			left join company a on a.companyid = t.companyid 
			left join employee d on d.employeeid = t.employeeid  ';
		$where ="
			where (coalesce(ttfid,'') like '%".$ttfid."%') and (coalesce(docno,'') like '%".$docno."%') and (coalesce(d.fullname) like '%".$sales."%') 
				and (coalesce(a.companyname,'') like '%".$companyname."%')
				and (coalesce(t.description,'') like '%".$description."%')
				and t.companyid in (".getUserObjectValues('company').")";
		$sqldep = new CDbCacheDependency('select max(ttfid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.*, a.companyname, d.fullname as salesname'.$from.' '.$where;
        $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ttfid' => $data['ttfid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'employeeid' => $data['employeeid'],
        'salesname' => $data['salesname'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'ttfdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = 'select count(1) as total ';
    $from            = 'from ttfdetail t
                        left join invoice a on a.invoiceid = t.invoiceid
                        left join ttntdetail b on b.ttntdetailid = t.ttntdetailid
                        left join giheader c on c.giheaderid = a.giheaderid
                        left join soheader d on d.soheaderid = c.soheaderid
                        left join addressbook e on e.addressbookid = d.addressbookid
                        left join paymentmethod f on f.paymentmethodid = d.paymentmethodid ';
    $where           = ' where t.ttfid = '.$id;
    
    $result['total'] = Yii::app()->db->createCommand($cmd.$from.$where)->queryScalar();
    $cmd             = Yii::app()->db->createCommand('select t.*, e.fullname, a.invoiceno, a.invoicedate, date_add(a.invoicedate,interval paydays day) as duedate, d.sono, a.amount, t.payamount '.$from.$where.' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ttfdetailid' => $data['ttfdetailid'],
        'ttfid' => $data['ttfid'],
        'customer' => $data['fullname'],
        'invoiceno' => $data['invoiceno'],
        'sono' => $data['sono'],
        'amount' => Yii::app()->format->formatCurrency($data['amount']),
        'payamount' => Yii::app()->format->formatCurrency($data['payamount']),
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate']))
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.companyid, a.ttfid,a.docno, b.fullname as salesname, a.docdate, c.paymentname, e.taxcode, e.taxvalue,
			a.addressbookid, a.description,a.recordstatus,a.shipto,a.billto,d.fullname as salesname
      from ttf a
      join addressbook b on b.addressbookid = a.addressbookid
		  join employee d on d.employeeid = a.employeeid
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		  join tax e on e.taxid = a.taxid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.ttfid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Sales Order';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
    $this->pdf->SetFont('Tahoma');
    foreach ($dataReader as $row) {
      if ($row['addressbookid'] > 0) {
        $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.lat, a.lng
					from address a
					left join addresstype b on b.addresstypeid = a.addresstypeid
					left join city c on c.cityid = a.cityid
					where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $phone;
        foreach ($dataReader1 as $row1) {
          $phone = $row1['phoneno'];
        }
      }
      $this->pdf->SetFontSize(8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        100,
        30,
        60
      ));
      $this->pdf->row(array(
        'sales',
        '',
        'Sales Order No',
        ' : ' . $row['docno']
      ));
      $this->pdf->row(array(
        'Name',
        ' : ' . $row['salesname'].'   ('.$row1['lat'].','.$row1['lng'].')',
        'SO Date',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate']))
      ));
      $this->pdf->row(array(
        'Phone',
        ' : ' . $phone,
        'Sales',
        ' : ' . $row['salesname']
      ));
      $this->pdf->row(array(
        'Address',
        ' : ' . $row['shipto'],
        'Payment',
        ' : ' . $row['paymentname']
      ));
      $sql1        = "select a.ttfid,c.uomcode,a.qty,a.price * a.currencyrate as price,(qty * price * currencyrate) as total,(e.taxvalue * qty * price * currencyrate / 100) as ppn,b.addressbookname,
			d.symbol,d.i18n,a.itemnote
			from ttfdetail a
			left join ttf f on f.ttfid = a.ttfid 
			left join addressbook b on b.addressbookid = a.addressbookid
			left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
			left join currency d on d.currencyid = a.currencyid
			left join tax e on e.taxid = f.taxid
			where a.ttfid = " . $row['ttfid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 0);
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
        15,
        70,
        40,
        20,
        35
      ));
      $this->pdf->colheader = array(
        'Qty',
        'Units',
        'Description',
        'Item Note',
        'Unit Price',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'R',
        'C',
        'L',
        'L',
        'R',
        'R',
        'R'
      );
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['addressbookname'],
          $row1['itemnote'],
          Yii::app()->format->formatCurrency($row1['price']),
          Yii::app()->format->formatCurrency($row1['total'])
        ));
        $total    = $row1['total'] + $total;
        $totalqty = $row1['qty'] + $totalqty;
      }
      $this->pdf->row(array(
        Yii::app()->format->formatNumber($totalqty),
        '',
        'Total',
        '',
        Yii::app()->format->formatCurrency($total)
      ));
      $sql1        = "select a.discvalue
			from sodisc a
			where a.ttfid = " . $row['ttfid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $discvalue   = '';
      foreach ($dataReader1 as $row1) {
        if ($discvalue == '') {
          $discvalue = Yii::app()->format->formatNumber($row1['discvalue']);
        } else {
          $discvalue = $discvalue . ' + ' . Yii::app()->format->formatNumber($row1['discvalue']);
        }
      }
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Diskon (%)',
        $discvalue
      ));
      $cmd                 = Yii::app()->db->createCommand()->selectdistinct('gettotalamountdiscso(t.ttfid) as amountafterdisc')->from('ttfdetail t')->where('ttfid = :ttfid', array(
        ':ttfid' => $row['ttfid']
      ))->queryRow();
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Harga Diskon',
        Yii::app()->format->formatNumber($total - $cmd['amountafterdisc'])
      ));
      $bilangan = explode(".", $cmd['amountafterdisc']);
      $this->pdf->row(array(
        'Harga Sesudah Diskon',
        Yii::app()->format->formatCurrency($cmd['amountafterdisc']) . ' (' . eja($bilangan[0]) . ')'
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Ship To',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Bill To',
        $row['billto']
      ));
      $this->pdf->row(array(
        'Note',
        $row['description']
      ));
      $this->pdf->checkNewPage(10);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety(), 'Pembuat');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  
}