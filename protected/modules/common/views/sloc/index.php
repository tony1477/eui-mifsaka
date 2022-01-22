<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'slocid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/sloc/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/sloc/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/sloc/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/sloc/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/sloc/upload'),
	'downpdf'=>Yii::app()->createUrl('common/sloc/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/sloc/downxls'),
	'columns'=>"
		{
			field:'slocid',
			title:'". GetCatalog('slocid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title:'". GetCatalog('plant') ."',
			width:'150px',
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'plantid',
					textField:'plantcode',
					url:'". $this->createUrl('plant/index',array('grid'=>true)) ."',
					fitColumns:true,
					required:true,
					queryParams:{
						combo:true,
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'plantid',title:'". GetCatalog('plantid')."',width:'50px'},
						{field:'plantcode',title:'". GetCatalog('plantcode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'sloccode',
			title:'". GetCatalog('sloccode') ."',
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
	'searchfield'=> array ('slocid','plantcode','sloccode','description')
));