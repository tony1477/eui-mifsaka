<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'standardissueid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('production/standardissue/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/standardissue/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/standardissue/save',array('grid'=>true)),
	//'destroyurl'=>Yii::app()->createUrl('production/standardissue/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/standardissue/upload'),
	'downpdf'=>Yii::app()->createUrl('production/standardissue/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/standardissue/downxls'),
	'columns'=>"
		{
			field:'standardissueid',
			title:'". GetCatalog('standardissueid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'issuename',
			title:'". GetCatalog('issuename') ."',
			editor:'text',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
            field:'cycletime',
            title:'".GetCatalog('cycletime')."',
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
			width:'200px',
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
	'searchfield'=> array ('standardissueid','issuename','description')
));