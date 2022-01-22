<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'deliveryadviceid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/reportda/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/deliveryadvice/downpdf'),
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='List Material Yang Tidak ada di Gudang Sumber'class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdf1()'></a>
		<a href='javascript:void(0)' title='List Material Yang Tidak ada di Gudang Tujuan'class='easyui-linkbutton' iconCls='icon-box2' plain='true' onclick='downpdf2()'></a>
		<a href='javascript:void(0)' title='List Material Yang Satuannya Tidak sama dengan Data Gudang Sumber'class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdf3()'></a>		
		<a href='javascript:void(0)' title='List Material Yang Satuannya Tidak sama dengan Data Gudang Tujuan'class='easyui-linkbutton' iconCls='icon-box2' plain='true' onclick='downpdf4()'></a>
		<a href='javascript:void(0)' title='List Product Yang Gudang Sumber Belum Dicentang'class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdf5()'></a>
	",
	'addonscripts'=>"
		function downpdf1() {
			var ss = [];
			var rows = $('#dg-reportda').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.deliveryadviceid);
			}	
			window.open('".$this->createUrl('deliveryadvice/downpdf1') ."?id='+ss);
		}
		function downpdf2() {
			var ss = [];
			var rows = $('#dg-reportda').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.deliveryadviceid);
			}
			window.open('".$this->createUrl('deliveryadvice/downpdf2') ."?id='+ss);
		}
		function downpdf3() {
			var ss = [];
			var rows = $('#dg-reportda').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.deliveryadviceid);
			}
			window.open('".$this->createUrl('deliveryadvice/downpdf3') ."?id='+ss);
		}
		function downpdf4() {
			var ss = [];
			var rows = $('#dg-reportda').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.deliveryadviceid);
			}
			window.open('".$this->createUrl('deliveryadvice/downpdf4') ."?id='+ss);
		}
		function downpdf5() {
			var ss = [];
			var rows = $('#dg-reportda').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.deliveryadviceid);
			}
			window.open('".$this->createUrl('deliveryadvice/downpdf5') ."?id='+ss);
		}
	",
	'columns'=>"
		{
			field:'deliveryadviceid',
			title:'".getCatalog('deliveryadviceid') ."',
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
			field:'dadate',
			title:'".getCatalog('dadate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'dano',
			title:'".getCatalog('dano') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				if (row.warna == 1){
					return '<div style=\"background-color:cyan;color:black\">'+value+'</div>';
				} else {
					return value;
				}
		}},
		{
			field:'productplanid',
			title:'".getCatalog('productplan') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'soheaderid',
			title:'".getCatalog('soheader') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.sono;
			}},
		{
			field:'productoutputid',
			title:'".getCatalog('productoutput') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.productoutputno;
		}},
		{
			field:'useraccessid',
			title:'".getCatalog('useraccess') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.username;
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
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.recordstatusda;
		}}
	",
	'searchfield'=> array ('deliveryadviceid','dano','productplanno','sono','productoutputno','sloccode','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'reportdadetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/reportda/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".getCatalog('productname') ."'},
				{field:'qty',title:'".getCatalog('qty') ."'},
				{field:'prqty',title:'".getCatalog('prqty') ."'},
				{field:'giqty',title:'".getCatalog('trqty') ."',
					formatter: function(value,row,index){
						if (row.wtfs == 1) {
						return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
					}
					}},
				{field:'grqty',title:'".getCatalog('grqty') ."'},
				{field:'poqty',title:'".getCatalog('poqty') ."'},
				{field:'stock',title:'".getCatalog('stock') ."',
				formatter: function(value,row,index){
					if (row.wstock == 1) {
						return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
					}
					}},
				{field:'uomcode',title:'".getCatalog('uomcode') ."'},
				{field:'requestedbycode',title:'".getCatalog('requestedbycode') ."'},
				{field:'reqdate',title:'".getCatalog('reqdate') ."'},
				{field:'tosloccode',title:'".getCatalog('sloc') ."'},
				{field:'itemtext',title:'".getCatalog('itemtext') ."'},
			",
			'columns'=>"
			"
		),
	),	
));