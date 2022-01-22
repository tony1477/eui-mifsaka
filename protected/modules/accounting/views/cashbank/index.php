<table id="dg-cashbank" style="width:1200px;height:400px">
</table>
<div id="tb-cashbank">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addcashbank()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editcashbank()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvecashbank()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelcashbank()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfcashbank()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchcashbank" style="width:150px">
</div>

<div id="dlg-cashbank" class="easyui-dialog" title="cashbank" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormcashbank();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-cashbank').dialog('close');
			}
		},
	]	
	">
	<form id="ff-cashbank-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="cashbankid" id="cashbankid"></input>
		<table cellpadding="5">
      <tr>
				<td><?php echo GetCatalog('companyname')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'companyid',
								required: true,
								textField: 'companyname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
                columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>',width:80},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>',width:120},
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
				<td><?php echo GetCatalog('receiptno')?></td>
				<td><input class="easyui-textbox" name="receiptno"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('isin')?></td>
				<td><input id="isin" type="checkbox" name="isin" style="width:250px"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="DETAIL" style="padding:5px">
				<table id="dg-cashbankacc"  style="width:100%">
				</table>
				<div id="tb-cashbankacc">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-cashbankacc').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-cashbankacc').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-cashbankacc').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-cashbankacc').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$('#dg-cashbank').edatagrid({
		singleSelect: false,
		toolbar:'#tb-cashbank',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editcashbank(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-cashbankacc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvcashbankacc = $(this).datagrid('getRowDetail',index).find('table.ddv-cashbankacc');
			ddvcashbankacc.datagrid({
				url:'<?php echo $this->createUrl('cashbank/indexpay',array('grid'=>true)) ?>?id='+row.cashbankid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				pagination:true,
				loadMsg:'',
				height:'auto',
				width:'600px',
				columns:[[
					{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>'},
					{field:'cheqno',title:'<?php echo GetCatalog('cheqno') ?>'},
					{field:'tglterima',title:'<?php echo GetCatalog('tglterima') ?>'},
					{field:'tglcair',title:'<?php echo GetCatalog('tglcair') ?>'},
					{field:'debit',title:'<?php echo GetCatalog('debet') ?>'},
					{field:'credit',title:'<?php echo GetCatalog('credit') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>'},
					{field:'bankaccountno',title:'<?php echo GetCatalog('bankaccountno') ?>'},
					{field:'bankname',title:'<?php echo GetCatalog('bankname') ?>'},
					{field:'bankowner',title:'<?php echo GetCatalog('accountowner') ?>'},
					{field:'description',title:'<?php echo GetCatalog('description') ?>'},
				]],
				onResize:function(){
						$('#dg-cashbank').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-cashbank').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-cashbank').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('cashbank/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-cashbank').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cashbankid',
		editing: false,
		columns:[[
		{
field:'cashbankid',
title:'<?php echo GetCatalog('cashbankid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
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
field:'cashbankno',
title:'<?php echo GetCatalog('cashbankno') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'companyid',
title:'<?php echo GetCatalog('companyname') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return row.companyname;
					}}, 
{
	field:'isin',
	title:'<?php echo GetCatalog('isin') ?>',
	align:'center',
	width:'100px',
	sortable: true,
	formatter: function(value,row,index){
						if (value == 1){
										return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
										return '';
						}
}},
{
field:'receiptno',
title:'<?php echo GetCatalog('receiptno') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'headernote',
title:'<?php echo GetCatalog('headernote') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'center',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchcashbank(value){
	$('#dg-cashbank').edatagrid('load',{
	cashbankid:value,
	docdate:value,
	cashbankno:value,
	companyid:value,
	receiptno:value,
	docdate:value,
	recordstatus:value,
	});
}
function approvecashbank() {
	var ss = [];
	var rows = $('#dg-cashbank').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cashbank/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cashbank').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelcashbank() {
	var ss = [];
	var rows = $('#dg-cashbank').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cashbank/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cashbank').edatagrid('reload');				
		} ,
		'cache':false});
};
function addcashbank() {
		$('#dlg-cashbank').dialog('open');
		$('#ff-cashbank-modif').form('clear');
		$('#ff-cashbank-modif').form('load','<?php echo $this->createUrl('cashbank/getdata') ?>');
};

function editcashbank($i) {
	var row = $('#dg-cashbank').datagrid('getSelected');
	if(row) {
		$('#dlg-cashbank').dialog('open');
		$('#ff-cashbank-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormcashbank(){
	$('#ff-cashbank-modif').form('submit',{
		url:'<?php echo $this->createUrl('cashbank/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-cashbank').datagrid('reload');
        $('#dlg-cashbank').dialog('close');
			}
    }
	});	
};

function clearFormcashbank(){
		$('#ff-cashbank-modif').form('clear');
};

function cancelFormcashbank(){
		$('#dlg-cashbank').dialog('close');
};
$('#ff-cashbank-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-cashbank').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-cashbankacc').datagrid({
			queryParams: {
				id: $('#cashbankid').val()
			}
		});
		if (data.isin == 1)
				{
						$('#isin').prop('checked', true);
				} else
				{
						$('#isin').prop('checked', false);
				}
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
function downpdfcashbank() {
	var ss = [];
	var rows = $('#dg-cashbank').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankid);
	}
	window.open('<?php echo $this->createUrl('cashbank/downpdf') ?>?id='+ss);
}
function downxlscashbank() {
	var ss = [];
	var rows = $('#dg-cashbank').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankid);
	}
	window.open('<?php echo $this->createUrl('cashbank/downxls') ?>?id='+ss);
}

$('#dg-cashbankacc').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'cashbankaccid',
	editing: true,
	toolbar:'#tb-cashbankacc',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cashbank/searchpay',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cashbank/savepay',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('cashbank/savepay',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('cashbank/purgepay',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-cashbankacc').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-cashbankacc').edatagrid('getSelected');
		if (row)
		{
			row.cashbankid = $('#cashbankid').val();
		}
	},
	onBeforeEdit: function(index,row){
		if (row.debit != undefined) {
			var value = row.debit;
			row.debit = value.replace(".", "");
			var value = row.credit;
			row.credit = value.replace(".", "");
			var value = row.currencyrate;
			row.currencyrate = value.replace(".", "");
		}
	},
	columns:[[
	{
		field:'cashbankid',
		title:'<?php echo GetCatalog('cashbankid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'cashbankaccid',
		title:'<?php echo GetCatalog('cashbankaccid') ?>',
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
		editor:{
			type:'textbox'
		},
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'tglcair',
		title:'<?php echo GetCatalog('tglcair') ?>',
		width:'150px',
		editor:{
			type:'datebox'
		},
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'tglterima',
		title:'<?php echo GetCatalog('tglterima') ?>',
		width:'150px',
		editor:{
			type:'datebox'
		},
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'debit',
		title:'<?php echo GetCatalog('debit') ?>',
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
		field:'credit',
		title:'<?php echo GetCatalog('credit') ?>',
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
						required:true,
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
		field:'bankaccountno',
		title:'<?php echo GetCatalog('bankaccountno') ?>',
    editor:'text',
		width:'150px',		
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'bankname',
		title:'<?php echo GetCatalog('bankname') ?>',
    editor:'text',
		width:'150px',		
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'bankowner',
		title:'<?php echo GetCatalog('accountowner') ?>',
    editor:'text',
		width:'150px',		
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'description',
		title:'<?php echo GetCatalog('description') ?>',
    editor:'text',
		width:'150px',		
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
