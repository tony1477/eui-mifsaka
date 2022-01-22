<?php
class ReportgireturController extends Controller {
  public $menuname = 'reportgiretur';
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
    $gireturno       = isset($_POST['gireturno']) ? $_POST['gireturno'] : '';
    $gireturdate     = isset($_POST['gireturdate']) ? $_POST['gireturdate'] : '';
    $gino      = isset($_POST['gino']) ? $_POST['gino'] : '';
    $customer      = isset($_POST['customer']) ? $_POST['customer'] : '';
    $gireturid      = isset($_POST['gireturid']) ? $_POST['gireturid'] : '';
    $companyname      = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'gireturid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('giretur t')->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')->leftjoin('company c', 'c.companyid = b.companyid')->
    leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')->where("
    (coalesce(t.gireturid,'') like :gireturid) and
    (coalesce(t.gireturno,'') like :gireturno) and
    (coalesce(t.gireturdate,'') like :gireturdate) and
    (coalesce(a.gino,'') like :gino) and
    (coalesce(d.fullname,'') like :customer) and   
    (coalesce(c.companyname,'') like :companyname) and   
    (coalesce(t.headernote,'') like :headernote) and 
    c.companyid in (".getUserObjectValues('company').")", array(
      ':headernote' => '%' . $headernote . '%',
      ':gireturid' => '%' . $gireturid . '%',
      ':gireturno' => '%' . $gireturno . '%',
      ':gireturdate' => '%' . $gireturdate . '%',
      ':gino' => '%' . $gino . '%',
      ':companyname' => '%' . $companyname . '%',
      ':customer' => '%' . $customer . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.gino,d.fullname as customername,c.companyid,c.companyname')
		->from('giretur t')
		->leftjoin('giheader a', 'a.giheaderid = t.giheaderid')
		->leftjoin('soheader b', 'b.soheaderid = a.soheaderid')
		->leftjoin('company c', 'c.companyid = b.companyid')
		->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')
		->where("
    (coalesce(t.gireturid,'') like :gireturid) and
    (coalesce(t.gireturno,'') like :gireturno) and
    (coalesce(t.gireturdate,'') like :gireturdate) and
    (coalesce(a.gino,'') like :gino) and
    (coalesce(c.companyname,'') like :companyname) and
    (coalesce(d.fullname,'') like :customer) and   
    (coalesce(t.headernote,'') like :headernote) and c.companyid in (".getUserObjectValues('company').")", array(
      ':headernote' => '%' . $headernote . '%',
      ':gireturid' => '%' . $gireturid . '%',
      ':gireturno' => '%' . $gireturno . '%',
      ':gireturdate' => '%' . $gireturdate . '%',
      ':gino' => '%' . $gino . '%',
      ':companyname' => '%' . $companyname . '%',
      ':customer' => '%' . $customer . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'gireturid' => $data['gireturid'],
        'gireturno' => $data['gireturno'],
        'gireturdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['gireturdate'])),
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'customername' => $data['customername'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgiretur' => $data['statusname']
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
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'gireturdetailid';
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
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('gireturid = :gireturid', array(
        ':gireturid' => $id
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('gireturid = :gireturid', array(
        ':gireturid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcodec,c.sloccode,d.description')->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('gireturid = :gireturid', array(
        ':gireturid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,c.description as slocdesc,d.description')->from('gireturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('gireturid = :gireturid', array(
        ':gireturid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'gireturdetailid' => $data['gireturdetailid'],
        'gireturid' => $data['gireturid'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['description'],
        'itemnote' => $data['itemnote']
      );
    }
    $result   = array_merge($result, array(
      'rows' => $row
    ));
    $cmd      = Yii::app()->db->createCommand()->select('sum(t.qty) as totalqty')->from('gireturdetail t')->where('gireturid = :gireturid', array(
      ':gireturid' => $id
    ))->queryRow();
    $footer[] = array(
      'productname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($cmd['totalqty'])
    );
    $result   = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select b.companyid, a.gireturno,a.gireturdate,b.gino ,a.shipto,a.gireturid,a.headernote,a.recordstatus
      from giretur a
      left join giheader b on b.giheaderid = a.giheaderid";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.gireturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('giretur');
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->setFont('Arial', 'B', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['gireturno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['gireturdate'])));
      $this->pdf->text(15, $this->pdf->gety() + 15, 'SO No ');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['gino']);
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
        from gireturdetail a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
        inner join sloc d on d.slocid = a.slocid
				left join storagebin f on f.storagebinid = a.storagebinid
        where gireturid = " . $row['gireturid'] . " group by b.productname order by gireturdetailid";
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
        'L',
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
          Yii::app()->format->formatNumber($row1['vqty']),
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
        from gireturdetail a
        left join productdetail e on e.productdetailid = a.productdetailid
        left join product b on b.productid = e.productid
        left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
        left join sloc d on d.slocid = a.slocid
				left join storagebin f on f.storagebinid = a.storagebinid
        where gireturid = " . $row['gireturid'] . " order by a.gireturdetailid, a.storagebinid, b.productname";
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
        $this->pdf->AddPage('L', array(
          100,
          200
        ));
      }
    }
    $this->pdf->Output();
  }
}