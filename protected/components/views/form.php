<?php if (Yii::app()->user->id !== '') { ?>
<?php if ($this->formtype == 'master') { ?>
<table id="dg-<?php echo $this->menuname?>" style="width:100%;height:100%"></table>
<div id="tb-<?php echo $this->menuname?>">
	<?php if ($this->iswrite == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
			<a id="add-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('add')?>" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add<?php echo $this->menuname?>()"></a>
			<a id="save-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('save')?>" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="save<?php echo $this->menuname?>()"></a>
			<a id="cancel-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('cancel')?>" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="cancel<?php echo $this->menuname?>()"></a>
			<?php echo $this->writebuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->ispost == 1) { ?>
		<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
			<a href="javascript:void(0)" title="<?php echo getCatalog('approve')?>"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approve<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php }?>
	<?php if ($this->isreject == 1) { ?>
		<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
			<a id="reject-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('reject')?>"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="reject<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php }?>
	<?php if ($this->ispurge == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'ispurge') == 1) {?>
			<a id="purge-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('purge')?>" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="purge<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php } ?>
	<?php if ($this->isdownload == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isdownload') == 1) {?>
			<?php if ($this->ispdf == 1) { ?>
				<a id="pdf-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('downpdf')?>" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdf<?php echo $this->menuname?>()"></a>
			<?php }?>
			<?php if ($this->isxls == 1) { ?>
				<a id="xls-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('downxls')?>" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxls<?php echo $this->menuname?>()"></a>
			<?php }?>
			<?php echo $this->downloadbuttons?>
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
	<a href="javascript:void(0)" title="<?php echo getCatalog('search')?>" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="search<?php echo $this->menuname?>()"></a>
	<?php echo $this->otherbuttons?>
	<table>
	<?php 
	$i=0;$searchscript='';$searchgridscript='';$searcharray='';
	foreach ($this->searchfield as $field) {
		if ($i == 0) {
			echo '<tr>';
		}
		echo '<td>'.getCatalog($field).'</td>';
		echo '<td><input class="easyui-textbox" id="'.$this->menuname.'_search_'.$field.'" style="width:150px"></td>';
		$i++;
		if (($i % 3) == 0) {
			echo '</tr>';
			$i=0;
		}			
		$searchscript .= "$('#".$this->menuname."_search_".$field."').textbox({
				inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
					keyup:function(e){
						if (e.keyCode == 13) {
							search".$this->menuname."();
						}
					}
				})
			});";
		$searchgridscript .= $field.":$('#".$this->menuname."_search_".$field."').val(),";
		$searcharray .= "\n+'&".$field."='+$('#".$this->menuname."_search_".$field."').textbox('getValue')";
	}	
	?>
	<?php echo $this->addonsearchfield?>
	</table>
</div>
<script type="text/javascript">
<?php echo $searchscript;?>
$('#dg-<?php echo $this->menuname?>').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	toolbar:'#tb-<?php echo $this->menuname?>',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoSave:false,
	url: '<?php echo $this->url?>',
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
	editing: true,
	<?php } else {?>
	editing: false,
	<?php }?>
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
	saveUrl: '<?php echo $this->saveurl?>',
	updateUrl: '<?php echo $this->updateurl?>',
	<?php }?>
	<?php if (CheckAccess($this->menuname, 'ispurge') == 1) {?>
	destroyUrl: '<?php echo $this->destroyurl?>',
	<?php }?>
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
	},
	<?php if ($this->beginedit != '') { ?>
	onBeginEdit: function(index,row){
		<?php echo $this->beginedit;?>
	},
	<?php } ?>
	onError: function(index,row){
		show('Pesan',row.msg,'error');
		$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
	},
	<?php }?>
	idField: '<?php echo $this->idfield?>',
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
	editing: true,
	<?php } else {?>
	editing: false,
	<?php }?>	
	<?php if ($this->rowstyler != '') {?>
	rowStyler: function(index,row){
		<?php echo $this->rowstyler;?>
	},
	<?php }?>
	columns:[[ <?php echo $this->columns; ?> ]]
});
$("#form-<?php echo $this->menuname?>").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo $this->uploadurl ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
<?php if ($this->iswrite == 1) { ?>
function add<?php echo $this->menuname?>() {
	$('#dg-<?php echo $this->menuname?>').edatagrid('addRow');
}
function save<?php echo $this->menuname?>() {
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('saveRow');
	closeloader();
}
function cancel<?php echo $this->menuname?>() {
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('cancelRow');
	closeloader();
}
<?php }?>
<?php if ($this->ispost == 1) { ?>
function approve<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelections');
	for (var i=0; i<rows.length; i++) {
			var row = rows[i];
			ss.push(row.<?php echo $this->idfield?>);
	}
	jQuery.ajax({'url':'<?php echo $this->approveurl ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data) {
			show('Pesan',data.msg);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
		} ,
		'cache':false});
};
<?php }?>
<?php if ($this->isreject == 1) { ?>
function reject<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	jQuery.ajax({'url':'<?php echo $this->rejecturl ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data) {
			show('Pesan',data.msg);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
		} ,
		'cache':false});
};
<?php }?>
<?php if ($this->ispurge == 1) { ?>
function purge<?php echo $this->menuname?>() {
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('destroyRow');
	closeloader();
}
<?php }?>
function search<?php echo $this->menuname?>(value,name){
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('load',{
		<?php echo $searchgridscript?>});
	closeloader();
}
<?php if ($this->isdownload == 1) { ?>
function downpdf<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downpdf?>?'+array);
}
function downxls<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downxls?>?'+array);
}
<?php }?>
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
<?php echo $this->addonscripts?>
</script>
<?php } else ?>
<?php if ($this->formtype == 'masterdetail') { ?>
<table id="dg-<?php echo $this->menuname?>"  style="width:100%;height:100%"></table>
<div id="tb-<?php echo $this->menuname?>">
	<?php if ($this->iswrite == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {  ?>
			<a id="add-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('add')?>" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add<?php echo $this->menuname?>()"></a>
			<a id="edit-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('edit')?>" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit<?php echo $this->menuname?>()"></a>
			<?php echo $this->writebuttons?>
		<?php }?>
	<?php }?>
	<?php if ($this->ispost == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'ispost') == 1) {  ?>
			<a href="javascript:void(0)" title="<?php echo getCatalog('approve')?>"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approve<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php }?>
	<?php if ($this->isreject == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isreject') == 1) {  ?>
			<a href="javascript:void(0)" title="<?php echo getCatalog('reject')?>"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php }?>
	<?php if ($this->ispurge == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'ispurge') == 1) {  ?>
			<a id="purge-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('purge')?>" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="purge<?php echo $this->menuname?>()"></a>
		<?php }?>
	<?php }?>
	<?php if ($this->isdownload == 1) { ?>
		<?php if (CheckAccess($this->menuname, 'isdownload') == 1) {  ?>
		<?php if ($this->ispdf == 1) { ?>
			<a id="pdf-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('downpdf')?>"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdf<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php if ($this->isxls == 1) { ?>
			<a id="xls-<?php echo $this->menuname?>" href="javascript:void(0)" title="<?php echo getCatalog('downxls')?>"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxls<?php echo $this->menuname?>()"></a>
		<?php }?>
		<?php echo $this->downloadbuttons?>
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
    <?php if ($this->isupload == 1 && $this->uploadurl2 != '') { ?>
		<?php if (CheckAccess($this->menuname, 'isupload') == 1) {?>
			<br />
            <?php echo $this->upload2text?> <form id="form2-<?php echo $this->menuname?>" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
				<input type="file" name="file2-<?php echo $this->menuname?>" id="file2-<?php echo $this->menuname?>" style="display:inline">
				<input type="submit" value="<?php echo getCatalog('uploaddata')?>" id="submit-<?php echo $this->menuname?>" style="display:inline">
			</form>
		<?php }?>
	<?php }?>
	<?php echo $this->otherbuttons?>
	<a href="javascript:void(0)" title="<?php echo getCatalog('search')?>" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="search<?php echo $this->menuname?>()"></a>
	<table>
	<?php 
	$i=0;$searchscript='';$searchgridscript='';$searcharray='';
	foreach ($this->searchfield as $field) {
		if ($i == 0) {
			echo '<tr>';
		}
		echo '<td>'.getCatalog($field).'</td>';
		echo '<td><input class="easyui-textbox" id="'.$this->menuname.'_search_'.$field.'" style="width:150px"></td>';
		$i++;
		if (($i % 3) == 0) {
			echo '</tr>';
			$i=0;
		}			
		$searchscript .= " $('#".$this->menuname."_search_".$field."').textbox({
			inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
				keyup:function(e){
					if (e.keyCode == 13) {
						search".$this->menuname."();
					}
				}
			})
		});";
		$searchgridscript .= $field.":$('#".$this->menuname."_search_".$field."').val(),";
		$searcharray .= "\n+'&".$field."='+$('#".$this->menuname."_search_".$field."').textbox('getValue')";
	}	
	?>
	<?php echo $this->addonsearchfield?>
	</table>
</div>
<?php if ($this->iswrite == 1) { ?>
<div id="dlg-<?php echo $this->menuname?>" class="easyui-dialog" title="<?php echo getCatalog($this->menuname)?>" style="width:auto;height:550px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			id:'save-<?php echo $this->menuname?>',
			iconCls:'icon-save',
			handler:function(){
					submitform<?php echo $this->menuname?>();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			id:'cancel-<?php echo $this->menuname?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-<?php echo $this->menuname?>').dialog('close');
			}
		},
	]	
	">
	<form id="ff-<?php echo $this->menuname?>-modif" method="post" data-options="novalidate:true">
	<?php echo $this->headerform?>
	</form>
	<div class="easyui-tabs" style="width:auto;height:auto" id="tabdetails-<?php echo $this->menuname?>">
		<?php $i=0; foreach ($this->columndetails as $detail) {?>
			<div title="<?php echo getCatalog($detail['id'])?>" style="padding:5px" >
				<table class="mytable easyui-edatagrid" id="dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>" style="width:100%;height:350px"></table>			
				<div id="tb-<?php echo $this->menuname?>-<?php echo $detail['id']?>">
				<?php $a = (isset($detail['isnew'])?$detail['isnew']:1); if ($a == 1) { ?>
					<a id="adddetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title="<?php echo getcatalog('add')?>" class="adddetail easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('addRow')"></a>
				<?php }?>
				<?php $a = (isset($detail['iswrite'])?$detail['iswrite']:1); if ($a == 1) { ?>
					<a id="savedetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title="<?php echo getcatalog('save')?>" class="savedetail easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('saveRow')"></a>
				<?php }?>
				<?php $a = (isset($detail['iswrite'])?$detail['iswrite']:1); if ($a == 1) { ?>
					<a id="canceldetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title="<?php echo getcatalog('cancel')?>"class="canceldetail easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('cancelRow')"></a>
				<?php }?>
				<?php $a = (isset($detail['ispurge'])?$detail['ispurge']:1); if ($a == 1) { ?>
					<a id="purgedetail-<?php echo $this->menuname?>-<?php echo $detail['id']?>" href="#" title="<?php echo getcatalog('purge')?>"class="purgedetail easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('destroyRow')"></a>
				<?php }?>
				</div>
			</div>			
		<?php $i++;}?>
	</div>
</div>
<?php } ?>
<script type="text/javascript">
<?php echo $searchscript;?>
$("#form-<?php echo $this->menuname?>").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo $this->uploadurl ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$("#form2-<?php echo $this->menuname?>").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo $this->uploadurl2 ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#dg-<?php echo $this->menuname?>').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	toolbar:'#tb-<?php echo $this->menuname?>',
	pagination: true,	
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {  ?>
	onDblClickRow:function (index,row) {
		edit<?php echo $this->menuname?>(index);
	},
	<?php }?>
	fitColumns:true,
	ctrlSelect:true,
	autoRowHeight:true,
	view: detailview,
	detailFormatter:function(index,row){
		return '<div style="padding:2px">'+
			<?php foreach ($this->columndetails as $detail) { ?>
			'<table class="ddv-<?php echo $this->menuname?>-<?php echo $detail['id']?>"></table>'+
			<?php }?>
			'</div>';
	},
	onExpandRow: function(index,row){
		<?php $i=0;foreach ($this->columndetails as $detail) { ?>
		var ddv<?php echo $detail['id']?> = $(this).datagrid('getRowDetail',index).find('table.ddv-<?php echo $this->menuname?>-<?php echo $detail['id']?>');
		ddv<?php echo $detail['id']?>.datagrid({
			url:'<?php echo $detail['urlsub'] ?>?id='+row.<?php echo $this->idfield?>,
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:'<?php echo GetCatalog('pleasewait') ?>',
			height:'auto',
			showFooter:true,
			pagination:true,
			onSelect:function(index,row){
				<?php echo (isset($detail['onselectsub'])?$detail['onselectsub']:'')?>
			},
			columns:[[ <?php echo (isset($detail['subs'])?$detail["subs"]:'')?> ]],
			onResize:function(){
				$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
				setTimeout(function(){
					$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
				},0);
			}
		});
		<?php $i++; } ?>
		$('#dg-<?php echo $this->menuname?>').datagrid('fixDetailRowHeight',index);
	},
	url: '<?php echo $this->url?>',
	destroyUrl: '<?php echo $this->destroyurl?>',
	idField: '<?php echo $this->idfield;?>',
	<?php if ($this->rowstyler != '') {?>
	rowStyler: function(index,row) {
		<?php echo $this->rowstyler;?>
	},
	<?php }?>
	columns:[[
	{field:'_expander',expander:true,width:24,fixed:true}, 
	<?php echo $this->columns?>
	]]
});
<?php if ($this->iswrite == 1) { ?>
function add<?php echo $this->menuname?>() {
	jQuery.ajax({'url':'<?php echo $this->urlgetdata ?>',
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.<?php echo $this->idfield?> != undefined)  {
				$('#dlg-<?php echo $this->menuname?>').dialog('open');
				$('#ff-<?php echo $this->menuname?>-modif').form('clear');
				$('#<?php echo $this->idfield?>').val(data.<?php echo $this->idfield?>);
				$('#ff-<?php echo $this->menuname?>-modif').form('load','<?php echo $this->url?>');
				<?php echo $this->addload ?>
			} else {
				show('Pesan','Server sedang sibuk, tunggu 1 menit kemudian ulangi kembali');
			}
		} ,
		'cache':false});
}
function edit<?php echo $this->menuname?>($i) {
	openloader();
	var row = $('#dg-<?php echo $this->menuname?>').datagrid('getSelected');
	<?php if ($this->ispost == 1) { ?>
	var docmax = <?php echo CheckDoc($this->wfapp) ?>;
	var docstatus = row.recordstatus;
	<?php }?>
	if (row) {
		<?php if ($this->ispost == 1) { ?>
		if (docstatus == docmax) {
			show('Pesan','<?php echo GetCatalog('docreachmaxstatus')?>');
			closeloader();
		} else {
			closeloader();
			$('#dlg-<?php echo $this->menuname?>').dialog('open');
			$('#ff-<?php echo $this->menuname?>-modif').form('load',row);
			<?php echo $this->customjs?>
		}	<?php } else {?>
			closeloader();			
			$('#dlg-<?php echo $this->menuname?>').dialog('open');
			$('#ff-<?php echo $this->menuname?>-modif').form('load',row);
			<?php echo $this->customjs?>
		<?php }?>
	} else {
		closeloader();			
		show('Pesan','<?php echo getcatalog('chooseone')?>');
	}
};
function submitform<?php echo $this->menuname?>(){
	openloader();
	$('#ff-<?php echo $this->menuname?>-modif').form('submit',{
		url:'<?php echo $this->saveurl ?>',
		onSubmit:function(){
			return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			closeloader();
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg);
			if (data.isError == false){
        $('#dg-<?php echo $this->menuname?>').datagrid('reload');
        $('#dlg-<?php echo $this->menuname?>').dialog('close');
			}
    }
	});	
};
function clearform<?php echo $this->menuname?>(){
	$('#ff-<?php echo $this->menuname?>-modif').form('clear');
};
function cancelform<?php echo $this->menuname?>(){
	$('#dlg-<?php echo $this->menuname?>').dialog('close');
};
$('#ff-<?php echo $this->menuname?>-modif').form({
	onLoadSuccess: function(data) {
		<?php foreach ($this->columndetails as $detail) {?>
		$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').datagrid({
			queryParams: {
				id: $('#<?php echo $this->idfield?>').val()
			}
		});
		<?php }?>
        if (data.isin == 1)
        {
                $('#isin').prop('checked', true);
        } else
        {
                $('#isin').prop('checked', false);
        }
        if (data.isover == 1)
        {
                $('#isover').prop('checked', true);
        } else
        {
                $('#isover').prop('checked', false);
        }
		<?php echo $this->loadsuccess?>
        if($('#isover').is(':checked')==true) {
            $("#ctover").show();
        }
        else
        {
            $("#ctover").hide();
        }
	}
});
<?php }?>
function search<?php echo $this->menuname?>(value,name){
	openloader();
	$('#dg-<?php echo $this->menuname?>').edatagrid('load',{
		<?php echo $searchgridscript?>});
	closeloader();
}
<?php if ($this->isdownload == 1) { ?>
function downpdf<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++) {
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downpdf?>?'+array);
}
function downxls<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').datagrid('getSelections');
	for(var i=0; i<rows.length; i++) {
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	var array = 'id='+ss<?php echo $searcharray?>;
	window.open('<?php echo $this->downxls?>?'+array);
}
<?php }?>
<?php if ($this->ispost == 1) { ?>
function approve<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	jQuery.ajax({'url':'<?php echo $this->approveurl ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data) {
			show('Pesan',data.msg);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
		} ,
		'cache':false});
};
<?php }?>
<?php if ($this->isreject == 1) { ?>
function cancel<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	jQuery.ajax({'url':'<?php echo $this->rejecturl ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data) {
			show('Pesan',data.msg);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
		} ,
		'cache':false});
};
<?php }?>
<?php if ($this->ispurge == 1) {?>
function purge<?php echo $this->menuname?>() {
	var ss = [];
	var rows = $('#dg-<?php echo $this->menuname?>').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.<?php echo $this->idfield?>);
	}
	jQuery.ajax({'url':'<?php echo $this->destroyurl ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data) {
			show('Pesan',data.msg);
			$('#dg-<?php echo $this->menuname?>').edatagrid('reload');				
		} ,
		'cache':false});
};
<?php }?>
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
<?php echo $this->addonscripts?>
<?php if ($this->iswrite == 1) { ?>
<?php foreach ($this->columndetails as $detail) {?>
$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid({
	iconCls: 'icon-edit',	
	<?php $issingle = (isset($detail['issingle'])?$detail['issingle']:true);
	if ($issingle == 'false') {?>
	singleSelect: false,
	ctrlSelect: true,
	<?php } else {?>
	singlSelect: true,
	<?php }?>
	idField:'<?php echo $detail['idfield']?>',
	<?php if (CheckAccess($this->menuname, 'iswrite') == 1) {?>
	editing: true,
	<?php } ?>
	pagination: true,
	toolbar:'#tb-<?php echo $this->menuname?>-<?php echo $detail['id']?>',
	fitColumn: true,
	url: '<?php echo $detail['url']?>',
	saveUrl: '<?php echo $detail['saveurl']?>',
	updateUrl: '<?php echo $detail['saveurl']?>',
	destroyUrl: '<?php echo $detail['destroyurl']?>',
	<?php $onsuccess = (isset($detail['issuccess']) ? $detail['issuccess']:true);
    if($onsuccess == 'false') { ?>
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('reload');
	},
    <?php } else { ?>
    onSuccess: function(index,row){
        show('Pesan',row.msg);
        <?php echo $detail['onsuccess'];?>
    },
    <?php } ?>
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeginEdit:function(index,row) {
		<?php if (!isset($detail['onbeginedit'])) { ?>
		row.<?php echo $this->idfield?> = $('#<?php echo $this->idfield?>').val();
		<?php } else {?>
		<?php echo $detail['onbeginedit']; 
		}?>
	}, 
	/*
	onBeforeSave:function(index){
		<?php if (!isset($detail['onbeforesave'])) { ?>
		var row = $('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('getSelected');
		if (row) {
			row.<?php echo $this->idfield?> = $('#<?php echo $this->idfield?>').val();
		}		
		<?php } else {?>
		<?php echo $detail['onbeforesave']; 
		}?>
	},
	*/
	onDestroy: function(index,row) {
		<?php if (!isset($detail['ondestroy'])) { ?>
		$('#dg-<?php echo $this->menuname?>-<?php echo $detail['id']?>').edatagrid('reload');
		<?php } else {
			echo $detail['ondestroy'];
		}?>
	},
	<?php if (isset($detail['onselect'])) { ?>
	onSelect:function(index,row){ <?php echo $detail['onselect'] ?>
	},
	<?php }?>
	columns:[[ <?php echo $detail['columns']?>
	]]
});
<?php }?>
<?php }?>
</script>
<?php } ?>
<?php } else Yii::app()->redirect(Yii::app()->createUrl('site/login'));?>
