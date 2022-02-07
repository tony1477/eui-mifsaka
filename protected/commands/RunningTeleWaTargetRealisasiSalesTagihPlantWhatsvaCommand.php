<?php
class RunningTeleWaTargetRealisasiSalesTagihPlantWhatsvaCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			$startdate=$connection->createCommand("SELECT DATE_ADD(LAST_DAY(DATE_ADD(DATE_ADD(DATE(NOW()),INTERVAL -1 DAY), INTERVAL -1 MONTH)),INTERVAL 1 DAY)")->queryScalar();
			$enddate=$connection->createCommand("SELECT DATE_ADD(DATE(NOW()),INTERVAL -1 DAY)")->queryScalar();
				
			$sql = "select a.companyid,a.companycode
							from company a
							where a.recordstatus = 1
							and a.nourut > 0
							and a.companyid <> 11
							and a.companyid <> 12
							and a.companyid <> 14
							and a.companyid <> 18
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
				";
				$dataReader2=$connection->createCommand($sql2)->queryAll();
				$totaltargetcom=0;$totalpenjualancom=0;$totalpelunasancom=0;$totaltargetharcom=0;$totalpenjualanharcom=0;$totalpelunasanharcom=0;$pesancom="";
				
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
							GROUP BY employeeid
							ORDER BY fullname
					";
					$dataReader1=$connection->createCommand($sql1)->queryAll();
					$i=0;$totaltarget=0;$totalpenjualan=0;$totalpelunasan=0;$totaltargethar=0;$totalpenjualanhar=0;$totalpelunasanhar=0;$pesan="";
					
					foreach ($dataReader1 as $data1)
					{
						$i=$i+1;
						
						$persenpenj = 0;$persenpel = 0;$persenpenjhar = 0;$persenpelhar = 0;
						if($data1['target'] <> 0){$persenpenj = 100*$data1['penjualan']/$data1['target'];}
						if($data1['penjualan'] <> 0){$persenpel = 100*$data1['pelunasan']/$data1['penjualan'];}
						if($data1['targethar'] <> 0){$persenpenjhar = 100*$data1['penjualanhar']/$data1['targethar'];}
						if($data1['penjualanhar'] <> 0){$persenpelhar = 100*$data1['pelunasanhar']/$data1['penjualanhar'];}
						
						$target = Yii::app()->numberFormatter->formatCurrency($data1['target'],"Rp.");
						$penjualan = Yii::app()->numberFormatter->formatCurrency($data1['penjualan'],"Rp.");
						$pelunasan = Yii::app()->numberFormatter->formatCurrency($data1['pelunasan'],"Rp.");
						$targethar = Yii::app()->numberFormatter->formatCurrency($data1['targethar'],"Rp.");
						$penjualanhar = Yii::app()->numberFormatter->formatCurrency($data1['penjualanhar'],"Rp.");
						$pelunasanhar = Yii::app()->numberFormatter->formatCurrency($data1['pelunasanhar'],"Rp.");
						$persenpenj = Yii::app()->numberFormatter->formatCurrency($persenpenj,"");
						$persenpel = Yii::app()->numberFormatter->formatCurrency($persenpel,"");
						$persenpenjhar = Yii::app()->numberFormatter->formatCurrency($persenpenjhar,"");
						$persenpelhar = Yii::app()->numberFormatter->formatCurrency($persenpelhar,"");
						
						$pesan = $pesan ."\n *{$i}. {$data1['fullname']}*\nTarget {$targethar}\nPenj-Ret {$penjualanhar}  {$persenpenjhar}\nTagihan {$pelunasanhar}  {$persenpelhar}\nTarget 1Bln {$target}\nPenj-Ret Kum {$penjualan}  {$persenpenj}\nTagihan Kum {$pelunasan}  {$persenpel}\n";
						
						$totaltarget += $data1['target'];
						$totalpenjualan += $data1['penjualan'];
						$totalpelunasan += $data1['pelunasan'];
						$totaltargethar += $data1['targethar'];
						$totalpenjualanhar += $data1['penjualanhar'];
						$totalpelunasanhar += $data1['pelunasanhar'];
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
					
					$pesan = $pesan ."\n *TOTAL {$plantcode}*\nTarget {$totaltargetharform}\nPenj-Ret {$totalpenjualanharform}  {$totalpersenpenjharform}\nTagihan {$totalpelunasanharform}  {$totalpersenpelharform}\nTarget 1Bln {$totaltargetform}\nPenj-Ret Kum {$totalpenjualanform}  {$totalpersenpenjform}\nTagihan Kum {$totalpelunasanform}  {$totalpersenpelform}\n";
					
					$time = date('Y-m-d H:i:s');

					if ($companyid == 1) {$telegroupid = "-1001435078485"; $wagroupid = "6285265644828-1483401177";} //AKA
					else if ($companyid == 11) {$telegroupid = "-1001435078485"; $wagroupid = "6285265644828-1483401177";} //UD1
					else if ($companyid == 12) {$telegroupid = "-1001435078485"; $wagroupid = "6285265644828-1483401177";} //UD2
					//else if ($companyid == 21) {$telegroupid = "-1001196054232"; $wagroupid = "EEUVg29UfkDEkDVTqHtVLH";} //AKS
					else if ($companyid == 17) {$telegroupid = "-1001211726344"; $wagroupid = "6287875097026-1527279238";} //AMIN
					else if ($companyid == 20) {$telegroupid = "-1001442360059"; $wagroupid = "6285265644828-1547688422";} //AKM
					else if ($companyid == 18) {$telegroupid = "-1001257116233"; $wagroupid = "6281378010952-1533358281";} //AJM
					else if ($companyid == 7) {$telegroupid = "-1001402373281"; $wagroupid = "6285265644828-1557388538";} //AMI
					//else if ($companyid == 15) {$telegroupid = "-1001264861899"; $wagroupid = "EdJpLXyACDg3egNWEWWUun";} //AGEM
					else if ($companyid == 14) {$telegroupid = "-1001406450805"; $wagroupid = "6285265644828-1539936063";} //AKP


					//device-key
					$indosat = "d4987114-8563-4fdf-b15c-ed328057fae2";
					$siaga = "bf1ea6ba-ecc5-488e-9d6a-d75947ecebcf";
					$as = "";
			
					$wanumber = '6281717212109';
					$wanumber = '6285265644828';
					//$wanumber = '628111777570'; //suwito
					//$wanumber = '6285376361879'; //martoni
					
					$budgetplantpl=$connection->createCommand("SELECT -SUM(b.budgetamount) FROM budget b WHERE b.companyid = {$companyid} 
					AND b.plantid = {$plantid} 
					AND b.budgetdate = '{$startdate}' AND b.accountcode BETWEEN '3' AND '39999999999999'")->queryScalar();
					$budgetplantpl = Yii::app()->numberFormatter->formatCurrency($budgetplantpl,"Rp.");
					
					//$budget = "\n*Budget Penjualan Bersih {$plantcode} {$budgetplantpl}*\n";
				
					$wamessage = "*LAPORAN TARGET VS REALISASI PENJUALAN-RETUR VS REALISASI TAGIHAN SALESMAN {$plantcode} :*\n_Tanggal {$startdate} s/d {$enddate}_\n{$pesan}{$budget}\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

					//url dan kirim data untuk telegram ke group
					$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage);
					$ch = curl_init();
					$optArray = array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true
					);
					curl_setopt_array($ch, $optArray);
					$result = curl_exec($ch);
					curl_close($ch);
					
					//url dan kirim data untuk wa group
					sendwagroup($siaga,$wamessage,$wagroupid);
					//sendwajapri($siaga,$wamessage,$wanumber);
					
					sleep(3);
						
					$totaltargetcom += $totaltarget;
					$totalpenjualancom += $totalpenjualan;
					$totalpelunasancom += $totalpelunasan;
					$totaltargetharcom += $totaltargethar;
					$totalpenjualanharcom += $totalpenjualanhar;
					$totalpelunasanharcom += $totalpelunasanhar;
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
					
				$budgetpl=$connection->createCommand("SELECT -SUM(b.budgetamount) FROM budget b WHERE b.companyid = {$companyid} AND b.budgetdate = '{$startdate}' AND b.accountcode BETWEEN '3' AND '39999999999999'")->queryScalar();
				$budgetpl = Yii::app()->numberFormatter->formatCurrency($budgetpl,"Rp.");
					
				$pesancom = $pesancom ."\n *TOTAL {$companycode}*\nTarget {$totaltargetharcomform}\nPenj-Ret {$totalpenjualanharcomform}  {$totalpersenpenjharcomform}\nTagihan {$totalpelunasanharcomform}  {$totalpersenpelharcomform}\nTarget 1Bln {$totaltargetcomform}\nPenj-Ret Kum {$totalpenjualancomform}  {$totalpersenpenjcomform}\nTagihan Kum {$totalpelunasancomform}  {$totalpersenpelcomform}\n";
				
				//$budgetcom = "\n*Budget Penjualan Bersih {$companycode} {$budgetpl}*\n";
				
				$wamessagecom = "*LAPORAN TARGET VS REALISASI PENJUALAN-RETUR VS REALISASI TAGIHAN SALESMAN {$companycode} :*\n_Tanggal {$startdate} s/d {$enddate}_\n{$pesancom}{$budgetcom}\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

				//url dan kirim data untuk telegram ke group
				$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessagecom);
				$ch = curl_init();
				$optArray = array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true
				);
				curl_setopt_array($ch, $optArray);
				$result = curl_exec($ch);
				curl_close($ch);
				
				//url dan kirim data untuk wa group
				sendwagroup($siaga,$wamessagecom,$wagroupid);
				//sendwajapri($siaga,$wamessagecom,$wanumber);
					
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