<?php
class Runningwatelereminderar25WhatsvaCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			
			$msgtele = "Selamat Pagi%0ASIAGA (System Information AKA Group - Automatic) hanya ingin mengingatkan.%0A%0A<b>JANGAN LUPA OPNAME FAKTUR SUDAH TANGGAL 25.</b>%0A%0ATerima Kasih.";
			$pesantelegram = $msgtele;
			
			$msgwa = "Selamat Pagi\nSIAGA (System Information AKA Group - Automatic) hanya ingin mengingatkan.\n\n*JANGAN LUPA OPNAME FAKTUR SUDAH TANGGAL 25.*\n\nTerima Kasih.";
			$pesanwhatsapp = $msgwa;
			
			$sqluser = "select distinct replace(wanumber,'+','') as wanumber,telegramid,username
						from useraccess a
						join usergroup b on b.useraccessid = a.useraccessid
						join groupaccess c on c.groupaccessid = b.groupaccessid
						join groupmenuauth d on d.groupaccessid=c.groupaccessid
						where a.recordstatus = 1 and c.groupname like '%Chief Accounting%'
						and d.menuauthid = 5 and a.useraccessid <> 5
						and (a.telegramid <> '' or a.wanumber <> '')
						and a.useraccessid <> 3
					union
						select distinct replace(wanumber,'+','') as wanumber,telegramid,username
						from useraccess a
						join usergroup b on b.useraccessid = a.useraccessid
						join groupaccess c on c.groupaccessid = b.groupaccessid
						join groupmenuauth d on d.groupaccessid=c.groupaccessid
						where a.recordstatus = 1 and c.groupname like '%Account Receivable%'
						and d.menuauthid = 5 and a.useraccessid <> 5
						and (a.telegramid <> '' or a.wanumber <> '')
						and a.useraccessid <> 3
						-- Limit 1
			";
			$datauser = Yii::app()->db->createCommand($sqluser)->queryAll();

			foreach($datauser as $user)
			{
				$teleuserid = $user['telegramid'];
				$wauserid = $user['wanumber'];
				$time = date('d-m-Y H:i:s');

				if ($teleuserid > 0)
				{
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
				}


				if ($wauserid > 0)
				{
				//send pesan whatsapp ke Chief Accounting
					$ch = curl_init();
					curl_setopt_array($ch, array(
					CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time)."&tujuan=".$wauserid."@s.whatsapp.net",
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
					echo $user['username']." ".$wauserid." ".$res."\n";
				}
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


		//send pesan whatsapp ke ius.tan
			$whatsvano = '6281717212109';
			
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
			echo "ius.tan ".$whatsvano." ".$res."\n";

			curl_close($ch);
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			echo $e->getMessage();
			GetMessage(true, $e->getMessage());
		}
	}
}