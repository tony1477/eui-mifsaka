<?php

class ProductionController extends Controller
{
	protected $pageTitle = 'API Production';
	
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionGetSO()
	{
		$response = array();
		$row = array();
		$response["error"] = TRUE;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['username']) && isset($_REQUEST['key']))
		{
			if ($_REQUEST['tag'] == 'getso') 
			{
				if (getkey($_REQUEST['username']) == $_REQUEST['key'])
				{
					$cmd = Yii::app()->db->createCommand("select distinct b.soheaderid,c.sono
						from sodetail b 
						join soheader c on c.soheaderid = b.soheaderid
						where b.giqty < b.qty and sono is not null ")->queryAll();
					foreach($cmd as $data)
					{	
						$row[] = array(
							'soheaderid'=>$data['soheaderid'],
							'sono'=>$data['sono']
						);
					}
					$response["error"] = FALSE;
					$response["error_msg"] = 'OK';
					$response=array_merge($response,array('rows'=>$row));
				}
				else
				{
					$response["error"] = TRUE;
					$response['error_msg'] = getCatalog('youarenotauthorized');
				}
			}
			else
			{
				$response["error"] = TRUE;
				$response['error_msg'] = getCatalog('invalidmethod');
			}
		}
		else
		{
			$response["error"] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
	
	public function actionGetProductByBarcode()
	{
		$response = array();
		$row = array();
		$response["error"] = TRUE;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['username']) && isset($_REQUEST['key'])
			&& isset($_REQUEST['barcode']))
		{
			if ($_REQUEST['tag'] == 'getproductbybarcode') 
			{
				if (getkey($_REQUEST['username']) == $_REQUEST['key'])
				{
					$cmd = Yii::app()->db->createCommand("select productname
						from tempscan a 
						join product b on b.productid = a.productid
						where a.barcode = '".$_REQUEST['barcode']."'")->queryScalar();
					$response["error"] = FALSE;
					$response["error_msg"] = 'OK';
					$response["productname"] = $cmd;
				}
				else
				{
					$response["error"] = TRUE;
					$response['error_msg'] = getCatalog('youarenotauthorized');
				}
			}
			else
			{
				$response["error"] = TRUE;
				$response['error_msg'] = getCatalog('invalidmethod');
			}
		}
		else
		{
			$response["error"] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
	
	public function actionScanGI()
	{
		$response = array();
		$row = array();
		$response["error"] = TRUE;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['username']) && isset($_REQUEST['key']) 
				&& isset($_REQUEST['soid']) && isset($_REQUEST['barcode']))
		{
			if ($_REQUEST['tag'] == 'scangi') 
			{
				if (getkey($_REQUEST['username']) == $_REQUEST['key'])
				{
					$sql = "select ifnull(count(1),0) 
						from tempscan where barcode = '".$_REQUEST['barcode']."'";
					$i = Yii::app()->db->createCommand($sql)->queryScalar();
					if ($i > 0)
					{
						$sql = "select a.tempscanid 
							from tempscan a 
							join sodetail b on b.sodetailid 
							barcode = '".$_REQUEST['barcode']."'";
						$tempscan = Yii::app()->db->createCommand($sql)->queryRow();
						$sql = "update tempscandetail 
							set soheaderid = ".$_REQUEST['soid'];
						
					}
					else
					{
						$response["error"] = TRUE;
						$response['error_msg'] = getCatalog('invalidbarcode');
					}
				}
				else
				{
					$response["error"] = TRUE;
					$response['error_msg'] = getCatalog('youarenotauthorized');
				}
			}
			else
			{
				$response["error"] = TRUE;
				$response['error_msg'] = getCatalog('invalidmethod');
			}
		}
		else
		{
			$response["error"] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
	
	public function actionGetProdPlan()
	{
		$response = array();
		$row = array();
		$response["error"] = TRUE;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['username']) && isset($_REQUEST['key']))
		{
			if ($_REQUEST['tag'] == 'getprodplan') 
			{
				if (getkey($_REQUEST['username']) == $_REQUEST['key'])
				{
					$cmd = Yii::app()->db->createCommand("select distinct b.productplanno,b.productplanid
						from productplanfg a 
						join productplan b on b.productplanid = a.productplanid 
						where productplanno is not null and a.qty > a.qtyres")->queryAll();
					foreach($cmd as $data)
					{	
						$row[] = array(
							'productplanid'=>$data['productplanid'],
							'productplanno'=>$data['productplanno']
						);
					}
					$response["error"] = FALSE;
					$response["error_msg"] = 'OK';
					$response=array_merge($response,array('rows'=>$row));
				}
				else
				{
					$response["error"] = TRUE;
					$response['error_msg'] = getCatalog('youarenotauthorized');
				}
			}
			else
			{
				$response["error"] = TRUE;
				$response['error_msg'] = getCatalog('invalidmethod');
			}
		}
		else
		{
			$response["error"] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
	
	public function actionScanOutput()
	{
		$response = array();
		$row = array();
		$response["error"] = TRUE;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['username']) && isset($_REQUEST['key']) 
			 && isset($_REQUEST['barcode']))
		{
			if ($_REQUEST['tag'] == 'scanoutput') 
			{
				if (getkey($_REQUEST['username']) == $_REQUEST['key'])
				{
					$sql = "select ifnull(count(1),0) from tempscan where barcode = '".$_REQUEST['barcode']."'";
					$i = Yii::app()->db->createCommand($sql)->queryScalar();
					if ($i > 0)
					{
						$sql = "select ifnull(count(1),0) from tempscan where barcode = '".$_REQUEST['barcode']."' 
							and qtyscan < qtyori";
						$i = Yii::app()->db->createCommand($sql)->queryScalar();
						if ($i > 0)
						{
							$sql = "update tempscan set qtyscan = qtyscan + 1 where barcode = '".$_REQUEST['barcode']."'";
							Yii::app()->db->createCommand($sql)->execute();
							$sql = "select tempscanid,slocid,b.productname,a.productplanfgid,a.productplanid
								from tempscan a 
								join product b on b.productid = a.productid 
								where a.barcode = '".$_REQUEST['barcode']."'";
							$tempid = Yii::app()->db->createCommand($sql)->queryRow();
							$sql = "insert tempscandetail (tempscanid,qtyscan,statusscan,scandate,productplanid,productplanfgid) 
								values (".$tempid['tempscanid'].",1,1,now(),".$tempid['productplanid'].",".$tempid['productplanfgid'].")";
							Yii::app()->db->createCommand($sql)->execute();
							$response["error"] = FALSE;
							$response["error_msg"] = "OK";
							$response["productname"] = $tempid['productname'];
						}
						else
						{
							$response["error"] = TRUE;
							$response['error_msg'] = getCatalog('barcodescanned');
						}
					}
					else
					{
						$response["error"] = TRUE;
						$response['error_msg'] = getCatalog('invalidbarcode');
					}
				}
				else
				{
					$response["error"] = TRUE;
					$response['error_msg'] = getCatalog('youarenotauthorized');
				}
			}
			else
			{
				$response["error"] = TRUE;
				$response['error_msg'] = getCatalog('invalidmethod');
			}
		}
		else
		{
			$response["error"] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
	
	public function actionApproveOutput()
	{
		$response = array();
		$row = array();
		$response["error"] = TRUE;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['username']) && isset($_REQUEST['key']) 
				&& isset($_REQUEST['ppid']))
		{
			if ($_REQUEST['tag'] == 'approveoutput') 
			{
				if (getkey($_REQUEST['username']) == $_REQUEST['key'])
				{
					$sql = "select ifnull(count(1),0) from tempscandetail   
						where productplanid = ".$_REQUEST['ppid']." and statusscan = 1";
					$i = Yii::app()->db->createCommand($sql)->queryScalar();
					if ($i > 0)
					{
						$connection=Yii::app()->db;
						$transaction=$connection->beginTransaction();
						try
						{
							$sql = 'call ApproveScanOutput(:vid,:vcreatedby)';
							$command=$connection->createCommand($sql);
							$command->bindvalue(':vid',$_REQUEST['ppid'],PDO::PARAM_STR);
							$command->bindvalue(':vcreatedby',$_REQUEST['username'],PDO::PARAM_STR);
							$command->execute();
							$transaction->commit();
							$response["error"] = FALSE;
							$response["error_msg"] = "OK";
							$sql = "select productoutputno
								from productoutput 
								where productplanid = ".$_REQUEST['ppid']." and description = 'Hasil Scan' 
								order by productoutputid desc limit 1";
							$opno = Yii::app()->db->createCommand($sql)->queryScalar();
							$response["productoutput"] = $opno;
						}
						catch (Exception $e)
						{
							$transaction->rollback();
							$response["error"] = TRUE;
							$response["error_msg"] = $e->getMessage();
						}
					}
					else
					{
						$response["error"] = TRUE;
						$response['error_msg'] = getCatalog('barcodenotscan');
					}
				}
				else
				{
					$response["error"] = TRUE;
					$response['error_msg'] = getCatalog('youarenotauthorized');
				}
			}
			else
			{
				$response["error"] = TRUE;
				$response['error_msg'] = getCatalog('invalidmethod');
			}
		}
		else
		{
			$response["error"] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
	
	
}