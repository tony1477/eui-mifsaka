<?php

Yii::import('ext.yii-easyui.widgets.EuiControl');
Yii::import('ext.yii-easyui.widgets.EuiTabPanel');

class EuiTabs extends EuiControl {

	/**
	 * @var boolean When true to set the panel size fit it's parent container.
	 */
	public $fit;

	/**
	 * (non-PHPdoc)
	 * @see EuiWidget::getCssClass()
	 */
	protected function getCssClass()
	{
		return 'easyui-tabs';
	}

	public function init()
	{
		echo CHtml::openTag('div', $this->toOptions())."\n";
	}


	public function run()
	{
		echo CHtml::closeTag('div')."\n";
	}
}

?>