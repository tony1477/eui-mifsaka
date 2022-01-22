<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'requestedbyid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('inventory/requestedby/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/requestedby/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/requestedby/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/requestedby/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('inventory/requestedby/upload'),
	'downpdf'=>Yii::app()->createUrl('inventory/requestedby/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/requestedby/downxls'),
	'columns'=>"
		{
			field:'requestedbyid',
			title:'".getCatalog('requestedbyid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'requestedbycode',
			title:'".getCatalog('requestedbycode')."', 
			editor:'text',
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'description',
			title:'".getCatalog('description')."', 
			editor:'text',
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable:'true',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		}",
	'searchfield'=> array ('requestedbyid','requestedbycode','description')
));