<!-- Data Grid ( #dg-product ) -->
<table id="dg-standardopoutput"  style="width:100%;height:100%"></table>
<div id="tb-standardopoutput">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-standardopoutput').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-standardopoutput').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-standardopoutput').edatagrid('cancelRow')"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispurge) == 1) {  ?>
		<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-standardopoutput').edatagrid('destroyRow')"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfstandardopoutput()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsstandardopoutput()"></a>
<?php }?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:searchstandardopoutput" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-standardopoutput').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-standardopoutput',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:false,
    url: '<?php echo $this->createUrl('standardopoutput/index',array('grid'=>true)) ?>',
    saveUrl: '<?php echo $this->createUrl('standardopoutput/save',array('grid'=>true)) ?>',
    updateUrl: '<?php echo $this->createUrl('standardopoutput/save',array('grid'=>true)) ?>',
    destroyUrl: '<?php echo $this->createUrl('standardopoutput/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-standardopoutput').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'standardopoutputid',
		editing: '<?php echo (CheckAccess($this->menuname, $this->iswrite) == 1 ? 'true' : 'false') ?>',
		columns:[[
		{
field:'standardopoutputid',
title:'<?php echo GetCatalog('standardopoutputid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'slocid',
title:'<?php echo GetCatalog('sloccode') ?>',
editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						loadMsg: '<?php echo GetCatalog('pleasewait')?>',
						columns:[[
							{field:'slocid',title:'<?php echo GetCatalog('slocid')?>'},
							{field:'sloccode',title:'<?php echo GetCatalog('sloccode')?>'},
							{field:'description',title:'<?php echo GetCatalog('description')?>'},
						]]
				}	
			},
width:'300px',
sortable: true,
formatter: function(value,row,index){
						return row.slocdesc; 
					}},
{
field:'groupname',
title:'<?php echo GetCatalog('groupname') ?>',
editor:'text',
width:'250px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'standardvalue',
title:'<?php echo GetCatalog('standardvalue') ?>',
editor:'text',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'cycletime',
title:'<?php echo GetCatalog('cycletime') ?>',
editor:'text',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'price',
title:'<?php echo GetCatalog('price') ?>',
editor:'text',
width:'150px',
sortable: true,
formatter: function(value,row,index){
						return value;
					}},
{
field:'recordstatus',
title:'<?php echo GetCatalog('recordstatus') ?>',
align:'center',
width:'50px',
editor:{type:'checkbox',options:{on:'1',off:'0'}},
sortable: true,
formatter: function(value,row,index){
						if (value == 1){
							return '<img src="<?php echo Yii::app()->request->baseUrl?>/images/ok.png"></img>';
						} else {
							return '';
						}
					}},
		]]
});
function searchstandardopoutput(value){
	$('#dg-standardopoutput').edatagrid('load',{
	standardopoutputid:value,
    sloccode:value,
    groupname:value,

	});
}
function downpdfstandardopoutput() {
	var ss = [];
	var rows = $('#dg-standardopoutput').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.standardopoutputid);
	}
	window.open('<?php echo $this->createUrl('standardopoutput/downpdf') ?>?id='+ss);
}
function downxlsstandardopoutput() {
	var ss = [];
	var rows = $('#dg-standardopoutput').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.standardopoutputid);
	}
	window.open('<?php echo $this->createUrl('standardopoutput/downxls') 

?>?id='+ss);
}
</script>
