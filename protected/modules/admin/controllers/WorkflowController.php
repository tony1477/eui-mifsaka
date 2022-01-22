<?php
class WorkflowController extends Controller {
	public $menuname = 'workflow';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$workflowid = isset ($_POST['workflowid']) ? $_POST['workflowid'] : '';
		$wfname = isset ($_POST['wfname']) ? $_POST['wfname'] : '';
		$wfdesc = isset ($_POST['wfdesc']) ? $_POST['wfdesc'] : '';
		$wfminstat = isset ($_POST['wfminstat']) ? $_POST['wfminstat'] : '';
		$wfmaxstat = isset ($_POST['wfmaxstat']) ? $_POST['wfmaxstat'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';  
		$workflowid = isset ($_GET['q']) ? $_GET['q'] : $workflowid;
		$wfname = isset ($_GET['q']) ? $_GET['q'] : $wfname;
		$wfdesc = isset ($_GET['q']) ? $_GET['q'] : $wfdesc;
		$wfminstat = isset ($_GET['q']) ? $_GET['q'] : $wfminstat;
		$wfmaxstat = isset ($_GET['q']) ? $_GET['q'] : $wfmaxstat;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'workflowid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';                
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('workflow t')
				->where('(workflowid like :workflowid) and (wfname like :wfname) and (wfdesc like :wfdesc) and (wfminstat like :wfminstat) and (wfmaxstat like :wfmaxstat)',
					array(':workflowid'=>'%'.$workflowid.'%',':wfname'=>'%'.$wfname.'%',':wfdesc'=>'%'.$wfdesc.'%',':wfminstat'=>'%'.$wfminstat.'%',':wfmaxstat'=>'%'.$wfmaxstat.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('workflow t')
				->where('((workflowid like :workflowid) or (wfname like :wfname) or (wfdesc like :wfdesc) or (wfminstat like :wfminstat) or (wfmaxstat like :wfmaxstat)) and t.recordstatus=1',
					array(':workflowid'=>'%'.$workflowid.'%',':wfname'=>'%'.$wfname.'%',':wfdesc'=>'%'.$wfdesc.'%',':wfminstat'=>'%'.$wfminstat.'%',':wfmaxstat'=>'%'.$wfmaxstat.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('workflow t')
				->where('(workflowid like :workflowid) and (wfname like :wfname) and (wfdesc like :wfdesc) and (wfminstat like :wfminstat) and (wfmaxstat like :wfmaxstat)',
					array(':workflowid'=>'%'.$workflowid.'%',':wfname'=>'%'.$wfname.'%',':wfdesc'=>'%'.$wfdesc.'%',':wfminstat'=>'%'.$wfminstat.'%',':wfmaxstat'=>'%'.$wfmaxstat.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('workflow t')
				->where('((workflowid like :workflowid) or (wfname like :wfname) or (wfdesc like :wfdesc) or (wfminstat like :wfminstat) or (wfmaxstat like :wfmaxstat)) and t.recordstatus=1',
						array(':workflowid'=>'%'.$workflowid.'%',':wfname'=>'%'.$wfname.'%',':wfdesc'=>'%'.$wfdesc.'%',':wfminstat'=>'%'.$wfminstat.'%',':wfmaxstat'=>'%'.$wfmaxstat.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}		
		foreach($cmd as $data) {	
			$row[] = array(
				'workflowid'=>$data['workflowid'],
				'wfname'=>$data['wfname'],
				'wfdesc'=>$data['wfdesc'],
				'wfminstat'=>$data['wfminstat'],
				'wfmaxstat'=>$data['wfmaxstat'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}	
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertworkflow(:vwfname,:vwfdesc,:vwfminstat,:vwfmaxstat,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateworkflow(:vid,:vwfname,:vwfdesc,:vwfminstat,:vwfmaxstat,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vwfname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vwfdesc',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vwfminstat',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vwfmaxstat',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-workflow"]["name"]);
		if (move_uploaded_file($_FILES["file-workflow"]["tmp_name"], $target_file)) {
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
					$wfname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$wfdesc = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$wfminstat = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$wfmaxstat = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$wfname,$wfdesc,$wfminstat,$wfmaxstat,$recordstatus));
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
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['workflowid'])?$_POST['workflowid']:''),
				$_POST['wfname'],
				$_POST['wfdesc'],
				$_POST['wfminstat'],
				$_POST['wfmaxstat'],
				$_POST['recordstatus']));
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
				$sql = 'call Purgeworkflow(:vid,:vcreatedby)';
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
		//masukkan perintah download
	  $sql = "select workflowid,wfname,wfdesc,wfminstat,wfmaxstat,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from workflow a ";
		$workflowid = filter_input(INPUT_GET,'workflowid');
		$wfname = filter_input(INPUT_GET,'wfname');
		$wfdesc = filter_input(INPUT_GET,'wfdesc');
		$wfminstat = filter_input(INPUT_GET,'wfminstat');
		$wfmaxstat = filter_input(INPUT_GET,'wfmaxstat');
		$sql .= " where coalesce(a.workflowid,'') like '%".$workflowid."%' 
			and coalesce(a.wfname,'') like '%".$wfname."%'
			and coalesce(a.wfdesc,'') like '%".$wfdesc."%'
			and coalesce(a.wfminstat,'') like '%".$wfminstat."%'
			and coalesce(a.wfmaxstat,'') like '%".$wfmaxstat."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where a.workflowid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by wfname asc, wfdesc asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('workflow');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('workflowid'),
																	GetCatalog('wfname'),
																	GetCatalog('wfdesc'),
																	GetCatalog('wfminstat'),
																	GetCatalog('wfmaxstat'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,40,75,20,23,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['workflowid'],$row1['wfname'],$row1['wfdesc'],$row1['wfminstat'],$row1['wfmaxstat'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='workflow';
		parent::actionDownxls();
		$sql = "select workflowid,wfname,wfdesc,wfminstat,wfmaxstat,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from workflow a ";
		$workflowid = filter_input(INPUT_GET,'workflowid');
		$wfname = filter_input(INPUT_GET,'wfname');
		$wfdesc = filter_input(INPUT_GET,'wfdesc');
		$wfminstat = filter_input(INPUT_GET,'wfminstat');
		$wfmaxstat = filter_input(INPUT_GET,'wfmaxstat');
		$sql .= " where coalesce(a.workflowid,'') like '%".$workflowid."%' 
			and coalesce(a.wfname,'') like '%".$wfname."%'
			and coalesce(a.wfdesc,'') like '%".$wfdesc."%'
			and coalesce(a.wfminstat,'') like '%".$wfminstat."%'
			and coalesce(a.wfmaxstat,'') like '%".$wfmaxstat."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where a.workflowid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by wfname asc, wfdesc asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;				
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('workflowid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('wfname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('wfdesc'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('wfminstat'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('wfmaxstat'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['workflowid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['wfname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['wfdesc'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['wfminstat'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['wfmaxstat'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}