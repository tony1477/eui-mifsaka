<?php
class ParameterController extends Controller {
	public $menuname = 'parameter';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$parameterid = isset ($_POST['parameterid']) ? $_POST['parameterid'] : '';
		$paramname = isset ($_POST['paramname']) ? $_POST['paramname'] : '';
		$paramvalue = isset ($_POST['paramvalue']) ? $_POST['paramvalue'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$modulename = isset ($_POST['modulename']) ? $_POST['modulename'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$parameterid = isset ($_GET['q']) ? $_GET['q'] : $parameterid;
		$paramname = isset ($_GET['q']) ? $_GET['q'] : $paramname;
		$paramvalue = isset ($_GET['q']) ? $_GET['q'] : $paramvalue;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$modulename = isset ($_GET['q']) ? $_GET['q'] : $modulename;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.parameterid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('parameter t')
				->join('modules p','t.moduleid=p.moduleid')
				->where('(paramname like :paramname) and (paramvalue like :paramvalue) and (description like :description) and (p.modulename like :modulename)',
					array(':paramname'=>'%'.$paramname.'%',':paramvalue'=>'%'.$paramvalue.'%',':description'=>'%'.$description.'%',':modulename'=>'%'.$modulename.'%'))			
				->queryScalar();
		}
    else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('parameter t')
				->join('modules p','t.moduleid=p.moduleid')
				->where('((paramname like :paramname) or (paramvalue like :paramvalue) or (description like :description) or (p.modulename like :modulename)) and t.recordstatus=1',
					array(':paramname'=>'%'.$paramname.'%',':paramvalue'=>'%'.$paramvalue.'%',':description'=>'%'.$description.'%',':modulename'=>'%'.$modulename.'%'))			
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('parameter t')
				->join('modules p','t.moduleid=p.moduleid')
				->where('(paramname like :paramname) and (paramvalue like :paramvalue) and (description like :description) and (p.modulename like :modulename)',
						array(':paramname'=>'%'.$paramname.'%',':paramvalue'=>'%'.$paramvalue.'%',':description'=>'%'.$description.'%',':modulename'=>'%'.$modulename.'%'))			
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
    }
    else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('parameter t')
				->join('modules p','t.moduleid=p.moduleid')
				->where('((paramname like :paramname) or (paramvalue like :paramvalue) or (description like :description) or (p.modulename like :modulename)) and t.recordstatus=1',
												array(':paramname'=>'%'.$paramname.'%',':paramvalue'=>'%'.$paramvalue.'%',':description'=>'%'.$description.'%',':modulename'=>'%'.$modulename.'%'))			
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'parameterid'=>$data['parameterid'],
				'paramname'=>$data['paramname'],
				'paramvalue'=>$data['paramvalue'],
				'description'=>$data['description'],
				'moduleid'=>$data['moduleid'],
				'modulename'=>$data['modulename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertparameter(:vparamname,:vparamvalue,:vdescription,:vmoduleid,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateparameter(:vid,:vparamname,:vparamvalue,:vdescription,:vmoduleid,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vparamname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vparamvalue',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmoduleid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-parameter"]["name"]);
		if (move_uploaded_file($_FILES["file-parameter"]["tmp_name"], $target_file)) {
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
					$paramname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$paramvalue = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$modulename = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$moduleid = Yii::app()->db->createCommand("select moduleid from modules where modulename = '".$modulename."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$paramname,$paramvalue,$description,$moduleid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['parameterid'])?$_POST['parameterid']:''),$_POST['paramname'],$_POST['paramvalue'],$_POST['description'],
				$_POST['moduleid'],$_POST['recordstatus']));
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
				$sql = 'call Purgeparameter(:vid,:vdatauser)';
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
	  $sql = "select a.parameterid,a.paramname,a.paramvalue,a.description,b.modulename as modules,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from parameter a 
						left join modules b on b.moduleid = a.moduleid ";
		$parameterid = filter_input(INPUT_GET,'parameterid');
		$paramname = filter_input(INPUT_GET,'paramname');
		$paramvalue = filter_input(INPUT_GET,'paramvalue');
		$description = filter_input(INPUT_GET,'description');
		$modulename = filter_input(INPUT_GET,'modulename');
		$sql .= " where coalesce(a.parameterid,'') like '%".$parameterid."%' 
			and coalesce(a.paramname,'') like '%".$paramname."%'
			and coalesce(a.paramvalue,'') like '%".$paramvalue."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(b.modulename,'') like '%".$modulename."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.parameterid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by paramname asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('parameter');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('parameterid'),
																	GetCatalog('paramname'),
																	GetCatalog('paramvalue'),
																	GetCatalog('description'),
																	GetCatalog('modules'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,70,90,90,50,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['parameterid'],$row1['paramname'],$row1['paramvalue'],$row1['description'],$row1['modules'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='parameter';
		parent::actionDownxls();
		$sql = "select a.parameterid,a.paramname,a.paramvalue,a.description,b.modulename as modules,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from parameter a 
						left join modules b on b.moduleid = a.moduleid ";
		$parameterid = filter_input(INPUT_GET,'parameterid');
		$paramname = filter_input(INPUT_GET,'paramname');
		$paramvalue = filter_input(INPUT_GET,'paramvalue');
		$description = filter_input(INPUT_GET,'description');
		$modulename = filter_input(INPUT_GET,'modulename');
		$sql .= " where coalesce(a.parameterid,'') like '%".$parameterid."%' 
			and coalesce(a.paramname,'') like '%".$paramname."%'
			and coalesce(a.paramvalue,'') like '%".$paramvalue."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(b.modulename,'') like '%".$modulename."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.parameterid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by paramname asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('parameterid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('paramname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('paramvalue'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('modules'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['parameterid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['paramname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['paramvalue'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['modules'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}