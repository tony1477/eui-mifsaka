<?php
class BroadcastwaController extends Controller {
  public $menuname = 'broadcastwa';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into broadcastwa (recordstatus) values (1)";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'broadcastwaid' => $id
			));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $broadcastwaid       = isset($_POST['broadcastwaid']) ? $_POST['broadcastwaid'] : '';
    $company  = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $wanumber   = isset($_POST['wanumber']) ? $_POST['wanumber'] : '';
    $senddate  = isset($_POST['senddate']) ? $_POST['senddate'] : '';
    $sendtime         = isset($_POST['sendtime']) ? $_POST['sendtime'] : '';
    $message       = isset($_POST['message']) ? $_POST['message'] : '';
    $file     = isset($_POST['file']) ? $_POST['file'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $recordstatus = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $broadcastwaid       = isset($_GET['q']) ? $_GET['q'] : $broadcastwaid;
    $company  = isset($_GET['q']) ? $_GET['q'] : $company;
    $wanumber   = isset($_GET['q']) ? $_GET['q'] : $wanumber;
    $senddate   = isset($_GET['q']) ? $_GET['q'] : $senddate;
    $sendtime         = isset($_GET['q']) ? $_GET['q'] : $sendtime;
    $message       = isset($_GET['q']) ? $_GET['q'] : $message;
    $file     = isset($_GET['q']) ? $_GET['q'] : $file;
    $description = isset($_GET['q']) ? $_GET['q'] : $description;
    $recordstatus = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'broadcastwaid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
      
    if(isset($recordstatus) && $recordstatus!='')
        $recordstatus = 'and t.recordstatus = '.$recordstatus;
    else
        $recordstatus = "and t.recordstatus like '%%' ";
        
    $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
      ->from('broadcastwa t')
      ->leftjoin('company a', 'a.companyid = t.companyid')
      ->where("(coalesce(broadcastwaid,'') like :broadcastwaid) 
      and (coalesce(companyname,'') like :companyname) 
      and (coalesce(t.description,'') like :description) 
      ".$recordstatus, array(
      ':broadcastwaid' => '%' . $broadcastwaid . '%',
      ':companyname' => '%' . $company . '%',
      ':description' => '%' . $description . '%',
      //':senddate' => '%'. $senddate. '%',
      //':productdetailname' => '%'. $productdetail. '%',
    ))->queryScalar();
  
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()
      ->select("t.*,a.companyname, if(broadcasttype=1,'Message',if(broadcasttype=2,'Image',if(broadcasttype=3,'Document/File','Video'))) as type")
      ->from('broadcastwa t')->leftjoin('company a', 'a.companyid = t.companyid')->where("
        (coalesce(broadcastwaid,'') like :broadcastwaid)  
        and (coalesce(companyname,'') like :company) 
        and (coalesce(t.description,'') like :description) 
        ".$recordstatus, array(
        ':broadcastwaid' => '%' . $broadcastwaid . '%',
        ':company' => '%' . $company . '%',
        ':description' => '%' . $description . '%',
        //':senddate' => '%'. $senddate. '%',
        //':productdetailname' => '%'. $productdetail. '%',
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
      
    foreach ($cmd as $data) {
      $row[] = array(
        'broadcastwaid' => $data['broadcastwaid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'wanumber' => $data['wanumber'],
        'senddate' => date(Yii::app()->params['dateviewfromdb'],strtotime($data['senddate'])),
        'sendtime' => date('H:m',strtotime($data['sendtime'])),
        'broadcasttype' => $data['broadcasttype'],
        'type' => $data['type'],
        'message' => $data['message'],
        'file' => $data['file'],
        'filename' => $data['filename'],
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'broadcastwadetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('broadcastwadet t')->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')->where('t.broadcastwaid = :broadcastwaid', array(
      ':broadcastwaid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.fullname')->from('broadcastwadet t')->leftjoin('addressbook a','a.addressbookid = t.addressbookid')->where('t.broadcastwaid = :broadcastwaid', array(
      ':broadcastwaid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'broadcastwadetid' => $data['broadcastwadetid'],
        'broadcastwaid' => $data['broadcastwaid'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'customername' => $data['customername'],
        'ownername' => $data['ownername'],
        'destnumber' => $data['destnumber'],
        'status' => $data['status'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actioncopybroadcastwa() {
		parent::actionIndex();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Copybroadcastwa(:vid,:vcreatedby)';
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
  public function actionUploadFile() {
		parent::actionUpload();
    
    $ds = DIRECTORY_SEPARATOR;
    $storeFolder = 'images/broadcastwa';
    $tempFile  = $_FILES['file-broadcastwa']['tmp_name']; 
    //$sourceProperties = getimagesize($file);
    //$fileNewName = time();
    $fileNewName = $_FILES["file-broadcastwa"]["name"];
    $folderPath = dirname('__FILES__').'/images/broadcastwa/';
    $targetPath = dirname('__FILES__') . $ds. $storeFolder . $ds;
    $targetFile =  $targetPath. $_FILES['file-broadcastwa']['name'];
    $ext = pathinfo($_FILES['file-broadcastwa']['name'], PATHINFO_EXTENSION);
    //$imageType = $sourceProperties[2];

    try {
      
      $newfile = $fileNewName;
      $folderPath = dirname('__FILES__').'/images/broadcastwa/';
      $files = scandir($folderPath, 1);
      //$files = array_diff(scandir($folderPath), array('.', '..'));

      $find = array_search($newfile,$files);
      
      if($find!='') { getMessage(true,'Duplicate File'); die(); }
      
      if (move_uploaded_file($tempFile,$targetFile)) {
        // start curl set up for remote file upload
        $url = "http://akagroup.co.id/public/server.php";
        //$file = new CURLFile($tempFile);
        $handle    = fopen($targetFile, "r");
        $data      = fread($handle, filesize($targetFile));
        $POST_DATA = array(
          'file' => base64_encode($data),
          'name' => $_FILES['file-broadcastwa']['name']
        );
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_DATA);
        $response = curl_exec($ch);
        //echo $processedImage;
        curl_close($ch);
        if($response=='success') {
          getMessage(false,'Success Upload to Server');
        }
        else {
          getMessage(true,'Failed Upload to Server!');
        }

      } else {
          getMessage(true,'Your file cant be upload!');
      }
      
    }
    catch(Exception $e) {
      getMessage(true,$e->getMessage());
    }
        //move_uploaded_file($file, $folderPath. $fileNewName);
	}
  private function sendBroadcastWA($broadcastwaid) {
    // check if status max
    $maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appbroadcastwa')")->queryScalar();

    // check type broadcast, 
    $sql='select broadcastwaid,broadcasttype,message,file,description,recordstatus,filename
    from broadcastwa a
    where a.broadcastwaid = '.$broadcastwaid;
    $row = Yii::app()->db->createCommand($sql)->queryRow();

    if($row['recordstatus']==$maxstat) {
      // data for sending
      $type = $row['broadcasttype'];
      $message = $row['message'];
      $file = 'http://akagroup.co.id/public/broadcastwa/'.$row['file'];
      $titledocument = $row['filename'];
      $devicekey = "1a23cd16-c6a9-4245-a2f9-057e1b50dd4b"; //siaga
      //$devicekey = Yii::app()->params['devicekey'];
      
      try {
        $q = "select customername, ownername, destnumber from broadcastwadet where broadcastwaid = ".$row['broadcastwaid'];
        $dataReader = Yii::app()->db->createCommand($q)->queryAll();
        foreach($dataReader as $data) {
          // sending broadcast
          $phonenumber = $data['destnumber'];
          if($type==1) {
            $action = sendwajapri($devicekey,$message,$phonenumber);
          }
          else if($type==2) {
            $action = sendwaimage($devicekey,$message,$phonenumber,$file);
          }
          else if($type==3) {
            $action = sendwadocument($devicekey,$titledocument,$phonenumber,$file);
          }
          else if($type==4) {
            $action = sendwavideo($devicekey,$message,$phonenumber,$file);
          }
          sleep(3);
        }
      }
      catch(Exception $e) {
        GetMessage(true, $e->getMessage());
      }
    }
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertbroadcastwa(:vcompanyid,:vwanumber,:vsenddate,:vbroadcasttype,:vmessage,:vfile,:vfilename,:vdescription,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatebroadcastwa(:vid,:vcompanyid,:vwanumber,:vsenddate,:vsendtime,:vbroadcasttype,:vmessage,:vfile,:vfilename,:vdescription,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vwanumber', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vsenddate', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vsendtime', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vbroadcasttype', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vmessage', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vfile', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vfilename', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vdescription', $arraydata[9], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[10], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('_FILES_').'/uploads/' . basename($_FILES["file-broadcastwa"]["name"]);
		if (move_uploaded_file($_FILES["file-broadcastwa"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$abid = '';$nourut = '';
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
      $row=2;
			try {
        $id = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
        if($id=='') $id=null;
        $companyname = $objWorksheet->getCellByColumnAndRow(1, $row+1)->getValue();
        $companyid = Yii::app()->db->createCommand("select companyid from company where companyname = '".$companyname."'")->queryScalar();
        $wanumber = $objWorksheet->getCellByColumnAndRow(1, $row+2)->getValue();
        $senddate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(1, $row+3)->getValue()));
        $sendtime = date('H:i', strtotime($objWorksheet->getCellByColumnAndRow(1 , $row+4)->getValue()));
        $type = $objWorksheet->getCellByColumnAndRow(1, $row+5)->getValue();
        if($type=='Message'){
          $broadcasttype = 1;
        }
        else if($type=='Image'){
          $broadcasttype = 2;
        }
        else if($type=='File' || $type=='Document') {
          $broadcasttype = 3;
        }
        else if($type=='Video') {
          $broadcasttype = 4;
        }
        $message = $objWorksheet->getCellByColumnAndRow(1, $row+6)->getValue();
        $description = $objWorksheet->getCellByColumnAndRow(1, $row+7)->getValue();
        
        //if($productid>0)
        if($id=='') {
          // insert 
          $this->ModifyData($connection,array('',$companyid,$wanumber,$senddate,$sendtime,$broadcasttype,$message,'',$description,1));
          $id = Yii::app()->db->createCommand("select last_insert_id()")->queryScalar();
        }
        else {
          $this->ModifyData($connection,array($id,$companyid,$wanumber,$senddate,$sendtime,$broadcasttype,$message,'',$description,1));
        }
        
        for ($row = 11; $row <= $highestRow; ++$row) {
          $iddetail = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
          if($iddetail=='') $iddetail=null;
          $fullname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
          $addressbookid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$fullname."' and iscustomer=1")->queryScalar();
          //$addressbookid=1;
          $customername = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
          $ownername = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
          $destnumber = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
          $this->ModifyDataDetail($connection,array($iddetail,$id,$addressbookid,$customername,$ownername,$destnumber));
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
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['broadcastwaid'])?$_POST['broadcastwaid']:''),$_POST['companyid'],$_POST['wanumber'],date(Yii::app()->params['datetodb'], strtotime($_POST['senddate'])),date('H:m',strtotime($_POST['sendtime'])),$_POST['broadcasttype'],$_POST['message'],$_POST['file'],$_POST['filename'],
      $_POST['description'],(isset($_POST['recordstatus']) ? '1' : '0')));
			$transaction->commit();
			GetMessage(false, 'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true, $e->getMessage());
		}
  }
	private function ModifyDataDetail($connection,$arraydata) {
		$detailid = (isset($arraydata[0])?$arraydata[0]:'');
		if ($detailid == '') {
			$sql     = 'call Insertbroadcastwadet(:vbroadcastwaid,:vaddressbookid,:vcustomername,:vownername,:vdestnumber,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatebroadcastwadet(:vid,:vbroadcastwaid,:vaddressbookid,:vcustomername,:vownername,:vdestnumber,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vbroadcastwaid', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vaddressbookid', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vcustomername', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vownername', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vdestnumber', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
  public function actionSavedetail() {
		parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyDataDetail($connection,array((isset($_POST['broadcastwadetid'])?$_POST['broadcastwadetid']:''),$_POST['broadcastwaid'],(isset($_POST['addressbookid']) && ($_POST['addressbookid']!='') ? $_POST['addressbookid'] : 0),$_POST['customername'],$_POST['ownername'],$_POST['destnumber']));
			$transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebroadcastwa(:vid,:vcreatedby)';
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
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebroadcastwadet(:vid,:vcreatedby)';
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
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveBroadcastwa(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        $this->sendBroadcastWA($ids);
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
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteBroadcastwa(:vid,:vcreatedby)';
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
  public function actionDownPDF() {
      parent::actionDownload();
      $sql = "select a.broadcastwaid,b.companyname,a.senddate,a.sendtime,a.message,a.broadcasttype,a.recordstatus,a.description,
        if(a.broadcasttype=1,'Message',if(a.broadcasttype=2,'Image',if(a.broadcasttype=3,'Document/File','Video'))) as type,a.wanumber
				from broadcastwa a 
				left join company b on b.companyid = a.companyid
        where a.broadcastwaid like '%".$_GET['broadcastwaid']."%'
				";
        if ($_GET['id'] !== '') {
          $sql = $sql . " and a.broadcastwaid in (".$_GET['id'].") ";
        }
        if ($_GET['companyname'] !== '') {
          $sql = $sql . " and b.companyname like '%".$_GET['companyname']."%' ";
        }
        if ($_GET['senddate'] !== '') {
          $sql = $sql . " and a.senddate like '%".$_GET['senddate']."%' ";
        }
        if ($_GET['description'] !== '') {
          $sql = $sql . " and a.description like '%".$_GET['description']."%' ";
        }
	      
	      if(isset($_GET['recordstatus']) && $_GET['recordstatus']!='')
	        $sql = $sql . ' and a.recordstatus = '.$_GET['recordstatus'];
	      else
	        $sql = $sql. " and a.recordstatus like '%%' ";
      $command          = $this->connection->createCommand($sql);
      $dataReader       = $command->queryAll();
      $this->pdf->title = getCatalog('broadcastwa');
      $this->pdf->AddPage('P');
      $this->pdf->SetFont('Arial');
      $this->pdf->AliasNBPages();
      foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->setwidths(array(
        50,
        80,
      ));
      $this->pdf->row(array('Perusahaan',': '.$row['companyname']));
      $this->pdf->row(array('WA Number',': '.$row['wanumber']));
      $this->pdf->row(array('Tanggal Kirim Broadcast', ': '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['senddate']))));
      $this->pdf->row(array('Jam Kirim Broadcast', ': '.date('H:i', strtotime($row['sendtime']))));
      $this->pdf->row(array('Tipe Broadcast', ': '.$row['type']));
      $this->pdf->row(array('Pesan', $row['message']));
      $this->pdf->row(array('Keterangan', $row['description']));
      /*
      $this->pdf->text(15, $this->pdf->gety() + 5, 'broadcastwa Version ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['companyname']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tanggal Kirim Broadcast ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['datetimeviewfromdb'], strtotime($row['senddate'].' '.$row['sendtime'])));
      $this->pdf->text(15, $this->pdf->gety() + 15, 'Tipe Broadcast');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': '.$row['type']);
      //$this->pdf->setY($this->pdf->getY()+20);
      $this->pdf->row(array('Pesan ini',$row['message']));
      //$this->pdf->text(15, $this->pdf->gety() + 20, 'Pesan');
      //$this->pdf->text(50, $this->pdf->gety() + 20, ': '.$row['message']);
      
      //$this->pdf->text(50, $this->pdf->gety() + 25, '');
      $this->pdf->text(15, $this->pdf->gety() + 30, 'Description');
      $this->pdf->text(50, $this->pdf->gety() + 30, ': ' . $row['description']);
      */
      $sql1        = "select a.broadcastwadetid,b.fullname, a.addressbookid, a.customername, a.ownername, a.destnumber
        from broadcastwadet a
        left join addressbook b on b.addressbookid = a.addressbookid
        where a.broadcastwaid = '" . $row['broadcastwaid'] . "'
	  order by broadcastwadetid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->colalign = array(
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->setwidths(array(
        10,
        50,
        50,
        50,
        35
      ));
      $this->pdf->colheader = array(
        'ID',
        'Nama Toko',
        'Nama Customer',
        'Nama Pemilik',
        'No Tujuan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $row1['broadcastwadetid'],
          $row1['fullname'],
          $row1['customername'],
          $row1['ownername'],
          $row1['destnumber']
        ));
      }
      $this->pdf->checkNewPage(10);
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
		$this->menuname='broadcastwa';
    parent::actionDownxls();
    $sql = "select a.broadcastwaid,b.companyname,a.senddate,a.sendtime,a.message,a.broadcasttype,a.recordstatus,a.description,
        if(a.broadcasttype=1,'Message',if(a.broadcasttype=2,'Image',if(a.broadcasttype=3,'Document/File','Video'))) as type,a.wanumber
				from broadcastwa a 
				left join company b on b.companyid = a.companyid
        where a.broadcastwaid like '%".$_GET['broadcastwaid']."%'
				";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.broadcastwaid in (".$_GET['id'].") ";
    }
    if ($_GET['companyname'] !== '') {
      $sql = $sql . " and b.companyname like '%".$_GET['companyname']."%' ";
    }
    if ($_GET['senddate'] !== '') {
      $sql = $sql . " and a.senddate like '%".$_GET['senddate']."%' ";
    }
    if ($_GET['description'] !== '') {
      $sql = $sql . " and a.description like '%".$_GET['description']."%' ";
    }
    
    if(isset($_GET['recordstatus']) && $_GET['recordstatus']!='')
      $sql = $sql . ' and a.recordstatus = '.$_GET['recordstatus'];
    else
      $sql = $sql. " and a.recordstatus like '%%' ";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach($dataReader as $row) {
      $i          = 2;
      $this->phpExcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0, $i, getCatalog('broadcastwaid'))
        ->setCellValueByColumnAndRow(1, $i, $row['broadcastwaid'])
        ->setCellValueByColumnAndRow(0, $i+1, getCatalog('companyname'))
        ->setCellValueByColumnAndRow(1, $i+1, $row['companyname'])
        ->setCellValueByColumnAndRow(0, $i+2, getCatalog('WA Number'))
        ->setCellValueByColumnAndRow(1, $i+2, $row['wanumber'])
        ->setCellValueByColumnAndRow(0, $i+3, getCatalog('Tanggal Kirim Broadcast'))
        ->setCellValueByColumnAndRow(1, $i+3, $row['senddate'])
        ->setCellValueByColumnAndRow(0, $i+4, getCatalog('Jam Kirim Broadcast'))
        ->setCellValueByColumnAndRow(1, $i+4, $row['sendtime'])
        ->setCellValueByColumnAndRow(0, $i+5, getCatalog('Tipe Broadcast'))
        ->setCellValueByColumnAndRow(1, $i+5, $row['type'])
        ->setCellValueByColumnAndRow(0, $i+6, getCatalog('Pesan'))
        ->setCellValueByColumnAndRow(1, $i+6, $row['message'])
        ->setCellValueByColumnAndRow(0, $i+7, getCatalog('description'))
        ->setCellValueByColumnAndRow(1, $i+7, $row['description']);
      $i=10;
      foreach ($dataReader as $row) {
        $sql1        = "select a.broadcastwadetid,b.fullname, a.addressbookid, a.customername, a.ownername, a.destnumber
          from broadcastwadet a
          left join addressbook b on b.addressbookid = a.addressbookid
          where a.broadcastwaid = '" . $row['broadcastwaid'] . "'
          order by broadcastwadetid";
        $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
        $this->phpExcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0, $i, getCatalog('broadcastwadetid'))
        ->setCellValueByColumnAndRow(1, $i, getCatalog('fullname'))
        ->setCellValueByColumnAndRow(2, $i, getCatalog('Customer Name'))
        ->setCellValueByColumnAndRow(3, $i, getCatalog('Owner Name'))
        ->setCellValueByColumnAndRow(4, $i, getCatalog('Dest Number'));
        $i++;
        foreach ($dataReader1 as $row1) {
          $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $i, $row1['broadcastwadetid'])
            ->setCellValueByColumnAndRow(1, $i, $row1['fullname'])
            ->setCellValueByColumnAndRow(2, $i, $row1['customername'])
            ->setCellValueByColumnAndRow(3, $i, $row1['ownername'])
            ->setCellValueByColumnAndRow(4, $i, $row1['destnumber'])
            ;
          $i += 1;
        }
      }
    }
    $this->getFooterXLS($this->phpExcel);
  }
  public function actionDownPDF1(){
    //echo 'test';
    $id = $_GET['id'];
    $sql = "select a.* from broadcastwa a where broadcastwaid = $id";
    $query = Yii::app()->db->createCommand($sql)->queryRow();
    $devicekey = 'fb13f6f1-5837-444c-9279-d90a783bca8d';
    //$message = 'HALLO, INI TES CHATFIRE';
    $message = $query['message']."\nSorry SPAM guys, lagi tes Broadcast WA";
    $type = $query['broadcasttype'];
    //$file  = 'FILE-BROADCAST';
    
    // sending message/broadcast 
    //$file = $query['file'];
    $file = 'http://sinargemilang.com/media/aka-group_small.png';

    $sql = "select * from broadcastwadet where broadcastwaid =".$id;
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    foreach($dataReader as $row) {
      //$phonenumber = '6281298987771';  
      $phonenumber = $row['destnumber'];
      /*
      if($type==1) {
        $action = sendwajapri($devicekey,$message,$phonenumber);
      }
      else if($type==2) {
        $action = sendwaimage($devicekey,$row['customername']."\nSorry SPAM guys, lgi tes broadcast WA. 1 Lagi",$phonenumber,$file);
      }
      else if($type==3) {
        $action = sendwadocument($devicekey,"Sorry SPAM guys, lgi tes broadcast WA. 1 Lagi, dengan dokumen",$phonenumber,$file);
      }
      else if($type==4) {
        $action = sendwavideo($devicekey,$message,$phonenumber,$file);
      }
      sleep(3);
      */
      /*
      $dest = 'http://akagroup.co.id/broadcastwa/impos.jpg';
      $local = realpath('C:\nginx-1.18.0\html\agemlive\uploads\impos.jpg');
      $copy = copy( $local, $dest );
      if(!$copy) echo 'Failed , to copy';
      */
      //echo realpath('C:\nginx-1.18.0\html\agemlive\uploads\impos.jpg');
    }
    //$phonenumber = '6285888885050';

    $newfile = 'ok.png';
    $dir    = dirname('__FILES__').'/images/broadcastwa/';
    //$files1 = scandir($dir);
    $files2 = scandir($dir, 1);
    $files = array_diff(scandir($dir), array('.', '..'));

    $find = array_search($newfile,$files);
    if($find!='')  getMessage(true,'Duplicate File');

    $homepage = file_get_contents('https://www.php.net/manual/en/function.file-get-contents.php');
    echo $homepage;

  }
}