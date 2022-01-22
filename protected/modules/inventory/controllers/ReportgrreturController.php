<?php
class ReportgrreturController extends Controller {
  public $menuname = 'reportgrretur';
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
    $grreturid       = isset($_POST['grreturid']) ? $_POST['grreturid'] : '';
    $grreturno       = isset($_POST['grreturno']) ? $_POST['grreturno'] : '';
    $grreturdate     = isset($_POST['grreturdate']) ? $_POST['grreturdate'] : '';
    $pono      = isset($_POST['pono']) ? $_POST['pono'] : '';
    $headernote      = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $supplier      = isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $companyname      = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grreturid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('grretur t')->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')->where("
    ((coalesce(grreturdate,'') like :grreturdate) and
					(coalesce(grreturno,'') like :grreturno) and
					(coalesce(t.headernote,'') like :headernote) and
					(coalesce(a.pono,'') like :pono) and 
					(coalesce(b.fullname,'') like :supplier) and
					(coalesce(t.grreturid,'') like :grreturid))
					and a.companyid in (".getUserObjectValues('company').")", array(
      ':grreturdate' => '%' . $grreturdate . '%',
      ':grreturno' => '%' . $grreturno . '%',
      ':headernote' => '%' . $headernote . '%',
      ':pono' => '%' . $pono . '%',
      ':supplier' => '%' . $supplier . '%',
      ':grreturid' => '%' . $grreturid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.pono,b.fullname,c.companyid,c.companyname')
		->from('grretur t')
		->leftjoin('poheader a', 'a.poheaderid = t.poheaderid')
		->leftjoin('addressbook b', 'b.addressbookid = a.addressbookid')
		->leftjoin('company c', 'c.companyid = a.companyid')
		->where("
    ((coalesce(grreturdate,'') like :grreturdate) and
					(coalesce(grreturno,'') like :grreturno) and
					(coalesce(t.headernote,'') like :headernote) and
					(coalesce(a.pono,'') like :pono) and 
					(coalesce(c.companyname,'') like :companyname) and 
					(coalesce(b.fullname,'') like :supplier) and
					(coalesce(t.grreturid,'') like :grreturid))
					and a.companyid in (".getUserObjectValues('company').")", array(
      ':grreturdate' => '%' . $grreturdate . '%',
      ':grreturno' => '%' . $grreturno . '%',
      ':headernote' => '%' . $headernote . '%',
      ':companyname' => '%' . $companyname . '%',
      ':pono' => '%' . $pono . '%',
      ':supplier' => '%' . $supplier . '%',
      ':grreturid' => '%' . $grreturid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'grreturid' => $data['grreturid'],
        'grreturno' => $data['grreturno'],
        'grreturdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['grreturdate'])),
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'fullname' => $data['fullname'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusgrretur' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'grreturdetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('grreturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('podetail d', 'd.podetailid = t.podetailid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->where('grreturid = :grreturid', array(
      ':grreturid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select()->from('grreturdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('podetail d', 'd.podetailid = t.podetailid')->leftjoin('storagebin e', 'e.storagebinid = t.storagebinid')->where('grreturid = :grreturid', array(
      ':grreturid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'grreturdetailid' => $data['grreturdetailid'],
        'grreturid' => $data['grreturid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
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
}
