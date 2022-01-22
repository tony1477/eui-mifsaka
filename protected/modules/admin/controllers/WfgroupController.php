<?php
class WfgroupController extends Controller {
	public $menuname = 'wfgroup';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$wfgroupid = isset ($_POST['wfgroupid']) ? $_POST['wfgroupid'] : '';
		$workflow = isset ($_POST['workflow']) ? $_POST['workflow'] : '';
		$groupaccess = isset ($_POST['groupaccess']) ? $_POST['groupaccess'] : '';
		$wfbefstat = isset ($_POST['wfbefstat']) ? $_POST['wfbefstat'] : '';
		$wfrecstat = isset ($_POST['wfrecstat']) ? $_POST['wfrecstat'] : '';
		$wfgroupid = isset ($_GET['q']) ? $_GET['q'] : $wfgroupid;
		$workflow = isset ($_GET['q']) ? $_GET['q'] : $workflow;
		$groupaccess = isset ($_GET['q']) ? $_GET['q'] : $groupaccess;
		$wfbefstat = isset ($_GET['q']) ? $_GET['q'] : $wfbefstat;
		$wfrecstat = isset ($_GET['q']) ? $_GET['q'] : $wfrecstat;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'wfgroupid';
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
			->from('wfgroup t')
			->join('workflow p', 'p.workflowid=t.workflowid')
			->join('groupaccess q', 'q.groupaccessid=t.groupaccessid')
			->where('(p.wfdesc like :workflow) and (q.groupname like :groupaccess)',
						array(':workflow'=>'%'.$workflow.'%',':groupaccess'=>'%'.$groupaccess.'%'))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.wfdesc,q.groupname')	
			->from('wfgroup t')
			->join('workflow p', 'p.workflowid=t.workflowid')
			->join('groupaccess q', 'q.groupaccessid=t.groupaccessid')
			->where('(p.wfdesc like :workflow) and (q.groupname like :groupaccess)',
					array(':workflow'=>'%'.$workflow.'%',':groupaccess'=>'%'.$groupaccess.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();	
		foreach($cmd as $data) {	
			$row[] = array(
				'wfgroupid'=>$data['wfgroupid'],
				'workflowid'=>$data['workflowid'],
				'wfdesc'=>$data['wfdesc'],
				'groupaccessid'=>$data['groupaccessid'],
				'groupname'=>$data['groupname'],
				'wfbefstat'=>$data['wfbefstat'],
				'wfrecstat'=>$data['wfrecstat'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertwfgroup(:vworkflowid,:vgroupaccessid,:vwfbefstat,:vwfrecstat,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatewfgroup(:vid,:vworkflowid,:vgroupaccessid,:vwfbefstat,:vwfrecstat,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vworkflowid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vgroupaccessid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vwfbefstat',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vwfrecstat',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-wfgroup"]["name"]);
		if (move_uploaded_file($_FILES["file-wfgroup"]["tmp_name"], $target_file)) {
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
					$groupname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$groupid = Yii::app()->db->createCommand("select groupaccessid from groupaccess where groupname = '".$groupname."'")->queryScalar();
					$wfbefstat = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$wfrecstat = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$languagename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['wfgroupid'])?$_POST['wfgroupid']:''),$_POST['workflowid'],$_POST['groupaccessid'],$_POST['wfbefstat'],$_POST['wfrecstat']));
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
				$sql = 'call Purgewfgroup(:vid,:vcreatedby)';
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
	  $sql = "select a.wfgroupid,b.wfdesc as workflow,c.groupname as groupaccess,a.wfbefstat,a.wfrecstat
						from wfgroup a
						left join workflow b on b.workflowid = a.workflowid
						left join groupaccess c on c.groupaccessid = a.groupaccessid ";
		$wfgroupid = filter_input(INPUT_GET,'wfgroupid');
		$workflow = filter_input(INPUT_GET,'workflow');
		$groupaccess = filter_input(INPUT_GET,'groupaccess');
		$sql .= " where coalesce(a.wfgroupid,'') like '%".$wfgroupid."%' 
			and coalesce(b.wfdesc,'') like '%".$workflow."%'
			and coalesce(c.groupname,'') like '%".$groupaccess."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.wfgroupid in (".$_GET['id'].")";
		}
		$sql = $sql . "order by workflow asc, groupaccess asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('wfgroup');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('wfgroupid'),
																	GetCatalog('workflow'),
																	GetCatalog('groupaccess'),
																	GetCatalog('wfbefstat'),
																	GetCatalog('wfrecstat'));
		$this->pdf->setwidths(array(20,100,100,55,55));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['wfgroupid'],$row1['workflow'],$row1['groupaccess'],$row1['wfbefstat'],$row1['wfrecstat']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='wfgroup';
		parent::actionDownxls();
		$sql = "select a.wfgroupid,b.wfdesc as workflow,c.groupname as groupaccess,a.wfbefstat,a.wfrecstat
						from wfgroup a
						left join workflow b on b.workflowid = a.workflowid
						left join groupaccess c on c.groupaccessid = a.groupaccessid ";
		$wfgroupid = filter_input(INPUT_GET,'wfgroupid');
		$workflow = filter_input(INPUT_GET,'workflow');
		$groupaccess = filter_input(INPUT_GET,'groupaccess');
		$sql .= " where coalesce(a.wfgroupid,'') like '%".$wfgroupid."%' 
			and coalesce(b.wfdesc,'') like '%".$workflow."%'
			and coalesce(c.groupname,'') like '%".$groupaccess."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.wfgroupid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by workflow asc, groupaccess asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('wfgroupid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('workflow'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('groupaccess'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('wfbefstat'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('wfrecstat'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['wfgroupid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['workflow'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['groupaccess'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['wfbefstat'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['wfrecstat']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}