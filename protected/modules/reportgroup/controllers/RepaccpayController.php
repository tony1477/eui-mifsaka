<?php
class RepaccpayController extends Controller
{
  public $menuname = 'repaccpay';
  public function actionIndex()
  {
    $this->renderPartial('index', array());
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    if (isset($_GET['lro']) && isset($_GET['company']) && isset($_GET['sloc']) && isset($_GET['product']) && isset($_GET['supplier']) && isset($_GET['invoice']) && isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['per'])) {
      if ($_GET['lro'] == 99) {
        $this->wamessage($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 1) {
        $this->RincianBiayaEkspedisiPerDokumen($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 2) {
        $this->RekapBiayaEkspedisiPerDokumen($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 3) {
        $this->RekapBiayaEkspedisiPerBarang($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 4) {
        $this->RincianPembayaranHutangPerDokumen($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 5) {
        $this->KartuHutang($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 6) {
        $this->RekapHutangPerSupplier($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 7) {
        $this->RincianPembeliandanReturBeliBelumLunas($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 8) {
        $this->RincianUmurHutangperSTTB($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 9) {
        $this->RekapUmurHutangperSupplier($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 10) {
        $this->RekapInvoiceAPPerDokumenBelumStatusMax($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 11) {
        $this->RekapPermohonanPembayaranPerDokumenBelumStatusMax($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 12) {
        $this->RekapNotaReturPembelianPerDokumenBelumStatusMax($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      }
    }
  }
  public function wamessage($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    parent::actionDownload();
    //$this->no_result();

        $companyid=21;
        $startdate = '2020-02-01';
        $enddate = '2020-02-24';
        
    $connection = Yii::app()->db;
    $this->pdf->title='MONITORING REPORT';
    $datetime = new DateTime(date($enddate));

    $this->pdf->subtitle='Dari Tgl :  '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).'  s/d  '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P',array(210,310));

    $this->pdf->SetFont('Arial','b',10);
    $this->pdf->sety($this->pdf->gety()+5);

    $sql = "select a.materialgroupid,a.description
              from materialgroup a
              where a.recordstatus = 1 and a.isfg = 1
    ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
      
    $sqlcompanyname = 'select companyname from company where companyid='.$companyid;
    $companyname = Yii::app()->db->createCommand($sqlcompanyname)->queryScalar();

    $this->pdf->text(10,$this->pdf->gety(),'1. QTY (PENJUALAN - RETUR) vs QTY PRODUKSI');
    $this->pdf->SetFont('Arial','',10);
    $this->pdf->sety($this->pdf->gety()+3);
    $this->pdf->colalign = array('C','C','C','C','C','C','C');
    $this->pdf->setwidths(array(10,94,17,22,10,17,22));
    $this->pdf->colheader = array('No','Material Group','Hari Ini','Kumulatif','VS','Hari Ini','Kumulatif');
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array('C','L','R','R','C','R','R');  
    $i=0;

    foreach($dataReader as $row)
    {
      $sql1 = "select ifnull(sum(qty),0) as qty
              from (select sum(qty) as qty,sum(nett) as netto
              from (select distinct b3.gidetailid,b3.qty,
              (select getamountdiscso(c1.soheaderid,c1.sodetailid,c0.qty)
              from gidetail c0 
              join sodetail c1 on c1.sodetailid = c0.sodetailid
              where c0.giheaderid = b1.giheaderid and c0.productid = b3.productid and c0.gidetailid=b3.gidetailid) as nett
              from invoice b0 
              join giheader b1 on b1.giheaderid = b0.giheaderid
              join sodetail b2 on b2.soheaderid = b1.soheaderid
              join gidetail b3 on b3.giheaderid = b1.giheaderid
              join productplant b4 on b4.productid = b3.productid and b4.slocid=b2.slocid
              where b0.recordstatus = 3 and b0.invoiceno is not null and b1.companyid = {$companyid} and b0.invoiceno is not null and b4.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and b0.invoicedate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
              union
              select sum(qty) as qty,sum(nett) as netto from 
              (select distinct d0.notagirproid,(-1*d0.qty) as qty,(-1*d0.qty*d0.price) as nett
              from notagirpro d0
              join notagir d1 on d1.notagirid=d0.notagirid
              join gireturdetail d2 on d2.gireturdetailid=d0.gireturdetailid
              join giretur d3 on d3.gireturid=d1.gireturid
              join gidetail d4 on d4.gidetailid=d2.gidetailid
              join giheader d5 on d5.giheaderid=d3.giheaderid
              join sodetail d6 on d6.sodetailid=d4.sodetailid
              join productplant d7 on d7.productid=d0.productid and d7.slocid=d0.slocid
              where d5.companyid = {$companyid} and d1.recordstatus = 3 and d7.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and d3.gireturdate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."') z) zz
      ";
      $row1 = Yii::app()->db->createCommand($sql1)->queryScalar();
      
      $sql2 = "select ifnull(sum(qty),0) as kumqty
              from (select sum(qty) as qty,sum(nett) as netto
              from (select distinct b3.gidetailid,b3.qty,
              (select getamountdiscso(c1.soheaderid,c1.sodetailid,c0.qty)
              from gidetail c0 
              join sodetail c1 on c1.sodetailid = c0.sodetailid
              where c0.giheaderid = b1.giheaderid and c0.productid = b3.productid and c0.gidetailid=b3.gidetailid) as nett
              from invoice b0 
              join giheader b1 on b1.giheaderid = b0.giheaderid
              join sodetail b2 on b2.soheaderid = b1.soheaderid
              join gidetail b3 on b3.giheaderid = b1.giheaderid
              join productplant b4 on b4.productid = b3.productid and b4.slocid=b2.slocid
              where b0.recordstatus = 3 and b0.invoiceno is not null and b1.companyid = {$companyid} and b0.invoiceno is not null and b4.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and b0.invoicedate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
              union
              select sum(qty) as qty,sum(nett) as netto from 
              (select distinct d0.notagirproid,(-1*d0.qty) as qty,(-1*d0.qty*d0.price) as nett
              from notagirpro d0
              join notagir d1 on d1.notagirid=d0.notagirid
              join gireturdetail d2 on d2.gireturdetailid=d0.gireturdetailid
              join giretur d3 on d3.gireturid=d1.gireturid
              join gidetail d4 on d4.gidetailid=d2.gidetailid
              join giheader d5 on d5.giheaderid=d3.giheaderid
              join sodetail d6 on d6.sodetailid=d4.sodetailid
              join productplant d7 on d7.productid=d0.productid and d7.slocid=d0.slocid
              where d5.companyid = {$companyid} and d1.recordstatus = 3 and d7.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and d3.gireturdate between 
              '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
              and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."') z) zz
      ";
      $row2 = Yii::app()->db->createCommand($sql2)->queryScalar();
      
      $sql3 = "select ifnull(sum(qtyoutput),0) as qtyoutput
              from productoutputfg a
              join productoutput b on b.productoutputid=a.productoutputid
              join productplant c on c.productid=a.productid and c.slocid=a.slocid
              where b.companyid = {$companyid} and b.recordstatus = 3 and b.productoutputdate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' and c.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']})
      ";
      $row3 = Yii::app()->db->createCommand($sql3)->queryScalar();
      
      $sql4 = "select ifnull(sum(qtyoutput),0) as kumqtyoutput
              from productoutputfg a
              join productoutput b on b.productoutputid=a.productoutputid
              join productplant c on c.productid=a.productid and c.slocid=a.slocid
              where b.companyid = {$companyid} and b.recordstatus = 3 and b.productoutputdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' and c.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']})
      ";
      $row4 = Yii::app()->db->createCommand($sql4)->queryScalar();
      
      $cmd1 = "select b.addressbookid
              from addressbook b
              where b.fullname = (select replace(a.companyname,'TRADING','PRODUKSI')
              from company a
              where a.companyid = {$companyid} and a.companyname like '%TRADING')
      ";
      $addressbook = Yii::app()->db->createCommand($cmd1)->queryScalar();
      
      $cmd = "select ifnull(count(1),0)
                from company a
                where a.companyid = {$companyid} and a.companyname like '%trading'
      ";
      $company = Yii::app()->db->createCommand($cmd)->queryScalar();
      
      if ($company == 0)
      {
        $row7 = $row3;
        $row8 = $row4;
      }
      else
      {
        $sql5 = "select ifnull(sum(a.qty),0) as grqty
                from productstockdet a
                join productplant b on b.productid=a.productid and b.slocid=a.slocid
                join grheader c on c.grno=a.referenceno
                join sloc d on d.slocid=a.slocid
                join plant e on e.plantid=d.plantid and e.companyid=c.companyid
                join poheader f on f.poheaderid=c.poheaderid
                where c.recordstatus=3 and c.companyid = {$companyid} and f.addressbookid = {$addressbook} and a.transdate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' and b.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']})
        ";
        $row5 = Yii::app()->db->createCommand($sql5)->queryScalar();

        $sql6 = "select ifnull(sum(a.qty),0) as kumgrqty
                from productstockdet a
                join productplant b on b.productid=a.productid and b.slocid=a.slocid
                join grheader c on c.grno=a.referenceno
                join sloc d on d.slocid=a.slocid
                join plant e on e.plantid=d.plantid and e.companyid=c.companyid
                join poheader f on f.poheaderid=c.poheaderid
                where c.recordstatus=3 and c.companyid = {$companyid} and f.addressbookid = {$addressbook} and a.transdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' and b.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']})
        ";
        $row6 = Yii::app()->db->createCommand($sql6)->queryScalar();
        
        $row7 = $row5;
        $row8 = $row6;
      }

      $i+=1;
      $this->pdf->setFont('Arial','',10);
      $this->pdf->row(array(
        $i,$row['description'],
        Yii::app()->format->formatCurrency($row1),
        Yii::app()->format->formatCurrency($row2),
        '',
        Yii::app()->format->formatCurrency($row7),
        Yii::app()->format->formatCurrency($row8),
      ));
      
    }

    $this->pdf->sety($this->pdf->gety()+5);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->text(10,$this->pdf->gety(),'2. NILAI PENJUALAN - RETUR');
    $this->pdf->SetFont('Arial','',10);
    $this->pdf->sety($this->pdf->gety()+3);
    $this->pdf->colalign = array('C','C','C','C');
    $this->pdf->setwidths(array(10,94,35,40));
    $this->pdf->colheader = array('No','Material Group','Hari Ini','Kumulatif');
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array('C','L','R','R');  
    $i=0;$totalnilai=0;$totalkumnilai=0;
    
    foreach($dataReader as $row)
    {
      $sql1 = "select ifnull(sum(netto),0) as qty
              from (select sum(qty) as qty,sum(nett) as netto
              from (select distinct b3.gidetailid,b3.qty,
              (select getamountdiscso(c1.soheaderid,c1.sodetailid,c0.qty)
              from gidetail c0 
              join sodetail c1 on c1.sodetailid = c0.sodetailid
              where c0.giheaderid = b1.giheaderid and c0.productid = b3.productid and c0.gidetailid=b3.gidetailid) as nett
              from invoice b0 
              join giheader b1 on b1.giheaderid = b0.giheaderid
              join sodetail b2 on b2.soheaderid = b1.soheaderid
              join gidetail b3 on b3.giheaderid = b1.giheaderid
              join productplant b4 on b4.productid = b3.productid and b4.slocid=b2.slocid
              where b0.recordstatus = 3 and b0.invoiceno is not null and b1.companyid = {$companyid} and b0.invoiceno is not null and b4.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and b0.invoicedate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
              union
              select sum(qty) as qty,sum(nett) as netto from 
              (select distinct d0.notagirproid,(-1*d0.qty) as qty,(-1*d0.qty*d0.price) as nett
              from notagirpro d0
              join notagir d1 on d1.notagirid=d0.notagirid
              join gireturdetail d2 on d2.gireturdetailid=d0.gireturdetailid
              join giretur d3 on d3.gireturid=d1.gireturid
              join gidetail d4 on d4.gidetailid=d2.gidetailid
              join giheader d5 on d5.giheaderid=d3.giheaderid
              join sodetail d6 on d6.sodetailid=d4.sodetailid
              join productplant d7 on d7.productid=d0.productid and d7.slocid=d0.slocid
              where d5.companyid = {$companyid} and d1.recordstatus = 3 and d7.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and d3.gireturdate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."') z) zz
      ";
      $row1 = Yii::app()->db->createCommand($sql1)->queryScalar();
      
      $sql2 = "select ifnull(sum(netto),0) as kumqty
              from (select sum(qty) as qty,sum(nett) as netto
              from (select distinct b3.gidetailid,b3.qty,
              (select getamountdiscso(c1.soheaderid,c1.sodetailid,c0.qty)
              from gidetail c0 
              join sodetail c1 on c1.sodetailid = c0.sodetailid
              where c0.giheaderid = b1.giheaderid and c0.productid = b3.productid and c0.gidetailid=b3.gidetailid) as nett
              from invoice b0 
              join giheader b1 on b1.giheaderid = b0.giheaderid
              join sodetail b2 on b2.soheaderid = b1.soheaderid
              join gidetail b3 on b3.giheaderid = b1.giheaderid
              join productplant b4 on b4.productid = b3.productid and b4.slocid=b2.slocid
              where b0.recordstatus = 3 and b0.invoiceno is not null and b1.companyid = {$companyid} and b0.invoiceno is not null and b4.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and b0.invoicedate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
              union
              select sum(qty) as qty,sum(nett) as netto from 
              (select distinct d0.notagirproid,(-1*d0.qty) as qty,(-1*d0.qty*d0.price) as nett
              from notagirpro d0
              join notagir d1 on d1.notagirid=d0.notagirid
              join gireturdetail d2 on d2.gireturdetailid=d0.gireturdetailid
              join giretur d3 on d3.gireturid=d1.gireturid
              join gidetail d4 on d4.gidetailid=d2.gidetailid
              join giheader d5 on d5.giheaderid=d3.giheaderid
              join sodetail d6 on d6.sodetailid=d4.sodetailid
              join productplant d7 on d7.productid=d0.productid and d7.slocid=d0.slocid
              where d5.companyid = {$companyid} and d1.recordstatus = 3 and d7.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and d3.gireturdate between 
              '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
              and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."') z) zz
      ";
      $row2 = Yii::app()->db->createCommand($sql2)->queryScalar();

      $i+=1;
      $this->pdf->setFont('Arial','',10);
      $this->pdf->row(array(
        $i,$row['description'],
        Yii::app()->format->formatCurrency($row1/$per),
        Yii::app()->format->formatCurrency($row2/$per),
      ));
      $totalnilai += $row1/$per;
      $totalkumnilai += $row2/$per;
    }
    $this->pdf->setFont('Arial','B',10);
    $this->pdf->row(array(
      '','TOTAL NILAI PENJUALAN - RETUR ALL PRODUK >>>',
      Yii::app()->format->formatCurrency($totalnilai),
      Yii::app()->format->formatCurrency($totalkumnilai),
    ));
    
    $this->pdf->CheckNewPage(5);

        $this->pdf->sety($this->pdf->gety()+5);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->text(10,$this->pdf->gety(),'3. PENCAPAIN SALES ');
    $this->pdf->SetFont('Arial','',10);
    //$first_month = '';
    $month = date('m',strtotime($startdate));
    $year = date('Y',strtotime($startdate));
    $day = $year.'-'.$month.'-01';
        //$this->pdf->setY($this->pdf->getY()+5);
      
    $sqlplant = "select plantid,plantcode,description
              from plant where companyid = ".$companyid;
    $plant = Yii::app()->db->createCommand($sqlplant)->queryAll();
    $k=1;
    foreach($plant as $rows)
    {
        $sqlpenc = "select sum(netto1) as netto, sum(netto2) as netto2, invoicedate from
              (select fullname,sum(nom) as nominal,(sum(nom)-sum(nett1)) as disc,sum(nett1) as netto1, sum(nett2) netto2, invoicedate from
              (select distinct ss.gidetailid,a.invoiceno,a.invoicedate,d.fullname,i.productname,k.uomcode,ss.qty,a.headernote,
              (select xx.price from sodetail xx where xx.sodetailid=ss.sodetailid) as price,
              (ss.qty*(select xx.price from sodetail xx where xx.sodetailid=ss.sodetailid)) as nom,
              (select getamountdiscso(zza.soheaderid,zza.sodetailid,zzb.qty)
              from gidetail zzb 
              join sodetail zza on zza.sodetailid = zzb.sodetailid
              where zzb.giheaderid = b.giheaderid and zzb.productid = i.productid and zzb.gidetailid=ss.gidetailid) as nett1,
                              ifnull((select getamountdiscso(zza.soheaderid,zza.sodetailid,zzb.qty)
              from gidetail zzb 
              join sodetail zza on zza.sodetailid = zzb.sodetailid
                              join giheader zzc on zzc.giheaderid = zzb.giheaderid
                              join invoice zzd on zzd.giheaderid = zzc.giheaderid
              where zzb.giheaderid = b.giheaderid and zzb.productid = i.productid and zzb.gidetailid=ss.gidetailid
                              and zzd.invoicedate = '".date(Yii::app()->params['datetodb'],strtotime($enddate))."'),0) as nett2
              from invoice a 
              join giheader b on b.giheaderid = a.giheaderid
              join soheader c on c.soheaderid = b.soheaderid
              join addressbook d on d.addressbookid = c.addressbookid
              join employee e on e.employeeid = c.employeeid
              join salesarea f on f.salesareaid = d.salesareaid
              join sodetail g on g.soheaderid = b.soheaderid
              join gidetail ss on ss.giheaderid = b.giheaderid
              join sloc h on h.slocid = ss.slocid
              join product i on i.productid = ss.productid
              join productplant j on j.productid = i.productid
                            join plant l on l.plantid = h.plantid
              join unitofmeasure k on k.unitofmeasureid = ss.unitofmeasureid
              where a.recordstatus = 3 and a.invoiceno is not null and
              c.companyid = {$companyid} and h.sloccode like '%%' and d.fullname like '%%' and
              e.fullname like '%%' and f.areaname like '%%' and i.productname like '%%' and a.invoiceno is not null and 
              a.invoiceno is not null and a.invoicedate between '{$day}' and
              '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and l.plantid = {$rows['plantid']} )z group by fullname
              union
              select fullname, -1*sum(nom) as nominal, -1*(sum(nom)-sum(nett1)) as disc, -1*sum(nett1) as netto1,ifnull(-1*sum(nett2),0) as netto2,invoicedate from
              (select distinct a.notagirproid,b.notagirno,replace(f.gino,'SJ','INV') as invoiceno,i.productname,a.qty,
              g.price,sum(a.qty*g.price) as nom,a.price as harga,sum(a.qty*a.price) as nett1,ifnull((select sum(qty*price) from notagirpro o join notagir p on o.notagirid = p.notagirid where o.notagirid = b.notagirid and p.docdate='".date(Yii::app()->params['datetodb'],strtotime($enddate))."'),0) as nett2, b.headernote,k.fullname,d.gireturdate as invoicedate
              from notagirpro a
              join notagir b on b.notagirid=a.notagirid
              join gireturdetail c on c.gireturdetailid=a.gireturdetailid
              join giretur d on d.gireturid=b.gireturid
              join gidetail e on e.gidetailid=c.gidetailid
              join giheader f on f.giheaderid=d.giheaderid
              join sodetail g on g.sodetailid=e.sodetailid
              join soheader h on h.soheaderid=f.soheaderid
              join product i on i.productid = a.productid
              join sloc j on j.slocid = a.slocid
              join addressbook k on k.addressbookid = h.addressbookid
              join employee l on l.employeeid = h.employeeid
              join salesarea m on m.salesareaid = k.salesareaid
                            join plant n on n.plantid = j.plantid
              where h.companyid = {$companyid} and b.recordstatus = 3 and j.sloccode like '%%' 
              and k.fullname like '%%' and l.fullname like '%%' and m.areaname like '%%'
              and i.productname like '%%' and d.gireturdate between '{$day}' and
              '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and n.plantid = {$rows['plantid']}
                            group by notagirno
                            order by notagirno,notagirproid
              )z group by fullname) zz order by fullname";
        $penc = Yii::app()->db->createCommand($sqlpenc)->queryRow();
        $this->pdf->text(14,$this->pdf->gety()+5*$k,'PENCAPAIAN '.$rows['plantcode'].' : '.Yii::app()->format->formatCurrency($penc['netto2']/$per).'/'.Yii::app()->format->formatCurrency($penc['netto']/$per));
        $k++;
                $this->pdf->setY($this->pdf->getY());
    }
        
    $this->pdf->sety($this->pdf->gety()+5+(5*$k));
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->text(10,$this->pdf->gety(),'4. TAGIHAN HARI INI / KUMULATIF TAGIHAN');
    $this->pdf->SetFont('Arial','',10);
    
    $sql10 = "select sum(a.cashamount + a.bankamount)
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            where b.recordstatus=3 and b.companyid = ".$companyid." and b.docdate = '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
    ";
    $row10=Yii::app()->db->createCommand($sql10)->queryScalar();
    
    $sql11 = "select sum(a.cashamount + a.bankamount)
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            where b.recordstatus=3 and b.companyid = ".$companyid." and b.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
    ";
    $row11=Yii::app()->db->createCommand($sql11)->queryScalar();
    
    $this->pdf->text(14,$this->pdf->gety()+5,Yii::app()->format->formatCurrency($row10/$per).' / '.Yii::app()->format->formatCurrency($row11/$per));
        
        $this->pdf->sety($this->pdf->gety()+15);
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->text(10,$this->pdf->gety(),'5. PERSEDIAAN ');
        $this->pdf->SetFont('Arial','',10);

        $connection = Yii::app()->db;
        $sqlfg = "call hitungsaldo(:vfg,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";
        $command1 = $connection->createCommand($sqlfg);
        $command1->bindvalue(':vfg','11050101',PDO::PARAM_STR);
        $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($enddate)),PDO::PARAM_STR);
        $command1->bindvalue(':vcompanyid',$companyid,PDO::PARAM_STR);
        $command1->execute();

        $qfg = "select @vsaldoawal as saldoawal, @vsaldoakhir as saldoakhir";
        $tmt1 = Yii::app()->db->createCommand($qfg);
        $tmt1->execute();
        $fg = $tmt1->queryRow();

        $sqlwip = "call hitungsaldo(:vwip,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";
        $command2 = $connection->createCommand($sqlwip);
        $command2->bindvalue(':vwip','11050103',PDO::PARAM_STR);
        $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($enddate)),PDO::PARAM_STR);
        $command2->bindvalue(':vcompanyid',$companyid,PDO::PARAM_STR);
        $command2->execute();

        $qwip = "select @vsaldoawal as saldoawal, @vsaldoakhir as saldoakhir";
        $tmt2 = Yii::app()->db->createCommand($qwip);
        $tmt2->execute();
        $wip = $tmt2->queryRow();

        $sqlrw = "call hitungsaldo(:vrw,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";
        $command3 = $connection->createCommand($sqlrw);
        $command3->bindvalue(':vrw','11050102',PDO::PARAM_STR);
        $command3->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($enddate)),PDO::PARAM_STR);
        $command3->bindvalue(':vcompanyid',$companyid,PDO::PARAM_STR);
        $command3->execute();

        $qrw = "select @vsaldoawal as saldoawal, @vsaldoakhir as saldoakhir";
        $tmt3 = Yii::app()->db->createCommand($qrw);
        $tmt3->execute();
        $rw = $tmt3->queryRow();

        
        $this->pdf->text(14,$this->pdf->gety()+5,'PERSEDIAAN FG   :'.Yii::app()->format->formatCurrency($fg['saldoakhir']));
        $this->pdf->text(14,$this->pdf->gety()+10,'PERSEDIAAN WIP :'.Yii::app()->format->formatCurrency($wip['saldoakhir']));
        $this->pdf->text(14,$this->pdf->gety()+15,'PERSEDIAAN RW  :'.Yii::app()->format->formatCurrency($rw['saldoakhir']));
        
        
        $this->pdf->setY($this->pdf->getY()+25);
        
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->text(10,$this->pdf->gety(),'6. HUTANG DAGANG vs PEMBAYARAN');
    $this->pdf->SetFont('Arial','',10);
    $this->pdf->sety($this->pdf->gety()+3);
    $this->pdf->colalign = array('C','C','C','C','C');
    $this->pdf->setwidths(array(10,60,50,10,50));
    $this->pdf->colheader = array('No','Umur','Jumlah','VS','Jumlah');
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array('C','L','R','C','R');
        
        $sql13 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120
    from (select (amount)-(payamount) , case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
    case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
    case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
    case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
    case when umur > 120 then amount-payamount else 0 end as a5 from (select a.amount, 
    ifnull((select sum(payamount) from cbapinv j
    left join cashbankout k on k.cashbankoutid=j.cashbankoutid
    where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
    and k.docdate <= '".date(Yii::app()->params['datetodb'],strtotime($enddate))."'
    group by invoiceapid),0) as payamount,
    datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."',a.invoicedate) as umur
    from invoiceap a
    left join grheader b on b.grheaderid = a.grheaderid
    inner join poheader c on c.poheaderid = a.poheaderid
    inner join addressbook d on d.addressbookid = c.addressbookid
    inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
    where a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid}
    and a.receiptdate <= '".date(Yii::app()->params['datetodb'],strtotime($enddate))."') z
    where z.amount > z.payamount ) zz  ";
    $row13=Yii::app()->db->createCommand($sql13)->queryRow();
        
        $sql15 = "select *,case when total=0 then 0 else 0sd30/total end as a1,case when total=0 then 0 else 31sd60/total end as a2,case when total=0 then 0 else 61sd90/total end as a3,case when total=0 then 0 else 91sd120/total end as a4,case when total=0 then 0 else up120/total end as a5,case when total=0 then 0 else 100 end as a6
    from (select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,ifnull(sum(tot),0) as total from (select case when umur >= 0 and umur <= 30 then payamount else 0 end as a1, case when umur > 30 and umur <= 60 then payamount else 0 end as a2,case when umur > 60 and umur <= 90 then payamount else 0 end as a3, case when umur > 90 and umur <= 120 then payamount else 0 end as a4, case when umur > 120 then payamount else 0 end as a5,case when umur >= 0 then payamount else 0 end as tot 
    from (select (a.payamount), datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."',b.invoicedate) as umur
    from cashbankout e
    join cbapinv a on a.cashbankoutid=e.cashbankoutid
    join invoiceap b on b.invoiceapid=a.invoiceapid
    join addressbook c on c.addressbookid=b.addressbookid
    where e.recordstatus=3 and e.companyid={$companyid} and e.docdate between '".date(Yii::app()->params['datetodb'],strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."') z ) zz) zzz;";
    
    $row15=Yii::app()->db->createCommand($sql15)->queryRow();
        
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->row(array('1','HUTANG DAGANG 1-30',Yii::app()->format->formatCurrency($row13['0sd30']/$per),'',Yii::app()->format->formatCurrency($row15['0sd30']/$per)));
        $this->pdf->row(array('2','HUTANG DAGANG 31-60',Yii::app()->format->formatCurrency($row13['31sd60']/$per),'',Yii::app()->format->formatCurrency($row15['31sd60']/$per)));
        $this->pdf->row(array('3','HUTANG DAGANG 61-90',Yii::app()->format->formatCurrency($row13['61sd90']/$per),'',Yii::app()->format->formatCurrency($row15['61sd90']/$per)));
        $this->pdf->row(array('4','HUTANG DAGANG 91-120',Yii::app()->format->formatCurrency($row13['91sd120']/$per),'',Yii::app()->format->formatCurrency($row15['91sd120']/$per)));
        $this->pdf->row(array('5','HUTANG DAGANG > 120',Yii::app()->format->formatCurrency($row13['up120']/$per),'',Yii::app()->format->formatCurrency($row15['up120']/$per)));
        $this->pdf->setFont('Arial','B',10);
        $this->pdf->row(array('','TOTAL HUTANG DAGANG',Yii::app()->format->formatCurrency(($row13['0sd30']+$row13['31sd60']+$row13['61sd90']+$row13['91sd120']+$row13['up120'])/$per),'',Yii::app()->format->formatCurrency(($row15['0sd30']+$row15['31sd60']+$row15['61sd90']+$row15['91sd120']+$row15['up120'])/$per)));
        
        $this->pdf->setY($this->pdf->getY()+10);
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->text(10,$this->pdf->gety(),'7. PIUTANG DAGANG');
        
        $sql9 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5
                    from (select a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
                    where amount > payamount) zz ";
        $row9=Yii::app()->db->createCommand($sql9)->queryRow();

        $this->pdf->setFont('Arial','',10);
        $this->pdf->sety($this->pdf->gety()+3);
        $this->pdf->colalign = array('C','C','C','C');
        $this->pdf->setwidths(array(15,100,50,25));
        $this->pdf->colheader = array('No','Keterangan','Nilai','%');
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array('C','L','R','R');
        
        //$this->pdf->setFont('Arial','',12);
        $this->pdf->row(array('1','PIUTANG DAGANG 1 - 30',
      Yii::app()->format->formatCurrency($row9['0sd30']/$per),
      Yii::app()->format->formatCurrency(($row9['0sd30']/($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']))* 100),
        ));
        $this->pdf->row(array('2','PIUTANG DAGANG 31 - 60',
      Yii::app()->format->formatCurrency($row9['31sd60']/$per),
      Yii::app()->format->formatCurrency(($row9['31sd60']/($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']))*100),
        ));
        $this->pdf->row(array('3','PIUTANG DAGANG 61 - 90',
      Yii::app()->format->formatCurrency($row9['61sd90']/$per),
      Yii::app()->format->formatCurrency(($row9['61sd90']/($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']))*100),
        ));
        $this->pdf->row(array('4','PIUTANG DAGANG 91 - 120',
      Yii::app()->format->formatCurrency($row9['91sd120']/$per),
      Yii::app()->format->formatCurrency(($row9['91sd120']/($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']))*100),
        ));
        $this->pdf->row(array('5','PIUTANG DAGANG > 120',
      Yii::app()->format->formatCurrency($row9['up120']/$per),
      Yii::app()->format->formatCurrency(($row9['up120']/($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']))*100),
        ));
        $this->pdf->setFont('Arial','B',10);
        $this->pdf->row(array('','TOTAL PIUTANG DAGANG',
      Yii::app()->format->formatCurrency(($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120'])/$per),
      Yii::app()->format->formatCurrency('100'),
        ));
        
    $this->pdf->sety($this->pdf->gety()+25);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->text(10,$this->pdf->gety(),'KETERANGAN :');
    $this->pdf->SetFont('Arial','',10);
    $this->pdf->text(10,$this->pdf->gety()+5,'- Hari Ini Tanggal '.date('d',strtotime($enddate)));
    $this->pdf->text(10,$this->pdf->gety()+10,'- Kumulatif Tanggal '.date('d',strtotime($startdate)).' s/d '.date('d',strtotime($enddate)));
    $this->pdf->text(10,$this->pdf->gety()+15,'- Apabila masih trading, qty produksi belum bisa menggambarkan 100%.');
    $this->pdf->text(10,$this->pdf->gety()+20,'  Karena data qty produksi diambil dari pembelian trading ke produksi.');
    $this->pdf->text(10,$this->pdf->gety()+25,'  Kecuali untuk barang jadi, apabila setiap hari selalu melakukan jual-beli antara trading dan produksi');
    $this->pdf->text(10,$this->pdf->gety()+30,'- Data bisa dikatakan Valid apabila semua transaksi sudah diinput (up to date).');
        
        $code = getCompanycode($companyid);
        $date = date(Yii::app()->params['datetodb'],strtotime($enddate));
    $this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/DailyMonitoringReport-'.$code.'-'.$date.'.pdf', 'F');
    $this->pdf->Output('DailyMonitoringReport'.$date.'.pdf', 'I');   
    }

  public function RincianBiayaEkspedisiperDokumen($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    parent::actionDownload();
    $grandqty   = 0;
    $grandtotal = 0;
    $grandbiaya = 0;
    $sql        = "select distinct a.ekspedisiid,a.ekspedisino, a.docdate as tanggal,c.pono
					from ekspedisi a
					join ekspedisipo b on b.ekspedisiid = a.ekspedisiid
					join poheader c on c.poheaderid = b.poheaderid
					join eksmat d on d.ekspedisiid = a.ekspedisiid
					join productplant e on e.productid = d.productid
					join sloc f on f.slocid = e.slocid
					join product g on g.productid = d.productid
					where a.companyid = " . $companyid . " and a.recordstatus = 3
					and f.sloccode like '%" . $sloc . "%' and g.productname like '%" . $product . "%'
					and a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                    and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Biaya Ekspedisi Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 5);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Dokumen');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . $row['ekspedisino']);
      $this->pdf->text(10, $this->pdf->gety() + 15, 'No. PO');
      $this->pdf->text(40, $this->pdf->gety() + 15, ': ' . $row['pono']);
      $this->pdf->text(130, $this->pdf->gety() + 10, 'Tanggal');
      $this->pdf->text(160, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['tanggal'])));
      $sql1        = "select a.ekspedisino,c.qty,c.expense as biaya,d.productname,d.productid,f.uomcode,
						(select zz.netprice
							from podetail zz
							where zz.poheaderid = b.poheaderid and zz.productid = c.productid limit 1) as harga
						from ekspedisi a
						join ekspedisipo b on b.ekspedisiid = a.ekspedisiid
						join eksmat c on c.ekspedisiid = a.ekspedisiid
						join product d on d.productid = c.productid
						join productplant e on e.productid = d.productid
						join unitofmeasure f on f.unitofmeasureid = c.uomid
						join poheader g on g.poheaderid = b.poheaderid
						join podetail h on h.poheaderid = g.poheaderid
						join sloc i on i.slocid = h.slocid
						where a.companyid = " . $companyid . " and a.recordstatus = 3
						and a.ekspedisiid = '" . $row['ekspedisiid'] . "' and d.productname like '%" . $product . "%' and i.sloccode like '%" . $sloc . "%'
						and a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
                        and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' group by productname";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      $totalqty    = 0;
      $total       = 0;
      $biaya       = 0;
      $this->pdf->sety($this->pdf->gety() + 18);
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
        60,
        30,
        30,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Satuan',
        'Qty',
        'Jumlah',
        'Biaya Ekspedisi'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'C',
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
          $row1['uomcode'],
          Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row1['qty']),
          Yii::app()->format->formatNumber($row1['qty'] * $row1['harga']),
          Yii::app()->format->formatNumber($row1['biaya'])
        ));
        $totalqty += $row1['qty'];
        $total += ($row1['qty'] * $row1['harga']);
        $biaya += $row1['biaya'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        '',
        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $totalqty),
        Yii::app()->format->formatNumber($total),
        Yii::app()->format->formatNumber($biaya)
      ));
      $grandqty += $totalqty;
      $grandtotal += $total;
      $grandbiaya += $biaya;
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->colalign = array(
      'C',
      'R',
      'R',
      'R'
    );
    $this->pdf->setwidths(array(
      15,
      45,
      60,
      60
    ));
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->row(array(
      '',
      'GRAND QTY:  ' . Yii::app()->format->formatCurrency($grandqty),
      'GRAND JUMLAH:   ' . Yii::app()->format->formatCurrency($grandtotal),
      'GRAND BIAYA:  ' . Yii::app()->format->formatCurrency($grandbiaya)
    ));
    $this->pdf->Output();
  }
  public function RekapBiayaEkspedisiPerDokumen($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    parent::actionDownload();
    $sql        = "select distinct a.ekspedisino as dokumen,a.docdate as tanggal,a.amount as biaya,i.fullname as supplier , j.invoiceno as no_doc,l.productname,m.sloccode,
                         (select sum(zz.qty*(select xx.netprice
                         from podetail xx
                         where xx.poheaderid = c.poheaderid
                         and xx.productid = zz.productid))
                         from eksmat zz
                         left join ekspedisipo b on b.ekspedisipoid = zz.ekspedisipoid
                         left join poheader c on c.poheaderid = b.poheaderid
                         left join addressbook f on f.addressbookid = c.poheaderid                   
                         where zz.ekspedisiid = a.ekspedisiid) as jumlah
                         from ekspedisi a
                         join ekspedisipo g on g.ekspedisiid = a.ekspedisiid
                         join poheader h on h.poheaderid = g.poheaderid 
                         join addressbook i on i.addressbookid = h.addressbookid
                         left join invoiceap j on j.addressbookid = h.addressbookid
                     	 left join podetail k on k.podetailid = h.poheaderid
                     	 left join product l on l.productid = k.productid
                     	 left join sloc m on m.slocid = k.slocid
                     	 
                         where a.ekspedisino is not null and a.recordstatus = 3 and a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
                         and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and m.sloccode like '%" . $sloc . "%' and l.productname like '%" . $product . "%' and j.invoiceno like '%" . $invoice . "%' and i.fullname like '%" . $supplier . "%' and a.ekspedisiid in
                         (select d.ekspedisiid from ekspedisipo d
                         left join poheader p on p.poheaderid = d.poheaderid
                         where p.companyid =  " . $companyid . ")";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Biaya Ekspedisi Per Dokumen';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->sety($this->pdf->gety() + 10);
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
      25,
      25,
      50,
      25,
      25,
      30
    ));
    $this->pdf->colheader = array(
      'No',
      'No Bukti',
      'Tanggal',
      'Supplier',
      'No.Dokumen',
      'Jumlah',
      'Biaya Ekspedisi'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'C',
      'C',
      'C',
      'R',
      'R'
    );
    $total                     = 0;
    $i                         = 0;
    $biaya                     = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        $i,
        $row['dokumen'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['tanggal'])),
        $row['supplier'],
        $row['no_doc'],
        Yii::app()->format->formatCurrency($row['jumlah']),
        Yii::app()->format->formatCurrency($row['biaya'])
      ));
      $total += $row['jumlah'];
      $biaya += $row['biaya'];
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->row(array(
      '',
      'GRAND TOTAL',
      '',
      Yii::app()->format->formatNumber($total),
      Yii::app()->format->formatNumber($biaya)
    ));
    $this->pdf->checkPageBreak(20);
    $this->pdf->Output();
  }
  public function RincianPembayaranHutangPerDokumen($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
	{
      parent::actionDownload();
      $totalamount1=0;$totalpayamount1=0;$totalsisa1=0;
      $sql = "select distinct e.cashbankoutno,e.cashbankoutid,e.docdate as cbdate,f.reqpayno,f.docdate as rpdate
              from cashbankout e
              join cbapinv a on a.cashbankoutid=e.cashbankoutid
              join invoiceap b on b.invoiceapid=a.invoiceapid
              join addressbook c on c.addressbookid=b.addressbookid
              join account d on d.accountid=a.accountid
              join reqpay f on f.reqpayid=e.reqpayid
              where e.recordstatus=3 and e.companyid = " . $companyid . " and c.fullname like '%" . $supplier . "%' and e.cashbankoutno like '%".$invoice."%'
              and e.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
              and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
              order by cashbankoutno";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
                
		foreach($dataReader as $row)
		{
			$this->pdf->companyid = $companyid;
		}
        $this->pdf->title='Rincian Pembayaran Hutang Per Dokumen';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');		
               
		foreach($dataReader as $row)
        {
          $this->pdf->setFont('Arial','B',9);
          $this->pdf->text(10,$this->pdf->gety()+2,'No ');
          $this->pdf->text(30,$this->pdf->gety()+2,': '.$row['cashbankoutno']);
          $this->pdf->text(160,$this->pdf->gety()+2,'No ');
          $this->pdf->text(170,$this->pdf->gety()+2,': '.$row['reqpayno']);
          $this->pdf->text(10,$this->pdf->gety()+6,'Tgl ');
          $this->pdf->text(30,$this->pdf->gety()+6,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['cbdate'])));
          $this->pdf->text(160,$this->pdf->gety()+6,'Tgl ');
          $this->pdf->text(170,$this->pdf->gety()+6,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['rpdate'])));
          $sql1 = " select b.invoiceno,c.fullname,b.invoicedate,b.amount,a.payamount,d.accountname,b.amount - a.payamount as sisa
                    from cbapinv a
                    join invoiceap b on b.invoiceapid=a.invoiceapid
                    join addressbook c on c.addressbookid=b.addressbookid
                    join account d on d.accountid=a.accountid
                    where a.cashbankoutid = '".$row['cashbankoutid']."'  ";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
          
          $this->pdf->setFont('Arial','',8);
          $this->pdf->sety($this->pdf->gety()+10);    
          $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
          $this->pdf->setwidths(array(10,30,60,20,35,35,50,35));
          $this->pdf->colheader = array('No','No Invoice','Customer','Tgl Invoice','Saldo Invoice','Nilai bayar','Akun','Sisa');
          $this->pdf->RowHeader();
          $this->pdf->coldetailalign = array('C','L','L','C','R','R','R','R','R');
          $i=0;$totalamount=0;$totalpayamount=0;$totalsisa=0; 
          foreach ($dataReader1 as $row1)
          {
            $i=$i+1;
				$this->pdf->row(array($i,$row1['invoiceno'],
				$row1['fullname'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
				Yii::app()->format->formatCurrency($row1['amount']/$per),						
				Yii::app()->format->formatCurrency($row1['payamount']/$per),                        
				$row1['accountname'],
				Yii::app()->format->formatCurrency($row1['sisa']/$per ),
				));
            $totalamount += ($row1['amount']/$per);
            $totalpayamount += ($row1['payamount']/$per);
            $totalsisa += ($row1['sisa']/$per );

          } 
          $this->pdf->setFont('Arial','BI',9);
			$this->pdf->row(array('','TOTAL ',$row['cashbankoutno'],'',
			Yii::app()->format->formatCurrency($totalamount),					
			Yii::app()->format->formatCurrency($totalpayamount),'',
			Yii::app()->format->formatCurrency($totalsisa)));
          $this->pdf->sety($this->pdf->gety()+5); 
          
          $totalamount1 += $totalamount;
          $totalpayamount1 += $totalpayamount;
          $totalsisa1 += $totalsisa;
          $this->pdf->checkPageBreak(25);          
        }
        
        $this->pdf->setFont('Arial','BI',10);
		$this->pdf->row(array('','','GRAND TOTAL','',
			Yii::app()->format->formatCurrency($totalamount1),					
			Yii::app()->format->formatCurrency($totalpayamount1),'',
			Yii::app()->format->formatCurrency($totalsisa1)));
    
      $this->pdf->Output();
	}
  public function KartuHutang($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    parent::actionDownload();
    $penambahan1 = 0;
    $dibayar1    = 0;
    $bank1       = 0;
    $diskon1     = 0;
    $retur1      = 0;
    $ob1         = 0;
    $saldo1      = 0;
    $sql         = "select distinct addressbookid,fullname,saldoawal
						from (select a.addressbookid,a.fullname,
						ifnull((select sum(ifnull(d.amount,0)-ifnull((select sum(ifnull(b.payamount,0))
						from cbapinv b
						join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid 
						and c.docdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0) as saldoawal,
						ifnull((select sum(ifnull(d.amount,0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as hutang,
						ifnull((select sum(ifnull((select sum(ifnull(b.payamount,0))
						from cbapinv b
						join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid 
						and c.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as dibayar
						from addressbook a where a.fullname like '%" . $supplier . "%') z
						where z.saldoawal<>0 or z.hutang<>0 or z.dibayar<>0
						order by fullname";
    $command     = $this->connection->createCommand($sql);
    $dataReader  = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Kartu Hutang';
    $this->pdf->subtitle = 'Dari Tgl : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', 'B', 10);
      $this->pdf->text(10, $this->pdf->gety() + 3, $row['fullname']);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        25,
        20,
        40,
        35,
        35,
        35
      ));
      $this->pdf->colheader = array(
        'Dokumen',
        'Tanggal',
        'U/ Byr INV',
        'Penambahan',
        'Dibayar',
        'Saldo'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'C',
        'C',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 0);
      $this->pdf->row(array(
        'Saldo Awal',
        '',
        '',
        '',
        '',
        Yii::app()->format->formatCurrency($row['saldoawal'] / $per)
      ));
      $penambahan  = 0;
      $dibayar     = 0;
      $bank        = 0;
      $diskon      = 0;
      $retur       = 0;
      $ob          = 0;
      $sql2        = "select * from
				(select a.invoiceno as dokumen,a.invoicedate as tanggal,ifnull(b.grno,'-') as ref,a.amount as penambahan,'0' as dibayar
				from invoiceap a
				join grheader b on b.grheaderid=a.grheaderid
				join poheader c on c.poheaderid=b.poheaderid
				where a.recordstatus=3 and a.companyid=" . $companyid . " 
				and a.addressbookid=" . $row['addressbookid'] . "
				and a.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					union
				select d.cashbankoutno as dokumen,d.docdate as tanggal,concat(ifnull(h.grno,'-'),' / ',ifnull(g.invoiceno,'-')) as ref,'0' as penambahan,c.payamount as dibayar
				from cbapinv c
				join cashbankout d on d.cashbankoutid=c.cashbankoutid
				join invoiceap g on g.invoiceapid=c.invoiceapid
				join grheader h on h.grheaderid=g.grheaderid
				where d.recordstatus=3
				and d.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				and c.invoiceapid in (select b.invoiceapid
				from invoiceap b
				join grheader e on e.grheaderid=b.grheaderid
				join poheader f on f.poheaderid=e.poheaderid
				where b.recordstatus=3
				and b.companyid=" . $companyid . " and b.addressbookid = " . $row['addressbookid'] . "
				and b.receiptdate<='" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
				) z
				order by tanggal,dokumen";
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      foreach ($dataReader2 as $row2) {
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->row(array(
          $row2['dokumen'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row2['tanggal'])),
          $row2['ref'],
          Yii::app()->format->formatCurrency($row2['penambahan'] / $per),
          Yii::app()->format->formatCurrency(-$row2['dibayar'] / $per),
          ''
        ));
        $penambahan += $row2['penambahan'] / $per;
        $dibayar += $row2['dibayar'] / $per;
      }
      $this->pdf->SetFont('Arial', 'B', 8);
      $this->pdf->setwidths(array(
        85,
        35,
        35,
        35,
        30,
        30,
        30,
        30
      ));
      $this->pdf->coldetailalign = array(
        'C',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->row(array(
        'TOTAL ' . $row['fullname'],
        Yii::app()->format->formatCurrency($penambahan),
        Yii::app()->format->formatCurrency(-$dibayar),
        Yii::app()->format->formatCurrency($row['saldoawal'] / $per + $penambahan - $dibayar)
      ));
      $penambahan1 += $penambahan;
      $dibayar1 += $dibayar;
      $saldo1 += $row['saldoawal'] / $per;
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->checkPageBreak(5);
    }
    $this->pdf->SetFont('Arial', 'B', 8);
    $this->pdf->setwidths(array(
      50,
      35,
      35,
      35,
      35,
      30,
      30,
      30,
      30
    ));
    $this->pdf->coldetailalign = array(
      'C',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R'
    );
    $this->pdf->row(array(
      '',
      'Saldo Awal',
      'Penambahan',
      'Dibayar',
      'Saldo Akhir'
    ));
    $this->pdf->SetFont('Arial', 'BI', 8);
    $this->pdf->setwidths(array(
      50,
      35,
      35,
      35,
      35,
      30,
      30,
      30,
      30
    ));
    $this->pdf->coldetailalign = array(
      'C',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R'
    );
    $this->pdf->row(array(
      'GRAND TOTAL',
      Yii::app()->format->formatCurrency($saldo1),
      Yii::app()->format->formatCurrency($penambahan1),
      Yii::app()->format->formatCurrency(-$dibayar1),
      Yii::app()->format->formatCurrency($saldo1 + $penambahan1 - $dibayar1)
    ));
    $this->pdf->Output();
  }
  public function RekapHutangPerSupplier($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    parent::actionDownload();
    $sql        = "select *
						from (select a.fullname,
						ifnull((select sum(ifnull(d.amount,0)-ifnull((select sum(ifnull(b.payamount,0))
						from cbapinv b
						join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid 
						and c.docdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0) as saldoawal,
						ifnull((select sum(ifnull(d.amount,0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as hutang,
						ifnull((select sum(ifnull((select sum(ifnull(b.payamount,0))
						from cbapinv b
						join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid 
						and c.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as dibayar
						from addressbook a where a.fullname like '%" . $supplier . "%') z
						where z.saldoawal<>0 or z.hutang<>0 or z.dibayar<>0
						order by fullname";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Hutang Per Supplier';
    $this->pdf->subtitle = 'Dari Tgl : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 8);
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
      10,
      50,
      30,
      30,
      30,
      40
    ));
    $this->pdf->colheader = array(
      'No',
      'Supplier',
      'Saldo Awal',
      'Hutang',
      'Dibayar',
      'Saldo Akhir'
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
    $i                         = 0;
    $saldoawal                 = 0;
    $hutang                    = 0;
    $dibayar                   = 0;
    $saldoakhir                = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['fullname'],
        Yii::app()->format->formatCurrency($row['saldoawal'] / $per),
        Yii::app()->format->formatCurrency($row['hutang'] / $per),
        Yii::app()->format->formatCurrency($row['dibayar'] / $per),
        Yii::app()->format->formatCurrency(($row['saldoawal'] + $row['hutang'] - $row['dibayar']) / $per)
      ));
      $saldoawal += $row['saldoawal'] / $per;
      $hutang += $row['hutang'] / $per;
      $dibayar += $row['dibayar'] / $per;
      $saldoakhir += ($row['saldoawal'] + $row['hutang'] - $row['dibayar']) / $per;
    }
    $this->pdf->setFont('Arial', 'BI', 9);
    $this->pdf->row(array(
      '',
      'TOTAL',
      Yii::app()->format->formatCurrency($saldoawal),
      Yii::app()->format->formatCurrency($hutang),
      Yii::app()->format->formatCurrency($dibayar),
      Yii::app()->format->formatCurrency($saldoakhir)
    ));
    $this->pdf->Output();
  }
  public function RincianPembeliandanReturBeliBelumLunas($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    parent::actionDownload();
    $nilaitot1  = 0;
    $dibayar1   = 0;
    $sisa1      = 0;
    $sql        = "select distinct addressbookid,fullname
					from (select *
					from (select d.addressbookid, d.fullname, a.amount,
					ifnull((select sum(payamount) from cbapinv j
					left join cashbankout k on k.cashbankoutid=j.cashbankoutid
					where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
					and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					group by invoiceapid),0) as payamount
					from invoiceap a
					join grheader b on b.grheaderid = a.grheaderid
					join poheader c on c.poheaderid = b.poheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . " 
					and d.fullname like '%" . $supplier . "%'
					and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
					where amount > payamount) zz
					order by fullname";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Pembelian & Retur Beli Belum Lunas';
    $this->pdf->subtitle = 'Per Tanggal : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->sety($this->pdf->gety() + 0);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, $row['fullname']);
      $sql1        = "select *
				from (select a.invoiceno,b.grno,a.invoicedate,e.paydays,
				date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
				datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',a.invoicedate) as umur,a.amount, 
				ifnull((select sum(payamount) from cbapinv j
				left join cashbankout k on k.cashbankoutid=j.cashbankoutid
				where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
				and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				group by invoiceapid),0) as payamount
				from invoiceap a
				inner join grheader b on b.grheaderid = a.grheaderid
				inner join poheader c on c.poheaderid = b.poheaderid
				inner join addressbook d on d.addressbookid = c.addressbookid
				inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
				where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . "
				and d.addressbookid = '" . $row['addressbookid'] . "'						
				and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
				where z.amount > z.payamount
				order by invoicedate,invoiceno";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 7);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
        'C',
        'C',
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
        20,
        20,
        20,
        20,
        10,
        30,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Dokumen',
        'Referensi',
        'Tanggal',
        'j_tempo',
        'Umur',
        'Nilai',
        'Kum_bayar',
        'Sisa'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      $i        = 0;
      $nilaitot = 0;
      $dibayar  = 0;
      $sisa     = 0;
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['invoiceno'],
          $row1['grno'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['jatuhtempo'])),
          $row1['umur'],
          Yii::app()->format->formatCurrency($row1['amount'] / $per),
          Yii::app()->format->formatCurrency($row1['payamount'] / $per),
          Yii::app()->format->formatCurrency(($row1['amount'] / $per) - ($row1['payamount'] / $per))
        ));
        $nilaitot += $row1['amount'] / $per;
        $dibayar += $row1['payamount'] / $per;
        $sisa += (($row1['amount'] / $per) - ($row1['payamount'] / $per));
        $this->pdf->checkPageBreak(20);
      }
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Total:',
        '',
        Yii::app()->format->formatCurrency($nilaitot),
        Yii::app()->format->formatCurrency($dibayar),
        Yii::app()->format->formatCurrency($sisa)
      ));
      $nilaitot1 += $nilaitot;
      $dibayar1 += $dibayar;
      $sisa1 += $sisa;
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->setFont('Arial', 'BI', 9);
    $this->pdf->coldetailalign = array(
      'R',
      'R',
      'R',
      'R'
    );
    $this->pdf->setwidths(array(
      95,
      30,
      35,
      30
    ));
    $this->pdf->row(array(
      'GRAND TOTAL',
      Yii::app()->format->formatCurrency($nilaitot1),
      Yii::app()->format->formatCurrency($dibayar1),
      Yii::app()->format->formatCurrency($sisa1)
    ));
    $date = date('Y-m-d_h-m');
    $this->pdf->Output();
    //$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/RincianPembelian-ReturBeliBelumLunas_'.$date.'.pdf','F');
  }
  public function RincianUmurHutangperSTTB($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    parent::actionDownload();
    $total2sd0    = 0;
    $total20sd30  = 0;
    $total231sd60 = 0;
    $total261sd90 = 0;
    $total2sd90   = 0;
    $totaltempo2  = 0;
    $total2       = 0;
    $sql          = "select distinct addressbookid,fullname
					from (select *
					from (select d.addressbookid, d.fullname, a.amount,
					ifnull((select sum(payamount) from cbapinv j
					left join cashbankout k on k.cashbankoutid=j.cashbankoutid
					where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
					and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					group by invoiceapid),0) as payamount
					from invoiceap a
					join grheader b on b.grheaderid = a.grheaderid
					join poheader c on c.poheaderid = b.poheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . " 
					and d.fullname like '%" . $supplier . "%'
					and a.invoicedate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
					where amount > payamount) zz
					order by fullname";
    $command      = $this->connection->createCommand($sql);
    $dataReader   = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rincian Umur Hutang Per Supplier';
    $this->pdf->subtitle = 'Per Tanggal : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L');
    $this->pdf->sety($this->pdf->gety() + 0);
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 3, 'Supplier');
      $this->pdf->text(30, $this->pdf->gety() + 3, ': ' . $row['fullname']);
      $sql1        = "select z.*,
														case when umurtempo < 0 then totamount else 0 end as sd0,
														case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
														case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
														case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
														case when umurtempo > 90 then totamount else 0 end as sd90
												from
												(select concat(ifnull(a.invoiceno,'-'),' / ',ifnull(b.grno,'-')) as invoiceno, a.invoicedate,
												datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',a.invoicedate) as umur,
												datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
												date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
												a.amount-ifnull((select sum(payamount) from cbapinv j
												left join cashbankout k on k.cashbankoutid=j.cashbankoutid
												where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
												and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
												group by invoiceapid),0) as totamount,e.paydays
												from invoiceap a
												inner join grheader b on b.grheaderid = a.grheaderid
												inner join poheader c on c.poheaderid = b.poheaderid
												inner join addressbook d on d.addressbookid = c.addressbookid
												inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
												where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . "
												and d.addressbookid = '" . $row['addressbookid'] . "'						
												and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')z
												where totamount > 0";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 6);
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->colalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'C',
        'R',
        'R'
      );
      $this->pdf->setwidths(array(
        10,
        55,
        12,
        12,
        27,
        27,
        81,
        27,
        32
      ));
      $this->pdf->colheader = array(
        '|',
        '|',
        '|',
        '|',
        '|',
        '|',
        'Sudah Jatuh Tempo',
        '|',
        '|'
      );
      $this->pdf->RowHeader();
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
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
        55,
        12,
        12,
        27,
        27,
        27,
        27,
        27,
        27,
        32
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'T.O.P',
        'Umur',
        'Belum JTT',
        '0-30 Hari',
        '31-60 Hari',
        '61-90 Hari',
        '> 90 Hari',
        'Jumlah',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'C',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      $totalsd0    = 0;
      $total0sd30  = 0;
      $total31sd60 = 0;
      $total61sd90 = 0;
      $totalsd90   = 0;
      $totaltempo  = 0;
      $total       = 0;
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->pdf->row(array(
          $i,
          $row1['invoiceno'],
          $row1['paydays'],
          $row1['umur'],
          Yii::app()->format->formatCurrency($row1['sd0'] / $per),
          Yii::app()->format->formatCurrency($row1['0sd30'] / $per),
          Yii::app()->format->formatCurrency($row1['31sd60'] / $per),
          Yii::app()->format->formatCurrency($row1['61sd90'] / $per),
          Yii::app()->format->formatCurrency($row1['sd90'] / $per),
          Yii::app()->format->formatCurrency(($row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per),
          Yii::app()->format->formatCurrency(($row1['sd0'] + $row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per)
        ));
        $totalsd0 += $row1['sd0'] / $per;
        $total0sd30 += $row1['0sd30'] / $per;
        $total31sd60 += $row1['31sd60'] / $per;
        $total61sd90 += $row1['61sd90'] / $per;
        $totalsd90 += $row1['sd90'] / $per;
        $totaltempo += ($row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per;
        $total += ($row1['sd0'] + $row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per;
      }
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->row(array(
        '',
        'TOTAL ' . $row['fullname'],
        '',
        '',
        Yii::app()->format->formatCurrency($totalsd0),
        Yii::app()->format->formatCurrency($total0sd30),
        Yii::app()->format->formatCurrency($total31sd60),
        Yii::app()->format->formatCurrency($total61sd90),
        Yii::app()->format->formatCurrency($totalsd90),
        Yii::app()->format->formatCurrency($totaltempo),
        Yii::app()->format->formatCurrency($total)
      ));
      $total2sd0 += $totalsd0;
      $total20sd30 += $total0sd30;
      $total231sd60 += $total31sd60;
      $total261sd90 += $total61sd90;
      $total2sd90 += $totalsd90;
      $totaltempo2 += $totaltempo;
      $total2 += $total;
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->checkPageBreak(30);
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->coldetailalign = array(
      'C',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R'
    );
    $this->pdf->setwidths(array(
      89,
      27,
      27,
      27,
      27,
      27,
      27,
      32
    ));
    $this->pdf->setFont('Arial', 'BI', 9);
    $this->pdf->row(array(
      'Grand Total :',
      Yii::app()->format->formatCurrency($total2sd0),
      Yii::app()->format->formatCurrency($total20sd30),
      Yii::app()->format->formatCurrency($total231sd60),
      Yii::app()->format->formatCurrency($total261sd90),
      Yii::app()->format->formatCurrency($total2sd90),
      Yii::app()->format->formatCurrency($totaltempo2),
      Yii::app()->format->formatCurrency($total2)
    ));
    $this->pdf->Output();
  }
  public function RekapUmurHutangperSupplier($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    parent::actionDownload();
    $sql        = "select *,sum(sd0) as belumjatuhtempo, sum(0sd30) as sum0sd30,sum(31sd60) as sum31sd60, sum(61sd90) as sum61sd90, sum(sd90) as sumsd90
				from (select *,
				case when umurtempo < 0 then totamount else 0 end as sd0,
				case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
				case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
				case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
				case when umurtempo > 90 then totamount else 0 end as sd90
				from (select a.invoicedate,
				datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',a.invoicedate) as umur,
				datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
				date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,d.fullname,
				a.amount-ifnull((select sum(payamount) from cbapinv j
				left join cashbankout k on k.cashbankoutid=j.cashbankoutid
				where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
				and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				group by invoiceapid),0) as totamount,e.paydays
				from invoiceap a
				inner join grheader b on b.grheaderid = a.grheaderid
				inner join poheader c on c.poheaderid = b.poheaderid
				inner join addressbook d on d.addressbookid = c.addressbookid
				inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
				where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . "
				and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')z 
				where totamount>0)zz
				group by fullname
				order by fullname";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Umur Hutang Per Supplier';
    $this->pdf->subtitle = 'Per Tanggal : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('L');
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->colalign = array(
      'C',
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
      75,
      30,
      30,
      30,
      30,
      30,
      35
    ));
    $this->pdf->colheader = array(
      'No',
      'Nama Supplier',
      'Belum Jatuh Tempo',
      '0-30 Hari',
      '31-60 Hari',
      '61-90 Hari',
      '> 90 Hari',
      'Total'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R'
    );
    $totalsd0                  = 0;
    $total0sd30                = 0;
    $total31sd60               = 0;
    $total61sd90               = 0;
    $totalsd90                 = 0;
    $total                     = 0;
    $i                         = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        $i,
        $row['fullname'],
        Yii::app()->format->formatCurrency($row['belumjatuhtempo'] / $per),
        Yii::app()->format->formatCurrency($row['sum0sd30'] / $per),
        Yii::app()->format->formatCurrency($row['sum31sd60'] / $per),
        Yii::app()->format->formatCurrency($row['sum61sd90'] / $per),
        Yii::app()->format->formatCurrency($row['sumsd90'] / $per),
        Yii::app()->format->formatCurrency(($row['belumjatuhtempo'] + $row['sum0sd30'] + $row['sum31sd60'] + $row['sum61sd90'] + $row['sumsd90']) / $per)
      ));
      $totalsd0 += $row['belumjatuhtempo'] / $per;
      $total0sd30 += $row['sum0sd30'] / $per;
      $total31sd60 += $row['sum31sd60'] / $per;
      $total61sd90 += $row['sum61sd90'] / $per;
      $totalsd90 += $row['sumsd90'] / $per;
      $total += ($row['belumjatuhtempo'] + $row['sum0sd30'] + $row['sum31sd60'] + $row['sum61sd90'] + $row['sumsd90']) / $per;
    }
    $this->pdf->sety($this->pdf->gety() + 5);
    $this->pdf->setFont('Arial', 'B', 9);
    $this->pdf->row(array(
      '',
      'Total :',
      Yii::app()->format->formatCurrency($totalsd0),
      Yii::app()->format->formatCurrency($total0sd30),
      Yii::app()->format->formatCurrency($total31sd60),
      Yii::app()->format->formatCurrency($total61sd90),
      Yii::app()->format->formatCurrency($totalsd90),
      Yii::app()->format->formatCurrency($total)
    ));
    $this->pdf->checkPageBreak(20);
    $this->pdf->Output();
  }
  public function RekapInvoiceAPPerDokumenBelumStatusMax($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select distinct a.invoiceapid, a.invoiceno,a.invoicedate, b.pono, b.headernote, a.recordstatus
				from invoiceap a
				join poheader b on b.poheaderid = a.poheaderid
				where a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				and a.recordstatus between 1 and (3-1)
				and b.pono is not null
				and a.companyid = " . $companyid . "
				order by a.recordstatus";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Invoice AP Per Dokumen Belum Status Max';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->colalign = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'L',
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      25,
      25,
      60,
      25,
      25
    ));
    $this->pdf->colheader = array(
      'No',
      'ID Transaksi',
      'No Transaksi',
      'Tanggal',
      'No Referensi',
      'Keterangan',
      'Status'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'L',
      'L'
    );
    $totalnominal1             = 0;
    $i                         = 0;
    $totaldisc1                = 0;
    $totaljumlah1              = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['invoiceapid'],
        $row['invoiceno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])),
        $row['pono'],
        $row['headernote'],
        findstatusname("appinvap", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapPermohonanPembayaranPerDokumenBelumStatusMax($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select a.reqpayid, a.reqpayno, a.docdate, a.headernote, a.recordstatus
				from reqpay a
				where a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
				and a.recordstatus between 1 and (6-1)
				and a.companyid = " . $companyid . "
				order by a.recordstatus";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Permohonan Pembayaran Per Dokumen Belum Status Max';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->colalign = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'L',
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      25,
      25,
      60,
      25,
      25
    ));
    $this->pdf->colheader = array(
      'No',
      'ID Transaksi',
      'No Transaksi',
      'Tanggal',
      'No Referensi',
      'Keterangan',
      'Status'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'L',
      'L'
    );
    $totalnominal1             = 0;
    $i                         = 0;
    $totaldisc1                = 0;
    $totaljumlah1              = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['reqpayid'],
        $row['reqpayno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
        '',
        $row['headernote'],
        findstatusname("apppayreq", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function RekapNotaReturPembelianPerDokumenBelumStatusMax($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate)
  {
    parent::actionDownload();
    $sql        = "select a.notagrreturid,a.notagrreturno,a.docdate,b.grreturno,a.headernote,a.recordstatus
				from notagrretur a
				join grretur b on b.grreturid=a.grreturid
				where a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				and a.recordstatus between 1 and (3-1)
				and a.companyid = " . $companyid . "
				order by a.recordstatus";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $companyid;
    }
    $this->pdf->title    = 'Rekap Nota Retur Pembelian Per Dokumen Belum Status Max';
    $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
    $this->pdf->AddPage('P');
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->colalign = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'L',
      'L'
    );
    $this->pdf->setwidths(array(
      10,
      20,
      25,
      25,
      25,
      60,
      25,
      25
    ));
    $this->pdf->colheader = array(
      'No',
      'ID Transaksi',
      'No Transaksi',
      'Tanggal',
      'No Referensi',
      'Keterangan',
      'Status'
    );
    $this->pdf->RowHeader();
    $this->pdf->coldetailalign = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'L',
      'L'
    );
    $totalnominal1             = 0;
    $i                         = 0;
    $totaldisc1                = 0;
    $totaljumlah1              = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->row(array(
        $i,
        $row['notagrreturid'],
        $row['notagrreturno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
        $row['grreturno'],
        $row['headernote'],
        findstatusname("appcutar", $row['recordstatus'])
      ));
      $this->pdf->checkPageBreak(20);
    }
    $this->pdf->Output();
  }
  public function actionDownXLS()
  {
    parent::actionDownload();
    if (isset($_GET['lro']) && isset($_GET['company']) && isset($_GET['sloc']) && isset($_GET['product']) && isset($_GET['supplier']) && isset($_GET['invoice']) && isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['per'])) {
      if ($_GET['lro'] == 1) {
        $this->RincianBiayaEkspedisiPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 2) {
        $this->RekapBiayaEkspedisiPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 3) {
        $this->RekapBiayaEkspedisiPerBarangXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 4) {
        $this->RincianPembayaranHutangPerDokumenXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 5) {
        $this->KartuHutangXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 6) {
        $this->RekapHutangPerSupplierXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 7) {
        $this->RincianPembeliandanReturBeliBelumLunasXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 8) {
        $this->RincianUmurHutangperSTTBXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      } else if ($_GET['lro'] == 9) {
        $this->RekapUmurHutangperSupplierXLS($_GET['company'], $_GET['sloc'], $_GET['product'], $_GET['supplier'], $_GET['invoice'], $_GET['startdate'], $_GET['enddate'], $_GET['per']);
      }
    }
  }
  public function RincianPembayaranHutangPerDokumenXLS($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    $this->menuname = 'rincianpembayaranhutangperdokumen';
    parent::actionDownxls();
    {
      $sql = "select distinct e.cashbankoutno,e.cashbankoutid,e.docdate as cbdate,f.reqpayno,f.docdate as rpdate
              from cashbankout e
              join cbapinv a on a.cashbankoutid=e.cashbankoutid
              join invoiceap b on b.invoiceapid=a.invoiceapid
              join addressbook c on c.addressbookid=b.addressbookid
              join account d on d.accountid=a.accountid
              join reqpay f on f.reqpayid=e.reqpayid
              where e.recordstatus=3 and e.companyid = " . $companyid . " and c.fullname like '%" . $supplier . "%' and e.cashbankoutno like '%".$invoice."%'
              and e.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
              and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
              order by cashbankoutno";
		
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
      
        foreach($dataReader as $row)
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
            ->setCellValueByColumnAndRow(2,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
            ->setCellValueByColumnAndRow(7,1,GetCompanyCode($companyid));
        $line=4;
        $totalamount1=0;$totalpayamount1=0;$totalsisa1=0;       
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,'No :')
                    ->setCellValueByColumnAndRow(1,$line,$row['cashbankoutno'])
                    ->setCellValueByColumnAndRow(2,$line,'No :')
                    ->setCellValueByColumnAndRow(3,$line,$row['reqpayno']);														
            $line++;

            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,'Tgl :')
                ->setCellValueByColumnAndRow(1,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row['cbdate'])))
                ->setCellValueByColumnAndRow(2,$line,'Tgl :')
                ->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row['rpdate'])));														
            $line++;

            $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,'No')
                    ->setCellValueByColumnAndRow(1,$line,'No. Invoice')
                    ->setCellValueByColumnAndRow(2,$line,'Customer')					
                    ->setCellValueByColumnAndRow(3,$line,'Tgl Invoice')
                    ->setCellValueByColumnAndRow(4,$line,'Saldo Invoice')
                    ->setCellValueByColumnAndRow(5,$line,'Nilai Bayar')
                    ->setCellValueByColumnAndRow(6,$line,'Akun')
                    ->setCellValueByColumnAndRow(7,$line,'Sisa');
            $line++;
            $sql1 = " select b.invoiceno,c.fullname,b.invoicedate,b.amount,a.payamount,d.accountname,b.amount - a.payamount as sisa
                    from cbapinv a
                    join invoiceap b on b.invoiceapid=a.invoiceapid
                    join addressbook c on c.addressbookid=b.addressbookid
                    join account d on d.accountid=a.accountid
                    where a.cashbankoutid = '".$row['cashbankoutid']."'  ";
			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
          $i=0;$totalamount=0;$totalpayamount=0;$totalsisa=0;
          
          foreach($dataReader1 as $row1)
          {
            $i+=1;
            $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(0,$line,$i)
              ->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
              ->setCellValueByColumnAndRow(2,$line,$row1['fullname'])
              ->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])))
              ->setCellValueByColumnAndRow(4,$line,$row1['amount'])
              ->setCellValueByColumnAndRow(5,$line,$row1['payamount'])					
              ->setCellValueByColumnAndRow(6,$line,$row1['accountname'])
              ->setCellValueByColumnAndRow(7,$line,$row1['sisa']);
            $line+=1;
            
            $totalamount += $row1['amount'];
            $totalpayamount += $row1['payamount'];
            $totalsisa += $row1['sisa'];
            
          }
          $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(1,$line,'TOTAL '.$row['cashbankoutno'])										
              ->setCellValueByColumnAndRow(4,$line,($totalamount))
              ->setCellValueByColumnAndRow(5,$line,($totalpayamount))
              ->setCellValueByColumnAndRow(7,$line,($totalsisa));
          $line+=2;
          $totalamount1 += $totalamount;
          $totalpayamount1 += $totalpayamount;
          $totalsisa1 += $totalsisa;
          
		}
      
      $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(1,$line,'GRAND TOTAL ')										
              ->setCellValueByColumnAndRow(4,$line,($totalamount1))
              ->setCellValueByColumnAndRow(5,$line,($totalpayamount1))
              ->setCellValueByColumnAndRow(7,$line,($totalsisa1));
      $line+=2;
    }
    
    
    $this->getFooterXLS($this->phpExcel);
  }
  public function KartuHutangXLS($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    $this->menuname = 'kartuhutang';
    parent::actionDownxls();
    $penambahan1 = 0;
    $dibayar1    = 0;
    $bank1       = 0;
    $diskon1     = 0;
    $retur1      = 0;
    $ob1         = 0;
    $saldo1      = 0;
    $sql         = "select distinct addressbookid,fullname,saldoawal
						from (select a.addressbookid,a.fullname,
						ifnull((select sum(ifnull(d.amount,0)-ifnull((select sum(ifnull(b.payamount,0))
						from cbapinv b
						join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid 
						and c.docdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0) as saldoawal,
						ifnull((select sum(ifnull(d.amount,0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as hutang,
						ifnull((select sum(ifnull((select sum(ifnull(b.payamount,0))
						from cbapinv b
						join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid 
						and c.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as dibayar
						from addressbook a where a.fullname like '%" . $supplier . "%') z
						where z.saldoawal<>0 or z.hutang<>0 or z.dibayar<>0
						order by fullname";
    $command     = $this->connection->createCommand($sql);
    $dataReader  = $command->queryAll();
    foreach ($dataReader as $row)
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))->setCellValueByColumnAndRow(3, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))->setCellValueByColumnAndRow(4, 1, GetCompanyCode($companyid));
    $line = 4;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $row['fullname']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dokumen')->setCellValueByColumnAndRow(1, $line, 'Tanggal')->setCellValueByColumnAndRow(2, $line, 'U/ Byr INV')->setCellValueByColumnAndRow(3, $line, 'Penambahan')->setCellValueByColumnAndRow(4, $line, 'Dibayar')->setCellValueByColumnAndRow(5, $line, 'Saldo');
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Saldo Awal')->setCellValueByColumnAndRow(5, $line, ($row['saldoawal'] / $per));
      $line++;
      $penambahan  = 0;
      $dibayar     = 0;
      $bank        = 0;
      $diskon      = 0;
      $retur       = 0;
      $ob          = 0;
      $sql1        = "select * from
				(select a.invoiceno as dokumen,a.invoicedate as tanggal,ifnull(b.grno,'-') as ref,a.amount as penambahan,'0' as dibayar
				from invoiceap a
				join grheader b on b.grheaderid=a.grheaderid
				join poheader c on c.poheaderid=b.poheaderid
				where a.recordstatus=3 and a.companyid=" . $companyid . " 
				and a.addressbookid=" . $row['addressbookid'] . "
				and a.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
				and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					union
				select d.cashbankoutno as dokumen,d.docdate as tanggal,concat(ifnull(h.grno,'-'),' / ',ifnull(g.invoiceno,'-')) as ref,'0' as penambahan,c.payamount as dibayar
				from cbapinv c
				join cashbankout d on d.cashbankoutid=c.cashbankoutid
				join invoiceap g on g.invoiceapid=c.invoiceapid
				join grheader h on h.grheaderid=g.grheaderid
				where d.recordstatus=3
				and d.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				and c.invoiceapid in (select b.invoiceapid
				from invoiceap b
				join grheader e on e.grheaderid=b.grheaderid
				join poheader f on f.poheaderid=e.poheaderid
				where b.recordstatus=3
				and b.companyid=" . $companyid . " and b.addressbookid = " . $row['addressbookid'] . "
				and b.receiptdate<='" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
				) z
				order by tanggal,dokumen";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $row1['dokumen'])->setCellValueByColumnAndRow(1, $line, $row1['tanggal'])->setCellValueByColumnAndRow(2, $line, $row1['ref'])->setCellValueByColumnAndRow(3, $line, ($row1['penambahan'] / $per))->setCellValueByColumnAndRow(4, $line, ($row1['dibayar'] / $per));
        $line++;
        $penambahan += $row1['penambahan'] / $per;
        $dibayar += $row1['dibayar'] / $per;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $line, 'Total ' . $row['fullname'])->setCellValueByColumnAndRow(3, $line, ($penambahan))->setCellValueByColumnAndRow(4, $line, ($dibayar))->setCellValueByColumnAndRow(5, $line, (($row['saldoawal'] / $per) + $penambahan - $dibayar - $bank - $diskon - $retur - $ob));
      $line++;
      $penambahan1 += $penambahan;
      $dibayar1 += $dibayar;
      $saldo1 += $row['saldoawal'] / $per;
      $line += 1;
    }
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Grand Total')->setCellValueByColumnAndRow(2, $line, ($saldo1))->setCellValueByColumnAndRow(3, $line, ($penambahan1))->setCellValueByColumnAndRow(4, $line, ($dibayar1))->setCellValueByColumnAndRow(5, $line, ($saldo1 + $penambahan1 - $dibayar1));
    $line++;
    $this->getFooterXLS($this->phpExcel);
  }
  public function RekapHutangPerSupplierXLS($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    $this->menuname = 'rekaphutangpersupplier';
    parent::actionDownxls();
    $sql        = "select *
						from (select a.fullname,
						ifnull((select sum(ifnull(d.amount,0)-ifnull((select sum(ifnull(b.payamount,0))
						from cbapinv b
						join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid 
						and c.docdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate < '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'),0) as saldoawal,
						ifnull((select sum(ifnull(d.amount,0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as hutang,
						ifnull((select sum(ifnull((select sum(ifnull(b.payamount,0))
						from cbapinv b
						join cashbankout c on c.cashbankoutid=b.cashbankoutid
						where c.recordstatus=3 and b.invoiceapid=d.invoiceapid 
						and c.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0))
						from invoiceap d
						join grheader e on e.grheaderid=d.grheaderid
						join poheader f on f.poheaderid=e.poheaderid
						where d.recordstatus=3 and d.companyid=" . $companyid . " and d.addressbookid=a.addressbookid 
						and d.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'),0) as dibayar
						from addressbook a where a.fullname like '%" . $supplier . "%') z
						where z.saldoawal<>0 or z.hutang<>0 or z.dibayar<>0
						order by fullname";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row)
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))->setCellValueByColumnAndRow(3, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))->setCellValueByColumnAndRow(5, 1, GetCompanyCode($companyid));
    $line = 4;
    $this->phpExcel->getActiveSheet()->getStyle("A4:F4")->getFont()->setBold(true);
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Supplier')->setCellValueByColumnAndRow(2, $line, 'Saldo Awal')->setCellValueByColumnAndRow(3, $line, 'Hutang')->setCellValueByColumnAndRow(4, $line, 'Dibayar')->setCellValueByColumnAndRow(5, $line, 'Saldo Akhir');
    $line++;
    $i          = 0;
    $saldoawal  = 0;
    $hutang     = 0;
    $dibayar    = 0;
    $saldoakhir = 0;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i)->setCellValueByColumnAndRow(1, $line, $row['fullname'])->setCellValueByColumnAndRow(2, $line, ($row['saldoawal'] / $per))->setCellValueByColumnAndRow(3, $line, ($row['hutang'] / $per))->setCellValueByColumnAndRow(4, $line, ($row['dibayar'] / $per))->setCellValueByColumnAndRow(5, $line, (($row['saldoawal'] + $row['hutang'] - $row['dibayar']) / $per));
      $line++;
      $saldoawal += $row['saldoawal'] / $per;
      $hutang += $row['hutang'] / $per;
      $dibayar += $row['dibayar'] / $per;
      $saldoakhir += ($row['saldoawal'] + $row['hutang'] - $row['dibayar']) / $per;
    }
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $line, 'TOTAL')->setCellValueByColumnAndRow(2, $line, ($saldoawal))->setCellValueByColumnAndRow(3, $line, ($hutang))->setCellValueByColumnAndRow(4, $line, ($dibayar))->setCellValueByColumnAndRow(5, $line, ($saldoakhir));
    $line++;
    $line += 1;
    $this->getFooterXLS($this->phpExcel);
  }
  public function RincianPembeliandanReturBeliBelumLunasXLS($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    $this->menuname = 'rincianpembeliandanreturbelibelumlunas';
    parent::actionDownxls();
    $nilaitot1  = 0;
    $dibayar1   = 0;
    $sisa1      = 0;
    $sql        = "select distinct addressbookid,fullname
					from (select *
					from (select d.addressbookid, d.fullname, a.amount,
					ifnull((select sum(payamount) from cbapinv j
					left join cashbankout k on k.cashbankoutid=j.cashbankoutid
					where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
					and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					group by invoiceapid),0) as payamount
					from invoiceap a
					join grheader b on b.grheaderid = a.grheaderid
					join poheader c on c.poheaderid = b.poheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . " 
					and d.fullname like '%" . $supplier . "%'
					and a.invoicedate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
					where amount > payamount) zz
					order by fullname";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row)
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))->setCellValueByColumnAndRow(3, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))->setCellValueByColumnAndRow(8, 1, GetCompanyCode($companyid));
    $line = 4;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $row['fullname']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Dokumen')->setCellValueByColumnAndRow(2, $line, 'Referensi')->setCellValueByColumnAndRow(3, $line, 'Tanggal')->setCellValueByColumnAndRow(4, $line, 'j_tempo')->setCellValueByColumnAndRow(5, $line, 'Umur')->setCellValueByColumnAndRow(6, $line, 'Nilai')->setCellValueByColumnAndRow(7, $line, 'Kum_bayar')->setCellValueByColumnAndRow(8, $line, 'Sisa');
      $line++;
      $sql1        = "select *
				from (select a.invoiceno,b.grno,a.invoicedate,e.paydays,
				date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
				datediff(current_date(),a.invoicedate) as umur,a.amount, 
				ifnull((select sum(payamount) from cbapinv j
				left join cashbankout k on k.cashbankoutid=j.cashbankoutid
				where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
				and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				group by invoiceapid),0) as payamount
				from invoiceap a
				inner join grheader b on b.grheaderid = a.grheaderid
				inner join poheader c on c.poheaderid = b.poheaderid
				inner join addressbook d on d.addressbookid = c.addressbookid
				inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
				where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . "
				and d.addressbookid = '" . $row['addressbookid'] . "'						
				and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
				where z.amount > z.payamount
				order by invoicedate,invoiceno";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i           = 0;
      $nilaitot    = 0;
      $dibayar     = 0;
      $sisa        = 0;
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i)->setCellValueByColumnAndRow(1, $line, $row1['invoiceno'])->setCellValueByColumnAndRow(2, $line, $row1['grno'])->setCellValueByColumnAndRow(3, $line, $row1['invoicedate'])->setCellValueByColumnAndRow(4, $line, $row1['jatuhtempo'])->setCellValueByColumnAndRow(5, $line, $row1['umur'])->setCellValueByColumnAndRow(6, $line, $row1['amount'] / $per)->setCellValueByColumnAndRow(7, $line, $row1['payamount'] / $per)->setCellValueByColumnAndRow(8, $line, ($row1['amount'] / $per) - ($row1['payamount'] / $per));
        $line++;
        $nilaitot += $row1['amount'] / $per;
        $dibayar += $row1['payamount'] / $per;
        $sisa += (($row1['amount'] / $per) - ($row1['payamount'] / $per));
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $line, 'Total')->setCellValueByColumnAndRow(6, $line, $nilaitot)->setCellValueByColumnAndRow(7, $line, $dibayar)->setCellValueByColumnAndRow(8, $line, $sisa);
      $line += 1;
      $nilaitot1 += $nilaitot;
      $dibayar1 += $dibayar;
      $sisa1 += $sisa;
    }
    $line += 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(4, $line, 'GRAND TOTAL ')->setCellValueByColumnAndRow(6, $line, $nilaitot1)->setCellValueByColumnAndRow(7, $line, $dibayar1)->setCellValueByColumnAndRow(8, $line, $sisa1);
    $this->getFooterXLS($this->phpExcel);
  }
  public function RincianUmurHutangperSTTBXLS($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    $this->menuname = 'rincianumurhutangpersttb';
    parent::actionDownxls();
    $total2sd0    = 0;
    $total20sd30  = 0;
    $total231sd60 = 0;
    $total261sd90 = 0;
    $total2sd90   = 0;
    $total2       = 0;
    $sql          = "select distinct addressbookid,fullname
					from (select *
					from (select d.addressbookid, d.fullname, a.amount,
					ifnull((select sum(payamount) from cbapinv j
					left join cashbankout k on k.cashbankoutid=j.cashbankoutid
					where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
					and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
					group by invoiceapid),0) as payamount
					from invoiceap a
					join grheader b on b.grheaderid = a.grheaderid
					join poheader c on c.poheaderid = b.poheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . " 
					and d.fullname like '%" . $supplier . "%'
					and a.invoicedate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "') z
					where amount > payamount) zz
					order by fullname";
    $command      = $this->connection->createCommand($sql);
    $dataReader   = $command->queryAll();
    foreach ($dataReader as $row)
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))->setCellValueByColumnAndRow(9, 1, GetCompanyCode($companyid));
    $line = 4;
    foreach ($dataReader as $row) {
      $this->phpExcel->getActiveSheet()->getStyle(1, $line)->getFont()->setBold(true);
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Supplier')->setCellValueByColumnAndRow(1, $line, $row['fullname']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'No. Invoice')->setCellValueByColumnAndRow(2, $line, 'T.O.P')->setCellValueByColumnAndRow(3, $line, 'Umur')->setCellValueByColumnAndRow(4, $line, 'Belum Jatuh Tempo')->setCellValueByColumnAndRow(5, $line, '0-30 Hari')->setCellValueByColumnAndRow(6, $line, '31-60 Hari')->setCellValueByColumnAndRow(7, $line, '61-90 Hari')->setCellValueByColumnAndRow(8, $line, '>90 Hari')->setCellValueByColumnAndRow(9, $line, 'Total');
      $line++;
      $sql1        = "select z.*,
														case when umurtempo < 0 then totamount else 0 end as sd0,
														case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
														case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
														case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
														case when umurtempo > 90 then totamount else 0 end as sd90
												from
												(select concat(ifnull(a.invoiceno,'-'),' / ',ifnull(b.grno,'-')) as invoiceno, a.invoicedate,
												datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',a.invoicedate) as umur,
												datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
												date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
												a.amount-ifnull((select sum(payamount) from cbapinv j
												left join cashbankout k on k.cashbankoutid=j.cashbankoutid
												where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
												and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
												group by invoiceapid),0) as totamount,e.paydays
												from invoiceap a
												inner join grheader b on b.grheaderid = a.grheaderid
												inner join poheader c on c.poheaderid = b.poheaderid
												inner join addressbook d on d.addressbookid = c.addressbookid
												inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
												where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . "
												and d.addressbookid = '" . $row['addressbookid'] . "'						
												and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')z
												where totamount > 0";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i           = 0;
      $totalsd0    = 0;
      $total0sd30  = 0;
      $total31sd60 = 0;
      $total61sd90 = 0;
      $totalsd90   = 0;
      $total       = 0;
      foreach ($dataReader1 as $row1) {
        $i += 1;
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i)->setCellValueByColumnAndRow(1, $line, $row1['invoiceno'])->setCellValueByColumnAndRow(2, $line, $row1['paydays'])->setCellValueByColumnAndRow(3, $line, $row1['umur'])->setCellValueByColumnAndRow(4, $line, ($row1['sd0'] / $per))->setCellValueByColumnAndRow(5, $line, ($row1['0sd30'] / $per))->setCellValueByColumnAndRow(6, $line, ($row1['31sd60'] / $per))->setCellValueByColumnAndRow(7, $line, ($row1['61sd90'] / $per))->setCellValueByColumnAndRow(8, $line, ($row1['sd90'] / $per))->setCellValueByColumnAndRow(9, $line, (($row1['sd0'] + $row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per));
        $line++;
        $totalsd0 += $row1['sd0'] / $per;
        $total0sd30 += $row1['0sd30'] / $per;
        $total31sd60 += $row1['31sd60'] / $per;
        $total61sd90 += $row1['61sd90'] / $per;
        $totalsd90 += $row1['sd90'] / $per;
        $total += ($row1['sd0'] + $row1['0sd30'] + $row1['31sd60'] + $row1['61sd90'] + $row1['sd90']) / $per;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $line, 'Total')->setCellValueByColumnAndRow(4, $line, ($totalsd0))->setCellValueByColumnAndRow(5, $line, ($total0sd30))->setCellValueByColumnAndRow(6, $line, ($total31sd60))->setCellValueByColumnAndRow(7, $line, ($total61sd90))->setCellValueByColumnAndRow(8, $line, ($totalsd90))->setCellValueByColumnAndRow(9, $line, ($total));
      $line++;
      $total2sd0 += $totalsd0;
      $total20sd30 += $total0sd30;
      $total231sd60 += $total31sd60;
      $total261sd90 += $total61sd90;
      $total2sd90 += $totalsd90;
      $total2 += $total;
      $line += 1;
    }
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $line, 'GRAND TOTAL')->setCellValueByColumnAndRow(4, $line, ($total2sd0))->setCellValueByColumnAndRow(5, $line, ($total20sd30))->setCellValueByColumnAndRow(6, $line, ($total231sd60))->setCellValueByColumnAndRow(7, $line, ($total261sd90))->setCellValueByColumnAndRow(8, $line, ($total2sd90))->setCellValueByColumnAndRow(9, $line, ($total2));
    $line++;
    $this->getFooterXLS($this->phpExcel);
  }
  public function RekapUmurHutangperSupplierXLS($companyid, $sloc, $product, $supplier, $invoice, $startdate, $enddate, $per)
  {
    $this->menuname = 'rekapumurhutangpersupplier';
    parent::actionDownxls();
    $sql        = "select *,sum(sd0) as belumjatuhtempo, sum(0sd30) as sum0sd30,sum(31sd60) as sum31sd60, sum(61sd90) as sum61sd90, sum(sd90) as sumsd90
				from (select *,
				case when umurtempo < 0 then totamount else 0 end as sd0,
				case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
				case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
				case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
				case when umurtempo > 90 then totamount else 0 end as sd90
				from (select a.invoicedate,
				datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',a.invoicedate) as umur,
				datediff('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "',date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
				date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,d.fullname,
				a.amount-ifnull((select sum(payamount) from cbapinv j
				left join cashbankout k on k.cashbankoutid=j.cashbankoutid
				where k.recordstatus=3 and j.invoiceapid=a.invoiceapid
				and k.docdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
				group by invoiceapid),0) as totamount,e.paydays
				from invoiceap a
				inner join grheader b on b.grheaderid = a.grheaderid
				inner join poheader c on c.poheaderid = b.poheaderid
				inner join addressbook d on d.addressbookid = c.addressbookid
				inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
				where a.recordstatus=3 and a.invoiceno is not null and c.companyid = " . $companyid . "
				and a.receiptdate <= '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')z 
				where totamount>0)zz
				group by fullname
				order by fullname";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row)
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))->setCellValueByColumnAndRow(7, 1, GetCompanyCode($companyid));
    $line        = 4;
    $i           = 0;
    $totalsd0    = 0;
    $total0sd30  = 0;
    $total31sd60 = 0;
    $total61sd90 = 0;
    $totalsd90   = 0;
    $total       = 0;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Supplier')->setCellValueByColumnAndRow(2, $line, 'Belum Jatuh Tempo')->setCellValueByColumnAndRow(3, $line, '0-30 Hari')->setCellValueByColumnAndRow(4, $line, '31-60 Hari')->setCellValueByColumnAndRow(5, $line, '61-90 Hari')->setCellValueByColumnAndRow(6, $line, '>90 Hari')->setCellValueByColumnAndRow(7, $line, 'Total');
    $line++;
    foreach ($dataReader as $row) {
      $i += 1;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i)->setCellValueByColumnAndRow(1, $line, $row['fullname'])->setCellValueByColumnAndRow(2, $line, ($row['belumjatuhtempo'] / $per))->setCellValueByColumnAndRow(3, $line, ($row['sum0sd30'] / $per))->setCellValueByColumnAndRow(4, $line, ($row['sum31sd60'] / $per))->setCellValueByColumnAndRow(5, $line, ($row['sum61sd90'] / $per))->setCellValueByColumnAndRow(6, $line, ($row['sumsd90'] / $per))->setCellValueByColumnAndRow(7, $line, (($row['belumjatuhtempo'] + $row['sum0sd30'] + $row['sum31sd60'] + $row['sum61sd90'] + $row['sumsd90']) / $per));
      $line++;
      $totalsd0 += $row['belumjatuhtempo'] / $per;
      $total0sd30 += $row['sum0sd30'] / $per;
      $total31sd60 += $row['sum31sd60'] / $per;
      $total61sd90 += $row['sum61sd90'] / $per;
      $totalsd90 += $row['sumsd90'] / $per;
      $total += ($row['belumjatuhtempo'] + $row['sum0sd30'] + $row['sum31sd60'] + $row['sum61sd90'] + $row['sumsd90']) / $per;
    }
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $line, 'Total')->setCellValueByColumnAndRow(2, $line, ($totalsd0))->setCellValueByColumnAndRow(3, $line, ($total0sd30))->setCellValueByColumnAndRow(4, $line, ($total31sd60))->setCellValueByColumnAndRow(5, $line, ($total61sd90))->setCellValueByColumnAndRow(6, $line, ($totalsd90))->setCellValueByColumnAndRow(7, $line, ($total));
    $line++;
    $this->getFooterXLS($this->phpExcel);
  }
}