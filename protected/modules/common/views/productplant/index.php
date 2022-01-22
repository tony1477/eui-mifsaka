<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productplantid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/productplant/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/productplant/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/productplant/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/productplant/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/productplant/upload'),
	'downpdf'=>Yii::app()->createUrl('common/productplant/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/productplant/downxls'),
	'columns'=>"
		{
			field:'productplantid',
			title:'". GetCatalog('productplantid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productid',
			title:'". GetCatalog('product') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'550px',
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'". $this->createUrl('product/index',array('grid'=>true)) ."',
						queryParams:{
							combo:true
						},
						fitColumns:true,
						pagination:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'productid',title:'". GetCatalog('productid')."',width:'80px'},
							{field:'productname',title:'". GetCatalog('productname')."',width:'300px'},
						]]
				}	
			},
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'slocid',
			title:'". GetCatalog('sloc') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'". $this->createUrl('sloc/indexcombo',array('grid'=>true)) ."',
						fitColumns:true,
						pagination:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'slocid',title:'". GetCatalog('slocid')."',width:'80px'},
							{field:'sloccode',title:'". GetCatalog('sloccode')."',width:'80px'},
							{field:'description',title:'". GetCatalog('description')."',width:'200px'},
						]]
				}	
			},
			width:'300px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'unitofissue',
			title:'". GetCatalog('uom') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'". $this->createUrl('unitofmeasure/index',array('grid'=>true)) ."',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'80px'},
							{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'80px'},
							{field:'description',title:'". GetCatalog('description')."',width:'200px'},
						]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}},
		{
			field:'isautolot',
			title:'". GetCatalog('isautolot') ."',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'sled',
			title:'". GetCatalog('sled') ."',
			editor:{type:'numberbox',precision:'0'},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'snroid',
			title:'". GetCatalog('snro') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'snroid',
					textField:'description',
					url:'". Yii::app()->createUrl('admin/snro/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'snroid',title:'". GetCatalog('snroid')."',width:'50px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.snrodesc;
		}},
		{
			field:'materialgroupid',
			title:'". GetCatalog('materialgroup') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'materialgroupid',
					textField:'description',
					url:'". $this->createUrl('materialgroup/indextrx',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'materialgroupid',title:'". GetCatalog('unitofmeasureid')."',width:'50px'},
						{field:'materialgroupcode',title:'". GetCatalog('materialgroupcode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialgroupcode;
		}},
        {
			field:'mgprocessid',
			title:'". GetCatalog('mgprocess') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'mgprocessid',
					textField:'description',
					url:'". $this->createUrl('mgprocess/indextrx',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:false,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'mgprocessid',title:'". GetCatalog('mgprocessid')."',width:'50px'},
						{field:'mgprocesscode',title:'". GetCatalog('mgprocesscode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.mgprocesscode;
		}},
		{
			field:'issource',
			title:'". GetCatalog('source') ."',
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
	'searchfield'=> array ('productplantid','product','sloc','unitofissue','materialgroup','mgprocesscode')
));