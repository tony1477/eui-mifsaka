<?php
class Filter
{
  public $_var = array();
  private $protectedvar = array('unitofmeasureid', 'productid');

  public function __construct($request)
  {
    $this->_var = $request;
  }

  public function filter()
  {
    $arr = $this->_var;
    // check if $arr in protected Variable
    for ($i = 0; $i < count($arr); $i++) {
      if (in_array($arr[$i], $this->protectedvar)) {
      }
    }
  }

  private function getItems()
  {
    return $this->_var;
  }
}
