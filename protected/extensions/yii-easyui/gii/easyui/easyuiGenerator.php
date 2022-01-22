<?php

Yii::import('gii.generators.crud.CrudGenerator');

class easyuiGenerator extends CrudGenerator
{
	public $codeModel = 'ext.yii-easyui.gii.easyui.easyuiCode';
}