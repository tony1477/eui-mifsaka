<?php
class MachineController extends Controller {
	public $menuname = 'machine';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$machineid = isset ($_POST['machineid']) ? $_POST['machineid'] : '';
		$machinename = isset ($_POST['machinename']) ? $_POST['machinename'] : '';
		$machineid = isset ($_GET['q']) ? $_GET['q'] : $machineid;
		$machinename = isset ($_GET['q']) ? $_GET['q'] : $machinename;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'machineid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('machine t')
                ->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('sloc b','b.slocid = t.slocid')
				->where('((t.machineid like :machineid) or (t.machinename like :machinename))
					and t.recordstatus = 1 ',
					array(':machineid'=>'%'.$machineid.'%',':machinename'=>'%'.$machinename.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('machine t')
                ->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('sloc b','b.slocid = t.slocid')
				->where("(coalesce(t.machineid,'') like :machineid) and (coalesce(t.machinename,'') like :machinename) ",
					array(':machineid'=>'%'.$machineid.'%',':machinename'=>'%'.$machinename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, a.plantcode, b.sloccode')	
				->from('machine t')
				->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('sloc b','b.slocid = t.slocid')
				->where('((t.machineid like :machineid) or (t.machinename like :machinename) ) 
					and t.recordstatus = 1 ',
					array(':machineid'=>'%'.$machineid.'%',':machinename'=>'%'.$machinename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select("t.*, a.plantcode, b.sloccode")	
				->from('machine t')
                ->leftjoin('plant a','a.plantid = t.plantid')
				->leftjoin('sloc b','b.slocid = t.slocid')
				->where("(coalesce(t.machineid,'') like :machineid) and (coalesce(t.machinename,'') like :machinename)",
					array(':machineid'=>'%'.$machineid.'%',':machinename'=>'%'.$machinename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'machineid'=>$data['machineid'],
				'machinename'=>$data['machinename'],
				'machinecode'=>$data['machinecode'],
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'slocid'=>$data['slocid'],
				'sloccode'=>$data['sloccode'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmachine(:vplantid,:vslocid,:vmachinecode,:vmachinename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatemachine(:vid,:vplantid,:vslocid,:vmachinecode,:vmachinename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vplantid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vslocid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vmachinecode',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vmachinename',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["Filemachine"]["name"]);
		if (move_uploaded_file($_FILES["Filemachine"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$machinename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$isstock = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$machinepic = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$barcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where materialtypecode = '".$materialtypecode."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$machinename,$isstock,$machinepic,$barcode,$materialtypeid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['machineid'])?$_POST['machineid']:''),$_POST['plantid'],$_POST['slocid'],$_POST['machinecode'],$_POST['machinename'],$_POST['recordstatus']));
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
				$sql = 'call Purgemachine(:vid,:vcreatedby)';
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
		else
		{
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.*, b.plantcode, c.sloccode
			from machine a
            left join plant b on b.plantid = a.plantid
            left join sloc c on c.slocid = a.slocid
            ";
		$machineid = filter_input(INPUT_GET,'machineid');
		$machinecode = filter_input(INPUT_GET,'machinecode');
		$machinename = filter_input(INPUT_GET,'machinename');
		$sql .= " where coalesce(a.machineid,'') like '%".$machineid."%' 
			and coalesce(a.machinecode,'') like '%".$machinecode."%'
			and coalesce(a.machinename,'') like '%".$machinename."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.machineid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by machinecode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('custgrade');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('machineid'),
                                      GetCatalog('machinecode'),
                                      GetCatalog('machinename'),
                                      GetCatalog('plant'),
                                      GetCatalog('sloc'),
                                      GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,55,95,95,20,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['machineid'],$row1['machinecode'],$row1['machinename'],$row1['plantcode'],$row1['sloccode'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='machine';
		parent::actionDownxls();
		$sql = "select machineid,machinename,machinepic,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						barcode,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from machine a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.machineid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by machinename asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['machineid'])
				->setCellValueByColumnAndRow(1,$i,$row1['machinename'])							
				->setCellValueByColumnAndRow(2,$i,$row1['machinepic'])
				->setCellValueByColumnAndRow(3,$i,$row1['isstock'])
				->setCellValueByColumnAndRow(4,$i,$row1['barcode'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
}
