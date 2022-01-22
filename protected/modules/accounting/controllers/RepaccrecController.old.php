<?php
class RepaccrecController extends Controller
{
	public $menuname = 'repaccrec';
	public function actionIndex()
	{
		$this->renderPartial('index',array());
	}
    public function actionDownPDF()
	{
		parent::actionDownload();
		if (isset($_GET['lro']) && isset($_GET['company']) && isset($_GET['sloc']) && isset($_GET['materialgroup']) && isset($_GET['customer']) && isset($_GET['product']) && isset($_GET['sales']) && isset($_GET['spv']) && isset($_GET['salesarea']) && isset($_GET['umurpiutang']) && isset($_GET['isdisplay']) && isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['per']))
		{
			$uri = $_SERVER['REQUEST_URI'];
			$start = strpos($uri,'customer');
			$end  = strpos($uri,'&product=');
			$new =  substr($uri,$start,$end-$start);
			$customer = urldecode(substr($new,9));
			if ($_GET['lro'] == 99)
			{
				$this->RincianFakturdanReturJualBelumLunasGabungan($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 98)
			{
				$this->RincianFakturdanReturJualBelumLunasSpesial($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 1)
			{
				$this->RincianPelunasanPiutangPerDokumen($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 2)
			{
				$this->RekapPelunasanPiutangPerDivisi($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 3)
			{
				$this->KartuPiutangDagang($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 4)
			{
				$this->RekapPiutangDagangPerCustomer($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 5)
			{
				$this->RincianFakturdanReturJualBelumLunas($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 6)
			{
				$this->RincianUmurPiutangDagangPerCustomer($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 7)
			{
				$this->RekapUmurPiutangDagangPerCustomer($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 8)
			{
				$this->RincianFakturdanReturJualBelumLunasPerSales($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 9)
			{
				$this->RekapKontrolPiutangCustomervsPlafon($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 10)
			{
				$this->RincianKontrolPiutangCustomervsPlafon($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 11)
			{
				$this->KonfirmasiPiutangDagang($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 12)
			{
				$this->RekapInvoiceARPerDokumenBelumStatusMax($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 13)
			{
				$this->RekapNotaReturPenjualanPerDokumenBelumStatusMax($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 14)
			{
				$this->RekapPelunasanPiutangPerDokumenBelumStatusMax($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 15)
			{
				$this->RincianPelunasanPiutangPerSales($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 16)
			{
				$this->RekapPelunasanPiutangPerSales($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 17)
			{
				$this->RincianPelunasanPiutangPerSalesPerJenisBarang($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 18)
			{
				$this->RincianPelunasanPiutangPerSalesPerJenisBarangWithoutOB($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 19)
			{
				$this->RekapPelunasanPiutangPerSalesPerJenisBarang($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 20)
			{
				$this->RekapPenjualanVSPelunasanPerBulanPerCustomer($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 21)
			{
				$this->RekapPiutangVSPelunasanPerBulanPerCustomer($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 22)
			{
				$this->RincianPelunasanPiutangPerCustomer($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 23)
			{
				$this->RekapPelunasanPiutangPerCustomer($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 24)
			{
				$this->RincianPelunasanPiutangPerCustomerPerJenisBarang($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 25)
			{
				$this->RekapPelunasanPiutangPerCustomerPerJenisBarang($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 26)
			{
				$this->RekapUmurPiutangDagang($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 27)
			{
				$this->RekapUmurPiutangDagangPerBulanPerTahun($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 28)
			{
				$this->RincianFakturdanReturJualBelumLunasFilterJTT($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 29)
			{
				$this->RincianPelunasanPiutangFilterTanggalInvoice($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 30)
			{
				$this->RincianPelunasanPiutangFilterTanggalPelunasan($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 31)
			{
				$this->RekapTargetVSTagihan($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 32)
			{
				$this->RincianKomisiKasta($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 33)
			{
				$this->RekapTargetTagihanPerDokumenBelumStatusMax($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 34)
			{
				$this->RekapSkalaKomisiTagihanPerDokumenBelumStatusMax($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 35)
			{
				$this->RekapUmurPiutangDagangPerCustomerVsTop($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 36)
			{
				$this->RekapMonitoringPiutangPerCustomerPerSales($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 37)
			{
				$this->RekapKomisiTagihanPerSPVPerSales($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 38)
			{
				$this->RekapUmurPiutangDagangPerCompany($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 39)
			{
				$this->RekapKomisiKasta($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 40)
			{
				$this->RincianKomisiTagihanPerSPVPerSales($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
		}
	}
	//99
	public function RincianFakturdanReturJualBelumLunasGabungan($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
    parent::actionDownload();
		$nilaitot1 = 0;$dibayar1 = 0;$sisa1 = 0;
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			$isdisplay1= " and c.isdisplay = ".$isdisplay." ";
		}
    $sql = "select distinct addressbookid,fullname,lat,lng,wanumber
					from (select d.addressbookid,d.fullname,a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
					ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,
					(select round(h.lat,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lat,(select round(h.lng,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lng,
					ifnull((select h.wanumber from addresscontact h where h.addressbookid=d.addressbookid Limit 1),'') as wanumber
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid
					where e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null
					and d.fullname like '%".$customer."%' ".$isdisplay1."
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount
		";
		if ($_GET['umurpiutang'] !== '') 
		{
				$sql = $sql . "and  umur > ".$_GET['umurpiutang']." order by fullname";
		}
		else 
		{
				$sql = $sql . "order by fullname";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
		$this->pdf->companyid = $companyid;
		
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			if($isdisplay == "1")
			{
				$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas Gabungan (HANYA DISPLAY)';
			}
			else if($isdisplay == "0")
			{
				$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas Gabungan (BUKAN DISPLAY)';
			}
		}
		else
		{
			$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas Gabungan';
		}
		//$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas Gabungan';
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');

		$this->pdf->sety($this->pdf->gety()+0);
	 
		foreach($dataReader as $row)
		{                
			$this->pdf->SetFont('Arial','',10);
			if ($row['wanumber'] > 0) {$wanumber = '';} else {$wanumber = ' - (BELUM ADA NOMOR WHATSAPP)';}
			$this->pdf->text(10,$this->pdf->gety()+5,$row['fullname'].$wanumber);
                $this->pdf->text(145,$this->pdf->gety()+5,'Koordinat:');
                $this->pdf->text(168,$this->pdf->gety()+5,$row['lat'].',');
                $this->pdf->text(185,$this->pdf->gety()+5,$row['lng']);
			$sql1 = " select *, (amount-payamount) as sisa,(amount) as nilai
							from (select if(c.isdisplay=1,concat(a.invoiceno,'_D'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
							date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,ff.fullname as sales,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
							from cutarinv f
							join cutar g on g.cutarid=f.cutarid
							where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,c.companyid,gg.companycode
							from invoice a
							inner join giheader b on b.giheaderid = a.giheaderid
							inner join soheader c on c.soheaderid = b.soheaderid
							inner join addressbook d on d.addressbookid = c.addressbookid
							inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
							inner join employee ff on ff.employeeid = c.employeeid
							inner join company gg on gg.companyid = a.companyid

							where d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null
							and d.addressbookid = '".$row['addressbookid']."'	".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
			where amount > payamount
			";
			if ($_GET['umurpiutang'] !== '') 
			{
					$sql1 = $sql1 . "and  umur > ".$_GET['umurpiutang']." order by umurtempo desc,invoiceno";
			}
			else 
			{
					$sql1 = $sql1 . "order by umurtempo desc,invoiceno";
			}
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			//$piutang =0;
			//$dibayar=0;
			//$saldo=0;
			
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,32,20,20,11,11,25,22,25,25));
			$this->pdf->colheader = array('No','Dokumen','Tanggal','j_tempo','Umur','UT','Nilai','Kum_bayar','Sisa','Sales');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','L','C','C','C','C','R','R','R','L');
			$this->pdf->setFont('Arial','',8);
			$i=0;
			$nilaitot = 0;
			$dibayar = 0;
			$sisa = 0;
			foreach($dataReader1 as $row1)
				{
					$i+=1;
					$this->pdf->row(array(
						$i,$row1['invoiceno'].'-'.$row1['companycode'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
						date(Yii::app()->params['dateviewfromdb'], strtotime($row1['jatuhtempo'])),
						$row1['umur'],
						$row1['umurtempo'],
						Yii::app()->format->formatCurrency($row1['nilai']/$per),
						Yii::app()->format->formatCurrency($row1['payamount']/$per),
						Yii::app()->format->formatCurrency(($row1['nilai']-$row1['payamount'])/$per),
						$row1['sales'],
          ));
					$nilaitot += $row1['nilai']/$per;
					$dibayar += $row1['payamount']/$per;
					$sisa += ($row1['nilai']-$row1['payamount'])/$per;
						
					$this->pdf->checkPageBreak(20);
				}
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->row(array(
					'','','','Total:','','',
					Yii::app()->format->formatCurrency($nilaitot),
					Yii::app()->format->formatCurrency($dibayar),
					Yii::app()->format->formatCurrency($sisa),
				));
				$nilaitot1 += $nilaitot;
				$dibayar1 += $dibayar;
				$sisa1 += $sisa;
            }
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->setFont('Arial','BI',9);
			$this->pdf->coldetailalign = array('R','R','R','R');
			$this->pdf->setwidths(array(100,30,35,30));
			$this->pdf->row(array(
			'GRAND TOTAL',
			Yii::app()->format->formatCurrency($nilaitot1),
			Yii::app()->format->formatCurrency($dibayar1),
			Yii::app()->format->formatCurrency($sisa1),
			));
			
			$this->pdf->Output();
  }
	//98
	public function RincianFakturdanReturJualBelumLunasSpesial($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
    parent::actionDownload();
		$nilaitot1 = 0;$dibayar1 = 0;$sisa1 = 0;
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			$isdisplay1= " and c.isdisplay = ".$isdisplay." ";
		}
    $sql = "select distinct addressbookid,fullname,lat,lng,wanumber
					from (select b.giheaderid,d.addressbookid,d.fullname,a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
					ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,
					(select round(h.lat,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lat,(select round(h.lng,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lng,
					ifnull((select h.wanumber from addresscontact h where h.addressbookid=d.addressbookid Limit 1),'') as wanumber
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid
					left join salesarea f on f.salesareaid = d.salesareaid

					where e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
					and d.fullname like '%".$customer."%' and f.areaname like '%".$salesarea."%' ".$isdisplay1." and a.invoiceno like '%-AGEM%'
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount
		";
		if ($sloc !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join sloc l on l.slocid=k.slocid where l.sloccode like '%".$sloc."%') ";
		}
		if ($materialgroup !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join productplant l on l.productid=k.productid and k.slocid=l.slocid and k.unitofmeasureid=l.unitofissue join materialgroup m on m.materialgroupid=l.materialgroupid where m.description like '%".$materialgroup."%') ";
		}
		if ($product !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join product l on l.productid=k.productid where l.productname like '%".$product."%') ";
		}
		if ($umurpiutang !== '') 
		{
				$sql = $sql . "and  umur > ".$umurpiutang." ";
		}
		$sql = $sql . "order by fullname";
					
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
		$this->pdf->companyid = $companyid;
    
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			if($isdisplay == "1")
			{
				$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas (HANYA DISPLAY)';
			}
			else if($isdisplay == "0")
			{
				$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas (BUKAN DISPLAY)';
			}
		}
		else
		{
			$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas';
		}
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');

		$this->pdf->sety($this->pdf->gety()+0);
	 
		foreach($dataReader as $row)
		{                
			$this->pdf->SetFont('Arial','',10);
			if ($row['wanumber'] > 0) {$wanumber = '';} else {$wanumber = ' - (BELUM ADA NOMOR WHATSAPP)';}
			$this->pdf->text(10,$this->pdf->gety()+5,$row['fullname'].$wanumber);
			$this->pdf->text(145,$this->pdf->gety()+5,'Koordinat:');
			$this->pdf->text(168,$this->pdf->gety()+5,$row['lat'].',');
			$this->pdf->text(185,$this->pdf->gety()+5,$row['lng']);
			$sql1 = " select *, (amount-payamount) as sisa,(amount) as nilai
							from (select b.giheaderid,if(c.isdisplay=1,concat(a.invoiceno,'_D'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
							date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,ff.fullname as sales,
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

							where d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." and a.invoiceno like '%-AGEM%'
							and d.addressbookid = '".$row['addressbookid']."' ".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
							where amount > payamount
			";
			if ($sloc !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join sloc l on l.slocid=k.slocid where l.sloccode like '%".$sloc."%') ";
			}
			if ($materialgroup !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join productplant l on l.productid=k.productid and k.slocid=l.slocid and k.unitofmeasureid=l.unitofissue join materialgroup m on m.materialgroupid=l.materialgroupid where m.description like '%".$materialgroup."%') ";
			}
			if ($product !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join product l on l.productid=k.productid where l.productname like '%".$product."%') ";
			}
			if ($umurpiutang !== '') 
			{
					$sql1 = $sql1 . "and  umur > ".$umurpiutang." ";
			}
			$sql1 = $sql1 . "order by umurtempo desc";
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			//$piutang =0;
			//$dibayar=0;
			//$saldo=0;
			
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,25,18,18,11,11,25,25,25,25));
			$this->pdf->colheader = array('No','Dokumen','Tanggal','j_tempo','Umur','UT','Nilai','Kum_bayar','Sisa','Sales');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','C','C','C','C','C','R','R','R','L');
			$this->pdf->setFont('Arial','',8);
			$i=0;
			$nilaitot = 0;
			$dibayar = 0;
			$sisa = 0;
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->row(array(
					$i,$row1['invoiceno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
					date(Yii::app()->params['dateviewfromdb'], strtotime($row1['jatuhtempo'])),
					$row1['umur'],
					$row1['umurtempo'],
					Yii::app()->format->formatCurrency($row1['nilai']/$per),
					Yii::app()->format->formatCurrency($row1['payamount']/$per),
					Yii::app()->format->formatCurrency(($row1['nilai']-$row1['payamount'])/$per),
					$row1['sales'],
				));
				$nilaitot += $row1['nilai']/$per;
				$dibayar += $row1['payamount']/$per;
				$sisa += ($row1['nilai']-$row1['payamount'])/$per;
				
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->row(array(
				'','','','','Total:','',
				Yii::app()->format->formatCurrency($nilaitot),
				Yii::app()->format->formatCurrency($dibayar),
				Yii::app()->format->formatCurrency($sisa),
			));
			$nilaitot1 += $nilaitot;
			$dibayar1 += $dibayar;
			$sisa1 += $sisa;
		}
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->setFont('Arial','BI',9);
		$this->pdf->coldetailalign = array('R','R','R','R');
		$this->pdf->setwidths(array(95,30,35,30));
		$this->pdf->row(array(
			'GRAND TOTAL',
			Yii::app()->format->formatCurrency($nilaitot1),
			Yii::app()->format->formatCurrency($dibayar1),
			Yii::app()->format->formatCurrency($sisa1),
		));
		$this->pdf->Output();
	}
	//1
	public function RincianPelunasanPiutangPerDokumen($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$totalall = 0;$totalall1 = 0;$totalall2 = 0;$totalall3 = 0;$totalall4 = 0;$totalall5 = 0;$totalall6 = 0;$totalall7 = 0;
		$sql = "select distinct a.cutarid,a.cutarno,a.docdate as cutardate,c.docno as ttntno,c.docdate as ttntdate,b.companyid
						from cutar a
						join company b on b.companyid = a.companyid
						join ttnt c on c.ttntid = a.ttntid
						join cutarinv d on d.cutarid = a.cutarid
						join invoice e on e.invoiceid = d.invoiceid
						join giheader f on f.giheaderid = e.giheaderid
						join soheader g on g.soheaderid = f.soheaderid	
						join ttnt m on m.ttntid=a.ttntid					
						left join employee h on h.employeeid = m.employeeid
						join addressbook i on i.addressbookid = g.addressbookid
						where i.fullname like '%".$customer."%' and h.fullname like '%".$sales."%' and a.cutarno is not null 
						and a.companyid = ".$companyid." and a.recordstatus=3
						and	a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if ($product !== '') 
		{
			$sql = $sql . " and	f.giheaderid in (select distinct a0.giheaderid from gidetail a0 join product a1 on a1.productid=a0.productid where a1.productname like '%".$product."%') ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
		$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rincian Pelunasan Piutang Per Dokumen';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		// definisi font
                
		foreach($dataReader as $row)
		{
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->text(10,$this->pdf->gety()+2,'No ');$this->pdf->text(30,$this->pdf->gety()+2,': '.$row['cutarno']);
			$this->pdf->text(160,$this->pdf->gety()+2,'TTNT ');$this->pdf->text(170,$this->pdf->gety()+2,': '.$row['ttntno']);
			$this->pdf->text(10,$this->pdf->gety()+6,'Tgl ');$this->pdf->text(30,$this->pdf->gety()+6,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['cutardate'])));
			$this->pdf->text(160,$this->pdf->gety()+6,'Tgl ');$this->pdf->text(170,$this->pdf->gety()+6,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['ttntdate'])));
			if ($product !== '') 
			{
				$whereproduct = " and d.giheaderid in (select distinct a0.giheaderid from gidetail a0 join product a1 on a1.productid=a0.productid where a1.productname like '%".$product."%') ";
			}
			else
			{
				$whereproduct = "";
			}
			$sql1 = "select*,(saldo-(tunai+bank+diskon+retur+ob)) as sisa
						from(select f.fullname,c.invoiceno,c.invoicedate,sum(saldoinvoice)as saldo,sum(cashamount) as tunai,sum(a.bankamount) as bank,sum(a.discamount) as diskon,sum(a.returnamount) as retur,sum(a.obamount) as ob,
						sum(cashamount)+sum(a.bankamount)+sum(a.discamount)+sum(a.returnamount)+sum(a.obamount) as jumlah
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid = a.invoiceid
						join giheader d on d.giheaderid = c.giheaderid
						join soheader e on e.soheaderid= d.soheaderid
						join addressbook f on f.addressbookid = e.addressbookid	
						join ttnt h on h.ttntid=b.ttntid
						left join employee g on g.employeeid = h.employeeid
						where f.fullname like '%".$customer."%' and g.fullname like '%".$sales."%' and b.recordstatus=3 and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'	and a.cutarid = ".$row['cutarid']." {$whereproduct}
						group by invoiceno)z
						";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
									
			$total = 0;$totalqty=0;
			$total1 = 0;$totalqty1=0;
			$total2 = 0;$totalqty2=0;
			$total3 = 0;$totalqty3=0;
			$total4 = 0;$totalqty4=0;
			$total5 = 0;$totalqty5=0;
			$total6 = 0;$totalqty6=0;
			$total7 = 0;$totalqty7=0;
			$this->pdf->setFont('Arial','',8);
			$this->pdf->sety($this->pdf->gety()+10);    
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,22,30,20,25,25,25,25,25,25,25,25));
			$this->pdf->colheader = array('No','No Invoice','Customer','Tgl Invoice','Saldo Invoice','Tunai','Bank','Diskon','Retur','OB','Jumlah','Sisa');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','L','L','C','R','R','R','R','R','R','R','R');
			$i=0;
			foreach($dataReader1 as $row1)							
			{
				$i=$i+1;
				$this->pdf->row(array($i,$row1['invoiceno'],
				$row1['fullname'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
				Yii::app()->format->formatCurrency($row1['saldo']/$per),						
				Yii::app()->format->formatCurrency($row1['tunai']/$per),
				Yii::app()->format->formatCurrency($row1['bank']/$per),
				Yii::app()->format->formatCurrency($row1['diskon']/$per),
				Yii::app()->format->formatCurrency($row1['retur']/$per),
				Yii::app()->format->formatCurrency($row1['ob']/$per),                        
				Yii::app()->format->formatCurrency($row1['jumlah']/$per),
				Yii::app()->format->formatCurrency($row1['sisa']/$per),
				));
						
				$total += ($row1['saldo']/$per);
				$total1 += ($row1['tunai']/$per);
				$total2 += ($row1['bank']/$per);
				$total3 += ($row1['diskon']/$per);
				$total4 += ($row1['retur']/$per);
				$total5 += ($row1['ob']/$per);
				$total6 += ($row1['jumlah']/$per);
				$total7 += ($row1['sisa']/$per);
			}
					
			$this->pdf->setFont('Arial','BI',8);
			$this->pdf->row(array('','TOTAL ',$row['cutarno'],'',
			Yii::app()->format->formatCurrency($total),					
			Yii::app()->format->formatCurrency($total1),
			Yii::app()->format->formatCurrency($total2),
			Yii::app()->format->formatCurrency($total3),
			Yii::app()->format->formatCurrency($total4),
			Yii::app()->format->formatCurrency($total5),
			Yii::app()->format->formatCurrency($total6),
			Yii::app()->format->formatCurrency($total7)));
			
			$totalall += $total;
			$totalall1 += $total1;
			$totalall2 += $total2;
			$totalall3 += $total3;
			$totalall4 += $total4;
			$totalall5 += $total5;
			$totalall6 += $total6;
			$totalall7 += $total7;
			
			$this->pdf->sety($this->pdf->gety()+10);
		}
		
		$this->pdf->setFont('Arial','BI',8);
		$this->pdf->row(array('','','GRAND TOTAL','',
			Yii::app()->format->formatCurrency($totalall),					
			Yii::app()->format->formatCurrency($totalall1),
			Yii::app()->format->formatCurrency($totalall2),
			Yii::app()->format->formatCurrency($totalall3),
			Yii::app()->format->formatCurrency($totalall4),
			Yii::app()->format->formatCurrency($totalall5),
			Yii::app()->format->formatCurrency($totalall6),
			Yii::app()->format->formatCurrency($totalall7)));
		$this->pdf->Output();
	}
	//2
	public function RekapPelunasanPiutangPerDivisi($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql ="select distinct a.cutarid, sum(cashamount) as tunai,sum(a.bankamount) as bank,sum(a.discamount) as diskon,sum(a.returnamount) as retur,sum(a.obamount) as ob,
				sum(cashamount)+sum(a.bankamount)+sum(a.discamount)+sum(a.returnamount)+sum(a.obamount) as jumlah,
				(select xx.description from materialgroup xx 
				join productplant zz on zz.materialgroupid = xx.materialgroupid
				join sodetail yy on yy.productid = zz.productid
				where yy.soheaderid = e.soheaderid limit 1
				) as materialgroupname
				from cutarinv a
				join cutar b on b.cutarid=a.cutarid
				join invoice c on c.invoiceid=a.invoiceid
				join giheader d on d.giheaderid = c.giheaderid
				join soheader e on e.soheaderid = d.soheaderid 
				join ttnt h on h.ttntid=b.ttntid
				join employee f on f.employeeid = h.employeeid
				join addressbook g on g.addressbookid = e.addressbookid
				where b.companyid = ".$companyid." and g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
				and b.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				group by materialgroupname";

		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Rekap Pelunasan Piutang Per Divisi';
		$this->pdf->subtitle = 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L');
		$this->pdf->setFont('Arial','B',9);
		$this->pdf->sety($this->pdf->gety()+0);
		$this->pdf->colalign = array('C','C','C','C','C','C','C','R');
		$this->pdf->setwidths(array(10,50,38,38,38,30,35,38));
		$this->pdf->colheader = array('No','Devisi/Jenis','Tunai','Bank','Discont','Retur','PDMK/OB','Jumlah');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','R','R','R','C','R','R');		
		$i=0;$cash=0;$bank=0;$obamount=0;$jumlah=0;$retur=0;$diskon=0;$total=0;
		
		foreach($dataReader as $row)
		{
			
			$i+=1;
			$this->pdf->setFont('Arial','',9);
			$this->pdf->row(array(
			$i,$row['materialgroupname'],
			Yii::app()->format->formatCurrency($row['tunai']/$per),
			Yii::app()->format->formatCurrency($row['bank']/$per),
			Yii::app()->format->formatCurrency($row['diskon']/$per),
			Yii::app()->format->formatCurrency($row['retur']/$per),
			Yii::app()->format->formatCurrency($row['ob']/$per),
			Yii::app()->format->formatCurrency($row['jumlah']/$per),
			));

			$cash += $row['tunai']/$per;
			$bank += $row['bank']/$per;
			$diskon += $row['diskon']/$per;
			$retur += $row['retur']/$per;
			$obamount += $row['ob']/$per;
			$total += $row['jumlah']/$per;			
		}
		$this->pdf->setFont('Arial','BI',10);
		$this->pdf->row(array(
		'','GRAND TOTAL',
		Yii::app()->format->formatCurrency($cash),
		Yii::app()->format->formatCurrency($bank),
		Yii::app()->format->formatCurrency($diskon),
		Yii::app()->format->formatCurrency($retur),
		Yii::app()->format->formatCurrency($obamount),
		Yii::app()->format->formatCurrency($total),
		));
		$this->pdf->Output();
	}
	//3
	public function KartuPiutangDagang($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
    parent::actionDownload();
		$penambahan1=0;$tunai1=0;$bank1=0;$diskon1=0;$retur1=0;$ob1=0;$saldo1=0;
    $sql = "select *
						from (select a.fullname,a.addressbookid,
						ifnull((select sum(ifnull(b.amount,0)-ifnull((select sum((ifnull(c.cashamount,0)+ifnull(c.bankamount,0)+ifnull(c.discamount,0)+ifnull(c.returnamount,0)+ifnull(c.obamount,0))*ifnull(c.currencyrate,0))
						from cutarinv c
						join cutar d on d.cutarid=c.cutarid
						where d.recordstatus=3 and c.invoiceid=b.invoiceid and d.companyid=".$companyid."
						and d.docdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),0))
						from invoice b
						join giheader e on e.giheaderid=b.giheaderid
						join soheader f on f.soheaderid=e.soheaderid
						join employee g on g.employeeid = f.employeeid
						join addressbook h on h.addressbookid = f.addressbookid
						where h.fullname like '".$customer."' and g.fullname like '%".$sales."%' and b.recordstatus=3
						and f.companyid=".$companyid."	and b.invoicedate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and f.addressbookid=a.addressbookid),0) as saldo,
						ifnull((select sum(ifnull(h.amount,0))
						from invoice h
						join giheader i on i.giheaderid=h.giheaderid
						join soheader j on j.soheaderid=i.soheaderid
						join employee k on k.employeeid = j.employeeid
						join addressbook l on l.addressbookid = j.addressbookid
						where l.fullname like '%".$customer."%' and k.fullname like '%".$sales."%' and h.recordstatus=3
						and j.companyid=".$companyid." and j.addressbookid=a.addressbookid
						and h.invoicedate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as piutang,
						ifnull((select sum(ifnull((select sum((ifnull(c.cashamount,0)+ifnull(c.bankamount,0)+ifnull(c.discamount,0)+ifnull(c.returnamount,0)+ifnull(c.obamount,0))*ifnull(c.currencyrate,0))
						from cutarinv c
						join cutar d on d.cutarid=c.cutarid
						where d.recordstatus=3 and c.invoiceid=b.invoiceid and d.companyid=".$companyid."
						and d.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0))
						from invoice b
						join giheader e on e.giheaderid=b.giheaderid
						join soheader f on f.soheaderid=e.soheaderid
						join employee g on g.employeeid = f.employeeid
						join addressbook h on h.addressbookid = f.addressbookid
						where h.fullname like '%".$customer."%' and g.fullname like '%".$sales."%' and b.recordstatus=3
						and f.companyid=".$companyid."
						and b.invoicedate<='".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and f.addressbookid=a.addressbookid),0) as dibayar
						from addressbook a
						where a.fullname like '%".$customer."%') z
						where z.saldo<>0 or z.piutang<>0 or z.dibayar<>0
						order by fullname";
				
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
				$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Kartu Piutang Dagang';
		$this->pdf->subtitle = 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L');

		foreach($dataReader as $row)
		{
			$this->pdf->SetFont('Arial','B',10);
			$this->pdf->text(10,$this->pdf->gety()+3,$row['fullname']);
			
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(22,20,20,30,30,30,30,30,30,30));
			$this->pdf->colheader = array('Dokumen','Tanggal','U/ Byr INV','Penambahan','Tunai','Bank','Diskon','Retur','OB','Saldo');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','C','C','R','R','R','R','R','R','R');
			
			
			$this->pdf->setFont('Arial','',8);
			$this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->row(array(
				'Saldo Awal','','','','','','','','',
				Yii::app()->format->formatCurrency($row['saldo']/$per),
			));
			
			$penambahan=0;$tunai=0;$bank=0;$diskon=0;$retur=0;$ob=0;
			$sql2 = "select * from
				(select distinct a.invoiceno as dokumen,a.invoicedate as tanggal,'-' as ref,a.amount as penambahan,'0' as tunai,'0' as bank,'0' as diskon,'0' as retur,'0' as ob
				from invoice a
				left join giheader b on b.giheaderid = a.giheaderid
				left join soheader c on c.soheaderid = b.soheaderid
				left join cbarinv d on d.invoiceid = a.invoiceid
				join employee e on e.employeeid = c.employeeid
				join addressbook f on f.addressbookid = c.addressbookid
				where f.fullname like '%".$customer."%' and c.companyid = ".$companyid." and a.recordstatus = 3 and a.invoiceno is not null and
				c.addressbookid = ".$row['addressbookid']." and
				a.invoicedate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
				and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
					union
				select d.cutarno as dokumen,d.docdate as tanggal,g.invoiceno as ref,'0' as penambahan,ifnull(c.cashamount,0) as tunai,ifnull(c.bankamount,0) as bank,ifnull(c.discamount,0) as diskon,
				ifnull(c.returnamount,0) as retur,ifnull(c.obamount,0) as ob
				from cutarinv c
				join cutar d on d.cutarid=c.cutarid
				join invoice g on g.invoiceid=c.invoiceid
				left join giheader h on h.giheaderid = g.giheaderid
				left join soheader i on i.soheaderid = h.soheaderid
				join addressbook j on j.addressbookid = i.addressbookid
				join employee k on k.employeeid = i.employeeid
				where d.recordstatus=3
				and d.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				and c.invoiceid in (select b.invoiceid
				from invoice b
				join giheader e on e.giheaderid=b.giheaderid
				join soheader f on f.soheaderid=e.soheaderid
				join employee g on g.employeeid = f.employeeid
				join addressbook h on h.addressbookid = f.addressbookid
				where h.fullname like '".$customer."' and g.fullname like '%".$sales."%' and b.recordstatus=3
				and f.companyid=".$companyid." and f.addressbookid = ".$row['addressbookid']."
				and b.invoicedate<='".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				) z
				order by tanggal,dokumen";
			$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
			
			foreach($dataReader2 as $row2)
			{
				$this->pdf->SetFont('Arial','',8);				
				$this->pdf->row(array(
					$row2['dokumen'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row2['tanggal'])),
					$row2['ref'],
					Yii::app()->format->formatCurrency($row2['penambahan']/$per),
					Yii::app()->format->formatCurrency(-$row2['tunai']/$per),
					Yii::app()->format->formatCurrency(-$row2['bank']/$per),
					Yii::app()->format->formatCurrency(-$row2['diskon']/$per),
					Yii::app()->format->formatCurrency(-$row2['retur']/$per),
					Yii::app()->format->formatCurrency(-$row2['ob']/$per),
					'',
				));
				$penambahan += $row2['penambahan']/$per;
				$tunai += $row2['tunai']/$per;
				$bank += $row2['bank']/$per;
				$diskon += $row2['diskon']/$per;
				$retur += $row2['retur']/$per;
				$ob += $row2['ob']/$per;
			}
			$this->pdf->SetFont('Arial','B',8);
			$this->pdf->setwidths(array(62,30,30,30,30,30,30,30));
			$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R');				
			$this->pdf->row(array(
				'TOTAL '.$row['fullname'],
				Yii::app()->format->formatCurrency($penambahan),
				Yii::app()->format->formatCurrency(-$tunai),
				Yii::app()->format->formatCurrency(-$bank),
				Yii::app()->format->formatCurrency(-$diskon),
				Yii::app()->format->formatCurrency(-$retur),
				Yii::app()->format->formatCurrency(-$ob),
				Yii::app()->format->formatCurrency($row['saldo']/$per+$penambahan-$tunai-$bank-$diskon-$retur-$ob),
			));		
			$penambahan1 += $penambahan;
			$tunai1 += $tunai;
			$bank1 += $bank;
			$diskon1 += $diskon;
			$retur1 += $retur;
			$ob1 += $ob;
			$saldo1 += $row['saldo']/$per;
			
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->checkPageBreak(5);
		}
		$this->pdf->SetFont('Arial','B',8);
		$this->pdf->setwidths(array(30,30,30,30,30,30,30,30,30));
		$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R','R');				
		$this->pdf->row(array(
			'',
			'',
			'Penambahan',
			'Tunai',
			'Bank',
			'Diskon',
			'Retur',
			'OB',
			'Saldo',
		));
		$this->pdf->SetFont('Arial','BI',8);
		$this->pdf->setwidths(array(30,30,30,30,30,30,30,30,30));
		$this->pdf->coldetailalign = array('C','R','R','R','R','R','R','R','R');				
		$this->pdf->row(array(
			'GRAND TOTAL',
			Yii::app()->format->formatCurrency($saldo1),
			Yii::app()->format->formatCurrency($penambahan1),
			Yii::app()->format->formatCurrency(-$tunai1),
			Yii::app()->format->formatCurrency(-$bank1),
			Yii::app()->format->formatCurrency(-$diskon1),
			Yii::app()->format->formatCurrency(-$retur1),
			Yii::app()->format->formatCurrency(-$ob1),
			Yii::app()->format->formatCurrency($saldo1+$penambahan1-$tunai1-$bank1-$diskon1-$retur1-$ob1),
		));
		
		$this->pdf->Output();
	}
	//4
	public function RekapPiutangDagangPerCustomer($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql = 	"select *
						from (select a.fullname,
						ifnull((select sum(ifnull(b.amount,0)-ifnull((select sum((ifnull(c.cashamount,0)+ifnull(c.bankamount,0)+ifnull(c.discamount,0)+ifnull(c.returnamount,0)+ifnull(c.obamount,0))*ifnull(c.currencyrate,0))
						from cutarinv c
						join cutar d on d.cutarid=c.cutarid
						join invoice e on e.invoiceid = c.invoiceid
						join giheader f on f.giheaderid = e.giheaderid
						join soheader g on g.soheaderid = f.soheaderid
						join employee h on h.employeeid = g.employeeid
						join addressbook i on i.addressbookid = g.addressbookid
						where i.fullname like '%".$customer."%' and h.fullname like '%".$sales."%' and d.recordstatus=3 and c.invoiceid=b.invoiceid and d.companyid=".$companyid."
						and d.docdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),0))
						from invoice b
						join giheader e on e.giheaderid=b.giheaderid
						join soheader f on f.soheaderid=e.soheaderid
						join employee g on g.employeeid = f.employeeid
						join addressbook h on h.addressbookid = f.addressbookid
						where h.fullname like '%".$customer."%' and g.fullname like '%".$sales."%' and b.recordstatus=3
						and f.companyid=".$companyid."	and b.invoicedate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and f.addressbookid=a.addressbookid),0) as saldoawal,
						ifnull((select sum(ifnull(h.amount,0))
						from invoice h
						join giheader i on i.giheaderid=h.giheaderid
						join soheader j on j.soheaderid=i.soheaderid
						join employee k on k.employeeid = j.employeeid
						join addressbook l on l.addressbookid = j.addressbookid					
						where l.fullname like '%".$customer."%' and k.fullname like '%".$sales."%' and h.recordstatus=3
						and j.companyid=".$companyid." and j.addressbookid=a.addressbookid
						and h.invoicedate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as piutang,
						ifnull((select sum(ifnull((select sum((ifnull(c.cashamount,0)+ifnull(c.bankamount,0)+ifnull(c.discamount,0)+ifnull(c.returnamount,0)+ifnull(c.obamount,0))*ifnull(c.currencyrate,0))
						from cutarinv c
						join cutar d on d.cutarid=c.cutarid
						where d.recordstatus=3 and c.invoiceid=b.invoiceid and d.companyid=".$companyid."
						and d.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0))
						from invoice b
						join giheader e on e.giheaderid=b.giheaderid
						join soheader f on f.soheaderid=e.soheaderid
						join employee g on g.employeeid = f.employeeid
						join addressbook h on h.addressbookid = f.addressbookid
						where h.fullname like '%".$customer."%' and g.fullname like '%".$sales."%' and b.recordstatus=3
						and f.companyid=".$companyid."
						and b.invoicedate<='".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and f.addressbookid=a.addressbookid),0) as dibayar
						from addressbook a
						where a.fullname like '%".$customer."%') z
						where z.saldoawal<>0 or z.piutang<>0 or z.dibayar<>0
						order by fullname";


		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
				$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rekap Piutang Dagang Per Customer';
		$this->pdf->subtitle = 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->sety($this->pdf->gety()+0);
		$this->pdf->colalign = array('C','C','C','C','C','C');
		$this->pdf->setwidths(array(10,50,30,30,30,40));
		$this->pdf->colheader = array('No','Customer','Saldo Awal','Piutang','Dibayar','Saldo Akhir');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','L','R','R','R','R');		
		$i=0;$saldoawal=0;$piutang=0;$dibayar=0;$saldoakhir=0;
		
		foreach($dataReader as $row)
		{
			
			$i+=1;
			$this->pdf->setFont('Arial','',7);
			$this->pdf->row(array(
			$i,$row['fullname'],
			Yii::app()->format->formatCurrency($row['saldoawal']/$per),
			Yii::app()->format->formatCurrency($row['piutang']/$per),
			Yii::app()->format->formatCurrency($row['dibayar']/$per),
			Yii::app()->format->formatCurrency(($row['saldoawal'] + $row['piutang'] - $row['dibayar'])/$per),
			));

			$saldoawal += $row['saldoawal']/$per;
			$piutang += $row['piutang']/$per;
			$dibayar += $row['dibayar']/$per;
			$saldoakhir += ($row['saldoawal'] + $row['piutang'] - $row['dibayar'])/$per;			
		}
		$this->pdf->setFont('Arial','BI',9);
		$this->pdf->row(array(
		'','TOTAL',
		Yii::app()->format->formatCurrency($saldoawal),
		Yii::app()->format->formatCurrency($piutang),
		Yii::app()->format->formatCurrency($dibayar),
		Yii::app()->format->formatCurrency($saldoakhir),
		));
		$this->pdf->Output();
	}
	//5
	public function RincianFakturdanReturJualBelumLunas($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
    parent::actionDownload();
		$nilaitot1 = 0;$dibayar1 = 0;$sisa1 = 0;
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			$isdisplay1= " and c.isdisplay = ".$isdisplay." ";
		}
    $sql = "select distinct addressbookid,fullname,lat,lng,wanumber
					from (select b.giheaderid,d.addressbookid,d.fullname,a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
					ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,
					(select round(h.lat,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lat,(select round(h.lng,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lng,
					ifnull((select h.wanumber from addresscontact h where h.addressbookid=d.addressbookid Limit 1),'') as wanumber
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid
					left join salesarea f on f.salesareaid = d.salesareaid

					where e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
					and d.fullname like '%".$customer."%' and f.areaname like '%".$salesarea."%' ".$isdisplay1."
					-- and d.groupcustomerid=4
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount
		";
		if ($sloc !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join sloc l on l.slocid=k.slocid where l.sloccode like '%".$sloc."%') ";
		}
		if ($materialgroup !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join productplant l on l.productid=k.productid and k.slocid=l.slocid and k.unitofmeasureid=l.unitofissue join materialgroup m on m.materialgroupid=l.materialgroupid where m.description like '%".$materialgroup."%') ";
		}
		if ($product !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join product l on l.productid=k.productid where l.productname like '%".$product."%') ";
		}
		if ($umurpiutang !== '') 
		{
				$sql = $sql . "and  umur > ".$umurpiutang." ";
		}
		$sql = $sql . "order by fullname";
					
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
		$this->pdf->companyid = $companyid;
    
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			if($isdisplay == "1")
			{
				$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas (HANYA DISPLAY)';
			}
			else if($isdisplay == "0")
			{
				$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas (BUKAN DISPLAY)';
			}
		}
		else
		{
			$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas';
		}
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');

		$this->pdf->sety($this->pdf->gety()+0);
	 
		foreach($dataReader as $row)
		{                
			$this->pdf->SetFont('Arial','',10);
			if ($row['wanumber'] > 0) {$wanumber = '';} else {$wanumber = ' - (BELUM ADA NOMOR WHATSAPP)';}
			$this->pdf->text(10,$this->pdf->gety()+5,$row['fullname'].$wanumber);
			$this->pdf->text(145,$this->pdf->gety()+5,'Koordinat:');
			$this->pdf->text(168,$this->pdf->gety()+5,$row['lat'].',');
			$this->pdf->text(185,$this->pdf->gety()+5,$row['lng']);
			$sql1 = " select *, (amount-payamount) as sisa,(amount) as nilai
							from (select b.giheaderid,if(c.isdisplay=1,concat(a.invoiceno,'_D'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
							date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,ff.fullname as sales,
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

							where d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
							and d.addressbookid = '".$row['addressbookid']."' ".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
							where amount > payamount
			";
			if ($sloc !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join sloc l on l.slocid=k.slocid where l.sloccode like '%".$sloc."%') ";
			}
			if ($materialgroup !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join productplant l on l.productid=k.productid and k.slocid=l.slocid and k.unitofmeasureid=l.unitofissue join materialgroup m on m.materialgroupid=l.materialgroupid where m.description like '%".$materialgroup."%') ";
			}
			if ($product !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join product l on l.productid=k.productid where l.productname like '%".$product."%') ";
			}
			if ($umurpiutang !== '') 
			{
					$sql1 = $sql1 . "and  umur > ".$umurpiutang." ";
			}
			$sql1 = $sql1 . "order by umurtempo desc";
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			//$piutang =0;
			//$dibayar=0;
			//$saldo=0;
			
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,25,18,18,11,11,25,25,25,25));
			$this->pdf->colheader = array('No','Dokumen','Tanggal','j_tempo','Umur','UT','Nilai','Kum_bayar','Sisa','Sales');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','C','C','C','C','C','R','R','R','L');
			$this->pdf->setFont('Arial','',8);
			$i=0;
			$nilaitot = 0;
			$dibayar = 0;
			$sisa = 0;
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->row(array(
					$i,$row1['invoiceno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
					date(Yii::app()->params['dateviewfromdb'], strtotime($row1['jatuhtempo'])),
					$row1['umur'],
					$row1['umurtempo'],
					Yii::app()->format->formatCurrency($row1['nilai']/$per),
					Yii::app()->format->formatCurrency($row1['payamount']/$per),
					Yii::app()->format->formatCurrency(($row1['nilai']-$row1['payamount'])/$per),
					$row1['sales'],
				));
				$nilaitot += $row1['nilai']/$per;
				$dibayar += $row1['payamount']/$per;
				$sisa += ($row1['nilai']-$row1['payamount'])/$per;
				
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->row(array(
				'','','','','Total:','',
				Yii::app()->format->formatCurrency($nilaitot),
				Yii::app()->format->formatCurrency($dibayar),
				Yii::app()->format->formatCurrency($sisa),
			));
			$nilaitot1 += $nilaitot;
			$dibayar1 += $dibayar;
			$sisa1 += $sisa;
		}
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->setFont('Arial','BI',9);
		$this->pdf->coldetailalign = array('R','R','R','R');
		$this->pdf->setwidths(array(95,30,35,30));
		$this->pdf->row(array(
			'GRAND TOTAL',
			Yii::app()->format->formatCurrency($nilaitot1),
			Yii::app()->format->formatCurrency($dibayar1),
			Yii::app()->format->formatCurrency($sisa1),
		));
		$this->pdf->Output();
	}
	//6
	public function RincianUmurPiutangDagangPerCustomer($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
            parent::actionDownload();
						$total2sd0 = 0;
						$total20sd30 = 0;
                        $total231sd60 = 0;
                        $total261sd90 = 0;
                        $total291sd120 = 0;
						$total2sd120 = 0;
						$totaltempo2 = 0;
						$total2 = 0;
			//$this->pdf->AddPage('L');
            $sql ="select distinct addressbookid,fullname
					from (select d.addressbookid,d.fullname,a.amount,
					ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
						from cutarinv o
						join cutar p on p.cutarid=o.cutarid
						where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid
					where e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
					and d.fullname like '%".$customer."%' 
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount order by fullname";
								
            $dataReader=Yii::app()->db->createCommand($sql)->queryAll();
            
                $this->pdf->companyid = $companyid;
            
            $this->pdf->title='Rincian Umur Piutang Dagang Per Customer';
            $this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('L');
			
            $this->pdf->sety($this->pdf->gety()+0);
            
            foreach($dataReader as $row)
            {
                $this->pdf->SetFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->gety()+3,'Customer');$this->pdf->text(30,$this->pdf->gety()+3,': '.$row['fullname']);
                $sql1 = "select z.*,
														case when umurtempo < 0 then totamount else 0 end as sd0,
														case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
														case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
														case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
														case when umurtempo > 90 and umurtempo <= 120 then totamount else 0 end as 91sd120,
														case when umurtempo > 120 then totamount else 0 end as sd120
												from
												(select distinct a.invoiceno, a.invoicedate, datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
												datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate,interval d.paydays day)) as umurtempo,
												date_add(a.invoicedate,interval d.paydays day) as jatuhtempo,
												a.amount-ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
													from cutarinv o
													join cutar p on p.cutarid=o.cutarid
													where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as totamount,d.paydays
												from invoice a
												join giheader b on b.giheaderid = a.giheaderid
												join soheader c on c.soheaderid = b.soheaderid
												join paymentmethod d on d.paymentmethodid = c.paymentmethodid
												join employee e on e.employeeid = c.employeeid
												where  e.fullname like '%".$sales."%' and c.companyid = ".$companyid." and a.recordstatus = 3 and c.addressbookid = '".$row['addressbookid']."' 
												and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
												where totamount > 0
												order by invoicedate";



                $dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
                $this->pdf->sety($this->pdf->gety()+6);
                $this->pdf->setFont('Arial','',8);
								$this->pdf->colalign = array('L','L','L','L','L','L','C','R','R');
                $this->pdf->setwidths(array(8,22,12,12,28,28,112,28,30));
								$this->pdf->colheader = array('|','|','|','|','|','|','Sudah Jatuh Tempo','|','|');
								$this->pdf->RowHeader();
								$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
                $this->pdf->setwidths(array(8,22,12,12,28,28,28,28,28,28,28,30));
                $this->pdf->colheader = array('No','No Invoice','T.O.P','Umur','Belum Jatuh Tempo','0-30 Hari','31-60 Hari','61-90 Hari','91-120 Hari','> 120 Hari','Jumlah','Total');
                $this->pdf->RowHeader();
                $this->pdf->coldetailalign = array('L','C','R','R','R','R','R','R','R','R','R','R');
                $this->pdf->setFont('Arial','',8);
						$totalsd0 = 0;
						$total0sd30 = 0;
            $total31sd60 = 0;
            $total61sd90 = 0;
            $total91sd120 = 0;
						$totalsd120 = 0;
						$totaltempo = 0;
						$total = 0;$i=0;
                foreach($dataReader1 as $row1)
                {
                    $i+=1;
                        $this->pdf->row(array(
                                $i,$row1['invoiceno'],
								$row1['paydays'],$row1['umur'],
								Yii::app()->format->formatCurrency($row1['sd0']/$per),
								Yii::app()->format->formatCurrency($row1['0sd30']/$per),
								Yii::app()->format->formatCurrency($row1['31sd60']/$per),
								Yii::app()->format->formatCurrency($row1['61sd90']/$per),
								Yii::app()->format->formatCurrency($row1['91sd120']/$per),
								Yii::app()->format->formatCurrency($row1['sd120']/$per),
                Yii::app()->format->formatCurrency(($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['sd120'])/$per),
                Yii::app()->format->formatCurrency(($row1['sd0']+$row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['sd120'])/$per)
                        )); 
                        $totalsd0 += $row1['sd0']/$per;
												$total0sd30 += $row1['0sd30']/$per;
                        $total31sd60 += $row1['31sd60']/$per;
                        $total61sd90 += $row1['61sd90']/$per;
                        $total91sd120 += $row1['91sd120']/$per;
												$totalsd120 += $row1['sd120']/$per;
												$totaltempo += ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['sd120'])/$per;
												$total += ($row1['sd0']+$row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['sd120'])/$per;
                } 
							$this->pdf->setFont('Arial','B',8);
                $this->pdf->row(array(
                        '','Total','','',
						Yii::app()->format->formatCurrency($totalsd0),
						Yii::app()->format->formatCurrency($total0sd30),
						Yii::app()->format->formatCurrency($total31sd60),
						Yii::app()->format->formatCurrency($total61sd90),
						Yii::app()->format->formatCurrency($total91sd120),
						Yii::app()->format->formatCurrency($totalsd120),
						Yii::app()->format->formatCurrency($totaltempo),
                        Yii::app()->format->formatCurrency($total)
                ));
						$total2sd0 += $totalsd0;
						$total20sd30 += $total0sd30;
            $total231sd60 += $total31sd60;
            $total261sd90 += $total61sd90;
            $total291sd120 += $total91sd120;
						$total2sd120 += $totalsd120;
						$totaltempo2 += $totaltempo;
						$total2 += $total;
						$this->pdf->sety($this->pdf->gety()+5);
            $this->pdf->checkPageBreak(30);
            } 
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->colalign = array('C','R','R','R','R','R','R','R','R');
			$this->pdf->setwidths(array(52,28,28,28,28,28,28,28,35));
			$this->pdf->setFont('Arial','BI',9);
			$this->pdf->row(array(
			'Grand Total :',
					Yii::app()->format->formatCurrency($total2sd0),
					Yii::app()->format->formatCurrency($total20sd30),
						Yii::app()->format->formatCurrency($total231sd60),
						Yii::app()->format->formatCurrency($total261sd90),
						Yii::app()->format->formatCurrency($total291sd120),
						Yii::app()->format->formatCurrency($total2sd120),
						Yii::app()->format->formatCurrency($totaltempo2),
                        Yii::app()->format->formatCurrency($total2)	
			));
            $this->pdf->Output();
        }
	//7
	public function RekapUmurPiutangDagangPerCustomer($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql = "select *,sum(sd0) as belumjatuhtempo, sum(0sd30) as sum0sd30,sum(31sd60) as sum31sd60, sum(61sd90) as sum61sd90, sum(sd90) as sumsd90
				from (select *,
				case when umurtempo < 0 then totamount else 0 end as sd0,
				case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
				case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
				case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
				case when umurtempo > 90 then totamount else 0 end as sd90
				from(select *,amount - payamount as totamount
				from (select d.fullname,e.paydays,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',
				date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
				date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
				datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,
				ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
				from cutarinv f
				join cutar g on g.cutarid=f.cutarid
				where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
				from invoice a
				inner join giheader b on b.giheaderid = a.giheaderid
				inner join soheader c on c.soheaderid = b.soheaderid
				inner join addressbook d on d.addressbookid = c.addressbookid
				inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
				inner join employee f on f.employeeid = c.employeeid			
				where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 	
				and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z)zz 
				where amount > payamount)zzz
				group by fullname
				order by fullname";

		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rekap Umur Piutang Dagang Per Customer';
			$this->pdf->subtitle='Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('L');
			
			$this->pdf->setFont('Arial','B',8);
            $this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C');
                $this->pdf->setwidths(array(10,45,35,35,35,35,35,40));
                $this->pdf->colheader = array('No','Nama Customer','Belum Jatuh Tempo','0-30 Hari','31-60 Hari','61-90 Hari','> 90 Hari','Total');
                $this->pdf->RowHeader();
                $this->pdf->coldetailalign = array('L','L','R','R','R','R','R','R');		
			$totalsd0 = 0;
			$total0sd30 = 0;
      $total31sd60 = 0;
      $total61sd90 = 0;
			$totalsd90 = 0;
			$total = 0;$i=0;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',8);
				$this->pdf->row(array(
					$i,$row['fullname'],
								Yii::app()->format->formatCurrency($row['belumjatuhtempo']/$per),
								Yii::app()->format->formatCurrency($row['sum0sd30']/$per),
								Yii::app()->format->formatCurrency($row['sum31sd60']/$per),
								Yii::app()->format->formatCurrency($row['sum61sd90']/$per),
								Yii::app()->format->formatCurrency($row['sumsd90']/$per),
                Yii::app()->format->formatCurrency(($row['belumjatuhtempo']+$row['sum0sd30']+$row['sum31sd60']+$row['sum61sd90']+$row['sumsd90'])/$per)
				));
            			$totalsd0 += $row['belumjatuhtempo']/$per;
						$total0sd30 += $row['sum0sd30']/$per;
            			$total31sd60 += $row['sum31sd60']/$per;
						$total61sd90 += $row['sum61sd90']/$per;
						$totalsd90 += $row['sumsd90']/$per;
						$total += ($row['belumjatuhtempo']+$row['sum0sd30']+$row['sum31sd60']+$row['sum61sd90']+$row['sumsd90'])/$per;
			}
						$this->pdf->sety($this->pdf->gety()+5);
						$this->pdf->setFont('Arial','B',9);
                        $this->pdf->row(array(
                                '','Total :',
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
	//8
	public function RincianFakturdanReturJualBelumLunasPerSales($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
      parent::actionDownload();
			$nilaitot2 = 0;$dibayar2 = 0;$sisa2 = 0;
      $sql = "select distinct employeeid,fullname
						from (select f.employeeid,f.fullname,a.amount,
						ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
							from cutarinv o
							join cutar p on p.cutarid=o.cutarid
							where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee f on f.employeeid=c.employeeid
						where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
						where amount > payamount order by fullname";
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas Per Sales';
			$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');

			$this->pdf->sety($this->pdf->gety()+0);
		 
			foreach($dataReader as $row)
			{                
				$this->pdf->SetFont('Arial','B',10);
				$this->pdf->text(10,$this->pdf->gety()+5,'SALESMAN ');$this->pdf->text(30,$this->pdf->gety()+5,': '.$row['fullname']);
				$this->pdf->sety($this->pdf->gety()+5);
				$nilaitot1 = 0;$dibayar1 = 0;$sisa1 = 0;
				$sql1 = "select distinct addressbookid,fullname
						from (select d.addressbookid,d.fullname,a.amount,
						datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
						ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
							from cutarinv o
							join cutar p on p.cutarid=o.cutarid
							where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee f on f.employeeid = c. employeeid
						where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."
						and c.employeeid = '".$row['employeeid']."'
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
						where amount > payamount ";
                if ($_GET['umurpiutang'] !== '') 
                {
                        $sql1 = $sql1 . "and  umur > ".$_GET['umurpiutang']." order by fullname";
                }
                else 
                {
                        $sql1 = $sql1 . "order by fullname";
                }
				$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
				
				foreach($dataReader1 as $row1)
				{                
					$this->pdf->SetFont('Arial','',9);
					$this->pdf->text(10,$this->pdf->gety()+5,'Customer ');$this->pdf->text(30,$this->pdf->gety()+5,': '.$row1['fullname']);
					$this->pdf->sety($this->pdf->gety()+5);
					
					$sql2 = "select *
						from (select if(c.isdisplay=1,concat(a.invoiceno,'_D'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
						date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
						datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
						datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,
						ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
							from cutarinv o
							join cutar p on p.cutarid=o.cutarid
							where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee f on f.employeeid = c.employeeid
						where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."
						and d.addressbookid = '".$row1['addressbookid']."' and c.employeeid = '".$row['employeeid']."'
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
						where amount > payamount ";
                    if ($_GET['umurpiutang'] !== '') 
                    {
                            $sql2 = $sql2 . "and  umur > ".$_GET['umurpiutang']." order by umurtempo desc";
                    }
                    else 
                    {
                            $sql2 = $sql2 . "order by umurtempo desc";
                    }
					$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
					
					$this->pdf->sety($this->pdf->gety()+7);
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
					$this->pdf->setwidths(array(10,22,24,24,13,13,30,25,30));
					$this->pdf->colheader = array('No','Dokumen','Tanggal','j_tempo','Umur','UT','Nilai','Kum_bayar','Sisa');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','C','R','R','R');
					$this->pdf->setFont('Arial','',8);
					$i=0;$nilaitot = 0;$dibayar = 0;$sisa = 0;
					
					foreach($dataReader2 as $row2)
					{
						$i+=1;
						$this->pdf->row(array(
							$i,$row2['invoiceno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])),
							date(Yii::app()->params['dateviewfromdb'], strtotime($row2['jatuhtempo'])),
							$row2['umur'],
							$row2['umurtempo'],
							Yii::app()->format->formatCurrency($row2['amount']/$per),
							Yii::app()->format->formatCurrency($row2['payamount']/$per),
							Yii::app()->format->formatCurrency(($row2['amount']-$row2['payamount'])/$per),
						));
						$nilaitot += $row2['amount']/$per;
						$dibayar += $row2['payamount']/$per;
						$sisa += ($row2['amount']-$row2['payamount'])/$per;
						
						$this->pdf->checkPageBreak(20);
					}
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->coldetailalign = array('R','R','R','R');
					$this->pdf->setwidths(array(106,30,25,30));
					$this->pdf->row(array(
						'TOTAL '.$row1['fullname'],
						Yii::app()->format->formatCurrency($nilaitot),
						Yii::app()->format->formatCurrency($dibayar),
						Yii::app()->format->formatCurrency($sisa),
					));
					$nilaitot1 += $nilaitot;
					$dibayar1 += $dibayar;
					$sisa1 += $sisa;
				}
				$this->pdf->sety($this->pdf->gety()+5);
					$this->pdf->setFont('Arial','BI',9);
					$this->pdf->coldetailalign = array('R','R','R','R');
					$this->pdf->setwidths(array(106,30,25,30));
					$this->pdf->row(array(
					'TOTAL SALESMAN '.$row['fullname'],
					Yii::app()->format->formatCurrency($nilaitot1),
					Yii::app()->format->formatCurrency($dibayar1),
					Yii::app()->format->formatCurrency($sisa1),
				));
				$nilaitot2 += $nilaitot1;
				$dibayar2 += $dibayar1;
				$sisa2 += $sisa1;
			}
			$this->pdf->sety($this->pdf->gety()+5);
				$this->pdf->setFont('Arial','B',9);
				$this->pdf->coldetailalign = array('R','R','R','R');
				$this->pdf->setwidths(array(95,30,35,30));
				$this->pdf->row(array(
				'GRAND TOTAL',
				Yii::app()->format->formatCurrency($nilaitot2),
				Yii::app()->format->formatCurrency($dibayar2),
				Yii::app()->format->formatCurrency($sisa2),
			));            
			
			$this->pdf->Output();
    }
	//9
	public function RekapKontrolPiutangCustomervsPlafon($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$totalbelum=0;$totalsudah=0;$totalnilai=0;$totalplafon=0;
		$sql = "select fullname,sum(belum) as belum,sum(sudah) as sudah,sum(nilai) as nilai,plafon from
								(select z.*,
								case when umur <= paydays then nilai else 0 end as belum,
								case when umur > paydays then nilai else 0 end as sudah
								from
								(select distinct a.invoiceno,a.invoicedate,date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,e.paydays,
								datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
								a.amount-(ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
												from cutarinv f
												join cutar g on g.cutarid=f.cutarid
												where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0)) as nilai,
								d.creditlimit as plafon,d.fullname
								from invoice a
								join giheader b on b.giheaderid=a.giheaderid
								join soheader c on c.soheaderid=b.soheaderid
								join addressbook d on d.addressbookid=c.addressbookid
								join paymentmethod e on e.paymentmethodid=c.paymentmethodid
								join employee f on f.employeeid = c.employeeid
								where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
								-- and d.groupcustomerid=4
								and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								order by invoicedate) z where nilai > 0) zz group by fullname order by fullname";
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rekap Kontrol Piutang Customer VS Plafon';
			$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');

			$this->pdf->sety($this->pdf->gety()+3);
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->colalign = array('C','C','C','C','C','C','C','C');
					$this->pdf->setwidths(array(10,50,27,27,27,27,27));
					$this->pdf->colheader = array('No','Customer','Belum JT','Sudah JT','Jumlah','Plafon','Over/(Under)');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','L','R','R','R','R','R');
					$this->pdf->setFont('Arial','',8);
					$i=0;
					
				foreach($dataReader as $row)
				{
					$i+=1;
					$this->pdf->SetFont('Arial','',8);
					$this->pdf->sety($this->pdf->gety()+0);
					$this->pdf->row(array($i,$row['fullname'],						
						Yii::app()->format->formatCurrency($row['belum']/$per),
						Yii::app()->format->formatCurrency($row['sudah']/$per),
						Yii::app()->format->formatCurrency($row['nilai']/$per),
						Yii::app()->format->formatCurrency($row['plafon']/$per),
						Yii::app()->format->formatCurrency(($row['nilai'] - $row['plafon'])/$per),
					));
					$totalbelum += $row['belum']/$per;
					$totalsudah += $row['sudah']/$per;
					$totalnilai += $row['nilai']/$per;
					$totalplafon += $row['plafon']/$per;
				}
				
			
			$this->pdf->SetFont('Arial','B',8);
				$this->pdf->sety($this->pdf->gety()+4);
				$this->pdf->row(array(
					'','TOTAL',
					Yii::app()->format->formatCurrency($totalbelum),
					Yii::app()->format->formatCurrency($totalsudah),
					Yii::app()->format->formatCurrency($totalnilai),
					Yii::app()->format->formatCurrency($totalplafon),
					Yii::app()->format->formatCurrency($totalnilai - $totalplafon),
					
					));
		
		
		
		
		$this->pdf->Output();
	}
		
	/*public function RekapKontrolPiutangCustomervsPlafon($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$totalbelum=0;$totalsudah=0;$totalnilai=0;$totalplafon=0;
		
			$this->pdf->title='Rekap Kontrol Piutang Customer VS Plafon';
			$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');

			$this->pdf->sety($this->pdf->gety()+3);
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->colalign = array('C','C','C','C','C','C','C','C');
					$this->pdf->setwidths(array(10,50,27,27,27,27,27));
					$this->pdf->colheader = array('No','Customer','Belum JT','Sudah JT','Jumlah','Plafon','Over/(Under)');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','L','R','R','R','R','R');
					$this->pdf->setFont('Arial','',8);
					$i=0;
					
					
		 
			
				$sql1 = 	"select *,sum(amount) as sumamount,sum(creditlimit)as sumcreditlimit,sum(payamount)as sumpayamount,sum(sisa) as 							sumsisa,sum(belum) as sumbelum,sum(sudah)as sumsudah
											from(select zz.*,
											case when umur <= paydays then amount else 0 end as belum,
											case when umur > paydays then amount else 0 end as sudah
											from
											(select *, (amount-payamount) as sisa
											from (select a.invoiceno,a.invoicedate,e.paydays,d.fullname as customer,
											date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
											datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,d.creditlimit,
											ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
											from cutarinv f
											join cutar g on g.cutarid=f.cutarid
											where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
											from invoice a
											inner join giheader b on b.giheaderid = a.giheaderid
											inner join soheader c on c.soheaderid = b.soheaderid
											inner join addressbook d on d.addressbookid = c.addressbookid
											inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
											where a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
											and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z)zz)zzz
											where amount > payamount
											group by customer
											order by invoiceno";




								
				$dataReader=Yii::app()->db->createCommand($sql)->queryAll();			
				
				foreach($dataReader1 as $row1)
				{
					$i+=1;
					$this->pdf->SetFont('Arial','',8);
					$this->pdf->sety($this->pdf->gety()+0);
					$this->pdf->row(array($i,$row1['customer'],						
						Yii::app()->format->formatCurrency($row1['sumbelum']/$per),
						Yii::app()->format->formatCurrency($row1['sumsudah']/$per),
						Yii::app()->format->formatCurrency($row1['sumamount']/$per),
						Yii::app()->format->formatCurrency($row1['sumcreditlimit']/$per),
						Yii::app()->format->formatCurrency($row1['sumamount']/$per - $row1['sumcreditlimit']/$per),
					));
					$totalbelum += $row1['sumbelum']/$per;
					$totalsudah += $row1['sumsudah']/$per;
					$totalnilai += $row1['sumamount']/$per;
					$totalplafon += $row1['sumcreditlimit']/$per;
				}
				
			
			$this->pdf->SetFont('Arial','B',8);
				$this->pdf->sety($this->pdf->gety()+4);
				$this->pdf->row(array(
					'','TOTAL',
					Yii::app()->format->formatCurrency($totalbelum),
					Yii::app()->format->formatCurrency($totalsudah),
					Yii::app()->format->formatCurrency($totalnilai),
					Yii::app()->format->formatCurrency($totalplafon),
					Yii::app()->format->formatCurrency($totalnilai - $totalplafon),
					
					));
		
		
		
		
		$this->pdf->Output();
	}*/
	//10
	public function RincianKontrolPiutangCustomervsPlafon($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
      parent::actionDownload();
			$totalbelum1=0;$totalsudah1=0;$totalnilai1=0;$totalplafon1=0;
      $sql ="select distinct addressbookid,fullname
						from (select d.addressbookid,d.fullname,a.amount,
						ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
						from cutarinv f
						join cutar g on g.cutarid=f.cutarid
						where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						join giheader b on b.giheaderid = a.giheaderid
						join soheader c on c.soheaderid = b.soheaderid
						join addressbook d on d.addressbookid = c.addressbookid
						join employee e on e.employeeid = c.employeeid
						where d.fullname like '%".$customer."%' and e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
						and d.fullname like '%".$customer."%' 
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
						where amount > payamount
						order by fullname";
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rincian Kontrol Piutang Customer VS Plafon';
			$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');

			
		 
			foreach($dataReader as $row)
			{                
				$totalbelum=0;$totalsudah=0;$totalnilai=0;$totalcreditlimit=0;
				$this->pdf->setFont('Arial','B',10);
				$this->pdf->text(10,$this->pdf->gety()+5,'CUSTOMER ');$this->pdf->text(30,$this->pdf->gety()+5,' : '.$row['fullname']);
				
				$this->pdf->sety($this->pdf->gety()+8);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(10,22,17,17,10,22,22,25,25,25));
				$this->pdf->colheader = array('No','Dokumen','Tanggal','J_Tempo','Umur','Belum JT','Sudah JT','Jumlah','Plafon','Over/(Under)');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('C','C','C','C','C','R','R','R','R','R');
				$this->pdf->sety($this->pdf->gety()+1);
				
				$i=0;$nilaitot = 0;$dibayar = 0;$sisa = 0;$i=0;
									$sql1 ="select zz.*,
												case when umur <= paydays then sisa else 0 end as belum,
												case when umur > paydays then sisa else 0 end as sudah
												from
												(select *, (amount-payamount) as sisa,(amount) as nilai
												from (select a.invoiceno,a.invoicedate,e.paydays,
												date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
												datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,d.creditlimit,
												ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
												from cutarinv f
												join cutar g on g.cutarid=f.cutarid
												where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
												from invoice a
												inner join giheader b on b.giheaderid = a.giheaderid
												inner join soheader c on c.soheaderid = b.soheaderid
												inner join addressbook d on d.addressbookid = c.addressbookid
												inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
												inner join employee f on f.employeeid = c.employeeid
												where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."  
												and d.addressbookid = ".$row['addressbookid']."
												and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z)zz
												where amount > payamount 
												order by invoiceno";
				$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();		
				
				foreach($dataReader1 as $row1)
				{
					$i+=1;
					$this->pdf->SetFont('Arial','',8);					
					$this->pdf->row(array(
						$i,$row1['invoiceno'],
						date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
						date(Yii::app()->params['dateviewfromdb'], strtotime($row1['jatuhtempo'])),
						$row1['umur'],
						Yii::app()->format->formatCurrency($row1['belum']/$per),
						Yii::app()->format->formatCurrency($row1['sudah']/$per),
						Yii::app()->format->formatCurrency($row1['sisa']/$per),						
						'',
						'',
					));
					
					$totalbelum += $row1['belum']/$per;
					$totalsudah += $row1['sudah']/$per;
					$totalnilai += $row1['sisa']/$per;
				}
				$this->pdf->SetFont('Arial','B',8);
				$this->pdf->coldetailalign = array('R','R','R','R','R','R');
			$this->pdf->setwidths(array(74,22,22,25,25,25));
				$this->pdf->sety($this->pdf->gety()+0);
				$this->pdf->row(array(
					'SUB TOTAL',
					Yii::app()->format->formatCurrency($totalbelum),
					Yii::app()->format->formatCurrency($totalsudah),
					Yii::app()->format->formatCurrency($totalnilai),
					Yii::app()->format->formatCurrency($row1['creditlimit']/$per),
					Yii::app()->format->formatCurrency($totalnilai - ($row1['creditlimit']/$per)),
				));
				
					$totalbelum1 += $totalbelum;
					$totalsudah1 += $totalsudah;
					$totalnilai1 += $totalnilai;
					$totalplafon1 += ($row1['creditlimit']/$per);
			}
			
			$this->pdf->coldetailalign = array('C','R','R','R','R','R');
			$this->pdf->setwidths(array(58,27,27,27,27,27));
			$this->pdf->SetFont('Arial','BI',8);
			$this->pdf->sety($this->pdf->gety()+5);
			$this->pdf->row(array(
				'TOTAL',
				Yii::app()->format->formatCurrency($totalbelum1),
				Yii::app()->format->formatCurrency($totalsudah1),
				Yii::app()->format->formatCurrency($totalnilai1),
				Yii::app()->format->formatCurrency($totalplafon1),
				Yii::app()->format->formatCurrency($totalnilai1 - $totalplafon1),
			));         
			
			$this->pdf->Output();
    }
	//11
	public function KonfirmasiPiutangDagang($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$totalbelum1=0;$totalsudah1=0;$totalnilai1=0;$totalplafon1=0;
		$sql ="select distinct addressbookid,fullname,alamat,phoneno
					from (select d.addressbookid,d.fullname,a.amount,
					ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,
					(select h.addressname	from address h where h.addressbookid=d.addressbookid Limit 1) as alamat,
					(select i.phoneno	from address i where i.addressbookid=d.addressbookid Limit 1) as phoneno
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid			
					where d.fullname like '%".$customer."%' and e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
					and d.fullname like '%".$customer."%' 
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount
					order by fullname";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Konfirmasi Piutang';
		/*$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));*/
		$this->pdf->AddPage('P');
		
		foreach($dataReader as $row)
		{                
			$i=0;$totalamount=0;$totalpayamount=0;
			$this->pdf->setFont('Arial','',8);
			$this->pdf->text(10,$this->pdf->gety()+0,'Kepada YTH,');$this->pdf->text(135,$this->pdf->gety()+5,'Note : Bukti Konfirmasi ini tidak untuk Penagihan');
			$this->pdf->text(15,$this->pdf->gety()+5,$row['fullname']);
			$this->pdf->text(10,$this->pdf->gety()+10,'Di Tempat');
			$this->pdf->setFont('Arial','BU',10);
			$this->pdf->text(80,$this->pdf->gety()+15,'Hal : KONFIRMASI PIUTANG');
			$this->pdf->setFont('Arial','',8);
			$this->pdf->text(10,$this->pdf->gety()+25,'Dengan Hormat,');
			$this->pdf->text(10,$this->pdf->gety()+32,'Dalam rangka kegiatan rutin dan tertib administrasi, maka dengan ini kami mohon kesediaan Bapak / Ibu Relasi kami untuk dapat memberikan informasi');
			$this->pdf->text(10,$this->pdf->gety()+35,'atas saldo tagihan perusahaan kami yang masih tersisa terhadap :');
			$this->pdf->text(25,$this->pdf->gety()+40,'Toko / Customer');$this->pdf->text(50,$this->pdf->gety()+40,': '.$row['fullname']);
			$this->pdf->text(110,$this->pdf->gety()+40,'No. Telepon');$this->pdf->text(130,$this->pdf->gety()+40,': '.$row['phoneno']);
			$this->pdf->text(25,$this->pdf->gety()+44,'Alamat');$this->pdf->text(50,$this->pdf->gety()+44,': '.$row['alamat']);
			$this->pdf->text(25,$this->pdf->gety()+48,'Per Tanggal');$this->pdf->text(50,$this->pdf->gety()+48,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
			$this->pdf->text(10,$this->pdf->gety()+55,'Dengan perincian sebagai berikut :');
			
			$i=0;$nilaitot = 0;$dibayar = 0;$sisa = 0;$i=0;
			$sql1 ="select *
						from (select if(c.isdisplay=1,concat(a.invoiceno,'_D'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
						date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
						datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,d.creditlimit,
						ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
						from cutarinv f
						join cutar g on g.cutarid=f.cutarid
						where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,f.fullname,c.isdisplay
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee f on f.employeeid = c.employeeid
						where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."  
						and d.addressbookid = ".$row['addressbookid']."
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
						where amount > payamount 
						order by invoiceno";
						
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();

			$this->pdf->sety($this->pdf->gety()+60);
			$this->pdf->setFont('Arial','',8);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(8,24,17,17,25,22,25,9,48));
			$this->pdf->colheader = array('No','Dokumen','Tanggal','J_Tempo','Nilai Invoice','Jml_Cicilan','Sisa Invoice','Umur','Sales');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','L','C','C','R','R','R','R','L');
			$this->pdf->setbordercell(array(
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
      ));
			$this->pdf->sety($this->pdf->gety()+1);
			
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->SetFont('Arial','',8);
				if($row1['isdisplay'] == 1){$jatuhtempo = 'TERJUAL';}else{$jatuhtempo = date(Yii::app()->params['dateviewfromdb'], strtotime($row1['jatuhtempo']));}
				$this->pdf->row(array(
					$i,$row1['invoiceno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
					$jatuhtempo,
					Yii::app()->format->formatCurrency($row1['amount']/$per),
					Yii::app()->format->formatCurrency($row1['payamount']/$per),
					Yii::app()->format->formatCurrency(($row1['amount']-$row1['payamount'])/$per),
					$row1['umur'],
					$row1['fullname'],
				));
				$totalamount += $row1['amount']/$per;
				$totalpayamount += $row1['payamount']/$per;
			}
			$this->pdf->setbordercell(array(
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
      ));
			$this->pdf->row(array(
				'',
				'',
				'',
				'Grand Total',
				Yii::app()->format->formatCurrency($totalamount),
				Yii::app()->format->formatCurrency($totalpayamount),
				Yii::app()->format->formatCurrency($totalamount-$totalpayamount),
				'',
				'',
			));
			$this->pdf->text(35,$this->pdf->gety()+5,'Saldo Piutang Dagang');
			$this->pdf->text(70,$this->pdf->gety()+5,': ');
			$this->pdf->SetFont('Arial','B',9);
			$this->pdf->text(75,$this->pdf->gety()+5,Yii::app()->format->formatCurrency(($totalamount-$totalpayamount)));
			
			$this->pdf->SetFont('Arial','',8);
			$this->pdf->text(10,$this->pdf->gety()+12,'Mengingat pentingnya hal ini, maka kami sangat mengharapkan bantuannya dan jika dari jumlah tersebut diatas ada yang tidak setuju');
			$this->pdf->text(10,$this->pdf->gety()+16,'mohon penjelasannya.');
			$this->pdf->text(20,$this->pdf->gety()+21,'Alasan :');
			$this->pdf->text(32,$this->pdf->gety()+43,'Menyetujui,');
			$this->pdf->text(20,$this->pdf->gety()+66,'( TTd / Cap Toko /Nama Jelas )');
			$this->pdf->text(90,$this->pdf->gety()+43,'Mengetahui,');
			$this->pdf->text(85,$this->pdf->gety()+66,'(         FM / BM         )');
			$this->pdf->text(150,$this->pdf->gety()+43,'Hormat kami,');
			$this->pdf->text(145,$this->pdf->gety()+66,'(                                  )');
			$this->pdf->text(10,$this->pdf->gety()+75,'Catatan : Konfirmasi ini bukan sebagai bukti penagihan');
			
			$this->pdf->checkPageBreak(250);
		}   
		$this->pdf->Output();
   }
	//12
	public function RekapInvoiceARPerDokumenBelumStatusMax($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
			$sql ="select distinct a.invoiceid, a.invoiceno, a.invoicedate, b.gino, a.headernote, c.companyid, a.recordstatus, a.statusname
				from invoice a
				join giheader b on b.giheaderid = a.giheaderid
				join soheader c on c.soheaderid = b.soheaderid
				where a.invoicedate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				and a.recordstatus between 1 and (3-1)
				and a.invoiceno is not null
				and c.companyid = ".$companyid."
				order by a.recordstatus";
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rekap Invoice AR Per Dokumen Belum Status Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');
			
			$this->pdf->setFont('Arial','B',8);
                        $this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->colalign = array('C','C','C','C','C','L','L');
			$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
			$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
			$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',7);
				$this->pdf->row(array(
					$i,$row['invoiceid'],$row['invoiceno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])),
					$row['gino'],$row['headernote'],$row['statusname']
				));
               
				$this->pdf->checkPageBreak(20);
			}
			
			$this->pdf->Output();
	}
	//13
	public function RekapNotaReturPenjualanPerDokumenBelumStatusMax($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
			$sql ="select distinct a.notagirid, a.notagirno, a.docdate, b.gireturno, a.headernote, a.recordstatus, a.companyid, a.statusname
				from notagir a
				join giretur b on b.gireturid = a.gireturid
				where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				and a.recordstatus between 1 and (3-1)
				and b.gireturno is not null
				and a.companyid = ".$companyid."
				order by a.recordstatus";
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rekap Nota Retur Penjualan Per Dokumen Belum Status Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');
			
			$this->pdf->setFont('Arial','B',8);
                        $this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->colalign = array('C','C','C','C','C','L','L');
			$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
			$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
			$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',7);
				$this->pdf->row(array(
					$i,$row['notagirid'],$row['notagirno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
					$row['gireturno'],$row['headernote'],$row['statusname']
				));
               
				$this->pdf->checkPageBreak(20);
			}
			
			$this->pdf->Output();
	}
	//14
	public function RekapPelunasanPiutangPerDokumenBelumStatusMax($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
			$sql ="select distinct a.cutarid,a.cutarno,a.docdate, b.docno,a.headernote,a.recordstatus,a.statusname
				from cutar a
				join ttnt b on b.ttntid = a.ttntid
				where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				and a.recordstatus between 1 and (3-1)
				and b.docno is not null
				and a.companyid = ".$companyid."
				order by a.recordstatus";
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rekap Pelunasan Piutang Per Dokumen Belum Status Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');
			
			$this->pdf->setFont('Arial','B',8);
                        $this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->colalign = array('C','C','C','C','C','L','L');
			$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
			$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
			$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',7);
				$this->pdf->row(array(
					$i,$row['cutarid'],$row['cutarno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
					$row['docno'],$row['headernote'],$row['statusname']
				));
               
				$this->pdf->checkPageBreak(20);
			}
			
			$this->pdf->Output();
	}	
	//15
	public function RincianPelunasanPiutangPerSales($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;$wheresalesarea='';$whereproduct ='';
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						{$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if($salesarea!=='')
        {
            $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
            $sql = $sql. " and j.areaname like '%".$salesarea."%'";
        }
        if($product!=='')
        {
            $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
            left join product l on l.productid=k.productid";
            $sql = $sql. "and l.productname like '%".$product."%'";
        }
        
            
            
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
        $this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rincian Pelunasan Piutang Per Sales';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','F4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font
         
        $wheresalesarea = ''; $whereproduct='';
        
		foreach($dataReader as $row)
		{
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->text(10,$this->pdf->gety()+2,'SALES ');$this->pdf->text(30,$this->pdf->gety()+2,': '.$row['fullname']);
			$sql1 = "select *,
								case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
								case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
								case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
								case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
								case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
								case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
								case when umur > 90 then nilaibayar else 0 end as sd91								
						from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
						a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
						and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
						and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid'].") z
						order by docdate,fullname,invoicedate
						";
            if($salesarea !=='') 
            {
                $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaidnd ";
                $sql1 = $sql1. " and j.areaname like '%".$salesarea."%'";
            }
            if($product!=='')
            {
                $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                left join product l on l.productid=k.productid";
                $sql1 = $sql1. "and l.productname like '%".$product."%'";
            }
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
									
			$i=0;
			$totaldisc=0;
			$totalnilaibayar=0;
			$total0sd30=0;
			$total31sd45=0;
			$total46sd60=0;
			$total61sd63=0;
			$total64sd70=0;
			$total71sd90=0;
			$totalsd91=0;
			$this->pdf->setFont('Arial','B',7.2);
			$this->pdf->sety($this->pdf->gety()+5);    
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,18,14,14,49,7,20,20,22,20,20,20,20,20,20,20));
			$this->pdf->colheader = array('No','No Invoice','Tanggal','Tgl Byr','Customer','Hari','Nil. Faktur','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','C','C','C','L','C','R','R','R','R','R','R','R','R','R','R');
			
			foreach($dataReader1 as $row1)							
			{
				$this->pdf->setFont('Arial','',6.5);
				$i=$i+1;
				$this->pdf->row(array($i,$row1['invoiceno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
				date(Yii::app()->params['dateviewfromdb'], strtotime($row1['docdate'])),
				$row1['fullname'],
				$row1['umur'],
				Yii::app()->format->formatCurrency($row1['amount']/$per),
				Yii::app()->format->formatCurrency($row1['disc']/$per),
				Yii::app()->format->formatCurrency($row1['nilaibayar']/$per),
				Yii::app()->format->formatCurrency($row1['0sd30']/$per),
				Yii::app()->format->formatCurrency($row1['31sd45']/$per),
				Yii::app()->format->formatCurrency($row1['46sd60']/$per),
				Yii::app()->format->formatCurrency($row1['61sd63']/$per),
				Yii::app()->format->formatCurrency($row1['64sd70']/$per),
				Yii::app()->format->formatCurrency($row1['71sd90']/$per),
				Yii::app()->format->formatCurrency($row1['sd91']/$per),
				));
						
				$totaldisc += ($row1['disc']/$per);
				$totalnilaibayar += ($row1['nilaibayar']/$per);
				$total0sd30 += ($row1['0sd30']/$per);
				$total31sd45 += ($row1['31sd45']/$per);
				$total46sd60 += ($row1['46sd60']/$per);
				$total61sd63 += ($row1['61sd63']/$per);
				$total64sd70 += ($row1['64sd70']/$per);
				$total71sd90 += ($row1['71sd90']/$per);
				$totalsd91 += ($row1['sd91']/$per);
			}
						
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->row(array('','','','','TOTAL SALES '.$row['fullname'],'','',
			Yii::app()->format->formatCurrency($totaldisc),			
			Yii::app()->format->formatCurrency($totalnilaibayar),			
			Yii::app()->format->formatCurrency($total0sd30),
			Yii::app()->format->formatCurrency($total31sd45),
			Yii::app()->format->formatCurrency($total46sd60),
			Yii::app()->format->formatCurrency($total61sd63),
			Yii::app()->format->formatCurrency($total64sd70),
			Yii::app()->format->formatCurrency($total71sd90),
			Yii::app()->format->formatCurrency($totalsd91),));
			
			$totaldisc1 += $totaldisc;
			$totalnilaibayar1 += $totalnilaibayar;
			$total0sd301 += $total0sd30;
			$total31sd451 += $total31sd45;
			$total46sd601 += $total46sd60;
			$total61sd631 += $total61sd63;
			$total64sd701 += $total64sd70;
			$total71sd901 += $total71sd90;
			$totalsd911 += $totalsd91;
			
			$this->pdf->sety($this->pdf->gety()+5);
			
			$this->pdf->checkPageBreak(20);
		}
		
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->row(array('','','','','GRAND TOTAL','','',
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911),));
				
		$this->pdf->Output();
	}
	//16
	public function RekapPelunasanPiutangPerSales($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$i=0;$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;$wheresalesarea='';$whereproduct ='';
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
        
		if($salesarea!=='')
        {
            $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
            $sql = $sql. " and j.areaname like '%".$salesarea."%'";
        }
        if($product!=='')
        {
            $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
            left join product l on l.productid=k.productid";
            $sql = $sql. "and l.productname like '%".$product."%'";
        }
        
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rekap Pelunasan Piutang Per Sales';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font
    
		$this->pdf->setFont('Arial','B',8.5);
		$this->pdf->sety($this->pdf->gety()+5);    
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(8,50,23,25,25,25,25,25,25,25,25));
		$this->pdf->colheader = array('No','Nama Sales','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('R','L','R','R','R','R','R','R','R','R','R');
        
        $wheresalesarea='';$whereproduct ='';
		foreach($dataReader as $row)
		{
			$sql1 = "select *,
								case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
								case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
								case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
								case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
								case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
								case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
								case when umur > 90 then nilaibayar else 0 end as sd91								
						from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
						a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
						and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
                        and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid'].") z
						order by docdate,fullname
						";
            if($salesarea!=='')
            {
                $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                $sql1 = $sql1. " and j.areaname like '%".$salesarea."%'";
            }
            if($product!=='')
            {
                $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                left join product l on l.productid=k.productid";
                $sql1 = $sql1. "and l.productname like '%".$product."%'";
            }
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
									
			$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
			
			foreach($dataReader1 as $row1)							
			{
				$totaldisc += ($row1['disc']/$per);
				$totalnilaibayar += ($row1['nilaibayar']/$per);
				$total0sd30 += ($row1['0sd30']/$per);
				$total31sd45 += ($row1['31sd45']/$per);
				$total46sd60 += ($row1['46sd60']/$per);
				$total61sd63 += ($row1['61sd63']/$per);
				$total64sd70 += ($row1['64sd70']/$per);
				$total71sd90 += ($row1['71sd90']/$per);
				$totalsd91 += ($row1['sd91']/$per);
			}
			$this->pdf->setFont('Arial','',7);
			$i=$i+1;
			$this->pdf->row(array($i,$row['fullname'],
			Yii::app()->format->formatCurrency($totaldisc),			
			Yii::app()->format->formatCurrency($totalnilaibayar),			
			Yii::app()->format->formatCurrency($total0sd30),
			Yii::app()->format->formatCurrency($total31sd45),
			Yii::app()->format->formatCurrency($total46sd60),
			Yii::app()->format->formatCurrency($total61sd63),
			Yii::app()->format->formatCurrency($total64sd70),
			Yii::app()->format->formatCurrency($total71sd90),
			Yii::app()->format->formatCurrency($totalsd91),));
			
			$totaldisc1 += $totaldisc;
			$totalnilaibayar1 += $totalnilaibayar;
			$total0sd301 += $total0sd30;
			$total31sd451 += $total31sd45;
			$total46sd601 += $total46sd60;
			$total61sd631 += $total61sd63;
			$total64sd701 += $total64sd70;
			$total71sd901 += $total71sd90;
			$totalsd911 += $totalsd91;
			
			$this->pdf->checkPageBreak(20);
		}
		
		$this->pdf->setFont('Arial','BI',8);
		$this->pdf->row(array('','GRAND TOTAL',
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911)));
				
		$this->pdf->Output();
	}
	//17
	public function RincianPelunasanPiutangPerSalesPerJenisBarang($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0; $joinsalesarea = ''; $joinproduct=''; $wheresalesarea = ''; $whereproduct='';
		if($salesarea!=='')
		{
				$joinsalesarea = " join salesarea j on j.salesareaid=g.salesareaid ";
				$wheresalesarea = " and j.areaname like '%".$salesarea."%' ";
		}
		if($product!=='')
		{
				$joinproduct = " left join gidetail k on k.giheaderid=d.giheaderid
				left join product l on l.productid=k.productid ";
				$whereproduct = " and l.productname like '%".$product."%' ";
		}
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$joinsalesarea} {$joinproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." {$wheresalesarea} {$whereproduct}
						and b.recordstatus=3 and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if($salesarea!=='')
        {
            $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
            $sql = $sql. " and j.areaname like '%".$salesarea."%'";
        }
        if($product!=='')
        {
            $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
            left join product l on l.productid=k.productid";
            $sql = $sql. "and l.productname like '%".$product."%'";
        }
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rincian Pelunasan Piutang Per Sales Per Jenis Barang';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','F4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font
                
        $wheresalesarea='';$whereproduct ='';
		foreach($dataReader as $row)
		{
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->text(10,$this->pdf->gety()+2,'SALES ');$this->pdf->text(25,$this->pdf->gety()+2,': '.$row['fullname']);
			$this->pdf->sety($this->pdf->gety()+5);
			$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
			$sql1 = "select distinct materialgroupid,description								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid,
							(select j.description from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid join materialgroup j on j.materialgroupid=i.materialgroupid where h.giheaderid=d.giheaderid Limit 1) as description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
                            {$joinsalesarea} {$joinproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							{$wheresalesarea} {$whereproduct}
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							order by description
							";
			if($salesarea!=='')
            {
                $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                $sql1 = $sql1. " and j.areaname like '%".$salesarea."%'";
            }
            if($product!=='')
            {
                $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                left join product l on l.productid=k.productid";
                $sql1 = $sql1. "and l.productname like '%".$product."%'";
            }
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
            $wheresalesarea='';$whereproduct ='';
			foreach($dataReader1 as $row1)
			{
				$i=0;$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->text(10,$this->pdf->gety()+2,'MATERIAL GROUP ');$this->pdf->text(35,$this->pdf->gety()+2,': '.$row1['description']);
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							{$wheresalesarea} {$whereproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							where z.materialgroupid = ".$row1['materialgroupid']."
							order by docdate,fullname,invoicedate
							";
                if($salesarea!=='')
                {
                    $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                    $sql2 = $sql2. " and j.areaname like '%".$salesarea."%'";
                }
                if($product!=='')
                {
                    $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                    left join product l on l.productid=k.productid";
                    $sql2 = $sql2. "and l.productname like '%".$product."%'";
                }
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
										
				$this->pdf->setFont('Arial','B',7.2);
				$this->pdf->sety($this->pdf->gety()+5);    
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(6,18,14,14,49,7,20,20,22,20,20,20,20,20,20,20));
				$this->pdf->colheader = array('No','No Invoice','Tanggal','Tgl Byr','Customer','Hari','Nil. Faktur','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('R','C','C','C','L','C','R','R','R','R','R','R','R','R','R','R');
				
				foreach($dataReader2 as $row2)							
				{
					$this->pdf->setFont('Arial','',6.5);
					$i=$i+1;
					$this->pdf->row(array($i,$row2['invoiceno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])),
					date(Yii::app()->params['dateviewfromdb'], strtotime($row2['docdate'])),
					$row2['fullname'],
					$row2['umur'],
					Yii::app()->format->formatCurrency($row2['amount']/$per),
					Yii::app()->format->formatCurrency($row2['disc']/$per),
					Yii::app()->format->formatCurrency($row2['nilaibayar']/$per),
					Yii::app()->format->formatCurrency($row2['0sd30']/$per),
					Yii::app()->format->formatCurrency($row2['31sd45']/$per),
					Yii::app()->format->formatCurrency($row2['46sd60']/$per),
					Yii::app()->format->formatCurrency($row2['61sd63']/$per),
					Yii::app()->format->formatCurrency($row2['64sd70']/$per),
					Yii::app()->format->formatCurrency($row2['71sd90']/$per),
					Yii::app()->format->formatCurrency($row2['sd91']/$per),
					));
							
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);
					
				}
				$this->pdf->setFont('Arial','B',7.2);
				$this->pdf->sety($this->pdf->gety()+0);
				$this->pdf->setwidths(array(128,20,22,20,20,20,20,20,20,20));
				$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R');
				$this->pdf->setFont('Arial','B',6.5);
				$this->pdf->row(array('JUMLAH MATERIAL GROUP '.$row1['description'],
				Yii::app()->format->formatCurrency($totaldisc),			
				Yii::app()->format->formatCurrency($totalnilaibayar),			
				Yii::app()->format->formatCurrency($total0sd30),
				Yii::app()->format->formatCurrency($total31sd45),
				Yii::app()->format->formatCurrency($total46sd60),
				Yii::app()->format->formatCurrency($total61sd63),
				Yii::app()->format->formatCurrency($total64sd70),
				Yii::app()->format->formatCurrency($total71sd90),
				Yii::app()->format->formatCurrency($totalsd91),));
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->row(array('TOTAL SALES '.$row['fullname'],
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911),));
			
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
			
			$this->pdf->checkPageBreak(150);
		}
		
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->row(array('GRAND TOTAL',
			Yii::app()->format->formatCurrency($totaldisc2),			
			Yii::app()->format->formatCurrency($totalnilaibayar2),			
			Yii::app()->format->formatCurrency($total0sd302),
			Yii::app()->format->formatCurrency($total31sd452),
			Yii::app()->format->formatCurrency($total46sd602),
			Yii::app()->format->formatCurrency($total61sd632),
			Yii::app()->format->formatCurrency($total64sd702),
			Yii::app()->format->formatCurrency($total71sd902),
			Yii::app()->format->formatCurrency($totalsd912),));
				
		$this->pdf->Output();
	}
	//18
	public function RincianPelunasanPiutangPerSalesPerJenisBarangWithoutOB($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0; $joinsalesarea = ''; $joinproduct=''; $wheresalesarea = ''; $whereproduct='';
		if($salesarea!=='')
		{
				$joinsalesarea = " join salesarea j on j.salesareaid=g.salesareaid ";
				$wheresalesarea = " and j.areaname like '%".$salesarea."%' ";
		}
		if($product!=='')
		{
				$joinproduct = " left join gidetail k on k.giheaderid=d.giheaderid
				left join product l on l.productid=k.productid ";
				$whereproduct = " and l.productname like '%".$product."%' ";
		}
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$joinsalesarea} {$joinproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." {$wheresalesarea} {$whereproduct}
						and b.recordstatus=3 and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rincian Pelunasan Piutang Per Sales Per Jenis Barang (Tanpa OB)';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','F4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font    
		foreach($dataReader as $row)
		{
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->text(10,$this->pdf->gety()+2,'SALES ');$this->pdf->text(25,$this->pdf->gety()+2,': '.$row['fullname']);
			$this->pdf->sety($this->pdf->gety()+5);
			$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
			$sql1 = "select distinct materialgroupid,description								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
							(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid,
							(select j.description from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid join materialgroup j on j.materialgroupid=i.materialgroupid where h.giheaderid=d.giheaderid Limit 1) as description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
                            {$joinsalesarea} {$joinproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							{$wheresalesarea} {$whereproduct}
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							order by description
							";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			foreach($dataReader1 as $row1)
			{
				$i=0;$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->text(10,$this->pdf->gety()+2,'GROUP MATERIAL ');$this->pdf->text(35,$this->pdf->gety()+2,': '.$row1['description']);
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
							(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
                            {$joinsalesarea} {$joinproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							{$wheresalesarea} {$whereproduct}
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							where z.materialgroupid = ".$row1['materialgroupid']."
							order by docdate,fullname,invoicedate
							";
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
										
				$this->pdf->setFont('Arial','B',7.2);
				$this->pdf->sety($this->pdf->gety()+5);    
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(6,18,14,14,49,7,20,20,22,20,20,20,20,20,20,20));
				$this->pdf->colheader = array('No','No Invoice','Tanggal','Tgl Byr','Customer','Hari','Nil. Faktur','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('R','C','C','C','L','C','R','R','R','R','R','R','R','R','R','R');
				
				foreach($dataReader2 as $row2)							
				{
					$this->pdf->setFont('Arial','',6.5);
					$i=$i+1;
					$this->pdf->row(array($i,$row2['invoiceno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])),
					date(Yii::app()->params['dateviewfromdb'], strtotime($row2['docdate'])),
					$row2['fullname'],
					$row2['umur'],
					Yii::app()->format->formatCurrency($row2['amount']/$per),
					Yii::app()->format->formatCurrency($row2['disc']/$per),
					Yii::app()->format->formatCurrency($row2['nilaibayar']/$per),
					Yii::app()->format->formatCurrency($row2['0sd30']/$per),
					Yii::app()->format->formatCurrency($row2['31sd45']/$per),
					Yii::app()->format->formatCurrency($row2['46sd60']/$per),
					Yii::app()->format->formatCurrency($row2['61sd63']/$per),
					Yii::app()->format->formatCurrency($row2['64sd70']/$per),
					Yii::app()->format->formatCurrency($row2['71sd90']/$per),
					Yii::app()->format->formatCurrency($row2['sd91']/$per),
					));
							
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);
					
				}
				$this->pdf->setFont('Arial','B',7.2);
				$this->pdf->sety($this->pdf->gety()+0);
				$this->pdf->setwidths(array(128,20,22,20,20,20,20,20,20,20));
				$this->pdf->coldetailalign = array('R','R','R','R','R','R','R','R','R','R');
				$this->pdf->setFont('Arial','B',6.5);
				$this->pdf->row(array('JUMLAH GROUP MATERIAL '.$row1['description'],
				Yii::app()->format->formatCurrency($totaldisc),			
				Yii::app()->format->formatCurrency($totalnilaibayar),			
				Yii::app()->format->formatCurrency($total0sd30),
				Yii::app()->format->formatCurrency($total31sd45),
				Yii::app()->format->formatCurrency($total46sd60),
				Yii::app()->format->formatCurrency($total61sd63),
				Yii::app()->format->formatCurrency($total64sd70),
				Yii::app()->format->formatCurrency($total71sd90),
				Yii::app()->format->formatCurrency($totalsd91),));
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial','B',7);
			$this->pdf->row(array('TOTAL SALES '.$row['fullname'],
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911),));
			
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
			
			$this->pdf->checkPageBreak(150);
		}
		
		$this->pdf->setFont('Arial','BI',7);
		$this->pdf->row(array('GRAND TOTAL',
			Yii::app()->format->formatCurrency($totaldisc2),			
			Yii::app()->format->formatCurrency($totalnilaibayar2),			
			Yii::app()->format->formatCurrency($total0sd302),
			Yii::app()->format->formatCurrency($total31sd452),
			Yii::app()->format->formatCurrency($total46sd602),
			Yii::app()->format->formatCurrency($total61sd632),
			Yii::app()->format->formatCurrency($total64sd702),
			Yii::app()->format->formatCurrency($total71sd902),
			Yii::app()->format->formatCurrency($totalsd912),));
				
		$this->pdf->Output();
	}
	//19
	public function RekapPelunasanPiutangPerSalesPerJenisBarang($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0; $wheresalesarea=''; $whereproduct='';
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						{$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if($salesarea!=='')
        {
            $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
            $sql = $sql. " and j.areaname like '%".$salesarea."%'";
        }
        if($product!=='')
        {
            $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
            left join product l on l.productid=k.productid";
            $sql = $sql. "and l.productname like '%".$product."%'";
        }
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rekap Pelunasan Piutang Per Sales Per Jenis Barang';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font
        $wheresalesarea=''; $whereproduct='';        
		foreach($dataReader as $row)
		{
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->text(10,$this->pdf->gety()+2,'SALES ');$this->pdf->text(25,$this->pdf->gety()+2,': '.$row['fullname']);
			$i=0;$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0; 
			$sql1 = "select distinct i.slocid,i.description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join gidetail h on h.giheaderid=d.giheaderid
							join sloc i on i.slocid=h.slocid
							{$wheresalesarea} {$whereproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
							b.recordstatus=3 and f.employeeid = ".$row['employeeid']." and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
			if($salesarea!=='')
            {
                $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                $sql1 = $sql1. " and j.areaname like '%".$salesarea."%'";
            }
            if($product!=='')
            {
                $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                left join product l on l.productid=k.productid";
                $sql1 = $sql1. "and l.productname like '%".$product."%'";
            }
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			
			$this->pdf->setFont('Arial','B',8.5);
			$this->pdf->sety($this->pdf->gety()+5);    
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(8,50,23,25,25,25,25,25,25,25,25));
			$this->pdf->colheader = array('No','Nama Sales','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','R','R','R','R','R','R','R','R','R');
            $wheresalesarea=''; $whereproduct='';
			foreach($dataReader1 as $row1)
			{
				$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select h.slocid from gidetail h where h.giheaderid=d.giheaderid Limit 1) as slocid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							{$wheresalesarea} {$whereproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							where z.slocid = ".$row1['slocid']."
							order by docdate,fullname
							";
                if($salesarea!=='')
                {
                    $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                    $sql2 = $sql2. " and j.areaname like '%".$salesarea."%'";
                }
                if($product!=='')
                {
                    $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                    left join product l on l.productid=k.productid";
                    $sql2 = $sql2. "and l.productname like '%".$product."%'";
                }
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
				
				foreach($dataReader2 as $row2)							
				{							
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);					
				}
				$this->pdf->setFont('Arial','',7);
				$i=$i+1;
				$this->pdf->row(array($i,$row1['description'],
				Yii::app()->format->formatCurrency($totaldisc),			
				Yii::app()->format->formatCurrency($totalnilaibayar),			
				Yii::app()->format->formatCurrency($total0sd30),
				Yii::app()->format->formatCurrency($total31sd45),
				Yii::app()->format->formatCurrency($total46sd60),
				Yii::app()->format->formatCurrency($total61sd63),
				Yii::app()->format->formatCurrency($total64sd70),
				Yii::app()->format->formatCurrency($total71sd90),
				Yii::app()->format->formatCurrency($totalsd91),));
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial','B',7);
			$this->pdf->row(array('','TOTAL SALES '.$row['fullname'],
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911),));
			
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
			
			$this->pdf->sety($this->pdf->gety()+5);
		}
		
		$this->pdf->setFont('Arial','BI',8);
		$this->pdf->row(array('','GRAND TOTAL',
			Yii::app()->format->formatCurrency($totaldisc2),			
			Yii::app()->format->formatCurrency($totalnilaibayar2),			
			Yii::app()->format->formatCurrency($total0sd302),
			Yii::app()->format->formatCurrency($total31sd452),
			Yii::app()->format->formatCurrency($total46sd602),
			Yii::app()->format->formatCurrency($total61sd632),
			Yii::app()->format->formatCurrency($total64sd702),
			Yii::app()->format->formatCurrency($total71sd902),
			Yii::app()->format->formatCurrency($totalsd912),));
				
		$this->pdf->Output();
	}
	//20
	public function RekapPenjualanVSPelunasanPerBulanPerCustomer($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql = "select * from
				(select z.fullname,
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=1 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=1 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_jan, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=1 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jan,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=2 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=2 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_feb, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=2 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_feb,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=3 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=3 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_mar, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=3 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_mar,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=4 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=4 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_apr, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=4 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_apr,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=5 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=5 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_mei, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=5 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_mei,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=6 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=6 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_jun, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=6 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jun,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=7 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=7 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_jul, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=7 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jul,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=8 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=8 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_agus, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=8 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_agus,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=9 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=9 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_sept, 
                
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=9 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_sept,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=10 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=10 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_okt, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=10 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_okt,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=11 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=11 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_nov, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=11 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_nov,
                
               
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=12 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=12 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_des, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=12 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_des,
                
                ((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_total, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_total
                
                
				from addressbook z
				where z.recordstatus=1 and z.iscustomer=1 and z.fullname is not null order by fullname asc) zz 
                where penj_jan<>0 or byr_jan<>0 or penj_feb<>0 or byr_feb<>0 or penj_mar<>0 or byr_mar<>0 or penj_apr<>0 or byr_apr<>0 or penj_mei<>0 or byr_mei<>0 or penj_jun<>0 or byr_jun<>0 or penj_jul<>0 or byr_jul<>0 or penj_agus<>0 or byr_agus<>0 or penj_sept<>0 or byr_sept<>0 or penj_okt<>0 or byr_okt<>0 or penj_nov<>0 or byr_nov<>0 or penj_des<>0 or byr_des<>0 "; 
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			$i=0;$totaljual1=0;$totalbyr1=0;$totaljual2=0;$totalbyr2=0;$totaljual3=0;$totalbyr3=0;$totaljual4=0;             $totalbyr4=0;$totaljual5=0;$totalbyr5=0;$totaljual6=0;$totalbyr6=0;$totaljual7=0;$totalbyr7=0;$totaljual8=0;
            $totalbyr8=0;$totaljual9=0;$totalbyr9=0;$totaljual10=0;$totalbyr10=0;$totaljual11=0;$totalbyr11=0;$totaljual12=0;
            $totalbyr12=0;$totaljual=0;$totalbyr=0;
      
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rekap Penjualan VS Pelunasan Per Bulan Per Customer';
			$this->pdf->subtitle='Per Tahun '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate));
			$this->pdf->AddPage('P',array(500,150));
			
			$this->pdf->setFont('Arial','B',8);
            $this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,35,34,34,34,34,34,34,34,34,34,34,34,34,34));
			$this->pdf->colheader = array('No','Customer','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember','Total');
			$this->pdf->RowHeader();
        
            $this->pdf->sety($this ->pdf->gety()+0,5);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,35,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,20,20));
			$this->pdf->colheader = array('','','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar');
      $this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R');
      $this->pdf->setbordercell(array('TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB'));
			
			foreach($dataReader as $row)
			{
				$this->pdf->setFont('Arial','',7);
				$i=$i+1;
				$this->pdf->row(array(
					$i,$row['fullname'],
					Yii::app()->format->formatCurrency($row['penj_jan']/$per),Yii::app()->format->formatCurrency($row['byr_jan']/$per),
					Yii::app()->format->formatCurrency($row['penj_feb']/$per),Yii::app()->format->formatCurrency($row['byr_feb']/$per),
					Yii::app()->format->formatCurrency($row['penj_mar']/$per),Yii::app()->format->formatCurrency($row['byr_mar']/$per),
					Yii::app()->format->formatCurrency($row['penj_apr']/$per),Yii::app()->format->formatCurrency($row['byr_apr']/$per),
					Yii::app()->format->formatCurrency($row['penj_mei']/$per),Yii::app()->format->formatCurrency($row['byr_mei']/$per),
					Yii::app()->format->formatCurrency($row['penj_jun']/$per),Yii::app()->format->formatCurrency($row['byr_jun']/$per),
					Yii::app()->format->formatCurrency($row['penj_jul']/$per),Yii::app()->format->formatCurrency($row['byr_jul']/$per),
					Yii::app()->format->formatCurrency($row['penj_agus']/$per),Yii::app()->format->formatCurrency($row['byr_agus']/$per),
					Yii::app()->format->formatCurrency($row['penj_sept']/$per),Yii::app()->format->formatCurrency($row['byr_sept']/$per),
					Yii::app()->format->formatCurrency($row['penj_okt']/$per),Yii::app()->format->formatCurrency($row['byr_okt']/$per),
					Yii::app()->format->formatCurrency($row['penj_nov']/$per),Yii::app()->format->formatCurrency($row['byr_nov']/$per),
					Yii::app()->format->formatCurrency($row['penj_des']/$per),Yii::app()->format->formatCurrency($row['byr_des']/$per),
                    Yii::app()->format->formatCurrency($row['penj_total']/$per),Yii::app()->format->formatCurrency($row['byr_total']/$per)
                 
				));
              
              
              $totaljual1 += $row['penj_jan']/$per;
              $totalbyr1 += $row['byr_jan']/$per;
              $totaljual2 += $row['penj_feb']/$per;
              $totalbyr2 += $row['byr_feb']/$per;
              $totaljual3 += $row['penj_mar']/$per;
              $totalbyr3 += $row['byr_mar']/$per;
              $totaljual4 += $row['penj_apr']/$per;
              $totalbyr4 += $row['byr_apr']/$per;
              $totaljual5 += $row['penj_mei']/$per;
              $totalbyr5 += $row['byr_mei']/$per;
              $totaljual6 += $row['penj_jun']/$per;
              $totalbyr6 += $row['byr_jun']/$per;
              $totaljual7 += $row['penj_jul']/$per;
              $totalbyr7 += $row['byr_jul']/$per;
              $totaljual8 += $row['penj_agus']/$per;
              $totalbyr8 += $row['byr_agus']/$per;
              $totaljual9 += $row['penj_sept']/$per;
              $totalbyr9 += $row['byr_sept']/$per;
              $totaljual10 += $row['penj_okt']/$per;
              $totalbyr10 += $row['byr_okt']/$per;
              $totaljual11 += $row['penj_nov']/$per;
              $totalbyr11 += $row['byr_nov']/$per;
              $totaljual12 += $row['penj_des']/$per;
              $totalbyr12 += $row['byr_des']/$per;
              $totaljual += $row['penj_total']/$per;
              $totalbyr += $row['byr_total']/$per;
              
              $this->pdf->checkPageBreak(20);
              }
      
      
              
				
				
                 
			$this->pdf->setFont('Arial','BI',10);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,35,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,20,20));
			/*$this->pdf->colheader = array('','','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar');
            $this->pdf->RowHeader();*/
			$this->pdf->coldetailalign = array('L','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R');		
			$this->pdf->row(array(
					'','TOTAL',
                    Yii::app()->format->formatCurrency($totaljual1),
                    Yii::app()->format->formatCurrency($totalbyr1),
                    Yii::app()->format->formatCurrency($totaljual2),
                    Yii::app()->format->formatCurrency($totalbyr2),
                    Yii::app()->format->formatCurrency($totaljual3),
                    Yii::app()->format->formatCurrency($totalbyr3),
                    Yii::app()->format->formatCurrency($totaljual4),
                    Yii::app()->format->formatCurrency($totalbyr4),
                    Yii::app()->format->formatCurrency($totaljual5),
                    Yii::app()->format->formatCurrency($totalbyr5),
                    Yii::app()->format->formatCurrency($totaljual6),
                    Yii::app()->format->formatCurrency($totalbyr6),
                    Yii::app()->format->formatCurrency($totaljual7),
                    Yii::app()->format->formatCurrency($totalbyr7),
                    Yii::app()->format->formatCurrency($totaljual8),
                    Yii::app()->format->formatCurrency($totalbyr8),
                    Yii::app()->format->formatCurrency($totaljual9),
                    Yii::app()->format->formatCurrency($totalbyr9),
                    Yii::app()->format->formatCurrency($totaljual10),
                    Yii::app()->format->formatCurrency($totalbyr10),
                    Yii::app()->format->formatCurrency($totaljual11),
                    Yii::app()->format->formatCurrency($totalbyr11),
                    Yii::app()->format->formatCurrency($totaljual12),
                    Yii::app()->format->formatCurrency($totalbyr12),
                    Yii::app()->format->formatCurrency($totaljual),
                    Yii::app()->format->formatCurrency($totalbyr),
						
			));
       
       
			$this->pdf->Output();
      
      
	}  
	//21
	public function RekapPiutangVSPelunasanPerBulanPerCustomer($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql = "select * from
				(select z.fullname,
				(select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-01-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-01-01'))) zz where zz.addressbookid = z.addressbookid) as piu_jan, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=1 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jan,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-02-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-02-01'))) zz where zz.addressbookid = z.addressbookid) as piu_feb, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=2 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_feb,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-03-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-03-01'))) zz where zz.addressbookid = z.addressbookid) as piu_mar, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=3 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_mar,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-04-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-04-01'))) zz where zz.addressbookid = z.addressbookid) as piu_apr, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=4 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_apr,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-05-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-05-01'))) zz where zz.addressbookid = z.addressbookid) as piu_mei, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=5 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_mei,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-06-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-06-01'))) zz where zz.addressbookid = z.addressbookid) as piu_jun, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=6 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jun,
               
               
               (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-07-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-07-01'))) zz where zz.addressbookid = z.addressbookid) as piu_jul, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=7 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jul,
                
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-08-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-08-01'))) zz where zz.addressbookid = z.addressbookid) as piu_agus, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=8 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_agus,
                
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-09-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-09-01'))) zz where zz.addressbookid = z.addressbookid) as piu_sept, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=9 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_sept,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-10-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-10-01'))) zz where zz.addressbookid = z.addressbookid) as piu_okt, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=10 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_okt,
                
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-11-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-11-01'))) zz where zz.addressbookid = z.addressbookid) as piu_nov, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=11 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_nov,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-12-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-12-01'))) zz where zz.addressbookid = z.addressbookid) as piu_des, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=12 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_des,
                
                
				(select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' 
               ) zz where zz.addressbookid = z.addressbookid) as piu_total, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_total
                
					 
                
				from addressbook z
				where z.recordstatus=1 and z.iscustomer=1 and z.fullname is not null order by fullname asc) zz 
                
                where piu_jan<>0 or byr_jan<>0 or piu_feb<>0 or byr_feb<>0 or piu_mar<>0 or byr_mar<>0 or piu_apr<>0 or byr_apr<>0 or piu_mei<>0 or byr_mei<>0 or piu_jun<>0 or byr_jun<>0 or piu_jul<>0 or byr_jul<>0 or piu_agus<>0 or byr_agus<>0 or piu_sept<>0 or byr_sept<>0 or piu_okt<>0 or byr_okt<>0 or piu_nov<>0 or byr_nov<>0 or piu_des<>0 or byr_des<>0 "; 
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			$i=0;$totalpiu1=0;$totalbyr1=0;$totalpiu2=0;$totalbyr2=0;$totalpiu3=0;$totalbyr3=0;$totalpiu4=0;             $totalbyr4=0;$totalpiu5=0;$totalbyr5=0;$totalpiu6=0;$totalbyr6=0;$totalpiu7=0;$totalbyr7=0;$totalpiu8=0;
            $totalbyr8=0;$totalpiu9=0;$totalbyr9=0;$totalpiu10=0;$totalbyr10=0;$totalpiu11=0;$totalbyr11=0;$totalpiu12=0;
            $totalbyr12=0;$totalpiu=0;$totalbyr=0;
      
				$this->pdf->companyid = $companyid;
			
			$this->pdf->title='Rekap Piutang VS Pelunasan Per Bulan Per Customer';
			$this->pdf->subtitle='Per Tahun '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P',array(500,150));
			
			$this->pdf->setFont('Arial','B',8);
            $this->pdf->sety($this->pdf->gety()+0);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,35,34,34,34,34,34,34,34,34,34,34,34,34,34));
			$this->pdf->colheader = array('No','Customer','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember','Total');
			$this->pdf->RowHeader();
        
            $this->pdf->sety($this ->pdf->gety()+0,5);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,35,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,20,20));
			$this->pdf->colheader = array('','','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar','piutang','bayar');
            $this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R');
      $this->pdf->setbordercell(array('TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB','TB'));
			
			foreach($dataReader as $row)
			{
				$this->pdf->setFont('Arial','',7);
				$i=$i+1;
				$this->pdf->row(array(
					$i,$row['fullname'],
					Yii::app()->format->formatCurrency($row['piu_jan']/$per),Yii::app()->format->formatCurrency($row['byr_jan']/$per),
					Yii::app()->format->formatCurrency($row['piu_feb']/$per),Yii::app()->format->formatCurrency($row['byr_feb']/$per),
					Yii::app()->format->formatCurrency($row['piu_mar']/$per),Yii::app()->format->formatCurrency($row['byr_mar']/$per),
					Yii::app()->format->formatCurrency($row['piu_apr']/$per),Yii::app()->format->formatCurrency($row['byr_apr']/$per),
					Yii::app()->format->formatCurrency($row['piu_mei']/$per),Yii::app()->format->formatCurrency($row['byr_mei']/$per),
					Yii::app()->format->formatCurrency($row['piu_jun']/$per),Yii::app()->format->formatCurrency($row['byr_jun']/$per),
					Yii::app()->format->formatCurrency($row['piu_jul']/$per),Yii::app()->format->formatCurrency($row['byr_jul']/$per),
					Yii::app()->format->formatCurrency($row['piu_agus']/$per),Yii::app()->format->formatCurrency($row['byr_agus']/$per),
					Yii::app()->format->formatCurrency($row['piu_sept']/$per),Yii::app()->format->formatCurrency($row['byr_sept']/$per),
					Yii::app()->format->formatCurrency($row['piu_okt']/$per),Yii::app()->format->formatCurrency($row['byr_okt']/$per),
					Yii::app()->format->formatCurrency($row['piu_nov']/$per),Yii::app()->format->formatCurrency($row['byr_nov']/$per),
					Yii::app()->format->formatCurrency($row['piu_des']/$per),Yii::app()->format->formatCurrency($row['byr_des']/$per),
                    Yii::app()->format->formatCurrency($row['piu_total']/$per),Yii::app()->format->formatCurrency($row['byr_total']/$per)
                   
				));
              
              
              $totalpiu1 += $row['piu_jan']/$per;
              $totalbyr1 += $row['byr_jan']/$per;
              $totalpiu2 += $row['piu_feb']/$per;
              $totalbyr2 += $row['byr_feb']/$per;
              $totalpiu3 += $row['piu_mar']/$per;
              $totalbyr3 += $row['byr_mar']/$per;
              $totalpiu4 += $row['piu_apr']/$per;
              $totalbyr4 += $row['byr_apr']/$per;
              $totalpiu5 += $row['piu_mei']/$per;
              $totalbyr5 += $row['byr_mei']/$per;
              $totalpiu6 += $row['piu_jun']/$per;
              $totalbyr6 += $row['byr_jun']/$per;
              $totalpiu7 += $row['piu_jul']/$per;
              $totalbyr7 += $row['byr_jul']/$per;
              $totalpiu8 += $row['piu_agus']/$per;
              $totalbyr8 += $row['byr_agus']/$per;
              $totalpiu9 += $row['piu_sept']/$per;
              $totalbyr9 += $row['byr_sept']/$per;
              $totalpiu10 += $row['piu_okt']/$per;
              $totalbyr10 += $row['byr_okt']/$per;
              $totalpiu11 += $row['piu_nov']/$per;
              $totalbyr11 += $row['byr_nov']/$per;
              $totalpiu12 += $row['piu_des']/$per;
              $totalbyr12 += $row['byr_des']/$per;
              $totalpiu12 += $row['piu_total']/$per;
              $totalbyr12 += $row['byr_total']/$per;
              
              
              $this->pdf->checkPageBreak(20);
              }
      
      
              
				
				
                 
			$this->pdf->setFont('Arial','BI',10);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,35,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,17,20,20));
			/*$this->pdf->colheader = array('','','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar','jual','bayar');
            $this->pdf->RowHeader();*/
			$this->pdf->coldetailalign = array('L','L','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R','R');		
			$this->pdf->row(array(
					'','TOTAL',
                    Yii::app()->format->formatCurrency($totalpiu1),
                    Yii::app()->format->formatCurrency($totalbyr1),
                    Yii::app()->format->formatCurrency($totalpiu2),
                    Yii::app()->format->formatCurrency($totalbyr2),
                    Yii::app()->format->formatCurrency($totalpiu3),
                    Yii::app()->format->formatCurrency($totalbyr3),
                    Yii::app()->format->formatCurrency($totalpiu4),
                    Yii::app()->format->formatCurrency($totalbyr4),
                    Yii::app()->format->formatCurrency($totalpiu5),
                    Yii::app()->format->formatCurrency($totalbyr5),
                    Yii::app()->format->formatCurrency($totalpiu6),
                    Yii::app()->format->formatCurrency($totalbyr6),
                    Yii::app()->format->formatCurrency($totalpiu7),
                    Yii::app()->format->formatCurrency($totalbyr7),
                    Yii::app()->format->formatCurrency($totalpiu8),
                    Yii::app()->format->formatCurrency($totalbyr8),
                    Yii::app()->format->formatCurrency($totalpiu9),
                    Yii::app()->format->formatCurrency($totalbyr9),
                    Yii::app()->format->formatCurrency($totalpiu10),
                    Yii::app()->format->formatCurrency($totalbyr10),
                    Yii::app()->format->formatCurrency($totalpiu11),
                    Yii::app()->format->formatCurrency($totalbyr11),
                    Yii::app()->format->formatCurrency($totalpiu12),
                    Yii::app()->format->formatCurrency($totalbyr12),
                    Yii::app()->format->formatCurrency($totalpiu),
                    Yii::app()->format->formatCurrency($totalbyr)
                   
						
			));
       
       
			$this->pdf->Output();
      
      
	}
	//22
	public function RincianPelunasanPiutangPerCustomer($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
		$sql = "select distinct g.addressbookid,g.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						join salesarea j on j.salesareaid=g.salesareaid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and j.areaname like '%".$salesarea."%' and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if ($product !== '') 
		{
			$sql = $sql . " and	d.giheaderid in (select distinct a0.giheaderid from gidetail a0 join product a1 on a1.productid=a0.productid where a1.productname like '%".$product."%') ";
		}
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rincian Pelunasan Piutang Per Customer';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','F4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font
                
		foreach($dataReader as $row)
		{
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->text(10,$this->pdf->gety()+2,'CUSTOMER ');$this->pdf->text(30,$this->pdf->gety()+2,': '.$row['fullname']);
			if ($product !== '') 
			{
				$whereproduct = " and d.giheaderid in (select distinct a0.giheaderid from gidetail a0 join product a1 on a1.productid=a0.productid where a1.productname like '%".$product."%') ";
			}
			else
			{
				$whereproduct = "";
			}
			$sql1 = "select *,
								case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
								case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
								case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
								case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
								case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
								case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
								case when umur > 90 then nilaibayar else 0 end as sd91								
						from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
						a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						join salesarea j on j.salesareaid=g.salesareaid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3
						and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
						and j.areaname like '%".$salesarea."%' and e.companyid = ".$companyid." and g.addressbookid = ".$row['addressbookid']." {$whereproduct}) z
						";
			if ($umurpiutang !== '') 
			{
					$sql1 = $sql1 . " where  umur > ".$umurpiutang." ";
			}
			$sql1 = $sql1 . " order by docdate,fullname ";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
									
			$i=0;
			$totaldisc=0;
			$totalnilaibayar=0;
			$total0sd30=0;
			$total31sd45=0;
			$total46sd60=0;
			$total61sd63=0;
			$total64sd70=0;
			$total71sd90=0;
			$totalsd91=0;
			$this->pdf->setFont('Arial','B',7.2);
			$this->pdf->sety($this->pdf->gety()+5);    
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(6,18,14,14,49,7,20,20,22,20,20,20,20,20,20,20));
			$this->pdf->colheader = array('No','No Invoice','Tanggal','Tgl Byr','Customer','Hari','Nil. Faktur','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','C','C','C','L','C','R','R','R','R','R','R','R','R','R','R');
			
			foreach($dataReader1 as $row1)							
			{
				$this->pdf->setFont('Arial','',6.5);
				$i=$i+1;
				$this->pdf->row(array($i,$row1['invoiceno'],
				date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
				date(Yii::app()->params['dateviewfromdb'], strtotime($row1['docdate'])),
				$row1['fullname'],
				$row1['umur'],
				Yii::app()->format->formatCurrency($row1['amount']/$per),
				Yii::app()->format->formatCurrency($row1['disc']/$per),
				Yii::app()->format->formatCurrency($row1['nilaibayar']/$per),
				Yii::app()->format->formatCurrency($row1['0sd30']/$per),
				Yii::app()->format->formatCurrency($row1['31sd45']/$per),
				Yii::app()->format->formatCurrency($row1['46sd60']/$per),
				Yii::app()->format->formatCurrency($row1['61sd63']/$per),
				Yii::app()->format->formatCurrency($row1['64sd70']/$per),
				Yii::app()->format->formatCurrency($row1['71sd90']/$per),
				Yii::app()->format->formatCurrency($row1['sd91']/$per),
				));
						
				$totaldisc += ($row1['disc']/$per);
				$totalnilaibayar += ($row1['nilaibayar']/$per);
				$total0sd30 += ($row1['0sd30']/$per);
				$total31sd45 += ($row1['31sd45']/$per);
				$total46sd60 += ($row1['46sd60']/$per);
				$total61sd63 += ($row1['61sd63']/$per);
				$total64sd70 += ($row1['64sd70']/$per);
				$total71sd90 += ($row1['71sd90']/$per);
				$totalsd91 += ($row1['sd91']/$per);
			}
						
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->row(array('','','','','TOTAL CUSTOMER '.$row['fullname'],'','',
			Yii::app()->format->formatCurrency($totaldisc),			
			Yii::app()->format->formatCurrency($totalnilaibayar),			
			Yii::app()->format->formatCurrency($total0sd30),
			Yii::app()->format->formatCurrency($total31sd45),
			Yii::app()->format->formatCurrency($total46sd60),
			Yii::app()->format->formatCurrency($total61sd63),
			Yii::app()->format->formatCurrency($total64sd70),
			Yii::app()->format->formatCurrency($total71sd90),
			Yii::app()->format->formatCurrency($totalsd91),));
			
			$totaldisc1 += $totaldisc;
			$totalnilaibayar1 += $totalnilaibayar;
			$total0sd301 += $total0sd30;
			$total31sd451 += $total31sd45;
			$total46sd601 += $total46sd60;
			$total61sd631 += $total61sd63;
			$total64sd701 += $total64sd70;
			$total71sd901 += $total71sd90;
			$totalsd911 += $totalsd91;
			
			$this->pdf->sety($this->pdf->gety()+5);
			
			$this->pdf->checkPageBreak(20);
		}
		
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->row(array('','','','','GRAND TOTAL','','',
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911),));
				
		$this->pdf->Output();
	}
	//23
	public function RekapPelunasanPiutangPerCustomer($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$i=0;$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
		$sql = "select distinct g.addressbookid,g.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						left join salesarea j on j.salesareaid=g.salesareaid
						left join gidetail k on k.giheaderid=d.giheaderid
						left join product l on l.productid=k.productid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and ifnull(j.areaname,'') like '%".$salesarea."%' and ifnull(l.productname,'') like '%".$product."%' and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rekap Pelunasan Piutang Per Customer';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font
    
		$this->pdf->setFont('Arial','B',8.5);
		$this->pdf->sety($this->pdf->gety()+5);    
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(8,50,23,25,25,25,25,25,25,25,25));
		$this->pdf->colheader = array('No','Nama Customer','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('R','L','R','R','R','R','R','R','R','R','R');
			
		foreach($dataReader as $row)
		{
			$sql1 = "select *,
								case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
								case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
								case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
								case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
								case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
								case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
								case when umur > 90 then nilaibayar else 0 end as sd91								
						from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
						a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						left join salesarea j on j.salesareaid=g.salesareaid
						left join gidetail k on k.giheaderid=d.giheaderid
						left join product l on l.productid=k.productid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
						and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
						and ifnull(l.productname,'') like '%".$product."%' 
						and ifnull(j.areaname,'') like '%".$salesarea."%' and e.companyid = ".$companyid." and g.addressbookid = ".$row['addressbookid'].") z
						order by docdate,fullname
						";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
									
			$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
			
			foreach($dataReader1 as $row1)							
			{
				$totaldisc += ($row1['disc']/$per);
				$totalnilaibayar += ($row1['nilaibayar']/$per);
				$total0sd30 += ($row1['0sd30']/$per);
				$total31sd45 += ($row1['31sd45']/$per);
				$total46sd60 += ($row1['46sd60']/$per);
				$total61sd63 += ($row1['61sd63']/$per);
				$total64sd70 += ($row1['64sd70']/$per);
				$total71sd90 += ($row1['71sd90']/$per);
				$totalsd91 += ($row1['sd91']/$per);
			}
			$this->pdf->setFont('Arial','',7);
			$i=$i+1;
			$this->pdf->row(array($i,$row['fullname'],
			Yii::app()->format->formatCurrency($totaldisc),			
			Yii::app()->format->formatCurrency($totalnilaibayar),			
			Yii::app()->format->formatCurrency($total0sd30),
			Yii::app()->format->formatCurrency($total31sd45),
			Yii::app()->format->formatCurrency($total46sd60),
			Yii::app()->format->formatCurrency($total61sd63),
			Yii::app()->format->formatCurrency($total64sd70),
			Yii::app()->format->formatCurrency($total71sd90),
			Yii::app()->format->formatCurrency($totalsd91),));
			
			$totaldisc1 += $totaldisc;
			$totalnilaibayar1 += $totalnilaibayar;
			$total0sd301 += $total0sd30;
			$total31sd451 += $total31sd45;
			$total46sd601 += $total46sd60;
			$total61sd631 += $total61sd63;
			$total64sd701 += $total64sd70;
			$total71sd901 += $total71sd90;
			$totalsd911 += $totalsd91;
			
			$this->pdf->checkPageBreak(20);
		}
		
		$this->pdf->setFont('Arial','BI',8);
		$this->pdf->row(array('','GRAND TOTAL',
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911)));
				
		$this->pdf->Output();
	}
	//24
	public function RincianPelunasanPiutangPerCustomerPerJenisBarang($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0;
		$sql = "select distinct g.addressbookid,g.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and j.areaname like '%".$salesarea."%' and l.productname like '%".$product."%' and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rincian Pelunasan Piutang Per Customer Per Jenis Barang';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','F4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font
                
		foreach($dataReader as $row)
		{
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->text(10,$this->pdf->gety()+2,'CUSTOMER ');$this->pdf->text(25,$this->pdf->gety()+2,': '.$row['fullname']);
			$this->pdf->sety($this->pdf->gety()+5);
			$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
			$sql1 = "select distinct i.slocid,i.description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
						join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join gidetail h on h.giheaderid=d.giheaderid
							join sloc i on i.slocid=h.slocid
							join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
							b.recordstatus=3 and g.addressbookid = ".$row['addressbookid']." and j.areaname like '%".$salesarea."%' and
							l.productname like '%".$product."%' and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
									
			foreach($dataReader1 as $row1)
			{
				$i=0;$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->text(10,$this->pdf->gety()+2,'JENIS BARANG ');$this->pdf->text(35,$this->pdf->gety()+2,': '.$row1['description']);
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select h.slocid from gidetail h where h.giheaderid=d.giheaderid Limit 1) as slocid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
						join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and l.productname like '%".$product."%' 
							and j.areaname like '%".$salesarea."%' and e.companyid = ".$companyid." and g.addressbookid = ".$row['addressbookid']." ) z
							where z.slocid = ".$row1['slocid']."
							order by docdate,fullname
							";
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
										
				$this->pdf->setFont('Arial','B',7.2);
				$this->pdf->sety($this->pdf->gety()+5);    
				$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(6,18,14,14,49,7,20,20,22,20,20,20,20,20,20,20));
				$this->pdf->colheader = array('No','No Invoice','Tanggal','Tgl Byr','Customer','Hari','Nil. Faktur','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('R','C','C','C','L','C','R','R','R','R','R','R','R','R','R','R');
				
				foreach($dataReader2 as $row2)							
				{
					$this->pdf->setFont('Arial','',6.5);
					$i=$i+1;
					$this->pdf->row(array($i,$row2['invoiceno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])),
					date(Yii::app()->params['dateviewfromdb'], strtotime($row2['docdate'])),
					$row2['fullname'],
					$row2['umur'],
					Yii::app()->format->formatCurrency($row2['amount']/$per),
					Yii::app()->format->formatCurrency($row2['disc']/$per),
					Yii::app()->format->formatCurrency($row2['nilaibayar']/$per),
					Yii::app()->format->formatCurrency($row2['0sd30']/$per),
					Yii::app()->format->formatCurrency($row2['31sd45']/$per),
					Yii::app()->format->formatCurrency($row2['46sd60']/$per),
					Yii::app()->format->formatCurrency($row2['61sd63']/$per),
					Yii::app()->format->formatCurrency($row2['64sd70']/$per),
					Yii::app()->format->formatCurrency($row2['71sd90']/$per),
					Yii::app()->format->formatCurrency($row2['sd91']/$per),
					));
							
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);
					
				}
				$this->pdf->setFont('Arial','B',6.5);
				$this->pdf->row(array('','','','','TOTAL GUDANG '.$row1['description'],'','',
				Yii::app()->format->formatCurrency($totaldisc),			
				Yii::app()->format->formatCurrency($totalnilaibayar),			
				Yii::app()->format->formatCurrency($total0sd30),
				Yii::app()->format->formatCurrency($total31sd45),
				Yii::app()->format->formatCurrency($total46sd60),
				Yii::app()->format->formatCurrency($total61sd63),
				Yii::app()->format->formatCurrency($total64sd70),
				Yii::app()->format->formatCurrency($total71sd90),
				Yii::app()->format->formatCurrency($totalsd91),));
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial','B',6.5);
			$this->pdf->row(array('','','','','TOTAL CUSTOMER '.$row['fullname'],'','',
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911),));
			
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
			
			$this->pdf->sety($this->pdf->gety()+5);
		}
		
		$this->pdf->setFont('Arial','BI',6.5);
		$this->pdf->row(array('','','','','GRAND TOTAL','','',
			Yii::app()->format->formatCurrency($totaldisc2),			
			Yii::app()->format->formatCurrency($totalnilaibayar2),			
			Yii::app()->format->formatCurrency($total0sd302),
			Yii::app()->format->formatCurrency($total31sd452),
			Yii::app()->format->formatCurrency($total46sd602),
			Yii::app()->format->formatCurrency($total61sd632),
			Yii::app()->format->formatCurrency($total64sd702),
			Yii::app()->format->formatCurrency($total71sd902),
			Yii::app()->format->formatCurrency($totalsd912),));
				
		$this->pdf->Output();
	}
	//25
	public function RekapPelunasanPiutangPerCustomerPerJenisBarang($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	  parent::actionDownload();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0;
		$sql = "select distinct g.addressbookid,g.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and j.areaname like '%".$salesarea."%' and l.productname like '%".$product."%' and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
			$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rekap Pelunasan Piutang Per Customer Per Jenis Barang';
		$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L','A4');
		$this->pdf->AliasNbPages();
		$this->pdf->setFont('Arial');
		$this->pdf->sety($this->pdf->gety()+5);
		// definisi font
                
		foreach($dataReader as $row)
		{
			$this->pdf->setFont('Arial','B',9);
			$this->pdf->text(10,$this->pdf->gety()+2,'CUSTOMER ');$this->pdf->text(25,$this->pdf->gety()+2,': '.$row['fullname']);
			$i=0;$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
			$sql1 = "select distinct i.slocid,i.description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
						join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join gidetail h on h.giheaderid=d.giheaderid
							join sloc i on i.slocid=h.slocid
							join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
							b.recordstatus=3 and j.areaname like '%".$salesarea."%' and g.addressbookid = ".$row['addressbookid']." and
							l.productname like '%".$product."%' and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			
			$this->pdf->setFont('Arial','B',8.5);
			$this->pdf->sety($this->pdf->gety()+5);    
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(8,50,23,25,25,25,25,25,25,25,25));
			$this->pdf->colheader = array('No','Nama Customer','Disc/Ret','Jmlh Bayar','0 - 30 Hari','31 - 45 Hari','46 - 60 Hari','61 - 63 Hari','64 - 70 Hari','71 - 90 Hari','> 90 Hari');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('R','L','R','R','R','R','R','R','R','R','R');
									
			foreach($dataReader1 as $row1)
			{
				$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select h.slocid from gidetail h where h.giheaderid=d.giheaderid Limit 1) as slocid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
						join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and l.productname like '%".$product."%' 
							and j.areaname like '%".$salesarea."%' and e.companyid = ".$companyid." and g.addressbookid = ".$row['addressbookid']." ) z
							where z.slocid = ".$row1['slocid']."
							order by docdate,fullname
							";
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
				
				foreach($dataReader2 as $row2)							
				{							
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);					
				}
				$this->pdf->setFont('Arial','',7);
				$i=$i+1;
				$this->pdf->row(array($i,$row1['description'],
				Yii::app()->format->formatCurrency($totaldisc),			
				Yii::app()->format->formatCurrency($totalnilaibayar),			
				Yii::app()->format->formatCurrency($total0sd30),
				Yii::app()->format->formatCurrency($total31sd45),
				Yii::app()->format->formatCurrency($total46sd60),
				Yii::app()->format->formatCurrency($total61sd63),
				Yii::app()->format->formatCurrency($total64sd70),
				Yii::app()->format->formatCurrency($total71sd90),
				Yii::app()->format->formatCurrency($totalsd91),));
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial','B',7);
			$this->pdf->row(array('','TOTAL CUSTOMER '.$row['fullname'],
			Yii::app()->format->formatCurrency($totaldisc1),			
			Yii::app()->format->formatCurrency($totalnilaibayar1),			
			Yii::app()->format->formatCurrency($total0sd301),
			Yii::app()->format->formatCurrency($total31sd451),
			Yii::app()->format->formatCurrency($total46sd601),
			Yii::app()->format->formatCurrency($total61sd631),
			Yii::app()->format->formatCurrency($total64sd701),
			Yii::app()->format->formatCurrency($total71sd901),
			Yii::app()->format->formatCurrency($totalsd911),));
			
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
			
			$this->pdf->sety($this->pdf->gety()+5);
		}
		
		$this->pdf->setFont('Arial','BI',8);
		$this->pdf->row(array('','GRAND TOTAL',
			Yii::app()->format->formatCurrency($totaldisc2),			
			Yii::app()->format->formatCurrency($totalnilaibayar2),			
			Yii::app()->format->formatCurrency($total0sd302),
			Yii::app()->format->formatCurrency($total31sd452),
			Yii::app()->format->formatCurrency($total46sd602),
			Yii::app()->format->formatCurrency($total61sd632),
			Yii::app()->format->formatCurrency($total64sd702),
			Yii::app()->format->formatCurrency($total71sd902),
			Yii::app()->format->formatCurrency($totalsd912),));
				
		$this->pdf->Output();
	}
	//26
	public function RekapUmurPiutangDagang($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
        parent::actionDownload();
		if ($companyid == 0) {$com = " and co.isgroup = 1 ";} else {$com = " and c.companyid = ".$companyid." ";}
        $sql1 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120
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
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
                    where amount > payamount) zz ";
        $row1=Yii::app()->db->createCommand($sql1)->queryRow();

        $this->pdf->companyid = $companyid;

        $this->pdf->title='Rekap Umur Piutang Dagang';
        $this->pdf->subtitle='Sampai Tanggal: '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
        $this->pdf->AddPage('P',array(220,80));
        $this->pdf->AliasNbPages();

        $this->pdf->setFont('Arial','B',12);
        $this->pdf->colalign = array('C','C','C','C');
        $this->pdf->setwidths(array(15,100,50,25));
        $this->pdf->colheader = array('No','Keterangan','Nilai','%');
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array('C','L','R','R');
        
		$total = ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
		if ($total > 0)
		{
			$persen0 = ($row1['0sd30']/$total)* 100;
			$persen30 = ($row1['31sd60']/$total)* 100;
			$persen60 = ($row1['61sd90']/$total)* 100;
			$persen90 = ($row1['91sd120']/$total)* 100;
			$persen120 = ($row1['up120']/$total)* 100;
			$persen1total = ($total/$total)* 100;
		}
		else
		{
			$persen0 = 0;
			$persen30 = 0;
			$persen60 = 0;
			$persen90 = 0;
			$persen120 = 0;
			$persen1total = 0;
		}
		
        $this->pdf->setFont('Arial','',12);
        $this->pdf->row(array('1','Piutang Dagang 1 - 30',
			Yii::app()->format->formatCurrency($row1['0sd30']/$per),
			Yii::app()->format->formatCurrency($persen0),
        ));
        $this->pdf->row(array('2','Piutang Dagang 31 - 60',
			Yii::app()->format->formatCurrency($row1['31sd60']/$per),
			Yii::app()->format->formatCurrency($persen30),
        ));
        $this->pdf->row(array('3','Piutang Dagang 61 - 90',
			Yii::app()->format->formatCurrency($row1['61sd90']/$per),
			Yii::app()->format->formatCurrency($persen60),
        ));
        $this->pdf->row(array('4','Piutang Dagang 91 - 120',
			Yii::app()->format->formatCurrency($row1['91sd120']/$per),
			Yii::app()->format->formatCurrency($persen90),
        ));
        $this->pdf->row(array('5','Piutang Dagang > 120',
			Yii::app()->format->formatCurrency($row1['up120']/$per),
			Yii::app()->format->formatCurrency($persen120),
        ));
        $this->pdf->setFont('Arial','B',12);
        $this->pdf->row(array('','Total Piutang Dagang',
			Yii::app()->format->formatCurrency($total/$per),
			Yii::app()->format->formatCurrency('100'),
        ));        
        $this->pdf->checkPageBreak(0);
		$this->pdf->Output();
	}
	//27
	public function RekapUmurPiutangDagangPerBulanPerTahun($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
        parent::actionDownload();
		if ($companyid == 0) {$com = " and co.isgroup = 1 ";} else {$com = " and c.companyid = ".$companyid." ";}
        $sql1 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-01-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-01-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-01-01'))) z
                    where amount > payamount) zz ";
        $row1=Yii::app()->db->createCommand($sql1)->queryRow();
        $sql2 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-02-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-02-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-02-01'))) z
                    where amount > payamount) zz ";
        $row2=Yii::app()->db->createCommand($sql2)->queryRow();
        $sql3 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-03-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-03-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-03-01'))) z
                    where amount > payamount) zz ";
        $row3=Yii::app()->db->createCommand($sql3)->queryRow();
        $sql4 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-04-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-04-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-04-01'))) z
                    where amount > payamount) zz ";
        $row4=Yii::app()->db->createCommand($sql4)->queryRow();
        $sql5 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-05-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-05-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-05-01'))) z
                    where amount > payamount) zz ";
        $row5=Yii::app()->db->createCommand($sql5)->queryRow();
        $sql6 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-06-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-06-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-06-01'))) z
                    where amount > payamount) zz ";
        $row6=Yii::app()->db->createCommand($sql6)->queryRow();
        $sql7 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-07-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-07-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-07-01'))) z
                    where amount > payamount) zz ";
        $row7=Yii::app()->db->createCommand($sql7)->queryRow();
        $sql8 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-08-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-08-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-08-01'))) z
                    where amount > payamount) zz ";
        $row8=Yii::app()->db->createCommand($sql8)->queryRow();
        $sql9 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-09-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-09-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-09-01'))) z
                    where amount > payamount) zz ";
        $row9=Yii::app()->db->createCommand($sql9)->queryRow();
        $sql10 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-10-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-10-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-10-01'))) z
                    where amount > payamount) zz ";
        $row10=Yii::app()->db->createCommand($sql10)->queryRow();
        $sql11 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-11-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-11-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-11-01'))) z
                    where amount > payamount) zz ";
        $row11=Yii::app()->db->createCommand($sql11)->queryRow();
        $sql12 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120,bulan
                from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
                            case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
                            case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
                            case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
                            case when umur > 120 then amount-payamount else 0 end as a5,bulan
                    from (select a.amount,datediff(last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-12-01')),a.invoicedate) as umur,
                        ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                            from cutarinv f
                            join cutar g on g.cutarid=f.cutarid
                            where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-12-01'))),0) as payamount,month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') as bulan
                        from invoice a
                        inner join giheader b on b.giheaderid = a.giheaderid
                        inner join soheader c on c.soheaderid = b.soheaderid
                        inner join company co on co.companyid = c.companyid
                        inner join addressbook d on d.addressbookid = c.addressbookid
                        inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                        inner join employee ff on ff.employeeid = c.employeeid
                        left join salesarea gg on gg.salesareaid = d.salesareaid
                        where a.recordstatus=3 and a.invoiceno is not null {$com} and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= last_day(concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-12-01'))) z
                    where amount > payamount) zz ";
        $row12=Yii::app()->db->createCommand($sql12)->queryRow();

        $this->pdf->companyid = $companyid;

        $this->pdf->title='Rekap Umur Piutang Dagang Per Bulan Per Tahun';
        $this->pdf->subtitle='Sampai Tanggal: '.date("t F Y" , strtotime($enddate));
        //$this->pdf->AddPage('L','Legal');
        $this->pdf->AddPage('L',array(80,360));
        $this->pdf->AliasNbPages();

        $this->pdf->setFont('Arial','B',8.5);
        $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
        $this->pdf->setwidths(array(8,32,25,25,25,25,25,25,25,25,25,25,25,25));
        $this->pdf->colheader = array('No','Keterangan','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sept','Okt','Nop','Des');
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array('C','L','R','R','R','R','R','R','R','R','R','R','R','R');
        
        if ($row2['bulan'] < 2)
        {
            $row2['0sd30'] = 0;
            $row2['31sd60'] = 0;
            $row2['61sd90'] = 0;
            $row2['91sd120'] = 0;
            $row2['up120'] = 0;
        }
        if ($row3['bulan'] < 3)
        {
            $row3['0sd30'] = 0;
            $row3['31sd60'] = 0;
            $row3['61sd90'] = 0;
            $row3['91sd120'] = 0;
            $row3['up120'] = 0;
        }
        if ($row4['bulan'] < 4)
        {
            $row4['0sd30'] = 0;
            $row4['31sd60'] = 0;
            $row4['61sd90'] = 0;
            $row4['91sd120'] = 0;
            $row4['up120'] = 0;
        }
        if ($row5['bulan'] < 5)
        {
            $row5['0sd30'] = 0;
            $row5['31sd60'] = 0;
            $row5['61sd90'] = 0;
            $row5['91sd120'] = 0;
            $row5['up120'] = 0;
        }
        if ($row6['bulan'] < 6)
        {
            $row6['0sd30'] = 0;
            $row6['31sd60'] = 0;
            $row6['61sd90'] = 0;
            $row6['91sd120'] = 0;
            $row6['up120'] = 0;
        }
        if ($row7['bulan'] < 7)
        {
            $row7['0sd30'] = 0;
            $row7['31sd60'] = 0;
            $row7['61sd90'] = 0;
            $row7['91sd120'] = 0;
            $row7['up120'] = 0;
        }
        if ($row8['bulan'] < 8)
        {
            $row8['0sd30'] = 0;
            $row8['31sd60'] = 0;
            $row8['61sd90'] = 0;
            $row8['91sd120'] = 0;
            $row8['up120'] = 0;
        }
        if ($row9['bulan'] < 9)
        {
            $row9['0sd30'] = 0;
            $row9['31sd60'] = 0;
            $row9['61sd90'] = 0;
            $row9['91sd120'] = 0;
            $row9['up120'] = 0;
        }
        if ($row10['bulan'] < 10)
        {
            $row10['0sd30'] = 0;
            $row10['31sd60'] = 0;
            $row10['61sd90'] = 0;
            $row10['91sd120'] = 0;
            $row10['up120'] = 0;
        }
        if ($row11['bulan'] < 11)
        {
            $row11['0sd30'] = 0;
            $row11['31sd60'] = 0;
            $row11['61sd90'] = 0;
            $row11['91sd120'] = 0;
            $row11['up120'] = 0;
        }
        if ($row12['bulan'] < 12)
        {
            $row12['0sd30'] = 0;
            $row12['31sd60'] = 0;
            $row12['61sd90'] = 0;
            $row12['91sd120'] = 0;
            $row12['up120'] = 0;
        }
        
        $this->pdf->setFont('Arial','',7);
        $this->pdf->row(array('1','Piutang Dagang 1 - 30',
			Yii::app()->format->formatCurrency($row1['0sd30']/$per),
			Yii::app()->format->formatCurrency($row2['0sd30']/$per),
			Yii::app()->format->formatCurrency($row3['0sd30']/$per),
			Yii::app()->format->formatCurrency($row4['0sd30']/$per),
			Yii::app()->format->formatCurrency($row5['0sd30']/$per),
			Yii::app()->format->formatCurrency($row6['0sd30']/$per),
			Yii::app()->format->formatCurrency($row7['0sd30']/$per),
			Yii::app()->format->formatCurrency($row8['0sd30']/$per),
			Yii::app()->format->formatCurrency($row9['0sd30']/$per),
			Yii::app()->format->formatCurrency($row10['0sd30']/$per),
			Yii::app()->format->formatCurrency($row11['0sd30']/$per),
			Yii::app()->format->formatCurrency($row12['0sd30']/$per),
        ));
        $this->pdf->row(array('2','Piutang Dagang 31 - 60',
			Yii::app()->format->formatCurrency($row1['31sd60']/$per),
			Yii::app()->format->formatCurrency($row2['31sd60']/$per),
			Yii::app()->format->formatCurrency($row3['31sd60']/$per),
			Yii::app()->format->formatCurrency($row4['31sd60']/$per),
			Yii::app()->format->formatCurrency($row5['31sd60']/$per),
			Yii::app()->format->formatCurrency($row6['31sd60']/$per),
			Yii::app()->format->formatCurrency($row7['31sd60']/$per),
			Yii::app()->format->formatCurrency($row8['31sd60']/$per),
			Yii::app()->format->formatCurrency($row9['31sd60']/$per),
			Yii::app()->format->formatCurrency($row10['31sd60']/$per),
			Yii::app()->format->formatCurrency($row11['31sd60']/$per),
			Yii::app()->format->formatCurrency($row12['31sd60']/$per),
        ));
        $this->pdf->row(array('3','Piutang Dagang 61 - 90',
			Yii::app()->format->formatCurrency($row1['61sd90']/$per),
			Yii::app()->format->formatCurrency($row2['61sd90']/$per),
			Yii::app()->format->formatCurrency($row3['61sd90']/$per),
			Yii::app()->format->formatCurrency($row4['61sd90']/$per),
			Yii::app()->format->formatCurrency($row5['61sd90']/$per),
			Yii::app()->format->formatCurrency($row6['61sd90']/$per),
			Yii::app()->format->formatCurrency($row7['61sd90']/$per),
			Yii::app()->format->formatCurrency($row8['61sd90']/$per),
			Yii::app()->format->formatCurrency($row9['61sd90']/$per),
			Yii::app()->format->formatCurrency($row10['61sd90']/$per),
			Yii::app()->format->formatCurrency($row11['61sd90']/$per),
			Yii::app()->format->formatCurrency($row12['61sd90']/$per),
        ));
        $this->pdf->row(array('4','Piutang Dagang 91 - 120',
			Yii::app()->format->formatCurrency($row1['91sd120']/$per),
			Yii::app()->format->formatCurrency($row2['91sd120']/$per),
			Yii::app()->format->formatCurrency($row3['91sd120']/$per),
			Yii::app()->format->formatCurrency($row4['91sd120']/$per),
			Yii::app()->format->formatCurrency($row5['91sd120']/$per),
			Yii::app()->format->formatCurrency($row6['91sd120']/$per),
			Yii::app()->format->formatCurrency($row7['91sd120']/$per),
			Yii::app()->format->formatCurrency($row8['91sd120']/$per),
			Yii::app()->format->formatCurrency($row9['91sd120']/$per),
			Yii::app()->format->formatCurrency($row10['91sd120']/$per),
			Yii::app()->format->formatCurrency($row11['91sd120']/$per),
			Yii::app()->format->formatCurrency($row12['91sd120']/$per),
        ));
        $this->pdf->row(array('5','Piutang Dagang > 120',
			Yii::app()->format->formatCurrency($row1['up120']/$per),
			Yii::app()->format->formatCurrency($row2['up120']/$per),
			Yii::app()->format->formatCurrency($row3['up120']/$per),
			Yii::app()->format->formatCurrency($row4['up120']/$per),
			Yii::app()->format->formatCurrency($row5['up120']/$per),
			Yii::app()->format->formatCurrency($row6['up120']/$per),
			Yii::app()->format->formatCurrency($row7['up120']/$per),
			Yii::app()->format->formatCurrency($row8['up120']/$per),
			Yii::app()->format->formatCurrency($row9['up120']/$per),
			Yii::app()->format->formatCurrency($row10['up120']/$per),
			Yii::app()->format->formatCurrency($row11['up120']/$per),
			Yii::app()->format->formatCurrency($row12['up120']/$per),
        ));
        $this->pdf->setFont('Arial','B',7);
        $this->pdf->row(array('','Total Piutang Dagang',
			Yii::app()->format->formatCurrency(($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120'])/$per),
			Yii::app()->format->formatCurrency(($row2['0sd30']+$row2['31sd60']+$row2['61sd90']+$row2['91sd120']+$row2['up120'])/$per),
			Yii::app()->format->formatCurrency(($row3['0sd30']+$row3['31sd60']+$row3['61sd90']+$row3['91sd120']+$row3['up120'])/$per),
			Yii::app()->format->formatCurrency(($row4['0sd30']+$row4['31sd60']+$row4['61sd90']+$row4['91sd120']+$row4['up120'])/$per),
			Yii::app()->format->formatCurrency(($row5['0sd30']+$row5['31sd60']+$row5['61sd90']+$row5['91sd120']+$row5['up120'])/$per),
			Yii::app()->format->formatCurrency(($row6['0sd30']+$row6['31sd60']+$row6['61sd90']+$row6['91sd120']+$row6['up120'])/$per),
			Yii::app()->format->formatCurrency(($row7['0sd30']+$row7['31sd60']+$row7['61sd90']+$row7['91sd120']+$row7['up120'])/$per),
			Yii::app()->format->formatCurrency(($row8['0sd30']+$row8['31sd60']+$row8['61sd90']+$row8['91sd120']+$row8['up120'])/$per),
			Yii::app()->format->formatCurrency(($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120'])/$per),
			Yii::app()->format->formatCurrency(($row10['0sd30']+$row10['31sd60']+$row10['61sd90']+$row10['91sd120']+$row10['up120'])/$per),
			Yii::app()->format->formatCurrency(($row11['0sd30']+$row11['31sd60']+$row11['61sd90']+$row11['91sd120']+$row11['up120'])/$per),
			Yii::app()->format->formatCurrency(($row12['0sd30']+$row12['31sd60']+$row12['61sd90']+$row12['91sd120']+$row12['up120'])/$per),
        ));
        
        $this->pdf->checkPageBreak(5);
		$this->pdf->Output();
	}
	//28
	public function RincianFakturdanReturJualBelumLunasFilterJTT($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$nilaitot1 = 0;$dibayar1 = 0;$sisa1 = 0;
		$sql = "select distinct addressbookid,fullname
					from (select d.addressbookid,d.fullname,a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
					ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= DATE_ADD(LAST_DAY('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),INTERVAL - 1 MONTH)),0) as payamount
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid
					join paymentmethod g on g.paymentmethodid = c.paymentmethodid
					left join salesarea f on f.salesareaid = d.salesareaid

					where e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
					and d.fullname like '%".$customer."%' and f.areaname like '%".$salesarea."%'
					and date_add(a.invoicedate,interval g.paydays day) <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount
					";
		if ($_GET['umurpiutang'] !== '') 
		{
				$sql = $sql . "and  umur > ".$_GET['umurpiutang']." order by fullname";
		}
		else 
		{
				$sql = $sql . "order by fullname";
		}
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
		$this->pdf->companyid = $companyid;
		
		$this->pdf->title='Rincian Faktur & Retur Jual Belum Lunas (Filter Tanggal Jatuh Tempo)';
		$this->pdf->subtitle = 'Per Tanggal Jatuh Tempo: '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('P');

		$this->pdf->sety($this->pdf->gety()+0);
	 
		foreach($dataReader as $row)
		{                
			$this->pdf->SetFont('Arial','',10);
			$this->pdf->text(10,$this->pdf->gety()+5,$row['fullname']);
			$sql1 = " select *, (amount-payamount) as sisa,(amount) as nilai
								from (select a.invoiceno,a.invoicedate,e.paydays,
								date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
								datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
								datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,ff.fullname as sales,
								ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
								from cutarinv f
								join cutar g on g.cutarid=f.cutarid
								where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= DATE_ADD(LAST_DAY('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),INTERVAL - 1 MONTH)),0) as payamount
								from invoice a
								inner join giheader b on b.giheaderid = a.giheaderid
								inner join soheader c on c.soheaderid = b.soheaderid
								inner join addressbook d on d.addressbookid = c.addressbookid
								inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
								inner join employee ff on ff.employeeid = c.employeeid

								where d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
								and d.addressbookid = '".$row['addressbookid']."'						
								and date_add(a.invoicedate,interval e.paydays day) <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
								where amount > payamount
								";
			if ($_GET['umurpiutang'] !== '') 
			{
					$sql1 = $sql1 . "and  umur > ".$_GET['umurpiutang']." order by umurtempo desc";
			}
			else 
			{
					$sql1 = $sql1 . "order by umurtempo desc";
			}			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			//$piutang =0;
			//$dibayar=0;
			//$saldo=0;
			
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,22,18,18,11,11,25,25,25,25));
			$this->pdf->colheader = array('No','Dokumen','Tanggal','j_tempo','Umur','UT','Nilai','Kum_bayar','Sisa','Sales');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','C','C','C','C','C','R','R','R','L');
			$this->pdf->setFont('Arial','',8);
			$i=0;
			$nilaitot = 0;
			$dibayar = 0;
			$sisa = 0;
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->pdf->row(array(
					$i,$row1['invoiceno'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
					date(Yii::app()->params['dateviewfromdb'], strtotime($row1['jatuhtempo'])),
					$row1['umur'],
					$row1['umurtempo'],
					Yii::app()->format->formatCurrency($row1['nilai']/$per),
					Yii::app()->format->formatCurrency($row1['payamount']/$per),
					Yii::app()->format->formatCurrency(($row1['nilai']-$row1['payamount'])/$per),
					$row1['sales'],
				));
				$nilaitot += $row1['nilai']/$per;
				$dibayar += $row1['payamount']/$per;
				$sisa += ($row1['nilai']-$row1['payamount'])/$per;
						
				$this->pdf->checkPageBreak(20);
			}
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->coldetailalign = array('R','R','R','R','R','R');
			$this->pdf->setwidths(array(79,11,25,25,25,25));
			$this->pdf->row(array(
				'TOTAL '.$row['fullname'],'',
				Yii::app()->format->formatCurrency($nilaitot),
				Yii::app()->format->formatCurrency($dibayar),
				Yii::app()->format->formatCurrency($sisa),
			));
			$nilaitot1 += $nilaitot;
			$dibayar1 += $dibayar;
			$sisa1 += $sisa;
		}
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->setFont('Arial','BI',9);
		$this->pdf->coldetailalign = array('R','R','R','R');
		$this->pdf->setwidths(array(95,30,35,30));
		$this->pdf->row(array(
			'GRAND TOTAL',
			Yii::app()->format->formatCurrency($nilaitot1),
			Yii::app()->format->formatCurrency($dibayar1),
			Yii::app()->format->formatCurrency($sisa1),
		));
			
		$this->pdf->Output();
	}
	//29
	public function RincianPelunasanPiutangFilterTanggalInvoice($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$this->pdf->companyid = $companyid;
				
		$this->pdf->title='Rincian Pelunasan Piutang (Filter Tanggal Invoice)';
		$this->pdf->subtitle = 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L');
		$i=0;
		$sub_nominal=0;$sub_paidamount=0;$sub_ob=0;$sub_disc=0;$sub_return=0;$sub_paidvalue=0;
		$sql = "select c.invoicedate, c.invoiceno, c.amount, f.fullname as customername, b.docdate,b.cutarno,(a.cashamount+a.bankamount) as paidamount, a.obamount, a.discamount, a.returnamount,
                (cashamount+bankamount+discamount+returnamount+obamount) as paidvalue, g.fullname as salesname
                from cutarinv a
                join cutar b on a.cutarid = b.cutarid
                join invoice c on c.invoiceid = a.invoiceid
                join giheader d on d.giheaderid = c.giheaderid
                join soheader e on e.soheaderid = d.soheaderid
                join addressbook f on f.addressbookid = e.addressbookid
						join ttnt h on h.ttntid=b.ttntid
                join employee g on g.employeeid = h.employeeid
                where c.invoicedate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
                and c.recordstatus = 3 and b.recordstatus = 3 and e.companyid = {$companyid} and g.fullname like '%{$sales}%'
                order by invoicedate asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->sety($this->pdf->gety()+7);
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(8,18,21,25,21,20,22,24,25,25,25,27,25));
		$this->pdf->colheader = array('No','Tgl Invoice','No Invoice','Nilai INV','Toko','Tgl Bayar','No Pelunasan','Bank/Cash/Giro','OB','Diskon','Retur','Jumlah Pelunasan','Sales');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','C','C','R','C','C','C','R','R','R','R','R','L');
		$this->pdf->setFont('Arial','',8);
		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->row(array($i,$row['invoicedate'],$row['invoiceno'],Yii::app()->format->formatCurrency($row['amount']/$per),
        $row['customername'],$row['docdate'],$row['cutarno'],
				Yii::app()->format->formatCurrency($row['paidamount']/$per),
				Yii::app()->format->formatCurrency($row['obamount']/$per),
				Yii::app()->format->formatCurrency($row['discamount']/$per),
				Yii::app()->format->formatCurrency($row['returnamount']/$per),
				Yii::app()->format->formatCurrency($row['paidvalue']/$per),
				$row['salesname']
			));
			$sub_nominal += $row['amount']/$per;
			$sub_paidamount += $row['paidamount']/$per;
			$sub_ob += $row['obamount']/$per;
			$sub_disc += $row['discamount']/$per;
			$sub_return += $row['returnamount']/$per;
			$sub_paidvalue += $row['paidvalue']/$per;
		}
    
		$this->pdf->setFont('Arial','B',9);
		$this->pdf->coldetailalign = array('C','R','L','R','C','C','C','R','R','R','R','R','L');
		$this->pdf->row(array(
			'','GRAND','TOTAL',
			Yii::app()->format->formatCurrency($sub_nominal),
			'','','',
			Yii::app()->format->formatCurrency($sub_paidamount),
			Yii::app()->format->formatCurrency($sub_ob),
			Yii::app()->format->formatCurrency($sub_disc),
			Yii::app()->format->formatCurrency($sub_return),
			Yii::app()->format->formatCurrency($sub_paidvalue),
			''
		));
		$this->pdf->Output();
	}
	//30
	public function RincianPelunasanPiutangFilterTanggalPelunasan($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$this->pdf->companyid = $companyid;
				
		$this->pdf->title='Rincian Pelunasan Piutang (Filter Tanggal Pelunasan)';
		$this->pdf->subtitle = 'Dari Tgl : '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L');
		$i=0;
		$sub_nominal=0;$sub_paidamount=0;$sub_ob=0;$sub_disc=0;$sub_return=0;$sub_paidvalue=0;
		
		$sql = "select c.invoicedate, c.invoiceno, c.amount, f.fullname as customername, b.docdate,b.cutarno,(a.cashamount+a.bankamount) as paidamount, a.obamount, a.discamount, a.returnamount,
                (cashamount+bankamount+discamount+returnamount+obamount) as paidvalue, g.fullname as salesname
                from cutarinv a
                join cutar b on a.cutarid = b.cutarid
                join invoice c on c.invoiceid = a.invoiceid
                join giheader d on d.giheaderid = c.giheaderid
                join soheader e on e.soheaderid = d.soheaderid
                join addressbook f on f.addressbookid = e.addressbookid
						join ttnt h on h.ttntid=b.ttntid
                join employee g on g.employeeid = h.employeeid
                where b.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
                and c.recordstatus = 3 and b.recordstatus = 3 and e.companyid = {$companyid} and g.fullname like '%{$sales}%'
                order by b.docdate asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$this->pdf->sety($this->pdf->gety()+7);
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(8,18,21,25,21,20,22,24,25,25,25,27,25));
		$this->pdf->colheader = array('No','Tgl Invoice','No Invoice','Nilai INV','Toko','Tgl Bayar','No Pelunasan','Bank/Cash/Giro','OB','Diskon','Retur','Jumlah Pelunasan','Sales');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('C','C','C','R','C','C','C','R','R','R','R','R','L');
		$this->pdf->setFont('Arial','',8);
		foreach($dataReader as $row)
		{
			$i+=1;
			$this->pdf->row(array($i,$row['invoicedate'],$row['invoiceno'],Yii::app()->format->formatCurrency($row['amount']/$per),
				$row['customername'],$row['docdate'],$row['cutarno'],
				Yii::app()->format->formatCurrency($row['paidamount']/$per),
				Yii::app()->format->formatCurrency($row['obamount']/$per),
				Yii::app()->format->formatCurrency($row['discamount']/$per),
				Yii::app()->format->formatCurrency($row['returnamount']/$per),
				Yii::app()->format->formatCurrency($row['paidvalue']/$per),
				$row['salesname']
			));
            
			$sub_nominal += $row['amount']/$per;
			$sub_paidamount += $row['paidamount']/$per;
			$sub_ob += $row['obamount']/$per;
			$sub_disc += $row['discamount']/$per;
			$sub_return += $row['returnamount']/$per;
			$sub_paidvalue += $row['paidvalue']/$per;
		}
        
		$this->pdf->setFont('Arial','B',9);
		$this->pdf->coldetailalign = array('C','R','l','R','C','C','C','R','R','R','R','R','L');
		$this->pdf->row(array(
			'','GRAND','TOTAL',
			Yii::app()->format->formatCurrency($sub_nominal),
			'','','',
			Yii::app()->format->formatCurrency($sub_paidamount),
			Yii::app()->format->formatCurrency($sub_ob),
			Yii::app()->format->formatCurrency($sub_disc),
			Yii::app()->format->formatCurrency($sub_return),
			Yii::app()->format->formatCurrency($sub_paidvalue),
			''
		));
        
		$this->pdf->Output();
	}
	//31
	public function RekapTargetVSTagihan($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
			parent::actionDownload();
			//$this->no_result();
			
			$connection = Yii::app()->db;
			$this->pdf->title='LAPORAN TARGET PENAGIHAN VS REALISASI PENAGIHAN';
			$datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

			$this->pdf->subtitle='TARGET BULAN : '.$datetime->format('F').' '.$datetime->format('Y');
			
			$month = date('m',strtotime($enddate));
			$year = date('Y',strtotime($enddate));
			
			$prev_month_ts =  strtotime($year.'-'.$month.'-01');
			$month1 = date('Y-m-d', $prev_month_ts);
			
			$this->pdf->AddPage('L',array(210,365));
			
			$this->pdf->SetFont('Arial','B',10);
			$this->pdf->sety($this->pdf->gety()+5);
			
			$this->pdf->text(310,15,'Per : '.$per);
			
			$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppt')")->queryScalar();
			$sqlsales = "select distinct employeeid, fullname from (
											select c.employeeid, c.fullname, b.addressbookid
											from paymenttarget a
											join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
											join employee c on a.employeeid = c.employeeid
											where a.recordstatus = {$maxstat} and month(a.perioddate) = month('".$enddate."') and year(a.perioddate) = year('".$enddate."')
											and c.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")." and a.companyid = {$companyid}
											union
											select g.employeeid, g.fullname, e.addressbookid
											from cutarinv a
											join cutar b on b.cutarid = a.cutarid
											join invoice c on c.invoiceid = a.invoiceid
											join giheader d on d.giheaderid = c.giheaderid
											join soheader e on e.soheaderid = d.soheaderid
											join ttnt f on f.ttntid = b.ttntid
											join employee g on g.employeeid = f.employeeid
											where g.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")."
											and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and e.companyid = {$companyid} group by addressbookid ) z group by addressbookid";
			//$res = Yii::app()->db->createCommand($sqlsales)->queryAll();
			$emp = Yii::app()->db->createCommand($sqlsales)->queryAll();
			
			foreach($emp as $sales)
			{
					
					$totaltargetgt30 = 0 ;   $totaltodayx0 = 0;
					$totaltargetgt15 = 0;   $totaltodaylt0 = 0; 
					$totaltargetgt0 = 0;    $totaltodaylt14 = 0; 
					$totaltargetx0 = 0;     $totaltoday = 0; 
					$totaltargetlt0 = 0;    $totalkumgt30 = 0; 
					$totaltargetlt14 = 0;   $totalkumgt15 = 0; 
					$totaltarget = 0;       $totalkumgt7 = 0; 
					$totaltodaygt30 = 0;    $totalkumgt0 = 0; 
					$totaltodaygt15 = 0;    $totalkumx0 = 0; 
					$totaltodaygt7 = 0;     $totalkumlt0 = 0;
					$totaltodaygt0 = 0;     $totalkumlt14 = 0;
					$totaltargetgt7 = 0;     $totalkum = 0; 
					
					$this->pdf->SetFont('Arial','',10);
					$this->pdf->colalign = array('C','C','C','C','C');
					$this->pdf->setwidths(array(35,90,95,100,32));

					$this->pdf->colheader = array('Ket','TARGET','REALISASI HARI INI','KUMULATIF S/D HARI INI','         %');

					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
					$this->pdf->setwidths(array(35,12,12,12,12,12,12,12,15,12,12,12,12,12,12,12,15,12,12,12,12,12,12,12,15,25));
					$this->pdf->row(array('','X      >     30   ','15 <X<= 30','7  X<= 15','0 <X<= 7','X        =       0      ','-14 <X<= 0','X      <     -14   ','TOTAL',
															 'X      >     30   ','15 <X<= 30','7  X<= 15','0 <X<= 7','X        =       0      ','-14 <X<= 0','X      <     -14   ','TOTAL',
															 'X      >     30   ','15 <X<= 30','7  X<= 15','0 <X<= 7','X        =       0      ','-14 <X<= 0','X      <     -14   ','TOTAL','')); 
					
					$this->pdf->setwidths(array(150));
					$this->pdf->SetFont('Arial','',10);
					$this->pdf->row(array('NAMA SALES : '.$sales['fullname']));
					
					$this->pdf->setwidths(array(35,12,12,12,12,12,12,12,15,12,12,12,12,12,12,12,15,12,12,12,12,12,12,12,15,25));
					$this->pdf->setFont('Arial','',9);
					
					//$sqladdressbook = "select addressbookid, fullname as custname from addressbook a where a.addressbookid = ".$sales['addressbookid'];
					$sqladdressbook = "
													select b.addressbookid, c.fullname as custname
														from paymenttarget a
														join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
														join addressbook c on c.addressbookid = b.addressbookid
														where a.recordstatus = {$maxstat} and perioddate = '{$month1}' and a.employeeid = ".$sales['employeeid']."
														union
													select h.addressbookid, h.fullname as custname 
														from cutarinv a
														join cutar b on b.cutarid = a.cutarid
														join invoice c on c.invoiceid = a.invoiceid
														join giheader d on d.giheaderid = c.giheaderid
														join soheader e on e.soheaderid = d.soheaderid
														join ttnt f on f.ttntid = b.ttntid
														join employee g on g.employeeid = f.employeeid
														join addressbook h on h.addressbookid = e.addressbookid
														where b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and g.employeeid = ".$sales['employeeid']." group by h.addressbookid ";
													
					$addressbook = Yii::app()->db->createCommand($sqladdressbook)->queryAll();
					
					foreach($addressbook as $row1)
					{
							$sql2 = "select 
													sum(lt14) as targetlt14,sum(lt0) as targetlt0,sum(x0) as targetx0,sum(gt0) as targetgt0,sum(gt7) as targetgt7,sum(gt15) as targetgt15,sum(gt30) as targetgt30,sum(totaltarget) as totaltarget,
													sum(todaylt14) as todaylt14,sum(todaylt0) as todaylt0,sum(todayx0) as todayx0,sum(todaygt0) as todaygt0,sum(todaygt7) as todaygt7,sum(todaygt15) as todaygt15,sum(todaygt30) as todaygt30,
													sum(kumlt14) as kumlt14,sum(kumlt0) as kumlt0,sum(kumx0) as kumx0,sum(kumgt0) as kumgt0,sum(kumgt7) as kumgt7,sum(kumgt15) as kumgt15,sum(kumgt30) as kumgt30,
													addressbookid,fullname
											from (
													select 
															b.lt14, lt0, x0, gt0, gt7, gt15, gt30, (lt14+lt0+x0+gt0+gt7+gt15+gt30) as totaltarget,
															0 as todaylt14,0 as todaylt0, 0 todayx0, 0 as todaygt0, 0 as todaygt7, 0 as todaygt15, 0 as todaygt30,
															0 as kumlt14,0 as kumlt0, 0 as kumx0, 0 as kumgt0, 0 as kumgt7, 0 as kumgt15, 0 as kumgt30, c.addressbookid, c.fullname, ''
													from paymenttarget a 
													join paymenttargetdet b on a.paymenttargetid = b.paymenttargetid
													join addressbook c on c.addressbookid = b.addressbookid
													where a.recordstatus = {$maxstat} and a.employeeid = {$sales['employeeid']} and a.companyid={$companyid} and b.addressbookid = {$row1['addressbookid']} and a.perioddate = '{$month1}'
													union
													select 0 as lt14, 0 as lt0, 0 as x0, 0 as gt0, 0 as gt7, 0 as gt15, 0 as gt30,0 as totaltarget,
													case when umurtop < -14 then sum(nilaibayar) else 0 end as todaylt14,
													case when umurtop < 0 and umurtop >= -14 then sum(nilaibayar) else 0 end as todaylt0,
													case when umurtop = 0 then sum(nilaibayar) else 0 end as todayx0,
													case when umurtop > 0 and umurtop <= 7 then sum(nilaibayar) else 0 end as todaygt0,
													case when umurtop > 7 and umurtop <= 15 then sum(nilaibayar) else 0 end as todaygt7,
													case when umurtop > 15 and umurtop <= 30 then sum(nilaibayar) else 0 end as todaygt15,
													case when umurtop > 30 then sum(nilaibayar) else 0 end as todaygt30,
													case when umurtop < -14 then sum(kumbayar) else 0 end as kumlt14,
													case when umurtop < 0 and umurtop >= -14 then sum(kumbayar) else 0 end as kumlt0,
													case when umurtop = 0 then sum(kumbayar) else 0 end as kumx0,
													case when umurtop > 0 and umurtop <= 7 then sum(kumbayar) else 0 end as kumgt0,
													case when umurtop > 7 and umurtop <= 15 then sum(kumbayar) else 0 end as kumgt7,
													case when umurtop > 15 and umurtop <= 30 then sum(kumbayar) else 0 end as kumgt15,
													case when umurtop > 30 then sum(kumbayar) else 0 end as kumgt30, addressbookid, fullname, invoiceno
													from (
															select c.invoiceno, a.invoiceid, g.fullname, datediff(b.docdate,date_add(c.invoicedate, interval h.paydays day)) as umurtop, a.cashamount+a.bankamount+a.obamount as nilaibayar, 0 as kumbayar, g.addressbookid
															from cutarinv a
															join cutar b on a.cutarid = b.cutarid
															join invoice c on c.invoiceid = a.invoiceid
															join giheader d on d.giheaderid = c.giheaderid
															join soheader e on e.soheaderid = d.soheaderid
															join ttnt i on i.ttntid = b.ttntid
															join employee f on f.employeeid = i.employeeid
															join addressbook g on g.addressbookid = e.addressbookid
															join paymentmethod h on h.paymentmethodid = e.paymentmethodid
															where f.employeeid = {$sales['employeeid']} and e.companyid = {$companyid}
															and b.docdate = '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and g.addressbookid = {$row1['addressbookid']}
															union
															select c.invoiceno, a.invoiceid, f.fullname, datediff(b.docdate,date_add(c.invoicedate, interval h.paydays day)) as umurtop, 0 as nilaibayar, a.cashamount+a.bankamount+a.obamount as kumbayar, g.addressbookid
															from cutarinv a
															join cutar b on a.cutarid = b.cutarid
															join invoice c on c.invoiceid = a.invoiceid
															join giheader d on d.giheaderid = c.giheaderid
															join soheader e on e.soheaderid = d.soheaderid
															join ttnt i on i.ttntid = b.ttntid
															join employee f on f.employeeid = i.employeeid
															join addressbook g on g.addressbookid = e.addressbookid
															join paymentmethod h on h.paymentmethodid = e.paymentmethodid
															where f.employeeid = {$sales['employeeid']} and e.companyid = {$companyid}
															and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and g.addressbookid = {$row1['addressbookid']}
													) z group by invoiceid ) x";
							
							$res2 = Yii::app()->db->createCommand($sql2)->queryAll();
							foreach($res2 as $row3)
							{
									$totalrealisasi = 0;
									$totalkumulatif = 0;
									$totalrealisasi = $row3['todaygt30']+$row3['todaygt15']+$row3['todaygt7']+$row3['todaygt0']+$row3['todayx0']+$row3['todaylt0']+$row3['todaylt14'];
									$totalkumulatif = $row3['kumgt30']+$row3['kumgt15']+$row3['kumgt7']+$row3['kumgt0']+$row3['kumx0']+$row3['kumlt0']+$row3['kumlt14'];
									
									if($row3['totaltarget']==0)
									{
											$mod = 0;
									}
									else
									{
											$mod = ($totalkumulatif/$row3['totaltarget'])*100;
									}
									
									
									$totaltargetgt30 += $row3['targetgt30'];   $totaltodayx0 += $row3['todayx0'];
									$totaltargetgt15 += $row3['targetgt15'];   $totaltodaylt0 += $row3['todaylt0']; 
									$totaltargetgt0 += $row3['targetgt0'];    $totaltodaylt14 += $row3['todaylt14'];
									$totaltargetx0 += $row3['targetx0'];     $totaltoday += $totalrealisasi;
									$totaltargetlt0 += $row3['targetlt0'];    $totalkumgt30 += $row3['kumgt30']; 
									$totaltargetlt14 += $row3['targetlt14'];   $totalkumgt15 += $row3['kumgt15'];
									$totaltarget += $row3['totaltarget'];       $totalkumgt7 += $row3['kumgt7'];
									$totaltodaygt30 += $row3['todaygt30'];    $totalkumgt0 += $row3['kumgt0']; 
									$totaltodaygt15 += $row3['todaygt15'];    $totalkumx0 += $row3['kumx0']; 
									$totaltodaygt7 += $row3['todaygt7'];     $totalkumlt0 += $row3['kumlt0'];
									$totaltodaygt0 += $row3['todaygt0'];     $totalkumlt14 += $row3['kumlt14'];
									$totaltargetgt7 += $row3['targetgt7'];     $totalkum += $totalkumulatif;
									
									$this->pdf->setFont('Arial','',8);
									$this->pdf->row(array($row1['custname'],
									Yii::app()->format->formatCurrency($row3['targetgt30']/$per),
									Yii::app()->format->formatCurrency($row3['targetgt15']/$per),
									Yii::app()->format->formatCurrency($row3['targetgt7']/$per),
									Yii::app()->format->formatCurrency($row3['targetgt0']/$per),
									Yii::app()->format->formatCurrency($row3['targetx0']/$per),
									Yii::app()->format->formatCurrency($row3['targetlt0']/$per),
									Yii::app()->format->formatCurrency($row3['targetlt14']/$per),
									Yii::app()->format->formatCurrency($row3['totaltarget']/$per),
									//'','','','','','','','',
									Yii::app()->format->formatCurrency($row3['todaygt30']/$per),
									Yii::app()->format->formatCurrency($row3['todaygt15']/$per),
									Yii::app()->format->formatCurrency($row3['todaygt7']/$per),
									Yii::app()->format->formatCurrency($row3['todaygt0']/$per),
									Yii::app()->format->formatCurrency($row3['todayx0']/$per),
									Yii::app()->format->formatCurrency($row3['todaylt0']/$per),
									Yii::app()->format->formatCurrency($row3['todaylt14']/$per),
									Yii::app()->format->formatCurrency($totalrealisasi/$per),
									//'',
									Yii::app()->format->formatCurrency($row3['kumgt30']/$per),
									Yii::app()->format->formatCurrency($row3['kumgt15']/$per),
									Yii::app()->format->formatCurrency($row3['kumgt7']/$per),
									Yii::app()->format->formatCurrency($row3['kumgt0']/$per),
									Yii::app()->format->formatCurrency($row3['kumx0']/$per),
									Yii::app()->format->formatCurrency($row3['kumlt0']/$per),
									Yii::app()->format->formatCurrency($row3['kumlt14']/$per),
									Yii::app()->format->formatCurrency($totalkumulatif/$per),
									Yii::app()->format->formatNumber($mod),
									//'',
									));
							}
									
					}
							
				 
					$this->pdf->setFont('Arial','B',8);
					//$this->pdf->setwidths(array(90));
					
					$this->pdf->row(array('TOTAL SALES : '.$sales['fullname'],
									Yii::app()->format->formatCurrency($totaltargetgt30/$per),
									Yii::app()->format->formatCurrency($totaltargetgt15/$per),
									Yii::app()->format->formatCurrency($totaltargetgt7/$per),
									Yii::app()->format->formatCurrency($totaltargetgt0/$per),
									Yii::app()->format->formatCurrency($totaltargetx0/$per),
									Yii::app()->format->formatCurrency($totaltargetlt0/$per),
									Yii::app()->format->formatCurrency($totaltargetlt14/$per),
									Yii::app()->format->formatCurrency($totaltarget/$per),
									Yii::app()->format->formatCurrency($totaltodaygt30/$per),
									Yii::app()->format->formatCurrency($totaltodaygt15/$per),
									Yii::app()->format->formatCurrency($totaltodaygt7/$per),
									Yii::app()->format->formatCurrency($totaltodaygt0/$per),
									Yii::app()->format->formatCurrency($totaltodayx0/$per),
									Yii::app()->format->formatCurrency($totaltodaylt0/$per),
									Yii::app()->format->formatCurrency($totaltodaylt14/$per),
									Yii::app()->format->formatCurrency($totaltoday/$per),
									Yii::app()->format->formatCurrency($totalkumgt30/$per),
									Yii::app()->format->formatCurrency($totalkumgt15/$per),
									Yii::app()->format->formatCurrency($totalkumgt7/$per),
									Yii::app()->format->formatCurrency($totalkumgt0/$per),
									Yii::app()->format->formatCurrency($totalkumx0/$per),
									Yii::app()->format->formatCurrency($totalkumlt0/$per),
									Yii::app()->format->formatCurrency($totalkumlt14/$per),
									Yii::app()->format->formatCurrency($totalkum/$per),
									'',
									));
									
					$this->pdf->row(array(''));
			}
			$this->pdf->Output();
	}
	//32
    public function RincianKomisiKasta($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
    {
        parent::actionDownload();
        $c_employeeid = getEmployeeid();
        $issales = getSalesEmployee();
        $issales == 1 ? $emp =  getStructure($c_employeeid,$c_employeeid,$companyid) : $emp = getUserObjectValues('employee');

        $arr_emp = explode(',',$c_employeeid);
        $arr = explode(',',$emp);
        if($sales!='') {
          //$exp = array_intersect($arr_emp,$arr);
          $employeeid = implode(',',$arr);
        }
        else {
          $employeeid = $emp;
        }
        $connection = Yii::app()->db;
        $this->pdf->title='RINCIAN UPAH TAMBAHAN SALES';
	      $this->pdf->companyid=$companyid;
        $datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

        $this->pdf->subtitle='PERIODE : '.$datetime->format('F').' '.$datetime->format('Y');

        $month = date('m',strtotime($enddate));
        $year = date('Y',strtotime($enddate));

        $prev_month_ts =  strtotime($year.'-'.$month.'-01');
        $month1 = date('Y-m-d', $prev_month_ts);

        $this->pdf->AddPage('L','F4');

        $this->pdf->sety($this->pdf->gety()+5);

        $this->pdf->text(300,15,'Per : '.$per);
        //$this->pdf->text(270,15,'X = T.O.P');
        $wheresalesarea = $whereproduct = '';
        $sqldata = "select distinct f.employeeid,f.fullname,e.companyid
        from cutarinv a
        join cutar b on b.cutarid=a.cutarid
        join invoice c on c.invoiceid=a.invoiceid
        join giheader d on d.giheaderid=c.giheaderid
        join soheader e on e.soheaderid=d.soheaderid
        join ttnt h on h.ttntid=b.ttntid
        join employee f on f.employeeid=h.employeeid
        join addressbook g on g.addressbookid=e.addressbookid
		    join employeeorgstruc i on i.employeeid=f.employeeid
		    join orgstructure j on j.orgstructureid=i.orgstructureid and j.companyid=e.companyid
        {$wheresalesarea} {$whereproduct}
        where j.structurename LIKE '%salesman%' and i.recordstatus=1 and g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
        b.recordstatus=3 and f.employeeid in({$employeeid})
		    and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
		    and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
        and e.isdisplay=0
        order by f.fullname asc";
        
      $sqlidscale = "select scaleid
                    from scale
                    where recordstatus = getwfmaxstatbywfname('appss') 
                    -- and companyid = {$companyid} 
                    order by docdate desc limit 1";
      $idscale = Yii::app()->db->createCommand($sqlidscale)->queryScalar();

        $data = Yii::app()->db->createCommand($sqldata)->queryAll();
        foreach($data as $row)
		  {
            $totbayar0sd50 = 0;
            $totscale0sd50 = 0;
            $totbayar50sd90 = 0;
            $totscale50sd90 = 0;
            $totbayar90sd110 = 0;
            $totscale90sd110 = 0;
            $totbayar110sd150 = 0;
            $totscale110sd150 = 0;
            $totbayarsd150 = 0;
            $totscalesd150 = 0;
            $totjumlahbayar = 0;
            $totscalejumlah = 0;
			
			$sqlsaldoup120 = "select sum(a5) as up120
				from (select case when umur > 120 then amount-payamount else 0 end as a5
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
						where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
						and ff.fullname like '%".$sales."%'  and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and c.employeeid = {$row['employeeid']}
						-- and d.custcategoryid = {cust['custcategoryid']}
						) z
					where amount > payamount) zz";
			$saldo120 = Yii::app()->db->createCommand($sqlsaldoup120)->queryScalar();
          
            $sqlcustcategory = "select i.custcategoryid,i.custcategoryname,
            (select ifnull(`value`,0) from scalecat s where s.custcategoryid = g.custcategoryid and s.scaleid = {$idscale}) skala
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            join invoice c on c.invoiceid=a.invoiceid
            join giheader d on d.giheaderid=c.giheaderid
            join soheader e on e.soheaderid=d.soheaderid
            join ttnt h on h.ttntid=b.ttntid
            join employee f on f.employeeid=h.employeeid
            join addressbook g on g.addressbookid=e.addressbookid
            join custcategory i on i.custcategoryid = g.custcategoryid
            {$wheresalesarea} {$whereproduct}
            where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
			and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
			and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
            and e.companyid = {$companyid} and f.employeeid = {$row['employeeid']}
            and e.isdisplay=0
            group by g.custcategoryid
            order by g.custcategoryid asc";
            $this->pdf->SetFont('Arial','B',9);
            $this->pdf->text(10,$this->pdf->getY(),'NAMA SALES : '.$row['fullname']);
            $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();
            foreach($custcategory as $cust)
			{
				$subbayar0sd50 = 0;
				$subscale0sd50 = 0;
				$subbayar50sd90 = 0;
				$subscale50sd90 = 0;
				$subbayar90sd110 = 0;
				$subscale90sd110 = 0;
				$subbayar110sd150 = 0;
				$subscale110sd150 = 0;
				$subbayarsd150 = 0;
				$subscalesd150 = 0;
				$subjumlahbayar = 0;
				$subscalejumlah = 0;
                          
                $this->pdf->setFont('Arial','B',10);
                $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory'). ' = '. $cust['custcategoryname'].' ('.number_format($cust['skala'],0,',','.').'%)');
                $subjumlahut = 0;
                $this->pdf->SetFont('Arial','B',9);

                $sqlperiod = "select period, invoicedate from (select c.invoiceno,c.invoicedate,b.docdate,setdateformat(c.invoicedate) as period, a.cashamount+a.bankamount as nilai
                from cutarinv a
                join cutar b on b.cutarid=a.cutarid
                join invoice c on c.invoiceid=a.invoiceid
                join giheader d on d.giheaderid=c.giheaderid
                join soheader e on e.soheaderid=d.soheaderid
                join ttnt h on h.ttntid=b.ttntid
                join employee f on f.employeeid=h.employeeid
                join addressbook g on g.addressbookid=e.addressbookid
                {$wheresalesarea} {$whereproduct}
                where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
				and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
                and e.companyid = {$companyid} and f.employeeid = {$row['employeeid']}
                and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
                order by invoicedate asc ) z
                where nilai <> 0
                group by month(invoicedate),year(invoicedate)
				order by invoicedate asc";
                $this->pdf->setY($this->pdf->getY()+10);
                $period = Yii::app()->db->createCommand($sqlperiod)->queryAll();
                foreach($period as $row1) 
				{
					$custbayar0sd50 = 0;
					$custscale0sd50 = 0;
					$custbayar50sd90 = 0;
					$custscale50sd90 = 0;
					$custbayar90sd110 = 0;
					$custscale90sd110 = 0;
					$custbayar110sd150 = 0;
					$custscale110sd150 = 0;
					$custbayarsd150 = 0;
					$custscalesd150 = 0;
					$custjumlahbayar = 0;
					$custscalejumlah = 0;
					
                    $sqlawalbulan = "select date_add(date_add(last_day('{$row1['invoicedate']}'),interval 1 day), interval -1 month) as awalbulan";
                    $tglawal = Yii::app()->db->createCommand($sqlawalbulan)->queryScalar();
					          $percentperiod = Yii::app()->db->createCommand("select ifnull(scalevalue,0) from scalevalue where recordstatus=5 and custcategoryid = {$cust['custcategoryid']} and employeeid = {$row['employeeid']} and perioddate = '{$tglawal}'")->queryScalar();
                    /*
                    $percentperiod = Yii::app()->db->createCommand("
                    select case when '2020-12-01' > '{$tglawal}' then
                      (select ifnull(scalevalue,0) from scalevalue s 
                      where s.custcategoryid = {$cust['custcategoryid']} and s.perioddate='{$tglawal}' and employeeid = {$row['employeeid']} and recordstatus=5 and companyid={$companyid})
	                  else 
                      (select ifnull(scalevalue,0) from scalevalue where recordstatus=5 and employeeid = {$row['employeeid']}
                      and perioddate = '{$tglawal}' and companyid={$companyid} order by scalevalueid desc limit 1)
                    end as scale")->queryScalar();
                    */

                    $this->pdf->setFont('Arial','',9);
                    $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
                    $this->pdf->setwidths(array(30,47,47,47,47,47,48));
                    $this->pdf->colheader = array("          Period             (".Yii::app()->format->formatCurrency($percentperiod)."% ) ",'            <= 50%TOP             120%','   50%TOP < U <= 90%TOP    110%','90%TOP < U <= 110%TOP    100%','110%TOP < U <= 150%TOP    90%','              U > 150%TOP                0%','                  Jumlah                               ');
                    $this->pdf->RowHeader();

                    $this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
                    $this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
                    $this->pdf->SetFont('Arial','',9);
                    $this->pdf->row(array($row1['period'],'NIlai Bayar','UT','Nilai Bayar','UT','NIlai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT'));

                    $sqldet = "select *,
								case when umur >= 0 and umur <= (0.5 * paydays) then nilaibayar else 0 end as 0sd50,
								case when umur > (0.5 * paydays) and umur <= (0.9 * paydays) then nilaibayar else 0 end AS 50sd90,
								case when umur > (0.9 * paydays) and umur <= (1.1 * paydays) then nilaibayar else 0 end AS 90sd110,
								case when umur > (1.1 * paydays) and umur <= (1.5 * paydays) then nilaibayar else 0 end AS 110sd150,
								case when umur > (1.5 * paydays) then nilaibayar else 0 end as sd150
								,(0.5 * paydays),(0.9 * paydays),(paydays),(1.5 * paydays)
								from (select distinct g.fullname,c.invoiceno,c.invoicedate,b.docdate AS cutardate,datediff(b.docdate,c.invoicedate) as umur,p.paydays,c.amount,
								a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
								(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
								join invoice c on c.invoiceid=a.invoiceid
								join giheader d on d.giheaderid=c.giheaderid
								join soheader e on e.soheaderid=d.soheaderid
								join ttnt i on i.ttntid=b.ttntid
								join employee f on f.employeeid=i.employeeid
								join addressbook g on g.addressbookid=e.addressbookid
								join paymentmethod p ON p.paymentmethodid=e.paymentmethodid
								{$wheresalesarea} {$whereproduct}
								where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
								and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and e.companyid = {$companyid}
								and f.employeeid = ".$row['employeeid']."
								and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
								) z
								-- where z.materialgroupid = ''
								where month(invoicedate) = month('{$row1['invoicedate']}')
								and year(invoicedate) = year('{$row1['invoicedate']}')
								and nilaibayar <> 0 
								order by cutardate,fullname,invoicedate";
					$detail = Yii::app()->db->createCommand($sqldet)->queryAll();
                    $this->pdf->setFont('Arial','',8);
                    foreach($detail as $row2)
					{
                        //$this->pdf->row(array('','NIlai INV','UT','Nilai INV','UT','NIlai INV','UT','Nilai INV','UT','Nilai INV','UT',''));
                        $jumlahbayar = ($row2['0sd50'] + $row2['50sd90'] + $row2['90sd110'] + $row2['110sd150'] + $row2['sd150'])/$per;
                        $jumlahut = (($row2['0sd50']*$percentperiod*1.2)+($row2['50sd90']*$percentperiod*1.1)+($row2['90sd110']*$percentperiod*1)+
                                    ($row2['110sd150']*$percentperiod*0.9))/100/$per;
                        $this->pdf->row(array(
                          $row2['invoiceno'],
                          Yii::app()->format->formatCurrency($row2['0sd50']/$per),
                          Yii::app()->format->formatCurrency(($row2['0sd50']*$percentperiod*1.2)/100/$per),
                          Yii::app()->format->formatCurrency($row2['50sd90']/$per),
                          Yii::app()->format->formatCurrency(($row2['50sd90']*$percentperiod*1.1)/100/$per),
                          Yii::app()->format->formatCurrency($row2['90sd110']/$per),
                          Yii::app()->format->formatCurrency(($row2['90sd110']*$percentperiod*1)/100/$per),
                          Yii::app()->format->formatCurrency($row2['110sd150']/$per),
                          Yii::app()->format->formatCurrency(($row2['110sd150']*$percentperiod*0.9)/100/$per),
                          Yii::app()->format->formatCurrency($row2['sd150']/$per),
                          Yii::app()->format->formatCurrency(0),
                          Yii::app()->format->formatCurrency($jumlahbayar),
                          Yii::app()->format->formatCurrency($jumlahut)
                        ));
                        $custbayar0sd50 += $row2['0sd50']/$per;
                        $custscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $custbayar50sd90 += $row2['50sd90']/$per;
                        $custscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $custbayar90sd110 += $row2['90sd110']/$per;
                        $custscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $custbayar110sd150 += $row2['110sd150']/$per;
                        $custscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $custbayarsd150 += $row2['sd150']/$per;
                        $custscalesd150 = 0;
                        $custjumlahbayar += $jumlahbayar;
                        $custscalejumlah += $jumlahut;
                      
                        $subbayar0sd50 += $row2['0sd50']/$per;
                        $subscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $subbayar50sd90 += $row2['50sd90']/$per;
                        $subscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $subbayar90sd110 += $row2['90sd110']/$per;
                        $subscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $subbayar110sd150 += $row2['110sd150']/$per;
                        $subscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $subbayarsd150 += $row2['sd150']/$per;
                        $subscalesd150 = 0;
                        $subjumlahbayar += $jumlahbayar;
                        $subscalejumlah += $jumlahut;
                      
                        $totbayar0sd50 += $row2['0sd50']/$per;
                        $totscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $totbayar50sd90 += $row2['50sd90']/$per;
                        $totscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $totbayar90sd110 += $row2['90sd110']/$per;
                        $totscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $totbayar110sd150 += $row2['110sd150']/$per;
                        $totscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $totbayarsd150 += $row2['sd150']/$per;
                        $totscalesd150 = 0;
                        $totjumlahbayar += $jumlahbayar;
                        $totscalejumlah += $jumlahut;
                      
                        //$totjumlahut = $totjumlahut + $jumlahut;
                    }
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->row(array(
						'Jumlah '.$row1['period'],
						Yii::app()->format->formatCurrency($custbayar0sd50),
						Yii::app()->format->formatCurrency($custscale0sd50),
						Yii::app()->format->formatCurrency($custbayar50sd90),
						Yii::app()->format->formatCurrency($custscale50sd90),
						Yii::app()->format->formatCurrency($custbayar90sd110),
						Yii::app()->format->formatCurrency($custscale90sd110),
						Yii::app()->format->formatCurrency($custbayar110sd150),
						Yii::app()->format->formatCurrency($custscale110sd150),
						Yii::app()->format->formatCurrency($custbayarsd150),
						Yii::app()->format->formatCurrency($custscalesd150),
						Yii::app()->format->formatCurrency($custjumlahbayar),
						Yii::app()->format->formatCurrency($custscalejumlah)
					));
                    $this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R');
                }
				$this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
				$this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->coldetailalign = array('L','R','R');
				//$this->pdf->setwidths(array(80,165,35));
				$this->pdf->row(array('TOTAL '.strtoupper($cust['custcategoryname']),
					Yii::app()->format->formatCurrency($subbayar0sd50),
					Yii::app()->format->formatCurrency($subscale0sd50),
					Yii::app()->format->formatCurrency($subbayar50sd90),
					Yii::app()->format->formatCurrency($subscale50sd90),
					Yii::app()->format->formatCurrency($subbayar90sd110),
					Yii::app()->format->formatCurrency($subscale90sd110),
					Yii::app()->format->formatCurrency($subbayar110sd150),
					Yii::app()->format->formatCurrency($subscale110sd150),
					Yii::app()->format->formatCurrency($subbayarsd150),
					Yii::app()->format->formatCurrency($subscalesd150),
					Yii::app()->format->formatCurrency($subjumlahbayar),
					Yii::app()->format->formatCurrency($subscalejumlah)
				));
				$this->pdf->setY($this->pdf->getY()+5);
            }
			$this->pdf->checkNewPage(55);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->row(array('TOTAL SALES '.strtoupper($row['fullname']),
				Yii::app()->format->formatCurrency($totbayar0sd50),
				Yii::app()->format->formatCurrency($totscale0sd50),
				Yii::app()->format->formatCurrency($totbayar50sd90),
				Yii::app()->format->formatCurrency($totscale50sd90),
				Yii::app()->format->formatCurrency($totbayar90sd110),
				Yii::app()->format->formatCurrency($totscale90sd110),
				Yii::app()->format->formatCurrency($totbayar110sd150),
				Yii::app()->format->formatCurrency($totscale110sd150),
				Yii::app()->format->formatCurrency($totbayarsd150),
				Yii::app()->format->formatCurrency($totscalesd150),
				Yii::app()->format->formatCurrency($totjumlahbayar),
				Yii::app()->format->formatCurrency($totscalejumlah)
			));
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->coldetailalign = array('L','R','R');
            $this->pdf->setwidths(array(165,100,35));
			$saldo120per = $saldo120/$per;
            $this->pdf->row(array('TOTAL UPAH TAMBAHAN ','',Yii::app()->format->formatCurrency($totscalejumlah)));
            
            $this->pdf->row(array('DEPOSIT 10%','',Yii::app()->format->formatCurrency($totscalejumlah*-0.1)));

            $this->pdf->row(array('SALDO UMUR PIUTANG > 120 HARI (-0.25%)',Yii::app()->format->formatCurrency($saldo120per),
                                  Yii::app()->format->formatCurrency(($saldo120per*-0.25)/100)));
            
            
            //$upah = ($totscalejumlah+(($saldo120per*-0.25)/100))*0.9;
            $upah = $totscalejumlah+($totscalejumlah*-0.1)+($saldo120per*-0.25/100);
            $this->pdf->row(array('UPAH TAMBAHAN YANG DITERIMA SALES '.strtoupper($row['fullname']),'',Yii::app()->format->formatCurrency($upah)));
            //$this->pdf->text(10,$this->pdf->getY(),'SALDO UMUR PIUTANG > 120 HARI');
            $this->pdf->setY($this->pdf->getY()+5);
			
            $this->pdf->setFont('Arial','',9);
			$this->pdf->setwidths(array(63,63,63,63,63));
			$this->pdf->coldetailalign = array('C','C','C','C','C');
			$this->pdf->row(array('Diperiksa Oleh,', 'Disetujui Oleh,', 'Diketahui Oleh,', 'Dibayar Oleh,', 'Diterima Oleh,'));
			$this->pdf->setY($this->pdf->getY()+20);
			$this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR', strtoupper($row['fullname'])));
			$this->pdf->checkNewPage(120);
            
        }
        $this->pdf->Output();
    }
	public function RincianKomisiKasta_lama3($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
    {
        parent::actionDownload();
        $connection = Yii::app()->db;
        $this->pdf->title='RINCIAN UPAH TAMBAHAN SALES';
	  $this->pdf->companyid=$companyid;
        $datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

        $this->pdf->subtitle='PERIODE : '.$datetime->format('F').' '.$datetime->format('Y');

        $month = date('m',strtotime($enddate));
        $year = date('Y',strtotime($enddate));

        $prev_month_ts =  strtotime($year.'-'.$month.'-01');
        $month1 = date('Y-m-d', $prev_month_ts);

        $this->pdf->AddPage('L','F4');

        $this->pdf->sety($this->pdf->gety()+5);

        $this->pdf->text(300,15,'Per : '.$per);
        //$this->pdf->text(270,15,'X = T.O.P');
        $wheresalesarea = $whereproduct = '';
        $sqldata = "select distinct f.employeeid,f.fullname,e.companyid
        from cutarinv a
        join cutar b on b.cutarid=a.cutarid
        join invoice c on c.invoiceid=a.invoiceid
        join giheader d on d.giheaderid=c.giheaderid
        join soheader e on e.soheaderid=d.soheaderid
        join ttnt h on h.ttntid=b.ttntid
        join employee f on f.employeeid=h.employeeid
        join addressbook g on g.addressbookid=e.addressbookid
		join employeeorgstruc i on i.employeeid=f.employeeid
		join orgstructure j on j.orgstructureid=i.orgstructureid and j.companyid=e.companyid
        {$wheresalesarea} {$whereproduct}
        where j.structurename LIKE '%salesman%' and i.recordstatus=1 and g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
        b.recordstatus=3
		and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
		and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
        and e.isdisplay=0
        order by f.fullname asc";
        
        $data = Yii::app()->db->createCommand($sqldata)->queryAll();
        foreach($data as $row)
		{
            $totbayar0sd50 = 0;
            $totscale0sd50 = 0;
            $totbayar50sd90 = 0;
            $totscale50sd90 = 0;
            $totbayar90sd110 = 0;
            $totscale90sd110 = 0;
            $totbayar110sd150 = 0;
            $totscale110sd150 = 0;
            $totbayarsd150 = 0;
            $totscalesd150 = 0;
            $totjumlahbayar = 0;
            $totscalejumlah = 0;
			
			$sqlsaldoup120 = "select sum(a5) as up120
				from (select case when umur > 120 then amount-payamount else 0 end as a5
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
						where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
						and ff.fullname like '%".$sales."%'  and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and c.employeeid = {$row['employeeid']}
						-- and d.custcategoryid = {$cust['custcategoryid']}
						) z
					where amount > payamount) zz";
			$saldo120 = Yii::app()->db->createCommand($sqlsaldoup120)->queryScalar();
          
            $sqlcustcategory = "select i.custcategoryid,i.custcategoryname
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            join invoice c on c.invoiceid=a.invoiceid
            join giheader d on d.giheaderid=c.giheaderid
            join soheader e on e.soheaderid=d.soheaderid
            join ttnt h on h.ttntid=b.ttntid
            join employee f on f.employeeid=h.employeeid
            join addressbook g on g.addressbookid=e.addressbookid
            join custcategory i on i.custcategoryid = g.custcategoryid
            {$wheresalesarea} {$whereproduct}
            where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
			and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
			and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
            and e.companyid = {$companyid} and f.employeeid = {$row['employeeid']}
            and e.isdisplay=0
            group by g.custcategoryid
            order by g.custcategoryid asc";
            $this->pdf->SetFont('Arial','B',9);
            $this->pdf->text(10,$this->pdf->getY(),'NAMA SALES : '.$row['fullname']);
            $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();
            foreach($custcategory as $cust)
			{
				$subbayar0sd50 = 0;
				$subscale0sd50 = 0;
				$subbayar50sd90 = 0;
				$subscale50sd90 = 0;
				$subbayar90sd110 = 0;
				$subscale90sd110 = 0;
				$subbayar110sd150 = 0;
				$subscale110sd150 = 0;
				$subbayarsd150 = 0;
				$subscalesd150 = 0;
				$subjumlahbayar = 0;
				$subscalejumlah = 0;
                          
                $this->pdf->setFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory'). ' = '. $cust['custcategoryname']);
                $subjumlahut = 0;
                $this->pdf->SetFont('Arial','B',9);

                $sqlperiod = "select period, invoicedate from (select c.invoiceno,c.invoicedate,b.docdate,setdateformat(c.invoicedate) as period, a.cashamount+a.bankamount as nilai
                from cutarinv a
                join cutar b on b.cutarid=a.cutarid
                join invoice c on c.invoiceid=a.invoiceid
                join giheader d on d.giheaderid=c.giheaderid
                join soheader e on e.soheaderid=d.soheaderid
                join ttnt h on h.ttntid=b.ttntid
                join employee f on f.employeeid=h.employeeid
                join addressbook g on g.addressbookid=e.addressbookid
                {$wheresalesarea} {$whereproduct}
                where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
				and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
                and e.companyid = {$companyid} and f.employeeid = {$row['employeeid']}
                and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
                order by invoicedate asc ) z
                where nilai <> 0
                group by month(invoicedate),year(invoicedate)
				order by invoicedate asc";
                $this->pdf->setY($this->pdf->getY()+10);
                $period = Yii::app()->db->createCommand($sqlperiod)->queryAll();
                foreach($period as $row1) 
				{
					$custbayar0sd50 = 0;
					$custscale0sd50 = 0;
					$custbayar50sd90 = 0;
					$custscale50sd90 = 0;
					$custbayar90sd110 = 0;
					$custscale90sd110 = 0;
					$custbayar110sd150 = 0;
					$custscale110sd150 = 0;
					$custbayarsd150 = 0;
					$custscalesd150 = 0;
					$custjumlahbayar = 0;
					$custscalejumlah = 0;
					
                    $sqlawalbulan = "select date_add(date_add(last_day('{$row1['invoicedate']}'),interval 1 day), interval -1 month) as awalbulan";
                    $tglawal = Yii::app()->db->createCommand($sqlawalbulan)->queryScalar();
					$percentperiod = Yii::app()->db->createCommand("select ifnull(scalevalue,0) from scalevalue where recordstatus=5 and custcategoryid = {$cust['custcategoryid']} and employeeid = {$row['employeeid']}
                      and perioddate = '{$tglawal}'")->queryScalar();

                    $this->pdf->setFont('Arial','',9);
                    $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
                    $this->pdf->setwidths(array(30,47,47,47,47,47,48));
                    $this->pdf->colheader = array("          Period             (".Yii::app()->format->formatCurrency($percentperiod)."% ) ",'            <= 50%TOP             120%','   50%TOP < U <= 90%TOP    110%','90%TOP < U <= 110%TOP    100%','110%TOP < U <= 150%TOP    90%','              U > 150%TOP                0%','                  Jumlah                               ');
                    $this->pdf->RowHeader();

                    $this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
                    $this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
                    $this->pdf->SetFont('Arial','',9);
                    $this->pdf->row(array($row1['period'],'NIlai Bayar','UT','Nilai Bayar','UT','NIlai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT'));

                    $sqldet = "select *,
								case when umur >= 0 and umur <= (0.5 * paydays) then nilaibayar else 0 end as 0sd50,
								case when umur > (0.5 * paydays) and umur <= (0.9 * paydays) then nilaibayar else 0 end AS 50sd90,
								case when umur > (0.9 * paydays) and umur <= (1.1 * paydays) then nilaibayar else 0 end AS 90sd110,
								case when umur > (1.1 * paydays) and umur <= (1.5 * paydays) then nilaibayar else 0 end AS 110sd150,
								case when umur > (1.5 * paydays) then nilaibayar else 0 end as sd150
								,(0.5 * paydays),(0.9 * paydays),(paydays),(1.5 * paydays)
								from (select distinct g.fullname,c.invoiceno,c.invoicedate,b.docdate AS cutardate,datediff(b.docdate,c.invoicedate) as umur,p.paydays,c.amount,
								a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
								(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
								join invoice c on c.invoiceid=a.invoiceid
								join giheader d on d.giheaderid=c.giheaderid
								join soheader e on e.soheaderid=d.soheaderid
								join ttnt i on i.ttntid=b.ttntid
								join employee f on f.employeeid=i.employeeid
								join addressbook g on g.addressbookid=e.addressbookid
								join paymentmethod p ON p.paymentmethodid=e.paymentmethodid
								{$wheresalesarea} {$whereproduct}
								where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
								and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and e.companyid = {$companyid}
								and f.employeeid = ".$row['employeeid']."
								and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
								) z
								-- where z.materialgroupid = ''
								where month(invoicedate) = month('{$row1['invoicedate']}')
								and year(invoicedate) = year('{$row1['invoicedate']}')
								and nilaibayar <> 0 
								order by cutardate,fullname,invoicedate";
					$detail = Yii::app()->db->createCommand($sqldet)->queryAll();
                    $this->pdf->setFont('Arial','',8);
                    foreach($detail as $row2)
					{
                        //$this->pdf->row(array('','NIlai INV','UT','Nilai INV','UT','NIlai INV','UT','Nilai INV','UT','Nilai INV','UT',''));
                        $jumlahbayar = ($row2['0sd50'] + $row2['50sd90'] + $row2['90sd110'] + $row2['110sd150'] + $row2['sd150'])/$per;
                        $jumlahut = (($row2['0sd50']*$percentperiod*1.2)+($row2['50sd90']*$percentperiod*1.1)+($row2['90sd110']*$percentperiod*1)+
                                    ($row2['110sd150']*$percentperiod*0.9))/100/$per;
                        $this->pdf->row(array(
                          $row2['invoiceno'],
                          Yii::app()->format->formatCurrency($row2['0sd50']/$per),
                          Yii::app()->format->formatCurrency(($row2['0sd50']*$percentperiod*1.2)/100/$per),
                          Yii::app()->format->formatCurrency($row2['50sd90']/$per),
                          Yii::app()->format->formatCurrency(($row2['50sd90']*$percentperiod*1.1)/100/$per),
                          Yii::app()->format->formatCurrency($row2['90sd110']/$per),
                          Yii::app()->format->formatCurrency(($row2['90sd110']*$percentperiod*1)/100/$per),
                          Yii::app()->format->formatCurrency($row2['110sd150']/$per),
                          Yii::app()->format->formatCurrency(($row2['110sd150']*$percentperiod*0.9)/100/$per),
                          Yii::app()->format->formatCurrency($row2['sd150']/$per),
                          Yii::app()->format->formatCurrency(0),
                          Yii::app()->format->formatCurrency($jumlahbayar),
                          Yii::app()->format->formatCurrency($jumlahut)
                        ));
                        $custbayar0sd50 += $row2['0sd50']/$per;
                        $custscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $custbayar50sd90 += $row2['50sd90']/$per;
                        $custscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $custbayar90sd110 += $row2['90sd110']/$per;
                        $custscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $custbayar110sd150 += $row2['110sd150']/$per;
                        $custscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $custbayarsd150 += $row2['sd150']/$per;
                        $custscalesd150 = 0;
                        $custjumlahbayar += $jumlahbayar;
                        $custscalejumlah += $jumlahut;
                      
                        $subbayar0sd50 += $row2['0sd50']/$per;
                        $subscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $subbayar50sd90 += $row2['50sd90']/$per;
                        $subscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $subbayar90sd110 += $row2['90sd110']/$per;
                        $subscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $subbayar110sd150 += $row2['110sd150']/$per;
                        $subscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $subbayarsd150 += $row2['sd150']/$per;
                        $subscalesd150 = 0;
                        $subjumlahbayar += $jumlahbayar;
                        $subscalejumlah += $jumlahut;
                      
                        $totbayar0sd50 += $row2['0sd50']/$per;
                        $totscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $totbayar50sd90 += $row2['50sd90']/$per;
                        $totscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $totbayar90sd110 += $row2['90sd110']/$per;
                        $totscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $totbayar110sd150 += $row2['110sd150']/$per;
                        $totscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $totbayarsd150 += $row2['sd150']/$per;
                        $totscalesd150 = 0;
                        $totjumlahbayar += $jumlahbayar;
                        $totscalejumlah += $jumlahut;
                      
                        $totjumlahut = $totjumlahut + $jumlahut;
                    }
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->row(array(
						'Jumlah '.$row1['period'],
						Yii::app()->format->formatCurrency($custbayar0sd50),
						Yii::app()->format->formatCurrency($custscale0sd50),
						Yii::app()->format->formatCurrency($custbayar50sd90),
						Yii::app()->format->formatCurrency($custscale50sd90),
						Yii::app()->format->formatCurrency($custbayar90sd110),
						Yii::app()->format->formatCurrency($custscale90sd110),
						Yii::app()->format->formatCurrency($custbayar110sd150),
						Yii::app()->format->formatCurrency($custscale110sd150),
						Yii::app()->format->formatCurrency($custbayarsd150),
						Yii::app()->format->formatCurrency($custscalesd150),
						Yii::app()->format->formatCurrency($custjumlahbayar),
						Yii::app()->format->formatCurrency($custscalejumlah)
					));
                    $this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R');
                }
				$this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
				$this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->coldetailalign = array('L','R','R');
				//$this->pdf->setwidths(array(80,165,35));
				$this->pdf->row(array('TOTAL '.strtoupper($cust['custcategoryname']),
					Yii::app()->format->formatCurrency($subbayar0sd50),
					Yii::app()->format->formatCurrency($subscale0sd50),
					Yii::app()->format->formatCurrency($subbayar50sd90),
					Yii::app()->format->formatCurrency($subscale50sd90),
					Yii::app()->format->formatCurrency($subbayar90sd110),
					Yii::app()->format->formatCurrency($subscale90sd110),
					Yii::app()->format->formatCurrency($subbayar110sd150),
					Yii::app()->format->formatCurrency($subscale110sd150),
					Yii::app()->format->formatCurrency($subbayarsd150),
					Yii::app()->format->formatCurrency($subscalesd150),
					Yii::app()->format->formatCurrency($subjumlahbayar),
					Yii::app()->format->formatCurrency($subscalejumlah)
				));
				$this->pdf->setY($this->pdf->getY()+5);
            }
			$this->pdf->checkNewPage(55);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->row(array('TOTAL SALES '.strtoupper($row['fullname']),
				Yii::app()->format->formatCurrency($totbayar0sd50),
				Yii::app()->format->formatCurrency($totscale0sd50),
				Yii::app()->format->formatCurrency($totbayar50sd90),
				Yii::app()->format->formatCurrency($totscale50sd90),
				Yii::app()->format->formatCurrency($totbayar90sd110),
				Yii::app()->format->formatCurrency($totscale90sd110),
				Yii::app()->format->formatCurrency($totbayar110sd150),
				Yii::app()->format->formatCurrency($totscale110sd150),
				Yii::app()->format->formatCurrency($totbayarsd150),
				Yii::app()->format->formatCurrency($totscalesd150),
				Yii::app()->format->formatCurrency($totjumlahbayar),
				Yii::app()->format->formatCurrency($totscalejumlah)
			));
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->coldetailalign = array('L','R','R');
            $this->pdf->setwidths(array(165,100,35));
			$saldo120per = $saldo120/$per;
            $this->pdf->row(array('SALDO UMUR PIUTANG > 120 HARI (-0.5%)',Yii::app()->format->formatCurrency($saldo120per),
                                  Yii::app()->format->formatCurrency(($saldo120per*-0.5)/100)));
            $this->pdf->row(array('TOTAL UPAH TAMBAHAN','',Yii::app()->format->formatCurrency($totscalejumlah+(($saldo120per*-0.5)/100))));
            $this->pdf->row(array('DEPOSIT 10%','',Yii::app()->format->formatCurrency(($totscalejumlah+(($saldo120per*-0.5)/100))*-0.1)));
            $upah = ($totscalejumlah+(($saldo120per*-0.5)/100))*0.9;
            $this->pdf->row(array('UPAH TAMBAHAN YANG DITERIMA SALES '.strtoupper($row['fullname']),'',Yii::app()->format->formatCurrency($upah)));
            //$this->pdf->text(10,$this->pdf->getY(),'SALDO UMUR PIUTANG > 120 HARI');
            $this->pdf->setY($this->pdf->getY()+5);
			
            $this->pdf->setFont('Arial','',9);
			$this->pdf->setwidths(array(63,63,63,63,63));
			$this->pdf->coldetailalign = array('C','C','C','C','C');
			$this->pdf->row(array('Diperiksa Oleh,', 'Disetujui Oleh,', 'Diketahui Oleh,', 'Dibayar Oleh,', 'Diterima Oleh,'));
			$this->pdf->setY($this->pdf->getY()+20);
			$this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR', strtoupper($row['fullname'])));
			$this->pdf->checkNewPage(120);
            
        }
        $this->pdf->Output();
    }
	public function RekapKomisiTagihanPerSales($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
			parent::actionDownload();
			//$this->no_result();
			
			$connection = Yii::app()->db;
			$this->pdf->title='LAPORAN TARGET PENAGIHAN VS REALISASI PENAGIHAN';
			$datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

			$this->pdf->subtitle='TARGET BULAN : '.$datetime->format('F').' '.$datetime->format('Y');
			
			$month = date('m',strtotime($enddate));
			$year = date('Y',strtotime($enddate));
			
			$prev_month_ts =  strtotime($year.'-'.$month.'-01');
			$month1 = date('Y-m-d', $prev_month_ts);
			
			$this->pdf->AddPage('L',array(210,365));
			
			$this->pdf->SetFont('Arial','B',10);
			$this->pdf->sety($this->pdf->gety()+5);
			
			$this->pdf->text(310,15,'Per : '.$per);
			$this->pdf->text(270,15,'X = T.O.P');
			
			$maxstat2 = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appps')")->queryScalar();
			
			$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppt')")->queryScalar();
            $maxs=0;
            $maxscale = Yii::app()->db->createCommand("select b.min, b.max,gt30,gt15,gt7,gt0,x0,lt0,lt14
                from paymentscale a
                join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                where a.companyid={$companyid} and a.perioddate  = '{$month1}' and a.recordstatus={$maxstat2}
                and b.min = (select max(c.min) from paymentscaledet c where c.paymentscaleid=a.paymentscaleid)")->queryRow();
        
			$sqlsales = "select distinct employeeid, fullname from (
											select c.employeeid, c.fullname, b.addressbookid
											from paymenttarget a
											join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
											join employee c on a.employeeid = c.employeeid
											where a.recordstatus = {$maxstat} and month(a.perioddate) = month('".$enddate."') and year(a.perioddate) = year('".$enddate."')
											and c.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")." and a.companyid = {$companyid}
											union
											select g.employeeid, g.fullname, e.addressbookid
											from cutarinv a
											join cutar b on b.cutarid = a.cutarid
											join invoice c on c.invoiceid = a.invoiceid
											join giheader d on d.giheaderid = c.giheaderid
											join soheader e on e.soheaderid = d.soheaderid
											join ttnt f on f.ttntid = b.ttntid
											join employee g on g.employeeid = f.employeeid
											where g.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")."
											and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and b.companyid = {$companyid} group by addressbookid ) z group by addressbookid";
			//$res = Yii::app()->db->createCommand($sqlsales)->queryAll();
			$emp = Yii::app()->db->createCommand($sqlsales)->queryAll();
			
			foreach($emp as $sales)
			{
					$totaltarget = 0;
					$totalrealisasi = 0;
					$totalgt30 = 0; 
					$totalgt15 = 0;
					$totalgt7 = 0;
					$totalgt0 = 0;
					$totalx0 = 0;
					$totallt0 = 0;
					$totallt14 = 0;
					$komisigt30 = 0;
					$komisigt15 = 0;
					$komisigt7 = 0;
					$komisigt0 = 0;
					$komisix0 = 0;
					$komisilt0 = 0;
					$komisilt14 = 0;
                    $totalkomisi = 0;
                    $percent=0;
                    $persentotal=0;
                    $next=0;
                
                    $gt30 = 0;
                    $gt15 = 0;
                    $gt7 = 0;
                    $gt0 = 0;
                    $x0 = 0;
                    $lt0 = 0;
                    $lt14 = 0;
                    
                    $sqlcustcategory = "select a.custcategoryid as custid, custcategoryname
                                        from custcategory a
                                        join paymenttargetdet b on b.custcategoryid = a.custcategoryid
                                        join paymenttarget c on c.paymenttargetid = b.paymenttargetid
                                        where c.companyid = {$companyid} and c.perioddate = '{$month1}' and c.employeeid = ".$sales['employeeid']."
                                        and c.recordstatus = {$maxstat}
                                        group by a.custcategoryid
                                        union
                                        select i.custcategoryid, i.custcategoryname
                                        from cutarinv a
                                        join cutar b on b.cutarid = a.cutarid
                                        join invoice c on c.invoiceid = a.invoiceid
                                        join giheader d on d.giheaderid = c.giheaderid
                                        join soheader e on e.soheaderid = d.soheaderid
                                        join ttnt f on f.ttntid = b.ttntid
                                        join employee g on g.employeeid = f.employeeid
                                        join addressbook h on h.addressbookid = e.addressbookid
                                        join custcategory i on i.custcategoryid = h.custcategoryid
                                        where b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and g.employeeid = ".$sales['employeeid']." and b.companyid = {$companyid} group by h.custcategoryid
                    ";
                    
                    $this->pdf->setwidths(array(150));
                    $this->pdf->SetFont('Arial','',10);
                    $this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->row(array('NAMA SALES : '.$sales['fullname']));
                    $this->pdf->text(10,$this->pdf->getY(),'NAMA SALES : '.$sales['fullname']);
                
                    $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();
                    foreach($custcategory as $rslt)
                    {
                        //$sqladdressbook = "select addressbookid, fullname as custname from addressbook a where a.addressbookid = ".$sales['addressbookid'];

                        $totaltargetcust = 0;
                        $totalrealisasicust = 0;
                        $totalgt30cust = 0; 
                        $totalgt15cust = 0;
                        $totalgt7cust = 0;
                        $totalgt0cust = 0;
                        $totalx0cust = 0;
                        $totallt0cust = 0;
                        $totallt14cust = 0;
                        $totalkomisicust = 0;
                        $percent1=0;
                        
                        if($rslt['custid']=='')
                        {
                            $next=0;
                        }
                        else
                        {
                            $next=1;
                        }
                            $sqladdressbook = "select b.addressbookid, c.fullname as custname
                                        from paymenttarget a
                                        join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
                                        join addressbook c on c.addressbookid = b.addressbookid
                                        where a.recordstatus = {$maxstat} and perioddate = '{$month1}' and a.employeeid = ".$sales['employeeid']." and a.companyid = {$companyid}
                                        and b.custcategoryid = ".$rslt['custid']."
                                        union
                                        select h.addressbookid, h.fullname as custname 
                                        from cutarinv a
                                        join cutar b on b.cutarid = a.cutarid
                                        join invoice c on c.invoiceid = a.invoiceid
                                        join giheader d on d.giheaderid = c.giheaderid
                                        join soheader e on e.soheaderid = d.soheaderid
                                        join ttnt f on f.ttntid = b.ttntid
                                        join employee g on g.employeeid = f.employeeid
                                        join addressbook h on h.addressbookid = e.addressbookid
                                        where b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and g.employeeid = ".$sales['employeeid']." and b.companyid = {$companyid} and h.custcategoryid = ".$rslt['custid']." group by h.addressbookid ";
                        
                        $addressbook = Yii::app()->db->createCommand($sqladdressbook)->queryAll();
                        
                        $this->pdf->setFont('Arial','B',10);
                        $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory').' : '.$rslt['custcategoryname']);
                        
                        $this->pdf->setY($this->pdf->getY()+8);
                        $this->pdf->SetFont('Arial','',10);
                        $this->pdf->colalign = array('C','C','C','C','C');
                        $this->pdf->setwidths(array(50,150,150));

                        $this->pdf->colheader = array('','KUMULATIF REALISASI','NILAI KOMISI TAGIHAN');

                        $this->pdf->RowHeader();
                        $this->pdf->coldetailalign = array('L','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
                        $this->pdf->setwidths(array(28,20,21,12,17,17,17,17,17,17,17,17,17,17,17,17,17,17,21));
                        $this->pdf->SetFont('Arial','',9);
                        $this->pdf->row(array('KET','TARGET TAGIHAN','REALISASI TAGIHAN','%',
                                                                    'X > 30','15<X<=30','7X<=15','0 <X<=7','X=0','-14<X<= 0','X < -14',
                                                                    'X > 30','15<X<=30','7X<=15','0 <X<=7','X=0','-14<X<= 0','X < -14',
                                                                    'TOTAL'));

                        $this->pdf->setwidths(array(28,20,21,12,17,17,17,17,17,17,17,17,17,17,17,17,17,17,21));
                        $this->pdf->setFont('Arial','',9);
                        foreach($addressbook as $row1)
                        {   
                                $sql2 = "select sum(target) as target, sum(realisasi) as realisasi,
                                                        sum(kumlt14) as kumlt14,sum(kumlt0) as kumlt0,sum(kumx0) as kumx0,sum(kumgt0) as kumgt0,sum(kumgt7) as kumgt7,sum(kumgt15) as kumgt15,sum(kumgt30) as kumgt30,
                                                        addressbookid,fullname
                                                from (select ifnull(sum(gt30+gt15+gt7+gt0+x0+lt0+lt14),0) as target, 
                                                         (select sum(a.cashamount+a.bankamount) as kumbayar
                                                                        from cutarinv a
                                                                        join cutar b on a.cutarid = b.cutarid
                                                                        join invoice c on c.invoiceid = a.invoiceid
                                                                        join giheader d on d.giheaderid = c.giheaderid
                                                                        join soheader e on e.soheaderid = d.soheaderid
                                                                        join ttnt i on i.ttntid = b.ttntid
                                                                        join employee f on f.employeeid = i.employeeid
                                                                        join addressbook g on g.addressbookid = e.addressbookid
                                                                        join paymentmethod h on h.paymentmethodid = e.paymentmethodid
                                                                        where f.employeeid = {$sales['employeeid']} and e.companyid = {$companyid}
                                                                        and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and g.addressbookid = {$row1['addressbookid']}) as realisasi,
                                                                0 as kumlt14,0 as kumlt0, 0 as kumx0, 0 as kumgt0, 0 as kumgt7, 0 as kumgt15, 0 as kumgt30, c.addressbookid,c.fullname, ''
                                                        from paymenttarget a
                                                        join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
                                                        join addressbook c on c.addressbookid = b.addressbookid
                                                        where b.addressbookid = {$row1['addressbookid']} and a.recordstatus = {$maxstat} and a.companyid = {$companyid}
                                                        and a.employeeid = {$sales['employeeid']} and a.perioddate = '{$month1}'
                                                        union
                                                        select 0 target, 0 as realisasi,
                                                                case when umurtop < -14 then sum(kumbayar) else 0 end as kumlt14,
                                                                case when umurtop < 0 and umurtop >= -14 then sum(kumbayar) else 0 end as kumlt0,
                                                                case when umurtop = 0 then sum(kumbayar) else 0 end as kumx0,
                                                                case when umurtop > 0 and umurtop <= 7 then sum(kumbayar) else 0 end as kumgt0,
                                                                case when umurtop > 7 and umurtop <= 15 then sum(kumbayar) else 0 end as kumgt7,
                                                                case when umurtop > 15 and umurtop <= 30 then sum(kumbayar) else 0 end as kumgt15,
                                                                case when umurtop > 30 then sum(kumbayar) else 0 end as kumgt30, addressbookid, fullname, invoiceno
                                                        from (
                                                                select c.invoiceno, a.invoiceid, g.fullname, datediff(b.docdate,date_add(c.invoicedate, interval h.paydays day)) as umurtop, a.cashamount+a.bankamount as kumbayar, g.addressbookid
                                                                from cutarinv a
                                                                join cutar b on a.cutarid = b.cutarid
                                                                join invoice c on c.invoiceid = a.invoiceid
                                                                join giheader d on d.giheaderid = c.giheaderid
                                                                join soheader e on e.soheaderid = d.soheaderid
                                                                join ttnt i on i.ttntid = b.ttntid
                                                                join employee f on f.employeeid = i.employeeid
                                                                join addressbook g on g.addressbookid = e.addressbookid
                                                                join paymentmethod h on h.paymentmethodid = e.paymentmethodid
                                                                where f.employeeid = {$sales['employeeid']} and e.companyid = {$companyid}
                                                                and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and g.addressbookid = {$row1['addressbookid']}
                                                        ) z group by invoiceid) x";
                                $res2 = Yii::app()->db->createCommand($sql2)->queryAll();
                            
                                foreach($res2 as $row3)
                                {                    
                                        if($row3['target']==0)
                                        {
                                                $percent1 = 0;
                                        }
                                        else
                                        {
                                                $percent1 = $row3['realisasi']/$row3['target'];
                                        }
                                        
                                        $this->pdf->setFont('Arial','',8);
                                        $this->pdf->row(array($row1['custname'],
                                        Yii::app()->format->formatCurrency($row3['target']/$per),
                                        Yii::app()->format->formatCurrency($row3['realisasi']/$per),
                                        number_format($percent1*100,1,'.',',').' %',
                                        Yii::app()->format->formatCurrency($row3['kumgt30']/$per),
                                        Yii::app()->format->formatCurrency($row3['kumgt15']/$per),
                                        Yii::app()->format->formatCurrency($row3['kumgt7']/$per),
                                        Yii::app()->format->formatCurrency($row3['kumgt0']/$per),
                                        Yii::app()->format->formatCurrency($row3['kumx0']/$per),
                                        Yii::app()->format->formatCurrency($row3['kumlt0']/$per),
                                        Yii::app()->format->formatCurrency($row3['kumlt14']/$per),
                                        ));
                                        
                                        $totaltarget = ($totaltarget + $row3['target']);
                                        $totalrealisasi = ($totalrealisasi + $row3['realisasi']);
                                        $totalgt30 = ($totalgt30 + $row3['kumgt30']);
                                        $totalgt15 = ($totalgt15 + $row3['kumgt15']);
                                        $totalgt7  = ($totalgt7 + $row3['kumgt7']);
                                        $totalgt0 = ($totalgt0 + $row3['kumgt0']);
                                        $totalx0 = ($totalx0 + $row3['kumx0']);
                                        $totallt0 = ($totallt0 + $row3['kumlt0']);
                                        $totallt14 = ($totallt14 + $row3['kumlt14']);
                                    
                                    
                                        $totaltargetcust = ($totaltargetcust + $row3['target']);
                                        $totalrealisasicust = ($totalrealisasicust + $row3['realisasi']);
                                        $totalgt30cust = ($totalgt30cust + $row3['kumgt30']);
                                        $totalgt15cust = ($totalgt15cust + $row3['kumgt15']);
                                        $totalgt7cust  = ($totalgt7cust + $row3['kumgt7']);
                                        $totalgt0cust = ($totalgt0cust + $row3['kumgt0']);
                                        $totalx0cust = ($totalx0cust + $row3['kumx0']);
                                        $totallt0cust = ($totallt0cust + $row3['kumlt0']);
                                        $totallt14cust = ($totallt14cust + $row3['kumlt14']);

                                }              
                        }
                        
                        $this->pdf->setY($this->pdf->getY()+2);
                        $sqlminscale = "select minscale
                                    from paymentscale t
                                    where t.recordstatus = {$maxstat2} and t.perioddate = '{$month1}' 
                                    and t.companyid = {$companyid}";
                        $minscale = Yii::app()->db->createCommand($sqlminscale)->queryScalar();

                        $this->pdf->setFont('Arial','B',8);
                        $max=0;
                        $min=0;
                        //$this->pdf->setwidths(array(90));
                        if($totaltarget==0)
                        {
                                $percent = 0;
                                $max = 0;
                                $min = 0;
                        }
                        else
                        {
                                if ($totaltargetcust==0)
                                {
                                    $percent = 0;
                                    $max=0;
                                    $min=0;
                                }
                                else
                                {
                                    $percent = ($totalrealisasicust/$totaltargetcust)*100;
                                }
                                
                                if($percent>=$minscale)
                                {
                                    /*
                                    if($percent > 100)
                                    {
                                            $max = null;
                                            $min = 100;
                                    }
                                    elseif($percent > 90 && $percent < 100)
                                    {
                                            $max = 100;
                                            $min = 90;
                                    }
                                    elseif($percent > 80 && $percent < 90)
                                    {
                                            $max = 90;
                                            $min = 80;
                                    }
                                    elseif($percent > 70 && $percent < 80)
                                    {
                                            $max = 80;
                                            $min = 70;
                                    }
                                    else
                                    {
                                            $max = 70;
                                            $min = null;
                                    }
                                    */
                                    
                                    // jika pencapaian diluar dari skala yg dibuat, 
                                    // maka diambil yg paling tinggi dari skala
                                    
                                    // ambil nilai min tertinggi
                                    
                                    if($percent>$maxscale['min'])
                                    {
                                        // check lebih tinggi dari max
                                        if($percent>$maxscale['max'] || $maxscale['max']=='-1')
                                        {
                                            // ambil skala berdasarkan max persentasi
                                            $gt30 = $maxscale['gt30'];
                                            $gt15 = $maxscale['gt15'];
                                            $gt7 = $maxscale['gt7'];
                                            $gt0 = $maxscale['gt0'];
                                            $x0 = $maxscale['x0'];
                                            $lt0 = $maxscale['lt0'];
                                            $lt14 = $maxscale['lt14'];
                                        }
                                            $gt30 = $maxscale['gt30'];
                                            $gt15 = $maxscale['gt15'];
                                            $gt7 = $maxscale['gt7'];
                                            $gt0 = $maxscale['gt0'];
                                            $x0 = $maxscale['x0'];
                                            $lt0 = $maxscale['lt0'];
                                            $lt14 = $maxscale['lt14'];
                                    }
                                    else
                                    {
                                        $sql = "select * from (select b.*
                                            from paymentscale a
                                            join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                                            where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid}
                                            and min <= round({$percent})
                                            union
                                            select b.*
                                            from paymentscale a
                                            join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                                            where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid}
                                            and max <= round({$percent}) ) z
                                            where round({$percent}) between min and max";
                                        $q = Yii::app()->db->createCommand($sql)->queryRow();
                                        $gt30 = $q['gt30'];
                                        $gt15 = $q['gt15'];
                                        $gt7 = $q['gt7'];
                                        $gt0 = $q['gt0'];
                                        $x0 = $q['x0'];
                                        $lt0 = $q['lt0'];
                                        $lt14 = $q['lt14'];
                                    }
                                    
                                    /*
                                    if($percent>$scale)
                                    {
                                        $percent1 = $scale;
                                    }
                                    
                                    if($maxs===null)
                                    {
                                        $maxsql = " and max is null";
                                    }
                                    else
                                    {
                                        $maxsql = " and case 
                                        when {$percent1} > max then  
                                            max>=120
                                        else 
                                            max>={$percent1}
                                        end ";
                                    }
                                    
                                    $sqlminmax = "select min,max from (select b.min, b.max
                                    from paymentscale a
                                    join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                                    where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid}
                                    and min <= {$percent1}
                                    union
                                    select b.min, b.max
                                    from paymentscale a
                                    join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                                    where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid}
                                    and max <= {$percent1} ) z
                                    where min<={$percent1} {$maxsql} ";
                                    $minmax = Yii::app()->db->createCommand($sqlminmax)->queryRow();
                                    
                                    
                                    $count = Yii::app()->db->createCommand($sqlminmax)->query()->rowCount;
                                    if($count>0)
                                    {
                                        $min = ' ='.$minmax['min'];
                                        if($minmax==null)
                                        {
                                            $max = ' is null';
                                        }
                                        else
                                        {
                                            $max = ' = '.$minmax['max'];
                                        }
                                        //$max = $minmax['max'];
                                          
                                        $sqlgt30 = "select gt30 ";
                                        $sqlgt15 = "select gt15 ";
                                        $sqlgt7 = "select gt7 ";
                                        $sqlgt0 = "select gt0 ";
                                        $sqlx0 = "select x0 ";
                                        $sqllt0 = "select lt0 ";
                                        $sqllt14 = "select lt14 ";
                                        $from = "from paymentscale a
                                                 join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                                                 where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid} ";
                                        $where = " and b.min  {$min} and b.max {$max} ";    
                                         $komisigt30cust = Yii::app()->db->createCommand($sqlgt30.$from.$where)->queryScalar();
                                    }
                                    else
                                    {
                                        $min=' = 0';
                                        $max=' = 0';
                                    }
                                    */
                                }
                                else
                                {
                                    //$min=' = 0'; $max=' = 0';
                                    //var_dump($gt30);
                                    $gt30 = 0;
                                    $gt15 = 0;
                                    $gt7 = 0;
                                    $gt0 = 0;
                                    $x0 = 0;
                                    $lt0 = 0;
                                    $lt14 = 0;
                                    
                                }
                           
                            //$min=0; $max=0;
                           
                        }
                        
                        $sqlcustscale = "select paramvalue
                                     from paymentscalecat a
                                     join paymentscale b on b.paymentscaleid = a.paymentscaleid
                                     where b.perioddate = '{$month1}' and b.recordstatus = {$maxstat2} and b.companyid = {$companyid}
                                     and a.custcategoryid = ".$rslt['custid'];
                        $custscale = Yii::app()->db->createCommand($sqlcustscale)->queryScalar();
                        
                        /*
                        $sqlgt30 = "select gt30 ";
                        $sqlgt15 = "select gt15 ";
                        $sqlgt7 = "select gt7 ";
                        $sqlgt0 = "select gt0 ";
                        $sqlx0 = "select x0 ";
                        $sqllt0 = "select lt0 ";
                        $sqllt14 = "select lt14 ";
                        $from = "from paymentscale a
                                 join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                                 where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid} ";
                        
                        /*
                        if($min===null)
                        {
                                $where = " and b.max = {$max} and b.min is null";   
                        }
                        elseif($max===null)
                        {
                                $where = " and b.max is null and b.min = {$min}";
                        }
                        
                        else
                        {
                                $where = " and b.min = {$min} and b.max = {$max} ";    
                        }
                        */
                        //$where = " and b.min {$min} and b.max {$max} ";    
                        /*
                        $komisigt30cust = Yii::app()->db->createCommand($sqlgt30.$from.$where)->queryScalar()*$totalgt30cust*($custscale/100);
                        $komisigt15cust = Yii::app()->db->createCommand($sqlgt15.$from.$where)->queryScalar()*$totalgt15cust*($custscale/100);
                        $komisigt7cust = Yii::app()->db->createCommand($sqlgt7.$from.$where)->queryScalar()*$totalgt7cust*($custscale/100);
                        $komisigt0cust = Yii::app()->db->createCommand($sqlgt0.$from.$where)->queryScalar()*$totalgt0cust*($custscale/100);
                        $komisix0cust = Yii::app()->db->createCommand($sqlx0.$from.$where)->queryScalar()*$totalx0cust*($custscale/100);
                        $komisilt0cust = Yii::app()->db->createCommand($sqllt0.$from.$where)->queryScalar()*$totallt0cust*($custscale/100);
                        $komisilt14cust = Yii::app()->db->createCommand($sqllt14.$from.$where)->queryScalar()*$totallt14cust*($custscale/100);
                        */
                        $komisigt30cust = $gt30*$totalgt30cust*($custscale/100);
                        $komisigt15cust = $gt15*$totalgt15cust*($custscale/100);
                        $komisigt7cust = $gt7*$totalgt7cust*($custscale/100);
                        $komisigt0cust = $gt0*$totalgt0cust*($custscale/100);
                        $komisix0cust = $x0*$totalx0cust*($custscale/100);
                        $komisilt0cust = $lt0*$totallt0cust*($custscale/100);
                        $komisilt14cust = $lt14*$totallt14cust*($custscale/100);
                        
                        $totalkomisicust = $komisigt30cust + $komisigt15cust + $komisigt7cust + $komisigt0cust + $komisix0cust + $komisilt0cust + $komisilt14cust;
                        
                        $komisigt30 += $komisigt30cust/100;
                        $komisigt15 += $komisigt15cust/100;
                        $komisigt7 += $komisigt7cust/100;
                        $komisigt0 += $komisigt0cust/100;
                        $komisix0 += $komisix0cust/100;
                        $komisilt0 += $komisilt0cust/100;
                        $komisilt14 += $komisilt14cust/100;
                        
                        $this->pdf->setFont('Arial','B',8);
                        $this->pdf->row(array('TOTAL KATEGORI : '.$rslt['custcategoryname'],
                                Yii::app()->format->formatCurrency($totaltargetcust/$per),
                                Yii::app()->format->formatCurrency($totalrealisasicust/$per),
                                number_format($percent,0,'.',',').' %',
                                Yii::app()->format->formatCurrency($totalgt30cust/$per),
                                Yii::app()->format->formatCurrency($totalgt15cust/$per),
                                Yii::app()->format->formatCurrency($totalgt7cust/$per),
                                Yii::app()->format->formatCurrency($totalgt0cust/$per),
                                Yii::app()->format->formatCurrency($totalx0cust/$per),
                                Yii::app()->format->formatCurrency($totallt0cust/$per),
                                Yii::app()->format->formatCurrency($totallt14cust/$per),
                                Yii::app()->format->formatCurrency((($komisigt30cust)/100)/$per),
                                Yii::app()->format->formatCurrency((($komisigt15cust)/100)/$per),
                                Yii::app()->format->formatCurrency((($komisigt7cust)/100)/$per),
                                Yii::app()->format->formatCurrency((($komisigt0cust)/100)/$per),
                                Yii::app()->format->formatCurrency((($komisix0cust)/100)/$per),
                                Yii::app()->format->formatCurrency((($komisilt0cust)/100)/$per),
                                Yii::app()->format->formatCurrency((($komisilt14cust)/100)/$per),
                                Yii::app()->format->formatCurrency(($totalkomisicust/100)/$per),
                        ));
                        /*
                        $komisigt30 = $komisigt30 + $komisigt30cust;
                        $komisigt15 = $komisigt15 + $komisigt15cust;
                        $komisigt7 = $komisigt7 + $komisigt7cust;
                        $komisigt0 = $komisigt0 + $komisigt0cust;
                        $komisix0 = $komisix0 + $komisix0cust;
                        $komisilt0 = $komisilt0 + $komisilt0cust;
                        $komisilt14 = $komisilt14 + $komisilt14cust;
                        */
                    }
					
                    $this->pdf->setY($this->pdf->getY()+5);
                    $totalkomisi = $komisigt30 + $komisigt15 + $komisigt7 + $komisigt0 + $komisix0 + $komisilt0 + $komisilt14;
                    if ($totaltarget == 0){
                    	$persentotal = 0;	
                    }
                    else
                    {
                    	$persentotal = ($totalrealisasi/$totaltarget)*100;
                    }
                    if($next!='')
                    {
					   $this->pdf->row(array('TOTAL SALES : '.$sales['fullname'],
									Yii::app()->format->formatCurrency($totaltarget/$per),
									Yii::app()->format->formatCurrency($totalrealisasi/$per),
									number_format($persentotal,0,'.',',').' %',
									Yii::app()->format->formatCurrency($totalgt30/$per),
									Yii::app()->format->formatCurrency($totalgt15/$per),
									Yii::app()->format->formatCurrency($totalgt7/$per),
									Yii::app()->format->formatCurrency($totalgt0/$per),
									Yii::app()->format->formatCurrency($totalx0/$per),
									Yii::app()->format->formatCurrency($totallt0/$per),
									Yii::app()->format->formatCurrency($totallt14/$per),
									Yii::app()->format->formatCurrency((($komisigt30))/$per),
									Yii::app()->format->formatCurrency((($komisigt15))/$per),
									Yii::app()->format->formatCurrency((($komisigt7))/$per),
									Yii::app()->format->formatCurrency((($komisigt0))/$per),
									Yii::app()->format->formatCurrency((($komisix0))/$per),
									Yii::app()->format->formatCurrency((($komisilt0))/$per),
									Yii::app()->format->formatCurrency((($komisilt14))/$per),
									Yii::app()->format->formatCurrency(($totalkomisi)/$per),
									));
                    }
					$this->pdf->row(array(''));
                
                    $deposit = (($totalkomisi)*10/100)/$per;
                    
					$this->pdf->setwidths(array(220,25,35,30));
					$this->pdf->colalign = array('C','R','R','L');
					$this->pdf->row(array('','Deposit 10%', Yii::app()->format->formatCurrency($deposit).' = ',Yii::app()->format->formatCurrency(($totalkomisi/$per)-$deposit)));
					$this->pdf->checkNewPage(25);
					$this->pdf->setwidths(array(50,50,70,70,70));
					$this->pdf->colalign = array('C','C','C','C','C');
					$this->pdf->row(array('Diperiksa Oleh', 'Disetujui Oleh     ', 'Mengetahui      ', 'Dibayar Oleh', 'Diterima Oleh'));
					$this->pdf->setY($this->pdf->getY()+12);
					$this->pdf->setwidths(array(50,50,70,70,70));
					$this->pdf->colalign = array('C','L','C','C','C');
					$this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR     ', 'SALES     '));
					$this->pdf->checkNewPage(-20);
			}
			$this->pdf->Output();
			
	}

	/*public function RekapKomisiTagihanPerSales($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
			parent::actionDownload();
			//$this->no_result();
			
			$connection = Yii::app()->db;
			$this->pdf->title='LAPORAN TARGET PENAGIHAN VS REALISASI PENAGIHAN';
			$datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

			$this->pdf->subtitle='TARGET BULAN : '.$datetime->format('F').' '.$datetime->format('Y');
			
			$month = date('m',strtotime($enddate));
			$year = date('Y',strtotime($enddate));
			
			$prev_month_ts =  strtotime($year.'-'.$month.'-01');
			$month1 = date('Y-m-d', $prev_month_ts);
			
			$this->pdf->AddPage('L',array(210,365));
			
			$this->pdf->SetFont('Arial','B',10);
			$this->pdf->sety($this->pdf->gety()+5);
			
			$this->pdf->text(310,15,'Per : '.$per);
			$this->pdf->text(270,15,'X = T.O.P');
			
			$maxstat2 = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appps')")->queryScalar();
			
			$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppt')")->queryScalar();
			$sqlsales = "select distinct employeeid, fullname from (
											select c.employeeid, c.fullname, b.addressbookid
											from paymenttarget a
											join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
											join employee c on a.employeeid = c.employeeid
											where a.recordstatus = {$maxstat} and month(a.perioddate) = month('".$enddate."') and year(a.perioddate) = year('".$enddate."')
											and c.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")." and a.companyid = {$companyid}
											union
											select g.employeeid, g.fullname, e.addressbookid
											from cutarinv a
											join cutar b on b.cutarid = a.cutarid
											join invoice c on c.invoiceid = a.invoiceid
											join giheader d on d.giheaderid = c.giheaderid
											join soheader e on e.soheaderid = d.soheaderid
											join ttnt f on f.ttntid = b.ttntid
											join employee g on g.employeeid = f.employeeid
											where g.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")."
											and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and b.companyid = {$companyid} group by addressbookid ) z group by addressbookid";
			//$res = Yii::app()->db->createCommand($sqlsales)->queryAll();
			$emp = Yii::app()->db->createCommand($sqlsales)->queryAll();
			
			foreach($emp as $sales)
			{
					$totaltarget = 0;
					$totalrealisasi = 0;
					$totalgt30 = 0; 
					$totalgt15 = 0;
					$totalgt7 = 0;
					$totalgt0 = 0;
					$totalx0 = 0;
					$totallt0 = 0;
					$totallt14 = 0;
					$totalkomisi = 0;
				 
					$this->pdf->SetFont('Arial','',10);
					$this->pdf->colalign = array('C','C','C','C','C');
					$this->pdf->setwidths(array(50,150,150));

					$this->pdf->colheader = array('','KUMULATIF REALISASI','NILAI KOMISI TAGIHAN');

					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
					$this->pdf->setwidths(array(28,20,21,12,17,17,17,17,17,17,17,17,17,17,17,17,17,17,21));
					$this->pdf->SetFont('Arial','',9);
					$this->pdf->row(array('KET','TARGET TAGIHAN','REALISASI TAGIHAN','%',
																'X > 30','15<X<=30','7X<=15','0 <X<=7','X=0','-14<X<= 0','X < -14',
																'X > 30','15<X<=30','7X<=15','0 <X<=7','X=0','-14<X<= 0','X < -14',
																'TOTAL')); 
					
					$this->pdf->setwidths(array(150));
					$this->pdf->SetFont('Arial','',10);
					$this->pdf->row(array('NAMA SALES : '.$sales['fullname']));
					
					$this->pdf->setwidths(array(28,20,21,12,17,17,17,17,17,17,17,17,17,17,17,17,17,17,21));
					$this->pdf->setFont('Arial','',9);
					
					//$sqladdressbook = "select addressbookid, fullname as custname from addressbook a where a.addressbookid = ".$sales['addressbookid'];
					$sqladdressbook = "select b.addressbookid, c.fullname as custname
														from paymenttarget a
														join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
														join addressbook c on c.addressbookid = b.addressbookid
														where a.recordstatus = {$maxstat} and perioddate = '{$month1}' and a.employeeid = ".$sales['employeeid']." and a.companyid = {$companyid}
														union
														select h.addressbookid, h.fullname as custname 
														from cutarinv a
														join cutar b on b.cutarid = a.cutarid
														join invoice c on c.invoiceid = a.invoiceid
														join giheader d on d.giheaderid = c.giheaderid
														join soheader e on e.soheaderid = d.soheaderid
														join ttnt f on f.ttntid = b.ttntid
														join employee g on g.employeeid = f.employeeid
														join addressbook h on h.addressbookid = e.addressbookid
														where b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and g.employeeid = ".$sales['employeeid']." and b.companyid = {$companyid} group by h.addressbookid ";
					$addressbook = Yii::app()->db->createCommand($sqladdressbook)->queryAll();
					
					foreach($addressbook as $row1)
					{   
							$sql2 = "select sum(target) as target, sum(realisasi) as realisasi,
													sum(kumlt14) as kumlt14,sum(kumlt0) as kumlt0,sum(kumx0) as kumx0,sum(kumgt0) as kumgt0,sum(kumgt7) as kumgt7,sum(kumgt15) as kumgt15,sum(kumgt30) as kumgt30,
													addressbookid,fullname
											from (select ifnull(sum(gt30+gt15+gt7+gt0+x0+lt0+lt14),0) as target, 
													 (select sum(a.cashamount+a.bankamount+a.obamount) as kumbayar
																	from cutarinv a
																	join cutar b on a.cutarid = b.cutarid
																	join invoice c on c.invoiceid = a.invoiceid
																	join giheader d on d.giheaderid = c.giheaderid
																	join soheader e on e.soheaderid = d.soheaderid
																	join ttnt i on i.ttntid = b.ttntid
																	join employee f on f.employeeid = i.employeeid
																	join addressbook g on g.addressbookid = e.addressbookid
																	join paymentmethod h on h.paymentmethodid = e.paymentmethodid
																	where f.employeeid = {$sales['employeeid']} and e.companyid = {$companyid}
																	and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and g.addressbookid = {$row1['addressbookid']}) as realisasi,
															0 as kumlt14,0 as kumlt0, 0 as kumx0, 0 as kumgt0, 0 as kumgt7, 0 as kumgt15, 0 as kumgt30, c.addressbookid,c.fullname, ''
													from paymenttarget a
													join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
													join addressbook c on c.addressbookid = b.addressbookid
													where b.addressbookid = {$row1['addressbookid']} and a.recordstatus = {$maxstat} and a.companyid = {$companyid}
													and a.employeeid = {$sales['employeeid']} and a.perioddate = '{$month1}'
													union
													select 0 target, 0 as realisasi,
															case when umurtop < -14 then sum(kumbayar) else 0 end as kumlt14,
															case when umurtop < 0 and umurtop >= -14 then sum(kumbayar) else 0 end as kumlt0,
															case when umurtop = 0 then sum(kumbayar) else 0 end as kumx0,
															case when umurtop > 0 and umurtop <= 7 then sum(kumbayar) else 0 end as kumgt0,
															case when umurtop > 7 and umurtop <= 15 then sum(kumbayar) else 0 end as kumgt7,
															case when umurtop > 15 and umurtop <= 30 then sum(kumbayar) else 0 end as kumgt15,
															case when umurtop > 30 then sum(kumbayar) else 0 end as kumgt30, addressbookid, fullname, invoiceno
													from (
															select c.invoiceno, a.invoiceid, g.fullname, datediff(b.docdate,date_add(c.invoicedate, interval h.paydays day)) as umurtop, a.cashamount+a.bankamount+a.obamount as kumbayar, g.addressbookid
															from cutarinv a
															join cutar b on a.cutarid = b.cutarid
															join invoice c on c.invoiceid = a.invoiceid
															join giheader d on d.giheaderid = c.giheaderid
															join soheader e on e.soheaderid = d.soheaderid
															join ttnt i on i.ttntid = b.ttntid
															join employee f on f.employeeid = i.employeeid
															join addressbook g on g.addressbookid = e.addressbookid
															join paymentmethod h on h.paymentmethodid = e.paymentmethodid
															where f.employeeid = {$sales['employeeid']} and e.companyid = {$companyid}
															and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and g.addressbookid = {$row1['addressbookid']}
													) z group by invoiceid) x";
							$res2 = Yii::app()->db->createCommand($sql2)->queryAll();
							foreach($res2 as $row3)
							{                    
									if($row3['target']==0)
									{
											$percent1 = 0;
									}
									else
									{
											$percent1 = $row3['realisasi']/$row3['target'];
									}
									
									$this->pdf->setFont('Arial','',8);
									$this->pdf->row(array($row1['custname'],
									Yii::app()->format->formatCurrency($row3['target']/$per),
									Yii::app()->format->formatCurrency($row3['realisasi']/$per),
									number_format($percent1*100,1,'.',',').' %',
									Yii::app()->format->formatCurrency($row3['kumgt30']/$per),
									Yii::app()->format->formatCurrency($row3['kumgt15']/$per),
									Yii::app()->format->formatCurrency($row3['kumgt7']/$per),
									Yii::app()->format->formatCurrency($row3['kumgt0']/$per),
									Yii::app()->format->formatCurrency($row3['kumx0']/$per),
									Yii::app()->format->formatCurrency($row3['kumlt0']/$per),
									Yii::app()->format->formatCurrency($row3['kumlt14']/$per),
									));
									
									$totaltarget = ($totaltarget + $row3['target']);
									$totalrealisasi = ($totalrealisasi + $row3['realisasi']);
									$totalgt30 = ($totalgt30 + $row3['kumgt30']);
									$totalgt15 = ($totalgt15 + $row3['kumgt15']);
									$totalgt7  = ($totalgt7 + $row3['kumgt7']);
									$totalgt0 = ($totalgt0 + $row3['kumgt0']);
									$totalx0 = ($totalx0 + $row3['kumx0']);
									$totallt0 = ($totallt0 + $row3['kumlt0']);
									$totallt14 = ($totallt14 + $row3['kumlt14']);
									
							}              
					}
                
                    $sqlminscale = "select minscale
                                from paymentscale t
                                where t.recordstatus = {$maxstat2} and t.perioddate = '{$month1}' 
                                and t.companyid = {$companyid}";
                    $minscale = Yii::app()->db->createCommand($sqlminscale)->queryScalar();
                
					$this->pdf->setFont('Arial','B',8);
					//$this->pdf->setwidths(array(90));
					if($totaltarget==0)
					{
							$percent = 0;
							$max = 0;
							$min = null;
					}
					else
					{
							$percent = ($totalrealisasi/$totaltarget)*100;
                            if($percent>=$minscale)
                            {
                                if($percent > 100)
                                {
                                        $max = null;
                                        $min = 100;
                                }
                                elseif($percent > 90 && $percent < 100)
                                {
                                        $max = 100;
                                        $min = 90;
                                }
                                elseif($percent > 80 && $percent < 90)
                                {
                                        $max = 90;
                                        $min = 80;
                                }
                                elseif($percent > 70 && $percent < 80)
                                {
                                        $max = 80;
                                        $min = 70;
                                }
                                else
                                {
                                        $max = 70;
                                        $min = null;
                                }
                            }
                            else
                            {
                                $min=0; $max=0;
                            }
					}
					
					$sqlgt30 = "select gt30 ";
					$sqlgt15 = "select gt15 ";
					$sqlgt7 = "select gt7 ";
					$sqlgt0 = "select gt0 ";
					$sqlx0 = "select x0 ";
					$sqllt0 = "select lt0 ";
					$sqllt14 = "select lt14 ";
					$from = "from paymentscale a
									 join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
									 where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid} ";
					if($min===null)
					{
							$where = " and b.max = {$max} and b.min is null";   
					}
					elseif($max===null)
					{
							$where = " and b.max is null and b.min = {$min}";
					}
					else
					{
							$where = " and b.min = {$min} and b.max = {$max} ";    
					}
					
					$komisigt30 = Yii::app()->db->createCommand($sqlgt30.$from.$where)->queryScalar()*$totalgt30;
					$komisigt15 = Yii::app()->db->createCommand($sqlgt15.$from.$where)->queryScalar()*$totalgt15;
					$komisigt7 = Yii::app()->db->createCommand($sqlgt7.$from.$where)->queryScalar()*$totalgt7;
					$komisigt0 = Yii::app()->db->createCommand($sqlgt0.$from.$where)->queryScalar()*$totalgt0;
					$komisix0 = Yii::app()->db->createCommand($sqlx0.$from.$where)->queryScalar()*$totalx0;
					$komisilt0 = Yii::app()->db->createCommand($sqllt0.$from.$where)->queryScalar()*$totallt0;
					$komisilt14 = Yii::app()->db->createCommand($sqllt14.$from.$where)->queryScalar()*$totallt14;
					
					$totalkomisi = $komisigt30 + $komisigt15 + $komisigt7 + $komisigt0 + $komisix0 + $komisilt0 + $komisilt14;
					
					$this->pdf->row(array('TOTAL SALES : '.$sales['fullname'],
									Yii::app()->format->formatCurrency($totaltarget/$per),
									Yii::app()->format->formatCurrency($totalrealisasi/$per),
									number_format($percent,0,'.',',').' %',
									Yii::app()->format->formatCurrency($totalgt30/$per),
									Yii::app()->format->formatCurrency($totalgt15/$per),
									Yii::app()->format->formatCurrency($totalgt7/$per),
									Yii::app()->format->formatCurrency($totalgt0/$per),
									Yii::app()->format->formatCurrency($totalx0/$per),
									Yii::app()->format->formatCurrency($totallt0/$per),
									Yii::app()->format->formatCurrency($totallt14/$per),
									Yii::app()->format->formatCurrency((($komisigt30)/100)/$per),
									Yii::app()->format->formatCurrency((($komisigt15)/100)/$per),
									Yii::app()->format->formatCurrency((($komisigt7)/100)/$per),
									Yii::app()->format->formatCurrency((($komisigt0)/100)/$per),
									Yii::app()->format->formatCurrency((($komisix0)/100)/$per),
									Yii::app()->format->formatCurrency((($komisilt0)/100)/$per),
									Yii::app()->format->formatCurrency((($komisilt14)/100)/$per),
									Yii::app()->format->formatCurrency(($totalkomisi/100)/$per),
									));
								 
					$this->pdf->row(array(''));
			}
			$this->pdf->Output();
			
	}*/
	//33
	public function RekapTargetTagihanPerDokumenBelumStatusMax($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql = "select distinct a.paymenttargetid,c.companyname,a.docdate,a.perioddate,b.fullname, a.statusname
						from paymenttarget a
                        join employee b on a.employeeid=b.employeeid
                        join company c on a.companyid=c.companyid
                       	where a.companyid = ".$companyid." and a.companyid = ".$companyid." and a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and a.recordstatus < getwfmaxstatbywfname('apppt')
                        and a.recordstatus <> 0 ";  
      
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
			foreach($dataReader as $row)
			{
				$this->pdf->companyid = $companyid;
			}
			$this->pdf->title='Rekap Target Tagihan Per Dokumen Belum Status Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');
			
			$this->pdf->setFont('Arial','B',8);
                        $this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->colalign = array('C','L','L','L','C','C','L');
			$this->pdf->setwidths(array(10,15,45,20,20,60,25));
			$this->pdf->colheader = array('No','ID','Perusahaan','Tanggal','Tgl Periode','Nama Sales','Status');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');		
			$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',7);
				$this->pdf->row(array(
					$i,$row['paymenttargetid'],$row['companyname'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['perioddate'])),$row['fullname'],$row['statusname']
				));
               
				$this->pdf->checkPageBreak(20);
			}
			
			$this->pdf->Output();
	}
	//34
	public function RekapSkalaKomisiTagihanPerDokumenBelumStatusMax($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$sql = "select distinct a.paymentscaleid,c.companyname,a.docdate,a.perioddate,a.paramspv, a.statusname
						from paymentscale a
                        join company c on a.companyid=c.companyid
                       	where a.companyid = ".$companyid." and a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and a.recordstatus < getwfmaxstatbywfname('appps')
                        and a.recordstatus <> 0 ";
												
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
			foreach($dataReader as $row)
			{
				$this->pdf->companyid = $companyid;
			}
			$this->pdf->title='Rekap Skala Komisi Tagihan Per Dokumen Belum Status Max';
			$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->AddPage('P');
			
			$this->pdf->setFont('Arial','B',8);
                        $this->pdf->sety($this->pdf->gety()+10);
			$this->pdf->colalign = array('L','L','L','L','L','L','L');
			$this->pdf->setwidths(array(10,20,50,20,20,20,30));
			$this->pdf->colheader = array('No','ID SKT','Perusahaan','Tanggal','Tgl Periode','Param','Status');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');		
			$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->pdf->setFont('Arial','',7);
				$this->pdf->row(array(
					$i,$row['paymentscaleid'],$row['companyname'],
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
					date(Yii::app()->params['dateviewfromdb'], strtotime($row['perioddate'])),$row['paramspv'],$row['statusname']
				));
               
				$this->pdf->checkPageBreak(20);
			}
			
			$this->pdf->Output();
	}
	//35
	public function RekapUmurPiutangDagangPerCustomerVsTop($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			$isdisplay1= " and c.isdisplay = ".$isdisplay." ";
		}
		$url = $_SERVER['REQUEST_URI'];
		$strpos1 = strpos($url,'salesarea');
		$strpos2 = strpos($url,'umurpiutang');

		$salesarea = substr($url,$strpos1+10,($strpos2-$strpos1-11));
		if ($umurpiutang != '')
		{
			$umur = " where umur > ".$umurpiutang." ";
		}
		else
		{
			$umur = '';
		}
		$sql1 = "select salesname, employeeid from (select salesname, employeeid
							from(select salesname, employeeid, amount, payamount
							from (select f.fullname as salesname, d.fullname,e.paydays,f.employeeid,datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."',
							date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
							date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."',a.invoicedate) as umur,a.amount,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
							from cutarinv f
							join cutar g on g.cutarid=f.cutarid
							where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'],strtotime($enddate))."'),0) as payamount
							from invoice a
							inner join giheader b on b.giheaderid = a.giheaderid
							inner join gidetail h on h.giheaderid = b.giheaderid
							inner join soheader c on c.soheaderid = b.soheaderid
							inner join addressbook d on d.addressbookid = c.addressbookid
							inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
							inner join employee f on f.employeeid = c.employeeid
							inner join sloc g on g.slocid = h.slocid
							inner join salesarea i on i.salesareaid = d.salesareaid
							where d.fullname like '%{$customer}%' and f.fullname like '%{$sales}%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid}
							and g.sloccode like '%{$sloc}%'
							and i.areaname like '%{$salesarea}%' ".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'],strtotime($enddate))."')z {$umur})zz 
							where amount > payamount)zzz group by salesname
		";
		$dataReader=Yii::app()->db->createCommand($sql1)->queryAll();
		
		$this->pdf->companyid = $companyid;
		
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			if($isdisplay == "1")
			{
				$this->pdf->title='Rekap Umur Piutang Dagang Per Customer VS TOP (HANYA DISPLAY)';
			}
			else if($isdisplay == "0")
			{
				$this->pdf->title='Rekap Umur Piutang Dagang Per Customer VS TOP (BUKAN DISPLAY)';
			}
		}
		else
		{
			$this->pdf->title='Rekap Umur Piutang Dagang Per Customer VS TOP';
		}
		//$this->pdf->title='Rekap Umur Piutang Dagang Per Customer VS TOP';
		$this->pdf->subtitle='Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L');
		
		$this->pdf->setFont('Arial','B',10);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->text(15,20,'X = TOP');
					foreach($dataReader as $row)
					{
							$this->pdf->setFont('Arial','B',9);
							$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C');
							$this->pdf->setwidths(array(10,40,28,28,28,28,28,28,28,35));
							$this->pdf->text($this->pdf->getX(),$this->pdf->getY(),'Sales : '.$row['salesname']);
							$this->pdf->setX($this->pdf->getX()+5);
							$this->pdf->setY($this->pdf->getY()+2);
							$this->pdf->setFont('Arial','B',8);
							$this->pdf->colheader = array('No','Nama Customer','X < -14 Hari','-14 <= X < 0 Hari','X=0 Hari','0 < X <= 7 Hari','7 < X <= 15 Hari','15 < X <= 30 Hari','X > 30 Hari','Total');
							$this->pdf->RowHeader();
							$this->pdf->coldetailalign = array('L','L','R','R','R','R','R','R','R','R');
							
							$sql2 = "select *,sum(lt14) as lt14, sum(lt0) as lt0,sum(x0) as x0, sum(gt0) as gt0, sum(gt7) as gt7, sum(gt15) as gt15, sum(gt30) as gt30
			from (select *, 
			case when umurtempo < -14 then totamount else 0 end as lt14,
			case when umurtempo >= -14 and umurtempo < 0 then totamount else 0 end as lt0,
							case when umurtempo = 0 then totamount else 0 end as x0,
			case when umurtempo > 0 and umurtempo <= 7 then totamount else 0 end as gt0,
			case when umurtempo > 7 and umurtempo <= 15 then totamount else 0 end as gt7,
			case when umurtempo > 15 and umurtempo <= 30 then totamount else 0 end as gt15,
			case when umurtempo > 30 then totamount else 0 end as gt30
			from(select *,amount - payamount as totamount
			from (select d.fullname,e.paydays,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',
			date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
			date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
			datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,
			ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
			from cutarinv f
			join cutar g on g.cutarid=f.cutarid
			where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
			from invoice a
			inner join giheader b on b.giheaderid = a.giheaderid
							-- inner join gidetail h on h.giheaderid = b.giheaderid
			inner join soheader c on c.soheaderid = b.soheaderid
			inner join addressbook d on d.addressbookid = c.addressbookid
			inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
			inner join employee f on f.employeeid = c.employeeid
							inner join salesarea i on i.salesareaid = d.salesareaid
							-- inner join sloc g on g.slocid = h.slocid
			where d.fullname like '%".$customer."%' and f.employeeid = {$row['employeeid']} and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 	
							-- and g.sloccode like '%{$sloc}%'
							and i.areaname like '%{$salesarea}%' ".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'],strtotime($enddate))."')z {$umur})zz 
			where amount > payamount)zzz
			group by fullname
			order by fullname";
							
							$totallt14 = 0;
							$totallt0 = 0;
							$totalx0 = 0;
							$totalgt0 = 0;
							$totalgt7 = 0;
							$totalgt15 = 0;
							$totalgt30 = 0;
							$total = 0;$i=0;
							
							$dataReader1 = Yii::app()->db->createCommand($sql2)->queryAll();
							foreach($dataReader1 as $row1)
							{
									$i+=1;
									$this->pdf->setFont('Arial','',8);
									$this->pdf->row(array(
											$i,$row1['fullname'],
																	Yii::app()->format->formatCurrency($row1['lt14']/$per),
																	Yii::app()->format->formatCurrency($row1['lt0']/$per),
																	Yii::app()->format->formatCurrency($row1['x0']/$per),
																	Yii::app()->format->formatCurrency($row1['gt0']/$per),
																	Yii::app()->format->formatCurrency($row1['gt7']/$per),
																	Yii::app()->format->formatCurrency($row1['gt15']/$per),
																	Yii::app()->format->formatCurrency($row1['gt30']/$per),
																	Yii::app()->format->formatCurrency(($row1['lt14']+$row1['lt0']+$row1['x0']+$row1['gt0']+$row1['gt7']+$row1['gt15']+$row1['gt30'])/$per)));
									
											
													$totallt14 += $row1['lt14']/$per;
													$totallt0 += $row1['lt0']/$per;
													$totalx0 += $row1['x0']/$per;
													$totalgt0 += $row1['gt0']/$per;
													$totalgt7 += $row1['gt7']/$per;
													$totalgt15 += $row1['gt15']/$per;
													$totalgt30 += $row1['gt30']/$per;
													$total += ($row1['lt14']+$row1['lt0']+$row1['x0']+$row1['gt0']+$row1['gt7']+$row1['gt15']+$row1['gt30'])/$per;
													
							}
													$this->pdf->sety($this->pdf->gety()+5);
								 
													$this->pdf->setFont('Arial','B',9);
													$this->pdf->row(array(
																	'','Total :',
											Yii::app()->format->formatCurrency($totallt14),
													Yii::app()->format->formatCurrency($totallt0),
													Yii::app()->format->formatCurrency($totalx0),
													Yii::app()->format->formatCurrency($totalgt0),
													Yii::app()->format->formatCurrency($totalgt7),
													Yii::app()->format->formatCurrency($totalgt15),
													Yii::app()->format->formatCurrency($totalgt30),
													Yii::app()->format->formatCurrency($total)
													));
							$this->pdf->sety($this->pdf->gety()+5);
							$this->pdf->checkPageBreak(20);
					}
		$this->pdf->Output();
	}
	//36
	public function RekapMonitoringPiutangPerCustomerPerSales($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		$this->pdf->title = 'Monitoring Penyelesaian Piutang Khusus';
		$this->pdf->subtitle = 'Piutang Diatas = '.$umurpiutang. ' Hari  /  Periode : '.date('F Y',strtotime($enddate));
		$this->pdf->addPage('L','F4');
		//$this->pdf->row('Piutang Diatas = ');
		//$this->pdf->text(10,20,'Piutang Diatas = '. ' hari');
		
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->sety($this->pdf->gety()+5);    
		
		$this->pdf->setAutoPageBreak(false);
		$height_of_cell = 18.5; // mm
		$page_height = 210; // mm (portrait letter)
		$bottom_margin = 0; // mm
		
        $totalsaldoawal=0;
        $totalsaldoakhir=0;
        $totalwa1=0;
        $totalwa2=0;
        $totalwa3=0;
        $totalwa4=0;
        $totalpa1=0;
        $totalpa2=0;
        $totalpa3=0;
        $totalpa4=0;
                	 
		$month = date('m',strtotime($enddate));
		$year = date('Y',strtotime($enddate));
		$month1 = date('Y-m-d',strtotime(''.$year.'-'.$month.'-01'));
		//$month2 = date('Y-')
		
		if($umurpiutang=='')
		{
				$umur=0;
		}
		else
		{
				$umur=$umurpiutang;
		}
		
		if($umur>0){
			$month2 = date('Y-m-d',strtotime($month1));
			$sql1 = "select distinct addressbookid,fullname as customername
							from (select d.addressbookid,d.fullname,a.amount,datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."',a.invoicedate) as umur,a.invoiceno,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
							from cutarinv f
							join cutar g on g.cutarid=f.cutarid
							where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= last_day(date_add('".date(Yii::app()->params['datetodb'],strtotime($enddate))."', interval -1 month))),0) as payamount, e.fullname as salesname
							from invoice a
							join giheader b on b.giheaderid = a.giheaderid
							join soheader c on c.soheaderid = b.soheaderid
							join addressbook d on d.addressbookid = c.addressbookid
							join employee e on e.employeeid = c.employeeid
							where e.fullname like '%{$sales}%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid}
							and d.fullname like '%{$customer}%' 
							and a.invoicedate <= last_day('".date(Yii::app()->params['datetodb'],strtotime($enddate))."')) z
							where amount > payamount and umur > {$umur}
							order by customername
							";
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
		 
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->coldetailalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setbordercell(array('TL','TL','TL','TL','TL','TL','TL','TL','TLR'));
			$this->pdf->setwidths(array(8,45,40,25,44,44,44,44,23));
			$this->pdf->row(array('No','Nama Toko','Nama Sales','Saldo','1 - 7','8 - 14','15 - 21','22 - 31','Saldo'));
			$this->pdf->setwidths(array(8,45,40,25,22,22,22,22,22,22,22,22,23));
			$this->pdf->setbordercell(array('LB','LB','LB','LB','TLB','TLB','TLB','TLB','TLB','TLB','TLB','TLB','LBR'));
			$this->pdf->coldetailalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->row(array('','','','Awal','Penambahan','Pengurangan','Penambahan','Pengurangan','Penambahan','Pengurangan','Penambahan','Pengurangan','Akhir'));
			$this->pdf->setFont('Arial','',8);
		 
			$i=1;
			$this->pdf->setbordercell(array('','','','','','','','','','','','',''));
			$this->pdf->coldetailalign = array('L','L','L','R','R','R','R','R','R','R','R','R','R');
			foreach($dataReader1 as $row1)
			{
					//$this->pdf->row(array($i,$row1['customername'],$row1['salesname']));
					$sql2 = "select a.invoicedate, c.employeeid, d.fullname as salesname, e.fullname as customername, (select ifnull(sum(amount),0)
							from invoice x
							join giheader y on y.giheaderid = x.giheaderid
							join soheader z on z.soheaderid = y.soheaderid
							where datediff('{$month1}',x.invoicedate) > {$umur}
							and x.recordstatus = 3 and x.invoiceno is not null and x.invoiceid = a.invoiceid) as saldo
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join employee d on d.employeeid = c.employeeid
					join addressbook e on e.addressbookid = c.addressbookid
					where a.companyid = {$companyid} and c.addressbookid = {$row1['addressbookid']}
					-- and datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."', a.invoicedate) > {$umur}
					and datediff(last_day('{$month1}'), a.invoicedate) > {$umur}
					and a.amount > (select ifnull(sum((ifnull(x.cashamount,0)+ifnull(x.bankamount,0)+ifnull(x.discamount,0)+ifnull(x.returnamount,0)+ifnull(x.obamount,0))*ifnull(x.currencyrate,0)),0)
					from cutarinv x
					join cutar y on x.cutarid = y.cutarid
					where y.recordstatus=3 and x.invoiceid = a.invoiceid and y.docdate<=date_add('{$month1}',interval -1 day)) and a.recordstatus = 3 and a.invoiceno is not null 
					group by customername,salesname";
					
					$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
					foreach($dataReader2 as $row2)
					{
							$sqlsaldoawal = "select sum(amount)-sum(payamount) from (select a.amount, datediff(date_add('{$month1}',interval -1 day),a.invoicedate) as umur,
							a.invoiceno, ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
									from cutarinv f
									join cutar g on g.cutarid=f.cutarid
									where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= date_add('{$month1}', interval -1 day)),0) as payamount
							from invoice a
							join giheader b on b.giheaderid = a.giheaderid
							join soheader c on c.soheaderid = b.soheaderid
							join addressbook d on d.addressbookid = c.addressbookid
							join employee e on e.employeeid = c.employeeid
							where a.recordstatus=3 and a.invoiceno is not null and c.companyid = 1
							and d.addressbookid = {$row1['addressbookid']} and e.employeeid = {$row2['employeeid']}
							and a.invoicedate <= date_add('{$month1}',interval -1 day))  z
							where amount > payamount and umur > {$umur}";
							
							$saldoawal = Yii::app()->db->createCommand($sqlsaldoawal)->queryScalar();
							
							$sqlamount="select sum(week1)-sum(pay) as week1, sum(week2)-sum(pay) as week2, sum(week3)-sum(pay) as week3, sum(week4)-sum(pay) as week4 from (select d.employeeid,
											(select     ifnull(sum((ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0))*ifnull(za.currencyrate,0)),0)
											from cutarinv za
											join cutar zb on zb.cutarid = za.cutarid
											join invoice zc on zc.invoiceid = za.invoiceid
											where zc.invoiceid = a.invoiceid
											and zb.docdate < '{$month1}' 
											and datediff(date_add('{$month1}', interval -1 day),zc.invoicedate) > {$umur}
                                            and zb.recordstatus=3
											) as pay,
											(select 
											sum(z.amount)
											-- ifnull(sum(ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0)),0)
											-- from cutarinv za
											-- join cutar zb on zb.cutarid = za.cutarid
											-- join invoice z on z.invoiceid = za.invoiceid
											from invoice z 
											where z.invoiceno is not null and z.recordstatus=3 and z.invoiceid = a.invoiceid
											and datediff(date_add('{$month1}',interval 6 day), z.invoicedate) > {$umur}
											-- and zb.docdate < '{$month1}' and zb.recordstatus=3
											) as week1,
											(select 
											sum(z.amount)
											-- ifnull(sum(ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0)),0)
											-- from cutarinv za
											-- join cutar zb on zb.cutarid = za.cutarid
											-- join invoice z on z.invoiceid = za.invoiceid
											from invoice z 
											where z.invoiceno is not null and z.recordstatus=3 and z.invoiceid = a.invoiceid
											and datediff(date_add('{$month1}',interval 13 day), z.invoicedate) > {$umur}
											-- and zb.docdate < '{$month1}' and zb.recordstatus=3
											) 
											as week2,
											(select 
											sum(z.amount)
											-- ifnull(sum(ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0)),0)
											-- from cutarinv za
											-- join cutar zb on zb.cutarid = za.cutarid
											-- join invoice z on z.invoiceid = za.invoiceid
											from invoice z 
											where z.invoiceno is not null and z.recordstatus=3 and z.invoiceid = a.invoiceid
											and datediff(date_add('{$month1}',interval 20 day), z.invoicedate) > {$umur}
											-- and zb.docdate < '{$month1}' and zb.recordstatus=3
											)
											as week3,
											(select 
											sum(z.amount)
											-- ifnull(sum(ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0)),0)
											-- from cutarinv za
											-- join cutar zb on zb.cutarid = za.cutarid
											-- join invoice z on z.invoiceid = za.invoiceid
											from invoice z 
											where z.invoiceno is not null and z.recordstatus=3 and z.invoiceid = a.invoiceid
											and datediff(last_day('{$month1}'), z.invoicedate) > {$umur}
											-- and zb.docdate < '{$month1}' and zb.recordstatus=3
											)
											as week4
							from invoice a
							join giheader b on b.giheaderid = a.giheaderid
							join soheader c on c.soheaderid = b.soheaderid
							join employee d on d.employeeid = c.employeeid
							join addressbook e on e.addressbookid = c.addressbookid
							where a.companyid = {$companyid}
							and c.addressbookid = {$row1['addressbookid']}
							and c.employeeid = {$row2['employeeid']}
							and (datediff(last_day('{$month1}'),a.invoicedate) > {$umur})
							and a.amount > (select ifnull(sum((ifnull(x.cashamount,0)+ifnull(x.bankamount,0)+ifnull(x.discamount,0)+ifnull(x.returnamount,0)+ifnull(x.obamount,0))*ifnull(x.currencyrate,0)),0)
							from cutarinv x
							join cutar y on x.cutarid = y.cutarid
							where y.recordstatus=3 and x.invoiceid = a.invoiceid and y.docdate<=date_add('{$month1}',interval -1 day)) and a.recordstatus = 3 and a.invoiceno is not null
							-- group by employeeid
							) zz group by employeeid ";
							$weekamount = Yii::app()->db->createCommand($sqlamount)->queryRow();
							
							$sqlpayamount = "select 
							sum(pay1) as week1, sum(pay2) as week2, sum(pay3) as week3, sum(pay4) as week4 
							from (
									select y.docdate, z.invoiceid, ifnull((select sum(ifnull(a.cashamount,0)+ifnull(a.bankamount,0)+ifnull(a.discamount,0)+ifnull(a.returnamount,0)+ifnull(a.obamount,0))
									from cutarinv a
									join cutar b on b.cutarid = a.cutarid
									where a.invoiceid = z.invoiceid and b.recordstatus=3 and b.cutarno is not null and b.docdate between '{$month1}' and date_add('{$month1}',interval 6 day)),0) as pay1,
									ifnull((select sum(ifnull(a.cashamount,0)+ifnull(a.bankamount,0)+ifnull(a.discamount,0)+ifnull(a.returnamount,0)+ifnull(a.obamount,0))
									from cutarinv a
									join cutar b on b.cutarid = a.cutarid
									where a.invoiceid = z.invoiceid and b.recordstatus=3 and b.cutarno is not null and b.docdate between date_add('{$month1}',interval 7 day) and date_add('{$month1}', interval 13 day)),0) as pay2,
								 ifnull((select sum(ifnull(a.cashamount,0)+ifnull(a.bankamount,0)+ifnull(a.discamount,0)+ifnull(a.returnamount,0)+ifnull(a.obamount,0))
									from cutarinv a
									join cutar b on b.cutarid = a.cutarid
									where a.invoiceid = z.invoiceid and b.recordstatus=3 and b.cutarno is not null and b.docdate between date_add('{$month1}',interval 14 day) and date_add('{$month1}', interval 20 day)),0) as pay3,
									ifnull((select sum(ifnull(a.cashamount,0)+ifnull(a.bankamount,0)+ifnull(a.discamount,0)+ifnull(a.returnamount,0)+ifnull(a.obamount,0))
									from cutarinv a
									join cutar b on b.cutarid = a.cutarid
									where a.invoiceid = z.invoiceid and b.recordstatus=3 and b.cutarno is not null and b.docdate between date_add('{$month1}',interval 21 day) and last_day('{$month1}')),0) as pay4
							from cutarinv x
							join cutar y on x.cutarid = y.cutarid
							join invoice z on z.invoiceid = x.invoiceid
							join giheader za on za.giheaderid = z.giheaderid
							join soheader zb on zb.soheaderid = za.soheaderid
							where y.recordstatus=3 
							and zb.addressbookid = {$row1['addressbookid']}
							and zb.employeeid= {$row2['employeeid']}
							and y.docdate between '{$month1}' and last_day('{$month1}')
							and datediff(last_day('{$month1}'),z.invoicedate) > {$umur}
							and zb.companyid= {$companyid}) zz ";
							$weekpayamount = Yii::app()->db->createCommand($sqlpayamount)->queryRow();
															
							$totalpayamount = ($weekpayamount['week1']+$weekpayamount['week2']+$weekpayamount['week3']+$weekpayamount['week4']);
							
							$totalamount = (($weekamount['week1']-$saldoawal)+($weekamount['week2']-$weekamount['week1'])+($weekamount['week3']-$weekamount['week2'])+($weekamount['week4']-$weekamount['week3']));
							
							$saldoakhir = ($saldoawal+$totalamount)-$totalpayamount;
							
							$this->pdf->setFont('Arial','',8);
							$this->pdf->row(array($i,$row2['customername'],$row2['salesname'],
                                            Yii::app()->format->formatCurrency(($saldoawal)/$per),
                                            Yii::app()->format->formatCurrency(($weekamount['week1']-$saldoawal)/$per),
                                            Yii::app()->format->formatCurrency(($weekpayamount['week1'])/$per),
                                            Yii::app()->format->formatCurrency(($weekamount['week2']-$weekamount['week1'])/$per),
                                            Yii::app()->format->formatCurrency(($weekpayamount['week2'])/$per),
                                            Yii::app()->format->formatCurrency(($weekamount['week3']- $weekamount['week2'])/$per),
                                            Yii::app()->format->formatCurrency(($weekpayamount['week3'])/$per),
                                            Yii::app()->format->formatCurrency(($weekamount['week4']- $weekamount['week3'])/$per),
                                            Yii::app()->format->formatCurrency(($weekpayamount['week4'])/$per),
                                            Yii::app()->format->formatCurrency(($saldoakhir)/$per)
                                     ));
                        
                            $totalsaldoawal = $totalsaldoawal + $saldoawal;
                            $totalwa1 = $totalwa1 + ($weekamount['week1']-$saldoawal);
                            $totalwa2 = $totalwa2 + ($weekamount['week2']-$weekamount['week1']);
                            $totalwa3 = $totalwa3 + ($weekamount['week3']-$weekamount['week2']);
                            $totalwa4 = $totalwa4 + ($weekamount['week4']-$weekamount['week3']);
                            $totalpa1 = $totalpa1 + ($weekpayamount['week1']);
                            $totalpa2 = $totalpa2 + ($weekpayamount['week2']);
                            $totalpa3 = $totalpa3 + ($weekpayamount['week3']);
                            $totalpa4 = $totalpa4 + ($weekpayamount['week4']);
                            $totalsaldoakhir = $totalsaldoakhir + $saldoakhir;
                        
				            $i++;
                            $space_left=$page_height-($this->pdf->getY()+$bottom_margin);
							if ($height_of_cell > $space_left) {
									$this->pdf->addPage('L','F4');
									$this->pdf->setFont('Arial','B',8);
									$this->pdf->coldetailalign = array('C','C','C','C','C','C','C','C','C');
									$this->pdf->setbordercell(array('TL','TL','TL','TL','TL','TL','TL','TL','TLR'));
									$this->pdf->setwidths(array(8,45,40,25,44,44,44,44,23));
									$this->pdf->row(array('No','Nama Toko','Nama Sales','Saldo','1 - 7','8 - 14','15 - 21','22 - 31','Saldo'));
									$this->pdf->setwidths(array(8,45,40,25,22,22,22,22,22,22,22,22,23));
									$this->pdf->setbordercell(array('LB','LB','LB','LB','TLB','TLB','TLB','TLB','TLB','TLB','TLB','TLB','LBR'));
									$this->pdf->coldetailalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
									$this->pdf->row(array('','','','Awal','Penambahan','Pengurangan','Penambahan','Pengurangan','Penambahan','Pengurangan','Penambahan','Pengurangan','Akhir'));
									$this->pdf->setFont('Arial','',8);
									
									$this->pdf->setbordercell(array('','','','','','','','','','','','',''));
									$this->pdf->coldetailalign = array('L','L','L','R','R','R','R','R','R','R','R','R','R');
							}
					}
			}
    }else{
        $this->pdf->SetFont('helvetica','B',20);
        $this->pdf->text(20, 88, 'Anda Harus Mengisi Umur Piutang');
    }
        $this->pdf->setY($this->pdf->getY()+5);
        $this->pdf->setFont('Arial','B',9);
        $this->pdf->row(array('','','GRAND TOTAL',																		Yii::app()->format->formatCurrency(($totalsaldoawal)/$per),
								    Yii::app()->format->formatCurrency(($totalwa1)/$per),
									Yii::app()->format->formatCurrency(($totalpa1)/$per),
									Yii::app()->format->formatCurrency(($totalwa2)/$per),
									Yii::app()->format->formatCurrency(($totalpa2)/$per),
									Yii::app()->format->formatCurrency(($totalwa3)/$per),
									Yii::app()->format->formatCurrency(($totalpa3)/$per),
									Yii::app()->format->formatCurrency(($totalwa4)/$per),
									Yii::app()->format->formatCurrency(($totalpa4)/$per),
									Yii::app()->format->formatCurrency(($totalsaldoakhir)/$per)
                    ));
		$this->pdf->Output();
	}
	/*{
		parent::actionDownload();
		$this->pdf->title = 'Monitoring Penyelesaian Piutang Khusus';
		$this->pdf->subtitle = 'Piutang Diatas = '.$umurpiutang. ' Hari  /  Periode : '.date('F Y',strtotime($enddate));
		$this->pdf->addPage('L','F4');
		//$this->pdf->row('Piutang Diatas = ');
		//$this->pdf->text(10,20,'Piutang Diatas = '. ' hari');
		
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->sety($this->pdf->gety()+5);    
		
		$this->pdf->setAutoPageBreak(false);
		$height_of_cell = 18.5; // mm
		$page_height = 210; // mm (portrait letter)
		$bottom_margin = 0; // mm
		
	 
		$month = date('m',strtotime($enddate));
		$year = date('Y',strtotime($enddate));
		$month1 = date('Y-m-d',strtotime(''.$year.'-'.$month.'-01'));
		//$month2 = date('Y-')
		
		if($umurpiutang=='')
		{
				$umur=0;
		}
		else
		{
				$umur=$umurpiutang;
		}
		
		if($umur>0){
			$month2 = date('Y-m-d',strtotime($month1));
			$sql1 = "select distinct addressbookid,fullname as customername
							from (select d.addressbookid,d.fullname,a.amount,datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."',a.invoicedate) as umur,a.invoiceno,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
							from cutarinv f
							join cutar g on g.cutarid=f.cutarid
							where g.recordstatus=getwfmaxstatbywfname('appcutar') and f.invoiceid=a.invoiceid and g.docdate <= last_day(date_add('".date(Yii::app()->params['datetodb'],strtotime($enddate))."', interval -1 month))),0) as payamount, e.fullname as salesname
							from invoice a
							join giheader b on b.giheaderid = a.giheaderid
							join soheader c on c.soheaderid = b.soheaderid
							join addressbook d on d.addressbookid = c.addressbookid
							join employee e on e.employeeid = c.employeeid
							where e.fullname like '%{$sales}%' and a.recordstatus=getwfmaxstatbywfname('appinvar') and a.invoiceno is not null and c.companyid = {$companyid}
							and d.fullname like '%{$customer}%' 
							and a.invoicedate <= last_day('".date(Yii::app()->params['datetodb'],strtotime($enddate))."')) z
							where amount > payamount and umur > {$umur}
							order by customername
							";
			$dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
		 
			$this->pdf->setFont('Arial','B',8);
			$this->pdf->coldetailalign = array('C','C','C','C','C','C','C','C','C');
			$this->pdf->setbordercell(array('TL','TL','TL','TL','TL','TL','TL','TL','TLR'));
			$this->pdf->setwidths(array(8,45,40,25,44,44,44,44,23));
			$this->pdf->row(array('No','Nama Toko','Nama Sales','Saldo','1 - 7','8 - 14','15 - 21','22 - 31','Saldo'));
			$this->pdf->setwidths(array(8,45,40,25,22,22,22,22,22,22,22,22,23));
			$this->pdf->setbordercell(array('LB','LB','LB','LB','TLB','TLB','TLB','TLB','TLB','TLB','TLB','TLB','LBR'));
			$this->pdf->coldetailalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
			$this->pdf->row(array('','','','Awal','Penambahan','Pengurangan','Penambahan','Pengurangan','Penambahan','Pengurangan','Penambahan','Pengurangan','Akhir'));
			$this->pdf->setFont('Arial','',8);
		 
			$i=1;
			$this->pdf->setbordercell(array('','','','','','','','','','','','',''));
			$this->pdf->coldetailalign = array('L','L','L','R','R','R','R','R','R','R','R','R','R');
			foreach($dataReader1 as $row1)
			{
					//$this->pdf->row(array($i,$row1['customername'],$row1['salesname']));
					$sql2 = "select a.invoicedate, c.employeeid, d.fullname as salesname, e.fullname as customername, (select ifnull(sum(amount),0)
							from invoice x
							join giheader y on y.giheaderid = x.giheaderid
							join soheader z on z.soheaderid = y.soheaderid
							where datediff('{$month1}',x.invoicedate) > {$umur}
							and x.recordstatus = getwfmaxstatbywfname('appinvar') and x.invoiceno is not null and x.invoiceid = a.invoiceid) as saldo
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join employee d on d.employeeid = c.employeeid
					join addressbook e on e.addressbookid = c.addressbookid
					where a.companyid = {$companyid} and c.addressbookid = {$row1['addressbookid']}
					-- and datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."', a.invoicedate) > {$umur}
					and datediff(last_day('{$month1}'), a.invoicedate) > {$umur}
					and a.amount > (select ifnull(sum((ifnull(x.cashamount,0)+ifnull(x.bankamount,0)+ifnull(x.discamount,0)+ifnull(x.returnamount,0)+ifnull(x.obamount,0))*ifnull(x.currencyrate,0)),0)
					from cutarinv x
					join cutar y on x.cutarid = y.cutarid
					where y.recordstatus=getwfmaxstatbywfname('appcutar') and x.invoiceid = a.invoiceid and y.docdate<=date_add('{$month1}',interval -1 day)) and a.recordstatus = getwfmaxstatbywfname('appinvar') and a.invoiceno is not null 
					group by customername,salesname";
					
					$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
					foreach($dataReader2 as $row2)
					{
							$sqlsaldoawal = "select sum(amount)-sum(payamount) from (select a.amount, datediff(date_add('{$month1}',interval -1 day),a.invoicedate) as umur,
							a.invoiceno, ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
									from cutarinv f
									join cutar g on g.cutarid=f.cutarid
									where g.recordstatus=getwfmaxstatbywfname('appcutar') and f.invoiceid=a.invoiceid and g.docdate <= date_add('{$month1}', interval -1 day)),0) as payamount
							from invoice a
							join giheader b on b.giheaderid = a.giheaderid
							join soheader c on c.soheaderid = b.soheaderid
							join addressbook d on d.addressbookid = c.addressbookid
							join employee e on e.employeeid = c.employeeid
							where a.recordstatus=getwfmaxstatbywfname('appinvar') and a.invoiceno is not null and c.companyid = 1
							and d.addressbookid = {$row1['addressbookid']} and e.employeeid = {$row2['employeeid']}
							and a.invoicedate <= date_add('{$month1}',interval -1 day))  z
							where amount > payamount and umur > {$umur}";
							
							$saldoawal = Yii::app()->db->createCommand($sqlsaldoawal)->queryScalar();
							
							$sqlamount="select sum(week1)-sum(pay) as week1, sum(week2)-sum(pay) as week2, sum(week3)-sum(pay) as week3, sum(week4)-sum(pay) as week4 from (select d.employeeid,
											(select     ifnull(sum((ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0))*ifnull(za.currencyrate,0)),0)
											from cutarinv za
											join cutar zb on zb.cutarid = za.cutarid
											join invoice zc on zc.invoiceid = za.invoiceid
											where zc.invoiceid = a.invoiceid
											and zb.docdate < '{$month1}' 
											and zb.recordstatus = getwfmaxstatbywfname('appcutar')
											and datediff(date_add('{$month1}', interval -1 day),zc.invoicedate) > {$umur}
											) as pay,
											(select 
											sum(z.amount)
											-- ifnull(sum(ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0)),0)
											-- from cutarinv za
											-- join cutar zb on zb.cutarid = za.cutarid
											-- join invoice z on z.invoiceid = za.invoiceid
											from invoice z 
											where z.invoiceno is not null and z.recordstatus=getwfmaxstatbywfname('appinvar') and z.invoiceid = a.invoiceid
											and datediff(date_add('{$month1}',interval 6 day), z.invoicedate) > {$umur}
											-- and zb.docdate < '{$month1}' and zb.recordstatus=3
											) as week1,
											(select 
											sum(z.amount)
											-- ifnull(sum(ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0)),0)
											-- from cutarinv za
											-- join cutar zb on zb.cutarid = za.cutarid
											-- join invoice z on z.invoiceid = za.invoiceid
											from invoice z 
											where z.invoiceno is not null and z.recordstatus=getwfmaxstatbywfname('appinvar') and z.invoiceid = a.invoiceid
											and datediff(date_add('{$month1}',interval 13 day), z.invoicedate) > {$umur}
											-- and zb.docdate < '{$month1}' and zb.recordstatus=3
											) 
											as week2,
											(select 
											sum(z.amount)
											-- ifnull(sum(ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0)),0)
											-- from cutarinv za
											-- join cutar zb on zb.cutarid = za.cutarid
											-- join invoice z on z.invoiceid = za.invoiceid
											from invoice z 
											where z.invoiceno is not null and z.recordstatus=getwfmaxstatbywfname('appinvar') and z.invoiceid = a.invoiceid
											and datediff(date_add('{$month1}',interval 20 day), z.invoicedate) > {$umur}
											-- and zb.docdate < '{$month1}' and zb.recordstatus=3
											)
											as week3,
											(select 
											sum(z.amount)
											-- ifnull(sum(ifnull(za.cashamount,0)+ifnull(za.bankamount,0)+ifnull(za.discamount,0)+ifnull(za.returnamount,0)+ifnull(za.obamount,0)),0)
											-- from cutarinv za
											-- join cutar zb on zb.cutarid = za.cutarid
											-- join invoice z on z.invoiceid = za.invoiceid
											from invoice z 
											where z.invoiceno is not null and z.recordstatus=getwfmaxstatbywfname('appinvar') and z.invoiceid = a.invoiceid
											and datediff(last_day('{$month1}'), z.invoicedate) > {$umur}
											-- and zb.docdate < '{$month1}' and zb.recordstatus=3
											)
											as week4
							from invoice a
							join giheader b on b.giheaderid = a.giheaderid
							join soheader c on c.soheaderid = b.soheaderid
							join employee d on d.employeeid = c.employeeid
							join addressbook e on e.addressbookid = c.addressbookid
							where a.companyid = {$companyid}
							and c.addressbookid = {$row1['addressbookid']}
							and c.employeeid = {$row2['employeeid']}
							and (datediff(last_day('{$month1}'),a.invoicedate) > {$umur})
							and a.amount > (select ifnull(sum((ifnull(x.cashamount,0)+ifnull(x.bankamount,0)+ifnull(x.discamount,0)+ifnull(x.returnamount,0)+ifnull(x.obamount,0))*ifnull(x.currencyrate,0)),0)
							from cutarinv x
							join cutar y on x.cutarid = y.cutarid
							where y.recordstatus = getwfmaxstatbywfname('appcutar') and x.invoiceid = a.invoiceid and y.docdate<=date_add('{$month1}',interval -1 day)) and a.recordstatus = getwfmaxstatbywfname('appinvar') and a.invoiceno is not null
							-- group by employeeid
							) zz group by employeeid ";
							$weekamount = Yii::app()->db->createCommand($sqlamount)->queryRow();
							
							$sqlpayamount = "select 
							sum(pay1) as week1, sum(pay2) as week2, sum(pay3) as week3, sum(pay4) as week4 
							from (
									select y.docdate, z.invoiceid, ifnull((select sum(ifnull(a.cashamount,0)+ifnull(a.bankamount,0)+ifnull(a.discamount,0)+ifnull(a.returnamount,0)+ifnull(a.obamount,0))
									from cutarinv a
									join cutar b on b.cutarid = a.cutarid
									where a.invoiceid = z.invoiceid and b.recordstatus=getwfmaxstatbywfname('appcutar') and b.cutarno is not null and b.docdate between '{$month1}' and date_add('{$month1}',interval 6 day)),0) as pay1,
									ifnull((select sum(ifnull(a.cashamount,0)+ifnull(a.bankamount,0)+ifnull(a.discamount,0)+ifnull(a.returnamount,0)+ifnull(a.obamount,0))
									from cutarinv a
									join cutar b on b.cutarid = a.cutarid
									where a.invoiceid = z.invoiceid and b.recordstatus=getwfmaxstatbywfname('appcutar') and b.cutarno is not null and b.docdate between date_add('{$month1}',interval 7 day) and date_add('{$month1}', interval 13 day)),0) as pay2,
								 ifnull((select sum(ifnull(a.cashamount,0)+ifnull(a.bankamount,0)+ifnull(a.discamount,0)+ifnull(a.returnamount,0)+ifnull(a.obamount,0))
									from cutarinv a
									join cutar b on b.cutarid = a.cutarid
									where a.invoiceid = z.invoiceid and b.recordstatus=getwfmaxstatbywfname('appcutar') and b.cutarno is not null and b.docdate between date_add('{$month1}',interval 14 day) and date_add('{$month1}', interval 20 day)),0) as pay3,
									ifnull((select sum(ifnull(a.cashamount,0)+ifnull(a.bankamount,0)+ifnull(a.discamount,0)+ifnull(a.returnamount,0)+ifnull(a.obamount,0))
									from cutarinv a
									join cutar b on b.cutarid = a.cutarid
									where a.invoiceid = z.invoiceid and b.recordstatus=getwfmaxstatbywfname('appcutar') and b.cutarno is not null and b.docdate between date_add('{$month1}',interval 21 day) and last_day('{$month1}')),0) as pay4
							from cutarinv x
							join cutar y on x.cutarid = y.cutarid
							join invoice z on z.invoiceid = x.invoiceid
							join giheader za on za.giheaderid = z.giheaderid
							join soheader zb on zb.soheaderid = za.soheaderid
							where y.recordstatus=getwfmaxstatbywfname('appcutar') 
							and zb.addressbookid = {$row1['addressbookid']}
							and zb.employeeid= {$row2['employeeid']}
							and y.docdate between '{$month1}' and last_day('{$month1}')
							and datediff(last_day('{$month1}'),z.invoicedate) > {$umur}
							and zb.companyid= {$companyid}) zz ";
							$weekpayamount = Yii::app()->db->createCommand($sqlpayamount)->queryRow();
															
							$totalpayamount = ($weekpayamount['week1']+$weekpayamount['week2']+$weekpayamount['week3']+$weekpayamount['week4']);
							
							$totalamount = (($weekamount['week1']-$saldoawal)+($weekamount['week2']-$weekamount['week1'])+($weekamount['week3']-$weekamount['week2'])+($weekamount['week4']-$weekamount['week3']));
							
							$saldoakhir = ($saldoawal+$totalamount)-$totalpayamount;
							
							$this->pdf->setFont('Arial','',8);
							$this->pdf->row(array($i,$row2['customername'],$row2['salesname'],
																		Yii::app()->format->formatCurrency(($saldoawal)/$per),
																		Yii::app()->format->formatCurrency(($weekamount['week1']-$saldoawal)/$per),
																		Yii::app()->format->formatCurrency(($weekpayamount['week1'])/$per),
																		Yii::app()->format->formatCurrency(($weekamount['week2']-$weekamount['week1'])/$per),
																		Yii::app()->format->formatCurrency(($weekpayamount['week2'])/$per),
																		Yii::app()->format->formatCurrency(($weekamount['week3']- $weekamount['week2'])/$per),
																		Yii::app()->format->formatCurrency(($weekpayamount['week3'])/$per),
																		Yii::app()->format->formatCurrency(($weekamount['week4']- $weekamount['week3'])/$per),
																		Yii::app()->format->formatCurrency(($weekpayamount['week4'])/$per),
																		Yii::app()->format->formatCurrency(($saldoakhir)/$per)
																	 ));
							$i++;
							 $space_left=$page_height-($this->pdf->getY()+$bottom_margin);
							if ($height_of_cell > $space_left) {
									$this->pdf->addPage('L','F4');
									$this->pdf->setFont('Arial','B',8);
									$this->pdf->coldetailalign = array('C','C','C','C','C','C','C','C','C');
									$this->pdf->setbordercell(array('TL','TL','TL','TL','TL','TL','TL','TL','TLR'));
									$this->pdf->setwidths(array(8,45,40,25,44,44,44,44,23));
									$this->pdf->row(array('No','Nama Toko','Nama Sales','Saldo','1 - 7','8 - 14','15 - 21','22 - 31','Saldo'));
									$this->pdf->setwidths(array(8,45,40,25,22,22,22,22,22,22,22,22,23));
									$this->pdf->setbordercell(array('LB','LB','LB','LB','TLB','TLB','TLB','TLB','TLB','TLB','TLB','TLB','LBR'));
									$this->pdf->coldetailalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C');
									$this->pdf->row(array('','','','Awal','Penambahan','Pengurangan','Penambahan','Pengurangan','Penambahan','Pengurangan','Penambahan','Pengurangan','Akhir'));
									$this->pdf->setFont('Arial','',8);
									
									$this->pdf->setbordercell(array('','','','','','','','','','','','',''));
									$this->pdf->coldetailalign = array('L','L','L','R','R','R','R','R','R','R','R','R','R');
							}
					}
			}
    }else{
        $this->pdf->SetFont('helvetica','B',20);
        $this->pdf->text(20, 88, 'Anda Harus Mengisi Umur Piutang');
    }
		$this->pdf->Output();
	}*/
	//37
	public function RekapKomisiTagihanPerSPVPerSales($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
    {
        parent::actionDownload();
        $c_employeeid = getEmployeeid();
        $issales = getSalesEmployee();
        $issales == 1 ? $emp =  getStructure($c_employeeid,$c_employeeid,$companyid) : $emp = getUserObjectValues('employee');

        $arr_emp = explode(',',$c_employeeid);
        $arr = explode(',',$emp);
        if($sales!='') {
          //$exp = array_intersect($arr_emp,$arr);
          $employeeid = implode(',',$arr);
        }
        else {
          $employeeid = $emp;
        }
        $connection = Yii::app()->db;
        $this->pdf->title='REKAP UPAH TAMBAHAN SPV SALES';
	    $this->pdf->companyid=$companyid;
        $datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

        $this->pdf->subtitle='PERIODE : '.$datetime->format('F').' '.$datetime->format('Y');

        $month = date('m',strtotime($enddate));
        $year = date('Y',strtotime($enddate));

        $prev_month_ts =  strtotime($year.'-'.$month.'-01');
        $month1 = date('Y-m-d', $prev_month_ts);
		
		  $spvscale = Yii::app()->db->createCommand("select ifnull(spvscale/100,0) from scale where recordstatus = 5 order by docdate desc")->queryScalar();

        $this->pdf->AddPage('L','F4');

        $this->pdf->text(300,15,'Per : '.$per);
        //$this->pdf->text(270,15,'X = T.O.P');
        $wheresalesarea = $whereproduct = '';
        $sqldata = "select distinct l.employeeid as spvid,l.fullname,b.companyid,GROUP_CONCAT(DISTINCT f.employeeid) as employeeid
        from cutarinv a
        join cutar b on b.cutarid=a.cutarid
        join invoice c on c.invoiceid=a.invoiceid
        join giheader d on d.giheaderid=c.giheaderid
        join soheader e on e.soheaderid=d.soheaderid
        join ttnt h on h.ttntid=b.ttntid
        join employeeorgstruc f on f.employeeid=h.employeeid
        join orgstructure i ON i.orgstructureid=f.orgstructureid
        join orgstructure j ON j.orgstructureid=i.parentid
        join employeeorgstruc k on k.orgstructureid=j.orgstructureid
        JOIN employee l ON l.employeeid=k.employeeid
        join addressbook g on g.addressbookid=e.addressbookid
        {$wheresalesarea} {$whereproduct}
        where f.recordstatus=1 and k.recordstatus=1 and j.structurename LIKE '%spv%' and g.fullname like '%".$customer."%' and l.fullname like '%".$spv."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
        b.recordstatus=3 
		-- and h.employeeid in({$employeeid})
      and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
      and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
        and e.isdisplay=0
        GROUP BY l.employeeid
        order by l.fullname asc";
        
        $data = Yii::app()->db->createCommand($sqldata)->queryAll();
        foreach($data as $row)
		    {
            $totbayar0sd50 = 0;
            $totscale0sd50 = 0;
            $totbayar50sd90 = 0;
            $totscale50sd90 = 0;
            $totbayar90sd110 = 0;
            $totscale90sd110 = 0;
            $totbayar110sd150 = 0;
            $totscale110sd150 = 0;
            $totbayarsd150 = 0;
            $totscalesd150 = 0;
            $totjumlahbayar = 0;
            $totscalejumlah = 0;
			
			$sqlsaldoup120 = "select sum(a5) as up120
				from (select case when umur > 120 then amount-payamount else 0 end as a5
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
						where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and c.employeeid in ({$row['employeeid']})
						-- and d.custcategoryid = 
						) z
					where amount > payamount) zz";
      $saldo120 = Yii::app()->db->createCommand($sqlsaldoup120)->queryScalar();
          
            $sqlcustcategory = "select i.custcategoryid,i.custcategoryname
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            join invoice c on c.invoiceid=a.invoiceid
            join giheader d on d.giheaderid=c.giheaderid
            join soheader e on e.soheaderid=d.soheaderid
            join ttnt h on h.ttntid=b.ttntid
            join employee f on f.employeeid=h.employeeid
            join addressbook g on g.addressbookid=e.addressbookid
            join custcategory i on i.custcategoryid = g.custcategoryid
            {$wheresalesarea} {$whereproduct}
            where g.fullname like '%".$customer."%'and b.recordstatus=3 
            and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
            and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
            and e.companyid = {$companyid} and f.employeeid in ({$row['employeeid']})
			      and i.custcategoryid not in (13,14)
            and e.isdisplay=0
            group by g.custcategoryid
            order by g.custcategoryid asc";
            $this->pdf->SetFont('Arial','B',9);
			      $this->pdf->sety($this->pdf->gety()+5);
            $this->pdf->text(10,$this->pdf->getY(),'NAMA SUPERVISOR : '.$row['fullname']);
			      $this->pdf->sety($this->pdf->gety()+3);
            $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();

                    $this->pdf->setFont('Arial','',9);
                    $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
                    $this->pdf->setwidths(array(30,47,47,47,47,47,48));
                    $this->pdf->colheader = array("          Period                        ",'            <= 50%TOP             120%','   50%TOP < U <= 90%TOP    110%','90%TOP < U <= 110%TOP    100%','110%TOP < U <= 150%TOP    90%','              U > 150%TOP                0%','                  Jumlah                               ');
                    $this->pdf->RowHeader();

                    $this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
                    $this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
                    $this->pdf->SetFont('Arial','',9);
                    $this->pdf->row(array('Periods','NIlai Bayar','UT','Nilai Bayar','UT','NIlai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT'));
					
            foreach($custcategory as $cust)
			{
				$subbayar0sd50 = 0;
				$subscale0sd50 = 0;
				$subbayar50sd90 = 0;
				$subscale50sd90 = 0;
				$subbayar90sd110 = 0;
				$subscale90sd110 = 0;
				$subbayar110sd150 = 0;
				$subscale110sd150 = 0;
				$subbayarsd150 = 0;
				$subscalesd150 = 0;
				$subjumlahbayar = 0;
				$subscalejumlah = 0;
                          
                $this->pdf->setFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory'). ' = '. $cust['custcategoryname']);
                $subjumlahut = 0;
                $this->pdf->SetFont('Arial','B',9);

                $sqlperiod = "select period, invoicedate from (select c.invoiceno,c.invoicedate,b.docdate,setdateformat(c.invoicedate) as period, a.cashamount+a.bankamount as nilai
                from cutarinv a
                join cutar b on b.cutarid=a.cutarid
                join invoice c on c.invoiceid=a.invoiceid
                join giheader d on d.giheaderid=c.giheaderid
                join soheader e on e.soheaderid=d.soheaderid
                join ttnt h on h.ttntid=b.ttntid
                join employee f on f.employeeid=h.employeeid
                join addressbook g on g.addressbookid=e.addressbookid
                {$wheresalesarea} {$whereproduct}
                where g.fullname like '%".$customer."%' and b.recordstatus=3 
				and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
                and e.companyid = {$companyid} and f.employeeid in ({$row['employeeid']})
                and e.isdisplay = 0
				and g.custcategoryid = {$cust['custcategoryid']}
                order by invoicedate asc ) z
                where nilai <> 0
                group by month(invoicedate),year(invoicedate)
				order by invoicedate asc";
                $this->pdf->setY($this->pdf->getY()+7);
                $period = Yii::app()->db->createCommand($sqlperiod)->queryAll();
					
                foreach($period as $row1) 
				{
					$custbayar0sd50 = 0;
					$custscale0sd50 = 0;
					$custbayar50sd90 = 0;
					$custscale50sd90 = 0;
					$custbayar90sd110 = 0;
					$custscale90sd110 = 0;
					$custbayar110sd150 = 0;
					$custscale110sd150 = 0;
					$custbayarsd150 = 0;
					$custscalesd150 = 0;
					$custjumlahbayar = 0;
					$custscalejumlah = 0;
					
          $sqlawalbulan = "select date_add(date_add(last_day('{$row1['invoicedate']}'),interval 1 day), interval -1 month) as awalbulan";
                    $tglawal = Yii::app()->db->createCommand($sqlawalbulan)->queryScalar();
					$percentperiod = Yii::app()->db->createCommand("select ifnull(scalevalue,0) from scalevaluespv where recordstatus=5 and custcategoryid = {$cust['custcategoryid']} 
            and employeeid in ({$row['spvid']})
            and perioddate = '{$tglawal}'")->queryScalar();

                    $sqldet = "select *,
								case when umur >= 0 and umur <= (0.5 * paydays) then nilaibayar else 0 end as 0sd50,
								case when umur > (0.5 * paydays) and umur <= (0.9 * paydays) then nilaibayar else 0 end AS 50sd90,
								case when umur > (0.9 * paydays) and umur <= (1.1 * paydays) then nilaibayar else 0 end AS 90sd110,
								case when umur > (1.1 * paydays) and umur <= (1.5 * paydays) then nilaibayar else 0 end AS 110sd150,
								case when umur > (1.5 * paydays) then nilaibayar else 0 end as sd150
								,(0.5 * paydays),(0.9 * paydays),(paydays),(1.5 * paydays)
								from (select distinct g.fullname,c.invoiceno,c.invoicedate,b.docdate AS cutardate,datediff(b.docdate,c.invoicedate) as umur,p.paydays,c.amount,
								a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
								(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
								join invoice c on c.invoiceid=a.invoiceid
								join giheader d on d.giheaderid=c.giheaderid
								join soheader e on e.soheaderid=d.soheaderid
								join ttnt i on i.ttntid=b.ttntid
								join employee f on f.employeeid=i.employeeid
								join addressbook g on g.addressbookid=e.addressbookid
								join paymentmethod p ON p.paymentmethodid=e.paymentmethodid
								{$wheresalesarea} {$whereproduct}
								where g.fullname like '%".$customer."%' and b.recordstatus=3 
								and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and e.companyid = {$companyid}
								and f.employeeid in (".$row['employeeid'].")
								and e.isdisplay=0
								and g.custcategoryid = {$cust['custcategoryid']}
								) z
								-- where z.materialgroupid = ''
								where month(invoicedate) = month('{$row1['invoicedate']}')
								and year(invoicedate) = year('{$row1['invoicedate']}')
								and nilaibayar <> 0 
								order by cutardate,fullname,invoicedate";
					$detail = Yii::app()->db->createCommand($sqldet)->queryAll();
                    $this->pdf->setFont('Arial','',8);
                    foreach($detail as $row2)
					{
                        //$this->pdf->row(array('','NIlai INV','UT','Nilai INV','UT','NIlai INV','UT','Nilai INV','UT','Nilai INV','UT',''));
                        $jumlahbayar = ($row2['0sd50'] + $row2['50sd90'] + $row2['90sd110'] + $row2['110sd150'] + $row2['sd150'])/$per;
                        $jumlahut = (($row2['0sd50']*$percentperiod*1.2)+($row2['50sd90']*$percentperiod*1.1)+($row2['90sd110']*$percentperiod*1)+
                                    ($row2['110sd150']*$percentperiod*0.9))/100/$per;
                    /*    $this->pdf->row(array(
                          $row2['invoiceno'],
                          Yii::app()->format->formatCurrency($row2['0sd50']),
                          Yii::app()->format->formatCurrency(($row2['0sd50']*$percentperiod*1.2)/100),
                          Yii::app()->format->formatCurrency($row2['50sd90']),
                          Yii::app()->format->formatCurrency(($row2['50sd90']*$percentperiod*1.1)/100),
                          Yii::app()->format->formatCurrency($row2['90sd110']),
                          Yii::app()->format->formatCurrency(($row2['90sd110']*$percentperiod*1)/100),
                          Yii::app()->format->formatCurrency($row2['110sd150']),
                          Yii::app()->format->formatCurrency(($row2['110sd150']*$percentperiod*0.9)/100),
                          Yii::app()->format->formatCurrency($row2['sd150']),
                          Yii::app()->format->formatCurrency(0),
                          Yii::app()->format->formatCurrency($jumlahbayar),
                          Yii::app()->format->formatCurrency($jumlahut)
                        ));*/
                        $custbayar0sd50 += $row2['0sd50']/$per;
                        $custscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $custbayar50sd90 += $row2['50sd90']/$per;
                        $custscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $custbayar90sd110 += $row2['90sd110']/$per;
                        $custscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $custbayar110sd150 += $row2['110sd150']/$per;
                        $custscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $custbayarsd150 += $row2['sd150']/$per;
                        $custscalesd150 = 0;
                        $custjumlahbayar += $jumlahbayar;
                        $custscalejumlah += $jumlahut;
                      
                        $subbayar0sd50 += $row2['0sd50']/$per;
                        $subscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $subbayar50sd90 += $row2['50sd90']/$per;
                        $subscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $subbayar90sd110 += $row2['90sd110']/$per;
                        $subscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $subbayar110sd150 += $row2['110sd150']/$per;
                        $subscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $subbayarsd150 += $row2['sd150']/$per;
                        $subscalesd150 = 0;
                        $subjumlahbayar += $jumlahbayar;
                        $subscalejumlah += $jumlahut;
                      
                        $totbayar0sd50 += $row2['0sd50']/$per;
                        $totscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $totbayar50sd90 += $row2['50sd90']/$per;
                        $totscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $totbayar90sd110 += $row2['90sd110']/$per;
                        $totscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $totbayar110sd150 += $row2['110sd150']/$per;
                        $totscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $totbayarsd150 += $row2['sd150']/$per;
                        $totscalesd150 = 0;
                        $totjumlahbayar += $jumlahbayar;
                        $totscalejumlah += $jumlahut;
                      
                        //$totjumlahut = $totjumlahut + $jumlahut;
                    }
					//$this->pdf->setFont('Arial','B',8);
					$this->pdf->row(array(
						$row1['period'].' - ('.Yii::app()->format->formatNumber($percentperiod).'%)',
						Yii::app()->format->formatCurrency($custbayar0sd50),
						Yii::app()->format->formatCurrency($custscale0sd50),
						Yii::app()->format->formatCurrency($custbayar50sd90),
						Yii::app()->format->formatCurrency($custscale50sd90),
						Yii::app()->format->formatCurrency($custbayar90sd110),
						Yii::app()->format->formatCurrency($custscale90sd110),
						Yii::app()->format->formatCurrency($custbayar110sd150),
						Yii::app()->format->formatCurrency($custscale110sd150),
						Yii::app()->format->formatCurrency($custbayarsd150),
						Yii::app()->format->formatCurrency($custscalesd150),
						Yii::app()->format->formatCurrency($custjumlahbayar),
						Yii::app()->format->formatCurrency($custscalejumlah)
					));
                    //$this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R');
                }
				$this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
				$this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->coldetailalign = array('L','R','R');
				//$this->pdf->setwidths(array(80,165,35));
				$this->pdf->row(array('JUMLAH '.strtoupper($cust['custcategoryname']),
					Yii::app()->format->formatCurrency($subbayar0sd50),
					Yii::app()->format->formatCurrency($subscale0sd50),
					Yii::app()->format->formatCurrency($subbayar50sd90),
					Yii::app()->format->formatCurrency($subscale50sd90),
					Yii::app()->format->formatCurrency($subbayar90sd110),
					Yii::app()->format->formatCurrency($subscale90sd110),
					Yii::app()->format->formatCurrency($subbayar110sd150),
					Yii::app()->format->formatCurrency($subscale110sd150),
					Yii::app()->format->formatCurrency($subbayarsd150),
					Yii::app()->format->formatCurrency($subscalesd150),
					Yii::app()->format->formatCurrency($subjumlahbayar),
					Yii::app()->format->formatCurrency($subscalejumlah)
				));
				$this->pdf->setY($this->pdf->getY()+5);
            }
			$this->pdf->checkNewPage(55);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->row(array('TOTAL SUPERVISOR ',
				Yii::app()->format->formatCurrency($totbayar0sd50),
				Yii::app()->format->formatCurrency($totscale0sd50),
				Yii::app()->format->formatCurrency($totbayar50sd90),
				Yii::app()->format->formatCurrency($totscale50sd90),
				Yii::app()->format->formatCurrency($totbayar90sd110),
				Yii::app()->format->formatCurrency($totscale90sd110),
				Yii::app()->format->formatCurrency($totbayar110sd150),
				Yii::app()->format->formatCurrency($totscale110sd150),
				Yii::app()->format->formatCurrency($totbayarsd150),
				Yii::app()->format->formatCurrency($totscalesd150),
				Yii::app()->format->formatCurrency($totjumlahbayar),
				Yii::app()->format->formatCurrency($totscalejumlah)
			));
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->coldetailalign = array('L','R','R');
            $this->pdf->setwidths(array(165,100,35));
			$saldo120per = $saldo120/$per;
            $this->pdf->row(array('TOTAL UPAH TAMBAHAN','',Yii::app()->format->formatCurrency($totscalejumlah)));
            $this->pdf->row(array('DEPOSIT 10%','',Yii::app()->format->formatCurrency(($totscalejumlah*-0.1))));
            $this->pdf->row(array('SALDO UMUR PIUTANG > 120 HARI (-0.25%) ('.Yii::app()->format->formatCurrency($spvscale).' )',Yii::app()->format->formatCurrency($saldo120per),Yii::app()->format->formatCurrency(($saldo120per*-0.25*$spvscale)/100)));
			
			$sqlmonth = "SELECT MONTH('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')";
			$month=$connection->createCommand($sqlmonth)->queryScalar();
			
			if ($month == 3 or $month == 6 or $month == 9 or $month == 12)
			{
				$sqldateminus3months = "SELECT LAST_DAY(DATE_ADD(DATE('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),INTERVAL -3 MONTH))";
				$enddateminus3months=$connection->createCommand($sqldateminus3months)->queryScalar();
				
				$sqlsaldoup120minus3months = "select sum(a5) as up120
					from (select case when umur > 120 then amount-payamount else 0 end as a5
						from (select a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddateminus3months))."',a.invoicedate) as umur,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
								from cutarinv f
								join cutar g on g.cutarid=f.cutarid
								where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddateminus3months))."'),0) as payamount
							from invoice a
							inner join giheader b on b.giheaderid = a.giheaderid
							inner join soheader c on c.soheaderid = b.soheaderid
							inner join addressbook d on d.addressbookid = c.addressbookid
							inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
							inner join employee ff on ff.employeeid = c.employeeid
							where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
							and ff.fullname like '%".$sales."%'  and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddateminus3months))."'
							and c.employeeid in ({$row['employeeid']})
							-- and d.custcategoryid = {cust['custcategoryid']}
							) z
						where amount > payamount) zz";
				$saldo120minus3months = Yii::app()->db->createCommand($sqlsaldoup120minus3months)->queryScalar();
				$saldo120minus3monthsper = $saldo120minus3months/$per;
				
				$sqlsaldoakhir = "select ifnull(sum(debit-credit)*-1,0)
						from (select case when b.accountname = 'HUTANG FINALTY TAGIHAN SALES / SPV' then a.amount else 0 end as debit,
						case when c.accountname = 'HUTANG FINALTY TAGIHAN SALES / SPV' then a.amount else 0 end as credit, e.fullname, e.employeeid
						from cbacc a
						join account b on b.accountid = a.debitaccid
						join account c on c.accountid = a.creditaccid
						join cb d on d.cbid = a.cbid
						join employee e on e.employeeid = a.employeeid
						where d.recordstatus = 3 and d.companyid=".$companyid."
						and d.docdate <= CAST('".date(Yii::app()->params['datetodb'], strtotime($enddate))."' AS DATE) and a.employeeid = (".$row['spvid'].")) z
						where debit <> 0 or credit <> 0";
				$saldoakhir = $connection->createCommand($sqlsaldoakhir)->queryScalar();
				$saldoakhirper = $saldoakhir/$per;
				
				$this->pdf->setFont('Arial','',9);
				$this->pdf->row(array('          - Saldo Umur Piutang > 120 Hari (3 Bulan Lalu)',Yii::app()->format->formatCurrency($saldo120minus3monthsper),''));
				$this->pdf->row(array('          - Saldo Hutang Finalty Tagihan Sales / SPV Per '.$datetime->format('F').' '.$datetime->format('Y'),Yii::app()->format->formatCurrency($saldoakhirper),''));
				if(($saldo120per - $saldo120minus3monthsper) < 0){$persenfinalty = (($saldo120per - $saldo120minus3monthsper) / $saldo120minus3monthsper) * 100;}else{$persenfinalty = 0;}
				$this->pdf->row(array('          - Saldo Umur Piutang > 120 Hari - Bertambah / (Berkurang)',Yii::app()->format->formatCurrency($saldo120per).'  -  '.Yii::app()->format->formatCurrency($saldo120minus3monthsper).'  =  '.Yii::app()->format->formatCurrency($saldo120per - $saldo120minus3monthsper).'   '.Yii::app()->format->formatCurrency($persenfinalty).'%',''));
				$this->pdf->setFont('Arial','B',9);
				$this->pdf->row(array('SALDO HUTANG FINALTY TAGIHAN SALES / SPV YANG DICAIRKAN ',Yii::app()->format->formatCurrency($saldoakhirper).' x '.Yii::app()->format->formatCurrency(-1*$persenfinalty).'%',Yii::app()->format->formatCurrency($saldoakhirper*-1*$persenfinalty/100)));
				$cairfinalty = $saldoakhirper*-1*$persenfinalty/100;
			}
			else
			{
				$cairfinalty = 0;
			}
			
            $upah = $totscalejumlah+($totscalejumlah*-0.1)+($saldo120per*-0.25*$spvscale/100)+$cairfinalty;
            //$upah = ($totscalejumlah+(($saldo120per*-0.5*$spvscale)/100))*0.9;
            //$upah = $totscalejumlah+($totscalejumlah*-0.1)+($saldo120per*-0.25*$spvscale/100);
            $this->pdf->row(array('UPAH TAMBAHAN YANG DITERIMA SUPERVISOR '.strtoupper($row['fullname']),'',Yii::app()->format->formatCurrency($upah)));
            //$this->pdf->text(10,$this->pdf->getY(),'SALDO UMUR PIUTANG > 120 HARI');
            $this->pdf->setY($this->pdf->getY()+5);
			
            $this->pdf->setFont('Arial','',9);
			$this->pdf->setwidths(array(63,63,63,63,63));
			$this->pdf->coldetailalign = array('C','C','C','C','C');
			$this->pdf->row(array('Diperiksa Oleh,', 'Disetujui Oleh,', 'Diketahui Oleh,', 'Dibayar Oleh,', 'Diterima Oleh,'));
			$this->pdf->setY($this->pdf->getY()+20);
			$this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR', strtoupper($row['fullname'])));
			$this->pdf->checkNewPage(120);
            
        }
        $this->pdf->Output();
    }
	public function RekapKomisiTagihanPerSPVPerSales_lama3($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
    {
        parent::actionDownload();
        $connection = Yii::app()->db;
        $this->pdf->title='REKAP UPAH TAMBAHAN SPV SALES';
	  $this->pdf->companyid=$companyid;
        $datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

        $this->pdf->subtitle='PERIODE : '.$datetime->format('F').' '.$datetime->format('Y');

        $month = date('m',strtotime($enddate));
        $year = date('Y',strtotime($enddate));

        $prev_month_ts =  strtotime($year.'-'.$month.'-01');
        $month1 = date('Y-m-d', $prev_month_ts);
		
		$spvscale = Yii::app()->db->createCommand("select ifnull(spvscale/100,0) from scale where recordstatus = 5 order by docdate desc")->queryScalar();

        $this->pdf->AddPage('L','F4');

        $this->pdf->text(300,15,'Per : '.$per);
        //$this->pdf->text(270,15,'X = T.O.P');
        $wheresalesarea = $whereproduct = '';
        $sqldata = "select distinct l.employeeid as spvid,l.fullname,b.companyid,GROUP_CONCAT(DISTINCT f.employeeid) as employeeid
        from cutarinv a
        join cutar b on b.cutarid=a.cutarid
        join invoice c on c.invoiceid=a.invoiceid
        join giheader d on d.giheaderid=c.giheaderid
        join soheader e on e.soheaderid=d.soheaderid
        join ttnt h on h.ttntid=b.ttntid
        join employeeorgstruc f on f.employeeid=h.employeeid
        join orgstructure i ON i.orgstructureid=f.orgstructureid
        join orgstructure j ON j.orgstructureid=i.parentid
        join employeeorgstruc k on k.orgstructureid=j.orgstructureid
        JOIN employee l ON l.employeeid=k.employeeid
        join addressbook g on g.addressbookid=e.addressbookid
        {$wheresalesarea} {$whereproduct}
        where f.recordstatus=1 and k.recordstatus=1 and j.structurename LIKE '%spv%' and g.fullname like '%".$customer."%' and l.fullname like '%".$spv."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
        b.recordstatus=3
		and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
		and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
        and e.isdisplay=0
        GROUP BY l.employeeid
        order by l.fullname asc";
        
        $data = Yii::app()->db->createCommand($sqldata)->queryAll();
        foreach($data as $row)
		{
            $totbayar0sd50 = 0;
            $totscale0sd50 = 0;
            $totbayar50sd90 = 0;
            $totscale50sd90 = 0;
            $totbayar90sd110 = 0;
            $totscale90sd110 = 0;
            $totbayar110sd150 = 0;
            $totscale110sd150 = 0;
            $totbayarsd150 = 0;
            $totscalesd150 = 0;
            $totjumlahbayar = 0;
            $totscalejumlah = 0;
			
			$sqlsaldoup120 = "select sum(a5) as up120
				from (select case when umur > 120 then amount-payamount else 0 end as a5
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
						where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and c.employeeid in ({$row['employeeid']})
						-- and d.custcategoryid = {$cust['custcategoryid']}
						) z
					where amount > payamount) zz";
			$saldo120 = Yii::app()->db->createCommand($sqlsaldoup120)->queryScalar();
          
            $sqlcustcategory = "select i.custcategoryid,i.custcategoryname
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            join invoice c on c.invoiceid=a.invoiceid
            join giheader d on d.giheaderid=c.giheaderid
            join soheader e on e.soheaderid=d.soheaderid
            join ttnt h on h.ttntid=b.ttntid
            join employee f on f.employeeid=h.employeeid
            join addressbook g on g.addressbookid=e.addressbookid
            join custcategory i on i.custcategoryid = g.custcategoryid
            {$wheresalesarea} {$whereproduct}
            where g.fullname like '%".$customer."%'and b.recordstatus=3 
			and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
			and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
            and e.companyid = {$companyid} and f.employeeid in ({$row['employeeid']})
			and i.custcategoryid not in (13,14)
            and e.isdisplay=0
            group by g.custcategoryid
            order by g.custcategoryid asc";
            $this->pdf->SetFont('Arial','B',9);
			$this->pdf->sety($this->pdf->gety()+5);
            $this->pdf->text(10,$this->pdf->getY(),'NAMA SUPERVISOR : '.$row['fullname']);
			$this->pdf->sety($this->pdf->gety()+3);
            $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();

                    $this->pdf->setFont('Arial','',9);
                    $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
                    $this->pdf->setwidths(array(30,47,47,47,47,47,48));
                    $this->pdf->colheader = array("          Period                        ",'            <= 50%TOP             120%','   50%TOP < U <= 90%TOP    110%','90%TOP < U <= 110%TOP    100%','110%TOP < U <= 150%TOP    90%','              U > 150%TOP                0%','                  Jumlah                               ');
                    $this->pdf->RowHeader();

                    $this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
                    $this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
                    $this->pdf->SetFont('Arial','',9);
                    $this->pdf->row(array($row1['period'],'NIlai Bayar','UT','Nilai Bayar','UT','NIlai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT'));
					
            foreach($custcategory as $cust)
			{
				$subbayar0sd50 = 0;
				$subscale0sd50 = 0;
				$subbayar50sd90 = 0;
				$subscale50sd90 = 0;
				$subbayar90sd110 = 0;
				$subscale90sd110 = 0;
				$subbayar110sd150 = 0;
				$subscale110sd150 = 0;
				$subbayarsd150 = 0;
				$subscalesd150 = 0;
				$subjumlahbayar = 0;
				$subscalejumlah = 0;
                          
                $this->pdf->setFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory'). ' = '. $cust['custcategoryname']);
                $subjumlahut = 0;
                $this->pdf->SetFont('Arial','B',9);

                $sqlperiod = "select period, invoicedate from (select c.invoiceno,c.invoicedate,b.docdate,setdateformat(c.invoicedate) as period, a.cashamount+a.bankamount as nilai
                from cutarinv a
                join cutar b on b.cutarid=a.cutarid
                join invoice c on c.invoiceid=a.invoiceid
                join giheader d on d.giheaderid=c.giheaderid
                join soheader e on e.soheaderid=d.soheaderid
                join ttnt h on h.ttntid=b.ttntid
                join employee f on f.employeeid=h.employeeid
                join addressbook g on g.addressbookid=e.addressbookid
                {$wheresalesarea} {$whereproduct}
                where g.fullname like '%".$customer."%' and b.recordstatus=3 
				and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
                and e.companyid = {$companyid} and f.employeeid in ({$row['employeeid']})
                and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
                order by invoicedate asc ) z
                where nilai <> 0
                group by month(invoicedate),year(invoicedate)
				order by invoicedate asc";
                $this->pdf->setY($this->pdf->getY()+7);
                $period = Yii::app()->db->createCommand($sqlperiod)->queryAll();
					
                foreach($period as $row1) 
				{
					$custbayar0sd50 = 0;
					$custscale0sd50 = 0;
					$custbayar50sd90 = 0;
					$custscale50sd90 = 0;
					$custbayar90sd110 = 0;
					$custscale90sd110 = 0;
					$custbayar110sd150 = 0;
					$custscale110sd150 = 0;
					$custbayarsd150 = 0;
					$custscalesd150 = 0;
					$custjumlahbayar = 0;
					$custscalejumlah = 0;
					
                    $sqlawalbulan = "select date_add(date_add(last_day('{$row1['invoicedate']}'),interval 1 day), interval -1 month) as awalbulan";
                    $tglawal = Yii::app()->db->createCommand($sqlawalbulan)->queryScalar();
					$percentperiod = Yii::app()->db->createCommand("select ifnull(scalevalue,0) from scalevaluespv where recordstatus=5 and custcategoryid = {$cust['custcategoryid']} and employeeid in ({$row['spvid']})
                      and perioddate = '{$tglawal}'")->queryScalar();

                    $sqldet = "select *,
								case when umur >= 0 and umur <= (0.5 * paydays) then nilaibayar else 0 end as 0sd50,
								case when umur > (0.5 * paydays) and umur <= (0.9 * paydays) then nilaibayar else 0 end AS 50sd90,
								case when umur > (0.9 * paydays) and umur <= (1.1 * paydays) then nilaibayar else 0 end AS 90sd110,
								case when umur > (1.1 * paydays) and umur <= (1.5 * paydays) then nilaibayar else 0 end AS 110sd150,
								case when umur > (1.5 * paydays) then nilaibayar else 0 end as sd150
								,(0.5 * paydays),(0.9 * paydays),(paydays),(1.5 * paydays)
								from (select distinct g.fullname,c.invoiceno,c.invoicedate,b.docdate AS cutardate,datediff(b.docdate,c.invoicedate) as umur,p.paydays,c.amount,
								a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
								(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
								join invoice c on c.invoiceid=a.invoiceid
								join giheader d on d.giheaderid=c.giheaderid
								join soheader e on e.soheaderid=d.soheaderid
								join ttnt i on i.ttntid=b.ttntid
								join employee f on f.employeeid=i.employeeid
								join addressbook g on g.addressbookid=e.addressbookid
								join paymentmethod p ON p.paymentmethodid=e.paymentmethodid
								{$wheresalesarea} {$whereproduct}
								where g.fullname like '%".$customer."%' and b.recordstatus=3 
								and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and e.companyid = {$companyid}
								and f.employeeid in (".$row['employeeid'].")
								and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
								) z
								-- where z.materialgroupid = ''
								where month(invoicedate) = month('{$row1['invoicedate']}')
								and year(invoicedate) = year('{$row1['invoicedate']}')
								and nilaibayar <> 0 
								order by cutardate,fullname,invoicedate";
					$detail = Yii::app()->db->createCommand($sqldet)->queryAll();
                    $this->pdf->setFont('Arial','',8);
                    foreach($detail as $row2)
					{
                        //$this->pdf->row(array('','NIlai INV','UT','Nilai INV','UT','NIlai INV','UT','Nilai INV','UT','Nilai INV','UT',''));
                        $jumlahbayar = ($row2['0sd50'] + $row2['50sd90'] + $row2['90sd110'] + $row2['110sd150'] + $row2['sd150'])/$per;
                        $jumlahut = (($row2['0sd50']*$percentperiod*1.2)+($row2['50sd90']*$percentperiod*1.1)+($row2['90sd110']*$percentperiod*1)+
                                    ($row2['110sd150']*$percentperiod*0.9))/100/$per;
                    /*    $this->pdf->row(array(
                          $row2['invoiceno'],
                          Yii::app()->format->formatCurrency($row2['0sd50']),
                          Yii::app()->format->formatCurrency(($row2['0sd50']*$percentperiod*1.2)/100),
                          Yii::app()->format->formatCurrency($row2['50sd90']),
                          Yii::app()->format->formatCurrency(($row2['50sd90']*$percentperiod*1.1)/100),
                          Yii::app()->format->formatCurrency($row2['90sd110']),
                          Yii::app()->format->formatCurrency(($row2['90sd110']*$percentperiod*1)/100),
                          Yii::app()->format->formatCurrency($row2['110sd150']),
                          Yii::app()->format->formatCurrency(($row2['110sd150']*$percentperiod*0.9)/100),
                          Yii::app()->format->formatCurrency($row2['sd150']),
                          Yii::app()->format->formatCurrency(0),
                          Yii::app()->format->formatCurrency($jumlahbayar),
                          Yii::app()->format->formatCurrency($jumlahut)
                        ));*/
                        $custbayar0sd50 += $row2['0sd50']/$per;
                        $custscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $custbayar50sd90 += $row2['50sd90']/$per;
                        $custscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $custbayar90sd110 += $row2['90sd110']/$per;
                        $custscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $custbayar110sd150 += $row2['110sd150']/$per;
                        $custscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $custbayarsd150 += $row2['sd150']/$per;
                        $custscalesd150 = 0;
                        $custjumlahbayar += $jumlahbayar;
                        $custscalejumlah += $jumlahut;
                      
                        $subbayar0sd50 += $row2['0sd50']/$per;
                        $subscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $subbayar50sd90 += $row2['50sd90']/$per;
                        $subscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $subbayar90sd110 += $row2['90sd110']/$per;
                        $subscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $subbayar110sd150 += $row2['110sd150']/$per;
                        $subscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $subbayarsd150 += $row2['sd150']/$per;
                        $subscalesd150 = 0;
                        $subjumlahbayar += $jumlahbayar;
                        $subscalejumlah += $jumlahut;
                      
                        $totbayar0sd50 += $row2['0sd50']/$per;
                        $totscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $totbayar50sd90 += $row2['50sd90']/$per;
                        $totscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $totbayar90sd110 += $row2['90sd110']/$per;
                        $totscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $totbayar110sd150 += $row2['110sd150']/$per;
                        $totscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $totbayarsd150 += $row2['sd150']/$per;
                        $totscalesd150 = 0;
                        $totjumlahbayar += $jumlahbayar;
                        $totscalejumlah += $jumlahut;
                      
                        $totjumlahut = $totjumlahut + $jumlahut;
                    }
					//$this->pdf->setFont('Arial','B',8);
					$this->pdf->row(array(
						$row1['period'].' - ('.Yii::app()->format->formatNumber($percentperiod).'%)',
						Yii::app()->format->formatCurrency($custbayar0sd50),
						Yii::app()->format->formatCurrency($custscale0sd50),
						Yii::app()->format->formatCurrency($custbayar50sd90),
						Yii::app()->format->formatCurrency($custscale50sd90),
						Yii::app()->format->formatCurrency($custbayar90sd110),
						Yii::app()->format->formatCurrency($custscale90sd110),
						Yii::app()->format->formatCurrency($custbayar110sd150),
						Yii::app()->format->formatCurrency($custscale110sd150),
						Yii::app()->format->formatCurrency($custbayarsd150),
						Yii::app()->format->formatCurrency($custscalesd150),
						Yii::app()->format->formatCurrency($custjumlahbayar),
						Yii::app()->format->formatCurrency($custscalejumlah)
					));
                    //$this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R');
                }
				$this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
				$this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->coldetailalign = array('L','R','R');
				//$this->pdf->setwidths(array(80,165,35));
				$this->pdf->row(array('JUMLAH '.strtoupper($cust['custcategoryname']),
					Yii::app()->format->formatCurrency($subbayar0sd50),
					Yii::app()->format->formatCurrency($subscale0sd50),
					Yii::app()->format->formatCurrency($subbayar50sd90),
					Yii::app()->format->formatCurrency($subscale50sd90),
					Yii::app()->format->formatCurrency($subbayar90sd110),
					Yii::app()->format->formatCurrency($subscale90sd110),
					Yii::app()->format->formatCurrency($subbayar110sd150),
					Yii::app()->format->formatCurrency($subscale110sd150),
					Yii::app()->format->formatCurrency($subbayarsd150),
					Yii::app()->format->formatCurrency($subscalesd150),
					Yii::app()->format->formatCurrency($subjumlahbayar),
					Yii::app()->format->formatCurrency($subscalejumlah)
				));
				$this->pdf->setY($this->pdf->getY()+5);
            }
			$this->pdf->checkNewPage(55);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->row(array('TOTAL SUPERVISOR ',
				Yii::app()->format->formatCurrency($totbayar0sd50),
				Yii::app()->format->formatCurrency($totscale0sd50),
				Yii::app()->format->formatCurrency($totbayar50sd90),
				Yii::app()->format->formatCurrency($totscale50sd90),
				Yii::app()->format->formatCurrency($totbayar90sd110),
				Yii::app()->format->formatCurrency($totscale90sd110),
				Yii::app()->format->formatCurrency($totbayar110sd150),
				Yii::app()->format->formatCurrency($totscale110sd150),
				Yii::app()->format->formatCurrency($totbayarsd150),
				Yii::app()->format->formatCurrency($totscalesd150),
				Yii::app()->format->formatCurrency($totjumlahbayar),
				Yii::app()->format->formatCurrency($totscalejumlah)
			));
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->coldetailalign = array('L','R','R');
            $this->pdf->setwidths(array(165,100,35));
			$saldo120per = $saldo120/$per;
            $this->pdf->row(array('SALDO UMUR PIUTANG > 120 HARI (-0.5%) ('.Yii::app()->format->formatCurrency($spvscale).' )',Yii::app()->format->formatCurrency($saldo120per),
                                  Yii::app()->format->formatCurrency(($saldo120per*-0.5*$spvscale)/100)));
            $this->pdf->row(array('TOTAL UPAH TAMBAHAN','',Yii::app()->format->formatCurrency($totscalejumlah+(($saldo120per*-0.5*$spvscale)/100))));
            $this->pdf->row(array('DEPOSIT 10%','',Yii::app()->format->formatCurrency(($totscalejumlah+(($saldo120per*-0.5*$spvscale)/100))*-0.1)));
            $upah = ($totscalejumlah+(($saldo120per*-0.5*$spvscale)/100))*0.9;
            $this->pdf->row(array('UPAH TAMBAHAN YANG DITERIMA SUPERVISOR '.strtoupper($row['fullname']),'',Yii::app()->format->formatCurrency($upah)));
            //$this->pdf->text(10,$this->pdf->getY(),'SALDO UMUR PIUTANG > 120 HARI');
            $this->pdf->setY($this->pdf->getY()+5);
			
            $this->pdf->setFont('Arial','',9);
			$this->pdf->setwidths(array(63,63,63,63,63));
			$this->pdf->coldetailalign = array('C','C','C','C','C');
			$this->pdf->row(array('Diperiksa Oleh,', 'Disetujui Oleh,', 'Diketahui Oleh,', 'Dibayar Oleh,', 'Diterima Oleh,'));
			$this->pdf->setY($this->pdf->getY()+20);
			$this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR', strtoupper($row['fullname'])));
			$this->pdf->checkNewPage(120);
            
        }
        $this->pdf->Output();
    }
	public function RekapKomisiTagihanPerSPVPerSales_lama($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
    {
        parent::actionDownload();
			//$this->no_result();
			
        $connection = Yii::app()->db;
        $this->pdf->title='LAPORAN TARGET PENAGIHAN VS REALISASI PENAGIHAN SPV';
        $datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

        $this->pdf->subtitle='TARGET BULAN : '.$datetime->format('F').' '.$datetime->format('Y');

        $month = date('m',strtotime($enddate));
        $year = date('Y',strtotime($enddate));

        $prev_month_ts =  strtotime($year.'-'.$month.'-01');
        $month1 = date('Y-m-d', $prev_month_ts);

        $this->pdf->AddPage('L',array(210,365));

        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->sety($this->pdf->gety()+5);

        $this->pdf->text(310,15,'Per : '.$per);
        $this->pdf->text(270,15,'X = T.O.P');

        $maxstat2 = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appps')")->queryScalar();

        $maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppt')")->queryScalar();
        
        $maxs=0;
        $maxscale = Yii::app()->db->createCommand("select b.min, b.max,gt30,gt15,gt7,gt0,x0,lt0,lt14
                from paymentscale a
                join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                where a.companyid={$companyid} and a.perioddate  = '{$month1}' and a.recordstatus={$maxstat2}
                and b.min = (select max(c.min) from paymentscaledet c where c.paymentscaleid=a.paymentscaleid)")->queryRow();
        
        if(isset($spv) && $spv!='')
        {
            $wherespv = " and d.parentid in 
            (select distinct a.orgstructureid
            from employeeorgstruc a
            join employee b on b.employeeid = a.employeeid
            join orgstructure c on c.orgstructureid = a.orgstructureid
            where b.fullname like '%{$spv}%' and c.companyid={$companyid}
            and a.recordstatus=1)";
        }
        else
        {
            $wherespv = '';
        }
        
        $sqlspv = "select group_concat(a.employeeid) as employee, d.parentid,count(1) as hitung
        from paymenttarget a
        join employee b on b.employeeid = a.employeeid
        join employeeorgstruc c on c.employeeid = b.employeeid
        join orgstructure d on d.orgstructureid = c.orgstructureid and d.companyid = a.companyid
        where a.perioddate = '{$month1}' and a.recordstatus={$maxstat} and a.companyid={$companyid} and c.recordstatus=1
        {$wherespv}
        group by d.orgstructureid";
        $result = Yii::app()->db->createCommand($sqlspv)->queryAll();
        $c=0;
        
        $sqlcusttradisional = "select custcategoryid from custcategory where custcategoryname like '%tradisional%'";
        $custtradisional = Yii::app()->db->createCommand($sqlcusttradisional)->queryScalar();
        foreach($result as $rows)
        {
            $totaltargetspv = 0;
            $totalrealisasispv = 0;
            $totalgt30spv = 0; 
            $totalgt15spv = 0;
            $totalgt7spv = 0;
            $totalgt0spv = 0;
            $totalx0spv = 0;
            $totallt0spv = 0;
            $totallt14spv = 0;
            $komisigt30spv = 0;
            $komisigt15spv = 0;
            $komisigt7spv = 0;
            $komisigt0spv = 0;
            $komisix0spv = 0;
            $komisilt0spv = 0;
            $komisilt14spv = 0;
            $totalkomisispv = 0;
            
            $sqlspvname = "select substring_index(substring_index(structurename, ',', 1), ',', - 1) as structurename, 
            a.fullname as spvname
            from employee a
            join employeeorgstruc b on b.employeeid = a.employeeid
            join orgstructure c on c.orgstructureid = b.orgstructureid
            where c.orgstructureid = {$rows['parentid']}";
            $resspvname = Yii::app()->db->createCommand($sqlspvname)->queryAll();
            
            foreach($resspvname as $spvname)
            {
                $sqlsales = "select c.employeeid, c.fullname, a.paymenttargetid
                from paymenttarget a
                join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
                join employee c on a.employeeid = c.employeeid
                join employeeorgstruc d on d.employeeid = c.employeeid
                join orgstructure e on e.orgstructureid = d.orgstructureid
                where a.recordstatus = {$maxstat} and month(a.perioddate) = month('".date(Yii::app()->params['datetodb'],strtotime($enddate))."') and year(a.perioddate) = year('".date(Yii::app()->params['datetodb'],strtotime($enddate))."')
                and c.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")." and a.companyid = {$companyid}
                and e.parentid = {$rows['parentid']}
                union
                select 0,'{$spvname['spvname']}',0";
                //$res = Yii::app()->db->createCommand($sqlsales)->queryAll();
                $emp = Yii::app()->db->createCommand($sqlsales)->queryAll();
			
                foreach($emp as $row)
                {
                    $totaltarget = 0;
                    $totalrealisasi = 0;
                    $totalgt30 = 0; 
                    $totalgt15 = 0;
                    $totalgt7 = 0;
                    $totalgt0 = 0;
                    $totalx0 = 0;
                    $totallt0 = 0;
                    $totallt14 = 0;
                    $komisigt30 = 0;
                    $komisigt15 = 0;
                    $komisigt7 = 0;
                    $komisigt0 = 0;
                    $komisix0 = 0;
                    $komisilt0 = 0;
                    $komisilt14 = 0;
                    $totalkomisi = 0;
                    $percent=0;
                    $persentotal=0;
                    $next=0;
                    
                    if($c==$rows['hitung'])
                    {
                        $where_all = " and c.employeeid in ({$rows['employee']})";
                        $where_all1= " and g.employeeid in ({$rows['employee']})";
                    }
                    else
                    {
                        $where_all = " and c.employeeid = {$row['employeeid']}";   
                        $where_all1 = " and g.employeeid = {$row['employeeid']}";   
                    }
                    
                    $sqlcustcategory = "select a.custcategoryid as custid, d.custcategoryname
                    from custcategory a
                    join paymenttargetdet b on b.custcategoryid = a.custcategoryid
                    join paymenttarget c on c.paymenttargetid = b.paymenttargetid
                    join custcategory d on d.custcategoryid = b.custcategoryid
                    where c.companyid = {$companyid} and c.perioddate = '{$month1}' {$where_all}
                    and c.recordstatus = {$maxstat} 
                    and d.custcategoryname like '%tradisional%'
                    group by a.custcategoryid
                    union
                    select i.custcategoryid, i.custcategoryname
                    from cutarinv a
                    join cutar b on b.cutarid = a.cutarid
                    join invoice c on c.invoiceid = a.invoiceid
                    join giheader d on d.giheaderid = c.giheaderid
                    join soheader e on e.soheaderid = d.soheaderid
                    join ttnt f on f.ttntid = b.ttntid
                    join employee g on g.employeeid = f.employeeid
                    join addressbook h on h.addressbookid = e.addressbookid
                    join custcategory i on i.custcategoryid = h.custcategoryid
                    and i.custcategoryname like '%tradisional%'
                    where b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' {$where_all1} and b.companyid = {$companyid} group by h.custcategoryid";

                    $this->pdf->setwidths(array(150));
                    $this->pdf->SetFont('Arial','',10);
                    //$this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->row(array('NAMA SALES : '.$sales['fullname']));
                    if($c==$rows['hitung'])
                    {
                        $this->pdf->row(array('NAMA SPV = '. $row['fullname']));    
                    }
                    else
                    {
                        $this->pdf->row(array('NAMA SALES = '. $row['fullname']));
                    }

                    $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();
                    foreach($custcategory as $rslt)
                    {
                            //$sqladdressbook = "select addressbookid, fullname as custname from addressbook a where a.addressbookid = ".$sales['addressbookid'];

                        $totaltargetcust = 0;
                        $totalrealisasicust = 0;
                        $totalgt30cust = 0; 
                        $totalgt15cust = 0;
                        $totalgt7cust = 0;
                        $totalgt0cust = 0;
                        $totalx0cust = 0;
                        $totallt0cust = 0;
                        $totallt14cust = 0;
                        $totalkomisicust = 0;

                        if($c==$rows['hitung'])
                        {
                            $custreal = " and f.employeeid in ({$rows['employee']}) and g.custcategoryid = ".$custtradisional;
                            $custpayment = " and a.employeeid in ({$rows['employee']}) and b.custcategoryid = ".$custtradisional;
                            
                            $sqlminscale = "select spvscale
                            from paymentscale t
                            where t.recordstatus = {$maxstat2} and t.perioddate = '{$month1}' 
                            and t.companyid = {$companyid}";
                            $minscale = Yii::app()->db->createCommand($sqlminscale)->queryScalar();
                        }
                        else
                        {
                            $custreal = " and f.employeeid = {$row['employeeid']} and g.custcategoryid = ".$rslt['custid'];
                            $custpayment = " and a.employeeid = {$row['employeeid']} and b.custcategoryid = ".$rslt['custid'];
                            
                            $sqlminscale = "select minscale
                            from paymentscale t
                            where t.recordstatus = {$maxstat2} and t.perioddate = '{$month1}' 
                            and t.companyid = {$companyid}";
                            $minscale = Yii::app()->db->createCommand($sqlminscale)->queryScalar();
                        }

                        if($rslt['custid']=='')
                        {
                            $next=0;
                        }
                        else
                        {
                            $next=1;
                        }
                        /*
                        $sqladdressbook = "select b.addressbookid, c.fullname as custname
                                            from paymenttarget a
                                            join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
                                            join addressbook c on c.addressbookid = b.addressbookid
                                            where a.recordstatus = {$maxstat} and perioddate = '{$month1}' and a.employeeid = ".$row['employeeid']." and a.companyid = {$companyid}
                                            and b.custcategoryid = ".$rslt['custid']."
                                            union
                                            select h.addressbookid, h.fullname as custname 
                                            from cutarinv a
                                            join cutar b on b.cutarid = a.cutarid
                                            join invoice c on c.invoiceid = a.invoiceid
                                            join giheader d on d.giheaderid = c.giheaderid
                                            join soheader e on e.soheaderid = d.soheaderid
                                            join ttnt f on f.ttntid = b.ttntid
                                            join employee g on g.employeeid = f.employeeid
                                            join addressbook h on h.addressbookid = e.addressbookid
                                            where b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and g.employeeid = ".$row['employeeid']." and b.companyid = {$companyid} and h.custcategoryid = ".$rslt['custid']." group by h.addressbookid ";

                        $addressbook = Yii::app()->db->createCommand($sqladdressbook)->queryAll();
                            */

                        $this->pdf->setFont('Arial','B',10);
                        $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory').' : '.$rslt['custcategoryname']);

                        $this->pdf->setY($this->pdf->getY()+8);
                        $this->pdf->SetFont('Arial','',10);
                        $this->pdf->colalign = array('C','C','C','C','C');
                        $this->pdf->setwidths(array(50,150,150));

                        $this->pdf->colheader = array('','KUMULATIF REALISASI','NILAI KOMISI TAGIHAN');

                        $this->pdf->RowHeader();
                        $this->pdf->coldetailalign = array('L','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
                        $this->pdf->setwidths(array(28,20,21,12,17,17,17,17,17,17,17,17,17,17,17,17,17,17,21));
                        $this->pdf->SetFont('Arial','',9);
                        $this->pdf->row(array('KET','TARGET TAGIHAN','REALISASI TAGIHAN','%',
                                            'X > 30','15<X<=30','7X<=15','0 <X<=7','X=0','-14<X<= 0','X < -14',
                                            'X > 30','15<X<=30','7<X<=15','0 <X<=7','X=0','-14<X<= 0','X < -14',
                                            'TOTAL'));

                        $this->pdf->setwidths(array(28,20,21,12,17,17,17,17,17,17,17,17,17,17,17,17,17,17,21));
                        $this->pdf->setFont('Arial','',9);
                        
                        //foreach($addressbook as $row1)
                        //{   
                        /*
                        if($c==$rows['hitung'])
                        {
                            $custreal = " and f.employeeids in ({$rows['employee']}) and g.custcategoryid = ".$custtradisional;
                            $custpayment = " and a.employeeid sin ({$rows['employee']}) and b.custcategoryid = ".$custtradisional;
                        }
                        else
                        {
                            
                            $custreal = " and f.employeeid = {$row['employeeid']} and g.custcategoryid = ".$rslt['custid'];
                            $custpayment = " and a.employeeid = {$row['employeeid']} and b.custcategoryid = ".$rslt['custid'];
                        }
                        */
                        $sql2 = "select sum(target) as target, sum(realisasi) as realisasi,
                        sum(kumlt14) as kumlt14,sum(kumlt0) as kumlt0,sum(kumx0) as kumx0,sum(kumgt0) as kumgt0,sum(kumgt7) as kumgt7,sum(kumgt15) as kumgt15,sum(kumgt30) as kumgt30,addressbookid,fullname
                        from (select ifnull(sum(gt30+gt15+gt7+gt0+x0+lt0+lt14),0) as target, 
                        (select sum(a.cashamount+a.bankamount) as kumbayar
                        from cutarinv a
                        join cutar b on a.cutarid = b.cutarid
                        join invoice c on c.invoiceid = a.invoiceid
                        join giheader d on d.giheaderid = c.giheaderid
                        join soheader e on e.soheaderid = d.soheaderid
                        join ttnt i on i.ttntid = b.ttntid
                        join employee f on f.employeeid = i.employeeid
                        join addressbook g on g.addressbookid = e.addressbookid
                        join paymentmethod h on h.paymentmethodid = e.paymentmethodid
                        where e.companyid = {$companyid} {$custreal}
                        and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 ) as realisasi,
                        0 as kumlt14,0 as kumlt0, 0 as kumx0, 0 as kumgt0, 0 as kumgt7, 0 as kumgt15, 0 as kumgt30, c.addressbookid,c.fullname, ''
                        from paymenttarget a
                        join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
                        join addressbook c on c.addressbookid = b.addressbookid
                        where a.recordstatus = {$maxstat} and a.companyid = {$companyid}
                        and a.perioddate = '{$month1}' {$custpayment}
                        union
                        select 0 target, 0 as realisasi,
                        case when umurtop < -14 then sum(kumbayar) else 0 end as kumlt14,
                        case when umurtop < 0 and umurtop >= -14 then sum(kumbayar) else 0 end as kumlt0,
                        case when umurtop = 0 then sum(kumbayar) else 0 end as kumx0,
                        case when umurtop > 0 and umurtop <= 7 then sum(kumbayar) else 0 end as kumgt0,
                        case when umurtop > 7 and umurtop <= 15 then sum(kumbayar) else 0 end as kumgt7,
                        case when umurtop > 15 and umurtop <= 30 then sum(kumbayar) else 0 end as kumgt15,
                        case when umurtop > 30 then sum(kumbayar) else 0 end as kumgt30, addressbookid, fullname, invoiceno
                        from (
                        select c.invoiceno, a.invoiceid, g.fullname, datediff(b.docdate,date_add(c.invoicedate, interval h.paydays day)) as umurtop, a.cashamount+a.bankamount as kumbayar, g.addressbookid
                        from cutarinv a
                        join cutar b on a.cutarid = b.cutarid
                        join invoice c on c.invoiceid = a.invoiceid
                        join giheader d on d.giheaderid = c.giheaderid
                        join soheader e on e.soheaderid = d.soheaderid
                        join ttnt i on i.ttntid = b.ttntid
                        join employee f on f.employeeid = i.employeeid
                        join addressbook g on g.addressbookid = e.addressbookid
                        join paymentmethod h on h.paymentmethodid = e.paymentmethodid
                        where e.companyid = {$companyid}
                        and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 {$custreal}
                        ) z group by invoiceid) x";
                        $res2 = Yii::app()->db->createCommand($sql2)->queryAll();

                        foreach($res2 as $row3)
                        {                    
                            if($row3['target']==0)
                            {
                                $percent1 = 0;
                            }
                            else
                            {
                                $percent1 = $row3['realisasi']/$row3['target'];
                            }
                                /*
                                $this->pdf->setFont('Arial','',8);
                                $this->pdf->row(array($row1['custname'],
                                Yii::app()->format->formatCurrency($row3['target']/$per),
                                Yii::app()->format->formatCurrency($row3['realisasi']/$per),
                                number_format($percent1*100,1,'.',',').' %',
                                Yii::app()->format->formatCurrency($row3['kumgt30']/$per),
                                Yii::app()->format->formatCurrency($row3['kumgt15']/$per),
                                Yii::app()->format->formatCurrency($row3['kumgt7']/$per),
                                Yii::app()->format->formatCurrency($row3['kumgt0']/$per),
                                Yii::app()->format->formatCurrency($row3['kumx0']/$per),
                                Yii::app()->format->formatCurrency($row3['kumlt0']/$per),
                                Yii::app()->format->formatCurrency($row3['kumlt14']/$per),
                                ));
                                */
                            $totaltarget = ($totaltarget + $row3['target']);
                            $totalrealisasi = ($totalrealisasi + $row3['realisasi']);
                            $totalgt30 = ($totalgt30 + $row3['kumgt30']);
                            $totalgt15 = ($totalgt15 + $row3['kumgt15']);
                            $totalgt7  = ($totalgt7 + $row3['kumgt7']);
                            $totalgt0 = ($totalgt0 + $row3['kumgt0']);
                            $totalx0 = ($totalx0 + $row3['kumx0']);
                            $totallt0 = ($totallt0 + $row3['kumlt0']);
                            $totallt14 = ($totallt14 + $row3['kumlt14']);


                            $totaltargetcust = ($totaltargetcust + $row3['target']);
                            $totalrealisasicust = ($totalrealisasicust + $row3['realisasi']);
                            $totalgt30cust = ($totalgt30cust + $row3['kumgt30']);
                            $totalgt15cust = ($totalgt15cust + $row3['kumgt15']);
                            $totalgt7cust  = ($totalgt7cust + $row3['kumgt7']);
                            $totalgt0cust = ($totalgt0cust + $row3['kumgt0']);
                            $totalx0cust = ($totalx0cust + $row3['kumx0']);
                            $totallt0cust = ($totallt0cust + $row3['kumlt0']);
                            $totallt14cust = ($totallt14cust + $row3['kumlt14']);

                        }              
                    }

                    $this->pdf->setY($this->pdf->getY()+2);

                    $this->pdf->setFont('Arial','B',8);
                    //$this->pdf->setwidths(array(90));
                    if($totaltarget==0)
                    {
                        $percent = 0;
                        $max = 0;
                        $min = null;
                    }
                    else
                    {
                        $percent = ($totalrealisasicust/$totaltargetcust)*100;
                        if($percent>=$minscale)
                        {
                            /*
                            if($percent > 100)
                            {
                                $max = null;
                                $min = 100;
                            }
                            elseif($percent > 90 && $percent < 100)
                            {
                                $max = 100;
                                $min = 90;
                            }
                            elseif($percent > 80 && $percent < 90)
                            {
                                $max = 90;
                                $min = 80;
                            }
                            elseif($percent > 70 && $percent < 80)
                            {
                                $max = 80;
                                $min = 70;
                            }
                            else
                            {
                                $max = 70;
                                $min = null;
                            }
                            */
                            if($percent>$maxscale['min'])
                                    {
                                        // check lebih tinggi dari max
                                        if($percent>$maxscale['max'] || $maxscale['max']=='-1')
                                        {
                                            // ambil skala berdasarkan max persentasi
                                            $gt30 = $maxscale['gt30'];
                                            $gt15 = $maxscale['gt15'];
                                            $gt7 = $maxscale['gt7'];
                                            $gt0 = $maxscale['gt0'];
                                            $x0 = $maxscale['x0'];
                                            $lt0 = $maxscale['lt0'];
                                            $lt14 = $maxscale['lt14'];
                                        }
                                            $gt30 = $maxscale['gt30'];
                                            $gt15 = $maxscale['gt15'];
                                            $gt7 = $maxscale['gt7'];
                                            $gt0 = $maxscale['gt0'];
                                            $x0 = $maxscale['x0'];
                                            $lt0 = $maxscale['lt0'];
                                            $lt14 = $maxscale['lt14'];
                                    }
                                    else
                                    {
                                        $sql = "select * from (select b.*
                                            from paymentscale a
                                            join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                                            where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid}
                                            and min <= round({$percent})
                                            union
                                            select b.*
                                            from paymentscale a
                                            join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                                            where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid}
                                            and max <= round({$percent}) ) z
                                            where round({$percent}) between min and max";
                                        $q = Yii::app()->db->createCommand($sql)->queryRow();
                                        $gt30 = $q['gt30'];
                                        $gt15 = $q['gt15'];
                                        $gt7 = $q['gt7'];
                                        $gt0 = $q['gt0'];
                                        $x0 = $q['x0'];
                                        $lt0 = $q['lt0'];
                                        $lt14 = $q['lt14'];
                                    }
                        }
                        else
                                {
                                    //$min=' = 0'; $max=' = 0';
                                    //var_dump($gt30);
                                    $gt30 = 0;
                                    $gt15 = 0;
                                    $gt7 = 0;
                                    $gt0 = 0;
                                    $x0 = 0;
                                    $lt0 = 0;
                                    $lt14 = 0;
                                    
                                }
                    }

                    if($c!=$rows['hitung'])
                    {
                        $sqlcustscale = "select paramvalue
                        from paymentscalecat a 
                        join paymentscale b on b.paymentscaleid = a.paymentscaleid
                        where b.perioddate = '{$month1}' and b.recordstatus = {$maxstat2} and b.companyid = {$companyid}
                        and a.custcategoryid = ".$rslt['custid'];
                        $custscale = Yii::app()->db->createCommand($sqlcustscale)->queryScalar();
                        
                        $spvscale=100;
                    }
                    else
                    {
                        $sqlcustscale = "select paramvalue
                        from paymentscalecat a 
                        join paymentscale b on b.paymentscaleid = a.paymentscaleid
                        where b.perioddate = '{$month1}' and b.recordstatus = {$maxstat2} and b.companyid = {$companyid}
                        and a.custcategoryid = ".$custtradisional;
                        $custscale = Yii::app()->db->createCommand($sqlcustscale)->queryScalar();
                        
                        $sqlspvscale = "select paramspv from paymentscale b where b.perioddate = '{$month1}' and b.companyid = {$companyid} and b.recordstatus = ".$maxstat2;
                        $spvscale = Yii::app()->db->createCommand($sqlspvscale)->queryScalar();
                    }
                    /*
                    $sqlgt30 = "select gt30 ";
                    $sqlgt15 = "select gt15 ";
                    $sqlgt7 = "select gt7 ";
                    $sqlgt0 = "select gt0 ";
                    $sqlx0 = "select x0 ";
                    $sqllt0 = "select lt0 ";
                    $sqllt14 = "select lt14 ";
                    $from = "from paymentscale a
                    join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
                    where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid} ";
                    if($min===null)
                    {
                        $where = " and b.max = {$max} and b.min is null";   
                    }
                    elseif($max===null)
                    {
                        $where = " and b.max is null and b.min = {$min}";
                    }
                    else
                    {
                        $where = " and b.min = {$min} and b.max = {$max} ";    
                    }

                    $komisigt30cust = Yii::app()->db->createCommand($sqlgt30.$from.$where)->queryScalar()$totalgt30cust($custscale/100)*($spvscale/100);
                    $komisigt15cust = Yii::app()->db->createCommand($sqlgt15.$from.$where)->queryScalar()$totalgt15cust($custscale/100)*($spvscale/100);
                    $komisigt7cust = Yii::app()->db->createCommand($sqlgt7.$from.$where)->queryScalar()$totalgt7cust($custscale/100)*($spvscale/100);
                    $komisigt0cust = Yii::app()->db->createCommand($sqlgt0.$from.$where)->queryScalar()$totalgt0cust($custscale/100)*($spvscale/100);
                    $komisix0cust = Yii::app()->db->createCommand($sqlx0.$from.$where)->queryScalar()$totalx0cust($custscale/100)*($spvscale/100);
                    $komisilt0cust = Yii::app()->db->createCommand($sqllt0.$from.$where)->queryScalar()$totallt0cust($custscale/100)*($spvscale/100);
                    $komisilt14cust = Yii::app()->db->createCommand($sqllt14.$from.$where)->queryScalar()$totallt14cust($custscale/100)*($spvscale/100);
                    */
                    $komisigt30cust = $gt30*$totalgt30cust*($custscale/100)*($spvscale/100);
                        $komisigt15cust = $gt15*$totalgt15cust*($custscale/100)*($spvscale/100);
                        $komisigt7cust = $gt7*$totalgt7cust*($custscale/100)*($spvscale/100);
                        $komisigt0cust = $gt0*$totalgt0cust*($custscale/100)*($spvscale/100);
                        $komisix0cust = $x0*$totalx0cust*($custscale/100)*($spvscale/100);
                        $komisilt0cust = $lt0*$totallt0cust*($custscale/100)*($spvscale/100);
                        $komisilt14cust = $lt14*$totallt14cust*($custscale/100)*($spvscale/100);
                    $totalkomisicust = $komisigt30cust + $komisigt15cust + $komisigt7cust + $komisigt0cust + $komisix0cust + $komisilt0cust + $komisilt14cust;

                    $komisigt30 += $komisigt30cust/100;
                    $komisigt15 += $komisigt15cust/100;
                    $komisigt7 += $komisigt7cust/100;
                    $komisigt0 += $komisigt0cust/100;
                    $komisix0 += $komisix0cust/100;
                    $komisilt0 += $komisilt0cust/100;
                    $komisilt14 += $komisilt14cust/100;

                    $this->pdf->coldetailalign = array('L','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
                    $this->pdf->setwidths(array(28,20,21,12,17,17,17,17,17,17,17,17,17,17,17,17,17,17,21));

                    $this->pdf->setFont('Arial','B',8);
                    $this->pdf->row(array('TOTAL KATEGORI : '.$rslt['custcategoryname'],
                    Yii::app()->format->formatCurrency($totaltargetcust/$per),
                    Yii::app()->format->formatCurrency($totalrealisasicust/$per),
                    number_format($percent,0,'.',',').' %',
                    Yii::app()->format->formatCurrency($totalgt30cust/$per),
                    Yii::app()->format->formatCurrency($totalgt15cust/$per),
                    Yii::app()->format->formatCurrency($totalgt7cust/$per),
                    Yii::app()->format->formatCurrency($totalgt0cust/$per),
                    Yii::app()->format->formatCurrency($totalx0cust/$per),
                    Yii::app()->format->formatCurrency($totallt0cust/$per),
                    Yii::app()->format->formatCurrency($totallt14cust/$per),
                    Yii::app()->format->formatCurrency((($komisigt30cust)/100)/$per),
                    Yii::app()->format->formatCurrency((($komisigt15cust)/100)/$per),
                    Yii::app()->format->formatCurrency((($komisigt7cust)/100)/$per),
                    Yii::app()->format->formatCurrency((($komisigt0cust)/100)/$per),
                    Yii::app()->format->formatCurrency((($komisix0cust)/100)/$per),
                    Yii::app()->format->formatCurrency((($komisilt0cust)/100)/$per),
                    Yii::app()->format->formatCurrency((($komisilt14cust)/100)/$per),
                    Yii::app()->format->formatCurrency(($totalkomisicust/100)/$per),
                    ));
                    
                    if($c==$rows['hitung'])
                    {
                        $c=0;
                    }
                    else
                    {
                        $c+=1;
                    }
                }
                        
                $this->pdf->setY($this->pdf->getY()+5);
                $totalkomisi = $komisigt30 + $komisigt15 + $komisigt7 + $komisigt0 + $komisix0 + $komisilt0 + $komisilt14;
                if ($totaltarget == 0){
                    $persentotal = 0;	
                }
                else
                {
                    $persentotal = ($totalrealisasi/$totaltarget)*100;
                }
                
                   $this->pdf->row(array('TOTAL SALES : '.$row['fullname'],
                    Yii::app()->format->formatCurrency($totaltarget/$per),
                    Yii::app()->format->formatCurrency($totalrealisasi/$per),
                    number_format($persentotal,0,'.',',').' %',
                    Yii::app()->format->formatCurrency($totalgt30/$per),
                    Yii::app()->format->formatCurrency($totalgt15/$per),
                    Yii::app()->format->formatCurrency($totalgt7/$per),
                    Yii::app()->format->formatCurrency($totalgt0/$per),
                    Yii::app()->format->formatCurrency($totalx0/$per),
                    Yii::app()->format->formatCurrency($totallt0/$per),
                    Yii::app()->format->formatCurrency($totallt14/$per),
                    Yii::app()->format->formatCurrency((($komisigt30))/$per),
                    Yii::app()->format->formatCurrency((($komisigt15))/$per),
                    Yii::app()->format->formatCurrency((($komisigt7))/$per),
                    Yii::app()->format->formatCurrency((($komisigt0))/$per),
                    Yii::app()->format->formatCurrency((($komisix0))/$per),
                    Yii::app()->format->formatCurrency((($komisilt0))/$per),
                    Yii::app()->format->formatCurrency((($komisilt14))/$per),
                    Yii::app()->format->formatCurrency(($totalkomisi)/$per),
                    ));
                
                    
                $this->pdf->row(array(''));
                $deposit = (($totalkomisi)*10/100)/$per;
                
                $this->pdf->setwidths(array(220,25,35,30));
                $this->pdf->colalign = array('C','R','R','L');
                $this->pdf->row(array('','Deposit 10%', Yii::app()->format->formatCurrency($deposit).' = ',Yii::app()->format->formatCurrency(($totalkomisi/$per)-$deposit)));
                $this->pdf->checkNewPage(25);
                $this->pdf->setwidths(array(50,50,70,70,70));
                $this->pdf->colalign = array('C','C','C','C','C');
                $this->pdf->row(array('Diperiksa Oleh', 'Disetujui Oleh     ', 'Mengetahui      ', 'Dibayar Oleh', 'Diterima Oleh'));
                $this->pdf->setY($this->pdf->getY()+12);
                $this->pdf->setwidths(array(50,50,70,70,70));
                $this->pdf->colalign = array('C','L','C','C','C');
                $this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR     ', 'SALES     '));
                
                $this->pdf->checkNewPage(-20);
            }
        }
        $this->pdf->Output();
    }
	//38
	public function RekapUmurPiutangDagangPerCompany($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		if ($companyid > 0){$comp=" and companyid = ".$companyid." ";}else{$comp="";}
		$sql = Yii::app()->db->createCommand("select companyname,companyid, companycode from company where recordstatus=1 and isgroup=1 {$comp} order by nourut");
		$dataReader = $sql->queryAll();
		$this->pdf->companyid = $companyid;

		$this->pdf->title='Rekap Umur Piutang Dagang All Cabang';
		$this->pdf->subtitle='Sampai Tanggal '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L',array(110,320));
		$this->pdf->AliasNbPages();

		$this->pdf->setFont('Arial','B',11);
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(15,35,15,35,15,35,15,35,15,35,15,35));
		$this->pdf->colheader = array('Kode','Umur 0-30','%','Umur 31-60','%','Umur 61-90','%','Umur 91-120','%','Umur > 120','%','Total');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R');
		
		$totsd30=0;
		$totsd60=0;
		$totsd90=0;
		$totsd120=0;
		$totup120=0;
		
		foreach($dataReader as $row)
		{
			$sql1 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120
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
											where a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$row['companyid']." and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
									where amount > payamount) zz ";
			$row1=Yii::app()->db->createCommand($sql1)->queryRow();
			
			if($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120'] == 0)
			{
					$perc0 = 0;
					$perc30 = 0;
					$perc60 = 0;
					$perc90 = 0;
					$perc120 = 0;
			}
			else
			{
					$perc0 = ($row1['0sd30']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
					$perc30 = ($row1['31sd60']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
					$perc60 = ($row1['61sd90']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
					$perc90 = ($row1['91sd120']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
					$perc120 = ($row1['up120']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
			}
			
			$this->pdf->setFont('Arial','',10);
			$this->pdf->row(array($row['companycode'],
					Yii::app()->format->formatCurrency($row1['0sd30']/$per),
					Yii::app()->format->formatCurrency($perc0 * 100),
					Yii::app()->format->formatCurrency($row1['31sd60']/$per),
					Yii::app()->format->formatCurrency($perc30 * 100),
					Yii::app()->format->formatCurrency($row1['61sd90']/$per),
					Yii::app()->format->formatCurrency($perc60 * 100),
					Yii::app()->format->formatCurrency($row1['91sd120']/$per),
					Yii::app()->format->formatCurrency($perc90 * 100),
					Yii::app()->format->formatCurrency($row1['up120']/$per),
					Yii::app()->format->formatCurrency(($perc120) * 100),
					Yii::app()->format->formatCurrency(($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120'])/$per)
			));
			
			$totsd30 += $row1['0sd30']/$per;
			$totsd60 += $row1['31sd60']/$per;
			$totsd90 += $row1['61sd90']/$per;
			$totsd120 += $row1['91sd120']/$per;
			$totup120 += $row1['up120']/$per;
			
			if($totsd30+$totsd60+$totsd90+$totsd120+$totup120 == 0)
			{
					$penc0 = 0;
					$penc30 = 0;
					$penc60 = 0;
					$penc90 = 0;
					$penc120 = 0;
			}
			else
			{
					$penc0	= ($totsd30) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
					$penc30 = ($totsd60) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
					$penc60 = ($totsd90) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
					$penc90 = ($totsd120) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
					$penc120 = ($totup120) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
			}
		}
			$this->pdf->setFont('Arial','B',10);
			$this->pdf->row(array('TOTAL ',
					Yii::app()->format->formatCurrency($totsd30),
					Yii::app()->format->formatCurrency($penc0 * 100),
					Yii::app()->format->formatCurrency($totsd60),
					Yii::app()->format->formatCurrency($penc30 * 100),
					Yii::app()->format->formatCurrency($totsd90),
					Yii::app()->format->formatCurrency($penc60 * 100),
					Yii::app()->format->formatCurrency($totsd120),
					Yii::app()->format->formatCurrency($penc90 * 100),
					Yii::app()->format->formatCurrency($totup120),
					Yii::app()->format->formatCurrency(($penc120) * 100),
					Yii::app()->format->formatCurrency(($totsd30+$totsd60+$totsd90+$totsd120+$totup120))
			));
			
		 $this->pdf->checkPageBreak(0);
		$this->pdf->Output();
	}
	public function RekapUmurPiutangDagangPerCompany_variance($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		parent::actionDownload();
		if ($companyid > 0){$comp=" and companyid = ".$companyid." ";}else{$comp="";}
		$sql = Yii::app()->db->createCommand("select companyname,companyid, companycode from company where recordstatus=1 and isgroup=1 {$comp} order by nourut");
		$dataReader = $sql->queryAll();
		$this->pdf->companyid = $companyid;

		$this->pdf->title='Rekap Umur Piutang Dagang All Cabang';
		$this->pdf->subtitle='Sampai Tanggal '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
		$this->pdf->AddPage('L',array(110,390));
		$this->pdf->AliasNbPages();

		$this->pdf->setFont('Arial','B',11);
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		$this->pdf->setwidths(array(15,35,15,35,15,35,15,35,15,35,15,35,35,35));
		$this->pdf->colheader = array('Kode','Umur 0-30','%','Umur 31-60','%','Umur 61-90','%','Umur 91-120','%','Umur > 120','%','Total','Per 31 Maret','Variance');
		$this->pdf->RowHeader();
		$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R','R','R');
		
		$totsd30=0;
		$totsd60=0;
		$totsd90=0;
		$totsd120=0;
		$totup120=0;
		
		foreach($dataReader as $row)
		{
			$sql1 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120
			
			,(select sum(a2.amount -
											ifnull((select sum((ifnull(f2.cashamount,0)+ifnull(f2.bankamount,0)+ifnull(f2.discamount,0)+ifnull(f2.returnamount,0)+ifnull(f2.obamount,0))*ifnull(f2.currencyrate,0))
													from cutarinv f2
													join cutar g2 on g2.cutarid=f2.cutarid
													where g2.recordstatus=3 and f2.invoiceid=a2.invoiceid and g2.docdate <= concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-03-31')),0)) as invoiceamount
											from invoice a2
											inner join giheader b2 on b2.giheaderid = a2.giheaderid
											inner join soheader c2 on c2.soheaderid = b2.soheaderid
											where a2.recordstatus=3 and a2.invoiceno is not null and c2.companyid = ".$row['companyid']." and a2.invoicedate <= concat(year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),'-03-31')) as total
			
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
											where a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$row['companyid']." and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
									where amount > payamount) zz ";
			$row1=Yii::app()->db->createCommand($sql1)->queryRow();
			
			if($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120'] == 0)
			{
					$perc0 = 0;
					$perc30 = 0;
					$perc60 = 0;
					$perc90 = 0;
					$perc120 = 0;
			}
			else
			{
					$perc0 = ($row1['0sd30']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
					$perc30 = ($row1['31sd60']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
					$perc60 = ($row1['61sd90']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
					$perc90 = ($row1['91sd120']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
					$perc120 = ($row1['up120']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
			}
			
			$this->pdf->setFont('Arial','',10);
			$this->pdf->row(array($row['companycode'],
					Yii::app()->format->formatCurrency($row1['0sd30']/$per),
					Yii::app()->format->formatCurrency($perc0 * 100),
					Yii::app()->format->formatCurrency($row1['31sd60']/$per),
					Yii::app()->format->formatCurrency($perc30 * 100),
					Yii::app()->format->formatCurrency($row1['61sd90']/$per),
					Yii::app()->format->formatCurrency($perc60 * 100),
					Yii::app()->format->formatCurrency($row1['91sd120']/$per),
					Yii::app()->format->formatCurrency($perc90 * 100),
					Yii::app()->format->formatCurrency($row1['up120']/$per),
					Yii::app()->format->formatCurrency(($perc120) * 100),
					Yii::app()->format->formatCurrency(($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120'])/$per),
					Yii::app()->format->formatCurrency(($row1['total'])/$per),
					Yii::app()->format->formatCurrency((($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120'])-$row1['total'])/$per),
			));
			
			$totsd30 += $row1['0sd30']/$per;
			$totsd60 += $row1['31sd60']/$per;
			$totsd90 += $row1['61sd90']/$per;
			$totsd120 += $row1['91sd120']/$per;
			$totup120 += $row1['up120']/$per;
			$tot += $row1['total']/$per;
			
			if($totsd30+$totsd60+$totsd90+$totsd120+$totup120 == 0)
			{
					$penc0 = 0;
					$penc30 = 0;
					$penc60 = 0;
					$penc90 = 0;
					$penc120 = 0;
			}
			else
			{
					$penc0	= ($totsd30) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
					$penc30 = ($totsd60) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
					$penc60 = ($totsd90) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
					$penc90 = ($totsd120) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
					$penc120 = ($totup120) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
			}
		}
			$this->pdf->setFont('Arial','B',10);
			$this->pdf->row(array('TOTAL ',
					Yii::app()->format->formatCurrency($totsd30),
					Yii::app()->format->formatCurrency($penc0 * 100),
					Yii::app()->format->formatCurrency($totsd60),
					Yii::app()->format->formatCurrency($penc30 * 100),
					Yii::app()->format->formatCurrency($totsd90),
					Yii::app()->format->formatCurrency($penc60 * 100),
					Yii::app()->format->formatCurrency($totsd120),
					Yii::app()->format->formatCurrency($penc90 * 100),
					Yii::app()->format->formatCurrency($totup120),
					Yii::app()->format->formatCurrency(($penc120) * 100),
					Yii::app()->format->formatCurrency(($totsd30+$totsd60+$totsd90+$totsd120+$totup120)),
					Yii::app()->format->formatCurrency(($tot)),
					Yii::app()->format->formatCurrency(($totsd30+$totsd60+$totsd90+$totsd120+$totup120)-$tot),
			));
			
		 $this->pdf->checkPageBreak(0);
		$this->pdf->Output();
	}
	//39
    public function RekapKomisiKasta($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
    {
        parent::actionDownload();
        $c_employeeid = getEmployeeid();
        $issales = getSalesEmployee();
        $issales == 1 ? $emp =  getStructure($c_employeeid,$c_employeeid,$companyid) : $emp = getUserObjectValues('employee');

        $arr_emp = explode(',',$c_employeeid);
        $arr = explode(',',$emp);
        if($sales!='') {
          //$exp = array_intersect($arr_emp,$arr);
          $employeeid = implode(',',$arr);
        }
        else {
          $employeeid = $emp;
        }
        $connection = Yii::app()->db;
        $this->pdf->title='REKAP UPAH TAMBAHAN SALES';
	      $this->pdf->companyid=$companyid;
        $datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

        $this->pdf->subtitle='PERIODE : '.$datetime->format('F').' '.$datetime->format('Y');

        $month = date('m',strtotime($enddate));
        $year = date('Y',strtotime($enddate));

        $prev_month_ts =  strtotime($year.'-'.$month.'-01');
        $month1 = date('Y-m-d', $prev_month_ts);

        $this->pdf->AddPage('L','F4');

        $this->pdf->sety($this->pdf->gety()+5);

        $this->pdf->text(300,15,'Per : '.$per);
        //$this->pdf->text(270,15,'X = T.O.P');
        $wheresalesarea = $whereproduct = '';
        $sqldata = "select distinct f.employeeid,f.fullname,e.companyid
        from cutarinv a
        join cutar b on b.cutarid=a.cutarid
        join invoice c on c.invoiceid=a.invoiceid
        join giheader d on d.giheaderid=c.giheaderid
        join soheader e on e.soheaderid=d.soheaderid
        join ttnt h on h.ttntid=b.ttntid
        join employee f on f.employeeid=h.employeeid
        join addressbook g on g.addressbookid=e.addressbookid
		    join employeeorgstruc i on i.employeeid=f.employeeid
		    join orgstructure j on j.orgstructureid=i.orgstructureid and j.companyid=e.companyid
        {$wheresalesarea} {$whereproduct}
        where j.structurename LIKE '%salesman%' and i.recordstatus=1 and g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
        b.recordstatus=3
        and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
        and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
        and e.isdisplay=0 and f.employeeid in({$employeeid})
        order by f.fullname asc";
        
        $data = Yii::app()->db->createCommand($sqldata)->queryAll();
        foreach($data as $row)
		    {
            $totbayar0sd50 = 0;
            $totscale0sd50 = 0;
            $totbayar50sd90 = 0;
            $totscale50sd90 = 0;
            $totbayar90sd110 = 0;
            $totscale90sd110 = 0;
            $totbayar110sd150 = 0;
            $totscale110sd150 = 0;
            $totbayarsd150 = 0;
            $totscalesd150 = 0;
            $totjumlahbayar = 0;
            $totscalejumlah = 0;
			
			$sqlsaldoup120 = "select sum(a5) as up120
				from (select case when umur > 120 then amount-payamount else 0 end as a5
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
						where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
						and ff.fullname like '%".$sales."%'  and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and c.employeeid = {$row['employeeid']}
						-- and d.custcategoryid = {cust['custcategoryid']}
						) z
					where amount > payamount) zz";
			$saldo120 = Yii::app()->db->createCommand($sqlsaldoup120)->queryScalar();
          
            $sqlcustcategory = "select i.custcategoryid,i.custcategoryname
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            join invoice c on c.invoiceid=a.invoiceid
            join giheader d on d.giheaderid=c.giheaderid
            join soheader e on e.soheaderid=d.soheaderid
            join ttnt h on h.ttntid=b.ttntid
            join employee f on f.employeeid=h.employeeid
            join addressbook g on g.addressbookid=e.addressbookid
            join custcategory i on i.custcategoryid = g.custcategoryid
            {$wheresalesarea} {$whereproduct}
            where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
			and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
			and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
            and e.companyid = {$companyid} and f.employeeid = {$row['employeeid']}
            and e.isdisplay=0
            group by g.custcategoryid
            order by g.custcategoryid asc";
            $this->pdf->SetFont('Arial','B',9);
            $this->pdf->text(10,$this->pdf->getY(),'NAMA SALES : '.$row['fullname']);
			$this->pdf->sety($this->pdf->gety()+3);
            $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();

                    $this->pdf->setFont('Arial','',9);
                    $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
                    $this->pdf->setwidths(array(30,47,47,47,47,47,48));
                    $this->pdf->colheader = array("          Period                       ",'            <= 50%TOP             120%','   50%TOP < U <= 90%TOP    110%','90%TOP < U <= 110%TOP    100%','110%TOP < U <= 150%TOP    90%','              U > 150%TOP                0%','                  Jumlah                               ');
                    $this->pdf->RowHeader();

                    $this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
                    $this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
                    $this->pdf->SetFont('Arial','',9);
                    $this->pdf->row(array('','NIlai Bayar','UT','Nilai Bayar','UT','NIlai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT'));
					
            foreach($custcategory as $cust)
			{
				$subbayar0sd50 = 0;
				$subscale0sd50 = 0;
				$subbayar50sd90 = 0;
				$subscale50sd90 = 0;
				$subbayar90sd110 = 0;
				$subscale90sd110 = 0;
				$subbayar110sd150 = 0;
				$subscale110sd150 = 0;
				$subbayarsd150 = 0;
				$subscalesd150 = 0;
				$subjumlahbayar = 0;
				$subscalejumlah = 0;
                          
                $this->pdf->setFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory'). ' = '. $cust['custcategoryname']);
                $subjumlahut = 0;
                $this->pdf->SetFont('Arial','B',9);

                $sqlperiod = "select period, invoicedate from (select c.invoiceno,c.invoicedate,b.docdate,setdateformat(c.invoicedate) as period, a.cashamount+a.bankamount as nilai
                from cutarinv a
                join cutar b on b.cutarid=a.cutarid
                join invoice c on c.invoiceid=a.invoiceid
                join giheader d on d.giheaderid=c.giheaderid
                join soheader e on e.soheaderid=d.soheaderid
                join ttnt h on h.ttntid=b.ttntid
                join employee f on f.employeeid=h.employeeid
                join addressbook g on g.addressbookid=e.addressbookid
                {$wheresalesarea} {$whereproduct}
                where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
				and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
                and e.companyid = {$companyid} and f.employeeid = {$row['employeeid']}
                and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
                order by invoicedate asc ) z
                where nilai <> 0
                group by month(invoicedate),year(invoicedate)
				order by invoicedate asc";
                $this->pdf->setY($this->pdf->getY()+7);
                $period = Yii::app()->db->createCommand($sqlperiod)->queryAll();
					
                foreach($period as $row1) 
				{
					$custbayar0sd50 = 0;
					$custscale0sd50 = 0;
					$custbayar50sd90 = 0;
					$custscale50sd90 = 0;
					$custbayar90sd110 = 0;
					$custscale90sd110 = 0;
					$custbayar110sd150 = 0;
					$custscale110sd150 = 0;
					$custbayarsd150 = 0;
					$custscalesd150 = 0;
					$custjumlahbayar = 0;
					$custscalejumlah = 0;
					
                    $sqlawalbulan = "select date_add(date_add(last_day('{$row1['invoicedate']}'),interval 1 day), interval -1 month) as awalbulan";
                    $tglawal = Yii::app()->db->createCommand($sqlawalbulan)->queryScalar();
					$percentperiod = Yii::app()->db->createCommand("select ifnull(scalevalue,0) from scalevalue where recordstatus=5 and custcategoryid = {$cust['custcategoryid']} and employeeid = {$row['employeeid']} and perioddate = '{$tglawal}'")->queryScalar();
                    

                    $sqldet = "select *,
								case when umur >= 0 and umur <= (0.5 * paydays) then nilaibayar else 0 end as 0sd50,
								case when umur > (0.5 * paydays) and umur <= (0.9 * paydays) then nilaibayar else 0 end AS 50sd90,
								case when umur > (0.9 * paydays) and umur <= (1.1 * paydays) then nilaibayar else 0 end AS 90sd110,
								case when umur > (1.1 * paydays) and umur <= (1.5 * paydays) then nilaibayar else 0 end AS 110sd150,
								case when umur > (1.5 * paydays) then nilaibayar else 0 end as sd150
								,(0.5 * paydays),(0.9 * paydays),(paydays),(1.5 * paydays)
								from (select distinct g.fullname,c.invoiceno,c.invoicedate,b.docdate AS cutardate,datediff(b.docdate,c.invoicedate) as umur,p.paydays,c.amount,
								a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
								(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
								join invoice c on c.invoiceid=a.invoiceid
								join giheader d on d.giheaderid=c.giheaderid
								join soheader e on e.soheaderid=d.soheaderid
								join ttnt i on i.ttntid=b.ttntid
								join employee f on f.employeeid=i.employeeid
								join addressbook g on g.addressbookid=e.addressbookid
								join paymentmethod p ON p.paymentmethodid=e.paymentmethodid
								{$wheresalesarea} {$whereproduct}
								where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
								and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and e.companyid = {$companyid}
								and f.employeeid = ".$row['employeeid']."
								and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
								) z
								-- where z.materialgroupid = ''
								where month(invoicedate) = month('{$row1['invoicedate']}')
								and year(invoicedate) = year('{$row1['invoicedate']}')
								and nilaibayar <> 0 
								order by cutardate,fullname,invoicedate";
					$detail = Yii::app()->db->createCommand($sqldet)->queryAll();
                    $this->pdf->setFont('Arial','',8);
                    foreach($detail as $row2)
					{
                        //$this->pdf->row(array('','NIlai INV','UT','Nilai INV','UT','NIlai INV','UT','Nilai INV','UT','Nilai INV','UT',''));
                        $jumlahbayar = ($row2['0sd50'] + $row2['50sd90'] + $row2['90sd110'] + $row2['110sd150'] + $row2['sd150'])/$per;
                        $jumlahut = (($row2['0sd50']*$percentperiod*1.2)+($row2['50sd90']*$percentperiod*1.1)+($row2['90sd110']*$percentperiod*1)+
                                    ($row2['110sd150']*$percentperiod*0.9))/100/$per;
                    /*    $this->pdf->row(array(
                          $row2['invoiceno'],
                          Yii::app()->format->formatCurrency($row2['0sd50']),
                          Yii::app()->format->formatCurrency(($row2['0sd50']*$percentperiod*1.2)/100),
                          Yii::app()->format->formatCurrency($row2['50sd90']),
                          Yii::app()->format->formatCurrency(($row2['50sd90']*$percentperiod*1.1)/100),
                          Yii::app()->format->formatCurrency($row2['90sd110']),
                          Yii::app()->format->formatCurrency(($row2['90sd110']*$percentperiod*1)/100),
                          Yii::app()->format->formatCurrency($row2['110sd150']),
                          Yii::app()->format->formatCurrency(($row2['110sd150']*$percentperiod*0.9)/100),
                          Yii::app()->format->formatCurrency($row2['sd150']),
                          Yii::app()->format->formatCurrency(0),
                          Yii::app()->format->formatCurrency($jumlahbayar),
                          Yii::app()->format->formatCurrency($jumlahut)
                        ));*/
                        $custbayar0sd50 += $row2['0sd50']/$per;
                        $custscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $custbayar50sd90 += $row2['50sd90']/$per;
                        $custscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $custbayar90sd110 += $row2['90sd110']/$per;
                        $custscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $custbayar110sd150 += $row2['110sd150']/$per;
                        $custscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $custbayarsd150 += $row2['sd150']/$per;
                        $custscalesd150 = 0;
                        $custjumlahbayar += $jumlahbayar;
                        $custscalejumlah += $jumlahut;
                      
                        $subbayar0sd50 += $row2['0sd50']/$per;
                        $subscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $subbayar50sd90 += $row2['50sd90']/$per;
                        $subscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $subbayar90sd110 += $row2['90sd110']/$per;
                        $subscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $subbayar110sd150 += $row2['110sd150']/$per;
                        $subscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $subbayarsd150 += $row2['sd150']/$per;
                        $subscalesd150 = 0;
                        $subjumlahbayar += $jumlahbayar;
                        $subscalejumlah += $jumlahut;
                      
                        $totbayar0sd50 += $row2['0sd50']/$per;
                        $totscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $totbayar50sd90 += $row2['50sd90']/$per;
                        $totscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $totbayar90sd110 += $row2['90sd110']/$per;
                        $totscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $totbayar110sd150 += $row2['110sd150']/$per;
                        $totscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $totbayarsd150 += $row2['sd150']/$per;
                        $totscalesd150 = 0;
                        $totjumlahbayar += $jumlahbayar;
                        $totscalejumlah += $jumlahut;

                        //$totjumlahut = $totjumlahut + $jumlahut;
                    }
					//$this->pdf->setFont('Arial','B',8);
					$this->pdf->row(array(
						$row1['period'].' - ('.Yii::app()->format->formatCurrency($percentperiod).'% )',
						Yii::app()->format->formatCurrency($custbayar0sd50),
						Yii::app()->format->formatCurrency($custscale0sd50),
						Yii::app()->format->formatCurrency($custbayar50sd90),
						Yii::app()->format->formatCurrency($custscale50sd90),
						Yii::app()->format->formatCurrency($custbayar90sd110),
						Yii::app()->format->formatCurrency($custscale90sd110),
						Yii::app()->format->formatCurrency($custbayar110sd150),
						Yii::app()->format->formatCurrency($custscale110sd150),
						Yii::app()->format->formatCurrency($custbayarsd150),
						Yii::app()->format->formatCurrency($custscalesd150),
						Yii::app()->format->formatCurrency($custjumlahbayar),
						Yii::app()->format->formatCurrency($custscalejumlah)
					));
                    //$this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R');
                }
				$this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
				$this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->coldetailalign = array('L','R','R');
				//$this->pdf->setwidths(array(80,165,35));
				$this->pdf->row(array('JUMLAH '. strtoupper($cust['custcategoryname']),
					Yii::app()->format->formatCurrency($subbayar0sd50),
					Yii::app()->format->formatCurrency($subscale0sd50),
					Yii::app()->format->formatCurrency($subbayar50sd90),
					Yii::app()->format->formatCurrency($subscale50sd90),
					Yii::app()->format->formatCurrency($subbayar90sd110),
					Yii::app()->format->formatCurrency($subscale90sd110),
					Yii::app()->format->formatCurrency($subbayar110sd150),
					Yii::app()->format->formatCurrency($subscale110sd150),
					Yii::app()->format->formatCurrency($subbayarsd150),
					Yii::app()->format->formatCurrency($subscalesd150),
					Yii::app()->format->formatCurrency($subjumlahbayar),
					Yii::app()->format->formatCurrency($subscalejumlah)
				));
				$this->pdf->setY($this->pdf->getY()+5);
            }
			$this->pdf->checkNewPage(60);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->row(array('TOTAL SALES ',
				Yii::app()->format->formatCurrency($totbayar0sd50),
				Yii::app()->format->formatCurrency($totscale0sd50),
				Yii::app()->format->formatCurrency($totbayar50sd90),
				Yii::app()->format->formatCurrency($totscale50sd90),
				Yii::app()->format->formatCurrency($totbayar90sd110),
				Yii::app()->format->formatCurrency($totscale90sd110),
				Yii::app()->format->formatCurrency($totbayar110sd150),
				Yii::app()->format->formatCurrency($totscale110sd150),
				Yii::app()->format->formatCurrency($totbayarsd150),
				Yii::app()->format->formatCurrency($totscalesd150),
				Yii::app()->format->formatCurrency($totjumlahbayar),
				Yii::app()->format->formatCurrency($totscalejumlah)
			));
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->coldetailalign = array('L','R','R');
            $this->pdf->setwidths(array(165,100,35));
			$saldo120per = $saldo120/$per;
            $this->pdf->row(array('TOTAL UPAH TAMBAHAN','',Yii::app()->format->formatCurrency($totscalejumlah)));
            $this->pdf->row(array('DEPOSIT 10%','',Yii::app()->format->formatCurrency(($totscalejumlah*-0.1))));
            $this->pdf->row(array('SALDO UMUR PIUTANG > 120 HARI (-0.25%)',Yii::app()->format->formatCurrency($saldo120per),
            Yii::app()->format->formatCurrency(($saldo120per*-0.25)/100)));
			
			$sqlmonth = "SELECT MONTH('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')";
			$month=$connection->createCommand($sqlmonth)->queryScalar();
			
			if ($month == 3 or $month == 6 or $month == 9 or $month == 12)
			{
				$sqldateminus3months = "SELECT LAST_DAY(DATE_ADD(DATE('".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),INTERVAL -3 MONTH))";
				$enddateminus3months=$connection->createCommand($sqldateminus3months)->queryScalar();
				
				$sqlsaldoup120minus3months = "select sum(a5) as up120
					from (select case when umur > 120 then amount-payamount else 0 end as a5
						from (select a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddateminus3months))."',a.invoicedate) as umur,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
								from cutarinv f
								join cutar g on g.cutarid=f.cutarid
								where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddateminus3months))."'),0) as payamount
							from invoice a
							inner join giheader b on b.giheaderid = a.giheaderid
							inner join soheader c on c.soheaderid = b.soheaderid
							inner join addressbook d on d.addressbookid = c.addressbookid
							inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
							inner join employee ff on ff.employeeid = c.employeeid
							where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
							and ff.fullname like '%".$sales."%'  and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddateminus3months))."'
							and c.employeeid = {$row['employeeid']}
							-- and d.custcategoryid = {cust['custcategoryid']}
							) z
						where amount > payamount) zz";
				$saldo120minus3months = Yii::app()->db->createCommand($sqlsaldoup120minus3months)->queryScalar();
				$saldo120minus3monthsper = $saldo120minus3months/$per;
				
				$sqlsaldoakhir = "select ifnull(sum(debit-credit)*-1,0)
						from (select case when b.accountname = 'HUTANG FINALTY TAGIHAN SALES / SPV' then a.amount else 0 end as debit,
						case when c.accountname = 'HUTANG FINALTY TAGIHAN SALES / SPV' then a.amount else 0 end as credit, e.fullname, e.employeeid
						from cbacc a
						join account b on b.accountid = a.debitaccid
						join account c on c.accountid = a.creditaccid
						join cb d on d.cbid = a.cbid
						join employee e on e.employeeid = a.employeeid
						where d.recordstatus = 3 and d.companyid=".$companyid."
						and d.docdate <= CAST('".date(Yii::app()->params['datetodb'], strtotime($enddate))."' AS DATE) and a.employeeid = ".$row['employeeid'].") z
						where debit <> 0 or credit <> 0";
				$saldoakhir = $connection->createCommand($sqlsaldoakhir)->queryScalar();
				$saldoakhirper = $saldoakhir/$per;
				
				$this->pdf->setFont('Arial','',9);
				$this->pdf->row(array('          - Saldo Umur Piutang > 120 Hari (3 Bulan Lalu)',Yii::app()->format->formatCurrency($saldo120minus3monthsper),''));
				$this->pdf->row(array('          - Saldo Hutang Finalty Tagihan Sales / SPV Per '.$datetime->format('F').' '.$datetime->format('Y'),Yii::app()->format->formatCurrency($saldoakhirper),''));
				if(($saldo120per - $saldo120minus3monthsper) < 0){$persenfinalty = (($saldo120per - $saldo120minus3monthsper) / $saldo120minus3monthsper) * 100;}else{$persenfinalty = 0;}
				$this->pdf->row(array('          - Saldo Umur Piutang > 120 Hari - Bertambah / (Berkurang)',Yii::app()->format->formatCurrency($saldo120per).'  -  '.Yii::app()->format->formatCurrency($saldo120minus3monthsper).'  =  '.Yii::app()->format->formatCurrency($saldo120per - $saldo120minus3monthsper).'   '.Yii::app()->format->formatCurrency($persenfinalty).'%',''));
				$this->pdf->setFont('Arial','B',9);
				$this->pdf->row(array('SALDO HUTANG FINALTY TAGIHAN SALES / SPV YANG DICAIRKAN ',Yii::app()->format->formatCurrency($saldoakhirper).' x '.Yii::app()->format->formatCurrency(-1*$persenfinalty).'%',Yii::app()->format->formatCurrency($saldoakhirper*-1*$persenfinalty/100)));
				$cairfinalty = $saldoakhirper*-1*$persenfinalty/100;
			}
			else
			{
				$cairfinalty = 0;
			}
			
            $upah = $totscalejumlah+($totscalejumlah*-0.1)+($saldo120per*-0.25/100)+$cairfinalty;
            $this->pdf->row(array('UPAH TAMBAHAN YANG DITERIMA SALES '.strtoupper($row['fullname']),'',Yii::app()->format->formatCurrency($upah)));
            //$this->pdf->text(10,$this->pdf->getY(),'SALDO UMUR PIUTANG > 120 HARI');
            $this->pdf->setY($this->pdf->getY()+5);
			
            $this->pdf->setFont('Arial','',9);
			$this->pdf->setwidths(array(63,63,63,63,63));
			$this->pdf->coldetailalign = array('C','C','C','C','C');
			$this->pdf->row(array('Diperiksa Oleh,', 'Disetujui Oleh,', 'Diketahui Oleh,', 'Dibayar Oleh,', 'Diterima Oleh,'));
			$this->pdf->setY($this->pdf->getY()+20);
			$this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR', strtoupper($row['fullname'])));
			$this->pdf->checkNewPage(120);
            
        }
        $this->pdf->Output();
    }
	public function RekapKomisiKasta_lama3($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
    {
        parent::actionDownload();
        $connection = Yii::app()->db;
        $this->pdf->title='REKAP UPAH TAMBAHAN SALES';
	  $this->pdf->companyid=$companyid;
        $datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

        $this->pdf->subtitle='PERIODE : '.$datetime->format('F').' '.$datetime->format('Y');

        $month = date('m',strtotime($enddate));
        $year = date('Y',strtotime($enddate));

        $prev_month_ts =  strtotime($year.'-'.$month.'-01');
        $month1 = date('Y-m-d', $prev_month_ts);

        $this->pdf->AddPage('L','F4');

        $this->pdf->sety($this->pdf->gety()+5);

        $this->pdf->text(300,15,'Per : '.$per);
        //$this->pdf->text(270,15,'X = T.O.P');
        $wheresalesarea = $whereproduct = '';
        $sqldata = "select distinct f.employeeid,f.fullname,e.companyid
        from cutarinv a
        join cutar b on b.cutarid=a.cutarid
        join invoice c on c.invoiceid=a.invoiceid
        join giheader d on d.giheaderid=c.giheaderid
        join soheader e on e.soheaderid=d.soheaderid
        join ttnt h on h.ttntid=b.ttntid
        join employee f on f.employeeid=h.employeeid
        join addressbook g on g.addressbookid=e.addressbookid
		join employeeorgstruc i on i.employeeid=f.employeeid
		join orgstructure j on j.orgstructureid=i.orgstructureid and j.companyid=e.companyid
        {$wheresalesarea} {$whereproduct}
        where j.structurename LIKE '%salesman%' and i.recordstatus=1 and g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
        b.recordstatus=3
		and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
		and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
        and e.isdisplay=0
        order by f.fullname asc";
        
        $data = Yii::app()->db->createCommand($sqldata)->queryAll();
        foreach($data as $row)
		{
            $totbayar0sd50 = 0;
            $totscale0sd50 = 0;
            $totbayar50sd90 = 0;
            $totscale50sd90 = 0;
            $totbayar90sd110 = 0;
            $totscale90sd110 = 0;
            $totbayar110sd150 = 0;
            $totscale110sd150 = 0;
            $totbayarsd150 = 0;
            $totscalesd150 = 0;
            $totjumlahbayar = 0;
            $totscalejumlah = 0;
			
			$sqlsaldoup120 = "select sum(a5) as up120
				from (select case when umur > 120 then amount-payamount else 0 end as a5
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
						where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
						and ff.fullname like '%".$sales."%'  and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and c.employeeid = {$row['employeeid']}
						-- and d.custcategoryid = {$cust['custcategoryid']}
						) z
					where amount > payamount) zz";
			$saldo120 = Yii::app()->db->createCommand($sqlsaldoup120)->queryScalar();
          
            $sqlcustcategory = "select i.custcategoryid,i.custcategoryname
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            join invoice c on c.invoiceid=a.invoiceid
            join giheader d on d.giheaderid=c.giheaderid
            join soheader e on e.soheaderid=d.soheaderid
            join ttnt h on h.ttntid=b.ttntid
            join employee f on f.employeeid=h.employeeid
            join addressbook g on g.addressbookid=e.addressbookid
            join custcategory i on i.custcategoryid = g.custcategoryid
            {$wheresalesarea} {$whereproduct}
            where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
			and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
			and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
            and e.companyid = {$companyid} and f.employeeid = {$row['employeeid']}
            and e.isdisplay=0
            group by g.custcategoryid
            order by g.custcategoryid asc";
            $this->pdf->SetFont('Arial','B',9);
            $this->pdf->text(10,$this->pdf->getY(),'NAMA SALES : '.$row['fullname']);
			$this->pdf->sety($this->pdf->gety()+3);
            $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();

                    $this->pdf->setFont('Arial','',9);
                    $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
                    $this->pdf->setwidths(array(30,47,47,47,47,47,48));
                    $this->pdf->colheader = array("          Period                       ",'            <= 50%TOP             120%','   50%TOP < U <= 90%TOP    110%','90%TOP < U <= 110%TOP    100%','110%TOP < U <= 150%TOP    90%','              U > 150%TOP                0%','                  Jumlah                               ');
                    $this->pdf->RowHeader();

                    $this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
                    $this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
                    $this->pdf->SetFont('Arial','',9);
                    $this->pdf->row(array($row1['period'],'NIlai Bayar','UT','Nilai Bayar','UT','NIlai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT'));
					
            foreach($custcategory as $cust)
			{
				$subbayar0sd50 = 0;
				$subscale0sd50 = 0;
				$subbayar50sd90 = 0;
				$subscale50sd90 = 0;
				$subbayar90sd110 = 0;
				$subscale90sd110 = 0;
				$subbayar110sd150 = 0;
				$subscale110sd150 = 0;
				$subbayarsd150 = 0;
				$subscalesd150 = 0;
				$subjumlahbayar = 0;
				$subscalejumlah = 0;
                          
                $this->pdf->setFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory'). ' = '. $cust['custcategoryname']);
                $subjumlahut = 0;
                $this->pdf->SetFont('Arial','B',9);

                $sqlperiod = "select period, invoicedate from (select c.invoiceno,c.invoicedate,b.docdate,setdateformat(c.invoicedate) as period, a.cashamount+a.bankamount as nilai
                from cutarinv a
                join cutar b on b.cutarid=a.cutarid
                join invoice c on c.invoiceid=a.invoiceid
                join giheader d on d.giheaderid=c.giheaderid
                join soheader e on e.soheaderid=d.soheaderid
                join ttnt h on h.ttntid=b.ttntid
                join employee f on f.employeeid=h.employeeid
                join addressbook g on g.addressbookid=e.addressbookid
                {$wheresalesarea} {$whereproduct}
                where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
				and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
                and e.companyid = {$companyid} and f.employeeid = {$row['employeeid']}
                and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
                order by invoicedate asc ) z
                where nilai <> 0
                group by month(invoicedate),year(invoicedate)
				order by invoicedate asc";
                $this->pdf->setY($this->pdf->getY()+7);
                $period = Yii::app()->db->createCommand($sqlperiod)->queryAll();
					
                foreach($period as $row1) 
				{
					$custbayar0sd50 = 0;
					$custscale0sd50 = 0;
					$custbayar50sd90 = 0;
					$custscale50sd90 = 0;
					$custbayar90sd110 = 0;
					$custscale90sd110 = 0;
					$custbayar110sd150 = 0;
					$custscale110sd150 = 0;
					$custbayarsd150 = 0;
					$custscalesd150 = 0;
					$custjumlahbayar = 0;
					$custscalejumlah = 0;
					
                    $sqlawalbulan = "select date_add(date_add(last_day('{$row1['invoicedate']}'),interval 1 day), interval -1 month) as awalbulan";
                    $tglawal = Yii::app()->db->createCommand($sqlawalbulan)->queryScalar();
					$percentperiod = Yii::app()->db->createCommand("select ifnull(scalevalue,0) from scalevalue where recordstatus = 5 and custcategoryid = {$cust['custcategoryid']} and employeeid = {$row['employeeid']}
                      and perioddate = '{$tglawal}'")->queryScalar();

                    $sqldet = "select *,
								case when umur >= 0 and umur <= (0.5 * paydays) then nilaibayar else 0 end as 0sd50,
								case when umur > (0.5 * paydays) and umur <= (0.9 * paydays) then nilaibayar else 0 end AS 50sd90,
								case when umur > (0.9 * paydays) and umur <= (1.1 * paydays) then nilaibayar else 0 end AS 90sd110,
								case when umur > (1.1 * paydays) and umur <= (1.5 * paydays) then nilaibayar else 0 end AS 110sd150,
								case when umur > (1.5 * paydays) then nilaibayar else 0 end as sd150
								,(0.5 * paydays),(0.9 * paydays),(paydays),(1.5 * paydays)
								from (select distinct g.fullname,c.invoiceno,c.invoicedate,b.docdate AS cutardate,datediff(b.docdate,c.invoicedate) as umur,p.paydays,c.amount,
								a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
								(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
								join invoice c on c.invoiceid=a.invoiceid
								join giheader d on d.giheaderid=c.giheaderid
								join soheader e on e.soheaderid=d.soheaderid
								join ttnt i on i.ttntid=b.ttntid
								join employee f on f.employeeid=i.employeeid
								join addressbook g on g.addressbookid=e.addressbookid
								join paymentmethod p ON p.paymentmethodid=e.paymentmethodid
								{$wheresalesarea} {$whereproduct}
								where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
								and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and e.companyid = {$companyid}
								and f.employeeid = ".$row['employeeid']."
								and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
								) z
								-- where z.materialgroupid = ''
								where month(invoicedate) = month('{$row1['invoicedate']}')
								and year(invoicedate) = year('{$row1['invoicedate']}')
								and nilaibayar <> 0 
								order by cutardate,fullname,invoicedate";
					$detail = Yii::app()->db->createCommand($sqldet)->queryAll();
                    $this->pdf->setFont('Arial','',8);
                    foreach($detail as $row2)
					{
                        //$this->pdf->row(array('','NIlai INV','UT','Nilai INV','UT','NIlai INV','UT','Nilai INV','UT','Nilai INV','UT',''));
                        $jumlahbayar = ($row2['0sd50'] + $row2['50sd90'] + $row2['90sd110'] + $row2['110sd150'] + $row2['sd150'])/$per;
                        $jumlahut = (($row2['0sd50']*$percentperiod*1.2)+($row2['50sd90']*$percentperiod*1.1)+($row2['90sd110']*$percentperiod*1)+
                                    ($row2['110sd150']*$percentperiod*0.9))/100/$per;
                    /*    $this->pdf->row(array(
                          $row2['invoiceno'],
                          Yii::app()->format->formatCurrency($row2['0sd50']),
                          Yii::app()->format->formatCurrency(($row2['0sd50']*$percentperiod*1.2)/100),
                          Yii::app()->format->formatCurrency($row2['50sd90']),
                          Yii::app()->format->formatCurrency(($row2['50sd90']*$percentperiod*1.1)/100),
                          Yii::app()->format->formatCurrency($row2['90sd110']),
                          Yii::app()->format->formatCurrency(($row2['90sd110']*$percentperiod*1)/100),
                          Yii::app()->format->formatCurrency($row2['110sd150']),
                          Yii::app()->format->formatCurrency(($row2['110sd150']*$percentperiod*0.9)/100),
                          Yii::app()->format->formatCurrency($row2['sd150']),
                          Yii::app()->format->formatCurrency(0),
                          Yii::app()->format->formatCurrency($jumlahbayar),
                          Yii::app()->format->formatCurrency($jumlahut)
                        ));*/
                        $custbayar0sd50 += $row2['0sd50']/$per;
                        $custscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $custbayar50sd90 += $row2['50sd90']/$per;
                        $custscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $custbayar90sd110 += $row2['90sd110']/$per;
                        $custscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $custbayar110sd150 += $row2['110sd150']/$per;
                        $custscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $custbayarsd150 += $row2['sd150']/$per;
                        $custscalesd150 = 0;
                        $custjumlahbayar += $jumlahbayar;
                        $custscalejumlah += $jumlahut;
                      
                        $subbayar0sd50 += $row2['0sd50']/$per;
                        $subscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $subbayar50sd90 += $row2['50sd90']/$per;
                        $subscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $subbayar90sd110 += $row2['90sd110']/$per;
                        $subscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $subbayar110sd150 += $row2['110sd150']/$per;
                        $subscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $subbayarsd150 += $row2['sd150']/$per;
                        $subscalesd150 = 0;
                        $subjumlahbayar += $jumlahbayar;
                        $subscalejumlah += $jumlahut;
                      
                        $totbayar0sd50 += $row2['0sd50']/$per;
                        $totscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $totbayar50sd90 += $row2['50sd90']/$per;
                        $totscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $totbayar90sd110 += $row2['90sd110']/$per;
                        $totscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $totbayar110sd150 += $row2['110sd150']/$per;
                        $totscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $totbayarsd150 += $row2['sd150']/$per;
                        $totscalesd150 = 0;
                        $totjumlahbayar += $jumlahbayar;
                        $totscalejumlah += $jumlahut;
                      
                        $totjumlahut = $totjumlahut + $jumlahut;
                    }
					//$this->pdf->setFont('Arial','B',8);
					$this->pdf->row(array(
						$row1['period'].' - ('.Yii::app()->format->formatCurrency($percentperiod).'% )',
						Yii::app()->format->formatCurrency($custbayar0sd50),
						Yii::app()->format->formatCurrency($custscale0sd50),
						Yii::app()->format->formatCurrency($custbayar50sd90),
						Yii::app()->format->formatCurrency($custscale50sd90),
						Yii::app()->format->formatCurrency($custbayar90sd110),
						Yii::app()->format->formatCurrency($custscale90sd110),
						Yii::app()->format->formatCurrency($custbayar110sd150),
						Yii::app()->format->formatCurrency($custscale110sd150),
						Yii::app()->format->formatCurrency($custbayarsd150),
						Yii::app()->format->formatCurrency($custscalesd150),
						Yii::app()->format->formatCurrency($custjumlahbayar),
						Yii::app()->format->formatCurrency($custscalejumlah)
					));
                    //$this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R');
                }
				$this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
				$this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->coldetailalign = array('L','R','R');
				//$this->pdf->setwidths(array(80,165,35));
				$this->pdf->row(array('JUMLAH '.strtoupper($cust['custcategoryname']),
					Yii::app()->format->formatCurrency($subbayar0sd50),
					Yii::app()->format->formatCurrency($subscale0sd50),
					Yii::app()->format->formatCurrency($subbayar50sd90),
					Yii::app()->format->formatCurrency($subscale50sd90),
					Yii::app()->format->formatCurrency($subbayar90sd110),
					Yii::app()->format->formatCurrency($subscale90sd110),
					Yii::app()->format->formatCurrency($subbayar110sd150),
					Yii::app()->format->formatCurrency($subscale110sd150),
					Yii::app()->format->formatCurrency($subbayarsd150),
					Yii::app()->format->formatCurrency($subscalesd150),
					Yii::app()->format->formatCurrency($subjumlahbayar),
					Yii::app()->format->formatCurrency($subscalejumlah)
				));
				$this->pdf->setY($this->pdf->getY()+5);
            }
			$this->pdf->checkNewPage(60);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->row(array('TOTAL SALES ',
				Yii::app()->format->formatCurrency($totbayar0sd50),
				Yii::app()->format->formatCurrency($totscale0sd50),
				Yii::app()->format->formatCurrency($totbayar50sd90),
				Yii::app()->format->formatCurrency($totscale50sd90),
				Yii::app()->format->formatCurrency($totbayar90sd110),
				Yii::app()->format->formatCurrency($totscale90sd110),
				Yii::app()->format->formatCurrency($totbayar110sd150),
				Yii::app()->format->formatCurrency($totscale110sd150),
				Yii::app()->format->formatCurrency($totbayarsd150),
				Yii::app()->format->formatCurrency($totscalesd150),
				Yii::app()->format->formatCurrency($totjumlahbayar),
				Yii::app()->format->formatCurrency($totscalejumlah)
			));
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->coldetailalign = array('L','R','R');
            $this->pdf->setwidths(array(165,100,35));
			$saldo120per = $saldo120/$per;
            $this->pdf->row(array('SALDO UMUR PIUTANG > 120 HARI (-0.5%)',Yii::app()->format->formatCurrency($saldo120per),
                                  Yii::app()->format->formatCurrency(($saldo120per*-0.5)/100)));
            $this->pdf->row(array('TOTAL UPAH TAMBAHAN','',Yii::app()->format->formatCurrency($totscalejumlah+(($saldo120per*-0.5)/100))));
            $this->pdf->row(array('DEPOSIT 10%','',Yii::app()->format->formatCurrency(($totscalejumlah+(($saldo120per*-0.5)/100))*-0.1)));
            $upah = ($totscalejumlah+(($saldo120per*-0.5)/100))*0.9;
            $this->pdf->row(array('UPAH TAMBAHAN YANG DITERIMA SALES '.strtoupper($row['fullname']),'',Yii::app()->format->formatCurrency($upah)));
            //$this->pdf->text(10,$this->pdf->getY(),'SALDO UMUR PIUTANG > 120 HARI');
            $this->pdf->setY($this->pdf->getY()+5);
			
            $this->pdf->setFont('Arial','',9);
			$this->pdf->setwidths(array(63,63,63,63,63));
			$this->pdf->coldetailalign = array('C','C','C','C','C');
			$this->pdf->row(array('Diperiksa Oleh,', 'Disetujui Oleh,', 'Diketahui Oleh,', 'Dibayar Oleh,', 'Diterima Oleh,'));
			$this->pdf->setY($this->pdf->getY()+20);
			$this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR', strtoupper($row['fullname'])));
			$this->pdf->checkNewPage(120);
            
        }
        $this->pdf->Output();
    }
	//40
	public function RincianKomisiTagihanPerSPVPerSales($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
    {
        parent::actionDownload();
        $connection = Yii::app()->db;
        $this->pdf->title='RINCIAN UPAH TAMBAHAN SPV SALES';
	  $this->pdf->companyid=$companyid;
        $datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));

        $this->pdf->subtitle='PERIODE : '.$datetime->format('F').' '.$datetime->format('Y');

        $month = date('m',strtotime($enddate));
        $year = date('Y',strtotime($enddate));

        $prev_month_ts =  strtotime($year.'-'.$month.'-01');
        $month1 = date('Y-m-d', $prev_month_ts);
		
		$spvscale = Yii::app()->db->createCommand("select ifnull(spvscale/100,0) from scale where recordstatus = 5 order by docdate desc")->queryScalar();

        $this->pdf->AddPage('L','F4');

        $this->pdf->text(300,15,'Per : '.$per);
        //$this->pdf->text(270,15,'X = T.O.P');
        $wheresalesarea = $whereproduct = '';
        $sqldata = "select distinct l.employeeid as spvid,l.fullname,b.companyid,GROUP_CONCAT(DISTINCT f.employeeid) as employeeid
        from cutarinv a
        join cutar b on b.cutarid=a.cutarid
        join invoice c on c.invoiceid=a.invoiceid
        join giheader d on d.giheaderid=c.giheaderid
        join soheader e on e.soheaderid=d.soheaderid
        join ttnt h on h.ttntid=b.ttntid
        join employeeorgstruc f on f.employeeid=h.employeeid
        join orgstructure i ON i.orgstructureid=f.orgstructureid
        join orgstructure j ON j.orgstructureid=i.parentid
        join employeeorgstruc k on k.orgstructureid=j.orgstructureid
        JOIN employee l ON l.employeeid=k.employeeid
        join addressbook g on g.addressbookid=e.addressbookid
        {$wheresalesarea} {$whereproduct}
        where f.recordstatus=1 and k.recordstatus=1 and j.structurename LIKE '%spv%' and g.fullname like '%".$customer."%' and l.fullname like '%".$spv."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
        b.recordstatus=3
		and month(b.docdate) = month('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
		and year(b.docdate) = year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
        and e.isdisplay=0
        GROUP BY l.employeeid
        order by l.fullname asc";
        
        $data = Yii::app()->db->createCommand($sqldata)->queryAll();
        foreach($data as $row)
		{
            $totbayar0sd50 = 0;
            $totscale0sd50 = 0;
            $totbayar50sd90 = 0;
            $totscale50sd90 = 0;
            $totbayar90sd110 = 0;
            $totscale90sd110 = 0;
            $totbayar110sd150 = 0;
            $totscale110sd150 = 0;
            $totbayarsd150 = 0;
            $totscalesd150 = 0;
            $totjumlahbayar = 0;
            $totscalejumlah = 0;
			
			$sqlsaldoup120 = "select sum(a5) as up120
				from (select case when umur > 120 then amount-payamount else 0 end as a5
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
						where c.isdisplay=0 and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid} and d.fullname like '%".$customer."%' 
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						and c.employeeid in ({$row['employeeid']})
						-- and d.custcategoryid = {$cust['custcategoryid']}
						) z
					where amount > payamount) zz";
			$saldo120 = Yii::app()->db->createCommand($sqlsaldoup120)->queryScalar();
          
            $sqlcustcategory = "select i.custcategoryid,i.custcategoryname
            from cutarinv a
            join cutar b on b.cutarid=a.cutarid
            join invoice c on c.invoiceid=a.invoiceid
            join giheader d on d.giheaderid=c.giheaderid
            join soheader e on e.soheaderid=d.soheaderid
            join ttnt h on h.ttntid=b.ttntid
            join employee f on f.employeeid=h.employeeid
            join addressbook g on g.addressbookid=e.addressbookid
            join custcategory i on i.custcategoryid = g.custcategoryid
            {$wheresalesarea} {$whereproduct}
            where g.fullname like '%".$customer."%'and b.recordstatus=3 
			and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
			and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
            and e.companyid = {$companyid} and f.employeeid in ({$row['employeeid']})
			and i.custcategoryid not in (13,14)
            and e.isdisplay=0
            group by g.custcategoryid
            order by g.custcategoryid asc";
            $this->pdf->SetFont('Arial','B',9);
			$this->pdf->sety($this->pdf->gety()+5);
            $this->pdf->text(10,$this->pdf->getY(),'NAMA SUPERVISOR : '.$row['fullname']);
			$this->pdf->sety($this->pdf->gety()+3);
            $custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();
					
            foreach($custcategory as $cust)
			{
				$subbayar0sd50 = 0;
				$subscale0sd50 = 0;
				$subbayar50sd90 = 0;
				$subscale50sd90 = 0;
				$subbayar90sd110 = 0;
				$subscale90sd110 = 0;
				$subbayar110sd150 = 0;
				$subscale110sd150 = 0;
				$subbayarsd150 = 0;
				$subscalesd150 = 0;
				$subjumlahbayar = 0;
				$subscalejumlah = 0;
                          
                $this->pdf->setFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->getY()+5,getCatalog('custcategory'). ' = '. $cust['custcategoryname']);
                $subjumlahut = 0;
                $this->pdf->SetFont('Arial','B',9);

                $sqlperiod = "select period, invoicedate from (select c.invoiceno,c.invoicedate,b.docdate,setdateformat(c.invoicedate) as period, a.cashamount+a.bankamount as nilai
                from cutarinv a
                join cutar b on b.cutarid=a.cutarid
                join invoice c on c.invoiceid=a.invoiceid
                join giheader d on d.giheaderid=c.giheaderid
                join soheader e on e.soheaderid=d.soheaderid
                join ttnt h on h.ttntid=b.ttntid
                join employee f on f.employeeid=h.employeeid
                join addressbook g on g.addressbookid=e.addressbookid
                {$wheresalesarea} {$whereproduct}
                where g.fullname like '%".$customer."%' and b.recordstatus=3 
				and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
                and e.companyid = {$companyid} and f.employeeid in ({$row['employeeid']})
                and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
                order by invoicedate asc ) z
                where nilai <> 0
                group by month(invoicedate),year(invoicedate)
				order by invoicedate asc";
                $this->pdf->setY($this->pdf->getY()+7);
                $period = Yii::app()->db->createCommand($sqlperiod)->queryAll();
					
                foreach($period as $row1) 
				{
					$custbayar0sd50 = 0;
					$custscale0sd50 = 0;
					$custbayar50sd90 = 0;
					$custscale50sd90 = 0;
					$custbayar90sd110 = 0;
					$custscale90sd110 = 0;
					$custbayar110sd150 = 0;
					$custscale110sd150 = 0;
					$custbayarsd150 = 0;
					$custscalesd150 = 0;
					$custjumlahbayar = 0;
					$custscalejumlah = 0;
					
                    $sqlawalbulan = "select date_add(date_add(last_day('{$row1['invoicedate']}'),interval 1 day), interval -1 month) as awalbulan";
                    $tglawal = Yii::app()->db->createCommand($sqlawalbulan)->queryScalar();
					$percentperiod = Yii::app()->db->createCommand("select ifnull(scalevalue,0) from scalevaluespv where recordstatus=5 and custcategoryid = {$cust['custcategoryid']} and employeeid in ({$row['spvid']})
                      and perioddate = '{$tglawal}'")->queryScalar();

					$this->pdf->setFont('Arial','',9);
                    $this->pdf->colalign = array('C','C','C','C','C','C','C','C','C','C','C','C');
                    $this->pdf->setwidths(array(30,47,47,47,47,47,48));
                    $this->pdf->colheader = array("          Period             ( ".Yii::app()->format->formatNumber($percentperiod)."% ) ",'            <= 50%TOP             120%','   50%TOP < U <= 90%TOP    110%','90%TOP < U <= 110%TOP    100%','110%TOP < U <= 150%TOP    90%','              U > 150%TOP                0%','                  Jumlah                               ');
                    $this->pdf->RowHeader();

                    $this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
                    $this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
                    $this->pdf->SetFont('Arial','',9);
                    $this->pdf->row(array($row1['period'],'NIlai Bayar','UT','Nilai Bayar','UT','NIlai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT','Nilai Bayar','UT'));

                    $sqldet = "select *,
								case when umur >= 0 and umur <= (0.5 * paydays) then nilaibayar else 0 end as 0sd50,
								case when umur > (0.5 * paydays) and umur <= (0.9 * paydays) then nilaibayar else 0 end AS 50sd90,
								case when umur > (0.9 * paydays) and umur <= (1.1 * paydays) then nilaibayar else 0 end AS 90sd110,
								case when umur > (1.1 * paydays) and umur <= (1.5 * paydays) then nilaibayar else 0 end AS 110sd150,
								case when umur > (1.5 * paydays) then nilaibayar else 0 end as sd150
								,(0.5 * paydays),(0.9 * paydays),(paydays),(1.5 * paydays)
								from (select distinct g.fullname,c.invoiceno,c.invoicedate,b.docdate AS cutardate,datediff(b.docdate,c.invoicedate) as umur,p.paydays,c.amount,
								a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
								(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
								join invoice c on c.invoiceid=a.invoiceid
								join giheader d on d.giheaderid=c.giheaderid
								join soheader e on e.soheaderid=d.soheaderid
								join ttnt i on i.ttntid=b.ttntid
								join employee f on f.employeeid=i.employeeid
								join addressbook g on g.addressbookid=e.addressbookid
								join paymentmethod p ON p.paymentmethodid=e.paymentmethodid
								{$wheresalesarea} {$whereproduct}
								where g.fullname like '%".$customer."%' and b.recordstatus=3 
								and month(b.docdate) = month('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and year(b.docdate) = year('". date(Yii::app()->params['datetodb'], strtotime($enddate))."')
								and e.companyid = {$companyid}
								and f.employeeid in (".$row['employeeid'].")
								and e.isdisplay=0 and g.custcategoryid = {$cust['custcategoryid']}
								) z
								-- where z.materialgroupid = ''
								where month(invoicedate) = month('{$row1['invoicedate']}')
								and year(invoicedate) = year('{$row1['invoicedate']}')
								and nilaibayar <> 0 
								order by cutardate,fullname,invoicedate";
					$detail = Yii::app()->db->createCommand($sqldet)->queryAll();
                    $this->pdf->setFont('Arial','',8);
                    foreach($detail as $row2)
					{
                        //$this->pdf->row(array('','NIlai INV','UT','Nilai INV','UT','NIlai INV','UT','Nilai INV','UT','Nilai INV','UT',''));
                        $jumlahbayar = ($row2['0sd50'] + $row2['50sd90'] + $row2['90sd110'] + $row2['110sd150'] + $row2['sd150'])/$per;
                        $jumlahut = (($row2['0sd50']*$percentperiod*1.2)+($row2['50sd90']*$percentperiod*1.1)+($row2['90sd110']*$percentperiod*1)+
                                    ($row2['110sd150']*$percentperiod*0.9))/100/$per;
                        $this->pdf->row(array(
                          $row2['invoiceno'],
                          Yii::app()->format->formatCurrency($row2['0sd50']/$per),
                          Yii::app()->format->formatCurrency(($row2['0sd50']*$percentperiod*1.2)/100/$per),
                          Yii::app()->format->formatCurrency($row2['50sd90']/$per),
                          Yii::app()->format->formatCurrency(($row2['50sd90']*$percentperiod*1.1)/100/$per),
                          Yii::app()->format->formatCurrency($row2['90sd110']/$per),
                          Yii::app()->format->formatCurrency(($row2['90sd110']*$percentperiod*1)/100/$per),
                          Yii::app()->format->formatCurrency($row2['110sd150']/$per),
                          Yii::app()->format->formatCurrency(($row2['110sd150']*$percentperiod*0.9)/100/$per),
                          Yii::app()->format->formatCurrency($row2['sd150']/$per),
                          Yii::app()->format->formatCurrency(0),
                          Yii::app()->format->formatCurrency($jumlahbayar),
                          Yii::app()->format->formatCurrency($jumlahut)
                        ));
                        $custbayar0sd50 += $row2['0sd50']/$per;
                        $custscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $custbayar50sd90 += $row2['50sd90']/$per;
                        $custscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $custbayar90sd110 += $row2['90sd110']/$per;
                        $custscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $custbayar110sd150 += $row2['110sd150']/$per;
                        $custscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $custbayarsd150 += $row2['sd150']/$per;
                        $custscalesd150 = 0;
                        $custjumlahbayar += $jumlahbayar;
                        $custscalejumlah += $jumlahut;
                      
                        $subbayar0sd50 += $row2['0sd50']/$per;
                        $subscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $subbayar50sd90 += $row2['50sd90']/$per;
                        $subscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $subbayar90sd110 += $row2['90sd110']/$per;
                        $subscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $subbayar110sd150 += $row2['110sd150']/$per;
                        $subscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $subbayarsd150 += $row2['sd150']/$per;
                        $subscalesd150 = 0;
                        $subjumlahbayar += $jumlahbayar;
                        $subscalejumlah += $jumlahut;
                      
                        $totbayar0sd50 += $row2['0sd50']/$per;
                        $totscale0sd50 += ($row2['0sd50']*$percentperiod*1.2)/100/$per;
                        $totbayar50sd90 += $row2['50sd90']/$per;
                        $totscale50sd90 += ($row2['50sd90']*$percentperiod*1.1)/100/$per;
                        $totbayar90sd110 += $row2['90sd110']/$per;
                        $totscale90sd110 += ($row2['90sd110']*$percentperiod*1)/100/$per;
                        $totbayar110sd150 += $row2['110sd150']/$per;
                        $totscale110sd150 += ($row2['110sd150']*$percentperiod*0.9)/100/$per;
                        $totbayarsd150 += $row2['sd150']/$per;
                        $totscalesd150 = 0;
                        $totjumlahbayar += $jumlahbayar;
                        $totscalejumlah += $jumlahut;
                      
                        $totjumlahut = $totjumlahut + $jumlahut;
                    }
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->row(array(
						'Jumlah '.$row1['period'],
						Yii::app()->format->formatCurrency($custbayar0sd50),
						Yii::app()->format->formatCurrency($custscale0sd50),
						Yii::app()->format->formatCurrency($custbayar50sd90),
						Yii::app()->format->formatCurrency($custscale50sd90),
						Yii::app()->format->formatCurrency($custbayar90sd110),
						Yii::app()->format->formatCurrency($custscale90sd110),
						Yii::app()->format->formatCurrency($custbayar110sd150),
						Yii::app()->format->formatCurrency($custscale110sd150),
						Yii::app()->format->formatCurrency($custbayarsd150),
						Yii::app()->format->formatCurrency($custscalesd150),
						Yii::app()->format->formatCurrency($custjumlahbayar),
						Yii::app()->format->formatCurrency($custscalejumlah)
					));
                    $this->pdf->setY($this->pdf->getY()+5);
                    //$this->pdf->coldetailalign = array('L','R','R','R','R','R','R','R','R','R','R','R');
                }
				$this->pdf->coldetailalign = array('L','R','L','R','L','R','L','R','L','R','L','R','L');
				$this->pdf->setwidths(array(30,25,22,25,22,25,22,25,22,25,22,26,22));
				$this->pdf->setFont('Arial','B',8);
				//$this->pdf->coldetailalign = array('L','R','R');
				//$this->pdf->setwidths(array(80,165,35));
				$this->pdf->row(array('JUMLAH '.strtoupper($cust['custcategoryname']),
					Yii::app()->format->formatCurrency($subbayar0sd50),
					Yii::app()->format->formatCurrency($subscale0sd50),
					Yii::app()->format->formatCurrency($subbayar50sd90),
					Yii::app()->format->formatCurrency($subscale50sd90),
					Yii::app()->format->formatCurrency($subbayar90sd110),
					Yii::app()->format->formatCurrency($subscale90sd110),
					Yii::app()->format->formatCurrency($subbayar110sd150),
					Yii::app()->format->formatCurrency($subscale110sd150),
					Yii::app()->format->formatCurrency($subbayarsd150),
					Yii::app()->format->formatCurrency($subscalesd150),
					Yii::app()->format->formatCurrency($subjumlahbayar),
					Yii::app()->format->formatCurrency($subscalejumlah)
				));
				$this->pdf->setY($this->pdf->getY()+5);
            }
			$this->pdf->checkNewPage(55);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->row(array('TOTAL SUPERVISOR ',
				Yii::app()->format->formatCurrency($totbayar0sd50),
				Yii::app()->format->formatCurrency($totscale0sd50),
				Yii::app()->format->formatCurrency($totbayar50sd90),
				Yii::app()->format->formatCurrency($totscale50sd90),
				Yii::app()->format->formatCurrency($totbayar90sd110),
				Yii::app()->format->formatCurrency($totscale90sd110),
				Yii::app()->format->formatCurrency($totbayar110sd150),
				Yii::app()->format->formatCurrency($totscale110sd150),
				Yii::app()->format->formatCurrency($totbayarsd150),
				Yii::app()->format->formatCurrency($totscalesd150),
				Yii::app()->format->formatCurrency($totjumlahbayar),
				Yii::app()->format->formatCurrency($totscalejumlah)
			));
            $this->pdf->setY($this->pdf->getY()+5);
            $this->pdf->setFont('Arial','B',9);
            $this->pdf->coldetailalign = array('L','R','R');
            $this->pdf->setwidths(array(165,100,35));
			$saldo120per = $saldo120/$per;
            $this->pdf->row(array('SALDO UMUR PIUTANG > 120 HARI (-0.5%) ('.Yii::app()->format->formatCurrency($spvscale).' )',Yii::app()->format->formatCurrency($saldo120per),
                                  Yii::app()->format->formatCurrency(($saldo120per*-0.5*$spvscale)/100)));
            $this->pdf->row(array('TOTAL UPAH TAMBAHAN','',Yii::app()->format->formatCurrency($totscalejumlah+(($saldo120per*-0.5*$spvscale)/100))));
            $this->pdf->row(array('DEPOSIT 10%','',Yii::app()->format->formatCurrency(($totscalejumlah+(($saldo120per*-0.5*$spvscale)/100))*-0.1)));
            $upah = ($totscalejumlah+(($saldo120per*-0.5*$spvscale)/100))*0.9;
            $this->pdf->row(array('UPAH TAMBAHAN YANG DITERIMA SUPERVISOR '.strtoupper($row['fullname']),'',Yii::app()->format->formatCurrency($upah)));
            //$this->pdf->text(10,$this->pdf->getY(),'SALDO UMUR PIUTANG > 120 HARI');
            $this->pdf->setY($this->pdf->getY()+5);
			
            $this->pdf->setFont('Arial','',9);
			$this->pdf->setwidths(array(63,63,63,63,63));
			$this->pdf->coldetailalign = array('C','C','C','C','C');
			$this->pdf->row(array('Diperiksa Oleh,', 'Disetujui Oleh,', 'Diketahui Oleh,', 'Dibayar Oleh,', 'Diterima Oleh,'));
			$this->pdf->setY($this->pdf->getY()+20);
			$this->pdf->row(array('ACC & HEAD FINANCE','HEAD MARKETING', 'BRANCH MANAGER', 'KASIR', strtoupper($row['fullname'])));
			$this->pdf->checkNewPage(120);
            
        }
        $this->pdf->Output();
    }
	
	
	public function actionDownXLS()
	{
		parent::actionDownload();
		if (isset($_GET['lro']) && isset($_GET['company']) && isset($_GET['sloc']) && isset($_GET['materialgroup']) && isset($_GET['customer']) && isset($_GET['product']) && isset($_GET['sales']) && isset($_GET['spv']) && isset($_GET['salesarea']) && isset($_GET['umurpiutang']) && isset($_GET['isdisplay']) && isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['per']))
		{
			$uri = $_SERVER['REQUEST_URI'];
			$start = strpos($uri,'customer');
			$end  = strpos($uri,'&product=');
			$new =  substr($uri,$start,$end-$start);
			$customer = urldecode(substr($new,9));
			if ($_GET['lro'] == 99)
			{
				$this->RincianFakturdanReturJualBelumLunasGabunganXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 1)
			{
				$this->RincianPelunasanPiutangPerDokumenXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 2)
			{
				$this->RekapPelunasanPiutangPerDivisiXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 3)
			{
				$this->KartuPiutangDagangXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 4)
			{
				$this->RekapPiutangDagangPerCustomerXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 5)
			{
				$this->RincianFakturdanReturJualBelumLunasXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 6)
			{
				$this->RincianUmurPiutangDagangPerCustomerXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 7)
			{
				$this->RekapUmurPiutangDagangPerCustomerXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 8)
			{
				$this->RincianFakturdanReturJualBelumLunasPerSalesXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 9)
			{
				$this->RekapKontrolPiutangCustomervsPlafonXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 10)
			{
				$this->RincianKontrolPiutangCustomervsPlafonXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 11)
			{
				$this->KonfirmasiPiutangDagangXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 12)
			{
				$this->RekapInvoiceARPerDokumenBelumStatusMaxXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 13)
			{
				$this->RekapNotaReturPenjualanPerDokumenBelumStatusMaxXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 14)
			{
				$this->RekapPelunasanPiutangPerDokumenBelumStatusMaxXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 15)
			{
				$this->RincianPelunasanPiutangPerSalesXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 16)
			{
				$this->RekapPelunasanPiutangPerSalesXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 17)
			{
				$this->RincianPelunasanPiutangPerSalesPerJenisBarangXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 18)
			{
				$this->RincianPelunasanPiutangPerSalesPerJenisBarangWithoutOBXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 19)
			{
				$this->RekapPelunasanPiutangPerSalesPerJenisBarangXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 20)
			{
				$this->RekapPenjualanVSPelunasanPerBulanPerCustomerXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 21)
			{
				$this->RekapPiutangVSPelunasanPerBulanPerCustomerXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 22)
			{
				$this->RincianPelunasanPiutangPerCustomerXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 23)
			{
				$this->RekapPelunasanPiutangPerCustomerXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 24)
			{
				$this->RincianPelunasanPiutangPerCustomerPerJenisBarangXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 25)
			{
				$this->RekapPelunasanPiutangPerCustomerPerJenisBarangXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			if ($_GET['lro'] == 26)
			{
				$this->RekapUmurPiutangDagangXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 27)
			{
				$this->RekapUmurPiutangDagangPerBulanPerTahunXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 28)
			{
				$this->RincianFakturdanReturJualBelumLunasFilterJTTXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 29)
			{
				$this->RincianPelunasanPiutangFilterTanggalInvoiceXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 30)
			{
				$this->RincianPelunasanPiutangFilterTanggalPelunasanXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 31)
			{
				$this->RekapTargetVSTagihanXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 32)
			{
				$this->RincianKomisiKastaXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 33)
			{
				$this->RekapTargetTagihanPerDokumenBelumStatusMaxXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 34)
			{
				$this->RekapSkalaKomisiTagihanPerDokumenBelumStatusMaxXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 35)
			{
				$this->RekapUmurPiutangDagangPerCustomerVsTopXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
      else
			if ($_GET['lro'] == 36)
			{
				$this->RekapMonitoringPiutangPerCustomerPerSalesXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
      else
			if ($_GET['lro'] == 37)
			{
				$this->RekapKomisiTagihanPerSPVPerSalesXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 38)
			{
				$this->RekapUmurPiutangDagangPerCompanyXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 39)
			{
				$this->RekapKomisiKastaXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
			else
			if ($_GET['lro'] == 40)
			{
				$this->RincianKomisiTagihanPerSPVPerSalesXLS($_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$customer,$_GET['product'],$_GET['sales'],$_GET['spv'],$_GET['salesarea'],$_GET['umurpiutang'],$_GET['isdisplay'],$_GET['startdate'],$_GET['enddate'],$_GET['per']);
			}
		}
	}
	//99
	public function RincianFakturdanReturJualBelumLunasGabunganXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)			
	{
		$this->menuname='rincianfakturdanreturjualbelumlunasgabungan';
		parent::actionDownxls();
		$nilaitot1=0;$dibayar1=0; $sisa1=0;
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			$isdisplay1= " and c.isdisplay = ".$isdisplay." ";
		}
    $sql = "select distinct addressbookid,fullname,lat,lng,wanumber
					from (select d.addressbookid,d.fullname,a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
					ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,
					(select round(h.lat,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lat,(select round(h.lng,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lng,
					ifnull((select h.wanumber from addresscontact h where h.addressbookid=d.addressbookid Limit 1),'') as wanumber
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid
					where e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null
					and d.fullname like '%".$customer."%' ".$isdisplay1."
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount
		";
		if ($_GET['umurpiutang'] !== '') 
		{
				$sql = $sql . "and  umur > ".$_GET['umurpiutang']." order by fullname";
		}
		else 
		{
				$sql = $sql . "order by fullname";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			if($isdisplay == "1")
			{
				$title='Rincian Faktur & Retur Jual Belum Lunas (HANYA DISPLAY)';
			}
			else if($isdisplay == "0")
			{
				$title='Rincian Faktur & Retur Jual Belum Lunas (BUKAN DISPLAY)';
			}
		}
		else
		{
			$title='Rincian Faktur & Retur Jual Belum Lunas';
		}
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,1,$title)
			->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
			->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
			->setCellValueByColumnAndRow(7,1,'Gabungan');
		$line=4;				
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)						
				->setCellValueByColumnAndRow(0,$line,$row['fullname']);							
			$line++;
				
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No')
				->setCellValueByColumnAndRow(1,$line,'No. Dokumen')
				->setCellValueByColumnAndRow(2,$line,'Tanggal')					
				->setCellValueByColumnAndRow(3,$line,'J_Tempo')
				->setCellValueByColumnAndRow(4,$line,'Umur')
				->setCellValueByColumnAndRow(5,$line,'UT')
				->setCellValueByColumnAndRow(6,$line,'Nilai')
				->setCellValueByColumnAndRow(7,$line,'Kum_Bayar')
				->setCellValueByColumnAndRow(8,$line,'Sisa')
				->setCellValueByColumnAndRow(9,$line,'Sales');
			$line++;
			$sql1 = " select *, (amount-payamount) as sisa,(amount) as nilai
							from (select if(c.isdisplay=1,concat(a.invoiceno,'_D'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
							date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,ff.fullname as sales,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
							from cutarinv f
							join cutar g on g.cutarid=f.cutarid
							where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,c.companyid,gg.companycode
							from invoice a
							inner join giheader b on b.giheaderid = a.giheaderid
							inner join soheader c on c.soheaderid = b.soheaderid
							inner join addressbook d on d.addressbookid = c.addressbookid
							inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
							inner join employee ff on ff.employeeid = c.employeeid
							inner join company gg on gg.companyid = a.companyid

							where d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null
							and d.addressbookid = '".$row['addressbookid']."'	".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
			where amount > payamount
			";
			if ($_GET['umurpiutang'] !== '') 
			{
					$sql1 = $sql1 . "and  umur > ".$_GET['umurpiutang']." order by umurtempo desc,invoiceno";
			}
			else 
			{
					$sql1 = $sql1 . "order by umurtempo desc,invoiceno";
			}
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			$i=0;$nilaitot=0;$dibayar=0; $sisa=0;            
							 
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
					->setCellValueByColumnAndRow(2,$line,$row1['invoicedate'])
					->setCellValueByColumnAndRow(3,$line,$row1['jatuhtempo'])
					->setCellValueByColumnAndRow(4,$line,$row1['umur'])
					->setCellValueByColumnAndRow(5,$line,$row1['umurtempo'])
					->setCellValueByColumnAndRow(6,$line,($row1['nilai']/$per))							
					->setCellValueByColumnAndRow(7,$line,($row1['payamount']/$per))					
					->setCellValueByColumnAndRow(8,$line,(($row1['nilai']/$per)-($row1['payamount']/$per)))
					->setCellValueByColumnAndRow(9,$line,$row1['sales']);
				$line++;
				$nilaitot += $row1['nilai']/$per;
				$dibayar += $row1['payamount']/$per;
				$sisa += (($row1['nilai']/$per)-($row1['payamount']/$per));
	
				
			}
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(3,$line,'Total')										
				->setCellValueByColumnAndRow(6,$line,($nilaitot))
				->setCellValueByColumnAndRow(7,$line,($dibayar))
				->setCellValueByColumnAndRow(8,$line,($sisa));
			$line++;
			$nilaitot1 += $nilaitot;
			$dibayar1 += $dibayar;
			$sisa1 += $sisa;
			$line += 1;
		}
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(3,$line,'GRAND TOTAL')										
			->setCellValueByColumnAndRow(6,$line,($nilaitot1))
			->setCellValueByColumnAndRow(7,$line,($dibayar1))
			->setCellValueByColumnAndRow(8,$line,($sisa1));
		$line++;
			
		$this->getFooterXLS($this->phpExcel);
	}	
	//1
	public function RincianPelunasanPiutangPerDokumenXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianpelunasanpiutangperdokumen';
		parent::actionDownxls();
		$totalsaldo2=0;$totaltunai2=0;$totalbank2=0;$totaldiskon2=0;$totalretur2=0;$totalob2=0;$totaljumlah2=0;$totalsisa2=0;
		$sql = "select distinct a.cutarid,a.cutarno,a.docdate as cutardate,c.docno as ttntno,c.docdate as ttntdate,b.companyid
						from cutar a
						join company b on b.companyid = a.companyid
						join ttnt c on c.ttntid = a.ttntid
						join cutarinv d on d.cutarid = a.cutarid
						join invoice e on e.invoiceid = d.invoiceid
						join giheader f on f.giheaderid = e.giheaderid
						join soheader g on g.soheaderid = f.soheaderid	
						join ttnt m on m.ttntid=a.ttntid					
						left join employee h on h.employeeid = m.employeeid
						join addressbook i on i.addressbookid = g.addressbookid
						where i.fullname like '%".$customer."%' and h.fullname like '%".$sales."%' and a.cutarno is not null 
						and a.companyid = ".$companyid." and a.recordstatus=3
						and	a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if ($product !== '') 
		{
			$sql = $sql . " and	f.giheaderid in (select distinct a0.giheaderid from gidetail a0 join product a1 on a1.productid=a0.productid where a1.productname like '%".$product."%') ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
            
            foreach($dataReader as $row)
						$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
							->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))

												
							->setCellValueByColumnAndRow(3,1,GetCompanyCode($companyid));
							$line=4;				
						foreach($dataReader as $row)
						{
							//$this->phpExcel->getActiveSheet()->getStyle(1,$line)->getFont()->setBold(true);
							
								$this->phpExcel->setActiveSheetIndex(0)
							        ->setCellValueByColumnAndRow(0,$line,'No:')
									->setCellValueByColumnAndRow(1,$line,$row['cutarno'])
									->setCellValueByColumnAndRow(2,$line,'TTNT:')
									->setCellValueByColumnAndRow(3,$line,$row['ttntno']);														
									$line++;
                                                         $this->phpExcel->setActiveSheetIndex(0)
							        ->setCellValueByColumnAndRow(0,$line,'Tgl :')
									->setCellValueByColumnAndRow(1,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row['cutardate'])))
									->setCellValueByColumnAndRow(2,$line,'Tgl :')
									->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row['ttntdate'])));														
									$line++;
									
							$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'No')
									->setCellValueByColumnAndRow(1,$line,'No. Invoice')
									->setCellValueByColumnAndRow(2,$line,'Customer')					
									->setCellValueByColumnAndRow(3,$line,'Tgl Invoice')
									->setCellValueByColumnAndRow(4,$line,'Saldo Invoice')
									->setCellValueByColumnAndRow(5,$line,'Tunai')
									->setCellValueByColumnAndRow(6,$line,'Bank')
									->setCellValueByColumnAndRow(7,$line,'Diskon')
									->setCellValueByColumnAndRow(8,$line,'Retur')
									->setCellValueByColumnAndRow(9,$line,'OB')
									->setCellValueByColumnAndRow(10,$line,'Jumlah')
									->setCellValueByColumnAndRow(11,$line,'Sisa');
							$line++;
			if ($product !== '') 
			{
				$whereproduct = " and d.giheaderid in (select distinct a0.giheaderid from gidetail a0 join product a1 on a1.productid=a0.productid where a1.productname like '%".$product."%') ";
			}
			else
			{
				$whereproduct = "";
			}
			$sql1 = "select*,(saldo-(tunai+bank+diskon+retur+ob)) as sisa
						from(select f.fullname,c.invoiceno,c.invoicedate,sum(saldoinvoice)as saldo,sum(cashamount) as tunai,sum(a.bankamount) as bank,sum(a.discamount) as diskon,sum(a.returnamount) as retur,sum(a.obamount) as ob,
						sum(cashamount)+sum(a.bankamount)+sum(a.discamount)+sum(a.returnamount)+sum(a.obamount) as jumlah
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid = a.invoiceid
						join giheader d on d.giheaderid = c.giheaderid
						join soheader e on e.soheaderid= d.soheaderid
						join addressbook f on f.addressbookid = e.addressbookid	
						join ttnt h on h.ttntid=b.ttntid
						left join employee g on g.employeeid = h.employeeid
						where f.fullname like '%".$customer."%' and g.fullname like '%".$sales."%' and b.recordstatus=3 and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'	and a.cutarid = ".$row['cutarid']." {$whereproduct}
						group by invoiceno)z
						";

                $dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
                $i=0;$totalsaldo=0;$totaltunai=0;$totalbank=0;$totaldiskon=0;$totalretur=0;$totalob=0;$totaljumlah=0;$totalsisa=0;									
								foreach($dataReader1 as $row1)
								{
									$i+=1;
									$this->phpExcel->setActiveSheetIndex(0)
								->setCellValueByColumnAndRow(0,$line,$i)
								->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
								->setCellValueByColumnAndRow(2,$line,$row1['fullname'])
								->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])))
								->setCellValueByColumnAndRow(4,$line,($row1['saldo']/$per))
								->setCellValueByColumnAndRow(5,$line,($row1['tunai']/$per))					
								->setCellValueByColumnAndRow(6,$line,($row1['bank']/$per))					
							    ->setCellValueByColumnAndRow(7,$line,($row1['diskon']/$per))
								->setCellValueByColumnAndRow(8,$line,($row1['retur']/$per))
								->setCellValueByColumnAndRow(9,$line,($row1['ob']/$per))
								->setCellValueByColumnAndRow(10,$line,($row1['jumlah']/$per))
								->setCellValueByColumnAndRow(11,$line,($row1['sisa']/$per));
									$line++;
									$totalsaldo += $row1['saldo']/$per;
									$totaltunai += $row1['tunai']/$per;
									$totalbank += $row1['bank']/$per;
                  					$totaldiskon += $row1['diskon']/$per;
                  					$totalretur += $row1['retur']/$per;
									$totalob += $row1['ob']/$per;
									$totaljumlah += $row1['jumlah']/$per;
									$totalsisa += $row1['sisa']/$per;
								}
								$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(1,$line,'Total')										
										->setCellValueByColumnAndRow(4,$line,($totalsaldo))
										->setCellValueByColumnAndRow(5,$line,($totaltunai))
										->setCellValueByColumnAndRow(6,$line,($totalbank))
										->setCellValueByColumnAndRow(7,$line,($totaldiskon))
										->setCellValueByColumnAndRow(8,$line,($totalretur))
										->setCellValueByColumnAndRow(9,$line,($totalob))
										->setCellValueByColumnAndRow(10,$line,($totaljumlah))
										->setCellValueByColumnAndRow(11,$line,($totalsisa));
								$line++;
									$totalsaldo2 += $totalsaldo;
									$totaltunai2 += $totaltunai;
									$totalbank2 += $totalbank;
                  					$totaldiskon2 += $totaldiskon;
                  					$totalretur2 += $totalretur;
									$totalob2 += $totalob;
									$totaljumlah2 += $totaljumlah;
									$totalsisa2 += $totalsisa;
								$line += 1;
						}
						$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(1,$line,'Grand Total')										
										->setCellValueByColumnAndRow(4,$line,($totalsaldo2))
										->setCellValueByColumnAndRow(5,$line,($totaltunai2))
										->setCellValueByColumnAndRow(6,$line,($totalbank2))
										->setCellValueByColumnAndRow(7,$line,($totaldiskon2))
										->setCellValueByColumnAndRow(8,$line,($totalretur2))
										->setCellValueByColumnAndRow(9,$line,($totalob2))
										->setCellValueByColumnAndRow(10,$line,($totaljumlah2))
										->setCellValueByColumnAndRow(11,$line,($totalsisa2));
								$line++;
		
		
		$this->getFooterXLS($this->phpExcel);
	}
	//2
	public function RekapPelunasanPiutangPerDivisiXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekappelunasanpiutangperdivisi';
		parent::actionDownxls();
		$sql ="select distinct a.cutarid, sum(cashamount) as tunai,sum(a.bankamount) as bank,sum(a.discamount) as diskon,sum(a.returnamount) as retur,sum(a.obamount) as ob,
				sum(cashamount)+sum(a.bankamount)+sum(a.discamount)+sum(a.returnamount)+sum(a.obamount) as jumlah,
				(select xx.description from materialgroup xx 
				join productplant zz on zz.materialgroupid = xx.materialgroupid
				join sodetail yy on yy.productid = zz.productid
				where yy.soheaderid = e.soheaderid limit 1
				) as materialgroupname
				from cutarinv a
				join cutar b on b.cutarid=a.cutarid
				join invoice c on c.invoiceid=a.invoiceid
				join giheader d on d.giheaderid = c.giheaderid
				join soheader e on e.soheaderid = d.soheaderid 
				join ttnt h on h.ttntid=b.ttntid
				join employee f on f.employeeid = h.employeeid
				join addressbook g on g.addressbookid = e.addressbookid
				where b.companyid = ".$companyid." and g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
				and b.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				group by materialgroupname";
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
			foreach($dataReader as $row)
			$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))							
							->setCellValueByColumnAndRow(7,1,GetCompanyCode($companyid));
							$line=4;
			$i=0;$totaltunai=0;$totalbank=0; $totaldiskon=0;$totalretur=0;$totalob=0;$totaljumlah=0;		
					
			$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'No')
									->setCellValueByColumnAndRow(1,$line,'Devisi/Jenis')
									->setCellValueByColumnAndRow(2,$line,'Tunai')								
									->setCellValueByColumnAndRow(3,$line,'Bank')
									->setCellValueByColumnAndRow(4,$line,'Diskon')
									->setCellValueByColumnAndRow(5,$line,'Retur')
									->setCellValueByColumnAndRow(6,$line,'PDMK/OB')
									->setCellValueByColumnAndRow(7,$line,'Jumlah');
							$line++;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,$i)
						->setCellValueByColumnAndRow(1,$line,$row['materialgroupname'])						
						->setCellValueByColumnAndRow(2,$line,($row['tunai']/$per))
						->setCellValueByColumnAndRow(3,$line,($row['bank']/$per))							
						->setCellValueByColumnAndRow(4,$line,($row['diskon']/$per))					
						->setCellValueByColumnAndRow(5,$line,($row['retur']/$per))
						->setCellValueByColumnAndRow(6,$line,($row['ob']/$per))
						->setCellValueByColumnAndRow(7,$line,($row['jumlah']/$per));
					$line++;
					$totaltunai += $row['tunai']/$per;
					$totalbank += $row['bank']/$per;
          			$totaldiskon += $row['diskon']/$per;
					$totalretur += $row['retur']/$per;
					$totalob += $row['ob']/$per;
					$totaljumlah += $row['jumlah']/$per;
					
			}
			$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(1,$line,'Total')										
										->setCellValueByColumnAndRow(2,$line,($totaltunai))
										->setCellValueByColumnAndRow(3,$line,($totalbank))
										->setCellValueByColumnAndRow(4,$line,($totaldiskon))
										->setCellValueByColumnAndRow(5,$line,($totalretur))
										->setCellValueByColumnAndRow(6,$line,($totalob))
										->setCellValueByColumnAndRow(7,$line,($totaljumlah));
								$line++;
		$this->getFooterXLS($this->phpExcel);
	}
	//3
	public function KartuPiutangDagangXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='kartupiutangdagang';
		parent::actionDownxls();
		$penambahan1=0;$tunai1=0;$bank1=0;$diskon1=0;$retur1=0;$ob1=0;$saldo1=0;     
            
		$sql = "select *
						from (select a.fullname,a.addressbookid,
						ifnull((select sum(ifnull(b.amount,0)-ifnull((select sum((ifnull(c.cashamount,0)+ifnull(c.bankamount,0)+ifnull(c.discamount,0)+ifnull(c.returnamount,0)+ifnull(c.obamount,0))*ifnull(c.currencyrate,0))
						from cutarinv c
						join cutar d on d.cutarid=c.cutarid
						where d.recordstatus=3 and c.invoiceid=b.invoiceid and d.companyid=".$companyid."
						and d.docdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),0))
						from invoice b
						join giheader e on e.giheaderid=b.giheaderid
						join soheader f on f.soheaderid=e.soheaderid
						join employee g on g.employeeid = f.employeeid
						join addressbook h on h.addressbookid = f.addressbookid
						where h.fullname like '".$customer."' and g.fullname like '%".$sales."%' and b.recordstatus=3
						and f.companyid=".$companyid."	and b.invoicedate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and f.addressbookid=a.addressbookid),0) as saldo,
						ifnull((select sum(ifnull(h.amount,0))
						from invoice h
						join giheader i on i.giheaderid=h.giheaderid
						join soheader j on j.soheaderid=i.soheaderid
						join employee k on k.employeeid = j.employeeid
						join addressbook l on l.addressbookid = j.addressbookid
						where l.fullname like '%".$customer."%' and k.fullname like '%".$sales."%' and h.recordstatus=3
						and j.companyid=".$companyid." and j.addressbookid=a.addressbookid
						and h.invoicedate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as piutang,
						ifnull((select sum(ifnull((select sum((ifnull(c.cashamount,0)+ifnull(c.bankamount,0)+ifnull(c.discamount,0)+ifnull(c.returnamount,0)+ifnull(c.obamount,0))*ifnull(c.currencyrate,0))
						from cutarinv c
						join cutar d on d.cutarid=c.cutarid
						where d.recordstatus=3 and c.invoiceid=b.invoiceid and d.companyid=".$companyid."
						and d.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0))
						from invoice b
						join giheader e on e.giheaderid=b.giheaderid
						join soheader f on f.soheaderid=e.soheaderid
						join employee g on g.employeeid = f.employeeid
						join addressbook h on h.addressbookid = f.addressbookid
						where h.fullname like '%".$customer."%' and g.fullname like '%".$sales."%' and b.recordstatus=3
						and f.companyid=".$companyid."
						and b.invoicedate<='".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and f.addressbookid=a.addressbookid),0) as dibayar
						from addressbook a
						where a.fullname like '%".$customer."%') z
						where z.saldo<>0 or z.piutang<>0 or z.dibayar<>0
						order by fullname";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();;
		
		foreach($dataReader as $row)
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
			->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
			->setCellValueByColumnAndRow(4,1,GetCompanyCode($companyid));
			$line=4;				
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)						
				->setCellValueByColumnAndRow(0,$line,$row['fullname']);							
			$line++;	
									
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Dokumen')
				->setCellValueByColumnAndRow(1,$line,'Tanggal')
				->setCellValueByColumnAndRow(2,$line,'U/ Byr INV')					
				->setCellValueByColumnAndRow(3,$line,'Penambahan')
				->setCellValueByColumnAndRow(4,$line,'Tunai')
				->setCellValueByColumnAndRow(5,$line,'Bank')
				->setCellValueByColumnAndRow(6,$line,'Diskon')
				->setCellValueByColumnAndRow(7,$line,'Retur')
				->setCellValueByColumnAndRow(8,$line,'OB')
				->setCellValueByColumnAndRow(9,$line,'Saldo');
			$line++;
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'Saldo Awal')
				->setCellValueByColumnAndRow(9,$line,($row['saldo']/$per));
			$line++;
			
			$penambahan=0;$tunai=0;$bank=0;$diskon=0;$retur=0;$ob=0;
			$sql2 = "select * from
				(select distinct a.invoiceno as dokumen,a.invoicedate as tanggal,'-' as ref,a.amount as penambahan,'0' as tunai,'0' as bank,'0' as diskon,'0' as retur,'0' as ob
				from invoice a
				left join giheader b on b.giheaderid = a.giheaderid
				left join soheader c on c.soheaderid = b.soheaderid
				left join cbarinv d on d.invoiceid = a.invoiceid
				join employee e on e.employeeid = c.employeeid
				join addressbook f on f.addressbookid = c.addressbookid
				where f.fullname like '%".$customer."%' and c.companyid = ".$companyid." and a.recordstatus = 3 and a.invoiceno is not null and
				c.addressbookid = ".$row['addressbookid']." and
				a.invoicedate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
				and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
					union
				select d.cutarno as dokumen,d.docdate as tanggal,g.invoiceno as ref,'0' as penambahan,ifnull(c.cashamount,0) as tunai,ifnull(c.bankamount,0) as bank,ifnull(c.discamount,0) as diskon,
				ifnull(c.returnamount,0) as retur,ifnull(c.obamount,0) as ob
				from cutarinv c
				join cutar d on d.cutarid=c.cutarid
				join invoice g on g.invoiceid=c.invoiceid
				left join giheader h on h.giheaderid = g.giheaderid
				left join soheader i on i.soheaderid = h.soheaderid
				join addressbook j on j.addressbookid = i.addressbookid
				join employee k on k.employeeid = i.employeeid
				where d.recordstatus=3
				and d.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				and c.invoiceid in (select b.invoiceid
				from invoice b
				join giheader e on e.giheaderid=b.giheaderid
				join soheader f on f.soheaderid=e.soheaderid
				join employee g on g.employeeid = f.employeeid
				join addressbook h on h.addressbookid = f.addressbookid
				where h.fullname like '".$customer."' and g.fullname like '%".$sales."%' and b.recordstatus=3
				and f.companyid=".$companyid." and f.addressbookid = ".$row['addressbookid']."
				and b.invoicedate<='".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
				) z
				order by tanggal,dokumen";
			$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();            
							
			foreach($dataReader2 as $row2)
			{
					
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$row2['dokumen'])
					->setCellValueByColumnAndRow(1,$line,$row2['tanggal'])
					->setCellValueByColumnAndRow(2,$line,$row2['ref'])							
					->setCellValueByColumnAndRow(3,$line,($row2['penambahan']/$per))					
					->setCellValueByColumnAndRow(4,$line,($row2['tunai']/$per))
					->setCellValueByColumnAndRow(5,$line,($row2['bank']/$per))
					->setCellValueByColumnAndRow(6,$line,($row2['diskon']/$per))
					->setCellValueByColumnAndRow(7,$line,($row2['retur']/$per))
					->setCellValueByColumnAndRow(8,$line,($row2['ob']/$per));
				$line++;
				$penambahan += $row2['penambahan']/$per;
				$tunai += $row2['tunai']/$per;
				$bank += $row2['bank']/$per;
				$diskon += $row2['diskon']/$per;
				$retur += $row2['retur']/$per;
				$ob += $row2['ob']/$per; 
					
			}
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(1,$line,'Total '.$row['fullname'])
				->setCellValueByColumnAndRow(3,$line,($penambahan))
				->setCellValueByColumnAndRow(4,$line,($tunai))
				->setCellValueByColumnAndRow(5,$line,($bank))
				->setCellValueByColumnAndRow(6,$line,($diskon))
				->setCellValueByColumnAndRow(7,$line,($retur))
				->setCellValueByColumnAndRow(8,$line,($ob))
				->setCellValueByColumnAndRow(9,$line,(($row['saldo']/$per)+$penambahan-$tunai-$bank-$diskon-$retur-$ob));
			$line++;
			$penambahan1 += $penambahan;
			$tunai1 += $tunai;
			$bank1 += $bank;
			$diskon1 += $diskon;
			$retur1 += $retur;
			$ob1 += $ob;
			$saldo1 += $row['saldo']/$per;
			$line += 1;
		}
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,$line,'Grand Total')
			->setCellValueByColumnAndRow(2,$line,($saldo1))
			->setCellValueByColumnAndRow(3,$line,($penambahan1))
			->setCellValueByColumnAndRow(4,$line,($tunai1))
			->setCellValueByColumnAndRow(5,$line,($bank1))
			->setCellValueByColumnAndRow(6,$line,($diskon1))
			->setCellValueByColumnAndRow(7,$line,($retur1))
			->setCellValueByColumnAndRow(8,$line,($ob1))
			->setCellValueByColumnAndRow(9,$line,($saldo1+$penambahan1-$tunai1-$bank1-$diskon1-$retur1-$ob1));
		$line++;
	
		$this->getFooterXLS($this->phpExcel);
	}	
	//4
	public function RekapPiutangDagangPerCustomerXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekappiutangdagangpercustomer';
		parent::actionDownxls();
		
		$sql = 	"select *
						from (select a.fullname,
						ifnull((select sum(ifnull(b.amount,0)-ifnull((select sum((ifnull(c.cashamount,0)+ifnull(c.bankamount,0)+ifnull(c.discamount,0)+ifnull(c.returnamount,0)+ifnull(c.obamount,0))*ifnull(c.currencyrate,0))
						from cutarinv c
						join cutar d on d.cutarid=c.cutarid
						join invoice e on e.invoiceid = c.invoiceid
						join giheader f on f.giheaderid = e.giheaderid
						join soheader g on g.soheaderid = f.soheaderid
						join employee h on h.employeeid = g.employeeid
						join addressbook i on i.addressbookid = g.addressbookid
						where i.fullname like '%".$customer."%' and h.fullname like '%".$sales."%' and d.recordstatus=3 and c.invoiceid=b.invoiceid and d.companyid=".$companyid."
						and d.docdate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),0))
						from invoice b
						join giheader e on e.giheaderid=b.giheaderid
						join soheader f on f.soheaderid=e.soheaderid
						join employee g on g.employeeid = f.employeeid
						join addressbook h on h.addressbookid = f.addressbookid
						where h.fullname like '%".$customer."%' and g.fullname like '%".$sales."%' and b.recordstatus=3
						and f.companyid=".$companyid."	and b.invoicedate < '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and f.addressbookid=a.addressbookid),0) as saldoawal,
						ifnull((select sum(ifnull(h.amount,0))
						from invoice h
						join giheader i on i.giheaderid=h.giheaderid
						join soheader j on j.soheaderid=i.soheaderid
						join employee k on k.employeeid = j.employeeid
						join addressbook l on l.addressbookid = j.addressbookid					
						where l.fullname like '%".$customer."%' and k.fullname like '%".$sales."%' and h.recordstatus=3
						and j.companyid=".$companyid." and j.addressbookid=a.addressbookid
						and h.invoicedate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as piutang,
						ifnull((select sum(ifnull((select sum((ifnull(c.cashamount,0)+ifnull(c.bankamount,0)+ifnull(c.discamount,0)+ifnull(c.returnamount,0)+ifnull(c.obamount,0))*ifnull(c.currencyrate,0))
						from cutarinv c
						join cutar d on d.cutarid=c.cutarid
						where d.recordstatus=3 and c.invoiceid=b.invoiceid and d.companyid=".$companyid."
						and d.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."'and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0))
						from invoice b
						join giheader e on e.giheaderid=b.giheaderid
						join soheader f on f.soheaderid=e.soheaderid
						join employee g on g.employeeid = f.employeeid
						join addressbook h on h.addressbookid = f.addressbookid
						where h.fullname like '%".$customer."%' and g.fullname like '%".$sales."%' and b.recordstatus=3
						and f.companyid=".$companyid."
						and b.invoicedate<='".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and f.addressbookid=a.addressbookid),0) as dibayar
						from addressbook a
						where a.fullname like '%".$customer."%') z
						where z.saldoawal<>0 or z.piutang<>0 or z.dibayar<>0
						order by fullname";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($dataReader as $row)
			$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
							->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
							->setCellValueByColumnAndRow(5,1,GetCompanyCode($companyid));
							$line=4;
$this->phpExcel->getActiveSheet()->getStyle("A4:F4")->getFont()->setBold(true);
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'Customer')
						->setCellValueByColumnAndRow(2,$line,'Saldo Awal')					
						->setCellValueByColumnAndRow(3,$line,'Piutang')
						->setCellValueByColumnAndRow(4,$line,'Dibayar')
						->setCellValueByColumnAndRow(5,$line,'Saldo Akhir');
					$line++;
					
				$i=0;$saldoawal=0;$piutang=0;$dibayar=0;$saldoakhir=0;				
								
		foreach($dataReader as $row)
		{
			$i+=1;
			$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(0,$line,$i)
							->setCellValueByColumnAndRow(1,$line,$row['fullname'])
							->setCellValueByColumnAndRow(2,$line,($row['saldoawal']/$per))							
							->setCellValueByColumnAndRow(3,$line,($row['piutang']/$per))					
							->setCellValueByColumnAndRow(4,$line,($row['dibayar']/$per))
							->setCellValueByColumnAndRow(5,$line,(($row['saldoawal'] + $row['piutang'] - $row['dibayar'])/$per));
							$line++;
							$saldoawal += $row['saldoawal']/$per;
							$piutang += $row['piutang']/$per;
							$dibayar += $row['dibayar']/$per;
							$saldoakhir += ($row['saldoawal'] + $row['piutang'] - $row['dibayar'])/$per;	
							
		}
		$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(1,$line,'TOTAL')
						->setCellValueByColumnAndRow(2,$line,($saldoawal))
						->setCellValueByColumnAndRow(3,$line,($piutang))
						->setCellValueByColumnAndRow(4,$line,($dibayar))
						->setCellValueByColumnAndRow(5,$line,($saldoakhir));
						$line++;
						$line += 1;
		$this->getFooterXLS($this->phpExcel);
	}	
	//5
	public function RincianFakturdanReturJualBelumLunasXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)			
	{
		//$_GET['company'],$_GET['sloc'],$_GET['materialgroup'],$_GET['customer'],$_GET['product'],$_GET['startdate'],$_GET['enddate'],$_GET['per']
		$this->menuname='rincianfakturdanreturjualbelumlunas';
		parent::actionDownxls();
		$nilaitot1=0;$dibayar1=0; $sisa1=0;
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			$isdisplay1= " and c.isdisplay = ".$isdisplay." ";
		}
		$sql = "select distinct addressbookid,fullname,lat,lng,wanumber
					from (select b.giheaderid,d.addressbookid,d.fullname,a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
					ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,
					(select round(h.lat,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lat,(select round(h.lng,6) from address h where h.addressbookid=d.addressbookid Limit 1) as lng,
					ifnull((select h.wanumber from addresscontact h where h.addressbookid=d.addressbookid Limit 1),'') as wanumber
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid
					left join salesarea f on f.salesareaid = d.salesareaid

					where e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
					and d.fullname like '%".$customer."%' and f.areaname like '%".$salesarea."%' ".$isdisplay1."
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount
		";
		if ($sloc !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join sloc l on l.slocid=k.slocid where l.sloccode like '%".$sloc."%') ";
		}
		if ($materialgroup !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join productplant l on l.productid=k.productid and k.slocid=l.slocid and k.unitofmeasureid=l.unitofissue join materialgroup m on m.materialgroupid=l.materialgroupid where m.description like '%".$materialgroup."%') ";
		}
		if ($product !== '') 
		{
				$sql = $sql . "and  giheaderid in (select k.giheaderid from gidetail k join product l on l.productid=k.productid where l.productname like '%".$product."%') ";
		}
		if ($umurpiutang !== '') 
		{
				$sql = $sql . "and  umur > ".$umurpiutang." ";
		}
		$sql = $sql . "order by fullname";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
    
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			if($isdisplay == "1")
			{
				$title='Rincian Faktur & Retur Jual Belum Lunas (HANYA DISPLAY)';
			}
			else if($isdisplay == "0")
			{
				$title='Rincian Faktur & Retur Jual Belum Lunas (BUKAN DISPLAY)';
			}
		}
		else
		{
			$title='Rincian Faktur & Retur Jual Belum Lunas';
		}
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,1,$title)
			->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
			->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
			->setCellValueByColumnAndRow(7,1,GetCompanyCode($companyid));
		$line=4;
		
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)						
				->setCellValueByColumnAndRow(0,$line,$row['fullname'])
				->setCellValueByColumnAndRow(8,$line,'Koordinat:')
				->setCellValueByColumnAndRow(9,$line,$row['lat'].','.$row['lng']);
			$line++;
			
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,'No')
				->setCellValueByColumnAndRow(1,$line,'No. Dokumen')
				->setCellValueByColumnAndRow(2,$line,'Tanggal')					
				->setCellValueByColumnAndRow(3,$line,'J_Tempo')
				->setCellValueByColumnAndRow(4,$line,'Umur')
				->setCellValueByColumnAndRow(5,$line,'UT')
				->setCellValueByColumnAndRow(6,$line,'Nilai')
				->setCellValueByColumnAndRow(7,$line,'Kum_Bayar')
				->setCellValueByColumnAndRow(8,$line,'Sisa')
				->setCellValueByColumnAndRow(9,$line,'Sales');
			$line++;
			
			$sql1 = " select *, (amount-payamount) as sisa,(amount) as nilai
							from (select b.giheaderid,if(c.isdisplay=1,concat(a.invoiceno,'_D'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
							date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
							datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,ff.fullname as sales,
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

							where d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
							and d.addressbookid = '".$row['addressbookid']."' ".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
							where amount > payamount
			";
			if ($sloc !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join sloc l on l.slocid=k.slocid where l.sloccode like '%".$sloc."%') ";
			}
			if ($materialgroup !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join productplant l on l.productid=k.productid and k.slocid=l.slocid and k.unitofmeasureid=l.unitofissue join materialgroup m on m.materialgroupid=l.materialgroupid where m.description like '%".$materialgroup."%') ";
			}
			if ($product !== '') 
			{
					$sql1 = $sql1 . "and  giheaderid in (select k.giheaderid from gidetail k join product l on l.productid=k.productid where l.productname like '%".$product."%') ";
			}
			if ($umurpiutang !== '') 
			{
					$sql1 = $sql1 . "and  umur > ".$umurpiutang." ";
			}
			$sql1 = $sql1 . "order by umurtempo desc";
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			$i=0;$nilaitot=0;$dibayar=0; $sisa=0;            
			 
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
					->setCellValueByColumnAndRow(2,$line,$row1['invoicedate'])
					->setCellValueByColumnAndRow(3,$line,$row1['jatuhtempo'])
					->setCellValueByColumnAndRow(4,$line,$row1['umur'])
					->setCellValueByColumnAndRow(5,$line,$row1['umurtempo'])
					->setCellValueByColumnAndRow(6,$line,($row1['nilai']/$per))							
					->setCellValueByColumnAndRow(7,$line,($row1['payamount']/$per))					
					->setCellValueByColumnAndRow(8,$line,(($row1['nilai']/$per)-($row1['payamount']/$per)))
					->setCellValueByColumnAndRow(9,$line,$row1['sales']);
				$line++;
				$nilaitot += $row1['nilai']/$per;
				$dibayar += $row1['payamount']/$per;
				$sisa += (($row1['nilai']/$per)-($row1['payamount']/$per));
			}
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(3,$line,'Total')										
					->setCellValueByColumnAndRow(6,$line,($nilaitot))
					->setCellValueByColumnAndRow(7,$line,($dibayar))
					->setCellValueByColumnAndRow(8,$line,($sisa));
				$line++;
				$nilaitot1 += $nilaitot;
				$dibayar1 += $dibayar;
				$sisa1 += $sisa;
				$line += 1;
		}
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(3,$line,'GRAND TOTAL')										
			->setCellValueByColumnAndRow(6,$line,($nilaitot1))
			->setCellValueByColumnAndRow(7,$line,($dibayar1))
			->setCellValueByColumnAndRow(8,$line,($sisa1));
		$line++;
		
		$this->getFooterXLS($this->phpExcel);
	}
	//6
	public function RincianUmurPiutangDagangPerCustomerXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianumurpiutangdagangpercustomer';
		parent::actionDownxls();
		$total2sd0=0;$total20sd30=0;$total231sd60=0;$total261sd90=0;$total291sd120=0;$total2sd120=0;$totaltempo2=0;$total2=0;
		$sql ="select distinct addressbookid,fullname
					from (select d.addressbookid,d.fullname,a.amount,
					ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
						from cutarinv o
						join cutar p on p.cutarid=o.cutarid
						where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid
					where e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
					and d.fullname like '%".$customer."%' 
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount order by fullname";
								
            $dataReader=Yii::app()->db->createCommand($sql)->queryAll();
            
            foreach($dataReader as $row)
						$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))							
							->setCellValueByColumnAndRow(9,1,GetCompanyCode($companyid));
							$line=4;				
						foreach($dataReader as $row)
						{
							$this->phpExcel->getActiveSheet()->getStyle(1,$line)->getFont()->setBold(true);
							$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'Customer')
									->setCellValueByColumnAndRow(1,$line,$row['fullname'])
									->setCellValueByColumnAndRow(5,$line,'Sudah Jatuh Tempo');							
									$line++;
									
							$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'No')
									->setCellValueByColumnAndRow(1,$line,'No. Invoice')
									->setCellValueByColumnAndRow(2,$line,'T.O.P')					
									->setCellValueByColumnAndRow(3,$line,'Umur')
									->setCellValueByColumnAndRow(4,$line,'Belum Jatuh Tempo')
									->setCellValueByColumnAndRow(5,$line,'0-30 Hari')
									->setCellValueByColumnAndRow(6,$line,'31-60 Hari')
									->setCellValueByColumnAndRow(7,$line,'61-90 Hari')
									->setCellValueByColumnAndRow(8,$line,'91-120 Hari')
									->setCellValueByColumnAndRow(9,$line,'>120 Hari')
									->setCellValueByColumnAndRow(10,$line,'Jumlah')
									->setCellValueByColumnAndRow(11,$line,'Total');
							$line++;
							$sql1 = "select z.*,
														case when umurtempo < 0 then totamount else 0 end as sd0,
														case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
														case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
														case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
														case when umurtempo > 90 and umurtempo <= 120 then totamount else 0 end as 91sd120,
														case when umurtempo > 120 then totamount else 0 end as sd120
												from
												(select distinct a.invoiceno, a.invoicedate, datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
												datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate,interval d.paydays day)) as umurtempo,
												date_add(a.invoicedate,interval d.paydays day) as jatuhtempo,
												a.amount-ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
													from cutarinv o
													join cutar p on p.cutarid=o.cutarid
													where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as totamount,d.paydays
												from invoice a
												join giheader b on b.giheaderid = a.giheaderid
												join soheader c on c.soheaderid = b.soheaderid
												join paymentmethod d on d.paymentmethodid = c.paymentmethodid
												join employee e on e.employeeid = c.employeeid
												where  e.fullname like '%".$sales."%' and c.companyid = ".$companyid." and a.recordstatus = 3 and c.addressbookid = '".$row['addressbookid']."' 
												and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
												where totamount > 0
												order by invoicedate";

                $dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
                $i=0;$totalsd0=0;$total0sd30=0;$total31sd60=0;$total61sd90=0;$total91sd120=0;$totalsd120=0;$totaltempo=0;$total=0;								
								foreach($dataReader1 as $row1)
								{
									$i+=1;
									$this->phpExcel->setActiveSheetIndex(0)
											->setCellValueByColumnAndRow(0,$line,$i)
											->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
											->setCellValueByColumnAndRow(2,$line,$row1['paydays'])
											->setCellValueByColumnAndRow(3,$line,$row1['umur'])
											->setCellValueByColumnAndRow(4,$line,($row1['sd0']/$per))
											->setCellValueByColumnAndRow(5,$line,($row1['0sd30']/$per))							
											->setCellValueByColumnAndRow(6,$line,($row1['31sd60']/$per))					
											->setCellValueByColumnAndRow(7,$line,($row1['61sd90']/$per))
											->setCellValueByColumnAndRow(8,$line,($row1['91sd120']/$per))
											->setCellValueByColumnAndRow(9,$line,($row1['sd120']/$per))
											->setCellValueByColumnAndRow(10,$line,(($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['sd120'])/$per))
											->setCellValueByColumnAndRow(11,$line,(($row1['sd0']+$row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['sd120'])/$per));
									$line++;
									$totalsd0 += $row1['sd0']/$per;
									$total0sd30 += $row1['0sd30']/$per;
									$total31sd60 += $row1['31sd60']/$per;
									$total61sd90 += $row1['61sd90']/$per;
									$total61sd90 += $row1['91sd120']/$per;
									$totalsd120 += $row1['sd120']/$per;
									$totaltempo += ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['sd120'])/$per;
									$total += ($row1['sd0']+$row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['sd120'])/$per;
									
								}
								$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(1,$line,'Total')										
										->setCellValueByColumnAndRow(4,$line,($totalsd0))
										->setCellValueByColumnAndRow(5,$line,($total0sd30))
										->setCellValueByColumnAndRow(6,$line,($total31sd60))
										->setCellValueByColumnAndRow(7,$line,($total61sd90))
										->setCellValueByColumnAndRow(8,$line,($total91sd120))
										->setCellValueByColumnAndRow(9,$line,($totalsd120))
										->setCellValueByColumnAndRow(10,$line,($totaltempo))
										->setCellValueByColumnAndRow(11,$line,($total));
								$line++;
								$total2sd0 += $totalsd0;
								$total20sd30 += $total0sd30;
								$total231sd60 += $total31sd60;
								$total261sd90 += $total61sd90;
								$total291sd120 += $total91sd120;
								$total2sd120 += $totalsd120;
								$totaltempo2 += $totaltempo;
								$total2 += $total;
								$line += 1;
						}
						$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(1,$line,'GRAND TOTAL')										
										->setCellValueByColumnAndRow(4,$line,($total2sd0))
										->setCellValueByColumnAndRow(5,$line,($total20sd30))
										->setCellValueByColumnAndRow(6,$line,($total231sd60))
										->setCellValueByColumnAndRow(7,$line,($total261sd90))
										->setCellValueByColumnAndRow(8,$line,($total291sd120))
										->setCellValueByColumnAndRow(9,$line,($total2sd120))
										->setCellValueByColumnAndRow(10,$line,($totaltempo2))
										->setCellValueByColumnAndRow(11,$line,($total2));
								$line++;
		
		
		$this->getFooterXLS($this->phpExcel);
	}
	//7
	public function RekapUmurPiutangDagangPerCustomerXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekapumurpiutangdagangpercustomer';
		parent::actionDownxls();
		$sql = "select *,sum(sd0) as belumjatuhtempo, sum(0sd30) as sum0sd30,sum(31sd60) as sum31sd60, sum(61sd90) as sum61sd90, sum(sd90) as sumsd90
				from (select *,
				case when umurtempo < 0 then totamount else 0 end as sd0,
				case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
				case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
				case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
				case when umurtempo > 90 then totamount else 0 end as sd90
				from(select *,amount - payamount as totamount
				from (select d.fullname,e.paydays,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',
				date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
				date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
				datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,
				ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
				from cutarinv f
				join cutar g on g.cutarid=f.cutarid
				where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
				from invoice a
				inner join giheader b on b.giheaderid = a.giheaderid
				inner join soheader c on c.soheaderid = b.soheaderid
				inner join addressbook d on d.addressbookid = c.addressbookid
				inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
				inner join employee f on f.employeeid = c.employeeid			
				where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 	
				and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z)zz 
				where amount > payamount)zzz
				group by fullname
				order by fullname";
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
			foreach($dataReader as $row)
			$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))							
							->setCellValueByColumnAndRow(7,1,GetCompanyCode($companyid));
							$line=4;
			$i=0;$totalsd0=0;$total0sd30=0; $total31sd60=0;$total61sd90=0;$totalsd90=0;$total=0;		
					
			$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'No')
									->setCellValueByColumnAndRow(1,$line,'Nama Customer')
									->setCellValueByColumnAndRow(2,$line,'Belum Jatuh Tempo')								
									->setCellValueByColumnAndRow(3,$line,'0-30 Hari')
									->setCellValueByColumnAndRow(4,$line,'31-60 Hari')
									->setCellValueByColumnAndRow(5,$line,'61-90 Hari')
									->setCellValueByColumnAndRow(6,$line,'>90 Hari')
									->setCellValueByColumnAndRow(7,$line,'Total');
							$line++;
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,$i)
						->setCellValueByColumnAndRow(1,$line,$row['fullname'])						
						->setCellValueByColumnAndRow(2,$line,($row['belumjatuhtempo']/$per))
						->setCellValueByColumnAndRow(3,$line,($row['sum0sd30']/$per))							
						->setCellValueByColumnAndRow(4,$line,($row['sum31sd60']/$per))					
						->setCellValueByColumnAndRow(5,$line,($row['sum61sd90']/$per))
						->setCellValueByColumnAndRow(6,$line,($row['sumsd90']/$per))
						->setCellValueByColumnAndRow(7,$line,(($row['belumjatuhtempo']+$row['sum0sd30']+$row['sum31sd60']+$row['sum61sd90']+$row['sumsd90'])/$per));
					$line++;
					$totalsd0 += $row['belumjatuhtempo']/$per;
					$total0sd30 += $row['sum0sd30']/$per;
          			$total31sd60 += $row['sum31sd60']/$per;
					$total61sd90 += $row['sum61sd90']/$per;
					$totalsd90 += $row['sumsd90']/$per;
					$total += ($row['belumjatuhtempo']+$row['sum0sd30']+$row['sum31sd60']+$row['sum61sd90']+$row['sumsd90'])/$per;
					
			}
			$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(1,$line,'Total')										
										->setCellValueByColumnAndRow(2,$line,($totalsd0))
										->setCellValueByColumnAndRow(3,$line,($total0sd30))
										->setCellValueByColumnAndRow(4,$line,($total31sd60))
										->setCellValueByColumnAndRow(5,$line,($total61sd90))
										->setCellValueByColumnAndRow(6,$line,($totalsd90))
										->setCellValueByColumnAndRow(7,$line,($total));
								$line++;
		$this->getFooterXLS($this->phpExcel);
	}
	//8
	public function RincianFakturdanReturJualBelumLunasPerSalesXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianfakturdanreturjualbelumlunaspersales';
		parent::actionDownxls();
		$nilaitot2=0;$dibayar2=0;$sisa2=0;
		$sql = "select distinct employeeid,fullname
						from (select f.employeeid,f.fullname,a.amount,
						ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
							from cutarinv o
							join cutar p on p.cutarid=o.cutarid
							where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee f on f.employeeid=c.employeeid
						where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
						where amount > payamount order by fullname";
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
			foreach($dataReader as $row)
			$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
							->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
							->setCellValueByColumnAndRow(7,1,GetCompanyCode($companyid));
							$line=4;				
			foreach($dataReader as $row)
			{
				$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'SALESMAN')
									->setCellValueByColumnAndRow(1,$line,': '.$row['fullname']);							
						$line++;
						$sql1 = "select distinct addressbookid,fullname
						from (select d.addressbookid,d.fullname,a.amount,
						datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
						ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
							from cutarinv o
							join cutar p on p.cutarid=o.cutarid
							where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee f on f.employeeid = c. employeeid
						where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."
						and c.employeeid = '".$row['employeeid']."'
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
						where amount > payamount ";
                if ($_GET['umurpiutang'] !== '') 
                {
                        $sql1 = $sql1 . "and  umur > ".$_GET['umurpiutang']." order by fullname";
                }
                else 
                {
                        $sql1 = $sql1 . "order by fullname";
                }
				$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
				$nilaitot1=0;$dibayar1=0;$sisa1=0;			
					
				foreach($dataReader1 as $row1)
				{
					$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'Customer')
									->setCellValueByColumnAndRow(1,$line,': '.$row1['fullname']);							
						$line++;
						
						
					$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'No. Dokumen')
						->setCellValueByColumnAndRow(2,$line,'Tanggal')					
						->setCellValueByColumnAndRow(3,$line,'J_Tempo')
						->setCellValueByColumnAndRow(4,$line,'Umur')
						->setCellValueByColumnAndRow(5,$line,'UT')
						->setCellValueByColumnAndRow(6,$line,'Nilai')
						->setCellValueByColumnAndRow(7,$line,'Kum_Bayar')
						->setCellValueByColumnAndRow(8,$line,'Sisa');
						$line++;
						
						$sql2 = "select *
						from (select if(c.isdisplay=1,concat(a.invoiceno,'_D'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
						date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
						datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
						datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,
						ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
							from cutarinv o
							join cutar p on p.cutarid=o.cutarid
							where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee f on f.employeeid = c.employeeid
						where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."
						and d.addressbookid = '".$row1['addressbookid']."' and c.employeeid = '".$row['employeeid']."'
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
						where amount > payamount ";
                    if ($_GET['umurpiutang'] !== '') 
                    {
                            $sql2 = $sql2 . "and  umur > ".$_GET['umurpiutang']." order by umurtempo desc";
                    }
                    else 
                    {
                            $sql2 = $sql2 . "order by umurtempo desc";
                    }
					$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
					$i=0;$nilaitot=0;$dibayar=0;$sisa=0;					
									
					foreach($dataReader2 as $row2)
					{
						$i+=1;
									$this->phpExcel->setActiveSheetIndex(0)
											->setCellValueByColumnAndRow(0,$line,$i)
											->setCellValueByColumnAndRow(1,$line,$row2['invoiceno'])
											->setCellValueByColumnAndRow(2,$line,$row2['invoicedate'])
											->setCellValueByColumnAndRow(3,$line,$row2['jatuhtempo'])
											->setCellValueByColumnAndRow(4,$line,$row2['umur'])
											->setCellValueByColumnAndRow(5,$line,$row2['umurtempo'])
											->setCellValueByColumnAndRow(6,$line,($row2['amount']/$per))							
											->setCellValueByColumnAndRow(7,$line,($row2['payamount']/$per))					
											->setCellValueByColumnAndRow(8,$line,(($row2['amount']-$row2['payamount'])/$per));
									$line++;
									$nilaitot += $row2['amount']/$per;
									$dibayar += $row2['payamount']/$per;
									$sisa += ($row2['amount']-$row2['payamount'])/$per;
					}
					$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(4,$line,'TOTAL '.$row1['fullname'])									
										->setCellValueByColumnAndRow(6,$line,($nilaitot))
										->setCellValueByColumnAndRow(7,$line,($dibayar))
										->setCellValueByColumnAndRow(8,$line,($sisa));
								$line++;
								$nilaitot1 += $nilaitot;
								$dibayar1 += $dibayar;
								$sisa1 += $sisa;
								$line += 1;
				}
				$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(4,$line,'TOTAL SALES '.$row['fullname'])									
										->setCellValueByColumnAndRow(6,$line,($nilaitot1))
										->setCellValueByColumnAndRow(7,$line,($dibayar1))
										->setCellValueByColumnAndRow(8,$line,($sisa1));
								$line++;
								$nilaitot2 += $nilaitot1;
								$dibayar2 += $dibayar1;
								$sisa2 += $sisa1;
								$line += 1;
			}
			$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(4,$line,'GRAND TOTAL')									
										->setCellValueByColumnAndRow(6,$line,($nilaitot2))
										->setCellValueByColumnAndRow(7,$line,($dibayar2))
										->setCellValueByColumnAndRow(8,$line,($sisa2));
								$line++;
		
		$this->getFooterXLS($this->phpExcel);
		
	}
	//9
	public function RekapKontrolPiutangCustomervsPlafonXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekapkontrolpiutangcustomervsplafon';
		parent::actionDownxls();
		$totalbelum=0;$totalsudah=0;$totalnilai=0;$totalplafon=0;			
					
		$sql = "select fullname,sum(belum) as belum,sum(sudah) as sudah,sum(nilai) as nilai,plafon from
								(select z.*,
								case when umur <= paydays then nilai else 0 end as belum,
								case when umur > paydays then nilai else 0 end as sudah
								from
								(select distinct a.invoiceno,a.invoicedate,date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,e.paydays,
								datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
								a.amount-(ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
												from cutarinv f
												join cutar g on g.cutarid=f.cutarid
												where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0)) as nilai,
								d.creditlimit as plafon,d.fullname
								from invoice a
								join giheader b on b.giheaderid=a.giheaderid
								join soheader c on c.soheaderid=b.soheaderid
								join addressbook d on d.addressbookid=c.addressbookid
								join paymentmethod e on e.paymentmethodid=c.paymentmethodid
								join employee f on f.employeeid = c.employeeid
								where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
								and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								order by invoicedate) z where nilai > 0) zz group by fullname order by fullname";
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			$i=0;
			foreach($dataReader as $row)
			$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))							
							->setCellValueByColumnAndRow(6,1,GetCompanyCode($companyid));
							$line=4;	
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'Customer')
						->setCellValueByColumnAndRow(2,$line,'Belum JT')					
						->setCellValueByColumnAndRow(3,$line,'Sudah JT')
						->setCellValueByColumnAndRow(4,$line,'Jumlah')
						->setCellValueByColumnAndRow(5,$line,'Plafon')						
						->setCellValueByColumnAndRow(6,$line,'Over/(Under)');
					$line++;		
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,$i)
						->setCellValueByColumnAndRow(1,$line,$row['fullname'])							
						->setCellValueByColumnAndRow(2,$line,($row['belum']/$per))
						->setCellValueByColumnAndRow(3,$line,($row['sudah']/$per))							
						->setCellValueByColumnAndRow(4,$line,($row['nilai']/$per))					
						->setCellValueByColumnAndRow(5,$line,($row['plafon']/$per))
						->setCellValueByColumnAndRow(6,$line,(($row['nilai'] - $row['plafon'])/$per));
				$line++;
				$totalbelum += $row['belum']/$per;
				$totalsudah += $row['sudah']/$per;
				$totalnilai += $row['nilai']/$per;
				$totalplafon += $row['plafon']/$per;			
			}
				
			$line += 1;
			$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(1,$line,'TOTAL')									
										->setCellValueByColumnAndRow(2,$line,($totalbelum))
										->setCellValueByColumnAndRow(3,$line,($totalsudah))
										->setCellValueByColumnAndRow(4,$line,($totalnilai))
										->setCellValueByColumnAndRow(5,$line,($totalplafon))
										->setCellValueByColumnAndRow(6,$line,($totalnilai - $totalplafon));
								$line++;
		$this->getFooterXLS($this->phpExcel);
	}
	//10
	public function RincianKontrolPiutangCustomervsPlafonXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rinciankontrolpiutangcustomervsplafon';
		parent::actionDownxls();
		$totalbelum1=0;$totalsudah1=0;$totalnilai1=0;$totalplafon1=0;								
								
		$sql ="select distinct addressbookid,fullname
						from (select d.addressbookid,d.fullname,a.amount,
						ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
						from cutarinv f
						join cutar g on g.cutarid=f.cutarid
						where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						join giheader b on b.giheaderid = a.giheaderid
						join soheader c on c.soheaderid = b.soheaderid
						join addressbook d on d.addressbookid = c.addressbookid
						join employee e on e.employeeid = c.employeeid
						where d.fullname like '%".$customer."%' and e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
						and d.fullname like '%".$customer."%' 
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
						where amount > payamount
						order by fullname";
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
			foreach($dataReader as $row)
			$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))							
							->setCellValueByColumnAndRow(9,1,GetCompanyCode($companyid));
							$line=4;				
			foreach($dataReader as $row)
			{
				$this->phpExcel->setActiveSheetIndex(0)	
						->setCellValueByColumnAndRow(0,$line,'CUSTOMER : '.$row['fullname']);							
					$line++;
					
				$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'Dokumen')
						->setCellValueByColumnAndRow(2,$line,'Tanggal')					
						->setCellValueByColumnAndRow(3,$line,'J_Tempo')
						->setCellValueByColumnAndRow(4,$line,'Umur')
						->setCellValueByColumnAndRow(5,$line,'Belum JT')
						->setCellValueByColumnAndRow(6,$line,'Sudah JT')
						->setCellValueByColumnAndRow(7,$line,'Jumlah')
						->setCellValueByColumnAndRow(8,$line,'Plafon')
						->setCellValueByColumnAndRow(9,$line,'Over/(Under)');
					$line++;
					$sql1 ="select zz.*,
												case when umur <= paydays then sisa else 0 end as belum,
												case when umur > paydays then sisa else 0 end as sudah
												from
												(select *, (amount-payamount) as sisa,(amount) as nilai
												from (select a.invoiceno,a.invoicedate,e.paydays,
												date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
												datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,d.creditlimit,
												ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
												from cutarinv f
												join cutar g on g.cutarid=f.cutarid
												where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
												from invoice a
												inner join giheader b on b.giheaderid = a.giheaderid
												inner join soheader c on c.soheaderid = b.soheaderid
												inner join addressbook d on d.addressbookid = c.addressbookid
												inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
												inner join employee f on f.employeeid = c.employeeid
												where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."  
												and d.addressbookid = ".$row['addressbookid']."
												and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z)zz
												where amount > payamount 
												order by invoiceno";
				$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();			
				$i=0;$totalbelum=0;$totalsudah=0;$totalnilai=0;
						
						
				foreach($dataReader1 as $row1)
				{
					$i+=1;
						$this->phpExcel->setActiveSheetIndex(0)
								->setCellValueByColumnAndRow(0,$line,$i)
								->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
								->setCellValueByColumnAndRow(2,$line,$row1['invoicedate'])
								->setCellValueByColumnAndRow(3,$line,$row1['jatuhtempo'])
								->setCellValueByColumnAndRow(4,$line,$row1['umur'])
								->setCellValueByColumnAndRow(5,$line,($row1['belum']/$per))
								->setCellValueByColumnAndRow(6,$line,($row1['sudah']/$per))							
								->setCellValueByColumnAndRow(7,$line,($row1['sisa']/$per))					
								->setCellValueByColumnAndRow(8,$line,'')
								->setCellValueByColumnAndRow(9,$line,'');
						$line++;
						$totalbelum += $row1['belum']/$per;
						$totalsudah += $row1['sudah']/$per;
						$totalnilai += $row1['sisa']/$per;
						
				}
				$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(4,$line,'SUB TOTAL')									
										->setCellValueByColumnAndRow(5,$line,($totalbelum))
										->setCellValueByColumnAndRow(6,$line,($totalsudah))
										->setCellValueByColumnAndRow(7,$line,($totalnilai))
										->setCellValueByColumnAndRow(8,$line,($row1['creditlimit']/$per))
										->setCellValueByColumnAndRow(9,$line,($totalnilai - ($row1['creditlimit']/$per)));
								$line++;
								$totalbelum1 += $totalbelum;
								$totalsudah1 += $totalsudah;
								$totalnilai1 += $totalnilai;
								$totalplafon1 += $row1['creditlimit']/$per;
								$line += 1;
			}
			$this->phpExcel->setActiveSheetIndex(0)
										->setCellValueByColumnAndRow(4,$line,'TOTAL')									
										->setCellValueByColumnAndRow(5,$line,($totalbelum1))
										->setCellValueByColumnAndRow(6,$line,($totalsudah1))
										->setCellValueByColumnAndRow(7,$line,($totalnilai1))
										->setCellValueByColumnAndRow(8,$line,($totalplafon1))
										->setCellValueByColumnAndRow(9,$line,($totalnilai1 - $totalplafon1));
								$line++;
								
								
		$this->getFooterXLS($this->phpExcel);
	}
	//11
	public function KonfirmasiPiutangDagangXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='konfirmasipiutangdagang';
		parent::actionDownxls();
				$sql ="select distinct addressbookid,fullname,alamat,phoneno
					from (select d.addressbookid,d.fullname,a.amount,
					ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount,
					(select h.addressname	from address h where h.addressbookid=d.addressbookid Limit 1) as alamat,
					(select i.phoneno	from address i where i.addressbookid=d.addressbookid Limit 1) as phoneno
					from invoice a
					join giheader b on b.giheaderid = a.giheaderid
					join soheader c on c.soheaderid = b.soheaderid
					join addressbook d on d.addressbookid = c.addressbookid
					join employee e on e.employeeid = c.employeeid			
					where d.fullname like '%".$customer."%' and e.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 
					and d.fullname like '%".$customer."%' 
					and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
					where amount > payamount
					order by fullname";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($dataReader as $row)
		$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
							->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
							$line=4;				
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)	
						->setCellValueByColumnAndRow(0,$line,'Kepada YTH,');
					$line++;
			$this->phpExcel->setActiveSheetIndex(0)							
						->setCellValueByColumnAndRow(1,$line,$row['fullname'])
						->setCellValueByColumnAndRow(8,$line,'Note : Bukti Konfirmasi ini tidak untuk Penagihan');
					$line++;
			$this->phpExcel->setActiveSheetIndex(0)	
						->setCellValueByColumnAndRow(0,$line,'Di Tempat');
					$line++;
			$this->phpExcel->setActiveSheetIndex(0)	
				->setCellValueByColumnAndRow(4,$line,'Hal : KONFIRMASI PIUTANG');
					$line+=3;
			$this->phpExcel->setActiveSheetIndex(0)	
				->setCellValueByColumnAndRow(0,$line,'Dengan Hormat,');
					$line+=2;
			$this->phpExcel->setActiveSheetIndex(0)	
						->setCellValueByColumnAndRow(0,$line,'Dalam rangka kegiatan rutin dan tertib administrasi, maka dengan ini kami mohon kesediaan Bapak / Ibu Relasi kami untuk dapat memberikan informasi');
					$line++;
			$this->phpExcel->setActiveSheetIndex(0)	
						->setCellValueByColumnAndRow(0,$line,'atas saldo tagihan perusahaan kami yang masih tersisa terhadap :');
					$line++;
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(1,$line,'Toko / Customer')
						->setCellValueByColumnAndRow(2,$line,' : '.$row['fullname'])
						->setCellValueByColumnAndRow(5,$line,'No. Telepon')
						->setCellValueByColumnAndRow(6,$line,' : '.$row['phoneno']);							
					$line++;
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(1,$line,'Alamat')
						->setCellValueByColumnAndRow(2,$line,' : '.$row['alamat']);							
					$line++;
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(1,$line,'Per Tanggal')
						->setCellValueByColumnAndRow(2,$line,' : '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));							
					$line+=2;
			$this->phpExcel->setActiveSheetIndex(0)	
						->setCellValueByColumnAndRow(0,$line,'Dengan perincian sebagai berikut : ');
					$line+=2;
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'Dokumen')
						->setCellValueByColumnAndRow(2,$line,'Tanggal')					
						->setCellValueByColumnAndRow(3,$line,'J_Tempo')
						->setCellValueByColumnAndRow(4,$line,'Nilai Invoice')
						->setCellValueByColumnAndRow(5,$line,'Jml_Cicilan')
						->setCellValueByColumnAndRow(6,$line,'Sisa Invoice')
						->setCellValueByColumnAndRow(7,$line,'Umur')
						->setCellValueByColumnAndRow(8,$line,'Sales');
					$line++;
					
				$sql1 ="select *
						from (select a.invoiceno,a.invoicedate,e.paydays,
						date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
						datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,d.creditlimit,
						ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
						from cutarinv f
						join cutar g on g.cutarid=f.cutarid
						where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee f on f.employeeid = c.employeeid
						where d.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid."  
						and d.addressbookid = ".$row['addressbookid']."
						and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."')z
						where amount > payamount 
						order by invoiceno";
						
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			$i=0;$totalamount=0;$totalpayamount=0;
			
			foreach($dataReader1 as $row1)
			{
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
					->setCellValueByColumnAndRow(2,$line,$row1['invoicedate'])
					->setCellValueByColumnAndRow(3,$line,$row1['jatuhtempo'])	
					->setCellValueByColumnAndRow(4,$line,($row1['amount']/$per))
					->setCellValueByColumnAndRow(5,$line,($row1['payamount']/$per))						
					->setCellValueByColumnAndRow(6,$line,(($row1['amount'] - $row1['payamount'])/$per))
					->setCellValueByColumnAndRow(7,$line,$row1['umur']);
				$line+=1;			
							
			}
			$totalamount += $row1['amount']/$per;
			$totalpayamount += $row1['payamount']/$per;
			
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(3,$line,'Saldo Piutang Dagang')
				->setCellValueByColumnAndRow(4,$line,' : '.($row1['amount']-$row1['payamount'])/$per);							
			$line+=2;
			
			$this->phpExcel->setActiveSheetIndex(0)	
				->setCellValueByColumnAndRow(0,$line,'Mengingat pentingnya hal ini, maka kami sangat mengharapkan bantuannya dan jika dari jumlah tersebut diatas ada yang tidak setuju');
			$line++;
			
			$this->phpExcel->setActiveSheetIndex(0)	
				->setCellValueByColumnAndRow(0,$line,'mohon penjelasannya.');
			$line++;
			
			$this->phpExcel->setActiveSheetIndex(0)	
				->setCellValueByColumnAndRow(1,$line,'Alasan :');
			$line+=6;
			
			$this->phpExcel->setActiveSheetIndex(0)	
				->setCellValueByColumnAndRow(1,$line,'Menyetujui,')
				->setCellValueByColumnAndRow(4,$line,'Mengetahui,')
				->setCellValueByColumnAndRow(7,$line,'Hormat kami,');
			$line+=6;
			
			$this->phpExcel->setActiveSheetIndex(0)	
				->setCellValueByColumnAndRow(1,$line,'( TTd / Cap Toko /Nama Jelas )')
				->setCellValueByColumnAndRow(4,$line,'(         FM / BM         )')
				->setCellValueByColumnAndRow(7,$line,'(                                  )');
			$line+=2;
			
			$this->phpExcel->setActiveSheetIndex(0)	
				->setCellValueByColumnAndRow(0,$line,'Catatan : Konfirmasi ini bukan sebagai bukti penagihan');
			$line+=3;
			
		}
		$this->getFooterXLS($this->phpExcel);
	}	
	//12
	public function RekapInvoiceARPerDokumenBelumStatusMaxXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekapinvoicearperdokumenbelumstatusmax';
		parent::actionDownxls();
		$sql ="select distinct a.invoiceid, a.invoiceno, a.invoicedate, b.gino, a.headernote, c.companyid, a.recordstatus, a.statusname
				from invoice a
				join giheader b on b.giheaderid = a.giheaderid
				join soheader c on c.soheaderid = b.soheaderid
				where a.invoicedate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				and a.recordstatus between 1 and (3-1)
				and a.invoiceno is not null
				and c.companyid = ".$companyid."
				order by a.recordstatus";
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();			
			
			foreach($dataReader as $row)
			$this->phpExcel->setActiveSheetIndex(0)				
						->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
						->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
			$line=4;	
			$i=0;
			
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'ID Transaksi')
						->setCellValueByColumnAndRow(2,$line,'No Transaksi')					
						->setCellValueByColumnAndRow(3,$line,'Tanggal')
						->setCellValueByColumnAndRow(4,$line,'No Referensi')
						->setCellValueByColumnAndRow(5,$line,'Keterangan')
						->setCellValueByColumnAndRow(6,$line,'Status');
			$line++;	
			
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row['invoiceid'])
					->setCellValueByColumnAndRow(2,$line,$row['invoiceno'])
					->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])))
					->setCellValueByColumnAndRow(4,$line,$row['gino'])
					->setCellValueByColumnAndRow(5,$line,$row['headernote'])				
					->setCellValueByColumnAndRow(6,$line,$row['statusname']);
				$line++;				
			}
			
		$this->getFooterXLS($this->phpExcel);
	}
	//13
	public function RekapNotaReturPenjualanPerDokumenBelumStatusMaxXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekapnotareturpenjualanperdokumenbelumstatusmax';
		parent::actionDownxls();
			$sql ="select distinct a.notagirid, a.notagirno, a.docdate, b.gireturno, a.headernote, a.recordstatus, a.companyid, a.statusname
				from notagir a
				join giretur b on b.gireturid = a.gireturid
				where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				and a.recordstatus between 1 and (3-1)
				and b.gireturno is not null
				and a.companyid = ".$companyid."
				order by a.recordstatus";
		
			$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
			
			foreach($dataReader as $row)
			$this->phpExcel->setActiveSheetIndex(0)				
						->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
						->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
			$line=4;	
			$i=0;
			
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'ID Transaksi')
						->setCellValueByColumnAndRow(2,$line,'No Transaksi')					
						->setCellValueByColumnAndRow(3,$line,'Tanggal')
						->setCellValueByColumnAndRow(4,$line,'No Referensi')
						->setCellValueByColumnAndRow(5,$line,'Keterangan')
						->setCellValueByColumnAndRow(6,$line,'Status');
			$line++;			
			foreach($dataReader as $row)
			{
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row['notagirid'])
					->setCellValueByColumnAndRow(2,$line,$row['notagirno'])
					->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])))
					->setCellValueByColumnAndRow(4,$line,$row['gireturno'])
					->setCellValueByColumnAndRow(5,$line,$row['headernote'])				
					->setCellValueByColumnAndRow(6,$line,$row['statusname']);
				$line++;
			}
		$this->getFooterXLS($this->phpExcel);
	}
	//14
	public function RekapPelunasanPiutangPerDokumenBelumStatusMaxXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
	$this->menuname='rekappelunasanpiutangperdokumenbelumstatusmax';
	parent::actionDownxls();	
		$sql ="select distinct a.cutarid,a.cutarno,a.docdate, b.docno,a.headernote,a.recordstatus,a.statusname
				from cutar a
				join ttnt b on b.ttntid = a.ttntid
				where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
				and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				and a.recordstatus between 1 and (3-1)
				and b.docno is not null
				and a.companyid = ".$companyid."
				order by a.recordstatus";
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($dataReader as $row)
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;	
		$i=0;
		
		$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,'No')
					->setCellValueByColumnAndRow(1,$line,'ID Transaksi')
					->setCellValueByColumnAndRow(2,$line,'No Transaksi')					
					->setCellValueByColumnAndRow(3,$line,'Tanggal')
					->setCellValueByColumnAndRow(4,$line,'No Referensi')
					->setCellValueByColumnAndRow(5,$line,'Keterangan')
					->setCellValueByColumnAndRow(6,$line,'Status');
		$line++;
		
		foreach($dataReader as $row)
		{
			$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row['cutarid'])
					->setCellValueByColumnAndRow(2,$line,$row['cutarno'])
					->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])))
					->setCellValueByColumnAndRow(4,$line,$row['docno'])
					->setCellValueByColumnAndRow(5,$line,$row['headernote'])				
					->setCellValueByColumnAndRow(6,$line,$row['statusname']);
				$line++;
		}
	
	$this->getFooterXLS($this->phpExcel);
	}	
	//15
	public function RincianPelunasanPiutangPerSalesXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianpelunasanpiutangpersales';
		parent::actionDownxls();
		$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;$wheresalesarea='';$whereproduct ='';
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						{$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if($salesarea!=='')
        {
            $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
            $sql = $sql. " and j.areaname like '%".$salesarea."%'";
        }
        if($product!=='')
        {
            $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
            left join product l on l.productid=k.productid";
            $sql = $sql. "and l.productname like '%".$product."%'";
        }
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
                
		foreach($dataReader as $row)
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;	
		$i=0;
        $wheresalesarea = ''; $whereproduct='';
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'SALES ')
					->setCellValueByColumnAndRow(1,$line,': '.$row['fullname']);							
			$line++;
				
			$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,'No')
					->setCellValueByColumnAndRow(1,$line,'No Invoice')
					->setCellValueByColumnAndRow(2,$line,'Tanggal')					
					->setCellValueByColumnAndRow(3,$line,'Tgl Byr')
					->setCellValueByColumnAndRow(4,$line,'Customer')
					->setCellValueByColumnAndRow(5,$line,'Hari')
					->setCellValueByColumnAndRow(6,$line,'Nil. Faktur')
					->setCellValueByColumnAndRow(7,$line,'Disc/Ret')
					->setCellValueByColumnAndRow(8,$line,'Jmlh Bayar')
					->setCellValueByColumnAndRow(9,$line,'0-30 Hari')
					->setCellValueByColumnAndRow(10,$line,'31-45 Hari')
					->setCellValueByColumnAndRow(11,$line,'46-60 Hari')
					->setCellValueByColumnAndRow(12,$line,'61-63 Hari')
					->setCellValueByColumnAndRow(13,$line,'64-70 Hari')
					->setCellValueByColumnAndRow(14,$line,'71-90 Hari')
					->setCellValueByColumnAndRow(15,$line,'>90 Hari');
			$line++;
			
			$sql1 = "select *,
								case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
								case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
								case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
								case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
								case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
								case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
								case when umur > 90 then nilaibayar else 0 end as sd91								
						from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
						a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
						and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
						and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid'].") z
						order by docdate,fullname,invoicedate
						";
            if($salesarea !=='') 
            {
                $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaidnd ";
                $sql1 = $sql1. " and j.areaname like '%".$salesarea."%'";
            }
            if($product!=='')
            {
                $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                left join product l on l.productid=k.productid";
                $sql1 = $sql1. "and l.productname like '%".$product."%'";
            }
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			$i=0;$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
			
			foreach($dataReader1 as $row1)
				{
					$i+=1;
						$this->phpExcel->setActiveSheetIndex(0)
								->setCellValueByColumnAndRow(0,$line,$i)
								->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
								->setCellValueByColumnAndRow(2,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])))
								->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row1['docdate'])))
								->setCellValueByColumnAndRow(4,$line,$row1['fullname'])
								->setCellValueByColumnAndRow(5,$line,$row1['umur'])
								->setCellValueByColumnAndRow(6,$line,($row1['amount']/$per))							
								->setCellValueByColumnAndRow(7,$line,($row1['disc']/$per))					
								->setCellValueByColumnAndRow(8,$line,($row1['nilaibayar']/$per))
								->setCellValueByColumnAndRow(9,$line,($row1['0sd30']/$per))
								->setCellValueByColumnAndRow(10,$line,($row1['31sd45']/$per))
								->setCellValueByColumnAndRow(11,$line,($row1['46sd60']/$per))
								->setCellValueByColumnAndRow(12,$line,($row1['61sd63']/$per))
								->setCellValueByColumnAndRow(13,$line,($row1['64sd70']/$per))
								->setCellValueByColumnAndRow(14,$line,($row1['71sd90']/$per))
								->setCellValueByColumnAndRow(15,$line,($row1['sd91']/$per));
						$line++;
						
					$totaldisc += ($row1['disc']/$per);
					$totalnilaibayar += ($row1['nilaibayar']/$per);
					$total0sd30 += ($row1['0sd30']/$per);
					$total31sd45 += ($row1['31sd45']/$per);
					$total46sd60 += ($row1['46sd60']/$per);
					$total61sd63 += ($row1['61sd63']/$per);
					$total64sd70 += ($row1['64sd70']/$per);
					$total71sd90 += ($row1['71sd90']/$per);
					$totalsd91 += ($row1['sd91']/$per);				
						
				}
				$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'TOTAL SALES '.$row['fullname'])				
					->setCellValueByColumnAndRow(7,$line,($totaldisc))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar))
					->setCellValueByColumnAndRow(9,$line,($total0sd30))
					->setCellValueByColumnAndRow(10,$line,($total31sd45))
					->setCellValueByColumnAndRow(11,$line,($total46sd60))
					->setCellValueByColumnAndRow(12,$line,($total61sd63))
					->setCellValueByColumnAndRow(13,$line,($total64sd70))
					->setCellValueByColumnAndRow(14,$line,($total71sd90))
					->setCellValueByColumnAndRow(15,$line,($totalsd91));
				$line+=2;
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(7,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(9,$line,($total0sd301))
					->setCellValueByColumnAndRow(10,$line,($total31sd451))
					->setCellValueByColumnAndRow(11,$line,($total46sd601))
					->setCellValueByColumnAndRow(12,$line,($total61sd631))
					->setCellValueByColumnAndRow(13,$line,($total64sd701))
					->setCellValueByColumnAndRow(14,$line,($total71sd901))
					->setCellValueByColumnAndRow(15,$line,($totalsd911));
				$line++;		
		
		$this->getFooterXLS($this->phpExcel);
	}
	//16
	public function RekapPelunasanPiutangPerSalesXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekappelunasanpiutangpersales';
		parent::actionDownxls();
		$i=0;$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;$wheresalesarea='';$whereproduct ='';
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
        
		if($salesarea!=='')
        {
            $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
            $sql = $sql. " and j.areaname like '%".$salesarea."%'";
        }
        if($product!=='')
        {
            $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
            left join product l on l.productid=k.productid";
            $sql = $sql. "and l.productname like '%".$product."%'";
        }
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($dataReader as $row)
		
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;		
		$i=0;
		$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'Nama Sales')
						->setCellValueByColumnAndRow(2,$line,'Disc/Ret')					
						->setCellValueByColumnAndRow(3,$line,'Jmlh Bayar')
						->setCellValueByColumnAndRow(4,$line,'0-30 Hari')
						->setCellValueByColumnAndRow(5,$line,'31-45 Hari')
						->setCellValueByColumnAndRow(6,$line,'46-60 Hari')
						->setCellValueByColumnAndRow(7,$line,'61-63 Hari')
						->setCellValueByColumnAndRow(8,$line,'64-70 Hari')
						->setCellValueByColumnAndRow(9,$line,'71-90 Hari')
						->setCellValueByColumnAndRow(10,$line,'>90 Hari');
		$line++;
        $wheresalesarea='';$whereproduct ='';
		foreach($dataReader as $row)	
		{
			$sql1 = "select *,
								case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
								case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
								case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
								case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
								case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
								case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
								case when umur > 90 then nilaibayar else 0 end as sd91								
						from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
						a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
						and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
                        and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid'].") z
						order by docdate,fullname
						";
            if($salesarea!=='')
            {
                $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                $sql1 = $sql1. " and j.areaname like '%".$salesarea."%'";
            }
            if($product!=='')
            {
                $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                left join product l on l.productid=k.productid";
                $sql1 = $sql1. "and l.productname like '%".$product."%'";
            }
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
								
			$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
			
			foreach($dataReader1 as $row1)
			{
				$totaldisc += ($row1['disc']/$per);
				$totalnilaibayar += ($row1['nilaibayar']/$per);
				$total0sd30 += ($row1['0sd30']/$per);
				$total31sd45 += ($row1['31sd45']/$per);
				$total46sd60 += ($row1['46sd60']/$per);
				$total61sd63 += ($row1['61sd63']/$per);
				$total64sd70 += ($row1['64sd70']/$per);
				$total71sd90 += ($row1['71sd90']/$per);
				$totalsd91 += ($row1['sd91']/$per);
			}
			$i+=1;
			$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row['fullname'])										
					->setCellValueByColumnAndRow(2,$line,($totaldisc))					
					->setCellValueByColumnAndRow(3,$line,($totalnilaibayar))
					->setCellValueByColumnAndRow(4,$line,($total0sd30))
					->setCellValueByColumnAndRow(5,$line,($total31sd45))
					->setCellValueByColumnAndRow(6,$line,($total46sd60))
					->setCellValueByColumnAndRow(7,$line,($total61sd63))
					->setCellValueByColumnAndRow(8,$line,($total64sd70))
					->setCellValueByColumnAndRow(9,$line,($total71sd90))
					->setCellValueByColumnAndRow(10,$line,($totalsd91));
			$line++;	
			$totaldisc1 += $totaldisc;
			$totalnilaibayar1 += $totalnilaibayar;
			$total0sd301 += $total0sd30;
			$total31sd451 += $total31sd45;
			$total46sd601 += $total46sd60;
			$total61sd631 += $total61sd63;
			$total64sd701 += $total64sd70;
			$total71sd901 += $total71sd90;
			$totalsd911 += $totalsd91;
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(1,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(2,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(3,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(4,$line,($total0sd301))
					->setCellValueByColumnAndRow(5,$line,($total31sd451))
					->setCellValueByColumnAndRow(6,$line,($total46sd601))
					->setCellValueByColumnAndRow(7,$line,($total61sd631))
					->setCellValueByColumnAndRow(8,$line,($total64sd701))
					->setCellValueByColumnAndRow(9,$line,($total71sd901))
					->setCellValueByColumnAndRow(10,$line,($totalsd911));
				$line++;
		
		$this->getFooterXLS($this->phpExcel);
	}
	//17
	public function RincianPelunasanPiutangPerSalesPerJenisBarangXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianpelunasanpiutangpersalesperjenisbarang';
		parent::actionDownxls();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0; $joinsalesarea = ''; $joinproduct=''; $wheresalesarea = ''; $whereproduct='';
		if($salesarea!=='')
		{
				$joinsalesarea = " join salesarea j on j.salesareaid=g.salesareaid ";
				$wheresalesarea = " and j.areaname like '%".$salesarea."%' ";
		}
		if($product!=='')
		{
				$joinproduct = " left join gidetail k on k.giheaderid=d.giheaderid
				left join product l on l.productid=k.productid ";
				$whereproduct = " and l.productname like '%".$product."%' ";
		}
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$wheresalesarea} {$product}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if($salesarea!=='')
        {
            $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
            $sql = $sql. " and j.areaname like '%".$salesarea."%'";
        }
        if($product!=='')
        {
            $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
            left join product l on l.productid=k.productid";
            $sql = $sql. "and l.productname like '%".$product."%'";
        }
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
                
		foreach($dataReader as $row)		
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;		
		$i=0;
        $wheresalesarea='';$whereproduct ='';
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'SALES ')
					->setCellValueByColumnAndRow(1,$line,': '.$row['fullname']);							
			$line++;			
			
			$sql1 = "select distinct materialgroupid,description								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid,
							(select j.description from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid join materialgroup j on j.materialgroupid=i.materialgroupid where h.giheaderid=d.giheaderid Limit 1) as description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
                            {$joinsalesarea} {$joinproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							{$wheresalesarea} {$whereproduct}
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							order by description
							";
			if($salesarea!=='')
            {
                $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                $sql1 = $sql1. " and j.areaname like '%".$salesarea."%'";
            }
            if($product!=='')
            {
                $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                left join product l on l.productid=k.productid";
                $sql1 = $sql1. "and l.productname like '%".$product."%'";
            }
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
            $wheresalesarea='';$whereproduct ='';
			foreach($dataReader1 as $row1)
			{
				$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'MATERIAL GROUP ')
					->setCellValueByColumnAndRow(1,$line,': '.$row1['description']);							
				$line++;
				
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							{$wheresalesarea} {$whereproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							where z.materialgroupid = ".$row1['materialgroupid']."
							order by docdate,fullname,invoicedate
							";
                if($salesarea!=='')
                {
                    $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                    $sql2 = $sql2. " and j.areaname like '%".$salesarea."%'";
                }
                if($product!=='')
                {
                    $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                    left join product l on l.productid=k.productid";
                    $sql2 = $sql2. "and l.productname like '%".$product."%'";
                }
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
				$i=0;$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,'No')
					->setCellValueByColumnAndRow(1,$line,'No Invoice')
					->setCellValueByColumnAndRow(2,$line,'Tanggal')					
					->setCellValueByColumnAndRow(3,$line,'Tgl Byr')
					->setCellValueByColumnAndRow(4,$line,'Customer')
					->setCellValueByColumnAndRow(5,$line,'Hari')
					->setCellValueByColumnAndRow(6,$line,'Nil. Faktur')
					->setCellValueByColumnAndRow(7,$line,'Disc/Ret')
					->setCellValueByColumnAndRow(8,$line,'Jmlh Bayar')
					->setCellValueByColumnAndRow(9,$line,'0-30 Hari')
					->setCellValueByColumnAndRow(10,$line,'31-45 Hari')
					->setCellValueByColumnAndRow(11,$line,'46-60 Hari')
					->setCellValueByColumnAndRow(12,$line,'61-63 Hari')
					->setCellValueByColumnAndRow(13,$line,'64-70 Hari')
					->setCellValueByColumnAndRow(14,$line,'71-90 Hari')
					->setCellValueByColumnAndRow(15,$line,'>90 Hari');
				$line++;
				
				foreach($dataReader2 as $row2)
				{
					$i+=1;
						$this->phpExcel->setActiveSheetIndex(0)
								->setCellValueByColumnAndRow(0,$line,$i)
								->setCellValueByColumnAndRow(1,$line,$row2['invoiceno'])
								->setCellValueByColumnAndRow(2,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])))
								->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row2['docdate'])))
								->setCellValueByColumnAndRow(4,$line,$row2['fullname'])
								->setCellValueByColumnAndRow(5,$line,$row2['umur'])
								->setCellValueByColumnAndRow(6,$line,($row2['amount']/$per))							
								->setCellValueByColumnAndRow(7,$line,($row2['disc']/$per))					
								->setCellValueByColumnAndRow(8,$line,($row2['nilaibayar']/$per))
								->setCellValueByColumnAndRow(9,$line,($row2['0sd30']/$per))
								->setCellValueByColumnAndRow(10,$line,($row2['31sd45']/$per))
								->setCellValueByColumnAndRow(11,$line,($row2['46sd60']/$per))
								->setCellValueByColumnAndRow(12,$line,($row2['61sd63']/$per))
								->setCellValueByColumnAndRow(13,$line,($row2['64sd70']/$per))
								->setCellValueByColumnAndRow(14,$line,($row2['71sd90']/$per))
								->setCellValueByColumnAndRow(15,$line,($row2['sd91']/$per));
						$line++;
						
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);
				}
				$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'JUMLAH MATERIAL GROUP '.$row1['description'])				
					->setCellValueByColumnAndRow(7,$line,($totaldisc))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar))
					->setCellValueByColumnAndRow(9,$line,($total0sd30))
					->setCellValueByColumnAndRow(10,$line,($total31sd45))
					->setCellValueByColumnAndRow(11,$line,($total46sd60))
					->setCellValueByColumnAndRow(12,$line,($total61sd63))
					->setCellValueByColumnAndRow(13,$line,($total64sd70))
					->setCellValueByColumnAndRow(14,$line,($total71sd90))
					->setCellValueByColumnAndRow(15,$line,($totalsd91));
				$line+=2;
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
			}
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'TOTAL SALES '.$row['fullname'])				
					->setCellValueByColumnAndRow(7,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(9,$line,($total0sd301))
					->setCellValueByColumnAndRow(10,$line,($total31sd451))
					->setCellValueByColumnAndRow(11,$line,($total46sd601))
					->setCellValueByColumnAndRow(12,$line,($total61sd631))
					->setCellValueByColumnAndRow(13,$line,($total64sd701))
					->setCellValueByColumnAndRow(14,$line,($total71sd901))
					->setCellValueByColumnAndRow(15,$line,($totalsd911));
				$line+=2;	
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(7,$line,($totaldisc2))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar2))
					->setCellValueByColumnAndRow(9,$line,($total0sd302))
					->setCellValueByColumnAndRow(10,$line,($total31sd452))
					->setCellValueByColumnAndRow(11,$line,($total46sd602))
					->setCellValueByColumnAndRow(12,$line,($total61sd632))
					->setCellValueByColumnAndRow(13,$line,($total64sd702))
					->setCellValueByColumnAndRow(14,$line,($total71sd902))
					->setCellValueByColumnAndRow(15,$line,($totalsd912));
		$line+=2;
		$this->getFooterXLS($this->phpExcel);
	}
	//18
	public function RincianPelunasanPiutangPerSalesPerJenisBarangWithoutOBXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianpelunasanpiutangpersalesperjenisbarangwithoutob';
		parent::actionDownxls();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0;  $joinsalesarea = ''; $joinproduct=''; $wheresalesarea = ''; $whereproduct='';
		if($salesarea!=='')
		{
				$joinsalesarea = " join salesarea j on j.salesareaid=g.salesareaid ";
				$wheresalesarea = " and j.areaname like '%".$salesarea."%' ";
		}
		if($product!=='')
		{
				$joinproduct = " left join gidetail k on k.giheaderid=d.giheaderid
				left join product l on l.productid=k.productid ";
				$whereproduct = " and l.productname like '%".$product."%' ";
		}
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
                        {$joinsalesarea} {$joinproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." {$wheresalesarea} {$whereproduct}
						and b.recordstatus=3 and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
                
		foreach($dataReader as $row)		
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;		
		$i=0;
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'SALES ')
					->setCellValueByColumnAndRow(1,$line,': '.$row['fullname']);							
			$line++;
			$sql1 = "select distinct materialgroupid,description								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
							(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid,
							(select j.description from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid join materialgroup j on j.materialgroupid=i.materialgroupid where h.giheaderid=d.giheaderid Limit 1) as description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
                            {$joinsalesarea} {$joinproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							{$wheresalesarea} {$whereproduct}
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							order by description
							";		
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();	
			$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
			foreach($dataReader1 as $row1)
			{
				$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'JENIS BARANG ')
					->setCellValueByColumnAndRow(1,$line,': '.$row1['description']);							
				$line++;
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount as nilaibayar,
							(select i.materialgroupid from gidetail h join productplant i on i.slocid=h.slocid and i.productid=h.productid and i.unitofissue=h.unitofmeasureid where h.giheaderid=d.giheaderid Limit 1) as materialgroupid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
                            {$joinsalesarea} {$joinproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							{$wheresalesarea} {$whereproduct}
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							where z.materialgroupid = ".$row1['materialgroupid']."
							order by docdate,fullname,invoicedate
							";
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
				$i=0;$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,'No')
					->setCellValueByColumnAndRow(1,$line,'No Invoice')
					->setCellValueByColumnAndRow(2,$line,'Tanggal')					
					->setCellValueByColumnAndRow(3,$line,'Tgl Byr')
					->setCellValueByColumnAndRow(4,$line,'Customer')
					->setCellValueByColumnAndRow(5,$line,'Hari')
					->setCellValueByColumnAndRow(6,$line,'Nil. Faktur')
					->setCellValueByColumnAndRow(7,$line,'Disc/Ret')
					->setCellValueByColumnAndRow(8,$line,'Jmlh Bayar')
					->setCellValueByColumnAndRow(9,$line,'0-30 Hari')
					->setCellValueByColumnAndRow(10,$line,'31-45 Hari')
					->setCellValueByColumnAndRow(11,$line,'46-60 Hari')
					->setCellValueByColumnAndRow(12,$line,'61-63 Hari')
					->setCellValueByColumnAndRow(13,$line,'64-70 Hari')
					->setCellValueByColumnAndRow(14,$line,'71-90 Hari')
					->setCellValueByColumnAndRow(15,$line,'>90 Hari');
				$line++;
				
				foreach($dataReader2 as $row2)
				{
					$i+=1;
						$this->phpExcel->setActiveSheetIndex(0)
								->setCellValueByColumnAndRow(0,$line,$i)
								->setCellValueByColumnAndRow(1,$line,$row2['invoiceno'])
								->setCellValueByColumnAndRow(2,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])))
								->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row2['docdate'])))
								->setCellValueByColumnAndRow(4,$line,$row2['fullname'])
								->setCellValueByColumnAndRow(5,$line,$row2['umur'])
								->setCellValueByColumnAndRow(6,$line,($row2['amount']/$per))							
								->setCellValueByColumnAndRow(7,$line,($row2['disc']/$per))					
								->setCellValueByColumnAndRow(8,$line,($row2['nilaibayar']/$per))
								->setCellValueByColumnAndRow(9,$line,($row2['0sd30']/$per))
								->setCellValueByColumnAndRow(10,$line,($row2['31sd45']/$per))
								->setCellValueByColumnAndRow(11,$line,($row2['46sd60']/$per))
								->setCellValueByColumnAndRow(12,$line,($row2['61sd63']/$per))
								->setCellValueByColumnAndRow(13,$line,($row2['64sd70']/$per))
								->setCellValueByColumnAndRow(14,$line,($row2['71sd90']/$per))
								->setCellValueByColumnAndRow(15,$line,($row2['sd91']/$per));
						$line++;
						
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);
				}
				$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'JUMLAH GROUP MATERIAL '.$row1['description'])				
					->setCellValueByColumnAndRow(7,$line,($totaldisc))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar))
					->setCellValueByColumnAndRow(9,$line,($total0sd30))
					->setCellValueByColumnAndRow(10,$line,($total31sd45))
					->setCellValueByColumnAndRow(11,$line,($total46sd60))
					->setCellValueByColumnAndRow(12,$line,($total61sd63))
					->setCellValueByColumnAndRow(13,$line,($total64sd70))
					->setCellValueByColumnAndRow(14,$line,($total71sd90))
					->setCellValueByColumnAndRow(15,$line,($totalsd91));
				$line+=2;
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
			}
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'TOTAL SALES '.$row['fullname'])				
					->setCellValueByColumnAndRow(7,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(9,$line,($total0sd301))
					->setCellValueByColumnAndRow(10,$line,($total31sd451))
					->setCellValueByColumnAndRow(11,$line,($total46sd601))
					->setCellValueByColumnAndRow(12,$line,($total61sd631))
					->setCellValueByColumnAndRow(13,$line,($total64sd701))
					->setCellValueByColumnAndRow(14,$line,($total71sd901))
					->setCellValueByColumnAndRow(15,$line,($totalsd911));
				$line+=2;	
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(7,$line,($totaldisc2))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar2))
					->setCellValueByColumnAndRow(9,$line,($total0sd302))
					->setCellValueByColumnAndRow(10,$line,($total31sd452))
					->setCellValueByColumnAndRow(11,$line,($total46sd602))
					->setCellValueByColumnAndRow(12,$line,($total61sd632))
					->setCellValueByColumnAndRow(13,$line,($total64sd702))
					->setCellValueByColumnAndRow(14,$line,($total71sd902))
					->setCellValueByColumnAndRow(15,$line,($totalsd912));
		$line+=2;
		$this->getFooterXLS($this->phpExcel);
	}
	//19
	public function RekapPelunasanPiutangPerSalesPerJenisBarangXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekappelunasanpiutangpersalesperjenisbarang';
		parent::actionDownxls();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0; $wheresalesarea=''; $whereproduct='';
		$sql = "select distinct f.employeeid,f.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
				        join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						{$wheresalesarea} {$whereproduct}
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if($salesarea!=='')
        {
            $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
            $sql = $sql. " and j.areaname like '%".$salesarea."%'";
        }
        if($product!=='')
        {
            $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
            left join product l on l.productid=k.productid";
            $sql = $sql. "and l.productname like '%".$product."%'";
        }
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($dataReader as $row)
		
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;		
		
        $wheresalesarea=''; $whereproduct='';        
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'SALES ')
					->setCellValueByColumnAndRow(1,$line,': '.$row['fullname']);							
			$line++;
			
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'Nama Sales')
						->setCellValueByColumnAndRow(2,$line,'Disc/Ret')					
						->setCellValueByColumnAndRow(3,$line,'Jmlh Bayar')
						->setCellValueByColumnAndRow(4,$line,'0-30 Hari')
						->setCellValueByColumnAndRow(5,$line,'31-45 Hari')
						->setCellValueByColumnAndRow(6,$line,'46-60 Hari')
						->setCellValueByColumnAndRow(7,$line,'61-63 Hari')
						->setCellValueByColumnAndRow(8,$line,'64-70 Hari')
						->setCellValueByColumnAndRow(9,$line,'71-90 Hari')
						->setCellValueByColumnAndRow(10,$line,'>90 Hari');
			$line++;
			$i=0;$totaldisc1=0;$totalnilaibayar1=0;$total0sd301=0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
			$sql1 = "select distinct i.slocid,i.description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join gidetail h on h.giheaderid=d.giheaderid
							join sloc i on i.slocid=h.slocid
							{$wheresalesarea} {$whereproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
							b.recordstatus=3 and f.employeeid = ".$row['employeeid']." and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
			if($salesarea!=='')
            {
                $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                $sql1 = $sql1. " and j.areaname like '%".$salesarea."%'";
            }
            if($product!=='')
            {
                $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                left join product l on l.productid=k.productid";
                $sql1 = $sql1. "and l.productname like '%".$product."%'";
            }
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			$wheresalesarea=''; $whereproduct='';
			foreach($dataReader1 as $row1)
			{
				
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select h.slocid from gidetail h where h.giheaderid=d.giheaderid Limit 1) as slocid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
				            join ttnt i on i.ttntid=b.ttntid
							join employee f on f.employeeid=i.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							{$wheresalesarea} {$whereproduct}
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and e.companyid = ".$companyid." and f.employeeid = ".$row['employeeid']." ) z
							where z.slocid = ".$row1['slocid']."
							order by docdate,fullname
							";
                if($salesarea!=='')
                {
                    $wheresalesarea = " join salesarea j on j.salesareaid=g.salesareaid";
                    $sql2 = $sql2. " and j.areaname like '%".$salesarea."%'";
                }
                if($product!=='')
                {
                    $whereproduct = " left join gidetail k on k.giheaderid=d.giheaderid
                    left join product l on l.productid=k.productid";
                    $sql2 = $sql2. "and l.productname like '%".$product."%'";
                }
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
				
				$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				foreach($dataReader2 as $row2)
				{
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);
				}
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,$i)
						->setCellValueByColumnAndRow(1,$line,$row1['description'])										
						->setCellValueByColumnAndRow(2,$line,($totaldisc))					
						->setCellValueByColumnAndRow(3,$line,($totalnilaibayar))
						->setCellValueByColumnAndRow(4,$line,($total0sd30))
						->setCellValueByColumnAndRow(5,$line,($total31sd45))
						->setCellValueByColumnAndRow(6,$line,($total46sd60))
						->setCellValueByColumnAndRow(7,$line,($total61sd63))
						->setCellValueByColumnAndRow(8,$line,($total64sd70))
						->setCellValueByColumnAndRow(9,$line,($total71sd90))
						->setCellValueByColumnAndRow(10,$line,($totalsd91));
				$line++;
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;			
				
			}
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(1,$line,'TOTAL SALES '.$row['fullname'])				
					->setCellValueByColumnAndRow(2,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(3,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(4,$line,($total0sd301))
					->setCellValueByColumnAndRow(5,$line,($total31sd451))
					->setCellValueByColumnAndRow(6,$line,($total46sd601))
					->setCellValueByColumnAndRow(7,$line,($total61sd631))
					->setCellValueByColumnAndRow(8,$line,($total64sd701))
					->setCellValueByColumnAndRow(9,$line,($total71sd901))
					->setCellValueByColumnAndRow(10,$line,($totalsd911));
			$line+=2;
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
				
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(1,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(2,$line,($totaldisc2))										
					->setCellValueByColumnAndRow(3,$line,($totalnilaibayar2))
					->setCellValueByColumnAndRow(4,$line,($total0sd302))
					->setCellValueByColumnAndRow(5,$line,($total31sd452))
					->setCellValueByColumnAndRow(6,$line,($total46sd602))
					->setCellValueByColumnAndRow(7,$line,($total61sd632))
					->setCellValueByColumnAndRow(8,$line,($total64sd702))
					->setCellValueByColumnAndRow(9,$line,($total71sd902))
					->setCellValueByColumnAndRow(10,$line,($totalsd912));
		$line++;
		
		$this->getFooterXLS($this->phpExcel);
	}
	//20
	public function RekapPenjualanVSPelunasanPerBulanPerCustomerXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekappenjualanvspelunasanpercustomer';
		parent::actionDownxls();
		$total2sd0=0;$total20sd30=0;$total231sd60=0;$total261sd90=0;$total291sd120=0;$total2sd120=0;$totaltempo2=0;$total2=0;
		$sql = "select * from
				(select z.fullname,
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=1 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=1 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_jan, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=1 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jan,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=2 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=2 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_feb, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=2 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_feb,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=3 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=3 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_mar, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=3 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_mar,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=4 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=4 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_apr, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=4 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_apr,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=5 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=5 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_mei, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=5 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_mei,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=6 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=6 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_jun, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=6 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jun,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=7 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=7 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_jul, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=7 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_jul,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=8 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=8 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_agus, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=8 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_agus,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=9 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=9 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_sept, 
                
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=9 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_sept,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=10 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=10 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_okt, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=10 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_okt,
                
                
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=11 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=11 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_nov, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=11 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_nov,
                
               
				((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid
				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(aa.gidate)=12 and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and month(c.gireturdate)=12 and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_des, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=12 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_des,
                
                ((select ifnull(sum(ifnull(a.amount,0)),0)
				from invoice a 
				join giheader aa on aa.giheaderid=a.giheaderid
				join soheader aaa on aaa.soheaderid=aa.soheaderid
                join addressbook f on f.addressbookid = aaa.addressbookid				where a.recordstatus=3 and aaa.companyid = ".$companyid." and aaa.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and year(aa.gidate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))
				-
				(select ifnull(sum(ifnull(a.qty,0)*ifnull(a.price,0)),0)
				from notagirpro a
				join notagir b on b.notagirid=a.notagirid
				join giretur c on c.gireturid=b.gireturid
				join giheader d on d.giheaderid=c.giheaderid
				join soheader e on e.soheaderid=d.soheaderid
                join employee f on f.employeeid=e.employeeid
				where b.recordstatus=3 and e.companyid = ".$companyid." and e.addressbookid=z.addressbookid and f.fullname like '%".$customer."%' and year(c.gireturdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'))) as penj_total, 
                
                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($enddate))."') and c1.addressbookid = z.addressbookid ) as byr_total
                
                
				from addressbook z
				where z.recordstatus=1 and z.iscustomer=1 and z.fullname is not null order by fullname asc) zz 
                where penj_jan<>0 or byr_jan<>0 or penj_feb<>0 or byr_feb<>0 or penj_mar<>0 or byr_mar<>0 or penj_apr<>0 or byr_apr<>0 or penj_mei<>0 or byr_mei<>0 or penj_jun<>0 or byr_jun<>0 or penj_jul<>0 or byr_jul<>0 or penj_agus<>0 or byr_agus<>0 or penj_sept<>0 or byr_sept<>0 or penj_okt<>0 or byr_okt<>0 or penj_nov<>0 or byr_nov<>0 or penj_des<>0 or byr_des<>0 "; 
								
	  $dataReader=Yii::app()->db->createCommand($sql)->queryAll();
            $i=1;
        $line=6;
      $this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(1,2,'Per Tahun')							
							->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
            foreach($dataReader as $row){
						$this->phpExcel->setActiveSheetIndex(0)
							->setCellValueByColumnAndRow(0,$line,$i)							
							->setCellValueByColumnAndRow(1,$line,$row['fullname'])
                            ->setCellValueByColumnAndRow(2,$line,$row['penj_jan']/$per)
                          ->setCellValueByColumnAndRow(3,$line,$row['byr_jan']/$per)
                          ->setCellValueByColumnAndRow(4,$line,$row['penj_feb']/$per)
                          ->setCellValueByColumnAndRow(5,$line,$row['byr_feb']/$per)
                          ->setCellValueByColumnAndRow(6,$line,$row['penj_mar']/$per)
                          ->setCellValueByColumnAndRow(7,$line,$row['byr_mar']/$per)
                          ->setCellValueByColumnAndRow(8,$line,$row['penj_apr']/$per)
                          ->setCellValueByColumnAndRow(9,$line,$row['byr_apr']/$per)
                          ->setCellValueByColumnAndRow(10,$line,$row['penj_mei']/$per)
                          ->setCellValueByColumnAndRow(11,$line,$row['byr_mei']/$per)
                          ->setCellValueByColumnAndRow(12,$line,$row['penj_jun']/$per)
                           ->setCellValueByColumnAndRow(13,$line,$row['byr_jun']/$per)
                          ->setCellValueByColumnAndRow(14,$line,$row['penj_jul']/$per)
                           ->setCellValueByColumnAndRow(15,$line,$row['byr_jul']/$per)
                          ->setCellValueByColumnAndRow(16,$line,$row['penj_agus']/$per)
                           ->setCellValueByColumnAndRow(17,$line,$row['byr_agus']/$per)
                          ->setCellValueByColumnAndRow(18,$line,$row['penj_sept']/$per)
                           ->setCellValueByColumnAndRow(19,$line,$row['byr_sept']/$per)
                          ->setCellValueByColumnAndRow(20,$line,$row['penj_okt']/$per)
                           ->setCellValueByColumnAndRow(21,$line,$row['byr_okt']/$per)
                          ->setCellValueByColumnAndRow(22,$line,$row['penj_nov']/$per)
                           ->setCellValueByColumnAndRow(23,$line,$row['byr_nov']/$per)
                          ->setCellValueByColumnAndRow(24,$line,$row['penj_des']/$per)
                           ->setCellValueByColumnAndRow(25,$line,$row['byr_des']/$per)
                          ->setCellValueByColumnAndRow(26,$line,$row['penj_total']/$per)
                           ->setCellValueByColumnAndRow(27,$line,$row['byr_total']/$per);
							$line++; $i++;
						}
		$this->getFooterXLS($this->phpExcel);
	}
  //21
	public function RekapPiutangVSPelunasanPerBulanPerCustomerXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekappiutangvspelunasanperbulanpercustomer';
		parent::actionDownxls();
		$sql = "select * from
				(select z.fullname,
				(select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-01-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-01-01'))) zz where zz.addressbookid = z.addressbookid) as piu_jan, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=1 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_jan,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-02-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-02-01'))) zz where zz.addressbookid = z.addressbookid) as piu_feb, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=2 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_feb,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-03-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-03-01'))) zz where zz.addressbookid = z.addressbookid) as piu_mar, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=3 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_mar,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-04-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-04-01'))) zz where zz.addressbookid = z.addressbookid) as piu_apr, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=4 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_apr,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-05-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-05-01'))) zz where zz.addressbookid = z.addressbookid) as piu_mei, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=5 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_mei,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-06-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-06-01'))) zz where zz.addressbookid = z.addressbookid) as piu_jun, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=6 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_jun,
               
               
               (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-07-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-07-01'))) zz where zz.addressbookid = z.addressbookid) as piu_jul, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=7 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_jul,
                
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-08-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-08-01'))) zz where zz.addressbookid = z.addressbookid) as piu_agus, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=8 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_agus,
                
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-09-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-09-01'))) zz where zz.addressbookid = z.addressbookid) as piu_sept, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=9 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_sept,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-10-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-10-01'))) zz where zz.addressbookid = z.addressbookid) as piu_okt, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=10 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_okt,
                
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-11-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-11-01'))) zz where zz.addressbookid = z.addressbookid) as piu_nov, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=11 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_nov,
                
                (select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-12-01'))),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' and a.invoicedate <= LAST_DAY(CONCAT(YEAR('".date(Yii::app()->params['datetodb'], strtotime($startdate))."'),'-12-01'))) zz where zz.addressbookid = z.addressbookid) as piu_des, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and month(g1.docdate)=12 and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_des,
                
                
				(select ifnull(sum(amount),0) from (select c.addressbookid,a.amount - ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
                from cutarinv f
                join cutar g on g.cutarid=f.cutarid
                where g.recordstatus=3 and f.invoiceid=a.invoiceid),0) as amount
                from invoice a
                inner join giheader b on b.giheaderid = a.giheaderid
                inner join soheader c on c.soheaderid = b.soheaderid
                inner join employee ff on ff.employeeid = c.employeeid
                WHERE a.recordstatus=3 and c.companyid= ".$companyid." and ff.fullname like '%".$customer."%' 
               ) zz where zz.addressbookid = z.addressbookid) as piu_total, 

                (select ifnull(sum((ifnull(f1.cashamount,0)+ifnull(f1.bankamount,0)+ifnull(f1.discamount,0)+ifnull(f1.returnamount,0)+ifnull(f1.obamount,0))*ifnull(f1.currencyrate,0)),0)
                from cutarinv f1
                join invoice a1 on a1.invoiceid = f1.invoiceid
                join cutar g1 on g1.cutarid=f1.cutarid
                join giheader b1 on b1.giheaderid = a1.giheaderid
                join soheader c1 on c1.soheaderid = b1.soheaderid
                join addressbook d1 on d1.addressbookid = c1.addressbookid
                where g1.recordstatus=3  AND c1.companyid= ".$companyid." and d1.fullname like '%".$customer."%' and year(g1.docdate)=year('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') and c1.addressbookid = z.addressbookid ) as byr_total
                
					 
                
				from addressbook z
				where z.recordstatus=1 and z.iscustomer=1 and z.fullname is not null order by fullname asc) zz 
                
                where piu_jan<>0 or byr_jan<>0 or piu_feb<>0 or byr_feb<>0 or piu_mar<>0 or byr_mar<>0 or piu_apr<>0 or byr_apr<>0 or piu_mei<>0 or byr_mei<>0 or piu_jun<>0 or byr_jun<>0 or piu_jul<>0 or byr_jul<>0 or piu_agus<>0 or byr_agus<>0 or piu_sept<>0 or byr_sept<>0 or piu_okt<>0 or byr_okt<>0 or piu_nov<>0 or byr_nov<>0 or piu_des<>0 or byr_des<>0 "; 
								
            $dataReader=Yii::app()->db->createCommand($sql)->queryAll();
            
            /*
            $this->phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1,2,'Rekap Piutang VS Pelunasan Per Bulan Per Customer')
                                                   ->setCellValueByColumnAndRow(1,3,'Per Tahun : '.date(Yii::app()->params['datetodb'], strtotime($startdate)))
                                                   ->setCellValueByColumnAndRow(0,4,'No')
                                                   ->setCellValueByColumnAndRow(1,4,'Customer')
                                                   ->mergeCells('C4:D4')
                                                   ->setCellValueByColumnAndRow(2,4,'Januari')
                                                   ->mergeCells('E4:F4')
                                                   ->setCellValueByColumnAndRow(4,4,'Februari')
                                                   ->mergeCells('G4:H4')
                                                   ->setCellValueByColumnAndRow(6,4,'Maret')
                                                   ->mergeCells('I4:J4')
                                                   ->setCellValueByColumnAndRow(8,4,'April')
                                                   ->mergeCells('K4:L4')
                                                   ->setCellValueByColumnAndRow(10,4,'Mei')
                                                   ->mergeCells('M4:N4')
                                                   ->setCellValueByColumnAndRow(12,4,'Juni')
                                                   ->mergeCells('O4:P4')
                                                   ->setCellValueByColumnAndRow(14,4,'Juli')
                                                   ->mergeCells('Q4:R4')
                                                   ->setCellValueByColumnAndRow(16,4,'Agustus')
                                                   ->mergeCells('S4:T4')
                                                   ->setCellValueByColumnAndRow(18,4,'September')
                                                   ->mergeCells('U4:V4')
                                                   ->setCellValueByColumnAndRow(20,4,'Oktober')
                                                   ->mergeCells('W4:X4')
                                                   ->setCellValueByColumnAndRow(22,4,'November')
                                                   ->mergeCells('Y4:Z4')
                                                   ->setCellValueByColumnAndRow(24,4,'Desember')
                                                   ->mergeCells('AA4:AB4')
                                                   ->setCellValueByColumnAndRow(26,4,'Total');
        
            $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0,5,'')
                                                   ->setCellValueByColumnAndRow(1,5,'')
                                                   ->setCellValueByColumnAndRow(2,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(3,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(4,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(5,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(6,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(7,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(8,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(9,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(10,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(11,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(12,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(13,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(14,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(15,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(16,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(17,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(18,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(19,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(20,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(21,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(22,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(23,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(24,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(25,5,'Pelunasan')
                                                   ->setCellValueByColumnAndRow(26,5,'Piutang')
                                                   ->setCellValueByColumnAndRow(27,5,'Pelunasan');
            */
            $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2,2,'')
                                                   ->setCellValueByColumnAndRow(3,2,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)));
            $line=6;
		    $i=1;
						foreach($dataReader as $row)
						{
							$this->phpExcel->getActiveSheet()->getStyle(1,$line)->getFont()->setBold(true);
							$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,$i)
									->setCellValueByColumnAndRow(1,$line,$row['fullname'])
									->setCellValueByColumnAndRow(2,$line,$row['piu_jan']/$per)							
									->setCellValueByColumnAndRow(3,$line,$row['byr_jan']/$per)							
									->setCellValueByColumnAndRow(4,$line,$row['piu_feb']/$per)							
									->setCellValueByColumnAndRow(5,$line,$row['byr_feb']/$per)							
									->setCellValueByColumnAndRow(6,$line,$row['piu_mar']/$per)							
									->setCellValueByColumnAndRow(7,$line,$row['byr_mar']/$per)							
                  ->setCellValueByColumnAndRow(8,$line,$row['piu_apr']/$per)							
									->setCellValueByColumnAndRow(9,$line,$row['byr_apr']/$per)							
									->setCellValueByColumnAndRow(10,$line,$row['piu_mei']/$per)							
									->setCellValueByColumnAndRow(11,$line,$row['byr_mei']/$per)							
									->setCellValueByColumnAndRow(12,$line,$row['piu_jun']/$per)							
									->setCellValueByColumnAndRow(13,$line,$row['byr_jun']/$per)							
									->setCellValueByColumnAndRow(14,$line,$row['piu_jul']/$per)							
									->setCellValueByColumnAndRow(15,$line,$row['byr_jul']/$per)							
									->setCellValueByColumnAndRow(16,$line,$row['piu_agus']/$per)							
									->setCellValueByColumnAndRow(17,$line,$row['byr_agus']/$per)							
									->setCellValueByColumnAndRow(18,$line,$row['piu_sept']/$per)							
									->setCellValueByColumnAndRow(19,$line,$row['byr_sept']/$per)							
									->setCellValueByColumnAndRow(20,$line,$row['piu_okt']/$per)							
									->setCellValueByColumnAndRow(21,$line,$row['byr_okt']/$per)							
									->setCellValueByColumnAndRow(22,$line,$row['piu_nov']/$per)							
									->setCellValueByColumnAndRow(23,$line,$row['byr_nov']/$per)							
									->setCellValueByColumnAndRow(24,$line,$row['piu_des']/$per)							
									->setCellValueByColumnAndRow(25,$line,$row['byr_des']/$per)							
									->setCellValueByColumnAndRow(26,$line,$row['piu_total']/$per)							
									->setCellValueByColumnAndRow(27,$line,$row['byr_total']/$per);
				            $i++;
							$line++;
                        }
		$this->getFooterXLS($this->phpExcel);
	}	
	//22
	public function RincianPelunasanPiutangPerCustomerXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianpelunasanpiutangpercustomer';
		parent::actionDownxls();
		$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
		$sql = "select distinct g.addressbookid,g.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						join salesarea j on j.salesareaid=g.salesareaid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and j.areaname like '%".$salesarea."%' and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		if ($product !== '') 
		{
			$sql = $sql . " and	d.giheaderid in (select distinct a0.giheaderid from gidetail a0 join product a1 on a1.productid=a0.productid where a1.productname like '%".$product."%') ";
		}
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
                
		foreach($dataReader as $row)
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;	
		$i=0;
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'CUSTOMER ')
					->setCellValueByColumnAndRow(1,$line,': '.$row['fullname']);							
			$line++;
				
			$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,'No')
					->setCellValueByColumnAndRow(1,$line,'No Invoice')
					->setCellValueByColumnAndRow(2,$line,'Tanggal')					
					->setCellValueByColumnAndRow(3,$line,'Tgl Byr')
					->setCellValueByColumnAndRow(4,$line,'Customer')
					->setCellValueByColumnAndRow(5,$line,'Hari')
					->setCellValueByColumnAndRow(6,$line,'Nil. Faktur')
					->setCellValueByColumnAndRow(7,$line,'Disc/Ret')
					->setCellValueByColumnAndRow(8,$line,'Jmlh Bayar')
					->setCellValueByColumnAndRow(9,$line,'0-30 Hari')
					->setCellValueByColumnAndRow(10,$line,'31-45 Hari')
					->setCellValueByColumnAndRow(11,$line,'46-60 Hari')
					->setCellValueByColumnAndRow(12,$line,'61-63 Hari')
					->setCellValueByColumnAndRow(13,$line,'64-70 Hari')
					->setCellValueByColumnAndRow(14,$line,'71-90 Hari')
					->setCellValueByColumnAndRow(15,$line,'>90 Hari');
			$line++;
			
			if ($product !== '') 
			{
				$whereproduct = " and d.giheaderid in (select distinct a0.giheaderid from gidetail a0 join product a1 on a1.productid=a0.productid where a1.productname like '%".$product."%') ";
			}
			else
			{
				$whereproduct = "";
			}
			$sql1 = "select *,
								case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
								case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
								case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
								case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
								case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
								case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
								case when umur > 90 then nilaibayar else 0 end as sd91								
						from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
						a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						join salesarea j on j.salesareaid=g.salesareaid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3
						and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
						and j.areaname like '%".$salesarea."%' and e.companyid = ".$companyid." and g.addressbookid = ".$row['addressbookid']." {$whereproduct}) z
						";
			if ($umurpiutang !== '') 
			{
					$sql1 = $sql1 . " where  umur > ".$umurpiutang." ";
			}
			$sql1 = $sql1 . " order by docdate,fullname ";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			$i=0;$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
			
			foreach($dataReader1 as $row1)
				{
					$i+=1;
						$this->phpExcel->setActiveSheetIndex(0)
								->setCellValueByColumnAndRow(0,$line,$i)
								->setCellValueByColumnAndRow(1,$line,$row1['invoiceno'])
								->setCellValueByColumnAndRow(2,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])))
								->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row1['docdate'])))
								->setCellValueByColumnAndRow(4,$line,$row1['fullname'])
								->setCellValueByColumnAndRow(5,$line,$row1['umur'])
								->setCellValueByColumnAndRow(6,$line,($row1['amount']/$per))							
								->setCellValueByColumnAndRow(7,$line,($row1['disc']/$per))					
								->setCellValueByColumnAndRow(8,$line,($row1['nilaibayar']/$per))
								->setCellValueByColumnAndRow(9,$line,($row1['0sd30']/$per))
								->setCellValueByColumnAndRow(10,$line,($row1['31sd45']/$per))
								->setCellValueByColumnAndRow(11,$line,($row1['46sd60']/$per))
								->setCellValueByColumnAndRow(12,$line,($row1['61sd63']/$per))
								->setCellValueByColumnAndRow(13,$line,($row1['64sd70']/$per))
								->setCellValueByColumnAndRow(14,$line,($row1['71sd90']/$per))
								->setCellValueByColumnAndRow(15,$line,($row1['sd91']/$per));
						$line++;
						
					$totaldisc += ($row1['disc']/$per);
					$totalnilaibayar += ($row1['nilaibayar']/$per);
					$total0sd30 += ($row1['0sd30']/$per);
					$total31sd45 += ($row1['31sd45']/$per);
					$total46sd60 += ($row1['46sd60']/$per);
					$total61sd63 += ($row1['61sd63']/$per);
					$total64sd70 += ($row1['64sd70']/$per);
					$total71sd90 += ($row1['71sd90']/$per);
					$totalsd91 += ($row1['sd91']/$per);				
						
				}
				$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'TOTAL CUSTOMER '.$row['fullname'])				
					->setCellValueByColumnAndRow(7,$line,($totaldisc))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar))
					->setCellValueByColumnAndRow(9,$line,($total0sd30))
					->setCellValueByColumnAndRow(10,$line,($total31sd45))
					->setCellValueByColumnAndRow(11,$line,($total46sd60))
					->setCellValueByColumnAndRow(12,$line,($total61sd63))
					->setCellValueByColumnAndRow(13,$line,($total64sd70))
					->setCellValueByColumnAndRow(14,$line,($total71sd90))
					->setCellValueByColumnAndRow(15,$line,($totalsd91));
				$line+=2;
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(7,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(9,$line,($total0sd301))
					->setCellValueByColumnAndRow(10,$line,($total31sd451))
					->setCellValueByColumnAndRow(11,$line,($total46sd601))
					->setCellValueByColumnAndRow(12,$line,($total61sd631))
					->setCellValueByColumnAndRow(13,$line,($total64sd701))
					->setCellValueByColumnAndRow(14,$line,($total71sd901))
					->setCellValueByColumnAndRow(15,$line,($totalsd911));
				$line++;		
		
		$this->getFooterXLS($this->phpExcel);
	}
	//23
	public function RekapPelunasanPiutangPerCustomerXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekappelunasanpiutangpercustomer';
		parent::actionDownxls();
		$i=0;$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
		$sql = "select distinct g.addressbookid,g.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						left join salesarea j on j.salesareaid=g.salesareaid
						left join gidetail k on k.giheaderid=d.giheaderid
						left join product l on l.productid=k.productid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and ifnull(j.areaname,'') like '%".$salesarea."%' and ifnull(l.productname,'') like '%".$product."%' and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($dataReader as $row)
		
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;		
		$i=0;
		$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'Nama Customer')
						->setCellValueByColumnAndRow(2,$line,'Disc/Ret')					
						->setCellValueByColumnAndRow(3,$line,'Jmlh Bayar')
						->setCellValueByColumnAndRow(4,$line,'0-30 Hari')
						->setCellValueByColumnAndRow(5,$line,'31-45 Hari')
						->setCellValueByColumnAndRow(6,$line,'46-60 Hari')
						->setCellValueByColumnAndRow(7,$line,'61-63 Hari')
						->setCellValueByColumnAndRow(8,$line,'64-70 Hari')
						->setCellValueByColumnAndRow(9,$line,'71-90 Hari')
						->setCellValueByColumnAndRow(10,$line,'>90 Hari');
		$line++;
		foreach($dataReader as $row)	
		{
			$sql1 = "select *,
								case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
								case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
								case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
								case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
								case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
								case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
								case when umur > 90 then nilaibayar else 0 end as sd91								
						from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
						a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						left join salesarea j on j.salesareaid=g.salesareaid
						left join gidetail k on k.giheaderid=d.giheaderid
						left join product l on l.productid=k.productid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
						and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
						and ifnull(l.productname,'') like '%".$product."%' 
						and ifnull(j.areaname,'') like '%".$salesarea."%' and e.companyid = ".$companyid." and g.addressbookid = ".$row['addressbookid'].") z
						order by docdate,fullname
						";
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
								
			$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
			
			foreach($dataReader1 as $row1)
			{
				$totaldisc += ($row1['disc']/$per);
				$totalnilaibayar += ($row1['nilaibayar']/$per);
				$total0sd30 += ($row1['0sd30']/$per);
				$total31sd45 += ($row1['31sd45']/$per);
				$total46sd60 += ($row1['46sd60']/$per);
				$total61sd63 += ($row1['61sd63']/$per);
				$total64sd70 += ($row1['64sd70']/$per);
				$total71sd90 += ($row1['71sd90']/$per);
				$totalsd91 += ($row1['sd91']/$per);
			}
			$i+=1;
			$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,$i)
					->setCellValueByColumnAndRow(1,$line,$row['fullname'])										
					->setCellValueByColumnAndRow(2,$line,($totaldisc))					
					->setCellValueByColumnAndRow(3,$line,($totalnilaibayar))
					->setCellValueByColumnAndRow(4,$line,($total0sd30))
					->setCellValueByColumnAndRow(5,$line,($total31sd45))
					->setCellValueByColumnAndRow(6,$line,($total46sd60))
					->setCellValueByColumnAndRow(7,$line,($total61sd63))
					->setCellValueByColumnAndRow(8,$line,($total64sd70))
					->setCellValueByColumnAndRow(9,$line,($total71sd90))
					->setCellValueByColumnAndRow(10,$line,($totalsd91));
			$line++;	
			$totaldisc1 += $totaldisc;
			$totalnilaibayar1 += $totalnilaibayar;
			$total0sd301 += $total0sd30;
			$total31sd451 += $total31sd45;
			$total46sd601 += $total46sd60;
			$total61sd631 += $total61sd63;
			$total64sd701 += $total64sd70;
			$total71sd901 += $total71sd90;
			$totalsd911 += $totalsd91;
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(1,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(2,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(3,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(4,$line,($total0sd301))
					->setCellValueByColumnAndRow(5,$line,($total31sd451))
					->setCellValueByColumnAndRow(6,$line,($total46sd601))
					->setCellValueByColumnAndRow(7,$line,($total61sd631))
					->setCellValueByColumnAndRow(8,$line,($total64sd701))
					->setCellValueByColumnAndRow(9,$line,($total71sd901))
					->setCellValueByColumnAndRow(10,$line,($totalsd911));
				$line++;
		
		$this->getFooterXLS($this->phpExcel);
	}
	//24
	public function RincianPelunasanPiutangPerCustomerPerJenisBarangXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianpelunasanpiutangpercustomerperjenisbarang';
		parent::actionDownxls();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0;
		$sql = "select distinct g.addressbookid,g.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and j.areaname like '%".$salesarea."%' and l.productname like '%".$product."%' and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
                
		foreach($dataReader as $row)		
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;		
		$i=0;
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'CUSTOMER ')
					->setCellValueByColumnAndRow(1,$line,': '.$row['fullname']);							
			$line++;			
			
			$sql1 = "select distinct i.slocid,i.description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
						join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join gidetail h on h.giheaderid=d.giheaderid
							join sloc i on i.slocid=h.slocid
							join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
							b.recordstatus=3 and g.addressbookid = ".$row['addressbookid']." and j.areaname like '%".$salesarea."%' and
							l.productname like '%".$product."%' and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();	
			$totaldisc1 = 0;$totalnilaibayar1 = 0;$total0sd301 = 0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
			foreach($dataReader1 as $row1)
			{
				$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'JENIS BARANG ')
					->setCellValueByColumnAndRow(1,$line,': '.$row1['description']);							
				$line++;
				
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select h.slocid from gidetail h where h.giheaderid=d.giheaderid Limit 1) as slocid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
						join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and l.productname like '%".$product."%' 
							and j.areaname like '%".$salesarea."%' and e.companyid = ".$companyid." and g.addressbookid = ".$row['addressbookid']." ) z
							where z.slocid = ".$row1['slocid']."
							order by docdate,fullname
							";
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
				$i=0;$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				
				$this->phpExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$line,'No')
					->setCellValueByColumnAndRow(1,$line,'No Invoice')
					->setCellValueByColumnAndRow(2,$line,'Tanggal')					
					->setCellValueByColumnAndRow(3,$line,'Tgl Byr')
					->setCellValueByColumnAndRow(4,$line,'Customer')
					->setCellValueByColumnAndRow(5,$line,'Hari')
					->setCellValueByColumnAndRow(6,$line,'Nil. Faktur')
					->setCellValueByColumnAndRow(7,$line,'Disc/Ret')
					->setCellValueByColumnAndRow(8,$line,'Jmlh Bayar')
					->setCellValueByColumnAndRow(9,$line,'0-30 Hari')
					->setCellValueByColumnAndRow(10,$line,'31-45 Hari')
					->setCellValueByColumnAndRow(11,$line,'46-60 Hari')
					->setCellValueByColumnAndRow(12,$line,'61-63 Hari')
					->setCellValueByColumnAndRow(13,$line,'64-70 Hari')
					->setCellValueByColumnAndRow(14,$line,'71-90 Hari')
					->setCellValueByColumnAndRow(15,$line,'>90 Hari');
				$line++;
				
				foreach($dataReader2 as $row2)
				{
					$i+=1;
						$this->phpExcel->setActiveSheetIndex(0)
								->setCellValueByColumnAndRow(0,$line,$i)
								->setCellValueByColumnAndRow(1,$line,$row2['invoiceno'])
								->setCellValueByColumnAndRow(2,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])))
								->setCellValueByColumnAndRow(3,$line,date(Yii::app()->params['dateviewfromdb'], strtotime($row2['docdate'])))
								->setCellValueByColumnAndRow(4,$line,$row2['fullname'])
								->setCellValueByColumnAndRow(5,$line,$row2['umur'])
								->setCellValueByColumnAndRow(6,$line,($row2['amount']/$per))							
								->setCellValueByColumnAndRow(7,$line,($row2['disc']/$per))					
								->setCellValueByColumnAndRow(8,$line,($row2['nilaibayar']/$per))
								->setCellValueByColumnAndRow(9,$line,($row2['0sd30']/$per))
								->setCellValueByColumnAndRow(10,$line,($row2['31sd45']/$per))
								->setCellValueByColumnAndRow(11,$line,($row2['46sd60']/$per))
								->setCellValueByColumnAndRow(12,$line,($row2['61sd63']/$per))
								->setCellValueByColumnAndRow(13,$line,($row2['64sd70']/$per))
								->setCellValueByColumnAndRow(14,$line,($row2['71sd90']/$per))
								->setCellValueByColumnAndRow(15,$line,($row2['sd91']/$per));
						$line++;
						
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);
				}
				$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'TOTAL GUDANG '.$row1['description'])				
					->setCellValueByColumnAndRow(7,$line,($totaldisc))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar))
					->setCellValueByColumnAndRow(9,$line,($total0sd30))
					->setCellValueByColumnAndRow(10,$line,($total31sd45))
					->setCellValueByColumnAndRow(11,$line,($total46sd60))
					->setCellValueByColumnAndRow(12,$line,($total61sd63))
					->setCellValueByColumnAndRow(13,$line,($total64sd70))
					->setCellValueByColumnAndRow(14,$line,($total71sd90))
					->setCellValueByColumnAndRow(15,$line,($totalsd91));
				$line+=2;
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;
			
			}
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'TOTAL CUSTOMER '.$row['fullname'])				
					->setCellValueByColumnAndRow(7,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(9,$line,($total0sd301))
					->setCellValueByColumnAndRow(10,$line,($total31sd451))
					->setCellValueByColumnAndRow(11,$line,($total46sd601))
					->setCellValueByColumnAndRow(12,$line,($total61sd631))
					->setCellValueByColumnAndRow(13,$line,($total64sd701))
					->setCellValueByColumnAndRow(14,$line,($total71sd901))
					->setCellValueByColumnAndRow(15,$line,($totalsd911));
				$line+=2;	
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(4,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(7,$line,($totaldisc2))										
					->setCellValueByColumnAndRow(8,$line,($totalnilaibayar2))
					->setCellValueByColumnAndRow(9,$line,($total0sd302))
					->setCellValueByColumnAndRow(10,$line,($total31sd452))
					->setCellValueByColumnAndRow(11,$line,($total46sd602))
					->setCellValueByColumnAndRow(12,$line,($total61sd632))
					->setCellValueByColumnAndRow(13,$line,($total64sd702))
					->setCellValueByColumnAndRow(14,$line,($total71sd902))
					->setCellValueByColumnAndRow(15,$line,($totalsd912));
		$line+=2;
		$this->getFooterXLS($this->phpExcel);
	}
	//25
	public function RekapPelunasanPiutangPerCustomerPerJenisBarangXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekappelunasanpiutangpercustomerperjenisbarang';
		parent::actionDownxls();
		$totaldisc2 = 0;$totalnilaibayar2 = 0;$total0sd302 = 0;$total31sd452 = 0;$total46sd602 = 0;$total61sd632 = 0;$total64sd702 = 0;$total71sd902 = 0;$totalsd912 = 0;
		$sql = "select distinct g.addressbookid,g.fullname,e.companyid
						from cutarinv a
						join cutar b on b.cutarid=a.cutarid
						join invoice c on c.invoiceid=a.invoiceid
						join giheader d on d.giheaderid=c.giheaderid
						join soheader e on e.soheaderid=d.soheaderid
						join ttnt h on h.ttntid=b.ttntid
						join employee f on f.employeeid=h.employeeid
						join addressbook g on g.addressbookid=e.addressbookid
						join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
						where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
						b.recordstatus=3 and j.areaname like '%".$salesarea."%' and l.productname like '%".$product."%' and
						b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		foreach($dataReader as $row)
		
		$this->phpExcel->setActiveSheetIndex(0)				
					->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
					->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=4;		
		
                
		foreach($dataReader as $row)
		{
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(0,$line,'CUSTOMER ')
					->setCellValueByColumnAndRow(1,$line,': '.$row['fullname']);							
			$line++;
			
			$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,'No')
						->setCellValueByColumnAndRow(1,$line,'Nama Customer')
						->setCellValueByColumnAndRow(2,$line,'Disc/Ret')					
						->setCellValueByColumnAndRow(3,$line,'Jmlh Bayar')
						->setCellValueByColumnAndRow(4,$line,'0-30 Hari')
						->setCellValueByColumnAndRow(5,$line,'31-45 Hari')
						->setCellValueByColumnAndRow(6,$line,'46-60 Hari')
						->setCellValueByColumnAndRow(7,$line,'61-63 Hari')
						->setCellValueByColumnAndRow(8,$line,'64-70 Hari')
						->setCellValueByColumnAndRow(9,$line,'71-90 Hari')
						->setCellValueByColumnAndRow(10,$line,'>90 Hari');
			$line++;
			$i=0;$totaldisc1=0;$totalnilaibayar1=0;$total0sd301=0;$total31sd451 = 0;$total46sd601 = 0;$total61sd631 = 0;$total64sd701 = 0;$total71sd901 = 0;$totalsd911 = 0;
			$sql1 = "select distinct i.slocid,i.description
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
						join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join gidetail h on h.giheaderid=d.giheaderid
							join sloc i on i.slocid=h.slocid
							join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.cutarno is not null and e.companyid = ".$companyid." and 
							b.recordstatus=3 and j.areaname like '%".$salesarea."%' and g.addressbookid = ".$row['addressbookid']." and
							l.productname like '%".$product."%' and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ";
			
			$dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();
			
			foreach($dataReader1 as $row1)
			{
				
				$sql2 = "select *,
									case when umur >= 0 and umur <= 30 then nilaibayar else 0 end as 0sd30,
									case when umur > 30 and umur <= 45 then nilaibayar else 0 end as 31sd45,
									case when umur > 45 and umur <= 60 then nilaibayar else 0 end as 46sd60,
									case when umur > 60 and umur <= 63 then nilaibayar else 0 end as 61sd63,
									case when umur > 63 and umur <= 70 then nilaibayar else 0 end as 64sd70,
									case when umur > 70 and umur <= 90 then nilaibayar else 0 end as 71sd90,
									case when umur > 90 then nilaibayar else 0 end as sd91								
							from (select distinct c.invoiceno,c.invoicedate,b.docdate,g.fullname,datediff(b.docdate,c.invoicedate) as umur,c.amount,
							a.discamount+a.returnamount as disc,a.cashamount+a.bankamount+a.obamount as nilaibayar,
							(select h.slocid from gidetail h where h.giheaderid=d.giheaderid Limit 1) as slocid
							from cutarinv a
							join cutar b on b.cutarid=a.cutarid
							join invoice c on c.invoiceid=a.invoiceid
							join giheader d on d.giheaderid=c.giheaderid
							join soheader e on e.soheaderid=d.soheaderid
						join ttnt m on m.ttntid=b.ttntid
							join employee f on f.employeeid=m.employeeid
							join addressbook g on g.addressbookid=e.addressbookid
							join salesarea j on j.salesareaid=g.salesareaid
						join gidetail k on k.giheaderid=d.giheaderid
						join product l on l.productid=k.productid
							where g.fullname like '%".$customer."%' and f.fullname like '%".$sales."%' and b.recordstatus=3 
							and b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
							and l.productname like '%".$product."%' 
							and j.areaname like '%".$salesarea."%' and e.companyid = ".$companyid." and g.addressbookid = ".$row['addressbookid']." ) z
							where z.slocid = ".$row1['slocid']."
							order by docdate,fullname
							";
				$dataReader2=Yii::app()->db->createCommand($sql2)->queryAll();
				
				$totaldisc=0;$totalnilaibayar=0;$total0sd30=0;$total31sd45=0;$total46sd60=0;$total61sd63=0;$total64sd70=0;$total71sd90=0;$totalsd91=0;
				foreach($dataReader2 as $row2)
				{
					$totaldisc += ($row2['disc']/$per);
					$totalnilaibayar += ($row2['nilaibayar']/$per);
					$total0sd30 += ($row2['0sd30']/$per);
					$total31sd45 += ($row2['31sd45']/$per);
					$total46sd60 += ($row2['46sd60']/$per);
					$total61sd63 += ($row2['61sd63']/$per);
					$total64sd70 += ($row2['64sd70']/$per);
					$total71sd90 += ($row2['71sd90']/$per);
					$totalsd91 += ($row2['sd91']/$per);
				}
				$i+=1;
				$this->phpExcel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow(0,$line,$i)
						->setCellValueByColumnAndRow(1,$line,$row1['description'])										
						->setCellValueByColumnAndRow(2,$line,($totaldisc))					
						->setCellValueByColumnAndRow(3,$line,($totalnilaibayar))
						->setCellValueByColumnAndRow(4,$line,($total0sd30))
						->setCellValueByColumnAndRow(5,$line,($total31sd45))
						->setCellValueByColumnAndRow(6,$line,($total46sd60))
						->setCellValueByColumnAndRow(7,$line,($total61sd63))
						->setCellValueByColumnAndRow(8,$line,($total64sd70))
						->setCellValueByColumnAndRow(9,$line,($total71sd90))
						->setCellValueByColumnAndRow(10,$line,($totalsd91));
				$line++;
				
				$totaldisc1 += $totaldisc;
				$totalnilaibayar1 += $totalnilaibayar;
				$total0sd301 += $total0sd30;
				$total31sd451 += $total31sd45;
				$total46sd601 += $total46sd60;
				$total61sd631 += $total61sd63;
				$total64sd701 += $total64sd70;
				$total71sd901 += $total71sd90;
				$totalsd911 += $totalsd91;			
				
			}
			$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(1,$line,'TOTAL CUSTOMER '.$row['fullname'])				
					->setCellValueByColumnAndRow(2,$line,($totaldisc1))										
					->setCellValueByColumnAndRow(3,$line,($totalnilaibayar1))
					->setCellValueByColumnAndRow(4,$line,($total0sd301))
					->setCellValueByColumnAndRow(5,$line,($total31sd451))
					->setCellValueByColumnAndRow(6,$line,($total46sd601))
					->setCellValueByColumnAndRow(7,$line,($total61sd631))
					->setCellValueByColumnAndRow(8,$line,($total64sd701))
					->setCellValueByColumnAndRow(9,$line,($total71sd901))
					->setCellValueByColumnAndRow(10,$line,($totalsd911));
			$line+=2;
			$totaldisc2 += $totaldisc1;
			$totalnilaibayar2 += $totalnilaibayar1;
			$total0sd302 += $total0sd301;
			$total31sd452 += $total31sd451;
			$total46sd602 += $total46sd601;
			$total61sd632 += $total61sd631;
			$total64sd702 += $total64sd701;
			$total71sd902 += $total71sd901;
			$totalsd912 += $totalsd911;
				
		}
		$this->phpExcel->setActiveSheetIndex(0)	
					->setCellValueByColumnAndRow(1,$line,'GRAND TOTAL ')				
					->setCellValueByColumnAndRow(2,$line,($totaldisc2))										
					->setCellValueByColumnAndRow(3,$line,($totalnilaibayar2))
					->setCellValueByColumnAndRow(4,$line,($total0sd302))
					->setCellValueByColumnAndRow(5,$line,($total31sd452))
					->setCellValueByColumnAndRow(6,$line,($total46sd602))
					->setCellValueByColumnAndRow(7,$line,($total61sd632))
					->setCellValueByColumnAndRow(8,$line,($total64sd702))
					->setCellValueByColumnAndRow(9,$line,($total71sd902))
					->setCellValueByColumnAndRow(10,$line,($totalsd912));
		$line++;
		
		$this->getFooterXLS($this->phpExcel);
	}
	//26
	
	//27
	
	//28
	
	//29
	public function RincianPelunasanPiutangFilterTanggalInvoiceXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianpelunasanpiutangfiltertanggalinovice';
		parent::actionDownxls();
		
		$i=0;
		$sub_nominal=0;$sub_paidamount=0;$sub_ob=0;$sub_disc=0;$sub_return=0;$sub_paidvalue=0;
		$sql = "select c.invoicedate, c.invoiceno, c.amount, f.fullname as customername, b.docdate,b.cutarno,(a.cashamount+a.bankamount) as paidamount, a.obamount, a.discamount, a.returnamount,
                (cashamount+bankamount+discamount+returnamount+obamount) as paidvalue, g.fullname as salesname
                from cutarinv a
                join cutar b on a.cutarid = b.cutarid
                join invoice c on c.invoiceid = a.invoiceid
                join giheader d on d.giheaderid = c.giheaderid
                join soheader e on e.soheaderid = d.soheaderid
                join addressbook f on f.addressbookid = e.addressbookid
						join ttnt h on h.ttntid=b.ttntid
                join employee g on g.employeeid = h.employeeid
                where c.invoicedate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
                and c.recordstatus = 3 and b.recordstatus = 3 and e.companyid = {$companyid} and g.fullname like '%{$sales}%'
                order by invoicedate asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		$this->phpExcel->setActiveSheetIndex(0)				
			->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
			->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=5;
		foreach($dataReader as $row)
		{
			$i+=1;
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,$i)
				->setCellValueByColumnAndRow(1,$line,date(Yii::app()->params['dateviewfromdb'],strtotime($row['invoicedate'])))
				->setCellValueByColumnAndRow(2,$line,$row['invoiceno'])
				->setCellValueByColumnAndRow(3,$line,($row['amount']))
				->setCellValueByColumnAndRow(4,$line,$row['customername'])
				->setCellValueByColumnAndRow(5,$line,date(Yii::app()->params['dateviewfromdb'],strtotime($row['docdate'])))
				->setCellValueByColumnAndRow(6,$line,$row['cutarno'])
				->setCellValueByColumnAndRow(7,$line,($row['paidamount']))
				->setCellValueByColumnAndRow(8,$line,($row['obamount']))
				->setCellValueByColumnAndRow(9,$line,($row['discamount']))
				->setCellValueByColumnAndRow(10,$line,($row['returnamount']))
				->setCellValueByColumnAndRow(11,$line,($row['paidvalue']))
				->setCellValueByColumnAndRow(12,$line,$row['salesname']);
			$line++;
					
			$sub_nominal += $row['amount'];
			$sub_paidamount += $row['paidamount'];
			$sub_ob += $row['obamount'];
			$sub_disc += $row['discamount'];
			$sub_return += $row['returnamount'];
			$sub_paidvalue += $row['paidvalue'];
					
		}
		$line++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,$line,'')
			->setCellValueByColumnAndRow(1,$line,'GRAND TOTAL')
			->setCellValueByColumnAndRow(2,$line,'')
			->setCellValueByColumnAndRow(3,$line,($sub_nominal))
			->setCellValueByColumnAndRow(4,$line,'')
			->setCellValueByColumnAndRow(5,$line,'')
			->setCellValueByColumnAndRow(6,$line,'')
			->setCellValueByColumnAndRow(7,$line,($sub_paidamount))
			->setCellValueByColumnAndRow(8,$line,($sub_ob))
			->setCellValueByColumnAndRow(9,$line,($sub_disc))
			->setCellValueByColumnAndRow(10,$line,($sub_return))
			->setCellValueByColumnAndRow(11,$line,($sub_paidvalue))
			->setCellValueByColumnAndRow(12,$line,'');
		$this->getFooterXLS($this->phpExcel);
	}
	//30
	public function RincianPelunasanPiutangFilterTanggalPelunasanXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rincianpelunasanpiutangfiltertanggalpelunasan';
		parent::actionDownxls();
		
		$i=0;
		$sub_nominal=0;$sub_paidamount=0;$sub_ob=0;$sub_disc=0;$sub_return=0;$sub_paidvalue=0;
		$sql = "select c.invoicedate, c.invoiceno, c.amount, f.fullname as customername, b.docdate,b.cutarno,(a.cashamount+a.bankamount) as paidamount, a.obamount, a.discamount, a.returnamount,
                (cashamount+bankamount+discamount+returnamount+obamount) as paidvalue, g.fullname as salesname
                from cutarinv a
                join cutar b on a.cutarid = b.cutarid
                join invoice c on c.invoiceid = a.invoiceid
                join giheader d on d.giheaderid = c.giheaderid
                join soheader e on e.soheaderid = d.soheaderid
                join addressbook f on f.addressbookid = e.addressbookid
						join ttnt h on h.ttntid=b.ttntid
                join employee g on g.employeeid = h.employeeid
                where b.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
                and c.recordstatus = 3 and b.recordstatus = 3 and e.companyid = {$companyid} and g.fullname like '%{$sales}%'
                order by b.docdate asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		
		$this->phpExcel->setActiveSheetIndex(0)				
			->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
			->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
		$line=5;
		foreach($dataReader as $row)
		{
			$i+=1;
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$line,$i)
				->setCellValueByColumnAndRow(1,$line,date(Yii::app()->params['dateviewfromdb'],strtotime($row['invoicedate'])))
				->setCellValueByColumnAndRow(2,$line,$row['invoiceno'])
				->setCellValueByColumnAndRow(3,$line,($row['amount']))
				->setCellValueByColumnAndRow(4,$line,$row['customername'])
				->setCellValueByColumnAndRow(5,$line,date(Yii::app()->params['dateviewfromdb'],strtotime($row['docdate'])))
				->setCellValueByColumnAndRow(6,$line,$row['cutarno'])
				->setCellValueByColumnAndRow(7,$line,($row['paidamount']))
				->setCellValueByColumnAndRow(8,$line,($row['obamount']))
				->setCellValueByColumnAndRow(9,$line,($row['discamount']))
				->setCellValueByColumnAndRow(10,$line,($row['returnamount']))
				->setCellValueByColumnAndRow(11,$line,($row['paidvalue']))
				->setCellValueByColumnAndRow(12,$line,$row['salesname']);
			$line++;
					
			$sub_nominal += $row['amount'];
			$sub_paidamount += $row['paidamount'];
			$sub_ob += $row['obamount'];
			$sub_disc += $row['discamount'];
			$sub_return += $row['returnamount'];
			$sub_paidvalue += $row['paidvalue'];
					
		}
		$line++;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,$line,'')
			->setCellValueByColumnAndRow(1,$line,'GRAND TOTAL')
			->setCellValueByColumnAndRow(2,$line,'')
			->setCellValueByColumnAndRow(3,$line,($sub_nominal))
			->setCellValueByColumnAndRow(4,$line,'')
			->setCellValueByColumnAndRow(5,$line,'')
			->setCellValueByColumnAndRow(6,$line,'')
			->setCellValueByColumnAndRow(7,$line,($sub_paidamount))
			->setCellValueByColumnAndRow(8,$line,($sub_ob))
			->setCellValueByColumnAndRow(9,$line,($sub_disc))
			->setCellValueByColumnAndRow(10,$line,($sub_return))
			->setCellValueByColumnAndRow(11,$line,($sub_paidvalue))
			->setCellValueByColumnAndRow(12,$line,'');
		$this->getFooterXLS($this->phpExcel);
	}
	//31
	
	//32
	public function RekapKomisiTagihanPerSalesXLS_1($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
			$this->menuname='rekapkomisitagihansales';
	parent::actionDownxls();
			$datetime = new DateTime(date(Yii::app()->params['datetodb'], strtotime($enddate)));
			
			$month = date('m',strtotime($enddate));
			$year = date('Y',strtotime($enddate));

			$prev_month_ts =  strtotime($year.'-'.$month.'-01');
			$month1 = date('Y-m-d', $prev_month_ts);
			
			$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(1,2,$datetime->format('F').' '.$datetime->format('Y'))
		->setCellValueByColumnAndRow(7,2,'Per : '.$per);
			
			$maxstat2 = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appps')")->queryScalar();
		
			$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppt')")->queryScalar();
			$maxs=0;
			$maxscale = Yii::app()->db->createCommand("select b.min, b.max,gt30,gt15,gt7,gt0,x0,lt0,lt14
					from paymentscale a
					join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
					where a.companyid={$companyid} and a.perioddate  = '{$month1}' and a.recordstatus={$maxstat2}
					and b.min = (select max(c.min) from paymentscaledet c where c.paymentscaleid=a.paymentscaleid)")->queryRow();

			$sqlsales = "select distinct employeeid, fullname from (
																			select c.employeeid, c.fullname, b.addressbookid
																			from paymenttarget a
																			join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
																			join employee c on a.employeeid = c.employeeid
																			where a.recordstatus = {$maxstat} and month(a.perioddate) = month('".$enddate."') and year(a.perioddate) = year('".$enddate."')
																			and c.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")." and a.companyid = {$companyid}
																			union
																			select g.employeeid, g.fullname, e.addressbookid
																			from cutarinv a
																			join cutar b on b.cutarid = a.cutarid
																			join invoice c on c.invoiceid = a.invoiceid
																			join giheader d on d.giheaderid = c.giheaderid
																			join soheader e on e.soheaderid = d.soheaderid
																			join ttnt f on f.ttntid = b.ttntid
																			join employee g on g.employeeid = f.employeeid
																			where g.fullname ".(isset($sales)&&$sales!='' ? "like '%".$sales."%'" : "like '%%'")."
																			and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and b.companyid = {$companyid} group by addressbookid ) z group by addressbookid";
			//$res = Yii::app()->db->createCommand($sqlsales)->queryAll();
			$emp = Yii::app()->db->createCommand($sqlsales)->queryAll();
			$line = 4;
			foreach($emp as $sales)
			{
					$totaltarget = 0;
					$totalrealisasi = 0;
					$totalgt30 = 0; 
					$totalgt15 = 0;
					$totalgt7 = 0;
					$totalgt0 = 0;
					$totalx0 = 0;
					$totallt0 = 0;
					$totallt14 = 0;
					$komisigt30 = 0;
					$komisigt15 = 0;
					$komisigt7 = 0;
					$komisigt0 = 0;
					$komisix0 = 0;
					$komisilt0 = 0;
					$komisilt14 = 0;
					$totalkomisi = 0;
					$percent=0;
					$persentotal=0;
					$next=0;
					
					$gt30 = 0;
					$gt15 = 0;
					$gt7 = 0;
					$gt0 = 0;
					$x0 = 0;
					$lt0 = 0;
					$lt14 = 0;

					$sqlcustcategory = "select a.custcategoryid as custid, custcategoryname
															from custcategory a
															join paymenttargetdet b on b.custcategoryid = a.custcategoryid
															join paymenttarget c on c.paymenttargetid = b.paymenttargetid
															where c.companyid = {$companyid} and c.perioddate = '{$month1}' and c.employeeid = ".$sales['employeeid']."
															and c.recordstatus = {$maxstat}
															group by a.custcategoryid
															union
															select i.custcategoryid, i.custcategoryname
															from cutarinv a
															join cutar b on b.cutarid = a.cutarid
															join invoice c on c.invoiceid = a.invoiceid
															join giheader d on d.giheaderid = c.giheaderid
															join soheader e on e.soheaderid = d.soheaderid
															join ttnt f on f.ttntid = b.ttntid
															join employee g on g.employeeid = f.employeeid
															join addressbook h on h.addressbookid = e.addressbookid
															join custcategory i on i.custcategoryid = h.custcategoryid
															where b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and g.employeeid = ".$sales['employeeid']." and b.companyid = {$companyid} group by h.custcategoryid
							";
					
					 $this->phpExcel->setActiveSheetIndex(0)
				 ->setCellValueByColumnAndRow(0,$line,'NAMA SALES :')
				 ->setCellValueByColumnAndRow(1,$line,$sales['fullname']);
					
					$line++;
					
					$custcategory = Yii::app()->db->createCommand($sqlcustcategory)->queryAll();
					foreach($custcategory as $rslt)
					{
							$totaltargetcust = 0;
							$totalrealisasicust = 0;
							$totalgt30cust = 0; 
							$totalgt15cust = 0;
							$totalgt7cust = 0;
							$totalgt0cust = 0;
							$totalx0cust = 0;
							$totallt0cust = 0;
							$totallt14cust = 0;
							$totalkomisicust = 0;
							$percent1=0;

							if($rslt['custid']=='')
							{
									$next=0;
							}
							else
							{
									$next=1;
							}
									$sqladdressbook = "select b.addressbookid, c.fullname as custname
															from paymenttarget a
															join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
															join addressbook c on c.addressbookid = b.addressbookid
															where a.recordstatus = {$maxstat} and perioddate = '{$month1}' and a.employeeid = ".$sales['employeeid']." and a.companyid = {$companyid}
															and b.custcategoryid = ".$rslt['custid']."
															union
															select h.addressbookid, h.fullname as custname 
															from cutarinv a
															join cutar b on b.cutarid = a.cutarid
															join invoice c on c.invoiceid = a.invoiceid
															join giheader d on d.giheaderid = c.giheaderid
															join soheader e on e.soheaderid = d.soheaderid
															join ttnt f on f.ttntid = b.ttntid
															join employee g on g.employeeid = f.employeeid
															join addressbook h on h.addressbookid = e.addressbookid
															where b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and g.employeeid = ".$sales['employeeid']." and b.companyid = {$companyid} and h.custcategoryid = ".$rslt['custid']." group by h.addressbookid ";

							$addressbook = Yii::app()->db->createCommand($sqladdressbook)->queryAll();
							
							$this->phpExcel->setActiveSheetIndex(0)
						 ->setCellValueByColumnAndRow(0,$line,getCatalog('custcategory'))
						 ->setCellValueByColumnAndRow(1,$line,$rslt['custcategoryname']);
							$line++;
							
							$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(5,$line,'KUMULATIF REALISASI')
									->setCellValueByColumnAndRow(14,$line,'NILAI KOMISI TAGIHAN');
							$line++;
							$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'Ket')
									->setCellValueByColumnAndRow(1,$line,'Target Tagihan')
									->setCellValueByColumnAndRow(2,$line,'Realisasi Tagihan')
									->setCellValueByColumnAndRow(3,$line,'%')
									->setCellValueByColumnAndRow(4,$line,'X > 30')
									->setCellValueByColumnAndRow(5,$line,'15 < X <= 30')
									->setCellValueByColumnAndRow(6,$line,'7 < X <= 15')
									->setCellValueByColumnAndRow(7,$line,'0 < X <= 7')
									->setCellValueByColumnAndRow(8,$line,'X=0')
									->setCellValueByColumnAndRow(9,$line,'-14 < X <= 0')
									->setCellValueByColumnAndRow(10,$line,'X < -14')
									->setCellValueByColumnAndRow(11,$line,'X > 30')
									->setCellValueByColumnAndRow(12,$line,'15 < X <= 30')
									->setCellValueByColumnAndRow(13,$line,'7 < X <= 15')
									->setCellValueByColumnAndRow(14,$line,'0 < X <= 7')
									->setCellValueByColumnAndRow(15,$line,'X=0')
									->setCellValueByColumnAndRow(16,$line,'-14 < X <= 0')
									->setCellValueByColumnAndRow(17,$line,'X < -14')
									->setCellValueByColumnAndRow(18,$line,'TOTAL');
							$line++;
							
							foreach($addressbook as $row1)
							{   
									$sql2 = "select sum(target) as target, sum(realisasi) as realisasi,
																					sum(kumlt14) as kumlt14,sum(kumlt0) as kumlt0,sum(kumx0) as kumx0,sum(kumgt0) as kumgt0,sum(kumgt7) as kumgt7,sum(kumgt15) as kumgt15,sum(kumgt30) as kumgt30,
																					addressbookid,fullname
																	from (select ifnull(sum(gt30+gt15+gt7+gt0+x0+lt0+lt14),0) as target, 
																					 (select sum(a.cashamount+a.bankamount) as kumbayar
																													from cutarinv a
																													join cutar b on a.cutarid = b.cutarid
																													join invoice c on c.invoiceid = a.invoiceid
																													join giheader d on d.giheaderid = c.giheaderid
																													join soheader e on e.soheaderid = d.soheaderid
																													join ttnt i on i.ttntid = b.ttntid
																													join employee f on f.employeeid = i.employeeid
																													join addressbook g on g.addressbookid = e.addressbookid
																													join paymentmethod h on h.paymentmethodid = e.paymentmethodid
																													where f.employeeid = {$sales['employeeid']} and e.companyid = {$companyid}
																													and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and g.addressbookid = {$row1['addressbookid']}) as realisasi,
																									0 as kumlt14,0 as kumlt0, 0 as kumx0, 0 as kumgt0, 0 as kumgt7, 0 as kumgt15, 0 as kumgt30, c.addressbookid,c.fullname, ''
																					from paymenttarget a
																					join paymenttargetdet b on b.paymenttargetid = a.paymenttargetid
																					join addressbook c on c.addressbookid = b.addressbookid
																					where b.addressbookid = {$row1['addressbookid']} and a.recordstatus = {$maxstat} and a.companyid = {$companyid}
																					and a.employeeid = {$sales['employeeid']} and a.perioddate = '{$month1}'
																					union
																					select 0 target, 0 as realisasi,
																									case when umurtop < -14 then sum(kumbayar) else 0 end as kumlt14,
																									case when umurtop < 0 and umurtop >= -14 then sum(kumbayar) else 0 end as kumlt0,
																									case when umurtop = 0 then sum(kumbayar) else 0 end as kumx0,
																									case when umurtop > 0 and umurtop <= 7 then sum(kumbayar) else 0 end as kumgt0,
																									case when umurtop > 7 and umurtop <= 15 then sum(kumbayar) else 0 end as kumgt7,
																									case when umurtop > 15 and umurtop <= 30 then sum(kumbayar) else 0 end as kumgt15,
																									case when umurtop > 30 then sum(kumbayar) else 0 end as kumgt30, addressbookid, fullname, invoiceno
																					from (
																									select c.invoiceno, a.invoiceid, g.fullname, datediff(b.docdate,date_add(c.invoicedate, interval h.paydays day)) as umurtop, a.cashamount+a.bankamount as kumbayar, g.addressbookid
																									from cutarinv a
																									join cutar b on a.cutarid = b.cutarid
																									join invoice c on c.invoiceid = a.invoiceid
																									join giheader d on d.giheaderid = c.giheaderid
																									join soheader e on e.soheaderid = d.soheaderid
																									join ttnt i on i.ttntid = b.ttntid
																									join employee f on f.employeeid = i.employeeid
																									join addressbook g on g.addressbookid = e.addressbookid
																									join paymentmethod h on h.paymentmethodid = e.paymentmethodid
																									where f.employeeid = {$sales['employeeid']} and e.companyid = {$companyid}
																									and b.docdate between '{$month1}' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."' and b.recordstatus = 3 and g.addressbookid = {$row1['addressbookid']}
																					) z group by invoiceid) x";
									$res2 = Yii::app()->db->createCommand($sql2)->queryAll();

									foreach($res2 as $row3)
									{                    
											if($row3['target']==0)
											{
															$percent1 = 0;
											}
											else
											{
															$percent1 = $row3['realisasi']/$row3['target'];
											}
											
											$this->phpExcel->setActiveSheetIndex(0)
													->setCellValueByColumnAndRow(0,$line,$row1['custname'])
													->setCellValueByColumnAndRow(1,$line, Yii::app()->format->formatCurrency($row3['target']/$per))
													->setCellValueByColumnAndRow(2,$line, Yii::app()->format->formatCurrency($row3['realisasi']/$per))
													->setCellValueByColumnAndRow(3,$line, number_format($percent1*100,1,'.',',').' %')
													->setCellValueByColumnAndRow(4,$line, Yii::app()->format->formatCurrency($row3['kumgt30']/$per))
													->setCellValueByColumnAndRow(5,$line, Yii::app()->format->formatCurrency($row3['kumgt15']/$per))
													->setCellValueByColumnAndRow(6,$line, Yii::app()->format->formatCurrency($row3['kumgt7']/$per))
													->setCellValueByColumnAndRow(7,$line, Yii::app()->format->formatCurrency($row3['kumgt0']/$per))
													->setCellValueByColumnAndRow(8,$line, Yii::app()->format->formatCurrency($row3['kumx0']/$per))
													->setCellValueByColumnAndRow(9,$line, Yii::app()->format->formatCurrency($row3['kumlt0']/$per))
													->setCellValueByColumnAndRow(10,$line, Yii::app()->format->formatCurrency($row3['kumlt14']/$per));
											
											$totaltargetcust = ($totaltargetcust + $row3['target']);
											$totalrealisasicust = ($totalrealisasicust + $row3['realisasi']);
											$totalgt30cust = ($totalgt30cust + $row3['kumgt30']);
											$totalgt15cust = ($totalgt15cust + $row3['kumgt15']);
											$totalgt7cust  = ($totalgt7cust + $row3['kumgt7']);
											$totalgt0cust = ($totalgt0cust + $row3['kumgt0']);
											$totalx0cust = ($totalx0cust + $row3['kumx0']);
											$totallt0cust = ($totallt0cust + $row3['kumlt0']);
											$totallt14cust = ($totallt14cust + $row3['kumlt14']);
											$line++;
									}
							}
							$sqlminscale = "select minscale
																	from paymentscale t
																	where t.recordstatus = {$maxstat2} and t.perioddate = '{$month1}' 
																	and t.companyid = {$companyid}";
							$minscale = Yii::app()->db->createCommand($sqlminscale)->queryScalar();

							$this->pdf->setFont('Arial','B',8);
							$max=0;
							$min=0;
							//$this->pdf->setwidths(array(90));
							if($totaltarget==0)
							{
											$percent = 0;
											$max = 0;
											$min = 0;
							}
							else
							{
											if ($totaltargetcust==0)
											{
													$percent = 0;
													$max=0;
													$min=0;
											}
											else
											{
													$percent = ($totalrealisasicust/$totaltargetcust)*100;
											}

											if($percent>=$minscale)
											{

													// jika pencapaian diluar dari skala yg dibuat, 
													// maka diambil yg paling tinggi dari skala

													// ambil nilai min tertinggi

													if($percent>$maxscale['min'])
													{
															// check lebih tinggi dari max
															if($percent>$maxscale['max'] || $maxscale['max']=='-1')
															{
																	// ambil skala berdasarkan max persentasi
																	$gt30 = $maxscale['gt30'];
																	$gt15 = $maxscale['gt15'];
																	$gt7 = $maxscale['gt7'];
																	$gt0 = $maxscale['gt0'];
																	$x0 = $maxscale['x0'];
																	$lt0 = $maxscale['lt0'];
																	$lt14 = $maxscale['lt14'];
															}
															$gt30 = $maxscale['gt30'];
															$gt15 = $maxscale['gt15'];
															$gt7 = $maxscale['gt7'];
															$gt0 = $maxscale['gt0'];
															$x0 = $maxscale['x0'];
															$lt0 = $maxscale['lt0'];
															$lt14 = $maxscale['lt14'];
													}
													else
													{
															$sql = "select * from (select b.*
																	from paymentscale a
																	join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
																	where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid}
																	and min <= {$percent}
																	union
																	select b.*
																	from paymentscale a
																	join paymentscaledet b on b.paymentscaleid = a.paymentscaleid
																	where a.recordstatus = {$maxstat2} and a.perioddate = '{$month1}' and a.companyid = {$companyid}
																	and max <= {$percent} ) z
																	where {$percent} between min and max";
															$q = Yii::app()->db->createCommand($sql)->queryRow();
															$gt30 = $q['gt30'];
															$gt15 = $q['gt15'];
															$gt7 = $q['gt7'];
															$gt0 = $q['gt0'];
															$x0 = $q['x0'];
															$lt0 = $q['lt0'];
															$lt14 = $q['lt14'];
													}
											}
											else
											{
													//$min=' = 0'; $max=' = 0';
													$gt30 = 0;
													$gt15 = 0;
													$gt7 = 0;
													$gt0 = 0;
													$x0 = 0;
													$lt0 = 0;
													$lt14 = 0;

											}

									//$min=0; $max=0;
							}
											
							$sqlcustscale = "select paramvalue
													 from paymentscalecat a
													 join paymentscale b on b.paymentscaleid = a.paymentscaleid
													 where b.perioddate = '{$month1}' and b.recordstatus = {$maxstat2} and b.companyid = {$companyid}
													 and a.custcategoryid = ".$rslt['custid'];
							$custscale = Yii::app()->db->createCommand($sqlcustscale)->queryScalar();

							$komisigt30cust = $gt30*$totalgt30cust*($custscale/100);
							$komisigt15cust = $gt15*$totalgt15cust*($custscale/100);
							$komisigt7cust = $gt7*$totalgt7cust*($custscale/100);
							$komisigt0cust = $gt0*$totalgt0cust*($custscale/100);
							$komisix0cust = $x0*$totalx0cust*($custscale/100);
							$komisilt0cust = $lt0*$totallt0cust*($custscale/100);
							$komisilt14cust = $lt14*$totallt14cust*($custscale/100);

							$totalkomisicust = $komisigt30cust + $komisigt15cust + $komisigt7cust + $komisigt0cust + $komisix0cust + $komisilt0cust + $komisilt14cust;

							$komisigt30 += $komisigt30cust/100;
							$komisigt15 += $komisigt15cust/100;
							$komisigt7 += $komisigt7cust/100;
							$komisigt0 += $komisigt0cust/100;
							$komisix0 += $komisix0cust/100;
							$komisilt0 += $komisilt0cust/100;
							$komisilt14 += $komisilt14cust/100;
							
							$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'TOTAL KATEGORI :'.$rslt['custcategoryname'])
									->setCellValueByColumnAndRow(1,$line, Yii::app()->format->formatCurrency($totaltargetcust/$per))
									->setCellValueByColumnAndRow(2,$line, Yii::app()->format->formatCurrency($totalrealisasicust/$per))
									->setCellValueByColumnAndRow(3,$line, number_format($percent,0,'.',',').' %')
									->setCellValueByColumnAndRow(4,$line, Yii::app()->format->formatCurrency($totalgt30cust/$per))
									->setCellValueByColumnAndRow(5,$line, Yii::app()->format->formatCurrency($totalgt15cust/$per))
									->setCellValueByColumnAndRow(6,$line, Yii::app()->format->formatCurrency($totalgt7cust/$per))
									->setCellValueByColumnAndRow(7,$line, Yii::app()->format->formatCurrency($totalgt0cust/$per))
									->setCellValueByColumnAndRow(8,$line, Yii::app()->format->formatCurrency($totalx0cust/$per))
									->setCellValueByColumnAndRow(9,$line, Yii::app()->format->formatCurrency($totallt0cust/$per))
									->setCellValueByColumnAndRow(10,$line, Yii::app()->format->formatCurrency($totallt14cust/$per))
									->setCellValueByColumnAndRow(11,$line, Yii::app()->format->formatCurrency($komisigt30cust/$per))
									->setCellValueByColumnAndRow(12,$line, Yii::app()->format->formatCurrency($komisigt15cust/$per))
									->setCellValueByColumnAndRow(13,$line, Yii::app()->format->formatCurrency($komisigt7cust/$per))
									->setCellValueByColumnAndRow(14,$line, Yii::app()->format->formatCurrency($komisigt0cust/$per))
									->setCellValueByColumnAndRow(15,$line, Yii::app()->format->formatCurrency($komisix0cust/$per))
									->setCellValueByColumnAndRow(16,$line, Yii::app()->format->formatCurrency($komisilt0cust/$per))
									->setCellValueByColumnAndRow(17,$line, Yii::app()->format->formatCurrency($komisilt14cust/$per))
									->setCellValueByColumnAndRow(18,$line, Yii::app()->format->formatCurrency($totalkomisicust/$per));
							$line=$line+2;
					}
					$totalkomisi = $komisigt30 + $komisigt15 + $komisigt7 + $komisigt0 + $komisix0 + $komisilt0 + $komisilt14;
					if ($totaltarget == 0){
							$persentotal = 0;	
					}
					else
					{
							$persentotal = ($totalrealisasi/$totaltarget)*100;
					}
					if($next!='')
					{
							$line++;
							$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'TOTAL SALES :'.$sales['fullname'])
									->setCellValueByColumnAndRow(1,$line, Yii::app()->format->formatCurrency($totaltarget/$per))
									->setCellValueByColumnAndRow(2,$line, Yii::app()->format->formatCurrency($totalrealisasi/$per))
									->setCellValueByColumnAndRow(3,$line, number_format($persentotal,0,'.',',').' %')
									->setCellValueByColumnAndRow(4,$line, Yii::app()->format->formatCurrency($totalgt30/$per))
									->setCellValueByColumnAndRow(5,$line, Yii::app()->format->formatCurrency($totalgt15/$per))
									->setCellValueByColumnAndRow(6,$line, Yii::app()->format->formatCurrency($totalgt7/$per))
									->setCellValueByColumnAndRow(7,$line, Yii::app()->format->formatCurrency($totalgt0/$per))
									->setCellValueByColumnAndRow(8,$line, Yii::app()->format->formatCurrency($totalx0/$per))
									->setCellValueByColumnAndRow(9,$line, Yii::app()->format->formatCurrency($totallt0/$per))
									->setCellValueByColumnAndRow(10,$line, Yii::app()->format->formatCurrency($totallt14/$per))
									->setCellValueByColumnAndRow(11,$line, Yii::app()->format->formatCurrency($komisigt30/$per))
									->setCellValueByColumnAndRow(12,$line, Yii::app()->format->formatCurrency($komisigt15/$per))
									->setCellValueByColumnAndRow(13,$line, Yii::app()->format->formatCurrency($komisigt7/$per))
									->setCellValueByColumnAndRow(14,$line, Yii::app()->format->formatCurrency($komisigt0/$per))
									->setCellValueByColumnAndRow(15,$line, Yii::app()->format->formatCurrency($komisix0/$per))
									->setCellValueByColumnAndRow(16,$line, Yii::app()->format->formatCurrency($komisilt0/$per))
									->setCellValueByColumnAndRow(17,$line, Yii::app()->format->formatCurrency($komisilt14/$per))
									->setCellValueByColumnAndRow(18,$line, Yii::app()->format->formatCurrency($totalkomisi/$per));
					}
					
					$line++;
					$deposit = (($totalkomisi)*10/100)/$per;        
					
					$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(12,$line,'Deposit 10 :')
									->setCellValueByColumnAndRow(13,$line,Yii::app()->format->formatCurrency($deposit).'=')
									->setCellValueByColumnAndRow(14,$line,Yii::app()->format->formatCurrency(($totalkomisi/$per)-$deposit));
					
					$line=$line+2;
					$this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'Diperiksa Oleh :')
									->setCellValueByColumnAndRow(2,$line,'Disetujui Oleh :')
									->setCellValueByColumnAndRow(6,$line,'Mengetahui :')
									->setCellValueByColumnAndRow(10,$line,'Dibayar Oleh :')
									->setCellValueByColumnAndRow(15,$line,'Diterima Oleh :');
					
				 $line=$line+4;
				 $this->phpExcel->setActiveSheetIndex(0)
									->setCellValueByColumnAndRow(0,$line,'ACC & HEAD FINANCE ')
									->setCellValueByColumnAndRow(2,$line,'HEAD MARKETING ')
									->setCellValueByColumnAndRow(6,$line,'BRANCH MANAGER ')
									->setCellValueByColumnAndRow(10,$line,'KASIR ')
									->setCellValueByColumnAndRow(15,$line,'SALES ');
					
		}
		$this->getFooterXLS($this->phpExcel);
	}
	//33
	
	//34
	
	//35
	public function RekapUmurPiutangDagangPerCustomerVsTopXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekapumurpiutangdagangpercustomervstop';
		parent::actionDownxls();
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			$isdisplay1= " and c.isdisplay = ".$isdisplay." ";
		}
        
        $url = $_SERVER['REQUEST_URI'];
        $strpos1 = strpos($url,'salesarea');
        $strpos2 = strpos($url,'umurpiutang');

        $salesarea = substr($url,$strpos1+10,($strpos2-$strpos1-11));
        if ($umurpiutang != '')
        {
        	$umur = " where umur > ".$umurpiutang." ";
        }
        else
        {
        	$umur = '';
        }
		$sql1 = "select salesname, employeeid from (select salesname, employeeid
							from(select salesname, employeeid, amount, payamount
							from (select f.fullname as salesname, d.fullname,e.paydays,f.employeeid,datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."',
							date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
							date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('".date(Yii::app()->params['datetodb'],strtotime($enddate))."',a.invoicedate) as umur,a.amount,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
							from cutarinv f
							join cutar g on g.cutarid=f.cutarid
							where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'],strtotime($enddate))."'),0) as payamount
							from invoice a
							inner join giheader b on b.giheaderid = a.giheaderid
							inner join gidetail h on h.giheaderid = b.giheaderid
							inner join soheader c on c.soheaderid = b.soheaderid
							inner join addressbook d on d.addressbookid = c.addressbookid
							inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
							inner join employee f on f.employeeid = c.employeeid
							inner join sloc g on g.slocid = h.slocid
							inner join salesarea i on i.salesareaid = d.salesareaid
							where d.fullname like '%{$customer}%' and f.fullname like '%{$sales}%' and a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid}
							and g.sloccode like '%{$sloc}%'
							and i.areaname like '%{$salesarea}%' ".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'],strtotime($enddate))."')z {$umur})zz 
							where amount > payamount)zzz group by salesname
		";
        
        $dataReader=Yii::app()->db->createCommand($sql1)->queryAll();
			
		
		if(isset($isdisplay) && ($isdisplay != ''))
		{
			if($isdisplay == "1")
			{
				$title='Rekap Umur Piutang Dagang Per Customer VS TOP (HANYA DISPLAY)';
			}
			else if($isdisplay == "0")
			{
				$title='Rekap Umur Piutang Dagang Per Customer VS TOP (BUKAN DISPLAY)';
			}
		}
		else
		{
			$title='Rekap Umur Piutang Dagang Per Customer VS TOP';
		}
        $this->phpExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(0,1,$title)
                        ->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
                        ->setCellValueByColumnAndRow(7,1,GetCompanyCode($companyid));
        $line=4;
        foreach($dataReader as $row)
        {
               
            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,'Sales')
                ->setCellValueByColumnAndRow(1,$line,': '.$row['salesname']);
            
            $line++;
            
            $i=0;
            $totallt14=0;
            $totallt0=0;
            $totalx0=0;
            $totalgt0=0;
            $totalgt7=0;
            $totalgt15=0;
            $totalgt30=0;
            $total=0;		

            $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0,$line,'No')
                    ->setCellValueByColumnAndRow(1,$line,'Nama Customer')
                    ->setCellValueByColumnAndRow(2,$line,'X < -14 Hari')	
                    ->setCellValueByColumnAndRow(3,$line,'-14 < X < 0 Hari')
                    ->setCellValueByColumnAndRow(4,$line,'X = 0 Hari')
                    ->setCellValueByColumnAndRow(5,$line,'0 < X <= 7 Hari')
                    ->setCellValueByColumnAndRow(6,$line,'7 < X <= 15 Hari')
                    ->setCellValueByColumnAndRow(7,$line,'15 < X <= 30 Hari')
                    ->setCellValueByColumnAndRow(8,$line,'X > 30 Hari')
                    ->setCellValueByColumnAndRow(9,$line,'Total');
            $line++;
             
            $sql2 = "select *,sum(lt14) as lt14, sum(lt0) as lt0,sum(x0) as x0, sum(gt0) as gt0, sum(gt7) as gt7, sum(gt15) as gt15, sum(gt30) as gt30
			from (select *, 
			case when umurtempo < -14 then totamount else 0 end as lt14,
			case when umurtempo >= -14 and umurtempo < 0 then totamount else 0 end as lt0,
							case when umurtempo = 0 then totamount else 0 end as x0,
			case when umurtempo > 0 and umurtempo <= 7 then totamount else 0 end as gt0,
			case when umurtempo > 7 and umurtempo <= 15 then totamount else 0 end as gt7,
			case when umurtempo > 15 and umurtempo <= 30 then totamount else 0 end as gt15,
			case when umurtempo > 30 then totamount else 0 end as gt30
			from(select *,amount - payamount as totamount
			from (select d.fullname,e.paydays,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',
			date_add(a.invoicedate,interval e.paydays day)) as umurtempo,
			date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
			datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,a.amount,
			ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
			from cutarinv f
			join cutar g on g.cutarid=f.cutarid
			where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
			from invoice a
			inner join giheader b on b.giheaderid = a.giheaderid
							-- inner join gidetail h on h.giheaderid = b.giheaderid
			inner join soheader c on c.soheaderid = b.soheaderid
			inner join addressbook d on d.addressbookid = c.addressbookid
			inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
			inner join employee f on f.employeeid = c.employeeid
							inner join salesarea i on i.salesareaid = d.salesareaid
							-- inner join sloc g on g.slocid = h.slocid
			where d.fullname like '%".$customer."%' and f.employeeid = {$row['employeeid']} and a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$companyid." 	
							-- and g.sloccode like '%{$sloc}%'
							and i.areaname like '%{$salesarea}%' ".$isdisplay1."
							and a.invoicedate <= '".date(Yii::app()->params['datetodb'],strtotime($enddate))."')z {$umur})zz 
			where amount > payamount)zzz
			group by fullname
			order by fullname";
            
            $dataReader1 = Yii::app()->db->createCommand($sql2)->queryAll();
            foreach($dataReader1 as $row1)
            {
            
                    $i+=1;
                    $this->phpExcel->setActiveSheetIndex(0)
                                    ->setCellValueByColumnAndRow(0,$line,$i)
                                    ->setCellValueByColumnAndRow(1,$line,$row1['fullname'])
                                    ->setCellValueByColumnAndRow(2,$line,($row1['lt14']/$per))
                                    ->setCellValueByColumnAndRow(3,$line,($row1['lt0']/$per))
                                    ->setCellValueByColumnAndRow(4,$line,($row1['x0']/$per))
                                    ->setCellValueByColumnAndRow(5,$line,($row1['gt0']/$per))
                                    ->setCellValueByColumnAndRow(6,$line,($row1['gt7']/$per))
                                    ->setCellValueByColumnAndRow(7,$line,($row1['gt15']/$per))
                                    ->setCellValueByColumnAndRow(8,$line,($row1['gt30']/$per))
                                    ->setCellValueByColumnAndRow(9,$line,(($row1['lt14']+$row1['lt0']+$row1['x0']+$row1['gt0']+$row1['gt7']+$row1['gt15']+$row1['gt30'])/$per));
                        $line++;
                        $totallt14 += $row1['lt14']/$per;
                        $totallt0 += $row1['lt0']/$per;
                        $totalx0 += $row1['x0']/$per;
                        $totalgt0 += $row1['gt0']/$per;
                        $totalgt7 += $row1['gt7']/$per;
                        $totalgt15 += $row1['gt15']/$per;
                        $totalgt30 += $row1['gt30']/$per;
                        $total += ($row1['lt14']+$row1['lt0']+$row1['x0']+$row1['gt0']+$row1['gt7']+$row1['gt15']+$row1['gt30'])/$per;

            }
            $this->phpExcel->setActiveSheetIndex(0)
                            ->setCellValueByColumnAndRow(1,$line,'Total')
                            ->setCellValueByColumnAndRow(2,$line,($totallt14))
                            ->setCellValueByColumnAndRow(3,$line,($totallt0))
                            ->setCellValueByColumnAndRow(4,$line,($totalx0))
                            ->setCellValueByColumnAndRow(5,$line,($totalgt0))
                            ->setCellValueByColumnAndRow(6,$line,($totalgt7))
                            ->setCellValueByColumnAndRow(7,$line,($totalgt15))
                            ->setCellValueByColumnAndRow(8,$line,($totalgt30))
                            ->setCellValueByColumnAndRow(9,$line,($total));
            $line+=2;
        }
		$this->getFooterXLS($this->phpExcel);
	}
	//36
	
	//37
	
	//38
	public function
	RekapUmurPiutangDagangPerCompanyXLS($companyid,$sloc,$materialgroup,$customer,$product,$sales,$spv,$salesarea,$umurpiutang,$isdisplay,$startdate,$enddate,$per)
	{
		$this->menuname='rekapumurpiutangdagangpercompany';
		parent::actionDownxls();
		$sql = Yii::app()->db->createCommand("select companyname,companyid, companycode from company where recordstatus=1 order by nourut");
		$dataReader = $sql->queryAll();
			
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(2,2,date(Yii::app()->params['dateviewfromdb'],strtotime($enddate)));
		$line = 3;
			
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,$line,'Kode')
			->setCellValueByColumnAndRow(1,$line,'Umur 0-30')
				->setCellValueByColumnAndRow(2,$line,'%')
			->setCellValueByColumnAndRow(3,$line,'Umur 31-60')
				->setCellValueByColumnAndRow(4,$line,'%')
			->setCellValueByColumnAndRow(5,$line,'Umur 61-90')
				->setCellValueByColumnAndRow(6,$line,'%')
			->setCellValueByColumnAndRow(7,$line,'Umur 91-120')
				->setCellValueByColumnAndRow(8,$line,'%')
			->setCellValueByColumnAndRow(9,$line,'> 120')
				->setCellValueByColumnAndRow(10,$line,'%')
			->setCellValueByColumnAndRow(11,$line,'Total');
			
		$line++;
		
		$totsd30=0;
		$totsd60=0;
		$totsd90=0;
		$totsd120=0;
		$totup120=0;
			
		foreach($dataReader as $row)
		{
			$sql1 = "select sum(a1) as 0sd30,sum(a2) as 31sd60,sum(a3) as 61sd90,sum(a4) as 91sd120,sum(a5) as up120
							from (select case when umur >= 0 and umur <= 30 then amount-payamount else 0 end as a1,
							case when umur > 30 and umur <= 60 then amount-payamount else 0 end as a2,
							case when umur > 60 and umur <= 90 then amount-payamount else 0 end as a3,
							case when umur > 90 and umur <= 120 then amount-payamount else 0 end as a4,
							case when umur > 120 then amount-payamount else 0 end as a5
								from (select a.amount,datediff('".date(Yii::app()->params['datetodb'], strtotime($enddate))."',a.invoicedate) as umur,
								ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0)) from cutarinv f join cutar g on g.cutarid=f.cutarid where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'),0) as payamount
									from invoice a
									inner join giheader b on b.giheaderid = a.giheaderid
									inner join soheader c on c.soheaderid = b.soheaderid
									inner join addressbook d on d.addressbookid = c.addressbookid
									inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
									inner join employee ff on ff.employeeid = c.employeeid
									left join salesarea gg on gg.salesareaid = d.salesareaid
									where a.recordstatus=3 and a.invoiceno is not null and c.companyid = ".$row['companyid']." and d.fullname like '%".$customer."%' and ff.fullname like '%".$sales."%' and gg.areaname like '%".$salesarea."%' and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
								where amount > payamount) zz
			";
			$row1=Yii::app()->db->createCommand($sql1)->queryRow();
			
			if($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120'] == 0)
			{
				$perc0 = 0;
				$perc30 = 0;
				$perc60 = 0;
				$perc90 = 0;
				$perc120 = 0;
			}
			else
			{
				$perc0 = ($row1['0sd30']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
				$perc30 = ($row1['31sd60']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
				$perc60 = ($row1['61sd90']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
				$perc90 = ($row1['91sd120']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
				$perc120 = ($row1['up120']) / ($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120']);
			}
					
			$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,$line,$row['companycode'])
			->setCellValueByColumnAndRow(1,$line,($row1['0sd30']))
				->setCellValueByColumnAndRow(2,$line,($perc0 * 100))
			->setCellValueByColumnAndRow(3,$line,($row1['31sd60']))
				->setCellValueByColumnAndRow(4,$line,($perc30 * 100))
			->setCellValueByColumnAndRow(5,$line,($row1['61sd90']))
				->setCellValueByColumnAndRow(6,$line,($perc60 * 100))
			->setCellValueByColumnAndRow(7,$line,($row1['91sd120']))
				->setCellValueByColumnAndRow(8,$line,($perc90 * 100))
			->setCellValueByColumnAndRow(9,$line,($row1['up120']))
				->setCellValueByColumnAndRow(10,$line,($perc120 * 100))
			->setCellValueByColumnAndRow(11,$line,(($row1['0sd30']+$row1['31sd60']+$row1['61sd90']+$row1['91sd120']+$row1['up120'])));
							
			$line++;
			
			$totsd30 += $row1['0sd30']/$per;
			$totsd60 += $row1['31sd60']/$per;
			$totsd90 += $row1['61sd90']/$per;
			$totsd120 += $row1['91sd120']/$per;
			$totup120 += $row1['up120']/$per;
			
			if($totsd30+$totsd60+$totsd90+$totsd120+$totup120 == 0)
			{
				$penc0 = 0;
				$penc30 = 0;
				$penc60 = 0;
				$penc90 = 0;
				$penc120 = 0;
			}
			else
			{
				$penc0	= ($totsd30) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
				$penc30 = ($totsd60) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
				$penc60 = ($totsd90) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
				$penc90 = ($totsd120) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
				$penc120 = ($totup120) / ($totsd30+$totsd60+$totsd90+$totsd120+$totup120);
			}
		}
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,$line,'TOTAL ')
		->setCellValueByColumnAndRow(1,$line,($totsd30))
			->setCellValueByColumnAndRow(2,$line,($penc0 * 100))
		->setCellValueByColumnAndRow(3,$line,($totsd60))
			->setCellValueByColumnAndRow(4,$line,($penc30 * 100))
		->setCellValueByColumnAndRow(5,$line,($totsd90))
			->setCellValueByColumnAndRow(6,$line,($penc60 * 100))
		->setCellValueByColumnAndRow(7,$line,($totsd120))
			->setCellValueByColumnAndRow(8,$line,($penc90 * 100))
		->setCellValueByColumnAndRow(9,$line,($totup120))
			->setCellValueByColumnAndRow(10,$line,($penc120 * 100))
		->setCellValueByColumnAndRow(11,$line,(($totsd30+$totsd60+$totsd90+$totsd120+$totup120)));
			
		$this->getFooterXLS($this->phpExcel);
	}
	
}
