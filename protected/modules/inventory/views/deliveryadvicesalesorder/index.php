<table id="dg-deliveryadvicesalesorder" style="width:1200px;height:97%"></table>
<div id="tb-deliveryadvicesalesorder">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addDA()"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editDA()"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveda()"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelda()"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfdeliveryadvicesalesorder()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsdeliveryadvicesalesorder()"></a>
		<a href="javascript:void(0)" title="List Material Yang Tidak ada di Gudang Sumber"class="easyui-linkbutton" iconCls="icon-box" plain="true" onclick="downpdf1()"></a>
		<a href="javascript:void(0)" title="List Material Yang Tidak ada di Gudang Tujuan"class="easyui-linkbutton" iconCls="icon-box2" plain="true" onclick="downpdf2()"></a>
		<a href="javascript:void(0)" title="List Material Yang Satuannya Tidak sama dengan Data Gudang Sumber"class="easyui-linkbutton" iconCls="icon-box" plain="true" onclick="downpdf3()"></a>		
		<a href="javascript:void(0)" title="List Material Yang Satuannya Tidak sama dengan Data Gudang Tujuan"class="easyui-linkbutton" iconCls="icon-box2" plain="true" onclick="downpdf4()"></a>
		<a href="javascript:void(0)" title="List Product Yang Gudang Sumber Belum Dicentang"class="easyui-linkbutton" iconCls="icon-box" plain="true" onclick="downpdf5()"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('deliveryadviceid')?></td>
<td><input class="easyui-textbox" id="deliveryadvicesalesorder_search_deliveryadviceid" style="width:150px"></td>
<td><?php echo getCatalog('dano')?></td>
<td><input class="easyui-textbox" id="deliveryadvicesalesorder_search_dano" style="width:150px"></td>
<td><?php echo getCatalog('sono')?></td>
<td><input class="easyui-textbox" id="deliveryadvicesalesorder_search_soheaderid" style="width:150px"></td>
<td><?php echo getCatalog('dadate')?></td></td>
<td><input class="easyui-textbox" id="deliveryadvicesalesorder_search_dadate" style="width:150px"></td>
</tr>
<tr>
<td><?php echo getCatalog('useraccess')?></td>
<td><input class="easyui-textbox" id="deliveryadvicesalesorder_search_useraccessid" style="width:150px"></td>
<td><?php echo getCatalog('sloccode')?></td>
<td><input class="easyui-textbox" id="deliveryadvicesalesorder_search_slocid" style="width:150px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="deliveryadvicesalesorder_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchdeliveryadvicesalesorder()"></a></td>
</tr>
</table>
</div>

<div id="dlg-deliveryadvicesalesorder" class="easyui-dialog" title="Delivery Advice SO" style="width:auto;height:600px" 
	closed="true" data-options="
	resizable:true,
	modal:true,
	toolbar: [
		{
			text:'<?php echo getCatalog('save')?>',
			iconCls:'icon-save',
			handler:function(){
					submitFormDA();
			}
		},
		{
			text:'<?php echo getCatalog('cancel')?>',
			iconCls:'icon-cancel',
			handler:function(){
					$('#dlg-deliveryadvicesalesorder').dialog('close');
			}
		},
	]	
	">
	<form id="ff-deliveryadvicesalesorder-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="deliveryadviceid" id="deliveryadviceid"></input>
		<table cellpadding="5">
      <tr>
				<td><?php echo getCatalog('dadate')?></td>
				<td><input class="easyui-datebox" type="text" id="dadate" name="dadate" data-options="formatter:dateformatter,required:true,parser:dateparser"></input></td>
			</tr>
			<tr>
				<td><?php echo getCatalog('sloc')?></td>
				<td><select class="easyui-combogrid" name="slocid" id="slocid" style="width:250px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'slocid',
								textField: 'sloccode',
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('common/sloc/indextrx',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
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
				<td><?php echo getCatalog('soheader')?></td>
				<td><select class="easyui-combogrid" name="soheaderid" id="soheaderid" style="width:250px" data-options="
								panelWidth: 500,
								idField: 'soheaderid',
								textField: 'sono',
								required:true,
								pagination:true,
								url: '<?php echo Yii::app()->createUrl('order/soheader/indexcombo',array('grid'=>true)) ?>',
								method: 'get',
								mode: 'remote',
								onHidePanel: function(){
									jQuery.ajax({'url':'<?php echo $this->createUrl('deliveryadvicesalesorder/generateso') ?>',
											'data':{
													'id':$('#soheaderid').combogrid('getValue'),
                          'slocid':$('#slocid').combogrid('getValue'),
													'hid':$('#deliveryadviceid').val()
												},
												'type':'post',
												'dataType':'json',
											'success':function(data)
											{
												$('#dg-deliveryadvicesalesorder').edatagrid('reload');		
												$('#dg-deliveryadvicesalesorderdetail').edatagrid('reload');				
											},
											'cache':false});					
									$('#dg-deliveryadvicesalesorderdetail').datagrid({
										queryParams: {
											id: $('#deliveryadviceid').val()
										}
									});
								},
								columns: [[
										{field:'soheaderid',title:'<?php echo getCatalog('soheaderid') ?>'},
										{field:'sono',title:'<?php echo getCatalog('sono') ?>'},
										{field:'sodate',title:'<?php echo getCatalog('sodate') ?>'},
										{field:'companyname',title:'<?php echo getCatalog('companyname') ?>'},
										{field:'customername',title:'<?php echo getCatalog('customer') ?>'},
								]],
								fitColumns: true
						">
				</select></td>
			
      			<tr>
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"></input></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="Detail" style="padding:5px">
				<table id="dg-deliveryadvicesalesorderdetail"  style="width:100%;height:300px">
				</table>
				<div id="tb-deliveryadvicesalesorderdetail">					
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-deliveryadvicesalesorderdetail').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-deliveryadvicesalesorderdetail').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-deliveryadvicesalesorderdetail').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$('#deliveryadvicesalesorder_search_deliveryadviceid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvicesalesorder();
			}
		}
	})
});
$('#deliveryadvicesalesorder_search_dano').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvicesalesorder();
			}
		}
	})
});
$('#deliveryadvicesalesorder_search_slocid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvicesalesorder();
			}
		}
	})
});
$('#deliveryadvicesalesorder_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvicesalesorder();
			}
		}
	})
});
$('#deliveryadvicesalesorder_search_useraccessid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvicesalesorder();
			}
		}
	})
});
$('#deliveryadvicesalesorder_search_soheaderid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvicesalesorder();
			}
		}
	})
});
$('#deliveryadvicesalesorder_search_dadate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvicesalesorder();
			}
		}
	})
});
$('#dg-deliveryadvicesalesorder').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-deliveryadvicesalesorder',
		pagination: true,
		fitColumns:true,
		ctrlSelect:true,
		onDblClickRow: function (index,row) {
			editDA(index);
		},
		rowStyler: function(index,row){
			if (row.count >= 1){
					return 'background-color:blue;color:#fff;font-weight:bold;';
			}
		},
		view: detailview,
		detailFormatter:function(index,row){
				return '<div style="padding:2px"><table class="ddv-dadetail"></table></div>';
		},
   onExpandRow: function(index,row){
			var ddvdadetail = $(this).datagrid('getRowDetail',index).find('table.ddv-dadetail');
			ddvdadetail.datagrid({
				url:'<?php echo $this->createUrl('deliveryadvicesalesorder/indexdetail',array('grid'=>true)) ?>?id='+row.deliveryadviceid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo getCatalog('pleasewait') ?>',
				pagination: true,
				height:'auto',
				width:'1300px',
				columns:[[
          {field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>',align:'right'},
					{field:'prqty',title:'<?php echo getCatalog('prqty') ?>',align:'right'},
			{field:'giqty',title:'<?php echo getCatalog('tfsqty') ?>',align:'right'},
					{field:'poqty',title:'<?php echo getCatalog('poqty') ?>',align:'right'},
					{field:'stock',title:'<?php echo GetCatalog('stock') ?>',align:'right'},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
					{field:'requestedbycode',title:'<?php echo getCatalog('requestedbycode') ?>'},
					{field:'reqdate',title:'<?php echo getCatalog('reqdate') ?>'},
					{field:'tosloccode',title:'<?php echo getCatalog('sloc') ?>'},
					{field:'itemtext',title:'<?php echo getCatalog('itemtext') ?>'},
				]],
				onResize:function(){
						$('#dg-deliveryadvicesalesorder').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-deliveryadvicesalesorder').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-deliveryadvicesalesorder').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('deliveryadvicesalesorder/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-deliveryadvicesalesorder').edatagrid('reload');
		},
		onError: function(index,row){
			show('Message',row.msg);
		},
		idField:'deliveryadviceid',
		editing: false,
		columns:[[
		{
field:'deliveryadviceid',
title:'<?php echo getCatalog('deliveryadviceid') ?>',
sortable: true,
formatter: function(value,row,index){
					if (row.recordstatus == 1) {
				return '<div style="background-color:green;color:white">'+value+'</div>';
			} else 
				if (row.recordstatus == 2) {
					return '<div style="background-color:yellow;color:black">'+value+'</div>';
				} else if (row.recordstatus == 3) {
					return '<div style="background-color:red;color:white">'+value+'</div>';
				} else if (row.recordstatus == 0) {
					return '<div style="background-color:black;color:white">'+value+'</div>';
				}
					}},
{
field:'dadate',
title:'<?php echo getCatalog('dadate') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return value;
					}},
{
field:'dano',
title:'<?php echo getCatalog('dano') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
	if (row.warna == 1) {
				return '<div style="background-color:cyan;color:black">'+value+'</div>';
			} else { return value; }
					}},
					{
field:'soheaderid',
title:'<?php echo getCatalog('soheader') ?>',
sortable: true,
width:'120px',
formatter: function(value,row,index){
	return row.sono;
					}},
					{
field:'useraccessid',
title:'<?php echo getCatalog('useraccess') ?>',
sortable: true,
width:'100px',
formatter: function(value,row,index){
						return row.username;
					}},
{
field:'slocid',
title:'<?php echo getCatalog('sloc') ?>',
sortable: true,
width:'300px',
formatter: function(value,row,index){
						return row.sloccode;
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
field:'recordstatusda',
title:'<?php echo getCatalog('recordstatus') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return value;
					}},
		]]
});
function searchdeliveryadvicesalesorder(value){
	$('#dg-deliveryadvicesalesorder').edatagrid('load',{
	deliveryadviceid:$('#deliveryadvicesalesorder_search_deliveryadviceid').val(),
        dadate:$('#deliveryadvicesalesorder_search_dadate').val(),
        dano:$('#deliveryadvicesalesorder_search_dano').val(),
	soheaderid:$('#deliveryadvicesalesorder_search_soheaderid').val(),
        useraccessid:$('#deliveryadvicesalesorder_search_useraccessid').val(),
        slocid:$('#deliveryadvicesalesorder_search_slocid').val(),
        headernote:$('#deliveryadvicesalesorder_search_headernote').val(),
	});
}
function approveda() {
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('deliveryadvicesalesorder/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-deliveryadvicesalesorder').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelda() {
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('deliveryadvicesalesorder/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
			show('Message',data.msg);
			$('#dg-deliveryadvicesalesorder').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfdeliveryadvicesalesorder() 
{
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvicesalesorder/downpdf') ?>?id='+ss);
}
function downxlsdeliveryadvicesalesorder() 
{
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvicesalesorder/downxls') ?>?id='+ss);
}
    function downpdf1() {
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf1') ?>?id='+ss);
    }
      function downpdf2() {
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf2') ?>?id='+ss);
    }
     function downpdf3() {
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf3') ?>?id='+ss);
     }
    function downpdf4() {
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf4') ?>?id='+ss);
     }
		 function downpdf5() {
	var ss = [];
	var rows = $('#dg-deliveryadvicesalesorder').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf5') ?>?id='+ss);
     }
function addDA() {
		$('#dlg-deliveryadvicesalesorder').dialog('open');
		$('#ff-deliveryadvicesalesorder-modif').form('clear');
		$('#ff-deliveryadvicesalesorder-modif').form('load','<?php echo $this->createUrl('deliveryadvicesalesorder/getdata') ?>');
		$('#dadate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editDA($i) {
	var row = $('#dg-deliveryadvicesalesorder').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appda') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
		$('#dlg-deliveryadvicesalesorder').dialog('open');
		$('#ff-deliveryadvicesalesorder-modif').form('load',row);
		$('#dg-deliveryadvicesalesorderdetail').datagrid('load',{'id':row.deliveryadviceid});
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormDA(){
	$('#ff-deliveryadvicesalesorder-modif').form('submit',{
		url:'<?php echo $this->createUrl('deliveryadvicesalesorder/save') ?>',
		onSubmit:function(){
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-deliveryadvicesalesorder').datagrid('reload');
        $('#dlg-deliveryadvicesalesorder').dialog('close');
			}
    }
	});	
};

function clearFormDA(){
		$('#ff-deliveryadvicesalesorder-modif').form('clear');
};

function cancelFormDA(){
		$('#dlg-deliveryadvicesalesorder').dialog('close');
};

$('#ff-deliveryadvicesalesorder-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-deliveryadvicesalesorderdetail').datagrid({
			queryParams: {
				id: $('#deliveryadviceid').val()
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

$('#dg-deliveryadvicesalesorderdetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	idField:'deliveryadvicedetailid',
	editing: true,
	toolbar:'#tb-deliveryadvicesalesorderdetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('deliveryadvicesalesorder/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('deliveryadvicesalesorder/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('deliveryadvicesalesorder/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('deliveryadvicesalesorder/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-deliveryadvicesalesorderdetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
		$('#dg-deliveryadvicesalesorderdetail').edatagrid('reload');
	},
	onBeforeEdit:function(index,row) {
		row.deliveryadviceid = $('#deliveryadviceid').val();
	},
	columns:[[
	{
		field:'deliveryadviceid',
		title:'<?php echo getCatalog('deliveryadviceid') ?>',
		hidden:true,
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	{
		field:'deliveryadvicedetailid',
		title:'<?php echo getCatalog('deliveryadvicedetailid') ?>',
		sortable: true,
		hidden:true,
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
								var productid = $("#dg-deliveryadvicesalesorderdetail").datagrid("getEditor", {index: index, field:"productid"});
								var unitofmeasureid = $("#dg-deliveryadvicesalesorderdetail").datagrid("getEditor", {index: index, field:"unitofmeasureid"});
								var slocid = $("#dg-deliveryadvicesalesorderdetail").datagrid("getEditor", {index: index, field:"slocid"});
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
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				required:true,
			}
		},
		width:'100px',
		required:true,
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
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'<?php echo Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
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
			width:'100px',
		sortable: true,
		formatter: function(value,row,index){
							return row.uomcode;
		}
	},
        {
		field:'reqdate',
		title:'<?php echo getCatalog('reqdate') ?>',
                editor: {
								type:'datebox',
								options:{required:true
								}
								},
		sortable: true,
		width:'130px',
		formatter: function(value,row,index){
							return value;
		}
	},
	 {
		field:'requestedbyid',
		title:'<?php echo getCatalog('requestedby') ?>',
		editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'requestedbyid',
						textField:'requestedbycode',
						url:'<?php echo $this->createUrl('requestedby/index',array('grid'=>true)) ?>',
						fitColumns:true,
						pagination:true,
						queryParams:{
							combo:true
						},
						loadMsg: '<?php echo getCatalog('pleasewait')?>',
						columns:[[
							{field:'requestedbyid',title:'<?php echo getCatalog('requestedbyid')?>',width:50},
							{field:'requestedbycode',title:'<?php echo getCatalog('requestedbycode')?>',width:200},
							{field:'description',title:'<?php echo getCatalog('description')?>',width:200}
						]]
				}	
			},
			width:'150px',
		sortable: true,
		formatter: function(value,row,index){
							return row.requestedbycode;
		}
	},
        {
                field:'slocid',
                title:'<?php echo getCatalog('sloc') ?>',
                editor:{
                                type:'combogrid',
                                options:{
                                            panelWidth:450,
                                            mode : 'remote',
                                            method:'get',
                                            idField:'slocid',
                                            textField:'sloccode',
                                            url:'<?php echo Yii::app()->createUrl('common/sloc/indextrxda',array('grid'=>true)) ?>',
                                            fitColumns:true,
                                            pagination:true,
                                            required:true,
                                            width:'180px',
                                            loadMsg: '<?php echo getCatalog('pleasewait')?>',
                                            columns:[[
                                            {field:'slocid',title:'<?php echo getCatalog('slocid')?>'},
                                            {field:'sloccode',title:'<?php echo getCatalog('sloccode')?>'},
                                            {field:'description',title:'<?php echo getCatalog('description')?>'},
                                            ]]
                                }	
                },
                sortable: true,
		width:'150px',
                formatter: function(value,row,index){
                return row.tosloccode;
                }
        },
        {
		field:'itemtext',
		title:'<?php echo getCatalog('itemtext') 

?>',
    editor: 'textbox',
		width:'200px',
		sortable: true,
		formatter: function(value,row,index){
							return value;
		}
	},
	]]
});
</script>
