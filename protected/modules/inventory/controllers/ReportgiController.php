<?php
class ReportgiController extends Controller {
  public $menuname = 'reportgi';
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
    $giheaderid = isset($_POST['giheaderid']) ? $_POST['giheaderid'] : '';
    $gino       = isset($_POST['gino']) ? $_POST['gino'] : '';
    $sono       = isset($_POST['sono']) ? $_POST['sono'] : '';
    $headernote = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $companyname = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $customer   = isset($_POST['customer']) ? $_POST['customer'] : '';
    $page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows       = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort       = isset($_POST['sort']) ? strval($_POST['sort']) : 'giheaderid';
    $order      = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset     = ($page - 1) * $rows;
    $result     = array();
    $row        = array();
		$connection		= Yii::app()->db;
		$from = '
			from giheader t 
			left join soheader a on a.soheaderid = t.soheaderid 
			left join addressbook b on b.addressbookid = a.addressbookid
			left join company c on c.companyid = a.companyid ';
		$where = "
			where (coalesce(t.giheaderid,'') like '%".$giheaderid."%') and (coalesce(t.gino,'') like '%".$gino."%')  
				and (coalesce(a.sono,'') like '%".$sono."%') and (coalesce(b.fullname,'') like '%".$customer."%') 
				and (coalesce(c.companyname,'') like '%".$companyname."%')
				and (coalesce(t.headernote,'') like '%".$headernote."%')
				and a.companyid in (".getUserObjectValues('company').")";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select t.giheaderid,t.gino,t.gidate,t.soheaderid,a.sono,a.pocustno,b.fullname,a.shipto,t.headernote,
		t.statusname,t.recordstatus,c.companyid,c.companyname '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'gidate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gidate'])),
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'customername' => $data['fullname'],
				'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'shipto' => $data['shipto'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgiheader' => $data['statusname']
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
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'sodetailid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset = ($page - 1) * $rows;
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,d.description,c.description as slocdesc')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcodec,c.sloccode,d.description,c.description as slocdesc')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'gidetailid' => $data['gidetailid'],
        'giheaderid' => $data['giheaderid'],
        'qty' => $data['qty'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['description'],
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select distinct b.companyid, a.gino,a.gidate,b.sono ,a.shipto,a.giheaderid,a.headernote,a.recordstatus
      from giheader a
      left join soheader b on b.soheaderid = a.soheaderid ";
		$giheaderid = filter_input(INPUT_GET,'giheaderid');
		$gino = filter_input(INPUT_GET,'gino');
		$sono = filter_input(INPUT_GET,'sono');
		$sql .= " where coalesce(a.giheaderid,'') like '%".$giheaderid."%' 
			and coalesce(a.gino,'') like '%".$gino."%'
			and coalesce(a.sono,'') like '%".$sono."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.giheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('giheader');
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->Rect(10, 60, 190, 20);
      $this->pdf->setFont('Arial', 'B', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['gino']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gidate'])));
      $this->pdf->text(15, $this->pdf->gety() + 15, 'SO No ');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['sono']);
      $this->pdf->sety($this->pdf->gety() + 20);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->colheader = array(
        'Item',
        'Description'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Ship To',
        $row['shipto']
      ));
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,d.description
        from gidetail a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
        inner join sloc d on d.slocid = a.slocid
				left join storagebin f on f.storagebinid = a.storagebinid
        where giheaderid = " . $row['giheaderid'] . " group by b.productname order by gidetailid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 6);
      $this->pdf->setwidths(array(
        10,
        80,
        30,
        20,
        50,
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Gudang'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 6);
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'L',
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
          Yii::app()->format->formatCurrency($row1['vqty']),
          $row1['uomcode'],
          $row1['description']
        ));
      }
      $this->pdf->Image('images/ttdsj.jpg', 5, $this->pdf->gety() + 25, 200);
      $this->pdf->isheader = false;
      $this->pdf->AddPage('L', array(
        100,
        200
      ));
      $sql1        = "select b.productname, a.qty, c.uomcode,d.description,f.description as rak
        from gidetail a
        left join productdetail e on e.productdetailid = a.productdetailid
        left join product b on b.productid = e.productid
        left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
        left join sloc d on d.slocid = a.slocid
				left join storagebin f on f.storagebinid = a.storagebinid
        where giheaderid = " . $row['giheaderid'] . " order by a.gidetailid, a.storagebinid, b.productname";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $i           = 0;
      $oldproduct  = '';
      $this->pdf->setFont('Arial', 'B', 18);
      foreach ($dataReader1 as $row1) {
        if ($oldproduct <> $row1['productname']) {
          $i += 1;
          $oldproduct = $row1['productname'];
        }
        $this->pdf->Rect(10, $this->pdf->gety() + 15, 180, $this->pdf->gety() + 30);
        $this->pdf->text(15, $this->pdf->gety() + 10, 'TAG ' . $i);
        $this->pdf->text(15, $this->pdf->gety() + 25, 'Product: ' . $row1['productname']);
        $this->pdf->text(15, $this->pdf->gety() + 35, 'Qty: ' . $row1['qty']);
        $this->pdf->text(15, $this->pdf->gety() + 45, 'Rak: ' . $row1['rak']);
      }
      $this->pdf->AddPage('P');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select gino,gidate,soheaderid,shipto,headernote,recordstatus
				from giheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.giheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, getCatalog('gino'))->setCellValueByColumnAndRow(1, 1, getCatalog('gidate'))->setCellValueByColumnAndRow(2, 1, getCatalog('soheaderid'))->setCellValueByColumnAndRow(3, 1, getCatalog('shipto'))->setCellValueByColumnAndRow(4, 1, getCatalog('headernote'))->setCellValueByColumnAndRow(5, 1, getCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['gino'])->setCellValueByColumnAndRow(1, $i + 1, $row1['gidate'])->setCellValueByColumnAndRow(2, $i + 1, $row1['soheaderid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['shipto'])->setCellValueByColumnAndRow(4, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(5, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="giheader.xlsx"');
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