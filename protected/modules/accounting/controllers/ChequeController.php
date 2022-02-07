<?php
class ChequeController extends Controller {
	public $menuname = 'cheque';
	public function actionIndex() {
		parent::actionIndex();
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	public function search() {
		header("Content-Type: application/json");
		$chequeid      = isset($_POST['chequeid']) ? $_POST['chequeid'] : '';
		$companyid     = isset($_POST['companyid']) ? $_POST['companyid'] : '';
		$tglbayar      = isset($_POST['tglbayar']) ? $_POST['tglbayar'] : '';
		$chequeno      = isset($_POST['chequeno']) ? $_POST['chequeno'] : '';
		$bankid        = isset($_POST['bankid']) ? $_POST['bankid'] : '';
		$plantid        = isset($_POST['plantid']) ? $_POST['plantid'] : '';
		$tglcheque     = isset($_POST['tglcheque']) ? $_POST['tglcheque'] : '';
		$tgltempo      = isset($_POST['tgltempo']) ? $_POST['tgltempo'] : '';
		$tglcair       = isset($_POST['tglcair']) ? $_POST['tglcair'] : '';
		$tgltolak      = isset($_POST['tgltolak']) ? $_POST['tgltolak'] : '';
		$addressbookid = isset($_POST['addressbookid']) ? $_POST['addressbookid'] : '';
		$iscustomer    = isset($_POST['iscustomer']) ? $_POST['iscustomer'] : '';
		$chequeid      = isset($_GET['q']) ? $_GET['q'] : $chequeid;
		$companyid     = isset($_GET['q']) ? $_GET['q'] : $companyid;
		$tglbayar      = isset($_GET['q']) ? $_GET['q'] : $tglbayar;
		$chequeno      = isset($_GET['q']) ? $_GET['q'] : $chequeno;
		$bankid        = isset($_GET['q']) ? $_GET['q'] : $bankid;
		$plantid        = isset($_GET['q']) ? $_GET['q'] : $plantid;
		$tglcheque     = isset($_GET['q']) ? $_GET['q'] : $tglcheque;
		$tgltempo      = isset($_GET['q']) ? $_GET['q'] : $tgltempo;
		$tglcair       = isset($_GET['q']) ? $_GET['q'] : $tglcair;
		$tgltolak      = isset($_GET['q']) ? $_GET['q'] : $tgltolak;
		$addressbookid = isset($_GET['q']) ? $_GET['q'] : $addressbookid;
		$iscustomer    = isset($_GET['q']) ? $_GET['q'] : $iscustomer;
		$page          = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows          = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort          = isset($_POST['sort']) ? strval($_POST['sort']) : 't.chequeid';
		$order         = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$page          = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows          = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort          = isset($_GET['sort']) ? strval($_GET['sort']) : $sort;
		$order         = isset($_GET['order']) ? strval($_GET['order']) : $order;
		$offset        = ($page - 1) * $rows;
		$result        = array();
		$row           = array();
		$cmd = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('cheque t')
			->join('addressbook a', 'a.addressbookid=t.addressbookid')
			->join('currency b', 'b.currencyid=t.currencyid')
			->join('bank c', 'c.bankid=t.bankid')
			->join('company d', 'd.companyid=t.companyid')
			->leftjoin('plant e', 'e.plantid=t.plantid')
			->where("((coalesce(t.chequeid,'') like :chequeid) or (coalesce(t.chequeno,'') like :chequeno) or (coalesce(d.companyname,'') like :companyid) or (coalesce(c.bankname,'') like :bankid) or (coalesce(e.plantcode,'') like :plantcode))
				and t.recordstatus <> 0 and
				t.companyid in (".getUserObjectValues('company').")", array(
		':chequeid' => '%' . $chequeid . '%',
		':chequeno' => '%' . $chequeno . '%',
		':companyid' => '%' . $companyid . '%',
		':bankid' => '%' . $bankid . '%',
		':plantcode' => '%' . $plantid . '%',
		))->queryScalar();
		$result['total'] = $cmd;
		$cmd = Yii::app()->db->createCommand()->select('t.*,a.fullname,b.currencyname,c.bankname,d.companyname, e.plantcode')
			->from('cheque t')
			->join('addressbook a', 'a.addressbookid=t.addressbookid')
			->join('currency b', 'b.currencyid=t.currencyid')
			->join('bank c', 'c.bankid=t.bankid')
			->join('company d', 'd.companyid=t.companyid')
			->leftjoin('plant e', 'e.plantid=t.plantid')
			->where("((coalesce(t.chequeid,'') like :chequeid) or (coalesce(t.chequeno,'') like :chequeno) or (coalesce(d.companyname,'') like :companyid) or (coalesce(c.bankname,'') like :bankid) or (coalesce(e.plantcode,'') like :plantcode))
			and t.recordstatus <> 0 and
			t.companyid in (".getUserObjectValues('company').")", array(
        ':chequeid' => '%' . $chequeid . '%',
        ':chequeno' => '%' . $chequeno . '%',
        ':companyid' => '%' . $companyid . '%',
        ':bankid' => '%' . $bankid . '%',
        ':plantcode' => '%' . $plantid . '%',
        ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
		foreach ($cmd as $data) {
			$row[] = array(
				'chequeid' => $data['chequeid'],
				'companyid' => $data['companyid'],
				'plantid' => $data['plantid'],
				'plantcode' => $data['plantcode'],
				'companyname' => $data['companyname'],
				'tglbayar' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglbayar'])),
				'chequeno' => $data['chequeno'],
				'bankid' => $data['bankid'],
				'bankname' => $data['bankname'],
				'amount' => Yii::app()->format->formatNumber($data['amount']),
				'currencyid' => $data['currencyid'],
				'currencyname' => $data['currencyname'],
				'currencyrate' => Yii::app()->format->formatNumber($data['currencyrate']),
				'tglcheque' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcheque'])),
				'tgltempo' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tgltempo'])),
				'tglcair' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tglcair'])),
				'tgltolak' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['tgltolak'])),
				'addressbookid' => $data['addressbookid'],
				'iscustomer' => $data['iscustomer'],
				'fullname' => $data['fullname'],
				'recordstatus' => $data['recordstatus']
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	public function actionSave() {
		parent::actionWrite();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['isNewRecord']))
			{
				$sql = 'call InsertCheque(:vcompanyid,:vplantid,:vtglbayar,:vchequeno,:vbankid,:vamount,:vcurrencyid,:vcurrencyrate,:vtglcheque,:vtgltempo,:vaddressbookid,:viscustomer,:vtglcair,:vtgltolak,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call UpdateCheque(:vid,:vcompanyid,:vplantid,:vtglbayar,:vchequeno,:vbankid,:vamount,:vcurrencyid,:vcurrencyrate,:vtglcheque,:vtgltempo,:vaddressbookid,:viscustomer,:vtglcair,:vtgltolak,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['chequeid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['chequeid']);
			}
			$command->bindvalue(':vcompanyid',$_POST['companyid'],PDO::PARAM_STR);
			$command->bindvalue(':vplantid',$_POST['plantid'],PDO::PARAM_STR);
			$command->bindvalue(':vtglbayar',date(Yii::app()->params['datetodb'], strtotime($_POST['tglbayar'])),PDO::PARAM_STR);
			$command->bindvalue(':vchequeno',$_POST['chequeno'],PDO::PARAM_STR);
			$command->bindvalue(':vbankid',$_POST['bankid'],PDO::PARAM_STR);
			$command->bindvalue(':vamount',$_POST['amount'],PDO::PARAM_STR);
			$command->bindvalue(':vcurrencyid',$_POST['currencyid'],PDO::PARAM_STR);
			$command->bindvalue(':vcurrencyrate', $_POST['currencyrate'],PDO::PARAM_STR);
			$command->bindvalue(':vtglcheque', date(Yii::app()->params['datetodb'], strtotime($_POST['tglcheque'])), PDO::PARAM_STR);
			$command->bindvalue(':vtgltempo', date(Yii::app()->params['datetodb'], strtotime($_POST['tgltempo'])), PDO::PARAM_STR);
			$command->bindvalue(':vaddressbookid', $_POST['addressbookid'],PDO::PARAM_STR);
			$command->bindvalue(':viscustomer', $_POST['iscustomer'],PDO::PARAM_STR);
			$command->bindvalue(':vtglcair', date(Yii::app()->params['datetodb'], strtotime($_POST['tglcair'])), PDO::PARAM_STR);
			$command->bindvalue(':vtgltolak', date(Yii::app()->params['datetodb'], strtotime($_POST['tgltolak'])), PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();			
			GetMessage(false,'insertsuccess');
		}
		catch (Exception $e) {
			$transaction->rollBack();
			GetMessage(true,$e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		if (isset($_POST['id'])) {
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try {
				$sql = 'call Purgecheque(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$id,PDO::PARAM_STR);
				$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
				GetMessage(false,'insertsuccess');
			}
			catch (Exception $e) {
				$transaction->rollback();
				GetMessage(true,$e->getMessage());
			}
		}
		else {
			GetMessage(true,'chooseone');
		}
	}
  public function actionApprove() {
    parent::actionApprove();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Approvecheque(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
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
  /*public function actionDelete() {
    parent::actionDelete();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Deletecheque(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        foreach ($id as $ids) {
          $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
          $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
          $command->execute();
        }
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
  */
	public function actionDownPDF() {
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select `t`.*, `a`.`fullname`, `b`.`currencyname`, `c`.`bankname`, `d`.`companyname`
            from `cheque` `t`
            join `addressbook` `a` ON a.addressbookid=t.addressbookid
            join `currency` `b` ON b.currencyid=t.currencyid
            join `bank` `c` ON c.bankid=t.bankid
            join `company` `d` ON d.companyid=t.companyid ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where t.chequeid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by chequeid desc ";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
		$this->pdf->title=GetCatalog('cheque');
		$this->pdf->AddPage('P',array(350,250));
		$this->pdf->setFont('Arial','B',10);
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		$this->pdf->colheader = array(GetCatalog('chequeid'),
																	GetCatalog('companyname'),
																	GetCatalog('tglbayar'),
																	GetCatalog('chequeno'),
																	GetCatalog('bankname'),
																	GetCatalog('chequeamount'),
																	GetCatalog('currency'),
																	GetCatalog('ratevalue'),
																	GetCatalog('tglcheque'),
																	GetCatalog('tgltempo'),
																	GetCatalog('customervendor'),
																	GetCatalog('tglcair'),
																	GetCatalog('tgltolak'),
																	GetCatalog('recordstatus'));
		$this->pdf->setwidths(array(10,40,25,25,25,35,25,25,25,25,25,25,25,25));
		$this->pdf->Rowheader();
		$this->pdf->setFont('Arial','',10);
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L');		
		foreach($dataReader as $row1) {
		  $this->pdf->row(array($row1['chequeid'],$row1['companyname'],$row1['tglbayar'],$row1['chequeno'],$row1['bankname'],$row1['amount'],$row1['currencyname'],$row1['currencyrate'],$row1['tglcheque'],$row1['tgltempo'],$row1['fullname'],$row1['tglcair'],$row1['tgltolak'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
	public function actionDownxls() {
		$this->menuname='cheque';
		parent::actionDownxls();
		$sql = "select chequeid,productname,productpic,
						case when isstock = 1 then 'Yes' else 'No' end as isstock,
						barcode,
						case when recordstatus = 1 then 'Yes' else 'No' end as recordstatus
						from product a ";
		if ($_GET['id'] !== '') 
		{
				$sql = $sql . " where a.productid in (".$_GET['id'].")";
		}
		$sql = $sql . " order by productname asc ";
		$dataReader=Yii::app()->db->createCommand($sql)->queryAll();	
		$i=3;
		foreach($dataReader as $row1) {
			$this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0,$i,$row1['productid'])
				->setCellValueByColumnAndRow(1,$i,$row1['productname'])							
				->setCellValueByColumnAndRow(2,$i,$row1['productpic'])
				->setCellValueByColumnAndRow(3,$i,$row1['isstock'])
				->setCellValueByColumnAndRow(4,$i,$row1['barcode'])
				->setCellValueByColumnAndRow(5,$i,$row1['recordstatus']);
			$i++;
		}
		$this->getFooterXLS($this->phpExcel);
	}
}