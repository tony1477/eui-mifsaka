<?php
require_once('tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    protected $print_header = true;
    protected $print_footer = true;

    public $companyname,$docdate,$revisionno,$docno=null;
    //public $docdate = null;
    //public $revisionno=null;


    //Page header
    public function Header($companyname='ANUGERAH KARYA ASLINDO') {
        // Logo

        if($this->docdate !== null) {
          $month = date('m',strtotime($this->docdate));
          $year = date('Y',strtotime($this->docdate));
        }
        else {
          $month = '00';
          $year = '1970';
        }
        if($this->companyname !== null) $companyname = $this->companyname;
        $this->Image('images/aka-group.png', 20, 15, 25, 26, 'PNG', '', '', true, 150, '', false, false, 1, false, false, false);

        // -- courier, dejavusans, freemono, freesans
        $this->SetFont('freesans', 'B', 11);
        //function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
        $this->MultiCell(85, 8, $companyname, 1, 'C', 0, 0, 45, 15, true,0,false,true,8,'M',false);
        $this->SetFont('freesans', '', 9);
        $this->MultiCell(20, 8, ' No. Dok.', 1, '', 0, 0, 130, 15, true,0,false,true,8,'M',false);
        $this->MultiCell(40, 8, ' '. $this->docno."/003/006/HCA/{$month}/{$year}", 1, '', 0, 1, 150, 15, true,0,false,true,8,'M',false);

        $this->SetFont('freesans', 'B', 11);
        $this->MultiCell(85, 10, 'HCA', 1, 'C', 0, 0, 45, 23, true,0,false,true,10,'M',false);
        $this->SetFont('freesans', '', 9);
        $this->MultiCell(20, 5, ' Rev. ', 1, '', 0, 0, 130, 23, true,0,false,true,5,'M',false);
        $this->MultiCell(40, 5, ' '.$this->revisionno, 1, '', 0, 0, 150, 23, true,0,false,true,5,'M',false);
        $this->MultiCell(20, 5, ' Tanggal', 1, '', 0, 2, 130, 28, true,0,false,true,5,'M',false);
        $this->MultiCell(40, 5, ' '.date(Yii::app()->params['dateviewfromdb'],strtotime($this->docdate)), 1, '', 0, 2, 150, 28, true,0,false,true,5,'M',false);

        $this->SetFont('freesans', 'B', 11);
        //function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
        $this->MultiCell(85, 8, 'INTERNAL MEMO', 1, 'C', 0, 0, 45, 33, true,0,false,true,8,'M',false);
        $this->SetFont('freesans', '', 9);
        $this->MultiCell(20, 8, ' Halaman', 1, '', 0, 0, 130, 33, true,0,false,true,8,'M',false);
        $this->MultiCell(40, 8,  ' '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 1, '', 0, 1, 150, 33, true,0,false,true,8,'M',false);
    }

     public function Header11() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-20);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Page number
        $this->Cell(0, 5, 'FRM-GA-003-006', 0, 1, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 5, 'Rev 00. 01 Januari 2018', 0, 2, 'L', 0, '', 0, false, 'T', 'M');
    }
}
class InternmemoController extends Controller
{
  public $menuname = 'internmemo';
  public function actionIndex()
  {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  } 
	public function actionIndexcombo()
  {
    if (isset($_GET['grid']))
      echo $this->searchcombo();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexcontent()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchcontent();
    else
      $this->renderPartial('index', array());
  }
  public function actionIndexdetail()
  {
    if (isset($_GET['grid']))
      echo $this->actionSearchdetail();
    else
      $this->renderPartial('index', array());
  }
  
  public function actionGetData()
  {
    if (isset($_GET['id'])) {
    } else {
			$dadate              = new DateTime('now');
			$sql = "insert into internmemo (docdate,recordstatus) values ('".$dadate->format('Y-m-d')."',1)";
      $model = Yii::app()->db->createCommand($sql)->execute();
      $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
			echo CJSON::encode(array(
				'internmemoid' => $id
			));
    }
  }
  
  public function searchcombo()
  {
    header("Content-Type: application/json");
    $internmemoid   = isset($_GET['q']) ? $_GET['q'] : '';
    $docno   = isset($_GET['q']) ? $_GET['q'] : '';
    $to     = isset($_GET['q']) ? $_GET['q'] : '';
    $page            = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
    $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : 't.internmemoid';
    $order           = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
		$connection			 = Yii::app()->db;
		$from = '
			from internmemo t 
			left join soheader a on a.soheaderid = t.soheaderid
            join company b on b.companyid = t.companyid ';
		$where = "
			where ((coalesce(to,'') like '%".$to."%') or (internmemoid like '%".$internmemoid."%') or (docno like '%".$docno."%')) 
				and t.docdate <= curdate() and t.recordstatus = 3 and t.companyid in (".getUserObjectValues('company').") ";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = 'select t.internmemoid,t.docno,t.to,b.companyname '.$from.' '.$where;
    $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'internmemoid' => $data['internmemoid'],
        'docno' => $data['docno'],
        'to' => $data['to'],
        'companyname' => $data['companyname']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    echo CJSON::encode($result);
  }
  public function search()
  {
    header("Content-Type: application/json");
    $internmemoid    = isset($_POST['internmemoid']) ? $_POST['internmemoid'] : '';
    $company         = isset($_POST['company']) ? $_POST['company'] : '';
    $subject         = isset($_POST['subject']) ? $_POST['subject'] : '';
    $docno           = isset($_POST['docno']) ? $_POST['docno'] : '';
    $revisionno      = isset($_POST['revisionno']) ? $_POST['revisionno'] : '';
    $docdate         = isset($_POST['docdate']) ? $_POST['docdate'] : '';
    $interfrom       = isset($_POST['from']) ? $_POST['from'] : '';
    $to              = isset($_POST['to']) ? $_POST['to'] : '';
    $cc              = isset($_POST['cc']) ? $_POST['cc'] : '';
    $content         = isset($_POST['content']) ? $_POST['content'] : '';
    $textapproveby   = isset($_POST['textapproveby']) ? $_POST['textapproveby'] : '';
    $appbyperson     = isset($_POST['appbyperson']) ? $_POST['appbyperson'] : '';
    $internmemoid    = isset($_GET['q']) ? $_GET['q'] : $internmemoid;
    $company         = isset($_GET['q']) ? $_GET['q'] : $company;
    $subject         = isset($_GET['q']) ? $_GET['q'] : $subject;
    $docno           = isset($_GET['q']) ? $_GET['q'] : $docno;
    $revisionno      = isset($_GET['q']) ? $_GET['q'] : $revisionno;
    $docdate         = isset($_GET['q']) ? $_GET['q'] : $docdate;
    $interfrom       = isset($_GET['q']) ? $_GET['q'] : $interfrom;
    $to              = isset($_GET['q']) ? $_GET['q'] : $to;
    $cc              = isset($_GET['q']) ? $_GET['q'] : $cc;
    $content         = isset($_GET['q']) ? $_GET['q'] : $content;
    $textapproveby   = isset($_GET['q']) ? $_GET['q'] : $textapproveby;
    $appbyperson     = isset($_GET['q']) ? $_GET['q'] : $appbyperson;
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'internmemoid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $wherecontent='';
    $wheretextapproveby = '';
    $count = 0;

		$maxstat = Yii::app()->db->createCommand("select getwfmaxstatbywfname('appprodplan')")->queryScalar();
		$connection		= Yii::app()->db;
		$from = ' 
			from internmemo t 
			left join company a on a.companyid = t.companyid ';
		$where = "
			where (coalesce(t.revisionno,'') like '%".$revisionno."%') and (coalesce(a.companyname,'') like '%".$company."%') 
				and (coalesce(t.docno,'') like '%".$docno."%') and (coalesce(t.docdate,'') like '%".$docdate."%') 
				and (t.internmemoid like '%".$internmemoid."%') 
				and (coalesce(t.cc,'') like '%".$cc."%') and (coalesce(t.from,'') like '%".$interfrom."%') and (coalesce(t.to,'') like '%".$to."%') 
				and t.recordstatus in (".getUserRecordStatus('listintmemo').")
				-- and t.recordstatus < {$maxstat}
        and t.companyid in (".getUserObjectWfValues('company','appintmemo').")
        ";
		$sqlcount = ' select count(1) as total '.$from.' '.$where;
		$sql = "
			select t.*, a.companyname ".$from.' '.$where;
		$result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'internmemoid' => $data['internmemoid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'subject' => $data['subject'],
        'docno' => $data['docno'],
        'revisionno' => $data['revisionno'],
        'docdate' => date(Yii::app()->params["dateviewfromdb"], strtotime($data['docdate'])),
        'from' => $data['from'],
        'to' => $data['to'],
        'cc' => $data['cc'],
        'content' => htmlspecialchars_decode($data['content']),
        'textapproveby' => $data['textapproveby'],
        'appbyperson' => $data['appbyperson'],
        'recordstatus' => $data['recordstatus'],
        'recordstatusinternmemo' => $data['statusname'],
        'count'=> $count
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function actionSearchcontent()
  {
    header("Content-Type: application/json");
    $id = 0;
    if (isset($_POST['id'])) {
      $id = $_POST['id'];
    } else if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $result          = array();
    $row             = array();
    $footer          = array();
    $text = $app   = null;
    $person = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('internmemo t')->where('t.internmemoid = :internmemoid', array(
      ':internmemoid' => $id
    ))->queryRow();
    $result['total'] = $cmd['total'];
    $cmd             = Yii::app()->db->createCommand()->select('t.internmemoid, t.content, t.textapproveby, t.appbyperson')->from('internmemo t')->where('t.internmemoid = :internmemoid', array(
      ':internmemoid' => $id
    ))->queryAll();
    foreach ($cmd as $data) {
      $exp = explode(',',$data['textapproveby']);
      if($exp!='') $count = count($exp); 
      
      $text = explode(',',$data['textapproveby']);
      $app = explode(',',$data['appbyperson']);

      $row[] = array(
        'internmemoid' => $data['internmemoid'],
        'content'=> htmlspecialchars_decode($data['content']),
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));

    if($count>0) {
      for($i=0; $i<$count; $i++)
      //$person[$i] = Yii::app()->db->createCommand("select realname from useraccess where recordstatus=1 and useraccessid = 106")->queryScalar();

      $footer[] = array(
        'internmemoid' => $text[$i],
        'content' => Yii::app()->db->createCommand('select realname from useraccess where recordstatus=1 and useraccessid='.$app[$i])->queryScalar(),
      );
		}
    $result   = array_merge($result, array(
      'footer' => $footer
    ));

    echo CJSON::encode($result);
  }
  public function actionSearchttd()
  {
    header("Content-Type: application/json");
    $id              = 0;
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } else if (isset($_POST['id'])) {
      $id = $_POST['id'];
    }
    $result          = array();
    $row             = array();
    $data = Yii::app()->db->createCommand("select textapproveby, appbyperson from internmemo where internmemoid = ".$id)->queryRow();

    $appbyperson = explode(',',$data['appbyperson']);
    $textapproveby = explode(',',$data['textapproveby']);

    $result['total'] = 0;
    if(!empty($textapproveby)) $result['total'] = count($textapproveby);
    
    for($i=0; $i<count($appbyperson); $i++) {
      $realname = Yii::app()->db->createCommand("select realname from useraccess where useraccessid = ".$appbyperson[$i])->queryScalar();
      $row[] = array(
        'internmemoid' => $id,
        'textapproveby' => $textapproveby[$i],
        'appbyperson' => $appbyperson[$i],
        'realname' => $realname,
        'number' => $i
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    ;
    echo CJSON::encode($result);
  }
  public function actionSave()
  {
    parent::actionWrite();
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if ($_POST['internmemoid']=='') {
        $sql     = 'call Insertinternmemo(:vcompanyid,:vsubject,:vrevisionno,:vdocdate,:vfrom,:vto,:vcc,:vcontent,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateinternmemo(:vid,:vcompanyid,:vsubject,:vrevisionno,:vdocdate,:vfrom,:vto,:vcc,:vcontent,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $_POST['internmemoid'], PDO::PARAM_STR);
        //$this->DeleteLock($this->menuname, $_POST['internmemoid']); */
      }
      $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_STR);
      $command->bindvalue(':vsubject', $_POST['subject'], PDO::PARAM_STR);
      $command->bindvalue(':vrevisionno', $_POST['revisionno'], PDO::PARAM_STR);
      $command->bindvalue(':vdocdate', date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])), PDO::PARAM_STR);
      $command->bindvalue(':vfrom', $_POST['from'], PDO::PARAM_STR);
      $command->bindvalue(':vto', $_POST['to'], PDO::PARAM_STR);
      $command->bindvalue(':vcc', $_POST['cc'], PDO::PARAM_STR);
      $command->bindvalue(':vcontent', htmlspecialchars($_POST['content']), PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(true, 'insertsuccess',1);
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(false, $e->getMessage());
    }
  }
  public function actionSavettd()
  {
    header("Content-Type: application/json");
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      if (isset($_POST['isNewRecord']) && $_POST['isNewRecord'] == true) {
        $sql     = 'call InsertTTDIntermemo(:vid,:vtextapproveby,:vappbyperson,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call UpdateTTDInternmemo(:vid,:vtextapproveby,:vappbyperson,:vnumber,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vnumber', $_POST['number'], PDO::PARAM_STR);
        //$this->DeleteLock($this->menuname, $_POST['internmemodetailid']);
      }
      $command->bindvalue(':vid', $_POST['internmemoid'], PDO::PARAM_STR);
      $command->bindvalue(':vtextapproveby', $_POST['textapproveby'], PDO::PARAM_STR);
      $command->bindvalue(':vappbyperson', $_POST['appbyperson'], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false, 'insertsuccess');
    }
    catch (Exception $e) {
      $transaction->rollBack();
      GetMessage(true, $e->getMessage());
    }
  }
  public function actionDelete()
  {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call RejectInternmemo(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionApprove()
  {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approveinternmemo(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionPurge()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinternmemo(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionPurgedetailfg()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinternmemofg(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(true, 'insertsuccess', 1);
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(false, $e->getMessage(), 1);
      }
    } else {
      GetMessage(false, 'chooseone', 1);
    }
  }
  public function actionPurgedetail()
  {
    header("Content-Type: application/json");
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeinternmemodetail(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, 'insertsuccess');
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  
  public function actionDownPDF()
  {
    $this->pdf = new MYPDF("P", "mm", array(210,297) , true, 'UTF-8', false);
    //$this->pdf->print_header = true;
    //$this->pdf->print_footer = true;
    $header = Yii::app()->db->createCommand("select companyname, docno, ifnull(revisionno,0) as revisionno, docdate, `from`, `to`, `cc`
    from internmemo a
    join company b on b.companyid = a.companyid
    where b.companyid in(".getUserObjectValues('company').") and a.internmemoid = ".$_GET['id'])->queryRow();

    $this->pdf->companyname = $header['companyname'];
    $this->pdf->docdate = $header['docdate'];
    $this->pdf->revisionno = $header['revisionno'];
    $this->pdf->docno = $header['docno'];

    $this->pdf->SetCreator(PDF_CREATOR);
    $this->pdf->SetAuthor('PT. Anugerah Karya Group');
    $this->pdf->SetTitle('Internal Memo ');

    // set default header data
    $this->pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Internal Memo ', PDF_HEADER_STRING);

    // set header and footer fonts
    //$this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    //$this->pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    //$this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $this->pdf->SetMargins(PDF_MARGIN_LEFT, 50, 15);
    $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $this->pdf->SetAutoPageBreak(TRUE, 20);

    // set image scale factor
    $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $this->pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set font
    
    $this->pdf->SetFont('helvetica', '', 10);
    $this->pdf->AddPage();
    
    //$this->pdf->MultiCell(40, 10, 'Tanggal', 1, '', 0, 2, 135, 26, true,0,false,true,10,'M',false);

    $this->pdf->MultiCell(85, 6, ' Dari ', 1, '', 0, 0, 20, 50, true,0,false,true,6,'M',false);
    $this->pdf->MultiCell(85, 6, $header['from'], 1, '', 0, 1, 105, 50, true,0,false,true,6,'M',false);
    $this->pdf->MultiCell(85, 6, ' Kepada ', 1, '', 0, 0, 20, 56, true,0,false,true,6,'M',false);
    $this->pdf->MultiCell(85, 6, $header['to'], 1, '', 0, 1, 105, 56, true,0,false,true,6,'M',false);
    $this->pdf->MultiCell(85, 6, ' CC ', 1, '', 0, 0, 20, 62, true,0,false,true,6,'M',false);
    $this->pdf->MultiCell(85, 6, $header['cc'], 1, '', 0, 1, 105, 62, true,0,false,true,6,'M',false);

    $y = $this->pdf->getY();
    $x = 20;
    $width = 170;

    $fn = 'htmlspecialchars_decode';
   
    
    $id = $_GET['id'];
		$sql = "select * from internmemo where internmemoid = ".$id;
    $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
    foreach($dataReader as $row) {
      //$this->pdf->writeHTMLCell($width,0, $x, $y, $html=htmlspecialchars_decode($row['content']),1,1,false,true,'',true);
    }
    $html = <<<EOF
    <!-- EXAMPLE OF CSS STYLE -->
    <style>
      
      p.first {
          color: #003300;
          font-family: helvetica;
          font-size: 12pt;
      }
      p.first span {
          color: #006600;
          font-style: italic;
      }
      p#second {
          color: rgb(00,63,127);
          font-family: times;
          font-size: 12pt;
          text-align: justify;
      }
      p#second > span {
          background-color: #FFFFAA;
      }
      td {
          border: 2px solid #000;
      }
      td.second {
          border: 2px dashed green;
      }
      div.test {
          color: #CC0000;
          background-color: #FFFF66;
          font-family: helvetica;
          font-size: 10pt;
          border-style: solid solid solid solid;
          border-width: 2px 2px 2px 2px;
          border-color: green #FF00FF blue red;
          text-align: center;
      }
      .lowercase {
          text-transform: lowercase;
      }
      .uppercase {
          text-transform: uppercase;
      }
      .capitalize {
          text-transform: capitalize;
      }
    </style>
    {$fn($row['content'])}
    EOF;
    $this->pdf->writeHTMLCell($width,0, $x, $y, $html,1,1,false,true,'',true);
    $this->pdf->setY($this->pdf->getY()+10);

    //$this->pdf->writeHTML($style, true, false, true, false, '');
    //$this->pdf->writeHTML($html, true, false, true, false, '');
    $y = $this->pdf->getY();
    $count = 1;
    $exp = explode(',',$row['textapproveby']);
    $app = explode(',',$row['appbyperson']);
    if(is_array($exp)) $count = count($exp);
    $x=20;
    $posisiimg=0;
    $img='';
    for($i=0; $i<$count; $i++) {
      $person = Yii::app()->db->createCommand('select realname from useraccess where recordstatus=1 and useraccessid = '.$app[$i])->queryScalar();
      $sign = Yii::app()->db->createCommand('select username,signature from useraccess where recordstatus=1 and useraccessid = '.$app[$i])->queryRow();
      if($sign['signature']==''){
        $img='images/martonifirman.jpg';
      }
      else {
        $img = 'images/signature/'.$sign['username'].'.jpg';
      }
      $each = 170/$count;
      $next = ($each*$i)+20;
      $posisiimg=$next+(($each)/$count);
      //$this->pdf->Write($each, 15, $exp[$i], 0, '', 0, 0, $next, $y, true,0,false,true,0,'M',false);
      //$this->pdf->Write(0, $exp[$i], '',false, 'L',false,0,false,false,0,0,'');
      //$this->pdf->Image('images/aka-group.png', $x, $y+3, 22, 21, 'PNG', '', '', false, 150, '', false, false, 0, false, false, false);
      
      //$this->pdf->MultiCell($each, 35, $exp[$i], 1, 'C', 0, 2, $next, $y, true,0,false,true,0,'M',false);
      $this->pdf->MultiCell($each, 35, $exp[$i], 1, 'C', 0, 2, $next, $y, true,0,false,true,0,'M',false);
      //$this->pdf->MultiCell($each, 35, 'Tulisan', 0, 'C', 0, 2, $x, $y+5, true,0,false,true,0,'M',false);
      $this->pdf->Image($img, $posisiimg, $y+3, 22, 21, 'JPG', '', '', false, 150, '', false, false, false, false, false, false);
      //this->pdf->Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
      $this->pdf->MultiCell($each, 10, $person, 0, 'C', 0, 2, $next, $y+25, true,0,false,true,0,'M',false);
      $x=$x+$each;

    }
    
    $this->pdf->Output();
  }
  /*
  public function actionDownxls()
  {
    parent::actionDownload();
    $sql = "select a.*,b.revisionno 
      		from internmemo a
			left join soheader b on b.soheaderid = a.soheaderid	";
    if ($_GET['id'] !== '') {
      $sql = $sql . "where a.internmemoid in (" . $_GET['id'] . ")";
    }
    $command    = $this->connection->createCommand($sql);
    $dataReader = $command->queryAll();
    $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
    $i          = 1;
    $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('internmemoversion'))->setCellValueByColumnAndRow(1, 1, GetCatalog('productid'))->setCellValueByColumnAndRow(2, 1, GetCatalog('qty'))->setCellValueByColumnAndRow(3, 1, GetCatalog('uomid'))->setCellValueByColumnAndRow(4, 1, GetCatalog('docdate'))->setCellValueByColumnAndRow(5, 1, GetCatalog('to'))->setCellValueByColumnAndRow(6, 1, GetCatalog('recordstatus'));
    foreach ($dataReader as $row1) {
      $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['internmemoversion'])->setCellValueByColumnAndRow(1, $i + 1, $row1['productid'])->setCellValueByColumnAndRow(2, $i + 1, $row1['qty'])->setCellValueByColumnAndRow(3, $i + 1, $row1['uomid'])->setCellValueByColumnAndRow(4, $i + 1, $row1['docdate'])->setCellValueByColumnAndRow(5, $i + 1, $row1['to'])->setCellValueByColumnAndRow(6, $i + 1, $row1['recordstatus']);
      $i += 1;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="internmemo.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    unset($excel);
  }*/
}