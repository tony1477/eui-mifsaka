<?php

class DefaultController extends Controller
{
	protected $pageTitle = "API";
	public function actionIndex()
	{
		$this->render('index');
	}
}