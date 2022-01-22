<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'groupaccessid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/groupaccess/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/groupaccess/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/groupaccess/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/groupaccess/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/groupaccess/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/groupaccess/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/groupaccess/downxls'),
	'columns'=>"
		{
			field:'groupaccessid',
			title:'".GetCatalog('groupaccessid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'groupname',
			title:'".GetCatalog('groupname')."',
			editor:'text',
			width:'250px',
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
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('groupaccessid','groupname')
));