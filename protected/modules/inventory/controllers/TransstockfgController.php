<?php
class TransstockfgController extends Controller
{
  public $menuname = 'transstockfg';
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
			$dadate              = new DateTime('now');
			$sql = "insert into transstock (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',".findstatusbyuser('insts').")";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'transstockid' => $id
			));
    }
  }
  public function search()
  {
    header("Content-Type: application/json");
    $transstockid     = isset($_POST['transstockid']) ? $_POST['transstockid'] : '';
    $transstockno     = isset($_POST['transstockno']) ? $_POST['transstockno'] : '';
    $slocfrom         = isset($_POST['slocfrom']) ? $_POST['slocfrom'] : '';
    $slocto           = isset($_POST['slocto']) ? $_POST['slocto'] : '';
    $docdate          = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $dano             = isset($_POST['dano']) ? $_POST['dano'] : '';
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'transstockid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
    $connection		  = Yii::app()->db;

		$from = '
			from transstock t ';
		$where = "
			where (coalesce(transstockid,'') like '%".$transstockid."%') and (coalesce(transstockno,'') like '%".$transstockno."%') 
				and (coalesce(t.headernote,'') like '%".$headernote."%') and (coalesce(t.dano,'') like '%".$dano."%') 
				and (coalesce(t.slocfromcode,'') like '%".$slocfrom."%')
				and (coalesce(t.sloctocode,'') like '%".$slocto."%') and t.recordstatus in (".getUserRecordStatus('listts').")
				and t.deliveryadviceid is null 
        and t.slocfromid in (".getUserObjectValues('sloc').")";
		$sqlcount = 'select count(1) as total '.$from.' '.$where;
		$sql = 'select t.transstockid,t.transstockno,t.docdate,t.productoutputid,t.slocfromid,t.sloctoid,t.slocfromcode,t.sloctocode,t.productoutputno,t.headernote,t.statusname,t.recordstatus '.$from.' '.$where;
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
        'productoutputid' => $data['productoutputid'],
        'productoutputno' => $data['productoutputno'],
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
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,c.uomcode,e.qty as daqty,
				(select distinct description from storagebin z where z.storagebinid = t.storagebinid) as rakasal,
				(select distinct description from storagebin z where z.storagebinid = t.storagebintoid) as raktujuan,
				(select sum(qty) from productstock z where z.productid = t.productid and z.unitofmeasureid = t.unitofmeasureid 
				and z.slocid = d.slocfromid and qty = (select max(zz.qty) from productstock zz where zz.productid = t.productid and zz.unitofmeasureid = t.unitofmeasureid 
				and zz.slocid = d.slocfromid and zz.storagebinid = t.storagebinid)) as stok')->from('transstockdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure c', 'c.unitofmeasureid = t.unitofmeasureid')->leftjoin('transstock d', 'd.transstockid = t.transstockid')->leftjoin('deliveryadvicedetail e', 'e.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.transstockid = :transstockid', array(
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
        $sql     = 'call Inserttransstockfgop(:vslocfromid,:vsloctoid,:vdocdate,:vheadernote,:vdeliveryadviceid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatetransstockfgop(:vid,:vslocfromid,:vsloctoid,:vdocdate,:vheadernote,:vproductoutputid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['transstockid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['transstockid']);
      }
      $command->bindvalue(':vslocfromid', $_POST['slocfromid'], PDO::PARAM_STR);
      $command->bindvalue(':vsloctoid', $_POST['sloctoid'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vheadernote', $_POST['headernote'], PDO::PARAM_STR);
      $command->bindvalue(':vproductoutputid', $_POST['productoutputid'], PDO::PARAM_INT);
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
        $sql     = 'call Inserttransstockdet(:vtransstockid,:vproductid,:vstoragebinid,:vunitofmeasureid,:vqty,:vitemtext,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updatetransstockdet(:vid,:vtransstockid,:vproductid,:vstoragebinid,:vunitofmeasureid,:vqty,:vitemtext,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['transstockdetid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['transstockdetid']);
      }
      $command->bindvalue(':vtransstockid', $_POST['transstockid'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vstoragebinid', $_POST['storagebinid'], PDO::PARAM_STR);
      $command->bindvalue(':vunitofmeasureid', $_POST['unitofmeasureid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
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
        $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_STR);
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
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteTS(:vid,:vcreatedby)';
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
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveTSFG(:vid,:vcreatedby)';
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
      
        $query = "SELECT a.productoutputid, a.companyid, b.productid, b.qtyoutput, b.uomid, b.slocid, a.description as headernote, c.sloccode
                  FROM productoutput a
                  JOIN productoutputfg b ON a.productoutputid = b.productoutputid
                  JOIN sloc c ON c.slocid = b.slocid
                  WHERE a.productoutputid = ".$_POST['id'];
    
        $cmd = Yii::app()->db->createCommand($query)->queryRow();
        $slocfrom    = $cmd['slocid'];
        $sloccode    = $cmd['sloccode'];
        $header      = $cmd['headernote'];
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {
            $sql     = 'call GenerateTSFG(:vid, :vslocfrom, :vhid)';
            $command = $connection->createCommand($sql);
            $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
            $command->bindvalue(':vslocfrom', $slocfrom, PDO::PARAM_INT);
            $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
            $command->execute();
            $transaction->commit();
            if (Yii::app()->request->isAjaxRequest) {
              echo CJSON::encode(array(
                'status' => 'success',
                'slocfromid' => $slocfrom,
                'sloccode' => $sloccode,
                'headernote' => $header,
              ));
            }
        }
        catch (Exception $e) {
            $transaction->rollBack();
            GetMessage(true, $e->getMessage());
        }
    }
    Yii::app()->end();
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select a.*,getcompanysloc(slocfromid) as companyid,b.productoutputno,
						(select concat(z.sloccode,' - ',z.description) from sloc z where z.slocid = a.slocfromid) as fromsloc,
						(select concat(zz.sloccode,' - ',zz.description) from sloc zz where zz.slocid = a.sloctoid) as tosloc
						from transstock a
						left join productoutput b on b.productoutputid = a.productoutputid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.transstockid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('transstockfg');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->setFontSize(9);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['transstockno']);
      $this->pdf->text(10, $this->pdf->gety() + 4, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 4, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(65, $this->pdf->gety(), 'No Hasil Produksi');
      $this->pdf->text(90, $this->pdf->gety(), ': ' . $row['productoutputno']);
      $this->pdf->text(120, $this->pdf->gety(), 'Gd Asal');
      $this->pdf->text(140, $this->pdf->gety(), ': ' . $row['fromsloc']);
      $this->pdf->text(120, $this->pdf->gety() + 4, 'Gd Tujuan');
      $this->pdf->text(140, $this->pdf->gety() + 4, ': ' . $row['tosloc']);
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,
							(select description from storagebin z where z.storagebinid = a.storagebinid) as storagebinfrom,
							(select description from storagebin z where z.storagebinid = a.storagebintoid) as storagebinto
							from transstockdet a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							where transstockid = " . $row['transstockid'] . " group by b.productname order by transstockdetid";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 8);
      $this->pdf->colalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L',
        'L'
      );
      $this->pdf->setwidths(array(
        10,
        110,
        20,
        10,
        20,
        20
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
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
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
      $this->pdf->sety($this->pdf->gety());
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
        'Keterangan',
        $row['headernote']
      ));
      $this->pdf->text(10, $this->pdf->gety() + 5, 'Dibuat oleh,');
      $this->pdf->text(50, $this->pdf->gety() + 5, 'Diserahkan oleh,');
      $this->pdf->text(120, $this->pdf->gety() + 5, 'Diketahui oleh,');
      $this->pdf->text(170, $this->pdf->gety() + 5, 'Diterima oleh,');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'transstock';
    parent::actionDownxls();
    $sql = "select a.*,getcompanysloc(slocfromid) as companyid,b.dano,
						(select concat(z.sloccode,' - ',z.description) from sloc z where z.slocid = a.slocfromid) as fromsloc,
						(select concat(zz.sloccode,' - ',zz.description) from sloc zz where zz.slocid = a.sloctoid) as tosloc
						from transstock a
						left join deliveryadvice b on b.deliveryadviceid = a.deliveryadviceid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.transstockid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $line       = 3;
    foreach ($dataReader as $row) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, ': ' . $row['transstockno'])->setCellValueByColumnAndRow(4, $line, 'Gd Asal')->setCellValueByColumnAndRow(5, $line, ': ' . $row['fromsloc']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Tgl')->setCellValueByColumnAndRow(1, $line, ': ' . $row['docdate'])->setCellValueByColumnAndRow(4, $line, 'Gd Tujuan')->setCellValueByColumnAndRow(5, $line, ': ' . $row['tosloc']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No Permintaan')->setCellValueByColumnAndRow(1, $line, ': ' . $row['dano']);
      $line++;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'No')->setCellValueByColumnAndRow(1, $line, 'Nama Barang')->setCellValueByColumnAndRow(2, $line, 'Qty')->setCellValueByColumnAndRow(3, $line, 'Unit')->setCellValueByColumnAndRow(4, $line, 'Rak Asal')->setCellValueByColumnAndRow(5, $line, 'Rak Tujuan');
      $line++;
      $sql1        = "select b.productname, sum(ifnull(a.qty,0)) as vqty, c.uomcode,
							(select description from storagebin z where z.storagebinid = a.storagebinid) as storagebinfrom,
							(select description from storagebin z where z.storagebinid = a.storagebintoid) as storagebinto
							from transstockdet a
							inner join product b on b.productid = a.productid
							inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							where transstockid = " . $row['transstockid'] . " group by b.productname order by transstockdetid";
      $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
      $i           = 0;
      foreach ($dataReader1 as $row1) {
        $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, $i += 1)->setCellValueByColumnAndRow(1, $line, $row1['productname'])->setCellValueByColumnAndRow(2, $line, $row1['vqty'])->setCellValueByColumnAndRow(3, $line, $row1['uomcode'])->setCellValueByColumnAndRow(4, $line, $row1['storagebinfrom'])->setCellValueByColumnAndRow(5, $line, $row1['storagebinto']);
        $line++;
      }
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Keterangan : ')->setCellValueByColumnAndRow(1, $line, $row['headernote']);
      $line += 2;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, 'Dibuat oleh, ')->setCellValueByColumnAndRow(1, $line, 'Diserahkan oleh, ')->setCellValueByColumnAndRow(2, $line, 'Diketahui oleh, ')->setCellValueByColumnAndRow(3, $line, 'Diterima oleh, ');
      $line += 5;
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $line, '........................')->setCellValueByColumnAndRow(1, $line, '........................')->setCellValueByColumnAndRow(2, $line, '........................')->setCellValueByColumnAndRow(3, $line, '........................');
      $line++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
}
