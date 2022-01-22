<?php
class RunningTeleMonthlyPLCommand extends CConsoleCommand
{
	protected $pdf;
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			date_default_timezone_set('Asia/Jakarta');
			$per =10;
			$sqldatelastmonth = "SELECT LAST_DAY(DATE_ADD(DATE_ADD(DATE(NOW()),INTERVAL -1 DAY), INTERVAL -1 MONTH))";
			$date=$connection->createCommand($sqldatelastmonth)->queryScalar();
			
			//invite link whatsapp group
			$groupexcellent = 'E9eArSN1JVlJSghuZfqCBW';
			$grouptop = 'CaddD6Qsh4x016NNBSN9Oq';
			$groupaka = 'EhB4FeDtiEQArhDuXTnjOo';
			$groupks = 'EEUVg29UfkDEkDVTqHtVLH';
			$groupamin = 'ETkHaiW08uQ4Py9EzJjBqm';
			$groupakm = 'KrBg58oBP8IBQaqzvFUj8G';
			$groupajm = '';
			$groupami = 'JqTxpwfq5Ir6fCr4ws35ae';
			$groupagem = 'EdJpLXyACDg3egNWEWWUun';
			$groupakp = 'E085tRmRqPh45LCdNcJAAr';
			
			$sql1 = "select a.companyid,a.companycode
							from company a
							where a.recordstatus = 1
							and a.nourut > 0
							-- and a.companyid = 21
							order by a.nourut asc
							-- Limit 1
			";
			$dataReader1=$connection->createCommand($sql1)->queryAll();
			
			foreach($dataReader1 as $row1)
			{
				require_once("pdf_console.php");
				//$this->connection = Yii::app()->db;
				$this->pdf        = new PDF();
				ob_start();

				$companyid = $row1['companyid'];
			  $comcode = $row1['companycode'];


				$sqlcall = "call InsertPLLajur('" . $companyid . "','" . date(Yii::app()->params['datetodb'], strtotime($date)) . "')";
				Yii::app()->db->createCommand($sqlcall)->execute();
				
$year = date('Y');
$pass = $comcode.$year;
$passmaster = 'S4kukur4t4';
$this->pdf->SetProtection(array('print'),$pass,$passmaster);

				$this->pdf->companyid = $companyid;
				$this->pdf->AddPage('L');
				$this->pdf->Cell(0, 0, 'Laporan P/L', 0, 0, 'C');
				$this->pdf->Cell(-277, 10, 'Per : ' . date("d F Y", strtotime($date)), 0, 0, 'C');
				$i = 0;
				$this->pdf->setFont('Arial', 'B', 6);
				$this->pdf->sety($this->pdf->gety() + 10);
				$this->pdf->colalign  = array(
					'C',
					'C',
					'C',
					'C'
				);
				$this->pdf->colheader = array(
					'',
					'Bulan Ini',
					'Bulan Lalu',
					'Akumulatif  s/d  Bulan Ini'
				);
				$this->pdf->setwidths(array(
					50,
					92,
					40,
					92
				));
				$this->pdf->Rowheader();
				$this->pdf->colalign  = array(
					'C',
					'C',
					'C',
					'C',
					'C',
					'C',
					'C',
					'C',
					'C',
					'C',
					'C',
					'C',
					'C'
				);
				$this->pdf->colheader = array(
					'Keterangan',
					'Budget',
					'%',
					'Actual',
					'%',
					'Penc %',
					'Actual',
					'%',
					'Budget',
					'%',
					'Actual',
					'%',
					'Penc %'
				);
				$this->pdf->setwidths(array(
					50,
					28,
					12,
					28,
					12,
					12,
					28,
					12,
					28,
					12,
					28,
					12,
					12
				));
				$this->pdf->Rowheader();
				$this->pdf->coldetailalign = array(
					'L',
					'R',
					'R',
					'R',
					'R',
					'R',
					'R',
					'R',
					'R',
					'R',
					'R',
					'R',
					'R'
				);
				$sql                       = "select a.*
					from repprofitlosslajur a 
					where a.companyid = '" . $companyid . "' 
					and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($date)) . "')
					and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($date)) . "')
					order by jumlah";
				$datas = Yii::app()->db->createCommand($sql)->queryAll();
				foreach ($datas as $data) {
					if (($data['accountid'] !== null) && (strpos($data['accountname'], 'Total') === false)) {
						$this->pdf->setFont('Arial', '', 6);
						$this->pdf->row(array(
							$data['accountname'],
							Yii::app()->format->formatCurrency($data['budgetblninitotal']/$per),
							Yii::app()->format->formatCurrency($data['budgetblninipersen']),
							Yii::app()->format->formatCurrency($data['actualblninitotal']/$per),
							Yii::app()->format->formatCurrency($data['actualblninipersen']),
							Yii::app()->format->formatCurrency($data['pencpersen']),
							Yii::app()->format->formatCurrency($data['actualblnlalutotal']/$per),
							Yii::app()->format->formatCurrency($data['actualblnlalupersen']),
							Yii::app()->format->formatCurrency($data['budgetakumtotal']/$per),
							Yii::app()->format->formatCurrency($data['budgetakumpersen']),
							Yii::app()->format->formatCurrency($data['actualakumtotal']/$per),
							Yii::app()->format->formatCurrency($data['actualakumpersen']),
							Yii::app()->format->formatCurrency($data['pencakumpersen'])
						));
					} else if ($data['accountid'] == null) {
						$this->pdf->setFont('Arial', 'B', 6);
						$this->pdf->row(array(
							$data['accountname'],
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							''
						));
					} else {
						$this->pdf->setFont('Arial', 'B', 6);
						$this->pdf->row(array(
							$data['accountname'],
							Yii::app()->format->formatCurrency($data['budgetblninitotal']/$per),
							Yii::app()->format->formatCurrency($data['budgetblninipersen']),
							Yii::app()->format->formatCurrency($data['actualblninitotal']/$per),
							Yii::app()->format->formatCurrency($data['actualblninipersen']),
							Yii::app()->format->formatCurrency($data['pencpersen']),
							Yii::app()->format->formatCurrency($data['actualblnlalutotal']/$per),
							Yii::app()->format->formatCurrency($data['actualblnlalupersen']),
							Yii::app()->format->formatCurrency($data['budgetakumtotal']/$per),
							Yii::app()->format->formatCurrency($data['budgetakumpersen']),
							Yii::app()->format->formatCurrency($data['actualakumtotal']/$per),
							Yii::app()->format->formatCurrency($data['actualakumpersen']),
							Yii::app()->format->formatCurrency($data['pencakumpersen'])
						));
					}
					$this->pdf->sety($this->pdf->gety() - 2);
				}
				//$this->pdf->Output();
				$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/PL_'.$comcode.'_'.$date.'.pdf','F');
				ob_clean();


				$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/PL_'.$comcode.'_'.$date.'.pdf');

	//$url = "https://api.telegram.org/bot842590160:AAExqP6zh1v-EaMDWWoM_ZuBiGwuF66Ricc/sendMessage?chat_id=-379058597&text=".$pesantelegram."&parse_mode=html";

	$telechannelACCid = "-1001411770810"; //group Accounting AKA-Group Channel
	//$telegroupid = "-379058597"; //group develop
	//$telegroupid = "-1001274774033"; //group Accounting AKA-Group Discuss
	$telegroupTOPid = "-1001152080596"; //group Top Management
	$telegroupBMid = "-1001154554937"; //group Kangaroo Excellent
	$telegroupCAid = "-1001242260468"; //group CA - Audit, Acc & Sys AKA-Grup Discuss
	$teleuserid = "875856213"; //user ius.tan
	//$teleuserid = "987910076"; //user suwito
				
	/*			//send file telegram japri
				$post = array('chat_id' => $teleuserid,'document'=>new CurlFile($filepath));    
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
				curl_setopt($ch, CURLOPT_POST, 1);   
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_exec ($ch);
	*/
				//send file telegram ke group Top Management
				$post = array('chat_id' => $telegroupTOPid,'document'=>new CurlFile($filepath));    
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
				curl_setopt($ch, CURLOPT_POST, 1);   
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_exec ($ch);

	/*				//send file telegram ke group Chief Accounting
				$post = array('chat_id' => $telegroupCAid,'document'=>new CurlFile($filepath));    
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
				curl_setopt($ch, CURLOPT_POST, 1);   
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_exec ($ch);

				//send file telegram ke Channel Chief Accounting
				$post = array('chat_id' => $telechannelACCid,'document'=>new CurlFile($filepath));    
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
				curl_setopt($ch, CURLOPT_POST, 1);   
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_exec ($ch);
	*/				

	/*				$sqlbm = "select distinct telegramid
							from useraccess a
							join usergroup b on b.useraccessid = a.useraccessid
							join groupaccess c on c.groupaccessid = b.groupaccessid
							join groupmenuauth d on d.groupaccessid=c.groupaccessid
							where a.recordstatus = 1 and c.groupname like '%Branch Manager%'
							and d.menuauthid = 5 and d.menuvalueid in ({$companyid})
							and a.telegramid <> '';
				";
				$databm = Yii::app()->db->createCommand($sqlbm)->queryAll();

				foreach($databm as $telebm)
				{
					//send file telegram ke Branch Manager
					$telebmid = $telebm['telegramid'];
					$post = array('chat_id' => $telebmid,'document'=>new CurlFile($filepath));
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
				}
				

				$sqlaudit = "select distinct telegramid
							from useraccess a
							join usergroup b on b.useraccessid = a.useraccessid
							join groupaccess c on c.groupaccessid = b.groupaccessid
							join groupmenuauth d on d.groupaccessid=c.groupaccessid
							where a.recordstatus = 1 and c.groupname like '%RASC%'
							and d.menuauthid = 5 and d.menuvalueid in ({$companyid})
							and a.telegramid <> '';
				";
				$dataaudit = Yii::app()->db->createCommand($sqlaudit)->queryAll();

				foreach($dataaudit as $teleaudit)
				{
					//send file telegram ke Audit
					$teleauditid = $teleaudit['telegramid'];
					$post = array('chat_id' => $teleauditid,'document'=>new CurlFile($filepath));
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
				}
				

				$sqlca = "select distinct telegramid
							from useraccess a
							join usergroup b on b.useraccessid = a.useraccessid
							join groupaccess c on c.groupaccessid = b.groupaccessid
							join groupmenuauth d on d.groupaccessid=c.groupaccessid
							where a.recordstatus = 1 and c.groupname like '%Chief Accounting%'
							and d.menuauthid = 5 and d.menuvalueid in ({$companyid})
							and a.telegramid <> '';
				";
				$dataca = Yii::app()->db->createCommand($sqlca)->queryAll();

				foreach($dataca as $teleca)
				{
					//send file telegram ke Chief Accounting
					$telecaid = $teleca['telegramid'];
					$post = array('chat_id' => $telecaid,'document'=>new CurlFile($filepath));
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
				}
	*/				
				curl_close($ch);
			}
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			//$transaction->rollBack();
			//GetMessage(true, $e->getMessage());
			echo $e->getMessage();
		}
	}
}