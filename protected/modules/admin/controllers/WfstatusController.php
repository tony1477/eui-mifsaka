<?php
class WfstatusController extends Controller {
	public $menuname = 'wfstatus';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$wfstatusid = isset ($_POST['wfstatusid']) ? $_POST['wfstatusid'] : '';
		$wfdesc = isset ($_POST['wfdesc']) ? $_POST['wfdesc'] : '';
		$wfname = isset ($_POST['wfname']) ? $_POST['wfname'] : '';
		$wfstat = isset ($_POST['wfstat']) ? $_POST['wfstat'] : '';
		$wfstatusname = isset ($_POST['wfstatusname']) ? $_POST['wfstatusname'] : '';
		$wfstatusid = isset ($_GET['q']) ? $_GET['q'] : $wfstatusid;
		$wfdesc = isset ($_GET['q']) ? $_GET['q'] : $wfdesc;
		$wfname = isset ($_GET['q']) ? $_GET['q'] : $wfname;
		$wfstat = isset ($_GET['q']) ? $_GET['q'] : $wfstat;
		$wfstatusname = isset ($_GET['q']) ? $_GET['q'] : $wfstatusname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'wfstatusid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('wfstatus t')
			->join('workflow p', 'p.workflowid=t.workflowid')
			->where('(p.wfdesc like :wfdesc) and (p.wfname like :wfname) and (wfstat like :wfstat) and (wfstatusname like :wfstatusname)',
				array(':wfdesc'=>'%'.$wfdesc.'%',':wfname'=>'%'.$wfname.'%',':wfstat'=>'%'.$wfstat.'%',':wfstatusname'=>'%'.$wfstatusname.'%'))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('wfstatus t')
			->join('workflow p', 'p.workflowid=t.workflowid')
			->where('(p.wfdesc like :wfdesc) and (p.wfname like :wfname) and (wfstat like :wfstat) and (wfstatusname like :wfstatusname)',
											array(':wfdesc'=>'%'.$wfdesc.'%',':wfname'=>'%'.$wfname.'%',':wfstat'=>'%'.$wfstat.'%',':wfstatusname'=>'%'.$wfstatusname.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'wfstatusid'=>$data['wfstatusid'],
				'workflowid'=>$data['workflowid'],
				'wfdesc'=>$data['wfdesc'],
				'wfstat'=>$data['wfstat'],
				'wfstatusname'=>$data['wfstatusname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertwfstatus(:vworkflowid,:vwfstat,:vwfstatusname,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatewfstatus(:vid,:vworkflowid,:vwfstat,:vwfstatusname,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vworkflowid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vwfstat',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vwfstatusname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-wfstatus"]["name"]);
		if (move_uploaded_file($_FILES["file-wfstatus"]["tmp_name"], $target_file)) {
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
					$workflowid = Yii::app()->db->createCommand("select workflowid from workflow where wfname = '".$wfname."'")->queryScalar();
					$wfstat = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$wfstatusname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$wfname,$wfdesc,$wfminstat,$wfmaxstat,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e)
			{
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
			$this->ModifyData($connection,array((isset($_POST['wfstatusid'])?$_POST['wfstatusid']:''),$_POST['workflowid'],$_POST['wfstat'],$_POST['wfstatusname']));
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
				$sql = 'call Purgewfstatus(:vid,:vcreatedby)';
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
	  $sql = "select a.wfstatusid,b.wfdesc as workflow,a.wfstat,a.wfstatusname
						from wfstatus a
						join workflow b on b.workflowid = a.workflowid ";
		$wfstatusid = filter_input(INPUT_GET,'wfstatusid');
		$wfdesc = filter_input(INPUT_GET,'wfdesc');
		$wfstat = filter_input(INPUT_GET,'wfstat');
		$wfstatusname = filter_input(INPUT_GET,'wfstatusname');
		$sql .= " where coalesce(a.wfstatusid,'') like '%".$wfstatusid."%' 
			and coalesce(b.wfdesc,'') like '%".$wfdesc."%'
			and coalesce(a.wfstat,'') like '%".$wfstat."%'
			and coalesce(a.wfstatusname,'') like '%".$wfstatusname."%'
			";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " where a.wfstatusid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by workflow asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('wfstatus');
		$this->pdf->AddPage('P',array(300,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('wfstatusid'),
																	GetCatalog('workflow'),
																	GetCatalog('wfstat'),
																	GetCatalog('wfstatusname'));
		$this->pdf->setwidths(array(15,110,50,110));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['wfstatusid'],$row1['workflow'],$row1['wfstat'],$row1['wfstatusname']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='wfstatus';
		parent::actionDownxls();
		$sql = "select a.wfstatusid,b.wfdesc as workflow,a.wfstat,a.wfstatusname
						from wfstatus a
						join workflow b on b.workflowid = a.workflowid ";
		$wfstatusid = filter_input(INPUT_GET,'wfstatusid');
		$wfdesc = filter_input(INPUT_GET,'wfdesc');
		$wfstat = filter_input(INPUT_GET,'wfstat');
		$wfstatusname = filter_input(INPUT_GET,'wfstatusname');
		$sql .= " where coalesce(a.wfstatusid,'') like '%".$wfstatusid."%' 
			and coalesce(b.wfdesc,'') like '%".$wfdesc."%'
			and coalesce(a.wfstat,'') like '%".$wfstat."%'
			and coalesce(a.wfstatusname,'') like '%".$wfstatusname."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " where a.wfstatusid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by workflow asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('wfstatusid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('workflow'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('wfstat'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('wfstatusname'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['wfstatusid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['workflow'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['wfstat'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['wfstatusname']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}