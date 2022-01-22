<?php
class BudgetController extends Controller {
  public $menuname = 'budget';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $budgetid   = isset($_POST['budgetid']) ? $_POST['budgetid'] : '';
    $budgetdate = isset($_POST['budgetdate']) ? $_POST['budgetdate'] : '';
    $companyname  = isset($_POST['companyname'])  ? $_POST['companyname'] : '';
    $accountcode  = isset($_POST['accountcode']) ? $_POST['accountcode'] : '';
    $accountname  = isset($_POST['accountname']) ? $_POST['accountname'] : '';
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'budgetid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('budget t')
		->leftjoin('account p', 't.accountid=p.accountid')
		->leftjoin('company q', 'q.companyid=p.companyid')
		->where("((budgetid like :budgetid) and (budgetdate like :budgetdate) and (q.companyname like :companyname) and (p.accountcode like :accountcode) 
				and (p.accountname like :accountname)) and
				p.companyid in (".getUserObjectValues('company').")", array(
			':budgetid' => '%' . $budgetid . '%',
			':budgetdate' => '%' . $budgetdate . '%',
			':companyname' => '%' . $companyname . '%',
			':accountcode' => '%' . $accountcode . '%',
			':accountname' => '%' . $accountname . '%'
		))->queryScalar();
    $result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,p.accountcode,p.accountname,q.companyname,
			(
				select ifnull(sum(z.credit) - sum(z.debit),0)
				from genledger z
				where month(z.journaldate) = month(t.budgetdate) and year(z.journaldate) = year(t.budgetdate) 
				and z.accountid = p.accountid
			) as pakaibudget,
			(
				select ifnull(sum(z.debit) - sum(z.credit),0)
				from genledger z
				where month(z.journaldate) = month(t.budgetdate) and year(z.journaldate) = year(t.budgetdate) 
				and z.accountid = p.accountid
			) as pakbug')
			->from('budget t')
			->leftjoin('account p', 't.accountid=p.accountid')
			->leftjoin('company q', 'q.companyid=p.companyid')
			->where("((budgetid like :budgetid) and (budgetdate like :budgetdate) and (q.companyname like :companyname) and (p.accountcode like :accountcode) and (p.accountname like :accountname)) and
					p.companyid in (".getUserObjectValues('company').")", array(
        ':budgetid' => '%' . $budgetid . '%',
        ':budgetdate' => '%' . $budgetdate . '%',
        ':companyname' => '%' . $companyname . '%',
        ':accountcode' => '%' . $accountcode . '%',
        ':accountname' => '%' . $accountname . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['budgetamount'] < 0) {
				$pakbug = $data['pakbug'];
			} else {
				$pakbug = $data['pakaibudget'];
			}
			if (($data['budgetamount'] >= 0) && ($data['budgetamount'] < $pakbug)) {
				$warna = 1;
			} else 
			if (($data['budgetamount'] >= 0) && ($data['budgetamount'] >= $pakbug)) {
				$warna = 2;
			} else
			if (($data['budgetamount'] < 0) && ($data['budgetamount'] >= $pakbug)) {
				$warna = 3;
			} else
			if (($data['budgetamount'] < 0) && ($data['budgetamount'] < $pakbug)) {
				$warna = 4;
			} 
      $row[] = array(
        'budgetid' => $data['budgetid'],
        'budgetdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['budgetdate'])),
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'accountcode' => $data['accountcode'],
        'pakaibud' => $pakbug,
        'budgetamount' => Yii::app()->format->formatCurrency($data['budgetamount']),
        'pakaibudget' => Yii::app()->format->formatCurrency($pakbug),
        'companyid' => $data['companyid'],
				'warna' => $warna,
        'companyname' => $data['companyname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call InsertBudget(:vbudgetdate, :vcompanyid, :vaccountid, :vbudgetamount, :vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateBudget(:vid, :vbudgetdate, :vcompanyid, :vaccountid, :vbudgetamount, :vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vbudgetdate', $arraydata[1], PDO::PARAM_STR);
    $command->bindvalue(':vcompanyid', $arraydata[2], PDO::PARAM_STR);
    $command->bindvalue(':vaccountid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vbudgetamount', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
    $target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-budget"]["name"]);
    if (move_uploaded_file($_FILES["file-budget"]["tmp_name"], $target_file)) {
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objPHPExcel = $objReader->load($target_file);
      $objWorksheet = $objPHPExcel->getActiveSheet();
      $highestRow = $objWorksheet->getHighestRow(); 
      $highestColumn = $objWorksheet->getHighestColumn();
      $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
			try {
				for ($row = 3; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$budgetdate = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$accountcode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$accountid = Yii::app()->db->createCommand("select accountid from account where companyid = ".$companyid." and accountcode = '".$accountcode."'")->queryScalar();
					$budgetamount = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$this->ModifyData($connection,array($id,date(Yii::app()->params['datetodb'], strtotime($budgetdate)),$companyid,$accountid,$budgetamount));
				}
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, $e->getMessage());
			}
    }
	}
  public function actionSave() {
    parent::actionWrite();
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['budgetid'])?$_POST['budgetid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['budgetdate'])),$_POST['companyid'],$_POST['accountid'],$_POST['budgetamount']));
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeBudget(:vid,:vcreatedby)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $budgetid = filter_input(INPUT_GET,'budgetid');
	$budgetdate = filter_input(INPUT_GET,'budgetdate');
	$companyname = filter_input(INPUT_GET,'companyname');
	$accountname = filter_input(INPUT_GET,'accountname');
	$accountcode = filter_input(INPUT_GET,'accountcode');
    $year = date('Y',strtotime($budgetdate));
    $month = date('m',strtotime($budgetdate));
    $day1 = strtotime(''.$year.'-'.$month.'-01');
    $day2 = strtotime(''.$year.'-'.$month.'-01 -1 month');
    $bulanini = date('Y-m-d',($day1));
    $bulanlalu = date('Y-m-d',($day2));
    
    
    /*$sql = "select a.budgetid,a.budgetdate,b.accountname,a.budgetamount,a.companyid,ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between DATE_ADD(DATE_ADD(LAST_DAY(a.budgetdate),INTERVAL 1 DAY),INTERVAL - 1 MONTH) and LAST_DAY(a.budgetdate)),0) as realisasi,
            ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between CONCAT(YEAR(a.budgetdate),'-01-01') and LAST_DAY(a.budgetdate)),0) as kumrealisasi
						from budget a
						join account b on b.accountid = a.accountid 
						join company c on c.companyid = a.companyid ";
		$budgetid = filter_input(INPUT_GET,'budgetid');
		$budgetdate = filter_input(INPUT_GET,'budgetdate');
		$companyname = filter_input(INPUT_GET,'companyname');
		$accountname = filter_input(INPUT_GET,'accountname');
		$accountcode = filter_input(INPUT_GET,'accountcode');
		$sql .= " where coalesce(a.budgetid,'') like '%".$budgetid."%' 
			and coalesce(a.budgetdate,'') like '%".$budgetdate."%'
			and coalesce(c.companyname,'') like '%".$companyname."%'
			and coalesce(b.accountname,'') like '%".$accountname."%'
			and coalesce(b.accountcode,'') like '%".$accountcode."%'
			";
    
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.budgetid in (" . $_GET['id'] . ")";
    } 
    */
    $sql = "select accountid, accountname, budgetdate,companyid,
            ifnull((select budgetamount
            from budget x where x.budgetdate like '%{$bulanlalu}%' and x.accountid = z.accountid and x.companyid = z.companyid),0) as budgetlalu,
            ifnull((select budgetamount
            from budget x where x.budgetdate like '%{$bulanini}%' and x.accountid = z.accountid and x.companyid = z.companyid),0) as budgetnow,
            ifnull((select if(z.budgetamount<0,sum(d.debit-d.credit),sum(d.credit-d.debit))
            from genledger d 
            join genjournal e on e.genjournalid=d.genjournalid 
            where e.recordstatus=3 and d.accountid=z.accountid and e.journaldate between '{$bulanlalu}' and last_day('{$bulanlalu}')),0) as realisasilalu,
            ifnull((select if(z.budgetamount<0,sum(d.debit-d.credit),sum(d.credit-d.debit)) 
            from genledger d 
            join genjournal e on e.genjournalid=d.genjournalid 
            where e.recordstatus=3 and d.accountid=z.accountid and e.journaldate between '{$bulanini}' and last_day('{$bulanini}')),0) as realisasinow,
            ifnull((select if(z.budgetamount<0,sum(d.debit-d.credit),sum(d.credit-d.debit))
            from genledger d 
            join genjournal e on e.genjournalid=d.genjournalid 
            where e.recordstatus=3 and d.accountid=z.accountid and e.journaldate between CONCAT(YEAR(z.budgetdate),'-01-01') 
            and last_day('{$bulanini}')),0) as kumrealisasi,
            ifnull((select sum(budgetamount)
            from budget y
            where y.accountid = z.accountid 
            and y.companyid = z.companyid
            and y.budgetdate between concat(year('{$bulanini}'),'-01-01') and last_day('{$bulanini}')),0) as kumbudget,accountcode
            from (select a.budgetid,a.budgetdate,b.accountname,b.accountcode, a.accountid, a.companyid, budgetamount
            from budget a
            join account b on b.accountid = a.accountid 
            join company c on c.companyid = a.companyid 
            where coalesce(a.budgetid,'') like '%%' 
            and coalesce(a.budgetdate,'') like '%{$bulanlalu}%'
            and coalesce(c.companyname,'') like '%{$companyname}%'
            and coalesce(b.accountname,'') like '%{$accountname}%'
            and coalesce(b.accountcode,'') like '%{$accountcode}%'
            union
            select a.budgetid,a.budgetdate,b.accountname,b.accountcode, a.accountid, a.companyid, budgetamount
            from budget a
            join account b on b.accountid = a.accountid 
            join company c on c.companyid = a.companyid 
            where coalesce(a.budgetid,'') like '%%' 
            and coalesce(a.budgetdate,'') like '%{$bulanini}%'
            and coalesce(c.companyname,'') like '%{$companyname}%'
            and coalesce(b.accountname,'') like '%{$accountname}%'
            and coalesce(b.accountcode,'') like '%{$accountcode}%'
            ) z group by accountid order by budgetdate,accountcode asc";
    //$sql = $sql . " order by accountname asc ";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row1)
		{
			$this->pdf->companyid = $row1['companyid'];
		}
    $this->pdf->title = GetCatalog('budget');
    $this->pdf->AddPage('L',array(220,335));
    $this->pdf->setFont('Arial','B',9);
    $this->pdf->colalign  = array(
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
    );
    $this->pdf->colheader = array(
      GetCatalog('budgetdate'),
      GetCatalog('accountname'),
      GetCatalog('Budget Bulan Ini'),
      GetCatalog('Pemakaian Bulan Ini'),
      GetCatalog('Budget Bulan Lalu'),
      GetCatalog('Pemakaian Bulan Lalu'),
      ('Kumulatif Budget'),
      ('Kumulatif Pemakaian')
    );
    $this->pdf->setwidths(array(
      27,
      63,
      35,
      35,
      35,
      36,
      40,
      40
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial','',9);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        date(Yii::app()->params['dateviewfromdb'],strtotime($bulanini)),
        $row1['accountname'],
        Yii::app()->format->formatCurrency($row1['budgetnow']),
        Yii::app()->format->formatCurrency($row1['realisasinow']),
        Yii::app()->format->formatCurrency($row1['budgetlalu']),
        Yii::app()->format->formatCurrency($row1['realisasilalu']),
        Yii::app()->format->formatCurrency($row1['kumbudget']),
        Yii::app()->format->formatCurrency($row1['kumrealisasi']),
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'budget';
    parent::actionDownxls();
    $budgetid = filter_input(INPUT_GET,'budgetid');
	$budgetdate = filter_input(INPUT_GET,'budgetdate');
	$companyname = filter_input(INPUT_GET,'companyname');
	$accountname = filter_input(INPUT_GET,'accountname');
	$accountcode = filter_input(INPUT_GET,'accountcode');
    $year = date('Y',strtotime($budgetdate));
    $month = date('m',strtotime($budgetdate));
    $day1 = strtotime(''.$year.'-'.$month.'-01');
    $day2 = strtotime(''.$year.'-'.$month.'-01 -1 month');
    $bulanini = date('Y-m-d',($day1));
    $bulanlalu = date('Y-m-d',($day2));
    /*
    $sql = "select a.budgetid,a.budgetdate,c.companycode,b.accountcode,b.accountname,a.budgetamount,
            ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between DATE_ADD(DATE_ADD(LAST_DAY(a.budgetdate),INTERVAL 1 DAY),INTERVAL - 1 MONTH) and LAST_DAY(a.budgetdate)),0) as realisasi,
            ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between CONCAT(YEAR(a.budgetdate),'-01-01') and LAST_DAY(a.budgetdate)),0) as kumrealisasi
						from budget a
						join account b on b.accountid = a.accountid
            join company c on c.companyid = a.companyid ";
		$budgetid = filter_input(INPUT_GET,'budgetid');
		$budgetdate = filter_input(INPUT_GET,'budgetdate');
		$companyname = filter_input(INPUT_GET,'companyname');
		$accountname = filter_input(INPUT_GET,'accountname');
		$accountcode = filter_input(INPUT_GET,'accountcode');
		$sql .= " where coalesce(a.budgetid,'') like '%".$budgetid."%' 
			and coalesce(a.budgetdate,'') like '%".$budgetdate."%'
			and coalesce(c.companyname,'') like '%".$companyname."%'
			and coalesce(b.accountname,'') like '%".$accountname."%'
			and coalesce(b.accountcode,'') like '%".$accountcode."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.budgetid in (" . $_GET['id'] . ")";
    } 
    $sql = $sql . " order by accountname asc ";
    */
    $sql = "select budgetid, accountid, accountname, budgetdate,companyid,companycode,accountcode,
            ifnull((select budgetamount
            from budget x where x.budgetdate like '%{$bulanlalu}%' and x.accountid = z.accountid and x.companyid = z.companyid),0) as budgetlalu,
            ifnull((select budgetamount
            from budget x where x.budgetdate like '%{$bulanini}%' and x.accountid = z.accountid and x.companyid = z.companyid),0) as budgetnow,
            ifnull((select if(z.budgetamount<0,sum(d.debit-d.credit),sum(d.credit-d.debit))
            from genledger d 
            join genjournal e on e.genjournalid=d.genjournalid 
            where e.recordstatus=3 and d.accountid=z.accountid and e.journaldate between '{$bulanlalu}' and last_day('{$bulanlalu}')),0) as realisasilalu,
            ifnull((select if(z.budgetamount<0,sum(d.debit-d.credit),sum(d.credit-d.debit)) 
            from genledger d 
            join genjournal e on e.genjournalid=d.genjournalid 
            where e.recordstatus=3 and d.accountid=z.accountid and e.journaldate between '{$bulanini}' and last_day('{$bulanini}')),0) as realisasinow,
            ifnull((select if(z.budgetamount<0,sum(d.debit-d.credit),sum(d.credit-d.debit))
            from genledger d 
            join genjournal e on e.genjournalid=d.genjournalid 
            where e.recordstatus=3 and d.accountid=z.accountid and e.journaldate between CONCAT(YEAR(z.budgetdate),'-01-01') 
            and last_day('{$bulanini}')),0) as kumrealisasi,
            ifnull((select sum(budgetamount)
            from budget y
            where y.accountid = z.accountid 
            and y.companyid = z.companyid
            and y.budgetdate between concat(year('{$bulanini}'),'-01-01') and last_day('{$bulanini}')),0) as kumbudget
            from (select a.budgetid,a.budgetdate,b.accountname, a.accountid, a.companyid, budgetamount, companycode, b.accountcode
            from budget a
            join account b on b.accountid = a.accountid 
            join company c on c.companyid = a.companyid 
            where coalesce(a.budgetid,'') like '%%' 
            and coalesce(a.budgetdate,'') like '%{$bulanlalu}%'
            and coalesce(c.companyname,'') like '%{$companyname}%'
            and coalesce(b.accountname,'') like '%{$accountname}%'
            and coalesce(b.accountcode,'') like '%{$accountcode}%'
            union
            select a.budgetid,a.budgetdate,b.accountname, a.accountid, a.companyid, budgetamount, companycode, b.accountcode
            from budget a
            join account b on b.accountid = a.accountid 
            join company c on c.companyid = a.companyid 
            where coalesce(a.budgetid,'') like '%%' 
            and coalesce(a.budgetdate,'') like '%{$bulanini}%'
            and coalesce(c.companyname,'') like '%{$companyname}%'
            and coalesce(b.accountname,'') like '%{$accountname}%'
            and coalesce(b.accountcode,'') like '%{$accountcode}%'
            ) z group by accountid order by budgetdate,accountcode asc";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['budgetid'])->setCellValueByColumnAndRow(1, $i, date(Yii::app()->params['dateviewfromdb'],strtotime($bulanini)))->setCellValueByColumnAndRow(2, $i, $row1['companycode'])->setCellValueByColumnAndRow(3, $i, $row1['accountcode'])->setCellValueByColumnAndRow(4, $i, $row1['accountname'])->setCellValueByColumnAndRow(5, $i, $row1['budgetnow'])->setCellValueByColumnAndRow(6, $i, $row1['realisasinow'])->setCellValueByColumnAndRow(7, $i, $row1['budgetlalu'])->setCellValueByColumnAndRow(8, $i, $row1['realisasilalu'])->setCellValueByColumnAndRow(9, $i, $row1['kumbudget'])->setCellValueByColumnAndRow(10, $i, $row1['kumrealisasi']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}