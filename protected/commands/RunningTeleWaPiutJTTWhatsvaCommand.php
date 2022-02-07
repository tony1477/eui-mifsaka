<?php
class RunningTeleWaPiutJTTWhatsvaCommand extends CConsoleCommand
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
				$sql1 = "select addressbookid,fullname,replace((select aa.wanumber from addresscontact aa where aa.addressbookid=z.addressbookid Limit 1),'+','') as wanumber,(select aa.telegramid from addresscontact aa where aa.addressbookid=z.addressbookid Limit 1) as telegramid
						from (select c.addressbookid,b.giheaderid,if(c.isdisplay=1,concat(a.invoiceno,'_Display'),a.invoiceno) as invoiceno,a.invoicedate,e.paydays,
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
						and a.invoicedate <= '{$enddate}'
						)z
						where amount > payamount
						and umurtempo >= 0
						-- AND addressbookid = 7580
						-- AND addressbookid in (42,50,1501,7149,6433,613,4720,6342,394,3520,7294,6441,7863,3713,2957,8246,8172,3152,420,7517,648,837,7378,1951,1948,2621,8226,3140,233,443,738,5340,6393,6360,4157,8222,525,7157,5770,5864,1958,1957,304,4606,5301,1744,325,327,603,1331,306,5278,8220,8258,121,8257,122,5735,4647,6508,384,6999,5251,4400,406,261,5852,6402,1934,1943,7646,7020,315,608,818,639,1327,4840,8195,8336,7647,7151,8356,2961,6396,6553,3452,1344,3079,8225,4801,6514,524,516,8230,853,7795,6075,43,672,4765,4769,3070,5778,423,7772,7377,6973,870,3005,413,3871,7887,7577,7575,8248,5227,5733,3151,4083,5781,5579,4656,145,7985,2941,5311,4132,7071,5795,1737,3071,2835,6092,559,1342,1772,3877,4529,1788)
						group by fullname
						order by wanumber desc, fullname asc
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
							and a.invoicedate <= '{$enddate}'
							and c.addressbookid = {$data1['addressbookid']}
							)z
							where amount > payamount
							and umurtempo >= 0
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
						if ($i < 36) {
							$pesan1 = $pesan1 ."\n {$i}. {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						} else
						if ($i < 71) {
							$pesan2 = $pesan2 ."\n {$i}. {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						} else
						if ($i < 106) {
							$pesan3 = $pesan3 ."\n {$i}. {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						} else
						if ($i < 141) {
							$pesan4 = $pesan4 ."\n {$i}. {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						} else
						if ($i < 176) {
							$pesan5 = $pesan5 ."\n {$i}. {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						} else
						if ($i < 211) {
							$pesan5 = $pesan6 ."\n {$i}. {$data2['invoiceno']} Tgl. JTT. {$jatuhtempo} {$amount} Kum. Bayar {$payamount} Sisa {$sisa} \n";
						}
						$total += ($data2['amount'] - $data2['payamount']);
					}
					$totalamount = Yii::app()->numberFormatter->formatCurrency($total,'Rp.');
					if ($bankacc1 == "")
					{$norek1 = "";} else	{$norek1 = $bankacc1."\n";}
/*					if ($bankacc2 == "")
					{$norek2 = "";} else	{$norek2 = $bankacc2."\n";}
					if ($bankacc3 == "")
					{$norek3 = "";} else	{$norek3 = $bankacc3."\n";}
*/
					if ($data1['wanumber'] > 0)
					{$sendtocustomer = "\n\n*_SUDAH TERKIRIM ke No WA Customer_* ".$data1['wanumber'];}
					else
					{$sendtocustomer = "\n\n*_BELUM ADA No WA Customer_*\n".$data1['fullname'];}
					
					$wanumber = '6281717212109';
					$whatsvano = '6281717212109';

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
					
					$time = date('Y-m-d H:i:s');
				
					if ($i < 36) {
						$wamessage1 = "*Pemberitahuan Piutang Telah Jatuh Tempo untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \nJumlah Piutang Telah Jatuh Tempo {$totalamount} \n\nAnda bisa melakukan pembayaran melalui No. Rek. sebagai berikut:\n{$norek1}\n_*Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan.*_\n\nPembayaran via Transfer diluar Rekening Resmi yang yang telah ditentukan, *BUKAN MENJADI TANGGUNG JAWAB PERUSAHAAN AKA GROUP*\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage1,$data1['wanumber']);
							
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage1,$whatsvano);

						if ($telegramid > 0)
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
					} else
					
					if ($i < 71) {
						$wamessage2 = "*Pemberitahuan Piutang Telah Jatuh Tempo untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage2,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage2,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage2);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage2);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage3 = "\n{$pesan2} \nJumlah Piutang Telah Jatuh Tempo {$totalamount} \n\nAnda bisa melakukan pembayaran melalui No. Rek. sebagai berikut:\n{$norek1}\n_*Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan.*_\n\nPembayaran via Transfer diluar Rekening Resmi yang yang telah ditentukan, *BUKAN MENJADI TANGGUNG JAWAB PERUSAHAAN AKA GROUP*\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage3,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
					
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage3,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage3);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage3.$sendtocustomer);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					} else
					
					if ($i < 106) {
						$wamessage4 = "*Pemberitahuan Piutang Telah Jatuh Tempo untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage4,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage4,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage4);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage4);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage5 = "\n{$pesan2} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage5,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage5,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage5);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage5);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage6 = "\n{$pesan3} \nJumlah Piutang Telah Jatuh Tempo {$totalamount} \n\nAnda bisa melakukan pembayaran melalui No. Rek. sebagai berikut:\n{$norek1}\n_*Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan.*_\n\nPembayaran via Transfer diluar Rekening Resmi yang yang telah ditentukan, *BUKAN MENJADI TANGGUNG JAWAB PERUSAHAAN AKA GROUP*\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage6,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
					
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage6,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage6);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage6.$sendtocustomer);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					} else
					
					if ($i < 141) {
						$wamessage7 = "*Pemberitahuan Piutang Telah Jatuh Tempo untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage7,$data1['wanumber']);
							//echo $companycode." ".$data1['fullname']." ".$data1['wanumber']." wamessage7 ".$res.". \n";
							if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage7,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage7);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage7);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage8 = "\n{$pesan2} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage8,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage8,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage8);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage8);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage9 = "\n{$pesan3} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage9,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage9,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage9);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage9);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage10 = "\n{$pesan4} \nJumlah Piutang Telah Jatuh Tempo {$totalamount} \n\nAnda bisa melakukan pembayaran melalui No. Rek. sebagai berikut:\n{$norek1}\n_*Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan.*_\n\nPembayaran via Transfer diluar Rekening Resmi yang yang telah ditentukan, *BUKAN MENJADI TANGGUNG JAWAB PERUSAHAAN AKA GROUP*\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage10,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
					
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage10,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage10);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage10.$sendtocustomer);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					} else
					
					if ($i < 176) {
						$wamessage11 = "*Pemberitahuan Piutang Telah Jatuh Tempo untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage11,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage11,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage11);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage11);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage12 = "\n{$pesan2} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage12,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage12,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage12);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage12);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage13 = "\n{$pesan3} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage13,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage13,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage13);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage13);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage14 = "\n{$pesan4} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage14,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage14,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage14);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage14);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage15 = "\n{$pesan5} \nJumlah Piutang Telah Jatuh Tempo {$totalamount} \n\nAnda bisa melakukan pembayaran melalui No. Rek. sebagai berikut:\n{$norek1}\n_*Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan.*_\n\nPembayaran via Transfer diluar Rekening Resmi yang yang telah ditentukan, *BUKAN MENJADI TANGGUNG JAWAB PERUSAHAAN AKA GROUP*\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage15,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
					
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage15,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage15);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage15.$sendtocustomer);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					} else
					
					if ($i < 211) {
						$wamessage16 = "*Pemberitahuan Piutang Telah Jatuh Tempo untuk Customer {$companycode} :*\n_{$data1['fullname']}._ \n{$pesan1} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage16,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage16,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage16);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage16);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage17 = "\n{$pesan2} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage17,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage17,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage17);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage17);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage18 = "\n{$pesan3} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage18,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage18,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage18);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage18);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage19 = "\n{$pesan4} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage19,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage19,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage19);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage19);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage20 = "\n{$pesan5} \n";
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage20,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
	
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage20,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage20);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage20);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
						
						$wamessage21 = "\n{$pesan6} \nJumlah Piutang Telah Jatuh Tempo {$totalamount} \n\nAnda bisa melakukan pembayaran melalui No. Rek. sebagai berikut:\n{$norek1}\n_*Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan.*_\n\nPembayaran via Transfer diluar Rekening Resmi yang yang telah ditentukan, *BUKAN MENJADI TANGGUNG JAWAB PERUSAHAAN AKA GROUP*\nTerima kasih.\n\nApabila ada yang *Tidak Sesuai*\nSilahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
						
						if ($data1['wanumber'] > 0)
						{
						//url dan kirim data untuk wa ke customer
							sendwajapri($siaga,$wamessage21,$data1['wanumber']);
							//if ($res != '{"success":true,"message":"berhasil"}') {if ($data1['wanumber'] > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$data1['wanumber']." (".$row['fullname'].")\n";}}}
						}
					
					//url dan kirim data untuk wa japri
						sendwajapri($siaga,$wamessage21,$whatsvano);
						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($wamessage21);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
						
					//url kirim telegram ke group
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($wamessage21.$sendtocustomer);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					}
					
					sleep(5);
				}
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