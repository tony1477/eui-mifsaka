<?php
class RunningTeleWaTargetRealisasiSalesTagihPlantGroupExcellentWhatsvaCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			$per = 10;
			$onoffjapri = 0;
			$onoffsales = 1;
			$onoffgroupexcellent = 1;
			$onoffchannelca = 0;
			$onoffjapribm = 1;
			
			$wanumber = '6281717212109';
			$wanumber = '6285265644828';
			//$wanumber = '628127090802'; //sriwanti
			//$wanumber = '628111777570'; //suwito
			//$wanumber = '6285376361879'; //martoni

			//device-key
			$siaga = "bf1ea6ba-ecc5-488e-9d6a-d75947ecebcf";
			
			date_default_timezone_set('Asia/Jakarta');
			$startdate=$connection->createCommand("SELECT DATE_ADD(LAST_DAY(DATE_ADD(DATE_ADD(DATE(NOW()),INTERVAL -1 DAY), INTERVAL -1 MONTH)),INTERVAL 1 DAY)")->queryScalar();
			$enddate=$connection->createCommand("SELECT DATE_ADD(DATE(NOW()),INTERVAL -1 DAY)")->queryScalar();
				
			$sql = "select a.companyid,a.companycode
							from company a
							where a.recordstatus = 1
							and a.nourut > 0
							-- and a.companyid <> 11
							and a.companyid <> 12
							and a.companyid <> 14
							-- and a.companyid = 1
							order by a.nourut asc
			";
			$dataReader=$connection->createCommand($sql)->queryAll();
			
			foreach ($dataReader as $data)
			{
				$companyid = $data['companyid'];
				$companycode = $data['companycode'];
				$sql2 = "SELECT plantid,plantcode
						FROM (SELECT s.plantid,p.plantcode
						FROM soheader s 
						JOIN giheader g ON g.soheaderid=s.soheaderid 
						JOIN invoice i ON i.giheaderid=g.giheaderid 
						JOIN employee e ON e.employeeid=s.employeeid 
						LEFT JOIN plant p on p.plantid=s.plantid
						WHERE i.recordstatus = 3 AND i.companyid = {$companyid} AND i.invoicedate BETWEEN '{$startdate}' AND '{$enddate}'
						GROUP BY p.plantid 
					UNION
						SELECT s6.plantid,p2.plantcode
						FROM notagirpro n 
						JOIN notagir n2 ON n2.notagirid=n.notagirid
						JOIN gireturdetail g3 ON g3.gireturdetailid=n.gireturdetailid
						JOIN giretur g4 ON g4.gireturid=g3.gireturid
						JOIN gidetail g5 ON g5.gidetailid=g3.gidetailid
						JOIN giheader g6 ON g6.giheaderid=g4.giheaderid
						JOIN sodetail s5 ON s5.sodetailid=g5.sodetailid
						JOIN soheader s6 ON s6.soheaderid=g6.soheaderid
						JOIN employee e2 ON e2.employeeid=s6.employeeid 
						LEFT JOIN plant p2 on p2.plantid=s6.plantid
						WHERE n2.recordstatus = 3 AND n2.companyid = {$companyid} AND n2.docdate BETWEEN '{$startdate}' AND '{$enddate}'
						GROUP BY p2.plantid) z
						GROUP BY plantid
						ORDER BY plantid
						-- Limit 1
				";
				$dataReader2=$connection->createCommand($sql2)->queryAll();
				$totaltargetcom=0;$totalpenjualancom=0;$totalpelunasancom=0;$totaltargetharcom=0;$totalpenjualanharcom=0;$totalpelunasanharcom=0;$totalpendingansocom=0;$pesancom="";$totalpiutsd0com=0;$totalpiut0sd30com=0;$totalpiut31sd60com=0;$totalpiut61sd90com=0;$totalpiut91sd120com=0;$totaljumlahcom=0;
				
				foreach ($dataReader2 as $data2)
				{
					$plantid = $data2['plantid'];
					$plantcode = $data2['plantcode'];
					$sql1 = "SELECT DISTINCT employeeid,fullname,SUM(target) AS target,SUM(penjualan) AS penjualan,SUM(pelunasan) AS pelunasan,SUM(targethar)/25 AS targethar,SUM(penjualanhar) AS penjualanhar,SUM(pelunasanhar) AS pelunasanhar
							FROM (SELECT s.employeeid,e.fullname
							,IFNULL((SELECT SUM(s3.qty*s3.price) FROM salestarget s2 JOIN salestargetdet s3 ON s3.salestargetid=s2.salestargetid WHERE s2.recordstatus = 4 AND s2.companyid = i.companyid AND s2.employeeid = s.employeeid AND s2.perioddate = CONCAT(YEAR(i.invoicedate),'-',MONTH(i.invoicedate),'-01')),0) AS target
							,SUM((SELECT SUM(getamountdiscso(s4.soheaderid,s4.sodetailid,g2.qty)) FROM gidetail g2 JOIN sodetail s4 ON s4.sodetailid = g2.sodetailid WHERE g2.giheaderid = g.giheaderid)) AS penjualan
							,IFNULL((SELECT SUM(c.cashamount+c.bankamount+c.discamount+c.obamount) FROM cutarinv c JOIN cutar c2 ON c2.cutarid=c.cutarid JOIN ttnt t ON t.ttntid=c2.ttntid WHERE c2.recordstatus = 3 AND c2.companyid = i.companyid AND t.employeeid = s.employeeid AND c2.docdate BETWEEN '{$startdate}' AND '{$enddate}'),0) AS pelunasan
							,0 AS targethar,0 AS penjualanhar,0 AS pelunasanhar
							FROM soheader s 
							JOIN giheader g ON g.soheaderid=s.soheaderid 
							JOIN invoice i ON i.giheaderid=g.giheaderid 
							JOIN employee e ON e.employeeid=s.employeeid 
							WHERE i.recordstatus = 3 AND i.companyid = {$companyid} 
							AND s.plantid = {$plantid} 
							AND i.invoicedate BETWEEN '{$startdate}' AND '{$enddate}'
							GROUP BY s.employeeid 
						UNION
							SELECT e2.employeeid,e2.fullname,0 as target,-SUM(n.qty*n.price) AS penjualan,0 AS pelunasan
							,0 AS targethar,0 AS penjualanhar,0 AS pelunasanhar
							FROM notagirpro n 
							JOIN notagir n2 ON n2.notagirid=n.notagirid
							JOIN gireturdetail g3 ON g3.gireturdetailid=n.gireturdetailid
							JOIN giretur g4 ON g4.gireturid=g3.gireturid
							JOIN gidetail g5 ON g5.gidetailid=g3.gidetailid
							JOIN giheader g6 ON g6.giheaderid=g4.giheaderid
							JOIN sodetail s5 ON s5.sodetailid=g5.sodetailid
							JOIN soheader s6 ON s6.soheaderid=g6.soheaderid
							JOIN employee e2 ON e2.employeeid=s6.employeeid 
							WHERE n2.recordstatus = 3 AND n2.companyid = {$companyid} 
							AND s6.plantid = {$plantid} 
							AND n2.docdate BETWEEN '{$startdate}' AND '{$enddate}'
							GROUP BY e2.employeeid 
						UNION
							SELECT s.employeeid,e.fullname,0 AS target,0 AS penjualan,0 AS pelunasan
							,IFNULL((SELECT SUM(s3.qty*s3.price) FROM salestarget s2 JOIN salestargetdet s3 ON s3.salestargetid=s2.salestargetid WHERE s2.recordstatus = 4 AND s2.companyid = i.companyid AND s2.employeeid = s.employeeid AND s2.perioddate = CONCAT(YEAR(i.invoicedate),'-',MONTH(i.invoicedate),'-01')),0) AS targethar
							,SUM((SELECT SUM(getamountdiscso(s4.soheaderid,s4.sodetailid,g2.qty)) FROM gidetail g2 JOIN sodetail s4 ON s4.sodetailid = g2.sodetailid WHERE g2.giheaderid = g.giheaderid)) AS penjualanhar
							,IFNULL((SELECT SUM(c.cashamount+c.bankamount+c.discamount+c.obamount) FROM cutarinv c JOIN cutar c2 ON c2.cutarid=c.cutarid JOIN ttnt t ON t.ttntid=c2.ttntid WHERE c2.recordstatus = 3 AND c2.companyid = i.companyid AND t.employeeid = s.employeeid AND c2.docdate = '{$enddate}'),0) AS pelunasanhar
							FROM soheader s 
							JOIN giheader g ON g.soheaderid=s.soheaderid 
							JOIN invoice i ON i.giheaderid=g.giheaderid 
							JOIN employee e ON e.employeeid=s.employeeid 
							WHERE i.recordstatus = 3 AND i.companyid = {$companyid} 
							AND s.plantid = {$plantid} 
							AND i.invoicedate = '{$enddate}'
							GROUP BY s.employeeid 
						UNION
							SELECT e2.employeeid,e2.fullname,0 AS target,0 AS penjualan,0 AS pelunasan,0 as targethar,-SUM(n.qty*n.price) AS penjualanhar,0 AS pelunasanhar
							FROM notagirpro n 
							JOIN notagir n2 ON n2.notagirid=n.notagirid
							JOIN gireturdetail g3 ON g3.gireturdetailid=n.gireturdetailid
							JOIN giretur g4 ON g4.gireturid=g3.gireturid
							JOIN gidetail g5 ON g5.gidetailid=g3.gidetailid
							JOIN giheader g6 ON g6.giheaderid=g4.giheaderid
							JOIN sodetail s5 ON s5.sodetailid=g5.sodetailid
							JOIN soheader s6 ON s6.soheaderid=g6.soheaderid
							JOIN employee e2 ON e2.employeeid=s6.employeeid 
							WHERE n2.recordstatus = 3 AND n2.companyid = {$companyid} 
							AND s6.plantid = {$plantid} 
							AND n2.docdate = '{$enddate}'
							GROUP BY e2.employeeid ) z
							WHERE target <> 0 OR penjualan <> 0 OR pelunasan <> 0 OR targethar <> 0 OR penjualanhar <> 0 OR pelunasanhar <> 0
							GROUP BY employeeid
							ORDER BY fullname
							-- Limit 1
					";
					$dataReader1=$connection->createCommand($sql1)->queryAll();
					$i=0;$totaltarget=0;$totalpenjualan=0;$totalpelunasan=0;$totaltargethar=0;$totalpenjualanhar=0;$totalpelunasanhar=0;$totalpendinganso=0;$pesan="";$totalpiutsd0= 0;$totalpiut0sd30=0;$totalpiut31sd60=0;$totalpiut61sd90=0;$totalpiut91sd120=0;$totalpiutsd120=0;$totaljumlah=0;
					
					foreach ($dataReader1 as $data1)
					{
						$employeeid = $data1['employeeid'];
						$employeename = $data1['fullname'];
						
						$pendso=0;
						$pendso=$connection->createCommand("SELECT SUM(GetTotalAmountDiscPendinganSO(soheaderid)) FROM (SELECT s2.soheaderid FROM sodetail s JOIN soheader s2 ON s2.soheaderid=s.soheaderid JOIN product p ON p.productid=s.productid WHERE s2.recordstatus = 6 AND s2.companyid = {$companyid} AND s2.plantid = {$plantid} AND p.isstock = 1 AND s.qty > s.giqty AND s2.employeeid = {$employeeid} 
						AND s2.sodate BETWEEN '{$startdate}' AND '{$enddate}'
						GROUP BY s2.soheaderid) z")->queryScalar();
						
						$sql3 = "select fullname,sum(sd0) as sd0,sum(0sd30) as 0sd30,sum(31sd60) as 31sd60,sum(61sd90) as 61sd90,sum(91sd120) as 91sd120,sum(sd120) as sd120
								FROM (select fullname,invoiceno,
								case when umurtempo < 0 then totamount else 0 end as sd0,
								case when umurtempo >= 0 and umurtempo <= 30 then totamount else 0 end as 0sd30,
								case when umurtempo > 30 and umurtempo <= 60 then totamount else 0 end as 31sd60,
								case when umurtempo > 60 and umurtempo <= 90 then totamount else 0 end as 61sd90,
								case when umurtempo > 90 and umurtempo <= 120 then totamount else 0 end as 91sd120,
								case when umurtempo > 120 then totamount else 0 end as sd120
								from
								(select distinct f.fullname,a.invoiceno,
								datediff('{$enddate}',date_add(a.invoicedate,interval d.paydays day)) as umurtempo,
								a.amount-ifnull((select sum((ifnull(o.cashamount,0)+ifnull(o.bankamount,0)+ifnull(o.discamount,0)+ifnull(o.returnamount,0)+ifnull(o.obamount,0))*ifnull(o.currencyrate,0))
								from cutarinv o
								join cutar p on p.cutarid=o.cutarid
								where p.recordstatus=3 and o.invoiceid=a.invoiceid and p.docdate <= '{$enddate}'),0) as totamount
								from invoice a
								join giheader b on b.giheaderid = a.giheaderid
								join soheader c on c.soheaderid = b.soheaderid
								join paymentmethod d on d.paymentmethodid = c.paymentmethodid
								join employee e on e.employeeid = c.employeeid
								join addressbook f on f.addressbookid  = c.addressbookid 
								where a.isbaddebt = 0 and c.companyid = {$companyid} and c.plantid = {$plantid} and a.recordstatus = 3 and a.invoiceno is not null and e.employeeid = {$employeeid}
								and a.invoicedate <= '{$enddate}') z ORDER BY fullname) zz
						";
						$piut=$connection->createCommand($sql3)->queryRow();
						
						$persenpenj = 0;$persenpel = 0;$persenpenjhar = 0;$persenpelhar = 0;
						if($data1['target'] <> 0){$persenpenj = 100*$data1['penjualan']/$data1['target'];}
						if($data1['penjualan'] <> 0){$persenpel = 100*$data1['pelunasan']/$data1['penjualan'];}
						if($data1['targethar'] <> 0){$persenpenjhar = 100*$data1['penjualanhar']/$data1['targethar'];}
						if($data1['penjualanhar'] <> 0){$persenpelhar = 100*$data1['pelunasanhar']/$data1['penjualanhar'];}
						
						$target = Yii::app()->numberFormatter->formatCurrency($data1['target']/$per,"Rp.");
						$penjualan = Yii::app()->numberFormatter->formatCurrency($data1['penjualan']/$per,"Rp.");
						$pelunasan = Yii::app()->numberFormatter->formatCurrency($data1['pelunasan']/$per,"Rp.");
						$targethar = Yii::app()->numberFormatter->formatCurrency($data1['targethar']/$per,"Rp.");
						$penjualanhar = Yii::app()->numberFormatter->formatCurrency($data1['penjualanhar']/$per,"Rp.");
						$pelunasanhar = Yii::app()->numberFormatter->formatCurrency($data1['pelunasanhar']/$per,"Rp.");
						$persenpenj = Yii::app()->numberFormatter->formatCurrency($persenpenj,"");
						$persenpel = Yii::app()->numberFormatter->formatCurrency($persenpel,"");
						$persenpenjhar = Yii::app()->numberFormatter->formatCurrency($persenpenjhar,"");
						$persenpelhar = Yii::app()->numberFormatter->formatCurrency($persenpelhar,"");
						$pendinganso = Yii::app()->numberFormatter->formatCurrency($pendso/$per,"Rp.");
						$piutsd0 = Yii::app()->numberFormatter->formatCurrency($piut['sd0']/$per,"Rp.");
						$piut0sd30 = Yii::app()->numberFormatter->formatCurrency($piut['0sd30']/$per,"Rp.");
						$piut31sd60 = Yii::app()->numberFormatter->formatCurrency($piut['31sd60']/$per,"Rp.");
						$piut61sd90 = Yii::app()->numberFormatter->formatCurrency($piut['61sd90']/$per,"Rp.");
						$piut91sd120 = Yii::app()->numberFormatter->formatCurrency($piut['91sd120']/$per,"Rp.");
						$piutsd120 = Yii::app()->numberFormatter->formatCurrency($piut['sd120']/$per,"Rp.");
						$jumlah = Yii::app()->numberFormatter->formatCurrency(($piut['0sd30']+$piut['31sd60']+$piut['61sd90']+$piut['91sd120']+$piut['sd120'])/$per,"Rp.");
						
						$i=$i+1;
						$pesan = $pesan ."\n *{$i}. {$data1['fullname']}* - {$employeeid}\nTarget {$targethar}\nPenj-Ret {$penjualanhar} / {$persenpenjhar}\nTagihan {$pelunasanhar} / {$persenpelhar}\nTarget 1Bln {$target}\nPenj-Ret Kum {$penjualan} / {$persenpenj}\nTagihan Kum {$pelunasan} / {$persenpel}\nPendingan SO {$pendinganso}\nPD Belum JTT {$piutsd0}\nPD Sudah JTT {$jumlah}\n   0-30 Hari {$piut0sd30}\n   31-60 Hari {$piut31sd60}\n   61-90 Hari {$piut61sd90}\n   91-120 Hari {$piut91sd120}\n   >120 Hari {$piutsd120}\n";
						
						$totaltarget += $data1['target']/$per;
						$totalpenjualan += $data1['penjualan']/$per;
						$totalpelunasan += $data1['pelunasan']/$per;
						$totaltargethar += $data1['targethar']/$per;
						$totalpenjualanhar += $data1['penjualanhar']/$per;
						$totalpelunasanhar += $data1['pelunasanhar']/$per;
						$totalpendinganso += $pendso/$per;
						$totalpiutsd0 += $piut['sd0']/$per;
						$totalpiut0sd30 += $piut['0sd30']/$per;
						$totalpiut31sd60 += $piut['31sd60']/$per;
						$totalpiut61sd90 += $piut['61sd90']/$per;
						$totalpiut91sd120 += $piut['91sd120']/$per;
						$totalpiutsd120 += $piut['sd120']/$per;
						$totaljumlah += ($piut['0sd30']+$piut['31sd60']+$piut['61sd90']+$piut['91sd120']+$piut['sd120'])/$per;
					
						$time = date('Y-m-d H:i:s');
						
						$pesansales = "";
						$pesansales = $pesansales ."\n*{$data1['fullname']}* - {$employeeid}\nTarget {$targethar}\nPenj-Ret {$penjualanhar} / {$persenpenjhar}\nTagihan {$pelunasanhar} / {$persenpelhar}\nTarget 1Bln {$target}\nPenj-Ret Kum {$penjualan} / {$persenpenj}\nTagihan Kum {$pelunasan} / {$persenpel}\nPendingan SO {$pendinganso}\nPD Belum JTT {$piutsd0}\nPD Sudah JTT {$jumlah}\n   0-30 Hari {$piut0sd30}\n   31-60 Hari {$piut31sd60}\n   61-90 Hari {$piut61sd90}\n   91-120 Hari {$piut91sd120}\n   >120 Hari {$piutsd120}\n";
						
						$wamessagesales = "*LAPORAN TARGET VS REALISASI PENJUALAN-RETUR VS REALISASI TAGIHAN SALESMAN {$companycode} :*\n_Tanggal {$startdate} s/d {$enddate}_\n{$pesansales}\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
						$wanumberemployee = $connection->createCommand("SELECT REPLACE(u.wanumber,'+','') FROM useraccess u WHERE u.recordstatus = 1 AND u.employeeid = {$employeeid}")->queryScalar();

						if($onoffsales > 0)
						{
							if($wanumberemployee > 0)
							{
								//url dan kirim data untuk wa japri salesman
								sendwajapri($siaga,$wamessagesales,$wanumberemployee);
							}
						}

						if($onoffjapri > 0)
						{
							//url dan kirim data untuk wa japri
							sendwajapri($siaga,$wamessagesales,$wanumber);
						}
						sleep (0);
					}
					$totalpersenpenj = 0;$totalpersenpel = 0;$totalpersenpenjhar = 0;$totalpersenpelhar = 0;
					if($totaltarget <> 0){$totalpersenpenj = 100*$totalpenjualan/$totaltarget;}
					if($totalpenjualan <> 0){$totalpersenpel = 100*$totalpelunasan/$totalpenjualan;}
					if($totaltargethar <> 0){$totalpersenpenjhar = 100*$totalpenjualanhar/$totaltargethar;}
					if($totalpenjualanhar <> 0){$totalpersenpelhar = 100*$totalpelunasanhar/$totalpenjualanhar;}
					
					$totaltargetform = Yii::app()->numberFormatter->formatCurrency($totaltarget,"Rp.");
					$totalpenjualanform = Yii::app()->numberFormatter->formatCurrency($totalpenjualan,"Rp.");
					$totalpelunasanform = Yii::app()->numberFormatter->formatCurrency($totalpelunasan,"Rp.");
					$totaltargetharform = Yii::app()->numberFormatter->formatCurrency($totaltargethar,"Rp.");
					$totalpenjualanharform = Yii::app()->numberFormatter->formatCurrency($totalpenjualanhar,"Rp.");
					$totalpelunasanharform = Yii::app()->numberFormatter->formatCurrency($totalpelunasanhar,"Rp.");
					$totalpersenpenjform = Yii::app()->numberFormatter->formatCurrency($totalpersenpenj,"");
					$totalpersenpelform = Yii::app()->numberFormatter->formatCurrency($totalpersenpel,"");
					$totalpersenpenjharform = Yii::app()->numberFormatter->formatCurrency($totalpersenpenjhar,"");
					$totalpersenpelharform = Yii::app()->numberFormatter->formatCurrency($totalpersenpelhar,"");
					$totalpendingansoform = Yii::app()->numberFormatter->formatCurrency($totalpendinganso,"Rp.");
					$totalpiutsd0form = Yii::app()->numberFormatter->formatCurrency($totalpiutsd0,"Rp.");
					$totalpiut0sd30form = Yii::app()->numberFormatter->formatCurrency($totalpiut0sd30,"Rp.");
					$totalpiut31sd60form = Yii::app()->numberFormatter->formatCurrency($totalpiut31sd60,"Rp.");
					$totalpiut61sd90form = Yii::app()->numberFormatter->formatCurrency($totalpiut61sd90,"Rp.");
					$totalpiut91sd120form = Yii::app()->numberFormatter->formatCurrency($totalpiut91sd120,"Rp.");
					$totalpiutsd120form = Yii::app()->numberFormatter->formatCurrency($totalpiutsd120,"Rp.");
					$totaljumlahform = Yii::app()->numberFormatter->formatCurrency($totaljumlah,"Rp.");
					
					$pesan = $pesan ."\n *TOTAL {$plantcode}*\nTarget {$totaltargetharform}\nPenj-Ret {$totalpenjualanharform} / {$totalpersenpenjharform}\nTagihan {$totalpelunasanharform} / {$totalpersenpelharform}\nTarget 1Bln {$totaltargetform}\nPenj-Ret Kum {$totalpenjualanform} / {$totalpersenpenjform}\nTagihan Kum {$totalpelunasanform} / {$totalpersenpelform}\nPendingan SO {$totalpendingansoform}\nPD Belum JTT {$totalpiutsd0form}\nPD Sudah JTT {$totaljumlahform}\n   0-30 Hari {$totalpiut0sd30form}\n   31-60 Hari {$totalpiut31sd60form}\n   61-90 Hari {$totalpiut61sd90form}\n   91-120 Hari {$totalpiut91sd120form}\n   >120 Hari {$totalpiutsd120form}\n";
					
					$time = date('Y-m-d H:i:s');

					//invite link whatsapp group
					$wagroupexcellent = '6285265644828-1461984841';
					$wagrouptop = '';
					
					//invite link telegram group
					$telegroupexcellent = '-1001154554937';
					$telegrouptop = '';
					$telechannelACCid = "-1001411770810"; //group Accounting AKA-Group Channel
					
					$budgetplantpl=$connection->createCommand("SELECT -SUM(b.budgetamount) FROM budget b WHERE b.companyid = {$companyid} 
					AND b.plantid = {$plantid} 
					AND b.budgetdate = '{$startdate}' AND b.accountcode BETWEEN '3' AND '39999999999999'")->queryScalar();
					$budgetplantpl = Yii::app()->numberFormatter->formatCurrency($budgetplantpl/$per,"Rp.");
					
					$budget = "\n*Budget Penjualan Bersih {$plantcode} {$budgetplantpl}*\n";
				
					$wamessage = "*LAPORAN TARGET VS REALISASI PENJUALAN-RETUR VS REALISASI TAGIHAN SALESMAN {$plantcode} :*\n_Tanggal {$startdate} s/d {$enddate}_\n{$pesan}{$budget}\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

					if($onoffjapri > 0)
					{
						//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage,$wanumber);
					}

					if($onoffgroupexcellent > 0)
					{
						//url dan kirim data untuk telegram ke groupexcellent
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupexcellent."&text=".urlencode($wamessage);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						curl_close($ch);
					
						//url dan kirim data untuk wa group
						sendwagroup($siaga,$wamessage,$wagroupexcellent);
					}

					if($onoffchannelca > 0)
					{
						//url dan kirim data untuk telegram ke channelCA
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telechannelACCid."&text=".urlencode($wamessage);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						curl_close($ch);
					}
					
					if($onoffjapribm > 0)
					{
						//japri ke Branch Manager Telegram
						$sqlbm = "select distinct telegramid
									from useraccess a
									join usergroup b on b.useraccessid = a.useraccessid
									join groupaccess c on c.groupaccessid = b.groupaccessid
									join groupmenuauth d on d.groupaccessid=c.groupaccessid
									where a.recordstatus = 1 and c.groupname like '%Branch Manager%'
									and d.menuauthid = 5 and d.menuvalueid = {$companyid}
									and a.telegramid <> '';
						";
						$databm = Yii::app()->db->createCommand($sqlbm)->queryAll();

						foreach($databm as $telebm)
						{
							$telebmid = $telebm['telegramid'];
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telebmid."&text=".urlencode($wamessage);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
							curl_close($ch);
						}
					
						//japri ke Branch Manager Whatsva
						$sqluser = "select distinct replace(wanumber,'+','') as wanumber,a.username
									from useraccess a
									join usergroup b on b.useraccessid = a.useraccessid
									join groupaccess c on c.groupaccessid = b.groupaccessid
									join groupmenuauth d on d.groupaccessid=c.groupaccessid
									where a.recordstatus = 1 and c.groupname like '%Branch Manager%'
									and d.menuauthid = 5 and d.menuvalueid = {$companyid}
									and a.wanumber <> '';
						";
						$dataUser = Yii::app()->db->createCommand($sqluser)->queryAll();

						foreach($dataUser as $wabm)
						{
							sendwajapri($siaga,$wamessage,$wabm['wanumber']);
						}
					}
					
					sleep(0);
						
					$totaltargetcom += $totaltarget;
					$totalpenjualancom += $totalpenjualan;
					$totalpelunasancom += $totalpelunasan;
					$totaltargetharcom += $totaltargethar;
					$totalpenjualanharcom += $totalpenjualanhar;
					$totalpelunasanharcom += $totalpelunasanhar;
					$totalpendingansocom += $totalpendinganso;
					$totalpiutsd0com += $totalpiutsd0;
					$totalpiut0sd30com += $totalpiut0sd30;
					$totalpiut31sd60com += $totalpiut31sd60;
					$totalpiut61sd90com += $totalpiut61sd90;
					$totalpiut91sd120com += $totalpiut91sd120;
					$totaljumlahcom += $totaljumlah;
				}
				$totalpersenpenjcom = 0;$totalpersenpelcom = 0;$totalpersenpenjharcom = 0;$totalpersenpelharcom = 0;
				if($totaltargetcom <> 0){$totalpersenpenjcom = 100*$totalpenjualancom/$totaltargetcom;}
				if($totalpenjualancom <> 0){$totalpersenpelcom = 100*$totalpelunasancom/$totalpenjualancom;}
				if($totaltargetharcom <> 0){$totalpersenpenjharcom = 100*$totalpenjualanharcom/$totaltargetharcom;}
				if($totalpenjualanharcom <> 0){$totalpersenpelharcom = 100*$totalpelunasanharcom/$totalpenjualanharcom;}
					
				$totaltargetcomform = Yii::app()->numberFormatter->formatCurrency($totaltargetcom,"Rp.");
				$totalpenjualancomform = Yii::app()->numberFormatter->formatCurrency($totalpenjualancom,"Rp.");
				$totalpelunasancomform = Yii::app()->numberFormatter->formatCurrency($totalpelunasancom,"Rp.");
				$totaltargetharcomform = Yii::app()->numberFormatter->formatCurrency($totaltargetharcom,"Rp.");
				$totalpenjualanharcomform = Yii::app()->numberFormatter->formatCurrency($totalpenjualanharcom,"Rp.");
				$totalpelunasanharcomform = Yii::app()->numberFormatter->formatCurrency($totalpelunasanharcom,"Rp.");
				$totalpersenpenjcomform = Yii::app()->numberFormatter->formatCurrency($totalpersenpenjcom,"");
				$totalpersenpelcomform = Yii::app()->numberFormatter->formatCurrency($totalpersenpelcom,"");
				$totalpersenpenjharcomform = Yii::app()->numberFormatter->formatCurrency($totalpersenpenjharcom,"");
				$totalpersenpelharcomform = Yii::app()->numberFormatter->formatCurrency($totalpersenpelharcom,"");
				$totalpendingansocomform = Yii::app()->numberFormatter->formatCurrency($totalpendingansocom,"Rp.");
				$totalpiutsd0comform = Yii::app()->numberFormatter->formatCurrency($totalpiutsd0com,"Rp.");
				$totalpiut0sd30comform = Yii::app()->numberFormatter->formatCurrency($totalpiut0sd30com,"Rp.");
				$totalpiut31sd60comform = Yii::app()->numberFormatter->formatCurrency($totalpiut31sd60com,"Rp.");
				$totalpiut61sd90comform = Yii::app()->numberFormatter->formatCurrency($totalpiut61sd90com,"Rp.");
				$totalpiut91sd120comform = Yii::app()->numberFormatter->formatCurrency($totalpiut91sd120com,"Rp.");
				$totalpiutsd120comform = Yii::app()->numberFormatter->formatCurrency($totalpiutsd120com,"Rp.");
				$totaljumlahcomform = Yii::app()->numberFormatter->formatCurrency($totaljumlahcom,"Rp.");
					
				$budgetpl=$connection->createCommand("SELECT -SUM(b.budgetamount) FROM budget b WHERE b.companyid = {$companyid} AND b.budgetdate = '{$startdate}' AND b.accountcode BETWEEN '3' AND '39999999999999'")->queryScalar();
				$budgetpl = Yii::app()->numberFormatter->formatCurrency($budgetpl/$per,"Rp.");
					
				$pesancom = $pesancom ."\n *TOTAL {$companycode}*\nTarget {$totaltargetharcomform}\nPenj-Ret {$totalpenjualanharcomform} / {$totalpersenpenjharcomform}\nTagihan {$totalpelunasanharcomform} / {$totalpersenpelharcomform}\nTarget 1Bln {$totaltargetcomform}\nPenj-Ret Kum {$totalpenjualancomform} / {$totalpersenpenjcomform}\nTagihan Kum {$totalpelunasancomform} / {$totalpersenpelcomform}\nPendingan SO {$totalpendingansocomform}\nPD Belum JTT {$totalpiutsd0comform}\nPD Sudah JTT {$totaljumlahcomform}\n   0-30 Hari {$totalpiut0sd30comform}\n   31-60 Hari {$totalpiut31sd60comform}\n   61-90 Hari {$totalpiut61sd90comform}\n   91-120 Hari {$totalpiut91sd120comform}\n   >120 Hari {$totalsd120comform}\n";
				
				$budgetcom = "\n*Budget Penjualan Bersih {$companycode} {$budgetpl}*\n";
				
				$wamessagecom = "*LAPORAN TARGET VS REALISASI PENJUALAN-RETUR VS REALISASI TAGIHAN SALESMAN {$companycode} :*\n_Tanggal {$startdate} s/d {$enddate}_\n{$pesancom}{$budgetcom}\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

				if($onoffjapri > 0)
				{
					//url dan kirim data untuk wa japri
					sendwajapri($siaga,$wamessagecom,$wanumber);
				}

				if($onoffgroupexcellent > 0)
				{
					//url dan kirim data untuk telegram ke groupexcellent
					$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupexcellent."&text=".urlencode($wamessagecom);
					$ch = curl_init();
					$optArray = array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true
					);
					curl_setopt_array($ch, $optArray);
					$result = curl_exec($ch);
					curl_close($ch);
				
					//url dan kirim data untuk wa group
					sendwagroup($siaga,$wamessagecom,$wagroupexcellent);
				}

				if($onoffchannelca > 0)
				{
					//url dan kirim data untuk telegram ke channelCA
					$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telechannelACCid."&text=".urlencode($wamessagecom);
					$ch = curl_init();
					$optArray = array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true
					);
					curl_setopt_array($ch, $optArray);
					$result = curl_exec($ch);
					curl_close($ch);
				}
					
				if($onoffjapribm > 0)
				{
					//japri ke Branch Manager Telegram
					$sqlbm = "select distinct telegramid
								from useraccess a
								join usergroup b on b.useraccessid = a.useraccessid
								join groupaccess c on c.groupaccessid = b.groupaccessid
								join groupmenuauth d on d.groupaccessid=c.groupaccessid
								where a.recordstatus = 1 and c.groupname like '%Branch Manager%'
								and d.menuauthid = 5 and d.menuvalueid = {$companyid}
								and a.telegramid <> '';
					";
					$databm = Yii::app()->db->createCommand($sqlbm)->queryAll();

					foreach($databm as $telebm)
					{
						$telebmid = $telebm['telegramid'];
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telebmid."&text=".urlencode($wamessagecom);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						curl_close($ch);
					}
				
					//japri ke Branch Manager Whatsva
					$sqluser = "select distinct replace(wanumber,'+','') as wanumber,a.username
								from useraccess a
								join usergroup b on b.useraccessid = a.useraccessid
								join groupaccess c on c.groupaccessid = b.groupaccessid
								join groupmenuauth d on d.groupaccessid=c.groupaccessid
								where a.recordstatus = 1 and c.groupname like '%Branch Manager%'
								and d.menuauthid = 5 and d.menuvalueid = {$companyid}
								and a.wanumber <> '';
					";
					$dataUser = Yii::app()->db->createCommand($sqluser)->queryAll();

					foreach($dataUser as $wabm)
					{
						sendwajapri($siaga,$wamessagecom,$wabm['wanumber']);
					}
				}
					
				sleep(0);
			}
			//curl_close($ch);
			//GetMessage(false, 'insertsuccess');
			//$transaction->commit();
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			//$transaction->rollBack();
			GetMessage(true, $e->getMessage());
			echo $e->getMessage();
		}
	}
}
