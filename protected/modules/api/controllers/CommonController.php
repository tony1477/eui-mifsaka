<?php

class CommonController extends Controller
{
	protected $pageTitle = 'API Common';
	
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionGetCustomers() {
		header("Content-Type: application/json");
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
			$sql = "select addressbookid,fullname,currentlimit,creditlimit
				from addressbook 
				where iscustomer = 1 and recordstatus = 1";
			$cmd = Yii::app()->db->createcommand($sql)->queryAll();
				foreach ($cmd as $data) {
					$row[] = array(
						'addressbookid' => $data['addressbookid'],
						'fullname' => $data['fullname'],
						'currentlimit' => $data['currentlimit'],
						'creditlimit' => $data['creditlimit'],
					);
			 }
			$result = array('isError'=>false,'msg'=>'');
			$result = array_merge($result, array(
				'rows' => $row
			));
			echo CJSON::encode($result);
		}
	}
	
	public function actionGetProducts() {
		header("Content-Type: application/json");
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
			$sql = "select a.productid,a.productname,a.barcode,c.slocid,c.sloccode,d.uomcode
from product a
join productplant b on b.productid = a.productid
join sloc c on c.slocid = b.slocid
join unitofmeasure d on d.unitofmeasureid = b.unitofissue
where productname <> '' 
and a.recordstatus = 1 
and c.slocid in 
(
select  a.menuvalueid
				from groupmenuauth a
				inner join menuauth b on b.menuauthid = a.menuauthid
				inner join usergroup c on c.groupaccessid = a.groupaccessid 
				inner join useraccess d on d.useraccessid = c.useraccessid 
				where upper(b.menuobject) = upper('sloc')
				and upper(d.username)=upper('".$username."')
)";
			$cmd = Yii::app()->db->createcommand($sql)->queryAll();
			foreach ($cmd as $data) {
				$row[] = array(
					'productid' => $data['productid'],
					'productname' => $data['productname'],
					'barcode' => $data['barcode'],
					'slocid' => $data['slocid'],
					'sloccode' => $data['sloccode'],
					'uomcode' => $data['uomcode'],
				);
			}
			$result = array('isError'=>false,'msg'=>'');
			$result = array_merge($result, array(
				'rows' => $row
			));
			echo CJSON::encode($result);
		}
	}
	
	public function actionGetPayments() {
		header("Content-Type: application/json");
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
			$sql = "select paymentmethodid,paycode,paydays,paymentname
				from paymentmethod
				where recordstatus = 1";
			$cmd = Yii::app()->db->createcommand($sql)->queryAll();
				foreach ($cmd as $data) {
					$row[] = array(
						'paymentmethodid' => $data['paymentmethodid'],
						'paycode' => $data['paycode'],
						'paydays' => $data['paydays'],
						'paymentname' => $data['paymentname'],
					);
			 }
			$result = array('isError'=>false,'msg'=>'');
			$result = array_merge($result, array(
				'rows' => $row
			));
			echo CJSON::encode($result);
		}
	}
	
}