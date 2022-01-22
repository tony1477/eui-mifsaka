<?php
class ShiftschedController extends Controller
{
	public $menuname = 'shiftsched';
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
				$sql = 'call Insertshiftsched(:vshiftcode,:vshiftname,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updateshiftsched(:vid,:vshiftcode,:vshiftname,:vdescription,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['shiftschedid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['shiftschedid']);
			}
			$command->bindvalue(':vshiftcode',$_POST['shiftcode'],PDO::PARAM_STR);
			$command->bindvalue(':vshiftname',$_POST['shiftname'],PDO::PARAM_STR);
			$command->bindvalue(':vdescription',$_POST['description'],PDO::PARAM_STR);
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
				$sql = 'call Purgeshiftsched(:vid,:vcreatedby)';
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
		$shiftschedid = isset ($_POST['shiftschedid']) ? $_POST['shiftschedid'] : '';
		$shiftcode = isset ($_POST['shiftcode']) ? $_POST['shiftcode'] : '';
		$shiftname = isset ($_POST['shiftname']) ? $_POST['shiftname'] : '';
		$shiftschedid = isset ($_GET['q']) ? $_GET['q'] : $shiftschedid;
		$shiftcode = isset ($_GET['q']) ? $_GET['q'] : $shiftcode;
		$shiftname = isset ($_GET['q']) ? $_GET['q'] : $shiftname;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'shiftschedid';
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
                    ->from('shiftsched t')
                    ->where('((shiftschedid like :shiftschedid) or (shiftcode like :shiftcode) or (shiftname like :shiftname)) 
                            and t.recordstatus = 1 ',
                    array(':shiftschedid'=>'%'.$shiftschedid.'%',':shiftcode'=>'%'.$shiftcode.'%',
                    ':shiftname'=>'%'.$shiftname.'%'
                                ))
                    ->queryRow();
		}
        /*
        else if(isset($_GET['opoutput']))
        {
            
			$cmd = Yii::app()->db->createCommand()
							->select('count(1) as total')	
							->from('shiftsched t')
                            ->leftjoin('sloc a','a.slocid = t.slocid')
							->where('((shiftschedid like :shiftschedid) or (shiftcode like :shiftcode)) 
				                    and t.recordstatus = 1 and t.slocid='.$_REQUEST['slocid'],
												array(':shiftschedid'=>'%'.$shiftschedid.'%',':shiftcode'=>'%'.$shiftcode.'%',
										))
							->queryRow();
        }
        */
		else
		{
			$cmd = Yii::app()->db->createCommand()
                    ->select('count(1) as total')	
                    ->from('shiftsched t')
                    ->where("(coalesce(shiftschedid,'') like :shiftschedid) and (coalesce(shiftcode,'') like :shiftcode) and (coalesce(shiftname,'') like :shiftname)",
                    array(':shiftschedid'=>'%'.$shiftschedid.'%',':shiftcode'=>'%'.$shiftcode.'%',
                    ':shiftname'=>'%'.$shiftname.'%'
                    ))
                    ->queryRow();
		}
	
		$result['total'] = $cmd['total'];
		
		if (isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('shiftsched t')
				->where('((shiftschedid like :shiftschedid) or (shiftcode like :shiftcode) or (shiftname like :shiftname)) 
				and t.recordstatus = 1 ',
                array(':shiftschedid'=>'%'.$shiftschedid.'%',':shiftcode'=>'%'.$shiftcode.'%',
                ':shiftname'=>'%'.$shiftname.'%'
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
				->select('t.*, a.shiftname, a.shiftname, a.slocid, concat(a.shiftname,"-",a.shiftname) as slocdesc')	
				->from('shiftsched t')
                ->leftjoin('sloc a','a.slocid = t.slocid')
				->where('((shiftschedid like :shiftschedid) or (shiftcode like :shiftcode))
				and t.recordstatus = 1 and t.slocid='.$_REQUEST['slocid'],
                array(':shiftschedid'=>'%'.$shiftschedid.'%',':shiftcode'=>'%'.$shiftcode.'%',
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
				->select('t.*')	
				->from('shiftsched t')
				->where("(coalesce(shiftschedid,'') like :shiftschedid) and (coalesce(shiftcode,'') like :shiftcode) and (coalesce(shiftname,'') like :shiftname)",
                array(':shiftschedid'=>'%'.$shiftschedid.'%',':shiftcode'=>'%'.$shiftcode.'%',
                ':shiftname'=>'%'.$shiftname.'%'
                ))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}

		foreach($cmd as $data)
		{	
			$row[] = array(
				'shiftschedid'=>$data['shiftschedid'],
				'description'=>$data['description'],
				'shiftcode'=>$data['shiftcode'],
                'shiftname'=>$data['shiftname'],
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
	  $sql = "select shiftschedid,
            shiftcode,shiftname,description,
            case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
            from shiftsched a ";
		if ($_GET['id'] !== '') 
		{
            $sql = $sql . "where a.shiftschedid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by shiftcode asc ";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=GetCatalog('shiftsched');
		$this->pdf->AddPage('P',array(350,250));
		//masukkan posisi judul
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(
            GetCatalog('shiftschedid'),
            GetCatalog('shiftcode'),
            GetCatalog('shiftname'),
            GetCatalog('description'),
            GetCatalog('recordstatus')
        );
        
		$this->pdf->setwidths(array(22,170,35,30,30,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
            $this->pdf->row(array($row1['shiftschedid'],$row1['shiftcode'],Yii::app()->format->formatCurrency($row1['shiftname']),
                                  $row1['description'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='shiftsched';
		parent::actionDownxls();
		$sql = "select shiftschedid,shiftcode,shiftname,description,
                case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
                from shiftsched a ";
		if ($_GET['id'] !== '') 
		{
            $sql = $sql . "where a.shiftschedid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by shiftcode asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['shiftschedid'])
				->setCellValueByColumnAndRow(1,$i,$row1['shiftcode'])							
				->setCellValueByColumnAndRow(2,$i,$row1['shiftname'])
				->setCellValueByColumnAndRow(3,$i,$row1['description'])
				->setCellValueByColumnAndRow(4,$i,$row1['recordstatus']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
	/*public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select isstock,shiftcode,productpic,shiftname,recordstatus
				from product a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.shiftschedid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,GetCatalog('isstock'))
                ->setCellValueByColumnAndRow(1,1,GetCatalog('shiftcode'))
                ->setCellValueByColumnAndRow(2,1,GetCatalog('productpic'))
                ->setCellValueByColumnAndRow(3,1,GetCatalog('shiftname'))
                ->setCellValueByColumnAndRow(4,1,GetCatalog('recordstatus'));
                foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['isstock'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['shiftcode'])
                                ->setCellValueByColumnAndRow(2, $i+1, $row1['productpic'])
                                ->setCellValueByColumnAndRow(3, $i+1, $row1['shiftname'])
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
