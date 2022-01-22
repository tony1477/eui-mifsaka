<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'snrodid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/snrodet/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/snrodet/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/snrodet/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/snrodet/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/snrodet/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/snrodet/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/snrodet/downxls'),
	'columns'=>"
		{
			field:'snrodid',
			title:'".GetCatalog('snrodid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'".Yii::app()->createUrl('admin/company/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'".GetCatalog('companyid')."',width:'50px'},
						{field:'companyname',title:'".GetCatalog('companyname')."',width:'200px'},
					]]
				}	
			},
			width:'350px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
			}
		},
		{
			field:'snroid',
			title:'".GetCatalog('snro') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'snroid',
					textField:'description',
					url:'".$this->createUrl('snro/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'snroid',title:'".GetCatalog('snroid')."',width:'50px'},
						{field:'description',title:'".GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},
		{
			field:'curdd',
			title:'".GetCatalog('curdd') ."',
			editor:'numberbox',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'curmm',
			title:'".GetCatalog('curmm') ."',
			editor:'numberbox',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'curyy',
			title:'".GetCatalog('curyy') ."',
			editor:'numberbox',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'curvalue',
			title:'".GetCatalog('curvalue') ."',
			editor:'numberbox',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},",
	'searchfield'=> array ('snrodid','companyname','snrodesc')
));