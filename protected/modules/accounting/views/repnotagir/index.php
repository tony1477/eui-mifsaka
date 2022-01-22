<table id="dg-repnotagir" style="width:100%;height:97%"></table>
<div id="tb-repnotagir">
	<?php
	if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepnotagir()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('notagirid')?></td>
<td><input class="easyui-textbox" id="repnotagir_search_notagirid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="repnotagir_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('notagirno')?></td>
<td><input class="easyui-textbox" id="repnotagir_search_notagirno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('gireturno')?></td>
<td><input class="easyui-textbox" id="repnotagir_search_gireturno" style="width:150px"></td>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="repnotagir_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('customer')?></td>
<td><input class="easyui-textbox" id="repnotagir_search_customer" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="repnotagir_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchrepnotagir()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repnotagir_search_notagirid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagir();
			}
		}
	})
});
$('#repnotagir_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagir();
			}
		}
	})
});
$('#repnotagir_search_notagirno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagir();
			}
		}
	})
});
$('#repnotagir_search_gireturno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagir();
			}
		}
	})
});
$('#repnotagir_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagir();
			}
		}
	})
});
$('#repnotagir_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagir();
			}
		}
	})
});
$('#repnotagir_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagir();
			}
		}
	})
});
$('#dg-repnotagir').edatagrid({	
		singleSelect: false,
		toolbar:'#tb-repnotagir',
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
				return '<div style="padding:2px"><table class="ddv-notagirpro"></table><table class="ddv-notagiracc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvrepnotagirpro = $(this).datagrid('getRowDetail',index).find('table.ddv-notagirpro');
			var ddvrepnotagiracc = $(this).datagrid('getRowDetail',index).find('table.ddv-notagiracc');
			ddvrepnotagirpro.datagrid({
				url:'<?php echo $this->createUrl('repnotagir/indexproduct',array('grid'=>true)) ?>?id='+row.notagirid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				pagination:true,
				showFooter:true,
				columns:[[
					{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo GetCatalog('qty') ?>',align:'right'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
					{field:'price',title:'<?php echo GetCatalog('price') ?>',align:'right'},
					{field:'sloccode',title:'<?php echo GetCatalog('sloc') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'total',title:'<?php echo GetCatalog('total') ?>',align:'right'},
				]],
				onResize:function(){
						$('#dg-repnotagir').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repnotagir').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			ddvrepnotagiracc.datagrid({
				url:'<?php echo $this->createUrl('repnotagir/indexakun',array('grid'=>true)) ?>?id='+row.notagirid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				pagination:true,
				showFooter:true,
				columns:[[
					{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>'},
					{field:'debet',title:'<?php echo GetCatalog('debet') ?>',align:'right'},
					{field:'credit',title:'<?php echo GetCatalog('credit') ?>',align:'right'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>'},
				]],
				onResize:function(){
						$('#dg-repnotagir').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repnotagir').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repnotagir').datagrid('fixDetailRowHeight',index);
                },
		url: '<?php echo $this->createUrl('repnotagir/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repnotagir').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'notagirid',
		editing: false,
		columns:[[
{
field:'notagirid',
title:'<?php echo GetCatalog('notagirid') ?>',
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
width:'250px',
formatter: function(value,row,index){
					return row.companyname;
					}},
{
field:'notagirno',
title:'<?php echo GetCatalog('notagirno') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'docdate',
title:'<?php echo GetCatalog('docdate') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
					{
field:'addressbookid',
title:'<?php echo GetCatalog('customer') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.fullname;
					}},
{
field:'gireturid',
title:'<?php echo GetCatalog('gireturno') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.gireturno;
					}},
					{
field:'giheaderid',
title:'<?php echo GetCatalog('giheader') ?>',
sortable: true,
width:'180px',
formatter: function(value,row,index){
						return row.gino;
					}},
										{
field:'soheaderid',
title:'<?php echo GetCatalog('soheader') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.sono;
					}},
{
field:'recordstatusnotagir',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'200px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchrepnotagir(value){
	$('#dg-repnotagir').edatagrid('load',{
	notagirid:$('#repnotagir_search_notagirid').val(),    
        docdate:$('#repnotagir_search_docdate').val(),
        gireturid:$('#repnotagir_search_gireturno').val(),
        companyid:$('#repnotagir_search_companyname').val(),
        customer:$('#repnotagir_search_customer').val(),
        notagirno:$('#repnotagir_search_notagirno').val(),
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
function downpdfrepnotagir() {
	var ss = [];
	var rows = $('#dg-repnotagir').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.notagirid);
	}
	window.open('<?php echo $this->createUrl('notagir/downpdf') ?>?id='+ss);
}
