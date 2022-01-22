<?php

// use vendor\phpoffice\phpspreadsheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller extends CController
{

  public $layout = '//layouts/columngeneral';
  public $menu = array();
  public $breadcrumbs = array();
  protected $iswrite = 'iswrite';
  protected $isread = 'isread';
  protected $ispost = 'ispost';
  protected $isreject = 'isreject';
  protected $isupload = 'isupload';
  protected $isdownload = 'isdownload';
  protected $ispurge = 'ispurge';
  protected $txt = '_help';
  protected $lockedby = "";
  protected $lockeddate = "";
  protected $messages = '';
  protected $connection;
  protected $pdf;
  protected $wfprint = '';
  protected $menuname = '';
  protected $folder = '';
  protected $filename = '';
  protected $phpExcel;
  protected $phpSpreadsheet;
  protected $EAN13;
  protected $dataprint;

  protected function CheckDataLock($menuname, $idvalue)
  {
  }
  protected function InsertLock($menuname, $idvalue)
  {
  }
  protected function DeleteLock($menuname, $idvalue)
  {
  }
  protected function DeleteLockCloseForm($menuname, $postvalue, $idvalue)
  {
  }
  public function actionIndex()
  {
    if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
      $this->redirect(Yii::app()->createUrl('site/login'));
    } else {
      $this->connection = Yii::app()->db;
    }
  }
  public function actionCreate()
  {
    if (checkAccess($this->menuname, $this->iswrite) == false) {
      getmessage(true, 'youarenotauthorized');
    } else {
      if (!Yii::app()->request->isPostRequest)
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
      header("Content-Type: application/json");
    }
  }
  public function actionUpdate()
  {
    if (checkAccess($this->menuname, $this->iswrite) == false) {
      getmessage(true, 'youarenotauthorized');
    } else {
      if (!Yii::app()->request->isPostRequest)
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
      header("Content-Type: application/json");
    }
  }
  public function actionWrite()
  {
    if (checkAccess($this->menuname, $this->iswrite) == false) {
      getmessage(true, 'youarenotauthorized');
    } else {
      if (!Yii::app()->request->isPostRequest)
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
      header("Content-Type: application/json");
    }
  }
  public function actionDelete()
  {
    if (checkAccess($this->menuname, $this->isreject) == false) {
      getmessage(true, 'youarenotauthorized');
    } else {
      if (!Yii::app()->request->isPostRequest)
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
      header("Content-Type: application/json");
    }
  }
  public function actionApprove()
  {
    if (checkAccess($this->menuname, $this->ispost) == false) {
      getmessage(true, 'youarenotauthorized');
    } else {
      if (!Yii::app()->request->isPostRequest)
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
      header("Content-Type: application/json");
    }
  }
  public function actionHistory()
  {
    if (checkAccess($this->menuname, $this->isread) == false) {
      getmessage(true, 'youarenotauthorized');
    } else {
      if (!Yii::app()->request->isPostRequest)
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
      header("Content-Type: application/json");
    }
  }
  public function actionPurge()
  {
    if (checkAccess($this->menuname, $this->ispurge) == false) {
      getmessage(true, 'youarenotauthorized');
    } else {
      if (!Yii::app()->request->isPostRequest)
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
      header("Content-Type: application/json");
    }
  }
  public function actionUpload()
  {
    if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
      $this->redirect(Yii::app()->createUrl('site/login'));
    } else {
      if (checkAccess($this->menuname, $this->isupload) == false) {
        getmessage(true, 'youarenotauthorized');
      }
      if (!Yii::app()->request->isPostRequest)
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
      Yii::import('ext.PHPExcel.XPHPExcel');
      $this->phpExcel = XPHPExcel::createPHPExcel();
    }
  }
  public function actionDownload()
  {
    if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
      $this->redirect(Yii::app()->createUrl('site/login'));
    } else {
      if (checkAccess($this->menuname, $this->isdownload) == false) {
        getmessage(true, 'youarenotauthorized');
      } else {
        $uri = $_SERVER['REQUEST_URI'];
        $str = stripos($uri, "company");
        $str2 = stripos(substr($uri, $str), "&");

        $new2 = substr($uri, $str, $str2);
        $start = stripos($new2, "=");
        $cpy = substr($new2, $start + 1);
        $new3 = substr($new2, 0, $start);
        //echo $cpy;
        $authcomp = getUserObjectValuesarray('company');

        if ($new3 == 'companyname') {
          if ($cpy != '') {
            // companyname/code
            $companyid = GetCompanyid($cpy);
          } else {
            $companyid = getUserObjectValuesarray('company');
            //$cpy=$companyid;
          }
          //echo $cpy;
        } else {
          if ($cpy != '') {
            $companyid = GetCompanyid($cpy, $cpy);
          } else if ($cpy == '') {
            $companyid = getUserObjectValuesarray('company');
            //$cpy=$companyid;
          } else {
            $companyid = getUserObjectValuesarray('company');
          }
        }

        if ($str == '') {
          $companyid = getUserObjectValuesarray('company');
        }

        if (array_intersect($authcomp, $companyid)) {
          require_once("pdf.php");
          $this->connection = Yii::app()->db;
          $this->pdf        = new PDF();
          ob_start();
        } else 
								if (getUserObjectValues('reportgroup') == 1) {
          // get groupmenuatuh for group
          require_once("pdf.php");
          $this->connection = Yii::app()->db;
          $this->pdf        = new PDF();
          ob_start();
        } else if (getUserObjectValues('bspl') == 1) {
          // get groupmenuatuh for group
          require_once("pdf.php");
          $this->connection = Yii::app()->db;
          $this->pdf        = new PDF();
          ob_start();
        } else {
          //var_dump($authcomp);
          //var_dump($companyid);
          getmessage(true, 'youarenotauthorized');
          die();
        }
      }
    }
  }
  public function actionDownload_old()
  {
    if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
      $this->redirect(Yii::app()->createUrl('site/login'));
    } else {
      if (checkAccess($this->menuname, $this->isdownload) == false) {
        getmessage(true, 'youarenotauthorized');
      } else {
        require_once("pdf.php");
        $this->connection = Yii::app()->db;
        $this->pdf        = new PDF();
        ob_start();
      }
    }
  }
  protected function actionDataPrint()
  {
    $this->dataprint = array(
      'j_username' => Yii::app()->params['ReportServerUser'],
      'j_password' => Yii::app()->params['ReportServerPass'],
      'titlereport' => GetCatalog($this->menuname),
      'titlecompany' => Yii::app()->params['title'],
      'titleuser' => getcatalog('printby') . ' ' . Yii::app()->user->id
    );
    return $this->dataprint;
  }
  public function actionDownpdf()
  {
    if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
      $this->redirect(Yii::app()->createUrl('site/login'));
    } else {
      if (checkAccess($this->menuname, $this->isdownload) == false) {
        getmessage(true, 'youarenotauthorized');
      } else {
        $data = $this->actionDataPrint();
        $url = Yii::app()->params['baseUrlReport'] . "/" . $this->menuname . ".pdf?" . http_build_query($this->dataprint);
        $data = GetRemoteData($url);
        header('Cache-Control: public');
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $this->menuname . '.pdf"');
        header('Content-Length: ' . strlen($data));
        echo $data;
      }
    }
  }
  public function actionDownloadbarcode()
  {
    $this->connection = Yii::app()->db;
    if (checkAccess($this->menuname, $this->isdownload) == false) {
      getmessage(true, 'youarenotauthorized');
    } else {
      require_once("tcpdf.php");
      $this->pdf        = new TCPDF("L", "mm", array(100, 180), true, 'UTF-8', false);
      ob_start();
    }
  }

  public function actionSanitizeInput()
  {
    require 'vendor/Filter.php';
  }

  public function actionDownxls1()
  {
    if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) return $this->redirect(Yii::app()->createUrl('site/login'));
    require 'vendor/autoload.php';
    $this->phpSpreadsheet = new Spreadsheet();
    $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
    $filename  = "";
    if (!file_exists(Yii::getPathOfAlias('webroot') . "/protected/modules/" . $this->menuname . ".xlsx")) {
      $filename = Yii::getPathOfAlias('webroot') . "/protected/modules/template.xlsx";
    } else {
      $filename = Yii::getPathOfAlias('webroot') . "/protected/modules/" . $this->menuname . ".xlsx";
    }
    $this->phpSpreadsheet = $objReader->load($filename);

    // require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
    // require(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
  }

  public function savePhpSpreadsheet($phpSpreadsheet)
  {
    $writer = IOFactory::createWriter($phpSpreadsheet, 'Xlsx');

    ob_end_clean();
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $this->menuname . '.xlsx"');
    header('Cache-Control: max-age=0');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $writer->save('php://output');
    unset($phpSpreadsheet);
  }

  public function actionDownxls()
  {
    if ((Yii::app()->user->id == '') || (Yii::app()->user->id == null)) {
      $this->redirect(Yii::app()->createUrl('site/login'));
    } else {
      Yii::import('ext.PHPExcel.XPHPExcel');
      $this->phpExcel = XPHPExcel::createPHPExcel();
      $this->phpExcel->getProperties()->setCreator("Prisma Data Abadi")->setLastModifiedBy("Prisma Data Abadi")->setCompany("Prisma Data Abadi")->setTitle("Capella CMS")->setSubject("Capella CMS")->setDescription("Capella CMS")->setManager("Romy Andre")->setKeywords("capella cms php yii framework")->setCategory("Capella CMS");
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $filename  = "";
      if (!file_exists(Yii::getPathOfAlias('webroot') . "/protected/modules/" . $this->menuname . ".xlsx")) {
        $filename = Yii::getPathOfAlias('webroot') . "/protected/modules/template.xlsx";
      } else {
        $filename = Yii::getPathOfAlias('webroot') . "/protected/modules/" . $this->menuname . ".xlsx";
      }
      $this->phpExcel = $objReader->load($filename);
      $this->connection = Yii::app()->db;
    }
  }
  protected function getFooterXLS($excel)
  {
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    ob_end_clean();
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $this->menuname . '.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter->save('php://output');
    unset($excel);
  }
  public function actionHelp()
  {
    getmessage('success', '');
  }
}
