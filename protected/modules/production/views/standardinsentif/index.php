<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'standardinsentifid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('production/standardinsentif/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/standardinsentif/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/standardinsentif/save',array('grid'=>true)),
	'ispost'=>1,
	'isreject'=>1,
	'approveurl'=>Yii::app()->createUrl('production/standardinsentif/approve'),
	'rejecturl'=>Yii::app()->createUrl('production/standardinsentif/delete'),
	//'destroyurl'=>Yii::app()->createUrl('production/standardinsentif/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/standardinsentif/upload'),
	'downpdf'=>Yii::app()->createUrl('production/standardinsentif/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/standardinsentif/downxls'),
    'downloadbuttons'=>"
		<a href='javascript:void(0)' title='Copy'class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='copyInsentif()'></a>
	",
	'addonscripts'=>"
		function copyInsentif() {
			var ss = [];
			var rows = $('#dg-standardinsentif').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.standardinsentifid);
			}
			jQuery.ajax({'url':'".$this->createUrl('standardinsentif/copyInsentif') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-standardinsentif').edatagrid('reload');				
				} ,
				'cache':false});
		};
	",
	   'columns'=>"
        {
			field:'standardinsentifid',
			title:'". GetCatalog('standardinsentifid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'companyid',
			title:'". GetCatalog('companyname') ."',
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
				loadMsg: '".GetCatalog('pleasewait')."',
				columns:[[
					{field:'companyid',title:'".GetCatalog('companyid')."',width:'80px'},
					{field:'companyname',title:'".GetCatalog('companyname')."',width:'400px'},
				]]
			}	
		},
		width:'250px',		
		sortable: true,
		formatter: function(value,row,index){
            return row.companyname;
		}},
        {
            field:'perioddate',
            title:'".GetCatalog('perioddate')."',
            editor: {
                type: 'datebox',
                options:{
                    formatter:dateformatter,
                    required:true,
                    parser:dateparser
                }
            },
            width:'150px',
            sortable: true,
            formatter: function(value,row,index){
                return value;
		}},
		{
		field:'price',
		title:'".GetCatalog('price') ."',
		sortable: true,
		editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		width:'150px',
		required:true,
		formatter: function(value,row,index){
            return value;
		}
	},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'150px',
			//editor:{type:'checkbox',options:{on:'1',off:'0'}},
            editor:{
                type: 'textbox'
            },
			sortable: true,
			formatter: function(value,row,index){
            /*if (value == 1){
                return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
            } else {
                return '';
            }*/
                return row.statusname;
		}}",
	'searchfield'=> array ('standardinsentifid','companyname')
));