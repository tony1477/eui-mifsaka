<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productseriesid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/productseries/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/productseries/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/productseries/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/productseries/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/productseries/upload'),
	'downpdf'=>Yii::app()->createUrl('common/productseries/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/productseries/downxls'),
	'columns'=>"
		{
			field:'productseriesid',
			title:'". GetCatalog('productseriesid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'seriescode',
			title:'". GetCatalog('seriescode') ."',
			editor:'text',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'description',
			title:'". GetCatalog('description') ."',
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
	'searchfield'=> array ('productseriesid','description')
));