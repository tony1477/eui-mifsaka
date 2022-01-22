<?php
class WebUser extends CWebUser {
  private $_model;
	function getIdentityid() {
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select identityid from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
	}
  function getRealName(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select realname from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
  function getEmail(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select email from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
  function getPhoneno(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select phoneno from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
  function getLanguageid(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select languageid from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
  function getLanguagename(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select languagename from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
  function getThemeid(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select themeid from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
  function getThemename(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select themename from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
  function getIsonline(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select isonline from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
  function getAuthkey(){
    $dependency = new CDbCacheDependency("select updatedate from useraccess where username = '".Yii::app()->user->id."'");
		$sql = "select authkey from useraccess where username = '".Yii::app()->user->id."'";
    return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
  }
}