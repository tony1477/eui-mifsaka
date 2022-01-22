<?php
class AddressaccountController extends Controller {
	public $menuname = 'addressaccount';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$addressaccountid = isset ($_POST['addressaccountid']) ? $_POST['addressaccountid'] : '';
		$fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
		$companyname = isset ($_POST['companyname']) ? $_POST['companyname'] : '';
		$accpiutang = isset ($_POST['accpiutang']) ? $_POST['accpiutang'] : '';
		$acchutang = isset ($_POST['acchutang']) ? $_POST['acchutang'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$addressaccountid = isset ($_GET['q']) ? $_GET['q'] : $addressaccountid;
		$fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
		$companyname = isset ($_GET['q']) ? $_GET['q'] : $companyname;
		$accpiutang = isset ($_GET['q']) ? $_GET['q'] : $accpiutang;
		$acchutang = isset ($_GET['q']) ? $_GET['q'] : $acchutang;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.addressaccountid';
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
				->from('addressaccount t')
				->leftjoin('addressbook r','r.addressbookid=t.addressbookid')
				->leftjoin('company s','s.companyid=t.companyid')
				->leftjoin('account p','p.accountid=t.accpiutangid')
				->leftjoin('account q','q.accountid=t.acchutangid')
				->where('((r.fullname like :fullname) or 
					(s.companyname like :companyname) or 
					(p.accountname like :accpiutang) or 
					(q.accountname like :acchutang)) and 
					t.recordstatus=1',
						array(':fullname'=>'%'.$fullname.'%',
								':companyname'=>'%'.$companyname.'%',
								':accpiutang'=>'%'.$accpiutang.'%',
								':acchutang'=>'%'.$acchutang.'%'
								))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('addressaccount t')
				->leftjoin('addressbook r','r.addressbookid=t.addressbookid')
				->leftjoin('company s','s.companyid=t.companyid')
				->leftjoin('account p','p.accountid=t.accpiutangid')
				->leftjoin('account q','q.accountid=t.acchutangid')
				->where("((coalesce(r.fullname,'') like :fullname) and 
					(coalesce(s.companyname,'') like :companyname) and 
					(coalesce(p.accountname,'') like :accpiutang) and 
					(coalesce(q.accountname,'') like :acchutang))",
					array(':fullname'=>'%'.$fullname.'%',
						':companyname'=>'%'.$companyname.'%',
							':accpiutang'=>'%'.$accpiutang.'%',
							':acchutang'=>'%'.$acchutang.'%'
							))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,r.fullname,s.companyname,p.accountname as accpiutang,q.accountname as acchutang')	
				->from('addressaccount t')
				->leftjoin('addressbook r','r.addressbookid=t.addressbookid')
				->leftjoin('company s','s.companyid=t.companyid')
				->leftjoin('account p','p.accountid=t.accpiutangid')
				->leftjoin('account q','q.accountid=t.acchutangid')
				->where('((r.fullname like :fullname) or 
					(s.companyname like :companyname) or 
					(p.accountname like :accpiutang) or 
					(q.accountname like :acchutang)) and 
					t.recordstatus=1',
					array(':fullname'=>'%'.$fullname.'%',
							':companyname'=>'%'.$companyname.'%',
							':accpiutang'=>'%'.$accpiutang.'%',
							':acchutang'=>'%'.$acchutang.'%'
							))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,r.fullname,s.companyname,p.accountname as accpiutang,q.accountname as acchutang')
				->from('addressaccount t')
				->leftjoin('addressbook r','r.addressbookid=t.addressbookid')
				->leftjoin('company s','s.companyid=t.companyid')
				->leftjoin('account p','p.accountid=t.accpiutangid')
				->leftjoin('account q','q.accountid=t.acchutangid')
				->where("(coalesce(r.fullname,'') like :fullname) and 
					(coalesce(s.companyname,'') like :companyname) and 
					(coalesce(p.accountname,'') like :accpiutang) and 
					(coalesce(q.accountname,'') like :acchutang)",
					array(':fullname'=>'%'.$fullname.'%',
							':companyname'=>'%'.$companyname.'%',
							':accpiutang'=>'%'.$accpiutang.'%',
							':acchutang'=>'%'.$acchutang.'%'
							))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'addressaccountid'=>$data['addressaccountid'],
				'addressbookid'=>$data['addressbookid'],
				'fullname'=>$data['fullname'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'accpiutangid'=>$data['accpiutangid'],
				'accpiutang'=>$data['accpiutang'],
				'acchutangid'=>$data['acchutangid'],
				'acchutang'=>$data['acchutang'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertaddressaccount(:vaddressbookid,:vcompanyid,:vaccpiutangid,:vacchutangid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else
		{
			$sql = 'call Updateaddressaccount(:vid,:vaddressbookid,:vcompanyid,:vaccpiutangid,:vacchutangid,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vaddressbookid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcompanyid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaccpiutangid',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vacchutangid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-addressaccount"]["name"]);
		if (move_uploaded_file($_FILES["file-addressaccount"]["tmp_name"], $target_file)) {
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
					$addressbookname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$addressbookid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '".$addressbookname."'")->queryScalar();
					$accpiutangcode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$accpiutangid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$accpiutangcode."'")->queryScalar();
					$acchutangcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$acchutangid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$acchutangcode."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$addressbookid,$companyid,$accpiutangid,$acchutangid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['addressaccountid'])?$_POST['addressaccountid']:''),$_POST['addressbookid'],$_POST['companyid'],$_POST['accpiutangid'],$_POST['acchutangid'],$_POST['recordstatus']));
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
				$sql = 'call Purgeaddressaccount(:vid,:vcreatedby)';
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
	  $sql = "select a.addressaccountid,e.companyname,b.fullname,c.accountname as accpiutangname,d.accountname as acchutangname
				from addressaccount a 
				left join addressbook b on b.addressbookid = a.addressbookid 
				left join account c on c.accountid = a.accpiutangid 
				left join account d on d.accountid = a.acchutangid 
				left join company e on e.companyid = a.companyid ";
		$addressaccountid = filter_input(INPUT_GET,'addressaccountid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$fullname = filter_input(INPUT_GET,'fullname');
		$accpiutang = filter_input(INPUT_GET,'accpiutang');
		$acchutang = filter_input(INPUT_GET,'acchutang');
		$sql .= " where coalesce(a.addressaccountid,'') like '%".$addressaccountid."%' 
			and coalesce(e.companyname,'') like '%".$companyname."%'
			and coalesce(c.accountname,'') like '%".$accpiutang."%'
			and coalesce(d.accountname,'') like '%".$acchutang."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.addressaccountid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=GetCatalog('addressaccount');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(
		GetCatalog('companyname'),
		GetCatalog('fullname'),
		GetCatalog('accpiutang'),
		GetCatalog('acchutang'));
		$this->pdf->setwidths(array(60,60,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1) {
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['companyname'],$row1['fullname'],$row1['accpiutangname'],$row1['acchutangname']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='addresstype';
		parent::actionDownxls();
		$sql = "select fullname,iscustomer,isemployee,isvendor,ishospital,taxno,accpiutangid,acchutangid,recordstatus
				from addressaccount a ";
		$sql = "select a.addressaccountid,e.companyname,b.fullname,c.accountname as accpiutangname,d.accountname as acchutangname
				from addressaccount a 
				left join addressbook b on b.addressbookid = a.addressbookid 
				left join account c on c.accountid = a.accpiutangid 
				left join account d on d.accountid = a.acchutangid 
				left join company e on e.companyid = a.companyid ";
		$addressaccountid = filter_input(INPUT_GET,'addressaccountid');
		$companyname = filter_input(INPUT_GET,'companyname');
		$fullname = filter_input(INPUT_GET,'fullname');
		$accpiutang = filter_input(INPUT_GET,'accpiutang');
		$acchutang = filter_input(INPUT_GET,'acchutang');
		$sql .= " where coalesce(a.addressaccountid,'') like '%".$addressaccountid."%' 
			and coalesce(e.companyname,'') like '%".$companyname."%'
			and coalesce(c.accountname,'') like '%".$accpiutang."%'
			and coalesce(d.accountname,'') like '%".$acchutang."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.addressaccountid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,GetCatalog('companyname'))
		->setCellValueByColumnAndRow(1,1,GetCatalog('fullname'))
		->setCellValueByColumnAndRow(2,1,GetCatalog('accpiutang'))
		->setCellValueByColumnAndRow(3,1,GetCatalog('acchutang'));		
		foreach($dataReader as $row1)
		{
			  $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['companyname'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['fullname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['accpiutangname'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['acchutangname']);			
				$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}