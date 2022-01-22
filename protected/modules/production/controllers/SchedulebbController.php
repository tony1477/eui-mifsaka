<?php
class SchedulebbController extends Controller
{
  public $menuname = 'schedulebb';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function actionUploaddata()
  {
	if (!file_exists(Yii::getPathOfAlias('webroot').'/uploads/'))
	{
		mkdir(Yii::getPathOfAlias('webroot').'/uploads/');
	}
    $storeFolder = dirname('__FILES__').'/uploads/';
	$tempFile = $_FILES['upload']['tmp_name'];                     									 
    $targetFile =  $storeFolder. $_FILES['upload']['name']; 		 
	move_uploaded_file($tempFile,$targetFile);
	echo json_encode(array(
        'status'=>'success',
        'filename'=>$_FILES['upload']['name']
    ));
    Yii::app()->end();
  }
  public function actionRunning()
  {
		$s = $_POST['id'];
		Yii::import('ext.PHPExcel.XPHPExcel');
		Yii::import('ext.PHPExcel.vendor.PHPExcel'); 
		$phpExcel = XPHPExcel::createPHPExcel();
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$phpExcel = $objReader->load(dirname('__FILES__').'/uploads/'.$s);
      
        $connection = Yii::app()->db;
		try
		{
			$sheet = $phpExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			
			
			for ($i = 4;$i <= $highestRow; $i++)
			{	
				$company = $sheet->getCell('A'.$i)->getValue();
				$companyid = Yii::app()->db->createCommand("select companyid from company where lower(companyname) = lower('".$company."')")->queryScalar();
				if ($companyid == null)
				{
					getmessage('error','emptycompanyid');
				}
                	
				$bulan = $sheet->getCell('B'.$i)->getValue();
				$tahun = $sheet->getCell('C'.$i)->getValue();
				$product = $sheet->getCell('D'.$i)->getValue();
				$productid = Yii::app()->db->createCommand("select productid from product where lower(productname) = lower('".$product."')")->queryScalar();
				if ($productid == null)
				{
					getmessage('error','emptyproductid');
				}
				$uom = $sheet->getCell('E'.$i)->getValue();
				$uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where lower(uomcode) = lower('".$uom."')")->queryScalar();
				if ($uomid == null)
				{
					getmessage('error','emptyunitofmeasureid');
				}
                $sloc = $sheet->getCell('F'.$i)->getValue();
				$slocid = Yii::app()->db->createCommand("select slocid from sloc where lower(sloccode) = lower('".$sloc."')")->queryScalar();
				if ($slocid == null)
				{
					getmessage('error','emptyslocid');
				}
				$d1 = $sheet->getCell('G'.$i)->getValue();
				$d2 = $sheet->getCell('H'.$i)->getValue();
				$d3 = $sheet->getCell('I'.$i)->getValue();
				$d4 = $sheet->getCell('J'.$i)->getValue();
				$d5 = $sheet->getCell('K'.$i)->getValue();
				$d6 = $sheet->getCell('L'.$i)->getValue();
				$d7 = $sheet->getCell('M'.$i)->getValue();
				$d8 = $sheet->getCell('N'.$i)->getValue();
				$d9 = $sheet->getCell('O'.$i)->getValue();
				$d10 = $sheet->getCell('P'.$i)->getValue();
				$d11 = $sheet->getCell('Q'.$i)->getValue();
				$d12 = $sheet->getCell('R'.$i)->getValue();
				$d13 = $sheet->getCell('S'.$i)->getValue();
				$d14 = $sheet->getCell('T'.$i)->getValue();
				$d15 = $sheet->getCell('U'.$i)->getValue();
				$d16 = $sheet->getCell('V'.$i)->getValue();
				$d17 = $sheet->getCell('W'.$i)->getValue();
				$d18 = $sheet->getCell('X'.$i)->getValue();
				$d19 = $sheet->getCell('Y'.$i)->getValue();
				$d20 = $sheet->getCell('Z'.$i)->getValue();
				$d21 = $sheet->getCell('AA'.$i)->getValue();
				$d22 = $sheet->getCell('AB'.$i)->getValue();
				$d23 = $sheet->getCell('AC'.$i)->getValue();
				$d24 = $sheet->getCell('AD'.$i)->getValue();
				$d25 = $sheet->getCell('AE'.$i)->getValue();
				$d26 = $sheet->getCell('AF'.$i)->getValue();
				$d27 = $sheet->getCell('AG'.$i)->getValue();
				$d28 = $sheet->getCell('AH'.$i)->getValue();
				$d29 = $sheet->getCell('AI'.$i)->getValue();
				$d30 = $sheet->getCell('AJ'.$i)->getValue();
				$d31 = $sheet->getCell('AK'.$i)->getValue();
               
                $sql =
				"insert into schedulebb (companyid,bulan,tahun,productid,uomid,slocid,d1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11,
			d12,d13,d14,d15,d16,d17,d18,d19,d20,d21,d22,d23,d24,d25,d26,d27,d28,d29,d30,d31)
		values (:companyid,:bulan,:tahun,:productid,:uomid,:slocid,:d1,:d2,:d3,:d4,:d5,:d6,:d7,:d8,:d9,:d10,:d11,:d12,:d13,:d14,:d15,:d16,:d17,:d18,:d19,:d20,:d21,:d22,:d23,:d24,:d25,:d26,:d27,:d28,:d29,:d30,:d31)";
           $command = $connection->createCommand($sql); 
           $command->bindvalue(':companyid',$companyid,PDO::PARAM_STR);   
           $command->bindvalue(':bulan',$bulan,PDO::PARAM_STR);   
           $command->bindvalue(':tahun',$tahun,PDO::PARAM_STR);   
           $command->bindvalue(':productid',$productid,PDO::PARAM_STR);   
           $command->bindvalue(':uomid',$uomid,PDO::PARAM_STR);   
           $command->bindvalue(':slocid',$slocid,PDO::PARAM_STR);   
           $command->bindvalue(':d1',$d1,PDO::PARAM_STR);   
           $command->bindvalue(':d2',$d2,PDO::PARAM_STR);   
           $command->bindvalue(':d3',$d3,PDO::PARAM_STR);   
           $command->bindvalue(':d4',$d4,PDO::PARAM_STR);   
           $command->bindvalue(':d5',$d5,PDO::PARAM_STR);   
           $command->bindvalue(':d6',$d6,PDO::PARAM_STR);   
           $command->bindvalue(':d7',$d7,PDO::PARAM_STR);   
           $command->bindvalue(':d8',$d8,PDO::PARAM_STR);   
           $command->bindvalue(':d9',$d9,PDO::PARAM_STR);   
           $command->bindvalue(':d10',$d10,PDO::PARAM_STR);   
           $command->bindvalue(':d11',$d11,PDO::PARAM_STR);   
           $command->bindvalue(':d12',$d12,PDO::PARAM_STR);   
           $command->bindvalue(':d13',$d13,PDO::PARAM_STR);   
           $command->bindvalue(':d14',$d14,PDO::PARAM_STR);   
           $command->bindvalue(':d15',$d15,PDO::PARAM_STR);   
           $command->bindvalue(':d16',$d16,PDO::PARAM_STR);   
           $command->bindvalue(':d17',$d17,PDO::PARAM_STR);   
           $command->bindvalue(':d18',$d18,PDO::PARAM_STR);   
           $command->bindvalue(':d19',$d19,PDO::PARAM_STR);   
           $command->bindvalue(':d20',$d20,PDO::PARAM_STR);   
           $command->bindvalue(':d21',$d21,PDO::PARAM_STR);   
           $command->bindvalue(':d22',$d22,PDO::PARAM_STR);   
           $command->bindvalue(':d23',$d23,PDO::PARAM_STR);   
           $command->bindvalue(':d24',$d24,PDO::PARAM_STR);   
           $command->bindvalue(':d25',$d25,PDO::PARAM_STR);   
           $command->bindvalue(':d26',$d26,PDO::PARAM_STR);   
           $command->bindvalue(':d27',$d27,PDO::PARAM_STR);   
           $command->bindvalue(':d28',$d28,PDO::PARAM_STR);   
           $command->bindvalue(':d29',$d29,PDO::PARAM_STR);   
           $command->bindvalue(':d30',$d30,PDO::PARAM_STR);   
           $command->bindvalue(':d31',$d31,PDO::PARAM_STR);   
           $command->execute(); 
					
			}
                 echo json_encode(array(
                    'status'=>'success',
            ));
			//getmessage('success',"alreadysaved");
		}	
		catch (Exception $e)
		{
			getmessage('error',$e->getMessage());
		}	
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
        $sql     = 'call Insertschedulebb(:vcompanyid,:vbulan,:vtahun,:vproductid,:vunitofmeasureid,:vslocid,:vd1,:vd2,:vd3,:vd4,:vd5,:vd6,:vd7,:vd8,:vd9,:vd10,:vd11,:vd12,:vd13,:vd14,:vd15,:vd16,:vd17,:vd18,:vd19,:vd20,:vd21,:vd22,:vd23,:vd24,:vd25,:vd26,:vd27,:vd28,:vd29,:vd30,:vd31,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateschedulebb(:vid,:vcompanyid,:vbulan,:vtahun,:vproductid,:vunitofmeasureid,:vslocid,:vd1,:vd2,:vd3,:vd4,:vd5,:vd6,:vd7,:vd8,:vd9,:vd10,:vd11,:vd12,:vd13,:vd14,:vd15,:vd16,:vd17,:vd18,:vd19,:vd20,:vd21,:vd22,:vd23,:vd24,:vd25,:vd26,:vd27,:vd28,:vd29,:vd30,:vd31,:vcreatedby)';
        $command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['schedulebbid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['schedulebbid']);
			}
			$command->bindvalue(':vcompanyid',$_POST['companyid'],PDO::PARAM_STR);
			$command->bindvalue(':vbulan',$_POST['bulan'],PDO::PARAM_STR);
			$command->bindvalue(':vtahun',$_POST['tahun'],PDO::PARAM_STR);
			$command->bindvalue(':vproductid',$_POST['productid'],PDO::PARAM_STR);
			$command->bindvalue(':vunitofmeasureid',$_POST['unitofmeasureid'],PDO::PARAM_STR);
			$command->bindvalue(':vslocid',$_POST['slocid'],PDO::PARAM_STR);
            $command->bindvalue(':vd1',$_POST['d1'],PDO::PARAM_STR);
			$command->bindvalue(':vd2',$_POST['d2'],PDO::PARAM_STR);
			$command->bindvalue(':vd3',$_POST['d3'],PDO::PARAM_STR);
			$command->bindvalue(':vd4',$_POST['d4'],PDO::PARAM_STR);
			$command->bindvalue(':vd5',$_POST['d5'],PDO::PARAM_STR);
			$command->bindvalue(':vd6',$_POST['d6'],PDO::PARAM_STR);
			$command->bindvalue(':vd7',$_POST['d7'],PDO::PARAM_STR);
			$command->bindvalue(':vd8',$_POST['d8'],PDO::PARAM_STR);
			$command->bindvalue(':vd9',$_POST['d9'],PDO::PARAM_STR);
			$command->bindvalue(':vd10',$_POST['d10'],PDO::PARAM_STR);
			$command->bindvalue(':vd11',$_POST['d11'],PDO::PARAM_STR);
			$command->bindvalue(':vd12',$_POST['d12'],PDO::PARAM_STR);
			$command->bindvalue(':vd13',$_POST['d13'],PDO::PARAM_STR);
			$command->bindvalue(':vd14',$_POST['d14'],PDO::PARAM_STR);
			$command->bindvalue(':vd15',$_POST['d15'],PDO::PARAM_STR);
			$command->bindvalue(':vd16',$_POST['d16'],PDO::PARAM_STR);
			$command->bindvalue(':vd17',$_POST['d17'],PDO::PARAM_STR);
			$command->bindvalue(':vd18',$_POST['d18'],PDO::PARAM_STR);
			$command->bindvalue(':vd19',$_POST['d19'],PDO::PARAM_STR);
			$command->bindvalue(':vd20',$_POST['d20'],PDO::PARAM_STR);
			$command->bindvalue(':vd21',$_POST['d21'],PDO::PARAM_STR);
			$command->bindvalue(':vd22',$_POST['d22'],PDO::PARAM_STR);
			$command->bindvalue(':vd23',$_POST['d23'],PDO::PARAM_STR);
			$command->bindvalue(':vd24',$_POST['d24'],PDO::PARAM_STR);
			$command->bindvalue(':vd25',$_POST['d25'],PDO::PARAM_STR);
			$command->bindvalue(':vd26',$_POST['d26'],PDO::PARAM_STR);
			$command->bindvalue(':vd27',$_POST['d27'],PDO::PARAM_STR);
			$command->bindvalue(':vd28',$_POST['d28'],PDO::PARAM_STR);
			$command->bindvalue(':vd29',$_POST['d29'],PDO::PARAM_STR);
			$command->bindvalue(':vd30',$_POST['d30'],PDO::PARAM_STR);
			$command->bindvalue(':vd31',$_POST['d31'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();
			getmessage(false,'insertsuccess');
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			getmessage(true,$e->getMessage());
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
        $sql     = 'call Purgeschedulebb(:vid,:vcreatedby)';
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
	public function search()
	{
		header("Content-Type: application/json");
		
		// search 
		$schedulebbid = isset ($_POST['schedulebbid']) ? $_POST['schedulebbid'] : '';
                $companyid = isset ($_POST['companyid']) ? $_POST['companyid'] : '';
                $bulan = isset ($_POST['bulan']) ? $_POST['bulan'] : '';
                $tahun = isset ($_POST['tahun']) ? $_POST['tahun'] : '';
                $productid = isset ($_POST['productid']) ? $_POST['productid'] : '';
                $unitofmeasureid = isset ($_POST['vunitofmeasureid']) ? $_POST['vunitofmeasureid'] : '';
                $slocid = isset ($_POST['slocid']) ? $_POST['slocid'] : '';
                $d1 = isset ($_POST['d1']) ? $_POST['d1'] : '';
                $d2 = isset ($_POST['d2']) ? $_POST['d2'] : '';
                $d3 = isset ($_POST['d3']) ? $_POST['d3'] : '';
                $d4 = isset ($_POST['d4']) ? $_POST['d4'] : '';
                $d5 = isset ($_POST['d5']) ? $_POST['d5'] : '';
                $d6 = isset ($_POST['d6']) ? $_POST['d6'] : '';
                $d7 = isset ($_POST['d7']) ? $_POST['d7'] : '';
                $d8 = isset ($_POST['d8']) ? $_POST['d8'] : '';
                $d9 = isset ($_POST['d9']) ? $_POST['d9'] : '';
                $d10 = isset ($_POST['d10']) ? $_POST['d10'] : '';
                $d11 = isset ($_POST['d11']) ? $_POST['d11'] : '';
                $d12 = isset ($_POST['d12']) ? $_POST['d12'] : '';
                $d13 = isset ($_POST['d13']) ? $_POST['d13'] : '';
                $d14 = isset ($_POST['d14']) ? $_POST['d14'] : '';
                $d15 = isset ($_POST['d15']) ? $_POST['d15'] : '';
                $d16 = isset ($_POST['d16']) ? $_POST['d16'] : '';
                $d17 = isset ($_POST['d17']) ? $_POST['d17'] : '';
                $d18 = isset ($_POST['d18']) ? $_POST['d18'] : '';
                $d19 = isset ($_POST['d19']) ? $_POST['d19'] : '';
                $d20 = isset ($_POST['d20']) ? $_POST['d20'] : '';
                $d21 = isset ($_POST['d21']) ? $_POST['d21'] : '';
                $d22 = isset ($_POST['d22']) ? $_POST['d22'] : '';
                $d23 = isset ($_POST['d23']) ? $_POST['d23'] : '';
                $d24 = isset ($_POST['d24']) ? $_POST['d24'] : '';
                $d25 = isset ($_POST['d25']) ? $_POST['d25'] : '';
                $d26 = isset ($_POST['d26']) ? $_POST['d26'] : '';
                $d27 = isset ($_POST['d27']) ? $_POST['d27'] : '';
                $d28 = isset ($_POST['d28']) ? $_POST['d28'] : '';
                $d29 = isset ($_POST['d29']) ? $_POST['d29'] : '';
                $d30 = isset ($_POST['d30']) ? $_POST['d30'] : '';
                $d31 = isset ($_POST['d31']) ? $_POST['d31'] : '';
               
		$schedulebbid = isset ($_GET['q']) ? $_GET['q'] : $schedulebbid;
                $companyid = isset ($_GET['q']) ? $_GET['q'] : $companyid;
                $bulan = isset ($_GET['q']) ? $_GET['q'] : $bulan;
                $tahun = isset ($_GET['q']) ? $_GET['q'] : $tahun;
                $productid = isset ($_GET['q']) ? $_GET['q'] : $productid;
                $unitofmeasureid = isset ($_GET['q']) ? $_GET['q'] : $unitofmeasureid;
                $slocid = isset ($_GET['q']) ? $_GET['q'] : $slocid;
                $d1 = isset ($_GET['q']) ? $_GET['q'] : $d1;
                $d2 = isset ($_GET['q']) ? $_GET['q'] : $d2;
                $d3 = isset ($_GET['q']) ? $_GET['q'] : $d3;
                $d4 = isset ($_GET['q']) ? $_GET['q'] : $d4;
                $d5 = isset ($_GET['q']) ? $_GET['q'] : $d5;
                $d6 = isset ($_GET['q']) ? $_GET['q'] : $d6;
                $d7 = isset ($_GET['q']) ? $_GET['q'] : $d7;
                $d8 = isset ($_GET['q']) ? $_GET['q'] : $d8;
                $d9 = isset ($_GET['q']) ? $_GET['q'] : $d9;
                $d10 = isset ($_GET['q']) ? $_GET['q'] : $d10;
                $d11 = isset ($_GET['q']) ? $_GET['q'] : $d11;
                $d12 = isset ($_GET['q']) ? $_GET['q'] : $d12;
                $d13 = isset ($_GET['q']) ? $_GET['q'] : $d13;
                $d14 = isset ($_GET['q']) ? $_GET['q'] : $d14;
                $d15 = isset ($_GET['q']) ? $_GET['q'] : $d15;
                $d16 = isset ($_GET['q']) ? $_GET['q'] : $d16;
                $d17 = isset ($_GET['q']) ? $_GET['q'] : $d17;
                $d18 = isset ($_GET['q']) ? $_GET['q'] : $d18;
                $d19 = isset ($_GET['q']) ? $_GET['q'] : $d19;
                $d20 = isset ($_GET['q']) ? $_GET['q'] : $d20;
                $d21 = isset ($_GET['q']) ? $_GET['q'] : $d21;
                $d22 = isset ($_GET['q']) ? $_GET['q'] : $d22;
                $d23 = isset ($_GET['q']) ? $_GET['q'] : $d23;
                $d24 = isset ($_GET['q']) ? $_GET['q'] : $d24;
                $d25 = isset ($_GET['q']) ? $_GET['q'] : $d25;
                $d26 = isset ($_GET['q']) ? $_GET['q'] : $d26;
                $d27 = isset ($_GET['q']) ? $_GET['q'] : $d27;
                $d28 = isset ($_GET['q']) ? $_GET['q'] : $d28;
                $d29 = isset ($_GET['q']) ? $_GET['q'] : $d29;
                $d30 = isset ($_GET['q']) ? $_GET['q'] : $d30;
                $d31 = isset ($_GET['q']) ? $_GET['q'] : $d31;
                
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'zz.schedulebbid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
    $cmd = Yii::app()->db->createCommand()
                        ->select('count(1) as total')	
                        ->from('schedulebb zz')
                        ->leftjoin('company pp','pp.companyid=zz.companyid')
                        ->leftjoin('product a','a.productid=zz.productid')
                        ->leftjoin('unitofmeasure b','b.unitofmeasureid=zz.uomid')
                        ->leftjoin('sloc c','c.slocid=zz.slocid')
                        
                        ->where('(a.productname like :productid) or 
                                (bulan like :bulan) or
                                (tahun like :tahun)',
                                        array(':productid'=>'%'.$productid.'%',
                                            ':bulan'=>'%'.$bulan.'%',
                                            ':tahun'=>'%'.$tahun.'%'
                                            ))
                        ->queryRow();
	
		$result['total'] = $cmd['total'];
		
		$cmd = Yii::app()->db->createCommand()
                        ->select('zz.*,pp.companyname as companyname,a.productname as productname,b.uomcode as uomcode,c.sloccode as sloccode')	
                        ->from('schedulebb zz')
                        ->leftjoin('company pp','pp.companyid=zz.companyid')
                        ->leftjoin('product a','a.productid=zz.productid')
                        ->leftjoin('unitofmeasure b','b.unitofmeasureid=zz.uomid')
                        ->leftjoin('sloc c','c.slocid=zz.slocid')
                        
                       ->where('(a.productname like :productid) or 
                                (bulan like :bulan) or
                                (tahun like :tahun)',
                                       
                                         array(':productid'=>'%'.$productid.'%',
                                            ':bulan'=>'%'.$bulan.'%',
                                            ':tahun'=>'%'.$tahun.'%'
                                            ))
                        ->offset($offset)
                                ->limit($rows)
                                ->order($sort.' '.$order)
                                ->queryAll();
    foreach($cmd as $data)
		{	
			$row[] = array(
		'schedulebbid'=>$data['schedulebbid'],
                'companyid'=>$data['companyid'],
                'companyname'=>$data['companyname'],
                'bulan'=>$data['bulan'],
                'tahun'=>$data['tahun'],
                'productid'=>$data['productid'],
                'productname'=>$data['productname'],
                'uomid'=>$data['uomid'],
                'uomcode'=>$data['uomcode'],
                
                'sloccode'=>$data['sloccode'],
                'd1'=>$data['d1'],
                
                'd2'=>$data['d2'],
                
                'd3'=>$data['d3'],
               
                'd4'=>$data['d4'],
                
                'd5'=>$data['d5'],
                
                'd6'=>$data['d6'],
                
                'd7'=>$data['d7'],
                
                'd8'=>$data['d8'],
                
                'd9'=>$data['d9'],
               
                'd10'=>$data['d10'],
                
                'd11'=>$data['d11'],
               
                'd12'=>$data['d12'],
               
                'd13'=>$data['d13'],
               
                'd14'=>$data['d14'],
                
                'd15'=>$data['d15'],
               
                'd16'=>$data['d16'],
                
                'd17'=>$data['d17'],
                
                'd18'=>$data['d18'],
                
                'd19'=>$data['d19'],
                
                'd20'=>$data['d20'],
               
                'd21'=>$data['d21'],
                
                'd22'=>$data['d22'],
                
                'd23'=>$data['d23'],
               
                'd24'=>$data['d24'],
                
                'd25'=>$data['d25'],
                
                'd26'=>$data['d26'],
                
                'd27'=>$data['d27'],
                
                'd28'=>$data['d28'],
                
                'd29'=>$data['d29'],
               
                'd30'=>$data['d30'],
                
                'd31'=>$data['d31'],
              
              
               
               
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
		
	}
  public function actionDownPDF()
  {
    parent::actionDownload();
    $sql = "select zz.schedulebbid,pp.companyname as companyname,zz.bulan,zz.tahun,a.productname as productname,b.uomcode as uomcode,c.sloccode as sloccode,zz.d1,zz.d2,zz.d3,zz.d4,zz.d5,zz.d6,zz.d7,zz.d8,zz.d9,zz.d10,zz.d11,zz.d12,zz.d13,zz.d14,zz.d15,zz.d16,zz.d17,zz.d18,zz.d19,zz.d20,zz.d21,zz.d22,zz.d23,zz.d24,zz.d25,zz.d26,zz.d27,zz.d28,zz.d29,zz.d30,zz.d31
from schedulebb zz
left join company pp on pp.companyid=zz.companyid
left join product a on a.productid=zz.productid
left join unitofmeasure b on b.unitofmeasureid=zz.uomid
left join sloc c on c.slocid=zz.slocid ";
                        
						
    if ($_GET['id'] !== '') {
      $sql = $sql . "where zz.schedulebbid in (" . $_GET['id'] . ")";
    }
    $command          = $this->connection->createCommand($sql);
    $dataReader       = $command->queryAll();
    $this->pdf->title = getCatalog('schedulebb');
    $this->pdf->AddPage('L', array(
      250,
      550
    ));
    $this->pdf->setFont('Arial', 'B', 8);
    $this->pdf->colalign  = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
      'L',   
         

    );
    $this->pdf->colheader = array(
      getCatalog('schedulebbid'),
      getCatalog('companyname'),
      getCatalog('bulan'),
      getCatalog('tahun'),
      getCatalog('productname'),
      getCatalog('uomcode'),
      getCatalog('sloccode'),
      getCatalog('d1'),
      getCatalog('d2'),
      getCatalog('d3'),
      getCatalog('d4'),
      getCatalog('d5'),
      getCatalog('d6'),
      getCatalog('d7'),
      getCatalog('d8'),
      getCatalog('d9'),
      getCatalog('d10'),
      getCatalog('d11'),
      getCatalog('d12'),
      getCatalog('d13'),
      getCatalog('d14'),
      getCatalog('d15'),
      getCatalog('d16'),
      getCatalog('d17'),
      getCatalog('d18'),
      getCatalog('d19'),
      getCatalog('d20'),
      getCatalog('d21'),
      getCatalog('d22'),
      getCatalog('d23'),
      getCatalog('d24'),
      getCatalog('d25'),
      getCatalog('d26'),
      getCatalog('d27'),
      getCatalog('d28'),
      getCatalog('d29'),
      getCatalog('d30'),
      getCatalog('d31'),
     
    );
    $this->pdf->setwidths(array(
      10,
      60,
      10,
      15,
      80,
      20,
      25,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10,
      10
    ));
    $this->pdf->Rowheader();
    $this->pdf->setFont('Arial', '', 10);
    $this->pdf->coldetailalign = array(
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      'L',
      
    );
    foreach ($dataReader as $row1) {
      $this->pdf->row(array(
        $row1['schedulebbid'],
        $row1['companyname'],
        $row1['bulan'],
        $row1['tahun'],
        $row1['productname'],
        $row1['uomcode'],
        $row1['sloccode'],
        number_format($row1['d1']),
        number_format($row1['d2']),
        number_format($row1['d3']),
        number_format($row1['d4']),
        number_format($row1['d5']),
        number_format($row1['d6']),
        number_format($row1['d7']),
        number_format($row1['d8']),
        number_format($row1['d9']),
        number_format($row1['d10']),
        number_format($row1['d11']),
        number_format($row1['d12']),
        number_format($row1['d13']),
        number_format($row1['d14']),
        number_format($row1['d15']),
        number_format($row1['d16']),
        number_format($row1['d17']),
        number_format($row1['d18']),
        number_format($row1['d19']),
        number_format($row1['d20']),
        number_format($row1['d21']),
        number_format($row1['d22']),
        number_format($row1['d23']),
        number_format($row1['d24']),
        number_format($row1['d25']),
        number_format($row1['d26']),
        number_format($row1['d27']),
        number_format($row1['d28']),
        number_format($row1['d29']),
        number_format($row1['d30']),
        number_format($row1['d31'])
      ));
    }
    $this->pdf->Output();
  }
  public function actionDownxls()
  {
    $this->menuname = 'schedulebb';
    parent::actionDownxls();
    $sql = "select zz.schedulebbid,pp.companyname as companyname,zz.bulan,zz.tahun,a.productname as productname,b.uomcode as uomcode,c.sloccode as sloccode,zz.d1,zz.d2,zz.d3,zz.d4,zz.d5,zz.d6,zz.d7,zz.d8,zz.d9,zz.d10,zz.d11,zz.d12,zz.d13,zz.d14,zz.d15,zz.d16,zz.d17,zz.d18,zz.d19,zz.d20,zz.d21,zz.d22,zz.d23,zz.d24,zz.d25,zz.d26,zz.d27,zz.d28,zz.d29,zz.d30,zz.d31
from schedulebb zz
left join company pp on pp.companyid=zz.companyid
left join product a on a.productid=zz.productid
left join unitofmeasure b on b.unitofmeasureid=zz.uomid
left join sloc c on c.slocid=zz.slocid ";
                        
    if ($_GET['id'] !== '') {
      $sql = $sql . "where zz.schedulebbid in (" . $_GET['id'] . ")";
    }
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    $i          = 3;
    foreach ($dataReader as $row1) {
      $this->phpExcel->setActiveSheetIndex(0)
    ->setCellValueByColumnAndRow(0, $i, $row1['companyname'])
    ->setCellValueByColumnAndRow(1, $i, $row1['bulan'])
    ->setCellValueByColumnAndRow(2, $i, $row1['tahun'])
    ->setCellValueByColumnAndRow(3, $i, $row1['productname'])
    ->setCellValueByColumnAndRow(4, $i, $row1['uomcode'])
    ->setCellValueByColumnAndRow(5, $i, $row1['sloccode'])
    ->setCellValueByColumnAndRow(6, $i, $row1['d1'])
    ->setCellValueByColumnAndRow(7, $i, $row1['d2'])
    ->setCellValueByColumnAndRow(8, $i, $row1['d3'])
    ->setCellValueByColumnAndRow(9, $i, $row1['d4'])
    ->setCellValueByColumnAndRow(10, $i, $row1['d5'])
    ->setCellValueByColumnAndRow(11, $i, $row1['d6'])
    ->setCellValueByColumnAndRow(12, $i, $row1['d7'])
    ->setCellValueByColumnAndRow(13, $i, $row1['d8'])
    ->setCellValueByColumnAndRow(14, $i, $row1['d9'])
    ->setCellValueByColumnAndRow(15, $i, $row1['d10'])
    ->setCellValueByColumnAndRow(16, $i, $row1['d11'])
    ->setCellValueByColumnAndRow(17, $i, $row1['d12'])
    ->setCellValueByColumnAndRow(18, $i, $row1['d13'])
    ->setCellValueByColumnAndRow(19, $i, $row1['d14'])
    ->setCellValueByColumnAndRow(20, $i, $row1['d15'])
    ->setCellValueByColumnAndRow(21, $i, $row1['d16'])
    ->setCellValueByColumnAndRow(22, $i, $row1['d17'])
    ->setCellValueByColumnAndRow(23, $i, $row1['d18'])
    ->setCellValueByColumnAndRow(24, $i, $row1['d19'])
    ->setCellValueByColumnAndRow(25, $i, $row1['d20'])
    ->setCellValueByColumnAndRow(26, $i, $row1['d21'])
    ->setCellValueByColumnAndRow(27, $i, $row1['d22'])
    ->setCellValueByColumnAndRow(28, $i, $row1['d23'])
    ->setCellValueByColumnAndRow(29, $i, $row1['d24'])
    ->setCellValueByColumnAndRow(30, $i, $row1['d25'])
    ->setCellValueByColumnAndRow(31, $i, $row1['d26'])
    ->setCellValueByColumnAndRow(32, $i, $row1['d27'])
    ->setCellValueByColumnAndRow(33, $i, $row1['d28'])
    ->setCellValueByColumnAndRow(34, $i, $row1['d29'])
    ->setCellValueByColumnAndRow(35, $i, $row1['d30'])
    ->setCellValueByColumnAndRow(36, $i, $row1['d31']);
      $i++;
    }
    $this->getFooterXLS($this->phpExcel);
  }
 
}