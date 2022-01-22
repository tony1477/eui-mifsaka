<input type="hidden" name="companyid" id="companyid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'fohulaccid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/fohulacc/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/fohulacc/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/fohulacc/save',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/fohulacc/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/fohulacc/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/fohulacc/downxls'),
    'addonscripts'=>"
		",
	'columns'=>"
		{
			field:'fohulaccid',
            title:'". GetCatalog('fohulaccid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
        {
		field:'slocid',
		title:'".GetCatalog('sloc')."',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'".Yii::app()->createUrl('common/sloc/indextrx',array('grid'=>true))."',
						fitColumns:true,
						required:true,
						pagination:true,
						queryParams:{
							trxcom:true
						},
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'slocid',title:'".GetCatalog('slocid')."'},
							{field:'sloccode',title:'".GetCatalog('sloccode')."'},
							{field:'description',title:'".GetCatalog('description')."'},
						]]
				}	
			},
                width:'120px',
		sortable: true,
		formatter: function(value,row,index){
            return row.sloccode;
            }
        },
        {
			field:'mgprocessid',
            title:'". GetCatalog('mgprocess')."', 
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'mgprocessid',
					textField:'description',
					required:true,
					pagination: true,
					url:'". Yii::app()->createUrl('common/mgprocess/indextrx',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'mgprocessid',title:'". GetCatalog('mgprocessid')."',width:'50px'},
						{field:'description',title:'". GetCatalog('description')."',width:'400px'},
					]]
				}	
			},
			width:'300px',
			sortable:'true',
			formatter: function(value,row,index){
				return row.description;
			}
		},
        {
            field:'fohcode',
            title:'".GetCatalog('fohcode')."',
            sortable: true,
            editor:'text',
            width:'250px',
            formatter: function(value,row,index){
                return value;
            }}, 
        {
            field:'ulcode',
            title:'".GetCatalog('ulcode')."',
            sortable: true,
            editor:'text',
            width:'250px',
            formatter: function(value,row,index){
                return value;
            }},
        {
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
            editor:{type:'checkbox',options:{on:'1',off:'0'}},
			width:'150px',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}
        ",
	'beginedit'=>"        
	",
	'searchfield'=> array ('fohulaccid','sloccode','mgprocess')
));