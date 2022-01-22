<?php
class FohulController extends Controller {
  public $menuname = 'fohul';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $fohulid   = isset($_POST['fohulid']) ? $_POST['fohulid'] : '';
    $perioddatey        = isset($_POST['perioddateyear']) ? $_POST['perioddateyear'] : '';
    $perioddatem        = isset($_POST['perioddatemonth']) ? $_POST['perioddatemonth'] : '';
    $companyname    = isset($_POST['companyname'])  ? $_POST['companyname'] : '';
    $plantfromcode  = isset($_POST['plantfromcode'])  ? $_POST['plantfromcode'] : '';
    $planttocode    = isset($_POST['planttocode'])  ? $_POST['planttocode'] : '';
    $description       = isset($_POST['mgprocess']) ? $_POST['mgprocess'] : '';
    $plantcode       = isset($_POST['plantcode']) ? $_POST['plantcode'] : '';
    $recordstatus   = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'fohulid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
    $perioddate = '';
    if($perioddatey != '') {
        $perioddate .= " and year(perioddate) = year('{$perioddatey}-01-01') ";
    }
    if($perioddatem != '') {
        $perioddate .= " and month(perioddate) = month('2020-{$perioddatem}-01') ";
    }
    $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('fohul t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('plant b', 'b.plantid=t.plantid')
    //->leftjoin('materialgroup c', 'c.materialgroupid=t.materialgroupid')
    ->leftjoin('mgprocess c', 'c.mgprocessid=t.mgprocessid')
    ->where("((fohulid like :fohulid) $perioddate and (a.companyname like :companyname) and (coalesce(c.description,'') like :description)) and ((coalesce(b.plantcode,'') like :plantcode)) and
            t.companyid in (".getUserObjectValues('company').")", array(
        ':fohulid' => '%' . $fohulid . '%',
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
        ':description' => '%' . $description . '%',
        ':plantcode' => '%' . $plantcode . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,c.description,b.plantcode, 
        ifnull((select foh from fohul x where x.companyid = t.companyid and x.plantid = t.plantid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as fohlast1,
        ifnull((select foh from fohul x where x.companyid = t.companyid and x.plantid = t.plantid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as fohlast2,
        ifnull((select ul from fohul x where x.companyid = t.companyid and x.plantid = t.plantid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as ullast1,
        ifnull((select ul from fohul x where x.companyid = t.companyid and x.plantid = t.plantid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as ullast2,
        ifnull((select qtyoutput from fohul x where x.companyid = t.companyid and x.plantid = t.plantid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as qtyoutput1,
        ifnull((select qtyoutput from fohul x where x.companyid = t.companyid and x.plantid = t.plantid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as qtyoutput2,
        ifnull((select ctoutput from fohul x where x.companyid = t.companyid and x.plantid = t.plantid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as ctoutput1,
        ifnull((select ctoutput from fohul x where x.companyid = t.companyid and x.plantid = t.plantid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as ctoutput2')
    ->from('fohul t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('plant b', 'b.plantid=t.plantid')
    //->leftjoin('materialgroup c', 'c.materialgroupid=t.materialgroupid')
    ->leftjoin('mgprocess c', 'c.mgprocessid=t.mgprocessid')
    ->where("((fohulid like :fohulid) $perioddate and (a.companyname like :companyname) and (coalesce(c.description,'') like :description)) and ((coalesce(b.plantcode,'') like :plantcode)) and
            t.companyid in (".getUserObjectValues('company').")", array(
        ':fohulid' => '%' . $fohulid . '%',
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
        ':description' => '%' . $description . '%',
        ':plantcode' => '%' . $plantcode . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'fohulid' => $data['fohulid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'perioddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['perioddate'])),
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'mgprocessid' => $data['mgprocessid'],
        'description' => $data['description'],
        'plantid' => $data['plantid'],
        'plantcode' => $data['plantcode'],
        'foh' => Yii::app()->format->formatCurrency($data['foh']),
        'ul' => Yii::app()->format->formatCurrency($data['ul']),
        'fohgen' => Yii::app()->format->formatCurrency($data['fohgen']),
        'fohlast1' => Yii::app()->format->formatCurrency($data['fohlast1']),
        'fohlast2' => Yii::app()->format->formatCurrency($data['fohlast2']),
        'ulgen' => Yii::app()->format->formatCurrency($data['ulgen']),
        'ullast1' => Yii::app()->format->formatCurrency($data['ullast1']),
        'ullast2' => Yii::app()->format->formatCurrency($data['ullast2']),
        'totalfoh' => Yii::app()->format->formatCurrency($data['totalfoh']),
        'totalul' => Yii::app()->format->formatCurrency($data['totalul']),
        'qtyoutput' => Yii::app()->format->formatCurrency($data['qtyoutput']),
        'qtyoutput1' => Yii::app()->format->formatCurrency($data['qtyoutput1']),
        'qtyoutput2' => Yii::app()->format->formatCurrency($data['qtyoutput2']),
        'ctoutput' => Yii::app()->format->formatCurrency($data['ctoutput']),
        'ctoutput1' => Yii::app()->format->formatCurrency($data['ctoutput1']),
        'ctoutput2' => Yii::app()->format->formatCurrency($data['ctoutput2']),
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
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
			$sql     = 'call Insertfohul(:vdocdate,:vperioddate,:vcompanyid,:vplantid,:vmgprocessid,:vfoh,:vul,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatefohul(:vid,:vdocdate,:vperioddate,:vcompanyid,:vplantid,:vmgprocessid,:vfoh,:vul,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vdocdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vperioddate', $arraydata[2], PDO::PARAM_STR);
        $command->bindvalue(':vcompanyid', $arraydata[3], PDO::PARAM_STR);
        $command->bindvalue(':vplantid', $arraydata[4], PDO::PARAM_STR);
        $command->bindvalue(':vmgprocessid', $arraydata[5], PDO::PARAM_STR);        
		$command->bindvalue(':vfoh', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vul', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
    $target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-fohul"]["name"]);
    if (move_uploaded_file($_FILES["file-fohul"]["tmp_name"], $target_file)) {
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objPHPExcel = $objReader->load($target_file);
      $objWorksheet = $objPHPExcel->getActiveSheet();
      $highestRow = $objWorksheet->getHighestRow(); 
      $highestColumn = $objWorksheet->getHighestColumn();
      $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
			try {
				for ($row = 4; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$docdate = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$perioddate = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
                    $plantcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$plantid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$plantcode."'")->queryScalar();
					$mgprocess = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $mgprocessid = Yii::app()->db->createCommand("select mgprocessid from mgprocess where description = '".$mgprocess."'")->queryScalar();
                    $foh = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $ul = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $recordstatus = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
					$this->ModifyData($connection,array($id,date(Yii::app()->params['datetodb'], strtotime($docdate)),date(Yii::app()->params['datetodb'], strtotime($perioddate)),$companyid,$plantid,$mgprocessid,$foh,$ul));
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
			$this->ModifyData($connection,array((isset($_POST['fohulid'])?$_POST['fohulid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),date(Yii::app()->params['datetodb'], strtotime($_POST['perioddate'])),$_POST['companyid'],$_POST['plantid'],$_POST['mgprocessid'],$_POST['foh'],$_POST['ul']));
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionApprove() {
    parent::actionApprove();
    if(isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvefohul(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
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
  public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Deletefohul(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
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
  public function actionPurge() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgefohul(:vid,:vcreatedby)';
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
    //$fohulid = filter_input(INPUT_GET,'fohulid');
    $fohulid = filter_input(INPUT_GET,'id');
	$perioddate = filter_input(INPUT_GET,'perioddate');
	$companyname = filter_input(INPUT_GET,'companyname');
	$description = filter_input(INPUT_GET,'mgprocess');
	$plantcode = filter_input(INPUT_GET,'plantcode');
    
    
    
    /*$sql = "select a.fohulid,a.perioddate,b.accountname,a.expeditionamount,a.companyid,ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between DATE_ADD(DATE_ADD(LAST_DAY(a.perioddate),INTERVAL 1 DAY),INTERVAL - 1 MONTH) and LAST_DAY(a.perioddate)),0) as realisasi,
            ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between CONCAT(YEAR(a.perioddate),'-01-01') and LAST_DAY(a.perioddate)),0) as kumrealisasi
						from expedition a
						join account b on b.accountid = a.accountid 
						join company c on c.companyid = a.companyid ";
		$fohulid = filter_input(INPUT_GET,'fohulid');
		$perioddate = filter_input(INPUT_GET,'perioddate');
		$companyname = filter_input(INPUT_GET,'companyname');
		$accountname = filter_input(INPUT_GET,'accountname');
		$accountcode = filter_input(INPUT_GET,'accountcode');
		$sql .= " where coalesce(a.fohulid,'') like '%".$fohulid."%' 
			and coalesce(a.perioddate,'') like '%".$perioddate."%'
			and coalesce(c.companyname,'') like '%".$companyname."%'
			and coalesce(b.accountname,'') like '%".$accountname."%'
			and coalesce(b.accountcode,'') like '%".$accountcode."%'
			";
    
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.fohulid in (" . $_GET['id'] . ")";
    } 
    */
    $sql = "select t.*, a.companyname, b.description, c.plantcode as plantcode, ifnull((select foh from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as fohlast1,
        ifnull((select foh from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as fohlast2,
        ifnull((select ul from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as ullast1,
        ifnull((select ul from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as ullast2,
        ifnull((select qtyoutput from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as qtyoutput1,
        ifnull((select qtyoutput from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as qtyoutput2
            from fohul t
            join company a on a.companyid = t.companyid
            left join mgprocess b on b.mgprocessid = t.mgprocessid
            left join plant c on c.plantid = t.plantid
            where coalesce(t.fohulid,'') like '%".$fohulid."%' 
            and coalesce(t.perioddate,'') like '%".$perioddate."%' 
            and coalesce(a.companyname,'') like '%".$companyname."%' 
            and coalesce(b.description,'') like '%".$description."%' 
            and coalesce(c.plantcode,'') like '%".$plantcode."%' 
            ";
    //$sql = $sql . " order by accountname asc ";
    $command          = Yii::app()->db->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row1)
		{
			$this->pdf->companyid = $row1['companyid'];
		}
    $this->pdf->title = GetCatalog('fohul');
    $this->pdf->AddPage('L',array(220,450));
    $this->pdf->setFont('Arial','B',8);
    $this->pdf->colalign  = array(
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
    );
    $this->pdf->colheader = array(
      GetCatalog('fohulid'),
      GetCatalog('docdate'),
      GetCatalog('perioddate'),
      GetCatalog('company'),
      GetCatalog('plant'),
      GetCatalog('mgprocess'),
      GetCatalog('foh'),
      GetCatalog('fohgen'),
      GetCatalog('fohlast1'),
      GetCatalog('fohlast2'),
      GetCatalog('ul'),
      GetCatalog('ulgen'),
      GetCatalog('ullast1'),
      GetCatalog('ullast2'),
      GetCatalog('totalfoh'),
      GetCatalog('totalul'),
      GetCatalog('qtyoutput'),
      GetCatalog('qtyoutput1'),
      GetCatalog('qtyoutput2'),
      GetCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      15,
      20,
      17,
      35,
      25,
      35,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20,
      20
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial','',8);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'L',
      'L',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
      'R',
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['fohulid'],
        date(Yii::app()->params['dateviewfromdb'],strtotime($row1['docdate'])),
        date(Yii::app()->params['dateviewfromdb'],strtotime($row1['perioddate'])),
        $row1['companyname'],
        $row1['plantcode'],
        $row1['description'],
        Yii::app()->format->formatCurrency($row1['foh']),
        Yii::app()->format->formatCurrency($row1['fohgen']),
        Yii::app()->format->formatCurrency($row1['fohlast1']),
        Yii::app()->format->formatCurrency($row1['fohlast2']),
        Yii::app()->format->formatCurrency($row1['ul']),
        Yii::app()->format->formatCurrency($row1['ulgen']),
        Yii::app()->format->formatCurrency($row1['ullast1']),
        Yii::app()->format->formatCurrency($row1['ullast2']),
        Yii::app()->format->formatCurrency($row1['totalfoh']),
        Yii::app()->format->formatCurrency($row1['totalul']),
        Yii::app()->format->formatCurrency($row1['qtyoutput']),
        Yii::app()->format->formatCurrency($row1['qtyoutput1']),
        Yii::app()->format->formatCurrency($row1['qtyoutput2']),
        $row1['statusname']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'fohul';
    parent::actionDownxls();
    $fohulid = filter_input(INPUT_GET,'id');
	$perioddate = filter_input(INPUT_GET,'perioddate');
	$companyname = filter_input(INPUT_GET,'companyname');
	$description = filter_input(INPUT_GET,'mgprocess');
	$plantcode = filter_input(INPUT_GET,'plantcode');
      
    $year = date('Y',strtotime($perioddate));
    $month = date('m',strtotime($perioddate));
    $day1 = strtotime(''.$year.'-'.$month.'-01');
    $day2 = strtotime(''.$year.'-'.$month.'-01 -1 month');
    $bulanini = date('Y-m-d',($day1));
    $bulanlalu = date('Y-m-d',($day2));
    
    $sql = "select t.*, a.companyname, b.description,a.companycode, c.plantcode as plantcode, ifnull((select foh from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as fohlast1,
        ifnull((select foh from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as fohlast2,
        ifnull((select ul from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as ullast1,
        ifnull((select ul from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as ullast2,
        ifnull((select qtyoutput from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -1 month)),0) as qtyoutput1,
        ifnull((select qtyoutput from fohul x where x.companyid = t.companyid and x.recordstatus=3 and x.mgprocessid = t.mgprocessid and x.perioddate = date_add(t.perioddate,interval -2 month)),0) as qtyoutput2
            from fohul t
            join company a on a.companyid = t.companyid
            left join mgprocess b on b.mgprocessid = t.mgprocessid
            left join plant c on c.plantid = t.plantid
            where coalesce(t.fohulid,'') like '%".$fohulid."%' 
            and coalesce(t.perioddate,'') like '%".$perioddate."%' 
            and coalesce(a.companyname,'') like '%".$companyname."%' 
            and coalesce(b.description,'') like '%".$description."%' 
            and coalesce(c.plantcode,'') like '%".$plantcode."%' 
            ";
      
    $command          = Yii::app()->db->createCommand($sql);
    $dataReader       = $command->queryAll();
    $i=3;
    
    $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, 'ID')
          ->setCellValueByColumnAndRow(1, $i, getCatalog('docdate'))
          ->setCellValueByColumnAndRow(2, $i, getCatalog('perioddate'))
          ->setCellValueByColumnAndRow(3, $i, getCatalog('company'))
          ->setCellValueByColumnAndRow(4, $i, getCatalog('plant'))
          ->setCellValueByColumnAndRow(5, $i, getCatalog('mgprocess'))
          ->setCellValueByColumnAndRow(6, $i, getCatalog('foh'))
          ->setCellValueByColumnAndRow(7, $i, getCatalog('fohgen'))
          ->setCellValueByColumnAndRow(8, $i, getCatalog('fohlast1'))
          ->setCellValueByColumnAndRow(9, $i, getCatalog('fohlast2'))
          ->setCellValueByColumnAndRow(10, $i, getCatalog('ul'))
          ->setCellValueByColumnAndRow(11, $i, getCatalog('ulgen'))
          ->setCellValueByColumnAndRow(12, $i, getCatalog('ullast1'))
          ->setCellValueByColumnAndRow(13, $i, getCatalog('ullast2'))
          ->setCellValueByColumnAndRow(14, $i, getCatalog('totalfoh'))
          ->setCellValueByColumnAndRow(15, $i, getCatalog('qtyoutput'))
          ->setCellValueByColumnAndRow(16, $i, getCatalog('qtyoutput1'))
          ->setCellValueByColumnAndRow(17, $i, getCatalog('qtyoutput2'))
          ->setCellValueByColumnAndRow(18, $i, getCatalog('recordstatus'));
    $i++;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, $row1['fohulid'])
          ->setCellValueByColumnAndRow(1, $i, date(Yii::app()->params['dateviewfromdb'],strtotime($row1['docdate'])))
          ->setCellValueByColumnAndRow(2, $i, date(Yii::app()->params['dateviewfromdb'],strtotime($row1['perioddate'])))
          ->setCellValueByColumnAndRow(3, $i, $row1['companycode'])
          ->setCellValueByColumnAndRow(4, $i, $row1['plantcode'])
          ->setCellValueByColumnAndRow(5, $i, $row1['description'])
          ->setCellValueByColumnAndRow(6, $i, $row1['foh'])
          ->setCellValueByColumnAndRow(7, $i, $row1['fohgen'])
          ->setCellValueByColumnAndRow(8, $i, $row1['fohlast1'])
          ->setCellValueByColumnAndRow(9, $i, $row1['fohlast2'])
          ->setCellValueByColumnAndRow(10, $i, $row1['ul'])
          ->setCellValueByColumnAndRow(11, $i, $row1['ulgen'])
          ->setCellValueByColumnAndRow(12, $i, $row1['ullast1'])
          ->setCellValueByColumnAndRow(13, $i, $row1['ullast2'])
          ->setCellValueByColumnAndRow(14, $i, $row1['totalfoh'])
          ->setCellValueByColumnAndRow(15, $i, $row1['qtyoutput'])
          ->setCellValueByColumnAndRow(16, $i, $row1['qtyoutput1'])
          ->setCellValueByColumnAndRow(17, $i, $row1['qtyoutput2'])
          ->setCellValueByColumnAndRow(18, $i, $row1['statusname']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
  
}