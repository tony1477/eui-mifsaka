<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'grreturid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/reportgrretur/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/grretur/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/grretur/downxls'),
	'downloadbuttons'=>"
	",
	'addonscripts'=>"
	",
	'columns'=>"
		{
			field:'grreturid',
			title:'".getCatalog('grreturid') ."',
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
			}
		},
		{
			field:'grreturno',
			title:'".getCatalog('grreturno') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'grreturdate',
			title:'".getCatalog('grreturdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'".getCatalog('company') ."',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'poheaderid',
			title:'".getCatalog('pono') ."',
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return row.pono;
		}},
		{
			field:'fullname',
			title:'".getCatalog('supplier') ."',
			width:'350px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".getCatalog('headernote') ."',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.recordstatusgrretur;
		}},
	",
	'searchfield'=> array ('grreturid','grreturdate','companyname','grreturno','pono','supplier','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'grreturdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/reportgrretur/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/reportgrretur/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/reportgrretur/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/reportgrretur/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/reportgrretur/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".getCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".getCatalog('qty') ."',align:'right',width:'150px'},
				{field:'uomcode',title:'".getCatalog('uomcode') ."',width:'150px'},
				{field:'sloccode',title:'".getCatalog('sloc') ."',width:'150px'},
				{field:'description',title:'".getCatalog('storagebin') ."',width:'150px'},
				{field:'itemnote',title:'".getCatalog('itemnote') ."',width:'200px'},
			",
			'columns'=>"
			"
		),
	),	
));