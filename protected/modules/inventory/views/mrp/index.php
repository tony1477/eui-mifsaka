<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'mrpid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('inventory/mrp/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/mrp/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/mrp/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/mrp/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('inventory/mrp/upload'),
	'downpdf'=>Yii::app()->createUrl('inventory/mrp/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/mrp/downxls'),
	'columns'=>"
		{
			field:'mrpid',
			title:'".getCatalog('mrpid')."', 
			sortable:'true',
			width:'50px',
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
			width:'450px',			
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'slocid',
			title:'".getCatalog('sloc') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'slocid',
					textField:'sloccode',
					url:'".Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'slocid',title:'".getCatalog('slocid')."',width:'80px'},
						{field:'sloccode',title:'".getCatalog('sloccode')."',width:'150px'},
						{field:'description',title:'".getCatalog('description')."',width:'250px'},
					]]
				}	
			},
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'minstock',
			title:'".getCatalog('minstock') ."',
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
			field:'reordervalue',
			title:'".getCatalog('reordervalue') ."',
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
			field:'maxvalue',
			title:'".getCatalog('maxvalue') ."',
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
field:'leadtime',
title:'".getCatalog('leadtime') ."',
editor:{
			type:'numberbox',
			options:{
				precision:0,
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
			field:'stock',
			title:'".getCatalog('Stock')."',
			align:'center',
			width:'50px',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable:'true',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		},
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
	'searchfield'=> array ('mrpid','uom','sloc','product','recordstatus')
));