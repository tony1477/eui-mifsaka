<?php
class CurrencyController extends Controller {
	public $menuname = 'currency';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$currencyid = isset ($_POST['currencyid']) ? $_POST['currencyid'] : '';
		$countryname = isset ($_POST['countryname']) ? $_POST['countryname'] : '';
		$currencyname = isset ($_POST['currencyname']) ? $_POST['currencyname'] : '';
		$symbol = isset ($_POST['symbol']) ? $_POST['symbol'] : '';
		$i18n = isset ($_POST['i18n']) ? $_POST['i18n'] : '';
		$currencyid = isset ($_GET['q']) ? $_GET['q'] : $currencyid;
		$countryname = isset ($_GET['q']) ? $_GET['q'] : $countryname;
		$currencyname = isset ($_GET['q']) ? $_GET['q'] : $currencyname;
		$symbol = isset ($_GET['q']) ? $_GET['q'] : $symbol;
		$i18n = isset ($_GET['q']) ? $_GET['q'] : $i18n;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'currencyid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_POST['page']) ? intval($_POST['page']) : $page;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : $rows;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : (strpos($sort,'t.') > 0)?$sort:'t.'.$sort;
		$order = isset($_POST['order']) ? strval($_POST['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('currency t')
				->join('country p', 't.countryid=p.countryid')
				->where('(currencyid like :currencyid) and (symbol like :symbol) and (currencyname like :currencyname) and (p.countryname like :countryname)
						and (i18n like :i18n)',
						array(':currencyid'=>'%'.$currencyid.'%',':symbol'=>'%'.$symbol.'%',':currencyname'=>'%'.$currencyname.'%',':countryname'=>'%'.$countryname.'%',
						':i18n'=>'%'.$i18n.'%'))			
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('currency t')
			->join('country p', 't.countryid=p.countryid')
			->where('((currencyid like :currencyid) or (symbol like :symbol) or (currencyname like :currencyname) or (p.countryname like :countryname)
				or (i18n like :i18n)) and t.recordstatus = 1',
					array(':currencyid'=>'%'.$currencyid.'%',':symbol'=>'%'.$symbol.'%',':currencyname'=>'%'.$currencyname.'%',':countryname'=>'%'.$countryname.'%',
					':i18n'=>'%'.$i18n.'%'))			
			->queryScalar();
		}
		$result['total'] = $cmd;
		if (!isset($_GET['combo'])) {
		$cmd = Yii::app()->db->createCommand()
			->select()			
			->from('currency t')
			->join('country p', 't.countryid=p.countryid')
			->where('(currencyid like :currencyid) and (symbol like :symbol) and (currencyname like :currencyname) and (p.countryname like :countryname)
				and (i18n like :i18n) ',
					array(':currencyid'=>'%'.$currencyid.'%',':symbol'=>'%'.$symbol.'%',':currencyname'=>'%'.$currencyname.'%',':countryname'=>'%'.$countryname.'%',
					':i18n'=>'%'.$i18n.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
			->select()			
			->from('currency t')
			->join('country p', 't.countryid=p.countryid')
			->where('((currencyid like :currencyid) or (symbol like :symbol) or (currencyname like :currencyname) or (p.countryname like :countryname)
				or (i18n like :i18n)) and t.recordstatus = 1',
					array(':currencyid'=>'%'.$currencyid.'%',':symbol'=>'%'.$symbol.'%',':currencyname'=>'%'.$currencyname.'%',':countryname'=>'%'.$countryname.'%',
					':i18n'=>'%'.$i18n.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'currencyid'=>$data['currencyid'],
				'countryid'=>$data['countryid'],
				'countryname'=>$data['countryname'],
				'currencyname'=>$data['currencyname'],
				'symbol'=>$data['symbol'],
				'i18n'=>$data['i18n'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertcurrency(:vcountryid,:vcurrencyname,:vsymbol,:vi18n,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatecurrency(:vid,:vcountryid,:vcurrencyname,:vsymbol,:vi18n,:vrecordstatus,:vdatauser)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcountryid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vsymbol',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vi18n',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vdatauser', GetUserPC(),PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-currency"]["name"]);
		if (move_uploaded_file($_FILES["file-currency"]["tmp_name"], $target_file)) {
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
					$countrycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$countryid = Yii::app()->db->createCommand("select countryid from country where countrycode = '".$countrycode."'")->queryScalar();
					$currencyname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$symbol = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$i18n = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,$countryid,$currencyname,$symbol,$i18n,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['currencyid'])?$_POST['currencyid']:''),$_POST['countryid'],$_POST['currencyname'],$_POST['symbol'],$_POST['i18n'],$_POST['recordstatus']));
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
				$sql = 'call Purgecurrency(:vid,:vdatauser)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vdatauser',GetUserPC(),PDO::PARAM_STR);
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
	  $sql = "select a.currencyid,b.countryname,a.currencyname,a.symbol,a.i18n,
				case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from currency a
				join country b on b.countryid = a.countryid ";
		$currencyid = filter_input(INPUT_GET,'currencyid');
		$currencyname = filter_input(INPUT_GET,'currencyname');
		$countryname = filter_input(INPUT_GET,'countryname');
		$sql .= " where coalesce(a.currencyid,'') like '%".$currencyid."%' 
			and coalesce(a.currencyname,'') like '%".$currencyname."%'
			and coalesce(b.countryname,'') like '%".$countryname."%'
			";
		if ($_GET['id'] !== '') {
			$sql = $sql . " and a.currencyid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by countryname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('currency');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('currencyid'),
										GetCatalog('countryname'),
										GetCatalog('currencyname'),
										GetCatalog('symbol'),
										GetCatalog('i18n'),
										GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,80,100,50,50,35));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',8);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['currencyid'],$row1['countryname'],$row1['currencyname'],$row1['symbol'],$row1['i18n'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='currency';
		parent::actionDownxls();
		$sql = "select a.currencyid,b.countryname,a.currencyname,a.symbol,a.i18n,
				case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
				from currency a
				join country b on b.countryid = a.countryid ";
		if ($_GET['id'] !== '')  {
			$sql = $sql . " where a.currencyid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by countryname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('currencyid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('countryname'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('currencyname'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('symbol'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('i18n'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['currencyid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['countryname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['currencyname'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['symbol'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['i18n'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}