<?php 
$otherbuttons = "";
if (getUserObjectValues('completepo') == 1) {
	$otherbuttons .= "<a href='javascript:void(0)' title='Already Completed'class='easyui-linkbutton' iconCls='icon-complete' plain='true' onclick='completereportpo()'></a>";
}
$this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'poheaderid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('purchasing/reportpurchaseorder/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('purchasing/poheader/downpdf'),
	'downxls'=>Yii::app()->createUrl('purchasing/poheader/downxls'),
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='Rekap DownPDF' class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdfrekap()'></a>
		<a href='javascript:void(0)' title='Generate Barcode'class='easyui-linkbutton' iconCls='icon-genbarcode' plain='true' onclick='generatebarcode()'></a>
		<a href='javascript:void(0)' title='Print QR Code'class='easyui-linkbutton' iconCls='icon-ean13' plain='true' onclick='downean13()'></a>
		<a href='javascript:void(0)' title='Print Sticker Barcode'class='easyui-linkbutton' iconCls='icon-barsticker' plain='true' onclick='downsticker()'></a>
	",
	'otherbuttons'=> $otherbuttons,
	'addonscripts'=>"
		function generatebarcode() {
			openloader();
			var ss = [];
			var rows = $('#dg-reportpurchaseorder').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.poheaderid);
			}
			jQuery.ajax({'url':'".$this->createUrl('poheader/generatebarcode') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					closeloader();
					show('Pesan',data.msg);
				} ,
			'cache':false});
		};
		function downean13() {
			var ss = [];
			var rows = $('#dg-reportpurchaseorder').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.poheaderid);
			}
			window.open('".$this->createUrl('poheader/downean13') ."?id='+ss);
		};
		function downsticker() {
			var ss = [];
			var rows = $('#dg-reportpurchaseorder').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.poheaderid);
			}
			window.open('".$this->createUrl('poheader/downsticker') ."?id='+ss);
		};
        function downpdfrekap() {
			var ss = [];
			var rows = $('#dg-reportpurchaseorder').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.poheaderid);
			}
			window.open('".$this->createUrl('poheader/downpdfrekap') ."?id='+ss);
		};
		function completereportpo() {
			var ss = [];
			var rows = $('#dg-reportpurchaseorder').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.poheaderid);
			}
			jQuery.ajax({'url':'".$this->createUrl('reportpurchaseorder/complete') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-reportpurchaseorder').edatagrid('reload');				
				} ,
				'cache':false});
		};
	",
	'columns'=>"
		{
			field:'poheaderid',
			title:'".GetCatalog('poheaderid') ."',
			width:'80px',
			sortable: true,
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
			field:'companyid',
			title:'".GetCatalog('company') ."',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'docdate',
			title:'".GetCatalog('docdate') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'pono',
			title:'".GetCatalog('pono') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				if (row.warna == 1) {
					return '<div style=\"background-color:cyan;color:black\">'+value+'</div>';
				} else {
					return value;
				}
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('supplier') ."',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'plantide',
			title:'".GetCatalog('plantcode') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'paymentmethodid',
			title:'".GetCatalog('paymentmethod') ."',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.paycode;
		}},
		{
			field:'purchasinggroupid',
			title:'".GetCatalog('purchasinggroup') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.purchasinggroupcode;
		}},
		{
			field:'shipto',
			title:'".GetCatalog('shipto') ."',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'billto',
			title:'".GetCatalog('billto') ."',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatuspoheader',
			title:'".GetCatalog('recordstatus') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
	",
	'searchfield'=> array ('poheaderid','companyname','pono','supplier','docdate','paycode','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'reportdadetailid',
			'urlsub'=>Yii::app()->createUrl('purchasing/reportpurchaseorder/indexdetail',array('grid'=>true)),
			'subs'=>"
					{field:'prno',title:'".GetCatalog('prno') ."'},
					{field:'productname',title:'".GetCatalog('productname') ."'},
					{field:'poqty',title:'".GetCatalog('poqty') ."',align:'right'},
					{field:'qtyres',title:'".GetCatalog('qtysend') ."',align:'right',formatter: function(value,row,index){
					if (row.wqtyres == 1) {
				return '<div style=\"background-color:red;color:white\">'+value+'</div>';
					} else {
						return value;
					} }},
					{field:'saldoqty',title:'".GetCatalog('saldoqty') ."',align:'right'},
					{field:'uomcode',title:'".GetCatalog('uomcode') ."'},
					{field:'netprice',title:'".GetCatalog('netprice') ."',align:'right',hidden:".GetMenuAuth('purchasing')."},
					{field:'total',title:'".GetCatalog('total') ."',align:'right',hidden:".GetMenuAuth('purchasing')."},
					{field:'currencyname',title:'".GetCatalog('currency') ."',hidden:".GetMenuAuth('purchasing')."},
					{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',hidden:".GetMenuAuth('purchasing')."},
					{field:'delvdate',title:'".GetCatalog('delvdate') ."'},
					{field:'underdelvtol',title:'".GetCatalog('underdelvtol') ."',align:'right'},
					{field:'overdelvtol',title:'".GetCatalog('overdelvtol') ."',align:'right'},
					{field:'itemtext',title:'".GetCatalog('itemtext') ."'},			",
			'columns'=>"
			"
		),
	),	
));