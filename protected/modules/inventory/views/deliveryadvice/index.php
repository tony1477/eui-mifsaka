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
<table id="dg-deliveryadvice" style="width:1200px;height:97%"></table>
<div id="tb-deliveryadvice">
	<?php
 if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>
		<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addDA()" id="addda"></a>
		<a href="javascript:void(0)" title="Rubah"class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editDA()" id="editda"></a>
	<?php }?>
	<?php if (CheckAccess($this->menuname, $this->ispost) == 1) {  ?>
		<a href="javascript:void(0)" title="Approve"class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="approveda()" id="approveda"></a>
<?php }?>
        <?php if (CheckAccess($this->menuname, $this->isreject) == 1) {  ?>
		<a href="javascript:void(0)" title="Reject"class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelda()" id="rejectda"></a>
<?php }?>
	<?php if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfdeliveryadvice()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL"class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsdeliveryadvice()"></a>    
		<a href="javascript:void(0)" title="List Material Yang Tidak ada di Gudang Sumber"class="easyui-linkbutton" iconCls="icon-box" plain="true" onclick="downpdf1()"></a>
		<a href="javascript:void(0)" title="List Material Yang Tidak ada di Gudang Tujuan"class="easyui-linkbutton" iconCls="icon-box2" plain="true" onclick="downpdf2()"></a>
		<a href="javascript:void(0)" title="List Material Yang Satuannya Tidak sama dengan Data Gudang Sumber"class="easyui-linkbutton" iconCls="icon-box" plain="true" onclick="downpdf3()"></a>		
		<a href="javascript:void(0)" title="List Material Yang Satuannya Tidak sama dengan Data Gudang Tujuan"class="easyui-linkbutton" iconCls="icon-box2" plain="true" onclick="downpdf4()"></a>
		<a href="javascript:void(0)" title="List Product Yang Gudang Sumber Belum Dicentang"class="easyui-linkbutton" iconCls="icon-box" plain="true" onclick="downpdf5()"></a>
<?php }?>
<table>
<tr>
<td><?php echo getCatalog('deliveryadviceid')?></td>
<td><input class="easyui-textbox" id="deliveryadvice_search_deliveryadviceid" style="width:150px"></td>
<td><?php echo getCatalog('dano')?></td>
<td><input class="easyui-textbox" id="deliveryadvice_search_dano" style="width:150px" /></td>
<td><?php echo getCatalog('docdate')?></td>
<td><input class="easyui-textbox" id="deliveryadvice_search_dadate" style="width:150px" /></td>
</tr>
<tr>
<td><?php echo getCatalog('useraccess')?></td>
<td><input class="easyui-textbox" id="deliveryadvice_search_useraccessid" style="width:150px"></td>
<td><?php echo getCatalog('sloccode')?></td>
<td><input class="easyui-textbox" id="deliveryadvice_search_slocid" style="width:150px"></td>
<td><?php echo getCatalog('headernote')?></td>
<td><input class="easyui-textbox" id="deliveryadvice_search_headernote" style="width:150px"><a href="javascript:void(0)" title="Cari"class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchdeliveryadvice()"></a></td>
</tr>
</table>
</div>

<div id="dlg-deliveryadvice" class="easyui-dialog" title="Delivery Advice" style="width:auto;height:600px" 
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
                    $('.ajax-loader').css('visibility', 'hidden');
					$('#dlg-deliveryadvice').dialog('close');
			}
		},
	]	
	">
	<form id="ff-deliveryadvice-modif" method="post" data-options="novalidate:true">
	<input type="hidden" name="deliveryadviceid" id="deliveryadviceid" />
		<table cellpadding="5">
      <tr>
				<td><?php echo getCatalog('dadate')?></td>
				<td><input class="easyui-datebox" type="text" id="dadate" name="dadate" data-options="formatter:dateformatter,required:true,parser:dateparser" /></td>
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
				<td><?php echo getCatalog('headernote')?></td>
				<td><input class="easyui-textbox" name="headernote" data-options="multiline:true,required:true" style="width:300px;height:100px"/></td>
			</tr>
		</table>
	</form>
		<div class="easyui-tabs" style="width:auto;height:400px">
			<div title="Detail" style="padding:5px">
				<table id="dg-deliveryadvicedetail"  style="width:100%;height:300px">
				</table>
				<div id="tb-deliveryadvicedetail">
					<a href="javascript:void(0)" title="Tambah"class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg-deliveryadvicedetail').edatagrid('addRow')"></a>
					<a href="javascript:void(0)" title="Simpan"class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="javascript:$('#dg-deliveryadvicedetail').edatagrid('saveRow')"></a>
					<a href="javascript:void(0)" title="Kembali"class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg-deliveryadvicedetail').edatagrid('cancelRow')"></a>
					<a href="javascript:void(0)" title="Hapus"class="easyui-linkbutton" iconCls="icon-purge" plain="true" onclick="javascript:$('#dg-deliveryadvicedetail').edatagrid('destroyRow')"></a>
				</div>
			</div>
	</div>
</div>

<script type="text/javascript">
$('#addda').click(function(){
    tampil_loading();
});
    
$('#editda').click(function(){
    tampil_loading();
});
    
$('#approveda').click(function(){
    tampil_loading();
});

$('#rejectda').click(function(){
    tampil_loading();
});

function tampil_loading(){
    $('.ajax-loader').css('visibility', 'visible');
}

function tutup_loading(){
    $('.ajax-loader').css('visibility', 'hidden');
}

$('#deliveryadvice_search_deliveryadviceid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvice();
			}
		}
	})
});
$('#deliveryadvice_search_dano').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvice();
			}
		}
	})
});
$('#deliveryadvice_search_slocid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvice();
			}
		}
	})
});
$('#deliveryadvice_search_headernote').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvice();
			}
		}
	})
});
$('#deliveryadvice_search_dadate').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvice();
			}
		}
	})
});
$('#deliveryadvice_search_useraccessid').textbox({
	inputEvents:$.extend({},$.fn.textbox.defaults.inputEvents,{
		keyup:function(e){
			if (e.keyCode == 13) {
				searchdeliveryadvice();
			}
		}
	})
});
$('#dg-deliveryadvice').edatagrid({
		iconCls: 'icon-edit',	
		singleSelect: false,
		toolbar:'#tb-deliveryadvice',
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
				url:'<?php echo $this->createUrl('deliveryadvice/indexdetail',array('grid'=>true)) ?>?id='+row.deliveryadviceid,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'<?php echo getCatalog('pleasewait') ?>',
				pagination: true,
				height:'auto',
				width:'100%',
				columns:[[
          {field:'productname',title:'<?php echo getCatalog('productname') ?>'},
					{field:'qty',title:'<?php echo getCatalog('qty') ?>'},
					{field:'prqty',title:'<?php echo getCatalog('prqty') ?>'},
					{field:'giqty',title:'<?php echo getCatalog('trqty') ?>',
						formatter: function(value,row,index){
							if (row.wtfs == 1) {
							return '<div style="background-color:red;color:white">'+value+'</div>';
						} else {
							return value;
						}
						}},
					{field:'grqty',title:'<?php echo getCatalog('grqty') ?>'},
					{field:'poqty',title:'<?php echo getCatalog('poqty') ?>'},
					{field:'stock',title:'<?php echo getCatalog('stock') ?>',
					formatter: function(value,row,index){
						if (row.wstock == 1) {
							return '<div style="background-color:red;color:white">'+value+'</div>';
						} else {
							return value;
						}
						}},
					{field:'uomcode',title:'<?php echo getCatalog('uomcode') ?>'},
					{field:'requestedbycode',title:'<?php echo getCatalog('requestedbycode') ?>'},
					{field:'reqdate',title:'<?php echo getCatalog('reqdate') ?>'},
					{field:'tosloccode',title:'<?php echo getCatalog('sloc') ?>'},
					{field:'itemtext',title:'<?php echo getCatalog('itemtext') ?>'},
				]],
				onResize:function(){
						$('#dg-deliveryadvice').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
						setTimeout(function(){
								$('#dg-deliveryadvice').datagrid('fixDetailRowHeight',index);
						},0);
				}
			});
			$('#dg-deliveryadvice').datagrid('fixDetailRowHeight',index);
		},
    url: '<?php echo $this->createUrl('deliveryadvice/index',array('grid'=>true)) ?>',
		onSuccess: function(index,row){
			show('Message',row.msg);
			$('#dg-deliveryadvice').edatagrid('reload');
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
field:'companyid',
title:'<?php echo getCatalog('company') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.companyname;
					}},
{
field:'dano',
title:'<?php echo getCatalog('dano') ?>',
sortable: true,
width:'130px',
formatter: function(value,row,index){
	if (row.warna == 1) {
				return '<div style="background-color:cyan;color:white">'+value+'</div>';
			} else {
				return value;
			}
			}},					
{
field:'useraccessid',
title:'<?php echo getCatalog('useraccess') ?>',
sortable: true,
width:'150px',
formatter: function(value,row,index){
						return row.username;
					}},
{
field:'slocid',
title:'<?php echo getCatalog('sloc') ?>',
sortable: true,
width:'250px',
formatter: function(value,row,index){
						return row.sloccode;
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
width:'150px',
formatter: function(value,row,index){
						return row.recordstatusda;
					}},
		]]
});
function searchdeliveryadvice(){
	$('#dg-deliveryadvice').edatagrid('load',{
	deliveryadviceid:$('#deliveryadvice_search_deliveryadviceid').val(),
        dano:$('#deliveryadvice_search_dano').val(),
        dadate:$('#deliveryadvice_search_dadate').val(),
        useraccessid:$('#deliveryadvice_search_useraccessid').val(),
        slocid:$('#deliveryadvice_search_slocid').val(),
        headernote:$('#deliveryadvice_search_headernote').val()	
	});
}
function approveda() {
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('deliveryadvice/approve') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
            tutup_loading();
			show('Message',data.msg);
			$('#dg-deliveryadvice').edatagrid('reload');				
		} ,
		'cache':false});
};
function cancelda() {
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	jQuery.ajax({'url':'<?php echo $this->createUrl('deliveryadvice/delete') ?>',
		'data':{'id':ss},'type':'post','dataType':'json',
		'success':function(data)
		{
            tutup_loading();
			show('Message',data.msg);
			$('#dg-deliveryadvice').edatagrid('reload');				
		} ,
		'cache':false});
};
function downpdfdeliveryadvice() 
{
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf') ?>?id='+ss);
}
function downxlsdeliveryadvice() 
{
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downxls') ?>?id='+ss);
}
    function downpdf1() {
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf1') ?>?id='+ss);
    }
      function downpdf2() {
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf2') ?>?id='+ss);
    }
     function downpdf3() {
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf3') ?>?id='+ss);
     }
    function downpdf4() {
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf4') ?>?id='+ss);
     }
		 function downpdf5() {
	var ss = [];
	var rows = $('#dg-deliveryadvice').edatagrid('getSelections');
	for(var i=0; i<rows.length; i++){
			var row = rows[i];
			ss.push(row.deliveryadviceid);
	}
	window.open('<?php echo $this->createUrl('deliveryadvice/downpdf5') ?>?id='+ss);
     }
function addDA() {
		$('#dlg-deliveryadvice').dialog('open');
		$('#ff-deliveryadvice-modif').form('clear');
		$('#ff-deliveryadvice-modif').form('load','<?php echo $this->createUrl('deliveryadvice/getdata') ?>');
		$('#dadate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});
};

function editDA($i) {
	var row = $('#dg-deliveryadvice').datagrid('getSelected');
	var docmax = <?php echo CheckDoc('appda') ?>;
	var docstatus = row.recordstatus;
	if(row) {
		if (docstatus == docmax) 
		{
			show('Message','<?php echo getCatalog('docreachmaxstatus')?>');
		}
		else
		{
			$('#dlg-deliveryadvice').dialog('open');
			$('#ff-deliveryadvice-modif').form('load',row);
            $('#dg-deliveryadvice').datagrid('load',{'id':row.deliveryadviceid});
		}
	}
	else {
		show('Message','chooseone');
	}
};

function submitFormDA(){
	$('#ff-deliveryadvice-modif').form('submit',{
		url:'<?php echo $this->createUrl('deliveryadvice/save') ?>',
		onSubmit:function(){
                tutup_loading();
				return $(this).form('enableValidation').form('validate');
		},
		success:function(data){
			var data = eval('(' + data + ')');  // change the JSON string to javascript object
			show('Pesan',data.msg)
			if (data.isError == false){
        $('#dg-deliveryadvice').datagrid('reload');
        $('#dlg-deliveryadvice').dialog('close');
			}
    }
	});	
};

function clearFormDA(){
		$('#ff-deliveryadvice-modif').form('clear');
};

function cancelFormDA(){
		$('#dlg-deliveryadvice').dialog('close');
        tutup_loading();
};

$('#ff-deliveryadvice-modif').form({
	onLoadSuccess: function(data) {
		$('#dg-deliveryadvicedetail').datagrid({
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

$('#dg-deliveryadvicedetail').edatagrid({
	iconCls: 'icon-edit',	
	singleSelect: false,
	idField:'deliveryadvicedetailid',
	editing: true,
	toolbar:'#tb-deliveryadvicedetail',
	fitColumn: true,
	pagination:true,
	url: '<?php echo $this->createUrl('deliveryadvice/searchdetail',array('grid'=>true)) ?>',
	saveUrl: '<?php echo $this->createUrl('deliveryadvice/savedetail',array('grid'=>true))?>',
	updateUrl: '<?php echo $this->createUrl('deliveryadvice/savedetail',array('grid'=>true))?>',
	destroyUrl: '<?php echo $this->createUrl('deliveryadvice/purgedetail',array('grid'=>true))?>',
	onSuccess: function(index,row){
		show('Pesan',row.msg);
		$('#dg-deliveryadvicedetail').edatagrid('reload');
	},
	onError: function(index,row){
		show('Pesan',row.msg);
		$('#dg-deliveryadvicedetail').edatagrid('reload');
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
								var productid = $("#dg-deliveryadvicedetail").datagrid("getEditor", {index: index, field:"productid"});
								var unitofmeasureid = $("#dg-deliveryadvicedetail").datagrid("getEditor", {index: index, field:"unitofmeasureid"});
								var slocid = $("#dg-deliveryadvicedetail").datagrid("getEditor", {index: index, field:"slocid"});
								jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('common/productplant/getdatafpb') ?>',
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
