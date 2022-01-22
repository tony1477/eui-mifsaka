<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'gireturid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/reportgiretur/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/giretur/downpdf'),
	'downloadbuttons'=>"
	",
	'addonscripts'=>"
	",
	'columns'=>"
		{
			field:'gireturid',
			title:'".getCatalog('gireturid') ."',
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
			title:'".getCatalog('company') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'gireturno',
			title:'".getCatalog('gireturno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'gireturdate',
			title:'".getCatalog('gireturdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'gino',
			title:'".getCatalog('gino') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'customername',
			title:'".getCatalog('customer') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".getCatalog('headernote') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return row.recordstatusgiretur;
		}},
	",
	'searchfield'=> array ('gireturid','gireturdate','gireturno','companyname','gino','customer','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'gireturdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/reportgiretur/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".getCatalog('productname') ."'},
				{field:'qty',title:'".getCatalog('qty') ."',align:'right'},
				{field:'uomcode',title:'".getCatalog('uomcode') ."'},
				{field:'sloccode',title:'".getCatalog('sloc') ."'},
				{field:'description',title:'".getCatalog('storagebin') ."'},
				{field:'itemnote',title:'".getCatalog('itemnote') ."',width:'300px'},
			",
			'columns'=>"
			"
		),
	),	
));