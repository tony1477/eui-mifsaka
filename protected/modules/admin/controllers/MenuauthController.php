<?php
class MenuauthController extends Controller {
	public $menuname = 'menuauth';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$menuauthid = isset ($_POST['menuauthid']) ? $_POST['menuauthid'] : '';
		$menuobject = isset ($_POST['menuobject']) ? $_POST['menuobject'] : '';
		$menuobject = isset ($_GET['q']) ? $_GET['q'] : $menuobject;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'menuauthid';
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
				->from('menuauth t')
				->where('(menuauthid like :menuauthid) and (menuobject like :menuobject)',
						array(':menuauthid'=>'%'.$menuauthid.'%',':menuobject'=>'%'.$menuobject.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('menuauth t')
				->where('((menuauthid like :menuauthid) or (menuobject like :menuobject)) and t.recordstatus = 1',
						array(':menuauthid'=>'%'.$menuauthid.'%',':menuobject'=>'%'.$menuobject.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('menuauth t')
				->where('(menuauthid like :menuauthid) and (menuobject like :menuobject)',
						array(':menuauthid'=>'%'.$menuauthid.'%',':menuobject'=>'%'.$menuobject.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('menuauth t')
				->where('((menuauthid like :menuauthid) or (menuobject like :menuobject)) and t.recordstatus=1',
						array(':menuauthid'=>'%'.$menuauthid.'%',':menuobject'=>'%'.$menuobject.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'menuauthid'=>$data['menuauthid'],
				'menuobject'=>$data['menuobject'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmenuauth(:vmenuobject,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatemenuauth(:vid,:vmenuobject,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmenuobject',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-menuauth"]["name"]);
		if (move_uploaded_file($_FILES["file-menuauth"]["tmp_name"], $target_file)) {
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
					$menuobject = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData(array($id,$menuobject,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['menuauthid'])?$_POST['menuauthid']:''),$_POST['menuobject'],$_POST['recordstatus']));
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
				$sql = 'call Purgemenuauth(:vid,:vcreatedby)';
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
	  $sql = "select menuauthid,menuobject,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from menuauth a ";
		$menuauthid = filter_input(INPUT_GET,'menuauthid');
		$menuobject = filter_input(INPUT_GET,'menuobject');
		$sql .= " where coalesce(a.menuauthid,'') like '%".$menuauthid."%' 
			and coalesce(a.menuobject,'') like '%".$menuobject."%'";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " and a.menuauthid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by menuobject asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('menuauth');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('menuauthid'),
																	GetCatalog('menuobject'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,160,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['menuauthid'],$row1['menuobject'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='menuauth';
		parent::actionDownxls();
		$sql = "select menuauthid,menuobject,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from menuauth a ";
		$menuauthid = filter_input(INPUT_GET,'menuauthid');
		$menuobject = filter_input(INPUT_GET,'menuobject');
		$sql .= " where coalesce(a.menuauthid,'') like '%".$menuauthid."%' 
			and coalesce(a.menuobject,'') like '%".$menuobject."%'";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " and a.menuauthid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by menuobject asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('menuauthid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('menuobject'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['menuauthid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['menuobject'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}