<table id="dg-ttf" style="width:1200px;height:97%"></table>
<div id="tb-ttf">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  
    ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addTtf()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editTtf()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvettf()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelttf()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfttf()"></a>
    
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('id')?></td>
<td><input class="easyui-textbox" id="ttf_search_ttfid" style="width:50px">
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="ttf_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('docno')?></td>
<td><input class="easyui-textbox" id="ttf_search_docno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="ttf_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('sales')?></td>
<td><input class="easyui-textbox" id="ttf_search_sales" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="ttf_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchtnt()"></a></td>
</tr>
</table>
</div>

<div id="dlg-ttf" class="easyui-dialog" title="<?php echo GetCatalog('ttf')?>" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormTtf();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-ttf').dialog('close');
			}
		},
	]	
	">
	<form id="ff-ttf-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="ttfid" id="ttfid"></input>
		<table cellpadding="5">
                        <tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,readonly:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'companyid',
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
				<td><?php echo GetCatalog('sales')?></td>
				<td><select class="easyui-combogrid" id="employeeid" name="employeeid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'employeeid',
								textField: 'fullname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								queryParams: {
									combo:true
								},
								columns: [[
										{field:'employeeid',title:'<?php echo GetCatalog('employeeid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
	<tr>
				<td><?php echo GetCatalog('ttnt')?></td>
				<td><select class="easyui-combogrid" id="ttntid" name="ttntid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'ttntid',
								textField: 'docno',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('order/ttnt/indexttf',array('grid'=>true)) ?>',
								method: 'get',
                                onBeforeLoad: function(param) {
 //var row = $('#dg-ttf').datagrid('getSelected');
    //param.employeeid=row.employeeid;
param.employeeid=$('#employeeid').combogrid('getValue');
param.companyid=$('#companyid').combogrid('getValue');
						        },
								mode: 'remote',
								queryParams: {
									combo:true
								},
								columns: [[
										{field:'ttntid',title:'<?php echo GetCatalog('ttntid') ?>'},
										{field:'docno',title:'<?php echo GetCatalog('docno') ?>'},
										{field:'employeename',title:'<?php echo GetCatalog('fullname') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('company') ?>'},
										
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('description')?></td>
				<td><input class="easyui-textbox" data-options="multiline:true,required:true" style="width:300px;height:100px" name="description"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="Detail" style="padding:5px">
				<table id="dg-ttfdetail"  style="width:100%">
				</table>
				<div id="tb-ttfdetail">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-ttfdetail').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-ttfdetail').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-ttfdetail').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-ttfdetail').edatagrid('destroyRow')"></a>
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
$('#ttf_search_ttfid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttf();
			}
		}
	})
});
$('#ttf_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttf();
			}
		}
	})
});
$('#ttf_search_docno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepttf();
			}
		}
	})
});
$('#ttf_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttf();
			}
		}
	})
});
$('#ttf_search_sales').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttf();
			}
		}
	})
});
$('#ttf_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttf();
			}
		}
	})
});
$('#dg-ttf').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-ttf',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		onDblClickRow: function (index,row) {
			editttf(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-ttfdetail"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvttfdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-ttfdetail');
			ddvttfdetail.datagrid({
				url:'<?php echo $this->createUrl('ttf/indexdetail',array('grid'=>true)) ?>?id='+row.ttfid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'1100px',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'fullname',title:'<?php echo GetCatalog('customer') ?>',width:'200px'},
					{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno') ?>',width:'100px'},
					{field:'invoicedate',title:'<?php echo GetCatalog('invoicedate') ?>',width:'100px'},
					{field:'jatuhtempo',title:'<?php echo GetCatalog('jatuhtempo') ?>',width:'100px'},
					{field:'ttntdetailid',title:'<?php echo GetCatalog('ttntdetail') ?>',width:'180px'},
					{field:'sono',title:'<?php echo GetCatalog('soheader') ?>',width:'120px'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>',width:'130px'},
					{field:'payamount',title:'<?php echo GetCatalog('payamount') ?>',width:'130px'},
				]],
				onResize:function(){
						$('#dg-ttf').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-ttf').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-ttf').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('ttf/index',array('grid'=>true)) ?>',
                onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-ttf').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'ttfid',
		editing: false,
		columns:[[
		{field:'_expander',expander:true,width:24,fixed:true},
		{
field:'ttfid',
title:'<?php echo GetCatalog('ttfid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
					}},
							{
field:'companyname',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'docno',
title:'<?php echo GetCatalog('docno') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'employeeid',
title:'<?php echo GetCatalog('sales') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return row.employeename;
					}},

{
field:'description',
title:'<?php echo GetCatalog('description') ?>',
width:'200px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
					{
field:'recordstatusttf',
title:'<?php echo GetCatalog('recordstatus') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchttf(value){
	$('#dg-ttf').edatagrid('load',{
	ttfid:$('#ttf_search_ttfid').val(),
        docdate:$('#ttf_search_docdate').val(),
        docno:$('#ttf_search_docno').val(),
        employeeid:$('#ttf_search_sales').val(),
        description:$('#ttf_search_description').val(),
        companyid:$('#ttf_search_companyname').val()
	});
}
function approvettf() {
	var ss = [];
	var rows = $('#dg-ttf').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttfid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('ttf/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-ttf').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelttf() {
	var ss = [];
	var rows = $('#dg-ttf').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttfid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('ttf/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-ttf').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfttf() {
	var ss = [];
	var rows = $('#dg-ttf').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttfid);
	}
	window.open('<?php echo $this->createUrl('ttf/downpdf') ?>?id='+ss);
}
function downxlsttf() {
	var ss = [];
	var rows = $('#dg-ttf').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttfid);
	}
	window.open('<?php echo $this->createUrl('ttf/downxls') ?>?id='+ss);
}
function addTtf() {
		$('#dlg-ttf').dialog('open');
		$('#ff-ttf-modif').form('clear');
		$('#ff-ttf-modif').form('load','<?php echo $this->createUrl('ttf/getdata') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editTtf($i) {
	var row = $('#dg-ttf').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appttf') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-ttf').dialog('open');
			$('#ff-ttf-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormTtf(){
	$('#ff-ttf-modif').form('submit',{
		url:'<?php echo $this->createUrl('ttf/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-ttf').datagrid('reload');
        $('#dlg-ttf').dialog('close');
			}
    }
	});	
};

function clearFormttf(){
		$('#ff-ttf-modif').form('clear');
};

function cancelFormttf(){
		$('#dlg-ttf').dialog('close');
};

$('#ff-ttf-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-ttfdetail').datagrid({
				queryParams: {
					id: $('#ttfid').val()
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

$('#dg-ttfdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'ttfdetailid',
	editing: true,
	toolbar:'#tb-ttfdetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('ttf/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('ttf/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('ttf/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('ttf/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-ttfdetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var ixs = $('#dg-ttf').edatagrid('getSelected');
		var row = $('#dg-ttfdetail').edatagrid('getSelected');
		if (row)
		{
			row.ttfid = $('#ttfid').val();
		}
	},
	columns:[[
	{
		field:'ttfid',
		title:'<?php echo GetCatalog('ttfid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'ttfdetailid',
		title:'<?php echo GetCatalog('ttfdetailid') ?>',
		sortable: true,
		hidden:true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'fullname',
		title:'<?php echo GetCatalog('customer') ?>',
		required:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
    {
		field:'invoiceid',
		title:'<?php echo GetCatalog('invoiceid') ?>',
		hidden:true,
        required: true,
        editor:{
			type:'textbox',
        },
		sortable: true,
        width: 250,
		formatter: function(value,row,index){
							return value;
	}
	},
	{
		field:'ttntdetailid',
		title:'<?php echo GetCatalog('invoice') ?>',
		width:'150px',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:700,
						mode : 'remote',
						method:'get',
						idField:'ttntdetailid',
						textField:'invoiceno',
						url:'<?php echo Yii::app()->createUrl('order/ttnt/indexttfdetail',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							ttfinv:true
						},
						onBeforeLoad: function(param) {
							 param.ttntid = $('#ttntid').combogrid('getValue');
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'ttntdetailid',title:'<?php echo GetCatalog('ttntdetailid')?>'},
							{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno')?>'},
                            {field:'customer',title:'<?php echo GetCatalog('customer')?>'},
                            {field:'sono',title:'<?php echo GetCatalog('sono')?>'},
                            {field:'employeename',title:'<?php echo GetCatalog('sales')?>'},
                            {field:'amount',title:'<?php echo GetCatalog('amount')?>'},
                            {field:'payamount',title:'<?php echo GetCatalog('payamount')?>'},
						]],
                         onHidePanel: function(){
                             var tr = $(this).closest('tr.datagrid-row');
							 var index = parseInt(tr.attr('datagrid-row-index'));
                             var invoiceid = $("#dg-ttfdetail").datagrid("getEditor", {index: index, field:"invoiceid"});
							 var ttntdetailid = $("#dg-ttfdetail").datagrid("getEditor", {index: index, field:"ttntdetailid"});
                             
                             jQuery.ajax({
                                'url':'<?php echo Yii::app()->createUrl('order/ttf/getinvoice') ?>',
								'data':{'ttntdetailid':$(ttntdetailid.target).combogrid('getValue')},
								'type':'post','dataType':'json',
								'success':function(data)
								{
									$(invoiceid.target).textbox('setValue',data.invoiceid);
                                    //console.log($(invoiceid.target).textbox('getValue'));
									
								},
								'cache':false});
			             },
                },
        },
		required:true,
		sortable: true,
		formatter: function(value,row,index){
							return row.invoiceno;
		},
	},
	{
		field:'amount',
		title:'<?php echo GetCatalog('amount') ?>',
		type:'numberbox',
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
		{
		field:'payamount',
		title:'<?php echo GetCatalog('payamount') ?>',
	type:'numberbox',
	sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	
	{
		field:'sono',
		title:'<?php echo GetCatalog('soheader') 

?>',
			required:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
