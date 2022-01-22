<?php
class ProfitlossbmController extends Controller {
  public $menuname = 'profitlossbm';
  public function actionIndex() {
		parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('application.modules.accounting.views.profitloss.index',array());
  }
  
}