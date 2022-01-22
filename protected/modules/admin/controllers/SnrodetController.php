<?php
class SnrodetController extends Controller {
	public $menuname = 'snrodet';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$snrodid = isset ($_POST['snrodid']) ? $_POST['snrodid'] : '';
		$snrodesc = isset ($_POST['snrodesc']) ? $_POST['snrodesc'] : '';
		$curdd = isset ($_POST['curdd']) ? $_POST['curdd'] : '';
		$companyname = isset ($_POST['companyname']) ? $_POST['companyname'] : '';
		$curmm = isset ($_POST['curmm']) ? $_POST['curmm'] : '';
		$curyy = isset ($_POST['curyy']) ? $_POST['curyy'] : '';
		$curvalue = isset ($_POST['curvalue']) ? $_POST['curvalue'] : '';
		$snrodid = isset ($_GET['q']) ? $_GET['q'] : $snrodid;
		$snrodesc = isset ($_GET['q']) ? $_GET['q'] : $snrodesc;
		$curdd = isset ($_GET['q']) ? $_GET['q'] : $curdd;
		$companyname = isset ($_GET['q']) ? $_GET['q'] : $companyname;
		$curmm = isset ($_GET['q']) ? $_GET['q'] : $curmm;
		$curyy = isset ($_GET['q']) ? $_GET['q'] : $curyy;
		$curvalue = isset ($_GET['q']) ? $_GET['q'] : $curvalue;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'snrodid';
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
				->from('snrodet t')
				->join('snro p','p.snroid=t.snroid')
				->join('company a','a.companyid=t.companyid')
				->where('(p.description like :snrodesc) and (curdd like :curdd) and (curmm like :curmm) and (curyy like :curyy) and (curvalue like :curvalue) and (a.companyname like :companyname)',
						array(':snrodesc'=>'%'.$snrodesc.'%',':curdd'=>'%'.$curdd.'%',':curmm'=>'%'.$curmm.'%',':curyy'=>'%'.$curyy.'%',':curvalue'=>'%'.$curvalue.'%',':companyname'=>'%'.$companyname.'%'))
				->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.description,a.companyname')	
				->from('snrodet t')
        ->join('snro p','p.snroid=t.snroid')
				->join('company a','a.companyid=t.companyid')
				->where('(p.description like :snrodesc) and (curdd like :curdd) and (curmm like :curmm) and (curyy like :curyy) and (curvalue like :curvalue) and (a.companyname like :companyname)',
						array(':snrodesc'=>'%'.$snrodesc.'%',':curdd'=>'%'.$curdd.'%',':curmm'=>'%'.$curmm.'%',':curyy'=>'%'.$curyy.'%',':curvalue'=>'%'.$curvalue.'%',':companyname'=>'%'.$companyname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'snrodid'=>$data['snrodid'],
				'snroid'=>$data['snroid'],
				'description'=>$data['description'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'curdd'=>$data['curdd'],
				'curmm'=>$data['curmm'],
				'curyy'=>$data['curyy'],
				'curvalue'=>$data['curvalue'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertsnrodet(:vcompanyid,:vsnroid,:vcurdd,:vcurmm,:vcuryy,:vcurvalue,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatesnrodet(:vid,:vcompanyid,:vsnroid,:vcurdd,:vcurmm,:vcuryy,:vcurvalue,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanyid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vsnroid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcurdd',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vcurmm',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcuryy',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcurvalue',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-snrodet"]["name"]);
		if (move_uploaded_file($_FILES["file-snrodet"]["tmp_name"], $target_file)) {
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
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$snrodesc = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$snroid = Yii::app()->db->createCommand("select snroid from snro where description = '".$snrodesc."'")->queryScalar();
					$curdd = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$curmm = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$curyy = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$curvalue = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$companyid,$snroid,$curdd,$curmm,$curyy,$curvalue));
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
			$this->ModifyData($connection,array((isset($_POST['snrodid'])?$_POST['snrodid']:''),$_POST['companyid'],$_POST['snroid'],$_POST['curdd'],$_POST['curmm'],$_POST['curyy'],
				$_POST['curvalue']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionIndex();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgesnrodet(:vid,:vcreatedby)';
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
	  $sql = "select a.snrodid,b.companyname,c.description as snro,a.curdd,a.curmm,a.curyy,a.curvalue
						from snrodet a 
						join company b on b.companyid = a.companyid
						join snro c on c.snroid = a.snroid ";
		$snrodid = filter_input(INPUT_GET,'snrodid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$snrodesc = filter_input(INPUT_GET,'snrodesc');
		$sql .= " where coalesce(a.snrodid,'') like '%".$snrodid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(c.description,'') like '%".$snrodesc."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.snrodid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by companyname asc, snro asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('snrodet');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('snrodid'),
																	GetCatalog('companyname'),
																	GetCatalog('snro'),
																	GetCatalog('curdd'),
																	GetCatalog('curmm'),
																	GetCatalog('curyy'),
																	GetCatalog('curvalue'));
		$this->pdf->setwidths(array(15,100,100,30,30,30,30));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['snrodid'],$row1['companyname'],$row1['snro'],$row1['curdd'],$row1['curmm'],$row1['curyy'],$row1['curvalue']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='snrodet';
		parent::actionDownxls();
		$sql = "select a.snrodid,b.companyname,c.description as snro,a.curdd,a.curmm,a.curyy,a.curvalue
						from snrodet a 
						join company b on b.companyid = a.companyid
						join snro c on c.snroid = a.snroid ";
		$snrodid = filter_input(INPUT_GET,'snrodid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$snrodesc = filter_input(INPUT_GET,'snrodesc');
		$sql .= " where coalesce(a.snrodid,'') like '%".$snrodid."%' 
			and coalesce(b.companyname,'') like '%".$companyname."%'
			and coalesce(c.description,'') like '%".$snrodesc."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.snrodid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by companyname asc, snro asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('snrodid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('companyname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('snro'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('curdd'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('curmm'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('curyy'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('curvalue'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['snrodid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['companyname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['snro'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['curdd'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['curmm'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['curyy'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['curvalue']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}