<?php
class LoginForm extends CFormModel {
	public $username;
	public $password;
	public $rememberMe;
	public $identityid;
	private $_identity;
	public function rules() {
		return array(
			array('username, password, identityid', 'required'),
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
		);
	}
	public function attributeLabels() {
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}
 	public function authenticate($attribute,$params) {
		if(!$this->hasErrors()) {
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->identityid = $this->identityid; 
			if(!$this->_identity->authenticate()) {
				$this->addError('password','Incorrect username or password.');
			} 
		}
	}
	public function login() {
		if($this->_identity===null) {
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->identityid = $this->identityid;
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE) {
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}