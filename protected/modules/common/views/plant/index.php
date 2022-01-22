<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'plantid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/plant/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/plant/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/plant/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/plant/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/plant/upload'),
	'downpdf'=>Yii::app()->createUrl('common/plant/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/plant/downxls'),
	'columns'=>"
		{
			field:'plantid',
			title:'". GetCatalog('plantid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'".GetCatalog('companyname')."',
			width:'300px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true))."',
					fitColumns:true,
					required:true,
					pagination:true,
					queryParams:{
						auth:true
					},
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'".GetCatalog('company')."',width:'100px'},
						{field:'companyname',title:'".GetCatalog('companyname')."',width:'300px'},
					]],
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantcode',
			title:'". GetCatalog('plantcode') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:'". GetCatalog('description') ."',
			width:'250px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			width:'50px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('plantid','company','plantcode','description')
));