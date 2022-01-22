<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'bankid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/bank/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/bank/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/bank/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/bank/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/bank/upload'),
	'downpdf'=>Yii::app()->createUrl('common/bank/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/bank/downxls'),
	'columns'=>"
		{
			field:'bankid',
			title:'". GetCatalog('bankid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankname',
			title:'". GetCatalog('bankname') ."',
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
	'searchfield'=> array ('bankid','bankname')
));