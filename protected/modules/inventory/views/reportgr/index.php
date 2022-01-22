<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'grheaderid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/reportgr/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/grheader/downpdf'),
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='Generate Barcode'class='easyui-linkbutton' iconCls='icon-genbarcode' plain='true' onclick='generatebarcode()'></a>		
		<a href='javascript:void(0)' title='Print External Sticker'class='easyui-linkbutton' iconCls='icon-barsticker' plain='true' onclick='downsticker()'></a>
		<a href='javascript:void(0)' title='Stock yang melebihi PO'class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='downpdf1()'></a>    
    <a href='javascript:void(0)' title='List Material Yang Tidak ada Di Gudang'class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdf2()'></a>    
    <a href='javascript:void(0)' title='List Material Yang Satuannya Tidak sama dengan Data Gudang'class='easyui-linkbutton' iconCls='icon-box2' plain='true' onclick='downpdf3()'></a>
	",
	'addonscripts'=>"
		function generatebarcode() {
			var ss = [];
			var rows = $('#dg-reportgr').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.grheaderid);
			}
			jQuery.ajax({'url':'".$this->createUrl('grheader/generatebarcode') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Message',data.msg);
				} ,
			'cache':false});
		}
		function downsticker() {
			var ss = [];
			var rows = $('#dg-reportgr').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.grheaderid);
			}
			window.open('".$this->createUrl('grheader/downsticker') ."?id='+ss);
		}
		function downpdfgrheader() {
			var ss = [];
			var rows = $('#dg-reportgr').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.grheaderid);
			}
			window.open('".$this->createUrl('grheader/downpdf')."?id='+ss);
		}
		function downpdf1() {
			var ss = [];
			var rows = $('#dg-reportgr').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.grheaderid);
			}
			window.open('".$this->createUrl('grheader/downpdf1') ."?id='+ss);
		}    
		function downpdf2() {
			var ss = [];
			var rows = $('#dg-reportgr').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.grheaderid);
			}
			window.open('".$this->createUrl('grheader/downpdf2') ."?id='+ss);
		}    
		function downpdf3() {
			var ss = [];
			var rows = $('#dg-reportgr').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.grheaderid);
			}
			window.open('".$this->createUrl('grheader/downpdf3') ."?id='+ss);
		}
	",
	'columns'=>"
		{
			field:'grheaderid',
			title:'".getCatalog('grheaderid') ."',
			sortable: true,
			width:'80px',
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
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'grno',
			title:'".getCatalog('grno') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'grdate',
			title:'".getCatalog('grdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'poheaderid',
			title:'".getCatalog('pono') ."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.pono;
		}},
		{
			field:'fullname',
			title:'".getCatalog('supplier') ."',
			width:'250px',
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
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.recordstatusgrheader;
		}},
	",
	'searchfield'=> array ('grheaderid','grdate','grno','companyname','pono','supplier','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'grdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/reportgr/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('inventory/reportgr/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('inventory/reportgr/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('inventory/reportgr/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('inventory/reportgr/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".getCatalog('productname') ."',width:'300px'},
				{field:'qty',title:'".getCatalog('qty') ."',align:'right',width:'100px'},
				{field:'uomcode',title:'".getCatalog('uomcode') ."',width:'100px'},
				{field:'sloccode',title:'".getCatalog('sloccode') ."',width:'150px'},
				{field:'description',title:'".getCatalog('storagebin') ."',width:'200px'},
				{field:'barcode',title:'".getCatalog('barcode') ."',width:'150px'},
				{field:'itemtext',title:'".getCatalog('itemtext') ."',width:'250px'},
			",
			'columns'=>"
			"
		),
	),	
));