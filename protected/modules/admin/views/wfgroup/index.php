<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'wfgroupid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/wfgroup/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/wfgroup/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/wfgroup/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/wfgroup/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/wfgroup/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/wfgroup/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/wfgroup/downxls'),
	'columns'=>"
		{
			field:'wfgroupid',
			title:'".GetCatalog('wfgroupid') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'workflowid',
			title:'".GetCatalog('workflow') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'workflowid',
					textField:'wfdesc',
					url:'".$this->createUrl('workflow/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'workflowid',title:'".GetCatalog('workflowid')."',width:'50px'},
						{field:'wfdesc',title:'".GetCatalog('wfdesc')."',width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.wfdesc;
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
						textField:'groupname',
						url:'".$this->createUrl('groupaccess/index',array('grid'=>true)) ."',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'groupaccessid',title:'".GetCatalog('groupaccessid')."',width:'50px'},
							{field:'groupname',title:'".GetCatalog('groupname')."',width:'200px'},
						]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.groupname;
		}},
		{
			field:'wfbefstat',
			title:'".GetCatalog('wfbefstat') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfrecstat',
			title:'".GetCatalog('wfrecstat') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}},",
	'searchfield'=> array ('wfgroupid','workflow','groupaccess')
));