<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'bsheaderid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/reportbs/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/bsheader/downpdf'),
	'downloadbuttons'=>"
	",
	'addonscripts'=>"
	",
	'columns'=>"
		{
			field:'bsheaderid',
			title:'".getCatalog('bsheaderid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				if (row.recordstatus == 1) {
					return '<div style=\"background-color:green;color:white\">'+value+'</div>';
				} else 
				if (row.recordstatus == 2) {
					return '<div style=\"background-color:yellow;color:black\">'+value+'</div>';
				} else 
					if (row.recordstatus == 3) {
					return '<div style=\"background-color:cyan;color:black\">'+value+'</div>';
				} else 
					if (row.recordstatus == 4) {
					return '<div style=\"background-color:blue;color:white\">'+value+'</div>';
				} else 
					if (row.recordstatus == 5) {
					return '<div style=\"background-color:red;color:white\">'+value+'</div>';
				} else 
						if (row.recordstatus == 0) {
						return '<div style=\"background-color:black;color:white\">'+value+'</div>';
					}
		}},
		{
			field:'bsdate',
			title:'".getCatalog('bsdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bsheaderno',
			title:'".getCatalog('bsheaderno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'slocid',
			title:'".getCatalog('sloc') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return row.sloccode;
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
			field:'recordstatusbsheader',
			title:'".getCatalog('recordstatus') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
	",
	'searchfield'=> array ('bsheaderid','bsdate','bsheaderno','sloccode','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'reportbsdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/reportbs/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".getCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".getCatalog('qty') ."',align:'right',width:'150px'},
				{field:'qtystock',title:'".getCatalog('qtystock') ."',align:'right',width:'150px'},
				{field:'uomcode',title:'".getCatalog('uomcode') ."',width:'100px'},
				{field:'expiredate',title:'".getCatalog('expiredate') ."',width:'100px'},
				{field:'materialstatusname',title:'".getCatalog('materialstatusname') ."',width:'150px'},
				{field:'ownershipname',title:'".getCatalog('ownershipname') ."',width:'100px'},
				{field:'currencyname',title:'".getCatalog('currency') ."',hidden:".GetMenuAuth('currency').",align:'right'},
				{field:'buyprice',title:'".getCatalog('buyprice') ."',hidden:".GetMenuAuth('currency').",align:'right'},
				{field:'buypricestock',title:'".getCatalog('buypricestock') ."',hidden:".GetMenuAuth('currency').",align:'right'},
				{field:'currencyrate',title:'".getCatalog('currencyrate') ."',hidden:".GetMenuAuth('currency').",align:'right'},
			",
			'columns'=>"
			"
		),
	),	
));