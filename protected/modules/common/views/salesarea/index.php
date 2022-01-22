<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'salesareaid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/salesarea/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/salesarea/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/salesarea/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/salesarea/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/salesarea/upload'),
	'downpdf'=>Yii::app()->createUrl('common/salesarea/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/salesarea/downxls'),
	'columns'=>"
		{
			field:'salesareaid',
			title:'".getCatalog('salesareaid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'areaname',
			title:'".getCatalog('areaname')."', 
			editor:'text',
			width:'500px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',title:'".getCatalog('recordstatus')."',
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
	'searchfield'=> array ('salesareaid','areaname','recordstatus')
));