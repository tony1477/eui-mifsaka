<?php
class ReportprodoutController extends Controller {
  public $menuname = 'reportprodout';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexfg() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetailfg();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $productoutputid	= isset($_POST['productoutputid']) ? $_POST['productoutputid'] : '';
    $productoutputno	= isset($_POST['productoutputno']) ? $_POST['productoutputno'] : '';
    $productoutputdate	= isset($_POST['productoutputdate']) ? $_POST['productoutputdate'] : '';
    $productplanno		= isset($_POST['productplanno']) ? $_POST['productplanno'] : '';
    $company				= isset($_POST['company']) ? $_POST['company'] : '';
    $description			= isset($_POST['description']) ? $_POST['description'] : '';
    $sono							= isset($_POST['sono']) ? $_POST['sono'] : '';
    $customer					= isset($_POST['customer']) ? $_POST['customer'] : '';
    $foreman					= isset($_POST['foreman']) ? $_POST['foreman'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productoutputid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		$connection		= Yii::app()->db;
		$from = "
			from productoutput t 
			left join productplan a on a.productplanid = t.productplanid 
			left join soheader b on b.soheaderid = a.soheaderid 
			left join addressbook c on c.addressbookid = b.addressbookid 
			left join employee d on d.employeeid = t.employeeid ";
		$where = "
			where ((t.productoutputid like '%".$productoutputid."%') 
				and (coalesce(t.productoutputno,'') like '%".$productoutputno."%') 
				and (coalesce(t.productoutputdate,'') like '%".$productoutputdate."%') 
				and (coalesce(t.productplanno,'') like '%".$productplanno."%')
				and (coalesce(b.sono,'') like '%".$sono."%')
				and (coalesce(c.fullname,'') like '%".$customer."%')
				and (coalesce(d.fullname,'') like '%".$foreman."%')
				and (coalesce(t.description,'') like '%".$description."%')
				and (coalesce(t.companyname,'') like '%".$company."%')
				)
				and t.companyid in (".getUserObjectValues('company').") ";
		$sqldep = new CDbCacheDependency('select max(productoutputid) '.$from.' '.$where);
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = ' 
			select t.productoutputid,t.productplanid,t.productplanno,t.productoutputno,t.productoutputdate,t.description,t.companyname,t.employeeid,ifnull(d.fullname,"-") as foreman,
			t.statusname,t.recordstatus,t.companyid,b.sono,b.soheaderid,c.fullname,c.addressbookid '.$from.' '.$where;
		$result['total'] = $connection->cache(1000,$sqldep)->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->cache(1000,$sqldep)->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productoutputid' => $data['productoutputid'],
        'productplanid' => $data['productplanid'],
        'productplanno' => $data['productplanno'],
        'productoutputno' => $data['productoutputno'],
        'productoutputdate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['productoutputdate'])),
        'description' => $data['description'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
				'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
				'addressbookid' => $data['addressbookid'],
        'employeeid' => $data['employeeid'],
        'fullname' => $data['fullname'],
        'foreman' => $data['foreman'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusproductoutput' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdetailfg() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productoutputfg t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('t.productoutputid = :productoutputid', array(
      ':productoutputid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,c.description as slocdesc,d.description as rak,
		getstock(t.productid,t.uomid,t.slocid) as stock,
		getminstockmrp(t.productid,t.uomid,t.slocid) as minstock')->from('productoutputfg t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('t.productoutputid = :productoutputid', array(
      ':productoutputid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
			if ($data['minstock'] > $data['stock']) {
				$wstock = 1;
			} else {
				$wstock = 0;
			}
      $row[] = array(
        'productoutputfgid' => $data['productoutputfgid'],
        'productoutputid' => $data['productoutputid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qtyoutput' => Yii::app()->format->formatNumber($data['qtyoutput']),
        'stock' => Yii::app()->format->formatNumber($data['stock']),
				'wstock' => $wstock,
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'outputdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['outputdate'])),
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'rak' => $data['rak'],
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionSearchdetail() {
    header("Content-Type: application/json");
    $id                = 0;
    $productoutputfgid = '';
    if (isset($_POST['productoutputfgid'])) {
      $productoutputfgid = $_POST['productoutputfgid'];
    }
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } else if (isset($_POST['id'])) {
      $id = $_POST['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productoutputdetail t')->leftjoin('productoutputfg c', 'c.productoutputfgid = t.productoutputfgid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('c.productoutputid = :productoutputid and c.productoutputfgid like :productoutputfgid', array(
      ':productoutputid' => $id,
      ':productoutputfgid' => $productoutputfgid     
		))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->selectdistinct('t.*,a.productname,b.uomcode,
					(select sloccode from sloc zz where zz.slocid = t.fromslocid) as fromsloccode,
			(select description from sloc zz where zz.slocid = t.fromslocid) as fromslocdesc,
			(select sloccode from sloc zz where zz.slocid = t.toslocid) as tosloccode,
			(select description from sloc zz where zz.slocid = t.toslocid) as toslocdesc,
			d.description as rak,
			getstock(t.productid,t.uomid,t.fromslocid) as fromslocstock,
			getstock(t.productid,t.uomid,t.toslocid) as toslocstock,
			getminstockmrp(t.productid,t.uomid,t.fromslocid) as minfromstock,
			getminstockmrp(t.productid,t.uomid,t.toslocid) as mintostock')->from('productoutputdetail t')->leftjoin('productoutputfg c', 'c.productoutputfgid = t.productoutputfgid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('c.productoutputid = :productoutputid and c.productoutputfgid like :productoutputfgid', array(
      ':productoutputid' => $id,
      ':productoutputfgid' => $productoutputfgid
    ))->queryAll();
    foreach ($cmd as $data) {
			if ($data['minfromstock'] > $data['fromslocstock']) {
				$wfromstock = 1;
			} else {
				$wfromstock = 0;
			}
			if ($data['mintostock'] > $data['toslocstock']) {
				$wtostock = 1;
			} else {
				$wtostock = 0;
			}
      $row[] = array(
        'productoutputdetailid' => $data['productoutputdetailid'],
        'productoutputfgid' => $data['productoutputfgid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'fromslocid' => $data['fromslocid'],
        'fromsloccode' => $data['fromsloccode'].' - '.$data['fromslocdesc'],
        'fromslocstock' => Yii::app()->format->formatNumber($data['fromslocstock']),
        'wfromstock' => $wfromstock,
        'wtostock' => $wtostock,
        'toslocid' => $data['toslocid'],
        'tosloccode' => $data['tosloccode'].' - '.$data['toslocdesc'],
        'toslocstock' => Yii::app()->format->formatNumber($data['toslocstock']),
        'storagebinid' => $data['storagebinid'],
        'rak' => $data['rak'],
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select a.*
      from productoutput a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productoutputid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('productoutput');
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->Rect(10, 60, 190, 15);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No Plan ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['productoutputno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl Plan ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productoutputdate'])));
      $sql1        = "select b.productname, a.qtyoutput as qty, c.uomcode, a.description,d.sloccode
        from productoutputfg a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.uomid
				inner join sloc d on d.slocid = a.slocid
        where productoutputid = " . $row['productoutputid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 20, 'FG');
      $this->pdf->sety($this->pdf->gety() + 25);
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
        60,
        20,
        15,
        30,
        55
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['sloccode'],
          $row1['description']
        ));
      }
      $this->pdf->setFont('Arial', '', 10);
      $sql1        = "select b.productname, a.qty, c.uomcode, a.description,
				(select sloccode from sloc d where d.slocid = a.fromslocid) as fromsloccode,
				(select sloccode from sloc d where d.slocid = a.toslocid) as tosloccode			
        from productoutputdetail a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.uomid
        where productoutputid = " . $row['productoutputid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 10, 'RM');
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
      $this->pdf->setwidths(array(
        10,
        60,
        20,
        15,
        25,
        25,
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang Asal',
        'Gudang Tujuan',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['fromsloccode'],
          $row1['tosloccode'],
          $row1['description']
        ));
      }
      $this->pdf->checkPagebreak(40);
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 30, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 30, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 50, '____________ ');
      $this->pdf->text(150, $this->pdf->gety() + 50, '____________');
      $this->pdf->AddPage($this->pdf->CurOrientation);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'productoutput';
    parent::actionDownxls();
    $sql = "select a.*,b.productplanno,b.productplandate
            from productoutput a 
	        join productplan b on b.productplanid = a.productplanid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productoutputid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 5;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(1, 3, 'No Output')
          ->setCellValueByColumnAndRow(2, 3, ': ' . $row['productoutputno'])
          ->setCellValueByColumnAndRow(1, 4, 'Tgl Output')
          ->setCellValueByColumnAndRow(2, 4, ': ' . $row['productplandate'])
          ->setCellValueByColumnAndRow(4, 3, 'No Plan')
          ->setCellValueByColumnAndRow(5, 3, ': ' . $row['productplanno']) 
          ->setCellValueByColumnAndRow(4, 4, 'Tgl Plan')
          ->setCellValueByColumnAndRow(5, 4, ': ' . $row['productplandate']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $line, 'No')
          ->setCellValueByColumnAndRow(1, $line, 'Items')
          ->setCellValueByColumnAndRow(3, $line, 'Qty')
          ->setCellValueByColumnAndRow(4, $line, 'Unit')
          ->setCellValueByColumnAndRow(5, $line, 'Gudang')
          ->setCellValueByColumnAndRow(6, $line, 'Rak');
      $line++;
      $sql1        = "select b.productname, a.qtyoutput as qty, c.uomcode, a.description,
			concat(d.sloccode,'-',d.description) as sloccode, 
			e.description as rak
            from productoutputfg a
                inner join product b on b.productid = a.productid
                inner join unitofmeasure c on c.unitofmeasureid = a.uomid
				inner join sloc d on d.slocid = a.slocid
				inner join storagebin e on e.storagebinid = a.storagebinid
        where productoutputid = " . $row['productoutputid'];
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(0, $line, $i += 1)
            ->setCellValueByColumnAndRow(1, $line, $row1['productname'])
            ->setCellValueByColumnAndRow(3, $line, $row1['qty'])
            ->setCellValueByColumnAndRow(4, $line, $row1['uomcode'])
            ->setCellValueByColumnAndRow(5, $line, $row1['sloccode'])
            ->setCellValueByColumnAndRow(6, $line, $row1['rak']);
       
$sql2        = "SELECT DISTINCT t.*,a.productname,b.uomcode,
					(select sloccode from sloc zz where zz.slocid = t.fromslocid) as fromsloccode,
			(select description from sloc zz where zz.slocid = t.fromslocid) as fromslocdesc,
			(select sloccode from sloc zz where zz.slocid = t.toslocid) as tosloccode,
			(select description from sloc zz where zz.slocid = t.toslocid) as toslocdesc,
			d.description as rak,
			getstock(t.productid,t.uomid,t.fromslocid) as fromslocstock,
			getstock(t.productid,t.uomid,t.toslocid) as toslocstock,
			getminstockmrp(t.productid,t.uomid,t.fromslocid) as minfromstock,
			getminstockmrp(t.productid,t.uomid,t.toslocid) as mintostock
FROM productoutputdetail t
LEFT JOIN productoutputfg c ON c.productoutputfgid = t.productoutputfgid
LEFT JOIN product a ON a.productid = t.productid
LEFT JOIN unitofmeasure b ON b.unitofmeasureid = t.uomid
LEFT JOIN storagebin d ON d.storagebinid = t.storagebinid 

 where t.productoutputid = " . $row['productoutputid'] ;
      $dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
      $c           = 0;
      $line++; 
            $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(1, $line, 'Material/Service - FG');
      $line++;  
          $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(1, $line, 'No')
          ->setCellValueByColumnAndRow(2, $line, 'Items')
          ->setCellValueByColumnAndRow(3, $line, 'Qty')
          ->setCellValueByColumnAndRow(4, $line, 'Unit')
          ->setCellValueByColumnAndRow(5, $line, 'Gudang Asal')
          ->setCellValueByColumnAndRow(6, $line, 'Stock Gd Asal')
          ->setCellValueByColumnAndRow(7, $line, 'Stock Gd Tujuan')
          ->setCellValueByColumnAndRow(8, $line, 'Rak');
      $line++;
      foreach ($dataReader2 as $row2) {
        $line++;
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $line, $c += 1)
            ->setCellValueByColumnAndRow(2, $line, $row2['productname'])
            ->setCellValueByColumnAndRow(3, $line, $row2['qty'])
            ->setCellValueByColumnAndRow(4, $line, $row2['uomcode'])
            ->setCellValueByColumnAndRow(5, $line, $row2['fromsloccode'])
            ->setCellValueByColumnAndRow(6, $line, $row2['fromslocstock'])
            ->setCellValueByColumnAndRow(7, $line, $row2['toslocstock'])
            ->setCellValueByColumnAndRow(8, $line, $row2['rak']);
        $line++;
      }
      }
     
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)
      ->setCellValueByColumnAndRow(1, $line, 'Approved By')
      ->setCellValueByColumnAndRow(4, $line, 'Proposed By');
      
    $line += 4;
    $this->phpExcel->setActiveSheetIndex(0)    
      ->setCellValueByColumnAndRow(1,$line, '____________ ')
      ->setCellValueByColumnAndRow(4, $line, '____________ ');
    }
    $this->getFooterXLS($this->phpExcel);
  }
}