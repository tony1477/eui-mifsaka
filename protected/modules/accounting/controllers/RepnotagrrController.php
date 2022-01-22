<?php
class RepnotagrrController extends Controller
{
  public $menuname = 'repnotagrr';
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
    $notagrreturid   = isset($_POST['notagrreturid']) ? $_POST['notagrreturid'] : '';
    $notagrreturno   = isset($_POST['notagrreturno']) ? $_POST['notagrreturno'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $grreturid       = isset($_POST['grreturid']) ? $_POST['grreturid'] : '';
    $poheaderid      = isset($_POST['poheaderid']) ? $_POST['poheaderid'] : '';
    $addressbook     = isset($_POST['addressbook']) ? $_POST['addressbook'] : '';
    $company     = isset($_POST['company']) ? $_POST['company'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'notagrreturid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagrretur t')
		->leftjoin('grretur a', 'a.grreturid = t.grreturid')
		->leftjoin('poheader b', 'b.poheaderid = a.poheaderid')
		->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')
		->leftjoin('company c', 'c.companyid = t.companyid')
		->where("((coalesce(t.docdate,'') like :docdate) and
			(coalesce(t.notagrreturno,'') like :notagrreturno) and
			(coalesce(t.notagrreturid,'') like :notagrreturid) and
			(coalesce(b.pono,'') like :poheaderid) and
			(coalesce(d.fullname,'') like :addressbookid) and
			(coalesce(c.companyname,'') like :company) and
			(coalesce(a.grreturno,'') like :grreturid))
			and t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':notagrreturno' => '%' . $notagrreturno . '%',
      ':notagrreturid' => '%' . $notagrreturid . '%',
      ':poheaderid' => '%' . $poheaderid  . '%',
      ':addressbookid' => '%' . $addressbook  . '%',
      ':company' => '%' . $company  . '%',
      ':grreturid' => '%' . $grreturid . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.grreturno,b.poheaderid,b.pono,c.companyname,d.fullname,d.addressbookid')
		->from('notagrretur t')
		->leftjoin('grretur a', 'a.grreturid = t.grreturid')
		->leftjoin('poheader b', 'b.poheaderid = a.poheaderid')
		->leftjoin('addressbook d', 'd.addressbookid = b.addressbookid')
		->leftjoin('company c', 'c.companyid = t.companyid')
		->where("((coalesce(t.docdate,'') like :docdate) and
			(coalesce(t.notagrreturno,'') like :notagrreturno) and
			(coalesce(t.notagrreturid,'') like :notagrreturid) and
			(coalesce(b.pono,'') like :poheaderid) and
			(coalesce(d.fullname,'') like :addressbookid) and
			(coalesce(c.companyname,'') like :company) and
			(coalesce(a.grreturno,'') like :grreturid))
			and t.companyid in (".getUserObjectValues('company').")", array(
      ':docdate' => '%' . $docdate . '%',
      ':notagrreturno' => '%' . $notagrreturno . '%',
      ':notagrreturid' => '%' . $notagrreturid . '%',
      ':poheaderid' => '%' . $poheaderid  . '%',
      ':addressbookid' => '%' . $addressbook  . '%',
      ':company' => '%' . $company  . '%',
      ':grreturid' => '%' . $grreturid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrreturid' => $data['notagrreturid'],
        'notagrreturno' => $data['notagrreturno'],
        'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
        'grreturid' => $data['grreturid'],
        'grreturno' => $data['grreturno'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'poheaderid' => $data['poheaderid'],
        'pono' => $data['pono'],
        'addressbookid' => $data['addressbookid'],
        'fullname' => $data['fullname'],
        'headernote' => $data['headernote'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusnotagrretur' => $data['statusname']
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagrrpro t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('grreturdetail b', 'b.grreturdetailid=t.grreturdetailid')->leftjoin('grdetail c', 'c.grdetailid=b.grdetailid')->leftjoin('podetail d', 'd.podetailid=c.podetailid')->leftjoin('unitofmeasure e', 'e.unitofmeasureid=b.uomid')->leftjoin('currency f', 'f.currencyid=t.currencyid')->where('(notagrreturid = :notagrreturid)', array(
      ':notagrreturid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,e.uomcode,(t.qty*t.price) as total,f.currencyname')->from('notagrrpro t')->leftjoin('product a', 'a.productid=t.productid')->leftjoin('grreturdetail b', 'b.grreturdetailid=t.grreturdetailid')->leftjoin('grdetail c', 'c.grdetailid=b.grdetailid')->leftjoin('podetail d', 'd.podetailid=c.podetailid')->leftjoin('unitofmeasure e', 'e.unitofmeasureid=b.uomid')->leftjoin('currency f', 'f.currencyid=t.currencyid')->where('(notagrreturid = :notagrreturid)', array(
      ':notagrreturid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrrproid' => $data['notagrrproid'],
        'notagrreturid' => $data['notagrreturid'],
        'grreturdetailid' => $data['grreturdetailid'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'price' => Yii::app()->format->formatCurrency($data['price']),
        'currencyid' => $data['currencyid'],
        'currencyname' => $data['currencyname'],
        'currencyrate' => Yii::app()->format->formatCurrency($data['currencyrate']),
        'total' => Yii::app()->format->formatCurrency($data['total'])
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
		$sql = "select sum(qty*price) as jumlah from notagrrpro where notagrreturid = ".$id;
		$cmd = Yii::app()->db->createCommand($sql)->queryScalar();
		$footer[] = array(
      'productname' => 'Total',
      'total' => Yii::app()->format->formatCurrency($cmd)
    );
		$sql = "select b.uomcode, sum(t.qty) as jumlah 
		from notagrrpro t 
		left join grreturdetail a on a.grreturdetailid = t.grreturdetailid 
		left join unitofmeasure b on b.unitofmeasureid = a.uomid 
		where notagrreturid = ".$id." group by b.uomcode";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
		$footer[] = array(
      'productname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($data['jumlah']),
      'uomcode' => $data['uomcode'],
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
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('notagrracc t')->leftjoin('account a', 'a.accountid=t.accountid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->where('(notagrreturid = :notagrreturid)', array(
      ':notagrreturid' => $id
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.accountname,b.currencyname')->from('notagrracc t')->leftjoin('account a', 'a.accountid=t.accountid')->leftjoin('currency b', 'b.currencyid=t.currencyid')->where('(notagrreturid = :notagrreturid)', array(
      ':notagrreturid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'notagrraccid' => $data['notagrraccid'],
        'notagrreturid' => $data['notagrreturid'],
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
    echo CJSON::encode($result);
  }
  public function actionDownPDF1()
  {
    parent::actionDownload();
    $sql = "select notagrreturid,a.companyid,a.notagrreturno,a.docdate,a.headernote,c.grreturno,d.pono,e.fullname
                        from notagrretur a 
						left join grretur c on c.grreturid = a.grreturid
                        left join poheader d on d.poheaderid = c.poheaderid
                        left join addressbook e on e.addressbookid = d.addressbookid			
                        ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.notagrreturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('notagrretur');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['notagrreturno']);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'No. Reff. ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . $row['grreturno'] . ' / ' . $row['pono']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(120, $this->pdf->gety() + 6, 'Supplier ');
      $this->pdf->text(140, $this->pdf->gety() + 6, ': ' . $row['fullname']);
      $sql1        = "select b.productname, a.qty, c.uomcode, concat(e.sloccode,' - ',e.description) as sloccode,a.price,a.qty*a.price as jumlah
        from notagrrpro a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        left join sloc e on e.slocid = a.slocid
        where notagrreturid = " . $row['notagrreturid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      $totaljumlah = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 10);
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
        90,
        20,
        15,
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
      $sql2        = "select b.accountname,a.debet,a.credit,c.currencyname,a.itemnote
                from notagrracc a
                left join account b on b.accountid = a.accountid
                left join currency c on c.currencyid = a.currencyid
                where notagrreturid = " . $row['notagrreturid'];
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      $totaldebet  = 0;
      $totalcredit = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 12);
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
          Yii::app()->format->formatNumber($row2['debet']),
          Yii::app()->format->formatNumber($row2['credit']),
          $row2['currencyname'],
          $row2['itemnote']
        ));
        $totaldebet += $row1['debet'];
        $totalcredit += $row1['credit'];
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
      $this->pdf->checkNewPage(40);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '    Admin AP');
      $this->pdf->text(55, $this->pdf->gety() + 25, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select notagrreturid,a.companyid,a.notagrreturno,a.docdate,a.headernote,c.grreturno,d.pono,e.fullname
                        from notagrretur a 
						left join grretur c on c.grreturid = a.grreturid
                        left join poheader d on d.poheaderid = c.poheaderid
                        left join addressbook e on e.addressbookid = d.addressbookid			
                        ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.notagrreturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    foreach ($dataReader as $row) {
      $this->pdf->companyid = $row['companyid'];
    }
    $this->pdf->title = GetCatalog('notagrretur');
    $this->pdf->AddPage('P', array(
      220,
      140
    ));
    $this->pdf->AliasNbPages();
    $this->pdf->setFont('Arial');
    foreach ($dataReader as $row) {
      $this->pdf->SetFontSize(8);
      $this->pdf->text(10, $this->pdf->gety() + 2, 'No ');
      $this->pdf->text(30, $this->pdf->gety() + 2, ': ' . $row['notagrreturno']);
      $this->pdf->text(120, $this->pdf->gety() + 2, 'No. Reff. ');
      $this->pdf->text(140, $this->pdf->gety() + 2, ': ' . $row['grreturno'] . ' / ' . $row['pono']);
      $this->pdf->text(10, $this->pdf->gety() + 6, 'Tgl ');
      $this->pdf->text(30, $this->pdf->gety() + 6, ': ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate'])));
      $this->pdf->text(120, $this->pdf->gety() + 6, 'Supplier ');
      $this->pdf->text(140, $this->pdf->gety() + 6, ': ' . $row['fullname']);
      $sql1        = "select b.productname, a.qty, c.uomcode, concat(e.sloccode,' - ',e.description) as sloccode,a.price,a.qty*a.price as jumlah
        from notagrrpro a
        left join product b on b.productid = a.productid
        left join unitofmeasure c on c.unitofmeasureid = a.uomid 
        left join sloc e on e.slocid = a.slocid
        where notagrreturid = " . $row['notagrreturid'];
      $command1    = $this->connection->createCommand($sql1);
      $dataReader1 = $command1->queryAll();
      
      $sql_tax = "select taxvalue, taxcode
      from notagrrpro t 
      join grreturdetail b on b.grreturdetailid = t.grreturdetailid 
      join grdetail c on c.grdetailid = b.grdetailid 
      join podetail d on d.podetailid = c.podetailid
      join poheader p on p.poheaderid = d.poheaderid
      join tax tx on tx.taxid = p.taxid
      where notagrreturid = ".$_GET['id'];
      $taxvalue = Yii::app()->db->createCommand($sql_tax)->queryRow();
        
        
      $totaljumlah = 0;
      $i           = 0;
      $ppn = 0;
      $this->pdf->sety($this->pdf->gety() + 10);
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
        90,
        20,
        15,
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
        if($taxvalue['taxvalue']!=0)
        {
            $price = 100/(100+$taxvalue['taxvalue'])*$row1['price'];
            $jumlah = $row1['qty']*$price;
        }
        else
        {
            $price = $row1['price'];
            $jumlah = $row1['qty']*$price;
        }
        $this->pdf->row(array(
          $i,
          $row1['productname'],
          Yii::app()->format->formatCurrency($row1['qty']),
          $row1['uomcode'],
          Yii::app()->format->formatCurrency($price),
          Yii::app()->format->formatCurrency($jumlah)
        ));
        $totaljumlah += $jumlah;
      }
      $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Sub Total',
        Yii::app()->format->formatCurrency($totaljumlah)
      ));
        $ppn = ($taxvalue['taxvalue']*$totaljumlah)/100;
    $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'PPN '.$taxvalue['taxvalue'].'%',
        Yii::app()->format->formatCurrency($ppn)
      ));
    $this->pdf->row(array(
        '',
        '',
        '',
        '',
        'Grand Total',
        Yii::app()->format->formatCurrency($ppn+$totaljumlah)
      ));
        
      $sql2        = "select b.accountname,a.debet,a.credit,c.currencyname,a.itemnote
                from notagrracc a
                left join account b on b.accountid = a.accountid
                left join currency c on c.currencyid = a.currencyid
                where notagrreturid = " . $row['notagrreturid'];
      $command2    = $this->connection->createCommand($sql2);
      $dataReader2 = $command2->queryAll();
      $totaldebet  = 0;
      $totalcredit = 0;
      $i           = 0;
      $this->pdf->sety($this->pdf->gety() + 12);
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
          Yii::app()->format->formatNumber($row2['debet']),
          Yii::app()->format->formatNumber($row2['credit']),
          $row2['currencyname'],
          $row2['itemnote']
        ));
        $totaldebet += $row1['debet'];
        $totalcredit += $row1['credit'];
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
      $this->pdf->checkNewPage(40);
      $this->pdf->sety($this->pdf->gety() + 10);
      $this->pdf->text(15, $this->pdf->gety(), '  Dibuat oleh,');
      $this->pdf->text(55, $this->pdf->gety(), ' Diperiksa oleh,');
      $this->pdf->text(96, $this->pdf->gety(), '  Diketahui oleh,');
      $this->pdf->text(15, $this->pdf->gety() + 22, '........................');
      $this->pdf->text(55, $this->pdf->gety() + 22, '.........................');
      $this->pdf->text(96, $this->pdf->gety() + 22, '...........................');
      $this->pdf->text(15, $this->pdf->gety() + 25, '    Admin AP');
      $this->pdf->text(55, $this->pdf->gety() + 25, '     Controller');
      $this->pdf->text(96, $this->pdf->gety() + 25, 'Chief Accounting');
    }
    $this->pdf->Output();
  }
	public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select docdate,grreturid,recordstatus
				from notagrretur a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.notagrreturid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(1, 1, GetCatalog('grreturid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['grreturid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="notagrretur.xlsx"');
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