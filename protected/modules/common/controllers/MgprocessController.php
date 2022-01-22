<?php
class MgprocessController extends Controller {
	public $menuname = 'mgprocess';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrx() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchtrx();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexfg() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchfg();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$mgprocessid = isset ($_POST['mgprocessid']) ? $_POST['mgprocessid'] : '';
		$mgprocesscode = isset ($_POST['mgprocesscode']) ? $_POST['mgprocesscode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$parentmgprocess = isset ($_POST['parentmgprocess']) ? $_POST['parentmgprocess'] : '';
		$isprocess = isset ($_POST['isprocess']) ? $_POST['isprocess'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$mgprocessid = isset ($_GET['q']) ? $_GET['q'] : $mgprocessid;
		$mgprocesscode = isset ($_GET['q']) ? $_GET['q'] : $mgprocesscode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$parentmgprocess = isset ($_GET['q']) ? $_GET['q'] : $parentmgprocess;
		$isprocess = isset ($_GET['q']) ? $_GET['q'] : $isprocess;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'mgprocessid';
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
				->from('mgprocess t')
				->where("((coalesce(t.mgprocessid,'') like :mgprocessid) and (coalesce(t.mgprocesscode,'') like :mgprocesscode) and (coalesce(t.description,'') like :description) and (coalesce(t.parentmgprocessid,'') like :parentmgprocess))",
					array(':mgprocesscode'=>'%'.$mgprocesscode.'%',
						':description'=>'%'.$description.'%',
						':parentmgprocess'=>'%'.$parentmgprocess.'%',
						':mgprocessid'=>'%'.$mgprocessid.'%'
				))
			->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('mgprocess t')
				->where("((coalesce(t.mgprocessid,'') like :mgprocessid) and (coalesce(t.mgprocesscode,'') like :mgprocesscode) and (coalesce(t.description,'') like :description) and (coalesce(t.parentmgprocessid,'') like :parentmgprocess)) and t.recordstatus=1",
					array(':mgprocesscode'=>'%'.$mgprocesscode.'%',
						':description'=>'%'.$description.'%',
						':parentmgprocess'=>'%'.$parentmgprocess.'%',
						':mgprocessid'=>'%'.$mgprocessid.'%'
				))
			->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,(select description from mgprocess z where z.mgprocessid = t.parentmgprocessid) as parentmatdesc')	
				->from('mgprocess t')
				->where("((coalesce(t.mgprocessid,'') like :mgprocessid) and (coalesce(t.mgprocesscode,'') like :mgprocesscode) and (coalesce(t.description,'') like :description) and (coalesce(t.parentmgprocessid,'') like :parentmgprocess))",
					array(':mgprocesscode'=>'%'.$mgprocesscode.'%',
						':description'=>'%'.$description.'%',
						':parentmgprocess'=>'%'.$parentmgprocess.'%',
						':mgprocessid'=>'%'.$mgprocessid.'%'
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,(select description from mgprocess z where z.mgprocessid = t.parentmgprocessid) as parentmatdesc')	
				->from('mgprocess t')
				->where("((coalesce(t.mgprocessid,'') like :mgprocessid) and (coalesce(t.mgprocesscode,'') like :mgprocesscode) and (coalesce(t.description,'') like :description) and (coalesce(t.parentmgprocessid,'') like :parentmgprocess)) and t.recordstatus=1",
					array(':mgprocesscode'=>'%'.$mgprocesscode.'%',
						':description'=>'%'.$description.'%',
						':parentmgprocess'=>'%'.$parentmgprocess.'%',
						':mgprocessid'=>'%'.$mgprocessid.'%'
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'mgprocessid'=>$data['mgprocessid'],
				'mgprocesscode'=>$data['mgprocesscode'],
				'description'=>$data['description'],
				'parentmgprocessid'=>$data['parentmgprocessid'],
				'parentmgprocessdesc'=>$data['parentmatdesc'],
				'isprocess'=>$data['isprocess'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrx() {
		header("Content-Type: application/json");
		$mgprocessid = isset ($_POST['mgprocessid']) ? $_POST['mgprocessid'] : '';
		$mgprocesscode = isset ($_POST['mgprocesscode']) ? $_POST['mgprocesscode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$parentmgprocessid = isset ($_POST['parentmgprocessid']) ? $_POST['parentmgprocessid'] : '';
		$isprocess = isset ($_POST['isprocess']) ? $_POST['isprocess'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$mgprocessid = isset ($_GET['q']) ? $_GET['q'] : $mgprocessid;
		$mgprocesscode = isset ($_GET['q']) ? $_GET['q'] : $mgprocesscode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$parentmgprocessid = isset ($_GET['q']) ? $_GET['q'] : $parentmgprocessid;
		$isprocess = isset ($_GET['q']) ? $_GET['q'] : $isprocess;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'mgprocessid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('mgprocess t')
			->where('((mgprocesscode like :mgprocesscode) or (t.description like :description) or (parentmgprocessid like :parentmgprocessid)) and  t.recordstatus = 1',
				array(':mgprocesscode'=>'%'.$mgprocesscode.'%',
					':description'=>'%'.$description.'%',
					':parentmgprocessid'=>'%'.$parentmgprocessid.'%'
			))
		->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,(select description from mgprocess z where z.mgprocessid = t.parentmgprocessid) as parentmatdesc')	
			->from('mgprocess t')
			->where('((mgprocesscode like :mgprocesscode) or (t.description like :description) or (parentmgprocessid like :parentmgprocessid)) and t.recordstatus = 1',
				array(':mgprocesscode'=>'%'.$mgprocesscode.'%',
					':description'=>'%'.$description.'%',
					':parentmgprocessid'=>'%'.$parentmgprocessid.'%'
			))
		->offset($offset)
		->limit($rows)
		->order($sort.' '.$order)
		->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'mgprocessid'=>$data['mgprocessid'],
				'mgprocesscode'=>$data['mgprocesscode'],
				'description'=>$data['description'],
				'parentmgprocessid'=>$data['parentmgprocessid'],
				'parentmgprocessdesc'=>$data['parentmatdesc'],
				'isprocess'=>$data['isprocess'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchfg() {
		header("Content-Type: application/json");
		$mgprocessid = isset ($_POST['mgprocessid']) ? $_POST['mgprocessid'] : '';
		$mgprocesscode = isset ($_POST['mgprocesscode']) ? $_POST['mgprocesscode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$parentmgprocessid = isset ($_POST['parentmgprocessid']) ? $_POST['parentmgprocessid'] : '';
		$isprocess = isset ($_POST['isprocess']) ? $_POST['isprocess'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$mgprocessid = isset ($_GET['q']) ? $_GET['q'] : $mgprocessid;
		$mgprocesscode = isset ($_GET['q']) ? $_GET['q'] : $mgprocesscode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$parentmgprocessid = isset ($_GET['q']) ? $_GET['q'] : $parentmgprocessid;
		$isprocess = isset ($_GET['q']) ? $_GET['q'] : $isprocess;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'mgprocessid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('mgprocess t')
			->where('((mgprocesscode like :mgprocesscode) or (t.description like :description) or (parentmgprocessid like :parentmgprocessid)) and t.isprocess = 1 and t.recordstatus = 1',
				array(':mgprocesscode'=>'%'.$mgprocesscode.'%',
					':description'=>'%'.$description.'%',
					':parentmgprocessid'=>'%'.$parentmgprocessid.'%'
			))
		->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,(select description from mgprocess z where z.mgprocessid = t.parentmgprocessid) as parentmatdesc')	
			->from('mgprocess t')
			->where('((mgprocesscode like :mgprocesscode) or (t.description like :description) or (parentmgprocessid like :parentmgprocessid)) and t.isprocess = 1 and t.recordstatus = 1',
				array(':mgprocesscode'=>'%'.$mgprocesscode.'%',
					':description'=>'%'.$description.'%',
					':parentmgprocessid'=>'%'.$parentmgprocessid.'%'
			))
		->offset($offset)
		->limit($rows)
		->order($sort.' '.$order)
		->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'mgprocessid'=>$data['mgprocessid'],
				'mgprocesscode'=>$data['mgprocesscode'],
				'description'=>$data['description'],
				'parentmgprocessid'=>$data['parentmgprocessid'],
				'parentmgprocessdesc'=>$data['parentmatdesc'],
				'isprocess'=>$data['isprocess'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmgprocess(:vmgprocesscode,:vparentmgprocessid,:vdescription,:visprocess,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatemgprocess(:vid,:vmgprocesscode,:vparentmgprocessid,:vdescription,:visprocess,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmgprocesscode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vparentmgprocessid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visprocess',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-mgprocess"]["name"]);
		if (move_uploaded_file($_FILES["file-mgprocess"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 3; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$mgprocesscode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$parentmgprocess = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select mgprocessid from mgprocess where description = '".$parentmgprocess."'")->queryScalar();
                    if($parentid == null) {
                        $parentid = 0;
                    }
					$prc = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$isprocess = Yii::app()->db->createCommand("select case when '".$prc."' = 'Yes' then '1' else '0' end")->queryScalar();
                    $rec = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $recordstatus = Yii::app()->db->createCommand("select case when '".$rec."' = 'Yes' then '1' else '0' end")->queryScalar();
					$this->ModifyData($connection,array($id,$mgprocesscode,$description,$parentid,$isprocess,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['mgprocessid'])?$_POST['mgprocessid']:''),$_POST['mgprocesscode'],$_POST['description'],(isset($_POST['parentmgprocessid']) && ($_POST['parentmgprocessid']!= '') ? $_POST['parentmgprocessid']:'0'),(isset($_POST['isprocess']) && ($_POST['isprocess']!= '') ? $_POST['isprocess']: '0'),$_POST['recordstatus']));
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
				$sql = 'call Purgemgprocess(:vid,:vcreatedby)';
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
	  $sql = "select a.mgprocessid,a.mgprocesscode,a.description,
						ifnull((select z.description from mgprocess z where z.mgprocessid = a.parentmgprocessid),'-')as parentmgprocess,
						case when a.isprocess = 1 then 'Yes' else 'No' end as isprocess,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from mgprocess a ";
		$mgprocessid = filter_input(INPUT_GET,'mgprocessid');
		$mgprocesscode = filter_input(INPUT_GET,'mgprocesscode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.mgprocessid,'') like '%".$mgprocessid."%' 
			and coalesce(a.mgprocesscode,'') like '%".$mgprocesscode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.mgprocessid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by mgprocesscode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('mgprocess');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('mgprocessid'),
																	GetCatalog('mgprocesscode'),
																	GetCatalog('description'),
																	GetCatalog('parentmgprocess'),
																	GetCatalog('isprocess'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,55,95,95,20,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['mgprocessid'],$row1['mgprocesscode'],$row1['description'],$row1['parentmgprocess'],$row1['isprocess'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXLS() {
		$this->menuname='mgprocess';
		parent::actionDownxls();
		$sql = "select a.mgprocessid,a.mgprocesscode,a.description,
						ifnull((select z.description from mgprocess z where z.mgprocessid = a.parentmgprocessid),'-')as parentmgprocess,
						case when a.isprocess = 1 then 'Yes' else 'No' end as isprocess,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from mgprocess a ";
		$mgprocessid = filter_input(INPUT_GET,'mgprocessid');
		$mgprocesscode = filter_input(INPUT_GET,'mgprocesscode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.mgprocessid,'') like '%".$mgprocessid."%' 
			and coalesce(a.mgprocesscode,'') like '%".$mgprocesscode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.mgprocessid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by mgprocesscode asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=3;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('mgprocessid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('mgprocesscode'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('parentmgprocess'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('isprocess'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i, $row1['mgprocessid'])
				->setCellValueByColumnAndRow(1, $i, $row1['mgprocesscode'])				
				->setCellValueByColumnAndRow(2, $i, $row1['description'])
				->setCellValueByColumnAndRow(3, $i, $row1['parentmgprocess'])
				->setCellValueByColumnAndRow(4, $i, $row1['isprocess'])
				->setCellValueByColumnAndRow(5, $i, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}