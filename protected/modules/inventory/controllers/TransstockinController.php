<?php
class TransstockinController extends Controller
{
  public $menuname = 'transstockin';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function actionGetData()
  {
    if (isset($_GET['id'])) {
    } else {
      $model               = new Transstock;
      $dadate              = new DateTime('now');
      $model->docdate      = $dadate->format('Y-m-d');
      $model->recordstatus = findstatusbyuser('insts');
      if ($model->save()) {
        echo CJSON::encode(array(
          'transstockid' => $model->transstockid
        ));
      }
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $transstockid     = isset($_POST['transstockid']) ? $_POST['transstockid'] : '';
    $transstockno     = isset($_POST['transstockno']) ? $_POST['transstockno'] : '';
    $slocfrom       = isset($_POST['slocfrom']) ? $_POST['slocfrom'] : '';
    $slocto         = isset($_POST['slocto']) ? $_POST['slocto'] : '';
    $docdate          = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $dano = isset($_POST['dano']) ? $_POST['dano'] : '';
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'transstockid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
		$connection				= Yii::app()->db;		
		$from = '
			from transstock t ';
		$where = "
			where (coalesce(transstockid,'') like '%".$transstockid."%') and (coalesce(transstockno,'') like '%".$transstockno."%') 
				and (coalesce(t.headernote,'') like '%".$headernote."%') and (coalesce(t.dano,'') like '%".$dano."%') 
				and (coalesce(t.slocfromcode,'') like '%".$slocfrom."%')
				and (coalesce(t.sloctocode,'') like '%".$slocto."%') and t.recordstatus in (".getUserRecordStatus('listtsin').")
				and t.recordstatus between 3 and 4 and t.sloctoid in (".getUserObjectWfValues('sloc','apptsin').")
		";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select t.transstockid,t.transstockno,t.docdate,t.deliveryadviceid,t.slocfromid,t.sloctoid,t.slocfromcode,t.sloctocode,t.dano,t.headernote,t.statusname,t.recordstatus '.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'transstockid' => $data['transstockid'],
        'transstockno' => $data['transstockno'],
        'slocfromid' => $data['slocfromid'],
        'slocfromcode' => $data['slocfromcode'],
        'sloctoid' => $data['sloctoid'],
        'sloctocode' => $data['sloctocode'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'headernote' => $data['headernote'],
        'deliveryadviceid' => $data['deliveryadviceid'],
        'dano' => $data['dano'],
        'recordstatus' => $data['recordstatus'],
        'recordstatustransstock' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdetail()
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'transstockdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('transstockdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.unitofmeasureid')->leftjoin('transstock d', 'd.transstockid = t.transstockid')->leftjoin('deliveryadvicedetail e', 'e.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.transstockid = :transstockid', array(
      ':transstockid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,c.uomcode,ifnull(e.qty,0) as daqty,
				(select description from storagebin z where z.storagebinid = t.storagebinid) as rakasal,
				(select description from storagebin z where z.storagebinid = t.storagebintoid) as raktujuan,
				(select qty from productstock z where z.productid = t.productid and z.unitofmeasureid = t.unitofmeasureid 
				and z.slocid = d.slocfromid and z.storagebinid = t.storagebinid) as stok')->from('transstockdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.unitofmeasureid')->leftjoin('transstock d', 'd.transstockid = t.transstockid')->leftjoin('deliveryadvicedetail e', 'e.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.transstockid = :transstockid', array(
      ':transstockid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'transstockdetid' => $data['transstockdetid'],
        'transstockid' => $data['transstockid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'storagebinid' => $data['storagebinid'],
        'rakasal' => $data['rakasal'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qtyt' => $data['qty'],
        'daqty' => Yii::app()->format->formatNumber($data['daqty']),
        'stok' => Yii::app()->format->formatNumber($data['stok']),
        'stokt' => $data['stok'],
        'storagebintoid' => $data['storagebintoid'],
        'raktujuan' => $data['raktujuan'],
        'itemtext' => $data['itemtext']
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
        $sql     = 'call Inserttransstock(:vslocfromid,:vsloctoid,:vdocdate,:vheadernote,:vdeliveryadviceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatetransstock(:vid,:vslocfromid,:vsloctoid,:vdocdate,:vheadernote,:vdeliveryadviceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['transstockid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['transstockid']);
      }
      $command->bindvalue(':vslocfromid', $_POST['slocfromid'], PDO::PARAM_STR);
      $command->bindvalue(':vsloctoid', $_POST['sloctoid'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vdeliveryadviceid', $_POST['deliveryadviceid'], PDO::PARAM_STR);
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
  public function actionSavedetail()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call Inserttransstockdet(:vtransstockid,:vproductid,:vstoragebinid,:vunitofmeasureid,:vqty,:vstoragebintoid,:vitemtext,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatetransstockdetin(:vid,:vtransstockid,:vproductid,:vstoragebintoid,:vunitofmeasureid,:vqty,:vitemtext,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['transstockdetid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['transstockdetid']);
      }
      $command->bindvalue(':vtransstockid', $_POST['transstockid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vunitofmeasureid', $_POST['unitofmeasureid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebintoid', $_POST['storagebintoid'], PDO::PARAM_STR);
      $command->bindvalue(':vitemtext', $_POST['itemtext'], PDO::PARAM_STR);
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
        $sql     = 'call Purgetransstock(:vid,:vcreatedby)';
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
  public function actionPurgedetail()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgetransstockdet(:vid,:vcreatedby)';
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteTSin(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(true, 'chooseone', 1);
    }
  }
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveTSIn(:vid,:vcreatedby)';
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
  public function actionGeneratedetail()
  {
    if (isset($_POST['id'])) {
      $cmd         = Yii::app()->db->createCommand()->select('t.slocid as slocfromid,t.headernote,
					(select slocid from productplanfg z where z.productplanid = a.productplanid limit 1) as sloctoid')->from('deliveryadvice t')->leftjoin('productplan a', 'a.productplanid = t.productplanid')->where('t.deliveryadviceid = ' . $_POST['id'])->queryRow();
      $slocfrom    = $cmd['slocfromid'];
      $header      = $cmd['headernote'];
      $slocto      = $cmd['sloctoid'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call GenerateTSDA(:vid, :vslocfrom, :vslocto,:vhid)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
        $command->bindvalue(':vslocfrom', $slocfrom, PDO::PARAM_INT);
        $command->bindvalue(':vslocto', $slocto, PDO::PARAM_INT);
        $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
        $command->execute();
        $transaction->commit();
        if (Yii::app()->request->isAjaxRequest) {
          echo CJSON::encode(array(
            'status' => 'success',
            'slocfromid' => $slocfrom,
            'headernote' => $header,
            'sloctoid' => $slocto
          ));
        }
      }
      catch (Exception $e) {
        $transaction->rollBack();
        GetMessage('failure', $e->getMessage());
      }
    }
    Yii::app()->end();
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select a.*,getcompanysloc(slocfromid) as companyid,b.dano,
			(select concat(z.sloccode,' - ',z.description) from sloc z where z.slocid = a.slocfromid) as fromsloc,
			(select concat(zz.sloccode,' - ',zz.description) from sloc zz where zz.slocid = a.sloctoid) as tosloc
			from transstock a
			left join deliveryadvice b on b.deliveryadviceid = a.deliveryadviceid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.transstockid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('transstock');
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->Rect(10, 60, 190, 30);
      $this->pdf->setFont('Arial', 'B', 9);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['transstockno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Date ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(15, $this->pdf->gety() + 15, 'No Permintaan');
      $this->pdf->text(50, $this->pdf->gety() + 15, ': ' . $row['dano']);
      $this->pdf->text(15, $this->pdf->gety() + 20, 'Gd Asal');
      $this->pdf->text(50, $this->pdf->gety() + 20, ': ' . $row['fromsloc']);
      $this->pdf->text(15, $this->pdf->gety() + 25, 'Gd Tujuan');
      $this->pdf->text(50, $this->pdf->gety() + 25, ': ' . $row['tosloc']);
      $this->pdf->sety($this->pdf->gety() + 30);
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        160
      ));
      $this->pdf->iscustomborder = false;
      $this->pdf->setbordercell(array(
        'none',
        'none'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L'
      );
      $this->pdf->row(array(
        'Note',
        $row['headernote']
      ));
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,
				(select description from storagebin z where z.storagebinid = a.storagebinid) as storagebinfrom,
				(select description from storagebin z where z.storagebinid = a.storagebintoid) as storagebinto
        from transstockdet a
        inner join product b on b.productid = a.productid
        inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
        where transstockid = " . $row['transstockid'] . " group by b.productname order by transstockdetid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setFont('Arial', 'B', 7);
      $this->pdf->setwidths(array(
        10,
        85,
        25,
        20,
        25,
        25
      ));
      $this->pdf->setbordercell(array(
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB'
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Rak Asal',
        'Rak Tujuan'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 7);
      $this->pdf->setbordercell(array(
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB',
        'LTRB'
      ));
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'L',
        'L',
        'L',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatNumber($row1['vqty']),
          $row1['uomcode'],
          $row1['storagebinfrom'],
          $row1['storagebinto']
        ));
      }
      $this->pdf->checkNewPage(40);
      $this->pdf->Image('images/ttdts.jpg', 5, $this->pdf->gety() + 5, 200);
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select transstockno,slocfromid,sloctoid,docdate,headernote,deliveryadviceid,recordstatus
				from transstock a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.transstockid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, getCatalog('transstockno'))->setCellValueByColumnAndRow(1, 1, getCatalog('slocfromid'))->setCellValueByColumnAndRow(2, 1, getCatalog('sloctoid'))->setCellValueByColumnAndRow(3, 1, getCatalog('docdate'))->setCellValueByColumnAndRow(6, 1, getCatalog('headernote'))->setCellValueByColumnAndRow(7, 1, getCatalog('deliveryadviceid'))->setCellValueByColumnAndRow(8, 1, getCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['transstockno'])->setCellValueByColumnAndRow(1, $i + 1, $row1['slocfromid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['sloctoid'])->setCellValueByColumnAndRow(3, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(6, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(7, $i + 1, $row1['deliveryadviceid'])->setCellValueByColumnAndRow(8, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="transstock.xlsx"');
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