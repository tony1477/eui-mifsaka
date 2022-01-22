<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'groupmenuid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/groupmenu/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/groupmenu/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/groupmenu/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/groupmenu/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/groupmenu/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/groupmenu/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/groupmenu/downxls'),
	'columns'=>"
		{
		field:'groupmenuid',
		title:'".GetCatalog('groupmenuid')."',
		sortable: true,
		width:'50px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'groupaccessid',
		title:'".GetCatalog('groupaccess')."',
		sortable: true,
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'groupaccessid',
				pagination:true,
				queryParams: {
					combo:true
				},
				required:true,
				textField:'groupname',
				url:'".$this->createUrl('groupaccess/index',array('grid'=>true))."',
				fitColumns:true,
				loadMsg: '".GetCatalog('pleasewait')."',
				columns:[[
					{field:'groupaccessid',title:'".GetCatalog('groupaccessid')."',width:'50px'},
					{field:'groupname',title:'".GetCatalog('groupname')."',width:'150px'},
				]]
			}	
		},
		width:'250px',
		formatter: function(value,row,index){
			return row.groupname;
	}},
	{
		field:'menuaccessid',
		title:'".GetCatalog('menuaccess')."',
		sortable: true,
		width:'250px',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'menuaccessid',
				textField:'description',
				pagination:true,
				queryParams: {
					combo:true
				},
				required:true,
				url:'".$this->createUrl('menuaccess/index',array('grid'=>true))."',
				fitColumns:true,
				loadMsg: '".GetCatalog('pleasewait')."',
				columns:[[
					{field:'menuaccessid',title:'".GetCatalog('menuaccessid')."',width:'50px'},
					{field:'menuname',title:'".GetCatalog('menuname')."',width:'150px'},
					{field:'description',title:'".GetCatalog('description')."',width:'200px'},
				]]
			}	
		},
		formatter: function(value,row,index){
			return row.description;
	}},
	{
		field:'isread',
		title:'".GetCatalog('isread')."',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		width:'50px',
		align: 'center',
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
			} else {
				return '';
			}
	}},
	{
		field:'iswrite',
		title:'".GetCatalog('iswrite')."',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		width:'50px',
		align: 'center',
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
			} else {
				return '';
			}
	}},
	{
		field:'ispost',
		title:'".GetCatalog('ispost')."',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		width:'50px',
		align: 'center',
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
			} else {
				return '';
			}
	}},
	{
		field:'isreject',
		title:'".GetCatalog('isreject')."',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		width:'50px',
		align: 'center',
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
			} else {
				return '';
			}
	}},
	{
		field:'isupload',
		title:'".GetCatalog('isupload')."',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		width:'50px',
		align: 'center',
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
			} else {
				return '';
			}
	}},
	{
		field:'isdownload',
		title:'".GetCatalog('isdownload')."',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		width:'50px',
		align: 'center',
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
			} else {
				return '';
			}
	}},
	{
		field:'ispurge',
		title:'".GetCatalog('ispurge')."',
		editor:{type:'checkbox',options:{on:'1',off:'0'}},
		sortable: true,
		width:'50px',
		align: 'center',
		formatter: function(value,row,index){
			if (value == 1){
				return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
			} else {
				return '';
			}
		}},",
	'searchfield'=> array ('groupmenuid','groupname','menuname','menudesc')
));