<?php
class RunningWaTeleReminderUltahWhatsvaCommand extends CConsoleCommand
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
							AND (a.resigndate IS NULL OR a.resigndate = '1970-01-01')
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
					
					//send pesan whatsapp ke Group Develop
					sendwagroup($siaga,$pesanwhatsapp,"6281717212109-1615804565");
					
					//send pesan telegram ke ius.tan
					$url = Yii::app()->params['tele']."/sendMessage?chat_id=875856213&text=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time);
					$ch = curl_init();
					$optArray = array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true
					);
					curl_setopt_array($ch, $optArray);
					$result = curl_exec($ch);
					
					//send pesan telegram ke yuliana
					$url = Yii::app()->params['tele']."/sendMessage?chat_id=2042560992&text=".urlencode($pesanwhatsapp."\n\n_*Dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time);
					$ch = curl_init();
					$optArray = array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true
					);
					curl_setopt_array($ch, $optArray);
					$result = curl_exec($ch);


					//send pesan whatsapp ke ius.tan
					$wano = '6285265644828';
					
					sendwajapri($siaga,$pesanwhatsapp,$wano);
					$ch = curl_init();
					
					//send pesan whatsapp ke 
					$wano = '6281290909041';
					
					sendwajapri($siaga,$pesanwhatsapp,$wano);
				}
				//curl_close($ch);
			}
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			echo $e->getMessage();
			GetMessage(true, $e->getMessage());
		}
	}
}