<?php
  class MarketareaController extends Controller {
    public $menuname = 'marketarea';
    public function actionIndex() {
      parent::actionIndex();
      if(isset($_GET['grid']))
        echo $this->search();
      else
        $this->renderPartial('index',array());
    }
    public function search() {
      header("Content-Type: application/json");
      $marketareaid = isset ($_POST['marketareaid']) ? $_POST['marketareaid'] : '';
      $marketname = isset ($_POST['marketname']) ? $_POST['marketname'] : '';
      $recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
      $marketareaid = isset ($_GET['q']) ? $_GET['q'] : $marketareaid;
      $marketname = isset ($_GET['q']) ? $_GET['q'] : $marketname;
      $recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
      $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
      $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
      $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.marketareaid';
      $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
      $offset = ($page-1) * $rows;
      $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
      $rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
      $sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
      $order = isset($_GET['order']) ? strval($_GET['order']) : $order;
      $offset = ($page-1) * $rows;
      $result = array();
      $row = array();
      if (!isset($_GET['combo'])) {
        $cmd = Yii::app()->db->createCommand()
          ->select('count(1) as total')	
          ->from('marketarea t')
          ->where('(marketname like :marketname) and (recordstatus like :recordstatus)',
                          array(':marketname'=>'%'.$marketname.'%',':recordstatus'=>'%'.$recordstatus.'%'))
          ->queryScalar();
      } else {
        $cmd = Yii::app()->db->createCommand()
          ->select('count(1) as total')	
          ->from('marketarea t')
          ->where('((marketname like :marketname)) and t.recordstatus=1',
                          array(':marketname'=>'%'.$marketname.'%'))
          ->queryScalar();
      }
      $result['total'] = $cmd;
      if (!isset($_GET['combo'])) {
        $cmd = Yii::app()->db->createCommand()
          ->select()	
          ->from('marketarea t')
          ->where('(marketname like :marketname) and (recordstatus like :recordstatus)',
                          array(':marketname'=>'%'.$marketname.'%',':recordstatus'=>'%'.$recordstatus.'%'))
          ->offset($offset)
          ->limit($rows)
          ->order($sort.' '.$order)
          ->queryAll();
      } else {
        $cmd = Yii::app()->db->createCommand()
          ->select()	
          ->from('marketarea t')
          ->where('((marketname like :marketname)) and t.recordstatus=1',
                          array(':marketname'=>'%'.$marketname.'%'))
          ->offset($offset)
          ->limit($rows)
          ->order($sort.' '.$order)
          ->queryAll();
      }
      foreach($cmd as $data) {	
        $row[] = array(
          'marketareaid'=>$data['marketareaid'],
          'marketname'=>$data['marketname'],
          'recordstatus'=>$data['recordstatus'],
        );
      }
      $result=array_merge($result,array('rows'=>$row));
      return CJSON::encode($result);
    }
    private function ModifyData($connection,$arraydata) {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
      if ($id == '') {
        $sql = 'call Insertmarketarea(:vmarketname,:vrecordstatus,:vcreatedby)';
        $command=$connection->createCommand($sql);
      }
      else {
        $sql = 'call Updatemarketarea(:vid,:vmarketname,:vrecordstatus,:vcreatedby)';
        $command=$connection->createCommand($sql);
        $command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $arraydata[0]);
      }
      $command->bindvalue(':vmarketname',$arraydata[1],PDO::PARAM_STR);
      $command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
      $command->execute();
    }
    public function actionUpload() {
      parent::actionUpload();
      $target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-marketarea"]["name"]);
      if (move_uploaded_file($_FILES["file-marketarea"]["tmp_name"], $target_file)) {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($target_file);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow(); 
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
        $connection=Yii::app()->db;
        $transaction=$connection->beginTransaction();
        try {
          for ($row = 3; $row <= $highestRow; ++$row) {
            $id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $marketname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            $recordstatus = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            if($recordstatus=='Yes') {
              $recordstatus=1;
            }
            else {
              $recordstatus=0;
            }
            $this->ModifyData($connection,array($id,$marketname,$recordstatus));
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
    public function actionSave() {
      parent::actionWrite();
      $connection=Yii::app()->db;
      $transaction=$connection->beginTransaction();
      try {		
        $this->ModifyData($connection,array((isset($_POST['marketareaid'])?$_POST['marketareaid']:''),$_POST['marketname'],$_POST['recordstatus']));
        $transaction->commit();
        GetMessage(false,'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage(true,$e->getMessage());
      }
    }
    public function actionPurge() {
      parent::actionPurge();
      if (isset($_POST['id'])) {
        $id=$_POST['id'];
        $connection=Yii::app()->db;
        $transaction=$connection->beginTransaction();
        try {
          $sql = 'call Purgemarketarea(:vid,:vcreatedby)';
          $command=$connection->createCommand($sql);
          foreach($id as $ids) {
            $command->bindvalue(':vid',$ids,PDO::PARAM_STR);
            $command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
            $command->execute();
          }
          $transaction->commit();
          GetMessage(false,'insertsuccess');
        }
        catch (Exception $e) {
          $transaction->rollback();
          GetMessage(true,$e->getMessage());
        }
      }
      else {
        GetMessage(true,'chooseone');
      }
    }
    public function actionDownPDF() {
      parent::actionDownload();
      $sql = "select marketareaid,marketname,
              case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
              from marketarea a ";
      $marketareaid = filter_input(INPUT_GET,'marketareaid');
      $marketname = filter_input(INPUT_GET,'marketname');
      $sql .= " where coalesce(a.marketareaid,'') like '%".$marketareaid."%' 
        and coalesce(a.marketname,'') like '%".$marketname."%'
        ";
      if ($_GET['id'] !== '') {
          $sql = $sql . " and a.marketareaid in (".$_GET['id'].")";
      }
      $sql = $sql . " order by marketname asc ";
      $command=$this->connection->createCommand($sql);
      $dataReader=$command->queryAll();
      $this->pdf->title=GetCatalog('marketarea');
      $this->pdf->AddPage('P');
      $this->pdf->colalign = array('L','L','L');
      $this->pdf->colheader = array(GetCatalog('marketareaid'),
                                    GetCatalog('marketname'),
                                    GetCatalog('recordstatus'));
      $this->pdf->setwidths(array(15,155,20));
      $this->pdf->Rowheader();
      $this->pdf->coldetailalign = array('L','L','L');
      foreach($dataReader as $row1) {
        $this->pdf->row(array($row1['marketareaid'],$row1['marketname'],$row1['recordstatus']));
      }
      $this->pdf->Output();
    }
    public function actionDownXls() {
      $this->menuname='marketarea';
      parent::actionDownxls();
      $sql = "select marketareaid,marketname,
              case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
              from marketarea a ";
      $marketareaid = filter_input(INPUT_GET,'marketareaid');
      $marketname = filter_input(INPUT_GET,'marketname');
      $sql .= " where coalesce(a.marketareaid,'') like '%".$marketareaid."%' 
        and coalesce(a.marketname,'') like '%".$marketname."%'
        ";
      if ($_GET['id'] !== '') 
      {
          $sql = $sql . " and a.marketareaid in (".$_GET['id'].")";
      }
      $sql = $sql . " order by marketname asc ";
      $dataReader=Yii::app()->db->createCommand($sql)->queryAll();
      $i=2;		
      $this->phpExcel->setActiveSheetIndex(0)
        ->setCellValueByColumnAndRow(0,2,GetCatalog('marketareaid'))
        ->setCellValueByColumnAndRow(1,2,GetCatalog('marketname'))			
        ->setCellValueByColumnAndRow(2,2,GetCatalog('recordstatus'));
      foreach($dataReader as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)
          ->setCellValueByColumnAndRow(0, $i+1, $row1['marketareaid'])
          ->setCellValueByColumnAndRow(1, $i+1, $row1['marketname'])				
          ->setCellValueByColumnAndRow(2, $i+1, $row1['recordstatus']);
        $i+=1;
      }
      $this->getFooterXLS($this->phpExcel);	
    }
  }