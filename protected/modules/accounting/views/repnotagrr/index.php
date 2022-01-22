<table id="dg-repnotagrr" style="width:100%;height:97%"></table>
<div id="tb-repnotagrr">
<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfnotagrretur()"></a>
        <?php }?>
<table>
<tr>
<td><?php echo GetCatalog('notagrreturid')?></td>
<td><input class="easyui-textbox" id="repnotagrr_search_notagrreturid" style="width:150px"></td>
<td><?php echo GetCatalog('company')?></td>
<td><input class="easyui-textbox" id="repnotagrr_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('notagrreturno')?></td>
<td><input class="easyui-textbox" id="repnotagrr_search_notagrreturno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="repnotagrr_search_docdate" style="width:150px"></td>
<td><?php echo GetCatalog('grreturno')?></td>
<td><input class="easyui-textbox" id="repnotagrr_search_grreturno" style="width:150px"></td>
<td><?php echo GetCatalog('poheader')?></td>
<td><input class="easyui-textbox" id="repnotagrr_search_poheader" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('supplier')?></td>
<td><input class="easyui-textbox" id="repnotagrr_search_supplier" style="width:150px"></td>
<td><?php echo GetCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="repnotagrr_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchrepnotagrr()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repnotagrr_search_notagrreturid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagrr();
			}
		}
	})
});
$('#repnotagrr_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagrr();
			}
		}
	})
});
$('#repnotagrr_search_notagrreturno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagrr();
			}
		}
	})
});
$('#repnotagrr_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagrr();
			}
		}
	})
});
$('#repnotagrr_search_grreturno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagrr();
			}
		}
	})
});
$('#repnotagrr_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagrr();
			}
		}
	})
});
$('#repnotagrr_search_poheader').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagrr();
			}
		}
	})
});
$('#repnotagrr_search_supplier').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepnotagrr();
			}
		}
	})
});
$('#dg-repnotagrr').edatagrid({	
		singleSelect: false,
		toolbar:'#tb-repnotagrr',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		view: detailview,
                detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-notagrrpro"></table><table class="ddv-notagrracc"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvnotagrrpro = $(this).datagrid('getRowDetail',index).find('table.ddv-notagrrpro');
			var ddvnotagrracc = $(this).datagrid('getRowDetail',index).find('table.ddv-notagrracc');
			ddvnotagrrpro.datagrid({
				url:'<?php echo $this->createUrl('repnotagrr/indexproduct',array('grid'=>true)) ?>?id='+row.notagrreturid,
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
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>',align:'right'},
					{field:'total',title:'<?php echo GetCatalog('total') ?>',align:'right'},
				]],
				onResize:function(){
						$('#dg-repnotagrr').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repnotagrr').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
                        ddvnotagrracc.datagrid({
				url:'<?php echo $this->createUrl('repnotagrr/indexakun',array('grid'=>true)) ?>?id='+row.notagrreturid,
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
						$('#dg-repnotagrr').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repnotagrr').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repnotagrr').datagrid('fixDetailRowHeight',index);
                },
                url: '<?php echo $this->createUrl('repnotagrr/index',array('grid'=>true)) ?>',
                onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repnotagrr').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'notagrreturid',
		editing: false,
		columns:[[
		{
field:'notagrreturid',
title:'<?php echo GetCatalog('notagrreturid') ?>',
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
field:'notagrreturno',
title:'<?php echo GetCatalog('notagrreturno') ?>',
sortable: true,
width:'150px',
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
field:'grreturid',
title:'<?php echo GetCatalog('grreturno') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.grreturno;
					}},
					{
field:'poheaderid',
title:'<?php echo GetCatalog('poheader') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
						return row.pono;
					}},
										{
field:'addressbookid',
title:'<?php echo GetCatalog('supplier') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.fullname;
					}},
{
field:'recordstatusnotagrretur',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'left',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchrepnotagrr(value){
	$('#dg-repnotagrr').edatagrid('load',{
		notagrreturid:$('#repnotagrr_search_notagrreturid').val(),
		docdate:$('#repnotagrr_search_docdate').val(),
		grreturid:$('#repnotagrr_search_grreturno').val(),
		poheaderid:$('#repnotagrr_search_poheader').val(),
		addressbook:$('#repnotagrr_search_supplier').val(),
		company:$('#repnotagrr_search_companyname').val(),
		notagrreturno:$('#repnotagrr_search_notagrreturno').val(),
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
function downpdfnotagrretur() {
	var ss = [];
	var rows = $('#dg-repnotagrr').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.notagrreturid);
	}
	window.open('<?php echo $this->createUrl('repnotagrr/downpdf') ?>?id='+ss);
}
