<table id="dg-giretur" style="width:1200px;height:97%"></table>
<div id="tb-giretur">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addGiretur()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editGiretur()"></a>
	<?php }?>
			<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvegiretur()"></a>
<?php }?>
<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelgiretur()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfgiretur()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsgiretur()"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('gireturid')?></td>
<td><input class="easyui-textbox" id="giretur_search_gireturid" style="width:150px"></td>
<td><?php echo getCatalog('gireturdate')?></td>
<td><input class="easyui-textbox" id="giretur_search_gireturdate" style="width:150px"></td>
<td><?php echo getCatalog('gireturno')?></td></td>
<td><input class="easyui-textbox" id="giretur_search_gireturno" style="width:150px"></td></td>
</tr>
<tr>
<td><?php echo getCatalog('company')?></td>
<td><input class="easyui-textbox" id="giretur_search_companyid" style="width:150px"></td>
<td><?php echo getCatalog('gino')?></td>
<td><input class="easyui-textbox" id="giretur_search_giheaderid" style="width:150px"></td>
<td><?php echo getCatalog('customer')?></td>
<td><input class="easyui-textbox" id="giretur_search_fullname" style="width:150px"></td>
</tr>
<tr>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="giretur_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchgiretur()"></a></td>
</tr>
</table>
</div>

<div id="dlg-giretur" class="easyui-dialog" title="<?php echo getCatalog('giretur')?>" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormGiretur();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-giretur').dialog('close');
			}
		},
	]	
	">
	<form id="ff-giretur-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="gireturid" id="gireturid"></input>
		<table cellpadding="5">
		<tr>
				<td><?php echo getCatalog('gireturdate')?></td>
				<td><input class="easyui-datebox" type="text" id="gireturdate" name="gireturdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('giheader')?></td>
				<td><select class="easyui-combogrid" id="giheaderid" name="giheaderid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'giheaderid',
								textField: 'gino',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('inventory/giheader/indexgiretur',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								onHidePanel: function(){
										jQuery.ajax({'url':'<?php echo $this->createUrl('giretur/generategi') ?>',
											'data':{
													'id':$('#giheaderid').combogrid('getValue'),
													'hid':$('#gireturid').val()},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
															
											} ,
											'cache':false});
											
											$('#dg-gireturdetail').datagrid({
											queryParams: {
												id: $('#gireturid').val()}
									});
								},
								columns: [[
										{field:'giheaderid',title:'<?php echo getCatalog('giheaderid') ?>'},
										{field:'pocustno',title:'<?php echo getCatalog('pocustno') ?>'},
										{field:'gino',title:'<?php echo getCatalog('gino') ?>'},
										{field:'fullname',title:'<?php echo getCatalog('customer') ?>'},
										{field:'companyname',title:'<?php echo getCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>			
			<tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
		<div title="Detail" style="padding:5px">
			<table id="dg-gireturdetail"  style="width:auto">
			</table>
			<div id="tb-gireturdetail">
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-gireturdetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-gireturdetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-gireturdetail').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#giretur_search_gireturid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiretur();
			}
		}
	})
});
$('#giretur_search_gireturdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiretur();
			}
		}
	})
});
$('#giretur_search_companyid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiretur();
			}
		}
	})
});
$('#giretur_search_gireturno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiretur();
			}
		}
	})
});
$('#giretur_search_giheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiretur();
			}
		}
	})
});
$('#giretur_search_fullname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiretur();
			}
		}
	})
});
$('#giretur_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiretur();
			}
		}
	})
});
$('#dg-giretur').edatagrid({
		singleSelect: false,
		toolbar:'#tb-giretur',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editGiretur(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-gireturdetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvgireturdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-gireturdetail');
			ddvgireturdetail.datagrid({
				url:'<?php echo $this->createUrl('giretur/indexdetail',array('grid'=>true)) ?>?id='+row.gireturid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo getCatalog('pleasewait') ?>',
				height:'auto',
				width:'1200px',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
					{field:'sloccode',title:'<?php echo getCatalog('sloc') ?>'},
					{field:'description',title:'<?php echo getCatalog('storagebin') ?>'},
					{field:'itemnote',title:'<?php echo getCatalog('itemnote') ?>',width:'300px'},
				]],
				onResize:function(){
						$('#dg-giretur').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-giretur').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-giretur').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('giretur/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-giretur').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'gireturid',
		editing: false,
		columns:[[
		{
field:'gireturid',
title:'<?php echo getCatalog('gireturid') ?>',
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
title:'<?php echo getCatalog('company') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.companyname;
					}},
{
field:'gireturno',
title:'<?php echo getCatalog('gireturno') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'gireturdate',
title:'<?php echo getCatalog('gireturdate') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'gino',
title:'<?php echo getCatalog('gino') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'customername',
title:'<?php echo getCatalog('customer') ?>',
sortable: true,
width:'300px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'headernote',
title:'<?php echo getCatalog('headernote') ?>',
sortable: true,
width:'350px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo getCatalog('recordstatus') ?>',
width:'130px',
sortable: true,
formatter: function(value,row,index){
						return row.recordstatusgiretur;
					}},
		]]
});

function searchgiretur(value){
	$('#dg-giretur').edatagrid('load',{
	gireturid:$('#giretur_search_gireturid').val(),
gireturdate:$('#giretur_search_gireturdate').val(),
giheaderid:$('#giretur_search_giheaderid').val(),
gireturno:$('#giretur_search_gireturno').val(),
companyid:$('#giretur_search_companyid').val(),
fullname:$('#giretur_search_fullname').val(),
headernote:$('#giretur_search_headernote').val(),
	});
};

function approvegiretur() {
	var ss = [];
	var rows = $('#dg-giretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.gireturid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('giretur/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-giretur').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelgiretur() {
	var ss = [];
	var rows = $('#dg-giretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.gireturid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('giretur/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-giretur').edatagrid('reload');				
		} ,
		'cache':false});
};

function downpdfgiretur() {
	var ss = [];
	var rows = $('#dg-giretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.gireturid);
	}
	window.open('<?php echo $this->createUrl('giretur/downpdf') ?>?id='+ss);
};

function downxlsgiretur() {
	var ss = [];
	var rows = $('#dg-giretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.gireturid);
	}
	window.open('<?php echo $this->createUrl('giretur/downxls') ?>?id='+ss);
}

function addGiretur() {
		$('#dlg-giretur').dialog('open');
		$('#ff-giretur-modif').form('clear');
		$('#ff-giretur-modif').form('load','<?php echo $this->createUrl('giretur/GetData') ?>');
};

function editGiretur($i) {
	var row = $('#dg-giretur').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appgiretur') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-giretur').dialog('open');
		$('#ff-giretur-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormGiretur(){
	$('#ff-giretur-modif').form('submit',{
		url:'<?php echo $this->createUrl('giretur/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-giretur').datagrid('reload');
        $('#dlg-giretur').dialog('close');
			}
    }
	});	
};

function clearFormGiretur(){
		$('#ff-giretur-modif').form('clear');
};

function cancelFormGiretur(){
		$('#dlg-giretur').dialog('close');
};

$('#ff-giretur-modif').form({
	onLoadSuccess: function(data) {
		$('#sodate').datebox('setValue', data.sodate);
		$('#dg-gireturdetail').datagrid({
			queryParams: {
				id: $('#gireturid').val()
			}
		});
		$('#dg-custdisc').datagrid({
				queryParams: {
					id:$('#gireturid').val()
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

$('#dg-gireturdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'gireturdetailid',
	editing: true,
	toolbar:'#tb-gireturdetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('giretur/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('giretur/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('giretur/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('giretur/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-gireturdetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-gireturdetail').edatagrid('getSelected');
		if (row)
		{
			row.gireturid = $('#gireturid').val();
		}
	},
	columns:[[
	{
		field:'gireturid',
		title:'<?php echo getCatalog('gireturid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'gireturdetailid',
		title:'<?php echo getCatalog('gireturdetailid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productid',
		title:'<?php echo getCatalog('product') ?>',
		width:'400px',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:'900px',
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
						onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $("#dg-gireturdetail").datagrid("getEditor", {index: index, field:"productid"});
								var uomid = $("#dg-gireturdetail").datagrid("getEditor", {index: index, field:"uomid"});
								var slocid = $("#dg-gireturdetail").datagrid("getEditor", {index: index, field:"slocid"});
								var storagebinid = $("#dg-gireturdetail").datagrid("getEditor", {index: index, field:"storagebinid"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdatasales') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue")},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uomid);
										$(slocid.target).combogrid('setValue',data.slocid);
										$(storagebinid.target).combogrid('setValue',data.storagebinid);
									} ,
									'cache':false});
							}
						},
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo getCatalog('productid')?>'},
							{field:'productname',title:'<?php echo getCatalog('productname')?>'},
						]]
				}	
			},
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
	{
		field:'uomid',
		title:'<?php echo getCatalog('uom') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode: 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						readonly:true,
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
		field:'slocid',
		title:'<?php echo getCatalog('sloc') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode: 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						//readonly:true,
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'slocid',title:'<?php echo getCatalog('slocid')?>',width:50},
							{field:'sloccode',title:'<?php echo getCatalog('sloccode')?>',width:200},
						]]
				}	
			},
			width:'150px',
		sortable: true,
		formatter: function(value,row,index){
							return row.sloccode;
		}
	},
	{
		field:'storagebinid',
		title:'<?php echo getCatalog('storagebin') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
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
						//readonly:true,
						onBeforeLoad: function(param) {
							var row = $('#dg-gireturdetail').datagrid('getSelected');
							param.slocid = row.slocid;
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:200},
							{field:'sloccode',title:'<?php echo getCatalog('sloccode')?>',width:200},
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
		field:'itemnote',
		title:'<?php echo getCatalog('itemnote') 

?>',
		editor:'text',
		width:'250px',
		multiline:true,
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	}
	]]
});
</script>
