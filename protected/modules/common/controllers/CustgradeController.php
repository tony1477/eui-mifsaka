<?php
class CustgradeController extends Controller {
	public $menuname = 'custgrade';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	
	public function search() {
		header("Content-Type: application/json");
		$custgradeid = isset ($_POST['custgradeid']) ? $_POST['custgradeid'] : '';
		$custgradename = isset ($_POST['custgradename']) ? $_POST['custgradename'] : '';
		$custogradedesc = isset ($_POST['custogradedesc']) ? $_POST['custogradedesc'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$custgradeid = isset ($_GET['q']) ? $_GET['q'] : $custgradeid;
		$custgradename = isset ($_GET['q']) ? $_GET['q'] : $custgradename;
		$custogradedesc = isset ($_GET['q']) ? $_GET['q'] : $custogradedesc;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'custgradeid';
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
				->from('custgrade t')
				->where("((coalesce(t.custgradeid,'') like :custgradeid) and (coalesce(t.custgradename,'') like :custgradename) and (coalesce(t.custogradedesc,'') like :custogradedesc)) ",
					array(':custgradeid'=>'%'.$custgradeid.'%',
						':custgradename'=>'%'.$custgradename.'%',
						':custogradedesc'=>'%'.$custogradedesc.'%',
				))
			->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('custgrade t')
				->where("((coalesce(t.custgradeid,'') like :custgradeid) or (coalesce(t.custgradeid,'') like :custgradeid) or
                (coalesce(t.custogradedesc,'') like :custogradedesc)) and t.recordstatus=1",
					array(':custgradeid'=>'%'.$custgradeid.'%',
						':custgradename'=>'%'.$custgradename.'%',
						':custogradedesc'=>'%'.$custogradedesc.'%',
				))
			->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.custgradeid, t.custgradename, t.recordstatus, t.custogradedesc, t.description')	
				->from('custgrade t')
				->where("((coalesce(t.custgradeid,'') like :custgradeid) and (coalesce(t.custgradename,'') like :custgradename) and
                (coalesce(t.custogradedesc,'') like :custogradedesc)) ",
					array(':custgradeid'=>'%'.$custgradeid.'%',
						':custgradename'=>'%'.$custgradename.'%',
						':custogradedesc'=>'%'.$custogradedesc.'%',
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.custgradeid, t.custgradename, t.custogradedesc, t.description, t.recordstatus')	
				->from('custgrade t')
				->where("((coalesce(t.custgradeid,'') like :custgradeid) or (coalesce(t.custgradename,'') like :custgradename) or
                (coalesce(t.custogradedesc,'') like :custogradedesc)) and t.recordstatus=1",
					array(':custgradeid'=>'%'.$custgradeid.'%',
						':custgradename'=>'%'.$custgradename.'%',
						':custogradedesc'=>'%'.$custogradedesc.'%',
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'custgradeid'=>$data['custgradeid'],
				'custgradename'=>$data['custgradename'],
				'custogradedesc'=>$data['custogradedesc'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcustgrade(:vcustgradename,:vcustogradedesc,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecustgrade(:vid,:vcustgradename,:vcustogradedesc,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
            $command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcustgradename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcustogradedesc',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-custgrade"]["name"]);
		if (move_uploaded_file($_FILES["file-custgrade"]["tmp_name"], $target_file)) {
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
					$custgradecode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$parentmatgroup = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select custgradeid from custgrade where custgradecode = '".$parentmatgroup."'")->queryScalar();
					$isfg = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$custgradecode,$description,$parentid,$isfg,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['custgradeid'])?$_POST['custgradeid']:''),$_POST['custgradename'],$_POST['custogradedesc'],$_POST['description'],$_POST['recordstatus']));
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
				$sql = 'call Purgecustgrade(:vid,:vcreatedby)';
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
	  $sql = "select a.custgradeid,a.custgradecode,a.description,
						ifnull((select z.description from custgrade z where z.custgradeid = a.parentmatgroupid),'-')as parentmatgroup,
						case when a.isfg = 1 then 'Yes' else 'No' end as isfg,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from custgrade a ";
		$custgradeid = filter_input(INPUT_GET,'custgradeid');
		$custgradecode = filter_input(INPUT_GET,'custgradecode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.custgradeid,'') like '%".$custgradeid."%' 
			and coalesce(a.custgradecode,'') like '%".$custgradecode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.custgradeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by custgradecode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('custgrade');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('custgradeid'),
																	GetCatalog('custgradecode'),
																	GetCatalog('description'),
																	GetCatalog('parentmatgroup'),
																	GetCatalog('isfg'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,55,95,95,20,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['custgradeid'],$row1['custgradecode'],$row1['description'],$row1['parentmatgroup'],$row1['isfg'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='custgrade';
		parent::actionDownxls();
		$sql = "select a.custgradeid,a.custgradecode,a.description,
						ifnull((select z.description from custgrade z where z.custgradeid = a.parentmatgroupid),'-')as parentmatgroup,
						case when a.isfg = 1 then 'Yes' else 'No' end as isfg,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from custgrade a ";
		$custgradeid = filter_input(INPUT_GET,'custgradeid');
		$custgradecode = filter_input(INPUT_GET,'custgradecode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.custgradeid,'') like '%".$custgradeid."%' 
			and coalesce(a.custgradecode,'') like '%".$custgradecode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.custgradeid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by custgradecode asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('custgradeid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('custgradecode'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('parentmatgroup'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('isfg'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['custgradeid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['custgradecode'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['parentmatgroup'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['isfg'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}