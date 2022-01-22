<?php
class ReportpurchaseorderController extends Controller {
  public $menuname = 'reportpurchaseorder';
  public function actionIndex() {
    parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $poheaderid = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $docdate           = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $supplier     = isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $headernote        = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $companyname        = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $pono              = isset($_POST['pono']) ? $_POST['pono'] : '';
    $paycode   = isset($_POST['paycode']) ? $_POST['paycode'] : '';
    $printke           = isset($_POST['printke']) ? $_POST['printke'] : '';
    $shipto            = isset($_POST['shipto']) ? $_POST['shipto'] : '';
    $billto            = isset($_POST['billto']) ? $_POST['billto'] : '';
    $companyid         = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $page              = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows              = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort              = isset($_POST['sort']) ? strval($_POST['sort']) : 'poheaderid';
    $order             = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset            = ($page - 1) * $rows;
    $result            = array();
    $row               = array();
    $cmd               = Yii::app()->db->createCommand()->select('count(1) as total')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')
      ->where("
      (coalesce(docdate,'') like :docdate) 
      and (coalesce(pono,'') like :pono) 
      and (coalesce(b.fullname,'') like :supplier) 
      and (coalesce(c.paycode,'') like :paycode) 
      and (coalesce(d.companyname,'') like :companyname) 
      and t.companyid in (".getUserObjectValues('company').")", 
      array(
        ':docdate' => '%' . $docdate . '%',
        ':pono' => '%' . $pono . '%',
        ':supplier' => '%' . $supplier . '%',
        ':paycode' => '%' . $paycode . '%',
        ':companyname' => '%' . $companyname . '%'
      ))->queryScalar();
    $result['total']   = $cmd;
    $cmd               = Yii::app()->db->createCommand()->select('t.*,a.description,b.fullname,c.paycode,d.companyname,
      e.taxcode,(
      select case when sum(z.poqty) > sum(z.qtyres) then 1 else 0 end
      from podetail z where z.poheaderid=t.poheaderid
      ) as warna')->from('poheader t')->leftjoin('purchasinggroup a', 'a.purchasinggroupid = t.purchasinggroupid')->leftjoin('addressbook b', 'b.addressbookid = t.addressbookid')->leftjoin('paymentmethod c', 'c.paymentmethodid = t.paymentmethodid')->leftjoin('company d', 'd.companyid = t.companyid')->leftjoin('tax e', 'e.taxid = t.taxid')
      ->where("
      (coalesce(docdate,'') like :docdate) 
      and (coalesce(pono,'') like :pono) 
      and (coalesce(poheaderid,'') like :poheaderid) 
      and (coalesce(b.fullname,'') like :supplier) 
      and (coalesce(c.paycode,'') like :paycode) 
      and (coalesce(d.companyname,'') like :companyname) 
      and t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':poheaderid' => '%' . $poheaderid . '%',
      ':pono' => '%' . $pono . '%',
      ':supplier' => '%' . $supplier . '%',
      ':paycode' => '%' . $paycode . '%',
      ':companyname' => '%' . $companyname . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'purchasinggroupid' => $data['purchasinggroupid'],
        'purchasinggroupcode' => $data['description'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'paymentmethodid' => $data['paymentmethodid'],
        'warna' => $data['warna'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'paycode' => $data['paycode'],
        'shipto' => $data['shipto'],
        'billto' => $data['billto'],
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
        'recordstatus' => $data['recordstatus'],
        'recordstatuspoheader' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'podetailid';
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('podetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->leftjoin('prmaterial f', 'f.prmaterialid = t.prmaterialid')->leftjoin('prheader g', 'g.prheaderid = f.prheaderid')->leftjoin('currency h', 'h.currencyid = t.currencyid')->where('poheaderid = :poheaderid', array(
      ':poheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.currencyname,g.prno,d.sloccode,h.currencyname,f.qty as prqty,c.symbol,i.taxvalue')->from('podetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->leftjoin('prmaterial f', 'f.prmaterialid = t.prmaterialid')->leftjoin('prheader g', 'g.prheaderid = f.prheaderid')->leftjoin('currency h', 'h.currencyid = t.currencyid')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('tax i', 'i.taxid = j.taxid')->where('t.poheaderid = :poheaderid', array(
      ':poheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      if ($data['qtyres'] < $data['poqty']) {
        $wqtyres = 1;
      } else {
        $wqtyres = 0;
      }
      $row[] = array(
        'podetailid' => $data['podetailid'],
        'prmaterialid' => $data['prmaterialid'],
        'prno' => $data['prno'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'wqtyres' => $wqtyres,
        'poqty' => Yii::app()->format->formatNumber($data['poqty']),
        'qtyres' => Yii::app()->format->formatNumber($data['qtyres']),
        'saldoqty' => Yii::app()->format->formatNumber($data['poqty'] - $data['qtyres']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'overdelvtol' => Yii::app()->format->formatNumber($data['overdelvtol']),
        'qtyres' => Yii::app()->format->formatNumber($data['qtyres']),
        'underdelvtol' => Yii::app()->format->formatNumber($data['underdelvtol']),
        'delvdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])),
        'netprice' => Yii::app()->format->formatCurrency($data['netprice'], iconv("UTF-8", "ISO-8859-1", $data['symbol'])),
        'total' => Yii::app()->format->formatNumber(($data['poqty'] * $data['netprice']) + ($data['taxvalue'] * $data['poqty'] * $data['netprice'] / 100), ($data['symbol'])),
        'itemtext' => $data['itemtext']
      );
    }
    $cmd      = Yii::app()->db->createCommand()->select('sum(t.poqty) as totalqty,sum(t.qtyres) as totalqtyres, sum(t.netprice*t.poqty*t.ratevalue) as totalamount,sum(t.poqty) as totalpoqty')->from('podetail t')->leftjoin('prmaterial f', 'f.prmaterialid = t.prmaterialid')->where('poheaderid = :poheaderid', array(
      ':poheaderid' => $id
    ))->queryRow();
    $footer[] = array(
      'productname' => 'Total',
      'poqty' => Yii::app()->format->formatNumber($cmd['totalqty']),
      'qtyres' => Yii::app()->format->formatNumber($cmd['totalqtyres']),
      'saldoqty' => Yii::app()->format->formatNumber($cmd['totalpoqty'] - $cmd['totalqtyres']),
      'total' => Yii::app()->format->formatNumber($cmd['totalamount'])
    );
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionComplete() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Completereportpo(:vid,:vcreatedby)';
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
    $sql = "select a.companyid,(select companyname from company zz where zz.companyid = a.companyid) as companyname,
    b.fullname, a.pono, a.docdate,b.addressbookid,a.poheaderid,c.paymentname,a.headernote,a.printke,a.poheaderid,
      ifnull(a.printke,0) as printke,a.recordstatus,a.shipto,a.billto
      from poheader a
      left join addressbook b on b.addressbookid = a.addressbookid
      left join paymentmethod c on c.paymentmethodid = a.paymentmethodid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.poheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('poheader');
    $this->pdf->AddPage('P', 'Letter');
    $this->pdf->AliasNbPages();
    $this->pdf->isprint = true;
    foreach ($dataReader as $row) {
      $sql1               = "update poheader set printke = ifnull(printke,0) + 1
        where poheaderid = " . $row['poheaderid'];
      $command1           = $this->connection->createCommand($sql1);
      $this->pdf->printke = $row['printke'];
      $command1->execute();
      $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.faxno
        from address a
        left join addresstype b on b.addresstypeid = a.addresstypeid
        left join city c on c.cityid = a.cityid
        where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $contact     = '';
      $addressname = '';
      $phoneno     = '';
      $faxno       = '';
      foreach ($dataReader1 as $row1) {
        $addressname = $row1['addressname'];
        $phoneno     = $row1['phoneno'];
        $faxno       = $row1['faxno'];
      }
      $sql2        = "select ifnull(a.addresscontactname,'') as addresscontactname, ifnull(a.phoneno,'') as phoneno, ifnull(a.mobilephone,'') as mobilephone
          from addresscontact a
          where addressbookid = " . $row['addressbookid'] . " order by addresscontactid " . " limit 1";
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      foreach ($dataReader2 as $row2) {
        $contact = $row2['addresscontactname'];
      }
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->Rect(10, 10, 202, 30);
      $this->pdf->text(15, 15, 'Supplier');
      $this->pdf->text(40, 15, ': ' . $row['fullname']);
      $this->pdf->text(15, 20, 'Attention');
      $this->pdf->text(40, 20, ': ' . $contact);
      $this->pdf->text(15, 25, 'Address');
      $this->pdf->text(40, 25, ': ' . $addressname);
      $this->pdf->text(15, 30, 'Phone');
      $this->pdf->text(40, 30, ': ' . $phoneno);
      $this->pdf->text(15, 35, 'Fax');
      $this->pdf->text(40, 35, ': ' . $faxno);
      $this->pdf->text(120, 15, 'PO No ');
      $this->pdf->text(150, 15, ': ' . $row['pono']);
      $this->pdf->text(120, 20, 'PO Date ');
      $this->pdf->text(150, 20, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $sql1        = "select *,(jumlah * (taxvalue / 100)) as ppn, jumlah + (jumlah * (taxvalue / 100)) as total
        from (select a.poheaderid,c.uomcode,a.poqty,a.delvdate,a.netprice,(a.netprice*a.poqty*a.ratevalue) as jumlah,b.productname,
        d.symbol,d.i18n,e.taxvalue,a.itemtext
        from podetail a
        left join poheader f on f.poheaderid = a.poheaderid
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
        left join currency d on d.currencyid = a.currencyid
        left join tax e on e.taxid = f.taxid
        where a.poheaderid = ".$row['poheaderid'].") z";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total = 0;$jumlah = 0;$ppn = 0;
      $this->pdf->sety($this->pdf->gety() + 30);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
      $this->pdf->setwidths(array(15,10,45,22,25,22,25,18,20));
      $this->pdf->setbordercell(array('LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB','LTRB'));
      $this->pdf->colheader = array('Qty','Units','Item', 'Unit Price','Jumlah','PPN','Total','Delivery','Remarks');
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array('R','C','L','R','R','R','R','R','R','L');
      $this->pdf->setFont('Arial','',8);
      $symbol = '';
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatCurrency($row1['poqty']),
          $row1['uomcode'],
          $row1['productname'],
          //iconv("UTF-8", "ISO-8859-1", $row1['productname']),
          Yii::app()->format->formatCurrency($row1['netprice'], iconv("UTF-8", "ISO-8859-1", $row1['symbol'])),
                 Yii::app()->format->formatCurrency($row1['jumlah'], $row1['symbol']),
                 Yii::app()->format->formatCurrency($row1['ppn'], $row1['symbol']),
                 Yii::app()->format->formatCurrency($row1['total'], $row1['symbol']),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate'])),
          $row1['itemtext']
        ));
        $jumlah = $row1['jumlah'] + $jumlah;
        $ppn = $row1['ppn'] + $ppn;
        $total = $row1['total'] + $total;
        $symbol = $row1['symbol'];
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        'Grand Total',
        Yii::app()->format->formatCurrency($jumlah,$symbol),
        Yii::app()->format->formatCurrency($ppn,$symbol),
        Yii::app()->format->formatCurrency($total,$symbol),
        '',
        ''
      ));
      $this->pdf->title = '';
      $this->pdf->checknewpage(100);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->setFont('Arial', 'BU', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'TERM OF CONDITIONS');
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setFont('Arial', 'B', 8);
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
      $this->pdf->colheader = array(
        'Item',
        'Description'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        'Payment Term',
        $row['paymentname']
      ));
      $this->pdf->row(array(
        'Kirim ke',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Tagih ke',
        $row['billto']
      ));
      $this->pdf->row(array(
        'Keterangan',
        $row['headernote']
      ));
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->CheckPageBreak(60);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Thanking you and assuring our best attention we remain.');
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Sincerrely Yours');
      $this->pdf->text(10, $this->pdf->gety() + 15, $row['companyname']);
      $this->pdf->text(135, $this->pdf->gety() + 15, 'Confirmed and Accepted by Supplier');
      $this->pdf->text(10, $this->pdf->gety() + 35, '');
      $this->pdf->text(10, $this->pdf->gety() + 36, '____________________');
      $this->pdf->text(135, $this->pdf->gety() + 36, '__________________________');
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->text(10, $this->pdf->gety() + 40, '');
      $this->pdf->setFont('Arial', 'BU', 7);
      $this->pdf->text(10, $this->pdf->gety() + 55, '#Note: Mohon tidak memberikan gift atau uang kepada staff kami#');
      $this->pdf->text(10, $this->pdf->gety() + 60, '#Print ke: ' . $row['printke']);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select purchasinggroupid,docdate,addressbookid,headernote,pono,paymentmethodid,printke,shipto,billto,companyid,recordstatus
        from poheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.poheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('purchasinggroupid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(3, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(4, 1, GetCatalog('pono'))->setCellValueByColumnAndRow(5, 1, GetCatalog('paymentmethodid'))->setCellValueByColumnAndRow(6, 1, GetCatalog('printke'))->setCellValueByColumnAndRow(7, 1, GetCatalog('shipto'))->setCellValueByColumnAndRow(8, 1, GetCatalog('billto'))->setCellValueByColumnAndRow(9, 1, GetCatalog('companyid'))->setCellValueByColumnAndRow(10, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['purchasinggroupid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(4, $i + 1, $row1['pono'])->setCellValueByColumnAndRow(5, $i + 1, $row1['paymentmethodid'])->setCellValueByColumnAndRow(6, $i + 1, $row1['printke'])->setCellValueByColumnAndRow(7, $i + 1, $row1['shipto'])->setCellValueByColumnAndRow(8, $i + 1, $row1['billto'])->setCellValueByColumnAndRow(9, $i + 1, $row1['companyid'])->setCellValueByColumnAndRow(10, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="poheader.xlsx"');
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