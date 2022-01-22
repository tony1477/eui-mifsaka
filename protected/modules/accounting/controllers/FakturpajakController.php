<?php
class FakturpajakController extends Controller
{
  public $menuname = 'fakturpajak';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $fakturpajakno   = isset($_POST['fakturpajakno']) ? $_POST['fakturpajakno'] : '';
    $invoiceid       = isset($_POST['invoiceid']) ? $_POST['invoiceid'] : '';
    $fakturpajakno   = isset($_GET['q']) ? $_GET['q'] : $fakturpajakno;
    $invoiceid       = isset($_GET['q']) ? $_GET['q'] : $invoiceid;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'fakturpajakid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('fakturpajak t')->leftjoin('invoice a', 'a.invoiceid = t.invoiceid')->leftjoin('giheader b', 'b.giheaderid = a.giheaderid')->leftjoin('soheader c', 'c.soheaderid = b.soheaderid')->leftjoin('company d', 'd.companyid = c.companyid')->leftjoin('addressbook e', 'e.addressbookid = c.addressbookid')->where("((fakturpajakno like :fakturpajakno) or (invoiceno like :invoiceno))", array(
      ':fakturpajakno' => '%' . $fakturpajakno . '%',
      ':invoiceno' => '%' . $invoiceid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.invoiceno,b.gino,c.sono,d.companyname,e.fullname')->from('fakturpajak t')->leftjoin('invoice a', 'a.invoiceid = t.invoiceid')->leftjoin('giheader b', 'b.giheaderid = a.giheaderid')->leftjoin('soheader c', 'c.soheaderid = b.soheaderid')->leftjoin('company d', 'd.companyid = c.companyid')->leftjoin('addressbook e', 'e.addressbookid = c.addressbookid')->where("((fakturpajakno like :fakturpajakno) or (invoiceno like :invoiceno))", array(
      ':fakturpajakno' => '%' . $fakturpajakno . '%',
      ':invoiceno' => '%' . $invoiceid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'fakturpajakid' => $data['fakturpajakid'],
        'fakturpajakno' => $data['fakturpajakno'],
        'invoiceid' => $data['invoiceid'],
        'invoiceno' => $data['invoiceno'],
        'companyname' => $data['companyname'],
        'fullname' => $data['fullname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSave()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertfakturpajak(:vfakturpajakno,:vinvoiceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatefakturpajak(:vid,:vfakturpajakno,:vinvoiceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['fakturpajakid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['fakturpajakid']);
      }
      $command->bindvalue(':vfakturpajakno', $_POST['fakturpajakno'], PDO::PARAM_STR);
      $command->bindvalue(':vinvoiceid', $_POST['invoiceid'], PDO::PARAM_STR);
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
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgefakturpajak(:vid,:vcreatedby)';
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
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select f.giheaderid,b.invoiceid,a.fakturpajakno,fullname,invoicedate,b.invoiceno,b.amount,
				(select addressname as custaddress from address z where z.addressbookid = c.addressbookid order by z.addressid desc limit 1) as addressname,
				(select cityname from city y left join address x on x.cityid = y.cityid where x.addressbookid = c.addressbookid limit 1) as cityname,
				c.taxno,d.taxvalue,
				g.companyname,
				g.address as companyaddressname,
				h.cityname as companycityname,
				g.taxno as companytaxno
			from fakturpajak a
			left join invoice b on b.invoiceid = a.invoiceid
			left join giheader f on f.giheaderid = b.giheaderid
			left join soheader e on e.soheaderid = f.soheaderid
			left join addressbook c on c.addressbookid = e.addressbookid
			left join company g on g.companyid = e.companyid
			left join city h on h.cityid = g.cityid
			left join tax d on d.taxid = e.taxid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.fakturpajakid in (" . $_GET['id'] . ")";
    }
    $sql                       = $sql . " order by fakturpajakid";
    $command                   = $this->connection->createCommand($sql);
    $dataReader                = $command->queryAll();
    $this->pdf->isheader       = false;
    $this->pdf->iscustomborder = true;
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 12);
      $this->pdf->rect(10, 20, 180, 15);
      $this->pdf->text(90, 30, 'FAKTUR PAJAK');
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->rect(10, 35, 180, 6);
      $this->pdf->text(12, 39, 'Kode dan Nomor Seri Faktur Pajak : ' . $row['fakturpajakno']);
      $this->pdf->text(130, 39, 'Invoice No : ' . $row['invoiceno']);
      $this->pdf->rect(10, 41, 180, 6);
      $this->pdf->text(12, 45, 'Pengusaha Kena Pajak');
      $this->pdf->rect(10, 47, 180, 20);
      $this->pdf->text(12, 51, 'N a m a');
      $this->pdf->text(50, 51, ':' . $row['companyname']);
      $this->pdf->text(12, 56, 'A l a m a t');
      $this->pdf->text(50, 56, ':' . $row['companyaddressname']);
      $this->pdf->text(12, 61, '');
      $this->pdf->text(50, 61, ':' . $row['companycityname']);
      $this->pdf->text(12, 66, 'NPWP');
      $this->pdf->text(50, 66, ':' . $row['companytaxno']);
      $this->pdf->rect(10, 67, 180, 6);
      $this->pdf->text(12, 71, 'Pembeli Barang Kena Pajak / Penerima Jasa Kena Pajak');
      $this->pdf->rect(10, 73, 180, 22);
      $this->pdf->text(12, 78, 'N a m a');
      $this->pdf->text(60, 78, ':' . $row['fullname']);
      $this->pdf->text(12, 83, 'A l a m a t');
      $this->pdf->text(60, 83, ':' . $row['addressname']);
      $this->pdf->text(12, 88, '');
      $this->pdf->text(60, 88, ':' . $row['cityname']);
      $this->pdf->text(12, 93, 'NPWP');
      $this->pdf->text(60, 93, ':' . $row['taxno']);
      $sql1        = "select d.productname,a.qty,uomcode,price*a.qty as price,symbol,currencyrate,a.itemnote,
	    price * a.qty * " . $row['taxvalue'] . "/100 as taxvalue
        from gidetail a
				left join sodetail e on e.sodetailid = a.sodetailid
		left join currency b on b.currencyid = e.currencyid
		left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
		left join product d on d.productid = a.productid
        where giheaderid = " . $row['giheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->SetY($this->pdf->gety() + 35);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        15,
        95,
        70
      ));
      $this->pdf->setbordercell(array(
        'LTRB',
        'LTRB',
        'LTRB'
      ));
      $this->pdf->colheader = array(
        'No Urut',
        'Nama Barang Kena Pajak / Jasa Kena Pajak',
        'Harga Jual/Penggantian/Uang Muka/Termin'
      );
      $this->pdf->RowHeader();
      $this->pdf->sety($this->pdf->gety() - 5);
      $this->pdf->coldetailalign = array(
        'C',
        'L',
        'R'
      );
      $this->pdf->setbordercell(array(
        'LTR',
        'LTR',
        'LTR'
      ));
      $this->pdf->row(array(
        '',
        '',
        ''
      ));
      $this->pdf->setbordercell(array(
        'LR',
        'LR',
        'LR'
      ));
      $total  = 0;
      $i      = 0;
      $symbol = "";
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->numberFormatter->formatCurrency($row1['price'], $row1['symbol'])
        ));
        $total  = $total + ($row1['price']);
        $symbol = $row1['symbol'];
      }
      for ($i = 0; $i < 10; $i++) {
        $this->pdf->row(array(
          ' ',
          ' ',
          ' '
        ));
      }
      $this->pdf->setbordercell(array(
        'LTB',
        'TRB',
        'LTRB'
      ));
      $this->pdf->row(array(
        '',
        'Harga Jual/Penggantian/Uang Muka/Termin**)',
        Yii::app()->format->formatCurrency($total, $symbol)
      ));
      $this->pdf->row(array(
        '',
        'Dikurangi Potongan Harga',
        Yii::app()->format->formatCurrency($row['amount'], $symbol)
      ));
      $this->pdf->row(array(
        '',
        'Dikurangi Uang Muka yang telah diterima',
        '-'
      ));
      $this->pdf->row(array(
        '',
        'Dasar Pengenaan Pajak',
        Yii::app()->format->formatCurrency($total - $row['amount'], $symbol)
      ));
      $this->pdf->row(array(
        '',
        'PPN = 10% x Dasar Pengenaan Pajak',
        Yii::app()->format->formatCurrency($total * $row['taxvalue'] / 100, $symbol)
      ));
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(12, $this->pdf->gety(), 'Pajak Penjualan Atas Barang Mewah');
      $this->pdf->text(150, $this->pdf->gety(), 'Tanggal: ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])));
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->setaligns(array(
        'C',
        'C',
        'C'
      ));
      $this->pdf->setwidths(array(
        15,
        15,
        15
      ));
      $this->pdf->setbordercell(array(
        'LTRB',
        'LTRB',
        'LTRB'
      ));
      $this->pdf->Row(array(
        'Tarif',
        'DPP',
        'PPnBM'
      ));
      $this->pdf->setbordercell(array(
        'LR',
        'LR',
        'LR'
      ));
      $this->pdf->Row(array(
        '.........%',
        'Rp........',
        'Rp........'
      ));
      $this->pdf->Row(array(
        '.........%',
        'Rp........',
        'Rp........'
      ));
      $this->pdf->Row(array(
        '.........%',
        'Rp........',
        'Rp........'
      ));
      $this->pdf->Row(array(
        '.........%',
        'Rp........',
        'Rp........'
      ));
      $this->pdf->setbordercell(array(
        'LTB',
        'RTB',
        'LRTB'
      ));
      $this->pdf->Row(array(
        'Jumlah',
        '',
        'Rp........'
      ));
      $this->pdf->text(12, $this->pdf->gety() + 20, '**) Coret yang tidak perlu');
      $this->pdf->text(150, $this->pdf->gety(), '_________________');
      $this->pdf->text(150, $this->pdf->gety() + 5, '');
      $this->pdf->CheckPageBreak(0);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select fakturpajakno,invoiceid
				from fakturpajak a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.fakturpajakid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('fakturpajakno'))->setCellValueByColumnAndRow(1, 1, GetCatalog('invoiceid'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['fakturpajakno'])->setCellValueByColumnAndRow(1, $i + 1, $row1['invoiceid']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="fakturpajak.xlsx"');
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