<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'snroid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/snro/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/snro/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/snro/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/snro/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/snro/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/snro/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/snro/downxls'),
	'columns'=>"
		{
		field:'snroid',
		title:'".GetCatalog('snroid')."',
		sortable: true,
		width:'50px',
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
			field:'formatdoc',
			title:'".GetCatalog('formatdoc')."',
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formatno',
			title:'".GetCatalog('formatno')."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'repeatby',
			title:'".GetCatalog('repeatby')."',
			editor:'text',
			width:'100px',
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
	'searchfield'=> array ('snroid','description','formatdoc','formatno','repeatby')
));