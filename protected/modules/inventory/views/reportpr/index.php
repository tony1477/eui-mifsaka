<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'prheaderid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/reportpr/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/prheader/downpdf'),
	'downloadbuttons'=>"
        <a href='javascript:void(0)' title='Check FPP - Stock Cabang PDF' class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdf1()'></a>
        <a href='javascript:void(0)' title='Check FPP - Stock Cabang XLS' class='easyui-linkbutton' iconCls='icon-xls' plain='true' onclick='downxls1()'></a>
	",
	'addonscripts'=>"
        function downpdf1() {
			var ss = [];
			var rows = $('#dg-reportpr').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.prheaderid);
			}	
			window.open('".$this->createUrl('prheader/downpdf1') ."?id='+ss);
		};
        
        function downxls1() {
			var ss = [];
			var rows = $('#dg-reportpr').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.prheaderid);
			}
			window.open('".$this->createUrl('prheader/downxls1') ."?id='+ss);
		};
	",
	'columns'=>"
		{
			field:'prheaderid',
			title:'".getCatalog('prheaderid') ."',
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
					return '<div style=\"background-color:red;color:white\">'+value+'</div>';
				} else 
					if (row.recordstatus == 0) {
					return '<div style=\"background-color:black;color:white\">'+value+'</div>';
				}
		}},
		{
			field:'prno',
			title:'".getCatalog('prno') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				if (row.warna == 1) {
					return '<div style=\"background-color:cyan;color:black\">'+value+'</div>';
				} else {
					return value;
			}
		}},
		{
			field:'prdate',
			title:'".getCatalog('prdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'deliveryadviceid',
			title:'".getCatalog('dano') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.dano;
		}},
		{
			field:'slocid',
			title:'".getCatalog('sloc') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'headernote',
			title:'".getCatalog('headernote') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return row.recordstatusprheader
			},
		}
	",
	'searchfield'=> array ('prheaderid','prno','dano','prdate','sloccode','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'reportprdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/reportpr/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/reportpr/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/reportpr/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/reportpr/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/reportpr/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".getCatalog('productname') ."'},
				{field:'qty',title:'".getCatalog('qty') ."',align:'right'},
				{field:'poqty',title:'".getCatalog('poqty') ."',align:'right',formatter: function(value,row,index){
					if (row.wqty == 1) {
						return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
					}
				}},
				{field:'uomcode',title:'".getCatalog('uomcode') ."'},
				{field:'reqdate',title:'".getCatalog('reqdate') ."'},
				{field:'itemtext',title:'".getCatalog('itemtext') ."'},
			",
			'columns'=>"
			"
		),
	),	
));