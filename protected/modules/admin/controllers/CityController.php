<?php
class CityController extends Controller {
	public $menuname = 'city';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$cityid = isset ($_POST['cityid']) ? $_POST['cityid'] : '';
		$provincename = isset ($_POST['provincename']) ? $_POST['provincename'] : '';
		$citycode = isset ($_POST['citycode']) ? $_POST['citycode'] : '';
		$cityname = isset ($_POST['cityname']) ? $_POST['cityname'] : '';
		$cityid = isset ($_GET['q']) ? $_GET['q'] : $cityid;
		$provincename = isset ($_GET['q']) ? $_GET['q'] : $provincename;
		$citycode = isset ($_GET['q']) ? $_GET['q'] : $citycode;
		$cityname = isset ($_GET['q']) ? $_GET['q'] : $cityname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'cityid';
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
				->from('city t')
				->join('province p', 't.provinceid=p.provinceid')
				->where('(cityid like :cityid) or (citycode like :citycode) or (cityname like :cityname) or (p.provincename like :provincename)',
						array(':cityid'=>'%'.$cityid.'%',':citycode'=>'%'.$citycode.'%',':cityname'=>'%'.$cityname.'%',':provincename'=>'%'.$provincename.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('city t')
				->join('province p', 't.provinceid=p.provinceid')
				->where('((cityid like :cityid) or (citycode like :citycode) or (cityname like :cityname) or (p.provincename like :provincename)) and t.recordstatus = 1',
						array(':cityid'=>'%'.$cityid.'%',':citycode'=>'%'.$citycode.'%',':cityname'=>'%'.$cityname.'%',':provincename'=>'%'.$provincename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.provincename')			
				->from('city t')
				->join('province p', 't.provinceid=p.provinceid')
				->where('(cityid like :cityid) or (citycode like :citycode) or (cityname like :cityname) or (p.provincename like :provincename)',
						array(':cityid'=>'%'.$cityid.'%',':citycode'=>'%'.$citycode.'%',':cityname'=>'%'.$cityname.'%',':provincename'=>'%'.$provincename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.provincename')			
				->from('city t')
				->join('province p', 't.provinceid=p.provinceid')
				->where('((cityid like :cityid) or (citycode like :citycode) or (cityname like :cityname) or (p.provincename like :provincename)) and t.recordstatus = 1',
						array(':cityid'=>'%'.$cityid.'%',':citycode'=>'%'.$citycode.'%',':cityname'=>'%'.$cityname.'%',':provincename'=>'%'.$provincename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'cityid'=>$data['cityid'],
				'provinceid'=>$data['provinceid'],
				'provincename'=>$data['provincename'],
				'citycode'=>$data['citycode'],
				'cityname'=>$data['cityname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcity(:vprovinceid,:vcitycode,:vcityname,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecity(:vid,:vprovinceid,:vcitycode,:vcityname,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vprovinceid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcitycode',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcityname',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-city"]["name"]);
		if (move_uploaded_file($_FILES["file-city"]["tmp_name"], $target_file)) {
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
					$provincecode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$provinceid = Yii::app()->db->createCommand("select provinceid from province where provincecode = '".$provincecode."'")->queryScalar();
					$citycode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$cityname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$provinceid,$citycode,$cityname,$recordstatus));
				}
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e)
			{
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
			$this->ModifyData($connection,array((isset($_POST['cityid'])?$_POST['cityid']:''),$_POST['provinceid'],$_POST['citycode'],$_POST['cityname'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e)
		{
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
				$sql = 'call Purgecity(:vid,:vdatauser)';
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
	protected function actionDataPrint() {
		parent::actionDataPrint();
    $this->dataprint['titleid'] = GetCatalog('cityid');
    $this->dataprint['titlecitycode'] = GetCatalog('citycode');
    $this->dataprint['titlecityname'] = GetCatalog('cityname');
    $this->dataprint['titleprovincecode'] = GetCatalog('provincecode');
    $this->dataprint['titleprovincename'] = GetCatalog('provincename');
    $this->dataprint['titlerecordstatus'] = GetCatalog('recordstatus');
    $this->dataprint['id'] = GetSearchText(array('GET'),'id');
    $this->dataprint['citycode'] = GetSearchText(array('GET'),'citycode');
    $this->dataprint['cityname'] = GetSearchText(array('GET'),'cityname');
    $this->dataprint['provincecode'] = GetSearchText(array('GET'),'provincecode');
    $this->dataprint['provincename'] = GetSearchText(array('GET'),'provincename');
  }
}