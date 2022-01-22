<?php
class ReportprController extends Controller {
  public $menuname = 'reportpr';
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
    $prheaderid       = isset($_POST['prheaderid']) ? $_POST['prheaderid'] : '';
    $prdate           = isset($_POST['prdate']) ? $_POST['prdate'] : '';
    $prno             = isset($_POST['prno']) ? $_POST['prno'] : '';
    $dano             = isset($_POST['dano']) ? $_POST['dano'] : '';
    $headernote       = isset($_POST['headernote']) ? $_POST['headernote'] : '';
    $slocid           = isset($_POST['sloccode']) ? $_POST['sloccode'] : '';
    $deliveryadviceid = isset($_POST['dano']) ? $_POST['dano'] : '';
    $recordstatus     = isset($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
    $page             = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows             = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort             = isset($_POST['sort']) ? strval($_POST['sort']) : 'prheaderid';
    $order            = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset           = ($page - 1) * $rows;
    $result           = array();
    $row              = array();
    $cmd              = Yii::app()->db->createCommand()->select('count(1) as total')->from('prheader t')->join('deliveryadvice j', 'j.deliveryadviceid = t.deliveryadviceid')->join('sloc k', 'k.slocid = j.slocid')->join('plant l', 'l.plantid = k.plantid')->join('company m', 'm.companyid = l.companyid')->where("
    ((coalesce(prdate,'') like :prdate) and 
						(coalesce(prno,'') like :prno) and 
						(coalesce(t.headernote,'') like :headernote) and 
						(coalesce(k.sloccode,'') like :slocid) and 
						(coalesce(j.dano,'') like :deliveryadviceid))
						and m.companyid in (".getUserObjectValues('company').")", array(
      ':prdate' => '%' . $prdate . '%',
      ':prno' => '%' . $prno . '%',
      ':headernote' => '%' . $headernote . '%',
      ':slocid' => '%' . $slocid . '%',
      ':deliveryadviceid' => '%' . $deliveryadviceid . '%'
    ))->queryScalar();
    $result['total']  = $cmd;
    $cmd              = Yii::app()->db->createCommand()->select('t.*,k.sloccode,j.dano,k.description as slocdesc,k.slocid,
		(
		select case when sum(qty) > sum(poqty) then 1 else 0 end
		from prmaterial z 
		where z.prheaderid = t.prheaderid 
		) as warna
		')->from('prheader t')->join('deliveryadvice j', 'j.deliveryadviceid = t.deliveryadviceid')->join('sloc k', 'k.slocid = j.slocid')->join('plant l', 'l.plantid = k.plantid')->join('company m', 'm.companyid = l.companyid')->where("
     ((coalesce(prdate,'') like :prdate) and 
						(coalesce(prno,'') like :prno) and 
						(coalesce(t.headernote,'') like :headernote) and 
						(coalesce(k.sloccode,'') like :slocid) and 
						(coalesce(j.dano,'') like :deliveryadviceid))
						and m.companyid in (".getUserObjectValues('company').")", array(
      ':prdate' => '%' . $prdate . '%',
      ':prno' => '%' . $prno . '%',
      ':headernote' => '%' . $headernote . '%',
      ':slocid' => '%' . $slocid . '%',
      ':deliveryadviceid' => '%' . $deliveryadviceid . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'prheaderid' => $data['prheaderid'],
        'prdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['prdate'])),
        'prno' => $data['prno'],
        'headernote' => $data['headernote'],
        'warna' => $data['warna'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'].' - '.$data['slocdesc'],
        'deliveryadviceid' => $data['deliveryadviceid'],
        'dano' => $data['dano'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusprheader' => $data['statusname']
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
    $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'prmaterialid';
    $order  = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset = ($page - 1) * $rows;
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('prmaterial t')->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.prheaderid = :prheaderid', array(
        ':prheaderid' => $id
      ))->queryScalar();
    } else if (isset($_GET['popr'])) {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('prmaterial t')->leftjoin('prheader ef', 'ef.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where("ef.recordstatus in (select b.wfbefstat
				from workflow a
				inner join wfgroup b on b.workflowid = a.workflowid
				inner join groupaccess c on c.groupaccessid = b.groupaccessid
				inner join usergroup d on d.groupaccessid = c.groupaccessid
				inner join useraccess e on e.useraccessid = d.useraccessid
				where upper(a.wfname) = upper('listpr') and upper(e.username)=upper('" . Yii::app()->user->name . "') and
								ef.slocid in (select gm.menuvalueid from groupmenuauth gm
								inner join menuauth ma on ma.menuauthid = gm.menuauthid
								where upper(ma.menuobject) = upper('sloc') and gm.groupaccessid = c.groupaccessid)
				and ef.prno is not null) and t.prmaterialid not in 
				(
				select zz.prmaterialid
				from podetail zz 
				where zz.productid = t.productid and zz.prmaterialid = t.prmaterialid
				and zz.poqty <= t.qty
				)")->queryScalar();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('prmaterial t')->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.prheaderid = :prheaderid', array(
        ':prheaderid' => $id
      ))->queryScalar();
    }
    $result['total'] = $cmd;
    if (isset($_GET['combo'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.description,e.prno')->from('prmaterial t')->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.prheaderid = :prheaderid', array(
        ':prheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else if (isset($_GET['popr'])) {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.description,ef.prno')->from('prmaterial t')->leftjoin('prheader ef', 'ef.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where("ef.recordstatus in (select b.wfbefstat
from workflow a
inner join wfgroup b on b.workflowid = a.workflowid
inner join groupaccess c on c.groupaccessid = b.groupaccessid
inner join usergroup d on d.groupaccessid = c.groupaccessid
inner join useraccess e on e.useraccessid = d.useraccessid
where upper(a.wfname) = upper('listpr') and upper(e.username)=upper('" . Yii::app()->user->name . "') and
				ef.slocid in (select gm.menuvalueid from groupmenuauth gm
				inner join menuauth ma on ma.menuauthid = gm.menuauthid
				where upper(ma.menuobject) = upper('sloc') and gm.groupaccessid = c.groupaccessid)
and ef.prno is not null) and t.prmaterialid not in 
(
select zz.prmaterialid
from podetail zz 
where zz.productid = t.productid and zz.prmaterialid = t.prmaterialid
and zz.poqty <= t.qty
)")->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    } else {
      $cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.description,e.prno')->from('prmaterial t')->leftjoin('prheader e', 'e.prheaderid = t.prheaderid')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('requestedby c', 'c.requestedbyid = t.requestedbyid')->leftjoin('deliveryadvicedetail d', 'd.deliveryadvicedetailid = t.deliveryadvicedetailid')->where('t.prheaderid = :prheaderid', array(
        ':prheaderid' => $id
      ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    foreach ($cmd as $data) {
			if ($data['qty'] > $data['poqty']) {
				$wqty = 1;
			} else {
				$wqty = 0;
			}
      $row[] = array(
        'prmaterialid' => $data['prmaterialid'],
        'prheaderid' => $data['prheaderid'],
        'prno' => $data['prno'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'unitofmeasureid' => $data['unitofmeasureid'],
        'uomcode' => $data['uomcode'],
        'wqty' => $wqty,
        'requestedbyid' => $data['requestedbyid'],
        'description' => $data['description'],
        'poqty' => Yii::app()->format->formatNumber($data['poqty']),
        'reqdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['reqdate'])),
        'itemtext' => $data['itemtext']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    $sql = "select a.uomcode,sum(qty) as qty, sum(poqty) as poqty 
		from prmaterial t 
		join unitofmeasure a on a.unitofmeasureid = t.unitofmeasureid 
		where prheaderid = ".$id." group by a.uomcode";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cmd as $data) {
		$footer[] = array(
      'productname' => 'Total',
      'qty' => Yii::app()->format->formatNumber($data['qty']),
      'poqty' => Yii::app()->format->formatNumber($data['poqty']),
      'uomcode' => $data['uomcode'],
    );
		}
    $result = array_merge($result, array(
      'footer' => $footer
    ));
    echo CJSON::encode($result);
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $sql = "select prdate,prno,headernote,slocid,deliveryadviceid,recordstatus
				from prheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.prheaderid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('prheader');
    $this->pdf->AddPage('P');
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    $this->pdf->colheader = array(
      getCatalog('prdate'),
      getCatalog('prno'),
      getCatalog('headernote'),
      getCatalog('slocid'),
      getCatalog('deliveryadviceid'),
      getCatalog('recordstatus')
    );
    $this->pdf->setwidths(array(
      40,
      40,
      40,
      40,
      40,
      40
    ));
    $this->pdf->Rowheader();
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L'
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['prdate'],
        $row1['prno'],
        $row1['headernote'],
        $row1['slocid'],
        $row1['deliveryadviceid'],
        $row1['recordstatus']
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls() {
    parent::actionDownXls();
    $sql = "select prdate,prno,headernote,slocid,deliveryadviceid,recordstatus
				from prheader a ";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.prheaderid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $i          = 1;
    $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, getCatalog('prdate'))->setCellValueByColumnAndRow(1, 1, getCatalog('prno'))->setCellValueByColumnAndRow(2, 1, getCatalog('headernote'))->setCellValueByColumnAndRow(3, 1, getCatalog('slocid'))->setCellValueByColumnAndRow(4, 1, getCatalog('deliveryadviceid'))->setCellValueByColumnAndRow(5, 1, getCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['prdate'])->setCellValueByColumnAndRow(1, $i + 1, $row1['prno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(3, $i + 1, $row1['slocid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['deliveryadviceid'])->setCellValueByColumnAndRow(5, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    $this->getFooterXls($this->phpExcel);
  }
}
