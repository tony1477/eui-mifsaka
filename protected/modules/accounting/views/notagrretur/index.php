<table id="dg-notagrretur" style="width:100%;height:97%"></table>
<div id="tb-notagrretur">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addNotagrretur()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editNotagrretur()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvenotagrretur()"></a>
        <?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelnotagrretur()"></a>
        <?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfnotagrretur()"></a>
        <?php }?>
				<table>
<tr>
<td><?php echo GetCatalog('notagrreturid')?></td>
<td><input class="easyui-textbox" id="notagrretur_search_notagrreturid" style="width:150px"></td>
<td><?php echo GetCatalog('company')?></td>
<td><input class="easyui-textbox" id="notagrretur_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('notagrreturno')?></td>
<td><input class="easyui-textbox" id="notagrretur_search_notagrreturno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="notagrretur_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('grreturno')?></td>
<td><input class="easyui-textbox" id="notagrretur_search_grreturno" style="width:150px"></td>
<td><?php echo GetCatalog('poheader')?></td>
<td><input class="easyui-textbox" id="notagrretur_search_poheader" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('supplier')?></td>
<td><input class="easyui-textbox" id="notagrretur_search_supplier" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="notagrretur_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchnotagrretur()"></a></td>
</tr>
</table>
</div>

<div id="dlg-notagrretur" class="easyui-dialog" title="Nota Retur Pembelian" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormNotagrretur();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-notagrretur').dialog('close');
			}
		},
	]	
	">
	<form id="ff-notagrretur-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="notagrreturid" id="notagrreturid"></input>
		<table cellpadding="5">
                        <tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			 <tr>
				<td><?php echo GetCatalog('grreturno')?></td>
				<td><select class="easyui-combogrid" id="grreturid" name="grreturid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'grreturid',
								textField: 'grreturno',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('inventory/grretur/index',array('grid'=>true)) ?>',
								method: 'get',
								queryParams:{
									notagrretur:true,
								},
								onBeforeLoad: function(param) {
									param.companyid = $('#companyid').combogrid('getValue');
								},
                onHidePanel: function(){
									jQuery.ajax({'url':'<?php echo $this->createUrl('notagrretur/generatedetail') ?>',
									'data':{
                    'id':$('#grreturid').combogrid('getValue'),
                    'hid':$('#notagrreturid').val(),
                   },
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										show('Message',data.message);
										$('#dg-notagrrpro').edatagrid('reload');				
									},
									'cache':false});
								},
								columns: [[
										{field:'grreturid',title:'<?php echo GetCatalog('grreturid') ?>'},
										{field:'grreturno',title:'<?php echo GetCatalog('grreturno') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('supplier') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
        <div class="easyui-tabs" style="width:auto;height:400px">
		<div title="Product" style="padding:5px">
			<table id="dg-notagrrpro"  style="width:100%">
			</table>
			<div id="tb-notagrrpro">
				<!-- <a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-notagrrpro').edatagrid('addRow')"></a> -->
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-notagrrpro').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-notagrrpro').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-notagrrpro').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#notagrretur_search_notagrreturid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagrretur();
			}
		}
	})
});
$('#notagrretur_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagrretur();
			}
		}
	})
});
$('#notagrretur_search_notagrreturno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagrretur();
			}
		}
	})
});
$('#notagrretur_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagrretur();
			}
		}
	})
});
$('#notagrretur_search_grreturno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagrretur();
			}
		}
	})
});
$('#notagrretur_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagrretur();
			}
		}
	})
});
$('#notagrretur_search_poheader').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagrretur();
			}
		}
	})
});
$('#notagrretur_search_supplier').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagrretur();
			}
		}
	})
});
$('#dg-notagrretur').edatagrid({	
		singleSelect: false,
		toolbar:'#tb-notagrretur',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editNotagrretur(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
                detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-notagrrpro"></table><table class="ddv-notagrracc"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvnotagrrpro = $(this).datagrid('getRowDetail',index).find('table.ddv-notagrrpro');
			var ddvnotagrracc = $(this).datagrid('getRowDetail',index).find('table.ddv-notagrracc');
			ddvnotagrrpro.datagrid({
				url:'<?php echo $this->createUrl('notagrretur/indexproduct',array('grid'=>true)) ?>?id='+row.notagrreturid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				pagination:true,
				showFooter:true,
				columns:[[
					{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
                                        {field:'qty',title:'<?php echo GetCatalog('qty') ?>',align:'right'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
					{field:'price',title:'<?php echo GetCatalog('price') ?>',align:'right'},
					{field:'sloccode',title:'<?php echo GetCatalog('sloc') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'total',title:'<?php echo GetCatalog('total') ?>',align:'right'},
				]],
				onResize:function(){
						$('#dg-notagrretur').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-notagrretur').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
                        ddvnotagrracc.datagrid({
				url:'<?php echo $this->createUrl('notagrretur/indexakun',array('grid'=>true)) ?>?id='+row.notagrreturid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				pagination:true,
				showFooter:true,
				columns:[[
					{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>'},
          {field:'debet',title:'<?php echo GetCatalog('debet') ?>',align:'right'},
					{field:'credit',title:'<?php echo GetCatalog('credit') ?>',align:'right'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>'},
				]],
				onResize:function(){
						$('#dg-notagrretur').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-notagrretur').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-notagrretur').datagrid('fixDetailRowHeight',index);
                },
                url: '<?php echo $this->createUrl('notagrretur/index',array('grid'=>true)) ?>',
                onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-notagrretur').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'notagrreturid',
		editing: false,
		columns:[[
		{
field:'notagrreturid',
title:'<?php echo GetCatalog('notagrreturid') ?>',
sortable: true,
width:'60px',
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
field:'companyid',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
					return row.companyname;
					}},
{
field:'notagrreturno',
title:'<?php echo GetCatalog('notagrreturno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'grreturid',
title:'<?php echo GetCatalog('grreturno') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.grreturno;
					}},
					{
field:'poheaderid',
title:'<?php echo GetCatalog('poheader') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.pono;
					}},
										{
field:'addressbookid',
title:'<?php echo GetCatalog('supplier') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.fullname;
					}},
{
field:'recordstatusnotagrretur',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchnotagrretur(value){
	$('#dg-notagrretur').edatagrid('load',{
		notagrreturid:$('#notagrretur_search_notagrreturid').val(),
		docdate:$('#notagrretur_search_docdate').val(),
		grreturid:$('#notagrretur_search_grreturno').val(),
		poheaderid:$('#notagrretur_search_poheader').val(),
		addressbook:$('#notagrretur_search_supplier').val(),
		company:$('#notagrretur_search_companyname').val(),
		notagrreturno:$('#notagrretur_search_notagrreturno').val(),
	});
}
function approvenotagrretur() {
	var ss = [];
	var rows = $('#dg-notagrretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.notagrreturid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('notagrretur/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-notagrretur').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelnotagrretur() {
	var ss = [];
	var rows = $('#dg-notagrretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.notagrreturid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('notagrretur/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-notagrretur').edatagrid('reload');				
		} ,
		'cache':false});
};
function addNotagrretur() {
		$('#dlg-notagrretur').dialog('open');
		$('#ff-notagrretur-modif').form('clear');
		$('#ff-notagrretur-modif').form('load','<?php echo $this->createUrl('notagrretur/getdata') ?>');
};

function editNotagrretur($i) {
	var row = $('#dg-notagrretur').datagrid('getSelected');
	if(row) {
		$('#dlg-notagrretur').dialog('open');
		$('#ff-notagrretur-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormNotagrretur(){
	$('#ff-notagrretur-modif').form('submit',{
		url:'<?php echo $this->createUrl('notagrretur/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-notagrretur').datagrid('reload');
        $('#dlg-notagrretur').dialog('close');
			}
    }
	});	
};

function clearFormNotagrretur(){
		$('#ff-notagrretur-modif').form('clear');
};

function cancelFormNotagrretur(){
		$('#dlg-notagrretur').dialog('close');
};
$('#ff-notagrretur-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-notagrretur').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-notagrrpro').datagrid({
			queryParams: {
				id: $('#notagrreturid').val()
			}
		});
                $('#dg-notagrracc').datagrid({
			queryParams: {
				id: $('#notagrreturid').val()
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
function downpdfnotagrretur() {
	var ss = [];
	var rows = $('#dg-notagrretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.notagrreturid);
	}
	window.open('<?php echo $this->createUrl('notagrretur/downpdf') ?>?id='+ss);
}

$('#dg-notagrrpro').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'notagrrproid',
	editing: true,
	toolbar:'#tb-notagrrpro',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('notagrretur/searchproduct',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('notagrretur/saveproduct',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('notagrretur/saveproduct',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('notagrretur/purgeproduct',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-notagrrpro').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var ixs = $('#dg-notagrretur').edatagrid('getSelected');
		var row = $('#dg-notagrrpro').edatagrid('getSelected');
		if (ixs)
		{
			row.notagrreturid = ixs.notagrreturid;
		}
	},
        columns:[[
	{
		field:'notagrreturid',
		title:'<?php echo GetCatalog('notagrreturid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'notagrrproid',
		title:'<?php echo GetCatalog('notagrrproid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'productid',
		title:'<?php echo GetCatalog('productname') ?>',
		/*editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						//required:true,
						readonly:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo GetCatalog('productid')?>'},
							{field:'productname',title:'<?php echo GetCatalog('productname')?>'},
						]]
				}	
			},*/
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.productname;
		}
	},
        {
		field:'qty',
		title:'<?php echo GetCatalog('qty') ?>',
		/*editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				//required:true,
				readonly:true,
				value:'0',
			}
		},*/
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
        {
		field:'uomid',
		title:'<?php echo GetCatalog('uom') ?>',
		width:'50px',		
		sortable: true,
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
	{
		field:'price',
		title:'<?php echo GetCatalog('price') ?>',
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
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
        {
		field:'slocid',
		title:'<?php echo GetCatalog('sloc') ?>',
		width:'100px',		
		sortable: true,
		formatter: function(value,row,index){
							return row.sloccode;
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
		title:'<?php echo GetCatalog('currencyrate') ?>',
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
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
        {
		field:'total',
		title:'<?php echo GetCatalog('total') ?>',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return row.total;
		}
	},
	]]
});

$('#dg-notagrracc').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'notagrraccid',
	editing: true,
	toolbar:'#tb-notagrracc',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('notagrretur/searchakun',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('notagrretur/saveakun',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('notagrretur/saveakun',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('notagrretur/purgeakun',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-notagrrpro').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var ixs = $('#dg-notagrretur').edatagrid('getSelected');
		var row = $('#dg-notagrracc').edatagrid('getSelected');
		if (ixs)
		{
			row.notagrreturid = ixs.notagrreturid;
		}
	},
        columns:[[
	{
		field:'notagrreturid',
		title:'<?php echo GetCatalog('notagrreturid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'notagrraccid',
		title:'<?php echo GetCatalog('notagrrproid') ?>',
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
                                                                pagination:true,
                                                                idField:'accountid',
                                                                textField:'accountname',
                                                                url:'<?php echo Yii::app()->createUrl('accounting/account/index',array('grid'=>true)) ?>',
                                                                fitColumns:true,
                                                                loadMsg: '<?php echo GetCatalog('pleasewait')?>',
                                                                columns:[[
                                                                        {field:'accountid',title:'<?php echo GetCatalog('accountid')?>',width:50},
                                                                        {field:'accountname',title:'<?php echo GetCatalog('accountname')?>',width:200},
                                                                ]]
                                                }	
                                        },
                sortable: true,
                formatter: function(value,row,index){
                                                                return row.accountname;
                                                        }},
        {
		field:'debet',
		title:'<?php echo GetCatalog('debet') ?>',
		width:'100px',
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
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
        {
		field:'credit',
		title:'<?php echo GetCatalog('credit') ?>',
		width:'100px',
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
		sortable: true,
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
		title:'<?php echo GetCatalog('currencyrate') ?>',
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
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'itemnote',
		title:'<?php echo GetCatalog('itemnote') 

?>',
                editor:'text',
		multiline:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
