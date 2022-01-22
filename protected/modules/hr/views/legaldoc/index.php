<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'legaldocid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/legaldoc/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/legaldoc/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/legaldoc/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/legaldoc/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/legaldoc/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/legaldoc/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/legaldoc/downxls'),
	'columns'=>"
		{
			field:'legaldocid',
			title:'". GetCatalog('legaldocid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'doctypeid',
			title:'". GetCatalog('doctype') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'doctypeid',
					textField:'doctypename',
					url:'". Yii::app()->createUrl('hr/doctype/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'doctypeid',title:'". GetCatalog('doctypeid')."',width:'50px'},
						{field:'doctypename',title:'". GetCatalog('doctype')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.doctypename;
		}},
        {
			field:'docname',
			title:'". GetCatalog('docname') ."',
			editor:'text',
			width:'450px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'docno',
			title:'". GetCatalog('docno') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'docdate',
			title:'".getCatalog('docdate')."', 
			editor:{type:'datebox'},
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
        {
			field:'doccompanyid',
			title:'". GetCatalog('company') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'". Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'". GetCatalog('companyid')."',width:'50px'},
						{field:'companyname',title:'". GetCatalog('company')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
        {
			field:'storagedocid',
			title:'". GetCatalog('storagedoc') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'storagedocid',
					textField:'storagedocname',
					url:'". Yii::app()->createUrl('hr/storagedoc/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'storagedocid',title:'". GetCatalog('storagedocid')."',width:'50px'},
						{field:'storagedocname',title:'". GetCatalog('storagedocname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.storagedocname;
		}},
        {
			field:'description',
			title:'". GetCatalog('description') ."',
			editor:'text',
			width:'550px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		",
        'beginedit'=> "
        var ed = $('#dg-".$this->menuname."').edatagrid('getEditor',{index:index,field:'docdate'});
        var s = row.docdate;
        var ss = s.split('-');
        var y = parseInt(ss[2],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[0],10);
        var datedoc = m+'/'+d+'/'+y; 
        $(ed.target).datebox('setValue',datedoc);
    ",
	'searchfield'=> array ('legaldocid','docname')
));