<?php
class RequestedbyController extends Controller {
  public $menuname = 'requestedby';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $requestedbyid = isset($_POST['requestedbyid']) ? $_POST['requestedbyid'] : '';
    $requestedbycode = isset($_POST['requestedbycode']) ? $_POST['requestedbycode'] : '';
    $description     = isset($_POST['description']) ? $_POST['description'] : '';
    $requestedbyid = isset($_GET['q']) ? $_GET['q'] : $requestedbyid;
    $requestedbycode = isset($_GET['q']) ? $_GET['q'] : $requestedbycode;
    $description     = isset($_GET['q']) ? $_GET['q'] : $description;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'requestedbyid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('requestedby t')->where('(requestedbyid like :requestedbyid) and (requestedbycode like :requestedbycode) and (description like :description)', array(
        ':requestedbyid' => '%' . $requestedbyid . '%',
        ':requestedbycode' => '%' . $requestedbycode . '%',
        ':description' => '%' . $description . '%'
      ))->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('requestedby t')->where('(requestedbyid like :requestedbyid) or (requestedbycode like :requestedbycode) or (description like :description)', array(
        ':requestedbyid' => '%' . $requestedbyid . '%',
        ':requestedbycode' => '%' . $requestedbycode . '%',
        ':description' => '%' . $description . '%'
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (!isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('requestedby t')->where('(requestedbyid like :requestedbyid) and (requestedbycode like :requestedbycode) and (description like :description)', array(
        ':requestedbyid' => '%' . $requestedbyid . '%',
        ':requestedbycode' => '%' . $requestedbycode . '%',
        ':description' => '%' . $description . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*')->from('requestedby t')->where('(requestedbyid like :requestedbyid) or (requestedbycode like :requestedbycode) or (description like :description)', array(
        ':requestedbyid' => '%' . $requestedbyid . '%',
        ':requestedbycode' => '%' . $requestedbycode . '%',
        ':description' => '%' . $description . '%'
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
      $row[] = array(
        'requestedbyid' => $data['requestedbyid'],
        'requestedbycode' => $data['requestedbycode'],
        'description' => $data['description'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Insertrequestedby(:vrequestedbycode,:vdescription,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updaterequestedby(:vid,:vrequestedbycode,:vdescription,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['requestedbyid'], PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['requestedbyid']);
      }
      $command->bindvalue(':vrequestedbycode', $_POST['requestedbycode'], PDO::PARAM_STR);
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
        $sql     = 'call Purgerequestedby(:vid,:vcreatedby)';
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
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select requestedbyid,requestedbycode,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from requestedby a ";
		$requestedbyid = filter_input(INPUT_GET,'requestedbyid');
		$requestedbycode = filter_input(INPUT_GET,'requestedbycode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.requestedbyid,'') like '%".$requestedbyid."%' 
			and coalesce(a.requestedbycode,'') like '%".$requestedbycode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.requestedbyid in (" . $_GET['id'] . ")";
    } 
    $sql = $sql . " order by requestedbycode asc ";
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('requestedby');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      getCatalog('requestedbyid'),
      getCatalog('requestedbycode'),
      getCatalog('description'),
      getCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      15,
      40,
      115,
      20
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
        $row1['requestedbyid'],
        $row1['requestedbycode'],
        $row1['description'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'requestedby';
    parent::actionDownxls();
    $sql = "select requestedbyid,requestedbycode,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from requestedby a ";
		$requestedbyid = filter_input(INPUT_GET,'requestedbyid');
		$requestedbycode = filter_input(INPUT_GET,'requestedbycode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.requestedbyid,'') like '%".$requestedbyid."%' 
			and coalesce(a.requestedbycode,'') like '%".$requestedbycode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.requestedbyid in (" . $_GET['id'] . ")";
    } 
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['requestedbyid'])->setCellValueByColumnAndRow(1, $i, $row1['requestedbycode'])->setCellValueByColumnAndRow(2, $i, $row1['description'])->setCellValueByColumnAndRow(3, $i, $row1['recordstatus']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}