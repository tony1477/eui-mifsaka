<?php
class GroupmenuController extends Controller {
	public $menuname = 'groupmenu';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$groupmenuid = isset ($_POST['groupmenuid']) ? $_POST['groupmenuid'] : '';
		$groupname = isset ($_POST['groupname']) ? $_POST['groupname'] : '';
		$menuname = isset ($_POST['menuname']) ? $_POST['menuname'] : '';
		$menudesc = isset ($_POST['menudesc']) ? $_POST['menudesc'] : '';
		$groupmenuid = isset ($_GET['q']) ? $_GET['q'] : $groupmenuid;
		$groupname = isset ($_GET['q']) ? $_GET['q'] : $groupname;
		$menuname = isset ($_GET['q']) ? $_GET['q'] : $menuname;
		$menudesc = isset ($_GET['q']) ? $_GET['q'] : $menudesc;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'groupmenuid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('groupmenu t')
			->join('groupaccess p','t.groupaccessid=p.groupaccessid')
			->join('menuaccess q','t.menuaccessid=q.menuaccessid')
			->where('(p.groupname like :groupmenuid) and (p.groupname like :groupname) and (q.menuname like :menuname) and (q.description like :menudesc)',
					array(':groupmenuid'=>'%'.$groupmenuid.'%',':groupname'=>'%'.$groupname.'%',':menuname'=>'%'.$menuname.'%',':menudesc'=>'%'.$menudesc.'%'))			
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('groupmenu t')
			->join('groupaccess p','t.groupaccessid=p.groupaccessid')
			->join('menuaccess q','t.menuaccessid=q.menuaccessid')
			->where('(p.groupname like :groupmenuid) and (p.groupname like :groupname) and (q.menuname like :menuname) and (q.description like :menudesc)',
					array(':groupmenuid'=>'%'.$groupmenuid.'%',':groupname'=>'%'.$groupname.'%',':menuname'=>'%'.$menuname.'%',':menudesc'=>'%'.$menudesc.'%'))			
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();		
		foreach($cmd as $data) {	
			$row[] = array(
				'groupmenuid'=>$data['groupmenuid'],
				'groupaccessid'=>$data['groupaccessid'],
				'groupname'=>$data['groupname'],
				'menuaccessid'=>$data['menuaccessid'],
				'description'=>$data['description'],
				'isread'=>$data['isread'],
				'iswrite'=>$data['iswrite'],
				'ispost'=>$data['ispost'],
				'isreject'=>$data['isreject'],
				'isupload'=>$data['isupload'],
				'isdownload'=>$data['isdownload'],
				'ispurge'=>$data['ispurge'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {	
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertgroupmenu(:vmenuaccessid,:vgroupaccessid,:visread,:viswrite,:vispost,:visreject,:visupload,:visdownload,:vispurge,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updategroupmenu(:vid,:vmenuaccessid,:vgroupaccessid,:visread,:viswrite,:vispost,:visreject,:visupload,:visdownload,:vispurge,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vgroupaccessid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmenuaccessid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':visread',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':viswrite',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vispost',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':visreject',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':visupload',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':visdownload',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vispurge',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-groupmenu"]["name"]);
		if (move_uploaded_file($_FILES["file-groupmenu"]["tmp_name"], $target_file)) {
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
					$groupname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$groupid = Yii::app()->db->createCommand("select groupaccessid from groupaccess where groupname = '".$groupname."'")->queryScalar();
					$menuname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$menuid = Yii::app()->db->createCommand("select menuaccessid from menuaccess where description = '".$menuname."'")->queryScalar();
					$isread = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$iswrite = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$ispost = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$isreject = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$isupload = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$isdownload = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$ispurge = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$this->ModifyData(array($id,$groupid,$menuid,$isread,$iswrite,$ispost,$isreject,$isupload,$isdownload,$ispurge));
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
			$this->ModifyData($connection,array((isset($_POST['groupmenuid'])?$_POST['groupmenuid']:''),$_POST['groupaccessid'],$_POST['menuaccessid'],$_POST['isread'],$_POST['iswrite'],$_POST['ispost'],
				$_POST['isreject'],$_POST['isupload'],$_POST['isdownload'],$_POST['ispurge']));
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
		header("Content-Type: application/json");
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgegroupmenu(:vid,:vcreatedby)';
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
	  $sql = "select a.groupmenuid,b.groupname,c.description,
						case when isread = 1 then 'Yes' else 'No' end as isread,
						case when iswrite = 1 then 'Yes' else 'No' end as iswrite,
						case when ispost = 1 then 'Yes' else 'No' end as ispost,
						case when isreject = 1 then 'Yes' else 'No' end as isreject,
						case when isupload = 1 then 'Yes' else 'No' end as isupload,
						case when isdownload = 1 then 'Yes' else 'No' end as isdownload,
						case when ispurge = 1 then 'Yes' else 'No' end as ispurge
						from groupmenu a
						left join groupaccess b on b.groupaccessid = a.groupaccessid
						left join menuaccess c on c.menuaccessid = a.menuaccessid ";
		$groupmenuid = filter_input(INPUT_GET,'groupmenuid');
		$groupname = filter_input(INPUT_GET,'groupname');
		$menuname = filter_input(INPUT_GET,'menuname');
		$menudesc = filter_input(INPUT_GET,'menudesc');
		$sql .= " where coalesce(a.groupmenuid,'') like '%".$groupmenuid."%' 
			and coalesce(b.groupname,'') like '%".$groupname."%'
			and coalesce(c.menuname,'') like '%".$menuname."%'
			and coalesce(c.description,'') like '%".$menudesc."%'
			";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " and a.groupmenuid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by groupname asc,description asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('groupmenu');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('groupmenuid'),
																	GetCatalog('groupname'),
																	GetCatalog('description'),
																	GetCatalog('isread'),
																	GetCatalog('iswrite'),
																	GetCatalog('ispost'),
																	GetCatalog('isreject'),
																	GetCatalog('isupload'),
																	GetCatalog('isdownload'),
																	GetCatalog('ispurge'));
		$this->pdf->setwidths(array(20,60,115,20,20,20,20,20,20,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['groupmenuid'],$row1['groupname'],$row1['description'],$row1['isread'],$row1['iswrite'],$row1['ispost'],$row1['isreject'],$row1['isupload'],$row1['isdownload'],$row1['ispurge']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='groupmenu';
		parent::actionDownxls();
		$sql = "select a.groupmenuid,b.groupname,c.description,
						case when isread = 1 then 'Yes' else 'No' end as isread,
						case when iswrite = 1 then 'Yes' else 'No' end as iswrite,
						case when ispost = 1 then 'Yes' else 'No' end as ispost,
						case when isreject = 1 then 'Yes' else 'No' end as isreject,
						case when isupload = 1 then 'Yes' else 'No' end as isupload,
						case when isdownload = 1 then 'Yes' else 'No' end as isdownload,
						case when ispurge = 1 then 'Yes' else 'No' end as ispurge
						from groupmenu a
						left join groupaccess b on b.groupaccessid = a.groupaccessid
						left join menuaccess c on c.menuaccessid = a.menuaccessid ";
		$groupmenuid = filter_input(INPUT_GET,'groupmenuid');
		$groupname = filter_input(INPUT_GET,'groupname');
		$menuname = filter_input(INPUT_GET,'menuname');
		$menudesc = filter_input(INPUT_GET,'menudesc');
		$sql .= " where coalesce(a.groupmenuid,'') like '%".$groupmenuid."%' 
			and coalesce(b.groupname,'') like '%".$groupname."%'
			and coalesce(c.menuname,'') like '%".$menuname."%'
			and coalesce(c.description,'') like '%".$menudesc."%'
			";
		if ($_GET['id'] !== '') 
		{
			$sql = $sql . " where a.groupmenuid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by groupname asc,description asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('groupmenuid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('groupname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('isread'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('iswrite'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('ispost'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('isreject'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('isupload'))
			->setCellValueByColumnAndRow(8,2,GetCatalog('isdownload'))
			->setCellValueByColumnAndRow(9,2,GetCatalog('ispurge'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['groupmenuid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['groupname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['isread'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['iswrite'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['ispost'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['isreject'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['isupload'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['isdownload'])
				->setCellValueByColumnAndRow(9, $i+1, $row1['ispurge']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}