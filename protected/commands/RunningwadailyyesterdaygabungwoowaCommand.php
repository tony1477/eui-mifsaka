<?php
class RunningwadailyyesterdaygabungwoowaCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			$per =10;
			$sqldatefirstmonth = "SELECT DATE_ADD(LAST_DAY(DATE_ADD(DATE_ADD(DATE(NOW()),INTERVAL -1 DAY), INTERVAL -1 MONTH)),INTERVAL 1 DAY)";
			$startdate=$connection->createCommand($sqldatefirstmonth)->queryScalar();
			//sqldate today
			//$sqldate = "SELECT DATE(NOW())";
			//sqldate yesterday
			$sqldate = "SELECT DATE_ADD(DATE(NOW()),INTERVAL -1 DAY)";
			$enddate=$connection->createCommand($sqldate)->queryScalar();
            
            $isdisplay = "0";
            if(isset($isdisplay) && $isdisplay!='')
            {
                $isdisplay1 = " and c.isdisplay = ".$isdisplay." ";
                $isdisplay2 = " and h.isdisplay = ".$isdisplay." ";
                $isdisplay3 = " and x1.isdisplay = ".$isdisplay." ";
                if ($isdisplay == "1") {$display = " JUST DISPLAY";}
                if ($isdisplay == "0") {$display = " WITHOUT DISPLAY";}
            }
            else
            {
                $isdisplay1 = "";
                $isdisplay2 = "";
                $isdisplay3 = "";
                $display = "";
            }
			
			//invite link whatsapp group
			$groupexcellent = 'E9eArSN1JVlJSghuZfqCBW';
			$grouptop = 'CaddD6Qsh4x016NNBSN9Oq';
			$groupaka = '';
			$groupks = '';
			$groupamin = '';
			$groupakm = '';
			$groupajm = '';
			$groupami = '';
			$groupagem = '';
			$groupakp = '';
			
			$sql1 = "select group_concat(a.companyid)
							from company a
							where a.recordstatus = 1
			";
			$companyid=$connection->createCommand($sql1)->queryScalar();
			
			$pesan="";
			//$companyid = $row1['companyid'];
			$title = "DAILY MONITORING REPORT";
			$datetime = new DateTime(date($enddate));
			$subtitle = "Dari Tgl :  ".date(Yii::app()->params['dateviewfromdb'], strtotime($startdate))."  s/d  ".date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
			$companyname = "LAPORAN GABUNGAN AKA Group".$display;
			
$msg  = "*".$title."*\n\n*".$companyname."*\n".$subtitle."\n\n*1. QTY (PENJUALAN - RETUR) vs QTY PRODUKSI*\nKeterangan     Hari Ini / Kum.   VS   Hari Ini / Kum.\n";
$pesan  .= $msg;
			
			$sql = "select a.materialgroupid,UC_Words(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(a.description,' - SBED',''),' - BUSA',''),' - BS',''),' - PLAS',''),' - PNL',''),' - SF',''),' - BU',''),'MATRAS','M.'),'DIVAN','D.'),'BED SORONG','BS.'),'SANDARAN','S.'),'NON KANGAROO & KANGAROO LOKAL','NON K. & K.LOKAL')) as description
							from materialgroup a
							where a.recordstatus = 1 and a.isfg = 1
							order by a.nourut asc";
			$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
			$i=0;
			foreach($dataReader as $row)
			{
				$row7=0;$row8=0;
				$sql1 = "select a.companyid
								from company a
								where a.recordstatus = 1
				";
				$dataReader1=$connection->createCommand($sql1)->queryAll();
				
				foreach($dataReader1 as $row1)
				{
					$sql3 = "select ifnull(sum(qtyoutput),0) as qtyoutput
									from productoutputfg a
									join productoutput b on b.productoutputid=a.productoutputid
									join productplant c on c.productid=a.productid and c.slocid=a.slocid and c.unitofissue=a.uomid
									where b.companyid = {$row1['companyid']} and b.recordstatus = 3 and b.productoutputdate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' and c.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']})
					";
					$row3 = Yii::app()->db->createCommand($sql3)->queryScalar();
					
					$sql4 = "select ifnull(sum(qtyoutput),0) as kumqtyoutput
									from productoutputfg a
									join productoutput b on b.productoutputid=a.productoutputid
									join productplant c on c.productid=a.productid and c.slocid=a.slocid and c.unitofissue=a.uomid
									where b.companyid = {$row1['companyid']} and b.recordstatus = 3 and b.productoutputdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' and c.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']})
					";
					$row4 = Yii::app()->db->createCommand($sql4)->queryScalar();
					
					$cmd1 = "select b.addressbookid
									from addressbook b
									where b.fullname = (select replace(a.companyname,'TRADING','PRODUKSI')
									from company a
									where a.companyid = {$row1['companyid']} and a.companyname like '%TRADING')
					";
					$addressbook = Yii::app()->db->createCommand($cmd1)->queryScalar();
					
					$cmd = "select ifnull(count(1),0)
										from company a
										where a.companyid = {$row1['companyid']} and a.companyname like '%trading'
					";
					$company = Yii::app()->db->createCommand($cmd)->queryScalar();
					
					if ($company == 0)
					{
						$row7 = $row7 + $row3;
						$row8 = $row8 + $row4;
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
										where c.recordstatus=3 and c.companyid = {$row1['companyid']} and f.addressbookid = {$addressbook} and a.transdate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' and b.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']})
						";
						$row5 = Yii::app()->db->createCommand($sql5)->queryScalar();

						$sql6 = "select ifnull(sum(a.qty),0) as kumgrqty
										from productstockdet a
										join productplant b on b.productid=a.productid and b.slocid=a.slocid
										join grheader c on c.grno=a.referenceno
										join sloc d on d.slocid=a.slocid
										join plant e on e.plantid=d.plantid and e.companyid=c.companyid
										join poheader f on f.poheaderid=c.poheaderid
										where c.recordstatus=3 and c.companyid = {$row1['companyid']} and f.addressbookid = {$addressbook} and a.transdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' and b.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']})
						";
						$row6 = Yii::app()->db->createCommand($sql6)->queryScalar();
						
						$row7 = $row7 + $row5;
						$row8 = $row8 + $row6;
					}
				}
				
				$sql1 = "select ifnull(sum(qty),0) as qty
								from (select sum(qty) as qty,sum(nett) as netto
								from (select distinct b3.gidetailid,b3.qty,
								(select getamountdiscso(c1.soheaderid,c1.sodetailid,c0.qty)
								from gidetail c0 
								join sodetail c1 on c1.sodetailid = c0.sodetailid
								where c0.giheaderid = b1.giheaderid and c0.productid = b3.productid and c0.gidetailid=b3.gidetailid) as nett
								from invoice b0 
								join giheader b1 on b1.giheaderid = b0.giheaderid
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=b1.soheaderid'):'')."
								join sodetail b2 on b2.soheaderid = b1.soheaderid
								join gidetail b3 on b3.giheaderid = b1.giheaderid
								join productplant b4 on b4.productid = b3.productid and b4.slocid=b2.slocid
								where b0.recordstatus = 3 and b0.invoiceno is not null and b1.companyid in ({$companyid}) and b0.invoiceno is not null and b4.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and b0.invoicedate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3.") z
								union
								select sum(qty) as qty,sum(nett) as netto from 
								(select distinct d0.notagirproid,(-1*d0.qty) as qty,(-1*d0.qty*d0.price) as nett
								from notagirpro d0
								join notagir d1 on d1.notagirid=d0.notagirid
								join gireturdetail d2 on d2.gireturdetailid=d0.gireturdetailid
								join giretur d3 on d3.gireturid=d1.gireturid
								join gidetail d4 on d4.gidetailid=d2.gidetailid
								join giheader d5 on d5.giheaderid=d3.giheaderid
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=d5.soheaderid'):'')."
								join sodetail d6 on d6.sodetailid=d4.sodetailid
								join productplant d7 on d7.productid=d0.productid and d7.slocid=d0.slocid
								where d5.companyid in ({$companyid}) and d1.recordstatus = 3 and d7.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and d3.gireturdate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3.") z) zz
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
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=b1.soheaderid'):'')."
								join sodetail b2 on b2.soheaderid = b1.soheaderid
								join gidetail b3 on b3.giheaderid = b1.giheaderid
								join productplant b4 on b4.productid = b3.productid and b4.slocid=b2.slocid
								where b0.recordstatus = 3 and b0.invoiceno is not null and b1.companyid in ({$companyid}) and b0.invoiceno is not null and b4.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and b0.invoicedate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3.") z
								union
								select sum(qty) as qty,sum(nett) as netto from 
								(select distinct d0.notagirproid,(-1*d0.qty) as qty,(-1*d0.qty*d0.price) as nett
								from notagirpro d0
								join notagir d1 on d1.notagirid=d0.notagirid
								join gireturdetail d2 on d2.gireturdetailid=d0.gireturdetailid
								join giretur d3 on d3.gireturid=d1.gireturid
								join gidetail d4 on d4.gidetailid=d2.gidetailid
								join giheader d5 on d5.giheaderid=d3.giheaderid
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=d5.soheaderid'):'')."
								join sodetail d6 on d6.sodetailid=d4.sodetailid
								join productplant d7 on d7.productid=d0.productid and d7.slocid=d0.slocid
								where d5.companyid in ({$companyid}) and d1.recordstatus = 3 and d7.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and d3.gireturdate between 
								'". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3.") z) zz
				";
				$row2 = Yii::app()->db->createCommand($sql2)->queryScalar();
				
$msg  = $row['description']." ".Yii::app()->format->formatNumberWODecimal($row1,'')." / ".Yii::app()->format->formatNumberWODecimal($row2,'')." VS ".Yii::app()->format->formatNumberWODecimal($row7,'')." / ".Yii::app()->format->formatNumberWODecimal($row8,'')."\n";
$pesan  .= $msg;
			}

$msg = "\n*2. NILAI PENJUALAN - RETUR*\nKeterangan Hari Ini VS Kum.\n";
$pesan .= $msg;
						
			$totalnilai=0;$totalkumnilai=0;
						
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
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=b1.soheaderid'):'')."
								join sodetail b2 on b2.soheaderid = b1.soheaderid
								join gidetail b3 on b3.giheaderid = b1.giheaderid
								join productplant b4 on b4.productid = b3.productid and b4.slocid=b2.slocid
								where b0.recordstatus = 3 and b0.invoiceno is not null and b1.companyid in ({$companyid}) and b0.invoiceno is not null and b4.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and b0.invoicedate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3.") z
								union
								select sum(qty) as qty,sum(nett) as netto from 
								(select distinct d0.notagirproid,(-1*d0.qty) as qty,(-1*d0.qty*d0.price) as nett
								from notagirpro d0
								join notagir d1 on d1.notagirid=d0.notagirid
								join gireturdetail d2 on d2.gireturdetailid=d0.gireturdetailid
								join giretur d3 on d3.gireturid=d1.gireturid
								join gidetail d4 on d4.gidetailid=d2.gidetailid
								join giheader d5 on d5.giheaderid=d3.giheaderid
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=d5.soheaderid'):'')."
								join sodetail d6 on d6.sodetailid=d4.sodetailid
								join productplant d7 on d7.productid=d0.productid and d7.slocid=d0.slocid
								where d5.companyid in ({$companyid}) and d1.recordstatus = 3 and d7.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and d3.gireturdate = '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3.") z) zz
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
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=b1.soheaderid'):'')."
								join sodetail b2 on b2.soheaderid = b1.soheaderid
								join gidetail b3 on b3.giheaderid = b1.giheaderid
								join productplant b4 on b4.productid = b3.productid and b4.slocid=b2.slocid
								where b0.recordstatus = 3 and b0.invoiceno is not null and b1.companyid in ({$companyid}) and b0.invoiceno is not null and b4.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and b0.invoicedate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3.") z
								union
								select sum(qty) as qty,sum(nett) as netto from 
								(select distinct d0.notagirproid,(-1*d0.qty) as qty,(-1*d0.qty*d0.price) as nett
								from notagirpro d0
								join notagir d1 on d1.notagirid=d0.notagirid
								join gireturdetail d2 on d2.gireturdetailid=d0.gireturdetailid
								join giretur d3 on d3.gireturid=d1.gireturid
								join gidetail d4 on d4.gidetailid=d2.gidetailid
								join giheader d5 on d5.giheaderid=d3.giheaderid
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=d5.soheaderid'):'')."
								join sodetail d6 on d6.sodetailid=d4.sodetailid
								join productplant d7 on d7.productid=d0.productid and d7.slocid=d0.slocid
								where d5.companyid in ({$companyid}) and d1.recordstatus = 3 and d7.materialgroupid in (select o.materialgroupid from materialgroup o where o.parentmatgroupid = {$row['materialgroupid']}) and d3.gireturdate between 
								'". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3.") z) zz
				";
				$row2 = Yii::app()->db->createCommand($sql2)->queryScalar();

$msg = $row['description']." ".Yii::app()->numberFormatter->formatCurrency($row1/$per,"Rp.")." VS ".Yii::app()->numberFormatter->formatCurrency($row2/$per,"Rp.")."\n";
$pesan .= $msg;
								
				$totalnilai += $row1/$per;
				$totalkumnilai += $row2/$per;
			}
						
$msg = "Total Penjualan - Retur >> ".Yii::app()->numberFormatter->formatCurrency($totalnilai,"Rp")."  VS  ".Yii::app()->numberFormatter->formatCurrency($totalkumnilai,"Rp")."\n";
$pesan .= $msg;
			
$msg = "\n*3. PENCAPAIAN SALES*\n";
$pesan .= $msg;
				
			$month = date('m',strtotime($startdate));
			$year = date('Y',strtotime($startdate));
			$day = $year.'-'.$month.'-01';
		
			$sqlplant = "select plantid,plantcode,description
				from plant where recordstatus = 1 and companyid in ({$companyid})" ;
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
										where zzd.recordstatus = a.recordstatus and zzb.giheaderid = b.giheaderid and zzb.productid = i.productid and zzb.gidetailid=ss.gidetailid
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
										c.companyid in ({$companyid}) and a.invoiceno is not null and 
										a.invoiceno is not null and a.invoicedate between '{$day}' and
										'".date(Yii::app()->params['datetodb'],strtotime($enddate))."' ".$isdisplay1." and l.plantid = {$rows['plantid']} )z group by fullname
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
										where h.companyid in ({$companyid}) and b.recordstatus = 3 and d.gireturdate between '{$day}' and
										'".date(Yii::app()->params['datetodb'],strtotime($enddate))."' ".$isdisplay2." and n.plantid = {$rows['plantid']}
										group by notagirno
										order by notagirno,notagirproid
										)z group by fullname) zz order by fullname
				";
				$penc = Yii::app()->db->createCommand($sqlpenc)->queryRow();
							
$msg = "Pencapaian ".$rows['plantcode']." : ".Yii::app()->numberFormatter->formatCurrency($penc['netto2']/$per,"Rp.")."/".Yii::app()->numberFormatter->formatCurrency($penc['netto']/$per,"Rp.")."\n";
$pesan .= $msg;
		}
				
$msg = "\n*4. TAGIHAN HARI INI / KUMULATIF*\n";
$pesan .= $msg;
			
			$sql10 = "select sum(a.cashamount + a.bankamount)
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
							".(($isdisplay !='') ? joinTable('invoice','c','c.invoiceid=a.invoiceid'):'')."
							".(($isdisplay !='') ? joinTable('giheader','d','d.giheaderid=c.giheaderid'):'')."
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=d.soheaderid'):'')."
								where b.recordstatus=3 and b.companyid in ({$companyid}) and b.docdate = '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3."
			";
			$row10=Yii::app()->db->createCommand($sql10)->queryScalar();

			$sql11 = "select sum(a.cashamount + a.bankamount)
								from cutarinv a
								join cutar b on b.cutarid=a.cutarid
							".(($isdisplay !='') ? joinTable('invoice','c','c.invoiceid=a.invoiceid'):'')."
							".(($isdisplay !='') ? joinTable('giheader','d','d.giheaderid=c.giheaderid'):'')."
							".($isdisplay !='' ? joinTable('soheader','x1','x1.soheaderid=d.soheaderid'):'')."
								where b.recordstatus=3 and b.companyid in ({$companyid}) and b.docdate between '".date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'], strtotime($enddate))."' ".$isdisplay3."
			";
			$row11=Yii::app()->db->createCommand($sql11)->queryScalar();

$msg = Yii::app()->numberFormatter->formatCurrency($row10/$per,"Rp.")." / ".Yii::app()->numberFormatter->formatCurrency($row11/$per,"Rp.")."\n";
$pesan .= $msg;
				
$msg = "\n*5. PERSEDIAAN*";
$pesan .= $msg;				
			
			$totfg=0;$totwip=0;$totrw=0;
			$sql1 = "select a.companyid
							from company a
							where a.recordstatus = 1
			";
			$dataReader1=$connection->createCommand($sql1)->queryAll();
			
			foreach($dataReader1 as $row1)
			{
				$connection = Yii::app()->db;
				$sqlfg = "call hitungsaldodate(:vfg,:vdate,:vcompanyid,@vsaldoakhir)";
				$command1 = $connection->createCommand($sqlfg);
				$command1->bindvalue(':vfg','11050101',PDO::PARAM_STR);
				$command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($enddate)),PDO::PARAM_STR);
				$command1->bindvalue(':vcompanyid',$row1['companyid'],PDO::PARAM_STR);
				$command1->execute();

				$qfg = "select @vsaldoakhir as saldoakhir";
				$tmt1 = Yii::app()->db->createCommand($qfg);
				$tmt1->execute();
				$fg = $tmt1->queryRow();

				$sqlwip = "call hitungsaldodate(:vwip,:vdate,:vcompanyid,@vsaldoakhir)";
				$command2 = $connection->createCommand($sqlwip);
				$command2->bindvalue(':vwip','11050103',PDO::PARAM_STR);
				$command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($enddate)),PDO::PARAM_STR);
				$command2->bindvalue(':vcompanyid',$row1['companyid'],PDO::PARAM_STR);
				$command2->execute();

				$qwip = "select @vsaldoakhir as saldoakhir";
				$tmt2 = Yii::app()->db->createCommand($qwip);
				$tmt2->execute();
				$wip = $tmt2->queryRow();

				$sqlrw = "call hitungsaldodate(:vrw,:vdate,:vcompanyid,@vsaldoakhir)";
				$command3 = $connection->createCommand($sqlrw);
				$command3->bindvalue(':vrw','11050102',PDO::PARAM_STR);
				$command3->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($enddate)),PDO::PARAM_STR);
				$command3->bindvalue(':vcompanyid',$row1['companyid'],PDO::PARAM_STR);
				$command3->execute();

				$qrw = "select @vsaldoakhir as saldoakhir";
				$tmt3 = Yii::app()->db->createCommand($qrw);
				$tmt3->execute();
				$rw = $tmt3->queryRow();
				
				$totfg	= $totfg + $fg['saldoakhir'];
				$totwip	= $totwip + $wip['saldoakhir'];
				$totrw	= $totrw + $rw['saldoakhir'];
			}
			
$msg = "\nPersediaan FG : ".Yii::app()->numberFormatter->formatCurrency($totfg/$per,"Rp.")
			."\nPersediaan WIP : ".Yii::app()->numberFormatter->formatCurrency($totwip/$per,"Rp.")
			."\nPersediaan RW: ".Yii::app()->numberFormatter->formatCurrency($totrw/$per,"Rp.");
$pesan .= $msg;

$msg = "\n\n*6. Hutang Dagang vs PEMBAYARAN*\nUmur Jumlah VS Jumlah\n";

$pesan .= $msg;
			
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
								where a.recordstatus=3 and a.invoiceno is not null and c.companyid in ({$companyid})
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
								where e.recordstatus=3 and e.companyid in ({$companyid}) and e.docdate between '".date(Yii::app()->params['datetodb'],strtotime($startdate))."' and '".date(Yii::app()->params['datetodb'],strtotime($enddate))."') z ) zz) zzz;
			";
	
			$row15=Yii::app()->db->createCommand($sql15)->queryRow();
		 
$msg = "HD 1-30 ".Yii::app()->numberFormatter->formatCurrency($row13['0sd30']/$per,"Rp.")." VS ".Yii::app()->numberFormatter->formatCurrency($row15['0sd30']/$per,"Rp.");
$pesan .= $msg; 

$msg = "\nHD 31-60 ".Yii::app()->numberFormatter->formatCurrency($row13['31sd60']/$per,"Rp.")." VS ".Yii::app()->numberFormatter->formatCurrency($row15['31sd60']/$per,"Rp.");
$pesan .= $msg; 

$msg = "\nHD 61-90 ".Yii::app()->numberFormatter->formatCurrency($row13['61sd90']/$per,"Rp.")." VS ".Yii::app()->numberFormatter->formatCurrency($row15['61sd90']/$per,"Rp.");
$pesan .= $msg; 

$msg = "\nHD 91-120 ".Yii::app()->numberFormatter->formatCurrency($row13['91sd120']/$per,"Rp.")." VS ".Yii::app()->numberFormatter->formatCurrency($row15['91sd120']/$per,"Rp.");
$pesan .= $msg;

$msg = "\nHD > 120 ".Yii::app()->numberFormatter->formatCurrency($row13['up120']/$per,"Rp.")." VS ".Yii::app()->numberFormatter->formatCurrency($row15['up120']/$per,"Rp.");
$pesan .= $msg; 

$msg = "\nTotal HD ".Yii::app()->numberFormatter->formatCurrency(($row13['0sd30']+$row13['31sd60']+$row13['61sd90']+$row13['91sd120']+$row13['up120'])/$per,"Rp."). " VS ".Yii::app()->numberFormatter->formatCurrency(($row15['0sd30']+$row15['31sd60']+$row15['61sd90']+$row15['91sd120']+$row15['up120'])/$per,"Rp.");

$pesan .= $msg;

$msg = "\n\n*7. Piutang Dagang*\nKeterangan Nilai Persen";
$pesan .= $msg;
			
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
											where a.recordstatus=3 and a.invoiceno is not null and c.companyid in ({$companyid}) and a.invoicedate <= '".date(Yii::app()->params['datetodb'], strtotime($enddate))."') z
									where amount > payamount) zz 
			";
			$row9=Yii::app()->db->createCommand($sql9)->queryRow();
			
			if (($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']) == 0)
			{
				$persen0sd30 = 0;
				$persen31sd60 = 0;
				$persen61sd90 = 0;
				$persen91sd120 = 0;
				$persenup120 = 0;
			}
			else
			{
				$persen0sd30 = $row9['0sd30'] / ($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']);
				$persen31sd60 = $row9['31sd60'] / ($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']);
				$persen61sd90 = $row9['61sd90'] / ($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']);
				$persen91sd120 = $row9['91sd120'] / ($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']);
				$persenup120 = $row9['up120'] / ($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120']);
			}
			
$msg = "\nPD 1-30 ".Yii::app()->numberFormatter->formatCurrency($row9['0sd30']/$per,"Rp.")." ".Yii::app()->numberFormatter->formatCurrency(($persen0sd30)* 100,'')." %";
$pesan .= $msg;

$msg = "\nPD 31-60 ".Yii::app()->numberFormatter->formatCurrency($row9['31sd60']/$per,"Rp.")." ".Yii::app()->numberFormatter->formatCurrency(($persen31sd60)* 100,'')." %";
$pesan .= $msg;

$msg = "\nPD 61-90 ".Yii::app()->numberFormatter->formatCurrency($row9['61sd90']/$per,"Rp.")." ".Yii::app()->numberFormatter->formatCurrency(($persen61sd90)* 100,'')." %";
$pesan .= $msg;

$msg = "\nPD 91-120 ".Yii::app()->numberFormatter->formatCurrency($row9['91sd120']/$per,"Rp.")." ".Yii::app()->numberFormatter->formatCurrency(($persen91sd120)* 100,'')." %";
$pesan .= $msg;

$msg = "\nPD > 120 ".Yii::app()->numberFormatter->formatCurrency($row9['up120']/$per,"Rp.")." ".Yii::app()->numberFormatter->formatCurrency(($persenup120)* 100,'')." %";
$pesan .= $msg;

$msg = "\nTotal PD ".Yii::app()->numberFormatter->formatCurrency(($row9['0sd30']+$row9['31sd60']+$row9['61sd90']+$row9['91sd120']+$row9['up120'])/$per,"Rp.")." "."100.00 %";

$pesan .= $msg;

			date_default_timezone_set('Asia/Jakarta');
			$time = date('d-m-Y H:i:s');
			
			//url dan kirim data untuk wa japri ke Ius Tan
			$wanumber = '6285888885050';
			
			$url = Yii::app()->params['ip'].'send_message';
			$data = array(
				"phone_no"  => $wanumber,
				"key"		=> Yii::app()->params['key'],
				"message"	=> $pesan."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time
			);
			$data_string = json_encode($data);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 360);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
			);
			echo $res=curl_exec($ch);
			
/*				//url dan kirim data untuk wa group
			$url = Yii::app()->params['ip'].'send_message_group_id';
			
			$data = array(
				"group_id"=> $groupexcellent,
				"key"		=> Yii::app()->params['key'],
				"message"	=> $pesan."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time
			);
			$data_string = json_encode($data);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 360);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
			);
			echo $res=curl_exec($ch);
*/				/*
			$data = array(
				"group_id"=> $grouptop,
				"key"		=> Yii::app()->params['key'],
				"message"	=> $pesan."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time
			);
			$data_string = json_encode($data);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 360);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
			);
			echo $res=curl_exec($ch);
			*/
			
/*				//japri ke Branch Manager
			$sqluser = "select distinct wanumber
						from useraccess a
						join usergroup b on b.useraccessid = a.useraccessid
						join groupaccess c on c.groupaccessid = b.groupaccessid
						join groupmenuauth d on d.groupaccessid=c.groupaccessid
						where a.recordstatus = 1 and c.groupname like '%Branch Manager%'
						and d.menuauthid = 5 and d.menuvalueid in ({$companyid})
						and a.wanumber <> '';
			";
			$dataUser = Yii::app()->db->createCommand($sqluser)->queryAll();

			foreach($dataUser as $wa)
			{
				
				//url dan kirim data untuk wa japri
				$url = Yii::app()->params['ip'].'send_message';
				$data = array(
					"phone_no"  => $wa['wanumber'],
					"key"		=> Yii::app()->params['key'],
					"message"	=> $pesan."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time
				);
				$data_string = json_encode($data);

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 360);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data_string))
				);
				echo $res=curl_exec($ch);
			}
			
			
			//japri ke Chief Accounting
			$sqluser = "select distinct wanumber
						from useraccess a
						join usergroup b on b.useraccessid = a.useraccessid
						join groupaccess c on c.groupaccessid = b.groupaccessid
						join groupmenuauth d on d.groupaccessid=c.groupaccessid
						where a.recordstatus = 1 and c.groupname like '%Chief Accounting%'
						and d.menuauthid = 5 and d.menuvalueid in ({$companyid})
						and a.wanumber <> '';
			";
			$dataUser = Yii::app()->db->createCommand($sqluser)->queryAll();

			foreach($dataUser as $wa)
			{
				
				//url dan kirim data untuk wa japri
				$url = Yii::app()->params['ip'].'send_message';
				$data = array(
					"phone_no"  => $wa['wanumber'],
					"key"		=> Yii::app()->params['key'],
					"message"	=> $pesan."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time
				);
				$data_string = json_encode($data);

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 360);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data_string))
				);
				echo $res=curl_exec($ch);
			}
*/				
			curl_close($ch);
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			//$transaction->rollBack();
      		GetMessage(true, $e->getMessage());
		}
	}
}