<?php 
$this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'arbaddebtid',
	'formtype'=>'masterdetail',
	'iswrite'=>0,
	'ispurge'=>0,
	'isupload'=>0,
	'isxls'=>0,
	'url'=>Yii::app()->createUrl('accounting/reparbaddebt/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/arbaddebt/downpdf'),
	'columns'=>"
		{
			field:'arbaddebtid',
			title:'".GetCatalog('arbaddebtid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		  }
    },
		{
			field:'docno',
			title:'".GetCatalog('docno') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
          return value;
		  }
    },
		{
			field:'docdate',
			title:'".GetCatalog('docdate') ."',
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
			field:'plantid',
			title:'".GetCatalog('plant') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return row.plantcode;
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
			field:'recordstatusarbaddebt',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
	",
	'searchfield'=> array ('arbaddebtid','companyname','docno','headernote'),
	'loadsuccess' => "",
	'rowstyler'=>"
		if (row.debit != row.credit){
      return 'background-color:blue;color:#fff;';
    }
    else {
      return 'background-color:white;color:black;font-weight:bold;';
    }	
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'arbaddebtdetid',
			'urlsub'=>Yii::app()->createUrl('accounting/reparbaddebt/indexdetail',array('grid'=>true,'list'=>true)),
			'subs'=>"
          {field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'},
					{field:'invoicedate',title:'".GetCatalog('invoicedate') ."',align:'right',width:'80px'},
					{field:'invoiceno',title:'".GetCatalog('invoiceno') ."',width:'150px'},
					{field:'paycode',title:'".GetCatalog('paycode') ."',width:'120px'},
					{field:'amount',title:'".GetCatalog('amount') ."',align:'right',width:'120px'},
					{field:'payamount',title:'".GetCatalog('payamount') ."',align:'right',width:'120px'},
			",
			'columns'=>"
			"
		),
		array(
			'id'=>'account',
			'idfield'=>'arbaddebtaccid',
			'urlsub'=>Yii::app()->createUrl('accounting/reparbaddebt/indexacc',array('grid'=>true,'list'=>true)),
			'subs'=>"
				  {field:'accountname',title:'". GetCatalog('accountname') ."',width:'350px'},
				  {field:'employeename',title:'". GetCatalog('employeename') ."',width:'350px'},
					{field:'debit',title:'". GetCatalog('debit') ."',width:'100px'},
					{field:'credit',title:'". GetCatalog('credit') ."',width:'120px'},
					{field:'currencyname',title:'". GetCatalog('currencyname') ."',width:'120px'},
					{field:'ratevalue',title:'". GetCatalog('ratevalue') ."',width:'120px'},
			",
			'columns'=>"
			"
		),
	),	
));