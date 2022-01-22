<?php
Yii::import('zii.widgets.CPortlet');
class Button extends CPortlet {
  public $menuname = '';
  public $iswrite = true;
  public $ispurge = true;
  public $ispost = false;
  public $isreject = true;
  public $isupload = true;
  public $isdownload = true;
	public $formtype = ''; //master, master-detail master part, 2: master-detail detail part
	public $uploadurl = '';
  protected function renderContent() {
		$this->render('button');
	}
}