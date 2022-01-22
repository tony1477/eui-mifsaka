<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'custgradeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/custgrade/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/custgrade/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/custgrade/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/custgrade/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/custgrade/upload'),
	'downpdf'=>Yii::app()->createUrl('common/custgrade/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/custgrade/downxls'),
	'columns'=>"
		{
			field:'custgradeid',
			title:'". GetCatalog('custgradeid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'custgradename',
			title:'". GetCatalog('custgradename') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'custogradedesc',
			title:'". GetCatalog('custogradedesc') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'description',
			title:'". GetCatalog('description') ."',
			width:'300px',
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
	'searchfield'=> array ('custgradeid','custgradename')
));