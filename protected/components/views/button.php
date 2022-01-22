<?php if ($this->formtype == 'master') { ?>
<div id="tb-<?php echo $this->menuname?>">
	<?php if ($this->iswrite == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
			<a id="add-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('add')?>" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add<?php echo $this->menuname?>()"></a>
			<a id="save-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('save')?>" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="save<?php echo $this->menuname?>()"></a>
			<a id="cancel-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('cancel')?>" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="cancel<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php }?>
	<?php if ($this->ispurge == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'ispurge') == 1) {?>
			<a id="purge-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('purge')?>" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="purge<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php } ?>
	<?php if ($this->isdownload == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isdownload') == 1) {?>
			<a id="pdf-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('downpdf')?>" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdf<?php echo $this->menuname?>()"></a>
			<a id="xls-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('downxls')?>" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxls<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php }?>
	<?php if ($this->isupload == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isupload') == 1) {?>
			<form id="form-<?php echo $this->menuname?>" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
				<input type="file" name="file-<?php echo $this->menuname?>" id="file-<?php echo $this->menuname?>" style="display:inline">
				<input type="submit" value="<?php echo getCatalog('uploaddata')?>" id="submit-<?php echo $this->menuname?>" style="display:inline">
			</form>
		<?php }?>
	<?php }?>
	<input id="search-<?php echo $this->menuname?>" class="easyui-searchbox" data-options="prompt:'<?php echo getCatalog('searchdata')?>',searcher:search<?php echo $this->menuname?>" style="width:200px">
</div>
<?php } ?>
<script>
$("#form-<?php echo $this->menuname?>").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl($this->uploadurl) ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
function add<?php echo $this->menuname?>() {
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('addRow');
}
function save<?php echo $this->menuname?>() {
	$('#dg-<?php echo $this->menuname?>').edatagrid('saveRow');
}
function cancel<?php echo $this->menuname?>() {
	$('#dg-<?php echo $this->menuname?>').edatagrid('cancelRow');
}
function purge<?php echo $this->menuname?>() {
	$('#dg-<?php echo $this->menuname?>').edatagrid('destroyRow');
}
</script>