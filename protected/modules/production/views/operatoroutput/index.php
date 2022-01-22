<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'operatoroutputid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('production/operatoroutput/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/operatoroutput/getData'),
	'saveurl'=>Yii::app()->createUrl('production/operatoroutput/save'),
    'ispost'=>'1',
    'isreject'=>'1',
    'approveurl' => Yii::app()->createUrl('production/operatoroutput/approve'),
    'rejecturl' => Yii::app()->createUrl('production/operatoroutput/delete'),
	//'destroyurl'=>Yii::app()->createUrl('production/operatoroutput/purge'),
	//'uploadurl'=>Yii::app()->createUrl('production/operatoroutput/upload'),
	'downpdf'=>Yii::app()->createUrl('production/operatoroutput/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/operatoroutput/downxls'),
    'addload'=> "$('input[name=\"ctover\"]').val(0);",
    'addonscripts'=>"
       $(document).ready(function () {
                
        var ckbox = $('#isover');
        $('input[name=\"isover\"]').on('click',function () {
            if (ckbox.is(':checked')) {
                $(\"#ctover\").show();
                var oldct = $('#oldctover').val();
                $('input[name=\"ctover\"]').val(oldct);
            } else {
                //$('input[name=\"ctover\"]').attr({value:\"0\"});
                $(\"#ctover\").hide();
                $('input[name=\"ctover\"]').val('0');
            }
        });
        
        $('#ctover').change(function(value,row){
                var sk = $('input[name=\"ctover\"]').val();
                console.log('change to ' + sk );
            });
    });",
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
	'headerform'=> "
		<input type='hidden' id='operatoroutputid' name='operatoroutputid'></input>
        <input type='hidden' name='oldctover' id='oldctover' value='' />
		<table cellpadding='5'>
		<tr>
				<td>".getCatalog('opoutputdate')."</td>
				<td><input class='easyui-datebox' type='text' id='opoutputdate' name='opoutputdate' data-options='formatter:dateformatter,required:true,parser:dateparser' ></input></td>
			</tr>
            <tr>
				<td>".getCatalog('companyname')."</td>
				<td><select class='easyui-combogrid' id='companyid' name='companyid' style='width:250px' data-options=\"
								panelWidth: '500',
								idField: 'companyid',
								required: true,
								textField: 'companyname',
								pagination:true,
								url: '".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ."',
								method: 'get',
								mode:'remote',
                                onHidePanel: function()
                                {
							         $('#slocid').combogrid('setValue','');
					            },
                                columns: [[
										{field:'companyid',title:'".getCatalog('companyid')."',width:80},
										{field:'companyname',title:'".getCatalog('companyname')."',width:120},
								]],
                                fitColumns: true,
						\" onclick=\"click()\">
				</select></td>
			</tr>
            <tr>
				<td>".getCatalog('sloc')."</td>
				<td><select class='easyui-combogrid' name='slocid' id='slocid' style='width:250px' data-options=\"
								panelWidth: '500',
								required: true,
								idField: 'slocid',
								textField: 'sloccode',
								pagination:true,
								url: '".Yii::app()->createUrl('common/sloc/indextrxcom',array('grid'=>true))."',
								method: 'get',
								mode: 'remote',
                                onBeforeLoad: function(param) {
							       param.companyid = $('#companyid').combogrid('getValue');
						        },
								columns: [[
										{field:'slocid',title:'".getCatalog('slocid')."'},
										{field:'sloccode',title:'".getCatalog('sloccode')."'},
										{field:'description',title:'".getCatalog('description') ."'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
            <tr>
				<td>".getCatalog('shiftsched')."</td>
				<td><select class='easyui-combogrid' name='shiftschedid' id='shiftschedid' style='width:250px' data-options=\"
								panelWidth: '500',
								required: true,
								idField: 'shiftschedid',
								textField: 'shiftname',
								pagination:true,
								url: '".Yii::app()->createUrl('production/shiftsched/index',array('grid'=>true))."',
								method: 'get',
								mode: 'remote',
								columns: [[
										{field:'shiftschedid',title:'".getCatalog('shiftschedid')."'},
										{field:'shiftcode',title:'".getCatalog('shiftcode')."'},
										{field:'shiftname',title:'".getCatalog('shiftname') ."'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
            <tr>
                <td>".getCatalog('headernote')."</td>
                <td><input class=\"easyui-textbox\" id=\"headernote\" name=\"headernote\" data-options=\"multiline:true\" style=\"width:300px;height:100px\" /></td>
            </tr>
            <tr>
				<td>".getCatalog('isover')."</td>
				<td><input id=\"isover\" type=\"checkbox\" name=\"isover\" style=\"width:250px\"></input></td>
			</tr>
            <tr id=\"ctover\">
				<td>".getCatalog('ctover')."</td>
				<td><input name=\"ctover\" id=\"ctover\" class=\"easyui-numberbox\" ></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'operatoroutputdetid',
			'urlsub'=>Yii::app()->createUrl('production/operatoroutput/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/operatoroutput/indexdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/operatoroutput/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/operatoroutput/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/operatoroutput/purgedetail',array('grid'=>true)),
            'issingle'=>'true',
            'issuccess'=>'true',
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
			'onsuccess'=>"
				$('#dg-operatoroutput-detail').edatagrid('reload');
				$('#dg-operatoroutput-issue').edatagrid('reload');
			",
			'onerror'=>"
				$('#dg-operatoroutput-detail').edatagrid('reload');
			",
			'onbeginedit'=>"
                row.operatoroutputid = $('#operatoroutputid').val();
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
			'ispurge'=>1,
			'isnew'=>1,
			'urlsub'=>Yii::app()->createUrl('production/operatoroutput/indexissue',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('production/operatoroutput/searchissue',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/operatoroutput/saveissue',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/operatoroutput/saveissue',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/operatoroutput/purgeissue',array('grid'=>true)),
			'issingle'=>'false',
			'subs'=>"
				{field:'description',title:'".GetCatalog('issuename') ."',width:'250px'},
				{field:'cycletime',title:'".GetCatalog('cycletime') ."',align:'right',width:'100px'},
				{field:'docupload',title:'".GetCatalog('docupload') ."',width:'100px'},
			",
            'onbeginedit'=>"
                row.operatoroutputid = $('#operatoroutputid').val();
                var rowx = $('#dg-operatoroutput-detail').edatagrid('getSelected');
                if (rowx)
                {
                  row.operatoroutputdetid = rowx.operatoroutputdetid;
                }                
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