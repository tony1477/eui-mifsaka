<table id="dg-repcashbank" style="width:auto;height:400px"></table>
<div id="tb-repcashbank">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfrepcashbank()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchrepcashbank" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-repcashbank').edatagrid({
		singleSelect: false,
		toolbar:'#tb-repcashbank',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editrepcashbank(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-repcashbankacc"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvrepcashbankacc = $(this).datagrid('getRowDetail',index).find('table.ddv-repcashbankacc');
			ddvrepcashbankacc.datagrid({
				url:'<?php echo $this->createUrl('repcashbank/indexpay',array('grid'=>true)) ?>?id='+row.cashbankid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				pagination:true,
				loadMsg:'',
				height:'auto',
				width:'600px',
				columns:[[
					{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>'},
					{field:'cheqno',title:'<?php echo GetCatalog('cheqno') ?>'},
					{field:'tglterima',title:'<?php echo GetCatalog('tglterima') ?>'},
					{field:'tglcair',title:'<?php echo GetCatalog('tglcair') ?>'},
					{field:'debit',title:'<?php echo GetCatalog('debet') ?>'},
					{field:'credit',title:'<?php echo GetCatalog('credit') ?>'},
					{field:'currencyname',title:'<?php echo GetCatalog('currency') ?>'},
					{field:'currencyrate',title:'<?php echo GetCatalog('currencyrate') ?>'},
					{field:'bankaccountno',title:'<?php echo GetCatalog('bankaccountno') ?>'},
					{field:'bankname',title:'<?php echo GetCatalog('bankname') ?>'},
					{field:'bankowner',title:'<?php echo GetCatalog('accountowner') ?>'},
					{field:'description',title:'<?php echo GetCatalog('description') ?>'},
				]],
				onResize:function(){
						$('#dg-repcashbank').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-repcashbank').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-repcashbank').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('repcashbank/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-repcashbank').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'cashbankid',
		editing: false,
		columns:[[
		{
field:'cashbankid',
title:'<?php echo GetCatalog('cashbankid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
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
field:'cashbankno',
title:'<?php echo GetCatalog('cashbankno') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'companyid',
title:'<?php echo GetCatalog('companyname') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return row.companyname;
					}}, 
{
	field:'isin',
	title:'<?php echo GetCatalog('isin') ?>',
	align:'center',
	width:'100px',
	sortable: true,
	formatter: function(value,row,index){
						if (value == 1){
										return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
										return '';
						}
}},
{
field:'receiptno',
title:'<?php echo GetCatalog('receiptno') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'headernote',
title:'<?php echo GetCatalog('headernote') ?>',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'center',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchrepcashbank(value){
	$('#dg-repcashbank').edatagrid('load',{
	cashbankid:value,
	docdate:value,
	cashbankno:value,
	companyid:value,
	receiptno:value,
	docdate:value,
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
function downpdfrepcashbank() {
	var ss = [];
	var rows = $('#dg-repcashbank').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankid);
	}
	window.open('<?php echo $this->createUrl('repcashbank/downpdf') ?>?id='+ss);
}
function downxlsrepcashbank() {
	var ss = [];
	var rows = $('#dg-repcashbank').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.cashbankid);
	}
	window.open('<?php echo $this->createUrl('repcashbank/downxls') ?>?id='+ss);
}