<?php
class RepforecastfppController extends Controller {
  public $menuname = 'repforecastfpp';
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
    $forecastfppid    = isset($_POST['forecastfppid']) ? $_POST['forecastfppid'] : '';
    $perioddatey        = isset($_POST['perioddateyear']) ? $_POST['perioddateyear'] : '';
    $perioddatem        = isset($_POST['perioddatemonth']) ? $_POST['perioddatemonth'] : '';
    $docdate          = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $perioddate       = isset($_POST['perioddate']) ? $_POST['perioddate'] : '';
    $companyname          = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $productname       = isset($_POST['productname']) ? $_POST['productname'] : '';
    $sloccode       = isset($_POST['sloccode']) ? $_POST['sloccode'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $recordstatus     = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $collectionname   = isset($_POST['collectionname']) ? $_POST['collectionname'] : '';
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'forecastfppid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
		$connection				= Yii::app()->db;
    $perioddate = '';
    if($perioddatey != '') {
        $perioddate .= "  year(perioddate) = year('{$perioddatey}-01-01') and ";
    }
    if($perioddatem != '') {
        $perioddate .= "  month(perioddate) = month('2020-{$perioddatem}-01') and ";
    }
    if($collectionname != '') {
        $collection .= " b.collectionname like '%".$collectionname."%' and ";
    }
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('forecastfpp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('productcollection b','b.productcollectid = t.productcollectid')
    ->where("{$perioddate} (a.companyname like :companyname) and {$collection} -- (b.collectionname like :collectionname) and
            t.companyid in (".getUserObjectValues('company').")", array(
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
        //':collectionname' => '%' . $collectionname . '%',
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.collectionname')
    ->from('forecastfpp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('productcollection b','b.productcollectid = t.productcollectid')
    ->where("{$perioddate}  (a.companyname like :companyname) and {$collection} -- (b.collectionname like :collectionname) and
            t.companyid in (".getUserObjectValues('company').")", array(
        
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
        //':collectionname' => '%' . $collectionname . '%',
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'forecastfppid' => $data['forecastfppid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'perioddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['perioddate'])),
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'productcollectid' => $data['productcollectid'],
        'collectionname' => $data['collectionname'],
        'recordstatus' => $data['recordstatus'],
        'sumpendingpo' => Yii::app()->format->formatCurrency($data['sumpendingpo']),
        'sumpredictpo' => Yii::app()->format->formatCurrency($data['sumpredictpo']),
        'sumtotalpo' => Yii::app()->format->formatCurrency($data['sumtotalpo']),
        'headernote' => $data['headernote'],
        'statusname' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.forecastfppdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('forecastfppdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('forecastfppid = :forecastfppid', array(
      ':forecastfppid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,d.sloccode,d.description')
    	->from('forecastfppdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('forecastfppid = :forecastfppid', array(
      ':forecastfppid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'forecastfppdetid' => $data['forecastfppdetid'],
        'forecastfppid' => $data['forecastfppid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'qtyforecast' => Yii::app()->format->formatNumber($data['qtyforecast']),
        'avg3month' => Yii::app()->format->formatNumber($data['avg3month']),
        'avgperday' => Yii::app()->format->formatNumber($data['avgperday']),
        'qtymax' => Yii::app()->format->formatNumber($data['qtymax']),
        'qtymin' => Yii::app()->format->formatNumber($data['qtymin']),
        'leadtime' => Yii::app()->format->formatNumber($data['leadtime']),
        'pendingpo' => Yii::app()->format->formatNumber($data['pendingpo']),
        'saldoawal' => Yii::app()->format->formatNumber($data['saldoawal']),
        'grpredict' => Yii::app()->format->formatNumber($data['grpredict']),
        'prqty' => Yii::app()->format->formatNumber($data['prqty']),
        'prqtyreal' => Yii::app()->format->formatNumber($data['prqtyreal']),
        'price' => Yii::app()->format->formatNumber($data['price']),
        'povalueout' => Yii::app()->format->formatNumber($data['povalueout']),
        'povalue' => Yii::app()->format->formatNumber($data['povalue']),
        'povaluetot' => Yii::app()->format->formatNumber($data['povaluetot']),
        'qtyshare' => Yii::app()->format->formatNumber($data['qtyshare']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
}
