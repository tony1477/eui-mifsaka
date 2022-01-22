<?php
class PurchasinggroupController extends Controller {
  public $menuname = 'purchasinggroup';
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
        $sql     = 'call Insertpurchasinggroup(:vpurchasingorgid,:vpurchasinggroupcode,:vdescription,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatepurchasinggroup(:vid,:vpurchasingorgid,:vpurchasinggroupcode,:vdescription,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['purchasinggroupid'], PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['purchasinggroupid']);
      }
      $command->bindvalue(':vpurchasingorgid', $_POST['purchasingorgid'], PDO::PARAM_STR);
      $command->bindvalue(':vpurchasinggroupcode', $_POST['purchasinggroupcode'], PDO::PARAM_STR);
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
        $sql     = 'call Purgepurchasinggroup(:vid,:vcreatedby)';
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
    $purchasingorgcode     = isset($_POST['purchasingorgcode']) ? $_POST['purchasingorgcode'] : '';
    $purchasinggroupcode = isset($_POST['purchasinggroupcode']) ? $_POST['purchasinggroupcode'] : '';
    $description         = isset($_POST['description']) ? $_POST['description'] : '';
    $purchasingorgcode     = isset($_GET['q']) ? $_GET['q'] : $purchasingorgcode;
    $purchasinggroupcode = isset($_GET['q']) ? $_GET['q'] : $purchasinggroupcode;
    $description         = isset($_GET['q']) ? $_GET['q'] : $description;
    $page                = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows                = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort                = isset($_POST['sort']) ? strval($_POST['sort']) : 'purchasinggroupid';
    $order               = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page                = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows                = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort                = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order               = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset              = ($page - 1) * $rows;
    $result              = array();
    $row                 = array();
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('purchasinggroup t')->leftjoin('purchasingorg a', 'a.purchasingorgid = t.purchasingorgid')
			->where('
			(purchasinggroupcode like :purchasinggroupcode) 
			and (purchasingorgcode like :purchasingorgcode) 
			and (t.description like :description)', 
			array(
        ':purchasinggroupcode' => '%' . $purchasinggroupcode . '%',
        ':purchasingorgcode' => '%' . $purchasingorgcode . '%',
        ':description' => '%' . $description . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('purchasinggroup t')->leftjoin('purchasingorg a', 'a.purchasingorgid = t.purchasingorgid')
			->where('
			(purchasinggroupcode like :purchasinggroupcode) 
			or (purchasingorgcode like :purchasingorgcode) 
			or (t.description like :description)', 
			array(
        ':purchasinggroupcode' => '%' . $purchasinggroupcode . '%',
        ':purchasingorgcode' => '%' . $purchasingorgcode . '%',
        ':description' => '%' . $description . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.purchasingorgcode')->from('purchasinggroup t')->leftjoin('purchasingorg a', 'a.purchasingorgid = t.purchasingorgid')
			->where('
			(purchasinggroupcode like :purchasinggroupcode) 
			and (purchasingorgcode like :purchasingorgcode) 
			and (t.description like :description)', 
			array(
        ':purchasinggroupcode' => '%' . $purchasinggroupcode . '%',
        ':purchasingorgcode' => '%' . $purchasingorgcode . '%',
        ':description' => '%' . $description . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.purchasingorgcode')->from('purchasinggroup t')->leftjoin('purchasingorg a', 'a.purchasingorgid = t.purchasingorgid')
			->where('
			(purchasinggroupcode like :purchasinggroupcode) 
			or (purchasingorgcode like :purchasingorgcode) 
			or (t.description like :description)', 
			array(
        ':purchasingorgcode' => '%' . $purchasingorgcode . '%',
        ':purchasinggroupcode' => '%' . $purchasinggroupcode . '%',
        ':description' => '%' . $description . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'purchasinggroupid' => $data['purchasinggroupid'],
        'purchasingorgid' => $data['purchasingorgid'],
        'purchasingorgcode' => $data['purchasingorgcode'],
        'purchasinggroupcode' => $data['purchasinggroupcode'],
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select purchasingorgid,purchasinggroupcode,description,recordstatus
				from purchasinggroup a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.purchasinggroupid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('purchasinggroup');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('purchasingorgid'),
      GetCatalog('purchasinggroupcode'),
      GetCatalog('description'),
      GetCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
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
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['purchasingorgid'],
        $row1['purchasinggroupcode'],
        $row1['description'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select purchasingorgid,purchasinggroupcode,description,recordstatus
				from purchasinggroup a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.purchasinggroupid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('purchasingorgid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('purchasinggroupcode'))->setCellValueByColumnAndRow(2, 1, GetCatalog('description'))->setCellValueByColumnAndRow(3, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['purchasingorgid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['purchasinggroupcode'])->setCellValueByColumnAndRow(2, $i + 1, $row1['description'])->setCellValueByColumnAndRow(3, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="purchasinggroup.xlsx"');
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