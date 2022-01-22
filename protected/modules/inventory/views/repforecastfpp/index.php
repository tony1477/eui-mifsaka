<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'forecastfppid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>1,
	'url'=>Yii::app()->createUrl('inventory/repforecastfpp/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('inventory/forecastfpp/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/forecastfpp/downxls'),
	'downloadbuttons'=>"",
	'addonscripts'=>"",
	'columns'=>"
		{
			field:'forecastfppid',
			title:'".getCatalog('forecastfppid') ."',
			sortable: true,
			width:'60px',
			formatter: function(value,row,index){
				return value;	
		}},
		{
			field:'docdate',
			title:'".getCatalog('docdate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'perioddate',
			title:'".getCatalog('perioddate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
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
			field:'productcollectid',
			title:'".getCatalog('productcollection') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.collectionname;
		}},
    {
    field:'sumpendingpo',
    title:'".GetCatalog('sumpendingpo')."',
    sortable: true,
    width:'125px',
    formatter: function(value,row,index){
      return value;
    }},
    {
    field:'sumpredictpo',
    title:'".GetCatalog('sumpredictpo')."',
    sortable: true,
    width:'125px',
    formatter: function(value,row,index){
      return value;
    }},
    {
    field:'sumtotalpo',
    title:'".GetCatalog('sumtotalpo')."',
    sortable: true,
    width:'175px',
    formatter: function(value,row,index){
      return value;
    }},
		{
			field:'headernote',
			title:'".getCatalog('headernote') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.statusname;
		}}
	",
	'searchfield'=> array ('forecastfppid','perioddateyear','perioddatemonth','companyname','productname','sloccode','collectionname'),
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'repforecastfppdetailid',
			'urlsub'=>Yii::app()->createUrl('inventory/repforecastfpp/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productname',title:'".getCatalog('productname') ."',width:'400px'},
				{field:'uomcode',title:'".getCatalog('uom') ."',width:'80px'},
				{field:'sloccode',title:'".getCatalog('sloc') ."',width:'100px'},
				{field:'qtyforecast',title:'".getCatalog('qtyforecast') ."',align:'right',width:'80px'},
				{field:'avg3month',title:'".getCatalog('avg3month') ."',align:'right',width:'80px'},
				{field:'avgperday',title:'".getCatalog('avgperday') ."',align:'right',width:'80px'},
				{field:'qtymax',title:'".getCatalog('qtymax') ."',align:'right',width:'80px'},
				{field:'qtymin',title:'".getCatalog('qtymin') ."',align:'right',width:'80px'},
				{field:'leadtime',title:'".getCatalog('leadtime') ."',align:'right',width:'80px'},
				{field:'pendingpo',title:'".getCatalog('pendingpo') ."',align:'right',width:'80px'},
				{field:'saldoawal',title:'".getCatalog('saldoawal') ."',align:'right',width:'80px'},
				{field:'grpredict',title:'".getCatalog('grpredict') ."',align:'right',width:'80px'},
				{field:'prqty',title:'".getCatalog('prqty') ."',align:'right',width:'80px'},
				{field:'prqtyreal',title:'".getCatalog('prqtyreal') ."',align:'right',width:'80px'},
				{field:'price',title:'".getCatalog('price') ."',align:'right',width:'80px',hidden:".GetMenuAuth('purchasing')."},
				{field:'povalueout',title:'".getCatalog('povalueout') ."',align:'right',width:'80px',hidden:".GetMenuAuth('purchasing')."},
				{field:'povalue',title:'".getCatalog('povalue') ."',align:'right',width:'80px',hidden:".GetMenuAuth('purchasing')."},
				{field:'povaluetot',title:'".getCatalog('povaluetot') ."',align:'right',width:'80px',hidden:".GetMenuAuth('purchasing')."},
				{field:'qtyshare',title:'".getCatalog('qtyshare') ."',align:'right',width:'80px',hidden:".GetMenuAuth('purchasing')."},
        
			",
			'columns'=>"
			"
		),
	),	
));
