<?php
class ContacttypeController extends Controller {
	public $menuname = 'contacttype';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$contacttypeid = isset ($_POST['contacttypeid']) ? $_POST['contacttypeid'] : '';
		$contacttypename = isset ($_POST['contacttypename']) ? $_POST['contacttypename'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$contacttypeid = isset ($_GET['q']) ? $_GET['q'] : $contacttypeid;
		$contacttypename = isset ($_GET['q']) ? $_GET['q'] : $contacttypename;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'contacttypeid';
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
				->from('contacttype t')
				->where('(contacttypename like :contacttypename)',
					array(':contacttypename'=>'%'.$contacttypename.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('contacttype t')
				->where('((contacttypename like :contacttypename)) and t.recordstatus=1',
					array(':contacttypename'=>'%'.$contacttypename.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('contacttype t')
				->where('(contacttypename like :contacttypename)',
					array(':contacttypename'=>'%'.$contacttypename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('contacttype t')
				->where('((contacttypename like :contacttypename)) and t.recordstatus=1',
					array(':contacttypename'=>'%'.$contacttypename.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'contacttypeid'=>$data['contacttypeid'],
				'contacttypename'=>$data['contacttypename'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcontacttype(:vcontacttypename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecontacttype(:vid,:vcontacttypename,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcontacttypename',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-contacttype"]["name"]);
		if (move_uploaded_file($_FILES["file-contacttype"]["tmp_name"], $target_file)) {
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
					$contacttypename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$contacttypename,$recordstatus));
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
		header("Content-Type: application/json");
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['contacttypeid'])?$_POST['contacttypeid']:''),$_POST['contacttypename'],$_POST['recordstatus']));
			$transaction->commit();
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		header("Content-Type: application/json");
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecontacttype(:vid,:vcreatedby)';
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
	  $sql = "select contacttypeid,contacttypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from contacttype a ";
		$contacttypeid = filter_input(INPUT_GET,'contacttypeid');
		$contacttypename = filter_input(INPUT_GET,'contacttypename');
		$sql .= " where coalesce(a.contacttypeid,'') like '%".$contacttypeid."%' 
			and coalesce(a.contacttypename,'') like '%".$contacttypename."%'";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.contacttypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by contacttypename asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('contacttype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L');
		$this->pdf->colheader = array(GetCatalog('contacttypeid'),
																	GetCatalog('contacttypename'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['contacttypeid'],$row1['contacttypename'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='contacttype';
		parent::actionDownxls();
		$sql = "select contacttypeid,contacttypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from contacttype a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.contacttypeid in (".$_GET['id'].")";
		}
		$sql = $sql . "order by contacttypename asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('contacttypeid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('contacttypename'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['contacttypeid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['contacttypename'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}