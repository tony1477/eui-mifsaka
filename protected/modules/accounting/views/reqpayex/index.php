<table id="dg-reqpayex" style="width:100%;height:97%"></table>
<div id="tb-reqpayex">
	<?php
if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addReqpay()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editReqpay()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvereqpay()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelreqpay()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreqpay()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchreqpay" style="width:150px">
</div>

<div id="dlg-reqpayex" class="easyui-dialog" title="Permohonan Pembayaran Ekspedisi" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormReqpay();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-reqpayex').dialog('close');
			}
		},
	]	
	">
	<form id="ff-reqpay-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="reqpayid" id="reqpayid"></input>
		<table cellpadding="5">
                        <tr>
				<td><?php echo GetCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td><select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'companyid',
								required: true,
								textField: 'companyname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('admin/company/index',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								queryParams:{
									auth:true,
								},
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
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
		<div title="Ekspedisi" style="padding:5px">
			<table id="dg-reqpayexinv"  style="width:100%">
			</table>
			<div id="tb-reqpayexinv">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-reqpayexinv').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-reqpayexinv').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-reqpayexinv').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-reqpayexinv').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#dg-reqpayex').edatagrid({
		singleSelect: false,
		toolbar:'#tb-reqpayex',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editReqpay(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-reqpayinv"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvreqpayinv = $(this).datagrid('getRowDetail',index).find('table.ddv-reqpayinv');
			ddvreqpayinv.datagrid({
				url:'<?php echo $this->createUrl('reqpayex/indexekspedisi',array('grid'=>true)) ?>?id='+row.reqpayid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				columns:[[
					{field:'ekspedisino',title:'<?php echo GetCatalog('ekspedisino') ?>'},
          {field:'supplier',title:'<?php echo GetCatalog('supplier') ?>'},
					{field:'docdate',title:'<?php echo GetCatalog('docdate') ?>'},
					{field:'taxcode',title:'<?php echo GetCatalog('taxcode') ?>'},
					{field:'taxno',title:'<?php echo GetCatalog('notax') ?>'},
					{field:'taxdate',title:'<?php echo GetCatalog('taxdate') ?>'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>'},
					{field:'bankaccountno',title:'<?php echo GetCatalog('bankaccountno') ?>'},
					{field:'bankname',title:'<?php echo GetCatalog('bankname') ?>'},
					{field:'bankowner',title:'<?php echo GetCatalog('accountowner') ?>'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>'},
					{field:'saldo',title:'<?php echo GetCatalog('alreadypaid') ?>'},
				]],
				onResize:function(){
						$('#dg-reqpayex').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-reqpayex').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-reqpayex').datagrid('fixDetailRowHeight',index);
                },
		url: '<?php echo $this->createUrl('reqpayex/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-reqpayex').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'reqpayid',
		editing: false,
		columns:[[
		{
field:'reqpayid',
title:'<?php echo GetCatalog('reqpayid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'reqpayno',
title:'<?php echo GetCatalog('reqpayno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
					{
field:'companyid',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return row.companyname;
					}},
{
field:'headernote',
title:'<?php echo GetCatalog('headernote') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatusreqpay',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'center',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchreqpay(value){
	$('#dg-reqpayex').edatagrid('load',{
	reqpayid:value,
	docdate:value,
	reqpayno:value,
	headernote:value,
	recordstatus:value,
	});
}
function approvereqpay() {
	var ss = [];
	var rows = $('#dg-reqpayex').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reqpayid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('reqpay/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-reqpayex').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelreqpay() {
	var ss = [];
	var rows = $('#dg-reqpayex').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reqpayid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('reqpay/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-reqpayex').edatagrid('reload');				
		} ,
		'cache':false});
};
function addReqpay() {
		$('#dlg-reqpayex').dialog('open');
		$('#ff-reqpay-modif').form('clear');
		$('#ff-reqpay-modif').form('load','<?php echo $this->createUrl('reqpay/getdata') ?>');
};

function editReqpay($i) {
	var row = $('#dg-reqpayex').datagrid('getSelected');
	if(row) {
		$('#dlg-reqpayex').dialog('open');
		$('#ff-reqpay-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormReqpay(){
	$('#ff-reqpay-modif').form('submit',{
		url:'<?php echo $this->createUrl('reqpay/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-reqpayex').datagrid('reload');
        $('#dlg-reqpayex').dialog('close');
			}
    }
	});	
};

function clearFormReqpay(){
		$('#ff-reqpay-modif').form('clear');
};

function cancelFormReqpay(){
		$('#dlg-reqpayex').dialog('close');
};
$('#ff-reqpay-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-reqpayex').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-reqpayexinv').datagrid({
			queryParams: {
				id: $('#reqpayid').val()
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
function downpdfreqpay() {
	var ss = [];
	var rows = $('#dg-reqpayex').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reqpayid);
	}
	window.open('<?php echo $this->createUrl('reqpay/downpdf') ?>?id='+ss);
}
function downxlsreqpay() {
	var ss = [];
	var rows = $('#dg-reqpayex').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reqpayid);
	}
	window.open('<?php echo $this->createUrl('reqpay/downxls') ?>?id='+ss);
}
$('#dg-reqpayexinv').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'reqpayinvid',
	editing: true,
	toolbar:'#tb-reqpayexinv',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('reqpayex/searchekspedisi',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('reqpayex/saveekspedisi',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('reqpayex/saveekspedisi',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('reqpayex/purgeekspedisi',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-reqpayexinv').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-reqpayexinv').edatagrid('getSelected');
		if (row)
		{
			row.reqpayid = $('#reqpayid').val();
		}
	},
        columns:[[
	{
		field:'reqpayid',
		title:'<?php echo GetCatalog('reqpayid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'reqpayinvid',
		title:'<?php echo GetCatalog('reqpayinvid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'ekspedisiid',
		title:'<?php echo GetCatalog('ekspedisino') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'ekspedisiid',
						textField:'ekspedisino',
						url:'<?php echo Yii::app()->createUrl('accounting/ekspedisi/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
            onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var ekspedisiid = $("#dg-reqpayexinv").datagrid("getEditor", {index: index, field:"ekspedisiid"});
								var currencyid = $("#dg-reqpayexinv").datagrid("getEditor", {index: index, field:"currencyid"});
								var currencyrate = $("#dg-reqpayexinv").datagrid("getEditor", {index: index, field:"currencyrate"});
								var bankaccountno = $("#dg-reqpayexinv").datagrid("getEditor", {index: index, field:"bankaccountno"});
								var bankname = $("#dg-reqpayexinv").datagrid("getEditor", {index: index, field:"bankname"});
								var bankowner = $("#dg-reqpayexinv").datagrid("getEditor", {index: index, field:"bankowner"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('accounting/reqpayex/generatebank') ?>',
									'data':{'ekspedisiid':$(ekspedisiid.target).combogrid("getValue"),},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(currencyid.target).combogrid('setValue',data.currencyid);
										$(currencyrate.target).numberbox('setValue',data.currencyrate);
										$(bankaccountno.target).textbox('setValue',data.bankaccountno);
										$(bankname.target).textbox('setValue',data.bankname);
										$(bankowner.target).textbox('setValue',data.bankowner);
									},
									'cache':false});
							}
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'ekspedisiid',title:'<?php echo GetCatalog('ekspedisiid')?>'},
							{field:'ekspedisino',title:'<?php echo GetCatalog('ekspedisino')?>'},
							{field:'docdate',title:'<?php echo GetCatalog('docdate')?>'},
							{field:'amount',title:'<?php echo GetCatalog('amount')?>'},
							{field:'supplier',title:'<?php echo GetCatalog('supplier')?>'},
						]]
				}	
			},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.ekspedisino;
		}
	},
	{
		field:'supplier',
		title:'<?php echo GetCatalog('supplier') ?>',
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
		return row.supplier;
		}
	},
	{
		field:'docdate',
		title:'<?php echo GetCatalog('docdate') ?>',
		sortable: true,
                width:'150px',
		formatter: function(value,row,index){
							return row.docdate;
		}
	},
	{
		field:'taxid',
		title:'<?php echo GetCatalog('tax') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'taxid',
						textField:'taxcode',
						url:'<?php echo Yii::app()->createUrl('accounting/tax/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'taxid',title:'<?php echo GetCatalog('taxid')?>'},
							{field:'taxcode',title:'<?php echo GetCatalog('taxcode')?>'},
						]]
				}	
			},
    width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.taxcode;
		}
	},
	{
	field:'taxno',
	title:'<?php echo GetCatalog('notax') ?>',
	sortable: true,
	editor:{
		type:'textbox'
	},
	width:'100px',
	formatter: function(value,row,index){
							return value;
						}},
	{
	field:'taxdate',
	title:'<?php echo GetCatalog('taxdate') ?>',
	editor:{
		type:'datebox'
	},
	sortable: true,
	width:'100px',
	formatter: function(value,row,index){
							return value;
						}},
	{
		field:'amount',
		title:'<?php echo GetCatalog('amount') ?>',
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
							return row.amount;
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
		field:'bankaccountno',
		title:'<?php echo GetCatalog('bankaccountno') ?>',
    editor:{
			type:'textbox'
		},
		width:'150px',		
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'bankname',
		title:'<?php echo GetCatalog('bankname') ?>',
    editor:{
			type:'textbox'
		},
		width:'150px',		
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'bankowner',
		title:'<?php echo GetCatalog('accountowner') ?>',
    editor:{
			type:'textbox'
		},
		width:'150px',		
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'itemnote',
		title:'<?php echo GetCatalog('itemnote') ?>',
		editor:'text',
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
