<?php
class RepcutarController extends Controller
{
  public $menuname = 'repcutar';
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
    $cutarid         = isset($_POST['cutarid']) ? $_POST['cutarid'] : '';
    $cutarno         = isset($_POST['cutarno']) ? $_POST['cutarno'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $ttntid          = isset($_POST['ttntid']) ? $_POST['ttntid'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'cutarid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();

    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cutar t')
		->leftjoin('ttnt a', 'a.ttntid=t.ttntid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.cutarno,'') like :cutarno) and
						(coalesce(b.companyname,'') like :companyid) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.cutarid,'') like :cutarid) and
						(coalesce(a.docno,'') like :ttntid) and t.companyid in (".getUserObjectValues('company').")", array(
      ':cutarid' => '%' . $cutarid . '%',
      ':cutarno' => '%' . $cutarno . '%',
      ':companyid' => '%' . $companyid . '%',
      ':docdate' => '%' . $docdate . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.docno,b.companyname')->from('cutar t')
		->leftjoin('ttnt a', 'a.ttntid=t.ttntid')
		->leftjoin('company b', 'b.companyid=t.companyid')
		->where("(coalesce(t.cutarno,'') like :cutarno) and
						(coalesce(b.companyname,'') like :companyid) and
						(coalesce(t.docdate,'') like :docdate) and
						(coalesce(t.cutarid,'') like :cutarid) and
						(coalesce(a.docno,'') like :ttntid) and t.companyid in (".getUserObjectValues('company').")", array(
      ':cutarid' => '%' . $cutarid . '%',
      ':cutarno' => '%' . $cutarno . '%',
      ':companyid' => '%' . $companyid . '%',
      ':docdate' => '%' . $docdate . '%',
      ':ttntid' => '%' . $ttntid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cutarid' => $data['cutarid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'cutarno' => $data['cutarno'],
        'ttntid' => $data['ttntid'],
        'docno' => $data['docno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('cutarinv t')->join('invoice a', 'a.invoiceid=t.invoiceid')->join('currency b', 'b.currencyid=t.currencyid')->leftjoin('notagir c', 'c.notagirid=t.notagirid')->where('cutarid = :cutarid', array(
      ':cutarid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.cutarinvid,t.cutarid,t.invoiceid,a.invoiceno,(a.amount-a.payamount) as saldoinvoicecurrent,t.saldoinvoice,a.invoicedate,t.cashamount,
			t.bankamount,t.discamount,t.returnamount,t.obamount,
			(t.saldoinvoice)-(ifnull(t.cashamount,0)+ifnull(t.bankamount,0)+ifnull(t.discamount,0)+ifnull(t.returnamount,0)+ifnull(t.obamount,0)) as saldo,
			t.currencyid,b.currencyname,t.currencyrate,t.description,c.notagirno')->from('cutarinv t')->join('invoice a', 'a.invoiceid=t.invoiceid')->join('currency b', 'b.currencyid=t.currencyid')->leftjoin('notagir c', 'c.notagirid=t.notagirid')->where('cutarid = :cutarid', array(
      ':cutarid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'cutarinvid' => $data['cutarinvid'],
        'cutarid' => $data['cutarid'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'saldoinvoice' => Yii::app()->format->formatNumber($data['saldoinvoice']),
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'cashamount' => Yii::app()->format->formatNumber($data['cashamount']),
        'bankamount' => Yii::app()->format->formatNumber($data['bankamount']),
        'discamount' => Yii::app()->format->formatNumber($data['discamount']),
        'returnamount' => Yii::app()->format->formatNumber($data['returnamount']),
        'notagirno' => $data['notagirno'],
        'obamount' => Yii::app()->format->formatNumber($data['obamount']),
        'saldo' => Yii::app()->format->formatNumber($data['saldo']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $cmd             = Yii::app()->db->createCommand()->select('sum(a.amount-a.payamount) as saldoinvoice,
			sum(t.cashamount) as cashamount,
			sum(t.bankamount) as bankamount,
			sum(t.discamount) as discamount,
			sum(t.returnamount) as returnamount,
			sum(t.obamount) as obamount,
			sum((a.amount-a.payamount)-(ifnull(t.cashamount,0)+ifnull(t.bankamount,0)+ifnull(t.discamount,0)+ifnull(t.returnamount,0)+ifnull(t.obamount,0))) as saldo
			')->from('cutarinv t')->join('invoice a', 'a.invoiceid=t.invoiceid')->join('currency b', 'b.currencyid=t.currencyid')->where('cutarid = :cutarid', array(
      ':cutarid' => $id
    ))->queryRow();
		$footer[] = array(
      'invoiceno' => 'Total',
      'saldoinvoice' => Yii::app()->format->formatNumber($cmd['saldoinvoice']),
      'cashamount' => Yii::app()->format->formatNumber($cmd['cashamount']),
      'bankamount' => Yii::app()->format->formatNumber($cmd['bankamount']),
      'discamount' => Yii::app()->format->formatNumber($cmd['discamount']),
      'returnamount' => Yii::app()->format->formatNumber($cmd['returnamount']),
      'obamount' => Yii::app()->format->formatNumber($cmd['obamount']),
      'saldo' => Yii::app()->format->formatNumber($cmd['saldo']),
    );
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select distinct a.cutarid,a.cutarno,a.docdate as cutardate,c.docno as ttntno,c.docdate as ttntdate,b.companyid
                        from cutar a
                        left join company b on b.companyid = a.companyid
                        left join ttnt c on c.ttntid = a.ttntid
                        left join cutarinv d on d.cutarid = a.cutarid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cutarid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('cutar');
    $this->pdf->AddPage('L', 'A4');
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['cutarno']);
      $this->pdf->text(160, $this->pdf->gety() + 2, 'TTNT ');
      $this->pdf->text(170, $this->pdf->gety() + 2, ': ' . $row['ttntno']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['cutardate'])));
      $this->pdf->text(160, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(170, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['ttntdate'])));
      $sql1        = "select a.cutarid,b.invoiceno,f.fullname,b.invoicedate,a.saldoinvoice,a.cashamount,a.bankamount,a.discamount,a.returnamount,a.obamount,
							(a.cashamount+a.bankamount+a.discamount+a.returnamount+a.obamount) as jumlah,c.currencyname,a.currencyrate,a.description,
							(a.saldoinvoice-(a.cashamount+a.bankamount+a.discamount+a.returnamount+a.obamount)) as sisa
                            from cutarinv a
                            left join invoice b on b.invoiceid = a.invoiceid
                            left join currency c on c.currencyid = a.currencyid
							left join giheader d on d.giheaderid=b.giheaderid
                            left join soheader e on e.soheaderid=d.soheaderid
                            left join addressbook f on f.addressbookid=e.addressbookid
                            where a.cutarid = " . $row['cutarid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $total1      = 0;
      $totalqty1   = 0;
      $total2      = 0;
      $totalqty2   = 0;
      $total3      = 0;
      $totalqty3   = 0;
      $total4      = 0;
      $totalqty4   = 0;
      $total5      = 0;
      $totalqty5   = 0;
      $total6      = 0;
      $totalqty6   = 0;
      $total7      = 0;
      $totalqty7   = 0;
      $this->pdf->sety($this->pdf->gety() + 10);
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
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        20,
        30,
        20,
        25,
        25,
        25,
        25,
        25,
        25,
        25,
        25
      ));
      $this->pdf->colheader = array(
        'No',
        'No Invoice',
        'Customer',
        'Tgl Invoice',
        'Saldo Invoice',
        'Tunai',
        'Bank',
        'Diskon',
        'Retur',
        'OB',
        'Jumlah',
        'Sisa'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'C',
        'L',
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
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->setFont('Arial', '', 7);
        $this->pdf->row(array(
          $i,
          $row1['invoiceno'],
          $row1['fullname'],
          date(Yii::app()->params['dateviewfromdb'], strtotime($row1['invoicedate'])),
          Yii::app()->format->formatNumber($row1['saldoinvoice']),
          Yii::app()->format->formatNumber($row1['cashamount']),
          Yii::app()->format->formatNumber($row1['bankamount']),
          Yii::app()->format->formatNumber($row1['discamount']),
          Yii::app()->format->formatNumber($row1['returnamount']),
          Yii::app()->format->formatNumber($row1['obamount']),
          Yii::app()->format->formatNumber($row1['jumlah']),
          Yii::app()->format->formatNumber($row1['sisa'])
        ));
        $total  = $row1['saldoinvoice'] + $total;
        $total1 = $row1['cashamount'] + $total1;
        $total2 = $row1['bankamount'] + $total2;
        $total3 = $row1['discamount'] + $total3;
        $total4 = $row1['returnamount'] + $total4;
        $total5 = $row1['obamount'] + $total5;
        $total6 = $row1['jumlah'] + $total6;
        $total7 = $row1['sisa'] + $total7;
      }
      $this->pdf->setbordercell(array(
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
        'TB',
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
        'Total',
        '',
        Yii::app()->format->formatNumber($total),
        Yii::app()->format->formatNumber($total1),
        Yii::app()->format->formatNumber($total2),
        Yii::app()->format->formatNumber($total3),
        Yii::app()->format->formatNumber($total4),
        Yii::app()->format->formatNumber($total5),
        Yii::app()->format->formatNumber($total6),
        Yii::app()->format->formatNumber($total7)
      ));
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '   Admin AR');
      $this->pdf->text(55, $this->pdf->gety() + 25, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'Chief Accounting');
      $this->pdf->checkNewPage(40);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select ttntid,docdate,recordstatus
				from cutar a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.cutarid in (" . $_GET['id'] . ")";
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
    header('Content-Disposition: attachment;filename="cutar.xlsx"');
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