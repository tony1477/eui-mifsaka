<?php
class StandardinsentifController extends Controller
{
	public $menuname = 'standardinsentif';
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
				$sql = 'call Insertstandardinsentif(:vcompanyid,:vperioddate,:vprice,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updatestandardinsentif(:vid,:vcompanyid,:vperioddate,:vprice,:vrecordstatus,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['standardinsentifid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['standardinsentifid']);
			}
			$command->bindvalue(':vcompanyid',$_POST['companyid'],PDO::PARAM_STR);
			$command->bindvalue(':vperioddate',date(Yii::app()->params['datetodb'],strtotime($_POST['perioddate'])),PDO::PARAM_STR);
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
	public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call ApproveStandardInsentif(:vid,:vcreatedby)';
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
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call DeleteStandardInsentif(:vid,:vcreatedby)';
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
				$sql = 'call Purgestandardinsentif(:vid,:vcreatedby)';
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
		$standardinsentifid = isset ($_POST['standardinsentifid']) ? $_POST['standardinsentifid'] : '';
		$companyname = isset ($_POST['companyname']) ? $_POST['companyname'] : '';
		$perioddate = isset ($_POST['perioddate']) ? $_POST['perioddate'] : '';
		$standardinsentifid = isset ($_GET['q']) ? $_GET['q'] : $standardinsentifid;
		$companyname = isset ($_GET['q']) ? $_GET['q'] : $companyname;
		$perioddate = isset ($_GET['q']) ? $_GET['q'] : $perioddate;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'standardinsentifid';
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
                    ->from('standardinsentif t')
                    ->leftjoin('company a','a.companyid = t.companyid')
                    ->where('((standardinsentifid like :standardinsentifid) or (companyname like :companyname)) 
                            and t.recordstatus = 1 ',
                    array(':standardinsentifid'=>'%'.$standardinsentifid.'%',':companyname'=>'%'.$companyname.'%'
                    ))
                    ->queryRow();
		}
        /*
        else if(isset($_GET['opoutput']))
        {
            
			$cmd = Yii::app()->db->createCommand()
							->select('count(1) as total')	
							->from('standardinsentif t')
                            ->leftjoin('sloc a','a.slocid = t.slocid')
							->where('((standardinsentifid like :standardinsentifid) or (issuename like :issuename)) 
				                    and t.recordstatus = 1 and t.slocid='.$_REQUEST['slocid'],
												array(':standardinsentifid'=>'%'.$standardinsentifid.'%',':issuename'=>'%'.$issuename.'%',
										))
							->queryRow();
        }
        */
		else
		{
			$cmd = Yii::app()->db->createCommand()
                    ->select('count(1) as total')	
                    ->from('standardinsentif t')
                    ->leftjoin('company a','a.companyid = t.companyid')
                    ->where("(coalesce(standardinsentifid,'') like :standardinsentifid) and (coalesce(companyname,'') like :companyname)",
                    array(':standardinsentifid'=>'%'.$standardinsentifid.'%',':companyname'=>'%'.$companyname.'%'
                    ))
                    ->queryRow();
		}
	
		$result['total'] = $cmd['total'];
		
		if (isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,a.companyname')	
				->from('standardinsentif t')
                ->leftjoin('company a','a.companyid = t.companyid')
				->where('((standardinsentifid like :standardinsentifid) or (companyname like :companyname)) 
				and t.recordstatus = 1 ',
                array(':standardinsentifid'=>'%'.$standardinsentifid.'%',':companyname'=>'%'.$companyname.'%'
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
				->from('standardinsentif t')
                ->leftjoin('sloc a','a.slocid = t.slocid')
				->where('((standardinsentifid like :standardinsentifid) or (issuename like :issuename))
				and t.recordstatus = 1 and t.slocid='.$_REQUEST['slocid'],
                array(':standardinsentifid'=>'%'.$standardinsentifid.'%',':issuename'=>'%'.$issuename.'%',
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
				->select('t.*,a.companyname')	
				->from('standardinsentif t')
                ->leftjoin('company a','a.companyid = t.companyid')
				->where("(coalesce(standardinsentifid,'') like :standardinsentifid) and (coalesce(companyname,'') like :companyname)",
                array(':standardinsentifid'=>'%'.$standardinsentifid.'%',':companyname'=>'%'.$companyname.'%'
                ))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}

		foreach($cmd as $data)
		{	
			$row[] = array(
				'standardinsentifid'=>$data['standardinsentifid'],
				'price'=>$data['price'],
				'companyname'=>$data['companyname'],
				'companyid'=>$data['companyid'],
                'perioddate'=>date(Yii::app()->params['dateviewfromdb'],strtotime($data['perioddate'])),
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
	  $sql = "select standardinsentifid,
            companyname,price,perioddate,
            case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
            from standardinsentif a ";
		if ($_GET['id'] !== '') 
		{
            $sql = $sql . "where a.standardinsentifid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by companyname asc ";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=GetCatalog('standardinsentif');
		$this->pdf->AddPage('P',array(350,250));
		//masukkan posisi judul
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(
            GetCatalog('standardinsentifid'),
            GetCatalog('companyname'),
            GetCatalog('perioddate'),
            GetCatalog('price'),
            GetCatalog('recordstatus')
        );
        
		$this->pdf->setwidths(array(22,170,35,30,30,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
            $this->pdf->row(array($row1['standardinsentifid'],$row1['companyname'],Yii::app()->format->formatCurrency($row1['perioddate']),
                                  $row1['price'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='standardinsentif';
		parent::actionDownxls();
		$sql = "select standardinsentifid,companyname,price,perioddate,
                case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
                from standardinsentif a ";
		if ($_GET['id'] !== '') 
		{
            $sql = $sql . "where a.standardinsentifid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by companyname asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['standardinsentifid'])
				->setCellValueByColumnAndRow(1,$i,$row1['companyname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['perioddate'])
				->setCellValueByColumnAndRow(3,$i,$row1['price'])
				->setCellValueByColumnAndRow(4,$i,$row1['recordstatus']);
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
				$sql = $sql . "where a.standardinsentifid in (".$_GET['id'].")";
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
