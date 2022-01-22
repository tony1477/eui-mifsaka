<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'genjournalid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('accounting/reportgl/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/genjournal/downpdf'),
	'columns'=>"
		{
			field:'genjournalid',
			title:'".GetCatalog('genjournalid') ."',
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
			width:'350px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'journalno',
			title:'".GetCatalog('journalno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'referenceno',
			title:'".GetCatalog('referenceno') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
						return value;
					}},
		{
			field:'journaldate',
			title:'".GetCatalog('journaldate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'journalnote',
			title:'".GetCatalog('journalnote') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusgenjournal',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'searchfield'=> array ('genjournalid','companyname','journalno','referenceno','journaldate','journalnote'),
	'headerform'=> "
	",
	'loadsuccess' => "
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'journaldetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/genjournal/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.debit;
					},align:'right',width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return row.symbol + ' ' + row.credit;
					},align:'right',width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
			'columns'=>"
				
			"
		),
	),	
));