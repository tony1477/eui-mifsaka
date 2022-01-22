<!-- Data Grid ( #dg-bsheader ) -->
<table id="dg-bsheader" style="width:100%;height:97%"></table>
<div id="tb-bsheader">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addBsheader()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editBsheader()"></a>
	<?php }?>
			<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvebsheader()"></a>
<?php }?>
<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelbsheader()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfbsheader()"></a>
		<a href="javascript:void(0)" title="Produk yang akan MINUS"class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="downpdfminus()"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('bsheaderid')?></td>
<td><input class="easyui-textbox" id="bsheader_search_bsheaderid" style="width:150px"></td>
<td><?php echo getCatalog('bsdate')?></td>
<td><input class="easyui-textbox" id="bsheader_search_bsdate" style="width:150px"></td>
<td><?php echo getCatalog('bsheaderno')?></td></td>
<td><input class="easyui-textbox" id="bsheader_search_bsno" style="width:150px"></td></td>
</tr>
<tr>
<td><?php echo getCatalog('sloccode')?></td>
<td><input class="easyui-textbox" id="bsheader_search_slocid" style="width:150px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="bsheader_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchbsheader()"></a></td>
</tr>
</table></div>

<div id="dlg-bsheader" class="easyui-dialog" title="Stok Opname" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormBsheader();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-bsheader').dialog('close');
			}
		},
	]	
	">
	<form id="ff-bsheader-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="bsheaderid" id="bsheaderid"></input>
		<table cellpadding="5">
		<tr>
				<td><?php echo getCatalog('bsdate')?></td>
				<td><input class="easyui-datebox" type="text" id="bsdate" name="bsdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('sloc')?></td>
				<td><select class="easyui-combogrid" name="slocid" id="slocid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'slocid',
								textField: 'sloccode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocid') ?>',width:80},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
		<div title="Detail" style="padding:5px">
			<table id="dg-bsdetail"  style="width:100%">
			</table>
			<div id="tb-bsdetail">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-bsdetail').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-bsdetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-bsdetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-bsdetail').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#bsheader_search_bsheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchbsheader();
			}
		}
	})
});
$('#bsheader_search_bsdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchbsheader();
			}
		}
	})
});
$('#bsheader_search_bsno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchbsheader();
			}
		}
	})
});
$('#bsheader_search_slocid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchbsheader();
			}
		}
	})
});
$('#bsheader_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchbsheader();
			}
		}
	})
});
$('#dg-bsheader').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-bsheader',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:true,
    url: '<?php echo $this->createUrl('bsheader/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('bsheader/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('bsheader/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('bsheader/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-bsheader').edatagrid('reload');
		},
		onDblClickRow: function (index,row) {
			editBsheader(index);
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-bsdetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvaddress = $(this).datagrid('getRowDetail',index).find('table.ddv-bsdetail');
			ddvaddress.datagrid({
				url:'<?php echo $this->createUrl('bsheader/indexdetail',array('grid'=>true)) ?>?id='+row.bsheaderid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo getCatalog('pleasewait') ?>',
				height:'auto',
				fitColumns:true,
				pagination:true,
				width:'auto',
				columns:[[
						{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
						{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right'},
						{field:'qtystock',title:'<?php echo getCatalog('qtystock') ?>',align:'right'},
						{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
						{field:'description',title:'<?php echo getCatalog('storagebin') ?>'},
						{field:'expiredate',title:'<?php echo getCatalog('expiredate') ?>'},
						{field:'materialstatusname',title:'<?php echo getCatalog('materialstatusname') ?>'},
						{field:'ownershipname',title:'<?php echo getCatalog('ownershipname') ?>'},
						{field:'currencyname',title:'<?php echo getCatalog('currency') ?>',hidden:<?php echo GetMenuAuth('currency')?>,align:'right'},
						{field:'buyprice',title:'<?php echo getCatalog('buyprice') ?>',hidden:<?php echo GetMenuAuth('currency')?>,align:'right'},
						{field:'buypricestock',title:'<?php echo getCatalog('buypricestock') ?>',hidden:<?php echo GetMenuAuth('currency')?>,align:'right'},
						{field:'currencyrate',title:'<?php echo getCatalog('currencyrate') ?>',hidden:<?php echo GetMenuAuth('currency')?>,align:'right'},
				]],
				onResize:function(){
						$('#dg-bsheader').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-bsheader').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-bsheader').datagrid('fixDetailRowHeight',index);
		},
		idField:'bsheaderid',
		editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'bsheaderid',
title:'<?php echo getCatalog('bsheaderid') ?>',
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
					return '<div style="background-color:cyan;color:black">'+value+'</div>';
				} else 
					if (row.recordstatus == 4) {
					return '<div style="background-color:blue;color:white">'+value+'</div>';
				} else 
					if (row.recordstatus == 5) {
					return '<div style="background-color:red;color:white">'+value+'</div>';
				} else 
						if (row.recordstatus == 0) {
						return '<div style="background-color:black;color:white">'+value+'</div>';
					}
					}},
{
field:'bsdate',
title:'<?php echo getCatalog('bsdate') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'bsheaderno',
title:'<?php echo getCatalog('bsheaderno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
					{
field:'slocid',
title:'<?php echo getCatalog('sloc') ?>',
sortable: true,
width:'300px',
formatter: function(value,row,index){
						return row.sloccode;
					}},
{
field:'headernote',
title:'<?php echo getCatalog('headernote') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatusbsheader',
title:'<?php echo getCatalog('recordstatus') ?>',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});

function searchbsheader(value){
	$('#dg-bsheader').edatagrid('load',{
bsheaderid:$('#bsheader_search_bsheaderid').val(),
slocid:$('#bsheader_search_slocid').val(),
bsdate:$('#bsheader_search_bsdate').val(),
bsheaderno:$('#bsheader_search_bsno').val(),
headernote:$('#bsheader_search_headernote').val()
	});
};

function approvebsheader() {
	var ss = [];
	var rows = $('#dg-bsheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.bsheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('bsheader/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-bsheader').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelbsheader() {
	var ss = [];
	var rows = $('#dg-bsheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.bsheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('bsheader/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-bsheader').edatagrid('reload');				
		} ,
		'cache':false});
};

function downpdfbsheader() {
	var ss = [];
	var rows = $('#dg-bsheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.bsheaderid);
	}
	window.open('<?php echo $this->createUrl('bsheader/downpdf') ?>?id='+ss);
}
function downpdfminus() {
	var ss = [];
	var rows = $('#dg-bsheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.bsheaderid);
	}
	window.open('<?php echo $this->createUrl('bsheader/downpdfminus') ?>?id='+ss);
}
function downxlsbsheader() {
	var ss = [];
	var rows = $('#dg-bsheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.bsheaderid);
	}
	window.open('<?php echo $this->createUrl('bsheader/downxls') ?>?id='+ss);
}
function addBsheader() {
		$('#dlg-bsheader').dialog('open');
		$('#ff-bsheader-modif').form('clear');
		$('#ff-bsheader-modif').form('load','<?php echo $this->createUrl('bsheader/GetData') ?>');
		$('#bsdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};
function editBsheader($i) {
	var row = $('#dg-bsheader').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appbs') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-bsheader').dialog('open');
			$('#ff-bsheader-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormBsheader(){
	$('#ff-bsheader-modif').form('submit',{
		url:'<?php echo $this->createUrl('bsheader/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg);
			if (data.isError == false){
        $('#dg-bsheader').datagrid('reload');
        $('#dlg-bsheader').dialog('close');
			}
    }
	});	
};

function clearFormBsheader(){
		$('#ff-bsheader-modif').form('clear');
};

function cancelFormBsheader(){
		$('#dlg-bsheader').dialog('close');
};

$('#ff-bsheader-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-bsdetail').datagrid({
				queryParams: {
					id: $('#bsheaderid').val()
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

$('#dg-bsdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'bsdetailid',
	editing: true,
	toolbar:'#tb-bsdetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('bsheader/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('bsheader/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('bsheader/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('bsheader/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-bsdetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-bsdetail').edatagrid('getSelected');
		if (row)
		{
			row.bsheaderid = $('#bsheaderid').val();
		}
	},
	columns:[[
	{
		field:'bsheaderid',
		title:'<?php echo getCatalog('bsheaderid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'bsdetailid',
		title:'<?php echo getCatalog('bsdetailid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productid',
		title:'<?php echo getCatalog('product') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'800px',
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $("#dg-bsdetail").datagrid("getEditor", {index: index, field:"productid"});
								var unitofmeasureid = $("#dg-bsdetail").datagrid("getEditor", {index: index, field:"unitofmeasureid"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdata') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue")},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(unitofmeasureid.target).combogrid('setValue',data.uomid);
									} ,
									'cache':false});
							}
						},
						required:true,
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo getCatalog('productid')?>',width:50},
							{field:'productname',title:'<?php echo getCatalog('productname')?>',width:200},
						]]
				}	
			},
			width:'100px',			
		sortable: true,
		formatter: function(value,row,index){
							return row.productname;
		}
	},
	{
		field:'qty',
		title:'<?php echo getCatalog('qty') ?>',
		editor:{
			type:'numberbox',
			options:{
				precision:6,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'qtystock',
		title:'<?php echo getCatalog('qtystock') ?>',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'unitofmeasureid',
		title:'<?php echo getCatalog('uom') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'800px',
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'unitofmeasureid',title:'<?php echo getCatalog('unitofmeasureid')?>',width:50},
							{field:'uomcode',title:'<?php echo getCatalog('uomcode')?>',width:200},
						]]
				}	
			},
			width:'150px',
		sortable: true,
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
	{
		field:'ownershipid',
		title:'<?php echo getCatalog('ownership') ?>',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:'300px',
					mode : 'remote',
					method:'get',
					idField:'ownershipid',
					textField:'ownershipname',
					url:'<?php echo Yii::app()->createUrl('common/ownership/index',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '<?php echo getCatalog('pleasewait')?>',
					columns:[[
						{field:'ownershipid',title:'<?php echo getCatalog('ownershipid')?>',width:50},
						{field:'ownershipname',title:'<?php echo getCatalog('ownershipname')?>',width:200},
					]]
				}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
							return row.ownershipname;
		}
	},
	{
		field:'expiredate',
		title:'<?php echo getCatalog('expiredate') ?>',
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
		}
	},
	{
		field:'materialstatusid',
		title:'<?php echo getCatalog('materialstatus') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'300px',
						mode : 'remote',
						method:'get',
						idField:'materialstatusid',
						textField:'materialstatusname',
						url:'<?php echo Yii::app()->createUrl('common/materialstatus/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'materialstatusid',title:'<?php echo getCatalog('materialstatusid')?>',width:50},
							{field:'materialstatusname',title:'<?php echo getCatalog('materialstatusname')?>',width:200},
						]]
				}	
			},
		width:'150px',	
		sortable: true,
		formatter: function(value,row,index){
							return row.materialstatusname;
		}
	},
	{
		field:'storagebinid',
		title:'<?php echo getCatalog('storagebin') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'300px',
						mode : 'remote',
						method:'get',
						idField:'storagebinid',
						textField:'description',
						url:'<?php echo Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							slocid:1
						},
						onBeforeLoad: function(param) {
							var row = $('#dg-bsheader').datagrid('getSelected');
							//param.slocid = row.slocid;
							param.slocid = $('#slocid').combogrid('getValue');
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:110},
							{field:'sloccode',title:'<?php echo getCatalog('sloccode')?>',width:50},
						]]
				}	
			},			
			width:'150px',
		sortable: true,
		formatter: function(value,row,index){
								return row.description;
		}
	},
	{
		field:'location',
		title:'<?php echo getCatalog('location') ?>',
		multiline:true,
		editor:'textbox',
		required:true,
		width:'350px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'currencyid',
		title:'<?php echo getCatalog('currency') ?>',
                hidden:<?php echo GetMenuAuth('currency')?>,
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'300px',
						mode : 'remote',
						method:'get',
						idField:'currencyid',
						textField:'currencyname',
						url:'<?php echo Yii::app()->createUrl('admin/currency/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						//required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'currencyid',title:'<?php echo getCatalog('currencyid')?>',width:50},
							{field:'currencyname',title:'<?php echo getCatalog('currencyname')?>',width:200},
						]]
				}	
			},
		width:'150px',	
		sortable: true,
		formatter: function(value,row,index){
							return row.currencyname;
		}
	},
	{
		field:'buyprice',
		title:'<?php echo getCatalog('buyprice') ?>',
                hidden:<?php echo GetMenuAuth('currency')?>,
		editor:{
			type:'numberbox',
			options:{
				precision:6,
				decimalSeparator:',',
				groupSeparator:'.',
				//required:true,
			}
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'buypricestock',
		title:'<?php echo getCatalog('buypricestock') ?>',
                hidden:<?php echo GetMenuAuth('currency')?>,
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'currencyrate',
		title:'<?php echo getCatalog('currencyrate') ?>',
                hidden:<?php echo GetMenuAuth('currency')?>,
		editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				//required:true,
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
		title:'<?php echo getCatalog('itemnote') 

?>',
		editor:'text',
		width:'350px',
		multiline:true,
		required:true,
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	]]
}); 
</script>
