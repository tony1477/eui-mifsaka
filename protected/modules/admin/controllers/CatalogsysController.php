<?php
class CatalogsysController extends Controller {
	public $menuname = 'catalogsys';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$catalogsysid = isset ($_POST['catalogsysid']) ? $_POST['catalogsysid'] : '';
		$languagename = isset ($_POST['languagename']) ? $_POST['languagename'] : '';
		$catalogname = isset ($_POST['catalogname']) ? $_POST['catalogname'] : '';
		$catalogval = isset ($_POST['catalogval']) ? $_POST['catalogval'] : '';
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'catalogsysid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('catalogsys t')
			->leftjoin('language p', 't.languageid=p.languageid')
			->where('((t.catalogsysid like :catalogsysid) and (p.languagename like :languagename) and (catalogname like :catalogname) and (catalogval like :catalogval))',
					array(':catalogsysid'=>'%'.$catalogsysid.'%',':languagename'=>'%'.$languagename.'%',':catalogname'=>'%'.$catalogname.'%',':catalogval'=>'%'.$catalogval.'%'))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,p.languagename')	
			->from('catalogsys t')
			->leftjoin('language p', 't.languageid=p.languageid')
			->where('((t.catalogsysid like :catalogsysid) and (p.languagename like :languagename) and (catalogname like :catalogname) and (catalogval like :catalogval))',
					array(':catalogsysid'=>'%'.$catalogsysid.'%',':languagename'=>'%'.$languagename.'%',':catalogname'=>'%'.$catalogname.'%',':catalogval'=>'%'.$catalogval.'%'))
			->offset($offset)
					->limit($rows)
					->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'catalogsysid'=>$data['catalogsysid'],
				'languageid'=>$data['languageid'],
				'languagename'=>$data['languagename'],
				'catalogname'=>$data['catalogname'],
				'catalogval'=>$data['catalogval'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcatalogsys(:vlanguageid,:vcatalogname,:vcatalogval,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecatalogsys(:vid,:vlanguageid,:vcatalogname,:vcatalogval,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vlanguageid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcatalogname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcatalogval',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-catalogsys"]["name"]);
		if (move_uploaded_file($_FILES["file-catalogsys"]["tmp_name"], $target_file)) {
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
					$languagename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$languageid = Yii::app()->db->createCommand("select languageid from language where languagename = '".$languagename."'")->queryScalar();
					$catalogname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$catalogval = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$this->ModifyData($connection,array($id,$languageid,$catalogname,$catalogval));
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
			$this->ModifyData($connection,array((isset($_POST['catalogsysid'])?$_POST['catalogsysid']:''),$_POST['languageid'],$_POST['catalogname'],$_POST['catalogval']));
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
				$sql = 'call Purgecatalogsys(:vid,:vcreatedby)';
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
	  $sql = "select a.catalogsysid,b.languagename,a.catalogname,a.catalogval
						from catalogsys a
						left join language b on b.languageid = a.languageid ";
		$catalogsysid = filter_input(INPUT_GET,'catalogsysid');
		$languagename = filter_input(INPUT_GET,'languagename');
		$catalogname = filter_input(INPUT_GET,'catalogname');
		$catalogval = filter_input(INPUT_GET,'catalogval');
		$sql .= " where coalesce(a.catalogsysid,'') like '%".$catalogsysid."%' 
			and coalesce(b.languagename,'') like '%".$languagename."%'
			and coalesce(a.catalogname,'') like '%".$catalogname."%'
			and coalesce(a.catalogval,'') like '%".$catalogval."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.catalogsysid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by catalogname asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('catalogsys');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('catalogsysid'),
																	GetCatalog('languagename'),
																	GetCatalog('catalogname'),
																	GetCatalog('catalogval'));
		$this->pdf->setwidths(array(15,30,60,230));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['catalogsysid'],$row1['languagename'],$row1['catalogname'],$row1['catalogval']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='catalogsys';
		parent::actionDownxls();
		$sql = "select a.catalogsysid,b.languagename,a.catalogname,a.catalogval
						from catalogsys a
						left join language b on b.languageid = a.languageid ";
		$catalogsysid = filter_input(INPUT_GET,'catalogsysid');
		$languagename = filter_input(INPUT_GET,'languagename');
		$catalogname = filter_input(INPUT_GET,'catalogname');
		$catalogval = filter_input(INPUT_GET,'catalogval');
		$sql .= " where coalesce(a.catalogsysid,'') like '%".$catalogsysid."%' 
			and coalesce(b.languagename,'') like '%".$languagename."%'
			and coalesce(a.catalogname,'') like '%".$catalogname."%'
			and coalesce(a.catalogval,'') like '%".$catalogval."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.catalogsysid in (".$_GET['id'].")";
		}
		$sql = $sql . "order by catalogname asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('catalogsysid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('languagename'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('catalogname'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('catalogval'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['catalogsysid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['languagename'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['catalogname'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['catalogval']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}		
}