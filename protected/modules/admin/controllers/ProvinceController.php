<?php
class ProvinceController extends Controller {
	public $menuname = 'province';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$provinceid = isset ($_POST['provinceid']) ? $_POST['provinceid'] : '';
		$countrycode = isset ($_POST['countrycode']) ? $_POST['countrycode'] : '';
		$countryname = isset ($_POST['countryname']) ? $_POST['countryname'] : '';
		$provincecode = isset ($_POST['provincecode']) ? $_POST['provincecode'] : '';
		$provincename = isset ($_POST['provincename']) ? $_POST['provincename'] : '';
		$provinceid = isset ($_GET['q']) ? $_GET['q'] : $provinceid;
		$provincecode = isset ($_GET['q']) ? $_GET['q'] : $provincecode;
		$provincename = isset ($_GET['q']) ? $_GET['q'] : $provincename;
		$countrycode = isset ($_GET['q']) ? $_GET['q'] : $countrycode;
		$countryname = isset ($_GET['q']) ? $_GET['q'] : $countryname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'provinceid';
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
				->from('province t')
				->join('country p', 't.countryid=p.countryid')
				->where('(provinceid like :provinceid) and (provincecode like :provincecode) and (provincename like :provincename) and (p.countryname like :countryname)
					and (p.countrycode like :countrycode)',
						array(':provinceid'=>'%'.$provinceid.'%',':provincecode'=>'%'.$provincecode.'%',':provincename'=>'%'.$provincename.'%',':countryname'=>'%'.$countryname.'%',
						':countrycode'=>'%'.$countrycode.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('province t')
				->join('country p', 't.countryid=p.countryid')
				->where('((provinceid like :provinceid) or (provincecode like :provincecode) or (provincename like :provincename) or (p.countryname like :countryname)
				or (p.countrycode like :countrycode)) 
				and t.recordstatus = 1',
						array(':provinceid'=>'%'.$provinceid.'%',':provincecode'=>'%'.$provincecode.'%',':provincename'=>'%'.$provincename.'%',':countryname'=>'%'.$countryname.'%',
						':countrycode'=>'%'.$countrycode.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.countryname')			
				->from('province t')
				->join('country p', 't.countryid=p.countryid')
				->where('(provinceid like :provinceid) and (provincecode like :provincecode) and (provincename like :provincename) and (p.countryname like :countryname)
				and (p.countrycode like :countrycode)',
						array(':provinceid'=>'%'.$provinceid.'%',':provincecode'=>'%'.$provincecode.'%',':provincename'=>'%'.$provincename.'%',':countryname'=>'%'.$countryname.'%',
						':countrycode'=>'%'.$countrycode.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.countryname')			
				->from('province t')
				->join('country p', 't.countryid=p.countryid')
				->where('((provinceid like :provinceid) or (provincecode like :provincecode) or (provincename like :provincename) or (p.countryname like :countryname)
				or (p.countrycode like :countrycode)) and t.recordstatus = 1',
						array(':provinceid'=>'%'.$provinceid.'%',':provincecode'=>'%'.$provincecode.'%',':provincename'=>'%'.$provincename.'%',':countryname'=>'%'.$countryname.'%',
						':countrycode'=>'%'.$countrycode.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
			'provinceid'=>$data['provinceid'],
			'countryid'=>$data['countryid'],
			'countryname'=>$data['countryname'],
			'provincecode'=>$data['provincecode'],
			'provincename'=>$data['provincename'],
			'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertprovince(:vcountryid,:vprovincecode,:vprovincename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateprovince(:vid,:vcountryid,:vprovincecode,:vprovincename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcountryid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vprovincecode',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vprovincename',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-province"]["name"]);
		if (move_uploaded_file($_FILES["file-province"]["tmp_name"], $target_file)) {
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
					$countryid = Yii::app()->db->createCommand("select countryid from country where countrycode = '".$countrycode."'")->queryScalar();
					$provincecode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$provincename = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$countryid,$provincecode,$provincename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['provinceid'])?$_POST['provinceid']:''),$_POST['countryid'],$_POST['provincecode'],$_POST['provincename'],$_POST['recordstatus']));
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
			try
			{
				$sql = 'call Purgeprovince(:vid,:vcreatedby)';
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
	  $sql = "select provinceid,countryname,provincecode,provincename,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from (select a.provinceid,b.countryname,a.provincecode,a.provincename,a.recordstatus
				from province a
				join country b on b.countryid = a.countryid) z ";
		if ($_GET['id'] !== '') {
			$sql = $sql . " where z.provinceid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by provincename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=getCatalog('province');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(getCatalog('provinceid'),
										getCatalog('countryname'),
										getCatalog('provincecode'),
										getCatalog('provincename'),
										getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10,33,30,100,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','C','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['provinceid'],$row1['countryname'],$row1['provincecode'],$row1['provincename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='province';
		parent::actionDownxls();
		$sql = "select provinceid,countryname,provincecode,provincename,
				case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from (select a.provinceid,b.countryname,a.provincecode,a.provincename,a.recordstatus
				from province a
				join country b on b.countryid = a.countryid) z ";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " where z.provinceid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by provincename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,getCatalog('provinceid'))
			->setCellValueByColumnAndRow(1,2,getCatalog('countryname'))
			->setCellValueByColumnAndRow(2,2,getCatalog('provincecode'))
			->setCellValueByColumnAndRow(3,2,getCatalog('provincename'))
			->setCellValueByColumnAndRow(4,2,getCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['provinceid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['countryname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['provincecode'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['provincename'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}