<?php
class ProductController extends Controller {
	public $menuname = 'product';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$productid = isset ($_POST['productid']) ? $_POST['productid'] : '';
		$isstock = isset ($_POST['isstock']) ? $_POST['isstock'] : '';
		$isfohulbom = isset ($_POST['isfohulbom']) ? $_POST['isfohulbom'] : '';
		$iscontinue = isset ($_POST['iscontinue']) ? $_POST['iscontinue'] : '';
		$productname = isset ($_POST['productname']) ? $_POST['productname'] : '';
		$productpic = isset ($_POST['productpic']) ? $_POST['productpic'] : '';
		$barcode = isset ($_POST['barcode']) ? $_POST['barcode'] : '';
		$identityname = isset ($_POST['identityname']) ? $_POST['identityname'] : '';
		$brandname = isset ($_POST['brandname']) ? $_POST['brandname'] : '';
		$collectionname = isset ($_POST['collectionname']) ? $_POST['collectionname'] : '';
		$productseries = isset ($_POST['productseries']) ? $_POST['productseries'] : '';
		$leadtime = isset ($_POST['leadtime']) ? $_POST['leadtime'] : '';
    $k3lnumber = isset ($_POST['k3lnumber']) ? $_POST['k3lnumber'] : '';
		$length = isset ($_POST['length']) ? $_POST['length'] : '';
		$width = isset ($_POST['width']) ? $_POST['width'] : '';
		$height = isset ($_POST['height']) ? $_POST['height'] : '';
		$materialtype = isset ($_POST['materialtype']) ? $_POST['materialtype'] : '';
		$density = isset ($_POST['density']) ? $_POST['density'] : '';
		$recordstatus = isset ($_POST['recordstatus']) ? $_POST['recordstatus'] : '';
		$productid = isset ($_GET['q']) ? $_GET['q'] : $productid;
		$isstock = isset ($_GET['q']) ? $_GET['q'] : $isstock;
		$isfohulbom = isset ($_GET['q']) ? $_GET['q'] : $isfohulbom;
		$iscontinue = isset ($_GET['q']) ? $_GET['q'] : $iscontinue;
		$productname = isset ($_GET['q']) ? $_GET['q'] : $productname;
		$productpic = isset ($_GET['q']) ? $_GET['q'] : $productpic;
		$barcode = isset ($_GET['q']) ? $_GET['q'] : $barcode;
		$identityname = isset ($_GET['q']) ? $_GET['q'] : $identityname;
		$brandname = isset ($_GET['q']) ? $_GET['q'] : $brandname;
		$collectionname = isset ($_GET['q']) ? $_GET['q'] : $collectionname;
		$productseries = isset ($_GET['q']) ? $_GET['q'] : $productseries;
		$leadtime = isset ($_GET['q']) ? $_GET['q'] : $leadtime;
    $k3lnumber = isset ($_GET['q']) ? $_GET['q'] : $k3lnumber;
		$length = isset ($_GET['q']) ? $_GET['q'] : $length;
		$width = isset ($_GET['q']) ? $_GET['q'] : $width;
		$height = isset ($_GET['q']) ? $_GET['q'] : $height;
		$materialtype = isset ($_GET['q']) ? $_GET['q'] : $materialtype;
		$density = isset ($_GET['q']) ? $_GET['q'] : $density;
		$recordstatus = isset ($_GET['q']) ? $_GET['q'] : $recordstatus;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'productid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
        $materialtypeid = '';
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('product t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('productidentity b','b.productidentityid = t.productidentityid')
				->leftjoin('productbrand c','c.productbrandid = t.productbrandid')
				->leftjoin('productcollection d','d.productcollectid = t.productcollectid')
				->leftjoin('productseries e','e.productseriesid = t.productseriesid')
				->where("((t.productid like :productid) or (t.productname like :productname) or (t.barcode like :barcode)) 
					and t.recordstatus = 1 ",
					array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',
							':barcode'=>'%'.$barcode.'%'))
				->queryScalar();
		}
		else
		if (isset($_GET['trx'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('product t')
				->leftjoin('productstock a','a.productid = t.productid')
				->join('sloc b','b.slocid = a.slocid')
				->join('storagebin c','c.storagebinid = a.storagebinid')
				->where("((t.productid like :productid) or (t.productname like :productname) or (t.barcode like :barcode)) 
						and t.recordstatus = 1 and  
						a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',
							':barcode'=>'%'.$barcode.'%'
							))
				->queryScalar();
		}
		else
		if (isset($_GET['trxplant'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('product t')
				->leftjoin('productplant a1','a1.productid = t.productid')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('productidentity b','b.productidentityid = t.productidentityid')
				->leftjoin('productbrand c','c.productbrandid = t.productbrandid')
				->leftjoin('productcollection d','d.productcollectid = t.productcollectid')
				->leftjoin('productseries e','e.productseriesid = t.productseriesid')
				->where("((t.productid like :productid) or (t.productname like :productname) or (t.barcode like :barcode)) 
					and t.recordstatus = 1 and a1.recordstatus = 1 and slocid in (".getUserObjectValues('sloc').")",
					array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',
							':barcode'=>'%'.$barcode.'%'))
				->queryScalar();
		}
		else
		if (isset($_GET['trxplantmattype'])) {
            if(isset($_GET['materialtypeid']) && $_GET['materialtypeid']!='') {
                $materialtypeid = " and t.materialtypeid = ".$_GET['materialtypeid'];
            } else {
                $materialtypeid = " and t.materialtypeid = 0 ";
            }
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('product t')
				->leftjoin('productplant a1','a1.productid = t.productid')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('productidentity b','b.productidentityid = t.productidentityid')
				->leftjoin('productbrand c','c.productbrandid = t.productbrandid')
				->leftjoin('productcollection d','d.productcollectid = t.productcollectid')
				->leftjoin('productseries e','e.productseriesid = t.productseriesid')
				->where("((t.productid like :productid) or (t.productname like :productname) or (t.barcode like :barcode)) 
					{$materialtypeid} and t.recordstatus = 1 and a1.recordstatus = 1 and slocid in (".getUserObjectValues('sloc').")",
					array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',
							':barcode'=>'%'.$barcode.'%'))
				->queryScalar();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('product t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('productidentity b','b.productidentityid = t.productidentityid')
				->leftjoin('productbrand c','c.productbrandid = t.productbrandid')
				->leftjoin('productcollection d','d.productcollectid = t.productcollectid')
				->leftjoin('productseries e','e.productseriesid = t.productseriesid')
				->where("(coalesce(t.productid,'') like :productid) and (coalesce(t.productname,'') like :productname) and (coalesce(t.barcode,'') like :barcode) and (coalesce(b.identityname,'') like :identityname) and (coalesce(c.brandname,'') like :brandname) and (coalesce(d.collectionname,'') like :collectionname) and (coalesce(e.description,'') like :productseries) and (coalesce(t.panjang,'') like :length) and (coalesce(t.lebar,'') like :width) and (coalesce(t.tinggi,'') like :height) and (coalesce(t.isstock,'') like :isstock) and (coalesce(t.isfohulbom,'') like :isfohulbom) and (coalesce(t.iscontinue,'') like :iscontinue) and (coalesce(a.description,'') like :materialtype) and (coalesce(t.recordstatus,'') like :recordstatus) ",
					array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',':barcode'=>'%'.$barcode.'%',':identityname'=>'%'.$identityname.'%',':brandname'=>'%'.$brandname.'%',':collectionname'=>'%'.$collectionname.'%',':productseries'=>'%'.$productseries.'%',':length'=>'%'.$length.'%',':width'=>'%'.$width.'%',':height'=>'%'.$height.'%',':isstock'=>'%'.$isstock.'%',':isfohulbom'=>'%'.$isfohulbom.'%',':iscontinue'=>'%'.$iscontinue.'%',':materialtype'=>'%'.$materialtype.'%',':recordstatus'=>'%'.$recordstatus.'%'))
				->queryScalar();
		}
		$result['total'] = $cmd;
		if (isset($_GET['combo'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, (0) as qty,(null) as sloccode,(null) as slocdesc, (null) as rak, a.description, a.materialtypeid, (null) as materialtypedesc, (null) as identitydesc, (null) as branddesc, (null) as collectdesc, (null) as seriesdesc')	
				->from('product t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('productidentity b','b.productidentityid = t.productidentityid')
				->leftjoin('productbrand c','c.productbrandid = t.productbrandid')
				->leftjoin('productcollection d','d.productcollectid = t.productcollectid')
				->leftjoin('productseries e','e.productseriesid = t.productseriesid')
				->where("((t.productid like :productid) or (t.productname like :productname) or (t.barcode like :barcode)) 
					and t.recordstatus = 1 ",
					array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',
							':barcode'=>'%'.$barcode.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		if (isset($_GET['trx'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.productid,t.isstock,t.isfohulbom,t.iscontinue,t.productpic,t.barcode,t.panjang,t.lebar,t.tinggi,t.recordstatus,t.productname,a.qty,b.sloccode,b.description as slocdesc,c.description as rak, (null) as materialtypeid, (null) as description, (null) as materialtypedesc, (null) as identitydesc, (null) as branddesc, (null) as collectdesc, (null) as seriesdesc, ifnull(t.productseriesid,0) as productseriesid,k3lnumber')	
				->from('product t')
				->leftjoin('productstock a','a.productid = t.productid')
				->join('sloc b','b.slocid = a.slocid')
				->join('storagebin c','c.storagebinid = a.storagebinid')
				->where("((t.productid like :productid) or (t.productname like :productname) or (t.barcode like :barcode)) 
					and t.recordstatus = 1 and
					a.slocid in (".getUserObjectValues('sloc').")",
						array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',
								':barcode'=>'%'.$barcode.'%'
								))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		if (isset($_GET['trxplant'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, (0) as qty,(null) as sloccode,(null) as slocdesc, (null) as rak, a.description, a.materialtypeid, (null) as materialtypedesc, (null) as identitydesc, (null) as branddesc, (null) as collectdesc, (null) as seriesdesc')	
				->from('product t')
				->leftjoin('productplant a1','a1.productid = t.productid')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('productidentity b','b.productidentityid = t.productidentityid')
				->leftjoin('productbrand c','c.productbrandid = t.productbrandid')
				->leftjoin('productcollection d','d.productcollectid = t.productcollectid')
				->leftjoin('productseries e','e.productseriesid = t.productseriesid')
				->where("((t.productid like :productid) or (t.productname like :productname) or (t.barcode like :barcode)) 
					and  t.recordstatus = 1 and a1.recordstatus = 1 and slocid in (".getUserObjectValues('sloc').")",
					array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',
							':barcode'=>'%'.$barcode.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		if (isset($_GET['trxplantmattype'])) {
            if(isset($_GET['materialtypeid']) && $_GET['materialtypeid']!='') {
                $materialtypeid = " and t.materialtypeid = ".$_GET['materialtypeid'];
            } else {
                $materialtypeid = " and t.materialtypeid = 0 ";
            }
			$cmd = Yii::app()->db->createCommand()
				->select('t.*, (0) as qty,(null) as sloccode,(null) as slocdesc, (null) as rak, a.description, a.materialtypeid, (null) as materialtypedesc, (null) as identitydesc, (null) as branddesc, (null) as collectdesc, (null) as seriesdesc')	
				->from('product t')
				->leftjoin('productplant a1','a1.productid = t.productid')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('productidentity b','b.productidentityid = t.productidentityid')
				->leftjoin('productbrand c','c.productbrandid = t.productbrandid')
				->leftjoin('productcollection d','d.productcollectid = t.productcollectid')
				->leftjoin('productseries e','e.productseriesid = t.productseriesid')
				->where("((t.productid like :productid) or (t.productname like :productname) or (t.barcode like :barcode)) 
					{$materialtypeid} and  t.recordstatus = 1 and a1.recordstatus = 1 and slocid in (".getUserObjectValues('sloc').")",
					array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',
							':barcode'=>'%'.$barcode.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		else
		{
			$cmd = Yii::app()->db->createCommand()
				->select("t.*, (0) as qty, (null) as sloccode,(null) as slocdesc,(null) as rak, a.description as materialtypedesc, a.materialtypeid, a.description as materialtypedesc, b.identityname as identitydesc, c.brandname as branddesc, d.collectionname as collectdesc, e.description as seriesdesc ")	
				->from('product t')
				->leftjoin('materialtype a','a.materialtypeid = t.materialtypeid')
				->leftjoin('productidentity b','b.productidentityid = t.productidentityid')
				->leftjoin('productbrand c','c.productbrandid = t.productbrandid')
				->leftjoin('productcollection d','d.productcollectid = t.productcollectid')
				->leftjoin('productseries e','e.productseriesid = t.productseriesid')
				->where("(coalesce(t.productid,'') like :productid) and (coalesce(t.productname,'') like :productname) and (coalesce(t.barcode,'') like :barcode) and (coalesce(b.identityname,'') like :identityname) and (coalesce(c.brandname,'') like :brandname) and (coalesce(d.collectionname,'') like :collectionname) and (coalesce(e.description,'') like :productseries) and (coalesce(t.panjang,'') like :length) and (coalesce(t.lebar,'') like :width) and (coalesce(t.tinggi,'') like :height) and (coalesce(t.isstock,'') like :isstock) and (coalesce(t.isfohulbom,'') like :isfohulbom) and (coalesce(t.iscontinue,'') like :iscontinue) and (coalesce(a.description,'') like :materialtype) and (coalesce(t.recordstatus,'') like :recordstatus)",
					array(':productid'=>'%'.$productid.'%',':productname'=>'%'.$productname.'%',':barcode'=>'%'.$barcode.'%',':identityname'=>'%'.$identityname.'%',':brandname'=>'%'.$brandname.'%',':collectionname'=>'%'.$collectionname.'%',':productseries'=>'%'.$productseries.'%',':length'=>'%'.$length.'%',':width'=>'%'.$width.'%',':height'=>'%'.$height.'%',':isstock'=>'%'.$isstock.'%',':isfohulbom'=>'%'.$isfohulbom.'%',':iscontinue'=>'%'.$iscontinue.'%',':materialtype'=>'%'.$materialtype.'%',':recordstatus'=>'%'.$recordstatus.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
		foreach($cmd as $data) {	
			$row[] = array(
				'productid'=>$data['productid'],
				'isstock'=>$data['isstock'],
				'isfohulbom'=>$data['isfohulbom'],
				'iscontinue'=>$data['iscontinue'],
				'qty'=>Yii::app()->format->formatNumber($data['qty']),
				'sloccode'=>$data['sloccode'],
				'materialtypeid'=>$data['materialtypeid'],
				'materialtypedesc'=>$data['materialtypedesc'],
				'productidentityid'=>$data['productidentityid'],
				'identitydesc'=>$data['identitydesc'],
        'productbrandid'=>$data['productbrandid'],
				'branddesc'=>$data['branddesc'],
				'productcollectid'=>$data['productcollectid'],
        'collectdesc'=>$data['collectdesc'],
				'productseriesid'=>$data['productseriesid'],
				'k3lnumber'=>$data['k3lnumber'],
				'seriesdesc'=>$data['seriesdesc'],
				'leadtime'=>$data['leadtime'],
				'slocdesc'=>$data['slocdesc'],
				'rak'=>$data['rak'],
				'productname'=>$data['productname'],
				'productpic'=>$data['productpic'],
				'barcode'=>$data['barcode'],
				'panjang'=>Yii::app()->format->formatNumber($data['panjang']),
				'lebar'=>Yii::app()->format->formatNumber($data['lebar']),
				'tinggi'=>Yii::app()->format->formatNumber($data['tinggi']),
				'density'=>Yii::app()->format->formatNumber($data['density']),
				'recordstatus'=>$data['recordstatus'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertproduct(:vproductname,:visstock,:visfohulbom,:viscontinue,:vproductpic,:vbarcode,:vk3lnumber,:vmaterialtypeid,:vproductidentityid,:vproductbrandid,:vproductcollectid,:vproductseriesid,:vleadtime,:vpanjang,:vlebar,:vtinggi,:vdensity,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproduct(:vid,:vproductname,:visstock,:visfohulbom,:viscontinue,:vproductpic,:vbarcode,:vk3lnumber,:vmaterialtypeid,:vproductidentityid,:vproductbrandid,:vproductcollectid,:vproductseriesid,:vleadtime,:vpanjang,:vlebar,:vtinggi,:vdensity,:vrecordstatus,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vproductname',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':visstock',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':visfohulbom',$arraydata[17],PDO::PARAM_STR);
		$command->bindvalue(':viscontinue',$arraydata[18],PDO::PARAM_STR);
		$command->bindvalue(':vproductpic',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vbarcode',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vk3lnumber',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialtypeid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vproductidentityid',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vproductbrandid',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vproductcollectid',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vproductseriesid',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vleadtime',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vpanjang',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vlebar',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vtinggi',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vdensity',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vrecordstatus',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();			
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-product"]["name"]);
		if (move_uploaded_file($_FILES["file-product"]["tmp_name"], $target_file)) {
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
					$productname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$productpic = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$issto = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$isstock = Yii::app()->db->createCommand("select case when '".$issto."' = 'Yes' then '1' else '0' end")->queryScalar();
					$isfoh = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
					$isfohulbom = Yii::app()->db->createCommand("select case when '".$isfoh."' = 'Yes' then '1' else '0' end")->queryScalar();
					$iscont = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
					$iscontinue = Yii::app()->db->createCommand("select case when '".$iscont."' = 'Yes' then '1' else '0' end")->queryScalar();
					$barcode = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$k3lnumber = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$materialtypecode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$materialtypeid = Yii::app()->db->createCommand("select materialtypeid from materialtype where materialtypecode = '".$materialtypecode."'")->queryScalar();
					$identity = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $identityid = Yii::app()->db->createCommand("select productidentityid from productidentity where identityname = '".$identity."'")->queryScalar();
					$brand = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $brandid = Yii::app()->db->createCommand("select productbrandid from productbrand where brandname = '".$brand."'")->queryScalar();
					$collection = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $collectionid = Yii::app()->db->createCommand("select productcollectid from productcollection where collectionname = '".$collection."'")->queryScalar();
					$series = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $seriesid = Yii::app()->db->createCommand("select productseriesid from productseries where description = '".$series."'")->queryScalar();
					$leadtime = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$panjang = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$lebar = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
					$tinggi = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
					$density = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
					$rec = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
					$recordstatus = Yii::app()->db->createCommand("select case when '".$rec."' = 'Yes' then '1' else '0' end")->queryScalar();
					$this->ModifyData($connection,array($id,$productname,$isstock,$productpic,$barcode,$materialtypeid,$identityid,$brandid,$collectionid,$seriesid,$leadtime,$panjang,$lebar,$tinggi,$recordstatus,$k3lnumber,$density,$isfohulbom,$iscontinue));
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
			$this->ModifyData($connection,array((isset($_POST['productid'])?$_POST['productid']:''),$_POST['productname'],$_POST['isstock'],$_POST['productpic'],$_POST['barcode'],$_POST['materialtypeid'],$_POST['productidentityid'],$_POST['productbrandid'],$_POST['productcollectid'],$_POST['productseriesid'],$_POST['leadtime'],$_POST['panjang'],$_POST['lebar'],$_POST['tinggi'],$_POST['recordstatus'],$_POST['k3lnumber'],$_POST['density'],$_POST['isfohulbom'],$_POST['iscontinue']));
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
				$sql = 'call Purgeproduct(:vid,:vcreatedby)';
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
	/*public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select a.*,
					substr(productname,1,(20+instr(substr(productname,21,20),' '))) as productname1,
					substr(productname,(21+instr(substr(productname,21,20),' ')),(20+instr(substr(productname,21,20),' '))) as productname2,
					substr(productname,((21+instr(substr(productname,21,20),' '))+(20+instr(substr(productname,21,20),' '))),(20+instr(substr(productname,21,20),' '))) as productname3
			from product a ";
		$productid = filter_input(INPUT_GET,'productid');
		$productname = filter_input(INPUT_GET,'productname');
		$sql .= " where coalesce(a.productid,'') like '%".$productid."%' 
			and coalesce(a.productname,'') like '%".$productname."%'"; 
		if ($_GET['id'] !== '') {
      $sql = $sql . " and a.productid in (" . $_GET['id'] . ")";
    }
    $fgs = Yii::app()->db->createCommand($sql)->queryAll();
    $this->pdf->AddPage('P', array(
      82.6,
      108.3425
    ));
    $x = 0; $y = 0; $hitung = 0; $i = 0;
    $this->pdf->isfooter = false;
    foreach ($fgs as $row) {
				$hitung += 1;
				if ($hitung % 2 != 0) {
					$x = 10;
					$this->pdf->SetFont('Arial', 'B', 5);
					$this->pdf->text($x-8, $y+4, $row['productname1']);
					$this->pdf->text($x-8, $y+6, $row['productname2']);				
					$this->pdf->text($x-8, $y+8, $row['productname3']);
					$this->pdf->SetFont('Arial', '', 5);
					if (is_numeric($row['barcode'])) {
						$this->pdf->EAN13($x-1, $y+8.5, $row['barcode'], $h=3, $w=.20);
					} 
					$this->pdf->sety($y);
				} else  {
      			$x = 50;
      			$this->pdf->SetFont('Arial', 'B', 5);
						$this->pdf->text($x-8, $y+4, $row['productname1']);
						$this->pdf->text($x-8, $y+6, $row['productname2']);				
						$this->pdf->text($x-8, $y+8, $row['productname3']);
      			$this->pdf->SetFont('Arial', '', 5);
						if (is_numeric($row['barcode'])) {
							$this->pdf->EAN13($x-1, $y+8.5, $row['barcode'], $h=3, $w=.20);
						}
      			$y = $this->pdf->gety()+21.75;
				}
			if ($y > 90) {
				$this->pdf->AddPage('P', array(
					82.6,
					108.3425
				));
				$x = 0; $y = 0;
			}
    }
    $this->pdf->Output();
	}*/
	public function actionDownPDF()	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select productid,productname,productpic,leadtime,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						case when isfohulbom = 1 then 'Yes' else 'No' end as isfohulbom,
						case when iscontinue = 1 then 'Yes' else 'No' end as iscontinue,
						barcode,panjang,lebar,tinggi,density,b.materialtypecode,
            c.identityname,d.brandname,e.collectionname,f.description as productseries,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus, k3lnumber
						from product a
						left join materialtype b on b.materialtypeid=a.materialtypeid 
						left join productidentity c on c.productidentityid=a.productidentityid
						left join productbrand d on d.productbrandid=a.productbrandid
						left join productcollection e on e.productcollectid=a.productcollectid
						left join productseries f on f.productseriesid=a.productseriesid
                        ";
		$productid = filter_input(INPUT_GET,'productid');
		$productname = filter_input(INPUT_GET,'productname');
		$barcode = filter_input(INPUT_GET,'barcode');
		$productidentity = filter_input(INPUT_GET,'productidentity');
		$productbrand = filter_input(INPUT_GET,'productbrand');
		$productcollection = filter_input(INPUT_GET,'productcollection');
		$productseries = filter_input(INPUT_GET,'productseries');
		$leadtime = filter_input(INPUT_GET,'leadtime');
		$length = filter_input(INPUT_GET,'length');
		$width = filter_input(INPUT_GET,'width');
		$height = filter_input(INPUT_GET,'height');
		$isstock = filter_input(INPUT_GET,'isstock');
		$isfohulbom = filter_input(INPUT_GET,'isfohulbom');
		$iscontinue = filter_input(INPUT_GET,'iscontinue');
		$materialtype = filter_input(INPUT_GET,'materialtype');
		$density = filter_input(INPUT_GET,'density');
		$recordstatus = filter_input(INPUT_GET,'recordstatus');
		$sql .= " where coalesce(a.productid,'') like '%".$productid."%' 
			and coalesce(a.productname,'') like '%".$productname."%'
			and coalesce(a.barcode,'') like '%".$barcode."%'
			and coalesce(c.identityname,'') like '%".$productidentity."%'
			and coalesce(d.brandname,'') like '%".$productbrand."%'
			and coalesce(e.collectionname,'') like '%".$productcollection."%'
			and coalesce(f.description,'') like '%".$productseries."%'
			and coalesce(a.leadtime,'') like '%".$leadtime."%'
			and coalesce(a.panjang,'') like '%".$length."%'
			and coalesce(a.lebar,'') like '%".$width."%'
			and coalesce(a.tinggi,'') like '%".$height."%'
			and coalesce(a.isstock,'') like '%".$isstock."%'
			and coalesce(a.isfohulbom,'') like '%".$isfohulbom."%'
			and coalesce(a.iscontinue,'') like '%".$iscontinue."%'
			and coalesce(b.description,'') like '%".$materialtype."%'
			and coalesce(a.density,'') like '%".$density."%'
			and coalesce(a.recordstatus,'') like '%".$recordstatus."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.productid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by productname asc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=GetCatalog('product');
		$this->pdf->AddPage('P',array(400,250));
		//masukkan posisi judul
		$this->pdf->setFont('Arial','B',8);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(GetCatalog('productid'),
			GetCatalog('productname'),
			GetCatalog('productpic'),
			GetCatalog('isstock'),
			GetCatalog('isfohulbom'),
			GetCatalog('iscontinue'),
			GetCatalog('barcode'),
      GetCatalog('materialtype'),
			GetCatalog('identityname'),
			GetCatalog('brandname'),
			GetCatalog('collectionname'),
			GetCatalog('productseries'),
			GetCatalog('leadtime'),
			GetCatalog('panjang'),
			GetCatalog('lebar'),
			GetCatalog('tinggi'),
			GetCatalog('recordstatus'),
			GetCatalog('k3lnumber'),
			GetCatalog('density'));
		$this->pdf->setwidths(array(22,90,25,20,20,20,45,25,20,20,20,20,20,20,20,20,20,20,20,20));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',8);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['productid'],$row1['productname'],$row1['productpic'],$row1['isstock'],$row1['isfohulbom'],$row1['iscontinue'],$row1['barcode'],$row1['materialtypecode'],$row1['identityname'],$row1['brandname'],$row1['collectionname'],$row1['productseries'],$row1['leadtime'],$row1['panjang'],$row1['lebar'],$row1['tinggi'],$row1['recordstatus'],$row1['k3lnumber'],$row1['density']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	public function actionDownxls()	{
		$this->menuname='product';
		parent::actionDownxls();
		$sql = "select productid,productname,productpic,leadtime,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						case when isfohulbom = 1 then 'Yes' else 'No' end as isfohulbom,
						case when iscontinue = 1 then 'Yes' else 'No' end as iscontinue,
						barcode,panjang,lebar,tinggi,density,b.materialtypecode,
                        c.identityname,d.brandname,e.collectionname,f.description as productseries,
						case when a.recordstatus = 1 then 'Yes' else 'No' end as recordstatus, k3lnumber
						from product a
						left join materialtype b on b.materialtypeid=a.materialtypeid 
						left join productidentity c on c.productidentityid=a.productidentityid
						left join productbrand d on d.productbrandid=a.productbrandid
						left join productcollection e on e.productcollectid=a.productcollectid
						left join productseries f on f.productseriesid=a.productseriesid
                        ";
		$productid = filter_input(INPUT_GET,'productid');
		$productname = filter_input(INPUT_GET,'productname');
		$barcode = filter_input(INPUT_GET,'barcode');
		$productidentity = filter_input(INPUT_GET,'productidentity');
		$productbrand = filter_input(INPUT_GET,'productbrand');
		$productcollection = filter_input(INPUT_GET,'productcollection');
		$productseries = filter_input(INPUT_GET,'productseries');
		$leadtime = filter_input(INPUT_GET,'leadtime');
		$length = filter_input(INPUT_GET,'length');
		$width = filter_input(INPUT_GET,'width');
		$height = filter_input(INPUT_GET,'height');
		$isstock = filter_input(INPUT_GET,'isstock');
		$isfohulbom = filter_input(INPUT_GET,'isfohulbom');
		$iscontinue = filter_input(INPUT_GET,'iscontinue');
		$materialtype = filter_input(INPUT_GET,'materialtype');
		$density = filter_input(INPUT_GET,'density');
		$recordstatus = filter_input(INPUT_GET,'recordstatus');
		$sql .= " where coalesce(a.productid,'') like '%".$productid."%' 
			and coalesce(a.productname,'') like '%".$productname."%'
			and coalesce(a.barcode,'') like '%".$barcode."%'
			and coalesce(c.identityname,'') like '%".$productidentity."%'
			and coalesce(d.brandname,'') like '%".$productbrand."%'
			and coalesce(e.collectionname,'') like '%".$productcollection."%'
			and coalesce(f.description,'') like '%".$productseries."%'
			and coalesce(a.leadtime,'') like '%".$leadtime."%'
			and coalesce(a.panjang,'') like '%".$length."%'
			and coalesce(a.lebar,'') like '%".$width."%'
			and coalesce(a.tinggi,'') like '%".$height."%'
			and coalesce(a.isstock,'') like '%".$isstock."%'
			and coalesce(a.isfohulbom,'') like '%".$isfohulbom."%'
			and coalesce(a.iscontinue,'') like '%".$iscontinue."%'
			and coalesce(b.description,'') like '%".$materialtype."%'
			and coalesce(a.density,'') like '%".$density."%'
			and coalesce(a.recordstatus,'') like '%".$recordstatus."%'
			";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " and a.productid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		
		foreach($dataReader as $row1)
		{
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['productid'])
				->setCellValueByColumnAndRow(1,$i,$row1['productname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['productpic'])
				->setCellValueByColumnAndRow(3,$i,$row1['isstock'])
				->setCellValueByColumnAndRow(4,$i,$row1['barcode'])
				->setCellValueByColumnAndRow(5,$i,$row1['materialtypecode'])
				->setCellValueByColumnAndRow(6,$i,$row1['identityname'])
				->setCellValueByColumnAndRow(7,$i,$row1['brandname'])
				->setCellValueByColumnAndRow(8,$i,$row1['collectionname'])
				->setCellValueByColumnAndRow(9,$i,$row1['productseries'])
				->setCellValueByColumnAndRow(10,$i,$row1['leadtime'])
				->setCellValueByColumnAndRow(11,$i,$row1['panjang'])
				->setCellValueByColumnAndRow(12,$i,$row1['lebar'])
				->setCellValueByColumnAndRow(13,$i,$row1['tinggi'])
				->setCellValueByColumnAndRow(14,$i,$row1['recordstatus'])
				->setCellValueByColumnAndRow(15,$i,$row1['k3lnumber'])
				->setCellValueByColumnAndRow(16,$i,$row1['density'])
				->setCellValueByColumnAndRow(17,$i,$row1['isfohulbom'])
				->setCellValueByColumnAndRow(18,$i,$row1['iscontinue']);
			$i++;
		}
		
		
		$this->getFooterXLS($this->phpExcel);
	}
}