<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'custcategoryid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/custcategory/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/custcategory/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/custcategory/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/custcategory/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/custcategory/upload'),
	'downpdf'=>Yii::app()->createUrl('common/custcategory/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/custcategory/downxls'),
	'columns'=>"
		{
			field:'custcategoryid',
			title:'". GetCatalog('custcategoryid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'custcategoryname',
			title:'". GetCatalog('custcategoryname') ."',
			width:'150px',
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
	'searchfield'=> array ('custcategoryid','custcategoryname')
));