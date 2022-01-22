<?php 
$otherbuttons = "";
if (getUserObjectValues('completeso') == 1) {
	$otherbuttons .= "<a href='javascript:void(0)' title='Already Completed'class='easyui-linkbutton' iconCls='icon-complete' plain='true' onclick='completereportso()'></a>";
}
$this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'soheaderid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('order/reportso/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('order/soheader/downpdf'),
	'downloadbuttons'=>"
        <a href='javascript:void(0)' title='PDF Tanpa Harga' class='easyui-linkbutton' iconCls='icon-pdf' plain='true' onclick='downpdf1()'></a>
        <a href='javascript:void(0)' title='List Material Yang Tidak ada di Gudang' class='easyui-linkbutton' iconCls='icon-box' plain='true' onclick='downpdf2()'></a>
        <a href='javascript:void(0)' title='List Material Yang Satuan Tidak ada di Data Gudang'class='easyui-linkbutton' iconCls='icon-box2' plain='true' onclick='downpdf3()'></a>
	",
	'otherbuttons'=> $otherbuttons,
	'addonscripts'=>"
		function completereportso() {
			openloader();
			var ss = [];
			var rows = $('#dg-reportso').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.soheaderid);
			}
			jQuery.ajax({'url':'".$this->createUrl('reportso/complete') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					closeloader();
					show('Pesan',data.message);
					$('#dg-reportso').edatagrid('reload');				
				} ,
				'cache':false});
		}
        function downpdf1() {
			var ss = [];
			var rows = $('#dg-reportso').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.soheaderid);
			}	
			window.open('".$this->createUrl('soheader/downpdf1') ."?id='+ss);
		}
        function downpdf2() {
			var ss = [];
			var rows = $('#dg-reportso').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.soheaderid);
			}	
			window.open('".$this->createUrl('reportso/downpdf2') ."?id='+ss);
		}
		function downpdf3() {
			var ss = [];
			var rows = $('#dg-reportso').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.soheaderid);
			}
			window.open('".$this->createUrl('reportso/downpdf3') ."?id='+ss);
		}
	",
	'columns'=>"
		{
			field:'soheaderid',
			title:'".GetCatalog('soheaderid') ."',
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
					return '<div style=\"background-color:orange;color:white\">'+value+'</div>';
				} else 
				if (row.recordstatus == 6) {
					return '<div style=\"background-color:red;color:white\">'+value+'</div>';
				} else 
				if (row.recordstatus == 0) {
					return '<div style=\"background-color:black;color:white\">'+value+'</div>';
				}
		}},
		{
			field:'sono',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				if (row.wso == 1) {
					return '<div style=\"background-color:cyan;color:black\">'+value+'</div>';
				} else {
							return value;
				}
		}},
		{
			field:'sodate',
			title:'".GetCatalog('sodate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'280px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return row.customername;
		}},
        {
            field:'isdisplay',
            title:'".GetCatalog('isdisplay')."',
            align:'center',
            width:'100px',
            sortable: true,
            formatter: function(value,row,index){
                if (value == 1){
                    return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png\"></img>';
                } else {
                    return '';
                }
            }},
        {
            field:'sotype',
            title:'". GetCatalog('sotype')."',
            sortable: true,
            width:'250px',
            formatter: function(value,row,index){
                return row.sotypename;
        }},
        {
            field:'materialtypeid',
            title:'".GetCatalog('materialtype')."',
            sortable: true,
            width:'250px',
            formatter: function(value,row,index){
                return row.description;
            }},
        {
            field:'packageid',
            title:'". GetCatalog('package')."',
            sortable: true,
            width:'250px',
            formatter: function(value,row,index){
                return row.packagename;
            }},
                    
		{
			field:'top',
			title:'".GetCatalog('top') ."',
			sortable: true,
			width:'80px',
			align:'right',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'currentlimit',
			title:'".GetCatalog('currentarlimit') ."',
			sortable: true,
			width:'120px',
			align:'right',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'creditlimit',
			title:'".GetCatalog('creditlimit') ."',
			sortable: true,
			width:'120px',
			align:'right',
			formatter: function(value,row,index){
				return value;
		}},					
		{
			field:'pendinganso',
			title:'".GetCatalog('pendinganso') ."',
			sortable: true,
			width:'150px',
			align:'right',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'totalbefdisc',
			title:'".GetCatalog('totalbefdisc') ."',
			sortable: true,
			width:'150px',
			align:'right',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'totalaftdisc',
			title:'".GetCatalog('totalaftdisc') ."',
			sortable: true,
			width:'150px',
			align:'right',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'pono',
			title:'".GetCatalog('pono')."',
			sortable: true,
			width:'125px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'pocustno',
			title:'".GetCatalog('pocustno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".GetCatalog('sales') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.employeename;
		}},
		{
			field:'paymentmethodid',
			title:'".GetCatalog('paymentmethod') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.paycode;
		}},
		{
			field:'taxid',
			title:'".GetCatalog('tax') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.taxcode;
		}},
		{
			field:'shipto',
			title:'".GetCatalog('shipto') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.shipto;
		}},
		{
			field:'billto',
			title:'".GetCatalog('billto') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.billto;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.headernote;
		}},
		{
			field:'recordstatussoheader',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'createddate',
			title:'".GetCatalog('createddate') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'updatedate',
			title:'".GetCatalog('updatedate') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
	",
	'searchfield'=> array ('soheaderid','companyname','sono','customer','pocustno','pono','headernote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'rowstyler'=>"
		switch(row.warna) {
				case '1':
				return 'background-color:red;color:white;font-weight:bold;';
				break;
			case '2':
				return 'background-color:orange;color:white;font-weight:bold;';
				break;
			case '3':
				return 'background-color:yellow;color:black;font-weight:bold;';
				break;
			default :
				return 'background-color:white;color:black;font-weight:bold;';
		}	
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'sodetailid',
			'urlsub'=>Yii::app()->createUrl('order/reportso/indexdetail',array('grid'=>true,'list'=>true)),
			'subs'=>"
				{field:'productname',title:'".GetCatalog('productname') ."'},
				{field:'qty',title:'".GetCatalog('qty') ."',align:'right'},
				{field:'giqty',title:'".GetCatalog('giqty') ."',align:'right',
					formatter: function(value,row,index){
						if (row.wgi == 1) {
							return '<div style=\"background-color:red;color:white\">'+value+'</div>';
						} else {
							return value;
						}
					}},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',align:'right',
					formatter: function(value,row,index){
						if (row.wstock == 1) {
							return '<div style=\"background-color:red;color:white\">'+value+'</div>';
						} else {
							return value;
						}
					}},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."'},
				{field:'sloccode',title:'".GetCatalog('sloc') ."'},
				{field:'price',title:'".GetCatalog('price') ."',align:'right',hidden: (row.packageid === null) ? false : true},
				{field:'currencyname',title:'".GetCatalog('currency') ."'},
				{field:'currencyrate',title:'".GetCatalog('currencyrate') ."',align:'right'},
				{field:'total',title:'".GetCatalog('total') ."',align:'right',hidden: (row.packageid === null) ? false : true},
				{field:'delvdate',title:'".GetCatalog('delvdate') ."'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."'},
			",
			'columns'=>"
			"
		),
		array(
			'id'=>'disc',
			'idfield'=>'sodiscid',
			'urlsub'=>Yii::app()->createUrl('order/reportso/indexdisc',array('grid'=>true,'list'=>true)),
			'subs'=>"
				{field:'sodetailid',title:'".GetCatalog('sodetailid') ."'},
				{field:(row.packageid === null) ? 'discvalue' : 'discvalue1',title:'".GetCatalog('discvalue') ."'},
			",
			'columns'=>"
			"
		),
	),	
));