<?php
class AccperiodController extends Controller {
  public $menuname = 'accperiod';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header("Content-Type: application/json");
    $accperiodid     = isset($_POST['accperiodid']) ? $_POST['accperiodid'] : '';
    $period          = isset($_POST['period']) ? $_POST['period'] : '';
    $recordstatus    = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $accperiodid     = isset($_GET['q']) ? $_GET['q'] : $accperiodid;
    $period          = isset($_GET['q']) ? $_GET['q'] : $period;
    $recordstatus    = isset($_GET['q']) ? $_GET['q'] : $recordstatus;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.accperiodid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : 't.accperiodid';
    $order           = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('accperiod t')->where('(accperiodid like :accperiodid) and (period like :period)', array(
      ':accperiodid' => '%' . $accperiodid . '%',
      ':period' => '%' . $period . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*')->from('accperiod t')->where('(accperiodid like :accperiodid) and (period like :period)', array(
      ':accperiodid' => '%' . $accperiodid . '%',
      ':period' => '%' . $period . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'accperiodid' => $data['accperiodid'],
        'period' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['period'])),
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
        $sql     = 'call Insertaccperiod(:vperiod,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateaccperiod(:vid,:vperiod,:vrecordstatus,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['accperiodid'], PDO::PARAM_STR);
      }
      $command->bindvalue(':vperiod', date(Yii::app()->params['datetodb'], strtotime($_POST['period'])), PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus', $_POST['recordstatus'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      $this->DeleteLock($this->menuname, $_POST['accperiodid']);
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
        $sql     = 'call Purgeaccperiod(:vid,:vcreatedby)';
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
    $sql = "select accperiodid,period,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from accperiod a ";
		$accperiodid = filter_input(INPUT_GET,'accperiodid');
		$period = filter_input(INPUT_GET,'period');
		$sql .= " where coalesce(a.accperiodid,'') like '%".$accperiodid."%' 
			and coalesce(a.period,'') like '%".$period."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.accperiodid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('accperiod');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('accperiodid'),
      GetCatalog('period'),
      GetCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      15,
      155,
      20
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['accperiodid'],
        date(Yii::app()->params['dateviewfromdb'], strtotime($row1['period'])),
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'accperiod';
    parent::actionDownxls();
    $sql = "select accperiodid,period,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from accperiod a ";
    $accperiodid = filter_input(INPUT_GET,'accperiodid');
		$period = filter_input(INPUT_GET,'period');
		$sql .= " where coalesce(a.accperiodid,'') like '%".$accperiodid."%' 
			and coalesce(a.period,'') like '%".$period."%'
			";
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.accperiodid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['accperiodid'])->setCellValueByColumnAndRow(1, $i, $row1['period'])->setCellValueByColumnAndRow(2, $i, $row1['recordstatus']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}