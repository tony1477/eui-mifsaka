<?php
$this->widget('Form',	array(
	'menuname' => $this->menuname,
	'idfield' => 'forecastfppid',
	'formtype' => 'masterdetail',
	'url' => Yii::app()->createUrl('inventory/forecastfpp/index', array('grid' => true)),
	'urlgetdata' => Yii::app()->createUrl('inventory/forecastfpp/getData'),
	'saveurl' => Yii::app()->createUrl('inventory/forecastfpp/save', array('grid' => true)),
	//'updateurl'=>Yii::app()->createUrl('inventory/forecastfpp/save',array('grid'=>true)),
	'ispost' => 1,
	'isreject' => 1,
	'approveurl' => Yii::app()->createUrl('inventory/forecastfpp/approve'),
	'rejecturl' => Yii::app()->createUrl('inventory/forecastfpp/delete'),
	'uploadurl' => Yii::app()->createUrl('inventory/forecastfpp/upload'),
	'downpdf' => Yii::app()->createUrl('inventory/forecastfpp/downpdf'),
	'downxls' => Yii::app()->createUrl('inventory/forecastfpp/downxls'),
	'downloadbuttons' => "
	<select class=\"easyui-combogrid\" id=\"dlg_search_companyid\" name=\"dlg_search_companyid\" style=\"width:250px\" data-options=\"
								panelWidth: 500,
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								url: '" . Yii::app()->createUrl('admin/company/indexauth', array('grid' => true)) . "',
								method: 'get',
								onHidePanel: function(){
									$('#dlg_search_plantid').combogrid('setValue','');
								},
								columns: [[
										{field:'companyid',title:'" . GetCatalog('companyid') . "'},
										{field:'companyname',title:'" . GetCatalog('companyname') . "'},
								]],
								fitColumns: true,
								prompt:'Company'
						\">
				</select>
		<input class='easyui-datebox' type='text' id='dlg_search_date' name='dlg_search_date' data-options=\"formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date Company'\"></input>
		<a href='javascript:void(0)' title='Generate FPP' class='easyui-linkbutton' iconCls='icon-forecastfpp' plain='true' alt='Generate FPP' onclick='generatefpp()' id='generatefpp'></a>",
	'addonscripts' => "
			function generatefpp() {
				openloader();
				jQuery.ajax({'url':'" . Yii::app()->createUrl('/inventory/forecastfpp/generatefpp') . "',
					'data':{			
						'companyid':$('#dlg_search_companyid').combogrid('getValue'),
						'perioddate':$('#dlg_search_date').datebox('getValue')},
					'type':'post','dataType':'json',
					'success':function(data)
					{
						closeloader();
						show('Message',data.msg);
						$('#dg-profitloss').edatagrid('reload');				
					} ,
					'cache':false});
			};
		",
	'columns' => "
	{
		field:'forecastfppid',
		title:'" . GetCatalog('forecastfppid') . "',
		sortable: true,
		width:'50px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'docdate',
		title:'" . GetCatalog('docdate') . "', 
		editor:{type:'datebox'},
		width:'100px',
		sortable:'true',
		formatter: function(value,row,index){
			return value;
		}
	},
		{
			field:'perioddate',
			title:'" . GetCatalog('perioddate') . "', 
			editor:{type:'datebox'},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:'" . GetCatalog('companyname') . "',
			sortable: true,
			editor:{
				type : 'combogrid',
				options: {
					panelWidth:'500px',
					mode: 'remote',
					method: 'get',
					idField: 'companyid',
					textField: 'companyname',
					required: true,
					pagination: true,
					url:'" . Yii::app()->createUrl('admin/company/indexauth', array('grid' => true,)) . "',
					fitColumns: true,
					loadMsg: '" . GetCatalog('pleasewait') . "',
					columns:[[
							{field:'companyid',title:'" . GetCatalog('companyid') . "'},
							{field:'companyname',title:'" . GetCatalog('company') . "'},
					]],
					onBeforeLoad: function(param) {
							var row = $('#dg-forecastfpp').edatagrid('getSelected');
							if(row==null){
									$(\"input[name='companyid']\").val('0');
							}
					},
					onSelect: function(index,row){
							var companyid = row.companyid;
							$(\"input[name='companyid']\").val(row.companyid);
					},
				},
			},
			width:'350px',
			formatter: function(value,row,index){
				return row.companyname;
			}
		},
		{
			field:'headernote',
			title:'" . getCatalog('description') . "',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'" . getCatalog('recordstatus') . "',
			width:'180px',
			align:'left',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				return row.statusname;
			}
		}",
	'searchfield' => array('forecastfppid', 'perioddateyear', 'perioddatemonth', 'companyname', 'productname', 'sloccode'),
	'headerform' => "
		<input type='hidden' id='forecastfppid' name='forecastfppid'></input>
		<table cellpadding='5'>
		<tr>
			<td>" . GetCatalog('company') . "</td>
			<td><select class='easyui-combogrid' id='companyid' name='companyid' style='width:250px' data-options=\"
							panelWidth: '500px',
							required: true,
							idField: 'companyid',
							textField: 'companyname',
							pagination:true,
							mode:'remote',
							method: 'get',
							url:'" . Yii::app()->createUrl('admin/company/indexauth', array('grid' => true)) . "',
							columns: [[
									{field:'companyid',title:'" . GetCatalog('companyid') . "'},
									{field:'companyname',title:'" . GetCatalog('companyname') . "'},
							]],
							fitColumns: true
					\">
			</select></td>
		</tr>
			<tr>
				<td>" . getCatalog('docdate') . "</td>
				<td><input class='easyui-datebox' type='text' id='docdate' name='docdate' data-options='formatter:dateformatter,required:true,parser:dateparser' ></input></td>
			</tr>
			<tr>
				<td>" . getCatalog('perioddate') . "</td>
				<td><input class='easyui-datebox' type='text' id='perioddate' name='perioddate' data-options='formatter:dateformatter,required:true,parser:dateparser' ></input></td>
			</tr>
			<tr>
			<td>" . GetCatalog('headernote') . "</td>
			<td><input class='easyui-textbox' id='headernote' name='headernote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
		</tr>
		</table>
	",
	'columndetails' => array(
		array(
			'id' => 'detail',
			'idfield' => 'forecastfppdetid',
			'urlsub' => Yii::app()->createUrl('inventory/forecastfpp/indexdetail', array('grid' => true)),
			'url' => Yii::app()->createUrl('inventory/forecastfpp/searchdetail', array('grid' => true)),
			'saveurl' => Yii::app()->createUrl('inventory/forecastfpp/savedetail', array('grid' => true)),
			'updateurl' => Yii::app()->createUrl('inventory/forecastfpp/savedetail', array('grid' => true)),
			'destroyurl' => Yii::app()->createUrl('inventory/forecastfpp/purgedetail', array('grid' => true)),
			'subs' => "
				{field:'productname',title:'" . getCatalog('productname') . "',width:'400px'},
				{field:'uomcode',title:'" . getCatalog('uom') . "',width:'80px'},
				{field:'sloccode',title:'" . getCatalog('sloc') . "',width:'100px'},
				{field:'qtyforecast',title:'" . getCatalog('qtyforecast') . "',align:'right',width:'80px'},
				{field:'avg3month',title:'" . getCatalog('avg3month') . "',align:'right',width:'80px'},
				{field:'avgperday',title:'" . getCatalog('avgperday') . "',align:'right',width:'80px'},
				{field:'qtymax',title:'" . getCatalog('qtymax') . "',align:'right',width:'80px'},
				{field:'qtymin',title:'" . getCatalog('qtymin') . "',align:'right',width:'80px'},
				{field:'leadtime',title:'" . getCatalog('leadtime') . "',align:'right',width:'80px'},
				{field:'pendingpo',title:'" . getCatalog('pendingpo') . "',align:'right',width:'80px'},
				{field:'saldoawal',title:'" . getCatalog('saldoawal') . "',align:'right',width:'80px'},
				{field:'grpredict',title:'" . getCatalog('grpredict') . "',align:'right',width:'80px'},
				{field:'prqty',title:'" . getCatalog('prqty') . "',align:'right',width:'80px'},
				{field:'prqtyreal',title:'" . getCatalog('prqtyreal') . "',align:'right',width:'80px'},
				{field:'price',title:'" . getCatalog('price') . "',align:'right',width:'80px'},
			",
			'onsuccess' => "
				$('#dg-forecastfpp-detail').edatagrid('reload');
			",
			'onerror' => "
				$('#dg-forecastfpp-detail').edatagrid('reload');
			",
			'onbeforesave' => "
				var row = $('#dg-forecastfpp-detail').edatagrid('getSelected');
				if (row)
				{
					row.forecastfppid = $('#forecastfppid').val();
				}
			",
			'columns' => "
				{
					field:'forecastfppid',
					title:'" . getCatalog('forecastfppid') . "',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'forecastfppdetid',
					title:'" . getCatalog('forecastfppdetailid') . "',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'productid',
					title:'" . getCatalog('product') . "',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'" . Yii::app()->createUrl('common/product/index', array('grid' => true, 'combo' => true)) . "',
							fitColumns:true,
							pagination:true,		
							required:true,
							onChange:function(newValue,oldValue) {
								if ((newValue !== oldValue) && (newValue !== ''))
								{
									var tr = $(this).closest('tr.datagrid-row');
									var index = parseInt(tr.attr('datagrid-row-index'));
									var productid = $('#dg-forecastfpp-detail').datagrid('getEditor', {index: index, field:'productid'});
									var uomid = $('#dg-forecastfpp-detail').datagrid('getEditor', {index: index, field:'uomid'});
									jQuery.ajax({'url':'" . Yii::app()->createUrl('common/productplant/getdata') . "',
										'data':{'productid':$(productid.target).combogrid('getValue')},
										'type':'post','dataType':'json',
										'success':function(data)
										{
											$(uomid.target).combogrid('setValue',data.uomid);
										} ,
										'cache':false});
								}
							},
							loadMsg: '" . getCatalog('pleasewait') . "',
							columns:[[
								{field:'productid',title:'" . getCatalog('productid') . "',width:'80px'},
								{field:'productname',title:'" . getCatalog('productname') . "',width:'300px'},
							]]
						}	
					},
					width:'350px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productname;
					}
				},
				{
				field:'slocid',
				title:'" . GetCatalog('sloc') . "',
				editor:{
						type:'combogrid',
						options:{
								panelWidth:450,
								mode : 'remote',
								method:'get',
								idField:'slocid',
								textField:'sloccode',
								url:'" . Yii::app()->createUrl('common/sloc/indextrx', array('grid' => true)) . "',
								fitColumns:true,
								required:true,
								pagination:true,
								queryParams:{
									trxcom:true
								},
								onBeforeLoad: function(param) {
									var companyid = $(\"input[name='companyid']\").val();
									if(companyid=='')
									{
										var row = $('#dg-forecastfpp').datagrid('getSelected');
										param.companyid = row.companyid;
									}
									else
									{
										param.companyid = $(\"input[name='companyid']\").val(); 
									}
								},
								loadMsg: '" . GetCatalog('pleasewait') . "',
								columns:[[
									{field:'slocid',title:'" . GetCatalog('slocid') . "'},
									{field:'sloccode',title:'" . GetCatalog('sloccode') . "'},
									{field:'description',title:'" . GetCatalog('description') . "'},
								]]
						}	
					},
					width:'90px',
				sortable: true,
				formatter: function(value,row,index){
								return row.sloccode;
								}
						},
						{
							field:'unitofmeasureid',
							title:'" . GetCatalog('uom') . "',
							editor:{
								type:'combogrid',
								options:{
										panelWidth:'500px',
										mode : 'remote',
										method:'get',
										idField:'unitofmeasureid',
										textField:'uomcode',
										url:'" . Yii::app()->createUrl('common/unitofmeasure/index', array('grid' => true)) . "',
										fitColumns:true,
										pagination:true,
										required:true,
										queryParams:{
											combo:true
										},
										loadMsg: '" . GetCatalog('pleasewait') . "',
										columns:[[
											{field:'unitofmeasureid',title:'" . GetCatalog('unitofmeasureid') . "',width:'80px'},
											{field:'uomcode',title:'" . GetCatalog('uomcode') . "',width:'80px'},
											{field:'description',title:'" . GetCatalog('description') . "',width:'200px'},
										]]
								}	
							},
							width:'50px',
							sortable: true,
							formatter: function(value,row,index){
								return row.uomcode;
						}},
				{
					field:'qtyforecast',
					title:'" . GetCatalog('qtyforecast') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
						{
					field:'avg3month',
					title:'" . GetCatalog('avg3month') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
						{
					field:'avgperday',
					title:'" . GetCatalog('avgperday') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
						{
					field:'qtymax',
					title:'" . GetCatalog('qtymax') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
						{
					field:'qtymin',
					title:'" . GetCatalog('qtymin') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
				{
					field:'leadtime',
					title:'" . GetCatalog('leadtime') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:false,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
				{
					field:'pendingpo',
					title:'" . GetCatalog('pendingpo') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
				{
					field:'saldoawal',
					title:'" . GetCatalog('saldoawal') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
				{
					field:'grpredict',
					title:'" . GetCatalog('grpredict') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							//required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
				{
					field:'prqty',
					title:'" . GetCatalog('prqtygen') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							//required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:true,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
				{
					field:'prqtyreal',
					title:'" . GetCatalog('prqtyreal') . "',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							//required:true,
							decimalSeparator:',',
							groupSeparator:'.',
							disabled:false,
						}
					},
					//width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return '<div style=\"text-align:right\">'+value+'</div>';
					}
				},
			"
		),
	),
));
