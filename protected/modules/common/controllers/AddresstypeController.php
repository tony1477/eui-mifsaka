<?php
class AddresstypeController extends Controller {
	public $menuname = 'addresstype';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$addresstypeid = isset ($_POST['addresstypeid']) ? $_POST['addresstypeid'] : '';
		$addresstypename = isset ($_POST['addresstypename']) ? $_POST['addresstypename'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'addresstypeid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page = isset($_POST['page']) ? intval($_POST['page']) : $page;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : $rows;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : $sort;
		$order = isset($_POST['order']) ? strval($_POST['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addresstype t')
				->where('(addresstypename like :addresstypename)',
					array(':addresstypename'=>'%'.$addresstypename.'%'))
				->queryScalar();
		}
		else  {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addresstype t')
				->where('((addresstypename like :addresstypename)) and t.recordstatus=1',
					array(':addresstypename'=>'%'.$addresstypename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('addresstype t')
				->where('(addresstypename like :addresstypename)',
					array(':addresstypename'=>'%'.$addresstypename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('addresstype t')
				->where('((addresstypename like :addresstypename)) and t.recordstatus=1',
					array(':addresstypename'=>'%'.$addresstypename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'addresstypeid'=>$data['addresstypeid'],
				'addresstypename'=>$data['addresstypename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddresstype(:vaddresstypename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateaddresstype(:vid,:vaddresstypename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vaddresstypename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-addresstype"]["name"]);
		if (move_uploaded_file($_FILES["file-addresstype"]["tmp_name"], $target_file)) {
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
					$addresstypename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$addresstypename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['addresstypeid'])?$_POST['addresstypeid']:''),$_POST['addresstypename'],$_POST['recordstatus']));
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
				$sql = 'call Purgeaddresstype(:vid,:vcreatedby)';
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
	  $sql = "select addresstypeid,addresstypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from addresstype a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . " where a.addresstypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by addresstypename asc";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('addresstype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('addresstypeid'),
																	GetCatalog('addresstypename'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['addresstypeid'],$row1['addresstypename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='addresstype';
		parent::actionDownxls();
		$sql = "select addresstypeid,addresstypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from addresstype a ";
		$addresstypeid = filter_input(INPUT_GET,'addresstypeid');
		$addresstypename = filter_input(INPUT_GET,'addresstypename');
		$sql .= " where coalesce(a.addresstypeid,'') like '%".$addresstypeid."%' 
			and coalesce(a.addresstypename,'') like '%".$addresstypename."%'";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.addresstypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by addresstypename asc";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('addresstypeid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('addresstypename'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));			
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['addresstypeid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['addresstypename'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}