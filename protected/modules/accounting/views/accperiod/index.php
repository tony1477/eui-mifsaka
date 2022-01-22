<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'accperiodid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/accperiod/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/accperiod/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/accperiod/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/accperiod/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/accperiod/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/accperiod/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/accperiod/downxls'),
	'columns'=>"
		{
			field:'accperiodid',
			title:'".getCatalog('accperiodid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'period',
			title:'".getCatalog('period')."', 
			editor:{type:'datebox'},
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
		'beginedit'=> "
        var ed = $('#dg-".$this->menuname."').edatagrid('getEditor',{index:index,field:'period'});
				var s = row.period;
				var ss = s.split('-');
				var y = parseInt(ss[2],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[0],10);
				var dateperiod = m+'/'+d+'/'+y; 
        $(ed.target).datebox('setValue',dateperiod);
    ",
	'searchfield'=> array ('accperiodid','period')
));