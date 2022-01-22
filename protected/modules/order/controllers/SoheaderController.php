<?php
class SoheaderController extends Controller {
  public $menuname = 'soheader';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexcombo() {
    if (isset($_GET['grid']))
      echo $this->searchcombo();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
    if (isset($_GET['grid']))
      echo $this->actionSearchDetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdisc() {
    if (isset($_GET['grid']))
      echo $this->actionSearchDisc();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (!isset($_GET['id'])) {
      $dadate              = new DateTime('now');
			$sql = "insert into soheader (sodate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insso').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'soheaderid' => $id
			));
    }
  }
  public function actionGetAttr() {
      $materialtypeid = $_REQUEST['materialtypeid'];
      $sql = "select ifnull(iseditpriceso,0) as iseditpriceso, ifnull(iseditdiscso,0) as iseditdiscso, ifnull(isedittop,0) as isedittop
      from materialtype a 
      where a.materialtypeid = ".$materialtypeid;
      $q = Yii::app()->db->createCommand($sql)->queryRow();
      echo CJSON::encode(array(
          'iseditpriceso' => $q['iseditpriceso'],
          'iseditdiscso' => $q['iseditdiscso'],
          'isedittop' => $q['isedittop'],
      ));
  }
  public function actionSetMaterialtype() {
        // delete sodetail and sodisc
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        //$cmd = Yii::app()->db->createCommand;
        try {
            $sql = "call setMaterialtypeCustomer(:vsoheaderid,:vaddressbookid,:vmaterialtypeid,:vcreatedby)";
            $command = $connection->createCommand($sql);
            $command->bindvalue(':vsoheaderid', $_REQUEST['soheaderid'], PDO::PARAM_INT);
            $command->bindvalue(':vaddressbookid', $_REQUEST['addressbookid'], PDO::PARAM_INT);
            $command->bindvalue(':vmaterialtypeid', $_REQUEST['materialtypeid'], PDO::PARAM_INT);
            $command->bindvalue(':vcreatedby', Yii::app()->user->id, PDO::PARAM_STR);
            $command->execute();
            
            if (isset($_POST['materialtypeid'])) {
                $q = Yii::app()->db->createCommand("select ifnull(iseditpriceso,0) as iseditprice,ifnull(iseditdiscso,0) as iseditdisc,
                ifnull(isedittop,0) as isedittop, (select sopaymethodid from custdisc where addressbookid = {$_REQUEST['addressbookid']} and materialtypeid = {$_REQUEST['materialtypeid']}) as paymentmethodid
                from materialtype where materialtypeid = ".$_POST['materialtypeid'])->queryRow();
            }
            $paycode = Yii::app()->db->createCommand("select paycode from paymentmethod where paymentmethodid = ".$q['paymentmethodid'])->queryScalar();
            echo json_encode (array(
                'status' => 'success',
                'pesan' => getCatalog('alreadysaved'),
                'iseditprice' => $q['iseditprice'],
                'iseditdisc' => $q['iseditdisc'],
                'isedittop' => $q['isedittop'],
                'paymentmethodid' => $q['paymentmethodid'],
                'paycode' => $paycode,
            ));
            //GetMessage('success','alreadysaved');
            $transaction->commit();
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage(), 1);
      }
      
  }
  public function actionGetSotype() {
      $sql = "select sotype from soheader where soheaderid = ".$_REQUEST['soheaderid'];
      $query  = Yii::app()->db->createCommand($sql)->queryScalar();
      $sotype = 0;
      if($query == 2) {
          $sotype=1;
      }
      echo CJSON::encode(array(
        'sotype' => $sotype
      ));
  }
  public function actionGeneratepackagedetail(){
      if (isset($_POST['soheaderid']) && isset($_POST['packageid'])) {
      
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GeneratePackageSO(:vid, :vhid,:vcompanyid,:vqty,:vaddressbookid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['packageid'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['soheaderid'], PDO::PARAM_INT);
        $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_INT);
        $command->bindvalue(':vqty', $_REQUEST['qty'], PDO::PARAM_INT);
        $command->bindvalue(':vaddressbookid', $_REQUEST['addressbookid'], PDO::PARAM_INT);
        $command->bindvalue(':vcreatedby', Yii::app()->user->id, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
      }
    }
    Yii::app()->end();   
    }
	public function actionGeneratedetail() {
    if (isset($_POST['id'])) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateSOPO(:vid, :vhid, :vcompanyid, :vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_INT);
        $command->bindvalue(':vcreatedby', Yii::app()->user->id, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
      }
    }
    Yii::app()->end();
  }
  public function actionGenerateaddress() {
		$sql = "select concat(addressname,ifnull(cityname,'')) 
			from address a 
			join addressbook b on b.addressbookid = a.addressbookid 
			left join city c on c.cityid = a.cityid 
			where b.addressbookid = ".$_POST['id']." 
			limit 1";
		$address = Yii::app()->db->createCommand($sql)->queryScalar();
    if (Yii::app()->request->isAjaxRequest) {
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateCustDisc(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
      }
      catch (Exception $e) {
        $transaction->rollBack();
      }
      echo CJSON::encode(array(
        'shipto' => $address,
        'billto' => $address
      ));
      Yii::app()->end();
    }
  }
  public function actiongetTop() {
        $paydays = $_POST['payday'];
        $sql = "select paymentmethodid, paycode from paymentmethod where paydays=".$paydays;
        $q = Yii::app()->db->createCommand($sql)->queryRow();
        echo json_encode (array(
            'status' => 'success',
            'paycode' => $q['paycode'],
            'paymentmethodid' => $q['paymentmethodid']
        ));
  }
  private function SendNotifWaCustomer($menuname,$idarray) {
    // getrecordstatus
		$ids = null;
		if(is_array($idarray)==TRUE) {
			foreach($idarray as $id) {
				$sql = "select soheaderid
							from soheader
							where recordstatus = getwfmaxstatbywfname('appso') and soheaderid = ".$id;
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
				$getCustomer = "select a.soheaderid,c.fullname, a.companyid, a.sodate, a.sono, d.companycode, a.totalbefdisc, a.totalaftdisc, replace((select wanumber from addresscontact z where z.addressbookid = a.addressbookid AND z.wanumber LIKE '+%' AND z.wanumber NOT LIKE '% %' AND z.wanumber NOT LIKE '%-%' AND length(z.wanumber) > 10 limit 1),'+','') as wanumber, (select telegramid from addresscontact z where z.addressbookid = a.addressbookid limit 1) as telegramid
											from soheader a
											join addressbook c on c.addressbookid = a.addressbookid
											join company d on d.companyid = a.companyid
											where a.soheaderid in ({$ids})
											group by soheaderid
				";

				$res = Yii::app()->db->createCommand($getCustomer)->queryAll();
				
				foreach($res as $row) {
					$companyid = $row['companyid'];
					$wanumber = $row['wanumber'];
					$telegramid = $row['telegramid'];
					$time = date('Y-m-d H:i:s');
					
					$getWaNumber = "select e.productname, (b.qty) as qty, (b.price) as price, f.uomcode, (b.qty*price) as nilai
										from soheader a
										join sodetail b on b.soheaderid = a.soheaderid
										join product e on e.productid = b.productid
										left join unitofmeasure f on f.unitofmeasureid = b.unitofmeasureid
										where a.soheaderid = {$row['soheaderid']}
										-- group by productname 
										order by b.sodetailid
					";
		
					$pesanw = '';
					$pesant = '';
					$sendtocustomer = '';
					$i=1;
					$res1 = Yii::app()->db->createCommand($getWaNumber)->queryAll();
					$bilangan = explode(".", $row['totalaftdisc']);
					
					$sql2 = "select a.discvalue
					from sodisc a
					where a.soheaderid = {$row['soheaderid']}";
					$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
					$discvaluew   = '';
					$discvaluet   = '';
					foreach ($dataReader2 as $row2) {
						if ($discvaluew == '') {
							$discvaluew = Yii::app()->format->formatCurrency($row2['discvalue']);
						} else {
							$discvaluew = $discvaluew . ' +' . Yii::app()->format->formatCurrency($row2['discvalue']);
						}
						if ($discvaluet == '') {
							$discvaluet = Yii::app()->format->formatCurrency($row2['discvalue']);
						} else {
							$discvaluet = $discvaluet . ' ' . Yii::app()->format->formatCurrency($row2['discvalue']);
						}
					}
					
					foreach($res1 as $row1) {
						$pesant .= $i.". ".Yii::app()->format->formatCurrency($row1['qty'])." ".$row1['uomcode']." ".$row1['productname']." @ Rp.".Yii::app()->format->formatCurrency($row1['price'])." Jumlah Rp.".Yii::app()->format->formatCurrency($row1['nilai'])."%0A%0A";
						//$pesant .= "";
						
						$pesanw .= $i.". ".Yii::app()->format->formatCurrency($row1['qty'])." ".$row1['uomcode']." ".$row1['productname']."\n\n";
						
						$pesanwfull .= $i.". ".Yii::app()->format->formatCurrency($row1['qty'])." ".$row1['uomcode']." ".$row1['productname']." @ Rp.".Yii::app()->format->formatCurrency($row1['price'])." Jumlah Rp.".Yii::app()->format->formatCurrency($row1['nilai'])."\n\n";
						$i++;
					}
					if ($wanumber > 0)
					{$sendtocustomer = "\n\n*SUDAH TERKIRIM ke No WA Customer*\n".$wanumber;}
					else
					{$sendtocustomer = "\n\n*BELUM ADA No WA Customer*\n".$row['fullname'];}
					
					$pesanwa = 
					"*KONFIRMASI PESANAN PELANGGAN*\n\nTerima kasih atas pesanan Customer ".$row['companycode']." :\n*".$row['fullname']."*\n\nPada Tanggal ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['sodate']))." No. ".$row['sono']." dengan rincian sebagai berikut:\n\n".$pesanw."*Apabila* :\n1. Sudah Sesuai, abaikan pesan ini.\n2. Tidak Sesuai, silahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\nTerima kasih atas perhatian dan kerjasama pelanggan setia AKA Group.\n\n*JANGAN BALAS KE NO WA INI*\n\n_*Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".
					$time;
					
					$pesannowa = 
					"*Tidak Ada No WA* atau *No WA Belum Tepat* (PESANAN)\n\nCustomer ".$row['companycode']." :\n*".$row['fullname']."*\n\nPada Tanggal ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['sodate']))." No. ".$row['sono']."\n\n_*Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".
					$time;
					
					$pesanwafull = 
					"*KONFIRMASI PESANAN PELANGGAN*\n\nTerima kasih atas pesanan Customer ".$row['companycode']." :\n*".$row['fullname']."*\n\nPada Tanggal ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['sodate']))." No. ".$row['sono']." dengan rincian sebagai berikut:\n\n".$pesanwfull."*Total Pesanan Sblm Diskon* : ".Yii::app()->format->formatCurrency($row['totalbefdisc'])."\n*Diskon* ({$discvaluew} ): Rp. ".Yii::app()->format->formatCurrency($row['totalbefdisc'] - $row['totalaftdisc'])."\n\n*Total Pesanan Stlh Diskon* : Rp. ".Yii::app()->format->formatCurrency($row['totalaftdisc'])."\n(" . eja($bilangan[0]) . ")\n\n*Apabila* :\n1. Sudah Sesuai, abaikan pesan ini.\n2. Tidak Sesuai, silahkan konfirmasi dengan klik >> https://t.me/kangaroospringbed_bot atau https://wa.me/6285272087379 , dengan melampirkan pesan ini.\n\nTerima kasih atas perhatian dan kerjasama pelanggan setia AKA Group.\n\n*JANGAN BALAS KE NO WA INI*\n\n_*Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".
					$time;
					

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

					$teleuserid =  '875856213'; //telegram ADS
					//$wano = '6281717212109'; //wa ADS
					$wano = '6285888885050'; //wa ADS
					$nowanumber = '6285265644828';
					//$wano = '6285376361879';
					$auditgroup = '628127090802-1580887417';
					
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
*/				
					if ($companyid <> 18)
					{
						if ($wanumber > 0)
						{
						//Whatsapp Customer
							sendwajapri($siaga,$pesanwa,$wanumber);
/*							$ch = curl_init();
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
*/							
							sendwagroup($siaga,$pesanwa."\n\n_Tes Chatfire EUI_","6281717212109-1615804565");
						}
						else
						{
							//Whatsapp Group Tidak Ada No WA Customer
							sendwagroup($siaga,$pesannowa,$auditgroup);
/*							$ch = curl_init();
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
							
							//Whatsapp Japri Tidak Ada No WA Customer
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
						
/*						$url = Yii::app()->params['ip'].'send_message';
						$data = array(
							"phone_no"=> $wanumber,
							"key"		=> Yii::app()->params['key'],
							"message"	=> $pesanwa
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
					//Telegram Customer
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
			else
			{
				foreach($idarray as $id) {
					$sql = "select soheaderid
								from soheader
								where soheaderid = ".$id;
					if($ids == null) {
						$ids = Yii::app()->db->createCommand($sql)->queryScalar();
					}
					else
					{
						$ids .= ','.Yii::app()->db->createCommand($sql)->queryScalar();
					}
					//var_dump($idarray);
				}
				$getSalesOrder = "select a.soheaderid, c.fullname, a.sodate, a.sono, d.companycode, a.totalbefdisc, a.totalaftdisc, a.statusname, a.creditlimit, a.currentlimit, a.pendinganso, a.currentlimit+a.pendinganso+a.totalaftdisc as total, a.top, b.fullname as sales
											from soheader a
											join employee b on b.employeeid = a.employeeid
											join addressbook c on c.addressbookid = a.addressbookid
											join company d on d.companyid = a.companyid
											where a.soheaderid in ({$ids})
											group by soheaderid
				";

				$res = Yii::app()->db->createCommand($getSalesOrder)->queryAll();
				
				foreach($res as $row) {
					$companyid = $row['companyid'];
					$wanumber = $row['wanumber'];
					$telegramid = $row['telegramid'];
					$time = date('Y-m-d H:i:s');
					
					$pesanwa = 
					"Sales Order No.: ".$row['sono']."\nTanggal: ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['sodate']))."\nCustomer ".$row['companycode'].": *".$row['fullname']."*\nSales: ".$row['sales']."\n\nDengan Rincian:\nPiutang Rp. ".Yii::app()->format->formatCurrency($row['currentlimit'])."\nPendingan SO Rp. ".Yii::app()->format->formatCurrency($row['pendinganso'])."\nSO stlh diskon Rp.".Yii::app()->format->formatCurrency($row['totalaftdisc'])."\nTotal Rp. ".Yii::app()->format->formatCurrency($row['total'])." *VS* Plafon Rp. ".Yii::app()->format->formatCurrency($row['creditlimit'])."\nUmur Piutang: ".$row['top']."\n\nTelah disetujui oleh bagian terkait dengan status *".$row['statusname']."*, silahkan _*Review*_ lalu _*Approve*_ / _*Reject*_ pada Aplikasi ERP AKA Group.\n\n_*Pesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)*_\n".
					$time;
					
					$pesantele = 
					"Sales Order No.: ".$row['sono']."\nTanggal: ".date(Yii::app()->params['dateviewfromdb'],strtotime($row['sodate']))."\nCustomer ".$row['companycode'].": ".$row['fullname']."\nSales: ".$row['sales']."\n\nDengan Rincian:\nPiutang Rp. ".Yii::app()->format->formatCurrency($row['currentlimit'])."\nPendingan SO Rp. ".Yii::app()->format->formatCurrency($row['pendinganso'])."\nSO stlh diskon Rp.".Yii::app()->format->formatCurrency($row['totalaftdisc'])."\nTotal Rp. ".Yii::app()->format->formatCurrency($row['total'])." VS Plafon Rp. ".Yii::app()->format->formatCurrency($row['creditlimit'])."\nUmur Piutang: ".$row['top']."\n\nTelah disetujui oleh bagian terkait dengan status ".$row['statusname'].", silahkan Review lalu Approve / Reject pada Aplikasi ERP AKA Group.\n\nPesan ini dikirim Otomatis oleh SIAGA (System Information AKA Group - Automatic)\n".
					$time;
					
					$getWaNumber = "SELECT e.useraccessid,b.groupaccessid,replace(e.wanumber,'+','') as wanumber,e.telegramid
									FROM soheader a
									JOIN wfgroup b ON b.wfbefstat=a.recordstatus AND b.workflowid=15
									JOIN groupmenuauth c ON c.groupaccessid=b.groupaccessid AND c.menuauthid=5 AND c.menuvalueid=a.companyid
									JOIN groupmenuauth c2 ON c2.groupaccessid=b.groupaccessid AND c2.menuauthid=22 AND c2.menuvalueid=(SELECT a1.plantid FROM sloc a1 JOIN sodetail b1 ON b1.slocid=a1.slocid WHERE b1.soheaderid=a.soheaderid LIMIT 1)
									JOIN usergroup d ON d.groupaccessid=c.groupaccessid
									JOIN useraccess e ON e.useraccessid=d.useraccessid AND e.recordstatus=1 AND e.useraccessid<>2 AND e.useraccessid<>106
									-- AND e.useraccessid<>3
									WHERE a.soheaderid = {$row['soheaderid']}
					";
					$res1 = Yii::app()->db->createCommand($getWaNumber)->queryAll();
					
					foreach($res1 as $row1)
					{
						$wanumber = $row1['wanumber'];
						$telegramid = $row1['telegramid'];
						if ($row1['useraccessid'] == 3){$ui=" - eui ".$row1['groupaccessid'];}else{$ui="";}
/*					//Whatsapp Japri
						$ch = curl_init();
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
*/						
						if ($telegramid > 0)
						{
							$url = Yii::app()->params['tele']."/sendMessage?chat_id=".$telegramid."&text=".urlencode($pesantele.$ui);
							$ch = curl_init();
							$optArray = array(
								CURLOPT_URL => $url,
								CURLOPT_RETURNTRANSFER => true
							);
							curl_setopt_array($ch, $optArray);
							$result = curl_exec($ch);
						}
					}
				curl_close($ch);
				}
			}
		}
	}
  public function search() {
    header("Content-Type: application/json");
		$soheaderid   = isset($_POST['soheaderid']) ? $_POST['soheaderid'] : '';
    $companyname  = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $sono        	= isset($_POST['sono']) ? $_POST['sono'] : '';
    $sales        = isset($_POST['sales']) ? $_POST['sales'] : '';
    $customer  		= isset($_POST['customer']) ? $_POST['customer'] : '';
		$pocustno     = isset($_POST['pocustno']) ? $_POST['pocustno'] : '';
		$pono     		= isset($_POST['pono']) ? $_POST['pono'] : '';
		$headernote   = isset($_POST['headernote']) ? $_POST['headernote'] : '';
		$page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 't.soheaderid';
		$order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$connection		= Yii::app()->db;
		$maxstat			= $connection->createCommand("select getwfmaxstatbywfname('appso')")->queryScalar();
		
		$from = '
			from soheader t 
			left join company a on a.companyid = t.companyid 
			left join addressbook b on b.addressbookid = t.addressbookid 
			left join tax c on c.taxid = t.taxid 
			left join employee d on d.employeeid = t.employeeid 
			left join paymentmethod e on e.paymentmethodid = t.paymentmethodid 
			left join materialtype f on f.materialtypeid = t.materialtypeid 
			left join packages g on g.packageid = t.packageid ';
		$where = "
			-- where (soheaderid like '%".$soheaderid."%') and (coalesce(sono,'') like '%".$sono."%') and (coalesce(b.fullname,'') like '%".$customer."%') and (coalesce(companyname,'') like '%".$companyname."%') and (coalesce(t.pocustno,'') like '%".$pocustno."%') and (coalesce(t.pono,'') like '%".$pono."%') and (t.headernote like '%".$headernote."%')
		where b.iscustomer = 1 and t.recordstatus < {$maxstat} and t.recordstatus in (".getUserRecordStatus('listso').")
		and (t.soheaderid in (select distinct a1.soheaderid from sodetail a1 join sloc b1 on b1.slocid=a1.slocid where b1.plantid in (".getUserObjectValues('plant')."))
		or t.soheaderid in (select yy.soheaderid from sodetail xx right join soheader yy on xx.soheaderid = yy.soheaderid where yy.companyid in(".getUserObjectWfValues('company','appso').") and xx.slocid is null))
    and t.companyid in (".getUserObjectWfValues('company','appso').") ";
				
		if ((isset($soheaderid)) && ($soheaderid != ''))
		{	$where .= " and t.soheaderid like '%". $soheaderid. "%'	"; }
		if ((isset($sono)) && ($sono != ''))
		{	$where .= " and t.sono like '%". $sono. "%'	"; }
		if ((isset($customer)) && ($customer != ''))
		{ $where .= " and b.fullname like '%". $customer. "%'	"; }
		if ((isset($companyname)) && ($companyname != ''))
		{	$where .= " and a.companyname like '%". $companyname. "%'	"; }
		if ((isset($sales)) && ($sales != ''))
		{	$where .= " and d.fullname like '%". $sales. "%'	"; }
		if ((isset($pocustno)) && ($pocustno != ''))
		{ $where .= " and t.pocustno like '%". $pocustno. "%'	"; }
		if ((isset($pono)) && ($pono != ''))
		{ $where .= " and t.pono like '%". $pono. "%'	"; }
		if ((isset($headernote)) && ($headernote != ''))
		{ $where .= " and t.headernote like '%". $headernote. "%'	"; }
	
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = '
			select t.soheaderid,t.sono,t.sodate,t.companyid,a.companyname,t.addressbookid,b.fullname,t.top,t.creditlimit,t.totalbefdisc,
				t.totalaftdisc,t.statusname,t.taxid,c.taxcode, t.pocustno,t.employeeid,d.fullname as employeename,t.currentlimit,
				t.paymentmethodid,e.paycode,b.overdue,t.shipto,t.billto,t.headernote,t.poheaderid,t.pono, 
				case when (((t.currentlimit + t.totalaftdisc + t.pendinganso) > t.creditlimit) and (b.top > 0)) then 1  
				when (((t.currentlimit + t.totalaftdisc + t.pendinganso) <= t.creditlimit) and (b.top > 0)) then 2  
				when (((t.currentlimit + t.totalaftdisc + t.pendinganso) > t.creditlimit) and (b.top <= 0)) then 3 
				else 4	end as warna,
				t.pendinganso,
				t.recordstatus, t.isdisplay, t.sotype,case when sotype = 1 then "Jenis Material" when sotype = 2 then "PAKET" end as sotypename,
                t.materialtypeid, t.packageid,f.description, g.packagename,t.qtypackage,t.createddate,t.updatedate
			'.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$sql = $sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows;
		$cmd = $connection->createCommand($sql)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'soheaderid' => $data['soheaderid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'pocustno' => $data['pocustno'],
        'addressbookid' => $data['addressbookid'],
        'isdisplay' => $data['isdisplay'],
        'sotype' => $data['sotype'],
        'sotypename' => $data['sotypename'],
        'materialtypeid' => $data['materialtypeid'],
        'description' => $data['description'],
        'packageid' => $data['packageid'],
        'packagename' => $data['packagename'],
        'qtypackage' => $data['qtypackage'],
        'customername' => $data['fullname'],
        'currentlimit' => Yii::app()->format->formatCurrency($data['currentlimit']),
        'creditlimit' => Yii::app()->format->formatCurrency($data['creditlimit']),
        'warna' => $data['warna'],
        'employeeid' => $data['employeeid'],
        'employeename' => $data['employeename'],
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'sono' => $data['sono'],
        'sodate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['sodate'])),
        'headernote' => $data['headernote'],
        'shipto' => $data['shipto'],
        'billto' => $data['billto'],
        'top' => $data['top'],
        'pendinganso' => Yii::app()->format->formatCurrency($data['pendinganso']),
        'totalbefdisc' => Yii::app()->format->formatCurrency($data['totalbefdisc']),
        'totalaftdisc' => Yii::app()->format->formatCurrency($data['totalaftdisc']),
        'taxid' => $data['taxid'],
        'taxcode' => $data['taxcode'],
				'recordstatus'=> $data['recordstatus'],
        'recordstatussoheader' => $data['statusname'],
        'createddate' => $data['createddate'],
        'updatedate' => $data['updatedate']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function searchcombo() {
    header("Content-Type: application/json");
		$soheaderid   = isset($_GET['q']) ? $_GET['q'] : '';
    $sono        	= isset($_GET['q']) ? $_GET['q'] : '';
    $customer  		= isset($_GET['q']) ? $_GET['q'] : '';
		$pocustno     = isset($_GET['q']) ? $_GET['q'] : '';
		$headernote   = isset($_GET['q']) ? $_GET['q'] : '';
		$page         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows         = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort         = isset($_GET['sort']) ? strval($_GET['sort']) : 't.soheaderid';
		$order        = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$connection		= Yii::app()->db;
		$from = '
			from soheader t 
			left join company a on a.companyid = t.companyid 
			left join addressbook b on b.addressbookid = t.addressbookid ';
		$where = "
			where ((soheaderid like '%".$soheaderid."%') or (sono like '%".$sono."%') or (coalesce(b.fullname,'') like '%".$customer."%') 
				or (coalesce(t.pocustno,'') like '%".$pocustno."%') or (coalesce(t.headernote,'') like '%".$headernote."%'))
				and b.iscustomer = 1 and t.recordstatus = 6 and t.companyid in (".getUserObjectValues('company').")
				and t.soheaderid in (select distinct j.soheaderid from sodetail j where j.qty > j.giqty and j.slocid in (".getUserObjectValues('sloc').")) ";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.soheaderid,t.sono,t.sodate,a.companyname,b.fullname,t.pocustno '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'sodate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['sodate'])),
        'companyname' => $data['companyname'],
        'pocustno' => $data['pocustno'],
        'customername' => $data['fullname'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchDetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'sodetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $pkgid = Yii::app()->db->createCommand("select ifnull(count(packageid),0) from soheader where soheaderid = ".$id)->queryScalar();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('sodetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('soheaderid = :soheaderid', array(
      ':soheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.currencyname,d.sloccode,
			(t.price * t.qty * t.currencyrate) as amount,
			ifnull((select sum(z.qty) from productstock z where z.productid = t.productid and z.unitofmeasureid = t.unitofmeasureid and z.slocid = t.slocid),0) as qtystock')->from('sodetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('soheaderid = :soheaderid', array(
      ':soheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'sodetailid' => $data['sodetailid'],
        'soheaderid' => $data['soheaderid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'price' => Yii::app()->format->formatNumber($data['price']),
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
        'giqty' => Yii::app()->format->formatNumber($data['giqty']),
        'total' => Yii::app()->format->formatNumber($data['amount']),
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'currencyid' => $data['currencyid'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'delvdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])),
        'currencyname' => $data['currencyname'],
        'itemnote' => $data['itemnote'],
        'isbonus' => $data['isbonus']
      );
    }
    $cmd      = Yii::app()->db->createCommand()->select('sum(t.qty) as totalqty, sum(t.price*t.qty*t.currencyrate) as totalamount')->from('sodetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('soheaderid = :soheaderid', array(
      ':soheaderid' => $id
    ))->queryRow();
    
    $footer[] = array(
      'productid' => 'Total',
      'productname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($cmd['totalqty']),
      'total' => Yii::app()->format->formatNumber($cmd['totalamount'])
    );
    
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchdisc() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'sodiscid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset = ($page - 1) * $rows;
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    $footer = array();
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('sodisc t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('sodisc t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select()->from('sodisc t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('sodisc t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'sodiscid' => $data['sodiscid'],
        'soheaderid' => $data['soheaderid'],
        'discvalue' => Yii::app()->format->formatNumber($data['discvalue']),
        'discvalue1' => '-'
      );
    }
    $cmd      = Yii::app()->db->createCommand()->selectdistinct('(sum(t.price * t.qty) - gettotalamountdiscso(t.soheaderid)) as amountbefdisc,gettotalamountdiscso(t.soheaderid) as amountafterdisc')->from('sodetail t')->where('soheaderid = :soheaderid', array(
      ':soheaderid' => $id
    ))->queryRow();
    $footer[] = array(
      'sodetailid' => 'Diskon',
      'discvalue' => Yii::app()->format->formatNumber($cmd['amountbefdisc']),
      'discvalue1' => '-'
    );
    $footer[] = array(
      'sodetailid' => 'Setelah Diskon',
      'discvalue' => Yii::app()->format->formatNumber($cmd['amountafterdisc']),
      'discvalue1' => Yii::app()->format->formatNumber($cmd['amountafterdisc'])
    );
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertsoheader(:vsono,:vsodate,:vcompanyid,:vpoheaderid,;visdisplay,:vaddressbookid,:vpocustno,:vemployeeid,:vpaymentmethodid,:vtaxid,:vshipto,:vbillto,:vheadernote,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vsono', $arraydata[3], PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus', $arraydata[13], PDO::PARAM_STR);
		} else {
			$sql     = 'call Updatesoheader(:vid,:vsodate,:vcompanyid,:vpoheaderid,:visdisplay,:vsotype,:vmaterialtypeid,:vpackageid,:vqty,:vaddressbookid,:vpocustno,:vemployeeid,:vpaymentmethodid,:vtaxid,:vshipto,:vbillto,:vheadernote,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vsodate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vcompanyid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vpoheaderid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':visdisplay', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vsotype', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vmaterialtypeid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vpackageid', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vpocustno', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid', $arraydata[12], PDO::PARAM_STR);
		$command->bindvalue(':vpaymentmethodid', $arraydata[13], PDO::PARAM_STR);
		$command->bindvalue(':vtaxid', $arraydata[14], PDO::PARAM_STR);
		$command->bindvalue(':vshipto', $arraydata[15], PDO::PARAM_STR);
		$command->bindvalue(':vbillto', $arraydata[16], PDO::PARAM_STR);
		$command->bindvalue(':vheadernote', $arraydata[17], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileSoheader"]["name"]);
		if (move_uploaded_file($_FILES["FileSoheader"]["tmp_name"], $target_file)) {
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
					if ($nourut != '') {
						$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
						$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
						$sodate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()));
						$sono = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$abid = Yii::app()->db->createCommand("select soheaderid 
							from soheader 
							where companyid = ".$companyid."
							and sodate = '".$sodate."' 
							and sono = '".$sono."' 					
							")->queryScalar();
						if ($abid == '') {					
							$customer = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
							$customerid = Yii::app()->db->createCommand("select addressbookid 
								from addressbook 
								where fullname = '".$customer."' and iscustomer = 1")->queryScalar();
							$pono = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
							$poheaderid = Yii::app()->db->createCommand("select poheaderid 
								from poheader 
								where companyid = ".$companyid." and pono like '".$pono."'")->queryScalar();
							$pocustno = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
							$totalbefdisc = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
							$totalaftdisc = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
							$sales = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
							$salesid = Yii::app()->db->createCommand("select employeeid from employee where fullname = '".$sales."'")->queryScalar();
							$paymentmethod = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
							$paymentmethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '".$paymentmethod."'")->queryScalar();
							$taxcode = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
							$taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$taxcode."'")->queryScalar();
							$shipto = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
							$billto = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
							$headernote = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
							$recordstatus = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
							$this->ModifyData($connection,array('',$sodate,
								$companyid, $sono, $poheaderid, $customerid, $pocustno, $salesid, $paymentmethodid, $taxid, $shipto, $billto,$headernote,$recordstatus));
							//get id addressbookid
							$abid = Yii::app()->db->createCommand("select soheaderid 
								from soheader 
								where companyid = ".$companyid."
								and sodate = '".$sodate."' 
								and sono = '".$sono."' 					
								")->queryScalar();
						}
						if ($abid != '') {
							if ($objWorksheet->getCellByColumnAndRow(16, $row)->getValue() != '') {
								$productname = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
								$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
								$qty = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
								$uomcode = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
								$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
								$sloccode = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
								$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
								$price = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
								$currencyname = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
								$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
								$currencyrate = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
								$delvdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(23, $row)->getValue()));
								$description = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue();
								$this->ModifyDataDetail($connection,array('',$abid,$productid,$qty,$uomid,$slocid,$price,$currencyid,$currencyrate,
									$delvdate,$description));
							}
							if ($objWorksheet->getCellByColumnAndRow(25, $row)->getValue() != '') {
								$discvalue = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue();
								$this->ModifyDataDisc($connection,array('',$abid, $discvalue));
							}					
						}
					}	
				}
				$transaction->commit();
				GetMessage(false, 'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, $e->getMessage());
			}
        }
  }
  public function actionUploadSOPO() {
		//parent::actionUploadDoc();
        Yii::import('ext.PHPExcel.XPHPExcel');
		$this->phpExcel = XPHPExcel::createPHPExcel();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileSoheaderPO"]["name"]);
		if (move_uploaded_file($_FILES["FileSoheaderPO"]["tmp_name"], $target_file)) {
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
				for ($row = 1; $row <= $highestRow; ++$row) {
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '{$companycode}'")->queryScalar();
                    $customer = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$addressbookid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '{$customer}'")->queryScalar();
                    $sales = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$employeeid = Yii::app()->db->createCommand("select employeeid from employee where fullname = '{$sales}'")->queryScalar();
                    $shipto = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $disc = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $row+=1;
                    $sodate = date(Yii::app()->params['datetodb'],strtotime($objWorksheet->getCellByColumnAndRow(1, $row)->getValue()));
                    $top = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $paymentmethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '{$top}'")->queryScalar();
                    $tax = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '".$tax."'")->queryScalar();
                    $billto = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$headernote = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                    //var_dump($companyid);
                    
                    $stmt = Yii::app()->db->createCommand("select getrunno({$companyid},25,'{$sodate}')");
                    $sono = $stmt->queryScalar();
                    
                    $this->ModifyData($connection,array('',$sodate,
								$companyid, $sono, '', $addressbookid, '', $employeeid, $paymentmethodid, $taxid, $shipto, $billto,$headernote,1));
                    
                    $abid = Yii::app()->db->createCommand("select soheaderid 
							from soheader 
							where companyid = ".$companyid."
							and sodate = '".$sodate."' 
							and sono = '".$sono."' 					
							")->queryScalar();
                    
                    if ($abid != '')
                    {
                        $row+=3;
                        for ($i = $row; $i <= $highestRow; ++$i)
                        {
                            if ($objWorksheet->getCellByColumnAndRow(1, $i)->getValue() != '')
                            {
                                $productname = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
                                $productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
                                $qty = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
                                $uomcode = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
                                $uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
                                $sloccode = $objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
                                $slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
                                $price = $objWorksheet->getCellByColumnAndRow(5, $i)->getValue();
                                $currencyname = $objWorksheet->getCellByColumnAndRow(6, $i)->getValue();
                                $currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
                                $currencyrate = $objWorksheet->getCellByColumnAndRow(7, $i)->getValue();
                                $delvdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(8, $i)->getValue()));
                                $description = $objWorksheet->getCellByColumnAndRow(9, $i)->getValue();
                                $this->ModifyDataDetail($connection,array('',$abid,$productid,$qty,$uomid,$slocid,$price,$currencyid,$currencyrate,
                                $description,$delvdate));
                                $row++;
                            }
                        }
                    }
                    
                    if ($disc != '')
                    {
                        $exp = explode('+',$disc);
                        for($j=0; $j<count($exp); $j++)
                        {
                            $this->ModifyDataDisc($connection,array('',$abid, $exp[$j]));    
                        }
                    }
				}
				$transaction->commit();
				GetMessage(false, 'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, $e->getMessage());
			}
        }
  }
  public function actionSave() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['soheaderid'])?$_POST['soheaderid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['sodate'])),
				$_POST['companyid'],'',$_POST['poheaderid'],(isset($_POST['isdisplay'])  && $_POST['isdisplay'] == "on") ? 1 : 0,$_POST['sotype'],$_POST['materialtypeid'],$_POST['packageid'],(isset($_POST['qtypackage'])?$_POST['qtypackage']:'0'),$_POST['addressbookid'],$_POST['pocustno'],$_POST['employeeid'],$_POST['paymentmethodid'],
				$_POST['taxid'],$_POST['shipto'],$_POST['billto'],$_POST['headernote']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
	private function ModifyDataDetail($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertsodetail(:vsoheaderid,:vproductid,:vqty,:vuomid,:vslocid,:vprice,:vcurrencyid,:vcurrencyrate,:vdescription,:vdelvdate,:visbonus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatesodetail(:vid,:vsoheaderid,:vproductid,:vqty,:vuomid,:vslocid,:vprice,:vcurrencyid,:vcurrencyrate,:vdescription,:vdelvdate,:visbonus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vsoheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vslocid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vprice', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyrate', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vdelvdate', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':visbonus', $arraydata[11], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveDetail() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['sodetailid'])?$_POST['sodetailid']:''),$_POST['soheaderid'],$_POST['productid'],$_POST['qty'],
				$_POST['unitofmeasureid'],$_POST['slocid'],$_POST['price'],$_POST['currencyid'],$_POST['currencyrate'],$_POST['itemnote'],
				date(Yii::app()->params['datetodb'], strtotime($_POST['delvdate'])),$_POST['isbonus']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
	private function ModifyDataDisc($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertsodisc(:vsoheaderid,:vdiscvalue,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatesodisc(:vid,:vsoheaderid,:vdiscvalue,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vsoheaderid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vdiscvalue', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSaveDisc() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDisc($connection, array((isset($_POST['sodiscid'])?$_POST['sodiscid']:''),$_POST['soheaderid'],$_POST['discvalue']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteSO(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveSO(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        $this->SendNotifWaCustomer($this->menuname,$id);
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgesoheader(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgedetail() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgesodetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionPurgedisc() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgesodisc(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.companyid, a.soheaderid,a.sono, b.fullname as customername, a.sodate, c.paymentname, e.taxcode, e.taxvalue,
			a.addressbookid, a.headernote,a.recordstatus,a.shipto,a.billto,d.fullname as salesname, isdisplay, ifnull(count(packageid),0) as pkgid,
		 (select packagename from packages s where s.packageid = a.packageid) as packagename,(select headernote from packages s where s.packageid = a.packageid) as packagenote, ifnull(qtypackage,0) as qtypackage
      from soheader a
      join addressbook b on b.addressbookid = a.addressbookid
		  join employee d on d.employeeid = a.employeeid
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		  join tax e on e.taxid = a.taxid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.soheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Sales Order';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
    $this->pdf->SetFont('Tahoma');
    foreach ($dataReader as $row) {
		if($row['isdisplay'])
  			$this->pdf->Image('images/DISPLAY.jpg', 0, 8, 210, 135);
      if ($row['addressbookid'] > 0) {
        $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.lat, a.lng
					from address a
					left join addresstype b on b.addresstypeid = a.addresstypeid
					left join city c on c.cityid = a.cityid
					where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $phone;
        foreach ($dataReader1 as $row1) {
          $phone = $row1['phoneno'];
        }
      }
      $this->pdf->SetFontSize(8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        100,
        30,
        60
      ));
      $this->pdf->row(array(
        'Customer',
        '',
        'Sales Order No',
        ' : ' . $row['sono']
      ));
      $this->pdf->row(array(
        'Name',
        ' : ' . $row['customername'].'   ('.$row1['lat'].','.$row1['lng'].')',
        'SO Date',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['sodate']))
      ));
      $this->pdf->row(array(
        'Phone',
        ' : ' . $phone,
        'Sales',
        ' : ' . $row['salesname']
      ));
      $this->pdf->row(array(
        'Address',
        ' : ' . $row['shipto'],
        'Payment',
        ($row['isdisplay']==1) ? ' : LANGSUNG BAYAR SAAT TERJUAL' : ' : ' . $row['paymentname']
      ));
      $sql1        = "select a.soheaderid,c.uomcode,a.qty,a.price * a.currencyrate as price,(qty * price * currencyrate) as total,(e.taxvalue * qty * price * currencyrate/ 100) as ppn,b.productname,
			d.symbol,d.i18n,a.itemnote,a.delvdate
			from sodetail a
			left join soheader f on f.soheaderid = a.soheaderid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
			left join currency d on d.currencyid = a.currencyid
			left join tax e on e.taxid = f.taxid
			where a.soheaderid = " . $row['soheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 0);
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
        15,
        15,
        60,
        30,
        20,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'Qty',
        'Units',
        'Description',
        'Item Note',
          ($row['pkgid']==1) ? '' : 'Unit Price',
          ($row['pkgid']==1) ? '' : 'Total',
        'Tgl Kirim'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'R',
        'C',
        'L',
        'L',
        'R',
        'R',
        'R',
        'L'
      );
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['productname'],
          $row1['itemnote'],
          ($row['pkgid']==1) ? '' : Yii::app()->format->formatCurrency($row1['price']),
          ($row['pkgid']==1) ? '' : Yii::app()->format->formatCurrency($row1['total']),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate']))
        ));
        $total    = $row1['total'] + $total;
        $totalqty = $row1['qty'] + $totalqty;
      }
      $this->pdf->row(array(
        Yii::app()->format->formatNumber($totalqty),
        '',
        'Total',
        '',
        ($row['pkgid']==1) ? '' : Yii::app()->format->formatCurrency($total)
      ));
      $sql1        = "select a.discvalue
			from sodisc a
			where a.soheaderid = " . $row['soheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $discvalue   = '';
      foreach ($dataReader1 as $row1) {
        if ($discvalue == '') {
          $discvalue = ($row['pkgid']==1) ? '' : Yii::app()->format->formatNumber($row1['discvalue']);
        } else {
          $discvalue = ($row['pkgid']==1) ? '' : $discvalue . ' + ' . Yii::app()->format->formatNumber($row1['discvalue']);
        }
      }
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Diskon (%)',
        $discvalue
      ));
      $totalbefdisc                 = Yii::app()->db->createCommand('select gettotalbefdisc('.$row['soheaderid'].')')->queryScalar();
      $hrgaftdisc                 = Yii::app()->db->createCommand('select gettotalamountdiscso('.$row['soheaderid'].')')->queryScalar();
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Harga Diskon',
        ($row['pkgid']==1) ? '' : Yii::app()->format->formatNumber($totalbefdisc - $hrgaftdisc)
      ));
      $bilangan = explode(".", $hrgaftdisc);
      $this->pdf->row(array(
        'Harga Sesudah Diskon',
        Yii::app()->format->formatCurrency($hrgaftdisc) . ' (' . eja($bilangan[0]) . ')'
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));   
			$this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Ship To',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Bill To',
        $row['billto']
      ));
      $this->pdf->row(array('NOTE :',
        (($row['pkgid']==1) ? $row['packagename']." (QTY : ".Yii::app()->format->formatCurrency($row['qtypackage']).") \n".$row['packagenote']." \n". $row['headernote'] : $row['headernote']
      )));
/*      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));*/
      $this->pdf->checkNewPage(10);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety(), 'Pembuat');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownPDF1() {
    parent::actionDownload();
    $sql = "select a.companyid, a.soheaderid,a.sono, b.fullname as customername, a.sodate, c.paymentname, e.taxcode, e.taxvalue,
			a.addressbookid, a.headernote,a.recordstatus,a.shipto,a.billto,d.fullname as salesname, isdisplay
      from soheader a
      join addressbook b on b.addressbookid = a.addressbookid
		  join employee d on d.employeeid = a.employeeid
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		  join tax e on e.taxid = a.taxid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.soheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Sales Order';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
    $this->pdf->SetFont('Tahoma');
    foreach ($dataReader as $row) {
		if($row['isdisplay']==1)
	  		$this->pdf->Image('images/DISPLAY.jpg', 0, 0, 210, 150);
      if ($row['addressbookid'] > 0) {
        $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno
					from address a
					left join addresstype b on b.addresstypeid = a.addresstypeid
					left join city c on c.cityid = a.cityid
					where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $phone;
        foreach ($dataReader1 as $row1) {
          $phone = $row1['phoneno'];
        }
      }
      $this->pdf->SetFontSize(8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        100,
        30,
        60
      ));
      $this->pdf->row(array(
        'Customer',
        '',
        'Sales Order No',
        ' : ' . $row['sono']
      ));
      $this->pdf->row(array(
        'Name',
        ' : ' . $row['customername'],
        'SO Date',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['sodate']))
      ));
      $this->pdf->row(array(
        'Phone',
        ' : ' . $phone,
        'Sales',
        ' : ' . $row['salesname']
      ));
      $this->pdf->row(array(
        'Address',
        ' : ' . $row['shipto'],
        'Payment',
        ($row['isdisplay']==1) ? ' : LANGSUNG BAYAR SAAT TERJUAL' : ' : ' . $row['paymentname']
      ));
      $sql1        = "select a.soheaderid,c.uomcode,a.qty,a.price * a.currencyrate as price,(qty * price * currencyrate) as total,(e.taxvalue * qty * price * currencyrate/ 100) as ppn,b.productname,
			d.symbol,d.i18n,a.itemnote,a.delvdate
			from sodetail a
			left join soheader f on f.soheaderid = a.soheaderid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
			left join currency d on d.currencyid = a.currencyid
			left join tax e on e.taxid = f.taxid
			where a.soheaderid = " . $row['soheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() +0);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        15,
        15,
        110,
        30,
        30
      ));
      $this->pdf->colheader = array(
        'Qty',
        'Units',
        'Description',
        'Item Note',
        'Tgl Kirim'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'R',
        'C',
        'L',
        'L',
        'R',
        'L'
      );
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['productname'],
          $row1['itemnote'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate']))
        ));
        $total    = $row1['total'] + $total;
        $totalqty = $row1['qty'] + $totalqty;
      }
      $this->pdf->row(array(
        Yii::app()->format->formatNumber($totalqty),
        '',
        'Total',
        '',
        ''
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
      );
      $this->pdf->setwidths(array(
        35,
        200,
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
      ));   
			$this->pdf->coldetailalign = array(
        'L',
        'L',
      );
      $this->pdf->row(array(
        'Ship To',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->checkNewPage(10);
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownload();
    $sql = "select addressbookid,custreqno,quotno,headernote,recordstatus
			from soheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.soheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('custreqno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('quotno'))->setCellValueByColumnAndRow(8, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(9, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['custreqno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['quotno'])->setCellValueByColumnAndRow(8, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(9, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="soheader.xlsx"');
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
