<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productcollectid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/productcollection/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/productcollection/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/productcollection/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/productcollection/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/productcollection/upload'),
	'downpdf'=>Yii::app()->createUrl('common/productcollection/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/productcollection/downxls'),
	'columns'=>"
		{
			field:'productcollectid',
			title:'". GetCatalog('productcollectionid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'collectionname',
			title:'". GetCatalog('collectionname') ."',
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
	'searchfield'=> array ('productcollectid','collectionname')
));