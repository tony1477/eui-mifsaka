<?php
class RunningTeleWaReminderDiskonPelunasanWhatsvaCommand extends CConsoleCommand
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
							and a.nourut > 0
							-- and a.companyid in (20,18)
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
					
				$wanumber = '6281717212109';
				$whatsvano = '6281717212109';
				//$whatsvano = '628111777570'; //suwito

				if ($companyid == 1) {$telegroupid = "-1001435078485";} //AKA
				else if ($companyid == 11) {$telegroupid = "-1001435078485";} //UD1
				else if ($companyid == 12) {$telegroupid = "-1001435078485";} //UD2
				else if ($companyid == 21) {$telegroupid = "-1001196054232";} //AKS
				else if ($companyid == 17) {$telegroupid = "-1001211726344";} //AMIN 
				else if ($companyid == 20) {$telegroupid = "-1001442360059";} //AKM
				else if ($companyid == 18) {$telegroupid = "-1001257116233";} //AJM
				else if ($companyid == 7)  {$telegroupid = "-1001402373281";} //AMI
				else if ($companyid == 15) {$telegroupid = "-1001264861899";} //AGEM
				else if ($companyid == 14) {$telegroupid = "-1001406450805";} //AKP
				
				//device-key
				$indosat = "d4987114-8563-4fdf-b15c-ed328057fae2";
				$siaga = "bf1ea6ba-ecc5-488e-9d6a-d75947ecebcf";
				$as = "";
				
				//Reminder Invoice berpotensi mendapatkan tambahan diskon pelunasan 0-15 Hari
				$sql1 = "SELECT DISTINCT addressbookid,fullname,wanumber,telegramid
						FROM (SELECT DISTINCT a.addressbookid,a.fullname,replace((select aa.wanumber from addresscontact aa where aa.addressbookid=s.addressbookid Limit 1),'+','') as wanumber,(select aa.telegramid from addresscontact aa where aa.addressbookid=s.addressbookid Limit 1) as telegramid,datediff(date(now()),i.invoicedate) as umur
						FROM invoice i 
						JOIN giheader g ON g.giheaderid=i.giheaderid 
						JOIN soheader s ON s.soheaderid=g.soheaderid 
						JOIN addressbook a ON a.addressbookid=s.addressbookid
						WHERE i.recordstatus = 3 AND i.isbaddebt = 0 AND i.amount > i.payamount AND s.isdisplay = 0 AND i.companyid = {$companyid} AND a.isextern = 1
						AND s.addressbookid NOT IN (789,1344,2029,2030,2049,2675,3226,4533,4761,5064,5299,5525,5718,6037,6114)
						AND s.addressbookid NOT IN (SELECT DISTINCT addressbookid
						FROM (SELECT s2.addressbookid 
						,datediff(date(now()),i2.invoicedate) as umur2
						FROM invoice i2 
						JOIN giheader g2 ON g2.giheaderid=i2.giheaderid 
						JOIN soheader s2 ON s2.soheaderid=g2.soheaderid 
						WHERE i2.recordstatus = 3 AND i2.isbaddebt = 0 AND i2.amount > i2.payamount AND i2.companyid = {$companyid}
						HAVING umur2 > 15) z)
						HAVING umur <= 15
						ORDER BY a.fullname ASC) zz
				";
				$dataReader1=$connection->createCommand($sql1)->queryAll();
				
				foreach ($dataReader1 as $data1)
				{
					$telegramid = $data1['telegramid'];
					$pesan=""; $pesan1=""; $pesan2=""; $pesan3=""; $pesan4=""; $pesan5=""; $i=0; $total=0;
					$sql2 = "select *, (amount-payamount) as sisa,(amount) as nilai
							from (select b.giheaderid,if(c.isdisplay=1,concat(a.invoiceno,'_Display'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
						if(a.companyid = 18,if(a.invoicedate < '2021-09-01',date_add(a.invoicedate, INTERVAL e.paydays day),if(e.paydays < 30,date_add(a.invoicedate, INTERVAL e.paydays day),if(c.materialtypeid in (1,19,20,30,4,24,25,16,27,28,17,6),date_add(a.invoicedate, INTERVAL 45 day),if(c.materialtypeid in (14,15,22,3),date_add(a.invoicedate, INTERVAL 30 day),date_add(a.invoicedate, INTERVAL 45 day))))),date_add(a.invoicedate, INTERVAL e.paydays day)) as jatuhtempo,
							-- date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('{$enddate}',a.invoicedate) as umur,
						datediff('{$enddate}',date_add(a.invoicedate, INTERVAL if(a.companyid = 18,if(a.invoicedate < '2021-09-01',e.paydays,if(e.paydays < 30,e.paydays,if(c.materialtypeid in (1,19,20,30,4,24,25,16,27,28,17,6),45,if(c.materialtypeid in (14,15,22,3),30,45)))),e.paydays) DAY)) as umurtempo,
							-- datediff('{$enddate}',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,
							a.amount,ff.fullname as sales,
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
							and c.addressbookid = {$data1['addressbookid']}
							)z
							where amount > payamount
							order by umurtempo desc, invoiceno asc
					";
					$dataReader2=$connection->createCommand($sql2)->queryAll();
					
					foreach ($dataReader2 as $data2)
					{
						$i = $i + 1;
						$amount = Yii::app()->numberFormatter->formatCurrency($data2['amount'],'Rp.');
						$payamount = Yii::app()->numberFormatter->formatCurrency($data2['payamount'],'Rp.');
						$sisa = Yii::app()->numberFormatter->formatCurrency($data2['sisa'],'Rp.');
						$jatuhtempo = date(Yii::app()->params['dateviewfromdb'],strtotime($data2['jatuhtempo']));
						$umur = $data2['umur'];
						if ($i < 36) {
							$pesan1 = $pesan1 ."\n {$i}. {$data2['invoiceno']} Umur: {$umur} Hari\n{$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						} else
						if ($i < 71) {
							$pesan2 = $pesan2 ."\n {$i}. {$data2['invoiceno']} Umur: {$umur} Hari\n{$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						}
						$total += ($data2['amount'] - $data2['payamount']);
					}
					$totalamount = Yii::app()->numberFormatter->formatCurrency($total,'Rp.');
					if ($bankacc1 == "")
					{$norek1 = "";} else	{$norek1 = $bankacc1."\n";}
					if ($bankacc2 == "")
					{$norek2 = "";} else	{$norek2 = $bankacc2."\n";}
					if ($bankacc3 == "")
					{$norek3 = "";} else	{$norek3 = $bankacc3."\n";}

					if ($data1['wanumber'] > 0)
					{$sendtocustomer = "\n\n*_SUDAH TERKIRIM ke No WA Customer_* ".$data1['wanumber'];}
					else
					{$sendtocustomer = "\n\n*_BELUM ADA No WA Customer_*\n".$data1['fullname'];}
					
					$time = date('Y-m-d H:i:s');
				
					if ($i < 36) {
						$wamessage1 = "*TESTING PESAN*\n\n*REMINDER untuk Customer {$companycode} :*\n_{$data1['fullname']}_\n\nApabila anda melakukan Pelunasan dalam range umur 0-15 Hari dengan cara Kirim Uang (KU), anda berpotensi untuk mendapatkan *Tambahan Diskon Pelunasan* untuk Invoice berikut ini :\n\n_(Untuk lebih jelas dapat langsung menghubungi salesman kami)_\n{$pesan1} \nJumlah Invoice {$totalamount}\nBerpotensi Mendapatkan Tambahan Diskon Pelunasan\n\nAnda dapat melakukan pembayaran secara KU melalui No. Rek. sebagai berikut:\n{$norek1}\n_*Pembayaran KE REKENING SALES atau DILUAR DARI REKENING yang TELAH DITENTUKAN, maka dianggap TIDAK SAH / TIDAK DIAKUI sebagai PEMBAYARAN*_\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
/*						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage1,$data1['wanumber']);
							
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
*/	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage1,$whatsvano);

/*						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage1);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage1.$sendtocustomer);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
*/					}
					//sleep(5);
				}
				
				
				//Reminder Invoice berpotensi mendapatkan tambahan diskon pelunasan 16-30 Hari
				$sql1 = "SELECT DISTINCT addressbookid,fullname,wanumber,telegramid
						FROM (SELECT a.addressbookid,a.fullname,replace((select aa.wanumber from addresscontact aa where aa.addressbookid=s.addressbookid Limit 1),'+','') as wanumber,(select aa.telegramid from addresscontact aa where aa.addressbookid=s.addressbookid Limit 1) as telegramid,datediff(date(now()),i.invoicedate) as umur
						FROM invoice i 
						JOIN giheader g ON g.giheaderid=i.giheaderid 
						JOIN soheader s ON s.soheaderid=g.soheaderid 
						JOIN addressbook a ON a.addressbookid=s.addressbookid
						WHERE i.recordstatus = 3 AND i.isbaddebt = 0 AND i.amount > i.payamount AND s.isdisplay = 0 AND i.companyid = {$companyid} AND a.isextern = 1
						AND s.addressbookid NOT IN (789,1344,2029,2030,2049,2675,3226,4533,4761,5064,5299,5525,5718,6037,6114)
						AND s.addressbookid NOT IN (SELECT DISTINCT addressbookid
						FROM (SELECT s2.addressbookid 
						,datediff(date(now()),i2.invoicedate) as umur2
						FROM invoice i2 
						JOIN giheader g2 ON g2.giheaderid=i2.giheaderid 
						JOIN soheader s2 ON s2.soheaderid=g2.soheaderid 
						WHERE i2.recordstatus = 3 AND i2.isbaddebt = 0 AND i2.amount > i2.payamount AND i2.companyid = {$companyid}
						HAVING umur2 > 30) z)
						HAVING umur BETWEEN 16 AND 30
						ORDER BY a.fullname ASC) zz
				";
				$dataReader1=$connection->createCommand($sql1)->queryAll();
				
				foreach ($dataReader1 as $data1)
				{
					$telegramid = $data1['telegramid'];
					$pesan=""; $pesan1=""; $pesan2=""; $pesan3=""; $pesan4=""; $pesan5=""; $i=0; $total=0;
					$sql2 = "select *, (amount-payamount) as sisa,(amount) as nilai
							from (select b.giheaderid,if(c.isdisplay=1,concat(a.invoiceno,'_Display'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
						if(a.companyid = 18,if(a.invoicedate < '2021-09-01',date_add(a.invoicedate, INTERVAL e.paydays day),if(e.paydays < 30,date_add(a.invoicedate, INTERVAL e.paydays day),if(c.materialtypeid in (1,19,20,30,4,24,25,16,27,28,17,6),date_add(a.invoicedate, INTERVAL 45 day),if(c.materialtypeid in (14,15,22,3),date_add(a.invoicedate, INTERVAL 30 day),date_add(a.invoicedate, INTERVAL 45 day))))),date_add(a.invoicedate, INTERVAL e.paydays day)) as jatuhtempo,
							-- date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('{$enddate}',a.invoicedate) as umur,
						datediff('{$enddate}',date_add(a.invoicedate, INTERVAL if(a.companyid = 18,if(a.invoicedate < '2021-09-01',e.paydays,if(e.paydays < 30,e.paydays,if(c.materialtypeid in (1,19,20,30,4,24,25,16,27,28,17,6),45,if(c.materialtypeid in (14,15,22,3),30,45)))),e.paydays) DAY)) as umurtempo,
							-- datediff('{$enddate}',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,
							a.amount,ff.fullname as sales,
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
							and c.addressbookid = {$data1['addressbookid']}
							)z
							where amount > payamount
							order by umurtempo desc, invoiceno asc
					";
					$dataReader2=$connection->createCommand($sql2)->queryAll();
					
					foreach ($dataReader2 as $data2)
					{
						$i = $i + 1;
						$amount = Yii::app()->numberFormatter->formatCurrency($data2['amount'],'Rp.');
						$payamount = Yii::app()->numberFormatter->formatCurrency($data2['payamount'],'Rp.');
						$sisa = Yii::app()->numberFormatter->formatCurrency($data2['sisa'],'Rp.');
						$jatuhtempo = date(Yii::app()->params['dateviewfromdb'],strtotime($data2['jatuhtempo']));
						$umur = $data2['umur'];
						if ($i < 36) {
							$pesan1 = $pesan1 ."\n {$i}. {$data2['invoiceno']} Umur: {$umur} Hari\n{$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						} else
						if ($i < 71) {
							$pesan2 = $pesan2 ."\n {$i}. {$data2['invoiceno']} Umur: {$umur} Hari\n{$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						}
						$total += ($data2['amount'] - $data2['payamount']);
					}
					$totalamount = Yii::app()->numberFormatter->formatCurrency($total,'Rp.');
					if ($bankacc1 == "")
					{$norek1 = "";} else	{$norek1 = $bankacc1."\n";}
					if ($bankacc2 == "")
					{$norek2 = "";} else	{$norek2 = $bankacc2."\n";}
					if ($bankacc3 == "")
					{$norek3 = "";} else	{$norek3 = $bankacc3."\n";}

					if ($data1['wanumber'] > 0)
					{$sendtocustomer = "\n\n*_SUDAH TERKIRIM ke No WA Customer_* ".$data1['wanumber'];}
					else
					{$sendtocustomer = "\n\n*_BELUM ADA No WA Customer_*\n".$data1['fullname'];}
					
					$time = date('Y-m-d H:i:s');
				
					if ($i < 36) {
						$wamessage1 = "*TESTING PESAN*\n\n*REMINDER untuk Customer {$companycode} :*\n_{$data1['fullname']}_\n\nApabila anda melakukan Pelunasan dalam range umur 0-30 Hari dengan cara Kirim Uang (KU), anda berpotensi untuk mendapatkan *Tambahan Diskon Pelunasan* untuk Invoice berikut ini :\n\n_(Untuk lebih jelas dapat langsung menghubungi salesman kami)_\n{$pesan1} \nJumlah Invoice {$totalamount}\nBerpotensi Mendapatkan Tambahan Diskon Pelunasan\n\nAnda dapat melakukan pembayaran secara KU melalui No. Rek. sebagai berikut:\n{$norek1}{$norek2}{$norek3}\n_Pembayaran via Transfer diluar Rekening Resmi yang tertera diatas, *BUKAN MENJADI TANGGUNG JAWAB PERUSAHAAN AKA GROUP*\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
/*						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage1,$data1['wanumber']);
							
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
*/	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage1,$whatsvano);

/*						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage1);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage1.$sendtocustomer);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
*/					}
					//sleep(5);
				}
				
				
				//Reminder Invoice berpotensi mendapatkan tambahan diskon pelunasan 31-45 Hari
				$sql1 = "SELECT DISTINCT addressbookid,fullname,wanumber,telegramid
						FROM (SELECT a.addressbookid,a.fullname,replace((select aa.wanumber from addresscontact aa where aa.addressbookid=s.addressbookid Limit 1),'+','') as wanumber,(select aa.telegramid from addresscontact aa where aa.addressbookid=s.addressbookid Limit 1) as telegramid,datediff(date(now()),i.invoicedate) as umur
						FROM invoice i 
						JOIN giheader g ON g.giheaderid=i.giheaderid 
						JOIN soheader s ON s.soheaderid=g.soheaderid 
						JOIN addressbook a ON a.addressbookid=s.addressbookid
						WHERE i.recordstatus = 3 AND i.isbaddebt = 0 AND i.amount > i.payamount AND s.isdisplay = 0 AND i.companyid = {$companyid} AND a.isextern = 1
						AND s.addressbookid NOT IN (789,1344,2029,2030,2049,2675,3226,4533,4761,5064,5299,5525,5718,6037,6114)
						AND s.addressbookid NOT IN (SELECT DISTINCT addressbookid
						FROM (SELECT s2.addressbookid 
						,datediff(date(now()),i2.invoicedate) as umur2
						FROM invoice i2 
						JOIN giheader g2 ON g2.giheaderid=i2.giheaderid 
						JOIN soheader s2 ON s2.soheaderid=g2.soheaderid 
						WHERE i2.recordstatus = 3 AND i2.isbaddebt = 0 AND i2.amount > i2.payamount AND i2.companyid = {$companyid}
						HAVING umur2 > 45) z)
						HAVING umur BETWEEN 31 AND 45
						ORDER BY a.fullname ASC) zz
				";
				$dataReader1=$connection->createCommand($sql1)->queryAll();
				
				foreach ($dataReader1 as $data1)
				{
					$telegramid = $data1['telegramid'];
					$pesan=""; $pesan1=""; $pesan2=""; $pesan3=""; $pesan4=""; $pesan5=""; $i=0; $total=0;
					$sql2 = "select *, (amount-payamount) as sisa,(amount) as nilai
							from (select b.giheaderid,if(c.isdisplay=1,concat(a.invoiceno,'_Display'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
						if(a.companyid = 18,if(a.invoicedate < '2021-09-01',date_add(a.invoicedate, INTERVAL e.paydays day),if(e.paydays < 30,date_add(a.invoicedate, INTERVAL e.paydays day),if(c.materialtypeid in (1,19,20,30,4,24,25,16,27,28,17,6),date_add(a.invoicedate, INTERVAL 45 day),if(c.materialtypeid in (14,15,22,3),date_add(a.invoicedate, INTERVAL 30 day),date_add(a.invoicedate, INTERVAL 45 day))))),date_add(a.invoicedate, INTERVAL e.paydays day)) as jatuhtempo,
							-- date_add(a.invoicedate,interval e.paydays day) as jatuhtempo,
							datediff('{$enddate}',a.invoicedate) as umur,
						datediff('{$enddate}',date_add(a.invoicedate, INTERVAL if(a.companyid = 18,if(a.invoicedate < '2021-09-01',e.paydays,if(e.paydays < 30,e.paydays,if(c.materialtypeid in (1,19,20,30,4,24,25,16,27,28,17,6),45,if(c.materialtypeid in (14,15,22,3),30,45)))),e.paydays) DAY)) as umurtempo,
							-- datediff('{$enddate}',date_add(a.invoicedate, INTERVAL e.paydays DAY)) as umurtempo,
							a.amount,ff.fullname as sales,
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
							and c.addressbookid = {$data1['addressbookid']}
							)z
							where amount > payamount
							order by umurtempo desc, invoiceno asc
					";
					$dataReader2=$connection->createCommand($sql2)->queryAll();
					
					foreach ($dataReader2 as $data2)
					{
						$i = $i + 1;
						$amount = Yii::app()->numberFormatter->formatCurrency($data2['amount'],'Rp.');
						$payamount = Yii::app()->numberFormatter->formatCurrency($data2['payamount'],'Rp.');
						$sisa = Yii::app()->numberFormatter->formatCurrency($data2['sisa'],'Rp.');
						$jatuhtempo = date(Yii::app()->params['dateviewfromdb'],strtotime($data2['jatuhtempo']));
						$umur = $data2['umur'];
						if ($i < 36) {
							$pesan1 = $pesan1 ."\n {$i}. {$data2['invoiceno']} Umur: {$umur} Hari\n{$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						} else
						if ($i < 71) {
							$pesan2 = $pesan2 ."\n {$i}. {$data2['invoiceno']} Umur: {$umur} Hari\n{$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						}
						$total += ($data2['amount'] - $data2['payamount']);
					}
					$totalamount = Yii::app()->numberFormatter->formatCurrency($total,'Rp.');
					if ($bankacc1 == "")
					{$norek1 = "";} else	{$norek1 = $bankacc1."\n";}
					if ($bankacc2 == "")
					{$norek2 = "";} else	{$norek2 = $bankacc2."\n";}
					if ($bankacc3 == "")
					{$norek3 = "";} else	{$norek3 = $bankacc3."\n";}

					if ($data1['wanumber'] > 0)
					{$sendtocustomer = "\n\n*_SUDAH TERKIRIM ke No WA Customer_* ".$data1['wanumber'];}
					else
					{$sendtocustomer = "\n\n*_BELUM ADA No WA Customer_*\n".$data1['fullname'];}
					
					$time = date('Y-m-d H:i:s');
				
					if ($i < 36) {
						$wamessage1 = "*TESTING PESAN*\n\n*REMINDER untuk Customer {$companycode} :*\n_{$data1['fullname']}_\n\nApabila anda melakukan Pelunasan dalam range umur 0-45 Hari dengan cara Kirim Uang (KU), anda berpotensi untuk mendapatkan *Tambahan Diskon Pelunasan* untuk Invoice berikut ini :\n\n_(Untuk lebih jelas dapat langsung menghubungi salesman kami)_\n{$pesan1} \nJumlah Invoice {$totalamount}\nBerpotensi Mendapatkan Tambahan Diskon Pelunasan\n\nAnda dapat melakukan pembayaran secara KU melalui No. Rek. sebagai berikut:\n{$norek1}{$norek2}{$norek3}\n_Pembayaran via Transfer diluar Rekening Resmi yang tertera diatas, *BUKAN MENJADI TANGGUNG JAWAB PERUSAHAAN AKA GROUP*\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
/*						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage1,$data1['wanumber']);
							
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
*/	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage1,$whatsvano);

/*						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage1);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage1.$sendtocustomer);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
*/					}
					//sleep(5);
				}
			}
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			GetMessage(true, $e->getMessage());
			echo $e->getMessage();
		}
	}
}