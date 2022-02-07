<?php
class RunningWaStatusChatfireCommand extends CConsoleCommand
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
			
			//$message = "Status WA Otomatis: *PHONE ONLINE* ".$time;
			$message = "Status WA Otomatis: *PHONE ONLINE*\n\n".$time;
			
			  echo "\n";
			
			$ch = curl_init();
				
/*			//url dan kirim data untuk wa japri ke ADS
		//Whatsva
			$whatsvano = '6285265644828';

			curl_setopt_array($ch, [
			  CURLOPT_URL => "https://chat.sinargemilang.com/api/messages/send-text",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_SSL_VERIFYHOST => false,
			  CURLOPT_SSL_VERIFYPEER => false,
			  CURLOPT_POSTFIELDS => array(
				  'to' => $whatsvano,
				  'message' => urldecode($message),
				  'reply_for' => 0
			  ),
			  CURLOPT_HTTPHEADER => array(
				"device-key: d4987114-8563-4fdf-b15c-ed328057fae2"
			  ),
			]);

			$response = curl_exec($ch);
			$err = curl_error($ch);
			  echo $response."\n\n";
			if ($err) {
			  echo "cURL Error #:" . $err;
			}
			
			//url dan kirim data untuk wa japri ke MRT
		//Whatsva
			$whatsvano = '6285376361879';

			curl_setopt_array($ch, [
			  CURLOPT_URL => "https://chat.sinargemilang.com/api/messages/send-text",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_SSL_VERIFYHOST => false,
			  CURLOPT_SSL_VERIFYPEER => false,
			  CURLOPT_POSTFIELDS => array(
				  'to' => $whatsvano,
				  'message' => urldecode($message),
				  'reply_for' => 0
			  ),
			  CURLOPT_HTTPHEADER => array(
				"device-key: d4987114-8563-4fdf-b15c-ed328057fae2"
			  ),
			]);

			$response = curl_exec($ch);
			$err = curl_error($ch);
			  echo $response."\n\n";
			if ($err) {
			  echo "cURL Error #:" . $err;
			}
*/

			


				
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
			
			sendwagroup("d4987114-8563-4fdf-b15c-ed328057fae2",$message."\n\n_Tes Chatfire_",$whatsvagroup);
/*			curl_setopt_array($ch, [
			  CURLOPT_URL => "https://chat.sinargemilang.com/api/messages/send-text",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_SSL_VERIFYHOST => false,
			  CURLOPT_SSL_VERIFYPEER => false,
			  CURLOPT_POSTFIELDS => array(
				  'to' => $whatsvagroup,
				  'message' => urldecode($message),
				  'reply_for' => 0
			  ),
			  CURLOPT_HTTPHEADER => array(
				"device-key: d4987114-8563-4fdf-b15c-ed328057fae2"
			  ),
			]);

			$response = curl_exec($ch);
			$err = curl_error($ch);
			  echo $response."\n\n";
			if ($err) {
			  echo "cURL Error #:" . $err;
			}
*/			
			
			curl_close($ch);
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
      GetMessage(true, $e->getMessage());
      echo $e->getMessage();
		}
	}
}
