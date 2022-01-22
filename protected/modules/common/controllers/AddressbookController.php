<?php
class AddressbookController extends Controller {
	public $menuname = 'addressbook';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$addressbookid = isset ($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
		$fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
		$iscustomer = isset ($_POST['iscustomer']) ? $_POST['iscustomer'] : '';
		$isemployee = isset ($_POST['isemployee']) ? $_POST['isemployee'] : '';
		$isvendor = isset ($_POST['isvendor']) ? $_POST['isvendor'] : '';
		$ishospital = isset ($_POST['ishospital']) ? $_POST['ishospital'] : '';
		$taxno = isset ($_POST['taxno']) ? $_POST['taxno'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$addressbookid = isset ($_GET['q']) ? $_GET['q'] : $addressbookid;
		$fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
		$iscustomer = isset ($_GET['q']) ? $_GET['q'] : $iscustomer;
		$isemployee = isset ($_GET['q']) ? $_GET['q'] : $isemployee;
		$isvendor = isset ($_GET['q']) ? $_GET['q'] : $isvendor;
		$ishospital = isset ($_GET['q']) ? $_GET['q'] : $ishospital;
		$taxno = isset ($_GET['q']) ? $_GET['q'] : $taxno;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addressbookid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addressbook t')
				->where("(coalesce(fullname,'') like :fullname) and 
					(coalesce(taxno,'') like :taxno)",
					array(':fullname'=>'%'.$fullname.'%',
							':taxno'=>'%'.$taxno.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addressbook t')
				->where("((coalesce(fullname,'') like :fullname) or 
					(coalesce(taxno,'') like :taxno)) and 
					t.recordstatus=1",
					array(':fullname'=>'%'.$fullname.'%',
							':taxno'=>'%'.$taxno.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('addressbook t')
				->where("(coalesce(fullname,'') like :fullname) and 
					(coalesce(taxno,'') like :taxno)",
					array(':fullname'=>'%'.$fullname.'%',
							':taxno'=>'%'.$taxno.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('addressbook t')
				->where("((coalesce(fullname,'') like :fullname) or 
					(coalesce(taxno,'') like :taxno)) and 
					t.recordstatus=1",
					array(':fullname'=>'%'.$fullname.'%',
							':taxno'=>'%'.$taxno.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'addressbookid'=>$data['addressbookid'],
				'fullname'=>$data['fullname'],
				'iscustomer'=>$data['iscustomer'],
				'isemployee'=>$data['isemployee'],
				'isvendor'=>$data['isvendor'],
				'ishospital'=>$data['ishospital'],
				'taxno'=>$data['taxno'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddressbook(:vfullname,:viscustomer,:visemployee,:visvendor,:vishospital,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateaddressbook(:vid,:vfullname,:viscustomer,:visemployee,:visvendor,:vishospital,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vfullname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':viscustomer',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':visemployee',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visvendor',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vishospital',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-addressbook"]["name"]);
		if (move_uploaded_file($_FILES["file-addressbook"]["tmp_name"], $target_file)) {
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
					$fullname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$iscustomer = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$isemployee = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$isvendor = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$ishospital = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$fullname,$iscustomer,$isemployee,$isvendor,$ishospital,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['addressbookid'])?$_POST['addressbookid']:''),$_POST['fullname'],$_POST['iscustomer'],$_POST['isemployee'],
				$_POST['isvendor'],$_POST['ishospital'],$_POST['recordstatus']));
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
				$sql = 'call Purgeaddressbook(:vid,:vcreatedby)';
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
		//masukkan perintah download
	  $sql = "select a.addressbookid,
						case when a.fullname is null then '-' else fullname end as fullname,
						case when a.iscustomer  = 1 then 'Yes' else 'No' end as iscustomer,
						case when a.isemployee  = 1 then 'Yes' else 'No' end as isemployee,
						case when a.isvendor  = 1 then 'Yes' else 'No' end as isvendor,
						case when a.ishospital  = 1 then 'Yes' else 'No' end as ishospital,
						case when a.taxno is null then '-' else a.taxno end as taxno,
						case when b.accountname is null then '-' else b.accountname end as accpiutang,
						case when c.accountname is null then '-' else c.accountname end as acchutang,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from addressbook a
						left join account b on b.accountid = a.accpiutangid
						left join account c on c.accountid = a.acchutangid ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$sql .= " where coalesce(a.addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(a.fullname,'') like '%".$fullname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('addressbook');
		$this->pdf->AddPage('P',array(400,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('addressbookid'),
																	GetCatalog('fullname'),
																	GetCatalog('iscustomer'),
																	GetCatalog('isemployee'),
																	GetCatalog('isvendor'),
																	GetCatalog('ishospital'),
																	GetCatalog('taxno'),
																	GetCatalog('accpiutang'),
																	GetCatalog('acchutang'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,90,20,20,17,20,40,80,60,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['addressbookid'],$row1['fullname'],$row1['iscustomer'],$row1['isemployee'],$row1['isvendor'],$row1['ishospital'],$row1['taxno'],$row1['accpiutang'],$row1['acchutang'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='addressbook';
		parent::actionDownxls();
		$sql = "select a.addressbookid,
						case when a.fullname is null then '-' else fullname end as fullname,
						case when a.iscustomer  = 1 then 'Yes' else 'No' end as iscustomer,
						case when a.isemployee  = 1 then 'Yes' else 'No' end as isemployee,
						case when a.isvendor  = 1 then 'Yes' else 'No' end as isvendor,
						case when a.ishospital  = 1 then 'Yes' else 'No' end as ishospital,
						case when a.taxno is null then '-' else a.taxno end as taxno,
						case when b.accountname is null then '-' else b.accountname end as accpiutang,
						case when c.accountname is null then '-' else c.accountname end as acchutang,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from addressbook a
						left join account b on b.accountid = a.accpiutangid
						left join account c on c.accountid = a.acchutangid ";
		$addressbookid = filter_input(INPUT_GET,'addressbookid');
		$fullname = filter_input(INPUT_GET,'fullname');
		$sql .= " where coalesce(a.addressbookid,'') like '%".$addressbookid."%' 
			and coalesce(a.fullname,'') like '%".$fullname."%'";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where a.addressbookid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by fullname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('addressbookid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('fullname'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('iscustomer'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('isemployee'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('isvendor'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('ishospital'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('taxno'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('accpiutang'))
			->setCellValueByColumnAndRow(8,2,GetCatalog('acchutang'))
			->setCellValueByColumnAndRow(9,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['addressbookid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['fullname'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['iscustomer'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['isemployee'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['isvendor'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['ishospital'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['taxno'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['accpiutang'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['acchutang'])
				->setCellValueByColumnAndRow(9, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}	
}