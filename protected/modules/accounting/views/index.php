<input type="hidden" name="companyid" id="companyid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'fohulid',    
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/fohul/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/fohul/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/fohul/save',array('grid'=>true)),
    'ispost' => 1,
    'isreject' => 1,
	'approveurl'=>Yii::app()->createUrl('accounting/fohul/approve'),
	'rejecturl'=>Yii::app()->createUrl('accounting/fohul/delete'),
	'uploadurl'=>Yii::app()->createUrl('accounting/fohul/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/fohul/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/fohul/downxls'),
    'addonscripts'=>"

        $('#dg-".$this->menuname."').edatagrid({
	    onEdit: function(index,row)
        {
			if (row.foh != undefined) {
				var value = row.foh;
				row.foh = value.replace('.', '');
				var value = row.ul;
				row.ul = value.replace('.', '');
			}
  	},
    });
		",
	'columns'=>"
		{
			field:'fohulid',
            title:'". GetCatalog('fohulid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'docdate',
			title:'". GetCatalog('docdate')."', 
			editor:{type:'datebox'},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
        {
			field:'perioddate',
			title:'". GetCatalog('perioddate')."', 
			editor:{type:'datebox'},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:'". GetCatalog('companyname') ."',
			sortable: true,
            editor:{
                type : 'combogrid',
                options: {
                    panelWidth:'500px',
                    mode: 'remote',
                    method: 'get',
                    idField: 'companyid',
                    textField: 'companyname',
                    required: true,
                    pagination: true,
                    url:'". Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true,))."',
                    fitColumns: true,
                    loadMsg: '". GetCatalog('pleasewait')."',
                    columns:[[
                        {field:'companyid',title:'". GetCatalog('companyid')."', width:'50px'},
                        {field:'companyname',title:'". GetCatalog('company')."', width:'250px'},
                    ]],
                    onBeforeLoad: function(param) {
                        var row = $('#dg-fohul').edatagrid('getSelected');
                        if(row==null){
                            $(\"input[name='companyid']\").val('0');
                        }
				    },
                    onSelect: function(index,row){
                        var companyid = row.companyid;
                        $(\"input[name='companyid']\").val(row.companyid);
                    },
                },
            },
			width:'300px',
			formatter: function(value,row,index){
			return row.companyname;
		}},
        {
		field:'plantid',
		title:'".GetCatalog('plant')."',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'plantid',
						textField:'plantcode',
						url:'".Yii::app()->createUrl('common/plant/index',array('grid'=>true))."',
						fitColumns:true,
						required:true,
						pagination:true,
						queryParams:{
							trxcom:true
						},
						onBeforeLoad: function(param) {
				            var companyid = $(\"input[name='companyid']\").val();
                            if(companyid=='')
                            {
                                var row = $('#dg-fohul').datagrid('getSelected');
                                param.companyid = row.companyid;
                            }
                            else
                            {
                                param.companyid = $(\"input[name='companyid']\").val(); 
                            }
						},
                        onHidePanel: function(){
                            var rowss = $('#dg-".$this->menuname."').edatagrid('getSelected');
                            if (rowss){
                                var index = $('#dg-".$this->menuname."').edatagrid('getRowIndex', rowss);
                            }

                            var nilai2 = $('#dg-".$this->menuname."').datagrid('getRows'); 

                            var materialgroup = $('#dg-".$this->menuname."').datagrid('getEditor', {index: index, field:'materialgroupid'});
                            $(materialgroup.target).combogrid({required:true,disabled:false});                           
                        },
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'plantid',title:'".GetCatalog('plantid')."'},
							{field:'plantcode',title:'".GetCatalog('plantcode')."'},
							{field:'description',title:'".GetCatalog('description')."'},
						]]
				}	
			},
                width:'120px',
		sortable: true,
		formatter: function(value,row,index){
            return row.plantcode;
            }
        },
        {
			field:'materialgroupid',
            title:'". GetCatalog('materialgroup')."', 
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'materialgroupid',
					textField:'description',
					required:true,
					pagination: true,
					url:'". Yii::app()->createUrl('common/materialgroup/indextrx',array('grid'=>true)) ."',
					fitColumns:true,
                    disabled:true,
                    onHidePanel : function(){
                    
                        var rowss = $('#dg-".$this->menuname."').edatagrid('getSelected');
                        if (rowss){
                            var index = $('#dg-".$this->menuname."').edatagrid('getRowIndex', rowss);
                        }

                        var nilai2 = $('#dg-".$this->menuname."').datagrid('getRows'); 

                        var plantid = $('#dg-".$this->menuname."').datagrid('getEditor', {index: index, field:'plantid'});
                        var materialgroupid = $('#dg-".$this->menuname."').datagrid('getEditor', {index: index, field:'materialgroupid'});
                        var foh = $('#dg-".$this->menuname."').datagrid('getEditor', {index: index, field:'foh'});
                        var ul = $('#dg-".$this->menuname."').datagrid('getEditor', {index: index, field:'ul'});
                    
                        jQuery.ajax({'url':'".$this->createUrl('fohulacc/generate')."',
                            'data':{
                                'plantid':$(plantid.target).combogrid('getValue'),
                                'materialgroupid':$(materialgroupid.target).combogrid('getValue'),
                            },
                            'type':'post',
                            'dataType':'json',
                            'success':function(data) {
                                $(foh.target).numberbox('setValue',data.foh);
                                $(ul.target).numberbox('setValue',data.ul);
                            } ,
                            'cache':false});
                    },
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'materialgroupid',title:'". GetCatalog('materialgroupid')."',width:'50px'},
						{field:'description',title:'". GetCatalog('description')."',width:'400px'},
					]],
				},	
			},
			width:'300px',
			sortable:'true',
			formatter: function(value,row,index){
				return row.description;
			}
		},
		{
			field:'foh',
			title:'". GetCatalog('foh') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
			}
		},
        {
			field:'ul',
			title:'". GetCatalog('ul') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
			}
		},
        {
			field:'totalfoh',
			title:'". GetCatalog('totalfoh') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
			}
		},
        {
			field:'totalul',
			title:'". GetCatalog('totalul') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
			}
		},
        {
			field:'qtyoutput',
			title:'". GetCatalog('qtyoutput') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
			}
		},
        {
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.statusname;
		}}
        ",
	'beginedit'=>"        
	",
	'searchfield'=> array ('fohulid','docdate','companyname','fullname','plantcode')
));