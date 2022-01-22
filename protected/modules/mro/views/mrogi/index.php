<table id="dg-mrogiheader"  style="width:1200px;height:97%"></table>
<div id="tb-mrogiheader">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addmroGiheader()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editmroGiheader()"></a>
	<?php }?>
			<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvemrogiheader()"></a>
<?php }?>
<?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelmrogiheader()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfmrogiheader()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsmrogiheader()"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('mrogiheaderid')?></td>
<td><input class="easyui-textbox" id="mrogiheader_search_mrogiheaderid" style="width:150px"></td>
<td><?php echo getCatalog('mrogino')?></td>
<td><input class="easyui-textbox" id="mrogiheader_search_mrogino" style="width:150px"></td>
<td><?php echo getCatalog('gino')?></td>
<td><input class="easyui-textbox" id="mrogiheader_search_gino" style="width:150px"></td>
</tr>
<tr>
<td><?php echo getCatalog('customer')?></td>
<td><input class="easyui-textbox" id="mrogiheader_search_customer" style="width:150px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="mrogiheader_search_headernote" style="width:150px">
    <a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchmrogiheader()"></a></td>
</tr>
</table>
</div>

<div id="dlg-mrogiheader" class="easyui-dialog" title="<?php echo getCatalog('mrogiheader')?>" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormmroGiheader();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-mrogiheader').dialog('close');
			}
		},
	]	
	">
	<form id="ff-mrogiheader-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="mrogiheaderid" id="mrogiheaderid" />
		<table cellpadding="5">
		<tr>
				<td><?php echo getCatalog('mrogidate')?></td>
				<td><input class="easyui-datebox" type="text" id="mrogidate" name="mrogidate" data-options="formatter:dateformatter,required:true,parser:dateparser" /></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('giheader')?></td>
				<td><select class="easyui-combogrid" id="giheaderid" name="giheaderid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'giheaderid',
								required: true,
								textField: 'giheaderno',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('mro/mrogi/indexgihader',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								onHidePanel: function(){
                                    var headernote = $('#dg-mrogiheader').datagrid('getEditor', {index:'index', field:'headernote'});
                                    var shipto = $('#dg-mrogiheader').datagrid('getEditor', {field:'shipto'});
									jQuery.ajax({'url':'<?php echo $this->createUrl('mrogi/generategi') ?>',
											'data':{
													'id':$('#mrogiheaderid').val(),
													'hid':$('#giheaderid').combogrid('getValue'),
                                                    'mrogidate':$('#mrogidate').val()
												},
												'type':'post',
												'dataType':'json',
											    'success':function(data)
                                                {
                                                    $('#shipto').textbox('setValue',data.shipto);
                                                    $('#headernote').textbox('setValue',data.headernote);
                                                    $('#addressbookid').combogrid('setValue',data.addressbookid);
                                                    $('#taxid').combogrid('setValue',data.taxid);
                                                    $('#dg-mrogidetail').edatagrid('reload');
                                                },
                                                'cache':false});
								},
								columns: [[
										{field:'giheaderid',title:'<?php echo getCatalog('giheaderid') ?>'},
										{field:'gino',title:'<?php echo getCatalog('gino') ?>'},
										{field:'sono',title:'<?php echo getCatalog('sono') ?>'},
										{field:'fullname',title:'<?php echo getCatalog('customer') ?>'},
										{field:'sales',title:'<?php echo getCatalog('sales') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
            </tr>
            <tr>
            <td><?php echo getCatalog('addressbook')?></td>
                <td><select class="easyui-combogrid" id="addressbookid" name="addressbookid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'addressbookid',
								required: true,
								textField: 'fullname',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/addressbook/index',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								onHidePanel: function(){
									jQuery.ajax({'url':'<?php echo $this->createUrl('mrogiheader/generategi') ?>',
											'data':{
													'id':$('#giheaderid').combogrid('getValue'),
													'hid':$('#mrogiheaderid').val()
												},
												'type':'post',
												'dataType':'json',
											'success':function(data)
											{
                                               	
											} ,
											'cache':false});

										$('#dg-mrogidetail').datagrid({
										queryParams: {
											id: $('#mrogiheaderid').val()

									}
									});
								},
								columns: [[
										{field:'addressbookid',title:'<?php echo getCatalog('addressbookid') ?>'},
										{field:'fullname',title:'<?php echo getCatalog('fullname') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
            </tr>
            <tr>
                <td><?php echo getCatalog('tax')?></td>
                 <td><select class="easyui-combogrid" id="taxid" name="taxid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'taxid',
								required: true,
								textField: 'taxcode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('accounting/tax/index',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								columns: [[
										{field:'taxid',title:'<?php echo getCatalog('taxid') ?>'},
										{field:'taxvalue',title:'<?php echo getCatalog('taxvalue') ?>'},
										{field:'taxcode',title:'<?php echo getCatalog('taxcode') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
            <tr>
				<td><?php echo getCatalog('shipto')?></td>
				<td><input class="easyui-textbox" name="shipto" id="shipto" data-options="multiline:true,required:true" style="width:300px;height:100px"/></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" name="headernote" id="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"/></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
		<div title="Detail" style="padding:5px">
			<table id="dg-mrogidetail"  style="width:100%">
			</table>
			<div id="tb-mrogidetail">
                <a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg-mrogidetail').edatagrid('SaveRow')"></a>
				<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-mrogidetail').edatagrid('cancelRow')"></a>
				<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-mrogidetail').edatagrid('destroyRow')"></a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('#soheader_search_sono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchsoheader();
			}
		}
	})
});
$('#dg-mrogiheader').edatagrid({
		singleSelect: false,
		toolbar:'#tb-mrogiheader',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editmroGiheader(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-mrogidetail"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddvgidetail = $(this).datagrid('getRowDetail',index).find('table.ddv-mrogidetail');
			ddvgidetail.datagrid({
				url:'<?php echo $this->createUrl('mrogi/indexdetail',array('grid'=>true)) ?>?id='+row.mrogiheaderid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo getCatalog('pleasewait') ?>',
				height:'auto',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},					
					{field:'netprice',title:'<?php echo getCatalog('netprice') ?>'},					
					{field:'itemnote',title:'<?php echo getCatalog('itemnote') ?>',width:'300px'},
				]],
				onResize:function(){
						$('#dg-mrogiheader').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-mrogiheader').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-mrogiheader').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('mrogi/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-mrogiheader').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'mrogiheaderid',
		editing: false,
		columns:[[
		{
field:'mrogiheaderid',
title:'<?php echo getCatalog('mrogiheaderid') ?>',
sortable: true,
width:'60px',
formatter: function(value,row,index){
					return value;
		}},
{
field:'mrogino',
title:'<?php echo getCatalog('mrogino') ?>',
sortable: true,
width:'90px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'mrogidate',
title:'<?php echo getCatalog('gidate') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
					{
field:'giheaderid',
title:'<?php echo getCatalog('gino') ?>',
sortable: true,
width:'90px',
formatter: function(value,row,index){
						return row.gino;
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
field:'recordstatus',
title:'<?php echo getCatalog('recordstatus') ?>',
sortable: true,
width:'130px',
formatter: function(value,row,index){
						return row.recordstatusmrogiheader;
					}},
		]]
});

$('#mrogiheader_search_giheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});

$('#mrogiheader_search_gino').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});

$('#mrogiheader_search_sono').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});

$('#mrogiheader_search_customer').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});

$('#mrogiheader_search_shipto').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});

$('#mrogiheader_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchgiheader();
			}
		}
	})
});

function searchmrogiheader(value){
	$('#dg-mrogiheader').edatagrid('load',{
	giheaderid:$('#mrogiheader_search_giheaderid').val(),
	gino:$('#mrogiheader_search_gino').val(),
	sono:$('#mrogiheader_search_sono').val(),
	customer:$('#mrogiheader_search_customer').val(),
	shipto:$('#mrogiheader_search_shipto').val(),
	headernote:$('#mrogiheader_search_headernote').val(),
	});
};

function approvemrogiheader() {
	var ss = [];
	var rows = $('#dg-mrogiheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.mrogiheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('mrogi/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-mrogiheader').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelmrogiheader() {
	var ss = [];
	var rows = $('#dg-mrogiheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.mrogiheaderid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('mrogi/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.message);
			$('#dg-mrogiheader').edatagrid('reload');				
		} ,
		'cache':false});
};

function downpdfmrogiheader() {
	var ss = [];
	var rows = $('#dg-mrogiheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.mrogiheaderid);
	}
	window.open('<?php echo $this->createUrl('mrogi/downpdf') ?>?id='+ss);
};

function downxlsmrogiheader() {
	var ss = [];
	var rows = $('#dg-mrogiheader').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.giheaderid);
	}
	window.open('<?php echo $this->createUrl('giheader/downxls') ?>?id='+ss);
}

function addmroGiheader() {
		$('#dlg-mrogiheader').dialog('open');
		$('#ff-mrogiheader-modif').form('clear');
        $('#ff-mrogiheader-modif').form('load','<?php echo $this->createUrl('mrogi/GetData') ?>');
		$('#mrogidate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editmroGiheader($i) {
	var row = $('#dg-mrogiheader').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appgi') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-mrogiheader').dialog('open');
		$('#ff-mrogiheader-modif').form('load',row);
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormmroGiheader(){
	$('#ff-mrogiheader-modif').form('submit',{
		url:'<?php echo $this->createUrl('mrogi/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.message)
			if (data.success == true){
        $('#dg-mrogiheader').datagrid('reload');
        $('#dlg-mrogiheader').dialog('close');
			}
    }
	});	
};

function clearFormmroGiheader(){
		$('#ff-mrogiheader-modif').form('clear');
};

function cancelFormmroGiheader(){
		$('#dlg-mrogiheader').dialog('close');
};

$('#ff-mrogiheader-modif').form({
	onLoadSuccess: function(data) {
		$('#sodate').datebox('setValue', data.sodate);
		$('#dg-mrogidetail').datagrid({
			queryParams: {
				id: $('#mrogiheaderid').val()
			}
		});
		$('#dg-custdisc').datagrid({
				queryParams: {
					id:$('#mrogiheaderid').val()
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

$('#dg-mrogidetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'mrogidetailid',
	editing: true,
	toolbar:'#tb-mrogidetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('mrogi/indexdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('mrogi/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('mrogi/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('mrogi/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.message);
		$('#dg-mrogidetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.message);
	},
	onBeforeSave: function(index){
		var row = $('#dg-mrogidetail').edatagrid('getSelected');
		if (row)
		{
			row.mrogiheaderid = $('#mrogiheaderid').val();
		}
	},
	columns:[[
	{
		field:'mrogiheaderid',
		title:'<?php echo getCatalog('mrogiheaderid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'mrogidetailid',
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
		field:'netprice',
		title:'<?php echo getCatalog('price') ?>',
		required:true,
		sortable: true,
        readonly:true,
        precision:2,
        decimalSeparator:',',
		groupSeparator:'.',
		
		width:'150px',
		formatter: function(value,row,index){
							return row.netprice;
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
