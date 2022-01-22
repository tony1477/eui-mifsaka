<?php
class BalancesheetbmController extends Controller {
  public $menuname = 'balancesheetbm';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('application.modules.accounting.views.balancesheet.index',array());
  }
  
}