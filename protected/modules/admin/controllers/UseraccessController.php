<?php
class UseraccessController extends Controller {
	public $menuname = 'useraccess';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$useraccessid	= isset ($_POST['useraccessid']) ? $_POST['useraccessid'] : '';
		$username = isset ($_POST['username']) ? $_POST['username'] : '';
		$realname = isset ($_POST['realname']) ? $_POST['realname'] : '';
		$password = isset ($_POST['password']) ? $_POST['password'] : '';
		$employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
		$email = isset ($_POST['email']) ? $_POST['email'] : '';
		$phoneno = isset ($_POST['phoneno']) ? $_POST['phoneno'] : '';
		$languagename = isset ($_POST['languagename']) ? $_POST['languagename'] : '';
		$themename = isset ($_POST['themename']) ? $_POST['themename'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$useraccessid = isset ($_GET['q']) ? $_GET['q'] : $useraccessid;
		$username = isset ($_GET['q']) ? $_GET['q'] : $username;
		$realname = isset ($_GET['q']) ? $_GET['q'] : $realname;
		$password = isset ($_GET['q']) ? $_GET['q'] : $password;
		$employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
		$email = isset ($_GET['q']) ? $_GET['q'] : $email;
		$phoneno = isset ($_GET['q']) ? $_GET['q'] : $phoneno;
		$languagename = isset ($_GET['q']) ? $_GET['q'] : $languagename;
		$themename = isset ($_GET['q']) ? $_GET['q'] : $themename;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'useraccessid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('useraccess t')
				->join('language l', 'l.languageid=t.languageid')
				->leftjoin('theme h', 'h.themeid=t.themeid')
				->where('((useraccessid like :useraccessid) or (username like :username) or (realname like :realname) or (email like :email) or (phoneno like :phoneno) or (languagename like :languagename) or (themename like :themename)) and t.recordstatus=1',
						array(':useraccessid'=>'%'.$useraccessid.'%',':username'=>'%'.$username.'%',':realname'=>'%'.$realname.'%',':email'=>'%'.$email.'%',':phoneno'=>'%'.$phoneno.'%',':languagename'=>'%'.$languagename.'%',':themename'=>'%'.$themename.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('useraccess t')
				->join('language l', 'l.languageid=t.languageid')
				->leftjoin('theme h', 'h.themeid=t.themeid')
				->where('(useraccessid like :useraccessid) and (username like :username) and (realname like :realname) and (email like :email) and (phoneno like :phoneno) and (languagename like :languagename) and (themename like :themename) and (t.recordstatus like :recordstatus) ',
						array(':useraccessid'=>'%'.$useraccessid.'%',':username'=>'%'.$username.'%',':realname'=>'%'.$realname.'%',':email'=>'%'.$email.'%',':phoneno'=>'%'.$phoneno.'%',':languagename'=>'%'.$languagename.'%',':themename'=>'%'.$themename.'%',':recordstatus'=>'%'.$recordstatus.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,l.languagename,h.themename,if(signature is null,0,1) as images')			
				->from('useraccess t')
				->join('language l', 'l.languageid=t.languageid')
				->leftjoin('theme h', 'h.themeid=t.themeid')
				->where('((useraccessid like :useraccessid) or (username like :username) or (realname like :realname) or (email like :email) or (phoneno like :phoneno) or (languagename like :languagename) or (themename like :themename)) and t.recordstatus=1',
						array(':useraccessid'=>'%'.$useraccessid.'%',':username'=>'%'.$username.'%',':realname'=>'%'.$realname.'%',':email'=>'%'.$email.'%',':phoneno'=>'%'.$phoneno.'%',':languagename'=>'%'.$languagename.'%',':themename'=>'%'.$themename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,l.languagename,h.themename,if(signature is null,0,1) as images')			
				->from('useraccess t')
				->join('language l', 'l.languageid=t.languageid')
				->leftjoin('theme h', 'h.themeid=t.themeid')
				->where('(useraccessid like :useraccessid) and (username like :username) and (realname like :realname) and (email like :email) and (phoneno like :phoneno) and (languagename like :languagename) and (themename like :themename) and (t.recordstatus like :recordstatus)',
						array(':useraccessid'=>'%'.$useraccessid.'%',':username'=>'%'.$username.'%',':realname'=>'%'.$realname.'%',':email'=>'%'.$email.'%',':phoneno'=>'%'.$phoneno.'%',':languagename'=>'%'.$languagename.'%',':themename'=>'%'.$themename.'%',':recordstatus'=>'%'.$recordstatus.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'useraccessid'=>$data['useraccessid'],
				'username'=>$data['username'],
				'realname'=>$data['realname'],
				'password'=>$data['password'],
				'employeeid'=>$data['employeeid'],
				'email'=>$data['email'],
				'phoneno'=>$data['phoneno'],
				'wanumber'=>$data['wanumber'],
				'telegramid'=>$data['telegramid'],
				'languageid'=>$data['languageid'],
				'languagename'=>$data['languagename'],
				'themeid'=>$data['themeid'],
				'signature'=>$data['signature'],
				'images'=>$data['images'],
				'themename'=>$data['themename'],
				'isonline'=>$data['isonline'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertuseraccess(:vusername,:vrealname,:vpassword,:vemployeeid,:vemail,:vphoneno,:vwanumber,:vtelegramid,:vlanguageid,:vthemeid,:vsignature,:vrecordstatus,:vcreatedby,:vipaddress,:vhostname)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateuseraccess(:vid,:vusername,:vrealname,:vpassword,:vemployeeid,:vemail,:vphoneno,:vwanumber,:vtelegramid,:vlanguageid,:vthemeid,:vsignature,:vrecordstatus,:vcreatedby,:vipaddress,:vhostname)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vusername',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrealname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vipaddress',getip(),PDO::PARAM_STR);
		$command->bindvalue(':vhostname','test',PDO::PARAM_STR);
		$sql = "select `password` from useraccess where username = '".$arraydata[1]."'";
		$password = Yii::app()->db->createCommand($sql)->queryScalar();
		$newpass = md5($arraydata[3]);
		if ($password !== $arraydata[3])
		{
			$command->bindvalue(':vpassword',$newpass,PDO::PARAM_STR);
		}
		else
		{
			$command->bindvalue(':vpassword',$password,PDO::PARAM_STR);
		}
		$command->bindvalue(':vemployeeid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vemail',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vwanumber',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vtelegramid',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vlanguageid',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vthemeid',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vsignature',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-useraccess"]["name"]);
		if (move_uploaded_file($_FILES["file-useraccess"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$username = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$realname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$password = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$email = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$phoneno = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$wanumber = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$telegramid = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$languagename = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$languageid = Yii::app()->db->createCommand("select languageid from language where languagename = '".$languagename."'")->queryScalar();
					$themename = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$themeid = Yii::app()->db->createCommand("select themeid from theme where themename = '".$themename."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$this->ModifyData($connection,array($id,$username,$realname,$password,$email,$phoneno,$languageid,$themeid,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
			}
    }
	}
	public function actionUploadsign() {
		parent::actionUpload();
		/*
        $target_file = dirname('__FILES__').'/images/useraccess/' . basename($_FILES["file-sign"]["name"]);
        try {
		//if (
            move_uploaded_file($_FILES["file-sign"]["tmp_name"], $target_file);
			
        }
        catch (Exception $e) {
			GetMessage(true,$e->getMessage());
		}
        */
        
        $file = $_FILES['file-sign']['tmp_name']; 
        $sourceProperties = getimagesize($file);
        //$fileNewName = time();
        $fileNewName = $_FILES["file-sign"]["name"];
        $folderPath = dirname('__FILES__').'/images/useraccess/';
        $ext = pathinfo($_FILES['file-sign']['name'], PATHINFO_EXTENSION);
        $imageType = $sourceProperties[2];

        /*
        switch ($imageType) {


            case IMAGETYPE_PNG:
                $imageResourceId = imagecreatefrompng($file); 
                $targetLayer = $this->imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                imagepng($targetLayer,$folderPath. $fileNewName);
                break;


            case IMAGETYPE_GIF:
                $imageResourceId = imagecreatefromgif($file); 
                $targetLayer = $this->imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                imagegif($targetLayer,$folderPath. $fileNewName);
                break;


            case IMAGETYPE_JPEG:
                $imageResourceId = imagecreatefromjpeg($file); 
                $targetLayer = $this->imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                imagejpeg($targetLayer,$folderPath. $fileNewName);
                break;


            default:
                echo "Invalid Image type.";
                exit;
                break;
                
        }
        */
        
        /*$img = imagecreatefrompng($file);
        //$colors = explode(',', $color);
        $remove = imagecolorallocate($img, 255, 255, 255);
        imagecolortransparent($img, $remove);
        imagepng($img, $folderPath. $fileNewName);
        */
        move_uploaded_file($file, $folderPath. $fileNewName);
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['useraccessid'])?$_POST['useraccessid']:''),$_POST['username'],$_POST['realname'],$_POST['password'],$_POST['employeeid'],
				$_POST['email'],$_POST['phoneno'],$_POST['wanumber'],$_POST['telegramid'],$_POST['languageid'],$_POST['themeid'],$_POST['signature'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeuseraccess(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		$sql = "select a.useraccessid,a.username,a.realname,a.password,a.email,a.phoneno,a.wanumber,a.telegramid,b.languagename,c.themename,
				case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from useraccess a
				left join language b on b.languageid = a.languageid
				left join theme c on c.themeid = a.themeid ";
		$useraccessid = filter_input(INPUT_GET,'useraccessid');
		$username = filter_input(INPUT_GET,'username');
		$realname = filter_input(INPUT_GET,'realname');
		$email = filter_input(INPUT_GET,'email');
		$phoneno = filter_input(INPUT_GET,'phoneno');
		$languagename = filter_input(INPUT_GET,'languagename');
		$themename = filter_input(INPUT_GET,'themename');
		$sql .= " where coalesce(a.useraccessid,'') like '%".$useraccessid."%' 
			and coalesce(a.username,'') like '%".$username."%'
			and coalesce(a.realname,'') like '%".$realname."%'
			and coalesce(a.email,'') like '%".$email."%'
			and coalesce(a.phoneno,'') like '%".$phoneno."%'
			and coalesce(b.languagename,'') like '%".$languagename."%'
			and coalesce(c.themename,'') like '%".$themename."%'
			";	
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " where a.useraccessid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by recordstatus desc,username asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('useraccess');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('useraccessid'),
										GetCatalog('username'),
										GetCatalog('realname'),
										GetCatalog('email'),
										GetCatalog('phoneno'),
										GetCatalog('wanumber'),
										GetCatalog('telegramid'),
										GetCatalog('languagename'),
										GetCatalog('themename'),										
										GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,35,62,90,32,32,32,30,25,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['useraccessid'],$row1['username'],$row1['realname'],$row1['email'],$row1['phoneno'],$row1['wanumber'],$row1['telegramid'],$row1['languagename'],$row1['themename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='useraccess';
		parent::actionDownxls();
		$sql = "select a.useraccessid,a.username,a.realname,a.password,a.email,a.phoneno,a.wanumber,a.telegramid,b.languagename,c.themename,
				case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from useraccess a
				left join language b on b.languageid = a.languageid
				left join theme c on c.themeid = a.themeid ";
		$useraccessid = filter_input(INPUT_GET,'useraccessid');
		$username = filter_input(INPUT_GET,'username');
		$realname = filter_input(INPUT_GET,'realname');
		$email = filter_input(INPUT_GET,'email');
		$phoneno = filter_input(INPUT_GET,'phoneno');
		$languagename = filter_input(INPUT_GET,'languagename');
		$themename = filter_input(INPUT_GET,'themename');
		$sql .= " where coalesce(a.useraccessid,'') like '%".$useraccessid."%' 
			and coalesce(a.username,'') like '%".$username."%'
			and coalesce(a.realname,'') like '%".$realname."%'
			and coalesce(a.email,'') like '%".$email."%'
			and coalesce(a.phoneno,'') like '%".$phoneno."%'
			and coalesce(b.languagename,'') like '%".$languagename."%'
			and coalesce(c.themename,'') like '%".$themename."%'
			";	
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " where a.useraccessid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by username asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('useraccessid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('username'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('realname'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('password'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('email'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('phoneno'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('wanumber'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('telegramid'))
			->setCellValueByColumnAndRow(8,2,GetCatalog('languagename'))
			->setCellValueByColumnAndRow(9,2,GetCatalog('themename'))
			->setCellValueByColumnAndRow(10,2,GetCatalog('recordstatus'));
			
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['useraccessid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['username'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['realname'])
				->setCellValueByColumnAndRow(3, $i+1, '')
				->setCellValueByColumnAndRow(4, $i+1, $row1['email'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['phoneno'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['wanumber'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['telegramid'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['languagename'])
				->setCellValueByColumnAndRow(9, $i+1, $row1['themename'])
				->setCellValueByColumnAndRow(10, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}	
}