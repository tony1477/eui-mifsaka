<?php
class ProfitlossController extends Controller {
  public $menuname = 'profitloss';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $repprofitlossid = isset($_POST['repprofitlossid']) ? $_POST['repprofitlossid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $accountid       = isset($_POST['accountid']) ? $_POST['accountid'] : '';
    $isdebet         = isset($_POST['isdebet']) ? $_POST['isdebet'] : '';
    $nourut          = isset($_POST['nourut']) ? $_POST['nourut'] : '';
    $recordstatus    = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $repprofitlossid = isset($_GET['q']) ? $_GET['q'] : $repprofitlossid;
    $companyid       = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $accountid       = isset($_GET['q']) ? $_GET['q'] : $accountid;
    $isdebet         = isset($_GET['q']) ? $_GET['q'] : $isdebet;
    $nourut          = isset($_GET['q']) ? $_GET['q'] : $nourut;
    $recordstatus    = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'repprofitlossid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		
		$com = Yii::app()->db->createCommand()->select ('group_concat(distinct a.menuvalueid) as menuvalueid')
		->from('groupmenuauth a')
		->join('menuauth b', 'b.menuauthid = a.menuauthid')
		->join('usergroup c', 'c.groupaccessid = a.groupaccessid')
		->join('useraccess d', 'd.useraccessid = c.useraccessid')
		->where("upper(b.menuobject) = upper('company')
		and d.username = '" . Yii::app()->user->name . "' ")->queryScalar();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('repprofitloss t')->join('company a', 'a.companyid=t.companyid')->join('account b', 'b.accountid=t.accountid')->where('((t.repprofitlossid like :repprofitlossid) or
								(a.companyname like :companyid) or
								(b.accountname like :accountid) or
								(t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repprofitlossid' => '%' . $repprofitlossid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountid' => '%' . $accountid . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyid,a.companyname,b.accountid,b.accountname')->from('repprofitloss t')->join('company a', 'a.companyid=t.companyid')->join('account b', 'b.accountid=t.accountid')->where('((t.repprofitlossid like :repprofitlossid) or
								(a.companyname like :companyid) or
								(b.accountname like :accountid) or
								(t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repprofitlossid' => '%' . $repprofitlossid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountid' => '%' . $accountid . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'repprofitlossid' => $data['repprofitlossid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'isdebet' => $data['isdebet'],
        'accformula' => $data['accformula'],
        'performula' => $data['performula'],
        'aftacc' => Yii::app()->format->formatCurrency($data['aftacc']),
        'nourut' => $data['nourut'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	private function ModifyData($arraydata) {
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertprofitloss(:vcompanyid,:vaccountid,:visdebet,:vaccformula,:vperformula,:vaftacc,:vnourut,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateprofitloss(:vid,:vcompanyid,:vaccountid,:visdebet,:vaccformula,:vperformula,:vaftacc,:vnourut,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $arraydata[0]);
      }
      $command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vaccountid', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':visdebet', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vaccformula', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vperformula', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vaftacc', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vnourut', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $arraydata[8], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["FileProfitloss"]["name"]);
		if (move_uploaded_file($_FILES["FileProfitloss"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			for ($row = 2; $row <= $highestRow; ++$row) {
				$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
				$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
				$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
				$accountcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
				$accountid = Yii::app()->db->createCommand("select accountid from account where accountcode = '".$accountcode."'")->queryScalar();
				$isdebet = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
				$accformula = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
				$performula = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
				$aftacc = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
				$nourut = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
				$recordstatus = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
				$this->ModifyData(array($id,$companyid,$accountid,$isdebet,$accformula,$performula,$aftacc,$nourut,$recordstatus));
			}
    }
	}
  public function actionSave() {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $this->ModifyData(array((isset($_POST['repprofitlossid'])?$_POST['repprofitlossid']:''),$_POST['companyid'],$_POST['accountid'],
			$_POST['isdebet'],$_POST['accformula'],$_POST['performula'],$_POST['aftacc'],$_POST['nourut'],$_POST['recordstatus']));
  }
  public function actionPurge() {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeprofitloss(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionGeneratepl() {
    parent::actionDownload();
		$sql = "call InsertPLLajur(" . $_REQUEST['companyname'] . ", '" . date(Yii::app()->params['datetodb'], strtotime($_REQUEST['pldate'])) . "')";
    Yii::app()->db->createCommand($sql)->execute();
		GetMessage('success', 'alreadysaved');
  }
	public function actionGeneratepltahunan() {
    parent::actionDownload();
		$sql = "call InsertPLLajurTahun(" . $_REQUEST['companyname'] . ", '" . date(Yii::app()->params['datetodb'], strtotime($_REQUEST['pldate'])) . "')";
    Yii::app()->db->createCommand($sql)->execute();
		GetMessage('success', 'alreadysaved');
  }
	public function actionDownPDF() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    //$sql        = "call InsertPLLajur('" . $_GET['company'] . "','" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')";
    //$this->connection->createCommand($sql)->execute();
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->AddPage('L');
    $this->pdf->Cell(0, 0, GetCatalog('profitloss'), 0, 0, 'C');
    $this->pdf->Cell(-277, 10, 'Per : ' . date("d F Y", strtotime($_GET['date'])), 0, 0, 'C');
    $i = 0;
    $this->pdf->setFont('Arial', 'B', 6);
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->colalign  = array(
      'C',
      'C',
      'C',
      'C'
    );
    $this->pdf->colheader = array(
      '',
      'Bulan Ini',
      'Bulan Lalu',
      'Akumulatif  s/d  Bulan Ini'
    );
    $this->pdf->setwidths(array(
      50,
      92,
      40,
      92
    ));
    $this->pdf->Rowheader();
    $this->pdf->colalign  = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C'
    );
    $this->pdf->colheader = array(
      'Keterangan',
      'Budget',
      '%',
      'Actual',
      '%',
      'Penc %',
      'Actual',
      '%',
      'Budget',
      '%',
      'Actual',
      '%',
      'Penc %'
    );
    $this->pdf->setwidths(array(
      50,
      28,
      12,
      28,
      12,
      12,
      28,
      12,
      28,
      12,
      28,
      12,
      12
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R'
    );
    $sql                       = "select a.*
			from repprofitlosslajur a 
			where a.companyid = '" . $_GET['company'] . "' 
			and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			order by jumlah";
    $datas                     = $this->connection->createCommand($sql)->queryAll();
    foreach ($datas as $data) {
      if (($data['accountid'] !== null) && (strpos($data['accountname'], 'Total') === false)) {
        $this->pdf->setFont('Arial', '', 6);
        $this->pdf->row(array(
          $data['accountname'],
          Yii::app()->format->formatCurrency($data['budgetblninitotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['budgetblninipersen']),
          Yii::app()->format->formatCurrency($data['actualblninitotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['actualblninipersen']),
          Yii::app()->format->formatCurrency($data['pencpersen']),
          Yii::app()->format->formatCurrency($data['actualblnlalutotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['actualblnlalupersen']),
          Yii::app()->format->formatCurrency($data['budgetakumtotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['budgetakumpersen']),
          Yii::app()->format->formatCurrency($data['actualakumtotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['actualakumpersen']),
          Yii::app()->format->formatCurrency($data['pencakumpersen'])
        ));
      } else if ($data['accountid'] == null) {
        $this->pdf->setFont('Arial', 'B', 6);
        $this->pdf->row(array(
          $data['accountname'],
          '',
          '',
          '',
          '',
          '',
          '',
          '',
          '',
          '',
          '',
          '',
          ''
        ));
      } else {
        $this->pdf->setFont('Arial', 'B', 6);
        $this->pdf->row(array(
          $data['accountname'],
          Yii::app()->format->formatCurrency($data['budgetblninitotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['budgetblninipersen']),
          Yii::app()->format->formatCurrency($data['actualblninitotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['actualblninipersen']),
          Yii::app()->format->formatCurrency($data['pencpersen']),
          Yii::app()->format->formatCurrency($data['actualblnlalutotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['actualblnlalupersen']),
          Yii::app()->format->formatCurrency($data['budgetakumtotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['budgetakumpersen']),
          Yii::app()->format->formatCurrency($data['actualakumtotal']/$_GET['per']),
          Yii::app()->format->formatCurrency($data['actualakumpersen']),
          Yii::app()->format->formatCurrency($data['pencakumpersen'])
        ));
      }
      $this->pdf->sety($this->pdf->gety() - 2);
    }
    $this->pdf->Output();
  }
  public function actionDownXls() {
    $this->menuname = 'laporanlabarugi';
    parent::actionDownXls();
    $sql                       = "select a.*
			from repprofitlosslajur a 
			where a.companyid = '" . $_GET['company'] . "' 
			and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			order by jumlah";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 6;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($_GET['date'])));
    foreach ($dataReader as $row1) 
		{
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['accountname'])->setCellValueByColumnAndRow(1, $i + 1, $row1['budgetblninitotal']/$_GET['per'])->setCellValueByColumnAndRow(2, $i + 1, $row1['budgetblninipersen'])->setCellValueByColumnAndRow(3, $i + 1, $row1['actualblninitotal']/$_GET['per'])->setCellValueByColumnAndRow(4, $i + 1, $row1['actualblninipersen'])->setCellValueByColumnAndRow(5, $i + 1, $row1['pencpersen'])->setCellValueByColumnAndRow(6, $i + 1, $row1['actualblnlalutotal']/$_GET['per'])->setCellValueByColumnAndRow(7, $i + 1, $row1['actualblnlalupersen'])->setCellValueByColumnAndRow(8, $i + 1, $row1['budgetakumtotal']/$_GET['per'])->setCellValueByColumnAndRow(9, $i + 1, $row1['budgetakumpersen'])->setCellValueByColumnAndRow(10, $i + 1, $row1['actualakumtotal']/$_GET['per'])->setCellValueByColumnAndRow(11, $i + 1, $row1['actualakumpersen'])->setCellValueByColumnAndRow(12, $i + 1, $row1['pencakumpersen']);
      $i += 1;
    }
    $this->getFooterXLS($this->phpExcel);
  }
	public function actionDownPDF1() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    //$sql        = "call InsertPLLajur('" . $_GET['company'] . "','" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')";
    //$this->connection->createCommand($sql)->execute();
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->AddPage('L',array(200,775));
    $this->pdf->Cell(0, 0, GetCatalog('profitloss'), 0, 0, 'C');
    $this->pdf->Cell(-277, 10, 'Per : ' . date("d F Y", strtotime($_GET['date'])), 0, 0, 'C');
    $i = 0;
    $this->pdf->setFont('Arial', 'B', 6);
    $this->pdf->sety($this->pdf->gety() + 10);
    $this->pdf->colalign  = array(
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
      'C',
    );
    $this->pdf->colheader = array(
      'Keterangan',
      'Januari',
      '%',
      'Februari',
      '%',
      'Maret',
      '%',
      'Triwulan I',
      '%',
      'April',
      '%',
      'Mei',
      '%',
      'Juni',
      '%',
      'Triwulan II',
      '%',
      'Semester I',
      '%',
      'Juli',
      '%',
      'Agustus',
      '%',
      'September',
      '%',
      'Triwulan III',
      '%',
      'Oktober',
      '%',
      'Nopember',
      '%',
      'Desember',
      '%',
      'Triwulan IV',
      '%',
      'Semester II',
      '%',
      'Total',
      '%'
    );
    $this->pdf->setwidths(array(
      50,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
      25,
      12,
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
    );
    $sql = "select accountid,accountname,jan,janper,feb,febper,mar,marper,tri1,tri1per,
				apr,aprper,mei,meiper,jun,junper,tri2,tri2per,sem1,sem1per,
				jul,julper,ags,agsper,sep,sepper,tri3,tri3per,
				okt,oktper,nop,nopper,des,desper,tri4,tri4per,sem2,sem2per,total,totalper
			from repprofitlosslajurtahun
			where companyid = '" . $_GET['company'] . "' 
			and tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			order by jumlah";
    $datas                     = $this->connection->createCommand($sql)->queryAll();
    foreach ($datas as $data) {
      if (($data['accountid'] !== null) && (strpos($data['accountname'], 'Total') === false)) {
        $this->pdf->setFont('Arial', '', 6);
        $this->pdf->row(array(
          $data['accountname'],
					Yii::app()->format->formatCurrency($data['jan']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['janper']),
					Yii::app()->format->formatCurrency($data['feb']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['febper']),
					Yii::app()->format->formatCurrency($data['mar']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['marper']),
					Yii::app()->format->formatCurrency($data['tri1']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['tri1per']),
					Yii::app()->format->formatCurrency($data['apr']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['aprper']),
					Yii::app()->format->formatCurrency($data['mei']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['meiper']),
					Yii::app()->format->formatCurrency($data['jun']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['junper']),
					Yii::app()->format->formatCurrency($data['tri2']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['tri2per']),
					Yii::app()->format->formatCurrency($data['sem1']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['sem1per']),
					Yii::app()->format->formatCurrency($data['jul']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['julper']),
					Yii::app()->format->formatCurrency($data['ags']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['agsper']),
					Yii::app()->format->formatCurrency($data['sep']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['sepper']),
					Yii::app()->format->formatCurrency($data['tri3']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['tri3per']),
					Yii::app()->format->formatCurrency($data['okt']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['oktper']),
					Yii::app()->format->formatCurrency($data['nop']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['nopper']),
					Yii::app()->format->formatCurrency($data['des']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['desper']),
					Yii::app()->format->formatCurrency($data['tri4']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['tri4per']),
					Yii::app()->format->formatCurrency($data['sem2']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['sem2per']),
					Yii::app()->format->formatCurrency($data['total']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['totalper']),
          //Yii::app()->format->formatCurrency($data['pencakumpersen'])
        ));
      } else if ($data['accountid'] == null) {
        $this->pdf->setFont('Arial', 'B', 6);
        $this->pdf->row(array(
          $data['accountname'],
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
        ));
      } else {
        $this->pdf->setFont('Arial', 'B', 6);
        $this->pdf->row(array(
          $data['accountname'],
					Yii::app()->format->formatCurrency($data['jan']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['janper']),
					Yii::app()->format->formatCurrency($data['feb']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['febper']),
					Yii::app()->format->formatCurrency($data['mar']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['marper']),
					Yii::app()->format->formatCurrency($data['tri1']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['tri1per']),
					Yii::app()->format->formatCurrency($data['apr']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['aprper']),
					Yii::app()->format->formatCurrency($data['mei']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['meiper']),
					Yii::app()->format->formatCurrency($data['jun']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['junper']),
					Yii::app()->format->formatCurrency($data['tri2']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['tri2per']),
					Yii::app()->format->formatCurrency($data['sem1']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['sem1per']),
					Yii::app()->format->formatCurrency($data['jul']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['julper']),
					Yii::app()->format->formatCurrency($data['ags']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['agsper']),
					Yii::app()->format->formatCurrency($data['sep']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['sepper']),
					Yii::app()->format->formatCurrency($data['tri3']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['tri3per']),
					Yii::app()->format->formatCurrency($data['okt']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['oktper']),
					Yii::app()->format->formatCurrency($data['nop']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['nopper']),
					Yii::app()->format->formatCurrency($data['des']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['desper']),
					Yii::app()->format->formatCurrency($data['tri4']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['tri4per']),
					Yii::app()->format->formatCurrency($data['sem2']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['sem2per']),
					Yii::app()->format->formatCurrency($data['total']/$_GET['per']),
					Yii::app()->format->formatCurrency($data['totalper']),
        ));
      }
      $this->pdf->sety($this->pdf->gety() - 2);
    }
    $this->pdf->Output();
  }
  public function actionDownXls1() {
    $this->menuname = 'laporanlabarugitahun';
    parent::actionDownXls();
		$sql2 = "select a.companycode
							from company a
							where a.companyid = " . $_GET['company'] . "
		";
    $company = Yii::app()->db->createCommand($sql2)->queryScalar();
		
    $sql = "select accountid,accountname,jan,janper,feb,febper,mar,marper,tri1,tri1per,
				apr,aprper,mei,meiper,jun,junper,tri2,tri2per,sem1,sem1per,
				jul,julper,ags,agsper,sep,sepper,tri3,tri3per,
				okt,oktper,nop,nopper,des,desper,tri4,tri4per,sem2,sem2per,total,totalper
			from repprofitlosslajurtahun
			where companyid = " . $_GET['company'] . "
			and tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
			order by jumlah";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 5;
    $this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0, 2, date('Y',strtotime($_GET['date'])))
    ->setCellValueByColumnAndRow(0, 3, $company);
    foreach ($dataReader as $row1) 
		{
      $this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0, $i + 1, $row1['accountname'])
			->setCellValueByColumnAndRow(1, $i + 1, $row1['jan'])
			->setCellValueByColumnAndRow(2, $i + 1, $row1['janper'])
			->setCellValueByColumnAndRow(3, $i + 1, $row1['feb'])
			->setCellValueByColumnAndRow(4, $i + 1, $row1['febper'])
			->setCellValueByColumnAndRow(5, $i + 1, $row1['mar'])
			->setCellValueByColumnAndRow(6, $i + 1, $row1['marper'])
			->setCellValueByColumnAndRow(7, $i + 1, $row1['tri1'])
			->setCellValueByColumnAndRow(8, $i + 1, $row1['tri1per'])
			->setCellValueByColumnAndRow(9, $i + 1, $row1['apr'])
			->setCellValueByColumnAndRow(10, $i + 1, $row1['aprper'])
			->setCellValueByColumnAndRow(11, $i + 1, $row1['mei'])
			->setCellValueByColumnAndRow(12, $i + 1, $row1['meiper'])
			->setCellValueByColumnAndRow(13, $i + 1, $row1['jun'])
			->setCellValueByColumnAndRow(14, $i + 1, $row1['junper'])
			->setCellValueByColumnAndRow(15, $i + 1, $row1['tri2'])
			->setCellValueByColumnAndRow(16, $i + 1, $row1['tri2per'])
			->setCellValueByColumnAndRow(17, $i + 1, $row1['sem1'])
			->setCellValueByColumnAndRow(18, $i + 1, $row1['sem1per'])
			->setCellValueByColumnAndRow(19, $i + 1, $row1['jul'])
			->setCellValueByColumnAndRow(20, $i + 1, $row1['julper'])
			->setCellValueByColumnAndRow(21, $i + 1, $row1['ags'])
			->setCellValueByColumnAndRow(22, $i + 1, $row1['agsper'])
			->setCellValueByColumnAndRow(23, $i + 1, $row1['sep'])
			->setCellValueByColumnAndRow(24, $i + 1, $row1['sepper'])
			->setCellValueByColumnAndRow(25, $i + 1, $row1['tri3'])
			->setCellValueByColumnAndRow(26, $i + 1, $row1['tri3per'])
			->setCellValueByColumnAndRow(27, $i + 1, $row1['okt'])
			->setCellValueByColumnAndRow(28, $i + 1, $row1['oktper'])
			->setCellValueByColumnAndRow(29, $i + 1, $row1['nop'])
			->setCellValueByColumnAndRow(30, $i + 1, $row1['nopper'])
			->setCellValueByColumnAndRow(31, $i + 1, $row1['des'])
			->setCellValueByColumnAndRow(32, $i + 1, $row1['desper'])
			->setCellValueByColumnAndRow(33, $i + 1, $row1['tri4'])
			->setCellValueByColumnAndRow(34, $i + 1, $row1['tri4per'])
			->setCellValueByColumnAndRow(35, $i + 1, $row1['sem2'])
			->setCellValueByColumnAndRow(36, $i + 1, $row1['sem2per'])
			->setCellValueByColumnAndRow(37, $i + 1, $row1['total'])
			->setCellValueByColumnAndRow(38, $i + 1, $row1['totalper']);
      $i += 1;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}