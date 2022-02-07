<?php
class RunningwatelereminderSTWhatsvaCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			//device-key
			$indosat = "d4987114-8563-4fdf-b15c-ed328057fae2";
			$siaga = "bf1ea6ba-ecc5-488e-9d6a-d75947ecebcf";
			$as = "";
			
			date_default_timezone_set('Asia/Jakarta');
			
			$msgtele = "Selamat Pagi%0ASIAGA (System Information AKA Group - Automatic) hanya ingin mengingatkan.%0A%0A<b>JANGAN LUPA MELAKUKAN POSTING DAN APPROVE TARGET PENJUALAN PERIODE BULAN DEPAN, SERTA MEMASTIKAN SEMUA SUDAH DI-POSTING DENGAN TEPAT & BENAR.</b>%0A%0A<i>Apabila sudah, abaikan pesan ini.</i>%0A%0ATerima Kasih.";
			$pesantelegram = $msgtele;
			
			$msgwa = "Selamat Pagi\nSIAGA (System Information AKA Group - Automatic) hanya ingin mengingatkan.\n\n*JANGAN LUPA MELAKUKAN POSTING DAN APPROVE TARGET PENJUALAN PERIODE BULAN DEPAN, SERTA MEMASTIKAN SEMUA SUDAH DI-POSTING DENGAN TEPAT & BENAR.*\n\n_Apabila sudah, abaikan pesan ini._\n\nTerima Kasih.";
			$pesanwhatsapp = $msgwa;
			
			$sql = "SELECT a.wanumber,a.telegramid
						FROM company a
						WHERE a.wanumber IS NOT NULL
						and a.companyid <> 11
						ORDER BY a.nourut
			";
			$data = Yii::app()->db->createCommand($sql)->queryAll();

			foreach($data as $row)
			{
				$teleuserid = $row['telegramid'];
				$wanumber = $row['wanumber'];
				$time = date('d-m-Y H:i:s');

				if ($teleuserid > 0)
				{
				//send pesan telegram ke Telegram Group
					$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$teleuserid."&text=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time);
					$ch = curl_init();
					$optArray = array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true
					);
					curl_setopt_array($ch, $optArray);
					$result = curl_exec($ch);
				}


				if ($wanumber > 0)
				{
				//send pesan whatsapp ke Whatsapp Group
					sendwajapri($siaga,$pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time,$wanumber);
				}
			}
/*			$time = date('d-m-Y H:i:s');

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
			
			sendwajapri($siaga,$pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time,$whatsvano);
*/		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			echo $e->getMessage();
			GetMessage(true, $e->getMessage());
		}
	}
}