<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'doctypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/doctype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/doctype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/doctype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/doctype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/doctype/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/doctype/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/doctype/downxls'),
	'columns'=>"
		{
			field:'doctypeid',
			title:'". GetCatalog('doctypeid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'doctypename',
			title:'". GetCatalog('doctypename') ."',
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
	'searchfield'=> array ('doctypeid','doctypename')
));