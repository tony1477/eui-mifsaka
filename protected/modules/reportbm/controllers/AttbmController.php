<?php
class AttbmController extends Controller {
  public $menuname = 'attbm';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('application.modules.accounting.views.att.index',array());
  }
  
}