<table id="dg-repinvap" style="width:100%;height:97%"></table>
<div id="tb-repinvap">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfinvoiceap()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('invoiceapid')?></td>
<td><input class="easyui-textbox" id="repinvap_search_invoiceapid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="repinvap_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('invoiceno')?></td>
<td><input class="easyui-textbox" id="repinvap_search_invoiceno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('pono')?></td>
<td><input class="easyui-textbox" id="repinvap_search_pono" style="width:150px"></td>
<td><?php echo GetCatalog('invoicedate')?></td>
<td><input class="easyui-textbox" id="repinvap_search_invoicedate" style="width:150px"></td>
<td><?php echo GetCatalog('grno')?></td>
<td><input class="easyui-textbox" id="repinvap_search_grno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('supplier')?></td>
<td><input class="easyui-textbox" id="repinvap_search_supplier" style="width:150px"></td>
<td><?php echo GetCatalog('paymentmethod')?></td>
<td><input class="easyui-textbox" id="repinvap_search_paymentmethod" style="width:150px"></td>
<td><?php echo GetCatalog('tax')?></td>
<td><input class="easyui-textbox" id="repinvap_search_taxid" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="repinvap_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchrepinvap()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repinvap_search_invoiceapid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_invoiceno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_pono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_invoicedate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_grno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_supplier').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_paymentmethod').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#repinvap_search_paymentmethod').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepinvap();
			}
		}
	})
});
$('#dg-repinvap').edatagrid({
		singleSelect: false,
		toolbar:'#tb-repinvap',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
                autoRowHeight:true,
		queryParams:{
			list:true
		},
                view: detailview,
                detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-invoiceapmat"></table><table class="ddv-invoiceapjurnal"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvinvoiceapmat = $(this).datagrid('getRowDetail',index).find('table.ddv-invoiceapmat');
			var ddvinvoiceapjurnal = $(this).datagrid('getRowDetail',index).find('table.ddv-invoiceapjurnal');
			ddvinvoiceapmat.datagrid({
				url:'<?php echo $this->createUrl('repinvap/indexmaterial',array('grid'=>true)) ?>?id='+row.invoiceapid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'800px',
				pagination:true,
				showFooter:true,
				columns:[[
          {field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
					{field:'poqty',title:'<?php echo GetCatalog('poqty') ?>',align:'right'},
					{field:'grqty',title:'<?php echo GetCatalog('grqty') ?>',align:'right'},
					{field:'price',title:'<?php echo GetCatalog('price') ?>',align:'right'},
					{field:'jumlah',title:'<?php echo GetCatalog('jumlah') ?>',align:'right'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>'},
				]],
				onResize:function(){
						$('#dg-repinvap').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repinvap').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
                        ddvinvoiceapjurnal.datagrid({
				url:'<?php echo $this->createUrl('repinvap/indexjurnal',array('grid'=>true)) ?>?id='+row.invoiceapid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'800px',
				pagination:true,
				columns:[[
				    {field:'plantcode',title:'<?php echo GetCatalog('plantcode') ?>'},
                    {field:'accountname',title:'<?php echo GetCatalog('accountname') ?>'},
					{field:'debet',title:'<?php echo GetCatalog('debet') ?>',align:'right'},
					{field:'credit',title:'<?php echo GetCatalog('credit') ?>',align:'right'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('rate') ?>',align:'right'},
					{field:'description',title:'<?php echo GetCatalog('description') ?>'},
				]],
				onResize:function(){
						$('#dg-repinvap').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
                                                $('#dg-repinvap').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repinvap').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('repinvap/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repinvap').edatagrid('reload');
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
title:'<?php echo GetCatalog('company') ?>',
width:'250px',
sortable: true,
formatter: function(value,row,index){
						return row.companyname;
					}},
{
field:'invoiceno',
title:'<?php echo GetCatalog('invoiceno') ?>',
width:'120px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'invoicedate',
title:'<?php echo GetCatalog('invoicedate') ?>',
sortable: true,
width:'100px',
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
width:'120px',
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
width:'130px',
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
width:'70px',
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

function searchrepinvap(value){
	$('#dg-repinvap').edatagrid('load',{
	invoiceapid:$('#repinvap_search_invoiceapid').val(),
        invoiceno:$('#repinvap_search_invoiceno').val(),
        invoicedate:$('#repinvap_search_invoicedate').val(),
        poheaderid:$('#repinvap_search_pono').val(),
        addressbookid:$('#repinvap_search_supplier').val(),
        paymentmethoid:$('#repinvap_search_paymentmethod').val(),
        companyid:$('#repinvap_search_companyname').val(),
        taxid:$('#repinvap_search_taxid').val(),
        grheaderid:$('#repinvap_search_grno').val(),
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
function downpdfinvoiceap() {
	var ss = [];
	var rows = $('#dg-repinvap').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.invoiceapid);
	}
	window.open('<?php echo $this->createUrl('repinvap/downpdf') ?>?id='+ss);
}
</script>
