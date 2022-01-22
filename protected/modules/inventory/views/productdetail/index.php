<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productdetailid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'url'=>Yii::app()->createUrl('inventory/productdetail/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/productdetail/downpdf'),
	'downloadbuttons'=>"
	",
	'addonscripts'=>"
	",
	'columns'=>"
		{
			field:'productdetailid',
			title:'".getCatalog('productdetailid') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialcode',
			title:'".getCatalog('materialcode') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productid',
			title:'".getCatalog('product') ."',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'slocid',
			title:'".getCatalog('sloc') ."',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'expiredate',
			title:'".getCatalog('expiredate') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'serialno',
			title:'".getCatalog('serialno') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'qty',
			title:'".getCatalog('qty') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'unitofmeasureid',
			title:'".getCatalog('unitofmeasure') ."',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}},
		{
			field:'buydate',
			title:'".getCatalog('buydate') ."',
			editor:'datebox',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'buyprice',
			title:'".getCatalog('buyprice') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'currencyid',
			title:'".getCatalog('currency') ."',
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'storagebinid',
			title:'".getCatalog('storagebin') ."',
			sortable: true,
			formatter: function(value,row,index){
				return row.rak;
		}},
		{
			field:'materialstatusid',
			title:'".getCatalog('materialstatus') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ownershipid',
			title:'".getCatalog('ownership') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'referenceno',
			title:'".getCatalog('referenceno') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
	",
	'searchfield'=> array ('productname','sloccode','uomcode','storagebindesc'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
	),	
));