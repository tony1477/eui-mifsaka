<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'operatoroutputid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('production/repoperatoroutput/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/operatoroutput/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/operatoroutput/downxls'),
	'columns'=>"
		{
			field:'operatoroutputid',
			title:'".getCatalog('operatoroutputid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
                if (row.recordstatus == 1) {
                    return '<div style=\"background-color:green;color:white\">'+value+'</div>';
                } else  
                if (row.recordstatus == 2) {
                        return '<div style=\"background-color:yellow;color:black\">'+value+'</div>';
                } else  
                if (row.recordstatus == 3) {
                        return '<div style=\"background-color:red;color:white\">'+value+'</div>';
                }
                else 
                    if (row.recordstatus == 0) {
						return '<div style=\"background-color:black;color:white\">'+value+'</div>';
                }}
        },
		{
			field:'opoutputdate',
			title:'".getCatalog('opoutputdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'".getCatalog('companyname') ."',
			sortable: true,
			width:'450px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'slocid',
			title:'".getCatalog('sloccode') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return row.slocdesc;
		}},
        {
			field:'shiftschedid',
			title:'".getCatalog('shiftsched') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.shiftname;
		}},
        {
			field:'headernote',
			title:'".getCatalog('headernote') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'isover',
			title:'".getCatalog('isover') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
            if (value == 1){
                return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png\"></img>';
            } else {
                return '';
            }
        }},
        {
			field:'ctover',
			title:'".getCatalog('ctover') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			align:'center',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
                return row.recordstatusname;
        }},",
	'searchfield'=> array ('operatoroutputid','company','sloc','headernote','fullname'),
	'headerform'=> "",
	'loadsuccess' => "",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'operatoroutputdetid',
			'urlsub'=>Yii::app()->createUrl('production/repoperatoroutput/indexdetail',array('grid'=>true)),
            'url'=>Yii::app()->createUrl('production/repoperatoroutput/indexdetail',array('grid'=>true)),
            'saveurl'=>'',
            'updateurl'=>'',
            'destroyurl'=>'',
            'onselectsub'=>"
				ddvissue.edatagrid('load',{
					operatoroutputdetid: row.operatoroutputdetid
				})
			",
			'onselect'=>"
				$('#dg-operatoroutput-issue').edatagrid('load',{
					id: row.operatoroutputid,
					operatoroutputdetid: row.operatoroutputdetid
				})
			",
			'subs'=>"
				{field:'fullname',title:'".getCatalog('operator') ."'},
				{field:'groupname',title:'".getCatalog('groupname') ."'},
				{field:'qty',title:'".getCatalog('qty') ."'},
				{field:'headernote',title:'".getCatalog('headernote') ."'},
			",
			'columns'=>"
				{
					field:'operatoroutputissueid',
					title:'".getCatalog('operatoroutputissueid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'operatoroutputdetid',
					title:'".getCatalog('operatoroutputdetid') ."',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
                {
                    field:'employeeid',
                    title:'".getCatalog('employee')."',
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
                                    onBeforeLoad: function(param){
                                         param.companyid = $('#companyid').combogrid('getValue');
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
                    }
                },
				{
                    field:'standardopoutputid',
                    title:'".GetCatalog('standardopoutput')."',
                    editor:{
                            type:'combogrid',
                            options:{
                                panelWidth:450,
                                mode : 'remote',
                                method:'get',
                                idField:'standardopoutputid',
                                textField:'groupname',
                                url:'".Yii::app()->createUrl('production/standardopoutput/index',array('grid'=>true,'opoutput'=>true))."',
                                fitColumns:true,
                                pagination:true,
                                onBeforeLoad: function(param){
                                     param.companyid = $('#companyid').combogrid('getValue');
                                     param.slocid = $('#slocid').combogrid('getValue');
                                },
                                loadMsg: '".GetCatalog('pleasewait')."',
                                columns:[[
                                    {field:'standardopoutputid',title:'". GetCatalog('standardopoutputid')."'},
                                    {field:'sloccode',title:'".GetCatalog('sloc')."'},
                                    {field:'groupname',title:'".GetCatalog('groupname')."'},
                                ]]
                            }	
                        },
                    width:'200px',
                    sortable: true,
                    formatter: function(value,row,index){
                                        return row.groupname;
                    }
                },
				{
					field:'qty',
					title:'".getCatalog('qty')."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.'
						}
					},
					sortable: true,
					required:true,
					formatter: function(value,row,index){
						return value;
					}
				},
                {
                    field:'description',
                    title:'".getCatalog('itemnote')."',
                    editor:'text',
                    sortable: true,
                    width:'200px',
                    formatter: function(value,row,index){
                        return value;
                    }
                },
			"
		),
        array(
			'id'=>'issue',
			'idfield'=>'operatoroutputissueid',
			'urlsub'=>Yii::app()->createUrl('production/operatoroutput/indexissue',array('grid'=>true)),
            'url'=>Yii::app()->createUrl('production/repoperatoroutput/indexissue',array('grid'=>true)),
            'saveurl'=>'',
            'updateurl'=>'',
            'destroyurl'=>'',
			'subs'=>"
				{field:'description',title:'".GetCatalog('issuename') ."',width:'250px'},
				{field:'cycletime',title:'".GetCatalog('cycletime') ."',align:'right',width:'100px'},
				{field:'docupload',title:'".GetCatalog('docupload') ."',width:'100px'},
			",
			'columns'=>"
			{
				field:'operatoroutputissueid',
				title:'".GetCatalog('operatoroutputissueid') ."',
				sortable: true,
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'operatoroutputid',
				title:'".GetCatalog('operatoroutputid') ."',
				hidden:true,
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'operatoroutputdetid',
				title:'".GetCatalog('operatoroutputdetid') ."',
				sortable: true,
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
            {
                field:'standardissueid',
                title:'".GetCatalog('standardissue')."',
                editor:{
                        type:'combogrid',
                        options:{
                            panelWidth:450,
                            mode : 'remote',
                            method:'get',
                            idField:'standardissueid',
                            textField:'issuename',
                            url:'".Yii::app()->createUrl('production/standardissue/index',array('grid'=>true,'combo'=>true))."',
                            fitColumns:true,
                            pagination:true,
                            loadMsg: '".GetCatalog('pleasewait')."',
                            columns:[[
                                {field:'standardissueid',title:'". GetCatalog('standardissueid')."'},
                                {field:'issuename',title:'".GetCatalog('issuename')."'},
                                {field:'description',title:'".GetCatalog('description')."'},
                            ]]
                        }	
                    },
                width:'200px',
                sortable: true,
                formatter: function(value,row,index){
                        return row.issuename;
                }
            },
            {
                field:'description',
                title:'".GetCatalog('description')."',
                editor:'text',
                sortable: true,
                required:true,
                width:'200px',
                formatter: function(value,row,index){
                    return value;
                }
            },
		    {
                field:'cycletime',
                title:'".GetCatalog('cycletime') ."',
                editor:{
                    type:'numberbox',
                    options:{
                        precision:4,
                        required:true,
                        decimalSeparator:',',
                        groupSeparator:'.'
                    }
                },
                width:'100px',
                sortable: true,
                formatter: function(value,row,index){
                    return value;
                }
            },
			"
		)
	),	
));