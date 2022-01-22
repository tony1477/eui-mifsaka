<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'menuaccessid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/menuaccess/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/menuaccess/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/menuaccess/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/menuaccess/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/menuaccess/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/menuaccess/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/menuaccess/downxls'),
	'columns'=>"
		{
			field:'menuaccessid',
			title:'".GetCatalog('menuaccessid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
								return value;
		}},
		{
			field:'menuname',
			title:'".GetCatalog('menuname')."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'description',
			title:'".GetCatalog('description')."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'menuurl',
			title:'".GetCatalog('menuurl')."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'menuicon',
			title:'".GetCatalog('menuicon')."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
									return value;
		}},
		{
			field:'parentid',
			title:'".GetCatalog('parent')."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					pagination:true,
					idField:'menuaccessid',
					textField:'menuname',
					pagination:true,
					url:'".$this->createUrl('menuaccess/index',array('grid'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					queryParams: {
						parent:true
					},
					columns:[[
						{field:'menuaccessid',title:'".GetCatalog('menuaccessid')."',width:'50px'},
						{field:'menuname',title:'".GetCatalog('menuname')."',width:'150px'},
						{field:'description',title:'".GetCatalog('description')."',width:'250px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
									return row.parentdesc;
		}},
		{
			field:'moduleid',
			title:'".GetCatalog('module')."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'moduleid',
					textField:'modulename',
					pagination:true,
					url:'".$this->createUrl('modules/index',array('grid'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'moduleid',title:'".GetCatalog('moduleid')."',width:'50px'},
						{field:'modulename',title:'".GetCatalog('modulename')."',width:'150px'},
						{field:'moduledesc',title:'".GetCatalog('moduledesc')."',width:'200px'},
					]]
				}	
			},
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.modulename;
		}},
		{
			field:'sortorder',
			title:'".GetCatalog('sortorder')."',
			editor:'text',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('menuaccessid','menuname','description','menuurl','parentname','modulename')
));