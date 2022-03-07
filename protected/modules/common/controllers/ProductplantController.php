<?php
class ProductplantController extends Controller {
	public $menuname = 'productplant';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actiongetdata() {
		$uomid = '';
		$slocid = '';
		$cmd = '';
		$bomid = '';
		$companyid = isset($_POST['companyid'])?$_POST['companyid']:"'%%'";
		if(isset($_POST['productid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.unitofissue,t.slocid')
				->from('productplant t')
				->join('sloc a','a.slocid = t.slocid')
				->join('plant b','b.plantid = a.plantid')
				->join('company c','c.companyid = b.companyid')
				->where("productid = ".$_POST['productid']." and t.recordstatus = 1 and t.slocid in (".getUserObjectValues('sloc').")
					and c.companyid like ".$companyid)
				->limit(1)
				->queryRow();
			$uomid = $cmd['unitofissue'];
			$slocid = $cmd['slocid'];
			$cmd = Yii::app()->db->createCommand()
				->select('t.storagebinid')
				->from('storagebin t')
				->where('slocid = '.$slocid)
				->queryScalar();
			$storagebinid = $cmd;
			$cmd = Yii::app()->db->createCommand()
				->select('t.bomid')
				->from('billofmaterial t')
				->where("t.recordstatus = 1 and productid = ".$_POST['productid'])
				->limit(1)
				->queryScalar();
			$bomid = $cmd;
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'uomid'=> $uomid,
				'slocid'=>$slocid,
				'bomid'=>$bomid
				));
			Yii::app()->end();
		}
	}
	public function actiongetdatafpb() {
		$uomid = '';
		$slocid = '';
		$cmd = '';
		$bomid = '';
		$companyid = isset($_POST['companyid'])?$_POST['companyid']:"'%%'";
		if(isset($_POST['productid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.unitofissue,t.slocid')
				->from('productplant t')
				->join('sloc a','a.slocid = t.slocid')
				->join('plant b','b.plantid = a.plantid')
				->join('company c','c.companyid = b.companyid')
				->where("productid = ".$_POST['productid']." and t.recordstatus = 1 and t.issource = 1 and c.companyid like ".$companyid)
				->limit(1)
				->queryRow();
			$uomid = $cmd['unitofissue'];
			$slocid = $cmd['slocid'];
			$cmd = Yii::app()->db->createCommand()
				->select('t.bomid')
				->from('billofmaterial t')
				->where("t.recordstatus = 1 and productid = ".$_POST['productid'])
				->limit(1)
				->queryScalar();
			$bomid = $cmd;
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'uomid'=> $uomid,
				'slocid'=>$slocid,
				'bomid'=>$bomid
				));
			Yii::app()->end();
		}
	}
	public function actiongetdatasales() {
		$uomid = '';
		$slocid = '';
		$cmd = '';
		$bomid = '';
		$sloccode ='';
		$storagebinid = '';
		$companyid = isset($_POST['companyid'])?$_POST['companyid']:"'%%'";
		if(isset($_POST['productid']) && (isset($_POST['type']) && $_POST['type']=='productsales')) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.unitofissue,t.slocid,a.sloccode')
				->from('productplant t')
				->join('sloc a','a.slocid = t.slocid')
				->join('plant b','b.plantid = a.plantid')
				->join('company c','c.companyid = b.companyid')
				->where("productid = ".$_POST['productid']." 
									and t.recordstatus = 1
									and t.slocid in (".getUserObjectWfValues('sloc','appso').")")
				->limit(1)
				->queryRow();
			$uomid = $cmd['unitofissue'];
			$slocid = $cmd['slocid'];
			$sloccode = $cmd['sloccode'];			
			$storagebinid = 'null';
			$bomid = 'null';
		} else
		if(isset($_POST['productid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.unitofissue,t.slocid,a.sloccode')
				->from('productplant t')
				->join('sloc a','a.slocid = t.slocid')
				->join('plant b','b.plantid = a.plantid')
				->join('company c','c.companyid = b.companyid')
				->where("productid = ".$_POST['productid']." 
					and t.recordstatus = 1
					and t.slocid in (".getUserObjectValues('sloc').")
					and c.companyid in (".$companyid.")")
				->limit(1)
				->queryRow();
			$uomid = $cmd['unitofissue'];
			$slocid = $cmd['slocid'];
			$sloccode = $cmd['sloccode'];			
			$cmd = Yii::app()->db->createCommand()
				->select('t.storagebinid')
				->from('storagebin t')
				->where('slocid = '.$slocid)
				->queryRow();
			$storagebinid = $cmd['storagebinid'];
			$cmd = Yii::app()->db->createCommand()
				->select('t.bomid')
				->from('billofmaterial t')
				->where("productid = ".$_POST['productid'])
				->limit(1)
				->queryScalar();
			$bomid = $cmd;
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'uomid'=> $uomid,
				'slocid'=>$slocid,
				'bomid'=>$bomid,
				'storagebinid'=>$storagebinid,
				));
			Yii::app()->end();
		}
	}
  public function actiongetdatapp() {
		$uomid = '';
		$slocid = '';
		$cmd = '';
		$bomid = '';
		$companyid = isset($_POST['companyid'])?$_POST['companyid']:"'%%'";
		if(isset($_POST['productid']))
	  {
			$cmd = Yii::app()->db->createCommand()
				->select('t.unitofissue,t.slocid')
				->from('productplant t')
				->join('sloc a','a.slocid = t.slocid')
				->join('plant b','b.plantid = a.plantid')
				->join('company c','c.companyid = b.companyid')
				->where("productid = ".$_POST['productid']." and t.recordstatus = 1 and t.issource = 1 and t.slocid in (".getUserObjectValues('sloc').") 
					and c.companyid = ".$companyid)
				->limit(1)
				->queryRow();
			$uomid = $cmd['unitofissue'];
			$slocid = $cmd['slocid'];
			$bomid = Yii::app()->db->createCommand()
				->select('t.bomid')
				->from('billofmaterial t')
				->where("t.recordstatus = 1 and productid = ".$_POST['productid'])
				->limit(1)
				->queryScalar();
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'uomid'=> $uomid,
				'slocid'=>$slocid,
				'bomid'=>$bomid
				));
			Yii::app()->end();
		}
	}
    
    public function actiongetdataproduct() {
		$uomid = '';
		$slocid = '';
		$cmd = '';
		$bomid = '';
		$companyid = isset($_POST['companyid'])?$_POST['companyid']:"'%%'";
		if(isset($_POST['productid']))
	  {
			$cmd = Yii::app()->db->createCommand()
				->select('t.unitofissue,t.slocid')
				->from('productplant t')
				->join('sloc a','a.slocid = t.slocid')
				->join('plant b','b.plantid = a.plantid')
				->join('company c','c.companyid = b.companyid')
				->where("productid = ".$_POST['productid']." and t.recordstatus = 1 and t.issource = 1 
					and c.companyid = ".$companyid)
				->limit(1)
				->queryRow();
			$uomid = $cmd['unitofissue'];
			$slocid = $cmd['slocid'];
			$bomid = Yii::app()->db->createCommand()
				->select('t.bomid')
				->from('billofmaterial t')
				->where("t.recordstatus = 1 and productid = ".$_POST['productid'])
				->limit(1)
				->queryScalar();
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'uomid'=> $uomid,
				'slocid'=>$slocid,
				'bomid'=>$bomid
				));
			Yii::app()->end();
		}
	}
	public function search() {
		header("Content-Type: application/json");
		$productplantid = isset ($_POST['productplantid']) ? $_POST['productplantid'] : '';
		$product = isset ($_POST['product']) ? $_POST['product'] : '';
		$sloc = isset ($_POST['sloc']) ? $_POST['sloc'] : '';
		$unitofissue = isset ($_POST['unitofissue']) ? $_POST['unitofissue'] : '';
		$sled = isset ($_POST['sled']) ? $_POST['sled'] : '';
		$snro = isset ($_POST['snro']) ? $_POST['snro'] : '';
		$materialgroup = isset ($_POST['materialgroup']) ? $_POST['materialgroup'] : '';
		$mgprocesscode = isset ($_POST['mgprocesscode']) ? $_POST['mgprocesscode'] : '';
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'productplantid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('productplant t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('snro q','q.snroid=t.snroid')
			->leftjoin('sloc r','r.slocid=t.slocid')
			->leftjoin('unitofmeasure s','s.unitofmeasureid=t.unitofissue')
			->leftjoin('materialgroup a','a.materialgroupid=t.materialgroupid')			
			->leftjoin('mgprocess b','b.mgprocessid=t.mgprocessid')			
			->where("(coalesce(t.productplantid,'') like :productplantid) and 
				(coalesce(p.productname,'') like :product) and 
				(coalesce(r.sloccode,'') like :sloc) and 
				(coalesce(s.uomcode,'') like :unitofissue) and 
				(coalesce(q.description,'') like :snro) and 
				(coalesce(a.materialgroupcode,'') like :materialgroup) and
				(coalesce(b.mgprocesscode,'') like :mgprocesscode)",
					array(':product'=>'%'.$product.'%',
							':sloc'=>'%'.$sloc.'%',
							':unitofissue'=>'%'.$unitofissue.'%',
							':snro'=>'%'.$snro.'%',
							':materialgroup'=>'%'.$materialgroup.'%',
							':productplantid'=>'%'.$productplantid.'%',
							':mgprocesscode'=>'%'.$mgprocesscode.'%'))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,q.description as snrodesc,p.productname,r.sloccode, r.description as slocdesc,s.uomcode,a.materialgroupcode,b.mgprocesscode')	
			->from('productplant t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('snro q','q.snroid=t.snroid')
			->leftjoin('sloc r','r.slocid=t.slocid')
			->leftjoin('unitofmeasure s','s.unitofmeasureid=t.unitofissue')
			->leftjoin('materialgroup a','a.materialgroupid=t.materialgroupid')
			->leftjoin('mgprocess b','b.mgprocessid=t.mgprocessid')
			->where("(coalesce(t.productplantid,'') like :productplantid) and 
				(coalesce(p.productname,'') like :product) and 
				(coalesce(r.sloccode,'') like :sloc) and 
				(coalesce(s.uomcode,'') like :unitofissue) and 
				(coalesce(q.description,'') like :snro) and 
				(coalesce(a.materialgroupcode,'') like :materialgroup) and
                (coalesce(b.mgprocesscode,'') like :mgprocesscode)",
					array(':product'=>'%'.$product.'%',
							':sloc'=>'%'.$sloc.'%',
							':unitofissue'=>'%'.$unitofissue.'%',
							':snro'=>'%'.$snro.'%',
							':materialgroup'=>'%'.$materialgroup.'%',
							':productplantid'=>'%'.$productplantid.'%',
							':mgprocesscode'=>'%'.$mgprocesscode.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'productplantid'=>$data['productplantid'],
				'productid'=>$data['productid'],
				'productname'=>$data['productname'],
				'slocid'=>$data['slocid'],
				'sloccode'=>$data['sloccode'].'-'.$data['slocdesc'],
				'unitofissue'=>$data['unitofissue'],
				'uomcode'=>$data['uomcode'],
				'isautolot'=>$data['isautolot'],
				'sled'=>$data['sled'],
				'issource'=>$data['issource'],
				'snroid'=>$data['snroid'],
				'snrodesc'=>$data['snrodesc'],
				'materialgroupid'=>$data['materialgroupid'],
				'mgprocessid'=>$data['mgprocessid'],
				'materialgroupcode'=>$data['materialgroupcode'],
				'mgprocesscode'=>$data['mgprocesscode'],
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		$olddata = null;
		if ($id == '') {
			$title='Add';
			$sql = 'call Insertproductplant(:vproductid,:vslocid,:vunitofissue,:visautolot,:vsled,:vsnroid,:vmaterialgroupid,:vmgprocessid,:vrecordstatus,:vissource,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$title='Ubah';
			$olddata = Yii::app()->db->createCommand("select p1.productname,s.sloccode,u.uomcode,sn.description as penomoran, m.description as matgroup,mg.description as mgprocess,sled, issource,p.recordstatus
					from productplant p
					join product p1 on p1.productid = p.productid
					join sloc s on s.slocid = p.slocid
					join unitofmeasure u on u.unitofmeasureid = p.unitofissue
					join snro sn on sn.snroid = p.snroid
					join materialgroup m on m.materialgroupid = p.materialgroupid
					join mgprocess mg on mg.mgprocessid = p.mgprocessid
					where p.productplantid = ".$arraydata[0])->queryAll();
			$sql = 'call Updateproductplant(:vid,:vproductid,:vslocid,:vunitofissue,:visautolot,:vsled,:vsnroid,:vmaterialgroupid,:vmgprocessid,:vrecordstatus,:vissource,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vproductid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vslocid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vunitofissue',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':visautolot',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vsled',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialgroupid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vmgprocessid',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vsnroid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vissource',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
		$this->SendNotifWA($title,$olddata,$arraydata);
	}
	private function SendNotifWA($title,$olddata=null,$newdata) 
	{
		$oldtext = "Menu = ".$this->menuname."\n";
		if($olddata!=null) {
			foreach($olddata as $value) { 
				 $oldtext .= "Data Lama ID : ".$newdata[0]."\nProductname = ".$value['productname']."\nGudang = ".$value['sloccode']."\nSatuan = ".$value['uomcode']."\nMasa Garansi/Expired = ".$value['sled']."\nSistem Penomoran = ".$value['penomoran']."\nGroup Material = ".$value['matgroup']."\nMaterial Group Process = ".$value['mgprocess']."\nSumber = ".$value['issource']."\nStatus = ".$value['recordstatus']."\n\n";
			}
		}
		$judul = '';
		$user = Yii::app()->db->createCommand("select realname from useraccess where username='".Yii::app()->user->name."'")->queryScalar();
		$judul = ($title == 'Add' ? 'Data Baru ditambahkan oleh '.$user : 'Data Telah Diubah oleh : '.$user);
		//$text = '';
		
		$productname = Yii::app()->db->createCommand('select productname from product where productid='.$newdata[1])->queryScalar();
		$sloc = Yii::app()->db->createCommand('select sloccode from sloc where slocid='.$newdata[2])->queryScalar();
		$uom = Yii::app()->db->createCommand('select uomcode from unitofmeasure where unitofmeasureid='.$newdata[3])->queryScalar();
		$isautolot = $newdata[4];
		$sled = $newdata[5];
		$mgroup =  Yii::app()->db->createCommand('select description from materialgroup where materialgroupid='.$newdata[6])->queryScalar();
		$snro =  Yii::app()->db->createCommand('select description from snro where snroid='.$newdata[7])->queryScalar();
		$issource = $newdata[8];
		$status = $newdata[9];
		$mgprocess =  Yii::app()->db->createCommand('select description from mgprocess where mgprocessid='.$newdata[10])->queryScalar();
		

		 $text = $oldtext.$judul."\nProductname = ".$productname."\nGudang = ".$sloc."\nSatuan = ".$uom."\nLot? = ".$isautolot."\nGaransi/Expired = ".$sled."\nMaterial Group? = ".$mgroup."\nGroup Process? = ".$mgprocess."\nSistem Penomoran = ".$snro."\nIssource ? = ".$issource."\nSatus? = ".$status;

		//$text=$oldtext.$judul;
		$siaga = "bf1ea6ba-ecc5-488e-9d6a-d75947ecebcf";
		//$pesanwa = "$text;
		$wano = '6285376361879';
		sendwajapri($siaga,$text,$wano);
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-productplant"]["name"]);
		if (move_uploaded_file($_FILES["file-productplant"]["tmp_name"], $target_file)) {
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
					$productname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
					$sloccode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$uomcode = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
					$isautolot = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$sled = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$materialgroupdesc = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$materialgroupid = Yii::app()->db->createCommand("select materialgroupid from materialgroup where description = '".$materialgroupdesc."'")->queryScalar();
					$snrodesc = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$snroid = Yii::app()->db->createCommand("select snroid from snro where description = '".$snrodesc."'")->queryScalar();
					$issource = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$recordstatus = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $mgprocess = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$mgprocessid = Yii::app()->db->createCommand("select mgprocessid from mgprocess where description = '".$mgprocess."'")->queryScalar();
					$this->ModifyData($connection,array($id,$productid,$slocid,$uomid,$isautolot,$sled,$materialgroupid,$snroid,$issource,$recordstatus,$mgprocessid));
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
			$this->ModifyData($connection,array((isset($_POST['productplantid'])?$_POST['productplantid']:''),$_POST['productid'],$_POST['slocid'],$_POST['unitofissue'],$_POST['isautolot'],$_POST['sled'],$_POST['materialgroupid'],$_POST['snroid'],$_POST['issource'],$_POST['recordstatus'],$_POST['mgprocessid']));
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
				$sql = 'call Purgeproductplant(:vid,:vcreatedby)';
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
	  $sql = "select a.productplantid,b.productname,concat(c.sloccode,'-',c.description) as slocdesc,
					e.uomcode,isautolot,sled,d.description as snrodesc,a.recordstatus
				from productplant a 
				left join product b on b.productid = a.productid 
				left join sloc c on c.slocid = a.slocid 
				left join snro d on d.snroid = a.snroid 
				left join unitofmeasure e on e.unitofmeasureid = a.unitofissue 
                left join materialgroup f on f.materialgroupid = a.materialgroupid
                left join mgprocess g on g.mgprocessid = a.mgprocessid";
		$productplantid = filter_input(INPUT_GET,'productplantid');
		$productname = filter_input(INPUT_GET,'product');
		$sloccode = filter_input(INPUT_GET,'sloc');
		$uomcode = filter_input(INPUT_GET,'uomcode');
		$snro = filter_input(INPUT_GET,'snro');
		$materialgroup = filter_input(INPUT_GET,'materialgroup');
		$mgprocesscode = filter_input(INPUT_GET,'mgprocesscode');
		$sql .= " where coalesce(a.productplantid,'') like '%".$productplantid."%' 
			and coalesce(b.productname,'') like '%".$productname."%'
			and coalesce(c.sloccode,'') like '%".$sloccode."%'
			and coalesce(e.uomcode,'') like '%".$uomcode."%'
			and coalesce(d.description,'') like '%".$snro."%'
			and coalesce(f.materialgroupcode,'') like '%".$materialgroup."%'
			and coalesce(g.mgprocesscode,'') like '%".$mgprocesscode."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.productplantid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('productplant');
		$this->pdf->AddPage('L');
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(
                GetCatalog('No'),
                GetCatalog('product'),
                GetCatalog('sloc'),
                GetCatalog('uom'),
                GetCatalog('isautolot'),
                GetCatalog('sled'),
                GetCatalog('snro'),
                GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(14,80,38,25,10,45,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L');
        $i=1;
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($i,$row1['productname'],$row1['slocdesc'],$row1['uomcode'],$row1['isautolot'],$row1['sled'],$row1['snrodesc'],$row1['recordstatus']));
            $i++;
		}
		$this->pdf->Output();
	}
	public function actionDownXLS() {
		parent::actionDownxls();
		$sql = "select a.productplantid,b.productname,c.sloccode as slocdesc,
					e.uomcode,isautolot,sled,d.description as snrodesc,a.recordstatus, a.issource, f.description as materialgroupcode, g.description as mgprocesscode
				from productplant a 
				left join product b on b.productid = a.productid 
				left join sloc c on c.slocid = a.slocid 
				left join snro d on d.snroid = a.snroid 
				left join unitofmeasure e on e.unitofmeasureid = a.unitofissue
                left join materialgroup f on f.materialgroupid = a.materialgroupid
                left join mgprocess g on g.mgprocessid = a.mgprocessid";
		$productplantid = filter_input(INPUT_GET,'productplantid');
		$productname = filter_input(INPUT_GET,'product');
		$sloccode = filter_input(INPUT_GET,'sloc');
		$uomcode = filter_input(INPUT_GET,'uomcode');
		$snro = filter_input(INPUT_GET,'snro');
		$materialgroup = filter_input(INPUT_GET,'materialgroup');
        $mgprocesscode = filter_input(INPUT_GET,'mgprocesscode');
		$sql .= " where coalesce(a.productplantid,'') like '%".$productplantid."%' 
			and coalesce(b.productname,'') like '%".$productname."%'
			and coalesce(c.sloccode,'') like '%".$sloccode."%'
			and coalesce(e.uomcode,'') like '%".$uomcode."%'
			and coalesce(d.description,'') like '%".$snro."%'
			and coalesce(f.materialgroupcode,'') like '%".$materialgroup."%'
            and coalesce(g.mgprocesscode,'') like '%".$mgprocesscode."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.productplantid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $this->phpExcel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,1,GetCatalog('productplantid'))
			->setCellValueByColumnAndRow(1,1,GetCatalog('product'))
			->setCellValueByColumnAndRow(2,1,GetCatalog('sloc'))
			->setCellValueByColumnAndRow(3,1,GetCatalog('uom'))
			->setCellValueByColumnAndRow(4,1,GetCatalog('isautolot'))
			->setCellValueByColumnAndRow(5,1,GetCatalog('sled'))
			->setCellValueByColumnAndRow(6,1,GetCatalog('materialgroupcode'))
			->setCellValueByColumnAndRow(7,1,GetCatalog('snro'))
			->setCellValueByColumnAndRow(8,1,GetCatalog('issource'))
			->setCellValueByColumnAndRow(9,1,GetCatalog('recordstatus'))
			->setCellValueByColumnAndRow(10,1,GetCatalog('mgprocesscode'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['productplantid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['productname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['slocdesc'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['uomcode'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['isautolot'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['sled'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['materialgroupcode'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['snrodesc'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['issource'])
				->setCellValueByColumnAndRow(9, $i+1, $row1['recordstatus'])
				->setCellValueByColumnAndRow(10, $i+1, $row1['mgprocesscode']);
			$i+=1;
		}
		$this->getfooterXls($this->phpExcel);
	}
	/*public function actionDownxls() {
		parent::actionDownxls();
		$sql = "select a.productplantid,b.productname,concat(c.sloccode,'-',c.description) as slocdesc,
					e.uomcode,isautolot,sled,d.description as snrodesc,a.recordstatus
				from productplant a 
				left join product b on b.productid = a.productid 
				left join sloc c on c.slocid = a.slocid 
				left join snro d on d.snroid = a.snroid 
				left join unitofmeasure e on e.unitofmeasureid = a.unitofissue ";
		$productplantid = filter_input(INPUT_GET,'productplantid');
		$productname = filter_input(INPUT_GET,'product');
		$sloccode = filter_input(INPUT_GET,'sloc');
		$uomcode = filter_input(INPUT_GET,'uomcode');
		$snro = filter_input(INPUT_GET,'snro');
		$sql .= " where coalesce(a.productplantid,'') like '%".$productplantid."%' 
			and coalesce(b.productname,'') like '%".$productname."%'
			and coalesce(c.sloccode,'') like '%".$sloccode."%'
			and coalesce(e.uomcode,'') like '%".$uomcode."%'
			and coalesce(d.description,'') like '%".$snro."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.productplantid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		 $this->phpExcel=Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
			->setCellValueByColumnAndRow(0,1,GetCatalog('productplantid'))
			->setCellValueByColumnAndRow(1,1,GetCatalog('product'))
			->setCellValueByColumnAndRow(2,1,GetCatalog('sloc'))
			->setCellValueByColumnAndRow(3,1,GetCatalog('uom'))
			->setCellValueByColumnAndRow(4,1,GetCatalog('isautolot'))
			->setCellValueByColumnAndRow(5,1,GetCatalog('sled'))
			->setCellValueByColumnAndRow(6,1,GetCatalog('snro'))
			->setCellValueByColumnAndRow(7,1,GetCatalog('recordstatus'));
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['productplantid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['productname'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['slocdesc'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['uomcode'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['isautolot'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['sled'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['snrodesc'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['recordstatus']);
			$i+=1;
		}
		$this->getfooterXls($this->phpExcel);
	}*/
}