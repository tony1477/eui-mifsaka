<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'transstockid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('inventory/reportts/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/transstock/downpdf'),
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='List Material Yang Tidak ada di Gudang Sumber'class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdf1()'></a>
		<a href='javascript:void(0)' title='List Material Yang Tidak ada di Gudang Tujuan'class='easyui-linkbutton' iconCls='icon-box2' plain='true' onclick='downpdf2()'></a>		
		<a href='javascript:void(0)' title='List Material Yang Satuannya Tidak sama dengan Data Gudang Sumber'class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdf3()'></a>
		<a href='javascript:void(0)' title='List Material Yang Satuannya Tidak sama dengan Data Gudang Tujuan'class='easyui-linkbutton' iconCls='icon-box2' plain='true' onclick='downpdf4()'></a>
		<a href='javascript:void(0)' title='List Product Yang Gudang Sumber Belum Dicentang'class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='downpdf5()'></a> 
	",
	'addonscripts'=>"
		function downpdf1() {
			var ss = [];
			var rows = $('#dg-reportts').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.transstockid);
			}
			window.open('".$this->createUrl('reportts/downpdf1') ."?id='+ss);
		}    
		function downpdf2() {
			var ss = [];
			var rows = $('#dg-reportts').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.transstockid);
			}
			window.open('".$this->createUrl('reportts/downpdf2') ."?id='+ss);
		} 
		function downpdf3() {
			var ss = [];
			var rows = $('#dg-reportts').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.transstockid);
			}
			window.open('".$this->createUrl('reportts/downpdf3') ."?id='+ss);
		}
		function downpdf4() {
			var ss = [];
			var rows = $('#dg-reportts').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.transstockid);
			}
			window.open('".$this->createUrl('reportts/downpdf4') ."?id='+ss);
		}    
		function downpdf5() {
			var ss = [];
			var rows = $('#dg-reportts').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.transstockid);
			}
			window.open('".$this->createUrl('reportts/downpdf5') ."?id='+ss);
		}
	",
	'columns'=>"
		{
			field:'transstockid',
			title:'".getCatalog('transstockid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'docdate',
			title:'".getCatalog('docdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'transstockno',
			title:'".getCatalog('transstockno') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'deliveryadviceid',
			title:'".getCatalog('dano') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.dano;
		}},
		{
			field:'slocfromid',
			title:'".getCatalog('slocfrom') ."',
			sortable: true,
			formatter: function(value,row,index){
				return row.slocfromcode;
		}},
		{
			field:'sloctoid',
			title:'".getCatalog('slocto') ."',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloctocode;
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
			field:'recordstatustransstock',
			title:'".getCatalog('recordstatus') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
	",
	'searchfield'=> array ('transstockid','docdate','transstockno','dano','slocfrom','slocto','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'transstockdetid',
			'urlsub'=>Yii::app()->createUrl('inventory/reportts/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".getCatalog('productname') ."'},
				{field:'qty',title:'".getCatalog('qty') ."'},
				{field:'daqty',title:'".getCatalog('daqty') ."'},
				{field:'stok',title:'".getCatalog('stok') ."'},
				{field:'uomcode',title:'".getCatalog('uomcode') ."'},
				{field:'rakasal',title:'".getCatalog('storagebinfrom') ."'},
				{field:'raktujuan',title:'".getCatalog('storagebinto') ."'},
				{field:'itemtext',title:'".getCatalog('itemtext') ."',width:'300px'},
			",
			'columns'=>"
			"
		),
	),	
));