<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'groupmenuauthid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/groupmenuauth/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/groupmenuauth/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/groupmenuauth/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/groupmenuauth/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/groupmenuauth/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/groupmenuauth/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/groupmenuauth/downxls'),
	'columns'=>"
		{
			field:'groupmenuauthid',
			title:'".GetCatalog('groupmenuauthid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'groupaccessid',
			title:'".GetCatalog('groupaccess') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'groupaccessid',
					pagination:true,
					required:true,
					textField:'groupname',
					url:'".$this->createUrl('groupaccess/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'groupaccessid',title:'".GetCatalog('groupaccessid')."',width:'50px'},
						{field:'groupname',title:'".GetCatalog('groupname')."',width:'200px'},
					]]
				}	
			},
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return row.groupname;
		}},
		{
			field:'menuauthid',
			title:'".GetCatalog('menuauth') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'menuauthid',
					pagination: true,
					textField:'menuobject',
					required:true,
					url:'".$this->createUrl('menuauth/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'menuauthid',title:'".GetCatalog('menuauthid')."',width:'50px'},
						{field:'menuobject',title:'".GetCatalog('menuobject')."',width:'200px'},
					]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.menuobject;
		}},
		{
			field:'menuvalueid',
			title:'".GetCatalog('menuvalueid') ."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},",
	'searchfield'=> array ('groupmenuauthid','groupname','menuobject','menuvalueid')
));