<table id="dg-repcbout" style="width:100%;height:97%"></table>
<div id="tb-repcbout">
	<?php
	if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepcbout()"></a>
        <?php }?>
<table>
<tr>
<td><?php echo GetCatalog('cashbankoutid')?></td>
<td><input class="easyui-textbox" id="repcbout_search_cashbankoutid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="repcbout_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('cashbankoutno')?></td>
<td><input class="easyui-textbox" id="repcbout_search_cashbankoutno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="repcbout_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('reqpayno')?></td>
<td><input class="easyui-textbox" id="repcbout_search_reqpayno" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchrepcbout()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repcbout_search_cashbankoutid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbout();
			}
		}
	})
});
$('#repcbout_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbout();
			}
		}
	})
});
$('#repcbout_search_cashbankoutno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbout();
			}
		}
	})
});
$('#repcbout_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbout();
			}
		}
	})
});
$('#repcbout_search_reqpayno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbout();
			}
		}
	})
});
$('#dg-repcbout').edatagrid({	
		singleSelect: false,
		toolbar:'#tb-repcbout',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
                onDblClickRow: function (index,row) {
			editCbout(index);
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
				url:'<?php echo $this->createUrl('repcbout/indexinvoice',array('grid'=>true)) ?>?id='+row.cashbankoutid,
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
						$('#dg-repcbout').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repcbout').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repcbout').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('repcbout/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repcbout').edatagrid('reload');
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
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchrepcbout(value){
	$('#dg-repcbout').edatagrid('load',{
				cashbankoutid:$('#repcbout_search_cashbankoutid').val(),
				cashbankoutno:$('#repcbout_search_cashbankoutno').val(),
				companyid:$('#repcbout_search_companyname').val(),
        docdate:$('#repcbout_search_docdate').val(),
        reqpayid:$('#repcbout_search_reqpayno').val()
	});
};

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
function downpdfrepcbout() {
	var ss = [];
	var rows = $('#dg-repcbout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankoutid);
	}
	window.open('<?php echo $this->createUrl('repcbout/downpdf') ?>?id='+ss);
}
function downxlsrepcbout() {
	var ss = [];
	var rows = $('#dg-repcbout').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankoutid);
	}
	window.open('<?php echo $this->createUrl('repcbout/downxls') ?>?id='+ss);
}
