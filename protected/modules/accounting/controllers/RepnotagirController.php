<?php
class RepnotagirController extends Controller
{
  public $menuname = 'repnotagir';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexproduct()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchproduct();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexakun()
  {
    if (isset($_GET['grid']))
      echo $this->actionsearchakun();
    else
      $this->renderPartial('index', array());
  }
  public function search()
  {
    header("Content-Type: application/json");
    $notagirid       = isset($_POST['notagirid']) ? $_POST['notagirid'] : '';
    $notagirno       = isset($_POST['notagirno']) ? $_POST['notagirno'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $gireturid       = isset($_POST['gireturid']) ? $_POST['gireturid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $customer       = isset($_POST['customer']) ? $_POST['customer'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagirid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $rec          = array();
    $com          = array();
		
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagir t')
		->leftjoin('giretur a', 'a.gireturid = t.gireturid')
		->leftjoin('company b', 'b.companyid = t.companyid')
		->leftjoin('giheader c', 'c.giheaderid = a.giheaderid')
		->leftjoin('soheader d', 'd.soheaderid = c.soheaderid')
		->leftjoin('addressbook f', 'f.addressbookid = d.addressbookid')
		->leftjoin('company e', 'e.companyid = t.companyid')
		->where("((coalesce(t.docdate,'') like :docdate) and
			(coalesce(t.notagirno,'') like :notagirno) and
			(coalesce(t.notagirid,'') like :notagirid) and
			(coalesce(b.companyname,'') like :companyid) and
			(coalesce(f.fullname,'') like :customer) and
			(coalesce(a.gireturno,'') like :gireturid)) and t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':notagirid' => '%' . $notagirid . '%',
      ':notagirno' => '%' . $notagirno . '%',
      ':gireturid' => '%' . $gireturid . '%',
			 ':companyid' => '%' . $companyid . '%',
			 ':customer' => '%' . $customer . '%',
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.gireturno,b.companyname,f.fullname,f.addressbookid,
		d.soheaderid,d.sono,c.gino,c.giheaderid')->from('notagir t')->leftjoin('giretur a', 'a.gireturid = t.gireturid')
		->leftjoin('company b', 'b.companyid = t.companyid')
		->leftjoin('giheader c', 'c.giheaderid = a.giheaderid')
		->leftjoin('soheader d', 'd.soheaderid = c.soheaderid')
		->leftjoin('addressbook f', 'f.addressbookid = d.addressbookid')
		->leftjoin('company e', 'e.companyid = t.companyid')
		->where("((coalesce(t.docdate,'') like :docdate) and
			(coalesce(t.notagirno,'') like :notagirno) and
			(coalesce(t.notagirid,'') like :notagirid) and
			(coalesce(b.companyname,'') like :companyid) and
			(coalesce(f.fullname,'') like :customer) and
			(coalesce(a.gireturno,'') like :gireturid)) and t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':notagirid' => '%' . $notagirid . '%',
      ':notagirno' => '%' . $notagirno . '%',
      ':gireturid' => '%' . $gireturid . '%',
			 ':companyid' => '%' . $companyid . '%',
			 ':customer' => '%' . $customer . '%',
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagirid' => $data['notagirid'],
        'notagirno' => $data['notagirno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'gireturid' => $data['gireturid'],
        'gireturno' => $data['gireturno'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'giheaderid' => $data['giheaderid'],
        'gino' => $data['gino'],
        'soheaderid' => $data['soheaderid'],
        'sono' => $data['sono'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusnotagir' => $data['statusname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchproduct()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagirpro t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('gireturdetail b', 'b.gireturdetailid=t.gireturdetailid')->leftjoin('gidetail c', 'c.gidetailid=b.gidetailid')->leftjoin('sodetail d', 'd.sodetailid=c.sodetailid')->leftjoin('unitofmeasure e', 'e.unitofmeasureid=b.uomid')->leftjoin('currency f', 'f.currencyid=t.currencyid')->leftjoin('sloc g', 'g.slocid=t.slocid')->where('(notagirid = :notagirid)', array(
      ':notagirid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,e.uomcode,(t.qty*t.price) as total,f.currencyname,g.sloccode')->from('notagirpro t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('gireturdetail b', 'b.gireturdetailid=t.gireturdetailid')->leftjoin('gidetail c', 'c.gidetailid=b.gidetailid')->leftjoin('sodetail d', 'd.sodetailid=c.sodetailid')->leftjoin('unitofmeasure e', 'e.unitofmeasureid=b.uomid')->leftjoin('currency f', 'f.currencyid=t.currencyid')->leftjoin('sloc g', 'g.slocid=t.slocid')->where('(notagirid = :notagirid)', array(
      ':notagirid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagirproid' => $data['notagirproid'],
        'notagirid' => $data['notagirid'],
        'gireturdetailid' => $data['gireturdetailid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->numberFormatter->format(Yii::app()->params["defaultnumberqty"], $data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'price' => ($data['price']),
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'total' => Yii::app()->format->formatNumber($data['total'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(t.qty*t.price) as jumlah
		from notagirpro t 
		where notagirid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
			$footer[] = array(
				'productname' => 'Total',
				'total' => Yii::app()->format->formatNumber($data['jumlah'])
			);
		}
		$sql = "select b.uomcode,sum(t.qty) as credit
		from notagirpro t 
		join gireturdetail a on a.gireturdetailid = t.gireturdetailid
		join unitofmeasure b on b.unitofmeasureid = a.uomid
		where notagirid = ".$id." group by b.uomcode";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
			$footer[] = array(
				'productname' => 'Total',
				'qty' => Yii::app()->format->formatNumber($data['credit']),
				'uomcode' => $data['uomcode']
			);
		}
		$result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionSearchakun()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagiracc t')->leftjoin('account a', 'a.accountid=t.accountid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->where('(notagirid = :notagirid)', array(
      ':notagirid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountname,b.currencyname')->from('notagiracc t')->leftjoin('account a', 'a.accountid=t.accountid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->where('(notagirid = :notagirid)', array(
      ':notagirid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagiraccid' => $data['notagiraccid'],
        'notagirid' => $data['notagirid'],
        'accountid' => $data['accountid'],
        'accountname' => $data['accountname'],
        'debet' => Yii::app()->format->formatNumber($data['debet']),
        'credit' => Yii::app()->format->formatNumber($data['credit']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
        'itemnote' => $data['itemnote']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
				$sql = "select sum(t.debet) as debit,sum(t.credit) as credit
		from notagiracc t 
		join account a on a.accountid = t.accountid
		where notagirid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
			$footer[] = array(
				'accountname' => 'Total',
				'debet' => Yii::app()->format->formatNumber($data['credit']),
				'credit' => Yii::app()->format->formatNumber($data['credit'])
			);
		}
		$result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select *,a.notagirid,a.notagirno,a.companyid,a.docdate,b.gireturno,d.sono,e.fullname as customer,f.invoiceno
                        from notagir a
                        left join giretur b on b.gireturid = a.gireturid
                        left join giheader c on c.giheaderid = b.giheaderid
                        left join soheader d on d.soheaderid = c.soheaderid
                        left join addressbook e on e.addressbookid = d.addressbookid
						left join invoice f on f.giheaderid=c.giheaderid
                        ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.notagirid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('notagir');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety(), 'No ');
      $this->pdf->text(30, $this->pdf->gety(), ': ' . $row['notagirno']);
      $this->pdf->text(60, $this->pdf->gety(), 'Tgl ');
      $this->pdf->text(70, $this->pdf->gety(), ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(100, $this->pdf->gety(), 'No. Reff. ');
      $this->pdf->text(120, $this->pdf->gety(), ': ' . $row['invoiceno']);
      $this->pdf->text(150, $this->pdf->gety(), 'Customer ');
      $this->pdf->text(170, $this->pdf->gety(), ': ' . $row['customer']);
      $sql1        = "select b.productname, a.qty, c.uomcode, a.price, a.qty*a.price as jumlah
                    from notagirpro a
                    left join product b on b.productid = a.productid
                    left join unitofmeasure c on c.unitofmeasureid = a.uomid 
                    left join sloc e on e.slocid = a.slocid
                    where notagirid = " . $row['notagirid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totaljumlah = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 3);
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
        30
      ));
      $this->pdf->colheader = array(
        'No',
        'Nama Barang',
        'Qty',
        'Unit',
        'Harga',
        'Jumlah'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'C',
        'R',
        'R'
      );
      $i                         = 0;
      foreach ($dataReader1 as $row1) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($row1['price']),
          Yii::app()->format->formatCurrency($row1['jumlah'])
        ));
        $totaljumlah += $row1['jumlah'];
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Total',
        Yii::app()->format->formatCurrency($totaljumlah)
      ));
      $sql2        = "select distinct b.accountname,a.debet,a.credit,c.currencyname,a.itemnote
                            from notagiracc a
                            left join account b on b.accountid = a.accountid
                            left join currency c on c.currencyid = a.currencyid
                            where a.notagirid = " . $row['notagirid'];
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      $totaldebet  = 0;
      $totalcredit = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 5);
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
        60,
        25,
        25,
        25,
        50
      ));
      $this->pdf->colheader = array(
        'No',
        'Akun',
        'Debet',
        'Credit',
        'Mata Uang',
        'Keterangan'
      );
      $this->pdf->RowHeader();
      $this->pdf->coldetailalign = array(
        'L',
        'L',
        'R',
        'R',
        'C',
        'L'
      );
      $i                         = 0;
      foreach ($dataReader2 as $row2) {
        $i = $i + 1;
        $this->pdf->row(array(
          $i,
          $row2['accountname'],
          Yii::app()->format->formatCurrency($row2['debet']),
          Yii::app()->format->formatCurrency($row2['credit']),
          $row2['currencyname'],
          $row2['itemnote']
        ));
        $totaldebet += $row2['debet'];
        $totalcredit += $row2['credit'];
      }
      $this->pdf->row(array(
        '',
        'Total',
        Yii::app()->format->formatCurrency($totaldebet),
        Yii::app()->format->formatCurrency($totalcredit),
        '',
        ''
      ));
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
      $this->pdf->checkNewPage(30);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '    Admin AR');
      $this->pdf->text(55, $this->pdf->gety() + 25, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
}