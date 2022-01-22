<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'workflowid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/workflow/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/workflow/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/workflow/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/workflow/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/workflow/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/workflow/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/workflow/downxls'),
	'columns'=>"
		{
			field:'workflowid',
			title:'".GetCatalog('workflowid')."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfname',
			title:'".GetCatalog('wfname')."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfdesc',
			title:'".GetCatalog('wfdesc')."',
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfminstat',
			title:'".GetCatalog('wfminstat')."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'wfmaxstat',
			title:'".GetCatalog('wfmaxstat')."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('workflowid','wfname','wfdesc','wfminstat','wfmaxstat')
));