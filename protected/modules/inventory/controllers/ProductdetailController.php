<?php
class ProductdetailController extends Controller {
  public $menuname = 'productdetail';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $productdetailid  = isset($_POST['productdetailid']) ? $_POST['productdetailid'] : '';
    $materialcode     = isset($_POST['materialcode']) ? $_POST['materialcode'] : '';
    $productname        = isset($_POST['productname']) ? $_POST['productname'] : '';
    $sloccode           = isset($_POST['sloccode']) ? $_POST['sloccode'] : '';
    $uomcode  = isset($_POST['uomcode']) ? $_POST['uomcode'] : '';
    $storagebindesc  = isset($_POST['storagebindesc']) ? $_POST['storagebindesc'] : '';
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'productdetailid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $page             = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows             = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort             = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order            = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
    $cmd              = Yii::app()->db->createCommand()->select('count(1) as total')->from('productdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->leftjoin('currency e', 'e.currencyid = t.currencyid')
			->where('(a.productname like :productname) 
			and (b.uomcode like :uomcode) 
			and (c.sloccode like :sloccode) 
			and (d.description like :storagebindesc)', 
			array(
      ':productname' => '%' . $productname . '%',
      ':uomcode' => '%' . $uomcode . '%',
      ':sloccode' => '%' . $sloccode . '%',
      ':storagebindesc' => '%' . $storagebindesc . '%'
			))->queryScalar();
    $result['total']  = $cmd;
    $cmd              = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,d.description as rak,
			c.description as slocdesc,e.currencyname')->from('productdetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->leftjoin('currency e', 'e.currencyid = t.currencyid')
			->where('(a.productname like :productname) 
			and (b.uomcode like :uomcode) 
			and (c.sloccode like :sloccode) 
			and (d.description like :storagebindesc)', array(
      ':productname' => '%' . $productname . '%',
      ':uomcode' => '%' . $uomcode . '%',
      ':sloccode' => '%' . $sloccode . '%',
      ':storagebindesc' => '%' . $storagebindesc . '%'
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productdetailid' => $data['productdetailid'],
        'materialcode' => $data['materialcode'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'] . ' - ' . $data['slocdesc'],
        'expiredate' => $data['expiredate'],
        'serialno' => $data['serialno'],
        'qty' => $data['qty'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'buydate' => $data['buydate'],
        'buyprice' => Yii::app()->format->formatCurrency($data['buyprice']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'storagebinid' => $data['storagebinid'],
        'rak' => $data['rak'],
        'location' => $data['location'],
        'locationdate' => $data['locationdate'],
        'materialstatusid' => $data['materialstatusid'],
        'ownershipid' => $data['ownershipid'],
        'referenceno' => $data['referenceno'],
        'picproduct' => $data['picproduct'],
        'vrqty' => $data['vrqty'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
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
        $sql     = 'call Insertproductdetail(:vmaterialcode,:vproductid,:vslocid,:vexpiredate,:vserialno,:vqty,:vunitofmeasureid,:vbuydate,:vbuyprice,:vcurrencyid,:vstoragebinid,:vlocation,:vlocationdate,:vmaterialstatusid,:vownershipid,:vgrheaderid,:vgrdetailid,:vreferenceno,:vpicproduct,:vvrqty,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductdetail(:vid,:vmaterialcode,:vproductid,:vslocid,:vexpiredate,:vserialno,:vqty,:vunitofmeasureid,:vbuydate,:vbuyprice,:vcurrencyid,:vstoragebinid,:vlocation,:vlocationdate,:vmaterialstatusid,:vownershipid,:vreferenceno,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productdetailid'], PDO::PARAM_STR);
      }
      $command->bindvalue(':vmaterialcode', $_POST['materialcode'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vexpiredate', date(Yii::app()->params['datetodb'], strtotime($_POST['expiredate'])), PDO::PARAM_STR);
      $command->bindvalue(':vserialno', $_POST['serialno'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vunitofmeasureid', $_POST['unitofmeasureid'], PDO::PARAM_STR);
      $command->bindvalue(':vbuydate', date(Yii::app()->params['datetodb'], strtotime($_POST['buydate'])), PDO::PARAM_STR);
      $command->bindvalue(':vbuyprice', $_POST['buyprice'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':vlocation', $_POST['location'], PDO::PARAM_STR);
      $command->bindvalue(':vlocationdate', $_POST['locationdate'], PDO::PARAM_STR);
      $command->bindvalue(':vmaterialstatusid', $_POST['materialstatusid'], PDO::PARAM_STR);
      $command->bindvalue(':vownershipid', $_POST['ownershipid'], PDO::PARAM_STR);
      $command->bindvalue(':vreferenceno', $_POST['referenceno'], PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $_POST['recordstatus'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      $this->DeleteLock($this->menuname, $_POST['productdetailid']);
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
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
        $sql     = 'call Purgeproductdetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
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
    $sql = "select a.productdetailid,a.materialcode,b.productname,concat (c.sloccode,'-',c.description) as sloc,
						ifnull((a.expiredate),'-')as expiredate,
						ifnull((a.serialno),'-')as serialno,
						a.qty,d.uomcode as unitofmeasure,
						ifnull((a.buydate),'-')as buydate,
						ifnull((a.buyprice),'-')as buyprice,
						ifnull((e.currencyname),'-')as currency,f.description as storagebin,
						ifnull((g.materialstatusname),'-')as materialstatus,
						ifnull((h.ownershipname),'-')as ownership,a.referenceno
						from productdetail a
						left join product b on b.productid = a.productid
						left join sloc c on c.slocid = a.slocid
						left join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
						left join currency e on e.currencyid = a.currencyid
						left join storagebin f on f.storagebinid = a.storagebinid
						left join materialstatus g on g.materialstatusid = a.materialstatusid
						left join ownership h on h.ownershipid = a.ownershipid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productdetailid in (" . $_GET['id'] . ")";
    } else {
      $sql = $sql . "order by productname asc ";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('productdetail');
    $this->pdf->AddPage('P', array(
      550,
      300
    ));
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      getCatalog('productdetailid'),
      getCatalog('materialcode'),
      getCatalog('productname'),
      getCatalog('sloc'),
      getCatalog('expiredate'),
      getCatalog('serialno'),
      getCatalog('qty'),
      getCatalog('unitofmeasure'),
      getCatalog('buydate'),
      getCatalog('buyprice'),
      getCatalog('currency'),
      getCatalog('storagebin'),
      getCatalog('materialstatus'),
      getCatalog('ownership'),
      getCatalog('referenceno')
    );
    $this->pdf->setwidths(array(
      15,
      30,
      125,
      75,
      23,
      20,
      20,
      15,
      23,
      30,
      20,
      40,
      30,
      40,
      25
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial', '', 10);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['productdetailid'],
        $row1['materialcode'],
        $row1['productname'],
        $row1['sloc'],
        $row1['expiredate'],
        $row1['serialno'],
        $row1['qty'],
        $row1['unitofmeasure'],
        $row1['buydate'],
        $row1['buyprice'],
        $row1['currency'],
        $row1['storagebin'],
        $row1['materialstatus'],
        $row1['ownership'],
        $row1['referenceno']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'productdetail';
    parent::actionDownxls();
    $sql = "select a.productdetailid,a.materialcode,b.productname,concat (c.sloccode,'-',c.description) as sloc,
						ifnull((a.expiredate),'-')as expiredate,
						ifnull((a.serialno),'-')as serialno,
						a.qty,d.uomcode as unitofmeasure,
						ifnull((a.buydate),'-')as buydate,
						ifnull((a.buyprice),'-')as buyprice,
						ifnull((e.currencyname),'-')as currency,f.description as storagebin,
						ifnull((g.materialstatusname),'-')as materialstatus,
						ifnull((h.ownershipname),'-')as ownership,a.referenceno
						from productdetail a
						left join product b on b.productid = a.productid
						left join sloc c on c.slocid = a.slocid
						left join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
						left join currency e on e.currencyid = a.currencyid
						left join storagebin f on f.storagebinid = a.storagebinid
						left join materialstatus g on g.materialstatusid = a.materialstatusid
						left join ownership h on h.ownershipid = a.ownershipid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productdetailid in (" . $_GET['id'] . ")";
    } else {
      $sql = $sql . "order by productname asc ";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['productdetailid'])->setCellValueByColumnAndRow(1, $i, $row1['materialcode'])->setCellValueByColumnAndRow(2, $i, $row1['productname'])->setCellValueByColumnAndRow(3, $i, $row1['sloc'])->setCellValueByColumnAndRow(4, $i, $row1['expiredate'])->setCellValueByColumnAndRow(5, $i, $row1['serialno'])->setCellValueByColumnAndRow(6, $i, $row1['qty'])->setCellValueByColumnAndRow(7, $i, $row1['unitofmeasure'])->setCellValueByColumnAndRow(8, $i, $row1['buydate'])->setCellValueByColumnAndRow(9, $i, $row1['buyprice'])->setCellValueByColumnAndRow(10, $i, $row1['currency'])->setCellValueByColumnAndRow(11, $i, $row1['storagebin'])->setCellValueByColumnAndRow(12, $i, $row1['materialstatus'])->setCellValueByColumnAndRow(13, $i, $row1['ownership'])->setCellValueByColumnAndRow(14, $i, $row1['referenceno']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}