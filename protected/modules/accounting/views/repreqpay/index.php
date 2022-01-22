<table id="dg-repreqpay" style="width:100%;height:97%"></table>
<div id="tb-repreqpay">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreqpay()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('reqpayid')?></td>
<td><input class="easyui-textbox" id="repreqpay_search_reqpayid" style="width:150px"></td>
<td><?php echo GetCatalog('company')?></td>
<td><input class="easyui-textbox" id="repreqpay_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('reqpayno')?></td>
<td><input class="easyui-textbox" id="repreqpay_search_reqpayno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="repreqpay_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="repreqpay_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchrepreqpay()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repreqpay_search_reqpayid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepreqpay();
			}
		}
	})
});
$('#repreqpay_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepreqpay();
			}
		}
	})
});
$('#repreqpay_search_reqpayno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepreqpay();
			}
		}
	})
});
$('#repreqpay_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepreqpay();
			}
		}
	})
});
$('#repreqpay_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepreqpay();
			}
		}
	})
});
$('#dg-repreqpay').edatagrid({
		singleSelect: false,
		toolbar:'#tb-repreqpay',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
                autoRowHeight:true,
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
				url:'<?php echo $this->createUrl('reqpay/indexinvoice',array('grid'=>true)) ?>?id='+row.reqpayid,
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
          {field:'supplier',title:'<?php echo GetCatalog('supplier') ?>'},
					{field:'invoicedate',title:'<?php echo GetCatalog('invoicedate') ?>'},
					{field:'duedate',title:'<?php echo GetCatalog('duedate') ?>'},
					{field:'taxcode',title:'<?php echo GetCatalog('taxcode') ?>'},
					{field:'taxno',title:'<?php echo GetCatalog('notax') ?>'},
					{field:'taxdate',title:'<?php echo GetCatalog('taxdate') ?>'},
					{field:'amount',title:'<?php echo GetCatalog('amount') ?>',align:'right'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>'},
					{field:'bankaccountno',title:'<?php echo GetCatalog('bankaccountno') ?>'},
					{field:'bankname',title:'<?php echo GetCatalog('bankname') ?>'},
					{field:'bankowner',title:'<?php echo GetCatalog('accountowner') ?>'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>'},
					{field:'saldo',title:'<?php echo GetCatalog('alreadypaid') ?>',align:'right'},
				]],
				onResize:function(){
						$('#dg-repreqpay').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repreqpay').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repreqpay').datagrid('fixDetailRowHeight',index);
                },
                url: '<?php echo $this->createUrl('repreqpay/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repreqpay').edatagrid('reload');
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
width:'60px',
formatter: function(value,row,index){
					if (row.recordstatus == 1) {
				return '<div style="background-color:green;color:white">'+value+'</div>';
			} else 
				if (row.recordstatus == 2) {
					return '<div style="background-color:yellow;color:black">'+value+'</div>';
				} else 
					if (row.recordstatus == 3) {
					return '<div style="background-color:cyan;color:black">'+value+'</div>';
				} else 
					if (row.recordstatus == 4) {
					return '<div style="background-color:blue;color:white">'+value+'</div>';
				} else 
					if (row.recordstatus == 5) {
					return '<div style="background-color:orange;color:white">'+value+'</div>';
				} else 
					if (row.recordstatus == 6) {
						return '<div style="background-color:red;color:white">'+value+'</div>';
					} else 
						if (row.recordstatus == 0) {
						return '<div style="background-color:black;color:white">'+value+'</div>';
					}
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
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
					{
field:'companyid',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'300px',
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
field:'recordstatus',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return row.recordstatusname;
					}},
		]]
});
function searchrepreqpay(value){
	$('#dg-repreqpay').edatagrid('load',{
		reqpayid:$('#repreqpay_search_reqpayid').val(),
		docdate:$('#repreqpay_search_docdate').val(),
		reqpayno:$('#repreqpay_search_reqpayno').val(),
		headernote:$('#repreqpay_search_headernote').val(),
		companyid:$('#repreqpay_search_companyname').val()
	});
}
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
	var rows = $('#dg-repreqpay').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reqpayid);
	}
	window.open('<?php echo $this->createUrl('reqpay/downpdf') ?>?id='+ss);
}
</script>
