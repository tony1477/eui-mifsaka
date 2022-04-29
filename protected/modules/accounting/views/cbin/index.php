<table id="dg-cbin" style="width:100%;height:97%"></table>
<div id="tb-cbin">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addcbin()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editcbin()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvecbin()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelcbin()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfcbin()"></a>
	<?php }?>
<table>
	<tr>
		<td><?php echo GetCatalog('cbinid')?></td>
		<td><input class="easyui-textbox" id="cbin_search_cbinid" style="width:150px"></td>
		<td><?php echo GetCatalog('companyname')?></td>
		<td><input class="easyui-textbox" id="cbin_search_companyname" style="width:150px"></td>
		<td><?php echo GetCatalog('cbinno')?></td>
		<td><input class="easyui-textbox" id="cbin_search_cbinno" style="width:150px"></td>
	</tr>
	<tr>
		<td><?php echo GetCatalog('ttnt')?></td>
		<td><input class="easyui-textbox" id="cbin_search_ttntid" style="width:150px"></td>
		<td><?php echo GetCatalog('docdate')?></td>
		<td><input class="easyui-textbox" id="cbin_search_docdate" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchcbin()"></a></td>
	</tr>
</table>
</div>
<div id="dlg-cbin" class="easyui-dialog" title="<?php echo GetCatalog('cbin')?>" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormcbin();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-cbin').dialog('close');
			}
		},
	]	
	">
	<form id="ff-cbin-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="cbinid" id="cbinid"></input>
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('companyname')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'companyid',
								required: true,
								textField: 'companyname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,
				required:true,
				parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('ttnt')?></td>
				<td><select class="easyui-combogrid" id="ttntid" name="ttntid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'ttntid',
								required: true,
								textField: 'docno',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('order/ttnt/index',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								queryParams:{
									ttntcbin:true,
								},                                
						onBeforeLoad: function(param) {
							param.companyid = $('#companyid').combogrid('getValue');
						},
								columns: [[
										{field:'ttntid',title:'<?php echo GetCatalog('ttntid') ?>'},
										{field:'docno',title:'<?php echo GetCatalog('docno') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="JOURNAL" style="padding:5px">
				<table id="dg-cbinjournal"  style="width:100%">
				</table>
				<div id="tb-cbinjournal">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-cbinjournal').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-cbinjournal').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-cbinjournal').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-cbinjournal').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$('#cbin_search_cbinid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcbin();
			}
		}
	})
});
$('#cbin_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcbin();
			}
		}
	})
});
$('#cbin_search_cbinno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcbin();
			}
		}
	})
});
$('#cbin_search_ttntid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcbin();
			}
		}
	})
});
$('#cbin_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcbin();
			}
		}
	})
});
$('#dg-cbin').edatagrid({
		singleSelect: false,
		toolbar:'#tb-cbin',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
        autoRowHeight:true,
        onDblClickRow: function (index,row) {
			editcbin(index);
		},
    rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-cbinjournal"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvcbinjournal = $(this).datagrid('getRowDetail',index).find('table.ddv-cbinjournal');
			ddvcbinjournal.datagrid({
				url:'<?php echo $this->createUrl('cbin/indexjournal',array('grid'=>true)) ?>?id='+row.cbinid,
				fitColumns:true,
				singleSelect:true,
				pagination:true,
				rownumbers:true,
				loadMsg:'',
				title:'Journal Detail',
				height:'auto',
				width:'100%',
				showFooter:true,
				columns:[[
				{field:'plantcode',title:'<?php echo GetCatalog('plantcode') ?>',width:'250px'},
				{field:'accountname',title:'<?php echo GetCatalog('account') ?>',width:'250px'},
				{field:'debit',title:'<?php echo GetCatalog('debit') ?>',align:'right',width:'100px'},			
				{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>',width:'100px'},
				{field:'currencyrate',title:'<?php echo GetCatalog('rate') ?>',align:'right',width:'50px'},
				{field:'chequeno',title:'<?php echo GetCatalog('chequeno') ?>',width:'150px'},
				{field:'tglcair',title:'<?php echo GetCatalog('tglcair') ?>',width:'100px'},
				{field:'fullname',title:'<?php echo GetCatalog('customer') ?>',width:'250px'},
				{field:'description',title:'<?php echo GetCatalog('description') ?>',width:'300px'},
				]],
				onResize:function(){
						$('#dg-cbin').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-cbin').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-cbin').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('cbin/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-cbin').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cbinid',
		editing: false,
		columns:[[
		{
			field:'cbinid',
			title:'<?php echo GetCatalog('cbinid') ?>',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
					if (row.recordstatus == 1) {
						return '<div style="background-color:green;color:white">'+value+'</div>';
					} else 
					if (row.recordstatus == 2) {
						return '<div style="background-color:yellow;color:black">'+value+'</div>';
					} else 
					if (row.recordstatus == 3) {
						return '<div style="background-color:red;color:white">'+value+'</div>';
					} else 
					if (row.recordstatus == 0) {
						return '<div style="background-color:black;color:white">'+value+'</div>';
					}
				}
			},
{
field:'companyid',
title:'<?php echo GetCatalog('companyname') ?>',
sortable: true,
width:'400px',
formatter: function(value,row,index){
						return row.companyname;
					}},
{
field:'cbinno',
title:'<?php echo GetCatalog('cbinno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.cbinno;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
editor:'text',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'ttntid',
title:'<?php echo GetCatalog('ttntno') ?>',
editor:'text',
width:'120px',
sortable: true,
formatter: function(value,row,index){
						return row.docno;
					}},
{
field:'recordstatuscbin',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'200px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchcbin(value){
	$('#dg-cbin').edatagrid('load',{
	cbinid:$('#cbin_search_cbinid').val(),
        ttntid:$('#cbin_search_ttntid').val(),
        docdate:$('#cbin_search_docdate').val(),
        cbinno:$('#cbin_search_cbinno').val(),
        companyid:$('#cbin_search_companyname').val(),
	});
}
function approvecbin() {
	var ss = [];
	var rows = $('#dg-cbin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbinid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cbin/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cbin').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelcbin() {
	var ss = [];
	var rows = $('#dg-cbin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbinid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cbin/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cbin').edatagrid('reload');				
		} ,
		'cache':false});
};
function addcbin() {
		$('#dlg-cbin').dialog('open');
		$('#ff-cbin-modif').form('clear');
		$('#ff-cbin-modif').form('load','<?php echo $this->createUrl('cbin/getdata') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editcbin($i) {
	var row = $('#dg-cbin').datagrid('getSelected');
	if(row) {
		$('#dlg-cbin').dialog('open');
		$('#ff-cbin-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormcbin(){
	$('#ff-cbin-modif').form('submit',{
		url:'<?php echo $this->createUrl('cbin/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-cbin').datagrid('reload');
        $('#dlg-cbin').dialog('close');
			}
    }
	});	
};

function clearFormcbin(){
		$('#ff-cbin-modif').form('clear');
};

function cancelFormcbin(){
		$('#dlg-cbin').dialog('close');
};
$('#ff-cbin-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-cbin').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
        $('#dg-cbinjournal').datagrid({
			queryParams: {
				id: $('#cbinid').val()
			}
		});
}});
function dateformatter(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
}

function dateparser(s){
	if (!s) return new Date();
		var ss = (s.split('-'));
		var y = parseInt(ss[2],10);
		var m = parseInt(ss[1],10);
		var d = parseInt(ss[0],10);
		if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
				return new Date(y,m-1,d);
		} else {
				return new Date();
		}
}
function downpdfcbin() {
	var ss = [];
	var rows = $('#dg-cbin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbinid);
	}
	window.open('<?php echo $this->createUrl('cbin/downpdf') ?>?id='+ss);
}
function downxlscbin() {
	var ss = [];
	var rows = $('#dg-cbin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbinid);
	}
	window.open('<?php echo $this->createUrl('cbin/downxls') ?>?id='+ss);
}

$('#dg-cbinjournal').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'cbinjournalid',
	editing: true,
	toolbar:'#tb-cbinjournal',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cbin/searchjournal',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cbin/savejournal',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('cbin/savejournal',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('cbin/purgejournal',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-cbinjournal').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-cbinjournal').edatagrid('getSelected');
		if (row)
		{
			row.cbinid = $('#cbinid').val();
			row.companyid = $('#companyid').combogrid('getValue');
		}
	},
        columns:[[
	{
		field:'cbinid',
		title:'<?php echo GetCatalog('cbinid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'cbinjournalid',
		title:'<?php echo GetCatalog('cbinjournalid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'accountid',
		title:'<?php echo GetCatalog('accountname') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'accountid',
						textField:'accountname',
						url:'<?php echo $this->createUrl('account/index',array('grid'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
						queryParams:{
							trxcom:true
						},
						onBeforeLoad: function(param) {
							 param.companyid = $('#companyid').combogrid('getValue');
						},
                        onHidePanel: function() {
                            var tr = $(this).closest('tr.datagrid-row');
                            var index = parseInt(tr.attr('datagrid-row-index'));
                            var plantid = $('#dg-cbinjournal').datagrid("getEditor", {index: index, field:"plantid"});
                            
                            jQuery.ajax({
                                'url':'<?php echo Yii::app()->createUrl('common/plant/indexcompany',array('grid'=>true))?>',
                                'data' :{
                                    'companyid' : $('#companyid').combogrid('getValue'),
                                    'trxcom' : true,
                                },
                                'type':'post',
                                'dataType':'json',
                                'success':function(data)
                                {
                                    console.log('success');
                                    $(plantid.target).combogrid('setValue',data.rows[0]['plantid']);
                                },
                                'cache':false
                            });
                        },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('accountname')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('company')?>'},
						]]
				}	
			},
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.accountname;
		}
	},
	{
		field:'plantid',
		title:'<?php echo GetCatalog('plantcode') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'plantid',
						textField:'plantcode',
						url:'<?php echo Yii::app()->createUrl('common/plant/index',array('grid'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
						queryParams:{
							trxcom:true
						},
						onBeforeLoad: function(param) {
							 param.companyid = $('#companyid').combogrid('getValue');
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'plantid',title:'<?php echo GetCatalog('plantid')?>'},
							{field:'plantcode',title:'<?php echo GetCatalog('plantcode')?>'},
							{field:'description',title:'<?php echo GetCatalog('description')?>'},
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
		field:'amountold',
		title:'<?php echo GetCatalog('amount') ?>',
		sortable: true,
        hidden:true,
		editor:{
			type:'numberbox',
            options:{
                precision:4,
                decimalSeparator:',',
                groupSeparator:'.',
            }
		},
		width:'150px',
		formatter: function(value,row,index){
            return value;
		}
	},
	{
		field:'debit',
		title:'<?php echo GetCatalog('debit') ?>',
		sortable: true,
		editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'0',
                onChange:function(newValue,oldValue) {
                    if((newValue !== oldValue) && (newValue !== ''))
                    {
                        var tr = $(this).closest('tr.datagrid-row');
                        var index = parseInt(tr.attr('datagrid-row-index'));
                        var amountold = $("#dg-cbinjournal").datagrid("getEditor", {index: index, field:"amountold"});
                        var chequeid = $("#dg-cbinjournal").datagrid("getEditor", {index: index, field:"chequeid"});
                        var currencyrate = $("#dg-cbinjournal").datagrid("getEditor", {index: index, field:"currencyrate"});
                        var tglcair = $("#dg-cbinjournal").datagrid("getEditor", {index: index, field:"tglcair"});
                        
                        //$(chequeid.target).combogrid('setValue','');
                        var n1 = $(amountold.target).numberbox('getValue');
                        if(newValue != n1) {
                            $(chequeid.target).combogrid('setValue','');
                            $(tglcair.target).datebox('setValue','');
                        }
                    }
                }
			}
		},
		width:'150px',
		formatter: function(value,row,index){
							return value;
		}
	},        
	{
		field:'currencyid',
		title:'<?php echo GetCatalog('currency') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'currencyid',
						textField:'currencyname',
						url:'<?php echo Yii::app()->createUrl('admin/currency/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'currencyid',title:'<?php echo GetCatalog('currencyid')?>'},
							{field:'currencyname',title:'<?php echo GetCatalog('currencyname')?>'},
						]]
				}	
			},
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.currencyname;
		}
	},
	{
		field:'currencyrate',
		title:'<?php echo GetCatalog('ratevalue') ?>',
		editor:{
			type:'numberbox',
			options:{
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'0',
			}
		},
		width:'50px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'chequeid',
		title:'<?php echo GetCatalog('chequeno') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'chequeid',
						textField:'chequeno',
						url:'<?php echo Yii::app()->createUrl('accounting/cb/indexcheque',array('grid'=>true)) ?>',
						fitColumns:true,
						//required:true,
						pagination:true,
						onBeforeLoad: function(param) {
							param.companyid = $('#companyid').combogrid('getValue');
                            param.iscustomer = 1;
						},
						queryParams:{
							trx:true
						},
                        onChange:function(newValue,oldValue) {
                            if((newValue !== oldValue) && (newValue !== ''))
                            {
                                var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var debit = $("#dg-cbinjournal").datagrid("getEditor", {index: index, field:"debit"});
								var amountold = $("#dg-cbinjournal").datagrid("getEditor", {index: index, field:"amountold"});
								var chequeid = $("#dg-cbinjournal").datagrid("getEditor", {index: index, field:"chequeid"});
								var currencyrate = $("#dg-cbinjournal").datagrid("getEditor", {index: index, field:"currencyrate"});
                                
                                jQuery.ajax({
                                    'url':'<?php echo Yii::app()->createUrl('accounting/cb/indexcheque',array('grid'=>true,'trxcom'=>true))?>',
                                    'data':{
                                        'chequeid':$(chequeid.target).combogrid('getValue'),
                                        'iscustomer':1
                                    },
                                    'type':'post',
                                    'dataType':'json',
                                    success:function(data)
                                    {
                                        $(amountold.target).numberbox('setValue',data.rows[0].amountold);
                                        $(debit.target).numberbox('setValue',data.rows[0].amount);
                                        $(currencyrate.target).numberbox('setValue',data.rows[0].currencyrate);
                                    },
                                    'cache':false
                                });
                            }
                        },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'chequeid',title:'<?php echo GetCatalog('chequeid')?>'},
							{field:'chequeno',title:'<?php echo GetCatalog('chequeno')?>'},
							{field:'fullname',title:'<?php echo GetCatalog('customer')?>'},
							{field:'amount',title:'<?php echo GetCatalog('chequeamount')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('companyname')?>'},
						]]
				}	
			},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.chequeno;
		}
	},
	{
		field:'tglcair',
		title:'<?php echo GetCatalog('tglcair') ?>',
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
		field:'customerid',
		title:'<?php echo GetCatalog('customer') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'addressbookid',
						textField:'fullname',
						url:'<?php echo Yii::app()->createUrl('common/addressbook/index',array('grid'=>true)) ?>',
						fitColumns:true,
						// required:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid')?>'},
							{field:'fullname',title:'<?php echo GetCatalog('fullname')?>'},
							{field:'iscustomer',title:'<?php echo GetCatalog('iscustomer')?>'},
							{field:'isvendor',title:'<?php echo GetCatalog('isvendor')?>'},
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
		field:'description',
		title:'<?php echo GetCatalog('description') ?>',
		width:'250px',		
                editor:'text',
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
