<?php
class ThemeController extends Controller {
	public $menuname = 'theme';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$themeid = isset ($_POST['themeid']) ? $_POST['themeid'] : '';
		$themename = isset ($_POST['themename']) ? $_POST['themename'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$themeprev = isset ($_POST['themeprev']) ? $_POST['themeprev'] : '';
		$themeid = isset ($_GET['q']) ? $_GET['q'] : $themeid;
		$themename = isset ($_GET['q']) ? $_GET['q'] : $themename;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$themeprev = isset ($_GET['q']) ? $_GET['q'] : $themeprev;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'themeid';
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
				->from('theme t')
				->where('(themeid like :themeid) and (themename like :themename) and (description like :description) and (themeprev like :themeprev)',
					array(':themeid'=>'%'.$themeid.'%',':themename'=>'%'.$themename.'%',':description'=>'%'.$description.'%',':themeprev'=>'%'.$themeprev.'%'))
				->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('theme t')
				->where('((themeid like :themeid) or (themename like :themename) or (description like :description) or (themeprev like :themeprev)) and t.recordstatus=1',
						array(':themeid'=>'%'.$themeid.'%',':themename'=>'%'.$themename.'%',':description'=>'%'.$description.'%',':themeprev'=>'%'.$themeprev.'%'))
				->queryScalar();
		}		
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()			
				->from('theme t')
				->where('(themeid like :themeid) and (themename like :themename) and (description like :description) and (themeprev like :themeprev)',
												array(':themeid'=>'%'.$themeid.'%',':themename'=>'%'.$themename.'%',':description'=>'%'.$description.'%',':themeprev'=>'%'.$themeprev.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		} else {
				$cmd = Yii::app()->db->createCommand()
					->select()			
					->from('theme t')
					->where('((themeid like :themeid) or (themename like :themename) or (description like :description) or (themeprev like :themeprev)) and t.recordstatus=1',
													array(':themeid'=>'%'.$themeid.'%',':themename'=>'%'.$themename.'%',':description'=>'%'.$description.'%',':themeprev'=>'%'.$themeprev.'%'))
					->offset($offset)
					->limit($rows)
					->order($sort.' '.$order)
					->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'themeid'=>$data['themeid'],
				'themename'=>$data['themename'],
				'description'=>$data['description'],
				'themeprev'=>$data['themeprev'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Inserttheme(:vthemename,:vdescription,:vthemeprev,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatetheme(:vid,:vthemename,:vdescription,:vthemeprev,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vthemename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vthemeprev',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-theme"]["name"]);
		if (move_uploaded_file($_FILES["file-theme"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$themename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$themeprev = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$themename,$description,$themeprev,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['themeid'])?$_POST['themeid']:''),$_POST['themename'],$_POST['description'],$_POST['themeprev'],$_POST['recordstatus']));
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
				$sql = 'call Purgetheme(:vid,:vdatauser)';
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
	  $sql = "select themeid,themename,description,themeprev,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from theme a ";
		$themeid = filter_input(INPUT_GET,'themeid');
		$themename = filter_input(INPUT_GET,'themename');
		$themeprev = filter_input(INPUT_GET,'themeprev');
		$sql .= " where coalesce(a.themeid,'') like '%".$themeid."%' 
			and coalesce(a.themename,'') like '%".$themename."%'
			and coalesce(a.themeprev,'') like '%".$themeprev."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.themeid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('theme');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('themeid'),
										GetCatalog('themename'),
										GetCatalog('description'),
										GetCatalog('themeprev'),
										GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,50,55,55,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['themeid'],$row1['themename'],$row1['description'],$row1['themeprev'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='theme';
		parent::actionDownxls();
		$sql = "select themeid,themename,description,themeprev,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from theme a ";
		$themeid = filter_input(INPUT_GET,'themeid');
		$themename = filter_input(INPUT_GET,'themename');
		$themeprev = filter_input(INPUT_GET,'themeprev');
		$sql .= " where coalesce(a.themeid,'') like '%".$themeid."%' 
			and coalesce(a.themename,'') like '%".$themename."%'
			and coalesce(a.themeprev,'') like '%".$themeprev."%'
			";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " and a.themeid in (".$_GET['id'].")";
		}
		
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('themeid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('themename'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('themeprev'))			
			->setCellValueByColumnAndRow(4,2,GetCatalog('recordstatus'));
			
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['themeid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['themename'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['themeprev'])				
				->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		
		$this->getFooterXLS($this->phpExcel);
	}
}