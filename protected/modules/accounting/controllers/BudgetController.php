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
    $plantcode  = isset($_POST['plantcode'])  ? $_POST['plantcode'] : '';
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
		$cmd = Yii::app()->db->createCommand()->select('t.*,p.accountcode,p.accountname,q.companyname,(select plantcode from plant a where a.plantid = t.plantid) as plantcode,
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
			//->leftjoin('plant r', 'r.plantid=t.plantid')
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
        'companyname' => $data['companyname'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode']
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
			$sql     = 'call InsertBudget(:vbudgetdate, :vcompanyid, :vplantid, :vaccountid, :vbudgetamount, :vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call UpdateBudget(:vid, :vbudgetdate, :vcompanyid, :vplantid, :vaccountid, :vbudgetamount, :vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vbudgetdate', $arraydata[1], PDO::PARAM_STR);
        $command->bindvalue(':vcompanyid', $arraydata[2], PDO::PARAM_STR);
        $command->bindvalue(':vaccountid', $arraydata[3], PDO::PARAM_STR);
		$command->bindvalue(':vbudgetamount', $arraydata[4], PDO::PARAM_STR);
		$command->bindvalue(':vplantid', $arraydata[5], PDO::PARAM_STR);
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
          $plantcode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
						$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$accountcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
						$accountid = Yii::app()->db->createCommand("select accountid from account where companyid = ".$companyid." and accountcode = '".$accountcode."'")->queryScalar();
					$budgetamount = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,date(Yii::app()->params['datetodb'], strtotime($budgetdate)),$companyid,$accountid,$budgetamount,$plantid));
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
			$this->ModifyData($connection,array((isset($_POST['budgetid'])?$_POST['budgetid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['budgetdate'])),$_POST['companyid'],$_POST['accountid'],$_POST['budgetamount'],$_POST['plantid']));
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
    
    $sql = "
            select a.budgetid,a.budgetdate,b.accountname,b.accountcode, a.accountid, a.companyid, budgetamount, c.companyname, d.plantcode
            from budget a
            join account b on b.accountid = a.accountid 
            join company c on c.companyid = a.companyid 
            left join plant d on d.plantid = a.plantid
            where coalesce(a.budgetid,'') like '%%' 
            and coalesce(a.budgetdate,'') like '%{$bulanini}%'
            and coalesce(c.companyname,'') like '%{$companyname}%'
            and coalesce(b.accountname,'') like '%{$accountname}%'
            and coalesce(b.accountcode,'') like '%{$accountcode}%'
            -- ) z group by accountid order by budgetdate,accountcode asc";
    //$sql = $sql . " order by accountname asc ";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row1)
		{
			$this->pdf->companyid = $row1['companyid'];
		}
    $this->pdf->title = GetCatalog('budget');
    $this->pdf->AddPage('P',array(240,335));
    $this->pdf->setFont('Arial','B',9);
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'C',
      'R',
    );
    $this->pdf->colheader = array(
      GetCatalog('budgetdate'),
      GetCatalog('company'),
      GetCatalog('plant'),
      GetCatalog('account'),
      GetCatalog('Budget')
    );
    $this->pdf->setwidths(array(
      27,
      73,
      25,
      60,
      35,
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial','',9);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'R'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        date(Yii::app()->params['dateviewfromdb'],strtotime($row1['budgetdate'])),
        $row1['companyname'],
        $row1['plantcode'],
        $row1['accountname'],
        Yii::app()->format->formatCurrency($row1['budgetamount']),   
      ));
    }
    $this->pdf->Output();
    
  }
  public function actionDownPDFbudget() {
    parent::actionDownload();
    $budgetid = filter_input(INPUT_GET,'budgetid');
	$budgetdate = filter_input(INPUT_GET,'budgetdate');
	$companyid = filter_input(INPUT_GET,'companyid');
	$plantid = filter_input(INPUT_GET,'plantid');
    $year = date('Y',strtotime($budgetdate));
    $month = date('m',strtotime($budgetdate));
    $day1 = strtotime(''.$year.'-'.$month.'-01');
    $day2 = strtotime(''.$year.'-'.$month.'-01 -1 month');
    $blnini = date('Y-m-d',($day1));
    $blnlalu = date('Y-m-d',($day2));
    
    /*
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
    */
    //$sql = $sql . " order by accountname asc ";
    $sql = "SELECT noquery,budgetid,budgetdate,accountid,accountcode,accountname,companyid,companycode,companyname,plantid,plantcode,sum(budgetblnini) AS budgetblnini,SUM(realblnini) AS realblnini,SUM(budgetblnlalu) AS budgetblnlalu,SUM(realblnlalu) AS realblnlalu,SUM(kumbudget) AS kumbudget,SUM(kumreal) AS kumreal
            FROM (

            SELECT 1 as noquery,a1.budgetid,a1.budgetdate,a1.accountid,a1.accountcode,b1.accountname,a1.companyid,c1.companycode,c1.companyname,a1.plantid,d1.plantcode,a1.budgetamount AS budgetblnini,0 AS realblnini,0 AS budgetblnlalu,0 AS realblnlalu,0 AS kumbudget,0 AS kumreal
            FROM budget a1
            JOIN account b1 ON b1.accountid=a1.accountid
            JOIN company c1 ON c1.companyid=a1.companyid
            LEFT JOIN plant d1 ON d1.plantid=a1.plantid
            WHERE c1.companyid = {$companyid}
            ".($plantid != '' ? ' and d1.plantid = '.$plantid : '')."
            and a1.budgetdate = '".date(Yii::app()->params['datetodb'],strtotime($blnini))."'
            GROUP BY a1.accountid,a1.plantid

            UNION

            SELECT 2 AS noquery,'' AS budgetid,'' as budgetdate,a2.accountid,a2.accountcode,a2.accountname,a2.companyid,c2.companycode,a2.companyname,a2.plantid,d2.plantcode,0 AS budgetblnini,SUM(a2.debit-a2.credit) AS realblnini,0 AS budgetblnlalu,0 AS realblnlalu,0 AS kumbudget,0 AS kumreal
            FROM genledger a2
            JOIN account b2 ON b2.accountid = a2.accountid
            JOIN company c2 ON c2.companyid=a2.companyid
            LEFT JOIN plant d2 ON d2.plantid=a2.plantid
            WHERE b2.accountcode BETWEEN '3' AND '9999999999'
            AND b2.accounttypeid = 2
            and MONTH(a2.journaldate) = MONTH('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and YEAR(a2.journaldate) = YEAR('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and c2.companyid = {$companyid}
            ".($plantid != '' ? ' and d2.plantid = '.$plantid : '')."
            GROUP BY a2.accountid,a2.plantid

            UNION

            SELECT 3 as noquery,'' AS budgetid,a3.budgetdate,a3.accountid,a3.accountcode,b3.accountname,a3.companyid,c3.companycode,c3.companyname,a3.plantid,d3.plantcode,0 AS budgetblnini,0 AS realblnini,a3.budgetamount AS budgetblnlalu,0 AS realblnlalu,0 AS kumbudget,0 AS kumreal
            FROM budget a3
            JOIN account b3 ON b3.accountid=a3.accountid
            JOIN company c3 ON c3.companyid=a3.companyid
            LEFT JOIN plant d3 ON d3.plantid=a3.plantid
            WHERE c3.companyid = {$companyid}
            ".($plantid != '' ? ' and d3.plantid = '.$plantid : '')."
            and a3.budgetdate = '".date(Yii::app()->params['datetodb'],strtotime($blnlalu))."'
            GROUP BY a3.accountid,a3.plantid

            UNION

            SELECT 4 AS noquery,'' AS budgetid,'' as budgetdate,a4.accountid,a4.accountcode,a4.accountname,a4.companyid,c4.companycode,a4.companyname,a4.plantid,d4.plantcode,0 AS budgetblnini,0 AS realblnini,0 AS budgetblnlalu,SUM(a4.debit-a4.credit) AS realblnlalu,0 AS kumbudget,0 AS kumreal
            FROM genledger a4
            JOIN account b4 ON b4.accountid = a4.accountid
            JOIN company c4 ON c4.companyid=a4.companyid
            LEFT JOIN plant d4 ON d4.plantid=a4.plantid
            WHERE b4.accountcode BETWEEN '3' AND '9999999999'
            AND b4.accounttypeid = 2
            and MONTH(a4.journaldate) = MONTH('".date(Yii::app()->params['datetodb'],strtotime($blnlalu))."')
            and YEAR(a4.journaldate) = YEAR('".date(Yii::app()->params['datetodb'],strtotime($blnlalu))."')
            and c4.companyid = {$companyid}
            ".($plantid != '' ? ' and d4.plantid = '.$plantid : '')."
            GROUP BY a4.accountid,a4.plantid

            UNION

            SELECT 5 as noquery,'' AS budgetid,a5.budgetdate,a5.accountid,a5.accountcode,b5.accountname,a5.companyid,c5.companycode,c5.companyname,a5.plantid,d5.plantcode,0 AS budgetblnini,0 AS realblnini,0 AS budgetblnlalu,0 AS realblnlalu,sum(a5.budgetamount) AS kumbudget,0 AS kumreal
            FROM budget a5
            JOIN account b5 ON b5.accountid=a5.accountid
            JOIN company c5 ON c5.companyid=a5.companyid
            LEFT JOIN plant d5 ON d5.plantid=a5.plantid
            WHERE c5.companyid = {$companyid}
            ".($plantid != '' ? ' and d5.plantid = '.$plantid : '')."
            and month(a5.budgetdate) BETWEEN 1 AND MONTH('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and year(a5.budgetdate) = YEAR('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            GROUP BY a5.accountid,a5.plantid

            UNION

            SELECT 6 AS noquery,'' AS budgetid,'' as budgetdate,a6.accountid,a6.accountcode,a6.accountname,a6.companyid,c6.companycode,a6.companyname,a6.plantid,d6.plantcode,0 AS budgetblnini,0 AS realblnini,0 AS budgetblnlalu,0 AS realblnlalu,0 AS kumbudget,SUM(a6.debit-a6.credit) AS kumreal
            FROM genledger a6
            JOIN account b6 ON b6.accountid = a6.accountid
            JOIN company c6 ON c6.companyid=a6.companyid
            LEFT JOIN plant d6 ON d6.plantid=a6.plantid
            WHERE b6.accountcode BETWEEN '3' AND '9999999999'
            AND b6.accounttypeid = 2
            and month(a6.journaldate) BETWEEN 1 AND MONTH('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and year(a6.journaldate) = YEAR('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and c6.companyid = {$companyid}
            ".($plantid != '' ? ' and d6.plantid = '.$plantid : '')."
            GROUP BY a6.accountid,a6.plantid
            ) z
            GROUP BY accountid
            ORDER BY accountcode";
      
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row1)
		{
			$this->pdf->companyid = $row1['companyid'];
		}
    if($plantid != '') {
        $plantcode = Yii::app()->db->createCommand('select plantcode from plant where plantid = '.$plantid)->queryScalar();
    }
    $this->pdf->title = GetCatalog('budget').' '.GetCompanyCode($companyid).($plantid != '' ? ' PLANT : '.$plantcode : '');
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
        ($row1['budgetdate'] != '' ? date(Yii::app()->params['dateviewfromdb'],strtotime($row1['budgetdate'])) : ' '),
        $row1['accountname'],
        Yii::app()->format->formatCurrency($row1['budgetblnini']),
        Yii::app()->format->formatCurrency($row1['realblnini']),
        Yii::app()->format->formatCurrency($row1['budgetblnlalu']),
        Yii::app()->format->formatCurrency($row1['realblnlalu']),
        Yii::app()->format->formatCurrency($row1['kumbudget']),
        Yii::app()->format->formatCurrency($row1['kumreal']),
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownXLS() {
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
    $sql = "
            select a.budgetid,a.budgetdate,b.accountname, a.accountid, a.companyid, budgetamount, companycode, b.accountcode,a.plantid,plantcode
            from budget a
            join account b on b.accountid = a.accountid 
            join company c on c.companyid = a.companyid
            left join plant d on d.plantid = a.plantid
            where coalesce(a.budgetid,'') like '%%' 
            and coalesce(a.budgetdate,'') like '%{$bulanini}%'
            and coalesce(c.companyname,'') like '%{$companyname}%'
            and coalesce(b.accountname,'') like '%{$accountname}%'
            and coalesce(b.accountcode,'') like '%{$accountcode}%'
            -- ) z group by accountid order by budgetdate,accountcode asc";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, $row1['budgetid'])
          ->setCellValueByColumnAndRow(1, $i, date(Yii::app()->params['dateviewfromdb'],strtotime($bulanini)))
          ->setCellValueByColumnAndRow(2, $i, $row1['companycode'])
          ->setCellValueByColumnAndRow(3,$i, $row1['plantcode'])
          ->setCellValueByColumnAndRow(4, $i, $row1['accountcode'])
          ->setCellValueByColumnAndRow(5, $i, $row1['accountname'])
          ->setCellValueByColumnAndRow(6, $i, $row1['budgetamount']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
	public function actionDownXLSbudget() {
    $this->menuname = 'budget';
    parent::actionDownxls();
    $id = filter_input(INPUT_GET,'id');
    $budgetid = filter_input(INPUT_GET,'budgetid');
	$budgetdate = filter_input(INPUT_GET,'budgetdate');
	$companyid = filter_input(INPUT_GET,'companyid');
	$plantid = filter_input(INPUT_GET,'plantid');
    $year = date('Y',strtotime($budgetdate));
    $month = date('m',strtotime($budgetdate));
    $day1 = strtotime(''.$year.'-'.$month.'-01');
    $day2 = strtotime(''.$year.'-'.$month.'-01 -1 month');
    $blnini = date('Y-m-d',($day1));
    $blnlalu = date('Y-m-d',($day2));
	
        
    $sql = "SELECT noquery,budgetid,budgetdate,accountid,accountcode,accountname,companyid,companycode,companyname,plantid,plantcode,sum(budgetblnini) AS budgetblnini,SUM(realblnini) AS realblnini,SUM(budgetblnlalu) AS budgetblnlalu,SUM(realblnlalu) AS realblnlalu,SUM(kumbudget) AS kumbudget,SUM(kumreal) AS kumreal
            FROM (

            SELECT 1 as noquery,a1.budgetid,a1.budgetdate,a1.accountid,a1.accountcode,b1.accountname,a1.companyid,c1.companycode,c1.companyname,a1.plantid,d1.plantcode,a1.budgetamount AS budgetblnini,0 AS realblnini,0 AS budgetblnlalu,0 AS realblnlalu,0 AS kumbudget,0 AS kumreal
            FROM budget a1
            JOIN account b1 ON b1.accountid=a1.accountid
            JOIN company c1 ON c1.companyid=a1.companyid
            LEFT JOIN plant d1 ON d1.plantid=a1.plantid
            WHERE c1.companyid = {$companyid}
            ".($plantid != '' ? ' and d1.plantid = '.$plantid : '')."
            and a1.budgetdate = '".date(Yii::app()->params['datetodb'],strtotime($blnini))."'
            GROUP BY a1.accountid,a1.plantid

            UNION

            SELECT 2 AS noquery,'' AS budgetid,'' as budgetdate,a2.accountid,a2.accountcode,a2.accountname,a2.companyid,c2.companycode,a2.companyname,a2.plantid,d2.plantcode,0 AS budgetblnini,SUM(a2.debit-a2.credit) AS realblnini,0 AS budgetblnlalu,0 AS realblnlalu,0 AS kumbudget,0 AS kumreal
            FROM genledger a2
            JOIN account b2 ON b2.accountid = a2.accountid
            JOIN company c2 ON c2.companyid=a2.companyid
            LEFT JOIN plant d2 ON d2.plantid=a2.plantid
            WHERE b2.accountcode BETWEEN '3' AND '9999999999'
            AND b2.accounttypeid = 2
            and MONTH(a2.journaldate) = MONTH('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and YEAR(a2.journaldate) = YEAR('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and c2.companyid = {$companyid}
            ".($plantid != '' ? ' and d2.plantid = '.$plantid : '')."
            GROUP BY a2.accountid,a2.plantid

            UNION

            SELECT 3 as noquery,'' AS budgetid,a3.budgetdate,a3.accountid,a3.accountcode,b3.accountname,a3.companyid,c3.companycode,c3.companyname,a3.plantid,d3.plantcode,0 AS budgetblnini,0 AS realblnini,a3.budgetamount AS budgetblnlalu,0 AS realblnlalu,0 AS kumbudget,0 AS kumreal
            FROM budget a3
            JOIN account b3 ON b3.accountid=a3.accountid
            JOIN company c3 ON c3.companyid=a3.companyid
            LEFT JOIN plant d3 ON d3.plantid=a3.plantid
            WHERE c3.companyid = {$companyid}
            ".($plantid != '' ? ' and d3.plantid = '.$plantid : '')."
            and a3.budgetdate = '".date(Yii::app()->params['datetodb'],strtotime($blnlalu))."'
            GROUP BY a3.accountid,a3.plantid

            UNION

            SELECT 4 AS noquery,'' AS budgetid,'' as budgetdate,a4.accountid,a4.accountcode,a4.accountname,a4.companyid,c4.companycode,a4.companyname,a4.plantid,d4.plantcode,0 AS budgetblnini,0 AS realblnini,0 AS budgetblnlalu,SUM(a4.debit-a4.credit) AS realblnlalu,0 AS kumbudget,0 AS kumreal
            FROM genledger a4
            JOIN account b4 ON b4.accountid = a4.accountid
            JOIN company c4 ON c4.companyid=a4.companyid
            LEFT JOIN plant d4 ON d4.plantid=a4.plantid
            WHERE b4.accountcode BETWEEN '3' AND '9999999999'
            AND b4.accounttypeid = 2
            and MONTH(a4.journaldate) = MONTH('".date(Yii::app()->params['datetodb'],strtotime($blnlalu))."')
            and YEAR(a4.journaldate) = YEAR('".date(Yii::app()->params['datetodb'],strtotime($blnlalu))."')
            and c4.companyid = {$companyid}
            ".($plantid != '' ? ' and d4.plantid = '.$plantid : '')."
            GROUP BY a4.accountid,a4.plantid

            UNION

            SELECT 5 as noquery,'' AS budgetid,a5.budgetdate,a5.accountid,a5.accountcode,b5.accountname,a5.companyid,c5.companycode,c5.companyname,a5.plantid,d5.plantcode,0 AS budgetblnini,0 AS realblnini,0 AS budgetblnlalu,0 AS realblnlalu,sum(a5.budgetamount) AS kumbudget,0 AS kumreal
            FROM budget a5
            JOIN account b5 ON b5.accountid=a5.accountid
            JOIN company c5 ON c5.companyid=a5.companyid
            LEFT JOIN plant d5 ON d5.plantid=a5.plantid
            WHERE c5.companyid = {$companyid}
            ".($plantid != '' ? ' and d5.plantid = '.$plantid : '')."
            and month(a5.budgetdate) BETWEEN 1 AND MONTH('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and year(a5.budgetdate) = YEAR('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            GROUP BY a5.accountid,a5.plantid

            UNION

            SELECT 6 AS noquery,'' AS budgetid,'' as budgetdate,a6.accountid,a6.accountcode,a6.accountname,a6.companyid,c6.companycode,a6.companyname,a6.plantid,d6.plantcode,0 AS budgetblnini,0 AS realblnini,0 AS budgetblnlalu,0 AS realblnlalu,0 AS kumbudget,SUM(a6.debit-a6.credit) AS kumreal
            FROM genledger a6
            JOIN account b6 ON b6.accountid = a6.accountid
            JOIN company c6 ON c6.companyid=a6.companyid
            LEFT JOIN plant d6 ON d6.plantid=a6.plantid
            WHERE b6.accountcode BETWEEN '3' AND '9999999999'
            AND b6.accounttypeid = 2
            and month(a6.journaldate) BETWEEN 1 AND MONTH('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and year(a6.journaldate) = YEAR('".date(Yii::app()->params['datetodb'],strtotime($blnini))."')
            and c6.companyid = {$companyid}
            ".($plantid != '' ? ' and d6.plantid = '.$plantid : '')."
            GROUP BY a6.accountid,a6.plantid
            ) z
            GROUP BY accountid
            ORDER BY accountcode";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, $row1['budgetid'])
          ->setCellValueByColumnAndRow(1, $i, date(Yii::app()->params['dateviewfromdb'],strtotime($row1['budgetdate'])))
          ->setCellValueByColumnAndRow(2, $i, $row1['companycode'])
          ->setCellValueByColumnAndRow(3, $i, $row1['plantcode'])
          ->setCellValueByColumnAndRow(4, $i, $row1['accountcode'])
          ->setCellValueByColumnAndRow(5, $i, $row1['accountname'])
          ->setCellValueByColumnAndRow(6, $i, $row1['budgetblnini'])
          ->setCellValueByColumnAndRow(7, $i, $row1['realblnini'])
          ->setCellValueByColumnAndRow(8, $i, $row1['budgetblnlalu'])
          ->setCellValueByColumnAndRow(9, $i, $row1['realblnlalu'])
          ->setCellValueByColumnAndRow(10, $i, $row1['kumbudget'])
          ->setCellValueByColumnAndRow(11, $i, $row1['kumreal']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}