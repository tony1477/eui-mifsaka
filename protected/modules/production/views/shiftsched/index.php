<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'shiftschedid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('production/shiftsched/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/shiftsched/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/shiftsched/save',array('grid'=>true)),
	//'destroyurl'=>Yii::app()->createUrl('production/shiftsched/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/shiftsched/upload'),
	'downpdf'=>Yii::app()->createUrl('production/shiftsched/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/shiftsched/downxls'),
	'columns'=>"
		{
			field:'shiftschedid',
			title:'". GetCatalog('shiftschedid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'shiftcode',
			title:'". GetCatalog('shiftcode') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
            field:'shiftname',
            title:'".GetCatalog('shiftname')."',
            editor:'text',
            width:'150px',
            sortable: true,
            formatter: function(value,row,index){
                return value;
        }},
		{
			field:'description',
			title:'". GetCatalog('description') ."',
			editor:'text',
			width:'250px',
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
	'searchfield'=> array ('shiftschedid','issuename','description')
));