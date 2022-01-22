<?php
class ForecastController extends Controller
{
  public $menuname = 'forecast';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function RecursiveFG($companyid, $bulan, $tahun, $forecastid, $forecastdetailid, $productid, $uomid, $qty)
  {
    $dependency = new CDbCacheDependency('select max(bomid) from billofmaterial');
    $sql        = "select ifnull(count(1),0)
			from billofmaterial a 
			join productplant b on b.productid = a.productid 
			join product c on c.productid = a.productid 
			join sloc d on d.slocid = b.slocid 
			join plant e on e.plantid = d.plantid
			join company f on f.companyid = e.companyid 
			where a.productid = " . $productid . " and b.issource = 1 and a.recordstatus = 1 and f.companyid = " . $companyid . " limit 1";
    $k          = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
    if ($k > 0) {
      $sql = "select a.bomid 
				from billofmaterial a 
				join productplant b on b.productid = a.productid and b.issource = 1
				join product c on c.productid = a.productid 
				join sloc d on d.slocid = b.slocid 
				join plant e on e.plantid = d.plantid
				join company f on f.companyid = e.companyid 
				where a.productid = " . $productid . "  and a.recordstatus = 1 and f.companyid = " . $companyid . " limit 1";
      $k   = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
      $sql = "insert into forecastdetail (companyid,forecastid,bulan,tahun,productid,qty,uomid,slocid,iscreate,bomid,turunanid)
					select " . $companyid . "," . $forecastid . "," . $bulan . "," . $tahun . ",d.productid,d.qty * " . $qty . ",d.uomid,(
select zb.slocid
from productplant zb 
join sloc zc on zc.slocid = zb.slocid 
join plant zd on zd.plantid = zc.plantid
join company ze on ze.companyid = zd.companyid
where zb.productid = d.productid and zb.issource = 1 and zd.companyid = " . $companyid . " and zb.unitofissue = d.uomid
limit 1
),
						case when d.productbomid is null then 0 else 1 end,d.productbomid," . $forecastdetailid . "
from billofmaterial a 				
join bomdetail d on d.bomid = a.bomid and d.productbomid is not null  
join product e on e.productid = d.productid 
where a.bomid = " . $k . "
and a.productid = " . $productid . " 
and e.isstock = 1
order by d.bomdetailid";
      Yii::app()->db->createCommand($sql)->execute();
      $sql = "insert into forecastbb (companyid,forecastid,bulan,tahun,productid,qty,uomid,slocid,iscreate,bomid,turunanid)
					select " . $companyid . "," . $forecastid . "," . $bulan . "," . $tahun . ",d.productid,d.qty * " . $qty . ",d.uomid,(
select zb.slocid
from productplant zb 
join sloc zc on zc.slocid = zb.slocid 
join plant zd on zd.plantid = zc.plantid
join company ze on ze.companyid = zd.companyid
where zb.productid = d.productid and zb.issource = 1 and zd.companyid = " . $companyid . " and zb.unitofissue = d.uomid
and e.isstock = 1
limit 1
),
						case when d.productbomid is null then 0 else 1 end,d.productbomid," . $forecastdetailid . "
from billofmaterial a 				
join bomdetail d on d.bomid = a.bomid and d.productbomid is null  
join product e on e.productid = d.productid 
where a.bomid = " . $k . "
and a.productid = " . $productid . " 
and e.isstock = 1
order by d.bomdetailid";
      Yii::app()->db->createCommand($sql)->execute();
    }
    $sql        = "select f.companyid,a.forecastid, a.forecastdetailid,b.productid,c.unitofmeasureid,a.qty,a.slocid,a.turunanid
				from forecastdetail a
				join product b on b.productid = a.productid
				join unitofmeasure c on c.unitofmeasureid = a.uomid
				join sloc d on d.slocid = a.slocid 
				join plant e on e.plantid = d.plantid
				join company f on f.companyid = e.companyid
				where f.companyid = " . $_REQUEST['companyid'] . "
					and a.bulan = " . $_REQUEST['bulan'] . " 
					and b.isstock = 1
					and a.tahun = " . $_REQUEST['tahun'] . " and a.iscreate = 1 
					and a.turunanid = " . $forecastdetailid;
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    foreach ($dataReader as $data) {
      $sql = "update forecastdetail
				set iscreate = 0
				where forecastdetailid = " . $data['forecastdetailid'];
      Yii::app()->db->createCommand($sql)->execute();
      $this->RecursiveFG($data['companyid'], $_REQUEST['bulan'], $_REQUEST['tahun'], $data['forecastid'], $data['forecastdetailid'], $data['productid'], $data['unitofmeasureid'], $data['qty']);
    }
  }
  public function actionGeneratefg()
  {
    parent::actionCreate();
    //$sql = "select companyid from company where companyname like '%" . $_REQUEST['companyname'] . "%'";
    //$id  = Yii::app()->db->createCommand($sql)->execute();
    $id = $_REQUEST['companyid'];
    $sql = "delete from forecastdetail where companyid = " . $id . " and bulan = " . $_REQUEST['bulan'] . " and tahun = " . $_REQUEST['tahun'];
    Yii::app()->db->createCommand($sql)->execute();
    $sql = "delete from forecastbb where companyid = " . $id . " and bulan = " . $_REQUEST['bulan'] . " and tahun = " . $_REQUEST['tahun'];
    Yii::app()->db->createCommand($sql)->execute();
    $sql = "call generatefcfg(" . $id . "," . $_REQUEST['bulan'] . "," . $_REQUEST['tahun'] . ")";
    Yii::app()->db->createCommand($sql)->execute();
    $sql        = "select distinct f.companyid,a.forecastid, a.forecastdetailid,b.productid,c.unitofmeasureid,a.qty,a.slocid
			from forecastdetail a
			join product b on b.productid = a.productid
			join unitofmeasure c on c.unitofmeasureid = a.uomid
			join sloc d on d.slocid = a.slocid 
			join plant e on e.plantid = d.plantid
			join company f on f.companyid = e.companyid
			where f.companyid = " . $_REQUEST['companyid'] . " and a.bulan = " . $_REQUEST['bulan'] . " 
				and a.tahun = " . $_REQUEST['tahun'] . " and a.iscreate = 1";
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    foreach ($dataReader as $data) {
      $sql = "update forecastdetail
				set iscreate = 0
				where forecastdetailid = " . $data['forecastdetailid'];
      Yii::app()->db->createCommand($sql)->execute();
      $this->RecursiveFG($data['companyid'], $_REQUEST['bulan'], $_REQUEST['tahun'], $data['forecastid'], $data['forecastdetailid'], $data['productid'], $data['unitofmeasureid'], $data['qty']);
    }
    GetMessage('success', 'alreadysaved');
  }
  public function search()
  {
    header("Content-Type: application/json");
    $forecastid      = isset($_POST['forecastid']) ? $_POST['forecastid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $bulan           = isset($_POST['bulan']) ? $_POST['bulan'] : '';
    $tahun           = isset($_POST['tahun']) ? $_POST['tahun'] : '';
    $productid       = isset($_POST['productid']) ? $_POST['productid'] : '';
    $productname     = isset($_POST['productname']) ? $_POST['productname'] : '';
    $qty             = isset($_POST['qty']) ? $_POST['qty'] : '';
    $uomid           = isset($_POST['uomid']) ? $_POST['uomid'] : '';
    $uomcode         = isset($_POST['uomcode']) ? $_POST['uomcode'] : '';
    $slocid          = isset($_POST['slocid']) ? $_POST['slocid'] : '';
    $sloccode        = isset($_POST['sloccode']) ? $_POST['sloccode'] : '';
    $forecastid      = isset($_GET['q']) ? $_GET['q'] : $forecastid;
    $companyid       = isset($_GET['q']) ? $_GET['q'] : $companyid;
    $bulan           = isset($_GET['q']) ? $_GET['q'] : $bulan;
    $tahun           = isset($_GET['q']) ? $_GET['q'] : $tahun;
    $productid       = isset($_GET['q']) ? $_GET['q'] : $productid;
    $productname     = isset($_GET['q']) ? $_GET['q'] : $productname;
    $qty             = isset($_GET['q']) ? $_GET['q'] : $qty;
    $uomid           = isset($_GET['q']) ? $_GET['q'] : $uomid;
    $uomcode         = isset($_GET['q']) ? $_GET['q'] : $uomcode;
    $slocid          = isset($_GET['q']) ? $_GET['q'] : $slocid;
    $sloccode        = isset($_GET['q']) ? $_GET['q'] : $sloccode;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'forecastid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 'a.') > 0) ? $sort : 'a.' . $sort;
    $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    
    if(isset($_GET['list']))
    {   
      $cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('forecast a')
				->join('product b', 'b.productid=a.productid')
				->join('unitofmeasure c', 'c.unitofmeasureid = a.uomid')
				->join('sloc d', 'd.slocid = a.slocid')
				->join('plant e', 'e.plantid = d.plantid')
				->join('company f', 'f.companyid = e.companyid')
				->join('billofmaterial g', 'g.bomid = a.bomid')
				->where('(a.productid like :productid) or (a.uomid like :uomid) or (a.slocid    like :slocid) or (f.companyid like :companyid) or (a.bulan like :bulan) or (a.tahun like :tahun)', array(
              ':productid' => '%' . $productid . '%',
              ':uomid' => '%' . $uomid . '%',
              ':slocid' => '%' . $slocid . '%',
              ':companyid' => '%' . $companyid . '%',
              ':bulan' => '%' . $bulan . '%',
              ':tahun' => '%' . $tahun . '%'
				))->queryScalar();    
    }
    else
    {
			$sql = "select count(1)
							from forecast a
							join product b on b.productid = a.productid
							join unitofmeasure c on c.unitofmeasureid = a.uomid
							join sloc d on d.slocid = a.slocid
							join plant e on e.plantid = d.plantid
							join company f on f.companyid = e.companyid
							join billofmaterial g on g.bomid = a.bomid ";
			$where = "where (coalesce(a.productid,'') like '%{$productid}%') and (coalesce(a.uomid,'') like '%{$uomid}%') and (coalesce(a.slocid,'') like '%{$slocid}%') and (coalesce(a.tahun,'') like '%{$tahun}%') and d.slocid in (".getUserObjectValues('sloc').")";
		if(isset($companyid) && $companyid!='')
		{
				$where .= ' and f.companyid = '.$companyid;
		}
		if(isset($bulan) && $bulan!='')
		{
				$where .= ' and a.bulan = '.$bulan;
		}
		$cmd = Yii::app()->db->createCommand($sql.$where)->queryScalar();
		/*
		$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')
				->from('forecast a')
				->join('product b', 'b.productid=a.productid')
				->join('unitofmeasure c', 'c.unitofmeasureid = a.uomid')
				->join('sloc d', 'd.slocid = a.slocid')
				->join('plant e', 'e.plantid = d.plantid')
				->join('company f', 'f.companyid = e.companyid')
				->join('billofmaterial g', 'g.bomid = a.bomid')
				->where("(coalesce(a.productid,'') like :productid) and (coalesce(a.uomid,'') like :uomid) and (coalesce(a.slocid,'') like :slocid) and (coalesce(f.companyid,'') like :companyid) and (coalesce(a.bulan,'') like :bulan) and (coalesce(a.tahun,'') like :tahun)", array(
					':productid' => '%' . $productid . '%',
					':uomid' => '%' . $uomid . '%',
					':slocid' => '%' . $slocid . '%',
					':companyid' => '%' . $companyid . '%',
					':bulan' => '%' . $bulan . '%',
					':tahun' => '%' . $tahun . '%'
		))->queryScalar();
		*/
    }
    $result['total'] = $cmd;
    if(isset($_GET['list']))
    {
			$cmd = Yii::app()->db->createCommand()
				->select('a.*,b.productname,getstock(a.productid, a.uomid, a.slocid) as            qtystock,g.bomversion,
								(select ifnull(sum(zz.qty),0) from productoutputdetail zz
								where zz.productid = a.productid 
								and zz.uomid = a.uomid) as qtyoutput, c.description as uomcode, d.sloccode')
				->from('forecast a')
				->join('product b', 'b.productid=a.productid')
				->join('unitofmeasure c', 'c.unitofmeasureid = a.uomid')
				->join('sloc d', 'd.slocid = a.slocid')
				->join('plant e', 'e.plantid = d.plantid')
				->join('company f', 'f.companyid = e.companyid')
				->join('billofmaterial g', 'g.bomid = a.bomid')
				->where('(a.productid like :productid) or (a.uomid like :uomid) or (a.slocid       like :slocid) or (f.companyid like :companyid) or (a.bulan like :bulan) or (a.tahun like :tahun)', array(
					':productid' => '%' . $productid . '%',
					':uomid' => '%' . $uomid . '%',
					':slocid' => '%' . $slocid . '%',
					':companyid' => '%' . $companyid . '%',
					':bulan' => '%' . $bulan . '%',
					':tahun' => '%' . $tahun . '%'
				))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    }
    else
    {
			$sql = "select a.*,b.productname,getstock(a.productid, a.uomid, a.slocid) as            qtystock,g.bomversion,
							(select ifnull(sum(zz.qty),0) from productoutputdetail zz
							where zz.productid = a.productid 
								and zz.uomid = a.uomid) as qtyoutput, c.description as uomcode, d.sloccode
                from forecast a
                join product b on b.productid = a.productid
                join unitofmeasure c on c.unitofmeasureid = a.uomid
                join sloc d on d.slocid = a.slocid
                join plant e on e.plantid = d.plantid
                join company f on f.companyid = e.companyid
                join billofmaterial g on g.bomid = a.bomid ";
			$where = "where (coalesce(a.productid,'') like '%{$productid}%') and (coalesce(a.uomid,'') like '%{$uomid}%') and (coalesce(a.slocid,'') like '%{$slocid}%') and (coalesce(a.tahun,'') like '%{$tahun}%') and d.slocid in (".getUserObjectValues('sloc').")";
		if(isset($companyid) && $companyid!='')
		{
			$where .= ' and f.companyid = '.$companyid;
		}
		if(isset($bulan) && $bulan!='')
		{
				$where .= ' and a.bulan = '.$bulan;
		}
		$sql = $sql . $where . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows;
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		/*
		$cmd = Yii::app()->db->createCommand()
			->select('a.*,b.productname,getstock(a.productid, a.uomid, a.slocid) as            qtystock,g.bomversion,
							(select ifnull(sum(zz.qty),0) from productoutputdetail zz
							where zz.productid = a.productid 
							and zz.uomid = a.uomid) as qtyoutput, c.description as uomcode, d.sloccode')
			->from('forecast a')
			->join('product b', 'b.productid=a.productid')
			->join('unitofmeasure c', 'c.unitofmeasureid = a.uomid')
			->join('sloc d', 'd.slocid = a.slocid')
			->join('plant e', 'e.plantid = d.plantid')
			->join('company f', 'f.companyid = e.companyid')
			->join('billofmaterial g', 'g.bomid = a.bomid')
			->where("(coalesce(a.productid,'') like :productid) and (coalesce(a.uomid,'') like :uomid) and (coalesce(a.slocid,'') like :slocid) and (coalesce(f.companyid,'') like :companyid) and (coalesce(a.bulan,'') like :bulan) and (coalesce(a.tahun,'') like :tahun)", array(
				':productid' => '%' . $productid . '%',
				':uomid' => '%' . $uomid . '%',
				':slocid' => '%' . $slocid . '%',
				':companyid' => '%' . $companyid . '%',
				':bulan' => '%' . $bulan . '%',
				':tahun' => '%' . $tahun . '%'
			))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		*/
    }
   
    foreach ($cmd as $data) {
      $row[] = array(
        'forecastid' => $data['forecastid'],
        'bulan' => $data['bulan'],
        'tahun' => $data['tahun'],
        'productid' => $data['productid'],
        'productname' => $data['productname'],
        'qty' => Yii::app()->format->formatNumber($data['qty']),
        'qtystock' => Yii::app()->format->formatNumber($data['qtystock']),
        'qtyoutput' => Yii::app()->format->formatNumber($data['qtyoutput']),
        'uomid' => $data['uomid'],
        'uomcode' => $data['uomcode'],
        'slocid' => $data['slocid'],
        'sloccode' => $data['sloccode'],
        'bomid' => $data['bomid'],
        'bomversion' => $data['bomversion']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSave()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord'])) {
        $sql     = 'call InsertForecast(:vbulan,:vtahun,:vproductid,:vqty,:vuomid,:vslocid,:vbomid,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateForecast(:vid,:vbulan,:vtahun,:vproductid,:vqty,:vuomid,:vslocid,:vbomid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['forecastid'], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $_POST['forecastid']);
      }
      $command->bindvalue(':vbulan', $_POST['bulan'], PDO::PARAM_STR);
      $command->bindvalue(':vtahun', $_POST['tahun'], PDO::PARAM_STR);
      $command->bindvalue(':vproductid', $_POST['productid'], PDO::PARAM_STR);
      $command->bindvalue(':vqty', $_POST['qty'], PDO::PARAM_STR);
      $command->bindvalue(':vuomid', $_POST['uomid'], PDO::PARAM_STR);
      $command->bindvalue(':vslocid', $_POST['slocid'], PDO::PARAM_STR);
      $command->bindvalue(':vbomid', $_POST['bomid'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call PurgeForecast(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
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
  public function actionDownPDF()
  {
      parent::actionDownload();
      $sql = "select a.forecastid,a.bulan,a.tahun,e.productname,a.qty,f.uomcode,b.sloccode,
            (
            select sum(za.qty)
            from productstock za
            where za.productid = a.productid and za.unitofmeasureid = a.uomid and za.slocid = b.slocid) as stockqty
        from forecast a 
        join sloc b on b.slocid = a.slocid 
        join plant c on c.plantid = b.plantid 
        join company d on d.companyid = c.companyid 
        join product e on e.productid = a.productid 
        join unitofmeasure f on f.unitofmeasureid = a.uomid ";
        
        $where = " where bulan = ".$_GET['bulan']." and tahun =".$_GET['tahun'];
        if(isset($_GET['companyid']) && $_GET['companyid']!='')
        {
            $where .= ' and d.companyid = '.$_GET['companyid'];
        }
        $order = " order by d.companyid,a.bulan,a.tahun,b.slocid,e.productid ";
		$dataReader = Yii::app()->db->createCommand($sql.$where.$order)->queryAll();
		//masukkan judul
		$this->pdf->title=('forecast');
		$this->pdf->AddPage('P');
		$this->pdf->setFontSize(8);
		$this->pdf->text(10,$this->pdf->gety()+0,'Daftar FG');
		$this->pdf->sety($this->pdf->gety()+5);
		$this->pdf->colalign = array('C','C','C','C','C','C','C','C');
		$this->pdf->colheader = array(getCatalog('bulan'),getCatalog('tahun'),getCatalog('product'),getCatalog('qty'),getCatalog('stock'),getCatalog('uom'),getCatalog('sloc'));
		$this->pdf->setwidths(array(15,15,80,20,20,20,20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('C','C','L','R','R','C','L');
		$total = 0;
		foreach($dataReader as $row1)
		{
			$this->pdf->setFontSize(8);
			//masukkan baris untuk cetak
            $this->pdf->row(array($row1['bulan'],$row1['tahun'],$row1['productname'],
			Yii::app()->format->formatNumber($row1['qty']),Yii::app()->format->formatNumber($row1['stockqty']),
			$row1['uomcode'],$row1['sloccode']));
			$total += $row1['qty'];
		}
		$this->pdf->row(array('','','Total',
			Yii::app()->format->formatNumber($total)));
		$this->pdf->sety($this->pdf->gety()+7);	
		
		$this->pdf->text(10,$this->pdf->gety()+0,'Product WIP (Work In Process)');
		$sql = "select e.productname,sum(a.qty) as qty,(
            select sum(za.qty)
            from productstock za
            where za.productid = a.productid and za.unitofmeasureid = a.uomid and za.slocid = b.slocid

            ) as stockqty,f.uomcode,b.sloccode,g.bomversion
			from forecastdetail a 
			join sloc b on b.slocid = a.slocid 
			join plant c on c.plantid = b.plantid 
			join company d on d.companyid = c.companyid 
			join product e on e.productid = a.productid 
			join unitofmeasure f on f.unitofmeasureid = a.uomid 
			left join billofmaterial g on g.bomid = a.bomid ";
        $where = " where bulan = ".$_GET['bulan']." and tahun =".$_GET['tahun'];
        if(isset($_GET['companyid']) && $_GET['companyid']!='')
        {
            $where .= ' and d.companyid = '.$_GET['companyid'];
        }

        $group = " group by e.productname,f.uomcode,b.sloccode";
		$this->pdf->sety($this->pdf->gety()+5);
		$dataReader = Yii::app()->db->createCommand($sql.$where.$group)->queryAll();
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->colheader = array(getCatalog('product'),getCatalog('qty'),getCatalog('stock'),getCatalog('uom'),
        getCatalog('sloc'),getCatalog('bomversion'));
		$this->pdf->setwidths(array(70,25,20,20,30,30));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','R','R','C','L','L');
		$total = 0;
		foreach($dataReader as $row1)
		{
			$this->pdf->setFontSize(8);
			//masukkan baris untuk cetak
            $this->pdf->row(array($row1['productname'],
			Yii::app()->format->formatNumber($row1['qty']),Yii::app()->format->formatNumber($row1['stockqty']),$row1['uomcode'],$row1['sloccode'],$row1['bomversion']));
			$total += $row1['qty'];
		}
		$this->pdf->row(array('Total',
			Yii::app()->format->formatNumber($total)));
			
		$this->pdf->sety($this->pdf->gety()+7);
		$this->pdf->text(10,$this->pdf->gety()+0,'Kebutuhan Bahan Baku');
		$sql = "select e.productname,sum(a.qty) as qty,(
            select sum(za.qty)
            from productstock za
            where za.productid = a.productid and za.unitofmeasureid = a.uomid and za.slocid = b.slocid ) as stockqty,f.uomcode,b.sloccode,g.bomversion
			from forecastbb a 
			join sloc b on b.slocid = a.slocid 
			join plant c on c.plantid = b.plantid 
			join company d on d.companyid = c.companyid 
			join product e on e.productid = a.productid 
			join unitofmeasure f on f.unitofmeasureid = a.uomid 
			left join billofmaterial g on g.bomid = a.bomid ";
        $where = " where bulan = ".$_GET['bulan']." and tahun =".$_GET['tahun'];
        if(isset($_GET['companyid']) && $_GET['companyid']!='')
        {
            $where .= ' and d.companyid = '.$_GET['companyid'];
        }
    	$group = " group by e.productname,f.uomcode,b.sloccode";
		$this->pdf->sety($this->pdf->gety()+5);
		$dataReader = Yii::app()->db->createCommand($sql.$where.$group)->queryAll();
		$this->pdf->colalign = array('C','C','C','C','C','C','C');
		$this->pdf->colheader = array(getCatalog('product'),getCatalog('qty'),getCatalog('stock'),getCatalog('uom'),
        getCatalog('sloc'),getCatalog('bomversion'));
		$this->pdf->setwidths(array(70,25,20,20,30,30));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','R','R','C','L','L');
		$total = 0;
		foreach($dataReader as $row1)
		{
			$this->pdf->setFontSize(8);
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['productname'],
			Yii::app()->format->formatNumber($row1['qty']),Yii::app()->format->formatNumber($row1['stockqty']),$row1['uomcode'],$row1['sloccode'],$row1['bomversion']));
			$total += $row1['qty'];
		}
		$this->pdf->row(array('Total',
        Yii::app()->format->formatNumber($total)));
		// me-render ke browser
		$this->pdf->Output();
  }
    public function actionDownxls()
    {
        $this->menuname = 'forecast';
        parent::actionDownXLS();
        $sql = "select a.forecastid,a.bulan,a.tahun,e.productname,a.qty,f.uomcode,b.sloccode,
            (
            select sum(za.qty)
            from productstock za
            where za.productid = a.productid and za.unitofmeasureid = a.uomid and za.slocid = b.slocid) as stockqty
        from forecast a 
        join sloc b on b.slocid = a.slocid 
        join plant c on c.plantid = b.plantid 
        join company d on d.companyid = c.companyid 
        join product e on e.productid = a.productid 
        join unitofmeasure f on f.unitofmeasureid = a.uomid ";
        
        $where = " where bulan = ".$_GET['bulan']." and tahun =".$_GET['tahun'];
        if(isset($_GET['companyid']) && $_GET['companyid']!='')
        {
            $where .= ' and d.companyid = '.$_GET['companyid'];
        }
        $order = " order by d.companyid,a.bulan,a.tahun,b.slocid,e.productid ";
		$dataReader = Yii::app()->db->createCommand($sql.$where.$order)->queryAll();

        $line = 2;
        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,'Daftar FG');
        $line++;
        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,'Bulan')
                ->setCellValueByColumnAndRow(1,$line,'Tahun')
                ->setCellValueByColumnAndRow(2,$line,'Material / Service')
                ->setCellValueByColumnAndRow(3,$line,'Qty')
                ->setCellValueByColumnAndRow(4,$line,'Stock')
                ->setCellValueByColumnAndRow(5,$line,'Satuan')
                ->setCellValueByColumnAndRow(6,$line,'Gudang');
        $line++;

        $total = 0;
        foreach($dataReader as $row1)
        {
            //masukkan baris untuk cetak
            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,$row1['bulan'])
                ->setCellValueByColumnAndRow(1,$line,$row1['tahun'])
                ->setCellValueByColumnAndRow(2,$line,$row1['productname'])
                ->setCellValueByColumnAndRow(3,$line,$row1['qty'])
                ->setCellValueByColumnAndRow(4,$line,$row1['stockqty'])
                ->setCellValueByColumnAndRow(5,$line,$row1['uomcode'])
                ->setCellValueByColumnAndRow(6,$line,$row1['sloccode']);
            $line++;
            $total += $row1['qty'];
        }
        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(2,$line,'Total')
                ->setCellValueByColumnAndRow(3,$line,$total);
        $line++;
        $total=0;
        $sql = "select e.productname,sum(a.qty) as qty,(
            select sum(za.qty)
            from productstock za
            where za.productid = a.productid and za.unitofmeasureid = a.uomid and za.slocid = b.slocid

            ) as stockqty,f.uomcode,b.sloccode,g.bomversion
			from forecastdetail a 
			join sloc b on b.slocid = a.slocid 
			join plant c on c.plantid = b.plantid 
			join company d on d.companyid = c.companyid 
			join product e on e.productid = a.productid 
			join unitofmeasure f on f.unitofmeasureid = a.uomid 
			left join billofmaterial g on g.bomid = a.bomid ";
        $where = " where bulan = ".$_GET['bulan']." and tahun =".$_GET['tahun'];
        if(isset($_GET['companyid']) && $_GET['companyid']!='')
        {
            $where .= ' and d.companyid = '.$_GET['companyid'];
        }

        $group = " group by e.productname,f.uomcode,b.sloccode";
		$dataReader = Yii::app()->db->createCommand($sql.$where.$group)->queryAll();
        $line++;

        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,'Product WIP (Work In Progress)');
        $line++;
        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(2,$line,'Material / Service')
                ->setCellValueByColumnAndRow(3,$line,'Qty')
                ->setCellValueByColumnAndRow(4,$line,'Stock')
                ->setCellValueByColumnAndRow(5,$line,'Satuan')
                ->setCellValueByColumnAndRow(6,$line,'Gudang')
                ->setCellValueByColumnAndRow(7,$line,'Versi BOM');
        $line++;

        foreach($dataReader as $row1)
        {
            //masukkan baris untuk cetak
           $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(2,$line,$row1['productname'])
                ->setCellValueByColumnAndRow(3,$line,$row1['qty'])
                ->setCellValueByColumnAndRow(4,$line,$row1['stockqty'])
                ->setCellValueByColumnAndRow(5,$line,$row1['uomcode'])
                ->setCellValueByColumnAndRow(6,$line,$row1['sloccode'])
                ->setCellValueByColumnAndRow(7,$line,$row1['bomversion']);
            $line++;
            $total += $row1['qty'];
        }
        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(2,$line,'Total')
                ->setCellValueByColumnAndRow(3,$line,$total);
        $line++;

        $total=0;
        $sql = "select e.productname,sum(a.qty) as qty,(
            select sum(za.qty)
            from productstock za
            where za.productid = a.productid and za.unitofmeasureid = a.uomid and za.slocid = b.slocid ) as stockqty,f.uomcode,b.sloccode,g.bomversion
			from forecastbb a 
			join sloc b on b.slocid = a.slocid 
			join plant c on c.plantid = b.plantid 
			join company d on d.companyid = c.companyid 
			join product e on e.productid = a.productid 
			join unitofmeasure f on f.unitofmeasureid = a.uomid 
			left join billofmaterial g on g.bomid = a.bomid ";
        $where = " where bulan = ".$_GET['bulan']." and tahun =".$_GET['tahun'];
        if(isset($_GET['companyid']) && $_GET['companyid']!='')
        {
            $where .= ' and d.companyid = '.$_GET['companyid'];
        }
    	$group = " group by e.productname,f.uomcode,b.sloccode";
		$dataReader = Yii::app()->db->createCommand($sql.$where.$group)->queryAll();
        $line++;

        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0,$line,'Kebutuhan Bahan Baku');
        $line++;
        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(2,$line,'Material / Service')
                ->setCellValueByColumnAndRow(3,$line,'Qty')
                ->setCellValueByColumnAndRow(4,$line,'Stock')
                ->setCellValueByColumnAndRow(5,$line,'Satuan')
                ->setCellValueByColumnAndRow(6,$line,'Gudang')
                ->setCellValueByColumnAndRow(7,$line,'Versi BOM');
        $line++;

        foreach($dataReader as $row1)
        {
            //masukkan baris untuk cetak
           $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(2,$line,$row1['productname'])
                ->setCellValueByColumnAndRow(3,$line,$row1['qty'])
                ->setCellValueByColumnAndRow(4,$line,$row1['stockqty'])
                ->setCellValueByColumnAndRow(5,$line,$row1['uomcode'])
                ->setCellValueByColumnAndRow(6,$line,$row1['sloccode'])
                ->setCellValueByColumnAndRow(7,$line,$row1['bomversion']);
            $line++;
            $total += $row1['qty'];
        }
        $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(2,$line,'Total')
                ->setCellValueByColumnAndRow(3,$line,$total);

        $this->getFooterXLS($this->phpExcel);
    }
}