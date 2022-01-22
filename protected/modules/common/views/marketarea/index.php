<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'marketareaid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/marketarea/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/marketarea/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/marketarea/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/marketarea/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/marketarea/upload'),
	'downpdf'=>Yii::app()->createUrl('common/marketarea/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/marketarea/downxls'),
	'columns'=>"
		{
			field:'marketareaid',
			title:'".getCatalog('marketareaid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'marketname',
			title:'".getCatalog('marketname')."', 
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
	'searchfield'=> array ('marketareaid','marketname','recordstatus')
));