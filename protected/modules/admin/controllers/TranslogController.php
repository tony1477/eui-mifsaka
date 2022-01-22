<?php
class TranslogController extends Controller {
	public $menuname = 'translog';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$translogid = isset ($_POST['translogid']) ? $_POST['translogid'] : '';
		$username = isset ($_POST['username']) ? $_POST['username'] : '';
		$createddate = isset ($_POST['createddate']) ? $_POST['createddate'] : '';
		$useraction = isset ($_POST['useraction']) ? $_POST['useraction'] : '';
		$newdata = isset ($_POST['newdata']) ? $_POST['newdata'] : '';
		$olddata = isset ($_POST['olddata']) ? $_POST['olddata'] : '';
		$menuname = isset ($_POST['menuname']) ? $_POST['menuname'] : '';
		$tableid = isset ($_POST['tableid']) ? $_POST['tableid'] : '';
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'translogid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;		
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('translog t')
			->where('(translogid like :translogid) and (username like :username) and (createddate like :createddate) and (useraction like :useraction) and (newdata like :newdata) and (olddata like :olddata) and (menuname like :menuname) and (tableid like :tableid)',
				array(':translogid'=>'%'.$translogid.'%',':username'=>'%'.$username.'%',':createddate'=>'%'.$createddate.'%',':useraction'=>'%'.$useraction.'%',':newdata'=>'%'.$newdata.'%',':olddata'=>'%'.$olddata.'%',':menuname'=>'%'.$menuname.'%',':tableid'=>'%'.$tableid.'%'))			
			->queryScalar();	
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('translog t')
			->where('(translogid like :translogid) and (username like :username) and (createddate like :createddate) and (useraction like :useraction) and (newdata like :newdata) and (olddata like :olddata) and (menuname like :menuname) and (tableid like :tableid)',
				array(':translogid'=>'%'.$translogid.'%',':username'=>'%'.$username.'%',':createddate'=>'%'.$createddate.'%',':useraction'=>'%'.$useraction.'%',':newdata'=>'%'.$newdata.'%',':olddata'=>'%'.$olddata.'%',':menuname'=>'%'.$menuname.'%',':tableid'=>'%'.$tableid.'%'))			
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'translogid'=>$data['translogid'],
				'username'=>$data['username'],
				'createddate'=>$data['createddate'],
				'useraction'=>$data['useraction'],
				'newdata'=>$data['newdata'],
				'olddata'=>$data['olddata'],
				'menuname'=>$data['menuname'],
				'tableid'=>$data['tableid'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) 	{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgetranslog(:vid,:vcreatedby)';
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
	  $sql = "select translogid,username,createddate,useraction,newdata,olddata,menuname,tableid
				from translog a ";
		$translogid = filter_input(INPUT_GET,'translogid');
		$useraction = filter_input(INPUT_GET,'useraction');
		$username = filter_input(INPUT_GET,'username');
		$newdata = filter_input(INPUT_GET,'newdata');
		$olddata = filter_input(INPUT_GET,'olddata');
		$menuname = filter_input(INPUT_GET,'menuname');
		$tableid = filter_input(INPUT_GET,'tableid');
		$sql .= " where coalesce(a.translogid,'') like '%".$translogid."%' 
			and coalesce(a.useraction,'') like '%".$useraction."%'
			and coalesce(a.username,'') like '%".$username."%'
			and coalesce(a.newdata,'') like '%".$newdata."%'
			and coalesce(a.olddata,'') like '%".$olddata."%'
			and coalesce(a.menuname,'') like '%".$menuname."%'
			and coalesce(a.tableid,'') like '%".$tableid."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.translogid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by username asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('translog');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('translogid'),
																	GetCatalog('username'),
																	GetCatalog('createddate'),
																	GetCatalog('useraction'),
																	GetCatalog('newdata'),
																	GetCatalog('olddata'),
																	GetCatalog('menuname'),
																	GetCatalog('tableid'));
		$this->pdf->setwidths(array(15,40,40,40,60,60,40,40));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['translogid'],$row1['username'],$row1['createddate'],$row1['useraction'],$row1['newdata'],$row1['olddata'],$row1['menuname'],$row1['tableid']));
		}
		$this->pdf->Output();
	}
	public function actionDownXls() {
		$this->menuname='translog';
		parent::actionDownxls();
		$sql = "select translogid,username,createddate,useraction,newdata,olddata,menuname,tableid
				from translog a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where a.translogid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by username asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();
		$i=2;			
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,2,GetCatalog('translogid'))
			->setCellValueByColumnAndRow(1,2,GetCatalog('username'))
			->setCellValueByColumnAndRow(2,2,GetCatalog('createddate'))
			->setCellValueByColumnAndRow(3,2,GetCatalog('useraction'))
			->setCellValueByColumnAndRow(4,2,GetCatalog('newdata'))
			->setCellValueByColumnAndRow(5,2,GetCatalog('olddata'))
			->setCellValueByColumnAndRow(6,2,GetCatalog('menuname'))
			->setCellValueByColumnAndRow(7,2,GetCatalog('tableid'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['translogid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['username'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['createddate'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['useraction'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['newdata'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['olddata'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['menuname'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['tableid']);
			$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}