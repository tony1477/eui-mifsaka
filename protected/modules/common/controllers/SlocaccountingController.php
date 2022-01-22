<?php
class SlocaccountingController extends Controller {
	public $menuname = 'slocaccounting';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$slocaccid = isset ($_POST['slocaccid']) ? $_POST['slocaccid'] : '';
		$sloccode = isset ($_POST['sloccode']) ? $_POST['sloccode'] : '';
		$materialgroupname = isset ($_POST['materialgroupname']) ? $_POST['materialgroupname'] : '';
		$accaktivatetap = isset ($_POST['accaktivatetap']) ? $_POST['accaktivatetap'] : '';
		$accakumat = isset ($_POST['accakumat']) ? $_POST['accakumat'] : '';
		$accbiayaat = isset ($_POST['accbiayaat']) ? $_POST['accbiayaat'] : '';
		$accpersediaan = isset ($_POST['accpersediaan']) ? $_POST['accpersediaan'] : '';
		$accreturpembelian = isset ($_POST['accreturpembelian']) ? $_POST['accreturpembelian'] : '';
		$accdiscpembelian = isset ($_POST['accdiscpembelian']) ? $_POST['accdiscpembelian'] : '';
		$accpenjualan = isset ($_POST['accpenjualan']) ? $_POST['accpenjualan'] : '';
		$accbiaya = isset ($_POST['accbiaya']) ? $_POST['accbiaya'] : '';
		$accreturpenjualan = isset ($_POST['accreturpenjualan']) ? $_POST['accreturpenjualan'] : '';
		$accspsi = isset ($_POST['accspsi']) ? $_POST['accspsi'] : '';
		$accexpedisi = isset ($_POST['accexpedisi']) ? $_POST['accexpedisi'] : '';
		$hpp = isset ($_POST['hpp']) ? $_POST['hpp'] : '';
		$accupahlembur = isset ($_POST['accupahlembur']) ? $_POST['accupahlembur'] : '';
		$foh = isset ($_POST['foh']) ? $_POST['foh'] : '';
		$acckoreksi = isset ($_POST['acckoreksi']) ? $_POST['acckoreksi'] : '';
		$acccadangan = isset ($_POST['acccadangan']) ? $_POST['acccadangan'] : '';
		
		$slocaccid = isset ($_GET['q']) ? $_GET['q'] : $slocaccid;
		$sloccode = isset ($_GET['q']) ? $_GET['q'] : $sloccode;
		$materialgroupname = isset ($_GET['q']) ? $_GET['q'] : $materialgroupname;
		$accaktivatetap = isset ($_GET['q']) ? $_GET['q'] : $accaktivatetap;
		$accakumat = isset ($_GET['q']) ? $_GET['q'] : $accakumat;
		$accbiayaat = isset ($_GET['q']) ? $_GET['q'] : $accbiayaat;
		$accpersediaan = isset ($_GET['q']) ? $_GET['q'] : $accpersediaan;
		$accreturpembelian = isset ($_GET['q']) ? $_GET['q'] : $accreturpembelian;
		$accdiscpembelian = isset ($_GET['q']) ? $_GET['q'] : $accdiscpembelian;
		$accpenjualan = isset ($_GET['q']) ? $_GET['q'] : $accpenjualan;
		$accbiaya = isset ($_GET['q']) ? $_GET['q'] : $accbiaya;
		$accreturpenjualan = isset ($_GET['q']) ? $_GET['q'] : $accreturpenjualan;
		$accspsi = isset ($_GET['q']) ? $_GET['q'] : $accspsi;
		$accexpedisi = isset ($_GET['q']) ? $_GET['q'] : $accexpedisi;
		$hpp = isset ($_GET['q']) ? $_GET['q'] : $hpp;
		$accupahlembur = isset ($_GET['q']) ? $_GET['q'] : $accupahlembur;
		$foh = isset ($_GET['q']) ? $_GET['q'] : $foh;
		$acckoreksi = isset ($_GET['q']) ? $_GET['q'] : $acckoreksi;
		$acccadangan = isset ($_GET['q']) ? $_GET['q'] : $acccadangan;
		
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'slocaccid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.')>0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order ;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		$cmd = Yii::app()->db->createCommand()
			->select('count(1) as total')	
			->from('slocaccounting t')
			->join('sloc p','p.slocid=t.slocid')
			->join('materialgroup f','f.materialgroupid=t.materialgroupid')
			->leftjoin('account q','q.accountid=t.accaktivatetap')
			->leftjoin('account r','r.accountid=t.accakumat')
			->leftjoin('account s','s.accountid=t.accbiayaat')
			->leftjoin('account e','e.accountid=t.accpersediaan')
			->leftjoin('account u','u.accountid=t.accreturpembelian')
			->leftjoin('account v','v.accountid=t.accdiscpembelian')
			->leftjoin('account w','w.accountid=t.accpenjualan')
			->leftjoin('account x','x.accountid=t.accbiaya')
			->leftjoin('account y','y.accountid=t.accreturpenjualan')
			->leftjoin('account z','z.accountid=t.accspsi')
			->leftjoin('account a','a.accountid=t.accexpedisi')
			->leftjoin('account b','b.accountid=t.hpp')
			->leftjoin('account c','c.accountid=t.accupahlembur')
			->leftjoin('account d','d.accountid=t.foh')
			->leftjoin('account h','h.accountid=t.acckoreksi')
			->leftjoin('account g','g.accountid=t.acccadangan')
			->where('(p.sloccode like :sloccode) and
					(f.description like :materialgroupname) ',
							array(':sloccode'=>'%'.$sloccode.'%',
									':materialgroupname'=>'%'.$materialgroupname.'%'
									))
			->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()
			->select('t.*,f.description,a.accountname as accexpedisiname,b.accountname as acchppname,c.accountname as accupahlemburname,
			d.accountname as accfohname,e.accountname as accpersediaanname,p.sloccode as sloccode,q.accountname as accaktivatetapname
			,r.accountname as accakumatname,s.accountname as accbiayaatname,u.accountname as accreturpembelianname,v.accountname as accdiscpembelianname
			,w.accountname as accpenjualanname,x.accountname as accbiayaname,y.accountname as accreturpenjualanname,z.accountname as accspsiname,
            h.accountname as acckoreksiname,g.accountname as acccadanganname')	
			->from('slocaccounting t')
			->join('sloc p','p.slocid=t.slocid')
			->join('materialgroup f','f.materialgroupid=t.materialgroupid')
			->leftjoin('account q','q.accountid=t.accaktivatetap')
			->leftjoin('account r','r.accountid=t.accakumat')
			->leftjoin('account s','s.accountid=t.accbiayaat')
			->leftjoin('account e','e.accountid=t.accpersediaan')
			->leftjoin('account u','u.accountid=t.accreturpembelian')
			->leftjoin('account v','v.accountid=t.accdiscpembelian')
			->leftjoin('account w','w.accountid=t.accpenjualan')
			->leftjoin('account x','x.accountid=t.accbiaya')
			->leftjoin('account y','y.accountid=t.accreturpenjualan')
			->leftjoin('account z','z.accountid=t.accspsi')
			->leftjoin('account a','a.accountid=t.accexpedisi')
			->leftjoin('account b','b.accountid=t.hpp')
			->leftjoin('account c','c.accountid=t.accupahlembur')
			->leftjoin('account d','d.accountid=t.foh')
			->leftjoin('account h','h.accountid=t.acckoreksi')
			->leftjoin('account g','g.accountid=t.acccadangan')
			->where('(p.sloccode like :sloccode) and
					(f.description like :materialgroupname) ',
								array(':sloccode'=>'%'.$sloccode.'%',
										':materialgroupname'=>'%'.$materialgroupname.'%'
										))
			->offset($offset)
			->limit($rows)
			->order($sort.' '.$order)
			->queryAll();
		
		foreach($cmd as $data) {	
			$row[] = array(
				'slocaccid'=>$data['slocaccid'],
				'slocid'=>$data['slocid'],
				'sloccode'=>$data['sloccode'],
				'materialgroupid'=>$data['materialgroupid'],
				'description'=>$data['description'],
				'accaktivatetap'=>$data['accaktivatetap'],
				'accaktivatetapname'=>$data['accaktivatetapname'],
				'accakumat'=>$data['accakumat'],
				'accakumatname'=>$data['accakumatname'],
				'accbiayaat'=>$data['accbiayaat'],
				'accbiayaatname'=>$data['accbiayaatname'],
				'accpersediaan'=>$data['accpersediaan'],
				'accpersediaanname'=>$data['accpersediaanname'],
				'accreturpembelian'=>$data['accreturpembelian'],
				'accreturpembelianname'=>$data['accreturpembelianname'],
				'accdiscpembelian'=>$data['accdiscpembelian'],
				'accdiscpembelianname'=>$data['accdiscpembelianname'],
				'accpenjualan'=>$data['accpenjualan'],
				'accpenjualanname'=>$data['accpenjualanname'],
				'accbiaya'=>$data['accbiaya'],
				'accbiayaname'=>$data['accbiayaname'],
				'accreturpenjualan'=>$data['accreturpenjualan'],
				'accreturpenjualanname'=>$data['accreturpenjualanname'],
				'accspsi'=>$data['accspsi'],
				'accspsiname'=>$data['accspsiname'],
				'accexpedisi'=>$data['accexpedisi'],
				'accexpedisiname'=>$data['accexpedisiname'],
				'hpp'=>$data['hpp'],
				'acchppname'=>$data['acchppname'],
				'accupahlembur'=>$data['accupahlembur'],
				'accupahlemburname'=>$data['accupahlemburname'],
				'foh'=>$data['foh'],
				'accfohname'=>$data['accfohname'],
                'acckoreksi'=>$data['acckoreksi'],
				'acckoreksiname'=>$data['acckoreksiname'],
                'acccadangan'=>$data['acccadangan'],
				'acccadanganname'=>$data['acccadanganname'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	private function ModifyData($connection,$arraydata) {
		$id = (isset($arraydata[0])?$arraydata[0]:'');
		if ($id == '') {
			$sql = 'call Insertslocaccounting(:vslocid,:vmaterialgroupid,:vaccaktivatetap,:vaccakumat,:vaccbiayaat,:vaccpersediaan,:vaccreturpembelian,:vaccdiscpembelian,:vaccpenjualan,:vaccbiaya,:vaccreturpenjualan,:vaccspsi,:vaccexpedisi,:vhpp,:vaccupahlembur,:vfoh,:vacckoreksi,:vacccadangan,:vcreatedby)';
			$command=$connection->createCommand($sql);
		}
		else {
			$sql = 'call Updateslocaccounting(:vid,:vslocid,:vmaterialgroupid,:vaccaktivatetap,:vaccakumat,:vaccbiayaat,:vaccpersediaan,:vaccreturpembelian,:vaccdiscpembelian,:vaccpenjualan,:vaccbiaya,:vaccreturpenjualan,:vaccspsi,:vaccexpedisi,:vhpp,:vaccupahlembur,:vfoh,:vacckoreksi,:vacccadangan,:vcreatedby)';
			$command=$connection->createCommand($sql);
			$command->bindvalue(':vid',$arraydata[0],PDO::PARAM_STR);
			$this->DeleteLock($this->menuname, $arraydata[0]);
		}
		$command->bindvalue(':vslocid',$arraydata[1],PDO::PARAM_STR);
		$command->bindvalue(':vmaterialgroupid',$arraydata[2],PDO::PARAM_STR);
		$command->bindvalue(':vaccaktivatetap',$arraydata[3],PDO::PARAM_STR);
		$command->bindvalue(':vaccakumat',$arraydata[4],PDO::PARAM_STR);
		$command->bindvalue(':vaccbiayaat',$arraydata[5],PDO::PARAM_STR);
		$command->bindvalue(':vaccpersediaan',$arraydata[6],PDO::PARAM_STR);
		$command->bindvalue(':vaccreturpembelian',$arraydata[7],PDO::PARAM_STR);
		$command->bindvalue(':vaccdiscpembelian',$arraydata[8],PDO::PARAM_STR);
		$command->bindvalue(':vaccpenjualan',$arraydata[9],PDO::PARAM_STR);
		$command->bindvalue(':vaccbiaya',$arraydata[10],PDO::PARAM_STR);
		$command->bindvalue(':vaccreturpenjualan',$arraydata[11],PDO::PARAM_STR);
		$command->bindvalue(':vaccspsi',$arraydata[12],PDO::PARAM_STR);
		$command->bindvalue(':vaccexpedisi',$arraydata[13],PDO::PARAM_STR);
		$command->bindvalue(':vhpp',$arraydata[14],PDO::PARAM_STR);
		$command->bindvalue(':vaccupahlembur',$arraydata[15],PDO::PARAM_STR);
		$command->bindvalue(':vfoh',$arraydata[16],PDO::PARAM_STR);
		$command->bindvalue(':vacckoreksi',$arraydata[17],PDO::PARAM_STR);
		$command->bindvalue(':vacccadangan',$arraydata[18],PDO::PARAM_STR);
		$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
		$command->execute();
	}
	public function actionUpload() {
		parent::actionUpload();
		$target_file = dirname('__FILES__').'/uploads/' . basename($_FILES["file-slocaccounting"]["name"]);
		if (move_uploaded_file($_FILES["file-slocaccounting"]["tmp_name"], $target_file)) {
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
					$sloccode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
					$slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '".$sloccode."'")->queryScalar();
					$companyid = Yii::app()->db->createCommand("select a.companyid from plant a join sloc b on b.plantid=a.plantid where b.sloccode = '".$sloccode."'")->queryScalar();
					$materialgroupcode = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
					$materialgroupid = Yii::app()->db->createCommand("select materialgroupid from materialgroup where description = '".$materialgroupcode."'")->queryScalar();
					$aktivatetap = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
					$aktivatetapid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$aktivatetap."' and companyid = '".$companyid."'")->queryScalar();
					$akumat = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
					$akumatid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$akumat."' and companyid = '".$companyid."'")->queryScalar();
					$biayaat = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
					$biayaatid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$biayaat."' and companyid = '".$companyid."'")->queryScalar();
					$persediaan = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
					$persediaanid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$persediaan."' and companyid = '".$companyid."'")->queryScalar();
					$returpembelian = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
					$returpembelianid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$returpembelian."' and companyid = '".$companyid."'")->queryScalar();
					$discpembelian = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
					$discpembelianid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$discpembelian."' and companyid = '".$companyid."'")->queryScalar();
					$penjualan = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
					$penjualanid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$penjualan."' and companyid = '".$companyid."'")->queryScalar();
					$biaya = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
					$biayaid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$biaya."' and companyid = '".$companyid."'")->queryScalar();
					$returpenjualan = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
					$returpenjualanid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$returpenjualan."' and companyid = '".$companyid."'")->queryScalar();
					$spsi = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
					$spsiid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$spsi."' and companyid = '".$companyid."'")->queryScalar();
					$expedisi = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
					$expedisiid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$expedisi."' and companyid = '".$companyid."'")->queryScalar();
					$hpp = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
					$hppid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$hpp."' and companyid = '".$companyid."'")->queryScalar();
					$upahlembur = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
					$upahlemburid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$upahlembur."' and companyid = '".$companyid."'")->queryScalar();
					$foh = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
					$fohid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$foh."' and companyid = '".$companyid."'")->queryScalar();
                    $acckoreksi = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
					$acckoreksiid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$acckoreksi."' and companyid = '".$companyid."'")->queryScalar();
                    $acccadangan = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
					$acccadanganid = Yii::app()->db->createCommand("select ifnull(accountid,0) from account where accountname = '".$acccadangan."' and companyid = '".$companyid."'")->queryScalar();
					$this->ModifyData($connection,array($id,$slocid,$materialgroupid,$aktivatetapid,$akumatid,$biayaatid,$persediaanid,$returpembelianid,$discpembelianid,
					$penjualanid,$biayaid,$returpenjualanid,$spsiid,$expedisiid,$hppid,$upahlemburid,$fohid,$acckoreksiid,$acccadanganid));
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
			$this->ModifyData($connection,array((isset($_POST['slocaccid'])?$_POST['slocaccid']:''),$_POST['slocid'],$_POST['materialgroupid'],$_POST['accaktivatetap'],
				$_POST['accakumat'],$_POST['accbiayaat'],$_POST['accpersediaan'],$_POST['accreturpembelian'],$_POST['accdiscpembelian'],$_POST['accpenjualan'],
				$_POST['accbiaya'],$_POST['accreturpenjualan'],$_POST['accspsi'],$_POST['accexpedisi'],$_POST['hpp'],$_POST['accupahlembur'],$_POST['foh'],$_POST['acckoreksi'],$_POST['acccadangan']));
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
				$sql = 'call Purgeslocaccounting(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids) {
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
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
	  $sql = "select t.slocaccid,f.description,a.accountname as accexpedisiname,b.accountname as acchppname,c.accountname as accupahlemburname,
			d.accountname as accfohname,e.accountname as accpersediaanname,p.sloccode as sloccode,q.accountname as accaktivatetapname
			,r.accountname as accakumatname,s.accountname as accbiayaatname,u.accountname as accreturpembelianname,v.accountname as accdiscpembelianname
			,w.accountname as accpenjualanname,x.accountname as accbiayaname,y.accountname as accreturpenjualanname,z.accountname as accspsiname,
            h.accountname as acckoreksiname,g.accountname as acccadanganname
				from slocaccounting t 
				left join sloc p on p.slocid = t.slocid 
				left join materialgroup f on f.materialgroupid = t.materialgroupid  
				left join account q on q.accountid=t.accaktivatetap 
				left join account r on r.accountid=t.accakumat 
				left join account s on s.accountid=t.accbiayaat 
			  left join account e on e.accountid=t.accpersediaan 
			left join account u on u.accountid=t.accreturpembelian 
			left join account v on v.accountid=t.accdiscpembelian 
			left join account w on w.accountid=t.accpenjualan 
			left join account x on x.accountid=t.accbiaya 
			left join account y on y.accountid=t.accreturpenjualan 
			left join account z on z.accountid=t.accspsi 
			left join account a on a.accountid=t.accexpedisi 
			left join account b on b.accountid=t.hpp 
			left join account c on c.accountid=t.accupahlembur 
			left join account d on d.accountid=t.foh 
			left join account h on h.accountid=t.acckoreksi
			left join account g on g.accountid=t.acccadangan ";
		$slocaccid = filter_input(INPUT_GET,'slocaccid');
		$sloccode = filter_input(INPUT_GET,'sloccode');
		$materialgroupname = filter_input(INPUT_GET,'materialgroupname');
		$sql .= " where coalesce(t.slocaccid,'') like '%".$slocaccid."%' 
			and coalesce(p.sloccode,'') like '%".$sloccode."%'
			and coalesce(f.description,'') like '%".$materialgroupname."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.slocaccid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('slocaccounting');
		$this->pdf->AddPage('L',array(400,600));
		$this->pdf->SetFontSize(8);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(
		GetCatalog('sloc'),
		GetCatalog('materialgroup'),
		GetCatalog('accaktivatetap'),
		GetCatalog('accakumat'),
		GetCatalog('accbiayaat'),
		GetCatalog('accpersediaan'),
		GetCatalog('accreturpembelian'),
		GetCatalog('accdiscpembelian'),
		GetCatalog('accpenjualan'),
		GetCatalog('accbiaya'),
		GetCatalog('accreturpenjualan'),
		GetCatalog('accspsi'),
		GetCatalog('accexpedisi'),
		GetCatalog('hpp'),
		GetCatalog('accupahlembur'),
		GetCatalog('foh'),
		GetCatalog('acckoreksi'),
		GetCatalog('acccadangan'));
		$this->pdf->setwidths(array(30,35,35,35,35,35,35,35,35,35,35,35,35,40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array(
				$row1['sloccode'],
				$row1['description'],
				$row1['accaktivatetapname'],
				$row1['accakumatname'],
				$row1['accbiayaatname'],
				$row1['accpersediaanname'],
				$row1['accreturpembelianname'],
				$row1['accdiscpembelianname'],
				$row1['accpenjualanname'],
				$row1['accbiayaname'],
				$row1['accreturpenjualanname'],
				$row1['accspsiname'],
				$row1['accexpedisiname'],
				$row1['acchppname'],
				$row1['accupahlemburname'],
				$row1['accfohname'],
				$row1['acckoreksiname'],
				$row1['acccadanganname']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		parent::actionDownXls();
		$sql = "select t.slocaccid,f.description,a.accountname as accexpedisiname,b.accountname as acchppname,c.accountname as accupahlemburname,
			d.accountname as accfohname,e.accountname as accpersediaanname,p.sloccode as sloccode,q.accountname as accaktivatetapname
			,r.accountname as accakumatname,s.accountname as accbiayaatname,u.accountname as accreturpembelianname,v.accountname as accdiscpembelianname
			,w.accountname as accpenjualanname,x.accountname as accbiayaname,y.accountname as accreturpenjualanname,z.accountname as accspsiname,
            h.accountname as acckoreksiname,g.accountname as acccadanganname
				from slocaccounting t 
				left join sloc p on p.slocid = t.slocid 
				left join materialgroup f on f.materialgroupid = t.materialgroupid  
				left join account q on q.accountid=t.accaktivatetap 
				left join account r on r.accountid=t.accakumat 
				left join account s on s.accountid=t.accbiayaat 
			  left join account e on e.accountid=t.accpersediaan 
			left join account u on u.accountid=t.accreturpembelian 
			left join account v on v.accountid=t.accdiscpembelian 
			left join account w on w.accountid=t.accpenjualan 
			left join account x on x.accountid=t.accbiaya 
			left join account y on y.accountid=t.accreturpenjualan 
			left join account z on z.accountid=t.accspsi 
			left join account a on a.accountid=t.accexpedisi 
			left join account b on b.accountid=t.hpp 
			left join account c on c.accountid=t.accupahlembur 
			left join account d on d.accountid=t.foh
            left join account h on h.accountid=t.acckoreksi
			left join account g on g.accountid=t.acccadangan";
		$slocaccid = filter_input(INPUT_GET,'slocaccid');
		$sloccode = filter_input(INPUT_GET,'sloccode');
		$materialgroupname = filter_input(INPUT_GET,'materialgroupname');
		$sql .= " where coalesce(t.slocaccid,'') like '%".$slocaccid."%' 
			and coalesce(p.sloccode,'') like '%".$sloccode."%'
			and coalesce(f.description,'') like '%".$materialgroupname."%'
			";
		if ($_GET['id'] !== '') {
				$sql = $sql . " and t.slocaccid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$i=1;
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,1,GetCatalog('slocaccid'))
		->setCellValueByColumnAndRow(1,1,GetCatalog('sloc'))
		->setCellValueByColumnAndRow(2,1,GetCatalog('materialgroup'))
		->setCellValueByColumnAndRow(3,1,GetCatalog('accaktivatetap'))
		->setCellValueByColumnAndRow(4,1,GetCatalog('accakumat'))
		->setCellValueByColumnAndRow(5,1,GetCatalog('accbiayaat'))
		->setCellValueByColumnAndRow(6,1,GetCatalog('accpersediaan'))
		->setCellValueByColumnAndRow(7,1,GetCatalog('accreturpembelian'))
		->setCellValueByColumnAndRow(8,1,GetCatalog('accdiscpembelian'))
		->setCellValueByColumnAndRow(9,1,GetCatalog('accpenjualan'))
		->setCellValueByColumnAndRow(10,1,GetCatalog('accbiaya'))
		->setCellValueByColumnAndRow(11,1,GetCatalog('accreturpenjualan'))
		->setCellValueByColumnAndRow(12,1,GetCatalog('accspsi'))
		->setCellValueByColumnAndRow(13,1,GetCatalog('accexpedisi'))
		->setCellValueByColumnAndRow(14,1,GetCatalog('hpp'))
		->setCellValueByColumnAndRow(15,1,GetCatalog('accupahlembur'))
		->setCellValueByColumnAndRow(16,1,GetCatalog('foh'))
		->setCellValueByColumnAndRow(17,1,GetCatalog('acckoreksi'))
		->setCellValueByColumnAndRow(18,1,GetCatalog('acccadangan'));		
		foreach($dataReader as $row1) {
			  $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $i+1, $row1['slocaccid'])
				->setCellValueByColumnAndRow(1, $i+1, $row1['sloccode'])
				->setCellValueByColumnAndRow(2, $i+1, $row1['description'])
				->setCellValueByColumnAndRow(3, $i+1, $row1['accaktivatetapname'])
				->setCellValueByColumnAndRow(4, $i+1, $row1['accakumatname'])
				->setCellValueByColumnAndRow(5, $i+1, $row1['accbiayaatname'])
				->setCellValueByColumnAndRow(6, $i+1, $row1['accpersediaanname'])
				->setCellValueByColumnAndRow(7, $i+1, $row1['accreturpembelianname'])
				->setCellValueByColumnAndRow(8, $i+1, $row1['accdiscpembelianname'])
				->setCellValueByColumnAndRow(9, $i+1, $row1['accpenjualanname'])
				->setCellValueByColumnAndRow(10, $i+1, $row1['accbiayaname'])
				->setCellValueByColumnAndRow(11, $i+1, $row1['accreturpenjualanname'])
				->setCellValueByColumnAndRow(12, $i+1, $row1['accspsiname'])
				->setCellValueByColumnAndRow(13, $i+1, $row1['accexpedisiname'])
				->setCellValueByColumnAndRow(14, $i+1, $row1['acchppname'])
				->setCellValueByColumnAndRow(15, $i+1, $row1['accupahlemburname'])
				->setCellValueByColumnAndRow(16, $i+1, $row1['accfohname'])
				->setCellValueByColumnAndRow(17, $i+1, $row1['acckoreksiname'])
				->setCellValueByColumnAndRow(18, $i+1, $row1['acccadanganname'])
				;		$i+=1;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}