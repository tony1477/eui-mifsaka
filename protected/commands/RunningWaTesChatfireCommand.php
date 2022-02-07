<?php
class RunningWaTesChatfireCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			$time = date('Y-m-d H:i:s');
			  echo "\n";
				
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
			
			//device-key
			$indosat = "d4987114-8563-4fdf-b15c-ed328057fae2";
			$siaga = "bf1ea6ba-ecc5-488e-9d6a-d75947ecebcf";
			$as = "";
			
			$message = "Testing Kedua *BROADCAST GAMBAR*\n\n".$time."\n_Dari SIAGA_";
			
			//$ch = curl_init();
				
			//url dan kirim data untuk wa japri ke ADS
		//Whatsva
			$phonenumber = "6285376361879"; //martoni
			//$phonenumber = "628127090802"; //sriwanti
			$groupid = "6281717212109-1615804565";
			$urlimage = "http://mifsaka.com/public/logo-aka_group.jpg";
			$urlvideo = "http://sinargemilang.com/video.mp4";
			$titledocument = "WAJIB VAKSIN";
			$urldocument = "http://sinargemilang.com/media/surat_edaran.pdf";
			
		/*sendwajapri($devicekey,$message,$phonenumber)*/
//			sendwajapri($siaga,$message,$phonenumber);
			
		/*sendwagroup($devicekey,$message,$groupid)*/
//			sendwagroup($siaga,$message,$groupid);
			
		/*sendwaimage($devicekey,$message,$phonenumber,$urlimage)*/
//			sendwaimage($siaga,$message,$phonenumber,$urlimage);
//			sendwaimage($siaga,$message,$phonenumber,$urlimage);
//			sendwaimage($siaga,$message,$phonenumber,$urlimage);
//			sendwaimage($siaga,$message,"628127090802",$urlimage);
//			sendwaimage($siaga,$message,"628127090802",$urlimage);
//			sendwaimage($siaga,$message,"628127090802",$urlimage);
			sendwaimage($siaga,$message,"6281717212109",$urlimage);
//			sendwaimage($siaga,$message,"6281717212109",$urlimage);
//			sendwaimage($siaga,$message,"6281717212109",$urlimage);
//			sendwaimage($siaga,$message,"62817626210",$urlimage);
//			sendwaimage($siaga,$message,"62817626210",$urlimage);
//			sendwaimage($siaga,$message,"62817626210",$urlimage);
			
		/*sendwavideo($devicekey,$message,$phonenumber,$urlvideo)*/
//			sendwavideo($siaga,$message,$groupid,$urlvideo);
			
		/*sendwadocument($devicekey,$titledocument,$phonenumber,$urldocument)*/
//			sendwadocument($siaga,$titledocument,$groupid,$urldocument);
			
/* curl_setopt_array($ch, array(
     CURLOPT_URL => 'https://chat.sinargemilang.com/api/messages/send-media',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_SSL_VERIFYHOST => false,
     CURLOPT_SSL_VERIFYPEER => false,
     CURLOPT_POSTFIELDS => array(
			'to' => "628127090802",
			'message' => urldecode("SURAT"),
				  'media_url' => "http://sinargemilang.com/ktp_blkng.jpg",
				  'type' => "image",
			'reply_for' => 0
	),
     CURLOPT_HTTPHEADER => array(
       'device-key: d4987114-8563-4fdf-b15c-ed328057fae2',
     ),
   ));

			$response = curl_exec($ch);
			$err = curl_error($ch);
			  echo $response."\n\n";
*/			
			
/* curl_setopt_array($ch, array(
     CURLOPT_URL => 'http://akagroup.co.id:8999/api/messages/send-text',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_SSL_VERIFYHOST => false,
     CURLOPT_SSL_VERIFYPEER => false,
     CURLOPT_POSTFIELDS => array(
			'to' => "6285265644828",
			'message' => urldecode($message),
			'reply_for' => 0
	),
     CURLOPT_HTTPHEADER => array(
       'device-key: fb13f6f1-5837-444c-9279-d90a783bca8d',
     ),
   ));

			$response = curl_exec($ch);
			$err = curl_error($ch);
			  echo $response."\n\n";
*/			
/*			//url dan kirim data untuk wa japri ke MRT
		//Whatsva
			$whatsvano = '6285376361879';

			curl_setopt_array($ch, [
			  CURLOPT_URL => "https://chat.sinargemilang.com/api/messages/send-text",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => '{
				  "to": "'.$whatsvano.'",
				  "message": "'.$message.'",
				  "reply_for": "0"
				  }',
			  CURLOPT_HTTPHEADER => [
				"Content-Type: application/json",
				"device-key: d4987114-8563-4fdf-b15c-ed328057fae2"
			  ],
			]);

			$response = curl_exec($ch);
			$err = curl_error($ch);


			


				
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

			curl_setopt_array($ch, [
			  CURLOPT_URL => "https://chat.sinargemilang.com/api/messages/send-text",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => '{
				  "to": "'.$whatsvagroup.'",
				  "message": "'.$message.'",
				  "reply_for": "0"
				  }',
			  CURLOPT_HTTPHEADER => [
				"Content-Type: application/json",
				"device-key: d4987114-8563-4fdf-b15c-ed328057fae2"
			  ],
			]);

			$response = curl_exec($ch);
			$err = curl_error($ch);
*/			
			
//			curl_close($ch);

//			if ($err) {
	//		  echo "cURL Error #:" . $err;
		//	}
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
      GetMessage(true, $e->getMessage());
      echo $e->getMessage();
		}
	}
}