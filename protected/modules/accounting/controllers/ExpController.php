<?php
class ExpController extends Controller {
  public $menuname = 'exp';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $expid   = isset($_POST['expid']) ? $_POST['expid'] : '';
    $docdate        = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $issupplier    = isset($_POST['issupplier'])  ? $_POST['issupplier'] : '';
    $companyname    = isset($_POST['companyname'])  ? $_POST['companyname'] : '';
    $plantfromcode  = isset($_POST['plantfromcode'])  ? $_POST['plantfromcode'] : '';
    $planttocode    = isset($_POST['planttocode'])  ? $_POST['planttocode'] : '';
    $fullname       = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $recordstatus   = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'expid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
    $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('exp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')
    ->leftjoin('plant c', 'c.plantid=t.plantfromid')
    ->leftjoin('plant d', 'd.plantid=t.planttoid')
    ->where("((expid like :expid) and (docdate like :docdate) and (t.issupplier like :issupplier) and (a.companyname like :companyname) and (coalesce(b.fullname,'') like :fullname)) and ((coalesce(c.plantcode,'') like :plantfromcode) and (coalesce(d.plantcode,'') like :planttocode)) and
            t.companyid in (".getUserObjectValues('company').")", array(
        ':expid' => '%' . $expid . '%',
        ':docdate' => '%' . $docdate . '%',
        ':issupplier' => '%' . $issupplier . '%',
        ':companyname' => '%' . $companyname . '%',
        ':fullname' => '%' . $fullname . '%',
        ':plantfromcode' => '%' . $plantfromcode . '%',
        ':planttocode' => '%' . $planttocode . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,ifnull(b.fullname,"-") as fullname,ifnull(c.plantcode,"-") as plantfromcode, ifnull(d.plantcode,"-") as planttocode')
    ->from('exp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('addressbook b', 'b.addressbookid=t.addressbookid')
    ->leftjoin('plant c', 'c.plantid=t.plantfromid')
    ->leftjoin('plant d', 'd.plantid=t.planttoid')
    ->where("((expid like :expid) and (docdate like :docdate) and (t.issupplier like :issupplier) and (a.companyname like :companyname) and (coalesce(b.fullname,'') like :fullname)) and ((coalesce(c.plantcode,'') like :plantfromcode) and (coalesce(d.plantcode,'') like :planttocode)) and
            t.companyid in (".getUserObjectValues('company').")", array(
        ':expid' => '%' . $expid . '%',
        ':docdate' => '%' . $docdate . '%',
        ':issupplier' => '%' . $issupplier . '%',
        ':companyname' => '%' . $companyname . '%',
        ':fullname' => '%' . $fullname . '%',
        ':plantfromcode' => '%' . $plantfromcode . '%',
        ':planttocode' => '%' . $planttocode . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'expid' => $data['expid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'issupplier' => $data['issupplier'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'plantfromid' => $data['plantfromid'],
        'plantfromcode' => $data['plantfromcode'],
        'planttoid' => $data['planttoid'],
        'planttocode' => $data['planttocode'],
        'percent' => $data['percent'],
        'recordstatus' => $data['recordstatus'],
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
			$sql     = 'call Insertexp(:vdocdate, :vcompanyid, :vissupplier, :vaddressbookid, :vplantfromid, :vplanttoid, :vpercent, :vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateexp(:vid, :vdocdate, :vcompanyid, :vissupplier, :vaddressbookid, :vplantfromid, :vplanttoid, :vpercent, :vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
		}
		$command->bindvalue(':vdocdate', $arraydata[1], PDO::PARAM_STR);
        $command->bindvalue(':vcompanyid', $arraydata[2], PDO::PARAM_STR);
        $command->bindvalue(':vissupplier', $arraydata[3], PDO::PARAM_STR);
        $command->bindvalue(':vaddressbookid', $arraydata[4], PDO::PARAM_STR);
        $command->bindvalue(':vplantfromid', $arraydata[5], PDO::PARAM_STR);
		$command->bindvalue(':vplanttoid', $arraydata[6], PDO::PARAM_STR);
		$command->bindvalue(':vpercent', $arraydata[7], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
    $target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-exp"]["name"]);
    if (move_uploaded_file($_FILES["file-exp"]["tmp_name"], $target_file)) {
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
					$companyname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            $companyid = Yii::app()->db->createCommand("select companyid from company where companyname = '".$companyname."'")->queryScalar();
          $issupplier = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
            if ($issupplier == 'Yes'){$issupplier = '1';}else{$issupplier='0';}
          $namasupplier = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
            $addressbookid = Yii::app()->db->createCommand("select addressbookid from addressbook where isvendor = 1 and fullname = '".$namasupplier."'")->queryScalar();
            if ($addressbookid == null){$addressbookid = 0;}
          $cabangsumber = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
            $plantfromid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$cabangsumber."'")->queryScalar();
            if ($plantfromid == null){$plantfromid = 0;}
          $cabangtujuan = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
            $planttoid = Yii::app()->db->createCommand("select plantid from plant where plantcode = '".$cabangtujuan."'")->queryScalar();
          $percent = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$this->ModifyData($connection,array($id,date(Yii::app()->params['datetodb'], strtotime($docdate)),$companyid,$issupplier,$addressbookid,$plantfromid,$planttoid,$percent));
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
			$this->ModifyData($connection,array((isset($_POST['expid'])?$_POST['expid']:''),date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),$_POST['companyid'],$_POST['issupplier'],(isset($_POST['addressbookid']) && $_POST['addressbookid']!='') ? $_POST['addressbookid'] : 0,(isset($_POST['plantfromid']) && $_POST['plantfromid']!='') ? $_POST['plantfromid'] : 0 ,$_POST['planttoid'],$_POST['percent']));
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
        $sql     = 'call Approveexp(:vid,:vcreatedby)';
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
        $sql     = 'call Deleteexp(:vid,:vcreatedby)';
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
        $sql     = 'call Purgeexp(:vid,:vcreatedby)';
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
    $expid = filter_input(INPUT_GET,'expid'); 
  	$docdate = filter_input(INPUT_GET,'docdate');
  	$companyname = filter_input(INPUT_GET,'companyname');
  	$fullname = filter_input(INPUT_GET,'fullname');
  	$plantcode = filter_input(INPUT_GET,'plantcode');
    
    
    
    /*$sql = "select a.expid,a.docdate,b.accountname,a.expamount,a.companyid,ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between DATE_ADD(DATE_ADD(LAST_DAY(a.docdate),INTERVAL 1 DAY),INTERVAL - 1 MONTH) and LAST_DAY(a.docdate)),0) as realisasi,
            ifnull((select sum(d.debit-d.credit) from genledger d join genjournal e on e.genjournalid=d.genjournalid where e.recordstatus=3 and d.accountid=a.accountid and e.journaldate between CONCAT(YEAR(a.docdate),'-01-01') and LAST_DAY(a.docdate)),0) as kumrealisasi
						from exp a
						join account b on b.accountid = a.accountid 
						join company c on c.companyid = a.companyid ";
		$expid = filter_input(INPUT_GET,'expid');
		$docdate = filter_input(INPUT_GET,'docdate');
		$companyname = filter_input(INPUT_GET,'companyname');
		$accountname = filter_input(INPUT_GET,'accountname');
		$accountcode = filter_input(INPUT_GET,'accountcode');
		$sql .= " where coalesce(a.expid,'') like '%".$expid."%' 
			and coalesce(a.docdate,'') like '%".$docdate."%'
			and coalesce(c.companyname,'') like '%".$companyname."%'
			and coalesce(b.accountname,'') like '%".$accountname."%'
			and coalesce(b.accountcode,'') like '%".$accountcode."%'
			";
    
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.expid in (" . $_GET['id'] . ")";
    } 
    */
    $sql = "select t.*, a.companyname, b.fullname, c.plantcode as plantfromcode,d.plantcode as planttocode, if(t.issupplier=1,'Yes','No') as issupp
            from exp t
            join company a on a.companyid = t.companyid
            left join addressbook b on b.addressbookid = t.addressbookid
            left join plant c on c.plantid = t.plantfromid
            left join plant d on d.plantid = t.planttoid
            where coalesce(t.expid,'') like '%".$expid."%' 
            and coalesce(t.docdate,'') like '%".$docdate."%' 
            and coalesce(a.companyname,'') like '%".$companyname."%' 
            and coalesce(b.fullname,'') like '%".$fullname."%' 
            and coalesce(c.plantcode,'') like '%".$plantcode."%' 
            and coalesce(d.plantcode,'') like '%".$plantcode."%' 
            ";
    //$sql = $sql . " order by accountname asc ";
    $command          = Yii::app()->db->createCommand($sql);
    $dataReader       = $command->queryAll();
    foreach ($dataReader as $row1)
		{
			$this->pdf->companyid = $row1['companyid'];
		}
    $this->pdf->title = GetCatalog('exp');
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
      GetCatalog('docdate'),
      GetCatalog('companyname'),
      GetCatalog('Supplier ?'),
      GetCatalog('Supplier'),
      GetCatalog('Cabang Sumber'),
      GetCatalog('Cabang Tujuan'),
      GetCatalog('Percent'),
      GetCatalog('Status')
    );
    $this->pdf->setwidths(array(
      27,
      63,
      18,
      35,
      35,
      35,
      20,
      35
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
        date(Yii::app()->params['dateviewfromdb'],strtotime($row1['docdate'])),
        $row1['companyname'],
        $row1['issupp'],
        $row1['fullname'],
        $row1['plantfromcode'],
        $row1['planttocode'],
        $row1['percent'],
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'exp';
    parent::actionDownxls();
    $expid = filter_input(INPUT_GET,'expid'); 
    $docdate = filter_input(INPUT_GET,'docdate');
    $companyname = filter_input(INPUT_GET,'companyname');
    $fullname = filter_input(INPUT_GET,'fullname');
    $plantcode = filter_input(INPUT_GET,'plantcode');
    
    $sql = "select t.*, a.companyname, b.fullname, c.plantcode as plantfromcode,d.plantcode as planttocode, if(t.issupplier=1,'Yes','No') as issupp
            from exp t
            join company a on a.companyid = t.companyid
            left join addressbook b on b.addressbookid = t.addressbookid
            left join plant c on c.plantid = t.plantfromid
            left join plant d on d.plantid = t.planttoid
            where coalesce(t.expid,'') like '%".$expid."%' 
            and coalesce(t.docdate,'') like '%".$docdate."%' 
            and coalesce(a.companyname,'') like '%".$companyname."%' 
            and coalesce(b.fullname,'') like '%".$fullname."%' 
            and coalesce(c.plantcode,'') like '%".$plantcode."%' 
            and coalesce(d.plantcode,'') like '%".$plantcode."%' 
            ";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0, $i, $row['expid'])
        ->setCellValueByColumnAndRow(1, $i, date(Yii::app()->params['dateviewfromdb'],strtotime($row['docdate'])))
        ->setCellValueByColumnAndRow(2, $i, $row['companyname'])
        ->setCellValueByColumnAndRow(3, $i, $row['issupp'])
        ->setCellValueByColumnAndRow(4, $i, $row['fullname'])
        ->setCellValueByColumnAndRow(5, $i, $row['plantfromcode'])
        ->setCellValueByColumnAndRow(6, $i, $row['planttocode'])
        ->setCellValueByColumnAndRow(7, $i, $row['percent']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}