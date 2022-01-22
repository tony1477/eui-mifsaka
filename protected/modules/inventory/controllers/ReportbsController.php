<?php
class ReportbsController extends Controller {
  public $menuname = 'reportbs';
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
    $bsheaderid      = isset($_POST['bsheaderid']) ? $_POST['bsheaderid'] : '';
    $sloccode          = isset($_POST['sloccode']) ? $_POST['sloccode'] : '';
    $bsdate          = isset($_POST['bsdate']) ? $_POST['bsdate'] : '';
    $bsheaderno      = isset($_POST['bsheaderno']) ? $_POST['bsheaderno'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'bsheaderid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('bsheader t')->leftjoin('sloc a', 'a.slocid = t.slocid')->leftjoin('plant b', 'b.plantid = a.plantid')->
    where("((coalesce(bsheaderid,'') like :bsheaderid) and 
    (coalesce(sloccode,'') like :sloccode) and 
    (coalesce(bsheaderno,'') like :bsheaderno) and 
    (coalesce(headernote,'') like :headernote) and
    (coalesce(bsdate,'') like :bsdate))
		and b.companyid in (".getUserObjectValues('company').")", array(
      ':bsheaderid' => '%' . $bsheaderid . '%',
      ':sloccode' => '%' . $sloccode . '%',
      ':bsheaderno' => '%' . $bsheaderno . '%',
      ':headernote' => '%' . $headernote . '%',
      ':bsdate' => '%' . $bsdate . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.sloccode,a.description as slocdesc')->from('bsheader t')->leftjoin('sloc a', 'a.slocid = t.slocid')->leftjoin('plant b', 'b.plantid = a.plantid')->
    where("((coalesce(bsheaderid,'') like :bsheaderid) and 
    (coalesce(sloccode,'') like :sloccode) and 
    (coalesce(bsheaderno,'') like :bsheaderno) and 
    (coalesce(headernote,'') like :headernote) and
    (coalesce(bsdate,'') like :bsdate))
		and b.companyid in (".getUserObjectValues('company').")", array(
      ':bsheaderid' => '%' . $bsheaderid . '%',
      ':sloccode' => '%' . $sloccode . '%',
      ':bsheaderno' => '%' . $bsheaderno . '%',
      ':headernote' => '%' . $headernote . '%',
      ':bsdate' => '%' . $bsdate . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'bsheaderid' => $data['bsheaderid'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'bsdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['bsdate'])),
        'bsheaderno' => $data['bsheaderno'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusbsheader' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'bsdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('bsdetail t')->leftjoin('bsheader g', 'g.bsheaderid = t.bsheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('ownership c', 'c.ownershipid = t.ownershipid')->leftjoin('materialstatus d', 'd.materialstatusid = t.materialstatusid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->leftjoin('currency f', 'f.currencyid = t.currencyid')->leftjoin('productdetail h', 'h.productid = t.productid and h.unitofmeasureid = t.unitofmeasureid and h.storagebinid = t.storagebinid and h.slocid = g.slocid')->where('t.bsheaderid = :bsheaderid', array(
      ':bsheaderid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.ownershipname,d.materialstatusname,e.description,f.currencyname,ifnull(h.qty,0) as qtystock,ifnull(h.buyprice,0) as buypricestock')->from('bsdetail t')->leftjoin('bsheader g', 'g.bsheaderid = t.bsheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('ownership c', 'c.ownershipid = t.ownershipid')->leftjoin('materialstatus d', 'd.materialstatusid = t.materialstatusid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->leftjoin('currency f', 'f.currencyid = t.currencyid')->leftjoin('productdetail h', 'h.productid = t.productid and h.unitofmeasureid = t.unitofmeasureid and h.storagebinid = t.storagebinid and h.slocid = g.slocid')->where('t.bsheaderid = :bsheaderid', array(
      ':bsheaderid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'bsdetailid' => $data['bsdetailid'],
        'bsheaderid' => $data['bsheaderid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNum($data['qty']),
        'qtystock' => Yii::app()->format->formatNum($data['qtystock']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'ownershipid' => $data['ownershipid'],
        'ownershipname' => $data['ownershipname'],
        'materialstatusid' => $data['materialstatusid'],
        'materialstatusname' => $data['materialstatusname'],
        'storagebinid' => $data['storagebinid'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'buyprice' => Yii::app()->format->formatNum($data['buyprice']),
        'buypricestock' => Yii::app()->format->formatNum($data['buypricestock']),
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'description' => $data['description'],
        'location' => $data['location'],
        'expiredate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['expiredate'])),
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
}