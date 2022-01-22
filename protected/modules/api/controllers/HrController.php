<?php
class HrController extends Controller
{
	protected $pageTitle = 'API HR';
	public function actionIndex()
	{
		if (isset($_REQUEST['tag'])) {
			switch ($_REQUEST['tag']) {
				case 'selfabs':
					$this->selfabs();
					break;
				default :
					$response['error'] = TRUE;
					$response['error_msg'] = getCatalog('invalidmethod');
					echo json_encode($response);
			}
		} else {
			$this->render('index');
		}	
	}
	public function Selfabs()
  {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key'])
			&& isset($_REQUEST['deviceid'])
			&& isset($_REQUEST['location']))
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$pptt = $_REQUEST['pptt'];
				$location = $_REQUEST['location'];
				$deviceid = $_REQUEST['deviceid'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$sql = "select c.menuvalueid 
					from useraccess a
					join usergroup b on b.useraccessid = a.useraccessid 
					join groupmenuauth c on c.groupaccessid = b.groupaccessid 
					where c.menuauthid = 14 
					and lower(a.username) = lower('".$pptt."') 
				";
				$employeeid = Yii::app()->db->createcommand($sql)->queryScalar();
				$sql = "insert into abstrans (employeeid,datetimeclock,reason,location,recordstatus,deviceid) 
				values (".$employeeid.",now(),'Via Mobile','".$location."',1,'".$deviceid."')";
				Yii::app()->db->createcommand($sql)->execute();
				$sql = "select datetimeclock from abstrans where employeeid = ".$employeeid." order by abstransid desc limit 1";
				$id = Yii::app()->db->createcommand($sql)->queryScalar();
				$response['tgljam'] = $id;
			}
			else 
			{
				$response['error'] = TRUE;
				$response['error_msg'] = getCatalog('youarenotauthorized');
			}
		}
		else 
		{
			$response['error'] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
	
	public function actionUpload()
  {
		if (isset($_REQUEST['pptt'])) {
			$data = file_get_contents('php://input');
			$pptt = $_REQUEST['pptt'];
			$filename = rand(-1000000,10000000);
			$sql = "select c.menuvalueid 
				from useraccess a
				join usergroup b on b.useraccessid = a.useraccessid 
				join groupmenuauth c on c.groupaccessid = b.groupaccessid 
				where c.menuauthid = 14 
				and lower(a.username) = lower('".$pptt."') 
			";
			$employeeid = Yii::app()->db->createcommand($sql)->queryScalar();
			$sql = "select abstransid from abstrans where employeeid = ".$employeeid." order by abstransid desc limit 1";
			$id = Yii::app()->db->createcommand($sql)->queryScalar();
			$sql = "update abstrans set photo = '".$filename."' where abstransid = ".$id;
			Yii::app()->db->createcommand($sql)->execute();
			if (!(file_put_contents(dirname('__FILES__').'/images/employee/'.$filename,$data) === FALSE)) echo "File xfer completed."; // file could be empty, though
			else echo "File xfer failed.";
		}
	}
}