<?php
class MaterialtypeController extends Controller {
	public $menuname = 'materialtype';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
    public function actionIndextrx() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->searchtrx();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$materialtypeid = isset ($_POST['materialtypeid']) ? $_POST['materialtypeid'] : '';
		$materialtypecode = isset ($_POST['materialtypecode']) ? $_POST['materialtypecode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$materialtypeid = isset ($_GET['q']) ? $_GET['q'] : $materialtypeid;
		$materialtypecode = isset ($_GET['q']) ? $_GET['q'] : $materialtypecode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'materialtypeid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		if (!isset($_GET['combo'])) {
            $sql = "select count(1) as total 
                    from materialtype t 
                    left join materialtype a on a.materialtypeid = t.parentid
                    where (t.materialtypecode like '%{$materialtypecode}%') and 
								(t.description like '%{$description}%') and t.recordstatus=1 ";
            $cmd = Yii::app()->db->createCommand($sql)->queryScalar();
		}
        else if(isset($_GET['trx'])) {
            $sql = "select count(1) as total
                    from materialtype t
                    where (t.materialtypecode like '%{$materialtypecode}%' or t.description like '%{$description}%') and t.recordstatus=1
                and isparent=0 ";
            $cmd = Yii::app()->db->createCommand($sql)->queryScalar();
        }
        else if(isset($_GET['soheader'])) {
            $sql = "select count(1) as total
                    from materialtype t
                    where (t.materialtypecode like '%{$materialtypecode}%' or t.description like '%{$description}%') and t.recordstatus=1
                and isparent=1";
          
            $cmd = Yii::app()->db->createCommand($sql)->queryScalar();
        }
		else {
            $sql = "select count(1) as total
                    from materialtype t
                    left join materialtype a on a.materialtypeid = t.parentid
                    where ((t.materialtypecode like '%{$materialtypecode}%') or 
								(t.description like '%{$description}%')) and 
								t.recordstatus=1";
			$cmd = Yii::app()->db->createCommand($sql)->queryScalar();
		}
		$result['total'] = $cmd;
        
        if(isset($_GET['soheader'])) {
            $sql = "select t.*, b.description as parentdesc, z.paycode as paycode, z.sopaymethodid as paymentmethodid
                    from materialtype t 
                    left join materialtype b on b.materialtypeid = t.parentid
                    left join (select a.materialtypeid, a.sopaymethodid, b.paycode
                      from custdisc a
                      join paymentmethod b on b.paymentmethodid = a.sopaymethodid
                      where a.addressbookid = {$_REQUEST['addressbookid']}) z on z.materialtypeid = t.materialtypeid
                    where (t.materialtypecode like '%{$materialtypecode}%' or t.description like '%{$description}%') and t.recordstatus=1
                and t.isparent=1 
                order by {$sort} {$order}
                limit {$offset} , {$rows}";
          
            $cmd = Yii::app()->db->createCommand($sql)->queryAll();
        }
		else if (!isset($_GET['combo'])) {
            $sql = "select t.*, a.description as parentdesc, null as paycode, null as paymentmethodid
                    from materialtype t
                    left join materialtype a on a.materialtypeid = t.parentid
                    where (t.materialtypecode like '%{$materialtypecode}%') and (t.description like '%{$description}%' ) and t.recordstatus=1 
                    order by {$sort} {$order} 
                    limit {$offset}, {$rows}";
            
			$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		}
        else if(isset($_GET['trx'])) {
            $sql = "select t.*, null as parentdesc, null as paycode, null as paymentmethodid 
                    from materialtype t 
                    where (t.materialtypecode like '%{$materialtypecode}%' or description like '%{$description}%') and t.recordstatus=1
                    and t.isparent=0 
                    order by {$sort} {$order}
                    limit {$offset},{$rows} ";
                
            $cmd = Yii::app()->db->createCommand($sql)->queryAll();
        }
		else {
            $sql = "select t.*, a.description as parentdesc, null as paycode, null as paymentmethodid
                    from materialtype t
                    left join materialtype a on a.materialtypeid = t.parentid
                    where ((t.materialtypecode like '%{$materialtypecode}%') or (t.description like '%{$description}%')) and t.recordstatus=1
                    order by {$sort} {$order} 
                    limit {$offset}, {$rows}";
			$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'materialtypeid'=>$data['materialtypeid'],
				'materialtypecode'=>$data['materialtypecode'],
				'isview'=>$data['isview'],
				'description'=>$data['description'],
				'parentid'=>$data['parentid'],
				'parentdesc'=>$data['parentdesc'],
				'isparent'=>$data['isparent'],
				'iseditpriceso'=>$data['iseditpriceso'],
				'iseditdiscso'=>$data['iseditdiscso'],
				'isedittop'=>$data['isedittop'],
				'paymentmethodid' => $data['paymentmethodid'],
				'paycode' => $data['paycode'],
				'nourut' => $data['nourut'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
    public function searchtrx() {
		header("Content-Type: application/json");
		$materialtypeid = isset ($_POST['materialtypeid']) ? $_POST['materialtypeid'] : '';
		$materialtypecode = isset ($_POST['materialtypecode']) ? $_POST['materialtypecode'] : '';
		$description = isset ($_POST['description']) ? $_POST['description'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$materialtypeid = isset ($_GET['q']) ? $_GET['q'] : $materialtypeid;
		$materialtypecode = isset ($_GET['q']) ? $_GET['q'] : $materialtypecode;
		$description = isset ($_GET['q']) ? $_GET['q'] : $description;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'materialtypeid';
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
				->from('materialtype t')
				->where('(t.materialtypecode like :materialtypecode or 
								t.description like :description) and t.recordstatus=1
                                and t.isparent=1',
												array(':materialtypecode'=>'%'.$materialtypecode.'%',
														':description'=>'%'.$description.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, (select productid from product where productid = 1)')	
				->from('materialtype t')
				->where('(t.materialtypecode like :materialtypecode or 
								t.description like :description) and t.recordstatus=1
                                and t.isparent=1',
												array(':materialtypecode'=>'%'.$materialtypecode.'%',
														':description'=>'%'.$description.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
        
		foreach($cmd as $data) {	
			$row[] = array(
				'materialtypeid'=>$data['materialtypeid'],
				'materialtypecode'=>$data['materialtypecode'],
				'isview'=>$data['isview'],
				'description'=>$data['description'],
				'parentid'=>$data['parentid'],
				'isparent'=>$data['isparent'],
				'iseditpriceso'=>$data['iseditpriceso'],
				'iseditdiscso'=>$data['iseditdiscso'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertmaterialtype(:vmaterialtypecode,:vdescription,:vrecordstatus,:vnourut,:visview,:viseditpriceso,:viseditdiscso,:visedittop,:vparentid,:visparent,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else 	{
			$sql = 'call Updatematerialtype(:vid,:vmaterialtypecode,:vdescription,:vrecordstatus,:vnourut,:visview,:viseditpriceso,:viseditdiscso,:visedittop,:vparentid,:visparent,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vmaterialtypecode',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vdescription',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vnourut',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visview',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':viseditpriceso',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':viseditdiscso',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':visedittop',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vparentid',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':visparent',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-materialtype"]["name"]);
		if (move_uploaded_file($_FILES["file-materialtype"]["tmp_name"], $target_file)) {
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
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$description = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$nourut = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$isview = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$parentcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$parentid = Yii::app()->db->createCommand("select materialtypeid from materialtype where materialtypecode = '".$parentcode."'")->queryScalar();
					$isparent = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$this->ModifyData($connection,array($id,$materialtypecode,$description,$nourut,$isview,$parentid,$isparent,$recordstatus));
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
			$this->ModifyData($connection,array((isset($_POST['materialtypeid'])?$_POST['materialtypeid']:''),$_POST['materialtypecode'],$_POST['description'],($_POST['nourut']!='' ? $_POST['nourut'] : '0'),$_POST['isview'],$_POST['iseditpriceso'],$_POST['iseditdiscso'],$_POST['isedittop'],
				($_POST['parentid']!=''?$_POST['parentid'] : '0'),$_POST['isparent'],$_POST['recordstatus']));
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
				$sql = 'call Purgematerialtype(:vid,:vcreatedby)';
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
		//masukkan perintah download
	  $sql = "select materialtypeid,materialtypecode,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from materialtype a ";
		$materialtypeid = filter_input(INPUT_GET,'materialtypeid');
		$materialtypecode = filter_input(INPUT_GET,'materialtypecode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.materialtypeid,'') like '%".$materialtypeid."%' 
			and coalesce(a.materialtypecode,'') like '%".$materialtypecode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.materialtypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by materialtypecode asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('materialtype');
		$this->pdf->AddPage('P');
		$this->pdf->colalign = array('L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('materialtypeid'),
																	GetCatalog('materialtypecode'),
																	GetCatalog('description'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,50,105,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['materialtypeid'],$row1['materialtypecode'],$row1['description'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='materialtype';
		parent::actionDownxls();
		$sql = "select materialtypeid,materialtypecode,description,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from materialtype a ";
		$materialtypeid = filter_input(INPUT_GET,'materialtypeid');
		$materialtypecode = filter_input(INPUT_GET,'materialtypecode');
		$description = filter_input(INPUT_GET,'description');
		$sql .= " where coalesce(a.materialtypeid,'') like '%".$materialtypeid."%' 
			and coalesce(a.materialtypecode,'') like '%".$materialtypecode."%'
			and coalesce(a.description,'') like '%".$description."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.materialtypeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by materialtypecode asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;		
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('materialtypeid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('materialtypecode'))			
			->setCellValueByColumnAndRow(2,2,GetCatalog('description'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['materialtypeid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['materialtypecode'])				
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);	
	}
}