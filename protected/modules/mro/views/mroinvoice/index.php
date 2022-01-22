<!-- Data Grid ( #dg-mroinvoice ) -->
<table id="dg-mroinvoice" style="width:1200px;height:97%"></table>
<div id="tb-mroinvoice">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-mroinvoice').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-mroinvoice').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-mroinvoice').edatagrid('cancelRow')"></a>
	<?php }?>
				<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvemroinvoice()"></a>
<?php }?>
<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelmroinvoice()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-mroinvoice').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfmroinvoice()"></a>
<?php }?>
	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchmroinvoice" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-mroinvoice').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-mroinvoice',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
    url: '<?php echo $this->createUrl('mroinvoice/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('mroinvoice/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('mroinvoice/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('mroinvoice/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-mroinvoice').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-mroinvoicedetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvgidetail = $(this).datagrid('getRowDetail',index).find('table.ddv-mroinvoicedetail');
			ddvgidetail.datagrid({
				url:'<?php echo $this->createUrl('mroinvoice/indexdetail',array('grid'=>true)) ?>?id='+row.mrogiheaderid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo GetCatalog('pleasewait') ?>',
				height:'auto',
				showFooter:true,
				columns:[[
					{field:'productname',title:'<?php echo GetCatalog('productname') ?>'},
					{field:'uomcode',title:'<?php echo GetCatalog('unitofmeasure') ?>'},
					{field:'qty',title:'<?php echo GetCatalog('qty') ?>'},
					{field:'itemnote',title:'<?php echo GetCatalog('itemnote') ?>',width:'300px'},
				]],
				onResize:function(){
						$('#dg-giheader').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-giheader').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-giheader').datagrid('fixDetailRowHeight',index);
		},
		idField:'mroinvoiceid',
		editing: '<?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>',
		columns:[[
		{
field:'mroinvoiceid',
title:'<?php echo GetCatalog('mroinvoiceid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'mroinvoicedate',
title:'<?php echo GetCatalog('invoicedate') ?>',
width:'90px',
sortable: true,
formatter: function(value,row,index){
					return value;
					}},
{
field:'mroinvoiceno',
title:'<?php echo GetCatalog('invoiceno') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'mrogiheaderid',
title:'<?php echo GetCatalog('mrogino') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'mrogiheaderid',
						textField:'mrogino',
						url:'<?php echo Yii::app()->createUrl('mro/mrogi/indexmroinvoice',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'mrogiheaderid',title:'<?php echo GetCatalog('mrogiheaderid')?>'},
							{field:'mrogino',title:'<?php echo GetCatalog('mrogino')?>'},
							{field:'mrogidate',title:'<?php echo GetCatalog('mrogidate')?>'},
							{field:'shipto',title:'<?php echo GetCatalog('shipto')?>'},
						]]
				}	
			},
			width:'110px',
sortable: true,
formatter: function(value,row,index){
						return row.mrogino;
					}},
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
						required:true,
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
			width:'80px',
sortable: true,
formatter: function(value,row,index){
						return row.currencyname;
					}},					
					{
field:'currencyrate',
title:'<?php echo GetCatalog('currencyrate') ?>',
editor: {
	type: 'numberbox',
	precision:2,
	decimalSeparator:',',
	groupSeparator:'.',
	required:true,
},
width:'80px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'taxid',
title:'<?php echo GetCatalog('taxvalue') ?>',
width:'80px',
sortable: true,
formatter: function(value,row,index){
						return row.taxvalue;
					}},

					{
field:'amount',
title:'<?php echo GetCatalog('amount') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
										{
field:'payamount',
title:'<?php echo GetCatalog('payamount') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}}, 
					{
field:'headernote',
title:'<?php echo GetCatalog('headernote') ?>',
editor:'text',
width:'250px',
multiline:true,
required:true,
sortable: true,
formatter: function(value,row,index){
						return value;
					}},		
{
field:'recordstatus',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'left',
width:'100px',
sortable: true,
formatter: function(value,row,index){
						return row.recordstatus;
					}},
		]]
});
function searchmroinvoice(value){
	$('#dg-mroinvoice').edatagrid('load',{
	mroinvoiceid:value,
mroinvoicedate:value,
mroinvoiceno:value,
giheaderid:value,
headernote:value,
recordstatus:value,
	});
}
function approvemroinvoice() {
	var ss = [];
	var rows = $('#dg-mroinvoice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.mroinvoiceid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('mroinvoice/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-mroinvoice').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelmroinvoice() {
	var ss = [];
	var rows = $('#dg-mroinvoice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.mroinvoiceid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('mroinvoice/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-mroinvoice').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfmroinvoice() {
	var ss = [];
	var rows = $('#dg-mroinvoice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.mroinvoiceid);
	}
	window.open('<?php echo $this->createUrl('mroinvoice/downpdf') ?>?id='+ss);
}
function downxlsmroinvoice() {
	var ss = [];
	var rows = $('#dg-mroinvoice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.mroinvoiceid);
	}
	window.open('<?php echo $this->createUrl('mroinvoice/downxls') 

?>?id='+ss);
}
</script>
