<table id="dg-notagir" style="width:100%;height:97%"></table>
<div id="tb-notagir">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addNotagir()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editNotagir()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvenotagir()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelnotagir()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfnotagir()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('notagirid')?></td>
<td><input class="easyui-textbox" id="notagir_search_notagirid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="notagir_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('notagirno')?></td>
<td><input class="easyui-textbox" id="notagir_search_notagirno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('gireturno')?></td>
<td><input class="easyui-textbox" id="notagir_search_gireturno" style="width:150px"></td>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="notagir_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('customer')?></td>
<td><input class="easyui-textbox" id="notagir_search_customer" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="notagir_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchnotagir()"></a></td>
</tr>
</table>
</div>

<div id="dlg-notagir" class="easyui-dialog" title="Nota Retur Penjualan" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormNotagir();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-notagir').dialog('close');
			}
		},
	]	
	">
	<form id="ff-notagir-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="notagirid" id="notagirid"></input>
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
				<td><?php echo GetCatalog('gireturno')?></td>
				<td><select class="easyui-combogrid" id="gireturid" name="gireturid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'gireturid',
								required: true,
								textField: 'gireturno',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('inventory/giretur/index',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								queryParams:{
									notagir:true,
								},
								onBeforeLoad: function(param) {
                                    param.companyid = $('#companyid').combogrid('getValue');
						        },
                onHidePanel: function(){
										jQuery.ajax({'url':'<?php echo $this->createUrl('notagir/generatedetail') ?>',
											'data':{
															'id':$('#gireturid').combogrid('getValue'),
															'hid':$('#notagirid').val()
															},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
																
											},
											'cache':false});
											
											$('#dg-notagirpro').datagrid({
										queryParams: {
											id: $('#notagirid').val()

									}
									});
									
								},
								columns: [[
										{field:'gireturid',title:'<?php echo GetCatalog('gireturid') ?>',width:20},
										{field:'gireturno',title:'<?php echo GetCatalog('gireturno') ?>',width:60},
										{field:'customername',title:'<?php echo GetCatalog('customer') ?>',width:60},
										{field:'companyname',title:'<?php echo GetCatalog('company') ?>',width:60},
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
			<table id="dg-notagirpro"  style="width:100%">
			</table>
			<div id="tb-notagirpro">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-notagirpro').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-notagirpro').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-notagirpro').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-notagirpro').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#notagir_search_notagirid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagir();
			}
		}
	})
});
$('#notagir_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagir();
			}
		}
	})
});
$('#notagir_search_notagirno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagir();
			}
		}
	})
});
$('#notagir_search_gireturno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagir();
			}
		}
	})
});
$('#notagir_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagir();
			}
		}
	})
});
$('#notagir_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagir();
			}
		}
	})
});
$('#notagir_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchnotagir();
			}
		}
	})
});
$('#dg-notagir').edatagrid({	
		singleSelect: false,
		toolbar:'#tb-notagir',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editNotagir(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
                detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-notagirpro"></table><table class="ddv-notagiracc"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvnotagirpro = $(this).datagrid('getRowDetail',index).find('table.ddv-notagirpro');
			ddvnotagirpro.datagrid({
				url:'<?php echo $this->createUrl('notagir/indexproduct',array('grid'=>true)) ?>?id='+row.notagirid,
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
						$('#dg-notagir').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-notagir').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			ddvrepnotagiracc.datagrid({
				url:'<?php echo $this->createUrl('notagir/indexakun',array('grid'=>true)) ?>?id='+row.notagirid,
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
						$('#dg-repnotagir').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repnotagir').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-notagir').datagrid('fixDetailRowHeight',index);
                },
                url: '<?php echo $this->createUrl('notagir/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-notagir').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'notagirid',
		editing: false,
		columns:[[
{
field:'notagirid',
title:'<?php echo GetCatalog('notagirid') ?>',
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
field:'notagirno',
title:'<?php echo GetCatalog('notagirno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},{
field:'addressbookid',
title:'<?php echo GetCatalog('customer') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.fullname;
					}},
{
field:'gireturid',
title:'<?php echo GetCatalog('gireturno') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.gireturno;
					}},
					{
field:'giheaderid',
title:'<?php echo GetCatalog('giheader') ?>',
sortable: true,
width:'180px',
formatter: function(value,row,index){
						return row.gino;
					}},
										{
field:'soheaderid',
title:'<?php echo GetCatalog('soheader') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.sono;
					}},
{
field:'recordstatusnotagir',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'200px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchnotagir(value){
	$('#dg-notagir').edatagrid('load',{
	notagirid:$('#notagir_search_notagirid').val(),    
        docdate:$('#notagir_search_docdate').val(),
        gireturid:$('#notagir_search_gireturno').val(),
        companyid:$('#notagir_search_companyname').val(),
        customer:$('#notagir_search_customer').val(),
        notagirno:$('#notagir_search_notagirno').val(),
	});
}
function approvenotagir() {
	var ss = [];
	var rows = $('#dg-notagir').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.notagirid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('notagir/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-notagir').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelnotagir() {
	var ss = [];
	var rows = $('#dg-notagir').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.notagirid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('notagir/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-notagir').edatagrid('reload');				
		},
		'cache':false});
};
function addNotagir() {
		$('#dlg-notagir').dialog('open');
		$('#ff-notagir-modif').form('clear');
		$('#ff-notagir-modif').form('load','<?php echo $this->createUrl('notagir/getdata') ?>');
};

function editNotagir($i) {
	var row = $('#dg-notagir').datagrid('getSelected');
	if(row) {
		$('#dlg-notagir').dialog('open');
		$('#ff-notagir-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormNotagir(){
	$('#ff-notagir-modif').form('submit',{
		url:'<?php echo $this->createUrl('notagir/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
                        $('#dg-notagir').datagrid('reload');
                        $('#dlg-notagir').dialog('close');
                        }
                }
	});	
};
function clearFormNotagir(){
		$('#ff-notagir-modif').form('clear');
};

function cancelFormNotagir(){
		$('#dlg-notagir').dialog('close');
};
$('#ff-notagir-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-notagir').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-notagirpro').datagrid({
			queryParams: {
				id: $('#notagirid').val()
			}
		});
                $('#dg-notagiracc').datagrid({
			queryParams: {
				id: $('#notagirid').val()
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
function downpdfnotagir() {
	var ss = [];
	var rows = $('#dg-notagir').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.notagirid);
	}
	window.open('<?php echo $this->createUrl('notagir/downpdf') ?>?id='+ss);
}

$('#dg-notagirpro').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'notagirproid',
	editing: true,
	toolbar:'#tb-notagirpro',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('notagir/searchproduct',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('notagir/saveproduct',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('notagir/saveproduct',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('notagir/purgeproduct',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-notagirpro').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var ixs = $('#dg-notagir').edatagrid('getSelected');
		var row = $('#dg-notagirpro').edatagrid('getSelected');
		if (ixs)
		{
			row.notagirid = ixs.notagirid;
		}
	},
        columns:[[
	{
		field:'notagirid',
		title:'<?php echo GetCatalog('notagirid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'notagirproid',
		title:'<?php echo GetCatalog('notagirproid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'productid',
		title:'<?php echo GetCatalog('productname') ?>',
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.productname;
		}
	},
        {
		field:'qty',
		title:'<?php echo GetCatalog('qty') ?>',
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
				precision:6,
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
		title:'<?php echo GetCatalog('total') 

?>',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return row.total;
		}
	},
	]]
});
</script>
