<?php
class CompanyController extends Controller {
	public $menuname = 'company';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexauth() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchauth();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexgroup() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchgroup();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcombo() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchcombo();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$companyid = isset ($_POST['companyid']) ? $_POST['companyid'] : '';
		$companyname = isset ($_POST['companyname']) ? $_POST['companyname'] : '';
		$companycode = isset ($_POST['companycode']) ? $_POST['companycode'] : '';
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'companyid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from company t 
			left join city a on a.cityid = t.cityid 
			left join currency b on b.currencyid = t.currencyid';
		$where = "
			where (coalesce(companyid,'') like '%".$companyid."%') and (coalesce(companyname,'') like '%".$companyname."%') and (coalesce(companycode,'') like '%".$companycode."%')";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = '
			select t.companyid,t.companyname,t.companycode,t.address,t.cityid,a.cityname,t.zipcode,t.taxno,t.currencyid,b.currencyname,t.faxno,t.phoneno,t.webaddress,t.email,t.leftlogofile,t.rightlogofile,t.isholding,t.billto,t.bankacc1,t.bankacc2,t.bankacc3,t.recordstatus 
				'.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'companycode'=>$data['companycode'],
				'address'=>$data['address'],
				'cityid'=>$data['cityid'],
				'cityname'=>$data['cityname'],
				'zipcode'=>$data['zipcode'],
				'taxno'=>$data['taxno'],
				'currencyid'=>$data['currencyid'],
				'currencyname'=>$data['currencyname'],
				'faxno'=>$data['faxno'],
				'phoneno'=>$data['phoneno'],
				'webaddress'=>$data['webaddress'],
				'email'=>$data['email'],
				'leftlogofile'=>$data['leftlogofile'],
				'rightlogofile'=>$data['rightlogofile'],
				'isholding'=>$data['isholding'],
				'billto'=>$data['billto'],
				'bankacc1'=>$data['bankacc1'],
				'bankacc2'=>$data['bankacc2'],
				'bankacc3'=>$data['bankacc3'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchauth() {
		header("Content-Type: application/json");
		$companyname = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.companyid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = 'from company t';
		$where = "
			where (companyname like '%".$companyname."%')
				and t.recordstatus = 1 and companyid in (".getUserObjectValues('company').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.companyid,t.companyname '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchgroup() {
		header("Content-Type: application/json");
		$companyname = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.companyid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = 'from company t';
		$where = "
			where (companyname like '%".$companyname."%')
				and t.recordstatus = 1
				-- and companyid in (".getUserObjectValues('company').")
			";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.companyid,t.companyname '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchcombo() {
		header("Content-Type: application/json");
		$companyid = isset ($_GET['q']) ? $_GET['q'] : '';
		$companyname = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.companyid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = 'from company t';
		$where = "
			where ((companyid like '%".$companyid."%') or (companyname like '%".$companyname."%'))
				and t.recordstatus = 1 and companyid in (".getUserObjectValues('company').")";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.companyid,t.companyname '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcompany(:vcompanyname,:vcompanycode,:vaddress,:vcityid,:vzipcode,:vtaxno,:vcurrencyid,:vfaxno,:vphoneno,:vwebaddress,:vemail,:vleftlogofile,:vrightlogofile,:visholding,:vbillto,:vbankacc1,:vbankacc2,:vbankacc3,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecompany(:vid,:vcompanyname,:vcompanycode,:vaddress,:vcityid,:vzipcode,:vtaxno,:vcurrencyid,:vfaxno,:vphoneno,:vwebaddress,:vemail,:vleftlogofile,:vrightlogofile,:visholding,:vbillto,:vbankacc1,:vbankacc2,:vbankacc3,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanycode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcompanyname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaddress',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcityid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vzipcode',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vtaxno',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vfaxno',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vphoneno',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vwebaddress',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vemail',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vleftlogofile',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vrightlogofile',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':visholding',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vbillto',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vbankacc1',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vbankacc2',$arraydata[17],PDO::PARAM_STR);
		$command->bindvalue(':vbankacc3',$arraydata[18],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[19],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-company"]["name"]);
		if (move_uploaded_file($_FILES["file-company"]["tmp_name"], $target_file)) {
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
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$address = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$citycode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$cityid = Yii::app()->db->createCommand("select cityid from city where citycode = '".$citycode."'")->queryScalar();
					$zipcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$taxno = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$currencyname = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
					$faxno = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$phoneno = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$webaddress = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$email = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$leftlogofile = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
					$rightlogofile = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
					$isholding = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
					$billto = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$bankacc1 = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
					$bankacc2 = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
					$bankacc3 = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
					$this->ModifyData($connection,array($id,$companycode,$companyname,$address,$cityid,$zipcode,$taxno,$currencyid,$faxno,$phoneno,$webaddress,$email,
						$leftlogofile,$rightlogofile,$isholding,$billto,$bankacc1,$bankacc2,$bankacc3,$recordstatus));
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
	public function actionUploadLeftLogo() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/images/' . basename($_FILES["file-leftlogo"]["name"]);
		if (move_uploaded_file($_FILES["file-leftlogo"]["tmp_name"], $target_file)) {
			//$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
	public function actionUploadRightLogo() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/images/' . basename($_FILES["file-rightlogo"]["name"]);
		if (move_uploaded_file($_FILES["file-rightlogo"]["tmp_name"], $target_file)) {
			//$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['companyid'])?$_POST['companyid']:''),$_POST['companycode'],$_POST['companyname'],
				$_POST['address'],$_POST['cityid'],$_POST['zipcode'],$_POST['taxno'],$_POST['currencyid'],$_POST['faxno'],$_POST['phoneno'],
				$_POST['webaddress'],$_POST['email'],$_POST['leftlogofile'],$_POST['rightlogofile'],$_POST['isholding'],$_POST['billto'],
				$_POST['bankacc1'],$_POST['bankacc2'],$_POST['bankacc3'],$_POST['recordstatus']
			));
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
				$sql = 'call Purgecompany(:vid,:vcreatedby)';
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
	  $sql = "select companyid,companyname,companycode,address,cityid,zipcode,taxno,currencyid,faxno,
				phoneno,webaddress,email,leftlogofile,rightlogofile,
				case when isholding = 1 then 'Yes' else 'No' end as isholding,
				billto,bankacc1,bankacc2,bankacc3,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from company a ";
		$companyid = filter_input(INPUT_GET,'companyid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$companycode = filter_input(INPUT_GET,'companycode');
		$sql .= " where coalesce(a.companyid,'') like '%".$companyid."%' 
			and coalesce(a.companyname,'') like '%".$companyname."%'
			and coalesce(a.companycode,'') like '%".$companycode."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.companyid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('company');
		$this->pdf->AddPage('P',array(580,360));
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('companyid'),
										GetCatalog('companyname'),
										GetCatalog('companycode'),
										GetCatalog('address'),
										GetCatalog('cityid'),
										GetCatalog('zipcode'),
										GetCatalog('taxno'),
										GetCatalog('currencyid'),
										GetCatalog('faxno'),
										GetCatalog('phoneno'),
										GetCatalog('webaddress'),
										GetCatalog('email'),
										GetCatalog('leftlogofile'),
										GetCatalog('rightlogofile'),
										GetCatalog('isholding'),
										GetCatalog('billto'),
										GetCatalog('bankacc1'),
										GetCatalog('bankacc2'),
										GetCatalog('bankacc3'),
										GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(10,65,30,40,7,17,27,7,23,23,47,47,25,25,30,40,30,30,30,13));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',8);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['companyid'],$row1['companyname'],$row1['companycode'],$row1['address'],$row1['cityid'],$row1['zipcode'],$row1['taxno'],$row1['currencyid'],$row1['faxno'],$row1['phoneno'],$row1['webaddress'],$row1['email'],$row1['leftlogofile'],$row1['rightlogofile'],$row1['isholding'],$row1['billto'],$row1['bankacc1'],$row1['bankacc2'],$row1['bankacc3'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='company';
		parent::actionDownxls();
		$sql = "select companyid,companyname,companycode,address,cityid,zipcode,taxno,currencyid,faxno,
				phoneno,webaddress,email,leftlogofile,rightlogofile,
				case when isholding = 1 then 'Yes' else 'No' end as isholding,
				billto,bankacc1,bankacc2,bankacc3,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from company a ";
		$companyid = filter_input(INPUT_GET,'companyid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$companycode = filter_input(INPUT_GET,'companycode');
		$sql .= " where coalesce(a.companyid,'') like '%".$companyid."%' 
			and coalesce(a.companyname,'') like '%".$companyname."%'
			and coalesce(a.companycode,'') like '%".$companycode."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.companyid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,2,GetCatalog('companyid'))
				->setCellValueByColumnAndRow(1,2,GetCatalog('companyname'))
				->setCellValueByColumnAndRow(2,2,GetCatalog('address'))
				->setCellValueByColumnAndRow(3,2,GetCatalog('cityid'))
				->setCellValueByColumnAndRow(4,2,GetCatalog('zipcode'))
				->setCellValueByColumnAndRow(5,2,GetCatalog('taxno'))
				->setCellValueByColumnAndRow(6,2,GetCatalog('currencyid'))
				->setCellValueByColumnAndRow(7,2,GetCatalog('faxno'))
				->setCellValueByColumnAndRow(8,2,GetCatalog('phoneno'))
				->setCellValueByColumnAndRow(9,2,GetCatalog('webaddress'))
				->setCellValueByColumnAndRow(10,2,GetCatalog('email'))
				->setCellValueByColumnAndRow(11,2,GetCatalog('leftlogofile'))
				->setCellValueByColumnAndRow(12,2,GetCatalog('rightlogofile'))
				->setCellValueByColumnAndRow(13,2,GetCatalog('isholding'))
				->setCellValueByColumnAndRow(14,2,GetCatalog('billto'))
				->setCellValueByColumnAndRow(15,2,GetCatalog('bankacc1'))
				->setCellValueByColumnAndRow(16,2,GetCatalog('bankacc2'))
				->setCellValueByColumnAndRow(17,2,GetCatalog('bankacc3'))
				->setCellValueByColumnAndRow(18,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['companyid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['companyname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['address'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['cityid'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['zipcode'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['taxno'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['currencyid'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['faxno'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['phoneno'])
				->setCellValueByColumnAndRow(9, $i+1, $row1['webaddress'])
				->setCellValueByColumnAndRow(10, $i+1, $row1['email'])
				->setCellValueByColumnAndRow(11, $i+1, $row1['leftlogofile'])
				->setCellValueByColumnAndRow(12, $i+1, $row1['rightlogofile'])
				->setCellValueByColumnAndRow(13, $i+1, $row1['isholding'])
				->setCellValueByColumnAndRow(14, $i+1, $row1['billto'])
				->setCellValueByColumnAndRow(15, $i+1, $row1['bankacc1'])
				->setCellValueByColumnAndRow(16, $i+1, $row1['bankacc2'])
				->setCellValueByColumnAndRow(17, $i+1, $row1['bankacc3'])
				->setCellValueByColumnAndRow(18, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}