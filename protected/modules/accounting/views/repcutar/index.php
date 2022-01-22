<table id="dg-repcutar" style="width:100%;height:97%"></table>
<div id="tb-repcutar">
<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepcutar()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('cutarid')?></td>
<td><input class="easyui-textbox" id="repcutar_search_cutarid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="repcutar_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('cutarno')?></td>
<td><input class="easyui-textbox" id="repcutar_search_cutarno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('ttnt')?></td>
<td><input class="easyui-textbox" id="repcutar_search_docno" style="width:150px"></td>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="repcutar_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('cbinno')?></td>
<td><input class="easyui-textbox" id="repcutar_search_cbinno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="repcutar_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchrepcutar()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repcutar_search_cutarid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcutar();
			}
		}
	})
});
$('#repcutar_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcutar();
			}
		}
	})
});
$('#repcutar_search_cutarno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcutar();
			}
		}
	})
});
$('#repcutar_search_docno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcutar();
			}
		}
	})
});
$('#repcutar_search_cbinno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcutar();
			}
		}
	})
});
$('#repcutar_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcutar();
			}
		}
	})
});
$('#repcutar_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcutar();
			}
		}
	})
});
$('#dg-repcutar').edatagrid({
		singleSelect: false,
		toolbar:'#tb-repcutar',
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
				return '<div style="padding:2px"><table class="ddv-repcutarinv"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvrepcutarinv = $(this).datagrid('getRowDetail',index).find('table.ddv-repcutarinv');
			ddvrepcutarinv.datagrid({
				url:'<?php echo $this->createUrl('repcutar/indexinvoice',array('grid'=>true)) ?>?id='+row.cutarid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				showFooter:true,
				title:'Detail Pelunasan - Invoice',
				height:'auto',
				pagination:true,
				onSelect:function(index,row){
					ddvcbarpay.edatagrid('load',{
						repcutarinvid: row.repcutarinvid,
						cutarid: row.cutarid
					})
				},
				width:'100%',
				columns:[[
					{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno') ?>'},
					{field:'saldoinvoice',title:'<?php echo GetCatalog('amount') ?>',align:'right'},
					{field:'invoicedate',title:'<?php echo GetCatalog('invoicedate') ?>'},
					{field:'cashamount',title:'<?php echo GetCatalog('cashamount') ?>',align:'right'},
					{field:'bankamount',title:'<?php echo GetCatalog('bankamount') ?>',align:'right'},
					{field:'discamount',title:'<?php echo GetCatalog('discamount') ?>',align:'right'},
					{field:'returnamount',title:'<?php echo GetCatalog('returnamount') ?>',align:'right'},
					{field:'notagirno',title:'<?php echo GetCatalog('notagirno') ?>'},
					{field:'obamount',title:'<?php echo GetCatalog('obamount') ?>',align:'right'},
					{field:'saldo',title:'<?php echo GetCatalog('saldo') ?>',align:'right'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'description',title:'<?php echo GetCatalog('description') ?>'},
				]],
				onResize:function(){
						$('#dg-repcutar').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repcutar').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			
			$('#dg-repcutar').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('repcutar/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repcutar').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cutarid',
		editing: false,
		columns:[[
		{
field:'cutarid',
title:'<?php echo GetCatalog('cutarid') ?>',
sortable: true,
width:'80px',
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
width:'250px',
formatter: function(value,row,index){
						return row.companyname;
					}},
{
field:'cutarno',
title:'<?php echo GetCatalog('cutarno') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.cutarno;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
editor:'text',
width:'120px',
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
width:'120px',
sortable: true,
formatter: function(value,row,index){
						return row.docno;
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
function searchrepcutar(value){
	$('#dg-repcutar').edatagrid('load',{
	cutarid:$('#repcutar_search_cutarid').val(),
	companyid:$('#repcutar_search_companyname').val(),
        ttntid:$('#repcutar_search_docno').val(),
        docdate:$('#repcutar_search_docdate').val(),
        cutarno:$('#repcutar_search_cutarno').val(),
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
function downpdfrepcutar() {
	var ss = [];
	var rows = $('#dg-repcutar').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cutarid);
	}
	window.open('<?php echo $this->createUrl('repcutar/downpdf') ?>?id='+ss);
}
function downxlsrepcutar() {
	var ss = [];
	var rows = $('#dg-repcutar').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cutarid);
	}
	window.open('<?php echo $this->createUrl('repcutar/downxls') ?>?id='+ss);
}
