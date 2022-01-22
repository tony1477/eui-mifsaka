<table id="dg-cb" style="width:100%;height:97%">
</table>
<div id="tb-cb">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addcb()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editcb()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvecb()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelcb()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfcb()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('cbid')?></td>
<td><input class="easyui-textbox" id="cb_search_cbid" style="width:150px"></td>
<td><?php echo GetCatalog('company')?></td>
<td><input class="easyui-textbox" id="cb_search_company" style="width:150px"></td>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="cb_search_docdate" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('cashbankno')?></td>
<td><input class="easyui-textbox" id="cb_search_cashbankno" style="width:150px"></td>
<td><?php echo GetCatalog('receiptno')?></td>
<td><input class="easyui-textbox" id="cb_search_receiptno" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="cb_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchcb()"></a></td>
</tr>
</table>
</div>

<div id="dlg-cb" class="easyui-dialog" title="<?php echo GetCatalog('cashbank') ?>" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormcb();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-cb').dialog('close');
			}
		},
	]	
	">
	<form id="ff-cb-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="cbid" id="cbid"></input>
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
								mode:'remote',
                				columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>',width:80},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>		
			<tr>
				<td><?php echo GetCatalog('receiptno')?></td>
				<td><input class="easyui-textbox" name="receiptno"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('isin')?></td>
				<td><input id="isin" type="checkbox" name="isin" style="width:250px"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="DETAIL" style="padding:5px">
				<table id="dg-cbacc"  style="width:100%">
				</table>
				<div id="tb-cbacc">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-cbacc').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-cbacc').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-cbacc').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-cbacc').edatagrid('destroyRow')"></a>
				</div>
			</div>
			<div title="CHEQUE / GIRO" style="padding:5px">
				<table id="dg-cheque"  style="width:100%">
				</table>
				<div id="tb-cheque">
					<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchcheque" style="width:150px">
				</div>
			</div>
			<div title="DAFTAR BANK" style="padding:5px">
				<table id="dg-bank"  style="width:100%">
				</table>
				<div id="tb-bank">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-bank').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-bank').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-bank').edatagrid('cancelRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$('#cb_search_cbid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcb();
			}
		}
	})
});
$('#cb_search_company').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcb();
			}
		}
	})
});
$('#cb_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcb();
			}
		}
	})
});
$('#cb_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcb();
			}
		}
	})
});
$('#cb_search_cashbankno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcb();
			}
		}
	})
});
$('#cb_search_receiptno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcb();
			}
		}
	})
});
$('#dg-cb').edatagrid({
		singleSelect: false,
		toolbar:'#tb-cb',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editcb(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-cbacc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvcbacc = $(this).datagrid('getRowDetail',index).find('table.ddv-cbacc');
			ddvcbacc.datagrid({
				url:'<?php echo $this->createUrl('cb/indexacc',array('grid'=>true)) ?>?id='+row.cbid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				pagination:true,
				loadMsg:'',
				height:'auto',
				width:'100%',
				showFooter:true,
				columns:[[
					{field:'debplantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
					{field:'accdebitname',title:'<?php echo GetCatalog('accdebitname') ?>'},
					{field:'description',title:'<?php echo GetCatalog('description') ?>'},
					{field:'fullname',title:'<?php echo GetCatalog('employee') ?>'},
					{field:'customername',title:'<?php echo GetCatalog('customer') ?>'},
					{field:'suppliername',title:'<?php echo GetCatalog('supplier') ?>'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>',align:'right'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'fullname',title:'<?php echo GetCatalog('employee') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'chequeno',title:'<?php echo GetCatalog('chequeno') ?>'},
					{field:'tglcair',title:'<?php echo GetCatalog('tglcair') ?>',formatter: function(value,row,index){if (value == '01-01-1970'){return '';} else {return value;}}},
					{field:'tgltolak',title:'<?php echo GetCatalog('tgltolak') ?>',formatter: function(value,row,index){if (value == '01-01-1970'){return '';} else {return value;}}},
                    {field:'credplantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
					{field:'acccreditname',title:'<?php echo GetCatalog('acccreditname') ?>'},
				]],
				onResize:function(){
						$('#dg-cb').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-cb').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-cb').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('cb/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-cb').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cbid',
		editing: false,
		columns:[[
		{
field:'cbid',
title:'<?php echo GetCatalog('cbid') ?>',
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
field:'cashbankno',
title:'<?php echo GetCatalog('cashbankno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'companyid',
title:'<?php echo GetCatalog('companyname') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return row.companyname;
					}}, 
{
	field:'isin',
	title:'<?php echo GetCatalog('isin') ?>',
	align:'center',
	width:'100px',
	sortable: true,
	formatter: function(value,row,index){
						if (value == 1){
										return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
										return '';
						}
}},
{
field:'receiptno',
title:'<?php echo GetCatalog('receiptno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'headernote',
title:'<?php echo GetCatalog('headernote') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return row.recordstatusname;
					}},
		]]
});
function searchcb(value){
	$('#dg-cb').edatagrid('load',{
	cbid:$('#cb_search_cbid').val(),
	docdate:$('#cb_search_docdate').val(),
	cashbankno:$('#cb_search_cashbankno').val(),
	companyid:$('#cb_search_company').val(),
	headernote:$('#cb_search_headernote').val(),
	receiptno:$('#cb_search_receiptno').val()
	});
}
function searchcheque(value){
	$('#dg-cheque').edatagrid('load',{
	chequeid:value,
	companyid:value,
	tglbayar:value,
	chequeno:value,
	bankid:value,
	tglcheque:value,
	tgltempo:value,
	tglcair:value,
	tgltolak:value,
	addressbookid:value,
	iscustomer:value,
	});
}
function approvecb() {
	var ss = [];
	var rows = $('#dg-cb').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cb/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cb').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelcb() {
	var ss = [];
	var rows = $('#dg-cb').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cb/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cb').edatagrid('reload');				
		} ,
		'cache':false});
};
function addcb() {
		$('#dlg-cb').dialog('open');
		$('#ff-cb-modif').form('clear');
		$('#ff-cb-modif').form('load','<?php echo $this->createUrl('cb/getdata') ?>');
};

function editcb($i) {
	var row = $('#dg-cb').datagrid('getSelected');
	if(row) {
		$('#dlg-cb').dialog('open');
		$('#ff-cb-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormcb(){
	$('#ff-cb-modif').form('submit',{
		url:'<?php echo $this->createUrl('cb/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-cb').datagrid('reload');
        $('#dlg-cb').dialog('close');
			}
    }
	});	
};

function clearFormcb(){
		$('#ff-cb-modif').form('clear');
};

function cancelFormcb(){
		$('#dlg-cb').dialog('close');
};
$('#ff-cb-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-cb').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-cbacc').datagrid({
			queryParams: {
				id: $('#cbid').val()
			}
		});
		$('#dg-cheque').datagrid({
			queryParams: {
				id: $('#chequeid').val()
			}
		});
		$('#dg-bank').datagrid({
			queryParams: {
				id: $('#bankid').val()
			}
		});
		if (data.isin == 1)
				{
						$('#isin').prop('checked', true);
				} else
				{
						$('#isin').prop('checked', false);
				}
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
function downpdfcb() {
	var ss = [];
	var rows = $('#dg-cb').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbid);
	}
	window.open('<?php echo $this->createUrl('cb/downpdf') ?>?id='+ss);
}
function downxlscb() {
	var ss = [];
	var rows = $('#dg-cb').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbid);
	}
	window.open('<?php echo $this->createUrl('cb/downxls') ?>?id='+ss);
}

$('#dg-cbacc').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'cbaccid',
	editing: true,
	toolbar:'#tb-cbacc',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cb/searchacc',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cb/saveacc',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('cb/saveacc',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('cb/purgeacc',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-cbacc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-cbacc').edatagrid('getSelected');
		if (row)
		{
			row.cbid = $('#cbid').val();
		}
	},
	onBeforeEdit: function(index,row){
			if (row.amount != undefined) {
				var value = row.amount;
				row.amount = value.replace(".", "");
				var value = row.currencyrate;
				row.currencyrate = value.replace(".", "");
			}
  	},
	columns:[[
	{
		field:'cbaccid',
		title:'<?php echo GetCatalog('cbaccid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'cbid',
		title:'<?php echo GetCatalog('cbid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
    {
		field:'debitaccid',
		title:'<?php echo GetCatalog('accdebitname') ?>',
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
						pagination:true,
						required:true,
						queryParams:{
							trxcom:true
						},
						onBeforeLoad: function(param) {
							 param.companyid = $('#companyid').combogrid('getValue');
						},
                        onHidePanel: function() {
                            var tr = $(this).closest('tr.datagrid-row');
                            var index = parseInt(tr.attr('datagrid-row-index'));
                            var debplantid = $('#dg-cbacc').datagrid("getEditor", {index: index, field:"debplantid"});
                            
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
                                    $(debplantid.target).combogrid('setValue',data.rows[0]['plantid']);
                                },
                                'cache':false
                            });
                        },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('accdebitname')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('company')?>'},
						]]
				}	
			},
    width:'200px',
		sortable: true,
		formatter: function(value,row,index){
							return row.accdebitname;
		}
	},
    {
		field:'debplantid',
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
                return row.debplantcode;
		}
	},
	{
		field:'description',
		title:'<?php echo GetCatalog('description') ?>',
    editor:{
			type:'textbox',
			options: {
				required:true
			}
		},
		width:'150px',		
		sortable: true,		
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'amount',
		title:'<?php echo GetCatalog('amount') ?>',
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
                        var amount = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"amount"});
                        var amountold = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"amountold"});
                        var chequeid = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"chequeid"});
                        var currencyrate = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"currencyrate"});
                        var tglcair = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"tglcair"});
                        
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
						required:true,
						pagination:true,
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
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'1'
			}
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
    {
		field:'employeeid',
		title:'<?php echo GetCatalog('employee') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'employeeid',
						textField:'fullname',
						url:'<?php echo Yii::app()->createUrl('hr/employee/indexcompany',array('grid'=>true,'combo'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
                        onBeforeLoad: function(param){
							 param.companyid = $('#companyid').combogrid('getValue');
				        },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'employeeid',title:'<?php echo GetCatalog('employeeid')?>'},
							{field:'fullname',title:'<?php echo GetCatalog('fullname')?>'},
							{field:'structurename',title:'<?php echo GetCatalog('structurename')?>'},
						]]
				}	
			},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.fullname;
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
						idField:'customerid',
						textField:'customername',
						url:'<?php echo Yii::app()->createUrl('common/customer/indexcustcb',array('grid'=>true,'combo'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'customerid',title:'<?php echo GetCatalog('customerid')?>'},
							{field:'customername',title:'<?php echo GetCatalog('customername')?>'},
						]]
				}	
			},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.customername;
		}
	},
    {
		field:'supplierid',
		title:'<?php echo GetCatalog('supplier') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'supplierid',
						textField:'suppliername',
						url:'<?php echo Yii::app()->createUrl('common/supplier/indexsuppcb',array('grid'=>true,'combo'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'supplierid',title:'<?php echo GetCatalog('supplierid')?>'},
							{field:'suppliername',title:'<?php echo GetCatalog('suppliername')?>'},
						]]
				}	
			},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.suppliername;
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
							param.iscustomer = '0,1';
						},
						queryParams:{
							trx:true
						},
                        onChange:function(newValue,oldValue) {
                            if((newValue !== oldValue) && (newValue !== ''))
                            {
                                var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var amount = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"amount"});
								var amountold = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"amountold"});
								var chequeid = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"chequeid"});
								var currencyrate = $("#dg-cbacc").datagrid("getEditor", {index: index, field:"currencyrate"});
                                
                                jQuery.ajax({
                                    'url':'<?php echo Yii::app()->createUrl('accounting/cb/indexcheque',array('grid'=>true,'trxcom'=>true))?>',
                                    'data':{
                                        'chequeid':$(chequeid.target).combogrid('getValue'),
                                        'iscustomer':'0,1'
                                    },
                                    'type':'post',
                                    'dataType':'json',
                                    success:function(data)
                                    {
                                        $(amountold.target).numberbox('setValue',data.rows[0].amountold);
                                        $(amount.target).numberbox('setValue',data.rows[0].amount);
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
		field:'tgltolak',
		title:'<?php echo GetCatalog('tgltolak') ?>',
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
		field:'creditaccid',
		title:'<?php echo GetCatalog('acccreditname') ?>',
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
						pagination:true,
						required:true,
						queryParams:{
							trxcom:true
						},
                        onBeforeLoad: function(param) {
                           param.companyid = $('#companyid').combogrid('getValue');
                        },
                        onHidePanel: function() {
                            var tr = $(this).closest('tr.datagrid-row');
                            var index = parseInt(tr.attr('datagrid-row-index'));
                            var credplantid = $('#dg-cbacc').datagrid("getEditor", {index: index, field:"credplantid"});
                            
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
                                    $(credplantid.target).combogrid('setValue',data.rows[0]['plantid']);
                                },
                                'cache':false
                            });
                        },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('acccreditname')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('company')?>'},
						]]
				}	
			},
    width:'200px',
		sortable: true,
		formatter: function(value,row,index){
							return row.acccreditname;
		}
	},
    {
		field:'credplantid',
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
							return row.credplantcode;
		}
	},
	]]
});


$('#dg-cheque').edatagrid({
	iconCls: 'icon-edit',
	singleSelect: true,
	idField:'chequeid',
	editing: false,
	toolbar:'#tb-cheque',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cb/searchcheque',array('grid'=>true)) ?>',
	destroyUrl: '<?php echo $this->createUrl('cb/purgecheque',array('grid'=>true))?>',
	columns:[[
	{
		field:'chequeid',
		title:'<?php echo GetCatalog('chequeid') ?>',
		//hidden:true,
		readonly:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'companyid',
		title:'<?php echo GetCatalog('companyname') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'companyid',
						textField:'companyname',
						url:'<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'companyid',title:'<?php echo GetCatalog('companyid')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('companyname')?>'},
						]]
				}	
			},
		width:'250px',
		sortable: true,
		formatter: function(value,row,index){
							return row.companyname;
		}
	},
	{
		field:'tglbayar',
		title:'<?php echo GetCatalog('tglbayar') ?>',
		width:'150px',
		editor:{
			type:'datebox',
			options: {
				parser: dateparser,
				formatter: dateformatter,
				required:true
			}
		},
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'chequeno',
		title:'<?php echo GetCatalog('chequeno') ?>',
		width:'150px',
		editor:{
			type:'textbox',
			options: {
				required:true
			}
		},
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'bankid',
		title:'<?php echo GetCatalog('bankname') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'bankid',
						textField:'bankname',
						url:'<?php echo Yii::app()->createUrl('accounting/cb/indexbank',array('grid'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'bankid',title:'<?php echo GetCatalog('bankid')?>'},
							{field:'bankname',title:'<?php echo GetCatalog('bankname')?>'},
						]]
				}	
			},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.bankname;
		}
	},
	{
		field:'amount',
		title:'<?php echo GetCatalog('chequeamount') ?>',
		sortable: true,
		editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'0',
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
						required:true,
						pagination:true,
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
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'1'
			}
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'tglcheque',
		title:'<?php echo GetCatalog('tglcheque') ?>',
		width:'150px',
		editor:{
			type:'datebox',
			options: {
				parser: dateparser,
				formatter: dateformatter,
				required:true
			}
		},
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'tgltempo',
		title:'<?php echo GetCatalog('tgltempo') ?>',
		width:'150px',
		editor:{
			type:'datebox',
			options: {
				parser: dateparser,
				formatter: dateformatter,
				required:true
			}
		},
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'addressbookid',
		title:'<?php echo GetCatalog('customervendor') ?>',
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
						required:true,
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
field:'iscustomer',
title:'Is Customer?',
width:'80px',
editor:{type:'checkbox',options:{on:'1',off:'0'}},
sortable: true,
formatter: function(value,row,index){
						if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
							return '';
						}
		}
	},	
	{
		field:'tglcair',
		title:'<?php echo GetCatalog('tglcair') ?>',
		width:'150px',
		editor:{
			type:'datebox',
			options: {
				parser: dateparser,
				formatter: dateformatter,
				readonly:true,
				//required:true
			}
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
		field:'tgltolak',
		title:'<?php echo GetCatalog('tgltolak') ?>',
		width:'150px',
		editor:{
			type:'datebox',
			options: {
				parser: dateparser,
				formatter: dateformatter,
				readonly:true,
				//required:true
			}
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
		field:'recordstatus',
		title:'<?php echo GetCatalog('recordstatus') ?>',
		align:'center',
		sortable: true,
		formatter: function(value,row,index){
						return value;
		}
	},
	]]
});
    
$('#dg-bank').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'bankid',
	editing: true,
	toolbar:'#tb-bank',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cb/searchbank',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cb/savebank',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('cb/savebank',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('cb/purgebank',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-bank').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
		$('#dg-bank').edatagrid('reload');
	},
	onBeforeSave: function(index){
		var row = $('#dg-bank').edatagrid('getSelected');
		if (row)
		{
			row.cbid = $('#cbid').val();
		}
	},
	columns:[[
	{
		field:'bankid',
		title:'<?php echo GetCatalog('bankid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'bankname',
		title:'<?php echo GetCatalog('bankname') ?>',
    editor:{
			type:'text',
			options: {
				required:true
			}
		},
		width:'150px',		
		sortable: true,		
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'recordstatus',
		title:'<?php echo GetCatalog('recordstatus') ?>',
		align:'center',
		sortable: true,
		formatter: function(value,row,index){
						return value;
		}
	},
	]]
});

</script>
