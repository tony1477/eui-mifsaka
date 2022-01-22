<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productbrandid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/productbrand/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/productbrand/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/productbrand/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/productbrand/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/productbrand/upload'),
	'downpdf'=>Yii::app()->createUrl('common/productbrand/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/productbrand/downxls'),
	'columns'=>"   
		{
			field:'productbrandid',
			title:'". GetCatalog('productbrandid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'brandname',
			title:'". GetCatalog('brandname') ."',
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
	'searchfield'=> array ('productbrandid','brandname')
));