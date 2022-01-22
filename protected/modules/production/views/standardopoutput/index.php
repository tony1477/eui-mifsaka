<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'standardopoutputid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('production/standardopoutput/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/standardopoutput/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/standardopoutput/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/standardopoutput/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/standardopoutput/upload'),
	'downpdf'=>Yii::app()->createUrl('production/standardopoutput/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/standardopoutput/downxls'),
	'columns'=>"
		{
			field:'standardopoutputid',
			title:'". GetCatalog('standardopoutputid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'slocid',
			title:'". GetCatalog('sloccode') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'slocid',
					textField:'description',
					url:'". Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'slocid',title:'". GetCatalog('slocid')."',width:'50px'},
						{field:'sloccode',title:'". GetCatalog('sloccode')."',width:'200px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},
		{
			field:'groupname',
			title:'". GetCatalog('groupname') ."',
			editor:'text',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'producttype',
			title:'". GetCatalog('producttype') ."',
			editor:'text',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'standardvalue',
			title:'". GetCatalog('standardvalue') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
            field:'cycletime',
            title:'".GetCatalog('cycletime')."',
            editor:'text',
            width:'150px',
            sortable: true,
            formatter: function(value,row,index){
						return value;
        }},
        {
            field:'price',
            title:'".GetCatalog('insentif')."',
            editor:'text',
            width:'150px',
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
	'searchfield'=> array ('standardopoutputid','groupname','sloccode')
));