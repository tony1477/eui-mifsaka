<?php
class IssueController extends Controller
{
	public $menuname = 'issue';
	public function actionIndex()
	{
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actionUpload()
	{
		parent::actionUpload();
		$target_file = dirname('_FILES_').'/uploads/' . basename($_FILES["file-issue"]["name"]);
		if (move_uploaded_file($_FILES["file-issue"]["tmp_name"], $target_file)) {
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
					$company = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$company."'")->queryScalar();
                    $foreman = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$employeeid = Yii::app()->db->createCommand("select employeeid from employee where fullname = '".$foreman."'")->queryScalar();
                    $docdate = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$jumlah = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$cycletime = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$this->ModifyData($connection,array($id,$companyid,$docdate,$employeeid,$jumlah,$description,$cycletime));
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
  private function ModifyData($connection,$arraydata)
	{
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertissue(:vcompanyid,:vdocdate,:vemployeeid,:vjumlah,:vdescription,:vcycletime,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateissue(:vid,:vcompanyid,:vdocdate,:vemployeeid,:vjumlah,:vdescription,:vcycletime,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$this->DeleteLock($this->menuname, $arraydata[0]);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
		}
        $command->bindvalue(':vcompanyid',$arraydata[1],PDO::PARAM_STR);
        $command->bindvalue(':vdocdate',date(Yii::app()->params['datetodb'],strtotime($arraydata[2])),PDO::PARAM_STR);
        $command->bindvalue(':vemployeeid',$arraydata[3],PDO::PARAM_STR);
        $command->bindvalue(':vjumlah',$arraydata[4],PDO::PARAM_STR);
        $command->bindvalue(':vdescription',$arraydata[5],PDO::PARAM_STR);
        $command->bindvalue(':vcycletime',$arraydata[6],PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
        
        /*
		$command->bindvalue(':vcompanyid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vemployeeid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vperioddate',date(Yii::app()->params['datetodb'],strtotime($arraydata[3])),PDO::PARAM_STR);
		$command->bindvalue(':vjumlah',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
        */
		$command->execute();
	}
	public function actionSave()
	{
		header("Content-Type: application/json");
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord']))
			{
				$sql = 'call Insertissue(:vcompanyid,:vdocdate,:vemployeeid,:vjumlah,:vdescription,:vcycletime,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateissue(:vid,:vcompanyid,:vdocdate,:vemployeeid,:vjumlah,:vdescription,:vcycletime,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['issueid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['issueid']);
			}
			$command->bindvalue(':vcompanyid',$_POST['companyid'],PDO::PARAM_STR);
			$command->bindvalue(':vdocdate',date(Yii::app()->params['datetodb'],strtotime($_POST['docdate'])),PDO::PARAM_STR);
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
			$command->bindvalue(':vjumlah',$_POST['jumlah'],PDO::PARAM_STR);
			$command->bindvalue(':vdescription',$_POST['description'],PDO::PARAM_STR);
			$command->bindvalue(':vcycletime',$_POST['cycletime'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();			
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
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
        $sql     = 'call ApproveIssue(:vid,:vcreatedby)';
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
		header("Content-Type: application/json");
		
		if (isset($_POST['id']))
		{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call DeleteIssue(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
                foreach ($id as $ids) {
                    $command->bindvalue(':vid',$ids,PDO::PARAM_STR);
                    $command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
                    $command->execute();
                }
				
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e)
			{
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else
		{
			GetMessage(true,'chooseone');
		}
	}
	public function search()
	{
		header("Content-Type: application/json");
		
		// search 
		$issueid = isset ($_POST['issueid']) ? $_POST['issueid'] : '';
		$company = isset ($_POST['company']) ? $_POST['company'] : '';
		$employee = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$issueid = isset ($_GET['q']) ? $_GET['q'] : $issueid;
		$company = isset ($_GET['q']) ? $_GET['q'] : $company;
		$employee = isset ($_GET['q']) ? $_GET['q'] : $employee;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'issueid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
                
        $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		if (isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
                    ->select('count(1) as total')	
                    ->from('issue t')
                    ->leftjoin('company a','a.companyid = t.companyid')
                    ->leftjoin('employee b','b.employeeid = t.employeeid')
                    ->where('((issueid like :issueid) or (fullname like :employee) or (description like :description)) 
                            and t.recordstatus =  ',
                    array(':issueid'=>'%'.$issueid.'%',':employee'=>'%'.$employee.'%',
                    ':description'=>'%'.$description.'%'
                                ))
                    ->queryRow();
		}
        /*
        else if(isset($_GET['opoutput']))
        {
            
			$cmd = Yii::app()->db->createCommand()
							->select('count(1) as total')	
							->from('issue t')
                            ->leftjoin('sloc a','a.slocid = t.slocid')
							->where('((issueid like :issueid) or (issuename like :issuename)) 
				                    and t.recordstatus = 1 and t.slocid='.$_REQUEST['slocid'],
												array(':issueid'=>'%'.$issueid.'%',':issuename'=>'%'.$issuename.'%',
										))
							->queryRow();
        }
        */
		else
		{
			$cmd = Yii::app()->db->createCommand()
                    ->select('count(1) as total')	
                    ->from('issue t')
                    ->leftjoin('company a','a.companyid = t.companyid')
                    ->leftjoin('employee b','b.employeeid = t.employeeid')
                    ->where("(coalesce(issueid,'') like :issueid) and (coalesce(fullname,'') like :employee) and (coalesce(description,'') like :description) and t.recordstatus in( ".getUserRecordStatus('listissue').")",
                    array(':issueid'=>'%'.$issueid.'%',':employee'=>'%'.$employee.'%',
                    ':description'=>'%'.$description.'%'
                    ))
                    ->queryRow();
		}
	
		$result['total'] = $cmd['total'];
		
		if (isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, a.companyname, b.fullname')	
				->from('issue t')
                ->leftjoin('company a','a.companyid = t.companyid')
                    ->leftjoin('employee b','b.employeeid = t.employeeid')
				->where('((issueid like :issueid) or (fullname like :employee) or (description like :description)) 
				and t.recordstatus = 1 ',
                array(':issueid'=>'%'.$issueid.'%',':employee'=>'%'.$employee.'%',
                ':description'=>'%'.$description.'%'
                ))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
        /*
        else if(isset($_GET['opoutput']))
        {
            $cmd = Yii::app()->db->createCommand()
				->select('t.*, a.description, a.description, a.slocid, concat(a.description,"-",a.description) as slocdesc')	
				->from('issue t')
                ->leftjoin('sloc a','a.slocid = t.slocid')
				->where('((issueid like :issueid) or (issuename like :issuename))
				and t.recordstatus = 1 and t.slocid='.$_REQUEST['slocid'],
                array(':issueid'=>'%'.$issueid.'%',':issuename'=>'%'.$issuename.'%',
														))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
        }
        */
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, a.companyname, b.fullname, (t.cycletime/60) as cycletimemin')	
				->from('issue t')
                ->leftjoin('company a','a.companyid = t.companyid')
                ->leftjoin('employee b','b.employeeid = t.employeeid')
				->where("(coalesce(issueid,'') like :issueid) and (coalesce(fullname,'') like :employee) and (coalesce(description,'') like :description) and t.recordstatus in (".getUserRecordStatus('listissue').")",
                array(':issueid'=>'%'.$issueid.'%',':employee'=>'%'.$employee.'%',
                ':description'=>'%'.$description.'%'
                ))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}

		foreach($cmd as $data)
		{	
			$row[] = array(
				'issueid'=>$data['issueid'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'docdate'=>$data['docdate'],
                'employeeid'=>$data['employeeid'],
				'fullname'=>$data['fullname'],
                'description'=>$data['description'],
                'cycletime'=>$data['cycletime'],
                'cycletimemin'=>Yii::app()->format->formatCurrency($data['cycletimemin']),
                'jumlah'=>Yii::app()->format->formatCurrency($data['jumlah']),
				'recordstatus'=>$data['recordstatus'],
				'statusname'=>$data['statusname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select issueid,
            fullname,a.employeeid,docdate,a.companyid,companyname,description,cycletime,statusname,a.recordstatus, a.jumlah
            from issue a
            left join company b on b.companyid = a.companyid
            left join employee c on c.employeeid = a.employeeid ";
		if ($_GET['id'] !== '') 
		{
            $sql = $sql . "where a.issueid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by issueid desc ";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=GetCatalog('issue');
		$this->pdf->AddPage('P',array(350,250));
		//masukkan posisi judul
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(
            GetCatalog('issueid'),
            GetCatalog('company'),
            GetCatalog('docdate'),
            GetCatalog('fullname'),
            GetCatalog('jumlah'),
            GetCatalog('issue/mnt'),
            GetCatalog('recordstatus')
        );
        
		$this->pdf->setwidths(array(15,80,35,50,20,80,25));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',9);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
            $this->pdf->row(array($row1['issueid'],$row1['companyname'],date(Yii::app()->params['dateviewfromdb'],strtotime($row1['docdate'])),
                                  $row1['fullname'],$row1['jumlah'],$row1['description'],$row1['statusname']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='issue';
		parent::actionDownxls();
		$sql = "select issueid,
            fullname,a.employeeid,docdate,a.companyid,companyname,description,cycletime,statusname,a.recordstatus, a.jumlah, b.companycode, (cycletime/60) as cycletimemin
            from issue a
            left join company b on b.companyid = a.companyid
            left join employee c on c.employeeid = a.employeeid ";
		if ($_GET['id'] !== '') 
		{
            $sql = $sql . "where a.issueid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by issueid desc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['issueid'])
				->setCellValueByColumnAndRow(1,$i,$row1['companycode'])							
				->setCellValueByColumnAndRow(2,$i,$row1['fullname'])
				->setCellValueByColumnAndRow(3,$i,$row1['docdate'])
				->setCellValueByColumnAndRow(4,$i,$row1['jumlah'])
				->setCellValueByColumnAndRow(5,$i,$row1['description'])
				->setCellValueByColumnAndRow(6,$i,$row1['cycletimemin']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
	
	/*public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select isstock,issuename,productpic,description,recordstatus
				from product a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.issueid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,GetCatalog('isstock'))
                ->setCellValueByColumnAndRow(1,1,GetCatalog('issuename'))
                ->setCellValueByColumnAndRow(2,1,GetCatalog('productpic'))
                ->setCellValueByColumnAndRow(3,1,GetCatalog('description'))
                ->setCellValueByColumnAndRow(4,1,GetCatalog('recordstatus'));
                foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['isstock'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['issuename'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['productpic'])
                                ->setCellValueByColumnAndRow(3, $i+1, $row1['description'])
                                ->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus']);
                          $i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="product.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');
		unset($excel);
	}
	*/

}
