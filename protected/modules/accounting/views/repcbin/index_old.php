<table id="dg-repcbin" style="width:100%;height:97%"></table>
<div id="tb-repcbin">
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepcbin()"></a>
<?php }?>
<table>
<tr>
<td><?php echo GetCatalog('cbinid')?></td>
<td><input class="easyui-textbox" id="repcbin_search_cbinid" style="width:150px"></td>
<td><?php echo GetCatalog('companyname')?></td>
<td><input class="easyui-textbox" id="repcbin_search_companyname" style="width:150px"></td>
<td><?php echo GetCatalog('cbinno')?></td>
<td><input class="easyui-textbox" id="repcbin_search_cbinno" style="width:150px"></td>
</tr>
<tr>
<td><?php echo GetCatalog('ttnt')?></td>
<td><input class="easyui-textbox" id="repcbin_search_ttntid" style="width:150px"></td>
<td><?php echo GetCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="repcbin_search_docdate" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchrepcbin()"></a></td>
</tr>
</table>
</div>

<script type="text/javascript">
$('#repcbin_search_cbinid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbin();
			}
		}
	})
});
$('#repcbin_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbin();
			}
		}
	})
});
$('#repcbin_search_cbinno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbin();
			}
		}
	})
});
$('#repcbin_search_ttntid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbin();
			}
		}
	})
});
$('#repcbin_search_docdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchrepcbin();
			}
		}
	})
});
$('#dg-repcbin').edatagrid({
		singleSelect: false,
		toolbar:'#tb-repcbin',
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
				return '<div style="padding:2px"><table class="ddv-repcbinjournal"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvrepcbinjournal = $(this).datagrid('getRowDetail',index).find('table.ddv-repcbinjournal');
			ddvrepcbinjournal.datagrid({
				url:'<?php echo $this->createUrl('repcbin/indexjournal',array('grid'=>true)) ?>?id='+row.cbinid,
				fitColumns:true,
				singleSelect:true,
				pagination:true,
				rownumbers:true,
				loadMsg:'',
				title:'Journal Detail',
				height:'auto',
				width:'100%',
				showFooter:true,
				columns:[[
				{field:'accountname',title:'<?php echo GetCatalog('account') ?>',width:'250px'},
				{field:'debit',title:'<?php echo GetCatalog('debit') ?>',align:'right',width:'100px'},			
				{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>',width:'100px'},
				{field:'currencyrate',title:'<?php echo GetCatalog('rate') ?>',align:'right',width:'80px'},
				{field:'chequeno',title:'<?php echo GetCatalog('chequeno') ?>'},
				{field:'tglcair',title:'<?php echo GetCatalog('tglcair') ?>'},
				{field:'description',title:'<?php echo GetCatalog('description') ?>',width:'250px'},
				]],
				onResize:function(){
						$('#dg-repcbin').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repcbin').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repcbin').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('repcbin/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repcbin').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cbinid',
		editing: false,
		columns:[[
		{
field:'cbinid',
title:'<?php echo GetCatalog('cbinid') ?>',
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
field:'cbinno',
title:'<?php echo GetCatalog('cbinno') ?>',
sortable: true,
width:'180px',
formatter: function(value,row,index){
						return row.cbinno;
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
field:'ttntid',
title:'<?php echo GetCatalog('ttntno') ?>',
editor:'text',
width:'120px',
sortable: true,
formatter: function(value,row,index){
						return row.docno;
					}},
{
field:'recordstatuscbin',
title:'<?php echo GetCatalog('recordstatus') ?>',
width:'200px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchrepcbin(value){
	$('#dg-repcbin').edatagrid('load',{
	cbinid:$('#repcbin_search_cbinid').val(),
        ttntid:$('#repcbin_search_ttntid').val(),
        docdate:$('#repcbin_search_docdate').val(),
        cbinno:$('#repcbin_search_cbinno').val(),
        companyid:$('#repcbin_search_companyname').val(),
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
function downpdfrepcbin() {
	var ss = [];
	var rows = $('#dg-repcbin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbinid);
	}
	window.open('<?php echo $this->createUrl('repcbin/downpdf') ?>?id='+ss);
}
function downxlsrepcbin() {
	var ss = [];
	var rows = $('#dg-repcbin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cbinid);
	}
	window.open('<?php echo $this->createUrl('repcbin/downxls') ?>?id='+ss);
}
