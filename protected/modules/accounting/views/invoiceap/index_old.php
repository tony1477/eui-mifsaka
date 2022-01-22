<table id="dg-invoiceap" style="width:100%;height:97%"></table>
<div id="tb-invoiceap">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addInvoiceap()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editInvoiceap()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveinvoiceap()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelinvoiceap()"></a>
		<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfinvoiceap()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formInvoiceap" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileInvoiceap" id="FileInvoiceap" style="display:inline">
			<input type="submit" value="Upload" name="submitInvoiceap" style="display:inline">
		</form>
	<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('invoiceapid')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_invoiceapid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('invoiceno')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_invoiceno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('pono')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_pono" style="width:150px"></td>
<td><?php echo GetCatalog('invoicedate')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_invoicedate" style="width:150px"></td>
<td><?php echo GetCatalog('grno')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_grno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('supplier')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_supplier" style="width:150px"></td>
<td><?php echo GetCatalog('paymentmethod')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_paymentmethod" style="width:150px"></td>
<td><?php echo GetCatalog('tax')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_taxid" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="invoiceap_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchinvoiceap()"></a></td>
</tr>
</table>
</div>

<div id="dlg-invoiceap" class="easyui-dialog" title="Account Payable" style="width:auto;height:500px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormInvoiceap();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-invoiceap').dialog('close');
			}
		},
	]	
	">
	<form id="ff-invoiceap-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="invoiceapid" id="invoiceapid"/>
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
					panelWidth: '500px',
					required: true,
					idField: 'companyid',
					textField: 'companyname',
					pagination:true,
					mode:'remote',
					url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
					method: 'get',
					columns: [[
							{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>',width:'80px'},
							{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:'200px'},
					]],
					fitColumns: true">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('invoicedate')?></td>
				<td><input class="easyui-datebox" type="text" id="invoicedate" name="invoicedate" data-options="formatter:dateformatter,required:true,parser:dateparser"/></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('pono')?></td>
				<td><select class="easyui-combogrid" name="poheaderid" id="poheaderid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'poheaderid',
					textField: 'pono',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true)) ?>',
					method: 'get',
					required:true,
					mode:'remote',
					queryParams: {
						invpo:true
					},
					onBeforeLoad: function(param) {
					 param.companyid = $('#companyid').combogrid('getValue');
					},
					columns: [[
						{field:'poheaderid',title:'<?php echo GetCatalog('poheaderid') ?>',width:'80px'},
						{field:'pono',title:'<?php echo GetCatalog('pono') ?>',width:'80px'},
						{field:'docdate',title:'<?php echo GetCatalog('docdate') ?>',width:'120px'},
						{field:'fullname',title:'<?php echo GetCatalog('supplier') ?>',width:'200px'},
						{field:'headernote',title:'<?php echo GetCatalog('headernote') ?>',width:'200px'},
						{field:'companyname',title:'<?php echo GetCatalog('company') ?>',width:'250px'},
					]],
					fitColumns: true">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('grno')?></td>
				<td><select class="easyui-combogrid" name="grheaderid" id="grheaderid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'grheaderid',
					textField: 'grno',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('inventory/grheader/index',array('grid'=>true)) ?>',
					method: 'get',
					mode:'remote',
					queryParams: {
						pogr:true
					},
					onBeforeLoad: function(param) {
						param.companyid = $('#companyid').combogrid('getValue');
						param.poheaderid = $('#poheaderid').combogrid('getValue');
					},
					onHidePanel: function(){
						var g = $('#grheaderid').combogrid('grid');	
						var r = g.datagrid('getSelected');	// get the selected row
						var grheaderid = r.grheaderid;
						jQuery.ajax({'url':'<?php echo $this->createUrl('invoiceap/generatedetail') ?>',
							'data':{
									'hid':$('#invoiceapid').val(),
									'grheaderid':grheaderid,
									'pogr':true,
								},
								'type':'post',
								'dataType':'json',
							'success':function(data)
							{
								$('#addressbookid').combogrid('setValue',data.addressbookid);
								$('#paymentmethodid').combogrid('setValue',data.paymentmethodid);
								$('#taxid').combogrid('setValue',data.taxid);
								$('#amount').numberbox('setValue',data.amount);
								$('#dg-invoiceapmat').edatagrid('reload');				
							},
							'cache':false});
					},
					columns: [[
						{field:'grheaderid',title:'<?php echo GetCatalog('grheaderid') ?>',width:'80px'},
						{field:'grno',title:'<?php echo GetCatalog('grno') ?>',width:'80px'},
						{field:'grdate',title:'<?php echo GetCatalog('grdate') ?>',width:'120px'},
						{field:'pono',title:'<?php echo GetCatalog('pono') ?>',width:'120px'},
						{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:'200px'},
					]],
					fitColumns: true">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('invoiceno')?></td>
				<td><input class="easyui-textbox" id="invoiceno" name="invoiceno" data-options="required:true" /></td>
			</tr>
      <tr>
				<td><?php echo GetCatalog('supplier')?></td>
				<td><select class="easyui-combogrid" id="addressbookid" name="addressbookid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'addressbookid',
					required: true,
					textField: 'fullname',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('common/supplier/index',array('grid'=>true)) ?>',
					method: 'get',
					readonly:true,
					queryParams:{
						combo:true,
					},
					columns: [[
						{field:'addressbookid',title:'<?php echo GetCatalog('addressbookid') ?>',width:'80px'},
						{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>',width:'120px'},
					]],
					fitColumns: true">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('amount')?></td>
				<td><input class="easyui-numberbox" type="text" name="amount" id="amount" data-options="
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:'0',
					required:true"></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('currency')?></td>
				<td><select class="easyui-combogrid" id="currencyid" name="currencyid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'currencyid',
					required: true,
					textField: 'currencyname',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('admin/currency/index',array('grid'=>true)) ?>',
					method: 'get',
					queryParams:{
						combo:true,
					},
					columns: [[
						{field:'currencyid',title:'<?php echo GetCatalog('currencyid') ?>',width:'80px'},
						{field:'currencyname',title:'<?php echo GetCatalog('currencyname') ?>',width:'120px'},
					]],
					fitColumns: true">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('rate')?></td>
				<td><input class="easyui-numberbox" type="text" id="currencyrate" name="currencyrate" value="1" data-options="
					precision:2,
					decimalSeparator:',',
					groupSeparator:'.',
					value:1,
					required:true"></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('paymentmethod')?></td>
				<td><select class="easyui-combogrid" id="paymentmethodid" name="paymentmethodid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'paymentmethodid',
					required: true,
					textField: 'paycode',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true)) ?>',
					method: 'get',
					readonly:true,
					queryParams:{
						combo:true,
					},
					columns: [[
						{field:'paymentmethodid',title:'<?php echo GetCatalog('paymentmethodid') ?>',width:'80px'},
						{field:'paycode',title:'<?php echo GetCatalog('paycode') ?>',width:'80px'},
						{field:'paydays',title:'<?php echo GetCatalog('paydays') ?>',width:'80px'},
						{field:'paymentname',title:'<?php echo GetCatalog('paymentname') ?>',width:'200px'},
					]],
					fitColumns: true">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('tax')?></td>
				<td><select class="easyui-combogrid" id="taxid" name="taxid" style="width:250px" data-options="
					panelWidth: '500px',
					idField: 'taxid',
					required: true,
					textField: 'taxcode',
					pagination:true,
					url: '<?php echo Yii::app()->createUrl('accounting/tax/index',array('grid'=>true)) ?>',
					method: 'get',
					readonly:true,
					queryParams:{
						combo:true,
					},
					columns: [[
						{field:'taxid',title:'<?php echo GetCatalog('taxid') ?>',width:'80px'},
						{field:'taxcode',title:'<?php echo GetCatalog('taxcode') ?>',width:'80px'},
						{field:'taxvalue',title:'<?php echo GetCatalog('taxvalue') ?>',width:'120px'},
						{field:'description',title:'<?php echo GetCatalog('description') ?>',width:'200px'},
					]],
					fitColumns: true">
				</select></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('notax')?></td>
				<td><input class="easyui-textbox" id="taxno" name="taxno" /></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('taxdate')?></td>
				<td><input class="easyui-datebox" type="text" id="taxdate" name="taxdate" data-options="formatter:dateformatter,parser:dateparser"/></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('receiptdate')?></td>
				<td><input class="easyui-datebox" type="text" id="receiptdate" name="receiptdate" data-options="formatter:dateformatter,parser:dateparser"/></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="Material" style="padding:5px">
				<table id="dg-invoiceapmat"  style="width:100%;height:200px">
				</table>
				<div id="tb-invoiceapmat">
				</div>
			</div>
			<div title="Jurnal" style="padding:5px">
				<table id="dg-invoiceapjurnal"  style="width:100%;height:200px">
				</table>
				<div id="tb-invoiceapjurnal">
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-invoiceapjurnal').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-invoiceapjurnal').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-invoiceapjurnal').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-invoiceapjurnal').edatagrid('destroyRow')"></a>
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
$("#formInvoiceap").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('accounting/invoiceap/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-invoiceap').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
    
$('#invoiceap_search_invoiceapid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_invoiceno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_pono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_invoicedate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_grno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_supplier').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_paymentmethod').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#invoiceap_search_paymentmethod').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchinvoiceap();
			}
		}
	})
});
$('#dg-invoiceap').edatagrid({
	singleSelect: false,
	toolbar:'#tb-invoiceap',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoRowHeight:true,
	onDblClickRow: function (index,row) {
		editInvoiceap(index);
	},
	rowStyler: function(index,row){
		if (row.count >= 1){
				return 'background-color:blue;color:#fff;font-weight:bold;';
		}
	},
	view: detailview,
	detailFormatter:function(index,row){
		return '<div style="padding:2px"><table class="ddv-invoiceapmat"></table><table class="ddv-invoiceapjurnal"></table></div>';
	},
	onExpandRow: function(index,row){
		var ddvinvoiceapmat = $(this).datagrid('getRowDetail',index).find('table.ddv-invoiceapmat');
		var ddvinvoiceapjurnal = $(this).datagrid('getRowDetail',index).find('table.ddv-invoiceapjurnal');
		ddvinvoiceapmat.datagrid({
			url:'<?php echo $this->createUrl('invoiceap/indexmaterial',array('grid'=>true)) ?>?id='+row.invoiceapid,
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:'',
			height:'auto',
			width:'800px',
			pagination:true,
			showFooter:true,
			columns:[[
				{field:'productname',title:'<?php echo GetCatalog('productname') ?>',width:'250px'},
				{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>',width:'120px'},
				{field:'poqty',title:'<?php echo GetCatalog('poqty') ?>',align:'right',width:'80px'},
				{field:'grqty',title:'<?php echo GetCatalog('grqty') ?>',align:'right',width:'80px'},
					{field:'price',title:'<?php echo GetCatalog('price') ?>',align:'right'},
					{field:'jumlah',title:'<?php echo GetCatalog('jumlah') ?>',align:'right'},
				{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>',width:'200px'},
			]],
			onResize:function(){
				$('#dg-invoiceap').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
				setTimeout(function(){
						$('#dg-invoiceap').datagrid('fixDetailRowHeight',index);
				},0);
			}
		});
		ddvinvoiceapjurnal.datagrid({
			url:'<?php echo $this->createUrl('invoiceap/indexjurnal',array('grid'=>true)) ?>?id='+row.invoiceapid,
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:'',
			height:'auto',
			width:'800px',
			pagination:true,
			showFooter:true,
			columns:[[
				{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',width:'200px'},
				{field:'debet',title:'<?php echo GetCatalog('debet') ?>',align:'right',width:'120px'},
				{field:'credit',title:'<?php echo GetCatalog('credit') ?>',align:'right',width:'120px'},
				{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>',width:'150px'},
				{field:'currencyrate',title:'<?php echo GetCatalog('rate') ?>',align:'right',width:'80px'},
				{field:'description',title:'<?php echo GetCatalog('description') ?>',width:'200px'},
			]],
			onResize:function(){
				$('#dg-invoiceap').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
				setTimeout(function(){
					$('#dg-invoiceap').datagrid('fixDetailRowHeight',index);
				},0);
			}
		});
		$('#dg-invoiceap').datagrid('fixDetailRowHeight',index);
	},
	url: '<?php echo $this->createUrl('invoiceap/index',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg);
		$('#dg-invoiceap').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg);
	},
	idField:'invoiceapid',
	editing: false,
	columns:[[
	{
		field:'invoiceapid',
		title:'<?php echo GetCatalog('invoiceapid') ?>',
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
		width:'320px',
		formatter: function(value,row,index){
			return row.companyname;
	}},
	{
		field:'invoiceno',
		title:'<?php echo GetCatalog('invoiceno') ?>',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'invoicedate',
		title:'<?php echo GetCatalog('invoicedate') ?>',
		sortable: true,
		width:'80px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'poheaderid',
		title:'<?php echo GetCatalog('pono') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return row.pono;
	}},
	{
		field:'grheaderid',
		title:'<?php echo GetCatalog('grno') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return row.grno;
	}},
	{
		field:'addressbookid',
		title:'<?php echo GetCatalog('supplier') ?>',
		sortable: true,
		width:'250px',
		formatter: function(value,row,index){
			return row.supplier;
	}},
	{
		field:'amount',
		title:'<?php echo GetCatalog('amount') ?>',
		width:'110px',
		sortable: true,
		formatter: function(value,row,index){
			return '<div style="text-align:right">'+value+'</div>';
	}},
	{
		field:'currencyid',
		title:'<?php echo GetCatalog('currency') ?>',
		sortable: true,
		width:'70px',
		formatter: function(value,row,index){
			return row.currencyname;
	}},
	{
		field:'currencyrate',
		title:'<?php echo GetCatalog('ratevalue') ?>',
		sortable: true,
		width:'60px',
		formatter: function(value,row,index){
			return '<div style="text-align:right">'+value+'</div>';
	}},
	{
		field:'paymentmethoid',
		title:'<?php echo GetCatalog('paymentmethod') ?>',
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
			return row.paycode;
	}},
	{
		field:'taxid',
		title:'<?php echo GetCatalog('tax') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return row.taxcode;
	}},
	{
		field:'taxno',
		title:'<?php echo GetCatalog('notax') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'taxdate',
		title:'<?php echo GetCatalog('taxdate') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'receiptdate',
		title:'<?php echo GetCatalog('receiptdate') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'recordstatus',
		title:'<?php echo GetCatalog('recordstatus') ?>',
		width:'120px',
		sortable: true,
		formatter: function(value,row,index){
			return row.recordstatusinvoiceap;
	}},
	]]
});

function searchinvoiceap(value){
	$('#dg-invoiceap').edatagrid('load',{
		invoiceapid:$('#invoiceap_search_invoiceapid').val(),
		invoiceno:$('#invoiceap_search_invoiceno').val(),
		invoicedate:$('#invoiceap_search_invoicedate').val(),
		poheaderid:$('#invoiceap_search_pono').val(),
		addressbookid:$('#invoiceap_search_supplier').val(),
		paymentmethoid:$('#invoiceap_search_paymentmethod').val(),
		companyid:$('#invoiceap_search_companyname').val(),
		taxid:$('#invoiceap_search_taxid').val(),
		grheaderid:$('#invoiceap_search_grno').val(),
	});
}
function approveinvoiceap() {
	var ss = [];
	var rows = $('#dg-invoiceap').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.invoiceapid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('invoiceap/approve') ?>',
		'data':{'id':ss},
		'type':'post',
		'dataType':'json',
		'success':function(data) {
			show('Message',data.message);
			$('#dg-invoiceap').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelinvoiceap() {
	var ss = [];
	var rows = $('#dg-invoiceap').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.invoiceapid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('invoiceap/delete') ?>',
		'data':{'id':ss},
		'type':'post',
		'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-invoiceap').edatagrid('reload');				
		} ,
		'cache':false});
};
function addInvoiceap() {
	$('#dlg-invoiceap').dialog('open');
	$('#ff-invoiceap-modif').form('clear');
	$('#ff-invoiceap-modif').form('load','<?php echo $this->createUrl('invoiceap/getdata') ?>');
	$('#invoicedate').datebox({
			value: (new Date().toString('dd-MM-yyyy'))
	});
	$('#currencyid').combogrid({
			value: 40
	});
	$('#currencyrate').numberbox({
			value: 1
	});
	$('#amount').numberbox({
			value: 0
	});
	$('#taxdate').datebox({
			value: (new Date().toString('dd-MM-yyyy'))
	});
	$('#receiptdate').datebox({
			value: (new Date().toString('dd-MM-yyyy'))
	});
};

function editInvoiceap($i) {
	var row = $('#dg-invoiceap').datagrid('getSelected');
	if(row) {
		$('#dlg-invoiceap').dialog('open');
		$('#ff-invoiceap-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormInvoiceap(){
	$('#ff-invoiceap-modif').form('submit',{
		url:'<?php echo $this->createUrl('invoiceap/save') ?>',
		onSubmit:function(){
			return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-invoiceap').datagrid('reload');
        $('#dlg-invoiceap').dialog('close');
			}
    }
	});	
};
function clearFormInvoiceap(){
		$('#ff-invoiceap-modif').form('clear');
};
function cancelFormInvoiceap(){
		$('#dlg-invoiceap').dialog('close');
};
$('#ff-invoiceap-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-invoiceap').datagrid('getSelected');
		if(row) {
			$('#invoicedate').datebox('setValue', data.invoicedate);
			$('#currencyid').combogrid('setValue', data.currencyid);
			$('#currencyrate').numberbox('setValue', data.currencyrate);
			$('#taxdate').datebox('setValue', data.taxdate);
			$('#receiptdate').datebox('setValue', data.receiptdate);
		}
		$('#dg-invoiceapmat').datagrid({
			queryParams: {
				id: $('#invoiceapid').val()
			}
		});
		$('#dg-invoiceapjurnal').datagrid({
			queryParams: {
				id: $('#invoiceapid').val()
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
function downpdfinvoiceap() {
	var ss = [];
	var rows = $('#dg-invoiceap').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.invoiceapid);
	}
	window.open('<?php echo $this->createUrl('invoiceap/downpdf') ?>?id='+ss);
}
function downxlsinvoiceap() {
	var ss = [];
	var rows = $('#dg-invoiceap').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.invoiceapid);
	}
	window.open('<?php echo $this->createUrl('invoiceap/downxls') ?>?id='+ss);
}
$('#dg-invoiceapmat').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'invoiceapmatid',
	editing: false,
	toolbar:'#tb-invoiceapmat',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('invoiceap/searchmaterial',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('invoiceap/savematerial',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('invoiceap/savematerial',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('invoiceap/purgematerial',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-invoiceapmat').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var ixs = $('#dg-invoiceap').edatagrid('getSelected');
		var row = $('#dg-invoiceapmat').edatagrid('getSelected');
		if (ixs)
		{
			row.invoiceapid = ixs.invoiceapid;
		}
	},
	columns:[[
	{
		field:'invoiceapid',
		title:'<?php echo GetCatalog('invoiceapid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'invoiceapmatid',
		title:'<?php echo GetCatalog('invoiceapmatid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productid',
		title:'<?php echo GetCatalog('product') ?>',
		sortable: true,
		width:'250px',
		formatter: function(value,row,index){
							return row.productname;
		}
	},
	{
		field:'uomid',
		title:'<?php echo GetCatalog('uom') ?>',
		sortable: true,
    width:'100px',
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
	{
		field:'poqty',
		title:'<?php echo GetCatalog('poqty') ?>',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'grqty',
		title:'<?php echo GetCatalog('grqty') ?>',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	}
	]]
});
$('#dg-invoiceapjurnal').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'invoiceapjurnalid',
	editing: true,
	toolbar:'#tb-invoiceapjurnal',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('invoiceap/searchjurnal',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('invoiceap/savejurnal',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('invoiceap/savejurnal',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('invoiceap/purgejurnal',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-invoiceapjurnal').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.message);
	},
	onBeforeEdit: function(index,row){
		if (row.debet != undefined) {
			var value = row.debet;
			row.debet = value.replace(".", "");
			var value = row.credit;
			row.credit = value.replace(".", "");
			var value = row.currencyrate;
			row.currencyrate = value.replace(".", "");
		}
	},
	onBeforeSave: function(index){
		var row = $('#dg-invoiceapjurnal').edatagrid('getSelected');
		if (row)
		{
			row.invoiceapid = $('#invoiceapid').val();
		}
	},
	columns:[[
	{
		field:'invoiceapid',
		title:'<?php echo GetCatalog('invoiceapid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'invoiceapjurnalid',
		title:'<?php echo GetCatalog('invoiceapjurnalid') ?>',
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
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'accountid',
				textField:'accountname',
				url:'<?php echo $this->createUrl('account/index',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				queryParams:{
					trxcom:true
				},
				onBeforeLoad: function(param) {
					 param.companyid = $('#companyid').combogrid('getValue');
				},
				loadMsg: '<?php echo GetCatalog('pleasewait')?>',
				columns:[[
					{field:'accountid',title:'<?php echo GetCatalog('accountid')?>',width:'80px'},
					{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>',width:'120px'},
					{field:'accountname',title:'<?php echo GetCatalog('accountname')?>',width:'150px'},
					{field:'companyname',title:'<?php echo GetCatalog('company')?>',width:'200px'},
				]]
			}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accountname;
		}
	},
	{
		field:'debet',
		title:'<?php echo GetCatalog('debet') ?>',
		width:'100px',
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
				precision:4,
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
				required:true,
				loadMsg: '<?php echo GetCatalog('pleasewait')?>',
				columns:[[
					{field:'currencyid',title:'<?php echo GetCatalog('currencyid')?>',width:'80px'},
					{field:'currencyname',title:'<?php echo GetCatalog('currencyname')?>',width:'200px'},
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