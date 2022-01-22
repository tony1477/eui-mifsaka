<?php
class SystemController extends Controller
{
	protected $pageTitle = 'API System';
	public function actionIndex()
	{
		if (isset($_REQUEST['tag'])) {
			switch ($_REQUEST['tag']) {
				case 'login':
					$this->login();
					break;
				case 'logout':
					$this->logout();
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
	public function Login()
	{
		$response['error'] = true;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['pptt']) && isset($_REQUEST['sstt']))
		{
			$cmd = Yii::app()->db->createCommand("select ifnull(count(1),0) as jumlah from useraccess where lower(username) = '".$_REQUEST['pptt']."'
			and `password` = md5('".$_REQUEST['sstt']."') ")->queryRow();
			if ($cmd['jumlah'] > 0)
			{
				$cmd = Yii::app()->db->createCommand("select username,realname,authkey from useraccess where lower (username) = '".$_REQUEST['pptt']."'
				and `password` = md5('".$_REQUEST['sstt']."')")->queryRow();
				$response['error'] = false;
				$response['error_msg'] = getCatalog('welcomeaboard');
				$response['realname'] = $cmd['realname'];
				if (($cmd['authkey'] == null) || ($cmd['authkey'] == '')) 
				{
					$key = md5(uniqid(rand(), true));
					$response['key'] = $key;
					Yii::app()->getDb()->createCommand("update useraccess set isonline = 1, authkey = '".$key."' 
						where lower (username) = lower('".$_REQUEST['pptt']."')")->execute();
				}
				else
				{
					$response['key'] = $cmd['authkey'];
				}
				$cmd = Yii::app()->db->createCommand("select menuname,description 
					from useraccess a
					inner join usergroup b on b.useraccessid = a.useraccessid 
					inner join groupmenu c on c.groupaccessid = b.groupaccessid 
					inner join menuaccess d on d.menuaccessid = c.menuaccessid 
					where c.isread = 1 and lower(a.username) = '".$_REQUEST['pptt']."'")->queryAll();
				foreach($cmd as $data)
				{	
					$row[] = array(
						'menuname'=>$data['menuname'],
						'description'=>$data['description']
					);
				}
				$response=array_merge($response,array('rows'=>$row));
			}
			else
			{
				$response['error'] = TRUE;
				$response['error_msg'] = getCatalog('invalidusersstt');
			}
		}
		else
		{
			$response['error'] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
	public function actionLogout()
	{
		$response['error'] = true;
		if (isset($_REQUEST['tag']) && (isset($_REQUEST['username'])) && (isset($_REQUEST['key']))) 
		{
			if (getkey($_REQUEST['username']) == $_REQUEST['key'])
			{
				Yii::app()->getDb()->createCommand("update useraccess set isonline = 0, authkey = null 
					where username = '".$_REQUEST['username']."'")->execute();
				$response['error'] = false;
				$response['error_msg'] = 'OK';
			}
			else
			{
				$response['error'] = TRUE;
				$response['error_msg'] = getCatalog('alreadylogout');
			}
		}
		else
		{
			$response['error'] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
}