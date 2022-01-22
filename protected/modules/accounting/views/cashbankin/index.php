<table id="dg-cashbankin" style="width:1000px;height:400px">
</table>
<div id="tb-cashbankin">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addCashbankin()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editCashbankin()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvecashbankin()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelcashbankin()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfcashbankin()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchcashbankin" style="width:150px">
</div>

<div id="dlg-cashbankin" class="easyui-dialog" title="Cashbankin" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormCashbankin();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-cashbankin').dialog('close');
			}
		},
	]	
	">
	<form id="ff-cashbankin-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="cashbankarid" id="cashbankarid"></input>
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'companyid',
								required: true,
								textField: 'companyname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
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
								panelWidth: 500,
								idField: 'ttntid',
								required: true,
								textField: 'docno',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('order/ttnt/index',array('grid'=>true)) ?>',
								method: 'get',
								queryParams:{
									combo:true,
								},
								onHidePanel: function(){
									
										jQuery.ajax({'url':'<?php echo $this->createUrl('cashbankin/generatedetail') ?>',
											'data':{
																	'id':$('#ttntid').combogrid('getValue'),
																	'hid':$('#cashbankarid').val()
															},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#dg-cbarinv').edatagrid('reload');				
											},
											'cache':false});
									
								},
								columns: [[
										{field:'ttntid',title:'<?php echo GetCatalog('ttntid') ?>',width:80},
										{field:'docno',title:'<?php echo GetCatalog('docno') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="INVOICE" style="padding:5px">
				<table id="dg-cbarinv"  style="width:100%">
				</table>
				<div id="tb-cbarinv">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-cbarinv').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-cbarinv').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-cbarinv').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-cbarinv').edatagrid('destroyRow')"></a>
				</div>
			</div>
                        <div title="PAY" style="padding:5px">
				<table id="dg-cbarpay"  style="width:100%">
				</table>
				<div id="tb-cbarpay">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-cbarpay').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-cbarpay').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-cbarpay').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-cbarpay').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$('#dg-cashbankin').edatagrid({
		singleSelect: false,
		toolbar:'#tb-cashbankin',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editCashbankin(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-cbarinv"></table><table class="ddv-cbarpay"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvcbarinv = $(this).datagrid('getRowDetail',index).find('table.ddv-cbarinv');
			var ddvcbarpay = $(this).datagrid('getRowDetail',index).find('table.ddv-cbarpay');
			ddvcbarinv.datagrid({
				url:'<?php echo $this->createUrl('cashbankin/indexinvoice',array('grid'=>true)) ?>?id='+row.cashbankarid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				title:'Cashbank - Invoice',
				height:'auto',
				onSelect:function(index,row){
					ddvcbarpay.edatagrid('load',{
						cbarinvid: row.cbarinvid,
						cashbankarid: row.cashbankarid
					})
				},
				width:'600px',
				columns:[[
					{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno') ?>'},
					{field:'nilai',title:'<?php echo GetCatalog('amount') ?>'},
					{field:'invoicedate',title:'<?php echo GetCatalog('invoicedate') ?>'},
					{field:'payamount',title:'<?php echo GetCatalog('payamount') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>'},
					{field:'saldo',title:'<?php echo GetCatalog('saldo') ?>'},
				]],
				onResize:function(){
						$('#dg-cashbankin').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-cashbankin').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			ddvcbarpay.datagrid({
				url:'<?php echo $this->createUrl('cashbankin/indexpay',array('grid'=>true)) ?>?id='+row.cashbankarid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				title:'Cashbank - Payment',
				height:'auto',
				width:'600px',
				columns:[[
				{field:'cheqno',title:'<?php echo GetCatalog('cheqno') ?>'},
				{field:'tglterima',title:'<?php echo GetCatalog('tglterima') ?>'},
				{field:'tglcair',title:'<?php echo GetCatalog('tglcair') ?>'},
				{field:'amount',title:'<?php echo GetCatalog('amount') ?>'},
				{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
				{field:'currencyrate',title:'<?php echo GetCatalog('rate') ?>'},
				{field:'bankname',title:'<?php echo GetCatalog('bankname') ?>'},
				{field:'bankowner',title:'<?php echo GetCatalog('accountowner') ?>'},
				]],
				onResize:function(){
						$('#dg-cashbankin').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-cashbankin').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-cashbankin').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('cashbankin/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-cashbankin').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cashbankarid',
		editing: false,
		columns:[[
		{
field:'cashbankarid',
title:'<?php echo GetCatalog('cashbankarid') ?>',
sortable: true,
formatter: function(value,row,index){
					return value;
					}},
{
field:'companyid',
title:'<?php echo GetCatalog('companyname') ?>',
sortable: true,
formatter: function(value,row,index){
						return row.companyname;
					}},
{
field:'cashbankarno',
title:'<?php echo GetCatalog('cashbankarno') ?>',
sortable: true,
formatter: function(value,row,index){
						return row.cashbankarno;
					}},
{
field:'ttntid',
title:'<?php echo GetCatalog('ttntno') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return row.docno;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatuscashbankin',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'center',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchcashbankin(value){
	$('#dg-cashbankin').edatagrid('load',{
	cashbankarid:value,
        ttntid:value,
        docdate:value,
        recordstatus:value,
	});
}
function approvecashbankin() {
	var ss = [];
	var rows = $('#dg-cashbankin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankarid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cashbankin/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cashbankin').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelcashbankin() {
	var ss = [];
	var rows = $('#dg-cashbankin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankarid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cashbankin/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cashbankin').edatagrid('reload');				
		} ,
		'cache':false});
};
function addCashbankin() {
		$('#dlg-cashbankin').dialog('open');
		$('#ff-cashbankin-modif').form('clear');
		$('#ff-cashbankin-modif').form('load','<?php echo $this->createUrl('cashbankin/getdata') ?>');
};

function editCashbankin($i) {
	var row = $('#dg-cashbankin').datagrid('getSelected');
	if(row) {
		$('#dlg-cashbankin').dialog('open');
		$('#ff-cashbankin-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormCashbankin(){
	$('#ff-cashbankin-modif').form('submit',{
		url:'<?php echo $this->createUrl('cashbankin/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-cashbankin').datagrid('reload');
        $('#dlg-cashbankin').dialog('close');
			}
    }
	});	
};

function clearFormCashbankin(){
		$('#ff-cashbankin-modif').form('clear');
};

function cancelFormCashbankin(){
		$('#dlg-cashbankin').dialog('close');
};
$('#ff-cashbankin-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-cashbankin').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-cbarinv').datagrid({
			queryParams: {
				id: $('#cashbankarid').val()
			}
		});
                $('#dg-cbarpay').datagrid({
			queryParams: {
				id: $('#cashbankarid').val()
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
function downpdfcashbankin() {
	var ss = [];
	var rows = $('#dg-cashbankin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankarid);
	}
	window.open('<?php echo $this->createUrl('cashbankin/downpdf') ?>?id='+ss);
}
function downxlscashbankin() {
	var ss = [];
	var rows = $('#dg-cashbankin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankarid);
	}
	window.open('<?php echo $this->createUrl('cashbankin/downxls') ?>?id='+ss);
}

$('#dg-cbarinv').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'cbarinvid',
	editing: true,
	toolbar:'#tb-cbarinv',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cashbankin/searchinvoice',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cashbankin/saveinvoice',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('cashbankin/saveinvoice',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('cashbankin/purgeinvoice',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-cbarinv').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onSelect:function(index,row){
		$('#dg-cbarpay').edatagrid('load',{
			id: row.cashbankarid,
			cbarinvid: row.cbarinvid
		})
	},
	onBeforeSave: function(index){
		var ixs = $('#dg-cashbankin').edatagrid('getSelected');
		var row = $('#dg-cbarinv').edatagrid('getSelected');
		if (ixs)
		{
			row.cashbankarid = ixs.cashbankarid;
		}
	},
	onBeforeEdit: function(index,row){
			if (row.nilai != undefined) {
				var value = row.nilai;
				row.nilai = value.replace(".", "");
				var value = row.payamount;
				row.payamount = value.replace(".", "");
				var value = row.currencyrate;
				row.currencyrate = value.replace(".", "");
			}
  	},
        columns:[[
	{
		field:'cashbankarid',
		title:'<?php echo GetCatalog('cashbankarid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'cbarinvid',
		title:'<?php echo GetCatalog('cbarinvid') ?>',
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
		field:'nilai',
		title:'<?php echo GetCatalog('amount') ?>',
		sortable: true,
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
                width:'150px',
		formatter: function(value,row,index){
							return row.nilai;
		}
	},
        {
		field:'invoicedate',
		title:'<?php echo GetCatalog('invoicedate') ?>',
		sortable: true,
                editor:'datebox',
                width:'150px',
		formatter: function(value,row,index){
							return row.invoicedate;
		}
	},
        {
		field:'payamount',
		title:'<?php echo GetCatalog('payamount') ?>',
		sortable: true,
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
		field:'saldo',
		title:'<?php echo GetCatalog('saldo') ?>',
		sortable: true,
                width:'150px',
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});

$('#dg-cbarpay').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'cbarpayid',
	editing: true,
	toolbar:'#tb-cbarpay',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cashbankin/searchpay',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cashbankin/savepay',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('cashbankin/savepay',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('cashbankin/purgepay',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-cbarpay').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var cb = $('#dg-cashbankin').edatagrid('getSelected');
		var row = $('#dg-cbarpay').edatagrid('getSelected');
		var cbinv = $('#dg-cbarinv').edatagrid('getSelected');
		if (cb)
		{
			row.cashbankarid = $('#cashbankarid').val()
		}
		if (cbinv)
		{
			row.cbarinvid = cbinv.cbarinvid
		}
	},
        columns:[[
	{
		field:'cashbankarid',
		title:'<?php echo GetCatalog('cashbankarid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'cbarpayid',
		title:'<?php echo GetCatalog('cbarpayid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'cbarinvid',
		title:'<?php echo GetCatalog('cbarinvid') ?>',
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
						idField:'accountid',
						textField:'accountname',
						url:'<?php echo $this->createUrl('account/index',array('grid'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('accountname')?>'},
						]]
				}	
			},
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.accountname;
		}
	},
	{
		field:'cheqno',
		title:'<?php echo GetCatalog('cheqno') ?>',
                width:'150px',
                editor:'text',
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'tglterima',
		title:'<?php echo GetCatalog('tglterima') ?>',
                width:'150px',
                editor:'datebox',
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'tglcair',
		title:'<?php echo GetCatalog('tglcair') ?>',
                width:'150px',
                editor:'datebox',
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'amount',
		title:'<?php echo GetCatalog('amount') ?>',
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
		width:'100px',
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
		title:'<?php echo GetCatalog('ratevalue') ?>',
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
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
        {
		field:'bankname',
		title:'<?php echo GetCatalog('bankname') ?>',
		width:'50px',		
                editor:'text',
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
        {
		field:'bankowner',
		title:'<?php echo GetCatalog('accountowner') 

?>',
		width:'50px',		
                editor:'text',		
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
