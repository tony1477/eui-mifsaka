<?php
class RepreqpayController extends Controller
{
  public $menuname = 'repreqpay';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexinvoice()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchinvoice();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $reqpayid        = isset($_POST['reqpayid']) ? $_POST['reqpayid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $reqpayno        = isset($_POST['reqpayno']) ? $_POST['reqpayno'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $companyid      = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'reqpayid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		
		$rec = Yii::app()->db->createCommand()->select ('group_concat(distinct b.wfbefstat) as wfbefsat')
		->from('workflow a')
		->join('wfgroup b', 'b.workflowid = a.workflowid')
		->join('groupaccess c', 'c.groupaccessid = b.groupaccessid')
		->join('usergroup d', 'd.groupaccessid = c.groupaccessid')
		->join('useraccess e', 'e.useraccessid = d.useraccessid')
		->where(" upper(a.wfname) = upper('listpayreq')
		and e.username = '" . Yii::app()->user->name . "' ")->queryScalar();
		
		$com = Yii::app()->db->createCommand()->select ('group_concat(distinct a.menuvalueid) as menuvalueid')
		->from('groupmenuauth a')
		->join('menuauth b', 'b.menuauthid = a.menuauthid')
		->join('usergroup c', 'c.groupaccessid = a.groupaccessid')
		->join('useraccess d', 'd.useraccessid = c.useraccessid')
		->where("upper(b.menuobject) = upper('company')
		and d.username = '" . Yii::app()->user->name . "' ")->queryScalar();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpay t')
		->leftjoin('company a', 'a.companyid = t.companyid')
		->where("(coalesce(docdate,'') like :docdate) 
			and (coalesce(reqpayno,'') like :reqpayno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(reqpayid,'') like :reqpayid) 
			and (coalesce(companyname,'') like :companyid) 
			and t.companyid in ($com)", array(
      ':docdate' => '%' . $docdate . '%',
      ':headernote' => '%' . $headernote . '%',
      ':reqpayid' => '%' . $reqpayid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyname')->from('reqpay t')
		->leftjoin('company a', 'a.companyid = t.companyid')
		->where("(coalesce(docdate,'') like :docdate) 
			and (coalesce(reqpayno,'') like :reqpayno) 
			and (coalesce(headernote,'') like :headernote) 
			and (coalesce(companyname,'') like :companyid) 
			and (coalesce(reqpayid,'') like :reqpayid) and t.companyid in ($com)", array(
      ':docdate' => '%' . $docdate . '%',
      ':headernote' => '%' . $headernote . '%',
      ':reqpayid' => '%' . $reqpayid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':reqpayno' => '%' . $reqpayno . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'reqpayid' => $data['reqpayid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'reqpayno' => $data['reqpayno'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' =>  $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchinvoice()
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('reqpayinv t')->join('invoiceap a', 'a.invoiceapid=t.invoiceapid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('poheader c', 'c.poheaderid=a.poheaderid')->join('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.invoiceno,b.fullname as supplier,a.invoicedate,adddate(a.invoicedate,d.paydays) as duedate,t.amount')->from('reqpayinv t')->join('invoiceap a', 'a.invoiceapid=t.invoiceapid')->join('addressbook b', 'b.addressbookid=a.addressbookid')->join('poheader c', 'c.poheaderid=a.poheaderid')->join('paymentmethod d', 'd.paymentmethodid=c.paymentmethodid')->where('t.reqpayid = :reqpayid', array(
      ':reqpayid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'reqpayinvid' => $data['reqpayinvid'],
        'reqpayid' => $data['reqpayid'],
        'invoiceapid' => $data['invoiceapid'],
        'invoiceno' => $data['invoiceno'],
        'supplier' => $data['supplier'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'duedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['duedate'])),
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'payamount' => Yii::app()->format->formatNumber($data['payamount'])
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
    $sql = "select distinct a.reqpayid,a.docdate,d.fullname as supplier,b.bankname,d.bankaccountno,a.companyid,d.accountowner,a.reqpayno,
	  (select sum(za.amount)
				from invoiceap za
				join reqpayinv zb on zb.invoiceapid = za.invoiceapid
				where zb.reqpayid = a.reqpayid) as nilai
				from reqpay a 
				join reqpayinv b on b.reqpayid = a.reqpayid
				join invoiceap c on c.invoiceapid = b.invoiceapid
				join addressbook d on d.addressbookid = c.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.reqpayid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('reqpay');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No. Dokumen ');
      $this->pdf->text(40, $this->pdf->gety() + 2, ': ' . $row['reqpayno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Dibayarkan kepada ');
      $this->pdf->text(40, $this->pdf->gety() + 6, ': ' . $row['supplier']);
      $this->pdf->text(10, $this->pdf->gety() + 10, 'Sejumlah Rp. ');
      $this->pdf->text(40, $this->pdf->gety() + 10, ': ' . Yii::app()->format->formatCurrency($row['nilai']));
      $this->pdf->text(120, $this->pdf->gety() + 6, 'Bank ');
      $this->pdf->text(140, $this->pdf->gety() + 6, ': ' . $row['bankname']);
      $this->pdf->text(120, $this->pdf->gety() + 10, 'A/N ');
      $this->pdf->text(140, $this->pdf->gety() + 10, ': ' . $row['accountowner']);
      $this->pdf->SetFontSize(9);
      $this->pdf->text(120, $this->pdf->gety() + 14, 'No Rekening');
      $this->pdf->text(140, $this->pdf->gety() + 14, ': ' . $row['bankaccountno']);
      $this->pdf->SetFontSize(8);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'Tgl Dokumen ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(10, $this->pdf->gety() + 18, 'Terbilang ');
      $this->pdf->text(40, $this->pdf->gety() + 18, ': ');
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->setaligns(array(
        'C',
        'L'
      ));
      $this->pdf->setwidths(array(
        31,
        160
      ));
      $this->pdf->row(array(
        '',
        strtoupper(eja($row['nilai']))
      ));
      $sql1        = "select b.invoiceno,d.fullname as supplier,b.invoicedate,adddate(b.invoicedate,e.paydays) as duedate,a.amount,a.taxno,a.itemnote
        from reqpayinv a
        left join invoiceap b on b.invoiceapid = a.invoiceapid
        left join poheader c on c.poheaderid = b.poheaderid 
				left join addressbook d on d.addressbookid = c.addressbookid
				left join paymentmethod e on e.paymentmethodid = c.paymentmethodid
        where reqpayid = " . $row['reqpayid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 2);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        25,
        25,
        25,
        25,
        25,
        60
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'Tgl Invoice',
        'Nilai',
        'Jth Tempo',
        'No Faktur pajak',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'C',
        'C',
        'R',
        'C',
        'C',
        'L'
      );
      $i                         = 0;
      $total                     = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['invoiceno'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
          Yii::app()->format->formatCurrency($row1['amount']),
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['duedate'])),
          $row1['taxno'],
          $row1['itemnote']
        ));
        $total += $row1['amount'];
      }
      $this->pdf->SetFontSize(10);
      $this->pdf->setaligns(array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'L',
        'R'
      ));
      $this->pdf->setwidths(array(
        10,
        25,
        25,
        25,
        25,
        25,
        35
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        '',
        'TOTAL :',
        Yii::app()->format->formatCurrency($total)
      ));
      $this->pdf->checkNewPage(30);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety(), 'Diajukan oleh');
      $this->pdf->text(45, $this->pdf->gety(), 'Diperiksa oleh');
      $this->pdf->text(85, $this->pdf->gety(), 'Diketahui oleh');
      $this->pdf->text(125, $this->pdf->gety(), 'Disetujui oleh');
      $this->pdf->text(165, $this->pdf->gety(), 'Dibayar oleh');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(45, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(85, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(125, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(165, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(10, $this->pdf->gety() + 20, 'Adm H/D');
      $this->pdf->text(42, $this->pdf->gety() + 20, 'Divisi Acc & Finance');
      $this->pdf->text(85, $this->pdf->gety() + 20, 'Branch Manager');
      $this->pdf->text(125, $this->pdf->gety() + 20, 'Dir. Keuangan');
      $this->pdf->text(165, $this->pdf->gety() + 20, 'Bag. Bank pusat');
      $this->pdf->text(10, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(42, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(85, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(125, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->text(165, $this->pdf->gety() + 25, 'Tgl :');
      $this->pdf->setFontSize(7);
      $this->pdf->text(10, $this->pdf->gety() + 33, 'NB :Faktur pajak wajib diisi jika pembayaran melalui Legal (Tanpa melampirkan faktur pajak lagi)');
      $this->pdf->text(10, $this->pdf->gety() + 38, '     :Dibuat rangkap 3, putih untuk Bag.Bank/Kasir, setelah dibayar diserahkan ke Adm H/D,Rangkap 2 utk Bag.Pajak,rangkap 3 Arsip H/D');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select docdate,reqpayno,headernote,recordstatus
				from reqpay a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.reqpayid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('reqpayno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(3, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['reqpayno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(3, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reqpay.xlsx"');
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