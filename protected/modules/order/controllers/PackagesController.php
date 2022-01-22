<?php

class PackagesController extends Controller
{
    public $menuname = 'packages';
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
    public function actionIndexdatacomp()
    {
        if (isset($_GET['grid']))
            echo $this->actionSearchdatacomp();
        else
            $this->renderPartial('index', array());
    }
    public function actionIndexdatacust()
    {
        if (isset($_GET['grid']))
            echo $this->actionSearchdatacust();
        else
            $this->renderPartial('index', array());
    }
    public function actionIndexdetail()
    {
        if (isset($_GET['grid']))
            echo $this->actionSearchDetail();
        else
            $this->renderPartial('index', array());
    }
    public function actionIndexdisc()
    {
        if (isset($_GET['grid']))
            echo $this->actionSearchDisc();
        else
            $this->renderPartial('index', array());
    }
    public function actionPackagetype()
    {
        $arr = array('All Customer', 'Pilih Perusahaan', 'Pilih Customer', 'Pilih Perusahaan dan Customer');
        $result = array();
        $result['total'] = count($arr);
        for ($i = 0; $i < count($arr); $i++) {
            $row[] = array(
                'no' => $i + 1,
                'type' => $arr[$i],
            );
        }

        $result = array_merge($result, array('rows' => $row));
        echo CJSON::encode($result);
    }
    public function actionGetData()
    {
        if (!isset($_GET['id'])) {
            $dadate              = new DateTime('now');
            $sql = "insert into packages (docdate,recordstatus) values ('" . $dadate->format('Y-m-d') . "'," . findstatusbyuser('inspkg') . ")";
            $model = Yii::app()->db->createCommand($sql)->execute();
            $id = Yii::app()->db->createCommand('select last_insert_id()')->queryScalar();
            echo CJSON::encode(array(
                'packageid' => $id
            ));
        }
    }
    public function actionGetCompany()
    {
        header("Content-Type: application/json");
        $companyname = isset($_GET['q']) ? $_GET['q'] : '';
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 't.companyid';
        $order = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
        $offset = ($page - 1) * $rows;
        $result = array();
        $row = array();
        $connection = Yii::app()->db;
        $from = 'from company t';
        $where = "
			where (companyname like '%" . $companyname . "%')
				and t.recordstatus = 1 and companyid in (" . getUserObjectValues('company') . ")
                and t.companyid not in(select companyid from tempcompany where tableid = {$_GET['packageid']})";
        $sqlcount = ' select count(1) as total ' . $from . ' ' . $where;
        $sql = 'select t.companyid,t.companyname,companycode ' . $from . ' ' . $where;
        $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
        $cmd = $connection->createCommand($sql . ' order by ' . $sort . ' ' . $order . ' limit ' . $offset . ',' . $rows)->queryAll();
        foreach ($cmd as $data) {
            $row[] = array(
                'companyid' => $data['companyid'],
                'companyname' => $data['companyname'],
                'companycode' => $data['companycode'],
            );
        }
        $result = array_merge($result, array('rows' => $row));
        echo CJSON::encode($result);
    }
    public function actionGetCustomer()
    {
        header("Content-Type: application/json");
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $fullname = isset($_GET['q']) ? $_GET['q'] : $fullname;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'addressbookid';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $page = isset($_GET['page']) ? intval($_GET['page']) : $page;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
        $order = isset($_GET['order']) ? strval($_GET['order']) : $order;
        $offset = ($page - 1) * $rows;
        $result = array();
        $row = array();

        $cmd = Yii::app()->db->createCommand()
            ->select('count(1) as total')
            ->from('addressbook t')
            ->leftjoin('account a', 'a.accountid = t.accpiutangid')
            ->leftjoin('salesarea b', 'b.salesareaid = t.salesareaid')
            ->leftjoin('pricecategory c', 'c.pricecategoryid = t.pricecategoryid')
            ->leftjoin('groupcustomer d', 'd.groupcustomerid = t.groupcustomerid')
            ->leftjoin('custcategory e', 'e.custcategoryid = t.custcategoryid')
            ->leftjoin('custgrade f', 'f.custgradeid = t.custgradeid')
            ->where(
                "(fullname like :fullname) and iscustomer = 1 and t.recordstatus=1
                          and t.addressbookid not in(select customerid from tempcustomer where tableid = {$_GET['packageid']})",
                array(':fullname' => '%' . $fullname . '%')
            )
            ->queryScalar();

        $result['total'] = $cmd;

        $cmd = Yii::app()->db->createCommand()
            ->select('t.*,a.accountname,b.areaname,c.categoryname,d.groupname,e.custcategoryname,f.custgradename,g.taxcode,h.paycode')
            ->from('addressbook t')
            ->leftjoin('account a', 'a.accountid = t.accpiutangid')
            ->leftjoin('salesarea b', 'b.salesareaid = t.salesareaid')
            ->leftjoin('pricecategory c', 'c.pricecategoryid = t.pricecategoryid')
            ->leftjoin('groupcustomer d', 'd.groupcustomerid = t.groupcustomerid')
            ->leftjoin('custcategory e', 'e.custcategoryid = t.custcategoryid')
            ->leftjoin('custgrade f', 'f.custgradeid = t.custgradeid')
            ->leftjoin('tax g', 'g.taxid = t.taxid')
            ->leftjoin('paymentmethod h', 'h.paymentmethodid = t.paymentmethodid')
            ->where(
                "(fullname like :fullname) and iscustomer = 1 and t.recordstatus=1
                        and t.addressbookid not in(select customerid from tempcustomer where tableid = {$_GET['packageid']})",
                array(':fullname' => '%' . $fullname . '%')
            )
            ->offset($offset)
            ->limit($rows)
            ->order($sort . ' ' . $order)
            ->queryAll();

        foreach ($cmd as $data) {
            $row[] = array(
                'addressbookid' => $data['addressbookid'],
                'fullname' => $data['fullname'],
                'taxno' => $data['taxno'],
                'ktpno' => $data['ktpno'],
                'creditlimit' => Yii::app()->format->formatNumber($data['creditlimit']),
                'currentlimit' => Yii::app()->format->formatNumber($data['currentlimit']),
                'isstrictlimit' => $data['isstrictlimit'],
                'overdue' => $data['overdue'],
                'accpiutangid' => $data['accpiutangid'],
                'accpiutangname' => $data['accountname'],
                'salesareaid' => $data['salesareaid'],
                'areaname' => $data['areaname'],
                'pricecategoryid' => $data['pricecategoryid'],
                'categoryname' => $data['categoryname'],
                'groupcustomerid' => $data['groupcustomerid'],
                'groupname' => $data['groupname'],
                'custcategoryid' => $data['custcategoryid'],
                'custcategoryname' => $data['custcategoryname'],
                'custgradeid' => $data['custgradeid'],
                'custgradename' => $data['custgradename'],
                'taxid' => $data['taxid'],
                'taxcode' => $data['taxcode'],
                'paymentmethodid' => $data['paymentmethodid'],
                'paycode' => $data['paycode'],
                'bankaccountno' => $data['bankaccountno'],
                'bankname' => $data['bankname'],
                'accountowner' => $data['accountowner'],
                'recordstatuscustomer' => $data['recordstatus'],
            );
        }
        $result = array_merge($result, array('rows' => $row));
        echo CJSON::encode($result);
    }
    public function actiongetDataPackage()
    {
        $idcm = Yii::app()->db->createCommand("select group_concat(companyid) as companyid, group_concat(companyname) as companyname from tempcompany where tableid = " . $_REQUEST['id'])->queryRow();
        $idcs = Yii::app()->db->createCommand("select group_concat(customerid) as customerid, group_concat(fullname) as fullname from tempcustomer where tableid = " . $_REQUEST['id'])->queryRow();
        echo CJSON::encode(array(
            'companyid' => $idcm['companyid'],
            'companies' => $idcm['companyname'],
            'customerid' => $idcs['customerid'],
            'customers' => $idcs['fullname'],
        ));
    }
    public function actionGeneratedetail()
    {
        if (isset($_POST['id'])) {
            $connection  = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                $sql     = 'call GenerateSOPO(:vid, :vhid, :vcompanyid, :vcreatedby)';
                $command = $connection->createCommand($sql);
                $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
                $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
                $command->bindvalue(':vcompanyid', $_POST['companyid'], PDO::PARAM_INT);
                $command->bindvalue(':vcreatedby', Yii::app()->user->id, PDO::PARAM_STR);
                $command->execute();
                $transaction->commit();
                GetMessage(false, 'insertsuccess');
            } catch (Exception $e) {
                $transaction->rollBack();
                GetMessage(true, $e->getMessage());
            }
        }
        Yii::app()->end();
    }
    public function actionGenerateaddress()
    {
        $sql = "select concat(addressname,ifnull(cityname,'')) 
			from address a 
			join addressbook b on b.addressbookid = a.addressbookid 
			left join city c on c.cityid = a.cityid 
			where b.addressbookid = " . $_POST['id'] . " 
			limit 1";
        $address = Yii::app()->db->createCommand($sql)->queryScalar();
        if (Yii::app()->request->isAjaxRequest) {
            $connection  = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                $sql     = 'call GenerateCustDisc(:vid, :vhid)';
                $command = $connection->createCommand($sql);
                $command->bindvalue(':vid', $_POST['id'], PDO::PARAM_INT);
                $command->bindvalue(':vhid', $_POST['hid'], PDO::PARAM_INT);
                $command->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
            echo CJSON::encode(array(
                'shipto' => $address,
                'billto' => $address
            ));
            Yii::app()->end();
        }
    }
    public function actionGetMultiCompany()
    {
        $connection = Yii::app()->db;
        //$transaction=$connection->beginTransaction();
        $arr = $_REQUEST['companyid'];
        //$arr = explode(',',$_REQUEST['customerid']);
        $val = 0;
        try {
            for ($i = 0; $i < count($arr); $i++) {
                //check data
                $q = Yii::app()->db->createCommand("select find_in_set(" . $arr[$i] . ",companyid) from packages where packageid = {$_REQUEST['packageid']}")->queryScalar();
                if ($q == 0) {
                    if ($val == 0) {
                        $val = $arr[$i];
                    } else {
                        $val = $val . ',' . $arr[$i];
                    }
                }
            }

            if ($val != 0) {
                $checkcompany = Yii::app()->db->createCommand("select ifnull(companyid,0) from packages where packageid = " . $_REQUEST['packageid'])->queryScalar();

                if ($checkcompany != 0) {
                    $update = " concat(companyid,',','{$val}')";
                } else {
                    $update = "'{$val}'";
                }

                $insert = $connection->createCommand("update packages set companyid = {$update}, packagetype={$_REQUEST['packagetype']} where packageid = {$_REQUEST['packageid']}")->execute();

                if ($_REQUEST['packagetype'] == 2) {
                    $deletecustomerid = Yii::app()->db->createCommand("update packages set customerid = null where packageid = {$_REQUEST['packageid']}")->execute();
                    $deletetmpcustomerid = Yii::app()->db->createCommand("delete from tempcustomer where tableid = {$_REQUEST['packageid']}")->execute();
                }

                $companyid = Yii::app()->db->createCommand("select companyid from packages where packageid = " . $_REQUEST['packageid'])->queryScalar();

                $exec = "call getDataMulti(:vid,:vdata,:vtype)";
                $command = $connection->createCommand($exec);
                $command->bindvalue(':vid', $_REQUEST['packageid'], PDO::PARAM_STR);
                $command->bindvalue(':vdata', $companyid, PDO::PARAM_STR);
                $command->bindvalue(':vtype', 'company', PDO::PARAM_STR);
                $command->execute();

                //$transaction->commit();
                $qfullname = "select group_concat(companyname) from company where companyid in ($companyid)";
                $fullname = Yii::app()->db->createCommand($qfullname)->queryScalar();
                echo CJSON::encode(array(
                    'status' => 'success',
                    'companyname' => $fullname,
                    'multicompany' => $companyid,
                ));
                Yii::app()->end();
            } else {
                $error = 'duplicate';
                throw new Exception($error);
            }
        } catch (CDbException $e) {
            //$transaction->rollBack();
            GetMessage(true, $e->getMessage());
        }
    }
    public function actionCancelMultiCompany()
    {
        //updte rows first

        //if($val!=0) {
        $connection = Yii::app()->db;
        //$transaction = $connection->beginTransaction();
        try {
            $txt = '';
            if (isset($_REQUEST['deletemulti']) && $_REQUEST['deletemulti'] != '') {
                $delete = Yii::app()->db->createCommand("delete from tempcompany where tableid = " . $_REQUEST['packageid'] . " and companyid in( {$_REQUEST['companyid']})")->execute();
            } else {
                $delete = Yii::app()->db->createCommand("delete from tempcompany where tableid = " . $_REQUEST['packageid'] . " and companyname =  '{$_REQUEST['companyid']}'")->execute();
            }

            if ($delete == true) {
                $companyid = Yii::app()->db->createCommand("select ifnull(group_concat(companyid),'') from tempcompany where tableid = " . $_REQUEST['packageid'])->queryScalar();

                if (isset($_REQUEST['deletemulti']) && $_REQUEST['deletemulti'] != '') {
                    $delcompany = 'null';
                } else {
                    $delcompany = "'{$companyid}'";
                }
                if (isset($_REQUEST['all']) && $_REQUEST['all'] != '') $txt = ' ,packagetype=1';
                $update = Yii::app()->db->createCommand("update packages set companyid = {$delcompany} {$txt} where packageid = " . $_REQUEST['packageid'])->execute();
                //$transaction->commit();

                if (isset($_REQUEST['deletemulti']) && $_REQUEST['deletemulti'] != '') {
                    echo CJSON::encode(array(
                        'status' => 'success',
                    ));
                    Yii::app()->end();
                } else {
                    $qfullname = "select group_concat(companyname) from company where companyid in ($companyid)";
                    $fullname = Yii::app()->db->createCommand($qfullname)->queryScalar();
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'companyname' => $fullname,
                        'multicompany' => $companyid
                    ));
                    Yii::app()->end();
                }
            } else {
                $error = 'error delete';
                GetMessage(true, $error);
            }
        } catch (CDbException $e) {
            //$transaction->rollBack();
            GetMessage(true, $e->getMessage());
        }
    }
    public function actionGetMultiCustomer()
    {
        $arr = $_REQUEST['customerid'];
        //$arr = explode(',',$_REQUEST['customerid']);
        $val = 0;
        for ($i = 0; $i < count($arr); $i++) {
            //check data
            $q = Yii::app()->db->createCommand("select find_in_set(" . $arr[$i] . ",customerid) from packages where packageid = {$_REQUEST['packageid']}")->queryScalar();
            if ($q == 0) {
                if ($val == 0) {
                    $val = $arr[$i];
                } else {
                    $val = $val . ',' . $arr[$i];
                }
            }
        }

        if ($val != 0) {
            $checkcustomer = Yii::app()->db->createCommand("select ifnull(customerid,0) from packages where packageid = " . $_REQUEST['packageid'])->queryScalar();

            if ($checkcustomer != 0) {
                $update = " concat(customerid,',','{$val}')";
            } else {
                $update = "'{$val}'";
            }

            $insert = Yii::app()->db->createCommand("update packages set customerid = {$update}, packagetype={$_REQUEST['packagetype']} where packageid = {$_REQUEST['packageid']}")->execute();

            if ($_REQUEST['packagetype'] == 3) {
                $deletecompanyid = Yii::app()->db->createCommand("update packages set companyid = null where packageid = {$_REQUEST['packageid']}")->execute();
                $deletetmpcompanyid = Yii::app()->db->createCommand("delete from tempcompany where tableid = {$_REQUEST['packageid']}")->execute();
            }
            $customerid = Yii::app()->db->createCommand("select customerid from packages where packageid = " . $_REQUEST['packageid'])->queryScalar();

            $exec = "call getDataMulti(:vid,:vdata,:vtype)";
            $command = Yii::app()->db->createCommand($exec);
            $command->bindvalue(':vid', $_REQUEST['packageid'], PDO::PARAM_STR);
            $command->bindvalue(':vdata', $customerid, PDO::PARAM_STR);
            $command->bindvalue(':vtype', 'customer', PDO::PARAM_STR);
            $command->execute();

            $qfullname = "select group_concat(fullname) from addressbook where addressbookid in ($customerid)";
            $fullname = Yii::app()->db->createCommand($qfullname)->queryScalar();
            echo CJSON::encode(array(
                'status' => 'success',
                'fullname' => $fullname,
                'multicustomer' => $customerid
            ));
            Yii::app()->end();
        } else {
            $error = 'duplicate';
            throw new Exception($error);
        }
    }
    public function actionCancelMultiCustomer()
    {
        //updte rows first

        try {
            $txt = '';
            if (isset($_REQUEST['deletemulti']) && $_REQUEST['deletemulti'] != '') {
                $delete = Yii::app()->db->createCommand("delete from tempcustomer where tableid = " . $_REQUEST['packageid'] . " and customerid in( {$_REQUEST['customerid']})")->execute();
            } else {
                $delete = Yii::app()->db->createCommand("delete from tempcustomer where tableid = " . $_REQUEST['packageid'] . " and fullname =  '{$_REQUEST['customerid']}'")->execute();
            }

            if ($delete == true) {
                $customerid = Yii::app()->db->createCommand("select ifnull(group_concat(customerid),'') from tempcustomer where tableid = " . $_REQUEST['packageid'])->queryScalar();
                if (isset($_REQUEST['deletemulti']) && $_REQUEST['deletemulti'] != '') {
                    $delcustomer = 'null';
                } else {
                    $delcustomer = "'{$customerid}'";
                }
                if (isset($_REQUEST['all']) && $_REQUEST['all'] != '') $txt = ' ,packagetype=1';
                $update = Yii::app()->db->createCommand("update packages set customerid = {$delcustomer} {$txt} where packageid = " . $_REQUEST['packageid'])->execute();
                //$transaction->commit();

                if (isset($_REQUEST['deletemulti']) && $_REQUEST['deletemulti'] != '') {

                    echo CJSON::encode(array(
                        'status' => 'success',
                    ));
                    Yii::app()->end();
                } else {
                    $qfullname = "select group_concat(fullname) from addressbook where addressbookid in ($customerid)";
                    $fullname = Yii::app()->db->createCommand($qfullname)->queryScalar();
                    echo CJSON::encode(array(
                        'status' => 'success',
                        'fullname' => $fullname,
                        'multicustomer' => $customerid
                    ));
                    Yii::app()->end();
                }
            } else {
                $error = 'error delete';
                throw new Exception($error);
            }
        } catch (CDbException $e) {
            //$transaction->rollBack();
            GetMessage(true, $e->getMessage());
        }
    }
    public function search()
    {
        header("Content-Type: application/json");
        $packageid    = isset($_POST['packageid']) ? $_POST['packageid'] : '';
        $companyname  = isset($_POST['companyname']) ? $_POST['companyname'] : '';
        $docno        = isset($_POST['docno']) ? $_POST['docno'] : '';
        $customer      = isset($_POST['customer']) ? $_POST['customer'] : '';
        $packagename  = isset($_POST['packagename']) ? $_POST['packagename'] : '';
        $headernote   = isset($_POST['headernote']) ? $_POST['headernote'] : '';
        $page         = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows         = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort         = isset($_POST['sort']) ? strval($_POST['sort']) : 't.packageid';
        $order        = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset       = ($page - 1) * $rows;
        $result       = array();
        $row          = array();
        $connection    = Yii::app()->db;
        $maxstat      = $connection->createCommand("select getwfmaxstatbywfname('apppkg')")->queryScalar();

        $from = '
        from packages t
        left join tempcompany a1 on a1.tableid = t.packageid
        left join tempcustomer a2 on a2.tableid = t.packageid 
        left join paymentmethod a3 on a3.paymentmethodid = t.paymentmethodid ';
        $where = "
        where ((a1.companyid in (" . getUserObjectWfValues('company', 'apppkg') . ")) or (t.customerid is null and t.companyid is null) or (a2.customerid is not null and a1.companyid is null)) and t.recordstatus in(" . getUserRecordStatus('listpkg') . ") and (packageid like '%" . $packageid . "%') and (coalesce(docno,'') like '%" . $docno . "%') and (coalesce(a2.fullname,'') like '%" . $customer . "%') 
            and (coalesce(a1.companyname,'') like '%" . $companyname . "%')
            and (coalesce(t.packagename,'') like '%" . $packagename . "%') and (t.headernote like '%" . $headernote . "%') ";
        $sqlcount = ' select count(distinct packageid) as total ' . $from . ' ' . $where;
        $sql = "
			select distinct t.packageid,t.docno,t.packagename,t.docdate,t.startdate,t.enddate,t.headernote,t.recordstatus,t.statusname,t.companyid,t.customerid,
            case 
		when packagetype = 1 then 'All Customer' 
		when packagetype = 2 then 'Untuk Perusahaan'
		when packagetype = 3 then 'Untuk Customer'
		when packagetype = 4 then 'Untuk Perusahaan dan Customer' end as packagetypename, t.packagetype, t.paymentmethodid,a3.paycode
		-- ifnull(t.companyid,'-') as companyid,
		-- ifnull(t.customerid,'-') as customerid 
			" . $from . ' ' . $where;
        $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
        $sql = $sql . ' order by ' . $sort . ' ' . $order . ' limit ' . $offset . ',' . $rows;
        $cmd = $connection->createCommand($sql)->queryAll();
        foreach ($cmd as $data) {
            $row[] = array(
                'packageid' => $data['packageid'],
                'companyid' => $data['companyid'],
                'packagetype' => $data['packagetype'],
                'packagename' => $data['packagename'],
                'customerid' => $data['customerid'],
                'packagetypename' => $data['packagetypename'],
                'paymentmethodid' => $data['paymentmethodid'],
                'paycode' => $data['paycode'],
                'docno' => $data['docno'],
                'docdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['docdate'])),
                'startdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
                'enddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
                'headernote' => $data['headernote'],
                'recordstatus' => $data['recordstatus'],
                'statusname' => $data['statusname']
            );
        }
        $result = array_merge($result, array(
            'rows' => $row
        ));
        return CJSON::encode($result);
    }
    public function searchcombo()
    {
        header("Content-Type: application/json");
        $packageid   = isset($_GET['q']) ? $_GET['q'] : '';
        $docno          = isset($_GET['q']) ? $_GET['q'] : '';
        $customer      = isset($_GET['q']) ? $_GET['q'] : '';
        $pocustno     = isset($_GET['q']) ? $_GET['q'] : '';
        $headernote   = isset($_GET['q']) ? $_GET['q'] : '';
        $page         = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows         = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort         = isset($_GET['sort']) ? strval($_GET['sort']) : 'packageid';
        $order        = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
        $offset       = ($page - 1) * $rows;
        $result       = array();
        $row          = array();
        $recordstatus = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppkg')")->queryScalar();
        $connection    = Yii::app()->db;
        $from = "from 
			(select a.packageid,docno,packagename,startdate,enddate,headernote,a1.paymentmethodid,a1.paycode
        from packages a
        left join paymentmethod a1 on a1.paymentmethodid = a.paymentmethodid
        where packagetype=1 and curdate() >= startdate and curdate() <= enddate and a.recordstatus={$recordstatus}
        and (a.docno like '%" . $docno . "%'
        or a.packagename like '%" . $headernote . "%')
        union
        select b.packageid,docno,packagename,startdate,enddate,headernote,b2.paymentmethodid,b2.paycode
        from packages b
        join tempcompany b1 on b1.tableid = b.packageid
        left join paymentmethod b2 on b2.paymentmethodid = b.paymentmethodid
        where b1.companyid = " . (isset($_REQUEST['companyid']) ? $_REQUEST['companyid'] : 'null') . " and b.packagetype=2 and curdate() >= startdate and   curdate() <= enddate and b.recordstatus={$recordstatus}
        and (b.docno like '%" . $docno . "%'
        or b.packagename like '%" . $headernote . "%')
        union
        select c.packageid,docno,packagename,startdate,enddate,headernote,c2.paymentmethodid,c2.paycode
        from packages c
        join tempcustomer c1 on c1.tableid = c.packageid
        left join paymentmethod c2 on c2.paymentmethodid = c.paymentmethodid
        where c1.customerid = " . (isset($_REQUEST['addressbookid']) ? $_REQUEST['addressbookid'] : 'null') . " and c.packagetype=3 and curdate() >= startdate  and curdate() <= enddate and c.recordstatus={$recordstatus}
        and (c.docno like '%" . $docno . "%'
        or c.packagename like '%" . $headernote . "%')
        union
        select d.packageid,docno,packagename,startdate,enddate,headernote,d3.paymentmethodid,d3.paycode
        from packages d
        join tempcompany d1 on d1.tableid = d.packageid
        join tempcustomer d2 on d2.tableid = d.packageid
        join paymentmethod d3 on d3.paymentmethodid = d.paymentmethodid
        where d.packagetype=4 and d2.customerid = " . (isset($_REQUEST['addressbookid']) ? $_REQUEST['addressbookid'] : 'null') . " and d1.companyid = " . (isset($_REQUEST['companyid']) ? $_REQUEST['companyid'] : 'null') . "
        and curdate() >= startdate and curdate() <= enddate and d.recordstatus={$recordstatus}
        and (d.docno like '%" . $docno . "%'
        or d.packagename like '%" . $headernote . "%') ) z";
        $sql = "select *  " . $from;
        $sqlcount = ' select count(1) as total ' . $from;
        $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
        $cmd = $connection->createCommand($sql . ' order by ' . $sort . ' ' . $order . ' limit ' . $offset . ',' . $rows)->queryAll();
        foreach ($cmd as $data) {
            $row[] = array(
                'packageid' => $data['packageid'],
                'docno' => $data['docno'],
                'startdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
                'enddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
                'packagename' => $data['packagename'],
                'paymentmethodid' => $data['paymentmethodid'],
                'paycode' => $data['paycode'],
                'headernote' => $data['headernote'],
            );
        }
        $result = array_merge($result, array(
            'rows' => $row
        ));
        return CJSON::encode($result);
    }

    public function actionDownxls1()
    {
        $this->menuname = 'rekappenjualanperbarang';
        parent::actionDownxls1();

        $per = 1;
        $startdate = '2021-01-01';
        $enddate = '2021-01-31';
        $companyid = 1;

        // $this->phpExcel->setActiveSheetIndex(0)
        //   ->setCellValueByColumnAndRow(1,2,date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
        //   ->setCellValueByColumnAndRow(3,2,date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
        //   ->setCellValueByColumnAndRow(6,1,GetCompanyCode($companyid));

        // require 'vendor/autoload.php';
        // $this->phpSpreadsheet = new Spreadsheet();

        $totalnominal1 = 0;
        $totaldisc1 = 0;
        $totalnetto1 = 0;
        $isdisplay = 0;
        $employee = '';
        $customer = '';
        $product = '';
        $salesarea = '';
        $materialgroup = '';
        $sloc = '';

        if (isset($isdisplay) && $isdisplay != '') {
            $isdisplay1 = " and za.isdisplay = " . $isdisplay . " ";
            $isdisplay2 = " and c.isdisplay = " . $isdisplay . " ";
        } else {
            $isdisplay1 = "";
            $isdisplay2 = "";
        }
        $sql = "select distinct zk.materialgroupid,zk.materialgroupcode,zk.description
				from soheader za 
				join giheader zb on zb.soheaderid = za.soheaderid
				join gidetail zc on zc.giheaderid = zb.giheaderid
				join sodetail zs on zs.sodetailid = zc.sodetailid
				left join employee zd on zd.employeeid = za.employeeid
				join product ze on ze.productid = zs.productid
				left join addressbook zf on zf.addressbookid = za.addressbookid
				left join salesarea zg on zg.salesareaid = zf.salesareaid
				join sloc zh on zh.slocid = zc.slocid
				join invoice zi on zi.giheaderid = zc.giheaderid
				join productplant zj on zj.productid=zc.productid and zj.slocid=zc.slocid
				join materialgroup zk on zk.materialgroupid=zj.materialgroupid
				where zi.recordstatus = 3 and zi.invoiceno is not null and invoiceno not like '%-%-%' and za.companyid = " . $companyid . " and
				zf.fullname like '%" . $customer . "%' and zd.fullname like '%" . $employee . "%'  " . $isdisplay1 . " and ze.productname like '%" . $product . "%' and zg.areaname like '%" . $salesarea . "%' and zk.description like '%" . $materialgroup . "%' and zh.sloccode like '%" . $sloc . "%'
				and zi.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' order by description";

        $dataReader = Yii::app()->db->createCommand($sql)->queryAll();

        // $Excel = $this->phpSpreadsheet;
        // $sheet = $this->phpSpreadsheet->getActiveSheet();

        $this->phpSpreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow(2, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
            ->setCellValueByColumnAndRow(4, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
            ->setCellValueByColumnAndRow(7, 1, GetCompanyCode($companyid));
        $line = 4;

        foreach ($dataReader as $row) {
            $this->phpSpreadsheet->getActiveSheet()
                ->setCellValueByColumnAndRow(1, $line, 'Divisi')
                ->setCellValueByColumnAndRow(2, $line, ': ' . $row['description'])
                ->setCellValueByColumnAndRow(3, $line, '')
                ->setCellValueByColumnAndRow(4, $line, '')
                ->setCellValueByColumnAndRow(5, $line, '');

            $line++;
            $this->phpSpreadsheet->getActiveSheet()
                ->setCellValueByColumnAndRow(1, $line, 'No')
                ->setCellValueByColumnAndRow(2, $line, 'Nama Barang')
                ->setCellValueByColumnAndRow(3, $line, 'Qty')
                ->setCellValueByColumnAndRow(4, $line, 'Price')
                ->setCellValueByColumnAndRow(5, $line, 'Total')
                ->setCellValueByColumnAndRow(6, $line, 'Disc')
                ->setCellValueByColumnAndRow(7, $line, 'Netto');
            $line++;
            $sql1 = "select productid,productname,sum(qty) as giqty,harga,sum(nom) as nominal,sum(nett) as netto from
								(select distinct ss.gidetailid,d.fullname,i.productid,i.productname,k.uomcode,ss.qty,
								(select xx.price from sodetail xx where xx.sodetailid=ss.sodetailid) as harga,
								(ss.qty*(select xx.price from sodetail xx where xx.sodetailid=ss.sodetailid)) as nom,
								(select getamountdiscso(zza.soheaderid,zza.sodetailid,zzb.qty)
								from gidetail zzb 
								join sodetail zza on zza.sodetailid = zzb.sodetailid
								where zzb.giheaderid = b.giheaderid and zzb.productid = i.productid and zzb.gidetailid=ss.gidetailid) as nett
								from invoice a 
								join giheader b on b.giheaderid = a.giheaderid
								join soheader c on c.soheaderid = b.soheaderid
								join addressbook d on d.addressbookid = c.addressbookid
								join employee e on e.employeeid = c.employeeid
								join salesarea f on f.salesareaid = d.salesareaid
								join sodetail g on g.soheaderid = b.soheaderid
								join gidetail ss on ss.giheaderid = b.giheaderid
								join sloc h on h.slocid = ss.slocid
								join product i on i.productid = ss.productid
								join productplant j on j.productid = i.productid and j.slocid=g.slocid
								join unitofmeasure k on k.unitofmeasureid = ss.unitofmeasureid
								where a.recordstatus = 3 and a.invoiceno is not null and invoiceno not like '%-%-%' and
								c.companyid = " . $companyid . " and h.sloccode like '%" . $sloc . "%' and d.fullname like '%" . $customer . "%' and
								e.fullname like '%" . $employee . "%' and f.areaname like '%" . $salesarea . "%' and i.productname like '%" . $product . "%' 
								and j.materialgroupid = " . $row['materialgroupid'] . "
								and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' 
								and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' " . $isdisplay2 . "
								)zz group by productid order by productname";
            $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();

            $totalqty = 0;
            $totalnetto = 0;
            $totaldisc = 0;
            $totalnominal = 0;
            $i = 0;

            foreach ($dataReader1 as $row1) {
                $this->phpSpreadsheet->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(1, $line, $i += 1)
                    ->setCellValueByColumnAndRow(2, $line, $row1['productname'])
                    ->setCellValueByColumnAndRow(3, $line, $row1['giqty'])
                    ->setCellValueByColumnAndRow(4, $line, $row1['harga'] / $per)
                    ->setCellValueByColumnAndRow(5, $line, $row1['nominal'] / $per)
                    ->setCellValueByColumnAndRow(6, $line, ($row1['nominal'] / $per) - ($row1['netto'] / $per))
                    ->setCellValueByColumnAndRow(7, $line, $row1['netto'] / $per);
                $line++;
                $totalqty += $row1['giqty'];
                $totalnominal += $row1['nominal'] / $per;
                $totaldisc += ($row1['nominal'] / $per) - ($row1['netto'] / $per);
                $totalnetto += $row1['netto'] / $per;
            }
            $this->phpSpreadsheet->getActiveSheet()
                ->setCellValueByColumnAndRow(2, $line, 'Total ' . $row['description'])
                ->setCellValueByColumnAndRow(3, $line, $totalqty)
                ->setCellValueByColumnAndRow(5, $line, $totalnominal)
                ->setCellValueByColumnAndRow(6, $line, $totaldisc)
                ->setCellValueByColumnAndRow(7, $line, $totalnetto);
            $line += 2;
            $totalnominal1 += $totalnominal;
            $totaldisc1 += $totaldisc;
            $totalnetto1 += $totalnetto;
        }
        $this->phpSpreadsheet->getActiveSheet()
            ->setCellValueByColumnAndRow(2, $line, 'TOTAL')
            ->setCellValueByColumnAndRow(5, $line, $totalnominal1)
            ->setCellValueByColumnAndRow(6, $line, $totaldisc1)
            ->setCellValueByColumnAndRow(7, $line, $totalnetto1);
        $line += 2;
        // $Excel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, 'PhpSpreadsheet')->setCellValueByColumnAndRow(2, 6, 'NEW PHPEXCEL');
        // $sheet->setCellValue('A1', 'Hello World !');
        // // var_dump($sheet);

        $this->savePhpSpreadsheet($this->phpSpreadsheet);
    }

    public function actionSanitizeInput()
    {
        parent::actionSanitizeInput();
        $arr = '';
        // $filter = new Filter;

        echo "
    <form action='" . Yii::app()->createUrl('order/packages/save') . "' method='post'>
    <input type='text' name='user' > <input type='text' name='password' ><input type='submit' value='KIRIM'></form>";
    }
    /*  public function searchcombo()
  {
    header("Content-Type: application/json");
		$packageid   = isset($_GET['q']) ? $_GET['q'] : '';
        $docno        	= isset($_GET['q']) ? $_GET['q'] : '';
        $customer  		= isset($_GET['q']) ? $_GET['q'] : '';
		$pocustno     = isset($_GET['q']) ? $_GET['q'] : '';
		$headernote   = isset($_GET['q']) ? $_GET['q'] : '';
		$page         = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows         = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort         = isset($_GET['sort']) ? strval($_GET['sort']) : 'packageid';
		$order        = isset($_GET['order']) ? strval($_GET['order']) : 'desc';
		$offset       = ($page - 1) * $rows;
		$result       = array();
		$row          = array();
		$recordstatus = Yii::app()->db->createCommand("select getwfmaxstatbywfname('apppkg')")->queryScalar();
        $connection		= Yii::app()->db;
		$from = "from 
			(select a.packageid,docno,packagename,startdate,enddate,headernote,a1.paymentmethodid,a1.paycode
    from packages a
    left join paymentmethod a1 on a1.paymentmethodid = a.paymentmethodid
    where packagetype=1 and curdate() >= startdate and curdate() <= enddate and a.recordstatus={$recordstatus}
    and a.docno like '%".(isset($_REQUEST['docno'])?$_REQUEST['docno']:'')."%'
    and a.packagename like '%".(isset($_REQUEST['packagename'])?$_REQUEST['packagename']:'')."%'
    union
    select b.packageid,docno,packagename,startdate,enddate,headernote,b2.paymentmethodid,b2.paycode
    from packages b
    join tempcompany b1 on b1.tableid = b.packageid
    left join paymentmethod b2 on b2.paymentmethodid = b.paymentmethodid
    where b1.companyid = ".(isset($_REQUEST['companyid'])?$_REQUEST['companyid']:'null')." and b.packagetype=2 and curdate() >= startdate and   curdate() <= enddate and b.recordstatus={$recordstatus}
    and b.docno like '%".(isset($_REQUEST['docno'])?$_REQUEST['docno']:'')."%'
    and b.packagename like '%".(isset($_REQUEST['packagename'])?$_REQUEST['packagename']:'')."%'
    union
    select c.packageid,docno,packagename,startdate,enddate,headernote,c2.paymentmethodid,c2.paycode
    from packages c
    join tempcustomer c1 on c1.tableid = c.packageid
    left join paymentmethod c2 on c2.paymentmethodid = c.paymentmethodid
    where c1.customerid = ".(isset($_REQUEST['addressbookid'])?$_REQUEST['addressbookid']:'null')." and c.packagetype=3 and curdate() >= startdate  and curdate() <= enddate and c.recordstatus={$recordstatus}
    and c.docno like '%".(isset($_REQUEST['docno'])?$_REQUEST['docno']:'')."%'
    and c.packagename like '%".(isset($_REQUEST['packagename'])?$_REQUEST['packagename']:'')."%'
    union
    select d.packageid,docno,packagename,startdate,enddate,headernote,d3.paymentmethodid,d3.paycode
    from packages d
    join tempcompany d1 on d1.tableid = d.packageid
    join tempcustomer d2 on d2.tableid = d.packageid
    join paymentmethod d3 on d3.paymentmethodid = d.paymentmethodid
    where d.packagetype=4 and d2.customerid = ".(isset($_REQUEST['addressbookid'])?$_REQUEST['addressbookid']:'null')." and d1.companyid = ".(isset ($_REQUEST['companyid'])?$_REQUEST['companyid']:'null')."
    and curdate() >= startdate and curdate() <= enddate and d.recordstatus={$recordstatus}
    and d.docno like '%".(isset($_REQUEST['docno'])?$_REQUEST['docno']:'')."%'
    and d.packagename like '%".(isset($_REQUEST['packagename'])?$_REQUEST['packagename']:'')."%' ) z";
		$sql = "select *  ".$from;
		$sqlcount = ' select count(1) as total '.$from;
        $result['total'] = $connection->createCommand($sqlcount)->queryScalar();
		$cmd = $connection->createCommand($sql . ' order by '.$sort . ' ' . $order. ' limit '.$offset.','.$rows)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'packageid' => $data['packageid'],
        'docno' => $data['docno'],
        'startdate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['startdate'])),
        'enddate' => date(Yii::app()->params['dateviewfromdb'], strtotime($data['enddate'])),
        'packagename' => $data['packagename'],
        'paymentmethodid' => $data['paymentmethodid'],
        'paycode' => $data['paycode'],
        'headernote' => $data['headernote'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
*/
    public function actionSearchDetail()
    {
        header("Content-Type: application/json");
        $id = 0;
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'packagedetailid';
        $order           = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset          = ($page - 1) * $rows;
        $page            = isset($_GET['page']) ? intval($_GET['page']) : $page;
        $rows            = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
        $sort            = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
        $order           = isset($_GET['order']) ? strval($_GET['order']) : $order;
        $offset          = ($page - 1) * $rows;
        $result          = array();
        $row             = array();
        $footer          = array();
        $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->where('packageid = :packageid', array(
            ':packageid' => $id
        ))->queryScalar();
        $result['total'] = $cmd;
        $cmd             = Yii::app()->db->createCommand()->select('t.*,a.productname,b.uomcode')->from('packagedetail t')->leftjoin('product a', 'a.productid = t.productid')->leftjoin('unitofmeasure b', 'b.unitofmeasureid = t.unitofmeasureid')->where('packageid = :packageid', array(
            ':packageid' => $id
        ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
        foreach ($cmd as $data) {
            $row[] = array(
                'packagedetailid' => $data['packagedetailid'],
                'packageid' => $data['packageid'],
                'productid' => $data['productid'],
                'productname' => $data['productname'],
                'unitofmeasureid' => $data['unitofmeasureid'],
                'uomcode' => $data['uomcode'],
                'price' => Yii::app()->format->formatNumber($data['price']),
                'isbonus' => $data['isbonus'],
                'qty' => Yii::app()->format->formatNumber($data['qty'])
            );
        }

        $result   = array_merge($result, array(
            'rows' => $row
        ));
        echo CJSON::encode($result);
    }
    public function actionSearchdisc()
    {
        header("Content-Type: application/json");
        $id = 0;
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'packagediscid';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;
        $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
        $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
        $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
        $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
        $offset = ($page - 1) * $rows;
        $result = array();
        $row    = array();
        $footer = array();
        if (!isset($_GET['combo'])) {
            $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedisc t')->where('packageid = :packageid', array(
                ':packageid' => $id
            ))->queryScalar();
        } else {
            $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedisc t')->where('packageid = :packageid', array(
                ':packageid' => $id
            ))->queryScalar();
        }
        $result['total'] = $cmd;
        if (!isset($_GET['combo'])) {
            $cmd = Yii::app()->db->createCommand()->select()->from('packagedisc t')->where('packageid = :packageid', array(
                ':packageid' => $id
            ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
        } else {
            $cmd = Yii::app()->db->createCommand()->select('t.*')->from('packagedisc t')->where('packageid = :packageid', array(
                ':packageid' => $id
            ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
        }
        foreach ($cmd as $data) {
            $row[] = array(
                'packagediscid' => $data['packagediscid'],
                'packageid' => $data['packageid'],
                'discvalue' => Yii::app()->format->formatNumber($data['discvalue']),
                'discvalue1' => '-'
            );
        }
        $cmd      = Yii::app()->db->createCommand()->selectdistinct('(sum(t.price * t.qty) - gettotalamountdiscpackage(t.packageid)) as amountbefdisc,gettotalamountdiscpackage(t.packageid) as amountafterdisc')->from('packagedetail t')->where('packageid = :packageid', array(
            ':packageid' => $id
        ))->queryRow();
        $footer[] = array(
            'packagediscid' => 'Diskon',
            'discvalue' => Yii::app()->format->formatNumber($cmd['amountbefdisc']),
            'discvalue1' => '-'
        );
        $footer[] = array(
            'packagediscid' => 'Setelah Diskon',
            'discvalue' => $cmd['amountafterdisc'],
            'discvalue1' => Yii::app()->format->formatNumber($cmd['amountafterdisc'])
        );
        $result   = array_merge($result, array(
            'rows' => $row
        ));
        $result   = array_merge($result, array(
            'footer' => $footer
        ));
        echo CJSON::encode($result);
    }
    public function actionSearchdatacomp()
    {
        header("Content-Type: application/json");
        $id = 0;
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'tableid';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;
        $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
        $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
        $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
        $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
        $offset = ($page - 1) * $rows;
        $result = array();
        $row    = array();
        $footer = array();
        if (!isset($_GET['combo'])) {
            $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('tempcompany t')->where('tableid = :packageid', array(
                ':packageid' => $id
            ))->queryScalar();
        } else {
            $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedisc t')->where('packageid = :packageid', array(
                ':packageid' => $id
            ))->queryScalar();
        }
        $result['total'] = $cmd;
        if (!isset($_GET['combo'])) {
            $cmd = Yii::app()->db->createCommand()->select('t.*,(select companycode from company x where x.companyid = t.companyid ) as companycode')->from('tempcompany t')->where('tableid = :packageid', array(
                ':packageid' => $id
            ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
        } else {
            $cmd = Yii::app()->db->createCommand()->select()->from('packagedisc t')->where('packageid = :packageid', array(
                ':packageid' => $id
            ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
        }
        foreach ($cmd as $data) {
            $row[] = array(
                'tableid' => $data['tableid'],
                'companyid' => $data['companyid'],
                'companycode' => $data['companycode'],
                'companyname' => $data['companyname']
            );
        }
        $result   = array_merge($result, array(
            'rows' => $row
        ));
        echo CJSON::encode($result);
    }
    public function actionSearchdatacust()
    {
        header("Content-Type: application/json");
        $id = 0;
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'tableid';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;
        $page   = isset($_GET['page']) ? intval($_GET['page']) : $page;
        $rows   = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
        $sort   = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort, 't.') > 0) ? $sort : 't.' . $sort;
        $order  = isset($_GET['order']) ? strval($_GET['order']) : $order;
        $offset = ($page - 1) * $rows;
        $result = array();
        $row    = array();
        $footer = array();
        if (!isset($_GET['combo'])) {
            $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('tempcustomer t')->where('tableid = :packageid', array(
                ':packageid' => $id
            ))->queryScalar();
        } else {
            $cmd = Yii::app()->db->createCommand()->select('count(1) as total')->from('packagedisc t')->where('packageid = :packageid', array(
                ':packageid' => $id
            ))->queryScalar();
        }
        $result['total'] = $cmd;
        if (!isset($_GET['combo'])) {
            $cmd = Yii::app()->db->createCommand()->select('t.*')->from('tempcustomer t')->where('tableid = :packageid', array(
                ':packageid' => $id
            ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
        } else {
            $cmd = Yii::app()->db->createCommand()->select('t.*')->from('packagedisc t')->where('packageid = :packageid', array(
                ':packageid' => $id
            ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
        }
        foreach ($cmd as $data) {
            $row[] = array(
                'tableid' => $data['tableid'],
                'custoemrid' => $data['customerid'],
                'fullname' => $data['fullname'],
                'areaname' => $data['areaname'],
                'groupname' => $data['groupname'],
                'gradedesc' => $data['gradedesc']
            );
        }
        $result   = array_merge($result, array(
            'rows' => $row
        ));

        echo CJSON::encode($result);
    }
    private function ModifyData($connection, $arraydata)
    {
        $id = (isset($arraydata[0]) ? $arraydata[0] : '');
        if ($id == '') {
            $sql     = 'call Insertpackage(:vdocno,:vdocdate,:vcompanyid,:vpoheaderid,:vaddressbookid,:vpocustno,:vemployeeid,:vpaymentmethodid,:vtaxid,:vshipto,:vbillto,:vheadernote,:vrecordstatus,:vcreatedby)';
            $command = $connection->createCommand($sql);
            $command->bindvalue(':vdocno', $arraydata[3], PDO::PARAM_STR);
            $command->bindvalue(':vrecordstatus', $arraydata[13], PDO::PARAM_STR);
        } else {
            $sql     = 'call UpdatePackage (:vid,:vdocdate,:vpackagename,:vpackagetype,:vcompanyid,:vcustomerid,:vpaymentmethodid,:vstartdate,:venddate,:vheadernote,:vcreatedby)';
            $command = $connection->createCommand($sql);
            $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
            $this->DeleteLock($this->menuname, $arraydata[0]);
        }
        $command->bindvalue(':vdocdate', $arraydata[1], PDO::PARAM_STR);
        $command->bindvalue(':vpackagename', $arraydata[2], PDO::PARAM_STR);
        $command->bindvalue(':vpackagetype', $arraydata[3], PDO::PARAM_STR);
        $command->bindvalue(':vcompanyid', $arraydata[4], PDO::PARAM_STR);
        $command->bindvalue(':vcustomerid', $arraydata[5], PDO::PARAM_STR);
        $command->bindvalue(':vpaymentmethodid', $arraydata[6], PDO::PARAM_STR);
        $command->bindvalue(':vstartdate', $arraydata[7], PDO::PARAM_STR);
        $command->bindvalue(':venddate', $arraydata[8], PDO::PARAM_STR);
        $command->bindvalue(':vheadernote', $arraydata[9], PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->id, PDO::PARAM_STR);


        $command->execute();
    }
    public function actionUpload()
    {
        parent::actionUpload();
        $target_file = dirname('__FILES__') . '/uploads/' . basename($_FILES["Filepackage"]["name"]);
        if (move_uploaded_file($_FILES["Filepackage"]["tmp_name"], $target_file)) {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($target_file);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $abid = '';
            $nourut = '';
            $connection  = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                for ($row = 2; $row <= $highestRow; ++$row) {
                    $nourut = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                    if ($nourut != '') {
                        $companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '" . $companycode . "'")->queryScalar();
                        $docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()));
                        $docno = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $abid = Yii::app()->db->createCommand("select packageid 
							from package 
							where companyid = " . $companyid . "
							and docdate = '" . $docdate . "' 
							and docno = '" . $docno . "' 					
							")->queryScalar();
                        if ($abid == '') {
                            $customer = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
                            $customerid = Yii::app()->db->createCommand("select addressbookid 
								from addressbook 
								where fullname = '" . $customer . "' and iscustomer = 1")->queryScalar();
                            $pono = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
                            $poheaderid = Yii::app()->db->createCommand("select poheaderid 
								from poheader 
								where companyid = " . $companyid . " and pono like '" . $pono . "'")->queryScalar();
                            $pocustno = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                            $totalbefdisc = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
                            $totalaftdisc = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
                            $sales = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $salesid = Yii::app()->db->createCommand("select employeeid from employee where fullname = '" . $sales . "'")->queryScalar();
                            $paymentmethod = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $paymentmethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '" . $paymentmethod . "'")->queryScalar();
                            $taxcode = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
                            $taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '" . $taxcode . "'")->queryScalar();
                            $shipto = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
                            $billto = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
                            $headernote = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
                            $recordstatus = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
                            $this->ModifyData($connection, array(
                                '', $docdate,
                                $companyid, $docno, $poheaderid, $customerid, $pocustno, $salesid, $paymentmethodid, $taxid, $shipto, $billto, $headernote, $recordstatus
                            ));
                            //get id addressbookid
                            $abid = Yii::app()->db->createCommand("select packageid 
								from package 
								where companyid = " . $companyid . "
								and docdate = '" . $docdate . "' 
								and docno = '" . $docno . "' 					
								")->queryScalar();
                        }
                        if ($abid != '') {
                            if ($objWorksheet->getCellByColumnAndRow(16, $row)->getValue() != '') {
                                $productname = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
                                $productid = Yii::app()->db->createCommand("select productid from product where productname = '" . $productname . "'")->queryScalar();
                                $qty = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();
                                $uomcode = $objWorksheet->getCellByColumnAndRow(18, $row)->getValue();
                                $uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '" . $uomcode . "'")->queryScalar();
                                $sloccode = $objWorksheet->getCellByColumnAndRow(19, $row)->getValue();
                                $slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '" . $sloccode . "'")->queryScalar();
                                $price = $objWorksheet->getCellByColumnAndRow(20, $row)->getValue();
                                $currencyname = $objWorksheet->getCellByColumnAndRow(21, $row)->getValue();
                                $currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '" . $currencyname . "'")->queryScalar();
                                $currencyrate = $objWorksheet->getCellByColumnAndRow(22, $row)->getValue();
                                $delvdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(23, $row)->getValue()));
                                $description = $objWorksheet->getCellByColumnAndRow(24, $row)->getValue();
                                $this->ModifyDataDetail($connection, array(
                                    '', $abid, $productid, $qty, $uomid, $slocid, $price, $currencyid, $currencyrate,
                                    $delvdate, $description
                                ));
                            }
                            if ($objWorksheet->getCellByColumnAndRow(25, $row)->getValue() != '') {
                                $discvalue = $objWorksheet->getCellByColumnAndRow(25, $row)->getValue();
                                $this->ModifyDataDisc($connection, array('', $abid, $discvalue));
                            }
                        }
                    }
                }
                $transaction->commit();
                GetMessage(false, 'insertsuccess');
            } catch (Exception $e) {
                $transaction->rollBack();
                GetMessage(true, $e->getMessage());
            }
        }
    }
    public function actionUploadSOPO()
    {
        //parent::actionUploadDoc();
        Yii::import('ext.PHPExcel.XPHPExcel');
        $this->phpExcel = XPHPExcel::createPHPExcel();
        $target_file = dirname('__FILES__') . '/uploads/' . basename($_FILES["FilepackagePO"]["name"]);
        if (move_uploaded_file($_FILES["FilepackagePO"]["tmp_name"], $target_file)) {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($target_file);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $abid = '';
            $nourut = '';
            $connection  = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                for ($row = 1; $row <= $highestRow; ++$row) {
                    $companycode = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $companyid = Yii::app()->db->createCommand("select companyid from company where companycode = '{$companycode}'")->queryScalar();
                    $customer = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $addressbookid = Yii::app()->db->createCommand("select addressbookid from addressbook where fullname = '{$customer}'")->queryScalar();
                    $sales = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $employeeid = Yii::app()->db->createCommand("select employeeid from employee where fullname = '{$sales}'")->queryScalar();
                    $shipto = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $disc = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $row += 1;
                    $docdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(1, $row)->getValue()));
                    $top = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $paymentmethodid = Yii::app()->db->createCommand("select paymentmethodid from paymentmethod where paycode = '{$top}'")->queryScalar();
                    $tax = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $taxid = Yii::app()->db->createCommand("select taxid from tax where taxcode = '" . $tax . "'")->queryScalar();
                    $billto = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $headernote = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
                    //var_dump($companyid);

                    $stmt = Yii::app()->db->createCommand("select getrunno({$companyid},25,'{$docdate}')");
                    $docno = $stmt->queryScalar();

                    $this->ModifyData($connection, array(
                        '', $docdate,
                        $companyid, $docno, '', $addressbookid, '', $employeeid, $paymentmethodid, $taxid, $shipto, $billto, $headernote, 1
                    ));

                    $abid = Yii::app()->db->createCommand("select packageid 
							from package 
							where companyid = " . $companyid . "
							and docdate = '" . $docdate . "' 
							and docno = '" . $docno . "' 					
							")->queryScalar();

                    if ($abid != '') {
                        $row += 3;
                        for ($i = $row; $i <= $highestRow; ++$i) {
                            if ($objWorksheet->getCellByColumnAndRow(1, $i)->getValue() != '') {
                                $productname = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
                                $productid = Yii::app()->db->createCommand("select productid from product where productname = '" . $productname . "'")->queryScalar();
                                $qty = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
                                $uomcode = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
                                $uomid = Yii::app()->db->createCommand("select unitofmeasureid from unitofmeasure where uomcode = '" . $uomcode . "'")->queryScalar();
                                $sloccode = $objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
                                $slocid = Yii::app()->db->createCommand("select slocid from sloc where sloccode = '" . $sloccode . "'")->queryScalar();
                                $price = $objWorksheet->getCellByColumnAndRow(5, $i)->getValue();
                                $currencyname = $objWorksheet->getCellByColumnAndRow(6, $i)->getValue();
                                $currencyid = Yii::app()->db->createCommand("select currencyid from currency where currencyname = '" . $currencyname . "'")->queryScalar();
                                $currencyrate = $objWorksheet->getCellByColumnAndRow(7, $i)->getValue();
                                $delvdate = date(Yii::app()->params['datetodb'], strtotime($objWorksheet->getCellByColumnAndRow(8, $i)->getValue()));
                                $description = $objWorksheet->getCellByColumnAndRow(9, $i)->getValue();
                                $this->ModifyDataDetail($connection, array(
                                    '', $abid, $productid, $qty, $uomid, $slocid, $price, $currencyid, $currencyrate,
                                    $description, $delvdate
                                ));
                                $row++;
                            }
                        }
                    }

                    if ($disc != '') {
                        $exp = explode('+', $disc);
                        for ($j = 0; $j < count($exp); $j++) {
                            $this->ModifyDataDisc($connection, array('', $abid, $exp[$j]));
                        }
                    }
                }
                $transaction->commit();
                GetMessage(false, 'insertsuccess');
            } catch (Exception $e) {
                $transaction->rollBack();
                GetMessage(true, $e->getMessage());
            }
        }
    }
    public function actionSave()
    {
        header("Content-Type: application/json");
        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {
            $this->ModifyData($connection, array((isset($_POST['packageid']) ? $_POST['packageid'] : ''), date(Yii::app()->params['datetodb'], strtotime($_POST['docdate'])),
                $_POST['packagename'], $_POST['packagetype'], $_POST['companyid'], $_POST['customerid'], $_POST['paymentmethodid'], date(Yii::app()->params['datetodb'], strtotime($_POST['startdate'])), date(Yii::app()->params['datetodb'], strtotime($_POST['enddate'])), $_POST['headernote']
            ));
            $transaction->commit();
            GetMessage(false, 'insertsuccess');
        } catch (Exception $e) {
            $transaction->rollBack();
            GetMessage(true, $e->getMessage());
        }
    }
    private function ModifyDataDetail($connection, $arraydata)
    {
        $id = (isset($arraydata[0]) ? $arraydata[0] : '');
        if ($id == '') {
            $sql     = 'call Insertpackagedetail(:vpackageid,:vproductid,:vqty,:vuomid,:vprice,:visbonus,:vcreatedby)';
            $command = $connection->createCommand($sql);
        } else {
            $sql     = 'call Updatepackagedetail(:vid,:vpackageid,:vproductid,:vqty,:vuomid,:vprice,:visbonus,:vcreatedby)';
            $command = $connection->createCommand($sql);
            $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
        }
        $command->bindvalue(':vpackageid', $arraydata[1], PDO::PARAM_STR);
        $command->bindvalue(':vproductid', $arraydata[2], PDO::PARAM_STR);
        $command->bindvalue(':vqty', $arraydata[3], PDO::PARAM_STR);
        $command->bindvalue(':vuomid', $arraydata[4], PDO::PARAM_STR);
        $command->bindvalue(':vprice', $arraydata[5], PDO::PARAM_STR);
        $command->bindvalue(':visbonus', $arraydata[6], PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
    }
    public function actionSaveDetail()
    {
        // parent::actionSanitizeInput();
        // $filter = new Filter($_POST);
        // var_dump($filter->filter()); 
        // var_dump ($filter);
        header("Content-Type: application/json");
        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {
            $this->ModifyDataDetail($connection, array((isset($_POST['packagedetailid']) ? $_POST['packagedetailid'] : ''), $_POST['packageid'], $_POST['productid'], $_POST['qty'],
                $_POST['unitofmeasureid'], $_POST['price'], $_POST['isbonus']
            ));
            $transaction->commit();
            GetMessage(false, 'insertsuccess');
        } catch (Exception $e) {
            $transaction->rollBack();
            GetMessage(true, $e->getMessage());
        }
    }
    private function ModifyDataDisc($connection, $arraydata)
    {
        $id = (isset($arraydata[0]) ? $arraydata[0] : '');
        if ($id == '') {
            $sql     = 'call Insertpackagedisc(:vpackageid,:vdiscvalue,:vcreatedby)';
            $command = $connection->createCommand($sql);
        } else {
            $sql     = 'call Updatepackagedisc(:vid,:vpackageid,:vdiscvalue,:vcreatedby)';
            $command = $connection->createCommand($sql);
            $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
        }
        $command->bindvalue(':vpackageid', $arraydata[1], PDO::PARAM_STR);
        $command->bindvalue(':vdiscvalue', $arraydata[2], PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
    }
    public function actionSaveDisc()
    {
        header("Content-Type: application/json");
        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {
            $this->ModifyDataDisc($connection, array((isset($_POST['packagediscid']) ? $_POST['packagediscid'] : ''), $_POST['packageid'], $_POST['discvalue']));
            $transaction->commit();
            GetMessage(false, 'insertsuccess');
        } catch (Exception $e) {
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
                $sql     = 'call DeletePackage(:vid,:vcreatedby)';
                $command = $connection->createCommand($sql);
                foreach ($id as $ids) {
                    $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
                    $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
                    $command->execute();
                }
                $transaction->commit();
                GetMessage(false, 'insertsuccess');
            } catch (Exception $e) {
                $transaction->rollback();
                GetMessage(true, $e->getMessage());
            }
        } else {
            GetMessage(true, 'chooseone');
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
                $sql     = 'call ApprovePackage(:vid,:vcreatedby)';
                $command = $connection->createCommand($sql);
                foreach ($id as $ids) {
                    $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
                    $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
                    $command->execute();
                }
                $transaction->commit();
                //$this->SendNotifWaCustomer($this->menuname,$id);
                GetMessage(false, 'insertsuccess');
            } catch (Exception $e) {
                $transaction->rollback();
                GetMessage(true, $e->getMessage());
            }
        } else {
            GetMessage(true, 'chooseone');
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
                $sql     = 'call Purgepackage(:vid,:vcreatedby)';
                $command = $connection->createCommand($sql);
                foreach ($id as $ids) {
                    $command->bindvalue(':vid', $ids, PDO::PARAM_STR);
                    $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
                    $command->execute();
                }
                $transaction->commit();
                GetMessage(false, 'insertsuccess');
            } catch (Exception $e) {
                $transaction->rollback();
                GetMessage(true, $e->getMessage());
            }
        } else {
            GetMessage(true, 'chooseone');
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
                $sql     = 'call Purgepackagedetail(:vid,:vcreatedby)';
                $command = $connection->createCommand($sql);
                $command->bindvalue(':vid', $id, PDO::PARAM_STR);
                $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
                $command->execute();
                $transaction->commit();
                GetMessage(false, 'insertsuccess');
            } catch (Exception $e) {
                $transaction->rollback();
                GetMessage(true, $e->getMessage());
            }
        } else {
            GetMessage(true, 'chooseone');
        }
    }
    public function actionPurgedisc()
    {
        header("Content-Type: application/json");
        if (isset($_POST['id'])) {
            $id          = $_POST['id'];
            $connection  = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                $sql     = 'call Purgepackagedisc(:vid,:vcreatedby)';
                $command = $connection->createCommand($sql);
                $command->bindvalue(':vid', $id, PDO::PARAM_STR);
                $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
                $command->execute();
                $transaction->commit();
                GetMessage(false, 'insertsuccess');
            } catch (Exception $e) {
                $transaction->rollback();
                GetMessage(true, $e->getMessage());
            }
        } else {
            GetMessage(true, 'chooseone');
        }
    }
    public function actionDownPDF()
    {
        parent::actionDownload();
        $sql = "select a.*
      from packages a
      -- join addressbook b on b.addressbookid = a.addressbookid
		  -- join employee d on d.employeeid = a.employeeid
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		  -- join tax e on e.taxid = a.taxid 
      ";
        if ($_GET['id'] !== '') {
            $sql = $sql . "where a.packageid in (" . $_GET['id'] . ")";
        }
        $command    = $this->connection->createCommand($sql);
        $dataReader = $command->queryAll();
        foreach ($dataReader as $row) {
            $this->pdf->companyid = 0;
        }
        $this->pdf->title = 'Program Paket';
        $this->pdf->AddPage('P', 'A4');
        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
        $this->pdf->SetFont('Tahoma');
        foreach ($dataReader as $row) {
            /*
      if ($row['addressbookid'] != '') {
        $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno, a.lat, a.lng
					from address a
					left join addresstype b on b.addresstypeid = a.addresstypeid
					left join city c on c.cityid = a.cityid
					where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
        $command1    = $this->connection->createCommand($sql1);
        $dataReader1 = $command1->queryAll();
        $phone;
        foreach ($dataReader1 as $row1) {
          $phone = $row1['phoneno'];
        }
      }
      */
            $this->pdf->SetFontSize(10);
            $this->pdf->colalign = array(
                'C',
                'C',
                'C',
                'C'
            );
            $this->pdf->setwidths(array(
                25,
                100,
                30,
                60
            ));
            $this->pdf->row(array(
                'Nama Paket',
                ' : ' . $row['packagename'],
                'No Paket',
                ' : ' . $row['docno']
            ));
            $this->pdf->row(array(
                'Tanggal Mulai',
                ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])),
                'Tanggal Akhir',
                ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate']))
            ));
            $this->pdf->setY($this->pdf->getY() + 5);
            $this->pdf->SetFontSize(8);
            $sql1        = "select a.packageid,c.uomcode,a.qty,a.price as price,(qty * price) as total,b.productname
			from packagedetail a
			left join packages f on f.packageid = a.packageid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
			-- left join currency d on d.currencyid = a.currencyid
			-- left join tax e on e.taxid = f.taxid
			where a.packageid = " . $row['packageid'];
            $command1    = $this->connection->createCommand($sql1);
            $dataReader1 = $command1->queryAll();
            $total       = 0;
            $totalqty    = 0;
            $this->pdf->sety($this->pdf->gety() + 0);
            $this->pdf->colalign = array(
                'C',
                'C',
                'C',
                'C',
                'C',
                'C',
                'C'
            );
            $this->pdf->setwidths(array(
                10,
                90,
                15,
                15,
                getUserObjectValues('pricepkg') == '1' ? 20 : 20,
                30,
                //30
            ));
            $this->pdf->colheader = array(
                'No',
                'Nama Barang',
                'Satuan',
                'Qty',
                getUserObjectValues('pricepkg') == '1' ? 'Harga' : '',
                getUserObjectValues('pricepkg') == '1' ? 'Total' : '',
                //'Quota Paket'
            );
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array(
                'C',
                'L',
                'C',
                'L',
                'R',
                'R',
                'R',
                'L'
            );
            $i = 1;
            foreach ($dataReader1 as $row1) {
                $this->pdf->row(array(
                    $i,
                    $row1['productname'],
                    $row1['uomcode'],
                    Yii::app()->format->formatNumber($row1['qty']),
                    //$row1['itemnote'],
                    getUserObjectValues('pricepkg') == '1' ? Yii::app()->format->formatCurrency($row1['price']) : '',
                    getUserObjectValues('pricepkg') == '1' ? Yii::app()->format->formatCurrency($row1['total']) : '',
                    //date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate']))
                ));
                $i++;
                $total    = $row1['total'] + $total;
                $totalqty = $row1['qty'] + $totalqty;
            }
            $this->pdf->row(array(
                '',
                'Total',
                '',
                Yii::app()->format->formatNumber($totalqty),
                '',
                getUserObjectValues('pricepkg') == '1' ? Yii::app()->format->formatCurrency($total) : '',
            ));
            $sql1        = "select a.discvalue
			from packagedisc a
			where a.packageid = " . $row['packageid'];
            $command1    = $this->connection->createCommand($sql1);
            $dataReader1 = $command1->queryAll();
            $discvalue   = '';
            foreach ($dataReader1 as $row1) {
                if ($discvalue == '') {
                    $discvalue = Yii::app()->format->formatNumber($row1['discvalue']);
                } else {
                    $discvalue = $discvalue . ' + ' . Yii::app()->format->formatNumber($row1['discvalue']);
                }
            }
            $this->pdf->colalign = array(
                'C',
                'C',
                'C',
                'C',
                'C',
                'C'
            );
            $this->pdf->setwidths(array(
                35,
                155,
                155,
                155,
                155,
                155
            ));
            $this->pdf->iscustomborder = false;
            $this->pdf->setbordercell(array(
                'none',
                'none',
                'none',
                'none',
                'none',
                'none'
            ));
            $this->pdf->coldetailalign = array(
                'L',
                'L',
                'L',
                'L',
                'L',
                'L'
            );
            $this->pdf->row(array(
                getUserObjectValues('pricepkg') == '1' ? 'Diskon (%)' : '',
                getUserObjectValues('pricepkg') == '1' ? $discvalue : '',
            ));
            $totalbefdisc = Yii::app()->db->createCommand('select GetTotalBefDiscPackage(' . $row['packageid'] . ')')->queryScalar();
            $hrgaftdisc = Yii::app()->db->createCommand('select GetTotalAmountDiscPackage(' . $row['packageid'] . ')')->queryScalar();
            $this->pdf->colalign = array(
                'C',
                'C',
                'C',
                'C',
                'C',
                'C'
            );
            $this->pdf->setwidths(array(
                35,
                155,
                155,
                155,
                155,
                155
            ));
            $this->pdf->iscustomborder = false;
            $this->pdf->setbordercell(array(
                'none',
                'none',
                'none',
                'none',
                'none',
                'none'
            ));
            $this->pdf->coldetailalign = array(
                'L',
                'L',
                'L',
                'L',
                'L',
                'L'
            );
            $this->pdf->row(array(
                getUserObjectValues('pricepkg') == '1' ? 'Harga Diskon' : '',
                getUserObjectValues('pricepkg') == '1' ? Yii::app()->format->formatNumber($totalbefdisc - $hrgaftdisc) : '',
            ));
            $bilangan = explode(".", $hrgaftdisc);
            $this->pdf->row(array(
                'Harga Sesudah Diskon',
                Yii::app()->format->formatCurrency($hrgaftdisc) . ' (' . eja($bilangan[0]) . ')'
            ));
            $this->pdf->sety($this->pdf->gety());
            $this->pdf->colalign = array(
                'C',
                'C',
                'C',
                'C',
                'C',
                'C'
            );
            $this->pdf->setwidths(array(
                35,
                155,
                155,
                155,
                155,
                155
            ));
            $this->pdf->iscustomborder = false;
            $this->pdf->setbordercell(array(
                'none',
                'none',
                'none',
                'none',
                'none',
                'none'
            ));
            $this->pdf->coldetailalign = array(
                'L',
                'L',
                'L',
                'L',
                'L',
                'L'
            );

            $this->pdf->row(array(
                'Note',
                $row['headernote']
            ));
            $this->pdf->checkNewPage(10);
            $this->pdf->sety($this->pdf->gety() + 5);
            $this->pdf->text(10, $this->pdf->gety(), 'Pembuat');
            $this->pdf->text(50, $this->pdf->gety(), 'Mengetahui');
            $this->pdf->text(10, $this->pdf->gety() + 15, '........................');
            $this->pdf->text(50, $this->pdf->gety() + 15, '........................');
        }
        $this->pdf->Output();
    }
    public function actionDownPDF1()
    {
        parent::actionDownload();
        $sql = "select a.companyid, a.packageid,a.docno, b.fullname as customername, a.docdate, c.paymentname, e.taxcode, e.taxvalue,
			a.addressbookid, a.headernote,a.recordstatus,a.shipto,a.billto,d.fullname as salesname
      from package a
      join addressbook b on b.addressbookid = a.addressbookid
		  join employee d on d.employeeid = a.employeeid
      join paymentmethod c on c.paymentmethodid = a.paymentmethodid
		  join tax e on e.taxid = a.taxid ";
        if ($_GET['id'] !== '') {
            $sql = $sql . "where a.packageid in (" . $_GET['id'] . ")";
        }
        $command    = $this->connection->createCommand($sql);
        $dataReader = $command->queryAll();
        foreach ($dataReader as $row) {
            $this->pdf->companyid = $row['companyid'];
        }
        $this->pdf->title = 'Sales Order';
        $this->pdf->AddPage('P', array(
            220,
            140
        ));
        $this->pdf->AliasNbPages();
        $this->pdf->AddFont('Tahoma', '', 'tahoma.php');
        $this->pdf->SetFont('Tahoma');
        foreach ($dataReader as $row) {
            if ($row['addressbookid'] > 0) {
                $sql1        = "select b.addresstypename, a.addressname, c.cityname, a.phoneno
					from address a
					left join addresstype b on b.addresstypeid = a.addresstypeid
					left join city c on c.cityid = a.cityid
					where addressbookid = " . $row['addressbookid'] . " order by addressid " . " limit 1";
                $command1    = $this->connection->createCommand($sql1);
                $dataReader1 = $command1->queryAll();
                $phone;
                foreach ($dataReader1 as $row1) {
                    $phone = $row1['phoneno'];
                }
            }
            $this->pdf->SetFontSize(8);
            $this->pdf->colalign = array(
                'C',
                'C',
                'C',
                'C'
            );
            $this->pdf->setwidths(array(
                20,
                100,
                30,
                60
            ));
            $this->pdf->row(array(
                'Customer',
                '',
                'Sales Order No',
                ' : ' . $row['docno']
            ));
            $this->pdf->row(array(
                'Name',
                ' : ' . $row['customername'],
                'SO Date',
                ' : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['docdate']))
            ));
            $this->pdf->row(array(
                'Phone',
                ' : ' . $phone,
                'Sales',
                ' : ' . $row['salesname']
            ));
            $this->pdf->row(array(
                'Address',
                ' : ' . $row['shipto'],
                'Payment',
                ' : ' . $row['paymentname']
            ));
            $sql1        = "select a.packageid,c.uomcode,a.qty,a.price * a.currencyrate as price,(qty * price * currencyrate) as total,(e.taxvalue * qty * price * currencyrate/ 100) as ppn,b.productname,
			d.symbol,d.i18n,a.itemnote,a.delvdate
			from packagedetail a
			left join package f on f.packageid = a.packageid 
			left join product b on b.productid = a.productid
			left join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
			left join currency d on d.currencyid = a.currencyid
			left join tax e on e.taxid = f.taxid
			where a.packageid = " . $row['packageid'];
            $command1    = $this->connection->createCommand($sql1);
            $dataReader1 = $command1->queryAll();
            $total       = 0;
            $totalqty    = 0;
            $this->pdf->sety($this->pdf->gety() + 0);
            $this->pdf->colalign = array(
                'C',
                'C',
                'C',
                'C',
                'C'
            );
            $this->pdf->setwidths(array(
                15,
                15,
                110,
                30,
                30
            ));
            $this->pdf->colheader = array(
                'Qty',
                'Units',
                'Description',
                'Item Note',
                'Tgl Kirim'
            );
            $this->pdf->RowHeader();
            $this->pdf->coldetailalign = array(
                'R',
                'C',
                'L',
                'L',
                'R',
                'L'
            );
            foreach ($dataReader1 as $row1) {
                $this->pdf->row(array(
                    Yii::app()->format->formatNumber($row1['qty']),
                    $row1['uomcode'],
                    $row1['productname'],
                    $row1['itemnote'],
                    date(Yii::app()->params['dateviewfromdb'], strtotime($row1['delvdate']))
                ));
                $total    = $row1['total'] + $total;
                $totalqty = $row1['qty'] + $totalqty;
            }
            $this->pdf->row(array(
                Yii::app()->format->formatNumber($totalqty),
                '',
                'Total',
                '',
                ''
            ));
            $this->pdf->sety($this->pdf->gety());
            $this->pdf->colalign = array(
                'C',
                'C',
            );
            $this->pdf->setwidths(array(
                35,
                200,
            ));
            $this->pdf->iscustomborder = false;
            $this->pdf->setbordercell(array(
                'none',
                'none',
            ));
            $this->pdf->coldetailalign = array(
                'L',
                'L',
            );
            $this->pdf->row(array(
                'Ship To',
                $row['shipto']
            ));
            $this->pdf->row(array(
                'Note',
                $row['headernote']
            ));
            $this->pdf->checkNewPage(10);
        }
        $this->pdf->Output();
    }
    public function actionDownxls22()
    {
        parent::actionDownload();
        $sql = "select addressbookid,custreqno,quotno,headernote,recordstatus
			from package a ";
        if ($_GET['id'] !== '') {
            $sql = $sql . "where a.packageid in (" . $_GET['id'] . ")";
        }
        $command    = $this->connection->createCommand($sql);
        $dataReader = $command->queryAll();
        $excel      = Yii::createComponent('application.extensions.PHPExcel.PHPExcel');
        $i          = 1;
        $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, 1, GetCatalog('addressbookid'))->setCellValueByColumnAndRow(1, 1, GetCatalog('custreqno'))->setCellValueByColumnAndRow(2, 1, GetCatalog('quotno'))->setCellValueByColumnAndRow(8, 1, GetCatalog('headernote'))->setCellValueByColumnAndRow(9, 1, GetCatalog('recordstatus'));
        foreach ($dataReader as $row1) {
            $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $i + 1, $row1['addressbookid'])->setCellValueByColumnAndRow(1, $i + 1, $row1['custreqno'])->setCellValueByColumnAndRow(2, $i + 1, $row1['quotno'])->setCellValueByColumnAndRow(8, $i + 1, $row1['headernote'])->setCellValueByColumnAndRow(9, $i + 1, $row1['recordstatus']);
            $i += 1;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="package.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        unset($excel);
    }

    public function actionDownPDF2()
    {
        parent::actionDownload();
        $companyid = 1;
        $startdate = '08/01/2021';
        $enddate = '08/31/2021';
        $per = 1;
        $sloc = $customer = $salesarea = $employee = $product = '';
        $sql = "select distinct packageid , docno, startdate , enddate , packagename , headernote from (select z.*, c.soheaderid, sum(d.qty) as giqty, ifnull((select sum(qty) from sodetail s2 where s2.soheaderid = c.soheaderid ),0) as soqty
            from invoice a
            join giheader b on b.giheaderid = a.giheaderid 
            join soheader c on c.soheaderid = b.soheaderid
            join gidetail d on d.giheaderid = b.giheaderid 
            join (select distinct p.packageid , p.docno, p.startdate , p.enddate , p.packagename , p.headernote 
            from invoice a
            join giheader b on b.giheaderid = a.giheaderid
            join soheader c on c.soheaderid = b.soheaderid
            join addressbook d on d.addressbookid = c.addressbookid
            join paymentmethod e on e.paymentmethodid = c.paymentmethodid
            join sodetail f on f.soheaderid = c.soheaderid
            join sloc g on g.slocid = f.slocid
            join employee h on h.employeeid = c.employeeid
            join product i on i.productid = f.productid
            join salesarea j on j.salesareaid = d.salesareaid
            left join packages p on p.packageid = c.packageid 
            where a.recordstatus = 3 and c.companyid = {$companyid}
            and g.sloccode like '%{$sloc}%'
            and d.fullname like '%{$customer}%' and h.fullname like '%{$employee}%' 
            and i.productname like '%{$product}%'
            and j.areaname like '%{$salesarea}%' and b.gino is not null
            and invoiceno not like '%-%-%' 
            and c.sotype = 2
            and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ) z  on z.packageid = c.packageid
        where a.companyid = {$companyid} and a.recordstatus = 3 and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
        group by b.soheaderid ) zz
        where soqty-giqty>0 ";

        $this->pdf->companyid = $companyid;

        $this->pdf->title = 'Rincian SO Paket Outstanding (Belum Terkirim Semua Barang Dalam Paket)';
        $this->pdf->subtitle = 'Dari Tgl :' . date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($enddate));
        $this->pdf->AddPage('P');

        $grandtotal = 0;
        $totalsisa = 0;
        $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($dataReader as $row) {
            $this->pdf->SetFont('Arial', 'B', 10);
            $this->pdf->text(10, $this->pdf->gety() + 5, 'NO PAKET');
            $this->pdf->text(30, $this->pdf->gety() + 5, ': ' . $row['docno'] . ' (ID:' . $row['packageid'] . ')');
            $this->pdf->text(10, $this->pdf->gety() + 10, $row['packagename']);
            $this->pdf->text(125, $this->pdf->gety() + 5, 'PERIODE : ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate'])));
            $this->pdf->text(125, $this->pdf->gety() + 10, $row['headernote']);

            $subtotal = 0;
            $i = 0;
            $subqty = 0;
            $subgiqty = 0;
            $subsisa = 0;
            $substock = 0;

            $sql1 = "select sono, soheaderid, customer, sales,qtypackage from (select z.*, c.soheaderid , format(c.qtypackage,0) as qtypackage, sum(d.qty) as giqty, (select sum(qty) from sodetail s2 where s2.soheaderid = c.soheaderid ) as soqty,c.sono,
            (select e.fullname from employee e where e.employeeid=c.employeeid) as sales, (select ad.fullname from addressbook ad where ad.addressbookid=c.addressbookid) as customer
            from invoice a
            join giheader b on b.giheaderid = a.giheaderid 
            join soheader c on c.soheaderid = b.soheaderid
            join gidetail d on d.giheaderid = b.giheaderid 
            join (select distinct p.packageid
            from invoice a
            join giheader b on b.giheaderid = a.giheaderid
            join soheader c on c.soheaderid = b.soheaderid
            join addressbook d on d.addressbookid = c.addressbookid
            join paymentmethod e on e.paymentmethodid = c.paymentmethodid
            join sodetail f on f.soheaderid = c.soheaderid
            join sloc g on g.slocid = f.slocid
            join employee h on h.employeeid = c.employeeid
            join product i on i.productid = f.productid
            join salesarea j on j.salesareaid = d.salesareaid
            left join packages p on p.packageid = c.packageid 
            where a.recordstatus = 3 and c.companyid = {$companyid}
            and g.sloccode like '%{$sloc}%'
            and d.fullname like '%{$customer}%' and h.fullname like '%{$employee}%' 
            and i.productname like '%{$product}%'
            and j.areaname like '%{$salesarea}%' and b.gino is not null
            and invoiceno not like '%-%-%' 
            and c.sotype = 2 and c.packageid = {$row['packageid']}
            and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ) z  on z.packageid = c.packageid
            where a.companyid = {$companyid} and a.recordstatus = 3 and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
            group by b.soheaderid ) zz
            where soqty-giqty>0";
            $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
            foreach ($dataReader1 as $row1) {
                $this->pdf->SetFont('Arial', '', 10);
                $this->pdf->text(10, $this->pdf->gety() + 17, $row1['sono'] . ' (' . $row1['qtypackage'] . ') Paket');
                $this->pdf->text(60, $this->pdf->gety() + 17, $row1['sales']);
                $this->pdf->text(125, $this->pdf->gety() + 17, $row1['customer']);

                $sql2 = "select productname, giqty, qty, price, uomcode, (qty-giqty)*price as jumlah, amountafterdisc, stock,if(qty-giqty>0,'*','') as flag from (
                    select b.productname, a.qty, ifnull((select sum(zz.qty) from gidetail zz join giheader za on za.giheaderid = zz.giheaderid where za.soheaderid=f.soheaderid and za.recordstatus=3 and za.gidate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and zz.sodetailid = a.sodetailid),0) as giqty ,c.uomcode,a.price,  GetTotalAmountDiscPendinganSO(a.soheaderid) as amountafterdisc, (select sum(qty) from productstock x where x.productid = a.productid and x.slocid = a.slocid and x.unitofmeasureid = a.unitofmeasureid) as stock
                    from sodetail a 
                    inner join sloc j on j.slocid=a.slocid
                    inner join product b on b.productid = a.productid
                    inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
                    left join currency d on d.currencyid = a.currencyid
                    left join soheader f on f.soheaderid = a.soheaderid 
                    left join tax e on e.taxid = f.taxid
                    join product g on g.productid = a.productid
                    where b.productname like '%" . $product . "%' and g.isstock = 1 and a.soheaderid = '" . $row1['soheaderid'] . "') z 
                    -- where sisa > 0";
                $dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();

                $this->pdf->setY($this->pdf->getY() + 20);
                $this->pdf->setFont('Arial', 'B', 8);
                $this->pdf->colalign = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
                $this->pdf->setwidths(array(10, 62, 15, 15, 15, 15, 25, 25));
                $this->pdf->colheader = array('No', 'Nama Barang', 'Qty', 'Qty Gi', 'Stock', 'Satuan', 'Harga', 'Jumlah');
                $this->pdf->RowHeader();
                $this->pdf->coldetailalign = array('L', 'L', 'R', 'R', 'R', 'C', 'R', 'R');
                $this->pdf->setFont('Arial', '', 8);


                $disc = 0;
                foreach ($dataReader2 as $row2) {
                    if ($row2['flag'] != '') {
                        $this->pdf->SetTextColor(250, 0, 0);
                        $this->pdf->setFont('Arial', 'B', 8);
                    }
                    $i += 1;
                    $this->pdf->row(array(
                        $i, $row2['productname'],
                        Yii::app()->format->formatNumber($row2['qty']),
                        Yii::app()->format->formatNumber($row2['giqty']),
                        Yii::app()->format->formatNumber($row2['stock']),
                        $row2['uomcode'],
                        Yii::app()->format->formatCurrency($row2['price'] / $per),
                        Yii::app()->format->formatCurrency($row2['jumlah'] / $per),
                    ));
                    $this->pdf->checkNewPage(20);
                    $subqty += $row2['qty'];
                    $subgiqty += $row2['giqty'];
                    $subsisa += ($row2['qty'] - $row2['giqty']);
                    $substock += $row2['stock'];
                    $subtotal += $row2['jumlah'];
                    // $disc = ($row2['amountafterdisc']) - $total;
                    $this->pdf->SetTextColor(0, 0, 0);
                }
                $this->pdf->checkNewPage(25);
            }
            $grandtotal += $subtotal;
            $totalsisa += $subsisa;

            $this->pdf->checkNewPage(30);
            $this->pdf->setY($this->pdf->getY() + 5);
            $this->pdf->setwidths(array(10, 77, 20, 30));
            $this->pdf->setFont('Arial', 'B', 10);
            $this->pdf->row(array(
                '', 'Total Pendingan Paket ' . $row['docno'],
                Yii::app()->format->formatCurrency($subsisa),
                Yii::app()->format->formatCurrency($subtotal / $per)
            ));
            $this->pdf->setY($this->pdf->getY() + 5);
        }
        $this->pdf->setY($this->pdf->getY() + 10);
        $this->pdf->setwidths(array(10, 77, 20, 30));
        $this->pdf->setFont('Arial', 'B', 11);
        $this->pdf->row(array(
            '', 'Total Pendingan  ',
            Yii::app()->format->formatCurrency($totalsisa),
            Yii::app()->format->formatCurrency($grandtotal / $per)
        ));
        $this->pdf->Output();
    }

    public function actionDownPDF3()
    {
        parent::actionDownload();
        $companyid = 1;
        $productcollect = 'KAIN SPRINGBED';
        $startdate = '2021-07-10';
        $enddate = '2021-07-17';

        $this->pdf->title = 'Title';
        $this->pdf->subtitle = 'TES';

        $this->pdf->companyid = $companyid;
        $this->pdf->AddPage('P', 'A4');

        $sql = "select a.productname,a.productid
            from product a
            join productcollection b on b.productcollectid = a.productcollectid
            where b.collectionname like '%{$productcollect}%' ";
        $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
        // $i = 5;
        $this->pdf->setFont('Arial', '', 8);

        $sqldel = "delete from getfg where useraccessid = (select useraccessid from useraccess where username = '" . Yii::app()->user->name . "')";
        $q = Yii::app()->db->createCommand($sqldel)->execute();

        foreach ($dataReader as $row) {
            $sql1 = "call getFG({$row['productid']},{$row['productid']},'{$startdate}','{$enddate}','" . Yii::app()->user->name . "')";
            //$this->pdf->setY($this->pdf->getY() + 5);
            Yii::app()->db->createCommand($sql1)->execute();

            $sql1 = "select distinct productid2,productname2 from getfg where useraccessid = (select useraccessid from useraccess where username = '" . Yii::app()->user->name . "') and productid1 = " . $row['productid'];
            $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();
            foreach ($dataReader1 as $row1) {
                $this->pdf->text(10, $this->pdf->gety(), substr($row['productname'], 0, 50) . '-> ' . $row1['productname2']);
                $this->pdf->setY($this->pdf->getY() + 5);
            }
        }

        $this->pdf->Output();
    }
    public function actionDownXLS2()
    {
        $companyid = 1;
        $startdate = '08/01/2021';
        $enddate = '08/31/2021';
        $per = 1;
        $sloc = $customer = $salesarea = $employee = $product = '';
        $this->menuname = 'pendingansopaket';
        parent::actionDownxls();

        $sql = "select distinct packageid , docno, startdate , enddate , packagename , headernote from (select z.*, c.soheaderid, sum(d.qty) as giqty, ifnull((select sum(qty) from sodetail s2 where s2.soheaderid = c.soheaderid ),0) as soqty
            from invoice a
            join giheader b on b.giheaderid = a.giheaderid 
            join soheader c on c.soheaderid = b.soheaderid
            join gidetail d on d.giheaderid = b.giheaderid 
            join (select distinct p.packageid , p.docno, p.startdate , p.enddate , p.packagename , p.headernote 
            from invoice a
            join giheader b on b.giheaderid = a.giheaderid
            join soheader c on c.soheaderid = b.soheaderid
            join addressbook d on d.addressbookid = c.addressbookid
            join paymentmethod e on e.paymentmethodid = c.paymentmethodid
            join sodetail f on f.soheaderid = c.soheaderid
            join sloc g on g.slocid = f.slocid
            join employee h on h.employeeid = c.employeeid
            join product i on i.productid = f.productid
            join salesarea j on j.salesareaid = d.salesareaid
            left join packages p on p.packageid = c.packageid 
            where a.recordstatus = 3 and c.companyid = {$companyid}
            and g.sloccode like '%{$sloc}%'
            and d.fullname like '%{$customer}%' and h.fullname like '%{$employee}%' 
            and i.productname like '%{$product}%'
            and j.areaname like '%{$salesarea}%' and b.gino is not null
            and invoiceno not like '%-%-%' 
            and c.sotype = 2
            and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ) z  on z.packageid = c.packageid
        where a.companyid = {$companyid} and a.recordstatus = 3 and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
        group by b.soheaderid ) zz
        where soqty-giqty>0 ";
        $dataReader = Yii::app()->db->createCommand($sql)->queryAll();

        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($startdate)))
            ->setCellValueByColumnAndRow(3, 2, date(Yii::app()->params['dateviewfromdb'], strtotime($enddate)))
            ->setCellValueByColumnAndRow(6, 1, GetCompanyCode($companyid));
        $line = 4;

        $grandtotal = 0;
        $totalsisa = 0;
        $dataReader = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($dataReader as $row) {
            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0, $line, 'NO Paket')
                ->setCellValueByColumnAndRow(1, $line, $row['docno'] . ' (ID:' . $row['packageid'] . ')')
                ->setCellValueByColumnAndRow(6, $line, 'PERIODE :')
                ->setCellValueByColumnAndRow(7, $line, date(Yii::app()->params['dateviewfromdb'], strtotime($row['startdate'])) . ' s/d ' . date(Yii::app()->params['dateviewfromdb'], strtotime($row['enddate'])));
            $line++;

            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(0, $line, $row['packagename'])
                ->setCellValueByColumnAndRow(6, $line, $row['headernote']);
            $line++;

            $subtotal = 0;
            $i = 0;
            $subqty = 0;
            $subgiqty = 0;
            $subsisa = 0;
            $substock = 0;

            $sql1 = "select sono, soheaderid, customer, sales,qtypackage from (select z.*, c.soheaderid , format(c.qtypackage,0) as qtypackage, sum(d.qty) as giqty, (select sum(qty) from sodetail s2 where s2.soheaderid = c.soheaderid ) as soqty,c.sono,
                (select e.fullname from employee e where e.employeeid=c.employeeid) as sales, (select ad.fullname from addressbook ad where ad.addressbookid=c.addressbookid) as customer
                from invoice a
                join giheader b on b.giheaderid = a.giheaderid 
                join soheader c on c.soheaderid = b.soheaderid
                join gidetail d on d.giheaderid = b.giheaderid 
                join (select distinct p.packageid
                from invoice a
                join giheader b on b.giheaderid = a.giheaderid
                join soheader c on c.soheaderid = b.soheaderid
                join addressbook d on d.addressbookid = c.addressbookid
                join paymentmethod e on e.paymentmethodid = c.paymentmethodid
                join sodetail f on f.soheaderid = c.soheaderid
                join sloc g on g.slocid = f.slocid
                join employee h on h.employeeid = c.employeeid
                join product i on i.productid = f.productid
                join salesarea j on j.salesareaid = d.salesareaid
                left join packages p on p.packageid = c.packageid 
                where a.recordstatus = 3 and c.companyid = {$companyid}
                and g.sloccode like '%{$sloc}%'
                and d.fullname like '%{$customer}%' and h.fullname like '%{$employee}%' 
                and i.productname like '%{$product}%'
                and j.areaname like '%{$salesarea}%' and b.gino is not null
                and invoiceno not like '%-%-%' 
                and c.sotype = 2 and c.packageid = {$row['packageid']}
                and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' ) z  on z.packageid = c.packageid
                where a.companyid = {$companyid} and a.recordstatus = 3 and a.invoicedate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "'
                group by b.soheaderid ) zz
                where soqty-giqty>0";
            $dataReader1 = Yii::app()->db->createCommand($sql1)->queryAll();

            foreach ($dataReader1 as $row1) {
                $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0, $line, $row1['sono'] . ' (' . $row1['qtypackage'] . ') Paket')
                    ->setCellValueByColumnAndRow(3, $line, $row1['sales'])
                    ->setCellValueByColumnAndRow(6, $line, $row1['customer']);
                $line++;

                $sql2 = "select productname, giqty, qty, price, uomcode, (qty-giqty)*price as jumlah, amountafterdisc, stock,if(qty-giqty>0,'*','') as flag from (
                    select b.productname, a.qty, ifnull((select sum(zz.qty) from gidetail zz join giheader za on za.giheaderid = zz.giheaderid where za.soheaderid=f.soheaderid and za.recordstatus=3 and za.gidate between '" . date(Yii::app()->params['datetodb'], strtotime($startdate)) . "' and '" . date(Yii::app()->params['datetodb'], strtotime($enddate)) . "' and zz.sodetailid = a.sodetailid),0) as giqty ,c.uomcode,a.price,  GetTotalAmountDiscPendinganSO(a.soheaderid) as amountafterdisc, (select sum(qty) from productstock x where x.productid = a.productid and x.slocid = a.slocid and x.unitofmeasureid = a.unitofmeasureid) as stock
                    from sodetail a 
                    inner join sloc j on j.slocid=a.slocid
                    inner join product b on b.productid = a.productid
                    inner join unitofmeasure c on c.unitofmeasureid = a.unitofmeasureid
                    left join currency d on d.currencyid = a.currencyid
                    left join soheader f on f.soheaderid = a.soheaderid 
                    left join tax e on e.taxid = f.taxid
                    join product g on g.productid = a.productid
                    where b.productname like '%" . $product . "%' and g.isstock = 1 and a.soheaderid = '" . $row1['soheaderid'] . "') z 
                    -- where sisa > 0";
                $dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();

                $this->phpExcel->setActiveSheetIndex(0)
                    ->setCellValueByColumnAndRow(0, $line, 'No')
                    ->setCellValueByColumnAndRow(1, $line, 'Nama Barang')
                    ->setCellValueByColumnAndRow(2, $line, 'Qty')
                    ->setCellValueByColumnAndRow(3, $line, 'Qty Gi')
                    ->setCellValueByColumnAndRow(4, $line, 'Stock')
                    ->setCellValueByColumnAndRow(5, $line, 'Satuan')
                    ->setCellValueByColumnAndRow(6, $line, 'Harga')
                    ->setCellValueByColumnAndRow(7, $line, 'Jumlah');
                $line++;

                foreach ($dataReader2 as $row2) {
                    $i += 1;
                    $this->phpExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(0, $line, $i)
                        ->setCellValueByColumnAndRow(1, $line, $row2['productname'])
                        ->setCellValueByColumnAndRow(2, $line, $row2['qty'])
                        ->setCellValueByColumnAndRow(3, $line, $row2['giqty'])
                        ->setCellValueByColumnAndRow(4, $line, $row2['stock'])
                        ->setCellValueByColumnAndRow(5, $line, $row2['uomcode'])
                        ->setCellValueByColumnAndRow(6, $line, $row2['price'])
                        ->setCellValueByColumnAndRow(7, $line, $row2['jumlah']);
                    $line++;

                    $subqty += $row2['qty'];
                    $subgiqty += $row2['giqty'];
                    $subsisa += ($row2['qty'] - $row2['giqty']);
                    $substock += $row2['stock'];
                    $subtotal += $row2['jumlah'];
                }
            }
            $line++;
            $grandtotal += $subtotal;
            $totalsisa += $subsisa;

            $this->phpExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(1, $line, 'Total Pendingan Paket ' . $row['docno'])
                ->setCellValueByColumnAndRow(3, $line, $subsisa)
                ->setCellValueByColumnAndRow(6, $line, $subtotal);
            $line = $line + 2;
        }

        $line = $line + 2;
        $this->phpExcel->setActiveSheetIndex(0)
            ->setCellValueByColumnAndRow(1, $line, 'Total Pendingan')
            ->setCellValueByColumnAndRow(3, $line, $totalsisa)
            ->setCellValueByColumnAndRow(6, $line, $grandtotal);
        $line++;
        $this->getFooterXLS($this->phpExcel);
    }
}
