<?php
class ProductsalesController extends Controller {
	public $menuname = 'productsales';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function actiongetprice() {
		$product = null;
		$cmd='';
		if(isset($_POST['productid']) && isset($_POST['addressbookid']) && isset($_POST['unitofmeasureid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.pricecategoryid')
				->from('addressbook t')
				->where('t.addressbookid = '.$_POST['addressbookid'])
				->limit(1)
				->queryRow();
			$cmd = Yii::app()->db->createCommand()
				->select('t.currencyvalue,t.currencyid,a.currencyname')
				->from('productsales t')
				->join('currency a','a.currencyid = t.currencyid')
				->where('productid = '.$_POST['productid'].' and pricecategoryid = '.$cmd['pricecategoryid'])
				->limit(1)
				->queryRow();
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'currencyvalue'=> Yii::app()->format->formatNumber($cmd['currencyvalue']),
				'currencyid'=> $cmd['currencyid'],
				'currencyname'=>$cmd['currencyname'],
				'currencyrate'=>1
				));
			Yii::app()->end();
		}
	}
	public function search() {
		header("Content-Type: application/json");
		$productsalesid = isset ($_POST['productsalesid']) ? $_POST['productsalesid'] : '';
		$product = isset ($_POST['product']) ? $_POST['product'] : '';
		$currencyname = isset ($_POST['currencyname']) ? $_POST['currencyname'] : '';
		$currencyvalue = isset ($_POST['currencyvalue']) ? $_POST['currencyvalue'] : '';
		$pricecategory = isset ($_POST['pricecategory']) ? $_POST['pricecategory'] : '';
		$productsalesid = isset ($_GET['q']) ? $_GET['q'] : $productsalesid;
		$product = isset ($_GET['q']) ? $_GET['q'] : $product;
		$currencyname = isset ($_GET['q']) ? $_GET['q'] : $currencyname;
		$currencyvalue = isset ($_GET['q']) ? $_GET['q'] : $currencyvalue;
		$pricecategory = isset ($_GET['q']) ? $_GET['q'] : $pricecategory;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'productsalesid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		$result = array();
		$row = array();
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('productsales t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('currency q','q.currencyid=t.currencyid')
			->leftjoin('pricecategory r','r.pricecategoryid=t.pricecategoryid')
			->leftjoin('unitofmeasure s','s.unitofmeasureid=t.uomid')
			->where('(p.productname like :product) and 
				(q.currencyname like :currencyname) and 
				(r.categoryname like :pricecategory)',
					array(':product'=>'%'.$product.'%',
							':currencyname'=>'%'.$currencyname.'%',
							':pricecategory'=>'%'.$pricecategory.'%'
							))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select()	
			->from('productsales t')
			->leftjoin('product p','p.productid=t.productid')
			->leftjoin('currency q','q.currencyid=t.currencyid')
			->leftjoin('pricecategory r','r.pricecategoryid=t.pricecategoryid')
			->leftjoin('unitofmeasure s','s.unitofmeasureid=t.uomid')
			->where('(p.productname like :product) and 
				(q.currencyname like :currencyname) and 
				(r.categoryname like :pricecategory)',
					array(':product'=>'%'.$product.'%',
							':currencyname'=>'%'.$currencyname.'%',
							':pricecategory'=>'%'.$pricecategory.'%'))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		foreach($cmd as $data) {	
			$row[] = array(
				'productsalesid'=>$data['productsalesid'],
				'productid'=>$data['productid'],
				'productname'=>$data['productname'],
				'currencyid'=>$data['currencyid'],
				'currencyname'=>$data['currencyname'],
				'currencyvalue'=>Yii::app()->format->formatNumber($data['currencyvalue']),
				'pricecategoryid'=>$data['pricecategoryid'],
				'categoryname'=>$data['categoryname'],
				'uomid'=>$data['uomid'],
				'uomcode'=>$data['uomcode'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionGenerateData() {
		$cmd = 0;
		if(isset($_POST['productid']) && isset($_POST['customerid'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.currencyid,t.currencyvalue')	
				->from('productsales t')
				->join('addressbook a','a.pricecategoryid = t.pricecategoryid')
				->where('productid = :productid and a.addressbookid = :addressbookid',
					array(':productid'=>$_POST['productid'],':addressbookid'=>$_POST['customerid']))
				->queryRow();			
		}	else 
		if(isset($_POST['productid']) && isset($_POST['package'])) {
			$cmd = Yii::app()->db->createCommand()
				->select('t.currencyid,t.currencyvalue')	
				->from('productsales t')
				->join('addressbook a','a.pricecategoryid = t.pricecategoryid')
				->where('productid = :productid and a.pricecategoryid = 1',
					array(':productid'=>$_POST['productid']))
				->queryRow();			
		}
		if (Yii::app()->request->isAjaxRequest) {
			echo CJSON::encode(array(
				'status'=>'success',
				'price'=> Yii::app()->format->formatNumber($cmd['currencyvalue']),
				'currencyid'=>$cmd['currencyid'],
				'currencyrate'=>1
				));
			Yii::app()->end();
		}
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertproductsales(:vproductid,:vcurrencyid,:vcurrencyvalue,:vpricecategoryid,:vuomid,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateproductsales(:vid,:vproductid,:vcurrencyid,:vcurrencyvalue,:vpricecategoryid,:vuomid,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vproductid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vcurrencyvalue',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vpricecategoryid',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vuomid',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('_FILES_').'/uploads/' . basename($_FILES["file-productsales"]["name"]);
		if (move_uploaded_file($_FILES["file-productsales"]["tmp_name"], $target_file)) {
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($target_file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $objWorksheet->getHighestRow(); 
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				for ($row = 3; $row <= $highestRow; ++$row) {
					$id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
					$productname = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$productid = Yii::app()->db->createCommand("select productid from product where productname = '".$productname."'")->queryScalar();
					$currencyname = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '".$currencyname."'")->queryScalar();
					$currencyvalue = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$pricecategoryname = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$pricecategoryid = Yii::app()->db->createCommand("select pricecategoryid from pricecategory where categoryname = '".$pricecategoryname."'")->queryScalar();
					$uomcode = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '".$uomcode."'")->queryScalar();
					$this->ModifyData($connection,array($id,$productid,$currencyid,$currencyvalue,$pricecategoryid,$uomid));
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
			$this->ModifyData($connection,array((isset($_POST['productsalesid'])?$_POST['productsalesid']:''),$_POST['productid'],$_POST['currencyid'],$_POST['currencyvalue'],
				$_POST['pricecategoryid'],$_POST['uomid']));
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
				$sql = 'call Purgeproductsales(:vid,:vcreatedby)';
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
	  $sql = "select a.productsalesid,b.productname,c.currencyname,a.currencyvalue,d.categoryname,e.uomcode
						from productsales a
						left join product b on b.productid = a.productid
						left join currency c on c.currencyid = a.currencyid
						left join pricecategory d on d.pricecategoryid = a.pricecategoryid
						left join unitofmeasure e on e.unitofmeasureid = a.uomid ";
		$productsalesid = filter_input(INPUT_GET,'productsalesid');
		$productname = filter_input(INPUT_GET,'product');
		$currencyname = filter_input(INPUT_GET,'currencyname');
		$pricecategory = filter_input(INPUT_GET,'pricecategory');
		$sql .= " where coalesce(a.productsalesid,'') like '%".$productsalesid."%' 
			and coalesce(b.productname,'') like '%".$productname."%'
			and coalesce(c.currencyname,'') like '%".$currencyname."%'
			and coalesce(d.categoryname,'') like '%".$pricecategory."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " where a.productsalesid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('productsales');
		$this->pdf->AddPage('P',array(400,300));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('productsalesid'),
																	GetCatalog('productname'),
																	GetCatalog('currencyname'),
																	GetCatalog('currencyvalue'),
																	GetCatalog('categoryname'),
																	GetCatalog('uomcode'));
		$this->pdf->setwidths(array(15,230,25,45,35,30));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L');
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['productsalesid'],$row1['productname'],$row1['currencyname'],
				Yii::app()->format->formatCurrency($row1['currencyvalue']),$row1['categoryname'],$row1['uomcode']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='productsales';
		parent::actionDownxls();
		$sql = "select a.productsalesid,b.productname,c.currencyname,a.currencyvalue,d.categoryname,e.uomcode
						from productsales a
						left join product b on b.productid = a.productid
						left join currency c on c.currencyid = a.currencyid
						left join pricecategory d on d.pricecategoryid = a.pricecategoryid
						left join unitofmeasure e on e.unitofmeasureid = a.uomid ";
		$productsalesid = filter_input(INPUT_GET,'productsalesid');
		$productname = filter_input(INPUT_GET,'product');
		$currencyname = filter_input(INPUT_GET,'currencyname');
		$pricecategory = filter_input(INPUT_GET,'pricecategory');
		$sql .= " where coalesce(a.productsalesid,'') like '%".$productsalesid."%' 
			and coalesce(b.productname,'') like '%".$productname."%'
			and coalesce(c.currencyname,'') like '%".$currencyname."%'
			and coalesce(d.categoryname,'') like '%".$pricecategory."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.productsalesid in (".$_GET['id'].")";
		}
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['productsalesid'])
				->setCellValueByColumnAndRow(1,$i,$row1['productname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['currencyname'])
				->setCellValueByColumnAndRow(3,$i,$row1['currencyvalue'])
				->setCellValueByColumnAndRow(4,$i,$row1['categoryname'])
				->setCellValueByColumnAndRow(5,$i,$row1['uomcode']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}