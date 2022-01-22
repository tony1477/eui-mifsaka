<?php
class AttController extends Controller {
  public $menuname = 'att';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexacc() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchacc();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData() {
		parent::actionIndex();
    if (isset($_GET['id'])) {
    } else {
      $sql = 'insert into att(recordstatus) values (0)';
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
        echo CJSON::encode(array(
          'attid' => $id
        ));
    }
  }
  public function search() {
    header("Content-Type: application/json");
    $attid = isset($_POST['attid']) ? $_POST['attid'] : '%%';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $accountid       = isset($_POST['accountid']) ? $_POST['accountid'] : '';
    $accname         = isset($_POST['accountname']) ? $_POST['accountname'] : '';
    $isneraca        = isset($_POST['isneraca']) ? $_POST['isneraca'] : '%%';
    $isdebet         = isset($_POST['isdebet']) ? $_POST['isdebet'] : '';
    $nourut          = isset($_POST['nourut']) ? $_POST['nourut'] : '';
    $recordstatus    = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $attid = isset($_GET['q']) ? $_GET['q'] : $attid;
    $companyid       = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $accountid       = isset($_GET['q']) ? $_GET['q'] : $accountid;
    $isdebet         = isset($_GET['q']) ? $_GET['q'] : $isdebet;
    $nourut          = isset($_GET['q']) ? $_GET['q'] : $nourut;
    $recordstatus    = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'attid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    if(isset($isneraca) && $isneraca==0){
        $whereneraca = " ";
    }else{
        $whereneraca ='';
    }
    $sqlcount = "select count(1) as total ";
    $sqldata = "select t.*, a.companyname, b.accountid, b.accountname ";
    $from ="from att t
            join company a on t.companyid = a.companyid
            join account b on b.accountid = t.accheaderid ";
    $where = "where isneraca like '".$isneraca."'".$whereneraca;
      
    if(isset($attid) && $attid!=''){
        $where .= " and t.attid like '".$attid."'";
    }
    if(isset($companyid) && $companyid!=''){
        $where .= " and a.companyname like '%".$companyid."%'";
    }
    if(isset($accname) && $accname!=''){
        $where .= " and b.accountname like '%".$accname."%'";
    }
    $cmd = Yii::app()->db->createCommand($sqlcount.$from.$where)->queryScalar();
    $result['total'] = $cmd;
      
    $cmd = Yii::app()->db->createCommand($sqldata.$from.$where." order by ".$sort . " ". $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'attid' => $data['attid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'isneraca' => $data['isneraca'],
        'nourut' => $data['nourut'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSaveacc() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    if($_POST['accountid']==''){
        $accountid = null;
    }else{
        $accountid = $_POST['accountid'];
    }
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertattdet(:vattid,:vaccountid,:vaccountname,:vaccformula,:vnourut,:visbold,:visview,:vistotal,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateattdet(:vid,:vattid,:vaccountid,:vaccountname,:vaccformula,:vnourut,:visbold,:visview,:vistotal,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['attdetid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['attdetid']);
      }
      $command->bindvalue(':vattid', $_POST['attid'], PDO::PARAM_STR);
      $command->bindvalue(':vaccountid', $accountid, PDO::PARAM_STR);
      $command->bindvalue(':vaccountname', $_POST['accountname'], PDO::PARAM_STR);
      $command->bindvalue(':vaccformula', $_POST['accformula'], PDO::PARAM_STR);
      $command->bindvalue(':vnourut', $_POST['nourut'], PDO::PARAM_STR);
      $command->bindvalue(':visbold', $_POST['isbold'], PDO::PARAM_STR);
      $command->bindvalue(':visview', $_POST['isview'], PDO::PARAM_STR);
      $command->bindvalue(':vistotal', $_POST['istotal'], PDO::PARAM_STR);
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
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertatt(:vcompanyid,:vaccountid,:visdebet,:vaccformula,:vperformula,:vaftacc,:vnourut,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateatt    (:vid,:vcompanyid,:vaccheaderid,:visneraca,:vnourut,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['attid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['attid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vaccheaderid', $_POST['accountid'], PDO::PARAM_STR);
      $neraca = isset($_POST['isneraca']) ? ($_POST['isneraca'] == "on") ? 1 : 0 : 0;
      $status = isset($_POST['recordstatus']) ? ($_POST['recordstatus'] == "on") ? 1 : 0 : 0;
      $command->bindvalue(':visneraca', $neraca, PDO::PARAM_STR);
      $command->bindvalue(':vnourut', $_POST['nourut'], PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $status, PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      getmessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      getmessage(true, $e->getMessage());
    }
  }
  public function searchacc() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['attid'])) {
      $id = $_POST['attid'];
    } else if (isset($_GET['attid'])) {
      $id = $_GET['attid'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'attdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('attdet t')->leftjoin('account a', 'a.accountid=t.accountid')->where('t.attid like :attid', array(
      ':attid' => $id))->queryScalar();
    $result['total'] = $cmd;
      
    $cmd             = Yii::app()->db->createCommand()->select('t.*,b.accountid,b.accountcode')->from('attdet t')->leftjoin('account b', 'b.accountid=t.accountid')->where('t.attid like :attid', array(
        ':attid' => $id))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'attid' => $data['attid'],
        'attdetid' => $data['attdetid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'accountcode' => $data['accountcode'],
        'accformula' => $data['accformula'],
        'isbold' => $data['isbold'],
        'istotal' => $data['istotal'],
        'isview' => $data['isview'],
        'nourut' => $data['nourut']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionPurge() {
		parent::actionPurge();
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
  public function actionDownPdfNeraca() {
    parent::actionDownload();
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->AddPage('P');  
    $companyid = $_GET['company'];
    $plantid = $_GET['plant'];
    $date = $_GET['date'];
    $saldoawal = 0;
    $saldoakhir = 0;
    $per = $_GET['per'];
    $connection = Yii::app()->db;
    if($plantid != '') {
        $companyname = $connection->createCommand('select concat("PLANT : ",plantcode) from plant a where a.plantid = '.$plantid)->queryScalar();
        $companyid = $plantid;
    }
    else
    {
        $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
    }
      
    $sql1 = "select a.*, b.accountname as accheadername, b.accountcode
            from att a 
            join account b on a.accheaderid = b.accountid
            where a.recordstatus = 1 and isneraca = 1 and a.companyid = ".$_GET['company']." order by a.nourut asc";
    $res1 = $connection->createCommand($sql1)->queryAll();
    foreach($res1 as $row1){
        $this->pdf->setFont('Arial','',11);
        $this->pdf->Cell(0, 0, $companyname, 0, 0, 'C');
        $this->pdf->Cell(-187, 10, $row1['accheadername'], 0, 0, 'C');
        $this->pdf->text(85,$this->pdf->gety()+10,'Per : '.date("t F Y", strtotime($_GET['date'])));
        
        $i = 0;
        $this->pdf->setFont('Arial', '', 10);
        $this->pdf->sety($this->pdf->gety()+15);
        $this->pdf->colalign  = array(
          'C',
          'L',
          'C',
          'C',
          'C'
        );
        $this->pdf->setwidths(array(
          15,
          60,
          30,
          60,
          30
        ));
        $this->pdf->setbordercell(array(
        'LTR',
        'LTR',
        'LTR',
        'LTRB',
        'LTR'
      ));
        $this->pdf->colheader = array(
          'NO',
          'KETERANGAN',
          'SALDO',
          'MUTASI',
          'SALDO'
        );
        $this->pdf->Rowheader();
        
        $this->pdf->colalign  = array(
          'L',
          'L',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->colheader = array(
          '',
          '',
          'AWAL',
          'DEBIT',
          'CREDIT',
          'AKHIR'
        );
        $this->pdf->setwidths(array(
          15,
          60,
          30,
          30,
          30,
          30
        ));
        $this->pdf->setbordercell(array(
        'LRB',
        'LRB',
        'LRB',
        'LRB',
        'LRB',
        'LRB'
      ));
        $this->pdf->Rowheader();
        
        $this->pdf->coldetailalign = array(
          'C',
          'L',
          'R',
          'R',
          'R',
          'R'
        );
        //$this->pdf->row(array('1','TEST','AWAL','DEBIT','CREDIT','AKHIR'));
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        $i=1;
        $subawal = 0;
        $subakhir = 0;
        $subdebit = 0;
        $subcredit= 0;
        $this->pdf->Line(10,35,10,$this->pdf->gety()); // before NO, first border
        $this->pdf->Line(205,35,205,$this->pdf->gety()); // after akhir, end border
        foreach($res2 as $row2){
            $this->pdf->setFont('Arial','',8);
            if($row2['isbold']==1){
                $this->pdf->SetFont('Arial','B',9);
            }
            
            if($row2['istotal']!=1){
            if($plantid != '')
            {
                $sqlsaldoawal = "call hitungsaldoplant(:vaccountcode,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";
            }
            else
            {
                $sqlsaldoawal = "call hitungsaldo(:vaccountcode,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";
            }
            $command1 = $connection->createCommand($sqlsaldoawal);
            $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command1->execute();
            
            $sqlsaldo = "select @vsaldoawal as awal, @vsaldoakhir as akhir"; 
            $stmt1 = Yii::app()->db->createCommand($sqlsaldo); 
            $stmt1->execute(); 
            $saldo = $stmt1->queryRow();
            
            if($plantid != '')
            {
                $sqldebitcredit = "call hitungdebcredplant(:vaccountcode,:vdate,:vcompanyid,@vdebit,@vcredit)";
            }
            else
            {
                $sqldebitcredit = "call hitungdebcred(:vaccountcode,:vdate,:vcompanyid,@vdebit,@vcredit)";
            }
            $command2 = $connection->createCommand($sqldebitcredit);
            $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command2->execute();
            
            $sqldebcred = "select @vdebit as debit, @vcredit as credit"; 
            $stmt2 = Yii::app()->db->createCommand($sqldebcred); 
            $stmt2->execute(); 
            $debcred = $stmt2->queryRow();
            
            $this->pdf->setbordercell(array(
            'L',
            'LR',
            'R',
            'R',
            'R',
            'R'));
            
            
            if($row2['accformula']=='' || $row2['accformula']=='-'){
               $this->pdf->row(array($i,$row2['accountname'],'','','',''));
            }else{
                $this->pdf->Line(10,30,10,$this->pdf->gety()); // before NO, first border
                $this->pdf->Line(205,30,205,$this->pdf->gety()); // after akhir, end border
                $this->pdf->Line(115,30,115,$this->pdf->gety()+2); // after saldo awal
                $this->pdf->Line(145,35,145,$this->pdf->gety()+2); // after debit
                $this->pdf->Line(175,30,175,$this->pdf->gety()+2); // after credit
               $this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($saldo['awal']/$per),
                                      Yii::app()->format->formatCurrency($debcred['debit']/$per),
                                      Yii::app()->format->formatCurrency($debcred['credit']/$per),
                                      Yii::app()->format->formatCurrency($saldo['akhir']/$per)));   
            }
            /*
            $this->pdf->text(12,$this->pdf->gety(),$i);
			$this->pdf->text(21,$this->pdf->gety(),$row2['accountname']);
            */
            /*$this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($saldo['awal']/$per),
                                      Yii::app()->format->formatCurrency($debcred['debit']/$per),
                                      Yii::app()->format->formatCurrency($debcred['credit']/$per),
                                      Yii::app()->format->formatCurrency($saldo['akhir']/$per)));
            */
            $subawal = $subawal + $saldo['awal'];
            $subakhir = $subakhir + $saldo['akhir'];
            $subdebit = $subdebit + $debcred['debit'];
            $subcredit = $subcredit + $debcred['credit'];
            }else{
                $this->pdf->Line(10,30,10,$this->pdf->gety()); // before NO, first border
                $this->pdf->Line(205,30,205,$this->pdf->gety()); // after akhir, end border
                $this->pdf->Line(115,30,115,$this->pdf->gety()+2); // after saldo awal
                $this->pdf->Line(145,35,145,$this->pdf->gety()+2); // after debit
                $this->pdf->Line(175,30,175,$this->pdf->gety()+2); // after credit
                $this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($subawal/$per),
                                      Yii::app()->format->formatCurrency($subdebit/$per),
                                      Yii::app()->format->formatCurrency($subcredit/$per),
                                      Yii::app()->format->formatCurrency($subakhir/$per)));   
                
                $subawal = 0;
                $subakhir = 0;
                $subdebit = 0;
                $subcredit = 0;
            }
        $i++;
        }
        //$this->pdf->Rect($this->pdf->getX(),$this->pdf->getY(),5,20,'D');
        $this->pdf->setY($this->pdf->getY()-5);
        $this->pdf->setbordercell(array(
            'LRB',
            'LRB',
            'LRB',
            'LRB',
            'LRB',
            'LRB'));
        $this->pdf->row(array('','','','','',''));
        $this->pdf->checkPageBreak(250);
    }
    
    $this->pdf->Output();
  }   
	public function actionDownXlsNeraca()	{
      //$this->menuname = 'lampiranneraca';
      parent::actionDownXls();
      $companyid = $_GET['company'];
      $plantid = $_GET['plant'];
      $date = $_GET['date'];
      $per = $_GET['per'];
      $connection = Yii::app()->db;
      if($plantid != '') {
        $companyname = $connection->createCommand('select concat("PLANT : ",plantcode) from plant a where a.plantid = '.$plantid)->queryScalar();
        $companyid = $plantid;
    }
    else
    {
        $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
    }
      
      $sqldata1 = "select t.*, b.accountname, substr(b.accountname,1,30) as sheettitle ";
      $sqlcount1 = "select count(1) as total ";
      $from = "from att t 
              join company a on t.companyid = a.companyid
              join account b on b.accountid = t.accheaderid
              where isneraca = 1 and t.recordstatus = 1 and t.companyid = ".$_GET['company']." order by t.nourut desc";
      $total = $connection->createCommand($sqlcount1.$from)->queryScalar();
      $res1 = $connection->createCommand($sqldata1.$from)->queryAll();
      
      $i=0;$j=2;
      $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
      );

      foreach($res1 as $row1){
        $myWorkSheet = new PHPExcel_Worksheet($this->phpExcel, $row1['sheettitle']);
        $this->phpExcel->addSheet($myWorkSheet, $i);
        $excel = $this->phpExcel->getSheetByName($row1['sheettitle']);
        $excel->mergeCells('A2:F2');
        $excel->mergeCells('A3:F3');
        $excel->mergeCells('A4:F4');
        $excel->mergeCells('D6:E6');
        $excel->mergeCells('A6:A7');
        $excel->mergeCells('B6:B7');
        $excel->getColumnDimension('A')->setWidth(5);
        $excel->getColumnDimension('B')->setWidth(40);
        $excel->getColumnDimension('C')->setWidth(15);
        $excel->getColumnDimension('D')->setWidth(15);
        $excel->getColumnDimension('E')->setWidth(15);
        $excel->getColumnDimension('F')->setWidth(15);
        $excel->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->
            setCellValueByColumnAndRow($i, $j, $companyname)->
            setCellValueByColumnAndRow($i, $j+1, 'MUTASI '.$row1['accountname'])->
            setCellValueByColumnAndRow($i, $j+2, 'Per : '.date("t F Y", strtotime($_GET['date'])));
          
        $excel
          ->setCellValueByColumnAndRow($i, $j+4, 'NO')
          ->setCellValueByColumnAndRow($i+1, $j+4, 'Keterangan')
          ->setCellValueByColumnAndRow($i+2, $j+4, 'Saldo')
          ->setCellValueByColumnAndRow($i+3, $j+4, 'Mutasi')
          ->setCellValueByColumnAndRow($i+5, $j+4, 'Saldo');
          
        $excel
          ->setCellValueByColumnAndRow($i+2, $j+5, 'Awal')
          ->setCellValueByColumnAndRow($i+3, $j+5, 'Debit')
          ->setCellValueByColumnAndRow($i+4, $j+5, 'Credit')
          ->setCellValueByColumnAndRow($i+5, $j+5, 'Akhir');
              
          // get all data
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        
        $subawal = 0;
        $subakhir = 0;
        $subdebit = 0;
        $subcredit= 0;
        $k = 8;
        $l=1;
        foreach($res2 as $row2){
            
            if($row2['istotal']!=1){
                if($plantid != '') {
                    $sqlsaldoawal = "call hitungsaldoplant(:vaccountcode,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";
                }
                else {
                    $sqlsaldoawal = "call hitungsaldo(:vaccountcode,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";   
                }
                $command1 = $connection->createCommand($sqlsaldoawal);
                $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command1->execute();

                $sqlsaldo = "select @vsaldoawal as awal, @vsaldoakhir as akhir"; 
                $stmt1 = Yii::app()->db->createCommand($sqlsaldo); 
                $stmt1->execute(); 
                $saldo = $stmt1->queryRow();

                if($plantid != ''){
                    $sqldebitcredit = "call hitungdebcredplant(:vaccountcode,:vdate,:vcompanyid,@vdebit,@vcredit)";
                }else
                {
                    $sqldebitcredit = "call hitungdebcred(:vaccountcode,:vdate,:vcompanyid,@vdebit,@vcredit)";    
                }
                
                $command2 = $connection->createCommand($sqldebitcredit);
                $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command2->execute();

                $sqldebcred = "select @vdebit as debit, @vcredit as credit"; 
                $stmt2 = Yii::app()->db->createCommand($sqldebcred); 
                $stmt2->execute(); 
                $debcred = $stmt2->queryRow();
                
                $excel
                ->setCellValueByColumnAndRow($i+0, $k, $l)
                ->setCellValueByColumnAndRow($i+1, $k, $row2['accountname']);
                
                if($row2['accformula']=='' || $row2['accformula']=='-'){
                    
                }else{
                    $excel
                    ->setCellValueByColumnAndRow($i+2, $k, $saldo['awal']/$per)
                    ->setCellValueByColumnAndRow($i+3, $k, $debcred['debit']/$per)
                    ->setCellValueByColumnAndRow($i+4, $k, $debcred['credit']/$per)
                    ->setCellValueByColumnAndRow($i+5, $k, $saldo['akhir']/$per);
                }

                $subawal = $subawal + $saldo['awal'];
                $subakhir = $subakhir + $saldo['akhir'];
                $subdebit = $subdebit + $debcred['debit'];
                $subcredit = $subcredit + $debcred['credit'];
            }else{
                $excel
                ->setCellValueByColumnAndRow($i+0, $k, $l)
                ->setCellValueByColumnAndRow($i+1, $k, $row2['accountname'])
                ->setCellValueByColumnAndRow($i+2, $k, $subawal/$per)
                ->setCellValueByColumnAndRow($i+3, $k, $subdebit/$per)
                ->setCellValueByColumnAndRow($i+4, $k, $subcredit/$per)
                ->setCellValueByColumnAndRow($i+5, $k, $subakhir/$per);
                
                $subawal = 0;
                $subakhir = 0;
                $subdebit = 0;
                $subcredit = 0;
            }
            $k++;
            $l++;
        }
       
    }
    $this->getFooterXLS($this->phpExcel);  
  }
  public function actionDownPdfPL()	{
   parent::actionDownload();
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->AddPage('P');  
    $companyid = $_GET['company'];
    $plantid = $_GET['plant'];
    $date = $_GET['date'];
    $saldoawal = 0;
    $saldoakhir = 0;
    $per = $_GET['per'];
    $connection = Yii::app()->db;
    if($plantid != '') {
        $companyname = $connection->createCommand('select plantcode from plant a where a.plantid = '.$plantid)->queryScalar();
        $companyid = $plantid;
    }
    else
    {
        $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
    }
    
      
    $sql1 = "select a.*, b.accountname as accheadername, b.accountcode
            from att a 
            join account b on a.accheaderid = b.accountid
            where a.recordstatus = 1 and isneraca = 0 and a.companyid = ".$_GET['company']." order by a.nourut asc";
    $res1 = $connection->createCommand($sql1)->queryAll();
    foreach($res1 as $row1){
        $this->pdf->setFont('Arial','',11);
        $this->pdf->Cell(0, 0, $companyname, 0, 0, 'C');
        $this->pdf->Cell(-187, 10, $row1['accheadername'], 0, 0, 'C');
        $this->pdf->text(85,$this->pdf->gety()+10,'Per : '.date("t F Y", strtotime($_GET['date'])));
        
        $i = 0;
        $this->pdf->setFont('Arial', '', 10);
        $this->pdf->sety($this->pdf->gety()+15);
        $this->pdf->colalign  = array(
          'C',
          'L',
          'C',
          'C',
          'C'
        );
        $this->pdf->setwidths(array(
          15,
          90,
          30,
          30,
          30
        ));
        $this->pdf->setbordercell(array(
        'LTR',
        'LTR',
        'LTR',
        'LTR',
        'LTR'
      ));
        $this->pdf->colheader = array(
          'NO',
          'KETERANGAN',
          'BULAN',
          'BULAN',
          'KUMULATIF'
        );
        $this->pdf->Rowheader();
        
        $this->pdf->colalign  = array(
          'L',
          'L',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->colheader = array(
          '',
          '',
          'INI',
          'LALU',
          'S/D BULAN INI'
        );
        $this->pdf->setwidths(array(
          15,
          90,
          30,
          30,
          30
        ));
        $this->pdf->setbordercell(array(
        'LRB',
        'LRB',
        'LRB',
        'LRB',
        'LRB'
      ));
        $this->pdf->Rowheader();
        
        $this->pdf->coldetailalign = array(
          'C',
          'L',
          'R',
          'R',
          'R'
        );
        //$this->pdf->row(array('1','TEST','AWAL','DEBIT','CREDIT','AKHIR'));
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        $i=1;
        $subawal = 0;
        $subakhir = 0;
        $subakum = 0;
        $this->pdf->Line(10,35,10,$this->pdf->gety()); // before NO, first border
        $this->pdf->Line(205,35,205,$this->pdf->gety()); // after akhir, end border
        foreach($res2 as $row2){
            $this->pdf->setFont('Arial','',8);
            if($row2['isbold']==1){
                $this->pdf->SetFont('Arial','B',9);
            }
            
            if($row2['istotal']!=1){
            if($plantid != '') {
                $sqlsaldoawal = "call hitungplmonthplant(:vaccountcode,:vdate,:vcompanyid,@vplmonth,@vpllastmonth)";
            }
            else
            {
                $sqlsaldoawal = "call hitungplmonth(:vaccountcode,:vdate,:vcompanyid,@vplmonth,@vpllastmonth)";
            }
            $command1 = $connection->createCommand($sqlsaldoawal);
            $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command1->execute();
            
            $sqlpl = "select @vplmonth as month, @vpllastmonth as lastmonth"; 
            $stmt1 = Yii::app()->db->createCommand($sqlpl); 
            $stmt1->execute(); 
            $pl = $stmt1->queryRow();
            
            if($plantid !='' )
            {
                $sqldebitcredit = "call hitungplakumplant(:vaccountcode,:vdate,:vcompanyid,@vplakum)";
            }
            else
            {
                $sqldebitcredit = "call hitungplakum(:vaccountcode,:vdate,:vcompanyid,@vplakum)";
            }
            $command2 = $connection->createCommand($sqldebitcredit);
            $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command2->execute();
            
            $sqlakum = "select @vplakum as plakum"; 
            $stmt2 = Yii::app()->db->createCommand($sqlakum); 
            $stmt2->execute(); 
            $akum = $stmt2->queryRow();
            
            $this->pdf->setbordercell(array(
            'L',
            'LR',
            'R',
            'R',
            'R'));
            
            
            if($row2['accformula']=='' || $row2['accformula']=='-'){
               $this->pdf->row(array($i,$row2['accountname'],'','',''));
            }else{
                $this->pdf->Line(10,30,10,$this->pdf->gety()); // before NO, first border
                $this->pdf->Line(205,30,205,$this->pdf->gety()); // after akhir, end border
                $this->pdf->Line(115,30,115,$this->pdf->gety()+2); // after saldo awal
                $this->pdf->Line(145,35,145,$this->pdf->gety()+2); // after debit
                $this->pdf->Line(175,30,175,$this->pdf->gety()+2); // after credit
               $this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($pl['month']/$per),
                                      Yii::app()->format->formatCurrency($pl['lastmonth']/$per),
                                      Yii::app()->format->formatCurrency($akum['plakum']/$per)));   
            }
            /*
            $this->pdf->text(12,$this->pdf->gety(),$i);
			$this->pdf->text(21,$this->pdf->gety(),$row2['accountname']);
            */
            /*$this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($saldo['awal']/$per),
                                      Yii::app()->format->formatCurrency($debcred['debit']/$per),
                                      Yii::app()->format->formatCurrency($debcred['credit']/$per),
                                      Yii::app()->format->formatCurrency($saldo['akhir']/$per)));
            */
            $subawal = $subawal + $pl['month'];
            $subakhir = $subakhir + $pl['lastmonth'];
            $subakum = $subakum + $akum['plakum'];
            
            }else{
                $this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($subawal/$per),
                                      Yii::app()->format->formatCurrency($subakhir/$per),
                                      Yii::app()->format->formatCurrency($subakum/$per)));   
                
                $subawal = 0;
                $subakhir = 0;
                $subakum = 0;
            }
        $i++;
        }
        //$this->pdf->Rect($this->pdf->getX(),$this->pdf->getY(),5,20,'D');
        $this->pdf->setY($this->pdf->getY()-5);
        $this->pdf->setbordercell(array(
            'LRB',
            'LRB',
            'LRB',
            'LRB',
            'LRB'));
        $this->pdf->row(array('','','','',''));
        $this->pdf->checkPageBreak(250);
    }
    
    $this->pdf->Output();
  }
  public function actionDownXlsPL()	{
      parent::actionDownXls();
      //$this->menuname = 'lampiranlabarugi';
      $companyid = $_GET['company'];
      $plantid = $_GET['plant'];
      $date = $_GET['date'];
      $per = $_GET['per'];
      $connection = Yii::app()->db;
      if($plantid != '') {
          $companyname = $connection->createCommand('select plantcode from plant a where a.plantid = '.$plantid)->queryScalar();
          $companyid = $plantid;
      }
      else
      {
          $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
      }
      $sqldata1 = "select t.*, b.accountname, substr(b.accountname,1,30) as sheettitle ";
      $sqlcount1 = "select count(1) as total ";
      $from = "from att t 
              join company a on t.companyid = a.companyid
              join account b on b.accountid = t.accheaderid
              where isneraca = 0 and t.recordstatus = 1 and t.companyid = ".$_GET['company']." order by t.nourut desc";
      $total = $connection->createCommand($sqlcount1.$from)->queryScalar();
      $res1 = $connection->createCommand($sqldata1.$from)->queryAll();
      
      $i=0;$j=2;
      $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
      );
      $x = 1;
      foreach($res1 as $row1){
        $myWorkSheet = new PHPExcel_Worksheet($this->phpExcel,$row1['sheettitle']);
        $this->phpExcel->addSheet($myWorkSheet, $i);
        $excel = $this->phpExcel->getSheetByName($row1['sheettitle']);
        $excel->mergeCells('A2:F2');
        $excel->mergeCells('A3:F3');
        $excel->mergeCells('A4:F4');
        $excel->mergeCells('A6:A7');
        $excel->mergeCells('B6:B7');
        $excel->getColumnDimension('A')->setWidth(5);
        $excel->getColumnDimension('B')->setWidth(40);
        $excel->getColumnDimension('C')->setWidth(15);
        $excel->getColumnDimension('D')->setWidth(15);
        $excel->getColumnDimension('E')->setWidth(15);
        $excel->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->
            setCellValueByColumnAndRow($i, $j, $companyname)->
            setCellValueByColumnAndRow($i, $j+1, 'MUTASI '.$row1['accountname'])->
            setCellValueByColumnAndRow($i, $j+2, 'Per : '.date("t F Y", strtotime($_GET['date'])));
          
        $excel
          ->setCellValueByColumnAndRow($i, $j+4, 'NO')
          ->setCellValueByColumnAndRow($i+1, $j+4, 'KETERANGAN')
          ->setCellValueByColumnAndRow($i+2, $j+4, 'BULAN')
          ->setCellValueByColumnAndRow($i+3, $j+4, 'BULAN')
          ->setCellValueByColumnAndRow($i+4, $j+4, 'KUMULATIF');
          
        $excel
          ->setCellValueByColumnAndRow($i+2, $j+5, 'INI')
          ->setCellValueByColumnAndRow($i+3, $j+5, 'LALU')
          ->setCellValueByColumnAndRow($i+4, $j+5, 'S/D BULAN INI');
              
          // get all data
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        
        $subawal = 0;
        $subakhir = 0;
        $subakum = 0;
        $k = 8;
        $l=1;
        foreach($res2 as $row2){
					if($row2['istotal']!=1){
                            if($plantid !=''){
                                $sqlsaldoawal = "call hitungplmonthplant(:vaccountcode,:vdate,:vcompanyid,@vplmonth,@vpllastmonth)";    
                            }
                            else
                            {
                                $sqlsaldoawal = "call hitungplmonth(:vaccountcode,:vdate,:vcompanyid,@vplmonth,@vpllastmonth)";
                            }
                            
                            $command1 = $connection->createCommand($sqlsaldoawal);
                            $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                            $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                            $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                            $command1->execute();

                            $sqlpl = "select @vplmonth as month, @vpllastmonth as lastmonth"; 
                            $stmt1 = Yii::app()->db->createCommand($sqlpl); 
                            $stmt1->execute(); 
                            $pl = $stmt1->queryRow();

                            if($plantid != ''){
                                $sqldebitcredit = "call hitungplakumplant(:vaccountcode,:vdate,:vcompanyid,@vplakum)";
                            }
                            else
                            {
                                $sqldebitcredit = "call hitungplakum(:vaccountcode,:vdate,:vcompanyid,@vplakum)";
                            }
                            
                            $command2 = $connection->createCommand($sqldebitcredit);
                            $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                            $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                            $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                            $command2->execute();

                            $sqlakum = "select @vplakum as plakum"; 
                            $stmt2 = Yii::app()->db->createCommand($sqlakum); 
                            $stmt2->execute(); 
                            $akum = $stmt2->queryRow();
							
							$excel
							->setCellValueByColumnAndRow($i+0, $k, $l)
							->setCellValueByColumnAndRow($i+1, $k, $row2['accountname']);
							
							if($row2['accformula']=='' || $row2['accformula']=='-'){
									
							}else{
									$excel
									->setCellValueByColumnAndRow($i+2, $k, $pl['month']/$per)
									->setCellValueByColumnAndRow($i+3, $k, $pl['lastmonth']/$per)
									->setCellValueByColumnAndRow($i+4, $k, $akum['plakum']/$per);
							}

							$subawal = $subawal + $pl['month'];
							$subakhir = $subakhir + $pl['lastmonth'];
							$subakum = $subakum + $akum['plakum'];
					}else{
							$excel
									->setCellValueByColumnAndRow($i+2, $k, $pl['month']/$per)
									->setCellValueByColumnAndRow($i+3, $k, $pl['lastmonth']/$per)
									->setCellValueByColumnAndRow($i+4, $k, $akum['plakum']/$per);
							
							$subawal = 0;
							$subakhir = 0;
							$subakum = 0;
					}
					$k++;
					$l++;
        }
          $x++;
       
    }
    $this->getFooterXLS($this->phpExcel);  
  }
	public function actionDownPdfNeracayear() {
    parent::actionDownload();
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->AddPage('P');  
    $companyid = $_GET['company'];
    $plantid = $_GET['plant'];
    $date = $_GET['date'];
    $saldoawal = 0;
    $saldoakhir = 0;
    $per = $_GET['per'];
    $connection = Yii::app()->db;
    if($plantid != '') {
        $companyname = $connection->createCommand('select plantcode from plant a where a.plantid = '.$plantid)->queryScalar();
        $companyid = $plantid;
    }
    else
    {
        $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
    }
      
    $sql1 = "select a.*, b.accountname as accheadername, b.accountcode
            from att a 
            join account b on a.accheaderid = b.accountid
            where a.recordstatus = 1 and isneraca = 1 and a.companyid = ".$_GET['company']." order by a.nourut asc";
    $res1 = $connection->createCommand($sql1)->queryAll();
    foreach($res1 as $row1){
        $this->pdf->setFont('Arial','',11);
        $this->pdf->Cell(0, 0, $companyname, 0, 0, 'C');
        $this->pdf->Cell(-187, 10, $row1['accheadername'], 0, 0, 'C');
        $this->pdf->text(85,$this->pdf->gety()+10,'Per Tahun : '.date("Y", strtotime($_GET['date'])));
        
        $i = 0;
        $this->pdf->setFont('Arial', '', 10);
        $this->pdf->sety($this->pdf->gety()+15);
        $this->pdf->colalign  = array(
          'C',
          'L',
          'C',
          'C',
          'C'
        );
        $this->pdf->setwidths(array(
          15,
          60,
          30,
          60,
          30
        ));
        $this->pdf->setbordercell(array(
        'LTR',
        'LTR',
        'LTR',
        'LTRB',
        'LTR'
      ));
        $this->pdf->colheader = array(
          'NO',
          'KETERANGAN',
          'SALDO',
          'MUTASI',
          'SALDO'
        );
        $this->pdf->Rowheader();
        
        $this->pdf->colalign  = array(
          'L',
          'L',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->colheader = array(
          '',
          '',
          'AWAL',
          'DEBIT',
          'CREDIT',
          'AKHIR'
        );
        $this->pdf->setwidths(array(
          15,
          60,
          30,
          30,
          30,
          30
        ));
        $this->pdf->setbordercell(array(
        'LRB',
        'LRB',
        'LRB',
        'LRB',
        'LRB',
        'LRB'
      ));
        $this->pdf->Rowheader();
        
        $this->pdf->coldetailalign = array(
          'C',
          'L',
          'R',
          'R',
          'R',
          'R'
        );
        //$this->pdf->row(array('1','TEST','AWAL','DEBIT','CREDIT','AKHIR'));
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        $i=1;
        $subawal = 0;
        $subakhir = 0;
        $subdebit = 0;
        $subcredit= 0;
        $this->pdf->Line(10,35,10,$this->pdf->gety()); // before NO, first border
        $this->pdf->Line(205,35,205,$this->pdf->gety()); // after akhir, end border
        foreach($res2 as $row2){
            $this->pdf->setFont('Arial','',8);
            if($row2['isbold']==1){
                $this->pdf->SetFont('Arial','B',9);
            }
            
            if($row2['istotal']!=1){
            if($plantid != ''){
                $sqlsaldoawal = "call hitungsaldoyearplant(:vaccountcode,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";    
            }
            else
            {
                $sqlsaldoawal = "call hitungsaldoyear(:vaccountcode,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";
            }
            
            $command1 = $connection->createCommand($sqlsaldoawal);
            $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command1->execute();
            
            $sqlsaldo = "select @vsaldoawal as awal, @vsaldoakhir as akhir"; 
            $stmt1 = Yii::app()->db->createCommand($sqlsaldo); 
            $stmt1->execute(); 
            $saldo = $stmt1->queryRow();
            
            if($plantid != ''){
                $sqldebitcredit = "call hitungdebcredyearplant(:vaccountcode,:vdate,:vcompanyid,@vdebit,@vcredit)";    
            }
            else
            {
                $sqldebitcredit = "call hitungdebcredyear(:vaccountcode,:vdate,:vcompanyid,@vdebit,@vcredit)";
            }
            
            $command2 = $connection->createCommand($sqldebitcredit);
            $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command2->execute();
            
            $sqldebcred = "select @vdebit as debit, @vcredit as credit"; 
            $stmt2 = Yii::app()->db->createCommand($sqldebcred); 
            $stmt2->execute(); 
            $debcred = $stmt2->queryRow();
            
            $this->pdf->setbordercell(array(
            'L',
            'LR',
            'R',
            'R',
            'R',
            'R'));
            
            
            if($row2['accformula']=='' || $row2['accformula']=='-'){
               $this->pdf->row(array($i,$row2['accountname'],'','','',''));
            }else{
                $this->pdf->Line(10,30,10,$this->pdf->gety()); // before NO, first border
                $this->pdf->Line(205,30,205,$this->pdf->gety()); // after akhir, end border
                $this->pdf->Line(115,30,115,$this->pdf->gety()+2); // after saldo awal
                $this->pdf->Line(145,35,145,$this->pdf->gety()+2); // after debit
                $this->pdf->Line(175,30,175,$this->pdf->gety()+2); // after credit
               $this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($saldo['awal']/$per),
                                      Yii::app()->format->formatCurrency($debcred['debit']/$per),
                                      Yii::app()->format->formatCurrency($debcred['credit']/$per),
                                      Yii::app()->format->formatCurrency($saldo['akhir']/$per)));   
            }
            /*
            $this->pdf->text(12,$this->pdf->gety(),$i);
			$this->pdf->text(21,$this->pdf->gety(),$row2['accountname']);
            */
            /*$this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($saldo['awal']/$per),
                                      Yii::app()->format->formatCurrency($debcred['debit']/$per),
                                      Yii::app()->format->formatCurrency($debcred['credit']/$per),
                                      Yii::app()->format->formatCurrency($saldo['akhir']/$per)));
            */
            $subawal = $subawal + $saldo['awal'];
            $subakhir = $subakhir + $saldo['akhir'];
            $subdebit = $subdebit + $debcred['debit'];
            $subcredit = $subcredit + $debcred['credit'];
            }else{
                $this->pdf->Line(10,30,10,$this->pdf->gety()); // before NO, first border
                $this->pdf->Line(205,30,205,$this->pdf->gety()); // after akhir, end border
                $this->pdf->Line(115,30,115,$this->pdf->gety()+2); // after saldo awal
                $this->pdf->Line(145,35,145,$this->pdf->gety()+2); // after debit
                $this->pdf->Line(175,30,175,$this->pdf->gety()+2); // after credit
                $this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($subawal/$per),
                                      Yii::app()->format->formatCurrency($subdebit/$per),
                                      Yii::app()->format->formatCurrency($subcredit/$per),
                                      Yii::app()->format->formatCurrency($subakhir/$per)));   
                
                $subawal = 0;
                $subakhir = 0;
                $subdebit = 0;
                $subcredit = 0;
            }
        $i++;
        }
        //$this->pdf->Rect($this->pdf->getX(),$this->pdf->getY(),5,20,'D');
        $this->pdf->setY($this->pdf->getY()-5);
        $this->pdf->setbordercell(array(
            'LRB',
            'LRB',
            'LRB',
            'LRB',
            'LRB',
            'LRB'));
        $this->pdf->row(array('','','','','',''));
        $this->pdf->checkPageBreak(250);
    }
    
    $this->pdf->Output();
  }
	public function actionDownXlsNeracayear()	{
      //$this->menuname = 'lampiranneraca';
      parent::actionDownXls();
      $companyid = $_GET['company'];
      $plantid = $_GET['plant'];
      $date = $_GET['date'];
      $per = $_GET['per'];
      $connection = Yii::app()->db;
      if($plantid != '') {
          $companyname = $connection->createCommand('select plantcode from plant a where a.plantid = '.$plantid)->queryScalar();
          $companyid = $plantid;
      }
      else
      {
          $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
      }
      $sqldata1 = "select t.*, b.accountname, substr(b.accountname,1,30) as sheettitle ";
      $sqlcount1 = "select count(1) as total ";
      $from = "from att t 
              join company a on t.companyid = a.companyid
              join account b on b.accountid = t.accheaderid
              where isneraca = 1 and t.recordstatus = 1 and t.companyid = ".$_GET['company']." order by t.nourut desc";
      $total = $connection->createCommand($sqlcount1.$from)->queryScalar();
      $res1 = $connection->createCommand($sqldata1.$from)->queryAll();
      
      $i=0;$j=2;
      $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
      );

      foreach($res1 as $row1){
        $myWorkSheet = new PHPExcel_Worksheet($this->phpExcel, $row1['sheettitle']);
        $this->phpExcel->addSheet($myWorkSheet, $i);
        $excel = $this->phpExcel->getSheetByName($row1['sheettitle']);
        $excel->mergeCells('A2:F2');
        $excel->mergeCells('A3:F3');
        $excel->mergeCells('A4:F4');
        $excel->mergeCells('D6:E6');
        $excel->mergeCells('A6:A7');
        $excel->mergeCells('B6:B7');
        $excel->getColumnDimension('A')->setWidth(5);
        $excel->getColumnDimension('B')->setWidth(40);
        $excel->getColumnDimension('C')->setWidth(15);
        $excel->getColumnDimension('D')->setWidth(15);
        $excel->getColumnDimension('E')->setWidth(15);
        $excel->getColumnDimension('F')->setWidth(15);
        $excel->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->
            setCellValueByColumnAndRow($i, $j, $companyname)->
            setCellValueByColumnAndRow($i, $j+1, 'MUTASI '.$row1['accountname'])->
            setCellValueByColumnAndRow($i, $j+2, 'Per Tahun : '.date("Y", strtotime($_GET['date'])));
          
        $excel
          ->setCellValueByColumnAndRow($i, $j+4, 'NO')
          ->setCellValueByColumnAndRow($i+1, $j+4, 'Keterangan')
          ->setCellValueByColumnAndRow($i+2, $j+4, 'Saldo')
          ->setCellValueByColumnAndRow($i+3, $j+4, 'Mutasi')
          ->setCellValueByColumnAndRow($i+5, $j+4, 'Saldo');
          
        $excel
          ->setCellValueByColumnAndRow($i+2, $j+5, 'Awal')
          ->setCellValueByColumnAndRow($i+3, $j+5, 'Debit')
          ->setCellValueByColumnAndRow($i+4, $j+5, 'Credit')
          ->setCellValueByColumnAndRow($i+5, $j+5, 'Akhir');
              
          // get all data
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        
        $subawal = 0;
        $subakhir = 0;
        $subdebit = 0;
        $subcredit= 0;
        $k = 8;
        $l=1;
        foreach($res2 as $row2){
            
            if($row2['istotal']!=1){
                if($plantid !=''){
                    $sqlsaldoawal = "call hitungsaldoyearplant(:vaccountcode,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";   
                }
                else
                {
                    $sqlsaldoawal = "call hitungsaldoyear(:vaccountcode,:vdate,:vcompanyid,@vsaldoawal,@vsaldoakhir)";
                }
                
                $command1 = $connection->createCommand($sqlsaldoawal);
                $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command1->execute();

                $sqlsaldo = "select @vsaldoawal as awal, @vsaldoakhir as akhir"; 
                $stmt1 = Yii::app()->db->createCommand($sqlsaldo); 
                $stmt1->execute(); 
                $saldo = $stmt1->queryRow();

                if($plantid != ''){
                    $sqldebitcredit = "call hitungdebcredyearplant(:vaccountcode,:vdate,:vcompanyid,@vdebit,@vcredit)";   
                }
                else
                {
                    $sqldebitcredit = "call hitungdebcredyear(:vaccountcode,:vdate,:vcompanyid,@vdebit,@vcredit)";
                }
                $command2 = $connection->createCommand($sqldebitcredit);
                $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command2->execute();

                $sqldebcred = "select @vdebit as debit, @vcredit as credit"; 
                $stmt2 = Yii::app()->db->createCommand($sqldebcred); 
                $stmt2->execute(); 
                $debcred = $stmt2->queryRow();
                
                $excel
                ->setCellValueByColumnAndRow($i+0, $k, $l)
                ->setCellValueByColumnAndRow($i+1, $k, $row2['accountname']);
                
                if($row2['accformula']=='' || $row2['accformula']=='-'){
                    
                }else{
                    $excel
                    ->setCellValueByColumnAndRow($i+2, $k, $saldo['awal']/$per)
                    ->setCellValueByColumnAndRow($i+3, $k, $debcred['debit']/$per)
                    ->setCellValueByColumnAndRow($i+4, $k, $debcred['credit']/$per)
                    ->setCellValueByColumnAndRow($i+5, $k, $saldo['akhir']/$per);
                }

                $subawal = $subawal + $saldo['awal'];
                $subakhir = $subakhir + $saldo['akhir'];
                $subdebit = $subdebit + $debcred['debit'];
                $subcredit = $subcredit + $debcred['credit'];
            }else{
                $excel
                ->setCellValueByColumnAndRow($i+0, $k, $l)
                ->setCellValueByColumnAndRow($i+1, $k, $row2['accountname'])
                ->setCellValueByColumnAndRow($i+2, $k, $subawal/$per)
                ->setCellValueByColumnAndRow($i+3, $k, $subdebit/$per)
                ->setCellValueByColumnAndRow($i+4, $k, $subcredit/$per)
                ->setCellValueByColumnAndRow($i+5, $k, $subakhir/$per);
                
                $subawal = 0;
                $subakhir = 0;
                $subdebit = 0;
                $subcredit = 0;
            }
            $k++;
            $l++;
        }
       
    }
    $this->getFooterXLS($this->phpExcel);  
  }
	public function actionDownPdfPLyear()	{
   parent::actionDownload();
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->AddPage('L', array(
      220,
      520
    ));  
    $companyid = $_GET['company'];
    $plantid = $_GET['plant'];
    $date = $_GET['date'];
    $saldoawal = 0;
    $saldoakhir = 0;
    $per = $_GET['per'];
    $connection = Yii::app()->db;
     if($plantid != '') {
          $companyname = $connection->createCommand('select plantcode from plant a where a.plantid = '.$plantid)->queryScalar();
          $companyid = $plantid;
      }
      else
      {
          $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
      }
      
      $sql1 = "select a.*, b.accountname as accheadername, b.accountcode
            from att a 
            join account b on a.accheaderid = b.accountid
            where a.recordstatus = 1 and isneraca = 0 and a.companyid = ".$_GET['company']." order by a.nourut asc";
      $res1 = $connection->createCommand($sql1)->queryAll();
      foreach($res1 as $row1){
        $this->pdf->setFont('Arial','',11);
        $this->pdf->Cell(0, 0, $companyname, 0, 0, 'C');
        $this->pdf->Cell(-500, 10, $row1['accheadername'], 0, 0, 'C');
        $this->pdf->text(237,$this->pdf->gety()+10,'Per : '.date("t F Y", strtotime($_GET['date'])));
        
        $i = 0;
        $this->pdf->setFont('Arial', '', 10);
        $this->pdf->sety($this->pdf->gety()+15);
        $this->pdf->colalign  = array(
          'C',
          'L',
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
        $this->pdf->setwidths(array(
          15,
          90,
          30,
          30,
          30,
          30,
          30,
          30,
          30,
          30,
          30,
          30,
          30,
          30,
          30,
        ));
        $this->pdf->setbordercell(array(
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
      ));
        $this->pdf->colheader = array(
          'NO',
          'KETERANGAN',
          'JANUARI',
          'FEBRUARI',
          'MARET',
					'APRIL',
					'MEI',
					'JUNI',
					'JULI',
					'AGUSTUS',
					'SEPTEMBER',
					'OKTOBER',
					'NOPEMBER',
					'DESEMBER',
					'TOTAL',
        );
        $this->pdf->Rowheader();
        
        $this->pdf->coldetailalign = array(
          'C',
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
        );
        //$this->pdf->row(array('1','TEST','AWAL','DEBIT','CREDIT','AKHIR'));
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        $i=1;
        $subjan = 0;
        $subfeb = 0;
        $submar = 0;
        $subapr = 0;
        $submei = 0;
        $subjun = 0;
        $subjul = 0;
        $subags = 0;
        $subsep = 0;
        $subokt = 0;
        $subnop = 0;
        $subdes = 0;
        $subtotal = 0;
        $this->pdf->Line(10,35,10,$this->pdf->gety()); // before NO, first border
        $this->pdf->Line(205,35,205,$this->pdf->gety()); // after akhir, end border
        foreach($res2 as $row2){
            $this->pdf->setFont('Arial','',8);
            if($row2['isbold']==1){
                $this->pdf->SetFont('Arial','B',9);
            }
            
            if($row2['istotal']!=1){
            if($plantid != ''){
                $sqlsaldoawal = "call hitungplyearplant(:vaccountcode,:vdate,:vcompanyid,@vpljan,@vplfeb,@vplmar,@vplapr,@vplmei,@vpljun,@vpljul,@vplags,@vplsep,@vplokt,@vplnop,@vpldes)";   
            }
            else {
                $sqlsaldoawal = "call hitungplyear(:vaccountcode,:vdate,:vcompanyid,@vpljan,@vplfeb,@vplmar,@vplapr,@vplmei,@vpljun,@vpljul,@vplags,@vplsep,@vplokt,@vplnop,@vpldes)";
            }
            
            $command1 = $connection->createCommand($sqlsaldoawal);
            $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command1->execute();
            
            $sqlpl = "select @vpljan as jan,@vplfeb as feb,@vplmar as mar,@vplapr as apr,@vplmei as mei,@vpljun as jun,@vpljul as jul,@vplags as ags,@vplsep as sep,@vplokt as okt,@vplnop as nop,@vpldes as des"; 
            $stmt1 = Yii::app()->db->createCommand($sqlpl); 
            $stmt1->execute(); 
            $pl = $stmt1->queryRow();
            
            $this->pdf->setbordercell(array(
							'L',
							'LR',
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
						));
            
            
            if($row2['accformula']=='' || $row2['accformula']=='-'){
               $this->pdf->row(array($i,$row2['accountname'],'','','','','','','','','','','','',''));
            }else{
                $this->pdf->Line(10,30,10,$this->pdf->gety()); // before NO, first border
                $this->pdf->Line(205,30,205,$this->pdf->gety()); // after akhir, end border
                $this->pdf->Line(115,30,115,$this->pdf->gety()+2); // after saldo awal
                $this->pdf->Line(145,35,145,$this->pdf->gety()+2); // after debit
                $this->pdf->Line(175,30,175,$this->pdf->gety()+2); // after credit
								$this->pdf->row(array($i,
									$row2['accountname'],
									Yii::app()->format->formatCurrency($pl['jan']/$per),
									Yii::app()->format->formatCurrency($pl['feb']/$per),
									Yii::app()->format->formatCurrency($pl['mar']/$per),
									Yii::app()->format->formatCurrency($pl['apr']/$per),
									Yii::app()->format->formatCurrency($pl['mei']/$per),
									Yii::app()->format->formatCurrency($pl['jun']/$per),
									Yii::app()->format->formatCurrency($pl['jul']/$per),
									Yii::app()->format->formatCurrency($pl['ags']/$per),
									Yii::app()->format->formatCurrency($pl['sep']/$per),
									Yii::app()->format->formatCurrency($pl['okt']/$per),
									Yii::app()->format->formatCurrency($pl['nop']/$per),
									Yii::app()->format->formatCurrency($pl['des']/$per),
									Yii::app()->format->formatCurrency(($pl['jan']+$pl['feb']+$pl['mar']+$pl['apr']+$pl['mei']+$pl['jun']+$pl['jul']+$pl['ags']+$pl['sep']+$pl['okt']+$pl['nop']+$pl['des'])/$per),
								));   
            }
            /*
            $this->pdf->text(12,$this->pdf->gety(),$i);
			$this->pdf->text(21,$this->pdf->gety(),$row2['accountname']);
            */
            /*$this->pdf->row(array($i,
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($saldo['awal']/$per),
                                      Yii::app()->format->formatCurrency($debcred['debit']/$per),
                                      Yii::app()->format->formatCurrency($debcred['credit']/$per),
                                      Yii::app()->format->formatCurrency($saldo['akhir']/$per)));
            */
            $subjan = $subjan + $pl['jan'];
            $subfeb = $subfeb + $pl['feb'];
            $submar = $submar + $pl['mar'];
            $subapr = $subapr + $pl['apr'];
            $submei = $submei + $pl['mei'];
            $subjun = $subjun + $pl['jun'];
            $subjul = $subjul + $pl['jul'];
            $subags = $subags + $pl['ags'];
            $subsep = $subsep + $pl['sep'];
            $subokt = $subokt + $pl['okt'];
            $subnop = $subnop + $pl['nop'];
            $subdes = $subdes + $pl['des'];
            $subtotal = $subtotal + ($pl['jan']+$pl['feb']+$pl['mar']+$pl['apr']+$pl['mei']+$pl['jun']+$pl['jul']+$pl['ags']+$pl['sep']+$pl['okt']+$pl['nop']+$pl['des']);
            
            }else{
                $this->pdf->row(array($i,
									$row2['accountname'],
									Yii::app()->format->formatCurrency($subjan/$per),
									Yii::app()->format->formatCurrency($subfeb/$per),
									Yii::app()->format->formatCurrency($submar/$per),
									Yii::app()->format->formatCurrency($subapr/$per),
									Yii::app()->format->formatCurrency($submei/$per),
									Yii::app()->format->formatCurrency($subjun/$per),
									Yii::app()->format->formatCurrency($subjul/$per),
									Yii::app()->format->formatCurrency($subags/$per),
									Yii::app()->format->formatCurrency($subsep/$per),
									Yii::app()->format->formatCurrency($subokt/$per),
									Yii::app()->format->formatCurrency($subnop/$per),
									Yii::app()->format->formatCurrency($subdes/$per),
									Yii::app()->format->formatCurrency($subtotal/$per),
								));   
                
                $subjan = 0;
								$subfeb = 0;
								$submar = 0;
								$subapr = 0;
								$submei = 0;
								$subjun = 0;
								$subjul = 0;
								$subags = 0;
								$subsep = 0;
								$subokt = 0;
								$subnop = 0;
								$subdes = 0;
								$subtotal = 0;
            }
        $i++;
        }
        //$this->pdf->Rect($this->pdf->getX(),$this->pdf->getY(),5,20,'D');
        $this->pdf->setY($this->pdf->getY()-5);
        $this->pdf->setbordercell(array(
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
					'LRB',
				));
        $this->pdf->row(array('','','','','','','','','','','','','','',''));
        $this->pdf->checkPageBreak(250);
    }
    
    $this->pdf->Output();
  }
	public function actionDownXlsPLyear()	{
      parent::actionDownXls();
      //$this->menuname = 'lampiranlabarugi';
      $companyid = $_GET['company'];
      $plantid = $_GET['plant'];
      $date = $_GET['date'];
      $per = $_GET['per'];
      $connection = Yii::app()->db;
      if($plantid != '') {
          $companyname = $connection->createCommand('select plantcode from plant a where a.plantid = '.$plantid)->queryScalar();
          $companyid = $plantid;
      }
      else
      {
          $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
      }
      $sqldata1 = "select t.*, b.accountname, substr(b.accountname,1,30) as sheettitle ";
      $sqlcount1 = "select count(1) as total ";
      $from = "from att t 
              join company a on t.companyid = a.companyid
              join account b on b.accountid = t.accheaderid
              where isneraca = 0 and t.recordstatus = 1 and t.companyid = ".$_GET['company']." order by t.nourut desc";
      $total = $connection->createCommand($sqlcount1.$from)->queryScalar();
      $res1 = $connection->createCommand($sqldata1.$from)->queryAll();
      
      $i=0;$j=2;
      $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
      );
      $x = 1;
      foreach($res1 as $row1){
        $myWorkSheet = new PHPExcel_Worksheet($this->phpExcel,$row1['sheettitle']);
        $this->phpExcel->addSheet($myWorkSheet, $i);
        $excel = $this->phpExcel->getSheetByName($row1['sheettitle']);
        $excel->mergeCells('A2:O2');
        $excel->mergeCells('A3:O3');
        $excel->mergeCells('A4:O4');
        $excel->mergeCells('A6:A7');
        $excel->mergeCells('B6:B7');
        $excel->mergeCells('C6:N6');
        $excel->mergeCells('O6:O7');
        $excel->getColumnDimension('A')->setWidth(5);
        $excel->getColumnDimension('B')->setWidth(40);
        $excel->getColumnDimension('C')->setWidth(15);
        $excel->getColumnDimension('D')->setWidth(15);
        $excel->getColumnDimension('E')->setWidth(15);
        $excel->getColumnDimension('F')->setWidth(15);
        $excel->getColumnDimension('G')->setWidth(15);
        $excel->getColumnDimension('H')->setWidth(15);
        $excel->getColumnDimension('I')->setWidth(15);
        $excel->getColumnDimension('J')->setWidth(15);
        $excel->getColumnDimension('K')->setWidth(15);
        $excel->getColumnDimension('L')->setWidth(15);
        $excel->getColumnDimension('M')->setWidth(15);
        $excel->getColumnDimension('N')->setWidth(15);
        $excel->getColumnDimension('O')->setWidth(15);
        $excel->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('O6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('J7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('K7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('L7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('M7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('N7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->
            setCellValueByColumnAndRow($i, $j, $companyname)->
            setCellValueByColumnAndRow($i, $j+1, 'MUTASI '.$row1['accountname'])->
            setCellValueByColumnAndRow($i, $j+2, 'Periode '.date("Y", strtotime($_GET['date'])));
          
        $excel
          ->setCellValueByColumnAndRow($i, $j+4, 'NO')
          ->setCellValueByColumnAndRow($i+1, $j+4, 'KETERANGAN')
          ->setCellValueByColumnAndRow($i+2, $j+4, 'BULAN')
          ->setCellValueByColumnAndRow($i+14, $j+4, 'TOTAL');
          
        $excel
          ->setCellValueByColumnAndRow($i+2, $j+5, 'JANUARI')
          ->setCellValueByColumnAndRow($i+3, $j+5, 'FEBRUARI')
          ->setCellValueByColumnAndRow($i+4, $j+5, 'MARET')
          ->setCellValueByColumnAndRow($i+5, $j+5, 'APRIL')
          ->setCellValueByColumnAndRow($i+6, $j+5, 'MEI')
          ->setCellValueByColumnAndRow($i+7, $j+5, 'JUNI')
          ->setCellValueByColumnAndRow($i+8, $j+5, 'JULI')
          ->setCellValueByColumnAndRow($i+9, $j+5, 'AGUSTUS')
          ->setCellValueByColumnAndRow($i+10, $j+5, 'SEPTEMBER')
          ->setCellValueByColumnAndRow($i+11, $j+5, 'OKTOBER')
          ->setCellValueByColumnAndRow($i+12, $j+5, 'NOPEMBER')
          ->setCellValueByColumnAndRow($i+13, $j+5, 'DESEMBER');
              
          // get all data
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        
        $subjan = 0;
				$subfeb = 0;
				$submar = 0;
				$subapr = 0;
				$submei = 0;
				$subjun = 0;
				$subjul = 0;
				$subags = 0;
				$subsep = 0;
				$subokt = 0;
				$subnop = 0;
				$subdes = 0;
				$subtotal = 0;
        $k = 8;
        $l=1;
        foreach($res2 as $row2){
            if($row2['istotal']!=1){

                if($plantid != '') {
                    $sqlsaldoawal = "call hitungplyearplant(:vaccountcode,:vdate,:vcompanyid,@vpljan,@vplfeb,@vplmar,@vplapr,@vplmei,@vpljun,@vpljul,@vplags,@vplsep,@vplokt,@vplnop,@vpldes)";
                }
                else
                {
                    $sqlsaldoawal = "call hitungplyear(:vaccountcode,:vdate,:vcompanyid,@vpljan,@vplfeb,@vplmar,@vplapr,@vplmei,@vpljun,@vpljul,@vplags,@vplsep,@vplokt,@vplnop,@vpldes)";
                }
                           
                $command1 = $connection->createCommand($sqlsaldoawal);
                $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command1->execute();

                $sqlpl = "select @vpljan as jan,@vplfeb as feb,@vplmar as mar,@vplapr as apr,@vplmei as mei,@vpljun as jun,@vpljul as jul,@vplags as ags,@vplsep as sep,@vplokt as okt,@vplnop as nop,@vpldes as des"; 
                $stmt1 = Yii::app()->db->createCommand($sqlpl); 
                $stmt1->execute(); 
                $pl = $stmt1->queryRow();

                        $excel
                        ->setCellValueByColumnAndRow($i+0, $k, $l)
                        ->setCellValueByColumnAndRow($i+1, $k, $row2['accountname']);

                        if($row2['accformula']=='' || $row2['accformula']=='-'){

                        }else{
                                $excel
                                ->setCellValueByColumnAndRow($i+2, $k, $pl['jan']/$per)
                                ->setCellValueByColumnAndRow($i+3, $k, $pl['feb']/$per)
                                ->setCellValueByColumnAndRow($i+4, $k, $pl['mar']/$per)
                                ->setCellValueByColumnAndRow($i+5, $k, $pl['apr']/$per)
                                ->setCellValueByColumnAndRow($i+6, $k, $pl['mei']/$per)
                                ->setCellValueByColumnAndRow($i+7, $k, $pl['jun']/$per)
                                ->setCellValueByColumnAndRow($i+8, $k, $pl['jul']/$per)
                                ->setCellValueByColumnAndRow($i+9, $k, $pl['ags']/$per)
                                ->setCellValueByColumnAndRow($i+10, $k, $pl['sep']/$per)
                                ->setCellValueByColumnAndRow($i+11, $k, $pl['okt']/$per)
                                ->setCellValueByColumnAndRow($i+12, $k, $pl['nop']/$per)
                                ->setCellValueByColumnAndRow($i+13, $k, $pl['des']/$per)
                                ->setCellValueByColumnAndRow($i+14, $k, ($pl['jan']+$pl['feb']+$pl['mar']+$pl['apr']+$pl['mei']+$pl['jun']+$pl['jul']+$pl['ags']+$pl['sep']+$pl['okt']+$pl['nop']+$pl['des'])/$per);
                        }

                        $subjan = $subjan + $pl['jan'];
                        $subfeb = $subfeb + $pl['feb'];
                        $submar = $submar + $pl['mar'];
                        $subapr = $subapr + $pl['apr'];
                        $submei = $submei + $pl['mei'];
                        $subjun = $subjun + $pl['jun'];
                        $subjul = $subjul + $pl['jul'];
                        $subags = $subags + $pl['ags'];
                        $subsep = $subsep + $pl['sep'];
                        $subokt = $subokt + $pl['okt'];
                        $subnop = $subnop + $pl['nop'];
                        $subdes = $subdes + $pl['des'];
                        $subtotal = $subtotal + ($pl['jan']+$pl['feb']+$pl['mar']+$pl['apr']+$pl['mei']+$pl['jun']+$pl['jul']+$pl['ags']+$pl['sep']+$pl['okt']+$pl['nop']+$pl['des']);
                }else{
                        $excel
                                ->setCellValueByColumnAndRow($i+2, $k, $pl['jan']/$per)
                                ->setCellValueByColumnAndRow($i+3, $k, $pl['feb']/$per)
                                ->setCellValueByColumnAndRow($i+4, $k, $pl['mar']/$per);

                        $subjan = 0;
                        $subfeb = 0;
                        $submar = 0;
                        $subapr = 0;
                        $submei = 0;
                        $subjun = 0;
                        $subjul = 0;
                        $subags = 0;
                        $subsep = 0;
                        $subokt = 0;
                        $subnop = 0;
                        $subdes = 0;
                        $subtotal = 0;
                }
                $k++;
                $l++;
        }
          $x++;
       
    }
    $this->getFooterXLS($this->phpExcel);  
  }
	public function actionDownPDFLampiranNeraca1() {
        parent::actionDownload();
        $companyid = $_GET['company'];
        $plantid = $_GET['plant'];
        $per = $_GET['per'];
        $date = $_GET['date'];
        $connection = Yii::app()->db;
        $totalawal1=0;$totaldebit1=0;$totalcredit1=0;
        
        if($plantid != '') {
            $companyname = $connection->createCommand('select plantcode from plant a where a.plantid = '.$plantid)->queryScalar();
            $companyid = $plantid;
        }
        else
        {
            $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
        }
        $sql = "SELECT a.accountname, a.accountcode
                from repneraca a
                where a.companyid = '".$_GET['company']."' AND LOWER(a.accountname) <> LOWER('AKTIVA LANCAR') AND LOWER(a.accountname) <> LOWER('AKTIVA TETAP') AND LOWER(a.accountname) <> LOWER('AKTIVA LAIN-LAIN') AND LOWER(a.accountname) <> LOWER('AKTIVA') AND LOWER(a.accountname) <> LOWER('KEWAJIBAN LANCAR') AND LOWER(a.accountname) <> LOWER('KEWAJIBAN JANGKA PANJANG') AND LOWER(a.accountname) <> LOWER('EKUITAS') AND LOWER(a.accountname) <> LOWER('PASIVA') AND LOWER(a.accountname) <> LOWER('PERSEDIAAN')";

		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		foreach($dataReader as $row)
		{
				$this->pdf->companyid = $companyid;
		}
		$this->pdf->title='Lampiran Neraca';
		$this->pdf->subtitle = 'Per Tanggal : '.date(Yii::app()->params['dateviewfromdb'], strtotime($date));
		$this->pdf->AddPage('P','A4');

		foreach($dataReader as $row)
		{
			$this->pdf->SetFont('Arial','B',10);
			//$this->pdf->text(10,$this->pdf->gety()+10,$row['accountcode']);
						$this->pdf->text(10,$this->pdf->gety()+3,'MUTASI '.$row['accountname']);

						$sql1 = "select a.accountname,a.accountcode
                                 from account a
                                 where a.recordstatus = 1 and a.parentaccountid = (SELECT b.accountid FROM account b WHERE b.accountcode= '".$row['accountcode']."' AND b.companyid='".$_GET['company']."')
                                 order by a.accountid";

			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			$saldo=0;$i=0;

			$this->pdf->setFont('Arial','B',8);
			$this->pdf->sety($this->pdf->gety()+7);
			$this->pdf->colalign = array('C','C','C','C','C','C');
			$this->pdf->setwidths(array(10,70,28,28,28,28));
			$this->pdf->colheader = array('No','Keterangan','Saldo Awal','Debit','Kredit','Saldo Akhir');
			$this->pdf->RowHeader();
			$this->pdf->coldetailalign = array('C','L','R','R','R','R');		
			$saldo=0;$i=0;$totaldebit=0;$totalcredit=0;
            
            if($plantid != '')
            {
                $sqlcompanyid = ' and b.plantid = '.$plantid;
            }
            else
            {
                $sqlcompanyid = ' and b.companyid = '.$_GET['company'];
            }
			$sql2 = "SELECT SUM(b.debit-b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE    c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row['accountcode']."' AND concat('".$row['accountcode']."','9999999999') AND b.journaldate <= last_day(date_add( '".date(Yii::app()->params['datetodb'],strtotime($date))."', interval -1 month)) ";
			$command2=$this->connection->createCommand($sql2);
			$saldoawal1=$command2->queryScalar();

			$sql3 = "SELECT SUM(b.debit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE c.recordstatus=3  {$sqlcompanyid} AND b.accountcode BETWEEN '".$row['accountcode']."' AND concat('".$row['accountcode']."','9999999999') and month(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' and year(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' ";
			$command3=$this->connection->createCommand($sql3);
			$debit1=$command3->queryScalar();

			$sql4 = "SELECT SUM(b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE c.recordstatus=3  {$sqlcompanyid} AND b.accountcode BETWEEN '".$row['accountcode']."' AND concat('".$row['accountcode']."','9999999999') AND month(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' and year(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' ";
			$command4=$this->connection->createCommand($sql4);
			$credit1=$command4->queryScalar();

			$sql5 = "SELECT SUM(b.debit-b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE    c.recordstatus=3  {$sqlcompanyid} AND b.accountcode BETWEEN '".$row['accountcode']."' AND concat('".$row['accountcode']."','9999999999') AND b.journaldate <= '".date(Yii::app()->params['datetodb'], strtotime($date)) ."' ";
			$command5=$this->connection->createCommand($sql5);
			$saldoakhir1=$command5->queryScalar();

			$this->pdf->setFont('Arial','B',8);
			$this->pdf->row(array(
			'',$row['accountname'],
			Yii::app()->format->formatCurrency($saldoawal1/$per),
			Yii::app()->format->formatCurrency($debit1/$per),
			Yii::app()->format->formatCurrency($credit1/$per),
			Yii::app()->format->formatCurrency($saldoakhir1/$per)
			));	

			foreach($dataReader1 as $row1)
			{

				$sql6 = "SELECT SUM(b.debit-b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE    c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') AND b.journaldate <= last_day(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval -1 month)) ";
				$command6=$this->connection->createCommand($sql6);
				$saldoawal2=$command6->queryScalar();

				$sql7 = "SELECT SUM(b.debit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') AND month(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' and year(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' ";
				$command7=$this->connection->createCommand($sql7);
				$debit2=$command7->queryScalar();

				$sql8 = "SELECT SUM(b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') AND month(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' and year(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' ";
				$command8=$this->connection->createCommand($sql8);
				$credit2=$command8->queryScalar();

				$sql9 = "SELECT SUM(b.debit-b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE    c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') AND b.journaldate <= '".date(Yii::app()->params['datetodb'], strtotime($date)) ."' ";
				$command9=$this->connection->createCommand($sql9);
				$saldoakhir2=$command9->queryScalar();

					$i+=1;
					$this->pdf->setFont('Arial','',8);
					$this->pdf->row(array(
					$i,$row1['accountname'],
					Yii::app()->format->formatCurrency($saldoawal2/$per),
					Yii::app()->format->formatCurrency($debit2/$per),
					Yii::app()->format->formatCurrency($credit2/$per),
					Yii::app()->format->formatCurrency($saldoakhir2/$per)
					));	    
			}
				$this->pdf->checkPageBreak(250);
		}

		$this->pdf->Output();
    }
  public function actionDownXLSLampiranNeraca1() {
        $this->menuname='Lampiranneraca1';
		parent::actionDownxls();
        $companyid = $_GET['company'];
        $plantid = $_GET['plant'];
        $per = $_GET['per'];
        $date = $_GET['date'];
        $totalawal1=0;$totaldebit1=0;$totalcredit1=0;
        $sql = "SELECT a.accountname, a.accountcode
                from repneraca a
                where a.companyid = '".$_GET['company']."' AND LOWER(a.accountname) <> LOWER('AKTIVA LANCAR') AND LOWER(a.accountname) <> LOWER('AKTIVA TETAP') AND LOWER(a.accountname) <> LOWER('AKTIVA LAIN-LAIN') AND LOWER(a.accountname) <> LOWER('AKTIVA') AND LOWER(a.accountname) <> LOWER('KEWAJIBAN LANCAR') AND LOWER(a.accountname) <> LOWER('KEWAJIBAN JANGKA PANJANG') AND LOWER(a.accountname) <> LOWER('EKUITAS') AND LOWER(a.accountname) <> LOWER('PASIVA') AND LOWER(a.accountname) <> LOWER('PERSEDIAAN')";

		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		foreach($dataReader as $row)
		{
				//$this->pdf->companyid = $companyid;
		}
        $this->phpExcel->setActiveSheetIndex(0)	
            //->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
            ->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($date)))
            ->setCellValueByColumnAndRow(6,1,getcompanycode($companyid));
        $line=2;
		foreach($dataReader as $row)
		{
            $line=$line+2;
            $this->phpExcel->setActiveSheetIndex(0)	
            ->setCellValueByColumnAndRow(0,$line,'MUTASI '.$row['accountname']);

            $sql1 = "select a.accountname,a.accountcode
                     from account a
                     where a.recordstatus = 1 and a.parentaccountid = (SELECT b.accountid FROM account b WHERE b.accountcode= '".$row['accountcode']."' AND b.companyid='".$_GET['company']."')
                     order by a.accountid";

			$command1=$this->connection->createCommand($sql1);
			$dataReader1=$command1->queryAll();
			$saldo=0;$i=0;
            $line++;

            $this->phpExcel->setActiveSheetIndex(0)	
                ->setCellValueByColumnAndRow(0,$line,'No')
                ->setCellValueByColumnAndRow(1,$line,'Keterangan')
                ->setCellValueByColumnAndRow(2,$line,'Saldo Awal')
                ->setCellValueByColumnAndRow(3,$line,'Debit')
                ->setCellValueByColumnAndRow(4,$line,'Kredit')
                ->setCellValueByColumnAndRow(5,$line,'Saldo Akhir');
			
			$saldo=0;$i=0;$totaldebit=0;$totalcredit=0;
            if($plantid != '')
            {
                $sqlcompanyid = ' and b.plantid = '.$plantid;
            }
            else
            {
                $sqlcompanyid = ' and b.companyid = '.$_GET['company'];
            }
          
			$sql2 = "SELECT SUM(b.debit-b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE    c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row['accountcode']."' AND concat('".$row['accountcode']."','9999999999') AND b.journaldate <= last_day(date_add( '".date(Yii::app()->params['datetodb'],strtotime($date))."', interval -1 month)) ";
			$command2=$this->connection->createCommand($sql2);
			$saldoawal1=$command2->queryScalar();

			$sql3 = "SELECT SUM(b.debit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE c.recordstatus=3  {$sqlcompanyid} AND b.accountcode BETWEEN '".$row['accountcode']."' AND concat('".$row['accountcode']."','9999999999') and month(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' and year(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' ";
			$command3=$this->connection->createCommand($sql3);
			$debit1=$command3->queryScalar();

			$sql4 = "SELECT SUM(b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE c.recordstatus=3  {$sqlcompanyid} AND b.accountcode BETWEEN '".$row['accountcode']."' AND concat('".$row['accountcode']."','9999999999') AND month(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' and year(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' ";
			$command4=$this->connection->createCommand($sql4);
			$credit1=$command4->queryScalar();

			$sql5 = "SELECT SUM(b.debit-b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE    c.recordstatus=3  {$sqlcompanyid} AND b.accountcode BETWEEN '".$row['accountcode']."' AND concat('".$row['accountcode']."','9999999999') AND b.journaldate <= '".date(Yii::app()->params['datetodb'], strtotime($date)) ."' ";
			$command5=$this->connection->createCommand($sql5);
			$saldoakhir1=$command5->queryScalar();

            $line++;
			$this->phpExcel->setActiveSheetIndex(0)	
                ->setCellValueByColumnAndRow(0,$line,'')
                ->setCellValueByColumnAndRow(1,$line,$row['accountname'])
                ->setCellValueByColumnAndRow(2,$line,($saldoawal1))
                ->setCellValueByColumnAndRow(3,$line,($debit1))
                ->setCellValueByColumnAndRow(4,$line,($credit1))
                ->setCellValueByColumnAndRow(5,$line,($saldoakhir1));
            
            $line++;

			foreach($dataReader1 as $row1)
			{

				$sql6 = "SELECT SUM(b.debit-b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE    c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') AND b.journaldate <= last_day(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval -1 month)) ";
				$command6=$this->connection->createCommand($sql6);
				$saldoawal2=$command6->queryScalar();

				$sql7 = "SELECT SUM(b.debit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') AND month(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' and year(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' ";
				$command7=$this->connection->createCommand($sql7);
				$debit2=$command7->queryScalar();

				$sql8 = "SELECT SUM(b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') AND month(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' and year(b.journaldate) = '".date(Yii::app()->params['datetodb'],strtotime($date))."' ";
				$command8=$this->connection->createCommand($sql8);
				$credit2=$command8->queryScalar();

				$sql9 = "SELECT SUM(b.debit-b.credit) FROM genledger b JOIN genjournal c on c.genjournalid=b.genjournalid WHERE    c.recordstatus=3 {$sqlcompanyid} AND b.accountcode BETWEEN '".$row1['accountcode']."' AND concat('".$row1['accountcode']."','9999999999') AND b.journaldate <= '".date(Yii::app()->params['datetodb'], strtotime($date)) ."' ";
				$command9=$this->connection->createCommand($sql9);
				$saldoakhir2=$command9->queryScalar();

					$i+=1;
					$this->phpExcel->setActiveSheetIndex(0)	
                        ->setCellValueByColumnAndRow(0,$line,$i)
                        ->setCellValueByColumnAndRow(1,$line,$row1['accountname'])
                        ->setCellValueByColumnAndRow(2,$line,($saldoawal2))
                        ->setCellValueByColumnAndRow(3,$line,($debit2))
                        ->setCellValueByColumnAndRow(4,$line,($credit2))
                        ->setCellValueByColumnAndRow(5,$line,($saldoakhir2));
                $line++;
			}
		}
        $line++;
		$this->getFooterXLS($this->phpExcel);
    }
  public function actionDownPdfPL1() {
    parent::actionDownload();
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->AddPage('L',array(210,405));  
    $companyid = $_GET['company'];
    $plantid = $_GET['plant'];
    $date = $_GET['date'];
    $saldoawal = 0;
    $saldoakhir = 0;
    $per = $_GET['per'];
    $connection = Yii::app()->db;
    if($plantid != '') {
        $companyname = $connection->createCommand('select concat("PLANT : ",plantcode) from plant a where a.plantid = '.$plantid)->queryScalar();
        $companyid = $plantid;
    }
    else
    {
        $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
    }
    //$companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
    
    $sql1 = "select a.*, b.accountname as accheadername, b.accountcode
            from att a 
            join account b on a.accheaderid = b.accountid
            where a.recordstatus = 1 and isneraca = 0 and a.companyid = ".$_GET['company']." order by a.nourut asc";
    $res1 = $connection->createCommand($sql1)->queryAll();
    
    if($plantid != '') {
      $sqlcompanyid = ' a.plantid = '.$plantid;
    }
    else {
      $sqlcompanyid = ' a.companyid = '.$_GET['company'];
    }
        
    $sqlactnettnow = "select sum(debit-credit)*-1
                    from genledger a
                    join genjournal b on b.genjournalid = a.genjournalid
                    where {$sqlcompanyid} and b.journaldate between date_add(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval 1 day), interval -1 month) and last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."') and b.recordstatus=3 
                    and a.accountcode between '31' and '3999999999999999999'";
    $actnettnow = Yii::app()->db->createCommand($sqlactnettnow)->queryScalar();
        
    $sqlactnettlast = "select sum(debit-credit)*-1
                    from genledger a
                    join genjournal b on b.genjournalid = a.genjournalid
                    where {$sqlcompanyid} and b.journaldate between date_add(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval 1 day), interval -2 month) and last_day(date_add(last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."'), interval -1 month)) and b.recordstatus=3 
                    and a.accountcode between '31' and '3999999999999999999'";
    $actnettlast = Yii::app()->db->createCommand($sqlactnettlast)->queryScalar();
        
    $sqlactnettakum = "select sum(debit-credit)*-1
                    from genledger a
                    join genjournal b on b.genjournalid = a.genjournalid
                    where {$sqlcompanyid} and b.journaldate between concat(year(last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."')),'-01-01') and last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."') and b.recordstatus=3 
                    and a.accountcode between '31' and '3999999999999999999'";
    $actnettakum = Yii::app()->db->createCommand($sqlactnettakum)->queryScalar();
      
      
    $sqlbudnettnow = "select sum(budgetamount)*-1
                    from budget a
                    -- join genjournal b on b.genjournalid = a.genjournalid
                    where {$sqlcompanyid} and a.budgetdate between date_add(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval 1 day), interval -1 month) and last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."') 
                    and a.accountcode between '31' and '3999999999999999999'";
    $budnettnow = Yii::app()->db->createCommand($sqlbudnettnow)->queryScalar();
        
    $sqlbudnettlast = "select sum(budgetamount)*-1
                    from budget a
                    -- join genjournal b on b.genjournalid = a.genjournalid
                    where {$sqlcompanyid} and a.budgetdate between date_add(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval 1 day), interval -2 month) and last_day(date_add(last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."'), interval -1 month))
                    and a.accountcode between '31' and '3999999999999999999'";
    $budnettlast = Yii::app()->db->createCommand($sqlbudnettlast)->queryScalar();
        
    $sqlbudnettakum = "select sum(budgetamount)*-1
                    from budget a
                    -- join genjournal b on b.genjournalid = a.genjournalid
                    where {$sqlcompanyid} and a.budgetdate between concat(year(last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."')),'-01-01') and last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."')
                    and a.accountcode between '31' and '3999999999999999999'";
    $budnettakum = Yii::app()->db->createCommand($sqlbudnettakum)->queryScalar();
    
    foreach($res1 as $row1){
        $this->pdf->setFont('Arial','',11);
        $this->pdf->Cell(0, 0, $companyname, 0, 0, 'C');
        $this->pdf->Cell(-385, 10, $row1['accheadername'], 0, 0, 'C');
        $this->pdf->text(180,$this->pdf->gety()+10,'Per : '.date("t F Y", strtotime($_GET['date'])));
        
        $i = 0;
        $this->pdf->setFont('Arial', '', 10);
        $this->pdf->sety($this->pdf->gety()+15);
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
      75,
      102,
      102,
      106
    ));
    $this->pdf->Rowheader();
    $this->pdf->colalign  = array(
      'C',
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
      'R'
    );
    $this->pdf->colheader = array(
      'Keterangan',
      'Budget',
      '%',
      'Actual',
      '%',
      'Penc',
      'Budget',
      '%',
      'Actual',
      '%',
      'Penc',
      'Budget',
      '%',
      'Actual',
      '%',
      'Penc'
      
    );
    $this->pdf->setwidths(array(
      75,
      30,
      14,
      30,
      14,
      14,
      30,
      14,
      30,
      14,
      14,
      32,
      14,
      32,
      14,
      14
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
      'R'
    );
        //$this->pdf->row(array('1','TEST','AWAL','DEBIT','CREDIT','AKHIR'));
        $sql2 = "select a.isbold, a.istotal, a.accformula, a.accountname, if(a.accountid is null,0,a.accountid) as accountid from attdet a where a.isview = 1 and a.attid = ".$row1['attid']."  order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        $i=1;
        $subbudmonth = 0;
        $subactmonth = 0;
        $subbudlast = 0;
        $subactlast = 0;
        $subbudakum = 0;
        $subactakum = 0;
        //$this->pdf->Line(10,35,10,$this->pdf->gety()); // before NO, first border
        //$this->pdf->Line(205,35,205,$this->pdf->gety()); // after akhir, end border
        foreach($res2 as $row2){
            $this->pdf->setFont('Arial','',8);
            if($row2['isbold']==1){
                $this->pdf->SetFont('Arial','B',9);
            }
            
            if($row2['istotal']!=1){
            if($plantid != '') {
              $sqlsaldoawal = "call hitungplmonthplant(:vaccountcode,:vdate,:vcompanyid,@vplmonth,@vpllastmonth)";
            } else {
              $sqlsaldoawal = "call hitungplmonth(:vaccountcode,:vdate,:vcompanyid,@vplmonth,@vpllastmonth)";
            }
            $command1 = $connection->createCommand($sqlsaldoawal);
            $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command1->execute();
            
            $sqlpl = "select @vplmonth as month, @vpllastmonth as lastmonth"; 
            $stmt1 = Yii::app()->db->createCommand($sqlpl); 
            $stmt1->execute(); 
            $pl = $stmt1->queryRow();
            
            if($plantid != '') {
              $sqldebitcredit = "call hitungplakumplant(:vaccountcode,:vdate,:vcompanyid,@vplakum)";
            } 
            else {
              $sqldebitcredit = "call hitungplakum(:vaccountcode,:vdate,:vcompanyid,@vplakum)";
            }
            $command2 = $connection->createCommand($sqldebitcredit);
            $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command2->execute();
            
            $sqlakum = "select @vplakum as plakum"; 
            $stmt2 = Yii::app()->db->createCommand($sqlakum); 
            $stmt2->execute(); 
            $akum = $stmt2->queryRow();
            
            
            if($plantid != '') {
              $sqlbudget = "call hitungbudgetmonthplant(:vaccountcode,:vdate,:vcompanyid,@vbudgetmonth,@vbudgetlastmonth)";
            }
            else {
              $sqlbudget = "call hitungbudgetmonth(:vaccountcode,:vdate,:vcompanyid,@vbudgetmonth,@vbudgetlastmonth)";
            }
            $command1 = $connection->createCommand($sqlbudget);
            $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command1->execute();
            
            $sqlbud = "select @vbudgetmonth as month, @vbudgetlastmonth as lastmonth"; 
            $stmt1 = Yii::app()->db->createCommand($sqlbud); 
            $stmt1->execute(); 
            $budget = $stmt1->queryRow();
            
            if($plantid != '') {
              $sqlbudakum = "call hitungbudgetakumplant(:vaccountcode,:vdate,:vcompanyid,@vbudgetakum)";
            } 
            else {
              $sqlbudakum = "call hitungbudgetakum(:vaccountcode,:vdate,:vcompanyid,@vbudgetakum)";
            }
            $command2 = $connection->createCommand($sqlbudakum);
            $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
            $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
            $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
            $command2->execute();
            
            $sqlakum = "select @vbudgetakum as akum"; 
            $stmt2 = Yii::app()->db->createCommand($sqlakum); 
            $stmt2->execute(); 
            $budgetakum = $stmt2->queryRow();
                
            if($row2['accformula']=='' || $row2['accformula']=='-'){
               $this->pdf->row(array($row2['accountname'],'','',''));
            }else{
                if(($budget['month']==0) || ($budget['lastmonth']==0) || ($budgetakum['akum']==0))
                {
                    $penc1 = 0;
                    $penc2 = 0;
                    $penc3 = 0;
                }
                if($budget['month']!=0)
                {
                    $penc1 = $pl['month']/$budget['month'];
                }
                if($budget['lastmonth']!=0)
                {
                    $penc2 = $pl['lastmonth']/$budget['lastmonth'];
                }
                if($budgetakum['akum']!=0)
                {
                    $penc3 = $akum['plakum']/$budgetakum['akum'];
                }
                $percent1 = 0; $percent2=0; $percent3=0; $percent4=0; $percent5=0; $percent6=0;
                if($budnettnow>0) {
                    $percent1 = ($budget['month']/$budnettnow)*100;
                }
                if($actnettnow>0) {
                    $percent2 = ($pl['month']/$actnettnow)*100;
                }
                if($budnettlast>0) {
                    $percent3 = ($budget['lastmonth']/$budnettlast)*100;
                }
                if($actnettlast>0) {
                    $percent4 = ($pl['lastmonth']/$actnettlast)*100;
                }
                if($budnettakum>0) {
                    $percent5 = ($budgetakum['akum']/$budnettakum)*100;
                }
                if($actnettakum>0) {
                    $percent6 = ($akum['plakum']/$actnettakum)*100;
                }
                $this->pdf->row(array(
                      $row2['accountname'],
                      Yii::app()->format->formatCurrency($budget['month']/$per),
                      Yii::app()->format->formatCurrency($percent1),
                      Yii::app()->format->formatCurrency(($pl['month'])/$per),
                      Yii::app()->format->formatCurrency($percent2),
                      Yii::app()->format->formatCurrency(($penc1)*100),
                      Yii::app()->format->formatCurrency($budget['lastmonth']/$per),
                      Yii::app()->format->formatCurrency($percent3),
                      Yii::app()->format->formatCurrency(($pl['lastmonth'])/$per),
                      Yii::app()->format->formatCurrency($percent4),
                      Yii::app()->format->formatCurrency(($penc2)*100),
                      Yii::app()->format->formatCurrency($budgetakum['akum']/$per),
                      Yii::app()->format->formatCurrency($percent5),
                      Yii::app()->format->formatCurrency(($akum['plakum'])/$per),
                      Yii::app()->format->formatCurrency($percent6),
                      Yii::app()->format->formatCurrency(($penc3)*100)));   
            }
            
            
            $subbudmonth = $subbudmonth + $budget['month'];
            $subactmonth = $subactmonth + $pl['month'];
            $subbudlast = $subbudlast + $budget['lastmonth'];
            $subactlast = $subactlast + $pl['lastmonth'];
            $subbudakum = $subbudakum + $budgetakum['akum'];
            $subactakum = $subactakum + $akum['plakum'];
            
            }else{
								if($budnettnow == 0){$perbudmonth = 0;}else{$perbudmonth=$subbudmonth/$budnettnow;}
								if($actnettnow == 0){$peractmonth = 0;}else{$peractmonth=$subbudmonth/$actnettnow;}
								if($subbudmonth == 0){$pencmonth = 0;}else{$pencmonth=$subactmonth/$subbudmonth;}
								if($budnettlast == 0){$perbudlast = 0;}else{$perbudlast=$subbudlast/$budnettlast;}
								if($actnettlast == 0){$peractlast = 0;}else{$peractlast=$subactlast/$actnettlast;}
								if($subbudlast == 0){$penclast = 0;}else{$penclast=$subactlast/$subbudlast;}
								if($subbudakum == 0){$pencakum = 0;}else{$pencakum=$subactakum/$subbudakum;}
								
                $this->pdf->row(array(
                                      $row2['accountname'],
                                      Yii::app()->format->formatCurrency($subbudmonth/$per),
                                      Yii::app()->format->formatCurrency(($perbudmonth)*100),
                                      Yii::app()->format->formatCurrency($subactmonth/$per),
                                      Yii::app()->format->formatCurrency(($peractmonth)*100),
                                      Yii::app()->format->formatCurrency(($pencmonth)*100),
                                      Yii::app()->format->formatCurrency($subbudlast/$per),
                                      Yii::app()->format->formatCurrency(($perbudlast)*100),
                                      Yii::app()->format->formatCurrency($subactlast/$per),
                                      Yii::app()->format->formatCurrency(($peractlast)*100),
                                      Yii::app()->format->formatCurrency(($penclast)*100),
                                      Yii::app()->format->formatCurrency($subbudakum/$per),
                                      '',
                                      Yii::app()->format->formatCurrency($subactakum/$per),
                                      '',
                                      Yii::app()->format->formatCurrency(($pencakum)*100)));   
                
                $subbudmonth = 0;
                $subactmonth = 0;
                $subbudlast = 0;
                $subactlast = 0;
                $subbudakum = 0;
                $subactakum = 0;
            }
        $i++;
        }
        //$this->pdf->Rect($this->pdf->getX(),$this->pdf->getY(),5,20,'D');
        //$this->pdf->setY($this->pdf->getY()-5);
        /*$this->pdf->setbordercell(array(
             'LRB',
             'LRB',
             'LRB',
             'LRB',
             'LRB',
             'LRB',
             'LRB',
             'LRB',
             'LRB',
             'LRB',
             'LRB'));
        $this->pdf->row(array('','','','',''));*/
        $this->pdf->checkPageBreak(250);
    }
    
    $this->pdf->Output();
  }
  public function actionDownXlsPL1()	{
      parent::actionDownXls();
      //$this->menuname = 'lampiranlabarugi';
      $companyid = $_GET['company'];
      $plantid = $_GET['plant'];
      $date = $_GET['date'];
      $saldoawal = 0;
      $saldoakhir = 0;
      $per = $_GET['per'];
      $connection = Yii::app()->db;
      $sqldata1 = "select t.*, a.companyname, b.accountname, substr(b.accountname,1,30) as sheettitle ";
      $sqlcount1 = "select count(1) as total ";
      $from = "from att t 
              join company a on t.companyid = a.companyid
              join account b on b.accountid = t.accheaderid
              where isneraca = 0 and t.recordstatus = 1 and t.companyid = ".$companyid." order by t.nourut desc";
      $total = $connection->createCommand($sqlcount1.$from)->queryScalar();
      $res1 = $connection->createCommand($sqldata1.$from)->queryAll();
      
      if($plantid != '') {
          $companyname = $connection->createCommand('select concat("PLANT : ",plantcode) from plant a where a.plantid = '.$plantid)->queryScalar();
          $companyid = $plantid;
      }
      else
      {
          $companyname = $connection->createCommand('select companycode from company a where a.companyid='.$companyid)->queryScalar();
      }
      
      if($plantid != '') {
        $sqlcompanyid = ' a.plantid = '.$plantid;
      }
      else {
        $sqlcompanyid = ' a.companyid = '.$_GET['companyid'];
      }
      $sqlactnettnow = "select sum(debit-credit)*-1
            from genledger a
            join genjournal b on b.genjournalid = a.genjournalid
            where {$sqlcompanyid} and b.journaldate between date_add(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval 1 day), interval -1 month) and last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."') and b.recordstatus=3 
            and a.accountcode between '31' and '3999999999999999999'";
      $actnettnow = Yii::app()->db->createCommand($sqlactnettnow)->queryScalar();

      $sqlactnettlast = "select sum(debit-credit)*-1
            from genledger a
            join genjournal b on b.genjournalid = a.genjournalid
            where {$sqlcompanyid} and b.journaldate between date_add(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval 1 day), interval -2 month) and last_day(date_add(last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."'), interval -1 month)) and b.recordstatus=3 
            and a.accountcode between '31' and '3999999999999999999'";
      $actnettlast = Yii::app()->db->createCommand($sqlactnettlast)->queryScalar();
        
      $sqlactnettakum = "select sum(debit-credit)*-1
                      from genledger a
                      join genjournal b on b.genjournalid = a.genjournalid
                      where {$sqlcompanyid} and b.journaldate between concat(year(last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."')),'-01-01') and last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."') and b.recordstatus=3 
                      and a.accountcode between '31' and '3999999999999999999'";
      $actnettakum = Yii::app()->db->createCommand($sqlactnettakum)->queryScalar();


      $sqlbudnettnow = "select sum(budgetamount)*-1
            from budget a
            -- join genjournal b on b.genjournalid = a.genjournalid
            where {$sqlcompanyid} and a.budgetdate between date_add(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval 1 day), interval -1 month) and last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."') 
            and a.accountcode between '31' and '3999999999999999999'";
      $budnettnow = Yii::app()->db->createCommand($sqlbudnettnow)->queryScalar();

      $sqlbudnettlast = "select sum(budgetamount)*-1
            from budget a
            -- join genjournal b on b.genjournalid = a.genjournalid
            where {$sqlcompanyid} and a.budgetdate between date_add(date_add('".date(Yii::app()->params['datetodb'],strtotime($date))."', interval 1 day), interval -2 month) and last_day(date_add(last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."'), interval -1 month))
            and a.accountcode between '31' and '3999999999999999999'";
      $budnettlast = Yii::app()->db->createCommand($sqlbudnettlast)->queryScalar();
        
      $sqlbudnettakum = "select sum(budgetamount)*-1
                      from budget a
                      -- join genjournal b on b.genjournalid = a.genjournalid
                      where {$sqlcompanyid} and a.budgetdate between concat(year(last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."')),'-01-01') and last_day('".date(Yii::app()->params['datetodb'],strtotime($date))."')
                      and a.accountcode between '31' and '3999999999999999999'";
      $budnettakum = Yii::app()->db->createCommand($sqlbudnettakum)->queryScalar();
      
      $i=0;$j=2;
      $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
      );
      $x = 1;
      foreach($res1 as $row1){
        $myWorkSheet = new PHPExcel_Worksheet($this->phpExcel,$row1['sheettitle']);
        $this->phpExcel->addSheet($myWorkSheet, $i);
        $excel = $this->phpExcel->getSheetByName($row1['sheettitle']);
        $excel->mergeCells('A2:O2');
        $excel->mergeCells('A3:O3');
        $excel->mergeCells('A4:O4');
        $excel->mergeCells('A6:A7');
        $excel->mergeCells('B6:B7');
          
        $excel->mergeCells('C6:G6');
        $excel->mergeCells('H6:L6');
        $excel->mergeCells('M6:Q6');
          
        $excel->getColumnDimension('A')->setWidth(5);
        $excel->getColumnDimension('B')->setWidth(40);
        $excel->getColumnDimension('C')->setWidth(15);
        $excel->getColumnDimension('D')->setWidth(15);
        $excel->getColumnDimension('E')->setWidth(15);
        $excel->getColumnDimension('F')->setWidth(15);
        $excel->getColumnDimension('G')->setWidth(15);
        $excel->getColumnDimension('H')->setWidth(15);
        $excel->getColumnDimension('I')->setWidth(15);
        $excel->getColumnDimension('J')->setWidth(15);
        $excel->getColumnDimension('K')->setWidth(15);
        $excel->getColumnDimension('L')->setWidth(15);
        $excel->getColumnDimension('M')->setWidth(15);
        $excel->getColumnDimension('N')->setWidth(15);
        $excel->getColumnDimension('O')->setWidth(15);
        $excel->getColumnDimension('O')->setWidth(15);
        $excel->getColumnDimension('P')->setWidth(15);
        $excel->getColumnDimension('Q')->setWidth(15);
        $excel->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getStyle('M6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->
            setCellValueByColumnAndRow($i, $j, $row1['companyname'])->
            setCellValueByColumnAndRow($i, $j+1, 'MUTASI '.$row1['accountname'])->
            setCellValueByColumnAndRow($i, $j+2, 'Per : '.date("t F Y", strtotime($_GET['date'])));
          
        $excel
          ->setCellValueByColumnAndRow($i, $j+4, 'NO')
          ->setCellValueByColumnAndRow($i+1, $j+4, 'KETERANGAN')
          ->setCellValueByColumnAndRow($i+2, $j+4, 'BULAN INI')
          ->setCellValueByColumnAndRow($i+7, $j+4, 'BULAN LALU')
          ->setCellValueByColumnAndRow($i+12, $j+4, 'AKUMULATIF s/d BULAN INI');
          
        $excel
          ->setCellValueByColumnAndRow($i+2, $j+5, 'Budget')
          ->setCellValueByColumnAndRow($i+3, $j+5, '%')
          ->setCellValueByColumnAndRow($i+4, $j+5, 'Actual')
          ->setCellValueByColumnAndRow($i+5, $j+5, '%')
          ->setCellValueByColumnAndRow($i+6, $j+5, 'Pencapaian')
          ->setCellValueByColumnAndRow($i+7, $j+5, 'Budget')
          ->setCellValueByColumnAndRow($i+8, $j+5, '%')
          ->setCellValueByColumnAndRow($i+9, $j+5, 'Actual')
          ->setCellValueByColumnAndRow($i+10, $j+5, '%')
          ->setCellValueByColumnAndRow($i+11, $j+5, 'Pencapaian')
          ->setCellValueByColumnAndRow($i+12, $j+5, 'Budget')
          ->setCellValueByColumnAndRow($i+13, $j+5, '%')
          ->setCellValueByColumnAndRow($i+14, $j+5, 'Actual')
          ->setCellValueByColumnAndRow($i+15, $j+5, '%')
          ->setCellValueByColumnAndRow($i+16, $j+5, 'Pencapaian');
              
          // get all data
        $sql2 = "select a.* from attdet a where a.isview = 1 and a.attid = ".$row1['attid']." order by a.nourut asc";
        $res2 = $connection->createCommand($sql2)->queryAll();
        
        $subbudmonth = 0;
        $subactmonth = 0;
        $subbudlast = 0;
        $subactlast = 0;
        $subbudakum = 0;
        $subactakum = 0;
        $k = 8;
        $l=1;
        foreach($res2 as $row2){
            if($row2['istotal']!=1){
                if($plantid != '') {
                    $sqlsaldoawal = "call hitungplmonthplant(:vaccountcode,:vdate,:vcompanyid,@vplmonth,@vpllastmonth)";
                }
                else {
                    $sqlsaldoawal = "call hitungplmonth(:vaccountcode,:vdate,:vcompanyid,@vplmonth,@vpllastmonth)";
                }
                $command1 = $connection->createCommand($sqlsaldoawal);
                $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command1->execute();

                $sqlpl = "select @vplmonth as month, @vpllastmonth as lastmonth"; 
                $stmt1 = Yii::app()->db->createCommand($sqlpl); 
                $stmt1->execute(); 
                $pl = $stmt1->queryRow();

                if($plantid != '') {
                    $sqldebitcredit = "call hitungplakumplant(:vaccountcode,:vdate,:vcompanyid,@vplakum)";
                }
                else {
                    $sqldebitcredit = "call hitungplakum(:vaccountcode,:vdate,:vcompanyid,@vplakum)";  
                }
                $command2 = $connection->createCommand($sqldebitcredit);
                $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command2->execute();

                $sqlakum = "select @vplakum as plakum"; 
                $stmt2 = Yii::app()->db->createCommand($sqlakum); 
                $stmt2->execute(); 
                $akum = $stmt2->queryRow();


                if($plantid != '') {
                    $sqlbudget = "call hitungbudgetmonthplant(:vaccountcode,:vdate,:vcompanyid,@vbudgetmonth,@vbudgetlastmonth)";  
                }
                else {
                    $sqlbudget = "call hitungbudgetmonth(:vaccountcode,:vdate,:vcompanyid,@vbudgetmonth,@vbudgetlastmonth)";
                }
                $command1 = $connection->createCommand($sqlbudget);
                $command1->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command1->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command1->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command1->execute();

                $sqlbud = "select @vbudgetmonth as month, @vbudgetlastmonth as lastmonth"; 
                $stmt1 = Yii::app()->db->createCommand($sqlbud); 
                $stmt1->execute(); 
                $budget = $stmt1->queryRow();

                if($plantid != '') {
                    $sqlbudakum = "call hitungbudgetakumplant(:vaccountcode,:vdate,:vcompanyid,@vbudgetakum)";
                }
                else { 
                    $sqlbudakum = "call hitungbudgetakum(:vaccountcode,:vdate,:vcompanyid,@vbudgetakum)";
                }
                
                $command2 = $connection->createCommand($sqlbudakum);
                $command2->bindvalue(':vaccountcode', $row2['accformula'], PDO::PARAM_STR);
                $command2->bindvalue(':vdate',date(Yii::app()->params['datetodb'],strtotime($date)), PDO::PARAM_STR);
                $command2->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
                $command2->execute();

                $sqlakum = "select @vbudgetakum as akum"; 
                $stmt2 = Yii::app()->db->createCommand($sqlakum); 
                $stmt2->execute(); 
                $budgetakum = $stmt2->queryRow();
							
                $excel
                ->setCellValueByColumnAndRow($i+0, $k, $l)
                ->setCellValueByColumnAndRow($i+1, $k, $row2['accountname']);

							if($row2['accformula']=='' || $row2['accformula']=='-'){
									
							}else{
                                if(($budget['month']==0) || ($budget['lastmonth']==0) || ($budgetakum['akum']==0))
                                {
                                    $penc1 = 0;
                                    $penc2 = 0;
                                    $penc3 = 0;
                                }
                                if($budget['month']!=0)
                                {
                                    $penc1 = $pl['month']/$budget['month'];
                                }
                                if($budget['lastmonth']!=0)
                                {
                                    $penc2 = $pl['lastmonth']/$budget['lastmonth'];
                                }
                                if($budgetakum['akum']!=0)
                                {
                                    $penc3 = $akum['plakum']/$budgetakum['akum'];
                                }
                                
                                $percent1 = 0; $percent2=0; $percent3=0; $percent4=0; $percent5=0; $percent6=0;
                                if($budnettnow>0) {
                                    $percent1 = ($budget['month']/$budnettnow)*100;
                                }
                                if($actnettnow>0) {
                                    $percent2 = ($pl['month']/$actnettnow)*100;
                                }
                                if($budnettlast>0) {
                                    $percent3 = ($budget['lastmonth']/$budnettlast)*100;
                                }
                                if($actnettlast>0) {
                                    $percent4 = ($pl['lastmonth']/$actnettlast)*100;
                                }
                                if($budnettakum>0) {
                                    $percent5 = ($budgetakum['akum']/$budnettakum)*100;
                                }
                                if($actnettakum>0) {
                                    $percent6 = ($akum['plakum']/$actnettakum)*100;
                                }
                                
                                $excel
                                ->setCellValueByColumnAndRow($i+2, $k, $budget['month']/$per)
                                ->setCellValueByColumnAndRow($i+3, $k, $percent1)
                                ->setCellValueByColumnAndRow($i+4, $k, $pl['month']/$per)
                                ->setCellValueByColumnAndRow($i+5, $k, $percent2)
                                ->setCellValueByColumnAndRow($i+6, $k, $penc1*100)
                                ->setCellValueByColumnAndRow($i+7, $k, $budget['lastmonth']/$per)
                                ->setCellValueByColumnAndRow($i+8, $k, $percent3)
                                ->setCellValueByColumnAndRow($i+9, $k, $pl['lastmonth']/$per)
                                ->setCellValueByColumnAndRow($i+10, $k, $percent4)
                                ->setCellValueByColumnAndRow($i+11, $k, $penc2*100)
                                ->setCellValueByColumnAndRow($i+12, $k, $budgetakum['akum']/$per)
                                ->setCellValueByColumnAndRow($i+13, $k, $percent5)
                                ->setCellValueByColumnAndRow($i+14, $k, $akum['plakum']/$per)
                                ->setCellValueByColumnAndRow($i+15, $k, $percent6)
                                ->setCellValueByColumnAndRow($i+16, $k, $penc3*100);
							}

							$subbudmonth = $subbudmonth + $budget['month'];
                            $subactmonth = $subactmonth + $pl['month'];
                            $subbudlast = $subbudlast + $budget['lastmonth'];
                            $subactlast = $subactlast + $pl['lastmonth'];
                            $subbudakum = $subbudakum + $budgetakum['akum'];
                            $subactakum = $subactakum + $akum['plakum'];
					}else{
								if($budnettnow == 0){$perbudmonth = 0;}else{$perbudmonth=$subbudmonth/$budnettnow;}
								if($actnettnow == 0){$peractmonth = 0;}else{$peractmonth=$subbudmonth/$actnettnow;}
								if($subbudmonth == 0){$pencmonth = 0;}else{$pencmonth=$subactmonth/$subbudmonth;}
								if($budnettlast == 0){$perbudlast = 0;}else{$perbudlast=$subbudlast/$budnettlast;}
								if($actnettlast == 0){$peractlast = 0;}else{$peractlast=$subactlast/$actnettlast;}
								if($subbudlast == 0){$penclast = 0;}else{$penclast=$subactlast/$subbudlast;}
								if($subbudakum == 0){$pencakum = 0;}else{$pencakum=$subactakum/$subbudakum;}
								
							$excel
                                ->setCellValueByColumnAndRow($i+2, $k, $subbudmonth/$per)
                                ->setCellValueByColumnAndRow($i+3, $k, ($perbudmonth)*100)
                                ->setCellValueByColumnAndRow($i+4, $k, $subactmonth/$per)
                                ->setCellValueByColumnAndRow($i+5, $k, ($peractmonth)*100)
                                ->setCellValueByColumnAndRow($i+6, $k, ($pencmonth)*100)
                                ->setCellValueByColumnAndRow($i+7, $k, $subbudlast/$per)
                                ->setCellValueByColumnAndRow($i+8, $k, ($perbudlast)*100)
                                ->setCellValueByColumnAndRow($i+9, $k, $subactlast/$per)
                                ->setCellValueByColumnAndRow($i+10, $k, ($peractlast)*100)
                                ->setCellValueByColumnAndRow($i+11, $k, ($penclast)*100)
                                ->setCellValueByColumnAndRow($i+12, $k, $subbudakum/$per)
                                ->setCellValueByColumnAndRow($i+13, $k, $subactakum/$per)
                                ->setCellValueByColumnAndRow($i+14, $k, ($pencakum)*100);
							
							
                            $subbudmonth = 0;
                            $subactmonth = 0;
                            $subbudlast = 0;
                            $subactlast = 0;
                            $subbudakum = 0;
                            $subactakum = 0;
					}
					$k++;
					$l++;
        }
          $x++;
       
    }
    $this->getFooterXLS($this->phpExcel);  
  }
}
