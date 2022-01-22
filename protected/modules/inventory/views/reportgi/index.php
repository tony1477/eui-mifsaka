<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'giheaderid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/reportgi/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/giheader/downpdf'),
	'downloadbuttons'=>"
	",
	'addonscripts'=>"
	",
	'columns'=>"
		{
			field:'giheaderid',
			title:'".getCatalog('giheaderid') ."',
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
			field:'gino',
			title:'".getCatalog('gino') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'gidate',
			title:'".getCatalog('gidate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'sono',
			title:'".getCatalog('sono') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'customername',
			title:'".getCatalog('customer') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'shipto',
			title:'".getCatalog('shipto') ."',
			sortable: true,
			width:'200px',
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
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.recordstatusgiheader;
		}},
	",
	'searchfield'=> array ('giheaderid','gino','sono','companyname','customer','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'gidetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/reportgi/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/reportgi/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/reportgi/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/reportgi/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/reportgi/purgedetail',array('grid'=>true)),
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