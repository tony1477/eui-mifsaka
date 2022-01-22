<table id="dg-ttnt" style="width:1200px;height:97%"></table>
<div id="tb-ttnt">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addTtnt()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editTtnt()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvettnt()"></a>
	<?php }?>
  <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelttnt()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfttnt()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formTtnt" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileTtnt" id="FileTtnt" style="display:inline">
			<input type="submit" value="Upload" name="submitTtnt" style="display:inline">
		</form>
	<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('id')?></td>
<td><input class="easyui-textbox" id="ttnt_search_ttntid" style="width:50px">
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="ttnt_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('docno')?></td>
<td><input class="easyui-textbox" id="ttnt_search_docno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="ttnt_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('sales')?></td>
<td><input class="easyui-textbox" id="ttnt_search_sales" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="ttnt_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchtnt()"></a></td>
</tr>
</table>
</div>

<div id="dlg-ttnt" class="easyui-dialog" title="<?php echo GetCatalog('ttnt')?>" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormTtnt();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-ttnt').dialog('close');
			}
		},
	]	
	">
	<form id="ff-ttnt-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="ttntid" id="ttntid"></input>
		<table cellpadding="5">
                        <tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: '500px',
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>',width:'80px'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:'250px'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('sales')?></td>
				<td><select class="easyui-combogrid" id="employeeid" name="employeeid" style="width:250px" data-options="
								panelWidth: '500px',
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
										{field:'employeeid',title:'<?php echo GetCatalog('employeeid') ?>',width:'80px'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>',width:'250px'},
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
				<table id="dg-ttntdetail"  style="width:100%">
				</table>
				<div id="tb-ttntdetail">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-ttntdetail').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-ttntdetail').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-ttntdetail').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-ttntdetail').edatagrid('destroyRow')"></a>
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
$("#formTtnt").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('order/ttnt/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data);
			$('#dg-ttnt').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#ttnt_search_ttntid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttnt();
			}
		}
	})
});
$('#ttnt_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttnt();
			}
		}
	})
});
$('#ttnt_search_docno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepttnt();
			}
		}
	})
});
$('#ttnt_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttnt();
			}
		}
	})
});
$('#ttnt_search_sales').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttnt();
			}
		}
	})
});
$('#ttnt_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchttnt();
			}
		}
	})
});
$('#dg-ttnt').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-ttnt',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		onDblClickRow: function (index,row) {
			editTtnt(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-ttntdetail"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvttntdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-ttntdetail');
			ddvttntdetail.datagrid({
				url:'<?php echo $this->createUrl('ttnt/indexdetail',array('grid'=>true)) ?>?id='+row.ttntid,
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
					{field:'gino',title:'<?php echo GetCatalog('giheader') ?>',width:'180px'},
					{field:'sono',title:'<?php echo GetCatalog('soheader') ?>',width:'120px'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>',width:'130px'},
					{field:'payamount',title:'<?php echo GetCatalog('payamount') ?>',width:'130px'},
					{field:'sisa',title:'<?php echo GetCatalog('sisa') ?>',align:'right'},
				]],
				onResize:function(){
						$('#dg-ttnt').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-ttnt').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-ttnt').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('ttnt/index',array('grid'=>true)) ?>',
                onSuccess: function(index,row){
			show('Message',row.msg,'info');
			$('#dg-ttnt').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg,'error');
			$('#dg-ttnt').edatagrid('reload');
		},
		idField:'ttntid',
		editing: false,
		columns:[[
		{field:'_expander',expander:true,width:24,fixed:true},
		{
field:'ttntid',
title:'<?php echo GetCatalog('ttntid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					if (row.recordstatus == 1) {
				return '<div style="background-color:green;color:white">'+value+'</div>';
			} else 
				if (row.recordstatus == 2) {
					return '<div style="background-color:yellow;color:black">'+value+'</div>';
				} else if (row.recordstatus == 3) {
					return '<div style="background-color:red;color:white">'+value+'</div>';
				} else if (row.recordstatus == 0) {
					return '<div style="background-color:black;color:white">'+value+'</div>';
				}
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
field:'iscbin',
title:'<?php echo GetCatalog('iscbin') ?>',
align:'center',
width:'120px',
sortable: true,
formatter: function(value,row,index){
				if (value == 1){
					return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
				} else {
					return '';
				}
			}},
{
field:'iscutar',
title:'<?php echo GetCatalog('iscutar') ?>',
align:'center',
width:'120px',
sortable: true,
formatter: function(value,row,index){
				if (value == 1){
					return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
				} else {
					return '';
				}
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
field:'recordstatusttnt',
title:'<?php echo GetCatalog('recordstatus') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchttnt(value){
	$('#dg-ttnt').edatagrid('load',{
	ttntid:$('#ttnt_search_ttntid').val(),
        docdate:$('#ttnt_search_docdate').val(),
        docno:$('#ttnt_search_docno').val(),
        employeeid:$('#ttnt_search_sales').val(),
        description:$('#ttnt_search_description').val(),
        companyid:$('#ttnt_search_companyname').val()
	});
}
function approvettnt() {
	var ss = [];
	var rows = $('#dg-ttnt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttntid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('ttnt/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-ttnt').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelttnt() {
	var ss = [];
	var rows = $('#dg-ttnt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttntid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('ttnt/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-ttnt').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfttnt() {
	var ss = [];
	var rows = $('#dg-ttnt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttntid);
	}
	window.open('<?php echo $this->createUrl('ttnt/downpdf') ?>?id='+ss);
}
function downxlsttnt() {
	var ss = [];
	var rows = $('#dg-ttnt').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ttntid);
	}
	window.open('<?php echo $this->createUrl('ttnt/downxls') ?>?id='+ss);
}
function addTtnt() {
		$('#dlg-ttnt').dialog('open');
		$('#ff-ttnt-modif').form('clear');
		$('#ff-ttnt-modif').form('load','<?php echo $this->createUrl('ttnt/getdata') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editTtnt($i) {
	var row = $('#dg-ttnt').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appttnt') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo GetCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-ttnt').dialog('open');
			$('#ff-ttnt-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormTtnt(){
	$('#ff-ttnt-modif').form('submit',{
		url:'<?php echo $this->createUrl('ttnt/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-ttnt').datagrid('reload');
        $('#dlg-ttnt').dialog('close');
			}
    }
	});	
};

function clearFormTtnt(){
		$('#ff-ttnt-modif').form('clear');
};

function cancelFormTtnt(){
		$('#dlg-ttnt').dialog('close');
};

$('#ff-ttnt-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-ttntdetail').datagrid({
				queryParams: {
					id: $('#ttntid').val()
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

$('#dg-ttntdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'ttntdetailid',
	editing: true,
	toolbar:'#tb-ttntdetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('ttnt/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('ttnt/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('ttnt/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('ttnt/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-ttntdetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var ixs = $('#dg-ttnt').edatagrid('getSelected');
		var row = $('#dg-ttntdetail').edatagrid('getSelected');
		if (row)
		{
			row.ttntid = $('#ttntid').val();
		}
	},
	columns:[[
	{
		field:'ttntid',
		title:'<?php echo GetCatalog('ttntid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'ttntdetailid',
		title:'<?php echo GetCatalog('ttntdetailid') ?>',
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
		title:'<?php echo GetCatalog('invoice') ?>',
		width:'150px',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'invoiceid',
						textField:'invoiceno',
						url:'<?php echo Yii::app()->createUrl('accounting/invoicear/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							ttntinv:true
						},
						onBeforeLoad: function(param) {
							param.companyid = $('#companyid').combogrid('getValue');
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'invoiceid',title:'<?php echo GetCatalog('invoiceid')?>'},
							{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno')?>'},
							{field:'fullname',title:'<?php echo GetCatalog('customer')?>'},
							{field:'amount',title:'<?php echo GetCatalog('amount')?>'},
							{field:'payamount',title:'<?php echo GetCatalog('payamount')?>'},
						]]
				}	
			},
			required:true,
		sortable: true,
		formatter: function(value,row,index){
							return row.invoiceno;
		}
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
		field:'gino',
		title:'<?php echo GetCatalog('giheader') ?>',
			required:true,
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
