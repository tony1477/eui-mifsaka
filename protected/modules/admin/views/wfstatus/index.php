<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'wfstatusid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/wfstatus/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/wfstatus/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/wfstatus/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/wfstatus/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/wfstatus/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/wfstatus/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/wfstatus/downxls'),
	'columns'=>"
		{
			field:'wfstatusid',
			title:'".GetCatalog('wfstatusid') ."',
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
			field:'wfstat',
			title:'".GetCatalog('wfstat') ."',
			editor:'numberbox',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfstatusname',
			title:'".GetCatalog('wfstatusname') ."',
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},",
	'searchfield'=> array ('wfstatusid','wfname','wfdesc','wfstat','wfstatusname')
));