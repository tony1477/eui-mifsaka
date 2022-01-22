<?php
class SlocController extends Controller {
	public $menuname = 'sloc';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndexcombo() {
		if(isset($_GET['grid']))
			echo $this->searchcombo();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrx() {
		if(isset($_GET['grid']))
			echo $this->searchtrx();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrxda() {
		if(isset($_GET['grid']))
			echo $this->searchtrxda();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrxso() {
		if(isset($_GET['grid']))
			echo $this->searchtrxso();
		else
		$this->renderPartial('index',array());
	}
	public function actionIndextrxsloc() {
		if(isset($_GET['grid']))
			echo $this->searchtrxsloc();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrxcom(){
		if(isset($_GET['grid']))
			echo $this->searchtrxcom();
		else
			$this->renderPartial('index',array());
	}
	public function actionIndextrxgr() {
		if(isset($_GET['grid']))
			echo $this->searchtrxgr();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$slocid = isset ($_POST['slocid']) ? $_POST['slocid'] : '';
		$plantcode = isset ($_POST['plantcode']) ? $_POST['plantcode'] : '';
		$sloccode = isset ($_POST['sloccode']) ? $_POST['sloccode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.slocid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant a on a.plantid = t.plantid 
			left join company b on b.companyid = a.companyid';
		$where = "
			where (coalesce(a.plantcode,'') like '%".$plantcode."%') and (coalesce(sloccode,'') like '%".$sloccode."%') 
			and (coalesce(t.description,'') like '%".$description."%')";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = ' select t.slocid,t.plantid,t.sloccode,t.description,a.plantcode,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
			'slocid'=>$data['slocid'],
			'plantid'=>$data['plantid'],
			'plantcode'=>$data['plantcode'],
			'sloccode'=>$data['sloccode'],
			'description'=>$data['description'],
			'recordstatus'=>$data['recordstatus'],
		);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchcombo() {
		header("Content-Type: application/json");			
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : '';
		$company = isset ($_GET['q']) ? $_GET['q'] : '';
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : '';
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.slocid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '%".$plantcode."%') or (sloccode like '%".$sloccode."%') 
			or (c.companyname like '%".$company."%') or (t.description like '%".$description."%')) and 
			t.recordstatus=1 and t.slocid in (".getUserObjectValues('sloc').") and 
			c.companyid in (".getUserObjectValues('company').")";
		$sqldep = new CDbCacheDependency('select max(slocid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus, concat(t.sloccode,"-",t.description) as slocdesc '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
				'slocdesc'=>$data['slocdesc'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrx() {
		header("Content-Type: application/json");			
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : '';
		$company = isset ($_GET['q']) ? $_GET['q'] : '';
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : '';
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.slocid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '%".$plantcode."%') or (sloccode like '%".$sloccode."%') 
			or (c.companyname like '%".$company."%') or (t.description like '%".$description."%')) and 
			t.recordstatus=1 and c.companyid in (".getUserObjectValues('company').")";
		$sqldep = new CDbCacheDependency('select max(slocid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxda() {
		header("Content-Type: application/json");			
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : '';
		$company = isset ($_GET['q']) ? $_GET['q'] : '';
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : '';
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.slocid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '%".$plantcode."%') or (sloccode like '%".$sloccode."%') 
			or (c.companyname like '%".$company."%') or (t.description like '%".$description."%')) and 
			t.recordstatus=1 and t.slocid in (".getUserObjectWfValues('sloc','appda').")
			and c.companyid in (".getUserObjectValues('company').")";
		$sqldep = new CDbCacheDependency('select max(slocid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus, concat(t.sloccode,"-",t.description) as slocdesc '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
				'slocdesc'=>$data['slocdesc'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxso() {
		header("Content-Type: application/json");			
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : '';
		$company = isset ($_GET['q']) ? $_GET['q'] : '';
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : '';
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.slocid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '%".$plantcode."%') or (sloccode like '%".$sloccode."%') 
			or (c.companyname like '%".$company."%') or (t.description like '%".$description."%')) and 
			t.recordstatus=1 and t.slocid in (".getUserObjectWfValues('sloc','appso').")
			and c.companyid in (".getUserObjectValues('company').")";
		$sqldep = new CDbCacheDependency('select max(slocid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus, concat(t.sloccode,"-",t.description) as slocdesc '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
				'slocdesc'=>$data['slocdesc'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxsloc() {
		header("Content-Type: application/json");			
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : '';
		$company = isset ($_GET['q']) ? $_GET['q'] : '';
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : '';
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.slocid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '%".$plantcode."%') or (sloccode like '%".$sloccode."%') 
			or (c.companyname like '%".$company."%') or (t.description like '%".$description."%')) and 
			t.recordstatus=1 and t.slocid in (".getUserObjectValues('sloc').")";
		$sqldep = new CDbCacheDependency('select max(slocid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxcom() {
		header("Content-Type: application/json");			
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : '';
		$company = isset ($_GET['q']) ? $_GET['q'] : '';
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : '';
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.slocid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where t.recordstatus = 1 and ((p.plantcode like '%".$plantcode."%') or (sloccode like '%".$sloccode."%') 
			or (c.companyname like '%".$company."%') or (t.description like '%".$description."%')) and 
			t.recordstatus=1 and c.companyid = '".$_GET['companyid']."' ";
		$sqldep = new CDbCacheDependency('select max(slocid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data)
		{	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function searchtrxgr() {
		header("Content-Type: application/json");			
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : '';
		$company = isset ($_GET['q']) ? $_GET['q'] : '';
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : '';
		$description = isset ($_GET['q']) ? $_GET['q'] : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.slocid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset = ($page-1) * $rows;			
		$result = array();
		$row = array();
		$connection = Yii::app()->db;
		$from = '
			from sloc t 
			left join plant p on p.plantid = t.plantid 
			left join company c on c.companyid = p.companyid';
		$where = "
			where ((p.plantcode like '%".$plantcode."%') or (sloccode like '%".$sloccode."%') 
			or (c.companyname like '%".$company."%') or (t.description like '%".$description."%')) and 
			t.recordstatus=1 and t.slocid in (".getUserObjectValues('sloc').") and 
			c.companyid in (".getUserObjectValues('company').") and
			c.companyid = (select k.companyid from poheader k where k.poheaderid = '".$_GET['poheaderid']."') ";
		$sqldep = new CDbCacheDependency('select max(slocid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.slocid,t.plantid,p.plantcode,t.sloccode,t.description,t.recordstatus, concat(t.sloccode,"-",t.description) as slocdesc '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'slocid'=>$data['slocid'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'sloccode'=>$data['sloccode'],
				'description'=>$data['description'],
				'recordstatus'=>$data['recordstatus'],
				'slocdesc'=>$data['slocdesc'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertsloc(:vplantid,:vsloccode,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updatesloc(:vid,:vplantid,:vsloccode,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vplantid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vsloccode',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-sloc"]["name"]);
		if (move_uploaded_file($_FILES["file-sloc"]["tmp_name"], $target_file)) {
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
					$plantcode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$sloccode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$plantid,$sloccode,$description,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['slocid'])?$_POST['slocid']:''),$_POST['plantid'],$_POST['sloccode'],$_POST['description'],$_POST['recordstatus']));
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
				$sql = 'call Purgesloc(:vid,:vcreatedby)';
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
		$sql = "select a.slocid,b.plantcode as plant,a.sloccode,a.description,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from sloc a 
						left join plant b on b.plantid = a.plantid ";
		$slocid = filter_input(INPUT_GET,'slocid');
		$plantcode = filter_input(INPUT_GET,'plantcode');
		$sloccode = filter_input(INPUT_GET,'sloccode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.slocid,'') like '%".$slocid."%' 
			and coalesce(b.plantcode,'') like '%".$plantcode."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(a.sloccode,'') like '%".$sloccode."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.slocid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by plantcode asc, sloccode asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('sloc');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('slocid'),
																	GetCatalog('plant'),
																	GetCatalog('sloccode'),
																	GetCatalog('description'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,50,100,145,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
			$this->pdf->row(array($row1['slocid'],$row1['plant'],$row1['sloccode'],$row1['description'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='plant';
		parent::actionDownxls();
		$sql = "select a.slocid,b.plantcode as plant,a.sloccode,a.description,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from sloc a 
						left join plant b on b.plantid = a.plantid ";
		$slocid = filter_input(INPUT_GET,'slocid');
		$plantcode = filter_input(INPUT_GET,'plantcode');
		$sloccode = filter_input(INPUT_GET,'sloccode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.slocid,'') like '%".$slocid."%' 
			and coalesce(b.plantcode,'') like '%".$plantcode."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(a.sloccode,'') like '%".$sloccode."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.slocid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by plantcode asc, sloccode asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,2,GetCatalog('slocid'))
		->setCellValueByColumnAndRow(1,2,GetCatalog('plant'))
		->setCellValueByColumnAndRow(2,2,GetCatalog('sloccode'))
		->setCellValueByColumnAndRow(3,2,GetCatalog('description'))
		->setCellValueByColumnAndRow(4,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['slocid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['plant'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['sloccode'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}
