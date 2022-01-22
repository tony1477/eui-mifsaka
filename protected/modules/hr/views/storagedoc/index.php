<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'storagedocid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/storagedoc/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/storagedoc/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/storagedoc/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/storagedoc/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/storagedoc/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/storagedoc/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/storagedoc/downxls'),
	'columns'=>"
		{
			field:'storagedocid',
			title:'". GetCatalog('storagedocid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'storagedocname',
			title:'". GetCatalog('storagedocname') ."',
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
	'searchfield'=> array ('storagedocid','storagedocname')
));