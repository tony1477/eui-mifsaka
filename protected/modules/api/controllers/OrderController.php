<?php
class OrderController extends Controller
{
	protected $pageTitle = 'API Order';
	public function actionIndex()
	{
		if (isset($_REQUEST['tag'])) {
			switch ($_REQUEST['tag']) {
				case 'getuserso':
					$this->getuserso();
					break;
				case 'getsodetail':
					$this->getsodetail();
					break;
				case 'getsodisc':
					$this->getsodisc();
					break;
				case 'approveso':
					$this->approveso();
					break;
				case 'deleteso':
					$this->deleteso();
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
	public function Getuserso()
  {
		$row=array();
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['soheaderid']) 
			&& isset($_REQUEST['company']) 
			&& isset($_REQUEST['pocustno'])
			&& isset($_REQUEST['customer'])
			&& isset($_REQUEST['headernote'])
			&& isset($_REQUEST['sales'])
			&& isset($_REQUEST['sono'])
			&& isset($_REQUEST['page']) 
			&& isset($_REQUEST['rows']))
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$soheaderid = $_REQUEST['soheaderid'];
				$company = $_REQUEST['company'];
				$pocustno = $_REQUEST['pocustno'];
				$customer = $_REQUEST['customer'];
				$headernote = $_REQUEST['headernote'];
				$employee = $_REQUEST['sales'];
				$pptt = $_REQUEST['pptt'];
				$sono = $_REQUEST['sono'];
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
					->where(" upper(a.wfname) = upper('listso')
					and e.username = '" . $pptt . "' ")->queryScalar();
					
				$com = Yii::app()->db->createCommand()->select ('group_concat(distinct a.menuvalueid) as menuvalueid')
					->from('groupmenuauth a')
					->join('menuauth b', 'b.menuauthid = a.menuauthid')
					->join('usergroup c', 'c.groupaccessid = a.groupaccessid')
					->join('useraccess d', 'd.useraccessid = c.useraccessid')
					->where("upper(b.menuobject) = upper('company')
					and d.username = '" . $pptt . "' ")->queryScalar();
					
				$from = '
					from soheader t 
					left join company a on a.companyid = t.companyid 
					left join addressbook b on b.addressbookid = t.addressbookid 
					left join tax c on c.taxid = t.taxid 
					left join employee d on d.employeeid = t.employeeid 
					left join paymentmethod e on e.paymentmethodid = t.paymentmethodid ';
				$where = "
					where (coalesce(soheaderid,'') like '%".$soheaderid."%') 
						and (coalesce(sono,'') like '%".$sono."%') 
						and (coalesce(b.fullname,'') like '%".$customer."%') 
						and (coalesce(a.companycode,'') like '%".$company."%')
						and (coalesce(t.pocustno,'') like '%".$pocustno."%') 
						and (coalesce(d.fullname,'') like '%".$employee."%') 
						and (t.headernote like '%".$headernote."%')
						and b.iscustomer = 1 
						and t.companyid in (select a.menuvalueid 
						from groupmenuauth a
						inner join menuauth b on b.menuauthid = a.menuauthid
						inner join usergroup c on c.groupaccessid = a.groupaccessid 
						inner join useraccess d on d.useraccessid = c.useraccessid 
						inner join wfgroup e on e.groupaccessid = a.groupaccessid
						inner join workflow f on f.workflowid = e.workflowid
						where b.menuobject = 'company'
						and d.username = '" . $pptt . "')
						";
				$sqlcount = ' select count(1) as total '.$from.' '.$where;
				$sql = '
					select t.soheaderid,t.sono,t.sodate,t.companyid,a.companycode,t.addressbookid,b.fullname,b.top,b.creditlimit,t.totalbefdisc,
						t.totalaftdisc,t.statusname,t.taxid,c.taxcode, t.pocustno,t.employeeid,d.fullname as employeename,b.currentlimit,
						t.paymentmethodid,e.paycode,b.overdue,t.shipto,t.billto,t.headernote,t.poheaderid,t.pono, 
						case when (((hutang + totalaftdisc + t.pendinganso) > creditlimit) and (top > 0)) then 1  
						when (((hutang + totalaftdisc + t.pendinganso) <= creditlimit) and (top > 0)) then 2  
						when (((hutang + totalaftdisc + t.pendinganso) > creditlimit) and (top <= 0)) then 3 
						else 4	end as warna,
						t.pendinganso,t.recordstatus,
						t.statusname ,
						(
		select case when sum(z.qty) > sum(z.giqty) then 1 else 0 end 
		from sodetail z 
		where z.soheaderid = t.soheaderid 		
		) as wso
					'.$from.' '.$where;
				$response['total'] = Yii::app()->db->createCommand($sqlcount)->queryScalar();
				$response['numpage'] = ceil($response['total'] / $rows);
				if ($page < 1) { $page = 1; }
				if ($page > $response['numpage']) { $page = $response['numpage']; }
				$response['page'] = $page;
				$sql = $sql . ' order by soheaderid desc limit '.$offset.','.$rows;
				$cmd = $connection->createCommand($sql)->queryAll();
				$row = "<table style='border: 1px solid black;'>";
				$row .= "<tr style='background-color:white;color:black;font-weight:bold;border: 1px solid black;'>";
				$row .= "
				  <td></td>
					<td>ID</td>
					<td>No SO<br><br>Tgl SO</td>
					<td>Perusahaan</td>
					<td>No PO</td>
					<td>PO Cust No</td>
					<td>Customer</td>
					<td>TOP</td>
					<td>Jumlah Hutang</td>
					<td>Kredit Limit</td>
					<td>Pendingan SO</td>
					<td>Total Sblm Disc</td>
					<td>Total Setelah Disc</td>
					<td>Sales</td>
					<td>Metode Pembayaran</td>
					<td>Jenis Pajak</td>
					<td>Alamat Kirim</td>
					<td>Alamat Tagihan</td>
					<td>Keterangan</td>			
					<td>Status</td>";			
				$row .= "</tr>";
				foreach ($cmd as $data) {
					switch ($data['warna']) {
						case '1':
							$row .= "<tr style='background-color:red;color:white;border: 1px solid black;'>";
							break;
						case '2':
							$row .= "<tr style='background-color:orange;color:black;border: 1px solid black;'>";
							break;
						case '3':
							$row .= "<tr style='background-color:yellow;color:black;border: 1px solid black;'>";
							break;
						default :
							$row .= "<tr style='background-color:white;color:black;border: 1px solid black;'>";
					}
					$row .= 
					  "<td><button type='button' onclick='DetailClick(".$data['soheaderid'].")'>Select</button></td>";
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
							$row.= "<td style='background-color:orange;color:white'>";
							break;
						case 6:
							$row.= "<td style='background-color:red;color:white'>";
							break;
						default:
							$row.= "<td style='background-color:black;color:white'>";
					}
					$row .= $data['soheaderid']."</td>";
					if ($data['wso'] == 1) {
						$row .= "<td style='background-color:cyan;color:black'>";
					} else {
						$row .= "<td>";
					}
					$row .= $data['sono']."<br>".date(Yii::app()->params['dateviewfromdb'], strtotime($data['sodate']))."</td>";
					$row .= "<td>".$data['companycode']."</td>
					<td>".$data['pono']."</td>
					<td>".$data['pocustno']."</td>
					<td>".$data['fullname']."</td>
					<td>".$data['top']."</td>
					<td>".Yii::app()->format->formatCurrency($data['currentlimit'])."</td>
					<td>".Yii::app()->format->formatCurrency($data['creditlimit'])."</td>
					<td>".Yii::app()->format->formatCurrency($data['pendinganso'])."</td>
					<td>".Yii::app()->format->formatCurrency($data['totalbefdisc'])."</td>
					<td>".Yii::app()->format->formatCurrency($data['totalaftdisc'])."</td>
					<td>".$data['employeename']."</td>
					<td>".$data['paycode']."</td>
					<td>".$data['taxcode']."</td>
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
	public function Getsodetail()
  {
		$row='';
		$rowdisc='';
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['soheaderid'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$soheaderid = $_REQUEST['soheaderid'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				//Detail
				$cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.currencyname,d.sloccode,
					(t.price * t.qty * t.currencyrate) as amount,
					ifnull((select sum(z.qty) from productstock z where z.productid = t.productid and z.unitofmeasureid = t.unitofmeasureid and z.slocid = t.slocid),0) as qtystock')->from('sodetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('currency c', 'c.currencyid = t.currencyid')->leftjoin('sloc d', 'd.slocid = t.slocid')->where('soheaderid = :soheaderid', array(
					':soheaderid' => $soheaderid
				))->queryAll();
				$row = "<table style='border: 1px solid black;'>";
				$row .= "<tr style='background-color:white;color:black;font-weight:bold;border: 1px solid black;'>";
				$row .= "
					<td>Product</td>
					<td>Qty</td>
					<td>Qty Stock</td>
					<td>Qty SJ</td>
					<td>Satuan</td>
					<td>Harga</td>
					<td>Total</td>
					<td>Mata Uang</td>
					<td>Rate</td>
					<td>Gudang</td>
					<td>TOP</td>
					<td>Tgl Kirim</td>
					<td>Keterangan</td>";
				$row .= "</tr>";
				foreach ($cmd as $data) {
					$row .= 
						'<tr>'.
						'<td>'.$data['productname'].'</td>'.
						'<td>'.Yii::app()->format->formatNumberWODecimal($data['qty']).'</td>'.
						'<td>'.Yii::app()->format->formatNumberWODecimal($data['qtystock']).'</td>'.
						'<td>'.Yii::app()->format->formatNumberWODecimal($data['giqty']).'</td>'.
						'<td>'.$data['uomcode'].'</td>'.
						'<td>'.Yii::app()->format->formatNumber($data['price']).'</td>'.
						'<td>'.Yii::app()->format->formatNumber($data['amount']).'</td>'.
						'<td>'.$data['currencyname'].'</td>'.
						'<td>'.Yii::app()->format->formatNumber($data['currencyrate']).'</td>'.
						'<td>'.$data['sloccode'].'</td>'.
						'<td>'.date(Yii::app()->params['dateviewfromdb'], strtotime($data['delvdate'])).'</td>'.
						'<td>'.$data['itemnote'].'</td>'.
						'</tr>';
				}
				$row .= "</table>";
				$response['rows'] = $row;		
				
				//Disc
				$cmd             =  Yii::app()->db->createCommand()->select()->from('sodisc t')->where('soheaderid = :soheaderid', array(
					':soheaderid' => $soheaderid
				))->queryAll();
				$rowdisc = "<table style='border: 1px solid black;'>";
				$rowdisc .= "<tr style='background-color:white;color:black;font-weight:bold;border: 1px solid black;'>";
				$rowdisc .= "
				  <td></td>
					<td>Nilai Diskon</td>";
				$rowdisc .= "</tr>";
				foreach ($cmd as $data) {
					$rowdisc .= 
						'<tr>'.
						'<td></td>'.
						'<td>'.Yii::app()->format->formatNumber($data['discvalue']).'</td>'.
						'</tr>';
				}
				$cmd      = Yii::app()->db->createCommand()->selectdistinct('(sum(t.price * t.qty) - gettotalamountdiscso(t.soheaderid)) as amountbefdisc,gettotalamountdiscso(t.soheaderid) as amountafterdisc')->from('sodetail t')->where('soheaderid = :soheaderid', array(
					':soheaderid' => $soheaderid
				))->queryRow();
				$rowdisc .= "<tr>
					<td>Diskon</td>
					<td>".Yii::app()->format->formatNumber($cmd['amountbefdisc'])."</td>
					</tr>";
				$rowdisc .= "<tr>
					<td>Setelah Diskon</td>
					<td>".Yii::app()->format->formatNumber($cmd['amountafterdisc'])."</td>
					</tr>";
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
	public function ApproveSO()
  {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['soheaderid'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$soheaderid = $_REQUEST['soheaderid'];
				$pptt = $_REQUEST['pptt'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$connection  = Yii::app()->db;
				$transaction = $connection->beginTransaction();
				try {
					$sql     = 'call ApproveSO(:vid,:vcreatedby)';
					$command = $connection->createCommand($sql);
					$command->bindvalue(':vid', $soheaderid, PDO::PARAM_STR);
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
	public function DeleteSO()
  {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['soheaderid'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$soheaderid = $_REQUEST['soheaderid'];
				$pptt = $_REQUEST['pptt'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$connection  = Yii::app()->db;
				$transaction = $connection->beginTransaction();
				try {
					$sql     = 'call DeleteSO(:vid,:vcreatedby)';
					$command = $connection->createCommand($sql);
					$command->bindvalue(':vid', $soheaderid, PDO::PARAM_STR);
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
