<!-- Data Grid ( #dg-fakturpajak ) -->
<table id="dg-fakturpajak" style="width:100%;height:97%"></table>
<div id="tb-fakturpajak">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-fakturpajak').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-fakturpajak').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-fakturpajak').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-fakturpajak').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdffakturpajak()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchfakturpajak" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-fakturpajak').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-fakturpajak',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:true,
    url: '<?php echo $this->createUrl('fakturpajak/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('fakturpajak/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('fakturpajak/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('fakturpajak/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-fakturpajak').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'fakturpajakid',
		editing: <?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'fakturpajakid',
title:'<?php echo GetCatalog('fakturpajakid') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
					return value;
					}},
					{
field:'companyname',
title:'<?php echo GetCatalog('company') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'fakturpajakno',
title:'<?php echo GetCatalog('fakturpajakno') ?>',
editor:'text',
sortable: true,
width:'150px',
required:true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'invoiceid',
title:'<?php echo GetCatalog('invoice') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'invoiceid',
						textField:'gino',
						url:'<?php echo Yii::app()->createUrl('accounting/invoicear/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'invoiceid',title:'<?php echo GetCatalog('invoiceid')?>'},
							{field:'companyname',title:'<?php echo GetCatalog('company')?>'},
							{field:'invoiceno',title:'<?php echo GetCatalog('invoiceno')?>'},
							{field:'gino',title:'<?php echo GetCatalog('gino')?>'},
							{field:'sono',title:'<?php echo GetCatalog('sono')?>'},
							{field:'fullname',title:'<?php echo GetCatalog('customer')?>'},
							{field:'headernote',title:'<?php echo GetCatalog('headernote')?>'},
						]]
				}	
			},
			width:'200px',
sortable: true,
formatter: function(value,row,index){
						return row.invoiceno;
					}},
					{
field:'fullname',
title:'<?php echo GetCatalog('customer') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchfakturpajak(value){
	$('#dg-fakturpajak').edatagrid('load',{
	fakturpajakid:value,
fakturpajakno:value,
invoiceid:value,
	});
}
function downpdffakturpajak() {
	var ss = [];
	var rows = $('#dg-fakturpajak').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.fakturpajakid);
	}
	window.open('<?php echo $this->createUrl('fakturpajak/downpdf') ?>?id='+ss);
}
function downxlsfakturpajak() {
	var ss = [];
	var rows = $('#dg-fakturpajak').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.fakturpajakid);
	}
	window.open('<?php echo $this->createUrl('fakturpajak/downxls') 

?>?id='+ss);
}
</script>
