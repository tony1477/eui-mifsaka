<?php
class GenerateBSCommand extends CConsoleCommand
{
	public function run($args)
	{
		$sql = "call InsertBSLajur(1, now())";
  	Yii::app()->db->createCommand($sql)->execute();
		$sql = "call InsertBSLajur(2, now())";
  	Yii::app()->db->createCommand($sql)->execute();
	}
}
