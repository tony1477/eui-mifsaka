<?php
$otherbuttons = "";
if (getUserObjectValues('completepp') == 1) {
	$otherbuttons .= "<a href='javascript:void(0)' title='Already Completed'class='easyui-linkbutton' iconCls='icon-complete' plain='true' onclick='completereportpp()'></a>";
}
$this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productplanid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'url'=>Yii::app()->createUrl('production/repprodplan/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/productplan/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/productplan/downxls'),
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='Generate Barcode'class='easyui-linkbutton' iconCls='icon-genbarcode' plain='true' onclick='generatebarcode()' id='generatebarcode'></a>
		<a href='javascript:void(0)' title='Print External'class='easyui-linkbutton' iconCls='icon-ean13' plain='true' onclick='downean13()'></a>
		<a href='javascript:void(0)' title='Print External KB Poin'class='easyui-linkbutton' iconCls='icon-barsticker' plain='true' onclick='downkbpoin()'></a>
		<a href='javascript:void(0)' title='Print External Sticker'class='easyui-linkbutton' iconCls='icon-barsticker' plain='true' onclick='downsticker()'></a>
		<a href='javascript:void(0)' title='Print Internal Sticker'class='easyui-linkbutton' iconCls='icon-barsticker' plain='true' onclick='downsticker3()'></a>
		<a href='javascript:void(0)' title='Print Internal'class='easyui-linkbutton' iconCls='icon-code128' plain='true' onclick='downcode128()'></a>
    <a href='javascript:void(0)' title='Laporan Pendingan'class='easyui-linkbutton' iconCls='icon-pdf' plain='true' onclick='downpdfpendinganqtyspp()'></a>
    <a href='javascript:void(0)' title='PDF dengan Kolom Hasil'class='easyui-linkbutton' iconCls='icon-pdf' plain='true' onclick='downpdfhasil()'></a>
    <a href='javascript:void(0)' title='PDF SPP Plastik'class='easyui-linkbutton' iconCls='icon-pdf' plain='true' onclick='downpdf5()'></a>
	",
	'otherbuttons'=> $otherbuttons,
	'addonscripts'=>"
		function downean13() {
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			window.open('".$this->createUrl('productplan/downean13') ."?id='+ss);
		};
		function downsticker() {
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			window.open('".$this->createUrl('productplan/downsticker') ."?id='+ss);
		};
		function downsticker3() {
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			window.open('".$this->createUrl('productplan/downsticker3') ."?id='+ss);
		};
		function downkbpoin() {
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			window.open('".$this->createUrl('productplan/downkbpoin') ."?id='+ss);
		};
		function downcode128() {
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			window.open('".$this->createUrl('productplan/downcode128') ."?id='+ss);
		};
		function completereportpp() {
			openloader();
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.productplanid);
			}
			jQuery.ajax({'url':'".$this->createUrl('repprodplan/complete') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					closeloader();
					show('Message',data.message);
					$('#dg-repprodplan').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function downpdfpendinganqtyspp(){
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			if(ss>0){
				window.open('".$this->createUrl('productplan/downpdfpendinganqtyspp') ."?id='+ss);    
			}
			else{
				alert('".GetCatalog('chooseone')."');
			}
		}
		function downpdfhasil(){
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			if(ss>0){
				window.open('".$this->createUrl('productplan/downpdfhasil') ."?id='+ss);    
			}
			else{
				alert('".GetCatalog('chooseone')."');
			}
		}
		function downpdf5(){
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			if(ss>0){
				window.open('".$this->createUrl('productplan/downpdf5') ."?id='+ss);    
			}
			else{
				alert('".GetCatalog('chooseone')."');
			}
    }
		function generatebarcode() {
			openloader();
			var ss = [];
			var rows = $('#dg-repprodplan').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.productplanid);
			}
			jQuery.ajax({'url':'".$this->createUrl('productplan/generatebarcode') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					closeloader();
					show('Message',data.msg);
				} ,
			'cache':false});
		};
	",
	'columns'=>"
		{
			field:'productplanid',
			title:'".GetCatalog('productplanid') ."',
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
				field:'companyid',
				title:'".GetCatalog('company') ."',
				sortable: true,
				width:'250px',
				formatter: function(value,row,index){
					return row.companyname;
			}},
			{
				field:'productplanno',
				title:'".GetCatalog('productplanno') ."',
				sortable: true,
				width:'120px',
				formatter: function(value,row,index){
					if (row.warna == 1) {
						return '<div style=\"background-color:cyan;color:black\">'+value+'</div>';
					} else { return value; }
			}},
			{
				field:'productplandate',
				title:'".GetCatalog('productplandate') ."',
				sortable: true,
				width:'150px',
				formatter: function(value,row,index){
					return value;
			}},
			{
				field:'employeeid',
				title:'".GetCatalog('foreman') ."',
				sortable: true,
				width:'100px',
				formatter: function(value,row,index){
					return row.foremanname;
			}},
            {
				field:'soheaderid',
				title:'".GetCatalog('soheader') ."',
				sortable: true,
				width:'100px',
				formatter: function(value,row,index){
					return row.sono;
			}},
			{
				field:'customername',
				title:'".GetCatalog('customer') ."',
				sortable: true,
				width:'200px',
				formatter: function(value,row,index){
					return row.customername;
			}},
			{
				field:'description',
				title:'".GetCatalog('description') ."',
				sortable: true,
				width:'250px',
				formatter: function(value,row,index){
					return value;
			}},
			{
				field:'recordstatusproductplan',
				title:'".GetCatalog('recordstatus') ."',
				sortable: true,
				width:'150px',
				formatter: function(value,row,index){
					return value;
			}},
	",
	'searchfield'=> array ('productplanid','company','productplanno','productplandate','sono','customer','foreman','description','productdetail','productfg'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'productplanfg',
			'idfield'=>'productplanfgid',
			'urlsub'=>Yii::app()->createUrl('production/repprodplan/indexfg',array('grid'=>true)),
			'onselectsub'=>"
				ddvproductplandetail.edatagrid('load',{
					productplanfgid: row.productplanfgid,
					productplanid: row.productplanid
				})			
			",
			'onselect'=>"
				ddvproductplandetail.edatagrid('load',{
						productplanfgid: row.productplanfgid,
						productplanid: row.productplanid
					})
			",
			'subs'=>"
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',align:'right',width:'150px'},
				{field:'qtyres',title:'".GetCatalog('qtyOP') ."',align:'right',width:'150px',
					formatter: function(value,row,index){
						if (row.wqtyres == 1) {
							return '<div style=\"background-color:red;color:white\">'+value+'</div>';
						} else {
							return value;
						}
					}},
				{field:'stock',title:'".GetCatalog('stock') ."',align:'right',width:'150px',
					formatter: function(value,row,index){
						if (row.wstock == 1) {
							return '<div style=\"background-color:red;color:white\">'+value+'</div>';
						} else {
							return value;
						}
					}},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."',width:'200px'},
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'250px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
				{field:'startdate',title:'".GetCatalog('startdate') ."',width:'100px'},
				{field:'enddate',title:'".GetCatalog('enddate') ."',width:'100px'},
                {field:'machinecode',title:'".GetCatalog('machinecode') ."',hidden:".GetMenuAuth('spp-plastik')."},
				{field:'operator',title:'". GetCatalog('operator') ."',hidden:". GetMenuAuth('spp-plastik')."},
			",
			'columns'=>"
			"
		),
		array(
			'id'=>'productplandetail',
			'idfield'=>'productplanfgid',
			'urlsub'=>Yii::app()->createUrl('production/repprodplan/indexdetail',array('grid'=>true)),
			'onselect'=>"
			",
			'subs'=>"
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',align:'right',width:'150px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'100px'},
				{field:'fromsloccode',title:'".GetCatalog('fromsloc') ."',width:'200px'},
				{field:'stockfrom',title:'".GetCatalog('stockfrom') ."',align:'right',width:'100px'},
				{field:'tosloccode',title:'".GetCatalog('tosloc') ."',width:'200px'},
				{field:'stockto',title:'".GetCatalog('stockto') ."',align:'right',width:'100px'},
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'200px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
			",
			'columns'=>"
			"
		),
	),	
));