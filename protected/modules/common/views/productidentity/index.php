<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productidentityid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/productidentity/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/productidentity/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/productidentity/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/productidentity/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/productidentity/upload'),
	'downpdf'=>Yii::app()->createUrl('common/productidentity/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/productidentity/downxls'),
	'columns'=>"
		{
			field:'productidentityid',
			title:'". GetCatalog('productidentityid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'identityname',
			title:'". GetCatalog('identityname') ."',
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
	'searchfield'=> array ('productidentityid','identityname')
));