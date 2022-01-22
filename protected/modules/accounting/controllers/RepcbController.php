<?php
class RepcbController extends Controller
{
  public $menuname = 'repcb';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexacc()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchacc();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $cbid            = isset($_POST['cbid']) ? $_POST['cbid'] : '';
    $cashbankno      = isset($_POST['cashbankno']) ? $_POST['cashbankno'] : '';
    $receiptno       = isset($_POST['receiptno']) ? $_POST['receiptno'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $headernote         = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $companyid         = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cbid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cb t')
		->join('company a', 'a.companyid=t.companyid')
		->where("((coalesce(t.cashbankno,'') like :cashbankno) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.headernote,'') like :headernote) and
						(coalesce(a.companyname,'') like :companyid) and
						(coalesce(t.cbid,'') like :cbid) and
						(coalesce(t.receiptno,'') like :receiptno)) and t.companyid in (".getUserObjectValues('company').")", array(
      ':cashbankno' => '%' . $cashbankno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':cbid' => '%' . $cbid . '%',
      ':headernote' => '%' . $headernote . '%',
      ':companyid' => '%' . $companyid . '%',
      ':receiptno' => '%' . $receiptno . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyname')->from('cb t')
		->leftjoin('company a', 'a.companyid=t.companyid')
		->where("((coalesce(t.cashbankno,'') like :cashbankno) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.headernote,'') like :headernote) and
						(coalesce(a.companyname,'') like :companyid) and
						(coalesce(t.cbid,'') like :cbid) and
						(coalesce(t.receiptno,'') like :receiptno)) and t.companyid in (".getUserObjectValues('company').")", array(
      ':cashbankno' => '%' . $cashbankno . '%',
      ':docdate' => '%' . $docdate . '%',
      ':headernote' => '%' . $headernote . '%',
      ':cbid' => '%' . $cbid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':receiptno' => '%' . $receiptno . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbid' => $data['cbid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'cashbankno' => $data['cashbankno'],
        'receiptno' => $data['receiptno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'isin' => $data['isin'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchacc()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cbacc t')->leftjoin('account a', 'a.accountid=t.debitaccid')->leftjoin('account b', 'b.accountid=t.creditaccid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->leftjoin('cheque d', 'd.chequeid=t.chequeid')->leftjoin('employee e', 'e.employeeid=t.employeeid')->leftjoin('addressbook f', 'f.addressbookid=t.customerid')->leftjoin('addressbook g', 'g.addressbookid=t.supplierid')->leftjoin('plant h', 'h.plantid=t.debplantid')->leftjoin('plant i', 'i.plantid=t.credplantid')->where('cbid = :cbid', array(
      ':cbid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,d.chequeid,d.chequeno,a.accountid as debitaccid,b.accountid as creditaccid,a.accountname as accdebitname,b.accountname as acccreditname,c.currencyname,e.fullname, f.fullname as customername, g.fullname as suppliername,f.addressbookid as customerid, g.addressbookid as suppid, h.plantcode as debplantcode,i.plantcode as credplantcode')->from('cbacc t')->leftjoin('account a', 'a.accountid=t.debitaccid')->leftjoin('account b', 'b.accountid=t.creditaccid')->leftjoin('currency c', 'c.currencyid=t.currencyid')->leftjoin('cheque d', 'd.chequeid=t.chequeid')->leftjoin('employee e','e.employeeid=t.employeeid')->leftjoin('addressbook f', 'f.addressbookid=t.customerid')->leftjoin('addressbook g', 'g.addressbookid=t.supplierid')->leftjoin('plant h', 'h.plantid=t.debplantid')->leftjoin('plant i', 'i.plantid=t.credplantid')->where('cbid = :cbid', array(
      ':cbid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cbaccid' => $data['cbaccid'],
        'cbid' => $data['cbid'],
        'debplantid' => $data['debplantid'],
        'credplantid' => $data['credplantid'],
        'debplantcode' => $data['debplantcode'],
        'credplantcode' => $data['credplantcode'],
        'debitaccid' => $data['debitaccid'],
        'creditaccid' => $data['creditaccid'],
        'accdebitname' => $data['accdebitname'],
        'acccreditname' => $data['acccreditname'],
        'employeeid' => $data['employeeid'],
        'fullname' => $data['fullname'],
        'chequeid' => $data['chequeid'],
        'chequeno' => $data['chequeno'],
        'customerid' => $data['customerid'],
        'customername' => $data['customername'],
        'supplierid' => $data['supplierid'],
        'suppliername' => $data['suppliername'],
        'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
        'tgltolak' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tgltolak'])),
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $cmd             = Yii::app()->db->createCommand()->select('
		sum(t.amount) as amount')->from('cbacc t')->join('account a', 'a.accountid=t.debitaccid')->join('account b', 'b.accountid=t.creditaccid')->join('currency c', 'c.currencyid=t.currencyid')->leftjoin('cheque d', 'd.chequeid=t.chequeid')->where('cbid = :cbid', array(
      ':cbid' => $id
    ))->queryScalar();
    $footer[] = array(
      'accdebitname' => 'Total',
      'amount' => Yii::app()->format->formatNumber($cmd)
    );
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select *,a.cashbankno,a.docdate,a.receiptno
			from cb a
			join company b on b.companyid = a.companyid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cbid in (" . $_GET['id'] . ")";
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
      $sql1        = "select b.accountname as accdebitname,c.accountname as acccreditname,case when a.employeeid <> 0 then concat(a.description,' - ',f.fullname) else a.description end as description,a.amount,d.currencyname,
					d.symbol,e.chequeno,a.currencyrate,f.fullname,
					case when a.tglcair = '1970-01-01' then null else a.tglcair end as tglcair,
					case when a.tgltolak = '1970-01-01' then null else a.tgltolak end as tgltolak,
                    g.plantcode as debplantcode,h.plantcode as credplantcode
					from cbacc a
					join account b on b.accountid = a.debitaccid
					join account c on c.accountid = a.creditaccid
                    left join plant g on g.plantid = a.debplantid
                    left join plant h on h.plantid = a.credplantid
					left join currency d on d.currencyid = a.currencyid
					left join cheque e on e.chequeid=a.chequeid
          left join employee f on f.employeeid=a.employeeid
					where a.cbid = " . $row['cbid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->colalign = array(
        'C',
        'L',
        'L',
        'L',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        7,
        45,
        25,
        38,
        25,
        15,
        20,
        15,
        15
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun Debit',
        'Akun Credit',
        'Uraian',
        'Nilai',
        'Kurs',
        'No. Cek/Giro',
        'Tgl. Cair',
        'Tgl. Tolak'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'R',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      $amount                    = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['accdebitname'].' '.$row1['debplantcode'],
          $row1['acccreditname'].' '.$row1['credplantcode'],
          $row1['description'],
          $row1['symbol'].Yii::app()->format->formatCurrency($row1['amount']),
          Yii::app()->format->formatCurrency($row1['currencyrate']),
          $row1['chequeno'],
          (($row1['tglcair'] !== null) ? date(Yii::app()->params['dateviewfromdb'], strtotime($row1['tglcair'])) : ''),
          (($row1['tgltolak'] !== null) ? date(Yii::app()->params['dateviewfromdb'], strtotime($row1['tgltolak'])) : '')
        ));
        $amount += $row1['amount'];
      }
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->row(array(
        '',
        '',
        '',
        'JUMLAH',
        $row1['symbol'].Yii::app()->format->formatCurrency($amount),
        '',
        '',
        '',
        ''
      ));
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
        'Terbilang : ' . eja($bilangan[0]) . ' ' . $row1['currencyname']
      ));
      $this->pdf->setFont('Arial', 'BI', 8);
      $this->pdf->row(array(
        '',
        'NOTE : ' . $row['headernote']
      ));
      $this->pdf->checkNewPage(15);
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
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select receiptno,docdate,recordstatus
				from cb a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cbid in (" . $_GET['id'] . ")";
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
    header('Content-Disposition: attachment;filename="cb.xlsx"');
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
