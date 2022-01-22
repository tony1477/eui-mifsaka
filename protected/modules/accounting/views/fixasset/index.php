<input type="hidden" id='fixasset-plantid' name='fixasset-plantid'>
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'fixassetid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/fixasset/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/fixasset/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/fixasset/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/fixasset/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/fixasset/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/fixasset/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/fixasset/downxls'),
	'columns'=>"
	{
		field:'fixassetid',
		title:'".getCatalog('fixassetid')."', 
		sortable:'true',
		width:'50px',
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
                pagination:true,
				idField:'companyid',
				textField:'companyname',
				url:'". Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true,'combo'=>true)) ."',
				fitColumns:true,
                /*
				onHidePanel: function() {
					var tr = $(this).closest('tr.datagrid-row');
					var index = parseInt(tr.attr('datagrid-row-index'));
					var companyid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'companyid'});
					var productid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'productid'});
					var poheaderid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'poheaderid'});
					$('#fixasset-companyid').val($(companyid.target).combogrid('getValue'));
					jQuery.ajax({'url':'".Yii::app()->createUrl('common/plant/index',array('grid'=>true)) ."',
						'data':{'plantid':$(plantid.target).combogrid('getValue')},
						'type':'post','dataType':'json',
						'success':function(data)
						{
							$(productid.target).combogrid('setValue',data.productid);
							$(poheaderid.target).combogrid('setValue',data.poheaderid);
							
						},
						'cache':false});
				},
                */
				required:true,
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'companyid',title:'". GetCatalog('companyid')."',width:'50px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'300px'},
				]]
			}	
		},
		width:'300px',
		sortable: true,
		formatter: function(value,row,index){
		return row.companyname;
	}},
	{
		field:'poheaderid',
		title:'". GetCatalog('pono') ."',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:'550x',
					mode : 'remote',
					method:'get',
                    pagination:true,
					idField:'poheaderid',
					textField:'pono',
					url:'". Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true)) ."',
					fitColumns:true,
					required:false,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'poheaderid',title:'". GetCatalog('poheaderid')."',width:'80px'},
						{field:'pono',title:'". GetCatalog('pono')."',width:'100px'},
						{field:'headernote',title:'". GetCatalog('headernote')."',width:'300px'},
					]]
			}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.pono;
	}},
	{
		field:'stdqty2',
		title:'".getCatalog('stdqty2') ."',
		sortable: true,
		editor: {
			type: 'numberbox',
			options: {
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				value:0,
			}
		},
		hidden:true,
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'materialtypecode',
		title:'".GetCatalog('materialtypecode') ."',
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'assetno',
		title:'".getCatalog('assetno')."', 
		width:'120px',
		sortable:'true',
		formatter: function(value,row,index){
			return value;
		}
	},
	{
			field:'productid',
			title:'".getCatalog('productname') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode: 'remote',
					method:'get',
                    pagination:true,
					idField:'productid',
					textField:'productname',
					url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true)) ."',
					fitColumns:true,
					required:true,
					onBeforeLoad:function(param) {
						param.plantid = $('#fixasset-plantid').val()
					},
					onShowPanel: function() {
						var tr = $(this).closest('tr.datagrid-row');
						var index = parseInt(tr.attr('datagrid-row-index'));
						var productid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'productid'});
						var plantid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'plantid'});
						$(productid.target).combogrid('grid').datagrid('load',{
							plantid:$('#fixasset-plantid').val()
						});
					},
					onHidePanel: function() {
						var tr = $(this).closest('tr.datagrid-row');
						var index = parseInt(tr.attr('datagrid-row-index'));
						var productid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'productid'});
						var stdqty2 = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'stdqty2'});
						var uomid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'uomid'});
						var uom2id = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'uom2id'});
						jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/getdata') ."',
							'data':{'productid':$(productid.target).combogrid('getValue')},
							'type':'post','dataType':'json',
							'success':function(data)
							{
								$(uomid.target).combogrid('setValue',data.uomid);
								$(uom2id.target).combogrid('setValue',data.uomid);
								$(stdqty2.target).numberbox('setValue',data.qty2);
							} ,
							'cache':false});
					},
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
						{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'100px'},
						{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
					]]
				}	
			},
			width:'500px',
			sortable: true,
			formatter: function(value,row,index){
								return row.productname;
			}
		},
		{
			field:'description',
			title:'".getCatalog('description')."', 
			editor:{
				type:'text',
				options:{
				}
			},
			width:'400px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'qty',
			title:'".GetCatalog('qty') ."',
			width:'100px',
			editor: {
				type: 'numberbox',
				options:{
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
					required:true,
					onChange: function(newValue,oldValue) {
						var tr = $(this).closest('tr.datagrid-row');
						var index = parseInt(tr.attr('datagrid-row-index'));
						var stdqty2 = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'stdqty2'});
														
						var qty2 = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'qty2'});								
												
						$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * newValue);
						
					}
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'uomid',
			title:'".GetCatalog('uomcode') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
                    pagination:true,
					idField:'unitofmeasureid',
					textField:'uomcode',
					readonly:true,
					url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
						{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
					]]
				}	
			},
			width:'90px',
			sortable: true,
			formatter: function(value,row,index){
								return row.uomcode;
			}
		},
        
		{
			field:'price',
			title:'".GetCatalog('price') ."',
			width:'150px',
			editor: {
				type: 'numberbox',
				options:{
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
					required:true,
				}
			},
			sortable: true,
			formatter: function(value,row,index){
									return value;
			}
		},
		{
			field:'buydate',
			title:'".GetCatalog('buydate')."',
			editor: {
				type: 'datebox',
				options:{
					required:true,
					formatter:dateformatter,
							parser:dateparser
				}
			},
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
								return value;
			}
		},
		{
			field:'nilairesidu',
			title:'".GetCatalog('nilairesidu') ."',
			width:'150px',
			editor: {
				type: 'numberbox',
				options:{
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
					required:true,
				}
			},
			sortable: true,
			formatter: function(value,row,index){
									return value;
			}
		},
		{
			field:'currencyid',
			title:'". GetCatalog('currency') ."',
			width:'100px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
                    pagination:true,
					idField:'currencyid',
					textField:'currencyname',
					url:'". Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'currencyid',title:'". GetCatalog('currencyid')."',width:'50px'},
						{field:'currencyname',title:'". GetCatalog('currencyname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
			}	
		},
		{
			field:'currencyrate',
			title:'". GetCatalog('currencyrate') ."',
			width:'80px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					decimalSeparator:',',
					groupSeparator:'.',
					required:true,
				}
			},
			align: 'right',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}},
		{
			field:'accakum',
			title:'". GetCatalog('accakum') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
                    pagination:true,
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'100px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('company')."',width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accakumcode;
		}},
		{
			field:'accbiaya',
			title:'". GetCatalog('accbiaya') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
                    pagination:true,
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'100px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('company')."',width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accbiayacode;
		}},
		{
			field:'accperolehan',
			title:'". GetCatalog('accperolehan') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
                    pagination:true,
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'100px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('company')."',width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accperolehancode;
		}},
		{
			field:'acckorpem',
			title:'". GetCatalog('acckorpem') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
                    pagination:true,
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'100px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('company')."',width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.acckorpemcode;
		}},
		{
			field:'umur',
			title:'". GetCatalog('umur') ."',
			width:'150px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					decimalSeparator:',',
					groupSeparator:'.',
					required:true,
				}
			},
			align: 'right',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
        {
			field:'nilaipenyusutan',
			title:'".GetCatalog('nilaipenyusutan') ."',
			width:'150px',
			editor: {
				type: 'numberbox',
				options:{
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
					required:true,
				}
			},
			sortable: true,
			formatter: function(value,row,index){
									return value;
			}
		},
		{
			field:'famethodid',
			title:'". GetCatalog('famethod') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
                    pagination:true,
					idField:'famethodid',
					textField:'methodname',
					url:'". $this->createUrl('famethod/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'famethodid',title:'". GetCatalog('famethodid')."',width:'50px'},
						{field:'methodname',title:'". GetCatalog('methodname')."',width:'200px'}
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.methodname;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('fixassetid','assetno','plantcode','pono','productname','umur','metode','description'),
	'columndetails'=> array (
	array(
			'id'=>'fahistory',
			'idfield'=>'fahistoryid',
			'urlsub'=>Yii::app()->createUrl('accounting/fixasset/indexfahistory',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/fixasset/searchfahistory',array('grid'=>true)),
			'subs'=>"
				{field:'bulanke',title:'".GetCatalog('bulanke') ."',width:'70px'},
				{field:'susutdate',title:'".GetCatalog('susutdate') ."',width:'100px'},
				{field:'nilai',title:'".GetCatalog('nilai') ."',
					formatter: function(value,row,index){
						return value;
					},width:'150px'},
				{field:'beban',title:'".GetCatalog('beban') ."',
					formatter: function(value,row,index){
						return value;
					},width:'150px'},
				{field:'nilaiakum',title:'".GetCatalog('nilaiakum') ."',
					formatter: function(value,row,index){
						return value;
					},width:'150px'},
				{field:'nilaibuku',title:'".GetCatalog('nilaibuku') ."',
					formatter: function(value,row,index){
						return value;
					},width:'150px'},
			",
			),
	array(
			'id'=>'fajurnal',
			'idfield'=>'fajurnalid',
			'urlsub'=>Yii::app()->createUrl('accounting/fixasset/indexfajurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/fixasset/searchfajurnal',array('grid'=>true)),
			'subs'=>"
			{field:'susutdate',title:'".GetCatalog('susutdate') ."',width:'100px'},
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'400px'},
				{field:'currencyname',title:'".GetCatalog('currencyname') ."',width:'150px'},
				{field:'debet',title:'".GetCatalog('debet') ."',
					formatter: function(value,row,index){
						return value;
					},width:'150px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return value;
					},width:'150px'},
				{field:'currencyrate',title:'".GetCatalog('currencyrate') ."',
					formatter: function(value,row,index){
						return value;
					},width:'100px'}
			"
		)
)));