<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addressbookid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('common/customer/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('common/customer/getData'),
	'saveurl'=>Yii::app()->createUrl('common/customer/save'),
	'destroyurl'=>Yii::app()->createUrl('common/customer/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/customer/upload'),
    'uploadurl2'=>Yii::app()->createUrl('common/customer/uploadcustomerinfo'),
	'upload2text'=>' Upload Info Disc & TOP Customer',
	'downpdf'=>Yii::app()->createUrl('common/customer/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/customer/downxls'),
	'columns'=>"
		{
			field:'addressbookid',
			title:'". GetCatalog('addressbookid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'". GetCatalog('fullname') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxid',
			title:'". GetCatalog('taxcode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.taxcode;
		}},
		{
			field:'taxno',
			title:'". GetCatalog('taxno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ktpno',
			title:'". GetCatalog('ktpno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
    {
			field:'husbandbirthdate',
			title:'". GetCatalog('husbandbirthdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
    {
			field:'wifebirthdate',
			title:'". GetCatalog('wifebirthdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
    {
			field:'weddingdate',
			title:'". GetCatalog('weddingdate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'creditlimit',
			title:'". GetCatalog('creditlimit') ."',
			sortable: true,
			align:'right',
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'currentlimit',
			title:'". GetCatalog('currentlimit') ."',
			sortable: true,
			align:'right',
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'paymentmethodid',
			title:'". GetCatalog('paycode') ."',
			sortable: true,
			align:'right',
			width:'80px',
			formatter: function(value,row,index){
				return row.paycode;
		}}, 
		{
			field:'overdue',
			title:'". GetCatalog('overdue') ."',
			sortable: true,
			align:'right',
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'isstrictlimit',
			title:'". GetCatalog('isstrictlimit') ."',
			align:'center',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'salesareaid',
			title:'". GetCatalog('salesarea') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.areaname;
		}},
    {
			field:'provinceid',
			title:'". GetCatalog('province') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.provincename;
		}},
    {
			field:'marketareaid',
			title:'". GetCatalog('marketarea') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.marketname;
		}},
    {
			field:'customertypeid',
			title:'". GetCatalog('customertype') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.customertypename;
		}},
		{
			field:'pricecategoryid',
			title:'". GetCatalog('pricecategory') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.categoryname;
		}},
		{
			field:'groupcustomerid',
			title:'". GetCatalog('groupcustomer') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.groupname;
		}},
		{
			field:'custcategoryid',
			title:'". GetCatalog('custcategory') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.custcategoryname;
		}},
		{
			field:'custgradeid',
			title:'". GetCatalog('custgrade') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.custgradename;
		}},
		{
			field:'bankaccountno',
			title:'". GetCatalog('bankaccountno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankname',
			title:'". GetCatalog('bankname') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountowner',
			title:'". GetCatalog('accountowner') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatuscustomer',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
								return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
								return '';
				}
		}}",
	'searchfield'=> array ('addressbookid','fullname','bankname','accountowner','areaname','groupcustomer','custcategory','recordstatus'),
	'headerform'=> "
		<input type='hidden' id='addressbookid' name='addressbookid'></input>
		<table cellpadding='5'>
			<tr>
				<td>". GetCatalog('fullname')."</td>
				<td><input class='easyui-textbox' name='fullname' style='width:400px' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('taxcode')."</td>
				<td><select class='easyui-combogrid' name='taxid' style='width:400px' data-options=\"
							panelWidth: '500px',
							idField: 'taxid',
							textField: 'taxcode',
							required:true,
							url: '". Yii::app()->createUrl('accounting/tax/index',array('grid'=>true)) ."',
							queryParams:{
								combo:true
							},
							method: 'get',
							mode: 'remote',
							pagination:true,
							columns: [[
									{field:'taxid',title:'". GetCatalog('taxid') ."',width:'80px'},
									{field:'taxcode',title:'". GetCatalog('taxcode') ."',width:'150px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('taxno')."</td>
				<td><input class='easyui-textbox' name='taxno' ></input></td>
			</tr>
      <tr>
				<td>". GetCatalog('ktpno')."</td>
				<td><input class='easyui-textbox' name='ktpno' ></input></td>
			</tr>
      <tr>
				<td>". GetCatalog('husbandbirthdate')."</td>
				<td><input class='easyui-datebox' name='husbandbirthdate' ></input></td>
			</tr>
      <tr>
				<td>". GetCatalog('wifebirthdate')."</td>
				<td><input class='easyui-datebox' name='wifebirthdate' ></input></td>
			</tr>
      <tr>
				<td>". GetCatalog('weddingdate')."</td>
				<td><input class='easyui-datebox' name='weddingdate' ></input></td>
			</tr>
      <tr>
				<td>". GetCatalog('creditlimit')."</td>
				<td><input id='creditlimit' type='numberbox' name='creditlimit' class='easyui-numberbox'				
				data-options=\"precision:2,decimalSeparator:',',groupSeparator:'.'\" value='0' style='width:250px'></select></td>
			</tr>
			<tr>
				<td>". GetCatalog('isstrictlimit')."</td>
				<td><input id='isstrictlimit' name='isstrictlimit' type='checkbox'></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('paymentmethod')."</td>
				<td><select class='easyui-combogrid' name='paymentmethodid' style='width:400px' data-options=\"
							panelWidth: '500px',
							idField: 'paymentmethodid',
							textField: 'paycode',
							required:true,
							url: '". Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true)) ."',
							queryParams:{
								combo:true
							},
							method: 'get',
							mode: 'remote',
							pagination:true,
							columns: [[
									{field:'paymentmethodid',title:'". GetCatalog('paymentmethodid') ."',width:'80px'},
									{field:'paycode',title:'". GetCatalog('paycode') ."',width:'150px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('overdue')."</td>
				<td><input id='creditlimit' type='numberbox' name='overdue' class='easyui-numberbox'
				data-options=\"precision:2,decimalSeparator:',',groupSeparator:'.'\" value='0' style='width:250px'></select></td>
			</tr>
			<tr>
				<td>". GetCatalog('salesarea')."</td>
				<td><select class='easyui-combogrid' name='salesareaid' style='width:400px' data-options=\"
							panelWidth: '500px',
							idField: 'salesareaid',
							textField: 'areaname',
							required:true,
							url: '". Yii::app()->createUrl('common/salesarea/index',array('grid'=>true)) ."',
							queryParams:{
								combo:true
							},
							method: 'get',
							mode: 'remote',
							pagination:true,
							columns: [[
									{field:'salesareaid',title:'". GetCatalog('salesareaid') ."',width:'80px'},
									{field:'areaname',title:'". GetCatalog('areaname') ."',width:'400px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
      <tr>
				<td>". GetCatalog('pricecategory')."</td>
				<td><select class='easyui-combogrid' name='pricecategoryid' style='width:400px' data-options=\"
							panelWidth: '500px',
							idField: 'pricecategoryid',
							textField: 'categoryname',
							required:true,
							url: '". Yii::app()->createUrl('common/pricecategory/index',array('grid'=>true)) ."',
							queryParams:{
								combo:true
							},
							method: 'get',
							mode: 'remote',
							pagination:true,
							columns: [[
									{field:'pricecategoryid',title:'". GetCatalog('pricecategoryid') ."',width:'80px'},
									{field:'categoryname',title:'". GetCatalog('categoryname') ."',width:'400px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
      <tr>
				<td>". GetCatalog('province')."</td>
				<td><select class='easyui-combogrid' name='provinceid' style='width:400px' data-options=\"
							panelWidth: '500px',
							idField: 'provinceid',
							textField: 'provincename',
							required:true,
							url: '". Yii::app()->createUrl('admin/province/index',array('grid'=>true)) ."',
							queryParams:{
								combo:true
							},
							method: 'get',
							mode: 'remote',
							pagination:true,
							columns: [[
									{field:'provinceid',title:'". GetCatalog('provinceid') ."',width:'80px'},
									{field:'provincename',title:'". GetCatalog('provincename') ."',width:'400px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('marketarea')."</td>
				<td><select class='easyui-combogrid' name='marketareaid' style='width:250px' data-options=\"
						panelWidth: 500,
						idField: 'marketareaid',
						textField: 'marketname',
						required:true,
						url: '". Yii::app()->createUrl('common/marketarea/index',array('grid'=>true)) ."',
						queryParams:{
							combo:true
						},
						method: 'get',
						mode: 'remote',
						pagination:true,
						columns: [[
								{field:'marketareaid',title:'". GetCatalog('marketareaid') ."',width:80},
								{field:'marketname',title:'". GetCatalog('marketname') ."',width:120},
						]],
						fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('customertype')."</td>
				<td><select class='easyui-combogrid' name='customertypeid' style='width:250px' data-options=\"
						panelWidth: 500,
						idField: 'customertypeid',
						textField: 'customertypename',
						required:true,
						url: '". Yii::app()->createUrl('common/customertype/index',array('grid'=>true)) ."',
						queryParams:{
							combo:true
						},
						method: 'get',
						mode: 'remote',
						pagination:true,
						columns: [[
								{field:'customertypeid',title:'". GetCatalog('customertypeid') ."',width:80},
								{field:'customertypename',title:'". GetCatalog('customertypename') ."',width:120},
						]],
						fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('bankaccountno')."</td>
				<td><input class='easyui-textbox' name='bankaccountno' ></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('bankname')."</td>
				<td><input class='easyui-textbox' name='bankname' ></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('accountowner')."</td>
				<td><input class='easyui-textbox' name='accountowner' ></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('groupcustomer')."</td>
				<td><select class='easyui-combogrid' name='groupcustomerid' style='width:250px' data-options=\"
					panelWidth: '500px',
					idField: 'groupcustomerid',
					textField: 'groupname',
					required:true,
					url: '". Yii::app()->createUrl('common/groupcustomer/index',array('grid'=>true)) ."',
					queryParams:{
						combo:true
					},
					method: 'get',
					mode: 'remote',
					pagination:true,
					columns: [[
							{field:'groupcustomerid',title:'". GetCatalog('groupcustomerid') ."',width:'80px'},
							{field:'groupname',title:'". GetCatalog('groupname') ."',width:'150px'},
					]],
					fitColumns: true\">
				</select></td>
			</tr>
				<td>". GetCatalog('custcategory')."</td>
				<td><select class='easyui-combogrid' name='custcategoryid' style='width:250px' data-options=\"
					panelWidth: '500px',
					idField: 'custcategoryid',
					textField: 'custcategoryname',
					required:true,
					url: '". Yii::app()->createUrl('common/custcategory/index',array('grid'=>true)) ."',
					queryParams:{
						combo:true
					},
					method: 'get',
					mode: 'remote',
					pagination:true,
					columns: [[
							{field:'custcategoryid',title:'". GetCatalog('custcategoryid') ."',width:'80px'},
							{field:'custcategoryname',title:'". GetCatalog('custcategoryname') ."',width:'150px'},
					]],
					fitColumns: true\">
				</select></td>
			</tr>
				<td>". GetCatalog('custgrade')."</td>
				<td><select class='easyui-combogrid' name='custgradeid' style='width:250px' data-options=\"
					panelWidth: '500px',
					idField: 'custgradeid',
					textField: 'custgradename',
					required:true,
					url: '". Yii::app()->createUrl('common/custgrade/index',array('grid'=>true)) ."',
					queryParams:{
						combo:true
					},
					method: 'get',
					mode: 'remote',
					pagination:true,
					columns: [[
							{field:'custgradeid',title:'". GetCatalog('custgradeid') ."',width:'80px'},
							{field:'custgradename',title:'". GetCatalog('custgradename') ."',width:'100px'},
							{field:'description',title:'". GetCatalog('description') ."',width:'300px'},
					]],
					fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='recordstatuscustomer' name='recordstatuscustomer' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		if (data.recordstatuscustomer == 1)
		{
			$('#recordstatuscustomer').prop('checked', true);
		} else
		{
			$('#recordstatuscustomer').prop('checked', false);
		}
		if (data.isstrictlimit == 1)
		{
			$('#isstrictlimit').prop('checked', true);
		} else
		{
			$('#isstrictlimit').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'address',
			'idfield'=>'addressid',
			'urlsub'=>Yii::app()->createUrl('common/customer/indexaddress',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/customer/searchaddress',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/customer/saveaddress',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/customer/saveaddress',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/customer/purgeaddress',array('grid'=>true)),
			'subs'=>"
				{field:'addresstypename',title:'". GetCatalog('addresstypename') ."',width:'200px'},
				{field:'addressname',title:'". GetCatalog('addressname') ."',width:'200px'},
				{field:'rt',title:'". GetCatalog('rt') ."',width:'200px'},
				{field:'rw',title:'". GetCatalog('rw') ."',width:'200px'},
				{field:'cityname',title:'". GetCatalog('cityname') ."',width:'200px'},
				{field:'phoneno',title:'". GetCatalog('phoneno') ."',width:'200px'},
				{field:'faxno',title:'". GetCatalog('faxno') ."',width:'200px'},
				{field:'lat',title:'". GetCatalog('lat') ."',width:'100px'},
				{field:'lng',title:'". GetCatalog('lng') ."',width:'100px'}
			",
			'columns'=>"
				{
					field:'addressbookid',
					title:'". GetCatalog('addressbookid') ."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addressid',
					title:'". GetCatalog('addressid') ."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addresstypeid',
					title:'". GetCatalog('addresstype') ."',
					width:'200px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'addresstypeid',
							textField:'addresstypename',
							pagination:true,
							url:'". $this->createUrl('addresstype/index',array('grid'=>true)) ."',
							fitColumns:true,
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'addresstypeid',title:'". GetCatalog('addresstypeid')."',width:'80px'},
								{field:'addresstypename',title:'". GetCatalog('addresstypename')."',width:'200px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.addresstypename;
					}
				},
				{
					field:'addressname',
					title:'". GetCatalog('addressname') ."',
					width:'200px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rt',
					title:'". GetCatalog('rt') ."',
					width:'50px',
					editor:'numberbox',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rw',
					title:'". GetCatalog('rw') ."',
					width:'50px',
					editor:'numberbox',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'cityid',
					title:'". GetCatalog('city') ."',
					width:'150px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'cityid',
							textField:'cityname',
							pagination:true,
							url:'". Yii::app()->createUrl('admin/city/index',array('grid'=>true)) ."',
							fitColumns:true,
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'cityid',title:'". GetCatalog('cityid')."',width:'50px'},
								{field:'cityname',title:'". GetCatalog('cityname')."',width:'200px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.cityname;
					}
				},
				{
					field:'phoneno',
					title:'". GetCatalog('phoneno') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'faxno',
					title:'". GetCatalog('faxno') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lat',
					title:'". GetCatalog('lat') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lng',
					title:'". GetCatalog('lng') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				}
			"
		),
		array(
			'id'=>'addresscontact',
			'idfield'=>'addresscontactid',
			'urlsub'=>Yii::app()->createUrl('common/customer/indexcontact',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('common/customer/searchcontact',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/customer/savecontact',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/customer/savecontact',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/customer/purgecontact',array('grid'=>true)),
			'subs'=>"
				{field:'contacttypename',title:'". GetCatalog('contacttypename') ."',width:'200px'},
				{field:'addresscontactname',title:'". GetCatalog('addresscontactname') ."',width:'200px'},
				{field:'phoneno',title:'". GetCatalog('phoneno') ."',width:'100px'},
				{field:'mobilephone',title:'". GetCatalog('mobilephone') ."',width:'100px'},
				{field:'wanumber',title:'". GetCatalog('wanumber') ."',width:'100px'},
				{field:'telegramid',title:'". GetCatalog('telegramid') ."',width:'100px'},
				{field:'emailaddress',title:'". GetCatalog('emailaddress') ."',width:'200px'}
			",
			'columns'=>"
			{
				field:'addressbookid',
				width:'50px',
				hidden:true,
				formatter: function(value,row,index){
									return value;
				}
			},
			{
				field:'addresscontactid',
				title:'". GetCatalog('addresscontactid') ."',
				width:'50px',
				sortable: true,
				formatter: function(value,row,index){
									return value;
				}
			},
			{
				field:'contacttypeid',
				title:'". GetCatalog('contacttype') ."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'contacttypeid',
						textField:'contacttypename',
						url:'". $this->createUrl('contacttype/index',array('grid'=>true)) ."',
						fitColumns:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'contacttypeid',title:'". GetCatalog('contacttypeid')."',width:'80px'},
							{field:'contacttypename',title:'". GetCatalog('contacttypename')."',width:'200px'}
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
										return row.contacttypename;
				}
			},
			{
				field:'addresscontactname',
				title:'". GetCatalog('addresscontactname') ."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			},
			{
				field:'phoneno',
				title:'". GetCatalog('phoneno') ."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			},
			{
				field:'mobilephone',
				title:'". GetCatalog('mobilephone') ."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			},
			{
				field:'wanumber',
				title:'". GetCatalog('wanumber') ."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			},
			{
				field:'telegramid',
				title:'". GetCatalog('telegramid') ."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			},
			{
				field:'emailaddress',
				title:'". GetCatalog('emailaddress') ."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			}	
			"
		),
		array(
			'id'=>'customerdisc',
			'idfield'=>'custdiscid',
			'urlsub'=>Yii::app()->createUrl('common/customer/indexdiscount',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('common/customer/searchdisc',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/customer/savedisc',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/customer/savedisc',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/customer/purgedisc',array('grid'=>true)),
			'subs'=>"
        {field:'description',title:'". GetCatalog('materialtype') ."',width:'200px'},
				{field:'discvalue',title:'".GetCatalog('discvalue')."',width:'200px'},
				{field:'sopaycode',title:'".GetCatalog('sopaymethod')."',width:'200px'},
				{field:'realpaycode',title:'".GetCatalog('realpaymethod')."',width:'200px',hidden:". GetMenuAuth('realpayment')."}
			",
			'columns'=>"
				{
					field:'addressbookid',
					title:'". GetCatalog('addressbookid') ."',
					width:'50px',
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
        {
            field:'materialtypeid',
            title:'". GetCatalog('materialtype') ."',
            width:'150px',
            editor:{
                type:'combogrid',
                options:{
                    panelWidth:'500px',
                    mode : 'remote',
                    method:'get',
                    idField:'materialtypeid',
                    textField:'description',
                    pagination:true,
                    url:'". $this->createUrl('materialtype/indextrx',array('grid'=>true)) ."',
                    queryParams:{
                        combo:true
                    },
                    fitColumns:true,
                    loadMsg: '". GetCatalog('pleasewait')."',
                    columns:[[
                        {field:'materialtypeid',title:'". GetCatalog('materialtypeid')."',width:'80px'},
                        {field:'description',title:'". GetCatalog('description')."',width:'200px'}
                    ]]
                }	
            },
            sortable: true,
            formatter: function(value,row,index){
                    return row.description ;
            }
        },
				{
					field:'discvalue',
					title:'". GetCatalog('discvalue') ."',
					width:'150px',
					editor:'textbox',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
        {
            field:'sopaymethodid',
            title:'". GetCatalog('sopaymethod') ."',
            width:'150px',
            editor:{
                type:'combogrid',
                options:{
                    panelWidth:'500px',
                    mode : 'remote',
                    method:'get',
                    idField:'paymentmethodid',
                    textField:'paycode',
                    pagination:true,
                    url:'". Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true)) ."',
                    queryParams:{
                        combo:true
                    },
                    fitColumns:true,
                    loadMsg: '". GetCatalog('pleasewait')."',
                    columns:[[
                        {field:'paymentmethodid',title:'". GetCatalog('ID')."',width:'80px'},
                        {field:'paycode',title:'". GetCatalog('paycode')."',width:'200px'}
                    ]]
                }	
            },
            sortable: true,
            formatter: function(value,row,index){
                    return row.sopaycode ;
            }
        },
        {
          field:'realpaymethodid',
          title:'". GetCatalog('realpaymethod') ."',
          width:'150px',
          editor:{
              type:'combogrid',
              options:{
                  panelWidth:'500px',
                  mode : 'remote',
                  method:'get',
                  idField:'paymentmethodid',
                  textField:'paycode',
                  pagination:true,
                  url:'". Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true)) ."',
                  queryParams:{
                      combo:true
                  },
                  fitColumns:true,
                  loadMsg: '". GetCatalog('pleasewait')."',
                  columns:[[
                      {field:'paymentmethodid',title:'". GetCatalog('ID')."',width:'80px'},
                      {field:'paycode',title:'". GetCatalog('paycode')."',width:'200px'}
                  ]]
              },	
          },
          sortable: true,
          formatter: function(value,row,index){
            return row.realpaycode ;
          }
      },"
		),
    array(
      'id'=>'customerpotensi',
      'idfield'=>'addresspotensiid',
      'urlsub'=>Yii::app()->createUrl('common/customer/indexpotensi',array('grid'=>true)),
      'url'=> Yii::app()->createUrl('common/customer/searchpotensi',array('grid'=>true)),
      'saveurl'=>Yii::app()->createUrl('common/customer/savepotensi',array('grid'=>true)),
      'updateurl'=>Yii::app()->createUrl('common/customer/savepotensi',array('grid'=>true)),
      'destroyurl'=>Yii::app()->createUrl('common/customer/purgepotensi',array('grid'=>true)),
      'subs'=>"
        {field:'groupname',title:'". GetCatalog('groupname') ."',width:'200px'},
        {field:'amount',title:'".GetCatalog('amount')."',width:'250px'},
        {field:'recordstatus',title:'".GetCatalog('recordstatus')."',width:'80px'},
      ",
      'columns'=>"
      {
        field:'addressbookid',
        title:'". GetCatalog('addressbookid') ."',
        width:'50px',
        hidden:true,
        formatter: function(value,row,index){
          return value;
        }
      },
      {
        field:'grouplineid',
        title:'". GetCatalog('groupline') ."',
        width:'150px',
        editor:{
          type:'combogrid',
          options:{
              panelWidth:'500px',
              mode : 'remote',
              method:'get',
              idField:'grouplineid',
              textField:'groupname',
              pagination:true,
              url:'". $this->createUrl('groupline/index',array('grid'=>true)) ."',
              queryParams:{
                  combo:true
              },
              fitColumns:true,
              loadMsg: '". GetCatalog('pleasewait')."',
              columns:[[
                  {field:'grouplineid',title:'". GetCatalog('grouplineid')."',width:'80px'},
                  {field:'gruopname',title:'". GetCatalog('groupname')."',width:'200px'}
              ]]
          }	
        },
        sortable: true,
        formatter: function(value,row,index){
          return row.groupname ;
        }
      },
      {
        field:'amount',
        title:'". GetCatalog('amount') ."',
        width:'250px',
        editor:'textbox',
        sortable: true,
        formatter: function(value,row,index){
          return value;
        }
      },
      {
        field:'recordstatus',
        title:'". GetCatalog('recordstatus') ."',
        align:'center',
        width:'50px',
        editor:{type:'checkbox',options:{on:'1',off:'0'}},
        sortable: true,
        formatter: function(value,row,index) {
          if (value == 1){
            return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
          } else {
            return '';
          }
        }
      },
      "
    )
	),	
));