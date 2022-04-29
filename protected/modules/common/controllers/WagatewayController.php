<?php
class WagatewayController extends Controller {
	public $menuname = 'wagateway';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
    public function actionIndexcompany() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchcompany();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$wagatewayid = isset ($_POST['wagatewayid']) ? $_POST['wagatewayid'] : '';
		$waname = isset ($_POST['waname']) ? $_POST['waname'] : '';
		$wanumber = isset($_GET['wanumber']) ? $_GET['wanumber'] : '';
		$devicekey = isset ($_POST['devicekey']) ? $_POST['devicekey'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$wagatewayid = isset ($_GET['q']) ? $_GET['q'] : $wagatewayid;
		$waname = isset ($_GET['q']) ? $_GET['q'] : $waname;
		$wanumber = isset ($_GET['q']) ? $_GET['q'] : $wanumber;
		$devicekey = isset ($_GET['q']) ? $_GET['q'] : $devicekey;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'wagatewayid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
        
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('wagateway t')
				->where('(waname like :waname) and (devicekey like :devicekey) and (wanumber like :wanumber)',
					array(':waname'=>'%'.$waname.'%',':devicekey'=>'%'.$devicekey.'%',':wanumber'=>'%'.$wanumber.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('wagateway t')
				->where('((waname like :waname) or 
					(devicekey like :devicekey) or 
					(wanumber like :wanumber)) and 
					t.recordstatus=1',
					array(':waname'=>'%'.$waname.'%',
						':devicekey'=>'%'.$devicekey.'%',
						':wanumber'=>'%'.$wanumber.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
       
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,')	
				->from('wagateway t')
				->where('(waname like :waname) and 
					(devicekey like :devicekey) and 
					(wanumber like :wanumber)',
						array(':waname'=>'%'.$waname.'%',
								':devicekey'=>'%'.$devicekey.'%',
								':wanumber'=>'%'.$wanumber.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('wagateway t')
				->where('((waname like :waname) or 
					(devicekey like :devicekey) or 
					(wanumber like :wanumber)) and 
					t.recordstatus=1',
						array(':waname'=>'%'.$waname.'%',
								':devicekey'=>'%'.$devicekey.'%',
								':wanumber'=>'%'.$wanumber.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		$id = GetGroupName('Administrators');
		foreach($cmd as $data) {	
			$row[] = array(
				'wagatewayid'=>$data['wagatewayid'],
				'waname'=>$data['waname'],
				'wanumber'=>$data['wanumber'],
				'devicekey'=> ($id == 1 ? $data['devicekey'] : '*****'),
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertwagateway(:vwaname,:vwanumber,:vdevicekey,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatewagateway(:vid,:vwaname,:vwanumber,:vdevicekey,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vwaname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vwanumber',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdevicekey',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	// public function actionUpload() {
	// 	parent::actionUpload();
	// 	$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-wagateway"]["name"]);
	// 	if (move_uploaded_file($_FILES["file-wagateway"]["tmp_name"], $target_file)) {
	// 		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	// 		$objPHPExcel = $objReader->load($target_file);
	// 		$objWorksheet = $objPHPExcel->getActiveSheet();
	// 		$highestRow = $objWorksheet->getHighestRow(); 
	// 		$highestColumn = $objWorksheet->getHighestColumn();
	// 		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
	// 		$connection=Yii::app()->db;
	// 		$transaction=$connection->beginTransaction();
	// 		try {
	// 			for ($row = 2; $row <= $highestRow; ++$row) {
	// 				$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
	// 				$wanumbercode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
	// 				$wanumber = Yii::app()->db->createCommand("select wanumber from company where companycode = '".$wanumbercode."'")->queryScalar();
	// 				$waname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
	// 				$devicekey = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
	// 				$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
	// 				$this->ModifyData($connection,array($id,$languagename,$recordstatus));
	// 			}
	// 			$transaction->commit();
	// 			GetMessage(false,'insertsuccess');
	// 		}
	// 		catch (Exception $e) {
	// 			$transaction->rollBack();
	// 			GetMessage(true,$e->getMessage());
	// 		}
  //   }
	// }
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {		
			$this->ModifyData($connection,array((isset($_POST['wagatewayid'])?$_POST['wagatewayid']:''),$_POST['waname'],$_POST['wanumber'],$_POST['devicekey'],$_POST['recordstatus']));
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
				$sql = 'call Purgewagateway(:vid,:vcreatedby)';
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
	  $sql = "select a.wagatewayid,a.waname,a.devicekey,b.wanumber,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from wagateway a 
						left join company b on b.wanumber = a.wanumber ";
		$wagatewayid = filter_input(INPUT_GET,'wagatewayid');
		$waname = filter_input(INPUT_GET,'waname');
		$wanumber = filter_input(INPUT_GET,'company');
		$sql .= " where coalesce(a.wagatewayid,'') like '%".$wagatewayid."%' 
			and coalesce(a.waname,'') like '%".$waname."%'
			and coalesce(b.wanumber,'') like '%".$wanumber."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.wagatewayid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('wagateway');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('wagatewayid'),
																	GetCatalog('waname'),
																	GetCatalog('devicekey'),
																	GetCatalog('wanumber'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,60,60,170,25));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['wagatewayid'],$row1['waname'],$row1['devicekey'],$row1['wanumber'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='wagateway';
		parent::actionDownxls();
		$sql = "select a.wagatewayid,a.waname,a.devicekey,b.wanumber,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from wagateway a 
						join company b on b.wanumber = a.wanumber ";
		$wagatewayid = filter_input(INPUT_GET,'wagatewayid');
		$waname = filter_input(INPUT_GET,'waname');
		$wanumber = filter_input(INPUT_GET,'company');
		$sql .= " where coalesce(a.wagatewayid,'') like '%".$wagatewayid."%' 
			and coalesce(a.waname,'') like '%".$waname."%'
			and coalesce(b.wanumber,'') like '%".$wanumber."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.wagatewayid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('wagatewayid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('waname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('devicekey'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('wanumber'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['wagatewayid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['waname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['devicekey'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['wanumber'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}