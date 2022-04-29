<?php
Yii::import('zii.widgets.CPortlet');
class Form extends CPortlet {
  public $menuname = '';
  public $iswrite = 1;
  public $ispurge = 1;
  public $ispost = 0;
  public $isreject = 0;
  public $isupload = 1;
  public $isdownload = 1;
	public $ispdf = 1;
	public $isxls = 1;
	public $writebuttons = '';
	public $downloadbuttons = '';
	public $otherbuttons = '';
	public $addonscripts = '';
	public $customjs = '';
	public $formtype = 'master'; //master, masterdetail
	public $columns = null; //array columns / column header
	public $searchfield = null; //array search field
	public $addonsearchfield = '';
	public $beginedit = ''; //on begin edit header
	public $addload = '';
	public $rowstyler = '';
	public $loadsuccess = '';
	public $headerform = '';
	public $idfield = '';
	public $urlgetdata = '';
	public $url = '';
	public $wfapp = '';
	public $saveurl = '';
	public $updateurl = '';
	public $destroyurl = '';
	public $uploadurl = '';
	public $approveurl = '';
	public $rejecturl = '';
	public $downpdf = '';
	public $downxls = '';
	public $isupload2 = 0;
	public $upload2text = '';
	public $uploadurl2 = '';
	public $columndetails = null; //array column details
  protected function renderContent() {
		$this->render('form');
	}
}