<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-easyui/plugins/jquery.edatagrid.js"></script>
<!-- Data Grid ( #dg-<?php echo strtolower($this->modelClass); ?> ) -->
<table id="dg-<?php echo strtolower($this->modelClass); ?>" class="easyui-datagrid" style="width:800px;height:auto">
</table>
<div id="tb-<?php echo strtolower($this->modelClass); ?>">
	<?php echo "<?php if (\$this->CheckAccess(\$this->menuname, \$this->iswrite) == 1) {  ?>\n"?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('addRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('saveRow')"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onChange="javascript:$('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('cancelRow')"></a>
	<?php echo "<?php }?>\n";?>
	<?php echo "<?php if (\$this->CheckAccess(\$this->menuname, \$this->ispurge) == 1) {  ?>\n"?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('destroyRow')"></a>
<?php echo "<?php }?>\n";?>
	<?php echo "<?php if (\$this->CheckAccess(\$this->menuname, \$this->isdownload) == 1) {  ?>\n"?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdf<?php echo strtolower($this->modelClass); ?>()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxls<?php echo strtolower($this->modelClass); ?>()"></a>
<?php echo "<?php }?>\n";?>

	<input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:search<?php echo strtolower($this->modelClass); ?>" style="width:150px">
</div>

<script type="text/javascript">
$('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-<?php echo strtolower($this->modelClass); ?>',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoSave:true,
    url: <?php echo "'<?php echo \$this->createUrl('".$this->controller."/index',array('grid'=>true)) ?>'"?>,
    saveUrl: <?php echo "'<?php echo \$this->createUrl('".$this->controller."/save',array('grid'=>true)) ?>'"?>,
    updateUrl: <?php echo "'<?php echo \$this->createUrl('".$this->controller."/save',array('grid'=>true)) ?>'"?>,
    destroyUrl: <?php echo "'<?php echo \$this->createUrl('".$this->controller."/purge',array('grid'=>true)) ?>'"?>,
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
			$('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('reload');
		},
		<?php foreach($this->tableSchema->columns as $column)
		{
			if($column->autoIncrement)
			{
				echo "idField:'".$column->name."'\n";
			}
		}
		?>,
		editing: <?php echo "<?php echo (\$this->CheckAccess(\$this->menuname, \$this->iswrite) == 1 ? 'true' : 'false') ?>" ?>,
		columns:[[
		<?php foreach($this->tableSchema->columns as $column)
		{
			if($column->autoIncrement)
			{
				echo "{\n";
				echo "field:'".$column->name."',\n";
				echo "title:'<?php echo Catalogsys::model()->getCatalog('".$column->name."') ?>',\n";
				echo "width:30,\n";
				echo "sortable: true,\n";
				echo "formatter: function(value,row,index){
					return value;
					}";
				echo "},\n";
			}
			else
			if($column->name == 'recordstatus')
			{
				echo "{\n";
				echo "field:'".$column->name."',\n";
				echo "title:'<?php echo Catalogsys::model()->getCatalog('".$column->name."') ?>',\n";
				echo "width:80,\n";
				echo "align:'center',\n";
				echo "editor:{type:'checkbox',options:{on:'1',off:'0'}},\n";
				echo "sortable: true,\n";
				echo "formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"<?php echo Yii::app()->request->baseUrl?>/images/ok.png\"></img>';
						} else {
							return '';
						}
					}";
				echo "},\n";
			}
			else
			{
				echo "{\n";
				echo "field:'".$column->name."',\n";
				echo "title:'<?php echo Catalogsys::model()->getCatalog('".$column->name."') ?>',\n";
				echo "width:150,\n";
				echo "editor:'text',\n";
				echo "sortable: true,\n";
				echo "formatter: function(value,row,index){
						return value;
					}";
				echo "},\n";
			}	
		}
		?>
		]]
});
function search<?php echo strtolower($this->modelClass); ?>(value){
	$('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('load',{
	<?php foreach($this->tableSchema->columns as $column)
	{
			echo $column->name.":value,\n";
	}
	?>
	});
}
function downpdf<?php echo strtolower($this->modelClass); ?>() {
	var ss = [];
	var rows = $('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.<?php echo $this->getModelID() ?>);
	}
	window.open('<?php echo "<?php echo \$this->createUrl('".$this->controller."/downpdf') ?>?id='+ss" ?>);
}
function downxls<?php echo strtolower($this->modelClass); ?>() {
	var ss = [];
	var rows = $('#dg-<?php echo strtolower($this->modelClass); ?>').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.<?php echo $this->getModelID() ?>);
	}
	window.open('<?php echo "<?php echo \$this->createUrl('".$this->controller."/downxls') ?>?id='+ss" ?>);
}
</script>
