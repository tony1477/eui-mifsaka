<table id="dg-repekspedisi" style="width:100%;height:97%"></table>
<div id="tb-repekspedisi">	
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepekspedisi()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchrepekspedisi" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-repekspedisi').edatagrid({
		singleSelect: false,
		toolbar:'#tb-repekspedisi',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editrepekspedisi(index);
		},
    view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-repekspedisipo"></table><table class="ddv-eksmat"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvrepekspedisipo = $(this).datagrid('getRowDetail',index).find('table.ddv-repekspedisipo');
			var ddveksmat = $(this).datagrid('getRowDetail',index).find('table.ddv-eksmat');
			ddvrepekspedisipo.datagrid({
				url:'<?php echo $this->createUrl('repekspedisi/indexpo',array('grid'=>true)) ?>?id='+row.ekspedisiid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				pagination:true,
				loadMsg:'',
				height:'auto',
				width:'600px',
				columns:[[
					{field:'pono',title:'<?php echo GetCatalog('pono') ?>'},
          {field:'docdate',title:'<?php echo GetCatalog('docdate') ?>'},
					{field:'supplier',title:'<?php echo GetCatalog('supplier') ?>'},
				]],
				onResize:function(){
						$('#dg-repekspedisi').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repekspedisi').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			ddveksmat.datagrid({
				url:'<?php echo $this->createUrl('repekspedisi/indexmaterial',array('grid'=>true)) ?>?id='+row.ekspedisiid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				pagination:true,
				loadMsg:'',
				height:'auto',
				width:'800px',
				columns:[[
					{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo GetCatalog('qty') ?>'},
					{field:'uomcode',title:'<?php echo GetCatalog('uomcode') ?>'},
					{field:'expense',title:'<?php echo GetCatalog('expense') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currencyname') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('rate') ?>'},
				]],
				onResize:function(){
						$('#dg-repekspedisi').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repekspedisi').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repekspedisi').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('repekspedisi/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repekspedisi').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'ekspedisiid',
		editing: false,
		columns:[[
		{
field:'ekspedisiid',
title:'<?php echo GetCatalog('ekspedisiid') ?>',
sortable: true,
width:'80px',
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
field:'companyid',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
					return row.companyname;
					}},
					{
field:'ekspedisino',
title:'<?php echo GetCatalog('ekspedisino') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'addressbookid',
title:'<?php echo GetCatalog('repekspedisi') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.supplier;
					}},
{
field:'amount',
title:'<?php echo GetCatalog('amount') ?>',
editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return value;
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
						return value;
					}},
{
field:'recordstatusekspedisi',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'center',
width:'120px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchrepekspedisi(value){
	$('#dg-repekspedisi').edatagrid('load',{
	ekspedisiid:value,
	ekspedisino:value,
        docdate:value,
        addressbookid:value,
        amount:value,
        currencyid:value,
        currencyrate:value,
        recordstatus:value,
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
function downpdfrepekspedisi() {
	var ss = [];
	var rows = $('#dg-repekspedisi').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ekspedisiid);
	}
	window.open('<?php echo $this->createUrl('repekspedisi/downpdf') ?>?id='+ss);
}
function downxlsrepekspedisi() {
	var ss = [];
	var rows = $('#dg-repekspedisi').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.ekspedisiid);
	}
	window.open('<?php echo $this->createUrl('repekspedisi/downxls') ?>?id='+ss);
}

</script>
