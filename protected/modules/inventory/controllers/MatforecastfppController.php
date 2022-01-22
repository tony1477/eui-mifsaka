<?php
class MatforecastfppController extends Controller {
  public $menuname = 'matforecastfpp';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $company              = isset($_POST['company']) ? $_POST['company'] : '';
    $productcollect        = isset($_POST['collectionname']) ? $_POST['collectionname'] : '';
    //$uom                  = isset($_POST['uomid']) ? $_POST['uomid'] : '';
    $matforecastfppid     = isset($_POST['matforecastfppid']) ? $_POST['matforecastfppid'] : '';
    $isgenerate         = isset($_POST['isgenerate']) ? $_POST['isgenerate'] : '';
    $page                 = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows                 = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort                 = isset($_POST['sort']) ? strval($_POST['sort']) : 'matforecastfppid';
    $order                = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset               = ($page - 1) * $rows;
    $result               = array();
    $row                  = array();
      
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('matforecastfpp t')
		->leftjoin('company a', 'a.companyid = t.companyid')
		->leftjoin('productcollection c', 'c.productcollectid = t.productcollectid')
		->leftjoin('company d', 'd.companyid = t.pricefrom')
		->where("(coalesce(a.companyname,'') like :companyname) 
		and (coalesce(t.matforecastfppid,'') like :matforecastfppid)
		and (coalesce(c.collectionname,'') like :productcollect) ", array(
      ':companyname' => '%' . $company . '%',
      ':productcollect' => '%' . $productcollect . '%',
      ':matforecastfppid' => '%' . $matforecastfppid . '%',
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyname,c.productcollectid,c.collectionname, t.pricefrom, d.companyname as pricefromname')
    ->from('matforecastfpp t')
		->leftjoin('company a', 'a.companyid = t.companyid')
		->leftjoin('productcollection c', 'c.productcollectid = t.productcollectid')
		->leftjoin('company d', 'd.companyid = t.pricefrom')
		->where("(coalesce(a.companyname,'') like :companyname) 
		and (coalesce(t.matforecastfppid,'') like :matforecastfppid)
		and (coalesce(c.collectionname,'') like :productcollect) ", array(
      ':companyname' => '%' . $company . '%',
      ':productcollect' => '%' . $productcollect . '%',
      ':matforecastfppid' => '%' . $matforecastfppid . '%',
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'matforecastfppid' => $data['matforecastfppid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'pricefrom' => $data['pricefrom'],
        'pricefromname' => $data['pricefromname'],
        'productcollectid' => $data['productcollectid'],
        'collectionname' => $data['collectionname'],
        'isgenerate' => $data['isgenerate']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-matforecastfpp"]["name"]);
		if (move_uploaded_file($_FILES["file-matforecastfpp"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				for ($row = 3; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companyname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companyname = '".$companyname."'")->queryScalar();
          $companyprice = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$companyidprice = Yii::app()->db->createCommand("select companyid from company where companyname = '".$companyprice."'")->queryScalar();
          if($companyidprice==''){
            $companyidprice = null;
          }
					$productcollect = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
          $productcollectid = Yii::app()->db->createCommand("select productcollectid from productcollection where collectionname = '".$productcollect."'")->queryScalar();
					$isgenerate = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					//$isgenerate = Yii::app()->db->createCommand("select case when '".$generate."' = 'Yes' then '1' else '0' end")->queryScalar();
					if($id=='') {
              $sql = 'call Insertmatforecastfpp(:vcompanyid,:vproductcollectid,:vpricefrom,:visgenerate,:vcreatedby)';
              $command = $connection->createCommand($sql);
          }
          else{
              $sql = 'call Updatematforecastfpp(:vid,:vcompanyid,:vproductcollectid,:vpricefrom,:visgenerate,:vcreatedby)';
              $command = $connection->createCommand($sql);
              $command->bindvalue(':vid', $id, PDO::PARAM_STR);
          }
          $command->bindvalue(':vcompanyid', $companyid, PDO::PARAM_STR);
          $command->bindvalue(':vproductcollectid', $productcollectid, PDO::PARAM_STR);
          $command->bindvalue(':vpricefrom', $companyidprice, PDO::PARAM_STR);
          $command->bindvalue(':visgenerate', $isgenerate, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
				}
				$transaction->commit();			
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollBack();
				GetMessage(true,$e->getMessage());
			}
    }
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
        $sql     = 'call Insertmatforecastfpp(:vcompanyid,:vproductcollectid,:vpricefrom,:visgenerate,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatematforecastfpp(:vid,:vcompanyid,:vproductcollectid,:vpricefrom,:visgenerate,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['matforecastfppid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['matforecastfppid']);
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductcollectid', $_POST['productcollectid'], PDO::PARAM_STR);
      $command->bindvalue(':vpricefrom', $_POST['pricefrom'], PDO::PARAM_STR);
      $command->bindvalue(':visgenerate', $_POST['isgenerate'], PDO::PARAM_STR);
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
        $sql     = 'call Purgematforecastfpp(:vid,:vcreatedby)';
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
    $sql = "select a.*,b.companyname, c.companyname as companyprice, d.collectionname
						from matforecastfpp a
						left join company b on b.companyid = a.companyid
						left join company c on c.companyid = a.pricefrom
						left join productcollection d on d.productcollectid = a.productcollectid
            where a.matforecastfppid like '%".$_GET['matforecastfppid']."%'
                        ";
    /*
    if($_GET['isgenerate'] != '') {
        $sql = $sql. ' and a.isgenerate = '.$_GET['isgenerate'];
    }
    else
    {
        $isgenerate = ' ';
    }
    */
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.matforecastfppid in (" . $_GET['id'] . ")";
    }
    if ($_GET['company'] !== '') {
      $sql = $sql . " and b.companyname like '%".$_GET['uom']."%' ";
    }
    if ($_GET['collectionname'] !== '') {
      $sql = $sql . " and d.collectionname like '%".$_GET['collectionname']."%' ";
    }
      
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('matforecastfpp');
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
      getCatalog('ID'),
      getCatalog('companyname'),
      getCatalog('productcollect'),
      getCatalog('pricefrom'),
      getCatalog('isgenerate')
    );
    $this->pdf->setwidths(array(
      10,
      120,
      30,
      120,
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
        $row1['matforecastfppid'],
        $row1['companyname'],
        $row1['collectionname'],
        $row1['companyprice'],
        $row1['isgenerate']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'matforecastfpp';
    parent::actionDownxls();
    $sql = "select a.*,b.companyname, c.companyname as companyprice, d.collectionname
						from matforecastfpp a
						left join company b on b.companyid = a.companyid
						left join company c on c.companyid = a.pricefrom
						left join productcollection d on d.productcollectid = a.productcollectid
            where a.matforecastfppid like '%".$_GET['matforecastfppid']."%'
          ";
    /*
    if($_GET['isgenerate'] != '') {
        $sql = $sql. ' and a.isgenerate = '.$_GET['isgenerate'];
    }
    else
    {
        $isgenerate = ' ';
    }
    */
    if ($_GET['id'] !== '') {
      $sql = $sql . " and a.matforecastfppid in (" . $_GET['id'] . ")";
    }
    if ($_GET['company'] !== '') {
      $sql = $sql . " and b.companyname like '%".$_GET['uom']."%' ";
    }
    if ($_GET['collectionname'] !== '') {
      $sql = $sql . " and d.collectionname like '%".$_GET['collectionname']."%' ";
    }

    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0, $i, $row1['matforecastfppid'])
        ->setCellValueByColumnAndRow(1, $i, $row1['companyname'])
        ->setCellValueByColumnAndRow(2, $i, $row1['collectionname'])
        ->setCellValueByColumnAndRow(3, $i, $row1['companyprice'])
        ->setCellValueByColumnAndRow(4, $i, $row1['isgenerate']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}