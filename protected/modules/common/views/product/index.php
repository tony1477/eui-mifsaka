<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/product/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/product/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/product/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/product/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/product/upload'),
	'downpdf'=>Yii::app()->createUrl('common/product/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/product/downxls'),
	'columns'=>"
		{
			field:'productid',
			title:'". GetCatalog('productid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productname',
			title:'". GetCatalog('productname') ."',
			editor:'text',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productpic',
			title:'". GetCatalog('productpic') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			readonly: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isstock',
			title:'". GetCatalog('isstock') ."',
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
		}},
		{
			field:'isfohulbom',
			title:'". GetCatalog('isfohulbom') ."',
			align:'center',
			width:'70px',
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
			field:'iscontinue',
			title:'". GetCatalog('iscontinue') ."',
			align:'center',
			width:'70px',
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
			field:'barcode',
			title:'". GetCatalog('barcode') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'k3lnumber',
			title:'". GetCatalog('k3lnumber') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypeid',
			title:'". GetCatalog('materialtype') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'materialtypeid',
					textField:'description',
					url:'". Yii::app()->createUrl('common/materialtype/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'materialtypeid',title:'". GetCatalog('materialtypeid')."',width:'50px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.materialtypedesc;
		}},
        {
			field:'productidentityid',
			title:'". GetCatalog('productidentity') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'productidentityid',
					textField:'identityname',
					url:'". Yii::app()->createUrl('common/productidentity/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'productidentityid',title:'". GetCatalog('productidentityid')."',width:'50px'},
						{field:'identityname',title:'". GetCatalog('identityname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.identitydesc;
		}},
        {
			field:'productbrandid',
			title:'". GetCatalog('productbrand') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'productbrandid',
					textField:'brandname',
					url:'". Yii::app()->createUrl('common/productbrand/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'productbrandid',title:'". GetCatalog('productbrandid')."',width:'50px'},
						{field:'brandname',title:'". GetCatalog('brandname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.branddesc;
		}},
        {
			field:'productcollectid',
			title:'". GetCatalog('productcollect') ."',
			width:'150px',
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
			sortable: true,
			formatter: function(value,row,index){
				return row.collectdesc;
		}},
        {
			field:'productseriesid',
			title:'". GetCatalog('productseries') ."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'productseriesid',
					textField:'description',
					url:'". Yii::app()->createUrl('common/productseries/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'productseriesid',title:'". GetCatalog('productseriesid')."',width:'50px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.seriesdesc;
		}},
		{
			field:'panjang',
			title:'". GetCatalog('length') ."',
			width:'100px',
			editor:{
				type:'numberbox',
				options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				}
			},
			align: 'right',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'lebar',
			title:'". GetCatalog('width') ."',
			width:'100px',
			editor:{
				type:'numberbox',
				options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				}
			},
			align: 'right',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'tinggi',
			title:'". GetCatalog('height') ."',
			width:'100px',
			editor:{
				type:'numberbox',
				options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				}
			},
			align: 'right',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'density',
			title:'". GetCatalog('density') ."',
			width:'100px',
			editor:{
				type:'numberbox',
				options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				}
			},
			align: 'right',
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
	'searchfield'=> array ('productid','productname','barcode','length','width','height','identityname','brandname','collectionname','productseries','isstock','isfohulbom','iscontinue','materialtype','recordstatus')
));