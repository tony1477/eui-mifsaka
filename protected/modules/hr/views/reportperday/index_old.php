<table id="dg-reportperday" style="width:auto;height:400px">
</table>
<div id="tb-reportperday">
	<?php





 

 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-reportperday').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-reportperday').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-reportperday').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-reportperday').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreportperday()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsreportperday()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchreportperday" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-reportperday').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-reportperday',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:true,
    url: '<?php echo $this->createUrl('reportperday/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('reportperday/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('reportperday/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('reportperday/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-reportperday').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'reportperdayid'
,
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'reportperdayid',
title:'<?php echo getCatalog('reportperdayid') ?>',
sortable: true,
formatter: function(value,row,index){
					return value;
					}},
{
field:'employeeid',
title:'<?php echo getCatalog('employeeid') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'fullname',
title:'<?php echo getCatalog('fullname') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'oldnik',
title:'<?php echo getCatalog('oldnik') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'fulldivision',
title:'<?php echo getCatalog('fulldivision') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'absdate',
title:'<?php echo getCatalog('absdate') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'hourin',
title:'<?php echo getCatalog('hourin') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'hourout',
title:'<?php echo getCatalog('hourout') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'absscheduleid',
title:'<?php echo getCatalog('absscheduleid') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'schedulename',
title:'<?php echo getCatalog('schedulename') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'statusin',
title:'<?php echo getCatalog('statusin') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'statusout',
title:'<?php echo getCatalog('statusout') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'reason',
title:'<?php echo getCatalog('reason') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchreportperday(value){
	$('#dg-reportperday').edatagrid('load',{
	reportperdayid:value,
employeeid:value,
fullname:value,
oldnik:value,
fulldivision:value,
absdate:value,
hourin:value,
hourout:value,
absscheduleid:value,
schedulename:value,
statusin:value,
statusout:value,
reason:value,
	});
}
function downpdfreportperday() {
	var ss = [];
	var rows = $('#dg-reportperday').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reportperdayid);
	}
	window.open('<?php echo $this->createUrl('reportperday/downpdf') ?>?id='+ss);
}
function downxlsreportperday() {
	var ss = [];
	var rows = $('#dg-reportperday').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reportperdayid);
	}
	window.open('<?php echo $this->createUrl('reportperday/downxls') 

?>?id='+ss);
}
</script>