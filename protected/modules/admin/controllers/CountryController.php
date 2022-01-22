<?php
class CountryController extends Controller {
	public $menuname = 'country';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$countryid = isset ($_POST['countryid']) ? $_POST['countryid'] : '';
		$countrycode = isset ($_POST['countrycode']) ? $_POST['countrycode'] : '';
		$countryname = isset ($_POST['countryname']) ? $_POST['countryname'] : '';
		$countryid = isset ($_GET['q']) ? $_GET['q'] : $countryid;
		$countrycode = isset ($_GET['q']) ? $_GET['q'] : $countrycode;
		$countryname = isset ($_GET['q']) ? $_GET['q'] : $countryname;	
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'countryid';
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
				->from('country t')
				->where('(countryid like :countryid) and (countrycode like :countrycode) and (countryname like :countryname)',
						array(':countryid'=>'%'.$countryid.'%',':countrycode'=>'%'.$countrycode.'%',':countryname'=>'%'.$countryname.'%'))
				->queryScalar();
		}
		else {			
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('country t')
				->where('((countryid like :countryid) or (countrycode like :countrycode) or (countryname like :countryname)) and t.recordstatus = 1',
						array(':countryid'=>'%'.$countryid.'%',':countrycode'=>'%'.$countrycode.'%',':countryname'=>'%'.$countryname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('*')			
				->from('country t')
				->where('(countryid like :countryid) and (countrycode like :countrycode) and (countryname like :countryname)',
						array(':countryid'=>'%'.$countryid.'%',':countrycode'=>'%'.$countrycode.'%',':countryname'=>'%'.$countryname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('*')			
				->from('country t')
				->where('((countryid like :countryid) or (countrycode like :countrycode) or (countryname like :countryname)) and t.recordstatus = 1',
						array(':countryid'=>'%'.$countryid.'%',':countrycode'=>'%'.$countrycode.'%',':countryname'=>'%'.$countryname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'countryid'=>$data['countryid'],
			'countrycode'=>$data['countrycode'],
			'countryname'=>$data['countryname'],
			'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {		
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcountry(:vcountrycode,:vcountryname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecountry(:vid,:vcountrycode,:vcountryname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcountrycode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcountryname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-country"]["name"]);
		if (move_uploaded_file($_FILES["file-country"]["tmp_name"], $target_file)) {
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
					$countrycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$countryname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$countrycode,$countryname,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['countryid'])?$_POST['countryid']:''),$_POST['countrycode'],$_POST['countryname'],$_POST['recordstatus']));
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
			try {
				$sql = 'call Purgecountry(:vid,:vcreatedby)';
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
		$sql = "select countryid,countrycode,countryname,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from country a ";
		$countryid = filter_input(INPUT_GET,'countryid');
		$countrycode = filter_input(INPUT_GET,'countrycode');
		$countryname = filter_input(INPUT_GET,'countryname');
		$sql .= " where coalesce(a.countryid,'') like '%".$countryid."%' 
			and coalesce(a.countryname,'') like '%".$countryname."%'
			and coalesce(a.countrycode,'') like '%".$countrycode."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.countryid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by countryname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		$this->pdf->title=GetCatalog('country');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('countryid'),
			GetCatalog('countrycode'),
			GetCatalog('countryname'),
			GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,20,135,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['countryid'],$row1['countrycode'],$row1['countryname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='country';
		parent::actionDownxls();
		$sql = "select countryid,countrycode,countryname,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from country a ";
		$countryid = filter_input(INPUT_GET,'countryid');
		$countrycode = filter_input(INPUT_GET,'countrycode');
		$countryname = filter_input(INPUT_GET,'countryname');
		$sql .= " where coalesce(a.countryid,'') like '%".$countryid."%' 
			and coalesce(a.countryname,'') like '%".$countryname."%'
			and coalesce(a.countrycode,'') like '%".$countrycode."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.countryid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by countryname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('countryid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('countrycode'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('countryname'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['countryid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['countrycode'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['countryname'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}