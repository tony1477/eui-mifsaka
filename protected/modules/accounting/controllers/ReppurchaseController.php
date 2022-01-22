<?php
class ReppurchaseController extends Controller
{
  public $menuname = 'reppurchase';
  public function actionIndex()
  {
    $this->renderPartial('index', array());
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    if (isset($_GET['lro']) && isset($_GET['company']) && isset($_GET['sloc']) && isset($_GET['startdate']) && isset($_GET['enddate'])) {
      if ($_GET['lro'] == 1) {
        $this->RincianPembelianPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 2) {
        $this->RekapPembelianPerDokumen($_GET['company'], $_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 3) {
        $this->RekapPembelianPerCustomer($_GET['company'], $_GET['sloc'], $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 4) {
        $this->RekapPembelianPerBarang($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 5) {
        $this->RekapPembelianPerArea($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 6) {
        $this->RincianReturPembelianPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 7) {
        $this->RekapReturPembelianPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 8) {
        $this->RekapReturPembelianPerCustomer($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 9) {
        $this->RekapReturPembelianPerBarang($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 10) {
        $this->RekapReturPembelianPerArea($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 11) {
        $this->RincianPembelianReturPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 12) {
        $this->RekapPembelianReturPerDokumen($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 13) {
        $this->RekapPembelianReturPerCustomer($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 14) {
        $this->RekapPembelianReturPerBarang($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      } else if ($_GET['lro'] == 15) {
        $this->RekapPembelianReturPerArea($_GET['company'], $_GET['sloc'] ? $_GET['sloc'] : '"%%"', $_GET['startdate'], $_GET['enddate']);
      }
    }
  }
  public function RincianPembelianPerDokumen($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select *,(nominal-ppn) as netto
					from
					(select *,sum(jumlah) as nominal,(select jumlah*j.taxvalue/100 from tax j where j.taxid=z.taxid) as ppn
					from 
					(select a.invoiceid,a.invoiceno,h.fullname as customer,a.invoicedate,i.paycode,g.taxid,a.headernote,
					(select sum(b.price*c.qty) 
					from sodetail b
					where b.sodetailid=c.sodetailid and b.productid=c.productid and b.slocid=c.slocid) as jumlah
					from invoice a
					left join giheader e on e.giheaderid = a.giheaderid
					left join gidetail c on c.giheaderid=a.giheaderid
					left join product d on d.productid=c.productid
					left join soheader g on g.soheaderid=e.soheaderid
					left join addressbook h on h.addressbookid=g.addressbookid
					left join paymentmethod i on i.paymentmethodid=g.paymentmethodid
					where c.slocid = " . $slocid . " and a.invoiceno is not null and g.companyid = " . $companyid . " 
					and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Pembelian Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['invoiceno']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Customer');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['customer']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])));
      $this->pdf->text(130, $this->pdf->gety() + 15, 'T.O.P');
      $this->pdf->text(160, $this->pdf->gety() + 15, ': ' . $row['paycode'] . ' HARI');
      $sql1        = "select *,(qty*price) as jumlah
								from
                        (select distinct a.productid,e.productname,(
                        select sum(zzb.qty)
                        from gidetail zzb 
                        join giheader zzc on zzc.giheaderid = zzb.giheaderid
                        where zzb.productid = a.productid
                        and zzb.slocid = a.slocid
                        ) as qty,i.price,g.itemnote                        
                        from productplant a
                        join product e on e.productid = a.productid
                        join unitofmeasure f on f.unitofmeasureid = a.unitofissue
                        join sloc b on b.slocid = a.slocid
                        join plant c on c.plantid = b.plantid
                        join company d on d.companyid = c.companyid
                        join gidetail g on g.productid = a.productid
                        join giheader h on h.giheaderid = g.giheaderid
                        join sodetail i on i.sodetailid = g.sodetailid
                        where d.companyid = " . $companyid . " and b.slocid = " . $slocid . " and a.productid in
                        (select distinct za.productid
                        from gidetail za                        
                        join giheader zc on zc.giheaderid = za.giheaderid
                        join soheader zd on zd.soheaderid = zc.soheaderid
                        where zc.giheaderid = '" . $row['giheaderid'] . "' and za.slocid = a.slocid and zd.companyid = " . $companyid . " and zd.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
			and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') ) z";
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
  public function RekapPembelianPerDokumen($companyid, $slocid, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select *,(nominal-ppn) as netto
                    from
                    (select *,(select nominal*h.taxvalue/100 from tax h where h.taxid=z.taxid) as ppn
                    from
                    (select a.gino,a.gidate,d.fullname as customer,c.taxid,
                    (
                        select sum(g.price*e.qty)
                        from gidetail e                        
                        join sodetail g on g.sodetailid = e.sodetailid
                        where g.productid=e.productid and 
                        e.giheaderid = a.giheaderid and
                        e.slocid = " . $slocid . "
                    ) as nominal
                    from giheader a                    
                    join soheader c on c.soheaderid=a.soheaderid
                    join addressbook d on d.addressbookid=c.addressbookid
                    where c.companyid = " . $companyid . " and 
                    c.sodate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z) zz";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Pembelian Per Dokumen';
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
        $row['gino'],
        $row['gidate'],
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
  public function RekapPembelianPerCustomer($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Pembelian Per Customer';
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
  public function RekapPembelianPerBarang($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Pembelian Per Barang';
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
  public function RekapPembelianPerArea($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Retur Pembelian Per Area';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Area Pembelian');
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
  public function RincianReturPembelianPerDokumen($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rincian Retur Pembelian Per Dokumen';
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
  public function RekapReturPembelianPerDokumen($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Retur Pembelian Per Dokumen';
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
  public function RekapReturPembelianPerCustomer($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Retur Pembelian Per Customer';
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
  public function RekapReturPembelianPerBarang($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Retur Pembelian Per Barang';
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
  public function RekapReturPembelianPerArea($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Retur Pembelian Per Area';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Area Pembelian');
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
  public function RincianPembelianReturPerDokumen($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rincian Retur Pembelian Per Dokumen';
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
  public function RekapPembelianReturPerDokumen($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rincian Retur Pembelian Per Dokumen';
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
  public function RekapPembelianReturPerCustomer($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Pembelian Per Customer';
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
  public function RekapPembelianReturPerBarang($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Pembelian Per Barang';
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
  public function RekapPembelianReturPerArea($companyid, $slocid, $startdate, $enddate)
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
    $this->pdf->title    = 'Rekap Retur Pembelian Per Area';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Area Pembelian');
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