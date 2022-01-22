<table id="dg-invoice" style="width:1200px;height:97%"></table>
<div id="tb-invoice">
	<?php
	if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-invoice').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-invoice').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-invoice').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveinvoicear()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelinvoicear()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-invoice').edatagrid('destroyRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfinvoice()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formInvoice" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileInvoice" id="FileInvoice" style="display:inline">
			<input type="submit" value="Upload" name="submitInvoice" style="display:inline">
		</form>
	<?php }?>
	<table>
		<tr>
			<td><?php echo GetCatalog('invoiceid')?></td>
			<td><input class="easyui-textbox" id="invoice_search_invoiceid" style="width:150px"></td>
			<td><?php echo GetCatalog('invoicedate')?></td>
			<td><input class="easyui-textbox" id="invoice_search_invoicedate" style="width:150px"></td>
			<td><?php echo GetCatalog('invoiceno')?></td>
			<td><input class="easyui-textbox" id="invoice_search_invoiceno" style="width:150px"></td>
		</tr>
		<tr>
			<td><?php echo GetCatalog('gino')?></td>
			<td><input class="easyui-textbox" id="invoice_search_gino" style="width:150px"></td>
			<td><?php echo GetCatalog('company')?></td>
			<td><input class="easyui-textbox" id="invoice_search_company" style="width:150px"></td>
			<td><?php echo GetCatalog('customer')?></td>
			<td><input class="easyui-textbox" id="invoice_search_customer" style="width:150px"></td>
		</tr>
		<tr>
		<td><?php echo GetCatalog('headernote')?></td>
		<td><input class="easyui-textbox" id="invoice_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchinvoice()"></a></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
$("#formInvoice").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('accounting/invoicear/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-invoice').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#invoice_search_invoiceid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoice();
			}
		}
	})
});
$('#invoice_search_invoicedate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoice();
			}
		}
	})
});
$('#invoice_search_gino').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoice();
			}
		}
	})
});
$('#invoice_search_company').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoice();
			}
		}
	})
});
$('#invoice_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoice();
			}
		}
	})
});
$('#invoice_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoice();
			}
		}
	})
});
$('#invoice_search_invoiceno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoice();
			}
		}
	})
});
$('#dg-invoice').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-invoice',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
    url: '<?php echo $this->createUrl('invoicear/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('invoicear/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('invoicear/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('invoicear/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-invoice').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-invoicedetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvgidetail = $(this).datagrid('getRowDetail',index).find('table.ddv-invoicedetail');
			ddvgidetail.datagrid({
				url:'<?php echo $this->createUrl('invoicear/indexdetail',array('grid'=>true)) ?>?id='+row.giheaderid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo GetCatalog('pleasewait') ?>',
				height:'auto',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'productname',title:'<?php echo GetCatalog('productname') ?>',width:'400px'},
					{field:'qty',title:'<?php echo GetCatalog('qty') ?>',width:'80px',align:'right'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>',width:'100px'},
					{field:'sloccode',title:'<?php echo GetCatalog('sloc') ?>',width:'250px'},
					{field:'description',title:'<?php echo GetCatalog('storagebin') ?>',width:'200px'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>',width:'300px'},
				]],
				onResize:function(){
						$('#dg-giheader').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-giheader').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-giheader').datagrid('fixDetailRowHeight',index);
		},
		idField:'invoiceid',
		editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		rowStyler: function(index,row){
			if (row.warna == 1){
					return 'background-color:cyan;color:black;';
			}
		},
		columns:[[
		{
			field:'invoiceid',
			title:'<?php echo GetCatalog('invoiceid') ?>',
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
			field:'companyname',
			title:'<?php echo GetCatalog('company') ?>',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'invoicedate',
			title:'<?php echo GetCatalog('invoicedate') ?>',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'invoiceno',
			title:'<?php echo GetCatalog('invoiceno') ?>',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'giheaderid',
			title:'<?php echo GetCatalog('giheader') ?>',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'giheaderid',
					textField:'gino',
					url:'<?php echo Yii::app()->createUrl('inventory/giheader/indexinvoice',array('grid'=>true)) ?>',
					fitColumns:true,
					pagination:true,
					required:true,
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
						{field:'giheaderid',title:'<?php echo GetCatalog('giheaderid')?>'},
						{field:'gino',title:'<?php echo GetCatalog('gino')?>'},
						{field:'gidate',title:'<?php echo GetCatalog('gidate')?>'},
						{field:'sono',title:'<?php echo GetCatalog('sono')?>'},
						{field:'customername',title:'<?php echo GetCatalog('customer')?>'},
						{field:'companyname',title:'<?php echo GetCatalog('companyname')?>'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.gino;
		}},
		{
			field:'fullname',
			title:'<?php echo GetCatalog('customer') ?>',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'amount',
			title:'<?php echo GetCatalog('amount') ?>',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return '<div style="text-align:right">'+value+'</div>';
		}},
		{
			field:'payamount',
			title:'<?php echo GetCatalog('payamount') ?>',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return '<div style="text-align:right">'+value+'</div>';
		}},
		{
			field:'saldo',
			title:'<?php echo GetCatalog('saldo') ?>',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return '<div style="text-align:right">'+value+'</div>';
		}},	
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
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '<?php echo GetCatalog('pleasewait')?>',
					columns:[[
						{field:'currencyid',title:'<?php echo GetCatalog('currencyid')?>',width:'50px'},
						{field:'currencyname',title:'<?php echo GetCatalog('currencyname')?>',width:'150px'},
					]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},					
		{
			field:'currencyrate',
			title:'<?php echo GetCatalog('currencyrate') ?>',
			editor: {
				type: 'numberbox',
				precision:2,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'headernote',
			title:'<?php echo GetCatalog('headernote') ?>',
			editor:'text',
			width:'250px',
			multiline:true,
			required:true,
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'<?php echo GetCatalog('recordstatus') ?>',
			align:'center',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.recordstatusname;
		}},
	]]
});
function searchinvoice(value){
	$('#dg-invoice').edatagrid('load',{
		invoiceid:$('#invoice_search_invoiceid').val(),
		invoicedate:$('#invoice_search_invoicedate').val(),
		invoiceno:$('#invoice_search_invoiceno').val(),
		companyid:$('#invoice_search_company').val(),
		customer:$('#invoice_search_customer').val(),
		giheaderid:$('#invoice_search_gino').val(),
		headernote:$('#invoice_search_headernote').val()
	});
}
function approveinvoicear() {
	var ss = [];
	var rows = $('#dg-invoice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.invoiceid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('invoicear/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-invoice').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelinvoicear() {
	var ss = [];
	var rows = $('#dg-invoice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.invoiceid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('invoicear/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-invoice').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfinvoice() {
	var ss = [];
	var rows = $('#dg-invoice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.invoiceid);
	}
	window.open('<?php echo $this->createUrl('invoicear/downpdf') ?>?id='+ss);
}
function downxlsinvoice() {
	var ss = [];
	var rows = $('#dg-invoice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.invoiceid);
	}
	window.open('<?php echo $this->createUrl('invoicear/downxls')?>?id='+ss);
}
</script>