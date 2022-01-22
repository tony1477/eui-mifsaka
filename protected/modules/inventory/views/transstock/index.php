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
<table id="dg-transstock" style="width:1200px;height:97%"></table>
<div id="tb-transstock">
	<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addTS()" id="addres"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editTS()" id="editts"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approvets()" id="approvets"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelts()" id="rejectts"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdftransstock()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlstransstock()"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('transstockid')?></td>
<td><input class="easyui-textbox" id="transstock_search_transstockid" style="width:100px"></td>
<td><?php echo getCatalog('transstockno')?></td>
<td><input class="easyui-textbox" id="transstock_search_transstockno" style="width:100px"></td>
<td><?php echo getCatalog('dano')?></td>
<td><input class="easyui-textbox" id="transstock_search_dano" style="width:100px"></td>
</tr>
<tr>
<td><?php echo getCatalog('slocfrom')?></td>
<td><input class="easyui-textbox" id="transstock_search_slocfrom" style="width:100px"></td>
<td><?php echo getCatalog('slocto')?></td>
<td><input class="easyui-textbox" id="transstock_search_slocto" style="width:100px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="transstock_search_headernote" style="width:100px"><a href="javascript:void(0)" title="Cari" id="transstock_search" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchtransstock()"></a></td>
</tr>
</table>
</div>
<div id="dlg-transstock" class="easyui-dialog" title="Transfer Gudang" style="width:auto;height:600px" 
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
					$('#dlg-transstock').dialog('close');
			}
		},
	]	
	">
	<form id="ff-transstock-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="transstockid" id="transstockid"/>
		<table cellpadding="5">
                        <tr>
				<td><?php echo getCatalog('docdate')?></td>
				<td><input class="easyui-datebox" type="text" id="docdate" name="docdate" data-options="formatter:dateformatter,required:true,parser:dateparser"/></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('dano')?></td>
				<td><select class="easyui-combogrid" name="deliveryadviceid" id="deliveryadviceid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'deliveryadviceid',
								textField: 'dano',
								pagination:true,
								url: '<?php echo $this->createUrl('deliveryadvice/indextsda',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								required:true,
                onHidePanel: function(){
										jQuery.ajax({'url':'<?php echo $this->createUrl('transstock/generatedetail') ?>',
											'data':{
													'id':$('#deliveryadviceid').combogrid('getValue'),
													'hid':$('#transstockid').val()
												},
											'type':'post',
											'dataType':'json',
											'success':function(data)
											{
												$('#slocfromid').combogrid('setValue',data.slocfromid);
												$('#sloctoid').combogrid('setValue',data.sloctoid);
												$('#headernote').textbox('setValue',data.headernote);
												$('#dg-transstockdet').edatagrid('reload');				
											},
											'cache':false});
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
								url: '<?php echo Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								required:true,
								readonly:true,
                columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo getCatalog('description') ?>'},
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
								url: '<?php echo Yii::app()->createUrl('common/sloc/indextrx',array('grid'=>true)) ?>',
								method: 'get',
								mode:'remote',
								required:true,
								readonly:true,
                columns: [[
										{field:'slocid',title:'<?php echo getCatalog('slocid') ?>'},
										{field:'sloccode',title:'<?php echo getCatalog('sloccode') ?>'},
										{field:'description',title:'<?php echo getCatalog('description') ?>'},
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
		<div class="easyui-tabs" style="width:100%;height:350px">
			<div title="Detail" style="padding:5px">
				<table id="dg-transstockdet"  style="width:100%;height:300px">
				</table>
				<div id="tb-transstockdet">
					<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-transstockdet').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-transstockdet').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-transstockdet').edatagrid('destroyRow')"></a>
				</div>
			</div>
                </div>
</div>

<script type="text/javascript">
$('#addts').click(function(){
    tampil_loading();
});
    
$('#editts').click(function(){
    tampil_loading();
});
    
$('#approvets').click(function(){
    tampil_loading();
});

$('#rejectts').click(function(){
    tampil_loading();
});

function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css('visibility', 'hidden');
}

$('#dg-transstock').edatagrid({
		singleSelect: false,
		toolbar:'#tb-transstock',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		autoRowHeight:true,
		onDblClickRow: function (index,row) {
			editTS(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-transstockdet"></table><table class="ddv-custdisc"></table></div>';
		},
                onExpandRow: function(index,row){
			var ddvtransstockdet = $(this).datagrid('getRowDetail',index).find('table.ddv-transstockdet');
			ddvtransstockdet.datagrid({
				url:'<?php echo $this->createUrl('transstock/indexdetail',array('grid'=>true)) ?>?id='+row.transstockid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				width:'100%', 	
				showFooter:true,
				pagination:true,
				columns:[[
					{field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right'},
					{field:'daqty',title:'<?php echo getCatalog('daqty') ?>',align:'right'},
					{field:'stok',title:'<?php echo getCatalog('stok') ?>',align:'right'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
					{field:'rakasal',title:'<?php echo getCatalog('storagebinfrom') ?>'},
					{field:'raktujuan',title:'<?php echo getCatalog('storagebinto') ?>'},
					{field:'itemtext',title:'<?php echo getCatalog('itemtext') ?>',width:'300px'},
				]],
				onResize:function(){
						$('#dg-transstock').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-transstock').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-transstock').datagrid('fixDetailRowHeight',index);
		},
                url: '<?php echo $this->createUrl('transstock/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-transstock').edatagrid('reload');
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
field:'docdate',
title:'<?php echo getCatalog('docdate') ?>',
sortable: true,
width:'120px',
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
width:'200px',
formatter: function(value,row,index){
						return value;
					}},
		]]
});
$('#transstock_search_transstockid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstock_search_transstockno').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstock_search_slocfrom').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstock_search_slocto').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstock_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
$('#transstock_search_dano').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchtransstock();
			}
		}
	})
});
function searchtransstock(){
	$('#dg-transstock').edatagrid('load',{
		transstockid:$('#transstock_search_transstockid').val(),
		transstockno:$('#transstock_search_transstockno').val(),
		slocfrom:$('#transstock_search_slocfrom').val(),
		slocto:$('#transstock_search_slocto').val(),
		headernote:$('#transstock_search_headernote').val(),
		dano:$('#transstock_search_dano').val(),
	});
}
function approvets() {
	var ss = [];
	var rows = $('#dg-transstock').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transstock/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			tutup_loading();
            show('Message',data.msg);
			$('#dg-transstock').edatagrid('reload');				
		} ,
		'cache':false});
};

function cancelts() {
	var ss = [];
	var rows = $('#dg-transstock').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('transstock/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
            tutup_loading();
			show('Message',data.msg);
			$('#dg-transstock').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdftransstock() {
	var ss = [];
	var rows = $('#dg-transstock').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	window.open('<?php echo $this->createUrl('transstock/downpdf') ?>?id='+ss);
}
function downxlstransstock()
{
	var ss = [];
	var rows = $('#dg-transstock').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.transstockid);
	}
	window.open('<?php echo $this->createUrl('transstock/downxls') ?>?id='+ss);
}
function addTS() {
		$('#dlg-transstock').dialog('open');
		$('#ff-transstock-modif').form('clear');
		$('#ff-transstock-modif').form('load','<?php echo $this->createUrl('transstock/GetData') ?>');
		$('#docdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editTS($i) {
	var row = $('#dg-transstock').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appts') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-transstock').dialog('open');
		$('#ff-transstock-modif').form('load',row);
		$('#dg-transstockdet').datagrid('load',{'id':row.transstockid});
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormTS(){
	$('#ff-transstock-modif').form('submit',{
		url:'<?php echo $this->createUrl('transstock/save') ?>',
		onSubmit:function(){
                tutup_loading();
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-transstock').datagrid('reload');
        $('#dlg-transstock').dialog('close');
			}
    }
	});	
};

function clearFormTS(){
		$('#ff-transstock-modif').form('clear');
};

function cancelFormTS(){
		$('#dlg-transstock').dialog('close');
        tutup_loading();
};

$('#ff-transstock-modif').form({
	onLoadSuccess: function(data) {
			$('#docdate').datebox('setValue', data.docdate);
		$('#dg-transstockdet').datagrid({
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

$('#dg-transstockdet').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	idField:'transstockdetid',
	editing: true,
	ctrlSelect:true,
	toolbar:'#tb-transstockdet',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('transstock/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('transstock/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('transstock/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('transstock/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-transstockdet').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
	},
	onBeforeEdit: function(index,row){
			if (row.qty != undefined) {
			var value = row.qty;
			row.qty = value.replace(".", "");
			}
  	},
	onBeforeSave: function(index){
		var row = $('#dg-transstockdet').edatagrid('getSelected');
		if (row)
		{
			row.transstockid = $('#transstockid').val();
		}
	},
	columns:[[
	{
		field:'transstockid',
		title:'<?php echo getCatalog('transstockid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'transstockdetid',
		title:'<?php echo getCatalog('transstockdetid') ?>',
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
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'<?php echo Yii::app()->createUrl('common/product/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						onChange:function(newValue,oldValue) {
							if ((newValue !== oldValue) && (newValue !== ''))
							{
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $("#dg-transstockdet").datagrid("getEditor", {index: index, field:"productid"});
								var unitofmeasureid = $("#dg-transstockdet").datagrid("getEditor", {index: index, field:"unitofmeasureid"});
								var slocid = $("#dg-transstockdet").datagrid("getEditor", {index: index, field:"slocid"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdata') ?>',
									'data':{'productid':$(productid.target).combogrid("getValue")},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(unitofmeasureid.target).combogrid('setValue',data.uomid);
									} ,
									'cache':false});
							}
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'productid',title:'<?php echo getCatalog('productid')?>',width:50},
							{field:'productname',title:'<?php echo getCatalog('productname')?>',width:200},
						]]
				}	
			},
			width:'350px',
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
				precision:6,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		sortable: true,
		width:'100px',
		formatter: function(value,row,index){
								return value;
		}
	},
	{
		field:'unitofmeasureid',
		title:'<?php echo getCatalog('uom') ?>',
		sortable: true,
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						readonly:true,
						queryParams:{
							combo:true
						},
						required:true,
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'unitofmeasureid',title:'<?php echo getCatalog('unitofmeasureid')?>',width:50},
							{field:'uomcode',title:'<?php echo getCatalog('uomcode')?>',width:200},
						]]
				}	
			},
		width:'120px',
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
						queryParams:{
							slocid:1
						},
						onBeforeLoad: function(param) {
							param.slocid = $('#slocfromid').combogrid('getValue')
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:200},
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
						readonly:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'storagebinid',title:'<?php echo getCatalog('storagebinid')?>',width:50},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:200},
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
