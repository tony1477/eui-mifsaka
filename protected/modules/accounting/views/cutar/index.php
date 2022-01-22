<table id="dg-cutar" style="width:100%;height:97%"></table>
<div id="tb-cutar">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addcutar()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editcutar()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvecutar()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelcutar()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfcutar()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formCutar" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileCutar" id="FileCutar" style="display:inline">
			<input type="submit" value="Upload" name="submitCutar" style="display:inline">
		</form>
	<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('cutarid')?></td>
<td><input class="easyui-textbox" id="cutar_search_cutarid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="cutar_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('cutarno')?></td>
<td><input class="easyui-textbox" id="cutar_search_cutarno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('ttnt')?></td>
<td><input class="easyui-textbox" id="cutar_search_docno" style="width:150px"></td>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="cutar_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('cbinno')?></td>
<td><input class="easyui-textbox" id="cutar_search_cbinno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="cutar_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchcutar()"></a></td>
</tr>
</table>
</div>

<div id="dlg-cutar" class="easyui-dialog" title="<?php echo GetCatalog('cutar')?>" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormcutar();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-cutar').dialog('close');
			}
		},
	]	
	">
	<form id="ff-cutar-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="cutarid" id="cutarid"></input>
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('companyname')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'companyid',
					required: true,
					textField: 'companyname',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
					method: 'get',
					mode: 'remote',
					columns: [[
						{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>',width:'100px'},
						{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:'200px'},
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
				<td><?php echo GetCatalog('ttnt')?></td>
				<td><select class="easyui-combogrid" id="ttntid" name="ttntid" style="width:250px" data-options="
								panelWidth: '500px',
								idField: 'ttntid',
								required: true,
								textField: 'docno',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('order/ttnt/index',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								queryParams:{
									ttntcutar:true,
								},                                
						onBeforeLoad: function(param) {
							param.companyid = $('#companyid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'<?php echo $this->createUrl('cutar/generatedetail') ?>',
								'data':{
									'id':$('#ttntid').combogrid('getValue'),
									'hid':$('#cutarid').val()
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#dg-cutarinv').edatagrid('reload');				
								},
								'cache':false});
						},
						columns: [[
							{field:'ttntid',title:'<?php echo GetCatalog('ttntid') ?>',width:'80px'},
							{field:'docno',title:'<?php echo GetCatalog('docno') ?>',width:'160px'},
							{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:'160px'},
						]],
						fitColumns: true
						">
				</select></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="INVOICE" style="padding:5px">
				<table id="dg-cutarinv"  style="width:100%">
				</table>
				<div id="tb-cutarinv">
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-cutarinv').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-cutarinv').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-cutarinv').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>
<script type="text/javascript">
$("#formCutar").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('accounting/cutar/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data);
			$('#dg-cutar').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#cutar_search_cutarid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcutar();
			}
		}
	})
});
$('#cutar_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcutar();
			}
		}
	})
});
$('#cutar_search_cutarno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcutar();
			}
		}
	})
});
$('#cutar_search_docno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcutar();
			}
		}
	})
});
$('#cutar_search_cbinno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcutar();
			}
		}
	})
});
$('#cutar_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcutar();
			}
		}
	})
});
$('#cutar_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcutar();
			}
		}
	})
});
$('#dg-cutar').edatagrid({
	singleSelect: false,
	toolbar:'#tb-cutar',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoRowHeight:true,
	onDblClickRow: function (index,row) {
		editcutar(index);
	},
	rowStyler: function(index,row){
		if (row.count >= 1){
			return 'background-color:blue;color:#fff;font-weight:bold;';
		}
	},
	view: detailview,
	detailFormatter:function(index,row){
			return '<div style="padding:2px"><table class="ddv-cutarinv"></table></div>';
	},
	onExpandRow: function(index,row){
		var ddvcutarinv = $(this).datagrid('getRowDetail',index).find('table.ddv-cutarinv');
		ddvcutarinv.datagrid({
			url:'<?php echo $this->createUrl('cutar/indexinvoice',array('grid'=>true)) ?>?id='+row.cutarid,
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:'',
			showFooter:true,
			title:'Detail Pelunasan - Invoice',
			height:'auto',
			pagination:true,
			width:'100%',
			columns:[[
				{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno') ?>',width:'100px'},
				{field:'saldoinvoice',title:'<?php echo GetCatalog('amount') ?>',align:'right',width:'130px'},
				{field:'invoicedate',title:'<?php echo GetCatalog('invoicedate') ?>',width:'80px'},
				{field:'cashamount',title:'<?php echo GetCatalog('cashamount') ?>',align:'right',width:'130px'},
				{field:'bankamount',title:'<?php echo GetCatalog('bankamount') ?>',align:'right',width:'130px'},
				{field:'discamount',title:'<?php echo GetCatalog('discamount') ?>',align:'right',width:'130px'},
				{field:'returnamount',title:'<?php echo GetCatalog('returnamount') ?>',align:'right',width:'130px'},
				{field:'notagirno',title:'<?php echo GetCatalog('notagirno') ?>',align:'right',width:'130px'},
				{field:'obamount',title:'<?php echo GetCatalog('obamount') ?>',align:'right',width:'130px'},
				{field:'saldo',title:'<?php echo GetCatalog('saldo') ?>',align:'right',width:'130px'},
				{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>',width:'100px'},
				{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right',width:'80px'},
				{field:'description',title:'<?php echo GetCatalog('description') ?>',width:'100px'},
			]],
			onResize:function(){
					$('#dg-cutar').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
					setTimeout(function(){
							$('#dg-cutar').datagrid('fixDetailRowHeight',index);
					},0);
			}
		});
		$('#dg-cutar').datagrid('fixDetailRowHeight',index);
	},
	url: '<?php echo $this->createUrl('cutar/index',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg);
		$('#dg-cutar').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg,'error');
		$('#dg-cutar').edatagrid('reload');
	},
	idField:'cutarid',
	editing: false,
	columns:[[
	{
		field:'cutarid',
		title:'<?php echo GetCatalog('cutarid') ?>',
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
		title:'<?php echo GetCatalog('companyname') ?>',
		sortable: true,
		width:'350px',
		formatter: function(value,row,index){
			return row.companyname;
	}},
	{
		field:'cutarno',
		title:'<?php echo GetCatalog('cutarno') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return row.cutarno;
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
		field:'cbinid',
		title:'<?php echo GetCatalog('cbinno') ?>',
		editor:'text',
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.cbinno;
	}},
	{
		field:'ttntid',
		title:'<?php echo GetCatalog('ttntno') ?>',
		editor:'text',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
			return row.docno;
	}},
	{
		field:'recordstatus',
		title:'<?php echo GetCatalog('recordstatus') ?>',
		align:'left',
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.recordstatusname;
	}},
	]]
});
function searchcutar(value){
	$('#dg-cutar').edatagrid('load',{
		cutarid:$('#cutar_search_cutarid').val(),
		companyid:$('#cutar_search_companyname').val(),
		ttntid:$('#cutar_search_docno').val(),
		docdate:$('#cutar_search_docdate').val(),
		cutarno:$('#cutar_search_cutarno').val(),
	});
}
function approvecutar() {
	var ss = [];
	var rows = $('#dg-cutar').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.cutarid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cutar/approve') ?>',
		'data':{'id':ss},
		'type':'post',
		'dataType':'json',
        statusCode: {
            200: function(data) {
                //console.log(data);
                //show('Message','Simpan Data Berhasil');
                $('#dg-cutar').edatagrid('reload'); 
            }
        },
		'success':function(data)
		{
            console.log(data);
			show('Message',data.pesan);
            $('#dg-cutar').edatagrid('reload');
		},
		'cache':false});
};
function cancelcutar() {
	var ss = [];
	var rows = $('#dg-cutar').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.cutarid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cutar/delete') ?>',
		'data':{'id':ss},
		'type':'post',
		'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cutar').edatagrid('reload');				
		} ,
		'cache':false});
};
function addcutar() {
	$('#dlg-cutar').dialog('open');
	$('#ff-cutar-modif').form('clear');
	$('#ff-cutar-modif').form('load','<?php echo $this->createUrl('cutar/getdata') ?>');
	$('#docdate').datebox({
		value: (new Date().toString('dd-MMM-yyyy'))
	});
};

function editcutar($i) {
	var row = $('#dg-cutar').datagrid('getSelected');
	if(row) {
		$('#dlg-cutar').dialog('open');
		$('#ff-cutar-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormcutar(){
	$('#ff-cutar-modif').form('submit',{
		url:'<?php echo $this->createUrl('cutar/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-cutar').datagrid('reload');
        $('#dlg-cutar').dialog('close');
			}
    }
	});	
};

function clearFormcutar(){
	$('#ff-cutar-modif').form('clear');
};

function cancelFormcutar(){
	$('#dlg-cutar').dialog('close');
};
$('#ff-cutar-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-cutar').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-cutarinv').datagrid({
			queryParams: {
				id: $('#cutarid').val()
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
function downpdfcutar() {
	var ss = [];
	var rows = $('#dg-cutar').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.cutarid);
	}
	window.open('<?php echo $this->createUrl('cutar/downpdf') ?>?id='+ss);
}
function downxlscutar() {
	var ss = [];
	var rows = $('#dg-cutar').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.cutarid);
	}
	window.open('<?php echo $this->createUrl('cutar/downxls') ?>?id='+ss);
}

$('#dg-cutarinv').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'cutarinvid',
	editing: true,
	toolbar:'#tb-cutarinv',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cutar/searchinvoice',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cutar/saveinvoice',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('cutar/saveinvoice',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('cutar/purgeinvoice',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-cutarinv').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-cutarinv').edatagrid('getSelected');
		if (row)
		{
			row.cutarid = $('#cutarid').val();
		}
	},
	columns:[[
	{
		field:'cutarid',
		title:'<?php echo GetCatalog('cutarid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'cutarinvid',
		title:'<?php echo GetCatalog('cutarinvid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'invoiceid',
		title:'<?php echo GetCatalog('invoiceno') ?>',
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.invoiceno;
		}
	},
	{
		field:'customername',
		title:'<?php echo GetCatalog('customer') ?>',
		sortable: true,
                width:'150px',
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'saldoinvoice',
		title:'<?php echo GetCatalog('amount') ?>',
		sortable: true,
		options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:'0',
		},
		width:'150px',
		formatter: function(value,row,index){
			return row.saldoinvoice;
		}		
	},
	{
		field:'invoicedate',
		title:'<?php echo GetCatalog('invoicedate') ?>',
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
							return row.invoicedate;
		}
	},
	{
		field:'cashamount',
		title:'<?php echo GetCatalog('cashamount') ?>',
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
		field:'bankamount',
		title:'<?php echo GetCatalog('bankamount') ?>',
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
		field:'discamount',
		title:'<?php echo GetCatalog('discamount') ?>',
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
		field:'returnamount',
		title:'<?php echo GetCatalog('returnamount') ?>',
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
		field:'notagirid',
		title:'<?php echo GetCatalog('notagirno') ?>',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'post',
					idField:'notagirid',
					textField:'notagirno',
					url:'<?php echo Yii::app()->createUrl('accounting/notagir/indexcutar',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					queryParams:{
						combo:true
					},
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
						{field:'notagirid',title:'<?php echo GetCatalog('notagirid')?>',width:'80px'},
						{field:'notagirno',title:'<?php echo GetCatalog('notagirno')?>',width:'150px'},
						{field:'sisa',title:'<?php echo GetCatalog('sisa')?>',width:'150px'},
						{field:'amount',title:'<?php echo GetCatalog('amount')?>',width:'150px'},
						{field:'valres',title:'<?php echo GetCatalog('valres')?>',width:'150px'},
					]]
			}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.notagirno;
		}
	},
	{
		field:'obamount',
		title:'<?php echo GetCatalog('obamount') ?>',
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
		field:'saldo',
		title:'<?php echo GetCatalog('saldo') ?>',
		sortable: true,
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
					panelWidth:'500px',
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
						{field:'currencyid',title:'<?php echo GetCatalog('currencyid')?>',width:'80px'},
						{field:'currencyname',title:'<?php echo GetCatalog('currencyname')?>',width:'150px'},
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
		field:'description',
		title:'<?php echo GetCatalog('description') ?>',
		width:'250px',		
		editor:'text',
		sortable: true,
		formatter: function(text,row,index){
			return row.description;
		}
	},
	]]
});
</script>