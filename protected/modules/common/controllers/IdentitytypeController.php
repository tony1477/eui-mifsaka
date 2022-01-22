<?php
class IdentitytypeController extends Controller {
	public $menuname = 'identitytype';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$identitytypeid = isset ($_POST['identitytypeid']) ? $_POST['identitytypeid'] : '';
		$identitytypename = isset ($_POST['identitytypename']) ? $_POST['identitytypename'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$identitytypeid = isset ($_GET['q']) ? $_GET['q'] : $identitytypeid;
		$identitytypename = isset ($_GET['q']) ? $_GET['q'] : $identitytypename;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'identitytypeid';
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
				->from('identitytype t')
				->where('(identitytypename like :identitytypename)',
					array(':identitytypename'=>'%'.$identitytypename.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('identitytype t')
				->where('((identitytypename like :identitytypename)) and t.recordstatus=1',
					array(':identitytypename'=>'%'.$identitytypename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('identitytype t')
				->where('(identitytypename like :identitytypename)',
					array(':identitytypename'=>'%'.$identitytypename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('identitytype t')
				->where('((identitytypename like :identitytypename)) and t.recordstatus=1',
					array(':identitytypename'=>'%'.$identitytypename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'identitytypeid'=>$data['identitytypeid'],
				'identitytypename'=>$data['identitytypename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertidentitytype(:videntitytypename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateidentitytype(:vid,:videntitytypename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':videntitytypename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-identitytype"]["name"]);
		if (move_uploaded_file($_FILES["file-identitytype"]["tmp_name"], $target_file)) {
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
					$identitytypename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$identitytypename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['identitytypeid'])?$_POST['identitytypeid']:''),$_POST['identitytypename'],$_POST['recordstatus']));
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
		if (isset($_POST['id'])) 	{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgeidentitytype(:vid,:vcreatedby)';
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
	  $sql = "select identitytypeid,identitytypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from identitytype a ";
		$identitytypeid = filter_input(INPUT_GET,'identitytypeid');
		$identitytypename = filter_input(INPUT_GET,'identitytypename');
		$sql .= " where coalesce(a.identitytypeid,'') like '%".$identitytypeid."%' 
			and coalesce(a.identitytypename,'') like '%".$identitytypename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " where a.identitytypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by identitytypename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('identitytype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('identitytypeid'),
																	GetCatalog('identitytypename'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['identitytypeid'],$row1['identitytypename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='identitytype';
		parent::actionDownxls();
		$sql = "select identitytypeid,identitytypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from identitytype a ";
		$identitytypeid = filter_input(INPUT_GET,'identitytypeid');
		$identitytypename = filter_input(INPUT_GET,'identitytypename');
		$sql .= " where coalesce(a.identitytypeid,'') like '%".$identitytypeid."%' 
			and coalesce(a.identitytypename,'') like '%".$identitytypename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " where a.identitytypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by identitytypename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('identitytypeid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('identitytypename'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['identitytypeid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['identitytypename'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}