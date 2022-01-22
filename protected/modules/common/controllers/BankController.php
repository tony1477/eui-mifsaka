<?php
class BankController extends Controller {
	public $menuname = 'bank';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$bankid = isset ($_POST['bankid']) ? $_POST['bankid'] : '';
		$bankname = isset ($_POST['bankname']) ? $_POST['bankname'] : '';
		$bankid = isset ($_GET['q']) ? $_GET['q'] : $bankid;
		$bankname = isset ($_GET['q']) ? $_GET['q'] : $bankname;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'bankid';
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
				->from('bank t')
				->where('((t.bankid like :bankid) or (t.bankname like :bankname) 
					and t.recordstatus = 1 ',
					array(':bankid'=>'%'.$bankid.'%',':bankname'=>'%'.$bankname.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('bank t')
				->where("(coalesce(t.bankid,'') like :bankid) and (coalesce(t.bankname,'') like :bankname) ",
					array(':bankid'=>'%'.$bankid.'%',':bankname'=>'%'.$bankname.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('bank t')
				->where('((t.bankid like :bankid) or (t.bankname like :bankname) ) 
					and t.recordstatus = 1 ',
					array(':bankid'=>'%'.$bankid.'%',':bankname'=>'%'.$bankname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select("t.*")	
				->from('bank t')
				->where("(coalesce(t.bankid,'') like :bankid) and (coalesce(t.bankname,'') like :bankname)",
					array(':bankid'=>'%'.$bankid.'%',':bankname'=>'%'.$bankname.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'bankid'=>$data['bankid'],
				'bankname'=>$data['bankname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertbank(:vbankname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatebank(:vid,:vbankname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vbankname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["Filebank"]["name"]);
		if (move_uploaded_file($_FILES["Filebank"]["tmp_name"], $target_file)) {
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
					$bankname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$isstock = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$bankpic = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$barcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where materialtypecode = '".$materialtypecode."'")->queryScalar();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$bankname,$isstock,$bankpic,$barcode,$materialtypeid,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['bankid'])?$_POST['bankid']:''),$_POST['bankname'],$_POST['recordstatus']));
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
				$sql = 'call Purgebank(:vid,:vcreatedby)';
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
	  $sql = "select a.*,
					substr(bankname,1,(20+instr(substr(bankname,21,20),' '))) as bankname1,
					substr(bankname,(21+instr(substr(bankname,21,20),' ')),(20+instr(substr(bankname,21,20),' '))) as bankname2,
					substr(bankname,((21+instr(substr(bankname,21,20),' '))+(20+instr(substr(bankname,21,20),' '))),(20+instr(substr(bankname,21,20),' '))) as bankname3
			from bank a ";
		$bankid = filter_input(INPUT_GET,'bankid');
		$bankname = filter_input(INPUT_GET,'bankname');
		$sql .= " where coalesce(a.bankid,'') like '%".$bankid."%' 
			and coalesce(a.bankname,'') like '%".$bankname."%'"; 
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.bankid in (" . $_GET['id'] . ")";
    }
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->AddPage('P', array(
      82.6,
      108.3425
    ));
    $x = 0; $y = 0; $hitung = 0; $i = 0;
    $this->pdf->isfooter = false;
    foreach ($fgs as $row) {
				$hitung += 1;
				if ($hitung % 2 != 0) {
					$x = 10;
					$this->pdf->SetFont('Arial', 'B', 5);
					$this->pdf->text($x-8, $y+4, $row['bankname1']);
					$this->pdf->text($x-8, $y+6, $row['bankname2']);				
					$this->pdf->text($x-8, $y+8, $row['bankname3']);
					$this->pdf->SetFont('Arial', '', 5);
					if (is_numeric($row['barcode'])) {
						$this->pdf->EAN13($x-1, $y+8.5, $row['barcode'], $h=3, $w=.20);
					} 
					$this->pdf->sety($y);
				} else  {
      			$x = 50;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['bankname1']);
						$this->pdf->text($x-8, $y+6, $row['bankname2']);				
						$this->pdf->text($x-8, $y+8, $row['bankname3']);
      			$this->pdf->SetFont('Arial', '', 5);
						if (is_numeric($row['barcode'])) {
							$this->pdf->EAN13($x-1, $y+8.5, $row['barcode'], $h=3, $w=.20);
						}
      			$y = $this->pdf->gety()+21.75;
				}
			if ($y > 90) {
				$this->pdf->AddPage('P', array(
					82.6,
					108.3425
				));
				$x = 0; $y = 0;
			}
    }
    $this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='bank';
		parent::actionDownxls();
		$sql = "select bankid,bankname,bankpic,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						barcode,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from bank a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.bankid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by bankname asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['bankid'])
				->setCellValueByColumnAndRow(1,$i,$row1['bankname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['bankpic'])
				->setCellValueByColumnAndRow(3,$i,$row1['isstock'])
				->setCellValueByColumnAndRow(4,$i,$row1['barcode'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
}
