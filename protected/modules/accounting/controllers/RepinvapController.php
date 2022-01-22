<?php
class RepinvapController extends Controller
{
  public $menuname = 'repinvap';
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
  public function actionIndexjurnal()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchjurnal();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $invoiceapid     = isset($_POST['invoiceapid']) ? $_POST['invoiceapid'] : '';
    $invoiceno       = isset($_POST['invoiceno']) ? $_POST['invoiceno'] : '';
    $invoicedate     = isset($_POST['invoicedate']) ? $_POST['invoicedate'] : '';
    $poheaderid      = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $addressbookid   = isset($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
    $paymentmethodid = isset($_POST['paymentmethodid']) ? $_POST['paymentmethodid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $taxid           = isset($_POST['taxid']) ? $_POST['taxid'] : '';
    $grheaderid      = isset($_POST['grheaderid']) ? $_POST['grheaderid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceapid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
		->from('invoiceap t')
		->leftjoin('poheader a', 'a.poheaderid=t.poheaderid')
		->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')
		->leftjoin('currency c', 'c.currencyid=t.currencyid')
		->leftjoin('paymentmethod d', 'd.paymentmethodid=t.paymentmethodid')
		->leftjoin('tax e', 'e.taxid=t.taxid')
		->leftjoin('company f', 'f.companyid=a.companyid')
		->leftjoin('grheader g', 'g.grheaderid=t.grheaderid')
		->where("(coalesce(t.invoicedate,'') like :invoicedate) and 
			(coalesce(t.invoiceapid,'') like :invoiceapid) and 
			(coalesce(t.invoiceno,'') like :invoiceno) and 
			(coalesce(a.pono,'') like :poheaderid) and
			(coalesce(f.companyname,'') like :companyid) and
			(coalesce(e.taxcode,'') like :taxid) and
			(coalesce(g.grno,'') like :grheaderid) and
			(coalesce(b.fullname,'') like :addressbookid) and a.companyid in (".getUserObjectValues('company').")", array(
      ':invoicedate' => '%' . $invoicedate . '%',
      ':invoiceapid' => '%' . $invoiceapid . '%',
      ':invoiceno' => '%' . $invoiceno . '%',
      ':poheaderid' => '%' . $poheaderid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':taxid' => '%' . $taxid . '%',
      ':grheaderid' => '%' . $grheaderid . '%',
      ':addressbookid' => '%' . $addressbookid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.pono,b.fullname as supplier,c.currencyname,d.paycode,g.grno,
		e.taxcode,f.companyname')->from('invoiceap t')
		->leftjoin('poheader a', 'a.poheaderid=t.poheaderid')
		->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')
		->leftjoin('currency c', 'c.currencyid=t.currencyid')
		->leftjoin('paymentmethod d', 'd.paymentmethodid=t.paymentmethodid')
		->leftjoin('tax e', 'e.taxid=t.taxid')
		->leftjoin('company f', 'f.companyid=a.companyid')
		->leftjoin('grheader g', 'g.grheaderid=t.grheaderid')
		->where("(coalesce(t.invoicedate,'') like :invoicedate) and 
			(coalesce(t.invoiceapid,'') like :invoiceapid) and 
			(coalesce(t.invoiceno,'') like :invoiceno) and 
			(coalesce(a.pono,'') like :poheaderid) and
			(coalesce(f.companyname,'') like :companyid) and
			(coalesce(e.taxcode,'') like :taxid) and
			(coalesce(g.grno,'') like :grheaderid) and
			(coalesce(b.fullname,'') like :addressbookid) and a.companyid in (".getUserObjectValues('company').")", array(
      ':invoicedate' => '%' . $invoicedate . '%',
      ':invoiceapid' => '%' . $invoiceapid . '%',
      ':invoiceno' => '%' . $invoiceno . '%',
      ':poheaderid' => '%' . $poheaderid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':taxid' => '%' . $taxid . '%',
      ':grheaderid' => '%' . $grheaderid . '%',
      ':addressbookid' => '%' . $addressbookid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapid' => $data['invoiceapid'],
        'invoiceno' => $data['invoiceno'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'grheaderid' => $data['grheaderid'],
        'grno' => $data['grno'],
        'addressbookid' => $data['addressbookid'],
        'supplier' => $data['supplier'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
        'taxno' => $data['taxno'],
        'taxdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['taxdate'])),
        'receiptdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['receiptdate'])),
        'recordstatus' => $data['recordstatus'],
        'recordstatusinvoiceap' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchMaterial()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceapmatid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceapmat t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('podetail b', 'b.podetailid=t.podetailid')->leftjoin('grdetail c', 'c.grdetailid=t.grdetailid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.poqty,c.qty as grqty,d.uomcode,b.ratevalue*(b.netprice+(select b.netprice*b.ratevalue*k.taxvalue from poheader j join tax k on k.taxid=j.taxid where j.poheaderid=b.poheaderid)) as price,b.ratevalue*c.qty*(b.netprice+(select b.netprice*b.ratevalue*k.taxvalue from poheader j join tax k on k.taxid=j.taxid where j.poheaderid=b.poheaderid)) as jumlah')->from('invoiceapmat t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('podetail b', 'b.podetailid=t.podetailid')->leftjoin('grdetail c', 'c.grdetailid=t.grdetailid')->leftjoin('unitofmeasure d', 'd.unitofmeasureid=t.uomid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapmatid' => $data['invoiceapmatid'],
        'invoiceapid' => $data['invoiceapid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'productname' => $data['productname'],
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'podetailid' => $data['podetailid'],
        'grdetailid' => $data['grdetailid'],
        'poqty' => Yii::app()->format->formatNumber($data['poqty']),
        'grqty' => Yii::app()->format->formatNumber($data['grqty']),
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'jumlah' => Yii::app()->format->formatCurrency($data['jumlah']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $cmd             = Yii::app()->db->createCommand()->select('sum(b.poqty) as poqty,sum(c.qty) as grqty,sum(b.ratevalue*c.qty*(b.netprice+(select b.netprice*b.ratevalue*k.taxvalue from poheader j join tax k on k.taxid=j.taxid where j.poheaderid=b.poheaderid))) as jumlah')->from('invoiceapmat t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('podetail b', 'b.podetailid=t.podetailid')->leftjoin('grdetail c', 'c.grdetailid=t.grdetailid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->queryRow();
		$footer[] = array(
      'productname' => 'Total',
      'poqty' => Yii::app()->format->formatNumber($cmd['poqty']),
      'grqty' => Yii::app()->format->formatNumber($cmd['grqty']),
			'',
      'jumlah' => Yii::app()->format->formatNumber($cmd['jumlah']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchJurnal()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'debet';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoiceapjurnal t')->join('account a', 'a.accountid=t.accountid')->join('currency b', 'b.currencyid=t.currencyid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountname,b.currencyname')->from('invoiceapjurnal t')->join('account a', 'a.accountid=t.accountid')->join('currency b', 'b.currencyid=t.currencyid')->where('invoiceapid = :invoiceapid', array(
      ':invoiceapid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceapjurnalid' => $data['invoiceapjurnalid'],
        'invoiceapid' => $data['invoiceapid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debet' => Yii::app()->format->formatNumber($data['debet']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select journalno,invoiceapid,invoiceno,f.pono,fullname,amount,symbol,currencyrate,a.invoicedate,concat('Pencatatan Invoice Supplier No ',invoiceno) as headernote, taxvalue,a.recordstatus,
	   (select addressname from address e where e.addressbookid = f.addressbookid limit 1) as addressname,
	   (select cityname from address e left join city f on f.cityid = e.cityid where e.addressbookid = f.addressbookid limit 1) as cityname
		from invoiceap a 
		left join poheader f on f.poheaderid = a.poheaderid
		left join currency b on b.currencyid = a.currencyid 
		left join tax c on c.taxid = a.taxid 
		left join addressbook d on d.addressbookid = f.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.invoiceapid in (" . $_GET['id'] . ")";
    }
    $sql              = $sql . " order by invoiceapid ";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = 'Journal Adjustment';
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'PO No: ' . $row['pono']);
      $this->pdf->text(120, $this->pdf->gety() + 5, 'Tanggal: ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])));
      $this->pdf->text(15, $this->pdf->gety() + 10, 'J.NO: ' . $row['journalno']);
      $this->pdf->text(120, $this->pdf->gety() + 10, 'Supplier: ' . $row['fullname']);
      $sql1        = "select accountcode, accountname,debet,credit,a.currencyid,currencyrate,a.description,symbol,e.plantcode
        from invoiceapjurnal a
		left join currency b on b.currencyid = a.currencyid
		left join account d on d.accountid = a.accountid 
        left join plant e on e.plantid = a.plantid 
        where invoiceapid = " . $row['invoiceapid'] . " order by debet desc ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->SetY($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        40,
        30,
        30,
        10,
        50
      ));
      $this->pdf->colheader = array(
        'Account Code',
        'Account Name',
        'Debit',
        'Credit',
        'Rate',
        'Description'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'L'
      );
      $debit                     = 0;
      $credit                    = 0;
      foreach ($dataReader1 as $row1) {
        $debit  = $debit + ($row1['debet'] * $row1['currencyrate']);
        $credit = $credit + ($row1['credit'] * $row1['currencyrate']);
        $this->pdf->row(array(
          $row1['accountcode'],
          $row1['accountname'].' '.$row1['plantcode'],
          Yii::app()->format->formatCurrency($row1['debet']),
          Yii::app()->format->formatCurrency($row1['credit']),
          Yii::app()->format->formatCurrency($row1['currencyrate']),
          $row1['description']
        ));
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatCurrency($debit),
        Yii::app()->format->formatCurrency($credit),
        '',
        ''
      ));
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->setwidths(array(
        15,
        170
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 1);
      $this->pdf->setwidths(array(
        15,
        170
      ));
      $this->pdf->row(array(
        'Nilai',
        Yii::app()->numberFormatter->formatCurrency($row['amount'], $row['symbol'])
      ));
      $this->pdf->checkNewPage(20);
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 45, 'Prepared By');
      $this->pdf->text(10, $this->pdf->gety() + 75, '__________________');
      $this->pdf->text(90, $this->pdf->gety() + 45, 'Approved By');
      $this->pdf->text(90, $this->pdf->gety() + 75, '__________________');
      $this->pdf->text(150, $this->pdf->gety() + 45, 'Received By');
      $this->pdf->text(150, $this->pdf->gety() + 75, '__________________');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select invoiceno,invoicedate,poheaderid,addressbookid,amount,currencyid,currencyrate,paymentmethodid,taxid,taxno,taxdate,recordstatus
				from invoiceap a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.invoiceapid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('invoiceno'))->setCellValueByColumnAndRow(1, 1, GetCatalog('invoicedate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('poheaderid'))->setCellValueByColumnAndRow(3, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('amount'))->setCellValueByColumnAndRow(5, 1, GetCatalog('currencyid'))->setCellValueByColumnAndRow(6, 1, GetCatalog('currencyrate'))->setCellValueByColumnAndRow(7, 1, GetCatalog('paymentmethodid'))->setCellValueByColumnAndRow(8, 1, GetCatalog('taxid'))->setCellValueByColumnAndRow(9, 1, GetCatalog('taxno'))->setCellValueByColumnAndRow(10, 1, GetCatalog('taxdate'))->setCellValueByColumnAndRow(11, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['invoiceno'])->setCellValueByColumnAndRow(1, $i + 1, $row1['invoicedate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['poheaderid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['amount'])->setCellValueByColumnAndRow(5, $i + 1, $row1['currencyid'])->setCellValueByColumnAndRow(6, $i + 1, $row1['currencyrate'])->setCellValueByColumnAndRow(7, $i + 1, $row1['paymentmethodid'])->setCellValueByColumnAndRow(8, $i + 1, $row1['taxid'])->setCellValueByColumnAndRow(9, $i + 1, $row1['taxno'])->setCellValueByColumnAndRow(10, $i + 1, $row1['taxdate'])->setCellValueByColumnAndRow(11, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="invoiceap.xlsx"');
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
