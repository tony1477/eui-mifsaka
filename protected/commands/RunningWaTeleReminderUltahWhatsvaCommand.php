<?php
class RunningWaTeleReminderUltahWhatsvaCommand extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			
			$sqldate = "SELECT REPLACE(DATE(NOW()),YEAR(NOW()),'')";
			$date=$connection->createCommand($sqldate)->queryScalar();
			
			$pesanwhatsapp = "*SELAMAT ULANG TAHUN*\n";
			
			$sqlcount = "SELECT ifnull(count(distinct a.fullname),0)
						FROM employee a
						LEFT JOIN employeeorgstruc b ON b.employeeid=a.employeeid
						LEFT JOIN orgstructure c ON c.orgstructureid=b.orgstructureid
						LEFT JOIN company d ON d.companyid=c.companyid
						WHERE a.birthdate LIKE '%{$date}%' AND a.employeeid <> 11
						AND (a.resigndate IS NULL OR a.resigndate = '1970-01-01') Limit 1
			";
			$countdata = Yii::app()->db->createCommand($sqlcount)->queryScalar();
			
			if ($countdata > 0 )
			{
				$sql = "SELECT distinct a.fullname,d.companyname,a.wanumber,a.telegramid,TIMESTAMPDIFF(YEAR, a.birthdate, NOW()) as umur
							FROM employee a
							LEFT JOIN employeeorgstruc b ON b.employeeid=a.employeeid
							LEFT JOIN orgstructure c ON c.orgstructureid=b.orgstructureid
							LEFT JOIN company d ON d.companyid=c.companyid
							WHERE a.birthdate LIKE '%{$date}%' AND a.employeeid <> 11
							AND (a.resigndate IS NULL OR a.resigndate = '1970-01-01') Limit 1
				";
				$fulldata = Yii::app()->db->createCommand($sql)->queryAll();

				foreach($fulldata as $data)
				{
					$time = date('d-m-Y H:i:s');
					$teleuserid = $data['telegramid'];
					$wauserid = $data['wanumber'];
					
					$sql1 = "SELECT REPLACE({$data['umur']},'0000','')";
					$umur=$connection->createCommand($sql1)->queryScalar();
				
					$pesanwhatsapp = "*SELAMAT ULANG TAHUN* yang ke-{$umur}\n{$data['fullname']}\nKaryawan dari {$data['companyname']}";
					
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
					$wano = '6285265644828';
					
					$ch = curl_init();
					curl_setopt_array($ch, array(
						CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time)."&tujuan=".$wano."@s.whatsapp.net",
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
					
					//send pesan whatsapp ke yuni debora
					$wano = '6282288082066';
					
					$ch = curl_init();
					curl_setopt_array($ch, array(
						CURLOPT_URL => "http://akagroup.co.id:8888/api/sendText?id_device=1&message=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time)."&tujuan=".$wano."@s.whatsapp.net",
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
				}
				curl_close($ch);
			}
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			echo $e->getMessage();
			GetMessage(true, $e->getMessage());
		}
	}
}