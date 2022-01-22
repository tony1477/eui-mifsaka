<?php
class RepforecastfppController extends Controller {
  public $menuname = 'repforecastfpp';
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
    $forecastfppid    = isset($_POST['forecastfppid']) ? $_POST['forecastfppid'] : '';
    $perioddatey        = isset($_POST['perioddateyear']) ? $_POST['perioddateyear'] : '';
    $perioddatem        = isset($_POST['perioddatemonth']) ? $_POST['perioddatemonth'] : '';
    $docdate          = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $perioddate       = isset($_POST['perioddate']) ? $_POST['perioddate'] : '';
    $companyname          = isset($_POST['companyname']) ? $_POST['companyname'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $recordstatus     = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'forecastfppid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
		$connection				= Yii::app()->db;
    $perioddate = '';
    if($perioddatey != '') {
        $perioddate .= "  year(perioddate) = year('{$perioddatey}-01-01') and ";
    }
    if($perioddatem != '') {
        $perioddate .= "  month(perioddate) = month('2020-{$perioddatem}-01') and ";
    }
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
    ->from('forecastfpp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->where("{$perioddate} (a.companyname like :companyname) and
            t.companyid in (".getUserObjectValues('company').")", array(
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()->select('t.*,a.companyname')
    ->from('forecastfpp t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->where("{$perioddate}  (a.companyname like :companyname) and
            t.companyid in (".getUserObjectValues('company').")", array(
        
        //':perioddate' => '%' . $perioddate . '%',
        ':companyname' => '%' . $companyname . '%',
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'forecastfppid' => $data['forecastfppid'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'perioddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['perioddate'])),
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'recordstatus' => $data['recordstatus'],
        'statusname' => $data['statusname']
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
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 't.forecastfppdetid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
    $offset          = ($page - 1) * $rows;
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,d.sloccode,d.description')
    	->from('forecastfppdet t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('forecastfppid = :forecastfppid', array(
      ':forecastfppid' => $id
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'forecastfppdetid' => $data['forecastfppdetid'],
        'forecastfppid' => $data['forecastfppid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'qtyforecast' => Yii::app()->format->formatNumber($data['qtyforecast']),
        'avg3month' => Yii::app()->format->formatNumber($data['avg3month']),
        'avgperday' => Yii::app()->format->formatNumber($data['avgperday']),
        'qtymax' => Yii::app()->format->formatNumber($data['qtymax']),
        'qtymin' => Yii::app()->format->formatNumber($data['qtymin']),
        'leadtime' => Yii::app()->format->formatNumber($data['leadtime']),
        'pendingpo' => Yii::app()->format->formatNumber($data['pendingpo']),
        'saldoawal' => Yii::app()->format->formatNumber($data['saldoawal']),
        'grpredict' => Yii::app()->format->formatNumber($data['grpredict']),
        'prqty' => Yii::app()->format->formatNumber($data['prqty']),
        'prqtyreal' => Yii::app()->format->formatNumber($data['prqtyreal']),
        'price' => Yii::app()->format->formatNumber($data['price']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select getcompanysloc(a.slocid) as companyid,a.perioddate,a.docdate,a.headernote,
								a.forecastfppid,b.sloccode,b.description,a.recordstatus,c.productplanno,d.sono,e.productoutputno
								from deliveryadvice a 
								left join productplan c on c.productplanid = a.productplanid 
								left join soheader d on d.soheaderid = a.soheaderid 
								left join productoutput e on e.productoutputid = a.productoutputid
								left join sloc b on b.slocid = a.slocid ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.forecastfppid in (" . $_GET['id'] . ")";
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
      $this->pdf->text(20, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(50, $this->pdf->gety(), 'No ');
      $this->pdf->text(60, $this->pdf->gety(), ': ' . $row['perioddate']);
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
							where forecastfppid = " . $row['forecastfppid'] . " group by b.productname,c.uomcode,e.sloccode ";
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