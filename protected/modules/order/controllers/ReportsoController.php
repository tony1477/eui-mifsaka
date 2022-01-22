<?php
class ReportsoController extends Controller {
  public $menuname = 'reportso';
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
      echo $this->actionSearchDetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdisc() {
    if (isset($_GET['grid']))
      echo $this->actionSearchDisc();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $soheaderid   = isset($_POST['soheaderid']) ? $_POST['soheaderid'] : '';
    $sono        	= isset($_POST['sono']) ? $_POST['sono'] : '';
    $customer  		= isset($_POST['customer']) ? $_POST['customer'] : '';
		$companyname  = isset($_POST['companyname']) ? $_POST['companyname'] : '';
		$pocustno     = isset($_POST['pocustno']) ? $_POST['pocustno'] : '';
		$pono     		= isset($_POST['pono']) ? $_POST['pono'] : '';
		$headernote   = isset($_POST['headernote']) ? $_POST['headernote'] : '';
		$page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 't.soheaderid';
		$order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$connection		= Yii::app()->db;
		$sqlplant = getUserObjectValues('plantall');
		if ($sqlplant == '1'){$plant="";}else{$plant=" and (t.soheaderid in (select distinct a1.soheaderid from sodetail a1 join sloc b1 on b1.slocid=a1.slocid where b1.plantid in (".getUserObjectValues('plant').")) or t.soheaderid in (select yy.soheaderid from sodetail xx right join soheader yy on xx.soheaderid = yy.soheaderid where yy.companyid in(".getUserObjectValues('company').") and xx.slocid is null)) ";}
		$from = '
			from soheader t 
			left join company a on a.companyid = t.companyid 
			left join addressbook b on b.addressbookid = t.addressbookid 
			left join tax c on c.taxid = t.taxid 
			left join employee d on d.employeeid = t.employeeid 
			left join paymentmethod e on e.paymentmethodid = t.paymentmethodid
            left join materialtype f on f.materialtypeid = t.materialtypeid 
			left join packages g on g.packageid = t.packageid';
		$where ="
			where (coalesce(soheaderid,'') like '%".$soheaderid."%') and (coalesce(sono,'') like '%".$sono."%') and (coalesce(b.fullname) like '%".$customer."%') 
				and (coalesce(a.companyname,'') like '%".$companyname."%')
				and (coalesce(t.pocustno,'') like '%".$pocustno."%') and (coalesce(t.pono,'') like '%".$pono."%') and (coalesce(t.headernote,'') like '%".$headernote."%')
				{$plant}
				and b.iscustomer = 1 and t.companyid in (".getUserObjectValues('company').")";
		$sqldep = new CDbCacheDependency('select max(soheaderid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = '
			select t.soheaderid,t.sono,t.sodate,t.companyid,a.companyname,t.addressbookid,b.fullname,t.top,t.creditlimit,t.totalbefdisc,
				t.totalaftdisc,t.statusname,t.taxid,c.taxcode, t.pocustno,t.employeeid,d.fullname as employeename,t.currentlimit,
				t.paymentmethodid,e.paycode,b.overdue,t.shipto,t.billto,t.headernote,t.poheaderid,t.pono, 
				case when (((t.currentlimit + t.totalaftdisc + t.pendinganso) > t.creditlimit) and (b.top > 0)) then 1  
				when (((t.currentlimit + t.totalaftdisc + t.pendinganso) <= t.creditlimit) and (b.top > 0)) then 2  
				when (((t.currentlimit + t.totalaftdisc + t.pendinganso) > t.creditlimit) and (b.top <= 0)) then 3 
				else 4	end as warna,
				(
		select case when sum(z.qty) > sum(z.giqty) then 1 else 0 end 
		from sodetail z 
		where z.soheaderid = t.soheaderid 		
		) as wso,
				t.pendinganso,
				t.recordstatus,t.isdisplay,t.sotype,case when sotype = 1 then "Jenis Material" when sotype = 2 then "PAKET" end as sotypename,
                t.materialtypeid,t.packageid,f.description, g.packagename,t.createddate,t.updatedate
	'.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'soheaderid' => $data['soheaderid'],
        'companyname' => $data['companyname'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'pocustno' => $data['pocustno'],
        'isdisplay' => $data['isdisplay'],
        'sotype' => $data['sotype'],
        'sotypename' => $data['sotypename'],
        'materialtypeid' => $data['materialtypeid'],
        'description' => $data['description'],
        'packageid' => $data['packageid'],
        'packagename' => $data['packagename'],
        'customername' => $data['fullname'],
        'currentlimit' => Yii::app()->format->formatCurrency($data['currentlimit']),
        'creditlimit' => Yii::app()->format->formatCurrency($data['creditlimit']),
        'warna' => $data['warna'],
        'employeename' => $data['employeename'],
        'paycode' => $data['paycode'],
        'sono' => $data['sono'],
        'sodate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['sodate'])),
        'headernote' => $data['headernote'],
        'shipto' => $data['shipto'],
        'billto' => $data['billto'],
        'top' => $data['top'],
        'wso' => $data['wso'],
        'pendinganso' => Yii::app()->format->formatCurrency($data['pendinganso']),
        'totalbefdisc' => Yii::app()->format->formatCurrency($data['totalbefdisc']),
        'totalaftdisc' => Yii::app()->format->formatCurrency($data['totalaftdisc']),
        'taxcode' => $data['taxcode'],
        'recordstatus' => $data['recordstatus'],
        'recordstatussoheader' => $data['statusname'],
        'createddate' => $data['createddate'],
        'updatedate' => $data['updatedate']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'sodetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('sodetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('soheaderid = :soheaderid', array(
      ':soheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.currencyname,d.sloccode,
		case when t.qty > t.giqty then 1 else 0 end as wgi,
				(select ifnull(sum(ifnull(z.qty,0)),0) from productstock z where z.productid = t.productid and z.unitofmeasureid = t.unitofmeasureid and z.slocid = t.slocid) as qtystock')->from('sodetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('soheaderid = :soheaderid', array(
      ':soheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qty'] > $data['giqty']) {
				$wgi = 1;
			} else {
				$wgi = 0;
			}
			if ($data['qty'] > $data['qtystock']) {
				$wstock = 1;
			} else {
				$wstock = 0;
			}
      $row[] = array(
        'sodetailid' => $data['sodetailid'],
        'soheaderid' => $data['soheaderid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
        'giqty' => Yii::app()->format->formatNumber($data['giqty']),
        'total' => Yii::app()->format->formatCurrency(($data['price'] * $data['qty'] * $data['currencyrate'])),
        'currencyrate' => Yii::app()->format->formatCurrency($data['currencyrate']),
        'currencyid' => $data['currencyid'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'currencyname' => $data['currencyname'],
        'wgi' => $wgi,
        'wstock' => $wstock,
        'delvdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])),
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(t.price * t.qty) as total
		from sodetail t 
		where soheaderid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryRow();
    $footer[] = array(
      'productname' => 'Total',
      'total' => Yii::app()->format->formatCurrency($cmd['total'])
    );
    $sql = "select a.uomcode,sum(t.qty) as qty, sum(t.giqty) as giqty
		from sodetail t 
		join unitofmeasure a on a.unitofmeasureid = t.unitofmeasureid 
		where soheaderid = ".$id." group by a.uomcode";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
    $footer[] = array(
      'productname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($data['qty']),
      'giqty' => Yii::app()->format->formatNumber($data['giqty']),
      'uomcode' => $data['uomcode'],
    );
		}
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchdisc() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'sodiscid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset = ($page - 1) * $rows;
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    $footer = array();
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('sodisc t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('sodisc t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select()->from('sodisc t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select()->from('sodisc t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'sodiscid' => $data['sodiscid'],
        'soheaderid' => $data['soheaderid'],
        'discvalue' => Yii::app()->format->formatNumber($data['discvalue']),
        'discvalue1' => '-'
      );
    }
    $cmd      = Yii::app()->db->createCommand()->selectdistinct('(sum(t.price * t.qty) - gettotalamountdiscso(t.soheaderid)) as amountbefdisc,gettotalamountdiscso(t.soheaderid) as amountafterdisc')->from('sodetail t')->where('soheaderid = :soheaderid', array(
      ':soheaderid' => $id
    ))->queryRow();
    $footer[] = array(
      'sodetailid' => 'Diskon',
      'discvalue' => Yii::app()->format->formatNumber($cmd['amountbefdisc']),
      'discvalue1' => '-'
    );
    $footer[] = array(
      'sodetailid' => 'Setelah Diskon',
      'discvalue' => Yii::app()->format->formatNumber($cmd['amountafterdisc']),
      'discvalue1' => Yii::app()->format->formatNumber($cmd['amountafterdisc'])
    );
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionComplete() {
  	header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Completereportso(:vid,:vcreatedby)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.companyid, a.soheaderid,a.sono, b.fullname as customername, a.sodate, c.paymentname, e.taxcode, e.taxvalue,
			a.addressbookid, a.headernote,a.recordstatus,a.shipto,a.billto,d.fullname as salesname
      from soheader a
      join addressbook b on b.addressbookid = a.addressbookid
		  join employee d on d.employeeid = a.employeeid
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		  join tax e on e.taxid = a.taxid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.soheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = 'Sales Order';
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
    $this->pdf->SetFont('Tahoma');
    foreach ($dataReader as $row) {
      if ($row['addressbookid'] > 0) {
        $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.lat, a.lng
					from address a
					left join addresstype b on b.addresstypeid = a.addresstypeid
					left join city c on c.cityid = a.cityid
					where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $phone;
        foreach ($dataReader1 as $row1) {
          $phone = $row1['phoneno'];
        }
      }
      $this->pdf->SetFontSize(8);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        20,
        100,
        30,
        60
      ));
      $this->pdf->row(array(
        'Customer',
        '',
        'Sales Order No',
        ' : ' . $row['sono']
      ));
      $this->pdf->row(array(
        'Name',
        ' : ' . $row['customername'].'   ('.$row1['lat'].','.$row1['lng'].')',
        'SO Date',
        ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['sodate']))
      ));
      $this->pdf->row(array(
        'Phone',
        ' : ' . $phone,
        'Sales',
        ' : ' . $row['salesname']
      ));
      $this->pdf->row(array(
        'Address',
        ' : ' . $row['shipto'],
        'Payment',
        ' : ' . $row['paymentname']
      ));
      $sql1        = "select a.soheaderid,c.uomcode,a.qty,a.price * a.currencyrate as price,(qty * price * currencyrate) as total,(e.taxvalue * qty * price * currencyrate / 100) as ppn,b.productname,
			d.symbol,d.i18n,a.itemnote
			from sodetail a
			left join soheader f on f.soheaderid = a.soheaderid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
			left join currency d on d.currencyid = a.currencyid
			left join tax e on e.taxid = f.taxid
			where a.soheaderid = " . $row['soheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $total       = 0;
      $totalqty    = 0;
      $this->pdf->sety($this->pdf->gety() + 0);
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
        15,
        70,
        40,
        20,
        35
      ));
      $this->pdf->colheader = array(
        'Qty',
        'Units',
        'Description',
        'Item Note',
        'Unit Price',
        'Total'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'R',
        'C',
        'L',
        'L',
        'R',
        'R',
        'R'
      );
      foreach ($dataReader1 as $row1) {
        $this->pdf->row(array(
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['productname'],
          $row1['itemnote'],
          Yii::app()->format->formatCurrency($row1['price']),
          Yii::app()->format->formatCurrency($row1['total'])
        ));
        $total    = $row1['total'] + $total;
        $totalqty = $row1['qty'] + $totalqty;
      }
      $this->pdf->row(array(
        Yii::app()->format->formatNumber($totalqty),
        '',
        'Total',
        '',
        Yii::app()->format->formatCurrency($total)
      ));
      $sql1        = "select a.discvalue
			from sodisc a
			where a.soheaderid = " . $row['soheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $discvalue   = '';
      foreach ($dataReader1 as $row1) {
        if ($discvalue == '') {
          $discvalue = Yii::app()->format->formatNumber($row1['discvalue']);
        } else {
          $discvalue = $discvalue . ' + ' . Yii::app()->format->formatNumber($row1['discvalue']);
        }
      }
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Diskon (%)',
        $discvalue
      ));
      $cmd                 = Yii::app()->db->createCommand()->selectdistinct('gettotalamountdiscso(t.soheaderid) as amountafterdisc')->from('sodetail t')->where('soheaderid = :soheaderid', array(
        ':soheaderid' => $row['soheaderid']
      ))->queryRow();
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Harga Diskon',
        Yii::app()->format->formatNumber($total - $cmd['amountafterdisc'])
      ));
      $bilangan = explode(".", $cmd['amountafterdisc']);
      $this->pdf->row(array(
        'Harga Sesudah Diskon',
        Yii::app()->format->formatCurrency($cmd['amountafterdisc']) . ' (' . eja($bilangan[0]) . ')'
      ));
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        35,
        155,
        155,
        155,
        155,
        155
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none',
        'none',
        'none',
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Ship To',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Bill To',
        $row['billto']
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $this->pdf->checkNewPage(10);
      $this->pdf->sety($this->pdf->gety() + 5);
      $this->pdf->text(10, $this->pdf->gety(), 'Pembuat');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownPDF2() {
    parent::actionDownload();
    $sql = "select a.soheaderid, a.sodate,a.sono, a.statusname
            from soheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.soheaderid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Product Yang Tidak ada di Gudang";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['soheaderid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['sodate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO FPB ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['sono']);
     
     
      $sql1= "select b.soheaderid, a.productid, b.sodate,b.sono, d.sloccode,b.statusname,c.productname
        from sodetail a
        left join soheader b on b.soheaderid = a.soheaderid
        left join sloc d on d.slocid = a.slocid
        left join product c ON c.productid = a.productid 
        where b.soheaderid = ".$row['soheaderid']." and a.slocid not in (select x.slocid from productplant x where x.productid = a.productid) ";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          20,
          25,
          25,
          80,
          25
        ));
        $this->pdf->colheader = array(
          'No',
          'ID',
          'Tanggal SO',
          'Gudang',
          'Nama Barang',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'L',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['soheaderid'], $row1['sodate'],$row1['sloccode'],$row1['productname'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }
  public function actionDownPDF3() {
    parent::actionDownload();
    $sql = "select a.soheaderid, a.sodate,a.sono, a.statusname
from soheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.soheaderid in (" . $_GET['id'] . ")";
    }
    $command=$this->connection->createCommand($sql);
    $dataReader=$command->queryAll();
    $this->pdf->title = "List Satuan Yang Tidak ada di Gudang ";
    $this->pdf->AddPage('P', array(220,140));
    $this->pdf->AliasNBPages();
         $i           = 0;
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 10);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'ID ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': '.$row['soheaderid']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' .date(Yii::app()->params['dateviewfromdb'], strtotime($row['sodate'])));
      $this->pdf->text(135, $this->pdf->gety() + 5, 'NO FPB ');
      $this->pdf->text(170, $this->pdf->gety() + 5, ': '.$row['sono']);
     
     
      $sql1= "select b.soheaderid, a.productid, b.sodate,b.sono,b.statusname,c.productname,d.uomcode, e.sloccode
              from sodetail a
              join soheader b on b.soheaderid = a.soheaderid
              join product c ON c.productid = a.productid 
                            join sloc e on e.slocid = a.slocid
              join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
              where b.soheaderid = ".$row['soheaderid']." and a.slocid not in (select x.slocid from productplant x where x.productid = a.productid and x.unitofissue = a.unitofmeasureid) ";
        
      $command1= $this->connection->createCommand($sql1);
      $dataReader1= $command1->queryAll();
           $this->pdf->sety($this->pdf->gety() + 15);
        $this->pdf->colalign = array(
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
          
        );
        $this->pdf->setFont('Arial', 'B', 8);
        $this->pdf->setwidths(array(
          7,
          30,
          30,
          30,
          70,
          30
          
        ));
        $this->pdf->colheader = array(
          'No',
          'Tgl FPB',
          'Gudang',
          'Satuan',
          'Nama Barang',
          'Status'
        );
        $this->pdf->RowHeader();
        $this->pdf->setFont('Arial', '', 8);
        $this->pdf->coldetailalign = array(
          'C',
          'C',
          'C',
          'C',
          'C',
          'C'
        );
      foreach ($dataReader1 as $row1) {
     
        $i= $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['sodate'],$row1['sloccode'],$row1['uomcode'],$row1['productname'], $row1['statusname']
        ));
      }
    }
    $this->pdf->Output();
  }
}