<?php
class RunningteletesuserCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			$time = date('d-m-Y H:i:s');
			
			$msg = "<b><i>Selamat Siang%0ASaya adalah SIAGA (System Information AKA Group - Automatic)%0AMaaf mengganggu, dalam proses pengecekan.%0ATerima Kasih.</i></b>%0A".$time;
			$pesantelegram = $msg;
			$pesantelegram = "**KONFIRMASI PELUNASAN PIUTANG**%0ATerima kasih atas pelunasan Customer AKA :%0A*PT. ANUGRAH KARYA ASLINDO 1* Pada Tanggal 19-01-2021 No. BPD-21A00209 dengan rincian sebagai berikut : 1. INV-21A00743 Rp. 400.000,00 Kum. Bayar : Rp. 0,00 Saldo Invoice : Rp. 400.000,00 _Pelunasan secara_ : _OB_ : Rp. 400.000,00 Sisa : Rp. 0,00 *LUNAS* *Apabila :* 1. Sudah Sesuai, abaikan pesan ini. 2. Tidak Sesuai, silahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini. Terima kasih atas perhatian dan kerjasama pelanggan setia AKA Group. *JANGAN BALAS KE NO WA INI !!!* _*Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_ 2021-01-19 15:54:25";
			$pesantelegram = "Testing";
			
			$sqluser = "select distinct telegramid,username
						from useraccess a
						join usergroup b on b.useraccessid = a.useraccessid
						join groupaccess c on c.groupaccessid = b.groupaccessid
						join groupmenuauth d on d.groupaccessid=c.groupaccessid
						where a.recordstatus = 1 and c.groupname like '%%'
						and d.menuauthid = 5 and a.useraccessid <> 5
						and a.telegramid <> ''
						 Limit 1
			";
			$datauser = Yii::app()->db->createCommand($sqluser)->queryAll();
			
			$teleid = "875856213"; //user ius.tan
			
			//$teleid = "-1001435078485"; //AKA
			//$teleid = "-1001435078485"; //UD1
			//$teleid = "-1001435078485"; //UD2
			//$teleid = "-1001196054232"; //AKS
			//$teleid = "-1001211726344"; //AMIN 
			//$teleid = "-1001442360059"; //AKM
			//$teleid = "-1001257116233"; //AJM
			//$teleid = "-1001402373281"; //AMI
			//$teleid = "-1001264861899"; //AGEM
			//$teleid = "-1001406450805"; //AKP
			//$teleid = "-1001152080596"; //group Top Management
			//$teleid = "-1001154554937"; //group Kangaroo Excellent
			//$teleid = "-1001242260468"; //group CA - Audit, Acc & Sys AKA-Grup Discuss

			foreach($datauser as $teleuser)
			{
/*				//send file telegram ke Chief Accounting
				$teleuserid = $teleid;
				$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$teleuserid."&text=".json_encode($pesantelegram);
				$ch = curl_init($url);
				curl_exec ($ch);
				echo $teleuser['username'];
				*/
				
				$wano = '6285265644828';
				//$wano = '0';
				
				$ch = curl_init();

				curl_setopt_array($ch, array(
				  CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($pesantelegram)."&tujuan=".$wano."@s.whatsapp.net",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_CONNECTTIMEOUT => 0,
				  CURLOPT_VERBOSE => 0,
				  CURLOPT_SSL_VERIFYPEER => 0,
				  CURLOPT_SSL_VERIFYHOST => 0,
				  CURLOPT_HTTPHEADER => array(
					"apikey: t0k3nb4ruwh4ts4k4"
				  ),
				));

				$res = curl_exec($ch);
				echo $res;
				
/*				$url = Yii::app()->params['ip'].'send_message';
						$datamessage = array(
							"phone_no"=> $wanumber,
							"key"		=> Yii::app()->params['key'],
							"message"	=> $wamessage21.$sendtocustomer
						);
						$data_string = json_encode($datamessage);

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
						//echo
						$res=curl_exec($ch);*/
			}
			curl_close($ch);
			//curl_close($curl);
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			echo $e->getMessage();
			GetMessage(true, $e->getMessage());
		}
	}
}