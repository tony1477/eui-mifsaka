<?php
class Runningwates1Command extends CConsoleCommand
{
	public function run($args)
	{
		$connection=Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try
		{
			$idarray = array(47510,47511,47534);
			// getrecordstatus
			$ids = null;
			if(is_array($idarray)==TRUE) {
				foreach($idarray as $id) {
					$sql = "select cutarid
								from cutar
								where recordstatus = getwfmaxstatbywfname('appcutar') and cutarid = ".$id;
					if($ids == null) {
						$ids = Yii::app()->db->createCommand($sql)->queryScalar();
					}
					else
					{
						$ids .= ','.Yii::app()->db->createCommand($sql)->queryScalar();
					}
					//var_dump($idarray);
				}
				// get customer number
				if($ids != null) {
					$getCustomer = "select distinct f.fullname, e.addressbookid, b.cutarno, docdate, g.companycode, b.cutarid, (select wanumber from addresscontact z where z.addressbookid = f.addressbookid limit 1) as wanumber,b.companyid
												from cutarinv a
												join cutar b on b.cutarid = a.cutarid
												join invoice c on c.invoiceid = a.invoiceid
												join giheader d on d.giheaderid = c.giheaderid
												join soheader e on e.soheaderid = d.soheaderid
												left join addressbook f on f.addressbookid = e.addressbookid
												left join company g on g.companyid = e.companyid
												where b.cutarid in ({$ids}) 
					";

					$res = Yii::app()->db->createCommand($getCustomer)->queryAll();
					
					foreach($res as $row) {
						$companyid = $row['companyid'];
						$wanumber = $row['wanumber'];
						$time = date('Y-m-d H:i:s');
						
						$getWaNumber = "select *, if(sisa=0,'LUNAS','BELUM LUNAS') as status 
												from (select c.invoiceno, c.payamount - (a.cashamount+a.bankamount+a.returnamount+a.discamount+a.obamount) as payamount, c.amount, c.amount - c.payamount + (a.cashamount+a.bankamount+a.returnamount+a.discamount+a.obamount) as saldoinvoice, a.cashamount, a.bankamount, a.returnamount, a.discamount, obamount, (c.amount - c.payamount) as sisa
												from cutarinv a
												join cutar b on b.cutarid = a.cutarid
												-- join ttnt c on c.ttntid = b.ttntid
												join invoice c on c.invoiceid = a.invoiceid
												join giheader d on d.giheaderid = c.giheaderid
												join soheader e on e.soheaderid = d.soheaderid
												left join addressbook f on f.addressbookid = e.addressbookid
												left join addresscontact g on g.addressbookid = f.addressbookid
												where b.cutarid = {$row['cutarid']} and f.addressbookid = {$row['addressbookid']}) z";
						$res1 = Yii::app()->db->createCommand($getWaNumber)->queryAll();
						$pesanw = '';
						$pesant = '';
						$i=1;
						$subcash = 0;
						$subbank = 0;
						$subdisc = 0;
						$subreturn = 0;
						$subob   = 0;
						$subsisa   = 0;
						foreach($res1 as $row1) {
				//whatsapp1
							if($row1['cashamount'] == 0) {$cashw="";} else {$cashw = "\n    _Tunai_ : Rp. ".Yii::app()->format->formatCurrency($row1['cashamount']);}
							if($row1['bankamount'] == 0) {$bankw="";} else {$bankw = "\n    _KU_ : Rp. ".Yii::app()->format->formatCurrency($row1['bankamount']);}
							if($row1['discamount'] == 0) {$discw="";} else {$discw = "\n    _Diskon_ : Rp. ".Yii::app()->format->formatCurrency($row1['discamount']);}
							if($row1['returnamount'] == 0) {$returnw="";} else {$returnw = "\n    _Retur_ : Rp. ".Yii::app()->format->formatCurrency($row1['returnamount']);}
							if($row1['obamount'] == 0) {$obw="";} else {$obw = "\n    _OB_ : Rp. ".Yii::app()->format->formatCurrency($row1['obamount']);}
							//if($row1['sisa'] == 0) {$sisaw="";} else {$sisaw = "\nSisa : Rp. ".Yii::app()->format->formatCurrency($row1['sisa']).' '.$row1['status']."\n\n";}
							
							$pesanw .=  $i.". ".$row1['invoiceno']." Rp. ".Yii::app()->format->formatCurrency($row1['amount'])."\n    Kum. Bayar : Rp. ".Yii::app()->format->formatCurrency($row1['payamount'])."\n    Saldo Invoice : Rp. ".Yii::app()->format->formatCurrency($row1['saldoinvoice'])."\n_Pelunasan secara_ :".$cashw.$bankw.$discw.$returnw.$obw."\nSisa : Rp. ".Yii::app()->format->formatCurrency($row1['sisa']). " *".$row1['status']."*\n\n";
							
				//telegram1
							if($row1['cashamount'] == 0) {$casht="";} else {$casht = "%0A    <i>Tunai</i> : Rp. ".Yii::app()->format->formatCurrency($row1['cashamount']);}
							if($row1['bankamount'] == 0) {$bankt="";} else {$bankt = "%0A    <i>KU</i> : Rp. ".Yii::app()->format->formatCurrency($row1['bankamount']);}
							if($row1['discamount'] == 0) {$disct="";} else {$disct = "%0A    <i>Diskon</i> : Rp. ".Yii::app()->format->formatCurrency($row1['discamount']);}
							if($row1['returnamount'] == 0) {$returnt="";} else {$returnt = "%0A    <i>Retur</i> : Rp. ".Yii::app()->format->formatCurrency($row1['returnamount']);}
							if($row1['obamount'] == 0) {$obt="";} else {$obt = "%0A    <i>OB</i> : Rp. ".Yii::app()->format->formatCurrency($row1['obamount']);}
							//if($row1['sisa'] == 0) {$sisat="";} else {$sisat = "%0ASisa : Rp. ".Yii::app()->format->formatCurrency($row1['sisa']).' '.$row1['status']."%0A%0A";}
							
							$pesant .=  $i.". ".$row1['invoiceno']." Rp. ".Yii::app()->format->formatCurrency($row1['amount'])."%0A    Kum. Bayar : Rp. ".Yii::app()->format->formatCurrency($row1['payamount'])."%0A    Saldo Invoice : Rp. ".Yii::app()->format->formatCurrency($row1['saldoinvoice'])."%0A<i>Pelunasan secara</i> :".$casht.$bankt.$disct.$returnt.$obt."%0ASisa : Rp. ".Yii::app()->format->formatCurrency($row1['sisa']). " <b>".$row1['status']."</b>%0A%0A";
							
							$i++;
							$subcash = $subcash + $row1['cashamount'];
							$subbank = $subbank + $row1['bankamount'];
							$subdisc = $subdisc + $row1['discamount'];
							$subreturn = $subreturn + $row1['returnamount'];
							$subob = $subob + $row1['obamount'];
							$subsisa = $subsisa + $row1['sisa'];
						}
						if ($wanumber != '')
						{$sendtocustomer = "%0A%0A<b><i>SUDAH TERKIRIM ke No WA Customer</i></b>%0A".$wanumber;}
						else
						{$sendtocustomer = "%0A%0A<b><i>BELUM TERKIRIM ke No WA Customer</i></b>%0A".$wanumber;}
						
				//whatsapp2
						if($subcash == 0) {$totcashw="";} else {$totcashw = "Total Tunai : Rp. ".Yii::app()->format->formatCurrency($subcash);}
						if($subbank == 0) {$totbankw="";} else {$totbankw = "\nTotal KU : Rp. ".Yii::app()->format->formatCurrency($subbank);}
						if($subdisc == 0) {$totdiscw="";} else {$totdiscw = "\nTotal Diskon : Rp. ".Yii::app()->format->formatCurrency($subdisc);}
						if($subreturn == 0) {$totreturnw="";} else {$totreturnw = "\nTotal Retur : Rp. ".Yii::app()->format->formatCurrency($subreturn);}
						if($subob == 0) {$totobw="";} else {$totobw = "\nTotal OB : Rp. ".Yii::app()->format->formatCurrency($subob);}
						//if($subsisa == 0) {$totsisaw="";} else {$totsisaw = "\nTotal Sisa : Rp. ".Yii::app()->format->formatCurrency($subsisa)."\n\n";}
						
						if ($i > 2) {$totalpesanw = $totcashw.$totbankw.$totdiscw.$totreturnw.$totobw."\n\n";} else {$totalpesanw = "";}
						
						$pesanwa = "*KONFIRMASI PELUNASAN PIUTANG*\n\nTanggal ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['docdate']))." No. {$row['cutarno']} untuk Customer {$row['companycode']} :\n\n*".$row['fullname']."*\n\n".$pesanw.$totalpesanw."*Apabila :*\n1. Sudah sesuai, abaikan pesan ini.\n2. Tidak Sesuai, silahkan konfirmasi ke Nomor WA {whatsappnumber}\n\nTerima kasih atas perhatian dan kerjasama pelanggan setia AKA Group.\n\n*JANGAN BALAS KE NO WA INI !!!*\n\n*_Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)_*\n".$time;
						
				//telegram2
						if($subcash == 0) {$totcasht="";} else {$totcasht = "Total Tunai : Rp. ".Yii::app()->format->formatCurrency($subcash);}
						if($subbank == 0) {$totbankt="";} else {$totbankt = "%0ATotal KU : Rp. ".Yii::app()->format->formatCurrency($subbank);}
						if($subdisc == 0) {$totdisct="";} else {$totdisct = "%0ATotal Diskon : Rp. ".Yii::app()->format->formatCurrency($subdisc);}
						if($subreturn == 0) {$totreturnt="";} else {$totreturnt = "%0ATotal Retur : Rp. ".Yii::app()->format->formatCurrency($subreturn);}
						if($subob == 0) {$totobt="";} else {$totobt = "%0ATotal OB : Rp. ".Yii::app()->format->formatCurrency($subob);}
						//if($subsisa == 0) {$totsisat="";} else {$totsisat = "%0ATotal Sisa : Rp. ".Yii::app()->format->formatCurrency($subsisa)."%0A%0A";}
						
						if ($i > 2) {$totalpesant = $totcasht.$totbankt.$totdisct.$totreturnt.$totobt."%0A%0A";} else {$totalpesant = "";}
						
						$pesantele = "<b>KONFIRMASI PELUNASAN PIUTANG</b>%0A%0ATanggal ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['docdate']))." No. {$row['cutarno']} untuk Customer {$row['companycode']} :%0A%0A<b>".$row['fullname']."</b>%0A%0A".$pesant.$totalpesant."<b>Apabila :</b>%0A1. Sudah sesuai, abaikan pesan ini.%0A2. Tidak Sesuai, silahkan konfirmasi ke Nomor WA {whatsappnumber}%0A%0ATerima kasih atas perhatian dan kerjasama pelanggan setia AKA Group.%0A%0A<b>JANGAN BALAS KE NO WA INI !!!</b>%0A%0A<b><i>Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)</i></b>%0A".$time.$sendtocustomer;

/*						if ($companyid == 1) {$telegroupid = "-426217078";} //AKA
						else if ($companyid == 11) {$telegroupid = "-426217078";} //UD1
						else if ($companyid == 12) {$telegroupid = "-426217078";} //UD2
						else if ($companyid == 21) {$telegroupid = "-334252622";} //AKS
						else if ($companyid == 17) {$telegroupid = "-444175167";} //AMIN
						else if ($companyid == 20) {$telegroupid = "-412518882";} //AKM
						else if ($companyid == 18) {$telegroupid = "-329101365";} //AJM
						else if ($companyid == 7) {$telegroupid = "-439944673";} //AMI
						else if ($companyid == 15) {$telegroupid = "-461058988";} //AGEM
						else if ($companyid == 14) {$telegroupid = "-490976841";} //AKP
*/						
						if ($companyid == 1) {$telegroupid = "-400153522";} //AKA Tester
						else if ($companyid == 11) {$telegroupid = "-393940074";} //UD1 Tester
						else if ($companyid == 12) {$telegroupid = "-489286947";} //UD2 Tester
						else if ($companyid == 21) {$telegroupid = "-447885870";} //AKS Tester
						else if ($companyid == 17) {$telegroupid = "-388708629";} //AMIN Tester
						else if ($companyid == 20) {$telegroupid = "-465505907";} //AKM Tester
						else if ($companyid == 18) {$telegroupid = "-395367974";} //AJM Tester
						else if ($companyid == 7) {$telegroupid = "-465982804";} //AMI Tester
						else if ($companyid == 15) {$telegroupid = "-440886613";} //AGEM Tester
						else if ($companyid == 14) {$telegroupid = "-426162559";} //AKP Tester
						    
						$teleuserid =  '1021823837'; //telegram ADS
						$wanumber = '+6281717212109'; //wa ADS
						
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".$pesantele."&parse_mode=html";
						$ch = curl_init($url);
						curl_exec ($ch);
						curl_reset($ch);              
						
						$url = Yii::app()->params['ip'].'send_message';
						$data = array(
							"phone_no"=> $wanumber,
							"key"		=> Yii::app()->params['key'],
							"message"	=> $pesanwa,
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
						
						//echo $data_string;
					}
					curl_close($ch);    
				}
			}
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			//$transaction->rollBack();
      GetMessage(true, $e->getMessage());
      echo $e->getMessage();
		}
	}
}