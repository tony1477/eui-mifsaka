<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productstockid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/productstock/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/productstock/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/productstock/downxls'),
	'downloadbuttons'=>"
	",
	'addonscripts'=>"
	",
	'columns'=>"
		{
			field:'productstockid',
			title:'".getCatalog('productstockid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productid',
			title:'".getCatalog('product') ."',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'slocid',
			title:'".getCatalog('sloc') ."',
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode+' - '+row.slocdesc;
		}},
		{
			field:'storagebinid',
			title:'".getCatalog('storagebin') ."',
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},
		{
			field:'qty',
			title:'".getCatalog('qty') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+row.qtyshow+'</div>';
		}},
		{
			field:'qtyinprogress',
			title:'".getCatalog('qtyinprogress') ."',
			editor:'numberbox',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+row.qtyipshow+'</div>';
		}},
		{
			field:'unitofmeasureid',
			title:'".getCatalog('unitofmeasure') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}},
	",
	'rowstyler'=>"
		if ((row.qty < row.minstock)) {
			return 'background-color:red;color:white;';
		} else 
		if ((row.qty >= row.minstock) && (row.qty <= row.orderstock)) {
			return 'background-color:yellow;color:black;';
		}	else 
		if ((row.qty > row.orderstock) && (row.qty <= row.maxstock)) {
			return 'background-color:green;color:black;';
		} else {
			return 'background-color:white;color:black;';
		}	
	",
	'searchfield'=> array ('productstockid','product','sloc','storagebin','uom'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'productstockdetid',
			'urlsub'=>Yii::app()->createUrl('inventory/productstock/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'referenceno',title:'".getCatalog('referenceno') ."'},
				{field:'transdate',title:'".getCatalog('transdate') ."'},
				{field:'qty',title:'".getCatalog('qty') ."'},
			",
			'columns'=>"
			"
		),
	),	
));