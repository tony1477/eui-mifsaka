<?php
class RepcbinController extends Controller
{
  public $menuname = 'repcbin';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexjournal()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchjournal();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $cbinid          = isset($_POST['cbinid']) ? $_POST['cbinid'] : '';
    $cbinno          = isset($_POST['cbinno']) ? $_POST['cbinno'] : '';
    $ttntid          = isset($_POST['ttntid']) ? $_POST['ttntid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $companyid         = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cbinid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbin t')
		->leftjoin('ttnt a', 'a.ttntid=t.ttntid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.cbinno,'') like :cbinno) and
			(coalesce(t.docdate,'') like :docdate) and
			(coalesce(b.companyname,'') like :companyid) and
			(coalesce(t.cbinid,'') like :cbinid) and
			(coalesce(a.docno,'') like :ttntid) 
			and t.companyid in (".getUserObjectValues('company').")", array(
      ':cbinno' => '%' . $cbinno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cbinid' => '%' . $cbinid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.docno,b.companyname')->from('cbin t')
		->leftjoin('ttnt a', 'a.ttntid=t.ttntid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.cbinno,'') like :cbinno) and
			(coalesce(t.docdate,'') like :docdate) and
			(coalesce(b.companyname,'') like :companyid) and
			(coalesce(t.cbinid,'') like :cbinid) and
			(coalesce(a.docno,'') like :ttntid) and t.companyid in (".getUserObjectValues('company').")", array(
      ':cbinno' => '%' . $cbinno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cbinid' => '%' . $cbinid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbinid' => $data['cbinid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'cbinno' => $data['cbinno'],
        'ttntid' => $data['ttntid'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'recordstatus' => $data['recordstatus'],
        'recordstatuscbin' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchjournal()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbinjournal t')->leftjoin('currency a', 'a.currencyid=t.currencyid')->leftjoin('account b', 'b.accountid=t.accountid')->leftjoin('cheque c', 'c.chequeid=t.chequeid')->where('cbinid = :cbinid', array(
      ':cbinid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.currencyname,b.accountname,c.chequeno')->from('cbinjournal t')->leftjoin('currency a', 'a.currencyid=t.currencyid')->leftjoin('account b', 'b.accountid=t.accountid')->leftjoin('cheque c', 'c.chequeid=t.chequeid')->where('cbinid = :cbinid', array(
      ':cbinid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbinjournalid' => $data['cbinjournalid'],
        'cbinid' => $data['cbinid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debit' => Yii::app()->format->formatCurrency($data['debit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatCurrency($data['currencyrate']),
        'chequeid' => $data['chequeid'],
        'chequeno' => $data['chequeno'],
        'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $cmd             = Yii::app()->db->createCommand()->select('sum(t.debit) as debit')->from('cbinjournal t')->leftjoin('currency a', 'a.currencyid=t.currencyid')->leftjoin('account b', 'b.accountid=t.accountid')->where('cbinid = :cbinid', array(
      ':cbinid' => $id
    ))->queryScalar();
		$footer[] = array(
      'accountname' => 'Total',
      'debit' => Yii::app()->format->formatCurrency($cmd),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    parent::actionDownload();
    $sql = "select distinct a.cbinid,a.cbinno,a.docdate as cbindate,c.docno as ttntno,c.docdate as ttntdate,b.companyid,concat('Pelunasan Piutang ',c.docno) as uraian
                        from cbin a
                        left join company b on b.companyid = a.companyid
                        left join ttnt c on c.ttntid = a.ttntid
                        left join cbinjournal d on d.cbinid = a.cbinid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cbinid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('cbin');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(7);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['cbinno']);
      $this->pdf->text(60, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(70, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['cbindate'])));
      $this->pdf->text(100, $this->pdf->gety(), 'TTNT ');
      $this->pdf->text(130, $this->pdf->gety(), ': ' . $row['ttntno']);
      $this->pdf->text(160, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(180, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['ttntdate'])));
      $sql1        = "select b.accountname,a.description,d.chequeno,a.tglcair,a.debit,c.currencyname,a.currencyrate
                            from cbinjournal a
                            left join account b on b.accountid=a.accountid
                            left join currency c on c.currencyid=a.currencyid
														left join cheque d on d.chequeid = a.chequeid
                            where a.cbinid = " . $row['cbinid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->colalign = array(
        'C',
        'L',
        'L',
        'L',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        40,
        65,
        20,
        20,
        25,
        25
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun',
        'Keterangan',
        'No. Cek/Giro',
        'Tgl. Cair',
        'Debit',
        'Kredit'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'L',
        'L',
        'C',
        'R',
        'R'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['accountname'],
          $row1['description'],
          $row1['chequeno'],
          $row1['tglcair'],
          Yii::app()->format->formatCurrency($row1['debit']),
          '0.00'
        ));
        $total = $row1['debit'] + $total;
      }
      $i = $i + 1;
      $this->pdf->row(array(
        $i,
        'KAS PENAMPUNG PIUTANG',
        $row['uraian'],
        '',
        '',
        '0.00',
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->setbordercell(array(
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB'
      ));
      $this->pdf->row(array(
        '',
        '',
        'Jumlah',
        '',
        '',
        Yii::app()->format->formatCurrency($total),
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 18, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 18, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 18, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 20, '  Admin Kasir');
      $this->pdf->text(55, $this->pdf->gety() + 20, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 20, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select ttntid,docdate,recordstatus
				from cbin a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cbinid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('ttntid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['ttntid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="cbin.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    unset($excel);
  }
}