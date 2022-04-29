<?php
class MrpperiodController extends Controller {
  public $menuname = 'mrpperiod';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $product = isset($_POST['product']) ? $_POST['product'] : '';
    $company = isset($_POST['company']) ? $_POST['company'] : '';
    $uom = isset($_POST['uomid']) ? $_POST['uomid'] : '';
    $mrpperiodid = isset($_POST['mrpperiodid']) ? $_POST['mrpperiodid'] : '';
    $perioddate = isset($_POST['perioddate']) ? $_POST['perioddate'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'mrpperiodid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();

    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('mrpperiod t')
		->leftjoin('company a', 'a.companyid = t.companyid')
		->where("(coalesce(t.productname,'') like :productname)
		and (coalesce(t.uomcode,'') like :uomcode)
		and (coalesce(t.perioddate,'') like :perioddate)
		and (coalesce(t.mrpperiodid,'') like :mrpperiodid)
		and (coalesce(a.companycode,'') like :company)
		and t.companyid in (".getUserObjectValues('company').") ", array(
      ':productname' => '%' . $product . '%',
      ':company' => '%' . $company . '%',
      ':mrpperiodid' => '%' . $mrpperiodid . '%',
      ':uomcode' => '%' . $uom . '%',
      ':perioddate' => '%'.$perioddate.'%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyname')
    ->from('mrpperiod t')
		->leftjoin('company a', 'a.companyid = t.companyid')
		->where("(coalesce(t.productname,'') like :productname)
		and (coalesce(t.uomcode,'') like :uomcode)
		and (coalesce(t.perioddate,'') like :perioddate)
		and (coalesce(t.mrpperiodid,'') like :mrpperiodid)
		and (coalesce(a.companycode,'') like :company) 
    and t.companyid in (".getUserObjectValues('company').") ", array(
      ':productname' => '%' . $product . '%',
      ':company' => '%' . $company . '%',
      ':mrpperiodid' => '%' . $mrpperiodid . '%',
      ':uomcode' => '%' . $uom . '%',
      ':perioddate' => '%' . $perioddate . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'mrpperiodid' => $data['mrpperiodid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'perioddate' => $data['perioddate'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        // 'stock' => $data['stock'],
        'minqty' => $data['minqty'],
        'maxqty' => $data['maxqty'],
        'minqtyreal' => $data['minqtyreal'],
        'maxqtyreal' => $data['maxqtyreal']
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
        $sql     = 'call Insertmrpperiod(:vcompanyid,:vperioddate,:vproductid,:vuomid,:vqtyminreal,:vqtymaxreal,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatemrpperiod(:vid,:vcompanyid,:vperioddate,:vproductid,:vuomid,:vqtyminreal,:vqtymaxreal,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['mrpperiodid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['mrpperiodid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vperioddate', date(Yii::app()->params['datetodb'],strtotime($_POST['perioddate'])), PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vqtyminreal', $_POST['minqtyreal'], PDO::PARAM_STR);
      $command->bindvalue(':vqtymaxreal', $_POST['maxqtyreal'], PDO::PARAM_STR);
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
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgemrpperiod(:vid,:vcreatedby)';
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
    $sql = "select a.mrpperiodid,b.productname,concat (c.companycode,'-',c.description)as company,c.companycode, a.minstock,a.reordervalue,a.`maxvalue`,a.leadtime,d.uomcode,(select sum(qty) from productstock x where x.productid = a.productid and x.companyid = a.companyid and x.unitofmeasureid = a.uomid) as stock,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from mrpperiod a
						left join product b on b.productid = a.productid
						left join company c on c.companyid = a.companyid
						left join unitofmeasure d on d.unitofmeasureid = a.uomid
                        where a.mrpperiodid like '%".$_GET['mrpperiodid']."%'
                        ";

    if($_GET['recordstatus'] != '') {
        $sql = $sql. ' and a.recordstatus = '.$_GET['recordstatus'];
    }
    else
    {
        $recordstatus = ' ';
    }
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.mrpperiodid in (" . $_GET['id'] . ")";
    }
    if ($_GET['uom'] !== '') {
      $sql = $sql . " and d.uomcode like '%".$_GET['uom']."%' ";
    }
    if ($_GET['company'] !== '') {
      $sql = $sql . " and c.companycode like '%".$_GET['company']."%' ";
    }
    if ($_GET['product'] !== '') {
      $sql = $sql . " and b.productname like '%".parse_url($_GET['product'],PHP_URL_PATH)."%' ";
    }

    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('mrpperiod');
    $this->pdf->AddPage('P', array(
      350,
      250
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
      'L'
    );
    $this->pdf->colheader = array(
      getCatalog('mrpperiodid'),
      getCatalog('productname'),
      getCatalog('company'),
      getCatalog('minstock'),
      getCatalog('reordervalue'),
      getCatalog('maxvalue'),
      getCatalog('leadtime'),
      getCatalog('uomcode'),
      getCatalog('stock'),
      getCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      10,
      120,
      30,
      20,
      30,
      30,
      25,
      25,
      25,
      20
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
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['mrpperiodid'],
        $row1['productname'],
        $row1['companycode'],
        $row1['minstock'],
        $row1['reordervalue'],
        $row1['maxvalue'],
        $row1['leadtime'],
        $row1['uomcode'],
        $row1['stock'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'mrpperiod';
    parent::actionDownxls();
    $sql = "select a.mrpperiodid,b.productname,concat (c.companycode,'-',c.description)as company,c.companycode, a.minstock,a.reordervalue,a.`maxvalue`,a.leadtime,d.uomcode,(select sum(qty) from productstock x where x.productid = a.productid and x.companyid = a.companyid and x.unitofmeasureid = a.uomid) as stock,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from mrpperiod a
						left join product b on b.productid = a.productid
						left join company c on c.companyid = a.companyid
						left join unitofmeasure d on d.unitofmeasureid = a.uomid
                        where a.mrpperiodid like '%".$_GET['mrpperiodid']."%'
                        ";
    if($_GET['recordstatus'] != '') {
        $sql = $sql. ' and a.recordstatus = '.$_GET['recordstatus'];
    }
    else
    {
        $recordstatus = ' ';
    }
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.mrpperiodid in (" . $_GET['id'] . ")";
    }
    if ($_GET['uom'] !== '') {
      $sql = $sql . " and d.uomcode like '%".$_GET['uom']."%' ";
    }
    if ($_GET['company'] !== '') {
      $sql = $sql . " and c.companycode like '%".$_GET['company']."%' ";
    }
    if ($_GET['product'] !== '') {
      $sql = $sql . " and b.productname like '%".parse_url($_GET['product'],PHP_URL_PATH)."%' ";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['mrpperiodid'])->setCellValueByColumnAndRow(1, $i, $row1['productname'])->setCellValueByColumnAndRow(2, $i, $row1['company'])->setCellValueByColumnAndRow(3, $i, $row1['minstock'])->setCellValueByColumnAndRow(4, $i, $row1['reordervalue'])->setCellValueByColumnAndRow(5, $i, $row1['maxvalue'])->setCellValueByColumnAndRow(6, $i, $row1['leadtime'])->setCellValueByColumnAndRow(7, $i, $row1['uomcode'])->setCellValueByColumnAndRow(8, $i, $row1['stock'])->setCellValueByColumnAndRow(9, $i, $row1['recordstatus']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}