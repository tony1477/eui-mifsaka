<?php
class RepttntController extends Controller
{
  public $menuname = 'repttnt';
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
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $ttntid        = isset($_POST['ttntid']) ? $_POST['ttntid'] : '';
    $companyid     = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $docdate       = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $docno         = isset($_POST['docno']) ? $_POST['docno'] : '';
    $employeeid    = isset($_POST['employeeid']) ? $_POST['employeeid'] : '';
    $addressbookid = isset($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
    $description   = isset($_POST['description']) ? $_POST['description'] : '';
    $page          = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows          = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort          = isset($_POST['sort']) ? strval($_POST['sort']) : 'ttntid';
    $order         = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset        = ($page - 1) * $rows;
    $result        = array();
    $row           = array();
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('ttnt t')->leftjoin('employee j', 'j.employeeid = t.employeeid')->leftjoin('company k', 'k.companyid = t.companyid')->where("
      	((coalesce(docdate,'') like :docdate) and 
          (coalesce(docno,'') like :docno) and 
          (coalesce(j.fullname,'') like :employeeid) and 
          (coalesce(t.description,'') like :description) and 
          (coalesce(k.companyname,'') like :companyid))
					and t.companyid in (".getUserObjectValues('company').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':docno' => '%' . $docno . '%',
        ':employeeid' => '%' . $employeeid . '%',
        ':description' => '%' . $description . '%',
        ':companyid' => '%'.$companyid.'%'
      ))->queryRow();
    $result['total'] = $cmd['total'];
      $cmd = Yii::app()->db->createCommand()->select('t.*,t.docdate,t.docno,k.companyname,t.employeeid,j.fullname as employeename,t.description')->from('ttnt t')->leftjoin('employee j', 'j.employeeid = t.employeeid')->leftjoin('company k', 'k.companyid = t.companyid')->where("
      	((coalesce(docdate,'') like :docdate) and 
          (coalesce(docno,'') like :docno) and 
          (coalesce(j.fullname,'') like :employeeid) and 
          (coalesce(t.description,'') like :description) and 
          (coalesce(k.companyname,'') like :companyid))
					and t.companyid in (".getUserObjectValues('company').")", array(
        ':docdate' => '%' . $docdate . '%',
        ':docno' => '%' . $docno . '%',
        ':employeeid' => '%' . $employeeid . '%',
        ':description' => '%' . $description . '%',
        ':companyid' => '%'.$companyid.'%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'ttntid' => $data['ttntid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'docno' => $data['docno'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'employeeid' => $data['employeeid'],
        'employeename' => $data['employeename'],
        'description' => $data['description'],
        'iscbin' => $data['iscbin'],
        'iscutar' => $data['iscutar'],
        'isreturn' => $data['isreturn'],
        'isreject' => $data['isreject'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusttnt' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.ttntdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('ttntdetail t')->leftjoin('invoice b', 'b.invoiceid = t.invoiceid')->leftjoin('giheader c', 'c.giheaderid = b.giheaderid')->leftjoin('soheader d', 'd.soheaderid = c.soheaderid')->leftjoin('addressbook e', 'e.addressbookid = d.addressbookid')->where('ttntid = :ttntid', array(
      ':ttntid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,b.invoicedate,c.gino,d.sono,e.fullname,b.invoiceno,
		t.amount,t.payamount,t.amount-t.payamount as sisa,now() as hariini,
					adddate(b.invoicedate,f.paydays) as jatuhtempo')->from('ttntdetail t')->leftjoin('invoice b', 'b.invoiceid = t.invoiceid')->leftjoin('giheader c', 'c.giheaderid = b.giheaderid')->leftjoin('soheader d', 'd.soheaderid = c.soheaderid')->leftjoin('addressbook e', 'e.addressbookid = d.addressbookid')->leftjoin('paymentmethod f', 'f.paymentmethodid = d.paymentmethodid')->where('ttntid = :ttntid', array(
      ':ttntid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['hariini'] < $data['jatuhtempo']) {
				$wjth = 0;
			} else {
				$wjth = 1;
			}
      $row[] = array(
        'ttntdetailid' => $data['ttntdetailid'],
        'ttntid' => $data['ttntid'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'jatuhtempo' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['jatuhtempo'])),
        'gino' => $data['gino'],
        'sono' => $data['sono'],
        'fullname' => $data['fullname'],
        'wjth' => $wjth,
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'payamount' => Yii::app()->format->formatNumber($data['payamount']),
        'sisa' => Yii::app()->format->formatNumber($data['sisa']),
      );
    }
    $cmd      = Yii::app()->db->createCommand()->select('sum(b.amount) as totalamount,sum(b.payamount) as totalpayamount,sum(b.amount-b.payamount) as totalsisa')->from('ttntdetail t')->leftjoin('invoice b', 'b.invoiceid = t.invoiceid')->where('ttntid = :ttntid', array(
      ':ttntid' => $id
    ))->queryRow();
    $footer[] = array(
      'gino' => 'Total',
      'amount' => Yii::app()->format->formatNumber($cmd['totalamount']),
      'payamount' => Yii::app()->format->formatNumber($cmd['totalpayamount']),
      'sisa' => Yii::app()->format->formatNumber($cmd['totalsisa']),
    );
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    ob_start();
    $sql = "select a.ttntid,a.companyid,a.docno,b.fullname as employeename,a.docdate
				from ttnt a 
				inner join employee b on b.employeeid = a.employeeid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.ttntid in (" . $_GET['id'] . ")";
    }
    $command             = $this->connection->createCommand($sql);
    $dataReader          = $command->queryAll();
		foreach ($dataReader as $row) 
		{
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->isheader = false;
    $this->pdf->AddPage('L', 'Letter');
    foreach ($dataReader as $row) {
			$digit = substr($row['docno'],-4);
        $string = ltrim($digit,'0');
        $nilai = eja($string);
        $x = str_replace('Koma','',$nilai);
      $i      = 0;
      $total2 = 0;
      $this->pdf->SetFont('Arial', '', 12);
      $this->pdf->text(10, $this->pdf->gety() + 0, 'Tanda Terima Nota Tagihan');
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 5, 'TTNT No. ');
      $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . $row['docno'].' ( '.$x.')');
      $this->pdf->text(10, $this->pdf->gety() + 10, 'TTNT Date ');
      $this->pdf->text(30, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(10, $this->pdf->gety() + 15, 'Sales ');
      $this->pdf->text(30, $this->pdf->gety() + 15, ': ' . $row['employeename']);
      $sql1        = "select distinct e.addressbookid,e.fullname
					from ttntdetail a
					join invoice b on b.invoiceid = a.invoiceid
					join giheader c on c.giheaderid = b.giheaderid
					join soheader d on d.soheaderid = c.soheaderid
					join paymentmethod f on f.paymentmethodid = d.paymentmethodid
					join addressbook e on e.addressbookid = d.addressbookid
					where a.ttntid = " . $row['ttntid'] . " order by fullname ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->colalign = array(
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
        'C'
      );
      $this->pdf->setwidths(array(
        8,
        40,
        17,
        21,
        17,
        25,
        20,
        20,
        20,
        20,
        20,
        20,
        20
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
        'LTRB'
      ));
      $this->pdf->colheader = array(
        'No.',
        'Customer',
        'Tgl. Inv.',
        'No. Inv.',
        'Tgl. JTT',
        'Nilai Inv.',
        'Tunai',
        'Bank',
        'Diskon',
        'Retur',
        'Ov. Booking',
        'Sisa',
        'Ket.'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'C',
        'L',
        'C',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', '', 8);
      foreach ($dataReader1 as $row1) {
        $total               = 0;
        $this->pdf->colalign = array(
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
          'C'
        );
        $this->pdf->setwidths(array(
          8,
          40,
          17,
          21,
          17,
          25,
          20,
          20,
          20,
          20,
          20,
          20,
          20
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
          'LTRB'
        ));
        $this->pdf->coldetailalign = array(
          'L',
          'L',
          'C',
          'L',
          'C',
          'R',
          'R',
          'R',
          'R',
          'R',
          'R',
          'R',
          'R'
        );
        $this->pdf->setFont('Arial', '', 8);
        $sql2        = "select b.invoiceno,d.sono,e.fullname,b.invoicedate,adddate(b.invoicedate,f.paydays) as jatuhtempo, a.amount,
					b.amount-ifnull((select sum((ifnull(f.cashamount,0)+ifnull(f.bankamount,0)+ifnull(f.discamount,0)+ifnull(f.returnamount,0)+ifnull(f.obamount,0))*ifnull(f.currencyrate,0))
					from cutarinv f
					join cutar g on g.cutarid=f.cutarid
					where g.recordstatus=getwfmaxstatbywfname('appcutar') and f.invoiceid=a.invoiceid and g.docdate <= h.docdate),0) as saldoinvoice
					from ttntdetail a
					join invoice b on b.invoiceid = a.invoiceid
					join giheader c on c.giheaderid = b.giheaderid
					join soheader d on d.soheaderid = c.soheaderid
					join paymentmethod f on f.paymentmethodid = d.paymentmethodid
					join addressbook e on e.addressbookid = d.addressbookid
					join ttnt h on h.ttntid=a.ttntid
					where a.ttntid = " . $row['ttntid'] . " and e.addressbookid = " . $row1['addressbookid'] . " order by fullname ";
        $command2    = $this->connection->createCommand($sql2);
        $dataReader2 = $command2->queryAll();
        foreach ($dataReader2 as $row2) {
          $i += 1;
          $this->pdf->row(array(
            $i,
            $row2['fullname'],
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['invoicedate'])),
            $row2['invoiceno'],
            date(Yii::app()->params['dateviewfromdb'], strtotime($row2['jatuhtempo'])),
            Yii::app()->format->formatNumber($row2['saldoinvoice']),
            '',
            '',
            '',
            '',
            '',
            '',
            ''
          ));
          $total += $row2['saldoinvoice'];
        }
        $this->pdf->setwidths(array(
          103,
          25,
          20,
          20,
          20,
          20,
          20,
          20,
          20
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
          'LTRB'
        ));
        $this->pdf->coldetailalign = array(
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
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->row(array(
          'TOTAL ' . $row1['fullname'] . '  >>> ',
          Yii::app()->format->formatNumber($total),
          '',
          '',
          '',
          '',
          '',
          '',
          ''
        ));
        $total2 += $total;
      }
      $this->pdf->setwidths(array(
        103,
        25,
        20,
        20,
        20,
        20,
        20,
        20,
        20
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
        'LTRB'
      ));
      $this->pdf->coldetailalign = array(
        'C',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R',
        'R'
      );
      $this->pdf->setFont('Arial', 'B', 8);
      $this->pdf->row(array(
        'GRAND TOTAL  >>> ',
        Yii::app()->format->formatNumber($total2),
        '',
        '',
        '',
        '',
        '',
        '',
        ''
      ));
      $this->pdf->checkNewPage(15);
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(35, $this->pdf->gety(), '        PENYERAHAN INVOICE');
      $this->pdf->text(125, $this->pdf->gety(), 'FISIK UANG TUNAI');
      $this->pdf->text(200, $this->pdf->gety(), '    PENGEMBALIAN INVOICE');
      $this->pdf->text(15, $this->pdf->gety() + 4, '       Diserahkan oleh,');
      $this->pdf->text(70, $this->pdf->gety() + 4, '     Diterima oleh,');
      $this->pdf->text(125, $this->pdf->gety() + 4, '     Diterima oleh,');
      $this->pdf->text(180, $this->pdf->gety() + 4, ' Diserahkan oleh,');
      $this->pdf->text(235, $this->pdf->gety() + 4, '    Diterima oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 25, '     ..............................');
      $this->pdf->text(70, $this->pdf->gety() + 25, ' ..............................');
      $this->pdf->text(125, $this->pdf->gety() + 25, '..............................');
      $this->pdf->text(180, $this->pdf->gety() + 25, '..............................');
      $this->pdf->text(235, $this->pdf->gety() + 25, '..............................');
      $this->pdf->text(24, $this->pdf->gety() + 28, 'Admin AR');
      $this->pdf->text(78, $this->pdf->gety() + 28, 'Sales');
      $this->pdf->text(129, $this->pdf->gety() + 28, 'Admin Kasir');
      $this->pdf->text(188, $this->pdf->gety() + 28, 'Sales');
      $this->pdf->text(240, $this->pdf->gety() + 28, 'Admin AR');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select docdate,docno,employeeid,addressbookid,description
				from ttnt a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.ttntid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('docno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('employeeid'))->setCellValueByColumnAndRow(3, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('description'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['docno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['employeeid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['description']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ttnt.xlsx"');
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
