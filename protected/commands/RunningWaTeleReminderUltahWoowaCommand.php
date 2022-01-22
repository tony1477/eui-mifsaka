<?php
class RunningWaTeleReminderUltahWoowaCommand extends CConsoleCommand
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
			
			$sql = "SELECT distinct a.fullname,d.companyid,d.companyname,a.wanumber,a.telegramid,timestampdiff(year, a.birthdate, NOW()) as umur
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
				$telegramid = $data['telegramid'];
				$wanumber = $data['wanumber'];
				
				$pesanwhatsapp = "Manajemen AKA Group mengucapkan,\n_*SELAMAT ULANG TAHUN*_ yang ke-{$data['umur']} untuk\n\n*{$data['fullname']}*\n\n_Karyawan dari {$data['companyname']}_";
				
				//send pesan telegram ke ius.tan
				$url = Yii::app()->params['tele']."/sendMessage?chat_id=875856213&text=".urlencode($pesanwhatsapp."\n\n_".$time);
				$ch = curl_init();
				$optArray = array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true
				);
				curl_setopt_array($ch, $optArray);
				curl_exec($ch);


				//send pesan whatsapp ke ius.tan
				$url = Yii::app()->params['ip'].'send_message';
				$data = array(
					"phone_no"=> '+6285265644828', //whatsapp ius.tan
					"key"		=> Yii::app()->params['key'],
					"message"	=> $pesanwhatsapp."\n\n".$time
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
				
				if ($telegramid > 0)
				{
					$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($pesanwhatsapp."\n\n_".$time);
					$ch = curl_init();
					$optArray = array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true
					);
					curl_setopt_array($ch, $optArray);
					curl_exec($ch);
				}
				
				if ($wanumber > 0)
				{
					$url = Yii::app()->params['ip'].'send_message';
					$data = array(
						"phone_no"=> $wanumber,
						"key"		=> Yii::app()->params['key'],
						"message"	=> $pesanwhatsapp."\n\n".$time
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
				}
				
/*				$sqluser = "select distinct wanumber,telegramid,username
							from useraccess a
							join usergroup b on b.useraccessid = a.useraccessid
							join groupaccess c on c.groupaccessid = b.groupaccessid
							join groupmenuauth d on d.groupaccessid=c.groupaccessid
							where a.recordstatus = 1 and c.groupname like '%Chief Accounting%'
							and d.menuauthid = 5 and a.useraccessid <> 5
							and (a.telegramid <> '' or a.wanumber <> '')
							and a.useraccessid <> 3
						union
							select distinct wanumber,telegramid,username
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
					
				}
*/			}
			curl_close($ch);
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			echo $e->getMessage();
			GetMessage(true, $e->getMessage());
		}
	}
}