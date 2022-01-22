<?php
class ReportdaController extends Controller {
  public $menuname = 'reportda';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->actionsearchdetail();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header("Content-Type: application/json");
    $deliveryadviceid = isset($_POST['deliveryadviceid']) ? $_POST['deliveryadviceid'] : '';
    $dadate           = isset($_POST['dadate']) ? $_POST['dadate'] : '';
    $dano             = isset($_POST['dano']) ? $_POST['dano'] : '';
    $sloccode           = isset($_POST['sloccode']) ? $_POST['sloccode'] : '';
    $sono             = isset($_POST['sono']) ? $_POST['sono'] : '';
    $productplanno    = isset($_POST['productplanno']) ? $_POST['productplanno'] : '';
    $productoutputno  = isset($_POST['productoutputno']) ? $_POST['productoutputno'] : '';
    $useraccessid     = isset($_POST['useraccessid']) ? $_POST['useraccessid'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $recordstatus     = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'deliveryadviceid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
		$connection				= Yii::app()->db;
		$from = '
			from deliveryadvice t 
			left join soheader a on a.soheaderid = t.soheaderid 
			left join productplan b on b.productplanid = t.productplanid 
			left join productoutput c on c.productoutputid = t.productoutputid
			left join sloc d on d.slocid = t.slocid
			left join plant e on e.plantid=d.plantid ';
		$where = "
			where (t.deliveryadviceid like '%".$deliveryadviceid."%') and
				(coalesce(t.dano,'') like '%".$dano."%') and			
				(coalesce(t.sloccode,'') like  '%".$sloccode."%') and								
				(coalesce(t.username,'') like  '%".$useraccessid."%') and								
				(coalesce(t.dadate,'') like  '%".$dadate."%') and								
				(coalesce(t.headernote,'') like '%".$headernote."%') and
				(coalesce(t.sono,'') like '%".$sono."%') and
				(coalesce(b.productplanno,'') like '%".$productplanno."%') and
				(coalesce(c.productoutputno,'') like '%".$productoutputno."%') and
				e.companyid in (".getUserObjectValues('company').")
        ";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.deliveryadviceid,t.dadate,t.dano,t.headernote,t.useraccessid,t.username,t.slocid,t.sloccode,
		t.statusname,t.recordstatus,a.sono,b.productplanno,c.productoutputno,d.description as slocdesc,
		(
		select case when sum(z.qty) > sum(z.giqty) then 1 else 0 end 
		from deliveryadvicedetail z 
		where z.deliveryadviceid = t.deliveryadviceid 		
		) as warna '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'deliveryadviceid' => $data['deliveryadviceid'],
        'dadate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['dadate'])),
        'dano' => $data['dano'],
        'sono' => $data['sono'],
        'productoutputno' => $data['productoutputno'],
        'productplanno' => $data['productplanno'],
        'headernote' => $data['headernote'],
        'useraccessid' => $data['useraccessid'],
        'username' => $data['username'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'warna' => $data['warna'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusda' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionsearchdetail() {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.deliveryadvicedetailid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('deliveryadvicedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('deliveryadviceid = :deliveryadviceid', array(
      ':deliveryadviceid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.requestedbycode,d.sloccode,
		d.description,getstock(t.productid,t.unitofmeasureid,t.slocid) as stock')
		->from('deliveryadvicedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('deliveryadviceid = :deliveryadviceid', array(
      ':deliveryadviceid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
			if ($data['qty'] > $data['giqty']) {
				$wtfs = 1;
			} else {
				$wtfs = 0;
			}
			if ($data['qty']-$data['giqty'] > $data['stock']) {
				$wstock = 1;
			} else {
				$wstock = 0;
			}
      $row[] = array(
        'deliveryadvicedetailid' => $data['deliveryadvicedetailid'],
        'deliveryadviceid' => $data['deliveryadviceid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'requestedbyid' => $data['requestedbyid'],
        'requestedbycode' => $data['requestedbycode'],
        'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'slocid' => $data['slocid'],
        'tosloccode' => $data['sloccode'] . ' - ' . $data['description'],
        'itemtext' => $data['itemtext'],
        'prqty' => Yii::app()->format->formatNumber($data['prqty']),
        'giqty' => Yii::app()->format->formatNumber($data['giqty']),
        'grqty' => Yii::app()->format->formatNumber($data['grqty']),
        'poqty' => Yii::app()->format->formatNumber($data['poqty']),
        'stock' => Yii::app()->format->formatNumber($data['stock']),
				'wtfs' => $wtfs,
				'wstock' => $wstock
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select getcompanysloc(a.slocid) as companyid,a.dano,a.dadate,a.headernote,
								a.deliveryadviceid,b.sloccode,b.description,a.recordstatus,c.productplanno,d.sono,e.productoutputno
								from deliveryadvice a 
								left join productplan c on c.productplanid = a.productplanid 
								left join soheader d on d.soheaderid = a.soheaderid 
								left join productoutput e on e.productoutputid = a.productoutputid
								left join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.deliveryadviceid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = getCatalog('deliveryadvice');
    $this->pdf->AddPage('P', array(
      220,
      70
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(10);
      $this->pdf->text(10, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(20, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['dadate'])));
      $this->pdf->text(50, $this->pdf->gety(), 'No ');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . $row['dano']);
      $this->pdf->text(90, $this->pdf->gety(), 'SO ');
      $this->pdf->text(100, $this->pdf->gety(), ': ' . $row['sono']);
      $this->pdf->text(130, $this->pdf->gety(), 'SPP ');
      $this->pdf->text(140, $this->pdf->gety(), ': ' . $row['productplanno']);
      $this->pdf->text(170, $this->pdf->gety(), 'OP ');
      $this->pdf->text(180, $this->pdf->gety(), ': ' . $row['productoutputno']);
      $this->pdf->text(10, $this->pdf->gety()+4, 'Gudang ');
      $this->pdf->text(40, $this->pdf->gety()+4, ': ' . $row['sloccode'] . ' - ' . $row['description']);
      $sql1        = "select b.productname, sum(a.qty) as qty, c.uomcode, a.itemtext,e.sloccode
							from deliveryadvicedetail a
							left join product b on b.productid = a.productid
							left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
							left join sloc e on e.slocid = a.slocid
							where deliveryadviceid = " . $row['deliveryadviceid'] . " group by b.productname,c.uomcode,e.sloccode ";
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $this->pdf->sety($this->pdf->gety() + 6);
      $this->pdf->colalign = array(
        'C',
        'C',
        'C',
        'C',
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        10,
        100,
        20,
        10,
        30,
        25
      ));
      $this->pdf->colheader = array(
        'No',
        'Items',
        'Qty',
        'Unit',
        'Gd Tujuan',
        'Remark'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'L',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          $row1['sloccode'],
          $row1['itemtext']
        ));
      }
      $this->pdf->sety($this->pdf->gety());
      $this->pdf->colalign = array(
        'C',
        'C'
      );
      $this->pdf->setwidths(array(
        30,
        170
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
        'Note:',
        $row['headernote']
      ));
      $this->pdf->sety($this->pdf->gety() + 3);
      $this->pdf->text(10, $this->pdf->gety(), 'Penerima');
      $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
      $this->pdf->text(120, $this->pdf->gety(), 'Mengetahui Peminta');
      $this->pdf->text(170, $this->pdf->gety(), 'Peminta Barang');
      $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(120, $this->pdf->gety() + 15, '........................');
      $this->pdf->text(170, $this->pdf->gety() + 15, '........................');
    }
    $this->pdf->Output();
  }
}