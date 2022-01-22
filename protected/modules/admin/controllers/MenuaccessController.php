<?php
class MenuaccessController extends Controller {
	public $menuname = 'menuaccess';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$menuname = isset ($_POST['menuname']) ? $_POST['menuname'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$menuurl = isset ($_POST['menuurl']) ? $_POST['menuurl'] : '';
		$menuicon = isset ($_POST['menuicon']) ? $_POST['menuicon'] : '';
		$parentname = isset ($_POST['parentname']) ? $_POST['parentname'] : '';
		$modulename = isset ($_POST['modulename']) ? $_POST['modulename'] : '';
		$sortorder = isset ($_POST['sortorder']) ? $_POST['sortorder'] : '';
		$menuname = isset ($_GET['q']) ? $_GET['q'] : $menuname;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$menuurl = isset ($_GET['q']) ? $_GET['q'] : $menuurl;
		$menuicon = isset ($_GET['q']) ? $_GET['q'] : $menuicon;
		$parentname = isset ($_GET['q']) ? $_GET['q'] : $parentname;
		$modulename = isset ($_GET['q']) ? $_GET['q'] : $modulename;
		$sortorder = isset ($_GET['q']) ? $_GET['q'] : $sortorder;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'menuaccessid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['parent'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where('(t.menuname like :menuname) and (t.description like :description)',
						array(':menuname'=>'%'.$menuname.'%',':description'=>'%'.$description.'%'))
				->queryScalar();
		}
		else if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where('(t.menuname like :menuname) and (t.description like :description) and (t.menuurl like :menuurl) and (t.menuicon like :menuicon) and (m.modulename like :modulename)',
						array(':menuname'=>'%'.$menuname.'%',':description'=>'%'.$description.'%',':menuurl'=>'%'.$menuurl.'%',':menuicon'=>'%'.$menuicon.'%',':modulename'=>'%'.$modulename.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('count(1) as total')	
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where('((t.menuname like :menuname) or (t.description like :description) or (t.menuurl like :menuurl) or (t.menuicon like :menuicon) or (p.menuname like :parentname) or (m.modulename like :modulename)) and t.recordstatus = 1',
						array(':menuname'=>'%'.$menuname.'%',':description'=>'%'.$description.'%',':menuurl'=>'%'.$menuurl.'%',':menuicon'=>'%'.$menuicon.'%',':parentname'=>'%'.$parentname.'%',':modulename'=>'%'.$modulename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['parent'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('t.*,p.description as parentdesc,m.modulename')						
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where('(t.menuname like :menuname) and (t.description like :description)',
						array(':menuname'=>'%'.$menuname.'%',':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('t.*,p.description as parentdesc,m.modulename')						
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where('(t.menuname like :menuname) and (t.description like :description) and (t.menuurl like :menuurl) and (t.menuicon like :menuicon) and (m.modulename like :modulename)',
						array(':menuname'=>'%'.$menuname.'%',':description'=>'%'.$description.'%',':menuurl'=>'%'.$menuurl.'%',':menuicon'=>'%'.$menuicon.'%',':modulename'=>'%'.$modulename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->selectdistinct('t.*,p.description as parentdesc,m.modulename')			
				->from('menuaccess t')
				->leftjoin('menuaccess p', 't.parentid=p.menuaccessid')
				->join('modules m', 'm.moduleid=t.moduleid')
				->where('((t.menuname like :menuname) or (t.description like :description) or (t.menuurl like :menuurl) or (t.menuicon like :menuicon) or (p.menuname like :parentname) or (m.modulename like :modulename)) and t.recordstatus = 1',
						array(':menuname'=>'%'.$menuname.'%',':description'=>'%'.$description.'%',':menuurl'=>'%'.$menuurl.'%',':menuicon'=>'%'.$menuicon.'%',':parentname'=>'%'.$parentname.'%',':modulename'=>'%'.$modulename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'menuaccessid'=>$data['menuaccessid'],
				'menuname'=>$data['menuname'],
				'description'=>$data['description'],
				'menuurl'=>$data['menuurl'],
				'menuicon'=>$data['menuicon'],
				'parentid'=>$data['parentid'],
				'parentdesc'=>$data['parentdesc'], 
				'moduleid'=>$data['moduleid'],
				'modulename'=>$data['modulename'],
				'sortorder'=>$data['sortorder'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmenuaccess(:vmenuname,:vdescription,:vmenuurl,:vmenuicon,:vparentid,:vmoduleid,:vsortorder,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatemenuaccess(:vid,:vmenuname,:vdescription,:vmenuurl,:vmenuicon,:vparentid,:vmoduleid,:vsortorder,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmenuname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmenuurl',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmenuicon',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vparentid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vmoduleid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vsortorder',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-menuaccess"]["name"]);
		if (move_uploaded_file($_FILES["file-menuaccess"]["tmp_name"], $target_file)) {
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
					$menuname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$menuurl = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$menuicon = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$parentname = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select menuaccessid from menuaccess where menuname = '".$parentname."'")->queryScalar();
					$modulename = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$moduleid = Yii::app()->db->createCommand("select moduleid from modules where modulename = '".$modulename."'")->queryScalar();
					$sortorder = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$this->ModifyData($connection,array($id,$menuname,$description,$menuurl,$menuicon,$parentid,$moduleid,$sortorder,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['menuaccessid'])?$_POST['menuaccessid']:''),$_POST['menuname'],$_POST['description'],$_POST['menuurl'],
				$_POST['menuicon'],$_POST['parentid'],$_POST['moduleid'],$_POST['sortorder'],$_POST['recordstatus']));
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
				$sql = 'call Purgemenuaccess(:vid,:vdatauser)';
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
	  $sql = "select a.menuaccessid,a.menuname,a.description,a.menuurl,a.menuicon,
						c.menuname as parentname,
						b.modulename,a.sortorder,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from menuaccess a
						join modules b on b.moduleid = a.moduleid 
						left join menuaccess c on c.menuaccessid = a.parentid ";
		$menuaccessid = filter_input(INPUT_GET,'menuaccessid');
		$menuname = filter_input(INPUT_GET,'menuname');
		$description = filter_input(INPUT_GET,'description');
		$menuurl = filter_input(INPUT_GET,'menuurl');
		$parentname = filter_input(INPUT_GET,'parentname');
		$sql .= " where coalesce(a.menuaccessid,'') like '%".$menuaccessid."%' 
			and coalesce(a.menuname,'') like '%".$menuname."%'
			and coalesce(a.description,'') like '%".$description."%'
			and coalesce(a.menuurl,'') like '%".$menuurl."%'
			and coalesce(c.menuname,'') like '%".$parentname."%'
			";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " where a.menuaccessid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by menuname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('menuaccess');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('menuaccessid'),
																	GetCatalog('menuname'),
																	GetCatalog('description'),
																	GetCatalog('menuurl'),
																	GetCatalog('menuicon'),
																	GetCatalog('parent'),
																	GetCatalog('modulename'),
																	GetCatalog('sortorder'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(10,46,65,62,60,35,25,15,15));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['menuaccessid'],$row1['menuname'],$row1['description'],$row1['menuurl'],$row1['menuicon'],$row1['parentname'],$row1['modulename'],$row1['sortorder'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='menuaccess';
		parent::actionDownxls();
		$sql = "select a.menuaccessid,a.menuname,a.description,a.menuurl,a.menuicon,
						ifnull((select z.description from menuaccess z where z.menuaccessid = a.parentid),'-') as parent,
						b.modulename,a.sortorder,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from menuaccess a
						join modules b on b.moduleid = a.moduleid ";
		if ($_GET['id'] !== '') {
			$sql = $sql . " where a.menuaccessid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by menuname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('menuaccessid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('menuname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('menuurl'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('menuicon'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('parent'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('modulename'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('sortorder'))
			->setCellValueByColumnAndRow(8,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['menuaccessid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['menuname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['menuurl'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['menuicon'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['parent'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['modulename'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['sortorder'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}