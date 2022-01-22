<?php
class ProductstockController extends Controller {
  public $menuname = 'productstock';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexhome() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchhome();
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
    $product   = isset($_POST['product']) ? $_POST['product'] : '';
    $sloc        	= isset($_POST['sloc']) ? $_POST['sloc'] : '';
    $storagebin  		= isset($_POST['storagebin']) ? $_POST['storagebin'] : '';
		$unitofmeasure     = isset($_POST['unitofmeasure']) ? $_POST['unitofmeasure'] : '';
		$page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 't.productstockid';
		$order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$query = "
			from productstock t 
			inner join sloc c on c.slocid = t.slocid 
			inner join groupmenuauth f on f.menuvalueid = t.slocid 
			inner join menuauth g on g.menuauthid = f.menuauthid 
			inner join usergroup d on d.groupaccessid = f.groupaccessid
			inner join useraccess e on e.useraccessid = d.useraccessid 
			where (t.productname like '%".$product."%') and (t.sloccode like '%".$sloc."%') and (t.storagedesc like '%".$storagebin."%') 
				and (t.uomcode like '%".$unitofmeasure."%') 
				and upper(e.username)=upper('" . Yii::app()->user->name . "') 
				and upper(g.menuobject) = upper('sloc')
		";
		$sqlcount = ' select count(distinct t.productstockid) as total '.$query;
		$sql = '
			select distinct t.productstockid, t.productid, t.productname, t.slocid, t.sloccode, t.storagebinid, t.storagedesc,
				t.qty, t.unitofmeasureid, t.uomcode, t.qtyinprogress,c.description as slocdesc,
				ifnull((select z.minstock from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as minstock,
				ifnull((select z.reordervalue from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as orderstock,
ifnull((select z.maxvalue from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as maxstock '.$query;
    $result['total'] = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$cmd = Yii::app()->db->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productstockid' => $data['productstockid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'sloccode' => $data['sloccode'],
        'slocdesc' => $data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['storagedesc'],
        'qtyshow' => Yii::app()->format->formatNumber($data['qty']),
        'qty' => $data['qty'],
        'qtyinprogress' => $data['qtyinprogress'],
        'minstock' => $data['minstock'],
        'orderstock' => $data['orderstock'],
        'maxstock' => $data['maxstock'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'qtyipshow' => Yii::app()->format->formatNumber($data['qtyinprogress'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	public function searchhome()
  {
    header("Content-Type: application/json");
		$product   = isset($_POST['product']) ? $_POST['product'] : '';
    $sloc        	= isset($_POST['sloc']) ? $_POST['sloc'] : '';
    $storagebin  		= isset($_POST['storagebin']) ? $_POST['storagebin'] : '';
		$unitofmeasure     = isset($_POST['unitofmeasure']) ? $_POST['unitofmeasure'] : '';
		$page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 't.productstockid';
		$order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$query = "
			from productstock t 
			inner join sloc c on c.slocid = t.slocid 
			inner join plant h on h.plantid=c.plantid 
			inner join groupmenuauth f on f.menuvalueid = h.companyid 
			inner join menuauth g on g.menuauthid = f.menuauthid 
			inner join usergroup d on d.groupaccessid = f.groupaccessid
			inner join useraccess e on e.useraccessid = d.useraccessid 
			where (t.productname like '%".$product."%') and (t.sloccode like '%".$sloc."%') and (t.storagedesc like '%".$storagebin."%') 
				and (t.uomcode like '%".$unitofmeasure."%') 
				and upper(e.username)=upper('" . Yii::app()->user->name . "') 
				and upper(g.menuobject) = upper('company')
		";
		$sqlcount = ' select count(distinct t.productstockid) as total '.$query;
		$sql = '
			select distinct t.productstockid, t.productid, t.productname, t.slocid, t.sloccode, t.storagebinid, t.storagedesc,
				t.qty, t.unitofmeasureid, t.uomcode, t.qtyinprogress,c.description as slocdesc,
				ifnull((select z.minstock from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as minstock,
				ifnull((select z.reordervalue from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as orderstock,
ifnull((select z.maxvalue from mrp z where z.productid = t.productid and z.slocid = t.slocid limit 1),0) as maxstock '.$query;
    $result['total'] = Yii::app()->db->createCommand($sqlcount)->queryScalar();
		$cmd = Yii::app()->db->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productstockid' => $data['productstockid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'sloccode' => $data['sloccode'],
        'slocdesc' => $data['slocdesc'],
        'storagebinid' => $data['storagebinid'],
        'description' => $data['storagedesc'],
        //'qtyshow' => Yii::app()->format->formatNumber($data['qty']),
        'qtyshow' => $data['qty'],
        'qty' => $data['qty'],
        'qtyinprogress' => $data['qtyinprogress'],
        'minstock' => $data['minstock'],
        'orderstock' => $data['orderstock'],
        'maxstock' => $data['maxstock'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        //'qtyipshow' => Yii::app()->format->formatNumber($data['qtyinprogress'])
        'qtyipshow' => $data['qtyinprogress']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productstockdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $footer          = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productstockdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('t.productstockid = :productstockid', array(
      ':productstockid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,d.sloccode')->from('productstockdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('t.productstockid = :productstockid', array(
      ':productstockid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'referenceno' => $data['referenceno'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'transdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['transdate']))
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
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
        $sql     = 'call Insertproductstock(:vproductid,:vslocid,:vstoragebinid,:vqty,:vunitofmeasureid,:vqtyinprogress,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateproductstock(:vid,:vproductid,:vslocid,:vstoragebinid,:vqty,:vunitofmeasureid,:vqtyinprogress,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['productstockid'], PDO::PARAM_STR);
      }
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vunitofmeasureid', $_POST['unitofmeasureid'], PDO::PARAM_STR);
      $command->bindvalue(':vqtyinprogress', $_POST['qtyinprogress'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      $this->DeleteLock($this->menuname, $_POST['productstockid']);
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
        $sql     = 'call Purgeproductstock(:vid,:vcreatedby)';
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
    $sql = "select b.productname,c.sloccode,c.description,qty,d.uomcode,e.description as rak,qtyinprogress
						from productstock a
						join product b on b.productid = a.productid
						join sloc c on c.slocid = a.slocid
						join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
						join storagebin e on e.storagebinid = a.storagebinid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productstockid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('productstock');
    $this->pdf->AddPage('P', array(
      450,
      250
    ));
    $this->pdf->setFont('Arial', 'B', 10);
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      getCatalog('productname'),
      getCatalog('sloc'),
      getCatalog('storagebin'),
      getCatalog('qty'),
      getCatalog('uom'),
      getCatalog('qtyinprogress')
    );
    $this->pdf->setwidths(array(
      200,
      80,
      80,
      20,
      20,
      30
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial', '', 10);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['productname'],
        $row1['sloccode'] . '-' . $row1['description'],
        $row1['rak'],
        Yii::app()->format->formatNumber($row1['qty']),
        $row1['uomcode'],
        Yii::app()->format->formatNumber($row1['qtyinprogress'])
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'productstock';
    parent::actionDownxls();
    $sql = "select a.productstockid,b.productname,c.sloccode as sloc,c.description,a.qty,d.uomcode,e.description as storagebin,a.qtyinprogress
						from productstock a
						join product b on b.productid = a.productid
						join sloc c on c.slocid = a.slocid
						join unitofmeasure d on d.unitofmeasureid = a.unitofmeasureid
						join storagebin e on e.storagebinid = a.storagebinid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productstockid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['productstockid'])->setCellValueByColumnAndRow(1, $i, $row1['productname'])->setCellValueByColumnAndRow(2, $i, $row1['sloc'])->setCellValueByColumnAndRow(3, $i, $row1['uomcode'])->setCellValueByColumnAndRow(4, $i, $row1['storagebin'])->setCellValueByColumnAndRow(5, $i, $row1['qty'])->setCellValueByColumnAndRow(6, $i, $row1['qtyinprogress']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}