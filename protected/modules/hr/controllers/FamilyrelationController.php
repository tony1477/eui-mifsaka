<?php
class FamilyrelationController extends Controller {
	public $menuname = 'familyrelation';
	public function actionIndex() {
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertfamilyrelation(:vfamilyrelationname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updatefamilyrelation(:vid,:vfamilyrelationname,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vfamilyrelationname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionSave() {
		header("Content-Type: application/json");
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->ModifyData($connection,array((isset($_POST['familyrelationid'])?$_POST['familyrelationid']:''),
				$_POST['familyrelationname'],$_POST['recordstatus']));			
			$transaction->commit();
			getmessage(false,'insertsuccess');
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			getmessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		header("Content-Type: application/json");
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgefamilyrelation(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				getmessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else {
			getmessage(true,'chooseone');
		}
	}
	public function search() {
		header("Content-Type: application/json");
		$familyrelationid = isset ($_POST['familyrelationid']) ? $_POST['familyrelationid'] : '';
		$familyrelationname = isset ($_POST['familyrelationname']) ? $_POST['familyrelationname'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$familyrelationid = isset ($_GET['q']) ? $_GET['q'] : $familyrelationid;
		$familyrelationname = isset ($_GET['q']) ? $_GET['q'] : $familyrelationname;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 't.familyrelationid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('familyrelation t')
				->where('(familyrelationname like :familyrelationname)',
						array(':familyrelationname'=>'%'.$familyrelationname.'%'
                                                    ))
				->queryRow();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('familyrelation t')
				->where('((familyrelationname like :familyrelationname)) and t.recordstatus=1',
						array(':familyrelationname'=>'%'.$familyrelationname.'%'
                                                    ))
				->queryRow();
		}
	
		$result['total'] = $cmd['total'];
		
		if (!isset($_GET['combo']))
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('familyrelation t')
				->where('(familyrelationname like :familyrelationname)',
						array(':familyrelationname'=>'%'.$familyrelationname.'%'
                                                    ))
				->offset($offset)
                                ->limit($rows)
                                ->order($sort.' '.$order)
                                ->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select()	
				->from('familyrelation t')
				->where('((familyrelationname like :familyrelationname)) and t.recordstatus=1',
						array(':familyrelationname'=>'%'.$familyrelationname.'%'
                                                    ))
				->offset($offset)
                                ->limit($rows)
                                ->order($sort.' '.$order)
                                ->queryAll();
		}
		
		foreach($cmd as $data)
		{	
			$row[] = array(
                        'familyrelationid'=>$data['familyrelationid'],
                        'familyrelationname'=>$data['familyrelationname'],
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
	  $sql = "select familyrelationid,familyrelationname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from familyrelation a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.familyrelationid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by familyrelationname asc ";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('familyrelation');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('familyrelationid'),
																	getCatalog('familyrelationname'),
																	getCatalog('recordstatus'));
		$this->pdf->setwidths(array(15,155,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['familyrelationid'],$row1['familyrelationname'],$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()
	{
		$this->menuname='familyrelation';
		parent::actionDownxls();
		$sql = "select familyrelationid,familyrelationname,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from familyrelation a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . "where a.familyrelationid in (".$_GET['id'].")";
		}
		else
		{
			$sql = $sql . "order by familyrelationname asc ";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['familyrelationid'])
				->setCellValueByColumnAndRow(1,$i,$row1['familyrelationname'])				
				->setCellValueByColumnAndRow(2,$i,$row1['recordstatus']);
			$i++;
		}
		
		$this->getFooterXLS($this->phpExcel);
	}
	
	
	/*public function actionDownxls()
	{
		parent::actionDownload();
		$sql = "select familyrelationname,recordstatus
				from familyrelation a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.familyrelationid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $excel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$excel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,getCatalog('familyrelationname'))
                ->setCellValueByColumnAndRow(1,1,getCatalog('recordstatus'))
                ;		foreach($dataReader as $row1)
		{
			  $excel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['familyrelationname'])
                                ->setCellValueByColumnAndRow(1, $i+1, $row1['recordstatus'])
                                ;		$i+=1;
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="familyrelation.xlsx"');
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
