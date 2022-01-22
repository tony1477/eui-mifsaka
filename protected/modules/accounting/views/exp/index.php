<input type="hidden" name="companyid" id="companyid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'expid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/exp/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/exp/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/exp/save',array('grid'=>true)),
    'ispost' => 1,
    'isreject' => 1,
	'approveurl'=>Yii::app()->createUrl('accounting/exp/approve'),
	'rejecturl'=>Yii::app()->createUrl('accounting/exp/delete'),
	'uploadurl'=>Yii::app()->createUrl('accounting/exp/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/exp/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/exp/downxls'),
    'addonscripts'=>"
    
        $('#dg-".$this->menuname."').edatagrid({
	    onEdit: function(index,row)
        {
            var dg = $(this);
            var ed1 = dg.datagrid('getEditor',{index:index,field:'issupplier'}); // the 'view' editor					
            var addressbook = dg.datagrid('getEditor',{index:index,field:'addressbookid'}); 
            var plantfrom = dg.datagrid('getEditor',{index:index,field:'plantfromid'}); 
            if($(ed1.target).is(':checked'))
            {
				$(addressbook.target).combogrid({required:true,disabled:false});
				$(addressbook.target).combogrid('setValue',row.addressbookid);
            }
            else
            {
                $(plantfrom.target).combogrid({required:true,disabled:false});
				$(plantfrom.target).combogrid('setValue',row.plantfromid);
            }
	    }
    });
		",
	'columns'=>"
		{
			field:'expid',
            title:'". GetCatalog('expid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'docdate',
			title:'". GetCatalog('docdate')."', 
			editor:{type:'datebox',	
									options: {
									parser: dateparser,
									formatter: dateformatter,
									required:true
							}},
			width:'80px',
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
                        var row = $('#dg-exp').edatagrid('getSelected');
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
			field:'issupplier',
			title:'". GetCatalog('issupplier?') ."',
			align:'center',
			width:'70px',
			editor:{
                type:'checkbox',
                options:{
                    on:'1',off:'0'
                }
                },
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
                
		}},
        {
			field:'addressbookid',
            title:'". GetCatalog('supplier')."', 
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'addressbookid',
					textField:'fullname',
					required:true,
					pagination: true,
					url:'". Yii::app()->createUrl('common/supplier/indexsuppcb',array('grid'=>true)) ."',
					fitColumns:true,
					columns:[[
						{field:'addressbookid',title:'". GetCatalog('addressbookid')."'},
						{field:'fullname',title:'". GetCatalog('supplier')."'},
					]]
				}	
			},
			width:'350px',
			sortable:'true',
			formatter: function(value,row,index){
				return row.fullname;
			}
		},
        {
		field:'plantfromid',
		title:'".GetCatalog('plantfromcode')."',
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
                                var row = $('#dg-exp').datagrid('getSelected');
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
        width:'120px',
		sortable: true,
		formatter: function(value,row,index){
                return row.plantfromcode;
            }
        },
        {
		field:'planttoid',
		title:'".GetCatalog('planttocode')."',
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
                                var row = $('#dg-exp').datagrid('getSelected');
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
                width:'120px',
		sortable: true,
		formatter: function(value,row,index){
            return row.planttocode;
            }
        },
		{
			field:'percent',
			title:'". GetCatalog('percent') ."',
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
				title:'".GetCatalog('recordstatus')."',
				align:'center',
				sortable: true,
				formatter: function(value,row,index){
									if (value == 2){
													return '<img src=". Yii::app()->request->baseUrl."/images/ok.png></img>';
									} else if(value == 1){
													return 'New Entry';
									} else {
											return 'Not Active';
									}
				}
		},
        ",
	'beginedit'=>"        
            var addressbookid = $('#dg-".$this->menuname."').edatagrid('getEditor',{index:index, field:'addressbookid'});
            var plantfromid = $('#dg-".$this->menuname."').edatagrid('getEditor',{index:index, field:'plantfromid'});
            var issupplier = $('#dg-".$this->menuname."').edatagrid('getEditor',{index:index, field:'issupplier'});
            
            $(addressbookid.target).combogrid({required:false, disabled:true});
            $(plantfromid.target).combogrid({required:false,disabled:true});
            
            $(issupplier.target ).click( function() {
				if ($(this).is(':checked' ) ) {
					$(addressbookid.target).combogrid({required:true, disabled:false});
                    $(plantfromid.target).combogrid({required:false, disabled:true});
				}
                else{
                    $(addressbookid.target).combogrid({required:false, disabled:true});
                    $(plantfromid.target).combogrid({required:true, disabled:false});
                }
			});
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
	'searchfield'=> array ('expid','docdate','issupplier','companyname','fullname','plantfromcode','planttocode')
));