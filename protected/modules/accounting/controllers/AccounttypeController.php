<?php
class AccounttypeController extends Controller {
  public $menuname = 'accounttype';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header("Content-Type: application/json");
    $accounttypeid   = isset($_POST['accounttypeid']) ? $_POST['accounttypeid'] : '';
    $accounttypename = isset($_POST['accounttypename']) ? $_POST['accounttypename'] : '';
    $accounttypeid   = isset($_GET['q']) ? $_GET['q'] : $accounttypeid;
    $accounttypename = isset($_GET['q']) ? $_GET['q'] : $accounttypename;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.accounttypeid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('accounttype t')
				->where("((accounttypeid like :accounttypeid) or (accounttypename like :accounttypename)) and t.recordstatus = 1", array(
						':accounttypeid' => '%' . $accounttypeid . '%',
						':accounttypename' => '%' . $accounttypename . '%',
				))->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('accounttype t')
				->where("((accounttypeid like :accounttypeid) and (accounttypename like :accounttypename))", array(
						':accounttypeid' => '%' . $accounttypeid . '%',
						':accounttypename' => '%' . $accounttypename . '%',
				))->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('accounttype t')
				->where("((accounttypeid like :accounttypeid) or (accounttypename like :accounttypename)) and t.recordstatus = 1", array(
						':accounttypeid' => '%' . $accounttypeid . '%',
						':accounttypename' => '%' . $accounttypename . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('accounttype t')
				->where("((accounttypeid like :accounttypeid) and (accounttypename like :accounttypename))", array(
						':accounttypeid' => '%' . $accounttypeid . '%',
						':accounttypename' => '%' . $accounttypename . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {      
			$row[] = array(
        'accounttypeid' => $data['accounttypeid'],
        'accounttypename' => $data['accounttypename'],
        'recordstatus' => $data['recordstatus']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	protected function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql     = 'call Insertaccounttype(:vaccounttypename,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updateaccounttype(:vid,:vaccounttypename,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vaccounttypename', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-accounttype"]["name"]);
		if (move_uploaded_file($_FILES["file-accounttype"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection  = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$accounttypename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$accounttypename,$recordstatus));
				}
				$transaction->commit();     
				GetMessage(false, 'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true, $e->getMessage());
			}
    }
	}
  public function actionSave() {
		parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $this->ModifyData($connection,array((isset($_POST['accounttypeid'])?$_POST['accounttypeid']:''),$_POST['accounttypename'],$_POST['recordstatus']));
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
        $sql     = 'call Purgeaccounttype(:vid,:vcreatedby)';
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
    $sql = "select accounttypeid,accounttypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from accounttype a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.accounttypeid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('accounttype');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('accounttypeid'),
      GetCatalog('accounttypename'),
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
        $row1['accounttypeid'],
        $row1['accounttypename'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'accounttype';
    parent::actionDownxls();
    $sql = "select accounttypeid,accounttypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from accounttype a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.accounttypeid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['accounttypeid'])->setCellValueByColumnAndRow(1, $i, $row1['accounttypename'])->setCellValueByColumnAndRow(2, $i, $row1['recordstatus']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}