<input type="hidden" name="company" id="company" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addressaccountid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/addressaccount/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/addressaccount/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/addressaccount/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/addressaccount/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/addressaccount/upload'),
	'downpdf'=>Yii::app()->createUrl('common/addressaccount/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/addressaccount/downxls'),
	'columns'=>"
		{
			field:'addressaccountid',
			title:'".GetCatalog('addressaccountid')."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
		field:'addressbookid',
		title:'".GetCatalog('addressbook')."',
		width:'200px',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'addressbookid',
				textField:'fullname',
				url:'".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true))."',
				fitColumns:true,
				required:true,
				pagination:true,
				queryParams:{
					combo:true
				},
				loadMsg: '".GetCatalog('pleasewait')."',
				columns:[[
					{field:'addressbookid',title:'".GetCatalog('addressbookid')."',width:'50px'},
					{field:'fullname',title:'".GetCatalog('fullname')."',width:'250px'},
				]]
			}	
		},
			sortable: true,
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'companyid',
			title:'".GetCatalog('companyname')."',
			width:'300px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true))."',
					fitColumns:true,
					required:true,
					pagination:true,
					queryParams:{
						auth:true
					},
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'".GetCatalog('company')."',width:'100px'},
						{field:'companyname',title:'".GetCatalog('companyname')."',width:'300px'},
					]],
					onBeforeLoad: function(param) {
						var row = $('#dg-addressaccount').edatagrid('getSelected');
						if(row==null){
							$(\"input[name='company']\").val('0');
						}
					},
					onSelect: function(index,row){
						var company = row.companyid;
						$(\"input[name='company']\").val(row.companyid);
					},
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
		field:'accpiutangid',
		title:'".GetCatalog('accpiutang')."',
		width:'250px',
		editor:{
			type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountname',
					url:'".Yii::app()->createUrl('accounting/account/index',array('grid'=>true))."',
					fitColumns:true,
					required:true,
					pagination:true,
					queryParams:{
						trxcom:true
					},
					onBeforeLoad: function(param) {
						var company = $(\"input[name='company']\").val();
						if(company==''){
							var row = $('#dg-addressaccount').datagrid('getSelected');
							param.companyid = row.companyid;
							}else{
							param.companyid = $(\"input[name='company']\").val(); }
					},
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'".GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'".GetCatalog('accountcode')."',width:'80px'},
						{field:'accountname',title:'".GetCatalog('accpiutang')."',width:'200px'},
						{field:'companyname',title:'".GetCatalog('company')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.accpiutang;
		}},
		{
			field:'acchutangid',
			title:'".GetCatalog('acchutang')."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountname',
					url:'".Yii::app()->createUrl('accounting/account/index',array('grid'=>true))."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						trxcom:true
					},
					onBeforeLoad: function(param) {
						var company = $(\"input[name='company']\").val();
						if(company==''){
							var row = $('#dg-addressaccount').datagrid('getSelected');
							param.companyid = row.companyid;
						}else{
							param.companyid = $(\"input[name='company']\").val(); }
					},
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'".GetCatalog('accountid')."',width:50},
						{field:'accountcode',title:'".GetCatalog('accountcode')."',width:75},
						{field:'accountname',title:'".GetCatalog('acchutang')."',width:200},
						{field:'companyname',title:'".GetCatalog('company')."',width:200},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.acchutang;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			width:'50px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('addressaccountid','fullname','companyname','accpiutang','acchutang')
));