<?php
class RepoperatoroutputController extends Controller
{
  public $menuname = 'repoperatoroutput';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchdet();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexissue()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchissue();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $operatoroutputid = isset($_POST['operatoroutputid']) ? $_POST['operatoroutputid'] : '';
    $slocid           = isset($_POST['sloc']) ? $_POST['sloc'] : '';
    //$slocid           = isset($_POST['']) ? $_POST['sloc'] : '';
    $opoutputdate     = isset($_POST['opoutputdate']) ? $_POST['opoutputdate'] : '';
    $companyid        = isset($_POST['company']) ? $_POST['company'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $fullname         = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $operatoroutputid = isset($_GET['q']) ? $_GET['q'] : $operatoroutputid;
    $slocid           = isset($_GET['q']) ? $_GET['q'] : $slocid;
    $opoutputdate     = isset($_GET['q']) ? $_GET['q'] : $opoutputdate;
    $companyid        = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $headernote       = isset($_GET['q']) ? $_GET['q'] : $headernote;
    //$fullname       = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'operatoroutputid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
    /*
    if(isset($_POST['opoutputdate'])){
        $day = date('d',strtotime($_POST['opoutputdate']));
        $month = date('m',strtotime($_POST['opoutputdate']));
        $year = date('Y',strtotime($_POST['opoutputdate']));
        $date = $year.'-'.$month.'-'.$day;
    }else{
        $date = $opoutputdate;   
    }
    */
    if(isset($_GET['combo']))
    {
        $cmd = Yii::app()->db->createCommand()->select('count(1) as total')
                ->from('operatoroutput t')
                ->leftjoin('company a', 'a.companyid = t.companyid')
                ->leftjoin('sloc b', 'b.slocid = t.slocid')
                ->leftjoin('shiftsched c', 'c.shiftschedid = t.shiftschedid')
                ->where("((t.opoutputdate like :opoutputdate) or
						(b.sloccode like :sloccode) or
						(a.companyname like :companyid) or
						(t.operatoroutputid like :operatoroutputid) or
						(t.headernote like :headernote))
                        and t.companyid in (".getUserObjectValues('company').")", array(
                  ':sloccode' => '%' . $slocid . '%',
                  ':opoutputdate' => '%' . $opoutputdate . '%',
                  ':operatoroutputid' => '%' . $operatoroutputid . '%',
                  ':companyid' => '%' . $companyid . '%',
                  ':headernote' => '%' . $headernote . '%',
                ))->queryScalar();
    }
    else
    {
        $cmd = Yii::app()->db->createCommand()->selectdistinct('count(1) as total')
        ->from('operatoroutput t')
        ->join('company a', 'a.companyid = t.companyid')
        ->join('operatoroutputdet c', 'c.operatoroutputid = t.operatoroutputid')
        ->join('employee d','d.employeeid = c.employeeid')
        ->join('sloc b', 'b.slocid = t.slocid')
        ->where("(
				 (coalesce(b.sloccode,'') like :sloccode) and
				 (coalesce(a.companyname,'') like :companyid) and
				 (coalesce(t.operatoroutputid,'') like :operatoroutputid) and
				 (coalesce(t.headernote,'') like :headernote) and 
				 (coalesce(d.fullname,'') like '%".$fullname."%'))  and
				t.companyid in (".getUserObjectValues('company').")", array(
              ':sloccode' => '%' . $slocid . '%',
              ':operatoroutputid' => '%' . $operatoroutputid . '%',
              ':companyid' => '%' . $companyid . '%',
              ':headernote' => '%' . $headernote . '%',
        ))->queryScalar();
    }
    $result['total'] = $cmd;
    if(isset($_GET['combo']))
    {
        $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname, b.sloccode, concat(b.sloccode,"-",b.description, c.shiftcode, c.shiftname) as slocdesc, getwfstatusbywfname("appopoutput",t.recordstatus) as statusname')
            ->from('operatoroutput t')
            ->leftjoin('company a', 'a.companyid=t.companyid')
            ->leftjoin('sloc b', 'b.slocid=t.slocid')
            ->leftjoin('shiftsched c', 'c.shiftschedid = t.shiftschedid')
             ->where("((t.opoutputdate like :opoutputdate) or
						(b.sloccode like :sloccode) or
						(a.companyname like :companyid) or
						(t.operatoroutputid like :operatoroutputid) or
						(t.headernote like :headernote))
                        and t.companyid in (".getUserObjectValues('company').")", array(
                  ':sloccode' => '%' . $slocid . '%',
                  ':opoutputdate' => '%' . $opoutputdate . '%',
                  ':operatoroutputid' => '%' . $operatoroutputid . '%',
                  ':companyid' => '%' . $companyid . '%',
                  ':headernote' => '%' . $headernote . '%',
        ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    else
    {
        $cmd = Yii::app()->db->createCommand()->selectdistinct('t.*,a.companyname, b.sloccode, concat(b.sloccode,"-",b.description) as slocdesc, getwfstatusbywfname("appopoutput",t.recordstatus) as statusname, e.shiftname, e.shiftcode')
            ->from('operatoroutput t')
            ->leftjoin('company a', 'a.companyid=t.companyid')
            ->join('operatoroutputdet c', 'c.operatoroutputid = t.operatoroutputid')
            ->join('employee d','d.employeeid = c.employeeid')
            ->join('shiftsched e','e.shiftschedid = t.shiftschedid')
            ->leftjoin('sloc b', 'b.slocid=t.slocid')
            ->where("(      (coalesce(b.sloccode,'') like :sloccode) and
                            (coalesce(a.companyname,'') like :companyid) and
                            (coalesce(t.operatoroutputid,'') like :operatoroutputid) and
                            (coalesce(t.headernote,'') like :headernote) and
                            (coalesce(d.fullname,'') like '%".$fullname."%')) and
                            t.companyid in (".getUserObjectValues('company').")", array(
          ':sloccode' => '%' . $slocid . '%',
          ':operatoroutputid' => '%' . $operatoroutputid . '%',
          ':companyid' => '%' . $companyid . '%',
          ':headernote' => '%' . $headernote . '%',
        ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'operatoroutputid' => $data['operatoroutputid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'slocdesc' => $data['slocdesc'],
        'opoutputdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['opoutputdate'])),
        'headernote' => $data['headernote'],
        'isover' => $data['isover'],
        'ctover' => $data['ctover'],
        'oldctover' => $data['ctover'],
        'shiftschedid' => $data['shiftschedid'],
        'shiftcode' => $data['shiftcode'],
        'shiftname' => $data['shiftname'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdet()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'operatoroutputdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
            ->from('operatoroutputdet t')
            ->leftjoin('employee a', 'a.employeeid=t.employeeid')
            ->leftjoin('standardopoutput b', 'b.standardopoutputid=t.standardopoutputid')
            ->where('operatoroutputid = :operatoroutputid', array(
      ':operatoroutputid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*, a.fullname, b.groupname')
        ->from('operatoroutputdet t')
        ->leftjoin('employee a', 'a.employeeid=t.employeeid')
        ->leftjoin('standardopoutput b', 'b.standardopoutputid=t.standardopoutputid')
        ->where('operatoroutputid = :operatoroutputid', array(
      ':operatoroutputid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ')->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'operatoroutputdetid' => $data['operatoroutputdetid'],
        'operatoroutputid' => $data['operatoroutputid'],
        'groupname' => $data['groupname'],
        'employeeid' => $data['employeeid'],
        'fullname' => $data['fullname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    
    $cmd = Yii::app()->db->createCommand()->select('
		sum(t.qty) as qty')->from('operatoroutputdet t')->join('employee a', 'a.employeeid=t.employeeid')
        ->join('standardopoutput b', 'b.standardopoutputid=t.standardopoutputid')
        ->where('operatoroutputid = :operatoroutputid', array(
      ':operatoroutputid' => $id
    ))->queryRow();
    $footer[] = array(
      'fullname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($cmd['qty'])
    );
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchissue()
  {
    header("Content-Type: application/json");
    $id = 0;
    $operatoroutputdetid = '';
    if (isset($_POST['operatoroutputdetid'])) {
      $operatoroutputdetid = $_POST['operatoroutputdetid'];
    }
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } else if (isset($_POST['id'])) {
      $id = $_POST['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'operatoroutputissueid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
            ->from('operatoroutputissue t')
            ->where('operatoroutputid = :operatoroutputid and operatoroutputdetid like :operatoroutputdetid', array(
      ':operatoroutputid' => $id,
      ':operatoroutputdetid' => $operatoroutputdetid
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*, a.issuename')
        ->from('operatoroutputissue t')
        ->leftjoin('standardissue a','a.standardissueid = t.standardissueid')
       ->where('operatoroutputid = :operatoroutputid and operatoroutputdetid like :operatoroutputdetid', array(
      ':operatoroutputid' => $id,
      ':operatoroutputdetid' => $operatoroutputdetid
    ))->offset($offset)->limit($rows)->order($sort . ' ')->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'operatoroutputissueid' => $data['operatoroutputissueid'],
        'operatoroutputdetid' => $data['operatoroutputdetid'],
        'operatoroutputid' => $data['operatoroutputid'],
        'cycletime' => Yii::app()->format->formatNumber($data['cycletime']),
        'description' => $data['description'],
        'issuename' => $data['issuename']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    /*
    $cmd = Yii::app()->db->createCommand()->select('
		sum(t.qty) as qty')->from('operatoroutputdet t')->join('employee a', 'a.employeeid=t.employeeid')
        ->join('standardopoutput b', 'b.standardopoutputid=t.standardopoutputid')
        ->where('operatoroutputid = :operatoroutputid', array(
      ':operatoroutputid' => $id
    ))->queryRow();
    $footer[] = array(
      'fullname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($cmd['qty'])
    );
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    */
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select d.companyname, c.sloccode, a.*
			from operatoroutput a
            join sloc c on c.slocid = a.slocid
            join company d on d.companyid = a.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.operatoroutputid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('operatoroutput');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'Perusahaan ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['companyname']);
      $this->pdf->text(110, $this->pdf->gety() + 2, 'Kode Gudang ');
      $this->pdf->text(130, $this->pdf->gety() + 2, ': ' . $row['sloccode']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tanggal ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['opoutputdate'])));
      $sql1        = "select b.fullname, a.*, c.groupname
					from operatoroutputdet a
					join employee b on b.employeeid = a.employeeid
					join standardopoutput c on c.standardopoutputid = a.standardopoutputid
					where a.operatoroutputid = " . $row['operatoroutputid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
        'C',
        'L',
        'L',
        'R',
        'R',
        'L'
      );
      $this->pdf->setwidths(array(
        7,
        40,
        30,
        30,
        25,
        60
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Operator',
        'Nama Group',
        'Qty',
        'Cycle Time',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'R',
        'R',
        'L'  
          
      );
      $i                         = 0;
      $qty                       = 0;
      $totalqty=0;
      $totalct=0;
      foreach ($dataReader1 as $row1) {
        $sqldataissue = "select b.issuename, a.description, a.cycletime ";
        $sqlcountissue = "select ifnull(count(1),0) ";
        $from = "from operatoroutputissue a
            left join standardissue b on b.standardissueid = a.standardissueid
            where a.operatoroutputdetid = ".$row1['operatoroutputdetid'];
        $qissue = Yii::app()->db->createCommand($sqldataissue.$from)->queryAll();
        $num_rows = Yii::app()->db->createCommand($sqlcountissue.$from)->queryScalar();
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['fullname'],
          $row1['groupname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          Yii::app()->format->formatCurrency($row1['cycletime']*$row1['qty']),
          $row1['description']
        ));
        if($num_rows>0)
        {
            foreach($qissue as $row2)
            {
                $this->pdf->row(array(
                '',
                'ISSUE :',
                $row2['issuename'],
                //$row2['description'],
                '',
                Yii::app()->format->formatCurrency($row2['cycletime']),
                $row2['description']
                ));
                $totalct += $row2['cycletime'];
            }
        }
        $totalqty += $row1['qty'];
        $totalct += ($row1['cycletime']*$row1['qty']);
      }
      $this->pdf->setFont('Arial','B',8);
      $this->pdf->row(array(
      '',
      'JUMLAH',
      '',
      Yii::app()->format->formatCurrency($totalqty),
      Yii::app()->format->formatCurrency($totalct),
      ));
          
      $this->pdf->setY($this->pdf->getY()+5);
      $this->pdf->setFont('Arial','B',9);
      $this->pdf->row(array(
        '',
        'Note : '. $row['headernote'],
      ));
      
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        '',
        'JUMLAH',
        '',
        Yii::app()->format->formatCurrency($totalqty),
      ));
      /*
      $bilangan = explode(".", $amount);
      $this->pdf->sety($this->pdf->gety() + 0);
      $this->pdf->setFont('Arial', 'I', 8);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        7,
        200
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        '',
        'Terbilang : ' . $this->eja($bilangan[0]) . ' ' . $row1['currencyname']
      ));
      $this->pdf->setFont('Arial', 'BI', 8);
      $this->pdf->row(array(
        '',
        'NOTE : ' . $row['headernote']
      ));
      */
        
      $this->pdf->checkNewPage(30);
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Disetujui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '  Admin Produksi');
      $this->pdf->text(55, $this->pdf->gety() + 25, '  Foreman');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'SPV Produksi');
      $this->pdf->setY($this->pdf->getY()+25);
      $this->pdf->checkNewPage(50);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname='realisasihasiloperator';
    parent::actionDownxls();
    $sql = "select d.companyname, c.sloccode, a.*
      from operatoroutput a
            join sloc c on c.slocid = a.slocid
            join company d on d.companyid = a.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.operatoroutputid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(1,3,$row['companyname']) 
              ->setCellValueByColumnAndRow(3,3,$row['sloccode'])     
              ->setCellValueByColumnAndRow(1,4,date(Yii::app()->params['dateviewfromdb'], strtotime($row['opoutputdate'])));
                
  }
  $line=8;
  $sql1        = "select b.fullname, a.*, c.groupname
          from operatoroutputdet a
          join employee b on b.employeeid = a.employeeid
          join standardopoutput c on c.standardopoutputid = a.standardopoutputid
          where a.operatoroutputid = " . $row['operatoroutputid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i = 0;
      $qty= 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(0,$line,$i)
              ->setCellValueByColumnAndRow(1,$line,$row1['fullname']) 
              ->setCellValueByColumnAndRow(2,$line,$row1['groupname'])
              ->setCellValueByColumnAndRow(3,$line,$row1['qty']);
        $qty += $row1['qty'];
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(1,$line,'Jumlah')
              ->setCellValueByColumnAndRow(3,$line,$qty);
              $line = $line+3;
      $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(1,$line,'Dibuat Oleh,')
              ->setCellValueByColumnAndRow(2,$line,'Diperiksa Oleh,')
              ->setCellValueByColumnAndRow(3,$line,'Dsetujui Oleh,');
              $line=$line+3;
      $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(1,$line,'........................')
              ->setCellValueByColumnAndRow(2,$line,'........................')
              ->setCellValueByColumnAndRow(3,$line,'........................');
              $line=$line+1;
      $this->phpExcel->setActiveSheetIndex(0)
              ->setCellValueByColumnAndRow(1,$line,'Admin Produksi')
              ->setCellValueByColumnAndRow(2,$line,'Foreman')
              ->setCellValueByColumnAndRow(3,$line,'SPV Produksi');

  $this->getFooterXLS($this->phpExcel);
}
}
