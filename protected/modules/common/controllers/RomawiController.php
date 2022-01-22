<?php
class RomawiController extends Controller {
	public $menuname = 'romawi';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$romawiid = isset ($_POST['romawiid']) ? $_POST['romawiid'] : '';
		$monthcal = isset ($_POST['monthcal']) ? $_POST['monthcal'] : '';
		$monthrm = isset ($_POST['monthrm']) ? $_POST['monthrm'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$romawiid = isset ($_GET['q']) ? $_GET['q'] : $romawiid;
		$monthcal = isset ($_GET['q']) ? $_GET['q'] : $monthcal;
		$monthrm = isset ($_GET['q']) ? $_GET['q'] : $monthrm;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'romawiid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('romawi t')
				->where('(monthcal like :monthcal) and (monthrm like :monthrm)',
					array(':monthcal'=>'%'.$monthcal.'%',
							':monthrm'=>'%'.$monthrm.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('romawi t')
				->where('((monthcal like :monthcal) or (monthrm like :monthrm)) 
					and t.recordstatus=1',
					array(':monthcal'=>'%'.$monthcal.'%',
							':monthrm'=>'%'.$monthrm.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;		
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('romawi t')
				->where('(monthcal like :monthcal) and (monthrm like :monthrm)',
					array(':monthcal'=>'%'.$monthcal.'%',
							':monthrm'=>'%'.$monthrm.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
			}
			else {
				$cmd = Yii::app()->db->createCommand()
					->select()	
					->from('romawi t')
					->where('((monthcal like :monthcal) or 
							(monthrm like :monthrm)) and t.recordstatus=1',
											array(':monthcal'=>'%'.$monthcal.'%',
													':monthrm'=>'%'.$monthrm.'%'))
					->offset($offset)
					->limit($rows)
					->order($sort.' '.$order)
					->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'romawiid'=>$data['romawiid'],
				'monthcal'=>$data['monthcal'],
				'monthrm'=>$data['monthrm'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertromawi(:vmonthcal,:vmonthrm,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateromawi(:vid,:vmonthcal,:vmonthrm,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmonthcal',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmonthrm',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}		
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-romawi"]["name"]);
		if (move_uploaded_file($_FILES["file-romawi"]["tmp_name"], $target_file)) {
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
					$monthcal = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$monthrm = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$monthcal,$monthrm,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['romawiid'])?$_POST['romawiid']:''),$_POST['monthcal'],$_POST['monthrm'],$_POST['recordstatus']));
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
				$sql = 'call Purgeromawi(:vid,:vcreatedby)';
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
		//masukkan perintah download
	  $sql = "select romawiid,monthcal,monthrm,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from romawi a ";
		$romawiid = filter_input(INPUT_GET,'romawiid');
		$monthcal = filter_input(INPUT_GET,'monthcal');
		$monthrm = filter_input(INPUT_GET,'monthrm');
		$sql .= " where coalesce(a.romawiid,'') like '%".$romawiid."%' 
			and coalesce(a.monthcal,'') like '%".$monthcal."%'
			and coalesce(a.monthrm,'') like '%".$monthrm."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.romawiid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('romawi');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('romawiid'),
																	GetCatalog('monthcal'),
																	GetCatalog('monthrm'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,80,80,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['romawiid'],$row1['monthcal'],$row1['monthrm'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='romawi';
		parent::actionDownxls();
		$sql = "select romawiid,monthcal,monthrm,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from romawi a ";
		$romawiid = filter_input(INPUT_GET,'romawiid');
		$monthcal = filter_input(INPUT_GET,'monthcal');
		$monthrm = filter_input(INPUT_GET,'monthrm');
		$sql .= " where coalesce(a.romawiid,'') like '%".$romawiid."%' 
			and coalesce(a.monthcal,'') like '%".$monthcal."%'
			and coalesce(a.monthrm,'') like '%".$monthrm."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.romawiid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('romawiid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('monthcal'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('monthrm'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['romawiid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['monthcal'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['monthrm'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}