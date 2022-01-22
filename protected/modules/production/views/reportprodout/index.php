<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productoutputid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('production/reportprodout/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/productoutput/downpdf'),
	'downloadbuttons'=>"
	",
	'addonscripts'=>"
	",
	'columns'=>"
		{
			field:'productoutputid',
			title:'".GetCatalog('productoutputid') ."',
			sortable: true,
			width:'60px',
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
			field:'employeeid',
			title:'".GetCatalog('foreman') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.foreman;
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
		}},
	",
	'searchfield'=> array ('productoutputid','productoutputno','productoutputdate','company','productplanno','sono','customer','foreman','description'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'productoutputfg',
			'idfield'=>'productoutputid',
			'urlsub'=>Yii::app()->createUrl('production/reportprodout/indexfg',array('grid'=>true,'list'=>true)),
			'onselectsub'=>"
				ddvproductoutputdetail.edatagrid('load',{
					productoutputfgid: row.productoutputfgid,
					productoutputid: row.productoutputid
				})			
			",
			'onselect'=>"
				ddvproductoutputdetail.edatagrid('load',{
					productoutputfgid: row.productoutputfgid
				})
			",
			'subs'=>"
				{field:'productname',title:'".GetCatalog('productname') ."'},
				{field:'qtyoutput',title:'".GetCatalog('qty') ."',align:'right'},
				{field:'stock',title:'".GetCatalog('stock') ."',align:'right',
					formatter: function(value,row,index){
					if (row.wstock == 1) {
						return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
				}}},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."'},
				{field:'rak',title:'".GetCatalog('storagebin') ."'},
				{field:'description',title:'".GetCatalog('description') ."'},
			",
			'columns'=>"
			"
		),
		array(
			'id'=>'productoutputdetail',
			'idfield'=>'productoutputdetailid',
			'urlsub'=>Yii::app()->createUrl('production/reportprodout/indexdetail',array('grid'=>true,'list'=>true)),
			'onselect'=>"
				ddvproductoutputdetail.edatagrid('load',{
					productoutputfgid: row.productoutputfgid
				})
			",
			'subs'=>"
				{field:'productname',title:'".GetCatalog('productname') ."'},
				{field:'qty',title:'".GetCatalog('qty') ."',align:'right'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."'},
				{field:'fromsloccode',title:'".GetCatalog('fromsloc') ."'},
				{field:'fromslocstock',title:'".GetCatalog('stockfrom') ."',align:'right',
					formatter: function(value,row,index){
					if (row.wminfromstock == 1) {
						return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
				}}},
				{field:'toslocstock',title:'".GetCatalog('stockto') ."',align:'right',
					formatter: function(value,row,index){
					if (row.wmintostock == 1) {
						return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
				}}},
				{field:'rak',title:'".GetCatalog('storagebin') ."'},
				{field:'description',title:'".GetCatalog('description') ."'},			",
			'columns'=>"
			"
		),
	),	
));