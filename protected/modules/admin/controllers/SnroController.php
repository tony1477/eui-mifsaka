<?php
class SnroController extends Controller {
	public $menuname = 'snro';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$snroid = isset ($_POST['snroid']) ? $_POST['snroid'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$formatdoc = isset ($_POST['formatdoc']) ? $_POST['formatdoc'] : '';
		$formatno = isset ($_POST['formatno']) ? $_POST['formatno'] : '';
		$repeatby = isset ($_POST['repeatby']) ? $_POST['repeatby'] : '';
		$snroid = isset ($_GET['q']) ? $_GET['q'] : $snroid;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$formatdoc = isset ($_GET['q']) ? $_GET['q'] : $formatdoc;
		$formatno = isset ($_GET['q']) ? $_GET['q'] : $formatno;
		$repeatby = isset ($_GET['q']) ? $_GET['q'] : $repeatby;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'snroid';
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
				->from('snro t')
				->where('(snroid like :snroid) and (description like :description) and (formatdoc like :formatdoc) and (formatno like :formatno) and (repeatby like :repeatby)',
						array(':snroid'=>'%'.$snroid.'%',':description'=>'%'.$description.'%',':formatdoc'=>'%'.$formatdoc.'%',':formatno'=>'%'.$formatno.'%',':repeatby'=>'%'.$repeatby.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('snro t')
				->where('((snroid like :snroid) or (description like :description) or (formatdoc like :formatdoc) or (formatno like :formatno) or (repeatby like :repeatby)) and t.recordstatus=1',
						array(':snroid'=>'%'.$snroid.'%',':description'=>'%'.$description.'%',':formatdoc'=>'%'.$formatdoc.'%',':formatno'=>'%'.$formatno.'%',':repeatby'=>'%'.$repeatby.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('snro t')
				->where('(snroid like :snroid) and (description like :description) and (formatdoc like :formatdoc) and (formatno like :formatno) and (repeatby like :repeatby)',
						array(':snroid'=>'%'.$snroid.'%',':description'=>'%'.$description.'%',':formatdoc'=>'%'.$formatdoc.'%',':formatno'=>'%'.$formatno.'%',':repeatby'=>'%'.$repeatby.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('snro t')
				->where('((snroid like :snroid) or (description like :description) or (formatdoc like :formatdoc) or (formatno like :formatno) or (repeatby like :repeatby)) and t.recordstatus=1',
						array(':snroid'=>'%'.$snroid.'%',':description'=>'%'.$description.'%',':formatdoc'=>'%'.$formatdoc.'%',':formatno'=>'%'.$formatno.'%',':repeatby'=>'%'.$repeatby.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'snroid'=>$data['snroid'],
				'description'=>$data['description'],
				'formatdoc'=>$data['formatdoc'],
				'formatno'=>$data['formatno'],
				'repeatby'=>$data['repeatby'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertsnro(:vdescription,:vformatdoc,:vformatno,:vrepeatby,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatesnro(:vid,:vdescription,:vformatdoc,:vformatno,:vrepeatby,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vdescription',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vformatdoc',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vformatno',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrepeatby',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-snro"]["name"]);
		if (move_uploaded_file($_FILES["file-snro"]["tmp_name"], $target_file)) {
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
					$description = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$formatdoc = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$formatno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$repeatby = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$description,$formatdoc,$formatno,$repeatby,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['snroid'])?$_POST['snroid']:''),$_POST['description'],$_POST['formatdoc'],$_POST['formatno'],$_POST['repeatby'],$_POST['recordstatus']));
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
				$sql = 'call Purgesnro(:vid,:vcreatedby)';
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
	  $sql = "select snroid,description,formatdoc,formatno,repeatby,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from snro a ";
		$snroid = filter_input(INPUT_GET,'snroid');
		$description = filter_input(INPUT_GET,'description');
		$formatdoc = filter_input(INPUT_GET,'formatdoc');
		$formatno = filter_input(INPUT_GET,'formatno');
		$repeatby = filter_input(INPUT_GET,'repeatby');
		$sql .= " where coalesce(a.snroid,'') like '%".$snroid."%' 
			and coalesce(a.formatdoc,'') like '%".$formatdoc."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(a.formatno,'') like '%".$formatno."%'
			and coalesce(a.repeatby,'') like '%".$repeatby."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.snroid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by description asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('snro');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('snroid'),
																	GetCatalog('description'),
																	GetCatalog('formatdoc'),
																	GetCatalog('formatno'),
																	GetCatalog('repeatby'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,90,90,55,55,25));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['snroid'],$row1['description'],$row1['formatdoc'],$row1['formatno'],$row1['repeatby'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='snro';
		parent::actionDownxls();
		$sql = "select snroid,description,formatdoc,formatno,repeatby,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from snro a ";
		$snroid = filter_input(INPUT_GET,'snroid');
		$description = filter_input(INPUT_GET,'description');
		$formatdoc = filter_input(INPUT_GET,'formatdoc');
		$formatno = filter_input(INPUT_GET,'formatno');
		$repeatby = filter_input(INPUT_GET,'repeatby');
		$sql .= " where coalesce(a.snroid,'') like '%".$snroid."%' 
			and coalesce(a.formatdoc,'') like '%".$formatdoc."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(a.formatno,'') like '%".$formatno."%'
			and coalesce(a.repeatby,'') like '%".$repeatby."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where a.snroid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by description asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('snroid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('formatdoc'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('formatno'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('repeatby'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['snroid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['formatdoc'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['formatno'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['repeatby'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}