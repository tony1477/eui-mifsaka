<table id="dg-giheader"  style="width:1200px;height:97%"></table>
<div id="tb-giheader">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a id="add-giheader" href="javascript:void(0)" title="Tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addGiheader()"></a>
		<a id="edit-giheader" href="javascript:void(0)" title="Rubah" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editGiheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a id="approve-giheader" href="javascript:void(0)" title="Approve" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvegiheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a id="reject-giheader" href="javascript:void(0)" title="Reject" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelgiheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a id="pdf-giheader" href="javascript:void(0)" title="Export Ke PDF" class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfgiheader()"></a>
		<a id="xls-giheader" href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsgiheader()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formGiheader" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileGiheader" id="FileGiheader" style="display:inline">
			<input type="submit" value="Upload" name="submitGiheader" style="display:inline">
		</form>
	<?php }?>
<table>
	<tr>
		<td><?php echo getCatalog('giheaderid')?></td>
		<td><input class="easyui-textbox" id="giheader_search_giheaderid" style="width:150px"></td>
		<td><?php echo getCatalog('gino')?></td>
		<td><input class="easyui-textbox" id="giheader_search_gino" style="width:150px"></td>
		<td><?php echo getCatalog('sono')?></td>
		<td><input class="easyui-textbox" id="giheader_search_sono" style="width:150px"></td>
	</tr>
	<tr>
		<td><?php echo getCatalog('customer')?></td>
		<td><input class="easyui-textbox" id="giheader_search_customer" style="width:150px"></td>
		<td><?php echo getCatalog('headernote')?></td>
		<td><input class="easyui-textbox" id="giheader_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchgiheader()"></a></td>
	</tr>
</table>
</div>
<div id="dlg-giheader" class="easyui-dialog" title="<?php echo getCatalog('giheader')?>" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			id:'save-giheader',
			handler:function(){
				openloader();
				submitFormGiheader();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			id:'cancel-giheader',
			handler:function(){
				$('#dlg-giheader').dialog('close');
			}
		},
	]	
	">
	<form id="ff-giheader-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="giheaderid" id="giheaderid"/>
		<table cellpadding="5">
		<tr>
			<td><?php echo getCatalog('gidate')?></td>
			<td><input class="easyui-datebox" type="text" id="gidate" name="gidate" data-options="formatter:dateformatter,required:true,parser:dateparser"/></td>
		</tr>
		<tr>
			<td><?php echo getCatalog('soheader')?></td>
			<td><select class="easyui-combogrid" id="soheaderid" name="soheaderid" style="width:250px" data-options="
				panelWidth: '500px',
				idField: 'soheaderid',
				required: true,
				textField: 'sono',
				pagination:true,
				url: '<?php echo Yii::app()->createUrl('order/soheader/indexcombo',array('grid'=>true)) ?>',
				method: 'get',
				mode: 'remote',
				onHidePanel: function(){
					jQuery.ajax({'url':'<?php echo $this->createUrl('giheader/generateso') ?>',
						'data':{
							'id':$('#soheaderid').combogrid('getValue'),
							'hid':$('#giheaderid').val()
						},
						'type':'post',
						'dataType':'json',
						'success':function(data) {
							show('Message','silahkan refresh detail');
						} ,
						'cache':false});
						$('#dg-gidetail').datagrid({
							queryParams: {
								id: $('#giheaderid').val()
						}
					});
				},
				columns: [[
					{field:'soheaderid',title:'<?php echo getCatalog('soheaderid') ?>',width:'80px'},
					{field:'pocustno',title:'<?php echo getCatalog('pocustno') ?>',width:'120px'},
					{field:'sono',title:'<?php echo getCatalog('sono') ?>',width:'120px'},
					{field:'customername',title:'<?php echo getCatalog('customer') ?>',width:'200px'},
					{field:'companyname',title:'<?php echo getCatalog('companyname') ?>',width:'200px'},
				]],
				fitColumns: true">
			</select></td>
		</tr>			
		<tr>
			<td><?php echo getCatalog('headernote')?></td>
			<td><input class="easyui-textbox" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"/></td>
		</tr>
	</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
		<div title="Detail" style="padding:5px">
			<table id="dg-gidetail"  style="width:100%">
			</table>
			<div id="tb-gidetail">
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-gidetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-gidetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-gidetail').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$("#formGiheader").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('inventory/giheader/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data.msg);
			$('#dg-giheader').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#dg-giheader').edatagrid({
	singleSelect: false,
	toolbar:'#tb-giheader',
	pagination: true,
	fitColumns:true,
	ctrlSelect:true,
	autoRowHeight:true,
	onDblClickRow: function (index,row) {
		editGiheader(index);
	},
	rowStyler: function(index,row){
		if (row.count >= 1){
			return 'background-color:blue;color:#fff;font-weight:bold;';
		}
	},
	view: detailview,
	detailFormatter:function(index,row){
		return '<div style="padding:2px"><table class="ddv-gidetail"></table><table class="ddv-custdisc"></table></div>';
	},
	onExpandRow: function(index,row){
		var ddvgidetail = $(this).datagrid('getRowDetail',index).find('table.ddv-gidetail');
		ddvgidetail.datagrid({
			url:'<?php echo $this->createUrl('giheader/indexdetail',array('grid'=>true)) ?>?id='+row.giheaderid,
			fitColumns:true,
			singleSelect:true,
			rownumbers:true,
			loadMsg:'<?php echo getCatalog('pleasewait') ?>',
			height:'auto',
			showFooter:true,
			pagination:true,
			columns:[[
				{field:'productname',title:'<?php echo getCatalog('productname') ?>',width:'300px'},
				{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right',width:'100px'},
				{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>',width:'100px'},
				{field:'sloccode',title:'<?php echo getCatalog('sloc') ?>',width:'250px'},
				{field:'description',title:'<?php echo getCatalog('storagebin') ?>',width:'100px'},
				{field:'itemnote',title:'<?php echo getCatalog('itemnote') ?>',width:'300px'},
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
	url: '<?php echo $this->createUrl('giheader/index',array('grid'=>true)) ?>',
	onSuccess: function(index,row){
		show('Message',row.msg);
		$('#dg-giheader').edatagrid('reload');
	},
	onError: function(index,row){
		show('Message',row.msg);
	},
	idField:'giheaderid',
	editing: false,
	columns:[[
	{
		field:'giheaderid',
		title:'<?php echo getCatalog('giheaderid') ?>',
		sortable: true,
		width:'60px',
		formatter: function(value,row,index){
			switch (row.recordstatus) {
				case "1" :
					return '<div style="background-color:green;color:white">'+value+'</div>';
					break;
				case "2" :
					return '<div style="background-color:yellow;color:black">'+value+'</div>';
					break;
				case "3" :
					return '<div style="background-color:red;color:white">'+value+'</div>';
					break;
				default :
					return '<div style="background-color:black;color:white">'+value+'</div>';
					break;
			}
	}},
	{
		field:'companyid',
		title:'<?php echo getCatalog('company') ?>',
		sortable: true,
		width:'300px',
		formatter: function(value,row,index){
			return row.companyname;
	}},
	{
		field:'gino',
		title:'<?php echo getCatalog('gino') ?>',
		sortable: true,
		width:'120px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'gidate',
		title:'<?php echo getCatalog('gidate') ?>',
		sortable: true,
		width:'120px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'soheaderid',
		title:'<?php echo getCatalog('sono') ?>',
		sortable: true,
		width:'120px',
		formatter: function(value,row,index){
			return row.sono;
	}},
	{
		field:'fullname',
		title:'<?php echo getCatalog('customer') ?>',
		sortable: true,
		width:'250px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'shipto',
		title:'<?php echo getCatalog('shipto') ?>',
		sortable: true,
		width:'200px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'headernote',
		title:'<?php echo getCatalog('headernote') ?>',
		sortable: true,
		width:'250px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'recordstatusgiheader',
		title:'<?php echo getCatalog('recordstatus') ?>',
		align:'left',
		width:'120px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
	}},
	]]
});
$('#giheader_search_giheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});
$('#giheader_search_gino').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});
$('#giheader_search_sono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});
$('#giheader_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});
$('#giheader_search_shipto').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});
$('#giheader_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});
function searchgiheader(value){
	$('#dg-giheader').edatagrid('load',{
		giheaderid:$('#giheader_search_giheaderid').val(),
		gino:$('#giheader_search_gino').val(),
		sono:$('#giheader_search_sono').val(),
		customer:$('#giheader_search_customer').val(),
		shipto:$('#giheader_search_shipto').val(),
		headernote:$('#giheader_search_headernote').val(),
	});
};
function approvegiheader() {
	openloader();
	var ss = [];
	var rows = $('#dg-giheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.giheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('giheader/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			closeloader();
			show('Message',data.msg);
			$('#dg-giheader').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelgiheader() {
	openloader();
	var ss = [];
	var rows = $('#dg-giheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.giheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('giheader/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			closeloader();
			show('Message',data.msg);
			$('#dg-giheader').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfgiheader() {
	var ss = [];
	var rows = $('#dg-giheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.giheaderid);
	}
	window.open('<?php echo $this->createUrl('giheader/downpdf') ?>?id='+ss);
};
function downxlsgiheader() {
	var ss = [];
	var rows = $('#dg-giheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		ss.push(row.giheaderid);
	}
	window.open('<?php echo $this->createUrl('giheader/downxls') ?>?id='+ss);
}
function addGiheader() {
	openloader();
	$('#dlg-giheader').dialog('open');
	$('#ff-giheader-modif').form('clear');
	$('#ff-giheader-modif').form('load','<?php echo $this->createUrl('giheader/GetData') ?>');
	$('#gidate').datebox({
		value: (new Date().toString('dd-MMM-yyyy'))
	});
	closeloader();
};
function editGiheader($i) {
	var row = $('#dg-giheader').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appgi') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) {
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else {
			openloader();
			$('#dlg-giheader').dialog('open');
			$('#ff-giheader-modif').form('load',row);
			closeloader();
		}
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormGiheader(){
	$('#ff-giheader-modif').form('submit',{
		url:'<?php echo $this->createUrl('giheader/save') ?>',
		onSubmit:function(){
			return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			closeloader();
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-giheader').datagrid('reload');
        $('#dlg-giheader').dialog('close');
			}
    }
	});	
};
function clearFormGiheader(){
		$('#ff-giheader-modif').form('clear');
};
$('#ff-giheader-modif').form({
	onLoadSuccess: function(data) {
		$('#sodate').datebox('setValue', data.sodate);
		$('#dg-gidetail').datagrid({
			queryParams: {
				id: $('#giheaderid').val()
			}
		});
		$('#dg-custdisc').datagrid({
			queryParams: {
				id:$('#giheaderid').val()
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
$('#dg-gidetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'gidetailid',
	editing: true,
	toolbar:'#tb-gidetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('giheader/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('giheader/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('giheader/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('giheader/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-gidetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
		$('#dg-gidetail').edatagrid('reload');
	},
	onBeforeSave: function(index){
		var row = $('#dg-gidetail').edatagrid('getSelected');
		if (row) {
			row.giheaderid = $('#giheaderid').val();
		}
	},
	columns:[[
	{
		field:'giheaderid',
		title:'<?php echo getCatalog('giheaderid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'gidetailid',
		title:'<?php echo getCatalog('gidetailid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'productid',
		title:'<?php echo getCatalog('product') ?>',
		required:true,
		sortable: true,
		width:'350px',
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
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'unitofmeasureid',
		title:'<?php echo getCatalog('uom') ?>',
		required:true,
		sortable: true,
		width:'150px',
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
	{
		field:'slocid',
		title:'<?php echo getCatalog('sloc') ?>',
		required:true,
		sortable: true,
		width:'150px',
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
				onBeforeLoad: function(param) {
					var row = $('#dg-gidetail').datagrid('getSelected');
					param.slocid = row.slocid;
				},
				loadMsg: '<?php echo getCatalog('pleasewait')?>',
				columns:[[
					{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:'80px'},
					{field:'description',title:'<?php echo getCatalog('description')?>',width:'200px'},
				]]
			}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
							return row.description;
		}
	},
	{
		field:'itemnote',
		title:'<?php echo getCatalog('itemnote') ?>',
		editor:'text',
		width:'250px',
		multiline:true,
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
	]]
});
</script>