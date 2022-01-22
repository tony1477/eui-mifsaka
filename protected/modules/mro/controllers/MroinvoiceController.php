<?php
class MroinvoiceController extends Controller
{
  public $menuname = 'mroinv';
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
  public function actionGeneratedate()
  {
    $cmd = Yii::app()->db->createCommand()->select('t.gidate')->from('giheader t')->where("t.giheaderid = '" . $_POST['giheaderid'] . "'")->limit(1)->queryRow();
    if (Yii::app()->request->isAjaxRequest) {
      echo CJSON::encode(array(
        'status' => 'success',
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($cmd['gidate']))
      ));
      Yii::app()->end();
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $invoicedate = isset($_POST['invoicedate']) ? $_POST['invoicedate'] : '';
    $invoiceno   = isset($_POST['invoiceno']) ? $_POST['invoiceno'] : '';
    $giheaderid  = isset($_POST['giheaderid']) ? $_POST['giheaderid'] : '';
    $headernote  = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $invoicedate = isset($_GET['q']) ? $_GET['q'] : $invoicedate;
    $invoiceno   = isset($_GET['q']) ? $_GET['q'] : $invoiceno;
    $giheaderid  = isset($_GET['q']) ? $_GET['q'] : $giheaderid;
    $headernote  = isset($_GET['q']) ? $_GET['q'] : $headernote;
    $page        = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows        = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort        = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceid';
    $order       = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset      = ($page - 1) * $rows;
    $page        = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows        = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort        = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order       = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset      = ($page - 1) * $rows;
    $result      = array();
    $row         = array();
    
    $sqlcount = "SELECT COUNT(1) as total ";
    $sqldata = "select a0.mroinvoiceid,a0.invoicedate,a0.invoiceno,a0.mrogiheaderid,a0.amount,a0.currencyid,a0.currencyrate,a0.taxid,a0.payamount,a0.headernote,a0.recordstatus,a0.statusname,a1.mrogino as mrogino,a2.currencyname as currencyname,a3.taxcode as taxvalue "; 
    
    $from = "from mroinvoice a0 
    left join mrogiheader a1 on a1.mrogiheaderid = a0.mrogiheaderid
    left join currency a2 on a2.currencyid = a0.currencyid
    left join tax a3 on a3.taxid = a0.taxid ";
    $where = "WHERE a0.recordstatus <> 0 ";
    
    $connection = Yii::app()->db;
    $cmd = $connection->createCommand($sqlcount.$from.$where)->queryRow();
    $result['total'] = $cmd['total']; 
    
    $cmd = $connection->createCommand($sqldata.$from.$where. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'mroinvoiceid' => $data['mroinvoiceid'],
        'mroinvoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'mroinvoiceno' => $data['invoiceno'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatCurrency($data['currencyrate']),
        'mrogiheaderid' => $data['mrogiheaderid'],
        'mrogino' => $data['mrogino'],
        'taxvalue' => $data['taxvalue'],
        'taxid' => $data['taxid'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'payamount' => Yii::app()->format->formatNumber($data['payamount']),
        'headernote' => $data['headernote'],
        'recordstatus' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchDetail()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'gidetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('mrogidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->where('mrogiheaderid = :mrogiheaderid', array(
      ':mrogiheaderid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode')->from('mrogidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->where('mrogiheaderid = :mrogiheaderid', array(
      ':mrogiheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'qty' => $data['qty'],
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
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
        $sql     = 'call Insertmroinvoice(:vmrogiheaderid,:vcurrencyid,:vcurrencyrate,:vheadernote,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatemroinvoice(:vid,:vmrogiheaderid,:vcurrencyid,:vcurrencyrate,:vheadernote,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['mroinvoiceid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['mroinvoiceid']);
      }
      $command->bindvalue(':vmrogiheaderid', $_POST['mrogiheaderid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyrate', $_POST['currencyrate'], PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', findstatusbyuser('insinvar'), PDO::PARAM_STR);
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Deletemroinv(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(false, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage(), 1);
      }
    } else {
      GetMessage(true, 'chooseone', 1);
    }
  }
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvemroinv(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
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
        $sql     = 'call Purgeinvoice(:vid,:vcreatedby)';
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
  public function actionDownPDF()
  {
    parent::actionDownload();
    $this->pdf->title='PT. AKA';
        $this->pdf->AddPage('P',array(220,140));
        
        $this->pdf->SetFont('Arial','U');
        $this->pdf->text(100,7,'INVOICE');
        
        $sql = "select a.invoiceno, ifnull(d.sono,'-') as sono, c.gino, b.mrogino, b.shipto, c.giheaderid, b.mrogiheaderid, a.amount, b.headernote, d.soheaderid, upper(e.fullname) as customer, upper(i.addressname) as addressname,  a.invoicedate, date_add(a.invoicedate, INTERVAL h.paydays day) as duedate, b.addressbookid, upper(j.fullname) as salesname, i.cityid
                from mroinvoice a 
                left join mrogiheader b on b.mrogiheaderid = a.mrogiheaderid
                left join giheader c on c.giheaderid = b.giheaderid
                left join soheader d on d.soheaderid = c.soheaderid
                left join addressbook e on e.addressbookid = d.addressbookid
                left join address i on i.addressbookid = e.addressbookid
                left join company f on f.companyid = d.companyid
                left join city g on g.cityid = f.cityid 
                left join paymentmethod h on h.paymentmethodid = d.paymentmethodid 
                left join employee j on j.employeeid = d.employeeid ";
		if ($_GET['id'] !== '') {
            $sql = $sql . "WHERE a.mroinvoiceid in (".$_GET['id'].")";
		}
        $sql = $sql . " order by mroinvoiceid";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
        
		foreach($dataReader as $row){
            $city = "SELECT upper(cityname) as cityname FROM city WHERE cityid='".$row['cityid']."'";
            $cityname = Yii::app()->db->createCommand($city)->queryScalar();
            
            $this->pdf->SetFont('Arial','B',12);
            $this->pdf->text(97,12,$row['invoiceno']);

            $this->pdf->SetFont('Arial','',10);
            
            $this->pdf->text(142,17,$cityname.', '.date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate'])));
            $this->pdf->text(142,22,'KEPADA YTH,');
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->text(142,27,$row['customer']);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->text(142,32,$row['addressname']);
            //$this->pdf->text(145,37,$row['cityname']);

            $this->pdf->text(10,22,'NO.SO');$this->pdf->text(40,22,': '.$row['sono']);
            $this->pdf->text(10,27,'SALES');$this->pdf->text(40,27,': '.$row['salesname']);
            $this->pdf->text(10,32,'ALAMAT KIRIM');$this->pdf->text(40,32,': '.$row['shipto']);
            //$this->pdf->text(10,37,'KET');$this->pdf->text(30,37,': '. $row['headernote']);
            //$this->pdf->text(30,42,' Dikirim '. $row['shipto']);
            $this->pdf->setY(33);$this->pdf->setX(9);
            $this->pdf->colalign = array('L','L','L','L');
            $this->pdf->setwidths(array(30,2,100,85));
            $this->pdf->coldetailalign = array('L','L','L','L');
            $this->pdf->row(array('KET',': ',$row['headernote'],$cityname));
        }
        
       $sql1 = "select * from (select a.mrogidetailid,d.productname,sum(a.qty) as qty,c.uomcode,a.netprice,b.symbol,a.itemnote,
                (a.netprice * sum(a.qty) * ifnull(e.taxvalue,0)/100) as taxvalue
                from mrogidetail a
                join mroinvoice x on x.mrogiheaderid = a.mrogiheaderid
                inner join product d on d.productid = a.productid
                inner join currency b on b.currencyid = x.currencyid
                inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
                left join tax e on e.taxid = x.taxid
                where a.mrogiheaderid = '".$row['mrogiheaderid']."' group by d.productname,a.mrogidetailid order by a.mrogidetailid
                ) zz order by zz.mrogidetailid";
        $dataReader1=Yii::app()->db->createCommand($sql1)->queryAll();

        $this->pdf->setY(46);
        $this->pdf->colalign = array('L','L','C','C','C','C');
        $this->pdf->setwidths(array(10,95,18,18,33,33));
        $this->pdf->colheader = array('NO','NAMA BARANG','QTY','SATUAN','HARGA','JUMLAH');
        $this->pdf->RowHeader();
        $this->pdf->coldetailalign = array('L','L','C','C','R','R');
        $i=1;$total=0;
        foreach($dataReader1 as $row1){
            if($row1['taxvalue']==0 || $row1['taxvalue']==''){
                $row1['taxvalue'] = 10;
            }
            $b=$row1['symbol'];
            $this->pdf->row(array($i,$row1['productname'],
            Yii::app()->format->formatNumber($row1['qty']),
            $row1['uomcode'],
            Yii::app()->format->formatCurrency($row1['netprice'],$row1['symbol']),
            Yii::app()->format->formatCurrency(($row1['netprice'] * $row1['qty']) + $row1['taxvalue'],$row1['symbol'])));
            $total += ($row1['netprice'] * $row1['qty']) + $row1['taxvalue'];   
            $i++;
            $this->pdf->checkNewPage(20);
        }
        $this->pdf->setY($this->pdf->getY()+3);
        $this->pdf->setaligns(array('L','C','R'));
        $this->pdf->setFont('Arial','',11);
        $this->pdf->setwidths(array(150,20,40));
        $this->pdf->row(array('TERBILANG','TOTAL',Yii::app()->format->formatCurrency($total,$row1['symbol'])));
        
        $this->pdf->setFont('Arial','',10);
        $this->pdf->setY($this->pdf->getY()+3);
        $this->pdf->setaligns(array('L','L','L','L','L'));
        $this->pdf->setwidths(array(80,30,30,30,35));
        $this->pdf->coldetailalign = array('L','L','C','R','R');
        $this->pdf->row(array('# '.strtoupper($this->to_word($total + ($total*$row1['taxvalue']/100))).' RUPIAH#','Dicatat Oleh,','Hormat Kami','',''));
        $this->pdf->row(array('','','','PPN 10%',Yii::app()->format->formatCurrency($total*$row1['taxvalue']/100,$row1['symbol'])));
        $this->pdf->setFont('Arial','B',10);
        $this->pdf->row(array('','','','NETTO ',Yii::app()->format->formatCurrency($total + ($total*$row1['taxvalue']/100),$row1['symbol'])));
        $this->pdf->setFont('Arial','',9);
        $this->pdf->SetY(-10);
        $this->pdf->text(5,$this->pdf->getY(),'Catatan: Pembayaran dengan cek/ Giro dianggap lunas apabila telah disahkan');
        $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select invoicedate,invoiceno,giheaderid,headernote,recordstatus
				from invoice a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.invoiceid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('invoicedate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('invoiceno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('giheaderid'))->setCellValueByColumnAndRow(3, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(4, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['invoicedate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['invoiceno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['giheaderid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(4, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="invoice.xlsx"');
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