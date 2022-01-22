<input type="hidden" name="company" id="company" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'accountid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/account/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/account/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/account/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/account/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/account/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/account/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/account/downxls'),
	'columns'=>"
		{
			field:'accountid',
			title:'". GetCatalog('accountid') ."',
			sortable: true,
			width:'60px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:'". GetCatalog('company') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'". Yii::app()->createUrl('admin/company/indexcombo',array('grid'=>true)) ."',
					onBeforeLoad: function(param) {
						var row = $('#dg-account').edatagrid('getSelected');
						if(row==null){
							$(\"input[name='company']\").val('0');
						}
					},
					onSelect: function(index,row){
						var company = row.companyid;
						$(\"input[name='company']\").val(row.companyid);
					},
					fitColumns:true,
					pagination:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'". GetCatalog('companyid')."',width:'50px'},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:'300px'},
					]]
				}	
			},
			width:'350px',
			sortable: true,
			formatter: function(value,row,index){
			return row.companyname;
		}},
		{
			field:'accountcode',
			title:'". GetCatalog('accountcode') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountname',
			title:'". GetCatalog('accountname') ."',
			editor:'text',
			width:'320px',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'parentaccountid',
			title:'". GetCatalog('parentaccount') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountcode',
					pagination:true,
					url:'". $this->createUrl('account/index',array('grid'=>true)) ."',
					queryParams:{
						trxacc:true
					},
					fitColumns:true,
					onBeforeLoad: function(param) {
						var company = $(\"input[name='company']\").val();
						if(company==''){
							var row = $('#dg-account').datagrid('getSelected');
							param.companyid = row.companyid;
							}else{
							param.companyid = $(\"input[name='company']\").val(); }
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'50px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('company')."',width:'200px'},
					]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.parentaccountcode;
		}},
		{
			field:'currencyid',
			title:'". GetCatalog('currency') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					pagination:true,
					queryParams:{
						combo:true
					},
					url:'". Yii::app()->createUrl('admin/currency/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'currencyid',title:'". GetCatalog('currencyid')."',width:50},
						{field:'symbol',title:'". GetCatalog('symbol')."',width:50},
						{field:'currencyname',title:'". GetCatalog('currencyname')."',width:200},
					]]
				}	
			},
			width:'90px',
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'accounttypeid',
			title:'". GetCatalog('accounttype') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'accounttypeid',
						textField:'accounttypename',
						pagination:true,
						queryParams:{
							combo:true
						},
						url:'". $this->createUrl('accounttype/index',array('grid'=>true)) ."',
						fitColumns:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'accounttypeid',title:'". GetCatalog('accounttypeid')."',width:'50px'},
							{field:'accounttypename',title:'". GetCatalog('accounttypename')."',width:'200px'},
						]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accounttypename;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'searchfield'=> array ('accountid','companyname','accountcode','accountname','accounttypename','parentaccountcode','parentaccountname','currencyname','recordstatus')
));