<?php
class Runningwatelereminderfa5Command extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			
			$msgtele = "Selamat Pagi%0ASIAGA (System Information AKA Group - Automatic) hanya ingin mengingatkan.%0A%0A<b>JANGAN LUPA MELAKUKAN PEMERIKSAAN JURNAL PENYUSUTAN AKTIVA TETAP MASING-MASING CABANG, SUDAH TANGGAL 5.</b>%0A%0ATerima Kasih.";
			$pesantelegram = $msgtele;
			
			$msgwa = "Selamat Pagi\nSIAGA (System Information AKA Group - Automatic) hanya ingin mengingatkan.\n\n*JANGAN LUPA MELAKUKAN PEMERIKSAAN JURNAL PENYUSUTAN AKTIVA TETAP MASING-MASING CABANG, SUDAH TANGGAL 5.*\n\nTerima Kasih.";
			$pesanwhatsapp = $msgwa;
			
			$sqluser = "select distinct wanumber,telegramid,username
						from useraccess a
						join usergroup b on b.useraccessid = a.useraccessid
						join groupaccess c on c.groupaccessid = b.groupaccessid
						join groupmenuauth d on d.groupaccessid=c.groupaccessid
						where (a.recordstatus = 1 and c.groupname like '%RASC%'
						and d.menuauthid = 5 and a.useraccessid <> 5
						and (a.telegramid <> '' or a.wanumber <> '')
						and a.useraccessid <> 3)
						or a.useraccessid in (476,493)
						-- id untuk abram dan suriatik
			";
			$datauser = Yii::app()->db->createCommand($sqluser)->queryAll();

			foreach($datauser as $user)
			{
				$teleuserid = $user['telegramid'];
				$wauserid = $user['wanumber'];
				$time = date('d-m-Y H:i:s');

				//send pesan telegram ke Chief Accounting
				$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$teleuserid."&text=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time);
				$ch = curl_init();
				$optArray = array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true
				);
				curl_setopt_array($ch, $optArray);
				$result = curl_exec($ch);
				echo $user['username'];


				//send pesan whatsapp ke Chief Accounting
				$url = Yii::app()->params['ip'].'send_message';
				$data = array(
					"phone_no"=> $wauserid,
					"key"		=> Yii::app()->params['key'],
					"message"	=> $pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time
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
				curl_exec($ch);
				echo $user['username'];
			}
			$time = date('d-m-Y H:i:s');

			//send pesan telegram ke ius.tan
			$url = Yii::app()->params['tele']."/sendMessage?chat_id=875856213&text=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time);
			$ch = curl_init();
			$optArray = array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true
			);
			curl_setopt_array($ch, $optArray);
			$result = curl_exec($ch);


/*			//send pesan whatsapp ke ius.tan
			$url = Yii::app()->params['ip'].'send_message';
			$data = array(
				"phone_no"=> '+6285265644828', //whatsapp ius.tan
				"key"		=> Yii::app()->params['key'],
				"message"	=> $pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time
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
			curl_exec($ch);
*/			
		//Whatsva
			$whatsvano = '6285376361879';
			
			$ch = curl_init();
			curl_setopt_array($ch, array(
			CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time)."&tujuan=".$whatsvano."@s.whatsapp.net",
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

			curl_close($ch);
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			echo $e->getMessage();
			GetMessage(true, $e->getMessage());
		}
	}
}