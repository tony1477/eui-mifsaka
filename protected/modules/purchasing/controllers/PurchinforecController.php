<?php
class PurchinforecController extends Controller {
  public $menuname = 'purchinforec';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertpurchinforec(:vaddressbookid,:vproductid,:vdeliverytime,:vpurchasinggroupid,:vunderdelvtol,:voverdelvtol,:vprice,:vcurrencyid,:vbiddate,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatepurchinforec(:vid,:vaddressbookid,:vproductid,:vdeliverytime,:vpurchasinggroupid,:vunderdelvtol,:voverdelvtol,:vprice,:vcurrencyid,:vbiddate,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['purchinforecid'], PDO::PARAM_STR);
      }
      $command->bindvalue(':vaddressbookid', $_POST['addressbookid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vdeliverytime', $_POST['deliverytime'], PDO::PARAM_STR);
      $command->bindvalue(':vpurchasinggroupid', $_POST['purchasinggroupid'], PDO::PARAM_STR);
      $command->bindvalue(':vunderdelvtol', $_POST['underdelvtol'], PDO::PARAM_STR);
      $command->bindvalue(':voverdelvtol', $_POST['overdelvtol'], PDO::PARAM_STR);
      $command->bindvalue(':vprice', $_POST['price'], PDO::PARAM_STR);
      $command->bindvalue(':vcurrencyid', $_POST['currencyid'], PDO::PARAM_STR);
      $command->bindvalue(':vbiddate', date(Yii::app()->params['datetodb'], strtotime($_POST['biddate'])), PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $_POST['recordstatus'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgepurchinforec(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
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
  public function search() {
    header("Content-Type: application/json");
    $purchinforecid    = isset($_POST['purchinforecid']) ? $_POST['purchinforecid'] : '';
    $supplier     = isset($_POST['supplier']) ? $_POST['supplier'] : '';
    $productname         = isset($_POST['productname']) ? $_POST['productname'] : '';
    $startdate         = isset($_POST['startdate']) ? date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])) : ''; 
    $enddate           = isset($_POST['enddate']) ? date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])) : '';
    $page              = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows              = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort              = isset($_POST['sort']) ? strval($_POST['sort']) : 'purchinforecid';
    $order             = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset            = ($page - 1) * $rows;
    $result            = array();
    $row               = array();
		if($startdate != ''){ 
			$date = " and (t.biddate between '".$startdate."' and '".$enddate."')";
		} else {
			$date = '';
		}
    $cmd               = Yii::app()->db->createCommand()->select('count(1) as total')->from('purchinforec t')
		->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
		->leftjoin('product b', 'b.productid = t.productid')
		->leftjoin('purchasinggroup c', 'c.purchasinggroupid = t.purchasinggroupid')
		->leftjoin('currency d', 'd.currencyid = t.currencyid')
		->where("((a.fullname like :supplier) and 
			(b.productname like :productname) and 
			(t.purchinforecid like :purchinforecid)".$date.")", array(
      ':supplier' => '%' . $supplier . '%',
      ':productname' => '%' . $productname . '%',
      ':purchinforecid' => '%' . $purchinforecid . '%'
      
    ))->queryScalar();
    $result['total']   = $cmd;
    $cmd               = Yii::app()->db->createCommand()->select('t.*,a.fullname,b.productname,c.purchasinggroupcode,d.currencyname')->from('purchinforec t')
		->leftjoin('addressbook a', 'a.addressbookid = t.addressbookid')
		->leftjoin('product b', 'b.productid = t.productid')
		->leftjoin('purchasinggroup c', 'c.purchasinggroupid = t.purchasinggroupid')
		->leftjoin('currency d', 'd.currencyid = t.currencyid')
		->where("((a.fullname like :supplier) and 
			(b.productname like :productname) and 
			(t.purchinforecid like :purchinforecid)".$date.")", 
			array(':supplier' => '%' . $supplier . '%',
      ':productname' => '%' . $productname . '%',
      ':purchinforecid' => '%' . $purchinforecid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'purchinforecid' => $data['purchinforecid'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'deliverytime' => $data['deliverytime'],
        'purchasinggroupid' => $data['purchasinggroupid'],
        'purchasinggroupcode' => $data['purchasinggroupcode'],
        'underdelvtol' => $data['underdelvtol'],
        'overdelvtol' => $data['overdelvtol'],
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'biddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['biddate']))
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select addressbookid,productid,deliverytime,purchasinggroupid,underdelvtol,overdelvtol,price,currencyid,biddate,recordstatus
				from purchinforec a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.purchinforecid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('purchinforec');
    $this->pdf->AddPage('P');
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
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('addressbookid'),
      GetCatalog('productid'),
      GetCatalog('deliverytime'),
      GetCatalog('purchasinggroupid'),
      GetCatalog('underdelvtol'),
      GetCatalog('overdelvtol'),
      GetCatalog('price'),
      GetCatalog('currencyid'),
      GetCatalog('biddate'),
      GetCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      40,
      40,
      40,
      40,
      40,
      40,
      40,
      40,
      40,
      40
    ));
    $this->pdf->Rowheader();
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
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['addressbookid'],
        $row1['productid'],
        $row1['deliverytime'],
        $row1['purchasinggroupid'],
        $row1['underdelvtol'],
        $row1['overdelvtol'],
        $row1['price'],
        $row1['currencyid'],
        $row1['biddate'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownXls();
    $sql = "select addressbookid,productid,deliverytime,purchasinggroupid,underdelvtol,overdelvtol,price,currencyid,biddate,recordstatus
				from purchinforec a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.purchinforecid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('productid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('deliverytime'))->setCellValueByColumnAndRow(3, 1, GetCatalog('purchasinggroupid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('underdelvtol'))->setCellValueByColumnAndRow(5, 1, GetCatalog('overdelvtol'))->setCellValueByColumnAndRow(6, 1, GetCatalog('price'))->setCellValueByColumnAndRow(7, 1, GetCatalog('currencyid'))->setCellValueByColumnAndRow(8, 1, GetCatalog('biddate'))->setCellValueByColumnAndRow(9, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['productid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['deliverytime'])->setCellValueByColumnAndRow(3, $i + 1, $row1['purchasinggroupid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['underdelvtol'])->setCellValueByColumnAndRow(5, $i + 1, $row1['overdelvtol'])->setCellValueByColumnAndRow(6, $i + 1, $row1['price'])->setCellValueByColumnAndRow(7, $i + 1, $row1['currencyid'])->setCellValueByColumnAndRow(8, $i + 1, $row1['biddate'])->setCellValueByColumnAndRow(9, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    $this->getFooterXls($this->phpExcel);
  }
}