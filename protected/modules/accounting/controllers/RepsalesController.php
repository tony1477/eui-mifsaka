<?php
class RepsalesController extends Controller
{
  public $menuname = 'repsales';
  public function actionIndex()
  {
    $this->renderPartial('index', array());
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    if (isset($_GET['lro']) && isset($_GET['company']) && isset($_GET['sloc']) && isset($_GET['sales']) && isset($_GET['product']) && isset($_GET['startdate']) && isset($_GET['enddate'])) {
      if ($_GET['lro'] == 1) {
        $this->RincianPenjualanPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 2) {
        $this->RekapPenjualanPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 3) {
        $this->RekapPenjualanPerCustomer($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 4) {
        $this->RekapPenjualanPerBarang($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 5) {
        $this->RekapPenjualanPerArea($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 6) {
        $this->RincianReturPenjualanPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 7) {
        $this->RekapReturPenjualanPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 8) {
        $this->RekapReturPenjualanPerCustomer($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 9) {
        $this->RekapReturPenjualanPerBarang($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 10) {
        $this->RekapReturPenjualanPerArea($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 11) {
        $this->RincianPenjualanReturPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 12) {
        $this->RekapPenjualanReturPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 13) {
        $this->RekapPenjualanReturPerCustomer($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 14) {
        $this->RekapPenjualanReturPerBarang($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 15) {
        $this->RekapPenjualanReturPerArea($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['sales'] ? $_GET['sales'] : '"%%"', $_GET['product'] ? $_GET['product'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      }
    }
  }
  public function RincianPenjualanPerDokumen($companyid, $slocid, $employeeid, $productid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select f.companyid,a.amount,g.symbol,currencyrate,a.giheaderid,invoiceid,invoiceno,f.sono,d.fullname as customer,a.invoicedate,a.headernote, taxvalue,a.recordstatus,
					f.shipto as addressname,
					j.cityname,
					a.recordstatus,date_add(a.invoicedate, INTERVAL e.paydays day) as duedate,b.gino,f.sono,f.soheaderid,h.fullname as sales
					from invoice a 
					left join giheader b on b.giheaderid = a.giheaderid
					left join soheader f on f.soheaderid = b.soheaderid
					left join tax c on c.taxid = f.taxid 
					left join currency g on g.currencyid = a.currencyid
					left join addressbook d on d.addressbookid = f.addressbookid
					left join paymentmethod e on e.paymentmethodid = f.paymentmethodid
					left join employee h on h.employeeid = f.employeeid
					left join company i on i.companyid = h.companyid
					left join city j on j.cityid = i.cityid
					left join gidetail k on k.giheaderid = b.giheaderid
                    where a.invoiceno is not null and a.recordstatus = '3' and f.companyid = " . $companyid . " and h.employeeid like " . $employeeid . " and 
                    f.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Penjualan Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $sodisc      = '';
    $sql1        = 'select discvalue from sodisc z where z.soheaderid = ' . $row['soheaderid'];
    $command1    = $this->connection->createCommand($sql1);
    $dataReader1 = $command1->queryAll();
    foreach ($dataReader1 as $row1) {
      if ($sodisc == '') {
        $sodisc = Yii::app()->format->formatNumber($row1['discvalue']);
      } else {
        $sodisc = $sodisc . '+' . Yii::app()->format->formatNumber($row1['discvalue']);
      }
    }
    $this->pdf->sety($this->pdf->gety());
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(9);
      $this->pdf->text(10, $this->pdf->gety() + 3, 'No ');
      $this->pdf->text(25, $this->pdf->gety() + 3, ': ' . $row['invoiceno']);
      $this->pdf->text(140, $this->pdf->gety() + 3, $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])));
      $this->pdf->text(10, $this->pdf->gety() + 8, 'Sales ');
      $this->pdf->text(25, $this->pdf->gety() + 8, ': ' . $row['sales']);
      $this->pdf->text(140, $this->pdf->gety() + 8, 'Kepada Yth, ');
      $this->pdf->text(10, $this->pdf->gety() + 13, 'No. SO ');
      $this->pdf->text(25, $this->pdf->gety() + 13, ': ' . $row['sono']);
      $this->pdf->text(140, $this->pdf->gety() + 13, $row['customer']);
      $this->pdf->text(10, $this->pdf->gety() + 18, 'T.O.P. ');
      $this->pdf->text(25, $this->pdf->gety() + 18, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])));
      $this->pdf->text(140, $this->pdf->gety() + 18, '' . $row['addressname']);
      $sql1        = "(select d.productname,a.qty,c.uomcode,f.price,b.symbol,a.itemnote,
				(price * a.qty * ifnull(e.taxvalue,0)/100) as taxvalue
				from gidetail a
				left join sodetail f on f.sodetailid = a.sodetailid
				left join soheader g on g.soheaderid = f.soheaderid
				left join product d on d.productid = a.productid
				left join currency b on b.currencyid = f.currencyid
				left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
				left join tax e on e.taxid = g.taxid
				where a.giheaderid = '" . $row['giheaderid'] . "' group by d.productname,a.sodetailid order by a.sodetailid and a.productid=" . $productid . ")
				UNION
				(select d.productname,a.qty,c.uomcode,a.price,b.symbol,ifnull(f.itemnote,''),
				ifnull((price * a.qty * ifnull(e.taxvalue,0)/100),0) as taxvalue
				from sodetail a
				left join gidetail f on f.sodetailid = a.sodetailid
				left join soheader g on g.soheaderid = a.soheaderid
				left join product d on d.productid = a.productid
				left join currency b on b.currencyid = a.currencyid
				left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
				left join tax e on e.taxid = g.taxid
				where a.soheaderid = '" . $row['soheaderid'] . "' and d.isstock = 0 group by d.productname,a.sodetailid order by a.sodetailid and a.productid=" . $productid . ") ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->SetY($this->pdf->gety() + 20);
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
        10,
        70,
        20,
        20,
        32,
        40
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
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
          Yii::app()->format->formatCurrency(($row1['price'] * $row1['qty']) + $row1['taxvalue'], $row1['symbol'])
        ));
        $total += ($row1['price'] * $row1['qty']) + $row1['taxvalue'];
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
        'Disc (%) ' . $sodisc,
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
        'Terbilang : ' . $this->eja($bilangan[0])
      ));
      $this->pdf->row(array(
        'NOTE : ' . $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapPenjualanPerDokumen($companyid, $slocid, $employeeid, $productid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select *,(nominal-ppn) as netto
                    from
                    (select *,(select nominal*h.taxvalue/100 from tax h where h.taxid=z.taxid) as ppn
                    from
                    (select distinct b.invoiceno,b.invoicedate,d.fullname as customer,c.taxid,
                    (
                        select sum(g.price*e.qty)
                        from gidetail e
                        join sodetail g on g.sodetailid = e.sodetailid
                        where g.productid=e.productid and 
                        e.giheaderid = a.giheaderid and
                        e.slocid = " . $slocid . "
                    ) as nominal
                    from giheader a
                    join invoice b on b.giheaderid=a.giheaderid
                    join soheader c on c.soheaderid=a.soheaderid
                    join addressbook d on d.addressbookid=c.addressbookid
                    join sodisc f on f.soheaderid=c.soheaderid
                    where c.companyid = " . $companyid . " order by b.invoiceno and 
                    c.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Penjualan Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $i       = 0;
    $nominal = 0;
    $total   = 0;
    $ppn     = 0;
    $this->pdf->sety($this->pdf->gety() + 0);
    $this->pdf->setFont('Arial', 'B', 8);
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
      8,
      20,
      18,
      50,
      32,
      30,
      32
    ));
    $this->pdf->colheader = array(
      'No',
      'Dokumen',
      'Tanggal',
      'Customer',
      'Nominal',
      'Diskon',
      'Netto'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'R',
      'R',
      'R'
    );
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->SetFont('Arial', '', 8);
      $this->pdf->row(array(
        $i,
        $row['invoiceno'],
        $row['invoicedate'],
        $row['customer'],
        Yii::app()->format->formatNumber($row['nominal']),
        Yii::app()->format->formatNumber($row['ppn']),
        Yii::app()->format->formatNumber($row['netto'])
      ));
      $nominal += $row['nominal'];
      $ppn += $row['ppn'];
      $total += $row['netto'];
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->row(array(
      '',
      'Grand Total',
      '',
      '',
      Yii::app()->format->formatNumber($nominal),
      Yii::app()->format->formatNumber($ppn),
      Yii::app()->format->formatNumber($total)
    ));
    $this->pdf->Output();
  }
  public function RekapPenjualanPerCustomer($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct c.addressbookid,c.fullname 
                    from giheader a
                    join soheader b on b.soheaderid = a.soheaderid
                    join addressbook c on c.addressbookid = b.addressbookid
                    join gidetail d on d.giheaderid = a.giheaderid
                    where a.recordstatus = 3 and d.slocid like " . $slocid . " and b.companyid = " . $companyid . " and b.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by fullname";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Penjualan Per Customer';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Customer');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . $row['fullname']);
      $sql1        = "select distinct a.materialgroupid,a.materialgroupcode,a.description 
                    from materialgroup a 
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join product c on c.productid = b.productid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    where f.companyid = " . $companyid . " and d.slocid like " . $slocid . " and b.productid in
                    (select zc.productid 
                    from soheader za
                    join giheader zb on zb.soheaderid = za.soheaderid
                    join gidetail zc on zc.giheaderid = zb.giheaderid
                    where za.addressbookid = " . $row['addressbookid'] . " and zb.recordstatus = 3 and zc.slocid = b.slocid and za.companyid = " . $companyid . " and za.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      foreach ($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
        $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row1['description']);
        $sql2        = "select distinct a.productid,e.productname,f.uomcode,
                        (
                        select sum(zzb.qty)
                        from gidetail zzb 
                        join giheader zzc on zzc.giheaderid = zzb.giheaderid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        and zzc.recordstatus = 3
                        ) as qty
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid like " . $slocid . " and a.materialgroupid = " . $row1['materialgroupid'] . " and a.productid in
                        (select za.productid
                        from gidetail za 
                        join giheader zb on zb.giheaderid = za.giheaderid
                        join soheader zc on zc.soheaderid = zb.soheaderid
                        where zc.addressbookid = " . $row['addressbookid'] . " and zb.recordstatus = 3 and za.slocid = a.slocid and zc.companyid = " . $companyid . " and zc.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') order by productname";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $totalqty    = 0;
        $i           = 0;
        $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setwidths(array(
          10,
          120,
          30,
          30
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Satuan',
          'Qty'
        );
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array(
          'L',
          'L',
          'R',
          'R'
        );
        $this->pdf->setFont('Arial', '', 8);
        foreach ($dataReader2 as $row2) {
          $i += 1;
          $this->pdf->row(array(
            $i,
            $row2['productname'],
            $row2['uomcode'],
            Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row2['qty'])
          ));
          $totalqty += $row2['qty'];
        }
        $this->pdf->row(array(
          '',
          'Total -> ' . $row1['description'],
          '',
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty)
        ));
        $this->pdf->checkPageBreak(20);
      }
    }
    $this->pdf->Output();
  }
  public function RekapPenjualanPerBarang($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct a.materialgroupid,a.materialgroupcode,a.description 
                    from materialgroup a 
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join product c on c.productid = b.productid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    where f.companyid = " . $companyid . " and d.slocid like " . $slocid . " and b.productid in
                    (
                    select zc.productid 
                    from soheader za
                    join giheader zb on zb.soheaderid = za.soheaderid
                    join gidetail zc on zc.giheaderid = zb.giheaderid
                    where zb.recordstatus = 3 and zc.slocid = b.slocid and za.companyid = " . $companyid . " and za.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Penjualan Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1        = "select distinct a.productid,e.productname,f.uomcode,
                        (
                        select sum(zzb.qty)
                        from gidetail zzb 
                        join giheader zzc on zzc.giheaderid = zzb.giheaderid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        and zzc.recordstatus = 3
                        ) as qty
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid like " . $slocid . " and a.materialgroupid = " . $row['materialgroupid'] . " and a.productid in
                        (select distinct za.productid
                        from gidetail za 
                        join giheader zb on zb.giheaderid = za.giheaderid
                        join soheader zc on zc.soheaderid = zb.soheaderid
                        where zb.recordstatus = 3 and za.slocid = a.slocid and zc.companyid = " . $companyid . " and zc.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') order by productname";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        120,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty'])
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Total -> ' . $row['description'],
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapPenjualanPerArea($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct a.cityid,a.cityname
                    from city a
                    join company b on b.cityid = a.cityid
                    join soheader c on c.companyid = b.companyid
                    join giheader d on d.soheaderid = c.soheaderid
                    where d.recordstatus = 3 and b.companyid = " . $companyid . " and c.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Penjualan Per Area';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Area Penjualan');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['cityname']);
      $sql1        = "select *,(nominal-ppn) as netto
                        from
                        (select *,(select nominal*g.taxvalue/100 from tax g where g.taxid=z.taxid) as ppn
                        from
                        (select distinct a.productid,f.productname,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty,
                        (
                        select (xxa.qty*xxc.price)
                        from gireturdetail xxa
                        join gidetail xxb on xxb.gidetailid = xxa.gidetailid
                        join sodetail xxc on xxc.sodetailid = xxb.sodetailid
                        where xxa.productid = a.productid
                        and xxa.slocid = a.slocid
                        ) as nominal,
                        (
                        select ca.taxid
                        from soheader ca
                        join giheader cb on cb.soheaderid = ca.soheaderid
                        join giretur cc on cc.giheaderid = cb.giheaderid
                        join gireturdetail cd on cd.gireturid = cc.gireturid
                        where cd.productid = a.productid
                        and cd.slocid = a.slocid
                        ) as taxid
                        from productplant a
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        join city e on e.cityid = d.cityid
                        join product f on f.productid = a.productid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and e.cityid = " . $row['cityid'] . " and a.productid in
                        (select distinct za.productid
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'))z)zz";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $nominal     = 0;
      $ppn         = 0;
      $total       = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
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
        10,
        70,
        20,
        30,
        20,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'QTY',
        'Nominal',
        'PPN',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          Yii::app()->format->formatNumber($row1['nominal']),
          Yii::app()->format->formatNumber($row1['ppn']),
          Yii::app()->format->formatNumber($row1['netto'])
        ));
        $totalqty += $row1['qty'];
        $nominal += $row1['nominal'];
        $total += $row1['netto'];
      }
      $this->pdf->row(array(
        '',
        'Grand Total',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->format->formatNumber($nominal),
        Yii::app()->format->formatNumber($ppn),
        Yii::app()->format->formatNumber($total)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RincianReturPenjualanPerDokumen($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select *,(nominal-ppn) as netto
                    from 
                    (select *,sum(jumlah) as nominal,
                    (select jumlah*j.taxvalue/100 from tax j where j.taxid=z.taxid) as ppn
                    from 
                    (select a.gireturid,a.gireturno,h.fullname as customer,a.gireturdate,i.paycode,g.taxid,a.headernote,
                    (
                        select sum(b.price*c.qty) 
                        from sodetail b
                        where b.sodetailid=e.sodetailid 
                        and b.productid=c.productid
                        and b.slocid=c.slocid
                    ) as jumlah
                    from giretur a
                    left join gireturdetail c on c.gireturid=a.gireturid
                    left join product d on d.productid=c.productid
                    left join gidetail e on e.gidetailid=c.gidetailid
                    left join giheader f on f.giheaderid=a.giheaderid
                    left join soheader g on g.soheaderid=f.soheaderid
                    left join addressbook h on h.addressbookid=g.addressbookid
                    left join paymentmethod i on i.paymentmethodid=g.paymentmethodid
                    where e.slocid = " . $slocid . " and a.gireturno is not null and g.companyid = " . $companyid . " and a.gireturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Retur Penjualan Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['gireturno']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Customer');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['customer']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])));
      $this->pdf->text(130, $this->pdf->gety() + 15, 'T.O.P');
      $this->pdf->text(160, $this->pdf->gety() + 15, ': ' . $row['paycode'] . ' HARI');
      $sql1        = "select *,(qty*price) as jumlah
                        from
                        (select distinct a.productid,e.productname,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty,
                        (
                        select ba.price
                        from sodetail ba
                        join gidetail bb on bb.sodetailid = ba.sodetailid
                        join gireturdetail bc on bc.gidetailid = bb.gidetailid
                        where ba.productid = a.productid
                        and bc.slocid = a.slocid
                        ) as price,
                        (
                        select ca.itemnote
                        from gireturdetail ca
                        where ca.productid = a.productid
                        and ca.slocid = a.slocid
                        ) as itemnote
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and a.productid in
                        (select distinct za.productid
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where zb.gireturid = '" . $row['gireturid'] . "' and za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'))z";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 25);
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
        10,
        70,
        10,
        20,
        30,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Harga',
        'Jumlah',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          Yii::app()->format->formatNumber($row1['price']),
          Yii::app()->format->formatNumber($row1['jumlah']),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Keterangan : ' . $row['headernote'],
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        '',
        'NOMINAL',
        Yii::app()->format->formatNumber($row['nominal'])
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'PPN',
        Yii::app()->format->formatNumber($row['ppn'])
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'NETTO',
        Yii::app()->format->formatNumber($row['netto'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapReturPenjualanPerDokumen($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select *,(nominal-ppn) as netto
                    from
                    (select *,(select nominal*h.taxvalue/100 from tax h where h.taxid=z.taxid) as ppn
                    from
                    (select a.gireturno,a.gireturdate,d.fullname as customer,c.taxid,
                    (
                        select sum(g.price*e.qty)
                        from gireturdetail e
                        join gidetail f on f.gidetailid = e.gidetailid
                        join sodetail g on g.sodetailid = f.sodetailid
                        where g.productid=e.productid and 
                        e.gireturid = a.gireturid and
                        e.slocid = " . $slocid . "
                    ) as nominal
                    from giretur a
                    join giheader b on b.giheaderid=a.giheaderid
                    join soheader c on c.soheaderid=b.soheaderid
                    join addressbook d on d.addressbookid=c.addressbookid
                    where c.companyid = " . $companyid . " and 
                    c.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Penjualan Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $i       = 0;
    $nominal = 0;
    $total   = 0;
    $ppn     = 0;
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->setFont('Arial', 'B', 10);
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
      35,
      30,
      40,
      30,
      10,
      30
    ));
    $this->pdf->colheader = array(
      'No',
      'Dokumen',
      'Tanggal',
      'Customer',
      'Nominal',
      'PPN',
      'Total'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'R',
      'R',
      'R'
    );
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->SetFont('Arial', '', 8);
      $this->pdf->row(array(
        $i,
        $row['gireturno'],
        $row['gireturdate'],
        $row['customer'],
        Yii::app()->format->formatNumber($row['nominal']),
        Yii::app()->format->formatNumber($row['ppn']),
        Yii::app()->format->formatNumber($row['netto'])
      ));
      $nominal += $row['nominal'];
      $ppn += $row['ppn'];
      $total += $row['netto'];
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->row(array(
      '',
      'Grand Total',
      '',
      '',
      Yii::app()->format->formatNumber($nominal),
      Yii::app()->format->formatNumber($ppn),
      Yii::app()->format->formatNumber($total)
    ));
    $this->pdf->Output();
  }
  public function RekapReturPenjualanPerCustomer($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct c.addressbookid,d.fullname 
                    from giretur a
                    join giheader b on b.giheaderid = a.giheaderid
                    join soheader c on c.soheaderid = b.soheaderid
                    join addressbook d on d.addressbookid = c.addressbookid
                    join gireturdetail e on e.gireturid = a.gireturid
                    where e.slocid = " . $slocid . " and c.companyid = " . $companyid . " and c.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Penjualan Per Customer';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 10);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Customer');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . $row['fullname']);
      $sql1        = "select distinct a.materialgroupid,a.materialgroupcode,a.description 
                    from materialgroup a 
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join product c on c.productid = b.productid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    join gireturdetail g on g.slocid = b.slocid
                    where f.companyid = " . $companyid . " and d.slocid = " . $slocid . " and b.productid in
                    (select zd.productid 
                    from soheader za
                    join giheader zb on zb.soheaderid = za.soheaderid
                    join gidetail zc on zc.giheaderid = zb.giheaderid
                    join gireturdetail zd on zd.gidetailid = zc.gidetailid
                    where za.companyid = " . $companyid . " and za.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      foreach ($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
        $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row1['description']);
        $sql2        = "select distinct a.productid,e.productname,f.uomcode,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and a.materialgroupid = " . $row1['materialgroupid'] . " and a.productid in
                        (select distinct za.productid
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $totalqty    = 0;
        $i           = 0;
        $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setwidths(array(
          10,
          120,
          30,
          30
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Satuan',
          'Qty'
        );
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array(
          'L',
          'L',
          'R',
          'R'
        );
        $this->pdf->setFont('Arial', '', 8);
        foreach ($dataReader2 as $row2) {
          $i += 1;
          $this->pdf->row(array(
            $i,
            $row2['productname'],
            $row2['uomcode'],
            Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row2['qty'])
          ));
          $totalqty += $row2['qty'];
        }
        $this->pdf->row(array(
          '',
          'Total',
          '',
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty)
        ));
        $this->pdf->checkPageBreak(20);
      }
    }
    $this->pdf->Output();
  }
  public function RekapReturPenjualanPerBarang($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct a.materialgroupid,a.materialgroupcode,a.description 
                    from materialgroup a 
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join product c on c.productid = b.productid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    join gireturdetail g on g.slocid = b.slocid
                    where f.companyid = " . $companyid . " and d.slocid = " . $slocid . " and b.productid in
                    (select zd.productid 
                    from soheader za
                    join giheader zb on zb.soheaderid = za.soheaderid
                    join gidetail zc on zc.giheaderid = zb.giheaderid
                    join gireturdetail zd on zd.gidetailid = zc.gidetailid
                    where za.companyid = " . $companyid . " and zd.slocid = " . $slocid . " and za.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Penjualan Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1        = "select distinct a.productid,e.productname,f.uomcode,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and a.materialgroupid = " . $row['materialgroupid'] . " and a.productid in
                        (select distinct za.productid
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        120,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty'])
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapReturPenjualanPerArea($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct a.cityid,a.cityname
                    from city a
                    join company b on b.cityid = a.cityid
                    join soheader c on c.companyid = b.companyid
                    join giheader d on d.soheaderid = c.soheaderid
                    where d.recordstatus = 3 and b.companyid = " . $companyid . " and c.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Penjualan Per Area';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Area Penjualan');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['cityname']);
      $sql1        = "select *,(nominal-ppn) as netto
                        from
                        (select *,(select nominal*g.taxvalue/100 from tax g where g.taxid=z.taxid) as ppn
                        from
                        (select distinct a.productid,f.productname,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty,
                        (
                        select (xxa.qty*xxc.price)
                        from gireturdetail xxa
                        join gidetail xxb on xxb.gidetailid = xxa.gidetailid
                        join sodetail xxc on xxc.sodetailid = xxb.sodetailid
                        where xxa.productid = a.productid
                        and xxa.slocid = a.slocid
                        ) as nominal,
                        (
                        select ca.taxid
                        from soheader ca
                        join giheader cb on cb.soheaderid = ca.soheaderid
                        join giretur cc on cc.giheaderid = cb.giheaderid
                        join gireturdetail cd on cd.gireturid = cc.gireturid
                        where cd.productid = a.productid
                        and cd.slocid = a.slocid
                        ) as taxid
                        from productplant a
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        join city e on e.cityid = d.cityid
                        join product f on f.productid = a.productid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and e.cityid = " . $row['cityid'] . " and a.productid in
                        (select distinct za.productid
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'))z)zz";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $nominal     = 0;
      $ppn         = 0;
      $total       = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
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
        10,
        70,
        20,
        30,
        20,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'QTY',
        'Nominal',
        'PPN',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          Yii::app()->format->formatNumber($row1['nominal']),
          Yii::app()->format->formatNumber($row1['ppn']),
          Yii::app()->format->formatNumber($row1['netto'])
        ));
        $totalqty += $row1['qty'];
        $nominal += $row1['nominal'];
        $total += $row1['netto'];
      }
      $this->pdf->row(array(
        '',
        'Grand Total',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->format->formatNumber($nominal),
        Yii::app()->format->formatNumber($ppn),
        Yii::app()->format->formatNumber($total)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RincianPenjualanReturPerDokumen($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select a.gireturid,a.gireturno,f.invoiceno,d.fullname as customer,a.gireturdate,f.invoicedate,e.fullname as sales 
                    from giretur a
                    left join giheader b on b.giheaderid=a.giheaderid
                    left join soheader c on c.soheaderid=b.soheaderid
                    left join addressbook d on d.addressbookid=c.addressbookid
                    left join employee e on e.employeeid=c.employeeid
                    left join invoice f on f.giheaderid=b.giheaderid
                    left join gireturdetail g on g.gireturid = a.gireturid
                    where g.slocid = " . $slocid . " and a.gireturno is not null and c.companyid = " . $companyid . " and 
                    a.gireturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Retur Penjualan Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(20, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['gireturno']);
      $this->pdf->text(20, $this->pdf->gety() + 15, 'Reff Cust');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['invoiceno']);
      $this->pdf->text(20, $this->pdf->gety() + 20, 'Customer');
      $this->pdf->text(40, $this->pdf->gety() + 20, ': ' . $row['customer']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])));
      $this->pdf->text(130, $this->pdf->gety() + 15, 'Tgl Reff Cust');
      $this->pdf->text(160, $this->pdf->gety() + 15, ': ' . $row['invoicedate']);
      $this->pdf->text(130, $this->pdf->gety() + 20, 'Sales');
      $this->pdf->text(160, $this->pdf->gety() + 20, ': ' . $row['sales']);
      $sql1        = "select distinct a.productid,e.productname,f.uomcode,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty,
                        (
                        select ca.itemnote
                        from gireturdetail ca
                        where ca.productid = a.productid
                        and ca.slocid = a.slocid
                        ) as itemnote
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and a.productid in
                        (select distinct za.productid
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where zb.gireturid = '" . $row['gireturid'] . "' and za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        75,
        20,
        20,
        60
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'L',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Keterangan',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        ''
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapPenjualanReturPerDokumen($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select a.gireturid,a.gireturno,f.invoiceno,d.fullname as customer,a.gireturdate,f.invoicedate,e.fullname as sales 
                    from giretur a
                    left join giheader b on b.giheaderid=a.giheaderid
                    left join soheader c on c.soheaderid=b.soheaderid
                    left join addressbook d on d.addressbookid=c.addressbookid
                    left join employee e on e.employeeid=c.employeeid
                    left join invoice f on f.giheaderid=b.giheaderid
                    left join gireturdetail g on g.gireturid = a.gireturid
                    where g.slocid = " . $slocid . " and a.gireturno is not null and c.companyid = " . $companyid . " and 
                    a.gireturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Retur Penjualan Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(20, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['gireturno']);
      $this->pdf->text(20, $this->pdf->gety() + 15, 'Reff Cust');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['invoiceno']);
      $this->pdf->text(20, $this->pdf->gety() + 20, 'Customer');
      $this->pdf->text(40, $this->pdf->gety() + 20, ': ' . $row['customer']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])));
      $this->pdf->text(130, $this->pdf->gety() + 15, 'Tgl Reff Cust');
      $this->pdf->text(160, $this->pdf->gety() + 15, ': ' . $row['invoicedate']);
      $this->pdf->text(130, $this->pdf->gety() + 20, 'Sales');
      $this->pdf->text(160, $this->pdf->gety() + 20, ': ' . $row['sales']);
      $sql1        = "select distinct a.productid,e.productname,f.uomcode,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty,
                        (
                        select ca.itemnote
                        from gireturdetail ca
                        where ca.productid = a.productid
                        and ca.slocid = a.slocid
                        ) as itemnote
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and a.productid in
                        (select distinct za.productid
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where zb.gireturid = '" . $row['gireturid'] . "' and za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i           = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        75,
        20,
        20,
        60
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'L',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          $row1['itemnote']
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Keterangan',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        ''
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapPenjualanReturPerCustomer($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct c.addressbookid,c.fullname 
                    from giheader a
                    join soheader b on b.soheaderid = a.soheaderid
                    join addressbook c on c.addressbookid = b.addressbookid
                    join gidetail d on d.giheaderid = a.giheaderid
                    where a.recordstatus = 3 and d.slocid like " . $slocid . " and b.companyid = " . $companyid . " and b.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by fullname";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Penjualan Per Customer';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Customer');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . $row['fullname']);
      $sql1        = "select distinct a.materialgroupid,a.materialgroupcode,a.description 
                    from materialgroup a 
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join product c on c.productid = b.productid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    where f.companyid = " . $companyid . " and d.slocid like " . $slocid . " and b.productid in
                    (select zc.productid 
                    from soheader za
                    join giheader zb on zb.soheaderid = za.soheaderid
                    join gidetail zc on zc.giheaderid = zb.giheaderid
                    where za.addressbookid = " . $row['addressbookid'] . " and zb.recordstatus = 3 and zc.slocid = b.slocid and za.companyid = " . $companyid . " and za.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      foreach ($dataReader1 as $row1) {
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
        $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row1['description']);
        $sql2        = "select distinct a.productid,e.productname,f.uomcode,
                        (
                        select sum(zzb.qty)
                        from gidetail zzb 
                        join giheader zzc on zzc.giheaderid = zzb.giheaderid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        and zzc.recordstatus = 3
                        ) as qty
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid like " . $slocid . " and a.materialgroupid = " . $row1['materialgroupid'] . " and a.productid in
                        (select za.productid
                        from gidetail za 
                        join giheader zb on zb.giheaderid = za.giheaderid
                        join soheader zc on zc.soheaderid = zb.soheaderid
                        where zc.addressbookid = " . $row['addressbookid'] . " and zb.recordstatus = 3 and za.slocid = a.slocid and zc.companyid = " . $companyid . " and zc.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') order by productname";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        $totalqty    = 0;
        $i           = 0;
        $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setwidths(array(
          10,
          120,
          30,
          30
        ));
        $this->pdf->colheader = array(
          'No',
          'Nama Barang',
          'Satuan',
          'Qty'
        );
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array(
          'L',
          'L',
          'R',
          'R'
        );
        $this->pdf->setFont('Arial', '', 8);
        foreach ($dataReader2 as $row2) {
          $i += 1;
          $this->pdf->row(array(
            $i,
            $row2['productname'],
            $row2['uomcode'],
            Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row2['qty'])
          ));
          $totalqty += $row2['qty'];
        }
        $this->pdf->row(array(
          '',
          'Total -> ' . $row1['description'],
          '',
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty)
        ));
        $this->pdf->checkPageBreak(20);
      }
    }
    $this->pdf->Output();
  }
  public function RekapPenjualanReturPerBarang($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct a.materialgroupid,a.materialgroupcode,a.description 
                    from materialgroup a 
                    join productplant b on b.materialgroupid = a.materialgroupid
                    join product c on c.productid = b.productid
                    join sloc d on d.slocid = b.slocid
                    join plant e on e.plantid = d.plantid
                    join company f on f.companyid = e.companyid
                    where f.companyid = " . $companyid . " and d.slocid like " . $slocid . " and b.productid in
                    (
                    select zc.productid 
                    from soheader za
                    join giheader zb on zb.soheaderid = za.soheaderid
                    join gidetail zc on zc.giheaderid = zb.giheaderid
                    where zb.recordstatus = 3 and zc.slocid = b.slocid and za.companyid = " . $companyid . " and za.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Penjualan Per Barang';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Divisi');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['description']);
      $sql1        = "select distinct a.productid,e.productname,f.uomcode,
                        (
                        select sum(zzb.qty)
                        from gidetail zzb 
                        join giheader zzc on zzc.giheaderid = zzb.giheaderid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        and zzc.recordstatus = 3
                        ) as qty
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        where d.companyid = " . $companyid . " and b.slocid like " . $slocid . " and a.materialgroupid = " . $row['materialgroupid'] . " and a.productid in
                        (select distinct za.productid
                        from gidetail za 
                        join giheader zb on zb.giheaderid = za.giheaderid
                        join soheader zc on zc.soheaderid = zb.soheaderid
                        where zb.recordstatus = 3 and za.slocid = a.slocid and zc.companyid = " . $companyid . " and zc.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') order by productname";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        120,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty'])
        ));
        $totalqty += $row1['qty'];
      }
      $this->pdf->row(array(
        '',
        'Total -> ' . $row['description'],
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapPenjualanReturPerArea($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct a.cityid,a.cityname
                    from city a
                    join company b on b.cityid = a.cityid
                    join soheader c on c.companyid = b.companyid
                    join giheader d on d.soheaderid = c.soheaderid
                    where d.recordstatus = 3 and b.companyid = " . $companyid . " and c.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Retur Penjualan Per Area';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Area Penjualan');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . $row['cityname']);
      $sql1        = "select *,(nominal-ppn) as netto
                        from
                        (select *,(select nominal*g.taxvalue/100 from tax g where g.taxid=z.taxid) as ppn
                        from
                        (select distinct a.productid,f.productname,
                        (
                        select sum(zzb.qty)
                        from gireturdetail zzb 
                        join giretur zzc on zzc.gireturid = zzb.gireturid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty,
                        (
                        select (xxa.qty*xxc.price)
                        from gireturdetail xxa
                        join gidetail xxb on xxb.gidetailid = xxa.gidetailid
                        join sodetail xxc on xxc.sodetailid = xxb.sodetailid
                        where xxa.productid = a.productid
                        and xxa.slocid = a.slocid
                        ) as nominal,
                        (
                        select ca.taxid
                        from soheader ca
                        join giheader cb on cb.soheaderid = ca.soheaderid
                        join giretur cc on cc.giheaderid = cb.giheaderid
                        join gireturdetail cd on cd.gireturid = cc.gireturid
                        where cd.productid = a.productid
                        and cd.slocid = a.slocid
                        ) as taxid
                        from productplant a
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        join city e on e.cityid = d.cityid
                        join product f on f.productid = a.productid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and e.cityid = " . $row['cityid'] . " and a.productid in
                        (select distinct za.productid
                        from gireturdetail za 
                        join giretur zb on zb.gireturid = za.gireturid
                        join giheader zc on zc.giheaderid = zb.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'))z)zz";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totalqty    = 0;
      $nominal     = 0;
      $ppn         = 0;
      $total       = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 15);
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
        10,
        70,
        20,
        30,
        20,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'QTY',
        'Nominal',
        'PPN',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          Yii::app()->format->formatNumber($row1['nominal']),
          Yii::app()->format->formatNumber($row1['ppn']),
          Yii::app()->format->formatNumber($row1['netto'])
        ));
        $totalqty += $row1['qty'];
        $nominal += $row1['nominal'];
        $total += $row1['netto'];
      }
      $this->pdf->row(array(
        '',
        'Grand Total',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->format->formatNumber($nominal),
        Yii::app()->format->formatNumber($ppn),
        Yii::app()->format->formatNumber($total)
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
}