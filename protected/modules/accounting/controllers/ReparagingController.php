<?php
class ReparagingController extends Controller
{
  public $menuname = 'reparaging';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql              = "select az.*,
						case when umur > 0 and umur <= 30 then amount else 0 end as 1sd30,
						case when umur > 30 and umur <= 60 then amount else 0 end as 30sd60,
						case when umur > 60 and umur <= 90 then amount else 0 end as 60sd90,
						case when umur > 90 and umur <= 120 then amount else 0 end as 90sd120,
						case when umur > 120 then amount else 0 end as sd120
						from
						(select d.fullname,a.invoiceno,a.invoicedate,e.paydays,
						date_add(a.invoicedate,interval e.paydays day) as dropdate,
						datediff(date_add(a.invoicedate,interval e.paydays day),current_date()) as diffdate,
						datediff(current_date(),a.invoicedate) as umur,
						(a.amount * a.currencyrate) as amount
						from invoice a
						inner join giheader b on b.giheaderid = a.giheaderid
						inner join soheader c on c.soheaderid = b.soheaderid
						inner join addressbook d on d.addressbookid = c.addressbookid
						inner join paymentmethod e on e.paymentmethodid = c.paymentmethodid
						where a.invoiceid not in (
						select xx.invoiceid from cbarinv xx) and
						a.recordstatus = 3 and e.paymentmethodid > 1) az";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = 'AR AGING';
    $this->pdf->AddPage('L');
    $this->pdf->setFont('Arial', 'B', 10);
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
      'C'
    );
    $this->pdf->setwidths(array(
      25,
      25,
      25,
      25,
      30,
      30,
      30,
      30,
      30,
      30,
      30,
      30
    ));
    $this->pdf->colheader = array(
      'Customer',
      'Invoice No',
      'Invoice Date',
      'Expired Date',
      'Umur',
      '1 - 30',
      '31 - 60',
      '61 - 90',
      '91 - 120',
      '>= 121'
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
      'R',
      'R',
      'R',
      'R'
    );
    $totalexpamount            = 0;
    $total130                  = 0;
    $total3160                 = 0;
    $total6190                 = 0;
    $total91120                = 0;
    $total121                  = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', '', 8);
      $total130 += $row['1sd30'];
      $total3160 += $row['30sd60'];
      $total6190 += $row['60sd90'];
      $total91120 += $row['90sd120'];
      $total121 += $row['sd120'];
      $this->pdf->row(array(
        $row['fullname'],
        $row['invoiceno'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])),
        date(Yii::app()->params['dateviewfromdb'], strtotime($row['dropdate'])),
        $row['umur'] . ' hari',
        Yii::app()->numberFormatter->formatCurrency($row['1sd30'], getcurrencysymbol()),
        Yii::app()->numberFormatter->formatCurrency($row['30sd60'], getcurrencysymbol()),
        Yii::app()->numberFormatter->formatCurrency($row['60sd90'], getcurrencysymbol()),
        Yii::app()->numberFormatter->formatCurrency($row['90sd120'], getcurrencysymbol()),
        Yii::app()->numberFormatter->formatCurrency($row['sd120'], getcurrencysymbol())
      ));
    }
    $this->pdf->row(array(
      '',
      'Total',
      '',
      '',
      '',
      Yii::app()->numberFormatter->formatCurrency($total130, getcurrencysymbol()),
      Yii::app()->numberFormatter->formatCurrency($total3160, getcurrencysymbol()),
      Yii::app()->numberFormatter->formatCurrency($total6190, getcurrencysymbol()),
      Yii::app()->numberFormatter->formatCurrency($total91120, getcurrencysymbol()),
      Yii::app()->numberFormatter->formatCurrency($total121, getcurrencysymbol())
    ));
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select invoiceno,invoicedate,poheaderid,addressbookid,amount,currencyid,currencyrate,paymentmethodid,taxid,taxno,taxdate,recordstatus
				from invoiceap a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.invoiceapid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('invoiceno'))->setCellValueByColumnAndRow(1, 1, GetCatalog('invoicedate'))->setCellValueByColumnAndRow(2, 1, GetCatalog('poheaderid'))->setCellValueByColumnAndRow(3, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('amount'))->setCellValueByColumnAndRow(5, 1, GetCatalog('currencyid'))->setCellValueByColumnAndRow(6, 1, GetCatalog('currencyrate'))->setCellValueByColumnAndRow(7, 1, GetCatalog('paymentmethodid'))->setCellValueByColumnAndRow(8, 1, GetCatalog('taxid'))->setCellValueByColumnAndRow(9, 1, GetCatalog('taxno'))->setCellValueByColumnAndRow(10, 1, GetCatalog('taxdate'))->setCellValueByColumnAndRow(11, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['invoiceno'])->setCellValueByColumnAndRow(1, $i + 1, $row1['invoicedate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['poheaderid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['amount'])->setCellValueByColumnAndRow(5, $i + 1, $row1['currencyid'])->setCellValueByColumnAndRow(6, $i + 1, $row1['currencyrate'])->setCellValueByColumnAndRow(7, $i + 1, $row1['paymentmethodid'])->setCellValueByColumnAndRow(8, $i + 1, $row1['taxid'])->setCellValueByColumnAndRow(9, $i + 1, $row1['taxno'])->setCellValueByColumnAndRow(10, $i + 1, $row1['taxdate'])->setCellValueByColumnAndRow(11, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="invoiceap.xlsx"');
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