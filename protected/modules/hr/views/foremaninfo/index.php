<input type="hidden" name="companyid" id="companyid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'foremaninfoid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/foremaninfo/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/foremaninfo/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/foremaninfo/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/foremaninfo/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/foremaninfo/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/foremaninfo/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/foremaninfo/downxls'),
	'columns'=>"
		{
			field:'foremaninfoid',
			title:'". GetCatalog('id') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
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
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'". GetCatalog('companyid')."',width:'50px'},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
					]],
                    onBeforeLoad: function(param) {
                        var row = $('#dg-foremaninfo').edatagrid('getSelected');
                        if(row==null){
                            $(\"input[name='companyid']\").val('0');
                        }
                    },
                    onSelect: function(index,row){
                        var company = row.companyid;
                        $(\"input[name='companyid']\").val(row.companyid);
                    }
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
        {
            field:'employeeid',
            title:'".getCatalog('foreman')."',
            editor:{
                type:'combogrid',
                options:{
                    panelWidth:550,
                    mode : 'remote',
                    method:'get',
                    idField:'employeeid',
                    textField:'fullname',
                    url:'".Yii::app()->createUrl('hr/employee/indexcompany')."',
                    fitColumns:true,
                    pagination:true,
                    onBeforeLoad: function(param) {
                        var company = $(\"input[name='companyid']\").val();
                        if(company==''){
                            var row = $('#dg-foremaninfo').datagrid('getSelected');
                            param.companyid = row.companyid;
                        }else{
                        param.companyid = $(\"input[name='companyid']\").val(); }
                    },
                    loadMsg: '".GetCatalog('pleasewait')."',
                    columns:[[
                        {field:'employeeid',title:'".GetCatalog('employeeid')."'},
                        {field:'fullname',title:'".GetCatalog('fullname')."'},
                        {field:'structurename',title:'".getCatalog('structure')."',width:120},
                    ]]
                }	
            },
            width:'250px',
            sortable: true,
            formatter: function(value,row,index){
                return row.fullname;
            }
        },
        {
            field:'perioddate',
            title:'". GetCatalog('perioddate') ."',
            width:'100px',
            editor:{
                type:'datebox',
                options:{parser: dateparser,
                formatter: dateformatter,}
            },
            sortable: true,
            formatter: function(value,row,index){
                if (value == '01-01-1970'){
                    return '';
                } else {
                    return value;
                }
            }
        },
		{
			field:'jumlah',
			title:'". GetCatalog('jumlah') ."',
			editor:{
				type:'numberbox',
				options:{
					required:true,
					precision: 4,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('id','company','employee')
));