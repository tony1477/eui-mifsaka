<?php
class CustcategoryController extends Controller {
	public $menuname = 'custcategory';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	
	public function search() {
		header("Content-Type: application/json");
		$custcategoryid = isset ($_POST['custcategoryid']) ? $_POST['custcategoryid'] : '';
		$custcategoryname = isset ($_POST['custcategoryname']) ? $_POST['custcategoryname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$custcategoryid = isset ($_GET['q']) ? $_GET['q'] : $custcategoryid;
		$custcategoryname = isset ($_GET['q']) ? $_GET['q'] : $custcategoryname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'custcategoryid';
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
				->from('custcategory t')
				->where("((coalesce(t.custcategoryid,'') like :custcategoryid) and (coalesce(t.custcategoryname,'') like :custcategoryname)) ",
					array(':custcategoryid'=>'%'.$custcategoryid.'%',
						':custcategoryname'=>'%'.$custcategoryname.'%',
				))
			->queryScalar();
		}
		else { // combo
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('custcategory t')
				->where("((coalesce(t.custcategoryid,'') like :custcategoryid) or (coalesce(t.custcategoryid,'') like :custcategoryid)) and  t.recordstatus=1",
					array(':custcategoryid'=>'%'.$custcategoryid.'%',
						':custcategoryname'=>'%'.$custcategoryname.'%',
				))
			->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.custcategoryid, t.custcategoryname, t.recordstatus')	
				->from('custcategory t')
				->where("((coalesce(t.custcategoryid,'') like :custcategoryid) and (coalesce(t.custcategoryname,'') like :custcategoryname)) ",
					array(':custcategoryid'=>'%'.$custcategoryid.'%',
						':custcategoryname'=>'%'.$custcategoryname.'%',
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else { //combo
			$cmd = Yii::app()->db->createCommand()
				->select('t.custcategoryid, t.custcategoryname, t.recordstatus')	
				->from('custcategory t')
				->where("((coalesce(t.custcategoryid,'') like :custcategoryid) or (coalesce(t.custcategoryname,'') like :custcategoryname)) and t.recordstatus=1",
					array(':custcategoryid'=>'%'.$custcategoryid.'%',
						':custcategoryname'=>'%'.$custcategoryname.'%',
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'custcategoryid'=>$data['custcategoryid'],
				'custcategoryname'=>$data['custcategoryname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcustcategory(:vcustcategoryname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecustcategory(:vid,:vcustcategoryname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
            $command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcustcategoryname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-custcategory"]["name"]);
		if (move_uploaded_file($_FILES["file-custcategory"]["tmp_name"], $target_file)) {
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
					$custcategorycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$parentmatgroup = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select custcategoryid from custcategory where custcategorycode = '".$parentmatgroup."'")->queryScalar();
					$isfg = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$custcategorycode,$description,$parentid,$isfg,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['custcategoryid'])?$_POST['custcategoryid']:''),$_POST['custcategoryname'],$_POST['recordstatus']));
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
				$sql = 'call Purgecustcategory(:vid,:vcreatedby)';
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
	  $sql = "select a.custcategoryid,a.custcategorycode,a.description,
						ifnull((select z.description from custcategory z where z.custcategoryid = a.parentmatgroupid),'-')as parentmatgroup,
						case when a.isfg = 1 then 'Yes' else 'No' end as isfg,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from custcategory a ";
		$custcategoryid = filter_input(INPUT_GET,'custcategoryid');
		$custcategorycode = filter_input(INPUT_GET,'custcategorycode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.custcategoryid,'') like '%".$custcategoryid."%' 
			and coalesce(a.custcategorycode,'') like '%".$custcategorycode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.custcategoryid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by custcategorycode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('custcategory');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('custcategoryid'),
																	GetCatalog('custcategorycode'),
																	GetCatalog('description'),
																	GetCatalog('parentmatgroup'),
																	GetCatalog('isfg'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,55,95,95,20,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['custcategoryid'],$row1['custcategorycode'],$row1['description'],$row1['parentmatgroup'],$row1['isfg'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='custcategory';
		parent::actionDownxls();
		$sql = "select a.custcategoryid,a.custcategorycode,a.description,
						ifnull((select z.description from custcategory z where z.custcategoryid = a.parentmatgroupid),'-')as parentmatgroup,
						case when a.isfg = 1 then 'Yes' else 'No' end as isfg,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from custcategory a ";
		$custcategoryid = filter_input(INPUT_GET,'custcategoryid');
		$custcategorycode = filter_input(INPUT_GET,'custcategorycode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.custcategoryid,'') like '%".$custcategoryid."%' 
			and coalesce(a.custcategorycode,'') like '%".$custcategorycode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.custcategoryid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by custcategorycode asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('custcategoryid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('custcategorycode'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('parentmatgroup'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('isfg'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['custcategoryid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['custcategorycode'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['parentmatgroup'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['isfg'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}