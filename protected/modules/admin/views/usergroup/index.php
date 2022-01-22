<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'usergroupid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/usergroup/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/usergroup/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/usergroup/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/usergroup/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/usergroup/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/usergroup/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/usergroup/downxls'),
	'columns'=>"
		{
			field:'usergroupid',
			title:'".GetCatalog('usergroupid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'useraccessid',
			title:'".GetCatalog('useraccess') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'useraccessid',
					pagination:true,
					textField:'username',
					required:true,
					url:'".$this->createUrl('useraccess/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'useraccessid',title:'".GetCatalog('useraccessid')."',width:'50px'},
						{field:'username',title:'".GetCatalog('username')."',width:'200px'},
						{field:'realname',title:'".GetCatalog('realname')."',width:'200px'}
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.username;
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
			}},",
	'searchfield'=> array ('usergroupid','username','groupname')
));