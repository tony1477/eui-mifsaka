<table id="dg-grretur" style="width:1200px;height:97%"></table>
<div id="tb-grretur">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addGrretur()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editGrretur()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvegrretur()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelgrretur()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfgrretur()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsgrretur()"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('grreturid')?></td>
<td><input class="easyui-textbox" id="grretur_search_grreturid" style="width:150px"></td>
<td><?php echo getCatalog('grreturdate')?></td>
<td><input class="easyui-textbox" id="grretur_search_grreturdate" style="width:150px"></td>
<td><?php echo getCatalog('grreturno')?></td></td>
<td><input class="easyui-textbox" id="grretur_search_grreturno" style="width:150px"></td></td>
</tr>
<tr>
<td><?php echo getCatalog('poheader')?></td>
<td><input class="easyui-textbox" id="grretur_search_poheaderid" style="width:150px"></td>
<td><?php echo getCatalog('supplier')?></td>
<td><input class="easyui-textbox" id="grretur_search_supplier" style="width:150px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="grretur_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchgrretur()"></a></td>
</tr>
</table>
</div>

<div id="dlg-grretur" class="easyui-dialog" title="Goods Receipt Return" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormGrretur();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-grretur').dialog('close');
			}
		},
	]	
	">
	<form id="ff-grretur-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="grreturid" id="grreturid"></input>
		<table cellpadding="5">
                        <tr>
				<td><?php echo getCatalog('grreturdate')?></td>
				<td><input class="easyui-datebox" type="text" id="grreturdate" name="grreturdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('poheader')?></td>
				<td><select class="easyui-combogrid" name="poheaderid" id="poheaderid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'poheaderid',
								textField: 'pono',
								pagination:true,
								mode:'remote',
								required:true,
								url: '<?php echo Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true)) ?>',
								method: 'get',
                onHidePanel: function(){
										jQuery.ajax({'url':'<?php echo $this->createUrl('grretur/generatedetail') ?>',
											'data':{
												'id':$('#poheaderid').combogrid('getValue'),
												'hid':$('#grreturid').val()
											},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#headernote').textbox('setValue',data.headernote);
												$('#dg-grreturdetail').edatagrid('reload');				
											} ,
											'cache':false});
								},
								queryParams: {
									grrpo:true
								},
								columns: [[
										{field:'poheaderid',title:'<?php echo getCatalog('poheaderid') ?>'},
										{field:'pono',title:'<?php echo getCatalog('pono') ?>'},
										{field:'fullname',title:'<?php echo getCatalog('supplier') ?>'},
										{field:'headernote',title:'<?php echo getCatalog('headernote') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
                        <tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="required:true,multiline:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="Detail" style="padding:5px">
				<table id="dg-grreturdetail"  style="width:100%">
				</table>
				<div id="tb-grreturdetail">
					<!-- <a href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-grreturdetail').edatagrid('addRow')"></a> -->
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-grreturdetail').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-grreturdetail').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-grreturdetail').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$('#grretur_search_grreturid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrretur();
			}
		}
	})
});
$('#grretur_search_grreturdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrretur();
			}
		}
	})
});
$('#grretur_search_grreturno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrretur();
			}
		}
	})
});
$('#grretur_search_poheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrretur();
			}
		}
	})
});
$('#grretur_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrretur();
			}
		}
	})
});
$('#grretur_search_supplier').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrretur();
			}
		}
	})
});
$('#dg-grretur').edatagrid({	
		singleSelect: false,
		toolbar:'#tb-grretur',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
                onDblClickRow: function (index,row) {
			editGrretur(index);
		},
                rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
                view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-grreturdetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvgrreturdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-grreturdetail');
			ddvgrreturdetail.datagrid({
				url:'<?php echo $this->createUrl('grretur/indexdetail',array('grid'=>true)) ?>?id='+row.grreturid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo getCatalog('pleasewait') ?>',
				pagination:true,
				height:'auto',
				width:'100%',
				columns:[[
					{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
					{field:'sloccode',title:'<?php echo getCatalog('sloc') ?>'},
					{field:'description',title:'<?php echo getCatalog('storagebin') ?>'},
					{field:'itemnote',title:'<?php echo getCatalog('itemnote') ?>'},
				]],
				onResize:function(){
						$('#dg-grretur').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-grretur').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-grretur').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('grretur/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-grretur').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'grreturid',
		editing: false,
		columns:[[
		{
field:'grreturid',
title:'<?php echo getCatalog('grreturid') ?>',
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
field:'grreturno',
title:'<?php echo getCatalog('grreturno') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'grreturdate',
title:'<?php echo getCatalog('grreturdate') ?>',
sortable: true,
width:'130px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'poheaderid',
title:'<?php echo getCatalog('pono') ?>',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return row.pono;
					}},
					{
field:'fullname',
title:'<?php echo getCatalog('supplier') ?>',
width:'350px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'headernote',
title:'<?php echo getCatalog('headernote') ?>',
width:'180px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatusgrretur',
title:'<?php echo getCatalog('recordstatus') ?>',
width:'120px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});

function searchgrretur(value){
	$('#dg-grretur').edatagrid('load',{
	grreturid:$('#grretur_search_grreturid').val(),
        grreturno:$('#grretur_search_grreturno').val(),
        grreturdate:$('#grretur_search_grreturdate').val(),
        poheaderid:$('#grretur_search_poheaderid').val(),
        fullname:$('#grretur_search_supplier').val(),
        headernote:$('#grretur_search_headernote').val()
	});
}

function approvegrretur() {
	var ss = [];
	var rows = $('#dg-grretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grreturid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('grretur/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-grretur').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelgrretur() {
	var ss = [];
	var rows = $('#dg-grretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grreturid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('grretur/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-grretur').edatagrid('reload');				
		} ,
		'cache':false});
};
function addGrretur() {
		$('#dlg-grretur').dialog('open');
		$('#ff-grretur-modif').form('clear');
		$('#ff-grretur-modif').form('load','<?php echo $this->createUrl('grretur/getdata') ?>');
};

function editGrretur($i) {
	var row = $('#dg-grretur').datagrid('getSelected');
	if(row) {
		$('#dlg-grretur').dialog('open');
		$('#ff-grretur-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormGrretur(){
	$('#ff-grretur-modif').form('submit',{
		url:'<?php echo $this->createUrl('grretur/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-grretur').datagrid('reload');
        $('#dlg-grretur').dialog('close');
			}
    }
	});	
};

function clearFormGrretur(){
		$('#ff-grretur-modif').form('clear');
};

function cancelFormGrretur(){
		$('#dlg-grretur').dialog('close');
};

$('#ff-grretur-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-grretur').datagrid('getSelected');
		if(row) {
			$('#grreturdate').datebox('setValue', data.grreturdate);			
		}
		$('#dg-grreturdetail').datagrid({
			queryParams: {
				id: $('#grreturid').val()
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

function downpdfgrretur() {
	var ss = [];
	var rows = $('#dg-grretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grreturid);
	}
	window.open('<?php echo $this->createUrl('grretur/downpdf') ?>?id='+ss);
}

function downxlsgrretur() {
	var ss = [];
	var rows = $('#dg-grretur').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grreturid);
	}
	window.open('<?php echo $this->createUrl('grretur/downxls') ?>?id='+ss);
}

$('#dg-grreturdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	ctrlSelect: true,
	idField:'grreturdetailid',
	editing: true,
	toolbar:'#tb-grreturdetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('grretur/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('grretur/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('grretur/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('grretur/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.message);
		$('#dg-grreturdetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.message);
	},
	onBeforeSave: function(index){
		var row = $('#dg-grreturdetail').edatagrid('getSelected');
		if (row)
		{
			row.grreturid = $('#grreturid').val();
		}
	},
	columns:[[
	{
		field:'grreturid',
		title:'<?php echo getCatalog('grreturid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'grreturdetailid',
		title:'<?php echo getCatalog('grreturdetailid') ?>',
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
								var productid = $("#dg-grreturdetail").datagrid("getEditor", {index: index, field:"productid"});
								var uomid = $("#dg-grreturdetail").datagrid("getEditor", {index: index, field:"uomid"});
								var slocid = $("#dg-grreturdetail").datagrid("getEditor", {index: index, field:"slocid"});
								var storagebinid = $("#dg-grreturdetail").datagrid("getEditor", {index: index, field:"storagebinid"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdata') ?>',
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
		width:'80px',
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
						url:'<?php echo Yii::app()->createUrl('common/storagebin/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						/*onBeforeLoad: function(param) {
							var row = $('#dg-grreturdetail').datagrid('getSelected');
							param.slocid = row.slocid;
						},*/
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:20},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:50},
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
		field:'itemnote',
		title:'<?php echo getCatalog('itemnote') 

?>',
                editor:'text',
		multiline:true,
		width:'200px',
		required:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
