<?php

class ReppurchaseController extends Controller
{
        public $menuname = 'reppurchase';
        public function actionIndex()
        {
                $this->renderPartial('index',array());
        }
        
        public function actionDownPDF()
        {
          parent::actionDownload();
                if (isset($_GET['lro']) && isset($_GET['company']) && isset($_GET['startdate']) && isset($_GET['enddate']))
		{
			if ($_GET['lro'] == 1)
			{
				$this->RincianPembelianPerDokumen($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
			else
			if ($_GET['lro'] == 2)
			{
				$this->RekapPembelianPerDokumen($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
			else
			if ($_GET['lro'] == 3)
			{
				$this->RekapPembelianPerSupplier($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 4)
			{
				$this->RekapPembelianPerBarang($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 5)
			{
				$this->RekapPembelianPerArea($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 6)
			{
				$this->RincianReturPembelianPerDokumen($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
			if ($_GET['lro'] == 7)
			{
				$this->RekapReturPembelianPerDokumen($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
			if ($_GET['lro'] == 8)
			{
				$this->RekapReturPembelianPerSupplier($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 9)
			{
				$this->RekapReturPembelianPerBarang($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 10)
			{
				$this->RekapReturPembelianPerArea($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 11)
			{
				$this->RincianSelisihPembelianReturPerDokumen($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 12)
			{
				$this->RekapSelisihPembelianReturPerSupplier($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 13)
			{
				$this->RekapSelisihPembelianReturPerDokumen($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
                        else
                        if ($_GET['lro'] == 14)
			{
				$this->RekapSelisihPembelianReturPerBarang($_GET['company'],$_GET['startdate'],$_GET['enddate']);
			}
		}
        }
        
        public function RincianPembelianPerDokumen($companyid,$startdate,$enddate)
        {
                parent::actionDownload();
                $sql = "select *,(nominal-ppn) as netto
                        from
                        (select *,(select nominal*c.taxvalue/100 from tax c where c.taxid=zz.taxid) as ppn
                        from 
                        (select a.poheaderid,a.taxid,a.pono,a.docdate,d.fullname,e.paydays,(select sum(b.poqty*b.netprice) 
                        from podetail b where b.poheaderid=a.poheaderid) as nominal
                        from poheader a
                        inner join addressbook d on d.addressbookid=a.addressbookid
                        inner join paymentmethod e on e.paymentmethodid=a.paymentmethodid
                        where a.pono is not null and a.companyid = ".$companyid." and 
			a.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
			and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') zz)zzz";

			$command=$this->connection->createCommand($sql);
			$dataReader=$command->queryAll();
                        
                        foreach($dataReader as $row)
                        {
                            $this->pdf->companyid = $companyid;
                        }
                        $this->pdf->title='Rincian Pembelian Per Dokumen';
            $this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$this->pdf->text(10,$this->pdf->gety()+10,'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
			$this->pdf->AddPage('P');
                        
                        $this->pdf->sety($this->pdf->gety()+5);
                        $totalallqty = 0;
												$totalallrp = 0;
                        foreach($dataReader as $row)
                        {
                                $this->pdf->SetFont('Arial','',10);
				$this->pdf->text(10,$this->pdf->gety()+10,'No Bukti');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row['pono']);
				$this->pdf->text(10,$this->pdf->gety()+15,'Supplier');$this->pdf->text(30,$this->pdf->gety()+15,': '.$row['fullname']);
				$this->pdf->text(150,$this->pdf->gety()+10,'Tgl Bukti');$this->pdf->text(180,$this->pdf->gety()+10,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
				$this->pdf->text(150,$this->pdf->gety()+15,'T.O.P');$this->pdf->text(180,$this->pdf->gety()+15,': '.$row['paydays'].' HARI');
                                $sql1 = "select c.productname, a.poqty,d.uomcode,a.netprice,a.poqty * a.netprice as jumlah,a.itemtext
					from podetail a
					inner join product c on c.productid = a.productid
					inner join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
					where a.poheaderid = ".$row['poheaderid'];
                                $command1=$this->connection->createCommand($sql1);
				$dataReader1=$command1->queryAll();
				$total = 0;$i=0;$totalqty=0;
				$this->pdf->sety($this->pdf->gety()+20);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(10,50,10,15,30,30,43));
				$this->pdf->colheader = array('No','Nama Barang','Qty','Satuan','Harga','Jumlah','Keterangan');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','R','C','R','R','R');
				$this->pdf->setFont('Arial','',8);
                                foreach($dataReader1 as $row1)
                                {
                                        $i+=1;
					$this->pdf->row(array(
						$i,$row1['productname'],
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['poqty']),$row1['uomcode'],
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['netprice']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['jumlah']),
                                                $row1['itemtext']
					));
					$totalqty += $row1['poqty'];
					$total += $row1['jumlah'];
					$totalallqty += $row1['poqty'];
					$totalallrp += $row1['jumlah'];
                                }
                                $this->pdf->row(array(
						'','KETERANGAN',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty),'',
						'','NOMINAL',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$total),
					));
                                $this->pdf->row(array(
						'','',
						'',
						'','',
						'PPN',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['ppn']),
					));
                                $this->pdf->row(array(
						'','',
						'','',
						'',
						'NETTO',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['netto']),
					));
                        }
												$this->pdf->row(array(
						'',
						'Total Qty ',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalallqty),'',
						'',
						'Total',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalallrp),
					));
                        $this->pdf->Output();
        }
        
        public function RekapPembelianPerDokumen($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select *,(nominal-ppn) as netto
                    from
                    (select *,(select nominal*c.taxvalue/100 from tax c where c.taxid=zz.taxid) as ppn
                    from 
                    (select a.poheaderid,a.taxid,a.pono,a.docdate,d.fullname,(select sum(b.poqty*b.netprice) 
                    from podetail b where b.poheaderid=a.poheaderid) as nominal
                    from poheader a
                    inner join addressbook d on d.addressbookid=a.addressbookid
                    where a.pono is not null and a.companyid = ".$companyid." and 
                    a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') zz)zzz";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            $this->pdf->title='Rekap Pembelian Per Dokumen';
            $this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');

            $this->pdf->sety($this->pdf->gety()+10);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->colalign = array('C','C','C','C','C','C','C','C');
            $this->pdf->setwidths(array(10,25,25,45,30,25,25,25));
            $this->pdf->colheader = array('No','No Bukti','Tanggal','Supplier','Nominal','PPN','Total');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','C','L','R','R','R','L');		
            $total = 0;$i=0;$totalqty=0;
            
            foreach($dataReader as $row)
            {
                $i+=1;
                $this->pdf->setFont('Arial','',7);
                $this->pdf->row(array(
                        $i,$row['pono'],
                        date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
                        $row['fullname'],
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['nominal']),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['ppn']),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['netto']),
                ));
								$total += $row['nominal'];
								$totalqty += $row['netto'];
            }
						 $this->pdf->row(array(
                        '','',
                        'Grand Total',
                        '',
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$total),
                        '',
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty),
                ));
            $this->pdf->Output();
        }
        
        public function RekapPembelianPerSupplier($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select fullname,sum(nominal) as nominal,sum(ppn) as ppn,sum(netto) as netto
                    from
                    (select *,(nominal-ppn) as netto
                    from
                    (select *,(select sum(nominal*c.taxvalue/100) from tax c where c.taxid=zz.taxid) as ppn
                    from 
                    (select a.poheaderid,a.taxid,a.addressbookid,a.pono,a.docdate,d.fullname,(select sum(b.poqty*b.netprice) 
                    from podetail b where b.poheaderid=a.poheaderid) as nominal
                    from poheader a
                    inner join addressbook d on d.addressbookid=a.addressbookid
                    where a.pono is not null and a.companyid = ".$companyid." and 
                    a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') zz)zzz)xx
                    group by fullname";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            $totalppn = 0;$totalnominal=0;$total=0;
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            $this->pdf->title='Rekap Pembelian Per Supplier';
            $this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');

            $this->pdf->sety($this->pdf->gety()+10);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->colalign = array('C','C','C','C','C','C','C','C');
            $this->pdf->setwidths(array(10,70,50,20,40));
            $this->pdf->colheader = array('No','Nama Supplier','Nominal','PPN','Total');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L','L','R','R','R','L');		
            $i=0;
            
            foreach($dataReader as $row)
            {
                $i+=1;
                $this->pdf->setFont('Arial','',7);
                $this->pdf->row(array(
                        $i,
                        $row['fullname'],
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['nominal']),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['ppn']),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['netto']),
                ));
                $totalnominal += $row['nominal'];
                $totalppn += $row['ppn'];
                $total += $row['netto'];
            }
                $this->pdf->row(array(
                        '','GRAND TOTAL',
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalnominal),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalppn),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$total),
                ));
                $this->pdf->checkPageBreak(20);
            $this->pdf->Output();
        }
        
        public function RekapPembelianPerBarang($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select a.materialgroupid,a.description from materialgroup a
                    where a.materialgroupid in (select b.materialgroupid from productplant b
                    where b.productid in (select c.productid from podetail c inner join poheader d on d.poheaderid=c.poheaderid 
                    where d.companyid = ".$companyid." and d.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'))";
            
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            
            $this->pdf->title='Rekap Pembelian Per Barang';
            $this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');
			
            $this->pdf->sety($this->pdf->gety()+5);
            $i=0;
            
            foreach($dataReader as $row)
            {
                $this->pdf->SetFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->gety()+10,'Divisi');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row['description']);
                $sql1 ="select description as barang,productname,poqty as qty,nominal,ppn,(nominal-ppn) as total
                        from (select *,(select sum(nominal*d.taxvalue/100) from tax d where d.taxid=z.taxid) as ppn
                        from
                        (select b.taxid,c.productname,f.description,a.poqty,a.poqty*a.netprice as nominal from podetail a
                        inner join poheader b on b.poheaderid=a.poheaderid
                        inner join product c on c.productid=a.productid
                        inner join productplant e on e.productid=c.productid
                        inner join materialgroup f on f.materialgroupid=e.materialgroupid) z) zz
                        group by description";
                $command1=$this->connection->createCommand($sql1);
                $dataReader1=$command1->queryAll();
                $totalqty=0;$totalppn=0;$totalnominal=0;$total=0;
                
                $this->pdf->sety($this->pdf->gety()+15);
                $this->pdf->setFont('Arial','B',8);
                $this->pdf->colalign = array('C','C','C','C','C','C');
                $this->pdf->setwidths(array(10,60,30,30,30,30));    
                $this->pdf->colheader = array('No','Nama Barang','QTY','Nominal','PPN','Total');
                $this->pdf->RowHeader();
                $this->pdf->coldetailalign = array('L','L','R','R','R','R');
                $this->pdf->setFont('Arial','',8);
                
                foreach($dataReader1 as $row1)
                {
                        $i+=1;
                        $this->pdf->row(array(
                                $i,$row1['productname'],
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['qty']),
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['nominal']),
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['ppn']),
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['total']),
                        ));
                        $totalqty += $row1['qty'];
                        $totalnominal += $row1['nominal'];
                        $totalppn += $row1['ppn'];
                        $total += $row1['total'];
                }
                $this->pdf->row(array(
                        '','Total',
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalnominal),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalppn),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$total),
                ));
                $this->pdf->checkPageBreak(20);
            }
            $this->pdf->Output();
        }
        
        public function RekapPembelianPerArea($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select a.cityid,a.cityname from city a where a.cityid in
                    (select b.cityid from company b where b.companyid in (select c.companyid from poheader c inner join podetail d on d.poheaderid=c.poheaderid
                    where c.companyid = ".$companyid." and c.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'))";
            
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            
            $this->pdf->title='Rekap Pembelian Per Area';
            $this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');
			
            $this->pdf->sety($this->pdf->gety()+5);
            $i=0;
            
            foreach($dataReader as $row)
            {
                $this->pdf->SetFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->gety()+10,'Lokasi');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row['cityname']);
                $sql1 ="select divisi,poqty as qty,nominal,ppn,(nominal-ppn) as total
                        from (select *,(select sum(nominal*d.taxvalue/100) from tax d where d.taxid=z.taxid) as ppn
                        from
                        (select b.taxid,c.productname,f.description as divisi,a.poqty,a.poqty*a.netprice as nominal from podetail a
                        inner join poheader b on b.poheaderid=a.poheaderid
                        inner join product c on c.productid=a.productid
                        inner join productplant e on e.productid=c.productid
                        inner join materialgroup f on f.materialgroupid=e.materialgroupid
                        inner join company g on g.companyid=b.companyid) z) zz
                        group by divisi";
                $command1=$this->connection->createCommand($sql1);
                $dataReader1=$command1->queryAll();
                $totalqty=0;$totalppn=0;$totalnominal=0;$total=0;
                
                $this->pdf->sety($this->pdf->gety()+15);
                $this->pdf->setFont('Arial','B',8);
                $this->pdf->colalign = array('C','C','C','C','C','C');
                $this->pdf->setwidths(array(10,60,30,30,30,30));
                $this->pdf->colheader = array('No','Keterangan','QTY','Nominal','PPN','Total');
                $this->pdf->RowHeader();
                $this->pdf->coldetailalign = array('L','L','R','R','R','R');
                $this->pdf->setFont('Arial','',8);
                
                foreach($dataReader1 as $row1)
                {
                        $i+=1;
                        $this->pdf->row(array(
                                $i,$row1['divisi'],
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['qty']),
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['nominal']),
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['ppn']),
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['total']),
                        ));
                        $totalqty += $row1['qty'];
                        $totalnominal += $row1['nominal'];
                        $totalppn += $row1['ppn'];
                        $total += $row1['total'];
                }
                $this->pdf->row(array(
                        '','Total',
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalnominal),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalppn),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$total),
                ));
                $this->pdf->checkPageBreak(20);
            }
            $this->pdf->Output();
        }
        
        public function RincianReturPembelianPerDokumen($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select *,(nominal-ppn) as netto
                    from 
                    (select *,
                    (select nominal*i.taxvalue/100 from tax i where i.taxid=z.taxid) as ppn
                    from 
                    (select a.grreturid,a.grreturno,g.fullname as supplier,a.grreturdate,h.paycode,f.taxid,
                    (
                        select sum(b.netprice*c.qty) 
                        from podetail b
                        where b.podetailid=c.podetailid 
                        and b.productid=c.productid
                    ) as nominal
                    from grretur a
                    join grreturdetail c on c.grreturid=a.grreturid
                    join product d on d.productid=c.productid
                    join podetail e on e.podetailid=c.podetailid
                    join poheader f on f.poheaderid=e.poheaderid
                    join addressbook g on g.addressbookid=f.addressbookid
                    join paymentmethod h on h.paymentmethodid=f.paymentmethodid
                    where a.recordstatus = 3 and f.companyid = ".$companyid." and 
                    f.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z) zz";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            $this->pdf->title='Rincian Retur Pembelian Per Dokumen';
            $this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');
            
            $this->pdf->sety($this->pdf->gety()+5);
            
            foreach($dataReader as $row)
            {
                $this->pdf->SetFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->gety()+10,'Dokumen');$this->pdf->text(40,$this->pdf->gety()+10,': '.$row['grreturno']);
                $this->pdf->text(10,$this->pdf->gety()+15,'Supplier');$this->pdf->text(40,$this->pdf->gety()+15,': '.$row['supplier']);
                $this->pdf->text(130,$this->pdf->gety()+10,'Tanggal');$this->pdf->text(160,$this->pdf->gety()+10,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['grreturdate'])));
                $this->pdf->text(130,$this->pdf->gety()+15,'T.O.P');$this->pdf->text(160,$this->pdf->gety()+15,': '.$row['paycode'].' HARI');                
                $sql1 = "select b.productname,a.qty,c.netprice,(a.qty*c.netprice) as jumlah,a.itemnote
                        from grreturdetail a
                        join product b on b.productid=a.productid
                        join podetail c on c.podetailid=a.podetailid
                        join unitofmeasure d on d.unitofmeasureid=a.uomid
                        where a.grreturid = ".$row['grreturid'];
                $command1=$this->connection->createCommand($sql1);
                $dataReader1=$command1->queryAll();
                $i=0;$totalqty=0;
                $this->pdf->sety($this->pdf->gety()+25);
                $this->pdf->setFont('Arial','B',8);
                $this->pdf->colalign = array('C','C','C','C','C','C');
                $this->pdf->setwidths(array(10,70,10,20,30,50));
                $this->pdf->colheader = array('No','Nama Barang','Qty','Harga','Jumlah','Keterangan');
                $this->pdf->RowHeader();
                $this->pdf->coldetailalign = array('L','L','R','R','R','R');
                $this->pdf->setFont('Arial','',8);
                
                foreach($dataReader1 as $row1)
                {
                    $i+=1;
                    $this->pdf->row(array(
                            $i,$row1['productname'],
                            Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['qty']),
                            Yii::app()->format->formatNumber($row1['netprice']),
                            Yii::app()->format->formatNumber($row1['jumlah']),
                        $row1['itemnote'],
                    ));
                    $totalqty += $row1['qty'];
                }
                $this->pdf->row(array(
                            '','Keterangan : '.$row1['itemnote'],
                            Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty),
                            '','NOMINAL',
                            Yii::app()->format->formatNumber($row['nominal']),
                    ));
                $this->pdf->row(array(
                            '',
                            '',
                            '','',
                            'PPN',
                            Yii::app()->format->formatNumber($row['ppn']),
                    ));
                $this->pdf->row(array(
                            '',
                            '',
                            '','',
                            'NETTO',
                            Yii::app()->format->formatNumber($row['netto']),
                    ));
                $this->pdf->checkPageBreak(20);
            }
            $this->pdf->Output();
        }
        
        public function RekapReturPembelianPerDokumen($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select *,(nominal-ppn) as netto
                    from
                    (select *,(select nominal*g.taxvalue/100 from tax g where g.taxid=z.taxid) as ppn
                    from
                    (select a.grreturno,a.grreturdate,c.fullname as supplier,b.taxid,
                    (
                            select sum(d.qty*f.netprice) 
                            from grreturdetail d
                            join podetail f on f.podetailid=d.podetailid
                            where d.grreturid=a.grreturid
                    ) as nominal
                    from grretur a
                    join poheader b on b.poheaderid=a.poheaderid
                    join addressbook c on c.addressbookid=b.addressbookid
                    where a.recordstatus = 3 and b.companyid = ".$companyid." and 
                    b.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z) zz";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            
            $this->pdf->title='Rekap Retur Pembelian Per Dokumen';
            $this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');
            $i=0;$nominal=0;$ppn=0;$total=0;

            $this->pdf->sety($this->pdf->gety()+10);
            $this->pdf->setFont('Arial','B',10);
            $this->pdf->colalign = array('C','C','C','C','C','C','C');
            $this->pdf->setwidths(array(10,35,30,40,30,10,30));
            $this->pdf->colheader = array('No','Dokumen','Tanggal','Supplier','Nominal','PPN','Total');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L','R','R','R','R');
            
            foreach($dataReader as $row)
            {
                $i+=1;
                $this->pdf->SetFont('Arial','',8);
                $this->pdf->row(array(
                        $i,$row['grreturno'],
                        $row['grreturdate'],
                        $row['supplier'],
                        Yii::app()->format->formatNumber($row['nominal']),
                        Yii::app()->format->formatNumber($row['ppn']),
                        Yii::app()->format->formatNumber($row['netto']),
                ));
                $nominal += $row['nominal'];
                $ppn += $row['ppn'];
                $total += $row['netto'];
                $this->pdf->checkPageBreak(20);
            }
                $this->pdf->row(array(
                        '','Grand Total','','',
                        Yii::app()->format->formatNumber($nominal),
                        Yii::app()->format->formatNumber($ppn),
                        Yii::app()->format->formatNumber($total),
                ));
            $this->pdf->Output();
        }
        
        public function RekapReturPembelianPerSupplier($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select *,(nominal-ppn) as netto
                    from
                    (select *,(select nominal*f.taxvalue/100 from tax f where f.taxid=z.taxid) as ppn
                    from
                    (select c.fullname as supplier,a.taxid,
                    (
                            select sum(d.qty*e.netprice) as nominal 
                            from grreturdetail d
                            join podetail e on e.podetailid=d.podetailid
                            where d.grreturid = b.grreturid
                    ) as nominal
                    from poheader a
                    join grretur b on b.poheaderid=a.poheaderid
                    join addressbook c on c.addressbookid=a.addressbookid
                    where b.recordstatus=3 and a.companyid = ".$companyid." and 
                    a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'
                    group by c.fullname) z) zz";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            
            $this->pdf->title='Rekap Retur Pembelian Per Supplier';
            $this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');
            $i=0;$nominal=0;$ppn=0;$total=0;

            $this->pdf->sety($this->pdf->gety()+10);
            $this->pdf->setFont('Arial','B',10);
            $this->pdf->colalign = array('C','C','C','C','C');
            $this->pdf->setwidths(array(10,60,40,30,40));
            $this->pdf->colheader = array('No','Supplier','Nominal','PPN','Total');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','R','R','R');
            
            foreach($dataReader as $row)
            {
                $i+=1;
                $this->pdf->SetFont('Arial','',10);
                $this->pdf->row(array(
                        $i,$row['supplier'],
                        Yii::app()->format->formatNumber($row['nominal']),
                        Yii::app()->format->formatNumber($row['ppn']),
                        Yii::app()->format->formatNumber($row['netto']),
                ));
                $nominal += $row['nominal'];
                $total += $row['netto'];
                $this->pdf->checkPageBreak(20);
            }
                $this->pdf->row(array(
                        '','Grand Total',
                        Yii::app()->format->formatNumber($nominal),
                        Yii::app()->format->formatNumber($ppn),
                        Yii::app()->format->formatNumber($total),
                ));
            $this->pdf->Output();
        }
        
        public function RekapReturPembelianPerBarang($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select a.materialgroupid,a.description from materialgroup a where a.materialgroupid in
                    (select b.materialgroupid from productplant b where b.productid in
                    (select c.productid from grreturdetail c 
                    left join podetail d on d.podetailid=c.podetailid
                    left join poheader e on e.poheaderid=d.poheaderid
                    left join grretur f on f.grreturid=c.grreturid
                    where e.companyid = ".$companyid." and f.grreturdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'))";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            
            $this->pdf->title='Rekap Retur Pembelian Per Barang';
            $this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');
			
            $this->pdf->sety($this->pdf->gety()+5);
            
            foreach($dataReader as $row)
            {
                $this->pdf->SetFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->gety()+10,'Divisi');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row['description']);
                $sql1 = "select *,(nominal-ppn) as netto
                        from
                        (select *,(select nominal*i.taxvalue/100 from tax i where i.taxid=z.taxid) as ppn
                        from
                        (select b.productname,a.qty,(a.qty*c.netprice) as nominal,d.taxid,h.description
                        from grreturdetail a
                        join product b on b.productid=a.productid
                        join podetail c on c.podetailid=a.podetailid
                        join poheader d on d.poheaderid=c.poheaderid
                        join unitofmeasure e on e.unitofmeasureid=a.uomid
                        join grretur f on f.grreturid=a.grreturid
                        join productplant g on g.productid=a.productid
                        join materialgroup h on h.materialgroupid=g.materialgroupid
                        group by h.description) z) zz";
                $command1=$this->connection->createCommand($sql1);
                $dataReader1=$command1->queryAll();
                $totalqty=0;$nominal=0;$ppn=0;$total=0;$i=0;
                
                $this->pdf->sety($this->pdf->gety()+15);
                $this->pdf->setFont('Arial','B',8);
                $this->pdf->colalign = array('C','C','C','C','C','C');
                $this->pdf->setwidths(array(10,80,10,40,10,40));
                $this->pdf->colheader = array('No','Nama Barang','Qty','Nominal','PPN','Total');
                $this->pdf->RowHeader();
                $this->pdf->coldetailalign = array('L','L','R','R','R','R');
                $this->pdf->setFont('Arial','',8);
                
                foreach($dataReader1 as $row1)
                {
                    $i+=1;
                        $this->pdf->row(array(
                                $i,$row1['productname'],
                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['qty']),
                                Yii::app()->format->formatNumber($row1['nominal']),
                                Yii::app()->format->formatNumber($row1['ppn']),
                                Yii::app()->format->formatNumber($row1['netto']),
                        ));
                        $totalqty += $row1['qty'];
                        $ppn += $row1['ppn'];
                        $nominal += $row1['nominal'];
                        $total += $row1['netto'];
                }
                $this->pdf->row(array(
                        '','Total : '.$row['description'],
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty),
                        Yii::app()->format->formatNumber($nominal),
                        Yii::app()->format->formatNumber($ppn),
                        Yii::app()->format->formatNumber($total),
                ));
                $this->pdf->checkPageBreak(20);
            }
            $this->pdf->Output();
        }
        
        public function RekapReturPembelianPerArea($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select distinct a.cityid,a.cityname
                    from city a
                    join company b on b.cityid = a.cityid
                    join poheader c on c.companyid = b.companyid
                    join grretur d on d.poheaderid = c.poheaderid
                    where d.recordstatus = 3 and b.companyid = ".$companyid." and c.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."'";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            
            $this->pdf->title='Rekap Retur Pembelian Per Lokasi';
            $this->pdf->subtitle = 'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');

            $this->pdf->sety($this->pdf->gety()+5);
            
            foreach($dataReader as $row)
            {
                $this->pdf->SetFont('Arial','',10);
                $this->pdf->text(10,$this->pdf->gety()+10,'Lokasi');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row['cityname']);
                $sql1 = "select *,(nominal-ppn) as netto
                        from
                        (select *,(select nominal*g.taxvalue/100 from tax g where g.taxid=z.taxid) as ppn
                        from
                        (select b.productname,a.qty,c.netprice*a.qty as nominal,f.cityname,d.taxid 
                        from grreturdetail a
                        join product b on b.productid=a.productid
                        join podetail c on c.podetailid=a.podetailid
                        join poheader d on d.poheaderid=c.poheaderid
                        join company e on e.companyid=d.companyid
                        join city f on f.cityid=e.cityid
                        where f.cityid = '".$row['cityid']."'
                        group by f.cityname) z) zz";
                $command1=$this->connection->createCommand($sql1);
                $dataReader1=$command1->queryAll();
                $i=0;$totalqty=0;$ppn=0;$nominal=0;$total=0;
                
                $this->pdf->sety($this->pdf->gety()+15);
                $this->pdf->setFont('Arial','B',8);
                $this->pdf->colalign = array('C','C','C','C','C','C');
                $this->pdf->setwidths(array(10,70,20,30,20,40));
                $this->pdf->colheader = array('No','Nama Barang','QTY','Nominal','PPN','Total');
                $this->pdf->RowHeader();
                $this->pdf->coldetailalign = array('L','L','R','R','R','R');
                $this->pdf->setFont('Arial','',8);
                
                foreach($dataReader1 as $row1)
                {
                    $i+=1;
                    $this->pdf->row(array(
                            $i,$row1['productname'],
                            Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['qty']),
                            Yii::app()->format->formatNumber($row1['nominal']),
                            Yii::app()->format->formatNumber($row1['ppn']),
                            Yii::app()->format->formatNumber($row1['netto']),
                    ));
                    $totalqty += $row1['qty'];
                    $nominal += $row1['nominal'];
                    $ppn += $row1['ppn'];
                    $total += $row1['netto'];
                }
                $this->pdf->row(array(
                        '','Grand Total',
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty),
                            Yii::app()->format->formatNumber($nominal),
                            Yii::app()->format->formatNumber($ppn),
                            Yii::app()->format->formatNumber($total),
                ));
                $this->pdf->checkPageBreak(20);
            }
            $this->pdf->Output();
        }
        
        public function RincianSelisihPembelianReturPerDokumen($companyid,$startdate,$enddate)
        {
                parent::actionDownload();
                $sql = "select *,(nominal-ppn) as netto
                        from
                        (select *,(select nominal*c.taxvalue/100 from tax c where c.taxid=zz.taxid) as ppn
                        from 
                        (select a.poheaderid,a.taxid,a.pono,a.docdate,d.fullname,e.paydays,(select sum(b.poqty*b.netprice) 
                        from podetail b where b.poheaderid=a.poheaderid) as nominal
                        from poheader a
                        inner join addressbook d on d.addressbookid=a.addressbookid
                        inner join paymentmethod e on e.paymentmethodid=a.paymentmethodid
                        where a.pono is not null and a.companyid = ".$companyid." and 
			a.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
			and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and 
                        a.poheaderid in (select x.poheaderid from grretur x where x.recordstatus = 3)) zz)zzz";

			$command=$this->connection->createCommand($sql);
			$dataReader=$command->queryAll();
                        
                        foreach($dataReader as $row)
                        {
                            $this->pdf->companyid = $companyid;
                        }
                        $this->pdf->title='Rincian (Pembelian-Retur) Per Dokumen';
			$this->pdf->text(10,$this->pdf->gety()+10,'Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
			$this->pdf->AddPage('P');
                        
                        $this->pdf->sety($this->pdf->gety()+5);
                        
                        foreach($dataReader as $row)
                        {
                                $this->pdf->SetFont('Arial','',10);
				$this->pdf->text(10,$this->pdf->gety()+10,'No Bukti');$this->pdf->text(30,$this->pdf->gety()+10,': '.$row['pono']);
				$this->pdf->text(10,$this->pdf->gety()+15,'Supplier');$this->pdf->text(30,$this->pdf->gety()+15,': '.$row['fullname']);
				$this->pdf->text(150,$this->pdf->gety()+10,'Tgl Bukti');$this->pdf->text(180,$this->pdf->gety()+10,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
				$this->pdf->text(150,$this->pdf->gety()+15,'T.O.P');$this->pdf->text(180,$this->pdf->gety()+15,': '.$row['paydays'].' HARI');
                                $sql1 = "select c.productname, a.poqty,d.uomcode,a.netprice,a.poqty * a.netprice as jumlah,a.itemtext
					from podetail a
					inner join product c on c.productid = a.productid
					inner join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
					where a.poheaderid = ".$row['poheaderid'];
                                $command1=$this->connection->createCommand($sql1);
				$dataReader1=$command1->queryAll();
				$total = 0;$i=0;$totalqty=0;$grandtotalqty=0;$grandtotalnetto=0;
				$this->pdf->sety($this->pdf->gety()+20);
				$this->pdf->setFont('Arial','B',8);
				$this->pdf->colalign = array('C','C','C','C','C','C','C');
				$this->pdf->setwidths(array(10,50,10,15,30,30,43));
				$this->pdf->colheader = array('No','Nama Barang','Qty','Satuan','Harga','Jumlah','Keterangan');
				$this->pdf->RowHeader();
				$this->pdf->coldetailalign = array('L','L','R','C','R','R','R');
				$this->pdf->setFont('Arial','',8);
                                foreach($dataReader1 as $row1)
                                {
                                        $i+=1;
					$this->pdf->row(array(
						$i,$row1['productname'],
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['poqty']),$row1['uomcode'],
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['netprice']),
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row1['jumlah']),
                                                $row1['itemtext']
					));
					$totalqty += $row1['poqty'];
					$total += $row1['jumlah'];
                                }
                                $this->pdf->row(array(
						'','KETERANGAN',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty),'',
						'','NOMINAL',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$total),
					));
                                $this->pdf->row(array(
						'','',
						'',
						'','',
						'PPN',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['ppn']),
					));
                                $this->pdf->row(array(
						'','',
						'','',
						'',
						'NETTO',
						Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['netto']),
					));
                                $sql2 = "select *,(nominal-ppn) as netto
                                        from 
                                        (select *,
                                        (select nominal*i.taxvalue/100 from tax i where i.taxid=z.taxid) as ppn
                                        from 
                                        (select a.grreturid,a.grreturno,g.fullname as supplier,a.grreturdate,h.paycode,f.taxid,
                                        (
                                            select sum(b.netprice*c.qty) 
                                            from podetail b
                                            where b.podetailid=c.podetailid 
                                            and b.productid=c.productid
                                        ) as nominal
                                        from grretur a
                                        join grreturdetail c on c.grreturid=a.grreturid
                                        join product d on d.productid=c.productid
                                        join podetail e on e.podetailid=c.podetailid
                                        join poheader f on f.poheaderid=e.poheaderid
                                        join addressbook g on g.addressbookid=f.addressbookid
                                        join paymentmethod h on h.paymentmethodid=f.paymentmethodid
                                        where a.poheaderid = '".$row['poheaderid']."' and a.recordstatus = 3 and f.companyid = ".$companyid." and 
                                        f.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                                        and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z) zz";
                                $command2=$this->connection->createCommand($sql2);
                                $dataReader2=$command2->queryAll();
                                foreach($dataReader2 as $row2)
                                {
                                    $this->pdf->SetFont('Arial','',10);
                                    $this->pdf->text(10,$this->pdf->gety()+10,'Dokumen Retur');$this->pdf->text(40,$this->pdf->gety()+10,': '.$row2['grreturno']);
                                    $this->pdf->text(10,$this->pdf->gety()+15,'Supplier');$this->pdf->text(40,$this->pdf->gety()+15,': '.$row2['supplier']);
                                    $this->pdf->text(130,$this->pdf->gety()+10,'Tanggal');$this->pdf->text(160,$this->pdf->gety()+10,': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row2['grreturdate'])));
                                    $this->pdf->text(130,$this->pdf->gety()+15,'T.O.P');$this->pdf->text(160,$this->pdf->gety()+15,': '.$row2['paycode'].' HARI');                
                                    $sql3 = "select b.productname,a.qty,c.netprice,(a.qty*c.netprice) as jumlah,a.itemnote
                                            from grreturdetail a
                                            join product b on b.productid=a.productid
                                            join podetail c on c.podetailid=a.podetailid
                                            join unitofmeasure d on d.unitofmeasureid=a.uomid
                                            where a.grreturid = ".$row2['grreturid'];
                                    $command3=$this->connection->createCommand($sql3);
                                    $dataReader3=$command3->queryAll();
                                    $ii=0;$totalqty1=0;
                                    $this->pdf->sety($this->pdf->gety()+25);
                                    $this->pdf->setFont('Arial','B',8);
                                    $this->pdf->colalign = array('C','C','C','C','C','C');
                                    $this->pdf->setwidths(array(10,70,10,20,30,50));
                                    $this->pdf->colheader = array('No','Nama Barang','Qty','Harga','Jumlah','Keterangan');
                                    $this->pdf->RowHeader();
                                    $this->pdf->coldetailalign = array('L','L','R','R','R','R');
                                    $this->pdf->setFont('Arial','',8);

                                    foreach($dataReader3 as $row3)
                                    {
                                        $ii+=1;
                                        $this->pdf->row(array(
                                                $ii,$row3['productname'],
                                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row3['qty']),
                                                Yii::app()->format->formatNumber($row3['netprice']),
                                                Yii::app()->format->formatNumber($row3['jumlah']),
                                            $row3['itemnote'],
                                        ));
                                        $totalqty1 += $row3['qty'];
                                    }
                                    $this->pdf->row(array(
                                                '','Keterangan : '.$row3['itemnote'],
                                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalqty1),
                                                '','NOMINAL',
                                                Yii::app()->format->formatNumber($row2['nominal']),
                                        ));
                                    $this->pdf->row(array(
                                                '',
                                                '',
                                                '','',
                                                'PPN',
                                                Yii::app()->format->formatNumber($row2['ppn']),
                                        ));
                                    $this->pdf->row(array(
                                                '',
                                                '',
                                                '','',
                                                'NETTO',
                                                Yii::app()->format->formatNumber($row2['netto']),
                                        ));
                                }
                                $grandtotalqty= $totalqty-$totalqty1;
                                $grandtotalnetto= $row['netto']-$row2['netto'];
                                $this->pdf->row(array(
                                                '',
                                                'GRAND TOTAL',                                                
                                                Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$grandtotalqty),'',
                                                '',Yii::app()->format->formatNumber($grandtotalnetto),
                                        ));
				$this->pdf->checkPageBreak(20);
                        }
                        $this->pdf->Output();
        }
        
        public function RekapSelisihPembelianReturPerSupplier($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select fullname,(nominal-nominalretur) as nom,(ppn-ppnretur) as pajak,
                    (netto-nettoretur) as total
                    from
                    (select fullname,sum(nominal) as nominal,sum(nominalretur) as nominalretur,
                    sum(ppn) as ppn,sum(ppnretur) as ppnretur,sum(netto) as netto,
                    sum(nettoretur) as nettoretur
                    from
                    (select *,(nominal-ppn) as netto,(nominalretur-ppn) as nettoretur
                    from
                    (select *,(select sum(nominal*c.taxvalue/100) from tax c where c.taxid=zz.taxid) as ppn,
                    (select sum(nominalretur*c.taxvalue/100) from tax c where c.taxid=zz.taxid) as ppnretur
                    from 
                    (select a.poheaderid,a.taxid,a.addressbookid,a.pono,a.docdate,d.fullname,(select sum(b.poqty*b.netprice) 
                    from podetail b where b.poheaderid=a.poheaderid) as nominal,
                    (
                            select sum(f.qty*g.netprice)
                            from grreturdetail f
                            join podetail g on g.podetailid=f.podetailid
                            where f.grreturid = e.grreturid
                    ) as nominalretur
                    from poheader a
                    inner join addressbook d on d.addressbookid=a.addressbookid
                    left join grretur e on e.poheaderid = a.poheaderid
                    where a.pono is not null and a.companyid = ".$companyid." and 
                    a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' and a.poheaderid in
                    (select x.poheaderid from grretur x where x.recordstatus = 3)) zz)zzz)xx)xxx
                    group by fullname";
            $command=$this->connection->createCommand($sql);
            $dataReader=$command->queryAll();
            $totalppn = 0;$totalnominal=0;$total=0;
            
            foreach($dataReader as $row)
            {
                $this->pdf->companyid = $companyid;
            }
            $this->pdf->title='Rekap (Pembelian-Retur) Per Supplier';
            $this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
            $this->pdf->AddPage('P');

            $this->pdf->sety($this->pdf->gety()+10);
            $this->pdf->setFont('Arial','B',8);
            $this->pdf->colalign = array('C','C','C','C','C','C','C','C');
            $this->pdf->setwidths(array(10,70,50,20,40));
            $this->pdf->colheader = array('No','Nama Supplier','Nominal','PPN','Total');
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array('L','L','L','L','R','R','R','L');		
            $i=0;
            
            foreach($dataReader as $row)
            {
                $i+=1;
                $this->pdf->setFont('Arial','',7);
                $this->pdf->row(array(
                        $i,
                        $row['fullname'],
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['nom']),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['pajak']),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$row['total']),
                ));
                $totalnominal += $row['nom'];
                $totalppn += $row['pajak'];
                $total += $row['total'];
            }
                $this->pdf->row(array(
                        '','GRAND TOTAL',
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalnominal),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$totalppn),
                        Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"],$total),
                ));
                $this->pdf->checkPageBreak(20);
            $this->pdf->Output();
        }
        
        public function RekapSelisihPembelianReturPerDokumen($companyid,$startdate,$enddate)
        {
            parent::actionDownload();
            $sql = "select *,(nominal-ppn) as netto
                    from
                    (select *,(select nominal*c.taxvalue/100 from tax c where c.taxid=zz.taxid) as ppn
                    from 
                    (select a.poheaderid,a.taxid,a.pono,a.docdate,d.fullname,(select sum(b.poqty*b.netprice) 
                    from podetail b where b.poheaderid=a.poheaderid) as nominal
                    from poheader a
                    inner join addressbook d on d.addressbookid=a.addressbookid
                    where a.pono is not null and a.companyid = ".$companyid." and 
                    a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
                    and '".date(Yii::app()->params['datetodb'