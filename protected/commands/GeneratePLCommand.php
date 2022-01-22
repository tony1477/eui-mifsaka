<?php
class GeneratePLCommand extends CConsoleCommand
{
	public function run($args)
	{
		$sql = "call InsertPLLajur(1, now())";
  	Yii::app()->db->createCommand($sql)->execute();
		$sql = "call InsertPLLajur(2, now())";
  	Yii::app()->db->createCommand($sql)->execute();
	}
}
