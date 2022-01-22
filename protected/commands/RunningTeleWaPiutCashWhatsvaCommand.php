<?php
class RunningTeleWaPiutCashWhatsvaCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			$sqldatetoday = "SELECT DATE(NOW())";
			$sqldateyesterday = "SELECT DATE_ADD(DATE(NOW()),INTERVAL -1 DAY)";
			$enddate=$connection->createCommand($sqldatetoday)->queryScalar();
				
			$sql = "select a.companyid,a.companycode,a.bankacc1,a.bankacc2,a.bankacc3
							from company a
							where a.recordstatus = 1
							order by a.nourut asc
			";
			$dataReader=$connection->createCommand($sql)->queryAll();
			
			foreach ($dataReader as $data)
			{
				$companyid = $data['companyid'];
				$companycode = $data['companycode'];
				$bankacc1 = $data['bankacc1'];
				$bankacc2 = $data['bankacc2'];
				$bankacc3 = $data['bankacc3'];
				$sql1 = "select addressbookid,fullname,(select aa.wanumber from addresscontact aa where aa.addressbookid=z.addressbookid Limit 1) as wanumber
						from (select c.addressbookid,b.giheaderid,a.invoiceno,a.invoicedate,e.paydays,
						date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
						datediff('{$enddate}',a.invoicedate) as umur,
						datediff('{$enddate}',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,ff.fullname as sales,
						ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
						from cutarinv f
						join cutar g on g.cutarid=f.cutarid
						where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '{$enddate}'),0) as payamount,d.fullname	
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						inner join employee ff on ff.employeeid = c.employeeid
						where a.recordstatus=3 and a.invoiceno is not null
						and c.companyid = {$companyid}
						and a.invoicedate <= '{$enddate}'
						and (d.fullname like 'cash%-%' or d.fullname like 'umum%-%' or d.fullname like 'erist%algian%' or d.fullname like 'arbenz%' or d.fullname like 'aswar%' or d.fullname like 'edwin%saleh'))z
						where amount > payamount
						-- and umurtempo >= 0
						group by fullname
						order by fullname
				";
				$dataReader1=$connection->createCommand($sql1)->queryAll();
				
				foreach ($dataReader1 as $data1)
				{
					$pesan=""; $pesan1=""; $pesan2=""; $pesan3=""; $pesan4=""; $pesan5=""; $i=0; $total=0;
					$sql2 = "select *, (amount-payamount) as sisa,(amount) as nilai
							from (select b.giheaderid,a.invoiceno,a.invoicedate,e.paydays,
							date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('{$enddate}',a.invoicedate) as umur,
							datediff('{$enddate}',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,a.amount,ff.fullname as sales,
							ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
							from cutarinv f
							join cutar g on g.cutarid=f.cutarid
							where g.recordstatus=3 and f.invoiceid=a.invoiceid and g.docdate <= '{$enddate}'),0) as payamount,d.fullname	
							from invoice a
							inner join giheader b on b.giheaderid = a.giheaderid
							inner join soheader c on c.soheaderid = b.soheaderid
							inner join addressbook d on d.addressbookid = c.addressbookid
							inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
							inner join employee ff on ff.employeeid = c.employeeid
							where a.recordstatus=3 and a.invoiceno is not null and c.companyid = {$companyid}				
							and a.invoicedate <= '{$enddate}'
							and c.addressbookid = {$data1['addressbookid']})z
							where amount > payamount
							-- and umurtempo >= 0
							order by umurtempo desc, invoiceno asc
					";
					$dataReader2=$connection->createCommand($sql2)->queryAll();
					
					foreach ($dataReader2 as $data2)
					{
						$i = $i + 1;
						$amount = Yii::app()->numberFormatter->formatCurrency($data2['amount'],'Rp.');
						$payamount = Yii::app()->numberFormatter->formatCurrency($data2['payamount'],'Rp.');
						$jatuhtempo = date(Yii::app()->params['dateviewfromdb'],strtotime($data2['jatuhtempo']));
						$tp = ($data2['paydays']);
						$umur = ($data2['umur']);
						$sales = ($data2['sales']);
						if ($i < 41) {
							$pesan1 = $pesan1 ."\n*{$i}.* {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} \n*Umur :* {$umur}, *TP :* {$tp} Hari, *Sales :* {$sales} \n";
						} else
						if ($i < 81) {
							$pesan2 = $pesan2 ."\n*{$i}.* {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} \n*Umur :* {$umur}, *TP :* {$tp} Hari, *Sales :* {$sales} \n";
						} else
						if ($i < 121) {
							$pesan3 = $pesan3 ."\n*{$i}.* {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} \n*Umur :* {$umur}, *TP :* {$tp} Hari, *Sales :* {$sales} \n";
						} else
						if ($i < 161) {
							$pesan4 = $pesan4 ."\n*{$i}.* {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} \n*Umur :* {$umur},*TP :* {$tp} Hari, *Sales :* {$sales} \n";
						} else
						if ($i < 201) {
							$pesan5 = $pesan5 ."\n*{$i}.* {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} \n*UT : {$umur}*, *TP :* {$tp} Hari, *Sales :* {$sales} \n";
						}
						$total += ($data2['amount'] - $data2['payamount']);
					}
					$totalamount = Yii::app()->numberFormatter->formatCurrency($total,'Rp.');
					if ($bankacc1 == "")
					{$norek1 = "";} else	{$norek1 = $bankacc1;}
					if ($bankacc2 == "")
					{$norek2 = "";} else	{$norek2 = $bankacc2;}
					if ($bankacc3 == "")
					{$norek3 = "";} else	{$norek3 = $bankacc3;}

					if ($data1['wanumber'] != '')
					{$sendtocustomer = "\n\n*_SUDAH TERKIRIM ke No WA Customer_* ".$data1['wanumber'];}
					else
					{$sendtocustomer = "\n\n*_BELUM TERKIRIM ke No WA Customer_*".$data1['wanumber'];}
				
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

					$wanumber = '6281717212109';
				
					if ($i < 41) {
						$wamessage1 = "*Pemberitahuan Piutang untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \nJumlah Piutang {$totalamount} \n\n*SEGERA DIPROSES / DITAGIH / DILUNASI, SESUAI SOP YANG BERLAKU*\n\nKarena ini adalah *PENJUALAN CASH*\n\nTerima kasih.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage1);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
			
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage1)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage1 ".$res."\n";
					} else
					
					if ($i < 81) {
						$wamessage2 = "*Pemberitahuan Piutang untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage2);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage2)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage2 ".$res."\n";
						
						$wamessage3 = "\n{$pesan2} \nJumlah Piutang {$totalamount} \n\n*SEGERA DIPROSES / DITAGIH / DILUNASI, SESUAI SOP YANG BERLAKU*\n\nKarena ini adalah *PENJUALAN CASH*\n\nTerima kasih.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage3);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage3)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage3 ".$res."\n";
					} else
					
					if ($i < 121) {
						$wamessage4 = "*Pemberitahuan Piutang untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage4);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage4)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage4 ".$res."\n";
						
						$wamessage5 = "\n{$pesan2} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage5);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage5)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage5 ".$res."\n";
						
						$wamessage6 = "\n{$pesan3} \nJumlah Piutang {$totalamount} \n\n*SEGERA DIPROSES / DITAGIH / DILUNASI, SESUAI SOP YANG BERLAKU*\n\nKarena ini adalah *PENJUALAN CASH*\n\nTerima kasih.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage6);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage6)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage6 ".$res."\n";
					} else
					
					if ($i < 161) {
						$wamessage7 = "*Pemberitahuan Piutang untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage7);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage7)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage7 ".$res."\n";
						
						$wamessage8 = "\n{$pesan2} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage8);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage8)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage8 ".$res."\n";
						
						$wamessage9 = "\n{$pesan3} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage9);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage9)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage9 ".$res."\n";
						
						$wamessage10 = "\n{$pesan4} \nJumlah Piutang {$totalamount} \n\n*SEGERA DIPROSES / DITAGIH / DILUNASI, SESUAI SOP YANG BERLAKU*\n\nKarena ini adalah *PENJUALAN CASH*\n\nTerima kasih.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage10);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage10)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage10 ".$res."\n";
					} else
					
					if ($i < 201) {
						$wamessage11 = "*Pemberitahuan Piutang untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage11);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage11)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage11 ".$res."\n";
						
						$wamessage12 = "\n{$pesan2} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage12);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage12)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage12 ".$res."\n";
						
						$wamessage13 = "\n{$pesan3} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage13);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage13)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage13 ".$res."\n";
						
						$wamessage14 = "\n{$pesan4} \n";

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage14);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
	
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage14)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage14 ".$res."\n";
						
						$wamessage15 = "\n{$pesan5} \nJumlah Piutang {$totalamount} \n\n*SEGERA DIPROSES / DITAGIH / DILUNASI, SESUAI SOP YANG BERLAKU*\n\nKarena ini adalah *PENJUALAN CASH*\n\nTerima kasih.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;

						//url dan kirim data untuk telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage15);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					
						//url dan kirim data untuk wa group
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($wamessage15)."&tujuan=".$wagroupid."@g.us",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_HTTPHEADER => array(
								"apikey: t0k3nb4ruwh4ts4k4"
							  ),
						));
						$res = curl_exec($ch);
						echo $companycode." ".$wagroupid." wamessage15 ".$res."\n";
					}
					
					sleep(15);
				}
			}
			curl_close($ch);
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