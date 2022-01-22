<?php
class ModulesController extends Controller {
	public $menuname = 'modules';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$moduleid = isset ($_POST['moduleid']) ? $_POST['moduleid'] : '';
		$modulename = isset ($_POST['modulename']) ? $_POST['modulename'] : '';
		$moduledesc = isset ($_POST['moduledesc']) ? $_POST['moduledesc'] : '';
		$moduleicon = isset ($_POST['moduleicon']) ? $_POST['moduleicon'] : '';
		$moduleid = isset ($_GET['q']) ? $_GET['q'] : $moduleid;
		$modulename = isset ($_GET['q']) ? $_GET['q'] : $modulename;
		$moduledesc = isset ($_GET['q']) ? $_GET['q'] : $moduledesc;
		$moduleicon = isset ($_GET['q']) ? $_GET['q'] : $moduleicon;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'moduleid';
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
				->from('modules t')
				->where('(moduleid like :moduleid) or (modulename like :modulename) or (moduledesc like :moduledesc) or (moduleicon like :moduleicon)',
					array(':moduleid'=>'%'.$moduleid.'%',':modulename'=>'%'.$modulename.'%',':moduledesc'=>'%'.$moduledesc.'%',':moduleicon'=>'%'.$moduleicon.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('modules t')
				->where('((moduleid like :moduleid) or (modulename like :modulename) or (moduledesc like :moduledesc) or (moduleicon like :moduleicon)) and t.recordstatus=1',
					array(':moduleid'=>'%'.$moduleid.'%',':modulename'=>'%'.$modulename.'%',':moduledesc'=>'%'.$moduledesc.'%',':moduleicon'=>'%'.$moduleicon.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()			
				->from('modules t')
				->where('(moduleid like :moduleid) or (modulename like :modulename) or (moduledesc like :moduledesc) or (moduleicon like :moduleicon)',
												array(':moduleid'=>'%'.$moduleid.'%',':modulename'=>'%'.$modulename.'%',':moduledesc'=>'%'.$moduledesc.'%',':moduleicon'=>'%'.$moduleicon.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()			
				->from('modules t')
				->where('((moduleid like :moduleid) or (modulename like :modulename) or (moduledesc like :moduledesc) or (moduleicon like :moduleicon)) and t.recordstatus=1',
					array(':moduleid'=>'%'.$moduleid.'%',':modulename'=>'%'.$modulename.'%',':moduledesc'=>'%'.$moduledesc.'%',':moduleicon'=>'%'.$moduleicon.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'moduleid'=>$data['moduleid'],
				'modulename'=>$data['modulename'],
				'moduledesc'=>$data['moduledesc'],
				'moduleicon'=>$data['moduleicon'],
				'isinstall'=>$data['isinstall'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmodules(:vmodulename,:vmoduledesc,:vmoduleicon,:visinstall,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatemodules(:vid,:vmodulename,:vmoduledesc,:vmoduleicon,:visinstall,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmodulename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmoduledesc',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmoduleicon',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visinstall',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-modules"]["name"]);
		if (move_uploaded_file($_FILES["file-modules"]["tmp_name"], $target_file)) {
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
					$modulename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$moduledesc = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$moduleicon = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$isinstall = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$modulename,$moduledesc,$moduleicon,$isinstall,$recordstatus));
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
		header("Content-Type: application/json");
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->Modifydata($connection,array((isset($_POST['moduleid'])?$_POST['moduleid']:''),$_POST['modulename'],$_POST['moduledesc'],$_POST['moduleicon'],$_POST['isinstall'],$_POST['recordstatus']));
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
		header("Content-Type: application/json");
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgemodules(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
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
	  $sql = "select moduleid,modulename,moduledesc,moduleicon,
				case when isinstall = 1 then 'Yes' else 'No' end as isinstall,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from modules a ";
		$moduleid = filter_input(INPUT_GET,'moduleid');
		$modulename = filter_input(INPUT_GET,'modulename');
		$moduledesc = filter_input(INPUT_GET,'moduledesc');
		$moduleicon = filter_input(INPUT_GET,'moduleicon');
		$sql .= " where coalesce(a.moduleid,'') like '%".$moduleid."%' 
			and coalesce(a.modulename,'') like '%".$modulename."%'
			and coalesce(a.moduledesc,'') like '%".$moduledesc."%'
			and coalesce(a.moduleicon,'') like '%".$moduleicon."%'
			";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " and a.moduleid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by modulename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('modules');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('moduleid'),
										GetCatalog('modulename'),
										GetCatalog('moduledesc'),
										GetCatalog('moduleicon'),
										GetCatalog('isinstall'),
										GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,40,40,55,22,22));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['moduleid'],$row1['modulename'],$row1['moduledesc'],$row1['moduleicon'],$row1['isinstall'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='modules';
		parent::actionDownxls();
		$sql = "select moduleid,modulename,moduledesc,moduleicon,
				case when isinstall = 1 then 'Yes' else 'No' end as isinstall,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from modules a ";
		$moduleid = filter_input(INPUT_GET,'moduleid');
		$modulename = filter_input(INPUT_GET,'modulename');
		$moduledesc = filter_input(INPUT_GET,'moduledesc');
		$moduleicon = filter_input(INPUT_GET,'moduleicon');
		$sql .= " where coalesce(a.moduleid,'') like '%".$moduleid."%' 
			and coalesce(a.modulename,'') like '%".$modulename."%'
			and coalesce(a.moduledesc,'') like '%".$moduledesc."%'
			and coalesce(a.moduleicon,'') like '%".$moduleicon."%'
			";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " and a.moduleid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by modulename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('moduleid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('modulename'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('moduledesc'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('moduleicon'))			
			->setCellValueByColumnAndRow(4,2,GetCatalog('isinstall'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['moduleid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['modulename'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['moduledesc'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['moduleicon'])	
				->setCellValueByColumnAndRow(4, $i+1, $row1['isinstall'])				
				->setCellValueByColumnAndRow(5, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}