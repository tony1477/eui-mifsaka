<table id="dg-cashbankout" style="width:100%;height:97%">
</table>
<div id="tb-cashbankout">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addCashbankout()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editCashbankout()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvecashbankout()"></a>
        <?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelcashbankout()"></a>
        <?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfcashbankout()"></a>
        <?php }?>
				<table>
<tr>
<td><?php echo GetCatalog('cashbankoutid')?></td>
<td><input class="easyui-textbox" id="cashbankout_search_cashbankoutid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="cashbankout_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('cashbankoutno')?></td>
<td><input class="easyui-textbox" id="cashbankout_search_cashbankoutno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="cashbankout_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('reqpayno')?></td>
<td><input class="easyui-textbox" id="cashbankout_search_reqpayno" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchcashbankout()"></a></td>
</tr>
</table>
</div>

<div id="dlg-cashbankout" class="easyui-dialog" title="Cashbankout" style="width:auto;height:400px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormCashbankout();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-cashbankout').dialog('close');
			}
		},
	]	
	">
	<form id="ff-cashbankout-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="cashbankoutid" id="cashbankoutid"></input>
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
								mode: 'remote',
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
				<td><?php echo GetCatalog('reqpay')?></td>
				<td><select class="easyui-combogrid" id="reqpayid" name="reqpayid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'reqpayid',
								required: true,
								textField: 'reqpayno',
								pagination:true,
								url: '<?php echo $this->createUrl('reqpay/index',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								queryParams:{
									cashbankout:true,
								},
								onBeforeLoad: function(param) {
									 param.companyid = $('#companyid').combogrid('getValue');
								},
								onHidePanel: function()
									{
										jQuery.ajax({'url':'<?php echo $this->createUrl('cashbankout/generatedetail') ?>',
											'data':{
																'id':$('#reqpayid').combogrid('getValue'),
																'hid':$('#cashbankoutid').val()
															},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#dg-cbapinv').edatagrid('reload');				
											},
											'cache':false});
									},
								columns: [[
										{field:'reqpayid',title:'<?php echo GetCatalog('reqpayid') ?>',width:20},
										{field:'reqpayno',title:'<?php echo GetCatalog('reqpayno') ?>',width:120},
										{field:'companyname',title:'<?php echo GetCatalog('company') ?>',width:120},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
		</table>
	</form>
        <div class="easyui-tabs" style="width:auto;height:400px">
                <div title="INVOICE" style="padding:5px">
                        <table id="dg-cbapinv"  style="width:100%">
                        </table>
                        <div id="tb-cbapinv">
                                
                                <a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-cbapinv').edatagrid('saveRow')"></a>
                                <a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-cbapinv').edatagrid('cancelRow')"></a>
                                <a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-cbapinv').edatagrid('destroyRow')"></a>
                        </div>
                </div>
	</div>
</div>

<script type="text/javascript">
$('#cashbankout_search_cashbankoutid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcashbankout();
			}
		}
	})
});
$('#cashbankout_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcashbankout();
			}
		}
	})
});
$('#cashbankout_search_cashbankoutno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcashbankout();
			}
		}
	})
});
$('#cashbankout_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcashbankout();
			}
		}
	})
});
$('#cashbankout_search_reqpayno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchcashbankout();
			}
		}
	})
});
$('#dg-cashbankout').edatagrid({	
		singleSelect: false,
		toolbar:'#tb-cashbankout',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
                onDblClickRow: function (index,row) {
			editCashbankout(index);
		},
                rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
                view: detailview,
                detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-cbapinv"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvcbapinv = $(this).datagrid('getRowDetail',index).find('table.ddv-cbapinv');
                        ddvcbapinv.datagrid({
				url:'<?php echo $this->createUrl('cashbankout/indexinvoice',array('grid'=>true)) ?>?id='+row.cashbankoutid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				pagination:true,
				showFooter:true,
				columns:[[
					{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno') ?>'},
					{field:'ekspedisino',title:'<?php echo GetCatalog('ekspedisino') ?>'},
					{field:'supplier',title:'<?php echo GetCatalog('supplier') ?>'},
					{field:'ekspedisi',title:'<?php echo GetCatalog('ekspedisi') ?>'},
					{field:'plantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
					{field:'invoicedate',title:'<?php echo GetCatalog('invoicedate') ?>'},
					{field:'duedate',title:'<?php echo GetCatalog('duedate') ?>'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>',align:'right'},
					{field:'nilai',title:'<?php echo GetCatalog('nilaiexp') ?>',align:'right'},
					{field:'payamount',title:'<?php echo GetCatalog('payamount') ?>',align:'right'},
					{field:'cashbankno',title:'<?php echo GetCatalog('cashbankno') ?>'},
					{field:'tglcair',title:'<?php echo GetCatalog('tglcair') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'bankaccountno',title:'<?php echo GetCatalog('bankaccountno') ?>'},
					{field:'bankname',title:'<?php echo GetCatalog('bankname') ?>'},
					{field:'bankowner',title:'<?php echo GetCatalog('accountowner') ?>'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>'},
					{field:'saldo',title:'<?php echo GetCatalog('saldo') ?>',align:'right'},
				]],
				onResize:function(){
						$('#dg-cashbankout').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-cashbankout').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-cashbankout').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('cashbankout/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-cashbankout').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cashbankoutid',
		editing: false,
		columns:[[
		{
field:'cashbankoutid',
title:'<?php echo GetCatalog('cashbankoutid') ?>',
sortable: true,
width:'50px',
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
field:'cashbankoutno',
title:'<?php echo GetCatalog('cashbankoutno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.cashbankoutno;
					}},
{
field:'companyid',
title:'<?php echo GetCatalog('companyname') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.companyname;
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
field:'reqpayid',
title:'<?php echo GetCatalog('reqpayno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.reqpayno;
					}},
{
field:'recordstatuscashbankout',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'left',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchcashbankout(value){
	$('#dg-cashbankout').edatagrid('load',{
				cashbankoutid:$('#cashbankout_search_cashbankoutid').val(),
				cashbankoutno:$('#cashbankout_search_cashbankoutno').val(),
				companyid:$('#cashbankout_search_companyname').val(),
        docdate:$('#cashbankout_search_docdate').val(),
        reqpayid:$('#cashbankout_search_reqpayno').val()
	});
};
function approvecashbankout() {
	var ss = [];
	var rows = $('#dg-cashbankout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankoutid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cashbankout/approve') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-cashbankout').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelcashbankout() {
	var ss = [];
	var rows = $('#dg-cashbankout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankoutid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('cashbankout/delete') ?>',
		'data':{'id':ss},
                'type':'post',
                'dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-cashbankout').edatagrid('reload');				
		} ,
		'cache':false});
};
function addCashbankout() {
		$('#dlg-cashbankout').dialog('open');
		$('#ff-cashbankout-modif').form('clear');
		$('#ff-cashbankout-modif').form('load','<?php echo $this->createUrl('cashbankout/getdata') ?>');
};

function editCashbankout($i) {
	var row = $('#dg-cashbankout').datagrid('getSelected');
	if(row) {
		$('#dlg-cashbankout').dialog('open');
		$('#ff-cashbankout-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormCashbankout(){
	$('#ff-cashbankout-modif').form('submit',{
		url:'<?php echo $this->createUrl('cashbankout/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-cashbankout').datagrid('reload');
        $('#dlg-cashbankout').dialog('close');
			}
    }
	});	
};

function clearFormCashbankout(){
		$('#ff-cashbankout-modif').form('clear');
};

function cancelFormCashbankout(){
		$('#dlg-cashbankout').dialog('close');
};
$('#ff-cashbankout-modif').form({
	onLoadSuccess: function(data) {
		var row = $('#dg-cashbankout').datagrid('getSelected');
		if(row) {
			$('#docdate').datebox('setValue', data.docdate);			
		}
		$('#dg-cbapinv').datagrid({
			queryParams: {
				id: $('#cashbankoutid').val()
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
function downpdfcashbankout() {
	var ss = [];
	var rows = $('#dg-cashbankout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankoutid);
	}
	window.open('<?php echo $this->createUrl('repcbout/downpdf') ?>?id='+ss);
}
function downxlscashbankout() {
	var ss = [];
	var rows = $('#dg-cashbankout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankoutid);
	}
	window.open('<?php echo $this->createUrl('cashbankout/downxls') ?>?id='+ss);
}
$('#dg-cbapinv').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'cbapinvid',
	editing: true,
	toolbar:'#tb-cbapinv',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('cashbankout/searchinvoice',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('cashbankout/saveinvoice',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('cashbankout/saveinvoice',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('cashbankout/purgeinvoice',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-cbapinv').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-cbapinv').edatagrid('getSelected');
		if (row)
		{
			row.cashbankoutid = $('#cashbankoutid').val();
		}
	},
        columns:[[
	{
		field:'cashbankoutid',
		title:'<?php echo GetCatalog('cashbankoutid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'cbapinvid',
		title:'<?php echo GetCatalog('cbapinvid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'invoiceapid',
		title:'<?php echo GetCatalog('invoiceno') ?>',
    width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.invoiceno;
		}
	},
	{
		field:'ekspedisiid',
		title:'<?php echo GetCatalog('ekspedisino') ?>',
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
		field:'invoicedate',
		title:'<?php echo GetCatalog('invoicedate') ?>',
		sortable: true,
    width:'150px',
		formatter: function(value,row,index){
							return row.invoicedate;
		}
	},
    {
		field:'duedate',
		title:'<?php echo GetCatalog('duedate') ?>',
		sortable: true,
    width:'150px',
		formatter: function(value,row,index){
							return row.duedate;
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
							trxcom:true
						},
						onBeforeLoad: function(param) {
							 param.companyid = $('#companyid').combogrid('getValue');
						},
                        onHidePanel: function() {
                            var tr = $(this).closest('tr.datagrid-row');
                            var index = parseInt(tr.attr('datagrid-row-index'));
                            var plantid = $('#dg-cbapinv').datagrid("getEditor", {index: index, field:"plantid"});
                            
                            jQuery.ajax({
                                'url':'<?php echo Yii::app()->createUrl('common/plant/indexcompany',array('grid'=>true))?>',
                                'data' :{
                                    'companyid' : $('#companyid').combogrid('getValue'),
                                    'trxcom' : true,
                                },
                                'type':'post',
                                'dataType':'json',
                                'success':function(data)
                                {
                                    console.log('success');
                                    $(plantid.target).combogrid('setValue',data.rows[0]['plantid']);
                                },
                                'cache':false
                            });
                        },
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'accountid',title:'<?php echo GetCatalog('accountid')?>'},
							{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>'},
							{field:'accountname',title:'<?php echo GetCatalog('accountname')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('company')?>'},
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
		field:'plantid',
		title:'<?php echo GetCatalog('plantcode') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'plantid',
						textField:'plantcode',
						url:'<?php echo Yii::app()->createUrl('common/plant/index',array('grid'=>true)) ?>',
						fitColumns:true,
						required:true,
						pagination:true,
						queryParams:{
							trxcom:true
						},
						onBeforeLoad: function(param) {
							 param.companyid = $('#companyid').combogrid('getValue');
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'plantid',title:'<?php echo GetCatalog('plantid')?>'},
							{field:'plantcode',title:'<?php echo GetCatalog('plantcode')?>'},
							{field:'description',title:'<?php echo GetCatalog('description')?>'},
						]]
				}	
			},
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.plantcode;
		}
	},
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
		field:'cashbankno',
		title:'<?php echo GetCatalog('cashbankno') ?>',
		editor:'text',
		sortable: true,
    width:'150px',
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'tglcair',
		title:'<?php echo GetCatalog('tglcair') ?>',
		editor:'datebox',
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'payamount',
		title:'<?php echo GetCatalog('payamount') ?>',
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
		field:'itemnote',
		title:'<?php echo GetCatalog('itemnote') ?>',
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
