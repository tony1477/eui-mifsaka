<?php
class PlantController extends Controller {
	public $menuname = 'plant';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
    public function actionIndexcompany() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchcompany();
		else
			$this->renderPartial('index',array());
	}
    public function search() {
		header("Content-Type: application/json");
        $companyid = isset($_GET['companyid']) ? $_GET['companyid'] : '';
		$plantid = isset ($_POST['plantid']) ? $_POST['plantid'] : '';
		$plantcode = isset ($_POST['plantcode']) ? $_POST['plantcode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$company = isset ($_POST['company']) ? $_POST['company'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$plantid = isset ($_GET['q']) ? $_GET['q'] : $plantid;
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : $plantcode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$company = isset ($_GET['q']) ? $_GET['q'] : $company;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'plantid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
        if(isset($_GET['trxcom'])) {
            $cmd = Yii::app()->db->createCommand()
                ->select('ifnull(count(1),0)')
                ->from('plant t')
                ->join('company a','a.companyid = t.companyid')
                ->where('(plantcode like :plantcode or description like :description) and t.companyid = :companyid and t.recordstatus=1 and t.plantid in('.getUserObjectValues('plant').')',
                       array(':plantcode' => '%'.$plantcode.'%',
                        ':description' => '%'.$description.'%',
                        ':companyid' => $companyid
                        ))
                ->queryScalar();
        }
        else
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('plant t')
				->join('company p','p.companyid=t.companyid')
				->where('(plantcode like :plantcode) and (description like :description) and (p.companyname like :company)',
												array(':plantcode'=>'%'.$plantcode.'%',':description'=>'%'.$description.'%',':company'=>'%'.$company.'%'))
				->queryScalar();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('plant t')
				->join('company p','p.companyid=t.companyid')
				->where('((plantcode like :plantcode) or 
								(description like :description) or 
								(p.companyname like :company)) and 
								t.recordstatus=1',
												array(':plantcode'=>'%'.$plantcode.'%',
														':description'=>'%'.$description.'%',
														':company'=>'%'.$company.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
        if(isset($_GET['trxcom'])) {
            $cmd = Yii::app()->db->createCommand()
                ->select('t.*, a.companyname')
                ->from('plant t')
                ->join('company a','a.companyid = t.companyid')
                ->where('(plantcode like :plantcode or description like :description) and t.companyid = :companyid and t.recordstatus=1 and t.plantid in('.getUserObjectValues('plant').') ',
                       array(':plantcode' => '%'.$plantcode.'%',
                        ':description' => '%'.$description.'%',
                        ':companyid' => $companyid
                ))
                ->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
                ->queryAll();
        }
        else
		if (!isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.companyname')	
				->from('plant t')
				->join('company p','p.companyid=t.companyid')
				->where('(plantcode like :plantcode) and 
					(description like :description) and 
					(p.companyname like :company)',
						array(':plantcode'=>'%'.$plantcode.'%',
								':description'=>'%'.$description.'%',
								':company'=>'%'.$company.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*,p.companyname')	
				->from('plant t')
				->join('company p','p.companyid=t.companyid')
				->where('((plantcode like :plantcode) or 
								(description like :description) or 
								(p.companyname like :company)) and 
								t.recordstatus=1',
												array(':plantcode'=>'%'.$plantcode.'%',
														':description'=>'%'.$description.'%',
														':company'=>'%'.$company.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'description'=>$data['description'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
    public function searchcompany() {
        header("Content-Type: application/json");
		$plantid = isset ($_POST['plantid']) ? $_POST['plantid'] : '';
		$plantcode = isset ($_POST['plantcode']) ? $_POST['plantcode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$companyid = isset ($_REQUEST['companyid']) ? $_REQUEST['companyid'] : '';
		$company = isset ($_POST['company']) ? $_POST['company'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$plantid = isset ($_GET['q']) ? $_GET['q'] : $plantid;
		$plantcode = isset ($_GET['q']) ? $_GET['q'] : $plantcode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		//$companyid = isset ($_GET['q']) ? $_GET['q'] : $companyid;
		$company = isset ($_GET['q']) ? $_GET['q'] : $company;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'plantid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
       
        if(isset($_POST['trxcom']))
        {
            $cmd = Yii::app()->db->createCommand()
                ->select('ifnull(count(1),0) as total')
                ->from('plant t')
                ->join('company a','a.companyid=t.companyid')
                ->where('t.recordstatus=1 and t.companyid = :companyid and t.plantid in('.getUserObjectValues('plant').')', array(':companyid'=>$_POST['companyid']))
                ->queryScalar();
        }
        else
        {
            $cmd = Yii::app()->db->createCommand()
                ->select('count(1) as total')
                ->from('plant t')
                ->join('company a','a.companyid=t.companyid')
                ->where("t.recordstatus=1 and (a.companyid = :companyid 
                    or a.companyname like :company)",
                       array(':companyid'=>"'".$companyid."'",
                       ':company'=>'%'.$company.'%'))
                ->queryScalar();    
        }
        $result['total'] = $cmd;
        if(isset($_POST['trxcom']))
        {
            $cmd = Yii::app()->db->createCommand()
                ->select('t.*, a.companyname')
                ->from('plant t')
                ->join('company a','a.companyid=t.companyid')
                ->where('t.recordstatus=1 and t.companyid = :companyid and t.plantid in('.getUserObjectValues('plant').') limit 1', array(':companyid' => $_POST['companyid']))
                ->queryAll();
        }
        else
        {            
            $cmd = Yii::app()->db->createCommand()
                ->select('t.*, a.companyname')
                ->from('plant t ')
                ->join('company a','a.companyid=t.companyid')
                ->where("t.recordstatus=1 and a.companyid = {$companyid}")
                ->offset($offset)
                ->limit($rows)
                ->order($sort.' '.$order)
                ->queryAll();
        }
        
        foreach($cmd as $data) {	
			$row[] = array(
				'plantid'=>$data['plantid'],
				'plantcode'=>$data['plantcode'],
				'description'=>$data['description'],
				'companyid'=>$data['companyid'],
				'companyname'=>$data['companyname'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
    }
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertplant(:vcompanyid,:vplantcode,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateplant(:vid,:vcompanyid,:vplantcode,:vdescription,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vcompanyid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vplantcode',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-plant"]["name"]);
		if (move_uploaded_file($_FILES["file-plant"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 2; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '".$companycode."'")->queryScalar();
					$plantcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$this->ModifyData($connection,array($id,$languagename,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['plantid'])?$_POST['plantid']:''),$_POST['companyid'],$_POST['plantcode'],$_POST['description'],$_POST['recordstatus']));
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
				$sql = 'call Purgeplant(:vid,:vcreatedby)';
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
		else {
			GetMessage(true,'chooseone');
		}
	}
	public function actionDownPDF() {
	  parent::actionDownload();
	  $sql = "select a.plantid,a.plantcode,a.description,b.companyname,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from plant a 
						left join company b on b.companyid = a.companyid ";
		$plantid = filter_input(INPUT_GET,'plantid');
		$plantcode = filter_input(INPUT_GET,'plantcode');
		$company = filter_input(INPUT_GET,'company');
		$sql .= " where coalesce(a.plantid,'') like '%".$plantid."%' 
			and coalesce(a.plantcode,'') like '%".$plantcode."%'
			and coalesce(b.companyname,'') like '%".$company."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.plantid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('plant');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('plantid'),
																	GetCatalog('plantcode'),
																	GetCatalog('description'),
																	GetCatalog('companyname'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,60,60,170,25));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['plantid'],$row1['plantcode'],$row1['description'],$row1['companyname'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='plant';
		parent::actionDownxls();
		$sql = "select a.plantid,a.plantcode,a.description,b.companyname,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from plant a 
						join company b on b.companyid = a.companyid ";
		$plantid = filter_input(INPUT_GET,'plantid');
		$plantcode = filter_input(INPUT_GET,'plantcode');
		$company = filter_input(INPUT_GET,'company');
		$sql .= " where coalesce(a.plantid,'') like '%".$plantid."%' 
			and coalesce(a.plantcode,'') like '%".$plantcode."%'
			and coalesce(b.companyname,'') like '%".$company."%'
			";
		if ($_GET['id'] !== '')  {
				$sql = $sql . " and a.plantid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('plantid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('plantcode'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('companyname'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['plantid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['plantcode'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['companyname'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}