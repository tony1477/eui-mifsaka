<?php
class ForecastfppController extends Controller {
  public $menuname = 'forecastfpp';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $forecastfppid   = isset($_POST['forecastfppid']) ? $_POST['forecastfppid'] : '';
    $perioddatey        = isset($_POST['perioddateyear']) ? $_POST['perioddateyear'] : '';
    $perioddatem        = isset($_POST['perioddatemonth']) ? $_POST['perioddatemonth'] : '';
    $companyname    = isset($_POST['companyname'])  ? $_POST['companyname'] : '';
    $productname       = isset($_POST['productname']) ? $_POST['productname'] : '';
    $sloccode       = isset($_POST['sloccode']) ? $_POST['sloccode'] : '';
    $recordstatus   = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'forecastfppid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
    $perioddate = '';
    if($perioddatey != '') {
        $perioddate .= "  year(perioddate) = year('{$perioddatey}-01-01') and ";
    }
    if($perioddatem != '') {
        $perioddate .= "  month(perioddate) = month('2020-{$perioddatem}-01') and ";
    }
    $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('forecastfpp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->where("{$perioddate} (a.companyname like :companyname) and
            t.companyid in (".getUserObjectValues('company').")", array(
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname')
    ->from('forecastfpp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->where("{$perioddate}  (a.companyname like :companyname) and
            t.companyid in (".getUserObjectValues('company').")", array(
        
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'forecastfppid' => $data['forecastfppid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'perioddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['perioddate'])),
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionGeneratefpp() {
    parent::actionDownload();
		$sql = "call GenerateForecastFPP(" . $_REQUEST['companyid'] . ", '" . date(Yii::app()->params['datetodb'], strtotime($_REQUEST['perioddate'])) . "')";
    Yii::app()->db->createCommand($sql)->execute();
		GetMessage('success', 'alreadysaved');

    /*
    if (isset($_POST['id'])) {
			//$sql = "select headernote from deliveryadvice where deliveryadviceid = ".$_POST['id'];
			//$header = Yii::app()->db->createCommand($sql)->queryScalar();
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateForecastFPP(:vid, :vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true, $e->getMessage());
      }
    }
    Yii::app()->end();
    */
  }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertforecastfpp(:vdocdate,:vperioddate,:vcompanyid,:vproductid,:vslocid,:vunitofmeasureid,:vleadtime,:vgrpredictreal,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateforecastfpp(:vid,:vdocdate,:vperioddate,:vcompanyid,:vproductid,:vslocid,:vunitofmeasureid,:vleadtime,:vgrpredictreal,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vdocdate', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vperioddate', $arraydata[2], PDO::PARAM_STR);
    $command->bindvalue(':vcompanyid', $arraydata[3], PDO::PARAM_STR);
    $command->bindvalue(':vproductid', $arraydata[4], PDO::PARAM_STR);
    $command->bindvalue(':vslocid', $arraydata[5], PDO::PARAM_STR);        
		$command->bindvalue(':vunitofmeasureid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vleadtime', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vgrpredictreal', $arraydata[8], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
    $target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-forecastfpp"]["name"]);
    if (move_uploaded_file($_FILES["file-forecastfpp"]["tmp_name"], $target_file)) {
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
					$docdate = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$perioddate = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
          $sloccode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$product = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
          $productid = Yii::app()->db->createCommand("select productid from product where productname = '".$product."'")->queryScalar();
          $uomcode = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
          $leadtime = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
          $prqtyreal = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
					$this->ModifyData($connection,array($id,date(Yii::app()->params['datetodb'], strtotime($docdate)),date(Yii::app()->params['datetodb'], strtotime($perioddate)),$companyid,$productid,$slocid,$uomid,$leadtime,$prqtyreal));
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
			$this->ModifyData($connection,array((isset($_POST['forecastfppid'])?$_POST['forecastfppid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),date(Yii::app()->params['datetodb'], strtotime($_POST['perioddate'])),$_POST['companyid'],$_POST['productid'],$_POST['slocid'],$_POST['unitofmeasureid'],$_POST['leadtime'],$_POST['prqtyreal']));
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
        $sql     = 'call Approveforecastfpp(:vid,:vcreatedby)';
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
        $sql     = 'call Deleteforecastfpp(:vid,:vcreatedby)';
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
        $sql     = 'call Purgeforecastfpp(:vid,:vcreatedby)';
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
    //$forecastfppid = filter_input(INPUT_GET,'forecastfppid');
    $forecastfppid = filter_input(INPUT_GET,'id');
    $perioddate = filter_input(INPUT_GET,'perioddate');
    $companyname = filter_input(INPUT_GET,'companyname');
    $productname = filter_input(INPUT_GET,'productname');
    $sloccode = filter_input(INPUT_GET,'sloccode');
    
    
    
    /*$sql = "select a.forecastfppid,a.perioddate,b.accountname,a.expeditionamount,a.companyid,ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between DATE_ADD(DATE_ADD(LAST_DAY(a.perioddate),INTERVAL 1 DAY),INTERVAL - 1 MONTH) and LAST_DAY(a.perioddate)),0) as realisasi,
            ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between CONCAT(YEAR(a.perioddate),'-01-01') and LAST_DAY(a.perioddate)),0) as kumrealisasi
						from expedition a
						join account b on b.accountid = a.accountid 
						join company c on c.companyid = a.companyid ";
		$forecastfppid = filter_input(INPUT_GET,'forecastfppid');
		$perioddate = filter_input(INPUT_GET,'perioddate');
		$companyname = filter_input(INPUT_GET,'companyname');
		$accountname = filter_input(INPUT_GET,'accountname');
		$accountcode = filter_input(INPUT_GET,'accountcode');
		$sql .= " where coalesce(a.forecastfppid,'') like '%".$forecastfppid."%' 
			and coalesce(a.perioddate,'') like '%".$perioddate."%'
			and coalesce(c.companyname,'') like '%".$companyname."%'
			and coalesce(b.accountname,'') like '%".$accountname."%'
			and coalesce(b.accountcode,'') like '%".$accountcode."%'
			";
    
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.forecastfppid in (" . $_GET['id'] . ")";
    } 
    */
    $sql = "select t.*, a.companyname, b.productname, c.sloccode as sloccode, d.uomcode
            from forecastfpp t
            join company a on a.companyid = t.companyid
            left join product b on b.productid = t.productid
            left join sloc c on c.slocid = t.slocid
            left join unitofmeasure d on d.unitofmeasureid = t.unitofmeasureid
            where coalesce(t.forecastfppid,'') like '%".$forecastfppid."%' 
            and coalesce(t.perioddate,'') like '%".$perioddate."%' 
            and coalesce(a.companyname,'') like '%".$companyname."%' 
            and coalesce(b.productname,'') like '%".$productname."%' 
            and coalesce(c.sloccode,'') like '%".$sloccode."%' 
            ";
    //$sql = $sql . " order by accountname asc ";
    $command          = Yii::app()->db->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row1)
		{
			$this->pdf->companyid = $row1['companyid'];
		}
    $this->pdf->title = GetCatalog('forecastfpp');
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
      GetCatalog('ID'),
      GetCatalog('docdate'),
      GetCatalog('perioddate'),
      GetCatalog('company'),
      GetCatalog('sloccode'),
      GetCatalog('product'),
      GetCatalog('uom'),
      GetCatalog('qtyforecast'),
      GetCatalog('avg3month'),
      GetCatalog('avgperday'),
      GetCatalog('qtymax'),
      GetCatalog('qtymin'),
      GetCatalog('leadtime'),
      GetCatalog('pendingpo'),
      GetCatalog('saldoawal'),
      GetCatalog('grpredict'),
      GetCatalog('prqtygen'),
      GetCatalog('prqtyreal'),
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
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['forecastfppid'],
        date(Yii::app()->params['dateviewfromdb'],strtotime($row1['docdate'])),
        date(Yii::app()->params['dateviewfromdb'],strtotime($row1['perioddate'])),
        $row1['companyname'],
        $row1['sloccode'],
        $row1['productname'],
        $row1['uomcode'],
        Yii::app()->format->formatCurrency($row1['qtyforecast']),
        Yii::app()->format->formatCurrency($row1['avg3month']),
        Yii::app()->format->formatCurrency($row1['avgperday']),
        Yii::app()->format->formatCurrency($row1['qtymax']),
        Yii::app()->format->formatCurrency($row1['qtymin']),
        Yii::app()->format->formatCurrency($row1['leadtime']),
        Yii::app()->format->formatCurrency($row1['pendingpo']),
        Yii::app()->format->formatCurrency($row1['saldoawal']),
        Yii::app()->format->formatCurrency($row1['grpredict']),
        Yii::app()->format->formatCurrency($row1['prqty']),
        Yii::app()->format->formatCurrency($row1['prqtyreal']),
        $row1['statusname']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'forecastfpp';
    parent::actionDownxls();
    $forecastfppid = filter_input(INPUT_GET,'id');
    $perioddate = filter_input(INPUT_GET,'perioddate');
    $companyname = filter_input(INPUT_GET,'companyname');
    $productname = filter_input(INPUT_GET,'productname');
    $sloccode = filter_input(INPUT_GET,'sloccode');
      
    $year = date('Y',strtotime($perioddate));
    $month = date('m',strtotime($perioddate));
    $day1 = strtotime(''.$year.'-'.$month.'-01');
    $day2 = strtotime(''.$year.'-'.$month.'-01 -1 month');
    $bulanini = date('Y-m-d',($day1));
    $bulanlalu = date('Y-m-d',($day2));
    
    $sql = "select t.*, a.companyname, a.companycode, b.productname, c.sloccode as sloccode, d.uomcode
        from forecastfpp t
        join company a on a.companyid = t.companyid
        left join product b on b.productid = t.productid
        left join sloc c on c.slocid = t.slocid
        left join unitofmeasure d on d.unitofmeasureid = t.unitofmeasureid
        where coalesce(t.forecastfppid,'') like '%".$forecastfppid."%' 
        and coalesce(t.perioddate,'') like '%".$perioddate."%' 
        and coalesce(a.companyname,'') like '%".$companyname."%' 
        and coalesce(b.productname,'') like '%".$productname."%' 
        and coalesce(c.sloccode,'') like '%".$sloccode."%'
		order by t.forecastfppid
      ";
      
    $command          = Yii::app()->db->createCommand($sql);
    $dataReader       = $command->queryAll();
    $i=2;
    
    /*$this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, 'Periode:')
          ->setCellValueByColumnAndRow(2, $i, $month.'-'.$year);*/
    $i++;
    
    $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, 'ID')
          ->setCellValueByColumnAndRow(1, $i, getCatalog('docdate'))
          ->setCellValueByColumnAndRow(2, $i, getCatalog('perioddate'))
          ->setCellValueByColumnAndRow(3, $i, getCatalog('company'))
          ->setCellValueByColumnAndRow(4, $i, getCatalog('sloc'))
          ->setCellValueByColumnAndRow(5, $i, getCatalog('product'))
          ->setCellValueByColumnAndRow(6, $i, getCatalog('uom'))
          ->setCellValueByColumnAndRow(7, $i, getCatalog('qtyforecast'))
          ->setCellValueByColumnAndRow(8, $i, getCatalog('avg3month'))
          ->setCellValueByColumnAndRow(9, $i, getCatalog('avgperday'))
          ->setCellValueByColumnAndRow(10, $i, getCatalog('qtymax'))
          ->setCellValueByColumnAndRow(11, $i, getCatalog('qtymin'))
          ->setCellValueByColumnAndRow(12, $i, getCatalog('leadtime'))
          ->setCellValueByColumnAndRow(13, $i, getCatalog('pendingpo'))
          ->setCellValueByColumnAndRow(14, $i, getCatalog('saldoawal'))
          ->setCellValueByColumnAndRow(15, $i, getCatalog('grpredict'))
          ->setCellValueByColumnAndRow(16, $i, getCatalog('prqtygen'))
          ->setCellValueByColumnAndRow(17, $i, getCatalog('prqtyreal'))
          ->setCellValueByColumnAndRow(18, $i, getCatalog('recordstatus'));
    $i++;
    
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i, $row1['forecastfppid'])
          ->setCellValueByColumnAndRow(1, $i, date(Yii::app()->params['dateviewfromdb'],strtotime($row1['docdate'])))
          ->setCellValueByColumnAndRow(2, $i, date(Yii::app()->params['dateviewfromdb'],strtotime($row1['perioddate'])))
          ->setCellValueByColumnAndRow(3, $i, $row1['companycode'])
          ->setCellValueByColumnAndRow(4, $i, $row1['sloccode'])
          ->setCellValueByColumnAndRow(5, $i, $row1['productname'])
          ->setCellValueByColumnAndRow(6, $i, $row1['uomcode'])
          ->setCellValueByColumnAndRow(7, $i, $row1['qtyforecast'])
          ->setCellValueByColumnAndRow(8, $i, $row1['avg3month'])
          ->setCellValueByColumnAndRow(9, $i, $row1['avgperday'])
          ->setCellValueByColumnAndRow(10, $i, $row1['qtymax'])
          ->setCellValueByColumnAndRow(11, $i, $row1['qtymin'])
          ->setCellValueByColumnAndRow(12, $i, $row1['leadtime'])
          ->setCellValueByColumnAndRow(13, $i, $row1['pendingpo'])
          ->setCellValueByColumnAndRow(14, $i, $row1['saldoawal'])
          ->setCellValueByColumnAndRow(15, $i, $row1['grpredict'])
          ->setCellValueByColumnAndRow(16, $i, $row1['prqty'])
          ->setCellValueByColumnAndRow(17, $i, $row1['prqtyreal'])
          ->setCellValueByColumnAndRow(18, $i, $row1['statusname']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
  
}
