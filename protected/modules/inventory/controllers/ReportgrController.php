<?php
class ReportgrController extends Controller {
  public $menuname = 'reportgr';
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
    $grheaderid      = isset($_POST['grheaderid']) ? $_POST['grheaderid'] : '';
    $grdate          = isset($_POST['grdate']) ? $_POST['grdate'] : '';
    $grno            = isset($_POST['grno']) ? $_POST['grno'] : '';
    $pono      = isset($_POST['pono']) ? $_POST['pono'] : '';
    $companyname 			 = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $supplier        = isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grheaderid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $com          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('grheader t')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('addressbook k', 'k.addressbookid = j.addressbookid')->leftjoin('company l', 'l.companyid = j.companyid')->where("
    (coalesce(grheaderid,'') like :grheaderid) and
    (coalesce(grdate,'') like :grdate) and
			(coalesce(grno,'') like :grno) and 
			(coalesce(k.fullname,'') like :supplier) and 
			(coalesce(l.companyname) like :companyname) and
			(coalesce(t.headernote,'') like :headernote) and
			(coalesce(j.pono,'') like :pono) and j.companyid in (".getUserObjectValues('company').")", array(
      ':grheaderid' => '%' . $grheaderid . '%',
      ':grdate' => '%' . $grdate . '%',
      ':grno' => '%' . $grno . '%',
			':companyname' => '%' . $companyname . '%',
      ':supplier' => '%' . $supplier . '%',
      ':headernote' => '%' . $headernote . '%',
      ':pono' => '%' . $pono . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,j.pono,k.fullname,l.companyid,l.companyname')->from('grheader t')->leftjoin('poheader j', 'j.poheaderid = t.poheaderid')->leftjoin('addressbook k', 'k.addressbookid = j.addressbookid')->leftjoin('company l', 'l.companyid = j.companyid')->where("
    (coalesce(grheaderid,'') like :grheaderid) and
    (coalesce(grdate,'') like :grdate) and
			(coalesce(grno,'') like :grno) and 
			(coalesce(k.fullname,'') like :supplier) and 
			(coalesce(l.companyname) like :companyname) and
			(coalesce(t.headernote,'') like :headernote) and
			(coalesce(j.pono,'') like :pono) and j.companyid in (".getUserObjectValues('company').")", array(
      ':grheaderid' => '%' . $grheaderid . '%',
      ':grdate' => '%' . $grdate . '%',
      ':grno' => '%' . $grno . '%',
			':companyname' => '%' . $companyname . '%',
      ':supplier' => '%' . $supplier . '%',
      ':headernote' => '%' . $headernote . '%',
      ':pono' => '%' . $pono . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'grheaderid' => $data['grheaderid'],
        'grno' => $data['grno'],
        'grdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['grdate'])),
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgrheader' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('grdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('podetail d', 'd.podetailid = t.podetailid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->where('grheaderid = :grheaderid', array(
      ':grheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,c.description as slocdesc,e.description,a.barcode')->from('grdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('podetail d', 'd.podetailid = t.podetailid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->where('grheaderid = :grheaderid', array(
      ':grheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'grdetailid' => $data['grdetailid'],
        'grheaderid' => $data['grheaderid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'barcode' => $data['barcode'],
        'description' => $data['description'],
        'itemtext' => $data['itemtext']
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
    $sql = "select b.companyid,a.grno,a.grdate,a.grheaderid,b.pono,c.fullname,a.recordstatus,a.headernote
      from grheader a
      left join poheader b on b.poheaderid = a.poheaderid
      left join addressbook c on c.addressbookid = b.addressbookid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where a.grheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('grheader');
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->Rect(10, 60, 190, 25);
      $this->pdf->setFont('Arial', 'B', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['grno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['grdate'])));
      $this->pdf->text(15, $this->pdf->gety() + 15, 'PO No ');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['pono']);
      $this->pdf->text(15, $this->pdf->gety() + 20, 'Vendor ');
      $this->pdf->text(50, $this->pdf->gety() + 20, ': ' . $row['fullname']);
      $sql1        = "select b.productname, a.qty, c.uomcode,d.description
        from grdetail a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
        left join sloc d on d.slocid = a.slocid
        where grheaderid = " . $row['grheaderid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        100,
        20,
        20,
        40
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Gudang'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
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
          $row1['description']
        ));
      }
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        50,
        140
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
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
        'Note:',
        $row['headernote']
      ));
      $this->pdf->Image('images/ttdttb.jpg', 5, $this->pdf->gety() + 25, 200);
      $this->pdf->isheader = false;
      $this->pdf->AddPage('L', array(
        100,
        200
      ));
      $this->pdf->AddPage('P');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownload();
    $sql = "select grdate,grno,poheaderid,headernote,recordstatus
				from grheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . " where a.grheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, getCatalog('grdate'))->setCellValueByColumnAndRow(1, 1, getCatalog('grno'))->setCellValueByColumnAndRow(2, 1, getCatalog('poheaderid'))->setCellValueByColumnAndRow(3, 1, getCatalog('headernote'))->setCellValueByColumnAndRow(4, 1, getCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['grdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['grno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['poheaderid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(4, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="grheader.xlsx"');
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
