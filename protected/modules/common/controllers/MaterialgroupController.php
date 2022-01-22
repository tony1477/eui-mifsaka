<?php
class MaterialgroupController extends Controller {
	public $menuname = 'materialgroup';
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
		$materialgroupid = isset ($_POST['materialgroupid']) ? $_POST['materialgroupid'] : '';
		$materialgroupcode = isset ($_POST['materialgroupcode']) ? $_POST['materialgroupcode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$parentmatgroup = isset ($_POST['parentmatgroup']) ? $_POST['parentmatgroup'] : '';
		$isfg = isset ($_POST['isfg']) ? $_POST['isfg'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$materialgroupid = isset ($_GET['q']) ? $_GET['q'] : $materialgroupid;
		$materialgroupcode = isset ($_GET['q']) ? $_GET['q'] : $materialgroupcode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$parentmatgroup = isset ($_GET['q']) ? $_GET['q'] : $parentmatgroup;
		$isfg = isset ($_GET['q']) ? $_GET['q'] : $isfg;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'materialgroupid';
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
				->from('materialgroup t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->where("((coalesce(t.materialgroupid,'') like :materialgroupid) and (coalesce(t.materialgroupcode,'') like :materialgroupcode) and (coalesce(t.description,'') like :description) and (coalesce(t.parentmatgroupid,'') like :parentmatgroup))",
					array(':materialgroupcode'=>'%'.$materialgroupcode.'%',
						':description'=>'%'.$description.'%',
						':parentmatgroup'=>'%'.$parentmatgroup.'%',
						':materialgroupid'=>'%'.$materialgroupid.'%'
				))
			->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('materialgroup t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->where("((coalesce(t.materialgroupid,'') like :materialgroupid) and (coalesce(t.materialgroupcode,'') like :materialgroupcode) and (coalesce(t.description,'') like :description) and (coalesce(t.parentmatgroupid,'') like :parentmatgroup)) and t.recordstatus=1",
					array(':materialgroupcode'=>'%'.$materialgroupcode.'%',
						':description'=>'%'.$description.'%',
						':parentmatgroup'=>'%'.$parentmatgroup.'%',
						':materialgroupid'=>'%'.$materialgroupid.'%'
				))
			->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.materialtypecode,a.description as materialtypedesc,(select description from materialgroup z where z.materialgroupid = t.parentmatgroupid) as parentmatdesc')	
				->from('materialgroup t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->where("((coalesce(t.materialgroupid,'') like :materialgroupid) and (coalesce(t.materialgroupcode,'') like :materialgroupcode) and (coalesce(t.description,'') like :description) and (coalesce(t.parentmatgroupid,'') like :parentmatgroup))",
					array(':materialgroupcode'=>'%'.$materialgroupcode.'%',
						':description'=>'%'.$description.'%',
						':parentmatgroup'=>'%'.$parentmatgroup.'%',
						':materialgroupid'=>'%'.$materialgroupid.'%'
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.materialtypecode,a.description as materialtypedesc,(select description from materialgroup z where z.materialgroupid = t.parentmatgroupid) as parentmatdesc')	
				->from('materialgroup t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->where("((coalesce(t.materialgroupid,'') like :materialgroupid) and (coalesce(t.materialgroupcode,'') like :materialgroupcode) and (coalesce(t.description,'') like :description) and (coalesce(t.parentmatgroupid,'') like :parentmatgroup)) and t.recordstatus=1",
					array(':materialgroupcode'=>'%'.$materialgroupcode.'%',
						':description'=>'%'.$description.'%',
						':parentmatgroup'=>'%'.$parentmatgroup.'%',
						':materialgroupid'=>'%'.$materialgroupid.'%'
				))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'materialgroupid'=>$data['materialgroupid'],
				'materialgroupcode'=>$data['materialgroupcode'],
				'description'=>$data['description'],
				'parentmatgroupid'=>$data['parentmatgroupid'],
				'parentmatgroupdesc'=>$data['parentmatdesc'],
				'isfg'=>$data['isfg'],
				'materialtypeid'=>$data['materialtypeid'],
				'materialtypecode'=>$data['materialtypecode'],
				'materialtypedesc'=>$data['materialtypedesc'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrx() {
		header("Content-Type: application/json");
		$materialgroupid = isset ($_POST['materialgroupid']) ? $_POST['materialgroupid'] : '';
		$materialgroupcode = isset ($_POST['materialgroupcode']) ? $_POST['materialgroupcode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$parentmatgroupid = isset ($_POST['parentmatgroupid']) ? $_POST['parentmatgroupid'] : '';
		$isfg = isset ($_POST['isfg']) ? $_POST['isfg'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$materialgroupid = isset ($_GET['q']) ? $_GET['q'] : $materialgroupid;
		$materialgroupcode = isset ($_GET['q']) ? $_GET['q'] : $materialgroupcode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$parentmatgroupid = isset ($_GET['q']) ? $_GET['q'] : $parentmatgroupid;
		$isfg = isset ($_GET['q']) ? $_GET['q'] : $isfg;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'materialgroupid';
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
			->from('materialgroup t')
			->where('((materialgroupcode like :materialgroupcode) or (t.description like :description) or (parentmatgroupid like :parentmatgroupid)) and t.isfg = 0 and t.recordstatus = 1',
				array(':materialgroupcode'=>'%'.$materialgroupcode.'%',
					':description'=>'%'.$description.'%',
					':parentmatgroupid'=>'%'.$parentmatgroupid.'%'
			))
		->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,(select description from materialgroup z where z.materialgroupid = t.parentmatgroupid) as parentmatdesc')	
			->from('materialgroup t')
			->where('((materialgroupcode like :materialgroupcode) or (t.description like :description) or (parentmatgroupid like :parentmatgroupid)) and t.isfg = 0 and t.recordstatus = 1',
				array(':materialgroupcode'=>'%'.$materialgroupcode.'%',
					':description'=>'%'.$description.'%',
					':parentmatgroupid'=>'%'.$parentmatgroupid.'%'
			))
		->offset($offset)
		->limit($rows)
		->order($sort.' '.$order)
		->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'materialgroupid'=>$data['materialgroupid'],
				'materialgroupcode'=>$data['materialgroupcode'],
				'description'=>$data['description'],
				'parentmatgroupid'=>$data['parentmatgroupid'],
				'parentmatgroupdesc'=>$data['parentmatdesc'],
				'isfg'=>$data['isfg'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchfg() {
		header("Content-Type: application/json");
		$materialgroupid = isset ($_POST['materialgroupid']) ? $_POST['materialgroupid'] : '';
		$materialgroupcode = isset ($_POST['materialgroupcode']) ? $_POST['materialgroupcode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$parentmatgroupid = isset ($_POST['parentmatgroupid']) ? $_POST['parentmatgroupid'] : '';
		$isfg = isset ($_POST['isfg']) ? $_POST['isfg'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$materialgroupid = isset ($_GET['q']) ? $_GET['q'] : $materialgroupid;
		$materialgroupcode = isset ($_GET['q']) ? $_GET['q'] : $materialgroupcode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$parentmatgroupid = isset ($_GET['q']) ? $_GET['q'] : $parentmatgroupid;
		$isfg = isset ($_GET['q']) ? $_GET['q'] : $isfg;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'materialgroupid';
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
			->from('materialgroup t')
			->where('((materialgroupcode like :materialgroupcode) or (t.description like :description) or (parentmatgroupid like :parentmatgroupid)) and t.isfg = 1 and t.recordstatus = 1',
				array(':materialgroupcode'=>'%'.$materialgroupcode.'%',
					':description'=>'%'.$description.'%',
					':parentmatgroupid'=>'%'.$parentmatgroupid.'%'
			))
		->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,(select description from materialgroup z where z.materialgroupid = t.parentmatgroupid) as parentmatdesc')	
			->from('materialgroup t')
			->where('((materialgroupcode like :materialgroupcode) or (t.description like :description) or (parentmatgroupid like :parentmatgroupid)) and t.isfg = 1 and t.recordstatus = 1',
				array(':materialgroupcode'=>'%'.$materialgroupcode.'%',
					':description'=>'%'.$description.'%',
					':parentmatgroupid'=>'%'.$parentmatgroupid.'%'
			))
		->offset($offset)
		->limit($rows)
		->order($sort.' '.$order)
		->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'materialgroupid'=>$data['materialgroupid'],
				'materialgroupcode'=>$data['materialgroupcode'],
				'description'=>$data['description'],
				'parentmatgroupid'=>$data['parentmatgroupid'],
				'parentmatgroupdesc'=>$data['parentmatdesc'],
				'isfg'=>$data['isfg'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmaterialgroup(:vmaterialgroupcode,:vparentmatgroupid,:vdescription,:visfg,:vmaterialtypeid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatematerialgroup(:vid,:vmaterialgroupcode,:vparentmatgroupid,:vdescription,:visfg,:vmaterialtypeid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmaterialgroupcode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vparentmatgroupid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visfg',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialtypeid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-materialgroup"]["name"]);
		if (move_uploaded_file($_FILES["file-materialgroup"]["tmp_name"], $target_file)) {
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
					$materialgroupcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$parentmatgroup = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select materialgroupid from materialgroup where materialgroupcode = '".$parentmatgroup."'")->queryScalar();
					$isfg = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$materialtypedesc = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where description = '".$materialtypedesc."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$materialgroupcode,$description,$parentid,$isfg,$materialtypeid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['materialgroupid'])?$_POST['materialgroupid']:''),$_POST['materialgroupcode'],$_POST['description'],$_POST['parentmatgroupid'],$_POST['isfg'],$_POST['materialtypeid'],$_POST['recordstatus']));
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
				$sql = 'call Purgematerialgroup(:vid,:vcreatedby)';
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
	  $sql = "select a.materialgroupid,a.materialgroupcode,a.description,
						ifnull((select z.description from materialgroup z where z.materialgroupid = a.parentmatgroupid),'-')as parentmatgroup,
						case when a.isfg = 1 then 'Yes' else 'No' end as isfg,
						b.description as materialtypedesc,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from materialgroup a
						left join materialtype b on b.materialtypeid = a.materialtypeid ";
		$materialgroupid = filter_input(INPUT_GET,'materialgroupid');
		$materialgroupcode = filter_input(INPUT_GET,'materialgroupcode');
		$description = filter_input(INPUT_GET,'description');
		$materialtypedesc = filter_input(INPUT_GET,'materialtypedesc');
		$sql .= " where coalesce(a.materialgroupid,'') like '%".$materialgroupid."%' 
			and coalesce(a.materialgroupcode,'') like '%".$materialgroupcode."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(b.description,'') like '%".$materialtypedesc."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.materialgroupid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by materialgroupcode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('materialgroup');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('materialgroupid'),
																	GetCatalog('materialgroupcode'),
																	GetCatalog('description'),
																	GetCatalog('parentmatgroup'),
																	GetCatalog('isfg'),
																	GetCatalog('materialtypedesc'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,55,95,95,15,30,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['materialgroupid'],$row1['materialgroupcode'],$row1['description'],$row1['parentmatgroup'],$row1['isfg'],$row1['materialtypedesc'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='materialgroup';
		parent::actionDownxls();
		$sql = "select a.materialgroupid,a.materialgroupcode,a.description,
						ifnull((select z.description from materialgroup z where z.materialgroupid = a.parentmatgroupid),'-')as parentmatgroup,
						case when a.isfg = 1 then 'Yes' else 'No' end as isfg,
						b.description as materialtypedesc,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from materialgroup a
						left join materialtype b on b.materialtypeid = a.materialtypeid ";
		$materialgroupid = filter_input(INPUT_GET,'materialgroupid');
		$materialgroupcode = filter_input(INPUT_GET,'materialgroupcode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.materialgroupid,'') like '%".$materialgroupid."%' 
			and coalesce(a.materialgroupcode,'') like '%".$materialgroupcode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.materialgroupid in (".$_GET['id'].")";
		}
			$sql = $sql . " order by materialgroupcode asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('materialgroupid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('materialgroupcode'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('parentmatgroup'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('isfg'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('materialtypedesc'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['materialgroupid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['materialgroupcode'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['parentmatgroup'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['isfg'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['materialtypedesc'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}