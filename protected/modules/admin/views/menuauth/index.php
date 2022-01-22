<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'menuauthid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/menuauth/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/menuauth/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/menuauth/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/menuauth/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/menuauth/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/menuauth/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/menuauth/downxls'),
	'columns'=>"
		{
			field:'menuauthid',
			title:'".GetCatalog('menuauthid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'menuobject',
			title:'".GetCatalog('menuobject')."',
			editor:'text',
			width:'150px',
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
	'searchfield'=> array ('menuauthid','menuobject')
));