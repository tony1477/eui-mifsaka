<?php
class CutarController extends Controller {
  public $menuname = 'cutar';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexinvoice() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchinvoice();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		parent::actionIndex();
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into cutar (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('inscutar').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'cutarid' => $id
			));
    }
  }
  private function SendNotifWaCustomer($menuname,$idarray) {
    // getrecordstatus
    $ids = null;
		if(is_array($idarray)==TRUE) {
			foreach($idarray as $id) {
				$sql = "select cutarid
							from cutar
							where recordstatus = getwfmaxstatbywfname('appcutar')
							and cutarid = ".$id;
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
				/*$getWaNumber = "select distinct f.fullname, g.wanumber,sum(bankamount) as bankamount, sum(cashamount) as cashamount, sum(returnamount) as retur
												from cutarinv a
												join cutar b on b.cutarid = a.cutarid
												-- join ttnt c on c.ttntid = b.ttntid
												join invoice c on c.invoiceid = a.invoiceid
												join giheader d on d.giheaderid = c.giheaderid
												join soheader e on e.soheaderid = d.soheaderid
												left join addressbook f on f.addressbookid = e.addressbookid
												left join addresscontact g on g.addressbookid = f.addressbookid
												where b.cutarid in ({$ids})
												group by fullname"
				;*/
				
				$getCustomer = "select distinct f.fullname, e.addressbookid, b.cutarno, docdate, g.companycode, b.cutarid, replace((select wanumber from addresscontact z where z.addressbookid = f.addressbookid AND z.wanumber LIKE '+%' AND z.wanumber NOT LIKE '% %' AND z.wanumber NOT LIKE '%-%' AND length(z.wanumber) > 10 limit 1),'+','') as wanumber, (select telegramid from addresscontact z where z.addressbookid = f.addressbookid  limit 1) as telegramid, b.companyid
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
					$telegramid = $row['telegramid'];
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
											-- left join addresscontact g on g.addressbookid = f.addressbookid
											where b.cutarid = {$row['cutarid']} and f.addressbookid = {$row['addressbookid']}
											) z";
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
					if ($wanumber > 0)
					{$sendtocustomer = "\n\n*SUDAH TERKIRIM ke No WA Customer*\n".$wanumber;}
					else
					{$sendtocustomer = "\n\n*BELUM ADA No WA Customer*\n".$row['fullname'];}
					
			//whatsapp2
					if($subcash == 0) {$totcashw="";} else {$totcashw = "Total Tunai : Rp. ".Yii::app()->format->formatCurrency($subcash)."\n";}
					if($subbank == 0) {$totbankw="";} else {$totbankw = "Total KU : Rp. ".Yii::app()->format->formatCurrency($subbank)."\n";}
					if($subdisc == 0) {$totdiscw="";} else {$totdiscw = "Total Diskon : Rp. ".Yii::app()->format->formatCurrency($subdisc)."\n";}
					if($subreturn == 0) {$totreturnw="";} else {$totreturnw = "Total Retur : Rp. ".Yii::app()->format->formatCurrency($subreturn)."\n";}
					if($subob == 0) {$totobw="";} else {$totobw = "Total OB : Rp. ".Yii::app()->format->formatCurrency($subob)."\n";}
					//if($subsisa == 0) {$totsisaw="";} else {$totsisaw = "Total Sisa : Rp. ".Yii::app()->format->formatCurrency($subsisa)."\n";}
					
					if ($i > 2) {$totalpesanw = $totcashw.$totbankw.$totdiscw.$totreturnw.$totobw."\n";} else {$totalpesanw = "";}
					
					$pesanwa = "*KONFIRMASI PELUNASAN PIUTANG*\n\nTerima kasih atas pelunasan Customer {$row['companycode']} :\n*".$row['fullname']."*\n\nPada Tanggal ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['docdate']))." No. {$row['cutarno']} dengan rincian sebagai berikut :\n\n".$pesanw.$totalpesanw."*Apabila :*\n1. Sudah Sesuai, abaikan pesan ini.\n2. Tidak Sesuai, silahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\nTerima kasih atas perhatian dan kerjasama pelanggan setia AKA Group.\n\n*JANGAN BALAS KE NO WA INI !!!*\n\n_*Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
					
					$pesannowa = "*Tidak Ada No WA* atau *No WA Belum Tepat* (PELUNASAN)\n\nCustomer {$row['companycode']} :\n*".$row['fullname']."*\n\nPada Tanggal ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['docdate']))." No. {$row['cutarno']} \n\n_*Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".$time;
					
			//telegram2
					if($subcash == 0) {$totcasht="";} else {$totcasht = "Total Tunai : Rp. ".Yii::app()->format->formatCurrency($subcash)."%0A";}
					if($subbank == 0) {$totbankt="";} else {$totbankt = "Total KU : Rp. ".Yii::app()->format->formatCurrency($subbank)."%0A";}
					if($subdisc == 0) {$totdisct="";} else {$totdisct = "Total Diskon : Rp. ".Yii::app()->format->formatCurrency($subdisc)."%0A";}
					if($subreturn == 0) {$totreturnt="";} else {$totreturnt = "Total Retur : Rp. ".Yii::app()->format->formatCurrency($subreturn)."%0A";}
					if($subob == 0) {$totobt="";} else {$totobt = "Total OB : Rp. ".Yii::app()->format->formatCurrency($subob)."%0A";}
					//if($subsisa == 0) {$totsisat="";} else {$totsisat = "Total Sisa : Rp. ".Yii::app()->format->formatCurrency($subsisa)."%0A";}
					
					if ($i > 2) {$totalpesant = $totcasht.$totbankt.$totdisct.$totreturnt.$totobt."%0A";} else {$totalpesant = "";}
					
					$pesantele = "<b>KONFIRMASI PELUNASAN PIUTANG</b>%0A%0ATerima kasih atas pelunasan Customer {$row['companycode']} :%0A<b>".$row['fullname']."</b>%0A%0APada Tanggal ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['docdate']))." No. {$row['cutarno']} dengan rincian sebagai berikut :%0A%0A".$pesant.$totalpesant."<b>Apabila :</b>%0A1. Sudah Sesuai, abaikan pesan ini.%0A2. Tidak Sesuai, silahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.%0A%0ATerima kasih atas perhatian dan kerjasama pelanggan setia AKA Group.%0A%0A<b>JANGAN BALAS KE NO WA INI !!!</b>%0A%0A<b><i>Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)</i></b>%0A".$time.$sendtocustomer;

					if ($companyid == 1) {$telegroupid = "-1001435078485";} //AKA
					else if ($companyid == 11) {$telegroupid = "-1001435078485";} //UD1
					else if ($companyid == 12) {$telegroupid = "-1001435078485";} //UD2
					else if ($companyid == 21) {$telegroupid = "-1001196054232";} //AKS
					else if ($companyid == 17) {$telegroupid = "-1001211726344";} //AMIN 
					else if ($companyid == 20) {$telegroupid = "-1001442360059";} //AKM
					else if ($companyid == 18) {$telegroupid = "-1001257116233";} //AJM
					else if ($companyid == 7)  {$telegroupid = "-1001402373281";} //AMI
					else if ($companyid == 15) {$telegroupid = "-1001264861899";} //AGEM
					else if ($companyid == 14) {$telegroupid = "-1001406450805";} //AKP
					
					//device-key
					$indosat = "d4987114-8563-4fdf-b15c-ed328057fae2";
					$siaga = "bf1ea6ba-ecc5-488e-9d6a-d75947ecebcf";
					$as = "";
					
					$teleuserid =  '1021823837'; //telegram ADS
					//$wano = '6281717212109'; //wa ADS
					$wano = '6285888885050'; //wa ADS
					$nowanumber = '6285265644828';
					//$wano = '6285376361879';
					$auditgroup = '628127090802-1580887417';

/*					$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".$pesantele."&parse_mode=html";
					$ch = curl_init($url);
					curl_exec ($ch);
					curl_reset($ch);*/
					
					if ($wanumber > 0)
					{
					//Whatsapp Customer
						sendwajapri($siaga,$pesanwa,$wanumber);
/*						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => Yii::app()->params['whatsva']."/sendText?id_device=1&message=".urlencode($pesanwa)."&tujuan=".$wanumber."@s.whatsapp.net",
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
						if ($res != '{"success":true,"message":"berhasil"}') {if ($wanumber > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$wanumber." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$wanumber." (".$row['fullname'].")\n";}}}
						
*/						sendwagroup($siaga,$pesanwa."\n\n_Tes Chatfire EUI_","6281717212109-1615804565");
					}
					else
					{
						//Whatsapp Group Tidak Ada No WA Customer
						sendwagroup($siaga,$pesannowa,$auditgroup);
/*						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => Yii::app()->params['whatsva']."/sendText?id_device=1&message=".urlencode($pesannowa)."&tujuan=".$auditgroup."@g.us",
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
*/							
/*						//Whatsapp Japri Tidak Ada No WA Customer
						$ch = curl_init();
						curl_setopt_array($ch, array(
							CURLOPT_URL => Yii::app()->params['whatsva']."/sendText?id_device=1&message=".urlencode($pesannowa)."&tujuan=".$nowanumber."@s.whatsapp.net",
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
						$res = curl_exec($ch);*/
					}
					
/*					$url = Yii::app()->params['ip'].'send_message';
					$data = array(
						"phone_no"=> $row['wanumber'],
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
					$res=curl_exec($ch);
					if ($res != 'Success') {if ($wanumber > 0) {if ($res != '') {$sendtocustomer = "\n\n*TIDAK TERKIRIM ke No WA Customer*\n".$wanumber." (".$row['fullname'].")\n".$res;} else {$sendtocustomer = "\n\n*GAGAL TERKIRIM ke No WA Customer*\n".$wanumber." (".$row['fullname'].")\n";}}}
					//echo $data_string;
*/					
					
					sendwajapri($siaga,$pesanwa,$wano);
/*				//Whatsva v3
					$ch = curl_init();
					curl_setopt_array($ch, array(
						CURLOPT_URL => Yii::app()->params['whatsva']."/sendText?id_device=1&message=".urlencode($pesanwa)."&tujuan=".$wano."@s.whatsapp.net",
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
			
				//Whatsva v4
					$ch = curl_init();
					curl_setopt_array($ch, array(
					CURLOPT_URL => "https://v4.whatsva.com/api/sendText?id_device=22&tujuan=".$wano."@s.whatsapp.net&message=".urlencode("*Whatsva Versi 4*\n\n".$pesanwa)."&id_user=44",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_HTTPHEADER => array(
							"apikey: BKHrsz3l"
						),
					));
					echo $res = curl_exec($ch);
					
					$url = Yii::app()->params['ip'].'send_message';
					$data = array(
						"phone_no"=> $wanum,
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
*/
					if ($telegramid > 0)
					{
						$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($pesanwa);
						$ch = curl_init();
						$optArray = array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true
						);
						curl_setopt_array($ch, $optArray);
						$result = curl_exec($ch);
					}
					
					$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegroupid."&text=".urlencode($pesanwa.$sendtocustomer);
					$ch = curl_init();
					$optArray = array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true
					);
					curl_setopt_array($ch, $optArray);
					$result = curl_exec($ch);
				}	
				curl_close($ch);    
			}
		}
	}
  public function search() {
    header("Content-Type: application/json");
    $cutarid         = isset($_POST['cutarid']) ? $_POST['cutarid'] : '';
    $cutarno         = isset($_POST['cutarno']) ? $_POST['cutarno'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $ttntid          = isset($_POST['ttntid']) ? $_POST['ttntid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cutarid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $rec          = array();
    $com          = array();
		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appcutar')")->queryScalar();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cutar t')
		->leftjoin('ttnt a', 'a.ttntid=t.ttntid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.cutarno,'') like :cutarno) and
						(coalesce(b.companyname,'') like :companyid) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.cutarid,'') like :cutarid) and
						(coalesce(a.docno,'') like :ttntid) and t.recordstatus < {$maxstat} and t.recordstatus in (".getUserRecordStatus('listcutar').")
						and t.companyid in (".getUserObjectWfValues('company','listcutar').")", array(
      ':cutarid' => '%' . $cutarid . '%',
      ':cutarno' => '%' . $cutarno . '%',
      ':companyid' => '%' . $companyid . '%',
      ':docdate' => '%' . $docdate . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.docno,b.companyname')->from('cutar t')
		->leftjoin('ttnt a', 'a.ttntid=t.ttntid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.cutarno,'') like :cutarno) and
						(coalesce(b.companyname,'') like :companyid) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.cutarid,'') like :cutarid) and
						(coalesce(a.docno,'') like :ttntid) and t.recordstatus < {$maxstat} and t.recordstatus in (".getUserRecordStatus('listcutar').")
						and t.companyid in (".getUserObjectWfValues('company','listcutar').")", array(
      ':cutarid' => '%' . $cutarid . '%',
      ':cutarno' => '%' . $cutarno . '%',
      ':companyid' => '%' . $companyid . '%',
      ':docdate' => '%' . $docdate . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cutarid' => $data['cutarid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'cutarno' => $data['cutarno'],
        'ttntid' => $data['ttntid'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchinvoice() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cutarinvid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cutarinv t')->join('invoice a', 'a.invoiceid=t.invoiceid')->join('currency b', 'b.currencyid=t.currencyid')->join('giheader c', 'c.giheaderid=a.giheaderid')->join('soheader d', 'd.soheaderid=c.soheaderid')->join('addressbook e', 'e.addressbookid=d.addressbookid')->leftjoin('notagir f', 'f.notagirid=t.notagirid')->where('cutarid = :cutarid', array(
      ':cutarid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.cutarinvid,t.cutarid,t.invoiceid,a.invoiceno,t.saldoinvoice,a.invoicedate,t.cashamount,t.bankamount,t.discamount,t.notagirid,f.notagirno,t.returnamount,t.obamount,
			t.saldoinvoice-(ifnull(t.cashamount,0)+ifnull(t.bankamount,0)+ifnull(t.discamount,0)+ifnull(t.returnamount,0)+ifnull(t.obamount,0)) as saldo,
			t.currencyid,b.currencyname,t.currencyrate,t.description,e.fullname as customername')->from('cutarinv t')->join('invoice a', 'a.invoiceid=t.invoiceid')->join('currency b', 'b.currencyid=t.currencyid')->join('giheader c', 'c.giheaderid=a.giheaderid')->join('soheader d', 'd.soheaderid=c.soheaderid')->join('addressbook e', 'e.addressbookid=d.addressbookid')->leftjoin('notagir f', 'f.notagirid=t.notagirid')->where('cutarid = :cutarid', array(
      ':cutarid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cutarinvid' => $data['cutarinvid'],
        'cutarid' => $data['cutarid'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'saldoinvoice' => Yii::app()->format->formatNumber($data['saldoinvoice']),
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'cashamount' => Yii::app()->format->formatNumber($data['cashamount']),
        'bankamount' => Yii::app()->format->formatNumber($data['bankamount']),
        'discamount' => Yii::app()->format->formatNumber($data['discamount']),
        'notagirid' => $data['notagirid'],
        'notagirno' => $data['notagirno'],
        'returnamount' => Yii::app()->format->formatNumber($data['returnamount']),
        'obamount' => Yii::app()->format->formatNumber($data['obamount']),
        'saldo' => Yii::app()->format->formatNumber($data['saldo']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'description' => $data['description'],
        'customername' => $data['customername']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$cmd             = Yii::app()->db->createCommand()->select('sum(a.amount-a.payamount) as saldoinvoice,
			sum(t.cashamount) as cashamount,
			sum(t.bankamount) as bankamount,
			sum(t.discamount) as discamount,
			sum(t.returnamount) as returnamount,
			sum(t.obamount) as obamount,
			sum((a.amount-a.payamount)-(ifnull(t.cashamount,0)+ifnull(t.bankamount,0)+ifnull(t.discamount,0)+ifnull(t.returnamount,0)+ifnull(t.obamount,0))) as saldo
			')->from('cutarinv t')->join('invoice a', 'a.invoiceid=t.invoiceid')->join('currency b', 'b.currencyid=t.currencyid')->where('cutarid = :cutarid', array(
      ':cutarid' => $id
    ))->queryRow();
		$footer[] = array(
      'invoiceno' => 'Total',
      'saldoinvoice' => Yii::app()->format->formatNumber($cmd['saldoinvoice']),
      'cashamount' => Yii::app()->format->formatNumber($cmd['cashamount']),
      'bankamount' => Yii::app()->format->formatNumber($cmd['bankamount']),
      'discamount' => Yii::app()->format->formatNumber($cmd['discamount']),
      'returnamount' => Yii::app()->format->formatNumber($cmd['returnamount']),
      'obamount' => Yii::app()->format->formatNumber($cmd['obamount']),
      'saldo' => Yii::app()->format->formatNumber($cmd['saldo']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertcutar(:vcompanyid,:vdocdate,:vttntid,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vrecordstatus',$arraydata[4], PDO::PARAM_STR);
		} else {
			$sql     = 'call Updatecutar(:vid,:vcompanyid,:vdocdate,:vttntid,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vdocdate', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vttntid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileCutar"]["name"]);
		if (move_uploaded_file($_FILES["FileCutar"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()));
					$docno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$ttntno = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$ttntid = Yii::app()->db->createCommand("select ttntid from ttnt 
						where companyid = ".$companyid." 
						and docno = '".$ttntno."'")->queryScalar();
					$abid = Yii::app()->db->createCommand("select cutarid from cutar 
						where companyid = ".$companyid."
						and cutarno = '".$docno."' 
						and docdate = '".$docdate."'
						and ttntid = ".$ttntid)->queryScalar();
					if ($abid == '') {					
						$headernote = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
						$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
						$this->ModifyData($connection,array('',$companyid,$docdate,$ttntid,$recordstatus));
						//get id addressbookid
						$abid = Yii::app()->db->createCommand("select cutarid from cutar 
							where companyid = ".$companyid."
							and cutarno = '".$docno."' 
							and docdate = '".date(Yii::app()->params['datetodb'], strtotime($_POST['docdate']))."'
							and ttntid = ".$ttntid)->queryScalar();
					}
					if ($abid != '') {
						if ($objWorksheet->getCellByColumnAndRow(7, $row)->getValue() != '') {
							$invoiceno = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$invoiceid = Yii::app()->db->createCommand("select invoiceid from invoice 
								where invoiceno = '".$invoiceno."'
								and companyid = ".$companyid." 							
								")->queryScalar();
							$saldoinvoice = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$invoicedate = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$cashamount = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$bankamount = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$discamount = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$returnamount = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
							$obamount = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$currencyname = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
							$currencyrate = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
							$description = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
							$this->ModifyDataDetail($connection,array('',$abid,$addresstypeid,$addressname,$rt,$rw,$cityid,$phoneno,$faxno,$lat,$lng));
						}
					}
				}
				$transaction->commit();
				GetMessage(true, 'insertsuccess', 1);
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(false, $e->getMessage(), 1);
			}
    }
	}
  public function actionSave() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['cutarid'])?$_POST['cutarid']:''),$_POST['companyid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),$_POST['ttntid']));
			$transaction->commit();
      GetMessage(true, 'insertsuccess', 1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage(), 1);
    }
  }
	private function ModifyDataDetail($connection,$arraydata){
		
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call InsertCutarinv(:vcutarid,:vinvoiceid,:vsaldoinvoice,:vinvoiceid,:vsaldoinvoice,:vinvoicedate,:vcashamount,:vbankamount,:vdiscamount,:vreturnamount,:vnotagirid,:vobamount,:vcurrencyid,:vcurrencyrate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateCutarinv(:vid,:vcutarid,:vinvoiceid,:vinvoicedate,:vcashamount,:vbankamount,:vdiscamount,:vreturnamount,:vnotagirid,:vobamount,:vcurrencyid,:vcurrencyrate,:vdescription,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
      }
      $command->bindvalue(':vcutarid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceid', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vinvoicedate', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vcashamount', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vbankamount', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vdiscamount', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vreturnamount', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vnotagirid', $arraydata[8], PDO::PARAM_STR);
      $command->bindvalue(':vobamount', $arraydata[9], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $arraydata[10], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $arraydata[11], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $arraydata[12], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      
	}
  public function actionSaveinvoice() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['cutarinvid'])?$_POST['cutarinvid']:''),$_POST['cutarid'],$_POST['invoiceid'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['invoicedate'])),$_POST['cashamount'],$_POST['bankamount'],
				$_POST['discamount'],$_POST['returnamount'],$_POST['notagirid'],$_POST['obamount'],$_POST['currencyid'],$_POST['currencyrate'],$_POST['description']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateCutarTTNT(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success',
            'div' => "Data generated"
          ));
        }
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage('failure', $e->getMessage());
      }
    }
    Yii::app()->end();
  }
  public function actionPurge() {
		parent::actionPurge();
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeCutar(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionPurgeinvoice() {		
    //parent::actionDelete();
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeCutarinv(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveCutar(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        $this->SendNotifWaCustomer($this->menuname,$id);
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Deletecutar(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select distinct a.cutarid,a.cutarno,a.docdate as cutardate,c.docno as ttntno,c.docdate as ttntdate,b.companyid
                        from cutar a
                        left join company b on b.companyid = a.companyid
                        left join ttnt c on c.ttntid = a.ttntid
                        left join cutarinv d on d.cutarid = a.cutarid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cutarid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('cutar');
    $this->pdf->AddPage('L', 'A4');
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['cutarno']);
      $this->pdf->text(160, $this->pdf->gety() + 2, 'TTNT ');
      $this->pdf->text(170, $this->pdf->gety() + 2, ': ' . $row['ttntno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['cutardate'])));
      $this->pdf->text(160, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(170, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['ttntdate'])));
      $sql1        = "select a.cutarid,b.invoiceno,f.fullname,b.invoicedate,a.saldoinvoice,a.cashamount,a.bankamount,a.discamount,a.returnamount,a.obamount,
							(a.cashamount+a.bankamount+a.discamount+a.returnamount+a.obamount) as jumlah,c.currencyname,a.currencyrate,a.description,
							(a.saldoinvoice-(a.cashamount+a.bankamount+a.discamount+a.returnamount+a.obamount)) as sisa
                            from cutarinv a
                            left join invoice b on b.invoiceid = a.invoiceid
                            left join currency c on c.currencyid = a.currencyid
							left join giheader d on d.giheaderid=b.giheaderid
                            left join soheader e on e.soheaderid=d.soheaderid
                            left join addressbook f on f.addressbookid=e.addressbookid
                            where a.cutarid = " . $row['cutarid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $total1      = 0;
      $totalqty1   = 0;
      $total2      = 0;
      $totalqty2   = 0;
      $total3      = 0;
      $totalqty3   = 0;
      $total4      = 0;
      $totalqty4   = 0;
      $total5      = 0;
      $totalqty5   = 0;
      $total6      = 0;
      $totalqty6   = 0;
      $total7      = 0;
      $totalqty7   = 0;
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
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        20,
        45,
        18,
        23,
        23,
        23,
        23,
        23,
        23,
        23,
        23
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'Customer',
        'Tgl Invoice',
        'Saldo Invoice',
        'Tunai',
        'Bank',
        'Diskon',
        'Retur',
        'OB',
        'Jumlah',
        'Sisa'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'L',
        'C',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->setFont('Arial', '', 7);
        $this->pdf->row(array(
          $i,
          $row1['invoiceno'],
          $row1['fullname'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
          Yii::app()->format->formatCurrency($row1['saldoinvoice']),
          Yii::app()->format->formatCurrency($row1['cashamount']),
          Yii::app()->format->formatCurrency($row1['bankamount']),
          Yii::app()->format->formatCurrency($row1['discamount']),
          Yii::app()->format->formatCurrency($row1['returnamount']),
          Yii::app()->format->formatCurrency($row1['obamount']),
          Yii::app()->format->formatCurrency($row1['jumlah']),
          Yii::app()->format->formatCurrency($row1['sisa'])
        ));
        $total  = $row1['saldoinvoice'] + $total;
        $total1 = $row1['cashamount'] + $total1;
        $total2 = $row1['bankamount'] + $total2;
        $total3 = $row1['discamount'] + $total3;
        $total4 = $row1['returnamount'] + $total4;
        $total5 = $row1['obamount'] + $total5;
        $total6 = $row1['jumlah'] + $total6;
        $total7 = $row1['sisa'] + $total7;
      }
      $this->pdf->setbordercell(array(
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB'
      ));
      $this->pdf->row(array(
        '',
        '',
        'Total',
        '',
        Yii::app()->format->formatNumber($total),
        Yii::app()->format->formatNumber($total1),
        Yii::app()->format->formatNumber($total2),
        Yii::app()->format->formatNumber($total3),
        Yii::app()->format->formatNumber($total4),
        Yii::app()->format->formatNumber($total5),
        Yii::app()->format->formatNumber($total6),
        Yii::app()->format->formatNumber($total7)
      ));
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '   Admin AR');
      $this->pdf->text(55, $this->pdf->gety() + 25, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'Chief Accounting');
      $this->pdf->checkNewPage(40);
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownload();
    $sql = "select ttntid,docdate,recordstatus
				from cutar a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cutarid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('ttntid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['ttntid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="cutar.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    unset($excel);
  }
}
