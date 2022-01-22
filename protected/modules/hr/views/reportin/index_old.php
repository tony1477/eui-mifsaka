<table id="dg-reportin" style="width:auto;height:400px">
</table>
<div id="tb-reportin">
	<?php





 

 if (checkaccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-reportin').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-reportin').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-reportin').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (checkaccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-reportin').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (checkaccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreportin()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsreportin()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchreportin" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-reportin').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-reportin',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:true,
    url: '<?php echo $this->createUrl('reportin/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('reportin/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('reportin/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('reportin/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-reportin').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'reportinid'
,
		editing: <?php echo (checkaccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>,
		columns:[[
		{
field:'reportinid',
title:'<?php echo getCatalog('reportinid') ?>',
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
field:'month',
title:'<?php echo getCatalog('month') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'year',
title:'<?php echo getCatalog('year') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s1',
title:'<?php echo getCatalog('s1') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d1',
title:'<?php echo getCatalog('d1') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s2',
title:'<?php echo getCatalog('s2') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d2',
title:'<?php echo getCatalog('d2') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s3',
title:'<?php echo getCatalog('s3') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d3',
title:'<?php echo getCatalog('d3') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s4',
title:'<?php echo getCatalog('s4') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d4',
title:'<?php echo getCatalog('d4') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s5',
title:'<?php echo getCatalog('s5') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d5',
title:'<?php echo getCatalog('d5') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s6',
title:'<?php echo getCatalog('s6') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d6',
title:'<?php echo getCatalog('d6') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s7',
title:'<?php echo getCatalog('s7') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d7',
title:'<?php echo getCatalog('d7') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s8',
title:'<?php echo getCatalog('s8') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d8',
title:'<?php echo getCatalog('d8') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s9',
title:'<?php echo getCatalog('s9') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d9',
title:'<?php echo getCatalog('d9') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s10',
title:'<?php echo getCatalog('s10') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d10',
title:'<?php echo getCatalog('d10') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s11',
title:'<?php echo getCatalog('s11') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d11',
title:'<?php echo getCatalog('d11') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s12',
title:'<?php echo getCatalog('s12') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d12',
title:'<?php echo getCatalog('d12') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s13',
title:'<?php echo getCatalog('s13') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d13',
title:'<?php echo getCatalog('d13') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s14',
title:'<?php echo getCatalog('s14') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d14',
title:'<?php echo getCatalog('d14') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s15',
title:'<?php echo getCatalog('s15') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d15',
title:'<?php echo getCatalog('d15') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s16',
title:'<?php echo getCatalog('s16') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d16',
title:'<?php echo getCatalog('d16') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s17',
title:'<?php echo getCatalog('s17') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d17',
title:'<?php echo getCatalog('d17') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s18',
title:'<?php echo getCatalog('s18') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d18',
title:'<?php echo getCatalog('d18') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s19',
title:'<?php echo getCatalog('s19') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d19',
title:'<?php echo getCatalog('d19') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s20',
title:'<?php echo getCatalog('s20') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d20',
title:'<?php echo getCatalog('d20') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s21',
title:'<?php echo getCatalog('s21') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d21',
title:'<?php echo getCatalog('d21') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s22',
title:'<?php echo getCatalog('s22') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d22',
title:'<?php echo getCatalog('d22') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s23',
title:'<?php echo getCatalog('s23') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d23',
title:'<?php echo getCatalog('d23') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s24',
title:'<?php echo getCatalog('s24') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d24',
title:'<?php echo getCatalog('d24') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s25',
title:'<?php echo getCatalog('s25') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d25',
title:'<?php echo getCatalog('d25') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s26',
title:'<?php echo getCatalog('s26') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d26',
title:'<?php echo getCatalog('d26') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s27',
title:'<?php echo getCatalog('s27') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d27',
title:'<?php echo getCatalog('d27') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s28',
title:'<?php echo getCatalog('s28') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d28',
title:'<?php echo getCatalog('d28') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s29',
title:'<?php echo getCatalog('s29') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d29',
title:'<?php echo getCatalog('d29') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s30',
title:'<?php echo getCatalog('s30') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d30',
title:'<?php echo getCatalog('d30') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'s31',
title:'<?php echo getCatalog('s31') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'d31',
title:'<?php echo getCatalog('d31') ?>',
editor:'text',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchreportin(value){
	$('#dg-reportin').edatagrid('load',{
	reportinid:value,
employeeid:value,
fullname:value,
oldnik:value,
fulldivision:value,
month:value,
year:value,
s1:value,
d1:value,
s2:value,
d2:value,
s3:value,
d3:value,
s4:value,
d4:value,
s5:value,
d5:value,
s6:value,
d6:value,
s7:value,
d7:value,
s8:value,
d8:value,
s9:value,
d9:value,
s10:value,
d10:value,
s11:value,
d11:value,
s12:value,
d12:value,
s13:value,
d13:value,
s14:value,
d14:value,
s15:value,
d15:value,
s16:value,
d16:value,
s17:value,
d17:value,
s18:value,
d18:value,
s19:value,
d19:value,
s20:value,
d20:value,
s21:value,
d21:value,
s22:value,
d22:value,
s23:value,
d23:value,
s24:value,
d24:value,
s25:value,
d25:value,
s26:value,
d26:value,
s27:value,
d27:value,
s28:value,
d28:value,
s29:value,
d29:value,
s30:value,
d30:value,
s31:value,
d31:value,
	});
}
function downpdfreportin() {
	var ss = [];
	var rows = $('#dg-reportin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reportinid);
	}
	window.open('<?php echo $this->createUrl('reportin/downpdf') ?>?id='+ss);
}
function downxlsreportin() {
	var ss = [];
	var rows = $('#dg-reportin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.reportinid);
	}
	window.open('<?php echo $this->createUrl('reportin/downxls') 

?>?id='+ss);
}
</script>