<?php
class PricecategoryController extends Controller {
	public $menuname = 'pricecategory';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$categoryname = isset ($_POST['categoryname']) ? $_POST['categoryname'] : '';
		$categoryname = isset ($_GET['q']) ? $_GET['q'] : $categoryname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'pricecategoryid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
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
				->from('pricecategory t')
				->where('(categoryname like :categoryname)',
					array(':categoryname'=>'%'.$categoryname.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('pricecategory t')
				->where('((categoryname like :categoryname)) and t.recordstatus=1',
					array(':categoryname'=>'%'.$categoryname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('pricecategory t')
				->where('(categoryname like :categoryname)',
					array(':categoryname'=>'%'.$categoryname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('pricecategory t')
				->where('((categoryname like :categoryname)) and t.recordstatus=1',
					array(':categoryname'=>'%'.$categoryname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'pricecategoryid'=>$data['pricecategoryid'],
				'categoryname'=>$data['categoryname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertpricecategory(:vcategoryname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatepricecategory(:vid,:vcategoryname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$_POST['pricecategoryid'],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $_POST['pricecategoryid']);
		}
		$command->bindvalue(':vcategoryname',$_POST['categoryname'],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-pricecategory"]["name"]);
		if (move_uploaded_file($_FILES["file-pricecategory"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$categoryname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$categoryname,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['pricecategoryid'])?$_POST['pricecategoryid']:''),$_POST['categoryname'],$_POST['recordstatus']));
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
				$sql = 'call Purgepricecategory(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
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
	  $sql = "select pricecategoryid,categoryname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from pricecategory a ";
		$pricecategoryid = filter_input(INPUT_GET,'pricecategoryid');
		$categoryname = filter_input(INPUT_GET,'categoryname');
		$sql .= " where coalesce(a.pricecategoryid,'') like '%".$pricecategoryid."%' 
			and coalesce(a.categoryname,'') like '%".$categoryname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.pricecategoryid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by categoryname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('pricecategory');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('pricecategoryid'),
																	GetCatalog('categoryname'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['pricecategoryid'],$row1['categoryname'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='pricecategory';
		parent::actionDownxls();
		$sql = "select pricecategoryid,categoryname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from pricecategory a ";
		$pricecategoryid = filter_input(INPUT_GET,'pricecategoryid');
		$categoryname = filter_input(INPUT_GET,'categoryname');
		$sql .= " where coalesce(a.pricecategoryid,'') like '%".$pricecategoryid."%' 
			and coalesce(a.categoryname,'') like '%".$categoryname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.pricecategoryid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by categoryname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('pricecategoryid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('categoryname'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['pricecategoryid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['categoryname'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}