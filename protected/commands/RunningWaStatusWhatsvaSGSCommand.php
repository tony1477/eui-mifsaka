<?php
class RunningWaStatusWhatsvaSGSCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			$time = date('Y-m-d H:i:s');
				
			//$wanumber = '+6285265644828';
			//$wanumber = '+6285888885050';
			//$wanumber = '+6281717212109';
			//$wanumber = '+62817626210'; //noce
			//$wanumber = '+6288111777570'; //suwito
			//$wanumber = '+6285376361879'; //martoni
			//$wanumber = '+628127090802'; //sriwanti
			//$wanumber = '+6281290909041'; //yuliana
			//$wanumber = '+6282375443299'; //arie setiawan
			//$wanumber = '+6281315265569'; //indra gunawan
				
			//url dan kirim data untuk wa japri ke ADS
		//Whatsva
			$whatsvano = '6285265644828';
			
			sendwajapri(2,"Status WA Otomatis: *PHONE ONLINE*\n\n".$time,$whatsvano);
			
			//url dan kirim data untuk wa japri ke MRT
		//Whatsva
			$whatsvano = '6285376361879';
			
			sendwajapri(2,"Status WA Otomatis: *PHONE ONLINE*\n\n".$time,$whatsvano);
				
			//url dan kirim data untuk wa group
		//Whatsva
			$whatsvagroup = '6281717212109-1615804565';
			//$whatsvagroup = '6285265644828-1483401177'; //AKA
			//$whatsvagroup = '6287875097026-1527279238'; //AMIN
			//$whatsvagroup = '6285265644828-1547688422'; //AKM
			//$whatsvagroup = '6281378010952-1533358281'; //AJM
			//$whatsvagroup = '6285265644828-1557388538'; //AMI
			//$whatsvagroup = '6285265644828-1539936063'; //AKP
			//$whatsvagroup = '6285265644828-1461984841'; //EXCELLENT
			
/*			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => "https://wa.sinargemilangsolutions.tech/api/sendText?id_device=1&message=".urlencode("Status WA Otomatis: *PHONE ONLINE*\n\n".$time)."&tujuan=".$whatsvagroup."@g.us",
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
			echo $res;
				
			//url dan kirim data untuk wa japri ke Febri
			$url = Yii::app()->params['ip'].'send_message';
			$datamessage = array(
				"phone_no"=> '+6281318732207',
				"key"		=> Yii::app()->params['key'],
				"message"	=> 'Status WA Otomatis: *PHONE ONLINE*\n\n'.$time
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
			echo $res=curl_exec($ch);
				
			//url dan kirim data untuk wa japri ke Martoni
			$url = Yii::app()->params['ip'].'send_message';
			$datamessage = array(
				"phone_no"=> '+6285376361879',
				"key"		=> Yii::app()->params['key'],
				"message"	=> 'Status WA Otomatis: *PHONE ONLINE*\n\n'.$time
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
			echo $res=curl_exec($ch);
			
			curl_close($ch);
*/		}
		catch(Exception $e) // an exception is raised if a query fails
		{
      GetMessage(true, $e->getMessage());
      echo $e->getMessage();
		}
	}
}