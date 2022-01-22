<?php
class InventoryController extends Controller
{
	protected $pageTitle = 'API Inventory';
	public function actionIndex()
	{
		if (isset($_REQUEST['tag'])) {
			switch ($_REQUEST['tag']) {
				case 'getstock':
					$this->getstock();
					break;
				case 'getusergi':
					$this->getusergi();
					break;
				case 'getgidetail':
					$this->getgidetail();
					break;
				case 'approvegi':
					$this->getapprovegi();
					break;
				case 'deletegi':
					$this->getdeletegi();
					break;
				case 'scaninfo':
					$this->scaninfo();
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
	public function GetStock()
  {
		$row = array();
		$response['error'] = true;
		if (isset($_REQUEST['tag']) && isset($_REQUEST['pptt']) && isset($_REQUEST['key']) && isset($_REQUEST['product']) && isset($_REQUEST['sloc'])
			&& isset($_REQUEST['page']) && isset($_REQUEST['rows']))
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$username 	= $_REQUEST['pptt'];
				$product   	= $_REQUEST['product'];
				$sloc      	= $_REQUEST['sloc'];  
				$page       = (isset($_REQUEST['page'])?(int)($_REQUEST['page']):1);
				$rows       = (isset($_REQUEST['rows'])?(int)($_REQUEST['rows']):10);
				if (($page == '') || ($page == '0')) { $page = 1; }
				if (($rows == '') || ($rows == '0')) { $rows = 10; }
				$offset = ($page>1) ? ($page * $rows) - $rows : 0;
				$query = "
					from productstock t 
					inner join sloc c on c.slocid = t.slocid 
					inner join groupmenuauth f on f.menuvalueid = t.slocid 
					inner join menuauth g on g.menuauthid = f.menuauthid 
					inner join usergroup d on d.groupaccessid = f.groupaccessid
					inner join useraccess e on e.useraccessid = d.useraccessid 
					where (coalesce(t.productname,'') like '%".$product."%') 
						and (coalesce(t.sloccode,'') like '%".$sloc."%') 
						and upper(e.username)=upper('" . $username . "') 
						and upper(g.menuobject) = upper('sloc')
						and t.qty > 0
				";
				$sqlcount = ' select count(1) as total '.$query;
				$sql = '
					select distinct t.productname, t.sloccode, t.storagedesc,
					t.qty, t.uomcode, c.description as slocdesc'.$query;
				$response['total'] = Yii::app()->db->createCommand($sqlcount)->queryScalar();
				$response['numpage'] = ceil($response['total'] / $rows);
				if ($page < 1) { $page = 1; }
				if ($page > $response['numpage']) { $page = $response['numpage']; }
				$response['page'] = $page;
				$cmd = Yii::app()->db->createCommand($sql . ' order by productname asc limit '.$offset.','.$rows)->queryAll();
				foreach ($cmd as $data) {
					$row[] = array(
						'productname' => $data['productname'],
						'sloccode' => $data['sloccode'],
						'description' => $data['storagedesc'],
						'qtyshow' => Yii::app()->format->formatNumber($data['qty']),
						'uomcode' => $data['uomcode'],
					);
				}
				$response=array_merge($response,array('rows'=>$row));
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
	public function getusergi()
  {
		$row='';
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['giheaderid']) 
			&& isset($_REQUEST['company']) 
			&& isset($_REQUEST['gino'])
			&& isset($_REQUEST['sono'])
			&& isset($_REQUEST['customer'])
			&& isset($_REQUEST['headernote'])
			&& isset($_REQUEST['page']) 
			&& isset($_REQUEST['rows']))
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$pptt = $_REQUEST['pptt'];
				$giheaderid = $_REQUEST['giheaderid'];
				$company = $_REQUEST['company'];
				$gino = $_REQUEST['gino'];
				$sono = $_REQUEST['sono'];
				$customer = $_REQUEST['customer'];
				$headernote = $_REQUEST['headernote'];
				$page = $_REQUEST['page'];
				$rows = $_REQUEST['rows'];
				$page       = (isset($_REQUEST['page'])?(int)($_REQUEST['page']):1);
				$rows       = (isset($_REQUEST['rows'])?(int)($_REQUEST['rows']):10);
				if (($page == '') || ($page == '0')) { $page = 1; }
				if (($rows == '') || ($rows == '0')) { $rows = 10; }
				$offset = ($page>1) ? ($page * $rows) - $rows : 0;
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$rec = Yii::app()->db->createCommand()->select ('group_concat(distinct b.wfbefstat) as wfbefsat')
					->from('workflow a')
					->join('wfgroup b', 'b.workflowid = a.workflowid')
					->join('groupaccess c', 'c.groupaccessid = b.groupaccessid')
					->join('usergroup d', 'd.groupaccessid = c.groupaccessid')
					->join('useraccess e', 'e.useraccessid = d.useraccessid')
					->where(" upper(a.wfname) = upper('listgi')
					and e.username = '" . $pptt . "' ")->queryScalar();
					
				$com = Yii::app()->db->createCommand()->select ('group_concat(distinct a.menuvalueid) as menuvalueid')
					->from('groupmenuauth a')
					->join('menuauth b', 'b.menuauthid = a.menuauthid')
					->join('usergroup c', 'c.groupaccessid = a.groupaccessid')
					->join('useraccess d', 'd.useraccessid = c.useraccessid')
					->where("upper(b.menuobject) = upper('company')
					and d.username = '" . $pptt . "' ")->queryScalar();
					
				$connection		= Yii::app()->db;
				$from = '
					from giheader t 
					left join soheader a on a.soheaderid = t.soheaderid 
					left join addressbook b on b.addressbookid = a.addressbookid 
					left join company c on c.companyid = a.companyid ';
				$where = "
					where (coalesce(t.giheaderid,'') like '%".$giheaderid."%') 
						and (coalesce(t.gino,'') like '%".$gino."%')  
						and (coalesce(a.sono,'') like '%".$sono."%') 
						and (coalesce(b.fullname,'') like '%".$customer."%') 
						and (coalesce(t.headernote,'') like '%".$headernote."%')
						and (coalesce(c.companycode,'') like '%".$company."%')
						and t.recordstatus > 0 and t.recordstatus < 3 and t.recordstatus in (".$rec.") and a.companyid in (".$com.") 
						";
				$sqlcount = 'select count(1) as total '.$from.' '.$where;
				$sql = 'select t.giheaderid,t.gino,t.gidate,a.sono,a.pocustno,b.fullname,a.shipto,t.headernote,
					t.statusname,t.recordstatus,c.companycode, 
					(
			select case when sum(z.qty) > sum(z.qtyres) then 1 else 0 end
			from gidetail z 
			where z.giheaderid = t.giheaderid 
			) as wso '.$from.' '.$where;
				$response['total'] = Yii::app()->db->createCommand($sqlcount)->queryScalar();
				$response['numpage'] = ceil($response['total'] / $rows);
				if ($page < 1) { $page = 1; }
				if ($page > $response['numpage']) { $page = $response['numpage']; }
				$response['page'] = $page;
				$sql = $sql . ' order by giheaderid desc limit '.$offset.','.$rows;
				$cmd = $connection->createCommand($sql)->queryAll();
				$row = "<table style='border: 1px solid black;'>";
				$row .= "<tr style='background-color:white;color:black;font-weight:bold;border: 1px solid black;'>";
				$row .= "
				  <td></td>
					<td>ID</td>
					<td>No SJ<br><br>Tgl SJ</td>
					<td>Perusahaan</td>
					<td>Customer</td>
					<td>Alamat Kirim</td>
					<td>Keterangan</td>			
					<td>Status</td>";			
				$row .= "</tr>";
				foreach ($cmd as $data) {
					$row .= 
					  "<td><button type='button' onclick='DetailClick(".$data['giheaderid'].")'>Select</button></td>";					
					switch ($data['recordstatus']) {
						case 1:
							$row.= "<td style='background-color:green;color:white'>";
							break;
						case 2:
							$row.= "<td style='background-color:yellow;color:black'>";
							break;
						case 3:
							$row.= "<td style='background-color:red;color:white'>";
							break;
						default:
							$row.= "<td style='background-color:black;color:white'>";
					}
					$row .= $data['giheaderid']."</td>";
					if ($data['wso'] == 1) {
						$row .= "<td style='background-color:cyan;color:black'>";
					} else {
						$row .= "<td>";
					}
					$row .= $data['gino']."<br>".date(Yii::app()->params['dateviewfromdb'], strtotime($data['gidate']))."</td>";
					$row .= "<td>".$data['companycode']."</td>
					<td>".$data['fullname']."</td>
					<td>".$data['shipto']."</td>
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
	public function Getgidetail()
  {
		$row='';
		$rowdisc='';
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['giheaderid'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$giheaderid = $_REQUEST['giheaderid'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				//Detail
				$cmd = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode,c.sloccode,d.description,c.description as slocdesc')->from('gidetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->leftjoin('sloc c', 'c.slocid = t.slocid')->leftjoin('storagebin d', 'd.storagebinid = t.storagebinid')->where('giheaderid = :giheaderid', array(
        ':giheaderid' => $giheaderid
      ))->queryAll();
				$row = "<table style='border: 1px solid black;'>";
				$row .= "<tr style='background-color:white;color:black;font-weight:bold;border: 1px solid black;'>";
				$row .= "
					<td>Product</td>
					<td>Qty</td>
					<td>Rak</td>
					<td>Keterangan</td>";
				$row .= "</tr>";
				foreach ($cmd as $data) {
					$row .= '<tr>
					  <td>'.$data['productname'].'</td>
						<td>'.Yii::app()->format->formatNumberWODecimal($data['qty']).'<br>'.$data['uomcode'].'</td>
						<td>'.$data['description'].'</td>
						<td>'.$data['itemnote'].'</td>
						</tr>';
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
	public function ApproveGI()
  {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['giheaderid'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$poheaderid = $_REQUEST['giheaderid'];
				$pptt = $_REQUEST['pptt'];
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$connection  = Yii::app()->db;
				$transaction = $connection->beginTransaction();
				try {
					$sql     = 'call ApproveGI(:vid,:vcreatedby)';
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
	public function DeleteGiHeader()
  {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['giheaderid'])) 
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
					$sql     = 'call DeleteGI(:vid,:vcreatedby)';
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
	
	public function ScanInfo()
  {
		$response['error'] = true;
		if (isset($_REQUEST['tag']) 
			&& isset($_REQUEST['pptt']) 
			&& isset($_REQUEST['key']) 
			&& isset($_REQUEST['barcode'])) 
		{
			if (Getkey($_REQUEST['pptt']) == $_REQUEST['key']) 
			{
				$pptt = $_REQUEST['pptt'];
				$barcode = $_REQUEST['barcode'];
				$connection		= Yii::app()->db;
				$response['error'] = FALSE;
				$response['error_msg'] = 'OK';
				$sql = "select distinct a.*,b.productname,c.uomcode,a.qtyori,d.sloccode,e.description,f.productplanno,g.productoutputno,h.dano,i.transstockno,j.sono,k.gino
								FROM tempscan a
								inner JOIN product b ON a.productid=b.productid
								inner JOIN unitofmeasure c ON c.unitofmeasureid=a.unitofmeasureid
								LEFT JOIN sloc d ON d.slocid=a.slocid
								LEFT JOIN storagebin e ON e.storagebinid=a.storagebinid
								LEFT JOIN productplan f on f.productplanid=a.productplanid
								LEFT JOIN productoutput g on g.productoutputid=a.productoutputid
								LEFT JOIN deliveryadvice h on h.deliveryadviceid=a.deliveryadviceid
								LEFT JOIN transstock i on i.transstockid=a.transstockid
								LEFT JOIN soheader j on j.soheaderid=a.soheaderid
								LEFT JOIN giheader k on k.giheaderid=a.grheaderid ";
				$sql .= " where upper(a.barcode) = upper('". $barcode."') ";
				$cmd = $connection->createCommand($sql)->queryAll();
				$row = "<table style='border: 1px solid black;'>";
				$row .= "<tr style='background-color:white;color:black;font-weight:bold;border: 1px solid black;'>";
				$row .= "
					<td>ID</td>
					<td>Barcode<br><br>Nama Produk</td>
					<td>Qty<br>Satuan</td>
					<td>Gudang<br>Rak</td>
					<td>No SPP</td>			
					<td>No Hasil Produksi</td>
					<td>No FPB</td>
					<td>No Transfer</td>
					<td>No SJ</td>
					<td>Is Scan HP</td>
					<td>Is Approve HP</td>
					<td>Is Scan SJ</td>
					<td>Is Approve SJ</td>
					";			
				$row .= "</tr>";
				foreach ($cmd as $data) {
					$row .= 
					  "<tr><td>".$data['tempscanid']."</td>
					   <td>".$data['barcode']."<br>".$data['productname']."</td>
					   <td>".$data['qtyori']."<br>".$data['uomcode']."</td>
						 <td>".$data['sloccode']."<br>".$data['description']."</td>
					   <td>".$data['productplanno']."</td>
						 <td>".$data['productoutputno']."</td>
						 <td>".$data['dano']."</td>
						 <td>".$data['transstockno']."</td>
						 <td>".$data['gino']."</td>
						 <td>".$data['isscanhp']."</td>
						 <td>".$data['isapprovehp']."</td>
						 <td>".$data['isscangi']."</td>
						 <td>".$data['isapprovegi']."</td>";
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
}