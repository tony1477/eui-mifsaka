<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'matforecastfppid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('inventory/matforecastfpp/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/matforecastfpp/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/matforecastfpp/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/matforecastfpp/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('inventory/matforecastfpp/upload'),
	'downpdf'=>Yii::app()->createUrl('inventory/matforecastfpp/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/matforecastfpp/downxls'),
	'columns'=>"
		{
			field:'matforecastfppid',
			title:'".getCatalog('matforecastfppid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:'".getCatalog('company') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'".getCatalog('companyid')."',width:'80px'},
						{field:'companyname',title:'".getCatalog('companyname')."',width:'250px'},
					]]
				}	
			},
			width:'300px',			
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'productcollectid',
			title:'". GetCatalog('productcollection') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'productcollectid',
					textField:'collectionname',
					url:'". Yii::app()->createUrl('common/productcollection/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'productcollectid',title:'". GetCatalog('productcollectid')."',width:'50px'},
						{field:'collectionname',title:'". GetCatalog('collectionname')."',width:'200px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.collectionname;
		}},
    {
			field:'pricefrom',
			title:'".getCatalog('companyprice') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'".getCatalog('companyid')."',width:'80px'},
						{field:'companyname',title:'".getCatalog('companyname')."',width:'250px'},
					]]
				}	
			},
			width:'300px',			
			sortable: true,
			formatter: function(value,row,index){
				return row.pricefromname;
		}},
		{
			field:'isgenerate',
			title:'".getCatalog('isgenerate?')."',
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
		},
	",
	'beginedit'=>"",
	'searchfield'=> array ('matforecastfppid','collectionname','company',)
));