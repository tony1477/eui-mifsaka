<?php
class LanguageController extends Controller {
	public $menuname = 'language';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$languageid = isset($_POST['languageid']) ? $_POST['languageid'] : '';
		$languagename = isset($_POST['languagename']) ? $_POST['languagename'] : '';
		$languageid = isset($_GET['q']) ? $_GET['q'] : $languageid;
		$languagename = isset($_GET['q']) ? $_GET['q'] : $languagename;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'languageid';
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
				->from('language t')
				->where('(languageid like :languageid) and (languagename like :languagename)',
						array(':languageid'=>'%'.$languageid.'%',':languagename'=>'%'.$languagename.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('language t')
				->where('(languageid like :languageid) or (languagename like :languagename) and recordstatus = 1',
						array(':languageid'=>'%'.$languageid.'%',':languagename'=>'%'.$languagename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('*')			
				->from('language t')
				->where('(languageid like :languageid) and (languagename like :languagename)',
						array(':languageid'=>'%'.$languageid.'%',':languagename'=>'%'.$languagename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('*')			
				->from('language t')
				->where('(languageid like :languageid) or (languagename like :languagename) and recordstatus = 1',
						array(':languageid'=>'%'.$languageid.'%',':languagename'=>'%'.$languagename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		} 
		foreach($cmd as $data) {	
			$row[] = array(
				'languageid'=>$data['languageid'],
				'languagename'=>$data['languagename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = "call InsertLanguage(:vlanguagename,:vrecordstatus,:vcreatedby)";
			$command = $connection->createCommand($sql);
		} else {
			$sql = "call UpdateLanguage(:vlanguageid,:vlanguagename,:vrecordstatus,:vcreatedby)";
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vlanguageid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vlanguagename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-language"]["name"]);
		if (move_uploaded_file($_FILES["file-language"]["tmp_name"], $target_file)) {
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
					$languagename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
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
			$this->ModifyData($connection,array((isset($_POST['languageid'])?$_POST['languageid']:''),$_POST['languagename'],$_POST['recordstatus']));
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
				$sql = 'call PurgeLanguage(:vid,:vcreatedby)';
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
		$sql = "select languageid,languagename,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from language a ";
		$languageid = filter_input(INPUT_GET,'languageid');
		$languagename = filter_input(INPUT_GET,'languagename');
		$sql .= " where coalesce(a.languageid,'') like '%".$languageid."%' 
			and coalesce(a.languagename,'') like '%".$languagename."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.languageid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by languagename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('language');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','C','C');
		$this->pdf->setwidths(array(15,150,25));									
		$this->pdf->colheader = array(GetCatalog('languageid'),
			GetCatalog('languagename'),
			GetCatalog('recordstatus'));
		$this->pdf->rowheader();				
		$this->pdf->coldetailalign = array('L','L','C');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['languageid'],$row1['languagename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}	
	public function actionDownXls() {
		$this->menuname='language';
		parent::actionDownxls();
		$sql = "select languageid,languagename,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from language a ";
		$languageid = filter_input(INPUT_GET,'languageid');
		$languagename = filter_input(INPUT_GET,'languagename');
		$sql .= " where a.languageid like '%".$languageid."%' and a.languagename like '%".$languagename."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.languageid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by languagename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('languageid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('languagename'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {		
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['languageid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['languagename'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}