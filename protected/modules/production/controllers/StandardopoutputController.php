<?php
class StandardopoutputController extends Controller
{
	public $menuname = 'standardopoutput';
	public function actionIndex()
	{
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
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
				$sql = 'call InsertStandardop(:vslocid,:vgroupname,:vproducttype,:vstandardvalue,:vcycletime,:vprice,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call UpdateStandardop(:vid,:vslocid,:vgroupname,:vproducttype,:vstandardvalue,:vcycletime,:vprice,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['standardopoutputid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['standardopoutputid']);
			}
			$command->bindvalue(':vslocid',$_POST['slocid'],PDO::PARAM_STR);
			$command->bindvalue(':vgroupname',$_POST['groupname'],PDO::PARAM_STR);
			$command->bindvalue(':vproducttype',$_POST['producttype'],PDO::PARAM_STR);
			$command->bindvalue(':vstandardvalue',$_POST['standardvalue'],PDO::PARAM_STR);
			$command->bindvalue(':vcycletime',$_POST['cycletime'],PDO::PARAM_STR);
			$command->bindvalue(':vprice',$_POST['price'],PDO::PARAM_STR);
			$command->bindvalue(':vrecordstatus',$_POST['recordstatus'],PDO::PARAM_STR);
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
	public function actionPurge()
	{
		header("Content-Type: application/json");
		
		if (isset($_POST['id']))
		{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgestandardop(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);

				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				
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
		$standardopoutputid = isset ($_POST['standardopoutputid']) ? $_POST['standardopoutputid'] : '';
		$groupname = isset ($_POST['groupname']) ? $_POST['groupname'] : '';
		$sloccode = isset ($_POST['sloccode']) ? $_POST['sloccode'] : '';
		$standardopoutputid = isset ($_GET['q']) ? $_GET['q'] : $standardopoutputid;
		$groupname = isset ($_GET['q']) ? $_GET['q'] : $groupname;
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : $sloccode;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'standardopoutputid';
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
							->from('standardopoutput t')
                            ->leftjoin('sloc a','a.slocid = t.slocid')
							->where('((standardopoutputid like :standardopoutputid) or (groupname like :groupname) or (sloccode like :sloccode)) 
				                    and t.recordstatus = 1 ',
												array(':standardopoutputid'=>'%'.$standardopoutputid.'%',':groupname'=>'%'.$groupname.'%',
														':sloccode'=>'%'.$sloccode.'%'
										))
							->queryRow();
		}
        else if(isset($_GET['opoutput']))
        {
            
			$cmd = Yii::app()->db->createCommand()
							->select('count(1) as total')	
							->from('standardopoutput t')
                            ->leftjoin('sloc a','a.slocid = t.slocid')
							->where('((standardopoutputid like :standardopoutputid) or (groupname like :groupname)) 
				                    and t.recordstatus = 1 and t.slocid='.$_REQUEST['slocid'],
												array(':standardopoutputid'=>'%'.$standardopoutputid.'%',':groupname'=>'%'.$groupname.'%',
										))
							->queryRow();
        }
		else
		{
			$cmd = Yii::app()->db->createCommand()
							->select('count(1) as total')	
							->from('standardopoutput t')
                            ->leftjoin('sloc a','a.slocid = t.slocid')
							->where("(coalesce(standardopoutputid,'') like :standardopoutputid) and (coalesce(groupname,'') like :groupname) and (coalesce(sloccode,'') like :sloccode)",
															array(':standardopoutputid'=>'%'.$standardopoutputid.'%',':groupname'=>'%'.$groupname.'%',
							':sloccode'=>'%'.$sloccode.'%'
							))
							->queryRow();
		}
	
		$result['total'] = $cmd['total'];
		
		if (isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, a.description, a.sloccode, a.slocid, concat(a.sloccode,"-",a.description) as slocdesc')	
				->from('standardopoutput t')
                ->leftjoin('sloc a','a.slocid = t.slocid')
				->where('((standardopoutputid like :standardopoutputid) or (groupname like :groupname) or (sloccode like :sloccode)) 
				and t.recordstatus = 1 ',
												array(':standardopoutputid'=>'%'.$standardopoutputid.'%',':groupname'=>'%'.$groupname.'%',
														':sloccode'=>'%'.$sloccode.'%'
														))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
        else if(isset($_GET['opoutput']))
        {
            $cmd = Yii::app()->db->createCommand()
				->select('t.*, a.description, a.sloccode, a.slocid, concat(a.sloccode,"-",a.description) as slocdesc')	
				->from('standardopoutput t')
                ->leftjoin('sloc a','a.slocid = t.slocid')
				->where('((standardopoutputid like :standardopoutputid) or (groupname like :groupname))
				and t.recordstatus = 1 and t.slocid='.$_REQUEST['slocid'],
                array(':standardopoutputid'=>'%'.$standardopoutputid.'%',':groupname'=>'%'.$groupname.'%',
														))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
        }
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select("t.*, a.description, a.sloccode, a.slocid, concat(a.sloccode,'-',a.description) as slocdesc")	
				->from('standardopoutput t')
                ->leftjoin('sloc a','a.slocid = t.slocid')
				->where("(coalesce(standardopoutputid,'') like :standardopoutputid) and (coalesce(groupname,'') like :groupname) and (coalesce(sloccode,'') like :sloccode)",
					array(':standardopoutputid'=>'%'.$standardopoutputid.'%',':groupname'=>'%'.$groupname.'%',
							':sloccode'=>'%'.$sloccode.'%'
							))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}

		foreach($cmd as $data)
		{	
			$row[] = array(
				'standardopoutputid'=>$data['standardopoutputid'],
				'sloccode'=>$data['sloccode'],
				'slocdesc'=>$data['slocdesc'],
				'slocid'=>$data['slocid'],
				'standardvalue'=>$data['standardvalue'],
				'description'=>$data['description'],
				'groupname'=>$data['groupname'],
				'producttype'=>$data['producttype'],
                'cycletime'=>$data['cycletime'],
                'price'=>$data['price'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select standardopoutputid,
						groupname,sloccode,standardvalue,cycletime,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from standardopoutput a 
                        join sloc b on b.slocid = a.slocid ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.standardopoutputid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by groupname asc ";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=GetCatalog('standardopoutput');
		$this->pdf->AddPage('P',array(350,250));
		//masukkan posisi judul
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(GetCatalog('standardopoutputid'),
																	GetCatalog('groupname'),
																	GetCatalog('sloccode'),
																	GetCatalog('standardvalue'),
																	GetCatalog('cycletime'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(22,170,35,30,30,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['standardopoutputid'],$row1['groupname'],$row1['sloccode'],
                                Yii::app()->format->formatCurrency($row1['standardvalue']),
                                Yii::app()->format->formatCurrency($row1['cycletime'])
                                ,$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	/*{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select standardopoutputid,
						groupname,sloccode,standardvalue,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from standardopoutput a 
                        join sloc b on b.slocid = a.slocid ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.standardopoutputid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by groupname asc ";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=GetCatalog('standardopoutput');
		$this->pdf->AddPage('P',array(350,250));
		//masukkan posisi judul
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(GetCatalog('standardopoutputid'),
																	GetCatalog('groupname'),
																	GetCatalog('sloccode'),
																	GetCatalog('standardvalue'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(22,200,35,30,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['standardopoutputid'],$row1['groupname'],$row1['sloccode'],$row1['standardvalue'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}*/
	public function actionDownxls()
	{
		$this->menuname='product';
		parent::actionDownxls();
		$sql = "select standardopoutputid,groupname,productpic,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						sloccode,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from product a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.standardopoutputid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by groupname asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['standardopoutputid'])
				->setCellValueByColumnAndRow(1,$i,$row1['groupname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['productpic'])
				->setCellValueByColumnAndRow(3,$i,$row1['isstock'])
				->setCellValueByColumnAndRow(4,$i,$row1['sloccode'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
	/*public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select isstock,groupname,productpic,sloccode,recordstatus
				from product a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.standardopoutputid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,GetCatalog('isstock'))
                ->setCellValueByColumnAndRow(1,1,GetCatalog('groupname'))
                ->setCellValueByColumnAndRow(2,1,GetCatalog('productpic'))
                ->setCellValueByColumnAndRow(3,1,GetCatalog('sloccode'))
                ->setCellValueByColumnAndRow(4,1,GetCatalog('recordstatus'));
                foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['isstock'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['groupname'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['productpic'])
                                ->setCellValueByColumnAndRow(3, $i+1, $row1['sloccode'])
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
