<table id="dg-genjournal" style="width:100%;height:97%"></table>
<div id="tb-genjournal">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addGenjournal()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editGenjournal()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvegenjournal()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelgenjournal()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfgenjournal()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsgenjournal()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isupload) == 1) {?>
		<form id="formGenjournal" method="post" enctype="multipart/form-data" style="display:inline" data-options="novalidate:true">
			<input type="file" name="FileGenjournal" id="FileGenjournal" style="display:inline">
			<input type="submit" value="Upload" name="submitGenjournal" style="display:inline">
		</form>
	<?php }?>
	<table>
		<tr>
			<td><?php echo GetCatalog('genjournalid')?></td>
			<td><input class="easyui-textbox" id="genjournal_search_genjournalid" style="width:150px"></td>
			<td><?php echo GetCatalog('companyname')?></td>
			<td><input class="easyui-textbox" id="genjournal_search_companyname" style="width:150px"></td>
			<td><?php echo GetCatalog('journalno')?></td>
			<td><input class="easyui-textbox" id="genjournal_search_journalno" style="width:150px"></td>
		</tr>
		<tr>
			<td><?php echo GetCatalog('referenceno')?></td>
			<td><input class="easyui-textbox" id="genjournal_search_referenceno" style="width:150px"></td>
			<td><?php echo GetCatalog('journaldate')?></td>
			<td><input class="easyui-textbox" id="genjournal_search_journaldate" style="width:150px"></td>
			<td><?php echo GetCatalog('headernote')?></td>
			<td><input class="easyui-textbox" id="genjournal_search_headernote" style="width:150px">	<a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchgenjournal()"></a></td>
		</tr>
	</table>
</div>
<div id="dlg-genjournal" class="easyui-dialog" title="Jurnal Umum" style="width:auto;height:500px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo GetCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormGenjournal();
			}
		},
		{
			text:'<?php echo GetCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-genjournal').dialog('close');
			}
		},
	]	
	">
	<form id="ff-genjournal-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="genjournalid" id="genjournalid"></input>
		<table cellpadding="5">
			<tr>
				<td><?php echo GetCatalog('journaldate')?></td>
				<td><input class="easyui-datebox" type="text" id="journaldate" name="journaldate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('company')?></td>
				<td>
					<select class="easyui-combogrid" id="companyid" name="companyid" style="width:250px" data-options="
						panelWidth: '500px',
						required: true,
						idField: 'companyid',
						textField: 'companyname',
						pagination:true,
						mode:'remote',
						url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
						method: 'get',
						columns: [[
								{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
								{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
						]],
						fitColumns: true">
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('journalnote')?></td>
				<td><input class="easyui-textbox" id="journalnote" name="journalnote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
			<tr>
				<td><?php echo GetCatalog('referenceno')?></td>
				<td><input class="easyui-textbox" id="referenceno" name="referenceno"></input></td>
			</tr>
		</table>
	</form>
	<div class="easyui-tabs" style="width:auto;height:300px">
		<div title="Detail" style="padding:5px">
			<table id="dg-journaldetail"  style="width:100%;">
			</table>
			<div id="tb-journaldetail">
				<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-journaldetail').edatagrid('addRow')"></a>
				<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-journaldetail').edatagrid('saveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-journaldetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-journaldetail').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$("#formLanguage").submit(function(e) {
	e.preventDefault();    
	var formData = new FormData(this);
	$.ajax({
		url: '<?php echo Yii::app()->createUrl('accounting/genjournal/upload') ?>',
		type: 'POST',
		data: formData,
		success: function (data) {
			show('Pesan',data);
			$('#dg-genjournal').edatagrid('reload');
		},
		cache: false,
		contentType: false,
		processData: false
	});
});
$('#genjournal_search_genjournalid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgenjournal();
			}
		}
	})
});
$('#genjournal_search_companyname').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgenjournal();
			}
		}
	})
});
$('#genjournal_search_journalno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgenjournal();
			}
		}
	})
});
$('#genjournal_search_referenceno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgenjournal();
			}
		}
	})
});
$('#genjournal_search_journaldate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgenjournal();
			}
		}
	})
});
$('#genjournal_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgenjournal();
			}
		}
	})
});
$('#dg-genjournal').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-genjournal',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editGenjournal(index);
		},
		rowStyler: function(index,row){
			if (row.debit != row.credit){
					return 'background-color:blue;color:#fff;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-journaldetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvjournaldetail = $(this).datagrid('getRowDetail',index).find('table.ddv-journaldetail');
			ddvjournaldetail.datagrid({
				url:'<?php echo $this->createUrl('genjournal/indexdetail',array('grid'=>true)) ?>?id='+row.genjournalid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'auto',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'accountname',title:'<?php echo GetCatalog('accountname') ?>',width:'250px'},
					{field:'debit',title:'<?php echo GetCatalog('debit') ?>',
						formatter: function(value,row,index){
							return row.symbol + ' ' + row.debit;
						},align:'right',width:'120px'},
					{field:'credit',title:'<?php echo GetCatalog('credit') ?>',
						formatter: function(value,row,index){
							return row.symbol + ' ' + row.credit;
						},align:'right',width:'120px'},
					{field:'ratevalue',title:'<?php echo GetCatalog('ratevalue') ?>',align:'right',width:'60px'},
					{field:'detailnote',title:'<?php echo GetCatalog('detailnote') ?>',width:'350px'},
				]],
				onResize:function(){
					$('#dg-genjournal').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
					setTimeout(function(){
						$('#dg-genjournal').datagrid('fixDetailRowHeight',index);
					},0);
				}
			});
			$('#dg-genjournal').datagrid('fixDetailRowHeight',index);
		},
		url: '<?php echo $this->createUrl('genjournal/index',array('grid'=>true)) ?>',
		destroyUrl: '<?php echo $this->createUrl('genjournal/purge',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg,'info');
			$('#dg-genjournal').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg,'error');
			$('#dg-genjournal').edatagrid('reload');
		},
		idField:'genjournalid',
		editing: false,
		columns:[[
		{
			field:'genjournalid',
			title:'<?php echo GetCatalog('genjournalid') ?>',
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
			title:'<?php echo GetCatalog('company') ?>',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'journalno',
			title:'<?php echo GetCatalog('journalno') ?>',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'referenceno',
			title:'<?php echo GetCatalog('referenceno') ?>',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'journaldate',
			title:'<?php echo GetCatalog('journaldate') ?>',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'journalnote',
			title:'<?php echo GetCatalog('journalnote') ?>',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'recordstatus',
			title:'<?php echo GetCatalog('recordstatus') ?>',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.recordstatusname;
		}},
	]]
});
function searchgenjournal(value){
	$('#dg-genjournal').edatagrid('load',{
		genjournalid:$('#genjournal_search_genjournalid').val(),
		companyname:$('#genjournal_search_companyname').val(),
		journalno:$('#genjournal_search_journalno').val(),
		referenceno:$('#genjournal_search_referenceno').val(),
		journaldate:$('#genjournal_search_journaldate').val(),
		journalnote:$('#genjournal_search_headernote').val()
	});
}
function approvegenjournal() {
	var ss = [];
	var rows = $('#dg-genjournal').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.genjournalid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('genjournal/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data) {
			show('Message',data.message);
			$('#dg-genjournal').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelgenjournal() {
	var ss = [];
	var rows = $('#dg-genjournal').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.genjournalid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('genjournal/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data) {
			show('Message',data.message);
			$('#dg-genjournal').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfgenjournal() {
	var ss = [];
	var rows = $('#dg-genjournal').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.genjournalid);
	}
	window.open('<?php echo $this->createUrl('genjournal/downpdf') ?>?id='+ss);
}
function downxlsgenjournal() {
	var ss = [];
	var rows = $('#dg-genjournal').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.genjournalid);
	}
	window.open('<?php echo $this->createUrl('genjournal/downxls') ?>?id='+ss);
}
function addGenjournal() {
		$('#dlg-genjournal').dialog('open');
		$('#ff-genjournal-modif').form('clear');
		$('#ff-genjournal-modif').form('load','<?php echo $this->createUrl('genjournal/GetData') ?>');
};
function editGenjournal($i) {
	var row = $('#dg-genjournal').datagrid('getSelected');
	if(row) {
		$('#dlg-genjournal').dialog('open');
		$('#ff-genjournal-modif').form('load',row);
	}
	else {
		show('Message','chooseone');
	}
};
function submitFormGenjournal(){
	$('#ff-genjournal-modif').form('submit',{
		url:'<?php echo $this->createUrl('genjournal/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-genjournal').datagrid('reload');
        $('#dlg-genjournal').dialog('close');
			}
    }
	});	
}
function clearFormGenjournal(){
		$('#ff-genjournal-modif').form('clear');
}
function cancelFormGenjournal(){
		$('#dlg-genjournal').dialog('close');
}
$('#ff-genjournal-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-journaldetail').datagrid({
			queryParams: {
				id: $('#genjournalid').val()
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
$('#dg-journaldetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'journaldetailid',
	editing: true,
	toolbar:'#tb-journaldetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('genjournal/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('genjournal/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('genjournal/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('genjournal/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-journaldetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var row = $('#dg-journaldetail').edatagrid('getSelected');
		if (row)
		{
			row.genjournalid = $('#genjournalid').val();
		}
	},
	columns:[[
	{
		field:'genjournalid',
		title:'<?php echo GetCatalog('genjournalid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'journaldetailid',
		title:'<?php echo GetCatalog('journaldetailid') ?>',
		sortable: true,
		hidden:true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'accountid',
		title:'<?php echo GetCatalog('accountname') ?>',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'800px',
				mode : 'remote',
				method:'get',
				idField:'accountid',
				textField:'accountname',
				url:'<?php echo $this->createUrl('account/index',array('grid'=>true)) ?>',
				fitColumns:true,
				pagination:true,
				required:true,
				queryParams:{
					trxcom:true
				},
				onBeforeLoad: function(param) {
					 param.companyid = $('#companyid').combogrid('getValue');
				},
				loadMsg: '<?php echo GetCatalog('pleasewait')?>',
				columns:[[
					{field:'accountid',title:'<?php echo GetCatalog('accountid')?>',width:'80px'},
					{field:'companyname',title:'<?php echo GetCatalog('company')?>',width:'350px'},
					{field:'accountcode',title:'<?php echo GetCatalog('accountcode')?>',width:'120px'},
					{field:'accountname',title:'<?php echo GetCatalog('accountname')?>',width:'250px'},
				]]
			}	
		},
		width:'300px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accountname;
		}
	},
	{
		field:'debit',
		title:'<?php echo GetCatalog('debit') ?>',
		width:'100px',
		editor: {
			type: 'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				value:0,
				required:true,
			}
		},
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
        {
		field:'credit',
		title:'<?php echo GetCatalog('credit') ?>',
		width:'100px',
               editor: {
			type: 'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				value:0,
				required:true,
			}
		},
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
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
                width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.currencyname;
		}
	},
        {
		field:'ratevalue',
		title:'<?php echo GetCatalog('ratevalue') ?>',
		editor:{
			type:'numberbox',
			options:{
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
				value:1,
			}
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
        {
		field:'detailnote',
		title:'<?php echo GetCatalog('detailnote')?>',
    editor: 'textbox',
		sortable: true,
		width:'300px',
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
