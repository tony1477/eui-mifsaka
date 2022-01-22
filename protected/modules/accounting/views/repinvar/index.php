<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'invoiceid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('accounting/repinvar/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/invoicear/downpdf'),
	'columns'=>"
		{
			field:'invoiceid',
			title:'".GetCatalog('invoiceid') ."',
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
			field:'companyname',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'invoicedate',
			title:'".GetCatalog('invoicedate') ."',
			editor:'datebox',
			required:true,
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'invoiceno',
			title:'".GetCatalog('invoiceno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'giheaderid',
			title:'".GetCatalog('giheader') ."',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.gino;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'amount',
			title:'".GetCatalog('amount') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},
		{
			field:'payamount',
			title:'".GetCatalog('payamount') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},
		{
			field:'saldo',
			title:'".GetCatalog('saldo') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},
		{
			field:'currencyid',
			title:'".GetCatalog('currency') ."',
			width:'90px',
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},					
		{
			field:'currencyrate',
			title:'".GetCatalog('currencyrate') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}},			
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			editor:'text',
			width:'250px',
			multiline:true,
			required:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.recordstatusname;
		}},
	",
	'rowstyler'=>"
		if (row.warna == 1){
			return 'background-color:cyan;color:black;';
		}
	",
	'searchfield'=> array ('invoiceid','invoicedate','invoiceno','gino','companyname','customer','sono','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'giheaderid',
			'urlsub'=>Yii::app()->createUrl('accounting/repinvar/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".GetCatalog('productname') ."',width:'500px'},
				{field:'qty',title:'".GetCatalog('qty') ."',width:'80px',align:'right'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'250px'},
				{field:'description',title:'".GetCatalog('storagebin') ."',width:'200px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'300px'},
			",
			'columns'=>"
				
			"
		),
	),	
));