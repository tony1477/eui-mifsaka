<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productoutputid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('production/productplasticoutput/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/productplasticoutput/getData'),
	'saveurl'=>Yii::app()->createUrl('production/productplasticoutput/save'),
	'updateurl'=>Yii::app()->createUrl('production/productplasticoutput/save'),
	'approveurl'=>Yii::app()->createUrl('production/productplasticoutput/approve'),
	'rejecturl'=>Yii::app()->createUrl('production/productplasticoutput/delete'),
	'destroyurl'=>Yii::app()->createUrl('production/productplasticoutput/purge'),
	'uploadurl'=>Yii::app()->createUrl('production/productplasticoutput/upload'),
	'downpdf'=>Yii::app()->createUrl('production/productplasticoutput/downpdf'),
	'downloadbuttons'=>"
    <a href='javascript:void(0)' title='Stock yang TIDAK CUKUP'class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='downpdfminus()'></a>
	",
	'addonscripts'=>"
		function downpdfminus() {
			var ss = [];
			var rows = $('#dg-productplasticoutput').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.productoutputid);
			}
			window.open('".$this->createUrl('productplasticoutput/downpdfminus') ."?id='+ss);
    }
	",
	'addload'=>"
		$('#productoutputdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'wfapp'=>'appop',
	'columns'=>"
		{
			field:'productoutputid',
			title:'".GetCatalog('productoutputid') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				if (row.recordstatus == 1) {
					return '<div style=\"background-color:green;color:white\">'+value+'</div>';
				} else 
				if (row.recordstatus == 2) {
					return '<div style=\"background-color:yellow;color:black\">'+value+'</div>';
				} else 
				if (row.recordstatus == 3) {
					return '<div style=\"background-color:red;color:white\">'+value+'</div>';
				} else 
				if (row.recordstatus == 0) {
					return '<div style=\"background-color:black;color:white\">'+value+'</div>';
				}
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'productoutputno',
			title:'".GetCatalog('productoutputno') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productoutputdate',
			title:'".GetCatalog('productoutputdate') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productplanid',
			title:'".GetCatalog('productplanno') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusproductoutput',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'soheaderid',
			title:'".GetCatalog('soheader') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.sono;
		}}
	",
	'rowstyler'=>"
		if (row.count >= 1){
			return 'background-color:blue;color:#fff;font-weight:bold;';
		}	
	",
	'searchfield'=> array ('productoutputid','productoutputno','productplanno','productoutputdate','companyname','sono','customer','description'),
	'headerform'=> "
		<input type='hidden' name='productoutputid' id='productoutputid'/>
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('productoutputdate')."</td>
				<td><input class='easyui-datebox' type='text' id='productoutputdate' name='productoutputdate' data-options='formatter:dateformatter,required:true,parser:dateparser'/></td>
			</tr>
			<tr>
				<td>".GetCatalog('productplan')."</td>
				<td><select class='easyui-combogrid' name='productplanid' id='productplanid' style='width:250px' data-options=\"
					panelWidth: 500,
					idField: 'productplanid',
					textField: 'productplanno',
					pagination:true,
					url: '".Yii::app()->createUrl('production/productplan/indexcombo',array('grid'=>true)) ."',
					method: 'get',
					mode:'remote',
					required:true,
					onHidePanel: function(){
						jQuery.ajax({'url':'".$this->createUrl('productplasticoutput/generateplan') ."',
							'data':{'id':$('#productplanid').combogrid('getValue'),'hid':$('#productoutputid').val()},'type':'post','dataType':'json',
							'success':function(data)
							{
								$('#dg-productplasticoutput-productplasticoutputfg').edatagrid('reload');		
								$('#dg-productplasticoutput-productplasticoutputdetail').edatagrid('reload');
							} ,
							'cache':false});
					},
					columns: [[
						{field:'productplanid',title:'".GetCatalog('productplanid') ."',width:'80px'},
						{field:'productplanno',title:'".GetCatalog('productplanno') ."',width:'150px'},
						{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
						{field:'companyname',title:'".GetCatalog('companyname') ."',width:'250px'},
					]],
					fitColumns: true
					\">
					</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('description')."</td>
				<td><input class='easyui-textbox' type='textarea' name='description' data-options='multiline:true,required:true' style='width:300px;height:100px'/></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'productplasticoutputfg',
			'idfield'=>'productoutputfgid',
			'urlsub'=>Yii::app()->createUrl('production/productplasticoutput/indexfg',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/productplasticoutput/searchdetailfg',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/productplasticoutput/savedetailfg',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/productplasticoutput/savedetailfg',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/productplasticoutput/purgedetailfg',array('grid'=>true)),
			'issingle'=>'true',
            'issuccess'=>'true',
			'onselectsub'=>"
				ddvproductplasticoutputdetail.edatagrid('load',{
					productoutputfgid: row.productoutputfgid
				})
			",
			'onselect'=>"
				$('#dg-productplasticoutput-productplasticoutputdetail').edatagrid('load',{
					id: row.productoutputid,
					productoutputfgid: row.productoutputfgid
				})
			",
			'subs'=>"
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qtyoutput',title:'".GetCatalog('qty') ."',align:'right',width:'75px'},
				{field:'stock',title:'".GetCatalog('stock') ."',align:'right',width:'75px',
					formatter: function(value,row,index){
						if (row.wstock == 1) {
							return '<div style=\"background-color:red;color:white\">'+value+'</div>';
						} else {
							return value;
						}
					}},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'120px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'150px'},
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'200px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
				{field:'startdate',title:'".GetCatalog('startdate') ."',width:'100px'},
				{field:'enddate',title:'".GetCatalog('enddate') ."',width:'100px'},					
			",
			'onsuccess'=>"
				$('#dg-productplasticoutput-productplasticoutputdetail').edatagrid('reload');
                $('#dg-productplasticoutput-productplasticoutputfg').edatagrid('reload');
				
			",
			'onerror'=>"
				$('#dg-productplasticoutput-productplasticoutputfg').edatagrid('reload');
			",
            'onbeginedit'=>"
                row.productoutputid = $('#productoutputid').val();
            ",
			'columns'=>"
				{
					field:'productoutputid',
					title:'".GetCatalog('productoutputid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'productoutputfgid',
					title:'".GetCatalog('productoutputfgid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'productid',
					title:'".GetCatalog('product') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
                            readonly:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
							{field:'productid',title:'".GetCatalog('productid')."',width:'50px'},
							{field:'productname',title:'".GetCatalog('productname')."',width:'250px'},
							{field:'bomversion',title:'".GetCatalog('bomversion')."',width:'200px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.productname;
					}
				},
				{
					field:'qtyoutput',
					title:'".GetCatalog('qty') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							required:true,
							decimalSeparator:',',
							groupSeparator:'.'
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'uomid',
					title:'".GetCatalog('uom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:450,
							mode : 'remote',
							method:'get',
							readonly:true,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							queryParams:{
								combo:true
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
							{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
							{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'200px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.uomcode;
					}
				},
				{
					field:'slocid',
					title:'".GetCatalog('sloc') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:450,
							mode : 'remote',
							method:'get',
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/index',array('grid'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							readonly:true,
							queryParams:{
								combo:true
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
							{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
							{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'200px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.sloccode;
					}
				},
				{
					field:'storagebinid',
					title:'".GetCatalog('storagebin') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:450,
							mode : 'remote',
							method:'get',
							idField:'storagebinid',
							textField:'description',
							url:'".Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							queryParams:{
									slocid:0
							},
							onBeforeLoad: function(param) {
				                
								param.slocid = 1;
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
							{field:'storagebinid',title:'".GetCatalog('storagebinid')."',width:'50px'},
							{field:'description',title:'".GetCatalog('description')."',width:'200px'},
							]]
						}	
					},
					width:'120px',
					sortable: true,
					formatter: function(value,row,index){
						return row.rak;
					}
				},
				{
					field:'outputdate',
					title:'".GetCatalog('outputdate') ."',
					sortable: true,
					editor:{
						type: 'datebox',
						parser: dateparser,
						formatter: dateformatter
					},
					width:'100px',
					required:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('description') ."',
					editor:'text',
					width:'250px',
					sortable: true,
					required:true,
					formatter: function(value,row,index){
						return value;
					}
				},
			"
		),
		array(
			'id'=>'productplasticoutputdetail',
			'idfield'=>'productoutputdetailid',
			'ispurge'=>0,
			'isnew'=>0,
			'urlsub'=>Yii::app()->createUrl('production/productplasticoutput/indexdetail',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('production/productplasticoutput/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/productplasticoutput/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/productplasticoutput/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/productplasticoutput/purgedetail',array('grid'=>true)),
			'issingle'=>'false',
			'subs'=>"
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',align:'right',width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'100px'},
				{field:'fromsloccode',title:'".GetCatalog('fromsloc') ."',width:'200px'},
				{field:'fromslocstock',title:'".GetCatalog('stockfrom') ."',align:'right',width:'100px',
					formatter: function(value,row,index){
					if (row.wminfromstock == 1) {
						return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
				}}},
				{field:'toslocstock',title:'".GetCatalog('stockto') ."',align:'right',width:'100px',
					formatter: function(value,row,index){
					if (row.wmintostock == 1) {
						return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
				}}},
				{field:'rak',title:'".GetCatalog('storagebin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'200px'},
			",
            'onbeginedit'=>"
                row.productoutputid = $('#productoutputid').val();
                var rowx = $('#dg-productplasticoutput-productplasticoutputfg').edatagrid('getSelected');
                if (rowx)
                {
                  row.productoutputfgid = rowx.productoutputfgid;
                }                
            ",
			'columns'=>"
			{
				field:'productoutputdetailid',
				title:'".GetCatalog('productoutputdetailid') ."',
				sortable: true,
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'productoutputid',
				title:'".GetCatalog('productoutputid') ."',
				hidden:true,
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'productoutputfgid',
				title:'".GetCatalog('productoutputfgid') ."',
				sortable: true,
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'productid',
				title:'".GetCatalog('product') ."',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true)) ."',
						fitColumns:true,
						required:true,
						readonly:true,
						pagination:true,loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'productid',title:'".GetCatalog('productid')."',width:'80px'},
							{field:'productname',title:'".GetCatalog('productname')."',width:'250px'},
						]]
					}	
				},
				width:'250px',
				sortable: true,
				formatter: function(value,row,index){
					return row.productname;
				}
			},
		{
			field:'qty',
			title:'".GetCatalog('qty') ."',
			sortable: true,
			editor:{
				type:'textbox',
				options:{
					readonly:false,
				}
			},
			width:'150px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'uomid',
			title:'".GetCatalog('uom') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ."',
						fitColumns:true,
						pagination:true,
						required:true,
						readonly:true,
						queryParams:{
							combo:true
						},
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
							{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'200px'},
						]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
			}
		},
			{
			field:'toslocid',
			title:'".GetCatalog('tosloc') ."',
			editor:{
			type:'combogrid',
			options:{
			panelWidth:450,
			mode : 'remote',
			method:'get',
			idField:'slocid',
			textField:'sloccode',
			url:'".Yii::app()->createUrl('common/sloc/index',array('grid'=>true)) ."',
			fitColumns:true,
			pagination:true,
			required:true,
			readonly:true,
			queryParams:{
			combo:true
			},
			loadMsg: '".GetCatalog('pleasewait')."',
			columns:[[
			{field:'slocid',title:'".GetCatalog('slocid')."'},
			{field:'sloccode',title:'".GetCatalog('sloccode')."'},
			]]
			}	
			},
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
			return row.tosloccode;
			}
			},
			{
			field:'storagebinid',
			title:'".GetCatalog('storagebin') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'storagebinid',
						textField:'description',
						url:'".Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ."',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							slocid:1
						},
						onBeforeLoad: function(param) {
							var row = $('#dg-productplasticoutput-productplasticoutputdetail').datagrid('getSelected');
							param.slocid = row.toslocid;
						},
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'storagebinid',title:'".GetCatalog('storagebinid')."',width:'50px'},
							{field:'description',title:'".GetCatalog('description')."',width:'200px'},
						]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.rak;
			}
		},
		{
			field:'description',
			title:'".GetCatalog('description')."',
			editor:'text',
			sortable: true,
			required:true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
			}
		},
			"
		)
	),	
));