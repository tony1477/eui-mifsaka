<?php
class legaldocController extends Controller {
	public $menuname = 'legaldoc';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$legaldocid = isset ($_POST['legaldocid']) ? $_POST['legaldocid'] : '';
		$doctype = isset ($_POST['doctype']) ? $_POST['doctype'] : '';
		$docname = isset ($_POST['docname']) ? $_POST['docname'] : '';
		$docno = isset ($_POST['docno']) ? $_POST['docno'] : '';
		$doccompany = isset ($_POST['doccompany']) ? $_POST['doccompany'] : '';
		$storagedoc = isset ($_POST['storagedoc']) ? $_POST['storagedoc'] : '';
		$legaldocid = isset ($_GET['q']) ? $_GET['q'] : $legaldocid;
		$doctype = isset ($_GET['q']) ? $_GET['q'] : $doctype;
		$docname = isset ($_GET['q']) ? $_GET['q'] : $docname;
		$docno = isset ($_GET['q']) ? $_GET['q'] : $docno;
		$doccompany = isset ($_GET['q']) ? $_GET['q'] : $doccompany;
		$storagedoc = isset ($_GET['q']) ? $_GET['q'] : $storagedoc;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'legaldocid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('legaldoc t')
				->leftjoin('doctype a','a.doctypeid = t.doctypeid')
				->leftjoin('storagedoc b','b.storagedocid = t.storagedocid')
				->leftjoin('company c','c.companyid = t.doccompanyid')
				->where('((t.legaldocid like :legaldocid) or (t.docname like :docname) or (a.doctypename like :doctypename))',
					array(':legaldocid'=>'%'.$legaldocid.'%',':docname'=>'%'.$docname.'%',':doctypename'=>'%'.$doctype.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('legaldoc t')
                ->leftjoin('doctype a','a.doctypeid = t.doctypeid')
				->leftjoin('storagedoc b','b.storagedocid = t.storagedocid')
				->leftjoin('company c','c.companyid = t.doccompanyid')
				->where("(coalesce(t.legaldocid,'') like :legaldocid) and (coalesce(t.docname,'') like :docname) and (coalesce(a.doctypename,'') like :doctype) and (coalesce(t.docno,'') like :docno) and (coalesce(c.companyname,'') like :doccompany) and (coalesce(b.storagedocname,'') like :storagedoc) ",
					array(':legaldocid'=>'%'.$legaldocid.'%',':docname'=>'%'.$docname.'%',':doctype'=>'%'.$doctype.'%',':docno'=>'%'.$docno.'%',':doccompany'=>'%'.$doccompany.'%',':storagedoc'=>'%'.$storagedoc.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, a.doctypename, b.storagedocname, c.companyname')	
				->from('legaldoc t')
                ->leftjoin('doctype a','a.doctypeid = t.doctypeid')
				->leftjoin('storagedoc b','b.storagedocid = t.storagedocid')
				->leftjoin('company c','c.companyid = t.doccompanyid')
				->where('((t.legaldocid like :legaldocid) or (t.docname like :docname) ) or (a.doctypename like :doctypename) ',
					array(':legaldocid'=>'%'.$legaldocid.'%',':docname'=>'%'.$docname.'%',':doctypename'=>'%'.$doctype.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select("t.*, a.doctypename, b.storagedocname, c.companyname")	
				->from('legaldoc t')
				->leftjoin('doctype a','a.doctypeid = t.doctypeid')
				->leftjoin('storagedoc b','b.storagedocid = t.storagedocid')
				->leftjoin('company c','c.companyid = t.doccompanyid')
				->where("(coalesce(t.legaldocid,'') like :legaldocid) and (coalesce(t.docname,'') like :docname) and (coalesce(a.doctypename,'') like :doctype) and (coalesce(t.docno,'') like :docno) and (coalesce(c.companyname,'') like :doccompany) and (coalesce(b.storagedocname,'') like :storagedoc) ",
					array(':legaldocid'=>'%'.$legaldocid.'%',':docname'=>'%'.$docname.'%',':doctype'=>'%'.$doctype.'%',':docno'=>'%'.$docno.'%',':doccompany'=>'%'.$doccompany.'%',':storagedoc'=>'%'.$storagedoc.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'legaldocid'=>$data['legaldocid'],
				'docname'=>$data['docname'],
				'doctypeid'=>$data['doctypeid'],
				'doctypename'=>$data['doctypename'],
				'docno'=>$data['docno'],
				'docdate'=>date(Yii::app()->params['dateviewfromdb'],strtotime($data['docdate'])),
				'doccompanyid'=>$data['doccompanyid'],
				'companyname'=>$data['companyname'],
				'storagedocid'=>$data['storagedocid'],
				'storagedocname'=>$data['storagedocname'],
				'description'=>$data['description'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertlegaldoc(:vdoctype,:vdocname,:vdocno,:vdocdate,:vdoccompanyid,:vstoragedocid,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatelegaldoc(:vid,:vdoctype,:vdocname,:vdocno,:vdocdate,:vdoccompanyid,:vstoragedocid,:vdescription,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vdoctype',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdocname',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdocno',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vdocdate',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vdoccompanyid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vstoragedocid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-legaldoc"]["name"]);
		if (move_uploaded_file($_FILES["file-legaldoc"]["tmp_name"], $target_file)) {
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
					$doctype = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $doctypeid = Yii::app()->db->createCommand("select doctypeid from doctype where doctypename = '".$doctype."'")->queryScalar();
					$docname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$docno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$docdate = date(Yii::app()->params['datetodb'],strtotime($objWorksheet->getCellByColumnAndRow(4, $row)->getValue()));
					$company = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $companyid = Yii::app()->db->createCommand("select companyid from company where companyname = '".$company."'")->queryScalar();
					$storagedoc = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $storagedocid = Yii::app()->db->createCommand("select storagedocid from storagedoc where storagedocname = '".$storagedoc."'")->queryScalar();
                    $description = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$this->ModifyData($connection,array($id,$doctypeid,$docname,$docno,$docdate,$companyid,$storagedocid,$description));
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
			$this->ModifyData($connection,array((isset($_POST['legaldocid'])?$_POST['legaldocid']:''),$_POST['doctypeid'],$_POST['docname'],$_POST['docno'],date(Yii::app()->params['datetodb'],strtotime($_POST['docdate'])),$_POST['doccompanyid'],$_POST['storagedocid'],$_POST['description']));
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
				$sql = 'call Purgelegaldoc(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();				
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else
		{
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
        parent::actionDownload();
		$this->pdf->AddPage('L',array(210,400));
        
        $sql = 'select a.*, b.doctypename, c.companyname, d.storagedocname
                from legaldoc a
                left join doctype b on b.doctypeid = a.doctypeid
                left join company c on c.companyid = a.doccompanyid
                left join storagedoc d on d.storagedocid = a.storagedocid ';
        
        $legaldocid = filter_input(INPUT_GET,'legaldocid');
		$docname = filter_input(INPUT_GET,'docname');
		$sql .= " where coalesce(a.legaldocid,'') like '%".$legaldocid."%' 
			and coalesce(a.docname,'') like '%".$docname."%'"; 
		if ($_GET['id'] !== '') {
            $sql = $sql . " and a.legaldocid in (" . $_GET['id'] . ")";
        }
        
        $this->pdf->colalign = array(
				'C',
				'C',
				'L',
				'C',
				'C',
				'C',
				'C',
				'C'
			);
			$this->pdf->setFont('Arial', 'B', 8);
			$this->pdf->setwidths(array(
				7,
				40,
				120,
				28,
				20,
				50,
				20,
				125
			));
			$this->pdf->colheader = array(
				'ID',
				'Jenis',
				'Nama Dokumen',
				'No Dokumen',
				'Tgl Dokumen',
				'Perusahaan',
				'Lokasi',
				'Keterangan'
			);
			$this->pdf->RowHeader();
			$this->pdf->setFont('Arial', '', 8);
			$this->pdf->coldetailalign = array(
				'R',
				'L',
				'L',
				'C',
				'R',
				'L',
				'L',
				'L'
			);
        $i=1;
        $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
        foreach($dataReader as $row)
        {
            $this->pdf->row(array(
                $row['legaldocid'],
                $row['doctypename'],
                $row['docname'],
                $row['docno'],
                $row['docdate'],
                $row['companyname'],
                $row['storagedocname'],
                $row['description']
            ));   
        }
        
        $this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='legaldoc';
		parent::actionDownxls();
		
        $sql = 'select a.*, b.doctypename, c.companyname, d.storagedocname
                from legaldoc a
                left join doctype b on b.doctypeid = a.doctypeid
                left join company c on c.companyid = a.doccompanyid
                left join storagedoc d on d.storagedocid = a.storagedocid ';
        
        $legaldocid = filter_input(INPUT_GET,'legaldocid');
		$docname = filter_input(INPUT_GET,'docname');
		$sql .= " where coalesce(a.legaldocid,'') like '%".$legaldocid."%' 
			and coalesce(a.docname,'') like '%".$docname."%'"; 
		if ($_GET['id'] !== '') {
            $sql = $sql . " and a.legaldocid in (" . $_GET['id'] . ")";
        }
		else
		{
			$sql = $sql . "order by docname asc ";
		}
		
        $dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['legaldocid'])
				->setCellValueByColumnAndRow(1,$i,$row1['doctypename'])							
				->setCellValueByColumnAndRow(2,$i,$row1['docname'])
				->setCellValueByColumnAndRow(3,$i,$row1['docno'])
				->setCellValueByColumnAndRow(4,$i,$row1['docdate'])
				->setCellValueByColumnAndRow(5,$i,$row1['companyname'])
				->setCellValueByColumnAndRow(6,$i,$row1['storagedocname'])
				->setCellValueByColumnAndRow(7,$i,$row1['description']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
}
