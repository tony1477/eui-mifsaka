<?php
class GenledgerController extends Controller {
  public $menuname = 'genledger';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $genledgerid     = isset($_POST['genledgerid']) ? $_POST['genledgerid'] : '';
    $accountcode       = isset($_POST['accountcode']) ? $_POST['accountcode'] : '';
    $accountname     = isset($_POST['accountname']) ? $_POST['accountname'] : '';
    $journalno       = isset($_POST['journalno']) ? $_POST['journalno'] : '';
    $postdate       = isset($_POST['postdate']) ? $_POST['postdate'] : '';
    $companyname       = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $detailnote       = isset($_POST['detailnote']) ? $_POST['detailnote'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'genledgerid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('genledger t')
		->leftjoin('genjournal a', 'a.genjournalid = t.genjournalid')
		->leftjoin('account c', 'c.accountid = t.accountid')
		->leftjoin('currency d', 'd.currencyid = t.currencyid')
		->leftjoin('company e', 'e.companyid = c.companyid')
		->where("((coalesce(c.accountname,'') like :accountname) and 
		(coalesce(c.accountcode,'') like :accountcode) and 
		(coalesce(a.journalno,'') like :journalno) and 
		(coalesce(e.companyname,'') like :companyname) and 
		(coalesce(t.genledgerid,'') like :genledgerid) and 
		(coalesce(t.detailnote,'') like :detailnote) and 
		(coalesce(a.postdate,'') like :postdate)) 
		and t.accountid > 0", array(
      ':accountcode' => '%' . $accountcode . '%',
      ':genledgerid' => '%' . $genledgerid . '%',
      ':accountname' => '%' . $accountname . '%',
      ':journalno' => '%' . $journalno . '%',
      ':detailnote' => '%' . $detailnote . '%',
      ':companyname' => '%' . $companyname . '%',
      ':postdate' => '%' . $postdate . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,c.accountname,c.companyid,a.journalno,a.postdate,d.currencyname,d.symbol')->from('genledger t')
		->leftjoin('genjournal a', 'a.genjournalid = t.genjournalid')
		->leftjoin('account c', 'c.accountid = t.accountid')
		->leftjoin('currency d', 'd.currencyid = t.currencyid')
		->leftjoin('company e', 'e.companyid = c.companyid')
		->where("((coalesce(c.accountname,'') like :accountname) and 
		(coalesce(c.accountcode,'') like :accountcode) and 
		(coalesce(a.journalno,'') like :journalno) and 
		(coalesce(e.companyname,'') like :companyname) and 
		(coalesce(t.genledgerid,'') like :genledgerid) and 
		(coalesce(t.detailnote,'') like :detailnote) and 
		(coalesce(a.postdate,'') like :postdate)) 
    and t.accountid > 0", array(
      ':accountcode' => '%' . $accountcode . '%',
      ':accountname' => '%' . $accountname . '%',
      ':genledgerid' => '%' . $genledgerid . '%',
      ':detailnote' => '%' . $detailnote . '%',
      ':journalno' => '%' . $journalno . '%',
      ':companyname' => '%' . $companyname . '%',
      ':postdate' => '%' . $postdate . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'genledgerid' => $data['genledgerid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'genjournalid' => $data['genjournalid'],
        'journalno' => $data['journalno'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'debit' => Yii::app()->format->formatNumber($data['debit']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'postdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['postdate'])),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'symbol' => $data['symbol'],
        'ratevalue' => Yii::app()->format->formatNumber($data['ratevalue'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-genledger"]["name"]);
		if (move_uploaded_file($_FILES["file-genledger"]["tmp_name"], $target_file)) {
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
					$accountcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$accountname = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$accountid = Yii::app()->db->createCommand("select accountid from account 
						where accountcode = '".$accountcode."'
						and companyid = ".$companyid)->queryScalar();
					$journalno = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$genjournalid = Yii::app()->db->createCommand("select genjournalid from genjournal
						where companyid = ".$companyid." 
						and journalno = '".$journalno."'")->queryScalar();
					$debit = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$credit = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$postdate = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$journaldate = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$currencyname = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$currencyid = Yii::app()->db->createCommand("select currencyid from currencyname where currencyname = '".$currencyname."'")->queryScalar();
					$ratevalue = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$detailnote = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$sql = "insert into genledger (companyid,accountid,genjournalid,debit,credit,postdate,journaldate,currencyid,ratevalue,detailnote) 
						values (:companyid,:accountid,:genjournalid,:debit,:credit,:postdate,:journaldate,:currencyid,:ratevalue,:detailnote)";
					$command = $connection->createCommand($sql);
					$command->bindvalue(':companyid',$companyid,PDO::PARAM_STR);
					$command->bindvalue(':accountid',$accountid,PDO::PARAM_STR);
					$command->bindvalue(':genjournalid',$genjournalid,PDO::PARAM_STR);
					$command->bindvalue(':debit',$debit,PDO::PARAM_STR);
					$command->bindvalue(':credit',$credit,PDO::PARAM_STR);
					$command->bindvalue(':postdate',$postdate,PDO::PARAM_STR);
					$command->bindvalue(':journaldate',$journaldate,PDO::PARAM_STR);
					$command->bindvalue(':currencyid',$currencyid,PDO::PARAM_STR);
					$command->bindvalue(':ratevalue',$ratevalue,PDO::PARAM_STR);
					$command->bindvalue(':detailnote',$detailnote,PDO::PARAM_STR);
					$command->execute();
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
  public function actionDownPDF() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    $sql        = "select t.genledgerid, c.accountname, a.journalno, b.debit, b.credit, d.symbol, a.postdate, d.currencyname, b.ratevalue,a.companyid
				from genledger t
				left join account c on c.accountid = t.accountid
				left join genjournal a on a.genjournalid = t.genjournalid
				left join journaldetail b on b.accountid= t.accountid
				left join currency d on d.currencyid = t.currencyid ";
		$genledgerid = filter_input(INPUT_GET,'genledgerid');
		$accountname = filter_input(INPUT_GET,'accountname');
		$accountcode = filter_input(INPUT_GET,'accountcode');
		$journalno = filter_input(INPUT_GET,'journalno');
		$postdate = filter_input(INPUT_GET,'postdate');
		$detailnote = filter_input(INPUT_GET,'detailnote');
		$sql .= " where coalesce(t.genledgerid,'') like '%".$genledgerid."%' 
			and coalesce(c.accountname,'') like '%".$accountname."%'
			and coalesce(c.accountcode,'') like '%".$accountcode."%'
			and coalesce(a.journalno,'') like '%".$journalno."%'
			and coalesce(t.postdate,'') like '%".$postdate."%'
			and coalesce(t.detailnote,'') like '%".$detailnote."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and t.genledgerid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('Genledger');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('accountname'),
      GetCatalog('journalno'),
      GetCatalog('debit'),
      GetCatalog('credit'),
      GetCatalog('postdate'),
      GetCatalog('currencyname'),
      GetCatalog('ratevalue')
    );
    $this->pdf->setwidths(array(
      35,
      30,
      30,
      30,
      30,
      25,
      15
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial', '', 8);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['accountname'],
        $row1['journalno'],
        Yii::app()->numberFormatter->formatCurrency($row1['debit'], $row1['symbol']),
        Yii::app()->numberFormatter->formatCurrency($row1['credit'], $row1['symbol']),
        $row1['postdate'],
        $row1['currencyname'],
        $row1['ratevalue']
      ));
    }
    $this->pdf->Output();
  }
}