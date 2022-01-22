<input type="hidden" name="slocid" id="slocid" />
<table id="dg-grheader" style="width:100%;height:97%"></table>
<div id="tb-grheader">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addGrheader()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editGrheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvegrheader()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelgrheader()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfgrheader()"></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsgrheader()"></a>
		<a href="javascript:void(0)" title="Generate Barcode" class="easyui-linkbutton" iconCls="icon-genbarcode" plain="true" onclick="generatebarcode()"></a>		
		<a href="javascript:void(0)" title="Print External Sticker" class="easyui-linkbutton" iconCls="icon-barsticker" plain="true" onclick="downsticker()"></a>
		<a href="javascript:void(0)" title="Stock yang melebihi PO" class="easyui-linkbutton" iconCls="icon-bom" plain="true" onclick="downpdf1()"></a>    
    <a href="javascript:void(0)" title="List Material Yang Tidak ada Di Gudang" class="easyui-linkbutton" iconCls="icon-box" plain="true" onclick="downpdf2()"></a>    
    <a href="javascript:void(0)" title="List Material Yang Satuannya Tidak sama dengan Data Gudang" class="easyui-linkbutton" iconCls="icon-box2" plain="true" onclick="downpdf3()"></a>
<?php }?>
<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formGrheader" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileGrheader" id="FileGrheader" style="display:inline">
			<input type="submit" value="Upload" name="submitGrheaer" style="display:inline">
		</form>
	<?php }?>
<table>
<tr>
<td><?php echo getCatalog('grheaderid')?></td>
<td><input class="easyui-textbox" id="grheader_search_grheaderid" style="width:150px"></td>
<td><?php echo getCatalog('grno')?></td>
<td><input class="easyui-textbox" id="grheader_search_grno" style="width:150px"></td>
<td><?php echo getCatalog('grdate')?></td>
<td><input class="easyui-textbox" id="grheader_search_grdate" style="width:150px"></td>
</tr>
<tr>
<td><?php echo getCatalog('company')?></td>
<td><input class="easyui-textbox" id="grheader_search_company" style="width:150px"></td>
<td><?php echo getCatalog('pono')?></td>
<td><input class="easyui-textbox" id="grheader_search_pono" style="width:150px"></td>
<td><?php echo getCatalog('supplier')?></td>
<td><input class="easyui-textbox" id="grheader_search_fullname" style="width:150px"></td>
</tr>
<tr>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="grheader_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchgrheader()"></a></td>
</tr>
</table>
</div>

<div id="dlg-grheader" class="easyui-dialog" title="Goods Receipt" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormGrheader();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-grheader').dialog('close');
			}
		},
	]	
	">
	<form id="ff-grheader-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="grheaderid" id="grheaderid"></input>
		<table cellpadding="5">
                        <tr>
				<td><?php echo getCatalog('grdate')?></td>
				<td><input class="easyui-datebox" type="text" id="grdate" name="grdate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('pono')?></td>
				<td><select class="easyui-combogrid" name="poheaderid" id="poheaderid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'poheaderid',
								textField: 'pono',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								required:true,
								queryParams: {
									grpo:true
								},
								onHidePanel: function(){
										jQuery.ajax({'url':'<?php echo $this->createUrl('grheader/generatedetail') ?>',
											'data':{
														'id':$('#poheaderid').combogrid('getValue'),
														'hid':$('#grheaderid').val()
												},
												'type':'post',
												'dataType':'json',
											'success':function(data)
											{
												$('#headernote').textbox('setValue',data.headernote);
												$('#dg-grdetail').edatagrid('reload');				
											} ,
											'cache':false});
								},
								columns: [[
										{field:'poheaderid',title:'<?php echo getCatalog('poheaderid') ?>'},
										{field:'pono',title:'<?php echo getCatalog('pono') ?>'},
										{field:'fullname',title:'<?php echo getCatalog('supplier') ?>'},
										{field:'headernote',title:'<?php echo getCatalog('headernote') ?>'},
										{field:'companyname',title:'<?php echo getCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="Detail" style="padding:5px">
				<table id="dg-grdetail"  style="width:100%">
				</table>
				<div id="tb-grdetail">
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-grdetail').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-grdetail').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-grdetail').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$("#formGrheader").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('inventory/grheader/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data);
			$('#dg-grheader').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#grheader_search_company').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrheader();
			}
		}
	})
});
$('#grheader_search_grheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrheader();
			}
		}
	})
});
$('#grheader_search_grno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrheader();
			}
		}
	})
});
$('#grheader_search_grdate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrheader();
			}
		}
	})
});
$('#grheader_search_pono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrheader();
			}
		}
	})
});
$('#grheader_search_fullname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrheader();
			}
		}
	})
});
$('#grheader_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgrheader();
			}
		}
	})
});

$('#dg-grheader').edatagrid({	
	singleSelect: false,
	toolbar:'#tb-grheader',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoRowHeight:true,
	onDblClickRow: function (index,row) {
		editGrheader(index);
	},
	rowStyler: function(index,row){
		if (row.count >= 1){
				return 'background-color:blue;color:#fff;font-weight:bold;';
		}
	},
	view: detailview,
	detailFormatter:function(index,row){
			return '<div style="padding:2px"><table class="ddv-grdetail"></table></div>';
	},
	onExpandRow: function(index,row){
		var ddvgrdetail = $(this).datagrid('getRowDetail',index).find('table.ddv-grdetail');
		ddvgrdetail.datagrid({
			url:'<?php echo $this->createUrl('grheader/indexdetail',array('grid'=>true)) ?>?id='+row.grheaderid,
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:'<?php echo getCatalog('pleasewait') ?>',
			pagination:true,
			height:'auto',
			width:'100%',
			showFooter:true,
			columns:[[
				{field:'productname',title:'<?php echo getCatalog('productname') ?>',width:'350px'},
				{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right',width:'100px'},
				{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>',width:'100px'},
				{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>',width:'200px'},
				{field:'description',title:'<?php echo getCatalog('storagebin') ?>',width:'150px'},
				{field:'barcode',title:'<?php echo getCatalog('barcode') ?>',width:'150px'},
				{field:'itemtext',title:'<?php echo getCatalog('itemtext') ?>',width:'250px'},
			]],
			onResize:function(){
					$('#dg-grheader').datagrid('fixDetailRowHeight',index);
			},
			onLoadSuccess:function(){
					setTimeout(function(){
							$('#dg-grheader').datagrid('fixDetailRowHeight',index);
					},0);
			}
		});
		$('#dg-grheader').datagrid('fixDetailRowHeight',index);
	},
	url: '<?php echo $this->createUrl('grheader/index',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg);
		$('#dg-grheader').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg);
		$('#dg-grheader').edatagrid('reload');
	},
	idField:'grheaderid',
	editing: false,
	columns:[[
	{
		field:'grheaderid',
		title:'<?php echo getCatalog('grheaderid') ?>',
		sortable: true,
		width:'60px',
		formatter: function(value,row,index){
		if (row.recordstatus == 1) {
			return '<div style="background-color:green;color:white">'+value+'</div>';
		} else 
		if (row.recordstatus == 2) {
			return '<div style="background-color:yellow;color:black">'+value+'</div>';
		} else 
		if (row.recordstatus == 3) {
			return '<div style="background-color:red;color:white">'+value+'</div>';
		} else 
		if (row.recordstatus == 0) {
			return '<div style="background-color:black;color:white">'+value+'</div>';
		}
	}},
	{
		field:'companyid',
		title:'<?php echo getCatalog('companyname') ?>',
		width:'250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.companyname;
	}},
	{
		field:'grno',
		title:'<?php echo getCatalog('grno') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'grdate',
		title:'<?php echo getCatalog('grdate') ?>',
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'poheaderid',
		title:'<?php echo getCatalog('pono') ?>',
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
			return row.pono;
	}},
	{
		field:'fullname',
		title:'<?php echo getCatalog('supplier') ?>',
		width:'300px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'headernote',
		title:'<?php echo getCatalog('headernote') ?>',
		width:'200px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'recordstatusgrheader',
		title:'<?php echo getCatalog('recordstatus') ?>',
		sortable: true,
		width:'130px',
		formatter: function(value,row,index){
			return value;
	}},
	]]
});

function searchgrheader(value){
	$('#dg-grheader').edatagrid('load',{
		grheaderid:$('#grheader_search_grheaderid').val(),
		grdate:$('#grheader_search_grdate').val(),
		grno:$('#grheader_search_grno').val(),
		companyid:$('#grheader_search_company').val(),
		poheaderid:$('#grheader_search_pono').val(),
		fullname:$('#grheader_search_fullname').val(),
		headernote:$('#grheader_search_headernote').val()
	});
}
function approvegrheader() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.grheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('grheader/approve') ?>',
		'data':{'id':ss},
		'type':'post',
		'dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-grheader').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelgrheader() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('grheader/delete') ?>',
		'data':{'id':ss},
		'type':'post',
		'dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-grheader').edatagrid('reload');				
		} ,
		'cache':false});
};
function addGrheader() {
		$('#dlg-grheader').dialog('open');
		$('#ff-grheader-modif').form('clear');
		$('#ff-grheader-modif').form('load','<?php echo $this->createUrl('grheader/getdata') ?>');
		$('#grdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};
function editGrheader($i) {
	var row = $('#dg-grheader').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appgr') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-grheader').dialog('open');
		$('#ff-grheader-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormGrheader(){
	$('#ff-grheader-modif').form('submit',{
		url:'<?php echo $this->createUrl('grheader/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-grheader').datagrid('reload');
        $('#dlg-grheader').dialog('close');
			}
    }
	});	
};
function clearFormGrheader(){
		$('#ff-grheader-modif').form('clear');
};
function cancelFormGrheader(){
		$('#dlg-grheader').dialog('close');
};
$('#ff-grheader-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-grdetail').datagrid({
			queryParams: {
				id: $('#grheaderid').val()
			}
		});
}});
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
function generatebarcode() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.grheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('grheader/generatebarcode') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
		} ,
	'cache':false});
}
function downsticker() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.grheaderid);
	}
	window.open('<?php echo $this->createUrl('grheader/downsticker') ?>?id='+ss);
}
function downpdfgrheader() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grheaderid);
	}
	window.open('<?php echo $this->createUrl('grheader/downpdf') ?>?id='+ss);
}
function downpdf1() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grheaderid);
	}
	window.open('<?php echo $this->createUrl('grheader/downpdf1') ?>?id='+ss);
}    
function downpdf2() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grheaderid);
	}
	window.open('<?php echo $this->createUrl('grheader/downpdf2') ?>?id='+ss);
}    
function downpdf3() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grheaderid);
	}
	window.open('<?php echo $this->createUrl('grheader/downpdf3') ?>?id='+ss);
}
function downxlsgrheader() {
	var ss = [];
	var rows = $('#dg-grheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.grheaderid);
	}
	window.open('<?php echo $this->createUrl('grheader/downxls') ?>?id='+ss);
}
$('#dg-grdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	ctrlSelect: true,
	idField:'grdetailid',
	editing: true,
	toolbar:'#tb-grdetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('grheader/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('grheader/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('grheader/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('grheader/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-grdetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-grdetail').edatagrid('getSelected');
		if (row)
		{
			row.grheaderid = $('#grheaderid').val();
		}
	},
	columns:[[
	{
		field:'grheaderid',
		title:'<?php echo getCatalog('grheaderid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'grdetailid',
		title:'<?php echo getCatalog('grdetailid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productid',
		title:'<?php echo getCatalog('product') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'productid',
				textField:'productname',
				url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				readonly:true,
				queryParams:{
					combo:true
				},
				loadMsg: '<?php echo getCatalog('pleasewait')?>',
				columns:[[
					{field:'productid',title:'<?php echo getCatalog('productid')?>',width:'80px'},
					{field:'productname',title:'<?php echo getCatalog('productname')?>',width:'200px'},
				]]
			}	
		},
		width:'200px',
		sortable: true,
		formatter: function(value,row,index){
							return row.productname;
		}
	},
	{
		field:'qty',
		title:'<?php echo getCatalog('qty') ?>',
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
		}
	},
	{
		field:'unitofmeasureid',
		title:'<?php echo getCatalog('uom') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'unitofmeasureid',
				textField:'uomcode',
				url:'<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				readonly:true,
				queryParams:{
					combo:true
				},
				loadMsg: '<?php echo getCatalog('pleasewait')?>',
				columns:[[
					{field:'unitofmeasureid',title:'<?php echo getCatalog('unitofmeasureid')?>',width:'80px'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode')?>',width:'200px'},
				]]
			}	
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
	{
		field:'slocid',
		title:'<?php echo getCatalog('sloc') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'slocid',
				textField:'sloccode',
				url:'<?php echo Yii::app()->createUrl('common/sloc/indextrxgr',array('grid'=>true)) ?>',
				onBeforeLoad: function(param) {
					param.poheaderid = $('#poheaderid').combogrid('getValue');
				},
				fitColumns:true,
				pagination:true,
				required:true,
				loadMsg: '<?php echo getCatalog('pleasewait')?>',
				columns:[[
					{field:'slocid',title:'<?php echo getCatalog('slocid')?>',width:'80px'},
					{field:'sloccode',title:'<?php echo getCatalog('sloccode')?>',width:'200px'},
				]],
				onSelect: function(index,row){
					var sloc = row.slocid;
					$("input[name='slocid']").val(row.slocid);
				},
			}	
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.sloccode;
		}
	},
	{
		field:'storagebinid',
		title:'<?php echo getCatalog('storagebin') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'storagebinid',
				textField:'description',
				url:'<?php echo Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				queryParams:{
					slocid:1
				},
				/*onBeforeLoad: function(param) {
					var row = $('#dg-grdetail').datagrid('getSelected');
					param.slocid = row.slocid;
				},*/
				onBeforeLoad: function(param) {
					var sloc = $("input[name='slocid']").val();
					if(sloc==''){
							var row = $('#dg-grdetail').edatagrid('getSelected');
							param.slocid = row.slocid;
					}else{
						param.slocid = $("input[name='slocid']").val(); }
				},
				loadMsg: '<?php echo getCatalog('pleasewait')?>',
				columns:[[
					{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:'80px'},
					{field:'description',title:'<?php echo getCatalog('description')?>',width:'200px'},
				]]
			}	
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.description;
		}
	},
	{
		field:'itemtext',
		title:'<?php echo getCatalog('itemtext') ?>',
		editor:'text',
		multiline:true,
		width:'200px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
	]]
});
</script>