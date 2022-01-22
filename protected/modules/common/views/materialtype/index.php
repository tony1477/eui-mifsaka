<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'materialtypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/materialtype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/materialtype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/materialtype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/materialtype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/materialtype/upload'),
	'downpdf'=>Yii::app()->createUrl('common/materialtype/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/materialtype/downxls'),
	'columns'=>"
		{
			field:'materialtypeid',
			title:'". GetCatalog('materialtypeid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypecode',
			title:'". GetCatalog('materialtypecode') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:'". GetCatalog('description') ."',
			width:'250px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'nourut',
			title:'". GetCatalog('nourut') ."',
				editor:{
					type:'textbox',
					options: {
						required:false
					}
			},
			width:'80px',		
			sortable: true,		
			formatter: function(value,row,index){
								return value;
			}
		},
		{
			field:'isview',
			title:'". getCatalog('isview') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
			}
		}},
		{
			field:'parentid',
			title:'". GetCatalog('parent') ."',
				editor:{
					type:'combogrid',
					options:{
					panelWidth:450,
					mode : 'remote',
					method:'get',
					idField:'materialtypeid',
					textField:'description',
					url:'". Yii::app()->createUrl('common/materialtype/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:false,
					queryParams:{
							combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
							{field:'materialtypeid',title:'". GetCatalog('materialtypeid')."',width:50},
							{field:'description',title:'". GetCatalog('description')."',width:200},
					]]
				}},
			width:'120px',		
			sortable: true,		
			formatter: function(value,row,index){
				return row.parentdesc;
			}
		},
        {
			field:'iseditpriceso',
			title:'". getCatalog('iseditpriceso') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
			}
		}},
        {
			field:'iseditdiscso',
			title:'". getCatalog('iseditdiscso') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
			}
		}},
        {
			field:'isedittop',
			title:'". getCatalog('isedittop') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
			}
		}},
		{
			field:'isparent',
			title:'". getCatalog('isparent?') ."',
			width:80,
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
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
	'searchfield'=> array ('materialtypeid','materialtypecode','description')
));