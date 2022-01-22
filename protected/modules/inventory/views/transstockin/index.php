<style type="text/css">
.ajax-loader {
  visibility: hidden;
  background-color: rgba(255,255,255,0.7);
  position: absolute;
  z-index: +1000 !important;
  width: 100%;
  height:100%;
}

.ajax-loader img {
  position: relative;
  top:10%;
  left:10%;
}
</style>
<div class="ajax-loader">
  <img src="<?php echo Yii::app()->baseUrl?>/images/loading.gif" class="img-responsive" />
</div>
<table id="dg-transstockin" style="width:1200px;height:97%"></table>
<div id="tb-transstockin">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editTSin()" id="edittsin"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvetsin()" id="approvetsin"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="canceltsin()" id="rejecttsin"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('transstockid')?></td>
<td><input class="easyui-textbox" id="transstockin_search_transstockid" style="width:100px"></td>
<td><?php echo getCatalog('transstockno')?></td>
<td><input class="easyui-textbox" id="transstockin_search_transstockno" style="width:100px"></td>
<td><?php echo getCatalog('dano')?></td>
<td><input class="easyui-textbox" id="transstockin_search_dano" style="width:100px"></td>
</tr>
<tr>
<td><?php echo getCatalog('slocfrom')?></td>
<td><input class="easyui-textbox" id="transstockin_search_slocfrom" style="width:100px"></td>
<td><?php echo getCatalog('slocto')?></td>
<td><input class="easyui-textbox" id="transstockin_search_slocto" style="width:100px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="transstockin_search_headernote" style="width:100px"><a href="javascript:void(0)" title="Cari" id="transstock_search" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchtransstockin()"></a></td>
</tr>
</table>
</div>

<div id="dlg-transstockin" class="easyui-dialog" title="Transfer Gudang" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormTS();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
                    $('.ajax-loader').css('visibility', 'hidden');
					$('#dlg-transstockin').dialog('close');
			}
		},
	]	
	">
	<form id="ff-transstockin-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="transstockid" id="transstockid"/>
		<table cellpadding="5">
                        <tr>
				<td><?php echo getCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,readonly:true,required:true,parser:dateparser"/></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('dano')?></td>
				<td><select class="easyui-combogrid" name="deliveryadviceid" id="deliveryadviceid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'deliveryadviceid',
								textField: 'dano',
								pagination:true,
								readonly:true,
								url: '<?php echo $this->createUrl('deliveryadvice/index',array('grid'=>true)) ?>',
								method: 'get',
								queryParams: {
									combo:true
								},
               	columns: [[
										{field:'deliveryadviceid',title:'<?php echo getCatalog('deliveryadviceid') ?>'},
										{field:'dano',title:'<?php echo getCatalog('dano') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloc') ?>'},
										{field:'headernote',title:'<?php echo getCatalog('headernote') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
                        <tr>
				<td><?php echo getCatalog('slocfrom')?></td>
				<td><select class="easyui-combogrid" name="slocfromid" id="slocfromid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'slocid',
								textField: 'sloccode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/sloc/index',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								readonly:true,
								required:true,
								queryParams: {
									combo:true
								},
                columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocidid') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
                        <tr>
				<td><?php echo getCatalog('slocto')?></td>
				<td><select class="easyui-combogrid" name="sloctoid" id="sloctoid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'slocid',
								textField: 'sloccode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/sloc/index',array('grid'=>true)) ?>',
								method: 'get',
								readonly:true,
								mode:'remote',
								required:true,
                columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocidid') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" id="headernote" name="headernote" data-options="multiline:true" style="width:300px;height:100px"/></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="Detail" style="padding:5px">
				<table id="dg-transstockindet"  style="width:100%">
				</table>
				<div id="tb-transstockindet">
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-transstockindet').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-transstockindet').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-transstockindet').edatagrid('destroyRow')"></a>
				</div>
			</div>
                </div>
</div>

<script type="text/javascript">
$('#edittsin').click(function(){
    tampil_loading();
});
    
$('#approvetsin').click(function(){
    tampil_loading();
});

$('#rejecttsin').click(function(){
    tampil_loading();
});

function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css('visibility', 'hidden');
}

$('#dg-transstockin').edatagrid({
		singleSelect: false,
		toolbar:'#tb-transstockin',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editTSin(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-transstockindet"></table><table class="ddv-custdisc"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvtransstockindet = $(this).datagrid('getRowDetail',index).find('table.ddv-transstockindet');
			ddvtransstockindet.datagrid({
				url:'<?php echo $this->createUrl('transstockin/indexdetail',array('grid'=>true)) ?>?id='+row.transstockid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'800px',
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>'},
					{field:'daqty',title:'<?php echo getCatalog('daqty') ?>'},
					{field:'stok',title:'<?php echo getCatalog('stok') ?>'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
					{field:'rakasal',title:'<?php echo getCatalog('storagebinfrom') ?>'},
					{field:'raktujuan',title:'<?php echo getCatalog('storagebinto') ?>'},
					{field:'itemtext',title:'<?php echo getCatalog('itemtext') ?>'},
				]],
				onResize:function(){
						$('#dg-transstockin').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-transstockin').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-transstockin').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('transstockin/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-transstockin').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'transstockid',
		editing: false,
		columns:[[
		{
field:'transstockid',
title:'<?php echo getCatalog('transstockid') ?>',
sortable: true,
width:'50px',
formatter: function(value,row,index){
					return value;
					}},
{
field:'transstockno',
title:'<?php echo getCatalog('transstockno') ?>',
sortable: true,
width:'130px',
formatter: function(value,row,index){
                                        return value;
					}},
					{
field:'deliveryadviceid',
title:'<?php echo getCatalog('dano') ?>',
sortable: true,
width:'130px',
formatter: function(value,row,index){
                                        return row.dano;
					}},
{
field:'slocfromid',
title:'<?php echo getCatalog('slocfrom') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
                                        return row.slocfromcode;
					}},
{
field:'sloctoid',
title:'<?php echo getCatalog('slocto') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
                                        return row.sloctocode;
					}},
{
field:'docdate',
title:'<?php echo getCatalog('docdate') ?>',
sortable: true,
width:'80px',
formatter: function(value,row,index){
                                        return value;
					}},
{
field:'headernote',
title:'<?php echo getCatalog('headernote') ?>',
sortable: true,
width:'200px',
formatter: function(value,row,index){
                                        return value;
					}},
{
field:'recordstatustransstock',
title:'<?php echo getCatalog('recordstatus') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
		]]
});
$('#transstockin_search_transstockid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstockin();
			}
		}
	})
});
$('#transstockin_search_transstockno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstockin();
			}
		}
	})
});
$('#transstockin_search_slocfrom').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstockin();
			}
		}
	})
});
$('#transstockin_search_slocto').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstockin();
			}
		}
	})
});
$('#transstockin_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstockin();
			}
		}
	})
});
$('#transstockin_search_dano').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstockin();
			}
		}
	})
});
function searchtransstockin(){
	$('#dg-transstockin').edatagrid('load',{
		transstockid:$('#transstockin_search_transstockid').val(),
		transstockno:$('#transstockin_search_transstockno').val(),
		slocfrom:$('#transstockin_search_slocfrom').val(),
		slocto:$('#transstockin_search_slocto').val(),
		headernote:$('#transstockin_search_headernote').val(),
		dano:$('#transstockin_search_dano').val(),
	});
}
function approvetsin() {
	var ss = [];
	var rows = $('#dg-transstockin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transstockin/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			tutup_loading();
            show('Message',data.msg);
			$('#dg-transstockin').edatagrid('reload');				
		} ,
		'cache':false});
};

function canceltsin() {
	var ss = [];
	var rows = $('#dg-transstockin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transstockin/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
            tutup_loading();
			show('Message',data.msg);
			$('#dg-transstockin').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdftransstockin() {
	var ss = [];
	var rows = $('#dg-transstockin').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	window.open('<?php echo $this->createUrl('transstock/downpdf') ?>?id='+ss);
}

function addTS() {
		$('#dlg-transstockin').dialog('open');
		$('#ff-transstockin-modif').form('clear');
		$('#ff-transstockin-modif').form('load','<?php echo $this->createUrl('transstockin/GetData') ?>');
};

function editTSin($i) {
	var row = $('#dg-transstockin').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('apptsin') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-transstockin').dialog('open');
		$('#ff-transstockin-modif').form('load',row);
		$('#dg-transstockindet').datagrid('load',{'id':row.transstockid});
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormTS(){
	$('#ff-transstockin-modif').form('submit',{
		url:'<?php echo $this->createUrl('transstockin/save') ?>',
		onSubmit:function(){
                tutup_loading();
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-transstockin').datagrid('reload');
        $('#dlg-transstockin').dialog('close');
			}
    }
	});	
};

function clearFormTS(){
		$('#ff-transstockin-modif').form('clear');
};

function cancelFormTS(){
		$('#dlg-transstockin').dialog('close');
        tutup_loading();
};

$('#ff-transstockin-modif').form({
	onLoadSuccess: function(data) {
			$('#docdate').datebox('setValue', data.docdate);
		$('#dg-transstockindet').datagrid({
			queryParams: {
				id: $('#transstockid').val()
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

$('#dg-transstockindet').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: true,
	idField:'transstockdetid',
	editing: true,
	toolbar:'#tb-transstockindet',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('transstockin/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('transstockin/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('transstockin/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('transstockin/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-transstockindet').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeSave: function(index){
		var ixs = $('#dg-transstockin').edatagrid('getSelected');
		var row = $('#dg-transstockindet').edatagrid('getSelected');
		if (ixs)
		{
			row.transstockinid = $('#transstockinid').val();
		}
	},
	columns:[[
	{
		field:'transstockid',
		title:'<?php echo getCatalog('transstockinid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'transstockdetid',
		title:'<?php echo getCatalog('transstockindetid') ?>',
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
		readonly:true,
		sortable: true,
		width:'250px',
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
				readonly:true,
			}
		},
		sortable: true,
		width:'120px',
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'unitofmeasureid',
		title:'<?php echo getCatalog('uom') ?>',
		required:true,
		readonly:true,
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
	{
		field:'storagebinid',
		title:'<?php echo getCatalog('storagebinfrom') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'storagebinid',
						textField:'description',
						url:'<?php echo Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						readonly:true,
						queryParams:{
							slocid:1
						},
						onBeforeLoad: function(param) {
							param.slocid = $('#slocfromid').combogrid('getValue')
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:50},
							{field:'sloccode',title:'<?php echo getCatalog('sloccode')?>',width:50},
						]]
				}	
			},
			width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.rakasal;
		}
	},
        {
		field:'storagebintoid',
		title:'<?php echo getCatalog('storagebinto') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'storagebinid',
						textField:'description',
						url:'<?php echo Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true,
							slocid:1
						},
						onBeforeLoad: function(param) {
							param.slocid = $('#sloctoid').combogrid('getValue')
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:50},
							{field:'sloccode',title:'<?php echo getCatalog('sloccode')?>',width:50},
						]]
				}	
			},
			width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.raktujuan;
		}
	},
	{
		field:'itemtext',
		title:'<?php echo getCatalog('itemtext') 

?>',
		editor:'text',
		width:'200px',
		multiline:true,
		sortable: true,
		formatter: function(value,row,index){
								return value;
		}
	},
	]]
});
</script>
