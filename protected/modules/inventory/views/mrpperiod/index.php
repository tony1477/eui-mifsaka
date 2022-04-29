<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'mrpperiodid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('inventory/mrpperiod/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/mrpperiod/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/mrpperiod/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/mrpperiod/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('inventory/mrpperiod/upload'),
	'downpdf'=>Yii::app()->createUrl('inventory/mrpperiod/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/mrpperiod/downxls'),
	'columns'=>"
		{
			field:'mrpperiodid',
			title:'".getCatalog('mrpperiodid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:'".getCatalog('company') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'".getCatalog('companyid')."',width:'80px'},
						{field:'companyname',title:'".getCatalog('companyname')."',width:'450px'},
					]]
				}	
			},
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'perioddate',
			title:'". GetCatalog('perioddate')."',
			editor: {
				type:'datebox',
				options:{required:true}
			},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'productid',
			title:'".getCatalog('product') ."',
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
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'productid',title:'".getCatalog('productid')."',width:'80px'},
						{field:'productname',title:'".getCatalog('productname')."',width:'250px'},
					]]
				}	
			},
			width:'400px',			
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'uomid',
			title:'".getCatalog('uomid') ."',
			editor:{
					type:'combogrid',
					options:{
							panelWidth:450,
							mode : 'remote',
							method:'get',
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							queryParams:{
								combo:true
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".getCatalog('unitofmeasureid')."'},
								{field:'uomcode',title:'".getCatalog('uomcode')."'},
								{field:'description',title:'".getCatalog('description')."'},
							]]
					}	
				},			
				width:'100px',
	sortable: true,
	formatter: function(value,row,index){
							return row.uomcode;
						}},
		{
			field:'minqty',
			title:'".getCatalog('qtymin') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					disabled:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},
		{
			field:'maxqty',
			title:'".getCatalog('qtymax') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					disabled:true,
					readonly:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},
		{
			field:'minqtyreal',
			title:'".getCatalog('qtyminreal') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},
		{
			field:'maxqtyreal',
			title:'".getCatalog('qtymaxreal') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},

		
        {
			field:'stock',
			title:'".getCatalog('Stock')."',
			align:'center',
			width:'50px',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},
	",
	'beginedit'=>"
		if (row.minstock != undefined) {
			var value = row.minstock;
			row.minstock = value.replace('.', '');
			var value = row.reordervalue;
			row.reordervalue = value.replace('.', '');
			var value = row.maxvalue;
			row.maxvalue = value.replace('.', '');
			var value = row.leadtime;
			row.leadtime = value.replace('.', '');
		}
	",
	'searchfield'=> array ('mrpperiodid','uom','sloc','product','perioddate')
));