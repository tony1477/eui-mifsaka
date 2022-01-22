<input type="hidden" name="companyid" id="companyid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'budgetid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/budget/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/budget/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/budget/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/budget/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/budget/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/budget/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/budget/downxls'),
	'columns'=>"
		{
			field:'budgetid',title:'". GetCatalog('budgetid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'budgetdate',
			title:'". GetCatalog('budgetdate')."', 
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
                        var row = $('#dg-budget').edatagrid('getSelected');
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
		title:'".GetCatalog('plantcode')."',
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
                                var row = $('#dg-budget').datagrid('getSelected');
                                param.companyid = row.companyid;
                            }
                            else
                            {
                                param.companyid = $(\"input[name='companyid']\").val(); 
                            }
						},
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'plantid',title:'".GetCatalog('plantid')."'},
							{field:'plantcode',title:'".GetCatalog('plantcode')."'},
							{field:'description',title:'".GetCatalog('description')."'},
						]]
				}	
			},
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.plantcode;
		}
	},
		{
			field:'accountid',title:'". GetCatalog('account')."', 
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountname',
					required:true,
					pagination: true,
					url:'". $this->createUrl('account/index',array('grid'=>true,'trxcom'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
                    queryParams:{
							companyid:0
						},
                    onBeforeLoad: function(param) {
                        var companyid = $(\"input[name='companyid']\").val();
                        if(companyid=='')
                        {
                            var row = $('#dg-budget').datagrid('getSelected');
                            param.companyid = row.companyid;
                        }
                        else
                        {
                            param.companyid = $(\"input[name='companyid']\").val(); 
                        }
				    },
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'100px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('company')."',width:'200px'},
					]]
				}	
			},
			width:'350px',
			sortable:'true',
			formatter: function(value,row,index){
				return row.accountcode+' - '+ row.accountname;
			}
		},
		{
			field:'budgetamount',
			title:'". GetCatalog('budgetamount') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
			}
		},
		{
			field:'pakaibudget',
			title:'". GetCatalog('pakaibudget') ."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
			}
		},",
	'beginedit'=>"
			var ed = $('#dg-".$this->menuname."').edatagrid('getEditor',{index:index,field:'budgetdate'});
			var s = row.budgetdate;
			var ss = s.split('-');
			var y = parseInt(ss[2],10);
			var m = parseInt(ss[1],10);
			var d = parseInt(ss[0],10);
			var datebudget = m+'/'+d+'/'+y; 
			$(ed.target).datebox('setValue',datebudget);
	",
	'rowstyler'=>"
		if (row.warna == 1) {
			return 'background-color:red;color:white;';
		} else 
		if (row.warna == 2) {
			return 'background-color:cyan;color:black;';
		} else 
						if (row.warna == 3) {
			return 'background-color:red;color:white;';
		} else 
						if (row.warna == 4) {
			return 'background-color:cyan;color:black;';
		}
	",
	'searchfield'=> array ('budgetid','budgetdate','companyname','accountcode','accountname')
));