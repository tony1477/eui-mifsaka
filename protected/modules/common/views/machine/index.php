<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'machineid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/machine/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/machine/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/machine/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/machine/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/machine/upload'),
	'downpdf'=>Yii::app()->createUrl('common/machine/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/machine/downxls'),
	'columns'=>"
		{
			field:'machineid',
			title:'". GetCatalog('machineid') ."',
			sortable: true,
			width:'50px',
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
			field:'slocid',
			title:'". GetCatalog('sloc') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'". $this->createUrl('sloc/indexcombo',array('grid'=>true)) ."',
						fitColumns:true,
						pagination:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'slocid',title:'". GetCatalog('slocid')."',width:'80px'},
							{field:'sloccode',title:'". GetCatalog('sloccode')."',width:'80px'},
							{field:'description',title:'". GetCatalog('description')."',width:'200px'},
						]]
				}	
			},
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'machinecode',
			title:'". GetCatalog('code') ."',
			editor:'text',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'machinename',
			title:'". GetCatalog('machinename') ."',
			editor:'text',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('machineid','machinename')
));