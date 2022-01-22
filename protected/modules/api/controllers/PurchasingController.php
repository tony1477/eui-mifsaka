<?php
class PurchasingController extends Controller
{
	protected $pageTitle = 'API Purchasing';
	public function actionIndex()
	{
		if (isset($_REQUEST['tag'])) {
			switch ($_REQUEST['tag']) {
				case 'getuserpotrading':
					$this->getuserpotrading();
					break;
				case 'getpotradingdetail':
					$this->getpotradingdetail();
					break;
				case 'approvepotrading':
					$this->approvepotrading();
					break;
				case 'deletepotrading':
					$this->deletepotrading();
					break;
				default :
					$response['error'] = TRUE;
					$response['error_msg'] = getCatalog('invalidmethod');
					echo json_encode($response);
			}
		} else {
			$this->render('index');
		}
	}
	public function getuserpotrading()
  {
		$row=array();
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['poheaderid']) 
			&& isset($_REQUEST['company']) 
			&& isset($_REQUEST['pono'])
			&& isset($_REQUEST['supplier'])
			&& isset($_REQUEST['podate'])
			&& isset($_REQUEST['headernote'])
			&& isset($_REQUEST['paycode'])
			&& isset($_REQUEST['page']) 
			&& isset($_REQUEST['rows']))
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$poheaderid = $_REQUEST['poheaderid'];
				$company = $_REQUEST['company'];
				$pono = $_REQUEST['pono'];
				$supplier = $_REQUEST['supplier'];
				$podate = $_REQUEST['podate'];
				$headernote = $_REQUEST['headernote'];
				$pptt = $_REQUEST['pptt'];
				$paycode = $_REQUEST['paycode'];
				$page       = (isset($_REQUEST['page'])?(int)($_REQUEST['page']):1);
				$rows       = (isset($_REQUEST['rows'])?(int)($_REQUEST['rows']):10);
				if (($page == '') || ($page == '0')) { $page = 1; }
				if (($rows == '') || ($rows == '0')) { $rows = 10; }
				$offset = ($page>1) ? ($page * $rows) - $rows : 0;
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$connection		= Yii::app()->db;
				$rec = Yii::app()->db->createCommand()->select ('group_concat(distinct b.wfbefstat) as wfbefsat')
					->from('workflow a')
					->join('wfgroup b', 'b.workflowid = a.workflowid')
					->join('groupaccess c', 'c.groupaccessid = b.groupaccessid')
					->join('usergroup d', 'd.groupaccessid = c.groupaccessid')
					->join('useraccess e', 'e.useraccessid = d.useraccessid')
					->where(" upper(a.wfname) = upper('listpo')
					and e.username = '" . $pptt . "' ")->queryScalar();
					
				$com = Yii::app()->db->createCommand()->select ('group_concat(distinct a.menuvalueid) as menuvalueid')
					->from('groupmenuauth a')
					->join('menuauth b', 'b.menuauthid = a.menuauthid')
					->join('usergroup c', 'c.groupaccessid = a.groupaccessid')
					->join('useraccess d', 'd.useraccessid = c.useraccessid')
					->where("upper(b.menuobject) = upper('company')
					and d.username = '" . $pptt . "' ")->queryScalar();
					
				$from = '
					from poheader t 
					left join company a on a.companyid = t.companyid 
					left join addressbook b on b.addressbookid = t.addressbookid 
					left join tax c on c.taxid = t.taxid 
					left join paymentmethod e on e.paymentmethodid = t.paymentmethodid ';
				$where = "
					where ((coalesce(t.docdate,'') like '%".$podate."%') and 
					(coalesce(t.pono,'') like '%".$pono."%') and 
					(coalesce(b.fullname,'') like '%".$supplier."%') and 
					(coalesce(e.paycode,'') like '%".$paycode."%') and 
					(coalesce(t.poheaderid,'') like '%".$poheaderid."%') and 
					(coalesce(a.companycode,'') like '%".$company."%'))
					and t.recordstatus in (".$rec.") and a.companyid in (".$com.")";
				$sqlcount = ' select count(1) as total '.$from.' '.$where;
				$sql = '
					select distinct t.*,b.fullname,e.paycode,a.companycode,c.taxcode,
						(
			select case when sum(z.poqty) > sum(z.qtyres) then 1 else 0 end
			from podetail z 
			) as wso
					'.$from.' '.$where;
				$response['total'] = Yii::app()->db->createCommand($sqlcount)->queryScalar();
				$response['numpage'] = ceil($response['total'] / $rows);
				if ($page < 1) { $page = 1; }
				if ($page > $response['numpage']) { $page = $response['numpage']; }
				$response['page'] = $page;
				$sql = $sql . ' order by poheaderid desc limit '.$offset.','.$rows;
				$cmd = $connection->createCommand($sql)->queryAll();
				$row = "<table style='border: 1px solid black;'>";
				$row .= "<tr style='background-color:white;color:black;font-weight:bold;border: 1px solid black;'>";
				$row .= "
				  <td></td>
					<td>ID</td>
					<td>No PO<br><br>Tgl PO</td>
					<td>Perusahaan</td>
					<td>Supplier</td>
					<td>Metode Pembayaran</td>
					<td>Alamat Kirim</td>
					<td>Alamat Tagihan</td>
					<td>Keterangan</td>			
					<td>Status</td>";			
				$row .= "</tr>";
				foreach ($cmd as $data) {
					$row .= 
					  "<td><button type='button' onclick='DetailClick(".$data['poheaderid'].")'>Select</button></td>";					
					switch ($data['recordstatus']) {
						case 1:
							$row.= "<td style='background-color:green;color:white'>";
							break;
						case 2:
							$row.= "<td style='background-color:yellow;color:black'>";
							break;
						case 3:
							$row.= "<td style='background-color:cyan;color:black'>";
							break;
						case 4:
							$row.= "<td style='background-color:blue;color:white'>";
							break;
						case 5:
							$row.= "<td style='background-color:red;color:white'>";
							break;
						default:
							$row.= "<td style='background-color:black;color:white'>";
					}
					$row .= $data['poheaderid']."</td>";
					if ($data['wso'] == 1) {
						$row .= "<td style='background-color:cyan;color:black'>";
					} else {
						$row .= "<td>";
					}
					$row .= $data['pono']."<br>".date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate']))."</td>";
					$row .= "<td>".$data['companycode']."</td>
					<td>".$data['fullname']."</td>
					<td>".$data['paycode']."</td>
					<td>".$data['shipto']."</td>
					<td>".$data['billto']."</td>
					<td>".$data['headernote']."</td>
					<td>".$data['statusname']."</td>";
					$row .= "</tr>";
				}
				$row .= "</table>";
				$response['rows'] = $row;			
			}
			else 
			{
				$response['error'] = TRUE;
				$response['error_msg'] = getCatalog('youarenotauthorized');
			}
		}
		else
		{
			$response['error'] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
  }
	public function Getpotradingdetail()
  {
		$row='';
		$rowdisc='';
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['poheaderid'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$poheaderid = $_REQUEST['poheaderid'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				//Detail
				$cmd             = Yii::app()->db->createCommand()->select('t.*,(t.poqty-t.qtyres) as saldoqty, (t.poqty * t.ratevalue * t.netprice) as amount, a.productname,b.uomcode,c.currencyname,g.prno,d.sloccode,c.symbol')
					->from('podetail t')
					->leftjoin('product a', 'a.productid = t.productid')
					->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')
					->leftjoin('currency c', 'c.currencyid = t.currencyid')
					->leftjoin('sloc d', 'd.slocid = t.slocid')
					->leftjoin('prmaterial f', 'f.prmaterialid = t.prmaterialid')
					->leftjoin('prheader g', 'g.prheaderid = f.prheaderid')
					->leftjoin('currency h', 'h.currencyid = t.currencyid')->where('poheaderid = :poheaderid', array(
					':poheaderid' => $poheaderid
				))->queryAll();
				$row = "<table style='border: 1px solid black;'>";
				$row .= "<tr style='background-color:white;color:black;font-weight:bold;border: 1px solid black;'>";
				$row .= "
					<td>Product</td>
					<td>Qty</td>
					<td>Qty Terkirim</td>
					<td>Sisa Qty</td>
					<td>Satuan</td>
					<td>Harga</td>
					<td>Total</td>
					<td>Rate</td>
					<td>Tgl Kirim</td>
					<td>Toleransi Atas</td>
					<td>Toleransi Bawah</td>
					<td>Keterangan</td>";
				$row .= "</tr>";
				foreach ($cmd as $data) {
					$row .= '<tr>
					  <td>'.$data['productname'].'</td>
						<td>'.Yii::app()->format->formatNumber($data['poqty']).'</td>';
					if ($data['qtyres'] < $data['poqty']) {
						$row .= "<td style='background-color:red;color:white'>";
					} else {
						$row .= "<td style='background-color:red;color:white'>";
					}
					$row .=
						Yii::app()->format->formatNumber($data['qtyres']).'</td>'.
						'<td>'.Yii::app()->format->formatNumber($data['saldoqty']).'</td>'.
						'<td>'.$data['uomcode'].'</td>'.
						'<td>'.$data['symbol'].' '.Yii::app()->format->formatNumber($data['netprice']).'</td>'.
						'<td>'.$data['symbol'].' '.Yii::app()->format->formatNumber($data['amount']).'</td>'.
						'<td>'.Yii::app()->format->formatNumber($data['ratevalue']).'</td>'.
						'<td>'.date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])).'</td>'.
						'<td>'.Yii::app()->format->formatNumber($data['overdelvtol']).'</td>'.
						'<td>'.Yii::app()->format->formatNumber($data['underdelvtol']).'</td>'.
						'<td>'.$data['itemtext'].'</td>'.
						'</tr>';
				}
				$row .= "</table>";
				$response['rows'] = $row;		
				$rowdisc .= "</table>";
				$response['rowsdisc'] = $rowdisc;	
			}
			else 
			{
				$response['error'] = TRUE;
				$response['error_msg'] = getCatalog('youarenotauthorized');
			}
		}
		else
		{
			$response['error'] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
  }
	public function ApprovePOtrading()
  {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['poheaderid'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$poheaderid = $_REQUEST['poheaderid'];
				$pptt = $_REQUEST['pptt'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$connection  = Yii::app()->db;
				$transaction = $connection->beginTransaction();
				try {
					$sql     = 'call ApprovePODirect(:vid,:vcreatedby)';
					$command = $connection->createCommand($sql);
					$command->bindvalue(':vid', $poheaderid, PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby', $pptt, PDO::PARAM_STR);
					$command->execute();
					$transaction->commit();
					$response['error_msg'] = 'OK';
				}
				catch (Exception $e) {
					$transaction->rollback();
					$response['error'] = TRUE;
					$response['error_msg'] = getCatalog($e->getMessage());
				}
			}
			else 
			{
				$response['error'] = TRUE;
				$response['error_msg'] = getCatalog('youarenotauthorized');
			}
		}
		else
		{
			$response['error'] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
  }
	public function DeletePOtrading()
  {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['poheaderid'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$poheaderid = $_REQUEST['poheaderid'];
				$pptt = $_REQUEST['pptt'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$connection  = Yii::app()->db;
				$transaction = $connection->beginTransaction();
				try {
					$sql     = 'call DeletePO(:vid,:vcreatedby)';
					$command = $connection->createCommand($sql);
					$command->bindvalue(':vid', $poheaderid, PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby', $pptt, PDO::PARAM_STR);
					$command->execute();
					$transaction->commit();
					$response['error_msg'] = 'OK';
				}
				catch (Exception $e) {
					$transaction->rollback();
					$response['error'] = TRUE;
					$response['error_msg'] = $e->getMessage();
				}
			}
			else 
			{
				$response['error'] = TRUE;
				$response['error_msg'] = getCatalog('youarenotauthorized');
			}
		}
		else
		{
			$response['error'] = TRUE;
			$response['error_msg'] = getCatalog('invalidmethod');
		}
		echo json_encode($response);
  }
}
