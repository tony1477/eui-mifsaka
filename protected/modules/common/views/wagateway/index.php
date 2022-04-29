<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'wagatewayid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/wagateway/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/wagateway/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/wagateway/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/wagateway/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/wagateway/upload'),
	'downpdf'=>Yii::app()->createUrl('common/wagateway/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/wagateway/downxls'),
	'columns'=>"
		{
			field:'wagatewayid',
			title:'". GetCatalog('wagatewayid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'waname',
			title:'". GetCatalog('waname') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wanumber',
			title:'". GetCatalog('wanumber') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'devicekey',
			title:'". GetCatalog('devicekey') ."',
			width:'250px',
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
	'searchfield'=> array ('wagatewayid','wanumber','waname','devicekey')
));