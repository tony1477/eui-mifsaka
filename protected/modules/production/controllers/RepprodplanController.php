<?php
class RepprodplanController extends Controller {
  public $menuname = 'repprodplan';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexfg() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetailfg();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $productplanid   = isset($_POST['productplanid']) ? $_POST['productplanid'] : '';
    $company       = isset($_POST['company']) ? $_POST['company'] : '';
    $productplanno   = isset($_POST['productplanno']) ? $_POST['productplanno'] : '';
    $sono      			 = isset($_POST['sono']) ? $_POST['sono'] : '';
    $description     = isset($_POST['description']) ? $_POST['description'] : '';
    $productplandate = isset($_POST['productplandate']) ? $_POST['productplandate'] : '';
    $customer = isset($_POST['customer']) ? $_POST['customer'] : '';
    $foreman = isset($_POST['foreman']) ? $_POST['foreman'] : '';
    $productdetail = isset($_POST['productdetail']) ? $_POST['productdetail'] : '';
    $productfg = isset($_POST['productfg']) ? $_POST['productfg'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'productplanid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $whereproductfg='';
    $whereproductdetail = '';
    if($productdetail!='') {
        $whereproductdetail = " and t.productplanid in (select a.productplanid
        from productplan a
        join productplandetail b on b.productplanid = a.productplanid
        join product c on c.productid = b.productid
        where c.productname like '%".$productdetail."%') ";
    }
    if($productfg!='') {
        $whereproductfg =" and t.productplanid in (select a.productplanid
        from productplan a
        join productplanfg b on b.productplanid = a.productplanid
        join product c on c.productid = b.productid
        where c.productname like '%".$productfg."%') ";
    }
		$connection		= Yii::app()->db;
		$from = ' 
			from productplan t 
			left join soheader a on a.soheaderid = t.soheaderid 
			left join addressbook b on b.addressbookid = a.addressbookid 
			left join company c on c.companyid = t.companyid
			left join employee d on d.employeeid = t.employeeid ';
		$where = "
			where (coalesce(a.sono,'') like '%".$sono."%') and (coalesce(c.companyname,'') like '%".$company."%') 
				and (coalesce(t.productplanno,'') like '%".$productplanno."%') and (coalesce(t.productplandate,'') like '%".$productplandate."%') 
				and (t.productplanid like '%".$productplanid."%') 
				and (coalesce(b.fullname,'') like '%".$customer."%') and (coalesce(d.fullname,'') like '%".$foreman."%') and (coalesce(t.description,'') like '%".$description."%')  
				 and t.companyid in (".getUserObjectValues('company').")
                {$whereproductfg} {$whereproductdetail}
				";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = '
			select t.productplanid,t.soheaderid,a.sono,t.productplanno,t.companyid,c.companyname,b.fullname,t.productplandate,
				t.description,t.statusname,t.recordstatus,
				(
				select case when sum(z.qty) > sum(z.qtyres) then 1 else 0 end 
				from productplanfg z 
				where z.productplanid = t.productplanid  
				) as warna, t.employeeid, ifnull(d.fullname,"-") as foremanname

				'.$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productplanid' => $data['productplanid'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'productplanno' => $data['productplanno'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'customername' => $data['fullname'],
        'warna' => $data['warna'],
        'productplandate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['productplandate'])),
        'description' => $data['description'],
        'employeeid' => $data['employeeid'],
        'foremanname' => $data['foremanname'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusproductplan' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchdetailfg() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productplanfg t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('billofmaterial e', 'e.bomid = t.bomid')->leftjoin('machine f', 'f.machineid = t.machineid')->leftjoin('employee g', 'g.employeeid = t.employeeid')->where('t.productplanid = :productplanid', array(
      ':productplanid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,e.bomversion,c.sloccode,c.description as slocdesc,getstock(t.productid,t.uomid,t.slocid) as stock, f.machinecode, g.fullname')->from('productplanfg t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('billofmaterial e', 'e.bomid = t.bomid')->leftjoin('machine f', 'f.machineid = t.machineid')->leftjoin('employee g', 'g.employeeid = t.employeeid')->where('t.productplanid = :productplanid', array(
      ':productplanid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
			if ($data['qty'] > $data['qtyres']) {
				$wqtyres = 1;
			} else {
				$wqtyres = 0;
			}
			if ($data['qty'] > $data['stock']) {
				$wstock = 1;
			} else {
				$wstock = 0;
			}
      $row[] = array(
        'productplanfgid' => $data['productplanfgid'],
        'productplanid' => $data['productplanid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qtyres' => Yii::app()->format->formatNumber($data['qtyres']),
        'stock' => Yii::app()->format->formatNumber($data['stock']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'bomid' => $data['bomid'],
				'wqtyres'=> $wqtyres,
				'wstock'=> $wstock,
        'bomversion' => $data['bomversion'],
        'startdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
        'enddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'description' => $data['description'],
        'machineid' => $data['machineid'],
        'machinecode' => $data['machinecode'],
        'employeeid' => $data['machineid'],
        'fullname' => $data['fullname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $sql = "select a.uomcode, sum(t.qty) as qty, sum(t.qtyres) as qtyres
		from productplanfg t 
		join unitofmeasure a on a.unitofmeasureid = t.uomid 
		where t.productplanid = ".$id." group by a.uomcode";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
		$footer[] = array(
      'productname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($data['qty']),
      'qtyres' => Yii::app()->format->formatNumber($data['qtyres']),
      'uomcode' => $data['uomcode'],
    );
		}
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchdetail() {
    header("Content-Type: application/json");
    $id              = 0;
    $productplanfgid = '';
    if (isset($_POST['productplanfgid'])) {
      $productplanfgid = $_POST['productplanfgid'];
    }
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } else if (isset($_POST['id'])) {
      $id = $_POST['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('productplandetail t')->join('productplanfg c', 'c.productplanfgid = t.productplanfgid and c.productplanid = t.productplanid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('billofmaterial d', 'd.bomid = t.bomid')->where('c.productplanid = :productplanid and c.productplanfgid = :productplanfgid', array(
      ':productplanid' => $id,
      ':productplanfgid' => $productplanfgid
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,d.bomversion,
					(select description from sloc zz where zz.slocid = t.fromslocid) as fromsloccode,
					(select description from sloc zz where zz.slocid = t.toslocid) as tosloccode,
					getstock(t.productid,t.uomid,t.fromslocid) as stockfrom,
					getstock(t.productid,t.uomid,t.toslocid) as stockto')->from('productplandetail t')->join('productplanfg c', 'c.productplanfgid = t.productplanfgid and c.productplanid = t.productplanid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.uomid')->leftjoin('billofmaterial d', 'd.bomid = t.bomid')->where('c.productplanid = :productplanid and c.productplanfgid = :productplanfgid', array(
      ':productplanid' => $id,
      ':productplanfgid' => $productplanfgid
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'productplandetailid' => $data['productplandetailid'],
        'productplanfgid' => $data['productplanfgid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'bomid' => $data['bomid'],
        'bomversion' => $data['bomversion'],
        'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'fromslocid' => $data['fromslocid'],
        'fromsloccode' => $data['fromsloccode'],
        'stockfrom' => Yii::app()->format->formatNumber($data['stockfrom']),
        'stockto' => Yii::app()->format->formatNumber($data['stockto']),
        'toslocid' => $data['toslocid'],
        'tosloccode' => $data['tosloccode'],
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionComplete() {
  	header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Completereportspp(:vid,:vcreatedby)';
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
        GetMessage(true, $e->getMessage(), 1);
      }
    } else {
      GetMessage(true, 'chooseone', 1);
    }
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select a.*
      from productplan a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = GetCatalog('productplan');
    $this->pdf->AddPage('P');
    foreach ($dataReader as $row) {
      $this->pdf->SetFont('Arial', '', 10);
      $this->pdf->Rect(10, 60, 190, 15);
      $this->pdf->text(15, $this->pdf->gety() + 5, 'No SPP ');
      $this->pdf->text(50, $this->pdf->gety() + 5, ': ' . $row['productplanno']);
      $this->pdf->text(15, $this->pdf->gety() + 10, 'Tgl SPP ');
      $this->pdf->text(50, $this->pdf->gety() + 10, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['productplandate'])));
      $sql1        = "select b.productname, a.qty, c.uomcode, a.description,d.sloccode,d.description as slocdesc
        from productplanfg a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join sloc d on d.slocid = a.slocid
        where productplanid = " . $row['productplanid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 20, 'FG');
      $this->pdf->sety($this->pdf->gety() + 25);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        60,
        20,
        15,
        30,
        55
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
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
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['sloccode'] . ' - ' . $row1['slocdesc'],
          $row1['description']
        ));
      }
      $this->pdf->setFont('Arial', '', 10);
      $sql1        = "select b.productname, a.qty, c.uomcode, a.description,d.bomversion,
				(select sloccode from sloc d where d.slocid = a.fromslocid) as fromsloccode,
				(select description from sloc d where d.slocid = a.fromslocid) as fromslocdesc,
				(select sloccode from sloc d where d.slocid = a.toslocid) as tosloccode,	
				(select description from sloc d where d.slocid = a.toslocid) as toslocdesc			
        from productplandetail a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid
				left join billofmaterial d on d.bomid = a.bomid
        where b.isstock = 1 and productplanid = " . $row['productplanid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->text(10, $this->pdf->gety() + 10, 'RM');
      $this->pdf->sety($this->pdf->gety() + 15);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        60,
        20,
        15,
        25,
        25,
        35
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gudang Asal',
        'Gudang Tujuan',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->setFont('Arial', '', 8);
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
          Yii::app()->format->formatNumber($row1['qty']),
          $row1['uomcode'],
          $row1['fromsloccode'] . ' - ' . $row1['fromslocdesc'],
          $row1['tosloccode'] . ' - ' . $row1['toslocdesc'],
          $row1['bomversion'] . ' ' . $row1['description']
        ));
        $this->pdf->checkPageBreak(40);
      }
      $this->pdf->setFont('Arial', '', 10);
      $this->pdf->text(10, $this->pdf->gety() + 30, 'Approved By');
      $this->pdf->text(150, $this->pdf->gety() + 30, 'Proposed By');
      $this->pdf->text(10, $this->pdf->gety() + 50, '____________ ');
      $this->pdf->text(150, $this->pdf->gety() + 50, '____________');
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownload();
    $sql = "select productplanversion,productid,qty,uomid,productplandate,description,recordstatus
		from productplan a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.productplanid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('productplanversion'))->setCellValueByColumnAndRow(1, 1, GetCatalog('productid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('qty'))->setCellValueByColumnAndRow(3, 1, GetCatalog('uomid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('productplandate'))->setCellValueByColumnAndRow(5, 1, GetCatalog('description'))->setCellValueByColumnAndRow(6, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['productplanversion'])->setCellValueByColumnAndRow(1, $i + 1, $row1['productid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['qty'])->setCellValueByColumnAndRow(3, $i + 1, $row1['uomid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['productplandate'])->setCellValueByColumnAndRow(5, $i + 1, $row1['description'])->setCellValueByColumnAndRow(6, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="productplan.xlsx"');
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