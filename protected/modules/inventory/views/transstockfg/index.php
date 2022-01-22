<table id="dg-transstockfg" style="width:1200px;height:97%"></table>
<div id="tb-transstockfg">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addTS()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editTS()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvets()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelts()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdftransstock()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlstransstock()"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('transstockid')?></td>
<td><input class="easyui-textbox" id="transstockfg_search_transstockid" style="width:100px"></td>
<td><?php echo getCatalog('transstockno')?></td>
<td><input class="easyui-textbox" id="transstockfg_search_transstockno" style="width:100px"></td>
<td><?php echo getCatalog('productoutputno')?></td>
<td><input class="easyui-textbox" id="transstockfg_search_opno" style="width:100px"></td>
</tr>
<tr>
<td><?php echo getCatalog('slocfrom')?></td>
<td><input class="easyui-textbox" id="transstockfg_search_slocfrom" style="width:100px"></td>
<td><?php echo getCatalog('slocto')?></td>
<td><input class="easyui-textbox" id="transstockfg_search_slocto" style="width:100px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="transstockfg_search_headernote" style="width:100px"><a href="javascript:void(0)" title="Cari" id="transstock_search" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchtransstock()"></a></td>
</tr>
</table>
</div>
<div id="dlg-transstockfg" class="easyui-dialog" title="Transfer Gudang FG" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormTS();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-transstockfg').dialog('close');
			}
		},
	]	
	">
	<form id="ff-transstock-modif-fg" method="post" data-options="novalidate:true">
	<input type="hidden" name="transstockid" id="transstockid"/>
		<table cellpadding="5">
            <tr>
				<td><?php echo getCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"/></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('productoutputno')?></td>
				<td><select class="easyui-combogrid" name="productoutputid" id="productoutputid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'productoutputid',
								textField: 'productoutputno',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('production/productoutput/indexts',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								required:true,
                                onHidePanel: function(){
										jQuery.ajax({'url':'<?php echo $this->createUrl('transstockfg/generatedetail') ?>',
											'data':{
													'id':$('#productoutputid').combogrid('getValue'),
													'hid':$('#transstockid').val()
												},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#slocfromid').combogrid('setValue',data.slocfromid);
												$('#sloctoid').combogrid('setValue',data.sloctoid);
												$('#headernote').textbox('setValue',data.headernote);
												$('#dg-transstockdetfg').edatagrid('reload');				
											},
											'cache':false});
								},
								columns: [[
										{field:'productoutputid',title:'<?php echo getCatalog('productoutputid') ?>'},
										{field:'productoutputno',title:'<?php echo getCatalog('productoutputno') ?>'},
										{field:'productplanno',title:'<?php echo getCatalog('productplanno') ?>'},
										{field:'description',title:'<?php echo getCatalog('description') ?>'},
                                        {field:'productoutputdate',title:'<?php echo getCatalog('productoutputdate') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
                        <tr>
				<td><?php echo getCatalog('slocfrom')?></td>
				<td><select class="easyui-combogrid" name="slocfromid" id="slocfromid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'slocid',
								textField: 'sloccode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								required:true,
                columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo getCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
                        <tr>
				<td><?php echo getCatalog('slocto')?></td>
				<td><select class="easyui-combogrid" name="sloctoid" id="sloctoid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'slocid',
								textField: 'sloccode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/sloc/indextrx',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								required:true,
                columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo getCatalog('description') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true" style="width:300px;height:100px"/></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:100%;height:350px">
			<div title="Detail" style="padding:5px">
				<table id="dg-transstockdetfg"  style="width:100%;height:300px">
				</table>
				<div id="tb-transstockdet">
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-transstockdetfg').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-transstockdetfg').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-transstockdetfg').edatagrid('destroyRow')"></a>
				</div>
			</div>
                </div>
</div>

<script type="text/javascript">
$('#dg-transstockfg').edatagrid({
		singleSelect: false,
		toolbar:'#tb-transstockfg',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editTS(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-transstockfg"></table><table class="ddv-transstockdetfg"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvtransstockdet = $(this).datagrid('getRowDetail',index).find('table.ddv-transstockdetfg');
			ddvtransstockdet.datagrid({
				url:'<?php echo $this->createUrl('transstockfg/indexdetail',array('grid'=>true)) ?>?id='+row.transstockid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'800px', 	
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>'},
					{field:'daqty',title:'<?php echo getCatalog('daqty') ?>'},
					{field:'stok',title:'<?php echo getCatalog('stok') ?>'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
					{field:'rakasal',title:'<?php echo getCatalog('storagebinfrom') ?>'},
					{field:'raktujuan',title:'<?php echo getCatalog('storagebinto') ?>'},
					{field:'itemtext',title:'<?php echo getCatalog('itemtext') ?>',width:'300px'},
				]],
				onResize:function(){
						$('#dg-transstock').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-transstockfg').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-transstockfg').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('transstockfg/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-transstock').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'transstockid',
		editing: false,
		columns:[[
		{
field:'transstockid',
title:'<?php echo getCatalog('transstockid') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
					return value;
					}},
					{
field:'docdate',
title:'<?php echo getCatalog('docdate') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
                                        return value;
					}},
{
field:'transstockno',
title:'<?php echo getCatalog('transstockno') ?>',
sortable: true,
width:'130px',
formatter: function(value,row,index){
                                        return value;
					}},
					{
field:'productoutputid',
title:'<?php echo getCatalog('productoutputno') ?>',
sortable: true,
width:'130px',
formatter: function(value,row,index){
                                        return row.productoutputno;
					}},
{
field:'slocfromid',
title:'<?php echo getCatalog('slocfrom') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
                                        return row.slocfromcode;
					}},
{
field:'sloctoid',
title:'<?php echo getCatalog('slocto') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
                                        return row.sloctocode;
					}},
{
field:'headernote',
title:'<?php echo getCatalog('headernote') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
                                        return value;
					}},
{
field:'recordstatustransstock',
title:'<?php echo getCatalog('recordstatus') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return value;
					}},
		]]
});
$('#transstockfg_search_transstockid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstockfg_search_transstockno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstockfg_search_slocfrom').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstockfg_search_slocto').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstockfg_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstockfg_search_dano').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
function searchtransstock(){
	$('#dg-transstockfg').edatagrid('load',{
		transstockid:$('#transstockfg_search_transstockid').val(),
		transstockno:$('#transstockfg_search_transstockno').val(),
		slocfrom:$('#transstockfg_search_slocfrom').val(),
		slocto:$('#transstockfg_search_slocto').val(),
		headernote:$('#transstockfg_search_headernote').val(),
		dano:$('#transstockfg_search_dano').val(),
	});
}
function approvets() {
	var ss = [];
	var rows = $('#dg-transstockfg').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transstockfg/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-transstockfg').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelts() {
	var ss = [];
	var rows = $('#dg-transstockfg').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transstockfg/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-transstockfg').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdftransstock() {
	var ss = [];
	var rows = $('#dg-transstockfg').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	window.open('<?php echo $this->createUrl('transstockfg/downpdf') ?>?id='+ss);
}
function downxlstransstock()
{
	var ss = [];
	var rows = $('#dg-transstock').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	window.open('<?php echo $this->createUrl('transstockfg/downxls') ?>?id='+ss);
}
function addTS() {
		$('#dlg-transstockfg').dialog('open');
		$('#ff-transstock-modif-fg').form('clear');
		$('#ff-transstock-modif-fg').form('load','<?php echo $this->createUrl('transstockfg/GetData') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editTS($i) {
	var row = $('#dg-transstockfg').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appts') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-transstockfg').dialog('open');
		$('#ff-transstock-modif-fg').form('load',row);
		$('#dg-transstockfg').datagrid('load',{'id':row.transstockid});
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormTS(){
	$('#ff-transstock-modif-fg').form('submit',{
		url:'<?php echo $this->createUrl('transstockfg/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-transstockfg').datagrid('reload');
        $('#dlg-transstockfg').dialog('close');
			}
    }
	});	
};

function clearFormTS(){
		$('#ff-transstock-modif-fg').form('clear');
};

function cancelFormTS(){
		$('#dlg-transstockfg').dialog('close');
};

$('#ff-transstock-modif-fg').form({
	onLoadSuccess: function(data) {
			//$('#docdate').datebox('setValue', data.docdate);
		$('#dg-transstockdetfg').datagrid({
			queryParams: {
				id: $('#transstockid').val()
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

$('#dg-transstockdetfg').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	idField:'transstockdetid',
	editing: true,
	ctrlSelect:true,
	toolbar:'#tb-transstockdet',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('transstockfg/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('transstockfg/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('transstockfg/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('transstockfg/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-transstockdetfg').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeEdit: function(index,row){
			if (row.qty != undefined) {
			var value = row.qty;
			row.qty = value.replace(".", "");
			}
  	},
	onBeforeSave: function(index){
		var row = $('#dg-transstockdetfg').edatagrid('getSelected');
		if (row)
		{
			row.transstockid = $('#transstockid').val();
		}
	},
	columns:[[
	{
		field:'transstockid',
		title:'<?php echo getCatalog('transstockid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'transstockdetid',
		title:'<?php echo getCatalog('transstockdetid') ?>',
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
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
                        readonly:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo getCatalog('productid')?>',width:50},
							{field:'productname',title:'<?php echo getCatalog('productname')?>',width:200},
						]]
				}	
			},
			width:'350px',
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
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'unitofmeasureid',
		title:'<?php echo getCatalog('uom') ?>',
		sortable: true,
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						readonly:true,
						queryParams:{
							combo:true
						},
						required:true,
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'unitofmeasureid',title:'<?php echo getCatalog('unitofmeasureid')?>',width:50},
							{field:'uomcode',title:'<?php echo getCatalog('uomcode')?>',width:200},
						]]
				}	
			},
		width:'120px',
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
	{
		field:'storagebinid',
		title:'<?php echo getCatalog('storagebinfrom') ?>',
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
						onBeforeLoad: function(param) {
							param.slocid = $('#slocfromid').combogrid('getValue')
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:200},
						]]
				}	
			},
			width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.rakasal;
		}
	},
        {
		field:'storagebintoid',
		title:'<?php echo getCatalog('storagebinto') ?>',
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
						readonly:true,
						queryParams:{
							combo:true
						},
                        onBeforeLoad: function(param) {
                            param.slocid = $('#sloctoid').combogrid('getValue')
                        },
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:200},
						]]
				}	
			},
			width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.raktujuan;
		}
	},
	{
		field:'itemtext',
		title:'<?php echo getCatalog('itemtext') 

?>',
		editor:'text',
		width:'200px',
		multiline:true,
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	]]
});
</script>
