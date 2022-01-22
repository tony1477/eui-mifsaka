<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'mgprocessid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/mgprocess/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/mgprocess/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/mgprocess/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/mgprocess/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/mgprocess/upload'),
	'downpdf'=>Yii::app()->createUrl('common/mgprocess/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/mgprocess/downxls'),
	'columns'=>"
		{
			field:'mgprocessid',
			title:'". GetCatalog('mgprocessid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'mgprocesscode',
			title:'". GetCatalog('mgprocesscode') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:'". GetCatalog('description') ."',
			width:'350px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'parentmgprocessid',
			title:'". GetCatalog('parentmatgroup') ."',
			width:'400px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'mgprocessid',
					textField:'description',
					queryParams:{
						combo:true
					},
					url:'". $this->createUrl('mgprocess/indexfg',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'mgprocessid',title:'". GetCatalog('mgprocessid')."',width:'50px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.parentmgprocessdesc;
		}},
		{
			field:'isprocess',
			title:'". GetCatalog('isprocess') ."',
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
	'searchfield'=> array ('mgprocessid','mgprocesscode','description','parentmatgroup')
));