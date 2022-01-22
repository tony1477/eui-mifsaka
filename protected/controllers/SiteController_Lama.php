<?php
class SiteController extends Controller {
  public function actionIndex() {
    if (Yii::app()->user->name !== 'Guest') {
      $this->layout = '//layouts/columnadmin';
      $dependency   = new CDbCacheDependency("select max(useraccessid) from useraccess a");
      $user         = Yii::app()->db->cache(1000, $dependency)->createCommand("select a.useraccessid,b.themename
					from useraccess a
					inner join theme b on b.themeid = a.themeid 
					where username = '" . Yii::app()->user->id . "'")->queryRow();
      if (isset($user)) {
        Yii::app()->params['theme'] = $user['themename'];
      }
      $this->render('index');
    } else {
      $this->actionLogin();
    }
  }
	public function actionGetUserLogin() {
		echo CJSON::encode(array(
			'isError' => false,
			'msg' => Yii::app()->user->id,
			'identityid' => Yii::app()->user->identityid
		));
	}
  public function actionError() {
  }
  public function actionAbout() {
    $this->render('about');
  }
  public function actionSaveProfile() {
    $model = Yii::app()->db->createCommand("select useraccessid,username,password from useraccess where username = '" . $_POST['username'] . "'")->queryRow();
    if ($model['password'] !== $_POST['password']) {
      Yii::app()->db->createCommand("update useraccess set password = md5('" . $_POST['password'] . "') where username = '" . $_POST['username'] . "'")->execute();
			$sql = "CALL inserttranslog('".$_POST['username']."','update','".md5($_POST['password'])."','".$model['password']."','useraccess',".$model['useraccessid'].")";
        $exec = Yii::app()->db->createCommand($sql)->execute();
    }
    $this->GetMessage(false, 'insertsuccess');
  }
  public function actionHome() {		
    $this->render('home');
  }
	public function actionproductionfg() {
    $items = array();
    $cmd   = Yii::app()->db->createCommand()->select('a.productname,sum(t.qty) as qty,t.startdate,t.enddate')->from('productplanfg t')->join('product a', 'a.productid = t.productid')->leftjoin('sodetail b', 'b.sodetailid = t.sodetailid')->join('productplan c', 'c.productplanid = t.productplanid')->where("startdate >= '" . $_GET['start'] . "' and enddate <= '" . $_GET['end'] . "' and c.recordstatus = 3 ")->group('productname')->queryAll();
    
    foreach ($cmd as $data) {
      $items[] = array(
        'title' => $data['productname'] . ' ' . $data['qty'],
        'start' => $data['startdate'],
        'end' => $data['enddate'],
        'constraint' => 'businessHours'
      );
    }
    echo CJSON::encode($items);
  }
  public function actionusertodo() {
    header("Content-Type: application/json");
    $page  = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows  = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort  = isset($_POST['sort']) ? strval($_POST['sort']) : 'usertodoid';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
    $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
    $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
    $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
    $offset = ($page - 1) * $rows;
    $result = array();
    $row    = array();
    $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('usertodo t')->where("username = '" . Yii::app()->user->name . "'")->queryScalar();
    $result['total'] = $cmd;
    $cmd = Yii::app()->db->createCommand()->select('*')->from('usertodo t')->where("username = '" . Yii::app()->user->name . "'")->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'usertodoid' => $data['usertodoid'],
        'username' => $data['username'],
        'tododate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tododate'])),
        'menuname' => $data['menuname'],
        'docno' => $data['docno'],
        'description' => $data['description']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
	public function actionDeviceLogin() {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['pptt']) && isset($_REQUEST['sstt'])) {
			if ($_REQUEST['tag'] == 'login') {
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
				else {
					$response['error'] = TRUE;
					$response['error_msg'] = getCatalog('invaliduser');
				}
			} else {
				$response['error'] = TRUE;
				$response['error_msg'] = getCatalog('invalidmethod');
			}
		}
		else {
			$response['error'] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
	}
  public function actionLogin() {
    $this->layout = '//layouts/columngeneral';
		$model        = new LoginForm;
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
    if (isset($_POST['LoginForm'])) {
      $model->attributes = $_POST['LoginForm'];
      if ($model->validate() && $model->login()) {
				Yii::app()->db->createCommand("update useraccess set identityid = '".$model->identityid."' where username = '" . Yii::app()->user->id . "'")->execute();
        $this->redirect(Yii::app()->user->returnUrl);
      }
    }
    $this->render('login', array(
      'model' => $model
    ));
  }
  public function actionLogout() {
		Yii::app()->db->createCommand("update useraccess set isonline = 0 where username = '" . Yii::app()->user->id . "'")->execute();
    Yii::app()->user->logout();
    $this->redirect(Yii::app()->user->returnUrl);
  }
  public function getOrderYPY() {
    $datas = array();
    $maxso = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appso') as maxso")->queryScalar();
    $cmd   = Yii::app()->db->createCommand("select 
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 1 and recordstatus = " . $maxso . ") as bln1,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 2 and recordstatus = " . $maxso . ") as bln2,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 3 and recordstatus = " . $maxso . ") as bln3,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 4 and recordstatus = " . $maxso . ") as bln4,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 5 and recordstatus = " . $maxso . ") as bln5,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 6 and recordstatus = " . $maxso . ") as bln6,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 7 and recordstatus = " . $maxso . ") as bln7,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 8 and recordstatus = " . $maxso . ") as bln8,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 9 and recordstatus = " . $maxso . ") as bln9,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 10 and recordstatus = " . $maxso . ") as bln10,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 11 and recordstatus = " . $maxso . ") as bln11,
(
select ifnull(sum(qty * price),0) as total
from sodetail a 
inner join soheader b on b.soheaderid = a.soheaderid
where year(b.sodate) = year(now()) and month(b.sodate) = 12 and recordstatus = " . $maxso . ") as bln12")->queryAll();
    foreach ($cmd as $data) {
      $datas[] = $data['bln1'] . ',' . $data['bln2'] . ',' . $data['bln3'] . ',' . $data['bln4'] . ',' . $data['bln5'] . ',' . $data['bln6'] . ',' . $data['bln7'] . ',' . $data['bln8'] . ',' . $data['bln9'] . ',' . $data['bln10'] . ',' . $data['bln11'] . ',' . $data['bln12'];
    }
    return join($datas);
  }
  public function getOrderSalesYPY() {
    $datas = array();
    $maxso = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appso') as maxso")->queryScalar();
    $cmd   = Yii::app()->db->createCommand("select distinct zz.employeeid,zz.fullname,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 1 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln1,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 2 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln2,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 3 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln3,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 4 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln4,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 5 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln5,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 6 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln6,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 7 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln7,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 8 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln8,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 9 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln9,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 10 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln10,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 11 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln11,
			(
			select ifnull(sum(qty * price),0) as total
			from sodetail a 
			inner join soheader b on b.soheaderid = a.soheaderid
			where year(b.sodate) = year(now()) and month(b.sodate) = 12 and recordstatus = " . $maxso . " and b.employeeid = z.employeeid
			) as bln12
			from soheader z
			inner join employee zz on zz.employeeid = z.employeeid
			where z.recordstatus = " . $maxso . " and year(z.sodate) = year(now())
			order by z.employeeid
			")->queryAll();
    foreach ($cmd as $data) {
      $datas[] = "{
				name:'" . $data['fullname'] . "',
				data:[" . $data["bln1"] . "," . $data["bln2"] . "," . $data["bln3"] . "," . $data["bln4"] . "," . $data["bln5"] . "," . $data["bln6"] . "," . $data["bln7"] . "," . $data["bln8"] . "," . $data["bln9"] . "," . $data["bln10"] . "," . $data["bln11"] . "," . $data["bln12"] . "]
				}";
    }
    return join($datas, ',');
  }
	public function actionGetProfile() {
		$username = filter_input(INPUT_POST,'pptt');
		$password = filter_input(INPUT_POST,'sstt');
		$user = null;
		if (Yii::app()->user->id == null) {
			if (($username == null) || ($username == '')) {
				$this->getMessage('error','invaliduser');
			} else 
				if (($password == null) || ($password == '')) {
					$this->getMessage('error','invalidpassword');
			} else {
				$user = Yii::app()->db->createCommand("select useraccessid,username,realname,email,phoneno,employeeid 
					from useraccess 
					where username = '".$username."' 
					and `password` = md5('".$password."')")->queryRow();
			}
		} else {
			$user = Yii::app()->db->createCommand("select useraccessid,username,realname,email,phoneno,employeeid
				from useraccess 
				where username = '".Yii::app()->user->id."'")->queryRow();
		}
		if ($user !== null) {
			if ($user['useraccessid'] == null) {
				$this->getMessage('error','invaliduserpassword');
			} else {
				Yii::app()->db->createCommand("update useraccess set isonline = 1 where username = '".$user['useraccessid']."'")->execute();
				echo CJSON::encode(array(
					'isError'=>'false',
					'msg'=>'',
					'email'=>$user['email'],
					'phoneno'=>$user['phoneno'],
					'realname'=>$user['realname'],
					'useraccessid'=>$user['useraccessid'],
					'employeeid'=>$user['employeeid'],
					'username'=>$user['username'],
				));
				Yii::app()->end();
			}
		}
	}
	public function actionGetUserSuperMenu() { 
		$username = filter_input(INPUT_POST,'pptt');
		$password = filter_input(INPUT_POST,'sstt');
		$result = array();
		$row = array();
		if (($username == null) || ($username == '')) {
				$this->getMessage('error','invaliduser');
			} else 
				if (($password == null) || ($password == '')) {
					$this->getMessage('error','invalidpassword');
			} else {
			$sql = "select d.menuname
				from useraccess a
				inner join usergroup b on b.useraccessid = a.useraccessid
				inner join groupmenu c on c.groupaccessid = b.groupaccessid
				inner join menuaccess d on d.menuaccessid = c.menuaccessid
				inner join modules e on e.moduleid = d.moduleid
				where d.recordstatus = 1 and c.isread = 1 
				and d.parentid is null 
				and username = '".$username."'
				order by d.sortorder asc";
			$cmd = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($cmd as $data)
			{	
				$row[] = array(
					'menuname'=>$data['menuname'],
				);
			}
			$result = array('isError'=>'false','msg'=>'');
			$result=array_merge($result,array('rows'=>$row));
			echo CJSON::encode($result);
		}
	}
	public function actionGetAllMenu() {
		$username = filter_input(INPUT_POST,'pptt');
		$password = filter_input(INPUT_POST,'sstt');
		$result = array();
		$row = array();
		if (($username == null) || ($username == '')) {
				$this->getMessage('error','invaliduser');
			} else 
				if (($password == null) || ($password == '')) {
					$this->getMessage('error','invalidpassword');
			} else {
			$sql = "select d.menuname
				from useraccess a
				inner join usergroup b on b.useraccessid = a.useraccessid
				inner join groupmenu c on c.groupaccessid = b.groupaccessid
				inner join menuaccess d on d.menuaccessid = c.menuaccessid
				inner join modules e on e.moduleid = d.moduleid
				where d.recordstatus = 1 and c.isread = 1 
				and username = '".$username."' 
				order by d.sortorder asc";
			$cmd = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($cmd as $data)
			{	
				$row[] = array(
					'menuname'=>$data['menuname'],
				);
			}
			$result = array('isError'=>'false','msg'=>'',);
			$result=array_merge($result,array('rows'=>$row));
			echo CJSON::encode($result);
		}
	}
	public function actionGetUserMenu() {
		$username = filter_input(INPUT_POST,'pptt');
		$password = filter_input(INPUT_POST,'sstt');
		$menuname = filter_input(INPUT_POST,'spmu');
		$result = array();
		$row = array();
		if (($username == null) || ($username == '')) {
				$this->getMessage('error','invaliduser');
			} else 
				if (($password == null) || ($password == '')) {
					$this->getMessage('error','invalidpassword');
			} else {
			$sql = "select count(1)
				from useraccess a
				inner join usergroup b on b.useraccessid = a.useraccessid
				inner join groupmenu c on c.groupaccessid = b.groupaccessid
				inner join menuaccess d on d.menuaccessid = c.menuaccessid
				inner join modules e on e.moduleid = d.moduleid
				where d.recordstatus = 1 and c.isread = 1 
				and username = '".$username."' 
				and d.parentid is not null 
				and d.menuname = '".$menuname."' 
				order by d.sortorder asc";
			$cmd = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode(array(
					'status'=>'success',
				));
				Yii::app()->end();
		}
	}
}