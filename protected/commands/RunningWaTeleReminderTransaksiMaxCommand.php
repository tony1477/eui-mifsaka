<?php
class RunningwateleremindertransaksimaxCommand extends CConsoleCommand
{
	protected $pdf;
	public function run($args)
	{
		$connection=Yii::app()->db;
		try
		{
			$per =10;
			
			//startdate first day of month
			$sqldatefirstmonth = "SELECT DATE_ADD(LAST_DAY(DATE_ADD(DATE_ADD(DATE(NOW()),INTERVAL -1 DAY), INTERVAL -1 MONTH)),INTERVAL 1 DAY)";
			$startdate=$connection->createCommand($sqldatefirstmonth)->queryScalar();
			
			//enddate today
			$sqldate = "SELECT DATE(NOW())";			
			$enddate=$connection->createCommand($sqldate)->queryScalar();

			//invite link whatsapp group
			$groupdevelop = 'DgCpqnq4EAiKTxBxAVtaCt';
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
			
			//link group telegram
			$teleaccchannelid = "-1001411770810"; //group Accounting AKA-Group Channel
			$teleaccgroupid = "-1001274774033"; //group Accounting AKA-Group Discuss
			$teleexcellentgroupid = "-429137831"; //group Kangaroo Excellent
			$telecagroupid = "-370374663"; //group CA - Audit, Acc & Sys AKA-Grup Discuss
			$teledevelopgroupid = "-379058597"; //group develop

			$sql1 = "select a.companyid,a.companycode
							from company a
							where a.recordstatus = 1
							and a.nourut > 0
							order by a.nourut asc
							-- Limit 1
			";
			$dataReader1=$connection->createCommand($sql1)->queryAll();
			
			foreach($dataReader1 as $row1)
			{
				$connection = Yii::app()->db;
				$companyid = $row1['companyid'];

//01 Laporan Rekap STTB Per Dokumen Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from grheader a
								join poheader b on b.poheaderid = a.poheaderid
								join podetail c on c.poheaderid = b.poheaderid
								join product d on d.productid = c.productid
								join sloc e on e.slocid = c.slocid
								where a.grdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
								and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
								and a.recordstatus between 1 and (3-1)
								and a.grheaderid is not null
								and	b.companyid =  " . $companyid . "
								order by a.grdate,a.recordstatus,a.grno
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 	= "select distinct a.grheaderid,a.grno,a.grdate,b.pono,b.headernote,a.recordstatus
									from grheader a
									join poheader b on b.poheaderid = a.poheaderid
									join podetail c on c.poheaderid = b.poheaderid
									join product d on d.productid = c.productid
									join sloc e on e.slocid = c.slocid
									where a.grdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
									and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
									and a.recordstatus between 1 and (3-1)
									and a.grheaderid is not null
									and	b.companyid =  " . $companyid . "
									order by a.grdate,a.recordstatus,a.grno
									";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap STTB Per Dokumen Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'L'
					);
					$this->pdf->setwidths(array(
						10,
						20,
						25,
						25,
						25,
						50,
						40,
						25
					));
					$this->pdf->colheader = array(
						'No',
						'ID Transaksi',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'L'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['grheaderid'],
							$row['grno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])),
							$row['pono'],
							$row['headernote'],
							findstatusname("appgr", $row['recordstatus'])
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/GDG01_'.$comcode.'_DokumenSTTBBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/GDG01_'.$comcode.'_DokumenSTTBBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//02 Laporan Rekap Retur Pembelian Per Dokumen Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
									from grretur a
									join poheader b on b.poheaderid = a.poheaderid
									join grreturdetail c on c.grreturid = a.grreturid
									join product d on d.productid = c.productid
									join poheader e on e.poheaderid = a.poheaderid
									join sloc f on f.slocid = c.slocid
									where a.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
									and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
									and a.recordstatus between 1 and (3-1) and a.grreturid is not null
									and e.companyid = " . $companyid . "
									order by a.grreturdate,b.recordstatus,a.grreturno
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 	= "select distinct a.grreturid,a.grreturno,a.grreturdate,b.pono,b.headernote,a.recordstatus
										from grretur a
										join poheader b on b.poheaderid = a.poheaderid
										join grreturdetail c on c.grreturid = a.grreturid
										join product d on d.productid = c.productid
										join poheader e on e.poheaderid = a.poheaderid
										join sloc f on f.slocid = c.slocid
										where a.grreturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
										and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
										and a.recordstatus between 1 and (3-1) and a.grreturid is not null
										and e.companyid = " . $companyid . "
										order by a.grreturdate,b.recordstatus,a.grreturno
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Retur Pembelian Per Dokumen Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'L'
					);
					$this->pdf->setwidths(array(
						10,
						20,
						25,
						25,
						25,
						50,
						40,
						25
					));
					$this->pdf->colheader = array(
						'No',
						'ID Transaksi',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'L'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['grreturid'],
							$row['grreturno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['grreturdate'])),
							$row['pono'],
							$row['headernote'],
							findstatusname("appgrretur", $row['recordstatus'])
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/GDG02_'.$comcode.'_DokumenReturBeliBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/GDG02_'.$comcode.'_DokumenReturBeliBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//03 Laporan Rekap Surat Jalan Per Dokumen Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from giheader a
										join soheader b on b.soheaderid = a.soheaderid
										join sodetail c on c.soheaderid = b.soheaderid
										join product d on d.productid = c.productid
										join sloc e on e.slocid = c.slocid
										where a.gidate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
										and a.recordstatus between 1 and (3-1) 
										and b.companyid = " . $companyid . "
										order by a.gidate,a.recordstatus,a.gino
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql        = "select distinct a.giheaderid,a.gino,a.gidate,b.sono,a.headernote,a.recordstatus
											from giheader a
											join soheader b on b.soheaderid = a.soheaderid
											join sodetail c on c.soheaderid = b.soheaderid
											join product d on d.productid = c.productid
											join sloc e on e.slocid = c.slocid
											where a.gidate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
											and a.recordstatus between 1 and (3-1) 
											and b.companyid = " . $companyid . "
											order by a.gidate,a.recordstatus,a.gino
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Surat Jalan Per Dokumen Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$this->pdf->setwidths(array(
						10,
						20,
						25,
						25,
						25,
						60,
						25,
						25
					));
					$this->pdf->colheader = array(
						'No',
						'ID Transaksi',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['giheaderid'],
							$row['gino'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])),
							$row['sono'],
							$row['headernote'],
							findstatusname("appgi", $row['recordstatus'])
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/GDG03_'.$comcode.'_DokumenSuratJalanBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/GDG03_'.$comcode.'_DokumenSuratJalanBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//04 Laporan Rekap Retur Penjualan Per Dokumen Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
							 from giretur a 
							 join giheader b on b.giheaderid = a.giheaderid
							 join gidetail c on c.giheaderid = a.giheaderid
							 join product d on d.productid = c.productid
							 join sloc e on e.slocid = c.slocid
							 join plant f on f.plantid=e.plantid
							 where a.gireturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
							 and a.recordstatus between 1 and (3-1)
							 and f.companyid = " . $companyid . "
							 order by a.gireturdate,a.recordstatus,a.gireturno
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 	= "select distinct a.gireturid,a.gireturno,a.gireturdate,b.gino,a.headernote,a.recordstatus
								 from giretur a 
								 join giheader b on b.giheaderid = a.giheaderid
								 join gidetail c on c.giheaderid = a.giheaderid
								 join product d on d.productid = c.productid
								 join sloc e on e.slocid = c.slocid
								 join plant f on f.plantid=e.plantid
								 where a.gireturdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
								 and a.recordstatus between 1 and (3-1)
								 and f.companyid = " . $companyid . "
								 order by a.gireturdate,a.recordstatus,a.gireturno
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Retur Penjualan Per Dokumen Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$this->pdf->setwidths(array(
						10,
						20,
						25,
						25,
						25,
						60,
						25,
						25
					));
					$this->pdf->colheader = array(
						'No',
						'ID Transaksi',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['gireturid'],
							$row['gireturno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])),
							$row['gino'],
							$row['headernote'],
							findstatusname("appgiretur", $row['recordstatus'])
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/GDG04_'.$comcode.'_DokumenReturJualBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/GDG04_'.$comcode.'_DokumenReturJualBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//05 Laporan Rekap Transfer Per Dokumen Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
									from transstock a
									join deliveryadvice b on b.deliveryadviceid = a.deliveryadviceid
									join transstockdet c on c.transstockid = a.transstockid
									join product d on d.productid = c.productid
									join sloc e on e.slocid = a.slocfromid							
									join sloc f on f.slocid = a.sloctoid
									join plant g on g.plantid = e.plantid
									where g.companyid = " . $companyid . "
									and a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
									and a.recordstatus between 1 and (5-1)
									and b.dano is not null
									order by a.docdate,a.recordstatus,a.transstockno
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 	= "select distinct a.transstockid,a.transstockno,a.docdate,b.dano,a.headernote,a.recordstatus,
									e.sloccode as slocfrom,f.sloccode as slocto
										from transstock a
										join deliveryadvice b on b.deliveryadviceid = a.deliveryadviceid
										join transstockdet c on c.transstockid = a.transstockid
										join product d on d.productid = c.productid
										join sloc e on e.slocid = a.slocfromid							
										join sloc f on f.slocid = a.sloctoid
										join plant g on g.plantid = e.plantid
										where g.companyid = " . $companyid . "
										and a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
										and a.recordstatus between 1 and (5-1)
										and b.dano is not null
										order by a.docdate,a.recordstatus,a.transstockno
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Transfer Per Dokumen Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
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
					$this->pdf->setwidths(array(
						10,
						15,
						20,
						18,
						20,
						20,
						20,
						35,
						38
					));
					$this->pdf->colheader = array(
						'No',
						'ID',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Asal',
						'Tujuan',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['transstockid'],
							$row['transstockno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['dano'],
							$row['slocfrom'],
							$row['slocto'],
							$row['headernote'],
							findstatusname("appts", $row['recordstatus'])
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/GDG05_'.$comcode.'_DokumenTransferStockBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/GDG05_'.$comcode.'_DokumenTransferStockBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//06 Laporan Rekap Stock Opname Per Dokumen Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from bsheader a
										join bsdetail b on b.bsheaderid = a.bsheaderid
										join product c on c.productid = b.productid
										join sloc d on d.slocid = a.slocid
										join plant e on e.plantid = d.plantid
										where a.bsdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
										and a.recordstatus between 1 and (5-1)
										and e.companyid = " . $companyid . "
										order by a.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql        = "select distinct a.bsheaderid,a.bsdate,a.bsheaderno,d.sloccode,a.headernote,a.recordstatus
											from bsheader a
											join bsdetail b on b.bsheaderid = a.bsheaderid
											join product c on c.productid = b.productid
											join sloc d on d.slocid = a.slocid
											join plant e on e.plantid = d.plantid
											where a.bsdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
											and a.recordstatus between 1 and (5-1)
											and e.companyid = " . $companyid . "
											order by a.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Stock Opname Per Dokumen Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$this->pdf->setwidths(array(
						10,
						20,
						25,
						20,
						20,
						22,
						50,
						25
					));
					$this->pdf->colheader = array(
						'No',
						'ID Transaksi',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Gudang',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['bsheaderid'],
							$row['bsheaderno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['bsdate'])),
							'-',
							$row['sloccode'],
							$row['headernote'],
							findstatusname("appbs", $row['recordstatus'])
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/GDG06_'.$comcode.'_DokumenStockOpnameBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/GDG06_'.$comcode.'_DokumenStockOpnameBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//07 Laporan Rekap Konversi Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from productconvert a
										join productconvertdetail b on b.productconvertid = a.productconvertid
										join productconversion c on c.productconversionid = a.productconversionid
										join product d on c.productid = d.productid
										join unitofmeasure e on e.unitofmeasureid = a.uomid
										join sloc f on f.slocid = a.slocid
										join plant g on g.plantid = f.plantid
										where  a.recordstatus between 1 and (3-1)
										and g.companyid = ".$companyid."
										and date(a.createddate) between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
										order by a.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql        = "select distinct a.productconvertid,a.qty,a.recordstatus,d.productname,e.uomcode,f.sloccode,Getwfstatusbywfname('appconvert',a.recordstatus) as statusname
											from productconvert a
											join productconvertdetail b on b.productconvertid = a.productconvertid
											join productconversion c on c.productconversionid = a.productconversionid
											join product d on c.productid = d.productid
											join unitofmeasure e on e.unitofmeasureid = a.uomid
											join sloc f on f.slocid = a.slocid
											join plant g on g.plantid = f.plantid
											where  a.recordstatus between 1 and (3-1)
											and g.companyid = ".$companyid."
											and date(a.createddate) between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
											order by a.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Konversi Per Dokumen Belum Status Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'C'
					);
					$this->pdf->setwidths(array(
						10,
						15,
						80,
						15,
						20,
						20,
						30
					));
					$this->pdf->colheader = array(
						'No',
						'ID',
						'Material/Service',
						'QTY',
						'Gudang',
						'Satuan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'L',
						'R',
						'C',
						'C',
						'C'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['productconvertid'],
							$row['productname'],
							Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $row['qty']),
							$row['sloccode'],
							$row['uomcode'],
							$row['statusname'],
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/GDG07_'.$comcode.'_DokumenKonversiStockBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/GDG07_'.$comcode.'_DokumenKonversiStockBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//08 Laporan Rekap FPB Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
		            FROM (SELECT IF((productplanid is not null) or (productplanid<>''),'SPP',IF((productoutputid is not null) or (productoutputid<>''),'OP',IF((soheaderid is not null) or (soheaderid<>''),'SO','UMUM'))) as jenis, deliveryadviceid, b.username, y.dadate, y.dano, y.statusname, y.recordstatus, y.headernote, y.slocid
		                FROM deliveryadvice y
		                JOIN useraccess b ON b.useraccessid = y.useraccessid) zz
		                WHERE zz.recordstatus<3 
										AND zz.recordstatus <> 0
		                AND slocid IN (
		                SELECT xa.slocid
		                FROM sloc xa
		                JOIN plant xb ON xb.plantid = xa.plantid
		                JOIN company xc ON xc.companyid = xb.companyid
		                WHERE xc.companyid = ".$companyid." AND xa.slocid = zz.slocid)
		            AND zz.dadate BETWEEN ('" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "') AND ('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
		            ORDER BY deliveryadviceid DESC
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select 
									IF(jenis='SPP',(SELECT productplanid FROM deliveryadvice a WHERE a.deliveryadviceid = zz.deliveryadviceid),
									IF(jenis='OP',(SELECT productoutputid FROM deliveryadvice a WHERE a.deliveryadviceid = zz.deliveryadviceid),
									IF(jenis='SO',(SELECT soheaderid FROM deliveryadvice a WHERE a.deliveryadviceid = zz.deliveryadviceid),''))) as id_jenis, deliveryadviceid, jenis, dadate, dano, statusname, username, headernote
									FROM (SELECT IF((productplanid is not null) or (productplanid<>''),'SPP',IF((productoutputid is not null) or (productoutputid<>''),'OP',IF((soheaderid is not null) or (soheaderid<>''),'SO','UMUM'))) as jenis, deliveryadviceid, b.username, y.dadate, y.dano, y.statusname, y.recordstatus, y.headernote, y.slocid
											FROM deliveryadvice y
											JOIN useraccess b ON b.useraccessid = y.useraccessid) zz
											WHERE zz.recordstatus<3 
											AND zz.recordstatus <> 0
											AND slocid IN (
											SELECT xa.slocid
											FROM sloc xa
											JOIN plant xb ON xb.plantid = xa.plantid
											JOIN company xc ON xc.companyid = xb.companyid
											WHERE xc.companyid = ".$companyid." AND xa.slocid = zz.slocid)
									AND zz.dadate BETWEEN ('" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "') AND ('" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "')
									ORDER BY deliveryadviceid DESC
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Laporan FPB Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L',
						'L'
					);
					$this->pdf->setwidths(array(
						10,
						15,
						25,
						20,
						25,
						15,
						45,
						30
					));
					$this->pdf->colheader = array(
						'No',
						'ID FPB',
						'No FPB',
						'Tanggal',
						'User',
						'Jenis ',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'L',
						'L',
						'L',
						'L',
						'L',
						'L',
						'L'
					);
						$i=0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 8);
						$this->pdf->row(array(
							$i,
							$row['deliveryadviceid'],
							$row['dano'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])),
							$row['username'],
							$row['jenis'],
							$row['headernote'],
							$row['statusname']
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/GDG08_'.$comcode.'_DokumenFPBBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/GDG08_'.$comcode.'_DokumenFPBBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//09 Laporan Rekap Hasil Produksi Per Dokumen Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
									from productoutput b
									join productplan c on c.productplanid = b.productplanid
									join productoutputfg d on d.productoutputid = b.productoutputid
									join product e on e.productid = d.productid
									join sloc f on f.slocid = d.slocid
									where c.companyid = " . $companyid . "
									and b.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
									and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
									and b.recordstatus between 1 and (3-1) and b.productplanid is not null 
									order by b.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 		= "select distinct b.productoutputid,b.productoutputid,b.recordstatus,
										b.productoutputno,b.productoutputdate,c.productplanno,b.description,b.statusname
										from productoutput b
										join productplan c on c.productplanid = b.productplanid
										join productoutputfg d on d.productoutputid = b.productoutputid
										join product e on e.productid = d.productid
										join sloc f on f.slocid = d.slocid
										where c.companyid = " . $companyid . "
										and b.productoutputdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
										and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
										and b.recordstatus between 1 and (3-1) and b.productplanid is not null 
										order by b.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Hasil Produksi Per Dokumen Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'C'
					);
					$this->pdf->setwidths(array(
						10,
						25,
						25,
						30,
						30,
						50,
						20
					));
					$this->pdf->colheader = array(
						'No',
						'ID Transaksi',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'C'
					);
					$i=0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['productoutputid'],
							$row['productoutputno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])),
							$row['productplanno'],
							$row['description'],
							$row['statusname']
						));
						$this->pdf->checkPageBreak(20);
					}
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C'
					);
					$this->pdf->setwidths(array(
						40,
						50,
						40,
						40
					));
					$this->pdf->setFont('Arial', 'B', 9);
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/PRD01_'.$comcode.'_DokumenHasilProduksiBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/PRD01_'.$comcode.'_DokumenHasilProduksiBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//10 Laporan Rekap SPP Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
									from productplan a
									join productplanfg b on b.productplanid = a.productplanid
									join product c on c.productid = b.productid
									join sloc d on d.slocid = b.slocid
									join company e on e.companyid = a.companyid
									where a.recordstatus between 1 and 2
									and a.productplandate between ('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') 
									and ('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
									and a.companyid = ".$companyid."
									group by a.productplanid 
									order by a.productplanid desc
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 		= "select distinct a.productplanid,a.companyid, a.productplanno, a.statusname, productplandate, a.description, a.recordstatus, group_concat(c.productname) as productname, d.sloccode, e.companycode
										from productplan a
										join productplanfg b on b.productplanid = a.productplanid
										join product c on c.productid = b.productid
										join sloc d on d.slocid = b.slocid
										join company e on e.companyid = a.companyid
										where a.recordstatus between 1 and 2
										and a.productplandate between ('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') 
										and ('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
										and a.companyid = ".$companyid."
										group by a.productplanid 
										order by a.productplanid desc
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					foreach ($dataReader as $row) 
					{
						$this->pdf->companyid = $companyid;
					}
					
					$this->pdf->title    = 'Laporan SPP Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
					$this->pdf->AddPage('L');
					$this->pdf->sety($this->pdf->gety() + 5);
					$this->pdf->SetFont('Arial','',9);
					$this->pdf->colalign = array(
						'C',
						'L',
						'L',
						'C',
						'C',
						'C',
						'C',
						'C',
						'C'
					);
					$this->pdf->setwidths(array(
						15,
						35,
						25,
						25,
						70,
						30,
						40,
						30
					));
					$this->pdf->colheader = array(
						'No',
						'ID',
						'NO SPP',
								'Tanggal SPP',
						'Product',
						'Gudang',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();        
					$i=1;
					$this->pdf->coldetailalign = array(
						'L',
						'L',
						'L',
						'L',
						'L',
						'L',
						'L',
						'L'
					);
					foreach($dataReader as $row)
					{
						$this->pdf->row(array(
							$i,
							$row['productplanid'],
							$row['productplanno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])),
							$row['productname'],
							$row['sloccode'],
							$row['description'],
							$row['statusname']
						));
						$i++;
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/PRD02_'.$comcode.'_DokumenSPPBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/PRD02_'.$comcode.'_DokumenaSPPBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//11 Laporan PO Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
		                FROM poheader a 
		                LEFT JOIN addressbook b ON b.addressbookid = a.addressbookid
		                LEFT JOIN company c ON c.companyid = a.companyid
		                LEFT JOIN tax d ON d.taxid = a.taxid
		                LEFT JOIN paymentmethod e ON e.paymentmethodid = a.paymentmethodid
		                WHERE a.recordstatus BETWEEN ('1') AND ('4')
		                AND docdate BETWEEN ('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') 
		                AND ('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
		                AND c.companyid=".$companyid."
		                AND b.isvendor=1
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select a.poheaderid,c.companycode, a.pono, a.docdate, b.fullname, d.taxcode, a.shipto, a.billto, a.statusname, e.paycode
									FROM poheader a 
									LEFT JOIN addressbook b ON b.addressbookid = a.addressbookid
									LEFT JOIN company c ON c.companyid = a.companyid
									LEFT JOIN tax d ON d.taxid = a.taxid
									LEFT JOIN paymentmethod e ON e.paymentmethodid = a.paymentmethodid
									WHERE a.recordstatus BETWEEN ('1') AND ('4')
									AND docdate BETWEEN ('".date(Yii::app()->params['datetodb'], strtotime($startdate))."') 
									AND ('".date(Yii::app()->params['datetodb'], strtotime($enddate))."')
									AND c.companyid=".$companyid."
									AND b.isvendor=1
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Laporan PO Status Belum Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->text(10, $this->pdf->gety() + 10, 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)));
					$this->pdf->AddPage('L');
					$this->pdf->sety($this->pdf->gety() + 5);
					$this->pdf->SetFont('Arial','',9);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'C',
						'C'
					);
					$this->pdf->setwidths(array(
						15,
						20,
						25,
						25,
						100,
						20,
						20,
						40
					));
					$this->pdf->colheader = array(
						'ID',
						'Perusahaan',
						'NO PO',
						'Tanggal PO',
						'Supplier',
						'Pajak',
						'Tempo',
						'Status'
					);
					$this->pdf->RowHeader();        
					$i=1;
					$this->pdf->coldetailalign = array(
						'R',
						'L',
						'L',
						'L',
						'L',
						'L',
						'L',
						'L'
					);
					foreach($dataReader as $row)
					{
						$this->pdf->row(array(
							$row['poheaderid'],
							$row['companycode'],
							$row['pono'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['fullname'],
							$row['taxcode'],
							$row['paycode'],
							$row['statusname']
						));
						$i++;
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/PUR01_'.$comcode.'_DokumenPOBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/PUR01_'.$comcode.'_DokumenPOBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//12 Laporan Rekap SO Per Dokumen Status Belum Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from soheader a
						        join company b on b.companyid = a.companyid
						        join addressbook c on c.addressbookid = a.addressbookid
						        join employee e on e.employeeid = a.employeeid
						        where a.recordstatus < 6 and a.recordstatus <> 0
						        and a.companyid=".$companyid."
										and a.sodate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
										and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 		= "select a.soheaderid, a.sono, a.sodate, a.companyid, a.addressbookid, a.recordstatus, a.statusname, b.companyname, c.fullname
									from soheader a
									join company b on b.companyid = a.companyid
									join addressbook c on c.addressbookid = a.addressbookid
									join employee e on e.employeeid = a.employeeid
									where a.recordstatus < 6 and a.recordstatus <> 0
									and a.companyid=".$companyid."
									and a.sodate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
					";
					
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
				 
					$this->pdf->title='Rekap SO Per Dokumen Status Belum Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->colalign = array('C','C','C','C','C','C');
					$this->pdf->setwidths(array(10,20,20,50,45,45));
					$this->pdf->colheader = array('No','NO SO','Tgl SO','Perusahaan','Customer','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','L','L','L');
					$this->pdf->setFont('Arial','',8);	
					$i=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',8);
						$this->pdf->row(array(
							$row['soheaderid'],
							$row['sono'],
							date(Yii::app()->params['dateviewfromdb'],strtotime($row['sodate'])),
							$row['companyname'],
							$row['fullname'],
							$row['statusname']
						));
																		
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/MKT01_'.$comcode.'_DokumenSOBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/MKT01_'.$comcode.'_DokumenSOBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//13 Laporan Rekap TTNT Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from ttnt a
						        join company b on b.companyid = a.companyid
						        join employee e on e.employeeid = a.employeeid
						        where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						        and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						        and a.recordstatus < getwfmaxstatbywfname('appttnt')
						        and a.recordstatus <>0
						        and a.companyid=".$companyid."
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 		= "select a.ttntid, a.docno, a.docdate, a.companyid, e.fullname,a.recordstatus, a.statusname, b.companyname
										from ttnt a
										join company b on b.companyid = a.companyid
										join employee e on e.employeeid = a.employeeid
										where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
										and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
										and a.recordstatus < getwfmaxstatbywfname('appttnt')
										and a.recordstatus <>0
										and a.companyid=".$companyid."
					";
					$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap TTNT Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',9);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L');
					$this->pdf->setwidths(array(10,20,25,25,75,25));
					$this->pdf->colheader = array('No','ID','No Dokumen','Tanggal','Sales','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','L','C');		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',8);
						$this->pdf->row(array(
							$i,$row['ttntid'],$row['docno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['fullname'], $row['statusname']));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/MKT02_'.$comcode.'_DokumenTTNTBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/MKT02_'.$comcode.'_DokumenTTNTBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//14 Laporan Rekap TTF Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from ttf a
						        join company b on b.companyid = a.companyid
						        join employee e on e.employeeid = a.employeeid
						        where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
						        and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
						        and a.recordstatus < getwfmaxstatbywfname('appttf') 
						        and a.recordstatus <> 0
						        and a.companyid=".$companyid."
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql 		= "select a.ttfid, a.docno, a.docdate, a.companyid, e.fullname,a.recordstatus, a.statusname, b.companyname
										from ttf a
										join company b on b.companyid = a.companyid
										join employee e on e.employeeid = a.employeeid
										where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
										and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
										and a.recordstatus < getwfmaxstatbywfname('appttf') 
										and a.recordstatus <> 0
										and a.companyid=".$companyid."
					";
					$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap TTF Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',9);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L');
					$this->pdf->setwidths(array(10,20,25,25,75,25));
					$this->pdf->colheader = array('No','ID','No Dokumen','Tanggal','Sales','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','C','C','C','L','C');		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',8);
						$this->pdf->row(array(
							$i,$row['ttfid'],$row['docno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['fullname'], $row['statusname']));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/MKT03_'.$comcode.'_DokumenTTFBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/MKT03_'.$comcode.'_DokumenTTFBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//15 Laporan Rekap Skala Komisi Penjualan Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from salesscale a
                join salesscaledet b on a.salesscaleid=b.salesscaleid
                join materialgroup c on b.materialgroupid=c.materialgroupid
								where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								and a.recordstatus < getwfmaxstatbywfname('appss')
                and a.recordstatus <> 0
								and a.companyid like '".$companyid."'
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.salesscaleid,c.materialgroupcode,a.docdate,a.perioddate,c.description, a.statusname
									from salesscale a
									join salesscaledet b on a.salesscaleid=b.salesscaleid
									join materialgroup c on b.materialgroupid=c.materialgroupid
									where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus < getwfmaxstatbywfname('appss')
									and a.recordstatus <> 0
									and a.companyid like '".$companyid."'
					";
					$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Skala Komisi Penjualan Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','L','L','C','L');
					$this->pdf->setwidths(array(10,15,25,25,75,25));
					$this->pdf->colheader = array('No','ID SC','Tanggal','Tgl Periode','Material Group','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','L','L','L','L');		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['salesscaleid'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),	
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['perioddate'])),
							$row['description'], $row['statusname']));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/MKT04_'.$comcode.'_DokumenSkalaKomisiBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/MKT04_'.$comcode.'_DokumenSkalaKomisiBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//16 Laporan Rekap Target Penjualan Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from salestarget a
		                join salestargetdet b on a.salestargetid=b.salestargetid
		              	join materialgroup c on b.materialgroupid=c.materialgroupid
				            join employee d on a.employeeid=d.employeeid
										where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
										and a.recordstatus < getwfmaxstatbywfname('appst')
		                and a.recordstatus <> 0
										and a.companyid like '".$companyid."'
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.salestargetid,a.perioddate,a.docdate,c.description,d.fullname, a.statusname
									from salestarget a
									join salestargetdet b on a.salestargetid=b.salestargetid
									join materialgroup c on b.materialgroupid=c.materialgroupid
									join employee d on a.employeeid=d.employeeid
									where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus < getwfmaxstatbywfname('appst')
									and a.recordstatus <> 0
									and a.companyid like '".$companyid."'
					";
					$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Target Penjualan Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','L','L','L','C','L');
					$this->pdf->setwidths(array(10,15,20,20,30,70,20));
					$this->pdf->colheader = array('No','ID ST','Tanggal','Tgl Periode','Nama Sales','Material/Service','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','L','L','L','L','L');		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['salestargetid'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),	
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['perioddate'])),
							$row['fullname'],$row['description'], $row['statusname']
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/MKT05_'.$comcode.'_DokumenTargetPenjualanBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/MKT05_'.$comcode.'_DokumenTargetPenjualanBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//17 Laporan Rekap Perubahan Plafon Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from plafonreq a
										join addressbook b on a.addressbookid=b.addressbookid
										where  a.plafonreqdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
										and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
										and a.recordstatus < getwfmaxstatbywfname('appss')
		              	and a.recordstatus <> 0
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.plafonreqid,a.plafonreqno,a.plafonreqdate,a.reqlimit,b.fullname, a.recordstatus, getwfstatusbywfname('appplafonreq',a.recordstatus) as statusname
									from plafonreq a
									join addressbook b on a.addressbookid=b.addressbookid
									where  a.plafonreqdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus < getwfmaxstatbywfname('appss')
									and a.recordstatus <> 0
					";
							
					$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Perubahan Plafon Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','L','L','L','C','L');
					$this->pdf->setwidths(array(10,15,25,25,25,60,30));
					$this->pdf->colheader = array('No','ID Plafon','No Plafon','Tgl Plafon','Limit','Customer','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','L','L','L','L','L');		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['plafonreqid'],
							$row['plafonreqno'],	
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['plafonreqdate'])),
							$row['reqlimit'],$row['fullname'], $row['statusname']));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/MKT06_'.$comcode.'_DokumenPerubahanPlafonBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/MKT06_'.$comcode.'_DokumenPerubahanPlafonBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//18 Laporan Rekap Jurnal Umum Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from genjournal a
										join journaldetail b on b.genjournalid = a.genjournalid
										where a.journaldate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
										and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
										and a.recordstatus between 1 and (3-1)
										and a.referenceno is not null
										and a.companyid = ".$companyid."
										order by a.journaldate,a.journalno
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.genjournalid,a.journalno,a.referenceno,a.journaldate,a.journalnote,a.recordstatus
								from genjournal a
								join journaldetail b on b.genjournalid = a.genjournalid
								where a.journaldate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								and a.recordstatus between 1 and (3-1)
								and a.referenceno is not null
								and a.companyid = ".$companyid."
								order by a.journaldate,a.journalno
					";
					$dataReader1=$connection->createCommand($sql1)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Jurnal Umum Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L','L');
					$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
					$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['genjournalid'],$row['journalno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['journaldate'])),
							$row['referenceno'],$row['journalnote'],findstatusname("apppayreq",$row['recordstatus'])
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/ACC01_'.$comcode.'_DokumenJurnalUmumBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/ACC01_'.$comcode.'_DokumenJurnalUmumBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//19 Laporan Rekap Penerimaan Kas/Bank Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from cbin a
										join ttnt b on b.ttntid = a.ttntid
										where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
										and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
										and a.recordstatus between 1 and (3-1)
										and b.docno is not null
										and b.companyid = ".$companyid."
										order by a.docdate,a.cbinno
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.cbinid,a.cbinno,a.docdate,b.docno,a.headernote,a.recordstatus
									from cbin a
									join ttnt b on b.ttntid = a.ttntid
									where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus between 1 and (3-1)
									and b.docno is not null
									and b.companyid = ".$companyid."
									order by a.docdate,a.cbinno
					";
					$dataReader1=$connection->createCommand($sql1)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Penerimaan Kas/Bank Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L','L');
					$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
					$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['cbinid'],$row['cbinno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['docno'],$row['headernote'],findstatusname("apppayreq",$row['recordstatus'])
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/ACC02_'.$comcode.'_DokumenPenerimaanKasBankBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/ACC02_'.$comcode.'_DokumenPenerimaanKasBankBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//20 Laporan Rekap Pengeluaran Kas/Bank Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
							from cashbankout a
							join reqpay b on b.reqpayid = a.cashbankoutid
							where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
							and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
							and a.recordstatus between 1 and (3-1)
							and b.reqpayno is not null
							and a.companyid = ".$companyid." 
							order by a.docdate,a.cashbankoutno
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql="select a.cashbankoutid,a.cashbankoutno,a.docdate,b.reqpayno,b.headernote,a.recordstatus
								from cashbankout a
								join reqpay b on b.reqpayid = a.cashbankoutid
								where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								and a.recordstatus between 1 and (3-1)
								and b.reqpayno is not null
								and a.companyid = ".$companyid." 
								order by a.docdate,a.cashbankoutno
					";
					$dataReader1=$connection->createCommand($sql1)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Pengeluaran Kas/Bank Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
														$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L','L');
					$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
					$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['cashbankoutid'],$row['cashbankoutno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['reqpayno'],$row['headernote'],findstatusname("apppayreq",$row['recordstatus'])
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/ACC03_'.$comcode.'_DokumenPengeluaranKasBankBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/ACC03_'.$comcode.'_DokumenPengeluaranKasBankBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//21 Laporan 
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from cb a
								where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
								and a.receiptno is not null
								and a.recordstatus between 1 and (3-1)
								and a.companyid = ".$companyid." 
								order by a.docdate,a.cashbankno
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.cbid,a.cashbankno,a.docdate,a.receiptno,a.headernote,a.recordstatus
									from cb a
									where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."' 
									and a.receiptno is not null
									and a.recordstatus between 1 and (3-1)
									and a.companyid = ".$companyid." 
									order by a.docdate,a.cashbankno
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Cash Bank Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L','L');
					$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
					$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['cbid'],$row['cashbankno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['receiptno'],$row['headernote'],findstatusname("apppayreq",$row['recordstatus'])
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/ACC04_'.$comcode.'_DokumenCashBankBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/ACC04_'.$comcode.'_DokumenCashBankBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//22 Laporan Rekap Invoice AR Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from invoice a
								join giheader b on b.giheaderid = a.giheaderid
								join soheader c on c.soheaderid = b.soheaderid
								where a.invoicedate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								and a.recordstatus between 1 and (3-1)
								and a.invoiceno is not null
								and c.companyid = ".$companyid."
								order by a.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.invoiceid, a.invoiceno, a.invoicedate, b.gino, a.headernote, c.companyid, a.recordstatus, a.statusname
									from invoice a
									join giheader b on b.giheaderid = a.giheaderid
									join soheader c on c.soheaderid = b.soheaderid
									where a.invoicedate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus between 1 and (3-1)
									and a.invoiceno is not null
									and c.companyid = ".$companyid."
									order by a.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title='Rekap Invoice AR Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L','L');
					$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
					$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['invoiceid'],$row['invoiceno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])),
							$row['gino'],$row['headernote'],$row['statusname']
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/AR01_'.$comcode.'_DokumenInvoiceARBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/AR01_'.$comcode.'_DokumenInvoiceARBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//23 Laporan Rekap Nota Retur Penjualan Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from notagir a
								join giretur b on b.gireturid = a.gireturid
								where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								and a.recordstatus between 1 and (3-1)
								and b.gireturno is not null
								and a.companyid = ".$companyid."
								order by a.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.notagirid, a.notagirno, a.docdate, b.gireturno, a.headernote, a.recordstatus, a.companyid, a.statusname
									from notagir a
									join giretur b on b.gireturid = a.gireturid
									where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus between 1 and (3-1)
									and b.gireturno is not null
									and a.companyid = ".$companyid."
									order by a.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title='Rekap Nota Retur Penjualan Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L','L');
					$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
					$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['notagirid'],$row['notagirno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['gireturno'],$row['headernote'],$row['statusname']
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/AR02_'.$comcode.'_DokumenNotaReturPenjualanBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/AR02_'.$comcode.'_DokumenNotaReturPenjualanBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//24 Laporan 
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from cutar a
								join ttnt b on b.ttntid = a.ttntid
								where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								and a.recordstatus between 1 and (3-1)
								and b.docno is not null
								and a.companyid = ".$companyid."
								order by a.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.cutarid,a.cutarno,a.docdate, b.docno,a.headernote,a.recordstatus,a.statusname
									from cutar a
									join ttnt b on b.ttntid = a.ttntid
									where a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."'
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus between 1 and (3-1)
									and b.docno is not null
									and a.companyid = ".$companyid."
									order by a.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title='Rekap Pelunasan Piutang Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','C','C','C','C','L','L');
					$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
					$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','L','L',);		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['cutarid'],$row['cutarno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['docno'],$row['headernote'],$row['statusname']
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/AR03_'.$comcode.'_DokumenPelunasanPiutangBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/AR03_'.$comcode.'_DokumenPelunasanPiutangBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//25 Laporan Rekap Target Tagihan Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from paymenttarget a
	              join employee b on a.employeeid=b.employeeid
	              join company c on a.companyid=c.companyid
	             	where a.companyid = ".$companyid." and a.companyid = ".$companyid." and a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								and a.recordstatus < getwfmaxstatbywfname('apppt')
	              and a.recordstatus <> 0
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.paymenttargetid,c.companyname,a.docdate,a.perioddate,b.fullname, a.statusname
									from paymenttarget a
									join employee b on a.employeeid=b.employeeid
									join company c on a.companyid=c.companyid
									where a.companyid = ".$companyid." and a.companyid = ".$companyid." and a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus < getwfmaxstatbywfname('apppt')
									and a.recordstatus <> 0
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Target Tagihan Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('C','L','L','L','C','C','L');
					$this->pdf->setwidths(array(10,15,45,20,20,60,25));
					$this->pdf->colheader = array('No','ID','Perusahaan','Tanggal','Tgl Periode','Nama Sales','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['paymenttargetid'],$row['companyname'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['perioddate'])),$row['fullname'],$row['statusname']
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/AR04_'.$comcode.'_DokumenTargetTagihanBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/AR04_'.$comcode.'_DokumenTargetTagihanBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//26 Laporan Rekap Skala Komisi Tagihan Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from paymentscale a
	              join company c on a.companyid=c.companyid
	             	where a.companyid = ".$companyid." and a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
								and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
								and a.recordstatus < getwfmaxstatbywfname('appps')
                and a.recordstatus <> 0
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select distinct a.paymentscaleid,c.companyname,a.docdate,a.perioddate,a.paramspv, a.statusname
									from paymentscale a
									join company c on a.companyid=c.companyid
									where a.companyid = ".$companyid." and a.docdate between '". date(Yii::app()->params['datetodb'], strtotime($startdate))."' 
									and '". date(Yii::app()->params['datetodb'], strtotime($enddate))."'
									and a.recordstatus < getwfmaxstatbywfname('appps')
									and a.recordstatus <> 0
					";								
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach($dataReader as $row)
					{
						$this->pdf->companyid = $companyid;
					}
					$this->pdf->title='Rekap Skala Komisi Tagihan Per Dokumen Belum Status Max';
					$this->pdf->subtitle='Dari Tgl :'.date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)).' s/d '.date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					
					$this->pdf->setFont('Arial','B',8);
					$this->pdf->sety($this->pdf->gety()+10);
					$this->pdf->colalign = array('L','L','L','L','L','L','L');
					$this->pdf->setwidths(array(10,20,50,20,20,20,30));
					$this->pdf->colheader = array('No','ID SKT','Perusahaan','Tanggal','Tgl Periode','Param','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');		
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;
					foreach($dataReader as $row)
					{
						$i+=1;
						$this->pdf->setFont('Arial','',7);
						$this->pdf->row(array(
							$i,$row['paymentscaleid'],$row['companyname'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['perioddate'])),$row['paramspv'],$row['statusname']
						));
									 
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/AR05_'.$comcode.'_DokumenSkalaKomisiTagihanBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/AR05_'.$comcode.'_DokumenSkalaKomisiTagihanBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//27 Laporan Rekap Invoice AP Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
										from invoiceap a
										join poheader b on b.poheaderid = a.poheaderid
										where a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
										and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
										and a.recordstatus between 1 and (3-1)
										and b.pono is not null
										and a.companyid = " . $companyid . "
										order by a.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql        = "select distinct a.invoiceapid, a.invoiceno,a.invoicedate, b.pono, b.headernote, a.statusname
												from invoiceap a
												join poheader b on b.poheaderid = a.poheaderid
												where a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
												and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
												and a.recordstatus between 1 and (3-1)
												and b.pono is not null
												and a.companyid = " . $companyid . "
												order by a.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Invoice AP Per Dokumen Belum Status Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array('C','C','C','C','C','L','L');
					$this->pdf->setwidths(array(10,20,25,25,25,60,25,25));
					$this->pdf->colheader = array('No','ID Transaksi','No Transaksi','Tanggal','No Referensi','Keterangan','Status');
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array('C','C','C','C','C','L','L');
					$totalnominal1=0;$i=0;$totaldisc1=0;$totaljumlah1=0;

					foreach ($dataReader as $row)
					{
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['invoiceapid'],
							$row['invoiceno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])),
							$row['pono'],
							$row['headernote'],
							$row['statusname']
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/AP01_'.$comcode.'_DokumenInvoiceAPBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/AP01_'.$comcode.'_DokumenInvoiceAPBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//28 Laporan 
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from reqpay a
								where a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
								and a.recordstatus between 1 and (6-1)
								and a.companyid = " . $companyid . "
								order by a.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select a.reqpayid, a.reqpayno, a.docdate, a.headernote, a.recordstatus
									from reqpay a
									where a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
									and a.recordstatus between 1 and (6-1)
									and a.companyid = " . $companyid . "
									order by a.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Permohonan Pembayaran Per Dokumen Belum Status Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$this->pdf->setwidths(array(
						10,
						20,
						25,
						25,
						25,
						60,
						25,
						25
					));
					$this->pdf->colheader = array(
						'No',
						'ID Transaksi',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['reqpayid'],
							$row['reqpayno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							'',
							$row['headernote'],
							findstatusname("apppayreq", $row['recordstatus'])
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/AP02_'.$comcode.'_DokumenPermohonanPembayaranBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/AP02_'.$comcode.'_DokumenPermohonanPembayaranBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
//29 Laporan Rekap Nota Retur Pembelian Per Dokumen Belum Status Max
				require_once("pdf_console.php");
				$this->pdf = new PDF();
				ob_start();

				$sqlcount	= "select ifnull(count(1),0)
								from notagrretur a
								join grretur b on b.grreturid=a.grreturid
								where a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
								and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
								and a.recordstatus between 1 and (3-1)
								and a.companyid = " . $companyid . "
								order by a.recordstatus
				";
				$count=Yii::app()->db->createCommand($sqlcount)->queryScalar();

				if ($count > 0)
				{
					$sql = "select a.notagrreturid,a.notagrreturno,a.docdate,b.grreturno,a.headernote,a.recordstatus
									from notagrretur a
									join grretur b on b.grreturid=a.grreturid
									where a.docdate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "'
									and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
									and a.recordstatus between 1 and (3-1)
									and a.companyid = " . $companyid . "
									order by a.recordstatus
					";
					$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->pdf->companyid = $companyid;
					
					$this->pdf->title    = 'Rekap Nota Retur Pembelian Per Dokumen Belum Status Max';
					$this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
					$this->pdf->AddPage('P');
					$this->pdf->setFont('Arial', 'B', 8);
					$this->pdf->sety($this->pdf->gety() + 10);
					$this->pdf->colalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$this->pdf->setwidths(array(
						10,
						20,
						25,
						25,
						25,
						60,
						25,
						25
					));
					$this->pdf->colheader = array(
						'No',
						'ID Transaksi',
						'No Transaksi',
						'Tanggal',
						'No Referensi',
						'Keterangan',
						'Status'
					);
					$this->pdf->RowHeader();
					$this->pdf->coldetailalign = array(
						'C',
						'C',
						'C',
						'C',
						'C',
						'L',
						'L'
					);
					$totalnominal1             = 0;
					$i                         = 0;
					$totaldisc1                = 0;
					$totaljumlah1              = 0;
					foreach ($dataReader as $row) {
						$i += 1;
						$this->pdf->setFont('Arial', '', 7);
						$this->pdf->row(array(
							$i,
							$row['notagrreturid'],
							$row['notagrreturno'],
							date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])),
							$row['grreturno'],
							$row['headernote'],
							findstatusname("appcutar", $row['recordstatus'])
						));
						$this->pdf->checkPageBreak(20);
					}
					//$this->pdf->Output();
					$date = date(Yii::app()->params['datetodb'],strtotime($enddate));
					$comcode = GetCompanyCode($companyid);
					$this->pdf->Output('/usr/share/nginx/html/agemlive/downloads/AP03_'.$comcode.'_DokumenNotaReturPembelianBelumStatusMax_'.$date.'.pdf','F');
					ob_clean();

					$filepath = realpath('/usr/share/nginx/html/agemlive/downloads/AP03_'.$comcode.'_DokumenNotaReturPembelianBelumStatusMax_'.$date.'.pdf');

/*				//send file whatsapp ke group
					$url = Yii::app()->params['ip'].'async_send_file_url_group_id';
					$img_url = 'https://my.woo-wa.com/wp-content/uploads/2018/12/Logo-Woo-WA-PNG-Berwarna-150px.png';
					$filepath = 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
					$data = array(
					  "group_id"	=> $groupdevelop,
						"key"				=> Yii::app()->params['key'],
					  "url"       => $filepath,
					  "message"		=> 'Tes doank'
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
					echo $res=curl_exec($ch);
					echo 'https://mifsaka.com/agemlive/downloads/DokumenBelumStatusMax_'.$date.'.pdf';
*/
					//send file telegram ke suriatik.atik
					$post = array('chat_id' => "1127702253",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke anggi.robertus
					$post = array('chat_id' => "1069717991",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
					
					//send file telegram ke ratna.sari
					$post = array('chat_id' => "798151575",'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

/*					//send file telegram ke group Kangaroo Excellent
					$post = array('chat_id' => $teleexcellentgroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);
*/
					//send file telegram ke group Chief Accounting
					$post = array('chat_id' => $telecagroupid,'document'=>new CurlFile($filepath));    
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,Yii::app()->params['tele'] . "/sendDocument");
					curl_setopt($ch, CURLOPT_POST, 1);   
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_exec ($ch);

				}
			}
			curl_close($ch);
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
  		GetMessage(true, $e->getMessage());
  		echo $e->getMessage();
		}
	}
}