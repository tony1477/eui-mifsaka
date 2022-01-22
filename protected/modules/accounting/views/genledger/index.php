<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'genledgerid',
	'formtype'=>'master',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>1,
	'url'=>Yii::app()->createUrl('accounting/genledger/index',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/genledger/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/genledger/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/genledger/downxls'),
	'columns'=>"
		{
			field:'genledgerid',
			title:'". GetCatalog('genledgerid') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'". GetCatalog('company') ."',
			editor:'text',
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'accountid',
			title:'". GetCatalog('account') ."',
			editor:'text',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accountname;
		}},
		{
			field:'genjournalid',
			title:'". GetCatalog('journalno') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.journalno;
		}},
		{
			field:'debit',
			title:'". GetCatalog('debit') ."',
			editor:'text',
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+row.symbol+' '+value+'</div>';
		}},
		{
			field:'credit',
			title:'". GetCatalog('credit') ."',
			editor:'text',
			width:'130px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+row.symbol+' '+value+'</div>';
		}},
		{
			field:'postdate',
			title:'". GetCatalog('postdate') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ratevalue',
			title:'". GetCatalog('ratevalue') ."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
		}}",
	'searchfield'=> array ('genledgerid','companyname','journalno','accountcode','accountname','postdate','headernote')
));