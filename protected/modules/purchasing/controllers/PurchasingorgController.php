<?php
class PurchasingorgController extends Controller {
  public $menuname = 'purchasingorg';
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
        $sql     = 'call Insertpurchasingorg(:vpurchasingorgcode,:vdescription,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatepurchasingorg(:vid,:vpurchasingorgcode,:vdescription,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['purchasingorgid'], PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['purchasingorgid']);
      }
      $command->bindvalue(':vpurchasingorgcode', $_POST['purchasingorgcode'], PDO::PARAM_STR);
      $command->bindvalue(':vdescription', $_POST['description'], PDO::PARAM_STR);
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
        $sql     = 'call Purgepurchasingorg(:vid,:vcreatedby)';
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
    $purchasingorgcode = isset($_POST['purchasingorgcode']) ? $_POST['purchasingorgcode'] : '';
    $description       = isset($_POST['description']) ? $_POST['description'] : '';
    $purchasingorgcode = isset($_GET['q']) ? $_GET['q'] : $purchasingorgcode;
    $description       = isset($_GET['q']) ? $_GET['q'] : $description;
    $page              = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows              = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort              = isset($_POST['sort']) ? strval($_POST['sort']) : 'purchasingorgid';
    $order             = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page              = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows              = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort              = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order             = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset            = ($page - 1) * $rows;
    $result            = array();
    $row               = array();
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('purchasingorg t')->where('(purchasingorgcode like :purchasingorgcode) and (description like :description)', array(
        ':purchasingorgcode' => '%' . $purchasingorgcode . '%',
        ':description' => '%' . $description . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('purchasingorg t')->where('((purchasingorgcode like :purchasingorgcode) or (description like :description)) and recordstatus = 1', array(
        ':purchasingorgcode' => '%' . $purchasingorgcode . '%',
        ':description' => '%' . $description . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('*')->from('purchasingorg t')->where('(purchasingorgcode like :purchasingorgcode) and (description like :description)', array(
        ':purchasingorgcode' => '%' . $purchasingorgcode . '%',
        ':description' => '%' . $description . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('*')->from('purchasingorg t')->where('((purchasingorgcode like :purchasingorgcode) or (description like :description)) and recordstatus = 1', array(
        ':purchasingorgcode' => '%' . $purchasingorgcode . '%',
        ':description' => '%' . $description . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'purchasingorgid' => $data['purchasingorgid'],
        'purchasingorgcode' => $data['purchasingorgcode'],
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select purchasingorgcode,description,recordstatus
				from purchasingorg a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.purchasingorgid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('purchasingorg');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('purchasingorgcode'),
      GetCatalog('description'),
      GetCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      40,
      40,
      40
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['purchasingorgcode'],
        $row1['description'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownXls();
    $sql = "select purchasingorgcode,description,recordstatus
				from purchasingorg a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.purchasingorgid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('purchasingorgcode'))->setCellValueByColumnAndRow(1, 1, GetCatalog('description'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['purchasingorgcode'])->setCellValueByColumnAndRow(1, $i + 1, $row1['description'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
		$this->getFooterXls($this->phpExcel);
  }
}