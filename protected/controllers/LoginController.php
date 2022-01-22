<?php
class LoginController extends Controller
{
	public $layout = '//layouts/columngeneral';
	public function actionIndex()
	{
		$model=new LoginForm;
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			if($model->validate() && $model->login()) {
				$sql = "update useraccess set isonline = 1 where username = '".Yii::app()->user->id."'";
				Yii::app()->db->createCommand($sql)->execute();
				$sql = "select b.themename from useraccess a join theme b on b.themeid = a.themeid where username = '".Yii::app()->user->id."'";
				Yii::app()->theme = Yii::app()->db->createCommand($sql)->queryScalar();
			  $this->render('index');
			} 
		}
		$this->render('login',array('model'=>$model));
	}
}