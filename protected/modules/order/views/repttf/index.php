<?php 
$this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'ttfid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('order/repttf/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('order/ttf/downpdf'),
	'downloadbuttons'=>"",
	'otherbuttons'=> '',
	'addonscripts'=>"        ",
	'columns'=>"
		{
			field:'ttfid',
			title:'".GetCatalog('ttfid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'280px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'docno',
			title:'".GetCatalog('docno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
                return value;
		}},
		{
			field:'docdate',
			title:'".GetCatalog('docdate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".GetCatalog('sales') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return row.salesname;
		}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.description;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			formatter: function(value,row,index){
				return row.statusname;
		}},
	",
	'searchfield'=> array ('ttfid','companyname','docno','sales','description'),
	'headerform'=> "",
	'loadsuccess' => "",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'ttfdetailid',
			'urlsub'=>Yii::app()->createUrl('order/repttf/indexdetail',array('grid'=>true,'list'=>true)),
			'subs'=>"
				{field:'customer',title:'".GetCatalog('customer') ."'},
				{field:'invoiceno',title:'".GetCatalog('invoice') ."',align:'left'},
				{field:'invoicedate',title:'".GetCatalog('invoicedate') ."',align:'right'},
				{field:'duedate',title:'".GetCatalog('jatuhtempo') ."',align:'right'},
				{field:'sono',title:'".GetCatalog('sono') ."',align:'left'},
				{field:'amount',title:'".GetCatalog('amount') ."',align:'right'},
				{field:'payamount',title:'".GetCatalog('payamount') ."',align:'right'},
			",
			'columns'=>""
		),
	),	
));