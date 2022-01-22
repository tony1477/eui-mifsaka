<?php
class RepcashbankController extends Controller
{
  public $menuname = 'cashbank';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexpay()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchpay();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $cashbankid      = isset($_POST['cashbankid']) ? $_POST['cashbankid'] : '';
    $cashbankno      = isset($_POST['cashbankno']) ? $_POST['cashbankno'] : '';
    $receiptno       = isset($_POST['receiptno']) ? $_POST['receiptno'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $cashbankid      = isset($_GET['q']) ? $_GET['q'] : $cashbankid;
    $cashbankno      = isset($_GET['q']) ? $_GET['q'] : $cashbankno;
    $receiptno       = isset($_GET['q']) ? $_GET['q'] : $receiptno;
    $docdate         = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cashbankid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbank t')->join('company a', 'a.companyid=t.companyid')->where("(t.cashbankno like :cashbankno) or
						(t.docdate like :docdate) or
						(t.receiptno like :receiptno)", array(
      ':cashbankno' => '%' . $cashbankno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':receiptno' => '%' . $receiptno . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyname')->from('cashbank t')->join('company a', 'a.companyid=t.companyid')->where("(t.cashbankno like :cashbankno) or
						(t.docdate like :docdate) or
						(t.receiptno like :receiptno)", array(
      ':cashbankno' => '%' . $cashbankno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':receiptno' => '%' . $receiptno . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankid' => $data['cashbankid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'cashbankno' => $data['cashbankno'],
        'receiptno' => $data['receiptno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'isin' => $data['isin'],
        'headernote' => $data['headernote'],
        'recordstatus' => findstatusname("appcb", $data['recordstatus'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchpay()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cashbankacc t')->join('account a', 'a.accountid=t.accountid')->join('currency b', 'b.currencyid=t.currencyid')->where('cashbankid = :cashbankid', array(
      ':cashbankid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountname,b.currencyname')->from('cashbankacc t')->join('account a', 'a.accountid=t.accountid')->join('currency b', 'b.currencyid=t.currencyid')->where('cashbankid = :cashbankid', array(
      ':cashbankid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cashbankaccid' => $data['cashbankaccid'],
        'cashbankid' => $data['cashbankid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'cheqno' => $data['cheqno'],
        'tglterima' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglterima'])),
        'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
        'debit' => Yii::app()->format->formatNumber($data['debit']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'bankaccountno' => $data['bankaccountno'],
        'bankname' => $data['bankname'],
        'bankowner' => $data['bankowner'],
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select *,a.cashbankno,a.docdate,a.receiptno
			from cashbank a
			join company b on b.companyid = a.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cashbankid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('cashbank');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No. Transaksi ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['cashbankno']);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'No Kwitansi ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . $row['receiptno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tanggal ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $sql1        = "select b.accountname,a.cheqno,a.tglterima,a.tglcair, a.debit,a.credit,d.currencyname ,a.description
					from cashbankacc a
					join account b on b.accountid = a.accountid
					join cashbank c on c.cashbankid = a.cashbankid
					left join currency d on d.currencyid = a.currencyid
					where c.cashbankid = " . $row['cashbankid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
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
      $this->pdf->setwidths(array(
        7,
        40,
        20,
        17,
        17,
        22,
        22,
        15,
        45
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun',
        'No Cek',
        'Diterima',
        'Pencairan',
        'Debet',
        'Kredit',
        'Mata Uang',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'C',
        'C',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      $debit                     = 0;
      $credit                    = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['accountname'],
          $row1['cheqno'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['tglterima'])),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['tglcair'])),
          Yii::app()->format->formatCurrency($row1['debit']),
          Yii::app()->format->formatCurrency($row1['credit']),
          $row1['currencyname'],
          $row1['description']
        ));
        $debit += $row1['debit'];
        $credit += $row1['credit'];
      }
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'JUMLAH',
        Yii::app()->format->formatCurrency($debit),
        Yii::app()->format->formatCurrency($credit),
        $row1['currencyname'],
        ''
      ));
      $this->pdf->sety($this->pdf->gety() + 0);
      $this->pdf->setFont('Arial', 'BI', 7);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        16,
        170
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'NOTE :',
        $row['headernote']
      ));
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Disetujui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '  Admin Kasir');
      $this->pdf->text(55, $this->pdf->gety() + 25, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'Chief Accounting');
      $this->pdf->checkNewPage(25);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select receiptno,docdate,recordstatus
				from cashbank a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cashbankid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('receiptno'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['receiptno'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="cashbank.xlsx"');
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