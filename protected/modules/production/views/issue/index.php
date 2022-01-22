<input type="hidden" name="companyid" id="companyid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'issueid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('production/issue/index',array('grid'=>true)),
	'ispost'=>1,
	'isreject' => 1,
	'saveurl'=>Yii::app()->createUrl('production/issue/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/issue/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/issue/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/issue/upload'),
	'approveurl'=>Yii::app()->createUrl('production/issue/approve'),
	'rejecturl'=>Yii::app()->createUrl('production/issue/delete'),
	'downpdf'=>Yii::app()->createUrl('production/issue/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/issue/downxls'),
    'addonscripts'=>"",
	'columns'=>"
		{
			field:'issueid',
			title:'". GetCatalog('issueid') ."',
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
                onBeforeLoad: function(param) {
					var row = $('#dg-issue').edatagrid('getSelected');
					if(row==null){
						$(\"input[name='companyid']\").val('0');
					}
				},
				loadMsg: '".GetCatalog('pleasewait')."',
				columns:[[
					{field:'companyid',title:'".GetCatalog('companyid')."',width:'80px'},
					{field:'companyname',title:'".GetCatalog('companyname')."',width:'400px'},
				]],
                onSelect: function(index,row){
					var company = row.companyid;
					$(\"input[name='companyid']\").val(row.companyid);
				},
			}	
		},
		width:'250px',		
		sortable: true,
		formatter: function(value,row,index){
            return row.companyname;
		}},
        {
            field:'docdate',
            title:'".GetCatalog('docdate')."',
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
				            var row = $('#dg-issue').datagrid('getSelected');
                            param.companyid = row.companyid;
				        }else{
					       param.companyid = $(\"input[name='companyid']\").val();
				        }
                    },
                    loadMsg: '".GetCatalog('pleasewait')."',
                    columns:[[
                        {field:'employeeid',title:'".GetCatalog('employeeid')."'},
                        {field:'fullname',title:'".GetCatalog('fullname')."'},
                        {field:'structurename',title:'".getCatalog('structure')."',width:120},
                    ]]
                }	
            },
            width:'200px',
            sortable: true,
            formatter: function(value,row,index){
                return row.fullname;
            
        }},
        {
            field:'jumlah',
            title:'".GetCatalog('jumlah')."',
            editor:{
                type:'numberbox',
                options:{
                    required:'true',
                },
            },
            width:'100px',
            sortable: true,
            formatter: function(value,row,index){
                return value;
        }},
        {
			field:'description',
			title:'". GetCatalog('description') ."',
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
            field:'cycletime',
            title:'".GetCatalog('cycletimesec')."',
            editor: {
                type:'textbox',
                options:{
                    onChange:function(newValue,oldValue) {
						if ((newValue !== oldValue) && (newValue !== \"\"))
							{
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								
                                var ctmin = $(\"#dg-issue\").datagrid(\"getEditor\", {index: index, field:\"cycletimemin\"});
                                
                                $(ctmin.target).textbox('setValue',newValue/60);
							}
					},
                },
            },
            width:'100px',
            sortable: true,
            formatter: function(value,row,index){
                return value;
        }},		
        {
            field:'cycletimemin',
            title:'".GetCatalog('cycletimemin')."',
            editor:{
                type:'textbox',
                options:{
                    disabled:'true',
                    readonly:'true',
                },
            },
            width:'100px',
            sortable: true,
            formatter: function(value,row,index){
                return value;
        }},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.statusname;
		}}",
	'searchfield'=> array ('issueid','company','fullname','description')
));