<?php
class RepinvarController extends Controller {
  public $menuname = 'repinvar';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $invoiceid     	 = isset($_POST['invoiceid']) ? $_POST['invoiceid'] : '';
    $invoicedate     = isset($_POST['invoicedate']) ? $_POST['invoicedate'] : '';
    $invoiceno       = isset($_POST['invoiceno']) ? $_POST['invoiceno'] : '';
    $gino      			 = isset($_POST['gino']) ? $_POST['gino'] : '';
    $companyname     = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $customer      	 = isset($_POST['customer']) ? $_POST['customer'] : '';
    $sono      			 = isset($_POST['sono']) ? $_POST['sono'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'invoiceid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
	
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('invoice t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company d', 'd.companyid = b.companyid')->leftjoin('addressbook c', 'c.addressbookid = b.addressbookid')->leftjoin('currency e', 'e.currencyid = t.currencyid')->
		where("((coalesce(gino,'') like :gino) and 
		(coalesce(invoiceno,'') like :invoiceno) and 
		(coalesce(invoiceid,'') like :invoiceid) and 
		(coalesce(b.sono,'') like :sono) and 
		(coalesce(t.invoicedate,'') like :invoicedate) and 
		(coalesce(fullname,'') like :fullname) and 
		(coalesce(a.headernote,'') like :headernote) and 
		(coalesce(d.companyname,'') like :companyname))
						and d.companyid in (".getUserObjectValues('company').")", array(
      ':gino' => '%' . $gino . '%',
      ':invoiceid' => '%' . $invoiceid . '%',
      ':invoiceno' => '%' . $invoiceno . '%',
      ':sono' => '%' . $sono . '%',
      ':companyname' => '%' . $companyname . '%',
      ':invoicedate' => '%' . $invoicedate . '%',
      ':fullname' => '%' . $customer . '%',
      ':headernote' => '%' . $headernote . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()
		->select('t.*,a.gino,b.sono,c.fullname,d.companyname,e.currencyname,t.currencyrate,(t.amount-t.payamount) as saldo,
		case when t.amount > t.payamount then 1
		when t.amount = t.payamount then 2
		end as warna
		')
		->from('invoice t')
		->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')
		->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')
		->leftjoin('company d', 'd.companyid = b.companyid')
		->leftjoin('addressbook c', 'c.addressbookid = b.addressbookid')
		->leftjoin('currency e', 'e.currencyid = t.currencyid')
		->where("((coalesce(gino,'') like :gino) and 
		(coalesce(invoiceid,'') like :invoiceid) and 
		(coalesce(invoiceno,'') like :invoiceno) and 
		(coalesce(b.sono,'') like :sono) and 
		(coalesce(t.invoicedate,'') like :invoicedate) and 
		(coalesce(fullname,'') like :fullname) and 
		(coalesce(a.headernote,'') like :headernote) and 
		(coalesce(d.companyname,'') like :companyname))
						and d.companyid in (".getUserObjectValues('company').")", array(
      ':gino' => '%' . $gino . '%',
      ':invoiceid' => '%' . $invoiceid . '%',
      ':invoiceno' => '%' . $invoiceno . '%',
      ':sono' => '%' . $sono . '%',
      ':companyname' => '%' . $companyname . '%',
      ':invoicedate' => '%' . $invoicedate . '%',
      ':fullname' => '%' . $customer . '%',
      ':headernote' => '%' . $headernote . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'invoiceid' => $data['invoiceid'],
        'invoicedate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['invoicedate'])),
        'invoiceno' => $data['invoiceno'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => $data['currencyrate'],
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'sono' => $data['sono'],
        'fullname' => $data['fullname'],
        'companyname' => $data['companyname'],
        'amount' => Yii::app()->format->formatNumber($data['amount']),
        'saldo' => Yii::app()->format->formatNumber($data['saldo']),
        'payamount' => Yii::app()->format->formatNumber($data['payamount']),
        'headernote' => $data['headernote'],
				'warna'=>$data['warna'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchDetail() {
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->leftjoin('storagebin c', 'c.storagebinid = t.storagebinid')->leftjoin('sodetail e', 'e.sodetailid = t.sodetailid  and e.productid = t.productid')->leftjoin('currency f', 'f.currencyid = e.currencyid')->where('giheaderid = (select z.giheaderid from invoice z where z.invoiceid = :giheaderid)', array(
      ':giheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,d.sloccode,d.description as slocdesc,c.description,f.currencyname')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->leftjoin('storagebin c', 'c.storagebinid = t.storagebinid')->leftjoin('sodetail e', 'e.sodetailid = t.sodetailid and e.productid = t.productid')->leftjoin('currency f', 'f.currencyid = e.currencyid')->where('giheaderid = (select z.giheaderid from invoice z where z.invoiceid = :giheaderid)', array(
      ':giheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['description'],
        'currencyname' => $data['currencyname'],
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select a.uomcode,sum(qty) as credit 
		from gidetail t 
		join unitofmeasure a on a.unitofmeasureid = t.unitofmeasureid
		where giheaderid = ".$id." group by a.uomcode";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
			$footer[] = array(
				'productname' => 'Total',
				'qty' => Yii::app()->format->formatNumber($data['credit']),
				'uomcode' => $data['uomcode']
			);
		}
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select f.companyid,a.amount,g.symbol,currencyrate,a.giheaderid,invoiceid,invoiceno,f.sono,d.fullname as customer,a.invoicedate,a.headernote, taxvalue,a.recordstatus,
	   f.shipto as addressname,
	   j.cityname,
		 a.recordstatus,date_add(a.invoicedate, INTERVAL e.paydays day) as duedate,b.gino,f.sono,f.soheaderid,h.fullname as sales,i.bankacc1,i.bankacc2,i.bankacc3
		from invoice a 
		left join giheader b on b.giheaderid = a.giheaderid
		left join soheader f on f.soheaderid = b.soheaderid
		left join tax c on c.taxid = f.taxid 
		left join currency g on g.currencyid = a.currencyid
		left join addressbook d on d.addressbookid = f.addressbookid
		left join paymentmethod e on e.paymentmethodid = f.paymentmethodid
		left join employee h on h.employeeid = f.employeeid
		left join company i on i.companyid = f.companyid
		left join city j on j.cityid = i.cityid ";
		$invoiceid = filter_input(INPUT_GET,'invoiceid');
		$invoicedate = filter_input(INPUT_GET,'invoicedate');
		$invoiceno = filter_input(INPUT_GET,'invoiceno');
		$gino = filter_input(INPUT_GET,'gino');
		$companyname = filter_input(INPUT_GET,'companyname');
		$customer = filter_input(INPUT_GET,'customer');
		$headernote = filter_input(INPUT_GET,'headernote');
		$sql .= " where coalesce(a.invoiceid,'') like '%".$invoiceid."%' 
			and coalesce(a.invoicedate,'') like '%".$invoicedate."%'
			and coalesce(a.invoiceno,'') like '%".$invoiceno."%'
			and coalesce(b.gino,'') like '%".$gino."%'
			and coalesce(i.companyname,'') like '%".$companyname."%'
			and coalesce(d.fullname,'') like '%".$customer."%'
			and coalesce(a.headernote,'') like '%".$headernote."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.invoiceid in (" . $_GET['id'] . ")";
    }
    $sql        = $sql . " order by invoiceid";
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Faktur Penitipan Barang';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AddFont('tahoma', '', 'tahoma.php');
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('tahoma');
    $sodisc      = '';
    $sql1        = 'select ifnull(discvalue,0) as discvalue from sodisc z where z.soheaderid = ' . $row['soheaderid'];
    $command1    = $this->connection->createCommand($sql1);
    $dataReader1 = $command1->queryAll();
    foreach ($dataReader1 as $row1) {
      if ($sodisc == '') {
        $sodisc = Yii::app()->format->formatNumber($row1['discvalue']);
      } else {
        $sodisc = $sodisc . '+' . Yii::app()->format->formatNumber($row1['discvalue']);
      }
    }
    if ($sodisc == '') {
      $sodisc = '0';
    }
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(9);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        70,
        20,
        10,
        10,
        70
      ));
      $this->pdf->row(array(
        'No',
        ' : ' . $row['invoiceno'],
        '',
        '',
        '',
        $row['cityname'] . ', ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['invoicedate']))
      ));
      $this->pdf->row(array(
        'Sales',
        ' : ' . $row['sales'],
        '',
        '',
        '',
        'Kepada Yth, '
      ));
      $this->pdf->row(array(
        'No. SO ',
        ' : ' . $row['sono'],
        '',
        '',
        '',
        $row['customer']
      ));
      $this->pdf->row(array(
        'T.O.P. ',
        ($row['isdisplay']==1) ? ' : LANGSUNG BAYAR SAAT TERJUAL' : ' : ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['duedate'])),
        '',
        '',
        '',
        $row['addressname']
      ));
      $sql1        = "select * from (select a.sodetailid,d.productname,sum(a.qty) as qty,c.uomcode,f.price,b.symbol,a.itemnote,
	    (price * sum(a.qty) * ifnull(e.taxvalue,0)/100) as taxvalue
        from gidetail a
				inner join sodetail f on f.sodetailid = a.sodetailid
				inner join soheader g on g.soheaderid = f.soheaderid
		inner join product d on d.productid = a.productid
		inner join currency b on b.currencyid = f.currencyid
		inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
		left join tax e on e.taxid = g.taxid
        where a.giheaderid = '" . $row['giheaderid'] . "' group by d.productname,a.sodetailid order by a.sodetailid
		) zz order by zz.sodetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->SetY($this->pdf->gety() + 3);
      $this->pdf->setFontSize(9);
      $this->pdf->colalign = array(
        'L',
        'L',
        'C',
        'C',
        'C',
        'C',
        'L',
        'L'
      );
      $this->pdf->setwidths(array(
        10,
        95,
        20,
        20,
        25,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Price',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R',
        'R'
      );
      $i                         = 0;
      $total                     = 0;
      $b                         = '';
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $b = $row1['symbol'];
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['price'], $row1['symbol']),
          Yii::app()->format->formatCurrency(($row1['price'] * $row1['qty']), $row1['symbol'])
        ));
        $total += ($row1['price'] * $row1['qty']);
      }
      $this->pdf->setaligns(array(
        'L',
        'R',
        'L',
        'R',
        'C',
        'R',
        'R',
        'R'
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Nominal',
        Yii::app()->format->formatCurrency($total, $b)
      ));
      $this->pdf->row(array(
        '',
        'Disc ' . $sodisc . ' (%) ',
        '',
        '',
        'Diskon',
        Yii::app()->format->formatCurrency($total - $row['amount'], $b)
      ));
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Netto',
        Yii::app()->format->formatCurrency($row['amount'], $b)
      ));
      $bilangan                  = explode(".", $row['amount']);
      $this->pdf->iscustomborder = true;
      $this->pdf->setbordercell(array(
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        ''
      ));
      $this->pdf->colalign = array(
        'C'
      );
      $this->pdf->setwidths(array(
        150
      ));
      $this->pdf->coldetailalign = array(
        'L'
      );
      $this->pdf->row(array(
        'Terbilang : ' . eja($bilangan[0])
      ));
      $this->pdf->row(array(
        'NOTE : ' . $row['headernote']
      ));
      $this->pdf->checkNewPage(20);
      $this->pdf->text(25, $this->pdf->gety() + 5, 'Approved By');
      $this->pdf->text(170, $this->pdf->gety() + 5, 'Proposed By');
      $this->pdf->text(25, $this->pdf->gety() + 25, '_____________ ');
      $this->pdf->text(170, $this->pdf->gety() + 25, '_____________');
      $this->pdf->text(10, $this->pdf->gety() + 30, 'Catatan:');
      $this->pdf->text(25, $this->pdf->gety() + 30, '- Pembayaran dengan Cek/Giro dianggap lunas apabila telah dicairkan');
      if ($row['bankacc1'] !== null ){
      $this->pdf->text(25, $this->pdf->gety() + 35, '- Transfer Bank ke:');
      $this->pdf->text(55, $this->pdf->gety() + 35, '~ Rekening '.$row['bankacc1']);}
      if ($row['bankacc2'] !== null ){
      $this->pdf->text(55, $this->pdf->gety() + 40, '~ Rekening '.$row['bankacc2']);}
      if ($row['bankacc3'] !== null ){
      $this->pdf->text(55, $this->pdf->gety() + 45, '~ Rekening '.$row['bankacc3']);}
    }
    $this->pdf->Output();
  }
}