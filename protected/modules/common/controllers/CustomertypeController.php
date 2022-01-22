<?php
class CustomertypeController extends Controller {
  public $menuname = 'customertype';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function search() {
    header("Content-Type: application/json");
    $customertypeid   = isset($_POST['customertypeid']) ? $_POST['customertypeid'] : '';
    $customertypename = isset($_POST['customertypename']) ? $_POST['customertypename'] : '';
    $customertypeid   = isset($_GET['q']) ? $_GET['q'] : $customertypeid;
    $customertypename = isset($_GET['q']) ? $_GET['q'] : $customertypename;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.customertypeid';
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
				->from('customertype t')
				->where("((customertypeid like :customertypeid) or (customertypename like :customertypename)) and t.recordstatus = 1", array(
						':customertypeid' => '%' . $customertypeid . '%',
						':customertypename' => '%' . $customertypename . '%',
				))->queryScalar();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('customertype t')
				->where("((customertypeid like :customertypeid) and (customertypename like :customertypename))", array(
						':customertypeid' => '%' . $customertypeid . '%',
						':customertypename' => '%' . $customertypename . '%',
				))->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('customertype t')
				->where("((customertypeid like :customertypeid) or (customertypename like :customertypename)) and t.recordstatus = 1", array(
						':customertypeid' => '%' . $customertypeid . '%',
						':customertypename' => '%' . $customertypename . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		} else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')
				->from('customertype t')
				->where("((customertypeid like :customertypeid) and (customertypename like :customertypename))", array(
						':customertypeid' => '%' . $customertypeid . '%',
						':customertypename' => '%' . $customertypename . '%',
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		}
		foreach ($cmd as $data) {      
			$row[] = array(
        'customertypeid' => $data['customertypeid'],
        'customertypename' => $data['customertypename'],
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
			$sql     = 'call Insertcustomertype(:vcustomertypename,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
		} else {
			$sql     = 'call Updatecustomertype(:vid,:vcustomertypename,:vrecordstatus,:vcreatedby)';
			$command = $connection->createCommand($sql);
			$command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcustomertypename', $arraydata[1], PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus', $arraydata[2], PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-customertype"]["name"]);
		if (move_uploaded_file($_FILES["file-customertype"]["tmp_name"], $target_file)) {
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
					$customertypename = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$this->ModifyData($connection,array($id,$customertypename,$recordstatus));
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
      $this->ModifyData($connection,array((isset($_POST['customertypeid'])?$_POST['customertypeid']:''),$_POST['customertypename'],$_POST['recordstatus']));
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
        $sql     = 'call Purgecustomertype(:vid,:vcreatedby)';
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
    $sql = "select customertypeid,customertypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from customertype a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.customertypeid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('customertype');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      GetCatalog('customertypeid'),
      GetCatalog('customertypename'),
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
        $row1['customertypeid'],
        $row1['customertypename'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    $this->menuname = 'customertype';
    parent::actionDownxls();
    $sql = "select customertypeid,customertypename,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from customertype a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.customertypeid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i, $row1['customertypeid'])->setCellValueByColumnAndRow(1, $i, $row1['customertypename'])->setCellValueByColumnAndRow(2, $i, $row1['recordstatus']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}